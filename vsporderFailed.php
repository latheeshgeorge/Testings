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
if ($_REQUEST["typ"]=='payonaccount')
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

* 14/09/2007 - Mat Peck - New kit version
****************************************************************************************************
* Description
* ===========

* This is a placeholder for your Successful Order Completion Page.  It retrieves the VendorTxCode
* from the crypt string and displays the transaction results on the screen.  You wouldn't display 
* all the information in a live application, but during development this page shows everything
* sent back in the confirmation screen.
****************************************************************************************************/

// Now check we have a Crypt field passed to this page 
$strCrypt=$_REQUEST["crypt"];
if (strlen($strCrypt)==0) {
	ob_end_flush();
	/*echo "<script>window.location='http://".$ecom_hostname."/viewcart.html';</script>";
	exit;*/
}
// Now decode the Crypt field and extract the results

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
// Split out the useful information into variables we can use
$strStatus				=$values['Status'];
$strStatusDetail		=$values['StatusDetail'];
$strVendorTxCode	=$values["VendorTxCode"];
$strVPSTxId			=$values["VPSTxId"];
$strTxAuthNo			=$values["TxAuthNo"];
$strAmount			=$values["Amount"];
$strAVSCV2			=$values["AVSCV2"];
$strAddressResult	=$values["AddressResult"];
$strPostCodeResult	=$values["PostCodeResult"];
$strCV2Result			=$values["CV2Result"];
$strGiftAid				=$values["GiftAid"];
$str3DSecureStatus	=$values["3DSecureStatus"];
$strCAVV				=$values["CAVV"];

// Determine the reason this transaction was unsuccessful
if ($strStatus=="NOTAUTHED")
	$strReason="You payment was declined by the bank.  This could be due to insufficient funds, or incorrect card details.";
else if ($strStatus=="ABORT")
	$strReason="You chose to Cancel your order on the payment pages.  If you wish to change your order and resubmit it you can do so here. ";//If you have questions or concerns about ordering online, please contact us at [your number].";
else if ($strStatus=="REJECTED") 
	$strReason="Your order did not meet our minimum fraud screening requirements. ";//If you have questions about our fraud screening rules, or wish to contact us to discuss this, please call [your number].";
else if ($strStatus=="INVALID" or strStatus=="MALFORMED")
	$strReason="We could not process your order because we have been unable to register your transaction with our Payment Gateway.";// You can place the order over the telephone instead by calling [your number].";
else if ($strStatus=="ERROR")
	$strReason="We could not process your order because our Payment Gateway service was experiencing difficulties";//. You can place the order over the telephone instead by calling [your number].";
else
	$strReason="The transaction process failed. Please contact us with the date and time of your order and we will investigate.";
	
if($_REQUEST["typ"] == 'ord') 
{
	$order_id 	= $_REQUEST['oid'];
	// Calling function to delete the order details if payment is a failure
		deleteOrder_on_failure($order_id);
		echo "
			<script type='text/javascript'>
				window.location = '".url_link('checkout_failed.html',1)."';
			</script>
			";
		exit;
}	
elseif($_REQUEST["typ"]=='voucher')
{
	$voucher_id = $_REQUEST['vid'];
	// Delete gift voucher details
	deleteVoucher_on_failure($voucher_id);
    $sessionID = $_REQUEST['sessionID'];
	$update_query = "UPDATE gift_voucherbuy_cartvalues 
						SET 
							voucher_error_msg = '".$strStatusDetail."' 
						WHERE 
							sites_site_id =$ecom_siteid
							AND session_id='".$sessionID."' 
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
elseif($_REQUEST["typ"]=='payonaccount')
{
	$pay_id = $_REQUEST['pid'];
	// Delete payonaccount details
	deletePayonAccount_on_failure($pay_id);
    $sessionID = $_REQUEST['sessionID'];
	$update_query = "UPDATE payonaccount_cartvalues  
						SET 
							pay_error_msg = '".$strStatusDetail."' 
						WHERE 
							sites_site_id =$ecom_siteid
							AND session_id='".$sessionID."' 
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
?>
