<?php
	/*#################################################################
	# Script Name 	: worldpay_return.php
	# Description 	: Page which call the display the worldpay return details
	# Coded by 		: Sny
	# Created on	: 25-Aug-2009
	# Modified by	: 
	# Modified On	: 	
	#################################################################*/
	require("functions/functions.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/urls.php");
	require("config.php");	
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	
$error 				= '';
$order_id 			= $_REQUEST['cartId'];
if (!is_numeric($order_id)) // check whether order id received in numeric
{
	redirectIllegal();
	exit;
}
if($_POST["M_ordertype"] == 'order') // Case of order
{
	// Check whether order is valid
	$sql_check = "SELECT order_id
					FROM
						orders
					WHERE
						order_id = ".$order_id."
					LIMIT
						1";
	$ret_check = $db->query($sql_check);
	if ($db->num_rows($ret_check)==0)// Check whether the refering order exists in database
	{
		redirectIllegal();
		exit;
	}
	$sess_id = $_POST['M_sessionID'];
	if ($_POST['transStatus'] == 'Y') // case if payment is successfull
	{
		// Updating the payment status to paid.
		$payStat 							= 'Paid';
		$update_array						= array();
		$update_array['order_paystatus']	= add_slash($payStat);
		$update_array['order_status']		= 'NEW';
		$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));

		// Check whether an entry exists in order_payment_main table for current order
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_main
						WHERE
							orders_order_id = $order_id
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the transid only
		{
			$update_array							= array();
			$update_array['order_googletransId']	= add_slash($_REQUEST["transId"]);
			$update_array['sites_site_id']			= $ecom_siteid;
			$db->update_from_array($update_array,'order_payment_main',array('orders_order_id'=>$order_id));
		}
		else // case no record exists. so insert the order id and google transid
		{
			$insert_array							= array();
			$insert_array['orders_order_id']		= $order_id;
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['order_googletransId']	= add_slash($_REQUEST["transId"]);
			$db->insert_from_array($insert_array,'order_payment_main');
		}
		
		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($order_id);
		
		// calling function to send any mails 
		send_RequiredOrderMails($order_id);

		//Clearing the cart
		clear_cart($sess_id);

		// Clear sticky cart variables;
		clear_session_var("cart_total");
		clear_session_var("cart_total_items");
		$succ = "checkout_success".$order_id.".html";
		
		//if ($cartHtml=="")
		{ 
			//require("themes/$ecom_themename/html/cartHtml.php");
			//$cartHtml= new cart_Html(); // Creating an object for the cart_Html class
			//$cartHtml->Show_CheckoutSuccess($order_id); // call the function to show the Checkout Success message
			$succ = $ecom_selfhttp.$ecom_hostname."/".$succ;
			echo '<center>
			<br><br><br><br>Please wait .. you are being redirected to the website ...
			<meta http-equiv="refresh" content="0;URL=\''.$succ.'\'" />  
			</center>';
		}
		
		/*echo "
			<script type='text/javascript'>
				window.location = '".url_link($succ,1)."';
			</script>
			";*/
	}
	else // Case if payment failed
	{
		// Calling function to delete the order details if payment is a failure
		deleteOrder_on_failure($order_id);
		/*echo "
			<script type='text/javascript'>
				window.location = '".url_link('checkout_failed.html',1)."';
			</script>
			";
		exit;*/
		//if ($cartHtml=="")
		{ 
			/*require("themes/$ecom_themename/html/cartHtml.php");
			$cartHtml= new cart_Html(); // Creating an object for the cart_Html class
			$cartHtml->Show_CheckoutFailed(); // call the function to show the Checkout Failure message
			*/
			$fail = $ecom_selfhttp.$ecom_hostname."/checkout_failed.html";
			echo '<center>
			<br><br><br><br>Please wait .. you are being redirected to the website ...
			<meta http-equiv="refresh" content="0;URL=\''.$fail.'\'" />  
			</center>';
		}
		
	}
	//End

}
else if($_POST['M_ordertype'] == 'voucher')
{
	if ($_POST['transStatus'] == 'Y')
	{
		$voucher_id = $order_id;
		$payStat = 'Paid';
		if($payStat=='Paid') // case if payment is success
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
			send_RequiredVoucherMails($voucher_id);
		}
		/*else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".add_slash($payStat)."'
							WHERE
								voucher_id = ".$voucher_id."
							LIMIT
								1";
			$db->query($sql_update);

		}*/
		$sess_id = $_POST['M_sessionID'];
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
				$update_array['worldpay_transid']			= add_slash($_POST["transId"]);
				$db->update_from_array($update_array,'gift_vouchers_payment',array('gift_vouchers_voucher_id'=>$voucher_id));
			}
			else // case no record exists. so insert the voucher id and google_checkoutid
			{
				$insert_array								= array();
				$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
				$insert_array['worldpay_transid']			= add_slash($_POST["transId"]);
				$db->insert_from_array($insert_array,'gift_vouchers_payment');
			}
		
			// Clearing the voucher cart
			clear_VoucherCart($sess_id);
			$succ = "voucher_success".$voucher_id.".html";
		 // will modify the following when doing the voucher section
			/*echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
			*/	
			//if ($giftvoucherHtml=="")
			{ 
				/*require("themes/$ecom_themename/html/giftvoucherHtml.php");
				$giftvoucherHtml					= new giftvoucher_Html(); // Creating an object for the giftvoucher_Html class
				$return_voucher_arr['voucher_id']	= $voucher_id;
				$giftvoucherHtml->Show_giftvoucherPreview($return_voucher_arr,'pay_succ',true);	*/
				
				$succ = $ecom_selfhttp.$ecom_hostname."/".$succ;
				echo '<center>
				<br><br><br><br>Please wait .. you are being redirected to the website ...
				<meta http-equiv="refresh" content="0;URL=\''.$succ.'\'" />  
				</center>';
			}
	}
	else
	{
		$voucher_id = $order_id;
		// Delete gift voucher details
		deleteVoucher_on_failure($voucher_id);

		// Redirect to voucher failure page;
		/*echo "
				<script type='text/javascript'>
					window.location = '".url_link('voucher_failed.html',1)."';
				</script>
				";
		exit;*/
		if ($giftvoucherHtml=="")
		{ 
			/*require("themes/$ecom_themename/html/giftvoucherHtml.php");
			$giftvoucherHtml					= new giftvoucher_Html(); // Creating an object for the giftvoucher_Html class
			$giftvoucherHtml->Show_VoucherFailed();*/
			
			$fail = $ecom_selfhttp.$ecom_hostname."/voucher_failed.html";
			echo '<center>
			<br><br><br><br>Please wait .. you are being redirected to the website ...
			<meta http-equiv="refresh" content="0;URL=\''.$fail.'\'" />  
			</center>';
		}
		
	}
}
else if($_POST['M_ordertype'] == 'payonaccount')
{
	if ($_POST['transStatus'] == 'Y')
	{
		$pay_id = $order_id;
		$payStat = 'Paid';
		if($payStat=='Paid') // case if payment is success
		{
			/*$sql_update = "UPDATE order_payonaccount_pending_details
							SET
								pay_paystatus='".add_slash($payStat)."' 
							WHERE
								pendingpay_id = ".$pay_id."
							LIMIT
								1";
			$db->query($sql_update);*/
			// Sending mails which are not send yet
			//send_RequiredVoucherMails($voucher_id);
		}
		/*else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".add_slash($payStat)."'
							WHERE
								voucher_id = ".$voucher_id."
							LIMIT
								1";
			$db->query($sql_update);

		}*/
		$sess_id = $_POST['M_sessionID'];
			// Check whether an entry exists in gift_vouchers_payment table for current gift voucher
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
				$update_array								= array();
				$update_array['worldpay_transid']			= add_slash($_POST["transId"]);
				$db->update_from_array($update_array,'order_payonaccount_pending_details_payment',array('order_payonaccount_pendingpay_id'=>$pay_id));
			}
			else // case no record exists. so insert the voucher id and google_checkoutid
			{
				$insert_array											= array();
				$insert_array['order_payonaccount_pendingpay_id']		= $pay_id;
				$insert_array['worldpay_transid']						= add_slash($_POST["transId"]);
				$db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment');
			}
		
			//Post payment success operation
			$org_pay_id= do_PostPayonAccountSuccessOperations($pay_id );
			// Clearing the payonaccount cart
			clear_PayonAccountCart($sess_id);
			
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
		 // will modify the following when doing the voucher section
			/*echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
			*/
			//if ($payonaccountHtml=="")
			{ 
				/*require("themes/$ecom_themename/html/payonaccountHtml.php");
				$payonaccountHtml			= new payonaccountHtml(); // Creating an object for the sitereview_Html class
				$return_pay_arr['pay_id']	= $org_pay_id;
				$payonaccountHtml->Show_payonaccountPreview($return_pay_arr,'pay_succ',true);*/	
				
				$succ = $ecom_selfhttp.$ecom_hostname."/".$succ;
				
				echo '<center>
				<br><br><br><br>Please wait .. you are being redirected to the website ...
				<meta http-equiv="refresh" content="0;URL=\''.$succ.'\'" />  
				</center>';
			}		
	}
	else
	{
		$sess_id = $_POST['M_sessionID'];
		$pay = $order_id;
		
		// Delete payonaccount details
		deletePayonAccount_on_failure($pay_id);
		
		// Redirect to payonaccount failure page;
		/*echo "
				<script type='text/javascript'>
					window.location = '".url_link('payonaccount_failed.html',1)."';
				</script>
				";
		exit;*/
		//if ($payonaccountHtml=="")
		{ 
			/*require("themes/$ecom_themename/html/payonaccountHtml.php");
			$payonaccountHtml			= new payonaccountHtml(); // Creating an object for the sitereview_Html class
			$payonaccountHtml->Show_payonaccountFailed();	*/
			$fail = $ecom_selfhttp.$ecom_hostname."/payonaccount_failed.html";
			echo '<center>
			<br><br><br><br>Please wait .. you are being redirected to the website ...
			<meta http-equiv="refresh" content="0;URL=\''.$fail.'\'" />  
			</center>';
		}
	}
}

?>
