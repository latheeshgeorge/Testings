<?php
	/*
	#################################################################
	# Script Name 	: list_themes.php
	# Description 	: Page for listing Themes 
	# Coded by 		: Sny
	# Created on	: 31-May-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
//Define constants for this page
$table_name = 'themes';
$page_type = 'Themes';
$help_msg = 'This section lists the Themes available on the site. Here there is provision for adding a Theme, editing, & deleting it.';
$table_headers = array('Slno.','Theme name','Theme Type','Path','Usage Count','Action');
$header_positions=array('center','left','left','left','center','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('theme_name','themetype');
$themetype = $_REQUEST['themetype']; 
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'themename':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('themename' => 'Theme name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['theme_name']) {
	$where_conditions .= " AND themename LIKE '%".add_slash($_REQUEST['theme_name'])."%'";
}
if($_REQUEST['themetype']) {
	$where_conditions .= " AND themetype = '".add_slash($_REQUEST['themetype'])."' ";
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
			<td class="menutabletoptd">&nbsp;<b>Manage Themes<font size="1">>></font> 
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
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Theme name like <input type="text" id='theme_name' name="theme_name" value="<?=$_REQUEST['theme_name']?>" />
		&nbsp;
        Theme Type <select name="themetype" id="themetype">
        <option value="" >All</option>
        <option value="Normal" <?php if($_REQUEST['themetype']=='Normal') echo "selected";?>>Normal</option>
        <option value="Mobile" <?php if($_REQUEST['themetype']=='Mobile') echo "selected";?>>Mobile</option>
        </select>
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="themes" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT theme_id,themename,themetype,path FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
		$sql_sites 		= "SELECT count(site_id) as cnt FROM sites WHERE themes_theme_id=".$row['theme_id'];
		$ret_sites 		= $db->query($sql_sites);
		list($use_cnt) 	= $db->fetch_array($ret_sites);
	?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$row['theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['themename']); ?></a></td>
	   <td height="20" align="left"><?php echo $row['themetype']; ?></td>
       <td height="20" align="left"><?php echo $row['path']; ?></td>
	    <td height="20" align="center"><?php echo $use_cnt; ?></td>
      <td width="16%" align="center" valign="middle"><a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$row['theme_id']?>&pass_theme_name=<?=$_REQUEST['theme_name']?>&pass_themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;<a href="home.php?request=themes&amp;fpurpose=theme_layout&amp;pass_theme_id=<?=$row['theme_id']?>&pass_themetype=<?=$_REQUEST['themetype']?>&amp;pass_theme_name=<?=$_REQUEST['theme_name']?>&amp;pass_sort_by=<?=$sort_by?>&amp;pass_sort_order=<?=$sort_order?>&amp;pass_records_per_page=<?=$records_per_page?>&amp;pass_pg=<?=$pg?>" title="View Layouts"><img src="images/layout.gif" border="0" alt="View Layouts" /></a>
	  <a href="home.php?request=setup_groups&theme_id=<?php echo $row['theme_id']?>" title="Setup Wizard Groups"><img src="images/list_groups.gif" border="0" alt="Setup Wizard Groups" title="Setup Wizard Groups"/></a><?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=themes&fpurpose=delete&theme_id=<?=$row['theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
    </tr>
    <?
	}
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Themes added yet. -- <br /><br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_themes" value="Add <?=$page_type?>" onclick="location.href='home.php?request=themes&fpurpose=add&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=themes&themetype=$themetype";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
