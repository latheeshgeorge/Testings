<?php
	/*#################################################################
	# Script Name 	: edit_shelfgroup.php
	# Description 	: Page for editing Site Shelf  group
	# Coded by 		: Joby
	# Created on	: 29-Apr-2011
	# Modified by	:
	# Modified On	:
	#################################################################*/
#Define constants for this page
$page_type = 'Shelf Group';
$help_msg = get_help_messages('EDIT_SHELF_MENU_SHOW_MESS1');
$id=($_REQUEST['shelfgroup_id']?$_REQUEST['shelfgroup_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_shelf="SELECT name,hide,showinall
				   		FROM shelf_group
							WHERE id=".$id." AND sites_site_id='".$ecom_siteid."'";
$res_shelf= $db->query($sql_shelf);
if($db->num_rows($res_shelf)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
$row_shelf = $db->fetch_array($res_shelf);
$showinall = $row_shelf['showinall'];
// Find the feature_id for mod_shelf module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelfgroup'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
	
	
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('name','display_id[]');
	fieldDescription = Array('Shelf Menu Name','Shelf Menu Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
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
		
	} else {
		return false;
	}
}
function handle_showclick(mod)
{ 
	if (mod=='showinall')
	{
		if (document.frmEditShelf.showinall.checked)
			document.frmEditShelf.showinhome.checked = false;
	}
	else
	{
		if (document.frmEditShelf.showinhome.checked)
			document.frmEditShelf.showinall.checked = false;
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
	var shelfgroup_id										= '<?php echo $id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
		case 'product_shelf': // Case of Products in the shelf
			retdivid   	= 'productshelf_div';
			fpurpose	= 'list_shelfgroup';
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
	Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelfgroup_id='+shelfgroup_id);		
}		
function normal_assign_ShelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,shelf_group_id)
{
		window.location 			= 'home.php?request=shelfgroup&fpurpose=ShelfGroupAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelfgroup_id='+shelf_group_id;
}
function normal_assign_displayProdShelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,shelf_group_id)
{
		window.location 			= 'home.php?request=shelfgroup&fpurpose=displayProdShelfGroupAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelfgroup_id='+shelf_group_id;
}
function normal_assign_displayCategoryShelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,shelf_group_id)
{
		window.location 			= 'home.php?request=shelfgroup&fpurpose=displayCategoryShelfGroupAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelfgroup_id='+shelf_group_id;
}
function normal_assign_displayStaticShelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,shelf_group_id)
{
		window.location 			= 'home.php?request=shelfgroup&fpurpose=displayStaticShelfGroupAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelfgroup_id='+shelf_group_id;
}
function normal_assign_displayShopShelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,shelf_group_id)
{
		window.location 			= 'home.php?request=shelfgroup&fpurpose=displayShopShelfGroupAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shelfgroup_id='+shelf_group_id;
}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var shelfgroup_id			= '<?php echo $id?>';
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
		case 'shelf_group': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Shelves to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Shelf(s)?';
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
			Handlewith_Ajax('services/shelfgroup.php','fpurpose='+fpurpose+'&shelfgroup_id='+shelfgroup_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}

