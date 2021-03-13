<?php
	/*#################################################################
	# Script Name 	: edit_group.php
	# Description 	: Page for editing Staic Page Group
	# Coded by 		: SKR
	# Created on	: 26-June-2007
	# Modified by	: ANU
	# Modified On	: 04-july-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Sataic Page Menu';
$help_msg = get_help_messages('EDIT_STAT_PAGE_GROUP_MESS1');
$group_id=($_REQUEST['group_id']?$_REQUEST['group_id']:$_REQUEST['checkbox'][0]);
// Get the value of shopgroup_showinall field for current shop group
if($group_id)
{
 
	$sql_staticgroup = "SELECT group_showinall,group_name 
									FROM 
										static_pagegroup 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND group_id = ".$group_id." 
									LIMIT 
										1";
	$ret_staticgroup	 = $db->query($sql_staticgroup);
	if($db->num_rows($ret_staticgroup)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
	
	if ($db->num_rows($ret_staticgroup))
	{
		$row_staticgroup 		= $db->fetch_array($ret_staticgroup);
		$showinallpages		= $row_staticgroup['group_showinall'];
	}
	else
		exit;

}
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>	
<script language="javascript" type="text/javascript">
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

function handle_tabs(id,mod)
{ 
	
	tab_arr 									= new Array('main_tab_td','static_tab_td','catmenu_tab_td','prodmenu_tab_td','statmenu_tab_td');
	var atleastone 						= 0;
	var group_id							= '<?php echo $group_id?>';
	var shop_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var staticgroupname								='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'pass_group_name='+staticgroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'staticgroupmain_info':
			fpurpose ='list_staticgroup_maininfo';
		break;
		case 'static': // Case of Categories in the group
			fpurpose	= 'list_staticpages';
		break;
		case 'displayproduct_group': // Case of Display Products in the group
			fpurpose	= 'list_displayproduct_group';
		break;
		case 'displaycat_group': // Case of Display Categories in the group
			fpurpose	= 'list_displaycategory_group';
		break;
		case 'displaystatic_group': // Case of Display Categories in the group
			fpurpose	= 'list_displaystatic_group';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/static_group.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
	
	
	
}
function normal_assign_static(cname,sortby,sortorder,recs,start,pg,groupid)
{ 
		window.location 			= 'home.php?request=stat_group&fpurpose=assign_static_pages&pass_group_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&group_id='+groupid;
}
function normal_assign_CategoryGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=stat_group&fpurpose=list_assign_categories&pass_group_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&group_id='+groupid;
}
function normal_assign_staticprod(cname,sortby,sortorder,recs,start,pg,groupid)
{
		window.location 			= 'home.php?request=stat_group&fpurpose=list_assign_products&pass_group_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&group_id='+groupid;
}
function normal_assign_StaticGroupAssign(cname,sortby,sortorder,recs,start,pg,groupid)
{ 
		window.location 			= 'home.php?request=stat_group&fpurpose=list_assign_pages&pass_group_name='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&group_id='+groupid;
}
/*function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var group_id			= '<?php echo $group_id?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';

	/* check whether any checkbox is ticked //
	for(i=0;i<document.frmEditStaticGroup.elements.length;i++)
	{
	
	if (document.frmEditStaticGroup.elements[i].type =='checkbox' && document.frmEditStaticGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditStaticGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditStaticGroup.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'pages_ingroup': // Case of Static pages in the group
			atleastmsg 	= 'Please select the static Pages to Change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Static Page(s)?';
			retdivid   	= 'staticpages_div';
			moredivid	= 'pagesunassign_div';
			fpurpose	= 'changestat_static_pages';
			var chstat	= document.getElementById('static_pages_chstatus').value;
		break;
		case 'category': // Case of product messages
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
			atleastmsg 	= 'Please select the Static Page(s) Assigned to Group to change the status';
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
			document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result //
			document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result //
			retobj 										= eval("document.getElementById('"+retdivid+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/static_group.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_group_id='+group_id+'&ch_ids='+ch_ids);
		}	
	}	
}	
*/
function call_ajax_changeorderall(mod,checkboxname)
{
	var atleastone 			= 0;
	var group_id			= '<?php echo $group_id?>';
	var ch_ids 				= '';
	var ch_order			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var orderbox			= '';
	var atleastone 						= 0;
	var retdivid								= '';
	var staticgroupname								='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'pass_group_name='+staticgroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;

	switch(mod)
	{
		case 'pages_ingroup': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Static Pages to Change the Order';
			confirmmsg 	= 'Are you sure you want to Change the Order of selected Static Page(s)?';
			/*retdivid   	= 'staticpages_div';
			moredivid	= 'pagesunassign_div';*/
			fpurpose	= 'changeorder_static_pages';
			orderbox	= 'static_pages_order_';
		break;
		
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditStaticGroup.elements.length;i++)
	{
	if (document.frmEditStaticGroup.elements[i].type =='checkbox' && document.frmEditStaticGroup.elements[i].name== checkboxname)
		{
		

			if (document.frmEditStaticGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditStaticGroup.elements[i].value;
				 obj = eval("document.getElementById('"+orderbox+document.frmEditStaticGroup.elements[i].value+"')");
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
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/static_group.php','fpurpose='+fpurpose+'&cur_group_id='+group_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var group_id			= '<?php echo $group_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var group_id							= '<?php echo $group_id?>';
	var retdivid								= '';
	var staticgroupname								='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditStaticGroup.elements.length;i++)
	{
		if (document.frmEditStaticGroup.elements[i].type =='checkbox' && document.frmEditStaticGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditStaticGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditStaticGroup.elements[i].value;
			}	
		}
	}
	 var qrystr									= 'pass_group_name='+staticgroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;

	switch(mod)
	{
		case 'pages_ingroup': // Case of Static pages in the group
			atleastmsg 	= 'Please select the Static Pages(s) to be deleted to be deleted from the Menu';
			confirmmsg 	= 'Are you sure you want to delete the selected Static Page(s) from the Menu?';
			fpurpose	= 'delete_static_pages';
		break;
		case 'category': // Case of product messages
			atleastmsg 	= 'Please select the Product Categories to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product Categories?'
			fpurpose	= 'delete_category_ajax';
		break;
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			fpurpose	= 'delete_product_ajax';
		break;
		case 'assign_pages': // Case of linked products
			atleastmsg 	= 'Please select the Static Page(s) to be Unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Static Page(s)?'
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
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			
			Handlewith_Ajax('services/static_group.php','fpurpose='+fpurpose+'&cur_group_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function valform(frm)
{
	fieldRequired = Array('group_name','group_position');
	fieldDescription = Array('Menu Name','Menu Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('group_name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditStaticGroup' action='home.php?request=stat_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=stat_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Static Page Menu</a><span> Edit Menu for <?="'".$row_staticgroup['group_name']."'";?></span></div></td>
        </tr>
       <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="4" align="left">
			
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','staticgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('static_tab_td','static')" class="<?php if($curtab=='static_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="static_tab_td"><span>Static pages in this Menu</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','displaycat_group')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Display Menu for Following Categories</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','displayproduct_group')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Display Menu for Following Products</span></td>
						<td  align="left" onClick="handle_tabs('statmenu_tab_td','displaystatic_group')" class="<?php if($curtab=='statmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="statmenu_tab_td"><span>Display Menu for Following Static Pages</span></td>
						<td width="20%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		<tr>
          <td colspan="4">
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_static_maininfo($group_id,$alert);
			}
			elseif ($curtab=='static_tab_td')
			{
				show_static_pages_list($group_id,$alert);
			}
			elseif ($curtab=='catmenu_tab_td')
			{
				show_category_list($group_id,$alert);
			}
			elseif ($curtab=='prodmenu_tab_td')
			{
				show_product_list($group_id,$alert);
			}
			elseif ($curtab=='statmenu_tab_td')
			{
				show_assign_pages_list($group_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>

		<tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray">
		   
		   <input type="hidden" name="group_id" id="group_id" value="<?=$group_id?>" />
		  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
		   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  </td>
        </tr>
      </table>
</form>	  

