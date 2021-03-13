<?php
	/*#################################################################
	# Script Name 		: edit_product_category.php
	# Description 		: Page for editing Product Category
	# Coded by 			: Sny
	# Created on		: 22-June-2007
	# Modified by		: Sny
	# Modified On		: 28-May-2008
	#################################################################*/
//#Define constants for this page
$page_type = 'Product Category';
$help_msg  = get_help_messages('EDIT_PROD_CAT1');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_categorty 	= "SELECT * FROM product_categories WHERE category_id=$edit_id AND sites_site_id=".$ecom_siteid;
$ret_category 	= $db->query($sql_categorty);
if($db->num_rows($ret_category)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$label_group_td = '';
// Check whether any label group exists
$sql_grp = "SELECT group_id 
				FROM 
					product_labels_group  
				WHERE 
					sites_site_id = $ecom_siteid 
				LIMIT 
					1";
$ret_grp = $db->query($sql_grp);
if($db->num_rows($ret_grp))
{
	$label_group_td = ",'label_tab_td'";
}					
$editor_elements = "long_desc,bottom_desc";
include_once(ORG_DOCROOT."/console/js/tinymce.php");
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('cat_name');
	fieldDescription = Array('Category Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('catgroup_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		
			/* Check whether dispay location is selected*/
			obj = document.getElementById('group_id[]');
			
			 var cnt=0;
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						cnt++;
						atleastone = true;
					}
				}
					/*if(cnt>1)
					{
						var def_ok = false;
						for(i=0;i<obj.options.length;i++)
						{
							if(obj.options[i].selected)
							{
								if(obj.options[i].value==document.getElementById('default_catgroup_id').value)
									def_ok = true;
							}
						}
						if (def_ok==false)
						{
							alert('Default Product Category Group not in selected Product Category Group list');
							return false;
						}
					}*/
			if(frm.product_showimage.checked == false && 
				   frm.product_showtitle.checked == false &&
				   frm.product_showshortdescription.checked == false &&
				   frm.product_showprice.checked == false &&
				   frm.product_showrating.checked == false &&
				   frm.product_showbonuspoints.checked == false) 
	    		{
					  alert('Please select atleast one field for Products to Display ')	   
					  return false;
				}
			if(frm.category_showimage.checked == false && 
				   frm.category_showname.checked == false &&
				   frm.category_showshortdesc.checked == false ) 
					{
						  alert('Please select atleast one field for Subcategories to Display ')	   
						  return false;
					}	
			show_processing();
			return true;
		}	
		else
		{
			return false;
		}
}
function handle_tabs(id,mod)
{	
	tab_arr 									= new Array('main_tab_td','category_tab_td'<?php if($ecom_site_mobile_api==1){ echo ",'categorymobile_tab_td'";}?>,'product_tab_td','image_tab_td','settings_tab_td'<?php echo $label_group_td?>,'shops_tab_td','seo_tab_td'<?php if($ecom_enable_searchrefine_category==1){ echo ",'variables_tab_td'";}?>,'featured_tab_td');
	var atleastone 						= 0;
	var cat_id								= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var catgroupid						='<?php echo $_REQUEST['catgroupid']?>';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	document.frmEditProductCategory.fpurpose.value = 'save_edit';
	var qrystr								= 'catname='+cname+'&catgroupid='+catgroupid+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
	call_cancel();
	document.getElementById('retdiv_id').value ="master_div"; 
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
		case 'catmain_info':
			/*fpurpose 	= 'list_category_maininfo';*/
			document.frmEditProductCategory.fpurpose.value = 'list_category_maininfo';
			document.frmEditProductCategory.submit();
			return;
		break;
		case 'subcategory': // Case of showing subcategories
			fpurpose	= 'list_subcat';
		break;
		case 'subcategorymobile': // Case of showing subcategories in mobile
			fpurpose	= 'list_subcatmobile';
		break;
		case 'images': // Case of showing category image section
			fpurpose	= 'list_catimg';
		break;
		case 'settings':
			fpurpose	= 'list_cat_settings';
		break;
		case 'prods':
			fpurpose	= 'list_prods';
		break;
		case 'labelgroup':
			fpurpose = 'list_labelgroups';
		break;
		case 'shops':
			fpurpose = 'list_shops';
		break;
		case 'seo':
			fpurpose = 'list_seo';
		break;
		case 'variables':
			fpurpose = 'list_variables';
		break;
		case 'featured':
			fpurpose = 'list_featured';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&cur_catid='+cat_id+'&'+qrystr);	
}
/* Code for category variable starts here */
function save_varsetting(catid)
{
	if(document.getElementById('enable_searchrefine').checked)
		var enablevar	=	1;
	else
		var enablevar	=	0;
		
	Handlewith_Ajax('services/product_category.php','fpurpose=save_varsetting&cat_id='+catid+'&set_val='+enablevar);
}
function add_variable(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=variable_add&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
/* Code for category variable ends here */
function handle_images_from_product(obj)
{
	if(obj.checked)
		var checked = 'none';
	else
		var checked = '';
	
		if (document.getElementById('catimg_operation_main'))
			document.getElementById('catimg_operation_main').style.display = checked;
		if (document.getElementById('img_tr_1'))
			document.getElementById('img_tr_1').style.display = checked;
		if (document.getElementById('img_tr_2'))
			document.getElementById('img_tr_2').style.display = checked;
		if (document.getElementById('img_tr_3'))
			document.getElementById('img_tr_3').style.display = checked;		
		
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
	//var retdivid			= 'catimg_div';
	//var moredivid			= 'catimgunassign_div';
	//var fpurpose			= 'save_catimagedetails';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var catgroupid						='<?php echo $_REQUEST['catgroupid']?>';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr								= '&catname='+cname+'&catgroupid='+catgroupid+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
	var ch_order			= '';
	var ch_title			= '';
	var shimg				= 0;
	var category_turnoff_moreimages = 0;
	var category_turnoff_mainimage = 0;
	if(document.getElementById('category_turnoff_moreimages'))
	{
		if(document.getElementById('category_turnoff_moreimages').checked==true)
			category_turnoff_moreimages=1;
	}
	if(document.getElementById('category_turnoff_mainimage'))
	{
		if(document.getElementById('category_turnoff_mainimage').checked==true)
			category_turnoff_mainimage=1;
	}
	
	if(document.frmEditProductCategory.category_showimageofproduct)
	{
		if(document.frmEditProductCategory.category_showimageofproduct.checked == true){
			shimg = '1';
		} else {
			shimg = '0';
		}
	}	
	switch(mod)
	{
		case 'catimg': 
		/* check whether any checkbox is ticked */
		   for(i=0;i<document.frmEditProductCategory.elements.length;i++)
			{
				if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name== checkboxname)
				{
	
					if (document.frmEditProductCategory.elements[i].checked==true)
					{
						atleastone = 1;
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProductCategory.elements[i].value;
					 
						 obj1 = eval("document.getElementById('img_ord_"+document.frmEditProductCategory.elements[i].value+"')");
						obj2 = eval("document.getElementById('img_title_"+document.frmEditProductCategory.elements[i].value+"')");
					 
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
			//atleastmsg 	= 'Please select the image(s) to be saved';
			atleastone = 1;
			confirmmsg 	= 'Are you sure you want to save the details?';
			fpurpose	= 'save_catimagedetails';
		break;
		case 'subcat':
		  /* check whether any checkbox is ticked */
		           for(i=0;i<document.frmEditProductCategory.elements.length;i++)
					{
					if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name== checkboxname)
						{

							if (document.frmEditProductCategory.elements[i].checked==true)
								{
									atleastone = 1;
									if (ch_ids!='')
									ch_ids += '~';
					 				ch_ids += document.frmEditProductCategory.elements[i].value;
				 
					 				obj1 = eval("document.getElementById('cat_order_"+document.frmEditProductCategory.elements[i].value+"')");
				 
									if (ch_order != '')
									 ch_order += '~';
									 ch_order += obj1.value; 
							}	
						}
			}
		  // Case of saving the order
			atleastmsg 	= 'Please select the subcat(s) to be saved';
			confirmmsg 	= 'Are you sure you want to save the  order of selected categories?';
			retdivid   	= 'subcat_div';
			moredivid	= 'subcatunassign_div';
			fpurpose	= 'save_subcatdetails';
		break;
		case 'subcatmobile':
		  /* check whether any checkbox is ticked */
		           for(i=0;i<document.frmEditProductCategory.elements.length;i++)
					{
					if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name== checkboxname)
						{

							if (document.frmEditProductCategory.elements[i].checked==true)
								{
									atleastone = 1;
									if (ch_ids!='')
									ch_ids += '~';
					 				ch_ids += document.frmEditProductCategory.elements[i].value;
				 
					 				obj1 = eval("document.getElementById('cat_order_"+document.frmEditProductCategory.elements[i].value+"')");
				 
									if (ch_order != '')
									 ch_order += '~';
									 ch_order += obj1.value; 
							}	
						}
			}
		  // Case of saving the order
			atleastmsg 	= 'Please select the subcat(s) to be saved';
			confirmmsg 	= 'Are you sure you want to save the  order of selected categories?';
			retdivid   	= 'subcat_div';
			moredivid	= 'subcatunassign_div';
			fpurpose	= 'save_subcatdetailsmobile';
		break;
		case 'prods':
		  /* check whether any checkbox is ticked */
		           for(i=0;i<document.frmEditProductCategory.elements.length;i++)
					{
					if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name== checkboxname)
						{

							if (document.frmEditProductCategory.elements[i].checked==true)
								{
									atleastone = 1;
									if (ch_ids!='')
									ch_ids += '~';
					 				ch_ids += document.frmEditProductCategory.elements[i].value;
				 
					 				obj1 = eval("document.getElementById('prod_order_"+document.frmEditProductCategory.elements[i].value+"')");
				 
									if (ch_order != '')
									 ch_order += '~';
									 ch_order += obj1.value; 
							}	
						}
			}
		  // Case of saving the order
			atleastmsg 		= 'Please select the product(s) to be saved';
			confirmmsg 	= 'Are you sure you want to save the  order of selected product(s)?';
			retdivid   		= 'prod_div';
			moredivid		= 'produnassign_div';
			fpurpose		= 'save_proddetails';
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
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&edit_id='+editid+qrystr+'&ch_order='+ch_order+'&ch_title='+ch_title+'&ch_ids='+ch_ids+'&'+qrystr+'&shimg='+shimg+'&category_turnoff_moreimages='+category_turnoff_moreimages+'&category_turnoff_mainimage='+category_turnoff_mainimage);
		}	
	}
}
function call_ajax_savesettings(mod)
{
	var editid				= '<?php echo $edit_id?>';
	var qrystr				= '';
	var confirmmsg 			= '';
	switch(mod)
	{
			case 'display_settings':
				var category_subcatlisttype		= document.getElementById('category_subcatlisttype').value;
				var product_displaywhere 		= document.getElementById('product_displaywhere').value;
				var product_displaytype			= document.getElementById('product_displaytype').value;
				var subcategory_showimagetype = document.getElementById('subcategory_showimagetype').value;
				var category_subcatlistmethod	= document.getElementById('category_subcatlistmethod').value;
				var product_orderfield				= document.getElementById('product_orderfield').value;
				var product_orderby				= document.getElementById('product_orderby').value;
				
				if(document.frmEditProductCategory.category_showimage.checked)
						var category_showimage		= 1;
					else
						var category_showimage		= 0;
					if(document.frmEditProductCategory.category_showname.checked)
						var category_showname		= 1;
					else
						var category_showname		= 0;
					if(document.frmEditProductCategory.category_showshortdesc.checked)
						var category_showshortdesc		= 1;
					else
						var category_showshortdesc		= 0;
						
					if (category_showimage==0 && category_showname==0 && category_showshortdesc==0 )
					{
						alert('Please select atleast one field for subcategories');
						return false;
					}	
				if(document.frmEditProductCategory.product_showimage.checked)
					var product_showimage		= 1;
				else
					var product_showimage		= 0;
				if(document.frmEditProductCategory.product_showtitle.checked)
					var product_showtitle		= 1;
				else
					var product_showtitle		= 0;
				if(document.frmEditProductCategory.product_showshortdescription.checked)
					var product_showshortdescription		= 1;
				else
					var product_showshortdescription		= 0;
				if(document.frmEditProductCategory.product_showprice.checked)
					var product_showprice		= 1;
				else
					var product_showprice		= 0;
				if(document.frmEditProductCategory.product_showrating.checked)
					var product_showrating		= 1;
				else
					var product_showrating		= 0;
				if(document.frmEditProductCategory.product_showbonuspoints.checked)
					var product_showbonuspoints		= 1;
				else
					var product_showbonuspoints		= 0;
				if(document.frmEditProductCategory.display_to_guest)
				{
					if(document.frmEditProductCategory.display_to_guest.checked)
						var display_to_guest		= 1;								
				}		
				var enable_grid_display		= 0;
				var product_variables_group_id		= 0;
				var grid_column_cnt		= 12;
				//if(document.getElementById('enable_grid_display') != null)
				if(document.frmEditProductCategory.enable_grid_display)
				{
					if(document.frmEditProductCategory.enable_grid_display.checked)
						var enable_grid_display		= 1;						
						
					if(document.frmEditProductCategory.product_variables_group_id.value > 0)
						var product_variables_group_id		= document.frmEditProductCategory.product_variables_group_id.value;					
						
					if(document.frmEditProductCategory.grid_column_cnt.value > 0)
						var grid_column_cnt		= document.frmEditProductCategory.grid_column_cnt.value;					
				}
				
					
				if (product_showimage==0 && product_showtitle==0 && product_showshortdescription==0 && product_showprice==0 && product_showrating==0 && product_showbonuspoints==0)
				{
					alert('Please select atleast one field for products');
					return false;
				}	
				qrystr = '&category_subcatlisttype='+category_subcatlisttype+'&category_subcatlistmethod='+category_subcatlistmethod+'&product_displaywhere='+product_displaywhere+'&product_displaytype='+product_displaytype+'&product_showimage='+product_showimage+'&product_showtitle='+product_showtitle+'&product_showshortdescription='+product_showshortdescription+'&product_showprice='+product_showprice+'&subcategory_showimagetype='+subcategory_showimagetype+'&category_showimage='+category_showimage+'&category_showname='+category_showname+'&category_showshortdesc='+category_showshortdesc+'&product_orderfield='+product_orderfield+'&product_orderby='+product_orderby+'&product_showrating='+product_showrating+'&product_showbonuspoints='+product_showbonuspoints+'&product_variables_group_id='+product_variables_group_id+'&enable_grid_display='+enable_grid_display+'&grid_column_cnt='+grid_column_cnt+'&display_to_guest='+display_to_guest;
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
		Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&edit_id='+editid+qrystr);
	}	
}
function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var editid				= '<?php echo $edit_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var parentid							='<?php echo $_REQUEST['parentid']?>';
	var catgroupid						='<?php echo $_REQUEST['catgroupid']?>';
	var cname								='<?php echo $_REQUEST['catname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr								= 'catname='+cname+'&catgroupid='+catgroupid+'&parentid='+parentid+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
		if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name==checkboxname)
		{
			if (document.frmEditProductCategory.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditProductCategory.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
	case 'catimg': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the cateogry image(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Image(s)?';
			retdivid   	= 'catimg_div';
			moredivid	= 'catimgunassign_div';
			fpurpose	= 'unassign_catimagedetails';
		break;
		case 'subcat': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the categories to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected category?';
			retdivid   	= 'subcat_div';
			moredivid	= 'subcatunassign_div';
			fpurpose	= 'unassign_subcatdetails';
		break;
		case 'subcatmobile': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the categories to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected category?';
			retdivid   	= 'subcat_div';
			moredivid	= 'subcatunassign_div';
			fpurpose	= 'unassign_subcatdetailsmobile';
		break;
		case 'prods': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the product(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected product(s)?';
			retdivid   	= 'prod_div';
			moredivid	= 'produnassign_div';
			fpurpose	= 'unassign_prods';
		break;
		case 'labelgroup': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the label group(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected label group(s)?';
			retdivid   	= 'prod_div';
			moredivid	= 'produnassign_div';
			fpurpose	= 'unassign_labelgroup';
		break;
		case 'shops': 
		   
		// Case of saving the order
			atleastmsg 	= 'Please select the shop(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to to unassign the  selected shop(s)?';
			retdivid   	= 'prod_div';
			moredivid	= 'produnassign_div';
			fpurpose	= 'unassign_shop';
		break;
		case 'featprod': 
		   		// Case of deleting featured products under this category
					atleastmsg 	= 'Please select the Featured Product(s) to be unassigned';
					confirmmsg 	= 'Are you sure you want to to unassign the selected featured product(s)?';
					retdivid   	= 'featprod_div';
					moredivid	= 'featprod_unassign_div';
					fpurpose	= 'unassign_featprod';
				break;
		case 'variables': 
		   		// Case of deleting the variables
					atleastmsg 	= 'Please select the variable(s) to be deleted';
					confirmmsg 	= 'Are you sure you want to to delete the  selected variable(s)?';
					retdivid   	= 'prod_div';
					moredivid	= 'produnassign_div';
					fpurpose	= 'delete_variable';
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
			Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&edit_id='+editid+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_save_order(mod,checkboxname)
{
	var atleastone 			= 0;
	var cat_id			= '<?php echo $edit_id?>';
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
	switch(mod)
	{
		case 'shops': // Case of shops
			atleastmsg 	= 'Please select the Shops to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Shop(s)?';
			retdivid   	= 'shop_div';
			moredivid	= 'shop_unassign_div';
			fpurpose	= 'save_shoporder';
			orderbox	= 'shop_sort_';
		break;
		case 'featprod': // Case of featured products
			atleastmsg 	= 'Please select the featured products to Change the Sort Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			retdivid   	= 'featprod_div';
			moredivid	= 'featprod_unassign_div';
			fpurpose	= 'save_featprodorder';
			orderbox	= 'featprod_sort_';
		break;
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
	if (document.frmEditProductCategory.elements[i].type =='checkbox' && document.frmEditProductCategory.elements[i].name== checkboxname)
		{
		

			if (document.frmEditProductCategory.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProductCategory.elements[i].value;
				 obj = eval("document.getElementById('"+orderbox+document.frmEditProductCategory.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
			}	
		}
	}
	
	if(atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{ 
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&cat_id='+cat_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function call_save_seo(mod)
{
	var atleastone 			= 0;
	var cat_id			= '<?php echo $edit_id?>';
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
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{  
	if (document.frmEditProductCategory.elements[i].type =='text' && document.frmEditProductCategory.elements[i].name.substr(0,7)== 'keyword')
		{			
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProductCategory.elements[i].value;			
		}
	}
	atleastmsg = "Enter the Title";
	var page_title = '';
	var meta ='';
	page_title = document.frmEditProductCategory.page_title.value;
	meta       = document.frmEditProductCategory.page_meta.value;
	qrystr +='&page_title='+page_title+'&page_meta='+meta;	 
	//if(page_title=='')
	//{
		//alert(atleastmsg);
	//}
	//else
	{
		
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&cat_id='+cat_id+'&ch_ids='+ch_ids+'&'+qrystr);
			
	}	
}
function handle_imagesel(id)
{
	var ret_str	= '';
	var new_str = ''
	tdobj		= eval("document.getElementById('img_td_"+id+"')");
	if(tdobj.className=='imagelistproducttabletd')
	{
		tdobj.className = 'imagelistproducttabletd_sel';
	}	
	else
	{
		tdobj.className = 'imagelistproducttabletd';
	}	
}
function normal_assign_SubCategoryAssign(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=subcategoryAssign&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
function normal_assign_SubCategoryAssignMobile(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=subcategoryAssignMobile&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
function normal_assign_ProductAssign(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=productAssign&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
function normal_assign_ImageAssign(src_id,cname,catgroupid,parentid,sortby,sortorder,recs,start,pg)
{
		window.location 			= 'home.php?request=img_gal&src_page=prodcat&src_id='+src_id+'&catname='+cname+'&catgroupid='+catgroupid+'&parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg;
}
function normal_assign_ProductLabelGroupAssign(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=productLableGroupAssign&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
function normal_assign_shops(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=shop_sel&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
function normal_assign_featprod(cname,catgroupid,parentid,sortby,sortorder,recs,start,pg,catid)
{
		window.location 			= 'home.php?request=prod_cat&fpurpose=featprod_sel&pass_catname='+cname+'&pass_catgroupid='+catgroupid+'&pass_parentid='+parentid+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cat_id='+catid;
}
/* Google base category list change starts here */
function call_ajax_setgooglebase()
{
	var fpurpose = 'list_googlebase_cate';
	document.getElementById('set_googlebase').style.display	=	'none';
	document.getElementById('googlebase_cat_id').innerHTML	=	'<img src="/console/images/loading.gif" width="31" height="31" alt="Loading" align="middle" />';
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&faction=edit');
}

function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;				
			chkStr		= 0;
			chkStr		=	ret_val.search("google_product_category");
			if(chkStr > 0)
			{
				targetobj 	= eval("document.getElementById('googlebase_cat_id')");
			}
			else
			{
				targetdiv 	= document.getElementById('retdiv_id').value;
				targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			}
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
		}	
	}
}
/* Google base category list change ends here */
function show_googlecategorypopup()
{
	var qrystr														= '';
	var ch_ids   = '';
	var atleastone = 0;
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
		if (document.frmEditProductCategory.elements[i].type =='hidden' && document.frmEditProductCategory.elements[i].name== 'category_id[]')
			{			
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProductCategory.elements[i].value;
			}
	}	
	if(atleastone>0)
	{
		 qrystr +='ch_ids='+ch_ids;
	}
		qrystr +='&mod=edit';

	var fpurpose													= 'show_googlecategory_popup';
	document.getElementById('retdiv_id').value 						= 'moveto_googlecategory_div';
	obj																= eval("document.getElementById('moveto_googlecategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	showme('#moveto_googlecategory_div');
			if(document.getElementById('moveto_googlecategory_div'))
			{
			$ajax_j('html, body').animate({
					scrollTop: $ajax_j("#moveto_googlecategory_div").offset().top
				}, 500);
			}
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&'+qrystr);
} 
jQuery.noConflict();
		var $ajax_j = jQuery; 
function call_cancel()
{
	document.getElementById('moveto_googlecategory_div').style.display ='none';
	if(document.getElementById('googlebase_cat_id'))
	{
	 $ajax_j('html, body').animate({
                        scrollTop: $ajax_j("#googlebase_cat_id").offset().top
                    }, 500);
    }               
			document.getElementById('retdiv_id').value ="master_div"; 

	//document.getElementById('popup_bg_div').style.display='none';

}
function autocomplete_key()
{
<!-- Script for auto complete starts here -->
var $pnc = jQuery.noConflict();
	auto_search('catname_popg','google_taxonomy'); 
<!-- Script for auto complete ends here -->
}
function call_ajax_onkeyup()
{
	var qrystr														= '';
	var fpurpose													= 'show_googlecategory_popup';
	var catname      												= document.getElementById('catname_popg').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
	   
	   if ((document.frmEditProductCategory.elements[i].type =='hidden') )
		{
			if(document.frmEditProductCategory.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProductCategory.elements[i].value;				
			}
		}
	}	
	qrystr = 'catname='+catname+'&perpage='+perpage+'&ch_ids='+ch_ids;;
	qrystr +='&mod=edit';

	document.getElementById('retdiv_id').value 						= 'moveto_googlecategory_div';
	obj																= eval("document.getElementById('moveto_googlecategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&catname='+catname+'&'+qrystr);

}
function call_ajax_page(invid,page)
{
	var qrystr														= '';
	var fpurpose													= 'show_googlecategory_popup';
	var catname      												= document.getElementById('catname_popg').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var ch_ids ='';
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	
	var is_active													='';
		if(document.getElementById('is_active').checked==true)
		{					
					 is_active = "Y";				
		}
		else
		{
					is_active = "N";	
		}
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
	   
	   if ((document.frmEditProductCategory.elements[i].type =='hidden') )
		{
			if(document.frmEditProductCategory.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProductCategory.elements[i].value;				
			}
		}
	}	
	qrystr = 'catname='+catname+'&perpage='+perpage+'&ch_ids='+ch_ids+'&is_active='+is_active;
	qrystr +='&mod=edit';

	document.getElementById('retdiv_id').value 						= 'moveto_googlecategory_div';
	obj																= eval("document.getElementById('moveto_googlecategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	//Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&page='+page+'&'+qrystr);
    $ajax_j.ajax({
        url: 'services/product_category.php',
        type: 'POST',
		cache: false,
		 data: { catname: catname,perpage:perpage, page:page,fpurpose:fpurpose,is_active:is_active },
		dataType: "html",
        success: function (resp) {
            data = resp;
            obj.innerHTML 			= data;
        },
        error: function () {}
    });
}
function call_ajax_search()
{
	var qrystr														= '';
	var fpurpose													= 'show_googlecategory_popup';
	var catname      												= document.getElementById('catname_popg').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var is_active													='';
		if(document.getElementById('is_active').checked==true)
		{					
					 is_active = "Y";				
		}
		else
		{
					is_active = "N";	
		}
    var ch_ids ='';

    for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
	   
	   if ((document.frmEditProductCategory.elements[i].type =='hidden') )
		{
			if(document.frmEditProductCategory.elements[i].name== 'passcheckbox_assigncat[]')
			{					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProductCategory.elements[i].value;				
			}
		}
	}		
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	qrystr = '&perpage='+perpage+'&ch_ids='+ch_ids+'&catname='+catname+'&is_active='+is_active;
	qrystr +='&mod=edit';

	document.getElementById('retdiv_id').value 						= 'moveto_googlecategory_div';
	obj																= eval("document.getElementById('moveto_googlecategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	//Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&'+qrystr);
	 $ajax_j.ajax({
        url: 'services/product_category.php',
        type: 'POST',
		cache: false,
		 data: { catname: catname,perpage:perpage, is_active: is_active, fpurpose:fpurpose },
		dataType: "html",
        success: function (resp) {
            data = resp;
            obj.innerHTML 			= data;
        },
        error: function () {}
    });
}
function is_active_cat()
{
	var qrystr														= '';
	var fpurpose													= 'show_googlecategory_popup';
	var catname      												= document.getElementById('catname_popg').value ;
    var perpage														= document.getElementById('perpage_pop').value ;
    var is_active = '';
    var ch_ids ='';
   
			if(document.getElementById('is_active').checked==true)
			{					
						 is_active = "Y";				
			}
			else
			{
						is_active = "N";	
			}
		
    //var catgroup                                                    = document.getElementById('catgroupid_pop').value ;
	qrystr = '&perpage='+perpage+'&ch_ids='+ch_ids+'&catname='+catname+'&is_active='+is_active;
	qrystr +='&mod=edit';

	document.getElementById('retdiv_id').value 						= 'moveto_googlecategory_div';
	obj																= eval("document.getElementById('moveto_googlecategory_div')");
	obj.innerHTML 													= '<center><img src="images/loading.gif" alt="Loading"></center>';
	//Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&'+qrystr);
	$ajax_j.ajax({
        url: 'services/product_category.php',
        type: 'POST',
		cache: false,
		 data: { catname: catname,perpage:perpage, fpurpose:fpurpose,is_active:is_active },
		dataType: "html",
        success: function (resp) {
            data = resp;
            obj.innerHTML 			= data;
        },
        error: function () {}
    });

}
function call_ajax_assign_category(invid)
{
	var ch_ids     ='';
	var qrystr     = '';
	var atleastone = 0;
	var fpurpose													= 'assign_googlecategory_popup';
	var defval;
	var atleastmsg = 'Please Select One Category';
	for(i=0;i<document.frmEditProductCategory.elements.length;i++)
	{
	   
	   if ((document.frmEditProductCategory.elements[i].type =='checkbox' || document.frmEditProductCategory.elements[i].type =='hidden') )
		{ 
			if (document.frmEditProductCategory.elements[i].name.substring(0,20)=='default_category_id_')
			{  
				if(document.frmEditProductCategory.elements[i].checked==true)
				{
					defval   = document.frmEditProductCategory.elements[i].value;
				}
			}
			if(document.frmEditProductCategory.elements[i].name== 'checkbox_assigncat[]')
			{
				if (document.frmEditProductCategory.elements[i].checked==true)
				{  
					atleastone ++;
					if (ch_ids!='')
						ch_ids += '~';
					 ch_ids += document.frmEditProductCategory.elements[i].value;
				} 
			}
			if(document.frmEditProductCategory.elements[i].name== 'passcheckbox_assigncat[]')
			{
					
						if (ch_ids!='')
							ch_ids += '~';
						 ch_ids += document.frmEditProductCategory.elements[i].value;				
			}
		}
	}
	qrystr += '&defval='+defval;
	if (atleastone==0)
	{
		alert(atleastmsg);
		return false;
	}
	else if(atleastone>1)
	{
	    alert("Select One Category only");
		return false;
	}
	else
	{	
		hideme('moveto_googlecategory_div');
		document.frmEditProductCategory.google_product_category_new.value=ch_ids;
		document.getElementById('moveto_googlecategory_div').style.display ='none';
		if(document.getElementById('googlebase_cat_id'))
			{
		    $ajax_j('html, body').animate({
                        scrollTop: $ajax_j("#googlebase_cat_id").offset().top
                    }, 500);
            }       
	}
   	
}
function showme(id)
	{		
		$ajax_j(id).show();
	}
	function hideme(id)
	{
		$ajax_j(id).hide();
		$ajax_j(id).hide();
	}

function save_varsetting(catid)
{
	if(document.getElementById('enable_searchrefine').checked)
		var enablevar	=	1;
	else
		var enablevar	=	0;
		
	Handlewith_Ajax('services/product_category.php','fpurpose=save_varsetting&cat_id='+catid+'&set_val='+enablevar);
}

/*function call_ajax_updgooglebase(cate_id)
{
	//alert(cate_id);
	var fpurpose = 'sel_googlebase_cate';
	Handlewith_Ajax('services/product_category.php','fpurpose='+fpurpose+'&cate_id='+cate_id);
}
function ajax_return_cate()
{
	var ret_val = '';
	var dis_dat = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			//alert('status 200');
			ret_val 	= req.responseText;
			dis_dat		= ret_val.split("-");
			document.getElementById('google_category_selected').innerHTML = dis_dat[1]; 
			document.getElementById('google_product_category').innerHTML = dis_dat[0]; 
		}	
	}
}*/
<?php
	if($ecom_gridenable > 0)
	{
?>
function showGroupList()
{
	if(document.getElementById('enable_grid_display').checked == true)
	{
		document.getElementById('show_group_list').style.display = 'block';
	}
	else
	{
		document.getElementById('show_group_list').style.display = 'none';
	}
}
<?php
	}
?>
</script>
<form name='frmEditProductCategory' action='home.php?request=prod_cat' method="post" onsubmit="return valforms(this);">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat&amp;catname=<?=$_REQUEST['catname']?>&amp;parentid=<?=$_REQUEST['parentid']?>&amp;catgroupid=<?=$_REQUEST['catgroupid']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">List Product Categories</a><span> Edit Product Category</span></div></td>
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
				<div style="top: 0px; left: 0px; position: fixed; display: none;" class="flashvideo_outer" id="div_defaultFlash_outer"></div>
          <div id="moveto_googlecategory_div" class="processing_divcls_big_heightA" style="display:none" >
	</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td align="left" onClick="handle_tabs('main_tab_td','catmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td align="left" onClick="handle_tabs('category_tab_td','subcategory')" class="<?php if($curtab=='category_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="category_tab_td"><span>Sub Categories <?php if($ecom_site_mobile_api==1){ echo '(Web)';}?></span></td>
						<?php 
						if($ecom_site_mobile_api==1) 
						{
						?>
						<td align="left" onClick="handle_tabs('categorymobile_tab_td','subcategorymobile')" class="<?php if($curtab=='categorymobile_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="categorymobile_tab_td"><span>Sub Categories (Mob App)</span></td>
						<?php
						}
						?>
						<td align="left" onClick="handle_tabs('product_tab_td','prods')" class="<?php if($curtab=='product_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="product_tab_td"><span>Products Under this Category</span></td>
						<td align="left" onClick="handle_tabs('image_tab_td','images')" class="<?php if($curtab=='image_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="image_tab_td"><span>Images</span></td>
						<td align="left" onClick="handle_tabs('settings_tab_td','settings')" class="<?php if($curtab=='settings_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="settings_tab_td"><span>Display Settings</span></td>
						<?php
						if($label_group_td!='')
						{
						?>
							<td align="left" onClick="handle_tabs('label_tab_td','labelgroup')" class="<?php if($curtab=='label_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="label_tab_td"><span>Product Label Groups</span></td>
						<?php
						}
						?>
						<td align="left" onClick="handle_tabs('shops_tab_td','shops')" class="<?php if($curtab=='shops_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shops_tab_td"><span>Shops for this Category</span></td>
						<td align="left" onClick="handle_tabs('seo_tab_td','seo')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings </span></td>
						<td align="left" onClick="handle_tabs('featured_tab_td','featured')" class="<?php if($curtab=='featured_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="featured_tab_td"><span>Set Featured Products</span></td>
                        <?php 
						if($ecom_enable_searchrefine_category==1) 
						{
						?>
						<td align="left" onClick="handle_tabs('variables_tab_td','variables')" class="<?php if($curtab=='variables_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="variables_tab_td"><span>Search Refine</span></td>
						<?php
						}
						?>
						<td width="99%" align="left">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
  <?php
			if($alert)
			{
		?>
  <tr>
    <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
  </tr>
  <?php
		 	}
		 ?>
	
	<tr>
	<td colspan="4">
	<div id='master_div'>
	<?php
		if ($curtab=='category_tab_td')
		{
			show_subcat_list($edit_id,$alert);
		}
		if ($curtab=='categorymobile_tab_td')
		{
			show_subcat_list($edit_id,$alert,1);
		}
		elseif ($curtab=='product_tab_td')
		{
			show_product_list($edit_id,$alert);
		}
		elseif ($curtab=='image_tab_td')
		{
			show_catimage_list($edit_id,$alert);
		}
		elseif ($curtab=='settings_tab_td')
		{
			show_category_settings($edit_id,$alert);
		}
		elseif ($curtab=='main_tab_td') // done to handle the case of showing the category details info details when loading the page for the first time
		{
			show_catmaininfo($edit_id,$alert);
		}
		elseif ($curtab=='label_tab_td') // done to handle the case of showing the category details info details when loading the page for the first time
		{
			list_labelgroups($edit_id,$alert);
		}
		elseif ($curtab=='shops_tab_td') // done to handle the case of showing the category details info details when loading the page for the first time
		{
			show_shop_category_list($edit_id,$alert);
		}
		elseif ($curtab=='featured_tab_td') // done to handle the case of showing the category details info details when loading the page for the first time
		{
			show_featuredprod_list($edit_id,$alert);
		}
		elseif ($curtab=='variables_tab_td') // done to handle the case of showing the category details info details when loading the page for the first time
		{
			show_category_variables($edit_id,$alert,$_REQUEST);
		}
?>
	</div>	</td>
	</tr>
</table>
		<input type="hidden" name="catname" id="catname" value="<?=$_REQUEST['catname']?>" />
        <input type="hidden" name="parentid" id="parentid" value="<?=$_REQUEST['parentid']?>" />
        <input type="hidden" name="catgroupid" id="catgroupid" value="<?=$_REQUEST['catgroupid']?>" />
        <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
        <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
        <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
        <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
        <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
        <input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $edit_id?>" />
		<input type="hidden" name="src_page" id="src_page" value="prodcat" />
		<input type="hidden" name="src_id" id="src_id" value="<?php echo $edit_id?>" />
		<input type="hidden" name="search_in_mobile_application" id="search_in_mobile_application" value="<?php echo $search_in_mobile_application?>" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
</form>	  

<script language="javascript">
//display_cat_image();
function display_cat_image(){
	if(document.frmEditProductCategory.category_showimageofproduct.checked == true){
//document.getElementById('cat_image_display').style.display = 'none'; 
//
document.getElementById('cat_image_tr1').style.display = 'none';
document.getElementById('cat_image_tr2').style.display = 'none';
document.getElementById('catimg_tr').style.display = 'none';

	}else if(document.frmEditProductCategory.category_showimageofproduct.checked == false){
		document.getElementById('cat_image_tr1').style.display = '';
document.getElementById('cat_image_tr2').style.display = '';
document.getElementById('catimg_tr').style.display = '';
	}
}