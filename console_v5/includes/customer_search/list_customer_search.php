<?php
	/*#################################################################
	# Script Name 	: list_customer_search.php
	# Description 	: Page for searching customers
	# Coded by 		: ANU
	# Created on	: 21-Aug-2007
	# Modified by	: ANU
	# Modified On	: 21-Aug-2007
	#################################################################*/
//Define constants for this page
if(($_REQUEST['corporation_id'] && !is_numeric($_REQUEST['corporation_id'])) || ($_REQUEST['department_id'] && !is_numeric($_REQUEST['department_id'])) ){
redirect_illegal();
exit;
}
$table_name='customers';
$page_type='Search Customers';
$help_msg = get_help_messages('LIST_CUSTOMERS_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistSearchCustomers,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistSearchCustomers,\'checkbox[]\')"/>','Slno','Customer Name','Customer Email','Company','Bonus','Disc(%)','Mailing List','Added On','Hide','Action');
$header_positions=array('left','left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('search_compname','search_name','search_email','corporation_id','cbo_dept','sort_by','sort_order','customer_payonaccount_status');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'TRIM(customer_fname)':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('customer_fname' => 'Customer Name','customer_email_7503' => 'Customer Email');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['search_name']) {
	$where_conditions .= " AND ( customer_fname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR 
	customer_surname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR
        customer_mname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR
	concat(customer_fname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['search_name'])."%' OR 
	   concat(customer_fname,' ',customer_mname) LIKE '%".add_slash($_REQUEST['search_name'])."%' OR 
	   concat(customer_mname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['search_name'])."%' OR
        concat(customer_fname,' ',customer_mname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['search_name'])."%' 
	) ";
}
if($_REQUEST['search_compname']) {
	$where_conditions .= " AND ( customer_compname LIKE '%".add_slash($_REQUEST['search_compname'])."%') ";
}
if($_REQUEST['search_email']) {
	$where_conditions .= " AND ( customer_email_7503 LIKE '%".add_slash($_REQUEST['search_email'])."%'
	) ";
}
if($_REQUEST['corporation_id'] && !$_REQUEST['department_id']) {
	
	$sql_departments = "SELECT department_id FROM customers_corporation_department WHERE customers_corporation_corporation_id = ".$_REQUEST['corporation_id']." AND sites_site_id = ".$ecom_siteid;
	$ret_departments = $db->query($sql_departments);
	$department_ids = '';
	while($department_id = $db->fetch_array($ret_departments)){
	$department_ids.=$department_id['department_id'].",";
	}
	$department_ids;
	$pos = strrpos($department_ids, ",");
	$department_ids = substr($department_ids,0,$pos);
	if($department_ids!='')
	$where_conditions .= " AND ( customers_corporation_department_department_id IN (".$department_ids.")) ";
	
}
if($_REQUEST['cbo_dept']) {
	$where_conditions .= " AND customers_corporation_department_department_id = ".$_REQUEST['cbo_dept']."";
}
if($_REQUEST['customer_payonaccount_status'])
{
	if($_REQUEST['customer_payonaccount_status']!='All' && $_REQUEST['customer_payonaccount_status']!='PENDING_PAYMENT')
	{
			$where_conditions .= " AND customer_payonaccount_status = '".$_REQUEST['customer_payonaccount_status']."'";
	
	}
	if($_REQUEST['customer_payonaccount_status']=='PENDING_PAYMENT')
	{ 
	     $where_conditions .= " AND customer_payonaccount_usedlimit > 0";
	}
}
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "request=customer_search&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&start=$start";
//Get all list of corporation.
    $sql_list_corporation ="SELECT corporation_id,corporation_name FROM customers_corporation WHERE sites_site_id=".$ecom_siteid;
    $ret_list_corporation = $db->query($sql_list_corporation);
	$CorporationArray[0]="--All--";
		while(list($id,$CorporationList) = $db->fetch_array($ret_list_corporation)) {
			$CorporationArray[$id]=$CorporationList;
			//Get the list of dpts under this corporaton
			$sql_dept ="SELECT department_id,department_name FROM customers_corporation_department WHERE sites_site_id=".$ecom_siteid." AND customers_corporation_corporation_id=".$id;
			$ret_dept = $db->query($sql_dept);
			$deptehold_arr = array();
			if ($db->num_rows($ret_dept))
				{
					while ($row_dept = $db->fetch_array($ret_dept))
					{	
						$department_id					= $row_dept['department_id'];
						$department_name					= stripslashes($row_dept['department_name']);
						$deptehold_arr[$department_id] 	= $department_name;
					}
					$corpdept_arr[$id] = $deptehold_arr;
				}
			else
			$corpdept_arr[$id] = $deptehold_arr;
		}
	if (count($corpdept_arr))
	{
	//Building the javascript array for dept to be shown based on the selected corporation
	echo "<script>";
		foreach ($corpdept_arr as $k=>$v)
		{
			$arrvalname = 'corpval'.$k;
			$arrkeyname	= 'corpkey'.$k;
			echo "var $arrkeyname = new Array();var $arrvalname = new Array();";
			$ii = 0;
			
			if(count($v)){
				foreach ($v as $kk=>$vv)
			{
				echo "
					$arrkeyname"."[$ii] ='".$kk."';
					$arrvalname"."[$ii]  ='".$vv."';
					";
				$ii++;
			}	
			}
		}
	echo "</script>";
	}
