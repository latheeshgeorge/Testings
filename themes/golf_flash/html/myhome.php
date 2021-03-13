<?php
	/*#######################################################################
	# Script Name 	: myhome.php
	# Description 	: Page which call the display logic for page after login
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: Sny
	# Modified On	: 07-Sep-2009	
	########################################################################*/
	
	// ############################################################################################################
	// Do the following only if the position of mod_customerlogin is set 
	// ############################################################################################################
	$module_name = 'mod_customerlogin';
	if(in_array($module_name,$inlineSiteComponents))
	{
		$customer_id 	= get_session_var("ecom_login_customer");
		
		//print_r($Settings_arr);
		if($customer_id)
		{
			if ($myhomeHtml=="")
			{ 
				require("themes/$ecom_themename/html/myhomeHtml.php");
				$myhomeHtml= new myhome_Html(); // Creating an object for the myprofile_Html class
			}
			if(get_session_var("ecom_login_customer"))
			{ 
				global $Captions_arr;
				$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
				//$themename_arr = array('classic_new','nature','cctv','euro','classic','fashions','garraways','golf','metalic','golf_new');
				//if(in_array($ecom_themename,$themename_arr))
				if ($ecom_themename!='classic') 
				{					
					if ($ecom_themename=='golf_flash') 
					{
						$used_val_arr			= array
													(
														'cats_arr'=>array(),
														'prod_arr'=>array(),	
													);	
						// call the function to show the welcome message and also the discount related things
						$used_val_arr   = $myhomeHtml->Display_WelcomeMessage($mesgHeader,$Message); 
						$used_val_arr	= $myhomeHtml->Show_Favourite_Products($used_val_arr);
						$used_val_arr	= $myhomeHtml->Show_Favourite_Categories($used_val_arr);
						$used_val_arr	= $myhomeHtml->Show_Recently_Purchased_Products_With_Categories($used_val_arr);
						//Check whether output occured in any of the above section. If not, then get 3 categories which have highest hits and show products in them
						if(count($used_val_arr['cats_arr'])==0 and count($used_val_arr['prod_arr'])==0)
						{
							$used_val_arr = $myhomeHtml->Show_Highest_Hit_Categories($used_val_arr);
						}
						
					}
					else
					{
						//else
						{
						$used_val_arr			= array
													(
														'cats_arr'=>array(),
														'prod_arr'=>array(),	
													);	
						// call the function to show the welcome message and also the discount related things
						$used_val_arr   = $myhomeHtml->Display_WelcomeMessage($mesgHeader,$Message); 
						$used_val_arr	= $myhomeHtml->Show_Recently_Purchased_Products_With_Categories($used_val_arr);
						//Check whether output occured in any of the above section. If not, then get 3 categories which have highest hits and show products in them
						if(count($used_val_arr['cats_arr'])==0 and count($used_val_arr['prod_arr'])==0)
						{
							$used_val_arr = $myhomeHtml->Show_Highest_Hit_Categories($used_val_arr);
						}
						$used_val_arr	= $myhomeHtml->Show_Favourite_Categories($used_val_arr);
						$used_val_arr	= $myhomeHtml->Show_Favourite_Products($used_val_arr);
						}
					}
				}
				else
				{ 
					$ids = $myhomeHtml->Display_WelcomeMessage($mesgHeader,$Message); // call the function to show the welcome message
					
					$sql_tot_fav_categories = "SELECT count(id) 
										FROM 
											product_categories pc,customer_fav_categories cfc 
										WHERE
											 pc.category_id = cfc.categories_categories_id AND pc.category_hide =0 AND
									cfc.sites_site_id = $ecom_siteid  and cfc.customer_customer_id= $customer_id";
					$ret_totfav_categories = $db->query($sql_tot_fav_categories);
					list($tot_cntcateg) 	= $db->fetch_array($ret_totfav_categories); 
					//$categperpage	=	3;
					$chk_cnt = 0;
					$pg_variablecateg	= 'categ_pg';
					//if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
					//			{
									//$start_varcateg 		= prepare_paging($_REQUEST[$pg_variablecateg],$categperpage,$tot_cntcateg);
									//$Limitcateg				= " LIMIT ".$start_varcateg['startrec'].", ".$categperpage;
						//		}	
						//		else
									//$Limitcateg = '';
				$prod_limitcat			= $Settings_arr['product_limit_homepage_favcat_recent'];
				if($prod_limitcat<=0)
				$prod_limitcat = 3;
				$sql_fav_categories = "SELECT category_id,category_name,category_showimageofproduct,category_paid_for_longdescription,
											category_paid_description,category_shortdescription,
												  categories_categories_id,id 
										FROM 
											product_categories pc,customer_fav_categories cfc 
										WHERE
											 pc.category_id = cfc.categories_categories_id AND pc.category_hide =0 AND
									cfc.sites_site_id = $ecom_siteid  AND cfc.customer_customer_id= $customer_id
									";
					//$ret_fav_categories = $db->query($sql_fav_categories);
					$ret_favcat = $db->query($sql_fav_categories);
					if ($db->num_rows($ret_favcat))	{				// ** Check whether the category is valid for display
						//$categoryHtml->Show_CategoryDetails($ret_cat); 	// ** Call the function to show the category details
						$chk_cnt = 1;
						$ids_in = $myhomeHtml->Show_MyhomeFavoriteCategories($ret_favcat,$tot_cntcateg,$start_varcateg,$pg_variablecateg); // call the function to show the my profile form 
					} else {
						//Looking for 3 categories from the customer purchase history when there are no fav categories selected.
						$sql_fav_categories = "SELECT distinct category_id,category_name,category_showimageofproduct,category_paid_for_longdescription,
											category_paid_description,category_shortdescription
							FROM 
								orders a,order_details b,products p,product_categories pc 
							WHERE 
								a.order_id=b.orders_order_id 
								AND a.customers_customer_id = $customer_id
								AND a.sites_site_id=$ecom_siteid 
								AND b.products_product_id=p.product_id
								AND p.product_default_category_id=pc.category_id 
								AND pc.category_hide =0 
							GROUP BY 
								b.products_product_id  
							ORDER BY 
								a.order_date DESC
							Limit $prod_limitcat";
							$ret_favcat = $db->query($sql_fav_categories);
							if ($db->num_rows($ret_favcat))	{				// ** Check whether the category is valid for display
								$chk_cnt = 1;
								$ids_in = $myhomeHtml->Show_MyhomeFavoriteCategories($ret_favcat,$tot_cntcateg,$start_varcateg,$pg_variablecateg); 
							}
					}
					
					///////**********************FOR FAVORITE PRODUCTS*****************************///////////
					if(!$ids_in)
					{
						$ids_in = -1;
					}
					
					$sql_tot_fav_products = "SELECT count(id) 
										FROM 
											products p,customer_fav_products cfp 
										WHERE
											 p.product_id = cfp.products_product_id AND p.product_hide='N' AND p.product_id NOT IN ($ids_in) AND
									cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id= $customer_id";
					$ret_totfav_products 	= $db->query($sql_tot_fav_products);
					list($tot_cntprod) 		= $db->fetch_array($ret_totfav_products); 
					$prodperpage			= ($Settings_arr['product_maxcntperpage_favorite']>0)?$Settings_arr['product_maxcntperpage_favorite']:3;//Hardcoded at the moment. Need to change to a variable that can be set in the console.
					$favsort_by				= $Settings_arr['product_orderby_favorite'];
					$prodsort_order			= $Settings_arr['product_orderfield_favorite'];
					switch ($prodsort_order)
					{
						case 'product_name': // case of order by product name
						$prodsort_order		= 'product_name';
						break;
						case 'price': // case of order by price
						$prodsort_order		= 'product_webprice';
						break;
						case 'product_id': // case of order by price
						$prodsort_order		= 'product_id';
						break;	
					};
					
					$pg_variableprod		= 'prod_pg';
					if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
					{
						$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
						$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
					}	
					else
						$Limitprod = '';
								
									
					$sql_fav_products = "SELECT id,product_name,product_id,products_product_id,product_name,product_shortdesc
												,product_variablestock_allowed,product_show_cartlink,
											product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
											product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
											product_total_preorder_allowed, product_applytax,p.product_bonuspoints,
											p.product_stock_notification_required,p.product_alloworder_notinstock ,p.product_variables_exists,p.product_variablesaddonprice_exists     
										FROM 
											products p,customer_fav_products cfp
										WHERE
											 p.product_id = cfp.products_product_id AND p.product_hide='N' AND p.product_id NOT IN ($ids_in) AND
									cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id = $customer_id
										ORDER BY $prodsort_order $favsort_by $Limitprod	";
						$ret_fav_products = $db->query($sql_fav_products);
					if ($db->num_rows($ret_fav_products))	{				// ** Check whether the fav products exists
						$chk_cnt = 1;
						$myhomeHtml->Show_MyhomeFavoriteProducts($ret_fav_products,$tot_cntprod,$start_varprod,$pg_variableprod); 
					}
					
					//3 Recently purchased products
					//$prod_limitcat			= $Settings_arr['product_limit_homepage_favcat_recent'];
					//$ids_in =-1;
					$sql_purchase = "SELECT p.product_id,p.product_name,p.product_default_category_id,p.product_variablestock_allowed,p.product_show_cartlink,
											p.product_preorder_allowed,p.product_show_enquirelink,p.product_webstock,p.product_webprice,
											p.product_discount,p.product_discount_enteredasval,p.product_bulkdiscount_allowed,
											p.product_total_preorder_allowed,p.product_applytax,p.product_shortdesc,p.product_bonuspoints,
											p.product_stock_notification_required,p.product_alloworder_notinstock,p.product_variables_exists,p.product_variablesaddonprice_exists     
							FROM 
								orders a,order_details b,products p 
							WHERE 
								a.order_id=b.orders_order_id 
								AND a.customers_customer_id = $customer_id
								AND a.sites_site_id=$ecom_siteid 
								AND b.products_product_id=p.product_id 
								AND p.product_id NOT IN ($ids_in)
								AND p.product_hide ='N' 
							GROUP BY 
								b.products_product_id  
							ORDER BY 
								a.order_date DESC
							LIMIT 
								$prod_limitcat";
					if ($sql_purchase!='')
					{
						$ret_purchase = $db->query($sql_purchase);
						if ($db->num_rows($ret_purchase))// Check whether best sellers exists
						{ 
						  $chk_cnt = 1;
						  $myhomeHtml->Show_MyhomePurcahaseProducts($ret_purchase); // call the function to show the my profile form 
		
						}		
					}
					if($chk_cnt==0)
					{
					if(in_array('mod_shelf',$inlineSiteComponents)){
						$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
										FROM 
											display_settings a,features b 
										WHERE 
											a.sites_site_id=$ecom_siteid 
											AND a.display_position='middle' 
											AND b.feature_allowedinmiddlesection = 1  
											AND layout_code='".$default_layout."' 
											AND a.features_feature_id=b.feature_id 
											AND b.feature_modulename='mod_shelf' 
										ORDER BY 
												display_order 
																ASC";
						$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
						if ($db->num_rows($ret_inline))
						{
							while ($row_inline = $db->fetch_array($ret_inline))
							{
								//$modname 			= $row_inline['feature_modulename'];
								$body_dispcompid	= $row_inline['display_component_id'];
								$body_dispid		= $row_inline['display_id'];
								$body_title			= $row_inline['display_title'];
								include ("includes/base_files/shelf.php");
							}
						}
		
					}
				}
			}	
		}
	}
	else
	{
		if ($myhomeHtml=="")
		{ 
			require("themes/$ecom_themename/html/myhomeHtml.php");
			$myhomeHtml= new myhome_Html(); // Creating an object for the myprofile_Html class
		}
		
		// clearing all cookies
	/*if (isset($_SERVER['HTTP_COOKIE'])) {
		$cook_avoidarr = array('ecom_surveys','imgdir_curdir');
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		foreach($cookies as $cookie) {
			$parts = explode('=', $cookie);
			$name = trim($parts[0]);
			//echo "<br>".$name;
			if(substr($name,0,12) != 'prod_cookie_')
			{
				if (!in_array($name,$cook_avoidarr))
				{
					setcookie($name, '', time()-3600);
					setcookie($name, '', time()-3600, '/');
					
					clear_session_var($name);
					
					//echo "-- 1 --".$name;
				}	
			}	
		}
	}*/
		
		$Captions_arr['CUST_REG'] 	= getCaptions('CUST_REG');
		$header 					= $Captions_arr['CUST_REG']['CUSTOMER_NOT_AUTHORISED_LOGIN_HEADER'];
		$message					= $Captions_arr['CUST_REG']['CUSTOMER_NOT_AUTHORISED_LOGIN_MESSAGE'];
		$myhomeHtml->Display_Message($header,$message); // call the function to show the error message to login
	}
}

?>
