<?php
	require("functions/functions.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/urls.php");
	require("config.php");
	
	$fp = fopen ('finance_test/return_new.txt','a+');

	ob_start();
	echo date("r")."\n================================\n";
	/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
	print_r($_GET);

	$content = ob_get_contents();
	ob_end_clean();
	fwrite($fp,$content);
	fclose($fp);
	$retstat = $_REQUEST['st'];
	//$refno_arr = explode(':',$_REQUEST['retaileruniqueref']);
	//$order_id = $refno_arr[1];
	$order_id = $_REQUEST['retaileruniqueref'];
	switch($retstat)
	{
		case 'succ':
			$sessionID = session_id();
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
		break;
		case 'fail':
		case 'cancel':
			echo "
				<script type='text/javascript'>
					window.location = '".url_link('checkout_failed.html',1)."';
				</script>
				";
			exit;
		break;
	};

?>
