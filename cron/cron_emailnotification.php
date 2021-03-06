<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	require_once("../config_db.php");
	
	$curdate = date("Y-m-d");

	// to Get Sites In Bshop4
	$sitesql = "SELECT site_id, site_domain FROM sites ";//WHERE site_status='Live'
	$siteres = $db->query($sitesql);
	while($siterow = $db->fetch_array($siteres)) 
	{
				// Extracting Newsletters To send
				$sql = "SELECT news_id, newsletter_template_id, newsletter_title, newsletter_content, 
							   preview_title, preview_contents, set_senttype, week_day, month_date ,
							   number_newproducts, category_newproducts, category_discproducts, 
					 		   number_discproducts, product_select_type, discount_from, discount_to
							FROM customer_email_notification 
								 WHERE email_status='1' AND sites_site_id=".$siterow['site_id'];
				$res = $db->query($sql);
				while($row = $db->fetch_array($res)) 
				{
					$process = 0;
					$nonewpdts = 0;
					$nodiscpdts = 0;
					
					// Here We Replacing tag like NEWProductrts with the new products
					// Extracting Template Details and Notification Details FROm Database
							
					if($row['newsletter_template_id'] > 0) { // To get Template Product layout FROM Newsletter Template Table
						$tempsql = "SELECT product_layout 
											FROM newsletter_template 
												 WHERE newstemplate_id ='".$row['newsletter_template_id']."'";
						$tempres = $db->query($tempsql);
						$temprow = $db->fetch_array($tempres);						 
						$productlayout = $temprow['product_layout'];
					} else {
						$tempsql = "SELECT template_product_layout 
											FROM sites 
												 WHERE site_id ='".$siterow['site_id']."'";
						$tempres = $db->query($tempsql);
						$temprow = $db->fetch_array($tempres);						 
						$productlayout = $temprow['template_product_layout'];						 
					}
						

						$newprods 	   = $row['number_newproducts'];
						//if($newprods<1) $newprods = 10;
						$discprods	   = $row['number_discproducts'];
						
						//if($discprods<1) $discprods = 10;
						$prod_sel_type = $row['product_select_type'];
						$discount_from = $row['discount_from'];
						$discount_to   = $row['discount_to'];
						
						$categ_new_products   = $row['category_newproducts'];
						$categ_disc_products   = $row['category_discproducts'];
						
						$template = $row['preview_contents'];
						$preview_title = $row['preview_title'];
						
						// To Display Products As per Notifications in the Settings page
						$cond_sql = " ";
						if($discount_from>0 && $discount_to>0) {
							$cond_sql = " AND CASE a.product_discount_enteredasval 
						WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
						WHEN '1' THEN a.product_discount 
						WHEN '2' THEN (a.product_webprice-a.product_discount) 
						END BETWEEN '".$discount_from."' AND '".$discount_to."'";
						} else if($discount_from>0 && $discount_to=='' ) {
							$cond_sql = " AND CASE a.product_discount_enteredasval 
						WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
						WHEN '1' THEN a.product_discount 
						WHEN '2' THEN (a.product_webprice-a.product_discount) 
						END > '".$discount_from."'";
						} else if($discount_from<0 && $discount_to=='') {
							$cond_sql = " AND  CASE a.product_discount_enteredasval 
						WHEN '0' THEN (a.product_webprice * a.product_discount /100) 
						WHEN '1' THEN a.product_discount 
						WHEN '2' THEN (a.product_webprice-a.product_discount) 
						END < '".$discount_to."'";
						}
						
						if($newprods > 0)
						{
						
						if(trim($categ_new_products) && $categ_new_products!=0)
						{
							$prodsql = "SELECT DISTINCT(product_id) 
											FROM products a, product_category_map b 
												WHERE b.product_categories_category_id IN ($categ_new_products) 
													  AND b.products_product_id=a.product_id 
													  AND a.product_adddate>SUBDATE(CURDATE(),INTERVAL 4 MONTH)
													  AND a.sites_site_id='".$siterow['site_id']."'
													  AND a.product_hide='N' ";
							
							
						} else {
							$prodsql = "SELECT DISTINCT(product_id) 
													 FROM products 
															WHERE sites_site_id='".$siterow['site_id']."' AND product_adddate>SUBDATE(CURDATE(),INTERVAL 1 MONTH) 
																  AND product_hide='N' 
																		ORDER BY product_adddate DESC 
																			LIMIT 0,".$newprods."";
						}	
															
						$prodres = $db->query($prodsql);
						$prodnum = $db->num_rows($prodres);
						
						if($prodnum > 0) 
						{
							$productlayoutdesign = '';
							//$prodcontent = "<table width='100%' border='0'>";
							while($prodrow = $db->fetch_array($prodres)) 
							{
									$prodID[]  = $prodrow['product_id'];
									$count+=1;
									$imagsql  = "SELECT  image_thumbpath 
														  FROM images a, images_product b 
																		 WHERE a.image_id=b.images_image_id 
																		   AND b.products_product_id = '".$prodrow['product_id']."' 
																		   AND a.sites_site_id = '".$siterow['site_id']."'
																				ORDER BY b.image_order ASC
																			 ";
									$imagres   = $db->query($imagsql);
									$imagrow   = $db->fetch_array($imagres);
									$images    = $imagrow['image_thumbpath'];
									
									$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
														   product_bulkdiscount_allowed 
																	FROM products 
																		 WHERE product_id='".$prodrow['product_id']."'";
									$prodnameres = $db->query($prodnamesql);
									$prodnamerow = $db->fetch_array($prodnameres);
									
					
									if($prodnamerow['product_discount']>0)
									{
										switch($prodnamerow['product_discount_enteredasval']) 
										{
											case '0' :
												$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
											break;
											case '1' :
												$rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
											break;
											case '2' :
												$rate =  $prodnamerow['product_discount'];		
											break;
											default :
												$rate = $prodnamerow['product_webprice'];
										}
									}	
										
									if(trim($images)) {
										$imgname = "<a href=\"http://".$siterow['site_domain']."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
										<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/".$images."' border='0'/></a>";					 
									} else { 
										$imgname = "<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/site_images/no_small_image.gif' border='0'/>";
									}
										$productlayouttem =	 str_replace('[IMG]',$imgname,$productlayout);
										$prodname 	      =  $prodnamerow['product_name'];
										
										$prodshortdesc    =  $prodnamerow['product_shortdesc'];
										$rate 	   	   	  =  display_price($rate,$siterow['site_id']);
									
								
										
										$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
										$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
										$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
										$productlayoutdesign .=  	$productlayouttem;
							}
								$template =	str_replace('[NEWProducts]',$productlayoutdesign,$template);
						} else {
								$template =	str_replace('[NEWProducts]'," No New Products Available ",$template);
								$nonewpdts = 1;
						}		
					} else {
						$nonewpdts = 1;
					}
					
					if($discprods > 0) 
					{
						$productdisclayoutdesign = '';
						if(trim($categ_disc_products) && $categ_disc_products!=0)
						{
							$prodsql = "SELECT DISTINCT(product_id), 
											CASE product_discount_enteredasval
											WHEN  '0'
												THEN (product_webprice * product_discount /100)
											WHEN  '1'
												THEN product_discount
											WHEN  '2'
												THEN (product_webprice-product_discount)
											END  AS discountval
												FROM products a, product_category_map b 
														WHERE a.sites_site_id='".$siterow['site_id']."'
															  AND b.product_categories_category_id IN ($categ_disc_products) 	
															  AND b.products_product_id=a.product_id	 
															  AND a.product_discount >0 
															  AND a.product_hide='N' 
															  {$cond_sql}
																	ORDER BY product_adddate DESC 
																		LIMIT 0,".$discprods."";
						} else 
						{ 		
							// replacing DiscProducts in teh Template
							$prodsql = "SELECT DISTINCT(product_id), 
											CASE product_discount_enteredasval
											WHEN  '0'
												THEN (product_webprice * product_discount /100)
											WHEN  '1'
												THEN product_discount
											WHEN  '2'
												THEN (product_webprice-product_discount)
											END  AS discountval
												 FROM products a
														WHERE sites_site_id='".$siterow['site_id']."' 
															  AND product_discount >0 
															  AND product_hide='N' 
															  {$cond_sql}
																	ORDER BY product_adddate DESC 
																		LIMIT 0,".$discprods."";
						}
																
						$prodres = $db->query($prodsql);
						$prodnum = $db->num_rows($prodres);
						if($prodnum > 0) 
							{
							//$prodcontent = "<table width='100%' border='0'>";
							 while($prodrow = $db->fetch_array($prodres))
							 	{
									if(is_array($prodID)&&(!in_array($prodrow['product_id'],$prodID)))
									{
									if($prod_sel_type == 'discount') 
									{
										$count+=1;
										$imagsql     = "SELECT  image_thumbpath 
															  FROM images a, images_product b 
																			 WHERE a.image_id=b.images_image_id 
																			   AND b.products_product_id = '".$prodrow['product_id']."' 
																			   AND a.sites_site_id = '".$siterow['site_id']."'
																					ORDER BY b.image_order ASC
																				 ";
										$imagres      = $db->query($imagsql);
										$imagrow      = $db->fetch_array($imagres);
										$images       = $imagrow['image_thumbpath'];
										
										
										$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
															   product_bulkdiscount_allowed 
																		FROM products 
																			 WHERE product_id='".$prodrow['product_id']."'";
										$prodnameres = $db->query($prodnamesql);
										$prodnamerow = $db->fetch_array($prodnameres);
											
										if(trim($images)) {
											$imgname = "<a href=\"http://".$siterow['site_domain']."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
											<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/".$images."' border='0'/></a>";					 
										} else { 
											$imgname = "<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/site_images/no_small_image.gif' border='0'/>";
										}
											$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
											$prodname 	   =    $prodnamerow['product_name'];
											
											$prodshortdesc =    $prodnamerow['product_shortdesc'];
											$rate 	   	   	=   display_price($prodrow['discountval'],$siterow['site_id']);
										
											
											$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
											$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
											$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
											$productdisclayoutdesign .=  	$productlayouttem;
										
									} else {
										$count+=1;
										$imagsql     = "SELECT  image_thumbpath 
															  FROM images a, images_product b 
																			 WHERE a.image_id=b.images_image_id 
																			   AND b.products_product_id = '".$prodrow['product_id']."' 
																			   AND a.sites_site_id = '".$siterow['site_id']."'
																					ORDER BY b.image_order ASC
																				 ";
										$imagres      = $db->query($imagsql);
										$imagrow      = $db->fetch_array($imagres);
										$images       = $imagrow['image_thumbpath'];
										
										$prodnamesql = "SELECT product_name, product_shortdesc, product_webprice, product_discount, product_discount_enteredasval, 
															   product_bulkdiscount_allowed 
																		FROM products 
																			 WHERE product_id='".$prodrow['product_id']."'";
										$prodnameres = $db->query($prodnamesql);
										$prodnamerow = $db->fetch_array($prodnameres);
											
										if(trim($images)) {
											$imgname = "<a href=\"http://".$siterow['site_domain']."/p".$prodrow['products_product_id']."/".strip_url($prodnamerow['product_name']).".html\">
											<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/".$images."' border='0'/></a>";					 
										} else { 
											$imgname = "<img src='http://".$siterow['site_domain']."/images/".$siterow['site_domain']."/site_images/no_small_image.gif' border='0'/>";
										}
											$productlayouttem =	str_replace('[IMG]',$imgname,$productlayout);
											$prodname 	   =    $prodnamerow['product_name'];
											
											$prodshortdesc =    $prodnamerow['product_shortdesc'];
											$rate 	   	   	=   display_price($prodrow['discountval'],$siterow['site_id']);
										
											
											$productlayouttem =	str_replace('[TITLE]',$prodname,$productlayouttem);
											$productlayouttem =	str_replace('[DESCRIPTION]',$prodshortdesc,$productlayouttem);
											$productlayouttem =	str_replace('[PRICE]',$rate,$productlayouttem);
											$productdisclayoutdesign .=  	$productlayouttem;
											
									  }	
							     }		
						     }	
								$template =	str_replace('[DiscProducts]',$productdisclayoutdesign,$template); //
						} else {
								$template =	str_replace('[DiscProducts]'," No Discounted Products Available ",$template); //
								$nodiscpdts = 1;
						}	
					} else {
						$nodiscpdts = 1;
					}		
					
				
				
					// To check weekly mails or monthly mails 
					if($row['set_senttype'] == 'Week') 
					{
						if($row['week_day'] == date("D")) 
						{
							$newsletterTitle = $row['newsletter_title'];
							$newsletterContent = $template;							
							$process = 1;
						}					
					} else {
						if($row['month_date'] == date("d"))
						{
							$newsletterTitle = $row['newsletter_title'];
							$newsletterContent = $template;		
							$process = 1;					
						}
					}
					
					if($nonewpdts == 1 && $nodiscpdts == 1) 		
					{
						$process = 0;
					}	
				if($process == 1)
				{ 
					 
					// Selecting Customers
					$cust_sql = "SELECT customer_fname, customer_mname, customer_surname, customer_email_7503 
										FROM customers
											WHERE customer_prod_disc_newsletter_receive='Y' AND customer_hide='0'
												  AND sites_site_id=".$siterow['site_id'];
					$cust_res = $db->query($cust_sql);						
					while($cust_row = $db->fetch_array($cust_res)) 
					{
						$headers  = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$headers .= "From: NewsLetter<test@test.com>\r\n";
						$mailsubject  = $newsletterTitle;
						$mailcontents = $newsletterContent;
						
						$custname = $cust_row['customer_fname']." ".$cust_row['customer_mname']." ".$cust_row['customer_surname'];
						
								$mailcontents =	str_replace('[Name]',$custname,$mailcontents);
								$mailcontents =	str_replace('[Email]',$cust_row['customer_email_7503'],$mailcontents);
								$mailcontents =	str_replace('[date]',date("Y-m-d"),$mailcontents); 
								$mailcontents =	str_replace('[Domain]',$siterow['site_domain'],$mailcontents); 
								
								
								$mailsubject =	str_replace('[Name]',$custname,$mailsubject);
								$mailsubject =	str_replace('[Email]',$cust_row['email'],$mailsubject);
								$mailsubject =	str_replace('[date]',date("Y-m-d"),$mailsubject);
								$mailsubject =	str_replace('[Domain]',$siterow['site_domain'],$mailsubject); 
						
					
						
						$to 	  = $cust_row['customer_email_7503'];	
						mail($to,$mailsubject,$mailcontents,$headers);
					}
				}	
		  }	// Notification While Ends Here
		
	} // Site Row Ends Here	
	
	
function display_price($price,$siteid)
{
	global $db,$ecom_siteid;
	$sql_curr = "SELECT curr_sign_char FROM general_settings_site_currency WHERE
				sites_site_id=$siteid AND curr_default=1";
	$ret_curr = $db->query($sql_curr);
	if ($db->num_rows($ret_curr))
	{
		$row_curr 	= $db->fetch_array($ret_curr);
		$curr		= $row_curr['curr_sign_char'];
	}
	$price = sprintf("%.2f",$price);
	return $curr.$price;
}	

function strip_url($name) {
	$name = trim($name);
	$name = str_replace(" ","-",$name);
	$name = str_replace("_","-",$name);
	$name = preg_replace("/[^0-9a-zA-Z-]+/", "", $name);
	$name = str_replace("----","-",$name);
	$name = str_replace("---","-",$name);
	$name = str_replace("--","-",$name);
	return strtolower($name);
}	
?>
