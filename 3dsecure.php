<?php
if($_REQUEST['MD'] == '' or $_REQUEST['PaRes'] == '') {
	redirectIllegal();
	exit;
}
$sess_id = $_REQUEST['sessionID'];
require("includes/payment.php");

if ($ecom_testing==1)
	$mode			= 'testing';
else
	$mode			= 'live';

$payData["mode"] 	= $mode;

$p 					= new protxPayment3DSecure($_REQUEST['MD'],$_REQUEST['PaRes']);
$p->set_mode($mode);
$response 			= $p->send();
$order_id 			= $_REQUEST['order_id'];
$pass_type			= $_REQUEST['pass_typ'];
if ($pass_type=='ord') // Case of coming for order
{
	$sql	= "SELECT order_txType
				FROM
					order_payment_main
				WHERE
					orders_order_id=".$_REQUEST['order_id']."
				LIMIT
					1";
	$res 	= $db->query($sql);
}
elseif($pass_type=='voucher') // case of coming for voucher
{
	$sql	= "SELECT txType as order_txType
				FROM
					gift_vouchers_payment
				WHERE
					gift_vouchers_voucher_id=".$_REQUEST['order_id']."
				LIMIT
					1";
	$res 	= $db->query($sql);
}
elseif($pass_type=='payonaccount') // case of coming for payonaccount
{
	$sql	= "SELECT txType as order_txType
				FROM
					order_payonaccount_pending_details_payment
				WHERE
					order_payonaccount_pendingpay_id=".$_REQUEST['order_id']."
				LIMIT
					1";
	$res 	= $db->query($sql);
}
if ($db->num_rows($res))
	$row 			= $db->fetch_array($res);
$result 			= 0;
$baseStatus 		= array_shift(split(" ",$response["Status"]));
switch($baseStatus)
{
case "OK":
	$result 		= 1;
	switch($row["order_txType"])//Calp Added
	{
		case 'PAYMENT':
			$payData["payStatus"] 		= "Paid";
		break;
		case 'DEFERRED':
			$payData["payStatus"] 		= "DEFERRED";
		break;
		case 'PREAUTH':
			$payData["payStatus"] 		= "PREAUTH";
		break;
	};//Calp End
break;

case "REGISTERED":
	$result = 1;
	switch($row["order_txType"])//Calp Added
	{
		case 'AUTHENTICATE':
			$payData["payStatus"] 		= "AUTHENTICATE";
		break;

	};//Calp End
break;

case "AUTHENTICATED":
	$result = 1;
	switch($row["order_txType"])//Calp Added
	{
		case 'AUTHENTICATE':
			$payData["payStatus"] 		= "AUTHENTICATE";
		break;

	};//Calp End
break;

default:
	$payData["error"] = $response["Status"] . ": " . $response["StatusDetail"];
	if ($pass_type=='ord') // Case of coming for order
	{
		//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
		$update_array						= array();
		$update_array['cart_error_msg_ret']	= addslashes($payData["error"]);
		$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
		// Calling function to delete the order details if payment is a failure
		deleteOrder_on_failure($_REQUEST['order_id']);
		/*echo "
				<script type='text/javascript'>
					window.location = 'http://".$ecom_hostname."/checkout_failed.html';
				</script>
				";*/
			echo "
				<script type='text/javascript'>
					window.location = '".url_link('checkout_failed.html',1)."';
				</script>
				";
	
			exit;
	}
	elseif($pass_type=='voucher')// case of gift voucher
	{
		// Delete gift voucher details
		deleteVoucher_on_failure($_REQUEST['order_id']);

		// Redirect to voucher failure page;
		echo "
				<script type='text/javascript'>
					window.location = '".url_link('voucher_failed.html',1)."';
				</script>
				";
		exit;
	}
	elseif($pass_type=='payonaccount')// case of payonaccount
	{
		// Delete payonaccount details
		deletePayonAccount_on_failure($_REQUEST['order_id']);

		// Redirect to voucher failure page;
		echo "
				<script type='text/javascript'>
					window.location = '".url_link('payonaccount_failed.html',1)."';
				</script>
				";
		exit;
	}
break;
};

