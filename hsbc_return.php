<?php
require("functions/functions.php");
require("includes/session.php");
require("includes/urls.php");
require("includes/price_display.php");
require("config.php");
$sess_id	= session_id();
define('MODULE_PAYMENT_HSBC_TEXT_ERROR1', 'You have cancelled the transaction.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR2', 'The processor declined the transaction for an unknown reason.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR3', 'The transaction was declined because of a problem with the card. For example, an invalid card number or expiration date was specified.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR4', 'The processor did not return a response.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR5', 'The amount specified in the transaction was either too high or too low for the processor.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR6', 'The specified currency is not supported by either the processor or the card.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR7', 'The order is invalid because the order ID is a duplicate.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR8', 'The transaction was rejected by FraudShield.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR9', 'The transaction was placed in Review state by FraudShield.1');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR10', 'The transaction failed because of invalid input data.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR11', 'The transaction failed because the CPI was configured incorrectly.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR12', 'The transaction failed because the Storefront was configured incorrectly.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR13', 'The connection timed out.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR14', 'The transaction failed because your browser refused a cookie.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR15', 'Your browser does not support 128-bit encryption.');
define('MODULE_PAYMENT_HSBC_TEXT_ERROR16', 'The CPI cannot communicate with the Secure ePayment engine.');

$CpiResultsCode	= $_REQUEST['CpiResultsCode'];
$order_id 			= $_REQUEST['OrderId'];
$pass_type			= $_REQUEST['pass_typ'];
//$CpiResultsCode='0'	;

if ($order_id=='')
	exit;
