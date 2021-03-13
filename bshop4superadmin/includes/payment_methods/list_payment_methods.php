<?php
	/*
	#################################################################
	# Script Name 	: list_payment_methods.php
	# Description 	: Page for listing Payment Methods
	# Coded by 		: ANU
	# Created on	: 1-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'payment_methods';
$page_type = 'Payment Methods';
$help_msg = 'This section lists the Payment Methods available on the site. Here there is provision for adding a Payment Methods, editing, & deleting it.';
$table_headers = array('Slno.','Payment Methods','Take Card Details?','Show in Voucher?','Show in Setupwizard?','SSL Required?','Show in Mobile?','Hidden','Action');
$header_positions=array('center','left','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('pay_method');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by	 		= (!$_REQUEST['sort_by'])?'paymethod_name':$_REQUEST['sort_by'];
$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 		= array('paymethod_name' => 'Payment Method name');
$sort_option_txt 	= 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt 	.= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['pay_method']) {
	$where_conditions .= " AND paymethod_name LIKE '%".add_slash($_REQUEST['pay_method'])."%'";
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = is_numeric($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//Starting record.
$pages = ceil($numcount / $records_per_page);//Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b>Manage Payment Methods<font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br /></td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
      </tr>
	  <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td colspan="<?=$colspan?>" align="center" class="error_msg"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchPaymentMethods" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;Payment Methods  name like 
		<input type="text" id='pay_method' name="pay_method" value="<?=$_REQUEST['pay_method']?>" /><br />
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="payment_methods" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT * FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
	if (mysql_num_rows($res))
	{
	while($row = $db->fetch_array($res))
	 {
		$count_no++;
		$array_values = array();
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
		
			
	?>
	<tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=payment_methods&fpurpose=edit&paymethod_id=<?=$row['paymethod_id']?>&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['paymethod_name']); ?></a></td>
      <td width="10%" align="center"><?php echo ($row['paymethod_takecarddetails'])?'Y':'N'?></td>
      <td width="10%" align="center"><?php echo ($row['paymethod_showinvoucher'])?'Y':'N'?></td>
	   <td width="10%" align="center"><?php echo ($row['paymethod_showinsetup'])?'Y':'N'?></td>
      <td width="10%" align="center"><?php echo ($row['paymethod_secured_req'])?'Y':'N'?></td>
        <td width="10%" align="center"><?php echo ($row['paymethod_showinmobile'])?'Y':'N'?></td>
      <td width="5%" align="center"><?php echo ($row['payment_hide'])?'Y':'N'?></td>
      <td width="12%" align="center" valign="middle"><a href="home.php?request=payment_methods&fpurpose=edit&paymethod_id=<?=$row['paymethod_id']?>&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;<a href="home.php?request=payment_methods&amp;fpurpose=paymethod_details&amp;pass_paymethod_id=<?=$row['paymethod_id']?>&amp;pass_pay_method=<?=$pay_method?>&amp;pass_sort_by=<?=$sort_by?>&amp;pass_sort_order=<?=$sort_order?>&amp;pass_records_per_page=<?=$records_per_page?>&amp;pass_pg=<?=$pg?>" title="View Details"><img src="images/layout.gif" border="0" alt="View Details" /></a>
      <?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=payment_methods&fpurpose=delete&paymethod_id=<?=$row['paymethod_id']?>&paymethod_name=<?=$_REQUEST['paymethod_name']?>&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
    </tr>
    <?
	}
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />
			-- No Payment Methods found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_payment_methods" value="Add <?=$page_type?>" onclick="location.href='home.php?request=payment_methods&fpurpose=add&pay_method=<?=$pay_method?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payment_methods";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