if($result == 1)
{
	$payData["protStatus"] 					= $response["Status"];
	$payData["protStatusDetail"] 			= $response["StatusDetail"];
	$payData["VPSTxID"] 						= $response["VPSTxId"];
	$payData["SecurityKey"] 					= $response["SecurityKey"];
	$payData["TxAuthNo"] 					= $response["TxAuthNo"];
	$payData["AVSCV2"] 						= $response["AVSCV2"];
	$payData["CAVV"] 							= $response["CAVV"];
	$payData["3DSecureStatus"] 			= $response["3DSecureStatus"];
	/*if($ecom_siteid==84)
	{
		print_r($response);
		exit;
	}*/
	if ($pass_type=='ord') // Case of coming for order
	{
		$update_array						= array();
		$update_array['order_paystatus']	= $payData["payStatus"];
		$update_array['order_status']		= 'NEW';
		$db->update_from_array($update_array,'orders',array('order_id'=>$_REQUEST['order_id']));

		$update_array										= array();
		
		$update_array['order_protStatus']			= add_slash($payData["protStatus"]);
		$update_array['sites_site_id']				= $ecom_siteid;
		$update_array['order_protStatusDetail']	= add_slash($payData["protStatusDetail"]);
		$update_array['order_vPSTxId']			= add_slash($payData["VPSTxID"]);
		$update_array['order_securityKey']		= add_slash($payData["SecurityKey"]);
		$update_array['order_txAuthNo']			= add_slash($payData["TxAuthNo"]);
		$update_array['order_avscv2']				= add_slash($payData["AVSCV2"]);
		$update_array['order_cavv']					= add_slash($payData["CAVV"]);
		$update_array['order_3dsecurestatus']	= add_slash($payData["3DSecureStatus"]);
		$db->update_from_array($update_array,'order_payment_main',array('orders_order_id'=>$_REQUEST['order_id']));

		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($_REQUEST['order_id']);
		
		// Calling function to send any remaining order email
		send_RequiredOrderMails($_REQUEST['order_id']);
		//Clearing the cart
		clear_cart($sess_id);

		// Clear sticky cart variables;
		clear_session_var("cart_total");
		clear_session_var("cart_total_items");

		// call checkout successfull message
	/*	echo "
				<script type='text/javascript'>
					window.location = 'http://".$ecom_hostname."/checkout_success".$_REQUEST['order_id'].".html';
				</script>
				";
			exit;*/
				$succ = 'checkout_success'.$_REQUEST['order_id'].'.html';
				echo "
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
			exit;
	}
	elseif($pass_type=='voucher')
	{
		if($payData['payStatus']=='Paid') // case if payment is success
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".$payData['payStatus']."',
								voucher_activatedon = curdate(),
								voucher_expireson	= DATE_ADD(curdate(),INTERVAL voucher_activedays DAY)
							WHERE
								voucher_id = ".$_REQUEST['order_id']."
							LIMIT
								1";
			$db->query($sql_update);
			
			// Sending mails which are not send yet
			send_RequiredVoucherMails($_REQUEST['order_id']);
		}
		else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".$payData['payStatus']."'  
							WHERE
								voucher_id = ".$_REQUEST['order_id']." 
							LIMIT
								1";
			$db->query($sql_update);

		}
		/*$update_array							= array();
		$update_array['voucher_paystatus']		= $payData["payStatus"];
		$db->update_from_array($update_array,'gift_vouchers',array('voucher_id'=>$_REQUEST['order_id']));*/

		$update_array							= array();
		$update_array['protStatus']			= add_slash($payData["protStatus"]);
		$update_array['protStatusDetail']	= add_slash($payData["protStatusDetail"]);
		$update_array['vPSTxId']			= add_slash($payData["VPSTxID"]);
		$update_array['securityKey']		= add_slash($payData["SecurityKey"]);
		$update_array['txAuthNo']			= add_slash($payData["txAuthNo"]);
		$update_array['avscv2']				= add_slash($payData["AVSCV2"]);
		$update_array['cavv']				= add_slash($payData["CAVV"]);
		$update_array['3dsecurestatus']		= add_slash($payData["3DSecureStatus"]);
		$db->update_from_array($update_array,'gift_vouchers_payment',array('gift_vouchers_voucher_id'=>$_REQUEST['order_id']));


		// Send voucher email here
		// Redirect to voucher success page;
		// Clearing the voucher cart
			clear_VoucherCart($sess_id);
		 // will modify the following when doing the voucher section
			/*echo	"
					<script type='text/javascript'>
						window.location = 'http://$ecom_hostname/voucher_success".$_REQUEST['order_id'].".html';
					</script>
					";*/
			$succ = "voucher_success".$_REQUEST['order_id'].".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";		
			exit;
	}
	elseif($pass_type=='payonaccount')
	{
		/*$sql_update = "UPDATE order_payonaccount_pending_details 
							SET
								pay_paystatus='".$payData['payStatus']."' 
							WHERE
								pendingpay_id = ".$_REQUEST['order_id']." 
							LIMIT
								1";
		$db->query($sql_update);*/



		$update_array									= array();
		$update_array['protStatus']				= add_slash($payData["protStatus"]);
		$update_array['protStatusDetail']		= add_slash($payData["protStatusDetail"]);
		$update_array['vPSTxId']					= add_slash($payData["VPSTxID"]);
		$update_array['securityKey']				= add_slash($payData["SecurityKey"]);
		$update_array['txAuthNo']				= add_slash($payData["txAuthNo"]);
		$update_array['avscv2']					= add_slash($payData["AVSCV2"]);
		$update_array['cavv']						= add_slash($payData["CAVV"]);
		$update_array['3dsecurestatus']		= add_slash($payData["3DSecureStatus"]);
		$db->update_from_array($update_array,'order_payonaccount_pending_details_payment',array('order_payonaccount_pendingpay_id'=>$_REQUEST['order_id']));

		if($payData['payStatus']=='Paid') // case if payment is success
		{
			$org_pay_id = do_PostPayonAccountSuccessOperations($_REQUEST['order_id']);
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
		// Send voucher email here
		// Redirect to voucher success page;
		// Clearing the voucher cart
			clear_PayonAccountCart($sess_id);
		 // will modify the following when doing the voucher section
			/*echo	"
					<script type='text/javascript'>
						window.location = 'http://$ecom_hostname/payonaccount_success".$_REQUEST['order_id'].".html';
					</script>
					";*/
			$succ = "payonaccount_success".$org_pay_id.".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";		
			exit;
	}
}
?>
