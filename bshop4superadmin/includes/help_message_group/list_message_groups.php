<?php
	/*
	#################################################################
	# Script Name 	: list_message_groups.php
	# Description 	: Page for listing message groups
	# Coded by 		: LSH
	# Created on	: 12-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
//Define constants for this page
$table_name = 'console_help_group';
$page_type 	= 'Help Message Groups';
$help_msg 	= 'This section lists the Message Group available on the site. Here there is provision for adding a Message group, editing, & deleting it.';
$table_headers 		=   array('Slno.','Message Groups','Action');
$header_positions	=	array('center','left','center');
$colspan 			=   count($table_headers);

//Search terms
$search_fields 		= 	array('help_group');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by 		  = (!$_REQUEST['sort_by'])?'help_group_name':$_REQUEST['sort_by'];
$sort_order 	  = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 	  = array('help_group_name' => 'Group name');
$sort_option_txt  = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";
//Search Options
if($_REQUEST['help_group']) {
	$where_conditions .= " AND help_group_name LIKE '%".add_slash($_REQUEST['help_group'])."%'";
}
//Select condition for getting total count
$sql_count 		= "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count 		= $db->query($sql_count);
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
<form name="frmsearchmessagegroups" method="get" action="home.php">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b>Manage Message Group<font size="1">>></font> 
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
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;Group  name like 
		<input type="text" id='help_group' name="help_group" value="<?=$_REQUEST['help_group']?>" /><br />
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="help_message_group" />
		  <!-- Search Section Ends here -->
	  </td>
	 </tr>
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT help_group_id,help_group_name 
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
		
	?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=help_message_group&fpurpose=edit&help_group_id=<?=$row['help_group_id']?>&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['help_group_name']); ?></a></td>
      <td width="16%" align="center" valign="middle"><a href="home.php?request=help_message_group&fpurpose=edit&help_group_id=<?=$row['help_group_id']?>&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;
      <?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=help_message_group&fpurpose=delete&help_group_id=<?=$row['help_group_id']?>&help_group=<?=$_REQUEST['help_group']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
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
			-- No Message Group  found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_message_groups" value="Add <?=$page_type?>" onclick="location.href='home.php?request=help_message_group&fpurpose=add&help_group=<?=$help_group?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=help_message_group";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>
   </form>
