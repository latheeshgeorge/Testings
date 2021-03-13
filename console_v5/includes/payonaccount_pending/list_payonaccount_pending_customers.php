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
$page_type			= 'Pay on Account Pending Details';
$help_msg 			= get_help_messages('LIST_PAYONACCOUNT_PENDING_MESS1');
$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_payonpendingaccount,\'checkbox[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_payonpendingaccount,\'checkbox[]\')"/>','Slno.','Customer Name','Pay Date','Pay Amount','Payment Status','Payment Type','Action');
$header_positions	= array('center','left','left','left','right','center','center','center');
$colspan = count($table_headers);

// =============================================================================================
// Check whether any record exists in order_payonaccount_pending_details table which have already moved to the order_payonaccount_details table
// =============================================================================================
$sql_sel = "SELECT b.pendingpay_id,a.pay_temp_id 
					FROM 
						order_payonaccount_pending_details b, order_payonaccount_details a 
					WHERE 
						b.sites_site_id = $ecom_siteid 
						AND a.pay_temp_id = b.pendingpay_id ";
$ret_sel = $db->query($sql_sel);
if ($db->num_rows($ret_sel))
{
	while ($row_sel = $db->fetch_array($ret_sel))
	{
		// Delete the entries in all the payonaccount pending related tables
		// Deleting from order_payonaccount_pending_details_payment table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details_payment  
							WHERE 
								order_payonaccount_pendingpay_id = ".$row_sel['pendingpay_id']." 
							LIMIT 
								1";
			$db->query($del);
			// Deleting from order_payonaccount_pending_details_payment table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details_cheque_details   
							WHERE 
								order_payonaccount_pending_details_pending_id = ".$row_sel['pendingpay_id']." 
							LIMIT 
								1";
			$db->query($del);
			// Deleting from order_payonaccount_pending_details table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details    
							WHERE 
								pendingpay_id = ".$row_sel['pendingpay_id']." 
							LIMIT 
								1";
			$db->query($del);
	}
}					
// =============================================================================================						


//#Search terms
$search_fields = array('txt_name');

foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
}

$from_date 	= add_slash($_REQUEST['pay_fromdate']);
$to_date 	= add_slash($_REQUEST['pay_todate']);

if ($from_date or $to_date)
{
	// Check whether from and to dates are valid
	$valid_fromdate = is_valid_date($from_date,'normal','-');
	$valid_todate	= is_valid_date($to_date,'normal','-');
	if($valid_fromdate)
	{
		$frm_arr 		= explode('-',$from_date);
		$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
		$mysql_fromdate	= date("Y-m-d H:i:s", mktime(0,0,0,$frm_arr[1],$frm_arr[0],$frm_arr[2]));
	}
	else// case of invalid from date
		$_REQUEST['pay_fromdate'] = '';
		
	if($valid_todate)
	{
		$to_arr 		= explode('-',$to_date);
		$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
		$mysql_todate 	= date("Y-m-d H:i:s", mktime(0,0,0,$to_arr[1],$to_arr[0],$to_arr[2]));
	}
	else // case of invalid to date
		$_REQUEST['pay_todate'] = '';
	if($valid_fromdate and $valid_todate)// both dates are valid
	{
		$where_conditions .= " AND (pay_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
		$disp_more = true; 
	}
	elseif($valid_fromdate and !$valid_todate) // only from date is valid
	{
		$where_conditions .= " AND pay_date >= '".$mysql_fromdate."' ";
		$disp_more = true; 
	}
	elseif(!$valid_fromdate and $valid_todate) // only to date is valid
	{
		$where_conditions .= " AND pay_date <= '".$mysql_todate."' ";
		$disp_more = true; 
	}
}

if(($_REQUEST['sel_pay_type']))
{
	if($_REQUEST['sel_pay_type']=='credit_card')
	{
		if($_REQUEST['chk_incomplete_pend'])
			$add_cond_incom = " AND pay_incomplete = 1 ";
		else
			$add_cond_incom = " AND pay_incomplete = 0 ";
	}
	else
		$add_cond_incom = " AND pay_incomplete = 0 ";
	$where_conditions .= "AND pay_paymenttype='".$_REQUEST['sel_pay_type']."' $add_cond_incom ";
}
else 
	$where_conditions .= " AND pay_incomplete = 0 ";
