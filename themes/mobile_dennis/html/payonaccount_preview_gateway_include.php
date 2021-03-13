<?php
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
switch($row_pay['pay_paymentmethod'])
{
	case 'WORLD_PAY': // Display the form for worldpay
		$pass_type					= 'payonaccount';
		$button_maincaption 		= 'Confirm Pay On Account Payment';
		$button_clickmsg			= 'Please wait...Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
		$button_class				= 'buttonred_cart';
		// Setting up the values to be passed to worldpay gateway
		$return_order_arr['payData']['gateway_addons']['worldpay_instId'] 	= $_REQUEST['pass_world_instId'];
		$return_order_arr['order_id']										= $_REQUEST['pass_world_cartId'];
		$return_order_arr['payData']['gateway_addons']['curr_code']			= $_REQUEST['pass_world_currency'];
		$return_order_arr['payData']['gateway_addons']['desc']				= $_REQUEST['pass_world_desc'];
		$return_order_arr['payData']['gateway_addons']['pass_total']		= $_REQUEST['pass_world_total'];
		$return_order_arr['payData']['gateway_addons']['mode']				= $_REQUEST['pass_world_mode'];
		
		$row_ord['order_custfname']											= $row_cust['customer_fname'];
		$row_ord['order_buildingnumber']									= $row_cust['customer_buildingname'];	
		$row_ord['order_street']											= $row_cust['customer_streetname'];
		$row_ord['order_city']												= $row_cust['customer_towncity'];	
		$row_ord['order_state']												= $row_cust['customer_statecounty'];	
		$row_ord['order_country']											= $cust_country;
		$row_ord['order_custpostcode']										= $row_cust['customer_postcode'];
		$row_ord['order_custphone']											= $row_cust['customer_phone'];
		$row_ord['order_custfax']											= $row_cust['customer_fax'];
		$row_ord['order_custemail']											= $row_cust['customer_email_7503'];	
	
		include 'includes/world_pay.php';
	break;
	case 'GOOGLE_CHECKOUT':
			$sql_google = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
							  a.paymethod_takecarddetails,a.payment_minvalue,
							  b.payment_method_sites_caption 
						FROM 
							payment_methods a,
							payment_methods_forsites b 
						WHERE 
							a.paymethod_id=b.payment_methods_paymethod_id 
							AND a.paymethod_showinmobile  =1   
							AND b.sites_site_id = $ecom_siteid 
							AND payment_method_sites_active =1 
							AND paymethod_key='GOOGLE_CHECKOUT' 
						LIMIT 
							1";
			$ret_google = $db->query($sql_google);
			if($db->num_rows($ret_google))
			{
				$row_google = $db->fetch_array($ret_google);
				$cust_details 	= stripslash_normal($row_cust['customer_title']).stripslash_normal($row_cust['customer_fname']).' '.stripslash_normal($row_cust['customer_surname']).' - '.stripslash_normal($row_cust['customer_email_7503']).' - '.$ecom_hostname;
				$cartData["totals"]["bonus_price"] = $row_pay['pay_amount'];
				
				
				// Get the details from cart_supportdetails related to current session and current site
				$sql_cartdet = "SELECT * 
								FROM 
									cart_supportdetails 
								WHERE 
									session_id='".$session_id."'  
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_cartdet = $db->query($sql_cartdet);
				if($db->num_rows($ret_cartdet)) 
					$row_cartdet 		= $db->fetch_array($ret_cartdet); // Fetch the details to a record set
				$pass_type				= 'payonaccount';
				$button_clickmsg		= 'Please wait... Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
				require_once('includes/google_library/googlecart.php');
				require_once('includes/google_library/googleitem.php');
				require_once('includes/google_library/googleshipping.php');
				require_once('includes/google_library/googletax.php');
				include("includes/google_checkout.php");
			}	
		break;
	case 'HSBC':
		$button_maincaption 	= 'Confirm Pay On Account Payment';
		$button_clickmsg		= 'Please wait...Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
		$button_class				= 'buttonred_cart';

		$return_order_arr['order_id'] 											= $return_pay_arr['pay_id'];
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
		$button_maincaption 													= 'Confirm Pay On Account Payment';
		$pass_type																= 'payonaccount';
		$button_clickmsg														= 'Please wait...Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
		$button_class															= 'buttonred_cart';
		$return_order_arr['payData']['gateway_addons']['strPurchaseURL']		= $_REQUEST['pass_strPurchaseURL'];
		$return_order_arr['payData']['gateway_addons']['strTransactionType']	= $_REQUEST['pass_TxType'];
		$return_order_arr['payData']['gateway_addons']['strVSPVendorName']		= $_REQUEST['pass_Vendor'];
		$return_order_arr['payData']['gateway_addons']['strCrypt']				= $_REQUEST['pass_Crypt'];
		include 'includes/protx_vsp.php';
	break;
	case 'NOCHEX':
		$button_maincaption 													= 'Confirm Pay On Account Payment';
		$pass_type																= 'payonaccount';
		$button_clickmsg														= 'Please wait...Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
		$button_class															= 'buttonred_cart';
		$return_order_arr['payData']['gateway_addons']['nochex_merchantid']		= $_REQUEST['pass_nochex_merchantid'];
		$return_order_arr['payData']['gateway_addons']['pass_total']			= $_REQUEST['pass_nochex_total'];
		$return_order_arr['payData']['gateway_addons']['desc']					= $_REQUEST['pass_nochex_desc'];
		$return_order_arr['payData']['gateway_addons']['mode']					= $_REQUEST['pass_nochex_mode'];
		$return_order_arr['order_id']											= $_REQUEST['pass_nochex_orderid'];
		
			$row_ord['order_custfname'] 		= stripslashes($row_cust['customer_fname']);
			$row_ord['order_custsurname']		= stripslashes($row_cust['customer_surname']);
			$row_ord['order_buildingnumber']	= stripslashes($row_cust['customer_buildingname']);
			$row_ord['order_street']			= stripslashes($row_cust['customer_streetname']);
			$row_ord['order_city']				= stripslashes($row_cust['customer_towncity']);
			if($row_cust['country_id'])
			{
				$sql_country = "SELECT country_name 
											FROM 
												general_settings_site_country 
											WHERE 
												country_id = ".$row_cust['country_id']." 
											LIMIT 
												1";
				$ret_country = $db->query($sql_country);
				if ($db->num_rows($ret_country))
				{
					$row_country	 	= $db->fetch_array($ret_couintry);
					$cust_country		= stripslash_normal($row_country['country_name']);		
				}
			}
			$row_ord['order_state']				= stripslashes($row_cust['customer_statecounty']);
			$row_ord['order_country']			= $cust_country;
			$row_ord['order_custpostcode']		= stripslashes($row_cust['customer_postcode']);
			$row_ord['order_custemail']			= stripslashes($row_cust['customer_email_7503']);
			$row_ord['order_custphone']			= stripslashes($row_cust['customer_phone']);
			$row_ord['cancel_url']				= $ecom_selfhttp.$ecom_hostname.'/payonaccountpayment.html';
			$session_id							= $sessid;
						
		include 'includes/nochex.php';
	break;
	case 'REALEX':
		$button_maincaption 													= 'Confirm Pay On Account Payment';
		$pass_type																= 'payonaccount';
		$button_clickmsg														= 'Please wait...Loading '.getpaymentmethod_Name($row_pay['pay_paymentmethod']);
		$button_class															= 'buttonred_cart';
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
