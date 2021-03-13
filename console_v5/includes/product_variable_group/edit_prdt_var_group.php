<?php
	/*#################################################################
	# Script Name 		: edit_prdt_var_group.php
	# Description 		: Page for editing Product Variable Group
	# Coded by 			: Sobin Babu
	# Created on		: 26-July-2008
	# Modified by		:  
	# Modified On		: 
	#################################################################*/
#Define constants for this page
$page_type 			= 'Product Variable Group';
$help_msg 			= get_help_messages('EDIT_PRDT_VAR_GROUP_MESS1');
$prdt_var_grp_id	= ($_REQUEST['prdt_var_grp_id']?$_REQUEST['prdt_var_grp_id']:$_REQUEST['checkbox'][0]);
$curtab				= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
if($prdt_var_grp_id)
{
	$sql_group		= "SELECT *  FROM product_variables_group  WHERE var_group_id = ".$prdt_var_grp_id." AND sites_site_id = '".$ecom_siteid."' LIMIT 1";
}								
$res_group= $db->query($sql_group);
if($db->num_rows($res_group)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_group = $db->fetch_array($res_group);
?>	
<script language="javascript" type="text/javascript">
function call_ajax_savehorizontal(search_name,sortby,sortorder,recs,start,pg,group_id)
{
	var onlyone 	= 0;
	var Idstr		= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditVariableGroup.elements.length;i++)
	{
		if (document.frmEditVariableGroup.elements[i].type =='checkbox' && document.frmEditVariableGroup.elements[i].name=='checkboxdisplayvariables[]')
		{

			if (document.frmEditVariableGroup.elements[i].checked==true)
			{
				onlyone = onlyone+1;
				Idstr = document.frmEditVariableGroup.elements[i].value;
				
				/* if (Orderstr!='')
					Orderstr += '~';
				obj = eval('document.frmEditVariableGroup.ord_'+document.frmEditVariableGroup.elements[i].value);
				 Orderstr += obj.value;*/
			}	
		}
	}
	if (onlyone==0)
	{
		alert('Please select the variable to set as horizontal');
	}
	else if(onlyone > 1)
	{
		alert('Please select the only one variable to set as horizontal');
	}
	else
	{
		if(confirm('Save this Variable as Horizontal?'))
		{
			Handlewith_Ajax('services/product_variable_group.php','fpurpose=save_var_horizontal&'+qrystr+'&Idstr='+Idstr+'&prdt_var_grp_id='+group_id);
		}	
	}	
}

