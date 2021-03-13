<?php
	/*#################################################################
	# Script Name 	: edit_corporation_departments.php
	# Description 	: Page for adding  Corporation Departments
	# Coded by 		: ANU
	# Created on	: 17-Aug-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer Corporation';
$help_msg = get_help_messages('EDIT_DEP_CUST_CORP_MESS1');
$corporation_id = $_REQUEST['corporation_id'];
$department_id = $_REQUEST['department_id'];
$sql_corporation = "SELECT corporation_name FROM customers_corporation WHERE corporation_id = ".$corporation_id;
$ret_coporation = $db->query($sql_corporation);
$corporation = $db->fetch_array($ret_coporation);
$sql="SELECT department_name,department_building,department_street,department_town,country_id,state_id,department_postcode,department_phone,department_fax,department_hide FROM customers_corporation_department WHERE sites_site_id=$ecom_siteid AND department_id=".$department_id;
$res=$db->query($sql);
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
	tab_arr 									= new Array('main_tab_td','customer_tab_td');
	var atleastone 						= 0;
	var dep_id								= '<?php echo $department_id?>';
	var fpurpose							= '';
	var retdivid								= '';
		var curtab								= '<?php echo $curtab?>';
		var search_name								='<?php echo $_REQUEST['pass_search_name']?>';
	var sortby								= '<?php echo $_REQUEST['pass_sort_by']?>';
	var sortorder							= '<?php echo $_REQUEST['pass_sort_order']?>';
	var recs									= '<?php echo $_REQUEST['pass_records_per_page']?>';
	var start								= '<?php echo $_REQUEST['pass_start']?>';
	var pg									= '<?php echo $_REQUEST['pass_pg']?>';
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
		case 'departmentmain_info':
			fpurpose 	= 'show_department_maniinfo';
		break;
		case 'departments': // Case of showing subcategories
			fpurpose	= 'list_customersInDepartment_ajax';
		break;
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&cur_departmentid='+dep_id);	
}

function valform(frm)
{
	fieldRequired = Array('department_name');
	fieldDescription = Array('Depratment Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	var state_ids=document.frmEditCorporationDepratment.customer_statecounty.value;

		if(state_ids==-1)
		{
			 if(document.frmEditCorporationDepratment.other_state.value=='')
			 {
			 	alert("Enter Other state name");
				document.frmEditCorporationDepratment.other_state.focus();
				 return false;
			 }
		}
		else
		{
		    show_processing();
			return true;
		}	
	} else {
		return false;
	}
}
function call_ajax_deleteall(mod,checkboxname,recs)
{
	var atleastone 			= 0;
	var department_id		= '<?php echo $department_id?>';
	var del_ids 			= '';
	var qrystr				= '';
	var atleastmsg 			= '';
	var confirmmsg 			= '';
	var retdivid			= '';
	var fpurpose			= '';
	
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmEditCorporationDepratment.elements.length;i++)
	{
		if (document.frmEditCorporationDepratment.elements[i].type =='checkbox' && document.frmEditCorporationDepratment.elements[i].name==checkboxname)
		{

			if (document.frmEditCorporationDepratment.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmEditCorporationDepratment.elements[i].value;
			}	
		}
	}
	switch(mod)
	{
		
		case 'customers': // Case of customers
			atleastmsg 	= 'Please select the Customers to be Unassign';
			confirmmsg 	= 'Are you sure you want to Unassign the selected Customers?'
			fpurpose	= 'delete_customers_ajax';
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
			//alert('fpurpose='+fpurpose+'&cur_department_id='+department_id+'&del_ids='+del_ids+'&'+qrystr+'&recs='+recs);
			Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&cur_department_id='+department_id+'&del_ids='+del_ids+'&'+qrystr+'&recs='+recs);
		}	
	}	
}
///////////////////////////////// show list with paging////////////////// ANU
function call_ajax_showlistallWithPaging(mod,no_ofpages,curntpage,recs)
{  
	var atleastone 										= 0;
	var department_id									= '<?php echo $department_id?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	//var moredivid										= '';
	var recs											=10;
	switch(mod)
	{
	
		case 'customers': // Case of Departments
			fpurpose	= 'list_customersInDepartment_ajax';
		break;
		
	}
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 							= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	/* Calling the ajax function */
	Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&cur_departmentid='+department_id+'&no_ofpages='+no_ofpages+'&curntpage='+curntpage+'&recs='+recs);
}
// change state
function changestate(country_ids,state_id)
{
	var retdivid='state_tr';
	var fpurpose;
		if(country_ids==0)
	{
		country_ids=document.frmEditCorporationDepratment.country_id.value;
	}
	if(country_ids!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/customer_corporation.php','fpurpose='+fpurpose+'&'+qrystr+'&country_id='+country_ids+'&state_id='+state_id);
	}
	else
	{
		
		document.getElementById('state_tr').style.display='none';
	}
}
//If other state
function state_other()
{
	var stte_ids=document.frmEditCorporationDepratment.customer_statecounty.value;
	if(stte_ids==-1)
	{
		document.getElementById('state_other_tr').style.display='';
	}
	else
	{
		document.getElementById('state_other_tr').style.display='none';

	}
	
}


///////////////////// ANU //// with paging
</script>
<form name='frmEditCorporationDepratment' action='home.php?request=customer_corporation'   method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_corporation&amp;sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;search_name=<?=$_REQUEST['pass_search_name']?>&amp;pass_start=<?=$_REQUEST['pass_start']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>">List Business Customers </a> <a href="home.php?request=customer_corporation&amp;fpurpose=edit&corporation_id=<?=$corporation_id?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;search_name=<?=$_REQUEST['pass_search_name']?>&amp;start=<?=$_REQUEST['pass_start']?>&amp;pg=<?=$_REQUEST['pass_pg']?>&curtab=department_tab_td">Edit Business Customer:</a><span> Edit Departments under: <b>"<?=$corporation['corporation_name']?>"</b></span></div></td>
    </tr>
    <tr>
	  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
	<tr>
			<td colspan="2" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
				<tr>
						<td  align="left" onClick="handle_tabs('main_tab_td','departmentmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
						<td  align="left" onClick="handle_tabs('customer_tab_td','departments')" class="<?php if($curtab=='customer_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="customer_tab_td"><span>Customer under this department</span> </td>
						<td width="90%" align="left">&nbsp;</td>
				</tr>
				</table>			</td>
		</tr>
		<tr>
	<td colspan="2">
	<div id='master_div'>
	<?php
	if ($curtab=='main_tab_td')
		{
			show_department_maniinfo($department_id,$alert);
		}
		if ($curtab=='customer_tab_td')
		{
			show_customers_list($department_id,$alert,'',10);
		}
	?>
	</div>	</td>
	</tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="tdcolorgray" colspan="2"> 
          <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
          <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
          <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
          <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
          <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
          <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
          <input type="hidden" name="fpurpose" id="fpurpose" value="update_department" />
		   <input type="hidden" name="corporation_id" id="corporation_id" value="<?=$_REQUEST['corporation_id']?>" />
		    <input type="hidden" name="department_id" id="department_id" value="<?=$_REQUEST['department_id']?>" />
          <input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
         </td>
     
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
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
handletype_change('');
 <?php /*?>changestate(<?=$row['country_id']?>,'<?=$row['state_id']?>');<?php */?>
</script>
