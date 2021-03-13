<?php
/*
#################################################################
# Script Name 	: llist_setupgroups.php
# Description 		: Listing for groups to be used in setup wizard
# Coded by 		: Sny
# Created on		: 10-Jul-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
#Define constants for this page
$table_name 			= 'setup_layout';
$page_type 			= 'Setup Wizard layout Name';
$help_msg 				= 'This section lists the Groups for setup wizard layout names';
$table_headers 		= array('Slno.','Layout Code','Layout Name');
$header_positions	= array('center','left','center');
$colspan					= count($table_headers);

#Search terms
$search_fields = array('layout_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by 				= (!$_REQUEST['sort_by'])?'layout_name':$_REQUEST['sort_by'];
$sort_order 			= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 			= array('layout_name' => 'Layout Name');
$sort_option_txt 		= 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt 		.= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['layout_name'])
{
	$where_conditions .= " AND layout_name LIKE '%".add_slash($_REQUEST['layout_name'])."%'";
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
			<td class="menutabletoptd"><a href="home.php?request=themes"><b>List Themes</b></a><b><font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br />		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>		</td>
      </tr>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchThemes" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Title like 
		<input name="src_title" type="text" id='src_title' value="<?=$_REQUEST['src_title']?>" size="35" />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="setup_groups" />
		<input type="hidden" name="theme_id" value="<?php echo $_REQUEST['theme_id']?>" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT id,layout_id,layout_name,themes_theme_id 
										FROM 
											$table_name 
										$where_conditions 
										ORDER BY 
											$sort_by $sort_order 
										LIMIT 
											$startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
	if(mysql_num_rows($res))
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
			  <td height="20" align="center"><?=$count_no?>&nbsp;&nbsp;&nbsp;</td>
				<td height="20" align="left"><?php echo stripslashes($row['layout_id']); ?></td>	
			  <td height="20" align="center"><a href="home.php?request=setup_groups&fpurpose=edit_layout&id=<?=$row['id']?>&layout_name=<?=$_REQUEST['layout_name']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['country_name'];?>"><?php echo stripslashes($row['layout_name']); ?></a></td>
			  <td width="11%" align="center"><a href="home.php?request=setup_groups&fpurpose=edit_layout&id=<?=$row['id']?>&layout_name=<?=$_REQUEST['layout_name']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
				  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=setup_groups&fpurpose=delete_layout&id=<?=$row['id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a>				  </td>
			</tr>
    <?
		}
	}
	else {
	?>
	<tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Setup Layout Names added yet. -- <br /><br />		</td>
		</tr>
	<?
	}
	
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_setupgroup" value="Add <?=$page_type?>" onclick="location.href='home.php?request=setup_groups&theme_id=<?php echo $_REQUEST['theme_id']?>&fpurpose=add_layout&src_title=<?=$_REQUEST['src_title']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=setup_groups&layout_name=".$_REQUEST['layout_name'].'&theme_id='.$_REQUEST['theme_id'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
