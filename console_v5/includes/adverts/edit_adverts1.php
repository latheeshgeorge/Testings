<?php
	/*#################################################################
	# Script Name 	: edit_adverts.php
	# Description 	: Page for editing Adverts
	# Coded by 		: ANU
	# Created on	: 20-July-2007
	# Modified by	: Sny
	# Modified On	: 20-Dec-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Adverts';
$help_msg = get_help_messages('EDIT_ADVERT_MESS1');
/*
$advert_id=($_REQUEST['advert_id']?$_REQUEST['advert_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT advert_title,advert_hide,advert_showinall,advert_showinhome,advert_order,advert_source,advert_link,advert_type,advert_activateperiodchange,advert_displaystartdate,advert_displayenddate,advert_target FROM adverts WHERE sites_site_id=$ecom_siteid AND advert_id=".$advert_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);
// Find the feature_id for mod_adverts module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_adverts'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
// Find the display settings details for this advert
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$advert_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
	*/
?>	
<script language="javascript" type="text/javascript">
function activeperiod(check,bid){
 if(document.frmEditAdverts.advert_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmEditAdverts.advert_activateperiodchange.checked = false;
		}
		
		
}
function handletype_change(vals)
{	
	if (vals=='')
		vals = 'IMG';
	switch(vals)
	{
		case 'IMG':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
			document.getElementById('tr_target').style.display = '';
		break;
		case 'PATH':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = '';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
			document.getElementById('tr_target').style.display = '';
		break;
		case 'TXT':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = '';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
		break;
		case 'SWF':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
		break;
	};
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
			targetdiv 	= document.getElementById('retdiv_id').value;
			norecdiv 	= document.getElementById('retdiv_more').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			switch(targetdiv)
			{
				
				case 'category_div':
					if(document.getElementById('category_norec'))
					{
						if(document.getElementById('category_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'products_div':
					if(document.getElementById('products_norec'))
					{
						if(document.getElementById('products_norec').value==1)
						{
							disp = 'none';
						}	
						else
							disp = '';
					}
					else
						disp = '';	
				break;
				case 'assign_pages_div':
					if(document.getElementById('assign_pages_norec'))
					{
						if(document.getElementById('assign_pages_norec').value==1)
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
			
			if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
				norecobj.style.display = disp;
			}	
			hide_processing();
		}
		else
		{
		    show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
	
		case 'category': // Case of product messages
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('category_tr'))
					document.getElementById('category_tr').style.display = '';
				if(document.getElementById('categoryunassign_div'))
					document.getElementById('categoryunassign_div').style.display = '';	
				call_ajax_showlistall('category')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('category_tr'))
					document.getElementById('category_tr').style.display = 'none';
				if(document.getElementById('categoryunassign_div'))
					document.getElementById('categoryunassign_div').style.display = 'none';
			}	
		break;
		case 'products': // Case of product tabs
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = '';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = '';	
				call_ajax_showlistall('products');
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('products_tr'))
					document.getElementById('products_tr').style.display = 'none';
				if(document.getElementById('productsunassign_div'))
					document.getElementById('productsunassign_div').style.display = 'none';
			}	
		break;
		case 'assign_pages': // Case of Static Pages
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('assign_pages_tr'))
					document.getElementById('assign_pages_tr').style.display = '';
				if(document.getElementById('assign_pagesunassign_div'))
					document.getElementById('assign_pagesunassign_div').style.display = '';	
				call_ajax_showlistall('assign_pages')
			}	
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('assign_pages_tr'))
					document.getElementById('assign_pages_tr').style.display = 'none';
				if(document.getElementById('assign_pagesunassign_div'))
					document.getElementById('assign_pagesunassign_div').style.display = 'none';
			}	
		break;
	};
}
function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var advert_id										= '<?php echo $advert_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	switch(mod)
	{
	
		case 'category': // Case of product messages
			retdivid   	= 'category_div';
			fpurpose	= 'list_categoriesInAdverts_ajax';
			moredivid	= 'categoryunassign_div';
		break;
		case 'products': // Case of product assigned to the Advert
			retdivid   	= 'products_div';
			fpurpose	= 'list_products_ajax';
			moredivid	= 'productsunassign_div';
		break;
		case 'assign_pages': // Case of product link
			retdivid   	= 'assign_pages_div';
			fpurpose	= 'list_assign_pages_ajax';
			moredivid	= 'assign_pagesunassign_div';
		break;
	}
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&cur_advertid='+advert_id);
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var advert_id			= '<?php echo $advert_id?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditAdverts.elements.length;i++)
	{
	
	if (document.frmEditAdverts.elements[i].type =='checkbox' && document.frmEditAdverts.elements[i].name==checkboxname)
		{

			if (document.frmEditAdverts.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditAdverts.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product catgeories to be displayed
			atleastmsg 	= 'Please select the categories to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected categories?';
			retdivid   	= 'category_div';
			moredivid	= 'categoryunassign_div';
			fpurpose	= 'changestat_category_ajax';
			var chstat	= document.getElementById('categories_chstatus').value;
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Product(s) ?';
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'changestat_product_ajax';
			var chstat	= document.getElementById('product_chstatus').value;
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) Assigned to Advert to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Static Page(s)?';
			retdivid   	= 'assign_pages_div';
			moredivid	= 'assign_pagesunassign_div';
			fpurpose	= 'changestat_assign_pages_ajax';
			var chstat	= document.getElementById('assign_pages_chstatus').value;
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
		Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_advert_id='+advert_id+'&ch_ids='+ch_ids);
		}	
	}	
}	


