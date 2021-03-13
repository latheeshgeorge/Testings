<?php
switch($row_vouch['voucher_paymentmethod'])
{
	case 'WORLD_PAY': // Display the form for worldpay
		$pass_type					= 'voucher';
		$button_maincaption 		= 'Confirm Voucher';
		$button_clickmsg			= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		$button_class				= 'buttonred_cart';
		
		// Setting up the values to be passed to worldpay gateway
		$return_order_arr['payData']['gateway_addons']['worldpay_instId'] 	= $_REQUEST['pass_world_instId'];
		$return_order_arr['order_id']										= $_REQUEST['pass_world_cartId'];
		$return_order_arr['payData']['gateway_addons']['curr_code']			= $_REQUEST['pass_world_currency'];
		$return_order_arr['payData']['gateway_addons']['desc']				= $_REQUEST['pass_world_desc'];
		$return_order_arr['payData']['gateway_addons']['pass_total']		= $_REQUEST['pass_world_total'];
		$return_order_arr['payData']['gateway_addons']['mode']				= $_REQUEST['pass_world_mode'];
		
		$row_ord['order_custfname']											= $row_vouch_cust['voucher_fname'];
		$row_ord['order_buildingnumber']									= $row_vouch_cust['voucher_buildingno'];	
		$row_ord['order_street']											= $row_vouch_cust['voucher_street'];
		$row_ord['order_city']												= $row_vouch_cust['voucher_city'];	
		$row_ord['order_state']												= $row_vouch_cust['voucher_state'];	
		$row_ord['order_country']											= $row_vouch_cust['voucher_country'];	
		$row_ord['order_custpostcode']										= $row_vouch_cust['voucher_zip'];
		$row_ord['order_custphone']											= $row_vouch_cust['voucher_phone'];
		$row_ord['order_custfax']											= $row_vouch_cust['voucher_fax'];
		$row_ord['order_custemail']											= $row_vouch_cust['voucher_email'];	
	

		include 'includes/world_pay.php';
	break;
	case 'HSBC':
		$button_maincaption 		= 'Confirm Voucher';
		$button_clickmsg			= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		$button_class				= 'buttonred_cart';
		
		$return_order_arr['order_id'] 											= $return_voucher_arr['voucher_id'];
		$return_order_arr['payData']['gateway_addons']['time']					= $_REQUEST['pass_hsbc_timestamp'];
		$return_order_arr['payData']['gateway_addons']['CpiReturnUrl']			= $_REQUEST['pass_hsbc_CpiReturnUrl'];
		$return_order_arr['payData']['gateway_addons']['CpiDirectResultUrl']	= $_REQUEST['pass_hsbc_CpiDirectResultUrl'];
		$return_order_arr['payData']['gateway_addons']['hsbc_merchant_id']		= $_REQUEST['pass_hsbc_merchant_id'];
		$return_order_arr['payData']['gateway_addons']['OrderDesc']				= $_REQUEST['pass_hsbc_OrderDesc'];		 	
		$return_order_arr['payData']['gateway_addons']['pass_total']			= $_REQUEST['pass_hsbc_PurchaseAmount'];
		$return_order_arr['payData']['gateway_addons']['PurchaseCurrency']		= $_REQUEST['pass_hsbc_PurchaseCurrency'];
		$return_order_arr['payData']['gateway_addons']['TransactionType']		= $_REQUEST['pass_hsbc_TransactionType'];
		$return_order_arr['payData']['gateway_addons']['Mode']					= $_REQUEST['pass_hsbc_Mode'];
		$return_order_arr['payData']['gateway_addons']['MerchantData']			= $_REQUEST['pass_hsbc_MerchantData'];
		$row_ord['order_custfname']												= $_REQUEST['pass_hsbc_BillingFirstName'];
		$row_ord['order_custsurname']											= $_REQUEST['pass_hsbc_BillingLastName'];
		$row_ord['order_custemail']												= $_REQUEST['pass_hsbc_ShopperEmail'];
		$row_ord['order_buildingnumber']										= $_REQUEST['pass_hsbc_Billingbuildingname'];
		$row_ord['order_street']												= $_REQUEST['pass_hsbc_Billingstreet'];
		$row_ord['order_city']													= $_REQUEST['pass_hsbc_BillingCity'];
		$row_ord['order_state']													= $_REQUEST['pass_hsbc_BillingCounty'];
		$row_ord['order_custpostcode']											= $_REQUEST['pass_hsbc_BillingPostal'];
		$return_order_arr['payData']['gateway_addons']['pass_country']			= $_REQUEST['pass_hsbc_BillingCountry'];
		$return_order_arr['payData']['gateway_addons']['del_name']				= $_REQUEST['pass_hsbc_ShippingFirstName'];
		$return_order_arr['payData']['gateway_addons']['del_last_name']			= $_REQUEST['pass_hsbc_ShippingLastName'];
		$return_order_arr['payData']['gateway_addons']['del_building_name']		= $_REQUEST['pass_hsbc_Shippingbuildingname'];
		$return_order_arr['payData']['gateway_addons']['del_street']			= $_REQUEST['pass_hsbc_Shippingstreet'];
		$return_order_arr['payData']['gateway_addons']['del_city']				= $_REQUEST['pass_hsbc_ShippingCity'];
		$return_order_arr['payData']['gateway_addons']['del_county']			= $_REQUEST['pass_hsbc_ShippingCounty'];
		$return_order_arr['payData']['gateway_addons']['del_postCode']			= $_REQUEST['pass_hsbc_ShippingPostal'];
		$return_order_arr['payData']['gateway_addons']['pass_country']			= $_REQUEST['pass_hsbc_ShippingCountry'];
		$return_order_arr['payData']['gateway_addons']['hash']					= $_REQUEST['pass_hsbc_hash'];
		
		include("includes/hsbc_pay.php");
	break;
	case 'PROTX_VSP':
		$button_maincaption 		= 'Confirm Voucher';
		$pass_type					= 'voucher';
		$button_clickmsg			= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		$button_class				= 'buttonred_cart';
		$return_order_arr['payData']['gateway_addons']['strPurchaseURL']			= $_REQUEST['pass_strPurchaseURL'];
		$return_order_arr['payData']['gateway_addons']['strTransactionType']	= $_REQUEST['pass_TxType'];
		$return_order_arr['payData']['gateway_addons']['strVSPVendorName']	= $_REQUEST['pass_Vendor'];
		$return_order_arr['payData']['gateway_addons']['strCrypt']					= $_REQUEST['pass_Crypt'];
		include 'includes/protx_vsp.php';
	break;
	case 'GOOGLE_CHECKOUT':
		$pass_type 											= 'voucher';
		$row_google['paymethod_id']							= $ecom_common_settings['paymethodKey']["GOOGLE_CHECKOUT"] ["paymethod_id"];
		$paymethod_arr['GOOGLE_HASH_KEY']					= $_REQUEST['pass_google_hash'];
		$paymethod_arr['GOOGLE_MERCHANT_ID']				= $_REQUEST['pass_google_merchant_id'];
		$cartData["totals"]["bonus_price"]					= $_REQUEST['pass_google_amount'];
		$button_clickmsg									= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		
		require_once('includes/google_library/googlecart.php');
		require_once('includes/google_library/googleitem.php');
		require_once('includes/google_library/googleshipping.php');
		require_once('includes/google_library/googletax.php');
		include("includes/google_checkout.php");

	break;
	case 'NOCHEX': // case of nochex
		$button_maincaption 		= 'Confirm Voucher';
		$pass_type					= 'voucher';
		$button_clickmsg			= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		$button_class				= 'buttonred_cart';
		$return_order_arr['payData']['gateway_addons']['nochex_merchantid']		= $_REQUEST['pass_nochex_merchantid'];
		$return_order_arr['payData']['gateway_addons']['pass_total']			= $_REQUEST['pass_nochex_total'];
		$return_order_arr['payData']['gateway_addons']['desc']					= $_REQUEST['pass_nochex_desc'];
		$return_order_arr['payData']['gateway_addons']['mode']					= $_REQUEST['pass_nochex_mode'];
		$return_order_arr['order_id']											= $_REQUEST['pass_nochex_orderid'];
		// Get the billing address details from gift voucher table
		$sql_gift = "SELECT  voucher_title,voucher_fname,voucher_mname,voucher_surname,voucher_buildingno,voucher_street, voucher_city ,voucher_state ,
						voucher_country ,  voucher_zip ,  voucher_phone ,  voucher_email 
						FROM 
							gift_vouchers_customer 
						WHERE 
							voucher_id = ".$_REQUEST['pass_nochex_orderid']." 
						LIMIT 
							1";
		$ret_gift = $db->query($sql_gift);
		if($db->num_rows($ret_gift))
		{
			$row_gift = $db->fetch_array($ret_gift);
			$row_ord['order_custfname'] 		= stripslashes($row_gift['voucher_fname']);
			$row_ord['order_custsurname']		= stripslashes($row_gift['voucher_surname']);
			$row_ord['order_buildingnumber']	= stripslashes($row_gift['voucher_buildingno']);
			$row_ord['order_street']			= stripslashes($row_gift['voucher_street']);
			$row_ord['order_city']				= stripslashes($row_gift['voucher_city']);
			$row_ord['order_state']				= stripslashes($row_gift['voucher_state']);
			$row_ord['order_country']			= stripslashes($row_gift['voucher_country']);
			$row_ord['order_custpostcode']		= stripslashes($row_gift['voucher_zip']);
			$row_ord['order_custemail']			= stripslashes($row_gift['voucher_email']);
			$row_ord['order_custphone']			= stripslashes($row_gift['voucher_phone']);
			$row_ord['cancel_url']				= 'http://'.$ecom_hostname.'/buy_voucher.html';
			$session_id							= $sessid;
		}					
		include 'includes/nochex.php';
	break;
	case 'REALEX': // case of realex
		$button_maincaption 		= 'Confirm Voucher';
		$pass_type					= 'voucher';
		$button_clickmsg			= 'Please wait... Loading '.getpaymentmethod_Name($row_vouch['voucher_paymentmethod']);
		$button_class				= 'buttonred_cart';
		$return_order_arr['payData']['gateway_addons']['real_merchantid']		= $_REQUEST['pass_realex_merchantid'];
		$return_order_arr['payData']['gateway_addons']['real_secretcode']		= $_REQUEST['pass_realex_secretcode'];
		$return_order_arr['payData']['gateway_addons']['real_account']			= $_REQUEST['pass_realex_account'];
		$return_order_arr['payData']['gateway_addons']['curr_code']				= $_REQUEST['pass_realex_curr_code'];
		$return_order_arr['payData']['gateway_addons']['pass_total']			= $_REQUEST['pass_realex_total'];
		$return_order_arr['payData']['gateway_addons']['desc']					= $_REQUEST['pass_realex_desc'];
		$return_order_arr['order_id']											= $_REQUEST['pass_realex_orderid'];
		$session_id																= $sessid;
		include 'includes/realex.php';
	break;
};
?>