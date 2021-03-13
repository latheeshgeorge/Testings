<?

require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");

// Split out the useful information into variables we can use

 

$orderid			= $_REQUEST['orderID'];
$strcurrency		= $_REQUEST['currency'];
$stramount			= $_REQUEST["amount"];
$strpm				= $_REQUEST["PM"];
$stracceptance		= $_REQUEST["ACCEPTANCE"];
$strstatus			= $_REQUEST["STATUS"];
$strcardno			= $_REQUEST["CARDNO"];
$stred				= $_REQUEST["ED"];
$strcn				= $_REQUEST["CN"];
$strtrxdate			= $_REQUEST["TRXDATE"];
$strpayid			= $_REQUEST["PAYID"];
$strncerror			= $_REQUEST["NCERROR"];
$strbrand			= $_REQUEST["BRAND"];
$strcomplus_arr		= explode('&',$_REQUEST["COMPLUS"]);
$strcomplus			= $strcomplus_arr[0];
$strip				= $_REQUEST["IP"];

$fname = '/var/www/vhosts/bshop4.co.uk/httpdocs/Barclay_error/barclay_return.csv';
		$fp = fopen($fname,'a+');
		fwrite($fp,"$strtrxdate\t $orderid\t $stramount\t $strstatus\t $strpayid\t $strncerror\t $strstatus\t $strcomplus\n");
		$strreq = "";
		foreach ($_REQUEST as $key => $value)
		{
		  $strreq .= "$key:$value\t";
		}
		$strreq .="\n";
		fwrite($fp,$strreq);
		fclose($fp);		

