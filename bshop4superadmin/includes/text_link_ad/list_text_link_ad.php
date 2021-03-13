<?php
	/*#################################################################
	# Script Name 	: list_text_link.php
	# Description 	: Page for listing text link ads
	# Coded by 		: SKR
	# Created on	: 06-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################

//#Define constants for this page
*/
$table_name 	= 'sites';
$page_type 		= 'Sites';
$help_msg 		= 'This section lists the Sites available on the System. Here there is provision for assigning text link ads.';
$table_headers 	= array('Slno.','Title','Domain','Actions');
$header_positions=array('center','center','center','center');
$colspan 		= count($table_headers);

//#Search terms
$search_fields 	= array('site_title','site_domain');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";//#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'site_domain':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('site_title' => 'Site title','site_domain' => 'Domain name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
if($_REQUEST['title']) {
	$where_conditions = " AND sites.site_title LIKE '%".add_slash($_REQUEST['title'])."%'";
}
if($_REQUEST['domain']) {
	$where_conditions .= " AND sites.site_domain LIKE '%".add_slash($_REQUEST['domain'])."%'";
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

?>
<table width="100%" border="0" cellpadding="0" cellspacing="1">
	<tr>
		<td colspan="<?=$colspan?>">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd">&nbsp;<b>Manage Text Link Ads <font size="1">>></font> 
			  List Sites 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table></td>
	</tr>
      <tr>
        <td colspan="<?=$colspan?>" class="maininnertabletd3">
		<?=$help_msg?></td>
      </tr>
	  <?php
	  	if($alert_del)
		{
	  ?>
		  <tr class="maininnertabletd1">
			<td valign="top" class="error_msg" colspan="<?=$colspan?>" align="center">
				<?=$alert_del?>			</td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	  <form name="frmsearchClients" method="get" action="home.php">
	  <tr class="maininnertabletd1">
        <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>">
		
		  Site title like
            <input name="title" type="text" id='title' value="<?=$_REQUEST['title']?>" size="20" />
             Domain name like
             <input name="domain" type="text" id='domain' value="<?=$_REQUEST['domain']?>" size="20" />	  	</td>
      </tr>
	  <tr class="maininnertabletd1">
	    <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>"><p>Show
          <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/>
          Sites
per page. &nbsp;
<?=$sort_option_txt?>
<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
&nbsp;
<input type="hidden" name="pg" value="1" />
<input type="hidden" name="request" value="text_link_ad" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
</td>
  </tr>
    </form>	
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_sites = "SELECT sites.site_id,sites.site_title,sites.site_domain,sites.site_status,clients.client_company,clients.client_fname,clients.client_lname,sites.themes_theme_id FROM sites, clients WHERE sites.clients_client_id=clients.client_id $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
				// Find the name of current theme
				$sql_theme = "SELECT themename FROM themes WHERE theme_id=".$row['themes_theme_id'];
				$ret_theme = $db->query($sql_theme);
				if ($db->num_rows($ret_theme))
				{
					$row_theme = $db->fetch_array($ret_theme);
					$theme_name = $row_theme['themename'];
				}
		?>
		<tr  class="<?=$class_val;?>">
		  <td height="20" align="center"><?=$count_no?>.
			&nbsp;&nbsp;&nbsp;</td>
		  <td height="20" align="center"><?php echo stripslashes($row['site_title']); ?></td>
		  <td height="20" align="center"><a href="home.php?request=sites&fpurpose=edit&site_id=<?=$row['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?=stripslashes($row['site_domain'])?></a></td>
		  	
		  <td align="center"> <a href="home.php?request=text_link_ad&fpurpose=edit&site_id=<?=$row['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&pass_sort_by=<?=$sort_by?>&pass_sort_order=<?=$sort_order?>&pass_records_per_page=<?=$records_per_page?>&pass_pg=<?=$pg?>"><img src="images/layout.gif" border="0" alt="Links" /></a>
		</td>
		</tr>
		<?
		}
	}
	else
	{
	?>
			<tr class="maininnertabletd1">
        		<td align="center" class="error_msg" colspan="<?=$colspan?>"><br />-- No Sites Added Yet --<br /><br />				</td>
			</tr>	
	<?php	
	}
	 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=text_link_ad&title=".$_REQUEST['title']."&domain=".$_REQUEST['domain'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
    </table>
