<?php
	/*#################################################################
	# Script Name 		: actions.php
	# Description 		: This is the page for holding some of the actions for various components
	# Coded by 			: Sny
	# Created on		: 05-Dec-2007
	# Modified by		: Sny 
	# Modified On		: 08-Dec-2008
	#################################################################*/
	$cookval = get_session_var('b4cookie_productid');
	
	$cust_id			= get_session_var("ecom_login_customer"); // Get the customer id from session
	$sess = session_id();
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	if($ecom_siteid==103)
	{
		$bgcolor = '#4DAFBB';
		$color 	= '#FFFFFF';
	}
	else
	{
		$bgcolor = '#CC0000';
		$color = '#FFFFFF';
	}	
	
	if(!$cust_id)
	{
		// update the products in cart which have cart_prom_id != 0 to 0 for current session
		// This is done to avoid the case of logging out after placing the price promise product to cart. In such a case such products will become normal products
		$sess = session_id();
		$update_cart = "UPDATE 
							cart 
						SET 
							cart_prom_id = 0 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND session_id='$sess' 
							AND cart_prom_id !=0";
		$db->query($update_cart);
	}
	// Check whether any products exists in cart
	$sql_cartcheck = "SELECT cart_id 
						FROM 
							cart 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND session_id = '$sess' 
						LIMIT 
							1";
	$ret_cartcheck = $db->query($sql_cartcheck);
	if($db->num_rows($ret_cartcheck)==0)
	{
		clear_session_var("cart_total");
		clear_session_var("cart_total_items");
	}
	// ################################################################################################################
	// Check whether return to this page on order failure or returning from the order preview page with out proceeding to the payment gateway, so delete all the details of current order
	// ################################################################################################################
	$gateway_order_id = get_session_var('gateway_ord_id');
	if ($gateway_order_id)
	{
		if($gateway_order_id>0)
		{
			$req_arr = array ('vsp_success','vsp_fail'); // consider the case of deleting the order only if current request is not there in the above defined array
			if(!in_array($_REQUEST['req'],$req_arr))
			{
				// Check whether current order exists and the status is not paid
				$sql_chk = "SELECT order_id  
									FROM 
										orders 
									WHERE 
										order_id = $gateway_order_id 
										AND sites_site_id = $ecom_siteid 
										AND order_paystatus NOT IN ('Paid','FRAUD_REVIEW') 
									LIMIT 
										1";
				$ret_chk = $db->query($sql_chk);
				if ($db->num_rows($ret_chk))
					deleteOrder_on_failure($gateway_order_id);
			}		
			set_session_var('gateway_ord_id',0);
		}
	}
	// ###################################################################################################################
	// Check whether return to this page on order failure or returning from the voucher preview page with out proceeding to the payment gateway, so delete all the details of current voucher
	// ###################################################################################################################
	$gateway_voucher_id = get_session_var('gateway_voucher_id');
	if ($gateway_voucher_id)
	{
		if($gateway_voucher_id>0)
		{
			$req_arr = array ('vsp_success','vsp_fail'); // consider the case of deleting the order only if current request is not there in the above defined array
			if(!in_array($_REQUEST['req'],$req_arr))
			{
				// Check whether current order exists and the status is not paid
				$sql_chk = "SELECT voucher_id  
									FROM 
										gift_vouchers  
									WHERE 
										voucher_id = $gateway_voucher_id  
										AND sites_site_id = $ecom_siteid 
										AND voucher_paystatus NOT IN ('Paid','FRAUD_REVIEW') 
									LIMIT 
										1";
				$ret_chk = $db->query($sql_chk);
				if ($db->num_rows($ret_chk))
					deleteVoucher_on_failure($gateway_voucher_id);
			}		
			set_session_var('gateway_voucher_id',0);
		}
	}
	// ###############################################################################################################
	//to incluse the search page for the request for fetching the product for the searchfilter components
	//################################################################################################################
	$module_name ='mod_searchfilter';
	if(in_array($module_name,$inlineSiteComponents))
	{ 
		if(!$_REQUEST['quick_search'] && $_REQUEST['search_id']) 
		{
			$sql_search_kw 				= "SELECT 
														search_keyword 
													FROM 
														saved_search 
													WHERE 
														sites_site_id=$ecom_siteid 
														AND search_id=".$_REQUEST['search_id']." 
													LIMIT 
														1";
			$res_search_kw 				= $db->query($sql_search_kw);
			list($kw) 					= $db->fetch_array($res_search_kw);
			
			$_REQUEST['quick_search'] 	= $kw;
			$search_id					= $_REQUEST['search_id'];
		}
		if($_REQUEST['req']=='search')
		{
			$from_actions = true;
			include('includes/base_files/search.php');
			$from_actions = false;
		}
		if($_REQUEST['req']=='categories')
		{
			$from_actions = true;
			include('includes/base_files/categories.php');
			$from_actions = false;
		}
	}
	// ###################################################################################################################
	// Check whether return to this page on order failure or returning from the payonaccount preview page with out proceeding to the payment gateway, so delete all the details of current payonaccount
	// ###################################################################################################################
	$gateway_payonaccount_id = get_session_var('gateway_payonaccount_id');
	if ($gateway_payonaccount_id)
	{
		if($gateway_payonaccount_id>0)
		{
			$req_arr = array ('vsp_success','vsp_fail'); // consider the case of deleting the order only if current request is not there in the above defined array
			if(!in_array($_REQUEST['req'],$req_arr))
			{
				// Check whether current order exists and the status is not paid
				$sql_chk = "SELECT pendingpay_id   
									FROM 
										order_payonaccount_pending_details  
									WHERE 
										pendingpay_id = $gateway_payonaccount_id   
										AND sites_site_id = $ecom_siteid 
										AND pay_paystatus NOT IN ('Paid','FRAUD_REVIEW') 
									LIMIT 
										1";
				$ret_chk = $db->query($sql_chk);
				if ($db->num_rows($ret_chk))
					deletePayonAccount_on_failure($gateway_payonaccount_id);
			}		
			set_session_var('gateway_payonaccount_id',0);
		}
	}
	// ###################################################################################
	// ###################################################################################
	//setting cookie for finding recently viewed products 
	// ###################################################################################
	if($_REQUEST['product_id'])
	{
		if(!$cookval)
		{
			set_session_var('b4cookie_productid',$_REQUEST['product_id']);
			$cookiearray 	= array($_REQUEST['product_id']);
			//print_r($cookiearray);
			$cookieval 		= $_REQUEST['product_id'];
		}
		else
		{
			$cookiearray = explode(",",$cookval);
			if(in_array($_REQUEST['product_id'],$cookiearray))
			{
				$temp_arr	= array();
				foreach ($cookiearray as $key => $value)
				{
					if($value != $_REQUEST['product_id'] )
					{
						$temp_arr[] = $value;
					}
				}
				if (count($temp_arr))
				{
					$cookieval = $_REQUEST['product_id'].",".implode(",",$temp_arr);
				}
				else
					$cookieval = $_REQUEST['product_id'];	
				set_session_var('b4cookie_productid',$cookieval);
			}
			else
			{
				if (count($cookiearray)>=6)
					$cookiearray = array_slice($cookiearray,0,5);
				$cookieval = $_REQUEST['product_id'].','.implode(",",$cookiearray);
				set_session_var('b4cookie_productid',$cookieval);
			}
						

		}
	}
	else
		$cookieval = $cookval;
	// Removing recently viewed products
	if($_REQUEST['remove_recent'])
	{
		$cookieval = array();
		clear_session_var('b4cookie_productid');
	}	
	
	// ###################################################################################
	// Validation and inserting to the newsletter subscription from components
	// ###################################################################################
	if ($_REQUEST['newsletter_Submit'])
	{
		$alert='';
		$fieldRequired = $fieldDescription = array();
		if($Settings_arr['newsletter_title']==1)
		{
			$fieldRequired[] 		= $_REQUEST['newsletter_title'];
			$fieldDescription[] 	= 'Title';
		}
		if($Settings_arr['newsletter_name']==1)
		{
			$fieldRequired[] 		= $_REQUEST['newsletter_name'];
			$fieldDescription[] 	= 'Name';
		}
		if($Settings_arr['newsletter_email']==1)
		{
			$fieldRequired[] 		= $_REQUEST['newsletter_email'];
			$fieldDescription[] 	= 'Email Id';
		}
		if($Settings_arr['imageverification_req_newsletter']==1)
		{ 
			$fieldRequired[] 		= $_REQUEST['newsletter_Vimg'];
			$fieldDescription[] 	= 'Image Verification Code';
		}
		$fieldEmail 		= array($_REQUEST['newsletter_email']);
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if($alert=='')
		{				
			$go_flag = true;
			if($Settings_arr['imageverification_req_newsletter']) {
			$vImage->loadCodes('newsletter_Vimg');
			if($vImage->checkCode('newsletter_Vimg')) {
					$go_flag = true;
				}  else {
					$go_flag = false;
				}
			} 
				// Check whether the email id already exists in the newsletter table
				$sql_exists = "SELECT news_customer_id 
								FROM 
									newsletter_customers 
								WHERE 
									news_custemail ='".add_slash($_REQUEST['newsletter_email'])."' 
									AND sites_site_id = $ecom_siteid
								LIMIT 
									1";
				$ret_exists = $db->query($sql_exists);
				if($ecom_siteid==104 || $ecom_siteid==102 || $ecom_siteid==105 || $ecom_siteid==113 || $ecom_siteid==53 || $ecom_siteid==109 || $ecom_siteid==80 || $ecom_siteid==114 || $ecom_siteid==99 || $ecom_siteid==90 || $ecom_siteid==97 || $ecom_siteid==98)
						{
							$go_gdpr = true;
						}
				//Check whether the emailid is in the customer table.
				if ($db->num_rows($ret_exists))
				{
					$alert = 'Sorry!! Email id already registered for newsletter';
					$row_exists =$db->fetch_array($ret_exists); 
					$news_id = $row_exists['news_customer_id'];
				}
				if(!$alert)
				{  
					// Inserting to newsletter table
					$insert_array					= array();
					
					$sql = "SELECT customer_id FROM customers 
							WHERE customer_email_7503='".add_slash($_REQUEST['newsletter_email'])."' 
							AND sites_site_id = $ecom_siteid";
					$res = $db->query($sql);
					$row = $db->fetch_array($res);
					$num = $db->num_rows($res);
					
					if($num>0) {
						$insert_array['customer_id']	= $row['customer_id'];
					}
					$insert_array['news_title']		= add_slash($_REQUEST['newsletter_title']);
					$insert_array['news_custname']	= add_slash($_REQUEST['newsletter_name']);
					$insert_array['news_custemail']	= add_slash($_REQUEST['newsletter_email']);
					$insert_array['news_custphone']	= add_slash($_REQUEST['newsletter_phone']);
					$insert_array['sites_site_id']	= $ecom_siteid;
					$insert_array['news_join_date']	= 'curdate()';
					$db->insert_from_array($insert_array,'newsletter_customers');
					$news_id						= $db->insert_id();
					// Inserting the mappings to customer_newsletter_group_customers_map table if any group selected
					if (count($_REQUEST['newsletter_group']))
					{
						for($i=0;$i<count($_REQUEST['newsletter_group']);$i++)
						{
							
							$insert_array							= array();
							$insert_array['custgroup_id']			= $_REQUEST['newsletter_group'][$i];
							$insert_array['customer_id']			= $news_id;
							$insert_array['from_newslettergroup']	= 1;
							$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
						}
					}
					$alert = 'Newsletter Subscription Successfull';	
				}	
			
			if($go_flag==false)
			{
				$alert = "Sorry!! Image Verification Code does not match";
			}
			if($alert)
			{
				echo "<script type='text/javascript'>alert ('".$alert."')</script>";
			}
			     if($go_gdpr==true && $go_flag == true)
					{
					 echo '
									<script type="text/javascript"> 
									window.location = "'.url_link('news_gdproptin'.$news_id.'.html',1).'";
									</script>
								';
					}	
			echo "<script type='text/javascript'>window.location = window.location;</script>";
		}
		else // case if error exists
		{
			echo "<script type='text/javascript'>alert ('".$alert."')</script>";
			echo "<script type='text/javascript'>window.location = window.location;</script>";
		}		
	}
	//print_r($_REQUEST);
	// ###################################################################################
	// Case of coming for customer login validation
	// ###################################################################################
	if (($_REQUEST['custologin_Submit'] or $_REQUEST['custcartlogin_Submit'] or $_REQUEST['custenquirelogin_Submit'] or $_REQUEST['custpayonacclogin_Submit'])) 
	{
		if($_REQUEST['return_nocheck']!=1)
		{
			$alert='';
			$fieldRequired		= array($_REQUEST['custlogin_uname'],$_REQUEST['custlogin_pass']);
			$fieldDescription 	= array('Username','Password');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert == '')
			{
				// check whether the username and password are valid
				//$pass 	= base64_encode(add_slash($_REQUEST['custlogin_pass']));
				$pass 		= md5(add_slash($_REQUEST['custlogin_pass']));
				$sql_check	= "SELECT customer_id,customer_allow_product_discount,customer_discount,customer_title, customer_fname, customer_mname, customer_surname  
								FROM 
									customers 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND customer_email_7503 = '".add_slash($_REQUEST['custlogin_uname'])."' 
									AND customer_pwd_9501 = '".$pass."' 
									AND customer_hide=0 
									AND customer_activated =1 
								LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					// Start of section to find the discounts applicable to current customer
					if ($row_check['customer_allow_product_discount']==1) // proceed only if logged in and product discount is applicable to current customer
					{
						// Get the list of customer discount groups to which the current customer is assigned to 
						$sql_custgroup = "SELECT a.cust_disc_grp_id,a.cust_disc_grp_discount,a.cust_apply_direct_discount_also,
												a.cust_apply_direct_product_discount_also    
											FROM 
												customer_discount_group a,customer_discount_customers_map b 
											WHERE 
												a.cust_disc_grp_active = 1 
												AND a.sites_site_id = $ecom_siteid 
												AND b.customers_customer_id = ".$row_check['customer_id']."
												AND a.cust_disc_grp_id = b.customer_discount_group_cust_disc_grp_id 
												AND a.cust_disc_grp_discount>0 
											ORDER BY 
												a.cust_disc_grp_discount ASC";
						$ret_custgroup 		= $db->query($sql_custgroup);
						$disc_group_array 	= $disc_group_prod_array= $disg_group_allow_direct_array = array();
						if ($db->num_rows($ret_custgroup))
						{
							$atleast_one_prod_mapped = false;
							while($row_custgroup = $db->fetch_array($ret_custgroup))
							{
								// Check whether any products linked with this customer group
								$sql_grp_prod = "SELECT products_product_id 
													FROM 
														customer_discount_group_products_map 
													WHERE 
														customer_discount_group_cust_disc_grp_id = ".$row_custgroup['cust_disc_grp_id']. "
														AND sites_site_id=$ecom_siteid";
								$ret_grp_prod = $db->query($sql_grp_prod);
								if ($db->num_rows($ret_grp_prod))
								{
									$cust_group_disc_arr[$row_custgroup['cust_disc_grp_id']]['discount'] = $row_custgroup['cust_disc_grp_discount'];
									while($row_grp_prod = $db->fetch_array($ret_grp_prod))
									{
										$atleast_one_prod_mapped 											= true; // flag to check whether atleast one product mapped to any of the customer discount groups
										$disc_group_prod_array[$row_grp_prod['products_product_id']]		= $row_custgroup['cust_disc_grp_discount'];
										$disc_group_array[$row_grp_prod['products_product_id']] 			= $row_custgroup['cust_disc_grp_id'];
										$disg_group_allow_direct_array[$row_grp_prod['products_product_id']]= $row_custgroup['cust_apply_direct_discount_also'];
										$disg_group_allow_direct_product_array[$row_grp_prod['products_product_id']]= $row_custgroup['cust_apply_direct_product_discount_also'];
										
									}
								}
								/*else
								{
									// done to handle the case of customer mapped to customer group but product not mapped to that group.
									if ($disc_group_prod_array[0]==0 or $disc_group_prod_array[0]>$row_custgroup['cust_disc_grp_discount'])
									{
										$disc_group_prod_array[0] 								= $row_custgroup['cust_disc_grp_discount'];// while exiting the loop this variable hold the lowest of all discounts 	
										$disc_group_array[0]										= $row_custgroup['	cust_disc_grp_id'];
									}	
								}*/		
							}	
						}	
						if(count($disc_group_array)) // case if atleast one customer discount group is applicable
						{
							set_session_var('ecom_cust_group_exists',1);
							set_session_var('ecom_cust_group_prod_array',$disc_group_prod_array);
							set_session_var('ecom_cust_group_array',$disc_group_array);
							set_session_var('ecom_cust_group_allow_direct_array',$disg_group_allow_direct_array);
							set_session_var('ecom_cust_group_allow_direct_product_array',$disg_group_allow_direct_product_array);
						}
						// Check whether customer discount set for current customer in their account directly
						if ($row_check['customer_discount']>0)
						{
							set_session_var('ecom_cust_direct_exists',1);
							set_session_var('ecom_cust_direct_disc',$row_check['customer_discount']);
						}
					}
					// End of section to find the discounts applicable to current customers
					
					// Setting the customer id to the session variable
					/*set_session_var('ecom_login_customer',$row_check['customer_id']);
					$curname = stripslashes($row_check['customer_title']).stripslashes($row_check['customer_fname']).' '.stripslashes($row_check['customer_mname']).' '.stripslashes($row_check['customer_surname']);
					set_session_var('ecom_login_customer_name',$curname);
					$curname = stripslashes($row_check['customer_title']).stripslashes($row_check['customer_fname']).' '.stripslashes($row_check['customer_surname']);
					set_session_var('ecom_login_customer_shortname',$curname);*/
					
					set_session_var('ecom_login_customer',$row_check['customer_id']);
					$curname = stripslashes($row_check['customer_fname']).' '.stripslashes($row_check['customer_mname']).' '.stripslashes($row_check['customer_surname']);
					set_session_var('ecom_login_customer_name',$curname);
					$curname = stripslashes($row_check['customer_fname']).' '.stripslashes($row_check['customer_surname']);
					set_session_var('ecom_login_customer_shortname',$curname);
										
					// updating the customer_last_login_date field in customers table to record the last login date and time
					$update_sql = "UPDATE customers 
									SET 
										customer_last_login_date = now() 
									WHERE 
										customer_id = ".$row_check['customer_id']." 
										AND sites_site_id=$ecom_siteid  
									LIMIT 
										1";
					$db->query($update_sql);
					// Check whether user has to be redirected to the same page itself or to the welcome home page
					if($_REQUEST['redirect_back']==1) // this is a hidden field in the submitted form
					{
					 
						if($_REQUEST['pagetype']=='cart') 
						{
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='PHPSE' value='".$sess."'/><input type='hidden' name='cart_mod' value='show_cart'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";					
						}	
						elseif($_REQUEST['pagetype']=='checkout') 
						{
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/checkout.html' name='frm_subcheckout'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='PHPSE' value='".$sess."'/><input type='hidden' name='cart_mod' value='show_checkout'/></form><script type='text/javascript'>document.frm_subcheckout.submit();</script>";					
						}
						elseif($_REQUEST['pagetype']=='enquire') 
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/enquiry.html' name='frm_subenquire'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='PHPSE' value='".$sess."'/><input type='hidden' name='enq_mod' value='show_enquiry'/></form><script type='text/javascript'>document.frm_subenquire.submit();</script>";					
						elseif($_REQUEST['pagetype']=='payonacc') 
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/payonaccount.html' name='frm_subpayonacc'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='PHPSE' value='".$sess."'/><input type='hidden' name='action_purpose' value='show_middle'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						elseif($_REQUEST['pagetype']=='prodhtml')
						{ 
							echo "<form method='post' action ='".$_REQUEST['pass_url']."' name='frm_subpayonacc'>";
							$varN = 'var_';
							foreach ($_REQUEST as $k=>$v)
							{
								$var_nameLimit = strlen($varN);
								if (substr($k,0,$var_nameLimit) == $varN)
								{
									echo '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
								}
							}
							echo "<div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='action_purpose' value='show_middle'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						}
						elseif($_REQUEST['pagetype']=='priceprom')
						{
							echo "<form method='post' action ='".$_REQUEST['pass_url']."' name='frm_subpayonacc'>";
							echo "<div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='action_purpose' value='show_middle'/><input type='hidden' name='PHPSE' value='".$sess."'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						}	
						elseif($_REQUEST['pass_url']=='')// Redirecting the user to same page
							echo "<script type='text/javascript'>window.location = window.location;</script>";
						else // this is the case when coming from cart page
						{
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='PHPSE' value='".$sess."'/><input type='hidden' name='cart_mod' value='show_cart'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						}
						exit;
					}
					else // Case of redirecting to the welcome page after login
					{
						if ($ecom_siteid==68)//cctv website
						{
							echo "<script type='text/javascript'>window.location = '".$ecom_selfhttp.$ecom_hostname."';</script>";
							exit;
						}
						else
						{
							//echo "<script type='text/javascript'>window.location = 'http://".$ecom_hostname."/login_home.html';</script>";
							echo "<form method='post' action ='".$ecom_selfhttp.$ecom_hostname."/login_home.html' name='frm_resubcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='PHPSE' value='".$sess."'/></form><script type='text/javascript'>document.frm_resubcart.submit();</script>";
							exit;
						}	
					}
				}
				else
				{
					$alert = 'Invalid Login Details';
					echo "<script type='text/javascript'>alert ('".$alert."')</script>";
					if($_REQUEST['redirect_back']==1) // this is a hidden field in the submitted form
					{ 
						if($_REQUEST['pagetype']=='cart') 
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='cart_mod' value='show_cart'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";					
						else if($_REQUEST['pagetype']=='enquire') 
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/enquiry.html' name='frm_subenquire'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='enq_mod' value='show_enquiry'/></form><script type='text/javascript'>document.frm_subenquire.submit();</script>";					
						else if($_REQUEST['pagetype']=='payonacc') 
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/payonaccount.html' name='frm_subpayonacc'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='action_purpose' value='show_middle'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						elseif($_REQUEST['pagetype']=='prodhtml')
						{ 
							$action_url = url_link('custlogin.html',1);;
							echo "<form method='post' action ='".$action_url."' name='frm_subpayonacc'>";
							if($_REQUEST['pagetype']=='prodhtml')
							{
								$varN = 'var_';
								foreach ($_REQUEST as $k=>$v)
								{
									$var_nameLimit = strlen($varN);
									if ($k != 'return_nocheck')
									{
										echo '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
									}
								}
							}
							echo "<div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='return_nocheck' value='1'/><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='action_purpose' value='login'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						}
						elseif($_REQUEST['pagetype']=='priceprom')
						{
							echo "<form method='post' action ='".$_REQUEST['pass_url']."' name='frm_subpayonacc'>";
							echo '<input type="hidden" name="pagetype" value="priceprom"/>';
							echo '<input type="hidden" name="pass_url" value="'.url_link('pricepromiseapproved'.$_REQUEST['prom_id'].'.html',1).'"/>';
							echo '<input type="hidden" name="prom_id" value="'.$_REQUEST['prom_id'].'"/>';
							echo "<div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='action_purpose' value='show_middle'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
						}
						// Redirecting the user to same page
						else if($_REQUEST['pass_url']=='')
							echo "<script type='text/javascript'>window.location = window.location;</script>";
						else // this is the case when coming from cart page
							echo "<form method='post' action ='$ecom_selfhttp"."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='cart_mod' value='show_cart'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						exit;
					}
					else // Case of redirecting to the welcome page after login
					{
						/*echo "<script type='text/javascript'>window.location = 'http://".$ecom_hostname."/login_home.html';</script>";*/	
						if($_REQUEST['from_togle']==1)
						{
						    echo "<script type='text/javascript'>window.location = '".$ecom_selfhttp.$_REQUEST['current_url']."';</script>";
							exit;
						}
						else
						{
							echo "<script type='text/javascript'>window.location = window.location;</script>";
							exit;
						}
					}	
				}
			}
			else
			{
					echo "<script type='text/javascript'>alert ('".$alert."')</script>";
					echo "<script type='text/javascript'>window.location = window.location;</script>";
					exit;
			}
		}	
	}
	// ###################################################################################
	// Survery submit from components
	// ###################################################################################
	if ($_REQUEST['survey_Submit'])
	{
		$proceed_vote 	= true;
		$survey_str 	= $_COOKIE['ecom_surveys'];
		$cur_survey		= $_REQUEST['survey_comp_id'];
		$cur_option		= $_REQUEST['survey_opt'];
		// Building the sql to check whether already voted
		if($survey_str != '')
		{
			$sur_arr = explode(",",$survey_str);
			// Check whether id exists in the cookie value
			if (in_array($cur_survey,$sur_arr))
			{
				$proceed_vote = false;
			}
		}
		else
			$sur_arr = array();
		if($proceed_vote==true)
		{
			// Check whether entry exist for current survey in current session in database
			$sql_check = "SELECT survey_id 
							FROM 
								survey_results 
							WHERE 
								survey_id = $cur_survey 
								AND session_id= '".$sess_id."'";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$proceed_vote = false;
			}
		}
		if ($proceed_vote==false)
		{
			// redirect to the already voted page
			echo "<script type='text/javascript'>window.location = '".$ecom_selfhttp.$ecom_hostname."/vote_failed.html';</script>";
			exit;
		}
		else
		{
			// do the voting by making an entry to the table and also setting the values to the cookie. Once it is done
			$insert_array								= array();
			$insert_array['survey_id']					= $cur_survey;
			$insert_array['session_id']					= $sess_id;
			$insert_array['survey_option_option_id']	= $cur_option;
			$db->insert_from_array($insert_array,'survey_results');
			// Moving the id of current survey to the existing array
			$sur_arr[] =  $cur_survey;
			// Setting the value in array to cookie
			setcookie("ecom_surveys", implode(",", $sur_arr), time() + 60 * 60 * 24 * 365,'/');  // Expires after 1 year
			// redirect to the success page
			echo "<script type='text/javascript'>window.location = '".$ecom_selfhttp.$ecom_hostname."/vote_success".$cur_survey.".html';</script>";
			exit;
		}
	}
		
	/*
	#######################################################################################################
				Remove Compare Products
	#######################################################################################################
	*/
	if($_REQUEST['remove_compareid'])
	{ 
		$compare_pdt_id_arr =  array_unique($_SESSION['compare_products']);
		if(count($compare_pdt_id_arr) && $_REQUEST['remove_compareid'])
		$_SESSION['compare_products'] = array();
		if($_REQUEST['remove_compareid']){
		if($Settings_arr['enable_caching_in_site']){ // remove caching if the product to add is added/removed to/from compare list
		delete_body_cache();
		}
			foreach($compare_pdt_id_arr as $key=>$val){
			if($_REQUEST['remove_compareid']!=$val){
				$_SESSION['compare_products'][] = $val;
				}
			}
		}
	}	
	
	/* End Of Compare Remove section*/
	/*
		################################################################################################
				Compare Products 
		################################################################################################
	*/
	
	if($_REQUEST['submit_Compare_pdts'] &&  isProductCompareEnabled())
	{
		if($_REQUEST['compare_products'])
		{ 
			if($Settings_arr['enable_caching_in_site'])
			{ // remove caching if the product to add is added/removed to/from compare list
				delete_body_cache();
			}
			$pdt_id = stripslashes(trim($_REQUEST['compare_products']));
			if(is_array($_SESSION['compare_products']))
			{
				$tot_pdt_compare = count($_SESSION['compare_products']) +1;
				if($tot_pdt_compare  > $Settings_arr['no_of_products_to_compare'])
				{
					echo '<script language=javascript>';
					echo 'alert("Not more than "+'.$Settings_arr['no_of_products_to_compare'].'+" Can be compared. The Oldest one will be removed from the compare list.");'; 
					echo "window.location=window.location;";
					echo '</script>';
					
				}
				if( (count($_SESSION['compare_products']) >= $Settings_arr['no_of_products_to_compare']) && !in_array($pdt_id,$_SESSION['compare_products']))
				{
					array_shift($_SESSION['compare_products']);
				}
				if(!$_SESSION['compare_products'])
				{  
					$_SESSION['compare_products'] = array();
				}
				if(!in_array($pdt_id,$_SESSION['compare_products']) && is_numeric($pdt_id))
				{  
					$_SESSION['compare_products'][] = $pdt_id;
				}
			}
			else
			{
				$_SESSION['compare_products'] = array();
				$_SESSION['compare_products'][] = $pdt_id;
			}
		}
	}
	elseif($_REQUEST['detcomp_prods']!='')
	{
		if($Settings_arr['enable_caching_in_site'])
		{ // remove caching if the product to add is added/removed to/from compare list
			delete_body_cache();
		}
		$comp_prods 			= explode(",",$_REQUEST['detcomp_prods']);
		$tot_pdt_compare  = count($comp_prods) -1;
		if($tot_pdt_compare > $Settings_arr['no_of_products_to_compare'])
		{
			echo '<script language=javascript>';
			echo 'alert("Sorry!! only "+'.$Settings_arr['no_of_products_to_compare'].'+" products can be compared at a time.");'; 
			echo '</script>';
		}
		else
		{
			$_SESSION['compare_products'] = array();
			$_SESSION['compare_products'] = $comp_prods;
			echo '<script language=javascript>';
			echo "window.location='".$ecom_selfhttp.$ecom_hostname."/compare_products.html';";
			echo '</script>';
		}	
	}	
	/*			
		####################################################################################################
												Cart Handling functions 
		####################################################################################################
			
	*/		
	// Make the decision of which page to be displayed
	switch($_REQUEST['cart_mod'])
	{
		case 'clear_cart': 			// case of clearing the whole cart
			$session_id = session_id();				// Get the session id for the current section
			clear_Cart($session_id); 				// Calling the function to clear the cart
			set_session_var("cart_total", 0);		// Setting the cart total to session
			set_session_var("cart_total_items", 0);	// Setting the cart total to session
		break;
		case 'show_checkout':
			// Save the supporting details
			if($_REQUEST['nrm']==1)
			{
				$cart_msg 	= save_CartsupportDetails('save_commondetails');
				if(!$cart_msg)
				{
					$cart_msg = is_PayType_Valid(); // calling the function to check whether current payment type selected can be used
					if($ecom_siteid==102) // kqf
					{
						switch ($cart_msg)
						{
							case 'PAYON_CREDIT_NOT_SUFFICIENT':
								echo "<form method='post' action='$ecom_selfhttp"."$ecom_hostname/cart.html' id='cart_invalid_form1' name='cart_invalid_form1'><input type='hidden' name='hold_section' value='".$cart_msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form1.submit();</script>";
								exit;
							break;
						};
					}
				}	
					
					
					
			}	
			
			$goto_arr	= get_Checkoutlink(1,$cart_msg);
			if($show_cart_password==1 && $_REQUEST['alert1']!='')//for the password entry to register in the checkout page
			{  
			   $bld_url  = '';	
			   if(strpos($goto_arr['url'], '?')==true)
			   {
			      $bld_url = "&alert1=".$_REQUEST['alert1'];
			   }
			   else
			   { 
				  $bld_url = "?alert1=".$_REQUEST['alert1'];			     
			   }			   
			   $goto_arr['url'] = $goto_arr['url'].$bld_url; 
			}
			if($ecom_siteid==109 || $ecom_siteid==117)
			{
				//passport details validation
				if($_REQUEST['alertp']!='')
				{
				   $bld_url  = '';	
				   if(strpos($goto_arr['url'], '?')==true)
				   {
					  $bld_url = "&alertp=".$_REQUEST['alertp'];
				   }
				   else
				   { 
					  $bld_url = "?alertp=".$_REQUEST['alertp'];			     
				   }			   
				   $goto_arr['url'] = $goto_arr['url'].$bld_url; 
				}
			}
                        $cartData       = cartCalc(); // Calling the function to calculate the details related to cart
                        $cart_err       = 0;
                        // check whether products exists in cart  6 feb 2010
                        if(count($cartData['products'])==0)
                        {
                            $cart_err= 1;
                            echo '
                                <script type="text/javascript">
                                window.location = "'.$ecom_selfhttp.$ecom_hostname.'/cart.html";
                                </script>';
                            exit;
                        }
                       // end 06 feb 2010
			$ret_back 	= $_REQUEST['ret_back'];
			$prm		= $_REQUEST['erm']; // case of paypal express
			$pret		= $_REQUEST['pret'];
			if($prm==1)
			{
				/*if($ecom_siteid==65)
				{
					echo "retback = $ret_back ---- prm = $prm -- $pret = pret";
					exit;
				}*/
				// include the paypal_express file to get the token
				include("includes/paypal_express.php");
				exit; // since the above file will redirect the user to PAYPAL no need to execute what ever is below this
			}
			if($pret==1) // case if coming back from PAYPAL with token.
			{
				//$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				require_once ("includes/paypalfunctions.php");
				$token		= $_REQUEST['token'];
				$payer_id	= $_REQUEST['payer_id'];
			}

                        if($token == '' and $payer_id == '') // case if not paypal express 06 feb 2010 
                        {
                            // Check whether payment type is there
                            if ($cartData["payment"]["type"]=='') // case if payment type does not exists
                            {
                                $msg = '';
                                echo "<form method='post' action='".$ecom_selfhttp.$ecom_hostname."/cart.html' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
                                exit;
                            }
                        }
                        // end 06 feb 2010
			if ($goto_arr['type']=='cart') // check whether user should be redirected to cart page
			{
				if($cart_msg)
                                    $msg = $cart_msg;
				else
                                    $msg = '#incomp';
				echo "<form method='post' action='".$goto_arr['url']."' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='hold_section' value='".$msg."' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
				exit;
			}
			elseif ($goto_arr['type']=='checkout' && $ret_back!=1) // case of going to checkout page
			{
				if($goto_arr['sec'])
                                    $load_msg = 'Secured area loading ....';
				else
                                    $load_msg = 'Loading...';
				echo "<form method='post' action='".$goto_arr['url']."' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='erm' value='".$prm."'/><input type='hidden' name='pret' value='".$pret."'/><input type='hidden' name='token' value='".$token."'/><input type='hidden' name='payer_id' value='".$payer_id."'/><input type='hidden' name='ret_back' value='1' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>".$load_msg."</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
				exit;
			}	
		break;
		case 'show_inter':
			// Save the supporting details
			if($_REQUEST['nrm']==1)
			{
				$cart_msg 	= save_CartsupportDetails('save_commondetails');
				if(!$cart_msg)
				{
					$cart_msg = is_PayType_Valid(); // calling the function to check whether current payment type selected can be used
					
				}	
					
			}		
			$ret_url =''.$ecom_selfhttp.$ecom_hostname.'/cartintermediate.html';
				
				echo "<form method='post' action='".$ret_url."' id='cart_invalid_form' name='cart_invalid_form'><input type='hidden' name='erm' value='".$prm."'/><input type='hidden' name='pret' value='".$pret."'/><input type='hidden' name='token' value='".$token."'/><input type='hidden' name='payer_id' value='".$payer_id."'/><input type='hidden' name='ret_back' value='1' size='1'/><div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>".$load_msg."</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
			exit;
			
		break;
		case 'show_orderplace_preview': // Case of preview page before placing the order. so save the details in the checkout page
			if ($_REQUEST['save_checkoutdetails']==1)
			{
				save_CheckoutDetails();
				delete_bestseller_cache(); // Clearing the cache for best sellers listing for current site
			}	
			if($ecom_siteid == 108)
			{
				//print_r($_REQUEST);
				//$country = 23733;//local
				$country = 20977;//live
				$alert_mess = "Please Select United Kingdom as your delivery country";
				if($protectedUrl)
					$http = url_protected('index.php?req=cart&cart_mod=show_checkout',1);
				else 	
					$http = url_link('checkout.html',1);	
				if($_REQUEST['checkout_billing_same']=='Y')
				{
				   if($_REQUEST['checkout_country']!=$country)
				   {
				      echo "<script type='text/javascript'>alert ('".$alert_mess."')</script>";
				      echo "<script type='text/javascript'>window.location = '".$http."'</script>";
				      exit;
				   }
				}
				else
				{
				   if($_REQUEST['checkoutdelivery_country']!=$country)
				   {
				      echo "<script type='text/javascript'>alert ('".$alert_mess."')</script>";
				      echo "<script type='text/javascript'>window.location = '".$http."'</script>";
				      exit;
				   }
				  
				}
			} 
			// Written on 09 Oct 2012 -- Start 
			$cross_check_cart = cartProducts_StockCheck(); // this function in written in includes/cartCalc.php file. This function do a cross check to see whether items in cart are valid.
			if($cross_check_cart ==false)
			{
				$load_msg = ' Sorry an error occured ...';
				set_session_var("cart_total", 0);		// Setting the cart total to session
				set_session_var("cart_total_items", 0);	// Setting the cart total to session
				echo "<form method='post' action='".url_link('cart.html',1)."' id='cart_invalid_form' name='cart_invalid_form'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>".$load_msg."</div></form><script type='text/javascript'>document.cart_invalid_form.submit();</script>";
				exit;
			}
			// Written on 09 Oct 2012 -- End
			
			require("includes/payment.php");	
			// All details related to order will be saved here and call to respective payment gateway will be initiated
			$return_order_arr = Save_Order();
			// Setting the current order id in session 
			set_session_var('gateway_ord_id',$return_order_arr['order_id']);
		break;
		case 'show_cart': // case of showing the cart
			$cart_msg = save_CartsupportDetails($_REQUEST['fpurpose']);
			if ($_REQUEST['save_checkoutdetails']==1)
			{
				save_CheckoutDetails();
			}	
			if ($cart_msg)
				$_REQUEST['hold_section'] = $cart_msg;
		break;	
	};	
	
	
	// Gift voucher buy page 
	if($_REQUEST['req']=='voucher' and $_REQUEST['action_purpose']=='buy')
	{
		if($_REQUEST['nrm']==1) // Check whether the voucher cart details to be saved
		{
			// #####################################################################################################
			// Calling the function to Save the voucher cart details
			// #####################################################################################################
			Save_Voucher_cart();
			// handling the case of redirection to buy gift voucher section based on selected payment type and payment method
			$seq_req =  is_Pay_Req_Secured();
			if ($seq_req==true)
			{
				$cur_http = url_protected('index.php?req=voucher&action_purpose=buy&voucher_paytype='.$_REQUEST['voucher_paytype'].'&voucher_paymethod='.$_REQUEST['voucher_paymethod'],1);
			}
			else
			{
				$cur_http = url_link('buy_voucher.html?voucher_paytype='.$_REQUEST['voucher_paytype'].'&voucher_paymethod='.$_REQUEST['voucher_paymethod'],1);
			}
			echo "<script type='text/javascript'>window.location = '".$cur_http."'</script>";
			exit;
		}	
	}	
	if($_REQUEST['req']=='voucher' and $_REQUEST['new_purpose']=='spend_cancel') // cancelling voucher or promotion from spend page
	{
		$sess_id = session_id();
		$sql_update = "UPDATE cart_supportdetails 
							SET 
								promotionalcode_id=0,
								voucher_id=0 
							WHERE 
								session_id ='".$sess_id."' 
								AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$db->query($sql_update);
	}
	if($_REQUEST['req']=='payonaccountdetails' and $_REQUEST['action_purpose']=='buy')
	{
		if($_REQUEST['nrm']==1) // Check whether the voucher cart details to be saved
		{
			if ($_REQUEST['pay_amt']) // case of coming to this page to take the payment details
			{
				// #####################################################################################################
				// Calling the function to Save the payonaccount cart details
				// #####################################################################################################
				Save_Payonaccount_cart();	
			}
			// handling the case of redirection to buy gift voucher section based on selected payment type and payment method
			$seq_req =  is_Pay_Req_Secured('payonaccount_paytype','payonaccount_paymethod');
			if ($seq_req==true)
			{
				$cur_http = url_protected('index.php?req=payonaccountdetails&action_purpose=payment&payonaccount_paytype='.$_REQUEST['payonaccount_paytype'].'&payonaccount_paymethod='.$_REQUEST['payonaccount_paymethod'],1);
			}
			else
			{
				$cur_http = url_link('payonaccountpayment.html?payonaccount_paytype='.$_REQUEST['payonaccount_paytype'].'&payonaccount_paymethod='.$_REQUEST['payonaccount_paymethod'],1);
			}
			echo "<script type='text/javascript'>window.location = '".$cur_http."'</script>";
			exit;
		}	
	}	
	
	// ###################################################################################
	// Gift voucher submission section from components
	// ###################################################################################
	if($_REQUEST['compvoucher_Submit'])
	{
		$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');	
		if(!$Settings_arr['imageverification_req_voucher'])
		{
			$fieldRequired		= array($_REQUEST['cart_promotionalcode']);
			$fieldDescription 	= array('Enter Code');
		}
		else 
		{
			$fieldRequired		= array($_REQUEST['cart_promotionalcode'],$_REQUEST['buycompgiftvoucher_Vimg']);
			$fieldDescription 	= array('Enter Code','Enter Image verification code');
		}
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert)
		{ 
			if($Settings_arr['imageverification_req_voucher'])
			{	
				$vImage->loadCodes('buycompgiftvoucher_Vimg');
				if(!$vImage->checkCode('buycompgiftvoucher_Vimg'))	
				{
					$alert = $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_IMAGE_VERIFICATION_FAILED'];				
				}
			}	
			if (!$alert)
			{
				$alert = save_CartsupportDetails('save_commondetails');	// saving the gift voucher or promotional code 
				if (!$alert)
				{
					// case of success
					if ($_REQUEST['from_section'] != 'spend_section')
						$alert = $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_ACCEPTED'];	
					else
						$spend_alert = $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_ACCEPTED'];
				}
				else 
				{
					$spend_alert = $alert = $Captions_arr['GIFT_VOUCHER'][$alert];	
				}
				if ($_REQUEST['from_section'] != 'spend_section')
					echo "<script type='text/javascript'>alert ('".$alert."')</script>";		
			}
			else 
			{
				if ($_REQUEST['from_section'] != 'spend_section')
					echo "<script type='text/javascript'>alert ('".$alert."')</script>";
				else
					$spend_alert = $alert;
			}
		}
	}
	
	/* 	################################################################ 
		Case of showall deal page display requested
		################################################################ */
	if($_REQUEST['req']=='combo_deal' and ($_REQUEST['combo_mod']=='showall' or $_REQUEST['combprod_id']))
	{
		if($_REQUEST['combprod_id'])// case if combo related to a given product is to be picked
		{
			// Get the combo ids related to current product which are active
			$sql_combos = "SELECT a.combo_combo_id as combid
								FROM 
									combo_products a,combo b 
								WHERE 
									a.products_product_id=".$_REQUEST['combprod_id']." 
									AND b.combo_active = 1 
									AND a.combo_combo_id = b.combo_id 
								LIMIT 
									2";
			$ret_combos = $db->query($sql_combos);
			$tot_combos = $db->num_rows($ret_combos);
		}
		else // case if all combo related to current website should be picked
		{
			// Get the combo ids of all combos to which the current product is linked with
			$sql_combos = "SELECT combo_id as combid FROM combo WHERE sites_site_id = $ecom_siteid AND combo_active=1 LIMIT 2";
			$ret_combos = $db->query($sql_combos);
			$tot_combos = $db->num_rows($ret_combos);
		}	
		if($tot_combos==1) // case if only one active combo deal exists then
		{
			$row_combos 			= $db->fetch_array($ret_combos);
			$_REQUEST['combo_id'] 	= $row_combos['combid']; // setting the id of the only one active combo in $_REQUEST array
		}	
	}
	/* ################################################################ */
?>
