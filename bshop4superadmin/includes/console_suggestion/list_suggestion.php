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
$table_name = 'console_suggestions';
$page_type  = 'Console Suggestion ';
$help_msg   = 'This section lists the Console suggestions on the site.';
$table_headers 		= array('Slno.','Date','Title','Site','Username','Service','Feature','Status','Action');
$header_positions	= array('center','left','left','left','left','left','left','left','left');
$colspan 			= count($table_headers);

//Search terms
$search_fields 	= array('sugg_title','sugg_status');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by 		  =  (!$_REQUEST['sort_by'])?'sugg_title':$_REQUEST['sort_by'];
$sort_order 	  =  (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options 	  =  array('sugg_title' => 'Suggestion Title','sugg_date'=>'Date');
$sort_option_txt  =  'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);

$sort_option_txt .=  ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
//Search Options
$where_conditions = "WHERE 1=1 ";
//Search Options
/*if($_REQUEST['help_message']) {
	$where_conditions .= " AND help_help_message LIKE '%".add_slash($_REQUEST['help_message'])."%'";
}*/
if($_REQUEST['sugg_title']) {
	$where_conditions .= " AND sugg_title LIKE '%".add_slash($_REQUEST['sugg_title'])."%'";
}
if($_REQUEST['site_name']) {
	$where_conditions .= " AND sites_site_id LIKE '%".$_REQUEST['site_name']."%'";
} 
if($_REQUEST['feature_name']) {
	$where_conditions .= " AND features_feature_id LIKE '%".$_REQUEST['feature_name']."%'";
}
if($_REQUEST['service_name']) {
	$where_conditions .= " AND services_service_id LIKE '%".$_REQUEST['service_name']."%'";
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
			<td class="menutabletoptd">&nbsp;<b>Manage Console Suggestion<font size="1">>></font> 
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
		<form name="frmsearchmessage" method="get" action="home.php">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;Message  title like		
		<input type="text" id='sugg_title' name="sugg_title" value="<?=$_REQUEST['sugg_title']?>" />
		&nbsp
		<?
		 echo "&nbsp;".$sort_option_txt?>
		 <br />
		 Site Name :
		 <select name="site_name">
		<?PHP
		      $sql = "SELECT site_id, site_domain FROM sites ";
			  $res = $db->query($sql);
			  while($row = $db->fetch_array($res)) {
				 echo "<option value='$row[site_id]'";	
				 if($site_name==$row['site_id']) echo 'selected';
				 echo ">$row[site_domain]</option>";
			  }
		 ?>
		 </select>
		 Service :
		 <select name="service_name">
		 <option value="0">- Any - </option>
		<?PHP
		      $sql = "SELECT service_id, service_name FROM services ";
			  $res = $db->query($sql);
			  while($row = $db->fetch_array($res)) {
				 echo "<option value='$row[service_id]'";	
				 if($service_name==$row['service_id']) echo 'selected';
				 echo ">$row[service_name]</option>";
			  }
		 ?>
		 </select>
		  Feature :
		 <select name="feature_name">
		 <option value="0">- Any - </option>
		<?PHP
		      $sql = "SELECT feature_id, feature_name FROM features ";
			  $res = $db->query($sql);
			  while($row = $db->fetch_array($res)) {
				 echo "<option value='$row[feature_id]'";	
				 if($feature_name==$row['feature_id']) echo 'selected';
				 echo ">$row[feature_name]</option>";
			  }
		 ?>
		 </select>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="console_suggestion" />
		</form>		</td>
	 </tr>
	<form name="frmSavemessageshidden" method="get" action="home.php">
    <tr  class="maininnertabletd1">
    	<td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>" align="right">
    	<!--<input type="Submit" name="Save" value="Save" class="input-button"/> -->
		<input type="hidden" name="request" value="console_suggestion" />
		<input type="hidden" name="fpurpose" value="save_hidden" />
		<input type="hidden" name="pg" value="<?=$pg?>" />
		<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
		<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
		<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />		</td>
	</tr>
		  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	
	$sql_propertytype = "SELECT sugg_id,DATE_FORMAT(sugg_date,'%d %b %Y') AS date,sugg_date,sites_site_id,sugg_user_id,sugg_user_name,sugg_email,services_service_id,
						features_feature_id,sugg_status,sugg_title,sugg_text  FROM 
								$table_name 
										$where_conditions 
												ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
				$row_sites = $db->fetch_array($res_sites);
				$sitename = $row_sites['site_domain'];
			}
			if($row['services_service_id']>0)
			{
				$sql_sites = "SELECT service_name FROM services WHERE service_id=".$row['services_service_id'];
				$res_sites = $db->query($sql_sites);
				$row_sites = $db->fetch_array($res_sites);
				$service_name = $row_sites['service_name'];
			}
			if($row['features_feature_id']>0)
			{
				$sql_sites = "SELECT feature_name FROM features WHERE feature_id=".$row['features_feature_id'];
				$res_sites = $db->query($sql_sites);
				$row_sites = $db->fetch_array($res_sites);
				$feature_name = $row_sites['feature_name'];
			}
			
		
	?>
    <tr  class="<?=$class_val;?>">
		<td height="20" align="center"><?=$count_no?></td>
		<td height="20" align="left"><?=$row['date']?></td>
		<td height="20" align="left"><?=$row['sugg_title']?></td>
		<td height="20" align="left"><?=$sitename?></td>
		<td height="20" align="left"><?=$row['sugg_user_name']?></td>
		<td height="20" align="left"><?=$service_name?></td>	
		<td height="20" align="left"><?=$feature_name?></td>
		
		<td  align="left"><?=$row['sugg_status']?></td>
		<td width="16%" align="left" valign="middle">&nbsp;
<a href="home.php?request=console_suggestion&fpurpose=view_suggestion&sugg_id=<?=$row['sugg_id']?>" title="View Suggestion Details"><img src="images/on.gif" border="0" title="View Suggestion Details" /></a>		
<?php if ($use_cnt==0) {?><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=view_suggestion&fpurpose=delete&sugg_id=<?=$row['sugg_id']?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a><?php } ?></td>
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
			-- No Suggestions found. -- <br />
			<br />		</td>
		</tr>
	<?php
	} /*
	?>
 <!-- <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_news" value="Add <?=$page_type?>" onclick="location.href='home.php?request=console_news&fpurpose=add&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr> -->
  <?php 
  */
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=console_suggestion&startrec=$startrec";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
  </form> 
  </table>
   
