<?php
	/*
	#################################################################
	# Script Name 		: edit_settings_default.php
	# Description 		: Page for managing the main shop settings
	# Coded by 			: Snl
	# Created on		: 14-Jun-2007
	# Modified by		: Sny
	# Modified On		: 25-Aug-2008
	#################################################################
	*/	
	
	$help_msg 			= get_help_messages('SALES_TAX_SETTINGS');
	//# Retrieving the values of super admin from the table
	$sql 				= "SELECT saletax_before_discount, apply_tax_ondelivery, apply_tax_ongiftwrap,apply_tax_value_promgift_calc 
								FROM 
									general_settings_sites_common 
								WHERE 
									sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
/*	$sql 							= "SELECT delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment,
									   delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment	 
												 FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 				= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
	*/
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
	for(i=0;i<document.frmDeliverySettings.elements.length;i++)
	{
	if (document.frmDeliverySettings.elements[i].type =='checkbox' && document.frmDeliverySettings.elements[i].name== checkboxname)
		{
		

			if (document.frmDeliverySettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmDeliverySettings.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmDeliverySettings.elements[i].value+"')");
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
	for(i=0;i<document.frmDeliverySettings.elements.length;i++)
	{
		if (document.frmDeliverySettings.elements[i].type =='checkbox' && document.frmDeliverySettings.elements[i].name==checkboxname)
		{

			if (document.frmDeliverySettings.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmDeliverySettings.elements[i].value;
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
	if(document.frmDeliverySettings.product_compare_enable.checked == true){
		document.getElementById('compare_count_id').style.display='';
	}else{
		document.getElementById('compare_count_id').style.display='none';
	}
}
function assign_sslImage(paymt_methods_sites_id){
	document.frmDeliverySettings.fpurpose.value='add_sslimg';
	document.frmDeliverySettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
	document.frmDeliverySettings.submit();
}
function assign_default_sslImage(paymt_methods_sites_id){
document.getElementById('assign_button_'+paymt_methods_sites_id).style.display='none';
	//if(confirm('Are you sure You want to change to default SSL image')){
	document.frmDeliverySettings.payment_methods_forsites_id.value=paymt_methods_sites_id;
		document.frmDeliverySettings.fpurpose.value='add_default_sslimg';
	//}
}
function checkall() 
{
	frm = document.frmDeliverySettings;
		frm.imageverification_news_req.checked  = true;
		frm.imageverification_vouch_req.checked = true;
		frm.imageverification_site_req.checked  = true;
		frm.imageverification_cust_req.checked  = true;
		frm.imageverification_prod_req.checked  = true;
}
function uncheckall() 
{
	frm = document.frmDeliverySettings;
	frm.imageverification_news_req.checked  = false;
	frm.imageverification_vouch_req.checked = false;
	frm.imageverification_site_req.checked  = false;
	frm.imageverification_cust_req.checked  = false;
	frm.imageverification_prod_req.checked  = false;
}
function product_decr_display() {
	frm = document.frmDeliverySettings;
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
<form name="frmDeliverySettings" method="post" action="home.php?request=tax_settings" onsubmit="return valforms(this);">
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
<tr>
<td valign="top" class="contentarea">

<table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="43240%" colspan="6" align="left" valign="middle" class="treemenutd">
			  <div class="treemenutd_div"><span>
			  Sales Tax Settings Section</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
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
		  <td colspan="6" align="left" valign="middle">
			  <div class="editarea_div">

			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
           
           	
        <tr>
         <td align="left" valign="middle" width="2%" class="tdcolorgray"><input type="checkbox" name="saletax_before_discount" value="1" <?php echo($fetch_arr_admin['saletax_before_discount'] == 1)?"checked":"";?>/></td>
		 <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >Apply tax before a discount is applied &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_TAXDISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td width="3%" align="left" valign="middle" class="tdcolorgray"  ><input type="checkbox" name="apply_tax_ondelivery" value="1" <?php echo($fetch_arr_admin['apply_tax_ondelivery'] == 1)?"checked":"";?>/></td>
         <td width="57%" colspan="2" align="left" valign="middle" class="tdcolorgray"  >Apply tax to delivery charge <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_TAXDISC_DELIVERYCHRGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" width="2%" class="tdcolorgray">
            <input name="apply_tax_ongiftwrap" type="checkbox" id="apply_tax_ongiftwrap" value="1" <?php echo($fetch_arr_admin['apply_tax_ongiftwrap'] == 1)?"checked":"";?>/>          </td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >Apply tax to Giftwrap <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_TAXDISC_GIFTWRAP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray"  ><input type="checkbox" name="apply_tax_value_promgift_calc" value="1" <?php echo($fetch_arr_admin['apply_tax_value_promgift_calc'] == 1)?"checked":"";?>/></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"  >Include Tax value in Promotional / Gift voucher Calculation </td>
        </tr>
           

            
            
          </table>
            </div>

          </td>
		  </tr>
		<tr>
		  <td colspan="6" align="left" valign="middle">
			  <div class="editarea_div">

			  <table width="100%" border="0" cellpadding="0" cellspacing="0">       
        <tr>
         <td colspan="6" align="right" valign="middle" ><input name="Submit" type="submit" class="red" value="Save Settings" /></td>
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
<input type="hidden" name="fpurpose" id="fpurpose" value="sale_tax_settings_update" />
		<input type="hidden" name="src_id" id="src_id" value="" />


</form>
