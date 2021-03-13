<?php
	/*
	#################################################################
	# Script Name 	: list_theme_layouts.php
	# Description 	: Page for listing layouts for themes 
	# Coded by 		: Sny
	# Created on	: 31-May-2007
	# Modified by	: SKR
	# Modified On	: 01-June-2007  
	#################################################################
	*/

//Define constants for this page
$table_name = 'themes_layouts';
$page_type = 'Layouts';
$help_msg = 'This section lists the Layouts avaible for the selected theme on the site. Here there is provision for adding a layout, editing, & deleting it.';
$table_headers = array('Slno.','Layout name','Layout Code','Positions','Action');
$colspan = count($table_headers);

//Search terms
$search_fields = array('layoutname');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'layout_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('layout_name' => 'Layout name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

// Get the name of the selected theme
$sql_theme = "SELECT themename FROM themes WHERE theme_id=".$_REQUEST['pass_theme_id'];
$ret_theme = $db->query($sql_theme);
if ($db->num_rows($ret_theme))
{
	$row_theme 		= $db->fetch_array($ret_theme);
	$selthemename	= '"'.stripslashes($row_theme['themename']).'"';
}	
//Search Options
$where_conditions = "WHERE themes_theme_id= ".$_REQUEST['pass_theme_id'];

if($_REQUEST['layoutname']) {
	$where_conditions .= " AND layout_name LIKE '%".add_slash($_REQUEST['layoutname'])."%'";
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
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
			<td class="menutabletoptd"><a href="home.php?request=themes&theme_name=<?=$_REQUEST['pass_theme_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>" title="List Themes">List Themes</a><strong> <font size="1">>></font> List <?=$page_type?> for <?php echo $selthemename?>
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
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Layout name like 
		<input type="text" id='layoutname' name="layoutname" value="<?=$_REQUEST['layoutname']?>" />
		&nbsp;
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="themes" />
		<input type="hidden" name="fpurpose" value="theme_layout" />
		<input type="hidden" name="pass_theme_id" id="pass_theme_id" value="<?=$_REQUEST['pass_theme_id']?>" />
		<input type="hidden" name="pass_theme_name" id="pass_theme_name" value="<?=$_REQUEST['pass_theme_name']?>" />
		<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers);
	$sql_propertytype = "SELECT layout_id,layout_name,layout_code,layout_positions FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
      <td height="20" align="center"><a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$row['theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['layout_name']); ?></a></td>
	  <td height="20" align="center"><?php echo stripslashes($row['layout_code']); ?></td>
	  <td height="20" align="center"><?php echo stripslashes($row['layout_positions']); ?></td>
      <td width="16%" align="center" valign="middle"><a href="home.php?request=themes&fpurpose=edit_layout&layout_id=<?=$row['layout_id']?>&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;
      <?php if ($$use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=themes&fpurpose=delete&theme_id=<?=$row['theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
    </tr>
    <?
	}
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Layouts Added for the theme <?php echo $selthemename?> yet. -- <br /><br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_themes" value="Add <?=$page_type?>" onclick="location.href='home.php?request=themes&fpurpose=theme_layout_add&pass_theme_id=<?=$_REQUEST['pass_theme_id'];?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=themes";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
