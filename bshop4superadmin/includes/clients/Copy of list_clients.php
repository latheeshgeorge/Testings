<?php
	/*#################################################################
	# Script Name 	: list_clients.php
	# Description 	: Page for listing Clients 
	# Coded by 		: ANU
	# Created on	: 31-May-2007
	# Modified by	: ANU
	# Modified On	: 31-May-2007
	#################################################################*/

//Define constants for this page
$table_name = 'clients';
$page_type = 'Clients';
$help_msg = 'This section lists the Clients available on the site. Here there is provision for adding a Client, editing, & deleting it.';
$table_headers = array('Slno.','Client name','Company','Sites','More Info','Action');
$colspan = count($table_headers);

#Search terms
$search_fields = array('client_name','client_company');
foreach($search_fields as $v) {
	$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'fname':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('fname' => 'Client name','company' => 'Company');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";
if($_REQUEST['client_name']) {
	$where_conditions .= "AND ( fname LIKE '%".add_slash($_REQUEST['client_name'])."%' OR surname LIKE '%".add_slash($_REQUEST['client_name'])."%')";
}
if($_REQUEST['client_company']) {
	$where_conditions .= " AND company LIKE '%".add_slash($_REQUEST['client_company'])."%'";
}
#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
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
<br />
<table width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr>
		<td colspan="<?=$colspan?>">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td><b><a href="home.php?request=clients">Manage Clients</a> <font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br /><br />
		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?><br /><br />
		</td>
      </tr>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchClients" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Client name like <input type="text" id='client_name' name="client_name" value="<?=$_REQUEST['client_name']?>" />.&nbsp;Company name like <input type="text" id='client_company' name="client_company" value="<?=$_REQUEST['client_company']?>" />.<br />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="clients" />
		</form>
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers);
	$sql_propertytype = "SELECT client_id,fname,surname,company FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
	while($row = $db->fetch_array($res))
	 {
		$count_no++;
		$array_values = array();
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
		$sql_sites = "SELECT site_id,domain FROM sites WHERE client_id=".$row['client_id'];
		$res_sites = $db->query($sql_sites);
		while($row_sites = $db->fetch_array($res_sites)) {
			$array_values[$row_sites['site_id']] = $row_sites['domain'];
		}
		if($db->num_rows($res_sites) == 0) {
			$array_values[0] = 'No sites';
		}	
	?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="center"><?php echo stripslashes($row['fname'])." ".stripslashes($row['surname']); ?></td>
      <td height="20" align="center"><?=stripslashes($row['company'])?></td>
      <td align="center"><?=generateselectbox('sites'.$count_no,$array_values,0)?></td>
      <td width="13%" height="20" align="center"><input type='button' value='Manage sites' onclick="location.href='home.php?request=sites&site_client=<?=$row['client_id']?>';"> </td>
      <td width="11%" align="center"><a href="home.php?request=clients&fpurpose=edit&client_id=<?=$row['client_id']?>&client_name=<?=$client_name?>&client_company=<?=$client_company?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
	  	  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=clients&fpurpose=delete&client_id=<?=$row['client_id']?>&client_name=<?=$client_name?>&client_company=<?=$client_company?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a></td>
    </tr>
    <?
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_propertytype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=clients&fpurpose=add&client_name=<?=$client_name?>&client_company=<?=$client_company?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=clients";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
    </table>