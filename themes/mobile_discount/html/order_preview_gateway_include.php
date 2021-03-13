<?php
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
				switch($return_order_arr['payMethod']['paymethod_key'])
				{
					case 'ABLE2BUY': // Display the form for able2buy
						$button_maincaption = 'Confirm Order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'gate_way_btn';
						include 'includes/able2verify.php';
					break;
					case 'WORLD_PAY': // Display the form for worldpay
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'gate_way_btn';
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
								$row_cartdet = $db->fetch_array($ret_cartdet); // Fetch the details to a record set
							$pass_type 			= 'ord';
							$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
							require_once('includes/google_library/googlecart.php');
							require_once('includes/google_library/googleitem.php');
							require_once('includes/google_library/googleshipping.php');
							require_once('includes/google_library/googletax.php');
							include("includes/google_checkout.php");
						}	
					break;
					case 'HSBC':
						$button_maincaption 	= 'Confirm Order';
						$button_clickmsg		= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class			= 'gate_way_btn';
						include("includes/hsbc_pay.php");
					break;
					case 'PROTX_VSP':
						$button_maincaption 	= 'Confirm Order';
						$pass_type				= 'order';
						$button_clickmsg		= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class			= 'byellow_det';
						include 'includes/protx_vsp.php';
					break;
					case 'NOCHEX': // Display the form for nochex NPP
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$row_ord['cancel_url']				= $ecom_selfhttp.$ecom_hostname.'/cart.html';
						$button_class		= 'gate_way_btn';
						include 'includes/nochex.php';
					break;
					case 'REALEX': // Display the form for Realex gateway
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'gate_way_btn';
						include 'includes/realex.php';
					break;
					case 'PAYPAL_HOSTED': // case of paypal hosted
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'gate_way_btn';
						include 'includes/paypal_hosted.php';
					break;
					case 'BARCLAYCARD': // Display the form for barclaycard gateway
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'byellow_det';
						include 'includes/barclaycard.php';
					break;
					case 'FIDELITY': // Display the form for FIDELITY gateway
						$button_maincaption = 'Confirm Order Now';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'buttonred_cart';
						include 'includes/fidelity.php';
					break;
				};
				
				/* ------------------- 4 min finance - start ---------------------------*/
				switch($return_order_arr['payType'])
				{
					case '4min_finance': // Display the form for able2buy
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading ';//.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'buttonred_cart';
						include 'includes/4minfinance.php';
					break;
				};
			?>	