function call_ajax_saveorder(search_name,sortby,sortorder,recs,start,pg,group_id)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var Idstr		= '';
	var Orderstr	= '';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg;;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditVariableGroup.elements.length;i++)
	{
		if (document.frmEditVariableGroup.elements[i].type =='checkbox' && document.frmEditVariableGroup.elements[i].name=='checkboxdisplayvariables[]')
		{

			if (document.frmEditVariableGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (Idstr!='')
					Idstr += '~';
				 Idstr += document.frmEditVariableGroup.elements[i].value;
				 if (Orderstr!='')
					Orderstr += '~';
				obj = eval('document.frmEditVariableGroup.ord_'+document.frmEditVariableGroup.elements[i].value);
				 Orderstr += obj.value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the variable to save the order');
	}
	else
	{
		if(confirm('Save Sort Order Of Variable?'))
		{
			Handlewith_Ajax('services/product_variable_group.php','fpurpose=save_var_order&'+qrystr+'&Idstr='+Idstr+'&OrderStr='+Orderstr+'&prdt_var_grp_id='+group_id);
		}	
	}	
}

function valform(frm)
{
	fieldRequired = Array('var_group_name');
	fieldDescription = Array('Product Variable Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) 
	{
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
	tab_arr								= new Array('main_tab_td','varmenu_tab_td','catmenu_tab_td');
	
	var atleastone 						= 0;
	var group_id						= '<?php echo $prdt_var_grp_id?>';
	var shop_orders						= '';
	var fpurpose						= '';
	var retdiv_id						= '';
	var variablegroupname				='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg								= '<?php echo $_REQUEST['pg']?>';
	var curtab							= '<?php echo $curtab?>';
	var qrystr							= 'pass_group_name='+variablegroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;
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
		case 'variablegroupmain_info':
			fpurpose ='list_variablegroup_maininfo';
		break;
		case 'variable_group': // Case of Display Products in the group
			fpurpose	= 'list_variables';
		break;
		case 'category_group': // case of displaying categories assigned to current customer discount group
			fpurpose	= 'list_categories';
		break;
		
	}
	retobj								= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML					= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/product_variable_group.php','fpurpose='+fpurpose+'&prdt_var_grp_id='+group_id+'&'+qrystr);	
	
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
/*function save_cust_discount()
{
	var atleastone 			= 0;
	var map_ids				= '';
	var cat_discs 			= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var group_id			= '<?php echo $prdt_var_grp_id?>';
	var retdiv_id			= 'master_div';
	for(i=0;i<document.frmEditVariableGroup.elements.length;i++)
	{
		if (document.frmEditVariableGroup.elements[i].type =='checkbox' && document.frmEditVariableGroup.elements[i].name=='checkboxdisplaycategories[]')
		{

			if (document.frmEditVariableGroup.elements[i].checked==true)
			{
				atleastone = 1;
				 if (map_ids!='')
					map_ids += '~';
				 map_ids += document.frmEditVariableGroup.elements[i].value;
				
				 if (cat_discs!='')
					cat_discs += '~';
				objs = eval ("document.getElementById('customer_discount_group_category_discount_"+document.frmEditVariableGroup.elements[i].value+"')");	
				 cat_discs += objs.value;
			}	
		}
	}
	
	if (atleastone==0)
	{
		alert('Please tick the categories to save the discount details.');
	}
	else
	{
		if(document.getElementById('custdisc_cat_alert'))
		{
			document.getElementById('custdisc_cat_alert').style.display = 'none';
		}
		if(confirm('Are you sure you want to save the details?'))
		{
			fpurpose			= 'save_category_discount';
			retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/cust_discount_group.php','fpurpose='+fpurpose+'&prdt_var_grp_id='+group_id+'&cat_discs='+cat_discs+'&map_ids='+map_ids);
		}	
	}	
}*/
function call_ajax_deleteall(checkboxname,mod)
{
	var atleastone 			= 0;
	var del_ids 			= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var group_id							= '<?php echo $prdt_var_grp_id?>';
	var retdiv_id							= '';
	var variablegroupname					='<?php echo $_REQUEST['pass_group_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr								= 'pass_group_name='+variablegroupname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditVariableGroup.elements.length;i++)
	{
		if (document.frmEditVariableGroup.elements[i].type =='checkbox' && document.frmEditVariableGroup.elements[i].name==checkboxname)
		{

			if (document.frmEditVariableGroup.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditVariableGroup.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'variables':
			atleastmsg 	= 'Please select the variable(s) to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Variable(s)?';
			fpurpose	= 'unassign_variabledetails';
		break;
		case 'categories':
			atleastmsg 	= 'Please select the categories to be unassigned.';
			confirmmsg 	= 'Are you sure you want to unassign the selected Categories?';
			fpurpose	= 'unassign_categorydetails';
		break;
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
			Handlewith_Ajax('services/product_variable_group.php','fpurpose='+fpurpose+'&prdt_var_grp_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function normal_assign_variableGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=product_variable_group&fpurpose=add_variables&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_prdt_var_grp_id='+custgroupid;
}
function normal_assign_categoryGroupAssign(searchname,sortby,sortorder,recs,start,pg,custgroupid)
{
	window.location 			= 'home.php?request=product_variable_group&fpurpose=add_categories&pass_group_name='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_prdt_var_grp_id='+custgroupid;
}
</script>
<form name='frmEditVariableGroup' action='home.php?request=product_variable_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_variable_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['pass_group_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Variables Groups</a> <span>Edit Group for <? echo "'".$row_group['var_group_name']."'";?></span></div></td>
        </tr>
        <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="4" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
		<tr>
			<td colspan="4" align="left">
				<table width="100%" border="0" cellspacing="1" cellpadding="1" class="tabmenu_x">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','variablegroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('varmenu_tab_td','variable_group')" class="<?php if($curtab=='varmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="varmenu_tab_td"><span>Variables in this Group</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','category_group')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Categories in this Group</span></td>
						<td width="65%" align="left">&nbsp;</td>
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
				show_prdt_var_grp_maininfo($prdt_var_grp_id,$alert);
			}
			elseif ($curtab=='varmenu_tab_td')
			{
				show_display_variables_list($prdt_var_grp_id,$alert);
			}
			elseif ($curtab=='catmenu_tab_td')
			{
				show_display_categories_list($prdt_var_grp_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		
      
        <tr>
         
          <td align="center" valign="middle" class="tdcolorgray" colspan="2">
		  
		  <input type="hidden" name="prdt_var_grp_id" id="prdt_var_grp_id" value="<?=$prdt_var_grp_id?>" />
		  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?php echo $prdt_var_grp_id?>" />
		  <input type="hidden" name="src_page" id="src_page" value="gift_bow" />
		  <input type="hidden" name="src_id" id="src_id" value="<?php echo $prdt_var_grp_id?>" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
        </tr>
      </table>
</form>

