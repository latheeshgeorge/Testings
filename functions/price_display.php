<?php
	/*#################################################################
	# Script Name 	: price_display.php
	# Description 	: Page which holds the function to display price for products
	# Coded by 		: Sny
	# Created on	: 27-Dec-2007
	# Modified by	: Sny
	# Modified On	: 16-Jul-2009
	#################################################################*/
	
	//function which is used to display the price
	function show_Price($prod_arr,$price_class_arr,$pagetype ='other_3',$from_combo=false,$speclretn=0)
	{ 
		global $ecom_siteid,$db,$sitesel_curr,$default_crr,$PriceSettings_arr,$default_Currency_arr,$Settings_arr,$Captions_arr,$ecom_tax_total_arr,$current_currency_details;
		$show_only_on_login 	= $Settings_arr['hide_price_login'];
		$tax_before_disc		= $Settings_arr['saletax_before_discount'];
		// Get the customer id from the session
		$cust_id 				= get_session_var("ecom_login_customer");
		$discount_price_only 	= 0;
		$allow_direct_custdisc_in_group = $allow_direct_proddisc_in_group = 'Y';
                $discount_calculated = false;
		// Check whether price should be hidden when not logged in
		if ($show_only_on_login == 1)
		{
			if (!$cust_id)
				return;	
		}
		$applicable_disc =	$applicable_direct_disc = $applicable_group_disc = 0;
		$prod_arr['cust_allow_direct_product_disc'] = 'Y'; // by default product direct discount will be allowed.
		$cust_disc_group_exists = false;
		// Start of section to decide whether to show the discount set in the products table or based on the discount set for logged in customer / customer discount
		if (get_session_var('ecom_cust_group_exists')==1 and $cust_id)
		{
			$group_prod_arr				= get_session_var('ecom_cust_group_prod_array');
			$group_arr				= get_session_var('ecom_cust_group_array');
			$group_allow_direct_arr			= get_session_var('ecom_cust_group_allow_direct_array');// allow direct customer discount for group
			$group_allow_direct_product_arr		= get_session_var('ecom_cust_group_allow_direct_product_array'); // allow direct product discount for group
			
			// Check whether current product is mapped with any of the customer discount groups assigned to current customer
			if(count($group_prod_arr))
			{
				$pid = $prod_arr['product_id'];
				if(array_key_exists($pid,$group_prod_arr))
				{
					$applicable_group_disc 				= $group_prod_arr[$pid];
					$applicable_group = $group_arr[$pid];
					$prod_arr['cust_disc_type'] 			= 'custgroup';
					$prod_arr['cust_disc_grp_id'] 			= $applicable_group;
					$prod_arr['cust_disc_percent'] 			= $applicable_group_disc;
					$prod_arr['cust_allow_direct_disc']		= $group_allow_direct_arr[$pid];
					$prod_arr['cust_allow_direct_product_disc']	= $group_allow_direct_product_arr[$pid];
				
					$cust_disc_type					= 'GRP';
					$cust_disc_group_exists				= true;
				}
				/*else
				{
					// Check whether any discount group exists for current customer with no product set
					if($group_prod_arr[0])
					{
						$applicable_disc 				= $group_prod_arr[0];
						$applicable_group 				= $group_arr[0];
						$prod_arr['cust_disc_type'] 	= 'custgroup';
						$prod_arr['cust_disc_grp_id'] 	= $applicable_group;
						$prod_arr['cust_disc_percent'] 	= $applicable_disc;
						$cust_disc_type					= 'GRP';
					}
				}*/
			}			
		}
		/*if($applicable_disc==0)
		{*/
			// Check whether any direct discount is set for current customer
		if(get_session_var('ecom_cust_direct_exists')== 1  and $cust_id)
		{
			$applicable_direct_disc		= get_session_var('ecom_cust_direct_disc');
			$cust_disc_type			= 'CUST';
			$prod_arr['cust_disc_type'] 	= 'customer';
			//$prod_arr['cust_disc_grp_id'] = 0;
			$prod_arr['cust_disc_percent'] 	= $applicable_direct_disc;
		}
		/*}*/
	// End of section
		if ($cust_disc_group_exists)
		{
			if($prod_arr['cust_allow_direct_disc']=='N')
			{
				$applicable_disc			= $applicable_group_disc;
			}
			else 
			{
				$applicable_disc			= $applicable_direct_disc + $applicable_group_disc;
				if($applicable_disc>100) // in case if total of discount % is > 100 then reset it to 100
					$applicable_disc = 100;
			}
		}
		else
			$applicable_disc			= $applicable_direct_disc;	
		//Get the rate for the current currency
		$curr_rate = $current_currency_details['curr_rate'] + $current_currency_details['curr_margin'];
		$curr_sign = $current_currency_details['curr_sign_char'];
		
		// Assigning the required values from array to variables and applying conversions if required
		
		if($prod_arr['check_comb_price'] == 'YES' and $prod_arr['combination_id']>0) // case coming 
		{
			if($prod_arr['product_variablecomboprice_allowed']=='Y')
			{
				// Get the stock of variable combination form the variable combination table
				$sql_comb = "SELECT web_stock,comb_price  
								FROM 
									product_variable_combination_stock 
								WHERE 
									products_product_id =".$prod_arr['product_id']." 
									AND comb_id = ".$prod_arr['combination_id']." 
								LIMIT 
									1";
				$ret_comb = $db->query($sql_comb);
				if ($db->num_rows($ret_comb))// case if record found in combination stock table
				{
					$row_comb 	= $db->fetch_array($ret_comb);
					$webprice 		= $row_comb['comb_price']*$curr_rate; 
				}
				else // case if record not found in combination table
					$webprice = 0;
			}
			else
				$webprice = $prod_arr['product_webprice']*$curr_rate; 
		}
		else
			$webprice = $prod_arr['product_webprice']*$curr_rate; 
		
		$disc_asval		= $prod_arr['product_discount_enteredasval'];
		$discount		= 0;
		$override_yousave	= false;
		if($prod_arr['cust_allow_direct_product_disc']=='Y')
		{
			if ($disc_asval==1)
			{
				$discount	= $prod_arr['product_discount']*$curr_rate; 
				$override_yousave = true;
			}	
			else if($disc_asval==2)  // For Exact Discount Price
			{
				$discount	= $prod_arr['product_discount']*$curr_rate; 	
				$override_yousave = true;
			}
			else
                        {
				$discount	= $prod_arr['product_discount'];
                                if($applicable_disc>0)      // if normal product discount is there and also customer direct discount or customer group exists, then You save option will be displayed instead of % off.
                                    $override_yousave = true;
                                    
                        }        
		}	
		$apply_tax		= $prod_arr['product_applytax'];
		$tax_val		= 0;
		if ($apply_tax=='Y')
		{
			$tax_arr 		= $ecom_tax_total_arr;
			$tax_val		= $tax_arr['tax_val'];
			if (is_array($tax_arr['tax_name']))
			{
				if (count($tax_arr['tax_name']))
				{
					foreach ($tax_arr['tax_name'] as $k=>$v)
					{
						if ($tax_name)
							$tax_name .= $Captions_arr['PRICE_DISPLAY']['TAX_PLUS'];
						$tax_name .= $v;
					}
				}	
			}	
		}
		$br 	= '0';
		
		switch($pagetype)
		{
			case 'shelfcenter_1':
				if($PriceSettings_arr['price_middleshelf_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'shelfcenter_3':
				if($PriceSettings_arr['price_middleshelf_3_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'compshelf':
				if($PriceSettings_arr['price_compshelf_reqbreak']==1) 
				{
					$br = "1";			
				}	
			break;
			case 'search_1':
				if($PriceSettings_arr['price_searchresult_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'search_3':
				if($PriceSettings_arr['price_searchresult_3_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'prod_detail':
				if($PriceSettings_arr['price_proddetails_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'cat_detail_1':
				if($PriceSettings_arr['price_categorydetails_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'cat_detail_3':
				if($PriceSettings_arr['price_categorydetails_3_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'combo_1':
				if($PriceSettings_arr['price_combodeals_1_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'combo_3':
				if($PriceSettings_arr['price_combodeals_3_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'linkprod_1':
				if($PriceSettings_arr['price_linkedprod_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'linkprod_3':
				if($PriceSettings_arr['price_linkedprod_3_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'bestseller_1':
				if($PriceSettings_arr['price_best_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'bestseller_3':
				if($PriceSettings_arr['price_best_3_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'shopbrand_1':
				if($PriceSettings_arr['price_shopbrand_1_reqbreak']==1)
				{
					$br = "1";			
				}	
			break;
			case 'shopbrand_3':
				if($PriceSettings_arr['price_shopbrand_3_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'featured':
				if($PriceSettings_arr['price_featured_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'other_1':
				if($PriceSettings_arr['price_other_1_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
			case 'other_3':
				if($PriceSettings_arr['price_other_3_reqbreak']==1)
				{
					$br = "1";		
				}	
			break;
		};
		if($webprice)// Do the following only in case if the from price is set
		{
			// Setting the prefix and suffix to respective variables
			if($prod_arr['product_variablesaddonprice_exists']=='Y' or $prod_arr['product_variablecomboprice_allowed']=='Y')
			{
				$normal_prefix 	= ($prod_arr['price_fromprefix'])?$prod_arr['price_fromprefix']:$PriceSettings_arr['price_fromprefix'];
				$normal_suffix 	= ($prod_arr['price_fromsuffix'])?$prod_arr['price_fromsuffix']:$PriceSettings_arr['price_fromsuffix'];
			}
			else
			{
				$normal_prefix 	= ($prod_arr['price_normalprefix'])?$prod_arr['price_normalprefix']:$PriceSettings_arr['price_normalprefix'];
				$normal_suffix 	= ($prod_arr['price_normalsuffix'])?$prod_arr['price_normalsuffix']:$PriceSettings_arr['price_normalsuffix'];
			}
			
			$discount_prefix		= ($prod_arr['price_discountprefix'])?$prod_arr['price_discountprefix']:$PriceSettings_arr['price_discountprefix'];	
			$discount_suffix		= ($prod_arr['price_discountsuffix'])?$prod_arr['price_discountsuffix']:$PriceSettings_arr['price_discountsuffix'];
			
			$offer_prefix			= ($prod_arr['price_specialofferprefix'])?$prod_arr['price_specialofferprefix']:$PriceSettings_arr['price_specialofferprefix'];
			$offer_suffix			= ($prod_arr['price_specialoffersuffix'])?$prod_arr['price_specialoffersuffix']:$PriceSettings_arr['price_specialoffersuffix'];
			
			$yousave_prefix		= ($prod_arr['price_yousaveprefix'])?$prod_arr['price_yousaveprefix']:$PriceSettings_arr['price_yousaveprefix'];
			$yousave_suffix		= ($prod_arr['price_yousavesuffix'])?$prod_arr['price_yousavesuffix']:$PriceSettings_arr['price_yousavesuffix'];
			
			$normal_prefix 		= stripslashes($normal_prefix);
			$normal_suffix 		= stripslashes($normal_suffix);
			$discount_prefix	= stripslashes($discount_prefix);
			$discount_suffix	= stripslashes($discount_suffix);
			$offer_prefix		= stripslashes($offer_prefix);
			$offer_suffix		= stripslashes($offer_suffix);
			$yousave_prefix		= stripslashes($yousave_prefix);
			$yousave_suffix		= stripslashes($yousave_suffix);
			$tax_plus_suffix	= $PriceSettings_arr['price_tax_plus'];
			$tax_inc_suffix		= $PriceSettings_arr['price_tax_inc'];
			$tax_exc_suffix		= $PriceSettings_arr['price_tax_exc'];
			
			$priceclean_arr		= array();
			$priceclearvalonly_arr = array();
			// Decide how to display the price based on the value of price_displaytype
			switch($PriceSettings_arr['price_displaytype'])
			{
				case 'show_price_only': // show only price even if tax exists
					$price_arr['base_price'] 		= $normal_prefix." ".printcurr($webprice,$curr_sign)." ".$normal_suffix;
					$priceclean_arr['base_price'] 	= printcurr($webprice,$curr_sign);
					$priceclearvalonly_arr['base_price'] = $webprice;
					if ($discount>0) // case if direct product discount is applicable
					{
						/*if($cust_disc_type!='') //case if customer logged in and also custdisc or cust group disc exists
						{
							$disc_price 						= $webprice - ($webprice * $discount/100);
							$price_arr['disc_percent'] 			= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 	= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] =  remove_Trailing_zeros($discount);
						}*/
						
						if($disc_asval==1) // If discount is specified as value
						{
							$disc_price = $webprice - $discount;
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price     = $disc_price - $more_disc;
								$discount	+= $more_disc;
							}	 
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 				= 0;
								$priceclean_arr['disc_val'] 		= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
								$price_arr['disc_val'] 		= $discount_prefix." ".printcurr($discount,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] = printcurr($discount,$curr_sign);
								$priceclearvalonly_arr['disc_val'] =  $discount;
							}	
						}	
						else if($disc_asval==2) 
						{ // For Exact Discount Price 
							//echo 'jj'.$disc_price	= $prod_arr['product_discount'];// For Exact Discount Price
							$disc_price	= $prod_arr['product_discount']*$curr_rate; 
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price     = $disc_price - $more_disc;
								$discount	= $webprice - $disc_price;
							}
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 				= 0;
								$priceclean_arr['disc_val'] 		= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
								$price_arr['disc_val'] 				= $discount_prefix." ".printcurr($discount,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 		= printcurr($discount,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 	=  $discount;
							}	
						}	
						else // case if discount is given as percentage
						{
							/*if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$discount += $applicable_disc;
								if($discount>100)
									$discount = 100;
							}    */
							$disc_price 					= $webprice - ($webprice * $discount/100);
                                                        if($applicable_disc>0)
                                                        {
                                                            $disc_price = $disc_price - ($disc_price*$applicable_disc/100);
                                                            //$disc
                                                        }
							$price_arr['disc_percent'] 		        = $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent']                 = remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent']          =  remove_Trailing_zeros($discount);
						}
						
						$price_arr['discounted_price'] 					= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix;
						$priceclean_arr['discounted_price'] 			= printcurr($disc_price,$curr_sign);
						$priceclearvalonly_arr['discounted_price'] 		= $disc_price;
						if ($PriceSettings_arr['price_show_yousave']==1 or $override_yousave==true)// Check whether you save caption is to be displayed
						{
							$price_arr['disc_percent'] 		= '';
							$price_arr['priceclean_arr'] 	= '';
							$price_arr['priceclearvalonly_arr'] =  '';
							if ($disc_price) // check whether disc_price exists
							{
								$yousave_price 						= $webprice - $disc_price;	
								$price_arr['yousave_price'] 		= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
								$priceclean_arr['yousave_price'] 	= printcurr($yousave_price,$curr_sign);
								$priceclearvalonly_arr['yousave_price'] =  $yousave_price;
							}								
						}	
					}
					else // case if direct product discount is not there
					{
							
						if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
						{
							$discount	= ($webprice * $applicable_disc)/100;
							$disc_price = $webprice - $discount;
							$discount	= $applicable_disc; 
							
							$price_arr['disc_percent'] 						= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 				= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 			=  remove_Trailing_zeros($discount);
							
							$price_arr['discounted_price'] 					= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix;
							$priceclean_arr['discounted_price'] 			= printcurr($disc_price,$curr_sign);
							$priceclearvalonly_arr['discounted_price'] 		= $disc_price;
							if ($PriceSettings_arr['price_show_yousave']==1 or $override_yousave==true)// Check whether you save caption is to be displayed
							{
								$price_arr['disc_percent'] 		= '';
								$price_arr['priceclean_arr'] 	= '';
								$price_arr['priceclearvalonly_arr'] =  '';
								if ($disc_price) // check whether disc_price exists
								{
									$yousave_price 							= $webprice - $disc_price;	
									$price_arr['yousave_price'] 			= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
									$priceclean_arr['yousave_price'] 		= printcurr($yousave_price,$curr_sign);
									$priceclearvalonly_arr['yousave_price'] =  $yousave_price;
								}								
							}
						}	
						else // case if no type of discount is applicable
						{
							$price_arr['discounted_price'] 	= '';
							$price_arr['yousave_price']		= '';
							$priceclean_arr['discounted_price'] 	= '';
							$priceclean_arr['yousave_price']		= '';
							$priceclearvalonly_arr['discounted_price'] =  '';
							$priceclearvalonly_arr['yousave_price'] =  '';
						}	
					}	
				break;
				case 'show_price_plus_tax': // show price + Tax
					if ($tax_val>0)
						$tax_str = ' '.$tax_plus_suffix;//$Captions_arr['PRICE_DISPLAY']['TAX_PLUSTAX'];
					$price_arr['base_price'] 		= $normal_prefix." ".printcurr($webprice,$curr_sign)." ".$normal_suffix." ".$tax_str;
					$priceclean_arr['base_price'] 	= printcurr($webprice,$curr_sign)." ".$tax_str;
					$priceclearvalonly_arr['base_price'] 	= $webprice;
					if ($discount>0)
					{
						if($disc_asval==1) // If discount is specified as value
						{
							$disc_price = $webprice - $discount;
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
								$discount	+= $more_disc;
							}	
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 				= 0;
								$priceclean_arr['disc_val'] 		= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
								$price_arr['disc_val'] 					= $discount_prefix." ".printcurr($discount,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 				= printcurr($discount,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 	= $discount;
							}	
						}	
						else if($disc_asval==2)  // For Exact Discount Price 
						{
							$disc_price	= $prod_arr['product_discount']*$curr_rate; 
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
							}
                                                        $discount       = $webprice - $disc_price;
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 	= 0;
								$priceclean_arr['disc_val'] 	= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
								$price_arr['disc_val'] 			= $discount_prefix." ".printcurr($discount,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 	= printcurr($discount,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 	= $discount;
							}	

						}	
					   else // case if discount is given as percentage
						{
							/*if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$discount += $applicable_disc;
								if($discount>100)
									$discount = 100;
							}  */
							$disc_price 						= $webprice - ($webprice * $discount/100);
                                                        if($applicable_disc>0)
                                                        {
                                                            $disc_price = $disc_price - ($disc_price*$applicable_disc/100);
                                                        }
							$price_arr['disc_percent'] 			= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 	= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 	= $discount;
						}
						
						$price_arr['discounted_price'] 				= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix." ".$tax_str;
						$priceclean_arr['discounted_price'] 		= printcurr($disc_price,$curr_sign)." ".$tax_str;
						$priceclearvalonly_arr['discounted_price'] 		= $disc_price;
						if ($PriceSettings_arr['price_show_yousave']==1 or $override_yousave==true)// Check whether you save caption is to be displayed
						{
							$price_arr['disc_percent'] 	= '';
							if ($disc_price) // check whether disc_price exists
							{
								$yousave_price 						= $webprice - $disc_price;	
								$price_arr['yousave_price'] 		= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
								$priceclean_arr['yousave_price'] 	= printcurr($yousave_price,$curr_sign);
								$priceclearvalonly_arr['yousave_price'] 		= $yousave_price;
							}								
						}	
					}
					else // case if direct product discount is not there
					{
						if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
						{
							$discount	= ($webprice * $applicable_disc/100);
							$disc_price = $webprice - $discount;
							$discount	= $applicable_disc;
							$price_arr['disc_percent'] 					= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 			= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 		= $discount;
							$price_arr['discounted_price'] 				= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix." ".$tax_str;
							$priceclean_arr['discounted_price'] 		= printcurr($disc_price,$curr_sign)." ".$tax_str;
							$priceclearvalonly_arr['discounted_price'] 	= $disc_price;
							if ($PriceSettings_arr['price_show_yousave']==1)// Check whether you save caption is to be displayed
							{
								$price_arr['disc_percent'] 	= '';
								if ($disc_price) // check whether disc_price exists
								{
									$yousave_price 						= $webprice - $disc_price;	
									$price_arr['yousave_price'] 		= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
									$priceclean_arr['yousave_price'] 	= printcurr($yousave_price,$curr_sign);
									$priceclearvalonly_arr['yousave_price'] 		= $yousave_price;
								}								
							}
						}
						else
						{
							$price_arr['discounted_price'] 					= '';
							$price_arr['yousave_price']						= '';
							$priceclean_arr['discounted_price'] 			= '';
							$priceclean_arr['yousave_price']				= '';
							$priceclearvalonly_arr['discounted_price'] 		= '';
							$priceclearvalonly_arr['yousave_price'] 		= '';
						}	
					}	
					
				break;
				case 'show_price_inc_tax': // Show price including tax
					if ($tax_val>0 && $Captions_arr['PRICE_DISPLAY']['TAX_INC']!='')
					{
						$tax_str = ' '.$tax_inc_suffix;//$Captions_arr['PRICE_DISPLAY']['TAX_INC'];//.' '.$tax_name;
					}	
				
					$price_withtax								= $webprice + ($webprice * $tax_val/100);
					$price_arr['base_price'] 					= $normal_prefix." ".printcurr($price_withtax,$curr_sign)." ".$normal_suffix." ".$tax_str;
					$priceclean_arr['base_price'] 				= printcurr($price_withtax,$curr_sign)." ".$tax_str;
					$priceclearvalonly_arr['base_price'] 		= $webprice; // considering only the price with out tax;
					$priceclearvalonly_arr['tax_calc_req'] 		= 1; // variable to decide whether tax is to be calculated later
					if ($discount>0)
					{
						if($disc_asval==1) // If discount is specified as value
						{
							$disc_price 			= $webprice - $discount; // calculate the original discount
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
								$discount	+= $more_disc;
							}	
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 	= 0;
							}	
							else
							{
							    $disc  = $price_withtax - $disc_price_with_tax;
								$price_arr['disc_val'] 					= $discount_prefix." ".printcurr($disc,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 				= printcurr($disc,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 	= $disc;
							}
						}	
						else if($disc_asval==2)  // For Exact Discount Price 
						{
							$disc_price	= $prod_arr['product_discount']*$curr_rate; 
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
								//$discount	= $webprice - $disc_price;
							}
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$discount_with_tax 	= $price_withtax - $disc_price_with_tax;
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 	= 0;
								$priceclean_arr['disc_val'] 	= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
								$price_arr['disc_val'] 				= $discount_prefix." ".printcurr($discount_with_tax,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 		= printcurr($discount_with_tax,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 	= $discount_with_tax;
							}	
						}	
						else // case if discount is given as percentage
						{
							/*if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$discount += $applicable_disc;
								if($discount>100)
									$discount = 100;
							}*/
							$disc_price 				= $webprice - ($webprice * $discount/100);// calculate the original discount
                                                        if($applicable_disc>0)
                                                        {
                                                            $disc_price = $disc_price - ($disc_price*$applicable_disc/100);
                                                        }
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 		= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 		= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$price_arr['disc_percent'] 				= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 			= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 	= remove_Trailing_zeros($discount);
						}
						
						$price_arr['discounted_price'] 					= $offer_prefix." ".printcurr($disc_price_with_tax,$curr_sign)." ".$offer_suffix." ".$tax_str;
						$priceclean_arr['discounted_price']				= printcurr($disc_price_with_tax,$curr_sign)." ".$tax_str;
						$priceclearvalonly_arr['discounted_price'] 	=  $disc_price;// considering only the discount with out tax  //$disc_price_with_tax;
						if ($PriceSettings_arr['price_show_yousave']==1 or $override_yousave==true)// Check whether you save caption is to be displayed
						{
							$price_arr['disc_percent'] 	= '';
							if ($disc_price) // check whether disc_price exists
							{
								$yousave_price 								= $webprice - $disc_price;	
								$price_arr['yousave_price'] 				= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
								$priceclean_arr['yousave_price'] 			= printcurr($yousave_price,$curr_sign);
								$priceclearvalonly_arr['yousave_price'] 	= $yousave_price;
							}								
						}	
					}
					else
					{
						if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
						{
							$discount	= ($webprice * $applicable_disc)/100;
							$disc_price = $webprice - $discount;
							$discount	= $applicable_disc;
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 		= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 		= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$price_arr['disc_percent'] 					= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 			= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 		= remove_Trailing_zeros($discount);
							$price_arr['discounted_price'] 				= $offer_prefix." ".printcurr($disc_price_with_tax,$curr_sign)." ".$offer_suffix." ".$tax_str;
							$priceclean_arr['discounted_price']			= printcurr($disc_price_with_tax,$curr_sign)." ".$tax_str;
							$priceclearvalonly_arr['discounted_price'] 	=  $disc_price;// considering only the discount with out tax  //$disc_price_with_tax;
							if ($PriceSettings_arr['price_show_yousave']==1)// Check whether you save caption is to be displayed
							{
								$price_arr['disc_percent'] 	= '';
								if ($disc_price) // check whether disc_price exists
								{
									$yousave_price 								= $webprice - $disc_price;	
									$price_arr['yousave_price'] 				= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
									$priceclean_arr['yousave_price'] 			= printcurr($yousave_price,$curr_sign);
									$priceclearvalonly_arr['yousave_price'] 	= $yousave_price;
								}								
							}
						}
						else
						{
							$price_arr['discounted_price'] 	= '';
							$price_arr['yousave_price']		= '';
							$priceclean_arr['discounted_price'] 	= '';
							$priceclean_arr['yousave_price']		= '';
							$priceclearvalonly_arr['discounted_price'] 	= '';
							$priceclearvalonly_arr['yousave_price'] 	= '';
						}	
					}	
				break;
				case 'show_both': // show price value and tax value
					if ($tax_val>0)
					{
						$tax_str = ' '.$tax_exc_suffix;//$Captions_arr['PRICE_DISPLAY']['TAX_EXC'];//.' '.$tax_name;
					}	
				
					$price_withtax						= $webprice + ($webprice * $tax_val/100);
					$price_arr['base_price'] 			= $normal_prefix." ".printcurr($price_withtax,$curr_sign)." ".$normal_suffix;
					$price_arr['base_price_exc'] 		= $normal_prefix." ".printcurr($webprice,$curr_sign)." ".$normal_suffix." ".$tax_str;
					$priceclean_arr['base_price'] 		= printcurr($price_withtax,$curr_sign);
					$priceclean_arr['base_price_exc'] 	= printcurr($webprice,$curr_sign)." ".$tax_str;
					$priceclearvalonly_arr['base_price'] 		= $webprice; // consider only value with out tax
					$priceclearvalonly_arr['base_price_exc'] 	= $webprice;
					$priceclearvalonly_arr['tax_calc_req'] 		= 1; // variable to decide whether tax is to be calculated later
					if ($discount>0)
					{
						if($disc_asval==1) // If discount is specified as value
						{
							$disc_price 			= $webprice - $discount; // calculate the original discount
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
								$discount	+= $more_disc;
							}
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 	= 0;
								$priceclean_arr['disc_val'] 	= 0;
								$priceclearvalonly_arr['disc_val'] 	= 0;
							}	
							else
							{
							    $disc  = $price_withtax - $disc_price_with_tax;
								$price_arr['disc_val'] 						= $discount_prefix." ".printcurr($disc,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val'] 					= printcurr($disc,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 		= $disc;
							}	
						}	
					   else if($disc_asval==2)  // For Exact Discount Price 
						{
							$disc_price	= $prod_arr['product_discount']*$curr_rate; 
							if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$more_disc	= ($disc_price * $applicable_disc)/100;
								$disc_price = $disc_price - $more_disc;
								//$discount	= $webprice - $disc_price;
							}
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$discount_with_tax 	= $price_withtax - $disc_price_with_tax;
							if ($disc_price<0)
							{
								$price_arr['disc_val'] 		= 0;
								$priceclean_arr['disc_val'] = 0;
								$priceclearvalonly_arr['disc_val'] = 0;
							}	
							else
							{
								$price_arr['disc_val'] 						= $discount_prefix." ".printcurr($discount_with_tax,$curr_sign)." ".$discount_suffix;
								$priceclean_arr['disc_val']			 		= printcurr($discount_with_tax,$curr_sign);
								$priceclearvalonly_arr['disc_val'] 		= $disc_price_with_tax;
							}	
						}	

						else // case if discount is given as percentage
						{
							/*if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
							{
								$discount += $applicable_disc;
								if($discount>100)
									$discount = 100;
							}*/
							$disc_price 				= $webprice - ($webprice * $discount/100);// calculate the original discount
                                                        if($applicable_disc>0)
                                                        {
                                                            $disc_price = $disc_price - ($disc_price*$applicable_disc/100);
                                                        }
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 		= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 		= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$price_arr['disc_percent'] 					= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 				= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 		= remove_Trailing_zeros($discount);
						}
						
						$price_arr['discounted_price'] 				= $offer_prefix." ".printcurr($disc_price_with_tax,$curr_sign)." ".$offer_suffix;
						$price_arr['discounted_price_exc'] 			= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix." ".$tax_str;
						$priceclean_arr['discounted_price'] 		= printcurr($disc_price_with_tax,$curr_sign);
						$priceclean_arr['discounted_price_exc'] 	= printcurr($disc_price,$curr_sign)." ".$tax_str;
						$priceclearvalonly_arr['discounted_price'] 		= $disc_price; // considering only the value with out tax
						$priceclearvalonly_arr['discounted_price_exc'] 		= $disc_price;
						if ($PriceSettings_arr['price_show_yousave']==1 or $override_yousave==true)// Check whether you save caption is to be displayed
						{
							$price_arr['disc_percent'] 		= '';
							$priceclean_arr['disc_percent'] = '';
							$priceclearvalonly_arr['disc_percent'] = '';
							if ($disc_price) // check whether disc_price exists
							{
								$yousave_price 						= $webprice - $disc_price;	
								$price_arr['yousave_price']			= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
								$priceclean_arr['yousave_price'] 	= printcurr($yousave_price,$curr_sign);
								$priceclearvalonly_arr['yousave_price'] 		= $yousave_price;
							}								
						}	
					}
					else
					{
						if($applicable_disc>0)// case if customer group disc and / or customer direct discount exists. so apply that also
						{
							$discount	= ($webprice * $applicable_disc)/100;
							$disc_price = $webprice - $discount;
							$discount	= $applicable_disc;
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 		= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 		= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
							$price_arr['disc_percent'] 					= $discount_prefix." ".remove_Trailing_zeros($discount)."% ".$discount_suffix;
							$priceclean_arr['disc_percent'] 				= remove_Trailing_zeros($discount);
							$priceclearvalonly_arr['disc_percent'] 		= remove_Trailing_zeros($discount);
							$price_arr['discounted_price'] 				= $offer_prefix." ".printcurr($disc_price_with_tax,$curr_sign)." ".$offer_suffix;
							$price_arr['discounted_price_exc'] 			= $offer_prefix." ".printcurr($disc_price,$curr_sign)." ".$offer_suffix." ".$tax_str;
							$priceclean_arr['discounted_price'] 		= printcurr($disc_price_with_tax,$curr_sign);
							$priceclean_arr['discounted_price_exc'] 	= printcurr($disc_price,$curr_sign)." ".$tax_str;
							$priceclearvalonly_arr['discounted_price'] 		= $disc_price; // considering only the value with out tax
							$priceclearvalonly_arr['discounted_price_exc'] 		= $disc_price;
							if ($PriceSettings_arr['price_show_yousave']==1)// Check whether you save caption is to be displayed
							{
								$price_arr['disc_percent'] 		= '';
								$priceclean_arr['disc_percent'] = '';
								$priceclearvalonly_arr['disc_percent'] = '';
								if ($disc_price) // check whether disc_price exists
								{
									$yousave_price 						= $webprice - $disc_price;	
									$price_arr['yousave_price']			= $yousave_prefix." ".printcurr($yousave_price,$curr_sign)." ".$yousave_suffix;
									$priceclean_arr['yousave_price'] 	= printcurr($yousave_price,$curr_sign);
									$priceclearvalonly_arr['yousave_price'] 		= $yousave_price;
								}								
							}
						}
						else
						{
							$price_arr['discounted_price'] 					= '';
							$price_arr['yousave_price']						= '';
							$priceclean_arr['discounted_price'] 			= '';
							$priceclean_arr['yousave_price']				= '';
							$priceclearvalonly_arr['discounted_price'] 		= '';
							$priceclearvalonly_arr['yousave_price'] 		= '';
						}	
					}	
				break;
			};
			//print_r($price_arr);
			$returnval = prepare_price($price_arr,$price_class_arr,$br);
			$retval = $returnval['html'];
		}
		else // case of price is not set
		{
			$noprice = ($prod_arr['price_noprice'])?$prod_arr['price_noprice']:$PriceSettings_arr['price_noprice'];
			if($br==0)// case if no break required is selected
				$retval = "<span class='".$price_class_arr['normal_class']."'>".$noprice."</span>";
			else // case if break required is selected
			{
				if($price_class_arr['ul_class'])// place <ul> only if the class for ul is present
					$retval =  '<ul class="'.$price_class_arr['ul_class'].'">';
				$retval .= "<li class='".$price_class_arr['normal_class']."'>".$noprice."</li>";
				if($price_class_arr['ul_class'])// place </ul> only if the class for ul is present
					$retval .= "</ul>";
			}	
		}	
		if($priceclean_arr['disc_val']!='')
			$priceclean_arr['disc'] = $priceclean_arr['disc_val'];
		if($priceclean_arr['disc_percent']!='')
			$priceclean_arr['disc'] = $priceclean_arr['disc_percent'].'%';
		if($speclretn==1)
		{
			return $returnval['values'];
		}
		elseif($speclretn==2) // case to be used with the variables price (full price show option)
		{
			$returnval['disc_price_only'] = $webprice; 
			return $returnval['disc_price_only'];
		}	
		elseif($speclretn==3) // case if returning each of the prices with prefix and suffix in an associate array
		{
			return $price_arr;
		}
		elseif($speclretn==4) // case of returning prices with out prefix and suffix in an associate array
		{
			return $priceclean_arr;
		}
		elseif($speclretn==5) // case if returning each of the prices with prefix and suffix in an associate array
		{
			$retvals['prince_without_captions'] 	= $priceclean_arr;
			$retvals['price_with_captions']	= $price_arr; 
			return $retvals;
		}
		elseif($speclretn==6) // case of returning prices with out prefix and suffix in an associate array
		{
			return $priceclearvalonly_arr;
		}
		else
		{
			return $retval;
		}
	}
	
	// Function which actually builds the price to be printed
	function prepare_price($price_arr,$class_arr,$br)
	{
		
		global $PriceSettings_arr;
		$retval = '';
		$retvalue['pdf'] 		= array();
		// Check whether price is to be displayed in a single row or in multiple rows
		if ($br==1) // case if the seperation character is <br>
		{  
			// Check whether ul or div is to be used to display the prices
			switch ($class_arr['class_type'])
			{
				case 'div': // case if div
					$outer_prefix 	= '';
					$outer_suffix	= '';
					$main_prefix 	= '<div ';
					$main_suffix 	= '</div>';
				break;
				default: // case if any other than div
					if($class_arr['ul_class'])// place <ul> only if the class for ul is present
						$outer_prefix = '<ul class="'.$class_arr['ul_class'].'">';
					else
						$outer_prefix = '';
					if($class_arr['ul_class'])// place <ul> only if the class for ul is present
						$outer_suffix = '</ul>';
					else
						$outer_suffix = '';	
					$main_prefix 	= '<li ';
					$main_suffix 	= '</li>';
				break;
			};
		}
		else
		{
			$outer_prefix 	= '<div>';
			$outer_suffix	= '</div>';
			$main_prefix	= '<span ';
			$main_suffix	= '</span>';

			// Overriding the classes using the classes for span
			$class_arr['normal_class']		= ($class_arr['normal_span_class']!='')?$class_arr['normal_span_class']:'normal_span_class';
			$class_arr['strike_class']		= ($class_arr['strike_span_class']!='')?$class_arr['strike_span_class']:'strike_span_class';
			$class_arr['yousave_class']		= ($class_arr['yousave_span_class']!='')?$class_arr['yousave_span_class']:'yousave_span_class';
			$class_arr['discount_class']	= ($class_arr['discount_span_class']!='')?$class_arr['discount_span_class']:'discount_span_class';
		}	
		  
		if ($outer_prefix!='')
			$retval .= $outer_prefix;
		if ($price_arr['discounted_price']) //Check if discount exists
		{
			if ($PriceSettings_arr['strike_baseprice']==1) // check whether the base price is to be striked out
			{
				$base_class = $class_arr['strike_class'];
			}	
			else	
			{
				$base_class = $class_arr['normal_class'];
			}	
			$retval 							.=$main_prefix.' class="'.$base_class.'">'.$price_arr['base_price'].$main_suffix;
			$retvalue['pdf'][] 					= $price_arr['base_price'];
			$retvalue['normal']['base_price'] 	= $price_arr['base_price'];
			
			if ($price_arr['base_price_exc'])// show only in exists
			{
				$retval 						.=$main_prefix.' class="'.$base_class.'">'.$price_arr['base_price_exc'].$main_suffix;
				$retvalue['pdf'][] 				= $price_arr['base_price_exc'];
			}	
			$retval 							.=$main_prefix.' class="'.$class_arr['normal_class'].'">'.$price_arr['discounted_price'].$main_suffix;
			$discounted_price_only  			= $price_arr['discounted_price'];
			$retvalue['pdf'][] 					= $price_arr['discounted_price'];
			
			if ($price_arr['discounted_price_exc'])// show only in exists
			{
				$retval 						.=$main_prefix.' class="'.$class_arr['normal_class'].'">'.$price_arr['discounted_price_exc'].$main_suffix;
				$retvalue['pdf'][] 				= $price_arr['discounted_price_exc'];
			}	
			if($price_arr['yousave_price'])// show only in exists
			{
				$retval 						.=$main_prefix.' class="'.$class_arr['yousave_class'].'">'.$price_arr['yousave_price'].$main_suffix;
				$retvalue['pdf'][] 				= $price_arr['yousave_price'];
			}
			else
			{
				if($PriceSettings_arr['price_display_discount_with_price']==1)
				{
					if($price_arr['disc_val']) // if discount is entered as val
					{
						$retval 				.=$main_prefix.' class="'.$class_arr['discount_class'].'">'.$price_arr['disc_val'].$main_suffix;
						$retvalue['pdf'][] 		= $price_arr['disc_val'];
					}	
					elseif($price_arr['disc_percent']) // if discount is entered as percentage
					{
						$retval 				.=$main_prefix.' class="'.$class_arr['discount_class'].'">'.$price_arr['disc_percent'].$main_suffix;
						$retvalue['pdf'][] 		= $price_arr['disc_val'];
					}
				}		
			}
		}
		else
		{ 
			$retval 			.=$main_prefix.' class="'.$class_arr['normal_class'].'">'.$price_arr['base_price'].$main_suffix;
			$retvalue['pdf'][] 	= $price_arr['base_price'];
		}	
		if ($outer_suffix!='')
			$retval .= $outer_suffix;
		
		$returnvalue['html'] = $retval;
		$returnvalue['values'] = $retvalue['pdf'];
		return $returnvalue;
	} 
	// ** Function to display the price with currency symbol
	function print_price($price,$always_return=false,$exclude_currency=false)
	{
		global $ecom_siteid,$db,$sitesel_curr,$default_crr,$default_Currency_arr,$current_currency_details;
		//Get the rate for the current currency
		$curr_rate = $current_currency_details['curr_rate'] + $current_currency_details['curr_margin'];
		$curr_sign	= $current_currency_details['curr_sign_char'];
		if ($always_return==true) // if always_return is set to true then return the price even if it is 0
		{	
			if($exclude_currency == false)
				return $curr_sign.sprintf('%0.2f',($price * $curr_rate));
			else
				return sprintf('%0.2f',($price * $curr_rate));
		}	
		else // return only if the price is >0
		{
			if ($price>0)
			{
				if($exclude_currency == false)
					return $curr_sign.sprintf('%0.2f',($price * $curr_rate));
				else
					return sprintf('%0.2f',($price * $curr_rate));
			}	
		}	
	}
	
	// ** Function to display the additional price for product variables
	function Show_Variable_Additional_Price($prod_arr,$price,$vardisp_type)
	{
		global $ecom_siteid,$db,$sitesel_curr,$default_crr,$PriceSettings_arr,$default_Currency_arr,$Settings_arr,$ecom_tax_total_arr;
		if ($PriceSettings_arr['price_variableprice_display']==1 and $prod_arr['product_variablecomboprice_allowed']=='N') // ** Check whether variable price is to be displayed
		{
			if($price>=0) // case if price is > 0
			{
				switch($vardisp_type)// decide which prefix and suffix should be used with the variable price display
				{
					case 'ADD':
						$get_discounted_price = 0;
						$prefix 						= $PriceSettings_arr['price_variablepriceadd_prefix'];
						$suffix 						= $PriceSettings_arr['price_variablepriceadd_suffix'];
						// Check whether price including tax is to be displayed
						if($prod_arr['product_applytax'] == 'Y')
						{
							if($PriceSettings_arr['price_displaytype'] == 'show_price_inc_tax')
							{
								$pric_arr['tax_calc_req']=1;
							}
						}
					break;
					case 'FULL':
						//$get_discounted_price 			= show_Price($prod_arr,array(),'other_3',false,2);
						$pric_arr 					= show_Price($prod_arr,array(),'other_3',false,6);
						if($pric_arr['discounted_price'])
							$get_discounted_price =  $pric_arr['discounted_price'];
						else
							$get_discounted_price = $pric_arr['base_price'];
						if($get_discounted_price=='')
							$get_discounted_price = 0;
						$prefix 						= $PriceSettings_arr['price_variablepricefull_prefix'];
						$suffix 						= $PriceSettings_arr['price_variablepricefull_suffix'];
					break;
				};
			}
			else if($price<0)  // case if price is < 0 .. here what ever the vardisp_type .. the caption set for varprice less should be displayed
			{
				switch($vardisp_type)// decide which prefix and suffix should be used with the variable price display
				{
					case 'ADD':
						$get_discounted_price = 0;
						$prefix 						= $PriceSettings_arr['price_variablepriceless_prefix'];
						$suffix 						= $PriceSettings_arr['price_variablepriceless_suffix'];
						// Check whether price including tax is to be displayed
						if($prod_arr['product_applytax'] == 'Y')
						{
							if($PriceSettings_arr['price_displaytype'] == 'show_price_inc_tax')
							{
								$pric_arr['tax_calc_req']=1;
							}
						}
					break;
					case 'FULL':
						//$get_discounted_price 			= show_Price($prod_arr,array(),'other_3',false,2);
						$pric_arr 					= show_Price($prod_arr,array(),'other_3',false,6);
						if($pric_arr['discounted_price'])
							$get_discounted_price =  $pric_arr['discounted_price'];
						else
							$get_discounted_price = $pric_arr['base_price'];
						if($get_discounted_price=='')
							$get_discounted_price = 0;
						$prefix 						= $PriceSettings_arr['price_variablepricefull_prefix'];
						$suffix 						= $PriceSettings_arr['price_variablepricefull_suffix'];
					break;
				};
			}
				$price = $price + $get_discounted_price;
				if($pric_arr['tax_calc_req']==1) // Check whether tax is to be calculated here .. this is to handle the situations of price display including tax and also show both prices
				{
					$tax_arr 		= $ecom_tax_total_arr;
					$tax_val			= $tax_arr['tax_val'];
					$price 			= $price + ($price * $tax_arr['tax_val']/100);
				}
				$vprice = print_price(abs($price)); // ** Get the price with required currency
			if ($vprice)
				return ' '.$prefix.' '.$vprice.' '.$suffix;
		}		
	}
	
	
function convertPrice_to_selectedCurrrency($price)
{
	global $ecom_siteid,$default_Currency_arr,$sitesel_curr,$db,$current_currency_details;
	// selected currency
	$def_curr 	= $default_Currency_arr['currency_id'];
	if(!$sitesel_curr) $sitesel_curr = $def_curr;
	if($def_curr!=$sitesel_curr)
	{
		$rate = $current_currency_details['curr_rate'] + $current_currency_details['curr_margin'];
	}
	else
		$rate = 1;//+$default_Currency_arr['curr_margin']);;
	
	return ($price*$rate);	
}
// ** Function to display the price with currency symbol
function print_price_selected_currency($price,$rate=1,$sign,$always_return=false)
{
	if ($always_return==true) // if always_return is set to true then return the price even if it is 0
	return $sign.sprintf('%0.2f',($price * $rate));
	else // return only if the price is >0
	{
		if ($price>0)
		return $sign.sprintf('%0.2f',($price * $rate));
	}
}

// ** Function to convert price to default currency
function convert_price_default_currency($price,$rate=1)
{
	return sprintf('%0.2f',($price/$rate));
}
function remove_Trailing_zeros($val)
{
	// explode the value with respect to .
	$exp_arr = explode('.',$val);
	if(count($exp_arr)>1)
	{
		if ($exp_arr[1]=='00')// if the second element is array is 00 then dont return that otherwise return the decimal part as well
			return $exp_arr[0];
		else 	
			return $val;
	}
	else 	
		return $val;
}
function prepare_price_Ajax($price_arr,$class_arr,$br)
{
	
	global $PriceSettings_arr;
	
	if($price_arr['products'][0]['product_variablesaddonprice_exists']=='Y' or $price_arr['products'][0]['product_variablecomboprice_allowed']=='Y')
	{
		$normal_prefix 	= ($price_arr['products'][0]['price_fromprefix'])?$price_arr['products'][0]['price_fromprefix']:$PriceSettings_arr['price_fromprefix'];
		$normal_suffix 	= ($price_arr['products'][0]['price_fromsuffix'])?$price_arr['products'][0]['price_fromsuffix']:$PriceSettings_arr['price_fromsuffix'];
	}
	else
	{
		$normal_prefix 	= ($price_arr['products'][0]['price_normalprefix'])?$price_arr['products'][0]['price_normalprefix']:$PriceSettings_arr['price_normalprefix'];
		$normal_suffix 	= ($price_arr['products'][0]['price_normalsuffix'])?$price_arr['products'][0]['price_normalsuffix']:$PriceSettings_arr['price_normalsuffix'];
	}
	$yousave_prefix		= ($price_arr['products'][0]['price_yousaveprefix'])?$price_arr['products'][0]['price_yousaveprefix']:$PriceSettings_arr['price_yousaveprefix'];
	$yousave_suffix		= ($price_arr['products'][0]['price_yousavesuffix'])?$price_arr['products'][0]['price_yousavesuffix']:$PriceSettings_arr['price_yousavesuffix'];
	
	$noprice 				= ($price_arr['products'][0]['price_noprice'])?$price_arr['products'][0]['price_noprice']:$PriceSettings_arr['price_noprice'];

	$normal_prefix 	= stripslashes($normal_prefix);
	$normal_suffix 	= stripslashes($normal_suffix);
	$yousave_prefix = stripslashes($yousave_prefix);
	$noprice 		= stripslashes($noprice);
	$retval = '';
	// Check whether price is to be displayed in a single row or in multiple rows
	if ($br==1) // case if the seperation character is <br>
	{  
		// Check whether ul or div is to be used to display the prices
		switch ($class_arr['class_type'])
		{
			case 'div': // case if div
				$outer_prefix 	= '';
				$outer_suffix	= '';
				$main_prefix 	= '<div ';
				$main_suffix 	= '</div>';
			break;
			default: // case if any other than div
				if($class_arr['ul_class'])// place <ul> only if the class for ul is present
					$outer_prefix = '<ul class="'.$class_arr['ul_class'].'">';
				else
					$outer_prefix = '';
				if($class_arr['ul_class'])// place <ul> only if the class for ul is present
					$outer_suffix = '</ul>';
				else
					$outer_suffix = '';	
				$main_prefix 	= '<li ';
				$main_suffix 	= '</li>';
			break;
		};
	}
	else
	{
		$outer_prefix 	= '<div>';
		$outer_suffix	= '</div>';
		$main_prefix	= '<span ';
		$main_suffix	= '</span>';
		// Overriding the classes using the classes for span
			
		$class_arr['normal_class']		= ($class_arr['normal_span_class']!='')?$class_arr['normal_span_class']:'normal_span_class';
		$class_arr['strike_class']		= ($class_arr['strike_span_class']!='')?$class_arr['strike_span_class']:'strike_span_class';
		$class_arr['yousave_class']		= ($class_arr['yousave_span_class']!='')?$class_arr['yousave_span_class']:'yousave_span_class';
		$class_arr['discount_class']	= ($class_arr['discount_span_class']!='')?$class_arr['discount_span_class']:'discount_span_class';
	}	
	
	
	if($price_arr['products'][0]['discounted_price']>0)
	{
		if ($outer_prefix!='')
			$retval 	.= $outer_prefix;
			
		$base_class 	= $class_arr['normal_class'];
		$retval 			.=$main_prefix.' class="'.$class_arr['normal_class'].'">'. $normal_prefix.' '.print_price($price_arr['products'][0]['discounted_price']*$price_arr['totals']['pass_qty']).' '.$normal_suffix.$main_suffix;
		if($price_arr['products'][0]['savings']['bulk'])// show only in exists
		{
			$show_prices = $price_arr['products'][0]['savings']['bulk'];
			if($show_prices<0)
				$show_prices = $show_prices * -1;
			$retval 		.=$main_prefix.' class="'.$class_arr['yousave_class'].'">'.$yousave_prefix.' '.print_price($show_prices).' '.$yousave_suffix.$main_suffix;
		}	
		elseif($price_arr['products'][0]['savings']['combo'])// show only in exists
		{
			$show_prices = $price_arr['products'][0]['savings']['combo'];
			if($show_prices<0)
				$show_prices = $show_prices * -1;
			$retval 		.=$main_prefix.' class="'.$class_arr['yousave_class'].'">'.$yousave_prefix.' '.print_price($show_prices).' '.$yousave_suffix.$main_suffix;
		}			
		elseif($price_arr['products'][0]['savings']['product'])// show only in exists
		{
			$show_prices = $price_arr['products'][0]['savings']['product'];
			if($show_prices<0)
				$show_prices = $show_prices * -1;
			$retval 		.=$main_prefix.' class="'.$class_arr['yousave_class'].'">'.$yousave_prefix.' '.print_price($show_prices).' '.$yousave_suffix.$main_suffix;
		}
		if ($outer_suffix!='')
			$retval .= $outer_suffix;
	}	
	elseif($price_arr['products'][0]['discounted_price']==0)
	{
		if ($outer_prefix!='')
			$retval 	.= $outer_prefix;
		$retval 		.=$main_prefix.' class="'.$class_arr['yousave_class'].'">'.$noprice.$main_suffix;
	if ($outer_suffix!='')
			$retval .= $outer_suffix;	
	}
	return $retval;
} 

?>