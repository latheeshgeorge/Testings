<?php
	/*
	#################################################################
	# Script Name 	: List_AbletoBy_details.php
	# Description 	: Page for listing Shops in this site
	# Coded by 		: LH
	# Created on	: 09-Apr-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'payment_method_forsites_able2buy_details';
$page_type = 'AbleToBy';
$help_msg = 'This section lists the AbleToBy Details added under this site.Here we can add,edit and delete details';
$table_headers = array('Slno.','Code','Order','Active','Action');
$header_positions=array('center','center','center','center','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('det_code');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'det_order':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('det_code' => 'Code');
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 AND sites_site_id=".$_REQUEST['site_id'];

if($_REQUEST['code_name']) {
	$where_conditions .= " AND det_code LIKE '%".add_slash($_REQUEST['code_name'])."%'";
}
// Get the name of the Site seleted
$sql_sitedomain = "SELECT site_domain FROM sites WHERE site_id=".$_REQUEST['site_id'];
$ret_sitedomain = $db->query($sql_sitedomain);
if ($db->num_rows($ret_sitedomain))
{
	$row_sitedomain 		= $db->fetch_array($ret_sitedomain);
	$selsitedomain	= '"'.stripslashes($row_sitedomain['site_domain']).'"';
}

//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records


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
<script language="javascript" type="text/javascript"> 
function save_changes(){

document.frmSaveAbledetailsInSites.fpurpose.value = "SaveAble_changes";

}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="77%" colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd" ><b><a href="home.php?request=sites&amp;code=<?=$code?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$passlsort_by?>&amp;pass_sort_order=<?=$pass_sort_order?>&amp;pass_records_per_page=<?=$pass_records_per_page?>&amp;pass_pg=<?=$pass_pg?>">List Sites </a></b>>>&nbsp;<b> List <?=$page_type;?> For site <?=$selsitedomain?>
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400"></td>
		  </tr>
		</table><br /></td>
	</tr>
      <tr>
        <td class="maininnertabletd3" colspan="<?=$colspan?>">
		<?=$help_msg?>		</td>
      </tr>
	  <?php
	  	if($error_msg)
		{
	  ?>
		  <tr>
			<td align="center" class="error_msg" colspan="<?=$colspan?>"><?php echo $error_msg?></td>
		  </tr>
	  <?php
	  	}
	  ?>
	  <!-- Search Section Starts here -->
	  		<form name="frmAbledetailsInSites" method="post" action="home.php">

	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		 Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;Code like 
		<input type="text" id='code_name' name="code_name" value="<?=$_REQUEST['code_name']?>" />
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="request" value="sites" />
		<input type="hidden" name="fpurpose" value="List_AbleToBy_details" />
		<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
		
	<input type="hidden" name="passlpg" value="<?=$passlpg?>" />
	<input type="hidden" name="passlsort_by" value="<?=$passlsort_by?>" />
	<input type="hidden" name="passlsort_order" value="<?=$passlsort_order?>" />
	<input type="hidden" name="passlrecords_per_page" value="<?=$passlrecords_per_page?>" />
		<!--pasing values from the sites listing page starts-->
		<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
		<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
		<input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
		<input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
		<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
		<!--pasing values from the sites listing page ends-->		 	</td>
      </tr></form>
	  <!-- Search Section Ends here -->
	  <form name="frmSaveAbledetailsInSites" method="post" action="home.php">
	  <tr><td colspan="<?=$colspan?>" align="right"><input type="submit" name="SaveAble_changes" id="SaveAble_changes" value="Save" class="input-button" onclick="return save_changes();"/></td></tr>
	<?php
	echo table_header($table_headers,$header_positions);
	
	$sql_payment_type = "SELECT det_id,det_code,det_hidden,det_order FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page"; // NO paging
	$res = $db->query($sql_payment_type); 
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
      <td height="20" align="center" width="2%">
         <?=$count_no?>        </td><td align="center"><a href="home.php?request=sites&fpurpose=Edit_AlbeToBy&det_id=<?=$row['det_id']?>&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pg?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>"><?php echo stripslashes($row['det_code']); ?></a></td><td align="center"><input type="text" name="ableto_order[<?=$row['det_id']?>]" id="Able_order[<?=$row['det_id']?>]" size="5" value="<?=$row['det_order']?>"> </td>
    <td   height="20" align="center"> <select name="ableto_active[<?=$row['det_id']?>]">
      <option value="0" <? if(!$row['det_hidden']){echo "selected";}?> >Yes</option>
      <option value="1" <? if($row['det_hidden']){echo "selected";}?>>No</option>
    </select></td>
	<td   height="20" align="center"><a href="home.php?request=sites&fpurpose=Edit_AlbeToBy&det_id=<?=$row['det_id']?>&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a><a href="#" onclick="if(delete_confirm()) { location.href='home.php?request=sites&amp;fpurpose=delete_AbletoBy&amp;det_id=<?=$row['det_id']?>&site_id=<?=$_REQUEST['site_id']?>&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;status=<?=$status?>&amp;theme=<?php echo $theme?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>';}" title="Delete"><img src="images/delete.gif" width="16" height="16" border="0" title="Delete" /></a></td>
    </tr>
    <?
	}
	
 $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=sites&fpurpose=List_AbleToBy_details&site_id=$site_id&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_pg=$pass_pg&code=$code&domain=$domain&client=$client&status=$status&theme=$theme&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&pg=$pg";
  paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
 
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" align="center" colspan="<?=$colspan?>">
			<br />
			-- No entries found in this site. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
      <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		 
	<input type="hidden" name="request" value="sites" />
	<input type="hidden" id='code_name' name="code_name" value="<?=$_REQUEST['code_name']?>" />
	<input type="hidden" name="pg" value="<?=$pg?>" />
	<input type="hidden" name="fpurpose" value="List_AbleToBy_details" />
	<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
	<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
	<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />
	<input type="hidden" name="pass_pg" value="<?=$pass_pg?>" />
	<input type="hidden" name="pass_sort_by" value="<?=$pass_sort_by?>" />
	<input type="hidden" name="pass_sort_order" value="<?=$pass_sort_order?>" />
	<input type="hidden" name="pass_records_per_page" value="<?=$pass_records_per_page?>" />
	<input type="hidden" name="passlpg" value="<?=$passlpg?>" />
	<input type="hidden" name="passlsort_by" value="<?=$passlsort_by?>" />
	<input type="hidden" name="passlsort_order" value="<?=$passlsort_order?>" />
	<input type="hidden" name="passlrecords_per_page" value="<?=$passlrecords_per_page?>" />
		<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
	<input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
	<input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
	<input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
	<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
	<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
	<input type="button" name="Add_AbleToBy" id="Add_AbleToBy" value="Add More" class="input-button" onclick="javascript:location.href='home.php?request=sites&fpurpose=Add_AbleToBy&site_id=<?=$_REQUEST['site_id']?>'"/></td>
  </tr>
  <?php 
  
  //paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?></form>
   </table>