function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var shelfgroup_id			= '<?php echo $id?>';
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
		case 'shelf_group': // Case of Products in the shelf
			atleastmsg 	= 'Please select the shelf(s) to be deleted from the menu';
			confirmmsg 	= 'Are you sure you want to delete the selected shelf(s) from the menu?';
		//	retdivid   	= 'productshelf_div';
		//	moredivid	= 'product_shelfunassign_div';
			fpurpose	= 'ShelfGroupUnAssign';
		break;
		case 'display_product_shelfgroup':// Case of Display Products in the shelf
			atleastmsg 	= 'Please select the Product(s) to be deleted from the shelf menu';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the shelf menu?';
		//	retdivid   	= 'display_productshelf_div';
		//	moredivid	= 'display_product_shelfunassign_div';
			fpurpose	= 'displayProdShelfGroupUnAssign';
		break;
		case 'display_category_shelfgroup':// Case of Display Categories in the shelf
			atleastmsg 	= 'Please select the Category(s) to be deleted from the shelf menu';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the shelf menu?';
		//	retdivid   	= 'display_categoryshelf_div';
		//	moredivid	= 'display_category_shelfunassign_div';
			fpurpose	= 'displayCategoryShelfGroupUnAssign';
		break;
		case 'display_static_shelfgroup':// Case of Display Static Pages in the shelf
			atleastmsg 	= 'Please select the Static Page(s) to be deleted from the shelf menu';
			confirmmsg 	= 'Are you sure you want to delete the selected Static Page(s) from the shelf menu?';
		//	retdivid   	= 'display_staticshelf_div';
		//	moredivid	= 'display_static_shelfunassign_div';
			fpurpose	= 'displayStaticShelfGroupUnAssign';
		break;
		case 'display_shop_shelfgroup':// Case of Display Static Pages in the shelf
			atleastmsg 	= 'Please select the Shop(s) to be deleted from the shelf menu';
			confirmmsg 	= 'Are you sure you want to delete the selected Shop(s) from the shelf menu?';
		//	retdivid   	= 'display_staticshelf_div';
		//	moredivid	= 'display_static_shelfunassign_div';
			fpurpose	= 'displayShopShelfGroupUnAssign';
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
			Handlewith_Ajax('services/shelfgroup.php','fpurpose='+fpurpose+'&shelfgroup_id='+shelfgroup_id+'&del_ids='+del_ids+'&'+qrystr);
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
	/*tab_arr 								= new Array('main_tab_td','shelfs_tab_td','shelfprod_tab_td','shelfcategories_tab_td','static_tab_td','shop_tab_td'); */ 
	tab_arr 								= new Array('main_tab_td','shelfs_tab_td','shelfcategories_tab_td','shop_tab_td');
	var atleastone 							= 0;
	var shelfgroup_id							= '<?php echo $id?>';
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
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&shelfgroup_id='+shelfgroup_id+'&curtab='+curtab+'&showinall='+showinall+'&advert_title='+advert_title;
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
		      
		case 'displayshelfs_group': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_shelfgroup';
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
		
		
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
//	document.getElementById('retdiv_more').value = id;															
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/shelfgroup.php','fpurpose='+fpurpose+'&shelfgroup_id='+shelfgroup_id+'&'+qrystr);	
}

</script>
<form name='frmEditShelf' action='home.php?request=shelfgroup' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfgroup&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shelf menus</a><span> Edit Shelf menu for '<? echo $row_shelf['name'];?>'</span></div></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
		  		$help_msg = get_help_messages('EDIT_SHELF_MENU_SHOW_MESS1');
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','shelfmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('shelfs_tab_td','displayshelfs_group')" class="<?php if($curtab=='shelfs_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfs_tab_td"><span>Shelfs in this Menu</span></td>
						<!--<td width="19%" align="left" onClick="handle_tabs('shelfprod_tab_td','displayshelfproduct_group')" class="<?php /*if($curtab=='shelfprod_tab_td') echo "toptab_sel"; else echo "toptab"*/?>" id="shelfprod_tab_td"> Show Shelf Menu in these Products</td>-->
						<td  align="left" onClick="handle_tabs('shelfcategories_tab_td','displayshelfcateg_group')" class="<?php if($curtab=='shelfcategories_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfcategories_tab_td"><span>Show Shelf Menu in these Categories</span></td>
						<!--<td width="21%" align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php /*if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"*/?>" id="static_tab_td">Show Shelf Menu in these Static Pages</td>-->
						<td align="left" onClick="handle_tabs('shop_tab_td','displayshop_group')" class="<?php if($curtab=='shop_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shop_tab_td"><span>Show Shelf Menu in these Shops</span></td>
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
				show_shelf_maininfo($id,$alert);
			}
			elseif ($curtab=='shelfs_tab_td')
			{
				show_shelf_group_list($id,$alert);
			}
			elseif ($curtab=='shelfcategories_tab_td')
			{
				show_display_category_shelfgroup_list($id,$alert);
			}
			elseif ($curtab=='shelfprod_tab_td')
			{
				show_display_product_shelfgroup_list($id,$alert);	
			}
			
			elseif ($curtab=='static_tab_td')
			{
				show_display_static_shelfgroup_list($id,$alert);
			}
			elseif ($curtab=='shop_tab_td')
			{
				show_display_shop_shelfgroup_list($id,$alert);
			}
			 
			?>		
		  </div>		</td>
        </tr>
		
        <tr>
          
          <td colspan="2" align="center" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="shelfgroup_id" id="shelfgroup_id" value="<?=$id?>" />
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

