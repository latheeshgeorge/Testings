<?php
	#################################################################
	# Script Name 	: list_clients.php
	# Description 	: Page for listing Clients 
	# Coded by 		: SG
	# Created on	: 27-Jul-2006
	# Modified by	: SG
	# Modified On	: 28-Jul-2006
	#################################################################

#Define constants for this page
$table_name = 'features';
$page_type = 'features';
$help_msg = 'This section lists the features available on the site. Here there is provision for adding a feature, editing, & deleting it.';
$table_headers = array('Slno.','Feature name','Parent','Service Name','Ordering','Hidden','Action');
$header_positions=array('center','left','center','left','center','center','center');

$colspan = count($table_headers);

#Search terms
$search_fields = array('featuretitle');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'feature_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('feature_name' => 'Feature name','feature_ordering' => 'Ordering');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['featuretitle']) {
	$where_conditions .= " AND feature_name LIKE '%".add_slash($_REQUEST['featuretitle'])."%'";
}
if($_REQUEST['servicetitle']) {
	$where_conditions .= " AND services_service_id=".$_REQUEST['servicetitle'];
}
if($_REQUEST['mobile_support_only']) {
	$where_conditions .= " AND feature_showinmobilecomponentposition =".$_REQUEST['mobile_support_only'];
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
function show_featutres(featureid)
{
	hi=window.open('includes/features/parent_features.php?featureid=' + featureid,'hierarchy','top=0, left=0, menubar=0, resizable=0, scrollbars=1, toolbar=0,width=420,height=300');
	hi.focus();
}
</script>
<center><?=$alert_del?></center>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd"><b>Manage Features <font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?><br />		</td>
      </tr>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchThemes" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Feature name like <input type="text" id='featuretitle' name="featuretitle" value="<?=$_REQUEST['featuretitle']?>" />&nbsp;&nbsp;&nbsp;Service Name <select name="servicetitle">
		<option value="">--All--</option>
		<?
		$sql_service_name="SELECT service_id,service_name FROM services ORDER BY service_name";
		$res_service_name = $db->query($sql_service_name); 
		while($row_service_name=$db->fetch_array($res_service_name))
		{
			
		?>
		<option value="<?=$row_service_name['service_id']?>" <? if($_REQUEST['servicetitle']==$row_service_name['service_id']) echo "selected";?>><?=$row_service_name['service_name']?></option>
		<?	
		}
		
		?>
		
		</select>
		<input type="checkbox" name="mobile_support_only" id="mobile_support_only" value="1" <?php echo ($_REQUEST['mobile_support_only']==1)?'checked':''?>/>Show only Mobile View Supported Features
		.<br />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="features" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	  <form name="frmSavefeatures" method="get" action="home.php">
    <tr  class="<?=$class_val;?>">
      <td height="20" colspan="<?=$colspan?>" align="right"><input type="Submit" name="Save" value="Save" class="input-button"/>
	<input type="hidden" name="request" value="features" />
	<input type="hidden" name="fpurpose" value="save_features" />
	<input type="hidden" name="pg" value="<?=$pg?>" />
	<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
	<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
	<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" /></td>
      </tr>
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT feature_id,feature_name,services_service_id,feature_hide,feature_ordering,parent_id FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
	if(mysql_num_rows($res))
	{
	$tmp=0;
	while($row = $db->fetch_array($res))
	 {
		$count_no++;
		$tmp++;
		$array_values = array();
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
		
		$sql_service = "SELECT service_name FROM services WHERE service_id=".$row['services_service_id'];
		$res_service = $db->query($sql_service);
		list($row_service) = $db->fetch_array($res_service);
	?>
	 
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=features&fpurpose=edit&feature_id=<?=$row['feature_id']?>&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&servicetitle=<?=$_REQUEST['servicetitle']?>" title="<?php echo stripslashes($row['feature_name']); ?>" ><?php echo stripslashes($row['feature_name']); ?></a></td>
	  <td height="20" align="left">
	  <?
	  if($row['parent_id']<>0)
	  {
	  ?>
	  <a href="javascript:show_featutres('<? echo $row['feature_id']?>')" onclick="" title="View Hierarchy"><img src="images/consolemenu.gif" border="0" alt="View Parent Features" /></a>
	  <?
	  }
	  ?>
	 </td>
	  <td height="20" align="left"><?php echo stripslashes($row_service); ?></td>
      <td  align="left"><input type="text" size="2" name="ordering[<?=$row['feature_id']?>]" id="ordering[<?=$row['feature_id']?>]" value="<?=$row['feature_ordering']?>" /></td>
      <td  align="center"><select name="hide[<?=$row['feature_id']?>]" id="hide[<?=$row['feature_id']?>]">
      <option value="1" <?=(stripslashes($row['feature_hide'])==1)?'selected':''; ?>>YES</option>
      <option value="0" <?=(stripslashes($row['feature_hide'])==0)?'selected':''; ?>>NO</option>
    </select></td>
      <td  align="center"><a href="home.php?request=features&fpurpose=edit&feature_id=<?=$row['feature_id']?>&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&servicetitle=<?=$_REQUEST['servicetitle']?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>   
	  	  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=features&fpurpose=delete&feature_id=<?=$row['feature_id']?>&featuretitle=<?=$_REQUEST['featuretitle']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&servicetitle=<?=$_REQUEST['servicetitle']?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a>	  	  </td>
    </tr>
    <?
	/*if($tmp>=1)
	 exit;*/
	}
	}
	else
	{
	?>
	 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Features added yet. -- <br /><br />		</td>
		</tr>
	<?
	}
	

	?></form>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_propertytype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=features&fpurpose=add&featuretitle=<?=$featuretitle?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&servicetitle=<?=$_REQUEST['servicetitle']?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=features&servicetitle=".$_REQUEST['servicetitle'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
