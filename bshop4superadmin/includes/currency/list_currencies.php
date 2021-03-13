<?php
/*
	#################################################################
	# Script Name 	: list_currencies.php
	# Description 	: Page for listing Currencies 
	# Coded by 		: SG
	# Created on	: 31-May-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
*/
#Define constants for this page
$table_name = 'common_currency';
$page_type = 'Currencies';
$help_msg = 'This section lists the Currencies available on the site. Here there is provision for adding a Currency, editing, & deleting it.';
$table_headers = array('Slno.','Currency name','Currency Sign','Currency Code','Currency Numeric Code','Action');
$header_positions=array('center','left','left','left','left','center');
$colspan = count($table_headers);

#Search terms
$search_fields = array('currency_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'curr_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('curr_name' => 'Currency name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['currency_name']) {
	$where_conditions .= " AND curr_name LIKE '%".add_slash($_REQUEST['currency_name'])."%'";
}
#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////

?><center><?=$alert_del?></center>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd"><b>Manage Currencies <font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />
		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?><br />
		</td>
      </tr>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchThemes" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Currency name like <input type="text" id='currency_name' name="currency_name" value="<?=$_REQUEST['currency_name']?>" />.<br />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="currency" />
		</form>
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT currency_id,curr_name,curr_sign_char,curr_code,curr_numeric_code FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
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
      <td height="20" align="left"><a href="home.php?request=currency&fpurpose=edit&currency_id=<?=$row['currency_id']?>&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['curr_name'];?>"><?php echo stripslashes($row['curr_name']); ?></a></td>
	  <td height="20" align="left"><?php echo stripslashes($row['curr_sign_char']); ?></td>
	  <td height="20" align="left"><?php echo stripslashes($row['curr_code']); ?></td>
	  <td height="20" align="left"><?php echo stripslashes($row['curr_numeric_code']); ?></td>

      <td width="11%" align="center"><a href="home.php?request=currency&fpurpose=edit&currency_id=<?=$row['currency_id']?>&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
	  	  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=currency&fpurpose=delete&currency_id=<?=$row['currency_id']?>&currency_name=<?=$_REQUEST['currency_name']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a>  
	  	  </td>
    </tr>
    <?
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_propertytype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=currency&fpurpose=add&currency_name=<?=$currency_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=currency";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>