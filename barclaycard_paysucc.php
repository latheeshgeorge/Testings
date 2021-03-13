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
	$sessionID_arr		= explode('b4barclaysessid=',$strcomplus_arr[1]);
	$sessionID			= $sessionID_arr[1];
	if($strstatus==9)
	{
		if($strcomplus == 'order') 
		{
				//Clearing the cart
			clear_cart($sessionID);

			// Clear sticky cart variables;
			clear_session_var("cart_total");
			clear_session_var("cart_total_items");
			$succ = "checkout_success".$orderid.".html";
			echo "
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
			exit;
		}
		elseif($strcomplus == 'voucher') 
		{
			// Clearing the voucher cart
			clear_VoucherCart($sessionID);
			// will modify the following when doing the voucher section
			$succ = "voucher_success".$orderid.".html";
			echo	"
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
			exit;	
		}
		elseif($strcomplus == 'payonaccount') 
		{
			// Clearing the payonaccount cart
			clear_PayonAccountCart($sessionID);
			$succ = "payonaccount_success".$orderid.".html";
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
			exit;		
		}
	}

?>
