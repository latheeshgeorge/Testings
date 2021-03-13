<?php
				switch($return_order_arr['payMethod']['paymethod_key'])
				{
					case 'ABLE2BUY': // Display the form for able2buy
						$button_maincaption = 'Confirm Order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'buttonred_cart';
						include 'includes/able2verify.php';
					break;
					case 'WORLD_PAY': // Display the form for worldpay
						$button_maincaption = 'Confirm Order';
						$pass_type			= 'order';
						$button_clickmsg	= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class		= 'buttonred_cart';
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
						$button_class			= 'buttonred_cart';
						include("includes/hsbc_pay.php");
					break;
					case 'PROTX_VSP':
						$button_maincaption 	= 'Confirm Order';
						$pass_type				= 'order';
						$button_clickmsg		= 'Please wait... Loading '.getpaymentmethod_Name($return_order_arr['payMethod']['paymethod_key']);
						$button_class			= 'buttonred_cart';
						include 'includes/protx_vsp.php';
					break;
				};
			?>	