function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var advert_id			= '<?php echo $advert_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditAdverts.elements.length;i++)
	{
		if (document.frmEditAdverts.elements[i].type =='checkbox' && document.frmEditAdverts.elements[i].name==checkboxname)
		{

			if (document.frmEditAdverts.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditAdverts.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product messages
			atleastmsg 	= 'Please select the Product Categories to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Categories?'
			retdivid   	= 'category_div';
			moredivid	= 'categoryunassign_div';
			fpurpose	= 'delete_category_ajax';
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			retdivid   	= 'products_div';
			moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Static Page(s)?'
			retdivid   	= 'assign_pages_div';
			moredivid	= 'assign_pagesunassign_div';
			fpurpose	= 'delete_assign_pages';
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
			
			Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&cur_advert_id='+advert_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_showclick(mod)
{ 
	if (mod=='advert_showinall')
	{
		if (document.frmEditAdverts.advert_showinall.checked)
			document.frmEditAdverts.advert_showinhome.checked = false;
	}
	else
	{
		if (document.frmEditAdverts.advert_showinhome.checked)
			document.frmEditAdverts.advert_showinall.checked = false;
	}		
}

function valform(frm)
{
	fieldRequired = Array('advert_title','display_id[]');
	fieldDescription = Array('Advert Title','Display Location');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;
	if(document.frmEditAdverts.cbo_type.value=='IMG')
	{
			if(document.frmEditAdverts.img_source.value==0 && document.frmEditAdverts.path_source.value==1){
			fieldRequired[reqcnt] 	 = 'file_advert';
			fieldDescription[reqcnt] = 'Image';
			reqcnt++;
			}
	}	
	if(document.frmEditAdverts.cbo_type.value=='PATH')
	{
			fieldRequired[reqcnt] 	 = 'txt_imgloc';
			fieldDescription[reqcnt] = 'Path for Image';
			reqcnt++;
			if(document.frmEditAdverts.img_source.value==1 && document.frmEditAdverts.path_source.value==0){
			fieldRequired[reqcnt] 	 = 'txt_imgloc';
			fieldDescription[reqcnt] = 'Path for Image';
			reqcnt++;
			}
	}	
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.advert_activateperiodchange.checked  ==true){
			val_dates = compareDates(frm.advert_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.advert_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}else{
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}

</script>

<script language="javascript">
	function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','category_tab_td','products_tab_td','static_tab_td');  
	var atleastone 							= 0;
	var group_id							= '<?php echo $edit_id?>';
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
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr								= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'catgroupmain_info':
			fpurpose ='list_adverts_maininfo';
		break;
		case 'category': // Case of Categories in the group
			
			//retdivid   	= 'master_div';
			fpurpose	= 'list_categorygroup';
			//moredivid	= 'category_groupunassign_div';
			
		break;
		case 'displayproduct_group': // Case of Display Products in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_displayproductgroup';
			//moredivid	= 'displayproduct_groupunassign_div';
		break;
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_displaystaticgroup';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/adverts.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
	
}
</script>
<form name='frmEditAdverts' action='home.php?request=adverts' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Adverts</a> &gt;&gt; Edit Adverts</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
						<td width="10%" align="left" onClick="handle_tabs('main_tab_td','catgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td">Main Info</td>
						<td width="15%" align="left" onClick="handle_tabs('category_tab_td','category')" class="<?php if($curtab=='category_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="category_tab_td">Categories in this Menu</td>
						<td width="24%" align="left" onClick="handle_tabs('products_tab_td','displayproduct_group')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td">Products in this Menu</td>
						<td width="23%" align="left" onClick="handle_tabs('static_tab_td','displaystatic_group')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td">Static pages in this Menu</td>
						<td width="4%" align="left">&nbsp;</td> 
				</tr> 
		</table>
			</td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" >Advert Title  <span class="redtext">*</span> </td>
          <td width="39%" align="left" valign="middle" class="tdcolorgray"><input name="advert_title" type="text" id="advert_title" value="<?=$row['advert_title']?>" /></td>
          <td align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="advert_showinall"  value="1" <? if($row['advert_showinall']==1) echo "checked";?>   />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_SHOWINALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<?php /*?> <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray" >Show in home page </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input  type="checkbox" name="advert_showinhome" value="1" <? if($row['advert_showinhome']==1) echo "checked"?>  onclick="handle_showclick('showinhome')"  />	</td>
    </tr><?php */?>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php
		  	$disp_array		= array();
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
			// Get the list of position allowable for category groups for the current theme
			$sql_themes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$pos_arr	= explode(",",$row_themes['advert_positions']);
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
						$curid 	= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
						if(count($ext_val))
						{
							if(!in_array($curid,$ext_val))
							{
								$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
								$disp_array["0_".$curid] = $curname;
							}
						}
						else
						{
							$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
							$disp_array["0_".$curid] = $curname;
						}	
					}	
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$disp_ext_arr,'','',5);
		  ?>&nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="advert_hide" value="1" <? if($row['advert_hide']==1) echo "checked";?> />
		     Yes
		     <input type="radio" name="advert_hide"  value="0" <? if($row['advert_hide']==0) echo "checked";?> />
		     No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr  >
		   <td align="left" valign="top" class="tdcolorgray" >Advert Type</td>
		   <td align="left" valign="top" class="tdcolorgray"><?php
					  	$type_arr = array('IMG'=>'Image Upload','PATH'=>'Image URL','TXT'=>'Text/HTML','SWF'=>'Flash');
						echo generateselectbox('cbo_type',$type_arr,$row['advert_type'],'','handletype_change(this.value)');
					  ?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td colspan="2" align="left" valign="top" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr id="tr_img">
		   <td align="left" valign="middle" class="tdcolorgray" >Select Image <span class="redtext">*</span></td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="file_advert" type="file" id="file_advert" />&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	     </tr>
		 <tr id="tr_loc">
		   <td align="left" valign="top" class="tdcolorgray" >Specify Image Location <span class="redtext">*</span></td>
		   <td colspan="3" align="left" valign="top" class="tdcolorgray"><input name="txt_imgloc" type="text" id="txt_imgloc" size="50" value="<?php if($row['advert_type']=='PATH') echo $row['advert_source'];?>" />&nbsp;
		   (e.g. Address = "http://www.bshop4.co.uk/console/images/logo.gif") </td>
    </tr>
		 <tr id="tr_text">
          <td width="17%" align="left" valign="top" class="tdcolorgray" >Specify Advert Text/HTML</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  <?php
						
						$editor 			= new FCKeditor('txt_text') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= ($row['advert_type']=='TXT')?trim($row['advert_source']):'';
						$editor->Create() ;
				       
		?>		  </td>
        </tr>
		 <tr id="tr_link">
		   <td align="left" valign="middle" class="tdcolorgray" >Link for advert </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="txt_link" type="text" id="txt_link" size="50" value ="<?php echo $row['advert_link'];?>" />&nbsp;
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		   &nbsp;(e.g. Address = "http://www.bshop4.co.uk)</td>
    </tr>
	 <tr id="tr_target">
		   <td align="left" valign="middle" class="tdcolorgray" >Advert Link Open in </td>
	   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
	   <?php $advert_target_arr = array('_blank' => 'New Window','_self' => 'Same Window');
	   echo generateselectbox('advert_target',$advert_target_arr,$row['advert_target']);
	   ?>&nbsp;
	     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_LINK_TARGET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr>
		   <td colspan="4" align="left" valign="middle" class="tdcolorgray" ></td>
    </tr>
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="3"><b>Active Period</b></td>
		   </tr>
		   <? $id=10;
		   if($row['advert_activateperiodchange']==1)
		   			 {
					  $active_start_arr 		= explode(" ",$row['advert_displaystartdate']);
					  $active_end_arr 			= explode(" ",$row['advert_displayenddate']);
					  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
								$active_start_hr			= $active_starttime_arr[0];
								$active_start_mn			= $active_starttime_arr[1];
								$active_start_ss			= $active_starttime_arr[2];	
								$active_endttime_arr 		= explode(":",$active_end_arr[1]);
								$active_end_hr				= $active_endttime_arr[0];
								$active_end_mn				= $active_endttime_arr[1];
								$active_end_ss				= $active_endttime_arr[2];	
					  $display='';
					  $exp_advert_displaystartdate=explode("-",$active_start_arr[0]);
					  $val_advert_displaystartdate=$exp_advert_displaystartdate[2]."-".$exp_advert_displaystartdate[1]."-".$exp_advert_displaystartdate[0];
					  $exp_advert_displayenddate=explode("-",$active_end_arr[0]);
					  $val_advert_displayenddate=$exp_advert_displayenddate[2]."-".$exp_advert_displayenddate[1]."-".$exp_advert_displayenddate[0];
					}
					else
					{ 
					 //echo "none";
					  $display='none';
					}
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
					
					
					?>
		 
		    <tr>
		    <td width="20%" align="right" valign="middle" class="tdcolorgray" lign="left">
			    Change Active Period			</td>
			 <td width="80" colspan="2" valign="middle" class="tdcolorgray" lign="left">
			    <input type="checkbox" name="advert_activateperiodchange"  onclick="activeperiod(this.checked,<? echo $id?>)" value="1" <? if($row['advert_activateperiodchange']==1) echo "checked"?>/>			
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="4" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td width="25%" align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		     <td  align="right" valign="middle">&nbsp;</td>
		     <td align="left" valign="middle" >&nbsp;</td>
		     <td width="7%" class="tdcolorgray">Hrs</td>
		     <td width="6%" class="tdcolorgray">Min</td>
		     <td width="7%" class="tdcolorgray">Sec</td>
		     <td width="42%" class="tdcolorgray">&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="right" valign="middle" width="9%"><input class="input" type="text" name="advert_displaystartdate" size="8" value="<? echo $val_advert_displaystartdate ?>"  />		  </td>
			<td width="4%" align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditAdverts.advert_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td class="tdcolorgray"><select name="advert_starttime_hr" id="advert_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_starttime_mn" id="advert_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_starttime_ss" id="advert_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray">&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" >
			    End Date			</td>
			<td  align="right" valign="middle"  width="9%"><input class="input" type="text" name="advert_displayenddate" size="8" value="<? echo $val_advert_displayenddate ?>"  />		  </td>
			<td width="4%" align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmEditAdverts.advert_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td class="tdcolorgray"><select name="advert_endtime_hr" id="advert_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_endtime_mn" id="advert_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray"><select name="advert_endtime_ss" id="advert_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select></td>
		   <td class="tdcolorgray">&nbsp;</td>
		   </tr>
		   </table>		   </td>
		   </tr>
		   
		   </table>		   </td>
		   </tr>
    </tr>
		 <tr>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td width="39%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td width="14%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	      <td width="30%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="center" valign="middle" class="tdcolorgray" ><?php
				if($row['advert_type'] != 'TXT')
				{
					//echo $row['advert_source'] ."<br>";
					if($row['advert_type'] == 'IMG')
					{
					    if($row['advert_source']){
						?>
						 <input type="hidden" name="img_source" id="img_source" value="1" />
						 <input type="hidden" name="path_source" id="path_source" value="0" />
						<?
						}	
						$img = "http://$ecom_hostname/images/$ecom_hostname/adverts/".$row['advert_source'];
					}	
					elseif ($row['advert_type'] == 'PATH')
					{
						if($row['advert_source']){
						?>
						 <input type="hidden" name="img_source" id="img_source" value="0" />
						 <input type="hidden" name="path_source" id="path_source" value="1" />
						<?
						}	
						$img = $row['advert_source'];
					}	
					if ($row['advert_link'])
					{
					?>
            <a href="<?php echo $img?>" title="Click to enlarge" target="_blank">
            <?php
					}if ($row['advert_type'] == 'IMG'){
			?>
            <img src="<?php echo $img?>" alt="<?php echo $row['advert_title']?>" border="0" />
            <?php }else if ($row['advert_type'] == 'SWF')	{?>
		  <div id="flash_tag"><embed  src='http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/adverts/<?php echo $row['advert_source']?>' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' ></div>
<?
			}	
					if ($row['advert_link'])
					{
					?>
            </a>
            <?php
					}
				}
			?></td>
        </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">
		   
  <input type="hidden" name="advert_id" id="advert_id" value="<?=$advert_id?>" />
  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_advert" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		  <input name="Submit" type="submit" class="red" value="Save" /></td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
			
		<!--		for displaying the Categories assigned to the Advert STARTS -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" > 
		  <?php
		 // Check whether categories are Assiged to this Adverts
			$sql_categories_in_adverts = "SELECT id FROM advert_display_category
						 WHERE adverts_advert_id=$advert_id";
			$ret_categories_in_adverts = $db->query($sql_categories_in_adverts);
			
		 
		 ?>
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'category')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">  Categories for which  this Advert will be displayed</td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditAdverts.fpurpose.value='list_assign_categories';document.frmEditAdverts.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_categories_in_adverts))
				{
				?>
					<div id="categoryunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$categories_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('categories_chstatus',$categories_status,0);*/
					?>
					<!--<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_CAT_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		
		<tr id="category_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="category_div" style="text-align:center">			</div>			</td>
		</tr>
<!--		for displaying the Categories assigned to the Advert ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
<!--		for displaying the products assigned to the Advert STARTS  -->	
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >  
		  <?php
		 // Check whether Products are added to this Advert
			$sql_product_in_adverts = "SELECT products_product_id FROM advert_display_product
						 WHERE adverts_advert_id=$advert_id";
			$ret_product_in_adverts = $db->query($sql_product_in_adverts);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'products')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd"> Products for which  this Advert will be displayed</td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditAdverts.fpurpose.value='list_assign_products';document.frmEditAdverts.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_adverts))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to -->
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
						echo generateselectbox('product_chstatus',$products_status,0);
*/					?>
					<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_PROD_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="products_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="products_div" style="text-align:center">			</div>			</td>
		</tr>
		<!--		for displaying the products assigned to the Advert ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
		<!--		for displaying the Static Pages assigned to the Advert STARTS  -->	
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >  
		  <?php
		 // Check whether Pages are added to this Advert
			$sql_assigned_pages = "SELECT static_pages_page_id FROM advert_display_static
						 WHERE adverts_advert_id=$advert_id";
			$ret_assigned_pages = $db->query($sql_assigned_pages);		
		 
		 ?>
		 <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd"><img id="cat_imgtag" src="images/plus.gif" border="0" onclick="handle_expansionall(this,'assign_pages')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd">  Static Pages for which  this Advert will be displayed</td>
            </tr>
          </table></td>
        </tr>
		<tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditAdverts.fpurpose.value='list_assign_pages';document.frmEditAdverts.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_STATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_assigned_pages))
				{
				?>
					<div id="assign_pagesunassign_div" class="unassign_div" style="display:none">
					<!--Change Hidden Status to--> 
					<?php
						/*$products_status = array(0=>'No',1=>'Yes');
	
						echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
					?>
					<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_ASS_STATPAGE_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
-->								
					&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_UNASS_STATPAGE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
		<tr id="assign_pages_tr">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="assign_pages_div" style="text-align:center">			</div>			</td>
		</tr>
		<!--		for displaying the Static pages assigned to the Advert ENDS  -->	
		<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr>
      </table>
</form>	  

<script type="text/javascript">
	handletype_change('<?php echo $row['advert_type']?>');
</script>