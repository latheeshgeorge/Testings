<?php
/*#################################################################
# Script Name 	: realex_return.php
# Description 	: Page which call the display the realex return details
# Coded by 		: Sny
# Created on	: 09-Aug-2010
# Modified by	: Sny
# Modified On	: 10-Aug-2010	
#################################################################*/
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");	
$error 				= '';
$order_id			= trim($_REQUEST['OUR_ORDER_ID']);
$passtype			= trim($_REQUEST['OUR_PASS_TYPE']);
$sess_id			= trim($_REQUEST['OUR_SESS']);

$fp = fopen('testings/realex_new.txt','a+');
$rpass_vals = "Our Order id $order_id\t passtype $passtype\t session $sess_id\t timestamp $timestamp\t Result $result\t Order id $orderid\t message $message\t authcode $authcode\t pasref $pasref\t md5 $realexmd5"."\n";
fwrite($fp,$rpass_vals);
fclose($fp);

if (!is_numeric($order_id)) // check whether order id received in numeric
{
	exit;
}
$timestamp 			= $_REQUEST['TIMESTAMP'];
$result 			= $_REQUEST['RESULT'];
$orderid 			= $_REQUEST['ORDER_ID'];
$message 			= $_REQUEST['MESSAGE'];
$authcode 			= $_REQUEST['AUTHCODE'];
$pasref 			= $_REQUEST['PASREF'];
$realexmd5 			= $_REQUEST['MD5HASH'];

$fp = fopen('testings/response_realex.txt','a+');
$rpass_vals = "Our Order id $order_id\t passtype $passtype\t session $sess_id\t timestamp $timestamp\t Result $result\t Order id $orderid\t message $message\t authcode $authcode\t pasref $pasref\t md5 $realexmd5"."\n";
fwrite($fp,$rpass_vals);
fclose($fp);
// -------------------------------------------------------------
// Replace these with the values you receive from Realex Payments.If you have not yet received these values please contact us.
$sql_real = "SELECT paymethod_id 
				FROM 
					payment_methods 
				WHERE 
					paymethod_key = 'REALEX' 
				LIMIT 
					1";
$ret_real = $db->query($sql_real);
if($db->num_rows($ret_real))
{
	$row_real 		= $db->fetch_array($ret_real);
	$paymethod_id 	= $row_real['paymethod_id'];
	// Get the settings for REALEX for current website
	$sql_method = "SELECT a.payment_methods_details_key,b.payment_methods_forsites_details_values 
						FROM 
							payment_methods_details a,payment_methods_forsites_details b 
						WHERE 
							a.payment_methods_paymethod_id = $paymethod_id 
							AND a.payment_method_details_id = b.payment_methods_details_payment_method_details_id 
							AND b.sites_site_id = $ecom_siteid";
	$ret_method = $db->query($sql_method);
	if ($db->num_rows($ret_method))
	{
		while($row_method = $db->fetch_array($ret_method))
		{
			$paymethod_arr[$row_method['payment_methods_details_key']] = $row_method['payment_methods_forsites_details_values'];
		}
	}
}
$merchantid 		= $paymethod_arr['REALEX_MERCHANT_ID'];
$secret 			= $paymethod_arr['REALEX_SECRET_CODE'];
//---------------------------------------------------------------

//Below is the code for creating the digital signature using the md5 algorithm. 
//This digital siganture should correspond to the 
//one Realex Payments POSTs back to this script and can therefore be used to verify the message Realex sends back.
$tmp 		= "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
$md5hash 	= md5($tmp);
$tmp 		= "$md5hash.$secret";
$md5hash 	= md5($tmp);
//Check to see if hashes match or not
if ($md5hash != $realexmd5)
{
	$stat 	= 'ERR';
	$msg 	= "Hashes don't match - response not authenticated!";
}
$proceed_success = false;
if($result=="00")
	$proceed_success = true;
