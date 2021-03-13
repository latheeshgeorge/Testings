<?php
	/*#################################################################
	# Script Name 	: edit_shelf.php
	# Description 	: Page for editing Site Shelf
	# Coded by 		: SKR
	# Created on	: 19-July-2007
	# Modified by	: SKR
	# Modified On	: 31-July-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Shelf';
$help_msg = get_help_messages('EDIT_SHELVES_SHOW_MESS1');
$shelf_id=($_REQUEST['shelf_id']?$_REQUEST['shelf_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_shelf="SELECT shelf_name,shelf_description,shelf_order,shelf_hide,shelf_displaytype,
				   shelf_showinall,shelf_showimage,shelf_showtitle,shelf_showdescription,
				   shelf_showprice,shelf_showrating,shelf_currentstyle,shelf_activateperiodchange,shelf_displaystartdate,
				   shelf_displayenddate,shelf_showinhome 
				   		FROM product_shelf  
							WHERE shelf_id=".$shelf_id." AND sites_site_id='".$ecom_siteid."'";
$res_shelf= $db->query($sql_shelf);
if($db->num_rows($res_shelf)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_shelf = $db->fetch_array($res_shelf);
$showinall = $row_shelf['shelf_showinall'];
// Find the feature_id for mod_shelf module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelf'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
	
	// Find the display settings details for this shelf
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelf_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
	
	$editor_elements = "shelf_description";
	include_once(ORG_DOCROOT."/console/js/tinymce.php");
	$css_filename = "http://$ecom_hostname/console/css/editor.css";
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('shelf_name','display_id[]');
	fieldDescription = Array('Shelf Name','Shelf Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('shelf_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.shelf_activateperiodchange.checked  ==true){
			val_dates = compareDates(frm.shelf_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.shelf_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}
		/*if(frm.shelf_showimage.checked==false && frm.shelf_showtitle.checked==false && frm.shelf_showdescription.checked==false && frm.shelf_showprice.checked==false) 
		{
			    alert('Please Check any of Fields Items to Display in Shelf ');	   
				return false;    
		}
		else{
		show_processing();
		return true;
		}*/
	} else {
		return false;
	}
}
function handle_showclick(mod)
{ 
	if (mod=='showinall')
	{
		if (document.frmEditShelf.shelf_showinall.checked)
			document.frmEditShelf.shelf_showinhome.checked = false;
	}
	else
	{
		if (document.frmEditShelf.shelf_showinhome.checked)
			document.frmEditShelf.shelf_showinall.checked = false;
	}		
}
function handle_expansion(imgobj,mod)
{

	var src = imgobj.src;
	var retindx = src.search('plus.gif');
	switch(mod)
	{
		case 'product_shelf':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('product_shelftr_details'))
					document.getElementById('product_shelftr_details').style.display = '';
				if(document.getElementById('product_shelfunassign_div'))
					document.getElementById('product_shelfunassign_div').style.display = '';	
				call_ajax_showlistall('product_shelf');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('product_shelftr_details'))
					document.getElementById('product_shelftr_details').style.display = 'none';
				
				if(document.getElementById('product_shelftrtr_norec'))
					document.getElementById('product_shelftrtr_norec').style.display = 'none';
				
				if(document.getElementById('product_shelfunassign_div'))
					document.getElementById('product_shelfunassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_product_shelf':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_product_shelftr_details'))
					document.getElementById('display_product_shelftr_details').style.display = '';
				
				if(document.getElementById('display_product_shelfunassign_div'))
					document.getElementById('display_product_shelfunassign_div').style.display = '';	
				
				call_ajax_showlistall('display_product_shelf');	
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_product_shelftr_details'))
					document.getElementById('display_product_shelftr_details').style.display = 'none';
				
				if(document.getElementById('display_product_shelfunassign_div'))
					document.getElementById('display_product_shelfunassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_category_shelf':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_category_shelftr_details'))
					document.getElementById('display_category_shelftr_details').style.display = '';
				
					
				if(document.getElementById('display_category_shelfunassign_div'))
					document.getElementById('display_category_shelfunassign_div').style.display = '';	
				call_ajax_showlistall('display_category_shelf');	
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_category_shelftr_details'))
					document.getElementById('display_category_shelftr_details').style.display = 'none';
				
				
				if(document.getElementById('display_category_shelfunassign_div'))
					document.getElementById('display_category_shelfunassign_div').style.display = 'none';	
				
			}	
		break;
		case 'display_static_shelf':
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('display_static_shelftr_details'))
					document.getElementById('display_static_shelftr_details').style.display = '';
				
				if(document.getElementById('display_static_shelfunassign_div'))
					document.getElementById('display_static_shelfunassign_div').style.display = '';	
				call_ajax_showlistall('display_static_shelf');		
				
				
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('display_static_shelftr_details'))
					document.getElementById('display_static_shelftr_details').style.display = 'none';
				
				if(document.getElementById('display_static_shelfunassign_div'))
					document.getElementById('display_static_shelfunassign_div').style.display = 'none';	
				
			}	
		break;
	 };

}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var shelf_id										= '<?php echo $shelf_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'product_shelf': // Case of Products in the shelf
			retdivid   	= 'productshelf_div';
			fpurpose	= 'list_productshelf';
			moredivid	= 'product_shelfunassign_div';
			
		break;
		case 'display_product_shelf': // Case of Display Products 
			retdivid   	= 'display_productshelf_div';
			fpurpose	= 'list_display_productshelf';
			moredivid	= 'display_product_shelfunassign_div';
		break;  
		case 'display_category_shelf': // Case of Display Products 
			retdivid   	= 'display_categoryshelf_div';
			fpurpose	= 'list_display_categoryshelf';
			moredivid	= 'display_category_shelfunassign_div';
		break;
		case 'display_static_shelf': // Case of Display Products 
			retdivid   	= 'display_staticshelf_div';
			fpurpose	= 'list_display_staticshelf';
			moredivid	= 'display_static_shelfunassign_div';
		break;		
	}	
    document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id);		
}		
function normal_assign_prodShelfAssign(searchname,sortby,sortorder,recs,start,pg,shelfid)
{
		window.location 			= 'home.php?request=shelfs&fpurpose=prodShelfAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelf_id='+shelfid;
}
function normal_assign_displayProdShelfAssign(searchname,sortby,sortorder,recs,start,pg,shelfid)
{
		window.location 			= 'home.php?request=shelfs&fpurpose=displayProdShelfAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelf_id='+shelfid;
}
function normal_assign_displayCategoryShelfAssign(searchname,sortby,sortorder,recs,start,pg,shelfid)
{
		window.location 			= 'home.php?request=shelfs&fpurpose=displayCategoryShelfAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelf_id='+shelfid;
}
function normal_assign_displayStaticShelfAssign(searchname,sortby,sortorder,recs,start,pg,shelfid)
{
		window.location 			= 'home.php?request=shelfs&fpurpose=displayStaticShelfAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelf_id='+shelfid;
}
function normal_assign_displayShopShelfAssign(searchname,sortby,sortorder,recs,start,pg,shelfid)
{
		window.location 			= 'home.php?request=shelfs&fpurpose=displayShopShelfAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelf_id='+shelfid;
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var shelf_id			= '<?php echo $shelf_id?>';
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinall?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;

	switch(mod)
	{
		case 'product_shelf': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			//retdivid   	= 'productshelf_div';
			//moredivid	= 'product_shelfunassign_div';
			fpurpose	= 'save_order';
			orderbox	= 'product_shelf_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShelf.elements.length;i++)
	{
	if (document.frmEditShelf.elements[i].type =='checkbox' && document.frmEditShelf.elements[i].name== checkboxname)
		{
		

			if (document.frmEditShelf.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditShelf.elements[i].value;
				
				 obj = eval("document.getElementById('"+orderbox+document.frmEditShelf.elements[i].value+"')");
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
			//document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		 //	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var shelf_id			= '<?php echo $shelf_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinall?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShelf.elements.length;i++)
	{
		if (document.frmEditShelf.elements[i].type =='checkbox' && document.frmEditShelf.elements[i].name==checkboxname)
		{

			if (document.frmEditShelf.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditShelf.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_shelf': // Case of Products in the shelf
			atleastmsg 	= 'Please select the Product(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the shelf?';
		//	retdivid   	= 'productshelf_div';
		//	moredivid	= 'product_shelfunassign_div';
			fpurpose	= 'prodShelfUnAssign';
		break;
		case 'display_product_shelf':// Case of Display Products in the shelf
			atleastmsg 	= 'Please select the Product(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the shelf?';
		//	retdivid   	= 'display_productshelf_div';
		//	moredivid	= 'display_product_shelfunassign_div';
			fpurpose	= 'displayProdShelfUnAssign';
		break;
		case 'display_category_shelf':// Case of Display Categories in the shelf
			atleastmsg 	= 'Please select the Category(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the shelf?';
		//	retdivid   	= 'display_categoryshelf_div';
		//	moredivid	= 'display_category_shelfunassign_div';
			fpurpose	= 'displayCategoryShelfUnAssign';
		break;
		case 'display_static_shelf':// Case of Display Static Pages in the shelf
			atleastmsg 	= 'Please select the Static Page(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Static Page(s) from the shelf?';
		//	retdivid   	= 'display_staticshelf_div';
		//	moredivid	= 'display_static_shelfunassign_div';
			fpurpose	= 'displayStaticShelfUnAssign';
		break;
		case 'display_shop_shelf':// Case of Display Static Pages in the shelf
			atleastmsg 	= 'Please select the Shop(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Shop(s) from the shelf?';
		//	retdivid   	= 'display_staticshelf_div';
		//	moredivid	= 'display_static_shelfunassign_div';
			fpurpose	= 'displayShopShelfUnAssign';
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
		//	document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		//	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id+'&del_ids='+del_ids+'&'+qrystr);
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
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function change_show_date_period()
{
	
	if(document.frmEditShelf.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}

function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','products_tab_td','shelfprod_tab_td','shelfcategories_tab_td','static_tab_td','shop_tab_td','display_tab_td','seo_tab_td');  
	var atleastone 							= 0;
	var shelf_id							= '<?php echo $shelf_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&shelf_id='+shelf_id+'&curtab='+curtab+'&showinall='+showinall+'&advert_title='+advert_title;
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
	document.getElementById('pass_tab').value = '';
	switch(mod)
	{
		case 'shelfmain_info':
			/*fpurpose ='list_shelf_maininfo';
			document.getElementById('pass_tab').value = 'main';*/
			document.frmEditShelf.fpurpose.value = 'list_shelf_maininfo';
			document.frmEditShelf.submit();
			return;
		break;
		      
		case 'displayproducts_group': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_productshelf';
			//moredivid	= 'category_groupunassign_div';
		break;  
		case 'displayshelfproduct_group': // Case of Display Products in the group 
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_productshelf';
			//moredivid	= 'displayproduct_groupunassign_div';
		break;
		case 'displayshelfcateg_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_categoryshelf';
			//moredivid	= 'displaycategory_groupunassign_div';
		break; 
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_staticshelf';
			//moredivid	= 'displaycategory_groupunassign_div';
		break; 
		case 'displayshop_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_shopshelf';
			//moredivid	= 'displaycategory_groupunassign_div';
		break; 
		case 'display_settings': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_display_settings';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		case 'seo': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_seo';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
//	document.getElementById('retdiv_more').value = id;															
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id+'&'+qrystr);	
}
function call_ajax_savesettings(mod)
{
	var shelf_id				= '<?php echo $shelf_id?>';
	var qrystr				= '';
	var confirmmsg 			= '';
	switch(mod)
	{
			case 'display_settings':
				var shelf_displaytype	= document.getElementById('shelf_displaytype').value;
				var shelf_currentstyle	= document.getElementById('shelf_currentstyle').value;
				
				if(document.frmEditShelf.shelf_showimage.checked)
					var shelf_showimage		= 1;
				else
					var shelf_showimage		= 0;
				if(document.frmEditShelf.shelf_showtitle.checked)
					var shelf_showtitle		= 1;
				else
					var shelf_showtitle		= 0;
				if(document.frmEditShelf.shelf_showdescription.checked)
					var shelf_showdescription		= 1;
				else
					var shelf_showdescription		= 0;
				if(document.frmEditShelf.shelf_showprice.checked)
					var shelf_showprice		= 1;
				else
					var shelf_showprice		= 0;
				if(document.frmEditShelf.shelf_showrating.checked)
					var shelf_showrating		= 1;
				else
					var shelf_showrating		= 0;
				if(document.frmEditShelf.shelf_showbonuspoints.checked)
					var shelf_showbonuspoints		= 1;
				else
					var shelf_showbonuspoints		= 0;	
					if (shelf_showimage==0 && shelf_showtitle==0 && shelf_showdescription==0 && shelf_showprice==0 && shelf_showrating==0 && shelf_showbonuspoints=='')
					{
						alert('Please select atleast one field for Shelfs');
						return false;
					}	
					else if(document.getElementById('shelf_currentstyle').value=='')
					{
					 alert('Please select any listing style');
						return false;
					}
				qrystr = '&shelf_displaytype='+shelf_displaytype+'&shelf_showimage='+shelf_showimage+'&shelf_showtitle='+shelf_showtitle+'&shelf_showdescription='+shelf_showdescription+'&shelf_showprice='+shelf_showprice+'&shelf_currentstyle='+shelf_currentstyle+'&shelf_showrating='+shelf_showrating+'&shelf_showbonuspoints='+shelf_showbonuspoints;
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
		
		Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id+qrystr);
	}	
}
function call_save_seo(mod)
{
	var atleastone 			= 0;
	var editid				= <?php echo ($shelf_id)?$shelf_id:0?>;
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
	for(i=0;i<document.frmEditShelf.elements.length;i++)
	{  
	if (document.frmEditShelf.elements[i].type =='text' && document.frmEditShelf.elements[i].name.substr(0,7)== 'keyword')
		{			
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditShelf.elements[i].value;			
		}
	}
	atleastmsg = "Enter the Title";
	var page_title = '';
	var meta ='';
	page_title = document.frmEditShelf.page_title.value;
	meta       = document.frmEditShelf.page_meta.value;
	qrystr +='&page_title='+page_title+'&page_meta='+meta;	 
	//if(page_title=='')
	//{
		//alert(atleastmsg);
	//}
	//else
	{
		
			retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+editid+'&ch_ids='+ch_ids+'&'+qrystr);
			
	}	
}
</script>
<form name='frmEditShelf' action='home.php?request=shelfs' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfs&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shelves</a><span> Edit Shelf for '<? echo $row_shelf['shelf_name'];?>'</span></div></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
		  		$help_msg = get_help_messages('EDIT_SHELVES_SHOW_MESS1');
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','shelfmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('products_tab_td','displayproducts_group')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Products in this Shelf</span></td>
						<td align="left" onClick="handle_tabs('display_tab_td','display_settings')" class="<?php if($curtab=='display_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="display_tab_td"><span>Display Settings</span></td>
						<td  align="left" onClick="handle_tabs('shelfprod_tab_td','displayshelfproduct_group')" class="<?php if($curtab=='shelfprod_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfprod_tab_td"><span>Show Shelf for Products</span></td>
						<td align="left" onClick="handle_tabs('shelfcategories_tab_td','displayshelfcateg_group')" class="<?php if($curtab=='shelfcategories_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfcategories_tab_td"><span>Show Shelf for Categories</span></td>
						<td  align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="static_tab_td"><span>Show Shelf for Static Pages</span></td>
						<td  align="left" onClick="handle_tabs('shop_tab_td','displayshop_group')" class="<?php if($curtab=='shop_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shop_tab_td"><span>Show Shelf for Shops</span></td>
				        <td  align="left" onClick="handle_tabs('seo_tab_td','seo')" class="<?php if($curtab=='seo_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="seo_tab_td"><span>SEO Settings</span></td>
						<td width="90%" align="left">&nbsp;</td>
				</tr> 
		</table>		  </td>
        </tr>
		<tr>
		<td>
			  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				include("classes/fckeditor.php");
				show_shelf_maininfo($shelf_id,$alert);
			}
			elseif ($curtab=='shelfcategories_tab_td')
			{
				show_display_category_shelf_list($shelf_id,$alert);
			}
			elseif ($curtab=='shelfprod_tab_td')
			{
				show_display_product_shelf_list($shelf_id,$alert);
			}
			elseif ($curtab=='products_tab_td')
			{
				show_product_shelf_list($shelf_id,$alert);
			}
			elseif ($curtab=='static_tab_td')
			{
				show_display_static_shelf_list($shelf_id,$alert);
			}
			elseif ($curtab=='shop_tab_td')
			{
				show_display_shop_shelf_list($shelf_id,$alert);
			}
			elseif ($curtab=='display_tab_td')
			{
				show_display_settings($shelf_id,$alert);
			}
			?>		
		  </div>		</td>
        </tr>
		
        <tr>
          
          <td colspan="2" align="center" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="shelf_id" id="shelf_id" value="<?=$shelf_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		   <input type="hidden" name="pass_searchname" id="pass_searchname" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pass_sortby" id="pass_sortby" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="pass_sortorder" id="pass_sortorder" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="pass_tab" id="pass_tab" value="" />
  		 </td>
        </tr>
		<tr>
		<td colspan="2">&nbsp;		</td>
		</tr>
      </table>
</form>	  

