<?php
	/*#################################################################
	# Script Name 	: list_sites.php
	# Description 	: Page for listing sites
	# Coded by 		: Sny
	# Created on	: 04-Jun-2007
	# Modified by	: Sny
	# Modified On	: 05-Jun-2007
	#################################################################

//#Define constants for this page
*/


$table_name 	= 'sites';
$page_type 		= 'Sites';
$help_msg 		= 'This section lists the Sites available on the System. Here there is provision for adding a Site, editing, & deleting it.';
$table_headers 	= array('Slno.','Title','Domain','Client','Status','Web Theme','Mobile Theme','Actions');
$header_positions=array('center','left','left','left','center','left','left','center');
$colspan 		= count($table_headers);

//#Search terms
$search_fields 	= array('title','domain','client','status','theme','mobiletheme');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";//#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'site_domain':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('site_title' => 'Site title','site_domain' => 'Domain name' , 'client_company' => 'Company Name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
if($_REQUEST['title']) {
	$where_conditions = " AND sites.site_title LIKE '%".add_slash($_REQUEST['title'])."%'";
}
if($_REQUEST['domain']) {
	$where_conditions .= " AND sites.site_domain LIKE '%".add_slash($_REQUEST['domain'])."%'";
}
if($_REQUEST['client']) {
	$where_conditions .= " AND sites.clients_client_id = ".$_REQUEST['client'];
}
if($_REQUEST['status']) {
	$where_conditions .= " AND sites.site_status = '".$_REQUEST['status']."'";
}
if($_REQUEST['theme']) {
	$where_conditions .= " AND sites.themes_theme_id = '".$_REQUEST['theme']."'";
}

if($_REQUEST['mobiletheme']) {
	$where_conditions .= " AND sites.mobile_themes_theme_id = '".$_REQUEST['mobiletheme']."'";
}
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name WHERE 1=1 $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;//#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//#Starting record.
$pages = ceil($numcount / $records_per_page);//#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////

