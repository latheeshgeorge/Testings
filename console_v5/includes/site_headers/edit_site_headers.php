<?php
	/*#################################################################
	# Script Name 	: edit_site_headers.php
	# Description 	: Page for editing Site Headers
	# Coded by 		: ANU
	# Created on	: 1-Aug-2007
	# Modified by	: Sny
	# Modified On	: 26-Nov-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Site Headers';
$help_msg =get_help_messages('EDIT_SITE_HEADERS_MESS1');
$header_id=($_REQUEST['header_id']?$_REQUEST['header_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT header_title,header_filename,header_period_change_required,header_startdate,
			 header_enddate,header_hide,header_showinall 
					FROM site_headers 
						WHERE sites_site_id=$ecom_siteid AND header_id=".$header_id;
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row=$db->fetch_array($res);

	// Get the value of shopgroup_showinall field for current shop group
if($header_id)
{
 
	$sql_header = "SELECT header_showinall,header_title 
									FROM 
										site_headers 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND header_id = ".$header_id." 
									LIMIT 
										1";
	$ret_header	 = $db->query($sql_header);
	if ($db->num_rows($ret_header))
	{
		$row_header 		= $db->fetch_array($ret_header);
		$showinallpages		= $row_header['header_showinall'];
	}
	else
		exit;

}
	$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

?>	
<script language="javascript" type="text/javascript">
function change_show_date_period()
{
	
	if(document.frmEditSiteHeaders.header_period_change_required.checked==true)
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
	
	tab_arr 									= new Array('main_tab_td','catmenu_tab_td','prodmenu_tab_td','statmenu_tab_td');
	var atleastone 							= 0;
	var header_id							= '<?php echo $header_id?>';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var sitetitle							='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr								= 'search_name='+sitetitle+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&header_id='+header_id+'&curtab='+curtab+'&showinall='+showinall;
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
		case 'siteheadersmain_info':
			fpurpose ='list_sitegheaders_maininfo';
		break;
		case 'displayproduct_header': // Case of Display Products in the group
			fpurpose	= 'list_products_ajax';
		break;
		case 'displaycat_header': // Case of Display Categories in the group
			fpurpose	= 'list_categoriesInHeaders_ajax';
		break;
		case 'displaystatic_header': // Case of Display Categories in the group
			fpurpose	= 'list_assign_pages_ajax';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/site_headers.php','fpurpose='+fpurpose+'&cur_headerid='+header_id+'&'+qrystr);	
	
	
	
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
function call_ajax_removeimg()
{
	var header_id			= '<?php echo $header_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var sitetitle			='<?php echo $_REQUEST['search_name']?>';
	var sortby				= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder			= '<?php echo $_REQUEST['sort_order']?>';
	var recs				= '<?php echo $_REQUEST['records_per_page']?>';
	var start				= '<?php echo $_REQUEST['start']?>';
	var pg					= '<?php echo $_REQUEST['pg']?>';
	var curtab				= '<?php echo $curtab?>';
	var showinall			= '<?php echo $showinallpages?>';
	var qrystr				= 'search_name='+sitetitle+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&header_id='+header_id+'&curtab='+curtab+'&showinall='+showinall;
	if(confirm('Are you sure you want to remove the image?'))
	{
		fpurpose			= 'remove_image_ajax';
		retobj 				= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
		retobj.innerHTML 	= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/site_headers.php','fpurpose='+fpurpose+'&cur_header_id='+header_id+'&'+qrystr);
	}
}
function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var header_id			= '<?php echo $header_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	var sitetitle			='<?php echo $_REQUEST['search_name']?>';
	var sortby				= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder			= '<?php echo $_REQUEST['sort_order']?>';
	var recs				= '<?php echo $_REQUEST['records_per_page']?>';
	var start				= '<?php echo $_REQUEST['start']?>';
	var pg					= '<?php echo $_REQUEST['pg']?>';
	var curtab				= '<?php echo $curtab?>';
	var showinall			= '<?php echo $showinallpages?>';
	var qrystr				= 'search_name='+sitetitle+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&header_id='+header_id+'&curtab='+curtab+'&showinall='+showinall;

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditSiteHeaders.elements.length;i++)
	{
		if (document.frmEditSiteHeaders.elements[i].type =='checkbox' && document.frmEditSiteHeaders.elements[i].name==checkboxname)
		{

			if (document.frmEditSiteHeaders.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditSiteHeaders.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'category': // Case of product categories
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
			
			Handlewith_Ajax('services/site_headers.php','fpurpose='+fpurpose+'&cur_header_id='+header_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function valform(frm)
{
	var fname = '<?php echo $row['header_filename']?>';
	if(fname=='' && document.getElementById('header_caption').value=='')
	{
		fieldRequired = Array('header_title','header_filename');
		fieldDescription = Array('Header Title','Header Image file Or Header Caption');
	}
	else
	{
		fieldRequired = Array('header_title');
		fieldDescription = Array('Header Title');
	}
	
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		// validation for date format
		//alert(document.frmEditSiteHeaders.header_startdate.value);
		if(document.frmEditSiteHeaders.header_period_change_required.checked  ==true){
			val_dates = compareDates(document.frmEditSiteHeaders.header_startdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmEditSiteHeaders.header_enddate,"End Date\n Correct Format:dd-mm-yyyy");
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
		  
	}
	 else {
		return false;
	}
}
</script>
<form name='frmEditSiteHeaders' action='home.php?request=site_headers' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=site_headers&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&search_name=<?=$_REQUEST['pass_search_name']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Site Headers</a><span> Edit Site Header</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="6">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
			<td colspan="6" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','siteheadersmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('catmenu_tab_td','displaycat_header')" class="<?php if($curtab=='catmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="catmenu_tab_td"><span>Display Site Headers for Following Categories</span></td>
						<td  align="left" onClick="handle_tabs('prodmenu_tab_td','displayproduct_header')" class="<?php if($curtab=='prodmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prodmenu_tab_td"><span>Display Site Headers for Following Products</span></td>
						<td  align="left" onClick="handle_tabs('statmenu_tab_td','displaystatic_header')" class="<?php if($curtab=='statmenu_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="statmenu_tab_td"><span>Display Site Headers for Following Static Pages</span></td>
						<td width="24%" align="left">&nbsp;</td>
				</tr>
				</table>				
			</td>
		</tr>
		<tr>
          <td colspan="6">
		  <div id='master_div'>
			<?php 
			if ($curtab=='main_tab_td')
			{
				show_siteheaders_maininfo($header_id,$alert);
			}
			elseif ($curtab=='catmenu_tab_td')
			{
				show_category_list($header_id,$alert);
			}
			elseif ($curtab=='prodmenu_tab_td')
			{
				show_product_list($header_id,$alert);
			}
			elseif ($curtab=='statmenu_tab_td')
			{
				show_assign_pages_list($header_id,$alert);
			}
			?>		
		  </div>
		  </td>
		  </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="right" valign="middle" class="tdcolorgray">
			<input type="hidden" name="header_id" id="header_id" value="<?=$header_id?>" />
			<input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
			<input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
			<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
			<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
			<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
			<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="update_site_header" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
		 <tr>
          <td colspan="6" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		<tr>
          <td colspan="6" align="left" valign="middle" class="tdcolorgray" >&nbsp; </td>
        </tr><tr>
      </table>
</form>	  

