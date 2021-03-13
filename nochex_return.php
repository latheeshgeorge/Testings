<?php
	/*#################################################################
	# Script Name 	: nochex_return.php
	# Description 	: Page which call the display the nochex return details
	# Coded by 		: Sny
	# Created on	: 03-Mar-2010
	# Modified by	: 
	# Modified On	: 	
	#################################################################*/
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");

// uncomment below to force a DECLINED response
//$_REQUEST['order_id'] = "1";
$order_id 			= $_REQUEST['order_id'];
$curmod_arr			= explode('__',$_REQUEST['custom']);
$to_email			= $_REQUEST['to_email'];
$amount				= $_REQUEST['amount'];
$curmod				= trim($curmod_arr[0]);
$sess_id			= trim($curmod_arr[1]);
$response 			= http_post("www.nochex.com", 80, "/nochex.dll/apc/apc", $_REQUEST);
$debug 				= "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n";
foreach($_REQUEST as $Index => $Value) 
	$debug .= "$Index -> $Value\r\n";
$debug .= "\r\nRESPONSE:\r\n$response";

if (!strstr($response, "AUTHORISED")) // payment is NOT Authorized
{
	//$msg = "APC was not AUTHORISED.\r\n\r\n$debug";
	switch($curmod)
	{
		case 'order':
			// Calling function to delete the order details if payment is a failure
			deleteOrder_on_failure($order_id);
		break;
		case 'voucher':
			$voucher_id = $order_id;
			// Delete gift voucher details
			deleteVoucher_on_failure($voucher_id);
		break;
		case 'payonaccount':
			$pay = $order_id;
			// Delete payonaccount details
			deletePayonAccount_on_failure($pay_id);
		break;
	}
}
else  // Payment Authorized
{
	//$msg = "APC was AUTHORISED.\r\n\r\n$debug";
	// Get the payment method id for nochex
	$sql_paymethod = "SELECT paymethod_id 
						FROM 
							payment_methods 
						WHERE 
							paymethod_key = 'NOCHEX' 
						LIMIT 
							1";
	$ret_paymethod = $db->query($sql_paymethod);
	if($db->num_rows($ret_paymethod))
	{
		$row_paymethod = $db->fetch_array($ret_paymethod);
		$sql_paydet = "SELECT payment_method_details_id 
						FROM 
							payment_methods_details 
						WHERE 
							payment_methods_paymethod_id = ".$row_paymethod['paymethod_id']." 
							AND payment_methods_details_key ='NOCHEX_MERCHANT_ID' 
						LIMIT 
							1";	
		$ret_paydet = $db->query($sql_paydet);
		if($db->num_rowS($ret_paydet))
		{
			$row_paydet = $db->fetch_array($ret_paydet);
			$sql_paydata = "SELECT payment_methods_forsites_details_values 
								FROM 
									payment_methods_forsites_details 
								WHERE 
									payment_methods_details_payment_method_details_id =".$row_paydet['payment_method_details_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
			$ret_paydata = $db->query($sql_paydata);
			if($db->num_rows($ret_paydata))
			{
				$row_paydata = $db->fetch_array($ret_paydata);
			}
		}
	}
	/*if($row_paydata['payment_methods_forsites_details_values']!=$to_email) // case if merchant id set in current site is different from the one passed back to us
	{
		exit;
	}*/
	// what ever else you want to do
	switch($curmod)
	{
		case 'order':
			// Check whether order is valid
			$sql_check = "SELECT order_id,order_totalprice  
					FROM
						orders
					WHERE
						order_id = ".$order_id." 
						AND sites_site_id = $ecom_siteid 
					LIMIT
						1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)// Check whether the refering order exists in database
			{
				exit;
			}
			else
			{
				$row_check = $db->fetch_array($ret_check);
				if ($row_check['order_totalprice']!=$amount) // case if order amount is not correct
				{
					exit;		
				}	
			}
			// Updating the payment status to paid.
			$payStat 							= 'Paid';
			$update_array						= array();
			$update_array['order_paystatus']	= addslashes($payStat);
			$update_array['order_status']		= 'NEW';
			$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
			
			// Check whether an entry exists in order_payment_main table for current order id
			$sql_check = "SELECT orders_order_id 
							FROM 
								order_payment_main  
							WHERE 
								orders_order_id=$order_id 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);					
			if($db->num_rows($ret_check))
			{					
				$sql_update	= "UPDATE order_payment_main  
								SET 
									order_nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
									order_nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
									order_nochex_order_id='".addslashes($_REQUEST["order_id"])."',
									order_nochex_amount='".addslashes($_REQUEST["amount"])."',
									order_nochex_from_email='".addslashes($_REQUEST["from_email"])."',
									order_nochex_to_email='".addslashes($_REQUEST["to_email"])."',
									order_nochex_security_key='".addslashes($_REQUEST["security_key"])."',
									order_nochex_status='".addslashes($_REQUEST["status"])."' 
								WHERE 
									orders_order_id=$order_id 
								LIMIT 
									1";
				mysql_query($sql_update);
			}
			else
			{
				$sql_insert	= "INSERT INTO order_payment_main  
								SET 
									order_nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
									order_nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
									order_nochex_order_id='".addslashes($_REQUEST["order_id"])."',
									order_nochex_amount='".addslashes($_REQUEST["amount"])."',
									order_nochex_from_email='".addslashes($_REQUEST["from_email"])."',
									order_nochex_to_email='".addslashes($_REQUEST["to_email"])."',
									order_nochex_security_key='".addslashes($_REQUEST["security_key"])."',
									order_nochex_status='".addslashes($_REQUEST["status"])."', 
									orders_order_id=$order_id,
									sites_site_id=$ecom_siteid";
				mysql_query($sql_insert);
			}
			
			
			// Stock Decrementing section over here
			do_PostOrderSuccessOperations($order_id);
			
			// calling function to send any mails 
			send_RequiredOrderMails($order_id);
		break;
		case 'voucher':
			$voucher_id = $order_id;
			$payStat 	= 'Paid';
			if($payStat=='Paid') // case if payment is success
			{
				$sql_check = "SELECT voucher_value  
					FROM
						gift_vouchers
					WHERE
						voucher_id = ".$voucher_id." 
						AND sites_site_id = $ecom_siteid 
					LIMIT
						1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)// Check whether the refering voucher exists in database
				{
					exit;
				}
				else
				{
					$row_check = $db->fetch_array($ret_check);
					if ($row_check['voucher_value']!=$_REQUEST['amount']) // case if voucher amount is not correct
						exit;		
				}
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
				// Check whether an entry exists in gift_vouchers_payment table for current voucher id
				$sql_check = "SELECT payment_id  
								FROM 
									gift_vouchers_payment 
								WHERE 
									gift_vouchers_voucher_id=$voucher_id 
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);	
							
				if($db->num_rows($ret_check))
				{					
					$row_check = $db->fetch_array($ret_check);
					$sql_update	= "UPDATE gift_vouchers_payment 
									SET 
										voucher_nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
										voucher_nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
										voucher_nochex_order_id='".addslashes($_REQUEST["order_id"])."',
										voucher_nochex_amount='".addslashes($_REQUEST["amount"])."',
										voucher_nochex_from_email='".addslashes($_REQUEST["from_email"])."',
										voucher_nochex_to_email='".addslashes($_REQUEST["to_email"])."',
										voucher_nochex_security_key='".addslashes($_REQUEST["security_key"])."',
										voucher_nochex_status='".addslashes($_REQUEST["status"])."'
									WHERE 
										gift_vouchers_voucher_id=$voucher_id 
										AND payment_id=".$row_check['payment_id']." 
									LIMIT 
										1";
					mysql_query($sql_update);
				}
				else
				{
					$sql_insert	= "INSERT INTO gift_vouchers_payment 
									SET 
										voucher_nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
										voucher_nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
										voucher_nochex_order_id='".addslashes($_REQUEST["order_id"])."',
										voucher_nochex_amount='".addslashes($_REQUEST["amount"])."',
										voucher_nochex_from_email='".addslashes($_REQUEST["from_email"])."',
										voucher_nochex_to_email='".addslashes($_REQUEST["to_email"])."',
										voucher_nochex_security_key='".addslashes($_REQUEST["security_key"])."',
										voucher_nochex_status='".addslashes($_REQUEST["status"])."',
										gift_vouchers_voucher_id=$voucher_id";
					mysql_query($sql_insert);
				}
				
				
				// Sending mails which are not send yet
				send_RequiredVoucherMails($voucher_id);
			}
		break;
		case 'payonaccount':
			$pay_id = $order_id;
			$sql_check = "SELECT pay_amount   
					FROM
						order_payonaccount_pending_details 
					WHERE
						pendingpay_id = ".$pay_id." 
						AND sites_site_id = $ecom_siteid 
					LIMIT
						1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)// Check whether the refering payonaccount pending exists in database
			{
				exit;
			}
			else
			{
				$row_check = $db->fetch_array($ret_check);
				if ($row_check['pay_amount']!=$_REQUEST['amount']) // case if voucher amount is not correct
					exit;		
			}
			//Post payment success operation
			$org_pay_id= do_PostPayonAccountSuccessOperations($pay_id );
			// Check whether an entry exists in gift_vouchers_payment table for current voucher id
			$sql_check = "SELECT payment_id  
							FROM 
								order_payonaccount_payment   
							WHERE 
								 order_payonaccount_pay_id=$org_pay_id 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);					
			if($db->num_rows($ret_check))
			{					
				$row_check = $db->fetch_array($ret_check);
				$sql_update	= "UPDATE order_payonaccount_payment  
								SET 
									nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
									nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
									nochex_order_id='".addslashes($_REQUEST["order_id"])."',
									nochex_amount='".addslashes($_REQUEST["amount"])."',
									nochex_from_email='".addslashes($_REQUEST["from_email"])."',
									nochex_to_email='".addslashes($_REQUEST["to_email"])."',
									nochex_security_key='".addslashes($_REQUEST["security_key"])."',
									nochex_status='".addslashes($_REQUEST["status"])."' 
								WHERE 
									order_payonaccount_pendingpay_id=$typeid 
									AND payment_id=".$row_check['payment_id']." 
								LIMIT 
									1";
				mysql_query($sql_update);
			}
			else
			{
				$sql_insert	= "INSERT INTO order_payonaccount_payment  
								SET 
									nochex_transaction_id='".addslashes($_REQUEST["transaction_id"])."',
									nochex_transaction_date='".addslashes($_REQUEST["transaction_date"])."', 
									nochex_order_id='".addslashes($_REQUEST["order_id"])."',
									nochex_amount='".addslashes($_REQUEST["amount"])."',
									nochex_from_email='".addslashes($_REQUEST["from_email"])."',
									nochex_to_email='".addslashes($_REQUEST["to_email"])."',
									nochex_security_key='".addslashes($_REQUEST["security_key"])."',
									nochex_status='".addslashes($_REQUEST["status"])."', 
									order_payonaccount_pay_id=$org_pay_id";
				mysql_query($sql_insert);
			}
			
		break;
	};
}

function http_post($server, $port, $url, $vars)
{
	// get urlencoded vesion of $vars array
	$urlencoded = "";
	foreach ($vars as $Index => $Value)
	$urlencoded .= urlencode($Index ) . "=" . urlencode($Value) . "&";
	$urlencoded = substr($urlencoded,0,-1);
	$headers = "POST $url HTTP/1.0\r\n"
	. "Content-Type: application/x-www-form-urlencoded\r\n"
	. "Host: www.nochex.com\r\n"
	. "Content-Length: ". strlen($urlencoded) . "\r\n\r\n";
	$fp = fsockopen($server, $port, $errno, $errstr, 10);
	if (!$fp) return "ERROR: fsockopen failed.\r\nError no: $errno - $errstr";
	fputs($fp, $headers);
	fputs($fp, $urlencoded);
	$ret = "";
	while (!feof($fp)) $ret .= fgets($fp, 1024);
	fclose($fp);
	return $ret;
}
?>
