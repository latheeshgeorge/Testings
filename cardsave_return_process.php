<?php
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");

if (get_magic_quotes_gpc()) 
{
	$_POST = array_map('stripslashes', $_POST);
}

// Get payment methodid
$sql_pid = "SELECT paymethod_id FROM payment_methods WHERE paymethod_key ='CARDSAVE' LIMIT 1";
$ret_pid = $db->query($sql_pid);
if ($db->num_rows($ret_pid))
{
	$row_pid = $db->fetch_array($ret_pid);
}
if ($row_pid['paymethod_id'])
{
	$sql_method = "SELECT a.payment_methods_details_key,b.payment_methods_forsites_details_values 
					FROM 
						payment_methods_details a,payment_methods_forsites_details b 
					WHERE 
						a.payment_methods_paymethod_id = ".$row_pid['paymethod_id']."
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

$CARDSAVE_merchantid	= $paymethod_arr['CARDSAVE_MERCHANT_ID'];
$CARDSAVE_password		= $paymethod_arr['CARDSAVE_PASSWORD'];
$CARDSAVE_sharedkey		= $paymethod_arr['CARDSAVE_PRESHARED_KEY'];



$HashString="PreSharedKey=" . $CARDSAVE_sharedkey;
$HashString=$HashString . '&MerchantID=' . $_POST["MerchantID"];
$HashString=$HashString . '&Password=' . $CARDSAVE_password;
$HashString=$HashString . '&StatusCode=' . $_POST["StatusCode"];
$HashString=$HashString . '&Message=' . $_POST["Message"];
$HashString=$HashString . '&PreviousStatusCode=' . $_POST["PreviousStatusCode"];
$HashString=$HashString . '&PreviousMessage=' . $_POST["PreviousMessage"];
$HashString=$HashString . '&CrossReference=' . $_POST["CrossReference"];
$HashString=$HashString . '&AddressNumericCheckResult=' . $_POST["AddressNumericCheckResult"];
$HashString=$HashString . '&PostCodeCheckResult=' . $_POST["PostCodeCheckResult"];
$HashString=$HashString . '&CV2CheckResult=' . $_POST["CV2CheckResult"];
$HashString=$HashString . '&ThreeDSecureAuthenticationCheckResult=' . $_POST["ThreeDSecureAuthenticationCheckResult"];
$HashString=$HashString . '&CardType=' . $_POST["CardType"];
$HashString=$HashString . '&CardClass=' . $_POST["CardClass"];
$HashString=$HashString . '&CardIssuer=' . $_POST["CardIssuer"];
$HashString=$HashString . '&CardIssuerCountryCode=' . $_POST["CardIssuerCountryCode"];
$HashString=$HashString . '&Amount=' . $_POST["Amount"];
$HashString=$HashString . '&CurrencyCode=' . $_POST["CurrencyCode"];
$HashString=$HashString . '&OrderID=' . $_POST["OrderID"];
$HashString=$HashString . '&TransactionType=' . $_POST["TransactionType"];
$HashString=$HashString . '&TransactionDateTime=' . $_POST["TransactionDateTime"];
$HashString=$HashString . '&OrderDescription=' . $_POST["OrderDescription"];
$HashString=$HashString . '&CustomerName=' . $_POST["CustomerName"];
$HashString=$HashString . '&Address1=' . $_POST["Address1"];
$HashString=$HashString . '&Address2=' . $_POST["Address2"];
$HashString=$HashString . '&Address3=' . $_POST["Address3"];
$HashString=$HashString . '&Address4=' . $_POST["Address4"];
$HashString=$HashString . '&City=' . $_POST["City"];
$HashString=$HashString . '&State=' . $_POST["State"];
$HashString=$HashString . '&PostCode=' . $_POST["PostCode"];
$HashString=$HashString . '&CountryCode=' . $_POST["CountryCode"];
$HashString=$HashString . '&EmailAddress=' . $_POST["EmailAddress"];
$HashString=$HashString . '&PhoneNumber=' . $_POST["PhoneNumber"];


//Encode HashDigest using SHA1 encryption (and create HashDigest for later use) - This is used as a checksum to ensure that the form post from the gateway back to this page hasn't been tampered with.
$HashDigest = sha1($HashString);  
 
//Function to compare Hash returned from the gateway and that generated in the script above.
function checkhash($HashDigest) {
   $ReturnedHash = $_POST["HashDigest"];
   if ($HashDigest == $ReturnedHash) { 
      return true;
   } else { 
      return false;
   } 
}

$hash_result = checkhash($HashDigest);

if($hash_result) // hash comparison is done and found to be correct
{
	$osr_arr = array('{','}');
	$orp_arr = array('','');	
	$rep_str = str_replace($osr_arr,$orp_arr,$_POST['OrderID']);
	$order_id_arr = explode('-',$rep_str);
	$order_id = $order_id_arr[0];
	$passtype = $order_id_arr[1];
	$passsessid = $order_id_arr[2];
	
	//print_r($_POST);
	//exit;
	
	if ($_POST['StatusCode']==0) // case if transaction is successfull
	{
		// Building the values to be inserted or updated
		
		$insert_arr												= array();
		$insert_arr['orders_order_id']							= $order_id;
		$insert_arr['sites_site_id']							= $ecom_siteid;
		$insert_arr['StatusCode']								= addslashes($_POST['StatusCode']);
		$insert_arr['Message']									= addslashes($_POST['Message']);
		$insert_arr['PreviousStatusCode']						= addslashes($_POST['PreviousStatusCode']);
		$insert_arr['PreviousMessage']							= addslashes($_POST['PreviousMessage']);
		$insert_arr['CrossReference']							= addslashes($_POST['CrossReference']);
		$insert_arr['AddressNumericCheckResult']				= addslashes($_POST['AddressNumericCheckResult']);
		$insert_arr['PostCodeCheckResult']						= addslashes($_POST['PostCodeCheckResult']);
		$insert_arr['CV2CheckResult']							= addslashes($_POST['CV2CheckResult']);
		$insert_arr['ThreeDSecureAuthenticationCheckResult']	= addslashes($_POST['ThreeDSecureAuthenticationCheckResult']);
		$insert_arr['payid']									= addslashes($_POST['payid']);
		$insert_arr['CardType']									= addslashes($_POST['CardType']);
		$insert_arr['CardClass']								= addslashes($_POST['CardClass']);
		$insert_arr['CardIssuer']								= addslashes($_POST['CardIssuer']);
		$insert_arr['CardIssuerCountryCode']					= addslashes($_POST['CardIssuerCountryCode']);
		$insert_arr['Amount']									= addslashes($_POST['Amount']);
		$insert_arr['CurrencyCode']								= addslashes($_POST['CurrencyCode']);
		$insert_arr['TransactionType']							= addslashes($_POST['TransactionType']);	
		$insert_arr['TransactionDateTime']						= addslashes($_POST['TransactionDateTime']);
		$insert_arr['HashDigest']								= addslashes($_POST['HashDigest']);
		$insert_arr['pay_type']									= addslashes(trim($passtype));
		
		// Check whether there exists an entry in the table for the obtained order_id value
		$sql_cardcheck = "SELECT orders_order_id 
							FROM 
								order_payment_cardsave 
							WHERE 
								orders_order_id = $order_id
								AND sites_site_id = $ecom_siteid 
								AND pay_type='".trim($passtype)."' 
							LIMIT 
								1";
		$ret_cardcheck = $db->query($sql_cardcheck);
		if($db->num_rows($ret_cardcheck)==0) // if entry does not exists
		{
			
			$db->insert_from_array($insert_arr,'order_payment_cardsave');
		}
		else // if entry exists
		{
			$db->update_from_array($insert_arr,'order_payment_cardsave',array('orders_order_id'=>$order_id,'pay_type'=>$passtype,'sites_site_id'=>$ecom_siteid));
		}
		
		if($passtype=='order')
		{
			$payStat 							= 'Paid';
			$update_array						= array();
			$update_array['order_paystatus']	= add_slash($payStat);
			$update_array['order_status']		= 'NEW';
			$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
			
			// Stock Decrementing section over here
			do_PostOrderSuccessOperations($order_id);
			
			// calling function to send any mails 
			send_RequiredOrderMails($order_id);

			//Clearing the cart
			clear_cart($passsessid);

			// Clear sticky cart variables;
			clear_session_var("cart_total");
			clear_session_var("cart_total_items");
			$succ = "checkout_success".$order_id.".html";
			echo "
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
			exit;
		}
		elseif($passtype=='voucher')
		{
			$voucher_id 	= $order_id;
			$payStat 		= 'Paid';
			$sql_update 	= "UPDATE gift_vouchers
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
			// Clearing the voucher cart
			clear_VoucherCart($passsessid);
		 // will modify the following when doing the voucher section
			$succ = "voucher_success".$voucher_id.".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
		}
		elseif($passtype=='payonaccount')
		{
			$pay_id 		= $order_id;
			$payStat 		= 'Paid';
			/*$sql_update 	= "UPDATE order_payonaccount_pending_details 
										SET
											pay_paystatus='".add_slash($payStat)."' 
										WHERE
											pendingpay_id = ".$pay_id."
										LIMIT
											1";
			$db->query($sql_update);*/
			// Sending mails which are not send yet
			//send_RequiredVoucherMails($voucher_id);

			
			//Post payment success operation
			$org_pay_id = do_PostPayonAccountSuccessOperations($pay_id );
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
			// Clearing the payonaccount cart
			clear_PayonAccountCart($passsessid);
			$succ = "payonaccount_success".$org_pay_id.".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
		}
	}
	else // case if transactions is not successfull
	{
		if($passtype=='order')
		{
			// Calling function to delete the order details if payment is a failure
				deleteOrder_on_failure($order_id);
				$msgtodisplay = "<center class='regicontentA_msg'>Sorry!!. ".$_POST['Message']."</center>";
				$sql_cart = "UPDATE cart_supportdetails
								SET cart_error_msg_ret ='".addslashes($msgtodisplay)."'
							WHERE 
								sites_site_id =$ecom_siteid
								AND session_id='".$passsessid."'";
				$db->query($sql_cart);
				
				echo "
					<script type='text/javascript'>
						window.location = '".url_link('checkout_failed.html',1)."';
					</script>
					";
				exit;
		}
		elseif($passtype=='voucher')
		{
			$voucher_id = $order_id;
			// Delete gift voucher details
			deleteVoucher_on_failure($voucher_id);
			$update_query = "UPDATE gift_voucherbuy_cartvalues 
								SET 
									voucher_error_msg = '".$_POST['Message']."' 
								WHERE 
									sites_site_id =$ecom_siteid
									AND session_id='".$passsessid."' 
								LIMIT 
									1";
			$db->query($update_query);
			// Redirect to voucher failure page;
			echo "
					<script type='text/javascript'>
						window.location = '".url_link('voucher_failed.html',1)."';
					</script>
					";
			exit;
		}	
		elseif($passtype=='payonaccount')
		{
			$pay_id = $order_id;
			// Delete payonaccount details
			deletePayonAccount_on_failure($pay_id);
			$sessionID = $_REQUEST['sessionID'];
			$update_query = "UPDATE payonaccount_cartvalues  
								SET 
									pay_error_msg = '".$_POST['Message']."' 
								WHERE 
									sites_site_id =$ecom_siteid
									AND session_id='".$passsessid."' 
								LIMIT 
									1";
			$db->query($update_query);
			// Redirect to voucher failure page;
			echo "
					<script type='text/javascript'>
						window.location = '".url_link('payonaccount_failed.html',1)."';
					</script>
					";
			exit;
		}	
	}
}
else // some issue with hash value
{
	echo $msg = 'Sorry!.. Data is malformed';
	/*echo "
					<script type='text/javascript'>
						window.location = '".url_link('checkout_failed.html',1)."';
					</script>
					";
				exit;
	exit;*/
}


?>
