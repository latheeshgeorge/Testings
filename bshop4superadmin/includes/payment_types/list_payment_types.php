<?php
	/*
	#################################################################
	# Script Name 	: list_payment_types.php
	# Description 	: Page for listing payment types
	# Coded by 		: LSH
	# Created on	: 06-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
//Define constants for this page
$table_name = 'payment_types';
$page_type = 'Payment Types';
$help_msg = 'This section lists the Payment Types available on the site. Here there is provision for adding a Payment Types, editing, & deleting it.';
$table_headers = array('Slno.','Payment Types','order','Action');
$header_positions=array('center','left','center','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('pay_types');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'paytype_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('paytype_name' => 'Payment Type name','paytype_order' => 'Ordering');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";
//Search Options
if($_REQUEST['pay_types']) {
	$where_conditions .= " AND paytype_name LIKE '%".add_slash($_REQUEST['pay_types'])."%'";
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
			<td class="menutabletoptd">&nbsp;<b>Manage payment types<font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
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
		<form name="frmsearchPaymenttypes" type="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;payment types  name like 
		<input type="text" id='pay_types' name="pay_types" value="<?=$_REQUEST['pay_types']?>" /><br />
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="payment_types" />
		</form>		</td>
      </tr>
	  <form name="frmordertypes" type="get" action="home.php">
	   <tr class="maininnertabletd1">
 <td height="20" colspan="<?=$colspan?>" align="right"><input type="Submit" name="Save" value="Save" class="input-button"/>
		<input type="hidden" name="fpurpose" value="save_orders"/>
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="payment_types" />
				</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT paytype_id,paytype_name,paytype_order FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
      <td height="20" align="left"><a href="home.php?request=payment_types&fpurpose=edit&paytype_id=<?=$row['paytype_id']?>&pay_type=<?=$_REQUEST['pay_type']?>&paytype_name=<?=$_REQUEST['paytype_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['paytype_name']); ?></a></td>
	  	   <td height="20" align="center"><input type="text" value="<?php echo stripslashes($row['paytype_order']); ?>" name="paytype_order[<?=$row['paytype_id']?>]" id="paytype_order[<?=$row['paytype_id']?>]" size="3" /></td>
      <td width="16%" align="center" valign="middle"><a href="home.php?request=payment_types&fpurpose=edit&paytype_id=<?=$row['paytype_id']?>&pay_type=<?=$_REQUEST['pay_type']?>&paytype_name=<?=$_REQUEST['paytype_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;
      <?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=payment_types&fpurpose=delete&paytype_id=<?=$row['paytype_id']?>&paytype_name=<?=$_REQUEST['paytype_name']?>&pay_type=<?=$_REQUEST['pay_type']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
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
			-- No payment types found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_payment_types" value="Add <?=$page_type?>" onclick="location.href='home.php?request=payment_types&fpurpose=add&pay_type=<?=$pay_type?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payment_types";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
