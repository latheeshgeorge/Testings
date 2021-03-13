<?php
	/*#################################################################
	# Script Name 	: list_department_customers.php
	# Description 	: Page for listing Customers not asigned in any of the departments
	# Coded by 		: ANU
	# Created on	: 19-Aug-2007
	# Modified by	: ANU
	# Modified On	: 19-Aug-2007
	#################################################################*/
//Define constants for this page
$table_name='customers';
$page_type='Customers';
$help_msg = get_help_messages('LIST_CUST_CUST_CORP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCustomerCorporation,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCustomerCorporation,\'checkbox[]\')"/>','Slno.','Customer Name','CustomerEmail','Hide?','Is Activated');
$header_positions=array('left','left','left','left','left','left');
$colspan = count($table_headers);
$corporation_id = $_REQUEST['corporation_id'];
$department_id = $_REQUEST['department_id'];
$sql_corp_name = "SELECT corporation_name FROM customers_corporation WHERE corporation_id = ".$corporation_id;
$ret_corp_name = $db->query($sql_corp_name);
$res_corp_name = $db->fetch_array($ret_corp_name);
$corporation_name = $res_corp_name['corporation_name'];
$sql_dept_name = "SELECT department_name FROM customers_corporation_department WHERE department_id = ".$department_id;
$ret_dept_name = $db->query($sql_dept_name);
$res_dept_name = $db->fetch_array($ret_dept_name);
$department_name = $res_dept_name['department_name'];
//#Search terms
$search_fields = array('search_name','search_email','corporation_id','department_id');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}

//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'customer_fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('customer_fname' => 'Customers');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid AND customers_corporation_department_department_id = 0";
if($_REQUEST['search_name']) {
	$where_conditions .= " AND  (customer_fname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR 
	customer_surname LIKE '%".add_slash($_REQUEST['search_name'])."%' OR
	concat(customer_fname,' ',customer_surname) LIKE '%".add_slash($_REQUEST['search_name'])."%' )";
}
if($_REQUEST['search_email']) {
	$where_conditions .= " AND ( customer_email_7503 LIKE '%".add_slash($_REQUEST['search_email'])."%'
	) ";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=customer_corporation&fpurpose=add_customers&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&pass_search_name=".$_REQUEST['pass_search_name']."&pass_sort_order=".$_REQUEST['pass_sort_order']."&pass_sort_by=".$_REQUEST['pass_sort_by']."&pass_records_per_page=".$_REQUEST['pass_records_per_page']."&pass_pg=".$_REQUEST['pass_pg']."&pass_start=".$_REQUEST['pass_start'];
