<?php
	/*#################################################################
	# Script Name 	: edit_vendor.php
	# Description 	: Page for editing Site Vendor
	# Coded by 		: SKR
	# Created on	: 18-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Vendor';
$help_msg =get_help_messages('EDIT_PROD_VENDOR_MESS1');
$vendor_id=($_REQUEST['vendor_id']?$_REQUEST['vendor_id']:$_REQUEST['checkbox'][0]);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';

$sql_vendor="SELECT vendor_name,vendor_address,vendor_telephone,vendor_fax,vendor_email,vendor_website,vendor_hide 
					FROM product_vendors  
						WHERE vendor_id=".$vendor_id." AND sites_site_id=".$ecom_siteid." ";
$res_vendor= $db->query($sql_vendor);
if($db->num_rows($res_vendor)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row_vendor = $db->fetch_array($res_vendor);
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('vendor_name','vendor_email');
	fieldDescription = Array('Vendor Name','Email');
	fieldEmail = Array('vendor_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
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

function call_ajax_showlistall(mod)
{  
	var atleastone 										= 0;
	var vendor_id										= '<?php echo $vendor_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
			
	switch(mod)
	{
		case 'products': // Case of product assigned to the Page group
			//retdivid   	= 'products_div';
			fpurpose	= 'list_products_ajax';
		//	moredivid	= 'productsunassign_div';
		break;
	}
	//document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	//document.getElementById('retdiv_more').value 	= moredivid;/* Name of div to show the result */	
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	var qrystr									= '';
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/prod_vendor.php','fpurpose='+fpurpose+'&vendor_id='+vendor_id);
}

function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var vendor_id			= '<?php echo $vendor_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmAddCountry.elements.length;i++)
	{
		if (document.frmAddCountry.elements[i].type =='checkbox' && document.frmAddCountry.elements[i].name==checkboxname)
		{

			if (document.frmAddCountry.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmAddCountry.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		case 'product': // Case of product tabs
			atleastmsg 	= 'Please select the product(s) to be deleted';
			confirmmsg 	= 'Are you sure you want to delete the selected Product(s)?'
			//retdivid   	= 'products_div';
			//moredivid	= 'productsunassign_div';
			fpurpose	= 'delete_product_ajax';
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
			
			Handlewith_Ajax('services/prod_vendor.php','fpurpose='+fpurpose+'&vendor_id='+vendor_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_tabs(id,mod)
	{
	tab_arr 								= new Array('main_tab_td','prods_tab_td');  
	var atleastone 							= 0;
	var vendor_id								= '<?php echo $vendor_id?>';
	var cat_orders							= '';
	var fpurpose							= '';
	var retdivid							= '';
	var moredivid							= '';
	var search_name							= '<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs								= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $advert_showinall?>';
	var advert_title						= '<?php echo $advert_title; ?>';
	var qrystr								= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&vendor_id='+vendor_id+'&curtab='+curtab+'&advert_showinall='+showinall+'&advert_title='+advert_title;
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
		case 'vendor_main_info':
			fpurpose ='list_vendor_maininfo';
		break;
		case 'prod_vendor_info': // Case of Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_products_ajax';
			//moredivid	= 'category_groupunassign_div';
			
		break;  
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj								= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';
	//document.getElementById('retdiv_more').value = id;															
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+vendor_id);
	Handlewith_Ajax('services/prod_vendor.php','fpurpose='+fpurpose+'&vendor_id='+vendor_id+'&'+qrystr);	
}
</script>
<form name='frmAddCountry' action='home.php?request=prod_vendor' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_vendor&sort_by=<?=$sort_by?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Vendors</a> <span> Edit Vendor</span></div></td>
        </tr>
		
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<tr>
          <td colspan="2" align="center" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>  
						<td  align="left" onClick="handle_tabs('main_tab_td','vendor_main_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('prods_tab_td','prod_vendor_info')" class="<?php if($curtab=='prods_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="prods_tab_td"><span>Products from this Vendor </span></td>
						<td width="90%" align="left">&nbsp;</td>  
				</tr></table></td>
        </tr>
		  <tr>
          <td colspan="2" align="center" valign="middle" ><div id='master_div'>
			<?php 
			 
			if ($curtab=='main_tab_td')
			{
			
				show_vendor_maininfo($vendor_id,$alert);
			}
			elseif ($curtab=='prods_tab_td')
			{
				show_product_list($vendor_id,$alert);
			}
		
			?>		
		  </div></td>
        </tr>
	    <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="vendor_id" id="vendor_id" value="<?=$vendor_id?>" />
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update" />
		
		   <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />		  </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
       
        <tr>
          <td colspan="5" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
      </table>
</form>	  

