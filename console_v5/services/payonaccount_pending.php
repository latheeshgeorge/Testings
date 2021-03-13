<?php
// Array with month names to be  used in various sections of payon account
$month_arr			= array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
if($_REQUEST['fpurpose']=='')
{
	include("includes/payonaccount_pending/list_payonaccount_pending_customers.php");
}
elseif($_REQUEST['fpurpose']=='pending_details')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$pending_id  = $_REQUEST['pending_id'];
	$customer_id = $_REQUEST['customer_id'];
	include("includes/payonaccount_pending/pending_details.php");
}
elseif($_REQUEST['fpurpose']=='pay_approve') 
{
		$pending_id  = $_REQUEST['pending_id'];
		$customer_id = $_REQUEST['customer_id'];
		
		$pend_sql = "SELECT * 	
								FROM 
									order_payonaccount_pending_details 
								WHERE 
									pendingpay_id='".$pending_id."' 
									AND sites_site_id='$ecom_siteid' 
								LIMIT 
									1";
		$pend_res = $db->query($pend_sql);	
		if ($db->num_rows($pend_res))
		{
			$pend_row = $db->fetch_array($pend_res);
			
			$insert_array										= array();
			$insert_array['pay_date'] 							= 'now()';
			$insert_array['sites_site_id'] 						= $pend_row['sites_site_id'];
			$insert_array['customers_customer_id'] 				= $pend_row['customers_customer_id'];
			$insert_array['pay_amount'] 						= $pend_row['pay_amount'];
			$insert_array['pay_transaction_type'] 				= $pend_row['pay_transaction_type'];
			$insert_array['pay_details'] 							= addslashes(stripslashes($pend_row['pay_details']));
			$insert_array['pay_paystatus'] 						= $pend_row['pay_paystatus'];
			$insert_array['pay_paymenttype'] 					= $pend_row['pay_paymenttype'];
			$insert_array['pay_paymentmethod'] 					= $pend_row['pay_paymentmethod'];
			$insert_array['pay_paystatus_changed_by'] 			= $_SESSION['console_id'];
			$insert_array['pay_paystatus_changed_on'] 			= 'now()';
			$insert_array['pay_paystatus_changed_paytype'] 		= $pend_row['pay_paystatus_changed_paytype'];
			$insert_array['pay_additional_details'] 			= addslashes(stripslashes($pend_row['pay_additional_details']));
			$insert_array['pay_curr_rate'] 						= $pend_row['pay_curr_rate'];
			$insert_array['pay_curr_code'] 						= $pend_row['pay_curr_code'];
			$insert_array['pay_curr_symbol'] 					= $pend_row['pay_curr_symbol'];
			$insert_array['pay_curr_numeric_code'] 				= $pend_row['pay_curr_numeric_code'];
			
			$insert_array['pay_paystatus_changed_paytype'] 	= add_slash($_REQUEST['cbo_paymethod']);
			$insert_array['pay_paystatus_changed_details'] 		= add_slash($_REQUEST['pay_additional_details']);
			$db->insert_from_array($insert_array, 'order_payonaccount_details');
			
			$pay_id = $db->insert_id();

			// Decrement the used limit for current customer by the approved amount
			$update_cust = "UPDATE 
											customers 
										SET 
											customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - ".$pend_row['pay_amount']." 
										WHERE 
											customer_id = ".$pend_row['customers_customer_id']." 
											AND sites_site_id = $ecom_siteid 
											AND customer_payonaccount_usedlimit>=".$pend_row['pay_amount']." 
										LIMIT 
											1";
			$db->query($update_cust);
											
				$cr_sql = "SELECT * 
									FROM 
										order_payonaccount_pending_details_payment 
									WHERE 
										order_payonaccount_pendingpay_id='".$pending_id."' 
										LIMIT 1";
				$cr_res = $db->query($cr_sql);	
				if($db->num_rows($cr_res))
				{
					$cr_row = $db->fetch_array($cr_res);
					$insert_array															= array();
					$insert_array['order_payonaccount_pay_id'] 				= $pay_id;
					$insert_array['card_type'] 								= addslashes(stripslashes($cr_row['card_type']));
					$insert_array['card_number'] 							= addslashes(stripslashes($cr_row['card_number']));
					$insert_array['name_on_card'] 							= addslashes(stripslashes($cr_row['name_on_card']));
					$insert_array['sec_code'] 								= addslashes(stripslashes($cr_row['sec_code']));
					$insert_array['expiry_date_m'] 							= addslashes(stripslashes($cr_row['expiry_date_m']));
					$insert_array['expiry_date_y'] 							= addslashes(stripslashes($cr_row['expiry_date_y']));
					$insert_array['issue_number'] 							= addslashes(stripslashes($cr_row['issue_number']));
					$insert_array['issue_date_m'] 							= addslashes(stripslashes($cr_row['issue_date_m']));
					$insert_array['issue_date_y'] 							= addslashes(stripslashes($cr_row['issue_date_y']));
					$insert_array['vendorTxCode'] 							= addslashes(stripslashes($cr_row['vendorTxCode']));
					$insert_array['protStatus'] 							= addslashes(stripslashes($cr_row['protStatus']));
					$insert_array['protStatusDetail'] 						= addslashes(stripslashes($cr_row['protStatusDetail']));
					$insert_array['vPSTxId'] 								= addslashes(stripslashes($cr_row['vPSTxId']));
					$insert_array['securityKey'] 							= addslashes(stripslashes($cr_row['securityKey']));
					$insert_array['txAuthNo'] 								= addslashes(stripslashes($cr_row['txAuthNo']));
					$insert_array['txType'] 								= addslashes(stripslashes($cr_row['txType']));
					$insert_array['avscv2'] 								= addslashes(stripslashes($cr_row['avscv2']));
					$insert_array['cavv'] 									= addslashes(stripslashes($cr_row['cavv']));
					$insert_array['3dsecurestatus'] 						= addslashes(stripslashes($cr_row['3dsecurestatus']));
					$insert_array['acsurl'] 								= addslashes(stripslashes($cr_row['acsurl']));
					$insert_array['pareq'] 									= addslashes(stripslashes($cr_row['pareq']));
					$insert_array['md'] 									= addslashes(stripslashes($cr_row['md']));
					$insert_array['orgtxType'] 								= addslashes(stripslashes($cr_row['orgtxType']));
					$insert_array['card_encrypted'] 						= addslashes(stripslashes($cr_row['card_encrypted']));
					$insert_array['google_checkoutid'] 						= addslashes(stripslashes($cr_row['google_checkoutid']));
					$insert_array['worldpay_transid'] 						= addslashes(stripslashes($cr_row['worldpay_transid']));
					$insert_array['hsbc_cpiresultcode'] 					= addslashes(stripslashes($cr_row['hsbc_cpiresultcode']));
					$db->insert_from_array($insert_array, 'order_payonaccount_payment');
					
					$delsql = "DELETE FROM 
									order_payonaccount_pending_details_payment 
								WHERE 
									order_payonaccount_pendingpay_id='".$pending_id."' 
								LIMIT 
									1";
					$db->query($delsql);		
				}		
				
				$cr_sql = "SELECT * FROM order_payonaccount_pending_details_cheque_details 
										WHERE order_payonaccount_pending_details_pending_id='".$pending_id."'";
				$cr_res = $db->query($cr_sql);	
				if($db->num_rows($cr_res))
				{
					$cr_row = $db->fetch_array($cr_res);
					$insert_array										= array();
					$insert_array['order_payaccount_cheque_pay_id'] 	= $pay_id;
					$insert_array['cheque_date'] 						= addslashes(stripslashes($cr_row['cheque_date']));
					$insert_array['cheque_number'] 						= addslashes(stripslashes($cr_row['cheque_number']));
					$insert_array['cheque_bankname'] 					= addslashes(stripslashes($cr_row['cheque_bankname']));
					$insert_array['cheque_branchdetails'] 				= addslashes(stripslashes($cr_row['cheque_branchdetails']));
					$db->insert_from_array($insert_array, 'order_payonaccount_cheque_details');
				
					$delsql = "DELETE FROM 
									order_payonaccount_pending_details_cheque_details 
								WHERE 
									order_payonaccount_pending_details_pending_id='".$pending_id."' 
								LIMIT 
									1";
					$db->query($delsql);		
				}	
			
				$delsql = "DELETE FROM 
								order_payonaccount_pending_details 
							WHERE 
								pendingpay_id='".$pending_id."' 
							LIMIT 
							1";
				$db->query($delsql);	
			// Calling function to check and send payment approval email to client
			send_PayonAccountApproval($pay_id);
			
			$alert = " Payment Approved Sucessfully ";
		}	
		else
			$alert = 'Sorry payment details not found';
			
		echo 
		"
			<script type='text/javascript'>window.location = 'home.php?request=payonaccount_pending&m=1&sort_by=".$_REQUEST['sort_by']."&records_per_page=".$_REQUEST['records_per_page']."&pay_todate=".$_REQUEST['pay_todate']."&pay_fromdate=".$_REQUEST['pay_fromdate']."&txt_name=".$_REQUEST['txt_name']."&sel_pay_type=".$_REQUEST['sel_pay_type']."&chk_incomplete_pend=".$_REQUEST['chk_incomplete_pend']."'</script>
		";
		exit;
}
elseif ($_REQUEST['fpurpose']=='do_pending_delete')
{
	if(count($_REQUEST['checkbox']))
	{
		for($i=0;$i<count($_REQUEST['checkbox']);$i++)
		{
			// Deleting from order_payonaccount_pending_details_payment table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details_payment  
							WHERE 
								order_payonaccount_pendingpay_id = ".$_REQUEST['checkbox'][$i]." 
							LIMIT 
								1";
			$db->query($del);
			// Deleting from order_payonaccount_pending_details_payment table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details_cheque_details   
							WHERE 
								order_payonaccount_pending_details_pending_id = ".$_REQUEST['checkbox'][$i]." 
							LIMIT 
								1";
			$db->query($del);
			// Deleting from order_payonaccount_pending_details table 
			$del = "DELETE FROM 
								order_payonaccount_pending_details    
							WHERE 
								pendingpay_id = ".$_REQUEST['checkbox'][$i]." 
							LIMIT 
								1";
			$db->query($del);
		}	
		$alert = 'Pay On Account Pending Transactions deleted successfully';
	}
	else
	{
		$alert = 'Please select the pending transactions to be deleted';
			
	}
	include("includes/payonaccount_pending/list_payonaccount_pending_customers.php");
}

?>