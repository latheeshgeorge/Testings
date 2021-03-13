<?php
	/*
	#################################################################
	# Script Name 		: image_settings.php
	# Description 		: Page for managing the settings for images
	# Coded by 		: Snl
	# Created on		: 14-Jun-2007
	# Modified by		: Sny
	# Modified On		: 20-Jan-2010
	#################################################################
	*/	
	
	$help_msg 			= get_help_messages('EDIT_MAINSHOP_MESS1');
	//# Retrieving the values of super admin from the table
	$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
	$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
	//$fetch_arr_admin 	= $db->fetch_array($res_admin);

?>

<script type="text/javascript">
function handle_expansion(imgobj,mod)
{

	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'product_bestseller':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('product_bestsellertr_details'))
					document.getElementById('product_bestsellertr_details').style.display = '';
				if(document.getElementById('product_bestsellerunassign_div'))
					document.getElementById('product_bestsellerunassign_div').style.display = '';	
				call_ajax_showlistall('product_bestseller');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('product_bestsellertr_details'))
					document.getElementById('product_bestsellertr_details').style.display = 'none';
				
				if(document.getElementById('product_bestsellertrtr_norec'))
					document.getElementById('product_bestsellertrtr_norec').style.display = 'none';
				
				if(document.getElementById('product_bestsellerunassign_div'))
					document.getElementById('product_bestsellerunassign_div').style.display = 'none';	
				
			}	
		break;
	 };

}
function call_ajax_showlistall(mod)
{  
	var atleastone 			= 0;
	var fpurpose			= '';
	var retdivid			= '';
	var moredivid			= '';
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			retdivid   	= 'productbestseller_div';
			fpurpose	= 'list_productbestseller';
			moredivid	= 'productbestsellerunassign_div';
			
		break;
	};
    document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr										= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose);		
}		
function normal_assign_prodBestsellerAssign()
{
		window.location 			= 'home.php?request=general_settings&fpurpose=prodBestsellerAssign';
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	switch(mod)
	{
		case 'product_bestseller': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			retdivid   	= 'productbestseller_div';
			moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_bestseller_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmImageSettings.elements.length;i++)
	{
	if (document.frmImageSettings.elements[i].type =='checkbox' && document.frmImageSettings.elements[i].name== checkboxname)
		{
		

			if (document.frmImageSettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmImageSettings.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmImageSettings.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
			}	
		}
	}
	
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		 	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmImageSettings.elements.length;i++)
	{
		if (document.frmImageSettings.elements[i].type =='checkbox' && document.frmImageSettings.elements[i].name==checkboxname)
		{

			if (document.frmImageSettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmImageSettings.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_bestseller': // Case of Products in the bestseller
			atleastmsg 	= 'Please select the Product(s) to be deleted from the bestseller list';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the bestseller list?';
			retdivid   	= 'productbestseller_div';
			moredivid	= 'productbestsellerunassign_div';
			fpurpose	= 'prodbestsellerUnAssign';
		break;
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/general_settings.php','fpurpose='+fpurpose+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}

function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 			= req.responseText;
			targetdiv 			= document.getElementById('retdiv_id').value;
			norecdiv 			= document.getElementById('retdiv_more').value;
			targetobj 			= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				case 'productbestseller_div':
					if(document.getElementById('productbestseller_norec'))
					{
						if(document.getElementById('productbestseller_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
			};	
			if(document.getElementById('mainerror_tr'))
				document.getElementById('mainerror_tr').style.display = 'none';
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function setimagesize_todefault(typ)
{
	var len = typ.length;
	var allDivs = document.getElementsByTagName('select'); /* get all select */
	for(i=0;i<allDivs.length; i++)
	{
	  var aDiv = allDivs[i];
	  var sID = aDiv.id;
	  if (sID.indexOf(typ)!=-1)
	  	aDiv.selectedIndex =0;
	}
}
function display_compare_count(){
	if(document.frmImageSettings.product_compare_enable.checked == true){
		document.getElementById('compare_count_id').style.display='';
	}else{
		document.getElementById('compare_count_id').style.display='none';
	}
}
function assign_sslImage(paymt_methods_sites_id){
	document.frmImageSettings.fpurpose.value='add_sslimg';
	document.frmImageSettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
	document.frmImageSettings.submit();
}
function assign_default_sslImage(paymt_methods_sites_id){
document.getElementById('assign_button_'+paymt_methods_sites_id).style.display='none';
	//if(confirm('Are you sure You want to change to default SSL image')){
	document.frmImageSettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
		document.frmImageSettings.fpurpose.value='add_default_sslimg';
	//}
}
function checkall() 
{
	frm = document.frmImageSettings;
		frm.imageverification_news_req.checked  = true;
		frm.imageverification_vouch_req.checked = true;
		frm.imageverification_site_req.checked  = true;
		frm.imageverification_cust_req.checked  = true;
		frm.imageverification_prod_req.checked  = true;
}
function uncheckall() 
{
	frm = document.frmImageSettings;
	frm.imageverification_news_req.checked  = false;
	frm.imageverification_vouch_req.checked = false;
	frm.imageverification_site_req.checked  = false;
	frm.imageverification_cust_req.checked  = false;
	frm.imageverification_prod_req.checked  = false;
}
function product_decr_display() {
	frm = document.frmImageSettings;
	if(frm.product_maintainstock.checked == true) 
		document.getElementById('prd_decr').style.display='';
	else 	
		document.getElementById('prd_decr').style.display='none';
}
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array();
	fieldDescription = Array();
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
			 var alertMsg =  "Please Enter ";
   	var l_Msg = alertMsg.length;
	var re = /[<,>,",',%,&,*,;,^,(,)]/i;
	var e = / /g;
	if (alertMsg.length == l_Msg)
   	{
		 /************ Special Chars Validation ************/
		
			var obj = frm.ban_ipaddress;
			if (obj)	{
				var re = /[a-z,A-Z,!,@,#,$,<,>,",',%,&,*,;,(,)]/i;	    
				var result = obj.value.search(re); // checks invalid characters 
				if( result != -1 )  	{
					result++;
					alert('Invalid Charater of Ipaddress'); 
					obj.focus();
					obj.select();
				   	return false;
				} 
			}	// END IF obj
			
		}
		show_processing();
		return true;
		}	
		else
		{
			return false;
		}
	
}
</script>
<form name="frmImageSettings" method="post" action="home.php?request=image_setings" onsubmit="return valforms(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> Image Settings</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
         <tr>
          <td colspan="6" align="left" valign="top" >
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  
         <tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Design_Layout">&nbsp;Image On/Off </a></td>
        </tr>
        <tr>
          
         <td align="left" valign="middle" width="2%" class="tdcolorgray"><input type="checkbox" name="thumbnail_in_viewcart" value="1" <?php echo ($fetch_arr_admin['thumbnail_in_viewcart'] == 1)?"checked":"";?>/></td>
		 <td width="41%" colspan="2" align="left" valign="middle" class="tdcolorgray">Display  image for each product item on the view cart page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_THMBIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td width="2%" align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="thumbnail_in_wishlist" value="1" <?php echo ($fetch_arr_admin['thumbnail_in_wishlist'] == 1)?"checked":"";?>/></td>
         <td width="38%" colspan="2" align="left" valign="middle" class="tdcolorgray">Display  image for each product item on the wishlist page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_THMBIMG_WISHLIST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="thumbnail_in_enquiry" value="1" <?php echo ($fetch_arr_admin['thumbnail_in_enquiry'] == 1)?"checked":"";?>/></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">Display  image for each product item on the enquiry page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_THMBIMG_ENQ')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray"  ><input type="checkbox" name="turnoff_catimage" value="1" <?php echo($fetch_arr_admin['turnoff_catimage'] == 1)?"checked":"";?>/></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >Turn off the category image<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_CATIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="recentlyviewed_hide_image" id="recentlyviewed_hide_image" value="1" <?php echo ($fetch_arr_admin['recentlyviewed_hide_image'] == 1)?"checked":"";?>/></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">Turn off image in recently viewed product listing <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_THMBIMG_RECENTLY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >&nbsp;</td>
        </tr>
        
  
		<tr>
		  <td colspan="3" align="left" valign="middle" class="seperationtd"><strong>Set Image Sizes 
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_IMG_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></strong></td>
		  <td colspan="3" align="left" valign="middle"  class="seperationtd"><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td align="left"><input type="checkbox" name="set_all_to_default" id="set_all_to_default" value="1" onclick="setimagesize_todefault('_showimagetype')" />
                Make all to Default? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAKE_ALLTODEFAULT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
		  </tr>
		<tr>
		  <td colspan="6" align="left" valign="middle" class="tdcolorgray">
		  <table width="100%" border="0" cellpadding="1" cellspacing="1">
            <tr>
              <td width="20%">Component Shelves Image </td>
              <td width="28%">
			  <? 
			  	$arr_style 	= $val_arr = array();
			  	$sql_style	= "SELECT image_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
				$ret_style 	= $db->query($sql_style);
				if ($db->num_rows($ret_style))
				{
					$row_style	= $db->fetch_array($ret_style);
				 	$arr_style	= explode(',',$row_style['image_listingstyles']);
					if (count($arr_style))
					{
						foreach($arr_style as $v)
						{
							$temp_arr = explode("=>",$v);
							$val_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
						}
					}				
				}	
			 	echo generateselectbox('compshelf_showimagetype',$val_arr,$fetch_arr_admin['compshelf_showimagetype']);
			  ?></td>
              <td width="21%">Middle Area Shelves</td>
              <td width="23%"><?= generateselectbox('midshelf_showimagetype',$val_arr,$fetch_arr_admin['midshelf_showimagetype']); ?></td>
              <td width="8%">&nbsp;</td>
            </tr>
            <tr>
              <td>Recently Viewed Products </td>
              <td><?= generateselectbox('recent_showimagetype',$val_arr,$fetch_arr_admin['recent_showimagetype']); ?></td>
              <td>Search Result Product </td>
              <td colspan="2"><?= generateselectbox('search_showimagetype',$val_arr,$fetch_arr_admin['search_showimagetype']); ?></td>
            </tr>
            <tr>
              <td>Category Details Page Image  </td>
              <td><?=generateselectbox('category_showimagetype',$val_arr,$fetch_arr_admin['category_showimagetype']); ?> </td>
              <td>Search Result Category Listing </td>
              <td colspan="2"><?= generateselectbox('search_catshowimagetype',$val_arr,$fetch_arr_admin['search_catshowimagetype']); ?></td>
            </tr>
            <tr>
              <td>Category Details Product </td>
              <td><?= generateselectbox('categoryprod_showimagetype',$val_arr,$fetch_arr_admin['categoryprod_showimagetype']); ?></td>
              <td>Product Details Page </td>
              <td colspan="2"><?= generateselectbox('productdetail_showimagetype',$val_arr,$fetch_arr_admin['productdetail_showimagetype']); ?></td>
            </tr>
            <tr>
              <td>Linked Product Listing</td>
              <td><?= generateselectbox('linkedprod_showimagetype',$val_arr,$fetch_arr_admin['linkedprod_showimagetype']); ?></td>
              <td>Middle Combo Deals </td>
              <td colspan="2"><?= generateselectbox('midcombo_showimagetype',$val_arr,$fetch_arr_admin['midcombo_showimagetype']); ?></td>
            </tr>
            <tr>
              <td>Middle Shop Image </td>
              <td><?= generateselectbox('shop_showimagetype',$val_arr,$fetch_arr_admin['shop_showimagetype']); ?></td>
              <td>Middle SubShop Image </td>
              <td colspan="2"><?= generateselectbox('subshop_showimagetype',$val_arr,$fetch_arr_admin['subshop_showimagetype']); ?></td>
            </tr>
            <tr>
              <td>Middle Shop Product Image </td>
              <td><?= generateselectbox('shopprod_showimagetype',$val_arr,$fetch_arr_admin['shopprod_showimagetype']); ?></td>
              <td>Customer Favourite Category </td>
              <td colspan="2"><?= generateselectbox('myfavouritecategory_showimagetype',$val_arr,$fetch_arr_admin['myfavouritecategory_showimagetype']); ?></td>
            </tr>
            <tr>
              <td>Customer Favourite Products </td>
              <td><?= generateselectbox('myfavouriteproduct_showimagetype',$val_arr,$fetch_arr_admin['myfavouriteproduct_showimagetype']); ?></td>
			<td>
			Product on the view enquiry page			</td>
			<td>
			<select name="product_enquiry_showimagetype" id="product_enquiry_showimagetype">
			  <option value="Default" <?php echo ($fetch_arr_admin['product_enquiry_showimagetype']=='Default')?'selected="selected"':''?>>Default</option>
			  <option value="Icon" <?php echo ($fetch_arr_admin['product_enquiry_showimagetype']=='Icon')?'selected="selected"':''?>>Icon</option>
			  <option value="Thumb" <?php echo ($fetch_arr_admin['product_enquiry_showimagetype']=='Thumb')?'selected="selected"':''?>>Small Image</option>
			  </select>
			<? //generateselectbox('product_enquiry_showimagetype',$val_arr,$fetch_arr_admin['product_enquiry_showimagetype']); ?>			</td>
			 <!-- <td>Product Details More Images </td>
              <td colspan="2">
			  <select name="productdetail_moreimages_showimagetype" id="productdetail_moreimages_showimagetype">
			  <option value="Default" <?php //echo ($fetch_arr_admin['productdetail_moreimages_showimagetype']=='Default')?'checked="checked"':''?>>Default</option>
			  <option value="Icon"<?php //echo ($fetch_arr_admin['productdetail_moreimages_showimagetype']=='Icon')?'checked="checked"':''?>>Icon</option>
			  <option value="Thumb"<?php //echo ($fetch_arr_admin['productdetail_moreimages_showimagetype']=='Thumb')?'checked="checked"':''?>>Small Image</option>
			  </select>
			  </td> -->
            </tr>
			<tr>
			<td>
			Product on the view cart page			</td>
			<td>
			<select name="product_cart_showimagetype" id="product_cart_showimagetype">
			  <option value="Default" <?php echo ($fetch_arr_admin['product_cart_showimagetype']=='Default')?'selected="selected"':''?>>Default</option>
			  <option value="Icon"<?php echo ($fetch_arr_admin['product_cart_showimagetype']=='Icon')?'selected="selected"':''?>>Icon</option>
			  <option value="Thumb"<?php echo ($fetch_arr_admin['product_cart_showimagetype']=='Thumb')?'selected="selected"':''?>>Small Image</option>
			  </select>
			<? //generateselectbox('product_cart_showimagetype',$val_arr,$fetch_arr_admin['product_cart_showimagetype']); ?>			</td>
			<td>
			Product on the view wishlist page			</td>
			<td>
			<select name="product_wishlist_showimagetype" id="product_wishlist_showimagetype">
			  <option value="Default" <?php echo ($fetch_arr_admin['product_wishlist_showimagetype']=='Default')?'selected="selected"':''?>>Default</option>
			  <option value="Icon"<?php echo ($fetch_arr_admin['product_wishlist_showimagetype']=='Icon')?'selected="selected"':''?>>Icon</option>
			  <option value="Thumb"<?php echo ($fetch_arr_admin['product_wishlist_showimagetype']=='Thumb')?'selected="selected"':''?>>Small Image</option>
			  </select>
			<? //generateselectbox('product_wishlist_showimagetype',$val_arr,$fetch_arr_admin['product_wishlist_showimagetype']); ?>			</td>
			</tr>
          </table></td>
		  </tr>
		<? 
		/*if(is_module_valid('mod_ssl','any')){
		
				
			$sql_img =	"SELECT payment_methods_forsites_id,payment_methods_paymethod_id,paymethod_name,paymethod_key,
						paymethod_ssl_imagelink,payment_method_sites_image_id 
						from payment_methods_forsites pms,payment_methods pm
						where pms.sites_site_id = $ecom_siteid and pm.paymethod_id=pms.payment_methods_paymethod_id"; 
			$ret_img = $db->query($sql_img); 
  

		?>
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" > <a name="Managing_SSL_Images">&nbsp;</a><b>Managing SSL Images</b></td>
        </tr>
		
		<? }?>
		<tr>
		
          <td align="center" valign="middle" class="tdcolorgray" colspan="6">
		  <input name="payment_methods_forsites_id" type="hidden" value="" />
		
  <?php 
  	while($ssl_img = $db->fetch_array($ret_img)) {
	if($ssl_img['payment_method_sites_image_id']){
	$img_name =  getImageByID($ssl_img['payment_method_sites_image_id']);
	$img="http://$ecom_hostname/images/$ecom_hostname/".$img_name."" ;
	}else{
	global $image_path;
	///echo "$image_path/site_images/".strtolower($ssl_img['paymethod_key'])."_ssl.gif";
	if(file_exists("$image_path/site_images/".strtolower($ssl_img['paymethod_key'])."_ssl.gif")){
			$img="http://$ecom_hostname/images/$ecom_hostname/site_images/".strtolower($ssl_img['paymethod_key'])."_ssl.gif" ;	

	}else{
		$img='';
		}
	}
   ?>
   <div class="gensettings_ssl_img">
    <? // if($img){?>
	<div class="gensettings_ssl_imgA"><strong><?=$ssl_img['paymethod_name']?></strong></div>
	<div class="gensettings_ssl_imgA">Image:<input name="ssl_image_<?=$ssl_img['payment_methods_forsites_id']?>" type="radio" value="1"  <?php echo ($ssl_img['payment_method_sites_image_id'])?'':'checked="checked"'?>  onclick="assign_default_sslImage(<?=$ssl_img['payment_methods_forsites_id']?>);"  />Default<input name="ssl_image_<?=$ssl_img['payment_methods_forsites_id']?>" type="radio" value="0"  <?php echo ($ssl_img['payment_method_sites_image_id'])?'checked="checked"':''?> onclick="document.getElementById('assign_button_<?=$ssl_img['payment_methods_forsites_id']?>').style.display='';"  />Custom<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SSLIMAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>
<?php if($ssl_img['payment_method_sites_image_id']){
			$display_style =' style="display:" ';
		 }
			else{
			$display_style ='style="display:none" ';
			}
	?> 
	<div class="gensettings_ssl_imgA" id="assign_button_<?=$ssl_img['payment_methods_forsites_id']?>"  <?=$display_style?> ><input name="AssignImage" class="red" id="AssignImage" value="Assign/Change" onclick="assign_sslImage(<?=$ssl_img['payment_methods_forsites_id']?>);" type="button">
		<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ASSIGN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;</div>
		 
	 <div class="gensettings_ssl_imgB" ><?php if($img) {?><a href="<?php echo $img?>" title="Click to enlarge" target="_blank"><img src="<?php echo $img?>" alt="" border="0"  /></a><? }?></div><? // }?>
	</div>
<?php 
	}*/
	?>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
			<td colspan="6" align="right" valign="middle" class="tdcolorgray">
				<div class="editarea_div">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td  align="right" valign="middle" class="tdcolorgray"><input name="Submit" type="submit" class="red" value="Save Settings" /></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		
        
      </table>
</td>
</tr>
</table>
<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
<input type="hidden" name="src_page" id="src_page" value="mainshop" />
<input type="hidden" name="fpurpose" id="fpurpose" value="images_default_update" />
		<input type="hidden" name="src_id" id="src_id" value="" />


</form>