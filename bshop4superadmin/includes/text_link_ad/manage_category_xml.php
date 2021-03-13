<?php
	/*#################################################################
	# Script Name 	: manage_category_xml.php
	# Description 	: Page for managing the category XML
	# Coded by 		: ANU
	# Created on	: 07-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################

//#Define constants for this page
*/
$table_name 	= 'product_categories';
$page_type 		= 'Category XML';
$help_msg 		= 'This section lists the categories available in the site. Here there is provision for giving the XML file name corresponding XML Key.';
$table_headers 	= array('Slno.','Category Name','XML File Name','XML Key');
$header_positions=array('center','center','center','center','center');
$colspan 		= count($table_headers);
// Find the name of current Site
				$sql_sites = "SELECT site_domain FROM sites WHERE site_id=".$_REQUEST['site_id'];
				$ret_sites = $db->query($sql_sites);
				if ($db->num_rows($ret_sites))
				{
					$row_sites = $db->fetch_array($ret_sites);
					$sites_name = $row_sites['site_domain'];
				}
//#Search terms
$search_fields 	= array('category_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";//#For passing searh terms to javascript for passing to different pages.
}
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'category_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('category_name' => 'Category Name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
$where_conditions = " AND sites_site_id=".$_REQUEST['site_id'];
//#Search Options
if($_REQUEST['category_name']) {
	$where_conditions .= " AND category_name LIKE '%".add_slash($_REQUEST['category_name'])."%'";
}

//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name WHERE 1=1 $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//#Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;//#Total records shown in a page
$pg = $_REQUEST['pg'];

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
			<td class="menutabletoptd"><b><a href="home.php?request=text_link_ad&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">List Sites </a><font size="1">>></font> 
			<a href="home.php?request=text_link_ad&fpurpose=edit&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Edit Text Link Ads </a><font size="1">>></font>
			Manage Category XML
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
	  <form name="frmManageCategoryXMLpaging" method="get" action="home.php">
	  <tr class="maininnertabletd1">
        <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>">
		
		  Category  like
            <input name="category_name" type="text" id="category_name" value="<?=$_REQUEST['category_name']?>" size="20" /></td>
      </tr>
	  <tr class="maininnertabletd1">
	    <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>">Show
          <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/>
         Categories
per page. &nbsp;
<?=$sort_option_txt?>
<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
<input type="hidden" name="site_id" value="<?=$_REQUEST['site_id']?>" />
<input type="hidden" name="request" value="text_link_ad" />
<input type="hidden" name="fpurpose" value="manage_category_xml" />
  <input type="hidden" name="pg" value="1" />
<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
</td>
  </tr>
  </form><form name="frmManageCategoryXML">
   <tr class="maininnertabletd1">
        <td valign="top" class="maininnertabletd1" colspan="<?=$colspan?>" align="right">
	<input type="hidden" name="site_id" value="<?=$_REQUEST['site_id']?>" />
<input type="hidden" name="request" value="text_link_ad" />
<input type="hidden" name="fpurpose" value="save_category_xml" />
<input type="hidden" name="category_name" value="<?=$_REQUEST['category_name']?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
	<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
	<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
	<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />
<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
	<?php if($numcount) {?>
            <input name="update_category_xml" type="submit" class="smallsubmit" id='update_category_xml' value="Save category XML" /><? }?></td>
      </tr>
    
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
 $sql_sites = "SELECT category_id,category_name,category_xml_filename,category_xml_key FROM product_categories WHERE  1=1  $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
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
				
		?>
		<tr  class="<?=$class_val;?>">
		  <td height="20" align="center"><?=$count_no?>.
			&nbsp;&nbsp;&nbsp;</td>
		  <td height="20" align="center"><input name="category_id[]" type="hidden" value="<?php echo stripslashes($row['category_id']); ?>"/><?php echo stripslashes($row['category_name']); ?></td>
		  <td height="20" align="center"><input name="category_xml_filename[]" type="text" value="<?php echo stripslashes($row['category_xml_filename']); ?>" size="25"/></td>
		  	
		  <td align="center"><input name="category_xml_key[]" type="text" value="<?php echo stripslashes($row['category_xml_key']); ?>" size="25"  /></td>
		</tr>
		<?
		}
	}
	else
	{
	?>
			<tr class="maininnertabletd1">
        		<td align="center" class="error_msg" colspan="<?=$colspan?>"><br />
        		-- No Categories Added Yet --<br />
        		<br />				</td>
			</tr>	
	<?php	
	}
	 
  $query_string .= "pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_pg=$pass_pg&sort_by=$sort_by&sort_order=$sort_order&request=text_link_ad&fpurpose=manage_category_xml&title=".$_REQUEST['title']."&domain=".$_REQUEST['domain']."&category_name=".$_REQUEST['category_name']."&site_id=".$_REQUEST['site_id'];
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?></form>	
    </table>
