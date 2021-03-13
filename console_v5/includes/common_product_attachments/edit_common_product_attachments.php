<?php
	/*#################################################################
	# Script Name 	: edit_common_product_attachments.php
	# Description 	: Page for editing common attachments
	# Coded by 		: Sny
	# Created on	: 10-Aug-2010
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
//#Define constants for this page

$page_type 	= 'Common Product Attachments';
$help_msg 	= get_help_messages('EDIT_GEN_PROD_ATTACH');
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
$attach_id=($_REQUEST['attach_id']?$_REQUEST['attach_id']:$_REQUEST['checkbox'][0]);

?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('attach_title');
	fieldDescription = Array('Attachment Title');
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
	var attach_id							= '<?php echo $attach_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&attach_id='+attach_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'attach_maininfo':
			fpurpose	= 'list_attach_maininfo';
		break;
		case 'attach_product': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_attached_products';
			//moredivid	= 'category_groupunassign_div';
		break;
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj								= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/common_product_attachment.php','fpurpose='+fpurpose+'&attach_id='+attach_id+'&'+qrystr);	
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
function normal_assign_displayProdAssign(searchname,sortby,sortorder,recs,start,pg,attachid)
{
	window.location 			= 'home.php?request=common_prod_attachment&fpurpose=ProdAssign&pass_searchname='+searchname+'&pass_sortby='+sortby+'&pass_sortorder='+sortorder+'&pass_records_per_page='+recs+'&pass_start='+start+'&pass_pg='+pg+'&pass_attach_id='+attachid;
}
function call_ajax_deleteall(mod,checkboxname)
{
	
	var atleastone 			= 0;
	var attach_id			= '<?php echo $attach_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var search_name								='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&attach_id='+attach_id+'&curtab='+curtab+'&showinall='+showinall;

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
			confirmmsg 	= 'Are you sure you want to Unassign the selected Product(s) from this General Attachment?';
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
			Handlewith_Ajax('services/common_product_attachment.php','fpurpose='+fpurpose+'&attach_id='+attach_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
</script>
<form name='frmcommon_attachment' action='home.php?request=common_prod_attachment' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit" />
<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="attach_id" id="attach_id" value="<?=$attach_id?>" />
<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=common_prod_attachment&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Common Product Attachments</a><span> Edit Common Product Attachments</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		 <tr>
          <td colspan="5" align="left" valign="middle">
		     	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
					<tr> 
						<td width="8%" align="left" onClick="handle_tabs('main_tab_td','attach_maininfo')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td width="15%" align="left" onClick="handle_tabs('products_tab_td','attach_product')" class="<?php if($curtab=='products_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="products_tab_td"><span>Mapped Products</span></td>
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
				show_maininfo($attach_id,$alert);
			}
			elseif ($curtab=='products_tab_td')
			{
				show_attachedproducts($attach_id,$alert);
			}
		  ?>
		   </div>
		  </td>
        </tr>
  </table>
</form>	  
