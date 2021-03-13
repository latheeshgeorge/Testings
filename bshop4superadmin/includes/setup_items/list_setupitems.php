<?php
/*
#################################################################
# Script Name 	: llist_setupitems.php
# Description 		: Listing for items to be used in setup wizard
# Coded by 		: Sny
# Created on		: 10-Jul-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
//#Define constants for this page
$table_name 			= 'setup_items';
$page_type 			= 'Setup Wizard Items';
$help_msg 				= 'This section lists the Items for setup wizard Group ';
$table_headers 		= array('Slno.','Title','Order','Layout Code','Action'); //'Forcolor','BGCOLOR', 'center','center',
$header_positions	= array('center','left','center','center','center');
$colspan					= count($table_headers);

//#Search terms
$search_fields 		= array('src_title','src_layout');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by 				= (!$_REQUEST['sort_by'])?'item_order':$_REQUEST['sort_by'];
$sort_order 			= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 			= array('item_title' => 'Item Title','item_order' =>'Item Order','layout_code' =>'Layout Code');
$sort_option_txt 		= 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt 		.= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE setup_groups_group_id=".$_REQUEST['group_id'];

if($_REQUEST['src_title'])
{
	$where_conditions .= " AND item_title LIKE '%".add_slash($_REQUEST['src_title'])."%'";
}
if($_REQUEST['src_theme'])
{
	$where_conditions .= " AND themes_theme_id LIKE ".$_REQUEST['src_theme']."%";
}
if($_REQUEST['src_layout'])
{
	$where_conditions .= " AND layout_code LIKE '".$_REQUEST['src_layout']."%'";
}
//#Select condition for getting total count
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

$grsql  = "SELECT group_title 
				FROM  setup_groups 
					WHERE group_id='$group_id'";
$grres  = $db->query($grsql);
$grrow  = $db->fetch_array($grres);
$grname = $grrow['group_title'];	

$help_msg .=  "\" <b>".$grname."<b>\" ";				
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
		</table><br />
		</td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?>
		</td>
      </tr>
	  <!-- Search Section Starts here -->
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchThemes" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Title like 
		<input name="src_title" type="text" id='src_title' value="<?=$_REQUEST['src_title']?>" size="35" />
		<br />
		&nbsp;&nbsp;&nbsp;Layout Code Like 
		<input name="src_layout" type="text" id='src_layout' value="<?=$_REQUEST['src_layout']?>" size="35" />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="setup_items" />
		<input type="hidden" name="theme_id" value="<?php echo $_REQUEST['theme_id']?>" />
		<input type="hidden" name="group_id" value="<?php echo $_REQUEST['group_id']?>" />
		</form>
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT *
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
			  <td height="20" align="left"><a href="home.php?request=setup_items&fpurpose=edit&item_id=<?=$row['item_id']?>&group_id=<?=$row['setup_groups_group_id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['country_name'];?>"><?php echo stripslashes($row['item_title']); ?></a></td>	
			  <td height="20" align="center"><?php echo stripslashes($row['item_order']); ?></td>
			  <!--<td height="20" align="center"><?php //echo stripslashes($row['item_forecolor']);?></td>
			  <td height="20" align="center"><?php //echo stripslashes($row['item_bgcolor']);?></td> -->
			  <td height="20" align="center"><?php echo stripslashes($row['layout_code']);?></td>
			  <td width="11%" align="center"><a href="home.php?request=setup_items&fpurpose=edit&item_id=<?=$row['item_id']?>&group_id=<?=$row['setup_groups_group_id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
				  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=setup_items&fpurpose=delete&item_id=<?=$row['item_id']?>&group_id=<?=$row['setup_groups_group_id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?=$_REQUEST['theme_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a> 
				  </td>
			</tr>
    <?
		}
	}
	else {
	?>
	<tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Setup Group added yet. -- <br /><br />		</td>
		</tr>
	<?
	}
	
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_setupgroup" value="Add <?=$page_type?>" onclick="location.href='home.php?request=setup_items&group_id=<?PHP echo $_REQUEST['group_id'] ?>&theme_id=<?php echo $_REQUEST['theme_id']?>&fpurpose=add&src_title=<?=$_REQUEST['src_title']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=setup_items&src_title=".$_REQUEST['src_title'].'&theme_id='.$_REQUEST['theme_id'].'&group_id='.$_REQUEST['group_id'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>