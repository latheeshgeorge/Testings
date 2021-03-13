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

$sql_shelf="SELECT shelf_name,shelf_description,shelf_order,shelf_hide,shelf_displaytype,shelf_showinall,shelf_showimage,shelf_showtitle,shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,shelf_displaystartdate,shelf_displayenddate,shelf_showinhome FROM product_shelf  WHERE shelf_id=".$shelf_id;
$res_shelf= $db->query($sql_shelf);
$row_shelf = $db->fetch_array($res_shelf);
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
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('shelf_name','display_id','shelf_order','shelf_currentstyle');
	fieldDescription = Array('Shelf Name','Shelf Position','Shelf Order','Listing Style');
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
		if(frm.shelf_showimage.checked==false && frm.shelf_showtitle.checked==false && frm.shelf_showdescription.checked==false && frm.shelf_showprice.checked==false) 
		{
			    alert('Please Check any of Fields Items to Display in Shelf ');	   
				return false;    
		}
		else{
		show_processing();
		return true;
		}
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
	switch(mod)
	{
		case 'product_shelf': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Products to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Product(s)?';
			retdivid   	= 'productshelf_div';
			moredivid	= 'product_shelfunassign_div';
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
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */
		 	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+retdivid+"')");
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
			retdivid   	= 'productshelf_div';
			moredivid	= 'product_shelfunassign_div';
			fpurpose	= 'prodShelfUnAssign';
		break;
		case 'display_product_shelf':// Case of Display Products in the shelf
			atleastmsg 	= 'Please select the Product(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the shelf?';
			retdivid   	= 'display_productshelf_div';
			moredivid	= 'display_product_shelfunassign_div';
			fpurpose	= 'displayProdShelfUnAssign';
		break;
		case 'display_category_shelf':// Case of Display Categories in the shelf
			atleastmsg 	= 'Please select the Category(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the shelf?';
			retdivid   	= 'display_categoryshelf_div';
			moredivid	= 'display_category_shelfunassign_div';
			fpurpose	= 'displayCategoryShelfUnAssign';
		break;
		case 'display_static_shelf':// Case of Display Static Pages in the shelf
			atleastmsg 	= 'Please select the Static Page(s) to be deleted from the shelf';
			confirmmsg 	= 'Are you sure you want to delete the selected Static Page(s) from the shelf?';
			retdivid   	= 'display_staticshelf_div';
			moredivid	= 'display_static_shelfunassign_div';
			fpurpose	= 'displayStaticShelfUnAssign';
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
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			//alert(targetdiv);
			switch(targetdiv)
			{
				case 'productshelf_div':
					if(document.getElementById('productshelf_norec'))
					{
						if(document.getElementById('productshelf_norec').value==1)
						{ 
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'display_productshelf_div':
					if(document.getElementById('display_productshelf_norec'))
					{
						if(document.getElementById('display_productshelf_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'display_categoryshelf_div':
					if(document.getElementById('display_categoryshelf_norec'))
					{
						if(document.getElementById('display_categoryshelf_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'display_staticshelf_div':
					if(document.getElementById('display_staticshelf_norec'))
					{
						if(document.getElementById('display_staticshelf_norec').value==1)
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
			//hide_processing();
		if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}

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
	tab_arr 								= new Array('main_tab_td','products_tab_td','shelfprod_tab_td','shelfcategories_tab_td','static_tab_td');  
	var atleastone 							= 0;
	var shelf_id							= '<?php echo $shelf_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var cname								= '<?php echo $_REQUEST['catgroupname']?>';
	var sortby								= '<?php echo $sort_by?>';
	var sortorder							= '<?php echo $sort_order?>';
	var recs								= '<?php echo $records_per_page?>';
	var start								= '<?php echo $start?>';
	var pg									= '<?php echo $pg?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $advert_showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;
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
		case 'shelfmain_info':
			fpurpose ='list_shelf_maininfo';
		break;
		case 'displayproducts_group': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_products_ajax';
			//moredivid	= 'category_groupunassign_div';
		break;  
		case 'displayshelfproduct_group': // Case of Display Products in the group 
			//retdivid   	= 'master_div';
			fpurpose	= 'list_shelfproducts_ajax';
			//moredivid	= 'displayproduct_groupunassign_div';
		break;
		case 'displayshelfcateg_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_shelfcategs_ajax';
			//moredivid	= 'displaycategory_groupunassign_div';
		break; 
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_assign_pages_ajax';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	document.getElementById('retdiv_more').value = id;															
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/shelf.php','fpurpose='+fpurpose+'&shelf_id='+shelf_id+'&'+qrystr);	
}
</script>
<form name='frmEditShelf' action='home.php?request=shelfs' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><a href="home.php?request=shelfs&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shelves</a>  &gt;&gt; Edit Shelf for '<? echo $row_shelf['shelf_name'];?>'</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?>  
		<tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>  
						<td width="9%" align="left" onClick="handle_tabs('main_tab_td','shelfmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td">Main Info</td>
						<td width="16%" align="left" onClick="handle_tabs('products_tab_td','displayproducts_group')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td">  Products in the Shelf</td>
						<td width="21%" align="left" onClick="handle_tabs('shelfprod_tab_td','displayshelfproduct_group')" class="<?php if($curtab=='shelfprod_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfprod_tab_td"> Show Shelf in these Products</td>
						<td width="20%" align="left" onClick="handle_tabs('shelfcategories_tab_td','displayshelfcateg_group')" class="<?php if($curtab=='shelfcategories_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfcategories_tab_td">Show Shelf in these Categories</td>
						<td width="21%" align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="static_tab_td">Show Shelf in these Static Pages</td>
						<td width="13%" align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="static_tab_td">&nbsp;</td>
				</tr> 
		</table>
		  </td>
        </tr>
		<tr>
		<td width="51%" valign="top" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Name <span class="redtext">*</span> </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_name" value="<?=stripslashes($row_shelf['shelf_name'])?>"  />
		  </td>
        </tr>
		 <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Position <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <?php
		  	$disp_array		= array();
			$ext_val=array();
			if ($db->num_rows($ret_disp))
			{
				while ($row_disp = $db->fetch_array($ret_disp))
				{	
					$layoutid				= $row_disp['themes_layouts_layout_id'];
					$layoutcode				= $row_disp['layout_code'];
					$layoutname				= stripslashes($row_disp['layout_name']);
					$disp_id				= $row_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
					$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
					$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
				}
			}
			
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT shelf_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$pos_arr	= explode(",",$row_themes['shelf_positions']);
			}
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{
			
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					for($i=0;$i<count($pos_arr);$i++)
					{
					$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
						if(!in_array($curid,$ext_val))
						{
							$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
							$disp_array["0_".$curid] = $curname;
						}	
					}	
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$disp_ext_arr,'','',5);
		  ?>
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_DISPLOC')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <!--<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Order <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_order" size="3" value="<?=stripslashes($row_shelf['shelf_order'])?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>-->
		 <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Display Type </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shelf_displaytype">
		  <?
		 $sql_style	= "SELECT shelf_displaytypes FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style	= $db->fetch_array($ret_style);
		 $arr_style	= explode(',',$row_style['shelf_displaytypes']);
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 <option value="<?=$val_arr[0]?>" <?php echo ($row_shelf['shelf_displaytype']==$val_arr[0])?'selected':''?>><?=$val_arr[1]?></option>
		 <?
	
		 }
		 ?>
		 </select>
		 &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_DISPTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Listing Style</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shelf_currentstyle">
		  <option value="">--select--</option>
		  <?
		 $sql_style="SELECT shelf_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style=$db->fetch_array($ret_style);
		 $arr_style=explode(',',$row_style['shelf_listingstyles']);
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 	<option value="<?=$val_arr[0]?>" <?php echo ($row_shelf['shelf_currentstyle']==$val_arr[0])?'selected':''?>><?=$val_arr[1]?></option>
		 <?
		 }
		 ?>
		  </select>
		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_LISTSTYLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shelf_hide" value="1" <? if($row_shelf['shelf_hide']==1) echo "checked"?> />&nbsp;&nbsp;Yes&nbsp;&nbsp;<input type="radio" name="shelf_hide" value="0" <? if($row_shelf['shelf_hide']==0) echo "checked"?> />&nbsp;&nbsp;No
		  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</td>
		<td width="49%" valign="top" class="tdcolorgray">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php /*?><tr>
		<td width="100%" colspan="2" align="left"><b>Where to show?</b>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr><?php */?>
		<tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Show in all &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showinall" value="1"  <? if($row_shelf['shelf_showinall']==1) echo "checked"?>  />
		  </td>
        </tr>
		
         
		<tr>
		<td width="100%" colspan="2" align="left"><b>Fields to be displayed in shelf</b>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_FIELD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 <tr>
          <td width="40%" align="left" valign="middle" class="tdcolorgray" >Show Image </td>
          <td width="60%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showimage" value="1" <? if($row_shelf['shelf_showimage']==1) echo "checked"?>  />
		  </td>
        </tr>
		
		<tr>
          <td width="40%" align="left" valign="middle" class="tdcolorgray" >Show Title </td>
          <td width="60%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showtitle" value="1" <? if($row_shelf['shelf_showtitle']==1) echo "checked"?>  />
		  </td>
        </tr>
		<tr>
          <td width="40%" align="left" valign="middle" class="tdcolorgray" >Show Description </td>
          <td width="60%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showdescription" value="1" <? if($row_shelf['shelf_showdescription']==1) echo "checked"?>  />
		  </td>
        </tr>
		<tr>
          <td width="40%" align="left" valign="middle" class="tdcolorgray" >Show Price </td>
          <td width="60%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showprice" value="1" <? if($row_shelf['shelf_showprice']==1) echo "checked"?>  />
		  </td>
        </tr>
		 <tr>
			<td width="100%" colspan="2" align="left"><b>Active Period</b>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		   <tr>
          <td width="50%" align="left" valign="middle" class="tdcolorgray" >Change Active Period </td>
          <td width="50%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" id="shelf_activateperiodchange" name="shelf_activateperiodchange" onclick="change_show_date_period()" value="1" <? if($row_shelf['shelf_activateperiodchange']==1) echo "checked"?>  />
		  </td>
        </tr>
		<? 
			if($row_shelf['shelf_activateperiodchange']==1)
			{
			
			  $active_start_arr 		= explode(" ",$row_shelf['shelf_displaystartdate']);
			  $active_end_arr 			= explode(" ",$row_shelf['shelf_displayenddate']);
			  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
			  $display='';
			  $exp_shelf_displaystartdate=explode("-",$active_start_arr[0]);
			  $val_shelf_displaystartdate=$exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
			  $exp_shelf_displayenddate=explode("-",$active_end_arr[0]);
			  $val_shelf_displayenddate  =$exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
			}
			else
			{
			  $display='none';
			}
			
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		?>
		<tr id="show_date_period" style="display:<?=$display?>;">
		 <td colspan="2" width="100%" align="left" valign="middle" class="tdcolorgray" >
		 <table width="100%" cellpadding="0" cellspacing="2" border="0">
		 <tr>
		   <td align="left" valign="middle"  >&nbsp;</td>
		   <td align="left" valign="middle" >&nbsp;</td>
		   <td width="10%" align="left" valign="middle" >&nbsp;</td>
		   <td width="15%" align="left" valign="middle" >Hrs</td>
		   <td width="14%" align="left" valign="middle" >Min</td>
		   <td width="13%" align="left" valign="middle" >Sec</td>
		   <td width="13%" align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="17%" align="left" valign="middle"  >Start Date</td>
          <td width="18%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displaystartdate" size="8" value="<?=$val_shelf_displaystartdate?>"  />		  </td>
		  <td align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditShelf.shelf_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_hr" id="shelf_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_mn" id="shelf_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_ss" id="shelf_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="17%" align="left" valign="middle"  >End Date</td>
          <td width="18%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displayenddate" size="8" value="<?=$val_shelf_displayenddate?>"  />		  </td>
		  <td align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditShelf.shelf_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_hr" id="shelf_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_mn" id="shelf_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_ss" id="shelf_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 </table>
		  </td>
          
		</tr>
		
       </table>
	   
	   </td>
	   </tr>
	   <tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >Description &nbsp;<a href="#" onmouseover ="ddrivetip('This description will be displayed only while viewing the shelf in middle area.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >
			<?php
				include_once("classes/fckeditor.php");
				$editor 			= new FCKeditor('shelf_description') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '550';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($row_shelf['shelf_description']);
				$editor->Create() ;
			?>          
		  </td>
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
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input name="Submit" type="submit" class="red" value="Update" /></td>
        </tr>
		<tr>
		<td colspan="2">&nbsp;
		
		</td>
		</tr>
		<tr >
          <td colspan="2" align="left" valign="bottom">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'product_shelf')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Products Assigned to this shelf</td>
            </tr>
          </table></td>
        </tr>
	   <?
		  // Get the list of products under current category group
		  $sql_products_in_shelf = "SELECT products_product_id FROM 
						product_shelf_product WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		  $ret_products_in_shelf = $db->query($sql_products_in_shelf);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_shelf))
			{
			?>
			<div id="product_shelfunassign_div" class="unassign_div" style="display:none">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_shelf','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('product_shelf','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
			</tr>
			
			 <tr  id="product_shelftr_details" style="display:none" >
          		<td colspan="2" align="left" valign="middle" class="tdcolorgray" >
				<div id="productshelf_div" style="text-align:center">
			    </div>
				</td>
			</tr>
			
	  
		<tr >
          <td colspan="2" align="left" valign="bottom">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'display_product_shelf')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Display this shelf when viewing the following Products&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_DISP_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		 <?
		  // Get the list of products under current category group
		  $sql_display_products_in_shelf = "SELECT products_product_id FROM 
						product_shelf_display_product WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		  $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_SHELVES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_product_shelfunassign_div" class="unassign_div" style="display:none">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_product_shelf','checkboxdisplayproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_PROD_SHELVES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		</div>	
			<?php
			}
			?>
		  </td>
			</tr>
		
		   <tr  id="display_product_shelftr_details" style="display:none" >
          		<td colspan="2" align="left" valign="middle" class="tdcolorgray" >
				<div id="display_productshelf_div" style="text-align:center">
			    </div>
				</td>
			</tr>
		   <tr >
          <td colspan="2" align="left" valign="bottom">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'display_category_shelf')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Display this shelf when viewing the following Categories&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_DISP_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		 <?
		  // Get the list of categories under current category group
		  $sql_display_products_in_shelf = "SELECT product_categories_category_id FROM 
						product_shelf_display_category WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		
		 $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayCategoryShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_category_shelfunassign_div" class="unassign_div" style="display:none">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_category_shelf','checkboxdisplaycategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
			</tr>
		  <tr  id="display_category_shelftr_details" style="display:none" >
          		<td colspan="2" align="left" valign="middle" class="tdcolorgray" >
				<div id="display_categoryshelf_div" style="text-align:center">
			    </div>
				</td>
			</tr>
		 	
		 <tr >
          <td colspan="2" align="left" valign="bottom">&nbsp;
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'display_static_shelf')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">Display this shelf when viewing the following Static Pages&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_DISP_STAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
			 
		  <?
		  // Get the list of static pages under current category group
		$sql_display_static_in_shelf = "SELECT static_pages_page_id FROM 
						product_shelf_display_static WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		
		 $ret_display_static_in_shelf = $db->query($sql_display_static_in_shelf);
		 ?>
		 <tr>
		  <td align="right" colspan="2" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayStaticShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_shelf))
			{
			?>
			<div id="display_static_shelfunassign_div" class="unassign_div" style="display:none">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_static_shelf','checkboxdisplaystatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
		 <tr   >
          		<td colspan="2" align="left" valign="middle" class="tdcolorgray" id="display_static_shelftr_details" style="display:none" >
				<div id="display_staticshelf_div" style="text-align:center">
			    </div>
				</td>
			</tr>

		</tr>
		<tr>
		<td colspan="2">&nbsp;
		
		</td>
		
		</tr>
      </table>
</form>	  

