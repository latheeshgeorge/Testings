<?php
/*
	#################################################################
	# Script Name 	: list_companytypes.php
	# Description 	: Page for listing Common company types 
	# Coded by 		: Sny
	# Created on	: 05-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
*/
#Define constants for this page
$table_name = 'common_customer_company_types';
$page_type = 'Company Types';
$help_msg = 'This section lists the Company types which will be shown in the company type drop down box in customer registration. These values will get added to the company types section for each of the sites automatically when a new site is created. Each site console will have option in general settings section to add/edit/delete any of the company types required to be shown in the drop down box.';
$table_headers = array('Slno.','Company Type name','Action');
$header_positions=array('center','left','center');
$colspan = count($table_headers);

#Search terms
$search_fields = array('comptypename');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
#Sort
$sort_by = (!$_REQUEST['sort_by'])?'comptype_order':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('comptype_order' => 'Sort Order','comptype_name' => 'Company Type Name');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

#Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['comptypename']) {
	$where_conditions .= " AND comptype_name LIKE '%".add_slash($_REQUEST['comptypename'])."%'";
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
			<td class="menutabletoptd">&nbsp;<b>Manage Common Company Types<font size="1"> >> </font> 
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
		<form name="frmsearchThemes" method="get" action="home.php" class="frmcls">
		Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;&nbsp;&nbsp;Company type name like <input type="text" id='countryname' name="countryname" value="<?=$_REQUEST['countryname']?>" />
		<?=$sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  /><br />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="cust_comptype" />
		</form>
		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	$sql_propertytype = "SELECT comptype_id,comptype_name 
							FROM $table_name 
								$where_conditions 
									ORDER BY $sort_by $sort_order 
										LIMIT $startrec, $records_per_page ";
	$res = $db->query($sql_propertytype); 
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
      <td height="20" align="left"><a href="home.php?request=cust_comptype&fpurpose=edit&comptype_id=<?=$row['comptype_id']?>&comptypename=<?=$comptypename?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="<?=$row['comptype_name'];?>"><?php echo stripslashes($row['comptype_name']); ?></a></td>
	  
      <td width="11%" align="center"><a href="home.php?request=cust_comptype&fpurpose=edit&comptype_id=<?=$row['comptype_id']?>&comptypename=<?=$comptypename?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a> 
	  	  | <a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=cust_comptype&fpurpose=delete&comptype_id=<?=$row['comptype_id']?>&comptypename=<?=$comptypename?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';}" title="Delete"><img src="images/delete.gif"  border="0" /></a> 
	  	  </td>
    </tr>
    <?
	}
	if($db->num_rows($res)==0)
	{
	?>
	 	<tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">No Company types found</td>      
  		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>"><input type="button" name="add_comptype" value="Add <?=$page_type?>" onclick="location.href='home.php?request=cust_comptype&fpurpose=add&comptypename=<?=$comptypename?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" class="input-button"/></td>      
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=cust_comptype";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?>
   </table>