?>
<script language="javascript">
function assigncustomers()
{   
	var atleastone 			= 0;
	var curid				= 0;
	var customer_ids		= '';
	var cat_orders			= '';
	var department_name     = '<?=$department_name?>';
	for(i=0;i<document.frmlistCustomerCorporation.elements.length;i++)
	{
		if (document.frmlistCustomerCorporation.elements[i].type =='checkbox' && document.frmlistCustomerCorporation.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomerCorporation.elements[i].checked==true)
			{
			
				atleastone = 1;
				if (customer_ids!='')
					customer_ids += '~';
				 customer_ids += document.frmlistCustomerCorporation.elements[i].value;
			}	
		}
	}
	if (atleastone==0) 
	{
		alert('Please select the Customers to assign to the Department:'+department_name);
		return false;
	}
	else
	{
		if(confirm('Assign Customers to Department:'+department_name))
		{
				show_processing();
				//alert('fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				//Handlewith_Ajax('services/static_group.php','fpurpose=assign_pages&'+qrystr+'&page_ids='+page_ids);
				document.frmlistCustomerCorporation.customer_ids.value=customer_ids;
				document.frmlistCustomerCorporation.fpurpose.value='assign_customers';
				document.frmlistCustomerCorporation.submit();
		}	
	}	
}
function checkSelected()
{
	len=document.frmlistCustomerCorporation.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistCustomerCorporation.elements[j]
		if (el!=null && el.name== "checkbox[]" )
		   if(el.checked) {
		   		cnt++;
				user_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one corporation ');
		return false;
	}
	show_processing();
	return true;
}
</script>
<form name="frmlistCustomerCorporation" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="add_customers" />
<input type="hidden" name="request" value="customer_corporation" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="department_id" value="<?=$_REQUEST['department_id']?>" />
<input type="hidden" name="corporation_id" value="<?=$_REQUEST['corporation_id']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
<input type="hidden" name="customer_ids" value="" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input  type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>"  />
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td  colspan="<?=$colspan?>" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_corporation&amp;sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;search_name=<?=$_REQUEST['pass_search_name']?>&amp;start=<?=$_REQUEST['pass_start']?>&amp;pg=<?=$_REQUEST['pass_pg']?>">List Business Customers </a><span>List Departments under :<b>" <?=$corporation_name?> "</b></span> <a href="home.php?request=customer_corporation&fpurpose=edit_department&amp;pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&amp;pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&amp;pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&amp;pass_search_name=<?=$_REQUEST['pass_search_name']?>&amp;pass_start=<?=$_REQUEST['pass_start']?>&amp;pass_pg=<?=$_REQUEST['pass_pg']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&department_id=<?=$_REQUEST['department_id']?>&curtab=customer_tab_td">Edit this Department</a><span>Add Customers to the Department : <b>" <?=$department_name?> "</b></span></div></td>
    </tr>
	<tr>
	  <td colspan="<?=$colspan?>" align="left" valign="middle" class="helpmsgtd_main">
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
          			<td colspan="<?=$colspan?>" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
	  if($numcount)
	  {
	    
		 ?> 
    <tr>
		<td colspan="<?=$colspan?>" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="<?=$colspan?>" >
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableleft">
        <tr>
          <td width="22%" height="30" align="left" valign="middle">Customer  Name </td>
          <td height="30" colspan="3" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          </tr>
        <tr>
          <td height="30" align="left" valign="middle">Customer Email </td>
          <td height="30" colspan="3" align="left" valign="middle"><input name="search_email" type="text" class="textfeild" id="search_email" value="<?=$_REQUEST['search_email']?>"></td>
        </tr>
      </table>
	 
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttableright">
        <tr>
          <td width="21%" height="30" align="left" valign="middle">Records Per Page </td>
          <td width="32%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="47%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="middle">Sort By</td>
          <td height="30" align="left" valign="middle" nowrap="nowrap"><?=$sort_option_txt?> in <?=$sort_by_txt?>  </td>
          <td height="30" align="left" valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="button5" type="submit" class="red" id="button5" value="Go" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CUST_CORP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
      </table></div>	  </td>
    </tr>
    
     
    <tr>
      <td colspan="<?=$colspan?>" class="listingarea">
	  <div class="listingarea_div" >
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
	  <tr>
	   <td align="right" valign="middle" colspan="<?=$colspan?>" class="listeditd">
	  <?
	  if($numcount)
	  {
	  ?>
           <input name="change_hide" type="button" class="red" id="change_hide" value="Assign Customers" onClick="assigncustomers()" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CUST_CORP_ASSCUST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
	  <?
		}
		?>      </td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $sql_group = "SELECT customer_id,customer_title,customer_fname,customer_mname,customer_surname,customer_title,customer_surname,customer_email_7503,
	   				 customer_compname,customer_hide,customer_activated 
	   							FROM $table_name 
									$where_conditions 
										ORDER BY $sort_by $sort_order 
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
        <tr >
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['customer_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[0]=<?php echo $row['customer_id']?>&pass_search_name=<?php echo $_REQUEST['search_name']?>&pass_start=<?php echo $start?>&pass_pg=<?php echo $pg?>&pass_records_per_page=<?php echo $records_per_page?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>" title="<? echo $row['corporation_name']?>" class="edittextlink" onclick="show_processing()"><? echo $row['customer_title']." ".$row['customer_fname']." ".$row['customer_surname'];?></a></td>
		  <td align="left" class="<?php echo $class_val?>"><?php echo $row['customer_email_7503'] ?></td>
		<td align="left" class="<?php echo $class_val?>"><?php echo ($row['customer_hide'])?'Yes':'No'?></td>
		<td align="left" class="<?php echo $class_val?>"><?php echo ($row['customer_activated'])?'Yes':'No'?></td>
		 </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >
				  	No Unassigned Customers exists.				  </td>
			</tr>
		<?
		}
		?>	
		<tr>
	   <td class="listeditd"  align="right" valign="middle" colspan="<?=$colspan?>" >
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
  </tr>
      </table>
	  </div></td>
    </tr>
  
  </table>
</form>