?>
<script language="javascript">
function call_ajax_changestatus(search_name,search_compname,search_email,sortby,sortorder,recs,start,pg,corp_id,dep_id,status)
{ 

	var atleastone 			= 0;
	var curid				= 0;
	var customer_ids 			= '';
	var cat_orders			= '';
	var ch_status			= document.frmlistSearchCustomers.cbo_changehide.value;
	var qrystr				= 'search_name='+search_name+'&search_compname='+search_compname+'&search_email='+search_email+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&ch_status='+ch_status+'&start='+start+'&pg='+pg+'&corporation_id='+corp_id+'&cbo_dept='+dep_id+'&customer_payonaccount_status='+status;
	/* check whether any checkbox is ticked */
	
	
	for(i=0;i<document.frmlistSearchCustomers.elements.length;i++)
	{
		if (document.frmlistSearchCustomers.elements[i].type =='checkbox' && document.frmlistSearchCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSearchCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (customer_ids!='')
					customer_ids += '~';
				 customer_ids += document.frmlistSearchCustomers.elements[i].value;
				 
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the customers to change the hide status');
	}
	else
	{
		if(confirm('Change Hide Status of Seleted Customer(s)?'))
		{
				show_processing();
				Handlewith_Ajax('services/customer_search.php','fpurpose=change_hide&'+qrystr+'&customer_ids='+customer_ids);
		}	
	}
	
	//document.getElementById('retdiv_id').value 		= retdivid;	
}
function showdepartment(corporation_id)
{

	arrval = eval('corpval'+corporation_id);
	arrkey = eval('corpkey'+corporation_id);
	
	for(i=document.frmlistSearchCustomers.cbo_dept.options.length-1;i>0;i--)
	{
		 document.frmlistSearchCustomers.cbo_dept.remove(i);
	}
	
	for(i=0;i<arrkey.length;i++)
	{
		var lgth = document.frmlistSearchCustomers.cbo_dept.options.length;
		document.frmlistSearchCustomers.cbo_dept.options[lgth]= new Option(arrval[i],arrkey[i]);
	}
}
function checkSelected()
{
	len=document.frmlistSearchCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSearchCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Customer ');
		return false;
	}
	show_processing();
	return true;
}
function edit_selected()
{
	
	len=document.frmlistSearchCustomers.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistSearchCustomers.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one Customer ');
	}
	else if(cnt>1 ){
		alert('Please select only one Customer to edit');
	}
	else
	{
		show_processing();
		document.frmlistSearchCustomers.fpurpose.value='edit';
		document.frmlistSearchCustomers.submit();
	}
	
	
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			document.getElementById('maincontent').innerHTML=ret_val;
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function call_ajax_delete(search_name,search_compname,search_email,corp_id,customer_payonaccount,dept_id,sortby,sortorder,recs,start,pg)
{
	var atleastone 	= 0;
	var del_ids 	= '';
	var qrystr				= 'search_name='+search_name+'&search_compname='+search_compname+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&search_email='+search_email+'&corporation_id='+corp_id+'&customer_payonaccount_status='+customer_payonaccount+'&cbo_dept='+dept_id;
	/* check whether any checkbox is ticked */
	for(i=0;i<document.frmlistSearchCustomers.elements.length;i++)
	{
		if (document.frmlistSearchCustomers.elements[i].type =='checkbox' && document.frmlistSearchCustomers.elements[i].name=='checkbox[]')
		{

			if (document.frmlistSearchCustomers.elements[i].checked==true)
			{
				atleastone = 1;
				if (del_ids!='')
					del_ids += '~';
				 del_ids += document.frmlistSearchCustomers.elements[i].value;
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select customer to delete');
	}
	else
	{
		if(confirm('Are you sure you want to delete selected customer?'))
		{
			show_processing();
			Handlewith_Ajax('services/customer_search.php','fpurpose=delete&del_ids='+del_ids+'&'+qrystr);
		}	
	}	
}
function handle_export_customers()
{

	var exp_opt = document.frmlistSearchCustomers.cbo_export_customer.value;
	if (exp_opt =='')
	{
		alert('Please select the export option');
		return false;	
	}
	if (exp_opt=='sel_cust') // case of selected order, check whether any orders ticked 
	{
		var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frmlistSearchCustomers.elements.length;i++)
		{
			if (document.frmlistSearchCustomers.elements[i].type =='checkbox')
			{
				if (document.frmlistSearchCustomers.elements[i].name=='checkbox[]')
				{
					if (document.frmlistSearchCustomers.elements[i].checked==true)
					{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frmlistSearchCustomers.elements[i].value;
					}
				}	
			}	
		}
		if (atleast_one==false)
		{
			alert('Please select the customer(s) to export');
			return false;
		}
		/* Write the logic to submit the details to customer export section here*/
		document.frmlistSearchCustomers.request.value 	= 'import_export';
		document.frmlistSearchCustomers.export_what.value 	= 'cust';
		document.frmlistSearchCustomers.fpurpose.value 	= '';
		document.frmlistSearchCustomers.ids.value 	=ids;
		document.frmlistSearchCustomers.submit();
		
		
	}
	else
	{
	// var atleast_one = false;
		var ids ='';
		for(i=0;i<document.frmlistSearchCustomers.elements.length;i++)
		{
			if (document.frmlistSearchCustomers.elements[i].type =='checkbox')
			{
				if (document.frmlistSearchCustomers.elements[i].name=='checkbox[]')
				{
						atleast_one = true;
						if (ids!='')
							ids += '~';
				 		ids += document.frmlistSearchCustomers.elements[i].value;
				}	
			}	
		}
		/* Write the logic to submit the details to order export section here*/
		document.frmlistSearchCustomers.request.value 	= 'import_export';
		document.frmlistSearchCustomers.export_what.value 	= 'cust';
		document.frmlistSearchCustomers.fpurpose.value 	= '';
		document.frmlistSearchCustomers.ids.value 	=ids;
		document.frmlistSearchCustomers.submit();
	   
	}
}
</script>
<form name="frmlistSearchCustomers" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="customer_search" />
<input type="hidden" name="pass_start" value="<?=$start?>" />
<input type="hidden" name="pass_pg" value="<?=$pg?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input  type="hidden" name="pass_search_compname" id="pass_search_compname" value="<?=$_REQUEST['search_compname']?>"  />
<input  type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>"  />
<input  type="hidden" name="pass_search_email" id="pass_search_email" value="<?=$_REQUEST['search_email']?>"  />
<input type="hidden" name="customer_payonaccount_status" id="customer_payonaccount_status" value="<?=$_REQUEST['customer_payonaccount_status']?>" />
<input type="hidden" name="export_what" value="" />
<input type="hidden" name="ids" value="" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span>List Customers</span></div></td>
    </tr>
	<tr>
	  <td colspan="3" align="left" valign="middle" class="helpmsgtd_main">
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
          			<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
	<?
	if($numcount)
	  {
	  ?>
	<tr>
	<td colspan="3" class="sorttd" align="right">
	<?
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  ?>
	</td>
	</tr>
	<?  }?>
    <tr>
      <td height="48" colspan="3" class="sorttd">
		<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="100%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="5%" height="30" align="left"> Name</td>
              <td width="12%" height="30" align="left"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?php echo $_REQUEST['search_name']?>" size="20" /></td>
              <td width="12%" height="30" align="left">E-mail </td>
              <td width="16%" height="30" align="left"><input name="search_email" type="text" class="textfeild" id="search_email" value="<?php echo $_REQUEST['search_email']?>" size="20" /></td>
              <td width="9%" height="30" align="left">Company Name </td>
              <td width="10%" height="30" align="left"><input name="search_compname" type="text" class="textfeild" id="search_compname" value="<?php echo $_REQUEST['search_compname']?>" size="20" /></td>
              <td colspan="2" align="left">In Business
                <?php
	
	$on_change ='showdepartment(this.value)';
		echo generateselectbox('corporation_id',$CorporationArray,$_REQUEST['corporation_id'],'',$on_change);
			  ?></td>
              </tr>
            <tr>
              <td height="30" colspan="2" align="left"><?php 
			  $DepartmentArray = array('-- All --');
			 if($_REQUEST['corporation_id']){
						
							$sql_deptmnt = "SELECT * FROM customers_corporation_department WHERE  sites_site_id=$ecom_siteid AND customers_corporation_corporation_id=".$_REQUEST['corporation_id'];
							$ret_deptmnt = $db->query($sql_deptmnt);
							if ($db->num_rows($ret_deptmnt))
							{
								while($row_deptmnt = $db->fetch_array($ret_deptmnt))
								{
									$dpt_id = $row_deptmnt['department_id'];
									$dpt_na = stripslashes($row_deptmnt['department_name']);
									$DepartmentArray[$dpt_id]=$dpt_na;
								}
							}
							}
						//$DepartmentArray[0] = '-- All -- ';
			  
		echo "In Department&nbsp;&nbsp;&nbsp;&nbsp;";
		?>                <?
		echo generateselectbox('cbo_dept',$DepartmentArray,$_REQUEST['cbo_dept'],'','');
	?></td>
              <td height="30"  align="left" id="department_list" ><span style="white-space:nowrap">Pay on Account Status</span></td>
		<td height="30"  align="left" id="department_list" ><select name="select" class="dropdown" id="select" >
          <option value="All">Show All Status</option>
          <option value="NO" <? if($_REQUEST['customer_payonaccount_status']=='NO') echo "selected";?>>Not Requested</option>
          <option value="ACTIVE" <? if($_REQUEST['customer_payonaccount_status']=='ACTIVE') echo "selected";?>>Active</option>
          <option value="INACTIVE" <? if($_REQUEST['customer_payonaccount_status']=='INACTIVE') echo "selected";?>>Inactive</option>
          <option value="REQUESTED" <? if($_REQUEST['customer_payonaccount_status']=='REQUESTED') echo "selected";?>>Requested</option>
          <option value="REJECTED" <? if($_REQUEST['customer_payonaccount_status']=='REJECTED') echo "selected";?>>Rejected</option>
          <option value="PENDING_PAYMENT" <? if($_REQUEST['customer_payonaccount_status']=='PENDING_PAYMENT') echo "selected";?>>Payment Pending</option>
        </select></td>
              <td height="30"  align="left" id="department_list" ><span style="white-space:nowrap">Records Per Page</span></td>
              <td height="30" colspan="2"  align="left" id="department_list" ><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3" value="<?php echo $records_per_page?>" />                <span style="white-space:nowrap">Sort By&nbsp;</span><?php echo $sort_option_txt;?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;<?php echo $sort_by_txt?></td>
              <td width="8%" height="30"  align="right" id="department_list" ><input name="Search_go" type="submit" class="red" id="Search_go" value="Go" onclick="document.frmlistSearchCustomers.search_click.value=1" />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUSTOMERS_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table>            </td>
          </tr>
      </table>
	  </div>
      </td>
    </tr>
     
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>

	   <td class="listeditd" colspan="6"><a href="home.php?request=customer_search&fpurpose=add&search_name=<?php echo $_REQUEST['search_name']?>&search_compname=<?php echo $_REQUEST['search_compname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_email=<?php echo $_REQUEST['search_email']?>&corporation_id=<?= $_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" class="addlist" onclick="show_processing()">Add</a> 
	  <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['search_compname']?>','<? echo $_REQUEST['search_email']?>','<? echo $_REQUEST['corporation_id']?>','<?=$_REQUEST['customer_payonaccount_status']?>','<? echo $_REQUEST['cbo_dept']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a><a href="home.php?request=customer_search&fpurpose=settingstomany&search_name=<?=$_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="settingslist">Multiple Settings</a><?php if($ecom_siteid==104){?><a href="home.php?request=listquotes" style="color:black;text-decoration:none"  class="settingslist">View Quotes</a><?php } ?> 
	  <?
	  }
	  ?>
	  </td>
	  <td class="listeditd" colspan="5" align="right">
	   <?
	  if($numcount)
	  {
	  ?>
        
		Change Status
          <select name="cbo_changehide" class="dropdown" id="cbo_changehide">
            <option value="1">Yes</option>
			<option value="0">No</option>
          </select>&nbsp;<input name="change_hide" type="button" class="red" id="change_hide" value="Change" onclick="call_ajax_changestatus('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['search_compname']?>','<?php echo $_REQUEST['search_email']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>','<?=$_REQUEST['corporation_id']?>','<?=$_REQUEST['cbo_dept']?>','<?=$_REQUEST['customer_payonaccount_status']?>')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUSTOMERS_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
		<?
		}
		?>
	  </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	$sql_group = "SELECT customer_id,customer_title,customer_fname,customer_mname,
						customer_surname,customer_email_7503,customer_compname,customer_hide,customer_phone,customer_bonus,customer_discount,
						date_format(customer_addedon,'%d-%b-%Y') as added_date,
						customers_corporation_department_department_id,customer_in_mailing_list 
					FROM 
						$table_name 
						$where_conditions 
					ORDER BY 
						$sort_by $sort_order 
					LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_group);
	    $srno = getStartOfPageno($records_per_page,$pg); 
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	   
	   ?> 
		<tr>
			<td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['customer_id']?>" type="checkbox"></td>
			<td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
			<td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<?php echo $row['customer_id']?>&pass_search_compname=<?php echo $_REQUEST['search_compname']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_search_name=<?php echo $_REQUEST['search_name']?>&pass_search_email=<?php echo $_REQUEST['search_email']?>&corporation_id=<?= $_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" title="Edit Customer" class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['customer_title'])." ".stripslashes($row['customer_fname']).' '.stripslashes($row['customer_mname']).' '.stripslashes($row['customer_surname'])?></a></td>
		  	<td align="left" valign="middle" class="<?=$class_val;?>"><?=mask_emails($row['customer_email_7503'])?></td>
			<td align="center" valign="middle" class="<?=$class_val;?>"><? if ($row['customer_compname']){ echo $row['customer_compname']; } else{ echo"-";}?></td>   
		  <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_bonus']; ?></td>
		  <td align="center" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_discount']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?
		  	/*$news_cust_id = 0;
		  	$sql = "SELECT news_customer_id FROM newsletter_customers WHERE customer_id=".$row['customer_id'];  
			$rest = $db->query($sql);
			if($db->num_rows($rest))
			{
				$rows = $db->fetch_array($rest);
				$news_cust_id = $rows['news_customer_id'];
			}	
			
		  	$sql_list="SELECT cg.custgroup_name FROM customer_newsletter_group cg,customer_newsletter_group_customers_map cm WHERE cg.sites_site_id=".$ecom_siteid." AND cm.customer_id=".$news_cust_id."  AND cg.custgroup_id=cm.custgroup_id ";
		  	$ret_list=$db->query($sql_list);
		  if($db->num_rows($ret_list)>0)*/
		  if($row['customer_in_mailing_list']==1)
		  {
		  	 echo "Yes";
		  }
		  else
		  {
		  	echo "Not Assigned";
		  }
		  ?>
		 
		  </td>
		  <td align="left" valign="middle" class="<?=$class_val;?>" width="9%"><?php echo $row['added_date']; ?></td>    
        	<td align="center" valign="middle" class="<?=$class_val;?>"><?=($row['customer_hide'])?'Yes':'No'?></td>  
			<td align="center" valign="middle" class="<?=$class_val;?>" >
			<?php
				if ($row['customers_corporation_department_department_id'])
				{
					// Get the corporation id and department id
					$sql_depts = "SELECT department_name,customers_corporation_corporation_id FROM customers_corporation_department WHERE 
					department_id=".$row['customers_corporation_department_department_id']." AND sites_site_id = $ecom_siteid";
					$ret_depts = $db->query($sql_depts);
					if ($db->num_rows($ret_depts))
					{
						
						$row_depts = $db->fetch_array($ret_depts);
					?>
					<a href="home.php?request=customer_corporation&fpurpose=edit&checkbox[0]=<?php echo $row_depts['customers_corporation_corporation_id']?>" title="Go to Corporation Page"><img src="images/corporation.gif" alt="Corporation" title="Corporation" border="0" /></a><a href="home.php?request=customer_corporation&fpurpose=edit_department&corporation_id=<?php echo $row_depts['customers_corporation_corporation_id']?>&department_id=<?php echo $row['customers_corporation_department_department_id']?>" title="Go to Department Page"><img src="images/department.gif" alt="Department" title="Department" border="0" /></a>
					<?php	
					}
				}
				else{
				echo "-";
				}
			?>
			</td>  
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="11" >
				  	No Customers  exists.				  </td>
			</tr>
		<?
		}
		?>	
		<tr>

	   <td class="listeditd" colspan="6"><a href="home.php?request=customer_search&fpurpose=add&search_name=<?php echo $_REQUEST['search_name']?>&search_compname=<?php echo $_REQUEST['search_compname']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&search_email=<?php echo $_REQUEST['search_email']?>&corporation_id=<?= $_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" class="addlist" onclick="show_processing()">Add</a>  
	  <?
	  if($numcount)
	  {
	  ?>
	  	  <a href="#" onclick="edit_selected()" class="editlist">Edit</a> <a href="#" onclick="call_ajax_delete('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['search_compname']?>','<? echo $_REQUEST['search_email']?>','<? echo $_REQUEST['corporation_id']?>','<?=$_REQUEST['customer_payonaccount_status']?>','<? echo $_REQUEST['cbo_dept']?>','<?php echo $sort_by?>','<?php echo $sort_order?>','<?php echo $records_per_page?>','<?php echo $start?>','<?php echo $pg?>')" class="deletelist">Delete</a><a href="home.php?request=customer_search&fpurpose=settingstomany&search_name=<?=$_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" class="settingslist">Multiple Settings</a>
	  <?
	  }
	  ?>
	  </td>
	  <td class="listeditd" colspan="5" align="right">
	 </td>
    </tr>
	
      </table>
	  </div></td>
    </tr>
	<tr>

	  <td class="listing_bottom_paging" colspan="2" align="right">
	  <?
	  if($numcount)
	  { paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); }
	  ?></td>
    </tr>
  
	<?
	  if($numcount)
	  {
	   if(is_module_valid('mod_importexport','onconsole'))
	 	{
	?>
	<?php /*?><tr>
      <td colspan="3" class="sorttd">
	 <div class="editarea_div">
	 <table width="100%">
	<tr>
	 <td width="12%" align="left">	Export Customer(s) </td>
	 <td width="88%" align="left"><select name="cbo_export_customer" id="cbo_export_customer">
	 			<option value="">-- Select --</option>
	 			<option value="sel_cust">Export Selected Customers</option>
	 			<option value="all_cust">Export All Customers</option>
	 		</select>
	 		&nbsp;
	 		<input type="button" name="submit_custexport" id="submit_custexport" value="Export Now" class="red" onclick="handle_export_customers()" /></td>
	 </tr>
	 </table>
	 </div>
	 </td>
	 </tr><?php */?>
	 <?
	   }
	 }
	  ?>
    </table>
</form>

