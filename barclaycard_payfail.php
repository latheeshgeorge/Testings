<?php
	require("functions/functions.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/urls.php");
	require("config.php");

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
	if($strstatus!=9)
	{
		if($strcomplus == 'order') 
		{
			$order_id 	= $orderid;
			// Calling function to delete the order details if payment is a failure
			deleteOrder_on_failure($order_id);
			echo "
				<script type='text/javascript'>
					window.location = '".url_link('checkout_failed.html',1)."';
				</script>
				";
			exit;

		}
		elseif($strcomplus == 'voucher') 
		{
			$voucher_id = $orderid;
			// Delete gift voucher details
			deleteVoucher_on_failure($voucher_id);
			// Redirect to voucher failure page;
			echo "
					<script type='text/javascript'>
						window.location = '".url_link('voucher_failed.html',1)."';
					</script>
					";
			exit;	
		}
		elseif($strcomplus == 'payonaccount') 
		{
			$pay_id = $orderid;
			// Delete payonaccount details
			deletePayonAccount_on_failure($pay_id);
			// Redirect to voucher failure page;
			echo "
					<script type='text/javascript'>
						window.location = '".url_link('payonaccount_failed.html',1)."';
					</script>
					";
			exit;		
		}
	}

?>