if ($CpiResultsCode=='0' or $CpiResultsCode=='9')
{
	$stat = 'succ';
	if($CpiResultsCode=='0')
		$payStat = 'Paid';
	else
		$payStat = 'FRAUD_REVIEW';//Fraud rule review check';

	if($pass_type=='ord') // case of order
	{
		$update_array						= array();
		$update_array['order_paystatus']	= add_slash($payStat);
		$update_array['order_status']		= 'NEW';
		$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
		
		// Check whether there exists an entry in order_payment_main  table for current order id
		$sql_check = "SELECT orders_order_id 
								FROM 
									order_payment_main 
								WHERE 
									orders_order_id = $order_id 
									AND sites_site_id=$ecom_siteid 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check))
		{
			// case entry already exists
			$update_sql = "UPDATE order_payment_main 
										SET 
											order_googletransId = '".$CpiResultsCode."' 
										WHERE 
											orders_order_id = ".$order_id."
											AND sites_site_id = $ecom_siteid 
										LIMIT 1";
			$db->query($update_sql);
		}
		else
		{
			$insert_sql = "INSERT INTO order_payment_main 
									SET 
										orders_order_id = $order_id,
										order_googletransId = '".$CpiResultsCode."' ,
										sites_site_id=$ecom_siteid";
			$db->query($insert_sql);							
		}
		
		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($order_id);
		// send the mails if required
		send_RequiredOrderMails($order_id,'Payment Confirmed');
		
		/*$update_array						= array();
		$update_array['cart_error_msg_ret']	= '';
		$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));*/

		//Clearing the cart
		clear_cart($sess_id);

		// Clear sticky cart variables;
		clear_session_var("cart_total");
		clear_session_var("cart_total_items");

		/*echo "
			<script type='text/javascript'>
				window.location = 'http://".$ecom_hostname."/checkout_success".$order_id.".html';
			</script>
			";*/
		$succ = "checkout_success".$order_id.".html";
		echo "
			<script type='text/javascript'>
				window.location = '".url_link($succ,1)."';
			</script>
			";	
		exit;
	}
	elseif($pass_type=='voucher') // case of voucher
	{
		$voucher_id = $order_id;
		if($payStat=='Paid' or $payStat=='FRAUD_REVIEW') // case if payment is success
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".add_slash($payStat)."',
								voucher_activatedon = curdate(),
								voucher_expireson	= DATE_ADD(curdate(),INTERVAL voucher_activedays DAY)
							WHERE
								voucher_id = ".$voucher_id."
							LIMIT
								1";
			$db->query($sql_update);
			// Sending mails which are not send yet
			send_RequiredVoucherMails($voucher_id,'Payment Confirmed');
		}
		else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".add_slash($payStat)."' 
							WHERE
								voucher_id = ".$voucher_id."
							LIMIT
								1";
			$db->query($sql_update);

		}
		// Check whether an entry exists in gift_vouchers_payment table for current gift voucher
		$sql_check = "SELECT gift_vouchers_voucher_id
						FROM
							gift_vouchers_payment
						WHERE
							gift_vouchers_voucher_id = ".$voucher_id."
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the google_checkoutid only
		{
			$update_array								= array();
			$update_array['hsbc_cpiresultcode']			= add_slash($CpiResultsCode);
			$db->update_from_array($update_array,'gift_vouchers_payment',array('gift_vouchers_voucher_id'=>$voucher_id));
		}
		else // case no record exists. so insert the voucher id and google_checkoutid
		{
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
			$insert_array['hsbc_cpiresultcode']			= add_slash($CpiResultsCode);
			$db->insert_from_array($insert_array,'gift_vouchers_payment');
		}
			// Clearing the voucher cart
			clear_VoucherCart($sess_id);
			/*echo	"
					<script type='text/javascript'>
						window.location = 'http://$ecom_hostname/voucher_success".$voucher_id.".html';
					</script>
					";*/
			$succ = "voucher_success".$voucher_id.".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";		
			exit;
	}
	elseif($pass_type=='payonaccount') // case of payonaccount
	{
		
		$pay_id = $order_id;
		/*if($payStat=='Paid' or $payStat=='FRAUD_REVIEW') // case if payment is success
		{
			$sql_update = "UPDATE order_payonaccount_pending_details
							SET
								pay_paystatus='".add_slash($payStat)."' 
							WHERE
								pendingpay_id = ".$pay_id."
							LIMIT
								1";
			$db->query($sql_update);
			
			// Sending mails which are not send yet
			//send_RequiredVoucherMails($voucher_id,'Payment Confirmed');
		}
		else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE order_payonaccount_pending_details 
									SET
										pay_paystatus='".add_slash($payStat)."' 
									WHERE
										pendingpay_id = ".$pay_id."
									LIMIT
										1";
			$db->query($sql_update);

		}*/
		
		// Check whether an entry exists in order_payonaccount_pending_details_payment table for current payonaccount payment
		$sql_check = "SELECT order_payonaccount_pendingpay_id  
								FROM
									order_payonaccount_pending_details_payment  
								WHERE 
									order_payonaccount_pendingpay_id = ".$pay_id." 
								LIMIT
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the google_checkoutid only
		{
			$update_array											= array();
			$update_array['hsbc_cpiresultcode']			= add_slash($CpiResultsCode);
			$db->update_from_array($update_array,'order_payonaccount_pending_details_payment',array('order_payonaccount_pendingpay_id'=>$pay_id));
		}
		else // case no record exists. so insert the pay id and google_checkoutid
		{
			$insert_array															= array();
			$insert_array['order_payonaccount_pendingpay_id']		= $pay_id;
			$insert_array['hsbc_cpiresultcode']								= add_slash($CpiResultsCode);
			$db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment');
		}
		
		//Post payment success operation
		if($payStat=='Paid' or $payStat=='FRAUD_REVIEW') // case if payment is success
		{
			$org_pay_id = do_PostPayonAccountSuccessOperations($pay_id );
			
			// Clearing the payonaccount cart
			clear_PayonAccountCart($sess_id);
			set_session_var('gateway_payonaccount_id',0);
		}	
		if(!$org_pay_id or $ord_pay_id==0) // case if insert id is not obtained there
		{
			// Check whether there exists a record with the current  temp pay id in order_payonaccount_details table
			$sql_check = "SELECT pay_id 
									FROM 
										order_payonaccount_details 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND pay_temp_id = $pay_id 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$row_check = $db->fetch_array($ret_check);
				$org_pay_id = $row_check['pay_id'];
			}
		}
		$succ = "payonaccount_success".$org_pay_id.".html";
		echo	"
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
		exit;					
	}
}
else
{
	
	switch($CpiResultsCode)
	{
			case 1: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR1; break;
			case 2: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR2; break;
			case 3: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR3; break;
			case 4: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR4; break;
			case 5: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR5; break;
			case 6: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR6; break;
			case 7: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR7; break;
			case 8: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR8; break;
			case 9: $error  .= MODULE_PAYMENT_HSBC_TEXT_ERROR9; break;
			case 10: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR10; break;
			case 11: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR11; break;
			case 12: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR12; break;
			case 13: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR13; break;
			case 14: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR14; break;
			case 15: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR15; break;
			case 16: $error .= MODULE_PAYMENT_HSBC_TEXT_ERROR16; break;

	}
	if($pass_type=='ord')
	{
		// Calling function to delete the order details if payment is a failure
		deleteOrder_on_failure($order_id);
		// Updating the cart table with the error message
		$update_array						= array();
		$update_array['cart_error_msg_ret']	= $error;
		$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
		$fail = "checkout_failed.html?error=$error";
		echo "
				<script type='text/javascript'>
					window.location = '".url_link($fail,1)."';
				</script>
				";
		
		exit;
	}
	elseif($pass_type=='voucher')
	{
		$voucher_id = $order_id;
		// Delete gift voucher details
		deleteVoucher_on_failure($voucher_id);

		// Redirect to voucher failure page;
		$fail = "voucher_failed.html?error=$error";
		echo "
				<script type='text/javascript'>
					window.location = '".url_link($fail,1)."';
				</script>
				";
		exit;
	}
	elseif($pass_type=='payonaccount')
	{
		$pay_id = $order_id;
		// Delete payonaccount details
		deletePayonAccount_on_failure($pay_id);
		// Redirect to voucher failure page;
		$fail = "payonaccount_failed.html?error=$error";
		echo "
				<script type='text/javascript'>
					window.location = '".url_link($fail,1)."';
				</script>
				";
		exit;
	}
	exit;
}
?>