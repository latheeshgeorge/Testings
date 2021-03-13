<?php
	/*#################################################################
	# Script Name 	: edit_product_category_groups.php
	# Description 		: Page for editing Product Category Groups
	# Coded by 		: Sny
	# Created on		: 16-June-2007
	# Modified by		: Sny
	# Modified On		: 16-Sep-2008
	#################################################################*/
	
	// Get the value of catgroup_showinall field for current category group
	$edit_id = $_REQUEST['checkbox'][0];
	$sql_catgroup = "SELECT catgroup_showinall 
									FROM 
										product_categorygroup 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND catgroup_id = ".$edit_id." 
									LIMIT 
										1";
	$ret_catgroup	 = $db->query($sql_catgroup);
	if ($db->num_rows($ret_catgroup))
	{
		$row_catgroup 		= $db->fetch_array($ret_catgroup);
		$showinallpages		= $row_catgroup['catgroup_showinall'];
	}
	else
		exit;
//#Define constants for this page
$page_type 	= 'Category Menus';
$help_msg 	= get_help_messages('EDIT_PROD_CAT_GROUP1');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
?>		
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('catgroup_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('catgroup_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(document.frmEditProductCategoryGroup.catgroup_listtype.value=='Dropdown' && document.frmEditProductCategoryGroup.catgroup_subcatlisttype.value=='List')
		{
			alert('Subcategory List Type does not support Group List Type');
			return false;
		}
		else
		{
			/* Check whether dispay location is selected*/
			obj = document.getElementById('display_id[]');
			if(obj.options.length==0)
			{
				alert('Display location is required');
				return false;
			}
			else
			{
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						atleastone = true;
					}
				}
				if (atleastone==false)
				{
					alert('Please select the display location');
					return false;
				}
			}
			show_processing();
			return true;
		}	
	} else {
		return false;
	}
}
function handle_tabs(id,mod)
{
	tab_arr 									= new Array('main_tab_td','category_tab_td','catmenu_tab_td','prodmenu_tab_td','statmenu_tab_td');
	var atleastone 						= 0;
	var group_id							= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var cname								='<?php echo $_REQUEST['catgroupname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;
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
			fpurpose ='list_categorygroup_maininfo';
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
		case 'displaycategory_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_displaycategorygroup';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		case 'displaystatic_group': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_displaystaticgroup';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/product_category_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
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

function normal_assignsel(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=prod_cat_group&fpurpose=assign_sel&pass_catgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_groupid='+groupid;
}
function normal_assign_prodGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=prod_cat_group&fpurpose=prodGroupAssign&pass_catgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
}
function normal_assign_CategoryGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=prod_cat_group&fpurpose=categoryGroupAssign&pass_catgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
}
function normal_assign_StaticGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=prod_cat_group&fpurpose=staticGroupAssign&pass_catgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
}
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var group_id			= '<?php echo $edit_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var cname								='<?php echo $_REQUEST['catgroupname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var qrystr									= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductCategoryGroup.elements.length;i++)
	{
		if (document.frmEditProductCategoryGroup.elements[i].type =='checkbox' && document.frmEditProductCategoryGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditProductCategoryGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditProductCategoryGroup.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'category': // Case of Categories Products in the group
			atleastmsg 	= 'Please select the Category(s) to be deleted from the group';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the group?';
			//retdivid   	= 'categorygroup_div';
			//moredivid	= 'category_groupunassign_div';
			fpurpose	= 'unassigncat';
		break;
		case 'displayproduct_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Product(s) to be deleted from the group';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s) from the group?';
			//retdivid   	= 'displayproductgroup_div';
			//moredivid	= 'displayproduct_groupunassign_div';
			fpurpose	= 'prodGroupUnAssign';
		break;
		case 'displaycategory_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Category(s) to be deleted from the group';
			confirmmsg 	= 'Are you sure you want to delete the selected Category(s) from the group?';
			//retdivid   	= 'displaycategorygroup_div';
			//moredivid	= 'displaycategory_groupunassign_div';
			fpurpose	= 'categoryGroupUnAssign';
		break;
		case 'displaystatic_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Page(s) to be deleted from the group';
			confirmmsg 	= 'Are you sure you want to delete the selected Page(s) from the group?';
		//	retdivid   	= 'displaystaticgroup_div';
			//moredivid	= 'displaystatic_groupunassign_div';
			fpurpose	= 'staticGroupUnAssign';
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
			//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_category_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var group_id			= '<?php echo $edit_id?>';
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var displaytype			= '';
	var islink				= '';
	var drop_width			= '';
	var disp_type			= '';
	var is_link				= '';
	var cname								='<?php echo $_REQUEST['catgroupname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var qrystr								= 'catgroupname='+cname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id;
	var dropcheckreq = false;
	if(document.getElementById('dropdown_support'))
		dropcheckreq = true;
	switch(mod)
	{
		case 'cat_order': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Categories to Save the details';
			confirmmsg 	= 'Are you sure you want to Save the details of selected Categories?';
			//retdivid   	= 'categorygroup_div';
			//moredivid	= 'category_groupunassign_div';
			fpurpose	= 'save_catorder';
			orderbox	= 'cat_order_';
			displaytype	= 'category_displaytype_';
			islink		= 'category_islink_';
			drop_wid	= 'cat_width_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditProductCategoryGroup.elements.length;i++)
	{
	if (document.frmEditProductCategoryGroup.elements[i].type =='checkbox' && document.frmEditProductCategoryGroup.elements[i].name== checkboxname)
		{
			if (document.frmEditProductCategoryGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditProductCategoryGroup.elements[i].value;
				
				obj = eval("document.getElementById('"+orderbox+document.frmEditProductCategoryGroup.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				 ch_order += obj.value; 
				
				obj_disp = eval("document.getElementById('"+displaytype+document.frmEditProductCategoryGroup.elements[i].value+"')");
				if (disp_type != '')
					disp_type += '~';
				 disp_type += obj_disp.value;
				 
				obj_islink = eval("document.getElementById('"+islink+document.frmEditProductCategoryGroup.elements[i].value+"')");
				if (is_link != '')
					is_link += '~';
				 is_link += obj_islink.value; 
				 
				if(dropcheckreq)
				{
					obj_dropwidth = eval("document.getElementById('"+drop_wid+document.frmEditProductCategoryGroup.elements[i].value+"')");
					if (drop_width != '')
						drop_width += '~';
				 	drop_width += obj_dropwidth.value; 
				} 
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
			//document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		 	//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_category_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&ch_order='+ch_order+'&disp_type='+disp_type+'&is_link='+is_link+'&drop_width='+drop_width+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function handle_dropstyle(obj)
{
	if(obj.checked==true)
	{
		if(document.getElementById('subcatdrop_tr'))
			document.getElementById('subcatdrop_tr').style.display = '';
		
	}	
	else
	{
		if(document.getElementById('subcatdrop_tr'))
			document.getElementById('subcatdrop_tr').style.display = 'none';
	}
}
</script>
<form name='frmEditProductCategoryGroup' action='home.php?request=prod_cat_group' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat_group&&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List  Category Menus</a><span> Edit Category Menu</span></div></td>
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
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td align="left" onClick="handle_tabs('main_tab_td','catgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td align="left" onClick="handle_tabs('category_tab_td','category')" class="<?php if($curtab=='category_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="category_tab_td"><span>Categories in this Menu</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','displaycategory_group')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Display Menu for Following Categories</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','displayproduct_group')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Display Menu for Following Products</span></td>
						<td  align="left" onClick="handle_tabs('statmenu_tab_td','displaystatic_group')" class="<?php if($curtab=='statmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="statmenu_tab_td"><span>Display Menu for Following Static Pages</span></td>
						<td width="90%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <?php /*?><tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr><?php */?>
		 <?php
		 	}
		 ?> 
		 <tr>
          <td colspan="4">
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_categorygroup_maininfo($edit_id,$alert);
			}
			elseif ($curtab=='category_tab_td')
			{
				show_category_group_list($edit_id,$alert);
			}
			elseif ($curtab=='catmenu_tab_td')
			{
				show_diplaycategory_group_list($edit_id,$alert);
			}
			elseif ($curtab=='prodmenu_tab_td')
			{
				show_diplayproduct_group_list($edit_id,$alert);
			}
			elseif ($curtab=='statmenu_tab_td')
			{
				show_diplaystatic_group_list($edit_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		
      </table>
	  		<input type="hidden" name="catgroupname" id="catgroupname" value="<?=$_REQUEST['catgroupname']?>" />
		  	<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  	<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  	<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  	<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  	<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
		  	<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $edit_id?>" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />

</form>	  

