<?php
/*
#################################################################
# Script Name 	: list_templates.php
# Description 	: Page for listing Email Templates 
# Coded by 		: SKR
# Created on	: 07-June-2007
# Modified by	: Sny
# Modified On	: 30-Jul-2007
#################################################################
*/
#Define constants for this page
$table_name = 'common_emailtemplates';
$page_type = 'Email Templates';
$help_msg = 'This section lists the Dafault Email Templates available on the site. Here there is provision for adding a template, editing, & deleting it.';
$table_headers = array('Slno.','Letter Title','Letter Type','Action');
$header_positions=array('center','left','left','center');
$colspan = count($table_headers);

#Search terms
$search_fields = array('lettertype');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'template_lettertype':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('template_lettertitle' => 'Letter Title','template_lettertype' => 'Letter Type');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['lettertype']) {
	$where_conditions .= " AND template_lettertype LIKE '%".add_slash($_REQUEST['lettertype'])."%'";
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
			<td class="menutabletoptd"><b>Manage Email Templates<font size="1">>></font> 
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
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> <?=$page_type?> per page. &nbsp;&nbsp;&nbsp;type like <input type="text" id='lettertype' name="lettertype" value="<?=$_REQUEST['lettertype']?>" />.<br />
		<?=$sort_option_txt?>
		&nbsp;<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="email_templates" />
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT template_id,template_lettertype,template_lettertitle FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
      <td height="20" align="center" width="10%"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=email_templates&fpurpose=edit&template_id=<?=$row['template_id']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['template_lettertitle'];?>"><?php echo stripslashes($row['template_lettertitle']); ?></a></td>
	  <td height="20" align="left"><a href="home.php?request=email_templates&fpurpose=edit&template_id=<?=$row['template_id']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['template_lettertype'];?>"><?php echo stripslashes($row['template_lettertype']); ?></a></td>
	  
      <td width="11%" align="center"><a href="home.php?request=email_templates&fpurpose=edit&template_id=<?=$row['template_id']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
	  	  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=email_templates&fpurpose=delete&template_id=<?=$row['template_id']?>&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a>	  	  </td>
    </tr>
    <?
	}
	}
	else {
	?>
	<tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" colspan="<?=$colspan?>" align="center">
			<br />-- No Templates added yet. -- <br /><br />		</td>
		</tr>
	<?
	}
	
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_propertytype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=email_templates&fpurpose=add&lettertype=<?=$_REQUEST['lettertype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=email_templates&lettertype=".$_REQUEST['lettertype'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  
  $sql_disable = "SELECT  disable_now ,  disable_end_on 
  					FROM 
						newsletter_disable 
					LIMIT 
						1";
$ret_disable = $db->query($sql_disable);
if($db->num_rows($ret_disable))
{
	$row_disable = $db->fetch_array($ret_disable);

}	
  ?>
  <form method="post" name="frm_diable" id="frm_disable">
  <input type="hidden" name="fpurpose" id="fpurpose" value="disable_newsletter" />
   <?php
  	if ($alert_bottom!='')
	{
  ?>
  	 <tr>
	  <td align="center" valign="middle" class="redtext" colspan="<?=$colspan?>">&nbsp;
	  </td>
	  </tr>
	  <tr>
	  <td align="center" valign="middle" class="redtext" colspan="<?=$colspan?>"><?php echo $alert_bottom?>
	  </td>
	  </tr>
  <?
  }
  ?>
  <tr class="maininnertabletd1">
    <td align="left" valign="middle" class="maininnertabletd" colspan="<?=$colspan?>"><strong>Manage Newsletter Sending Cron</strong></td>
  </tr>
   <tr class="maininnertabletd1">
    <td align="left" valign="middle" class="maininnertabletd" colspan="<?=$colspan?>">Disable Newsletter Sending <input type="checkbox" name="disable_now" id="disable_now" value="1" <?php echo ($row_disable['disable_now']==1)?'checked="checked"':''?>/> </td>
  </tr>
  <tr class="maininnertabletd1">
    <td align="left" valign="middle" class="maininnertabletd" colspan="<?=$colspan?>">Date at which sending will be resumed on <input type="text" name="disable_end_on" id="disable_end_on" value="<?php echo $row_disable['disable_end_on']?>" /><a href="javascript:show_calendar('frm_diable.disable_end_on');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> (dd-mm-yyyy)</td>
  </tr>
   <tr class="maininnertabletd1">
    <td align="left" valign="middle" class="maininnertabletd" colspan="<?=$colspan?>" style="padding-left:150px;"><input type="submit" name="disable_submit" id="disable_submit" class="input-button" value="Save" /></td>
  </tr>
  </form>
  </table>