$client_array = array(0 => '-- All --');
$sql_clients = "SELECT client_id,client_company,client_fname,client_lname FROM clients ORDER BY client_company ASC";
$res_clients = $db->query($sql_clients);
while($row_clients = $db->fetch_array($res_clients)) {
	$client_array[$row_clients['client_id']] = stripslashes($row_clients['client_fname'])." ".stripslashes($row_clients['client_lname'])." (".stripslashes($row_clients['client_company']).")";
}
$theme_array = array(0 => '-- Any --');
$sql_theme = "SELECT theme_id,themename FROM themes WHERE themetype='Normal' ORDER BY themename ASC";
$res_theme = $db->query($sql_theme);
while($row_theme = $db->fetch_array($res_theme)) {
	$theme_array[$row_theme['theme_id']] = $row_theme['themename'];
}
$mobiletheme_array = array(0 => '-- Any --');
$sql_theme = "SELECT theme_id,themename FROM themes WHERE themetype='Mobile' ORDER BY themename ASC";
$res_theme = $db->query($sql_theme);
while($row_theme = $db->fetch_array($res_theme)) {
	$mobiletheme_array[$row_theme['theme_id']] = $row_theme['themename'];
}
$sitestatus_array = array(0=>'-- Any --','Awaiting Setup'=>'Awaiting Setup','Setup Completed'=>'Setup Completed','Under Construction' => 'Under Construction','Live' => 'Live','Suspended' => 'Suspended', "Cancelled" => "Cancelled");
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="<?=$colspan?>"><table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
      <tr>
        <td class="menutabletoptd">&nbsp;<b>Manage sites <font size="1">>></font> List
          <?=$page_type?>
          </b><br />
          <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="<?=$colspan?>" class="maininnertabletd3"><?=$help_msg?></td>
  </tr>
  <?php
	  	if($alert_del)
		{
	  ?>
  <tr class="maininnertabletd1">
    <td valign="top" class="error_msg" colspan="<?=$colspan?>" align="center"><?=$alert_del?>
    </td>
  </tr>
  <?php
	  	}
	  ?>
  <!-- Search Section Starts here -->
  <form name="frmsearchClients" method="get" action="home.php">
    <tr class="maininnertabletd1">
      <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>"> Site title like
        <input name="title" type="text" id='title' value="<?=$_REQUEST['title']?>" size="20" />
        Domain name like
        <input name="domain" type="text" id='domain' value="<?=$_REQUEST['domain']?>" size="20" />
        Under client <?php echo generateselectbox('client',$client_array,$_REQUEST['client']); ?>
         of status <?php echo generateselectbox('status',$sitestatus_array,$_REQUEST['status']); ?>
      </td>
    </tr>
    <tr class="maininnertabletd1">
      <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>">&nbsp; 
        Using Web theme <?php echo generateselectbox('theme',$theme_array,$_REQUEST['theme']); ?>
        &nbsp;&nbsp; Using Mobile theme <?php echo generateselectbox('mobiletheme',$mobiletheme_array,$_REQUEST['mobiletheme']); ?>
        </td>
    </tr>
    <tr class="maininnertabletd1">
      <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>">Show
        <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/>
          <?=$page_type?>
        per page. &nbsp;
        <?=$sort_option_txt?>
        <input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
        &nbsp;
        <input type="hidden" name="pg" value="1" />
        <input type="hidden" name="request" value="sites" /></td>
    </tr>
  </form>
  <!-- Search Section Ends here -->
  <?php
	echo table_header($table_headers,$header_positions);
	$sql_sites = "SELECT sites.site_id,sites.site_title,sites.site_domain,sites.site_status,clients.client_company,clients.client_fname,clients.client_lname,sites.themes_theme_id,mobile_themes_theme_id FROM sites, clients WHERE sites.clients_client_id=clients.client_id $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_sites); 
	if($db->num_rows($res))
	{
		while($row = $db->fetch_array($res))
		 {
			
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="maininnertabletd1";
			else
				$class_val="maininnertabletd2";	
			/*// Check whether any user exists for current site
			$sql_usr = "SELECT user_id FROM site_users WHERE sites_site_id=".$row['site_id']." LIMIT 1";
			$ret_usr = $db->query($sql_usr);
			$usr_cnt = $db->num_rows($ret_usr);	*/
				// Find the name of current theme
				$sql_theme = "SELECT themename FROM themes WHERE theme_id=".$row['themes_theme_id'];
				$ret_theme = $db->query($sql_theme);
				if ($db->num_rows($ret_theme))
				{
					$row_theme = $db->fetch_array($ret_theme);
					$theme_name = $row_theme['themename'];
				}
				$mobiletheme_name = '-';
				if($row['mobile_themes_theme_id'])
				{
					$sql_theme = "SELECT themename FROM themes WHERE theme_id=".$row['mobile_themes_theme_id'];
					$ret_theme = $db->query($sql_theme);
					if ($db->num_rows($ret_theme))
					{
						$row_theme = $db->fetch_array($ret_theme);
						$mobiletheme_name = $row_theme['themename'];
					}
				}	
		?>
  <tr  class="<?=$class_val;?>">
    <td height="20" align="center"><?=$count_no?>
      .
      &nbsp;&nbsp;&nbsp;</td>
    <td height="20" align="left"><?php echo stripslashes($row['site_title']); ?></td>
    <td height="20" align="left"><a href="home.php?request=sites&fpurpose=edit&site_id=<?=$row['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&status=<?=$_REQUEST['status']?>&theme=<?php echo $_REQUEST['theme']?>&mobiletheme=<?php echo $_REQUEST['mobiletheme']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit">
      <?=stripslashes($row['site_domain'])?>
    </a></td>
    <td height="20" align="left"><? echo stripslashes($row['client_fname'])." ".stripslashes($row['client_lname'])." (".stripslashes($row['client_company']).")"?></td>
    <td height="20" align="center"><?=stripslashes($row['site_status'])?></td>
    <td height="20" align="left"><?=stripslashes($theme_name)?></td>
    <td height="20" align="center"><?=stripslashes($mobiletheme_name)?></td>
   <!-- <td align="center"><a href="home.php?request=sites&fpurpose=list_pymt_methods&site_id=<?=$row['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$sort_by?>&pass_sort_order=<?=$sort_order?>&pass_records_per_page=<?=$records_per_page?>&pass_pg=<?=$pg?>">payment Methods</a> </td>-->
    <td align="center"><a href="home.php?request=sites&fpurpose=edit&site_id=<?=$row['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&status=<?=$_REQUEST['status']?>&theme=<?php echo $_REQUEST['theme']?>&mobiletheme=<?php echo $_REQUEST['mobiletheme']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>&nbsp;<a href="home.php?request=sites&fpurpose=user&site_id=<?=$row['site_id']?>&site_title=<?=$site_title?>&site_domain=<?=$site_domain?>&site_client=<?=$site_client?>&site_status=<?=$site_status?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="View User Login Details"><img src="images/on.gif" border="0" title="View User Login Details" /></a>&nbsp;<a href="home.php?request=sites&fpurpose=List_Shops&site_id=<?=$row['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$sort_by?>&pass_sort_order=<?=$sort_order?>&pass_records_per_page=<?=$records_per_page?>&pass_pg=<?=$pg?>" title="List Shops for this site"><img src="images/list_shops.gif" border="0" alt="List Shops in site" /></a> <a href="home.php?request=sites&fpurpose=List_Payment_Types&site_id=<?=$row['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$sort_by?>&pass_sort_order=<?=$sort_order?>&pass_records_per_page=<?=$records_per_page?>&pass_pg=<?=$pg?>" title="List Payment Types"><img src="images/pymt_methods.gif" border="0" alt="Select Payment Types" /></a>&nbsp;<a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=sites&amp;fpurpose=delete&amp;site_id=<?=$row['site_id']?>&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;status=<?=$status?>&amp;theme=<?php echo $theme?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif" border="0" title="Delete" /></a></td>
  </tr>
  <?
		}
	}
	else
	{
	?>
  <tr class="maininnertabletd1">
    <td align="center" class="error_msg" colspan="<?=$colspan?>"><br />
      -- No Sites Added Yet --<br />
      <br />
    </td>
  </tr>
  <?php	
	}
	?>
  <tr class="maininnertabletd1">
    <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_site" value="Add <?=$page_type?>" onclick="location.href='home.php?request=sites&fpurpose=add&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&theme=<?php $_REQUEST['theme']?>&site_status=<?=$site_status?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=sites";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
</table>