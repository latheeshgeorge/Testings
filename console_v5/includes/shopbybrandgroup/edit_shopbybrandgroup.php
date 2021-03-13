<?php
	/*#################################################################
	# Script Name 	: edit_shopbybrandgroup.php
	# Description 	: Page for editing Product shop groups
	# Coded by 		: Sny
	# Created on	: 13-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	//#Define constants for this page
	$page_type = 'Product Shop Menu';
	$help_msg =get_help_messages('EDIT_PROD_SHOP_GROUP1');

	// Get the value of shopgroup_showinall field for current shop group
if($edit_id)
{
	$sql_shopgroup = "SELECT shopbrandgroup_showinall,shopbrandgroup_name 
									FROM 
										product_shopbybrand_group 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND shopbrandgroup_id = ".$edit_id." 
									LIMIT 
										1";
	$ret_shopgroup	 = $db->query($sql_shopgroup);
	if($db->num_rows($ret_shopgroup)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
	if ($db->num_rows($ret_shopgroup))
	{
		$row_shopgroup 		= $db->fetch_array($ret_shopgroup);
		$showinallpages		= $row_shopgroup['shopbrandgroup_showinall'];
	}
	else
		exit;
}
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>	
<script language="javascript" type="text/javascript">
function handle_tabs(id,mod)
{ 
	
	tab_arr 									= new Array('main_tab_td','shop_tab_td','catmenu_tab_td','prodmenu_tab_td','statmenu_tab_td');
	var atleastone 						= 0;
	var group_id							= '<?php echo $edit_id?>';
	var shop_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	var shopname								='<?php echo $_REQUEST['shopgroupname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'shopgroupname='+shopname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'shopgroupmain_info':
			fpurpose ='list_shopgroup_maininfo';
		break;
		case 'shop': // Case of Categories in the group
			fpurpose	= 'list_shopgroup';
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
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/shopbybrandgroup.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
	
	
	
}
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('shopbrandgroup_name');
	fieldDescription = Array('Product Shop Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
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
		
		
			if(document.frmEditShopByBrandGroup.shopbrandgroup_activateperiodchange.checked  == true){
			
			val_dates = compareDates(document.frmEditShopByBrandGroup.shopbrandgroup_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmEditShopByBrandGroup.shopbrandgroup_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
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
function normal_assign_shopprod(cname,sortby,sortorder,recs,start,pg,shopgroupid)
{
		window.location 			= 'home.php?request=shopbybrandgroup&fpurpose=assign_selshopprod&pass_shopgroupname='+cname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shopgroup_id='+shopgroupid;
}

function normal_assign_prodGroupAssign(cname,sortby,sortorder,recs,start,pg,shopgroupid)
{
		window.location 			= 'home.php?request=shopbybrandgroup&fpurpose=prodGroupAssign&pass_shopgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shopgroup_id='+shopgroupid;
}
function normal_assign_CategoryGroupAssign(cname,sortby,sortorder,recs,start,pg,shopgroupid)
{
		window.location 			= 'home.php?request=shopbybrandgroup&fpurpose=categoryGroupAssign&pass_shopgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shopgroup_id='+shopgroupid;
}
function normal_assign_StaticGroupAssign(cname,sortby,sortorder,recs,start,pg,shopgroupid)
{
		window.location 			= 'home.php?request=shopbybrandgroup&fpurpose=staticGroupAssign&pass_shopgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shopgroup_id='+shopgroupid;
}
function normal_assign_shops(cname,sortby,sortorder,recs,start,pg,shopgroupid)
{
		window.location 			= 'home.php?request=shopbybrandgroup&fpurpose=shop_sel&pass_shopgroupname='+cname+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_shopgroup_id='+shopgroupid;
}
function call_ajax_deleteall(mod,checkboxname)
{
	
	tab_arr 							= new Array('main_tab_td','shop_tab_td','catmenu_tab_td','prodmenu_tab_td','statmenu_tab_td');
	var atleastone 						= 0;
	var group_id						= '<?php echo $edit_id?>';
	var del_ids 						= '';
	var shop_orders						= '';
	var fpurpose						= '';
	var retdivid						= '';
	var moredivid						= '';
	var shopname						='<?php echo $_REQUEST['shopgroupname']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg								= '<?php echo $_REQUEST['pg']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $showinallpages?>';
	var qrystr							= 'shopgroupname='+shopname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShopByBrandGroup.elements.length;i++)
	{
		if (document.frmEditShopByBrandGroup.elements[i].type =='checkbox' && document.frmEditShopByBrandGroup.elements[i].name==checkboxname)
		{
			if (document.frmEditShopByBrandGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditShopByBrandGroup.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'shops': // Case of unassigning shops from shop group
			atleastmsg 	= 'Please select the Shop(s) to be Unassigned from the menu';
			confirmmsg 	= 'Are you sure you want to be Unassigned the selected Shop(s) from the menu?';
			fpurpose	= 'unassignshop';
		break;
		case 'displayproduct_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Product(s) to be Unassigned from this product shop menu';
			confirmmsg 	= 'Are you sure you want to be Unassigned the selected Product(s) from this Product Shop Menu?';
			fpurpose	= 'prodGroupUnAssign';
		break;
		case 'displaycategory_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Category(s) to be Unassigned from this product shop menu';
			confirmmsg 	= 'Are you sure you want to be Unassigned the selected Category(s) from this product shop menu?';
			fpurpose	= 'categoryGroupUnAssign';
		break;
		case 'displaystatic_group': // Case of Display Products in the group
		    
			atleastmsg 	= 'Please select the Page(s) to be Unassigned from current product shop menu';
			confirmmsg 	= 'Are you sure you want to be Unassigned the selected Page(s) from current product shop menu?';
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
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/shopbybrandgroup.php','fpurpose='+fpurpose+'&shopgroup_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	

}
function call_save_order(mod,checkboxname)
{
	var atleastone 			= 0;
	var shopgroup_id			= '<?php echo $edit_id?>';
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
	var shopname						='<?php echo $_REQUEST['shopgroupname']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg								= '<?php echo $_REQUEST['pg']?>';
	var curtab							= '<?php echo $curtab?>';
	var showinall						= '<?php echo $showinallpages?>';
	var qrystr							= 'shopgroupname='+shopname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab+'&showinall='+showinall;
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
	}
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditShopByBrandGroup.elements.length;i++)
	{
	if (document.frmEditShopByBrandGroup.elements[i].type =='checkbox' && document.frmEditShopByBrandGroup.elements[i].name== checkboxname)
		{
		

			if (document.frmEditShopByBrandGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditShopByBrandGroup.elements[i].value;
				 obj = eval("document.getElementById('"+orderbox+document.frmEditShopByBrandGroup.elements[i].value+"')");
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
			Handlewith_Ajax('services/shopbybrandgroup.php','fpurpose='+fpurpose+'&shopgroup_id='+shopgroup_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}	
	}	
}
function change_show_date_period()
{
	
	if(document.frmEditShopByBrandGroup.shopbrandgroup_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}

</script>
<form name='frmEditShopByBrandGroup' action='home.php?request=shopbybrandgroup' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrandgroup&&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Shop Menu</a> <span>Edit Product Shop Menu for <?="'".$row_shopgroup['shopbrandgroup_name']."'";?> </span></div></td>
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
						<td  align="left" onClick="handle_tabs('main_tab_td','shopgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('shop_tab_td','shop')" class="<?php if($curtab=='shop_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="shop_tab_td"><span>Shops in this Menu</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','displaycat_group')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Display Menu for Following Categories</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','displayproduct_group')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Display Menu for Following Products</span></td>
						<td  align="left" onClick="handle_tabs('statmenu_tab_td','displaystatic_group')" class="<?php if($curtab=='statmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="statmenu_tab_td"><span>Display Menu for Following Static Pages</span></td>
						<td width="34%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		<?php
			/*if($alert)
			{*/
		?>
		<!--   <tr>
		  <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
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
				show_shopbybrandgroup_maininfo($edit_id,$alert);
			}
			elseif ($curtab=='shop_tab_td')
			{
				show_shop_group_list($edit_id,$alert);
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
       <input type="hidden" name="shopgroupname" id="shopgroupname" value="<?=$_REQUEST['shopgroupname']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
				<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $edit_id?>" />
				<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		
		  
		 <?
		?>
  </table>
</form>	  

