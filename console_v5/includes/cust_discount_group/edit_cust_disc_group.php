<?php
	/*#################################################################
	# Script Name 		: edit_cust_disc_group.php
	# Description 		: Page for editing Customer Group
	# Coded by 			: ANU
	# Created on		: 29-Feb-2008
	# Modified by		: Sny 
	# Modified On		: 24-Aug-2009
	#################################################################*/
$sql_group_special	=	"SELECT site_custgroup_special_display_enable FROM sites WHERE site_id=".$ecom_siteid;
$res_group_special	=	$db->query($sql_group_special);
if($db->num_rows($res_group_special)>0)
{
	$row_group_special = $db->fetch_array($res_group_special);
}
#Define constants for this page
$page_type 			= 'Customer Discount Group';
$help_msg 			= get_help_messages('EDIT_CUST_DISC_GROUP_MESS1');
$cust_disc_grp_id	= ($_REQUEST['cust_disc_grp_id']?$_REQUEST['cust_disc_grp_id']:$_REQUEST['checkbox'][0]);
$curtab				= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
if($cust_disc_grp_id)
{
		$sql_group			= "SELECT cust_disc_grp_name
								FROM 
									customer_discount_group  
								WHERE 
									cust_disc_grp_id=".$cust_disc_grp_id." AND  sites_site_id='".$ecom_siteid."'
								LIMIT 
									1";
}								
$res_group= $db->query($sql_group);
if($db->num_rows($res_group)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_group = $db->fetch_array($res_group);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('cust_disc_grp_name');
	fieldDescription = Array('Discount Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('cust_disc_grp_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.cust_disc_grp_discount.value>99) {
			alert("Discount allowed should be below 100% ");
			frm.cust_disc_grp_discount.focus();
			return false;
		}else if(frm.cust_disc_grp_discount.value<0) {
			alert("Discount allowed should be a Positive value. ");
			frm.cust_disc_grp_discount.focus();
			return false;
			}
		  else { 
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}
function handle_tabs(id,mod)
{ 
	<?php
		if($row_group_special['site_custgroup_special_display_enable'] > 0)
		{
	?>	tab_arr								= new Array('main_tab_td','customermenu_tab_td','prodmenu_tab_td','catmenu_tab_td','shelfmenu_tab_td','pagemenu_tab_td');
	<?php
		}
		else
		{
	?>	tab_arr								= new Array('main_tab_td','customermenu_tab_td','prodmenu_tab_td','catmenu_tab_td');
	<?php
		}
	?>
	var atleastone 						= 0;
	var group_id							= '<?php echo $cust_disc_grp_id?>';
	var shop_orders						= '';
	var fpurpose							= '';
	var retdiv_id								= '';
	var customergroupname								='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr									= 'pass_group_name='+customergroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
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
		case 'customergroupmain_info':
			fpurpose ='list_customergroup_maininfo';
		break;
		case 'customer_group': // Case of Categories in the group
			fpurpose	= 'list_customer';
			
		break;
		case 'product_group': // Case of Display Products in the group
			fpurpose	= 'list_products';
		break;
		case 'category_group': // case of displaying categories assigned to current customer discount group
			fpurpose	= 'list_categories';
		break;
		case 'shelf_group': // Case of Display Shelves in the group
			fpurpose	= 'list_shelves';
		case 'page_group': // Case of Display Pages in the group
			fpurpose	= 'list_pages';
		break;
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/cust_discount_group.php','fpurpose='+fpurpose+'&cust_disc_grp_id='+group_id+'&'+qrystr);	
	
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
function call_ajax_deleteall(checkboxname,mod)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var group_id							= '<?php echo $cust_disc_grp_id?>';
	var retdiv_id							= '';
	var customergroupname					='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr								= 'pass_group_name='+customergroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomerGroup.elements.length;i++)
	{
		if (document.frmEditCustomerGroup.elements[i].type =='checkbox' && document.frmEditCustomerGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomerGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCustomerGroup.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'customer':
			atleastmsg 	= 'Please select the customer(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Customer(s)?';
			fpurpose	= 'unassign_customerdetails';
		break;
		case 'products':
			atleastmsg 	= 'Please select the product(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Product(s)?';
			fpurpose	= 'unassign_productsdetails';
		break;
		case 'categories':
			atleastmsg 	= 'Please select the categories to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Categories?';
			fpurpose	= 'unassign_categorydetails';
		break;
		case 'shelves':
			atleastmsg 	= 'Please select the shelves to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Shelves?';
			fpurpose	= 'unassign_shelvesdetails';
		break;
		case 'pages':
			atleastmsg 	= 'Please select the page(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Page(s)?';
			fpurpose	= 'unassign_pagesdetails';
	};
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
			Handlewith_Ajax('services/cust_discount_group.php','fpurpose='+fpurpose+'&cust_disc_grp_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function normal_assign_customerGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=cust_discount_group&fpurpose=add_customer&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cust_disc_grp_id='+custgroupid;
}
function normal_assign_productGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=cust_discount_group&fpurpose=add_products&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cust_disc_grp_id='+custgroupid;
}
function normal_assign_categoryGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=cust_discount_group&fpurpose=add_categories&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cust_disc_grp_id='+custgroupid;
}
function normal_assign_shelfGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=cust_discount_group&fpurpose=add_shelves&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cust_disc_grp_id='+custgroupid;
}
function normal_assign_pageGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=cust_discount_group&fpurpose=add_pages&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_cust_disc_grp_id='+custgroupid;
}
</script>
<form name='frmEditCustomerGroup' action='home.php?request=cust_discount_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_discount_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer Discount Groups</a><span> Edit Group for <? echo "'".$row_group['cust_disc_grp_name']."'";?></span></div></td>
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
						<td  align="left" onClick="handle_tabs('main_tab_td','customergroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('customermenu_tab_td','customer_group')" class="<?php if($curtab=='customermenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="customermenu_tab_td"><span>Customers in this Group</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','product_group')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Products in this Group</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','category_group')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Categories in this Group</span></td>
                        <?php
							if($row_group_special['site_custgroup_special_display_enable'] > 0)
							{
						?>
                        <td  align="left" onClick="handle_tabs('shelfmenu_tab_td','shelf_group')" class="<?php if($curtab=='shelfmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shelfmenu_tab_td"><span>Shelves in this Group</span></td>
                        <td  align="left" onClick="handle_tabs('pagemenu_tab_td','page_group')" class="<?php if($curtab=='pagemenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="pagemenu_tab_td"><span>Static Pages in this Group</span></td>
                        <?php
							}
						?>
						<td width="40%" align="left">&nbsp;</td>
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
				show_customer_grp_maininfo($cust_disc_grp_id,$alert);
			}
			elseif ($curtab=='customermenu_tab_td')
			{
				show_display_customer_discountgroup_list($cust_disc_grp_id,$alert);
			}
			elseif ($curtab=='prodmenu_tab_td')
			{
				show_display_products_discountgroup_list($cust_disc_grp_id,$alert);
			}
			elseif ($curtab=='catmenu_tab_td')
			{
				show_display_categories_discountgroup_list($cust_disc_grp_id,$alert);
			}
			elseif ($curtab=='shelfmenu_tab_td')
			{
				show_display_shelves_discountgroup_list($cust_disc_grp_id,$alert);
			}
			elseif ($curtab=='pagemenu_tab_td')
			{
				show_display_pages_discountgroup_list($cust_disc_grp_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		
      
        <tr>
         
          <td align="center" valign="middle" class="tdcolorgray" colspan="2">
		  
		  <input type="hidden" name="cust_disc_grp_id" id="cust_disc_grp_id" value="<?=$cust_disc_grp_id?>" />
		  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $cust_disc_grp_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="gift_bow" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $cust_disc_grp_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
        </tr>
      </table>
</form>