if($passtype == 'order') // Case of order
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
		echo "<div class='realxfail_class'>Sorry!! Order id is not valid</div>";
		exit;
	}
	if ($proceed_success and $stat != 'ERR') // case if payment is successfull
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
			$update_array['order_realex_timestamp']	= add_slash($timestamp);
			$update_array['order_realex_result']	= add_slash($result);
			$update_array['order_realex_orderid']	= add_slash($orderid);
			$update_array['order_realex_message']	= add_slash($message);
			$update_array['order_realex_authcode']	= add_slash($authcode);
			$update_array['order_realex_passref']	= add_slash($pasref);
			$update_array['order_realex_md5hash']	= add_slash($realexmd5);
			$update_array['sites_site_id']			= $ecom_siteid;
			$db->update_from_array($update_array,'order_payment_main',array('orders_order_id'=>$order_id));
		}
		else // case no record exists. so insert the order id and google transid
		{
			$insert_array							= array();
			$insert_array['orders_order_id']		= $order_id;
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['order_realex_timestamp']	= add_slash($timestamp);
			$insert_array['order_realex_result']	= add_slash($result);
			$insert_array['order_realex_orderid']	= add_slash($orderid);
			$insert_array['order_realex_message']	= add_slash($message);
			$insert_array['order_realex_authcode']	= add_slash($authcode);
			$insert_array['order_realex_passref']	= add_slash($pasref);
			$insert_array['order_realex_md5hash']	= add_slash($realexmd5);
			$db->insert_from_array($insert_array,'order_payment_main');
		}
		
		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($order_id);
		
		
		
		// calling function to send any mails 
		send_RequiredOrderMails($order_id);

		//Clearing the cart
		clear_cart($sess_id);
		
		// Google ecom tracking script
		google_analytics_ecom_tracking_code($order_id);

		// Clear sticky cart variables;
		clear_session_var("cart_total");
		clear_session_var("cart_total_items");
		set_session_var('gateway_ord_id',0);
		$succ = "checkout_success".$order_id.".html";
		echo "<div class='realxsucc_class'>
		Thank you. Your Payment Received Successfull";
		
		// Get the grand total from orders table for current order
		$sql_ord = "SELECT order_totalprice,order_paystatus,order_cpc_keyword, order_cpc_se_id, order_cpc_click_id,
						order_cpc_click_pm_id, order_cost_per_click_id  
						FROM 
							orders 
						WHERE 
							order_id=".$order_id." 
							AND sites_site_id=$ecom_siteid 
						LIMIT 
							1";
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord))
		{
			$row_ord 		= $db->fetch_array($ret_ord);
			$total_price 	= $row_ord['order_totalprice'];
			$cur_stat		= $row_ord['order_paystatus'];
			if($ecom_isadword)
			{
				if ($order_id>0)
				{
				?>
						<!-- Google Code for purchase Conversion Page -->
						<script language="JavaScript" type="text/javascript">
						<!--
						var google_conversion_id 		= <?php echo $ecom_adword_conversionid?>; 			<?php /*1050720287;*/?>
						var google_conversion_language 	= "<?php echo $ecom_adword_conversionlanguage?>"; 	<?php /*"en_GB";*/?>
						var google_conversion_format 	= "<?php echo $ecom_adword_conversionformat?>";		<?php /*"1";*/?>
						var google_conversion_color 	= "<?php echo $ecom_adword_conversioncolor?>";		<?php /*"FFFFFF";*/?>
						if (<?=$total_price?>) 
						{
						  var google_conversion_value 	= <?=$total_price?>;
						}
						var google_conversion_label 	= "<?php echo $ecom_adword_conversionlabel?>"; 		<?php /*"purchase";*/ ?>
						//-->
						</script>
						<script language="JavaScript" src="https://www.googleadservices.com/pagead/conversion.js">
						</script>
						<noscript>
						<img height=1 width=1 border=0 src="https://www.googleadservices.com/pagead/conversion/<?php echo $ecom_adword_conversionid?>/imp.gif?value=<?=$total_price?>&label=<?php echo $ecom_adword_conversionlabel?>&script=0">
						</noscript>
			<?php		
					
				}
			}
			if(trim($ecom_success_script)!='')
			{
				$succ_script = trim($ecom_success_script);
				$succ_script = str_replace('[TOTAL_PRICE]',$total_price,trim($succ_script));
				echo stripslash_normal($succ_script);
			}
		}
		
		echo "
		</div>";
	}
	else // Case if payment failed
	{
		echo "<div class='realxfail_class'>Sorry!! Payment Failed";
		if($stat =='ERR')
			echo "<br>$msg";
		if ($message!='')
			echo "<br>$message -- ".$authcode;
		echo "</div>";	
		// Calling function to delete the order details if payment is a failure
		deleteOrder_on_failure($order_id);
	}
}
else if($passtype == 'voucher')
{
	if ($proceed_success and $stat != 'ERR') // case if payment is successfull
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
			$update_array['voucher_realex_timestamp']	= add_slash($timestamp);
			$update_array['voucher_realex_result']		= add_slash($result);
			$update_array['voucher_realex_orderid']		= add_slash($orderid);
			$update_array['voucher_realex_message']		= add_slash($message);
			$update_array['voucher_realex_authcode']	= add_slash($authcode);
			$update_array['voucher_realex_passref']		= add_slash($pasref);
			$update_array['voucher_realex_md5hash']		= add_slash($realexmd5);
			$db->update_from_array($update_array,'gift_vouchers_payment',array('gift_vouchers_voucher_id'=>$voucher_id));
		}
		else // case no record exists. so insert the voucher id and google_checkoutid
		{
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
			$insert_array['voucher_realex_timestamp']	= add_slash($timestamp);
			$insert_array['voucher_realex_result']		= add_slash($result);
			$insert_array['voucher_realex_orderid']		= add_slash($orderid);
			$insert_array['voucher_realex_message']		= add_slash($message);
			$insert_array['voucher_realex_authcode']	= add_slash($authcode);
			$insert_array['voucher_realex_passref']		= add_slash($pasref);
			$insert_array['voucher_realex_md5hash']		= add_slash($realexmd5);
			$db->insert_from_array($insert_array,'gift_vouchers_payment');
		}
	
		// Clearing the voucher cart
		clear_VoucherCart($sess_id);
		set_session_var('gateway_voucher_id',0);
		$succ = "voucher_success".$voucher_id.".html";
		echo "<div class='realxsucc_class'>Payment Successfull</div>";
	}
	else
	{
		$voucher_id = $order_id;
		// Delete gift voucher details
		deleteVoucher_on_failure($voucher_id);
		echo "<div class='realxfail_class'>Sorry!! Payment Failed";
		if($stat =='ERR')
			echo "<br>$msg";
		if ($message!='')
			echo "<br>$message";
		echo "</div>";	
	}
}
else if($passtype == 'payonaccount')
{
	if ($proceed_success and $stat != 'ERR') // case if payment is successfull
	{
		$pay_id = $order_id;
		$payStat = 'Paid';
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
			$update_array['realex_timestamp']	= add_slash($timestamp);
			$update_array['realex_result']		= add_slash($result);
			$update_array['realex_orderid']		= add_slash($orderid);
			$update_array['realex_message']		= add_slash($message);
			$update_array['realex_authcode']	= add_slash($authcode);
			$update_array['realex_passref']		= add_slash($pasref);
			$update_array['realex_md5hash']		= add_slash($realexmd5);
			$db->update_from_array($update_array,'order_payonaccount_pending_details_payment',array('order_payonaccount_pendingpay_id'=>$pay_id));
		}
		else // case no record exists. so insert the voucher id and google_checkoutid
		{
			$insert_array										= array();
			$insert_array['order_payonaccount_pendingpay_id']	= $pay_id;
			$insert_array['realex_timestamp']					= add_slash($timestamp);
			$insert_array['realex_result']						= add_slash($result);
			$insert_array['realex_orderid']						= add_slash($orderid);
			$insert_array['realex_message']						= add_slash($message);
			$insert_array['realex_authcode']					= add_slash($authcode);
			$insert_array['realex_passref']						= add_slash($pasref);
			$insert_array['realex_md5hash']						= add_slash($realexmd5);
			$db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment');
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
								realex_timestamp='".add_slash($timestamp)."',
								realex_result='".add_slash($result)."', 
								realex_orderid='".add_slash($orderid)."',
								realex_message='".add_slash($message)."',
								realex_authcode='".add_slash($authcode)."',
								realex_passref='".add_slash($pasref)."',
								realex_md5hash='".add_slash($realexmd5)."' 
							WHERE 
								order_payonaccount_pendingpay_id=$typeid 
								AND payment_id=".$row_check['payment_id']." 
							LIMIT 
								1";
			$db->query($sql_update);
		}
		else
		{
			$sql_insert	= "INSERT INTO order_payonaccount_payment  
							SET 
								realex_timestamp='".add_slash($timestamp)."',
								realex_result='".add_slash($result)."', 
								realex_orderid='".add_slash($orderid)."',
								realex_message='".add_slash($message)."',
								realex_authcode='".add_slash($authcode)."',
								realex_passref='".add_slash($pasref)."',
								realex_md5hash='".add_slash($realexmd5)."',
								order_payonaccount_pay_id=$org_pay_id";
			$db->query($sql_insert);
		}
		
		
		// Clearing the payonaccount cart
		clear_PayonAccountCart($sess_id);
		set_session_var('gateway_payonaccount_id',0);
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
		echo "<div class='realxsucc_class'>Payment Successfull</div>";
	}
	else
	{
		$pay = $order_id;
		
		// Delete payonaccount details
		deletePayonAccount_on_failure($pay_id);
		echo "<div class='realxfail_class'>Sorry!! Payment Failed";
		if($stat =='ERR')
			echo "<br>$msg";
		if ($message!='')
			echo "<br>$message";
		echo "</div>";	
	}
}
?>
