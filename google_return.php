<?php
require("functions/functions.php");
require("includes/session.php");
require("includes/urls.php");
require("config.php");
$pass_type = $_REQUEST['pass_type'];
$sess_id	= session_id();

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}

if ($pass_type=='ord')
{
	clear_cart($sess_id);

	// Clear sticky cart variables;
	clear_session_var("cart_total");
	clear_session_var("cart_total_items");
	$ord_id = get_session_var('gateway_ord_id');
	$ord_id	= (!$ord_id)?'':$ord_id;
	set_session_var('gateway_ord_id',0);
		
	echo "
		<script type='text/javascript'>
			window.location = '".$ecom_selfhttp.$ecom_hostname."/checkout_success".$ord_id.".html';
		</script>
		";
}
elseif($pass_type=='payonaccount')
{
		clear_PayonAccountCart($sess_id);
		set_session_var('gateway_payonaccount_id',0);
		echo "
		<script type='text/javascript'>
			window.location = '".$ecom_selfhttp.$ecom_hostname."/payonaccount_success.html';
		</script>
		";
}		
elseif($pass_type =='voucher')
{
		$sess_id = $_POST['M_sessionID'];
		$voucher_id = $_REQUEST['vid'];
		clear_VoucherCart($sess_id);
		set_session_var('gateway_voucher_id',0);
		$succ = "voucher_success".$voucher_id.".html";
		 // will modify the following when doing the voucher section
			echo	"
					<script type='text/javascript'>
						window.location = '".url_link($succ,1)."';
					</script>
					";
}		
exit;
?>
