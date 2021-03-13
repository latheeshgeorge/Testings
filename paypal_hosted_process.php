<?
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");



	$fp = fopen ('finance_test/paypal_response.txt','a+');

	ob_start();
	echo date("r")."\n================================\n";
	/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
	print_r($_POST);

	$content = ob_get_contents();
	ob_end_clean();
	fwrite($fp,$content);
	fclose($fp);
	
	
	/*$_GET['typ'] = 'order';
	$_POST['mc_gross'] = '3.25';
    $_POST['invoice'] = '4929';
    $_POST['protection_eligibility'] = 'Ineligible';
    $_POST['address_status'] = 'unconfirmed';
    $_POST['payer_id'] = 'D7UF8EE76EJL4';
    $_POST['tax'] = '0.00';
    $_POST['address_street'] = 'g g';
    $_POST['payment_date'] = '04:00:26 May 23, 2014 PDT';
    $_POST['payment_status'] = 'Completed';//'Pending';
    $_POST['charset'] = 'windows-1252';
    $_POST['address_zip'] = 'g';
    $_POST['first_name'] = 'g';
    $_POST['mc_fee'] = '0.31';
    $_POST['address_country_code'] = 'GB';
    $_POST['address_name'] = 'g g';
    $_POST['notify_version'] = '3.8';
    $_POST['custom'] = 'order';
    $_POST['payer_status'] = 'unverified';
    $_POST['business'] = 'sonyjo_1267103837_biz@gmail.com';
    $_POST['address_country'] = 'United Kingdom';
    $_POST['address_city'] = 'g';
    $_POST['quantity'] = '1';
    $_POST['verify_sign'] = 'AYJIrZQBhLZuLOJgzYSnv-ilyLZgABkj2psPi0xT7LaZgji6xSbqutvk';
    $_POST['txn_id'] = '22E22106T39626922';
    $_POST['payment_type'] = 'instant';
    $_POST['last_name'] = 'g';
    $_POST['address_state'] = 'g';
    $_POST['receiver_email'] = 'sonyjo_1267103837_biz@gmail.com';
    $_POST['payment_fee'] = '';
    $_POST['receiver_id'] = 'CSFTGCQKN494S';
    $_POST['pending_reason'] = 'paymentreview';
    $_POST['txn_type'] = 'web_accept';
    $_POST['item_name'] = '';
    $_POST['mc_currency'] = 'GBP';
    $_POST['item_number'] = '';
    $_POST['residence_country'] = 'US';
    $_POST['test_ipn'] = '1';
    $_POST['receipt_id'] = '3379-5971-4593-6037';
    $_POST['handling_amount'] = '0.00';
    $_POST['transaction_subject'] = '';
    $_POST['payment_gross'] = '';
    $_POST['shipping'] = '0.00';
    $_POST['ipn_track_id'] = '1dfff1294f600';*/
	
	
	
	$p_type 					= trim($_GET['typ']);
    $p_mcgross 					= trim($_POST['mc_gross']);
    $p_invoice 					= trim($_POST['invoice']);
    $p_protection				= trim($_POST['protection_eligibility']);
    $p_addressstat				= trim($_POST['address_status']);
    $p_payerid 					= trim($_POST['payer_id']);
    $p_tax	 					= trim($_POST['tax']);
    $p_addressstreet			= trim($_POST['address_street']);
    $p_paydate 					= trim($_POST['payment_date']);
    $p_paystatus				= trim($_POST['payment_status']);
    $p_charset					= trim($_POST['charset']);
    $p_addresszip				= trim($_POST['address_zip']);
    $p_fname 					= trim($_POST['first_name']);
    $p_mcfee 					= trim($_POST['mc_fee']);
    $p_addresscountrycode		= trim($_POST['address_country_code']);
    $p_addressname				= trim($_POST['address_name']);
    $p_notifyver				= trim($_POST['notify_version']);
    $p_custom 					= trim($_POST['custom']);
    $p_payerstat				= trim($_POST['payer_status']);
    $p_business					= trim($_POST['business']);
    $p_addresscountry			= trim($_POST['address_country']);
    $p_addresscity				= trim($_POST['address_city']);
    $p_qty						= trim($_POST['quantity']);
	$p_verifysign				= trim($_POST['verify_sign']);
    $p_txnid					= trim($_POST['txn_id']);
    $p_paymenttype				= trim($_POST['payment_type']);
    $p_lastname					= trim($_POST['last_name']);
    $p_addressstate				= trim($_POST['address_state']);
    $p_receiveremail			= trim($_POST['receiver_email']);
    $p_paymentfee				= trim($_POST['payment_fee']);
    $p_receiverid				= trim($_POST['receiver_id']);
    $p_pendingreason			= trim($_POST['pending_reason']);
    $p_txntype					= trim($_POST['txn_type']);
    $p_itemname					= trim($_POST['item_name']);
    $p_mccurrency				= trim($_POST['mc_currency']);
    $p_itemnumber				= trim($_POST['item_number']);
	$p_residencecountry			= trim($_POST['residence_country']);
    $p_testipn					= trim($_POST['test_ipn']);
	$p_receiptid				= trim($_POST['receipt_id']);
	$p_handlingamt				= trim($_POST['handling_amount']);
	$p_transactionsubject		= trim($_POST['transaction_subject']);
	$p_paymentgross				= trim($_POST['payment_gross']);
	$p_shipping					= trim($_POST['shipping']);
	$p_ipntrackid				= trim($_POST['ipn_track_id']);
	
	
	
	
	$valid_and_proceed = false;
	if($p_invoice!='')
	{
		// Check whether the order or voucher of payonaccount id is valid
		if($p_custom=='order')
		{
			$sql_chk = "SELECT order_id FROM orders WHERE sites_site_id = $ecom_siteid AND order_id = $p_invoice LIMIT 1";
			$ret_chk = $db->query($sql_chk);
			if($db->num_rows($ret_chk))
			{
				$valid_and_proceed = true;
			}
			
		}
		elseif($p_custom=='Voucher')
		{
			$sql_chk = "SELECT voucher_id FROM gift_vouchers WHERE sites_site_id = $ecom_siteid AND voucher_id = $p_invoice LIMIT 1";
			$ret_chk = $db->query($sql_chk);
			if($db->num_rows($ret_chk))
			{
				$valid_and_proceed = true;
			}
		}
		elseif($p_custom=='Payon')
		{
			/*$sql_chk = "SELECT pay_id FROM order_payonaccount_details WHERE sites_site_id = $ecom_siteid AND voucher_id = $p_invoice LIMIT 1";
			$ret_chk = $db->query($sql_chk);
			if($db->num_rows($ret_chk))
			{*/
				$valid_and_proceed = true;
			//}
		}
		if($valid_and_proceed)
		{
			if($p_paystatus=='Completed')
			{
				switch ($p_custom)
				{
					case 'order':	
						$order_id	= $p_invoice;
						$payStat 							= 'Paid';
						$update_array						= array();
						$update_array['order_paystatus']	= add_slash($payStat);
						$update_array['order_status']		= 'NEW';
						$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
						
						// Stock Decrementing section over here
						do_PostOrderSuccessOperations($order_id);
						
						// calling function to send any mails 
						send_RequiredOrderMails($order_id);
						
					break;
					case 'Voucher':
						$voucher_id 	= $p_invoice;
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
					break;
					case 'Payon':
						$pay_id 		= $p_invoice;
						$payStat 		= 'Paid';
						//Post payment success operation
						$org_pay_id = do_PostPayonAccountSuccessOperations($pay_id);
					break;	
				};
			}		
			
			// Check whether an entry already exists
			$sql_alreadyexists = "SELECT orders_order_id,sites_site_id 
									FROM 
										order_payment_paypal_hosted 
									WHERE 
										orders_order_id=$p_invoice 
										AND sites_site_id =$ecom_siteid 
										AND pay_for='".$p_custom."' 
									LIMIT 
										1";
			$ret_alreadyexists = $db->query($sql_alreadyexists);
			if($db->num_rows($ret_alreadyexists)==0) // does not exists
			{
				$insert_array							= array();
				$insert_array['orders_order_id']		= addslashes($p_invoice);
				$insert_array['sites_site_id'] 			= addslashes($ecom_siteid);
				$insert_array['pay_for']				= addslashes($p_type);
				$insert_array['p_mcgross'] 				= addslashes($p_mcgross);
				$insert_array['p_invoice']				= addslashes($p_invoice);
				$insert_array['p_protection']			= addslashes($p_protection);
				$insert_array['p_addressstat']			= addslashes($p_addressstat);
				$insert_array['p_payerid'] 				= addslashes($p_payerid);
				$insert_array['p_tax']					= addslashes($p_tax);
				$insert_array['p_addressstreet']		= addslashes($p_addressstreet);
				$insert_array['p_paydate'] 				= addslashes($p_paydate);
				$insert_array['p_paystatus']			= addslashes($p_paystatus);
				$insert_array['p_charset']				= addslashes($p_charset);
				$insert_array['p_addresszip']			= addslashes($p_addresszip);
				$insert_array['p_fname']				= addslashes($p_fname);
				$insert_array['p_mcfee']				= addslashes($p_mcfee);
				$insert_array['p_addresscountrycode']	= addslashes($p_addresscountrycode);
				$insert_array['p_addressname']			= addslashes($p_addressname);
				$insert_array['p_notifyver']			= addslashes($p_notifyver);
				$insert_array['p_custom'] 				= addslashes($p_custom);
				$insert_array['p_payerstat']			= addslashes($p_payerstat);
				$insert_array['p_business']				= addslashes($p_business);
				$insert_array['p_addresscountry']		= addslashes($p_addresscountry);
				$insert_array['p_addresscity']			= addslashes($p_addresscity);
				$insert_array['p_qty']					= addslashes($p_qty);
				$insert_array['p_verifysign']			= addslashes($p_verifysign);
				$insert_array['p_txnid']				= addslashes($p_txnid);
				$insert_array['p_paymenttype']			= addslashes($p_paymenttype);
				$insert_array['p_lastname']				= addslashes($p_lastname);
				$insert_array['p_addressstate']			= addslashes($p_addressstate);
				$insert_array['p_receiveremail']		= addslashes($p_receiveremail);
				$insert_array['p_paymentfee']			= addslashes($p_paymentfee);
				$insert_array['p_receiverid']			= addslashes($p_receiverid);
				$insert_array['p_pendingreason']		= addslashes($p_pendingreason);
				$insert_array['p_txntype']				= addslashes($p_txntype);
				$insert_array['p_itemname']				= addslashes($p_itemname);
				$insert_array['p_mccurrency']			= addslashes($p_mccurrency);
				$insert_array['p_itemnumber']			= addslashes($p_itemnumber);
				$insert_array['p_residencecountry']		= addslashes($p_residencecountry);
				$insert_array['p_testipn']				= addslashes($p_testipn);
				$insert_array['p_receiptid']			= addslashes($p_receiptid);
				$insert_array['p_handlingamt']			= addslashes($p_handlingamt);
				$insert_array['p_transactionsubject']	= addslashes($p_transactionsubject);
				$insert_array['p_paymentgross']			= addslashes($p_paymentgross);
				$insert_array['p_shipping']				= addslashes($p_shipping);
				$insert_array['p_ipntrackid']			= addslashes($p_ipntrackid);
				$db->insert_from_array($insert_array,'order_payment_paypal_hosted');
			}
			else // already exists
			{
				$update_array											= array();
				if($p_type!='')	$update_array['pay_for'] 				= addslashes($p_type);
				if($p_mcgross!='') $update_array['p_mcgross'] 				= addslashes($p_mcgross);
				if($p_invoice!='') $update_array['p_invoice']				= addslashes($p_invoice);
				if($p_protection!='') $update_array['p_protection']			= addslashes($p_protection);
				if($p_addressstat!='') $update_array['p_addressstat']			= addslashes($p_addressstat);
				if($p_payerid!='') $update_array['p_payerid'] 				= addslashes($p_payerid);
				if($p_tax!='') $update_array['p_tax']					= addslashes($p_tax);
				if($p_addressstreet!='') $update_array['p_addressstreet']		= addslashes($p_addressstreet);
				if($p_paydate!='') $update_array['p_paydate'] 				= addslashes($p_paydate);
				if($p_paystatus!='') $update_array['p_paystatus']			= addslashes($p_paystatus);
				if($p_charset!='') $update_array['p_charset']				= addslashes($p_charset);
				if($p_addresszip!='') $update_array['p_addresszip']			= addslashes($p_addresszip);
				if($p_fname!='') $update_array['p_fname']				= addslashes($p_fname);
				if($p_mcfee!='') $update_array['p_mcfee']				= addslashes($p_mcfee);
				if($p_addresscountrycode!='') $update_array['p_addresscountrycode']	= addslashes($p_addresscountrycode);
				if($p_addressname!='') $update_array['p_addressname']			= addslashes($p_addressname);
				if($p_notifyver!='') $update_array['p_notifyver']			= addslashes($p_notifyver);
				if($p_custom!='') $update_array['p_custom'] 				= addslashes($p_custom);
				if($p_payerstat!='') $update_array['p_payerstat']			= addslashes($p_payerstat);
				if($p_business!='') $update_array['p_business']				= addslashes($p_business);
				if($p_addresscountry!='') $update_array['p_addresscountry']		= addslashes($p_addresscountry);
				if($p_addresscity!='') $update_array['p_addresscity']			= addslashes($p_addresscity);
				if($p_qty!='') $update_array['p_qty']					= addslashes($p_qty);
				if($p_verifysign!='') $update_array['p_verifysign']			= addslashes($p_verifysign);
				if($p_txnid!='') $update_array['p_txnid']				= addslashes($p_txnid);
				if($p_paymenttype!='') $update_array['p_paymenttype']			= addslashes($p_paymenttype);
				if($p_lastname!='') $update_array['p_lastname']				= addslashes($p_lastname);
				if($p_addressstate!='') $update_array['p_addressstate']			= addslashes($p_addressstate);
				if($p_receiveremail!='') $update_array['p_receiveremail']		= addslashes($p_receiveremail);
				if($p_paymentfee!='') $update_array['p_paymentfee']			= addslashes($p_paymentfee);
				if($p_receiverid!='') $update_array['p_receiverid']			= addslashes($p_receiverid);
				if($p_pendingreason!='') $update_array['p_pendingreason']		= addslashes($p_pendingreason);
				if($p_txntype!='') $update_array['p_txntype']				= addslashes($p_txntype);
				if($p_itemname!='') $update_array['p_itemname']				= addslashes($p_itemname);
				if($p_mccurrency!='') $update_array['p_mccurrency']			= addslashes($p_mccurrency);
				if($p_itemnumber!='') $update_array['p_itemnumber']			= addslashes($p_itemnumber);
				if($p_residencecountry!='') $update_array['p_residencecountry']		= addslashes($p_residencecountry);
				if($p_testipn!='') $update_array['p_testipn']				= addslashes($p_testipn);
				if($p_receiptid!='') $update_array['p_receiptid']			= addslashes($p_receiptid);
				if($p_handlingamt!='') $update_array['p_handlingamt']			= addslashes($p_handlingamt);
				if($p_transactionsubject!='') $update_array['p_transactionsubject']	= addslashes($p_transactionsubject);
				if($p_paymentgross!='') $update_array['p_paymentgross']			= addslashes($p_paymentgross);
				if($p_shipping!='') $update_array['p_shipping']				= addslashes($p_shipping);
				if($p_ipntrackid!='') $update_array['p_ipntrackid']			= addslashes($p_ipntrackid);
				if(count($update_array))
					$db->update_from_array($update_array,'order_payment_paypal_hosted',array('orders_order_id'=>$p_invoice,'sites_site_id'=>$ecom_siteid));
			}
			
			
		}
	}
	
	
	
	
	
	/*$ctstr = '';
	foreach ($_POST as $k=>$v)
	{
		if($k!='typ')
		{
			$ctstr .= '&'."$k=$v";
		}
	}
	$API_Endpoint = 'https://www.paypal.com/cgi-bin/webscr';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the request as a POST FIELD for curl.
	$pass_str = 'cmd=_notify-validate'.$ctstr;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pass_str);

	// Get response from the server.
	$httpResponse = curl_exec($ch);
	
	$fp = fopen ('finance_test/paypal_response.txt','a+');

	ob_start();
	echo date("r")."\n================================\n";*/
	/* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
	
	/*echo "REsponse<br>-----------------------<br>";
	print_r($httpResponse);

	$content = ob_get_contents();
	ob_end_clean();
	fwrite($fp,$content);
	fclose($fp);*/
?>
