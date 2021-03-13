<?php
/*#################################################################
# Script Name 	: gift_voucher_submit.php
# Description 		: This is the page to which the gift voucher html buying will get submitted to
# Coded by 		: Sny
# Created on		: 15-May-2008
# Modified by		: Sny
# Modified On		: 08-Dec-2008
#################################################################*/
	include_once("functions/functions.php");
	include('includes/session.php');
	require_once("config.php");
	require("includes/payment.php");
	require("includes/urls.php");
	require("includes/price_display.php");
	$save_det               = trim($_REQUEST['save_voucherdetails']);
        $coming_from_paypal     = $_REQUEST['for_paypal'];
        $prm                    = $_REQUEST['prm'];
        
         

	// Calling the function to get all the general settings variables and store it in an array
	//$Settings_arr 			= getGeneralSettings();

	// Calling function ot get all the price display settings for the current site
	//$PriceSettings_arr		= getPriceDisplaySettings();
	
	// Including the general settings array file
	if(file_exists($image_path.'/settings_cache/general_settings.php'))
		include "$image_path/settings_cache/general_settings.php";
		
	// Including the price display settings array file
	if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
		include "$image_path/settings_cache/price_display_settings.php";	

	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']	= getCaptions('COMMON');

	// Calling the function to get the details of default currency
	$default_Currency_arr	= get_default_currency();
	$ecom_common_settings 	= get_Common_Settings();
	
	// Assigning the current currency to the variable
	$sitesel_curr			= get_session_var('SEL_CURR');
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
	}

	// Handling the case of coming to this page directly
	if(!$save_det or $save_det==0)
	{
		displayInvalidInput();
		exit;
	}
	// #####################################################################################################
	// Calling the function to Save the voucher cart details
	// #####################################################################################################
        Save_Voucher_cart($coming_from_paypal);

        if($coming_from_paypal==1) // case of coming by clicking the paypal express icon from gift voucher page
        {
            // Setting up variables to be passed to the following include file
            $cartData["totals"]["bonus_price"]     = trim($_REQUEST['voucher_value']);
            $gateway_mod        = 'Voucher';
            // include the paypal_express file to get the token
            include("includes/paypal_express.php");
            exit; // since the above file will redirect the user to PAYPAL no need to execute what ever is below this
        }
	// Checking whether the image verification code exists
	if ($_REQUEST['buygiftvoucher_Vimg'])
	{
		$vImage->loadCodes('buygiftvoucher_Vimg');
		if(!$vImage->checkCode('buygiftvoucher_Vimg'))
		{
			if ($protectedUrl==true)
				$http = url_protected('index.php?req=voucher&action_purpose=buy',1);
			else
				$http = url_link('buy_voucher.html',1);
			if($_REQUEST['token']!='')
				$pret_pass= 1;
			else
				$pret_pass = 0;	
			echo "
                                <form method='post' name='temp_submit' id='temp_submit' action='$http'>...
                                <input type='hidden' name='rt' value='2'/>
                                <input type='hidden' name='token' value='".$_REQUEST['token']."'/>
                                <input type='hidden' name='payer_id' value='".$_REQUEST['payer_id']."'/>
                                <input type='hidden' name='pret' value='".$pret_pass."'/>
                                </form>
				<script type='text/javascript'>
					document.temp_submit.submit();
				</script>
				";
			/*echo "
				<script type='text/javascript'>
					window.location = '".$http."';
				</script>
				";*/
			exit;
		}
	}
	// #####################################################################################################
	// Calling the function to save the voucher details
	// #####################################################################################################
		$ret_voucher_id = Save_VoucherDetails();
		// validating the returned voucher id
		if(!$ret_voucher_id or $ret_voucher_id==0)
		{
			displayInvalidInput();
			exit;
		}
	// #####################################################################################################
	// Calling the function to handle the payment related things
	// #####################################################################################################
	$return_voucher_arr = handle_Giftvoucher_PaymentDetails($ret_voucher_id);
	$session_id 		= session_id();
	if ($return_voucher_arr['voucher_id'])
	{
		if ($return_voucher_arr['payMethod']!='')// case if payment method exists
		{
			// Check whether the order preview page is to be displayed
			switch($return_voucher_arr['payMethod'])
			{
				case 'HSBC':
				case 'WORLD_PAY':
				case 'PROTX_VSP':
				case 'GOOGLE_CHECKOUT':
				case 'NOCHEX':
				case 'REALEX':
				case 'BARCLAYCARD':
					$to_preview_state		=	 'preview_before_gateway';
					$to_preview_mod		=   false;
				break;
				case 'PROTX': //protx
					if($return_voucher_arr['payData']['result']==1) // if payment is successfull
					{
						//Clearing the cart for voucher
							clear_VoucherCart($session_id);
							$to_preview_state 	= 'preview_after_protx';
							$to_preview_mod		= true;
					}
				break;
				case 'PAYPAL_EXPRESS': //paypal express
				case 'PAYPALPRO': //paypal express
						if($return_voucher_arr['payData']['result']==1) // if payment is successfull
						{
								//Clearing the cart for voucher
										clear_VoucherCart($session_id);
										$to_preview_state       = 'preview_after_protx';
										$to_preview_mod         = true;
						}
				break;
				case 'SELF': // Self
					// show voucher success page
					//Clearing the cart for voucher
					clear_VoucherCart($session_id);
					$to_preview_state 	= 'preview_after_self';
					$to_preview_mod		= true;
				break;
			};
		}
		else
		{
				switch($return_voucher_arr['payType'])
				{
					case 'cheque': // Checque
					case 'invoice': // invoice
					case 'cash_on_delivery': // cash_on_delivery
					case 'pay_on_phone': // cash_on_delivery
							//Clearing the cart for voucher
							clear_VoucherCart($session_id);
							$to_preview_state	= 'preview_after_ptype';
							$to_preview_mod		= true;
					break;
					case 'pay_on_account': // case of pay on account
						// Get the required details from the gift_vouchers table
						$sql_vouch = "SELECT voucher_number, voucher_boughton, voucher_paystatus, voucher_paymenttype, voucher_paymentmethod,voucher_value 
												FROM 
													gift_vouchers 
												WHERE 
													voucher_id =".$return_voucher_arr['voucher_id']." 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
						$ret_vouch = $db->query($sql_vouch);
						if ($db->num_rows($ret_vouch))
						{
							$row_vouch = $db->fetch_array($ret_vouch);
							$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
							// If any exists for current order id in this table delete it 
							$sql_del = "DELETE FROM 
												order_payonaccount_details 
											WHERE 
												gift_vouchers_voucher_id = ".$return_voucher_arr['voucher_id']." 
											LIMIT 
												1";
							$db->query($sql_del);
							// Making entry to order_payonaccount_details
							$insert_array														= array();
							$insert_array['pay_date']										= 'now()';
							$insert_array['gift_vouchers_voucher_id']				= $return_voucher_arr['voucher_id'];
							$insert_array['sites_site_id']									= $ecom_siteid;
							$insert_array['customers_customer_id']					= $cust_id;
							$insert_array['pay_amount']									= $row_vouch['voucher_value'];
							$insert_array['pay_transaction_type']						= 'D';	
							$insert_array['pay_details']									= 'Gift Voucher  '.$row_vouch['voucher_number'];
							$insert_array['pay_paystatus']								= $row_vouch['voucher_paymenttype'];
							$insert_array['pay_paymenttype']							= $row_vouch['voucher_paymenttype'];
							$insert_array['pay_paymentmethod']						= '';
							$db->insert_from_array($insert_array,'order_payonaccount_details');
							
							// Decrement the pay on account limit for current customer
							$update_sql = "UPDATE customers 
														SET 
															customer_payonaccount_usedlimit = customer_payonaccount_usedlimit + ".$row_vouch['voucher_value'] ." 
														WHERE 
															sites_site_id = $ecom_siteid 
															AND customer_id = ".$cust_id." 
														LIMIT 
															1";
							$db->query($update_sql);
							clear_VoucherCart($session_id);
							$to_preview_state	= 'pay_succ';
							$to_preview_mod		= true;
						}	
					break;
				};
		}
	}
	else// to handle the case refreshing or clicked the back button in browser while in voucher preview page
	{
		if ($protectedUrl==true)
			$http = url_protected('index.php?req=voucher&action_purpose=buy',1);
		else
			$http = url_link('buy_voucher.html',1);
		echo "
			<script type='text/javascript'>
				window.location = '".$http."';
			</script>
			";
		exit;
	}
		// Building a form here to submit the values we have here to take the user to the voucher preview page using javascript. Also deciding whether should be forwarded to http or https
		if ($protectedUrl==true)
			$http = url_protected('index.php?req=voucher&action_purpose=show_voucherpreview',1);
		else
			$http = url_link('voucher_preview.html',1);
				
		echo '
				<form method="post" name="frm_vouch_autosubmit" action ="'.$http.'" id="frm_vouch_autosubmit">
				<input type="hidden" name="vouch_id" value="'.$return_voucher_arr['voucher_id'].'"/>
				<input type="hidden" name="pass_TxType" value="'.$return_voucher_arr['payData']['gateway_addons']['strTransactionType'].'"/>
				<input type="hidden" name="pass_Vendor" value="'.$return_voucher_arr['payData']['gateway_addons']['vendor_id'].'"/>
				<input type="hidden" name="pass_Crypt" value="'.$return_voucher_arr['payData']['gateway_addons']['strCrypt'].'"/>
				<input type="hidden" name="pass_strPurchaseURL" value="'.$return_voucher_arr['payData']['gateway_addons']['strPurchaseURL'].'"/>
				
				<input type="hidden" name="pass_hsbc_timestamp" value="'.$return_voucher_arr['payData']['gateway_addons']['time'].'"/>
				<input type="hidden" name="pass_hsbc_CpiReturnUrl" value="'.$return_voucher_arr['payData']['gateway_addons']['CpiReturnUrl'].'"/>
				<input type="hidden" name="pass_hsbc_CpiDirectResultUrl" value="'.$return_voucher_arr['payData']['gateway_addons']['CpiDirectResultUrl'].'"/>
				<input type="hidden" name="pass_hsbc_merchant_id" value="'.$return_voucher_arr['payData']['gateway_addons']['hsbc_merchant_id'].'"/>
				<input type="hidden" name="pass_hsbc_OrderDesc" value="'.$return_voucher_arr['payData']['gateway_addons']['OrderDesc'].'"/>
				<input type="hidden" name="pass_hsbc_PurchaseAmount" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				<input type="hidden" name="pass_hsbc_PurchaseCurrency" value="'.$return_voucher_arr['payData']['gateway_addons']['PurchaseCurrency'].'"/>
				<input type="hidden" name="pass_hsbc_TransactionType" value="'.$return_voucher_arr['payData']['gateway_addons']['TransactionType'].'"/>
				<input type="hidden" name="pass_hsbc_Mode" value="'.$return_voucher_arr['payData']['gateway_addons']['Mode'].'"/>
				<input type="hidden" name="pass_hsbc_MerchantData" value="'.$return_voucher_arr['payData']['gateway_addons']['MerchantData'].'"/>
				<input type="hidden" name="pass_hsbc_BillingFirstName" value="'.$return_voucher_arr['payData']['gateway_addons']['del_name'].'"/>
				<input type="hidden" name="pass_hsbc_BillingLastName" value="'.$return_voucher_arr['payData']['gateway_addons']['del_last_name'].'"/>
				<input type="hidden" name="pass_hsbc_ShopperEmail" value="'.$return_voucher_arr['payData']['gateway_addons']['del_email'].'"/>
				<input type="hidden" name="pass_hsbc_Billingbuildingname" value="'.$return_voucher_arr['payData']['gateway_addons']['del_building_name'].'"/>
				<input type="hidden" name="pass_hsbc_Billingstreet" value="'.$return_voucher_arr['payData']['gateway_addons']['del_street'].'"/>
				<input type="hidden" name="pass_hsbc_BillingCity" value="'.$return_voucher_arr['payData']['gateway_addons']['del_city'].'"/>
				<input type="hidden" name="pass_hsbc_BillingCounty" value="'.$return_voucher_arr['payData']['gateway_addons']['del_county'].'"/>
				<input type="hidden" name="pass_hsbc_BillingPostal" value="'.$return_voucher_arr['payData']['gateway_addons']['del_postCode'].'"/>
				<input type="hidden" name="pass_hsbc_BillingCountry" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_country'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingFirstName" value="'.$return_voucher_arr['payData']['gateway_addons']['del_name'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingLastName" value="'.$return_voucher_arr['payData']['gateway_addons']['del_last_name'].'"/>
				<input type="hidden" name="pass_hsbc_Shippingbuildingname" value="'.$return_voucher_arr['payData']['gateway_addons']['del_building_name'].'"/>
				<input type="hidden" name="pass_hsbc_Shippingstreet" value="'.$return_voucher_arr['payData']['gateway_addons']['del_street'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingCity" value="'.$return_voucher_arr['payData']['gateway_addons']['del_city'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingCounty" value="'.$return_voucher_arr['payData']['gateway_addons']['del_county'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingPostal" value="'.$return_voucher_arr['payData']['gateway_addons']['del_postCode'].'"/>
				<input type="hidden" name="pass_hsbc_ShippingCountry" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_country'].'"/>
				<input type="hidden" name="pass_hsbc_hash" value="'.$return_voucher_arr['payData']['gateway_addons']['hash'].'"/>
				
				<input type="hidden" name="pass_world_instId" value="'.$return_voucher_arr['payData']['gateway_addons']['worldpay_instId'].'"/>
				<input type="hidden" name="pass_world_cartId" value="'.$return_voucher_arr['voucher_id'].'"/>
				<input type="hidden" name="pass_world_currency" value="'.$return_voucher_arr['payData']['gateway_addons']['curr_code'].'"/>
				<input type="hidden" name="pass_world_desc" value="'.$return_voucher_arr['payData']['gateway_addons']['desc'].'"/>
				<input type="hidden" name="pass_world_total" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				<input type="hidden" name="pass_world_amount" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				<input type="hidden" name="pass_world_mode" value="'.$return_voucher_arr['payData']['gateway_addons']['mode'].'"/>
				
				<input type="hidden" name="pass_google_hash" value="'.$return_voucher_arr['payData']['gateway_addons']['google_hash'].'"/>
				<input type="hidden" name="pass_google_merchant_id" value="'.$return_voucher_arr['payData']['gateway_addons']['google_merchant_id'].'"/>
				<input type="hidden" name="pass_google_desc" value="'.$return_voucher_arr['payData']['gateway_addons']['desc'].'"/>
				<input type="hidden" name="pass_google_curr_code" value="'.$return_voucher_arr['payData']['gateway_addons']['curr_code'].'"/>
				<input type="hidden" name="pass_google_mode" value="'.$return_voucher_arr['payData']['gateway_addons']['mode'].'"/>
				<input type="hidden" name="pass_google_amount" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				
				<input type="hidden" name="pass_nochex_merchantid" value="'.$return_voucher_arr['payData']['gateway_addons']['nochex_merchantid'].'"/>
				<input type="hidden" name="pass_nochex_orderid" value="'.$return_voucher_arr['voucher_id'].'"/>
				<input type="hidden" name="pass_nochex_desc" value="'.$return_voucher_arr['payData']['gateway_addons']['desc'].'"/>
				<input type="hidden" name="pass_nochex_mode" value="'.$return_voucher_arr['payData']['gateway_addons']['mode'].'"/>
				<input type="hidden" name="pass_nochex_total" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				
				<input type="hidden" name="pass_realex_merchantid" value="'.$return_voucher_arr['payData']['gateway_addons']['real_merchantid'].'"/>
				<input type="hidden" name="pass_realex_secretcode" value="'.$return_voucher_arr['payData']['gateway_addons']['real_secretcode'].'"/>
				<input type="hidden" name="pass_realex_account" value="'.$return_voucher_arr['payData']['gateway_addons']['real_account'].'"/>
				<input type="hidden" name="pass_realex_curr_code" value="'.$return_voucher_arr['payData']['gateway_addons']['curr_code'].'"/>
				<input type="hidden" name="pass_realex_orderid" value="'.$return_voucher_arr['voucher_id'].'"/>
				<input type="hidden" name="pass_realex_desc" value="'.$return_voucher_arr['payData']['gateway_addons']['desc'].'"/>
				<input type="hidden" name="pass_realex_total" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				
				<input type="hidden" name="pass_barclaycard_shaincode" value="'.$return_voucher_arr['payData']['gateway_addons']['shaincode'].'"/>
				<input type="hidden" name="pass_barclaycard_pspid" value="'.$return_voucher_arr['payData']['gateway_addons']['pspid'].'"/>
				<input type="hidden" name="pass_barclaycard_language" value="'.$return_voucher_arr['payData']['gateway_addons']['language'].'"/>
				<input type="hidden" name="pass_barclaycard_catalogurl" value="'.$return_voucher_arr['payData']['gateway_addons']['catalogurl'].'"/>
				<input type="hidden" name="pass_barclaycard_logo" value="'.$return_voucher_arr['payData']['gateway_addons']['logo'].'"/>
				<input type="hidden" name="pass_barclaycard_title" value="'.$return_voucher_arr['payData']['gateway_addons']['title'].'"/>
				<input type="hidden" name="pass_barclaycard_bgcolor" value="'.$return_voucher_arr['payData']['gateway_addons']['bgcolor'].'"/>
				<input type="hidden" name="pass_barclaycard_txtcolor" value="'.$return_voucher_arr['payData']['gateway_addons']['txtcolor'].'"/>
				<input type="hidden" name="pass_barclaycard_buttonbg_color" value="'.$return_voucher_arr['payData']['gateway_addons']['buttonbg_color'].'"/>
				<input type="hidden" name="pass_barclaycard_buttontxt_color" value="'.$return_voucher_arr['payData']['gateway_addons']['buttontxt_color'].'"/>
				<input type="hidden" name="pass_barclaycard_fonttype" value="'.$return_voucher_arr['payData']['gateway_addons']['fonttype'].'"/>
				<input type="hidden" name="pass_barclaycard_curr_code" value="'.$return_voucher_arr['payData']['gateway_addons']['curr_code'].'"/>
				<input type="hidden" name="pass_barclaycard_orderid" value="'.$return_voucher_arr['voucher_id'].'"/>
				<input type="hidden" name="pass_barclaycard_desc" value="'.$return_voucher_arr['payData']['gateway_addons']['desc'].'"/>
				<input type="hidden" name="pass_barclaycard_total" value="'.$return_voucher_arr['payData']['gateway_addons']['pass_total'].'"/>
				
				
				
				<input type="hidden" name="preview_state" id="preview_state" value="'.$to_preview_state.'"/>
				<input type="hidden" name="preview_mod" id="preview_mod" value="'.$to_preview_mod.'"/>
				<div style="position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold">Loading...</div>
				</form>
		   	 ';
		/* Submitting the form here */
		echo '
				<script type="text/javascript">
				document.frm_vouch_autosubmit.submit();
				</script>
			';
		exit;
?>
