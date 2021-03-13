<?php
	/*#################################################################
	# Script Name 	: edit_common_product_tabs.php
	# Description 	: Page for editing common tabs
	# Coded by 		: Sny
	# Created on	: 13-Aug-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//#Define constants for this page

$page_type 	= 'Common Product Tabs';
$help_msg 	= get_help_messages('EDIT_GEN_PROD_TAB');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
$tab_id=($_REQUEST['tab_id']?$_REQUEST['tab_id']:$_REQUEST['checkbox'][0]);
?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('tab_title');
	fieldDescription = Array('Tab Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	}
	 else {
		return false;
	}
}
function handle_tabs(id,mod)
{
	tab_arr 								= new Array('main_tab_td','products_tab_td');
	var atleastone 							= 0;
	var tab_id								= '<?php echo $tab_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							='<?php echo $_REQUEST['pass_search_name']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs								= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr								= 'pass_search_name='+search_name+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&tab_id='+tab_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'tab_maininfo':
			show_processing();
			document.frmcommon_attachment.fpurpose.value = 'edit';
			document.frmcommon_attachment.submit();
			return;
		break;
		case 'tab_product': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_tab_products';
			//moredivid	= 'category_groupunassign_div';
		break;
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj								= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/common_product_tab.php','fpurpose='+fpurpose+'&tab_id='+tab_id+'&'+qrystr);	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 			= req.responseText;
			targetdiv 			= document.getElementById('retdiv_id').value;
			targetobj 			= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
		}
		else
		{
			show_request_alert(req.status);
		}
	}
}
function normal_assign_displayProdAssign(searchname,sortby,sortorder,recs,start,pg,tabid)
{
	window.location 			= 'home.php?request=common_prod_tab&fpurpose=ProdAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_tab_id='+tabid;
}
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var tab_id			= '<?php echo $tab_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var search_name								='<?php echo $_REQUEST['pass_search_name']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs									= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'pass_search_name='+search_name+'&pass_sort_by='+sortby+'&pass_sort_order='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&tab_id='+tab_id+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmcommon_attachment.elements.length;i++)
	{
		if (document.frmcommon_attachment.elements[i].type =='checkbox' && document.frmcommon_attachment.elements[i].name==checkboxname)
		{

			if (document.frmcommon_attachment.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmcommon_attachment.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product_unassign': // Case of Products in the combo
			atleastmsg 	= 'Please select the Product(s) to be unassigned';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Product(s) from this General Tab?';
			fpurpose	= 'prodUnAssign';
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
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/common_product_tab.php','fpurpose='+fpurpose+'&tab_id='+tab_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name='frmcommon_attachment' action='home.php?request=common_prod_tab' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
<input type="hidden" name="pass_search_name" id="search_name" value="<?=$_REQUEST['pass_search_name']?>" />
<input type="hidden" name="tab_id" id="tab_id" value="<?=$tab_id?>" />
<input type="hidden" name="pass_start" id="start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_sort_by" id="sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_pg" id="pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=common_prod_tab&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Common Product Tabs  </a><span> Edit Common Product Tabs</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		 <tr>
          <td colspan="5" align="left" valign="middle">
		     	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x" >
					<tr> 
						<td width="8%" align="left" onClick="handle_tabs('main_tab_td','tab_maininfo')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td width="15%" align="left" onClick="handle_tabs('products_tab_td','tab_product')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Mapped Products</span></td>
						<td width="90%" align="left">&nbsp;</td>
					</tr>
				</table>
		  </td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td align="center">
		   <div id='master_div'>
		  <?php
		  	if ($curtab=='main_tab_td')
			{
				//include_once("classes/fckeditor.php");
				show_maininfo($tab_id,$alert);
			}
			elseif ($curtab=='products_tab_td')
			{
				show_tabproducts($tab_id,$alert);
			}
		  ?>
		   </div>
		  </td>
        </tr>
  </table>
</form>	  
