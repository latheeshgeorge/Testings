<?php
	/*
	#################################################################
	# Script Name 	: list_site_pymt_methods.php
	# Description 	: Page for listing Payment Methods for the site
	# Coded by 		: ANU
	# Created on	: 6-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'payment_methods';
$page_type = 'Payment Methods';
$help_msg = 'This section lists the Payment Methods available on the site. Here there is provision for selecting  Payment Methods needed for this site.';
$table_headers = array('Enable/Disable','Slno.','Payment Methods','Action');
$header_positions=array('center','center','left','center');
$colspan = count($table_headers);

//Search terms
$search_fields = array('pay_method');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
if ($_REQUEST['sort_by'])
$sort_by = (!$_REQUEST['sort_by'])?'paymethod_name':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('paymethod_name' => 'Payment Method name');
if(!in_array($sort_by,$sort_options))
	$sort_by = 'paymethod_name';
$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = "WHERE 1=1 ";

if($_REQUEST['pay_method']) {
	$where_conditions .= " AND paymethod_name LIKE '%".add_slash($_REQUEST['pay_method'])."%'";
}
// Get the name of the Site seleted
$sql_sitedomain = "SELECT site_domain FROM sites WHERE site_id=".$_REQUEST['site_id'];
$ret_sitedomain = $db->query($sql_sitedomain);
if ($db->num_rows($ret_sitedomain))
{
	$row_sitedomain 		= $db->fetch_array($ret_sitedomain);
	$selsitedomain	= '"'.stripslashes($row_sitedomain['site_domain']).'"';
}
//  Select the checked payment methods for the sites
$existingPymethod = array();
$sql_existing_Paymethod = "SELECT payment_methods_forsites_id,payment_methods_paymethod_id FROM payment_methods_forsites WHERE sites_site_id=".$_REQUEST['site_id'];	
$ret_existing_Paymethod = $db->query($sql_existing_Paymethod);
if ($db->num_rows($ret_existing_Paymethod))
{
	while($row_sitePaymethod = $db->fetch_array($ret_existing_Paymethod)){
	$existingPymethod[] = $row_sitePaymethod['payment_methods_paymethod_id'];
	$Pymethod_forsites_id[$row_sitePaymethod['payment_methods_paymethod_id']] = $row_sitePaymethod['payment_methods_forsites_id'];
	}
	
}
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging///////////////////////////////////////////commented for ermoving the paging
/*$records_per_page = is_numeric($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$startrec = ($pg - 1) * $records_per_page;//Starting record.
$pages = ceil($numcount / $records_per_page);//Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);*/
/////////////////////////////////////////////////////////////////////////////////////

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="77%" colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd" ><b><a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$pass_sort_by?>&amp;sort_order=<?=$pass_sort_order?>&amp;records_per_page=<?=$pass_records_per_page?>&amp;pg=<?=$pass_pg?>">List Sites </a></b>>>
			<b><a href="home.php?request=sites&fpurpose=List_Payment_Types&site_id=<?=$_REQUEST['site_id']?>&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;paytype_name=<?=$_REQUEST['paytype_name']?>&amp;site_status=<?=$site_status?>&amp;pass_sort_by=<?=$pass_sort_by?>&amp;pass_sort_order=<?=$pass_sort_order?>&amp;pass_records_per_page=<?=$pass_records_per_page?>&amp;pass_pg=<?=$pass_pg?>&amp;sort_by=<?=$pass1sort_by?>&amp;sort_order=<?=$pass1sort_order?>&amp;records_per_page=<?=$pass1records_per_page?>&amp;pg=<?=$pass1pg?>">List Payment Types </a> >></b>
			&nbsp;<b> List <?=$page_type;?> For site <?=$selsitedomain?>
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
	  <tr class="maininnertabletd1">
        <td valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<form name="frmsearchPaymentMethods" method="get" action="home.php">
		<!--Show <input type="text" id="records_per_page" name="records_per_page" value="<?=$records_per_page?>" size="2"/> 
		<?=$page_type?> per page. &nbsp;Payment Methods  name like 
		<input type="text" id='pay_method' name="pay_method" value="<?=$_REQUEST['pay_method']?>" />-->
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="sites" />
		<input type="hidden" name="fpurpose" value="list_pymt_methods" />
		<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
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
		<!--pasing values from the sites listing page ends-->
		</form>		</td>
      </tr>
	  <!-- Search Section Ends here -->
	<?php
	echo table_header($table_headers,$header_positions);
	//$sql_propertytype = "SELECT paymethod_id,paymethod_name  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$sql_propertytype = "SELECT paymethod_id,paymethod_name,paymethod_key  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order"; // query without paging
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
		// check whether details required for current payment method
		$sql_chk = "SELECT payment_method_details_id FROM payment_methods_details 
							WHERE payment_methods_paymethod_id=".$row['paymethod_id'];
		$ret_chk = $db->query($sql_chk);
		$chk_cnt = $db->num_rows($ret_chk);
	?>
	<form name="ListPymtMethdsSites" action="home.php" method="get" >
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center" width="5%"><input type="checkbox" name="paymt_method[<?=$row['paymethod_id']?>]" value="1" <?php if(is_array($existingPymethod)){echo in_array($row['paymethod_id'],$existingPymethod)?'checked':'';}?> /></td>
      <td align="center" width="8%"><?=$count_no?></td>
      <td align="left"><a href="home.php?request=payment_methods&fpurpose=edit&paymethod_id=<?=$row['paymethod_id']?>&pay_method=<?=$pay_method?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>" title="Edit"><?php echo stripslashes($row['paymethod_name']); ?></a></td>
      <td  align="center" valign="middle"><?php if(is_array($existingPymethod) && $chk_cnt>0){if (in_array($row['paymethod_id'],$existingPymethod)){?><a href="home.php?request=sites&amp;fpurpose=view_paymethod_values&amp;paymethod_id=<?=$row['paymethod_id']?>&amp;pay_method=<?=$pay_method?>&pymt_methods_forsites_id=<?=$Pymethod_forsites_id[$row['paymethod_id']]?>&amp;sort_by=<?=$sort_by?>&amp;sort_order=<?=$sort_order?>&amp;records_per_page=<?=$records_per_page?>&amp;pg=<?=$pg?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&pass1sort_by=<?=$sort_by?>&pass1sort_order=<?=$sort_order?>&pass1records_per_page=<?=$records_per_page?>&pass1pg=<?=$pg?>" title="View Values"><img src="images/layout.gif" border="0" alt="Edit" /></a><? if($row['paymethod_key']=='ABLE2BUY'){?> <a href="home.php?request=sites&fpurpose=List_AbleToBy_details&site_id=<?=$_REQUEST['site_id']?>&amp;paymethod_id=<?=$row['paymethod_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>&passlsort_by=<?=$passlsort_by?>&passlsort_order=<?=$passlsort_order?>&passlrecords_per_page=<?=$passlrecords_per_page?>&passlpg=<?=$passlpg?>" title="List AbleToBy Details"><img src="images/pymt_methods.gif" border="0" alt="List AbleToBy Details" /></a><? } } }?>
      </td> 
    </tr>
    <?
	}
	}
	else
	{
	?>
		 <tr class="maininnertabletd1">
        <td valign="middle" class="error_msg" align="center" colspan="<?=$colspan?>">
			<br />
			-- No Payment Methods found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<input type="hidden" name="request" value="sites" />
		<input type="hidden" name="fpurpose" value="add_paymethod" />
		<input type="hidden" name="pay_method" value="<?=$pay_method?>" />
		<input type="hidden" name="sort_by" value="<?=$sort_by?>" />
		<input type="hidden" name="sort_order" value="<?=$sort_order?>" />
		<input type="hidden" name="records_per_page" value="<?=$records_per_page?>" />
		<input type="hidden" name="pg" value="<?=$pg?>" />
		<input type="hidden" name="site_id" value="<?=$_REQUEST['site_id']?>" />
		<!--pasing values from the sites listing page starts-->
		<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
			        <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
			        <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
			        <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
	<input type="hidden" name="pass1sort_by" id="pass1sort_by" value="<?=$_REQUEST['pass1sort_by']?>" />
	<input type="hidden" name="pass1sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass1sort_order']?>" />
	<input type="hidden" name="pass1records_per_page" id="pass1records_per_page" value="<?=$_REQUEST['pass1records_per_page']?>" />
	<input type="hidden" name="pass1pg" id="pass1pg" value="<?=$_REQUEST['pass1pg']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
			        <input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
			        <input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
			        <input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
					<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
		<!--pasing values from the sites listing page ends-->


		<input type="submit" name="assign_payment_methods" id="assign_payment_methods" value="Save Selected <?=$page_type?>" class="input-button" /> </td>     <? /*onclick="location.href='home.php?request=sites&fpurpose=add_paymethod&pay_method=<?=$pay_method?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>';" */?> 
  </tr>
  <?php 
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=sites&fpurpose=list_pymt_methods&site_id=$site_id&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_pg=$pass_pg&title=$title&domain=$domain&client=$client&status=$status&theme=$theme&paytype_name=$paytype_name&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&pg=$pg";
 // paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?></form>
   </table>
