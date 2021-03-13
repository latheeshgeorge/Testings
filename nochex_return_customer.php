<?php
require("functions/functions.php");
require("includes/session.php");
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

/*$ret_arr 	= explode('__',$_POST['custom']);
$pass_type 	= $ret_arr[0];
$sess_id	= $ret_arr[1];
$ord_id		= $_POST['order_id'];
if ($pass_type=='order')
{
	clear_cart($sess_id);

	// Clear sticky cart variables;
	clear_session_var("cart_total");
	clear_session_var("cart_total_items");
	$ord_id = get_session_var('gateway_ord_id');
	set_session_var('gateway_ord_id',0);
		
	echo "
		<script type='text/javascript'>
			window.location = 'http://".$ecom_hostname."/checkout_success".$ord_id.".html';
		</script>
		";
	*/
		$pass_order_id = get_session_var('gateway_ord_id');
		set_session_var('gateway_ord_id',0);
		
		/*echo "
		<script type='text/javascript'>
			window.location = 'http://".$ecom_hostname."/nochex_success.html';
		</script>
		";*/
		
		echo "
		<script type='text/javascript'>
			window.location = '".$ecom_selfhttp.$ecom_hostname."/checkout_success".$pass_order_id.".html';
		</script>
		";
		
/*}
elseif($pass_type=='payonaccount')
{
		clear_PayonAccountCart($sess_id);
		set_session_var('gateway_payonaccount_id',0);
		echo "
		<script type='text/javascript'>
			window.location = 'http://".$ecom_hostname."/payonaccount_success.html';
		</script>
		";
}		
elseif($pass_type =='voucher')
{
		$voucher_id = $ord_id;
		clear_VoucherCart($sess_id);
		set_session_var('gateway_voucher_id',0);
		$succ = "voucher_success".$voucher_id.".html";
		 // will modify the following when doing the voucher section
		echo	"
				<script type='text/javascript'>
					window.location = '".url_link($succ,1)."';
				</script>
				";
}*/		
exit;
?>
