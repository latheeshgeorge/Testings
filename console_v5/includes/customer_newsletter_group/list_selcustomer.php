<?php
	/*#################################################################
	# Script Name 	: list_selcustomer.php
	# Description 	: Page for Assigning Customer to Groups
	# Coded by 		: SKR
	# Created on	: 17-Aug-2007
	# Modified by	: ANU
	# Modified On	: 
	#################################################################*/
//Define constants for this page
$table_name='customers';
$page_type='Customer';
$help_msg = get_help_messages('LIST_CUST_CUST_GROUP_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistCustomer,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistCustomer,\'checkbox[]\')"/>','Slno.','Customer Name','Email Address','Phone','Bonus points','Discount','On Mailing List','Date Added','Hide');
$header_positions=array('left','left','left','left','left','left','left','left','left','left','left');
$colspan = count($table_headers);
$pass_custgroup_id=($_REQUEST['pass_custgroup_id']?$_REQUEST['pass_custgroup_id']:'0');
//#Search terms
$search_fields = array('customer_fname');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'customer_fname':$_REQUEST['sort_by']; 
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('customer_fname' => 'Customer Name','customer_email_7503' => 'Customer Email');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
//#Avoiding already assigned product
$sql_assigned="SELECT customer_id FROM customer_newsletter_group_customers_map WHERE  custgroup_id=".$pass_custgroup_id;
$ret_assigned = $db->query($sql_assigned);
$str_assigned='-1';
while($row_assigned = $db->fetch_array($ret_assigned))
{
	$sql_news_customers = "SELECT customer_id FROM newsletter_customers WHERE sites_site_id=$ecom_siteid AND news_customer_id=".$row_assigned['customer_id'];
	$ret_news_customers =$db->query($sql_news_customers);
	$row_news_customers = $db->fetch_array($ret_news_customers);
	$str_assigned.=','.$row_news_customers['customer_id'];
	
}
$str_assigned='('.$str_assigned.')';	
$where_conditions.=" AND customer_id NOT IN $str_assigned";

if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( customer_fname LIKE '%".add_slash($_REQUEST['search_name'])."%')";
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
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=cust_group&fpurpose=add_customer&records_per_page=$records_per_page&search_name=".$_REQUEST['search_name']."&start=$start&pass_custgroup_id=$pass_custgroup_id";
?>
<script>
function call_save_selected(cname,sortby,sortorder,recs,start,pg,group_id) 
{
	
	var atleastone 			= 0;
	for(i=0;i<document.frmlistCustomer.elements.length;i++)
	{
		if (document.frmlistCustomer.elements[i].type =='checkbox' && document.frmlistCustomer.elements[i].name=='checkbox[]')
		{

			if (document.frmlistCustomer.elements[i].checked==true)
			{
				atleastone ++;
				
			}	
		}
	}
	if (atleastone==0)
	{
		alert('Please select the customer  to assign');
	}
	
	else
	{
		if(confirm('Are you sure you want to assign selected Customers ?'))
		{
			show_processing();
			document.frmlistCustomer.fpurpose.value='save_add_customer';
			document.frmlistCustomer.submit();
		}	
	}	

}
</script>
<form name="frmlistCustomer" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="add_customer" />
<input type="hidden" name="request" value="cust_group" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_custgroup_id" value="<?=$pass_custgroup_id?>" />
<input type="hidden" name="pass_searchname" value="<?=$_REQUEST['pass_searchname']?>" />
<input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sortby']?>" />
<input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sortorder']?>" />
<input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
   <tr>
          <td colspan="7" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_group">List Customer Newsletter Groups</a> <a href="home.php?request=cust_group&fpurpose=edit&checkbox[]=<?=$pass_custgroup_id?>">Edit Customer Newsletter Group</a><span> Assign Customer</span></div></td>
    </tr><? /*&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg'] */?>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="7">
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
          			<td colspan="7" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?>
	<?php
	  if($numcount)
	  {
	 ?> 
    <tr><td colspan="7" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" class="sorttd" colspan="7" >
	  <div class="sorttd_div">
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">
        <tr>
          <td width="7%" height="30" align="left" valign="middle">Customer Name </td>
          <td width="12%" height="30" align="left" valign="middle"><input name="search_name" type="text" class="textfeild" id="search_name" value="<?=$_REQUEST['search_name']?>"  /></td>
          <td width="7%" height="30" align="left" valign="middle">Records Page </td>
          <td width="6%" height="30" align="left" valign="middle"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
          <td width="5%" height="30" align="left" valign="middle">Sort By</td>
          <td width="21%" height="30" align="left" valign="middle"><?=$sort_option_txt?>&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp; <?=$sort_by_txt?></td>
          <td width="23%" height="30" align="left" valign="middle"><input name="button5" type="submit" class="red" id="button5" value="Go" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CUST_GROUP_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="45%" height="30" align="left" valign="middle">&nbsp;</td>
        </tr>
      </table>
      </div></td>
    </tr>
     
    <tr>
      <td colspan="7" class="listingarea">
	  <div class="listingarea_div">
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="listingtable">  
    <tr>
	   <td class="listeditd" align="right" valign="middle" colspan="<?=$colspan?>">
	 <input name="change_hide" type="button" class="red" id="change_hide" onClick="call_save_selected()" value="Assign Selected">
   	   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_CUST_CUST_GROUP_ASS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   
	   $sql_user = "SELECT customer_id,customer_fname,customer_mname,customer_surname,customer_email_7503,customer_phone,customer_bonus,customer_discount,customer_addedon,customer_hide FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $start,$records_per_page ";
	   
	   $res = $db->query($sql_user);
	   $srno = 1; 
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
          <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><input name="checkbox[]" value="<? echo $row['customer_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="2%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?request=customer&fpurpose=edit&checkbox[0]=<?php echo $row['customer_id']?>&search_name=<?php echo $_REQUEST['search_name']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="<? echo $row['customer_fname']?>" class="edittextlink" onclick="show_processing()"><? echo $row['customer_fname']?>&nbsp;<? echo $row['customer_mname']?>&nbsp;<? echo $row['customer_surname']?></a></td>
          
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_email_7503']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_phone']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_bonus']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_discount']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>">
		  <?
		  $sql_list="SELECT cg.custgroup_name FROM customer_newsletter_group cg,customer_newsletter_group_customers_map cm WHERE cg.sites_site_id=".$ecom_siteid." AND cm.customer_id=".$row['customer_id']."  AND cg.custgroup_id=cm.custgroup_id ";
		  $ret_list=$db->query($sql_list);
		  if($db->num_rows($ret_list)>0)
		  {
		  ?>
		  <select name="mail_list">
		  <?
		  while($row_list=$db->fetch_array($ret_list))
		  {
		  ?>
		  <option value="<?=$row_list['custgroup_name']?>"><?=$row_list['custgroup_name']?></option>
		  <?
		  }
		  ?>
		  </select>
		  <?
		  }
		  else
		  {
		  	echo "Not Assigned";
		  }
		  ?>		  </td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo $row['customer_addedon']; ?></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"><?php echo ($row['customer_hide'] == 1)?'Yes':'No'; ?></td>
        </tr>
      <?
	  }
	  }
	  else
	  {
	  ?>
	  <tr>  <td align="center" valign="middle" class="norecordredtext" colspan="<?=$colspan?>" >No Customer exists.  </td>  </tr>
		<?
		}
		?>
		
      </table>
	  </div></td>
    </tr><tr>
	   <td colspan="<?=$colspan?>"  align="right" valign="middle" class="listeditd">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>      </td>
    </tr>
    </table>
</form>
