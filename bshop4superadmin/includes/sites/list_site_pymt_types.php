<?php
	/*
	#################################################################
	# Script Name 	: list_site_pymt_types.php
	# Description 	: Page for listing Payment types avilabe for the site for the site
	# Coded by 		: ANU
	# Created on	: 26-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'payment_types pt,payment_types_forsites pts ';
$page_type = 'Payment Types';
$help_msg = 'This section lists the Payment Types available on the site. Here there is provision for selecting  Payment Types needed for this site.';
$table_headers = array('Enable/Disable','Slno.','Payment Types');
$header_positions=array('center','center','left');
$colspan = count($table_headers);

//Search terms
$search_fields = array('paytype_name');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
//Sort
$sort_by = (!$_REQUEST['sort_by'])?'paytype_id':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('paytype_name' => 'Payment Method Type');

if(!in_array($sort_by,$sort_options)) {
	//$sort_options = array('paytype_id' => 'Payment Method Type');
	$sort_by = 'paytype_id';
}


$sort_option_txt = 'Sort by '.generateselectbox('sort_by',$sort_options,$sort_by);
$sort_option_txt .= ' in '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//Search Options
$where_conditions = " WHERE pts.sites_site_id=".$_REQUEST['site_id']." AND pt.paytype_id=pts.paytype_id ";

if($_REQUEST['pay_method']) {
	$where_conditions .= " AND paytype_name LIKE '%".add_slash($_REQUEST['paytype_name'])."%'";
}
// Get the name of the Site seleted
$sql_sitedomain = "SELECT site_domain FROM sites WHERE site_id=".$_REQUEST['site_id'];
$ret_sitedomain = $db->query($sql_sitedomain);
if ($db->num_rows($ret_sitedomain))
{
	$row_sitedomain 	= $db->fetch_array($ret_sitedomain);
	$selsitedomain		= '"'.stripslashes($row_sitedomain['site_domain']).'"';
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
// Get the paymenttype id for payment type credit_card from 
//Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);//Getting total count of records

/////////////////////////////////For paging/////////////////////////////////////////// commented by anu for removing the paging
/*$records_per_page = is_numeric($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= $_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
//$startrec = ($pg - 1) * $records_per_page;//Starting record.
$pages = ceil($numcount / $records_per_page);//Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$startrec = ($pg - 1) * $records_per_page;//Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);*/
/////////////////////////////////////////////////////////////////////////////////////

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="77%" colspan="<?=$colspan?>" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
		  <tr> 
			<td class="menutabletoptd" ><b><a href="home.php?request=sites&amp;title=<?=$title?>&amp;domain=<?=$domain?>&amp;client=<?=$client?>&amp;theme=<?php $_REQUEST['theme']?>&amp;site_status=<?=$site_status?>&amp;sort_by=<?=$pass_sort_by?>&amp;sort_order=<?=$pass_sort_order?>&amp;records_per_page=<?=$pass_records_per_page?>&amp;pg=<?=$pass_pg?>">List Sites </a></b>>>&nbsp;<b> List <?=$page_type;?> For site <?=$selsitedomain?>
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
		<?=$page_type?> per page. &nbsp;Payment Type  name like 
		<input type="text" id='paytype_name' name="paytype_name" value="<?=$_REQUEST['paytype_name']?>" />-->
		<?php echo $sort_option_txt?>
		<input type="submit" class="smallsubmit" name="Paging_button" value="Go"  />
		<input type="hidden" name="pg" value="1" />
		<input type="hidden" name="request" value="sites" />
		<input type="hidden" name="fpurpose" value="Select_Payment_Types" />
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
	//$sql_payment_type = "SELECT pt.paytype_name,pts.paytype_forsites_id,pts.paytype_id,pts.paytype_forsites_active  FROM $table_name $where_conditions ORDER BY $sort_by $sort_order LIMIT $startrec, $records_per_page ";
	$sql_payment_type = "SELECT pt.paytype_name,pts.paytype_forsites_id,pts.paytype_id,pts.paytype_forsites_active  
							FROM $table_name 
								$where_conditions 
									ORDER BY $sort_by $sort_order"; // NO paging
	//echo 	$sql_payment_type;							
	//$sql_payment_type = "SELECT paytype_id,paytype_name FROM $table_name $where_conditions ORDER BY $sort_by $sort_order"; // NO paging
	$res = $db->query($sql_payment_type); 
	if (mysql_num_rows($res))
	{
		// Get the payment type id for credit card
		$sql_card = "SELECT paytype_id FROM payment_types WHERE paytype_code='credit_card' LIMIT 1";
		$ret_card = $db->query($sql_card);
		if($db->num_rows($ret_card))
		{
			$row_card 	= $db->fetch_array($ret_card);
			$cc_id		= $row_card['paytype_id'];
		}
	while($row = $db->fetch_array($res))
	 {
		$count_no++;
		$array_values = array();
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
		
	?>
	<form name="ListPymtMethdsSites" action="home.php" method="get" >
    <tr  class="<?=$class_val;?>">
      <td height="20" align="center" width="8%"><input type="checkbox" name="paytypeid[<?=$row['paytype_forsites_id']?>]" value="<?=$row['paytype_id']?>" <?php echo ($row['paytype_forsites_active'])?'checked':''; ?> /></td>
	  <td align="center" width="8%">&nbsp;<?=$count_no?>.</td>
	  <td height="20" align="left"><?php  if(($row['paytype_forsites_active']) && ($row['paytype_id'] == $cc_id) ){ ?><a href="home.php?request=sites&fpurpose=list_pymt_methods&site_id=<?=$_REQUEST['site_id']?>&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&status=<?=$status?>&theme=<?php echo $theme?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pg?>&pass1sort_by=<?=$sort_by?>&pass1sort_order=<?=$sort_order?>&pass1records_per_page=<?=$records_per_page?>&pass1pg=<?=$pg?>"><?php echo stripslashes($row['paytype_name']); ?></a><?php }else { echo stripslashes($row['paytype_name']); }?></td>
     
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
			-- No Payment Types found. -- <br />
			<br />		</td>
		</tr>
	<?php
	}
	?>
  <tr class="maininnertabletd1">
        <td align="center" valign="middle" class="maininnertabletd1" colspan="<?=$colspan?>">
		<input type="hidden" name="request" value="sites" />
		<input type="hidden" name="fpurpose" value="Select_Payment_Types" />
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
			        <input type="hidden" name="pass1sort_order" id="pass1sort_order" value="<?=$_REQUEST['pass1sort_order']?>" />
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
  $query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=sites&fpurpose=Select_Payment_Types&site_id=$site_id&pass_sort_by=$pass_sort_by&pass_sort_order=$pass_sort_order&pass_records_per_page=$pass_records_per_page&pass_pg=$pass_pg&title=$title&domain=$domain&client=$client&status=$status&theme=$theme&sort_by=$sort_by&sort_order=$sort_order&records_per_page=$records_per_page&pg=$pg";
  //paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
  ?></form>
   </table>