$error = '';
if($strcomplus == 'order') 
{
	$order_id = $orderid;
	// Updating the payment status to paid.
	if($strstatus==9) // case if payment is a success
	{
		$payStat 							= 'Paid';
	
	
		$update_array						= array();
		$update_array['order_paystatus']	= add_slash($payStat);
		$update_array['order_status']		= 'NEW';
		$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));

		// Check whether an entry exists in order_payment_barclaycard table for current order
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_barclaycard
						WHERE
							orders_order_id = $order_id
							AND pay_type = 'Order' 
							AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the details
		{
			$update_array					= array();
			$update_array['sites_site_id']	= $ecom_siteid;
			$update_array['currency']		= mysql_escape_string($strcurrency) ;
			$update_array['amount']			= add_slash($stramount);
			$update_array['pm']				= add_slash($strpm);
			$update_array['acceptance']		= add_slash($stracceptance);
			$update_array['status']			= add_slash($strstatus);
			$update_array['cardno']			= add_slash($strcardno);
			$update_array['ed']				= addslashes(stripslashes($stred));
			$update_array['cn']				= addslashes(stripslashes($strcn));
			$update_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$update_array['payid']			= addslashes(stripslashes($strpayid));
			$update_array['ncerror']		= addslashes(stripslashes($strncerror));
			$update_array['brand']			= addslashes(stripslashes($strbrand));
			$update_array['complus']		= addslashes(stripslashes($strcomplus));
			$update_array['ip']				= addslashes(stripslashes($strip));
			$update_array['pay_type']		= 'Order';
			$db->update_from_array($update_array,'order_payment_barclaycard',array('orders_order_id'=>$order_id));
		}
		else // case no record exists. so insert the details
		{
			$insert_array					= array();
			$insert_array['orders_order_id']= $order_id;
			$insert_array['sites_site_id']	= $ecom_siteid;
			$insert_array['currency']		= mysql_escape_string($strcurrency) ;
			$insert_array['amount']			= add_slash($stramount);
			$insert_array['pm']				= add_slash($strpm);
			$insert_array['acceptance']		= add_slash($stracceptance);
			$insert_array['status']			= add_slash($strstatus);
			$insert_array['cardno']			= add_slash($strcardno);
			$insert_array['ed']				= addslashes(stripslashes($stred));
			$insert_array['cn']				= addslashes(stripslashes($strcn));
			$insert_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$insert_array['payid']			= addslashes(stripslashes($strpayid));
			$insert_array['ncerror']		= addslashes(stripslashes($strncerror));
			$insert_array['brand']			= addslashes(stripslashes($strbrand));
			$insert_array['complus']		= addslashes(stripslashes($strcomplus));
			$insert_array['ip']				= addslashes(stripslashes($strip));
			$insert_array['pay_type']		= 'Order';
			$db->insert_from_array($insert_array,'order_payment_barclaycard');
		}
		
		/*$fname = '/var/www/vhosts/bshop4.co.uk/httpdocs/vs_return/vsp_return.txt';
		$fp = fopen($fname,'a+');
		$order_id = $order_id;
		$cardtype = $strCardType;
		fwrite($fp,"Order Id :$order_id\t Card Type: $cardtype\t Security Key: $strSecurityKey\t Auth no: $strTxAuthNo \n");
		fclose($fp);*/
		
		
		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($order_id);
		
		// calling function to send any mails 
		send_RequiredOrderMails($order_id);

		
	}
}
elseif($strcomplus == 'voucher') 
{
	if($strstatus==9) // case if payment is a success
	{
		$voucher_id 	= $orderid;
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

		// Check whether an entry exists in order_payment_barclaycard table for current gift voucher
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_barclaycard
						WHERE
							orders_order_id = $voucher_id
							AND pay_type = 'Voucher' 
							AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the google_checkoutid only
		{
			$update_array					= array();
			$update_array['sites_site_id']	= $ecom_siteid;
			$update_array['currency']		= mysql_escape_string($strcurrency) ;
			$update_array['amount']			= add_slash($stramount);
			$update_array['pm']				= add_slash($strpm);
			$update_array['acceptance']		= add_slash($stracceptance);
			$update_array['status']			= add_slash($strstatus);
			$update_array['cardno']			= add_slash($strcardno);
			$update_array['ed']				= addslashes(stripslashes($stred));
			$update_array['cn']				= addslashes(stripslashes($strcn));
			$update_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$update_array['payid']			= addslashes(stripslashes($strpayid));
			$update_array['ncerror']		= addslashes(stripslashes($strncerror));
			$update_array['brand']			= addslashes(stripslashes($strbrand));
			$update_array['complus']		= addslashes(stripslashes($strcomplus));
			$update_array['ip']				= addslashes(stripslashes($strip));
			$update_array['pay_type']		= 'Voucher';
			$db->update_from_array($update_array,'order_payment_barclaycard',array('orders_order_id'=>$voucher_id));
		}
		else // case no record exists. so insert the voucher id and google_checkoutid
		{
			$insert_array					= array();
			$insert_array['orders_order_id']= $voucher_id;
			$insert_array['sites_site_id']	= $ecom_siteid;
			$insert_array['currency']		= mysql_escape_string($strcurrency) ;
			$insert_array['amount']			= add_slash($stramount);
			$insert_array['pm']				= add_slash($strpm);
			$insert_array['acceptance']		= add_slash($stracceptance);
			$insert_array['status']			= add_slash($strstatus);
			$insert_array['cardno']			= add_slash($strcardno);
			$insert_array['ed']				= addslashes(stripslashes($stred));
			$insert_array['cn']				= addslashes(stripslashes($strcn));
			$insert_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$insert_array['payid']			= addslashes(stripslashes($strpayid));
			$insert_array['ncerror']		= addslashes(stripslashes($strncerror));
			$insert_array['brand']			= addslashes(stripslashes($strbrand));
			$insert_array['complus']		= addslashes(stripslashes($strcomplus));
			$insert_array['ip']				= addslashes(stripslashes($strip));
			$insert_array['pay_type']		= 'Voucher';
			$db->insert_from_array($insert_array,'order_payment_barclaycard');
		}
	}			
}
elseif($strcomplus == 'payonaccount') 
{
	
	if($strstatus==9) // case if payment is a success
	{
		$pay_id 		= $orderid;
		$payStat 		= 'Paid';


		// Check whether an entry exists in gift_vouchers_payment table for current gift voucher
		$sql_check = "SELECT orders_order_id
						FROM
							order_payment_barclaycard
						WHERE
							orders_order_id = $pay_id 
							AND pay_type = 'Payonaccount'
							AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // case record exists. so update the google_checkoutid only
		{
			$update_array					= array();
			$update_array['sites_site_id']	= $ecom_siteid;
			$update_array['currency']		= mysql_escape_string($strcurrency) ;
			$update_array['amount']			= add_slash($stramount);
			$update_array['pm']				= add_slash($strpm);
			$update_array['acceptance']		= add_slash($stracceptance);
			$update_array['status']			= add_slash($strstatus);
			$update_array['cardno']			= add_slash($strcardno);
			$update_array['ed']				= addslashes(stripslashes($stred));
			$update_array['cn']				= addslashes(stripslashes($strcn));
			$update_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$update_array['payid']			= addslashes(stripslashes($strpayid));
			$update_array['ncerror']		= addslashes(stripslashes($strncerror));
			$update_array['brand']			= addslashes(stripslashes($strbrand));
			$update_array['complus']		= addslashes(stripslashes($strcomplus));
			$update_array['ip']				= addslashes(stripslashes($strip));
			$update_array['pay_type']		= 'Payonaccount';
			$db->update_from_array($update_array,'order_payment_barclaycard',array('orders_order_id'=>$pay_id));
		}
		else // case no record exists. so insert the voucher id and google_checkoutid
		{
			$insert_array					= array();
			$insert_array['orders_order_id']= $pay_id;
			$insert_array['sites_site_id']	= $ecom_siteid;
			$insert_array['currency']		= mysql_escape_string($strcurrency) ;
			$insert_array['amount']			= add_slash($stramount);
			$insert_array['pm']				= add_slash($strpm);
			$insert_array['acceptance']		= add_slash($stracceptance);
			$insert_array['status']			= add_slash($strstatus);
			$insert_array['cardno']			= add_slash($strcardno);
			$insert_array['ed']				= addslashes(stripslashes($stred));
			$insert_array['cn']				= addslashes(stripslashes($strcn));
			$insert_array['trxdate']		= addslashes(stripslashes($strtrxdate));
			$insert_array['payid']			= addslashes(stripslashes($strpayid));
			$insert_array['ncerror']		= addslashes(stripslashes($strncerror));
			$insert_array['brand']			= addslashes(stripslashes($strbrand));
			$insert_array['complus']		= addslashes(stripslashes($strcomplus));
			$insert_array['ip']				= addslashes(stripslashes($strip));
			$insert_array['pay_type']		= 'Payonaccount';
			$db->insert_from_array($insert_array,'order_payment_barclaycard');
		}
		//Post payment success operation
		$org_pay_id = do_PostPayonAccountSuccessOperations($pay_id );
	}			
}
?>
