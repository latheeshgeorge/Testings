<?php
	/*#################################################################
	# Script Name 	: account_summary.php
	# Description 		: pay on account summary
	# Created by 		: Sny
	# Created on		: 16-Oct-2008
	# Modified by 		: 
	# Modified on		: 
	
	#################################################################*/
#Define constants for this page
$page_type 		= 'Company Type';
$help_msg 			= get_help_messages('EDIT_PAYONACC_SUMMARY_MESS1');
$customer_id		= $_REQUEST['customer_id'];
$sql_comp 		= "SELECT customer_title,customer_fname,customer_mname,customer_surname,
                                customer_payonaccount_status,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,
                                (customer_payonaccount_maxlimit - customer_payonaccount_usedlimit) as remaining,
                                customer_payonaccount_billcycle_day,customer_payonaccount_rejectreason,customer_payonaccount_laststatementdate,
                                customer_payonaccount_billcycle_month_duration,MONTH(customer_payonaccount_laststatementdate) as laststatemonth  
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
$row_cust 		= $db->fetch_array($ret_cust);
/*if($row_cust['customer_payonaccount_billcycle_day']<=date('d'))
    $next_date = date('d-M-Y',mktime(0,0,0,($row_cust['laststatemonth']+$row_cust['customer_payonaccount_billcycle_month_duration']),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
else
    $next_date = date('d-M-Y',mktime(0,0,0,date('m'),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
*/
if($row_cust['customer_payonaccount_billcycle_month_duration']==1)// case if interval is every month
{
    if($row_cust['customer_payonaccount_billcycle_day']<=date('d'))    
        $next_date = date('d-M-Y',mktime(0,0,0,(date('m')+1),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
    else
        $next_date = date('d-M-Y',mktime(0,0,0,date('m'),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
}
elseif($row_cust['customer_payonaccount_billcycle_month_duration']>1)// case if interval is not every month
{
    $last_statement_date    = explode('-',$row_cust['customer_payonaccount_laststatementdate']); // split the last statement date to get the numeric value of monthy. when payonaccount is activated for the first time the date of activation will be set as the last statement date by default.
    $last_month             = $last_statement_date[1];
    // get the numeric value of current month
    $cur_month              = date('n');
    if($cur_month<$last_month)
    {
        $cur_month += 12;   // Adding 12 to the current month numerical value to make it value > 12 so as to find the month difference
    }
    $month_diff = $cur_month - $last_month;
    
    if($month_diff==$row_cust['customer_payonaccount_billcycle_month_duration'] and ($row_cust['customer_payonaccount_billcycle_day']>date('d')))     // if next billing date is in current month
    {
        $next_date = date('d-M-Y',mktime(0,0,0,date('m'),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
    }
    else
    {  
        $next_date = date('d-M-Y',mktime(0,0,0,($row_cust['laststatemonth']+$row_cust['customer_payonaccount_billcycle_month_duration']),$row_cust['customer_payonaccount_billcycle_day'],date('Y')));
    }
}
$cust_name	= stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_mname']).' '.stripslashes($row_cust['customer_surname']);

// Getting the previous outstanding details from orders_payonaccount_details 
$sql_payonaccount = "SELECT pay_id, pay_date,pay_amount 
									FROM 
										order_payonaccount_details  
									WHERE 
										customers_customer_id = ".$_REQUEST['customer_id']."  
										AND pay_transaction_type ='O' 
									ORDER BY 
										pay_id DESC 
									LIMIT 
										1";
$ret_payonaccount = $db->query($sql_payonaccount);
if ($db->num_rows($ret_payonaccount))
{
	$row_payonaccount 	= $db->fetch_array($ret_payonaccount);
	$prev_balance				= $row_payonaccount['pay_amount'];
	$prev_id						= $row_payonaccount['pay_id'];
}
else
{
	$prev_balance				= 0;										
	$prev_id						= 0;
}	
?>	
<script type="text/javascript">
function ajax_return_contents() 
{
	var ret_val = '';
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetobj 	= document.getElementById('maincontent');
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			hide_processing();
		}
		else
		{
		    show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
} 
function call_ajax_makepaymentl()
{
	var atleastone 			= 0;
	var customer_id			= '<?php echo $customer_id?>';
	var txt_name 			= '<?php echo $_REQUEST['txt_name']?>';
	var qrystr					= '';
	var cbo_selectlimit 		= '<?php echo $_REQUEST['cbo_selectlimit']?>';
	var limit_from 			= '<?php echo $_REQUEST['limit_from']?>';
	var limit_to 				= '<?php echo $_REQUEST['limit_to']?>';
	var cbo_payon_status 	= '<?php echo $_REQUEST['cbo_payon_status']?>';
	var pay_additional_det	= document.getElementById('pay_additional_details').value;
	var pay_amt				= document.getElementById('pay_amt').value;
	var fpurpose				= 'make_payment';
	retobj 						= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';
	qrystr							= 'txt_name='+txt_name+'&cbo_selectlimit='+cbo_selectlimit+'&limit_from='+limit_from+'&limit_to='+limit_to+'&cbo_payon_status='+cbo_payon_status+'&pay_amt='+pay_amt+'&pay_additional_details='+pay_additional_det;
	Handlewith_Ajax('services/payonaccount.php','fpurpose='+fpurpose+'&customer_id='+customer_id+'&'+qrystr);
}
function handle_make_payment_top()
{
	if(document.getElementById('main_error_tr'))
		document.getElementById('main_error_tr').style.display = 'none';
	document.getElementById('make_pay_top_div').style.display 	= 'none';
	document.getElementById('pay_amt').value  				= '';
	document.getElementById('makepay_tr').style.display 	= '';
}
function handle_make_payment_cancel()
{
	document.getElementById('make_pay_top_div').style.display 	= 'inline';
	document.getElementById('pay_amt').value  				= '';
	document.getElementById('makepay_tr').style.display 	= 'none';
}
function validate_payment(frm)
{
	var tot_pending 		= '<?php echo $row_cust['customer_payonaccount_usedlimit']?>';
	var curr					= '<?php echo display_curr_symbol()?>';
	fieldRequired		 	= Array('pay_amt','cbo_paymethod');
	fieldDescription 		= Array('Amount','Payment Type');
	fieldEmail 				= Array();
	fieldConfirm 			= Array();
	fieldSpecChars 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 			= Array('pay_amt');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars))
	{
		payamt = parseFloat(document.getElementById('pay_amt').value);
		if (payamt>tot_pending)
		{
			alert('Sorry!! maximum amount that can be paid is '+curr+tot_pending);
			return false;
		}
		if(confirm('Are you sure you want to make the payment for  the specified amount?'))
		{
			 call_ajax_makepaymentl();
		}	
	}
}
function handle_details(id)
{
	obj 	= eval("document.getElementById('paydet_"+id+"')");
	objdet= eval("document.getElementById('paydetdiv_"+id+"')");
	if (objdet.style.display =='none')
	{
		obj .innerHTML = 'Hide Additional details <img src="images/down_arr.gif" align="Details" border="0">';
		objdet.style.display = '';
	}
	else
	{
		obj .innerHTML = 'Show Additional details <img src="images/right_arr.gif" align="Details" border="0">';
		objdet.style.display = 'none';
	}
}
function handle_bills()
{

	window.location = 'home.php?request=payonaccount&fpurpose=view_bills&customer_id=<?php echo $customer_id?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_txt_name=<?=$_REQUEST['txt_name']?>&pass_cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&pass_limit_from=<?=$_REQUEST['limit_from']?>&pass_limit_to=<?=$_REQUEST['limit_to']?>&pass_cbo_payon_status=<?=$_REQUEST['cbo_payon_status']?>&pass_start=<?=$_REQUEST['start']?>&pass_pg=<?=$_REQUEST['pg']?>';
}
</script>
<form name='frmpayonaccount_summary' action='home.php?request=payonaccount' method="post">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="txt_name" value="<?php echo $_REQUEST['txt_name']?>" />
<input type="hidden" name="cbo_selectlimit"  value="<?php echo $_REQUEST['cbo_selectlimit']?>"/>
<input type="hidden" name="limit_from" value="<?php echo $_REQUEST['limit_from']?>"/>
<input type="hidden" name="limit_to"  value="<?php echo $_REQUEST['limit_to']?>"/>
<input type="hidden" name="cbo_payon_status"  value="<?php echo $_REQUEST['cbo_payon_status']?>" />
<input type="hidden" name="customer_id"  value="<?php echo $customer_id?>" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payonaccount&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&txt_name=<?=$_REQUEST['txt_name']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&limit_from=<?=$_REQUEST['limit_from']?>&limit_to=<?=$_REQUEST['limit_to']?>&cbo_payon_status=<?=$_REQUEST['cbo_payon_status']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Pay on Account Details </a>  <span> Account summary  of <strong><?php echo $cust_name?></strong></span> </td>
        </tr>
       <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
	</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr id="main_error_tr">
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td width="100%" valign="top">
		<div class="listingarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
				  <td align="left" valign="middle" class="listingtablestyleB" ><strong>Customer</strong></td>
				  <td width="34%" align="left" valign="middle" class="listingtablestyleB">: <a href="home.php?request=customer_search&fpurpose=edit&checkbox[]=<?php echo $customer_id?>" class="edittextlink"><?php echo $cust_name?></a></td>
		          <td width="16%" align="left" valign="middle" class="listingtablestyleB"><strong>Credit Limit </strong></td>
                  <td width="33%" align="left" valign="middle" class="listingtablestyleB"><strong>: <?php echo display_price($row_cust['customer_payonaccount_maxlimit'])?></strong></td>
		</tr>
		  <?php
			if($row_cust['customer_payonaccount_laststatementdate']!='0000-00-00')
			{
		    ?>
			<?php
			}
		?>	
			<tr>
			  <td width="17%" align="left" valign="middle" class="shoppingcartpriceB" >&nbsp;</td>
			  <td align="left" valign="middle" class="shoppingcartpriceB">&nbsp;</td>
			  <td align="left" valign="middle" class="shoppingcartpriceB">Current Account Balance</td>
		      <td align="left" valign="middle" class="shoppingcartpriceB">: <?php echo display_price($row_cust['customer_payonaccount_usedlimit'])?></td>
			</tr>
			<?
				// Check whether any statements exists for current customers
			$sql_check = "SELECT pay_id 
									FROM 
										order_payonaccount_details 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND customers_customer_id = $customer_id 
										AND pay_transaction_type ='O' 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
		if($row_cust['customer_payonaccount_usedlimit']>0 || $db->num_rows($ret_check)>0)
		{
		?>
        <tr >
          <td colspan="2" align="left" valign="middle" class="homecontentusertabletdA" >&nbsp; </td>
          <td  align="left" valign="middle" class="homecontentusertabletdA" >
		  <?php
		  	if($row_cust['customer_payonaccount_usedlimit']>0)
			{
		  ?>
		  		<div id="make_pay_top_div"> <input name="make_payment" type="button" class="red" id="make_payment" value="Make Payment" onclick="handle_make_payment_top()"/></div>
		  <?php
			  }
		  ?>
		  </td>
		  <td  align="left" valign="middle" class="homecontentusertabletdA" >
		  <?php
			if ($db->num_rows($ret_check)>0)
			{
		  ?>
		  		<input name="view_bill" type="button" class="red" id="view_bill" value="View Statements" onclick="window.location='home.php?request=payonaccount&fpurpose=view_bills&customer_id=<?=$customer_id?>&pass_txt_name=<?=$_REQUEST['txt_name']?>&pass_cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&pass_limit_from=<?=$_REQUEST['limit_from']?>&pass_limit_to=<?=$_REQUEST['limit_to']?>&pass_cbo_payon_status=<?=$_REQUEST['cbo_payon_status']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_start=<?=$_REQUEST['start']?>&pass_pg=<?=$_REQUEST['pg']?>'" />
		 <?php
		 	}
		 ?>	 
		  </td>
		</tr>
		<? } ?>
		<tr id="makepay_tr" style="display:none;">
		<td colspan="4" align="center">
			<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="21%" >&nbsp;</td>
				<td align="center" width="75%">
						<table cellspacing="0" cellpadding="0" border="0" width="71%" bgcolor="#CEDDF4">
							<tr>
							<td class="listingtablestyleB" align="left" valign="top" width="15%">
							 Amount <span class="redtext">*</span></td>
							 <td width="41%"  align="left"  class="listingtablestyleB">
							 <input name="pay_amt" type="text" id="pay_amt"  value=""/> 
							 </td>
							</tr>
							<tr>
							  <td width="29%" align="left" class="listingtablestyleB">Payment Method <span class="redtext">*</span></td>
							  <td width="71%" align="left" class="listingtablestyleB"><select name="cbo_paymethod" id="cbopaymethod">
								<option value="">-- Select --</option>
								<option value="CARD">Credit Card</option>
								<option value="CHEQUE">Cheque / DD</option>
								<option value="BANK">Bank Transfer</option>
								<option value="PHONE">Pay on Phone</option>
								<option value="CASH" >Cash</option>
								<option value="OTHER">Other</option>
							  </select></td>
							</tr>
							<tr>
							<td class="listingtablestyleB" align="left" valign="top" width="29%" >Additional details</td>
							<td align="left"  class="listingtablestyleB" width="71%"><textarea name="pay_additional_details" id="pay_additional_details" cols="30" rows="5"></textarea>
							  </td>
							</tr>
							<tr>
							  <td  align="left" valign="middle" class="listingtablestyleB" > <input name="Cancel" type="button" class="red" id="cancel" value="Cancel" onclick="handle_make_payment_cancel()"  /></td>
							  <td  align="left" valign="middle" class="listingtablestyleB" ><input name="make_payment" type="button" class="red" id="make_payment" value="Make Payment" onclick="validate_payment(this.form)" /> </td>
							</tr>
				    </table>
				  </td>
					<td width="14%" >&nbsp;</td>
				</tr>
			</table>
		</td>
		</tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="homecontentusertabletdA" >Unbilled Transaction to appear on your next billing statement </td>
          <td colspan="2" align="left" valign="middle" class="homecontentusertabletdA" >Next Statement Date <strong><?php echo $next_date?></strong></td>
          </tr>
        <tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		  <?php
		  	$table_headers 		= array('Slno.','Date','Details','Amount','','');
			$header_positions	= array('left','center','left','right','center','left');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions);
			 
			// Get the list of transactions to be displayed here
		 	$where_add = " AND pay_id>=$prev_id AND pay_transaction_type!='O'";
			$sql_payondetails = "SELECT *
			 									FROM 
													order_payonaccount_details 
												WHERE 
													customers_customer_id = $customer_id 
													$where_add 
												ORDER BY 
													pay_date ASC";
			$ret_payondetails = $db->query($sql_payondetails);
			if ($db->num_rows($ret_payondetails))
				{		
					$srno = 0;
					while ($row_payondetails = $db->fetch_array($ret_payondetails))
					{
						$flag_cheque = $flag_pay = 0;
						$issue_date = $exp_date = '';
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleA';
						$srno++;
									if($row_payondetails['pay_paymenttype']=='cheque')
									{
										//Getting the details for the payment cheque.
										$sql_check_details ="SELECT 	* 
																		FROM  
																				order_payonaccount_cheque_details 
																			WHERE   	
																				   order_payaccount_cheque_pay_id=".$row_payondetails['pay_id']." 
																			LIMIT 1";
										$ret_check_details = $db->query($sql_check_details);
										if($db->num_rows($ret_check_details))	
										{									
											$row_check_details = $db->fetch_array($ret_check_details);
											if($row_check_details['cheque_date']!='' || $row_check_details['cheque_number']!='' || $row_check_details['cheque_bankname']!='' || $row_check_details['cheque_branchdetails']!='')	
											{
												$flag_cheque = 1;
											}	
										}	
									}
									elseif ($row_payondetails['pay_paymenttype']=='credit_card')
									{
										//Getting the details for the payment card.
										 $sql_payment_details ="SELECT card_type, card_number, name_on_card, sec_code, expiry_date_m, expiry_date_y, issue_number, issue_date_m,
										 									issue_date_y, vendorTxCode, protStatus, protStatusDetail, vPSTxId, securityKey, txAuthNo, txType, avscv2,
																			cavv, 3dsecurestatus, acsurl, pareq, md, orgtxType, card_encrypted, google_checkoutid, worldpay_transid, hsbc_cpiresultcode 
									 							FROM 
																	order_payonaccount_payment 
																WHERE 
																	order_payonaccount_pay_id=".$row_payondetails['pay_id']."
									 							LIMIT 1";
										$ret_payment_details = $db->query($sql_payment_details);
										if($db->num_rows($ret_payment_details))	
										{
											$row_payment_details = $db->fetch_array($ret_payment_details);
											//$flag_pay = 1;
											$issue_date = '';
											if($row_payment_details['issue_date_m']!=0 && $row_payment_details['issue_date_y']!=0)
												$issue_date = $row_payment_details['issue_date_m'].'/'.$row_payment_details['issue_date_y'];
											$exp_date = '';
											if($row_payment_details['expiry_date_m']!=0 && $row_payment_details['expiry_date_y']!=0)
												$exp_date = $row_payment_details['expiry_date_m'].'/'.$row_payment_details['expiry_date_m'];	
											if($row_payment_details['card_type']!='' || $row_payment_details['card_number']!='' || $row_payment_details['name_on_card']!='' || ($row_payment_details['sec_code']!='' && $row_payment_details['sec_code']!=0)  || $row_payment_details['vendorTxCode']!=''  || $row_payment_details['google_checkoutid'] !='' || $row_payment_details['worldpay_transid'] != ''  ||  $row_payment_details['hsbc_cpiresultcode'] !='')	
											{
												$flag_pay = 1;
											}
										}
                                                                                // Check whether there exists any entry in order_payonaccount_payment_paypal table for current payment 
                                                                               // Get the details given back to us from paypal
                                                                                $sql_paypal = "SELECT paypal_transactions_id, paypal_transaction_type, paypal_payment_type,
                                                                                                                        paypal_ordertime, paypal_amt, paypal_currency_code, paypal_feeamt, 
                                                                                                                        paypal_settleamt, paypal_taxamt, paypal_exchange_rate, paypal_paymentstatus,
                                                                                                                        paypal_pending_reason, paypal_reasoncode 
                                                                                                                FROM 
                                                                                                                        order_payonaccount_payment_paypal  
                                                                                                                WHERE 
                                                                                                                        order_payonaccount_pay_id = ".$row_payondetails['pay_id']."  
                                                                                                                        AND sites_site_id = $ecom_siteid
                                                                                                                LIMIT 
                                                                                                                        1";
                                                                                $ret_paypal = $db->query($sql_paypal);
                                                                                if($db->num_rows($ret_paypal))
                                                                                {
                                                                                    $row_paypal = $db->fetch_array($ret_paypal);
                                                                                }
									}
                                                                        else
                                                                            $row_paypal = array();
		  ?>
						<tr>
						  <td align="left" width="2%" class="<?php echo $cls?>"><?php echo $srno?>.</td>
						  <td width="15%" align="center" class="<?php echo $cls?>"><?php echo dateFormat($row_payondetails['pay_date'],'datetime');?></td>
						  <td width="38%" align="left" class="<?php echo $cls?>">
						  <?php 
							$link_from = '';
 							$link_to		= '';
						  if($row_payondetails['orders_order_id']!=0)
						  {
								$link_from = '<a href="home.php?request=orders&fpurpose=ord_details&edit_id='.$row_payondetails['orders_order_id'].'" class="edittextlink" title="Click for order details">';;
								$link_to		= '</a>';
						  }
						  elseif ($row_payondetails['gift_vouchers_voucher_id']!=0)
						  {
						  	// Check whether the current gift voucher still exists
							$sql_gift = "SELECT voucher_id 
													FROM 
														gift_vouchers 
													WHERE 
														voucher_id = ".$row_payondetails['gift_vouchers_voucher_id']." 
													LIMIT 
														1";
							$ret_gift = $db->query($sql_gift);
							if ($db->num_rows($ret_gift))
							{
						  		$link_from = '<a href="home.php?request=gift_voucher&fpurpose=edit&checkbox[0]='.$row_payondetails['gift_vouchers_voucher_id'].'" class="edittextlink" title="Click for voucher details">';;
								$link_to		= '</a>';
							}
						  }
						  	echo $link_from.stripslashes($row_payondetails['pay_details']).$link_to;
						  ?>						  </td>
						  <td width="15%" align="right" class="<?php echo $cls?>"><?php echo display_price($row_payondetails['pay_amount']);?></td>
						  <td align="center"  class="<?php echo $cls?>" width="5%">
						  <?php
								  if($row_payondetails['pay_transaction_type']=='C')
									echo ' <strong>(Cr.)</strong>';
							?>						</td>
						  <td width="25%" align="left" class="<?php echo $cls?>">
						  <?php
						  	if(count($row_paypal) || trim($row_payondetails['pay_additional_details']) !='' ||  $flag_cheque==1 || $flag_pay == 1 || $row_payondetails['pay_paystatus_changed_by']!=0)
							{
								//echo '<br>--'.$row_payondetails['pay_additional_details'].','.$flag_cheque.','.$flag_pay.','.$row_payondetails['pay_paystatus_changed_by'];
						  ?>
						  		<div id="paydet_<?php echo $row_payondetails['pay_id']?>" onClick="handle_details('<?php echo $row_payondetails['pay_id']?>')" style="padding-left:50px; cursor:pointer">
								Show Additional details <img src="images/right_arr.gif" align="Details" border="0">								</div>						  
						        <?php
						  }?>						  </td>
						</tr>
						<?php
						if(count($row_paypal) || $row_payondetails['pay_additional_details']!='' ||  $flag_cheque==1 || $flag_pay == 1 || $row_payondetails['pay_paystatus_changed_by']!=0)
						{
						?>
						<tr id="paydetdiv_<?php echo $row_payondetails['pay_id']?>" style="display:none">
							<td colspan="3">&nbsp;</td>
							<td colspan="3">
                                                            
                                                
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
								<?php
									// Check whether there exists any additional details added for current entry
									if($row_payondetails['pay_additional_details']!='')
									{
									?>
									<tr>
										<td  class="listingtablestyleB" colspan="2" align="left">
										<?php
											echo nl2br(stripslashes($row_payondetails['pay_additional_details']));
										?>						</td>
									</tr>
									<? }
									?>
                                                                        <tr>
                                                                        <td colspan='2' align='left'>
                                                                        <?
                                                                            if(count($row_paypal))
                                                                            {
                                                                            ?>
                                                                                <table width="100%" cellpadding="1" cellspacing="1" border="0">
                                                                                <tr>
                                                                                    <td align="left" colspan="2" class="shoppingcartheader">
                                                                                    Payment Gateway Return Details</td>
                                                                                </tr>
                                                                                <?php
                                                                                if($row_paypal['paypal_transactions_id']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Unique transaction ID of the payment</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_transactions_id']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_transaction_type']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Transaction Type</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_transaction_type']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_payment_type']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Payment Type</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_payment_type']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_ordertime']!='')
                                                                                {
                                                                                        $tarr = explode('T',$row_paypal['paypal_ordertime']);
                                                                                        $datarr = explode('-',$tarr[0]);
                                                                                        $showdate = $datarr[2].'-'.$datarr[1].'-'.$datarr[0];
                                                                                        $timearr = explode('Z',$tarr[1]);
                                                                                        $showdate .= ' '.$timearr[0];
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Order Time</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $showdate?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_amt']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Final Amount</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_amt']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_currency_code']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Currency Code</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_currency_code']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_feeamt']!='' and $row_paypal['paypal_feeamt']>0)
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>PayPal fee amount charged for the transaction</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_feeamt']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_settleamt']!='' and $row_paypal['paypal_settleamt']>0)
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Amount deposited in your PayPal account after a currency conversion</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_settleamt']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_taxamt']!='' and $row_paypal['paypal_taxamt']>0)
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Tax charged on the transaction</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_taxamt']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_exchange_rate']!='' and $row_paypal['paypal_exchange_rate']>0)
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Exchange rate if a currency conversion occurred</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_exchange_rate']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_paymentstatus']!='')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>Status of the payment</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_paymentstatus']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_pending_reason']!='' and strtolower($row_paypal['paypal_pending_reason'])!='none')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>The reason the payment is pending</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_pending_reason']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                if($row_paypal['paypal_reasoncode']!='' and strtolower($row_paypal['paypal_reasoncode'])!='none')
                                                                                {
                                                                                ?>
                                                                                    <tr>
                                                                                        <td align="left" width="45%" class="listingtablestyleB">
                                                                                        <strong>The reason code</strong></td>
                                                                                        <td align="left" class="listingtablestyleB">
                                                                                        <?php echo $row_paypal['paypal_reasoncode']?></td>
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                </table>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        </tr>
                                                                        <?php
									if($db->num_rows($ret_check_details)>0 and $flag_cheque==1)
									{
									?>
									<tr>
										<td colspan="2">
										<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td class="listingtableheader"  colspan="2" align="left"><b>Cheque Details</b></td>
										</tr>
										<? if($row_check_details['cheque_date']!='')
										{?>
										<tr>
											<td class="listingtablestyleB" width="35%" align="left" ><b>Date</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_check_details['cheque_date']?></td>
										</tr>
										<? }
										 if($row_check_details['cheque_number']!='')
										{?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Number</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_check_details['cheque_number']?></td>
										</tr>
										<? }
										 if($row_check_details['cheque_bankname']!='')
										 {?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Bank Name</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_check_details['cheque_bankname']?></td>
										</tr>
										<? }
										if($row_check_details['cheque_branchdetails']!='')
										{?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Branch Details</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_check_details['cheque_branchdetails']?></td>
										</tr>
										<? }?>
										</table>
										</td>
									</tr>
									<?
									 } 
									
									
									if($db->num_rows($ret_payment_details)>0 and $flag_pay ==1)
									{
									   if($row_payment_details['card_number']!='')
									   {
											if($row_payment_details['card_encrypted']==1)
												 $cc = base64_decode(base64_decode($row_payment_details['card_number']));
											else
												$cc = $row_payment_details['card_number'];
													$len	= (strlen($cc)-4);
													$cc 	= substr($cc,-4);
													for($i=0;$i<$len;$i++)
													{
														$ccs .='x';
													}
													$cc		= $ccs.$cc;
										}		
									?>
									<tr>
										<td colspan="2">
										<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td class="listingtableheader"  colspan="2" align="left"><b>Payment Details</b></td>
										</tr>
										<? if($row_payment_details['card_type']!=''){?>
										<tr>
											<td class="listingtablestyleB"  width="35%" align="left"><b>Card Type</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['card_type']?></td>
										</tr>
										<? }
										if($row_payment_details['card_number']!=''){?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Card Number</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $cc?></td>
										</tr>
										<? }
										if($row_payment_details['name_on_card']!=''){?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Name</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['name_on_card']?></td>
										</tr>
										<? }
										if($row_payment_details['sec_code']!='' and $row_payment_details['sec_code']!=0){?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Security Code</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['sec_code'];?></td>
										</tr>
										<? }
										if($exp_date!='')
										{?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Expiry Date</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $exp_date?></td>
										</tr>
										<? }if($row_payment_details['issue_number']!='' and $row_payment_details['issue_number']!=0){?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Issue Number</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['issue_number']?></td>
										</tr>
										<? }
										if($issue_date!='')
										{?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Issue Date</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $issue_date?></td>
										</tr>
										<?
											}
											if($row_payment_details['vendorTxCode']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left" width="35%"><b>Vendor TxCode</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['vendorTxCode']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['protStatus']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Prot Status</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['protStatus']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['protStatusDetail']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Prot Status Detail</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['protStatusDetail']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['vPSTxId']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>VPS TxId</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['vPSTxId']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['securityKey']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Security Key</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['securityKey']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['txAuthNo']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Tx Auth No</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['txAuthNo']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['txType']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Tx Type</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['txType']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['avscv2']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>AVSCV2</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['avscv2']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['cavv']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>CAVV</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['cavv']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['3dsecurestatus']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>3dsecure Status</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['3dsecurestatus']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['acsurl']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>ACS URL</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['acsurl']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['pareq']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>Pareq</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['pareq']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['md']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left"><b>MD</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['md']?></td>
											</tr>
										<?php
											}
											if($row_payment_details['google_checkoutid']!='')
											{?>
											<tr>
												<td class="listingtablestyleB" align="left" width="35%"><b>Google Checkout Id</b></td>
												<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['google_checkoutid']?></td>
											</tr>
										<?php
											}
											if($row_payondetails['pay_paymentmethod'] == 'WORLD_PAY')
											{
												if($row_payment_details['worldpay_transid']!='')
												{?>
												<tr>
													<td class="listingtablestyleB" align="left" width="35%"><b>Worldpay Transaction Id</b></td>
													<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['worldpay_transid']?></td>
												</tr>
											<?php
												}
											}	
											if($row_payondetails['pay_paymentmethod'] == 'HSBC')
											{
												if($row_payment_details['hsbc_cpiresultcode']!='')
												{?>
													<tr>
														<td class="listingtablestyleB" align="left" width="35%"><b>HSBC Cpi Result Code</b></td>
														<td  class="listingtablestyleB" align="left">:&nbsp;<? echo $row_payment_details['hsbc_cpiresultcode']?></td>
													</tr>
										<?php
												}
											}
										?>
										</table>
										</td>
									</tr>
								 <? }	
								 if($row_payondetails['pay_paystatus_changed_by']!=0)
									{
									?>
									<tr>
										<td colspan="2">
										<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tr>
											<td class="listingtableheader"  colspan="2" align="left"><b>Payment Approved Details</b></td>
										</tr>
										<? if($row_payondetails['pay_paystatus_changed_by']!=0)
										{?>
										<tr>
											<td class="listingtablestyleB" width="35%" align="left"><b>Approved By</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo getConsoleUserName($row_payondetails['pay_paystatus_changed_by'])?></td>
										</tr>
										<? }
										 if($row_payondetails['pay_paystatus_changed_paytype']!='')
										{?>
										<tr>
											<td class="listingtablestyleB" align="left"><b>Payment Type</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo getpaymentstatus_Name($row_payondetails['pay_paystatus_changed_paytype'])?></td>
										</tr>
										<? }
										 if($row_payondetails['pay_paystatus_changed_details']!='')
										 {?>
										<tr>
											<td class="listingtablestyleB" align="left" valign="top"><b>Details</b></td>
											<td  class="listingtablestyleB" align="left">:&nbsp;<? echo stripslashes(nl2br($row_payondetails['pay_paystatus_changed_details']))?></td>
										</tr>
										<? }
										?>
										</table>
										</td>
									</tr>
									<?
									 } 
									 ?>	
								</table>
						<?php		
						if($row_payondetails['pay_paymentmethod'] == 'NOCHEX')
						{
								$sql_nochex = "SELECT nochex_transaction_id, nochex_transaction_date, nochex_order_id, nochex_amount, 
								 					nochex_from_email , nochex_to_email, nochex_security_key, nochex_status 
								 				FROM 
													order_payonaccount_payment 
												WHERE 
													order_payonaccount_pay_id = ".$row_payondetails['pay_id']." 
												LIMIT 
													1";
								$ret_nochex = $db->query($sql_nochex);
								if($db->num_rows($ret_nochex))
								{
									$row_nochex = $db->fetch_array($ret_nochex);
				?>
					
								<table width="100%" cellpadding="1" cellspacing="1" border="0">
								<?php /*?><tr>
									<td align="left" colspan="2" class="shoppingcartheader">
									NoChex Return Details</td>
								</tr><?php */?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Transaction Id</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_transaction_id']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Transaction Date</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_transaction_date']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Payment Id</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_order_id']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Amount</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_amount']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Your Email</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_to_email']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Customer Email</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_from_email']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Security Key</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_security_key']?></td>
								</tr>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Status</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_nochex['nochex_status']?></td>
								</tr>
								</table>
								
					
			<?php
							}
						}
						if($row_payondetails['pay_paymentmethod'] == 'REALEX')
						{
								$sql_nochex = "SELECT  realex_timestamp, realex_result, realex_orderid, realex_message, realex_authcode,
												realex_passref, realex_md5hash 
								 				FROM 
													order_payonaccount_payment 
												WHERE 
													order_payonaccount_pay_id = ".$row_payondetails['pay_id']." 
												LIMIT 
													1";
								$ret_nochex = $db->query($sql_nochex);
								if($db->num_rows($ret_nochex))
								{
									$row_nochex = $db->fetch_array($ret_nochex);
				?>
					
								<table width="100%" cellpadding="1" cellspacing="1" border="0">
								<?php 
								//if($row_pay['realex_timestamp'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Time Stamp</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_timestamp'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_result'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Result</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_result'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_orderid'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Order Id</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_orderid'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_message'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Message</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_message'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_authcode'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Auth Code</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_authcode'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_passref'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Pass Ref</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_passref'])?></td>
								</tr>
								<?php
								}
								if($row_pay['realex_md5hash'])
								{
								?>
								<tr>
									<td align="left" width="25%" class="listingtablestyleB">
									<strong>Hash</strong>		</td>
									<td align="left" class="listingtablestyleB">
									<?php echo stripslashes($row_pay['realex_md5hash'])?></td>
								</tr>
								<?php
								}
								?>
								</table>
			<?php
							}
						}
			?>			
							</td>
						</tr>
						
			<?php
						
						
						}//End of checking any field
						
					}
					?>
					<?php
				}
				else
				{
			?>
					<tr>
             		 <td  align="center" class="redtext" colspan="<?php echo $colspan?>">
					 	Sorry!! no unbilled transactions found..</td>
				  </tr>
			<?php	
				}
				
			?>
          </table></td>
        </tr>
		</table>	
		</div>
		  </td>
		</tr>
  </table>
</form>	  

