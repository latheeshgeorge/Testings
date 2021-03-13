<?php
	/*#################################################################
	# Script Name 	: edit_shopbybrand.php
	# Description 	: Page for editing Product shops
	# Coded by 		: Sny
	# Created on	: 22-Nov-2007
	# Modified by	: LG
	# Modified On	: 25-Jan-2008
	#################################################################*/
	//#Define constants for this page
$page_type = 'Product Shops';
$help_msg =get_help_messages('EDIT_PROD_SHOP_SHORT');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
if($edit_id)
{
	$sql_shops = "SELECT shopbrand_name 
						FROM product_shopbybrand 
							WHERE shopbrand_id=$edit_id  AND sites_site_id=".$ecom_siteid."  LIMIT 1";
	$ret_shops = $db->query($sql_shops);
	if($db->num_rows($ret_shops)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
	if($db->num_rows($ret_shops))
	{
		$row_shops = $db->fetch_array($ret_shops);
	}
}
$editor_elements = "shopbrand_description,shopbrand_bottomdescription";
include_once(ORG_DOCROOT."/console/js/tinymce.php");	
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('shopbrand_name');
	fieldDescription = Array('Product Shop Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{ 
			if(frm.shopbrand_product_showimage.checked == false && 
				   frm.shopbrand_product_showtitle.checked == false &&
				   frm.shopbrand_product_showshortdescription.checked == false &&
				   frm.shopbrand_product_showprice.checked == false &&
				   frm.shopbrand_product_showbonuspoints.checked == false) 
	    		{
					  alert('Please Check any of Product Items to Display ')	   
					  return false;
				}
		show_processing();
		return true;
	} else {
		return false;
	}
}
function handle_tabs(id,mod)
{
	tab_arr 									= new Array('main_tab_td','shop_tab_td','image_tab_td','products_tab_td','settings_tab_td','seo_tab_td');
	var atleastone 						= 0;
	var shop_id								= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var shname								='<?php echo $_REQUEST['shopname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var shopgroupid						='<?php echo $_REQUEST['show_shopgroup']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	document.frmEditShopByBrand.fpurpose.value = 'save_edit';
	var qrystr								= 'shopname='+shname+'&show_shopgroup='+shopgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
	for(i=0;i<tab_arr.length;i++)
	{
		if(tab_arr[i]!=id)
		{
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			obj.className = 'toptab';
		}
	}
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	switch(mod)
	{
		case 'shopmain_info':
			/*fpurpose 	= 'list_shop_maininfo';*/
			document.frmEditShopByBrand.fpurpose.value = 'list_shop_maininfo';
			document.frmEditShopByBrand.submit();
			return;
		break;
		case 'subshop': // Case of showing subcategories
			fpurpose	= 'list_subshops';
		break;
		case 'images': // Case of showing category image section
			fpurpose	= 'list_shopimg';
		break;
		case 'products':
			fpurpose	= 'list_shop_products';
		break;
		case 'settings':
			fpurpose	= 'list_shop_settings';
		break;
		case 'seo':
			fpurpose	= 'list_seo';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&cur_shopid='+shop_id+'&'+qrystr);	
}
function call_ajax_savesettings(mod)
{
	var editid				= '<?php echo $edit_id?>';
	var qrystr				= '';
	var confirmmsg 			= '';
	var shop_id								= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var shname								='<?php echo $_REQUEST['shopname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var shopgroupid						='<?php echo $_REQUEST['show_shopgroup']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	switch(mod)
	{
			case 'display_settings':
				var shopbrand_subshoplisttype	= document.getElementById('shopbrand_subshoplisttype').value;
				//var product_displaywhere 	= document.getElementById('product_displaywhere').value;
				var shopbrand_product_displaytype		= document.getElementById('shopbrand_product_displaytype').value;
				
				if(document.frmEditShopByBrand.shopbrand_product_showimage.checked)
					var product_showimage		= 1;
				else
					var product_showimage		= 0;
				if(document.frmEditShopByBrand.shopbrand_product_showtitle.checked)
					var product_showtitle		= 1;
				else
					var product_showtitle		= 0;
				if(document.frmEditShopByBrand.shopbrand_product_showshortdescription.checked)
					var product_showshortdescription		= 1;
				else
					var product_showshortdescription		= 0;
				if(document.frmEditShopByBrand.shopbrand_product_showprice.checked)
					var product_showprice		= 1;
				else
					var product_showprice		= 0;
				if(document.frmEditShopByBrand.shopbrand_product_showrating.checked)
					var product_showrating		= 1;
				else
					var product_showrating		= 0;	
				if(document.frmEditShopByBrand.shopbrand_product_showbonuspoints.checked)
					var product_showbonuspoints		= 1;
				else
					var product_showbonuspoints		= 0;	
					if (product_showimage=='' && product_showtitle=='' && product_showshortdescription=='' && product_showprice=='' && product_showrating=='' && product_showbonuspoints=='')
					{
						alert('Please select atleast one field for products');
						return false;
					}
				qrystr = '&shopbrand_subshoplisttype='+shopbrand_subshoplisttype+'&shopbrand_product_displaytype='+shopbrand_product_displaytype+'&shopbrand_product_showimage='+product_showimage+'&shopbrand_product_showtitle='+product_showtitle+'&shopbrand_product_showshortdescription='+product_showshortdescription+'&shopbrand_product_showprice='+product_showprice+'&shopname='+shname+'&show_shopgroup='+shopgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&shopbrand_product_showrating='+product_showrating+'&shopbrand_product_showbonuspoints='+product_showbonuspoints;
				atleastmsg 	= '';
				atleastone = 1;
				confirmmsg 	= 'Are you sure you want to save the display settings?';
				fpurpose	= 'save_edit_settings';
			break;
	};
	if(confirm(confirmmsg))
	{
		//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&edit_id='+editid+qrystr);	

	}	
}
function handle_images_from_product(obj)
{
	if(obj.checked)
		var checked = 'none';
	else
		var checked = '';
	
		if (document.getElementById('shopimg_operation_main'))
			document.getElementById('shopimg_operation_main').style.display = checked;
		if (document.getElementById('img_tr_1'))
			document.getElementById('img_tr_1').style.display = checked;
		if (document.getElementById('img_tr_2'))
			document.getElementById('img_tr_2').style.display = checked;
		if (document.getElementById('img_tr_3'))
			document.getElementById('img_tr_3').style.display = checked;		
		
}
function normal_assign_ImageAssign(src_id,shopname,shgroupid,parentid,sortby,sortorder,recs,start,pg)
{ 
		window.location 			= 'home.php?request=shopbybrand&fpurpose=add_shopimg&src_page=prodshop&src_id='+src_id+'&pass_show_shopgroup='+shgroupid+'&pass_shopname='+shopname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg;
}
function normal_assign_shopprod(cname,shgroupid,sortby,sortorder,recs,start,pg,shopid)
{
		window.location 			= 'home.php?request=shopbybrand&fpurpose=assign_selshopprod&pass_shopname='+cname+'&pass_show_shopgroup='+shgroupid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shop_id='+shopid;
}
/*function normanl_assign_shopsubshop(cname,shgroupid,sortby,sortorder,recs,start,pg,shopid)
{
		window.location 			= 'home.php?request=shopbybrand&fpurpose=subshopAssign&pass_shopname='+cname+'&pass_show_shopgroup='+shgroupid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_sub_id='+shopid;

}*/
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var shop_id				= '<?php echo $edit_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var shname								='<?php echo $_REQUEST['shopname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var shopgroupid						='<?php echo $_REQUEST['show_shopgroup']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr								= 'shopname='+shname+'&show_shopgroup='+shopgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShopByBrand.elements.length;i++)
	{
		if (document.frmEditShopByBrand.elements[i].type =='checkbox' && document.frmEditShopByBrand.elements[i].name==checkboxname)
		{
			if (document.frmEditShopByBrand.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditShopByBrand.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'shopimg': 
		// Case of saving the order
			atleastmsg 	= 'Please select the Shop image(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
			fpurpose	= 'unassign_shopimagedetails';
		break;
		case 'subshop':
			atleastmsg 	= 'Please select the Sub Shop(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to unassign the selected Sub Shop(s)?';
			fpurpose	= 'unassignsubshop';
		break;
		case 'subshopproduct_group':
			atleastmsg 	= 'Please select the Product(s) to be unassigned to current shop';
			confirmmsg 	= 'Are you sure you want to unassign the selected Product(s) to current shop?';
			fpurpose	= 'unassignshopproduct';
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
		    retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&shop_id='+shop_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}

function call_save_order(mod,checkboxname)
{
	var atleastone 			= 0;
	var shop_id								= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var shname								='<?php echo $_REQUEST['shopname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var shopgroupid						='<?php echo $_REQUEST['show_shopgroup']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var ch_ids 								= '';
	var ch_order							= '';
	var ch_dis 								= '';
	switch(mod)
	{
		case 'subshop': // Case of Subshops
			atleastmsg 	= 'Please select the Sub Shops to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Subshop(s)?';
			/*retdivid   	= 'subshop_div';
			moredivid	= 'subshop_unassign_div';*/
			fpurpose	= 'save_shoporder';
			orderbox	= 'shop_sort_';
		break;
		case 'subshopproduct':
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			/*retdivid   	= 'subshopproductgroup_div';
			moredivid	= 'subshopproduct_groupunassign_div';*/
			fpurpose	= 'save_shopproductorder';
			orderbox	= 'shopprod_order_';
		break;
		
	}
		var qrystr								= 'shopname='+shname+'&show_shopgroup='+shopgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShopByBrand.elements.length;i++)
	{
	if (document.frmEditShopByBrand.elements[i].type =='checkbox' && document.frmEditShopByBrand.elements[i].name== checkboxname)
		{
		

			if (document.frmEditShopByBrand.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditShopByBrand.elements[i].value;
				 obj = eval("document.getElementById('"+orderbox+document.frmEditShopByBrand.elements[i].value+"')");
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
	        retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		 //	document.getElementById('retdiv_more').value	= moredivid;/* Name of div to show the result */	
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&shop_id='+shop_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function change_show_date_period()
{
	
	if(document.frmEditShopByBrand.shopbrand_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
function call_ajax_savedetails(mod,checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $edit_id?>';
	var ch_ids 				= '';
	var ch_variable			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var shop_id								= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var shname								='<?php echo $_REQUEST['shopname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var shopgroupid						='<?php echo $_REQUEST['show_shopgroup']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	//var retdivid			= 'catimg_div';
	//var moredivid			= 'catimgunassign_div';
	//var fpurpose			= 'save_catimagedetails';
	var ch_order			= '';
	var ch_title			= '';
	var qrystr								= 'shopname='+shname+'&show_shopgroup='+shopgroupid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
	
	var category_turnoff_moreimages = 0;
	var category_turnoff_mainimage = 0;
	if(document.getElementById('shopbrand_turnoff_mainimage'))
	{
		if(document.getElementById('shopbrand_turnoff_mainimage').checked==true)
			shopbrand_turnoff_mainimage=1;
			else
			shopbrand_turnoff_mainimage=0;
	}
	if(document.getElementById('shopbrand_turnoff_moreimages'))
	{
		if(document.getElementById('shopbrand_turnoff_moreimages').checked==true)
			shopbrand_turnoff_moreimages=1;
		else
			shopbrand_turnoff_moreimages=0;
	}
	if(document.frmEditShopByBrand.shop_showimageofproduct.checked == true){
		var shimg = '1';
	} else {
		var shimg = '0';
	}
	switch(mod)
	{
		case 'shopimg': 
		/* check whether any checkbox is ticked */
		   for(i=0;i<document.frmEditShopByBrand.elements.length;i++)
			{
			if (document.frmEditShopByBrand.elements[i].type =='checkbox' && document.frmEditShopByBrand.elements[i].name== checkboxname)
			{
				if (document.frmEditShopByBrand.elements[i].checked==true)
				{
					atleastone = 1;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditShopByBrand.elements[i].value;
				 
					 obj1 = eval("document.getElementById('img_ord_"+document.frmEditShopByBrand.elements[i].value+"')");
				 	obj2 = eval("document.getElementById('img_title_"+document.frmEditShopByBrand.elements[i].value+"')");
				 
					if (ch_order != '')
						ch_order += '~';
					 ch_order += obj1.value; 
				 
				 	if (ch_title != '')
						ch_title += '~';
					 ch_title += obj2.value; 
				}	
			}
			}
		// Case of saving the order
		//	atleastmsg 	= 'Please select the image(s) to be saved';
			atleastone = 1;
			confirmmsg 	= 'Are you sure you want to save the title and order of selected images?';
			/*retdivid   	= 'shopimg_div';
			moredivid	= 'shopimgunassign_div';*/
			fpurpose	= 'save_shopimagedetails';
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
		     retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");

			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&edit_id='+editid+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr+'&shimg='+shimg+'&shopbrand_turnoff_mainimage='+shopbrand_turnoff_mainimage+'&shopbrand_turnoff_moreimages='+shopbrand_turnoff_moreimages);
		}	
	}
}
function ajax_return_contents() 
{
	var ret_val='';
	var disp = 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			//norecdiv 	= document.getElementById('retdiv_more').value;
			
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
		}	
	}	
}
function call_save_seo(mod)
{
	var atleastone 			= 0;
	var editid												=<?php echo $edit_id?>;
	var ch_ids 				= '';
	var ch_order			= '';
	var ch_dis 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var disbox				= '';
	var catname						='<?php echo $_REQUEST['catname']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg								= '<?php echo $_REQUEST['pg']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $showinallpages?>';
	var qrystr							= 'catname='+catname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;
	fpurpose  = 'save_seo';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShopByBrand.elements.length;i++)
	{  
	if (document.frmEditShopByBrand.elements[i].type =='text' && document.frmEditShopByBrand.elements[i].name.substr(0,7)== 'keyword')
		{			
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditShopByBrand.elements[i].value;			
		}
	}
	atleastmsg = "Enter the Title";
	var page_title = '';
	var meta ='';
	page_title = document.frmEditShopByBrand.page_title.value;
	meta       = document.frmEditShopByBrand.page_meta.value;
	qrystr +='&page_title='+page_title+'&page_meta='+meta;	 
	//if(page_title=='')
	//{
		//alert(atleastmsg);
	//}
	//else
	{
		
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shopbybrand.php','fpurpose='+fpurpose+'&cur_shopid='+editid+'&ch_ids='+ch_ids+'&'+qrystr);
			
	}	
}
</script>
<form name='frmEditShopByBrand' action='home.php?request=shopbybrand' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrand&shopname=<?php echo $_REQUEST['shopname']?>&show_shopgroup=<?php echo $_REQUEST['show_shopgroup']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>">List Product Shops</a> <span> Edit Product Shop for <?="'".$row_shops['shopbrand_name']."'"?></span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="4" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x" >
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','shopmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('shop_tab_td','subshop')" class="<?php if($curtab=='shop_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shop_tab_td"><span>Sub Shops</span></td>
						<td  align="left" onClick="handle_tabs('image_tab_td','images')" class="<?php if($curtab=='image_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="image_tab_td"><span>Images</span></td>
						<td  align="left" onClick="handle_tabs('products_tab_td','products')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Products in Shop</span></td>
						<td  align="left" onClick="handle_tabs('settings_tab_td','settings')" class="<?php if($curtab=='settings_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="settings_tab_td"><span>Display Settings</span></td>
						<td  align="left" onClick="handle_tabs('seo_tab_td','seo')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings</span></td>

						<td width="50%" align="left">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
		<?php
			/*if($alert)
			{*/
		?>
       <!-- <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?/*$alert*/?></td>
          </tr>-->
		 <?php
		 	/*}*/
		 ?> 
        <tr>
	<td colspan="4">
	<div id='master_div'>
	<?php
	if ($curtab=='main_tab_td')
		{
			show_shopmaininfo($edit_id,$alert);
		}
		if ($curtab=='shop_tab_td')
		{
			show_subshop_list($edit_id,$alert);
		}
		elseif ($curtab=='image_tab_td')
		{
			show_shopimage_list($edit_id,$alert);
		}
		elseif ($curtab=='products_tab_td')
		{
			show_shop_products($edit_id,$alert);
		}
		elseif ($curtab=='settings_tab_td')
		{
			show_shop_settings($edit_id,$alert);
		}
	?>
	</div>	</td>
	</tr>
		 
		  <tr>
		    <td colspan="2" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="top" class="tdcolorgray">&nbsp;</td>
		    <td align="left" valign="top" class="tdcolorgray">&nbsp;</td>
    </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  
		  	<input type="hidden" name="shopname" id="shopname" value="<?=$_REQUEST['shopname']?>" />
			 <input type="hidden" name="show_shopgroup" id="show_shopgroup" value="<?=$_REQUEST['show_shopgroup']?>" />
		  	<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  	<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  	<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  	<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  	<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
		  	<input type="hidden" name="pass_sub_id" id="pass_sub_id" value="<?php echo $edit_id?>" />
			<input type="hidden" name="checkbox[]" id="checkbox[]" value="<?php echo $edit_id?>" />
			<input type="hidden" name="src_page" id="src_page" value="prodshop" />
		    <input type="hidden" name="src_id" id="src_id" value="<?php echo $edit_id?>" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
          <td align="center" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
</form>	  

	