/*if(($_REQUEST['sel_pay_status'])) 
{
	$where_conditions .= "AND pay_paystatus='".$_REQUEST['sel_pay_status']."' $add_cond_incom ";
}*/

	
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'paydate':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
$sort_options = array('paydate'=>'Date','customer_fname' => 'Customer Name','pay_amount' => 'Pay Amount');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt = generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

/*
// Get the ids of customers from order_payonaccount_pending_details table
$sql_dist = "SELECT distinct customers_customer_id 
						FROM 
							order_payonaccount_pending_details 
						WHERE 
							sites_site_id = $ecom_siteid";
$ret_dist = $db->query($sql_dist);
$distcust_arr = array();
if ($db->num_rows($ret_dist))
{	
	while ($row_dist = $db->fetch_array($ret_dist))
	{	
		$distcust_arr[] = $row_dist['customers_customer_id'];
	}
}
if(count($distcust_arr))
	$cust_str = " AND customer_id IN (".implode(',',$distcust_arr).")";
else
	$cust_str = " AND customer_id IN (-1)";
//#Search Options
$where_conditions = " WHERE 
										a.sites_site_id=$ecom_siteid 
										$cust_str ";
*/
										
if($_REQUEST['txt_name'])
{
	$where_conditions .= "AND ( (customer_fname LIKE '%".add_slash($_REQUEST['txt_name'])."%' )  
									OR (customer_mname LIKE '%".add_slash($_REQUEST['txt_name'])."%' ) 
									OR (customer_surname LIKE '%".add_slash($_REQUEST['txt_name'])."%' ) 
									OR (customer_email_7503 LIKE '%".add_slash($_REQUEST['txt_name'])."%' ) )";
}

//#Select condition for getting total count
$sql_count = "SELECT a.customer_title, a.customer_fname, a.customer_mname, a.customer_surname, DATE_FORMAT(b.pay_date,'%d %b %Y') AS paydate,
								b.pay_amount, b.pay_paystatus, b.pay_paymenttype  
							FROM 
								customers a, order_payonaccount_pending_details b 
						   WHERE 
						   		a.customer_id=b.customers_customer_id
							 	AND b.sites_site_id='".$ecom_siteid."'
								$where_conditions";
