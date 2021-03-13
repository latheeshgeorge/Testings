<?php
	/*
	#################################################################
	# Script Name 	: list_consolelevels.php
	# Description 	: Page for listing Console Levels 
	# Coded by 		: Sny
	# Created on	: 01-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'console_levels';
$page_type = 'Console Levels';
$help_msg = 'This section lists the Console levels available on the site. Here there is provision for adding a console level, editing, & deleting it.';
$table_headers = array('Slno.','Console Level name','Price','Duration (months)','Usage Count','Action');
$header_positions=array('center','left','center','center','center','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('level_name','level_price','level_duration');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'level_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('level_name' => 'Level name','level_price'=>'Level Price','level_duration'=>'Level Duration');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['theme_name']) {
	$where_conditions .= " AND level_name LIKE '%".add_slash($_REQUEST['levelname'])."%'";
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
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
			<td class="menutabletoptd">&nbsp;<b>Manage Console Levels<font size="1">>></font> 
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
		<form name="frmsearchThemes" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Console Level  name like 
		<input type="text" id='levelname' name="levelname" value="<?=$_REQUEST['levelname']?>" />
		&nbsp;
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="levels" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT level_id,level_name,level_price,level_duration 
								FROM $table_name 
									$where_conditions 
										ORDER BY $sort_by $sort_order 
											LIMIT $startrec, $records_per_page ";
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
		// Find the count of site which uses this theme
		$sql_sites 		= "SELECT count(site_id) as cnt FROM sites WHERE console_levels_level_id=".$row['level_id'];
		$ret_sites 		= $db->query($sql_sites);
		list($use_cnt) 	= $db->fetch_array($ret_sites);
	?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=levels&fpurpose=edit&level_id=<?=$row['level_id']?>&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['level_name']); ?></a></td>
	   <td height="20" align="center"><?php echo $row['level_price']; ?></td>
	    <td height="20" align="center"><?php echo $row['level_duration']; ?></td>
	    <td height="20" align="center"><?php echo $use_cnt; ?></td>
      <td width="16%" align="center" valign="middle"><a href="home.php?request=levels&fpurpose=edit&level_id=<?=$row['level_id']?>&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>
      <?php if ($$use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=levels&fpurpose=delete&level_id=<?=$row['level_id']?>&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
    </tr>
    <?
	}
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Console Levels added yet. -- <br /><br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_consolelevels" value="Add <?=$page_type?>" onclick="location.href='home.php?request=levels&fpurpose=add&levelname=<?=$levelname?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=levels";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
