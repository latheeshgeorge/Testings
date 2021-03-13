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
$order_id			= trim($_REQUEST['orderRef']);
$merchantid			= trim($_REQUEST['merchantID']);
$message			= trim($_REQUEST['responseMessage']);
$responseCode		= trim($_REQUEST['responseCode']);

$responseMessage 	= trim($_REQUEST['responseMessage']);
$transactionID    	= trim($_REQUEST['transactionID']);;
$xref				= trim($_REQUEST['xref']);;
$state				= trim($_REQUEST['state']);;
$timestamp			= trim($_REQUEST['timestamp']);;
$transactionUnique	= trim($_REQUEST['transactionUnique']);;
$referralPhone		= trim($_REQUEST['referralPhone']);;
$amountReceived		= trim($_REQUEST['amountReceived']);;
$orderRef			= trim($_REQUEST['orderRef']);;
$cardNumberMask		= trim($_REQUEST['cardNumberMask']);;
$cardTypeCode 		= trim($_REQUEST['cardTypeCode']);;
$cardType			= trim($_REQUEST['cardType']);;
$cardSchemeCode		= trim($_REQUEST['cardSchemeCode']);;
$cardScheme			= trim($_REQUEST['cardScheme']);;
$cardIssuer			= trim($_REQUEST['cardIssuer']);r;
$cardIssuerCountry	= trim($_REQUEST['cardIssuerCountry']);;

$displayAmount	= trim($_REQUEST['displayAmount']);;
$cardExpiryDate	= trim($_REQUEST['cardExpiryDate']);;
$customerName	= trim($_REQUEST['customerName']);;
$remoteAddress	= trim($_REQUEST['remoteAddress']);;
$currencyCode	= trim($_REQUEST['currencyCode']);;
//$cancelReason			= trim($_REQUEST['cancelReason']);
$curmod_arr			= explode('__',$_REQUEST['custom']);
$curmod 			= $curmod_arr[1];
$sess_id            = $curmod_arr[2];

$fp = fopen('testings/fidelity-response.txt','a+');
fwrite($fp, "orderid=".$order_id."  ".$timestamp);
fwrite($fp, "\n");
fwrite($fp, $curmod);

fwrite($fp, "\n");

fwrite($fp, print_r($_POST, TRUE));
fclose($fp);
if($curmod=='ord')
{
	 	
	// Check whether an entry exists in order_payment_barclaycard table for current order
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_fidelity
						WHERE
							orders_order_id = $order_id
							AND pay_type = 'Order' 
							AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		fwrite($fp, $sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the details
		{
			$update_array					= array();
			$update_array['sites_site_id']	= $ecom_siteid;
			$update_array['responseCode']	= $responseCode;
			$update_array['responseMessage']	= $responseMessage;
			$update_array['transactionID']	= $transactionID;
			$update_array['xref']	= $xref;
			$update_array['state']	= $state;
			$update_array['timestamp']	= $timestamp;
			$update_array['transactionUnique']	= $transactionUnique;
			$update_array['referralPhone']	= $referralPhone;
			$update_array['amountReceived']	= $amountReceived;
			$update_array['orderRef']	= $orderRef;
			$update_array['cardNumberMask']	= $cardNumberMask;
			$update_array['cardTypeCode']	= $cardTypeCode;
			$update_array['cardType']	= $cardType;
			$update_array['cardSchemeCode']	= $cardSchemeCode;
			$update_array['cardScheme']	= $cardScheme;
			$update_array['cardIssuer']	= $cardIssuer;
			$update_array['cardIssuerCountry']	= $cardIssuerCountry;	
			
			$update_array['displayAmount']	= $displayAmount;			
			$update_array['cardExpiryDate']	= $cardExpiryDate;			
			$update_array['customerName']	= $customerName;			
			$update_array['remoteAddress']	= $remoteAddress;			
			$update_array['currencyCode']	= $currencyCode;			
			//$update_array['ip']				= addslashes(stripslashes($strip));
			$update_array['pay_type']		= 'Order';
			$db->update_from_array($update_array,'order_payment_fidelity',array('orders_order_id'=>$order_id));
		}
		else // case no record exists. so insert the details
		{
			$insert_array					= array();
			$insert_array['orders_order_id']= $order_id;
			$insert_array['sites_site_id']	= $ecom_siteid;
			$insert_array['responseCode']	= $responseCode;
			$insert_array['responseMessage']	= $responseMessage;
			$insert_array['transactionID']	= $transactionID;
			$insert_array['xref']	= $xref;
			$insert_array['state']	= $state;
			$insert_array['timestamp']	= $timestamp;
			$insert_array['transactionUnique']	= $transactionUnique;
			$insert_array['referralPhone']	= $referralPhone;
			$insert_array['amountReceived']	= $amountReceived;
			$insert_array['orderRef']	= $orderRef;
			$insert_array['cardNumberMask']	= $cardNumberMask;
			$insert_array['cardTypeCode']	= $cardTypeCode;
			$insert_array['cardType']	= $cardType;
			$insert_array['cardSchemeCode']	= $cardSchemeCode;
			$insert_array['cardScheme']	= $cardScheme;
			$insert_array['cardIssuer']	= $cardIssuer;
			$insert_array['cardIssuerCountry']	= $cardIssuerCountry;
						
			$insert_array['displayAmount']	= $displayAmount;			
			$insert_array['cardExpiryDate']	= $cardExpiryDate;			
			$insert_array['customerName']	= $customerName;			
			$insert_array['remoteAddress']	= $remoteAddress;			
			$insert_array['currencyCode']	= $currencyCode;			
			//$insert_array['ip']				= addslashes(stripslashes($strip));
			$insert_array['pay_type']		= 'Order';
			$db->insert_from_array($insert_array,'order_payment_fidelity');
		}
		if($responseCode==0)
		 {
		//$order_id = $orderid;
			$payStat 							= 'Paid';
		
		
			$update_array						= array();
			$update_array['order_paystatus']	= add_slash($payStat);
			$update_array['order_status']		= 'NEW';
			$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
		 // Stock Decrementing section over here
				do_PostOrderSuccessOperations($order_id);				
				// calling function to send any mails 
				send_RequiredOrderMails($order_id);
		}
		 
}
?>
