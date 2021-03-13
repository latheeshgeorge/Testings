<?
if (sagepay_v3protocol_enabled()) // cateringdirect
{
	include("includes/protx_vsp_includes_v3.php"); // this fiile holds the variables required 
}	
else
{
	include("includes/protx_vsp_includes.php"); // this fiile holds the variables required 
}	

// Get the payment method id for protx vsp from payment_methods table
$sql_pay = "SELECT paymethod_id 
					FROM 
						payment_methods 
					WHERE 
						paymethod_key = 'PROTX_VSP' 
					LIMIT 
						1";
$ret_pay = $db->query($sql_pay);
if ($db->num_rows($ret_pay))
{
	$row_pay = $db->fetch_array($ret_pay);
}
$sql_method = "SELECT a.payment_methods_details_key,b.payment_methods_forsites_details_values 
						FROM 
							payment_methods_details a,payment_methods_forsites_details b 
						WHERE 
							a.payment_methods_paymethod_id = ".$row_pay['paymethod_id']." 
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
if($_REQUEST["typ"] == 'payonaccount') 
	$strTransactionType		= 'PAYMENT';
else
	$strTransactionType		= getprotxCaptureType();
$strCurrency					= $curr_code;
$strVSPVendorName		= $paymethod_arr['VENDOR_ID'];
$strEncryptionPassword	= $paymethod_arr['HASK_KEY'];

/**************************************************************************************************
* VSP Form PHP Kit Order Successful Page
***************************************************************************************************

***************************************************************************************************
* Change history
* ==============

* 18/10/2007 - Nick Selby - New kit version
****************************************************************************************************
* Description
* ===========

* This is a placeholder for your Successful Order Completion Page.  It retrieves the VendorTxCode
* from the crypt string and displays the transaction results on the screen.  You wouldn't display 
* all the information in a live application, but during development this page shows everything
* sent back in the confirmation screen.
****************************************************************************************************/

// Check for the proceed button click, and if so, go to the buildOrder page

$strCrypt=$_REQUEST["crypt"];
if (strlen($strCrypt)==0) {
	ob_end_flush();
	// Redirecting back to cart page
	/*echo "<script>window.location='http://".$ecom_hostname."/viewcart.html';</script>";
	exit;*/
}
// Now decode the Crypt field and extract the results
//$strDecoded=simpleXor(Base64Decode($strCrypt),$strEncryptionPassword);
//$values = getToken($strDecoded);

if($strProtocol=='3.00') // case if version is 3.00
{
	//$strDecoded=simpleXor(Base64Decode($strCrypt),$strEncryptionPassword);
	$strDecoded=decryptAes($strCrypt ,$strEncryptionPassword);
	//$values = getToken($strDecoded);
	$values = queryStringToArray($strDecoded);
}
else
{
	$strDecoded=simpleXor(Base64Decode($strCrypt),$strEncryptionPassword);
	$values = getToken($strDecoded);
}	

$fname = '/home/storage/024/3270024/user/htdocs/vs_return/vsp_return_full2015.txt';
	$fp = fopen($fname,'a+');
	
	fwrite($fp,"$strDecoded \n");
	fclose($fp);

// Split out the useful information into variables we can use
$strStatus			= $values['Status'];
$strStatusDetail	= $values['StatusDetail'];
$strVendorTxCode	= $values["VendorTxCode"];
$strVPSTxId			= $values["VPSTxId"];
$strTxAuthNo		= $values["TxAuthNo"];
$strAmount			= $values["Amount"];
$strAVSCV2			= $values["AVSCV2"];
$strAddressResult	= $values["AddressResult"];
$strPostCodeResult	= $values["PostCodeResult"];
$strCV2Result		= $values["CV2Result"];
$strGiftAid			= $values["GiftAid"];
$str3DSecureStatus	= $values["3DSecureStatus"];
$strCAVV			= $values["CAVV"];
$strCardType		= $values["CardType"];
$strSecurityKey		= $values["SecurityKey"];

$strDeclineCode		= $values["DeclineCode"];
$strBankAuthCode	= $values["BankAuthCode"];
$strExpiryDate		= $values["ExpiryDate"];


