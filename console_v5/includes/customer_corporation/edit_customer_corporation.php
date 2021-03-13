<?php
	/*#################################################################
	# Script Name 	: edit_customer_corporation.php
	# Description 	: Page for editing Customer Corporation
	# Coded by 		: ANU
	# Created on	: 15-Aug-2007
	# Modified by	: ANU
	# Modified On	: 15-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Customer Corporation';
$help_msg = get_help_messages('EDIT_CUST_CORP_MESS1');
$corporation_id=($_REQUEST['corporation_id']?$_REQUEST['corporation_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT corporation_name,corporation_type,corporation_regno,corporation_vatno,corporation_otherdetails,
			 corporation_admin_id,corporation_billing_id,corporation_discount_method,corporation_discount,
			 corporation_allow_product_discount,corporation_costplus 
			 		FROM customers_corporation 
							WHERE sites_site_id=$ecom_siteid AND corporation_id=".$corporation_id;
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row=$db->fetch_array($res);
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
?>	
<script language="javascript" type="text/javascript">

function ajax_return_contents() 
{
	var ret_val='';
	var disp = 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			//norecdiv 	= document.getElementById('retdiv_more').value;
			
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			document.getElementById('retdiv_id').value= 'master_div';
		}	
	}	
}
function handle_tabs(id,mod)
{
	tab_arr 									= new Array('main_tab_td','department_tab_td');
	var atleastone 						= 0;
	var corp_id								= '<?php echo $corporation_id?>';
	var fpurpose							= '';
	var retdivid								= '';
		var curtab								= '<?php echo $curtab?>';
		var search_name								='<?php echo $_REQUEST['search_name']?>';
	var sortby								= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['sort_order']?>';
	var recs									= '<?php echo $_REQUEST['records_per_page']?>';
	var start								= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var qrystr									= 'search_name='+search_name+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;

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
		case 'customermain_info':
			fpurpose 	= 'list_business_customer_maininfo';
		break;
		case 'departments': // Case of showing subcategories
			fpurpose	= 'list_departmentsInCorporation_ajax';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&cur_corporationid='+corp_id+'&'+qrystr);	
}
function call_ajax_changestatuspagesall(mod,checkboxname)
{ 
	var atleastone 			= 0;
	var corporation_id			= '<?php echo $corporation_id?>';
	var ch_ids 				= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';

	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomerCorporation.elements.length;i++)
	{
	
	if (document.frmEditCustomerCorporation.elements[i].type =='checkbox' && document.frmEditCustomerCorporation.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomerCorporation.elements[i].checked==true)
			{
				atleastone = 1;
				if (ch_ids!='')
					ch_ids += '~';
				 ch_ids += document.frmEditCustomerCorporation.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'department': // Case of department
			atleastmsg 	= 'Please select the Department to change the status';
			confirmmsg 	= 'Are you sure you want to Change the Status of selected Departments?';
			fpurpose	= 'changestat_department_ajax';
			var chstat	= document.getElementById('department_chstatus').value;
		break;
		
	}
	if (atleastone==0)
	{
		alert(atleastmsg);
	}
	else
	{
		if(confirm(confirmmsg))
		{	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
		Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&chstat='+chstat+'&cur_corporation_id='+corporation_id+'&ch_ids='+ch_ids);
		}	
	}	
}	


function call_ajax_deleteall(mod,checkboxname)
{
	var atleastone 			= 0;
	var corporation_id			= '<?php echo $corporation_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCustomerCorporation.elements.length;i++)
	{
		if (document.frmEditCustomerCorporation.elements[i].type =='checkbox' && document.frmEditCustomerCorporation.elements[i].name==checkboxname)
		{

			if (document.frmEditCustomerCorporation.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCustomerCorporation.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'department': // Case of product messages
			atleastmsg 	= 'Please select the Departments to be Delete';
			confirmmsg 	= 'Are you sure you want to Delete the selected Departments?'
			fpurpose	= 'delete_department_ajax';
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
			retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");			retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';
			Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&cur_corporation_id='+corporation_id+'&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function valform(frm)
{
	fieldRequired = Array('corporation_name');
	fieldDescription = Array('Business Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('corporation_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	   <?php /*?> if(frm.corporation_discount.value>99) {
			alert("Discount Value Should be less than 100%");
			return false;
		} 
		else if(frm.corporation_costplus.value>99) {
			alert("Corporation Costplus Value Should be less than 100%");
			return false;
		}<?php */?>
		show_processing();
		return true;
	} else {
		return false;
	}
}

</script>
<form name='frmEditCustomerCorporation' action='home.php?request=customer_corporation' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_corporation&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Business Customers </a><span> Edit Business Customer</span></div></td>
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
						<td  align="left" onClick="handle_tabs('main_tab_td','customermain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('department_tab_td','departments')" class="<?php if($curtab=='department_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="department_tab_td"><span>Departments under this Business customer</span></td>
						<td  align="left" width="90%">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
		<tr>
	<td colspan="4">
	<div id='master_div'>
	<?php
	if ($curtab=='main_tab_td')
		{
			show_customer_maniinfo($corporation_id,$alert);
		}
		if ($curtab=='department_tab_td')
		{
			show_department_list($corporation_id,$alert);
		}
	?>
	</div>	</td>
	</tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="right" valign="middle" class="tdcolorgray">
		   
		  <input type="hidden" name="corporation_id" id="corporation_id" value="<?=$corporation_id?>" />
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="update_corporation" />
		  <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
		   <input name="corporation_discount" type="hidden" id="corporation_discount"  value="0"/>
		   <input type="hidden" name='corporation_discount_method' id='corporation_discount_method' value='Discount' />
		   <input class="input" type="hidden" name="corporation_allow_product_discount"  value="1" />
		  </td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
      </table>
</form>	 
<script type="text/javascript">
 
 function handletype_change(vals)
{	
	//if (vals=='')
	
	switch(vals)
	{
		case 'Discount':
			document.getElementById('tr_disc').style.display = '';
			document.getElementById('tr_cost').style.display = 'none';
			
		break;
		case 'Cost Plus':
			document.getElementById('tr_disc').style.display = 'none';
			document.getElementById('tr_cost').style.display = '';
		
		break;
		
	};
}
	<?php /*handletype_change('<?=$row['corporation_discount_method']?>');*/?>
</script>
