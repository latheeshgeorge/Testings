<?php
	/*
	#################################################################
	# Script Name 	: list_message.php
	# Description 	: Page for listing messages
	# Coded by 		: LSH
	# Created on	: 12-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
//Define constants for this page
$table_name = 'console_news';
$page_type  = 'Console News ';
$help_msg   = 'This section lists the news  available on the site. Here there is provision for adding a News, editing, & deleting it.';
$table_headers 		= array('Slno.','News Title','Site','Hidden','Action');
$header_positions	= array('center','left','center','center');
$colspan 			= count($table_headers);

//Search terms
$search_fields 	= array('news_title','news_priority','news_fromdate','news_todate');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by 		  =  (!$_REQUEST['sort_by'])?'news_add_date':$_REQUEST['sort_by'];
$sort_order 	  =  (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options 	  =  array('news_title' => 'Message Title');
$sort_option_txt  =  'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .=  ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//Search Options
$where_conditions = "WHERE 1=1 ";
//Search Options
/*if($_REQUEST['help_message']) {
	$where_conditions .= " AND help_help_message LIKE '%".add_slash($_REQUEST['help_message'])."%'";
}*/
if($_REQUEST['news_title_search']) {
	$where_conditions .= " AND news_title LIKE '%".add_slash($_REQUEST['news_title_search'])."%'";
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
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
		

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b>Manage Console News<font size="1">>></font> 
			  List <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		   </tr>
		</table><br />
		</td>
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
		<form name="frmsearchmessage" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="4"/> 
		<?=$page_type?> per page. &nbsp;Message  title like 
		<input type="text" id='news_title_search' name="news_title_search" value="<?=$_REQUEST['news_title_search']?>" />&nbsp
		<?
		 echo "&nbsp;".$sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="console_news" />
		</form>
		</td>
	 </tr>
	<form name="frmSavemessageshidden" method="post" action="home.php">
    <tr  class="maininnertabletd1">
    	<td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>" align="right">
    	<input type="Submit" name="Save" value="Save" class="input-button"/>
		<input type="hidden" name="request" value="console_news" />
		<input type="hidden" name="fpurpose" value="save_hidden" />
		<input type="hidden" name="pg" value="<?=$pg?>" />
		<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
		<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
		<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />
		</td>
	</tr>
		  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	
	$sql_propertytype = "SELECT news_id,sites_site_id,news_title,news_text,news_activeperiod,news_fromdate,news_todate,news_hide 
    						FROM 
								$table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
			if($row['sites_site_id']>0)
			{
				$sql_sites = "SELECT site_id,site_domain FROM sites WHERE site_id=".$row['sites_site_id'];
				$res_sites = $db->query($sql_sites);
				if($db->num_rows($res_sites) == 0) {
					$array_values[0] = 'No sites';
				}
				else
				{
					$site_exists = true;
					while($row_sites = $db->fetch_array($res_sites)) {
						$sid = $row_sites['site_id'];
						$array_values[$sid] = $row_sites['site_domain'];
					}	
				}
			}
			elseif($row['sites_site_id']==0)
			{
			$array_values[0] = 'All sites';
			}
		
	?>
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center"><?=$count_no?>
        &nbsp;&nbsp;&nbsp;</td>
      <td height="20" align="left"><a href="home.php?request=console_news&fpurpose=edit&news_id=<?=$row['news_id']?>&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['news_title']); ?></a></td>
			      <td align="center"><?=generateselectbox('sites'.$count_no,$array_values,0)?></td>
			<td  align="center"><select name="hide[<?=$row['news_id']?>]" id="hide[<?=$row['news_id']?>]">
      <option value="1" <?=(stripslashes($row['news_hide'])==1)?'selected':''; ?>>YES</option>
      <option value="0" <?=(stripslashes($row['news_hide'])==0)?'selected':''; ?>>NO</option>
      </select></td>
	  <td width="16%" align="center" valign="middle"><a href="home.php?request=console_news&fpurpose=edit&news_id=<?=$row['news_id']?>&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;
      <?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=console_news&fpurpose=delete&news_id=<?=$row['news_id']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?>	  	  </td>
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
			-- No News found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_news" value="Add <?=$page_type?>" onclick="location.href='home.php?request=console_news&fpurpose=add&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=console_news&startrec=$startrec";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
  </form> 
  </table>
   