$error = '';
$sessionID = $_REQUEST['sessionID'];
if($_REQUEST["typ"] == 'ord') 
{
	$order_id = $_REQUEST['oid'];
	
	// Updating the payment status to paid.
	$capture_type_val = getprotxCaptureType();
	if($capture_type_val=='DEFERRED')
	{
		$payStat 							= 'DEFERRED';
	}
	else	
		$payStat 							= 'Paid';
	$update_array						= array();
	$update_array['order_paystatus']	= add_slash($payStat);
	$update_array['order_status']		= 'NEW';
//if ($ecom_siteid==95)
{
	$sql_stat = "SELECT order_paystatus FROM orders WHERE order_id=$order_id AND sites_site_id = $ecom_siteid LIMIT 1";
	$ret_stat = $db->query($sql_stat);
	if ($db->num_rows($ret_stat))
	{
		$row_stat = $db->fetch_array($ret_stat);
	}
	if($row_stat['order_paystatus']=='PROTX_VSP')
	{
		$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
	}
	//$db->update_from_array($update_array,'orders',array('order_id'=>$order_id,'order_paystatus'=>'PROTX_VSP'));
}
/*else
{
	$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
}*/

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
		$update_array										= array();
		$update_array['order_protStatus']			= add_slash($strStatus);
		$update_array['sites_site_id']				= $ecom_siteid;
		$update_array['order_protStatusDetail']	= mysql_escape_string($strStatusDetail) ;
		$update_array['order_vendorTxCode']	= add_slash($strVendorTxCode);
		$update_array['order_vPSTxId']			= add_slash($strVPSTxId);
		$update_array['order_avscv2']				= add_slash($strAVSCV2);
		$update_array['order_cavv']					= add_slash($strCAVV);
		$update_array['order_3dsecurestatus']	= add_slash($str3DSecureStatus);
		$update_array['order_card_type']		= addslashes(stripslashes($strCardType));
		$update_array['order_securityKey']		= addslashes(stripslashes($strSecurityKey));
		$update_array['order_txAuthNo']		= addslashes(stripslashes($strTxAuthNo));
		
		$update_array['order_vsp_DeclineCode']		= addslashes(stripslashes($strDeclineCode));
		$update_array['order_vsp_BankAuthCode']		= addslashes(stripslashes($strBankAuthCode));
		$update_array['order_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		
		$db->update_from_array($update_array,'order_payment_main',array('orders_order_id'=>$order_id));
	}
	else // case no record exists. so insert the order id and google transid
	{
		$insert_array										= array();
		$insert_array['orders_order_id']				= $order_id;
		$insert_array['sites_site_id']					= $ecom_siteid;
		$insert_array['order_protStatus']			= add_slash($strStatus);
		$insert_array['order_protStatusDetail']	= mysql_escape_string($strStatusDetail) ;
		$insert_array['order_vendorTxCode']		= add_slash($strVendorTxCode);
		$insert_array['order_vPSTxId']				= add_slash($strVPSTxId);
		$insert_array['order_avscv2']					= add_slash($strAVSCV2);
		$insert_array['order_cavv']					= add_slash($strCAVV);
		$insert_array['order_3dsecurestatus']		= add_slash($str3DSecureStatus);
		$insert_array['order_securityKey']		= addslashes(stripslashes($strSecurityKey));
		$insert_array['order_txAuthNo']		= addslashes(stripslashes($strTxAuthNo));
		$insert_array['order_card_type']		= addslashes(stripslashes($strCardType));
		
		$insert_array['order_vsp_DeclineCode']		= addslashes(stripslashes($strDeclineCode));
		$insert_array['order_vsp_BankAuthCode']		= addslashes(stripslashes($strBankAuthCode));
		$insert_array['order_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		$db->insert_from_array($insert_array,'order_payment_main');
	}
	
	$fname = '/home/storage/024/3270024/user/htdocs/vs_return/vsp_return2015.txt';
	$fp = fopen($fname,'a+');
	$order_id = $order_id;
	$cardtype = $strCardType;
	fwrite($fp,"Order Id :$order_id\t Card Type: $cardtype\t Security Key: $strSecurityKey\t Auth no: $strTxAuthNo \n");
	fclose($fp);
	
	
	// Stock Decrementing section over here
	do_PostOrderSuccessOperations($order_id);
	
	// calling function to send any mails 
	send_RequiredOrderMails($order_id);

	//Clearing the cart
	clear_cart($sessionID);

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
elseif($_REQUEST["typ"] == 'voucher') 
{
	$voucher_id 	= $_REQUEST['vid'];;
	$payStat 		= 'Paid';
	$sql_update 	= "UPDATE gift_vouchers
								SET
									voucher_paystatus='".add_slash($payStat)."',
									voucher_activatedon = curdate(),
									voucher_expireson	= DATE_ADD(curdate(),INTERVAL voucher_activedays DAY), 
									voucher_incomplete = 0 
								WHERE
									voucher_id = ".$voucher_id."
								LIMIT
									1";
	$db->query($sql_update);
	// Sending mails which are not send yet
	send_RequiredVoucherMails($voucher_id);

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
		$update_array['protStatus']			= add_slash($strStatus);
		$update_array['protStatusDetail']	= mysql_escape_string($strStatusDetail) ;
		$update_array['vendorTxCode']		= add_slash($strVendorTxCode);
		$update_array['vPSTxId']				= add_slash($strVPSTxId);
		$update_array['avscv2']				= add_slash($strAVSCV2);
		$update_array['cavv']					= add_slash($strCAVV);
		$update_array['3dsecurestatus']	= add_slash($str3DSecureStatus);
		
		$update_array['voucher_vsp_DeclineCode']	= addslashes(stripslashes($strDeclineCode));;
		$update_array['voucher_vsp_BankAuthCode']	= addslashes(stripslashes($strBankAuthCode));
		$update_array['voucher_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		$db->update_from_array($update_array,'gift_vouchers_payment',array('gift_vouchers_voucher_id'=>$voucher_id));
	}
	else // case no record exists. so insert the voucher id and google_checkoutid
	{
		$insert_array											= array();
		$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
		$insert_array['protStatus']						= add_slash($strStatus);
		$insert_array['protStatusDetail']					= mysql_escape_string($strStatusDetail) ;
		$insert_array['vendorTxCode']					= add_slash($strVendorTxCode);
		$insert_array['vPSTxId']							= add_slash($strVPSTxId);
		$insert_array['avscv2']								= add_slash($strAVSCV2);
		$insert_array['cavv']									= add_slash($strCAVV);
		$insert_array['3dsecurestatus']					= add_slash($str3DSecureStatus);
		
		$insert_array['voucher_vsp_DeclineCode']	= addslashes(stripslashes($strDeclineCode));;
		$insert_array['voucher_vsp_BankAuthCode']	= addslashes(stripslashes($strBankAuthCode));
		$insert_array['voucher_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		$db->insert_from_array($insert_array,'gift_vouchers_payment');
	}

	// Clearing the voucher cart
	clear_VoucherCart($sessionID);
 // will modify the following when doing the voucher section
	$succ = "voucher_success".$voucher_id.".html";
	echo	"
			<script type='text/javascript'>
				window.location = '".url_link($succ,1)."';
			</script>
			";
	exit;		
}
elseif($_REQUEST["typ"] == 'payonaccount') 
{
	$pay_id 			= $_REQUEST['pid'];;
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
		$update_array['protStatus']			= add_slash($strStatus);
		$update_array['protStatusDetail']	= mysql_escape_string($strStatusDetail) ;
		$update_array['vendorTxCode']		= add_slash($strVendorTxCode);
		$update_array['vPSTxId']				= add_slash($strVPSTxId);
		$update_array['avscv2']				= add_slash($strAVSCV2);
		$update_array['cavv']					= add_slash($strCAVV);
		$update_array['3dsecurestatus']	= add_slash($str3DSecureStatus);
		
		$update_array['pay_vsp_DeclineCode']	= addslashes(stripslashes($strDeclineCode));;
		$update_array['pay_vsp_BankAuthCode']	= addslashes(stripslashes($strBankAuthCode));
		$update_array['pay_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		$db->update_from_array($update_array,'order_payonaccount_pending_details_payment',array('order_payonaccount_pendingpay_id'=>$pay_id));
	}
	else // case no record exists. so insert the voucher id and google_checkoutid
	{
		$insert_array														= array();
		$insert_array['order_payonaccount_pendingpay_id']	= $pay_id;
		$insert_array['protStatus']									= add_slash($strStatus);
		$insert_array['protStatusDetail']								= mysql_escape_string($strStatusDetail) ;
		$insert_array['vendorTxCode']								= add_slash($strVendorTxCode);
		$insert_array['vPSTxId']										= add_slash($strVPSTxId);
		$insert_array['avscv2']											= add_slash($strAVSCV2);
		$insert_array['cavv']												= add_slash($strCAVV);
		$insert_array['3dsecurestatus']								= add_slash($str3DSecureStatus);
		$insert_array['pay_vsp_DeclineCode']	= addslashes(stripslashes($strDeclineCode));;
		$insert_array['pay_vsp_BankAuthCode']	= addslashes(stripslashes($strBankAuthCode));
		$insert_array['pay_vsp_ExpiryDate']		= addslashes(stripslashes($strExpiryDate));
		$db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment');
	}
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
	clear_PayonAccountCart($sessionID);
	$succ = "payonaccount_success".$org_pay_id.".html";
	echo	"
			<script type='text/javascript'>
				window.location = '".url_link($succ,1)."';
			</script>
			";
	exit;		
}
?>
