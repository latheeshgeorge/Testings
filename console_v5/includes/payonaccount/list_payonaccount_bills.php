<?php
/*#################################################################
# Script Name 	: list_payonaccount_customers.php
# Description 		: Page for listing pay on account customers
# Created by 		: Sny
# Created on		: 16-Oct-2008	
# Modified by 		: 
# Modified on		: 
#################################################################*/
//Define constants for this page
$page_type			= 'Pay on Account Statements';
$help_msg 			= get_help_messages('LIST_PAY_ACCSTATEMENT_MESS1');
$table_headers 		= array('Slno.','Statement Date','');
$header_positions	= array('left','center','center');
$colspan = count($table_headers);

//#Search terms
$search_fields = array('limit_from','limit_to');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'pay_date':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('pay_date' => 'Statement Date');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

$customer_id		=$_REQUEST['customer_id'];
$sql_comp 			= "SELECT customer_title,customer_fname,customer_mname,customer_surname,
                                            customer_payonaccount_status,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,
                                            (customer_payonaccount_maxlimit - customer_payonaccount_usedlimit) as remaining,
                                            customer_payonaccount_billcycle_day,customer_payonaccount_rejectreason,customer_payonaccount_laststatementdate,
                                            customer_payonaccount_billcycle_month_duration 
                                    FROM 
                                            customers 
                                    WHERE 
                                            customer_id = $customer_id 
                                            AND sites_site_id = $ecom_siteid 
                                    LIMIT 
                                            1";
$ret_cust = $db->query($sql_comp);
if ($db->num_rows($ret_cust)==0)
{	
	echo "Sorry!! no details found";
	exit;
}	
$row_cust 	= $db->fetch_array($ret_cust);
$cust_name	= stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_mname']).' '.stripslashes($row_cust['customer_surname']);
//#Search Options


