<?php
	/*
	#################################################################
	# Script Name 	: list_paymethod_details.php
	# Description 	: Page for listing details of different payment methods
	# Coded by 		: ANU
	# Created on	: 1-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'payment_methods_details';
$page_type = 'Payment Method Details';
$help_msg = 'This section lists the Payment Method Details available for the selected Payment method on the site. Here there is provision for adding , editing, & deleting a new detail for the site.';
$table_headers = array('Slno.','Title','Key','Is Required','Action');
$header_positions=array('center','left','left','center','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('paymethod_details');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'payment_methods_details_caption':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('payment_methods_details_caption' => 'Title');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

// Get the name of the selected Payment Method
$sql_paymentmethod = "SELECT paymethod_name FROM payment_methods WHERE paymethod_id=".$_REQUEST['pass_paymethod_id'];
$ret_paymentmethod = $db->query($sql_paymentmethod);
if ($db->num_rows($ret_paymentmethod))
{
	$row_paymentmethod 		= $db->fetch_array($ret_paymentmethod);
	$selpaymentmethod	= '"'.stripslashes($row_paymentmethod['paymethod_name']).'"';
}	
//Search Options
$where_conditions = "WHERE payment_methods_paymethod_id= ".$_REQUEST['pass_paymethod_id'];

if($_REQUEST['paymethod_details']) {
	$where_conditions .= " AND payment_methods_details_caption LIKE '%".add_slash($_REQUEST['paymethod_details'])."%'";
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
		<td width="79%" colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd"><strong><a href="home.php?request=payment_methods&pay_method=<?=$_REQUEST['pass_pay_method']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>" title="List Payment Methods">List Payment Methods</a> <font size="1">>></font> List <?=$page_type?> for <?php echo $selpaymentmethod?></strong>
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table></td>
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
		<form name="frmsearchpayMethodDetails" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Payment Method Details name like 
		<input type="text" id='paymethod_details' name="paymethod_details" value="<?=$_REQUEST['paymethod_details']?>" />
		&nbsp;
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="payment_methods" />
		<input type="hidden" name="fpurpose" value="paymethod_details" />
		<input type="hidden" name="pass_paymethod_id" id="pass_paymethod_id" value="<?=$_REQUEST['pass_paymethod_id']?>" />
		<input type="hidden" name="pass_pay_method" id="pass_pay_method" value="<?=$_REQUEST['pass_pay_method']?>" />
		<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_payment_method_details = "SELECT payment_method_details_id,payment_methods_paymethod_id,payment_methods_details_caption,payment_methods_details_key,payment_methods_details_isrequired FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_payment_method_details); 
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
        &nbsp;&nbsp;</td>
      <td   height="20" align="left"><a href="home.php?request=payment_methods&fpurpose=edit_paymethod_details&payment_method_details_id=<?=$row['payment_method_details_id']?>&pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$pass_pay_method?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&amp;paymethod_details=<?=$paymethod_details?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['payment_methods_details_caption']); ?></a></td>
	  <td    align="left" valign="middle"><?php echo stripslashes($row['payment_methods_details_key']); ?></td>
	  <td    height="20" align="center"><?=(stripslashes($row['payment_methods_details_isrequired']))?'yes':'No'; ?></td>
      <td    align="center" valign="middle"><a href="home.php?request=payment_methods&fpurpose=edit_paymethod_details&payment_method_details_id=<?=$row['payment_method_details_id']?>&pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$pass_pay_method?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&amp;paymethod_details=<?=$paymethod_details?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;
      <?php if ($$use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=payment_methods&fpurpose=delete_paymethod_details&payment_method_details_id=<?=$row['payment_method_details_id']?>&pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id']?>&amp;pass_pay_method=<?=$pass_pay_method?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
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
			-- No Details Added for the Payment Method <?php echo $selpaymentmethod?> yet. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_themes" value="Add <?=$page_type?>" onclick="location.href='home.php?request=payment_methods&fpurpose=add_paymethod_details&pass_paymethod_id=<?=$_REQUEST['pass_paymethod_id'];?>&pass_pay_method=<?=$_REQUEST['pass_pay_method'];?>&pass_sort_by=<?=$_REQUEST['pass_sort_by'];?>&pass_sort_order=<?=$_REQUEST['pass_sort_order'];?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page'];?>&pass_pg=<?=$_REQUEST['pass_pg'];?>
&paymethod_details=<?=$paymethod_details?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payment_methods&fpurpose=paymethod_details&pass_paymethod_id=$pass_paymethod_id&pass_pay_method=$pass_pay_method&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_pg=$pass_pg";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
