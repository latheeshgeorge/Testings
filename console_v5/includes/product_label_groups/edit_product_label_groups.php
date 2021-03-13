<?php
	/*#################################################################
	# Script Name 	: edit_product_label_groups.php
	# Description 	: Page for editing Product label groups
	# Coded by 		: SNY
	# Created on	: 07-Apr-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type 	= 'Product Label Groups'; 
$help_msg 	= get_help_messages('EDIT_PROD_LAB_GRP_MESS1');
$group_id	= ($_REQUEST['group_id']?$_REQUEST['group_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('group_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	}
	else
	{
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
function handle_tabs(id,mod)
{ 
	
	tab_arr 								= new Array('main_tab_td','cat_tab_td','label_tab_td');
	var atleastone 							= 0;
	var group_id							= '<?php echo $group_id?>';
	var shop_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var shopname							='<?php echo $_REQUEST['shopgroupname']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr								= 'shopgroupname='+shopname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'labelgroupmain_info':
			fpurpose 	='list_group_maininfo';
		break;
		case 'cat': // Case of Categories in the group
			fpurpose	= 'list_categories';
		break;
		case 'label': // Case of labels in the group
			fpurpose	= 'list_labels';
		break;
	}
	retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/product_label_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&'+qrystr);	
}
function normal_assign_displayCategoryAssign(searchname,sortby,sortorder,recs,start,pg,groupid)
{
	window.location = 'home.php?request=prod_label_groups&fpurpose=displayCategoryAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
}
function normal_assign_displayLabelAssign(searchname,sortby,sortorder,recs,start,pg,groupid)
{
	window.location = 'home.php?request=prod_label_groups&fpurpose=displayLabelAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_group_id='+groupid;
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
	var search_name			='<?php echo $_REQUEST['search_name']?>';
	var sortby				= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder			= '<?php echo $_REQUEST['sort_order']?>';
	var recs				= '<?php echo $_REQUEST['records_per_page']?>';
	var start				= '<?php echo $_REQUEST['start']?>';
	var pg					= '<?php echo $_REQUEST['pg']?>';
	var curtab				= '<?php echo $curtab?>';
	var showinall			= '<?php echo $showinallpages?>';
	var qrystr				= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&group_id='+group_id+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditLableGroups.elements.length;i++)
	{
		if (document.frmEditLableGroups.elements[i].type =='checkbox' && document.frmEditLableGroups.elements[i].name==checkboxname)
		{

			if (document.frmEditLableGroups.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditLableGroups.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'displayCategoryUnAssign':
			atleastmsg 	= 'Please select the Categories to be unassigned from the Label Group';
			confirmmsg 	= 'Are you sure you want to unassign the selected Categories from the Label Group?';
			fpurpose	= 'displayCategoryUnAssign';
		break;
		case 'displayLabelUnAssign':
			atleastmsg 	= 'Please select the Labels to be unassigned from the Label Group';
			confirmmsg 	= 'Are you sure you want to unassign the selected Labels from the Label Group?';
			fpurpose	= 'displayLabelUnAssign';
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
			retobj 					= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_label_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function call_ajax_changeorderall(checkboxname)
{
	var atleastone 			= 0;
	var group_id			= '<?php echo $group_id?>';
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
	var varid				= '';
	var varvalueid			= '';
	var varprodids			= '';
	var tempname;
	var discgreater			= false;
	atleastmsg 	= 'Please select the Labels to Save the details';
	confirmmsg 	= 'Are you sure you want to Save the details of selected Label(s)?';
	fpurpose	= 'save_label_order';
	orderbox	= 'label_sortorder_';
	var temparr,tempnames;
	var tempprod = tempvar = tempvarval = tempprodasn = '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditLableGroups.elements.length;i++)
	{
		if (document.frmEditLableGroups.elements[i].type =='checkbox' && document.frmEditLableGroups.elements[i].name== checkboxname)
		{
			if (document.frmEditLableGroups.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				ch_ids += document.frmEditLableGroups.elements[i].value;
				
				obj = eval("document.getElementById('"+orderbox+document.frmEditLableGroups.elements[i].value+"')");
				if (ch_order != '')
					ch_order += '~';
				ch_order += ' '+obj.value; 
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
			retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/product_label_groups.php','fpurpose='+fpurpose+'&group_id='+group_id+'&ch_order='+ch_order+'&ch_ids='+ch_ids+'&'+qrystr);
		}
	}	
}
function handle_label_val(imgobj,myid)
{
	obj = eval("document.getElementById('"+myid+"')");
	if(obj)
	{
		if (obj.style.display=='none')
		{
			imgobj.src = 'images/down_arr.gif';
			obj.style.display = '';
		}
		else
		{
			imgobj.src = 'images/right_arr.gif';
			obj.style.display = 'none';
		}
	}
}
</script>
<form name='frmEditLableGroups' action='home.php?request=prod_label_groups' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_label_groups&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Label Groups</a><span> Edit Product Label Groups</span></div></td>
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
					<td  align="left" onClick="handle_tabs('main_tab_td','labelgroupmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
					<td  align="left" onClick="handle_tabs('cat_tab_td','cat')" class="<?php if($curtab=='cat_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="cat_tab_td"><span>Categories Mapped with this Group</span></td>
					<td  align="left" onClick="handle_tabs('label_tab_td','label')" class="<?php if($curtab=='label_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="label_tab_td"><span>Product Labels Mapped with this Group</span></td>
					<td width="94%" align="left">&nbsp;</td>
			</tr>
			</table>				
			</td>
		</tr>
		<tr>
		<td  class="tdcolorgray" valign="top" colspan="4">
		 <div id='master_div'>
		 <?php 
			if ($curtab=='main_tab_td')
			{
				show_labelgroup_maininfo($group_id,$alert);
			}
			elseif ($curtab=='cat_tab_td')
			{
				show_category_list($group_id,$alert);
			}
			elseif ($curtab=='label_tab_td')
			{
				show_label_list($group_id,$alert);
			}
			?>	
		 </div>
		</td>
		</tr>  
        <tr>
          <td width="44%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td   valign="middle" class="tdcolorgray" align="left">
		  <input type="hidden" name="group_id" id="group_id" value="<?=$group_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
        </tr>
      </table>
</form>	  
<script language="javascript" type="text/javascript">
function handle_expansion(imgobj,mod)
{

	if(imgobj == 'dropdown'){
		if(document.getElementById(imgobj).checked=true){
			document.getElementById(mod).style.display = '';
			document.getElementById('cattr_headmore').style.display = '';
		}
	}else if(imgobj == 'textbox'){
		if(document.getElementById(imgobj).checked=true){
		document.getElementById(mod).style.display = 'none';
		document.getElementById('cattr_headmore').style.display = 'none';
		}
	}
				
}

</script>