$where_conditions = " WHERE 
							a.sites_site_id=$ecom_siteid 
							AND customers_customer_id=$customer_id 
							AND pay_transaction_type ='O' ";
	$min_exists 	= $max_exists = false;
	$min 			= trim($_REQUEST['limit_from']);
	$max			= trim($_REQUEST['limit_to']);
	$between_column = '';
	/*if ($min!='')
	{
		if (is_numeric($min))
		{
			$min_exists = true;
		}	
		else
			$_REQUEST['limit_from'] = '';
	}	
	if ($max!='')
	{
		if (is_numeric($max))
		{
			$max_exists = true;
		}	
		else
			$_REQUEST['limit_to'] = '';
	}*/
	if($_REQUEST['limit_from'] && $_REQUEST['limit_to'] ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['limit_from']));
	$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$todate_arr = explode("-",add_slash($_REQUEST['limit_to']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (pay_date >= '".$fromdate."' AND pay_date <= '".$todate."' )";
}
if($_REQUEST['limit_from'] && $_REQUEST['limit_to']=='' ) {
	$fromdate_arr = explode("-",add_slash($_REQUEST['limit_from']));
	$fromdate =$fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
	$where_conditions .= "AND (pay_date >= '".$fromdate."')";
}
if($_REQUEST['limit_from']=='' && $_REQUEST['limit_to'] ) {
	$todate_arr = explode("-",add_slash($_REQUEST['limit_to']));
	$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
	$where_conditions .= "AND (pay_date <= '".$todate."' )";
}
	/*if ($min or $max)
	{
		$between_column = " DATE_FORMAT (pay_date,'%Y-%c-%d') "; 
		if ($min and $max)
		{
			$where_conditions .=" AND ($between_column BETWEEN $min and $max) ";
		}
		elseif ($min and !$max)
		{
			$where_conditions .=" AND $between_column >= $min ";
		}
		elseif (!$min and $max)
		{
			$where_conditions .=" AND $between_column <= $max ";
		}
	}*/


//#Select condition for getting total count
$sql_count = "SELECT pay_id  remaining  FROM order_payonaccount_details a $where_conditions";
$res_count = $db->query($sql_count);
$numcount = $db->num_rows($res_count);
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payonaccount&fpurpose=view_bills&customer_id=".$customer_id."&records_per_page=$records_per_page&start=$start&pass_txt_name=".$_REQUEST['pass_txt_name']."&pass_cbo_selectlimit=".$_REQUEST['pass_cbo_selectlimit']."&pass_limit_from=".$_REQUEST['pass_limit_from']."&pass_limit_to=".$_REQUEST['pass_limit_to']."&pass_cbo_payon_status=".$_REQUEST['pass_cbo_payon_status']."&pass_start=".$_REQUEST['pass_start']."&pass_pg=".$_REQUEST['pass_pg']."&pass_records_per_page=".$_REQUEST['pass_records_per_page'];
?>
<form name="frm_payonaccount" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="view_bills" />
<input type="hidden" name="request" value="payonaccount" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="pass_txt_name" value="<?php echo $_REQUEST['pass_txt_name']?>" />
<input type="hidden" name="pass_cbo_selectlimit"  value="<?php echo $_REQUEST['pass_cbo_selectlimit']?>"/>
<input type="hidden" name="pass_limit_from" value="<?php echo $_REQUEST['pass_limit_from']?>"/>
<input type="hidden" name="pass_limit_to"  value="<?php echo $_REQUEST['pass_limit_to']?>"/>
<input type="hidden" name="pass_cbo_payon_status"  value="<?php echo $_REQUEST['pass_cbo_payon_status']?>" />
<input type="hidden" name="pass_start" value="<?php echo $_REQUEST['pass_start']?>" />
<input type="hidden" name="pass_pg" value="<?php echo $_REQUEST['pass_pg']?>" />
<input type="hidden" name="pass_records_per_page" value="<?php echo $_REQUEST['pass_records_per_page']?>" />
<input type="hidden" name="customer_id"  value="<?php echo $customer_id?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
<td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payonaccount&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['pass_cbo_selectlimit']?>&limit_from=<?=$_REQUEST['pass_limit_from']?>&limit_to=<?=$_REQUEST['pass_limit_to']?>&cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Pay on Account Details </a> <a href="home.php?request=payonaccount&fpurpose=account_summary&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['pass_cbo_selectlimit']?>&limit_from=<?=$_REQUEST['pass_limit_from']?>&limit_to=<?=$_REQUEST['pass_limit_to']?>&cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&customer_id=<?php echo $_REQUEST['customer_id']?>">Account Summary</a> <span>View Statements of <strong><?php echo $cust_name?></strong> as on <strong><?php echo date('d').' '.date('M').' '.date('Y')?></strong></span> </td>
    </tr>
	<tr>
	  <td  align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
	<?php 
			if($alert)
			{			
		?>
        		<tr>
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
		 ?> 
    <tr>
      <td height="48" class="sorttd" >
	  <div class="sorttd_div">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="100%" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td colspan="2" align="left">Between Dates 
                <input name="limit_from" id="limit_from" type="text" size="12" value="<?php echo $_REQUEST['limit_from']?>" /> 
                <a href="javascript:show_calendar('frm_payonaccount.limit_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a>and
                  <input name="limit_to" id="limit_to" type="text" size="12" value="<?php echo $_REQUEST['limit_to']?>" />
				  <a href="javascript:show_calendar('frm_payonaccount.limit_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" align="absmiddle" /></a></td>
              <td width="8%" align="left">Show</td>
              <td colspan="2" align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/>
                <?=$page_type?>
&nbsp;Per Page&nbsp;&nbsp;</td>
            </tr>
            <tr>
              <td width="14%" align="right">&nbsp;</td>
              <td width="31%" align="left">&nbsp;</td>
              <td align="left">Sort By</td>
              <td width="30%" align="left"><?=$sort_option_txt?>
in
  <?=$sort_by_txt?></td>
            <td width="17%" align="left"><input name="main_go" type="submit" class="red" id="main_go" value="Go" />
                <a href="#" onMouseOver ="ddrivetip('<?=get_help_messages('LIST_PAY_ACCSTATEMENT_GO')?>')"; onMouseOut="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
         </tr>
      </table></td>
          </tr>
      </table>	  
	  </div>
	  </td>
    </tr>
    <tr>
      <td align="center" class="listeditd">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?></td>
    </tr>
     
    <tr>
      <td class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable">
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	  	 $sql_payoncust = "SELECT a.pay_id,a.pay_date,a.orders_order_id  
										FROM 
											order_payonaccount_details a 
										$where_conditions 
										ORDER BY 
											$sort_by $sort_order 
										LIMIT 
											$start,$records_per_page ";
	   
	   $res = $db->query($sql_payoncust);
	   $srno = 1; 
	   $tot_used = 0;
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
			$tot_used += $row['customer_payonaccount_usedlimit'];
	   
	   ?>
        <tr >
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><?php echo $srno++?></td>
          <td align="center" valign="middle" class="<?=$class_val;?>" width="15%">
		  <a href="home.php?request=payonaccount&fpurpose=bill_details&customer_id=<?php echo $customer_id?>&pay_id=<?php echo $row['pay_id']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['pass_cbo_selectlimit']?>&pass_limit_from=<?=$_REQUEST['pass_limit_from']?>&pass_limit_to=<?=$_REQUEST['pass_limit_to']?>&pass_cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&limit_from=<?=$_REQUEST['limit_from']?>&limit_to=<?=$_REQUEST['limit_to']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&records_per_page=<?=$_REQUEST['records_per_page']?>"  class="edittextlink" onClick="show_processing()"><?php echo dateFormat($row['pay_date'],'datetime');?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"  width="80%"></td>
        </tr>
      <?
	  	}
	  }
	  else
	  {
	  ?>
	  		<tr>
				<td align="center" valign="middle" class="norecordredtext" colspan="3" >
				  	No Statements found.	  </td>
			</tr>
		<?
		}
		?>	
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td align="center" class="listeditd">
	  <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
    </table>
</form>