$res_count = $db->query($sql_count);
$numcount = $db->num_rows($res_count);
/////////////////////////////////For paging///////////////////////////////////////////
$records_per_page = (is_numeric($_REQUEST['records_per_page']) and $_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:25;#Total records shown in a page
$pg = ($_REQUEST['search_click'])?1:$_REQUEST['pg'];

if (!($pg > 0) || $pg == 0) { $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$start = ($pg - 1) * $records_per_page;#Starting record.
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
/////////////////////////////////////////////////////////////////////////////////////
$sel_pay_type = $_REQUEST['sel_pay_type'];
$query_string .= "sort_by=$sort_by&sort_order=$sort_order&request=payonaccount_pending&records_per_page=$records_per_page&start=$start&sel_pay_type=$sel_pay_type";
?>
<script type="text/javascript">
function handle_paypending_delete()
{
	var atleast_one = false;
	/* Check whether atleast one checkbox is ticked */
	for(i=0;i<document.frm_payonpendingaccount.elements.length;i++)
	{
		if(document.frm_payonpendingaccount.elements[i].type=='checkbox')
		{
			if(document.frm_payonpendingaccount.elements[i].name=='checkbox[]')
			{
				if(document.frm_payonpendingaccount.elements[i].checked==true)
					atleast_one = true;
			}
		}
	}
	if (atleast_one==false)
	{
		alert('Please select atleast one pending transaction to delete');
		return false;
	}
	else
	{
		if(confirm('Are you sure you want to delete the selected pending transactions? \n\n This action is not reversible'))
		{
			document.frm_payonpendingaccount.fpurpose.value = 'do_pending_delete';
			document.frm_payonpendingaccount.submit();
		}
	}
}
function handle_incomplete_tr(obj)
{
	if (obj.value=='credit_card')
	{
		document.getElementById('incomplete_tr').style.display ='';
		document.getElementById('chk_incomplete_pend').checked=false;
	}
	else
	{
		document.getElementById('incomplete_tr').style.display ='none';
		document.getElementById('chk_incomplete_pend').checked=false;
	}
}
</script>
<form name="frm_payonpendingaccount" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="payonaccount_pending" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Pay on Account Pending Details</span></div></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>

	<?php  
	         if($_REQUEST['m']==1)
			 {
			  $alert = "Payment Approved Successfully!!!";
			 }
			if($alert)
			{			
		?>
        		<tr>
          			<td colspan="2" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
		 <?php
		 	}
	  if($numcount)
	  {
	    
		 ?> 
    <tr>
		<td colspan="2" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td>
	</tr>
	<?php
	  }
	?>
	<tr>
      <td height="48" colspan="2" class="sorttd" >
	  <div class="sorttd_div">
<table width="100%" border="0" cellspacing="0" cellpadding="1">
            <tr>
              <td width="18%" valign="middle">Customer Name or  Email Id</td>
              <td width="22%" align="left" valign="middle"><input type="text" name="txt_name" value="<?=$_REQUEST['txt_name']?>" /></td>
              <td width="10%" align="left" valign="middle">Pay Date From </td>
              <td width="13%" align="left" valign="middle"><input name="pay_fromdate" class="textfeild" type="text" size="12" value="<?php echo $_REQUEST['pay_fromdate']?>" /></td>
              <td width="2%" align="left" valign="middle"><a href="javascript:show_calendar('frm_payonpendingaccount.pay_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
              <td width="5%" align="center" valign="middle">To</td>
              <td width="8%" align="left" valign="middle"><input name="pay_todate" class="textfeild" id="pay_todate" type="text" size="12" value="<?php echo $_REQUEST['pay_todate']?>" /></td>
              <td width="14%" align="left" valign="middle"><a href="javascript:show_calendar('frm_payonpendingaccount.pay_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
              <td width="8%" align="left" valign="middle">&nbsp;</td>
            </tr>
            <tr>
              <td>Payment Type </td>
              <td align="left"><select name="sel_pay_type" id="sel_pay_type" onchange="handle_incomplete_tr(this);">
                <option value="0"> -Select- </option>
                <?PHP
			  	$pay_sql = "SELECT paytype_name, paytype_code FROM payment_types ";
				$pay_res = $db->query($pay_sql);
				while($pay_row = $db->fetch_array($pay_res)) 
				{
					echo "<option value='".$pay_row['paytype_code']."'";
					if($sel_pay_type==$pay_row['paytype_code']) echo 'selected';
					echo ">".$pay_row['paytype_name']."</option>";
				}
			  ?>
              </select></td>
              <td align="left">Records Per Page</td>
              <td align="left"><input name="records_per_page" type="text" class="textfeild" id="records_per_page" size="3" maxlength="3"  value="<?=$records_per_page?>"/></td>
              <td align="left">&nbsp;</td>
              <td align="left">Sort By</td>
              <td colspan="2" align="left"><?=$sort_option_txt?>
                &nbsp;
in&nbsp;
<?=$sort_by_txt?></td>
              <td align="right"><input name="main_go" type="submit" class="red" id="main_go" value="Go" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PAYON_ACC_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td colspan="2"><input type="checkbox" name="chk_incomplete_pend" id="chk_incomplete_pend" value="1" <?php if ($_REQUEST['chk_incomplete_pend']==1) echo 'checked="checked"'?> />
Show Incomplete transactions only </td>
              <td align="left">&nbsp;</td>
              <td align="left">&nbsp;</td>
              <td align="left">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td colspan="2" align="left">&nbsp;</td>
              <td align="left">&nbsp;</td>
            </tr>
          </table>
	  </div>	  </td>
    </tr>
    
     
    <tr>
      <td colspan="2" class="listingarea">
	  <div class="listingarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		<?
			if($numcount)
			{
		?>
		<tr><td align="right" class="listeditd" valign="middle" colspan="8"><a href="#" onclick="handle_paypending_delete()" class="deletelist">Delete</a></td></tr>
		<?
		}
	   echo table_header($table_headers,$header_positions);
	   if($numcount)
	   {
	   $srno = 1; 
	   $tot_used = 0;
	   $sql_count = "SELECT a.customer_id, a.customer_title, a.customer_fname, a.customer_mname, a.customer_surname, b.pendingpay_id,
										DATE_FORMAT(b.pay_date,'%d %b %Y') AS paydate, b.pay_amount, b.pay_paystatus, b.pay_paymenttype,b.pay_incomplete    
									FROM 
										customers a, order_payonaccount_pending_details b 
									WHERE 
										a.customer_id=b.customers_customer_id
										 AND b.sites_site_id='".$ecom_siteid."'
										$where_conditions
									ORDER BY 
										$sort_by $sort_order 
									LIMIT 
										$start,$records_per_page";
$res_count = $db->query($sql_count);
	   while($row = $db->fetch_array($res_count))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
			$tot_used += $row['pay_amount'];
	   
	   ?>
        <tr >
			 <td align="left" valign="middle" class="<?=$class_val;?>"  width="5%"><input name="checkbox[]" value="<? echo $row['pendingpay_id']?>" type="checkbox"></td>
		  <td align="left" valign="middle" class="<?=$class_val;?>"  width="6%"><?php echo $srno++?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>" width="21%"><a href="home.php?request=customer_search&fpurpose=edit&checkbox[]=<?php echo $row['customer_id']?>"  class="edittextlink" onclick="show_processing()"><? echo stripslashes($row['customer_title']).stripslashes($row['customer_fname']).' '.stripslashes($row['customer_mname']).' '.stripslashes($row['customer_surname'])?></a></td>
		 <td align="left" valign="middle" class="<?=$class_val;?>"  width="16%"> <? echo $row['paydate']?></td>
          <?php /*?><td align="left" valign="middle" class="<?=$class_val;?>"  width="10%">
		  <?php 
		  if($row['customer_payonaccount_laststatementdate']=='0000-00-00')
		  	echo 'No Statements found';
		  else
		  {
			 $lastdate_arr = explode('-',$row['customer_payonaccount_laststatementdate']);
			 echo $lastdate_arr[2].'-'.$month_arr[$lastdate_arr[1]].'-'.$lastdate_arr[0];
			  
			}  
			  ?></td><?php */?>
		   <td align="right" valign="middle" class="<?=$class_val;?>"  width="10%"><?php echo  display_price($row['pay_amount'])?></td>
		   <td align="center" valign="middle" class="<?=$class_val;?>" width="18%">
		   <?php echo  getpaymentstatus_Name($row['pay_paystatus']);
		   if($row['pay_incomplete']==1) echo ' <span style="color:#FF0000">(Incomplete)</span>';
		   ?></td>
		   <td align="center" valign="middle" class="<?=$class_val;?>" width="17%"><?php echo  getpaymenttype_Name($row['pay_paymenttype'])?></td>
		    <td align="center" valign="middle" class="<?=$class_val;?>" width="18%"><a href="home.php?request=payonaccount_pending&fpurpose=pending_details&customer_id=<?php echo $row['customer_id']?>&pending_id=<?PHP echo $row['pendingpay_id']; ?>&txt_name=<?=$_REQUEST['txt_name']?>&pay_fromdate=<?=$_REQUEST['pay_fromdate']?>&pay_todate=<?=$_REQUEST['pay_todate']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&sel_pay_type=<?=$_REQUEST['sel_pay_type']?>&chk_incomplete_pend=<?php echo $_REQUEST['chk_incomplete_pend']?>" class="edittextlink">View Details</a></td>
        </tr>
      <?
	  	}
	  ?>
		  <tr >
			  <td colspan="4" align="right" valign="middle" class="shoppingcartpriceB">Page Total </td>
		      <td align="right" valign="middle" class="shoppingcartpriceB"><?php echo  display_price($tot_used)?></td>
	         <td align="right" valign="middle" class="shoppingcartpriceB" width="18%">&nbsp;</td>
			   <td colspan="2" align="right" valign="middle" class="shoppingcartpriceB"></td>
		  </tr>
	  <?php
	  }
	  else
	  {
	  ?>
	  <tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="8" >
				  	No pay on Account	pending details found	  </td>
		  </tr>
		<?
		}
		?>	
		
      </table>
	  </div></td>
    </tr>
	<tr>
      <td colspan="2" align="right" valign="middle" class="listing_bottom_paging">
	    <?
	  if($numcount)
	  {
	    paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); 
	  }
	  ?>   	   </td>
    </tr>
  
  </table>
</form>
