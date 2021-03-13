<?php
	#################################################################
	# Script Name 	: list_services.php
	# Description 	: Page for listing Services 
	# Coded by 		: SG
	# Created on	: 31-May-2007
	# Modified by	: SKR
	# Modified On	: 11-June-2007
	#################################################################

#Define constants for this page
$table_name = 'services';
$page_type = 'services';
$help_msg = 'This section lists the services available on the site. Here there is provision for adding a service, editing, & deleting it.';
$table_headers = array('Slno.','Service Name','Ordering','Hidden','Action');
$header_positions=array('center','left','center');
$colspan = count($table_headers);

#Search terms
$search_fields = array('servicename');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'service_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('service_name' => 'Service name','ordering' => 'Ordering');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['servicename']) {
	$where_conditions .= " AND service_name LIKE '%".add_slash($_REQUEST['servicename'])."%'";
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

?>
<script language="javascript">
function show_hierarchy(serviceid)
{
	hi=window.open('includes/services/list_hierachy.php?serviceid=' + serviceid,'hierarchy','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=420,height=600');
	hi.focus();
}
</script>
<center><?=$alert_del?></center>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr >
    <td colspan="<?=$colspan?>" valign="top"><table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
      <tr >
        <td class="menutabletoptd"><b>Manage
          <?=$page_type?>
                <font size="1">>></font> List
          <?=$page_type?>
          </b><br />
          <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
      </tr>
    </table>
        <br />
    </td>
  </tr>
  <tr>
    <td colspan="<?=$colspan?>" class="maininnertabletd3"><?=$help_msg?>
        <br />
      <br />
    </td>
  </tr>
  <!-- Search Section Starts here -->
  <tr class="maininnertabletd1">
    <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><form name="frmsearchThemes" method="get" action="home.php">
      Show
      <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/>
            <?=$page_type?>
      per page. &nbsp;&nbsp;&nbsp;Service name like
      <input type="text" id='servicename' name="servicename" value="<?=$_REQUEST['servicename']?>" />
      .<br />
      <?=$sort_option_txt?>
      &nbsp;
      <input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
      <br />
      <br />
      <input type="hidden" name="pg" value="1" />
      <input type="hidden" name="request" value="services" />
    </form></td>
  </tr>
  <form name="frmSaveservices" method="get" action="home.php">
  <tr  class="<?=$class_val;?>">
    <td height="20" colspan="<?=$colspan?>" align="right"><input type="Submit" name="Save" value="Save" class="input-button"/>
	<input type="hidden" name="request" value="services" />
	<input type="hidden" name="fpurpose" value="save_services" />
	<input type="hidden" name="pg" value="<?=$pg?>" />
	<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
	<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
	<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />
	</td>
  </tr>
  <!-- Search Section Ends here -->
  <?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT service_id,service_name,hide,ordering 
							FROM $table_name 
								$where_conditions 
									ORDER BY $sort_by $sort_order 
										LIMIT $startrec, $records_per_page ";
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
    <td height="20" align="left"><a href="home.php?request=services&fpurpose=edit&service_id=<?=$row['service_id']?>&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['service_name']); ?></a></td>
    <td align="center"><input type="text" size="2" name="ordering[<?=$row['service_id']?>]" id="ordering[<?=$row['service_id']?>]" value="<?=$row['ordering']?>" /></td>
    <td align="center"><select name="hide[<?=$row['service_id']?>]" id="hide[<?=$row['service_id']?>]">
      <option value="1" <?=(stripslashes($row['hide'])==1)?'selected':''; ?>>YES</option>
      <option value="0" <?=(stripslashes($row['hide'])==0)?'selected':''; ?>>NO</option>
    </select></td>
    <td width="11%" align="center"><a href="javascript:show_hierarchy('<? echo $row['service_id']?>')" onclick="" title="View Hierarchy"><img src="images/consolemenu.gif" border="0" alt="View Hierarchy" /></a> | <a href="home.php?request=services&fpurpose=edit&service_id=<?=$row['service_id']?>&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=services&fpurpose=delete&service_id=<?=$row['service_id']?>&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a> </td>
  </tr>
  <?
	}
	?></form>
  <tr class="maininnertabletd1">
    <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_propertytype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=services&fpurpose=add&servicename=<?=$_REQUEST['servicename']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=services";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
</table>
