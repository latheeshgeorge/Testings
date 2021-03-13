<?php
	/*#################################################################
	# Script Name 	: cron_submitreview_mailer.php
	# Description 	: Page to Send Reminder Email to
	# Coded by 		: Sobin
	# Created on	: 28-Jan-2013
	# Modified by	: 
	# Modified On	:  
	#################################################################*/
	// define('ORG_DOCROOT','/var/www/html/webclinic/bshop4'); // Local path
		define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live path

		//require_once(ORG_DOCROOT."/config.php");

		require_once(ORG_DOCROOT."/config_db.php");
		require_once(ORG_DOCROOT.'/functions/functions.php');
		require_once(ORG_DOCROOT.'/includes/session.php');
		require_once(ORG_DOCROOT.'/includes/price_display.php');

		//require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");//live

	
	$sql_check	=	"SELECT * FROM general_settings_site_product_review WHERE is_active > 0";//echo $sql_check;echo "<br>";
	$res_check	=	$db->query($sql_check);
	$db->num_rows($res_check);
	if($db->num_rows($res_check) > 0)
	{ 			
		while($row_check = $db->fetch_array($res_check))
		{ 
			$setting_id					=	$row_check['id'];
			$sites_site_id		= $ecom_siteid	=	$row_check['sites_site_id'];
			$review_begin_date			=	$row_check['review_begin_date'];
			$review_mail_interval		=	$row_check['review_mail_interval'];
			$review_registered_customers=	$row_check['review_registered_customers'];
			$review_order_total			=	$row_check['review_order_total'];
			$review_giftvoucher_sent	=	$row_check['review_giftvoucher_sent'];
			$review_giftvoucher_disctype=	$row_check['review_giftvoucher_disctype'];
			$review_giftvoucher_discount=	$row_check['review_giftvoucher_discount'];
			$review_only_approval		=	$row_check['review_only_approval'];
			$review_giftvoucher_sent_range=	$row_check['review_giftvoucher_sent_range'];

			$sql_site					=	"SELECT site_domain,selfssl_active FROM sites WHERE site_id = ".$sites_site_id." LIMIT 1";//echo $sql_site;echo "<br>";
			$ret_site					=	$db->query($sql_site); 
			$row_site					=	$db->fetch_array($ret_site);
			$sites_hostname				=	$row_site['site_domain']; 
			$ecom_selfssl_active		=	$row_site['selfssl_active']; 
			if($ecom_selfssl_active==1)
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			
			

		    $image_path = ORG_DOCROOT.'/images/'.$sites_hostname;

            require_once($image_path.'/settings_cache/price_display_settings.php');
            require_once($image_path.'/settings_cache/settings_captions/price_display.php');
            require_once($image_path.'/settings_cache/settings_captions/price_display.php');

            $Captions_arr['PRICE_DISPLAY']   = $Cache_captions_arr;
             //echo $sql_site;
             $file_price_details        = $image_path."/otherfiles/price_inline_style.php";    	
				if(file_exists($file_price_details))
				{ 
				   require_once($image_path.'/otherfiles/price_inline_style.php');

				}
				
			if($review_registered_customers > 0)
			{
				$intrvl_cond	=	"";
				$intrvl_cond1	=	"";
				$intrvl_cond2	=	"";
				$intrvl_cond3	=	"";
				if($review_begin_date != '0000-00-00')
				{
					$intrvl_cond1	=	"ordr.order_despatched_completly_on >= '".$review_begin_date." 00:00:00' ";
					if($review_mail_interval > 0)
					{
						$intrvl_cond2	=	"ordr.order_despatched_completly_on <= DATE_SUB(NOW(),INTERVAL ".$review_mail_interval." DAY) ";
					}
				}
				else
				{
					if($review_mail_interval > 0)
					{
						$intrvl_cond2	=	"ordr.order_despatched_completly_on <= DATE_SUB(NOW(),INTERVAL ".$review_mail_interval." DAY) ";
					}
					/*else
					{
						$intrvl_cond2	=	"ordr.order_despatched_completly_on != '0000-00-00 00:00:00' ";
						echo $intrvl_cond2;echo "<br>";
					}*/
				}
				
				if($review_order_total > 0)
				{
					$intrvl_cond3	=	"AND ordr.order_totalprice >= ".$review_order_total."";
				}
				
				if($intrvl_cond1 != "")
				{
					$intrvl_cond	.=	" AND ( ".$intrvl_cond1." )";
				}
				if($intrvl_cond2 != "")
				{
					$intrvl_cond	.=	" AND ( ".$intrvl_cond2." )";
				}
				
				$mailsent_cond		=	" AND ordr.product_reviewmail_sent = 'NO' AND ordr.order_status = 'DESPATCHED' ";	
				
				$sql_ordr	=	"SELECT
												cust.customer_fname AS first_name,
												cust.customer_mname AS middle_name,
												cust.customer_surname AS sur_name,
												cust.customer_email_7503 AS email,
												ordr.order_id AS ord_id,
												ordr.order_date AS ord_date
										FROM
												customers cust, orders ordr
										WHERE
										(		cust.sites_site_id = ".$sites_site_id." AND ordr.sites_site_id = ".$sites_site_id."		)
										AND
										(		cust.customer_id = ordr.customers_customer_id 	)
										".$intrvl_cond.$intrvl_cond3.$mailsent_cond."
										ORDER BY
												ordr.order_id
										ASC
										";
										

			}
			else
			{ 
				$intrvl_cond	=	"";
				$intrvl_cond1	=	"";
				$intrvl_cond2	=	"";
				if($review_begin_date != '0000-00-00')
				{
					$intrvl_cond1	=	"ordr.order_despatched_completly_on >= '".$review_begin_date." 00:00:00' ";
					if($review_mail_interval > 0)
					{
						$intrvl_cond2	=	"AND ordr.order_despatched_completly_on <= DATE_SUB(NOW(),INTERVAL ".$review_mail_interval." DAY) ";
					}
				}
				else
				{
					if($review_mail_interval > 0)
					{
						$intrvl_cond2	=	"ordr.order_despatched_completly_on <= DATE_SUB(NOW(),INTERVAL ".$review_mail_interval." DAY) ";
					}
					/*else
					{
						$intrvl_cond2	=	"ordr.order_despatched_completly_on != '0000-00-00 00:00:00' ";
					}*/
				}
				
				
				if($intrvl_cond1 != "" && $intrvl_cond2 != "")
				{
					$intrvl_cond	.=	" ( ".$intrvl_cond1.$intrvl_cond2." )";
					
				}
				else if($intrvl_cond1 != "")
				{
					$intrvl_cond	.=	" ( ".$intrvl_cond1." )";
				}
				else if($intrvl_cond2 != "")
				{
					$intrvl_cond	.=	" ( ".$intrvl_cond2." )";
				}
				
				if($intrvl_cond != "")
				{				
					if($review_order_total > 0)
					{
						$intrvl_cond3	=	"AND ordr.order_totalprice >= ".$review_order_total."";
					}
					$mailsent_cond		=	" AND ordr.product_reviewmail_sent = 'NO' AND ordr.order_status = 'DESPATCHED' ";	
					
				}
				else
				{				
					if($review_order_total > 0)
					{
						$intrvl_cond3	=	"ordr.order_totalprice >= ".$review_order_total."";
					}
					if($intrvl_cond3 != "")
					{
						$mailsent_cond		.=	" AND ";
					}
					$mailsent_cond		.=	"ordr.product_reviewmail_sent = 'NO' AND ordr.order_status = 'DESPATCHED' ";	
					
				}
				
				$sql_ordr	=	"SELECT
												order_custfname AS first_name,
												order_custmname AS middle_name,
												order_custsurname AS sur_name,
												order_custemail AS  email,
												order_id AS ord_id,
												order_date AS ord_date												
										FROM
												orders ordr 
										WHERE
												".$intrvl_cond.$intrvl_cond3.$mailsent_cond."
										AND
												 ordr.sites_site_id = ".$sites_site_id."	
										ORDER BY
												order_id
										ASC
										";
			}
			//echo $sql_ordr;echo "<br>" ;die();
			//echo $sql_ordr;
			$res_ordr	=	$db->query($sql_ordr);
			if($db->num_rows($res_ordr) > 0)
			{ 
				//echo $sql_ordr;echo "<br>" ;die();
				$img_url   = $ecom_selfhttp.$sites_hostname."/images/".$sites_hostname; 

				/*$sql_email	=	"SELECT
												template_lettertitle,template_lettersubject,template_content,template_code
										FROM
												common_emailtemplates
										WHERE
												template_lettertype = 'REMINDER_SUBMIT_REVIEW' 
										LIMIT 1";echo $sql_email;echo "<br>";*/
				$sql_email	=	"SELECT
												*
										FROM
												general_settings_site_letter_templates
										WHERE
												lettertemplate_letter_type = 'REMINDER_SUBMIT_REVIEW'
										AND
												sites_site_id = ".$sites_site_id."
										LIMIT 1";//echo $sql_email;echo "<br>";
				$ret_email	=	$db->query($sql_email);
				if($db->num_rows($ret_email))
				{
					$row_email	=	$db->fetch_array($ret_email);
				}
				
				if($row_email['lettertemplate_contents'] != "")
				{
					while($row_ordr = $db->fetch_array($res_ordr))
					{
						$mailcontents	=	stripslashes($row_email['lettertemplate_contents']);
			
						if($row_ordr['middle_name'] != "")
						{	$customer_name	=	$row_ordr['first_name']." ".$row_ordr['middle_name']." ".$row_ordr['sur_name'];		}
						else
						{	$customer_name	=	$row_ordr['first_name']." ".$row_ordr['sur_name'];		}
						$customer_name = addslashes($customer_name);
						$customer_email	=	$row_ordr['email'];
						$order_id		=	$row_ordr['ord_id'];
						$order_date		=	$row_ordr['ord_date'];
						if($order_id>0)
						{
						$sql_product	=	"SELECT DISTINCT products_product_id,product_name FROM order_details WHERE orders_order_id = ".$order_id;//echo $sql_product;echo "<br>";
						$ret_product	=	$db->query($sql_product);
						if($db->num_rows($ret_product))
						{
							$product_details	=	"";
							$product_count		=	1;
							//modification get the prodcu details
						    $header = "";
						    $image_path 							= ORG_DOCROOT . '/images/' . $sites_hostname;
		                    $file_prod_details        = $image_path."/otherfiles/product_det.txt";    	
                            if(file_exists($file_prod_details))
						    { 
								$file_header        = $image_path."/otherfiles/product_det_header.txt";    	
								if(file_exists($file_header))
								{ 
									$fh = fopen($file_header, 'r');
									$header = fread($fh, filesize($file_header));
									fclose($fh); 
								 
								}
							   	$product_details	.= $header;
							    $fh = fopen($file_prod_details, 'r');
								$prod_details = fread($fh, filesize($file_prod_details));
								fclose($fh); 
							}	
							$img_prod = "";
							$prod_name = "";
							$submit_but = "";
							$prod_details_tmp = "";
							while($row_product = $db->fetch_array($ret_product))
							{
								 $prod_details_tmp = $prod_details; 
								 $id = $row_product['products_product_id'];
								 $image_thumbpath = get_image_product($id);
								 
											   $img_prod  =  $img_url."/".$image_thumbpath;
											   $prod_name = $row_product['product_name']."<br>";
											   $submit_but = "<a href='".$ecom_selfhttp.$sites_hostname."/submitreview-ord".$order_id."-set".$setting_id.".html' target=\"_blank\"><img src=\"".$img_url."/site_images/write_review.jpg\" width=\"120\" height=\"26\" /></a>";   
										       $prod_details_tmp = str_replace('[prod_img]',$img_prod,$prod_details_tmp); 	   
										       $prod_details_tmp = str_replace('[prod_name]',$prod_name,$prod_details_tmp); 	   
										       $prod_details_tmp = str_replace('[submit]',$submit_but,$prod_details_tmp); 	   
											   $product_details	.=  $prod_details_tmp;
											   $prod_details_tmp = "";


										
								//$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,1,1);

								//$img = 
								//$prod_details  .= str_replace( 
								//$product_details	.=	$product_count.". ".$row_product['product_name']."<br>";
								//$product_count++;
							}
																	//echo $product_details;

						}
						//modification for the featured product details
						
						    $file_feat_details        = $image_path."/otherfiles/featured_product.txt";    	
                            if(file_exists($file_feat_details))
						    { 
									$fh = fopen($file_feat_details, 'r');
									$featured_details = fread($fh, filesize($file_feat_details));
									fclose($fh); 
								 
							}
								
						// ##############################################################################################################
						// Building the query for featured product
						// ##############################################################################################################
						$sql_featured = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
						a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
						a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
						product_total_preorder_allowed,a.product_applytax,
						a.product_shortdesc,a.product_longdesc,b.featured_desc,b.featured_showimage,
						b.featured_showtitle,b.featured_showshortdescription,b.featured_showprice,b.featured_showimagetype,a.product_bonuspoints,
						a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
						a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
						a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
						a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice  
						FROM 
						products a, product_featured b 
						WHERE 
						a.sites_site_id = $sites_site_id 
						AND a.product_hide = 'N' 
						AND a.product_id=b.products_product_id 	LIMIT 1";
						$ret_featured = $db->query($sql_featured);
						// Calling the function to get the details of default currency
						$default_Currency_arr	= get_default_currency();
						// Assigning the current currency to the variable
						$sitesel_curr	= $default_Currency_arr['currency_id'];
						// If sitesel_curr have no value then set it as the default currency
						if (!$sitesel_curr)
						{
								$sitesel_curr           = $default_Currency_arr['currency_id'];// setting the default currency value
								//clear_all_cache();
						}
						$current_currency_details = get_current_currency_details();

									if($db->num_rows($ret_featured))
									{
									$row_featured 	=  $db->fetch_array($ret_featured);
									//price
									$price_class_arr['class_type']       ='div';
									$price_class_arr['ul_class'] 		= 'shelfBul';
									$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
									$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
									$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
									$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
									//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
									$price_disp =  show_Price($row_featured,$price_class_arr,'featured');	
									
									$price_disp =  str_replace('class="shelfBstrikeprice"',$shelfBstrikeprice,$price_disp);
									$price_disp =  str_replace('class="shelfByousaveprice"',$shelfByousaveprice,$price_disp);
									$price_disp =  str_replace('class="shelfBnormalprice"',$shelfBnormalprice,$price_disp);
									$price_disp =  str_replace("class='or_cls_list'",$or_cls_list,$price_disp);
									$price_disp =  str_replace('class="price_cls_list"',$price_cls_list,$price_disp);
									$price_disp =  str_replace("class='appr_cls_list'",$appr_cls_list,$price_disp);
									$feat_name 		=  "<a href='".$ecom_selfhttp.$sites_hostname."' target=\"_blank\" style=\"color: #139ac4;text-decoration:none\">".$row_featured['product_name']."</a>";
									$id 			=  $row_featured['product_id'];
									if($row_featured['featured_desc']!='')
									{   
										$feat_desc		= $row_featured['featured_desc'];
									}
									else
									{  
										$feat_desc		=$row_featured['product_shortdesc'];
									}	
									$image_fthumbpath = get_image_product($id);								 
									$img_fprod  =  $img_url."/".$image_fthumbpath;

									$fprod_details_tmp = $featured_details; 

									$fprod_details_tmp = str_replace('[prod_img]',$img_fprod,$fprod_details_tmp); 	   
									$fprod_details_tmp = str_replace('[prod_name]',$feat_name,$fprod_details_tmp); 	   
									$fprod_details_tmp = str_replace('[prod_desc]',$feat_desc,$fprod_details_tmp); 	   
                                     $fprod_details_tmp = str_replace('[feat_price]',$price_disp,$fprod_details_tmp); 	   

                                    $featured_product_details = $fprod_details_tmp;
									$fprod_details_tmp = "";
									}
		                $sites_hostname_link   = "<a href=\"".$ecom_selfhttp.$sites_hostname."\" target=\"_blank\" style=\"color: #139ac4;text-decoration:none\">".$sites_hostname."</a>"; 
						$email_link		=	"<a href=\"".$ecom_selfhttp.$sites_hostname."/submitreview-ord".$order_id."-set".$setting_id.".html\" target=\"_blank\">".$ecom_selfhttp.$sites_hostname."/submitreview-ord".$order_id."-set".$setting_id.".html</a>";
						$mailcontents	=	str_replace('[cust_name]',$customer_name,$mailcontents);
						$mailcontents	=	str_replace('[domain]',$sites_hostname_link,$mailcontents);
						$mailcontents	=	str_replace('[date]',date("Y-m-d"),$mailcontents); 
						$mailcontents	=	str_replace('[orderid]',$order_id,$mailcontents);
						$mailcontents	=	str_replace('[orderdate]',$order_date,$mailcontents);
						$mailcontents	=	str_replace('[link]',$email_link,$mailcontents);
						$mailcontents	=	str_replace('[product_details]',$product_details,$mailcontents);
						$mailcontents	=	str_replace('[featured_product]',$featured_product_details,$mailcontents);
                         //echo $mailcontents; 
						$sql_review_chk = " SELECT * FROM product_review_mail WHERE order_id=$order_id LIMIT 1 ";
						$ret_review_chk = $db->query($sql_review_chk);
						$proceed_mail = false;
						if($db->num_rows($ret_review_chk)>0)
						{
						  $row_review_chk = $db->fetch_array($ret_review_chk);
						  if($row_review_chk['review_mail_sent']=='No')
						  {
						    $proceed_mail = true;
						    $upd_review		=	"UPDATE product_review_mail SET review_mail_sent = 'Yes' WHERE order_id = ".$order_id;
						    $ret_review		=	$db->query($upd_review);
						  }
						}
						else
						{
						$proceed_mail = true;
						$ins_review		=	"INSERT INTO product_review_mail (order_id,review_mail_sent) VALUES (".$order_id.",'Yes')";//echo $ins_review;echo "<br>";
						$ret_review		=	$db->query($ins_review);
						}
						if($proceed_mail == true)
						{
						$ins_cronreview	=	"INSERT INTO
																product_review_cron_mails
														(		send_email_from,send_name,send_email,send_subject,send_content,send_hostname,send_site_id		)
														VALUES
														(		'".$row_email['lettertemplate_from']."','".$customer_name."','".$customer_email."',
																'".$row_email['lettertemplate_subject']."','".addslashes($mailcontents)."',
																'".$sites_hostname."',".$sites_site_id."
														)";
														//echo $ins_cronreview;echo "<br>";
						$ret_cronreview=	$db->query($ins_cronreview);
						
						
						$upd_order		=	"UPDATE orders SET product_reviewmail_sent = 'YES' WHERE order_id = ".$order_id;
						$ret_order		=	$db->query($upd_order);
						}
						//echo $mailcontents;echo "<br>";
						$mailcontents	=	"";
						}
					}
				}
				//exit;
			}
		}
	}
	function get_image_product($id)
	{
		        global $db;
				$sql = "SELECT 
				image_extralargepath,image_bigpath,image_thumbpath,image_iconpath 
				FROM
				images a,images_product b
				WHERE
				a.image_id = b.images_image_id 
				AND b.products_product_id = $id ORDER BY b.image_order LIMIT 1 ";
				$ret = $db->query($sql);
				if ($db->num_rows($ret))
				{
				$row = $db->fetch_array($ret);
				$image_thumbpath 	= $row['image_thumbpath'];
				}
				return $image_thumbpath;	
	}
	/*$sql_giftcheck	=	"SELECT
										prm.id AS prm_id,prm.order_id,prm.giftvoucher_id,prm.review_submitted,prm.giftvoucher_mail_sent,prm.review_approved,
										ord.sites_site_id,ord.order_custfname,ord.order_custmname,ord.order_custsurname,ord.order_custemail,ord.order_totalprice,
										gspr.review_giftvoucher_activedays,
										gspr.review_order_total,
										gspr.review_giftvoucher_disctype,
										gspr.review_giftvoucher_discount,
										gspr.review_only_approval
								FROM
										product_review_mail prm, orders ord, general_settings_site_product_review gspr
								WHERE
								(		prm.order_id = ord.order_id AND ord.sites_site_id = gspr.sites_site_id		)
								AND
								(		gspr.review_giftvoucher_sent = 1 AND prm.giftvoucher_mail_sent = 'NO'	)
								ORDER BY
										ordr.order_id
								ASC";
	$res_giftcheck	=	$db->query($sql_giftcheck);
	
	if($db->num_rows($res_giftcheck) > 0)
	{
		$sql_giftemail	=	"SELECT
											template_lettertitle,template_lettersubject,template_content,template_code
									FROM
											common_emailtemplates
									WHERE
											template_lettertype = 'SUBMIT_REVIEW_GIFTVOUCHER' 
									LIMIT 1";echo $sql_email;echo "<br>";
		$ret_giftemail	=	$db->query($sql_giftemail);
		if($db->num_rows($ret_giftemail))
		{
			$row_giftemail	=	$db->fetch_array($ret_giftemail);
		}
		while($row_giftcheck = $db->fetch_array($res_giftcheck))
		{
			$prm_id						=	$row_giftcheck['prm_id'];
			$order_id					=	$row_giftcheck['order_id'];
			$giftvoucher_id				=	$row_giftcheck['giftvoucher_id'];			
			$review_submitted			=	$row_giftcheck['review_submitted'];
			$giftvoucher_mail_sent		=	$row_giftcheck['giftvoucher_mail_sent'];
			$review_approved			=	$row_giftcheck['review_approved'];
			
			$sites_site_id				=	$row_giftcheck['sites_site_id'];
			$order_custfname			=	$row_giftcheck['order_custfname'];
			$order_custmname			=	$row_giftcheck['order_custmname'];
			$order_custsurname			=	$row_giftcheck['order_custsurname'];
			$order_custemail			=	$row_giftcheck['order_custemail'];
			$order_totalprice			=	$row_giftcheck['order_totalprice'];
			
			$review_giftvoucher_activedays=	$row_giftcheck['review_giftvoucher_activedays'];
			$review_order_total			=	$row_giftcheck['review_order_total'];
			$review_giftvoucher_disctype=	$row_giftcheck['review_giftvoucher_disctype'];
			$review_giftvoucher_discount=	$row_giftcheck['review_giftvoucher_discount'];
			$review_only_approval		=	$row_giftcheck['review_only_approval'];
			
			$sql_giftsite				=	"SELECT site_domain FROM sites WHERE site_id = ".$sites_site_id." LIMIT 1";echo $sql_giftsite;echo "<br>";
			$ret_giftsite				=	$db->query($sql_giftsite); 
			$row_giftsite				=	$db->fetch_array($ret_giftsite);
			$giftsites_hostname			=	$row_giftsite['site_domain'];
			
			if($review_order_total > 0)
			{
				if($review_order_total <= $order_totalprice)
				{
					if($review_only_approval > 0)
					{
						if($review_approved == 'Yes')
						{
							
						}
					}
				}
			}
			else
			{
				if($review_only_approval > 0)
				{
					if($review_approved == 'Yes')
					{
						
					}
				}
			}
		}
	}*/
?>
