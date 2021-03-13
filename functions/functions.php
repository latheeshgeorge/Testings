<?
#################################################################
# Script Name 		: functions.php
# Description 		: Page which contains the common function used in site.
# Coded by 			: Sny
# Created on		: 05-Dec-2007
# Modified by		: Sny
# Modified On		: 05-Dec-2008
#################################################################*/
include("bookmark-function.php");

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}

/* 10 Nov 2011 */
function add_line_break($content)
{
	global $ecom_selfhttp;
	$data = str_replace('</tr>','</tr>'."\n",$content);
	$data = str_replace('</td>','</td>'."\n",$data);
	$data = str_replace('</TR>','</TR>'."\n",$data);
	$data = str_replace('</TD>','</TD>'."\n",$data);
	return $data;
}
function Display_Component_Module($display_module,$path)
{
	global $ecom_selfhttp;
	global $ecom_themename,$protectedUrl;
	switch($display_module)
	{
		case 'mod_quicksearch':
			$retpath = $path."/themes/$ecom_themename/modules/mod_quicksearch.php";
		break;
		case 'mod_staticgroup':
			$retpath = $path."/themes/$ecom_themename/modules/mod_staticgroup.php";
		break;	
		case 'mod_compare_products':
			$retpath = $path."/themes/$ecom_themename/modules/mod_compare_products.php";
		break;
		case 'mod_productcatgroup':
			$retpath = $path."/themes/$ecom_themename/modules/mod_productcatgroup.php";
		break;	
		case 'mod_customerlogin':
			$retpath = $path."/themes/$ecom_themename/modules/mod_customerlogin.php";
		break;
		case 'mod_callback':
			$retpath = $path."/themes/$ecom_themename/modules/mod_callback.php";
		break;
		case 'mod_bestsellers':
			$retpath = $path."/themes/$ecom_themename/modules/mod_bestsellers.php";
		break;
		case 'mod_preorder':
			$retpath = $path."/themes/$ecom_themename/modules/mod_preorder.php";
		break;
		case 'mod_combo':
			$retpath = $path."/themes/$ecom_themename/modules/mod_combo.php";
		break;
		case 'mod_shelf':
			$retpath = $path."/themes/$ecom_themename/modules/mod_shelf.php";
		break;
		case 'mod_newsletter':
			$retpath = $path."/themes/$ecom_themename/modules/mod_newsletter.php";
		break;
		case 'mod_giftvoucher':
			$retpath = $path."/themes/$ecom_themename/modules/mod_giftvoucher.php";
		break;
		case 'mod_spendvoucher':
			$retpath = $path."/themes/$ecom_themename/modules/mod_spendvoucher.php";
		break;
		case 'mod_survey':
			$retpath = $path."/themes/$ecom_themename/modules/mod_survey.php";
		break;
		case 'mod_shopbybrandgroup':
			$retpath = $path."/themes/$ecom_themename/modules/mod_shopbybrandgroup.php";
		break;
		case 'mod_recentlyviewedproduct':
			$retpath = $path."/themes/$ecom_themename/modules/mod_recentlyviewedproduct.php";
		break;
		case 'mod_adverts':
			if ($protectedUrl == FALSE)
				$retpath = $path."/themes/$ecom_themename/modules/mod_adverts.php";
		break;
		case 'mod_site_reviews':
			$retpath = $path."/themes/$ecom_themename/modules/mod_sitereviews.php";
		break;
		case 'mod_productlist':
			$retpath = $path."/themes/$ecom_themename/modules/mod_productlist.php";
		break;
		case 'mod_visitors':
			$retpath = $path."/themes/$ecom_themename/modules/mod_statistics.php";
		break;
		case 'mod_ssl':
			$retpath = $path."/themes/$ecom_themename/modules/mod_ssl.php";
		break;
		case 'mod_currencyselector':
			$retpath = $path."/themes/$ecom_themename/modules/mod_currencyselector.php";
		break;
		case 'mod_featured':
			$retpath = $path."/themes/$ecom_themename/modules/mod_featured.php";
		break;
		case 'mod_searchfilter':
			$retpath = $path."/themes/$ecom_themename/modules/mod_searchfilter.php";
		break;
		case 'mod_searchrefinecategory':
			$retpath = $path."/themes/$ecom_themename/modules/mod_searchrefinecategory.php";
		break;
		 case 'mod_payonaccount':
			$retpath = $path."/themes/$ecom_themename/modules/mod_payonaccount.php";
		break;
		 case 'mod_shoppingcart':
			$retpath = $path."/themes/$ecom_themename/modules/mod_shoppingcart.php";
		break;
	};
	return $retpath;
}
function display_rating($rate,$ret=0,$rate_star='star-green.gif',$norate_star='star-white.gif',$prod_id=0)
{
	global $ecom_siteid,$Settings_arr;
	global $ecom_selfhttp;
	if($Settings_arr['proddet_showwritereview']==1 or $Settings_arr['proddet_showreadreview']==1)
	{
		$rate = ceil($rate);
		for ($i=0;$i<$rate;$i++)
		{
					if($ret==0)
						echo '<img src="'.url_site_image($rate_star,1).'"  alt="revscoreimg" />'; 
					elseif($ret==1)
						$retn .= '<img src="'.url_site_image($rate_star,1).'" alt="revscoreimg" />';
		}
		if($rate<5)
		{
			$rem = ceil(5-$rate);
			for ($i=0;$i<$rem;$i++)
			{
						if($ret==0)
							echo '<img src="'.url_site_image($norate_star,1).'" border="0" alt="revscoreimg" />'; 
						elseif($ret==1)
							$retn .= '<img src="'.url_site_image($norate_star,1).'" alt="revscoreimg" />';    
			}
		}
		if($ecom_siteid==104 or $ecom_siteid==106 or $ecom_siteid==74)
		{  
			global $db;
			$cnt = 0;
		       if($prod_id>0)
		       {
		          $sql_prodreview	= "SELECT count(review_id) as cnt
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$prod_id."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 10";
				 $ret_prodreview = $db->query($sql_prodreview);
				    if($db->num_rows($ret_prodreview))
					{
						$row_prodreview = $db->fetch_array($ret_prodreview);
				        $cnt = $row_prodreview['cnt']; 
					
					}
					if($cnt>0)
					{
					   $retn .= '<a href="'.url_product($prod_id,'',1).'?prod_curtab=-4#review" title="'.stripslashes($row_prod['product_name']).'"><div class="rev_cnt">	'.$cnt.' Review(s)</div></a>';
					}					
				}
		 }	
			if($ret==1)
				return $retn;
	}
}
function display_discount_type($products_arr,$Captions_arr)
{
	global $ecom_selfhttp;
  	if($products_arr['order_discount']>0 and $products_arr['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
	{
		$disp_msg = '';
		if ($products_arr['order_discount_type']=='custgroup')
		{
			$disp_msg = $Captions_arr['CART']['CART_GROUP_DISC'];//.' ('.$row_prods['order_discount_group_percentage'].'%)';
		}
		elseif ($products_arr['order_discount_type']=='customer')
		{
			$disp_msg = $Captions_arr['CART']['CART_CUST_DIR_DISC'];
		}
		elseif ($products_arr['order_discount_type']=='bulk')
		{
			$disp_msg = $Captions_arr['CART']['CART_BULK_DISC'];
		}
		elseif ($products_arr['order_discount_type']=='combo')
		{
			$disp_msg = $Captions_arr['CART']['CART_COMBO_DISC'];
		}
		elseif ($products_arr['order_discount_type']=='promotional')
		{
			$disp_msg = $Captions_arr['CART']['CART_PROM_DISC'];
		}
		elseif ($products_arr['order_discount_type']=='normal')
		{
		        if(trim($Captions_arr['CART']['CART_PROD_DIR_DISC'])!='')
				$disp_msg = $Captions_arr['CART']['CART_PROD_DIR_DISC'];
		}
		elseif ($products_arr['order_discount_type']=='pricepromise')
		{
			if(trim($Captions_arr['CART']['PRICE_PROMISE_DISC'])!='')
			$disp_msg = $Captions_arr['CART']['PRICE_PROMISE_DISC'];
		}
	}
	elseif($products_arr['order_discount']>0 and $products_arr['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
	{
	
		$disc_caption = '';
		$disc_arr = explode(',',$products_arr['order_detail_discount_type']);
		$disp_cap = array();
		foreach ($disc_arr as $k=>$v)
		{
			switch ($v)
			{
				case 'PROM':
					$disp_cap[] = $Captions_arr['CART']['CART_PROM_DISC'];
				break;
				case 'CUST_DIR':
					$disp_cap[] =  $Captions_arr['CART']['CART_CUST_DIR_DISC'];
				break;
				case 'CUST_GROUP':
					$disp_cap[] =  $Captions_arr['CART']['CART_GROUP_DISC'];
				break;
				case 'COMBO':
					$disp_cap[] =  $Captions_arr['CART']['CART_COMBO_DISC'];
				break;
				case 'BULK':
					$disp_cap[] =  $Captions_arr['CART']['CART_BULK_DISC'];
				break;	
				default:
					if(trim($Captions_arr['CART']['CART_PROD_DIR_DISC'])!='')
						$disp_cap[] =  $Captions_arr['CART']['CART_PROD_DIR_DISC'];
				break;
			};
		}
		if(count($disp_cap))
		{
			$disc_caption = implode('<br/>',$disp_cap);
		}
	}
	return $disc_caption;
}
function display_cart_discount($products_arr,$Captions_arr,$ret=false)
{
	global $ecom_selfhttp;
	$ret_arr = array();
	if ($products_arr["prom_prodcode_disc"] and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
	{
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}
	}	
	elseif ($products_arr["cust_disc_type"] !='' and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
	{	
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}	
	}
	elseif($products_arr['savings']['product_combo'] or $products_arr['userin_combo']) // Check whether combo discount is there
	{
		if($ret==false)
			echo print_price($products_arr["savings"]["product_combo"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product_combo"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product_combo"];
		}	
	}
	elseif($products_arr["savings"]["bulk"]) // Check whether bulk discount is there
	{
		if($ret==false)
			echo print_price($products_arr["savings"]["bulk"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["bulk"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["bulk"];
		}	
	}
	else
	{
		//echo print_price($products_arr["savings"]["product"],true);
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}
	}	
	if($products_arr['discount_type']!='')
	{
		$disc_caption = '';
		$disc_arr = explode(',',$products_arr['discount_type']);
		$disp_cap = array();
		
		foreach ($disc_arr as $k=>$v)
		{
			switch ($v)
			{
				case 'PROM':
					$disp_cap[] = $Captions_arr['CART']['CART_PROM_DISC'];
				break;
				case 'CUST_DIR':
					$disp_cap[] =  $Captions_arr['CART']['CART_CUST_DIR_DISC'];
				break;
				case 'CUST_GROUP':
					$disp_cap[] =  $Captions_arr['CART']['CART_GROUP_DISC'];
				break;
				case 'COMBO':
					$disp_cap[] =  $Captions_arr['CART']['CART_COMBO_DISC'];
				break;
				case 'BULK':
                    $disp_cap[] =  $Captions_arr['CART']['CART_BULK_DISC'];
				break;	
				case 'PRICE_PROMISE_DISC':
					$disp_cap[] =  $Captions_arr['CART']['PRICE_PROMISE_DISC'];
				break;
				default:
                    if(trim($Captions_arr['CART']['CART_PROD_DIR_DISC'])!='' and $products_arr["savings"]["product"]>0)
					{
				    	$disp_cap[] =  $Captions_arr['CART']['CART_PROD_DIR_DISC'];
					}	
				break;	
			};
		}
		if(count($disp_cap))
		{
			$disc_caption = '<br/>'.implode('<br/>',$disp_cap);
		}
		if($ret==false)
			echo $disc_caption;
		else
		{
			$ret_arr['caption'] = $disc_caption;
		}	
		if($ret==true)
		{
			return $ret_arr;
		}
	}
}                                                   
function stripslash_normal($caption)
{
	global $ecom_selfhttp;
	// stripslash and also apply htmlspecial charts
	//$ret = htmlspecialchars(stripslashes($caption), ENT_QUOTES);
	$ret = stripslashes($caption);
	return $ret;
}
function stripslash_javascript($caption)
{
	global $ecom_selfhttp;
	// stripslash and replace single quotes with double quotes
	$ret = str_replace("'",'"',stripslashes($caption));
	return $ret;
}

function getprotxCaptureType()
{
	global $db, $ecom_siteid, $ecom_hostname;
	global $ecom_selfhttp;
	//Get the paycapid for current site from sites table
	$sql_pay 		= "SELECT payment_capture_types_paymentcapture_id 
							FROM 
								general_settings_site_paymentcapture_type  
							WHERE 
								sites_site_id=$ecom_siteid 
						       	LIMIT 
								1";
	$ret_pay 		= $db->query($sql_pay);
	list($paycapid)	= $db->fetch_array($ret_pay);
	if ($paycapid and $paycapid!=0)//If paycap id exists for current site
	{
		//Find the code for payment capture type from the payment capture table
		$sql_paycap 		= "SELECT paymentcapture_code 
								FROM 
									payment_capture_types 
								WHERE 
									paymentcapture_id=$paycapid";
		$ret_paycap 		= $db->query($sql_paycap);
		list($capcode)		= $db->fetch_array($ret_paycap);
		return stripslashes($capcode);		
	}
	else //Handle the case of payment capture type not set for sites for which the payment type is protx
		return "PAYMENT";	
}

//set Cookie for category hits
function set_cookie_category($cat_id) {
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	if (!isset($_COOKIE['cat_cookie_'.$cat_id])) {
		setcookie('cat_cookie_'.$cat_id, $cat_id, time()+259200,"/");  /* expire in 3 days */
		// Check whether there exists an entry in product_category_hit_count_totals for current category
		$sql_check = "SELECT product_categories_category_id 
                                FROM 
                                        product_category_hit_count_totals 
                                WHERE 
                                        product_categories_category_id = $cat_id 
                                        AND sites_site_id = $ecom_siteid 
                                LIMIT 
                                        1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0) // case if no extries exists 
		{
			$insert_array															= array();
			$insert_array['product_categories_category_id']			= $cat_id;
			$insert_array['sites_site_id']										= $ecom_siteid;
			$insert_array['total_hits']											= 1;
			$db->insert_from_array($insert_array,'product_category_hit_count_totals');
		}
		else // case if entries already exists
		{
			$sql_update = "UPDATE 
								product_category_hit_count_totals 
							SET 
								total_hits = total_hits + 1 
							WHERE 
								product_categories_category_id = $cat_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($sql_update);
		}
		$sql_hit_count = "SELECT hit_count_id FROM product_category_hit_count WHERE category_id=$cat_id AND month=".date('m')." AND year=".date('Y');
		$res_hit_count = $db->query($sql_hit_count);
		if($db->num_rows($res_hit_count)) {
			$db->query("UPDATE product_category_hit_count SET hits=hits+1 WHERE category_id=$cat_id AND month=".date('m')." AND year=".date('Y'));
		} else {
			$db->query("INSERT INTO product_category_hit_count SET hits=1, category_id=$cat_id, month=".date('m').", year=".date('Y'));
		}
	}
}
//set products views for unipad
function set_product_views($product_id) {
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	//echo time();
    $date = date("Y-m-d H:m:s", strtotime('-24 hours', time())); 
	 $sql_prod_check = "SELECT * FROM product_viewed_count WHERE views_sites_site_id=$ecom_siteid AND views_product_id=$product_id AND views_time_stamp < '$date'";
	$ret_prod_check =  $db->query($sql_prod_check);
	if($db->num_rows($ret_prod_check)>0)
	{
	   while($row_prod=$db->fetch_array($ret_prod_check))
	   {
	         $prod_ids[] = $row_prod['id'];
	   }
	   //print_r($prod_ids);
	     $prod_idstr = implode(',',$prod_ids);
	     $del_prod = "DELETE FROM product_viewed_count WHERE id IN($prod_idstr) AND views_sites_site_id=$ecom_siteid";
	     $db->query($del_prod);
	}
			$insert_array									= array();
			$insert_array['views_product_id']				= $product_id;
			$insert_array['views_sites_site_id']			= $ecom_siteid;
			$insert_array['views_time_stamp']				= date("Y-m-d H:i:s");
			//print_r($insert_array);
			$db->insert_from_array($insert_array,'product_viewed_count');
}
//set Cookie for product hits
function set_cookie_product($product_id) {
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	if($ecom_siteid==117 || $ecom_siteid==109)
	{
		set_product_views($_REQUEST['product_id']);
	}
	if (!isset($_COOKIE['prod_cookie_'.$product_id])) {
		setcookie('prod_cookie_'.$product_id, $product_id, time()+259200,"/");  /* expire in 3 days */
		// Check whether there exists an entry in product_hit_count_totals for current product
		$sql_check = "SELECT products_product_id 
								FROM 
									product_hit_count_totals 
								WHERE 
									products_product_id = $product_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0) // case if no extries exists 
		{
			$insert_array															= array();
			$insert_array['products_product_id']							= $product_id;
			$insert_array['sites_site_id']										= $ecom_siteid;
			$insert_array['total_hits']											= 1;
			$db->insert_from_array($insert_array,'product_hit_count_totals');
		}
		else // case if entries already exists
		{
			$sql_update = "UPDATE 
										product_hit_count_totals 
									SET 
										total_hits = total_hits + 1 
									WHERE 
										products_product_id = $product_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
			$db->query($sql_update);
		}
		//$db->query("UPDATE products SET hit_count=hit_count+1 WHERE sites_site_id=$ecom_siteid AND product_id=$product_id");
		$sql_hit_count = "SELECT hit_count_id FROM product_hit_count WHERE product_id=$product_id AND month=".date('m')." AND year=".date('Y');
		$res_hit_count = $db->query($sql_hit_count);
		if($db->num_rows($res_hit_count)) {
			$db->query("UPDATE product_hit_count SET hits=hits+1 WHERE product_id=$product_id AND month=".date('m')." AND year=".date('Y'));
		} else {
			$db->query("INSERT INTO product_hit_count SET hits=1, product_id=$product_id, month=".date('m').", year=".date('Y'));
		}
	}
}
// redirect to Invalid page for illegal entries
function redirectIllegal()
{
	global $ecom_hostname;
	global $ecom_selfhttp;
	?>
	<script language="javascript">
		window.location= <?php echo $ecom_selfhttp.$ecom_hostname?>/invalid_input.html';
	</script>
	<?
	exit;
}
/* Function which echo the base code of webtracker script */
function get_WebtrackerBottomScript()
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	if(get_session_var('stats_exists_site') > 0 )
	{
?>
		<script type="text/javascript">
			document.write('<img border="0" hspace="0" vspace="0" src="http://www.b-1st.com/newStats/image.php?account=<?php echo get_session_var('stats_exists_site')?>'+data+'" alt="Counter" title="Counter" width="0" height="0">');
		</script>
<?php
	}
}

// Function to check whether the given date is valid
function is_valid_date($date,$format='normal',$sep='-')
{
	global $ecom_selfhttp;
	$date_arr 	= explode(" ",$date); // done to extract the time section if exists
	$t_date		= $date_arr[0];
	$sp_date	= explode($sep,$t_date); // splitting the date base on the seperator
	$valid_Date	= true;
	if(count($sp_date)!= 3) // check whether there is exactly 3 elements in array after splitting
	$valid_Date = false;
	if($valid_Date)
	{
		// Check whether all the splitted elements are valid
		if(!is_numeric($sp_date[0]) or $sp_date[0]==0 or !is_numeric($sp_date[1]) or $sp_date[1]==0 or !is_numeric($sp_date[2]) or $sp_date[2]==0)
		$valid_Date = false;
		else
		{
			if($sp_date[0]<1 or $sp_date[1]<1 or $sp_date[2]<1)
			$valid_Date = false;
		}
	}
	if($valid_Date)
	{
		switch($format)
		{
			case 'normal':
			if (!checkdate($sp_date[1],$sp_date[0],$sp_date[2]))
			$valid_Date = false;
			break;
			case 'mysql':
			if (!checkdate($sp_date[1],$sp_date[2],$sp_date[0]))
			$valid_Date = false;
			break;
		};
	}
	return $valid_Date;
}

function check_allow_product_discount($cust_id,$cartData)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	if($cust_id) // check whether signed in
	{
		/*if ($cartData['customer']['customers_corporation_department_department_id']!=0) // case if current customer is linked with corporation
		{
			if($cartData['discounts']['corporation_allow_product_discount']==1)
				$allow_prod_disc = true;	
			else 	
				$allow_prod_disc = false;	
		}
		else // case if customer is not linked with corporation
		{*/
			if($cartData['customer']['customer_allow_product_discount'])
			{
				$allow_prod_disc = true;	
			}
		//}	
	}
	else // if not signed in then always apply product descount
		$allow_prod_disc = true;	
	return $allow_prod_disc;
}
// Function to get the general setting for the current site
function getGeneralSettings()
{
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	$sql = "SELECT listing_id, sites_site_id, product_maintainstock, product_show_instock, bonus_points_instock, product_decrementstock, bonuspoint_rate, order_confirmationmail,
							affiliate_commission, thumbnail_in_viewcart, thumbnail_in_wishlist, thumbnail_in_enquiry, empty_cart, empty_wishlist, config_continue_shopping, encrypted_cc_numbers,
							terms_and_condition_at_checkout, same_billing_shipping_checkout, hide_addtocart_login, hide_price_login, hide_newuser, hide_forgotpass, check_stock_management_before_checkout,
							turnoff_catimage, forcecustomer_login_checkout, ban_ipaddress, saletax_before_discount, apply_tax_ondelivery, apply_tax_ongiftwrap, epos_available, best_seller_picktype,
							show_cart_promotional_voucher, search_prodlisting, category_prodlisting, linked_prodlisting, shop_prodlisting, preorder_prodlisting, bestseller_prodlisting, favorite_prodlisting,
							favoritecategory_prodlisting, recentpurchased_prodlisting, paytype_listingtype, show_qty_box, enable_caching_in_site, imageverification_req_newsletter, imageverification_req_voucher,
							imageverification_req_sitereview, imageverification_req_customreg, imageverification_req_prodreview, imageverification_req_payonaccount, compshelf_showimagetype, 
							midshelf_showimagetype, recent_showimagetype, search_showimagetype, search_catshowimagetype, category_showimagetype, categoryprod_showimagetype,
							subcategory_showimagetype, productdetail_showimagetype, linkedprod_showimagetype, midcombo_showimagetype, shop_showimagetype, subshop_showimagetype,
							shopprod_showimagetype, myfavouritecategory_showimagetype, myfavouriteproduct_showimagetype, product_cart_showimagetype, product_enquiry_showimagetype,
							product_wishlist_showimagetype, show_variable_new_row, show_prod_image_inflash, product_compare_enable, no_of_products_to_compare, product_compare_prodlist_enable,
							product_compare_proddetail_enable, product_sizechart_default_mainheading, category_subcatlisttype, product_displaytype, product_displaywhere, product_showimage, 
							product_showtitle, product_showshortdescription, product_showprice, voucher_prefix, delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, 
							delivery_settings_weight_increment, delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment, unit_of_weight, pick_currency_rate_automatically,
							delivery_exclude_from_gift_prom_disc 
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_assoc($ret);
		foreach ($row as $k=>$v)
		{
			$settings[$k] = $v;
		}
	}
	$sql = "SELECT listing_id, sites_site_id, category_orderfield, category_orderby, product_orderfield, shopbybrand_shops_orderfield, shopbybrand_shops_orderby,
						productshop_orderfield, productshop_orderby, product_orderfield_bestseller, product_orderfield_preorder, enquiry_orderfield_settings, orders_orderfield_enqposts,
						orders_orderfield_settings, orders_orderfield_enquiry, product_orderfield_shelf, product_orderfield_search, product_orderfield_combo, product_orderfield_favorite,
						product_orderby, product_orderby_bestseller, product_orderby_preorder, enquiry_orderby_settings, orders_orderby_enquiry, orders_orderby_settings,
						orders_orderby_enqposts, product_orderby_shelf, product_orderby_search, product_orderby_combo, product_orderby_favorite, product_maxcntperpage,
						product_maxcntperpage_bestseller, enquiry_maxcntperpage, orders_maxcntperpage, orders_maxcntperpage_enquiry, orders_maxcntperpage_enqposts,
						product_maxcnt_bestseller, product_maxbestseller_in_component, product_maxcntperpage_shelf, product_maxcntperpage_preorder, product_maxcntperpage_favorite,
						product_maxshelfprod_in_component, product_maxcntperpage_search, product_maxcntperpage_shops, product_preorder_in_component, prodreview_ord_fld, 
						prodreview_ord_orderby, sitereview_ord_fld, sitereview_ord_orderby, productreview_maxcntperpage, sitereview_maxcntperpage, product_maxcnt_fav_category,
						product_maxcnt_recent_purchased, payon_maxcntperpage_statements 
					FROM 
						general_settings_sites_listorders 
					WHERE 
						sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_assoc($ret);
		foreach ($row as $k=>$v)
		{
			$settings[$k] = $v;
		}
	}
	$sql = "SELECT sites_site_id, adv_showkeyword, adv_showcategory, adv_showstocklevel, adv_showpricerange, adv_showcharacteristics, adv_showproductmodel,
							adv_showlabel, adv_showsearchfor, adv_showsearchincluding, adv_shosearchsortby, adv_showsearchperpage, comp_showprice, comp_showstock,
							comp_showlabels, comp_showdesc, comp_showbulkdisc, comp_showshipping, comp_showmanufact, comp_showmodel, comp_showbonus, 
							comp_showrating, comp_showweight, proddet_showfavourite, proddet_showemailfriend, proddet_showpdf, proddet_showreadreview,
							proddet_showwritereview 
					FROM 
						general_settings_sites_common_onoff 
					WHERE 
						sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_assoc($ret);
		foreach ($row as $k=>$v)
		{
			$settings[$k] = $v;
		}
	}
	return $settings;
}
// ** Function to get the details of Customers
function get_customer()
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$customer_id = get_session_var("ecom_login_customer");
	if(!$customer_id) return NULL;
	$sql = "SELECT customer_id, sites_site_id, customers_corporation_department_department_id, customer_activated, customer_accounttype, customer_title, customer_fname,
							customer_mname, customer_surname, customer_position, customer_compname, customer_comptype, customer_compregno, customer_compvatregno,
							customer_buildingname, customer_streetname, customer_towncity, customer_statecounty, customer_phone, customer_fax, customer_mobile,
							customer_postcode, country_id, customer_email_7503, customer_pwd_9501, customer_bonus, customer_discount, customer_allow_product_discount, 
							customer_use_bonus_points, customer_referred_by, customer_addedon, customer_anaffiliate, customer_approved_affiliate, customer_approved_affiliate_on,
							customer_affiliate_commission, customer_affiliate_taxid, shop_id, customer_hide, customer_last_login_date, customer_prod_disc_newsletter_receive, 
							customer_payonaccount_status, customer_payonaccount_maxlimit, customer_payonaccount_usedlimit, customer_payonaccount_billcycle_day, 
							customer_payonaccount_rejectreason, customer_payonaccount_laststatementdate 
				FROM
					customers
				WHERE
					customer_id = $customer_id
					AND customer_hide = 0
					AND customer_activated = 1
					AND sites_site_id = $ecom_siteid
				LIMIT
					1";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_array($ret);
		if($row['customer_use_bonus_points']==0)
			$row['customer_bonus'] = 0;
		return $row;
	}

}

// ** Function to check whether ip addresses is banned or not
function is_IP_banned()
{
	global $db,$ecom_siteid,$Settings_arr;
	global $ecom_selfhttp;
	$curip			= $_SERVER['REMOTE_ADDR'];
	$blockedip		= trim($Settings_arr['ban_ipaddress']);
	
	if ($blockedip)
	{
		$ip_arr = explode("\n",$blockedip);
		for ($i=0;$i<count($ip_arr);$i++)
		{
			if (trim($ip_arr[$i])==$curip)
				return true;
		}
	}
}
// Function to get the price display settings for the current site
function getPriceDisplaySettings()
{
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	$sql = "SELECT price_id, sites_site_id, price_displaytype, price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix, price_discountprefix, price_discountsuffix,
							price_specialofferprefix, price_specialoffersuffix, price_show_yousave, strike_baseprice, price_yousaveprefix, price_yousavesuffix, price_costplusprefix,
							price_costplussuffix, price_availabledateprefix, price_availabledatesuffix, price_noprice, price_variablepriceadd_prefix, price_variablepriceadd_suffix,
							price_variablepriceless_prefix, price_variablepriceless_suffix, price_variablepricefull_prefix, price_variablepricefull_suffix, price_applydiscount_tovariable,
							price_applytax_todelivery, price_middleshelf_1_reqbreak, price_middleshelf_3_reqbreak, price_compshelf_reqbreak, price_searchresult_1_reqbreak,
							price_searchresult_3_reqbreak, price_proddetails_reqbreak, price_categorydetails_1_reqbreak, price_categorydetails_3_reqbreak, price_combodeals_1_reqbreak,
							price_combodeals_3_reqbreak, price_other_1_reqbreak, price_linkedprod_1_reqbreak, price_linkedprod_3_reqbreak, price_other_3_reqbreak, price_best_1_reqbreak,
							price_best_3_reqbreak, price_shopbrand_1_reqbreak, price_shopbrand_3_reqbreak, price_featured_reqbreak, price_variableprice_display, price_display_discount_with_price
			 FROM 
			 	general_settings_site_pricedisplay 
			WHERE 
				sites_site_id = $ecom_siteid 
			LIMIT 
				1";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		$row = $db->fetch_assoc($ret);
		foreach ($row as $k=>$v)
		{
			$settings[$k] = $v;
		}
	}
		return $settings;
}
/* Function to get the list of caption sections */
function get_CaptionSections()
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$sql = "SELECT section_id, section_code 
					FROM 
						general_settings_section 
					ORDER BY 
						section_name";
	$ret = $db->query($sql);
	if ($db->num_rows($ret))
	{
		while ($row = $db->fetch_array($ret))
		{
			$section_arr[$row['section_code']] = $row['section_id'];
		}
	}
	else
		$section_arr = array();
	return $section_arr;
}
// Function to get the captions for the current site
function getCaptions_OLD($section = 'COMMON')
{
	global $db,$ecom_siteid,$ecom_section_Arr,$Captions_arr;
	global $ecom_selfhttp;
	// check whether section name is given
	if ($section)
	{
		if(array_key_exists($section,$ecom_section_Arr))
		{
			$add_condition  = " AND general_settings_section_section_id = ".$ecom_section_Arr[$section];
		}
		else
		{
			$sql_sec = "SELECT section_id FROM general_settings_section WHERE section_code = '$section' LIMIT 1";
			$ret_sec = $db->query($sql_sec);
			if ($db->num_rows($ret_sec))
			{
				$row_sec = $db->fetch_array($ret_sec);
				$add_condition  = " AND general_settings_section_section_id = ".$row_sec['section_id'];
			}
		}	
	}
	if(is_array($Captions_arr[$section]))
	{
		return $Captions_arr[$section];
	}
	else
	{
		$sql_cap = "SELECT general_key,general_text FROM general_settings_site_captions WHERE sites_site_id=$ecom_siteid $add_condition";
		$ret_cap = $db->query($sql_cap);
		if ($db->num_rows($ret_cap))
		{
			while ($row_cap = $db->fetch_array($ret_cap))
			{
				$key 			= $row_cap['general_key'];
				$cap 			= stripslashes($row_cap['general_text']);
				$caption[$key] 	= stripslashes($cap);
			}
			$Captions_arr[$section] = $caption;
		}
	}	
	return $caption;
}
// Function to get the captions for the current site
function getCaptions($section = 'COMMON')
{
	global $db,$ecom_siteid,$ecom_section_Arr,$Captions_arr,$image_path,$ecom_hostname;
	global $ecom_selfhttp;
	$file_path = $image_path .'/settings_cache/settings_captions/'.strtolower($section).'.php';
	if(file_exists($file_path))
	{
		include ($file_path);
		return $Cache_captions_arr;
	}
	return $caption;
}
/* Function to display the sold out image */
function format_currency($curr,$decimal_val,$decimal_char='.',$thousand_char=',')
{
	global $ecom_selfhttp;
	$var = number_format($curr,$decimal_val,$decimal_char,$thousand_char);
	$split = explode(".",$var);
	if($split[1] == '00')
		return $split[0];
	else
		return $var;
}
function printcurr($number,$curr_sign)
{
	global $ecom_siteid;
	global $ecom_selfhttp;
	if ($ecom_siteid==102) // case of kqf
		$number = round($number,2);
	$ret = sprintf("%s%01.2f", $curr_sign, $number);
	return $ret;
}
function serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc) {
	global $alert;
	global $ecom_selfhttp;
	foreach($fieldRequired as $k => $v) {
		if(trim($v) == "" || $v == '0') {
			$alert = "Enter ".$fieldDescription[$k];
			return false;
		}
	}
	foreach($fieldEmail as $v) { 
		//if(!ereg("^[a-z0-9_.-]+@[a-z0-9-]+\.([a-z.]{2,15})",trim($v))) {
		//if(!ereg("^[-a-zA-Z0-9_.]+@[-a-zA-Z0-9]+\.([-a-zA-Z.]{2,15})",trim($v))) {
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",trim($v))) {	
			$alert = "Enter a valid Email address";
			return false;
		}
	}

	if (isset($fieldConfirm[0])) {
		if($fieldConfirm[0] != $fieldConfirm[1]) {
			$alert = "Your ".$fieldConfirmDesc[0]." and ".$fieldConfirmDesc[1]." does not match";
			return false;
		}
	}
	foreach($fieldNumeric as $k => $v) {
		if($v && !is_numeric($v)) {
			$alert = "Enter numeric value for ".$fieldNumericDesc[$k];
			return false;
		}
	}
	return true;
}
function generateselectbox($name,$option_values,$selected,$onblur='',$onchange='',$multiple=0,$class='',$noid=false,$id='') {

global $ecom_selfhttp;

	$return_value = "<select name='$name' ";
	if ($noid==false)
	{
		if ($id=='')
			$return_value .= " id='$name' ";
		else
			$return_value .= " id='$id' ";
	}
	if ($multiple > 0) $return_value .= " multiple='multiple' size='$multiple'";
	if($onblur) {
		$return_value .= " onblur='$onblur'";
	}
	if($onchange) {
		$return_value .= " onchange='$onchange'";
	}
	if ($class!='')
		$cls = "class='".$class."'";
	$return_value .= " $cls>";
	foreach($option_values as $k => $v) {
		if(is_array($selected)) {
			if(in_array($k,$selected)) {
				$return_value .= "<option value='$k' selected='selected'>$v</option>";
			} else {
				$return_value .= "<option value='$k'>$v</option>";
			}
		} else {
			if($selected == $k) {
				$return_value .= "<option value='$k' selected='selected'>$v</option>";
			} else {
				$return_value .= "<option value='$k'>$v</option>";
			}
		}
	}
	$return_value .= "</select>";
	return $return_value;
}
	function add_slash($varial,$strip_html=true)
	{
		global $ecom_selfhttp;
		if ($strip_html)
			$varial = strip_tags($varial);
		#checking whether magic quotes are on
		//if (!get_magic_quotes_gpc()){
			//$ret=addslashes($varial);
		//} else 
		{
			$ret=$varial;
		}
		return $ret;
	}




	// Function to get the tax % for current site
	function get_Common_Settings()
	{
		global $db,$ecom_siteid,$ecom_section_Arr,$image_path,$ecom_hostname,$default_Currency_arr,$sitesel_curr;
		global $ecom_selfhttp;
		$file_path 	= $image_path .'/settings_cache/common_settings.php';
		if(file_exists($file_path))
		{
			include ($file_path);
			$ret_arr['tax'] 												= $ret_tax;
			$ret_arr['delivery']											= $ret_delivery;
			$ret_arr['paytypeId']										= $ret_paytypeId;
			$ret_arr['paytypeCode']									= $ret_paytypeCode;
			$ret_arr['paymethodId']									= $ret_paymethodId;
			$ret_arr['paymethodKey']									= $ret_paymethodKey;
			$ret_arr['total_paytypes']									= $total_paytype_cnts;
			$ret_arr['total_paymethods_cnt']						= $total_paymethods_cnt;
			$ret_arr['total_paymethods_no_googlecnt']		= $total_paymethods_without_google_cnt;
			return $ret_arr;
		}
	}
	function get_Tax_OLD()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$tax_vals = 0;
		$tax_name	= array();
		$sql_tax = "SELECT tax_name,tax_val
						FROM
							general_settings_site_tax
						WHERE
							sites_site_id = $ecom_siteid
							AND tax_active = 1";
		$ret_tax = $db->query($sql_tax);
		if ($db->num_rows($ret_tax))
		{
			while ($row_tax = $db->fetch_array($ret_tax))
			{
				$tax_vals += $row_tax['tax_val'];
				$tax_name[] = stripslashes($row_tax['tax_name']);
			}
		}
		$ret_tx['tax_val'] 	= $tax_vals;
		$ret_tx['tax_name'] = $tax_name;
		return $ret_tx;
	}
	// Function to get the default currency details for the current site
	function get_default_currency_OLD()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$sql_curr = "SELECT currency_id,curr_sign_char,curr_code,curr_rate,curr_numeric_code,curr_margin 
						FROM
							general_settings_site_currency
						WHERE
							sites_site_id = $ecom_siteid
							AND curr_default=1 
						LIMIT 
							1";
		$ret_curr = $db->query($sql_curr);
		if ($db->num_rows($ret_curr))
		{
			$row_curr = $db->fetch_array($ret_curr);
		}
		return $row_curr;
	}
	
	function get_default_currency()
	{
		global $db,$ecom_siteid,$ecom_section_Arr,$Captions_arr,$image_path,$ecom_hostname;
		global $ecom_selfhttp;
		$file_path = $image_path .'/settings_cache/currency.php';
		if(file_exists($file_path))
		{
			include ($file_path);
			return $default_curr;
		}
	}
	// Function to get the list of all currencies in the site
	// Function to get the default currency details for the current site
	function get_currency_list()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$ret_arr = array();
		$sql_curr = "SELECT currency_id,curr_sign_char,curr_code,curr_rate,curr_numeric_code,curr_name 
						FROM
							general_settings_site_currency
						WHERE
							sites_site_id = $ecom_siteid";
		$ret_curr = $db->query($sql_curr);
		if ($db->num_rows($ret_curr))
		{
			while($row_curr = $db->fetch_array($ret_curr))
			{
				$ret_arr[$row_curr['currency_id']] = stripslashes($row_curr['curr_name']).' '.stripslashes($row_curr['curr_sign_char']);
			}
		}
		return $ret_arr;
	}
	
	
	// function to check whether the component is valid for display by checking the start and end date
	function validate_component_dates($start_date,$end_date)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$startdate_arr 		= explode(' ',$start_date);
		$startdate  		=  explode('-',$startdate_arr[0]);
		$enddate_arr		= explode(' ',$end_date);
		$enddate  			=  explode('-',$enddate_arr[0]);
		$starttime          = explode(':',$startdate_arr[1]);
		$endtime   			= explode(':',$enddate_arr[1]);
		// Converting the dates to timestamp for comparison to check whether the shelf is to be displayed
		$today			= mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		$start_day		= mktime($starttime[0],$starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
		$end_day		= mktime($endtime[0],$endtime[1],$endtime[2],$enddate[1],$enddate[2],$enddate[0]);
		if($start_day<=$today and $end_day >=$today)
			$proceed = true; // case if valid and shelf is to be displayed
		else
			$proceed = false; // case if invalid and shelf not to be displayed
		return $proceed;
	}

	//function to get the list of all images of a particular category
	function get_imagelist($mod,$id,$field='image_thumbpath',$exclude_id=0,$showonly=0,$limit=0,$force_order='')
	{
		global $ecom_siteid,$db,$ecom_hostname;
		global $ecom_selfhttp;
		$ret_arr		= array();
		if ($showonly!=0)
			$add_condition = "AND a.image_id=$showonly";
		elseif($exclude_id!=0)
			$add_condition = " AND a.image_id <> $exclude_id ";


		switch($mod)
		{
			case 'prod':// case of product image
				$table				= 'images_product';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.products_product_id = $id";
				$notitle			= 0;
			break;
			case 'combo':// case of combo images
				$table				= 'images_combo';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.combo_combo_id = $id";
				$notitle			= 0;
			break;
			case 'bow':// case of gift wrap bows
				$table				= 'images_giftwrap_bow';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.giftwrap_bows_bow_id = $id";
				$notitle			= 0;
			break;
			case 'card':// case of gift wrap card
				$table				= 'images_giftwrap_card';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.giftwrap_card_card_id = $id";
				$notitle			= 0;
			break;
			case 'paper':// case of gift wrap paper
				$table				= 'images_giftwrap_paper';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.giftwrap_paper_paper_id = $id";
				$notitle			= 0;
			break;
			case 'ribbon':// case of gift wrap ribbon
				$table				= 'images_giftwrap_ribbon';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.giftwrap_ribbon_ribbon_id = $id";
				$notitle			= 0;
			break;
			case 'prodcat':// case of product category
				$table				= 'images_product_category';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.product_categories_category_id = $id";
				$notitle			= 0;
			break;

			case 'prodshop':// case of product category
				$table				= 'images_shopbybrand';
				$orderby			= 'image_order';
				$main_condition		= " AND b.product_shopbybrand_shopbrand_id = $id";
				$notitle			= 0;
			break;

			case 'prodtab':// case of product tab
				$table				= 'images_product_tab';
				$orderby			= ($force_order=='')?'image_order':$force_order;
				$main_condition		= " AND b.product_tabs_tab_id = $id";
				$notitle			= 0;
			break;
			case 'paytype':// case of payment types
				$table				= 'payment_types_forsites';
				$orderby			= 'paytype_order';
				$main_condition		= " AND b.paytype_forsites_id = $id";
				$notitle			= 1;
			break;
		};
		if ($orderby) // cat order by only if it exists
			$orderby = "ORDER BY $orderby";
		if ($notitle) // Decide whether image title should be included in the field list of query
			$field_list = "a.image_id,$field";
		else
			$field_list = "a.image_id,".$field.",b.image_title";

		 $sql = "SELECT $field_list,image_extralargepath,image_bigpath,image_thumbpath,image_iconpath   
				FROM
					images a,$table b
				WHERE
					a.image_id=b.images_image_id
					$main_condition
					$add_condition
				$orderby ";
			
		if ($limit!=0)
			$sql .=" LIMIT $limit";
	
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			while($row = $db->fetch_array($ret))
			{
				$ret_arr[]		= $row;
			}
		}
		return $ret_arr;
	}
	
	function get_imagelist_combination($comb_id,$field='image_thumbpath',$exclude_id=0,$limit=0)
	{
		global $ecom_siteid,$db,$ecom_hostname;
		global $ecom_selfhttp;
		$ret_arr		= array();
		if($exclude_id!=0)
			$add_condition = " AND a.image_id <> $exclude_id ";
		
		$field_list = "a.image_id,".$field.",b.image_title";
		$sql = "SELECT $field_list,image_extralargepath,image_bigpath,image_thumbpath,image_iconpath   
				FROM
					images a,images_variable_combination b
				WHERE
					a.image_id=b.images_image_id
					AND b.comb_id = $comb_id
					$add_condition
				ORDER BY image_order ";
			
		if ($limit!=0)
			$sql .=" LIMIT $limit";
	
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			while($row = $db->fetch_array($ret))
			{
				$ret_arr[]		= $row;
			}
		}
		return $ret_arr;
	}

	// Function to show the no image base of the value of parameter mod
	function get_noimage($mod='prod',$size='small')
	{ 
		global $ecom_hostname;
		global $ecom_selfhttp;
		$bigno 	= url_site_image('no_big_image.gif',1);
		$iconno	= url_site_image('no-image_icon.gif',1);
		$smallno = url_site_image('no_small_image.gif',1);
		switch ($mod)
		{
			case 'prod':
			    if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'combo':// case of combo images
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'bow':// case of gift wrap bows
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'card':// case of gift wrap card
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'paper':// case of gift wrap paper
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'ribbon':// case of gift wrap ribbon
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'prodcat':// case of product category
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'prodshop':// case of product category
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
			case 'prodtab':// case of product tab
				 if($size=='big')
					return  $bigno;
				else if ($size=='image_iconpath')
					return $iconno;
				else
					return  $smallno;
			break;
		};
	}
	// Function to get the default image type to be passed to the image list function from various functions.
	function get_default_imagetype($typ)
	{
		global $db,$ecom_siteid,$Settings_arr;
		global $ecom_selfhttp;
		switch($typ)
		{
			case 'combshelf': 	// Component Shelf
				$ret_type = ($Settings_arr['compshelf_showimagetype']=='Default')?'Thumb':$Settings_arr['compshelf_showimagetype'];
			break;
			case 'midshelf':	// Middle Shelf
				$ret_type = ($Settings_arr['midshelf_showimagetype']=='Default')?'Thumb':$Settings_arr['midshelf_showimagetype'];
			break;
			case 'recent':		// Recently viewed products
				$ret_type = ($Settings_arr['recent_showimagetype']=='Default')?'Icon':$Settings_arr['recent_showimagetype'];
			break;
			case 'search':		// Search Result
				$ret_type = $Settings_arr['search_showimagetype'];
				$ret_type = ($Settings_arr['search_showimagetype']=='Default')?'Thumb':$Settings_arr['search_showimagetype'];
			break;
			case 'search_cat':		// Search Result
				$ret_type = $Settings_arr['search_catshowimagetype'];
				$ret_type = ($Settings_arr['search_catshowimagetype']=='Default')?'Icon':$Settings_arr['search_catshowimagetype'];
			break;
			case 'category':	// Category
				$ret_type = ($Settings_arr['category_showimagetype']=='Default')?'Category':$Settings_arr['category_showimagetype'];
			break;
			case 'subcategory':	// Subcategory
				$ret_type = ($Settings_arr['subcategory_showimagetype']=='Default')?'Medium':$Settings_arr['subcategory_showimagetype'];
			break;
			case 'prodcat':// case of product category
				$ret_type = ($Settings_arr['categoryprod_showimagetype']=='Default')?'Thumb':$Settings_arr['categoryprod_showimagetype'];
			break;
			case 'proddet':// case of product details
				$ret_type = ($Settings_arr['productdetail_showimagetype']=='Default')?'Big':$Settings_arr['productdetail_showimagetype'];
			break;
			case 'link_prod':// case of linked products
				$ret_type = ($Settings_arr['linkedprod_showimagetype']=='Default')?'Thumb':$Settings_arr['linkedprod_showimagetype'];
			break;
			case 'midcombo':// case of middle combo
				$ret_type = ($Settings_arr['midcombo_showimagetype']=='Default')?'Medium':$Settings_arr['midcombo_showimagetype'];
			break;
			case 'shop':// case of shops
				$ret_type = ($Settings_arr['shop_showimagetype']=='Default')?'Big':$Settings_arr['shop_showimagetype'];
			break;
			case 'subshop':// case of subshops
				$ret_type = ($Settings_arr['subshop_showimagetype']=='Default')?'Medium':$Settings_arr['subshop_showimagetype'];
			break;
			case 'subshop_prod':// case of subshop products
				$ret_type = ($Settings_arr['shopprod_showimagetype']=='Default')?'Thumb':$Settings_arr['shopprod_showimagetype'];
			break;
			case 'fav_cat':// case of customer favourite categories
				$ret_type = ($Settings_arr['myfavouritecategory_showimagetype']=='Default')?'Thumb':$Settings_arr['myfavouritecategory_showimagetype'];
			break;
			case 'fav_prod':// case of customer favourite products
				$ret_type = ($Settings_arr['myfavouriteproduct_showimagetype']=='Default')?'Thumb':$Settings_arr['myfavouriteproduct_showimagetype'];
			break;
			case 'proddet_thumb':// case of customer favourite products
				$ret_type = ($Settings_arr['productdetail_moreimages_showimagetype']=='Default')?'Icon':$Settings_arr['productdetail_moreimages_showimagetype'];
			break;
			case 'cart': // case of view cart
				$ret_type = ($Settings_arr['product_cart_showimagetype']=='Default')?'Icon':$Settings_arr['product_cart_showimagetype'];
			break;
			case 'enquiry': // case of product enquiry
				$ret_type = ($Settings_arr['product_enquiry_showimagetype']=='Default')?'Icon':$Settings_arr['product_enquiry_showimagetype'];
			break;
			case 'wishlist': // case of wishlist
				$ret_type = ($Settings_arr['product_wishlist_showimagetype']=='Default')?'Icon':$Settings_arr['product_wishlist_showimagetype'];
			break;
		};
		switch($ret_type)
		{
			case 'Icon';
				$ret_type = 'image_iconpath';
			break;
			case 'Thumb';
				$ret_type = 'image_thumbpath';
			break;
			case 'Medium':
				$ret_type = 'image_thumbcategorypath';
			break;
			case 'Category':
				$ret_type = 'image_bigcategorypath';
			break;
			case 'Big':
				$ret_type = 'image_bigpath';
			break;
			case 'Extra':
				$ret_type = 'image_extralargepath';
			break;
			case 'Gallery':
				$ret_type = 'image_gallerythumbpath';
			break;
			default:
				$ret_type = 'image_thumbpath';
			break;
		};
		return $ret_type;
	}
	// Function to display a given image with a title
	function show_image($img,$alt,$title,$class='',$id='',$ret=0)
	{ 
		global $ecom_siteid,$microdata_img;
		global $ecom_selfhttp;
		$sr_arr = array('"',"'");
		$rp_arr = array('','');
		if($alt!='')
			$alt 	= stripslashes(str_replace($sr_arr,$rp_arr,$alt));
		if($title!='')
			$title 	= stripslashes(str_replace($sr_arr,$rp_arr,$title));
		if ($alt=='')
			$alt = $title;
		if ($title=='')
			$title = $alt;
		if($class!='')
			$class = 'class="'.$class.'"';
			/*if($ecom_siteid==61 || $ecom_siteid==95)
			{
			   $style = 'style="width:auto;border:0"';
			}
			else*/
			{
		       $style = 'style="border:0"';
		    }
		    if($id=='featsp_dental')
		    {
				$style = '';
			}
			else
			{
				if($id!='')
				$id = 'id="'.$id.'"';
			}
                if($ret==0)
                {
	?>
		<img <?php echo $microdata_img?> src="<?php echo $img ?>" alt="<?php echo $alt?>" title="<?php echo $title?>" <?php echo $class?> <?php echo $id?> <?php echo $style?>/>
	<?php
                }
                elseif($ret==1)
                    return '<img '.$microdata_img.' src="'.$img.'" alt="'.$alt.'" title="'.$title.'" '.$style.' '.$class.' '.$id.'/>';
	}
	//to generate category tree
	function generate_category_tree($id,$level=0,$all=false,$only_cat=false,$select=false)
		{
			global $db,$ecom_siteid;
			global $ecom_selfhttp;
			if($id == 0) {
				if(!$only_cat) {
					if($all)  $categories[0] = '--- All ---';
					elseif($select) $categories[0] = '--- Select ---';
					else $categories[0] = '--- Root Level ---';
				}
			}
			$query = "select category_id,category_name 
								from 
									product_categories 
								where 
									parent_id=$id 
									AND sites_site_id=$ecom_siteid 
									AND category_hide=0 
								ORDER BY 
									category_name";
			$result = $db->query($query);
			while(list($id,$name) = $db->fetch_array($result))
			{
				$space = '';
				for($i=0; $i<=$level-1; $i++) {
					$space .= '--';
				}
				$categories[$id] = $space.stripslashes($name);
				$subcategories = generate_category_tree($id,$level+1);
				if(is_array($subcategories))
				{
					$space = '';
					for($i=0; $i<=$level-1; $i++) {
						$space .= '--';
					}
					foreach($subcategories as $k => $v)
					{
						$categories[$k] = $space.$v;
					}
				}
			}
			return $categories;
		}
		//End category tree
	function generate_subcategory_tree($id)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$query = "select category_id,category_name 
							from 
								product_categories 
							where 
								parent_id=$id 
								AND sites_site_id=$ecom_siteid 
								AND category_hide=0 
							ORDER BY 
								category_name";
		$result = $db->query($query);
		while(list($id,$name) = $db->fetch_array($result))
		{
			$categories[$id] = stripslashes($name);
			$subcategories = generate_subcategory_tree($id);
			if(is_array($subcategories))
			{
				foreach($subcategories as $k => $v)
				{
					$categories[$k] = $space.$v;
				}
			}
		}
			return $categories;
	}

	//End Shop Tree
	function generate_subshop_tree($id)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$query = "select shopbrand_id,shopbrand_name 
						from 
							product_shopbybrand 
						where 
							shopbrand_parent_id=$id 
							AND sites_site_id=$ecom_siteid 
							AND shopbrand_hide=0 
						ORDER BY 
							shopbrand_name";
		$result = $db->query($query);
		while(list($id,$name) = $db->fetch_array($result))
		{
			$shops[$id] = stripslashes($name);
			$subshops = generate_subshop_tree($id);
			if(is_array($subshops))
			{
				foreach($subshops as $k => $v)
				{
					$shops[$k] = $space.$v;
				}
			}
		}
			return $shops;
	}

	// ** Check whether the product under a given category have image assign to them
	function find_ImageAssignedProduct($catid=0,$shopid=0)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		if ($catid!=0)
		{
			$sql_prods = "SELECT products_product_id
							FROM
								product_category_map a, products b 
							WHERE
								a.products_product_id = b.product_id 
								AND b.product_hide= 'N' 
								AND product_categories_category_id = $catid ORDER BY product_order";
		}					
		elseif($shopid!=0)
		{
			$sql_prods = "SELECT products_product_id
					FROM
						product_shopbybrand_product_map a, products b 
					WHERE
						a.products_product_id = b.product_id 
						AND b.product_hide = 'N' 
						AND product_shopbybrand_shopbrand_id = $shopid ORDER BY map_sortorder";

		}
		$ret_prods = $db->query($sql_prods);
		if ($db->num_rows($ret_prods))
		{
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				//** Check whether any image assigned to current product
				$sql_img = "SELECT id
							FROM
								images_product
							WHERE
								products_product_id = ".$row_prods['products_product_id']."
							LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					return $row_prods['products_product_id'];
				}
			}
		}
	}
	// ** Function to find the first product under current category which have image assigned to it
	function find_AnyProductWithImageUnderCategory($catid)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$prodid = find_ImageAssignedProduct($catid,0);// Calling the function to pick the id of product with image assigned
		if ($prodid)
			return $prodid;
		else
		{
			$sub_cat = generate_subcategory_tree($catid);
			if (is_array($sub_cat))
			{
				foreach($sub_cat as $k=>$v)
				{
					$prodid = find_ImageAssignedProduct($k,0);
					if ($prodid)
						return $prodid;
				}
			}
		}
	}

	// ** Function to find the first product under current category which have image assigned to it
	function find_AnyProductWithImageUnderShop($shopid)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$prodid = find_ImageAssignedProduct(0,$shopid);// Calling the function to pick the id of product with image assigned
		if ($prodid)
			return $prodid;
		else
		{
			$sub_shop = generate_subshop_tree($shopid);
			if (is_array($sub_shop))
			{
				foreach($sub_shop as $k=>$v)
				{
					$prodid = find_ImageAssignedProduct(0,$k);
					if ($prodid)
						return $prodid;
				}
			}
		}
	}

	// Function which check whether there exist variable stock for atleast one combination
	function get_atleastone_variablestock($prodid)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$stock = 0;
		// Check whether any of the variable combination have stock
		$sql_var = "SELECT web_stock
							FROM
								product_variable_combination_stock
							WHERE
								products_product_id = $prodid
								AND web_stock > 0
							LIMIT
								1";
		$ret_var = $db->query($sql_var);
		if($db->num_rows($ret_var))
		{
			$row_var 	= $db->fetch_array($ret_var);
			$stock		= $row_var['web_stock'];
		}
		return $stock;
	}
	// ** Function to get the details to be shown for stock in product details page
	function get_stockdetails($prodid,$compare='')
	{
		global $db,$ecom_siteid,$ecom_hostname,$PriceSettings_arr,$Captions_arr,$Settings_arr;
		global $ecom_selfhttp;
		$Captions_arr['COMMON']	= getCaptions('COMMON');
		$stockmsg 				= $Captions_arr['COMMON']['COMMON_STOCK'];
		$instockmsg 			= $Captions_arr['COMMON']['COMMON_STOCK_INSTOCK'];
		// Get the details of current product from main product table
		$sql_prod = "SELECT product_actualstock,product_webstock,product_preorder_allowed,product_total_preorder_allowed,
							product_instock_date,product_variablestock_allowed 
					FROM
						products
					WHERE
						product_id=$prodid
						AND sites_site_id=$ecom_siteid";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			if($row_prod['product_preorder_allowed']!='Y') // if preorder is not ticked
			{

				if($row_prod['product_variablestock_allowed']=='N')
				{
					if ($row_prod['product_webstock']>0)
					{
						$stock = $stockmsg.' '.$row_prod['product_webstock'];
						if($compare!='') {
							$stock = $instockmsg;
						}
					}
					else
					{
						$stock = $Captions_arr['COMMON']['COMMON_STOCK_NOSTOCK'];
					}	
				}
				elseif($row_prod['product_variablestock_allowed']=='Y')
				{
					$sql_comb_stk = "SELECT sum(web_stock)  act_stk
												FROM 
													product_variable_combination_stock 
												WHERE 
													products_product_id=".$prodid." 
													AND web_stock>0 ";
					$ret_comb_stk = $db->query($sql_comb_stk);
					if ($db->num_rows($ret_comb_stk)>0)
					{
						$row_comb_stk = $db->fetch_array($ret_comb_stk);
						if($row_comb_stk['act_stk']>0)
						{
							$stock = $stockmsg.' '.$row_comb_stk['act_stk'];
							if($compare!='')
							{
								$stock = $instockmsg;
							}	
						}
						else
							$stock = $Captions_arr['COMMON']['COMMON_STOCK_NOSTOCK'];	
					}
					else
						$stock = $Captions_arr['COMMON']['COMMON_STOCK_NOSTOCK'];
				}
				
				if ($Settings_arr['product_show_instock']!=1)
					$stock = '';
					
			}
			else // case if preorder is ticked
			{
				if($row_prod['product_total_preorder_allowed']>0)
				{
					// splitting the date to show it in dd/mm/yyyyy format
					$dates 	= explode("-",$row_prod['product_instock_date']);
					$stock 	= $PriceSettings_arr['price_availabledateprefix'].' '.$dates[2].'-'.getMonthName($dates[1]).'-'.$dates[0].' '.$PriceSettings_arr['price_availabledatesuffix'];
					//if($compare!='') {
						//$stock = $PriceSettings_arr['price_availabledateprefix'].' '.$dates[2].'-'.getMonthName($dates[1]).'-'.$dates[0].' '.$PriceSettings_arr['price_availabledatesuffix'];
					//}
				}
				else
				{
					if($row_prod['product_variablestock_allowed']=='N')
					{	
						if ($row_prod['web_stock']==0)
							$stock = $Captions_arr['COMMON']['COMMON_STOCK_NOSTOCK'];
					}
					elseif($row_prod['product_variablestock_allowed']=='Y')
					{
							$sql_comb_stk = "SELECT sum(web_stock)  act_stk
												FROM 
													product_variable_combination_stock 
												WHERE 
													products_product_id=".$prodid." 
													AND web_stock>0 ";
							$ret_comb_stk = $db->query($sql_comb_stk);	
							$row_comb_stk = $db->fetch_array($ret_comb_stk);	
							if($row_comb_stk['act_stk']==0)
								$stock = $Captions_arr['COMMON']['COMMON_STOCK_NOSTOCK'];
					}	
				}
				if ($Settings_arr['product_hide_preorder_msg']!=1)
				{
					$stock = '';
				}	
			}
		}
		return $stock;
	}

	// ** Function to get the name of month
	function getMonthName($num,$type='small')
	{
		global $ecom_selfhttp;
		if ($num<10 and strlen($num)>1)
			$num = substr($num,-1,1);
		$subindex = ($type=='small')?0:1;
		$month_arr = array
							(
								1	=>	array('Jan','January'),
								2	=>	array('Feb','February'),
								3	=>	array('Mar','March'),
								4	=>	array('Apr','April'),
								5	=>	array('May','May'),
								6	=>	array('Jun','June'),
								7	=>	array('Jul','July'),
								8	=>	array('Aug','August'),
								9	=>	array('Sep','September'),
								10	=>	array('Oct','October'),
								11	=>	array('Nov','November'),
								12	=>	array('Dec','December'),
							);
		return $month_name = $month_arr[$num][$subindex];
	}
	// Function to show the more info link
	function show_moreinfo($prod_arr,$class)
	{
		global $db,$ecom_siteid,$ecom_hostname,$Captions_arr;
		global $ecom_selfhttp;
		$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
		$caption	= $Captions_arr['COMMON']['MORE_INFO']
	?>
		<a href="<?php echo $link?>" title="<?php echo $prod_arr['product_name']?>" class="<?php echo $class?>"><?php echo $caption?></a>
	<?php
	}

	// ** Function to check whether sufficient stock is available for the current product.
	function check_stock_available($prodid,$var_arr)
	{ 
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
							product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,
							product_variablecombocommon_image_allowed  
						FROM
							products
						WHERE
							product_id=$prodid
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			$ret_data['preorder_allowed'] 		= $row_prod['product_preorder_allowed'];
			$ret_data['totalpreorder_allowed'] 	= $row_prod['product_total_preorder_allowed'];
			$ret_data['instock_date']		 	= $row_prod['product_instock_date'];
			// Make the decision whether preorder is available and it is valid
			$stock_check = true;
			if ($ret_data['preorder_allowed']=='N') // case if preorder is set to N
				$stock_check = false; // preorder not allowed
			elseif($ret_data['totalpreorder_allowed']==0) // case if preorder is set to Y but totalpreorder = 0
				$stock_check = false; // preorder not allowed
			$ret_data['inpreorder']		= $stock_check;

			if ($row_prod['product_variablestock_allowed'] == 'Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y') // Case if variable stock is maintained
			{
				if (count($var_arr))
				{
					$varids = array();
					foreach ($var_arr as $k=>$v)
					{
						// Check whether the variable is a check box or a drop down box
						$sql_check = "SELECT var_id
										FROM
											product_variables
										WHERE
											var_id=$k
											AND var_value_exists = 1
										LIMIT
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check 	= $db->fetch_array($ret_check);
							$varids[] 	= $k; // populate only the id's of variables which have values to the array
						}
					}
					/*$combid_arr = array(0);
					// Find the various combinations available for current product
					$sql_comb = "SELECT  comb_id, product_variables_var_id , product_variable_data_var_value_id , products_product_id  
									FROM
										product_variable_combination_stock_details 
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{

						while ($row_comb = $db->fetch_array($ret_comb))
						{
							//Check whether variable is still active 
							$sql_varchec = "SELECT var_id 
												FROM 
													product_variables 
												WHERE 
													var_hide =  0 
													AND var_id = ".$row_comb['product_variables_var_id']." 
													AND products_product_id = $prodid 
												LIMIT 
													1";
						
											
						}
					}*/	
					
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id,web_stock
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{

						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{  
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}					

							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{ 
								if ($row_prod['product_variablestock_allowed'] == 'Y' ) // Case if variable stock is maintained
									$stock 				= $row_comb['web_stock'];
								else
									$stock = $row_prod['product_webstock'];
									
								$ret_data['stock']	= $stock;
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
			}
			else // case if variable stock is not maintained
			{
				$stock 				= $row_prod['product_webstock'];
				$ret_data['stock']	= $stock;
				$ret_data['combid']	= 0;
				return $ret_data; // return from function as soon as the combination found
			}
			if ($row_prod['product_variablestock_allowed'] == 'N' ) // Case if variable stock is not maintained. Will reach here only if variable stock not maintained by variable price or var image exists and no combination found matching the current one
				$stock = $row_prod['product_webstock'];
		}
		$ret_data['stock']			= $stock;
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}
	// Function to check whether the current product already exists in the cart
	function Is_Item_already_Exists($var_arr,$varmsg_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$session_id = Get_session_Id_from();
		$sql_check 	= "SELECT cart_id,cart_promotional_code_id  
									FROM
										cart
									WHERE
										sites_site_id=$ecom_siteid
										AND products_product_id=".$_REQUEST['fproduct_id']."
										AND session_id='".$session_id."' AND cart_promotional_code_id = 0";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0) // case if product does not exists in cart
		{
			$cart_check_arr[$row_check['cart_id']] = -1;
		}
		else // case product exists in cart. So further checking is required to see if it is to be added as a new item or not
		{
			$cart_check_arr 	= array();
			while($row_check 	= $db->fetch_array($ret_check))
			{
				$cart_check_arr[$row_check['cart_id']] = 0;
				// ====================================================
				// Check whether any variable exist for current product
				// ====================================================
				$sql_var = "SELECT var_id,var_value_id
								FROM
									cart_variables
								WHERE
									cart_id = ".$row_check['cart_id'];
				$ret_var = $db->query($sql_var);
				if ($db->num_rows($ret_var))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_var)==count($var_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							if ($row_var['var_value_id']!=$var_arr[$row_var['var_id']])
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}

				// ======================================================
				// Check whether any variable message exists for product
				// ======================================================
				$sql_varmsg = "SELECT message_id,message_value
										FROM
											cart_messages
										WHERE
											cart_id = ".$row_check['cart_id'];
				$ret_varmsg = $db->query($sql_varmsg);
				if ($db->num_rows($ret_varmsg))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_varmsg)==count($varmsg_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_varmsg = $db->fetch_array($ret_varmsg))
						{
							if (strtolower($row_varmsg['message_value'])!=strtolower($varmsg_arr[$row_varmsg['message_id']]))
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}
			}
		}
		$returned_cartid = -1;
		foreach ($cart_check_arr as $k=>$v)
		{
			if ($v==0)
			{
				$returned_cartid = $k;
			}
		}
		// If reached upto here then the item already exists in cart. So return the cart id to the calling area
		return $returned_cartid;
	}
	//get the session id for the iphone and the other sites
    function Get_session_Id_from()
	{
	    global $iphone_session_id;
	    global $ecom_selfhttp;
	    if($iphone_session_id !='')
			$session_id_to = $iphone_session_id;
		else
			$session_id_to = session_id();	
		return $session_id_to;
	}
	// ** Writing the function to add item to cart
	function Add_Item_to_Cart($varN='var_',$varM='varmsg_',$ret=true,$prom_id=0)
	{   
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$PricePromise_addtocart,$from_iphone_app;
		global $ecom_selfhttp;
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		$session_id = Get_session_Id_from();
		// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		
		/* 15 Nov 2011 */
		// Check whether current product is in current website
		$sql_prcheck = "SELECT product_id 
							FROM 
								products 
							WHERE 
								product_id = '".$_REQUEST['fproduct_id']."' 
								AND product_hide='N' 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_prcheck = $db->query($sql_prcheck);
		if($db->num_rows($ret_prcheck)==0)
		{
			echo "Invalid Details";
			exit;
		}
		/* 15 Nov 2011 */
		
		// Check whether instock notification is allowed for this product
		$sql_note = "SELECT product_stock_notification_required,product_name,product_alloworder_notinstock    
									FROM 
										products 
									WHERE 
										product_id = ".$_REQUEST['fproduct_id']." 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
		$ret_note = $db->query($sql_note);
		if ($db->num_rows($ret_note))
		{
			$row_note = $db->fetch_array($ret_note);
			$stock_notification_req = $row_note['product_stock_notification_required'];
			$stock_prod_name		= stripslashes($row_note['product_name']);
			$always_add_to_cart	= ($row_note['product_alloworder_notinstock']=='Y')?true:false;
		} 
		
		/* 15 Nov 2011 */
		// Check how many variables in current product are set to have values
		$sql_valuecnt = "SELECT count(var_id) as varcnts  
							FROM 
								product_variables 
							WHERE 
								products_product_id = '".$_REQUEST['fproduct_id']."' 
								AND var_value_exists=1 
								AND var_hide=0";
		$ret_valuecnt = $db->query($sql_valuecnt);
		$row_valuecnt = $db->fetch_array($ret_valuecnt);
		$variable_value_cnt_check = $row_valuecnt['varcnts'];	
		$valid_value_exists_cnt	=0;
		/* 15 Nov 2011 */
		
		// Get the variable and variable messages set for this product to an array
		foreach ($_REQUEST as $k=>$v)
		{
			$var_nameLimit = strlen($varN);
			$var_messageLimit = strlen($varM);
			if (substr($k,0,$var_nameLimit) == $varN)
			{ 
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				if($v) // consider the variable only if value exists (done to avoid the case of incorrectly adding the checkbox variables in case of combo buy all
				{/* 15 Nov 2011 */
					
					// Check whether current variable is associated with current product itself
					$sql_checkvar = "SELECT var_id,var_value_exists  
										FROM 
											product_variables 
										WHERE 
											var_id = '".$curid[1]."' 
											AND var_hide = 0 
											AND products_product_id = '".$_REQUEST['fproduct_id']."' 
										LIMIT 
											1";
					$ret_checkvar = $db->query($sql_checkvar);
					if ($db->num_rows($ret_checkvar))
					{
						$row_checkvar = $db->fetch_array($ret_checkvar);
						if($row_checkvar['var_value_exists']==1)
						{
							// Check whether the given value is associated with current variable id
							$sql_validcheck = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = '".$curid[1]."' 
													AND var_value_id = '".trim($v)."' 
												LIMIT 
													1";
							$ret_validcheck = $db->query($sql_validcheck);							
							if($db->num_rows($ret_validcheck))
							{
								$var_arr[$curid[1]] 	= trim($v);	
								$valid_value_exists_cnt++;
							}
							else
							{
								echo "Sorry an Error Occured";
								exit;
							}
						}
						else // case if value does not exists and a non 0 or non integer value is given as value
						{
							$var_arr[$curid[1]] 	= 1;
						}
						
						
						//$var_arr[$curid[1]] 	= trim($v);	
					}						
				}///* 15 Nov 2011 */	
			}
			elseif (substr($k,0,$var_messageLimit) == $varM)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				/* 15 Nov 2011 */
				// Check whether current variable is associated with current product itself
				$sql_checkvar = "SELECT message_id  
									FROM 
										product_variable_messages  
									WHERE 
										message_id = '".$curid[1]."' 
										AND	message_hide = 0 
										AND products_product_id = '".$_REQUEST['fproduct_id']."' 
									LIMIT 
										1";
				$ret_checkvar = $db->query($sql_checkvar);
				if ($db->num_rows($ret_checkvar))
				{
					$varmsg_arr[$curid[1]] 	= trim($v);
				}
				/* 15 Nov 2011 */	
			}
		}
		/* 15 Nov 2011 */	
		if($variable_value_cnt_check!= $valid_value_exists_cnt)
		{
			echo "Sorry an error occured";
			exit;
		}
		/* 15 Nov 2011 */	
		
		$prom_id			= (trim($_REQUEST['prom_id']))?trim($_REQUEST['prom_id']):0;
		
		if($prom_id==0) // case if prom code does not exists
		{ 
			$prom_comb_arr 		= get_combination_id($_REQUEST['fproduct_id'],$var_arr);
			$prom_cur_comb_id 	= $prom_comb_arr['combid'];
			// Check whether there exists same product with cart_prom_id != 0 
			$sql_cartcheck = "SELECT cart_id 
								FROM 
									cart 
								WHERE 
									session_id = '$session_id'  
									AND sites_site_id = $ecom_siteid 
									AND products_product_id = ".$_REQUEST['fproduct_id']." 
									AND cart_comb_id = $prom_cur_comb_id 
									AND cart_prom_id != 0  
								LIMIT 
									1";
			
			$ret_cartcheck = $db->query($sql_cartcheck);
			if($db->num_rows($ret_cartcheck))
			{
					$show_msg = 'CART_PRICE_PROMISE_SAME_ITEM_EXISTS';
					echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
					exit;
			}
		}
		// Calling the function to check whether the item is already there in cart.
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_cartid 	= Is_Item_already_Exists($var_arr,$varmsg_arr);
		// Check the stock details here
		$stock_arr		= check_stock_available($_REQUEST['fproduct_id'],$var_arr);
	
		// Make the decision whether stock checking is required
		 if($stock_arr['inpreorder']==false)
		 {
		 $stock_check 	= 0;
		 }
		$stock			= $remstock = $stock_arr['stock']; // Setting the stock and remaining stock variables to the same value
		$combid			= $stock_arr['combid'];
		$qty			= ($_REQUEST['qty'])?$_REQUEST['qty']:1;
		if($qty<0 or !is_numeric($qty))
			$qty = 1;
		if($always_add_to_cart) // if always allow to add to cart then making the stock equal to qty so that the following section works simillar to there is sufficient stock
			$stock 		= $qty;

		$cartstock		= 0;
		// If stock exists and also item already exists in cart
		if ($stock>0 and $ret_cartid!=-1)
		{
			// Get the qty already in cart
			$sql_cart = "SELECT cart_qty
							FROM
								cart
							WHERE
								cart_id =".$ret_cartid." LIMIT 1";
			$ret_cart = $db->query($sql_cart);
			if ($db->num_rows($ret_cart))
			{
				$row_cart 	= $db->fetch_array($ret_cart);
				$cartstock	= $row_cart['cart_qty'];
				if (!$stock_check)
				{
				  if(!$always_add_to_cart)	
				  {
					if($stock<($qty+$cartstock))
					{
						$qty	= $stock - $cartstock;
						// Give approprate javascript message.
						/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
						$show_msg	= 'CART_STOCK_INSUFF_ADJ_QTY';
					}
				  }	
				}
			}
		}
		if($from_iphone_app  == true) //Tocheck whether it is coming from iphone application
		{
			$stock_notification_req = 'N';
		}	
		if ($ret_cartid==-1) // Case item does not exists in cart. So need to insert the details
		{    
				if (!$stock_check)
				{
					if($stock<$qty)
					{
						$qty	= $stock;
						// Give approprate javascript message.
						if ($qty==0 and $stock_notification_req=='Y') // If the stock is 0 and also stock notification is allowed for this product then.
						{  
							echo "<form method='post' action ='".url_product($_REQUEST['fproduct_id'],$stock_prod_name,1)."' name='frm_subcart'>
								<input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/>
								<div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div>
								<input type='hidden' name='for_notification' value='1'/>
								<input type='hidden' name='pass_combid' value='".$combid."'/>
								<input type='hidden' name='fproduct_id' value='".$_REQUEST['fproduct_id']."'/>
								";
							foreach ($_REQUEST as $k=>$v)
							{
								if(substr($k,0,4)=='var_' or substr($k,0,7)=='varmsg_')
									echo "<input type='hidden' name='".$k."' value='".$v."'/>";		
							}			
							echo "</form>";
							echo "<script type='text/javascript'>document.frm_subcart.submit();</script>";
							exit;
						}
						else
						{
							/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
							$show_msg	= 'CART_STOCK_INSUFF_ADJ_QTY';
						}	
					}
				}
				if($qty)
				{ 
					// Make an entry to the cart and its related tables
					$insert_array							= array();
					$insert_array['sites_site_id']			= $ecom_siteid;
					$insert_array['session_id']				= $session_id;
					$insert_array['products_product_id']	= $_REQUEST['fproduct_id'];
					$insert_array['cart_comb_id']			= $combid;
					$insert_array['cart_qty']				= $qty;
					$insert_array['cart_preorder']			= $stock_check;
					$insert_array['cart_addedon']			= 'now()';
					$insert_array['cart_prom_id']			= $prom_id;
					$db->insert_from_array($insert_array,'cart');
					$insert_cartid 							= $db->insert_id();

					// Making entries to the cart_variables table if any variables exists
					if (count($var_arr))
					{
						// Inserting the variable details cart_variables table
						foreach ($var_arr as $k=>$v)
						{
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['var_id']			= $k;
							$insert_array['var_value_id']	= $v;
							$db->insert_from_array($insert_array,'cart_variables');
						}
					} 
					// Making entries to the cart_messages table if any messages exists
					if (count($varmsg_arr))
					{
						foreach ($varmsg_arr as $k=>$v)
						{
							// Get the type of current message from product_variable_messages table
							$sql_msg = "SELECT message_type
										FROM
											product_variable_messages
										WHERE
											message_id = $k
										LIMIT
											1";
							$ret_msg = $db->query($sql_msg);
							if ($db->num_rows($ret_msg))
							{
								$row_msg  = $db->fetch_array($ret_msg);
								$msg_type = $row_msg['message_type'];
							}
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['message_id']		= $k;
							$insert_array['message_value']	= add_slash($v);
							$insert_array['message_type']	= $msg_type;
							$db->insert_from_array($insert_array,'cart_messages');
						}
					}
				}	
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]);	// Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				// Redirecting user to the cart page
				/*echo "<script type='text/javascript'>window.location = 'http://$ecom_hostname/cart.html';</script>";*/
				if($from_iphone_app==false)
				{
					if($ret==true)
					{
						echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						exit;
					}
			    }
			    else
			    { 
				      return $show_msg;
				}	
		}
		else // Case item already exists in cart. So need to increase the qty
		{
				$sql_update = "UPDATE cart
								SET
									cart_qty = cart_qty + $qty
								WHERE
									cart_id=$ret_cartid
								LIMIT
									1";
				$db->query($sql_update);
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				if($from_iphone_app==false)
				{
					if($ret==true)
					{
						// Redirecting user to the cart page
						echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						exit;
					}
				}
				 else
			    { 
				      return $show_msg;
				}	
		}

	}
	function Remove_freeproduct_cart($prom_id)
	{
			global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$PricePromise_addtocart,$from_iphone_app;
			global $ecom_selfhttp;
			//echo "here".$prom_id;
			$session_id = Get_session_Id_from();
			if($prom_id==0 OR $prom_id=='')
			{
			    $sql_prom = "SELECT a.promotionalcode_id FROM cart_supportdetails a,promotional_code b  WHERE a.session_id = '".$session_id."' AND a.sites_site_id = ".$ecom_siteid." AND a.promotionalcode_id = b.code_id AND b.code_type = 'freeproduct' LIMIT 1"; 
			    $ret_prom = $db->query($sql_prom);
			    if($db->num_rows($ret_prom)>0)
			    {
					$row_prom = $db->fetch_array($ret_prom);
					$del_promid = $row_prom['promotionalcode_id'];
					$sel_cartid  =  "SELECT cart_id FROM cart WHERE session_id = '$session_id' AND cart_promotional_code_id = $del_promid  ";
					$ret_cartid  = $db->query($sel_cartid);
					 if($db->num_rows($ret_prom)>0)
					 {
						while($row_cartid = $db->fetch_array($ret_cartid))
						{
						$cartid     = $row_cartid['cart_id'];
						$del_sql = "DELETE FROM cart WHERE cart_id = ".$cartid." AND session_id = '$session_id' AND cart_promotional_code_id = $del_promid ";
						$db->query($del_sql);
						$del_sqlv = "DELETE FROM cart_variables WHERE cart_id = $cartid ";
						$db->query($del_sqlv);
						$del_sqlm = "DELETE FROM cart_messages  WHERE cart_id = $cartid ";
						$db->query($del_sqlm);
						}
	
					 }
					return;
				}
			}

	}
	function Add_Item_to_Cart_Subproducts($varN='var_',$varM='varmsg_',$ret=true,$prom_id=0,$maincart_id=0)
	{   
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$PricePromise_addtocart,$from_iphone_app;
		global $ecom_selfhttp;
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		$session_id = Get_session_Id_from();
		// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		
		/* 15 Nov 2011 */
		// Check whether current product is in current website
		$sql_prcheck = "SELECT product_id 
							FROM 
								products 
							WHERE 
								product_id = '".$_REQUEST['fproduct_id']."' 
								AND product_hide='N' 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_prcheck = $db->query($sql_prcheck);
		if($db->num_rows($ret_prcheck)==0)
		{
			echo "Invalid Details";
			exit;
		}
		/* 15 Nov 2011 */
		
		// Check whether instock notification is allowed for this product
		$sql_note = "SELECT product_stock_notification_required,product_name,product_alloworder_notinstock    
									FROM 
										products 
									WHERE 
										product_id = ".$_REQUEST['fproduct_id']." 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
		$ret_note = $db->query($sql_note);
		if ($db->num_rows($ret_note))
		{
			$row_note = $db->fetch_array($ret_note);
			$stock_notification_req = $row_note['product_stock_notification_required'];
			$stock_prod_name		= stripslashes($row_note['product_name']);
			$always_add_to_cart	= ($row_note['product_alloworder_notinstock']=='Y')?true:false;
		} 
		
		/* 15 Nov 2011 */
		// Check how many variables in current product are set to have values
		$sql_valuecnt = "SELECT count(var_id) as varcnts  
							FROM 
								product_variables 
							WHERE 
								products_product_id = '".$_REQUEST['fproduct_id']."' 
								AND var_value_exists=1 
								AND var_hide=0";
		$ret_valuecnt = $db->query($sql_valuecnt);
		$row_valuecnt = $db->fetch_array($ret_valuecnt);
		$variable_value_cnt_check = $row_valuecnt['varcnts'];	
		$valid_value_exists_cnt	=0;
		/* 15 Nov 2011 */
		
		// Get the variable and variable messages set for this product to an array
		foreach ($_REQUEST as $k=>$v)
		{
			$var_nameLimit = strlen($varN);
			$var_messageLimit = strlen($varM);
			if (substr($k,0,$var_nameLimit) == $varN)
			{ 
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				if($v) // consider the variable only if value exists (done to avoid the case of incorrectly adding the checkbox variables in case of combo buy all
				{/* 15 Nov 2011 */
					
					// Check whether current variable is associated with current product itself
					$sql_checkvar = "SELECT var_id,var_value_exists  
										FROM 
											product_variables 
										WHERE 
											var_id = '".$curid[1]."' 
											AND var_hide = 0 
											AND products_product_id = '".$_REQUEST['fproduct_id']."' 
										LIMIT 
											1";
					$ret_checkvar = $db->query($sql_checkvar);
					if ($db->num_rows($ret_checkvar))
					{
						$row_checkvar = $db->fetch_array($ret_checkvar);
						if($row_checkvar['var_value_exists']==1)
						{
							// Check whether the given value is associated with current variable id
							$sql_validcheck = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = '".$curid[1]."' 
													AND var_value_id = '".trim($v)."' 
												LIMIT 
													1";
							$ret_validcheck = $db->query($sql_validcheck);							
							if($db->num_rows($ret_validcheck))
							{
								$var_arr[$curid[1]] 	= trim($v);	
								$valid_value_exists_cnt++;
							}
							else
							{
								echo "Sorry an Error Occured";
								exit;
							}
						}
						else // case if value does not exists and a non 0 or non integer value is given as value
						{
							$var_arr[$curid[1]] 	= 1;
						}
						
						
						//$var_arr[$curid[1]] 	= trim($v);	
					}						
				}///* 15 Nov 2011 */	
			}
			elseif (substr($k,0,$var_messageLimit) == $varM)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				/* 15 Nov 2011 */
				// Check whether current variable is associated with current product itself
				$sql_checkvar = "SELECT message_id  
									FROM 
										product_variable_messages  
									WHERE 
										message_id = '".$curid[1]."' 
										AND	message_hide = 0 
										AND products_product_id = '".$_REQUEST['fproduct_id']."' 
									LIMIT 
										1";
				$ret_checkvar = $db->query($sql_checkvar);
				if ($db->num_rows($ret_checkvar))
				{
					$varmsg_arr[$curid[1]] 	= trim($v);
				}
				/* 15 Nov 2011 */	
			}
		}
		/* 15 Nov 2011 */	
		if($variable_value_cnt_check!= $valid_value_exists_cnt)
		{
			echo "Sorry an error occured";
			exit;
		}
		/* 15 Nov 2011 */	
		
		$prom_id			= (trim($_REQUEST['prom_id']))?trim($_REQUEST['prom_id']):0;
		
		if($prom_id==0) // case if prom code does not exists
		{ 
			$prom_comb_arr 		= get_combination_id($_REQUEST['fproduct_id'],$var_arr);
			$prom_cur_comb_id 	= $prom_comb_arr['combid'];
			// Check whether there exists same product with cart_prom_id != 0 
			$sql_cartcheck = "SELECT cart_id 
								FROM 
									cart 
								WHERE 
									session_id = '$session_id'  
									AND sites_site_id = $ecom_siteid 
									AND products_product_id = ".$_REQUEST['fproduct_id']." 
									AND cart_comb_id = $prom_cur_comb_id 
									AND cart_prom_id != 0  
								LIMIT 
									1";
			
			$ret_cartcheck = $db->query($sql_cartcheck);
			if($db->num_rows($ret_cartcheck))
			{
					$show_msg = 'CART_PRICE_PROMISE_SAME_ITEM_EXISTS';
					echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
					exit;
			}
		}
		// Calling the function to check whether the item is already there in cart.
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_cartid 	= Is_Item_already_Exists_Subproducts($var_arr,$varmsg_arr,$maincart_id);
		// Check the stock details here
		$stock_arr		= check_stock_available($_REQUEST['fproduct_id'],$var_arr);
	
		// Make the decision whether stock checking is required
		$stock_check 	= $stock_arr['inpreorder'];
		$stock			= $remstock = $stock_arr['stock']; // Setting the stock and remaining stock variables to the same value
		$combid			= $stock_arr['combid'];
		$qty			= ($_REQUEST['qty'])?$_REQUEST['qty']:1;
		if($qty<0 or !is_numeric($qty))
			$qty = 1;
		if($always_add_to_cart) // if always allow to add to cart then making the stock equal to qty so that the following section works simillar to there is sufficient stock
			$stock 		= $qty;

		$cartstock		= 0;
		// If stock exists and also item already exists in cart
		if ($stock>0 and $ret_cartid!=-1)
		{
			// Get the qty already in cart
			$sql_cart = "SELECT cart_qty
							FROM
								cart
							WHERE
								cart_id =".$ret_cartid." LIMIT 1";
			$ret_cart = $db->query($sql_cart);
			if ($db->num_rows($ret_cart))
			{
				$row_cart 	= $db->fetch_array($ret_cart);
				$cartstock	= $row_cart['cart_qty'];
				if (!$stock_check)
				{
				  if(!$always_add_to_cart)	
				  {
					if($stock<($qty+$cartstock))
					{
						$qty	= $stock - $cartstock;
						// Give approprate javascript message.
						/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
						$show_msg	= 'CART_STOCK_INSUFF_ADJ_QTY';
					}
				  }	
				}
			}
		}
		if($from_iphone_app  == true) //Tocheck whether it is coming from iphone application
		{
			$stock_notification_req = 'N';
		}	
		if ($ret_cartid==-1) // Case item does not exists in cart. So need to insert the details
		{    
				if (!$stock_check)
				{
					if($stock<$qty)
					{
						$qty	= $stock;
						// Give approprate javascript message.
						if ($qty==0 and $stock_notification_req=='Y') // If the stock is 0 and also stock notification is allowed for this product then.
						{  
							echo "<form method='post' action ='".url_product($_REQUEST['fproduct_id'],$stock_prod_name,1)."' name='frm_subcart'>
								<input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/>
								<div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div>
								<input type='hidden' name='for_notification' value='1'/>
								<input type='hidden' name='pass_combid' value='".$combid."'/>
								<input type='hidden' name='fproduct_id' value='".$_REQUEST['fproduct_id']."'/>
								";
							foreach ($_REQUEST as $k=>$v)
							{
								if(substr($k,0,4)=='var_' or substr($k,0,7)=='varmsg_')
									echo "<input type='hidden' name='".$k."' value='".$v."'/>";		
							}			
							echo "</form>";
							echo "<script type='text/javascript'>document.frm_subcart.submit();</script>";
							exit;
						}
						else
						{
							/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
							$show_msg	= 'CART_STOCK_INSUFF_ADJ_QTY';
						}	
					}
				}
				if($qty)
				{ 
					// Make an entry to the cart and its related tables
					$insert_array							= array();
					$insert_array['sites_site_id']			= $ecom_siteid;
					$insert_array['session_id']				= $session_id;
					$insert_array['products_product_id']	= $_REQUEST['fproduct_id'];
					$insert_array['cart_comb_id']			= $combid;
					$insert_array['cart_qty']				= $qty;
					$insert_array['cart_preorder']			= $stock_check;
					$insert_array['cart_addedon']			= 'now()';
					$insert_array['cart_prom_id']			= $prom_id;
					$insert_array['cart_main_cart_id']		= $maincart_id;
					
					$db->insert_from_array($insert_array,'cart');
					$insert_cartid 							= $db->insert_id();

					// Making entries to the cart_variables table if any variables exists
					if (count($var_arr))
					{
						// Inserting the variable details cart_variables table
						foreach ($var_arr as $k=>$v)
						{
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['var_id']			= $k;
							$insert_array['var_value_id']	= $v;
							$db->insert_from_array($insert_array,'cart_variables');
						}
					} 
					// Making entries to the cart_messages table if any messages exists
					if (count($varmsg_arr))
					{
						foreach ($varmsg_arr as $k=>$v)
						{
							// Get the type of current message from product_variable_messages table
							$sql_msg = "SELECT message_type
										FROM
											product_variable_messages
										WHERE
											message_id = $k
										LIMIT
											1";
							$ret_msg = $db->query($sql_msg);
							if ($db->num_rows($ret_msg))
							{
								$row_msg  = $db->fetch_array($ret_msg);
								$msg_type = $row_msg['message_type'];
							}
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['message_id']		= $k;
							$insert_array['message_value']	= add_slash($v);
							$insert_array['message_type']	= $msg_type;
							$db->insert_from_array($insert_array,'cart_messages');
						}
					}
				}	
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]);	// Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				// Redirecting user to the cart page
				/*echo "<script type='text/javascript'>window.location = 'http://$ecom_hostname/cart.html';</script>";*/
				if($from_iphone_app==false)
				{
					if($ret==true)
					{
						echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						exit;
					}
			    }
			    else
			    { 
					$ret_arrs['msg'] 	= $show_msg;
					$ret_arrs['cartid'] = $insert_cartid;
				    //return $show_msg;
				    return $ret_arrs;
				}	
		}
		else // Case item already exists in cart. So need to increase the qty
		{
				$sql_update = "UPDATE cart
								SET
									cart_qty = cart_qty + $qty
								WHERE
									cart_id=$ret_cartid
								LIMIT
									1";
				$db->query($sql_update);
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				if($from_iphone_app==false)
				{
					if($ret==true)
					{
						// Redirecting user to the cart page
						echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/></form><script type='text/javascript'>document.frm_subcart.submit();</script>";
						exit;
					}
				}
				 else
			    { 
				      //return $show_msg;
				    $ret_arrs['msg'] 	= $show_msg;
					$ret_arrs['cartid'] = $ret_cartid;
				    //return $show_msg;
				    return $ret_arrs;
				}	
		}

	}
	function Add_Item_to_Cart_promoproducts($varN='var_',$varM='varmsg_',$ret=true,$prom_id=0,$maincart_id=0,$promtype)
	{
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$PricePromise_addtocart,$from_iphone_app;
		global $ecom_selfhttp;
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		$session_id = Get_session_Id_from();
		// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		$sql_product = "SELECT products_product_id,pcode_det_id FROM  promotional_code_product WHERE promotional_code_type = 'freeproduct' AND sites_site_id = $ecom_siteid AND promotional_code_code_id = $prom_id";
		$ret_product = $db->query($sql_product);
		if($db->num_rows($ret_product)>0)
		{
		while($row_product = $db->fetch_array($ret_product))
		{
			
			/*
			// Check whether instock notification is allowed for this product
			$sql_note = "SELECT product_stock_notification_required,product_name,product_alloworder_notinstock    
										FROM 
											products 
										WHERE 
											product_id = ".$row_product['products_product_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
			$ret_note = $db->query($sql_note);
			if ($db->num_rows($ret_note))
			{
				$row_note = $db->fetch_array($ret_note);
				$stock_notification_req = $row_note['product_stock_notification_required'];
				$stock_prod_name		= stripslashes($row_note['product_name']);
				$always_add_to_cart	= ($row_note['product_alloworder_notinstock']=='Y')?true:false;
			}*/
			 $sql_comb = "SELECT comb_id FROM promotional_code_products_variable_combination WHERE promotional_code_product_pcode_det_id=".$row_product['pcode_det_id']." AND products_product_id =".$row_product['products_product_id'];
			 $ret_comb =  $db->query($sql_comb);
			 $var_arr = array();

			 if($db->num_rows($ret_comb)>0)
			 {
			  while($row_comb = $db->fetch_array($ret_comb))
			  {
			   $var_check 	  = "SELECT var_id,var_value_id FROM promotional_code_products_variable_combination_map 
								  WHERE promotional_code_products_variable_combination_comb_id = ". $row_comb['comb_id'] ." AND products_product_id = ".$row_product['products_product_id']; 
			   $ret_varcheck  = $db->query($var_check);
				 if ($db->num_rows($ret_varcheck))
				 {
					 while($row_varcheck = $db->fetch_array($ret_varcheck))
					 {
						   $var_arr[$row_varcheck['var_id']] = $row_varcheck['var_value_id'];
					 }
				 }
			 // Calling the function to check whether the item is already there in cart.
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_cartid 	= Is_Item_already_Exists_Promproducts($var_arr,$varmsg_arr,$maincart_id,$row_product['products_product_id'],$prom_id);
		// Check the stock details here
		$stock_arr		= check_stock_available($row_product['products_product_id'],$var_arr);
	
		// Make the decision whether stock checking is required
		$stock_check 	= $stock_arr['inpreorder'];
		$stock			= $remstock = $stock_arr['stock']; // Setting the stock and remaining stock variables to the same value
		$combid			= $stock_arr['combid'];
		if ($ret_cartid==-1) // Case item does not exists in cart. So need to insert the details
		{  
					// Make an entry to the cart and its related tables
					$insert_array							= array();
					$insert_array['sites_site_id']			= $ecom_siteid;
					$insert_array['session_id']				= $session_id;
					$insert_array['products_product_id']	= $row_product['products_product_id'];
					$insert_array['cart_comb_id']			= $combid;
					$insert_array['cart_qty']				= 1;
					$insert_array['cart_preorder']			= $stock_check;
					$insert_array['cart_addedon']			= 'now()';
					$insert_array['cart_promotional_code_id'] = $prom_id;
					$insert_array['cart_main_cart_id']		= $maincart_id;
					
					$db->insert_from_array($insert_array,'cart');
					$insert_cartid 							= $db->insert_id();
					// Making entries to the cart_variables table if any variables exists
					if (count($var_arr))
					{
						// Inserting the variable details cart_variables table
						foreach ($var_arr as $k=>$v)
						{
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['var_id']			= $k;
							$insert_array['var_value_id']	= $v;
							$db->insert_from_array($insert_array,'cart_variables');
						}
					} 
					// Making entries to the cart_messages table if any messages exists
					if (count($varmsg_arr))
					{
						foreach ($varmsg_arr as $k=>$v)
						{
							// Get the type of current message from product_variable_messages table
							$sql_msg = "SELECT message_type
										FROM
											product_variable_messages
										WHERE
											message_id = $k
										LIMIT
											1";
							$ret_msg = $db->query($sql_msg);
							if ($db->num_rows($ret_msg))
							{
								$row_msg  = $db->fetch_array($ret_msg);
								$msg_type = $row_msg['message_type'];
							}
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['message_id']		= $k;
							$insert_array['message_value']	= add_slash($v);
							$insert_array['message_type']	= $msg_type;
							$db->insert_from_array($insert_array,'cart_messages');

						}
					}
                	$cartData = cartCalc(); // Calling the function to calculate the details related to cart

				}
			}

		  }
		  else
		  {
				// Calling the function to check whether the item is already there in cart.
				// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
				$ret_cartid 	= Is_Item_already_Exists_Promproducts($var_arr,$varmsg_arr,$maincart_id,$row_product['products_product_id'],$prom_id);
				// Check the stock details here
				$stock_arr		= check_stock_available($row_product['products_product_id'],$var_arr);

				// Make the decision whether stock checking is required
				$stock_check 	= $stock_arr['inpreorder'];
				$stock			= $remstock = $stock_arr['stock']; // Setting the stock and remaining stock variables to the same value
				$combid			= $stock_arr['combid'];
				if ($ret_cartid==-1) // Case item does not exists in cart. So need to insert the details
				{  
				// Make an entry to the cart and its related tables
				$insert_array							= array();
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['session_id']				= $session_id;
				$insert_array['products_product_id']	= $row_product['products_product_id'];
				$insert_array['cart_comb_id']			= $combid;
				$insert_array['cart_qty']				= 1;
				$insert_array['cart_preorder']			= $stock_check;
				$insert_array['cart_addedon']			= 'now()';
				//$insert_array['cart_prom_id']			= $prom_id;
				$insert_array['cart_promotional_code_id'] = $prom_id;
				$insert_array['cart_main_cart_id']		= $maincart_id;

				$db->insert_from_array($insert_array,'cart');
				$insert_cartid 							= $db->insert_id();
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart

				}
		  }
		}
	  }
	}
	function Is_Item_already_Exists_Subproducts($var_arr,$varmsg_arr,$maincart_id=0)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$session_id = Get_session_Id_from();
		$add_cond = '';
		if($maincart_id)
		{
			$add_cond = " AND cart_main_cart_id = $maincart_id ";	
		}
		$sql_check 	= "SELECT cart_id
									FROM
										cart
									WHERE
										sites_site_id=$ecom_siteid
										AND products_product_id=".$_REQUEST['fproduct_id']."
										AND session_id='".$session_id."'
										$add_cond 
										";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0) // case if product does not exists in cart
		{
			$cart_check_arr[$row_check['cart_id']] = -1;
		}
		else // case product exists in cart. So further checking is required to see if it is to be added as a new item or not
		{
			$cart_check_arr 	= array();
			while($row_check 	= $db->fetch_array($ret_check))
			{
				$cart_check_arr[$row_check['cart_id']] = 0;
				// ====================================================
				// Check whether any variable exist for current product
				// ====================================================
				$sql_var = "SELECT var_id,var_value_id
								FROM
									cart_variables
								WHERE
									cart_id = ".$row_check['cart_id'];
				$ret_var = $db->query($sql_var);
				if ($db->num_rows($ret_var))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_var)==count($var_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							if ($row_var['var_value_id']!=$var_arr[$row_var['var_id']])
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}

				// ======================================================
				// Check whether any variable message exists for product
				// ======================================================
				$sql_varmsg = "SELECT message_id,message_value
										FROM
											cart_messages
										WHERE
											cart_id = ".$row_check['cart_id'];
				$ret_varmsg = $db->query($sql_varmsg);
				if ($db->num_rows($ret_varmsg))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_varmsg)==count($varmsg_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_varmsg = $db->fetch_array($ret_varmsg))
						{
							if (strtolower($row_varmsg['message_value'])!=strtolower($varmsg_arr[$row_varmsg['message_id']]))
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}
			}
		}
		$returned_cartid = -1;
		foreach ($cart_check_arr as $k=>$v)
		{
			if ($v==0)
			{
				$returned_cartid = $k;
			}
		}
		// If reached upto here then the item already exists in cart. So return the cart id to the calling area
		return $returned_cartid;
	}	
	function Is_Item_already_Exists_Promproducts($var_arr,$varmsg_arr,$maincart_id=0,$prod_id,$prom_id)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$session_id = Get_session_Id_from();
		$add_cond = '';
		if($maincart_id)
		{
			$add_cond = " AND cart_main_cart_id = $maincart_id ";	
		}
		$sql_check 	= "SELECT cart_id 
									FROM
										cart
									WHERE
										sites_site_id=$ecom_siteid
										AND products_product_id=".$prod_id."
										AND session_id='".$session_id."'
										AND cart_promotional_code_id = '".$prom_id."'
										$add_cond 
										";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0) // case if product does not exists in cart
		{
			$cart_check_arr[$row_check['cart_id']] = -1;
		}
		else // case product exists in cart. So further checking is required to see if it is to be added as a new item or not
		{
			$cart_check_arr 	= array();
			while($row_check 	= $db->fetch_array($ret_check))
			{
				$cart_check_arr[$row_check['cart_id']] = 0;
				/*if($row_check['cart_promotional_code_id'] > 0)
				{
					$cart_check_arr[$row_check['cart_id']] = -1;
				}
				*/ 
				// ====================================================
				// Check whether any variable exist for current product
				// ====================================================
				$sql_var = "SELECT var_id,var_value_id
								FROM
									cart_variables
								WHERE
									cart_id = ".$row_check['cart_id'];
				$ret_var = $db->query($sql_var);
				if ($db->num_rows($ret_var))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_var)==count($var_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							if ($row_var['var_value_id']!=$var_arr[$row_var['var_id']])
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}

				// ======================================================
				// Check whether any variable message exists for product
				// ======================================================
				$sql_varmsg = "SELECT message_id,message_value
										FROM
											cart_messages
										WHERE
											cart_id = ".$row_check['cart_id'];
				$ret_varmsg = $db->query($sql_varmsg);
				if ($db->num_rows($ret_varmsg))
				{
					// Check whether the number of variables stored and number of variables passed as same
					if ($db->num_rows($ret_varmsg)==count($varmsg_arr))// Case if the count are same. Then the values should be compared
					{
						while ($row_varmsg = $db->fetch_array($ret_varmsg))
						{
							if (strtolower($row_varmsg['message_value'])!=strtolower($varmsg_arr[$row_varmsg['message_id']]))
								$cart_check_arr[$row_check['cart_id']] = -1;
						}
					}
					else
					{
						$cart_check_arr[$row_check['cart_id']] = -1;// case the product is to be treated as a new product
					}
				}
			}
		}
		$returned_cartid = -1;
		foreach ($cart_check_arr as $k=>$v)
		{
			if ($v==0)
			{
				$returned_cartid = $k;
			}
		}
		// If reached upto here then the item already exists in cart. So return the cart id to the calling area
		return $returned_cartid;
	}
	function handle_cart_prod_order()
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$session_id = Get_session_Id_from();
		// Check whether there exists atleast one sub product in cart
		$sql_cart = "SELECT cart_id FROM cart 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND session_id ='".$session_id."' 
							AND cart_main_cart_id<> 0 
						LIMIT 
							1";
		$ret_cart = $db->query($sql_cart);
		if($db->num_rows($ret_cart))
		{
			// Get all cart entries order by cart id
			$sql_carts = "SELECT cart_id,cart_main_cart_id FROM cart 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND session_id ='".$session_id."' 
						ORDER BY 
							cart_id";
			$ret_carts = $db->query($sql_carts);
			if($db->num_rows($ret_carts))
			{
				$carts_arr = array();
				while ($row_carts = $db->fetch_array($ret_carts))
				{
					$cur_cartid 	= $row_carts['cart_id'] ;
					$cur_maincartid = $row_carts['cart_main_cart_id'];
					if($cur_maincartid==0)
					{
						$carts_arr[$cur_cartid][] = $cur_cartid;
					}
					else
					{
						$carts_arr[$cur_maincartid][] = $cur_cartid;	
					}
				}
				$sortorder = 1;
				foreach ($carts_arr as $k=>$v)
				{
					if(count($v))
					{
						foreach ($v as $kk=>$vv)
						{
							$sql_upd = "UPDATE cart SET cart_sort=$sortorder WHERE cart_id = ".$vv." LIMIT 1";
							$db->query($sql_upd);
							$sortorder++;
						}
					}
				}
			}
			
		}
	}


	function add_products_wishlist($varN='var_',$varM='varmsg_')
	{
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
		global $ecom_selfhttp;
		$url = $_REQUEST['pass_url'];
		$custom_id = get_session_var('ecom_login_customer'); 
		$Captions_arr['WISHLIST'] 	= getCaptions('WISHLIST'); // Getting the captions to be used in this page
		$fproduct_id = $_REQUEST['fproduct_id'];
		$session_id = Get_session_Id_from();			// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		if($custom_id){
		// Get the variable and variable messages set for this product to an array 
		foreach ($_REQUEST as $k=>$v)
		{
			$var_nameLimit = strlen($varN);
			$var_messageLimit = strlen($varM);
			if (substr($k,0,$var_nameLimit) == $varN)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				$var_arr[$curid[1]] 	= trim($v);
				// Check whether curent var have values
				//$sql_check = "SELECT 
			}
			elseif (substr($k,0,$var_messageLimit) == $varM)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				$varmsg_arr[$curid[1]] 	= trim($v);
			}
		}
		// Calling the function to check whether the item is already there in cart. 
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_wishid 	= Is_Wishlist_already_Exists($var_arr,$varmsg_arr,$custom_id);	
		$comb_arr = get_combination_id($_REQUEST['fproduct_id'],$var_arr);
		//print_r($_REQUEST);
		
				if ($ret_wishid==-1) // Case item does not exists in cart. So need to insert the details
				{
				// Make an entry to the cart and its related tables
				$insert_array							= array();
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['session_id']				= $session_id;	
				$insert_array['products_product_id']	= $_REQUEST['fproduct_id'];
				$insert_array['customer_id']	= $custom_id;
				$insert_array['product_qty']	= $_REQUEST['qty'] ;
				$insert_array['comb_id']		= $comb_arr['combid'];	
	
				$db->insert_from_array($insert_array,'wishlist');
				$insert_wishid 							= $db->insert_id();
				// Making entries to the cart_variables table if any variables exists
				if (count($var_arr))
				{	
					// Inserting the variable details cart_variables table
					foreach ($var_arr as $k=>$v)
					{
						$insert_array											= array();
						$insert_array['wishlist_wishlist_id']		= $insert_wishid;
						$insert_array['wishlist_var_id']				= $k;
						$insert_array['wishlist_var_value_id']	= $v;
						$db->insert_from_array($insert_array,'wishlist_variables');
					}
				}
				// Making entries to the cart_messages table if any messages exists
				if (count($varmsg_arr))
				{
					foreach ($varmsg_arr as $k=>$v)
					{ 
					
						// Get the type of current message from product_variable_messages table
						$sql_msg = "SELECT message_type 
									FROM 
										product_variable_messages 
									WHERE 
										message_id = $k 
									LIMIT 
										1";
						$ret_msg = $db->query($sql_msg);
						if ($db->num_rows($ret_msg))
						{
							$row_msg  = $db->fetch_array($ret_msg);
							$msg_type = $row_msg['message_type'];
						}				
						$insert_array					= array();
						$insert_array['wishlist_wishlist_id']						= $insert_wishid;
						$insert_array['message_id']								= $k;
						$insert_array['message_value']							= add_slash($v);
						$insert_array['message_type']							= $msg_type;
						$db->insert_from_array($insert_array,'wishlist_messages');
					}
				}
				// Redirecting user to the cart page
				echo "<form method='post' action ='wishlist.html' name='frm_addwish'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='$url'/><input type='hidden' name='fproduct_id' value='$fproduct_id'/></form><script type='text/javascript'>document.frm_addwish.submit();</script>";
	
				}
			
			echo "<form method='post' action ='wishlist.html' name='frm_addwish'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='$url'/><input type='hidden' name='fproduct_id' value='$fproduct_id'/></form><script type='text/javascript'>document.frm_addwish.submit();</script>";
		}
		else{
			echo "<form method='post' action ='login_home.html' name='frm_addwish'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='$url'/><input type='hidden' name='fproduct_id' value='$fproduct_id'/></form><script type='text/javascript'>document.frm_addwish.submit();</script>";
		}
	}
		
		//Function to Support Enquiry 
function Is_Wishlist_already_Exists($var_arr,$varmsg_arr,$custom_id)
{
	
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	$session_id = Get_session_Id_from();
	$sql_check 	= "SELECT wishlist_id  
					FROM 
						wishlist 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND products_product_id=".$_REQUEST['fproduct_id']."
						AND customer_id='".$custom_id."'";
	$ret_check = $db->query($sql_check);
	if ($db->num_rows($ret_check)==0) // case if product does not exists in cart
	{
		
		$wish_check_arr[$row_check['wishlist_id']] = -1;
	}
	else // case product exists in cart. So further checking is required to see if it is to be added as a new item or not 
	{
		$wish_check_arr 	= array();
		while($row_check 	= $db->fetch_array($ret_check))
		{
			$wish_check_arr[$row_check['wishlist_id']] = 0;
			// ====================================================
			// Check whether any variable exist for current product
			// ====================================================
			$sql_var = "SELECT wishlist_var_id,wishlist_var_value_id 
						FROM 
							wishlist_variables
						WHERE 
							wishlist_wishlist_id = ".$row_check['wishlist_id'];
			$ret_var = $db->query($sql_var);
			if ($db->num_rows($ret_var))
			{
				// Check whether the number of variables stored and number of variables passed as same
				if ($db->num_rows($ret_var)==count($var_arr))// Case if the count are same. Then the values should be compared
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						if ($row_var['wishlist_var_value_id']!=$var_arr[$row_var['wishlist_var_id']])
							$wish_check_arr[$row_check['wishlist_id']] = -1;
					}
				}
				else
				{
				
					$wish_check_arr[$row_check['wishlist_id']] = -1;// case the product is to be treated as a new product
				}
			}
			// ======================================================
			// Check whether any variable message exists for product
			// ======================================================
			$sql_varmsg = "SELECT message_id,message_value  
						FROM 
							wishlist_messages 
						WHERE 
							wishlist_wishlist_id = ".$row_check['wishlist_id'];
			$ret_varmsg = $db->query($sql_varmsg);
			if ($db->num_rows($ret_varmsg))
			{
				// Check whether the number of variables stored and number of variables passed as same
				if ($db->num_rows($ret_varmsg)==count($varmsg_arr))// Case if the count are same. Then the values should be compared
				{
					while ($row_varmsg = $db->fetch_array($ret_varmsg))
					{
						if (strtolower($row_varmsg['message_value'])!=strtolower($varmsg_arr[$row_varmsg['message_id']]))
							$wish_check_arr[$row_check['wishlist_id']] = -1;
					}
				}
				else
				{
					$wish_check_arr[$row_check['wishlist_id']] = -1;// case the product is to be treated as a new product
				}
			}
		}
	}	
	$returned_wishid = -1;
	foreach ($wish_check_arr as $k=>$v)
	{
		if ($v==0)
		{
			$returned_wishid = $k;
		}
	}
	// If reached upto here then the item already exists in cart. So return the cart id to the calling area
	return $returned_wishid;
}
// ** Writing the function to add wishlist item to cart
	function Add_WishlistItem_to_Cart($custom_id,$wishlist_id,$product_ids)
	{
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
		global $ecom_selfhttp;
		$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
		$session_id = Get_session_Id_from();			// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		
		if($custom_id){
		foreach ($product_ids as $key=>$val) // loop to add each of the items in combo to cart
		{ 				
			$errmsg 							='';
			$variables 							= 'var'.$val;
			$variableMessages   				= 'varmsg'.$val;
			$_REQUEST['qty'] 				= $_REQUEST['qty_'.$val];
			$_REQUEST['fproduct_id'] 	= $val;
			$sql_select = "SELECT wishlist_id FROM wishlist WHERE products_product_id=".$val." AND customer_id=".$custom_id." AND sites_site_id=".$ecom_siteid." LIMIT 1";
			$ret_select = $db->query($sql_select);
			$row_select = $db->fetch_array($ret_select );
			$always_add_to_cart	= false;
			// Check whether instock notification is allowed for this product
			$sql_note = "SELECT product_stock_notification_required,product_name,product_alloworder_notinstock    
										FROM 
											products 
										WHERE 
											product_id = ".$val." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
			$ret_note = $db->query($sql_note);
			if ($db->num_rows($ret_note))
			{
				$row_note = $db->fetch_array($ret_note);
				$stock_notification_req = $row_note['product_stock_notification_required'];
				$stock_prod_name		= stripslashes($row_note['product_name']);
				$always_add_to_cart	= ($row_note['product_alloworder_notinstock']=='Y')?true:false;
			}
		// Get the variable and variable messages set for this product to an array 
		
		// ###################################################################################	
			// Section to handle the variables for the product
			// ###################################################################################
			$sql_vars = "SELECT wishlist_var_id,wishlist_var_value_id
							FROM 
								wishlist_variables  
							WHERE 	
								wishlist_wishlist_id=".$row_select['wishlist_id'];
			$ret_vars = $db->query($sql_vars);
			$row_prod['prod_vars'] = array();
			if ($db->num_rows($ret_vars))
			{
				$row_prod['prod_vars'] = array();
				while ($row_vars = $db->fetch_array($ret_vars))
				{
					$row_prod['prod_vars'][$row_vars['wishlist_var_id']] = $row_vars['wishlist_var_value_id'];
				}
			}
			$var_arr = $row_prod['prod_vars'];
			// #####################################################################################
			// Section to handle the case of messages
			// #####################################################################################
			$sql_msgs = "SELECT a.message_id,a.message_value,b.message_title 
							FROM 
								wishlist_messages a,product_variable_messages b 
							WHERE 	
								a.wishlist_wishlist_id=".$row_select['wishlist_id']." 
								AND a.message_id=b.message_id ";
			$ret_msgs = $db->query($sql_msgs);
			$row_prod['prod_msgs'] = array();
			if ($db->num_rows($ret_msgs))
			{
				
				while($row_msgs = $db->fetch_array($ret_msgs))
				{
					$cur_msg = array();
					$cur_msg['message_id'] 	  = $row_msgs['message_id'];
					$cur_msg['message_title'] = stripslashes($row_msgs['message_title']);
					$cur_msg['message_value'] = stripslashes($row_msgs['message_value']);
					if ($row_msgs['message_title'] and $row_msgs['message_value'])
					$row_prod['prod_msgs'][$cur_msg['message_id']] = $cur_msg['message_value'];
				}
			}
			$varmsg_arr = $row_prod['prod_msgs'];
		
		// Calling the function to check whether the item is already there in cart. 
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_cartid 	= Is_Item_already_Exists($var_arr,$varmsg_arr);	
		// Check the stock details here
		$stock_arr		= check_stock_available($val,$var_arr);
		
		// Make the decision whether stock checking is required
		$stock_check 	= $stock_arr['inpreorder'];
		$stock			= $remstock = $stock_arr['stock']; // Setting the stock and remaining stock variables to the same value
		$combid			= $stock_arr['combid'];
		$qty				= ($_REQUEST['qty'])?$_REQUEST['qty']:1; 
		$cartstock		= 0;
		
		if($always_add_to_cart) // case if add to cart is always allowed for this product then overriding the stock and also the preorder
		{
			$stock 			= $qty;
			$stock_check = true;
		}
		// If stock exists and also item already exists in cart
		if ($stock>0 and $ret_cartid!=-1) 
		{
			// Get the qty already in cart
			$sql_cart = "SELECT cart_qty 
							FROM 
								cart
							WHERE 
								cart_id =".$ret_cartid." LIMIT 1";
			$ret_cart = $db->query($sql_cart);
			if ($db->num_rows($ret_cart))
			{
				$row_cart 	= $db->fetch_array($ret_cart);
				$cartstock	= $row_cart['cart_qty'];
				if (!$stock_check)
				{
					if($stock<($qty+$cartstock))
					{
						$qty	= $stock - $cartstock;
						$errmsg = "Error!!";
						// Give approprate javascript message.
						/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
						$show_msg = 'CART_STOCK_INSUFF_ADJ_QTY';
					}	
				}	
			}
		}
		if ($ret_cartid==-1) // Case item does not exists in cart. So need to insert the details
		{
				if (!$stock_check)
				{
					if($stock<$qty)
					{
						$qty	= $stock;
						$errmsg = "Error!!";
						// Give approprate javascript message.
						/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
						$show_msg = 'CART_STOCK_INSUFF_ADJ_QTY';
					}	
				}
				
				if($qty)
				{
					// Make an entry to the cart and its related tables
					$insert_array							= array();
					$insert_array['sites_site_id']			= $ecom_siteid;
					$insert_array['session_id']				= $session_id;	
					$insert_array['products_product_id']	= $val;
					$insert_array['cart_comb_id']			= $combid;
					$insert_array['cart_qty']				= $qty;
					$insert_array['cart_addedon']			= 'now()';
					$db->insert_from_array($insert_array,'cart');
					$insert_cartid 							= $db->insert_id();
					
					// Making entries to the cart_variables table if any variables exists
					if (count($var_arr))
					{
						// Inserting the variable details cart_variables table
						foreach ($var_arr as $k=>$v)
						{ 
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['var_id']			= $k;
							$insert_array['var_value_id']	= $v;
							$db->insert_from_array($insert_array,'cart_variables');
						}
					}
	
					// Making entries to the cart_messages table if any messages exists
					if (count($varmsg_arr))
					{
						foreach ($varmsg_arr as $k=>$v)
						{
							// Get the type of current message from product_variable_messages table
							$sql_msg = "SELECT message_type 
										FROM 
											product_variable_messages 
										WHERE 
											message_id = $k 
										LIMIT 
											1";
							$ret_msg = $db->query($sql_msg);
							if ($db->num_rows($ret_msg))
							{
								$row_msg  = $db->fetch_array($ret_msg);
								$msg_type = $row_msg['message_type'];
							}				
							$insert_array					= array();
							$insert_array['cart_id']		= $insert_cartid;
							$insert_array['message_id']		= $k;
							$insert_array['message_value']	= add_slash($v);
							$insert_array['message_type']	= $msg_type;
							$db->insert_from_array($insert_array,'cart_messages');
						}
					}
				}	
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]);	// Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				
				// Redirecting user to the cart page
		}
		else // Case item already exists in cart. So need to increase the qty
		{
				$sql_update = "UPDATE cart 
								SET 
									cart_qty = cart_qty + $qty 
								WHERE 
									cart_id=$ret_cartid 
								LIMIT 
									1";
				$db->query($sql_update);
				$cartData = cartCalc(); // Calling the function to calculate the details related to cart
				//set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
				//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
				
					// Redirecting user to the cart page
		}
		//Delete section to remove the item from wishlist after inserted to the cart
				
				
				if(!$errmsg){
					if($val){
						$sql_delete = "DELETE FROM wishlist WHERE products_product_id=".$val." AND customer_id=".$custom_id." AND sites_site_id=".$ecom_siteid." LIMIT 1";
						$db->query($sql_delete);
					}
					if($row_select['wishlist_id']){
					//Delete section to remove the messages from wishlist_messages after inserted to the cart
						$sql_delete_mess = "DELETE FROM wishlist_messages WHERE wishlist_wishlist_id=".$row_select['wishlist_id']." LIMIT 1";
						$db->query($sql_delete_mess);
					//Delete section to remove the variables from wishlist_messages after inserted to the cart
						$sql_delete_var = "DELETE FROM wishlist_variables WHERE wishlist_wishlist_id=".$row_select['wishlist_id']." LIMIT 1";
						$db->query($sql_delete_var);
					}
				}
		}
		echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='hold_section' value='".$show_msg."'/>.</form><script type='text/javascript'>document.frm_subcart.submit();</script>";
      }//end custom_id
	  else 
	  {
	    echo "<form method='post' action ='login_home.html' name='frm_addwish'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='$url'/><input type='hidden' name='fproduct_id' value='$fproduct_id'/></form><script type='text/javascript'>document.frm_addwish.submit();</script>";

	  }	
	}	
	// Function to get the variables of a product in cart in an array
	function get_cartvariables($cartid)
	{
		global $db;
		global $ecom_selfhttp;
		$var_arr	= array();
		// Get the details of variables for this product in cart and moving it to an array
		$sql_cartvar = "SELECT var_id,var_value_id
							FROM
								cart_variables
							WHERE
								cart_id=$cartid";
		$ret_cartvar = $db->query($sql_cartvar);
		if ($db->num_rows($ret_cartvar))
		{
			while ($row_cartvar = $db->fetch_array($ret_cartvar))
			{
				$var_arr[$row_cartvar['var_id']] = $row_cartvar['var_value_id'];
			}
		}
		return $var_arr;
	}


	// Function to show the add to cart link
	function show_addtocart($prod_arr,$class_arr,$frm,$return = false,$prefix='',$suffix='',$class_div='',$override_hideqty=0)
	{ 
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr;
		global $ecom_selfhttp;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		
		//to sheck whether quantity box should be shown or not
		$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		$quantity_div_class     = ($class_arr['QTY_DIV']!='')?$class_arr['QTY_DIV']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';   
		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty && $override_hideqty!=1)
		{
			$quantity_box  = '<div class="'.$quantity_div_class.'">'.$Captions_arr['COMMON']['COMMON_QTY'].'<input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></div>';
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
				return;
		}
		$show_buy_now = false;
		
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// if show cart link is set to display
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{
					//if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
					*/	
					//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1,0,0);
						$quantity_box_display = true;
					//}
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					/*if ($var_exists){*/
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
					}*/
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						/*if ($var_exists){*/
							//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						/*}
						else {
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
						}*/
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					/*if ($var_exists){*/
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
					}*/
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{
			
			// Check whether variables exists for current product
			/*$sql_prod = "SELECT var_id
							FROM
								product_variables
							WHERE
								products_product_id=".$prod_arr['product_id']."
								AND var_hide = 0
							LIMIT
								1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$var_exists = true;
			}
			else
				$var_exists = false;*/
			
			if($prod_arr['product_variables_exists']=='Y')
				$var_exists = true;
			else
				$var_exists = false;	
				
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{
				
				//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// show preorder link only if show cart link is set to display
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// show preorder link only if show cart link is set to display
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		if ($return==true)
		{
			if($prod_arr['product_discontinue']==1)
			{
				$mod		= 'PRODDET_DISCONTINUE';
				return $mod;
			}
			else
			{
				return $mod;
			}
		}	
		elseif ($link)
		{
		if($prod_arr['product_discontinue']==1)
	    {
		?>
			<div class="discontinue_class"><?php echo "Discontinue";?></div>
		<?php
		}
	    else
	    {
		   if($prefix!='')
			 echo $prefix;
			if($quantity_box_display)
				echo $quantity_box;
	    
		if($class_div!='')
		{
		?>
			<div class="<?=$class_div?>"><a href="<?php echo $link?>" title="<?php echo $caption?>" class="<?php echo $class?>"><?php echo $caption?></a>
			<input type="hidden" name="prod_list_submit_common" value="<?php echo $curtype?>" />
			</div>
		<?php
		}
		else
		{
		?>
		<a href="<?php echo $link?>" title="<?php echo $caption?>" class="<?php echo $class?>"><?php echo $caption?></a>
		<input type="hidden" name="prod_list_submit_common" value="<?php echo $curtype?>" />
		<?
		}
		    if($suffix!='')
			echo $suffix;
		}
		}
	}
	// Function which prepares paging
	function prepare_paging($pg,$perpage,$numcount)
  	{
		global $ecom_selfhttp;
  		/////////////////////////////////For paging///////////////////////////////////////////
		$perpage = ($perpage)?$perpage:10;//#Total records shown in a page
		//moved up
		$pages = ceil($numcount / $perpage);//#Getting the total pages
		if($pg > $pages) {
			$pg = $pages;
		}
		// end moved up
		if (!($pg > 0) || $pg == 0) { $pg = 1; }
		$startrec = ($pg - 1) * $perpage;//#Starting record.
		// code here moved up by anu
		$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
		// ** Preparing the array to be returned
		$ret_arr['pg'] 		= $pg;
		$ret_arr['startrec']= $startrec;
		$ret_arr['pages']	= $pages;

		return $ret_arr;
  	}
	//Functions for Paging
function pageNavApp ($pagenum, $pages, $query_str,$class_arr,$perpage,$pg_var,$path)
{
	global $Captions_arr;
	global $ecom_selfhttp;
	// offset = (page - 1) * thumbs
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if($query_str)
	{
		if ($perpage)
			$add = '&amp;'.$perpage;
		$a = "<a href='$path?$query_str$add&amp;$pg_var=";
	}
	else
	{
		if ($perpage)
			$add = $perpage."&$pg_var=";
		else
			$add = "$pg_var=";
		$a = "<a href='$path?$add";
	}
	$b = "'>";
	$c = "</a>\n";
	$nav = "<div class='pro_nav_links' align='right'>"; // init page nav string

	if ($pagenum == 1) {

			//$nav .= "<img src='images/paging/left2_disabled.gif' border='0'>[First]&nbsp;&nbsp;";
			$nav .= $Captions_arr['COMMON']['COMMON_FIRST']."&nbsp;&nbsp;";
			//$nav .= "<img src='images/paging/left_disabled.gif' border='0'>[Prev]&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= $Captions_arr['COMMON']['COMMON_PREV']."&nbsp;&nbsp;";

	} else {

			//$nav .= $a."1".$b."<img src='images/paging/left2.gif' border='0'>[First]".$c."&nbsp;&nbsp;";
			$nav .= $a."1".$b.$Captions_arr['COMMON']['COMMON_FIRST'].$c."&nbsp;&nbsp;";
			//$nav .= $a.($pagenum - 1).$b."<img src='images/paging/left.gif' border='0'>[Prev]".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= $a.($pagenum - 1).$b."Prev".$c."&nbsp;&nbsp;&nbsp;&nbsp;";

	}


	if ($pagenum == $pages) {

			//$nav .= "<img src='images/paging/right_disabled.gif' border='0'>[Next]&nbsp;&nbsp;";
			$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_NEXT'];
			//$nav .= "<img src='images/paging/right2_disabled.gif' border='0'>[Last]<br>";
			$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_LAST']."<br/>";

	} else {
			//$nav .= $a.($pagenum +1).$b."<img src='images/paging/right.gif' border='0'>[Next]".$c."&nbsp;&nbsp;";
			$nav .= $a.($pagenum +1).$b.$Captions_arr['COMMON']['COMMON_NEXT'].$c."&nbsp;&nbsp;";
			//$nav .= $a.($pages).$b."<img src='images/paging/right2.gif' border='0'>[Last]".$c."<br>";
			$nav .= $a.($pages).$b.$Captions_arr['COMMON']['COMMON_LAST'].$c."<br/>";

	}
	
	$nav .= '</div>';
	$nav .= '<div class="pro_nav_page" align="right">';
	$nav .= makeNavApp ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path);
	$nav .= '</div>';
	return $nav;
}
//Functions for Paging
function pageNavApp_org ($pagenum, $pages, $query_str,$class_arr,$perpage,$pg_var,$path)
{
	global $Captions_arr;
	global $ecom_selfhttp;
	// offset = (page - 1) * thumbs
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if($query_str)
	{
		if ($perpage)
			$add = '&amp;'.$perpage;
		$a = "<a href='$path?$query_str$add&amp;$pg_var=";
	}
	else
	{
		if ($perpage)
			$add = $perpage."&$pg_var=";
		else
			$add = "$pg_var=";
		$a = "<a href='$path?$add";
	}
	$b = "'>";
	$c = "</a>\n";
	$nav = "<div>"; // init page nav string

	if ($pagenum == 1) {

			//$nav .= "<img src='images/paging/left2_disabled.gif' border='0'>[First]&nbsp;&nbsp;";
			$nav .= $Captions_arr['COMMON']['COMMON_FIRST']."&nbsp;&nbsp;";
			//$nav .= "<img src='images/paging/left_disabled.gif' border='0'>[Prev]&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= $Captions_arr['COMMON']['COMMON_PREV']."&nbsp;&nbsp;";

	} else {

			//$nav .= $a."1".$b."<img src='images/paging/left2.gif' border='0'>[First]".$c."&nbsp;&nbsp;";
			$nav .= $a."1".$b.$Captions_arr['COMMON']['COMMON_FIRST'].$c."&nbsp;&nbsp;";
			//$nav .= $a.($pagenum - 1).$b."<img src='images/paging/left.gif' border='0'>[Prev]".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
			$nav .= $a.($pagenum - 1).$b."Prev".$c."&nbsp;&nbsp;&nbsp;&nbsp;";

	}


	if ($pagenum == $pages) {

			//$nav .= "<img src='images/paging/right_disabled.gif' border='0'>[Next]&nbsp;&nbsp;";
			$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_NEXT'];
			//$nav .= "<img src='images/paging/right2_disabled.gif' border='0'>[Last]<br>";
			$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_LAST']."<br/>";

	} else {
			//$nav .= $a.($pagenum +1).$b."<img src='images/paging/right.gif' border='0'>[Next]".$c."&nbsp;&nbsp;";
			$nav .= $a.($pagenum +1).$b.$Captions_arr['COMMON']['COMMON_NEXT'].$c."&nbsp;&nbsp;";
			//$nav .= $a.($pages).$b."<img src='images/paging/right2.gif' border='0'>[Last]".$c."<br>";
			$nav .= $a.($pages).$b.$Captions_arr['COMMON']['COMMON_LAST'].$c."<br/>";

	}
	
	$nav .= '</div>';
	$nav .= '<div>';
	$nav .= makeNavApp ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path);
	$nav .= '</div>';
	return $nav;
}
function pageNavApp_Advanced ($pagenum, $pages, $query_str,$class_arr,$perpage,$pg_var,$path)
{
        global $Captions_arr;
        global $ecom_selfhttp;
        if($query_str)
        {
                if ($perpage)
                        $add = '&amp;'.$perpage;
                $a = "<a href='$path?$query_str$add&amp;$pg_var=";
        }
        else
        {
                if ($perpage)
                        $add = $perpage."&$pg_var=";
                else
                        $add = "$pg_var=";
                $a = "<a href='$path?$add";
        }
        $b = "'>";
        $c = "</a>";
        

        if ($pagenum == 1)
        {
            $nav_left = "<li class='nolink'>"; // init page nav string
            $nav_left .= $Captions_arr['COMMON']['COMMON_FIRST']."</li>";
			$nav_left .= "<li class='nolink'>"; // init page nav string
            $nav_left .= $Captions_arr['COMMON']['COMMON_PREV']."&nbsp;&nbsp;";
            $nav_left .= '</li>';
        }
        else 
        {
           $nav_left = "<li class='blacklinkleft'>"; // init page nav string
           $nav_left .= $a."1".$b.$Captions_arr['COMMON']['COMMON_FIRST'].$c."</li>";
		   $nav_left .= "<li class='blacklinkleft'>"; // init page nav string
           $nav_left .= $a.($pagenum - 1).$b.$Captions_arr['COMMON']['COMMON_PREV'].$c;
           $nav_left .= '</li>';
        }
        if ($pagenum == $pages)
        {
            $nav_right = "<li class='nolinkright'>"; // init page nav string
            $nav_right .= $Captions_arr['COMMON']['COMMON_NEXT'].'</li>';
			$nav_right .= "<li class='nolinkright'>"; // init page nav string
            $nav_right .= $Captions_arr['COMMON']['COMMON_LAST'];
            $nav_right .= '</li>';
        }
        else 
        {
            $nav_right = "<li class='blacklinkright'>"; // init page nav string
            $nav_right .= $a.($pagenum +1).$b.$Captions_arr['COMMON']['COMMON_NEXT'].$c."</li>";
			$nav_right .= "<li class='blacklinkright'>"; // init page nav string
            $nav_right .= $a.($pages).$b.$Captions_arr['COMMON']['COMMON_LAST'].$c."<br/>";
            $nav_right .= '</li>';
        }
        //$nav_middle .= '<li class="blacklink">';
        $nav_middle .= makeNavApp_advanced ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path);
        //$nav_middle .= '</li>';
        $nav['start_nav']       = $nav_left;
        $nav['page_no']         = $nav_middle;
        $nav['end_nav']         = $nav_right;
        return $nav;
}
function makeNavApp_advanced ($pages, $pagenum, $query_str='', $nav = "", $mag = 1,$class_arr,$perpage,$pg_var,$path) {
	global $theme_folder,$Captions_arr;
	global $ecom_selfhttp;
	$n = 10; // Number of pages or groupings
	$m = 10; // Order of magnitude of groupings
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if ($pages<=1)
		return;
	if($query_str) {
		if ($perpage)
				$add = '&amp;'.$perpage;
		$a = "<li class='blacklink'><a href='$path?$query_str$add&amp;$pg_var=";
	} else {
		if ($perpage)
				$add = $perpage."&amp;$pg_var=";
			else
				$add = "$pg_var=";
		$a = "<li class='blacklink'><a href='$path?$add";
	}
	$b = "'>";
	$c = "</a></li>";
	if ($mag == 1) {
		// single page level
		$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
		for ($i = $minpage; $i < $pagenum; $i++) {
			if ( isset($nav[1]) ) {
				$nav[1] .= $a.($i).$b;
			} else {
				$nav[1] = $a.($i).$b;
			}
			$nav[1] .= "$i ";
			$nav[1] .= $c;
		}
		if ( isset($nav[1]) ) {
			$nav[1] .= "<li class='redlink'>$pagenum</li>";
		} else {
			$nav[1] = "<li class='redlink'>$pagenum</li>";
		}
		$maxpage = ceil ($pagenum/$n) * $n;
		if ( $pages >= $maxpage ) {
			for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			//$nav[1] .= "<br />";
		} else {
			for ($i = ($pagenum+1); $i <= $pages; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			//$nav[1] .= "<br />";
		}
		if ( $minpage > 1 || $pages > $n ) {
			// go to next level
			$nav = makeNavApp_advanced ($pages, $pagenum, $query_str, $nav, $n,$class_arr,$perpage,$pg_var,$path);
		}
		// Construct outgoing string from pieces in the array
		$out = $nav[1];
		for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
			if (isset($nav[$i][1]) && isset($nav[$i][2])) {
				$out = $nav[$i][1].$out.$nav[$i][2];
			} else if (isset($nav[$i][1])) {
				$out = $nav[$i][1].$out;
			} else if (isset($nav[$i][2])) {
				$out = $out.$nav[$i][2];
			} else {
				$out = $out;
			}
		}
		return $out;
	}
	$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
	$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
	if ( $prevpage > $minpage ) {
		for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
			if (isset($nav[$mag][1])) {
				$nav[$mag][1] .= $a.($i+1).$b;
			} else {
				$nav[$mag][1] = $a.($i+1).$b;
			}
			$nav[$mag][1] .= $a.($i+1).$b;
			$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
			$nav[$mag][1] .= $c;
		}
		//$nav[$mag][1] .= "<br />";
	} // Otherwise, it's this page's group, which is handled the mag level below, so skip
	$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
	if ( $pages >= $maxpage ) {
		// If there are more pages than we are accounting for here
		$nextpage = ceil ($pagenum/$mag) * $mag;
		if ($maxpage > $nextpage) {
			for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			//$nav[$mag][2] .= "<br />";
		}
	} else {
		// This is the end
		if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
			// If there are more pages than just this page's group
			for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= $a.($i+1).$b;
			$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
			$nav[$mag][2] .= $c;
			//$nav[$mag][2] .= "<br />";
		}
	}
	if ( $minpage > 1 || $pages >= $maxpage ) {
		$nav = makeNavApp_advanced ($pages, $pagenum, $query_str, $nav, $mag * $m,$class_arr,$perpage,$pg_var,$path);
	}
	return $nav;
}
function makeNavApp ($pages, $pagenum, $query_str='', $nav = "", $mag = 1,$class_arr,$perpage,$pg_var,$path) {
	global $theme_folder,$Captions_arr;
	global $ecom_selfhttp;
	$n = 10; // Number of pages or groupings
	$m = 10; // Order of magnitude of groupings
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if ($pages<=1)
		return;
	if($query_str) {
		if ($perpage)
				$add = '&amp;'.$perpage;
		$a = "<a class='edittextlink' href='$path?$query_str$add&amp;$pg_var=";
	} else {
		if ($perpage)
				$add = $perpage."&amp;$pg_var=";
			else
				$add = "$pg_var=";
		$a = "<a class='edittextlink' href='$path?$add";
	}
	$b = "'>";
	$c = "</a>\n";
	if ($mag == 1) {
		// single page level
		$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
		for ($i = $minpage; $i < $pagenum; $i++) {
			if ( isset($nav[1]) ) {
				$nav[1] .= $a.($i).$b;
			} else {
				$nav[1] = $a.($i).$b;
			}
			$nav[1] .= "$i ";
			$nav[1] .= $c;
		}
		if ( isset($nav[1]) ) {
			$nav[1] .= "<span>&nbsp;$pagenum </span> ";
		} else {
			$nav[1] = "<span>&nbsp;$pagenum </span>";
		}
		$maxpage = ceil ($pagenum/$n) * $n;
		if ( $pages >= $maxpage ) {
			for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br />";
		} else {
			for ($i = ($pagenum+1); $i <= $pages; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br />";
		}
		if ( $minpage > 1 || $pages > $n ) {
			// go to next level
			$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $n,$class_arr,$perpage,$pg_var,$path);
		}
		// Construct outgoing string from pieces in the array
		$out = $nav[1];
		for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
			if (isset($nav[$i][1]) && isset($nav[$i][2])) {
				$out = $nav[$i][1].$out.$nav[$i][2];
			} else if (isset($nav[$i][1])) {
				$out = $nav[$i][1].$out;
			} else if (isset($nav[$i][2])) {
				$out = $out.$nav[$i][2];
			} else {
				$out = $out;
			}
		}
		return $out;
	}
	$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
	$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
	if ( $prevpage > $minpage ) {
		for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
			if (isset($nav[$mag][1])) {
				$nav[$mag][1] .= $a.($i+1).$b;
			} else {
				$nav[$mag][1] = $a.($i+1).$b;
			}
			$nav[$mag][1] .= $a.($i+1).$b;
			$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
			$nav[$mag][1] .= $c;
		}
		$nav[$mag][1] .= "<br />";
	} // Otherwise, it's this page's group, which is handled the mag level below, so skip
	$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
	if ( $pages >= $maxpage ) {
		// If there are more pages than we are accounting for here
		$nextpage = ceil ($pagenum/$mag) * $mag;
		if ($maxpage > $nextpage) {
			for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= "<br />";
		}
	} else {
		// This is the end
		if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
			// If there are more pages than just this page's group
			for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= $a.($i+1).$b;
			$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
			$nav[$mag][2] .= $c;
			$nav[$mag][2] .= "<br />";
		}
	}
	if ( $minpage > 1 || $pages >= $maxpage ) {
		$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $mag * $m,$class_arr,$perpage,$pg_var,$path);
	}
	return $nav;
}
function makeNavApp_org ($pages, $pagenum, $query_str='', $nav = "", $mag = 1,$class_arr,$perpage,$pg_var,$path) {
	global $theme_folder,$Captions_arr;
	global $ecom_selfhttp;
	$n = 10; // Number of pages or groupings
	$m = 10; // Order of magnitude of groupings
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if ($pages<=1)
		return;
	if($query_str) {
		if ($perpage)
				$add = '&amp;'.$perpage;
		$a = "<a class='edittextlink' href='$path?$query_str$add&amp;$pg_var=";
	} else {
		if ($perpage)
				$add = $perpage."&amp;$pg_var=";
			else
				$add = "$pg_var=";
		$a = "<a class='edittextlink' href='$path?$add";
	}
	$b = "'>";
	$c = "</a>\n";
	if ($mag == 1) {
		// single page level
		$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
		for ($i = $minpage; $i < $pagenum; $i++) {
			if ( isset($nav[1]) ) {
				$nav[1] .= $a.($i).$b;
			} else {
				$nav[1] = $a.($i).$b;
			}
			$nav[1] .= "$i ";
			$nav[1] .= $c;
		}
		if ( isset($nav[1]) ) {
			$nav[1] .= "<span>&nbsp;$pagenum </span> ";
		} else {
			$nav[1] = "<span>&nbsp;$pagenum </span>";
		}
		$maxpage = ceil ($pagenum/$n) * $n;
		if ( $pages >= $maxpage ) {
			for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br />";
		} else {
			for ($i = ($pagenum+1); $i <= $pages; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			$nav[1] .= "<br />";
		}
		if ( $minpage > 1 || $pages > $n ) {
			// go to next level
			$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $n,$class_arr,$perpage,$pg_var,$path);
		}
		// Construct outgoing string from pieces in the array
		$out = $nav[1];
		for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
			if (isset($nav[$i][1]) && isset($nav[$i][2])) {
				$out = $nav[$i][1].$out.$nav[$i][2];
			} else if (isset($nav[$i][1])) {
				$out = $nav[$i][1].$out;
			} else if (isset($nav[$i][2])) {
				$out = $out.$nav[$i][2];
			} else {
				$out = $out;
			}
		}
		return $out;
	}
	$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
	$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
	if ( $prevpage > $minpage ) {
		for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
			if (isset($nav[$mag][1])) {
				$nav[$mag][1] .= $a.($i+1).$b;
			} else {
				$nav[$mag][1] = $a.($i+1).$b;
			}
			$nav[$mag][1] .= $a.($i+1).$b;
			$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
			$nav[$mag][1] .= $c;
		}
		$nav[$mag][1] .= "<br />";
	} // Otherwise, it's this page's group, which is handled the mag level below, so skip
	$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
	if ( $pages >= $maxpage ) {
		// If there are more pages than we are accounting for here
		$nextpage = ceil ($pagenum/$mag) * $mag;
		if ($maxpage > $nextpage) {
			for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= "<br />";
		}
	} else {
		// This is the end
		if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
			// If there are more pages than just this page's group
			for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= $a.($i+1).$b;
			$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
			$nav[$mag][2] .= $c;
			$nav[$mag][2] .= "<br />";
		}
	}
	if ( $minpage > 1 || $pages >= $maxpage ) {
		$nav = makeNavApp ($pages, $pagenum, $query_str, $nav, $mag * $m,$class_arr,$perpage,$pg_var,$path);
	}
	return $nav;
}
function paging_show_totalcount($numcount,$page_type,$pg,$pages)
{
	global $Captions_arr;
	global $ecom_selfhttp;
	echo "$numcount $page_type ".$Captions_arr['COMMON']['COMMON_FOUND'].". ".$Captions_arr['COMMON']['COMMON_PAGE']." <b>$pg</b> ".$Captions_arr['COMMON']['COMMON_OF']." <b>$pages</b>";
}
function paging_footer($path,$query_string,$numcount,$pg,$pages,$perpage,$pg_var,$page_type,$class_arr,$total_req=1)
{
	global $Captions_arr;
	global $ecom_selfhttp;
	if ($pages<=1)
		return ;
	if($total_req==1)
		echo "$numcount $page_type ".$Captions_arr['COMMON']['COMMON_FOUND'].". ".$Captions_arr['COMMON']['COMMON_PAGE']." <b>$pg</b> ".$Captions_arr['COMMON']['COMMON_OF']." <b>$pages</b>";
	if($numcount>1)
	{
		if($total_req==1)
			echo "<br />".pageNavApp ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path);
		else
		
			echo pageNavApp ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path);
	}
}
function paging_footer_advanced($path,$query_string,$numcount,$pg,$pages,$perpage,$pg_var,$page_type,$class_arr)
{
    global $Captions_arr;
    global $ecom_selfhttp;
    $ret_arr    = array();
    $nav_arr    = array(); 
   // if ($pages<=1)
    //    return ;
    $ret_arr['total_cnt'] = "$numcount $page_type ".$Captions_arr['COMMON']['COMMON_FOUND'].". ".$Captions_arr['COMMON']['COMMON_PAGE']." <b>$pg</b> ".$Captions_arr['COMMON']['COMMON_OF']." <b>$pages</b>";
    if($numcount>1)
    {
        $ret_arr['navigation'] = pageNavApp_Advanced ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path);
    }
    return $ret_arr;
}
	// Function to get the parameters required for caching
	function getdetails_Cache($typ,$id)
	{
		global $ecom_hostname,$image_path,$sitesel_curr,$default_layout;
		global $ecom_selfhttp;
		$cache_path = $image_path.'/cache';
		if (!file_exists($cache_path))
			mkdir($cache_path);
		
		$use_default_layout = str_replace('_','',$default_layout);	
		switch($typ)
		{
			// #############################################
			// body for guest
			// #############################################
			case 'body_normal':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'."normal_".$id.".txt";
				$cur_dir			= $cache_path.'/body';
			break;
			// #############################################
			// Subcategory list for categories
			// #############################################
			case 'category':
				$id_arr				= explode("~",$id); 
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.$id_arr[0].".txt";
				$cur_dir			= $cache_path.'/category';
			break;
			// #############################################
			// Static page groups
			// #############################################
			case 'comp_topstatgroup': // component static group
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'top_'.$id.".txt";
				$cur_dir			= $cache_path.'/statgroup';
			break;
			case 'comp_bottomstatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'bottom_'.$id.".txt";
				$cur_dir			= $cache_path.'/statgroup';
			break;
			case 'comp_leftstatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'left_'.$id.".txt";
				$cur_dir			= $cache_path.'/statgroup';
			break;
			case 'comp_rightstatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'right_'.$id.".txt";
				$cur_dir			= $cache_path.'/statgroup';
			break;
			case 'comp_seostatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'seo_'.$id.".txt";
				$cur_dir			= $cache_path.'/statgroup';
			break;
			// #############################################
			// Category groups
			// #############################################
			case 'comp_topcatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'top_'.$id.".txt";
				$cur_dir			= $cache_path.'/catgroup';
			break;
			case 'comp_bottomcatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'bottom_'.$id.".txt";
				$cur_dir			= $cache_path.'/catgroup';
			break;
			case 'comp_leftcatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'left_'.$id.".txt";
				$cur_dir			= $cache_path.'/catgroup';
			break;
			case 'comp_rightcatgroup':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'right_'.$id.".txt";
				$cur_dir			= $cache_path.'/catgroup';
			break;
			// #############################################
			// Best Sellers
			// #############################################
			case 'comp_leftbestseller':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/bestseller';
			break;
			case 'comp_rightbestseller':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/bestseller';
			break;
			// #############################################
			// Combo Deal
			// #############################################
			case 'comp_leftcombo':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_leftcombo_'.$id.".txt";
				$cur_dir			= $cache_path.'/combo';
			break;
			case 'comp_rightcombo':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_rightcombo_'.$id.".txt";
				$cur_dir			= $cache_path.'/combo';
			break;
			// #############################################
			// Product Shops
			// #############################################
			case 'shop':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_left_menu':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'left_menu_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_left_dropdown':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'left_dropdown_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_right_menu':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'right_menu_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_right_dropdown':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'right_dropdown_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			
			
			case 'shop_left_header':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'left_header_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_right_header':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'right_header_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_top_menu':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'top_menu_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			case 'shop_bottom_menu':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'bottom_menu_'.$id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			
			
			
			// #############################################
			// Product Shelves
			// #############################################
										// Normal shelf
			case 'compshelf_normal_1row_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_1row_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_normal_1row_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_1row_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_normal_list_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_list_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_normal_list_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_list_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_normal_dropdown_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_dropdown_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_normal_dropdown_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_normal_dropdown_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
										// Christmas shelf
			case 'compshelf_christ_1row_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_1row_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_christ_1row_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_1row_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_christ_list_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_list_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_christ_list_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_list_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_christ_dropdown_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_dropdown_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_christ_dropdown_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_christ_dropdown_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
										// New year shelf
			case 'compshelf_new_1row_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_1row_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_new_1row_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_1row_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_new_list_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_list_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_new_list_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_list_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_new_dropdown_left':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_dropdown_left_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			case 'compshelf_new_dropdown_right':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'compshelf_new_dropdown_right_'.$id.".txt";
				$cur_dir			= $cache_path.'/shelf';
			break;
			// #############################################
			// advert
			// #############################################
			case 'comp_leftadvert':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_leftadvert_'.$id.".txt";
				$cur_dir			= $cache_path.'/advert';
			break;
			case 'comp_rightadvert':
				$ret_arr['fname']	= $sitesel_curr.'-'.$use_default_layout.'-'.'comp_rightadvert_'.$id.".txt";
				$cur_dir			= $cache_path.'/advert';
			break;
			/*case 'shop':
				$ret_arr['fname']	= $id.".txt";
				$cur_dir			= $cache_path.'/shop';
			break;
			*/
		};
		if (!file_exists($cur_dir) and $cur_dir!='')
			mkdir($cur_dir);
		$ret_arr['path'] 	= $cur_dir.'/'.$ret_arr['fname'];

		return $ret_arr;
	}
	// Function which checks whether cache file exists
	function exists_Cache($typ,$id)
	{
		global $ecom_selfhttp;
		$det_arr = getdetails_Cache($typ,$id); // Calling the function to get the parameters to be used
		// Check whether the required cache file exists
		if (file_exists($det_arr['path']))
		{
			return true;
		}
	}
	function delete_bestseller_cache()		// Function to delete cache for best sellers
	{
		global $image_path,$ecom_siteid;
		global $ecom_selfhttp;
		$cache_path = $image_path.'/cache/bestseller';
		if ($cache_path=='' or !$cache_path)		// just for a double protection
		exit;
	
		if (file_exists($cache_path))				// Check whether directory exists
		{
			if (is_dir($cache_path))
			{
				$dirhandle=opendir($cache_path);
				while(($file = readdir($dirhandle)) !== false)
				{
					if (($file!=".")&&($file!=".."))
					{
						$currentfile=$cache_path."/".$file;
						if (!$i) $i = 0;
						if(!is_dir($currentfile))
						{
							$file_arr = explode('.',$file);
							if($file_arr[1]=='txt')
							{
								unlink($currentfile);
							}
						}
						$i++;
					}
				}
			}
		}
	}
// Function to delete cache for category
function delete_category_cache($id)
{
	global $image_path,$db;
	global $ecom_selfhttp;

	if ($id)
	{
		$cache_path = $image_path.'/cache/category';
		if (file_exists($cache_path))				// Check whether directory exists
		{
			if ($root = @opendir($cache_path))		// open the directory
			{
				while ($file=readdir($root))		// reading the files in current directory
				{
					if($file=="." || $file=="..")
					{
						continue;
					}
					else
					{
						$file_arr 		= explode("-",$file);			// exploding based on -
						$id_arr		= explode(".",$file_arr[1]); 	// exploding based on .
						if ($id==$id_arr[0] and $id_arr[1]=='txt') 	// check if id match
						{
							if (file_exists($cache_path.'/'.$file))
							echo $cache_path.'/'.$file;
							unlink($cache_path.'/'.$file);		// delete the cache file
						}
					}

				}
			}
		}
	}
}
	// Function to save cache
	function save_Cache($typ,$id,$content)
	{
		global $ecom_hostname,$image_path;
		global $ecom_selfhttp;
		if (trim($content))
		{
			$det_arr = getdetails_Cache($typ,$id); // Calling the function to get the parameters to be used
			// Saving the cache over here
			$fp 	= fopen($det_arr['path'],'w');
			fwrite($fp,$content);
			fclose($fp);
		}
	}
	// Function to get the content from the cache file
	function getcontent_Cache($typ,$id)
	{
		global $ecom_selfhttp;
		$content	 = '';
		$det_arr 	= getdetails_Cache($typ,$id); // Calling the function to get the parameters to be used
		if (exists_Cache($typ,$id))
		{
			$fp 		= fopen($det_arr['path'],'r');
			$fsize		= filesize($det_arr['path']);
			if ($fsize>0)
			{
				$content	= fread($fp,$fsize);
			}
			fclose($fp);
		}
		return stripslashes($content);
	}
	// Function to generate Tree for category and products page
	function generate_tree($cat_id,$prod_id,$prefix='',$suffix='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$break_counter_at 	= 10000; // Variable to break the infinite loop
		$counter_val		= 1;
		$found				= false;
		$ret_str			= '';
		if ($prod_id==-1)
		{
			// find the details of current category category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id  
						FROM
							product_categories
						WHERE
							sites_site_id = $ecom_siteid
							AND category_id=$cat_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$cur_id		= $row_det['parent_id']; // setting the parent of current category as the next category to be fetched
				$ret_str	= stripslashes($row_det['category_name']);
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		if($cat_id==-1)
		{
			$cur_id = $_REQUEST['category_id'];// Getting the category id from $_REQUEST object
			// Get the detail of current product
			$sql_prod = "SELECT product_name,product_default_category_id
							FROM
								products
							WHERE
								product_id=$prod_id
								AND sites_site_id=$ecom_siteid";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				if ($cur_id=='')
				{
					$cur_id = $row_prod['product_default_category_id'];
				}
				$ret_str	= $prefix.stripslashes($row_prod['product_name']).$suffix; // place the name of the product in tree.
			}

		}
		while($cur_id>0 and $counter_val<$break_counter_at)
		{
			// find the details of category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id,category_hide 
						FROM
							product_categories
						WHERE
							sites_site_id = $ecom_siteid
							AND category_id=$cur_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$grp_id		= $row_det['default_catgroup_id']; // Get the default category group id
				// Building the tree node and saving it in a string variable.
				if($row_det['category_hide']==0)
					$ret_str	= "$prefix <a href='".url_category($cur_id,stripslashes($row_det['category_name']),1,0)."' title='".stripslashes($row_det['category_name'])."'>".stripslashes($row_det['category_name'])."</a> >> $suffix ".$ret_str;
				else
					$ret_str	= "$prefix ".stripslashes($row_det['category_name'])." >> $suffix ".$ret_str;	
				$cur_id		= $row_det['parent_id'];
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		$ret_str			= " $prefix<a href='".$ecom_selfhttp.$ecom_hostname."' title='$ecom_hostname'>Home</a> >> $suffix ".$ret_str;
		return $ret_str;
	}
	function generate_tree_menu($cat_id,$prod_id,$seperator='>>',$prefix='',$suffix='',$class='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$break_counter_at 	= 10000; // Variable to break the infinite loop
		$counter_val		= 1;
		$found				= false;
		$ret_str			= '';
		if ($prod_id==-1)
		{
			// find the details of current category category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id
						FROM
							product_categories
						WHERE
							sites_site_id = $ecom_siteid
							AND category_id=$cat_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$cur_id		= $row_det['parent_id']; // setting the parent of current category as the next category to be fetched
				$ret_str	= $prefix.'<span class="'.$class.' active">'.stripslashes($row_det['category_name']).'</span>'.$suffix;
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		if($cat_id==-1)
		{
			$cur_id = $_REQUEST['category_id'];// Getting the category id from $_REQUEST object
			// Get the detail of current product
			$sql_prod = "SELECT product_name,product_default_category_id
							FROM
								products
							WHERE
								product_id=$prod_id
								AND sites_site_id=$ecom_siteid";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				if ($cur_id=='')
				{
					$cur_id = $row_prod['product_default_category_id'];
				}
				$ret_str	= $prefix.'<span class="'.$class.' active">'.stripslashes($row_prod['product_name']).'</span>'.$suffix; // place the name of the product in tree.
			}

		}
		while($cur_id>0 and $counter_val<$break_counter_at)
		{
			// find the details of category
			$sql_det = "SELECT category_name,parent_id,default_catgroup_id,category_hide 
							FROM
								product_categories
							WHERE
								sites_site_id = $ecom_siteid
								AND category_id=$cur_id";
			$ret_det = $db->query($sql_det);
			if ($db->num_rows($ret_det))
			{
				$row_det 	= $db->fetch_array($ret_det);
				$grp_id		= $row_det['default_catgroup_id']; // Get the default category group id
				// Building the tree node and saving it in a string variable.
				if($row_det['category_hide']==0)
					$ret_str	= "$prefix <a class='".$class."' href='".url_category($cur_id,stripslashes($row_det['category_name']),1,0)."' title='".stripslashes($row_det['category_name'])."'>".stripslashes($row_det['category_name'])."</a> $seperator $suffix ".$ret_str;
				else
					$ret_str	= "$prefix <span class='".$class." active'>".stripslashes($row_det['category_name'])."</span> $seperator $suffix ".$ret_str;
				$cur_id		= $row_det['parent_id'];
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		}
		$ret_str			= " $prefix<a class='".$class."' href='".$ecom_selfhttp.$ecom_hostname."' title='$ecom_hostname'>Home</a> $seperator $suffix ".$ret_str;
		return $ret_str;
	}
	
		// Function to generate Tree for category and products page
	function generate_shop_tree($shopname,$prefix='',$suffix='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$shopname			= " $prefix<a href='".$ecom_selfhttp.$ecom_hostname."' title='$ecom_hostname'>Home</a> >> ".stripslashes($shopname).$suffix;
		return $shopname;

	}

	// ** Function to check whether a feature is active for the site
	function is_Feature_exists($modulename)
	{
		global $db,$ecom_siteid,$inlineSiteComponents,$consoleSiteComponents;
		global $ecom_selfhttp;
		if ($modulename)
		{
			if(in_array($modulename,$inlineSiteComponents))// check whether feature exists in site
			{
				return true; 
			}
			elseif (in_array($modulename,$consoleSiteComponents))// check whether feature exists in console
			{
				return true; 
			}	
			else
				return false; // case feature does not exists or disabled globally from super admin in features section
		}
		else // case if module is not found
			return false;
	}

	// Function to get the details of delivery to be shown in cart page
	function get_Delivery_Display_Details()
	{
		global $db,$ecom_siteid,$ecom_site_delivery_location_country_map;
		global $ecom_selfhttp;
		$sql_deliv = "SELECT a.deliverymethod_id,a.deliverymethod_name,a.deliverymethod_location_required,
							b.charge_split_delivery
						FROM
							delivery_methods a ,general_settings_site_delivery b
						WHERE
							a.deliverymethod_id=b.delivery_methods_delivery_id
							AND b.sites_site_id=$ecom_siteid
							AND a.deliverymethod_active=1
						LIMIT
							1";
		$ret_deliv = $db->query($sql_deliv);
		if ($db->num_rows($ret_deliv))
		{
			$row_deliv 							= $db->fetch_array($ret_deliv);
			$ret_arr['delivery_type'] 			= $row_deliv['deliverymethod_name'];
			$ret_arr['delivery_id'] 			= $row_deliv['deliverymethod_id'];
			$ret_arr['allow_split_delivery'] 	= $row_deliv['charge_split_delivery'];
			if ($row_deliv['deliverymethod_location_required']==1)
			{
				// Get the location details for current delivery method for current site
				$sql_loc = "SELECT location_id,location_name
							FROM
								delivery_site_location
							WHERE
								sites_site_id=$ecom_siteid
								AND delivery_methods_deliverymethod_id = ".$row_deliv['deliverymethod_id']."
							ORDER BY
								location_order";
				$ret_loc = $db->query($sql_loc);
				if ($db->num_rows($ret_loc))
				{
					$loc_arr[0] = ' -- Select --';
					while ($row_loc = $db->fetch_array($ret_loc))
					{
						$loc_arr[$row_loc['location_id']] = $row_loc['location_name'];
					}
					$ret_arr['locations'] = $loc_arr;
				}
			}
			$get_groups = true;
			$group_add_condition = '';
			// case if location is mapped with country
			if($ecom_site_delivery_location_country_map==1)
			{
				$session_id 	= Get_session_Id_from();
				$sql_cartdet 	= "SELECT location_id, delopt_det_id 
									FROM 
										cart_supportdetails 
									WHERE 
										session_id='".$session_id."'  
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
				$ret_cartdet = $db->query($sql_cartdet);
				if($db->num_rows($ret_cartdet)) 
					$row_cartdet = $db->fetch_array($ret_cartdet);
				if(!$row_cartdet['location_id'])
				{
					$get_groups = false;
				}
				else
				{
					// get the ids of delivery group which are active in selected location
					$sql_getgroupid = "SELECT distinct delivery_group_id 
											FROM 
												delivery_site_option_details 
											WHERE 
												delivery_site_location_location_id = ".$row_cartdet['location_id']." 
												AND delivery_group_active_in_location =1";
					$ret_getgroupid = $db->query($sql_getgroupid);
					if($db->num_rows($ret_getgroupid))
					{
						while ($row_getgroupid = $db->fetch_array($ret_getgroupid))
						{
							$grpget_arr[] = $row_getgroupid['delivery_group_id'];
						}
						$group_add_condition = " AND delivery_group_id IN (".implode(',',$grpget_arr).") ";	
					}
					else
						$group_add_condition = " AND delivery_group_id IN (-1) ";	
				}	
				
			}	
		   if($get_groups == true)
		   {	
				// Check whether any delivery method groups available
			   $sql_delgroup = "SELECT delivery_group_id,delivery_group_name
								FROM
									general_settings_site_delivery_group
								WHERE
									sites_site_id = $ecom_siteid
									AND delivery_group_hidden = 0 
									$group_add_condition 
								ORDER BY
									delivery_group_order";
				$ret_delgroup = $db->query($sql_delgroup);
				if ($db->num_rows($ret_delgroup))
				{
					while ($row_delgroup = $db->fetch_array($ret_delgroup))
					{
						$delopt_arr[$row_delgroup['delivery_group_id']] = stripslashes($row_delgroup['delivery_group_name']);
					}
					$ret_arr['del_groups'] = $delopt_arr;
				}
			}
		}
		return $ret_arr;
	}
	function get_cartSupportDetails($fld='*')
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$session_id 	= Get_session_Id_from();	// ** Get the session id for the current section
		$sql_cartsupp 	= "SELECT $fld
							FROM
								cart_supportdetails
							WHERE
								sites_site_id = $ecom_siteid
								AND session_id='".$session_id."'";
		$ret_cartsupp	= $db->query($sql_cartsupp);
		if($db->num_rows($ret_cartsupp))
		{
			$row_cartsupp = $db->fetch_array($ret_cartsupp);
			return $row_cartsupp;
		}
	}
	function check_Inpreorder($prodid,$cartid=0)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		if ($prodid)
		{
			$sql_prod = "SELECT product_instock_date
						FROM
							products
						WHERE
							product_id=$prodid
							AND sites_site_id=$ecom_siteid
							AND product_preorder_allowed='Y'
							AND product_total_preorder_allowed > 0
						LIMIT
							1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod 				= $db->fetch_array($ret_prod);
				$ret_arr['in_preorder']	= 'Y';
				$indate_arr				= explode("-",$row_prod['product_instock_date']);
				$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
				$ret_arr['in_date']		= $indate;
			}
			else
			{
				$set_preorder = 0;
				if($cartid)
				{
					$sql_prod = "SELECT product_alloworder_notinstock,product_order_outstock_instock_date 
							FROM
								products
							WHERE
								product_id=$prodid
								AND sites_site_id=$ecom_siteid
							LIMIT
								1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod 				= $db->fetch_array($ret_prod);
						if($row_prod['product_alloworder_notinstock']=='Y' and $row_prod['product_order_outstock_instock_date']!='' and $row_prod['product_order_outstock_instock_date']!='0000-00-00')
						{
							
							$var_arr			= get_cartvariables($cartid); // Calling function to get the variable details to an array
							// Check the stock details here
							$stock_arr			= check_stock_available($prodid,$var_arr);
							$stock				= $stock_arr['stock'];
							if($stock==0)
							{
								$ret_arr['in_preorder']	= 'Y';
								$indate_arr				= explode("-",$row_prod['product_order_outstock_instock_date']);
								$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
								$ret_arr['in_date']		= $indate;
								$set_preorder = 1;
							}
						}
					}
				}	
				if($set_preorder==0)
					$ret_arr['in_preorder']	= 'N';
			}	
		}
		return $ret_arr;
	}

function isProductCompareEnabled()
{
	global $inlineSiteComponents,$Settings_arr;
	global $ecom_selfhttp;
	if(in_array('mod_compare_products',$inlineSiteComponents) && $Settings_arr['product_compare_enable'] && $Settings_arr['product_compare_prodlist_enable']) {
		return true;
	}else{
		return false;
	}
}
function isProductCompareEnabledInProductDetails()
{
	global $inlineSiteComponents,$Settings_arr;
	global $ecom_selfhttp;
	if(in_array('mod_compare_products',$inlineSiteComponents) && $Settings_arr['product_compare_enable'] && $Settings_arr['product_compare_proddetail_enable']) {
		return true;
	}else{
		return false;
	}
}
function dislplayCompareButton($product_id,$prefix='',$suffix='',$ret=0,$div_class='compare_li')
{
	global $ecom_hostname;
	global $ecom_selfhttp;
	if($div_class=='')
		$div_class = 'compare_li';
	$confirm_message = "'Are you Sure You Want to Remove the selected product From the Compare List'";
	if(is_array($_SESSION['compare_products'])){
		if(in_array($product_id,$_SESSION['compare_products'])){ 
			$compare_link = '<div class="'.$div_class.'"><a href="#" class="addtocompare" onclick="document.common_compare_list.remove_compareid.value='.$product_id.'; if(confirm('.$confirm_message.')){ document.common_compare_list.submit()};"><img border="0" src="'.url_site_image("remove-compare.gif",1).'" alt="Remove from compare" title="remove from compare"/></a></div>';
		}else{
			$compare_link = '<div class="'.$div_class.'"><a href="#" class="addtocompare" onclick="addtoCompare('.$product_id.')"><img border="0" src="'.url_site_image("compare.gif",1).'" alt="Add to compare" title="Add to compare"/></a></div>';
		}
	}else{
	$compare_link = '<div class="'.$div_class.'"><a href="#" class="addtocompare" onclick="addtoCompare('.$product_id.')"><img border="0" src="'.url_site_image("compare.gif",1).'" alt="Add to compare" title="Add to compare"/></a></div>';
	}
	if($ret==0)
        {
            if($prefix!='')
                echo $prefix;
            echo  $compare_link;
            if($suffix!='')
                echo $suffix;
        }
        elseif($ret==1)
        {
            if($prefix!='')
                $rets =  $prefix;
            $rets .=  $compare_link;
            if($suffix!='')
                $rets .= $suffix;
            return $rets;
        }
}

function is_InputValid($input_arr)
{
	global $ecom_selfhttp;
	for($i=0;$i<count($input_arr);$i++)
	{
		if ($input_arr[$i] and !is_numeric($input_arr[$i]))
			return false;
	}
	return true;
}
function displayInvalidInput()
{
	global $ecom_hostname;
	global $ecom_selfhttp;
	echo "<script type='text/javascript'>window.location='".$ecom_selfhttp."$ecom_hostname/invalid_input.html'</script>";
	exit;
}

function Add_Enquire_det($mod = '')
{
 	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
 	global $ecom_selfhttp;
	$session_id = Get_session_Id_from();			// Get the session id for the current section
	$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
	$stock		= 0;
	// Get the variable and variable messages set for this product to an array
	foreach ($_REQUEST as $k=>$v)
	{
		if (substr($k,0,4) == 'var_')
		{
			$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
			$var_arr[$curid[1]] 	= trim($v);
		}
		elseif (substr($k,0,7) == 'varmsg_')
		{
			$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
			$varmsg_arr[$curid[1]] 	= trim($v);
		}
	}
	$ret_enqtid 		= Is_Enquire_already_Exists($var_arr,$varmsg_arr);
	$comb_arr			= get_combination_id($_REQUEST['fproduct_id'],$var_arr);
			// Redirecting user to the enquiry page
	if ($ret_enqtid==-1) // Case item does not exists in cart. So need to insert the details
	{
			// Make an entry to the cart and its related tables
			$insert_array									= array();
			$insert_array['sites_site_id']				= $ecom_siteid;
			$insert_array['session_id']					= $session_id;
			$insert_array['products_product_id']	= $_REQUEST['fproduct_id'];
			$insert_array['enquiry_date']				= 'now()';
			$insert_array['comb_id']					= $comb_arr['combid'];	
			$db->insert_from_array($insert_array,'product_enquiries_cart');
			$insert_cartid 									= $db->insert_id();

			// Making entries to the cart_variables table if any variables exists
			if (count($var_arr))
			{
				// Inserting the variable details cart_variables table
				foreach ($var_arr as $k=>$v)
				{
					$insert_array														= array();
					$insert_array['product_enquiries_cart_enquiry_id']	= $insert_cartid;
					$insert_array['product_variables_var_id']					= $k;
					$insert_array['product_variables_data_var_value_id']	= $v;
					$db->insert_from_array($insert_array,'product_enquiries_cart_vars');
				}
			}
			// Making entries to the cart_messages table if any messages exists
			if (count($varmsg_arr))
			{
				foreach ($varmsg_arr as $k=>$v)
				{
					// Get the type of current message from product_variable_messages table
					$sql_msg = "SELECT message_type
								FROM
									product_variable_messages
								WHERE
									message_id = $k
								LIMIT
									1";
					$ret_msg = $db->query($sql_msg);
					if ($db->num_rows($ret_msg))
					{
						$row_msg  = $db->fetch_array($ret_msg);
						$msg_type = $row_msg['message_type'];
					}
					$insert_array					= array();
					$insert_array['product_enquiries_cart_enquiry_id']	= $insert_cartid;
					$insert_array['message_id']									= $k;
					$insert_array['message_value']								= add_slash($v);
					$insert_array['message_type']								= $msg_type;
					$db->insert_from_array($insert_array,'product_enquiries_cart_messages');
				}
			}
			if($mod == '')
			{
			// Redirecting user to the cart page
			echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/enquiry.html' name='frm_subenquire'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/></form><script type='text/javascript'>document.frm_subenquire.submit();</script>";
			}
			//echo "<script type='text/javascript'>window.location = 'http://$ecom_hostname/enquiry.html';<script>";
	}
	//echo "<script type='text/javascript'>window.location = 'http://$ecom_hostname/enquiry.html';<script>";
			if($mod == '')
			{
			echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname/enquiry.html' name='frm_subenquire'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/></form><script type='text/javascript'>document.frm_subenquire.submit();</script>";
			}

}
//Function to Support Enquiry
function Is_Enquire_already_Exists($var_arr,$varmsg_arr)
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	$session_id = Get_session_Id_from();
	$sql_check 	= "SELECT enquiry_id
					FROM
						product_enquiries_cart
					WHERE
						sites_site_id=$ecom_siteid 
						AND products_product_id=".$_REQUEST['fproduct_id']."
						AND session_id='".$session_id."'";
	$ret_check = $db->query($sql_check);
	if ($db->num_rows($ret_check)==0) // case if product does not exists in cart
	{
		$enq_check_arr[$row_check['enquiry_id']] = -1;
	}
	else // case product exists in cart. So further checking is required to see if it is to be added as a new item or not
	{
		$enq_check_arr 	= array();
		while($row_check 	= $db->fetch_array($ret_check))
		{
			$enq_check_arr[$row_check['enquiry_id']] = 0;
			// ====================================================
			// Check whether any variable exist for current product
			// ====================================================
			$sql_var = "SELECT product_variables_var_id,product_variables_data_var_value_id
						FROM
							product_enquiries_cart_vars
						WHERE
							product_enquiries_cart_enquiry_id = ".$row_check['enquiry_id'];
			$ret_var = $db->query($sql_var);
			if ($db->num_rows($ret_var))
			{
				// Check whether the number of variables stored and number of variables passed as same
				if ($db->num_rows($ret_var)==count($var_arr))// Case if the count are same. Then the values should be compared
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						if ($row_var['product_variables_data_var_value_id']!=$var_arr[$row_var['product_variables_var_id']])
							$enq_check_arr[$row_check['enquiry_id']] = -1;
					}
				}
				else
				{
					$enq_check_arr[$row_check['enquiry_id']] = -1;// case the product is to be treated as a new product
				}
			}

			// ======================================================
			// Check whether any variable message exists for product
			// ======================================================
			$sql_varmsg = "SELECT message_id,message_value
						FROM
							product_enquiries_cart_messages
						WHERE
							product_enquiries_cart_enquiry_id = ".$row_check['enquiry_id'];
			$ret_varmsg = $db->query($sql_varmsg);
			if ($db->num_rows($ret_varmsg))
			{
				// Check whether the number of variables stored and number of variables passed as same
				if ($db->num_rows($ret_varmsg)==count($varmsg_arr))// Case if the count are same. Then the values should be compared
				{
					while ($row_varmsg = $db->fetch_array($ret_varmsg))
					{
						if (strtolower($row_varmsg['message_value'])!=strtolower($varmsg_arr[$row_varmsg['message_id']]))
							$enq_check_arr[$row_check['enquiry_id']] = -1;
					}
				}
				else
				{
					$enq_check_arr[$row_check['enquiry_id']] = -1;// case the product is to be treated as a new product
				}
			}
		}
	}
	$returned_enqid = -1;
	foreach ($enq_check_arr as $k=>$v)
	{
		if ($v==0)
		{
			$returned_enqid = $k;
		}
	}
	// If reached upto here then the item already exists in cart. So return the cart id to the calling area
	return $returned_enqid;
}

	/* Function to decide the messages to be shown in the show cart page of the site*/
function get_CartMessages($code,$ret_iserror=0)
{
	global $db,$ecom_siteid,$Captions_arr;
	global $ecom_selfhttp;
	$Captions_arr['CART'] 	= getCaptions('CART');
	$ret_err = 0;
	
	// Section which builds the messages to be shown in the cart
		switch($code)
		{
			case '#a_gwrapopt':
			case '#a_gwrap':
				$cart_alert = $Captions_arr['CART']['CART_GIFTWRAP_OPT_SAVED_SUCCESS'];
			break;
			case '#a_deliv':
				$cart_alert = $Captions_arr['CART']['CART_DELIVERY_SAVED_SUCCESS'];
			break;
			case '#upd':
				$cart_alert = $Captions_arr['CART']['CART_QTY_UPD_SUCCESS'];
			break;
			case '#rem':
				$cart_alert = $Captions_arr['CART']['CART_PROD_MOVED_SUCCESS'];
			break;
			case '#a_pay':
				$cart_alert = $Captions_arr['CART']['CART_PAYMENT_OPT_SUCCESS'];
			break;
			case '#incomp':
				$cart_alert = $Captions_arr['CART']['CART_FILL_IN_VALUES'];
				$ret_err	= 1;
			break;
			case 'PROM_LOGIN_REQ':
				$cart_alert = $Captions_arr['CART']['CART_LOGIN_USE'];
				$ret_err	= 1;
				//$add_opt
			break;
			case 'CODE_NOT_FOUND':
				$cart_alert = $Captions_arr['CART']['CART_INV_CODE'];
				$ret_err	= 1;
			break;
			case 'GIFT_LOGIN_REQ':
				$cart_alert = $Captions_arr['CART']['CART_LOGIN_USE'];
				$ret_err	= 1;
			break;
			case 'PROM_ALREADY_USED':
				$cart_alert = $Captions_arr['CART']['CART_PROM_USED'];
				$ret_err	= 1;
			break;
			case 'PAYON_CREDIT_NOT_SUFFICIENT':
				$cart_alert = $Captions_arr['CART']['PAYON_CREDIT_NOT_SUFFICIENT'];
				$ret_err	= 1;
			break;
			case 'PAYON_CREDIT_NOT_AUTH':
				$cart_alert = $Captions_arr['CART']['PAYON_CREDIT_NOT_AUTH'];
				$ret_err	= 1;
			break;
			case 'PAYON_CREDIT_NOT_LOGGEDIN':
				$cart_alert = $Captions_arr['CART']['PAYON_CREDIT_NOT_LOGGEDIN'];
			break;
			case 'CART_PROM_INACTIVE':
			case 'PROM_INACTIVE':
				$cart_alert = $Captions_arr['CART']['CART_PROM_INACTIVE'];
				$ret_err	= 1;
			break;
			case 'PROM_ALREADY_USED_CUSTOMER':
				$cart_alert = $Captions_arr['CART']['PROM_ALREADY_USED_CUSTOMER'];
				$ret_err	= 1;
			break;
			case 'PROM_CUST_NO_PROD_DISC':
			case 'CART_PROM_CUST_NO_PROD_DISC':
				$cart_alert = $Captions_arr['CART']['PROM_CUST_NO_PROD_DISC'];
				$ret_err	= 1;
			break;
			case 'PROM_MAXLIMIT_REACHED':
				$cart_alert = $Captions_arr['CART']['PROM_MAXLIMIT_REACHED'];
				$ret_err	= 1;
			break;
			case 'PROM_CUST_MAXLIMIT_REACHED':
			case 'CART_PROM_MAXLIMIT_REACHED':
				$cart_alert = $Captions_arr['CART']['PROM_CUST_MAXLIMIT_REACHED'];
				$ret_err	= 1;
			break;
			case 'CART_STOCK_INSUFF_ADJ_QTY':
				$cart_alert = $Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY'];
				$ret_err	= 1;
			break;
			case 'CART_PAYPAL_EXP_NO_ADDRESS_RET':
				$cart_alert = $Captions_arr['CART']['CART_PAYPAL_EXP_NO_ADDRESS_RET'];
				$ret_err	= 1;
			break;
			case 'CART_PRICE_PROMISE_SAME_ITEM_EXISTS':
				$cart_alert = $Captions_arr['CART']['CART_PRICE_PROMISE_SAME_ITEM_EXISTS'];
				$ret_err	= 1;
			break;
			case 'CART_PROM_CUST_NO_PROD_DISC':
				$cart_alert = $Captions_arr['CART']['CART_PROM_CUST_NO_PROD_DISC'];
				$ret_err	= 1;
			break;
		}
		if ($ret_iserror==1)
		{
			$ret_arr['msg'] 	= $cart_alert;
			$ret_arr['err']		= $ret_err;
			return $ret_arr;
		}
		else
		{
			return $cart_alert;
		}	
}

function get_Checkoutlink($display=0,$msg='')
{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_common_settings;
	global $ecom_selfhttp;
	$sessid					= Get_session_Id_from(); 	// Get the id for current session
	$rq 					= $_REQUEST['req']; // find the value of req to identify the currently viewing section
	if($_REQUEST['erm']==1 or $_REQUEST['pret']==1)
		$paypal_express			= 1;
	else
		$paypal_express			= 0;
	$ret_arr 				= array();
	$secure_required 		= false; // Initializing the variable which decides whether secure area is required or not
	$retcart					= false; // Decide whether cart url is to be returned on clicking the checkout link
	$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
	// check whether relevant details are set in cart_supportdetails table for current session
	$sql_sel = "SELECT location_id,paytype_id,paymethod_id
					FROM
						cart_supportdetails
					WHERE
						session_id ='".$sessid."'
						AND sites_site_id = $ecom_siteid 
					LIMIT
						1";
	$ret_sel = $db->query($sql_sel);
	if ($db->num_rows($ret_sel))
	{
		$row_sel = $db->fetch_array($ret_sel);
	}
	if($msg!='') // in case if error message is there
		$retcart = true;
	if($retcart==false)
	{	
		// Check whether the delivery method is linked with location
		if($ecom_common_settings['delivery']['deliverymethod_location_required']==1)
		{
			if($row_sel['location_id']==0)
				$retcart = true;
		}		
			/*$sql_del = "SELECT a.deliverymethod_id,a.deliverymethod_text,a.deliverymethod_location_required
							FROM
								delivery_methods a,general_settings_site_delivery b
							WHERE
								b.sites_site_id = $ecom_siteid
								AND a.deliverymethod_id=b.delivery_methods_delivery_id
							LIMIT
								1";
			$ret_del = $db->query($sql_del);
			if ($db->num_rows($ret_del))
			{
				$row_del = $db->fetch_array($ret_del);
				// Check whether the location is to be selected for delivery method
				if ($row_del['deliverymethod_location_required']==1)
				{
					if($row_sel['location_id']==0)
						$retcart = true;
				}
			}
			else
				$retcart = true;*/
		}
		if ($retcart==false and $paypal_express!=1)
		{
			// Check whether there exists more than one payment types for this site
			/*$sql_paytypes 	= "SELECT count(paytype_forsites_id)
								FROM
									payment_types_forsites
								WHERE
									sites_site_id = $ecom_siteid
									AND paytype_forsites_active=1
									AND paytype_forsites_userdisabled=0";
			$ret_paytypes = $db->query($sql_paytypes);
			list($tot_paytype) = $db->fetch_array($ret_paytypes);*/
			$tot_paytype = $ecom_common_settings['total_paytypes'];
			if($tot_paytype>1)
			{
				// Check whether atleast one product in cart
				if (count($cartData['products'])>0 and $cartData["totals"]["bonus_price"]>0)
				{
					if ($row_sel['paytype_id']==0)
						$retcart = true;
				}		
				elseif (count($cartData['products'])==0 )
				{
					if ($row_sel['paytype_id']==0)
						$retcart = true;
				}
			}
		}

	if ($retcart==false and  $paypal_express!=1)
	{
		$google_prev_req 				= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_preview_req'];
		$google_recommended		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_google_recommended'];
		if($google_recommended ==0) // case if google checkout is set to work in the way google recommend
			$tot_paymethod = $ecom_common_settings['total_paymethods_no_googlecnt']; // total number of payment methods with out google checkout
		else
		{
			$tot_paymethod = $ecom_common_settings['total_paymethods_cnt']; // total number of payment methods including` google checkout
		}	
		//echo $tot_paymethod;
		//exit;
		if($tot_paymethod>1)
		{
			if($row_sel['paymethod_id']==0)
			{
				if($ecom_common_settings['paytypeId'][$row_sel['paytype_id']]=='credit_card')
					$retcart = true;
			}
			else
			{
				// check whether the selected payment method required secured area
				if ($ecom_common_settings['paymethodId'][$row_sel['paymethod_id']]['paymethod_secured_req']==1)
					$secure_required = true;
			}
		}
		elseif ($tot_paymethod==1)
		{
			//if ($row_creditcard['paytype_code']=='credit_card')// if the selected payment type is credit_card, then check whether paymethod available is protx or self
			if($ecom_common_settings['paytypeId'][$row_sel['paytype_id']]['paytype_code']=='credit_card')
			{
				// Check whether secured area is required
				foreach ($ecom_common_settings['paymethodId'] as $k=>$v)
				{
					if($v['paymethod_secured_req']==1)
						$secure_required = true;
				}
			}	
			
		}
	}
	//if($ecom_siteid == 70 or $ecom_siteid==77) // overriding for www.nationwidefireextinguishers.co.uk, always show secure page for checkout section
	if($ecom_siteid==90 or $ecom_siteid==77 or $ecom_siteid==104 or $ecom_siteid==108  or $ecom_siteid==83) // overriding for healthlab, shootuk, discount-mobility, puregusto, discountcateringdirect.co.uk, asll.co.uk always show secure page for checkout section
	{
		$secure_required = true;
	}
	if($retcart==false or $paypal_express==1) // case when checkout url is to be returned .. override the checking in case of payme express
	{
		if ($secure_required) // case if https is required
		{
			if ($display==1)
			{
				$ret_arr['url'] 	= url_protected('index.php?req=cart&cart_mod=show_checkout',1);
				$ret_arr['type'] 	= 'checkout';
				$ret_arr['sec'] 	= true;
				return $ret_arr;	
			}
			else
			{
				url_protected('index.php?req=cart&cart_mod=show_checkout',0);
			}
		}
		else // case https not required
		{
			if ($display==1)
			{
				$ret_arr['url'] 		= $ecom_selfhttp.$ecom_hostname.'/checkout.html';
				$ret_arr['type']	= 'checkout';
				$ret_arr['sec'] 	= false;
				return $ret_arr;
			}
			else
				echo $ecom_selfhttp.$ecom_hostname.'/checkout.html'; 
		}
	}
	else // case when cart url is to be returned
	{
		if ($display==1)
		{
			$ret_arr['url'] 	= $ecom_selfhttp.$ecom_hostname.'/cart.html';
			$ret_arr['type'] 	= 'cart';
			return $ret_arr;
		}
		else
			echo $ecom_selfhttp.$ecom_hostname.'/cart.html';
	}

}
function get_Checkoutlink_OLDS($display=0,$msg='')
{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_common_settings;
	global $ecom_selfhttp;
	$sessid					= Get_session_Id_from(); 	// Get the id for current session
	$rq 						= $_REQUEST['req']; // find the value of req to identify the currently viewing section
	$ret_arr 				= array();
	$secure_required 	= false; // Initializing the variable which decides whether secure area is required or not
	$retcart					= false; // Decide whether cart url is to be returned on clicking the checkout link
	// check whether relevant details are set in cart_supportdetails table for current session
	$sql_sel = "SELECT location_id,paytype_id,paymethod_id
					FROM
						cart_supportdetails
					WHERE
						session_id ='".$sessid."'
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_sel = $db->query($sql_sel);
	if ($db->num_rows($ret_sel))
	{
		$row_sel = $db->fetch_array($ret_sel);
	}
	if($msg!='') // in case if error message is there
		$retcart = true;
	if($retcart==false)
	{	
		// Get the delivery method set for the current site
		$sql_del = "SELECT a.deliverymethod_id,a.deliverymethod_text,a.deliverymethod_location_required
						FROM
							delivery_methods a,general_settings_site_delivery b
						WHERE
							b.sites_site_id = $ecom_siteid
							AND a.deliverymethod_id=b.delivery_methods_delivery_id
						LIMIT
							1";
		$ret_del = $db->query($sql_del);
		if ($db->num_rows($ret_del))
		{
			$row_del = $db->fetch_array($ret_del);
			// Check whether the location is to be selected for delivery method
			if ($row_del['deliverymethod_location_required']==1)
			{
				if($row_sel['location_id']==0)
					$retcart = true;
			}
		}
		else
			$retcart = true;
		}
		if ($retcart==false)
		{
			// Check whether there exists more than one payment types for this site
			$sql_paytypes 	= "SELECT count(paytype_forsites_id)
								FROM
									payment_types_forsites
								WHERE
									sites_site_id = $ecom_siteid
									AND paytype_forsites_active=1
									AND paytype_forsites_userdisabled=0";
			$ret_paytypes = $db->query($sql_paytypes);
			list($tot_paytype) = $db->fetch_array($ret_paytypes);
			if($tot_paytype>1)
			{
				if ($row_sel['paytype_id']==0)
					$retcart = true;
			}
		}

	if ($retcart==false)
	{
		if ($row_sel['paytype_id'])
		{
			// Check whether the payment type selected is of type credit card
			$sql_creditcard = "SELECT paytype_code
										FROM
											payment_types
										WHERE
											paytype_id=".$row_sel['paytype_id']."
										LIMIT
											1";
			$ret_creditcard = $db->query($sql_creditcard);
			if ($db->num_rows($ret_creditcard))
			{
				$row_creditcard = $db->fetch_array($ret_creditcard);
			}
		}
		// Get the paymethod_id FOR google checkout
		$sql_google = "SELECT paymethod_id 
							FROM 
								payment_methods 
							WHERE 
								paymethod_key='GOOGLE_CHECKOUT' 
							LIMIT 
								1";
		$ret_google = $db->query($sql_google);
		if ($db->num_rows($ret_google))
		{
			$row_google = $db->fetch_array($ret_google);
			$add_condition = " AND payment_methods_paymethod_id<>".$row_google['paymethod_id'].' ';	
		}
		$sql_paymethods 	= "SELECT count(payment_methods_forsites_id)
									FROM
										payment_methods_forsites
									WHERE
										sites_site_id = $ecom_siteid 
										$add_condition";
		$ret_paymethods 	= $db->query($sql_paymethods);
		list($tot_paymethod) = $db->fetch_array($ret_paymethods);
		if($tot_paymethod>1)
		{
			if($row_sel['paymethod_id']==0)
			{
				
				if ($row_creditcard['paytype_code']=='credit_card')
					$retcart = true;
			}
			else
			{
				$sql_paymethod = "SELECT paymethod_id
									FROM
										payment_methods
									WHERE
										paymethod_id=".$row_sel['paymethod_id']."
										AND paymethod_secured_req =1 
										AND payment_hide=0
									LIMIT
										1";
			}
		}
		elseif ($tot_paymethod==1)
		{
			if ($row_creditcard['paytype_code']=='credit_card')// if the selected payment type is credit_card, then check whether paymethod available is protx or self
			{
				// Check whether secured area is required
				$sql_paymethod = "SELECT a.paymethod_id
									FROM
										payment_methods a, payment_methods_forsites b
									WHERE
										sites_site_id = $ecom_siteid
										AND a.paymethod_id = b.payment_methods_paymethod_id
										AND a.paymethod_secured_req=1 
										AND payment_hide = 0
									LIMIT
										1";
			}	
			
		}
		// If the sql variable is not blank fetch it from db to check the payment method and verify whether https is required
		if ($sql_paymethod!='')
		{
			$ret_paymethod = $db->query($sql_paymethod);
			if ($db->num_rows($ret_paymethod)) // If num rows exists then mark it as secured required
				$secure_required = true;
		}
	}
	if($retcart==false) // case when checkout url is to be returned
	{
		if ($secure_required) // case if https is required
		{
			if ($display==1)
			{
				$ret_arr['url'] 		= url_protected('index.php?req=cart&cart_mod=show_checkout',1);
				$ret_arr['type'] 	= 'checkout';
				$ret_arr['sec'] 	= true;
				return $ret_arr;	
			}
			else
			{
				url_protected('index.php?req=cart&cart_mod=show_checkout',0);
			}
		}
		else // case https not required
		{
			if ($display==1)
			{
				$ret_arr['url'] 		= $ecom_selfhttp.$ecom_hostname.'/checkout.html';
				$ret_arr['type']	= 'checkout';
				$ret_arr['sec'] 	= false;
				return $ret_arr;
			}
			else
				echo $ecom_selfhttp.$ecom_hostname.'/checkout.html';
		}
	}
	else // case when cart url is to be returned
	{
		if ($display==1)
		{
			$ret_arr['url'] 	= $ecom_selfhttp.$ecom_hostname.'/cart.html';
			$ret_arr['type'] 	= 'cart';
			return $ret_arr;
		}
		else
			echo $ecom_selfhttp.$ecom_hostname.'/cart.html';
	}

}
function get_buyGiftVoucherURL()
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$sec_req 					= false;
	$cust_id						= get_session_var("ecom_login_customer"); // Get the customer id from session
	if (!$cust_id) // case if not logged in
	{
		$pay_add_condition = ' AND a.paytype_logintouse = 0';
	}
	else // case if logged in
	{
		// Check whether pay_on_account is active for current customer
		$sql_custcheck = "SELECT customer_payonaccount_status 
										FROM 
											customers 
										WHERE 
											customer_id = $cust_id 
											AND sites_site_id = $ecom_siteid 
											AND customer_payonaccount_status ='ACTIVE' 
										LIMIT 
											1";
		$ret_custcheck = $db->query($sql_custcheck);
		if ($db->num_rows($ret_custcheck)) // case if payon account is active for current customer
		{
			$row_custcheck 						= $db->fetch_array($ret_custcheck);
		}
		else
		{
			$pay_add_condition				= " AND a.paytype_code <> 'pay_on_account' ";
			$payonaccount_remlimit = 0;
		}	
	}
	// Check whether there exists more than one payment types for this site
	$sql_paytypes 	= "SELECT a.paytype_logintouse,a.paytype_code,b.paytype_forsites_id
									FROM
										payment_types a,payment_types_forsites b
									WHERE
										a.paytype_id=b.paytype_id 
										AND b.sites_site_id = $ecom_siteid 
										AND a.paytype_showinvoucher=1 
										AND paytype_forsites_active=1 
										$pay_add_condition 
										AND paytype_forsites_userdisabled=0";
	$ret_paytypes = $db->query($sql_paytypes);
	if($db->num_rows($ret_paytypes)==1) // if only one payment type exists
	{
		// Check whether payment type is credit card
		$row_paytypes = $db->fetch_array($ret_paytypes);
		// Check whether payment type is credit card 
		if ($row_paytypes['paytype_code']=='credit_card')
		{
			// Get the list of payment methods for this sites
			$sql_pay_methods = "SELECT a.paymethod_id,a.paymethod_key,a.paymethod_takecarddetails,a.paymethod_secured_req 
												FROM 
													payment_methods a,payment_methods_forsites b 
												WHERE 
													a.paymethod_id = b.payment_methods_paymethod_id 
													AND b.sites_site_id = $ecom_siteid 
													AND a.payment_hide=0 
													AND a.paymethod_key <> 'GOOGLE_CHECKOUT'";
			$ret_pay_methods = $db->query($sql_pay_methods);
			if ($db->num_rows($ret_pay_methods)==1)
			{
				$row_pay_methods = $db->fetch_array($ret_pay_methods);
				if($row_pay_methods['paymethod_secured_req']==1) // if secured is req for current payment method
					$sec_req = true;
			}				
			else
			{
				$atleast_one = false;
				$pay_cnt = $db->num_rows($ret_pay_methods);
				// Check whether there exists atleast one pay method with no secured required
				while ($row_pay_methods = $db->fetch_array($ret_pay_methods))
				{
					if ($row_pay_methods['paymethod_secured_req'] ==0)
					{
						$atleast_one = true;
					}
				}
				if ($atleast_one ==false and $pay_cnt>0) // if all the pay methods set for current site req secured area
					$sec_req = true;
			}						
		}
	}
	if ($sec_req ==true)
		return url_protected('index.php?req=voucher&action_purpose=buy',1);
	else
		return url_link('buy_voucher.html',1);
}

function check_Paymethod_SSL_Req_Status($mod='gift')
{
	global $db,$ecom_siteid,$ecom_common_settings;
	global $ecom_selfhttp;
	$sec_req 					= false;
	if($mod=='gift')
	{
		$more_condition = "AND a.paymethod_key<>'GOOGLE_CHECKOUT' ";
	}
	elseif ($mod=='payonaccount')
	{
		if($ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['paymethod_key'] == "GOOGLE_CHECKOUT")
		{
			$google_recommended		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_google_recommended'];
			if($google_recommended ==0) // case if google checkout is set to work in the way google recommend
				$more_condition = " AND paymethod_key<>'GOOGLE_CHECKOUT' ";
			else
				$more_condition = '';
		}	
	}
	// Get the list of payment methods for this sites
	$sql_pay_methods = "SELECT a.paymethod_id,a.paymethod_key,a.paymethod_takecarddetails,a.paymethod_secured_req 
										FROM 
											payment_methods a,payment_methods_forsites b 
										WHERE 
											a.paymethod_id = b.payment_methods_paymethod_id 
											AND b.sites_site_id = $ecom_siteid 
											AND a.payment_hide=0 
											AND b.payment_method_sites_active = 1 
											$more_condition";
	$ret_pay_methods = $db->query($sql_pay_methods);
	if ($db->num_rows($ret_pay_methods)==1)
	{
		$row_pay_methods = $db->fetch_array($ret_pay_methods);
		if($row_pay_methods['paymethod_secured_req']==1) // if secured is req for current payment method
			$sec_req = true;
	}				
	else
	{
		if ($db->num_rows($ret_paymethod)>1)
		{
			$atleast_one = false;
			// Check whether there exists atleast one pay method with no secured required
			while ($row_pay_methods = $db->fetch_array($ret_pay_methods))
			{
				if ($row_pay_methods['paymethod_secured_req'] ==0)
				{
					$atleast_one = true;
				}
			}
			if ($atleast_one ==false) // if all the pay methods set for current site req secured area
				$sec_req = true;
		}
		else // case if no payment method exists
		{
			$sec_req = false;
		}		
	}		
	return $sec_req;				
}

// Function to return the static fields to be used in the checkout page
function get_Field($key='',$saved_checkoutvals,$customer_arr,$cur_form='',$class_array=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr,$ecom_load_mobile_theme;
	global $ecom_selfhttp;
	if($ecom_load_mobile_theme)
		$box_size = 20;
	else
		$box_size = 30;
	// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
	// If logged in then show the default values for billing address
	if (count($saved_checkoutvals)==0)
	{
		// Check whether logged in
		if(get_session_var('ecom_login_customer'))
		{
			if($cur_form=='frm_buygiftvoucher') // case of // gift voucher buy section
			{
				$sql_cust = "SELECT customer_title,customer_compname,customer_fname,customer_mname
									customer_surname,customer_buildingname,customer_streetname,
									customer_towncity,customer_statecounty,country_id,
									customer_postcode,customer_phone,customer_mobile,
									customer_fax,customer_email_7503 
								FROM
									customers
								WHERE
									customer_id =".get_session_var('ecom_login_customer')."
								LIMIT
									1";
				$ret_cust = $db->query($sql_cust);
				if ($db->num_rows($ret_cust))
					$row_cust = $db->fetch_array($ret_cust);

				// Set the values to be shown for voucher fields
				$saved_checkoutvals['checkout_vouchertitle'] 			= $row_cust['customer_title'];
				$saved_checkoutvals['checkout_vouchercomp_name'] 		= $row_cust['customer_compname'];
				$saved_checkoutvals['checkout_voucherfname'] 			= $row_cust['customer_fname'];
				$saved_checkoutvals['checkout_vouchermname'] 			= $row_cust['customer_mname'];
				$saved_checkoutvals['checkout_vouchersurname'] 			= $row_cust['customer_surname'];
				$saved_checkoutvals['checkout_voucherbuilding']			= $row_cust['customer_buildingname'];
				$saved_checkoutvals['checkout_voucherstreet'] 			= $row_cust['customer_streetname'];
				$saved_checkoutvals['checkout_vouchercity'] 			= $row_cust['customer_towncity'];
				$saved_checkoutvals['checkout_voucherstate'] 			= $row_cust['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 				= $row_cust['country_id'];
				$saved_checkoutvals['checkout_voucherzipcode'] 			= $row_cust['customer_postcode'];
				$saved_checkoutvals['checkout_voucherphone'] 			= $row_cust['customer_phone'];
				$saved_checkoutvals['checkout_vouchermobile'] 			= $row_cust['customer_mobile'];
				$saved_checkoutvals['checkout_voucherfax'] 				= $row_cust['customer_fax'];
				$saved_checkoutvals['checkout_voucheremail'] 			= $row_cust['customer_email_7503'];
			}
			else
			{
				// Set the values to be shown for billing address fields
				$saved_checkoutvals['checkout_title'] 				= $customer_arr['customer_title'];
				$saved_checkoutvals['checkout_comp_name'] 			= $customer_arr['customer_compname'];
				$saved_checkoutvals['checkout_fname'] 				= $customer_arr['customer_fname'];
				$saved_checkoutvals['checkout_mname'] 				= $customer_arr['customer_mname'];
				$saved_checkoutvals['checkout_surname'] 			= $customer_arr['customer_surname'];
				$saved_checkoutvals['checkout_building']			= $customer_arr['customer_buildingname'];
				$saved_checkoutvals['checkout_street'] 				= $customer_arr['customer_streetname'];
				$saved_checkoutvals['checkout_city'] 				= $customer_arr['customer_towncity'];
				$saved_checkoutvals['checkout_state'] 				= $customer_arr['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 			= $customer_arr['country_id'];
				$saved_checkoutvals['checkout_zipcode'] 			= $customer_arr['customer_postcode'];
				$saved_checkoutvals['checkout_phone'] 				= $customer_arr['customer_phone'];
				$saved_checkoutvals['checkout_mobile'] 				= $customer_arr['customer_mobile'];
				$saved_checkoutvals['checkout_fax'] 				= $customer_arr['customer_fax'];
				$saved_checkoutvals['checkout_email'] 				= $customer_arr['customer_email_7503'];
			}

			// Get the name of state
			/*if ($saved_checkoutvals['checkout_state']!=0)
			{

				$sql_state = "SELECT state_name
								FROM
									general_settings_site_state
								WHERE
									state_id=".$saved_checkoutvals['checkout_state']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_state = $db->query($sql_state);
				if ($db->num_rows($ret_state))
				{
					$row_state = $db->fetch_array($ret_state);
					$saved_checkoutvals['checkout_state'] = stripslashes($row_state['state_name']);
				}
				else
					$saved_checkoutvals['checkout_state'] = '';
			}
			else
				$saved_checkoutvals['checkout_state'] = '';*/

			// Get the name of country
			/*if ($saved_checkoutvals['checkout_country']!=0)
			{

				$sql_country = "SELECT country_name
								FROM
									general_settings_site_country
								WHERE
									country_id=".$saved_checkoutvals['checkout_country']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_country = $db->query($sql_country);
				if ($db->num_rows($ret_country))
				{
					$row_country = $db->fetch_array($ret_country);
					$saved_checkoutvals['checkout_country'] = stripslashes($row_country['country_name']);
				}
				else
				 $saved_checkoutvals['checkout_country']='';
			}
			else
				$saved_checkoutvals['checkout_country'] = '';
				
				*/

		}
	}
	$txt_cls 		= ($class_array['txtbox_cls'])?'class="'.$class_array['txtbox_cls'].'"':'';
	$txtarea_cls 	= ($class_array['txtarea_cls'])?'class="'.$class_array['txtarea_cls'].'"':'';
	$select_cls 	= ($class_array['select_cls'])?'class="'.$class_array['select_cls'].'"':'';
	
	$txt_onblur 		= ($class_array['onblur'])?$class_array['onblur']:'';

	// Deciding which is the field to be displayed
	switch($key)
	{
		case 'checkout_title':
		case 'checkout_vouchertitle':
		case 'checkoutdelivery_title':
		case 'customer_title':
			$ret = '<select name="'.$key.'" id="'.$key.'"'.$select_cls.'>';
			$sel = ($saved_checkoutvals[$key]=='Mr.')?'selected':'';
			$ret .='<option value="Mr." '.$sel.'>Mr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Mrs.')?'selected':'';
			$ret .='<option value="Mrs." '.$sel.'>Mrs.</option>';
			$sel = ($saved_checkoutvals[$key]=='Miss.')?'selected':'';
			$ret .='<option value="Miss." '.$sel.'>Miss.</option>';
			$sel = ($saved_checkoutvals[$key]=='Ms.')?'selected':'';
			$ret .='<option value="Ms." '.$sel.'>Ms.</option>';
			$sel = ($saved_checkoutvals[$key]=='M/s.')?'selected':'';
			$ret .='<option value="M/s." '.$sel.'>M/s.</option>';
			$sel = ($saved_checkoutvals[$key]=='Dr.')?'selected':'';
			$ret .='<option value="Dr." '.$sel.'>Dr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Sir.')?'selected':'';
			$ret .='<option value="Sir." '.$sel.'>Sir.</option>';
			$sel = ($saved_checkoutvals[$key]=='Rev.')?'selected':'';
			$ret .='<option value="Rev." '.$sel.'>Rev.</option>';
			$ret .='</select>';
		break;
		case 'checkout_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_country" id="checkout_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_country'])
						$saved_checkoutvals['checkout_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}	
							
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'checkoutdelivery_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order  	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkoutdelivery_country" id="checkoutdelivery_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!trim($saved_checkoutvals['checkoutdelivery_country']))
						$saved_checkoutvals['checkoutdelivery_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkoutdelivery_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}
							
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}	
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'checkout_vouchercountry':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_vouchercountry" id="checkout_vouchercountry">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_vouchercountry'])
						$saved_checkoutvals['checkout_vouchercountry'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_vouchercountry'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'cbo_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="cbo_country" id="cbo_country">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['cbo_country'])
						$saved_checkoutvals['cbo_country'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['cbo_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case 'checkout_comp_name':
		case 'checkout_fname':
		case 'checkout_mname':
		case 'checkout_surname':
		case 'checkout_building':
		case 'checkout_address2':
		case 'checkout_street':
		case 'checkout_city':
		case 'checkout_state':
		case 'checkout_zipcode':
		case 'checkout_phone':
		case 'checkout_mobile':
		case 'checkout_fax':
		case 'checkout_email':
		

		case 'checkout_vouchercomp_name':
		case 'checkout_voucherfname':
		case 'checkout_vouchermname':
		case 'checkout_vouchersurname':
		case 'checkout_voucherbuilding':
		case 'checkout_voucherstreet':
		case 'checkout_vouchercity':
		case 'checkout_voucherstate':
		case 'checkout_voucherzipcode':
		case 'checkout_voucherphone':
		case 'checkout_vouchermobile':
		case 'checkout_voucherfax':
		case 'checkout_voucheremail':
		

		case 'checkoutdelivery_comp_name':
		case 'checkoutdelivery_fname':
		case 'checkoutdelivery_mname':
		case 'checkoutdelivery_surname':
		case 'checkoutdelivery_building':
		case 'checkoutdelivery_address2':
		case 'checkoutdelivery_street':
		case 'checkoutdelivery_city':
		case 'checkoutdelivery_state':
		case 'checkoutdelivery_zipcode':
		case 'checkoutdelivery_phone':
		case 'checkoutdelivery_mobile':
		case 'checkoutdelivery_fax':
		case 'checkoutdelivery_email':
		
		case 'customer_fname':
		case 'customer_mname':
		case 'customer_surname':
		case 'customer_position':
		case 'customer_buildingname':
		case 'customer_streetname':
		case 'customer_towncity':
		case 'cbo_state':
		case 'customer_postcode':
		/*case 'cbo_country':*/
		case 'customer_phone':
		case 'customer_mobile':
		case 'customer_fax':
		
		case 'customer_compname':
		case 'customer_compregno':
		case 'customer_compvatregno':

		case 'checkoutpay_nameoncard':

		case 'checkoutchq_number':
		case 'checkoutchq_bankname':

		case 'checkoutpay_cardnumber':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case 'checkoutpay_issuenumber':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case 'checkoutpay_securitycode':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case 'customer_comptype':
			$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			$sql = "SELECT comptype_id,comptype_name
						FROM 
							general_settings_sites_customer_company_types 
						WHERE 
							sites_site_id=$ecom_siteid 
						AND 
							comptype_hide=0 
						ORDER BY 
							comptype_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				/*while ($row = $db->fetch_array($rets))
				{
					$key = $row['comptype_id'];
					$ret .= '<option value="'.$key.'">'.stripslashes($row['comptype_name']).'</option>';
				}*/
				while ($row = $db->fetch_array($rets))
				{
					$key1 = $row['comptype_id'];
					$selc='';
					if($saved_checkoutvals[$key]==$key1)
					{
						$selc = 'selected';
					}
					$ret .= '<option value="'.$key1.'" '.$selc.'>'.stripslashes($row['comptype_name']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_cardtype':
			/*if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card_voucher(this)">';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card(this)">';*/
			
			if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';	

			$ret .= "<option value=''>-- Select --</option>";
			
			$sql = "SELECT a.cardtype_key,a.cardtype_caption,a.cardtype_issuenumber_req,a.cardtype_securitycode_count,cardtype_numberofdigits,a.cardtype_paypalprokey 
					FROM
						payment_methods_supported_cards a,payment_methods_sites_supported_cards b
					WHERE
						b.sites_site_id = $ecom_siteid
						AND a.cardtype_id=b.payment_methods_supported_cards_cardtype_id
					ORDER BY
						b.supportcard_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				while ($row = $db->fetch_array($rets))
				{
					if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO')
						$key = $row['cardtype_paypalprokey'];
					else
						$key = $row['cardtype_key'];
					$ret .= '<option value="'.$key.'_'.$row['cardtype_issuenumber_req'].'_'.$row['cardtype_securitycode_count'].'_'.$row['cardtype_numberofdigits'].'">'.stripslashes($row['cardtype_caption']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_expirydate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			for($i=date('Y');$i<date('Y')+10;$i++)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutpay_issuedate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=date('Y');$i>date('Y')-20;$i--)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutchq_date':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="" size="10" maxlength="10" '.$txt_cls.'/> (e.g. 01-01-2008)';
		break;
		case 'checkout_notes':
		case 'checkout_vouchernotes':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'>'.$saved_checkoutvals[$key].'</textarea>';
		break;
		case 'checkoutchq_bankbranch':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'></textarea>';
		break;
	};
	return $ret;
}
function add_to_favproducts($custom_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
	global $ecom_selfhttp;
	$url = $_REQUEST['pass_url'];
	$product_id=$_REQUEST['fproduct_id'];
	if($product_id)
	{
		if($custom_id){
				 $sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=$product_id AND customer_customer_id=$custom_id LIMIT 1";
				 $ret_num= $db->query($sql_prod);
				  if($db->num_rows($ret_num)==0)
				  {
						$insert_array =array();
						$insert_array['customer_customer_id'] = $custom_id;
						$insert_array['products_product_id'] = $product_id ;
						$insert_array['product_hide'] = 0 ;
						$insert_array['sites_site_id'] = $ecom_siteid;
						$db->insert_from_array($insert_array,'customer_fav_products');
					echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_addfav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='result' value='added'/></form><script type='text/javascript'>document.frm_addfav.submit();</script>";

				  }
				  else if($db->num_rows($ret_num)>0)
				  {
				  echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_addfav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='result' value='exists'/></form><script type='text/javascript'>document.frm_addfav.submit();</script>";
				  }
		}
	 }
	 else
	 {
		echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_addfav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/></form><script type='text/javascript'>document.frm_addfav.submit();</script>";

	 }
}
function remove_from_favproducts($custom_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
	global $ecom_selfhttp;
	$product_id=$_REQUEST['fproduct_id'];
	$url = $_REQUEST['pass_url'];
	if($product_id){
		if($custom_id){
		$sql_del = "DELETE FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=$product_id AND customer_customer_id=$custom_id LIMIT 1";
		$db->query($sql_del);
		echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_removefav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/><input type='hidden' name='result' value='removed'/></form><script type='text/javascript'>document.frm_removefav.submit();</script>";
		}
		else
		{
		echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_removefav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/></form><script type='text/javascript'>document.frm_removefav.submit();</script>";
		}
	}
	else
	{
		echo "<form method='post' action ='".$ecom_selfhttp."$ecom_hostname".$url."' name='frm_removefav'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$_REQUEST['pass_url']."'/></form><script type='text/javascript'>document.frm_removefav.submit();</script>";
	}
}
// Function to get the checkout values temporarly saved for current cart
function get_CheckoutValues($pass_arr=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	$ret_arr = array();
	if(count($pass_arr)==0)
	{
		$sql = "SELECT checkout_fieldname,checkout_value
					FROM
						cart_checkout_values
					WHERE
						session_id='".$sess_id."'
						AND sites_site_id = $ecom_siteid";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			while($row = $db->fetch_array($ret))
			{
				$ret_arr[$row['checkout_fieldname']] = stripslashes($row['checkout_value']);
			}
		}
	}
	else
	{
		
		$ret_arr['checkout_fname'] 				= trim($pass_arr['FIRSTNAME']);
		$ret_arr['checkout_mname'] 				= trim($pass_arr['MIDDLENAME']);
		$ret_arr['checkout_surname'] 			= trim($pass_arr['LASTNAME']);
		$ret_arr['checkout_comp_name'] 			= trim($pass_arr['BUSINESS']);
		$ret_arr['checkout_building'] 			= trim($pass_arr['SHIPTOSTREET']);
		$ret_arr['checkout_street'] 			= trim($pass_arr['SHIPTOSTREET2']);
		$ret_arr['checkout_city'] 				= trim($pass_arr['SHIPTOCITY']);
		$ret_arr['checkout_state'] 				= trim($pass_arr['SHIPTOSTATE']);
		$pass_arr['SHIPTOCOUNTRYCODE']			= trim($pass_arr['SHIPTOCOUNTRYCODE']);
		//echo "country code".$pass_arr['SHIPTOCOUNTRYCODE'];
		if($pass_arr['SHIPTOCOUNTRYCODE'])
		{
			// try to get the country details using the country code 
			$sql_country_det = "SELECT country_id,country_name 
								FROM 
									general_settings_site_country 
								WHERE 
									sites_site_id = $ecom_siteid  
									AND country_code='".addslashes($pass_arr['SHIPTOCOUNTRYCODE'])."'  
								LIMIT 
									1";
			$ret_country_det = $db->query($sql_country_det);
			if($db->num_rows($ret_country_det))
			{
				$row_country_det = $db->fetch_array($ret_country_det);
				if($ecom_is_country_textbox==1)
				{
					$ret_arr['checkout_country'] 			= $row_country_det['country_name'];
				}
				else
					$ret_arr['checkout_country'] 			= $row_country_det['country_id'];
			}
		}
		$ret_arr['checkout_zipcode'] 			= $pass_arr['SHIPTOZIP'];
		$ret_arr['checkout_phone'] 				= $pass_arr['PHONENUM'];
		
	}
	return $ret_arr;
}
// Function to get the voucher values temporarly saved for current cart
function get_VoucherBuyValues($pass_arr=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	$ret_arr = array();
	
       
            $cust_id		= get_session_var("ecom_login_customer"); // Get the customer id from session
            if($cust_id)
            {
                    // Get the details of current customer 
                    $sql_cust = "SELECT customer_title, customer_fname, customer_mname, customer_surname, customer_compname, customer_buildingname,
                                                                            customer_streetname, customer_towncity, customer_statecounty, customer_phone, customer_fax, customer_mobile,
                                                                            customer_postcode, country_id, customer_email_7503 
                                                                    FROM 
                                                                            customers 
                                                                    WHERE 
                                                                            customer_id = $cust_id 
                                                                            AND sites_site_id = $ecom_siteid 
                                                                    LIMIT 
                                                                            1";
                    $ret_cust = $db->query($sql_cust);
                    if ($db->num_rows($ret_cust))
                    {
                            $row_cust = $db->fetch_array($ret_cust);
                    }
            }
            $sql = "SELECT voucher_title,voucher_fname,voucher_mname,voucher_surname,voucher_buildingno,
                                            voucher_street,voucher_city,voucher_state,voucher_country,voucher_zip,
                                            voucher_phone,voucher_mobile,voucher_company,voucher_fax,voucher_email,
                                            voucher_note,voucher_value,voucher_toname,voucher_toemail,
                                            voucher_tomessage,voucher_paytype,voucher_paymethod,voucher_activedays
                                    FROM
                                            gift_voucherbuy_cartvalues
                                    WHERE
                                            session_id='".$sess_id."'
                                            AND sites_site_id = $ecom_siteid";
            $ret = $db->query($sql);
            if($db->num_rows($ret))
            {
                    $row = $db->fetch_array($ret);
            }
           
            $ret_arr['checkout_vouchertitle'] 				= stripslashes(($row['voucher_title'])?$row['voucher_title']:$row_cust['customer_title']);
            $ret_arr['checkout_voucherfname'] 			= stripslashes(($row['voucher_fname'])?$row['voucher_fname']:$row_cust['customer_fname']);
            $ret_arr['checkout_vouchermname'] 			= stripslashes(($row['voucher_mname'])?$row['voucher_mname']:$row_cust['customer_mname']);
            $ret_arr['checkout_vouchersurname'] 		= stripslashes(($row['voucher_surname'])?$row['voucher_surname']:$row_cust['customer_surname']);
            $ret_arr['checkout_voucherbuilding'] 			= stripslashes(($row['voucher_buildingno'])?$row['voucher_buildingno']:$row_cust['customer_buildingname']);
            $ret_arr['checkout_voucherstreet'] 			= stripslashes(($row['voucher_street'])?$row['voucher_street']:$row_cust['customer_streetname']);
            $ret_arr['checkout_vouchercity'] 				= stripslashes(($row['voucher_city'])?$row['voucher_city']:$row_cust['customer_towncity']);
            $ret_arr['checkout_voucherstate'] 			= stripslashes(($row['voucher_state'])?$row['voucher_state']:$row_cust['customer_statecounty']);
            
            if($row['voucher_state'])
                    $ret_arr['checkout_voucherstate'] 	= stripslashes($row['voucher_state']);
            elseif ($row_cust['customer_statecounty']>0)
            {
                    // Get the name of state from general_settings_site_state table
                    $sql_state = "SELECT state_name   
                                                                            FROM  
                                                                                    general_settings_site_state 
                                                                            WHERE 
                                                                                    state_id = ".$row_cust['customer_statecounty']." 
                                                                                    AND sites_site_id = $ecom_siteid 
                                                                            LIMIT 
                                                                                    1";
                    $ret_state = $db->query($sql_state);
                    if($db->num_rows($ret_state))
                    {
                            $row_state = $db->fetch_array($ret_state);
                            $ret_arr['checkout_voucherstate'] 	= stripslashes($row_state['state_name']);
                    }
            }
            
            if($row['voucher_country'])
                    $ret_arr['checkout_vouchercountry'] 	= stripslashes($row['voucher_country']);
            elseif ($row_cust['country_id']>0)
            {
                    if($ecom_is_country_textbox==1)
                    {
                            // Get the name of country from general_settings_site_country table
                            $sql_country = "SELECT country_name  
                                                                                    FROM  
                                                                                            general_settings_site_country 
                                                                                    WHERE 
                                                                                            country_id = ".$row_cust['country_id']." 
                                                                                            AND sites_site_id = $ecom_siteid 
                                                                                    LIMIT 
                                                                                            1";
                            $ret_country = $db->query($sql_country);
                            if($db->num_rows($ret_country))
                            {
                                    $row_country = $db->fetch_array($ret_country);
                                    $ret_arr['checkout_vouchercountry'] 			= stripslashes($row_country['country_name']);
                            }
                    }
                    else
                            $ret_arr['checkout_vouchercountry'] 			= $row_cust['country_id'];	
            }
            $ret_arr['checkout_voucherzipcode'] 		= stripslashes(($row['voucher_zip'])?$row['voucher_zip']:$row_cust['customer_postcode']);
            $ret_arr['checkout_voucherphone'] 			= stripslashes(($row['voucher_phone'])?$row['voucher_phone']:$row_cust['customer_phone']);
            $ret_arr['checkout_vouchermobile'] 			= stripslashes(($row['voucher_mobile'])?$row['voucher_mobile']:$row_cust['customer_mobile']);
            $ret_arr['checkout_vouchercomp_name']               = stripslashes(($row['voucher_company'])?$row['voucher_company']:$row_cust['customer_compname']);
            $ret_arr['checkout_voucherfax'] 			= stripslashes(($row['voucher_fax'])?$row['voucher_fax']:$row_cust['customer_fax']);
            $ret_arr['checkout_voucheremail'] 			= stripslashes(($row['voucher_email'])?$row['voucher_email']:$row_cust['customer_email_7503']);
            $ret_arr['checkout_voucheremail'] 			= stripslashes(($row['voucher_email'])?$row['voucher_email']:$row_cust['customer_email_7503']);
        if(count($pass_arr))
        {
            if(trim($pass_arr['FIRSTNAME'])!='')
                $ret_arr['checkout_voucherfname']                   = trim($pass_arr['FIRSTNAME']);
            if(trim($pass_arr['MIDDLENAME'])!='')
                $ret_arr['checkout_vouchermname']                   = trim($pass_arr['MIDDLENAME']);
            if(trim($pass_arr['LASTNAME'])!='')
                $ret_arr['checkout_vouchersurname']                 = trim($pass_arr['LASTNAME']);
            //$ret_arr['checkout_comp_name']                    = trim($pass_arr['BUSINESS']);
            if(trim($pass_arr['SHIPTOSTREET'])!='')
                $ret_arr['checkout_voucherbuilding']                = trim($pass_arr['SHIPTOSTREET']);
            if(trim($pass_arr['SHIPTOSTREET2'])!='')
                $ret_arr['checkout_voucherstreet']                  = trim($pass_arr['SHIPTOSTREET2']);
            if(trim($pass_arr['SHIPTOCITY'])!='')
                $ret_arr['checkout_vouchercity']                    = trim($pass_arr['SHIPTOCITY']);
            if(trim($pass_arr['SHIPTOSTATE'])!='')
                $ret_arr['checkout_voucherstate']                   = trim($pass_arr['SHIPTOSTATE']);
            
            $pass_arr['SHIPTOCOUNTRYCODE']                      = trim($pass_arr['SHIPTOCOUNTRYCODE']);
            //echo "country code".$pass_arr['SHIPTOCOUNTRYCODE'];
            if($pass_arr['SHIPTOCOUNTRYCODE'])
            {
                    // try to get the country details using the country code 
                    $sql_country_det = "SELECT country_id,country_name 
                                                            FROM 
                                                                    general_settings_site_country 
                                                            WHERE 
                                                                    sites_site_id = $ecom_siteid  
                                                                    AND country_code='".addslashes($pass_arr['SHIPTOCOUNTRYCODE'])."'  
                                                            LIMIT 
                                                                    1";
                    $ret_country_det = $db->query($sql_country_det);
                    if($db->num_rows($ret_country_det))
                    {
                            $row_country_det = $db->fetch_array($ret_country_det);
                            if($ecom_is_country_textbox==1)
                            {
                                    $ret_arr['checkout_vouchercountry']                    = $row_country_det['country_name'];
                            }
                            else
                                    $ret_arr['checkout_vouchercountry']                    = $row_country_det['country_id'];
                    }
            }
            if(trim($pass_arr['SHIPTOZIP'])!='')
                $ret_arr['checkout_voucherzipcode']         = trim($pass_arr['SHIPTOZIP']);
            if(trim($pass_arr['PHONENUM'])!='')
                $ret_arr['checkout_voucherphone']           = trim($pass_arr['PHONENUM']);
        }
	$ret_arr['checkout_vouchernotes'] 		= stripslashes($row['voucher_note']);
	$ret_arr['voucher_value'] 			= stripslashes($row['voucher_value']);
	$ret_arr['voucher_toname'] 			= stripslashes($row['voucher_toname']);
	$ret_arr['voucher_toemail'] 			= stripslashes($row['voucher_toemail']);
	$ret_arr['voucher_tomessage'] 			= stripslashes($row['voucher_tomessage']);
	$ret_arr['voucher_paytype'] 			= stripslashes($row['voucher_paytype']);
	$ret_arr['voucher_paymethod'] 			= stripslashes($row['voucher_paymethod']);
	$ret_arr['voucher_activedays'] 			= stripslashes($row['voucher_activedays']);
	return $ret_arr;
}

// ** Function to check whether the payment type selected is valid or not
function is_PayType_Valid()
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	$session_id = Get_session_Id_from();
	$cust_id		= get_session_var("ecom_login_customer"); // Get the customer id from session
	$cartData 	= cartCalc(); // Calling the function to calculate the details related to cart
	if($cartData["payment"]["type"]=='pay_on_account') // case if payment type seleected is pay on account .
	{
		// Check whether this customer is eligibale for pay on account
		if ($cust_id)
		{
			// Check whether pay on account is activated for current customer. 
			$sql_custcheck = "SELECT customer_payonaccount_status ,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit 
											FROM 
												customers 
											WHERE 
												customer_id = $cust_id 
												AND sites_site_id = $ecom_siteid 
												AND customer_payonaccount_status ='ACTIVE' 
											LIMIT 
												1";
			$ret_custcheck = $db->query($sql_custcheck);
			if ($db->num_rows($ret_custcheck)) // case if payon account is active for current customer
			{
				$row_custcheck 						= $db->fetch_array($ret_custcheck);
				$payonaccount_maxlimit 			= $row_custcheck['customer_payonaccount_maxlimit'];
				$payonaccount_usedmaxlimit 	= $row_custcheck['customer_payonaccount_usedlimit'];
				$payonaccount_remlimit			= ($payonaccount_maxlimit - $payonaccount_usedmaxlimit);
				$amt_payable_now					= $cartData["totals"]["bonus_price"];//($cartData["totals"]["bonus_price"] - $cartData["totals"]["deposit_less"]);
				if($amt_payable_now>$payonaccount_remlimit) // case if credit limit is not sufficient
				{
					// show the msg -> you dont have sufficient credit limit to make this purchase.
					$paytype_id = 0;
					$cart_msg = 'PAYON_CREDIT_NOT_SUFFICIENT';
				}
			}
			else
			{
				// show the msg ->not authorized for pay on account
				$paytype_id = 0;						
				$cart_msg = 'PAYON_CREDIT_NOT_AUTH';		
			}
		}
		else
		{
			$paytype_id = 0;
			// show the msg -> should be logged in to do pay on account
			$cart_msg = 'PAYON_CREDIT_NOT_LOGGEDIN';
		}	
				
	}

	if ($cart_msg)
	{
		// some problem in the payment type selection so set the payment type to 0
		$update_sql = "UPDATE 
									cart_supportdetails 
								SET 
									paytype_id = 0,
									paymethod_id = 0 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND session_id = '".$session_id."'  
								LIMIT 
									1";
		$db->query($update_sql);
	}
	return $cart_msg;
}
function is_Pay_Req_Secured($paytype_name='voucher_paytype',$paymethod_name='voucher_paymethod')
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	$sec_req = false;
	if($_REQUEST[$paytype_name]!='')
	{
		// Check whether the current payment type is related to credit card
		$sql_paytype = "SELECT paytype_code 
									FROM 
										payment_types 
									WHERE 
										paytype_id='".$_REQUEST[$paytype_name]."' 
									LIMIT 
									1";
		$ret_paytype = $db->query($sql_paytype);
		if ($db->num_rows($ret_paytype))
		{
			$row_paytype = $db->fetch_array($ret_paytype);
			if($row_paytype['paytype_code']=='credit_card')
			{
				// Check whether any payment method is selected 
				if($_REQUEST[$paymethod_name]!='')
				{
					$paymethod_arr = explode('_',$_REQUEST[$paymethod_name]);
					$paymethod		= $paymethod_arr[count($paymethod_arr)-1];
					// Check whether the selected payment method required ssl 
					$sql_paymethod = "SELECT paymethod_secured_req 
													FROM 
														payment_methods 
													WHERE 
														paymethod_id ='".$paymethod."' 
													LIMIT 
														1";
					$ret_paymethod = $db->query($sql_paymethod);
					if($db->num_rows($ret_paymethod))
					{
						$row_paymethod = $db->fetch_array($ret_paymethod);
						if($row_paymethod['paymethod_secured_req']==1)
							$sec_req = true;
					}
				}
			}
		}
	}
	return $sec_req;
}
// ** Function to save the cart_supportdetails
function save_CartsupportDetails($mod)
{
	global $db,$ecom_siteid,$ecom_hostname,$Captions_arr,$ecom_common_settings,$ecom_site_delivery_location_country_map,$Settings_arr;
	global $ecom_selfhttp;
	$session_id = Get_session_Id_from();	// Get the session id for the current section
	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	$cust_id		= get_session_var("ecom_login_customer"); // Get the customer id from session
	/* ------------------- 4 min finance - start ---------------------------*/
	$finance_id = 0; 
	$finance_deposit = 0;
	/* ------------------- 4 min finance - end ---------------------------*/
	// ##################################################################
	// Check whether an entry exists in the cart_supportdetails table
	// ##################################################################
	$sql_det = "SELECT session_id
				FROM
					cart_supportdetails
				WHERE
					session_id = '".$session_id."'
					AND sites_site_id=$ecom_siteid
				LIMIT
					1";
	$ret_det = $db->query($sql_det);
	if ($db->num_rows($ret_det)==0)// case if no record exists. In such a case create a blank record
	{
		$insert_array							= array();
		$insert_array['session_id']				= $session_id;
		$insert_array['sites_site_id']			= $ecom_siteid;
		$insert_array['giftwrap_req']			= 0;
		$insert_array['giftwrap_msg_req']		= 0;
		$insert_array['giftwrap_msg']			= '';
		$insert_array['giftwrap_ribbon_id']		= 0;
		$insert_array['giftwrap_paper_id']		= 0;
		$insert_array['giftwrap_card_id']		= 0;
		$insert_array['giftwrap_bow_id']		= 0;
		$insert_array['location_id']			= 0;
		$insert_array['delopt_det_id']			= 0;
		$insert_array['split_delivery']			= 0;
		$insert_array['paytype_id']				= 0;
		$insert_array['paymethod_id']			= 0;
		$insert_array['promotionalcode_id']		= 0;
		$insert_array['voucher_id']				= 0;
		$insert_array['total_product_deposit']	= 0;
		$insert_array['date']					= 'now()';
		$insert_array['time_id']				= 0;
		/* ------------------- 4 min finance - start ---------------------------*/
		$insert_array['finance_id']				= 0; 
		$insert_array['finance_deposit']		= trim(($_REQUEST['fin_deposit'])?$_REQUEST['fin_deposit']:0);
		/* ------------------- 4 min finance - end ---------------------------*/	
		$db->insert_from_array($insert_array,'cart_supportdetails');
	}
	switch ($mod)
	{
		case 'save_commondetails': // Saving the details related to gift wrap

			// ##################################################################
			// Prepare the values to be saved related to gift wrap
			// ##################################################################
			if ($_REQUEST['chk_giftwrapreq']) // case if giftwrap main is checked
			{
				$wrapreq 		= 1;
				$wrapmsgreq		= ($_REQUEST['giftwrap_message_req'])?1:0;
				$wrapmsg		= ($wrapmsgreq==1)?$_REQUEST['giftwrap_message']:'';
				$ribbon_id		= ($_REQUEST['ribbon_radio'])?$_REQUEST['ribbon_radio']:0;
				$paper_id		= ($_REQUEST['paper_radio'])?$_REQUEST['paper_radio']:0;
				$card_id		= ($_REQUEST['card_radio'])?$_REQUEST['card_radio']:0;
				$bow_id			= ($_REQUEST['bow_radio'])?$_REQUEST['bow_radio']:0;

			}
			else // case if giftwrap main in not checked
			{
				$wrapreq 		= 0;
				$wrapmsgreq		= 0;
				$wrapmsg		= '';
				$ribbon_id		= 0;
				$paper_id		= 0;
				$card_id		= 0;
				$bow_id			= 0;
			}
			// ##################################################################
			// Prepare the values to be saved related to promotional code
			// ##################################################################

			if (trim($_REQUEST['cart_promotionalcode']))
			{
				$code 		= add_slash(trim($_REQUEST['cart_promotionalcode']));
				$prom_id	= $voucher_id = 0;
				
				// Check whether it is promotional code or gift voucher
				$sql_prom = "SELECT code_id,code_unlimit_check,code_limit,code_usedlimit,code_customer_unlimit_check,
									code_customer_limit,code_login_to_use,code_type  
								FROM
									promotional_code 
								WHERE
									sites_site_id = $ecom_siteid
									AND code_hidden = 0
									AND code_number = '".$code."'
									AND (curdate()>=code_startdate AND curdate()<=code_enddate)
								LIMIT
									1";
				$ret_prom = $db->query($sql_prom);
				if ($db->num_rows($ret_prom))
				{
					$row_prom 			= $db->fetch_array($ret_prom);
					$valid_promotional	= '';
					$proceed_proms 		= true;
					if ($row_prom['code_login_to_use']==1)
					{
						if (!$cust_id)	// check whether logged in
						{
							$valid_promotional  = 'PROM_LOGIN_REQ'; // if not logged in the show the login req msg
							$proceed_proms		= false;
						}	
					}
					//else 	// case if logged in
					if($proceed_proms)
					{
						// Check whether the promotional code is used in by current customer
						/*$sql_ordcheck = "SELECT order_id
											FROM
												orders
											WHERE
												sites_site_id=$ecom_siteid
												AND promotional_code_code_number='".$code."'
												AND customers_customer_id = $cust_id
											LIMIT
												1";
						*/				
						// Check whether the promotional code is limited or unlimited
						// Find the total number of time this code has been used by all customer
						/*$sql_cnt = "SELECT count(track_id) as totcnt 
										FROM 
											order_promotionalcode_track 
										WHERE 
											code_number='".stripslashes($code)."'
											AND promotional_code_code_id = ".$row_prom['code_id']." 
											AND sites_site_id = $ecom_siteid ";
						$ret_cnt = $db->query($sql_cnt);
						list($totalused_cnt) = $db->fetch_array($ret_cnt);*/
						
						
						$sql_cnt = "SELECT count(orders_order_id) as cust_usedcnt 
										FROM
											order_promotionalcode_track a, orders b 
										WHERE
											a.sites_site_id=$ecom_siteid 
											AND b.order_id = a.orders_order_id 
											AND b.order_status NOT IN ('NOT_AUTH') 
											AND code_number='".stripslashes($code)."' 
											AND a.promotional_code_code_id = ".$row_prom['code_id'];
						$ret_cnt = $db->query($sql_cnt);
						list($totalused_cnt) = $db->fetch_array($ret_cnt);
					
						if($row_prom['code_unlimit_check']==0) // case if limited
						{
							if($row_prom['code_limit']==0)
							{
								$valid_promotional = 'PROM_INACTIVE';
							}
							elseif($totalused_cnt>=$row_prom['code_limit'])
							{
								$valid_promotional = 'PROM_MAXLIMIT_REACHED';
							}
						}
						if ($valid_promotional=='')
						{
							if ($row_prom['code_login_to_use']==1)
							{
								$sql_ordcheck = "SELECT count(orders_order_id) as cust_usedcnt 
													FROM
														order_promotionalcode_track a, orders b 
													WHERE
														a.sites_site_id=$ecom_siteid 
														AND b.order_id = a.orders_order_id 
														AND b.order_status NOT IN ('NOT_AUTH') 
														AND code_number='".$code."' 
														AND a.promotional_code_code_id = ".$row_prom['code_id']." 
														AND a.customers_customer_id = $cust_id";					
								$ret_ordcheck	= $db->query($sql_ordcheck);
								$row_ordcheck = $db->fetch_array($ret_ordcheck);
								/*if ($row_ordcheck['cust_usedcnt']>0) // if used already atleast once
								{*/	
									// Check whether promotional code usable limit for customer is limited or unlimited
									if($row_prom['code_customer_unlimit_check']==0) // case if limited
									{
										if($row_prom['code_customer_limit']==0)
										{
											$valid_promotional = 'PROM_INACTIVE';
										}
										elseif($row_ordcheck['cust_usedcnt']>=$row_prom['code_customer_limit']) // if used count is >= usable count
										{
											$valid_promotional = 'PROM_CUST_MAXLIMIT_REACHED';
										}
										if($valid_promotional=='')
											$prom_id	= $row_prom['code_id']; // assign the promotional code		
									}
									else // case if usage for current customer is unlimited
									{
										$prom_id	= $row_prom['code_id']; // assign the promotional code	
									}
									//$valid_promotional  = 'PROM_ALREADY_USED'; // if not logged in the show the login req msg
								/*}	
								else
								{
									if($row_prom['code_customer_limit']==0)
									{
										$valid_promotional = 'PROM_INACTIVE';
									}
									elseif($row_ordcheck['cust_usedcnt']>=$row_prom['code_customer_limit'])
									{
										$valid_promotional = 'PROM_MAXLIMIT_REACHED';
									}
									else
										$prom_id	= $row_prom['code_id']; // assign the promotional code
								}	*/
							}
							else
							{
								$prom_id	= $row_prom['code_id']; // assign the promotional code
							}
							if($prom_id>0)
							{   
								 $prom_code_type = $row_prom['code_type'];
								if($ecom_siteid==112 OR $ecom_siteid==126)
								{
									if($prom_code_type=='freeproduct')
									{
										
										//Add products to the cart if promotional code type = freeproduct
										Add_Item_to_Cart_promoproducts($varN='var_',$varM='varmsg_',$ret=true,$prom_id,$maincart_id=0,$prom_code_type);
									
									}
								}
							}		
						}		
					}
				}
				else // Case not found in promotional code. So check in gift voucher
				{
					$sql_voucher = "SELECT voucher_id,voucher_login_touse
									FROM
										gift_vouchers
									WHERE
										sites_site_id = $ecom_siteid
										AND voucher_hide = 0
										AND (curdate()>=voucher_activatedon AND curdate()<=voucher_expireson)
										AND voucher_number ='".$code."'
										AND voucher_paystatus ='Paid'
										AND voucher_max_usage > voucher_usage";
					$ret_voucher = $db->query($sql_voucher);
					if ($db->num_rows($ret_voucher))
					{
						$row_voucher = $db->fetch_array($ret_voucher);
						if ($row_voucher['voucher_login_touse']==1) // Check whether the login to use is set for voucher
						{
							$cust_id = get_session_var("ecom_login_customer"); // Get the customer id from session
							if ($cust_id)
							{
								$voucher_id 		= $row_voucher['voucher_id'];
							}
							else
								$valid_promotional  = 'GIFT_LOGIN_REQ';
						}
						else
						{
							$voucher_id 		= $row_voucher['voucher_id'];
						}
					}
					else
						$valid_promotional  = 'CODE_NOT_FOUND';

				}
				// Section which builds the messages to be displayed as alert
				/*switch($valid_promotional)
				{
					case 'PROM_LOGIN_REQ':
						$msg = 1;
					break;
					case 'CODE_NOT_FOUND':
						$msg = 1;
					break;
					case 'GIFT_LOGIN_REQ':
						$msg = 1;
					break;
					case 'PROM_ALREADY_USED':
						$msg = 1;
					break;
					case 'PROM_INACTIVE':
						$msg = 1;
					break;
					case 'PROM_MAXLIMIT_REACHED':
						$msg = 1;
					break;
					case 'PROM_CUST_MAXLIMIT_REACHED':
						$msg = 1;
					break;
					
				}*/
				if ($valid_promotional!='')
					$msg = 1;
					
				if ($msg)
				{
					/*echo "<script type='text/javascript'>alert('".$msg."')</script>";*/
					$cart_msg = $valid_promotional;
				}
			}
			if($_REQUEST['cancel_promotionalcode'])
			{
				$voucher_id = 0;
				$prom_id	= 0;
			}
			$del_loc 			= ($_REQUEST['cart_deliverylocation'])?$_REQUEST['cart_deliverylocation']:0;
			$del_grp 			= ($_REQUEST['cart_deliveryoption'])?$_REQUEST['cart_deliveryoption']:0;
			$paytype_id		= ($_REQUEST['cart_paytype'])?$_REQUEST['cart_paytype']:0;
			$paymethod_id	= ($_REQUEST['cart_paymethod'])?$_REQUEST['cart_paymethod']:0;
			if($paytype_id) // case if payment type is selected
			{
				$sql_paytypes 	= "SELECT paytype_code
							FROM
								payment_types
							WHERE
								paytype_id=".$paytype_id."
							LIMIT
								1";
				$ret_paytypes = $db->query($sql_paytypes);
				if($db->num_rows($ret_paytypes))
					$row_paytypes = $db->fetch_array($ret_paytypes);
				if($row_paytypes['paytype_code']=='credit_card') // case if payment type selected is credit card
				{
					if($paymethod_id==0) // paymethod not selected
					{
						$add_condition = " ";
						// Get the paymethod_id FOR google checkout
						$sql_google = "SELECT paymethod_id 
											FROM 
												payment_methods 
											WHERE 
												paymethod_key='GOOGLE_CHECKOUT' 
											LIMIT 
												1";
						$ret_google = $db->query($sql_google);
						if ($db->num_rows($ret_google))
						{
							$row_google = $db->fetch_array($ret_google);
							$google_prev_req 				= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_preview_req'];
							$google_recommended		= $ecom_common_settings['paymethodKey']['GOOGLE_CHECKOUT']['payment_method_google_recommended'];
							if($google_recommended ==0) // case if google checkout is set to work in the way google recommend
								$add_condition = " AND payment_methods_paymethod_id<>".$row_google['paymethod_id'].' ';
						}
						if($ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_key']=='PAYPAL_EXPRESS')
						{
							$add_condition = " AND payment_methods_paymethod_id<>".$ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_id'].' ';
						}
						
						
						// Check whether there are more than one payment method selected for this site
						$sql_paymethods 	= "SELECT payment_methods_paymethod_id
													FROM
														payment_methods_forsites
													WHERE
														sites_site_id = $ecom_siteid 
														AND payment_method_sites_active = 1 
														$add_condition";
						$ret_paymethods 	= $db->query($sql_paymethods);
						if($db->num_rows($ret_paymethods)==1)
						{
							// If there is only one payment method then set the paymentmethod id as its id so that it gets saved to supportdetails table
							$row_paymethods = $db->fetch_array($ret_paymethods);
							$paymethod_id	= $row_paymethods['payment_methods_paymethod_id'];
						}
						
					}
				}
				elseif($row_paytypes['paytype_code']=='4min_finance') // case of 4minute finance
				{
					/* ------------------- 4 min finance - start ---------------------------*/
					if($_REQUEST['finradio'])
					{
						$finance_id = $_REQUEST['finradio'];
					}
					
					/* ------------------- 4 min finance - end ---------------------------*/
				}	
			}
			
			$split_delivery	= ($_REQUEST['cart_splitdelivery'])?1:0;
			$spend_bonus	= ($_REQUEST['spendBonusPoints'])?$_REQUEST['spendBonusPoints']:0;
			/* Donate bonus Start */
			$donate_bonus	= ($_REQUEST['donateBonusPoints'])?$_REQUEST['donateBonusPoints']:0;
			if($spend_bonus<0)
				$spend_bonus=0;
			if($donate_bonus<0)
				$donate_bonus=0;
			/* Donate bonus End */
			// ##################################################################
			// Making changes to the cart_supportdetails table
			// ##################################################################
			$update_array							= array();
			$update_array['giftwrap_req']			= $wrapreq;
			$update_array['giftwrap_msg_req']		= $wrapmsgreq;
			$update_array['giftwrap_msg']			= $wrapmsg;
			$update_array['giftwrap_ribbon_id']		= $ribbon_id;
			$update_array['giftwrap_paper_id']		= $paper_id;
			$update_array['giftwrap_card_id']		= $card_id;
			$update_array['giftwrap_bow_id']		= $bow_id;
			$update_array['paytype_id']				= $paytype_id;
			$update_array['paymethod_id']			= $paymethod_id;
			
			/* ------------------- 4 min finance - start ---------------------------*/
			if($finance_id)
			{
				$update_array['finance_id']			= $finance_id;
				$update_array['finance_deposit']	= trim(($_REQUEST['fin_deposit'])?$_REQUEST['fin_deposit']:0);
			}	
			/* ------------------- 4 min finance - end ---------------------------*/
			
			
			if($_REQUEST['cart_savepromotional']) // done to handle the case of overriding the promotional / voucher detail during saving on checkout link click
			{
				//Delete the promotional code product from the cart if $prom_id =0 or null
				if($ecom_siteid==112 OR $ecom_siteid==126)
				{
				Remove_freeproduct_cart($prom_id);
				}
				$update_array['promotionalcode_id']		= $prom_id;
				$update_array['voucher_id']				= $voucher_id;
			}
			$update_array['location_id']			= $del_loc;
			if($ecom_site_delivery_location_country_map==1)
			{
				if($del_grp)
				{
					if (!$del_loc)		
						$del_grp = 0;
					else
					{
						// Check whether current group is active under current location 
						$sql_grpchk = "SELECT delivery_group_id 
										FROM 
											delivery_site_option_details 
										WHERE 
											delivery_site_location_location_id=$del_loc 
											AND delivery_group_id=$del_grp 
											AND delivery_group_active_in_location=1 
										LIMIT 
											1";
						$ret_grpchk = $db->query($sql_grpchk);
						if($db->num_rows($ret_grpchk)==0)
						{
							$del_grp = 0;
						}
					}	
				}
				if($del_grp == 0 and $del_loc) // case if delivery group is not selected
				{
					// Check whether there exists any delivery group active for current location. If exists
					// then set the first one as the current group
					$sql_grpchk = "SELECT delivery_group_id 
										FROM 
											delivery_site_option_details 
										WHERE 
											delivery_site_location_location_id=$del_loc 
											AND delivery_group_active_in_location=1 
										ORDER BY 
											delopt_det_id
										LIMIT 
											1";
						$ret_grpchk = $db->query($sql_grpchk);
						if($db->num_rows($ret_grpchk))
						{
							$row_grpchk = $db->fetch_array($ret_grpchk);
							$del_grp = $row_grpchk['delivery_group_id'];
						}
					
				}
			}
			$update_array['delopt_det_id']			= $del_grp;
			$update_array['split_delivery']			= $split_delivery;
			$update_array['bonus_spending']			= $spend_bonus;
			/* Donate bonus Start */
			$update_array['bonus_donating']			= $donate_bonus;
			/* Donate bonus End */
			/*done for datetime for delivery */
			//print_r($Settings_arr);exit;
			if($Settings_arr['enable_location_datetime']==1)
			{
					if($del_loc>0)
					{
					$sql_del = "SELECT location_datetime_applicable 
											FROM 
												delivery_site_location
											WHERE 
												location_id =".$del_loc." 
												AND sites_site_id =".$ecom_siteid." LIMIT 1";
								 $ret_del  = $db->query($sql_del); 							
								 $row_del  = $db->fetch_array($ret_del);
					 if($row_del['location_datetime_applicable']==1)
					 {
						$sql_det_s = "SELECT location_id
						FROM
							cart_supportdetails
						WHERE
							session_id = '".$session_id."'
							AND sites_site_id=$ecom_siteid
						LIMIT
							1";
					   $ret_det_s = $db->query($sql_det_s); 
					   $row_det_s = $db->fetch_array($ret_det_s);
					   $curr_locid = $row_det_s['location_id'];
						$delivery_date_arr = array();
						$delivery_date  = '';
						if($_REQUEST['datesField']!='')
						{
						$delivery_date          = $_REQUEST['datesField'];
						$delivery_date_arr      = explode('/',$delivery_date);
						$delivery_date          = $delivery_date_arr[2]."-".$delivery_date_arr[0]."-".$delivery_date_arr[1];
						}
						$delivery_time          = $_REQUEST['cart_deliverytime'];
						if($curr_locid==$del_loc)
						{
						$update_array['date']					= $delivery_date;
						$update_array['time_id']				= $delivery_time;
						}
						else
						{
						$update_array['date']					= '';
						$update_array['time_id']				= '';
						}
					 }
					 else
					 {
						$update_array['date']					= '';
						$update_array['time_id']				= '';
					 }
				}
			}
			$disable_id = 0;
			if($_REQUEST['disability_type'])
			{
				$disable_id = $_REQUEST['disability_type'];
			}
			$update_array['disable_id'] = $disable_id;
			
			//section for euro zone for the lsforklift
			$zone_id = 0 ;
			if($_REQUEST['euro_zones']>0)
			{
				$zone_id = $_REQUEST['euro_zones'];
			}
			$update_array['zone_id'] = $zone_id;
			
			$euvat_id = 0 ;
			if($_REQUEST['euvat_id']>0)
			{
				$euvat_id = $_REQUEST['euvat_id'];
			}
			$update_array['euvat_id'] = $euvat_id;
			
			$company_num = "";
			if($_REQUEST['company_number'])
			{
			  $company_num = $_REQUEST['company_number'];
			}
			$update_array['company_number'] = $company_num;
			//end section for euro zone for the lsforklift
			 if($ecom_siteid==113 || $ecom_siteid==104)
			{
			//section for verification code for invoice
            $update_array['invoice_verification_code'] = $_REQUEST['invoice_verification_code'];
			}
			/*end for datetime for delivery */
			$db->update_from_array($update_array,'cart_supportdetails',array('session_id'=>$session_id,'sites_site_id'=>$ecom_siteid));

			// ** Calling the funciton to update the values in qty field.
			update_cart_qty();
			update_cart_terms_tick();
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			//set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
			//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
			/*echo "<script>window.location='http://".$ecom_hostname."/cart.html'</script>";*/
		break;
		case 'Update_qty': // Function to update the quantity of items added to cart
			$cid = ($_REQUEST['remcart_id'])?$_REQUEST['remcart_id']:-1;
			$cart_msg = update_cart_qty($cid);
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			//set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
			//set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
			/*echo "<script>window.location='http://".$ecom_hostname."/cart.html'</script>";*/
		break;
		case 'Remove_qty':
			if ($_REQUEST['remcart_id'])
			{
				// Deleting entries from the cart variables table
				$sql_del = "DELETE FROM
								cart_variables
							WHERE
								cart_id=".$_REQUEST['remcart_id'];
				$db->query($sql_del);
				// Deleting entries from the cart table
				$sql_del = "DELETE FROM
								cart
							WHERE
								cart_id=".$_REQUEST['remcart_id']."
								AND sites_site_id = $ecom_siteid";
				$db->query($sql_del);
				
				// Remove if any entries related to current cartid
				$sql_del = "DELETE FROM
								cart
							WHERE
								cart_main_cart_id=".$_REQUEST['remcart_id']." 
								AND sites_site_id = $ecom_siteid";
				$db->query($sql_del);
			}
			$cartData = cartCalc(); // Calling the function to calculate the details related to cart
			set_session_var("cart_total", $cartData["totals"]["bonus_price"]); // Setting the cart total to session
			set_session_var("cart_total_items", count($cartData["products"]));				// Setting the cart total to session
			/*echo "<script>window.location='http://".$ecom_hostname."/cart.html'</script>";*/
		break;
	};
	return $cart_msg;
}
function update_cart_terms_tick($cart_id=-1)
{
	global $db,$ecom_siteid,$Captions_arr,$qty_updated_arr;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	if ($cart_id==-1)
	{
		            $update_array				= array();
					$update_array['terms_req']	= 0;
					$update_array['terms_req_new']	= 0;
					$db->update_from_array($update_array,'cart',array('session_id'=>$sess_id,'sites_site_id'=>$ecom_siteid));	

		foreach ($_REQUEST as $k=>$v)
		{
			if (substr($k,0,10)=='tccheckbox')
			{ 
				
				if($v>0)
				{ 
				
					$update_array				= array();
					$update_array['terms_req']	= 1;
					$db->update_from_array($update_array,'cart',array('cart_id'=>$v,'sites_site_id'=>$ecom_siteid));	
				}	
							
			}
			if (substr($k,0,14)=='tcmorecheckbox')
			{ 
				
				if($v>0)
				{ 
				
					$update_array				= array();
					$update_array['terms_req_new']	= 1;
					$db->update_from_array($update_array,'cart',array('cart_id'=>$v,'sites_site_id'=>$ecom_siteid));	
				}	
							
			}
			
		}
		
	}	
}	

function update_cart_qty($cart_id=-1)
{
	global $db,$ecom_siteid,$Captions_arr,$qty_updated_arr;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	if ($cart_id==-1)
	{
		foreach ($_REQUEST as $k=>$v)
		{
			if (substr($k,0,8)=='cart_qty')
			{
				$cart_arrs 	= explode('_',$k);
				$cartid		= $cart_arrs[2];
				$qty		= $v;
				if ($cartid)
				{
					// Get the qty already in cart
					$sql_cart = "SELECT cart_qty,products_product_id,cart_promotional_code_id 
									FROM
										cart
									WHERE
										cart_id =".$cartid."
									LIMIT
										1";
					$ret_cart = $db->query($sql_cart);
					$hide_qty_promo = false;
					if ($db->num_rows($ret_cart))
					{
						$row_cart 		= $db->fetch_array($ret_cart);
						if($ecom_siteid==112 OR $ecom_siteid==126)
						{
							if($row_cart['cart_promotional_code_id'] > 0)
							{
								$hide_qty_promo = true;
							}
						}
						$always_add_to_cart	= false;
						// Get the value set for product_alloworder_notinstock for current product
						$sql_prod = "SELECT product_alloworder_notinstock 
												FROM 
													products 
												WHERE 
													product_id =".$row_cart['products_product_id']." 
												LIMIT 
													1";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							$row_prod 				= $db->fetch_array($ret_prod);
							$always_add_to_cart	= ($row_prod['product_alloworder_notinstock']=='Y')?true:false;
						}
						$var_arr			= get_cartvariables($cartid); // Calling function to get the variable details to an array
						// Check the stock details here
						$stock_arr		= check_stock_available($row_cart['products_product_id'],$var_arr);
						//print_r($stock_arr);
						$stock			= $stock_arr['stock'];
						$stock_check 	= $stock_arr['inpreorder'];
						$cartstock		= $row_cart['cart_qty'];
						if ($always_add_to_cart)
						{
							$stock 			= $qty; // since add to cart is always allowed, stock is made equal to qty in cart
							$stock_check = true;  // since add to cart is allowed always, this field is made true inorder to avoid the check for stock exists or not
						}	
						if (!$stock_check)
						{
							//if($stock<($qty+$cartstock))
							if($stock<$qty)
							{
								$qty	= $stock;// - $cartstock;
								$show_stock_msg = 1;
								
							}
						}
					}
					/*$qty_updated_arr['cart_id'] = $cartid;
					$qty_updated_arr['updated_qty'] = $qty;*/
					$update_array				= array();
					$update_array['cart_qty']	= $qty;
					if($hide_qty_promo == false)
					{
						$db->update_from_array($update_array,'cart',array('cart_id'=>$cartid,'sites_site_id'=>$ecom_siteid));
					}
					//$db->update_from_array($update_array,'cart',array('cart_id'=>$cartid,'sites_site_id'=>$ecom_siteid));
				}
			}
		}
	}
	else
	{
		$qty	= $_REQUEST['cart_qty_'.$cart_id];
		// Get the qty already in cart
		$sql_cart = "SELECT cart_qty,products_product_id,cart_promotional_code_id  
						FROM
							cart
						WHERE
							cart_id =".$cart_id."
						LIMIT
							1";
		$ret_cart = $db->query($sql_cart);
		$hide_qty_promo = false;
		if ($db->num_rows($ret_cart))
		{
			$row_cart 	= $db->fetch_array($ret_cart);
			$always_add_to_cart = false;
			if($ecom_siteid==112 OR $ecom_siteid==126)
            {
				if($row_cart['cart_promotional_code_id'] > 0)
				{
					$hide_qty_promo = true;
				}
			}
			// Get the value set for product_alloworder_notinstock for current product
			$sql_prod = "SELECT product_alloworder_notinstock 
									FROM 
										products 
									WHERE 
										product_id =".$row_cart['products_product_id']." 
									LIMIT 
										1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod 				= $db->fetch_array($ret_prod);
				$always_add_to_cart	= ($row_prod['product_alloworder_notinstock']=='Y')?true:false;
			}
			$var_arr	= get_cartvariables($cart_id); // Calling function to get the variable details to an array
			// Check the stock details here
			$stock_arr		= check_stock_available($row_cart['products_product_id'],$var_arr);
			$stock			= $stock_arr['stock'];
			$stock_check 	= $stock_arr['inpreorder'];
			$cartstock		= $row_cart['cart_qty'];
			
			if ($always_add_to_cart)
			{
				$stock 			= $qty; // since add to cart is always allowed, stock is made equal to qty in cart
				$stock_check = true;  // since add to cart is allowed always, this field is made true inorder to avoid the check for stock exists or not
			}
			if (!$stock_check)
			{
				if($stock<$qty)
				{
					$qty	= $stock;// - $cartstock;
					// Give approprate javascript message.
					/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
					$show_stock_msg = 2;

				}
			}
		}
		$qty_updated_arr['cart_id'] 	= $cart_id;
		$qty_updated_arr['updated_qty'] = $qty;
		$update_array				= array();
		$update_array['cart_qty']	= $qty;
		if($hide_qty_promo == false)
		{
		$db->update_from_array($update_array,'cart',array('cart_id'=>$cart_id,'sites_site_id'=>$ecom_siteid));
		}
		//$db->update_from_array($update_array,'cart',array('cart_id'=>$cart_id,'sites_site_id'=>$ecom_siteid));
	}
	// Done to delete the products with qty set to 0 in current session fro the current site
	$sql_del = "DELETE FROM
							cart
						WHERE
							session_id='".$sess_id."' 
							AND sites_site_id = $ecom_siteid 
							AND cart_qty = 0 ";
	$db->query($sql_del);
	if ($show_stock_msg ==2)
	{
		// Give approprate javascript message.
		/*echo "<script type='text/javascript'>alert('".$Captions_arr['CART']['CART_STOCK_INSUFF_ADJ_QTY']."')</script>";*/
		return 'CART_STOCK_INSUFF_ADJ_QTY';
	}
}

// Function to clear all items in cart
function clear_Cart($sessid)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	set_session_var("cart_total", 0);		// Setting the cart total to 0
	set_session_var("cart_total_items", 0);	// Setting the cart total to 0
	set_session_var("gateway_ord_id",0); // setting the order id hold session variable to 0
	// Get the cart ids for current site in current session
	$sql_cart = "SELECT cart_id
					FROM
						cart
					WHERE
						session_id='".$sessid."'
						AND sites_site_id = $ecom_siteid ";
	$ret_cart = $db->query($sql_cart);
	if ($db->num_rows($ret_cart))
	{
		while ($row_cart = $db->fetch_array($ret_cart))
		{
			// Deleting from cart_variables table
			$sql_del = "DELETE
							FROM
								cart_variables
							WHERE
								cart_id =".$row_cart['cart_id'];
			$db->query($sql_del);
			// Deleting from cart_messages table
			$sql_del = "DELETE
							FROM
								cart_messages
							WHERE
								cart_id =".$row_cart['cart_id'];
			$db->query($sql_del);
		}
		// Deleting from cart_supportdetails table
		$sql_del = "DELETE
						FROM
							cart_supportdetails
						WHERE
							session_id='".$sessid."'
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_del);
		// Deleting from cart table
		$sql_del = "DELETE
						FROM
							cart
						WHERE
							session_id='".$sessid."'
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_del);
		// Deleting from cart_checkout_values table
		$sql_del = "DELETE
						FROM
							cart_checkout_values
						WHERE
							session_id='".$sessid."'
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_del);
		if($ecom_siteid==109 || $ecom_siteid==117)//live unipad id=109 ,local id=121
		{
		$sql_del = "DELETE
						FROM
							cart_checkout_values_extrabilling
						WHERE
							session_id='".$sessid."'
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_del);
		$sql_delpass = "DELETE
						FROM
							cart_passport_details
						WHERE
							session_id='".$sessid."'
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_delpass);
		}
	}
}

// Function to temporarly save the values in checkout page
function save_CheckoutDetails()
{
	global $db,$ecom_siteid,$show_cart_password;
	global $ecom_selfhttp;
	$sess_id	= Get_session_Id_from();
	// Delete any record existing in cart_checkout_values table for current site and current session
	$sql_check = "DELETE
						FROM
							cart_checkout_values
						WHERE
							session_id = '".$sess_id."'
							AND sites_site_id = $ecom_siteid ";
	$ret_check = $db->query($sql_check);

	// Building an array which decides which all fields to be saved in this table
	$fieldname_arr = array();
	$fieldtext_arr = array();
	$field_type_arr = array();
	$sql_checkout = "SELECT field_key,field_name
						FROM
							general_settings_site_checkoutfields
						WHERE
							sites_site_id = $ecom_siteid
							AND field_type IN('PERSONAL','DELIVERY')";
	$ret_checkout = $db->query($sql_checkout);
	if ($db->num_rows($ret_checkout))
	{
		while ($row_checkout = $db->fetch_array($ret_checkout))
		{
			$fieldname_arr[] 						  = $row_checkout['field_key'];
			$fieldtext_arr[$row_checkout['field_key']] = $row_checkout['field_name'];
		}
	}
	// Handling the case of custom fields
	$sql_custom = "SELECT a.element_name,a.element_label,a.element_type
					FROM
						elements a,element_sections b
					WHERE
						a.sites_site_id = $ecom_siteid
						AND a.element_sections_section_id=b.section_id
						AND b.activate=1";
	$ret_custom = $db->query($sql_custom);
	if($db->num_rows($ret_custom))
	{
		while ($row_custom = $db->fetch_array($ret_custom))
		{
			$fieldname_arr[]							= $row_custom['element_name'];
			$fieldtext_arr[$row_custom['element_name']] = $row_custom['element_label'];
			$field_type_arr[$row_custom['element_name']] = $row_custom['element_type'];
		}
	}
	if(count($fieldname_arr))
	{
		//print_r($_REQUEST);
		// Saving the custom fields and dynamic fields values to checkout value save table
		foreach ($_REQUEST as $k=>$v)
		{ 
			// Check whether the current fields name is there in the fieldname_arr array. If yes then save it in the cart_checkout_value table
			if (in_array($k,$fieldname_arr))
			{
				if($show_cart_password==1)
				{
					if($k=='checkout_email')
					{
					   $chk_email = $v;
					}
				}
				$insert_array									= array();
				$insert_array['session_id']					=	 $sess_id;
				$insert_array['sites_site_id']				= $ecom_siteid;
				$insert_array['checkout_fieldname']	= add_slash($k);
				$insert_array['checkout_orgname']	= $fieldtext_arr[$k];
				if($field_type_arr[$k]=='checkbox' || $field_type_arr[$k]=='radio')
				{ 
				  if(is_array($v)){
					  foreach($v as $key=>$value)
					  { 
					   $insert_array['checkout_value']			= add_slash($value);
					  }
				  }
				}
				else
				$insert_array['checkout_value']			= add_slash($v);
				$db->insert_from_array($insert_array,'cart_checkout_values');
			}
		}
		// Insert a record extra to the cart_checkout_values table to hold the value selected for
		// the field checkout_billing_same in checkout page
		$insert_array						= array();
		$insert_array['session_id']			= $sess_id;
		$insert_array['sites_site_id']		= $ecom_siteid;
		$insert_array['checkout_fieldname']	= 'checkout_billing_same';
		$insert_array['checkout_value']		= $_REQUEST['checkout_billing_same'];
		$db->insert_from_array($insert_array,'cart_checkout_values');
	}
	if($ecom_siteid==109 || $ecom_siteid==117)//live unipad id=109 ,local id=121
	{
	 save_CheckoutDetails_extrabilling();
	 save_CheckoutDetails_passport();
	}
	save_checkoutfields_register($chk_email);
}
function save_checkoutfields_register($chk_email)
{
	 global $ecom_hostname,$db,$show_cart_password,$ecom_siteid,$ecom_themename,$Settings_arr;
	 global $ecom_selfhttp;
		if($show_cart_password==1)
			{
			$alert = '';
				if($chk_email!='' and $_REQUEST['checkout_passwd']!='')
				{ 					
						$fieldRequired = array();
						$fieldDescription   = array();
					    $fieldEmail 		= array($chk_email);
						$fieldConfirm 		= array();
						$fieldConfirmDesc 	= array();
						$fieldNumeric 		= array();
						$fieldNumericDesc 	= array();
						if(serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc))
						{
						
                    
				/* Query Updated for FB user register on site */
					$sql_chk_custexist = "SELECT customer_id,customer_pwd_9501,customer_fbid
											  FROM  
												customers 
											  WHERE 
												customer_email_7503 = '".$chk_email."' 
												AND sites_site_id = ".$ecom_siteid;
						$ret_chk_custexist = $db->query($sql_chk_custexist);
						$num_chk_custexist = $db->num_rows($ret_chk_custexist);

					if($num_chk_custexist)
					{
							$alert = "CUST_REG_EMAIL_ALREADY_EXISTS";
					}
					if($alert=='')
					{
						// add the customers in customers table
						$insert_array									= array();
						$insert_array['sites_site_id']					= $ecom_siteid;
						$insert_array['customer_accounttype']           = 'personal';						
						$insert_array['customer_title']					= add_slash($_REQUEST['checkout_title']);
						$insert_array['customer_fname']					= add_slash($_REQUEST['checkout_fname']);
						$insert_array['customer_mname']					= add_slash($_REQUEST['checkout_mname']);
						$insert_array['customer_surname']				= add_slash($_REQUEST['checkout_surname']);
						$insert_array['customer_buildingname']			= add_slash($_REQUEST['checkout_building']);
						$insert_array['customer_streetname']			= add_slash($_REQUEST['checkout_street']);
						$insert_array['customer_towncity']				= add_slash($_REQUEST['checkout_city']);
						$insert_array['country_id']						= add_slash($_REQUEST['checkout_country']);
						$insert_array['customer_statecounty']			= add_slash($_REQUEST['checkout_state']);
						$insert_array['customer_phone']					= add_slash($_REQUEST['checkout_phone']);
						$insert_array['customer_fax']					= add_slash($_REQUEST['checkout_fax']);
						$insert_array['customer_phone']					= add_slash($_REQUEST['checkout_phone']);
						$insert_array['customer_mobile']				= add_slash($_REQUEST['checkout_mobile']);
						$insert_array['customer_postcode']				= add_slash($_REQUEST['checkout_zipcode']);
						
						$insert_array['customer_activated']				= 1;
						
						$insert_array['customer_addedon']				= 'curdate()';
						
						$insert_array['customer_allow_product_discount']		= 1;
						$insert_array['customer_use_bonus_points']				= 1;

						
						$insert_array['customer_email_7503']			= add_slash($_REQUEST['checkout_email']);
						//$insert_array['customer_pwd_9501']				= add_slash(base64_encode($_REQUEST['customer_pwd']));
						$insert_array['customer_pwd_9501']				= add_slash(md5($_REQUEST['checkout_passwd']));
						
							
						
						
						
						$insert_array['customer_prod_disc_newsletter_receive']	= 'Y';
						$insert_array['customer_in_mailing_list']				= 1;
						
						$insert_id = $db->insert_from_array($insert_array,'customers');
						$custinsert_id						= $db->insert_id();
						
					    $sql_chk_newsexist = "SELECT news_customer_id 
											  FROM  
												newsletter_customers 
											  WHERE 
												news_custemail = '".$chk_email."' 
												AND sites_site_id = ".$ecom_siteid;
						$ret_chk_newsexist = $db->query($sql_chk_newsexist);
						$num_chk_newsexist = $db->num_rows($ret_chk_newsexist);
						if($num_chk_newsexist==0)
						{
												
												
												
												$insert_array = array();
												$insert_array['news_title']		= add_slash($_REQUEST['checkout_title']);
												$insert_array['news_custname']	= add_slash($_REQUEST['checkout_fname']);
												$insert_array['news_custemail']	= add_slash($_REQUEST['checkout_email']);
												$insert_array['news_custphone']	= add_slash($_REQUEST['checkout_phone']);
												$insert_array['sites_site_id']	= $ecom_siteid;
												$insert_array['news_join_date']	= 'curdate()';
												$insert_array['customer_id']	= $custinsert_id;
												$db->insert_from_array($insert_array, 'newsletter_customers');
												$news_id						= $db->insert_id();
						}						
							set_session_var('ecom_login_customer',$custinsert_id);
							$curname = stripslashes($_REQUEST['customer_title']).stripslashes($_REQUEST['customer_fname']).' '.stripslashes($_REQUEST['customer_mname']).' '.stripslashes($_REQUEST['customer_surname']);
							set_session_var('ecom_login_customer_name',$curname);
							$curname = stripslashes($_REQUEST['customer_title']).stripslashes($_REQUEST['customer_fname']).' '.stripslashes($_REQUEST['customer_surname']);
							set_session_var('ecom_login_customer_shortname',$curname);	
							
							
							 $sql_send_conf_letter = "SELECT lettertemplate_subject,lettertemplate_title,
															lettertemplate_contents,lettertemplate_from   
													 FROM  
														general_settings_site_letter_templates  
													 WHERE  
														sites_site_id=$ecom_siteid 
														AND lettertemplate_letter_type = 'CUST_REG' 
														AND lettertemplate_disabled = 0 LIMIT 1";
							$ret_send_conf_letter = $db->query($sql_send_conf_letter);
							if($db->num_rows($ret_send_conf_letter))
							{
								$send_conf_letter = $db->fetch_array($ret_send_conf_letter);
								$to = $chk_email;
								$subject = stripslashes($send_conf_letter['lettertemplate_subject']);
								$message = '';
								$message = stripslashes($send_conf_letter['lettertemplate_contents']);
								$message = str_replace("[domain]", $ecom_hostname, $message);
								$message = str_replace("[uname]", $chk_email, $message);
								$message = str_replace("[pass]", $_REQUEST['checkout_passwd'], $message);								
	                            
								$headers 	= "From: $ecom_hostname	<".$send_conf_letter['lettertemplate_from'].">\n";
								$headers 	.= "MIME-Version: 1.0\n";
								$headers 	.= "Content-type: text/html; charset=iso-8859-1\n";							
								 $message;
								/* and now mail it */
								if($message!='')
							    {
								mail($to, $subject, $message, $headers);
								}
							}
							
							//Sending the report to the Admin
							$to_arr	= explode(",",$Settings_arr['order_confirmationmail']);
							$sql_send_inform_letter = "SELECT lettertemplate_subject,lettertemplate_title,
																lettertemplate_contents,lettertemplate_from   
														 FROM  
															general_settings_site_letter_templates  
														 WHERE  
															sites_site_id=$ecom_siteid 
															AND lettertemplate_letter_type = 'CUST_REG_ADMIN_NOTIFY' 
															AND lettertemplate_disabled = 0 LIMIT 1";
							$ret_send_inform_letter = $db->query($sql_send_inform_letter);
							if($db->num_rows($ret_send_inform_letter))
							{
								$send_inform_letter = $db->fetch_array($ret_send_inform_letter);
								//$to = $_REQUEST['customer_email'];
								$subject = stripslashes($send_inform_letter['lettertemplate_subject']);
								$message = '';
								$message = stripslashes($send_inform_letter['lettertemplate_contents']);
								$message = str_replace("[domain]", $ecom_hostname, $message);
								$message = str_replace("[uname]", $chk_email, $message);
								$message = str_replace("[date]", date("Y/m/d"), $message);
								$message = str_replace("[title]", $_REQUEST['checkout_title'], $message);
								$message = str_replace("[first_name]", $_REQUEST['checkout_fname'], $message);
								$message = str_replace("[second_name]", $_REQUEST['checkout_mname'], $message);
								$message = str_replace("[sur_name]", $_REQUEST['checkout_surname'], $message);
								$headers 	= "From: $ecom_hostname	<".$send_inform_letter['lettertemplate_from'].">\n";
								$headers 	.= "MIME-Version: 1.0\n";
								$headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
							    if($message!='')
							    {								
									for($i=0;$i<count($to_arr);$i++)
									{
										if ($to_arr[$i]!='')
											mail($to_arr[$i], $subject, $message, $headers);
									}
								}
							}
							$header = $Captions_arr['CUST_REG']['REGISTRATION_TREEMENU_TITLE'];
							$sucessmsg = $Captions_arr['CUST_REG']['CUSTOMER_REG_SUCESSFULL_HEADER'];
							$message=$Captions_arr['CUST_REG']['CUSTOMER_REG_SUCESSFULL_MESSAGE'];			
											
					}
					else
					{ 
								if($cartHtml=="")
								{ 
									require("themes/$ecom_themename/html/cartHtml.php");
									$cartHtml= new cart_Html(); // Creating an object for the cart_Html class
								}
								echo '
									<script type="text/javascript">
									window.location = "'.url_link('checkout.html?&alert1=1',1).'";
									</script>
								';
								exit;
					}
						
				}
				else
					{ 
								if($cartHtml=="")
								{ 
									require("themes/$ecom_themename/html/cartHtml.php");
									$cartHtml= new cart_Html(); // Creating an object for the cart_Html class
								}
								echo '
									<script type="text/javascript">
									window.location = "'.url_link('checkout.html?&alert1=2',1).'";
									</script>
								';
								exit;
					}
					
			}
		  }	

}

// Function to get the url to which the user to be redirected when clicked on the "Continue Shopping" button
function get_continueURL($passurl)
{
	global $ecom_hostname;
	global $ecom_selfhttp;
	if($passurl)
	{
		$ps_url = $passurl;
		if ($ps_url=='/')
			$ps_url = $ecom_selfhttp."$ecom_hostname";
		else
		{
			if($ecom_selfhttp=='http://')
			{
				if (substr($ps_url,0,(strlen($ecom_hostname)+7))!="http://$ecom_hostname")
					$ps_url = "http://$ecom_hostname".$ps_url;
			}
			elseif($ecom_selfhttp=='https://')
			{
				if (substr($ps_url,0,(strlen($ecom_hostname)+8))!="https://$ecom_hostname")
					$ps_url = "https://$ecom_hostname".$ps_url;
			}
					
		}
	}
	return $ps_url;
}
function order_error_handler($cartData)
{
    global $db,$ecom_siteid,$ecom_hostname,$from_iphone_app;
    global $ecom_selfhttp;
    $error = false;
    if(!trim($cartData["payment"]["type"]))
    {
        $error = true;
        $main_error = 'No Payment Type';
    }    
    elseif ($cartData["payment"]["type"]==1) // case of credit card
    {
        if(!trim($cartData["payment"]["method"]))
        {
            $error = true;
            $main_error = 'No Payment Method';
        } 
    }
    if($error)
    { 
        ob_start();
        print '<br><br>============================<br> Error Condition <br>============================<br>';
        print $main_error;
        print '<br><br>============================<br> Request Array<br>============================<br>';
        echo '<pre>';
        var_dump($_REQUEST);
        echo '</pre>';
        print '<br><br>============================<br> Server Array<br>============================<br>';
        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';
        print '<br><br>============================<br> Cart Data Array<br>============================<br>';
        echo '<pre>';
        var_dump($cartData);
        echo '</pre>';
        $mailcontent = ob_get_contents();
        ob_end_clean();
        $email_headers = "From: $ecom_hostname  <info@bshop.com>\n";
        $email_headers .= "MIME-Version: 1.0\n";
        $email_headers .= "Content-type: text/html; charset=iso-8859-1\n";
        mail('sony.joy@thewebclinic.co.uk','Save order error',$mailcontent,$email_headers);
       //mail('latheeshgeorge@gmail.com','Save order error',$mailcontent,$email_headers);
        // Redirecting user back to cart page_id
        if($from_iphone_app == false)
        {
        echo '
          <script type="text/javascript">
                alert("Sorry!! an error occured..");
                 window.location ="'.$ecom_selfhttp.$ecom_hostname.'/cart.html";
          </script>
        ';
	    }
	    else
	    {
		  echo "Sorry!! an error occured.."; 
		}
        exit;
    }
}

// Function to get the client ip address
function get_client_ip() {
	global $ecom_selfhttp;
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

// Function to save the order details
function Save_Order()
{
	global $db,$ecom_common_settings,$ecom_siteid,$ecom_hostname,$default_Currency_arr,$sitesel_curr,$ecom_3dsecured,$ecom_testing,
		$Settings_arr,$Captions_arr,$ecom_activate_invoice,$ecom_is_country_textbox,
		$ecom_allpricewithtax,$ecom_site_delivery_location_country_map,$from_iphone_app,$ecom_load_mobile_theme;
   global $ecom_selfhttp;
	$cust_id 				= get_session_var('ecom_login_customer'); // Getting the id of customer .. if logged in
	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	$sess_id 				= Get_session_Id_from(); // getting the id of current session
	if($_REQUEST['pret']==1) // case if paypal express is selected
	{
		$paytype_id		= $ecom_common_settings['paytypeCode']['credit_card']['paytype_id'];
		$paymethod_id 	= $ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_id'];
		// Updating the cart_supportdetails table with the payment type id and payment method id
		$sql_update = "UPDATE cart_supportdetails 
							SET 
								paytype_id=$paytype_id,
								paymethod_id=$paymethod_id 
							WHERE 
							 	session_id='".$sess_id."' 
							 	AND sites_site_id=$ecom_siteid 
							LIMIT 
									1";
		$db->query($sql_update);
	}
	$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
	order_error_handler($cartData);
	$ret_arr['payMethod']	= $cartData["payment"]["method"];
	$ret_arr['payType']		= $cartData["payment"]["type"];
	// Check whether products details exists.
	if (count($cartData['products'])==0)
		return;
	// Get all the checkout details saved for current site in current session
	$sql_checkoutDetails = "SELECT session_id,sites_site_id,checkout_fieldname,checkout_orgname,checkout_value
						FROM
							cart_checkout_values
						WHERE
							sites_site_id = $ecom_siteid
							AND session_id = '".$sess_id."'";
	$ret_checkoutDetails = $db->query($sql_checkoutDetails);
	if ($db->num_rows($ret_checkoutDetails))
	{
		while($row_checkoutDetails = $db->fetch_array($ret_checkoutDetails))
		{
			$checkout_arr[$row_checkoutDetails['checkout_fieldname']] = $row_checkoutDetails['checkout_value'];
			$checkoutorg_arr[$row_checkoutDetails['checkout_fieldname']] = $row_checkoutDetails['checkout_orgname'];
		}
	}
	// Get the details saved from cart page for this order
	$sql_cartDetails = "SELECT session_id,sites_site_id,giftwrap_req,giftwrap_msg_req,giftwrap_msg,giftwrap_ribbon_id,
								giftwrap_paper_id,giftwrap_card_id,giftwrap_bow_id,location_id,delopt_det_id,
								split_delivery,paytype_id,paymethod_id,promotionalcode_id,voucher_id,bonus_spending,
								total_product_deposit,cart_error_msg_ret,disable_id,zone_id,euvat_id,company_number,
								finance_id,finance_deposit 
							FROM
								cart_supportdetails
							WHERE
								session_id='".$sess_id."'
								AND sites_site_id=$ecom_siteid
							LIMIT 1";
	$ret_cartDetails = $db->query($sql_cartDetails);
	if($db->num_rows($ret_cartDetails))
	{
		$row_cartDetails = $db->fetch_array($ret_cartDetails);
	}
	// Shop Details
	$shop_id = 0; // shop id is given as 0 since the order is placed from web
	
	// Customer Details
	$additonial_address_new = (trim($checkout_arr['checkout_address2'])!='')?', '.$checkout_arr['checkout_address2']:'';
	
	$cust_id 					= get_session_var('ecom_login_customer'); // Getting the id of customer .. if logged in
	$cust_title 					= $checkout_arr['checkout_title'];
	$cust_companyname 	= $checkout_arr['checkout_comp_name'];
	$cust_fname		 		= $checkout_arr['checkout_fname'];
	$cust_mname		 		= $checkout_arr['checkout_mname'];
	$cust_surname			= $checkout_arr['checkout_surname'];
	$cust_buildingno 		= $checkout_arr['checkout_building'].$additonial_address_new;
	$cust_street	 		= $checkout_arr['checkout_street'];
	$cust_city		 		= $checkout_arr['checkout_city'];
	$cust_state		 		= $checkout_arr['checkout_state'];
	$cust_country	 		= $checkout_arr['checkout_country'];
	$cust_zip		 		= $checkout_arr['checkout_zipcode'];
	$cust_phone		 		= $checkout_arr['checkout_phone'];
	$cust_mobile	 			= $checkout_arr['checkout_mobile'];
	$cust_fax	 				= $checkout_arr['checkout_fax'];
	$cust_email	 			= $checkout_arr['checkout_email'];
	$cust_notes	 			= $checkout_arr['checkout_notes'];
	
	$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
											FROM 
												general_settings_site_checkoutfields 
											WHERE 
												sites_site_id = $ecom_siteid 
												AND field_hidden=0 
												AND field_type='PERSONAL' 
											ORDER BY 
												field_order";
	$ret_checkout = $db->query($sql_checkout);
	if($db->num_rows($ret_checkout))
	{						
		$atleast_one_personal_req_blank = false;
		while($row_checkout = $db->fetch_array($ret_checkout))
		{		
			// Section to handle the case of required fields
			if($row_checkout['field_req']==1)
			{
				if($row_checkout['field_key']=='checkout_email')
				{
					if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",trim($checkout_arr[$row_checkout['field_key']]))) 
					{	
						$atleast_one_personal_req_blank = true;
					}
				}
				if (trim($checkout_arr[$row_checkout['field_key']])=='')
				{
					$atleast_one_personal_req_blank = true;
				}
				
			}
		}
		if($atleast_one_personal_req_blank)
		{
			echo "Sorry !! an error occured";
			echo '<script type="text/javascript">
			alert("Incomplete data: Please fill in all required fields");
			window.location="'.$ecom_selfhttp.$ecom_hostname.'/checkout.html'.'";</script>';
			exit;
		}
		
	}		
	
	
	if($ecom_is_country_textbox!=1) // case if country is displayes as drop down box .. so the name of the country should be picked from database
	{
		$sql_countryname = "SELECT country_name 
								FROM 
									general_settings_site_country 
								WHERE 
									country_id='".$cust_country."' 
									AND sites_site_id = $ecom_siteid  
								LIMIT 
									1";
		$ret_countryname = $db->query($sql_countryname);
		if($db->num_rows($ret_countryname))
		{
			$row_countryname 	= $db->fetch_array($ret_countryname);
			$cust_country		= stripslashes($row_countryname['country_name']);
		}
	}
	
	// Delivery Details
	$del_same			= $checkout_arr['checkout_billing_same'];
	if($del_same=='N') // Delivery address is not same as that of billing address
	{
		$additonial_address_new = (trim($checkout_arr['checkoutdelivery_address2'])!='')?', '.$checkout_arr['checkoutdelivery_address2']:'';
		$del_title 			= $checkout_arr['checkoutdelivery_title'];
		$del_companyname 	= $checkout_arr['checkoutdelivery_comp_name'];
		$del_fname		 	= $checkout_arr['checkoutdelivery_fname'];
		$del_mname		 	= $checkout_arr['checkoutdelivery_mname'];
		$del_surname		= $checkout_arr['checkoutdelivery_surname'];
		$del_buildingno 	= $checkout_arr['checkoutdelivery_building'].$additonial_address_new;
		$del_street	 		= $checkout_arr['checkoutdelivery_street'];
		$del_city		 	= $checkout_arr['checkoutdelivery_city'];
		$del_state		 	= $checkout_arr['checkoutdelivery_state'];
		$del_country	 	= $checkout_arr['checkoutdelivery_country'];
		$del_zip		 	= $checkout_arr['checkoutdelivery_zipcode'];
		$del_phone		 	= $checkout_arr['checkoutdelivery_phone'];
		$del_mobile	 		= $checkout_arr['checkoutdelivery_mobile'];
		$del_fax	 		= $checkout_arr['checkoutdelivery_fax'];
		$del_email	 		= $checkout_arr['checkoutdelivery_email'];
	}
	else // Case delivery address is same as that of billing address
	{
		$additonial_address_new = (trim($checkout_arr['checkout_address2'])!='')?', '.$checkout_arr['checkout_address2']:'';
		$del_title 			= $checkout_arr['checkout_title'];
		$del_companyname 	= $checkout_arr['checkout_comp_name'];
		$del_fname		 	= $checkout_arr['checkout_fname'];
		$del_mname		 	= $checkout_arr['checkout_mname'];
		$del_surname		= $checkout_arr['checkout_surname'];
		$del_buildingno 	= $checkout_arr['checkout_building'].$additonial_address_new;
		$del_street	 		= $checkout_arr['checkout_street'];
		$del_city		 	= $checkout_arr['checkout_city'];
		$del_state		 	= $checkout_arr['checkout_state'];
		$del_country	 	= $checkout_arr['checkout_country'];
		$del_zip		 	= $checkout_arr['checkout_zipcode'];
		$del_phone		 	= $checkout_arr['checkout_phone'];
		$del_mobile	 		= $checkout_arr['checkout_mobile'];
		$del_fax	 		= $checkout_arr['checkout_fax'];
		$del_email	 		= $checkout_arr['checkout_email'];
	}
	$hold_del_country = $del_country;
	if($ecom_is_country_textbox!=1) // case if country is displayes as drop down box .. so the name of the country should be picked from database
	{
		$sql_countryname = "SELECT country_name 
								FROM 
									general_settings_site_country 
								WHERE 
									country_id='".$del_country."' 
									AND sites_site_id = $ecom_siteid  
								LIMIT 
									1";
		$ret_countryname = $db->query($sql_countryname);
		if($db->num_rows($ret_countryname))
		{
			$row_countryname 	= $db->fetch_array($ret_countryname);
			$del_country		= stripslashes($row_countryname['country_name']);
		}
	}
	
	$del_arr['checkout_title'] 		= $del_title;
	$del_arr['checkout_comp_name'] 	= $del_companyname;
	$del_arr['checkout_fname'] 		= $del_fname;
	$del_arr['checkout_mname'] 		= $del_mname;
	$del_arr['checkout_surname'] 	= $del_surname;
	$del_arr['checkout_building'] 	= $del_buildingno;
	$del_arr['checkout_street'] 	= $del_street;
	$del_arr['checkout_city'] 		= $del_city;
	$del_arr['checkout_state'] 		= $del_state;
	$del_arr['checkout_country'] 	= $del_country;
	$del_arr['checkout_zipcode'] 	= $del_zip;
	$del_arr['checkout_phone'] 		= $del_phone;
	$del_arr['checkout_mobile'] 	= $del_mobile;
	$del_arr['checkout_fax'] 		= $del_fax;
	$del_arr['checkout_email'] 		= $del_email;
	$del_arr['same']				= $del_same; // adding the value of the field whether delivery address is same as that of billing address
	$del_arr['billing_det']			= $checkout_arr; // this is the array which contain the billing details. this is done to pass it to the processpayment function along with delivery details
	// Notes
	$ord_notes 						= $checkout_arr['checkout_notes'];

	// Gift Wrap Details
	$ord_giftwrap_req				= ($row_cartDetails['giftwrap_req']==1)?'Y':'N';
	if($ord_giftwrap_req=='Y')
	{
		$ord_giftwrap_per  			= $cartData["totals"]["giftwrap_type"];
		if($row_cartDetails['giftwrap_msg_req']==1)
		{
			$ord_giftwrapmessage_req	= 'Y';
			$ord_giftwrap_messsage		= add_slash($row_cartDetails['giftwrap_msg']);
		}
		else
			$ord_giftwrapmessage_req	= 'N';
		if ($cartData["totals"]["giftwrap_ribbon_name"])
		{
			$ord_ribbonname		= $cartData["totals"]["giftwrap_ribbon_name"];
			$ord_ribbonprice	= $cartData["totals"]["giftwrap_ribbon_price"];
		}
		else
		{
			$ord_ribbonname		= '';
			$ord_ribbonprice	= 0;
		}
		if ($cartData["totals"]["giftwrap_paper_name"])
		{
			$ord_papername		= $cartData["totals"]["giftwrap_paper_name"];
			$ord_paperprice		= $cartData["totals"]["giftwrap_paper_price"];
		}
		else
		{
			$ord_papername		= '';
			$ord_paperprice		= 0;
		}
		if ($cartData["totals"]["giftwrap_card_name"])
		{
			$ord_cardname		= $cartData["totals"]["giftwrap_card_name"];
			$ord_cardprice		= $cartData["totals"]["giftwrap_card_price"];
		}
		else
		{
			$ord_cardname		= '';
			$ord_cardprice		= 0;
		}
		if ($cartData["totals"]["giftwrap_bow_name"])
		{
			$ord_bowname		= $cartData["totals"]["giftwrap_bow_name"];
			$ord_bowprice		= $cartData["totals"]["giftwrap_bow_price"];
		}
		else
		{
			$ord_bowname		= '';
			$ord_bowprice		= 0;
		}
		$ord_giftwrap_total = $cartData["totals"]["giftwrap"];
	}
	else
	{
		$ordergiftwrap_per  		= '';
		$ord_giftwrapmessage_req	= 'N';
		$ord_giftwrap_messsage		= '';
		$ord_ribbonname				= '';
		$ord_ribbonprice			= 0;
		$ord_papername				= '';
		$ord_paperprice				= 0;
		$ord_cardname				= '';
		$ord_cardprice				= 0;
		$ord_bowname				= '';
		$ord_bowprice				= 0;
		$ord_giftwrap_total			= 0;
	}

	// Delivery Type Section
	$deliverydet_Arr 	= get_Delivery_Display_Details();
	$ord_delivery_type 	= $deliverydet_Arr['delivery_type'];
	if($ord_delivery_type!='None')
	{
		if($row_cartDetails['location_id'])
		{
			// Get the name of location from the current site for current delivery method
			$sql_loc = "SELECT location_name
							FROM
								delivery_site_location
							WHERE
								delivery_methods_deliverymethod_id =".$deliverydet_Arr['delivery_id']."
								AND sites_site_id = $ecom_siteid
								AND location_id = ".$row_cartDetails['location_id']. "
							LIMIT
								1";
			$ret_loc = $db->query($sql_loc);
			if ($db->num_rows($ret_loc))
			{
				$row_loc = $db->fetch_array($ret_loc);
			}
			$ord_delivery_location = $row_loc['location_name'];
		}
		else
			$ord_delivery_location = '';

		// Check whether any delivery group exists for the site
		if ($row_cartDetails['delopt_det_id'])
		{
			// Get the name of location from the current site for current delivery method
			$sql_grp = "SELECT delivery_group_name
							FROM
								general_settings_site_delivery_group
							WHERE
								sites_site_id = $ecom_siteid
								AND delivery_group_id = ".$row_cartDetails['delopt_det_id']. "
							LIMIT
								1";
			$ret_grp = $db->query($sql_grp);
			if ($db->num_rows($ret_grp))
			{
				$row_grp = $db->fetch_array($ret_grp);
				$ord_delivery_option = $row_grp['delivery_group_name'];
			}
			else
				$ord_delivery_option = '';
		}
		$ord_split_deliveryreq 	= ($row_cartDetails['split_delivery']==1)?'Yes':'No';
		$ord_extra_shipping		= $cartData["totals"]["extraShipping"];
		$ord_deliveryonly 		= $cartData["totals"]["delivery_alone"];
		$ord_deliverytotal 		= $cartData["totals"]["delivery"];
	}
	else
	{
			$ord_delivery_location 	= '';
			$ord_delivery_option 	= '';
			/*$ord_split_deliveryreq 	= 'N';
			$ord_extra_shipping		= 0;
			$ord_deliveryonly 		= 0;
			$ord_deliverytotal 		= 0;*/
			if($cartData["totals"]["extraShipping"])
			{
				$ord_split_deliveryreq 	= ($row_cartDetails['split_delivery']==1)?'Yes':'No';
				$ord_extra_shipping		= $cartData["totals"]["extraShipping"];
				$ord_deliveryonly 		= $cartData["totals"]["delivery_alone"];
				$ord_deliverytotal 		= $cartData["totals"]["delivery"];
			}
	}
	$ord_payment_type 		= $cartData["payment"]["type"];
	$ord_payment_method		= $cartData["payment"]["method"];

	// Bonus Points Section
	if($cartData["bonus"]["value"])
	{
		$ord_bonus_rate		= $cartData["bonus"]["rate"];
		$ord_bonus_value	= $cartData["bonus"]["value"];
		$ord_bonus_points	= $cartData["bonus"]["spending"];
	}
	else
	{
		$ord_bonus_rate		= 0;
		$ord_bonus_value	= 0;
		$ord_bonus_points	= 0;
	}
	
	/* Donate bonus Start */
	if($cartData["bonus"]["donating"])
	{
		$ord_bonus_donating = ($cartData["bonus"]["donating"])?$cartData["bonus"]["donating"]:0;
		if($ord_bonus_donating<0)
			$ord_bonus_donating = 0; 
	}
	/* Donate bonus End */
	
	if($cartData["totals"]["bonus"] and $cust_id)
		$ord_points_earned = $cartData["totals"]["bonus"];
	else
		$ord_points_earned = 0;



	// Currency
	if($sitesel_curr != $default_Currency_arr['currency_id'])
	{
		$sql_curr  = "SELECT curr_rate,curr_sign_char,curr_code,curr_numeric_code,curr_margin 
						FROM
						general_settings_site_currency
						WHERE
						currency_id=$sitesel_curr
						AND sites_site_id=$ecom_siteid";
		$ret_curr  = $db->query($sql_curr);
		if($db->num_rows($ret_curr))
		{
			$row_curr  		= $db->fetch_array($ret_curr);
			$curr_rate 			= ($row_curr['curr_rate']+$row_curr['curr_margin']);
			$curr_sign 			= $row_curr['curr_sign_char'];
			$curr_code 		= $row_curr['curr_code'];
			$curr_num_code 	= $row_curr['curr_numeric_code'];
		}
	}
	else
	{
		$curr_rate 		= 1;//+$default_Currency_arr['curr_margin']);
		$curr_sign 		= $default_Currency_arr['curr_sign_char'];
		$curr_code 		= $default_Currency_arr['curr_code'];
		$curr_num_code 	= $default_Currency_arr['curr_numeric_code'];
	}
	$ord_currency_rate 	= $curr_rate;
	$ord_currency_code 	= $curr_code;
	$ord_currency_sign 	= $curr_sign;
	$ord_currency_ncode	= $curr_num_code;


	$ord_total			= $cartData["totals"]["bonus_price"];
	if($_REQUEST['store_reduceval']>0)
	{
		$ord_deposit_amt = trim($_REQUEST['store_reduceval']);
	}
	//else

	// Tax
	$ord_totaltax		= $cartData["totals"]["tax"];
	$ord_tax_arr		= $cartData["tax"]; // This is an array
	$ord_tax_to_delivery= ($cartData["totals"]["tax_applied_to_delivery"])?$cartData["totals"]["tax_applied_to_delivery"]:0;
	$ord_tax_to_giftwrap= $cartData["totals"]["tax_applied_to_giftwrap"];

	// Gift voucher / Promotional Code
	$ord_gift_id	= 0;
	$ord_gift_code 	= '';
	$ord_gift_val	= 0;
	$ord_prom_id	= 0;
	$ord_prom_code 	= '';
	$ord_prom_type	= '';
	$ord_prom_val	= '';
	if($cartData["bonus"]['type']=='voucher')
	{
		$ord_gift_id	= $cartData["bonus"]['voucher']['voucher_id'];
		$ord_gift_code 	= $cartData["bonus"]['voucher']['voucher_number'];
		$ord_gift_val	= $cartData['totals']['lessval'];
	}
	elseif($cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']	== 'product')
	{
		if($cartData['totals']['promotional_type']	== 'product')
		{
			$ord_prom_id	= $cartData['totals']['promotional_code_id'];
			$ord_prom_code 	= $cartData['totals']['promotional_code'];
		}
		elseif($cartData["bonus"]['type']=='promotional') 
		{
			if($cartData['totals']['lessval']>0)// consider only if promotional discount is applied
			{
				$ord_prom_id	= $cartData["bonus"]['promotion']['code_id'];
				$ord_prom_code 	= $cartData["bonus"]['promotion']['code_number'];
			}	
		}
		$ord_prom_type	= $cartData['totals']['pro_type'];
		$ord_prom_val	= $cartData['totals']['lessval'];
	}

	// Customer Discount
	if ($cartData["savings"]["customer"])
	{
		$ord_customer_or_corporate_disc	= 'CUST';
		$ord_customer_discount_type		= $cartData['discounts']["customer_type"];
		$ord_customer_discount_precent	= $cartData["discounts"]["customer"];
		$ord_customer_discount_value	= $cartData["savings"]["customer"];
	}
	else
	{
		$ord_customer_or_corporate_disc	= 'CORP';
		$ord_customer_discount_type		= '';
		$ord_customer_discount_precent	= $cartData["discounts"]["corporate"];
		$ord_customer_discount_value	= $cartData["savings"]["corporate"];
	}
	/*done for datetime for delivery */
	if($Settings_arr['enable_location_datetime']==1)
	{
	$deli_date  = $cartData["delivery"]["del_date"];
	$deli_time  = $cartData["delivery"]["del_time"];
	}
	/*end for datetime for delivery */


	// Inserting the details to orders table and get the order id
	$insert_array										= array();
	$insert_array['customers_customer_id']				= $cust_id ;
	$insert_array['sites_site_id']						= $ecom_siteid ;
	$insert_array['sites_shops_shop_id']				= $shop_id;
	$insert_array['order_date']							= 'now()';

	// Billing
	$insert_array['order_custtitle']					= addslashes(stripslashes($cust_title));
	$insert_array['order_custfname']					= addslashes(stripslashes($cust_fname));
	$insert_array['order_custmname']					= addslashes(stripslashes($cust_mname));
	$insert_array['order_custsurname']					= addslashes(stripslashes($cust_surname));
	$insert_array['order_custcompany']					= addslashes(stripslashes($cust_companyname));
	$insert_array['order_buildingnumber']				= addslashes(stripslashes($cust_buildingno));
	$insert_array['order_street']						= addslashes(stripslashes($cust_street));
	$insert_array['order_city']							= addslashes(stripslashes($cust_city));
	$insert_array['order_state']						= addslashes(stripslashes($cust_state));
	$insert_array['order_country']						= addslashes(stripslashes($cust_country));
	$insert_array['order_custpostcode']					= addslashes(stripslashes($cust_zip));
	$insert_array['order_custphone']					= addslashes(stripslashes($cust_phone));
	$insert_array['order_custmobile']					= addslashes(stripslashes($cust_mobile));
	$insert_array['order_custfax']						= addslashes(stripslashes($cust_fax));
	$insert_array['order_custemail']					= addslashes(stripslashes($cust_email));
	$insert_array['order_notes']						= addslashes(stripslashes($cust_notes));

	$insert_array['order_giftwrap']						= addslashes(stripslashes($ord_giftwrap_req));
	$insert_array['order_giftwrap_minprice']			= ($cartData["totals"]["giftwrap_minprice"])?$cartData["totals"]["giftwrap_minprice"]:0;
	$insert_array['order_giftwrap_per']					= addslashes(stripslashes($ord_giftwrap_per));
	$insert_array['order_giftwrapmessage']				= addslashes(stripslashes($ord_giftwrapmessage_req));
	$insert_array['order_giftwrap_message_charge']		= ($cartData["totals"]["giftwrap_msg_charge"])?$cartData["totals"]["giftwrap_msg_charge"]:0;

	$insert_array['order_giftwrapmessage_text']			= addslashes(stripslashes($ord_giftwrap_messsage));
	$insert_array['order_giftwraptotal']				= addslashes(stripslashes($ord_giftwrap_total));

	$insert_array['order_deliverytype']					= addslashes(stripslashes($ord_delivery_type));
	$insert_array['order_deliverylocation']				= addslashes(stripslashes($ord_delivery_location));
	$insert_array['order_deliveryprice_only']			= addslashes(stripslashes($ord_deliveryonly));
	$insert_array['order_delivery_option']				= addslashes(stripslashes($ord_delivery_option));
	$insert_array['order_deliverytotal']				= ($ord_deliverytotal + $ord_extra_shipping);
	/*done for datetime for delivery */
	if($Settings_arr['enable_location_datetime']==1)
	{
	$insert_array['order_delivery_date']				= addslashes(stripslashes($deli_date));
	$insert_array['order_delivery_time']				= addslashes(stripslashes($deli_time));
	}
	/*end for datetime for delivery */

	$delivyer_tax_part_total = $extradelivyer_tax_part_total = 0;
	if($ecom_allpricewithtax==1) // case if special tax calculation is set for the website
	{
		$sql 	= "SELECT tax_val 
				FROM 
					general_settings_site_tax 
				WHERE 
					sites_site_id=$ecom_siteid 
					AND tax_active=1 
				LIMIT 
				1";
		$ret_tax = $db->query($sql);
		$web_taxable_part = $disc_taxable_part = 0;
		$extraship_taxable_part = 0;
		$delivyer_tax_part_total = $extradelivyer_tax_part_total = 0;
		if ($db->num_rows($ret_tax))
		{
			$row_tax = $db->fetch_array($ret_tax);
			$taxrate = $row_tax['tax_val'];
		}	
		if($cartData["totals"]['location_tax_applicable']=='Y')
		{
			//deliveryprice*Tax Rate/(100+Tax Rate)
			$total_del_new 			= ($ord_deliverytotal);
			$delivyer_tax_part_total 	= ($total_del_new*$taxrate)/(100+$taxrate);
		}
		$insert_array['order_specialtax_deliveryamt']		= $delivyer_tax_part_total;
		$insert_array['order_specialtax_orgdeliveryamt']	= $delivyer_tax_part_total;
		$insert_array['order_specialtax_calculation']		= 1;
		
	}
	$insert_array['order_splitdeliveryreq']				= addslashes(stripslashes($ord_split_deliveryreq));
	$insert_array['order_extrashipping']				= addslashes(stripslashes($ord_extra_shipping));
	$insert_array['order_freedeliverytype']				= addslashes(stripslashes($cartData["totals"]["freedeliverytype"]));

	$insert_array['order_bonusrate']				= addslashes(stripslashes($ord_bonus_rate));
	$insert_array['order_bonuspoint_discount']			= addslashes(stripslashes($ord_bonus_value));
	$insert_array['order_bonuspoints_used']				= addslashes(stripslashes($ord_bonus_points));
	$insert_array['order_bonuspoint_inorder']			= addslashes(stripslashes($ord_points_earned));
	
	/* Donate bonus Start */
	$insert_array['order_bonuspoints_donated']			= addslashes(stripslashes(($ord_bonus_donating)?$ord_bonus_donating:0));
	/* Donate bonus End */
	
	
	

	$insert_array['order_paymenttype']					= addslashes(stripslashes($cartData["payment"]["type"]));
	if ($cartData["payment"]["type"]=='credit_card')
		$insert_array['order_paymentmethod']			= addslashes(stripslashes($cartData["payment"]["method"]['paymethod_key']));
	else
		$insert_array['order_paymentmethod']			= '';

	$insert_array['order_status']						= 'NEW';

	$insert_array['order_currency_code']				= addslashes(stripslashes($ord_currency_code));
	$insert_array['order_currency_symbol']				= addslashes(stripslashes($ord_currency_sign));
	$insert_array['order_currency_numeric_code']		= addslashes(stripslashes($ord_currency_ncode));
	$insert_array['order_currency_convertionrate']		= $ord_currency_rate;

	$insert_array['order_tax_total']					= $cartData["totals"]["tax"];
	$insert_array['order_tax_to_delivery']				= $ord_tax_to_delivery;
	$insert_array['order_tax_to_giftwrap']				= ($ord_tax_to_giftwrap)?$ord_tax_to_giftwrap:0;

	$insert_array['order_customer_or_corporate_disc']	= addslashes(stripslashes($ord_customer_or_corporate_disc));
	$insert_array['order_customer_discount_type']		= addslashes(stripslashes($ord_customer_discount_type));
	$insert_array['order_customer_discount_percent']	= $ord_customer_discount_precent;
	$insert_array['order_customer_discount_value']		= ($ord_customer_discount_value)?$ord_customer_discount_value:0;


	if($cartData["totals"]["deposit_less"]>0)
		$insert_array['order_deposit_amt']				= ($ord_total - $cartData["totals"]["deposit_less"]); // total of product deposit amount
	else
		$insert_array['order_deposit_amt']				= 0;
	$insert_array['order_totalprice']					= $ord_total;
	$insert_array['order_subtotal']						= $cartData["totals"]["subtotal"];
	//$insert_array['order_deposit_amt']				= $ord_deposit_amt; // total or product deposit amount
	
	$insert_array['order_pre_order']					= $cartData["pre_order"]; // full/part/none

	$insert_array['gift_vouchers_voucher_id']			= $ord_gift_id;
	$insert_array['order_gift_voucher_number']			= add_slash($ord_gift_code);

	$insert_array['promotional_code_code_id']			= $ord_prom_id;
	$insert_array['promotional_code_code_number']		= add_slash($ord_prom_code);
	$insert_array['order_able2buy_cgid']				= ($_REQUEST['cgid'])?add_slash($_REQUEST['cgid']):'';


	// Hit to sale report handling section 
	$cpc_kw												= ($_SESSION['cpc_keyword']=='')?'':$_SESSION['cpc_keyword'];
	$cpc_se_id											= (!$_SESSION['cpc_se_id'])?0:$_SESSION['cpc_se_id'];
	$cpc_click_id										= (!$_SESSION['cpc_click_id'])?0:$_SESSION['cpc_click_id'];
	$cpc_click_pm_id									= (!$_SESSION['cpc_click_pm_id'])?0:$_SESSION['cpc_click_pm_id'];
	$insert_array['order_cpc_keyword']					= addslashes($cpc_kw);
	$insert_array['order_cpc_se_id']					= $cpc_se_id;
	$insert_array['order_cpc_click_id']					= $cpc_click_id;
	$insert_array['order_cpc_click_pm_id']				= $cpc_click_pm_id;
	
	// Cost Per Click
	$const_ids 											= trim(get_session_var('COST_PER_CLICK'));
	$insert_array['order_cost_per_click_id']			= ($const_ids=='')?'':$const_ids;
	if($from_iphone_app == true)
	{
			$insert_array['order_placed_from']			= 'IPHONE';
	}
	else if($ecom_load_mobile_theme == true)
	{    
			$insert_array['order_placed_from']			= 'MOBILE';
	}
	
	$disable_id = 0;
	if ($row_cartDetails['disable_id'])
	{
		$disable_id = $row_cartDetails['disable_id'];
	}	
	$insert_array['disable_id']				= $disable_id;
	//section for euro zone for the lsforklift
	$zone_id = 0 ;
	if($row_cartDetails['zone_id']>0)
	{
		$zone_id = $row_cartDetails['zone_id'];
	}
	$insert_array['zone_id'] = $zone_id;
	
	$euvat_id = 0 ;
	if($row_cartDetails['euvat_id']>0)
	{
		$euvat_id = $row_cartDetails['euvat_id'];
	}
	$insert_array['euvat_id'] = $euvat_id;
			
	$company_num = "";
	if($row_cartDetails['company_number'])
	{
	  $company_num = $row_cartDetails['company_number'];
	}
	$insert_array['company_number'] = $company_num;
	$insert_array['order_ip'] = get_client_ip();
	
	
	
	//end section for euro zone for the lsforklift
	// Executing the insert statement
	$db->insert_from_array($insert_array,'orders');
	$order_id = $db->insert_id();
	if($ecom_siteid==109 || $ecom_siteid==117)//live unipad id=109 ,local id=121
	{
	save_extrabilling_details_order($order_id);
	save_passport_details_order($order_id);

	}
	/*
	 Section for the incomplete mail sending section details inserting
	 */ 
	 insert_cartids_into_orders($order_id,$sess_id);
	/* ------------------- 4 min finance - start ---------------------------*/
	if ($cartData["payment"]["type"]=='4min_finance')
	{
		$sql_findet = "SELECT * FROM finance_details WHERE finance_id = ".$row_cartDetails['finance_id']." LIMIT 1";
		$ret_findet = $db->query($sql_findet);
		if($db->num_rows($ret_findet))
		{
			$row_findet = $db->fetch_array($ret_findet);
		}
		$insert_array = array();
		$insert_array['orders_order_id'] 	= $order_id;
		$insert_array['finance_rate'] 		= $row_findet['finance_rate'];
		$insert_array['finance_id'] 		= $row_cartDetails['finance_id'];
		$insert_array['finance_name'] 		= $row_findet['finance_name'];
		$insert_array['finance_deposit'] 	= $row_cartDetails['finance_deposit'];
		$insert_array['finance_code'] 		= $row_findet['finance_code'];
		$db->insert_from_array($insert_array,'order_finance_details');
	}
	/* ------------------- 4 min finance - end ---------------------------*/
	
	
	$order_invoice_id = 0;
	// Check whether invoice feature is activated for current site
	if($ecom_activate_invoice==1) // case if it is activated
	{
		// Making an entry to the order_invoice table to get the unique invoice id
		$sql_insert = "INSERT INTO order_invoice 
							SET 
								orders_order_id = $order_id ";
		$db->query($sql_insert);
		$order_invoice_id = $db->insert_id();						
	}
	$address = $cust_buildingno . "\n" . $cust_street. "\n". $cust_city . "\n" .$cust_state . "\n" .
				$cust_country . "\n" . $cust_zip;

	// Inserting the delivery details
	$insert_array								= array();
	$insert_array['orders_order_id']			= $order_id;
	$insert_array['delivery_title']				= addslashes(stripslashes($del_title));
	$insert_array['delivery_fname']				= addslashes(stripslashes($del_fname));
	$insert_array['delivery_mname']				= addslashes(stripslashes($del_mname));
	$insert_array['delivery_lname']				= addslashes(stripslashes($del_surname));
	$insert_array['delivery_companyname']		= addslashes(stripslashes($del_companyname));
	$insert_array['delivery_buildingnumber']	= addslashes(stripslashes($del_buildingno));
	$insert_array['delivery_street']			= addslashes(stripslashes($del_street));
	$insert_array['delivery_city']				= addslashes(stripslashes($del_city));
	$insert_array['delivery_state']				= addslashes(stripslashes($del_state));
	$insert_array['delivery_country']			= addslashes(stripslashes($del_country));
	$insert_array['delivery_zip']				= addslashes(stripslashes($del_zip));
	$insert_array['delivery_phone']				= addslashes(stripslashes($del_phone));
	$insert_array['delivery_fax']				= addslashes(stripslashes($del_fax));
	$insert_array['delivery_mobile']			= addslashes(stripslashes($del_mobile));
	$insert_array['delivery_email']				= addslashes(stripslashes($del_email));
	$insert_array['delivery_completed']			= 'N';
	$insert_array['delivery_same_as_billing']	= $checkout_arr['checkout_billing_same'];

	$db->insert_from_array($insert_array,'order_delivery_data');
	$order_delivery_id = $db->insert_id();

	// Making Entries to order_dynamicvalues
	// Get the dynamic values stored in cart_checkout_values for current session for current site. Pick only those records with
	// the values in checkout_fieldname starting with e_
	$sql_dynamic = "SELECT checkout_fieldname,checkout_orgname,checkout_value
						FROM
							cart_checkout_values
						WHERE
							sites_site_id = $ecom_siteid
							AND session_id = '".$sess_id."'
							AND LEFT(checkout_fieldname,2)='e_'";
	$ret_dynamic = $db->query($sql_dynamic);
	if ($db->num_rows($ret_dynamic))
	{
		while ($row_dynamic = $db->fetch_array($ret_dynamic))
		{
			// Get the section details for current element
			$sql_sec = "SELECT a.section_id,a.section_name,a.position
							FROM
								element_sections a,elements b
							WHERE
								b.element_name = '".$row_dynamic['checkout_fieldname']."'
								AND a.section_id=b.element_sections_section_id
								AND a.sites_site_id = $ecom_siteid
							LIMIT
								1";
			$ret_sec = $db->query($sql_sec);
			if ($db->num_rows($ret_sec))
			{
				$row_sec = $db->fetch_array($ret_sec);
				$insert_array						= array();
				$insert_array['orders_order_id']	= $order_id;
				$insert_array['section_id']			= $row_sec['section_id'];
				$insert_array['dynamic_label']		= addslashes(stripslashes($row_dynamic['checkout_orgname']));
				$insert_array['dynamic_value']		= addslashes(stripslashes($row_dynamic['checkout_value']));
				$insert_array['section_name']		= addslashes(stripslashes($row_sec['section_name']));
				$insert_array['position']			= addslashes(stripslashes($row_sec['position']));
				$db->insert_from_array($insert_array,'order_dynamicvalues');
			}
		}
	}

	// Inserting the required data to order_tax_details table
	// Tax total is stored in orders table. order_tax_details table stores the details of which all
	// taxes applied and value for each
	if($cartData["totals"]["tax"])
	{
		foreach($cartData["tax"] as $tax)
		{
			$taxname 							= $tax['tax_name'];
			$taxpercentage 						= $tax['tax_val'];
			$taxcharge							= $tax['charge'];

			$insert_array						= array();
			$insert_array['orders_order_id']	= $order_id;
			$insert_array['tax_name']			= addslashes(stripslashes($taxname));
			$insert_array['tax_percent']		= $taxpercentage;
			$insert_array['tax_charge']			= $taxcharge;
			$db->insert_from_array($insert_array,'order_tax_details');
		}
	}

	// Inserting the details to order_voucher table
	if($cartData["bonus"]['type']=='voucher') // do the following only if gift voucher exists
	{
		$insert_array							= array();
		$insert_array['orders_order_id']		= $order_id;
		$insert_array['customer_id']			= ($cust_id)?$cust_id:0;
		$insert_array['voucher_id']				= $cartData["bonus"]['voucher']['voucher_id'];
		$insert_array['voucher_no']				= $cartData["bonus"]['voucher']['voucher_number'];
		$insert_array['actual_voucher_value']	= $cartData["bonus"]['voucher']['voucher_value'];
		$insert_array['voucher_value_used']		= $cartData['totals']['lessval'];
		$insert_array['voucher_type']			= $cartData["bonus"]['voucher']['voucher_type'];
		$db->insert_from_array($insert_array,'order_voucher');
	}

	// Inserting the details to order_promotional_code
	if($cartData["bonus"]['type']=='promotional' or $cartData['totals']['promotional_type']	== 'product') // do the following only if promotional code exists
	{
		if($cartData['totals']['promotional_type']	== 'product') // case or promotional code type is 'product'
		{
			$insert_array								= array();
			$insert_array['orders_order_id']			= $order_id;
			$insert_array['promotional_code_code_id']	= $cartData['totals']['promotional_code_id'];
			$insert_array['customers_customer_id']		= ($cust_id)?$cust_id:0;
			$insert_array['code_type']					= 'product';
			$insert_array['code_number']				= $cartData['totals']['promotional_code'];
			$db->insert_from_array($insert_array,'order_promotional_code');
			$code_num		= $cartData['totals']['promotional_code'];
			$code_id		= $cartData['totals']['promotional_code_id'];
		}
		elseif($cartData["bonus"]['type'] == 'promotional') // promotional code type other than 'product' type.
		{
			$insert_array								= array();
			$insert_array['orders_order_id']			= $order_id;
			$insert_array['promotional_code_code_id']	= $cartData["bonus"]['promotion']['code_id'];
			$insert_array['customers_customer_id']		= ($cust_id)?$cust_id:0;;
			$insert_array['code_type']					= $cartData['totals']['pro_type'];
			$insert_array['code_number']				= $cartData["bonus"]['promotion']['code_number'];
			$insert_array['code_orgvalue']				= $cartData["totals"]["prom_org_val"];
			$insert_array['code_lessval']				= $cartData['totals']['lessval'];
			$insert_array['code_minimum']				= $cartData["bonus"]['promotion']['code_minimum'];
			$insert_array['code_value']					= $cartData["bonus"]['promotion']['code_value'];
			$insert_array['code_login_to_use']			= $cartData["bonus"]['promotion']['code_login_to_use'];
			$db->insert_from_array($insert_array,'order_promotional_code');
			$code_num		= $cartData["bonus"]['promotion']['code_number'];
			$code_id		= $cartData["bonus"]['promotion']['code_id'];
		}
		$insert_array								= array();
		$insert_array['track_date']					= 'now()';
		$insert_array['sites_site_id']				= $ecom_siteid;
		$insert_array['customers_customer_id']		= ($cust_id)?$cust_id:0;;
		$insert_array['code_number']				= $code_num;
		$insert_array['promotional_code_code_id']	= $code_id;		
		$insert_array['orders_order_id']			= $order_id;			
		$db->insert_from_array($insert_array,'order_promotionalcode_track');
	}

	
	// Saving the gift wrap details
	if($cartData["totals"]["giftwrap_ribbon_name"]) // if ribbon selected
	{
		$insert_array						= array();
		$insert_array['orders_order_id']	= $order_id;
		$insert_array['giftwrap_name']		= $cartData["totals"]["giftwrap_ribbon_name"];
		$insert_array['giftwrap_price']		= $cartData["totals"]["giftwrap_ribbon_price"];
		$insert_array['giftwrap_type']		= 'ribbon';
		$db->insert_from_array($insert_array,'order_giftwrap_details');
	}
	if($cartData["totals"]["giftwrap_paper_name"]) // if paper selected
	{
		$insert_array						= array();
		$insert_array['orders_order_id']	= $order_id;
		$insert_array['giftwrap_name']		= $cartData["totals"]["giftwrap_paper_name"];
		$insert_array['giftwrap_price']		= $cartData["totals"]["giftwrap_paper_price"];
		$insert_array['giftwrap_type']		= 'paper';
		$db->insert_from_array($insert_array,'order_giftwrap_details');
	}
	if($cartData["totals"]["giftwrap_card_name"]) // if card selected
	{
		$insert_array						= array();
		$insert_array['orders_order_id']	= $order_id;
		$insert_array['giftwrap_name']		= $cartData["totals"]["giftwrap_card_name"];
		$insert_array['giftwrap_price']		= $cartData["totals"]["giftwrap_card_price"];
		$insert_array['giftwrap_type']		= 'card';
		$db->insert_from_array($insert_array,'order_giftwrap_details');
	}
	if($cartData["totals"]["giftwrap_bow_name"]) // if bow selected
	{
		$insert_array						= array();
		$insert_array['orders_order_id']	= $order_id;
		$insert_array['giftwrap_name']		= $cartData["totals"]["giftwrap_bow_name"];
		$insert_array['giftwrap_price']		= $cartData["totals"]["giftwrap_bow_price"];
		$insert_array['giftwrap_type']		= 'bow';
		$db->insert_from_array($insert_array,'order_giftwrap_details');
	}

	if($cartData["payment"]["type"]=='cheque') // if payment type is cheque, then save the cheque details
	{
		$insert_array							= array();
		$insert_array['orders_order_id']		= $order_id;
		$insert_array['cheque_date']			= addslashes(stripslashes($_REQUEST['checkoutchq_date']));
		$insert_array['cheque_number']			= addslashes(stripslashes($_REQUEST['checkoutchq_number']));
		$insert_array['cheque_bankname']		= addslashes(stripslashes($_REQUEST['checkoutchq_bankname']));
		$insert_array['cheque_branchdetails']	= addslashes(stripslashes($_REQUEST['checkoutchq_bankbranch']));
		$db->insert_from_array($insert_array,'order_cheque_details');
	}


	// Remove any entry for current order id in the order_product_downloadable_products table. Done to avoid duplication
	$sql_delete = "DELETE FROM 
								order_product_downloadable_products 
							WHERE 
								orders_order_id = $order_id 
								AND sites_site_id = $ecom_siteid";
	$db->query($sql_delete);
	$product_tax_part_total = $extradelivery_tax_part_total = 0;		
	// Inserting to order_details table and its associated tables
	foreach($cartData["products"] as $product_Arr)
	{
		$insert_array										= array();
		$insert_array['order_delivery_data_delivery_id']	= $order_delivery_id;
		$insert_array['orders_order_id']			= $order_id;
		$insert_array['products_product_id']				= $product_Arr['product_id'];
		$insert_array['product_name']						= addslashes(stripslashes($product_Arr['product_name']));
		$insert_array['order_qty']						= $product_Arr['cart_qty'];
		$insert_array['order_orgqty']						= $product_Arr['cart_qty'];
		$insert_array['order_taxcalc_qty']					= $product_Arr['cart_qty'];
		$insert_array['product_soldprice']					= $product_Arr['discounted_price'];
		$insert_array['order_retailprice']					= $product_Arr['product_webprice'];
		$insert_array['product_costprice']					= $product_Arr['product_costprice'];
		$insert_array['order_detail_discount_type']			= $product_Arr['discount_type'];
		
		$productdisc										= 0;
		$productdisctype									= '';
		$cust_group_disc									= 0;
		$cust_group_name									= '';
		
		if($ecom_siteid==105) // case of puregusto only
		{
			// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
			if ($product_Arr["prom_prodcode_disc"] and $product_Arr["savings"]["product"]) // Case if promotional code disc is there for current product
			{
				$productdisc 		= $product_Arr["savings"]["product"];
				$productdisctype	= 'promotional';
			}
			elseif ($product_Arr["cust_disc_type"] !='' and ($product_Arr["savings"]["product"] OR $product_Arr["savings"]["bulk"])) // Case if promotional code disc is there for current product
			{
				$productdisc 				= ($product_Arr["savings"]["bulk"])?$product_Arr["savings"]["bulk"]:$product_Arr["savings"]["product"];
				$productdisctype			= $product_Arr["cust_disc_type"];		
				$cust_group_disc		= $product_Arr['cust_disc_percent'];
				if($product_Arr['cust_disc_grp_id']>0)
				{
					// Get the discount name from the customer group table
					$sql_grp = "SELECT cust_disc_grp_name  
										FROM 
											customer_discount_group 
										WHERE 
											cust_disc_grp_id = ".$product_Arr['cust_disc_grp_id']." 
										LIMIT 
											1";
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						$row_grp 				= $db->fetch_array($ret_grp);
						$cust_group_name	= add_slash($row_grp['cust_disc_grp_name']);
					}
				}	
			}
			elseif($product_Arr['savings']['product_combo'] or $product_Arr['userin_combo']) // Check whether combo discount is there. the Or option is done to handle the case if discounted price in deals is equal to webprice
			{
				$productdisc 		= $product_Arr["savings"]["product_combo"];
				$productdisctype	= 'combo';
			}
			elseif($product_Arr["savings"]["bulk"]) // Check whether bulk discount is there
			{
				$productdisc = $product_Arr["savings"]["bulk"];
				$productdisctype	= 'bulk';
			}
			else
			{
				$productdisc = $product_Arr["savings"]["product"];
				if($product_Arr['cart_prom_id']!=0) // case if discount type is price promise
					$productdisctype	= 'pricepromise';
				else // case if normal product discount
					$productdisctype	= 'normal';
			}
		}
		else // for websites other than puregusto
		{
		
			// if combo disc exists then that should be displayed otherwise the normal or promotional disc is to be shown
			if ($product_Arr["prom_prodcode_disc"] and $product_Arr["savings"]["product"]) // Case if promotional code disc is there for current product
			{
				$productdisc 		= $product_Arr["savings"]["product"];
				$productdisctype	= 'promotional';
			}
			elseif ($product_Arr["cust_disc_type"] !='' and $product_Arr["savings"]["product"]) // Case if promotional code disc is there for current product
			{
				$productdisc 				= $product_Arr["savings"]["product"];
				$productdisctype			= $product_Arr["cust_disc_type"];		
				$cust_group_disc		= $product_Arr['cust_disc_percent'];
				if($product_Arr['cust_disc_grp_id']>0)
				{
					// Get the discount name from the customer group table
					$sql_grp = "SELECT cust_disc_grp_name  
										FROM 
											customer_discount_group 
										WHERE 
											cust_disc_grp_id = ".$product_Arr['cust_disc_grp_id']." 
										LIMIT 
											1";
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						$row_grp 				= $db->fetch_array($ret_grp);
						$cust_group_name	= add_slash($row_grp['cust_disc_grp_name']);
					}
				}	
			}
			elseif($product_Arr['savings']['product_combo'] or $product_Arr['userin_combo']) // Check whether combo discount is there. the Or option is done to handle the case if discounted price in deals is equal to webprice
			{
				$productdisc 		= $product_Arr["savings"]["product_combo"];
				$productdisctype	= 'combo';
			}
			elseif($product_Arr["savings"]["bulk"]) // Check whether bulk discount is there
			{
				$productdisc = $product_Arr["savings"]["bulk"];
				$productdisctype	= 'bulk';
			}
			else
			{
				$productdisc = $product_Arr["savings"]["product"];
				if($product_Arr['cart_prom_id']!=0) // case if discount type is price promise
					$productdisctype	= 'pricepromise';
				else // case if normal product discount
					$productdisctype	= 'normal';
			}
		}
		$insert_array['order_discount']						= ($productdisc)?$productdisc:0;
		$insert_array['order_discount_type']				= addslashes(stripslashes($productdisctype));
		$insert_array['order_discount_group_name']			= addslashes(stripslashes($cust_group_name));
		$insert_array['order_discount_group_percentage']	= addslashes(stripslashes($cust_group_disc));
		
		if($product_Arr['product_alloworder_notinstock']=='N') // if add to cart is not allowed always then consider the case of preorder 
		{
			$insert_array['order_preorder']								= addslashes(stripslashes($product_Arr['product_preorder_allowed'])); //add_slash($product_Arr['currently_in_preorder']);
			if($product_Arr['product_preorder_allowed']=='Y')	//if($product_Arr['currently_in_preorder']=='Y')
			{
				$insert_array['order_preorder_available_date']		= $product_Arr['product_instock_date'];
			}	
		}
		else // case if add to cart is allowed always
		{
			$insert_array['order_preorder']								= 'N';
			$insert_array['order_preorder_available_date']			= '0000-00-00';
		}	
		$insert_array['order_deposit_value']				= $product_Arr['product_deposit_less'];
		$insert_array['order_deposit_message']				= addslashes(stripslashes($product_Arr['product_deposit_message']));
		$insert_array['order_stock_combination_id']			= $product_Arr['cart_comb_id'];
		$insert_array['order_rowtotal']						= $product_Arr['final_price'];
		$insert_array['order_freedelivery']					= $product_Arr['product_freedelivery'];
		if($product_Arr['cart_prom_id']!=0) // case if current product is a price promise entry
			$insert_array['order_prom_id']					= $product_Arr['cart_prom_id'];
		else
			$insert_array['order_prom_id']					= 0;
		
		
		$curtaxpart = $total_extradel_new = 0;
		
		if($ecom_allpricewithtax==1) // case if special tax calculation is set for the website
		{
			$sql_tax_new = "SELECT tax_val 
					FROM 
						general_settings_site_tax 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND tax_active=1 
					LIMIT 
						1";
			$ret_tax_new = $db->query($sql_tax_new);
			if($db->num_rows($ret_tax_new))
			{
				$row_tax_new = $db->fetch_array($ret_tax_new);
				$taxrate = $row_tax_new['tax_val'];
			}
			
			if($product_Arr['product_special_tax_calc_type']=='productyes_and_locationyes' and $product_Arr['extracted_vat_amount'] == 'Y')
			{
				//price*Tax Rate/(100+Tax Rate)
				$curtaxpart = ($product_Arr['final_price']*$taxrate)/(100+$taxrate);
				$product_tax_part_total += $curtaxpart;
				$insert_array['orderdet_specialtax_productamt'] = $curtaxpart;	
				$insert_array['orderdet_specialtax_orgproductamt'] = $curtaxpart;
				
				$total_extradel_new 		= ($product_Arr['extraShipping']);
				$sp_tax				= ($total_extradel_new*$taxrate)/(100+$taxrate);
				$extradelivery_tax_part_total 	+= $sp_tax;
				$insert_array['orderdet_specialtax_extrashippingamt'] = $sp_tax;
				$insert_array['orderdet_specialtax_orgextrashippingamt'] = $sp_tax;
			}
			elseif($product_Arr['product_special_tax_calc_type']=='productno_and_locationyes' and $product_Arr['extracted_vat_amount'] == 'Y')
			{
				$total_extradel_new 		= ($product_Arr['extraShipping']);
				$sp_tax				= ($total_extradel_new*$taxrate)/(100+$taxrate);
				$extradelivery_tax_part_total 	+= $sp_tax;
				$insert_array['orderdet_specialtax_extrashippingamt'] = $sp_tax;	
				$insert_array['orderdet_specialtax_orgextrashippingamt'] = $sp_tax;
			}
		}
		
		/* Handling the case of sub products and its related fields */
		if($product_Arr['cart_main_cart_id']==0)
		{
			$insert_array['order_mainproduct'] = 1;
			$insert_array['order_cartid'] 		= $product_Arr['cart_id'];
		}
		else
		{
			$insert_array['order_mainproduct'] = 0;	
			$insert_array['order_cartid'] 		= $product_Arr['cart_main_cart_id'];
		}
		$insert_array['order_sort'] 		= $product_Arr['cart_sort'];	
		
		
		$db->insert_from_array($insert_array,'order_details');
		$order_detail_id = $db->insert_id();
		
		
		
		// Inserting to order_details_variables table in variables exists for current product
		if ($product_Arr['prod_vars'])
		{
			foreach($product_Arr["prod_vars"] as $productVars)
			{
				$insert_array				= array();
				$insert_array['orders_order_id']			= $order_id;
				$insert_array['order_details_orderdet_id']	= $order_detail_id;
				$insert_array['var_id']						= $productVars['var_id'];
				$insert_array['var_name']					= addslashes(stripslashes($productVars['var_name']));
				$insert_array['var_price']					= $productVars['var_addprice'];

				if (trim($productVars['var_value'])!='')
				{
					$insert_array['var_value']				= addslashes(stripslashes($productVars['var_value']));
				}
				else
					$insert_array['var_value']					= '';
				$db->insert_from_array($insert_array,'order_details_variables');
			}
		}
		// Inserting to order_details_messages table in messages exists for current product and values entered for those
		if ($product_Arr['prod_msgs'])
		{
			foreach($product_Arr["prod_msgs"] as $productMsgs)
			{
				$insert_array				= array();
				$insert_array['orders_order_id']			= $order_id;
				$insert_array['order_details_orderdet_id']	= $order_detail_id;
				$insert_array['message_id']					= $productMsgs['var_id'];
				$insert_array['message_caption']			= addslashes(stripslashes($productMsgs['message_title']));
				$insert_array['message_value']				= addslashes(stripslashes($productMsgs['message_value']));
				$insert_array['message_type']				= addslashes(stripslashes($productMsgs['message_type']));
				$db->insert_from_array($insert_array,'order_details_messages');
			}
		}
		
		// Section to handle the case of downloadable products 
			if ($cust_id) // handle the case of downloadable products only if customer is logged in
			{
				// Check whether any downloadable items exists for current product 
				$sql_download = "SELECT proddown_id, proddown_limited, proddown_limit, proddown_days_active, proddown_days 
											FROM 
												product_downloadable_products 
											WHERE 
												products_product_id = ".$product_Arr['product_id']." 
												AND sites_site_id = $ecom_siteid 
												AND proddown_hide=0";
				$ret_download = $db->query($sql_download);
				if ($db->num_rows($ret_download))
				{
					while ($row_download = $db->fetch_array($ret_download))
					{
						$insert_array																		= array();
						$insert_array['orders_order_id']												= $order_id;
						$insert_array['order_details_orderdet_id']								= $order_detail_id;
						$insert_array['sites_site_id']													= $ecom_siteid;
						$insert_array['customers_customer_id']									= $cust_id;
						$insert_array['product_downloadable_products_proddown_id']	= $row_download['proddown_id'];
						$insert_array['proddown_disabled']										= 0;
						$insert_array['proddown_limited']											= $row_download['proddown_limited'];
						$insert_array['proddown_limit']												= $row_download['proddown_limit'];
						$insert_array['proddown_days_active']									= $row_download['proddown_days_active'];
						$insert_array['proddown_days']												= $row_download['proddown_days'];
						$insert_array['proddown_days_active_start']							= '0000-00-00 00:00:00';
						$insert_array['proddown_days_active_end']							= '0000-00-00 00:00:00';
						$db->insert_from_array($insert_array,'order_product_downloadable_products');
					}
				}
			}
		
	}
	if($ecom_allpricewithtax==1) // case if special tax calculation is set for the website
	{
		$sql_update_ord = "UPDATE orders 
					SET 
						order_specialtax_productamt=$product_tax_part_total,
						order_specialtax_extrashippingamt=$extradelivery_tax_part_total,
						order_specialtax_orgproductamt=$product_tax_part_total,
						order_specialtax_orgextrashippingamt=$extradelivery_tax_part_total  
					WHERE 
						order_id = $order_id 
					LIMIT 
						1";
		$db->query($sql_update_ord);
		// execute the sql to find the total tax amount for the current order_discount
		$sql_total_tax = "UPDATE orders 
					SET 
						order_specialtax_totalamt = order_specialtax_productamt+
						order_specialtax_deliveryamt+order_specialtax_extrashippingamt,
						order_specialtax_orgtotalamt = order_specialtax_productamt+
						order_specialtax_deliveryamt+order_specialtax_extrashippingamt 
					WHERE 
						order_id = $order_id 
					LIMIT 
						1";
		$db->query($sql_total_tax);
	}			
				
				
				
	// ###########################################################################
	// Calling the function to process the payment and get the status in return
	// ###########################################################################
	$cartData['checkout_country'] 			= $checkout_arr['checkout_country'];
	$cartData['checkoutdelivery_country'] 	= $hold_del_country;
	$curr_pass	= $curr_code.'~'.$curr_num_code;
	//to check whether it is coming from iphone app
	if($from_iphone_app == true)
	{
	$payData 	= processPayment_iphone($cartData,$del_arr,$curr_pass, $order_id, $address);
	}
	else
	$payData 	= processPayment($cartData,$del_arr,$curr_pass, $order_id, $address);

	// Not authorised or payment not successfull
	if($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"]==11 or $payData["result"]==20 or $payData["result"]==35)
	{
		// Deleting the details inserted related to order related tables
		
		// Start comment case no delete req on failure
		/*$db->delete_id($order_id,'orders_order_id','order_cheque_details');
		$db->delete_id($order_id,'orders_order_id','order_delivery_data');
		$db->delete_id($order_id,'orders_order_id','order_details');
		$db->delete_id($order_id,'orders_order_id','order_details_messages');
		$db->delete_id($order_id,'orders_order_id','order_details_variables');
		$db->delete_id($order_id,'orders_order_id','order_dynamicvalues');
		$db->delete_id($order_id,'orders_order_id','order_emails');
		$db->delete_id($order_id,'orders_order_id','order_giftwrap_details');
		$db->delete_id($order_id,'orders_order_id','order_payment_main');
		$db->delete_id($order_id,'orders_order_id','order_promotional_code');
		$db->delete_id($order_id,'orders_order_id','order_promotionalcode_track');
		$db->delete_id($order_id,'orders_order_id','order_tax_details');
		$db->delete_id($order_id,'orders_order_id','order_voucher');
		$db->delete_id($order_id,'order_id','orders');*/
		// End comment case no delete req on failure
		
		if($payData["result"] == 4)
		{
			$err_msg = "<br><br>Sorry, the card details were rejected by our online bank. Please go back and enter the details correctly. Thank you";
			//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= add_slash($err_msg,false);
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
			$payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
		}
		elseif($payData["result"] == 3)
		{
			$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
					 Please go back and enter the details correctly. Thank you";
			//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= add_slash($err_msg,false);
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
			$payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
		}
		elseif($payData["result"] == 11) // case of failure of paypal express
		{
			$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
					 Please go back and enter the details correctly. Thank you";
			//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= add_slash($err_msg,false);
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
			// In case of result ==11 the value of $payData["payStatus"] will be obtained from payment.php file itself
		}
		elseif($payData["result"] == 20) // case of failure of paypal pro
		{
			$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
					 Please go back and enter the details correctly. Thank you";
			//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= add_slash($err_msg,false);
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
			// In case of result ==11 the value of $payData["payStatus"] will be obtained from payment.php file itself
		}
		elseif($payData["result"] == 35) // case of failure of 4minute finance
		{
			/* ------------------- 4 min finance - start ---------------------------*/
			$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
					 ";
			//Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
			$update_array						= array();
			$update_array['cart_error_msg_ret']	= add_slash($err_msg,false);
			$db->update_from_array($update_array,'cart_supportdetails',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
			/* ------------------- 4 min finance - end ---------------------------*/
		}
		
		
		
		$sql_upd = "UPDATE orders 
					SET 
						order_paystatus = '" . $payData["payStatus"] . "', 
						order_status='NOT_AUTH' 
					WHERE 
						order_id =$order_id 
					LIMIT 
						1";
		$db->query($sql_upd);
			// Start comment case no delete req on failure	
			/*echo "
					<script type='text/javascript'>
						window.location = 'http://".$ecom_hostname."/checkout_failed.html';
					</script>
					";
			
			exit;
			return;*/
			// End comment case no delete req on failure
	}

	elseif($payData["result"] == 5) // Payment process 3D Secure in case of protx
	{
		echo "<br>Please Wait! Don't refresh the page...<br>You will be redirected to 3DSecure site...";
	}
	// Inserting the payment related details to orders_payment_main table only
	// if $cartData["payment"]["method"]['paymethod_takecarddetails']==1, that is credit card details are taken directly in site
	if($cartData["payment"]["method"]['paymethod_takecarddetails']==1)
	{
		// Check whether the credit card number (if exists) is to be encrypted before storing to table
		$payData['Encrypted']	= 0;
		if($payData["card_number"]!='')
		{
			if($Settings_arr['encrypted_cc_numbers']==1)
			{
				$payData["card_number"] = base64_encode(base64_encode($payData["card_number"]));
				$payData['Encrypted']	= 1;
			}
		}
		$insert_array							= array();
		$insert_array['orders_order_id']		= $order_id;
		$insert_array['sites_site_id']			= $ecom_siteid;
		$insert_array['order_card_type']		= addslashes(stripslashes($payData["card_type"]));
		$insert_array['order_name_on_card']		= '';//addslashes(stripslashes($payData["name_on_card"]));
		$insert_array['order_card_number']		= '';//addslashes(stripslashes($payData["card_number"]));
		$insert_array['order_sec_code']			= 0;//addslashes(stripslashes($payData["sec_code"]));
		$insert_array['order_expiry_date_m']	= 0;//$payData["expiry_date_m"];
		$insert_array['order_expiry_date_y']	= 0;//$payData["expiry_date_y"];
		$insert_array['order_issue_number']		= 0;//$payData["issue_number"];
		$insert_array['order_issue_date_m']		= 0;//$payData["issue_date_m"];
		$insert_array['order_issue_date_y']		= 0;//$payData["issue_date_y"];
		$insert_array['order_vendorTxCode']		= $payData["VendorTxCode"];
		$insert_array['order_protStatus']		= $payData["protStatus"];
		$insert_array['order_protStatusDetail']	= $payData["protStatusDetail"];
		$insert_array['order_vPSTxId']			= $payData["VPSTxID"];
		$insert_array['order_securityKey']		= $payData["SecurityKey"];
		$insert_array['order_txAuthNo']			= $payData["TxAuthNo"];
		$insert_array['order_txType']			= $payData["TxType"];
		$insert_array['order_orgtxType']		= $payData["TxType"]; // helps to identify which was the original payment capture type
		$insert_array['order_avscv2']			= $payData["AVSCV2"];
		$insert_array['order_acsurl']			= addslashes(stripslashes($payData["ACSURL"]));
		$insert_array['order_pareq']			= $payData["PAReq"];
		$insert_array['order_md']				= $payData["MD"];
		$insert_array['order_card_encrypted']	= $payData['Encrypted'];
		$db->insert_from_array($insert_array,'order_payment_main');
	}

	// Updating the order table with the payment status
	$update_array								= array();
	$update_array['order_paystatus']			= addslashes(stripslashes($payData['payStatus']));
	$db->update_from_array($update_array,'orders',array('order_id'=>$order_id,'sites_site_id'=>$ecom_siteid));
	if($payData["result"] == 1) // call the function to do the post order operations only if the order is accepted
	{ 
		if($cartData["payment"]["method"]['paymethod_key']=='PAYPAL_EXPRESS' or $cartData["payment"]["method"]['paymethod_key']=='PAYPALPRO') // if payment method is paypal express and payment is success, then store the additional deatils send back by paypal in order related tables
		{
			// if any entry exists in order_payment_paypal table related to current order id, then delete it
			$sql_del = "DELETE FROM 
							order_payment_paypal 
						WHERE 
							orders_order_id = $order_id";
			$ret_del = $db->query($sql_del);
			// Inserting to order_payment_paypal table
			$insert_array								= array();
			$insert_array['orders_order_id'] 			= $order_id;
			$insert_array['sites_site_id'] 				= $ecom_siteid;
			$insert_array['paypal_transactions_id'] 	= $payData["TRANSACTIONID"];	
			$insert_array['paypal_transaction_type'] 	= $payData["TRANSACTIONTYPE"];
			$insert_array['paypal_payment_type'] 		= $payData["PAYMENTTYPE"];
			$insert_array['paypal_ordertime'] 			= $payData["ORDERTIME"];
			$insert_array['paypal_amt'] 				= $payData["AMT"];
			$insert_array['paypal_currency_code'] 		= $payData["CURRENCYCODE"];	
			$insert_array['paypal_feeamt'] 				= $payData["FEEAMT"];
			$insert_array['paypal_settleamt'] 			= $payData["SETTLEAMT"];
			$insert_array['paypal_taxamt'] 				= $payData["TAXAMT"];
			$insert_array['paypal_exchange_rate'] 		= $payData["EXCHANGERATE"];
			$insert_array['paypal_paymentstatus'] 		= $payData["PAYMENTSTATUS"];
			$insert_array['paypal_pending_reason'] 		= $payData["PENDINGREASON"];
			$insert_array['paypal_reasoncode'] 			= $payData["REASONCODE"];
			
			$insert_array['paypal_avscode'] 			= $payData["AVSCODE"];
			$insert_array['paypal_cvv2match'] 			= $payData["CVV2MATCH"];
			$insert_array['paypal_VPAS'] 				= $payData["VPAS"];
			$db->insert_from_array($insert_array,'order_payment_paypal');
		}
		// Stock Decrementing section over here
		do_PostOrderSuccessOperations($order_id);
	}
	
	if($cartData["payment"]["type"]=='invoice') // Check whether payment type is invoice if so, get the invoice template to be send to customer
	{
		$cust_conf_type = 'ORDER_CONFIRM_CUST_INVOICED';
	}
	else
	{
		$cust_conf_type = 'ORDER_CONFIRM_CUST';
	}
	// Get the email template for order confirmation for current site
	/*$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'ORDER_CONFIRM_CUST'
						LIMIT
							1";*/
	$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = '".$cust_conf_type."'
						LIMIT
							1";
	$ret_template = $db->query($sql_template);
	if ($db->num_rows($ret_template))
	{
		$row_template 	= $db->fetch_array($ret_template);
		$email_from		= stripslashes($row_template['lettertemplate_from']);
		$email_subject	= stripslashes($row_template['lettertemplate_subject']);
		$email_content	= stripslashes($row_template['lettertemplate_contents']);
		$email_disabled	= stripslashes($row_template['lettertemplate_disabled']);
		$email_domain	= $ecom_hostname;

		// Get the checkout fields from general_settings_sites_checkoutfields table
		$sql_checkout = "SELECT field_key,field_name
							FROM
								general_settings_site_checkoutfields
							WHERE
								sites_site_id = $ecom_siteid
								AND field_type IN ('PERSONAL','DELIVERY')
							ORDER BY
								field_order";
		$ret_checkout = $db->query($sql_checkout);
		if($db->num_rows($ret_checkout))
		{
			while ($row_checkout = $db->fetch_array($ret_checkout))
			{
				$chkorder_arr[$row_checkout['field_key']] = stripslashes($row_checkout['field_name']);
			}
		}
		// Get the order details stored for current order id from orders table
		/* Donate bonus Start */
		$sql_ords		= "SELECT order_id,order_date,DATE_FORMAT(order_date,'%e-%b-%Y') as formateddate,order_custtitle,order_custfname,order_custmname,order_custsurname,
									order_custcompany,order_buildingnumber,order_street,order_city,order_state,
									order_country,order_custpostcode,order_custphone,order_custfax,order_custmobile,
									order_custemail,order_notes,order_giftwrap,order_giftwrap_per,order_giftwrapmessage,
									order_giftwrapmessage_text,order_giftwraptotal,order_deliverytype,order_deliverylocation,
									order_delivery_option,order_deliverytotal,order_extrashipping,order_bonuspoint_discount,
									order_paymenttype,order_paymentmethod,order_tax_total,order_totalprice,order_subtotal,
									gift_vouchers_voucher_id,order_gift_voucher_number,promotional_code_code_id,
									promotional_code_code_number,order_paystatus,order_deliveryprice_only,
									order_customer_or_corporate_disc,order_customer_discount_type,
									order_customer_discount_percent,order_customer_discount_value,order_deposit_amt,
									order_status,order_bonusrate,order_bonuspoints_used,order_bonuspoint_inorder,
									order_bonuspoints_donated,customers_customer_id,order_ip   
							FROM
								orders
							WHERE
								order_id = $order_id
							LIMIT
								1";
		/* Donate bonus End */						
		$ret_ords		= $db->query($sql_ords);
		if ($db->num_rows($ret_ords))
		{
			$row_ords 		= $db->fetch_array($ret_ords);
			if($ecom_siteid==105)//95
			{ //$ecom_siteid==105
				 $email_content_sp = $email_content ;
			     $email_orderid	= $row_ords['order_id'];
			     $order_subtotal	= print_price($row_ords['order_totalprice']);
			     $email_name		= stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname']).' '.stripslashes($row_ords['order_custmname']).' '.stripslashes($row_ords['order_custsurname']);
				 $email_notes	= stripslashes($row_ords['order_notes']);
				 if($email_notes=='')
			         {
						$email_content_sp = preg_replace('/<notes>(.*?)<\/notes>/iUs', "", $email_content_sp);
					 }
				 
				 $bill_company             = $row_ords['order_custcompany'];
				 $bill_buildingnumber      = $row_ords['order_buildingnumber'];
				 $bill_street       	   = $row_ords['order_street'];
				 $bill_city                = $row_ords['order_city'];
				 $bill_state   			   = $row_ords['order_state'];
				 $bill_country             = $row_ords['order_country'];
				 $bill_custpostcode       	= $row_ords['order_custpostcode'];
				 $bill_custphone            = $row_ords['order_custphone'];
				 $bill_custmobile   		= $row_ords['order_custmobile'];
				 $bill_custfax             = $row_ords['order_custfax'];
				 $bill_custemail       	   = $row_ords['order_custemail'];
			     $delivery_loc			= stripslashes($row_ords['order_deliverylocation']);
			     $paymethod				= $row_ords['order_paymentmethod'];
				if($paymethod!='')
							{
								$paymethod = getpaymentmethod_Name($paymethod);
							}
							else
								$paymethod = 'N/A';
				 // Get the delivery details corresponding to current order
					$sql_del_sp		= "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,
										delivery_companyname,delivery_buildingnumber,delivery_street,delivery_city,delivery_state,
										delivery_country,delivery_zip,delivery_phone,delivery_fax,delivery_mobile,
										delivery_email
									FROM
										order_delivery_data
									WHERE
										orders_order_id = $order_id
									LIMIT
										1";
					$ret_del_sp		= $db->query($sql_del_sp);
					if ($db->num_rows($ret_del_sp))
					{
						$row_del_sp = $db->fetch_array($ret_del_sp);
					}
			    
			     $delemail_name		= stripslashes($row_del_sp['delivery_title']).''.stripslashes($row_del_sp['delivery_fname']).' '.stripslashes($row_del_sp['delivery_mname']).' '.stripslashes($row_del_sp['delivery_lname']);
				 $del_company             = stripslashes($row_del_sp['delivery_companyname']);
				 $del_buildingnumber      = stripslashes($row_del_sp['delivery_buildingnumber']);
				 $del_street       	   = stripslashes($row_del_sp['delivery_street']);
				 $del_city                = stripslashes($row_del_sp['delivery_city']);
				 $del_state   			   = stripslashes($row_del_sp['delivery_state']);
				 $del_country             = stripslashes($row_del_sp['delivery_country']);
				 $del_custpostcode       	= stripslashes($row_del_sp['delivery_zip']);
				 $del_custphone            = stripslashes($row_del_sp['delivery_phone']);
				 $del_custmobile   		= stripslashes($row_del_sp['delivery_mobile']);
				 $del_custfax             = stripslashes($row_del_sp['delivery_fax']);
				 $del_custemail       	   = stripslashes($row_del_sp['delivery_email']);
				 
				 
				 
				 
                
					$sql_prods_sp  = "SELECT orderdet_id,product_name,products_product_id,order_qty,product_soldprice,order_retailprice,order_retailprice,order_discount,
											order_discount_type,order_rowtotal,order_stock_combination_id  
									FROM
										order_details
									WHERE
										orders_order_id = $order_id";
					$ret_prods_sp = $db->query($sql_prods_sp);
					if ($db->num_rows($ret_prods_sp))
					{ 

						 $prod_str_sp	.= '<table width="601" border="0" cellspacing="2" cellpadding="2">';
						 $prod_str_sp	.= ' <tr>
							<td width="208" style="padding:5px; background-color:#603814;color:#fff; font:bold 12px Arial, Helvetica, sans-serif;">'.$Captions_arr['CART']['CART_ITEM'].'</td>
							<td width="119" style="padding:5px; background-color:#603814;color:#fff; font:bold 12px Arial, Helvetica, sans-serif;">'.$Captions_arr['CART']['CART_PRICE'].'</td>
							<td width="51" align="center" style="padding:5px; background-color:#603814;color:#fff; font:bold 12px Arial, Helvetica, sans-serif;">'.$Captions_arr['CART']['CART_DISCOUNT'].'</td>
							<td width="102" align="center" style="padding:5px; background-color:#603814;color:#fff; font:bold 12px Arial, Helvetica, sans-serif;">'.$Captions_arr['CART']['CART_QTY'].'</td>
							<td width="121" align="right" style="padding:5px; background-color:#603814;color:#fff; font:bold 12px Arial, Helvetica, sans-serif;">'.$Captions_arr['CART']['CART_TOTAL'].'</td>
						  </tr>';
						
						
						
						$var_comb = array();//for garraways uniqueid
								
						while ($row_prods_sp = $db->fetch_array($ret_prods_sp))
						{
							$prodName = strip_url(stripslashes($row_prods_sp['product_name']));
							$prodId = $row_prods_sp['products_product_id'];
						
							$productPageUrlHash_sp = $ecom_selfhttp.$ecom_hostname."/".$prodName."-p$prodId.html";
							$prod_str_sp	.= '<tr>
											<td height="41" align="left" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;padding: 5px;"><a style="font:normal 12px Arial, Helvetica, sans-serif; color: #000; text-decoration: none;" href='.$productPageUrlHash_sp.' '.$style_desc_prodname.'>'.stripslashes($row_prods_sp['product_name']).'</a></td>
											<td align="left" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;padding-left:10px">'.print_price($row_prods_sp['order_retailprice']).'</td>
											<td align="center" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_prods_sp['order_discount']).'</td>
											<td align="center" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.stripslashes($row_prods_sp['order_qty']).'</td>
											<td align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_prods_sp['order_rowtotal']).'</td>
										  </tr>';
										  // Check whether any variables exists for current product in order_details_variables
								$sql_var_sp = "SELECT var_name,var_value,var_id 
												FROM
													order_details_variables
												WHERE
													orders_order_id = $order_id
													AND order_details_orderdet_id =".$row_prods_sp['orderdet_id'];
								$ret_var_sp = $db->query($sql_var_sp);
								if ($db->num_rows($ret_var_sp))
								{
									while ($row_var_sp = $db->fetch_array($ret_var_sp))
									{
										
										$prod_str_sp	.= '<tr>
														<td height="15" align="left" colspan="5" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;padding-left:15px;">'.stripslashes($row_var_sp['var_name']).': '.stripslashes($row_var_sp['var_value']).' </td>
														</tr>';
											
									}
								}
								// Check whether any variables messages exists for current product in order_details_messages
								$sql_msg_sp = "SELECT message_caption,message_value
												FROM
													order_details_messages
												WHERE
													orders_order_id = $order_id
													AND order_details_orderdet_id =".$row_prods_sp['orderdet_id'];
								$ret_msg_sp = $db->query($sql_msg_sp);
								if ($db->num_rows($ret_msg_sp))
								{
									while ($row_msg_sp = $db->fetch_array($ret_msg_sp))
									{
										$prod_str_sp	.= '<tr>
														<td height="41" align="right" colspan="5" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;"> '.stripslashes($row_msg_sp['message_caption']).': '.stripslashes($row_msg_sp['message_value']).' </td>
														</tr>';
									}
								}						
						}
						$prod_str_sp	.= '<tr>
										<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">'.$Captions_arr['CART']['CART_TOTPRICE'].'</td>
										<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_subtotal']).' </span></td>
									  </tr>';



						// giftwrap total and delivery type total and tax total
						if($row_ords['order_giftwraptotal']>0)
						{
							$prod_str_sp	.= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Gift Wrap Total</td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_giftwraptotal']).' </span></td>
										  </tr>';				
						}
						if($row_ords['order_deliverytotal']>0)
						{
							$prod_str_sp	.= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Delivery Total</td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_deliverytotal']).' </span></td>
										  </tr>';
						}
						if($row_ords['order_tax_total']>0)
						{
							$new_tot_tax_str = 'Total Tax';
							$prod_str_sp	.= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">'.$new_tot_tax_str.' </td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_tax_total']).' </span></td>
										  </tr>';
						}

						// Customer / Corporate discount
						if ($row_ords['order_customer_discount_value']>0)
						{
							if ($row_ords['order_customer_or_corporate_disc']=='CUST')
							{
								if($row_ords['order_customer_discount_type']=='Disc_Group')
									$caption = 'Customer Group Discount ('.$row_ords['order_customer_discount_percent'].'%)';
								else
									$caption = 'Customer Discount ('.$row_ords['order_customer_discount_percent'].'%)';
								$caption_val = $row_ords['order_customer_discount_value'];
								$prod_str_sp	.= '<tr>
												<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">'.$caption.' </td>
												<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($caption_val).' </span></td>
											  </tr>';
							}
							else // case of corporate discount
							{
								$caption = 'Corporate Discount ('.$row_ords['order_customer_discount_percent'].'%)';
								$caption_val = $row_ords['order_customer_discount_value'];
								$prod_str_sp	.= '<tr>
												<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">'.$caption.' </td>
												<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($caption_val).' </span></td>
											  </tr>';
							}
						}
						
						
						// ##############################################################################
				// Delivery Type
				// ##############################################################################
				if ($row_ords['order_delivery_type']!='None')
				{
					//$del_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					//$delsp_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					
					$del_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Method</strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.stripslashes($row_ords['order_deliverytype']).'</strong> </span></td>
					</tr>';
					if($row_ords['order_delivery_option']!='') // case if delivery option exists
					{
						$del_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Option</strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.stripslashes($row_ords['order_delivery_option']).'</strong> </span></td>
					</tr>';
					}	
					if(trim($row_ords['order_deliverylocation'])!='')
					{
						$del_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Delivery Location </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.stripslashes($row_ords['order_deliverylocation']).'</strong> </span></td>
					</tr>';
						
					}
					if ($row_ords['order_deliveryprice_only']>0) // case of delivery charge along
					{
						
						$del_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Base Delivery Charge </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_deliveryprice_only']).'</strong> </span></td>
					</tr>';
					}
					if ($row_ords['order_extrashipping']>0) // case of extra shipping exists
					{
						
						$del_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Extra Shipping Cost </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_extrashipping']).'</strong> </span></td>
					</tr>';
					}
					//$del_str 	.= '</table>';
					//$delsp_str 	.= '</table>';
				}
				// ##############################################################################
				// Promotional Code or Gift Voucher Details
				// ##############################################################################
				if($row_ords['gift_vouchers_voucher_id'] or $row_ords['promotional_code_code_id'])
				{
					//$prom_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if($row_ords['gift_vouchers_voucher_id'])
					{
						// Get the gift voucher details
						$sql_voucher = "SELECT voucher_no,voucher_value_used
											FROM
												order_voucher
											WHERE
												orders_order_id = $order_id
											LIMIT
												1";
						$ret_voucher = $db->query($sql_voucher);
						if ($db->num_rows($ret_voucher))
						{
							$row_voucher 	= $db->fetch_array($ret_voucher);
							//$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Gift Voucher Code </td><td align="left" width="50%" '.$style_desc.'>'.$row_voucher['voucher_no'].' </td></tr>';
							//$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Gift Voucher Discount </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_voucher['voucher_value_used']).' </td></tr>';
							$promtotal_str_sp	.= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Gift Voucher Discount</td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_voucher['voucher_value_used']).' </span></td>
										  </tr>';
						}

					}
					elseif($row_ords['promotional_code_code_id'])
					{
						// Get the promotional code details
						$sql_prom = "SELECT code_number,code_lessval,code_type
											FROM
												order_promotional_code
											WHERE
												orders_order_id = $order_id
											LIMIT
												1";
						$ret_prom = $db->query($sql_prom);
						if ($db->num_rows($ret_prom))
						{
							$row_prom 	= $db->fetch_array($ret_prom);
							//$prom_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Promotional Code </td><td align="left" width="50%" '.$style_desc.'>'.$row_prom['code_number'].' </td></tr>';
							if ($row_prom['code_type']!='product') // show only if not of type 'product' if type is product discount will be shown with product listing
							{
								//$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Promotional Code Discount </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_prom['code_lessval']).' </td></tr>';
								$promtotal_str_sp	= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Promotional Code Discount</td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_prom['code_lessval']).'</span></td>
										  </tr>';
							}
						}

					}
					//$prom_str 		.= 	'</table>';
				}

				// ##############################################################################
				// Gift wrap details
				// ##############################################################################
				// Check whether gift wrap exists
				/*
				if($row_ords['order_giftwrap']=='Y')
				{
					//$giftdet_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_giftwrap_per']=='order')
					{
						$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Apply to </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>Order</strong> </span></td>
					</tr>';
					}
					else
					{
						$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Apply to </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>Individual Items </strong> </span></td>
					</tr>';
					}

					if ($row_ords['order_giftwrap_minprice']>0)
					{
						$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Minimum Price for Gift wrap </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_giftwrap_minprice']).'</strong> </span></td>
					</tr>';
					}	

					if ($row_ords['order_giftwrapmessage']=='Y')
					{
						$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Message </strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_giftwrap_message_charge']).'</strong> </span></td>
					</tr>';
						//$giftdet_str		.= '<tr> <td align="left" colspan="2" '.$style_desc.'>'.stripslashes($row_ords['order_giftwrapmessage_text']).' </td></tr>';
					}

					$sql_gift_sp			= "SELECT giftwrap_name,giftwrap_price,giftwrap_price
											FROM
												order_giftwrap_details
											WHERE
												orders_order_id=$order_id";
					$ret_gift_sp			= $db->query($sql_gift_sp);
					if ($db->num_rows($ret_gift_sp))
					{
						while ($row_gift = $db->fetch_array($ret_gift_sp))
						{							
							$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>'.stripslashes($row_gift_sp['giftwrap_name']).'</strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_gift_sp['giftwrap_price']).'</strong> </span></td>
					</tr>';
						}
					}
					$giftdet_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Gift Wrap Total</strong></td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_giftwraptotal']).'</strong> </span></td>
					</tr>';
					// Catting the total of gift wrap to giftdet_str variable
					//$giftdet_str		.= '</table>';
				}
				else
					$giftdet_str_sp = '';
					*/ 


				// ##############################################################################
				// Bonus Points Checking
				// ##############################################################################
				if($row_ords['order_bonuspoints_used']>0 or $row_ords['order_bonuspoint_inorder']>0)// if bonus points used or bonus points achieved in currenr order
				{
					//$bonus_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_bonuspoints_used']>0)
					{
						//$bonus_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Bonus Points Used </td> <td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoints_used'].' </td></tr>';
						
						$bonus_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points Used</td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.$row_ords['order_bonuspoints_used'].' </span></td>
					</tr>';
					}
					if ($row_ords['order_bonuspoints_used']>0)
					{
						
						$bonus_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points Rate </td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_bonusrate']).' </span></td>
					</tr>';
					}
					if($row_ords['order_bonuspoint_discount']>0)
					{
						
						$bonus_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points Discount</td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_bonuspoint_discount']).'</span></td>
					</tr>';
					
						$bonustotal_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points Discount </td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_bonuspoint_discount']).' </span></td>
					</tr>';						
						
					}
					if ($row_ords['order_bonuspoint_inorder']>0)
					{
						$bonus_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points gained due to this order</td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.$row_ords['order_bonuspoint_inorder'].' </span></td>
					</tr>';
					}
					//$bonus_str 	.= 	'</table>';
				}
				/* Donate bonus Start */
				/* Donate bonus points done */
				if($row_ords['order_bonuspoints_donated']>0)
				{
					//$bonus_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_bonuspoints_donated']>0)
					{
					$bonus_str_sp	.= '<tr>
					<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Bonus Points Donated</td>
					<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.$row_ords['order_bonuspoints_donated'].'</span></td>
					</tr>';
					}
					//$bonus_str 	.= 	'</table>';
				}
				
				////
						
						
						// promotional code or gift voucher discounts or bonus points spend discount
						$prod_str_sp		.= $promtotal_str_sp.$bonustotal_str_sp;
						// Total Final Cost
						$prod_str_sp	.= '<tr>
										<td height="25" colspan="4" align="right" valign="middle" style="font:normal 18px Arial, Helvetica, sans-serif; background:#fff;"><strong>Grand Total</strong></td>
										<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 18px Arial, Helvetica, sans-serif; background:#f4f4f4;"><strong>'.print_price($row_ords['order_totalprice']).'</strong> </span></td>
									  </tr>';
						// Check whether product deposit exists
						if($row_ords['order_deposit_amt']>0)
						{
							$dep_less_sp = $row_ords['order_totalprice'] - $row_ords['order_deposit_amt'];
							$prod_str_sp	.= '<tr>
											<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Less Product Deposit Amount </td>
											<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($dep_less_sp).' </span></td>
										  </tr>';
							$prod_str_sp	.= '<tr>
												<td height="25" colspan="4" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;">Amount Payable Now </td>
												<td height="25" align="right" valign="middle" style="font:normal 12px Arial, Helvetica, sans-serif; background:#fff;"><span style="font:normal 12px Arial, Helvetica, sans-serif; background:#f4f4f4;">'.print_price($row_ords['order_deposit_amt']).' </span></td>
											  </tr>';
						}
						
						
						
						$prod_str_sp	.= '</table>';
					}
					$product_details_sp = $prod_str_sp;
					// Getting the design from otherfiles for the popular products
					 $file_pathc = ORG_DOCROOT."/images/".$ecom_hostname."/otherfiles/product-templ.html";
					 if(file_exists($file_pathc))
					 { 
						 $prod_templ = file_get_contents($file_pathc);
						 $prodsort_order = "ASC";
						 $prodsort_by    = "a.product_webprice";
						 $sql_best = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints ,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_show_pricepromise,a.product_freedelivery,
												IF(a.product_discount >0, 
												case a.product_discount_enteredasval
												WHEN 0 THEN (a.product_webprice-a.product_webprice*a.product_discount/100) 
												WHEN 1 THEN (IF((a.product_webprice-a.product_discount)>0,(a.product_webprice-a.product_discount),0)) 
												WHEN 2 THEN (a.product_discount) 
												END
												,a.product_webprice) calc_disc_price            
							FROM 
								products a,general_settings_site_bestseller b 
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.product_id = b.products_product_id 
								AND b.bestsel_hidden = 0 
								AND a.product_hide ='N' 
							ORDER BY 
								$prodsort_by $prodsort_order ";
								$best_prods = $db->query($sql_best);
								if ($db->num_rows($best_prods))
								{
									$prod_content ="";
									$prdcnt = 1;
									while($row_best = $db->fetch_array($best_prods))
									{
										$prodId_b = $row_best['product_id'];
										$prodName = strip_url(stripslashes($row_best['product_name']));
										$productPageUrlHash_spb = $ecom_selfhttp.$ecom_hostname."/".$prodName."-p$prodId_b.html";
										$product_name = '<a style="color:#493a23; font:bold 12px Arial, Helvetica, sans-serif; padding: 10px; text-decoration: none;" href='.$productPageUrlHash_spb.' '.$style_desc_prodname.'>'.$row_best['product_name'].'</a>';
										$pass_type ='image_thumbpath';
										$img_arr = get_imagelist('prod',$row_best['product_id'],$pass_type,0,0,2);
										if(count($img_arr))
										{
											  $HTML_image =  url_root_image($img_arr[0][$pass_type],1);
											  //$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_best['product_name'],$row_best['product_name'],'','',1);
										}
										$product_image = '<a  href='.$productPageUrlHash_spb.' '.$style_desc_prodname.'><img src="'.$HTML_image.'" width="139" height="122"/></a>';
										$price_arr =  show_Price($row_best,array(),'compshelf',false,5);
										
										$product_priceb = $price_arr['price_with_captions']['base_price'];
										$product_priceo = $price_arr['price_with_captions']['discounted_price'];
										$product_pricey = $price_arr['price_with_captions']['yousave_price'];
										$product_buylink = '<a href="'.url_product($row_best['product_id'],$row_best['product_name'],1).'" title="'.stripslash_normal($row_best['product_name']).'">
															<img src="'.$ecom_selfhttp.$ecom_hostname.'/images/'.$ecom_hostname.'/static/cart_bt.jpg" width="90" height="25" border="0" /></a>';
										$prod_search_arr = array(
																'[product_templ_name]',
																 '[product_templ_image]',
																 '[product_templ_price]',
																 '[product_templ_offer]',
																 '[product_templ_save]',
																 '[product_templ_addcart]'
																);
										$prod_rep_arr    = array(
																$product_name,
																$product_image,
																$product_priceb,
																$product_priceo,
																$product_pricey,
																$product_buylink
																);
										if($prdcnt ==1)
										$prod_content .= "<tr>";
										$prod_content .='<td width="201"align="center" valign="top" style="width:200px;">'.str_replace($prod_search_arr,$prod_rep_arr,$prod_templ).'</td>';
                                        $prdcnt++;
                                        if($prdcnt>3)
                                        {
										 $prod_content .= "</tr><tr>";
										 $prdcnt =1;
										}
										
									}
									if($prdcnt<=3)
									{
									  $prod_content .= "</tr>";
									}
								}
								else
								{
								   $email_content_sp = preg_replace('/<popularitems>(.*?)<\/popularitems>/iUs', "", $email_content_sp);
								}
					 }
					 else
					 {
						$email_content_sp = preg_replace('/<popularitems>(.*?)<\/popularitems>/iUs', "", $email_content_sp);
					 }
					$prod_rp_templ = ' 
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
					<td style="width:260px;font:normal 15px Arial, Helvetica, sans-serif; padding:5px 0px; ">
					<table width="601" border="0" cellspacing="0" cellpadding="0">
					';
					$prod_rp_templ .= $prod_content;
					$prod_rp_templ .='</table></td>
					</tr>
					</table>';
					 $ecom_hostnamelink = '<a style="font: bold 13px Arial, Helvetica, sans-serif; color: #544b3a; padding: 10px; text-decoration: none;" href="'.$ecom_selfhttp.$ecom_hostname.'">'.$ecom_hostname.'</a>';
			         

			         $search_arr_sp = array
									(
										'[orderid]',
										'[Gtotal]',
										'[billing_name]',
										'[billing_custcompany]',
										'[billing_buildingnumber]',
										'[billing_street]',
										'[billing_city]',
										'[billing_state]',
										'[billing_country]',
										'[billing_custpostcode]',
										'[billing_custphone]',
										'[billing_custmobile]',
										'[billing_custfax]',
										'[billing_custemail]',
										'[delivery_name]',
										'[delivery_custcompany]',
										'[delivery_buildingnumber]',
										'[delivery_street]',
										'[delivery_city]',
										'[delivery_state]',
										'[delivery_country]',
										'[delivery_custpostcode]',
										'[delivery_custphone]',
										'[delivery_custmobile]',
										'[delivery_custfax]',
										'[delivery_custemail]',
										'[payment_method]',
										'[delivery_location]',
										'[notes]',
										'[product_details]',
										'[payment_status]',
										'[popular_products]',
										'[domain]'										
									);
					// Building the array to replace the values in above array
					$replace_arr_sp = array
									(
										$email_orderid,
										$order_subtotal,
										$email_name,
										$bill_company,
										$bill_buildingnumber,
										$bill_street,
										$bill_city,
										$bill_state,
										$bill_country, 
										$bill_custpostcode,
										$bill_custphone,
										$bill_custmobile,
										$bill_custfax,
										$bill_custemail,
										$delemail_name,
										$del_company,
										$del_buildingnumber,
										$del_street,
										$del_city,
										$del_state,
										$del_country,
										$del_custpostcode,
										$del_custphone,
										$del_custmobile,
										$del_custfax,
										$del_custemail,
										$paymethod,
										$delivery_loc,
										$email_notes,
										$product_details_sp,
										'<paystat>'.getpaymentstatus_Name($row_ords["order_paystatus"]).'</paystat>',
										$prod_rp_templ,
										$ecom_hostnamelink
										
									);
				// Do the replacement in email template content
				  $email_content = str_replace($search_arr_sp,$replace_arr_sp,$email_content_sp);
			}
		
			$style_head_main = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#ffffff;font-weight:bold;border-bottom:1px solid  #acacac;background-color:#acacac;' ";
			$style_head = "style='padding:2px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;' ";
			$style_desc = "style='padding:2px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;' ";
			$style_desc_prodname ="style='padding:2px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;' ";
				$email_orderid	= $row_ords['order_id'];
				$email_name		= stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname']).' '.stripslashes($row_ords['order_custmname']).' '.stripslashes($row_ords['order_custsurname']);
				//$temp_date_arr  = explode(' ',$row_ords['order_date']);
				//$email_date_arr	= explode('-',$temp_date_arr[0]);
				$email_date		= $row_ords['formateddate'];//$email_date_arr[2].'-'.$email_date_arr[1].'-'.$email_date_arr[0];
				$email_to		= stripslashes($row_ords['order_custemail']);
				$email_notes	= stripslashes($row_ords['order_notes']);
				// ##############################################################################
				// 								Billing details
				// ##############################################################################
				// Get the checkout fields from general_settings_sites_checkoutfields table
				$sql_checkout = "SELECT field_key,field_name
									FROM
										general_settings_site_checkoutfields
									WHERE
										sites_site_id = $ecom_siteid
										AND field_type IN ('PERSONAL','DELIVERY')
									ORDER BY
										field_order";
				$ret_checkout = $db->query($sql_checkout);
				if($db->num_rows($ret_checkout))
				{
					while ($row_checkout = $db->fetch_array($ret_checkout))
					{
						$chkorder_arr[$row_checkout['field_key']] = stripslashes($row_checkout['field_name']);
					}
				}

				// ##############################################################################
				// Dynamic Values on top of billing details
				// ##############################################################################
				$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
								FROM
									order_dynamicvalues
								WHERE
									orders_order_id = $order_id
									AND position='Top'
								ORDER BY
									section_id,id";
				$ret_dynamic = $db->query($sql_dynamic);
				if($db->num_rows($ret_dynamic))
				{
					$prev_sec = 0;
					$cur_col	= 0;
					$max_col	= 2;
					$dynamictop_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					$dynamictopsp_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					while ($row_dynamic = $db->fetch_array($ret_dynamic))
					{
						if ($prev_sec!=$row_dynamic['section_id']) // Check whether section name is to be displayed
						{
							$prev_sec = $row_dynamic['section_id'];
							if ($row_dynamic['section_name']!='')
							{
								if($cur_col!=0)
								{
									$dynamictopsp_str .= '</tr>';
								}
								$dynamictop_str		.= '<tr> <td align="left" colspan="2" '.$style_head_main.'>'.stripslashes($row_dynamic['section_name']).' </td></tr>';
								$dynamictopsp_str		.= '<tr> <td align="left" colspan="4" '.$style_head_main.'>'.stripslashes($row_dynamic['section_name']).' </td></tr>';
								$cur_col=0;
							}	
						}
						$dynamictop_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td></tr>';
						if($cur_col==0)
						{
							$dynamictopsp_str .= '<tr>';
						}
						$dynamictopsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td>';
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col = 0;
						}
					}
					if($cur_col>0 and $cur_col<$max_col)
						$dynamictopsp_str .= '</tr>';	
					
					$dynamictopsp_str	.= '</table>';	
					$dynamictop_str	.= '</table>';
				}

				$bill_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
				$billsp_str		= $bill_str;
				// ##############################################################################
				// Dynamic Values on topinstatic of billing details
				// ##############################################################################
				$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
								FROM
									order_dynamicvalues
								WHERE
									orders_order_id = $order_id
									AND position='TopInStatic'
								ORDER BY
									section_id,id";
				$ret_dynamic = $db->query($sql_dynamic);
				if($db->num_rows($ret_dynamic))
				{
					$prev_sec 	= 0;
					$cur_col	= 0;
					$max_col	= 2;
					while ($row_dynamic = $db->fetch_array($ret_dynamic))
					{
						$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td></tr>';
						if($cur_col==0)
						{
							$billsp_str .= '<tr>';
						}
						$billsp_str .= ' <td align="left" width="25%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td>';
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col = 0;
						}
					}
					if($cur_col>0 and $cur_col<$max_col)
						$billsp_str .= '</tr>';	
				}
				// ##############################################################################
				// Main Billing address details
				// ##############################################################################
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Name </td><td align="left" width="50%" '.$style_desc.' >'.$email_name.' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_comp_name'].' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custcompany']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_building'] .' </td > <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_buildingnumber']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_street'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_street']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_city'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_city']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_state'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_state']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_country'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_country']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_zipcode'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custpostcode']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_phone'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custphone']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_mobile'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custmobile']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_fax'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custfax']).' </td></tr>';
				$bill_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_email'] .' </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custemail']).' </td></tr>';

				// Special billing address string
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>Name </td><td align="left" width="25%" '.$style_desc.' >'.$email_name.' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_comp_name'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custcompany']).' </td></tr>';
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_building'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_buildingnumber']).' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_street'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_street']).' </td></tr>';
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_city'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_city']).' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_state'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_state']).' </td></tr>';
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_country'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_country']).' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_zipcode'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custpostcode']).' </td></tr>';
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_phone'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custphone']).' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_mobile'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custmobile']).' </td></tr>';
				$billsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_fax'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custfax']).' </td>';
				$billsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkout_email'] .' </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_ords['order_custemail']).' </td></tr>';
	
				

				// ##############################################################################
				// Dynamic Values on bottominstatic of billing details
				// ##############################################################################
				$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
								FROM
									order_dynamicvalues
								WHERE
									orders_order_id = $order_id
									AND position='BottomInStatic'
								ORDER BY
									section_id,id";
				$ret_dynamic = $db->query($sql_dynamic);
				if($db->num_rows($ret_dynamic))
				{
					$prev_sec = 0;
					$cur_col	= 0;
					$max_col	= 2;
					while ($row_dynamic = $db->fetch_array($ret_dynamic))
					{
						$bill_str		.= ' <tr> <td align="left" width="50%" '.$style_head.' >'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td></tr>';
						if($cur_col==0)
						{
							$billsp_str .= '<tr>';
						}
						$billsp_str		.= ' <td align="left" width="25%" '.$style_head.' >'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td>';
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col = 0;
						}
					}
					if($cur_col>0 and $cur_col<$max_col)
						$billsp_str .= '</tr>';	
				}
				$bill_str		.= '</table>';
				$billsp_str		.= '</table>';
				// ##############################################################################
				// Dynamic Values on bottom of billing details
				// ##############################################################################
				$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
								FROM
									order_dynamicvalues
								WHERE
									orders_order_id = $order_id
									AND position='Bottom'
								ORDER BY
									section_id,id";
				$ret_dynamic = $db->query($sql_dynamic);
				if($db->num_rows($ret_dynamic))
				{
					$prev_sec = 0;
					$cur_col	= 0;
					$max_col	= 2;
					$dynamicbottom_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					$dynamicbottomsp_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					while ($row_dynamic = $db->fetch_array($ret_dynamic))
					{
						if ($prev_sec!=$row_dynamic['section_id']) // Check whether section name is to be displayed
						{
							$prev_sec = $row_dynamic['section_id'];
							if ($row_dynamic['section_name']!='')
							{
								if($cur_col!=0)
								{
									$dynamicbottomsp_str .= '</tr>';
								}
								$dynamicbottom_str		.= '<tr><td align="left" colspan="2" '.$style_head_main.' >'.stripslashes($row_dynamic['section_name']).' </td></tr>';
								$dynamicbottomsp_str		.= '<tr><td align="left" colspan="4" '.$style_head_main.' >'.stripslashes($row_dynamic['section_name']).' </td></tr>';
								$cur_col=0;
							}	
						}
						$dynamicbottom_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td></tr>';
						if($cur_col==0)
						{
							$dynamicbottomsp_str .= '<tr>';
						}
						$dynamicbottomsp_str		.= '<td align="left" width="25%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).' </td>';
						$cur_col++;
						if($cur_col>=$max_col)
						{
							$cur_col = 0;
						}
					}
					if($cur_col>0 and $cur_col<$max_col)
						$dynamicbottomsp_str .= '</tr>';	
					$dynamicbottom_str	.= '</table>';
					$dynamicbottomsp_str	.= '</table>';
					
				}
				// ##############################################################################
				// Concatenating the billing address
				// ##############################################################################
				$billing_addr		= $dynamictop_str.$bill_str.$dynamicbottom_str;
				$billingsp_addr		= $dynamictopsp_str.$billsp_str.$dynamicbottomsp_str;
				

				// ##############################################################################
				// Delivery details
				// ##############################################################################
				// Get the delivery details corresponding to current order
				$sql_del		= "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,
									delivery_companyname,delivery_buildingnumber,delivery_street,delivery_city,delivery_state,
									delivery_country,delivery_zip,delivery_phone,delivery_fax,delivery_mobile,
									delivery_email
								FROM
									order_delivery_data
								WHERE
									orders_order_id = $order_id
								LIMIT
									1";
				$ret_del		= $db->query($sql_del);
				if ($db->num_rows($ret_del))
				{
					$row_del = $db->fetch_array($ret_del);
				}
				
				$del_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Name </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_title']).''.stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']).' '.' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_comp_name'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_companyname']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_building'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_buildingnumber']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_street'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_street']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_city'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_city']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_state'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_state']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_country'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_country']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_zipcode'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_zip']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_phone'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_phone']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_mobile'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_mobile']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_fax'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_fax']).' </td></tr>';
				$del_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_email'] .' </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_email']).' </td></tr>';
				$del_str		.= '</table>';
				
				// Delivery Address special
				$delsp_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>Name </td> <td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_title']).''.stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']).' '.' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_comp_name'] .' </td><td align="left" width="25%" '.$style_desc.'> '.stripslashes($row_del['delivery_companyname']).' </td></tr>';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_building'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_buildingnumber']).' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_street'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_street']).' </td></tr>';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_city'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_city']).' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_state'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_state']).' </td></tr>';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_country'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_country']).' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_zipcode'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_zip']).' </td></tr>';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_phone'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_phone']).' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_mobile'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_mobile']).' </td></tr>';
				$delsp_str		.= '<tr> <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_fax'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_fax']).' </td>';
				$delsp_str		.= ' <td align="left" width="25%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_email'] .' </td><td align="left" width="25%" '.$style_desc.'>'.stripslashes($row_del['delivery_email']).' </td></tr>';
				$delsp_str		.= '</table>';

				// ##############################################################################
				// Delivery Type
				// ##############################################################################
				if ($row_ord['order_delivery_type']!='None')
				{
					$del_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					$delsp_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					
					$del_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Method </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_deliverytype']).' </td></tr>';
					$delsp_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Method </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_deliverytype']).' </td></tr>';
					if($row_ords['order_delivery_option']!='') // case if delivery option exists
					{
						$del_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Option </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_delivery_option']).' </td></tr>';
						$delsp_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Option </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_delivery_option']).' </td></tr>';
					}	
					if(trim($row_ords['order_deliverylocation'])!='')
					{
						$del_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Delivery Location </td> <td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_deliverylocation']).' </td></tr>';
						$delsp_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Delivery Location </td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_deliverylocation']).' </td></tr>';
					}
					if ($row_ord['order_deliveryprice_only']>0) // case of delivery charge along
					{
						$del_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Base Delivery Charge </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_deliveryprice_only']).' </td></tr>';
						$delsp_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Base Delivery Charge </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_deliveryprice_only']).' </td></tr>';
					}
					if ($row_ord['order_extrashipping']>0) // case of extra shipping exists
					{
						$del_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Extra Shipping Cost </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_extrashipping']).' </td></tr>';
						$delsp_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Extra Shipping Cost </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_extrashipping']).' </td></tr>';
					}
					$del_str 	.= '</table>';
					$delsp_str 	.= '</table>';
				}


				// ##############################################################################
				// Gift wrap details
				// ##############################################################################
				// Check whether gift wrap exists
				if($row_ords['order_giftwrap']=='Y')
				{
					$giftdet_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_giftwrap_per']=='order')
					{
						$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Apply to </td> <td align="left" width="50%" '.$style_desc.'>Order </td></tr>';
					}
					else
						$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Apply to </td> <td align="left" width="50%" '.$style_desc.'>Individual Items </td></tr>';

					if ($row_ords['order_giftwrap_minprice']>0)
						$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Minimum Price for Gift wrap </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_giftwrap_minprice']).' </td></tr>';

					if ($row_ords['order_giftwrapmessage']=='Y')
					{
						$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Message </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_giftwrap_message_charge']).' </td></tr>';
						$giftdet_str		.= '<tr> <td align="left" colspan="2" '.$style_desc.'>'.stripslashes($row_ords['order_giftwrapmessage_text']).' </td></tr>';
					}

					$sql_gift			= "SELECT giftwrap_name,giftwrap_price,giftwrap_price
											FROM
												order_giftwrap_details
											WHERE
												orders_order_id=$order_id";
					$ret_gift			= $db->query($sql_gift);
					if ($db->num_rows($ret_gift))
					{
						while ($row_gift = $db->fetch_array($ret_gift))
						{
							$giftdet_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.stripslashes($row_gift['giftwrap_name']).' </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_gift['giftwrap_price']).' </td></tr>';
						}
					}
					$giftdet_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Gift Wrap Total </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_giftwraptotal']).' </td></tr>';
					// Catting the total of gift wrap to giftdet_str variable
					$giftdet_str		.= '</table>';
				}
				else
					$giftdet_str = '';


				// ##############################################################################
				// Bonus Points Checking
				// ##############################################################################
				if($row_ords['order_bonuspoints_used']>0 or $row_ords['order_bonuspoint_inorder']>0)// if bonus points used or bonus points achieved in currenr order
				{
					$bonus_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_bonuspoints_used']>0)
					{
						$bonus_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Bonus Points Used </td> <td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoints_used'].' </td></tr>';
					}
					if ($row_ords['order_bonuspoints_used']>0)
					{
						$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points Rate </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_bonusrate']).' </td></tr>';
					}
					if($row_ords['order_bonuspoint_discount']>0)
					{
						$bonus_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Bonus Points Discount </td> <td align="left" width="50%" '.$style_desc.'>'.print_price($row_ords['order_bonuspoint_discount']).' </td></tr>';
						$bonustotal_str = '<tr>
												<td align="right" width="50%" colspan="2" '.$style_head.'>Bonus Points Discount </td>
												<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_bonuspoint_discount']).' </td>
											</tr>';
					}
					if ($row_ords['order_bonuspoint_inorder']>0)
					{
						$bonus_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Bonus Points gained due to this order </td><td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoint_inorder'].' </td></tr>';
					}
					$bonus_str 	.= 	'</table>';
				}
				/* Donate bonus Start */
				/* Donate bonus points done */
				if($row_ords['order_bonuspoints_donated']>0)
				{
					$bonus_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if ($row_ords['order_bonuspoints_donated']>0)
					{
						$bonus_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>Bonus Points Donated </td><td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoints_donated'].' </td></tr>';
					}
					$bonus_str 	.= 	'</table>';
				}
				/* Donate bonus End */
				// ##############################################################################
				// Tax Details
				// ##############################################################################

				$sql_tax 	= "SELECT tax_name,tax_percent,tax_charge
								FROM
									order_tax_details
								WHERE
									orders_order_id = $order_id";
				$ret_tax	= $db->query($sql_tax);
				if ($db->num_rows($ret_tax))
				{
					$tax_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					while ($row_tax = $db->fetch_array($ret_tax))
					{
						$tax_str	.= '<tr> <td align="left" width="50%" '.$style_head.'>'.stripslashes($row_tax['tax_name']).' ('.$row_tax['tax_percent'].'%)'.' </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_tax['tax_charge']).' </td></tr>';
					}
					$new_tot_tax_str = 'Total Tax';
					if($ecom_siteid == 87) // case of sumpandpump
						$new_tot_tax_str = 'VAT @ 20%';
					$tax_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>'.$new_tot_tax_str.' </td><td align="left" width="50%" '.$style_desc.'> '.print_price($row_ords['order_tax_total']).' </td></tr>';
					$tax_str 		.= 	'</table>';
				}


				// ##############################################################################
				// Promotional Code or Gift Voucher Details
				// ##############################################################################
				if($row_ords['gift_vouchers_voucher_id'] or $row_ords['promotional_code_code_id'])
				{
					$prom_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
					if($row_ords['gift_vouchers_voucher_id'])
					{
						// Get the gift voucher details
						$sql_voucher = "SELECT voucher_no,voucher_value_used
											FROM
												order_voucher
											WHERE
												orders_order_id = $order_id
											LIMIT
												1";
						$ret_voucher = $db->query($sql_voucher);
						if ($db->num_rows($ret_voucher))
						{
							$row_voucher 	= $db->fetch_array($ret_voucher);
							$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Gift Voucher Code </td><td align="left" width="50%" '.$style_desc.'>'.$row_voucher['voucher_no'].' </td></tr>';
							$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Gift Voucher Discount </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_voucher['voucher_value_used']).' </td></tr>';
							$promtotal_str	= '<tr> <td align="right" width="50%" colspan="2" '.$style_head.'>Gift Voucher Discount </td><td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_voucher['voucher_value_used']).' </td></tr>';
						}

					}
					elseif($row_ords['promotional_code_code_id'])
					{
						// Get the promotional code details
						$sql_prom = "SELECT code_number,code_lessval,code_type
											FROM
												order_promotional_code
											WHERE
												orders_order_id = $order_id
											LIMIT
												1";
						$ret_prom = $db->query($sql_prom);
						if ($db->num_rows($ret_prom))
						{
							$row_prom 	= $db->fetch_array($ret_prom);
							$prom_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Promotional Code </td><td align="left" width="50%" '.$style_desc.'>'.$row_prom['code_number'].' </td></tr>';
							if ($row_prom['code_type']!='product') // show only if not of type 'product' if type is product discount will be shown with product listing
							{
								$prom_str		.= '<tr> <td align="left" width="50%" '.$style_head.'>Promotional Code Discount </td><td align="left" width="50%" '.$style_desc.'>'.print_price($row_prom['code_lessval']).' </td></tr>';
								$promtotal_str	= '<tr> <td align="right" width="50%" colspan="2" '.$style_head.'>Promotional Code Discount </td><td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_prom['code_lessval']).' </td></tr>';
							}
						}

					}
					$prom_str 		.= 	'</table>';
				}
				// ##############################################################################
				// Product Details
				// ##############################################################################
				$prod_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
				//  added order_stock_combination_id in the following query for garraways uniqueid		
				$sql_prods  = "SELECT orderdet_id,product_name,products_product_id,order_qty,product_soldprice,order_retailprice,order_retailprice,order_discount,
										order_discount_type,order_rowtotal,order_stock_combination_id  
								FROM
									order_details
								WHERE
									orders_order_id = $order_id";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{ 

					$prod_str	.= '<tr>';
					if($ecom_siteid==61) // only for garraways uniqueid
					{
					$prod_str	.=	'<td align="left" width="10%" '.$style_head.'>'.$Captions_arr['CART']['CART_ID'].' </td>';
					}
					$prod_str	.=	   '<td align="left" width="40%" '.$style_head.'>'.$Captions_arr['CART']['CART_ITEM'].' </td>
										<td align="left" width="20%" '.$style_head.'>'.$Captions_arr['CART']['CART_PRICE'].' </td>
										<td align="left" width="15%" '.$style_head.'>'.$Captions_arr['CART']['CART_DISCOUNT'].' </td>
										<td align="left" width="25%" '.$style_head.'>'.$Captions_arr['CART']['CART_QTY'].' </td>
										<td align="left" width="25%" '.$style_head.'>'.$Captions_arr['CART']['CART_TOTAL'].' </td>
									</tr>';
					$var_comb = array();//for garraways uniqueid
							
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$show_man_id = '';
						$show_model = '';
						//get the manufacture id for current product from products table
						$sql_checkprod = "SELECT manufacture_id,product_default_category_id,product_model    
											FROM 
												products 
											WHERE 
												product_id = ".$row_prods['products_product_id']." 
											LIMIT 
												1";
						$ret_checkprod = $db->query($sql_checkprod);
						if ($db->num_rows($ret_checkprod))
						{
							$row_checkprod 	= $db->fetch_array($ret_checkprod);
							$cat_str 		= '';
							if($ecom_siteid==62) // only for eurolabels
							{
								// Get the name of the category 
								$sql_defcat = "SELECT category_name 
												FROM 
													product_categories 
												WHERE 
													category_id = ".$row_checkprod['product_default_category_id']." 
												LIMIT 
													1";
								$ret_defcat = $db->query($sql_defcat);
								if($db->num_rows($ret_defcat))
								{
									$row_defcat = $db->fetch_array($ret_defcat);
									$cat_str	= '<br/><strong>Category:</strong> '.stripslashes($row_defcat['category_name']);
								}
							}	
							if(trim($row_checkprod['manufacture_id'])!='')
								$show_man_id = ' ('.stripslashes(trim($row_checkprod['manufacture_id'])).')';
							if(trim($row_checkprod['product_model'])!='')
								$show_model = ' (Model - '.stripslashes(trim($row_checkprod['product_model'])).')';
							
							if($ecom_siteid==77) //shootuk
							{
								$show_man_id = $show_model = '';
							}	
						}
					$prodName = strip_url(stripslashes($row_prods['product_name']));
          			 $prodId = $row_prods['products_product_id'];
          			 if($ecom_siteid==61) // only for garraways uniqueid
					 { 
						 
						 $unique_id=show_specialcode_cons($row_prods['products_product_id'],$row_prods['order_stock_combination_id'],$row_prods['orderdet_id']);	
						 
						 /*if($row_prods['order_stock_combination_id'])// only for garraways uniqueid
						 {
							$unique_id = $row_prods['order_stock_combination_id'];
							$sql_unqprod = "SELECT comb_special_product_code FROM product_variable_combination_stock WHERE comb_id = $unique_id LIMIT 1";
							$ret_unqprod = $db->query($sql_unqprod);
							if($db->num_rows($ret_unqprod))
							{
								$row_unqprod = $db->fetch_array($ret_unqprod);
								$unique_id	 = $row_unqprod['comb_special_product_code'];
							}
						 }
						 else
						 {
							 
								   $sql_var = "SELECT var_id,var_value,var_name 
											FROM
												order_details_variables
											WHERE
												orders_order_id = $order_id
												AND order_details_orderdet_id =".$row_prods['orderdet_id']."";
												
							$ret_var = $db->query($sql_var); 
							if ($db->num_rows($ret_var))
							{
								$got_the_id = false;
								while ($row_var = $db->fetch_array($ret_var))
								{
									$sql_var_val ="SELECT var_value_id FROM product_variable_data 
									WHERE product_variables_var_id =".$row_var['var_id']."
									AND var_value='".$row_var['var_value']."'
									LIMIT 1";
									$ret_var_val = $db->query($sql_var_val);
									if($db->num_rows($ret_var_val)>0)
									{
									$row_var_val = $db->fetch_array($ret_var_val);
									 if($got_the_id == false)
									 {  
									 $sql_varcomb ="";
									 $sql_varcomb .= "SELECT a.comb_id from product_variable_combination_stock_details a
													WHERE a.products_product_id=$prodId
													AND a.product_variable_data_var_value_id=".$row_var_val['var_value_id']."
													AND a.product_variables_var_id=".$row_var['var_id']."";
									 
									$var_str ="";
									
									if(is_array($var_com) && count($var_com)>0)
									{//print_r($var_com);
									$var_str = implode(",",$var_com);
									$sql_varcomb .= " AND a.comb_id NOT IN(".$var_str.")";
									} 
										 // echo $var_str;          
									// $sql_varcomb .= " AND a.comb_id NOT IN(".$var_str.")";
									$sql_varcomb .=" LIMIT 1";
									$ret_varcomb = $db->query($sql_varcomb);  
									if($db->num_rows($ret_varcomb)>0)
									{
									  $row_varcomb =$db->fetch_array($ret_varcomb); 
									  if($row_varcomb['comb_id']>0)
									  {  
										  $got_the_id = true;
										  $var_com_id[$row_prods['orderdet_id']] = $row_varcomb['comb_id'];
										 //echo $var_com_id[$row_prods['orderdet_id']]."*";
										  $var_com[] = $var_com_id[$row_prods['orderdet_id']];								     
									  } 
									}
									}               
									} 
								}							
							} 
							//print_r($var_com);echo "<pre>";   
							if($var_com_id[$row_prods['orderdet_id']]>0)
							{							
								$unique_id = $var_com_id[$row_prods['orderdet_id']];
								$sql_unqprod = "SELECT comb_special_product_code FROM product_variable_combination_stock WHERE comb_id = $unique_id LIMIT 1";
								$ret_unqprod = $db->query($sql_unqprod);
								if($db->num_rows($ret_unqprod))
								{
									$row_unqprod = $db->fetch_array($ret_unqprod);
									$unique_id	 = $row_unqprod['comb_special_product_code'];
								}
							}
							else
							{
								$unique_id = $row_prods['products_product_id']; 
								$sql_unqprod = "SELECT product_special_product_code FROM products WHERE product_id = $unique_id LIMIT 1";
								$ret_unqprod = $db->query($sql_unqprod);
								if($db->num_rows($ret_unqprod))
								{
									$row_unqprod = $db->fetch_array($ret_unqprod);
									$unique_id	 = $row_unqprod['product_special_product_code'];
								}
							}	
						 }*/
				    }
		   			$productPageUrlHash = $ecom_selfhttp.$ecom_hostname."/".$prodName."-p$prodId.html";			
						$prod_str	.= '<tr>';
						if($ecom_siteid==61) // only for garraways uniqueid
					    {
						$prod_str	.= '<td align="left" width="10%" '.$style_desc.'>'.$unique_id.' </td>';
					    }
						$prod_str	.= '<td align="left" width="30%" '.$style_desc.'><a href='.$productPageUrlHash.' '.$style_desc_prodname.'>'.stripslashes($row_prods['product_name']).'</a> '.$show_man_id.$show_model.$cat_str.' </td>
										<td align="left" width="15%" '.$style_desc.'>'.print_price($row_prods['order_retailprice']).' </td>
										<td align="left" width="20%" '.$style_desc.'>'.print_price($row_prods['order_discount']).' </td>
										<td align="left" width="15%" '.$style_desc.'>'.stripslashes($row_prods['order_qty']).' </td>
										<td align="right" width="20%" '.$style_desc.'>'.print_price($row_prods['order_rowtotal']).' </td>
										</tr>';
						if($ecom_siteid==61) // only for garraways uniqueid
					    {
							 $colspan = 6;
							 $colspanA = 3;
						}
						else
						{
							$colspan = 5;
							 $colspanA = 2;
						}
						// Call function to decide whether grid display is to be used or not.
			    $check_arr = is_grid_display_enabled_prod($row_prods['products_product_id']);
				//if($check_arr['enabled']==true)
				{
					$sql_prod_grid = "SELECT product_intensivecode,product_metrodentcode,product_isocode FROM products WHERE product_id = ".$row_prods['products_product_id']." AND sites_site_id =".$ecom_siteid." LIMIT 1";
					$ret_prod_grid = $db->query($sql_prod_grid);
					if($db->num_rows($ret_prod_grid)>0)
					{
					     $row_prod_grid = $db->fetch_array($ret_prod_grid);
						 if($row_prod_grid['product_intensivecode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['INTENSIVE'].': '.stripslashes($row_prod_grid['product_intensivecode']).'</td>
										</tr>';
						 }	
						  if($row_prod_grid['product_metrodentcode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['METRODENT'].': '.stripslashes($row_prod_grid['product_metrodentcode']).'</td>
										</tr>';
						 }	
						  if($row_prod_grid['product_isocode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['ISOCODE'].': '.stripslashes($row_prod_grid['product_isocode']).'</td>
										</tr>';
						 }				
					}
				}	
				//else			
				if($check_arr['enabled']==false)
				{				
							// Check whether any variables exists for current product in order_details_variables
							$sql_var = "SELECT var_name,var_value,var_id 
											FROM
												order_details_variables
											WHERE
												orders_order_id = $order_id
												AND order_details_orderdet_id =".$row_prods['orderdet_id'];
							$ret_var = $db->query($sql_var);
							if ($db->num_rows($ret_var))
							{
								while ($row_var = $db->fetch_array($ret_var))
								{
									
									$prod_str	.= '<tr>
													<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']).' </td>
													</tr>';
										if($ecom_siteid==103)
										{
											if (trim($row_var['var_value'])!='')
											{
												// get the var_value_id for current value for the current variable 
												$sql_getvarvals = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id = ".$row_var['var_id']." AND var_value = '".$row_var['var_value']."' LIMIT 1";
												$ret_getvarvals = $db->query($sql_getvarvals);
												if($db->num_rows($ret_getvarvals))
												{
													$row_getvarvals = $db->fetch_array($ret_getvarvals);
													$sql_getmpn = "SELECT var_mpn FROM product_variable_data WHERE var_value_id = ".$row_getvarvals['var_value_id']." LIMIT 1";
													$ret_getmpn = $db->query($sql_getmpn);
													if($db->num_rows($ret_getmpn))
													{
														$row_getmpn = $db->fetch_array($ret_getmpn);
														if(trim($row_getmpn['var_mpn'])!='')
														{
														//$mpn = ' <br>'.$Captions_arr['CART']['CART_MPN'].stripslashes($row_getmpn['var_mpn']);
														$prod_str	.= '<tr>
														<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['CART_MPN'].''.stripslashes($row_getmpn['var_mpn']).' </td>
														</tr>';
														}
													}
												}	
											}
										}
								}
							}
							// Check whether any variables messages exists for current product in order_details_messages
							$sql_msg = "SELECT message_caption,message_value
											FROM
												order_details_messages
											WHERE
												orders_order_id = $order_id
												AND order_details_orderdet_id =".$row_prods['orderdet_id'];
							$ret_msg = $db->query($sql_msg);
							if ($db->num_rows($ret_msg))
							{
								while ($row_msg = $db->fetch_array($ret_msg))
								{
									$prod_str	.= '<tr>
													<td align="left" colspan="'.$colspan.'" '.$style_head.'> '.stripslashes($row_msg['message_caption']).': '.stripslashes($row_msg['message_value']).' </td>
													</tr>';
								}
							}
				}
			}	
				// ##################################################################################
				// Building order totals
				// ##################################################################################
					// subtotal
					$prod_str	.= '<tr>
											<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>'.$Captions_arr['CART']['CART_TOTPRICE'].' </td>
											<td align="right" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_subtotal']).' </td>
									</tr>';
					// giftwrap total and delivery type total and tax total
					if($row_ords['order_giftwraptotal']>0)
					{
						$prod_str	.= '<tr>
											<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>Gift Wrap Total </td>
											<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_giftwraptotal']).' </td>
											</tr>';
					}
					if($row_ords['order_deliverytotal']>0)
					{
						$prod_str	.= '<tr>
											<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>Delivery Total </td>
											<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_deliverytotal']).' </td>
										</tr>';
					}
					if($row_ords['order_tax_total']>0)
					{
						$new_tot_tax_str = 'Total Tax';
						if($ecom_siteid == 87) // case of sumpandpump
							$new_tot_tax_str = 'VAT @ 20%';
						$prod_str	.= '<tr>
											<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>'.$new_tot_tax_str.' </td>
											<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_tax_total']).' </td>
										</tr>';
					}

					// Customer / Corporate discount
					if ($row_ords['order_customer_discount_value']>0)
					{
						if ($row_ords['order_customer_or_corporate_disc']=='CUST')
						{
							if($row_ords['order_customer_discount_type']=='Disc_Group')
								$caption = 'Customer Group Discount ('.$row_ords['order_customer_discount_percent'].'%)';
							else
								$caption = 'Customer Discount ('.$row_ords['order_customer_discount_percent'].'%)';
							$caption_val = $row_ords['order_customer_discount_value'];
							$prod_str	.= '<tr>
												<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>'.$caption.' </td>
												<td align="right" colspan="3" '.$style_desc.'>'.print_price($caption_val).' </td>
											</tr>';
						}
						else // case of corporate discount
						{
							$caption = 'Corporate Discount ('.$row_ords['order_customer_discount_percent'].'%)';
							$caption_val = $row_ords['order_customer_discount_value'];
							$prod_str	.= '<tr>
												<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>'.$caption.' </td>
												<td align="right" colspan="3" '.$style_desc.'>'.print_price($caption_val).' </td>
											</tr>';
						}
					}
					// promotional code or gift voucher discounts or bonus points spend discount
					$prod_str		.= $promtotal_str.$bonustotal_str;
					// Total Final Cost
					$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>Grand Total </td>
										<td align="right" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_totalprice']).' </td>
									</tr>';
					// Check whether product deposit exists
					if($row_ords['order_deposit_amt']>0)
					{
						$dep_less = $row_ords['order_totalprice'] - $row_ords['order_deposit_amt'];
						$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>Less Product Deposit Amount </td>
										<td align="right" colspan="3" '.$style_desc.'>'.print_price($dep_less).' </td>
									</tr>';
						$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="'.$colspanA.'" '.$style_head.'>Amount Payable Now </td>
										<td align="right" colspan="3" '.$style_desc.'>'.print_price($row_ords['order_deposit_amt']).' </td>
									</tr>';
					}

					$prod_str		.= '</table>';
				}

					if($ecom_siteid == 88) //  For skatesrus
					{
						$paytype 	= getpaymenttype_Name_original($row_ords['order_paymenttype']);
						$paymethod	= $row_ords['order_paymentmethod'];
						if($paymethod!='')
						{
							$paymethod = getpaymentmethod_Name($paymethod);
						}
						else
							$paymethod = '';
					}
					else
					{
						$paytype 	= getpaymenttype_Name($row_ords['order_paymenttype']);
						$paymethod	= $row_ords['order_paymentmethod'];
						if($paymethod!='')
						{
							$paymethod = getpaymentmethod_Name($paymethod);
						}
						else
							$paymethod = 'N/A';
					}		
				
				// Handling the section to take care of special type of billing and delivery address details
				/*if($ecom_siteid == 75) // currently done only for iloveflooring website
				{
					$billing_addr 	= $billingsp_addr;
					$del_str		= $delsp_str; 
				}*/	
				// Building the array to search for to make the replacements
				$cur_ipaddress = $row_ords['order_ip'];
				$search_arr = array
								(
									'[cust_name]',
									'[domain]',
									'[orderid]',
									'[orderdate]',
									'[billing_details]',
									'[delivery_details]',
									'[giftwrap_details]',
									'[bonus_details]',
									'[tax_details]',
									'[product_details]',
									'[notes]',
									'[payment_status]',
									'[payment_type]',
									'[payment_method]',
									'[date]',
									'[promo_vouch_det]',
									'[ip_address]'
								);
				// Building the array to replace the values in above array
				$replace_arr = array
								(
									$email_name,
									$ecom_hostname,
									$email_orderid,
									$email_date,
									$billing_addr,
									$del_str,
									$giftdet_str,
									$bonus_str,
									$tax_str,
									$prod_str,
									$email_notes,
									'<paystat>'.getpaymentstatus_Name($row_ords["order_paystatus"]).'</paystat>',
									$paytype,
									$paymethod,
									$email_date,
									$prom_str,
									$cur_ipaddress
								);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
           			
//for dental diamonds  
			if($ecom_siteid==103)
			{
				if($row_ords['customers_customer_id']>0)
				{
				    $sql_custm = "SELECT metrodent_account_number FROM customers WHERE customer_id=".$row_ords['customers_customer_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
				    $ret_custm = $db->query($sql_custm);
				    if($db->num_rows($ret_custm)>0)
				    {
						$row_custm = $db->fetch_array($ret_custm);
						$metroac  = $row_custm['metrodent_account_number'];
					}
				}
				if($metroac!='')
				{
					$metroacc	= '
					<strong>Metrodent Account Number : </strong> '. $metroac.'';
				$search_arrm = array('[metrodent_account]');
				$replace_arrm = array($metroacc);
				$email_content = str_replace($search_arrm,$replace_arrm,$email_content);
				}
				else
				{
				$search_arrm = array('[metrodent_account]');
				$replace_arrm = array(' ');
				$email_content = str_replace($search_arrm,$replace_arrm,$email_content);
				}
			   
			}
             $email_subject   = str_replace('[orderid]',$order_id,$email_subject);
			// Building email headers to be used with the customer order confirmation email
			
			
			$email_headers = "From: $ecom_hostname	<$email_from>\n";
			$email_headers .= "MIME-Version: 1.0\n";
			$email_headers .= "Content-type: text/html; charset=iso-8859-1\n";


			// Inserting the order confirmation email details for customer in order_emails table
			$insert_array						= array();
			$insert_array['orders_order_id']	= $email_orderid;
			$insert_array['email_to']		=  addslashes(stripslashes(strip_tags($email_to)));
			$insert_array['email_subject']		=  addslashes(stripslashes(strip_tags($email_subject)));
			//$insert_array['email_message']	= addslashes(stripslashes($email_content));
			$insert_array['email_headers']		=  addslashes(stripslashes($email_headers));
			$insert_array['email_type']			= 'ORDER_CONFIRM_CUST';
			$insert_array['email_was_disabled']	= $email_disabled;
			$db->insert_from_array($insert_array,'order_emails');
			$curmail_id	= $db->insert_id();
			/* 10 Nov 2011 */
			$email_content = add_line_break($email_content);
			write_email_as_file('ord',$curmail_id,stripslashes($email_content));
			if($order_invoice_id>0) // case if order invoice option is activated for current website
			{
				$pass_paystatus = getpaymentstatus_Name($row_ords["order_paystatus"]);
				// Calling function to generate and save invoice html file
				CreateInvoice_file($email_orderid,$order_invoice_id,$email_name,$email_orderid,$row_ords,$del_str,$giftdet_str,$bonus_str,$tax_str,$prod_str,$email_notes,
								$pass_paystatus,$paytype,$paymethod,$email_date);		
			}
			if($email_disabled==0)
			{
				if($payData["result"] != 5 && $payData["result"] != 9 && $payData["result"] != 3 && $payData["result"] != 4 && $payData["result"] !=11 && $payData["result"] !=20)// case of not 3d secure and not protx vsp and not order failure in case of protx direct or paypal express
				{
					$sendmail_now = false;
					if ($row_ords['order_paymenttype'] != 'credit_card' and $row_ords['order_paymenttype'] != '4min_finance') // case if payment type is not by credit card
					{
						$sendmail_now = true;
					}
					else
					{
						switch($cartData["payment"]["method"]['paymethod_key'])
						{
							// Send mail if the payment method is any of the following
							case 'SELF':
							case 'ABLE2VERIFY':
							case 'PROTX':
							case 'PAYPAL_EXPRESS':
							case 'PAYPALPRO':
								$sendmail_now = true;
								clear_Cart($sess_id);
							break;
						};
					}
					
					if($sendmail_now) // Check whether mail is to be send now
					{
						// Replacing the Payment Details before sending the mail
						$pay_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
						$pay_str		.= '<tr><td width="50%" align="left" '.$style_head.'>Payment Status </td><td width="50%" align="left" '.$style_desc.'>'.getpaymentstatus_Name($row_ords['order_paystatus']).' </td></tr>';
						$pay_str		.= '</table>';
						$email_content 	= preg_replace ("/<paystat>(.*)<\/paystat>/", "<paystat>$pay_str</paystat>", $email_content);
                                                
						//$email_content	= str_replace('[payment_status]',$pay_str,$email_content);
						// check whether order invoice email template is disabled
						$sql_check = "SELECT lettertemplate_disabled  
										FROM 
											general_settings_site_letter_templates  
										WHERE 
											lettertemplate_letter_type='ORDER_CONFIRM_INVOICE' 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
						}
						if($order_invoice_id>0 and $row_check['lettertemplate_disabled']==0) // if order invoice option is activated for current website, then send mail with invoice attachment
						{	
							//===================================Send Mail ================================
							sendOrderMailWithAttachment($email_orderid,$order_invoice_id,$email_to,$email_subject,$email_content);
						}
						else
						{
							//if($ecom_siteid==111 || $ecom_siteid==57)
							//{ 
							    $ret_err = '';
						$ret_err .= "***before";
						write_email_as_file_error('ord',$email_orderid."*before",'',$ret_err);
								$ret_err = mail_Phpmaler($email_to, $email_subject,$email_content,$email_from,$ecom_hostname,$email_headers);								
								//if($ecom_siteid==112)
								{
									write_email_as_file_error('ord',$email_orderid,'',$ret_err);
								}

							//}
							/*
							else
						    {
							mail($email_to, $email_subject, $email_content, $email_headers);
							}
							*/ 
						}	
						//Updating the email_sendonce field in order_email table for current mail
						$update_array						= array();
						$update_array['email_sendonce']		= 1;
						$update_array['email_lastsenddate']	= 'now()';
						$db->update_from_array($update_array,'order_emails',array('email_id'=>$curmail_id));
					}
				}
			}
			// Get the content of order confirmation to site admin email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'ORDER_CONFIRM_ADMIN'
						LIMIT
							1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 			= $db->fetch_array($ret_template);
				$email_adminfrom		= stripslashes($row_template['lettertemplate_from']);
				$email_adminsubject		= stripslashes($row_template['lettertemplate_subject']);
				$email_admincontent		= stripslashes($row_template['lettertemplate_contents']);
				$email_admindisabled	= stripslashes($row_template['lettertemplate_disabled']);
			}
			
			// Do the replacement in email template content
			$email_admincontent = str_replace($search_arr,$replace_arr,$email_admincontent);
						

			//for dental diamonds  
			if($ecom_siteid==103)
			{				
				if($metroac!='')
				{
				 $metroacc	= '
					<strong>Metrodent Account Number : </strong> '. $metroac.'';
				$search_arrm = array('[metrodent_account]');
				$replace_arrm = array($metroacc);					
				  $email_admincontent = str_replace($search_arrm,$replace_arrm,$email_admincontent);
				}
				else
				{
				$search_arrm = array('[metrodent_account]');
				$replace_arrm = array(' ');
				$email_admincontent = str_replace($search_arrm,$replace_arrm,$email_admincontent);
				}
			   
			}
            $email_adminsubject     = str_replace('[orderid]',$order_id,$email_adminsubject);
			// Building email headers to be used with the customer order confirmation email
			$email_adminheaders 	= "From: $ecom_hostname	<$email_adminfrom>\n";
			$email_adminheaders 	.= "MIME-Version: 1.0\n";
			$email_adminheaders 	.= "Content-type: text/html; charset=iso-8859-1\n";

			
			// Inserting the order confirmation email details for customer in order_emails table
			$insert_array						= array();
			$insert_array['orders_order_id']	= $email_orderid;
			$insert_array['email_to']			= addslashes(stripslashes(strip_tags($Settings_arr['order_confirmationmail'])));
			$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_adminsubject)));
			//$insert_array['email_message']		= addslashes(stripslashes($email_admincontent));
			$insert_array['email_headers']		= addslashes(stripslashes($email_adminheaders));
			$insert_array['email_type']			= 'ORDER_CONFIRM_ADMIN';
			$insert_array['email_was_disabled']	= $email_admindisabled;
			$db->insert_from_array($insert_array,'order_emails');
			$curmail_id	= $db->insert_id();
			/* 10 Nov 2011 */
			$email_admincontent = add_line_break($email_admincontent);
			write_email_as_file('ord',$curmail_id,stripslashes($email_admincontent));
			if($email_admindisabled==0)
			{
				$to_arr	= array_filter(explode(",",$Settings_arr['order_confirmationmail']));
				if($payData["result"] != 5 && $payData["result"] != 9 && $payData["result"] != 3 && $payData["result"] != 4  && $payData["result"] != 11 && $payData["result"] != 20)// case of not 3d secure and not protx vsp
				{
					$sendmail_now = false;
					if ($row_ords['order_paymenttype'] != 'credit_card' and $row_ords['order_paymenttype'] != '4min_finance') // case if payment type is not by credit card
					{
						$sendmail_now = true;
					}
					else
					{
						switch($cartData["payment"]["method"]['paymethod_key'])
						{
							// Send mail if the payment method is any of the following
							case 'SELF':
							case 'ABLE2VERIFY':
							case 'PROTX':
							case 'PAYPAL_EXPRESS':
							case 'PAYPALPRO':
								$sendmail_now = true;
							break;
						};
					}
					if($sendmail_now) // check whether mail is to be send now
					{
						// Replacing the Payment Details before sending the mail
						$pay_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
						$pay_str		.= '<tr><td width="50%" align="left" '.$style_head.'>Payment Status </td><td width="50%" align="left" '.$style_desc.'>'.getpaymentstatus_Name($row_ords['order_paystatus']).' </td></tr>';
						$pay_str		.= '</table>';
						$email_admincontent 	= preg_replace ("/<paystat>(.*)<\/paystat>/", "<paystat>$pay_str</paystat>", $email_admincontent);
						//$email_admincontent	= str_replace('[payment_status]',$pay_str,$email_admincontent);						       
						        /*
						        if($ecom_siteid==100)
							    {
								  for($i=0;$i<count($to_arr);$i++) // send the order confm mail to as many mail ids which are set
									{
										if ($to_arr[$i]!='')
											mail($to_arr[$i], $email_adminsubject,$email_admincontent, $email_adminheaders);
									}	 
								}*/
						        if($ecom_siteid==112)
						        {
									 $ret_erra .= "***before";
						         write_email_as_file_error('ord',$email_orderid."*adminbefore",'',$ret_erra);
								 $ret_erra = mail_Phpmaler_admin_new($to_arr,$email_adminsubject,$email_admincontent,$email_adminfrom,$ecom_hostname,$email_adminheaders);
								//}
						         $ret_erra .= "***admin";
						         write_email_as_file_error('ord',$email_orderid."*admin",'',$ret_erra);	
								}
								else
								{
									 $ret_erra .= "***before";
						         write_email_as_file_error('ord',$email_orderid."*adminbefore",'',$ret_erra);
									
						        //else
								//{
								 $ret_erra = mail_Phpmaler_admin($to_arr,$email_adminsubject,$email_admincontent,$email_adminfrom,$ecom_hostname,$email_adminheaders);
								//}
						         $ret_erra .= "***admin";
						         write_email_as_file_error('ord',$email_orderid."*admin",'',$ret_erra);
								}	
						//Updating the email_sendonce field in order_email table for current mail
						$update_array						= array();
						$update_array['email_sendonce']		= 1;
						$update_array['email_lastsenddate']	= 'now()';
						$db->update_from_array($update_array,'order_emails',array('email_id'=>$curmail_id));
						clear_Cart($sess_id); 
					}
				}
			}
		}// end of main order checking
		if($from_iphone_app == false) // checking the request from is iphone 
		{
			if($payData["result"] == 5) // Payment process 3D Secure
			{
				$pass_typ = 'ord'; // This will be used to identify whether coming from order section or voucher section
				include("includes/3dsecure.php");
			}
			elseif($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"]== 11 or $payData["result"]== 20 or $payData["result"]== 35) // If payment failure happend, then redirect to payment failure section
			{
				echo "
						<script type='text/javascript'>
							window.location = '".$ecom_selfhttp.$ecom_hostname."/checkout_failed.html';
						</script>
						";
				
				exit;
				return;
			}
		}
	}// end of email template checking
	$ret_arr['order_id'] 	= $order_id;
	$ret_arr['payData']		= $payData;
	return $ret_arr;
}

function show_specialcode_cons($product_id,$comb_id,$orddet_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$specialcode = '';
	if($comb_id!=0) // case of combination stock
	{
		$sql_sel = "SELECT comb_special_product_code 
						FROM 
							product_variable_combination_stock 
						WHERE 
							comb_id=$comb_id 
						LIMIT 1";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			$row_sel = $db->fetch_array($ret_sel);
			$specialcode = trim(stripslashes($row_sel['comb_special_product_code']));		
		}	
	}
	else
	{
		$combination = 0;
		$sql_det_sp = "SELECT var_id, var_value FROM order_details_variables WHERE order_details_orderdet_id = $orddet_id AND var_value !=''";
		$ret_det_sp = $db->query($sql_det_sp);
		$sp_var_arr = array();
		if($db->num_rows($ret_det_sp))
		{
			while ($row_det_sp = $db->fetch_array($ret_det_sp))
			{
				// find the value id
				$sql_valid = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id =".$row_det_sp['var_id']. " AND var_value ='".$row_det_sp['var_value']."' LIMIT 1";
				$ret_valdid = $db->query($sql_valid);
				if($db->num_rows($ret_valdid))
				{
					$row_valid = $db->fetch_array($ret_valdid);
					$sp_var_arr[$row_det_sp['var_id']] = $row_valid['var_value_id'];
				}
			}
			$combination_arr_sp = find_combination_id_special_cons($product_id ,$sp_var_arr);
			$combination = $combination_arr_sp['combid'];
		}
		if($combination!=0) // case if combination id can be found 
		{
			$sql_unqprod = "SELECT comb_special_product_code FROM product_variable_combination_stock WHERE comb_id = ".$combination." LIMIT 1";
			$ret_unqprod = $db->query($sql_unqprod);
			if($db->num_rows($ret_unqprod))
			{
				$row_unqprod 	= $db->fetch_array($ret_unqprod);
				$specialcode	= $row_unqprod['comb_special_product_code'];
			}
		}
		else
		{
			// try to get the product code directly from products table
			$sql_prod= "SELECT product_special_product_code 
							FROM 
								products 
							WHERE 
								product_id = $product_id 
							LIMIT 
								1";
			$ret_prod = $db->query($sql_prod);
			if($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$specialcode = trim(stripslashes($row_prod['product_special_product_code']));
			} 
		}	
	}
	if($specialcode!='')
	{
		return $specialcode;
	}
}
function find_combination_id_special_cons($prodid,$var_arr)
	{ 
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
							product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,
							product_variablecombocommon_image_allowed  
						FROM
							products
						WHERE
							product_id=$prodid
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			

			
				if (count($var_arr))
				{
					$varids = array();
					foreach ($var_arr as $k=>$v)
					{
						// Check whether the variable is a check box or a drop down box
						$sql_check = "SELECT var_id
										FROM
											product_variables
										WHERE
											var_id=$k
											AND var_value_exists = 1
										LIMIT
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check 	= $db->fetch_array($ret_check);
							$varids[] 	= $k; // populate only the id's of variables which have values to the array
						}
					}
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{

						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{  
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}					

							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{ 
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
		}
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}

/*
	Function to decrement the stock, usage count for gift vouchers and customer discount updations
*/
	function do_PostOrderSuccessOperations($order_id)
	{
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr;
		global $ecom_selfhttp;
		/* Donate bonus Start */
		// Get relevant details from orders table 
		$sql_ord = "SELECT order_date,customers_customer_id,promotional_code_code_id,gift_vouchers_voucher_id,order_bonuspoints_used,
							order_bonuspoint_inorder,order_paymenttype,order_totalprice,order_deposit_amt,
							order_bonuspoints_donated    
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
		/* Donate bonus End */					
		$ret_ord = $db->query($sql_ord);
		if ($db->num_rows($ret_ord)==0) // done to handle the case if order with specified id does not exists
		{
			return ;
		}
		$row_ord = $db->fetch_array($ret_ord);
		// Get the order details for current order id
		$sql_det = "SELECT orderdet_id,products_product_id,order_orgqty,order_preorder,order_stock_combination_id,order_prom_id  
						FROM 
							order_details 
						WHERE 
							orders_order_id = $order_id";
		$ret_det = $db->query($sql_det);
		if ($db->num_rows($ret_det))
		{
			// Handle the case of decrementing the stock for products in order
			$prodext_arr= array(-1);
			while ($row_det = $db->fetch_array($ret_det))
			{
				// Handling the case of price promise count registering
				if($row_det['order_prom_id']!=0) // check whether current product is in price promise
				{
					//Update the used count or current price promise entry
					$update_price = "UPDATE 
										pricepromise 
									SET 
										prom_used = prom_used + 1,
										prom_used_on = now() 
									WHERE 
										prom_id = ".$row_det['order_prom_id']." 
										AND prom_max_usage>prom_used 
									LIMIT 
										1";
					$db->query($update_price);
				}
				
				// Check whether the product is currently in preorder or not
				$sql_prod = "SELECT product_preorder_allowed,product_variablestock_allowed,product_alloworder_notinstock  
								FROM 
									products 
								WHERE 
									product_id=".$row_det['products_product_id']." 
								LIMIT 
									1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
				}
				//if($row_prod['product_alloworder_notinstock']=='N') // do the stock decrement check and preorder decrement section only if "always add to cart" is set to 'N'
				//{
					if($row_prod['product_preorder_allowed']=='N') // Proceed to stock decrement section only if the product in not in preorder
					{
						if($Settings_arr['product_decrementstock']) // Set from console to decrement the stock when order is successfull
						{
							if($row_prod['product_variablestock_allowed']=='Y' and $row_det['order_stock_combination_id']) // If variable stock maintained
							{
								// Get the current stock for the current combination of current product
								$sql_stock = "SELECT web_stock
												FROM
													product_variable_combination_stock
												WHERE
													products_product_id = ".$row_det['products_product_id']. "
													AND comb_id = ".$row_det['order_stock_combination_id']."
												LIMIT
													1";
								$ret_stock = $db->query($sql_stock);
								if ($db->num_rows($ret_stock))
								{
									$row_stock = $db->fetch_array($ret_stock);
									if($row_stock['web_stock']>$row_det['order_orgqty']) // case if stock in web is > req qty
										$new_webstock = $row_stock['web_stock'] - $row_det['order_orgqty'];
									else
										$new_webstock = 0;
									// Updating the stock for current combination of current product
									$update_array				= array();
									$update_array['web_stock']	= $new_webstock;
									$db->update_from_array($update_array,'product_variable_combination_stock',array('comb_id'=>$row_det['order_stock_combination_id'],'products_product_id'=>$row_det['products_product_id']));
								}
							}
							else // Case if fixed stock is maintained
							{
								// Get the current stock for the product
								$sql_stock = "SELECT product_webstock
												FROM
													products
												WHERE
													product_id = ".$row_det['products_product_id']. "
												LIMIT
													1";
								$ret_stock = $db->query($sql_stock);
								if ($db->num_rows($ret_stock))
								{
									$row_stock = $db->fetch_array($ret_stock);
									if($row_stock['product_webstock']>$row_det['order_orgqty']) // case if stock in web is > req qty
										$new_webstock = $row_stock['product_webstock'] - $row_det['order_orgqty'];
									else
										$new_webstock = 0;
									// Updating the stock for current product
									$update_array						= array();
									$update_array['product_webstock']	= $new_webstock;
									$db->update_from_array($update_array,'products',array('product_id'=>$row_det['products_product_id']));
								}
							}
							// Calling function to recalculate the actual stock for the product
							recalculate_actual_stock($row_det['products_product_id']);
						}
					}
					else 
					{
					// If product was in preorder then decrement the max preorder allowed count for the product by one
						if($row_prod['product_preorder_allowed']=='Y')
						{
							if(!in_array($row_det['products_product_id'],$prodext_arr))// This is done to handle the case to decrement the total preorder value only once even if the product exists in cart more than once
							{
								$update_sql = "UPDATE 
													products 
												SET 
													product_total_preorder_allowed = product_total_preorder_allowed - 1 
												WHERE 
													product_total_preorder_allowed > 0 
													AND product_id = ".$row_det['products_product_id']." 
													AND sites_site_id = $ecom_siteid  
												LIMIT 
													1";
								$db->query($update_sql);
								$prodext_arr[] = $row_det['products_product_id'];
							}
						}	
					}
				//}
			}
		
			// If any gift voucher is used in current order, then increment the value of voucher_usage by 1
			if ($row_ord['gift_vouchers_voucher_id'])
			{
				$update_sql = "UPDATE gift_vouchers
								 SET
								 	voucher_usage = voucher_usage+1
								WHERE
									voucher_id = ".$row_ord['gift_vouchers_voucher_id']."
									AND sites_site_id = $ecom_siteid
									AND voucher_max_usage>voucher_usage";
				$db->query($update_sql);
			}
		
			
			// If any promotional code is used in current order and its usage is limited , then increment the value of code_usedlimit by 1
			if ($row_ord['promotional_code_code_id'])
			{
					// Increment the userlimit field value by  1
					$update_sql = "UPDATE promotional_code
											 SET
												code_usedlimit = code_usedlimit+1
											WHERE
												code_id = ".$row_ord['promotional_code_code_id']."
												AND sites_site_id = $ecom_siteid 
												AND code_unlimit_check=0 
												AND code_limit > code_usedlimit 
											LIMIT 
												1";
					$db->query($update_sql);
					if($row_ord['customers_customer_id'])
					{
						$update_sql = "UPDATE promotional_code
											 SET
												code_customer_usedlimit = code_customer_usedlimit+1
											WHERE
												code_id = ".$row_ord['promotional_code_code_id']." 
												AND code_login_to_use = 1 
												AND sites_site_id = $ecom_siteid 
												AND code_customer_unlimit_check=0 
												AND code_customer_limit > code_customer_usedlimit  
											LIMIT 
												1";
						$db->query($update_sql);
					}
			}			
			if($row_ord["customers_customer_id"])
			{
				// Section which updates the bonus points for customers
				// case if customer if logged in<br>
				// get the bonuspoints available for current customer
				$sql_cust = "SELECT customer_bonus 
								FROM 
									customers 
								WHERE 
									customer_id=".$row_ord['customers_customer_id']." 
								LIMIT 
									1";
				$ret_cust = $db->query($sql_cust);
				if($db->num_rows($ret_cust))
				{
					$row_cust 							= $db->fetch_array($ret_cust);
					$total_cust_bonuspoints				= $row_cust['customer_bonus'];
					$points_earned						= $row_ord['order_bonuspoint_inorder'];
					$points_used						= $row_ord['order_bonuspoints_used'];
					/* Donate bonus Start */
					$points_donated						= $row_ord['order_bonuspoints_donated'];
					/* Donate bonus End */
					
					/* Donate bonus Start */
					$final_bonuspoints					= $total_cust_bonuspoints + $points_earned - ($points_used+$points_donated);
					/* Donate bonus End */
					if($final_bonuspoints<0) // done to handle the case of bonus points becoming -ive 
						$final_bonuspoints = 0;
					$update_array						= array();
					$update_array['customer_bonus']		= $final_bonuspoints;
					$db->update_from_array($update_array,'customers',array('customer_id'=>$row_ord['customers_customer_id']));
				}
				
				//  Handling the case of downloadble products (if any) in current order. If exists and if active date range is applicable, then set the active start and end date based on number of days field
				$sql_download = "UPDATE order_product_downloadable_products 
												SET 
													proddown_days_active_start = now(),
													proddown_days_active_end = DATE_ADD(now(),INTERVAL proddown_days DAY) 
												WHERE 
													orders_order_id=$order_id 
													AND sites_site_id = $ecom_siteid 
													AND proddown_days_active=1 ";
				$db->query($sql_download);
				
				// Check whether payment type is pay_on_account
				if($row_ord['order_paymenttype']=='pay_on_account')
				{
					// If any exists for current order id in this table delete it 
					$sql_del = "DELETE FROM 
										order_payonaccount_details 
									WHERE 
										orders_order_id = ".$order_id." 
									LIMIT 
										1";
					$db->query($sql_del);
					// Making entry to order_payonaccount_details
					$insert_array														= array();
					$insert_array['pay_date']										= $row_ord['order_date'];
					$insert_array['orders_order_id']								= $order_id;
					$insert_array['sites_site_id']									= $ecom_siteid;
					$insert_array['customers_customer_id']					= $row_ord["customers_customer_id"];
					$insert_array['pay_amount']									= $row_ord['order_totalprice'];
					$insert_array['pay_transaction_type']						= 'D';	
					$insert_array['pay_details']									= 'Order Id '.$order_id;
					$insert_array['pay_paystatus']								= $row_ord['order_paymenttype'];
					$insert_array['pay_paymenttype']							= $row_ord['order_paymenttype'];
					$insert_array['pay_paymentmethod']						= '';
					$db->insert_from_array($insert_array,'order_payonaccount_details');
					
					// Decrement the pay on account limit for current customer
					$update_sql = "UPDATE customers 
												SET 
													customer_payonaccount_usedlimit = customer_payonaccount_usedlimit + ".$row_ord['order_totalprice'] ." 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND customer_id = ".$row_ord['customers_customer_id']." 
												LIMIT 
													1";
					$db->query($update_sql);
											
				}									
			}
		}
	}	
	function do_PostPayonAccountSuccessOperations($pay_id)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		
		// Get the details from order_payonaccount_pending_details table related to given pay_id
		$sql_pay = "SELECT  pendingpay_id, pay_date, sites_site_id, customers_customer_id, pay_amount, pay_transaction_type, pay_details,
                                    pay_paystatus, pay_paymenttype, pay_paymentmethod, pay_paystatus_changed_by, pay_paystatus_changed_on,
                                    pay_paystatus_changed_paytype, pay_additional_details, pay_curr_rate, pay_curr_code, pay_curr_symbol, pay_curr_numeric_code,
                                    pay_unique_key,pay_additional_details 
                                FROM 
                                    order_payonaccount_pending_details 
                                WHERE 
                                    pendingpay_id = $pay_id 
                                LIMIT 
                                    1";
		$ret_pay = $db->query($sql_pay);
		if($db->num_rows($ret_pay))
		{
			$row_pay = $db->fetch_array($ret_pay);
			// Insert details to order_payonaccount_details table  
			$insert_array					= array();
			$insert_array['pay_date']			= $row_pay['pay_date'];
			$insert_array['orders_order_id']		= 0;
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['customers_customer_id']		= $row_pay['customers_customer_id'];
			$insert_array['pay_amount']			= $row_pay['pay_amount'];
			$insert_array['pay_transaction_type']		= 'C';
			$insert_array['pay_details']			= 'Payment - Thank You';
			$insert_array['pay_paystatus']			= 'Paid';
			$insert_array['pay_amount']			= $row_pay['pay_amount'];
			$insert_array['pay_paymenttype']		= addslashes(stripslashes($row_pay['pay_paymenttype']));
			$insert_array['pay_paymentmethod']		= addslashes(stripslashes($row_pay['pay_paymentmethod']));
			$insert_array['pay_curr_rate']			= $row_pay['pay_curr_rate'];
			$insert_array['pay_curr_code']			= addslashes(stripslashes($row_pay['pay_curr_code']));
			$insert_array['pay_curr_symbol']		= addslashes(stripslashes($row_pay['pay_curr_symbol']));
			$insert_array['pay_curr_numeric_code']		= addslashes(stripslashes($row_pay['pay_curr_numeric_code']));
			$insert_array['pay_additional_details']		= addslashes(stripslashes($row_pay['pay_additional_details']));
			$insert_array['pay_temp_id']			= $pay_id;
			$db->insert_from_array($insert_array,'order_payonaccount_details');
			$insert_pay_id = $db->insert_id();
			// Get the details from order_payonaccount_pending_details_cheque_details if any for given pay_id
			$sql_cheque = "SELECT cheque_date, cheque_number, cheque_bankname, cheque_branchdetails	
                                            FROM 
                                                    order_payonaccount_pending_details_cheque_details 
                                            WHERE 
                                                    order_payonaccount_pending_details_pending_id = $pay_id 
                                            LIMIT 
                                                    1";
			$ret_cheque = $db->query($sql_cheque);
			if($db->num_rows($ret_cheque))
			{
				$row_cheque 						= $db->fetch_array($ret_cheque);
				$insert_array						= array();
				$insert_array['order_payaccount_cheque_pay_id']		= $insert_pay_id;
				$insert_array['cheque_date']				= $row_cheque['cheque_date'];
				$insert_array['cheque_number']				= addslashes(stripslashes($row_cheque['cheque_number']));
				$insert_array['cheque_bankname']			= addslashes(stripslashes($row_cheque['cheque_bankname']));
				$insert_array['cheque_branchdetails']			= addslashes(stripslashes($row_cheque['cheque_branchdetails']));
				$db->insert_from_array($insert_array,'order_payonaccount_cheque_details');
			}
			// Get the details from order_payonaccount_pending_details_payment if any for given pay_id
			$sql_payment = "SELECT card_type, card_number, name_on_card, sec_code, expiry_date_m, expiry_date_y, issue_number, issue_date_m, issue_date_y,
                                                vendorTxCode, protStatus, protStatusDetail, vPSTxId, securityKey, txAuthNo, txType, avscv2, cavv, 3dsecurestatus, acsurl, 
                                                pareq, md, orgtxType, card_encrypted, google_checkoutid, worldpay_transid, hsbc_cpiresultcode	
                                            FROM 
                                                    order_payonaccount_pending_details_payment 
                                            WHERE 
                                                    order_payonaccount_pendingpay_id = $pay_id 
                                            LIMIT 
                                                    1";
			$ret_payment = $db->query($sql_payment);
			if($db->num_rows($ret_payment))
			{
				$row_payment 						= $db->fetch_array($ret_payment);
				$insert_array						= array();
				$insert_array['order_payonaccount_pay_id']		= $insert_pay_id;
				$insert_array['card_type']				= addslashes(stripslashes($row_payment['card_type']));
				$insert_array['card_number']				= '';//addslashes(stripslashes($row_payment['card_number']));
				$insert_array['name_on_card']				= '';//addslashes(stripslashes($row_payment['name_on_card']));
				$insert_array['sec_code']				= 0;//addslashes(stripslashes($row_payment['sec_code']));
				$insert_array['expiry_date_m']				= 0;//addslashes(stripslashes($row_payment['expiry_date_m']));
				$insert_array['expiry_date_y']				= 0;//addslashes(stripslashes($row_payment['expiry_date_y']));
				$insert_array['issue_number']				= 0;//addslashes(stripslashes($row_payment['issue_number']));
				$insert_array['issue_date_m']				= 0;//addslashes(stripslashes($row_payment['issue_date_m']));
				$insert_array['issue_date_y']				= 0;//addslashes(stripslashes($row_payment['issue_date_y']));
				$insert_array['vendorTxCode']				= addslashes(stripslashes($row_payment['vendorTxCode']));
				$insert_array['protStatus']				= addslashes(stripslashes($row_payment['protStatus']));
				$insert_array['protStatusDetail']			= addslashes(stripslashes($row_payment['protStatusDetail']));
				$insert_array['vPSTxId']				= addslashes(stripslashes($row_payment['vPSTxId']));
				$insert_array['securityKey']				= addslashes(stripslashes($row_payment['securityKey']));
				$insert_array['txAuthNo']				= addslashes(stripslashes($row_payment['txAuthNo']));
				$insert_array['txType']					= addslashes(stripslashes($row_payment['txType']));
				$insert_array['avscv2']					= addslashes(stripslashes($row_payment['avscv2']));
				$insert_array['cavv']					= addslashes(stripslashes($row_payment['cavv']));
				$insert_array['3dsecurestatus']				= addslashes(stripslashes($row_payment['3dsecurestatus']));
				$insert_array['acsurl']					= addslashes(stripslashes($row_payment['acsurl']));
				$insert_array['pareq']					= addslashes(stripslashes($row_payment['pareq']));
				$insert_array['md']					= addslashes(stripslashes($row_payment['md']));
				$insert_array['orgtxType']				= addslashes(stripslashes($row_payment['orgtxType']));
				$insert_array['card_encrypted']				= addslashes(stripslashes($row_payment['card_encrypted']));
				$insert_array['google_checkoutid']			= addslashes(stripslashes($row_payment['google_checkoutid']));
				$insert_array['worldpay_transid']			= addslashes(stripslashes($row_payment['worldpay_transid']));
				$insert_array['hsbc_cpiresultcode']			= addslashes(stripslashes($row_payment['hsbc_cpiresultcode']));
				$db->insert_from_array($insert_array,'order_payonaccount_payment');
			}
                        
                        // getting the values from order_payonaccount_pending_details_payment_paypal and inserting to order_payonaccount_payment_paypal
                        $sql_paypal = "SELECT * 
                                        FROM 
r
                                            order_payonaccount_pending_details_payment_paypal  
                                        WHERE 
                                            order_payonaccount_pendingpay_id = $pay_id 
                                        LIMIT 
                                            1";
                        $ret_paypal = $db->query($sql_paypal);
                        if($db->num_rows($ret_pay))    
                        {  
                            $row_paypal = $db->fetch_array($ret_paypal);
                            // Inserting to order_payonaccount_pending_details_payment_paypal table
                            $insert_array                                   = array();
                            $insert_array['order_payonaccount_pay_id']      = $insert_pay_id;
                            $insert_array['sites_site_id']                  = $ecom_siteid;
                            $insert_array['paypal_transactions_id']         = addslashes(stripslashes($row_paypal["paypal_transactions_id"]));    
                            $insert_array['paypal_transaction_type']        = addslashes(stripslashes($row_paypal["paypal_transaction_type"]));
                            $insert_array['paypal_payment_type']            = addslashes(stripslashes($row_paypal["paypal_payment_type"]));
                            $insert_array['paypal_ordertime']               = addslashes(stripslashes($row_paypal["paypal_ordertime"]));
                            $insert_array['paypal_amt']                     = addslashes(stripslashes($row_paypal["paypal_amt"]));
                            $insert_array['paypal_currency_code']           = addslashes(stripslashes($row_paypal["paypal_currency_code"]));     
                            $insert_array['paypal_feeamt']                  = addslashes(stripslashes($row_paypal["paypal_feeamt"]));
                            $insert_array['paypal_settleamt']               = addslashes(stripslashes($row_paypal["paypal_settleamt"]));
                            $insert_array['paypal_taxamt']                  = addslashes(stripslashes($row_paypal["paypal_taxamt"]));
                            $insert_array['paypal_exchange_rate']           = addslashes(stripslashes($row_paypal["paypal_exchange_rate"]));
                            $insert_array['paypal_paymentstatus']           = addslashes(stripslashes($row_paypal["paypal_paymentstatus"]));
                            $insert_array['paypal_pending_reason']          = addslashes(stripslashes($row_paypal["paypal_pending_reason"]));
                            $insert_array['paypal_reasoncode']              = addslashes(stripslashes($row_paypal["paypal_reasoncode"]));
                            $db->insert_from_array($insert_array,'order_payonaccount_payment_paypal');
                        }
                        
			//Decrementing the paid amount in the customer_payonaccount_usedlimit field in customer table for current customers
			$update_cust = "UPDATE customers 
                                            SET 
                                                    customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - ".$row_pay['pay_amount']." 
                                            WHERE 
                                                    customer_id = ".$row_pay['customers_customer_id']." 
                                                    AND sites_site_id=$ecom_siteid 
                                            LIMIT 
                                                    1";
			$db->query($update_cust);
			// Sending mail if payment is approved 
			send_PayonAccountApproval($insert_pay_id);
			// Delete the details from order_payonaccount_pending_details_cheque_details tables
			$sql_del = "DELETE 
                                        FROM 
                                                order_payonaccount_pending_details_cheque_details 
                                        WHERE 
                                                order_payonaccount_pending_details_pending_id = $pay_id 
                                        LIMIT 
                                                1";
			$db->query($sql_del);
			// Delete the details from order_payonaccount_pending_details_payment tables
			$sql_del = "DELETE 
                                        FROM 
                                                order_payonaccount_pending_details_payment 
                                        WHERE 
                                                order_payonaccount_pendingpay_id = $pay_id 
                                        LIMIT 
                                                1";
			$db->query($sql_del);
                        // Delete the details from order_payonaccount_pending_details_payment_paypal tables
                        $sql_del = "DELETE 
                                        FROM 
                                                order_payonaccount_pending_details_payment_paypal  
                                        WHERE 
                                                order_payonaccount_pendingpay_id = $pay_id 
                                        LIMIT 
                                                1";
                        $db->query($sql_del);
			// Delete the details from order_payonaccount_pending_details tables
			$sql_del = "DELETE 
                                        FROM 
                                                order_payonaccount_pending_details  
                                        WHERE 
                                                pendingpay_id = $pay_id 
                                        LIMIT 
                                                1";
			$db->query($sql_del);
			
		}
		return $insert_pay_id;
	}
/*
	// Check whether bulk discount available of a given product
*/
	function product_BulkDiscount_Details($product_id,$comb_id=0)
	{
		global $db,$ecom_siteid,$PriceSettings_arr,$ecom_tax_total_arr;
		global $ecom_selfhttp;
		$bulk_arr_qty			= array();
		$bulk_arr_price		= array();
		$bulk_withoutarr_price		= array();
		$tax_arr 				= $ecom_tax_total_arr;
		$tax_val					= $tax_arr['tax_val'];
		$sql_prod = "SELECT product_bulkdiscount_allowed,product_applytax,product_discount,product_discount_enteredasval  
						FROM
							products
						WHERE
							product_id = $product_id
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			$bulk_disc = $row_prod['product_bulkdiscount_allowed'];
			$apply_tax = $row_prod['product_applytax'];
		}
		if($bulk_disc=='Y')
		{
			if($comb_id>0)
				$comb_add_cond = " AND comb_id = $comb_id ";
			else
				$comb_add_cond = " AND comb_id = 0 ";
			$sql_bulk = "SELECT bulk_qty,bulk_price
							FROM
								product_bulkdiscount
							WHERE
								products_product_id = $product_id 
								$comb_add_cond 
							ORDER BY
								bulk_qty ASC";
			$ret_bulk = $db->query($sql_bulk);
			if ($db->num_rows($ret_bulk))
			{
				while ($row_bulk = $db->fetch_array($ret_bulk))
				{
					$bulk_arr_qty[]			= $row_bulk['bulk_qty'];
 					switch($PriceSettings_arr['price_displaytype'])
					{
						case 'show_price_only': // show only price even if tax exists
							$bulk_arr_price[]			= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0;
						break;
						case 'show_price_plus_tax': // show price + Tax
							$bulk_arr_price[]			= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0;
						break;
						case 'show_price_inc_tax': // Show price including tax
							if ($apply_tax=='Y')
								$bulk_arr_price[]		= $row_bulk['bulk_price'] + ($row_bulk['bulk_price']*$tax_val/100);
							else
								$bulk_arr_price[]		= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0	;
						break;
						case 'show_both': // show price value and tax value
							if ($apply_tax=='Y')
							{
								$bulk_arr_price[]			= $row_bulk['bulk_price'] + ($row_bulk['bulk_price']*$tax_val/100);
								$bulk_withoutarr_price[]	= $row_bulk['bulk_price'];
							}	
							else
							{
								$bulk_arr_price[]			= $row_bulk['bulk_price'];
								$bulk_withoutarr_price[]	= 0	;
							}	
						break;
					};
				}
			}
		}
			$ret_arr['qty'] 			= $bulk_arr_qty;
			$ret_arr['price']			= $bulk_arr_price;
			$ret_arr['price_without']	= $bulk_withoutarr_price;
		return $ret_arr;
	}
	
	function product_BulkDiscount_Details_Puregusto($product_id,$comb_id=0,$var_arr = array()) // just for puregusto
	{
		global $db,$ecom_siteid,$PriceSettings_arr,$ecom_tax_total_arr;
		global $ecom_selfhttp;
		$bulk_arr_qty			= array();
		$bulk_arr_price		= array();
		$bulk_withoutarr_price		= array();
		$tax_arr 				= $ecom_tax_total_arr;
		$tax_val					= $tax_arr['tax_val'];
		
		$sql_prod = "SELECT product_bulkdiscount_allowed,product_applytax,product_discount,product_discount_enteredasval,product_variablecomboprice_allowed   
						FROM
							products
						WHERE
							product_id = $product_id
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			$bulk_disc = $row_prod['product_bulkdiscount_allowed'];
			$apply_tax = $row_prod['product_applytax'];
		}
		if($bulk_disc=='Y')
		{
			if($comb_id>0)
				$comb_add_cond = " AND comb_id = $comb_id ";
			else
				$comb_add_cond = " AND comb_id = 0 ";
			
			$new_additionalprice_total = 0;
			if($row_prod['product_variablecomboprice_allowed'] != 'Y')
			{
				if (count($var_arr)>0)
				{
					// Check whether add on price exists for the selected variable values
					foreach ($var_arr as $k=>$v)
					{
						//echo "<br> $k == $v";
						$sql_addp = "SELECT var_addprice 
										FROM 
											product_variable_data 
										WHERE 
											product_variables_var_id = $k 
											AND var_value_id = $v 
										LIMIT 
											1";
						$ret_addp = $db->query($sql_addp);
						if($db->num_rows($ret_addp))
						{
							$row_addp = $db->fetch_array($ret_addp);
							$new_additionalprice_total += $row_addp['var_addprice'];
						}
					}
				}
			}	
				
			$sql_bulk = "SELECT bulk_qty,bulk_price
							FROM
								product_bulkdiscount
							WHERE
								products_product_id = $product_id 
								$comb_add_cond 
							ORDER BY
								bulk_qty ASC";
			$ret_bulk = $db->query($sql_bulk);
			if ($db->num_rows($ret_bulk))
			{
				while ($row_bulk = $db->fetch_array($ret_bulk))
				{
					$bulk_arr_qty[]			= $row_bulk['bulk_qty'];
					
					
					
					if($ecom_siteid==105 || $ecom_siteid==112) // case of puregusto
					{
						//print_r($_REQUEST);
						
						$row_bulk['bulk_price'] += $new_additionalprice_total;
						
						$prod_n_disc 		= $row_prod['product_discount'];
						$prod_n_disctype 	= $row_prod['product_discount_enteredasval'];
						if($prod_n_disctype['product_discount_enteredasval']==0) // entered as %
						{
							if($prod_n_disc>0)
							{
								$row_bulk['bulk_price'] -= $row_bulk['bulk_price'] * $prod_n_disc/100;
							}
						}
						$cust_id 				= get_session_var('ecom_login_customer');
						if($cust_id)// case if customer is logged in
						{
							$cust_n_disc_exists 	= get_session_var('ecom_cust_direct_exists');
							$cust_n_disc_percent 	= get_session_var('ecom_cust_direct_disc');
							if($cust_n_disc_exists==1 and $cust_n_disc_percent>0)
							{
								// Check whether customer direct discount is applicable
								$sql_chks = "SELECT customer_allow_product_discount FROM customers WHERE customer_id = $cust_id AND sites_site_id = $ecom_siteid LIMIT 1";
								$ret_chks = $db->query($sql_chks);
								if($db->num_rows($ret_chks))
								{
									$row_chks = $db->fetch_array($ret_chks);
									if($row_chks['customer_allow_product_discount']==1)
									{
										$row_bulk['bulk_price'] -= $row_bulk['bulk_price'] * $cust_n_disc_percent/100;	
									}	
								}	
							}	
						}
					}
					
					
					switch($PriceSettings_arr['price_displaytype'])
					{
						case 'show_price_only': // show only price even if tax exists
							$bulk_arr_price[]			= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0;
						break;
						case 'show_price_plus_tax': // show price + Tax
							$bulk_arr_price[]			= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0;
						break;
						case 'show_price_inc_tax': // Show price including tax
							if ($apply_tax=='Y')
								$bulk_arr_price[]		= $row_bulk['bulk_price'] + ($row_bulk['bulk_price']*$tax_val/100);
							else
								$bulk_arr_price[]		= $row_bulk['bulk_price'];
							$bulk_withoutarr_price[]	= 0	;
						break;
						case 'show_both': // show price value and tax value
							if ($apply_tax=='Y')
							{
								$bulk_arr_price[]			= $row_bulk['bulk_price'] + ($row_bulk['bulk_price']*$tax_val/100);
								$bulk_withoutarr_price[]	= $row_bulk['bulk_price'];
							}	
							else
							{
								$bulk_arr_price[]			= $row_bulk['bulk_price'];
								$bulk_withoutarr_price[]	= 0	;
							}	
						break;
					};
				}
			}
		}
			$ret_arr['qty'] 			= $bulk_arr_qty;
			$ret_arr['price']			= $bulk_arr_price;
			$ret_arr['price_without']	= $bulk_withoutarr_price;
		return $ret_arr;
	}
	
	
function product_BulkDiscount_Build_Price($price_arr_val,$price_without_arr_val)
{
	global $PriceSettings_arr;
	global $ecom_selfhttp;
	if($price_without_arr_val)
	{
		$show_price = print_price($price_without_arr_val).' ('.print_price($price_arr_val).' '.$PriceSettings_arr['price_tax_inc'].')';
		return $show_price;
	}
	else
		return print_price($price_arr_val);
}	
// Function to recalculate the value in actual stock field
function recalculate_actual_stock($prodid)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$stock = 0;
	$stock_exists = false;
	// Get the basic details of current product from products table
	$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_hide_on_nostock,product_alloworder_notinstock FROM products WHERE product_id=$prodid";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);

		if($row_prod['product_variablestock_allowed'] == 'Y') // Case of variable stock exists
		{
			// ** If variable stock is maintained for current product, then set the webstock
			// and the fixed stock in various shops for current product to 0
			$update_array							= array();
			$update_array['product_webstock']		= 0;
			$update_array['product_actualstock']	= 0;
			$db->update_from_array($update_array,'products',array('product_id'=>$prodid));

			$update_array				= array();
			$update_array['shop_stock']	= 0;
			$db->update_from_array($update_array,'product_shop_stock',array('products_product_id'=>$prodid));



			$comb_arr = array();
			// Get the combinations for current product
			$sql_comb = "SELECT comb_id,web_stock FROM product_variable_combination_stock WHERE products_product_id=$prodid";
			$ret_comb = $db->query($sql_comb);
			if ($db->num_rows($ret_comb))
			{
				while($row_comb = $db->fetch_array($ret_comb))
				{
					$combid = $row_comb['comb_id'];
					$stock += $row_comb['web_stock']; // webstock for current combination

					// Get the sum of stocks existing for current product from product_shop_variable_combination_stock table
					$sql_shop = "SELECT sum(shop_stock) FROM product_shop_variable_combination_stock WHERE products_product_id=$prodid
								AND comb_id=$combid";
					$ret_shop = $db->query($sql_shop);
					list($shop_stock) = $db->fetch_array($ret_shop);
					$stock += $shop_stock; // adding the sum of stock in shops for current combination
					$var_actualstock = $row_comb['web_stock'] + $shop_stock; // actual stock to be placed in the

					if ($var_actualstock>0)
						$stock_exists = true;
					//Updating the actual_stock in product_variable_combination_stock table
					$update_array					= array();
					$update_array['actual_stock']	= $var_actualstock;
					$db->update_from_array($update_array,'product_variable_combination_stock',array('comb_id'=>$combid));
				}
				// Updating the product_actualstock field with the total of variable combination stock value
				$update_sql = "UPDATE 
											products 
										SET 
											product_actualstock = $stock 
										WHERE 
											product_id = $prodid 
											AND sites_site_id =$ecom_siteid 
										LIMIT 
											1";
				$db->query($update_sql);
			}
		}
		else // Case variable stock does not exists
		{
			$stock = $row_prod['product_webstock'];// getting the webstock
			// Get the sum of stocks existing for current product from product_shop_stock table
			$sql_shop = "SELECT sum(shop_stock) FROM product_shop_stock WHERE products_product_id=$prodid";
			$ret_shop = $db->query($sql_shop);
			list($shop_stock) = $db->fetch_array($ret_shop);
			$stock += $shop_stock;

			if ($stock>0)
				$stock_exists = true;
			//Updating the product_actualstock in products table with the calculated value
			$update_array							= array();
			$update_array['product_actualstock']	= $stock;
			$db->update_from_array($update_array,'products',array('product_id'=>$prodid));

			// Making the stock value to 0 for current product in table
			// 1. product_variable_combination_stock
			// 2. product_shop_variable_combination_stock

			$update_array						= array();
			$update_array['web_stock']			= 0;
			$update_array['actual_stock']		= 0;
			$db->update_from_array($update_array,'product_variable_combination_stock',array('products_product_id'=>$prodid));

			$update_array						= array();
			$update_array['shop_stock']			= 0;
			$db->update_from_array($update_array,'product_shop_variable_combination_stock',array('products_product_id'=>$prodid));


		}
		// If stock exists then reset the status of allow preorder for current product
		if ($stock_exists)
		{
			$update_array									= array();
			$update_array['product_preorder_allowed']		= 'N';
			$update_array['product_total_preorder_allowed']	= 0;
			$update_array['product_instock_date']			= '0000-00-00';
			$db->update_from_array($update_array,'products',array('product_id'=>$prodid));
		}
		else // case if stock does not exists
		{
			if($row_prod['product_hide_on_nostock']=='Y' and $row_prod['product_alloworder_notinstock']=='N') // case if product is to be made hidden when hide on no stock is set to Y and allow stock even if stock in not there option to 'N'
			{
				$sql_update = "UPDATE 
											products 
										SET 
											product_hide = 'Y' 
										WHERE 
											product_id = $prodid 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($sql_update);
			}
		}
	}
}
function deleteOrder_on_failure($order_id)
{
	global $db,$ecom_siteid,$settings_Arr;
	global $ecom_selfhttp;
	// Get the payment type and payment method for current order
	$sql_pay = "SELECT order_paymenttype,order_paymentmethod,order_paystatus,order_status   
					FROM 
						orders 
					WHERE 
						order_id = $order_id 
					LIMIT 
						1";
	$ret_pay = $db->query($sql_pay);
	if ($db->num_rows($ret_pay))
	{
		$row_pay = $db->fetch_array($ret_pay);
		// If payment method exists for site they take that key as order status otherwise take the payment type key as order status
		if (trim($row_pay['order_paymentmethod']) !='') 
			$p_status = trim($row_pay['order_paymentmethod']);
		else
			$p_status = trim($row_pay['order_paymenttype']);
	}
	if(trim($row_pay['order_paymenttype'])=='4min_finance')
	{
		/* Check whether atleast one status update happend from finance gateway for this order */
		$sql_updchk = "SELECT log_id FROM order_payment_4minute_finance_log WHERE orders_order_id = $order_id LIMIT 1";
		$ret_updchk = $db->query($sql_updchk);
		
		if($db->num_rows($ret_updchk)==0)
		{
			// Updating the orders table with the payment status and order status
			$sql_upd = "UPDATE orders 
							SET 
								order_paystatus = '".$p_status."', 
								order_status='NOT_AUTH' 
							WHERE 
								order_id =$order_id 
							LIMIT 
								1";
			$db->query($sql_upd);

		}	
	}
	else
	{
		// Updating the orders table with the payment status and order status
		$sql_upd = "UPDATE orders 
						SET 
							order_paystatus = '".$p_status."', 
							order_status='NOT_AUTH' 
						WHERE 
							order_id =$order_id 
						LIMIT 
							1";
		$db->query($sql_upd);
	}	
	// Check whether there exists any products in current order linked with price promise
	$sql_orderdet = "SELECT  orderdet_id, order_prom_id 
						FROM 
							order_details 
						WHERE 
							orders_order_id = $order_id 
							AND order_prom_id <> 0";
	$ret_orderdet = $db->query($sql_orderdet);
	if($db->num_rows($ret_orderdet))
	{
		while ($row_orderdet = $db->fetch_array($ret_orderdet))
		{
			$cur_promid = $row_orderdet['order_prom_id'];
			// get the used count of current price promise entry
			$sql_price = "SELECT  prom_used,  prom_max_usage 
							FROM 
								pricepromise 
							WHERE 
								prom_id = $cur_promid 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_price = $db->query($sql_price);
			if ($db->num_rows($ret_price))
			{
				$row_price = $db->fetch_array($ret_price);
				if($row_price['prom_used']>0)
				{
					// Decrementing the price promise usage count by 1
					$sql_update = "UPDATE pricepromise 
										SET 
											prom_used = prom_used-1 
										WHERE 
											prom_id = $cur_promid 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$db->query($sql_update);
				}	
			}
		}
	}
/*
	
	// ###########################################################################################
	// Delete from cheque details
	$db->delete_id($order_id,'orders_order_id','order_cheque_details');
	// ###########################################################################################
	// Delete from delivery data
	$db->delete_id($order_id,'orders_order_id','order_delivery_data');
	// ###########################################################################################
	// Delete from messages
	$db->delete_id($order_id,'orders_order_id','order_details_messages');
	// ###########################################################################################
	// Delete from variables
	$db->delete_id($order_id,'orders_order_id','order_details_variables');
	// ###########################################################################################
	// Delete from dynamicvalues
	$db->delete_id($order_id,'orders_order_id','order_dynamicvalues');
	// ###########################################################################################
	// Delete from emails
	$db->delete_id($order_id,'orders_order_id','order_emails');
	// ###########################################################################################
	// Delete from giftwrap
	$db->delete_id($order_id,'orders_order_id','order_giftwrap_details');
	// ###########################################################################################
	// Delete from payment
	$db->delete_id($order_id,'orders_order_id','order_payment_main');
	// ###########################################################################################
	// Delete from promotional code
	$db->delete_id($order_id,'orders_order_id','order_promotional_code');
	// ###########################################################################################
	// Delete from tax
	$db->delete_id($order_id,'orders_order_id','order_tax_details');
	// ###########################################################################################
	// Delete from promotional code track
	// $db->delete_id($order_id,'orders_order_id','order_promotionalcode_track');
// ###########################################################################################
// Delete from order voucher

// Get the voucher id related to current order
$sql_voucher = "SELECT voucher_id
					FROM
						order_voucher
					WHERE
						orders_order_id=$order_id
					LIMIT
						1";
$ret_voucher = $db->query($sql_voucher);
if ($db->num_rows($ret_voucher))
{
	$row_voucher = $db->fetch_array($ret_voucher);
	// Check whether this voucher still exists in site
	$sql_gift = "SELECT voucher_id,voucher_usage
					FROM
						gift_vouchers
					WHERE
						sites_site_id = $ecom_siteid
						AND voucher_id=".$row_voucher['voucher_id']."
					LIMIT
						1";
	$ret_gift = $db->query($sql_gift);
	if ($db->num_rows($ret_gift))
	{
		$row_gift = $db->fetch_array($ret_gift);
		if ($row_gift['voucher_usage']>0) // if the value of usage is >0 then decrement it by 1
		{
			$update_sql = "UPDATE gift_vouchers
						SET
							voucher_usage = voucher_usage - 1
						WHERE
							sites_site_id = $ecom_siteid
							AND voucher_id = ".$row_gift['voucher_id']."
						LIMIT
							1";
			$db->query($update_sql);

		}
	}
}

// Get the promotional_code_code_id related to current order
$sql_prom = "SELECT promotional_code_code_id 
					FROM
						order_promotionalcode_track
					WHERE
						orders_order_id=$order_id
					LIMIT
						1";
$ret_prom = $db->query($sql_prom);
if ($db->num_rows($ret_prom))
{
	$row_prom = $db->fetch_array($ret_prom);
	// Check whether this code still exists and if limit is to be decremented
	$sql_pc = "SELECT code_id, code_unlimit_check, code_limit, code_usedlimit 
					FROM
						promotional_code
					WHERE
						sites_site_id = $ecom_siteid
						AND code_id=".$row_prom['promotional_code_code_id']."
					LIMIT
						1";
	$ret_pc = $db->query($sql_pc);
	if ($db->num_rows($ret_pc))
	{
		$row_pc = $db->fetch_array($ret_pc);
		if ($row_pc['code_unlimit_check']==0) // if not unlimited
		{
			$update_sql = "UPDATE promotional_code
									SET
										code_usedlimit = code_usedlimit - 1
									WHERE
										sites_site_id = $ecom_siteid 
										AND code_id = ".$row_prom['promotional_code_code_id']." 
										AND code_usedlimit > 0
										AND code_limit >= code_usedlimit
									LIMIT
										1";
			$db->query($update_sql);

		}
	}
}
// ###########################################################################################
// Delete from promotional code track
 $db->delete_id($order_id,'orders_order_id','order_promotionalcode_track');
// ###########################################################################################

$db->delete_id($order_id,'orders_order_id','order_voucher');
// ###########################################################################################

// Delete from order details
// ###########################################################################################
// Get the list of product existing in current order
$sql_prods = "SELECT products_product_id,order_preorder,order_stock_combination_id,order_qty
				FROM
					order_details
				WHERE
					orders_order_id = $order_id";
$ret_prods = $db->query($sql_prods);
$prodext_arr = array(-1); // initializing the array to hold the product which are in preorder
if ($db->num_rows($ret_prods))
{
	while ($row_prods = $db->fetch_array($ret_prods))
	{
		// Check whether the product already exists
		$sql_prodcheck = "SELECT product_id,product_preorder_allowed 
							FROM
								products
							WHERE
								product_id = ".$row_prods['products_product_id']."
							LIMIT
								1";
		$ret_prodcheck = $db->query($sql_prodcheck);
		if ($db->num_rows($ret_prodcheck))
		{
			$row_prodcheck 	= $db->fetch_array($ret_prodcheck);
			$update_done 	= false;
			// Check whether the current product was in preorder while placing the order and also whether it is in preorder now also
			if($row_prods['order_preorder']=='N' and $row_prodcheck['product_preorder_allowed']=='N')
			{
				// Check whether stock is maintained for the site.. Stock will be returned if stock is maintained for the product
				if ($settings_Arr['product_maintainstock']==1)
				{
					// check whether combination id exists
					if ($row_prods['order_stock_combination_id']!=0)
					{
						if($row_prodcheck['product_variablestock_allowed']=='Y') // check whether the product is still in variable stock
						{
							// case of variable stock
							// Check whether the current combination still exists
							$sql_comb = "SELECT comb_id
											FROM
												product_variable_combination_stock
											WHERE
												comb_id = ".$row_prods['order_stock_combination_id']."
											LIMIT
												1";
							$ret_comb = $db->query($sql_comb);
							if ($db->num_rows($ret_comb))
							{
								// Updating the webstock and the actual stock for the respective combination so as to
								// add back the number of products whose combination stock has been updated
								$sql_update = "UPDATE
													product_variable_combination_stock
												SET
													web_stock 		= web_stock + ".$row_prods['order_qty'].",
													actual_stock	= actual_stock + ".$row_prods['order_qty']."
												WHERE
													comb_id = ".$row_prods['order_stock_combination_id']."
												LIMIT
													1";
								$db->query($sql_update);
								$update_done = true;
							}
						}
					}
					else
					{
						if($row_prodcheck['product_variablestock_allowed']=='N') // check whether the product is still in fixed stock
						{
							// case of fixed stock
							$sql_update		= "UPDATE
													products
												SET
													product_webstock = product_webstock + ".$row_prods['order_qty'].",
													product_actualstock = product_actualstock + ".$row_prods['order_qty']."
												WHERE
													product_id = ".$row_prods['products_product_id']."
												LIMIT
													1";
							$db->query($sql_update);
							$update_done = true;
						}
					}
					if ($update_done==true) // if the stock update is successfull for current product
					{
						// change the setting for preorder, preorder date and total preorder
						$update_array									= array();
						$update_array['product_preorder_allowed']		= 'N';
						$update_array['product_total_preorder_allowed']	= 0;
						$update_array['product_instock_date']			= '0000-00-00';
						$db->update_from_array($update_array,'products',array('product_id'=>$row_prods['products_product_id']));
					}
				}	
			}	
			elseif($row_prods['order_preorder']=='Y' and $row_prodcheck['product_preorder_allowed']=='Y')
			{
				// Check whether total preorder allowed in already incremented for this product 
				if(!in_array($row_prods['products_product_id'],$prodext_arr))
				{
					// Case if product was in preorder while ordering and also it is in preorder itself now. then increment the total number or preoreder allowed by 1
					$sql_update = "UPDATE 
										products 
									SET 
										product_total_preorder_allowed=product_total_preorder_allowed + 1 
									WHERE 
										product_id = ".$row_prods['products_product_id']." 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$db->query($sql_update);
					$prodext_arr[] = $row_prods['products_product_id'];
				}	
			}
		}
	}
}

$db->delete_id($order_id,'orders_order_id','order_details');

// ###########################################################################################
// Delete from orders
// ###########################################################################################

// Check whether customer id exits in order and if bonus point discount exists
$sql_order = "SELECT customers_customer_id,order_bonuspoints_used
				FROM
					orders
				WHERE
					order_id = $order_id
					AND customers_customer_id <> 0
					AND order_bonuspoints_used > 0
				LIMIT
					1";
$ret_order = $db->query($sql_order);
if ($db->num_rows($ret_order))
{
	$row_order = $db->fetch_array($ret_order);
	// returning back the bonus points to respective customer
	$update_sql = "UPDATE customers
					SET
						customer_bonus = customer_bonus + ".$row_order['order_bonuspoints_used']."
					WHERE
						customer_id = ".$row_order['customers_customer_id']."
					LIMIT
						1";
	$db->query($update_sql);

}

// Delete from order_product_downloadable_products
$db->delete_id($order_id,'orders_order_id','order_product_downloadable_products');
// Delete from order_payonaccount_details
$db->delete_id($order_id,'orders_order_id','order_payonaccount_details');

// Delete from orders table
$db->delete_id($order_id,'order_id','orders');
*/
}

function deleteVoucher_on_failure($voucher_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// Get the payment type and payment method for current gift voucher
	$sql_pay = "SELECT voucher_paymenttype,voucher_paymentmethod 
					FROM 
						gift_vouchers 
					WHERE 
						voucher_id = $voucher_id  
					LIMIT 
						1";
	$ret_pay = $db->query($sql_pay);
	if ($db->num_rows($ret_pay))
	{
		$row_pay = $db->fetch_array($ret_pay);
		// If payment method exists for site they take that key as order status otherwise take the payment type key as order status
		if (trim($row_pay['voucher_paymentmethod']) !='') 
			$p_status = trim($row_pay['voucher_paymentmethod']);
		else
			$p_status = trim($row_pay['voucher_paymenttype']);
	}
	$sql_upd = "UPDATE gift_vouchers 
							SET 
								voucher_paystatus = '" . $p_status . "', 
								voucher_incomplete = 1 
							WHERE 
								voucher_id =$voucher_id 
							LIMIT 
								1";
	$db->query($sql_upd);
	/*// Delete from voucher payment details table
	$db->delete_id($voucher_id,'gift_vouchers_voucher_id','gift_vouchers_payment');
	// Delete from voucher customer details table
	$db->delete_id($voucher_id,'voucher_id','gift_vouchers_customer');
	// Delete from voucher payment details table
	$db->delete_id($voucher_id,'gift_vouchers_voucher_id','gift_voucher_emails');
	// Delete from voucher table
	$db->delete_id($voucher_id,'voucher_id','gift_vouchers');*/

}
function deletePayonAccount_on_failure($pay_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// Get the payment type and payment method for current gift payon account payment
	$sql_pay = "SELECT pay_paymenttype,pay_paymentmethod  
					FROM 
						order_payonaccount_pending_details  
					WHERE 
						pendingpay_id = $pay_id   
					LIMIT 
						1";
	$ret_pay = $db->query($sql_pay);
	if ($db->num_rows($ret_pay))
	{
		$row_pay = $db->fetch_array($ret_pay);
		// If payment method exists for site they take that key as order status otherwise take the payment type key as order status
		if (trim($row_pay['pay_paymentmethod']) !='') 
			$p_status = trim($row_pay['pay_paymentmethod']);
		else
			$p_status = trim($row_pay['pay_paymenttype']);
	}
	$sql_upd = "UPDATE order_payonaccount_pending_details  
				SET 
					pay_paystatus = '" . $p_status . "', 
					pay_incomplete = 1 
				WHERE 
					pendingpay_id =$pay_id 
				LIMIT 
					1";
	$db->query($sql_upd);
	/*// Delete from payonaccount payment details table
	$db->delete_id($pay_id,'order_payonaccount_pendingpay_id','order_payonaccount_pending_details_payment');
	// Delete from order_payonaccount_pending_details table
	$db->delete_id($pay_id,'pendingpay_id','order_payonaccount_pending_details');
	*/
}
function delete_body_cache()			// Function to delete cache for body
{
	global $image_path,$ecom_siteid;
	global $ecom_selfhttp;
	$cache_path = $image_path.'/cache/body/normal_'.$ecom_siteid.'.txt';
	if (file_exists($cache_path))		// Check whether file exists
	{
		unlink($cache_path);			// Check whether file exists
	}
}

/*
	Function to get the name of payment method
*/
function getpaymentmethod_Name($key)
{
	global $db,$ecom_siteid,$ecom_common_settings;
	global $ecom_selfhttp;
	if ($key)
	{
		/*$sql = "SELECT paymethod_id,paymethod_name
				FROM
					payment_methods
				WHERE
					paymethod_key = '".$key."'
				LIMIT
					1";
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			// Check whether caption is set for current 
			return $row['paymethod_name'];
		}*/
		//echo "<br><br>here ".$ecom_common_settings['paymethodKey'][$key]['paymethod_name'];
		return $ecom_common_settings['paymethodKey'][$key]['paymethod_name'];
	}
}
function getpaymentstatus_Name($key)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	switch($key)
	{
		case 'pay_on_phone':
		case 'pay_on_account':
		case 'cash_on_delivery':
		case 'invoice':
		case 'cheque':
		case 'SELF':
			$caption = 'Not Paid';
		break;
		case 'HSBC':
		case 'GOOGLE_CHECKOUT':
		case 'WORLD_PAY':
		case 'PAYPAL_EXPRESS':
		case 'PAYPALPRO':
		case 'NOCHEX':
		case 'REALEX':
		case 'ABLE2BUY':
		case 'PROTX_VSP':
		case 'FIDELITY':
		case 'BARCLAYCARD':
		case 'VERIFONE':
			$caption = 'Check '.getpaymentmethod_Name($key);
		break;
		case 'Pay_Failed':
			$caption = 'Payment Failed';
		break;
		case 'Paid':
			$caption = 'Paid';
		break;
		case 'REFUNDED':
			$caption = 'Refunded';
		break;
		case 'DEFERRED':
			$caption = 'Deferred';
		break;
		case 'PREAUTH':
			$caption = 'Preauth';
		break;
		case 'AUTHENTICATE':
			$caption = 'Authenticate';
		break;
		case 'ABORTED':
			$caption = 'Deferred Aborted';
		break;
		case 'CANCELLED':
			$caption = 'Authorise Cancelled';
		break;
		case 'free':
			$caption = 'Free';
		break;
		case 'FRAUD_REVIEW':
		$caption = 'Fraud rule review check';
		break;
		case '3D_SEC_CHECK':
		$caption = 'Redirected for 3D Secure Password';
		break;
	};
	return $caption;
}
function getorderstatus_Name($key)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	switch($key)
	{
		case 'NEW':
			$caption = 'Unviewed';
		break;
		case 'PENDING':
			$caption = 'Pending';
		break;
		case 'DESPATCHED':
			$caption = 'Despatched';
		break;
		case 'ONHOLD':
			$caption = 'On Hold';
		break;
		case 'BACK':
			$caption = 'Back Order';
		break;
		case 'CANCELLED':
			$caption = 'Cancelled';
		break;

	};
	return $caption;
}
function dateFormat($passdt, $type = "default") {
	global $ecom_selfhttp;
	#############fromat of displaying date '4:21pm -Mar 11 Sat'
	//$arow[orddt] in yyyy-mm-dd hh:mm:sec format
	$sp_dt1=explode(" ",$passdt);
	$sp_dt = explode("-",$sp_dt1[0]);
	$rt_year=intval($sp_dt[0]);
	$rt_month=(integer)$sp_dt[1];
	$rt_day=(integer)$sp_dt[2];
	$sp_dt2 = explode(":",$sp_dt1[1]);
	$rt_hr = (integer)$sp_dt2[0];
	$rt_min = (integer)$sp_dt2[1];
	$rt_sec = (integer)$sp_dt2[2];
	$unixstamp=mktime ($rt_hr,$rt_min,$rt_sec,$rt_month,$rt_day,$rt_year);
	// $dtdisp=@date("h :i a"." - "."M d Y D",$unixstamp);
	if($type == 'time') {
		$dtdisp = @date("h :i a",$unixstamp);
	}
	elseif($type == 'datetime'){
		$dtdisp = @date("d-M-Y",$unixstamp)."&nbsp;".@date("h:i a",$unixstamp);
	}
	elseif($type == 'datetime_break'){
		$dtdisp = @date("d-M-Y",$unixstamp)."<br/>".@date("h:i a",$unixstamp);
	}
	else {
		$dtdisp = @date("d-M-Y",$unixstamp);
	}
	return $dtdisp;
	//int mktime (int hour, int minute, int second, int month, int day, int year [, int is_dst])
}
/*
Function to get the name of payment type
*/
function getpaymenttype_Name($key)
{
	global $db,$ecom_siteid,$ecom_common_settings;
	global $ecom_selfhttp;
	if ($key)
	{
		/*$sql = "SELECT paytype_name
				FROM
					payment_types
				WHERE
					paytype_code = '".$key."'
				LIMIT
					1";
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			return $row['paytype_name'];
		}*/
		return $ecom_common_settings['paytypeCode'][$key]['paytype_name'];
	}
}
function getpaymenttype_Name_original($key)
{
	global $db,$ecom_siteid,$ecom_common_settings;
	global $ecom_selfhttp;
	if ($key)
	{
		$sql = "SELECT paytype_name
				FROM
					payment_types
				WHERE
					paytype_code = '".$key."'
				LIMIT
					1";
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			return $row['paytype_name'];
		}
		//return $ecom_common_settings['paytypeCode'][$key]['paytype_name'];
	}
}
function getConsoleUserName($userid)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$sql_usr = "SELECT user_title,user_fname,user_lname,sites_site_id
					FROM
						sites_users_7584
					WHERE
						user_id = $userid
					LIMIT
						1";
	$ret_usr = $db->query($sql_usr);
	if($db->num_rows($ret_usr))
	{
		$row_usr = $db->fetch_array($ret_usr);
		if ($row_usr['sites_site_id']==0)// case of super admin
		$cap = stripslashes($row_usr['user_fname'])." ".stripslashes($row_usr['user_lname']);
		else
		$cap = stripslashes($row_usr['user_title']).".".stripslashes($row_usr['user_fname'])." ".stripslashes($row_usr['user_lname']);
	}
	return $cap;
}
/*
/* Function to get the display logic for product details to be included in the mail
function get_ProductsInOrdersForMail($order_id,$row_ords,$detail_arr='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	$totals_req		= true;
	$qty_req 		= false;
	$det_arr 		= $detail_arr['prods'];
	$detqty_arr		= $detail_arr['qtys'];
	if (is_array($det_arr)) // Check whether only selected products are to be displayed
	{
		if(count($det_arr))
		{
			$additional_condition 	= " AND orderdet_id IN (".implode(",",$det_arr).") ";
			$totals_req				= false;
		}
	}
	if (is_array($detqty_arr)) // check whether quantity array exists
	{
		if(count($detqty_arr))
		{
			$qty_req				= true;
		}
	}

	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	// ##############################################################################
	// Product Details
	// ##############################################################################
	$prod_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	$sql_prods  = "SELECT orderdet_id,product_name,order_qty,product_soldprice,order_retailprice,order_discount,
							order_discount_type,order_rowtotal
					FROM
						order_details
					WHERE
						orders_order_id = $order_id
						$additional_condition";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
		if($totals_req==true)
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%"><strong>'.$Captions_arr['CART']['CART_ITEM'].'</strong></td>
								<td align="left" width="20%"><strong>'.$Captions_arr['CART']['CART_PRICE'].'</strong></td>
								<td align="left" width="15%"><strong>'.$Captions_arr['CART']['CART_DISCOUNT'].'</strong></td>
								<td align="left" width="25%"><strong>'.$Captions_arr['CART']['CART_QTY'].'</strong></td>
								<td align="left" width="25%"><strong>'.$Captions_arr['CART']['CART_TOTAL'].'</strong></td>
							</tr>';

		}
		elseif ($qty_req==true)
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%"><strong>'.$Captions_arr['CART']['CART_ITEM'].'</strong></td>
								<td align="left" width="25%"><strong>'.$Captions_arr['CART']['CART_QTY'].'</strong></td>
								<td align="left" colspan="3"><strong>&nbsp;</strong></td>
							</tr>';
		}
		else
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%"><strong>'.$Captions_arr['CART']['CART_ITEM'].'</strong></td>
								<td align="left" colspan="4"><strong>&nbsp;</strong></td>
							</tr>';
		}
		while ($row_prods = $db->fetch_array($ret_prods))
		{
			$qty = ($totals_req)?stripslashes($row_prods['order_qty']):$detqty_arr[$row_prods['orderdet_id']];
			if($totals_req==true)
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%">'.stripslashes($row_prods['product_name']).'</td>
								<td align="left" width="15%">'.print_price_selected_currency($row_prods['product_soldprice'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								<td align="left" width="20%">'.print_price_selected_currency($row_prods['order_discount'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								<td align="left" width="15%">'.$qty.'</td>
								<td align="right" width="20%">'.print_price_selected_currency($row_prods['order_rowtotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}
			elseif ($qty_req==true)
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%">'.stripslashes($row_prods['product_name']).'</td>
								<td align="left" width="15%">'.$qty.'</td>
								<td align="left" colspan="3">&nbsp;</td>
								</tr>';
			}
			else
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%">'.stripslashes($row_prods['product_name']).'</td>
								<td align="left" colspan="4">&nbsp;</td>
								</tr>';
			}
			// Check whether any variables exists for current product in order_details_variables
			$sql_var = "SELECT var_name,var_value
							FROM
								order_details_variables
							WHERE
								orders_order_id = $order_id
								AND order_details_orderdet_id =".$row_prods['orderdet_id'];
			$ret_var = $db->query($sql_var);
			if ($db->num_rows($ret_var))
			{
				while ($row_var = $db->fetch_array($ret_var))
				{
					$prod_str	.= '<tr>
									<td align="left" colspan="5" style="padding-left:10px"><strong>'.stripslashes($row_var['var_name']).':</strong> '.stripslashes($row_var['var_value']).'</td>
									</tr>';
				}
			}
			// Check whether any variables messages exists for current product in order_details_messages
			$sql_msg = "SELECT message_caption,message_value
							FROM
								order_details_messages
							WHERE
								orders_order_id = $order_id
								AND order_details_orderdet_id =".$row_prods['orderdet_id'];
			$ret_msg = $db->query($sql_msg);
			if ($db->num_rows($ret_msg))
			{
				while ($row_msg = $db->fetch_array($ret_msg))
				{
					$prod_str	.= '<tr>
									<td align="left" colspan="5" style="padding-left:10px"><strong>'.stripslashes($row_msg['message_caption']).':</strong> '.stripslashes($row_msg['message_value']).'</td>
									</tr>';
				}
			}
		}
		if($totals_req==true)
		{
			// ##################################################################################
			// Building order totals
			// ##################################################################################
			// subtotal
			$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2"><strong>'.$Captions_arr['CART']['CART_TOTPRICE'].'</strong></td>
									<td align="right" colspan="3">'.print_price_selected_currency($row_ords['order_subtotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			// giftwrap total and delivery type total and tax total
			if($row_ords['order_giftwraptotal']>0)
			{
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2"><strong>Gift Wrap Total</strong></td>
									<td align="right" width="50%" colspan="3">'.print_price_selected_currency($row_ords['order_giftwraptotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
			}
			if($row_ords['order_deliverytotal']>0)
			{
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2"><strong>Delivery Total</strong></td>
									<td align="right" width="50%" colspan="3">'.print_price_selected_currency($row_ords['order_deliverytotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}
			if($row_ords['order_tax_total']>0)
			{
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2"><strong>Total Tax</strong></td>
									<td align="right" width="50%" colspan="3">'.print_price_selected_currency($row_ords['order_tax_total'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}

			// Customer / Corporate discount
			if ($row_ords['order_customer_discount_value']>0)
			{
				if ($row_ords['order_customer_or_corporate_disc']=='CUST')
				{
					if($row_ords['order_customer_discount_type']=='Disc_Group')
					$caption = 'Customer Group Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					else
					$caption = 'Customer Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					$caption_val = $row_ords['order_customer_discount_value'];
					$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="2"><strong>'.$caption.'</strong></td>
										<td align="right" colspan="3">'.print_price_selected_currency($caption_val,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
				}
				else // case of corporate discount
				{
					$caption = 'Corporate Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					$caption_val = $row_ords['order_customer_discount_value'];
					$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="2"><strong>'.$caption.'</strong></td>
										<td align="right" colspan="3">'.print_price_selected_currency($caption_val,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
				}
			}

			if($row_ords['gift_vouchers_voucher_id'])
			{
				// Get the gift voucher details
				$sql_voucher = "SELECT voucher_value_used
									FROM
										order_voucher
									WHERE
										orders_order_id = $order_id
									LIMIT
										1";
				$ret_voucher = $db->query($sql_voucher);
				if ($db->num_rows($ret_voucher))
				{
					$row_voucher 	= $db->fetch_array($ret_voucher);
					$prod_str	.= '<tr><td align="left" width="50%" colspan="2"><strong>Gift Voucher Discount</strong></td><td align="left" width="50%" colspan="3">'.print_price_selected_currency($row_voucher['voucher_value_used'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
				}

			}
			elseif($row_ords['promotional_code_code_id'])
			{
				// Get the promotional code details
				$sql_prom = "SELECT code_number,code_lessval,code_type
									FROM
										order_promotional_code
									WHERE
										orders_order_id = $order_id
									LIMIT
										1";
				$ret_prom = $db->query($sql_prom);
				if ($db->num_rows($ret_prom))
				{
					$row_prom 	= $db->fetch_array($ret_prom);
					if ($row_prom['code_type']!='product') // show only if not of type 'product' if type is product discount will be shown with product listing
					{
						$prod_str	.= '<tr><td align="right" width="50%" colspan="2"><strong>Promotional Code Discount</strong></td><td align="right" width="50%" colspan="3">'.print_price_selected_currency($row_prom['code_lessval'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
					}
				}

			}
			if($row_ords['order_bonuspoint_discount']>0)
			{
				$prod_str = '<tr>
								<td align="right" width="50%" colspan="2"><strong>Bonus Points Discount</strong></td>
								<td align="right" width="50%" colspan="3">'.print_price_selected_currency($row_ords['order_bonuspoint_discount'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			}
			// Total Final Cost
			$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2"><strong>Grand Total</strong></td>
								<td align="right" colspan="3">'.print_price_selected_currency($row_ords['order_totalprice'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			// Check whether product deposit exists
			if($row_ords['order_deposit_amt']>0)
			{
				$amt_payable_now = $row_ords['order_totalprice'] - $row_ords['order_deposit_amt'];
				$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2"><strong>Less Product Deposit Amount</strong></td>
								<td align="right" colspan="3">'.print_price_selected_currency($row_ords['order_deposit_amt'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
				$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2"><strong>Amount Payable Now</strong></td>
								<td align="right" colspan="3">'.print_price_selected_currency($amt_payable_now,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			}
		}
		$prod_str		.= '</table>';
	}
	return $prod_str;
}
*/

/* Function to retrieve payonaccount cart values*/
function payonaccount_CartDetails($sess_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$ret_arr = array();
	$sql_cart = "SELECT session_id, sites_site_id, pay_date, pay_amount, pay_paytype, pay_paymethod, pay_error_msg, pay_unique_key,pay_additional_details  
								FROM 
									payonaccount_cartvalues 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND session_id = '".$sess_id."'  
								LIMIT 
									1";
	$ret_cart = $db->query($sql_cart);	
	if ($db->num_rows($ret_cart))
	{
		$row_cart = $db->fetch_array($ret_cart);
		return $row_cart;
	}
	else
		return $ret_arr;
}
/* Function to save the details of payonaccount in payonaccount_cartvalues table */
function Save_Payonaccount_cart($coming_from_paypal=0)
{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_common_settings;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	if($coming_from_paypal==1) // case if payment method is paypal
        {  
         
           // Get the payment type id of credit card and also the payment method id of paypal
           $_REQUEST['payonaccount_paytype']   = $ecom_common_settings['paytypeCode']['credit_card']['paytype_id'];
           $_REQUEST['payonaccount_paymethod'] = 'PAYPAL_EXPRESS_'.$ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_id'];
        }
	// Check whether any record exists for payon account  in current session
	$sql_check = "SELECT session_id
					FROM
						payonaccount_cartvalues 
					WHERE
						session_id='".$sess_id."'
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_check = $db->query($sql_check);
	if ($db->num_rows($ret_check)) // case entry already exists
	{
		$update_array						= array();
		$update_array['pay_date']				= 'now()';
		$update_array['pay_amount']				= trim($_REQUEST['pay_amt']);
		$update_array['pay_additional_details']			= add_slash(trim($_REQUEST['pay_additional_details']));
		$update_array['pay_paytype']				= add_slash($_REQUEST['payonaccount_paytype']);
		$update_array['pay_unique_key']				= add_slash($_REQUEST['payonaccount_unique_key']);
		if ($_REQUEST['payonaccount_paymethod']!='')
		{
                    $pmethod						= explode('_',$_REQUEST['payonaccount_paymethod']);
                    $update_array['pay_paymethod']			= $pmethod[count($pmethod)-1];
		}
		else
                    $update_array['pay_paymethod']		        = 0;
		$db->update_from_array($update_array,'payonaccount_cartvalues',array('session_id'=>$sess_id));
	}
	else // case entry does not exists
	{	
		$insert_array											= array();
		$insert_array['pay_date']							= 'now()';
		$insert_array['session_id']							= add_slash($sess_id);
		$insert_array['sites_site_id']						= $ecom_siteid;
		$insert_array['pay_date']							= 'now()';
		$insert_array['pay_amount']						= trim($_REQUEST['pay_amt']);
		$insert_array['pay_additional_details']			= add_slash(trim($_REQUEST['pay_additional_details']));
		$insert_array['pay_paytype']						= add_slash($_REQUEST['payonaccount_paytype']);
		$insert_array['pay_unique_key']					= add_slash($_REQUEST['payonaccount_unique_key']);
		if ($_REQUEST['payonaccount_paymethod']!='')
		{
			$pmethod									= explode('_',$_REQUEST['payonaccount_paymethod']);
			$insert_array['pay_paymethod']				= $pmethod[count($pmethod)-1];
		}
		else
			$insert_array['pay_paymethod']		= 0;
		$db->insert_from_array($insert_array,'payonaccount_cartvalues');
	}
}
/* Function to save the actual payonaccount details */
function Save_PayonAccountDetails()
{
	global $db,$ecom_siteid,$sitesel_curr,$default_Currency_arr,$Settings_arr,$ecom_hostname;
	global $ecom_selfhttp;
	$customer_id 		= get_session_var("ecom_login_customer"); // get the id of current customer from session
	// Get the voucher details from payonaccount_cartvalues table
	$sess_id 	= Get_session_Id_from();
	$sql_cart	= "SELECT session_id, sites_site_id, pay_date, pay_amount, pay_paytype, pay_paymethod, pay_error_msg, pay_unique_key,pay_additional_details  
                            FROM
                                            payonaccount_cartvalues 
                            WHERE
                                            session_id ='".$sess_id."'
                                            AND sites_site_id = $ecom_siteid
                            LIMIT
                                    1";
	$ret_cart	= $db->query($sql_cart);
        
	if ($db->num_rows($ret_cart))
	{
		$row_cart 			= $db->fetch_array($ret_cart);
		// Check whether any entry already exists with the current value of unique_key in order_payonaccount_pending_details table for current site
		$sql_check = "SELECT pendingpay_id 
						FROM
							order_payonaccount_pending_details
						WHERE
							sites_site_id = $ecom_siteid
							AND pay_unique_key='".$row_cart['pay_unique_key']."'
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // if already exists .. then take user back to payment page with an error msg
		{
			echo "
				<script type='text/javascript'>
					window.location = '".$ecom_selfhttp.$ecom_hostname."/payonaccountpayment.html?rt=1';
				</script>
				";
			exit;
		}
		// Currency

		if($sitesel_curr != $default_Currency_arr['currency_id'])
		{
			$sql_curr  = "SELECT curr_rate,curr_sign_char,curr_code,curr_numeric_code,curr_margin 
							FROM
								general_settings_site_currency
							WHERE
									currency_id=$sitesel_curr
									AND sites_site_id=$ecom_siteid 
								LIMIT 
									1";
			$ret_curr  = $db->query($sql_curr);
			if($db->num_rows($ret_curr))
			{
				$row_curr  			= $db->fetch_array($ret_curr);
				$curr_rate 				= ($row_curr['curr_rate']+$row_curr['curr_margin']);
				$curr_sign 				= $row_curr['curr_sign_char'];
				$curr_code 			= $row_curr['curr_code'];
				$curr_num_code 	= $row_curr['curr_numeric_code'];
			}
		}
		else
		{
			$curr_rate 				= 1;// + $default_Currency_arr['curr_margin']);
			$curr_sign 				= $default_Currency_arr['curr_sign_char'];
			$curr_code 			= $default_Currency_arr['curr_code'];
			$curr_num_code 	= $default_Currency_arr['curr_numeric_code'];
		}
		$pay_currency_rate 	= $curr_rate;
		$pay_currency_code 	= $curr_code;
		$pay_currency_sign 	= $curr_sign;
		$pay_currency_ncode	= $curr_num_code;
		
		// Building the voucher number now
		$insert_array											= array();
		$insert_array['pay_date']							= 'now()';
		$insert_array['sites_site_id']						= $ecom_siteid;
		$insert_array['customers_customer_id']		= $customer_id;
		$insert_array['pay_details']						= 'Payment - Thank You';
		$insert_array['pay_additional_details']			= addslashes(stripslashes($row_cart['pay_additional_details']));
		$insert_array['pay_amount']						= convert_price_default_currency($row_cart['pay_amount'],$pay_currency_rate);
		$insert_array['pay_transaction_type']			= 'C';
		// Check whether payment type exists
		if ($row_cart['pay_paytype']!=0)
		{
			// Get the key for payment type
			$sql_ptype = "SELECT paytype_code
							FROM
								payment_types
							WHERE
								paytype_id = ".$row_cart['pay_paytype']."
							LIMIT
								1";
			$ret_ptype = $db->query($sql_ptype);
			if ($db->num_rows($ret_ptype))
			{
				$row_ptype 	= $db->fetch_array($ret_ptype);
				$ptype		= $row_ptype['paytype_code'];
			}
		}
		else
			$ptype = '';
		$insert_array['pay_paymenttype']	= $ptype;

		// Check whether payment method exists
		if ($row_cart['pay_paymethod']!=0)
		{
			// Get the key for payment method
			$sql_pmethod = "SELECT paymethod_key,paymethod_takecarddetails
							FROM
								payment_methods
							WHERE
								paymethod_id = ".$row_cart['pay_paymethod']."
							LIMIT
								1";
			$ret_pmethod = $db->query($sql_pmethod);
			if ($db->num_rows($ret_pmethod))
			{
				$row_pmethod 	= $db->fetch_array($ret_pmethod);
				$pmethod			= $row_pmethod['paymethod_key'];
				$ptake_card		= $row_pmethod['paymethod_takecarddetails'];
			}
		}
		else
		{
			$pmethod 	= '';
			$ptake_card = '';
		}

		$insert_array['pay_paymentmethod']				= $pmethod;
		$insert_array['pay_curr_rate']						= $pay_currency_rate;
		$insert_array['pay_curr_code']						= $pay_currency_code;
		$insert_array['pay_curr_symbol']					= $pay_currency_sign;
		$insert_array['pay_curr_numeric_code']			= $pay_currency_ncode;
		$insert_array['pay_unique_key']						= $row_cart['pay_unique_key'];

		$db->insert_from_array($insert_array,'order_payonaccount_pending_details');
		$pay_id		= $db->insert_id();

		if($ptype=='cheque') // if payment type is cheque, then save the cheque details
		{
			$insert_array																			= array();
			$insert_array['order_payonaccount_pending_details_pending_id']	= $pay_id;
			$insert_array['cheque_date']														= add_slash($_REQUEST['checkoutchq_date']);
			$insert_array['cheque_number']												= add_slash($_REQUEST['checkoutchq_number']);
			$insert_array['cheque_bankname']												= add_slash($_REQUEST['checkoutchq_bankname']);
			$insert_array['cheque_branchdetails']											= add_slash($_REQUEST['checkoutchq_bankbranch']);
			$db->insert_from_array($insert_array,'order_payonaccount_pending_details_cheque_details');
		}
	}
	return $pay_id;
}
/* Function to handle the payment related things for voucher*/
function handle_PayonAccount_PaymentDetails($pay_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$ecom_common_settings;
	global $ecom_selfhttp;
	$customer_id 		= get_session_var("ecom_login_customer"); // get the id of current customer from session
	// ###########################################################################
	// Calling the function to process the payment and get the status in return
	// ###########################################################################

		// Get the details of voucher with the given voucher id
		$sql_pay = "SELECT *
                                FROM
                                        order_payonaccount_pending_details
                                WHERE
                                        pendingpay_id = $pay_id 
                                        AND sites_site_id = $ecom_siteid
                                LIMIT
                                        1";
		$ret_pay = $db->query($sql_pay);
		if($db->num_rows($ret_pay)==0)
		{
			displayInvalidInput(); // case if voucher id does not exists
			exit;
		}
		else
			$row_pay = $db->fetch_array($ret_pay);
			
		// Get the customer details related to current voucher
		$sql_cust = "SELECT customer_fname, customer_surname, customer_buildingname, customer_streetname, customer_towncity,
                                    customer_statecounty, customer_phone, customer_fax, customer_postcode, customer_email_7503,country_id 
                                FROM
                                        customers 
                                WHERE
                                        customer_id = $customer_id
                                LIMIT
                                        1";
		$ret_cust	= $db->query($sql_cust);
		if ($db->num_rows($ret_cust))
		{
			$row_cust =  $db->fetch_array($ret_cust);
		}
		// Building the values to be passed to payment gateway

		$del_arr['checkout_fname']						= $row_cust['customer_fname'];
		$del_arr['checkout_surname']					= $row_cust['customer_surname'];
		$del_arr['checkout_building']					= $row_cust['customer_buildingname'];
		$del_arr['checkout_street']						= $row_cust['customer_streetname'];
		$del_arr['checkout_city']						= $row_cust['customer_towncity'];
		$del_arr['checkout_state']						= $row_cust['customer_statecounty'];
		$del_arr['checkout_zipcode']						= $row_cust['customer_postcode'];
		$del_arr['checkout_phone']						= $row_cust['customer_phone'];
		$del_arr['checkout_fax']							= $row_cust['customer_fax'];
		$del_arr['checkout_email']						= $row_cust['customer_email_7503'];
		// Get the name of country
		$sql_country = "SELECT country_name 
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id =$ecom_siteid 
										AND country_id = ".$row_cust['country_id']." 
									LIMIT 
										1";
		$ret_country = $db->query($sql_country);
		if ($db->num_rows($ret_country))
		{
			$row_country = $db->fetch_array($ret_country);
		}
		$address = $row_cust['customer_buildingname'] . "\n" . $row_cust['customer_streetname']. "\n". $row_cust['customer_towncity'] . "\n" .$row_cust['customer_statecounty'] . "\n".$row_country['country_name'] . "\n";


		if($row_pay['pay_paymentmethod'])
		{
			// Get the payment method id
			$sql_paym = "SELECT paymethod_id,paymethod_takecarddetails
									FROM
										payment_methods
									WHERE
										paymethod_key = '".$row_pay['pay_paymentmethod']."'
									LIMIT
										1";
			$ret_paym = $db->query($sql_paym);
			if ($db->num_rows($ret_paym))
				$row_paym 	= $db->fetch_array($ret_paym);
			$paymethod_id 	= $row_paym['paymethod_id'];
			$ptake_card		= $row_paym['paymethod_takecarddetails'];
		}
		if(!$paymethod_id)
			$paymethod_id 	= 0;
		$ptype					= $row_pay['pay_paymenttype'];
		$pmethod				= $row_pay['pay_paymentmethod'];
		$cartData																= array();
		$cartData["totals"]["bonus_price"]								= $row_pay['pay_amount'];// price in default currency;
		$cartData["payment"]["type"]									= $row_pay['pay_paymenttype'];
		$cartData["payment"]["method"]['paymethod_id']		= $paymethod_id;
		$cartData["payment"]["method"]['paymethod_key'] 	= $row_pay['pay_paymentmethod'];
		$cartData['checkout_country']						= $row_cust['country_id'];
		$curr_pass	= $row_pay['pay_curr_code'].'~'.$row_pay['pay_curr_numeric_code'].'~payonaccount';
		// Calling the function to process the payment
		$payData 	= processPayment($cartData,$del_arr,$curr_pass, $pay_id, $address);

		// Not authorised or payment not successfull
		if($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"]==11)
		{
			// Deleting the details inserted related to voucher related tables
			// Start comment case no delete req on failure	
			//$db->delete_id($pay_id,'pendingpay_id','order_payonaccount_pending_details');
			// End comment case no delete req on failure	

			if($payData["result"] == 4)
			{
				$err_msg = "<br><br>Sorry, the card details were rejected by our online bank. Please go back and enter the details correctly. Thank you";
				//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
				$update_array						= array();
				$update_array['pay_error_msg']	= add_slash($err_msg,false);
				$db->update_from_array($update_array,'payonaccount_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
                                $payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
			}
			elseif($payData["result"] == 3)
			{
				$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
						 Please go back and enter the details correctly. Thank you";
				//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
				$update_array						= array();
				$update_array['pay_error_msg']	= add_slash($err_msg,false);
				$db->update_from_array($update_array,'payonaccount_cartvalues',array('session_id'=>$sess_id,'sites_site_id'=>$ecom_siteid));
                                $payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
			}
                        elseif($payData["result"] == 11) // case of failure of paypal express
                        {
                                $err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
                                                Please go back and enter the details correctly. Thank you";
                                //Updated the cart_supportdetails table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
                                $update_array                                           = array();
                                $update_array['pay_error_msg']     = add_slash($err_msg,false);
                                $db->update_from_array($update_array,'payonaccount_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
                                // In case of result ==11 the value of $payData["payStatus"] will be obtained from payment.php file itself
                        }
			// Start comment case no delete req on failure
				/*echo "
						<script type='text/javascript'>
							window.location = 'http://".$ecom_hostname."/payonaccount_failed.html';
						</script>
						";
				exit;*/
			// End comment case no delete req on failure	
			
			$sql_upd = "UPDATE order_payonaccount_pending_details  
						SET 
							pay_paystatus = '" . $payData["payStatus"] . "', 
							pay_incomplete = 1 
						WHERE 
							pendingpay_id =$pay_id 
						LIMIT 
							1";
			$db->query($sql_upd);
		}

		elseif($payData["result"] == 5) // Payment process 3D Secure in case of protx
		{
			echo "<br>Please Wait! Don't refresh the page...<br>You will be redirected to 3DSecure site...";
		}


		// Inserting the payment related details to order_payonaccount_pending_details_payment table only
		// if $ptake_card==1, that is credit card details are taken directly in site
		if($ptake_card==1)
		{
			// Check whether the credit card number (if exists) is to be encrypted before storing to table
			$payData['Encrypted']	= 0;
			if($payData["card_number"]!='')
			{
				if($Settings_arr['encrypted_cc_numbers']==1)
				{
					$payData["card_number"] = base64_encode(base64_encode($payData["card_number"]));
					$payData['Encrypted']	= 1;
				}
			}
			$insert_array						= array();
			$insert_array['order_payonaccount_pendingpay_id']	= $pay_id;
			$insert_array['card_type']				= $payData["card_type"];
			$insert_array['name_on_card']				= '';//$payData["name_on_card"];
			$insert_array['card_number']				= '';//$payData["card_number"];
			$insert_array['sec_code']				= 0;//$payData["sec_code"];
			$insert_array['expiry_date_m']				= 0;//$payData["expiry_date_m"];
			$insert_array['expiry_date_y']				= 0;//$payData["expiry_date_y"];
			$insert_array['issue_number']				= 0;//$payData["issue_number"];
			$insert_array['issue_date_m']				= 0;//$payData["issue_date_m"];
			$insert_array['issue_date_y']				= 0;//$payData["issue_date_y"];
			$insert_array['vendorTxCode']				= $payData["VendorTxCode"];
			$insert_array['protStatus']				= $payData["protStatus"];
			$insert_array['protStatusDetail']			= $payData["protStatusDetail"];
			$insert_array['vPSTxId']				= $payData["VPSTxID"];
			$insert_array['securityKey']				= $payData["SecurityKey"];
			$insert_array['txAuthNo']				= $payData["TxAuthNo"];
			$insert_array['txType']					= $payData["TxType"];
			$insert_array['orgtxType']				= $payData["TxType"]; // helps to identify which was the original payment capture type
			$insert_array['avscv2']					= $payData["AVSCV2"];
			$insert_array['acsurl ']				= $payData["ACSURL"];
			$insert_array['pareq']					= $payData["PAReq"];
			$insert_array['md']					= $payData["MD"];
			$insert_array['card_encrypted']				= $payData['Encrypted'];
			$db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment');
		}

		// Updating the order_payonaccount_pending_details table with the payment status
		//$update_array								= array();
		//$update_array['voucher_paystatus']			= add_slash($payData['payStatus']);
		$sql_update = "UPDATE order_payonaccount_pending_details
							SET
								pay_paystatus='".$payData['payStatus']."'
							WHERE
								pendingpay_id = $pay_id 
							LIMIT
								1";
		$db->query($sql_update);
		
		if($payData['payStatus']=='Paid') // case if payment is success
		{
                        if($cartData["payment"]["method"]['paymethod_key']=='PAYPAL_EXPRESS') // if payment method is paypal express and payment is success, then store the additional deatils send back by paypal in order related tables
                        {
                                // if any entry exists in gift_voucher_payment_paypal table related to current voucher id, then delete it
                                $sql_del = "DELETE FROM 
                                                order_payonaccount_pending_details_payment_paypal  
                                            WHERE 
                                                order_payonaccount_pendingpay_id = $pay_id";
                                $ret_del = $db->query($sql_del);
                                // Inserting to gift_voucher_payment_paypal table
                                $insert_array                                           = array();
                                $insert_array['order_payonaccount_pendingpay_id']       = $pay_id;
                                $insert_array['sites_site_id']                          = $ecom_siteid;
                                $insert_array['paypal_transactions_id']                 = $payData["TRANSACTIONID"];    
                                $insert_array['paypal_transaction_type']                = $payData["TRANSACTIONTYPE"];
                                $insert_array['paypal_payment_type']                    = $payData["PAYMENTTYPE"];
                                $insert_array['paypal_ordertime']                       = $payData["ORDERTIME"];
                                $insert_array['paypal_amt']                             = $payData["AMT"];
                                $insert_array['paypal_currency_code']                   = $payData["CURRENCYCODE"];     
                                $insert_array['paypal_feeamt']                          = $payData["FEEAMT"];
                                $insert_array['paypal_settleamt']                       = $payData["SETTLEAMT"];
                                $insert_array['paypal_taxamt']                          = $payData["TAXAMT"];
                                $insert_array['paypal_exchange_rate']                   = $payData["EXCHANGERATE"];
                                $insert_array['paypal_paymentstatus']                   = $payData["PAYMENTSTATUS"];
                                $insert_array['paypal_pending_reason']                  = $payData["PENDINGREASON"];
                                $insert_array['paypal_reasoncode']                      = $payData["REASONCODE"];
                                $db->insert_from_array($insert_array,'order_payonaccount_pending_details_payment_paypal');
                        }
                    $pay_id = do_PostPayonAccountSuccessOperations($pay_id);
		}
		
		if($payData["result"] == 5) // Payment process 3D Secure
		{
			$order_id	= $pay_id;
			$pass_typ 	= 'payonaccount'; // This will be used to identify whether coming from order section or voucher section
			include("includes/3dsecure.php");
		}
		if($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"]== 11)
		{
			echo "
					<script type='text/javascript'>
						window.location = '".$ecom_selfhttp.$ecom_hostname."/payonaccount_failed.html';
					</script>
					";
				exit;
		}
		$ret_arr['payMethod']	= $pmethod;
		$ret_arr['payType']		= $ptype;
		$ret_arr['pay_id'] 		= $pay_id;
		$ret_arr['payData']		= $payData;
		return $ret_arr;
	}


/* Function to save the details of voucher in gift_voucherbuy_cartvalues table */
function Save_Voucher_cart($coming_from_paypal=0)
{
	global $db,$ecom_siteid,$ecom_hostname,$ecom_common_settings;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	//echo "<br>-----<br>";
        //print_r($_REQUEST);
        //echo "<br>-----<br>";
        // Check whether any record exists for voucher in current session
	$sql_check = "SELECT session_id
					FROM
						gift_voucherbuy_cartvalues
					WHERE
						session_id='".$sess_id."'
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_check = $db->query($sql_check);
        if($coming_from_paypal==1) // case if payment method is paypal
        {  
         
           // Get the payment type id of credit card and also the payment method id of paypal
           $_REQUEST['voucher_paytype']   = $ecom_common_settings['paytypeCode']['credit_card']['paytype_id'];
           $_REQUEST['voucher_paymethod'] = 'PAYPAL_EXPRESS_'.$ecom_common_settings['paymethodKey']['PAYPAL_EXPRESS']['paymethod_id'];
        }
	if ($db->num_rows($ret_check)) // case entry already exists
	{
		$update_array								= array();
		$update_array['voucher_date']				= 'now()';
		$update_array['voucher_value']				= trim($_REQUEST['voucher_value']);
		$update_array['voucher_activedays']			= trim($_REQUEST['voucher_noofdaysactive']);
		$update_array['voucher_toname']				= add_slash($_REQUEST['email_to']);
		$update_array['voucher_toemail']			= add_slash($_REQUEST['email_id']);
		$update_array['voucher_tomessage']			= add_slash($_REQUEST['email_message']);
		$update_array['voucher_title']				= add_slash($_REQUEST['checkout_vouchertitle']);
		$update_array['voucher_fname']				= add_slash($_REQUEST['checkout_voucherfname']);
		$update_array['voucher_mname']				= add_slash($_REQUEST['checkout_vouchermname']);
		$update_array['voucher_surname']			= add_slash($_REQUEST['checkout_vouchersurname']);
		$update_array['voucher_buildingno']			= add_slash($_REQUEST['checkout_voucherbuilding']);
		$update_array['voucher_street']				= add_slash($_REQUEST['checkout_voucherstreet']);
		$update_array['voucher_city']				= add_slash($_REQUEST['checkout_vouchercity']);
		$update_array['voucher_state']				= add_slash($_REQUEST['checkout_voucherstate']);
		$update_array['voucher_country']			= add_slash($_REQUEST['checkout_vouchercountry']);
		$update_array['voucher_zip']				= add_slash($_REQUEST['checkout_voucherzipcode']);
		$update_array['voucher_phone']				= add_slash($_REQUEST['checkout_voucherphone']);
		$update_array['voucher_mobile']				= add_slash($_REQUEST['checkout_vouchermobile']);
		$update_array['voucher_fax']				= add_slash($_REQUEST['checkout_voucherfax']);
		$update_array['voucher_company']			= add_slash($_REQUEST['checkout_vouchercomp_name']);
		$update_array['voucher_email']				= add_slash($_REQUEST['checkout_voucheremail']);
		$update_array['voucher_note']				= add_slash($_REQUEST['checkout_vouchernotes']);
                
                $update_array['voucher_paytype']			= add_slash($_REQUEST['voucher_paytype']);
		$update_array['voucher_type']				= add_slash($_REQUEST['voucher_type']);
		$update_array['voucher_unique_key']			= add_slash($_REQUEST['voucher_unique_key']);
		if ($_REQUEST['voucher_paymethod']!='')
		{
                        $pmethod					= explode('_',$_REQUEST['voucher_paymethod']);
			$update_array['voucher_paymethod']		= $pmethod[count($pmethod)-1];
		}
		else
			$update_array['voucher_paymethod']		= 0;
                $db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('session_id'=>$sess_id));
                      //  print_r($update_array);
                       // exit;
	}
	else // case entry does not exists
	{
		$insert_array								= array();
		$insert_array['session_id']					= $sess_id;
		$insert_array['sites_site_id']				= $ecom_siteid;
		$insert_array['voucher_date']				= 'now()';
		$insert_array['voucher_value']				= trim($_REQUEST['voucher_value']);
		$insert_array['voucher_activedays']			= trim($_REQUEST['voucher_noofdaysactive']);
		$insert_array['voucher_toname']				= add_slash($_REQUEST['email_to']);
		$insert_array['voucher_toemail']			= add_slash($_REQUEST['email_id']);
		$insert_array['voucher_tomessage']			= add_slash($_REQUEST['email_message']);
		$insert_array['voucher_title']				= add_slash($_REQUEST['checkout_vouchertitle']);
		$insert_array['voucher_fname']				= add_slash($_REQUEST['checkout_voucherfname']);
		$insert_array['voucher_mname']				= add_slash($_REQUEST['checkout_vouchermname']);
		$insert_array['voucher_surname']			= add_slash($_REQUEST['checkout_vouchersurname']);
		$insert_array['voucher_buildingno']			= add_slash($_REQUEST['checkout_voucherbuilding']);
		$insert_array['voucher_street']				= add_slash($_REQUEST['checkout_voucherstreet']);
		$insert_array['voucher_city']				= add_slash($_REQUEST['checkout_vouchercity']);
		$insert_array['voucher_state']				= add_slash($_REQUEST['checkout_voucherstate']);
		$insert_array['voucher_country']			= add_slash($_REQUEST['checkout_vouchercountry']);
		$insert_array['voucher_zip']				= add_slash($_REQUEST['checkout_voucherzipcode']);
		$insert_array['voucher_phone']				= add_slash($_REQUEST['checkout_voucherphone']);
		$insert_array['voucher_mobile']				= add_slash($_REQUEST['checkout_vouchermobile']);
		$insert_array['voucher_fax']				= add_slash($_REQUEST['checkout_voucherfax']);
		$insert_array['voucher_company']			= add_slash($_REQUEST['checkout_vouchercomp_name']);
		$insert_array['voucher_email']				= add_slash($_REQUEST['checkout_voucheremail']);
		$insert_array['voucher_note']				= add_slash($_REQUEST['checkout_vouchernotes']);
		$insert_array['voucher_paytype']			= add_slash($_REQUEST['voucher_paytype']);
		$insert_array['voucher_type']				= 'val';
		$insert_array['voucher_unique_key']			= add_slash($_REQUEST['voucher_unique_key']);
		if ($_REQUEST['voucher_paymethod']!='')
		{
			$pmethod												= explode('_',$_REQUEST['voucher_paymethod']);
			$insert_array['voucher_paymethod']		= $pmethod[count($pmethod)-1];
		}
		else
			$insert_array['voucher_paymethod']		= 0;
		$db->insert_from_array($insert_array,'gift_voucherbuy_cartvalues');
	}
}
/* Function to save the actual voucher details */
function Save_VoucherDetails()
{
	global $db,$ecom_siteid,$sitesel_curr,$default_Currency_arr,$Settings_arr,$ecom_hostname,$ecom_is_country_textbox;
	global $ecom_selfhttp;
	$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
	// Get the voucher details from gift_voucherbuy_cartvalues table
	$sess_id 	= Get_session_Id_from();
	$sql_cart	= "SELECT
							voucher_date,voucher_value,voucher_toname,voucher_toemail,
							voucher_tomessage,voucher_title,voucher_fname,voucher_mname,
							voucher_surname,voucher_buildingno,voucher_street,voucher_city,
							voucher_state,voucher_country,voucher_zip,voucher_phone,
							voucher_mobile,voucher_company, voucher_fax,voucher_email,
							voucher_note,voucher_paytype,voucher_paymethod,voucher_type,voucher_activedays,
							voucher_unique_key
						FROM
								gift_voucherbuy_cartvalues 
						WHERE
								session_id ='".$sess_id."'
								AND sites_site_id = $ecom_siteid 
						LIMIT
							1";
	$ret_cart	= $db->query($sql_cart);
	if ($db->num_rows($ret_cart))
	{
		$row_cart 			= $db->fetch_array($ret_cart);
                
		// Check whether any voucher already exists with the current value of voucher_unique_key in gift_vouchers table for current site
		/*$sql_check = "SELECT voucher_id
						FROM
							gift_vouchers
						WHERE
							sites_site_id = $ecom_siteid
							AND voucher_unique_key='".$row_cart['voucher_unique_key']."'
						LIMIT
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)) // if already exists .. then take user back to buy gift voucher page with an error msg
		{
			echo "
				<script type='text/javascript'>
					window.location = 'http://".$ecom_hostname."/buy_voucher.html?rt=1';
				</script>
				";
			exit;
		}*/
		// Currency

		if($sitesel_curr != $default_Currency_arr['currency_id'])
		{
			$sql_curr  = "SELECT curr_rate,curr_sign_char,curr_code,curr_numeric_code,curr_margin 
							FROM
							general_settings_site_currency
							WHERE
							currency_id=$sitesel_curr
							AND sites_site_id=$ecom_siteid";
			$ret_curr  = $db->query($sql_curr);
			if($db->num_rows($ret_curr))
			{
				$row_curr  			= $db->fetch_array($ret_curr);
				$curr_rate 				= ($row_curr['curr_rate']+$row_curr['curr_margin']);
				$curr_sign 				= $row_curr['curr_sign_char'];
				$curr_code 			= $row_curr['curr_code'];
				$curr_num_code 	= $row_curr['curr_numeric_code'];
			}
		}
		else
		{
			$curr_rate 				= 1;// + $default_Currency_arr['curr_margin']);
			$curr_sign 				= $default_Currency_arr['curr_sign_char'];
			$curr_code 			= $default_Currency_arr['curr_code'];
			$curr_num_code 	= $default_Currency_arr['curr_numeric_code'];
		}
		$vouch_currency_rate 	= $curr_rate;
		$vouch_currency_code 	= $curr_code;
		$vouch_currency_sign 	= $curr_sign;
		$vouch_currency_ncode	= $curr_num_code;
		// Building the voucher number now
		$voucher_number							= get_UniqueVoucherNumber();
		$insert_array									= array();
		$insert_array['sites_site_id']				= $ecom_siteid;
		$insert_array['voucher_number']		= $voucher_number;
		$insert_array['voucher_boughton']		= 'curdate()';
		$insert_array['voucher_type']			= 'val';
		$insert_array['voucher_value']			= convert_price_default_currency($row_cart['voucher_value'],$vouch_currency_rate);
		$insert_array['voucher_max_usage']	= 1;
		$insert_array['voucher_login_touse']	= 0;
		$insert_array['voucher_createdby']	= 'C';
		$insert_array['voucher_apply_direct_discount_also']			= ($Settings_arr['gift_voucher_apply_customer_direct_disc_also']=='Y')?'Y':'N';
		$insert_array['voucher_apply_custgroup_discount_also']		= ($Settings_arr['gift_voucher_apply_customer_group_disc_also']=='Y')?'Y':'N';
		$insert_array['voucher_apply_direct_product_discount_also']	= ($Settings_arr['gift_voucher_apply_direct_product_discount_also']=='Y')?'Y':'N';
		$insert_array['voucher_activedays']	= $row_cart['voucher_activedays'];
		// Check whether payment type exists
		if ($row_cart['voucher_paytype']!=0)
		{
			// Get the key for payment type
			$sql_ptype = "SELECT paytype_code
							FROM
								payment_types
							WHERE
								paytype_id = ".$row_cart['voucher_paytype']."
							LIMIT
								1";
			$ret_ptype = $db->query($sql_ptype);
			if ($db->num_rows($ret_ptype))
			{
				$row_ptype 	= $db->fetch_array($ret_ptype);
				$ptype		= $row_ptype['paytype_code'];
			}
		}
		else
			$ptype = '';
		$insert_array['voucher_paymenttype']	= $ptype;

		// Check whether payment method exists
		if ($row_cart['voucher_paymethod']!=0)
		{
			// Get the key for payment method
			$sql_pmethod = "SELECT paymethod_key,paymethod_takecarddetails
							FROM
								payment_methods
							WHERE
								paymethod_id = ".$row_cart['voucher_paymethod']."
							LIMIT
								1";
                        $ret_pmethod = $db->query($sql_pmethod);
			if ($db->num_rows($ret_pmethod))
			{
				$row_pmethod 	= $db->fetch_array($ret_pmethod);
				$pmethod		= $row_pmethod['paymethod_key'];
				$ptake_card		= $row_pmethod['paymethod_takecarddetails'];
			}
		}
		else
		{
			$pmethod 	= '';
			$ptake_card = '';
		}

		$insert_array['voucher_paymentmethod']			= $pmethod;
		$insert_array['voucher_curr_rate']				= $vouch_currency_rate;
		$insert_array['voucher_curr_code']				= $vouch_currency_code;
		$insert_array['voucher_curr_symbol']			= $vouch_currency_sign;
		$insert_array['voucher_curr_numeric_code']		= $vouch_currency_ncode;
		$insert_array['voucher_unique_key']				= $row_cart['voucher_unique_key'];
		$insert_array['customers_customer_id']			= ($cust_id)?$cust_id:0;
		$db->insert_from_array($insert_array,'gift_vouchers');
		$voucher_id		= $db->insert_id();

		// Making entry to the gift_vouchers_customer table	with the details of customer who bought the voucher
		$insert_array										= array();
		$insert_array['voucher_id']					= $voucher_id;
		$insert_array['voucher_toname']				= addslashes(stripslashes($row_cart['voucher_toname']));
		$insert_array['voucher_toemail']			= addslashes(stripslashes($row_cart['voucher_toemail']));
		$insert_array['voucher_tomessage']			= addslashes(stripslashes($row_cart['voucher_tomessage']));
		$insert_array['voucher_title']				= addslashes(stripslashes($row_cart['voucher_title']));
		$insert_array['voucher_fname']				= addslashes(stripslashes($row_cart['voucher_fname']));
		$insert_array['voucher_mname']				= addslashes(stripslashes($row_cart['voucher_mname']));
		$insert_array['voucher_surname']			= addslashes(stripslashes($row_cart['voucher_surname']));
		$insert_array['voucher_buildingno']			= addslashes(stripslashes($row_cart['voucher_buildingno']));
		$insert_array['voucher_street']				= addslashes(stripslashes($row_cart['voucher_street']));
		$insert_array['voucher_city']				= addslashes(stripslashes($row_cart['voucher_city']));
		$insert_array['voucher_state']				= addslashes(stripslashes($row_cart['voucher_state']));
		if($ecom_is_country_textbox==1) // case of textbox
		{
			$insert_array['voucher_country']		= addslashes(stripslashes($row_cart['voucher_country']));
		}
		else
		{
			// Get the name of country from general_settings_site_country table
			$sql_country_name = "SELECT country_name 
									FROM 
										general_settings_site_country 
									WHERE 
										country_id =".$row_cart['voucher_country']." 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
			$ret_country_name = $db->query($sql_country_name);
			if($db->num_rows($ret_country_name))
			{
				$row_country_name = $db->fetch_array($ret_country_name);
				$insert_array['voucher_country']		= addslashes(stripslashes($row_country_name['country_name']));
			}
			else
				$insert_array['voucher_country']		= '';	
		}	
		$insert_array['voucher_zip']				= addslashes(stripslashes($row_cart['voucher_zip']));
		$insert_array['voucher_phone']				= addslashes(stripslashes($row_cart['voucher_phone']));
		$insert_array['voucher_mobile']				= addslashes(stripslashes($row_cart['voucher_mobile']));
		$insert_array['voucher_fax']				= addslashes(stripslashes($row_cart['voucher_fax']));
		$insert_array['voucher_company']			= addslashes(stripslashes($row_cart['voucher_company']));
		$insert_array['voucher_email']				= addslashes(stripslashes($row_cart['voucher_email']));
		$insert_array['voucher_note']				= addslashes(stripslashes($row_cart['voucher_note']));
		$db->insert_from_array($insert_array,'gift_vouchers_customer');

		if($ptype=='cheque') // if payment type is cheque, then save the cheque details
		{
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $voucher_id;
			$insert_array['cheque_date']				= add_slash($_REQUEST['checkoutchq_date']);
			$insert_array['cheque_number']				= add_slash($_REQUEST['checkoutchq_number']);
			$insert_array['cheque_bankname']			= add_slash($_REQUEST['checkoutchq_bankname']);
			$insert_array['cheque_branchdetails']		= add_slash($_REQUEST['checkoutchq_bankbranch']);
			$db->insert_from_array($insert_array,'gift_vouchers_cheque_details');
		}
	}
	return $voucher_id;
}
/* Function to handle the payment related things for voucher*/
function handle_Giftvoucher_PaymentDetails($voucher_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$ecom_common_settings;
	global $ecom_selfhttp;
	$sess_id 				= Get_session_Id_from(); // getting the id of current session
	// ###########################################################################
	// Calling the function to process the payment and get the status in return
	// ###########################################################################

		// Get the details of voucher with the given voucher id
		$sql_vouch = "SELECT *
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$ret_vouch = $db->query($sql_vouch);
		if($db->num_rows($ret_vouch)==0)
		{
			displayInvalidInput(); // case if voucher id does not exists
			exit;
		}
		else
			$row_vouch = $db->fetch_array($ret_vouch);

		// Get the customer details related to current voucher
		$sql_vouch_cust = "SELECT *
							FROM
								gift_vouchers_customer
							WHERE
								voucher_id = $voucher_id
							LIMIT
								1";
		$ret_cust_vouch	= $db->query($sql_vouch_cust);
		if ($db->num_rows($ret_cust_vouch))
		{
			$row_cust_vouch = $db->fetch_array($ret_cust_vouch);
		}
		// Building the values to be passed to payment gateway

		$del_arr['checkout_fname']						= $row_cust_vouch['voucher_fname'];
		$del_arr['checkout_surname']					= $row_cust_vouch['voucher_surname'];
		$del_arr['checkout_building']					= $row_cust_vouch['voucher_buildingno'];
		$del_arr['checkout_street']						= $row_cust_vouch['voucher_street'];
		$del_arr['checkout_city']						= $row_cust_vouch['voucher_city'];
		$del_arr['checkout_state']						= $row_cust_vouch['voucher_state'];
		$del_arr['checkout_zipcode']					= $row_cust_vouch['voucher_zip'];
		$del_arr['checkout_phone']						= $row_cust_vouch['voucher_phone'];
		$del_arr['checkout_fax']						= $row_cust_vouch['voucher_fax'];
		$del_arr['checkout_email']						= $row_cust_vouch['voucher_email'];

		$address = $row_cust_vouch['voucher_buildingno'] . "\n" . $row_cust_vouch['voucher_street']. "\n". $row_cust_vouch['voucher_city'] . "\n" .$row_cust_vouch['voucher_state'] . "\n".$row_cust_vouch['voucher_country'] . "\n";// . $row_cust_vouch['voucher_zip'];


		if($row_vouch['voucher_paymentmethod'])
		{
			// Get the payment method id
			$sql_pay = "SELECT paymethod_id,paymethod_takecarddetails
						FROM
							payment_methods
						WHERE
							paymethod_key = '".$row_vouch['voucher_paymentmethod']."'
						LIMIT
							1";
			$ret_pay = $db->query($sql_pay);
			if ($db->num_rows($ret_pay))
				$row_pay = $db->fetch_array($ret_pay);
			$paymethod_id = $row_pay['paymethod_id'];
			$ptake_card		= $row_pay['paymethod_takecarddetails'];
		}
		if(!$paymethod_id)
			$paymethod_id = 0;
		$ptype		= $row_vouch['voucher_paymenttype'];
		$pmethod	= $row_vouch['voucher_paymentmethod'];
		$cartData												= array();
		$cartData["totals"]["bonus_price"]						= $row_vouch['voucher_value'];// price in default currency;
		$cartData["payment"]["type"]							= $row_vouch['voucher_paymenttype'];
		$cartData["payment"]["method"]['paymethod_id']			= $paymethod_id;
		$cartData["payment"]["method"]['paymethod_key'] 		= $row_vouch['voucher_paymentmethod'];
		
		// Get the id of country from general_settings_site_country table for current name
		$sql_country_id = "SELECT country_id 
								FROM 
									general_settings_site_country  
								WHERE 
									country_name='".addslashes(stripslashes($row_cust_vouch['voucher_country']))."' 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
		$ret_country_id = $db->query($sql_country_id);
		if($db->num_rows($ret_country_id))
		{
			$row_country_id 	= $db->fetch_array($ret_country_id);
			$pass_country_id	= $row_country_id['country_id'];
		}
		else
			$pass_country_id = 0;
		$cartData['checkout_country']						= $pass_country_id;
		$curr_pass	= $row_vouch['voucher_curr_code'].'~'.$row_vouch['voucher_curr_numeric_code'].'~voucher';
		// Calling the function to process the payment
		$payData 	= processPayment($cartData,$del_arr,$curr_pass, $voucher_id, $address);
		// Not authorised or payment not successfull
		if($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"] == 11 or $payData["result"] == 20)
		{
			// Start comment case no delete req on failure		
			// Deleting the details inserted related to voucher related tables
			/*db->delete_id($voucher_id,'voucher_id','gift_vouchers_customer');
			$db->delete_id($voucher_id,'voucher_id','gift_vouchers');*/
			// End comment case no delete req on failure	


			if($payData["result"] == 4)
			{
				$err_msg = "<br><br>Sorry, the card details were rejected by our online bank. Please go back and enter the details correctly. Thank you";
				//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
				$update_array						= array();
				$update_array['voucher_error_msg']	= add_slash($err_msg,false);
				$db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
                                $payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
			}
			elseif($payData["result"] == 3)
			{
				$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
						 Please go back and enter the details correctly. Thank you";
				//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
				$update_array						= array();
				$update_array['voucher_error_msg']	= add_slash($err_msg,false);
				$db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('session_id'=>$sess_id));
                                $payData['payStatus'] = 'PROTX'; // setting the payment status as protx since customer will reach here only if payment failed in case of protx direct
			}
			elseif($payData["result"] == 11) // case of failure of paypal express
			{
					$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
									Please go back and enter the details correctly. Thank you";
					//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
					$update_array                          = array();
					$update_array['voucher_error_msg']     = add_slash($err_msg,false);
					$db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
					
					// In case of result ==11 the value of $payData["payStatus"] will be obtained from payment.php file itself
			}
			elseif($payData["result"] == 20) // case of failure of paypal PRO
			{
					$err_msg =  "<br><br>Sorry, Payment Not Successful.<br><br>".$payData['error_details']."<br><br>
									Please go back and enter the details correctly. Thank you";
					//Updated the gift_voucherbuy_cartvalues table with the error message. This msg will be used to show in checkout failure section in cartHtml.php page
					$update_array                          = array();
					$update_array['voucher_error_msg']     = add_slash($err_msg,false);
					$db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));
					// In case of result ==11 the value of $payData["payStatus"] will be obtained from payment.php file itself
			}

				// Start comment case no delete req on failure	
				/*echo "
						<script type='text/javascript'>
							window.location = 'http://".$ecom_hostname."/voucher_failed.html';
						</script>
						";
				exit;*/
				
				// End comment case no delete req on failure
				$sql_upd = "UPDATE gift_vouchers 
							SET 
								voucher_paystatus = '" . $payData["payStatus"] . "', 
								voucher_incomplete = 1 
							WHERE 
								voucher_id =$voucher_id 
							LIMIT 
								1";
				$db->query($sql_upd);
		}

		elseif($payData["result"] == 5) // Payment process 3D Secure in case of protx
		{
			echo "<br>Please Wait! Don't refresh the page...<br>You will be redirected to 3DSecure site...";
		}


		// Inserting the payment related details to gift_vouchers_payment table only
		// if $ptake_card==1, that is credit card details are taken directly in site
		if($ptake_card==1)
		{
			// Check whether the credit card number (if exists) is to be encrypted before storing to table
			$payData['Encrypted']	= 0;
			if($payData["card_number"]!='')
			{
				if($Settings_arr['encrypted_cc_numbers']==1)
				{
					$payData["card_number"] = base64_encode(base64_encode($payData["card_number"]));
					$payData['Encrypted']	= 1;
				}
			}
			$insert_array											= array();
			$insert_array['gift_vouchers_voucher_id']		= $voucher_id;
			$insert_array['card_type']							= $payData["card_type"];
			$insert_array['name_on_card']					= '';//$payData["name_on_card"];
			$insert_array['card_number']						= '';//$payData["card_number"];
			$insert_array['sec_code']							= 0;//$payData["sec_code"];
			$insert_array['expiry_date_m']					= 0;//$payData["expiry_date_m"];
			$insert_array['expiry_date_y']					= 0;//$payData["expiry_date_y"];
			$insert_array['issue_number']					= 0;//$payData["issue_number"];
			$insert_array['issue_date_m']					= 0;//$payData["issue_date_m"];
			$insert_array['issue_date_y']						= 0;//$payData["issue_date_y"];
			$insert_array['vendorTxCode']					= $payData["VendorTxCode"];
			$insert_array['protStatus']						= $payData["protStatus"];
			$insert_array['protStatusDetail']					= $payData["protStatusDetail"];
			$insert_array['vPSTxId']							= $payData["VPSTxID"];
			$insert_array['securityKey']						= $payData["SecurityKey"];
			$insert_array['txAuthNo']							= $payData["TxAuthNo"];
			$insert_array['txType']								= $payData["TxType"];
			$insert_array['orgtxType']							= $payData["TxType"]; // helps to identify which was the original payment capture type
			$insert_array['avscv2']								= $payData["AVSCV2"];
			$insert_array['acsurl ']								= $payData["ACSURL"];
			$insert_array['pareq']								= $payData["PAReq"];
			$insert_array['md']									= $payData["MD"];
			$insert_array['card_encrypted']					= $payData['Encrypted'];
			$db->insert_from_array($insert_array,'gift_vouchers_payment');
		}

		// Updating the voucher table with the payment status
		//$update_array								= array();
		//$update_array['voucher_paystatus']			= add_slash($payData['payStatus']);
		if($payData['payStatus']=='Paid') // case if payment is success
		{
			$sql_update = "UPDATE gift_vouchers
                                        SET
                                                voucher_paystatus='".$payData['payStatus']."',
                                                voucher_activatedon = curdate(),
                                                voucher_expireson	= DATE_ADD(curdate(),INTERVAL voucher_activedays DAY)
                                        WHERE
                                                voucher_id = $voucher_id
                                        LIMIT
                                                1";
			$db->query($sql_update);
			if($cartData["payment"]["method"]['paymethod_key']=='PAYPAL_EXPRESS' or $cartData["payment"]["method"]['paymethod_key']=='PAYPALPRO' ) // if payment method is paypal express and payment is success, then store the additional deatils send back by paypal in order related tables
			{
					// if any entry exists in gift_voucher_payment_paypal table related to current voucher id, then delete it
					$sql_del = "DELETE FROM 
									gift_voucher_payment_paypal 
								WHERE 
									gift_vouchers_voucher_id = $voucher_id";
					$ret_del = $db->query($sql_del);
					// Inserting to gift_voucher_payment_paypal table
					$insert_array                                   = array();
					$insert_array['gift_vouchers_voucher_id']       = $voucher_id;
					$insert_array['sites_site_id']                  = $ecom_siteid;
					$insert_array['paypal_transactions_id']         = $payData["TRANSACTIONID"];    
					$insert_array['paypal_transaction_type']        = $payData["TRANSACTIONTYPE"];
					$insert_array['paypal_payment_type']            = $payData["PAYMENTTYPE"];
					$insert_array['paypal_ordertime']               = $payData["ORDERTIME"];
					$insert_array['paypal_amt']                     = $payData["AMT"];
					$insert_array['paypal_currency_code']           = $payData["CURRENCYCODE"];     
					$insert_array['paypal_feeamt']                  = $payData["FEEAMT"];
					$insert_array['paypal_settleamt']               = $payData["SETTLEAMT"];
					$insert_array['paypal_taxamt']                  = $payData["TAXAMT"];
					$insert_array['paypal_exchange_rate']           = $payData["EXCHANGERATE"];
					$insert_array['paypal_paymentstatus']           = $payData["PAYMENTSTATUS"];
					$insert_array['paypal_pending_reason']          = $payData["PENDINGREASON"];
					$insert_array['paypal_reasoncode']              = $payData["REASONCODE"];
					$insert_array['paypal_avscode'] 				= $payData["AVSCODE"];
					$insert_array['paypal_cvv2match'] 				= $payData["CVV2MATCH"];
					$insert_array['paypal_VPAS'] 					= $payData["VPAS"];
					$db->insert_from_array($insert_array,'gift_voucher_payment_paypal');
			}
			// Sending mails which are not send yet
			send_RequiredVoucherMails($voucher_id);
		}
		else // case we cant predict whether payment is successfull
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='".$payData['payStatus']."'
							WHERE
								voucher_id = $voucher_id
							LIMIT
								1";
			$db->query($sql_update);

		}
		//$db->update_from_array($update_array,'gift_vouchers',array('voucher_id'=>$voucher_id,'sites_site_id'=>$ecom_siteid));

		// Script to build the content for email here

		// Saving the details of emails to the order_emails table
		// Get the email template for voucher confirmation for current site
		$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
							FROM
								general_settings_site_letter_templates
							WHERE
								sites_site_id = $ecom_siteid
								AND lettertemplate_letter_type = 'VOUCHER_CONFIRMATION_CUST'
							LIMIT
								1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 	= $db->fetch_array($ret_template);
			$email_from		= stripslashes($row_template['lettertemplate_from']);
			$email_subject	= stripslashes($row_template['lettertemplate_subject']);
			$email_content	= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled	= stripslashes($row_template['lettertemplate_disabled']);
			$email_domain	= $ecom_hostname;
			// Get the checkout fields from general_settings_sites_checkoutfields table
			$sql_checkout = "SELECT field_key,field_name
								FROM
									general_settings_site_checkoutfields
								WHERE
									sites_site_id = $ecom_siteid
									AND field_type IN ('VOUCHER')
								ORDER BY
									field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				while ($row_checkout = $db->fetch_array($ret_checkout))
				{
					$chkorder_arr[$row_checkout['field_key']] = stripslashes($row_checkout['field_name']);
				}
			}


			// Get the details of current voucher from voucher related tables
			$sql_vouch = "SELECT *
								FROM
									gift_vouchers
								WHERE
									voucher_id = $voucher_id
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
			$ret_vouch = $db->query($sql_vouch);
			if ($db->num_rows($ret_vouch))
				$row_vouch = $db->fetch_array($ret_vouch);

			// Get the details of customers related to current voucher
			$sql_vouch_cust = "SELECT *
									FROM
										gift_vouchers_customer
									WHERE
										voucher_id = $voucher_id
									LIMIT
										1";
			$ret_vouch_cust = $db->query($sql_vouch_cust);
			if ($db->num_rows($ret_vouch_cust))
				$row_vouch_cust = $db->fetch_array($ret_vouch_cust);

			$email_to				= stripslashes($row_vouch_cust['voucher_email']);
			$email_voucherid		= $row_vouch['voucher_id'];
			$email_name				= stripslashes($row_vouch_cust['voucher_title']).stripslashes($row_vouch_cust['voucher_fname']).' '.stripslashes($row_vouch_cust['voucher_mname']).' '.stripslashes($row_vouch_cust['voucher_surname']);
			$email_voucher_code		= $row_vouch['voucher_number'];
			$email_curdate			= date('d-M-Y');
			$email_voucher_value	= print_price_selected_currency($row_vouch['voucher_value'],$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
			$email_total_usage		= 1;
			$email_paytype			= $row_vouch['voucher_paymenttype'];
			$email_paymethod		= $row_vouch['voucher_paymentmethod'];
			$email_activedays		= $row_vouch['voucher_activedays'];
			$email_vouchertoname	= stripslashes($row_vouch_cust['voucher_toname']);
			$email_vouchertoemail	= stripslashes($row_vouch_cust['voucher_toemail']);
			$email_vouchertomsg		= stripslashes($row_vouch_cust['voucher_tomessage']);
			$activated_on			= $row_vouch['voucher_activatedon'];
			$expires_on				= $row_vouch['voucher_expireson'];
			$email_activatedon = $email_expireson = '-';

           	$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
			$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
			$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

			// ##############################################################################
			// Main Billing address details
			// ##############################################################################
			$bought_str		= '<table width="100%" celpadding="0" cellspacing="0" border="0">';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Name</td><td align="left" width="50%" '.$style_desc.'>'.$email_name.'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_vouchercomp_name'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_company']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherbuilding'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_buildingno']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherstreet'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_street']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_vouchercity'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_city']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherstate'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_state']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_vouchercountry'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_country']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherzipcode'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_zip']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherphone'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_phone']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_vouchermobile'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_mobile']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucherfax'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_fax']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_voucheremail'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_email']).'</td></tr>';
			$bought_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_vouchernotes'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_note']).'</td></tr>';
			$bought_str		.= '</table>';

			// ##############################################################################
			// Details of person to whom the voucher details to be send
			// ##############################################################################
			$sendto_str		= '<table width="100%" celpadding="0" cellspacing="0" border="0">';
			$sendto_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Name</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_toname']).'</td></tr>';
			$sendto_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Email Id</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_toemail']).'</td></tr>';
			$sendto_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Message</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_vouch_cust['voucher_tomessage']).'</td></tr>';
			$sendto_str		.= '</table>';

			// Building the array to search for to make the replacements
			$search_arr = array
							(
								'[name]',
								'[domain]',
								'[bought_by_details]',
								'[send_to_details]',
								'[voucher_code]',
								'[activated_on]',
								'[expires_on]',
								'[voucherdate]',
								'[voucher_value]',
								'[total_usage]',
								'[payment_status]'
							);
			// Building the array to replace the values in above array
			$replace_arr = array
							(
								$email_name,
								$ecom_hostname,
								$bought_str,
								$sendto_str,
								$email_voucher_code,
								'<activatedon>'.$email_activatedon.'</activatedon>',
								'<expireson>'.$email_expireson.'</expireson>',
								$email_curdate,
								$email_voucher_value,
								$email_total_usage,
								'<paystat>'.getpaymentstatus_Name($row_vouch["voucher_paystatus"]).'</paystat>'
							);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);

			// Building email headers to be used with the customer order confirmation email
			$email_headers = "From: $ecom_hostname	<$email_from>\n";
			$email_headers .= "MIME-Version: 1.0\n";
			$email_headers .= "Content-type: text/html; charset=iso-8859-1\n";


			// Inserting the order confirmation email details for customer in gift_voucher_emails table
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $email_voucherid;
			$insert_array['email_to']					= add_slash($email_to);
			$insert_array['email_subject']				= add_slash($email_subject);
			//$insert_array['email_message']				= addslashes($email_content);
			$insert_array['email_headers']				= add_slash($email_headers,false);
			$insert_array['email_type']					= 'VOUCHER_CONFIRMATION_CUST';
			$insert_array['email_was_disabled']			= $email_disabled;
			$db->insert_from_array($insert_array,'gift_voucher_emails');
			$curmail_id	= $db->insert_id();
			$email_content = add_line_break($email_content);
			write_email_as_file('vouch',$curmail_id,stripslashes($email_content));
			$sess_id = Get_session_Id_from();
			// If the payment status is Paid then update the start and end date and also delete from voucher cart tables
			if($payData['payStatus']=='Paid')
			{
				/*
					Clear the voucher cart here and also set the startdate and
					enddate to correct value from here based on the active days given
				 */
					$update_sql	= "UPDATE
										gift_vouchers
									SET
										voucher_activatedon=curdate(),
										voucher_expireson = date_add(curdate(),INTERVAL $email_activedays DAY)
									WHERE
										voucher_id = $email_voucherid
									LIMIT
										1";
					$db->query($update_sql);

					// Clearing the cart table for voucher
					$sql_delete = "DELETE FROM
										gift_voucherbuy_cartvalues
									WHERE
										session_id = '".$sess_id."'
									LIMIT 1";
					$db->query($sql_delete);
					// Get back the start and end date for the voucher
					$sql_vouchdate = "SELECT voucher_activatedon,voucher_expireson
										FROM
											gift_vouchers
										WHERE
											voucher_id = ".$email_voucherid."
										LIMIT
											1";
					$ret_vouchdate = $db->query($sql_vouchdate);
					if ($db->num_rows($ret_vouchdate))
					{
						$row_vouchdate 		= $db->fetch_array($ret_vouchdate);
						$email_startdate 	= $row_vouchdate['voucher_activatedon'];
						$email_enddate 		= $row_vouchdate['voucher_expireson'];
						$active_arr 		= explode("-",$activated_on);
						$email_activatedon	= $active_arr[2].'-'.$active_arr[1].'-'.$active_arr[0];
						$expire_arr 		= explode("-",$expires_on);
						$email_expireson	= $expire_arr[2].'-'.$expire_arr[1].'-'.$expire_arr[0];
					}
			}

			// Mail sending section to Customer who bought the gift voucher
			if($email_disabled==0)
			{ 
				if($payData["result"] != 5 && $payData["result"] != 9 && $payData["result"] != 3 && $payData["result"] != 4 && $payData["result"] !=11 && $payData["result"] !=20) // case of not 3d secure and not protx vsp
				{
					$sendmail_now = false;
					if($payData['payStatus']=='Paid') // if status is paid then send the mail
					{
						$sendmail_now = true;
					}
					if($sendmail_now) // Check whether mail is to be send now
					{
						$pay_str		= $payData['payStatus'];
						$email_content 	= preg_replace ("/<paystat>(.*)<\/paystat>/","<paystat>$pay_str</paystat>", $email_content);
						$email_content 	= preg_replace ("/<activatedon>(.*)<\/activatedon>/","<activatedon>$email_activatedon</activatedon>", $email_content);
						$email_content 	= preg_replace ("/<expireson>(.*)<\/expireson>/","<expireson>$email_expireson</expireson>", $email_content);
						
						if($payData["result"] != 3 and $payData["result"] != 4)
						{
							$email_content = add_line_break($email_content);
							// Making the necessary replacements to the email content
							mail($email_to, $email_subject,$email_content, $email_headers);
	
							//Updating the email_sendonce field in gift_voucher_emails table for current mail
							$update_array						= array();
							$update_array['email_sendonce']		= 1;
							//$update_array['email_message']		=  addslashes($email_content);
							$update_array['email_lastsenddate']	= 'now()';
							$db->update_from_array($update_array,'gift_voucher_emails',array('email_id'=>$curmail_id));
							write_email_as_file('vouch',$curmail_id,stripslashes($email_content));
						}	
					}
				}
			}


			// Get the content of voucher notification to site admin email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'VOUCHER_NOTIFICATION_ADMIN'
						LIMIT
							1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 			= $db->fetch_array($ret_template);
				$email_adminfrom		= stripslashes($row_template['lettertemplate_from']);
				$email_adminsubject		= stripslashes($row_template['lettertemplate_subject']);
				$email_admincontent		= stripslashes($row_template['lettertemplate_contents']);
				$email_admindisabled	= stripslashes($row_template['lettertemplate_disabled']);
			}

			// Do the replacement in email template content
			$search_arr[]		= '[pay_type]'; // adding additional variables to search and replace array for admin email
			$search_arr[]		= '[pay_method]';
			$replace_arr[]		= $email_paytype;
			$replace_arr[]		= $email_paymethod;
			$email_admincontent = str_replace($search_arr,$replace_arr,$email_admincontent);



			// Building email headers to be used with the customer order confirmation email
			$email_adminheaders 	= "From: $ecom_hostname	<$email_adminfrom>\n";
			$email_adminheaders 	.= "MIME-Version: 1.0\n";
			$email_adminheaders 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Inserting the order confirmation email details for customer in order_emails table
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $email_voucherid;
			$insert_array['email_to']					= addslashes(stripslashes(strip_tags($Settings_arr['order_confirmationmail'])));
			$insert_array['email_subject']				= addslashes(stripslashes(strip_tags($email_adminsubject)));
			//$insert_array['email_message']				= addslashes(stripslashes($email_admincontent));
			$insert_array['email_headers']				= addslashes(stripslashes($email_adminheaders));
			$insert_array['email_type']					= 'VOUCHER_NOTIFICATION_ADMIN';
			$insert_array['email_was_disabled']			= $email_admindisabled;
			$db->insert_from_array($insert_array,'gift_voucher_emails');
			$curmail_id	= $db->insert_id();
			$email_admincontent = add_line_break($email_admincontent);
			write_email_as_file('vouch',$curmail_id,(stripslashes($email_admincontent)));
			if($email_admindisabled==0)
			{
				$to_arr	= explode(",",$Settings_arr['order_confirmationmail']);
				if($payData["result"] != 5 && $payData["result"] != 9 && $payData["result"] != 3 && $payData["result"] != 4 && $payData["result"] !=11  && $payData["result"] !=20)// case of not 3d secure and not protx vsp
				{
					$sendmail_now = false;
					if($payData['payStatus']=='Paid') // if status is paid then send the mail
					{
						$sendmail_now = true; // what ever the payment status send a mail to admin 
					}

					if($sendmail_now) // check whether mail is to be send now
					{
						$pay_str				= $payData['payStatus'];
						$email_admincontent 	= preg_replace ("/<paystat>(.*)<\/paystat>/", "<paystat>$pay_str</paystat>", $email_admincontent);
						$email_admincontent 	= preg_replace ("/<activatedon>(.*)<\/activatedon>/","<activatedon>$email_activatedon</activatedon>", $email_admincontent);
						$email_admincontent 	= preg_replace ("/<expireson>(.*)<\/expireson>/","<expireson>$email_expireson</expireson>", $email_admincontent);

						// Making the necessary replacements to the email content
						$sr_arr					= array('[start_date]','[end_date]');
						$rp_arr					= array($email_startdate,$email_enddate);
						$email_admincontent		= str_replace($sr_arr,$rp_arr,$email_admincontent);
						$email_admincontent		= str_replace('[payment_status]',$pay_str,$email_admincontent);
						
						if($payData["result"] != 3 and $payData["result"] != 4)
						{
							$email_admincontent = add_line_break($email_admincontent);
							for($i=0;$i<count($to_arr);$i++) // send the order confm mail to as many mail ids which are set
							{
								if ($to_arr[$i]!='')
									mail($to_arr[$i], $email_adminsubject,$email_admincontent, $email_adminheaders);
							}
	
							//Updating the email_sendonce field in gift_voucher_emails table for current mail
							$update_array						= array();
							$update_array['email_sendonce']		= 1;
							//$update_array['email_message']	= addslashes($email_admincontent);
							$update_array['email_lastsenddate']	= 'now()';
							$db->update_from_array($update_array,'gift_voucher_emails',array('email_id'=>$curmail_id));
							write_email_as_file('vouch',$curmail_id,(stripslashes($email_admincontent)));
						}	
					}

				}
			}


			// Get the content of mail to the person to whom the voucher details to be send
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'VOUCHER_DETAILS_CUST'
						LIMIT
							1";

			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 			= $db->fetch_array($ret_template);
				$email_adminfrom		= stripslashes($row_template['lettertemplate_from']);
				$email_adminsubject		= stripslashes($row_template['lettertemplate_subject']);
				$email_admincontent		= stripslashes($row_template['lettertemplate_contents']);
				$email_admindisabled	= stripslashes($row_template['lettertemplate_disabled']);
			}

			// Do the replacement in email template content
			$search_arr			= array(
											'[domain]',
											'[recepient_name]',
											'[cust_name]',
											'[voucher_value]',
											'[voucher_code]',
											'[total_usage]',
											'[message]',
											'[activated_on]',
											'[expires_on]'
										);
			$replace_arr		= array(
											$ecom_hostname,
											$email_vouchertoname,
											$email_name,
											$email_voucher_value,
											$email_voucher_code,
											$email_total_usage,
											$email_vouchertomsg,
											'<activatedon>-</activatedon>',
											'<expireson>-</expireson>'
										);
			$email_admincontent = str_replace($search_arr,$replace_arr,$email_admincontent);



			// Building email headers to be used with the customer order confirmation email
			$email_adminheaders 	= "From: $ecom_hostname	<$email_adminfrom>\n";
			$email_adminheaders 	.= "MIME-Version: 1.0\n";
			$email_adminheaders 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Inserting the email details for customer in gift_voucher_emails table
			$insert_array								= array();
			$insert_array['gift_vouchers_voucher_id']	= $email_voucherid;
			$insert_array['email_to']					= $email_vouchertoemail;
			$insert_array['email_subject']				= add_slash($email_adminsubject);
			//$insert_array['email_message']				= addslashes($email_admincontent);
			$insert_array['email_headers']				= add_slash($email_adminheaders,false);
			$insert_array['email_type']					= 'VOUCHER_DETAILS_CUST';
			$insert_array['email_was_disabled']			= $email_admindisabled;
			$db->insert_from_array($insert_array,'gift_voucher_emails');
			$curmail_id	= $db->insert_id();
			$email_admincontent = add_line_break($email_admincontent);
			write_email_as_file('vouch',$curmail_id,($email_admincontent));
			if($email_admindisabled==0)
			{ 
				$to_arr	= explode(",",$Settings_arr['order_confirmationmail']);
				if($payData["result"] != 5  && $payData["result"] != 9 && $payData["result"] != 3 && $payData["result"] != 4 && $payData["result"] !=11 && $payData["result"] !=20)// case of not 3d secure and not protx vsp
				{
					$sendmail_now = false;
					if($payData['payStatus']=='Paid') // if status is paid then send the mail
					{
						$sendmail_now = true;
					}

					if($sendmail_now) // check whether mail is to be send now
					{
						$pay_str		= $payData['payStatus'];
						$email_admincontent 	= preg_replace ("/<paystat>(.*)<\/paystat>/", "<paystat>$pay_str</paystat>", $email_admincontent);
						$email_admincontent 	= preg_replace ("/<activatedon>(.*)<\/activatedon>/","<activatedon>$email_activatedon</activatedon>", $email_admincontent);
						$email_admincontent 	= preg_replace ("/<expireson>(.*)<\/expireson>/","<expireson>$email_expireson</expireson>", $email_admincontent);
						// Making the necessary replacements to the email content
						$sr_arr			= array('[start_date]','[end_date]');
						$rp_arr			= array($email_startdate,$email_enddate);
						$email_admincontent	= str_replace($sr_arr,$rp_arr,$email_admincontent);
						$email_admincontent	= str_replace('[payment_status]',$pay_str,$email_admincontent);
						$email_admincontent = add_line_break($email_admincontent);
						if($payData["result"] != 3 and $payData["result"] != 4)
						{
							mail($email_vouchertoemail, $email_adminsubject,$email_admincontent, $email_adminheaders);
							//Updating the email_sendonce field in gift_voucher_emails table for current mail
							$update_array						= array();
							$update_array['email_sendonce']		= 1;
							//$update_array['email_message']		= addslashes($email_admincontent);
							$update_array['email_lastsenddate']	= 'now()';
							$db->update_from_array($update_array,'gift_voucher_emails',array('email_id'=>$curmail_id));
							write_email_as_file('vouch',$curmail_id,($email_admincontent));
						}		
					}

				}
			}
		}	// end of email if
		if($payData["result"] == 5) // Payment process 3D Secure
		{
			$order_id	= $voucher_id;
			$pass_typ 	= 'vouch'; // This will be used to identify whether coming from order section or voucher section
			include("includes/3dsecure.php");
		}
		if($payData["result"] == 3 or $payData["result"] == 4 or $payData["result"]== 11 or $payData["result"]== 20)
		{
			echo "
					<script type='text/javascript'>
						window.location = '".$ecom_selfhttp.$ecom_hostname."/voucher_failed.html';
					</script>
					";
			exit;
		}	
		$ret_arr['payMethod']	= $pmethod;
		$ret_arr['payType']		= $ptype;
		$ret_arr['voucher_id'] 	= $voucher_id;
		$ret_arr['payData']		= $payData;
		return $ret_arr;
	}

	/* Function to get a uniquie voucher number*/
function get_UniqueVoucherNumber()
{
	global $Settings_arr;
	global $ecom_selfhttp;
	if ($Settings_arr['voucher_prefix']=='')
		$prefix = '';
	else
		$prefix 	= $Settings_arr['voucher_prefix'];
	$voucher_num	= $prefix.strtoupper(substr(md5(uniqid()),-16));// take md5 or uniquid() and get the last 16 digits
	return strtoupper($voucher_num);
}

// ** Function to get the currency symbol of currently selected currency
	function get_selected_currency_symbol()
	{
		global $ecom_siteid,$db,$sitesel_curr,$default_crr,$default_Currency_arr;
		global $ecom_selfhttp;
		//Get the rate for the current currency
		if($sitesel_curr != $default_Currency_arr['currency_id'])
		{
			$sql_curr  = "SELECT curr_sign_char
							FROM
								general_settings_site_currency
							WHERE
								currency_id=$sitesel_curr
								AND sites_site_id=$ecom_siteid";
			$ret_curr  = $db->query($sql_curr);
			if($db->num_rows($ret_curr))
			{
				$row_curr  = $db->fetch_array($ret_curr);
				$curr_sign = $row_curr['curr_sign_char'];
			}
		}
		else
		{
			$curr_sign = $default_Currency_arr['curr_sign_char'];
		}
		return $curr_sign;
	}
	/* Function to clear the voucher cart */
	function clear_VoucherCart($sess_id)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$del_sql = "DELETE
						FROM
							gift_voucherbuy_cartvalues
						WHERE
							session_id = '".$sess_id."'
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$db->query($del_sql);
		set_session_var('gateway_voucher_id',0);
	}
	function clear_PayonAccountCart($sess_id)
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$del_sql = "DELETE
						FROM
							payonaccount_cartvalues 
						WHERE
							session_id = '".$sess_id."'
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$db->query($del_sql);
		set_session_var('gateway_payonaccount_id',0);
	}
/// Function which send the email which are not send from client area since the payment status was not paid while purchasing the voucher
function send_RequiredVoucherMails($voucher_id,$pstatus='')
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// Get the activation and the expiration date and place it in the main mail contents
	$sql_det = "SELECT date_format(voucher_activatedon,'%d-%b-%Y') activatedon,date_format(voucher_expireson,'%d-%b-%Y') expireson,voucher_paystatus  
					FROM 
						gift_vouchers 
					WHERE 
						voucher_id=$voucher_id 
					LIMIT 
						1";
	$ret_det = $db->query($sql_det);
	if ($db->num_rows($ret_det))
	{
		$row_det = $db->fetch_array($ret_det);
		// Get the mail contents for vouchers to make the modifications which are not yet send
		// Check whether any mails pending for sending for current order and also when email was not disabled
		$sql_email = "SELECT email_id,email_to,email_subject,email_messagepath,email_headers,
						email_type
						FROM
							gift_voucher_emails
						WHERE
							gift_vouchers_voucher_id =$voucher_id 
							AND email_sendonce = 0 
							AND email_type IN('VOUCHER_CONFIRMATION_CUST','VOUCHER_NOTIFICATION_ADMIN','VOUCHER_DETAILS_CUST') 
							AND email_was_disabled=0";
		$ret_email = $db->query($sql_email);
		if ($db->num_rows($ret_email))
		{
			while ($row_email = $db->fetch_array($ret_email))
			{
				//$email_content = stripslashes($row_email['email_message']);
				$email_content = read_email_from_file('vouch',$row_email['email_id']);
				if($pstatus=='')
					$pstatus_name = getpaymentstatus_Name($row_det['voucher_paystatus']);
				else 
					$pstatus_name = $pstatus;
				// Making the necessary replacement to the content of the mail
				$email_content 	= preg_replace ("/<paystat>(.*)<\/paystat>/","<paystat>".$pstatus_name."</paystat>", $email_content);
				$email_content 	= preg_replace ("/<activatedon>(.*)<\/activatedon>/","<activatedon>".$row_det['activatedon']."</activatedon>", $email_content);
				$email_content 	= preg_replace ("/<expireson>(.*)<\/expireson>/","<expireson>".$row_det['expireson']."</expireson>", $email_content);
				//Updating the changes to the content of email to gift_voucher_emails table
				$sql_update = "UPDATE 
									gift_voucher_emails 
								SET 
									email_sendonce=1,
									email_lastsenddate=now() 
								WHERE 
									email_id=".$row_email['email_id']." 
								LIMIT 
									1";
				$db->query($sql_update);
				$email_content = add_line_break($email_content);
				write_email_as_file('vouch',$row_email['email_id'],stripslashes($email_content));
				if ($row_email['email_type']=='VOUCHER_NOTIFICATION_ADMIN')// if order confirmation to admin
				{
					$to_arr	= explode(",",stripslashes($row_email['email_to']));
					for($i=0;$i<count($to_arr);$i++)
					{
						mail($to_arr[$i],stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));
					}
				}
				else // sending mail to customer
				{
					mail(stripslashes($row_email['email_to']),stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));
				}					
			}
		}
	}
}			
// Function which send the email which are not send from client area since the payment status was not paid while placing the order
function send_RequiredOrderMails($order_id,$pstatus='')
{
	global $db,$ecom_siteid,$ecom_activate_invoice,$ecom_hostname;
	global $ecom_selfhttp;
	// Get the activation and the expiration date and place it in the main mail contents
	$sql_det = "SELECT order_paystatus,order_paymentmethod   
					FROM 
						orders  
					WHERE 
						order_id=$order_id 
					LIMIT 
						1";
	$ret_det = $db->query($sql_det);
	if ($db->num_rows($ret_det))
	{
		$row_det = $db->fetch_array($ret_det);
		// Get the mail contents for orders to make the modifications which are not yet send
		// Check whether any mails pending for sending for current order and also when email was not disabled
		$sql_email = "SELECT email_id,email_to,email_subject,email_headers,email_messagepath,
						email_type
						FROM
							order_emails
						WHERE
							orders_order_id =$order_id 
							AND email_sendonce = 0 
							AND email_type IN('ORDER_CONFIRM_CUST','ORDER_CONFIRM_ADMIN') 
							AND email_was_disabled=0";
		$ret_email = $db->query($sql_email);
		if ($db->num_rows($ret_email))
		{
			while ($row_email = $db->fetch_array($ret_email))
			{
				$email_content = read_email_from_file('ord',$row_email['email_id']);;
				//$email_content = stripslashes($row_email['email_message']);
				if($pstatus=='')
					$pstatus_name = getpaymentstatus_Name($row_det['order_paystatus']);
				else 
					$pstatus_name = $pstatus;
				
				if($ecom_siteid==105) // case of puregusto
				{
					if ($row_email['email_type']=='ORDER_CONFIRM_ADMIN')
					{
						if($pstatus_name=='Paid')
						{
							// Check whether the payment is done via sagepayform
							if($row_det['order_paymentmethod']=='PROTX_VSP')
							{
								// get the card type from payment main table
								$sql_pmain = "SELECT order_card_type 
												FROM 
													order_payment_main 
												WHERE 
													orders_order_id = $order_id 
												LIMIT
													1";
								$ret_pmain = $db->query($sql_pmain);
								if($db->num_rows($ret_pmain))
								{
									$row_pmain = $db->fetch_array($ret_pmain);
									if($row_pmain['order_card_type']=='PAYPAL')
									{
										$pstatus_name .= ' - ( Via PAYPAL )';
									}
								}
							}
						}
					}	
				}	
					
				// Making the necessary replacement to the content of the mail
				$email_content 	= preg_replace ("/<paystat>(.*)<\/paystat>/","<paystat>".$pstatus_name."</paystat>", $email_content);
				//Updating the changes to the content of email to gift_voucher_emails table
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$row_email['email_id'],$email_content);
				
				/*$sql_update = "UPDATE 
									order_emails 
								SET 
									email_message='".addslashes(stripslashes($email_content))."',
									email_sendonce=1,
									email_lastsenddate=now() 
								WHERE 
									email_id=".$row_email['email_id']." 
								LIMIT 
									1";
				$db->query($sql_update);*/
				$sql_update = "UPDATE 
									order_emails 
								SET 
									email_sendonce=1,
									email_lastsenddate=now() 
								WHERE 
									email_id=".$row_email['email_id']." 
								LIMIT 
									1";
				$db->query($sql_update);
				if ($row_email['email_type']=='ORDER_CONFIRM_ADMIN')// if order confirmation to admin
				{
						//added 16march2018 latheesh

						$sql_template = "SELECT lettertemplate_from 
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'ORDER_CONFIRM_ADMIN'
						LIMIT
							1";
						$ret_template = $db->query($sql_template);
						if ($db->num_rows($ret_template))
						{
						$row_template 			= $db->fetch_array($ret_template);
						$email_adminfrom		= stripslashes($row_template['lettertemplate_from']);
						}	
						$to_arr	= array_filter(explode(",",stripslashes($row_email['email_to'])));
						$ret_erran = '';
						$ret_erran .= "***before";
						write_email_as_file_error('ord',$order_id."*beforeadminnew",'',$ret_erran);
						$ret_erran  = mail_Phpmaler_admin_new($to_arr,stripslashes($row_email['email_subject']),$email_content,$email_adminfrom,$ecom_hostname,stripslashes($row_email['email_headers']));
						$ret_erran .= "***adminnew";
						 write_email_as_file_error('ord',$order_id."*adminnew",'',$ret_erran);
					/*for($i=0;$i<count($to_arr);$i++)
					{
						mail($to_arr[$i],stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));
					}
					*/ 
				}
				elseif($row_email['email_type']=='ORDER_CONFIRM_CUST')
				{
					$order_invoice_id = 0;
					if($ecom_activate_invoice==1)
					{
						$sql_inv = "SELECT invoice_id 
										FROM 
											order_invoice 
										WHERE 
											orders_order_id = $order_id 
										LIMIT 
											1";
						$ret_inv = $db->query($sql_inv);
						if ($db->num_rows($ret_inv))
						{
							$row_inv = $db->fetch_array($ret_inv);
							$order_invoice_id = $row_inv['invoice_id'];
						}					
					}
					$sql_check = "SELECT lettertemplate_disabled,lettertemplate_from    
										FROM 
											general_settings_site_letter_templates  
										WHERE 
											lettertemplate_letter_type='ORDER_CONFIRM_INVOICE' 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))
					{
						$row_check = $db->fetch_array($ret_check);		
					}
					if($order_invoice_id > 0 and $row_check['lettertemplate_disabled']==0) // case if got the invoice id
					{
						if($ecom_siteid==60)
						{
							//mail(stripslashes($row_email['email_to']),stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));	
							mail_Phpmaler($row_email['email_to'], stripslashes($row_email['email_subject']),$email_content,$row_check['lettertemplate_from'],$ecom_hostname,stripslashes($row_email['email_headers']));

						}
						else
						{
							sendOrderMailWithAttachment($order_id,$order_invoice_id,stripslashes($row_email['email_to']),stripslashes($row_email['email_subject']),$email_content,1);
						}	
					}
					else // case if did not obtained the invoice id
					{
						$ret_errc = '';
						$ret_errc .= "***before";
						write_email_as_file_error('ord',$order_id."*beforecustnew",'',$ret_errc);
						$ret_errc .= "***custnew";
						$ret_errc .= mail_Phpmaler($row_email['email_to'], stripslashes($row_email['email_subject']),$email_content,$row_check['lettertemplate_from'],$ecom_hostname,stripslashes($row_email['email_headers']));
								//{
									write_email_as_file_error('ord',$order_id."*custnew",'',$ret_errc);
								//}
						//mail(stripslashes($row_email['email_to']),stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));
					}
					
				}
				else // sending mail to customer
				{
					//added 16march2018
					$sql_checkA = "SELECT lettertemplate_from    
										FROM 
											general_settings_site_letter_templates  
										WHERE 
											lettertemplate_letter_type='ORDER_CONFIRM_ADMIN' 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_checkA = $db->query($sql_checkA);
					if($db->num_rows($ret_checkA))
					{
						$row_checkA = $db->fetch_array($ret_checkA);		
					}
					$ret_errc = '';
						$ret_errc .= "***before";
						write_email_as_file_error('ord',$order_id."*beforecustnewadmine",'',$ret_errc);
					$ret_errc = mail_Phpmaler($row_email['email_to'], stripslashes($row_email['email_subject']),$email_content,$row_checkA['lettertemplate_from'],$ecom_hostname,stripslashes($row_email['email_headers']));
                     write_email_as_file_error('ord',$order_id."*custnewadmine",'',$ret_errc);
					//mail(stripslashes($row_email['email_to']),stripslashes($row_email['email_subject']),$email_content,stripslashes($row_email['email_headers']));
				}
			}
		}
	}
}	

/* Function to get the title, meta desc and keywords of a selected section  */
function get_PageMetaDetails($ptype,$kw_limit,$metatemplate_arr='',$additional_arr='')
{
	global $db,$ecom_siteid,$ecom_title,$Captions_arr;		
	global $ecom_selfhttp;
	// Initilizing the variables
	$title_table 	= $kw_table = '';
	$direct_table	= 0; 
	$kw_arr = array();
	// ########################################################################################
	// Deciding the tables and additional conditions
	// ########################################################################################
	switch($ptype)
	{
		case 'HOME': 				// Home and default case
			$title_table 				= 'se_home_title';
			$kw_table					= 'se_home_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'home_meta';
			$other_template_field	= '';
		break;
		case 'BEST_SELLER': 		// Best seller page
			$title_table 			= 'se_bestseller_titles';
			$kw_table				= 'se_bestseller_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'STATIC_PAGE': 		// Static pages
			$title_table 			= 'se_static_title';
			$kw_table				= 'se_static_keywords';	
			$add_condition_title	= ' AND static_pages_page_id='.$additional_arr['page_id'].' ';
			$add_condition_kw		= ' AND b.static_pages_page_id='.$additional_arr['page_id'].' ';
			$template_field			= 'static_meta';
			$req_arr				= array('page_id'=>$additional_arr['page_id']);
		break;
		case 'PRODUCT_PAGE': 		// Product details page
			$title_table 			= 'se_product_title';
			$kw_table				= 'se_product_keywords';	
			$add_condition_title	= ' AND products_product_id='.$additional_arr['product_id'].' ';
			$add_condition_kw		= ' AND b.products_product_id='.$additional_arr['product_id'].' ';
			$template_field			= 'product_meta';
			$req_arr				= array('product_id'=>$additional_arr['product_id']);
		break;
		case 'CATEGORY_PAGE': 		// Category details page
			$title_table 			= 'se_category_title';
			$kw_table				= 'se_category_keywords';	
			$add_condition_title	= ' AND product_categories_category_id='.$additional_arr['category_id'].' ';
			$add_condition_kw		= ' AND b.product_categories_category_id='.$additional_arr['category_id'].' ';
			$template_field			= 'category_meta';
			$req_arr				= array('category_id'=>$additional_arr['category_id']);
		break;
		case 'COMBO_DEALS': 		// combo deal page
			$title_table 			= 'se_combo_title';
			$kw_table				= 'se_combo_keywords';	
			$add_condition_title	= ' AND combo_combo_id='.$additional_arr['combo_id'].' ';
			$add_condition_kw		= ' AND b.combo_combo_id='.$additional_arr['combo_id'].' ';
			$template_field			= 'other_meta';
			$req_arr				= array('combo_id'=>$additional_arr['combo_id']);
		break;
		case 'CUSTOMER_REGISTRATION': // customer registration page
			$title_table 			= 'se_registration_title';
			$kw_table				= 'se_registration_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'FORGOT_PASSWORD': 	// customer forgot passsword page
			$title_table 			= 'se_forgotpassword_title';
			$kw_table				= 'se_forgotpassword_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'SITE_HELP': 			// site help page
			$title_table 			= 'se_help_title';
			$kw_table				= 'se_help_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'SITE_FAQ': 			// site faq page
			$title_table 			= 'se_faq_title';
			$kw_table				= 'se_faq_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'SITE_MAP': 			// site map page
			$title_table 			= 'se_sitemap_title';
			$kw_table				= 'se_sitemap_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field		= 'other_meta';
		break;
		case 'SITE_REVIEW': 		// site review page
			$title_table 				= 'se_sitereviews_title';
			$kw_table					= 'se_sitereviews_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field		= 'other_meta';
			
		break;
		case 'PRODUCT_SHELF': 		// product shelf page
			$title_table 				= 'se_shelf_title';
			$kw_table					= 'se_shelf_keywords';	
			$add_condition_title	= ' AND product_shelf_shelf_id='.$additional_arr['shelf_id'].' ';
			$add_condition_kw		= ' AND b.product_shelf_shelf_id='.$additional_arr['shelf_id'].' ';
			$template_field			= 'other_meta';
			$req_arr						= array('shelf_id'=>$additional_arr['shelf_id']);
		break;
		case 'PRODUCT_SHOP': 		// product shop page
			$title_table 				= 'se_shop_title';
			$kw_table					= 'se_shop_keywords';	
			$add_condition_title	= ' AND product_shopbybrand_shopbrand_id='.$additional_arr['shop_id'].' ';
			$add_condition_kw		= ' AND b.product_shopbybrand_shopbrand_id='.$additional_arr['shop_id'].' ';
			$req_arr						= array('shop_id'=>$additional_arr['shop_id']);
			$template_field			= 'other_meta';
		break;
		/*case 'PRODUCT_DETAILS': 	// product details page
			$title_table 			= 'se_product_title';
			$kw_table				= 'se_product_keywords';	
			$add_condition_title	= ' AND products_product_id='.$additional_arr['product_id'].' ';
			$add_condition_kw		= ' AND b.products_product_id='.$additional_arr['product_id'].' ';
			$template_field			= 'product_meta';
			$req_arr				= array('product_id'=>$additional_arr['product_id']);
		break;*/
		case 'SAVED_SEARCH_MAIN': 	// saved search main page
			$title_table 				= 'se_savedsearchmain_title';
			$kw_table					= 'se_savedsearch_main_keywords';	
			$add_condition_title	= '';
			$add_condition_kw		= ' AND b.sites_site_id='.$ecom_siteid;
			$template_field			= 'other_meta';
		break;
		case 'SAVED_SEARCH': 	// saved search
			$title_table 				= 'se_search_title';
			$kw_table					= 'se_search_keyword';	
			$add_condition_title	= ' AND saved_search_search_id = '.$additional_arr['search_id'];
			$add_condition_kw		= ' AND b.saved_search_search_id = '.$additional_arr['search_id'];
			$template_field			= 'search_meta';
			$f_keyword				= $additional_arr['sr_kw'];
		break;
		case 'SEARCH': // case of search page
			$template_field			= 'search_meta';
			$title_table 			= '';
			$kw_table				= '';	
			$add_condition_title	= '';
			$add_condition_kw		= '';
			$ret_arr['desc']		= '';
			$f_keyword				= $additional_arr['sr_kw'];
		break;
		case 'PRODUCTREVIEW_READ_PAGE':
			$title_table 			= 'se_product_review_emailfriend_title';
			$kw_table				= 'se_product_keywords';	
			$add_condition_title	= ' AND products_product_id='.$additional_arr['product_id'].' AND title_type=\'read\''.' ';
			$add_condition_kw		= ' AND b.products_product_id='.$additional_arr['product_id'].' ';
			$template_field			= 'readreview_meta';
			$req_arr				= '';//array('product_id'=>$additional_arr['product_id']);
		break;
		case 'PRODUCTREVIEW_WRITE_PAGE':
			$title_table 			= 'se_product_review_emailfriend_title';
			$kw_table				= 'se_product_keywords';	
			$add_condition_title	= ' AND products_product_id='.$additional_arr['product_id'].' AND title_type=\'write\''.' ';
			$add_condition_kw		= ' AND b.products_product_id='.$additional_arr['product_id'].' ';
			$template_field			= 'writereview_meta';
			$req_arr				= '';//array('product_id'=>$additional_arr['product_id']);
		break;
		case 'PRODUCT_EMAILFRIEND_PAGE':
			$title_table 			= 'se_product_review_emailfriend_title';
			$kw_table				= 'se_product_keywords';	
			$add_condition_title	= ' AND products_product_id='.$additional_arr['product_id'].' AND title_type=\'email\''.' ';
			$add_condition_kw		= ' AND b.products_product_id='.$additional_arr['product_id'].' ';
			$template_field			= 'emailfriend_meta';
			$req_arr				= '';//array('product_id'=>$additional_arr['product_id']);
		break;
	};
	// ########################################################################################
	// Fetching the title and meta description
	// ########################################################################################
	if ($title_table!='')
	{
		$sql_title = "SELECT title,meta_description 
						FROM 
							$title_table 
						WHERE 
							sites_site_id = $ecom_siteid 
							$add_condition_title 
						LIMIT 
							1";	
		$ret_title = $db->query($sql_title);
		if ($db->num_rows($ret_title))
		{
			$row_title 	= $db->fetch_array($ret_title);		
			
			$title 		= stripslashes(trim($row_title['title']));
			
			$desc		= stripslashes(trim($row_title['meta_description']));
			if ($title!='')
			{
				if($ecom_siteid==70 or $ecom_siteid==104)//nationwide or discount mobility
					$ret_arr['title'] 	= $title;
				else
					$ret_arr['title'] 	= ucwords(strtolower(($title)));
			}	
			if ($desc!='')	
				$ret_arr['desc']	= $desc;
		}
	}
	// Fetching the keywords assigned for the page
	if($kw_table!='')
	{
		$new_order = '';
		if($ecom_siteid==109)
		{
			$new_order = ' ORDER BY b.id ASC ';
		}
		
		$sql_kw 	= "SELECT 
							keyword_keyword 
						FROM 
							se_keywords a,$kw_table b 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							$add_condition_kw 
							AND a.keyword_id = b.se_keywords_keyword_id 
							$new_order 
						LIMIT 
							$kw_limit";
		$ret_kw		= $db->query($sql_kw);
		if($db->num_rows($ret_kw))
		{
			
			while ($row_kw = $db->fetch_array($ret_kw))
			{
				if($ecom_siteid==70)//nationwide
					$kw_arr[]	= ((stripslashes(trim($row_kw['keyword_keyword']))));	
				else	
					$kw_arr[]	= ucwords(strtolower(stripslashes(trim($row_kw['keyword_keyword']))));	
			}
		}
	}
	
	// if title is not obtained but obtained the keywords, then show the list of keywords as title
	if ($ret_arr['title']=='' and count($kw_arr))
	{
		$ret_arr['title'] = implode(' | ',$kw_arr);
		if (count($ret_arr))
		{
			$pass_name	  		= getname_Object($ptype,$req_arr);
			if ($pass_name)
				$ret_arr['title'] 	= $pass_name.' | '.implode(' | ',$kw_arr);
			$ret_arr['title'] 		= implode(' | ',$kw_arr);
		}
	}
	
	if(count($kw_arr)==0) // case no keyword set, then randomly pick the keywords
	{
		switch($ptype)
		{
			case 'STATIC_PAGE':
				// Get the name of current static page
				$sql_name = "SELECT title 
										FROM 
											static_pages 
										WHERE 
											page_id = ".$additional_arr['page_id']." 
										LIMIT 
											1";
				$ret_name = $db->query($sql_name);
				if($db->num_rows($ret_name))
				{
					$row_name 	= $db->fetch_array($ret_name);
					if($ecom_siteid==70)//nationwide
						$kw_arr[] 	= ((stripslashes(trim($row_name['title']))));			
					else
						$kw_arr[] 	= ucwords(strtolower(stripslashes(trim($row_name['title']))));			
				}
			break;
			case 'PRODUCT_PAGE':
				// Get the name of current product
				$sql_name = "SELECT product_name 
										FROM 
											products
										WHERE 
											product_id = ".$additional_arr['product_id']." 
										LIMIT 
											1";
				$ret_name = $db->query($sql_name);
				if($db->num_rows($ret_name))
				{
					$row_name 	= $db->fetch_array($ret_name);
					if($ecom_siteid==70)//nationwide
						$kw_arr[] 	= ((stripslashes(trim($row_name['product_name']))));			
					else
						$kw_arr[] 	= ucwords(strtolower(stripslashes(trim($row_name['product_name']))));		
				}
			break;
			case 'CATEGORY_PAGE':
				// Get the name of current category
				$sql_name = "SELECT category_name 
										FROM 
											product_categories 
										WHERE 
											category_id = ".$additional_arr['category_id']." 
										LIMIT 
											1";
				$ret_name = $db->query($sql_name);
				if($db->num_rows($ret_name))
				{
					$row_name 	= $db->fetch_array($ret_name);
					if($ecom_siteid==70)//nationwide
						$kw_arr[] 	= ((stripslashes(trim($row_name['category_name']))));			
					else
						$kw_arr[] 	= ucwords(strtolower(stripslashes(trim($row_name['category_name']))));	
				}
			break;
			
			default:
				$sql_key = "SELECT keyword_keyword 
								FROM 
									se_keywords 
								WHERE 
									sites_site_id = $ecom_siteid 
								ORDER BY 
									RAND() 
								LIMIT 
									$kw_limit";
				$ret_key = $db->query($sql_key);
				if ($db->num_rows($ret_key))
				{
					$kw_arr = array();
					while ($row_key =  $db->fetch_array($ret_key))
					{
						if($ecom_siteid==70)//nationwide
							$kw_arr[]	= ((stripslashes(trim($row_key['keyword_keyword']))));			
						else
							$kw_arr[]	= ucwords(strtolower(stripslashes(trim($row_key['keyword_keyword']))));
					}	
				}
			break;	
		};
	}
	$ret_arr['keywords'] = $kw_arr;	
	// Validate whether title, description and keywords obtained
	// Check whether title obtained or not
	$show_pr = '';
	if($ecom_siteid==104 or $ecom_siteid==106 or $ecom_siteid== 70 or $ecom_siteid==74)
	{
		
		if($ptype=='PRODUCT_PAGE')
		{
			if($additional_arr['product_id'])
			{
				$sql_titleprod = "SELECT * FROM products WHERE product_id=".$additional_arr['product_id']." AND sites_site_id = $ecom_siteid LIMIT 1";	
				$ret_titleprod = $db->query($sql_titleprod);
				if($db->num_rows($ret_titleprod))
				{
					$row_titleprod = $db->fetch_array($ret_titleprod);
					$ret_titleprice = show_Price($row_titleprod,$price_class_arr,'prod_detail',false,4);
					//print_r($ret_titleprice); 
					$base_p = $ret_titleprice['base_price'];
					$disc_p = $ret_titleprice['discounted_price'];
					
					if($ecom_siteid==70)
					{
						$base_p_arr 	= explode('(',$ret_titleprice['base_price']);
						$base_p 		= $base_p_arr[0];
						$disc_p_arr 	= explode('(',$ret_titleprice['discounted_price']);
						$disc_p 		= $disc_p_arr[0];
						
					}
					
					$def_catid = $row_titleprod['product_default_category_id'];
					$sql_cat = "SELECT category_name FROM product_categories WHERE category_id=$def_catid and sites_site_id = $ecom_siteid LIMIT 1";
					$ret_cat = $db->query($sql_cat);
					$titl_catname = '';
					if($db->num_rows($ret_cat) and $ecom_siteid!= 70)
					{
						$row_cat = $db->fetch_array($ret_cat);
						$titl_catname = stripslashes($row_cat['category_name']).' | ';
					}
					if ($disc_p !='')
					{
						//$show_pr = $disc_p.$titl_catname;
						$show_pr = $titl_catname.$disc_p;
					}
					else
					{
						//$show_pr = $base_p.$titl_catname;
						$show_pr = $titl_catname.$base_p;
					}
					//$ret_arr['title'] .= " | ".$show_pr;
				}
			}
		}	
	}
	
	if ($ret_arr['title']=='')
	{
		
		if($ptype=='SAVED_SEARCH' or $ptype =='SEARCH')
		{
			if($f_keyword)
			{
				if($ecom_siteid==70)//nationwide
					$ret_arr['title'] = (($f_keyword));
				else
				{	
					$searchadd = "";
					if($ecom_siteid==102)
					{
				     $searchadd = $Captions_arr['COMMON']['SEARCHEDFOR']." ";
				    }
					$ret_arr['title'] = $searchadd.ucwords(strtolower($f_keyword));
				}	
			}	
			else
			{
				if($ecom_siteid==70)//nationwide
					$ret_arr['title'] = (($ecom_title));
				else
					$ret_arr['title'] = ucwords(strtolower($ecom_title));
			}	
		}
		else
		{
			/*if($ecom_siteid==70)//nationwide
				$ret_arr['title'] = (($ecom_title)); // setting the site title */
			//else
			
			if($ecom_siteid==104 or $ecom_siteid==106 or $ecom_siteid== 70 or $ecom_siteid==74)
			{
				$ret_arr['title'] = $show_pr; 
			}
			else
				$ret_arr['title'] = ucwords(strtolower($ecom_title)); // setting the site title
			
			
		}	
		//$ret_arr['title'] = ucwords(strtolower($ecom_title)); // setting the site title
		if (count($req_arr))
		{
			$pass_name	  		= getname_Object($ptype,$req_arr);
			if($ecom_siteid==70)
			{
				if(trim($pass_name)!='')
				{
					if($ret_arr['title']!='')
					{
						$ret_arr['title'] 	= $pass_name.' | '.$ret_arr['title'];
					}
					else
					{
						$ret_arr['title'] 	= $pass_name;
					}	
				}	
			}
			else
			{
				$ret_arr['title'] 	= $pass_name.' | '.$ret_arr['title'];
			}	
			
			//if($_SERVER['REMOTE_ADDR']=='182.72.159.170'){ echo "Title".$ret_arr['title'];}	
		}	
	}
	else
	{
		if($ecom_siteid==104 or $ecom_siteid==106 or $ecom_siteid== 70 or $ecom_siteid==74)
		{
			if($show_pr!='')
			{
				$ret_arr['title'] .= " | ".$show_pr; 
			}	
		}
	}
	
	if ($ret_arr['desc']=='')
	{
		$sql_metatemplate = "SELECT $template_field   
										FROM 
											se_meta_description 
										WHERE 
											sites_site_id=$ecom_siteid 
										LIMIT 
											1";
		$ret_metatemplate = $db->query($sql_metatemplate);
		if($db->num_rows($ret_metatemplate))
		{
			$metatemplate_arr = $db->fetch_array($ret_metatemplate);
		}
		$meta_desc 		= $metatemplate_arr[$template_field];
		if($ptype=='PRODUCTREVIEW_READ_PAGE' or $ptype=='PRODUCTREVIEW_WRITE_PAGE' or $ptype=='PRODUCT_EMAILFRIEND_PAGE')
		{
			if($meta_desc=='')
			{
				
				$sql_metatemplate1 = "SELECT product_meta    
									FROM 
										se_meta_description 
									WHERE 
										sites_site_id=$ecom_siteid 
									LIMIT 
										1";
				$ret_metatemplate1 = $db->query($sql_metatemplate1);
				if($db->num_rows($ret_metatemplate1))
				{
					$metatemplate_arr1 = $db->fetch_array($ret_metatemplate1);
				}	
				$meta_desc 		= $metatemplate_arr1['product_meta'];	
			}
		}
		/*if($meta_desc	=='')
		{
			if ($other_template_field!='')
			{
				$meta_desc 		= $metatemplate_arr[$other_template_field];	
			}	
		}	*/
		if ($meta_desc!='')
		{
			if (count($ret_arr['keywords']))
			{
				if($f_keyword)
				{
					$first_keyword	= $f_keyword;
					$keywords		= $first_keyword.', '.implode(', ',$ret_arr['keywords']);			
				}	
				else
				{
					$first_keyword  = $ret_arr['keywords'][0];
					$keywords		= implode(', ',$ret_arr['keywords']);			
				}	
				
				$desc 			= stripslashes($meta_desc);
				$sr_arr			= array('[title]','[keywords]','[first_keyword]');
				//$rp_arr			= array($ret_arr['title'],$keywords,$first_keyword);
				$rp_arr			= array($ecom_title,$keywords,$first_keyword);
				$ret_arr['desc'] = str_replace($sr_arr,$rp_arr,$desc);
			}
		}
	}
	return $ret_arr;
}
function get_othermetadesc($desc,$home_desc,$keywords_arr)
{
	global $ecom_selfhttp;
	$desc_ret = '';
	if (is_array($keywords_arr) and $desc!='')
	{
		if(count($keywords_arr))
		{
			$first_keyword  	= $keywords_arr[0];
			$keywords			= implode(',',$keywords_arr);			
			$sr_arr				= array('[title]','[keywords]','[first_keyword]');
			$rp_arr				= array($ret_arr['title'],$keywords,$first_keyword);
			$desc_ret		 	= str_replace($sr_arr,$rp_arr,$desc);
		}	
	}
	else
		$desc_ret = $hode_desc;
	return $desc_ret;	
}
// Function to get the name of product, static page or shelf combo etc to be used in the title of page
function getname_Object($mod,$req_arr)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	switch($mod)
	{
		case 'STATIC_PAGE': 		// Static pages
			$sql_name = "SELECT title as retname
							FROM 
								static_pages  
							WHERE 
								page_id = ".$req_arr['page_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
		case 'PRODUCT_PAGE': // product details page
			$sql_name = "SELECT product_name as retname
							FROM 
								products 
							WHERE 
								product_id = ".$req_arr['product_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
		case 'CATEGORY_PAGE': // category details page
			$sql_name = "SELECT category_name as retname
							FROM 
								product_categories 
							WHERE 
								category_id = ".$req_arr['category_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
		case 'COMBO_DEALS': 		// combo deal page
			$sql_name = "SELECT combo_name as retname
							FROM 
								combo 
							WHERE 
								combo_id = ".$req_arr['combo_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
		case 'PRODUCT_SHELF': 		// Product Shelf
			$sql_name = "SELECT shelf_name as retname
							FROM 
								product_shelf  
							WHERE 
								shelf_id = ".$req_arr['shelf_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
		case 'PRODUCT_SHOP': 		// Product Shop
			$sql_name = "SELECT shopbrand_name as retname
							FROM 
								product_shopbybrand   
							WHERE 
								shopbrand_id = ".$req_arr['shop_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_name = $db->query($sql_name);
		break;
	};
	if($db->num_rows($ret_name))
	{
		$row_name = $db->fetch_array($ret_name);
		return stripslashes($row_name['retname']);
	}
}
////########################
//Function to get the image by Image ID and type
###############################		
function getImageByID($imageid,$type='thumb')
{
global $ecom_siteid,$db;
global $ecom_selfhttp;
	switch($type){
		case 'big':
		$sql_image = "SELECT image_bigpath FROM images WHERE sites_site_id = ".$ecom_siteid." AND image_id=".$imageid;
		$ret_image = $db->query($sql_image);
		$image	   = $db->fetch_array($ret_image);
		return $image_path = $image['image_bigpath'];
		break;
		case 'thumb':
		$sql_image = "SELECT image_thumbpath FROM images WHERE sites_site_id = ".$ecom_siteid." AND image_id=".$imageid;
		$ret_image = $db->query($sql_image);
		$image	   = $db->fetch_array($ret_image);
		return $image_path = $image['image_thumbpath'];
		break;
	}
}




/* Function to get the display logic to be included for alternate products */
function get_AlternateProductDetailsString($alt_prods,$rate,$symbol)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$alt_str = '';
	// Case if alternate products selected while cancelling the order
	if($alt_prods!='')
	{
		$prods_arr = explode('~',$alt_prods);
		$prods_str = implode(",",$prods_arr);
		// Get the details of alternate products
		$sql_prod = "SELECT product_id,product_name,product_webprice,product_discount,
							product_discount_enteredasval
						FROM
							products
						WHERE
							product_id IN ($prods_str) ";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{

			$alt_str = '<table width="100%" border="0" cellspacing="1" cellpadding="1">
			 				<tr>
							<td colspan="4" align="left"><strong>Alternate Products</strong></td>
						  </tr>
						  <tr>
							<td width="4%"><strong>#</strong></td>
							<td width="46%"><strong>Product Name </strong></td>
							<td width="34%" align="right"><strong>Price</strong></td>
							<td width="16%" align="right"><strong>Discount</strong></td>
						  </tr>';
			$srno = 1;
			while($row_prod = $db->fetch_array($ret_prod))
			{
				$alt_str .='<tr>
								<td>'.$srno++.'</td>
								<td>'.stripslashes($row_prod['product_name']).'</td>
								<td align="right">'.print_price_selected_currency($row_prod['product_webprice'],$rate,$symbol,true).'</td>
								<td align="right">';
				if($row_prod['product_discount_enteredasval']==1)
					$alt_str .= print_price_selected_currency($row_prod['product_discount'],$rate,$symbol,true);
				else
					$alt_str .= $row_prod['product_discount'].'%';
				$alt_str .='</td>
							</tr>';
			}
			$alt_str .= '</table>';
		}
	}
	return $alt_str;
}
/* Function to get the display logic for product details to be included in the mail */
function get_ProductsInOrdersForMail($order_id,$row_ords,$detail_arr='')
{
	global $db,$ecom_siteid,$ecom_hostname,$Captions_arr;
	global $ecom_selfhttp;
	$totals_req		= true;
	$qty_req 		= false;
	$det_arr 		= $detail_arr['prods'];
	$detqty_arr		= $detail_arr['qtys'];
	if (is_array($det_arr)) // Check whether only selected products are to be displayed
	{
		if(count($det_arr))
		{
			$additional_condition 	= " AND orderdet_id IN (".implode(",",$det_arr).") ";
			$totals_req				= false;
		}
	}
	if (is_array($detqty_arr)) // check whether quantity array exists
	{
		if(count($detqty_arr))
		{
			$qty_req				= true;
		}
	}

	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	// ##############################################################################
	// Product Details
	// ##############################################################################
	$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
	$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

	$prod_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	$sql_prods  = "SELECT orderdet_id,product_name,products_product_id,order_qty,product_soldprice,order_retailprice,order_discount,
							order_discount_type,order_rowtotal
					FROM
						order_details
					WHERE
						orders_order_id = $order_id
						$additional_condition 
					ORDER BY order_sort ASC,orderdet_id ASC";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
		if($totals_req==true)
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%" '.$style_head.'>'.$Captions_arr['CART']['CART_ITEM'].'</td>
								<td align="left" width="20%" '.$style_head.'>'.$Captions_arr['CART']['CART_PRICE'].'</td>
								<td align="left" width="15%" '.$style_head.'>'.$Captions_arr['CART']['CART_DISCOUNT'].'</td>
								<td align="left" width="25%" '.$style_head.'>'.$Captions_arr['CART']['CART_QTY'].'</td>
								<td align="left" width="25%" '.$style_head.'>'.$Captions_arr['CART']['CART_TOTAL'].'</td>
							</tr>';
		}
		elseif ($qty_req==true)
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%" '.$style_head.'>'.$Captions_arr['CART']['CART_ITEM'].'</td>
								<td align="left" width="25%" '.$style_head.'>'.$Captions_arr['CART']['CART_QTY'].'</td>
								<td align="left" colspan="3" '.$style_head.'>&nbsp;</td>
							</tr>';
		}
		else
		{
			$prod_str	.= '<tr>
								<td align="left" width="40%" '.$style_head.'>'.$Captions_arr['CART']['CART_ITEM'].'</td>
								<td align="left" colspan="4" '.$style_head.'>&nbsp;</td>
							</tr>';
		}
		while ($row_prods = $db->fetch_array($ret_prods))
		{
		     $prodName = strip_url(stripslashes($row_prods['product_name']));
			 $prodId = $row_prods['products_product_id'];
			 $productPageUrlHash = $ecom_selfhttp.$ecom_hostname."/".$prodName."-p$prodId.html";
			$qty = ($totals_req)?stripslashes($row_prods['order_qty']):$detqty_arr[$row_prods['orderdet_id']];
			if($totals_req==true)
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%" '.$style_desc.'><a href='.$productPageUrlHash.' '.$style_desc_prodname.'>'.stripslashes($row_prods['product_name']).'</a></td>
								<td align="left" width="15%" '.$style_desc.'>'.print_price_selected_currency($row_prods['product_soldprice'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								<td align="left" width="20%" '.$style_desc.'>'.print_price_selected_currency($row_prods['order_discount'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								<td align="left" width="15%" '.$style_desc.'>'.$qty.'</td>
								<td align="right" width="20%" '.$style_desc.'>'.print_price_selected_currency($row_prods['order_rowtotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}
			elseif ($qty_req==true)
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%"><a href='.$productPageUrlHash.' '.$style_desc_prodname.'>'.stripslashes($row_prods['product_name']).'</a></td>
								<td align="left" width="15%" '.$style_desc.'>'.$qty.'</td>
								<td align="left" colspan="3" '.$style_desc.'>&nbsp;</td>
								</tr>';
			}
			else
			{
				$prod_str	.= '<tr>
								<td align="left" width="30%"><a href='.$productPageUrlHash.' '.$style_desc_prodname.'>'.stripslashes($row_prods['product_name']).'</a></td>
								<td align="left" colspan="4" '.$style_desc.'>&nbsp;</td>
								</tr>';
			}
			// Call function to decide whether grid display is to be used or not.
			    $check_arr = is_grid_display_enabled_prod($row_prods['products_product_id']);
				//if($check_arr['enabled']==true)
				{
					$sql_prod_grid = "SELECT product_intensivecode,product_metrodentcode,product_isocode FROM products WHERE product_id = ".$row_prods['products_product_id']." AND sites_site_id =".$ecom_siteid." LIMIT 1";
					$ret_prod_grid = $db->query($sql_prod_grid);
					if($db->num_rows($ret_prod_grid)>0)
					{
					 $row_prod_grid = $db->fetch_array($ret_prod_grid);
						 if($row_prod_grid['product_intensivecode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['INTENSIVE'].': '.stripslashes($row_prod_grid['product_intensivecode']).'</td>
										</tr>';
						 }	
						  if($row_prod_grid['product_metrodentcode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['METRODENT'].': '.stripslashes($row_prod_grid['product_metrodentcode']).'</td>
										</tr>';
						 }	
						  if($row_prod_grid['product_isocode']!='')
						 {
							  $prod_str	.= '<tr>
											<td align="left" colspan="'.$colspan.'" '.$style_head.'>'.$Captions_arr['CART']['ISOCODE'].': '.stripslashes($row_prod_grid['product_isocode']).'</td>
										</tr>';
						 }				
					}
				}	
				//else			
				if($check_arr['enabled']==false)
				{					
					// Check whether any variables exists for current product in order_details_variables
					$sql_var = "SELECT var_name,var_value,var_id 
									FROM
										order_details_variables
									WHERE
										orders_order_id = $order_id
										AND order_details_orderdet_id =".$row_prods['orderdet_id'];
					$ret_var = $db->query($sql_var);
					if ($db->num_rows($ret_var))
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							$prod_str	.= '<tr>
											<td align="left" colspan="5" style="padding-left:10px"><strong>'.stripslashes($row_var['var_name']).':</strong> '.stripslashes($row_var['var_value']).'</td>
											</tr>';
										if($ecom_siteid==103)
										{	
											if (trim($row_var['var_value'])!='')
											{
												// get the var_value_id for current value for the current variable 
												$sql_getvarvals = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id = ".$row_var['var_id']." AND var_value = '".$row_var['var_value']."' LIMIT 1";
												$ret_getvarvals = $db->query($sql_getvarvals);
												if($db->num_rows($ret_getvarvals))
												{
													$row_getvarvals = $db->fetch_array($ret_getvarvals);
													$sql_getmpn = "SELECT var_mpn FROM product_variable_data WHERE var_value_id = ".$row_getvarvals['var_value_id']." LIMIT 1";
													$ret_getmpn = $db->query($sql_getmpn);
													if($db->num_rows($ret_getmpn))
													{
														$row_getmpn = $db->fetch_array($ret_getmpn);
														if(trim($row_getmpn['var_mpn'])!='')
														{
														//$mpn = ' <br>'.$Captions_arr['CART']['CART_MPN'].stripslashes($row_getmpn['var_mpn']);
														$prod_str	.= '<tr>
														<td align="left" colspan="5" style="padding-left:10px"><strong>'.$Captions_arr['CART']['CART_MPN'].'</strong> '.stripslashes($row_getmpn['var_mpn']).'</td>
														</tr>';
														}
													}
												}	
											}
									    }				
						}
					}
					// Check whether any variables messages exists for current product in order_details_messages
					$sql_msg = "SELECT message_caption,message_value
									FROM
										order_details_messages
									WHERE
										orders_order_id = $order_id
										AND order_details_orderdet_id =".$row_prods['orderdet_id'];
					$ret_msg = $db->query($sql_msg);
					if ($db->num_rows($ret_msg))
					{
						while ($row_msg = $db->fetch_array($ret_msg))
						{
							$prod_str	.= '<tr>
											<td align="left" colspan="5" style="padding-left:10px"><strong>'.stripslashes($row_msg['message_caption']).':</strong> '.stripslashes($row_msg['message_value']).'</td>
											</tr>';
						}
					}
				}
		}
		if($totals_req==true)
		{
			// ##################################################################################
			// Building order totals
			// ##################################################################################
			// subtotal
			$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2" '.$style_head.'>'.$Captions_arr['CART']['CART_TOTPRICE'].'</td>
									<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_subtotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			// giftwrap total and delivery type total and tax total
			if($row_ords['order_giftwraptotal']>0)
			{
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2" '.$style_head.'>Gift Wrap Total</td>
									<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_giftwraptotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
			}
			if($row_ords['order_deliverytotal']>0)
			{
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2" '.$style_head.'>Delivery Total</td>
									<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_deliverytotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}
			if($row_ords['order_tax_total']>0)
			{
				$new_tot_tax_str = 'Total Tax';
				if($ecom_siteid == 87) // case of sumpandpump
					$new_tot_tax_str = 'VAT @ 20%';
				$prod_str	.= '<tr>
									<td align="right" width="50%" colspan="2" '.$style_head.'>'.$new_tot_tax_str.'</td>
									<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_tax_total'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
								</tr>';
			}

			// Customer / Corporate discount
			if ($row_ords['order_customer_discount_value']>0)
			{
				if ($row_ords['order_customer_or_corporate_disc']=='CUST')
				{
					if($row_ords['order_customer_discount_type']=='Disc_Group')
					$caption = 'Customer Group Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					else
					$caption = 'Customer Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					$caption_val = $row_ords['order_customer_discount_value'];
					$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="2" '.$style_head.'>'.$caption.'</td>
										<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($caption_val,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
				}
				else // case of corporate discount
				{
					$caption = 'Corporate Discount ('.$row_ords['order_customer_discount_percent'].'%)';
					$caption_val = $row_ords['order_customer_discount_value'];
					$prod_str	.= '<tr>
										<td align="right" width="50%" colspan="2" '.$style_head.'>'.$caption.'</td>
										<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($caption_val,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
									</tr>';
				}
			}

			if($row_ords['gift_vouchers_voucher_id'])
			{
				// Get the gift voucher details
				$sql_voucher = "SELECT voucher_value_used
									FROM
										order_voucher
									WHERE
										orders_order_id = $order_id
									LIMIT
										1";
				$ret_voucher = $db->query($sql_voucher);
				if ($db->num_rows($ret_voucher))
				{
					$row_voucher 	= $db->fetch_array($ret_voucher);
					$prod_str	.= '<tr><td align="left" width="50%" colspan="2" '.$style_head.'>Gift Voucher Discount</td><td align="left" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_voucher['voucher_value_used'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
				}

			}
			elseif($row_ords['promotional_code_code_id'])
			{
				// Get the promotional code details
				$sql_prom = "SELECT code_number,code_lessval,code_type
									FROM
										order_promotional_code
									WHERE
										orders_order_id = $order_id
									LIMIT
										1";
				$ret_prom = $db->query($sql_prom);
				if ($db->num_rows($ret_prom))
				{
					$row_prom 	= $db->fetch_array($ret_prom);
					if ($row_prom['code_type']!='product') // show only if not of type 'product' if type is product discount will be shown with product listing
					{
						$prod_str	.= '<tr><td align="right" width="50%" colspan="2" '.$style_head.'>Promotional Code Discount</td><td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_prom['code_lessval'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
					}
				}

			}
			if($row_ords['order_bonuspoint_discount']>0)
			{
				$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2" '.$style_head.'>Bonus Points Discount</td>
								<td align="right" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_bonuspoint_discount'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			}
			// Total Final Cost
			$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2" '.$style_head.'>Grand Total</td>
								<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_totalprice'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			// Check whether product deposit exists
			if($row_ords['order_deposit_amt']>0)
			{
				$dep_less = $row_ords['order_totalprice'] - $row_ords['order_deposit_amt'];
				$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2" '.$style_head.'>Less Product Deposit Amount</td>
								<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($dep_less,$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
				$prod_str	.= '<tr>
								<td align="right" width="50%" colspan="2" '.$style_head.'>Amount Payable Now</td>
								<td align="right" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_deposit_amt'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td>
							</tr>';
			}
		}
		$prod_str		.= '</table>';
	}
	return $prod_str;
}

/* Function to send order mail */
function save_and_send_OrderMail($mail_type,$ord_arr)
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	// Check the mail type
	switch($mail_type)
	{
		case 'cancel': // Order cancellation mail
		// Get the content of email template
		 $sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
								FROM
									general_settings_site_letter_templates
								WHERE
									sites_site_id = $ecom_siteid
									AND lettertemplate_letter_type = 'ORDER_CANCELLATION_CUST'
								LIMIT
									1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 		= $db->fetch_array($ret_template);
			$email_from			= stripslashes($row_template['lettertemplate_from']);
			$email_subject		= stripslashes($row_template['lettertemplate_subject']);
			$email_content		= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);


			$note		= $ord_arr['note'];
			$alt_prod	= $ord_arr['alt_prods'];
			// Get cancel date from orders table
			$sql_ords = "SELECT DATE_FORMAT(order_cancelled_on,'%d-%b-%Y') canceldate
								FROM
									orders
								WHERE
									order_id =".$ord_arr['order_id']."
								LIMIT
									1";
			$ret_ords = $db->query($sql_ords);
			if ($db->num_rows($ret_ords))
			{
				$row_ords = $db->fetch_array($ret_ords);
			}
			// Calling function to get the product details of current order
			$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$ord_arr);
			$cname					= stripslashes($ord_arr['order_custtitle']).stripslashes($ord_arr['order_custfname'])." ".stripslashes($ord_arr['order_custsurname']);
			$search_arr	= array (
			'[cust_name]',
			'[domain]',
			'[orderid]',
			'[cancel_date]',
			'[product_details]',
			'[alternate_prods]',
			'[cancel_reason]'
			);
			$replace_arr= array(
			$cname,
			$ecom_hostname,
			$ord_arr['order_id'],
			$row_ords['canceldate'],
			$prod_details_str,
			$ord_arr['alt_prods'],
			$ord_arr['reason']
			);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Building email headers to be used with the mail
			$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
			$email_headers 	.= "MIME-Version: 1.0\n";
			$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Saving the email to order_emails table
			$insert_array								= array();
			$insert_array['orders_order_id']		= $ord_arr['order_id'];
			$insert_array['email_to']				= addslashes(stripslashes(strip_tags($ord_arr['order_custemail'])));
			$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
			//$insert_array['email_message']		= addslashes(stripslashes($email_content));
			$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
			$insert_array['email_type']			= 'ORDER_CANCELLATION_CUST';
			$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
			$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
			$db->insert_from_array($insert_array,'order_emails');
			$email_insertid = $db->insert_id();
			$email_content = add_line_break($email_content);
			write_email_as_file('ord',$email_insertid,stripslashes($email_content));
			if($email_disabled==0)// check whether mail sending is disabled
			{
				mail($ord_arr['order_custemail'], $email_subject,$email_content, $email_headers); 
				 
			}
		}
		
		break;
		case 'Paid': // Order payment success
		case 'Pay_Failed': // Order payment failed

		if($mail_type=='Paid')
		$letter_type = 'ORDER_PAYMENT_AUTHORIZE';
		else
		$letter_type = 'ORDER_PAYMENT_FAILED';

		// Get the content of email template
		$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
								FROM
									general_settings_site_letter_templates
								WHERE
									sites_site_id = $ecom_siteid
									AND lettertemplate_letter_type = '".$letter_type."'
								LIMIT
									1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 		= $db->fetch_array($ret_template);
			$email_from			= stripslashes($row_template['lettertemplate_from']);
			$email_subject		= stripslashes($row_template['lettertemplate_subject']);
			$email_content		= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);


			$note		= $ord_arr['note'];
			// Get cancel date from orders table
			$sql_ords = "SELECT DATE_FORMAT(order_paystatus_changed_manually_on,'%d-%b-%Y') order_paystatus_changed_manually_on
									FROM
										orders
									WHERE
										order_id =".$ord_arr['order_id']."
									LIMIT
										1";
			$ret_ords = $db->query($sql_ords);
			if ($db->num_rows($ret_ords))
			{
				$row_ords = $db->fetch_array($ret_ords);
			}
			// Calling function to get the product details of current order
			$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$ord_arr);
			$cname					= stripslashes($ord_arr['order_custtitle']).stripslashes($ord_arr['order_custfname'])." ".stripslashes($ord_arr['order_custsurname']);
			$search_arr	= array (
			'[cust_name]',
			'[domain]',
			'[orderid]',
			'[auth_date]',
			'[fail_date]',
			'[product_details]',
			'[reason]'
			);
			$replace_arr= array(
			$cname,
			$ecom_hostname,
			$ord_arr['order_id'],
			$row_ords['order_paystatus_changed_manually_on'],
			$row_ords['order_paystatus_changed_manually_on'],
			$prod_details_str,
			$ord_arr['reason']
			);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Building email headers to be used with the mail
			$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
			$email_headers 	.= "MIME-Version: 1.0\n";
			$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Saving the email to order_emails table
			$insert_array						= array();
			$insert_array['orders_order_id']	= $ord_arr['order_id'];
			$insert_array['email_to']			= addslashes(stripslashes(strip_tags($ord_arr['order_custemail'])));
			$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
			//$insert_array['email_message']		= addslashes(stripslashes($email_content));
			$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
			$insert_array['email_type']			= $letter_type;
			$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
			$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
			$db->insert_from_array($insert_array,'order_emails');
			$email_insertid = $db->insert_id();
			$email_content = add_line_break($email_content);
			write_email_as_file('ord',$email_insertid,stripslashes($email_content));
			if($email_disabled==0)// check whether mail sending is disabled
			{
				mail($ord_arr['order_custemail'], $email_subject,$email_content, $email_headers);
			}

		}
		break;
		case 'DESPATCHED': // Ordered item despatched
		$order_id						= $ord_arr['order_id'];
		$despatch_number				= $ord_arr['despatch_id'];
		$despatch_note					= $ord_arr['despatch_note'];
		$despatch_arr					= $ord_arr['despatched_prods'];
		$despatchqty_arr				= $ord_arr['despatched_qtys'];
		$despatch_completely			= $ord_arr['completly_despatched'];

		// Get the email content
		$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
								FROM
									general_settings_site_letter_templates
								WHERE
									sites_site_id = $ecom_siteid
									AND lettertemplate_letter_type = 'ORDER_DESPATCHED'
								LIMIT
									1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 		= $db->fetch_array($ret_template);
			$email_from			= stripslashes($row_template['lettertemplate_from']);
			$email_subject		= stripslashes($row_template['lettertemplate_subject']);
			$email_content		= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
			// Get some details from orders table for current order
			$sql_ords = "SELECT order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,
									order_custemail,order_currency_convertionrate,order_currency_symbol
					FROM
						orders
					WHERE
						order_id = $order_id
					LIMIT
						1";
			$ret_ords = $db->query($sql_ords);
			if ($db->num_rows($ret_ords))
			{
				$row_ords = $db->fetch_array($ret_ords);
			}
			// Calling function to get the product details of current order
			$pass_arr['prods']		= $despatch_arr;
			$pass_arr['qtys']		= $despatchqty_arr;
			$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ords,$pass_arr);
			$cname					= stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname'])." ".stripslashes($row_ords['order_custsurname']);
			$search_arr	= array (
			'[cust_name]',
			'[domain]',
			'[orderid]',
			'[despatch_date]',
			'[product_details]',
			'[note]',
			'[despatch_id]'
			);
			$replace_arr= array(
			$cname,
			$ecom_hostname,
			$row_ords['order_id'],
			date('d-M-Y'),
			$prod_details_str,
			$despatch_note,
			$despatch_number
			);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Building email headers to be used with the mail
			$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
			$email_headers 	.= "MIME-Version: 1.0\n";
			$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Saving the email to order_emails table
			$insert_array						= array();
			$insert_array['orders_order_id']	= $ord_arr['order_id'];
			$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ords['order_custemail'])));
			$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
			//$insert_array['email_message']		= addslashes(stripslashes($email_content));
			$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
			$insert_array['email_type']			= 'ORDER_DESPATCHED';
			$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
			$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
			$db->insert_from_array($insert_array,'order_emails');
			$email_insertid = $db->insert_id();
			$email_content = add_line_break($email_content);
			write_email_as_file('ord',$email_insertid,stripslashes($email_content));
			if($email_disabled==0)// check whether mail sending is disabled
			{
				mail($row_ords['order_custemail'], $email_subject,$email_content, $email_headers);
			}
		}
		break;
		case 'REFUNDED': // Refunding
		$order_id						= $ord_arr['order_id'];
		$refund_amt						= print_price_selected_currency($ord_arr['refund_amt'],$ord_arr['order_currency_convertionrate'],$ord_arr['order_currency_symbol'],true);
		$refund_note					= $ord_arr['refund_note'];
		$refund_arr						= $ord_arr['refunded_prods'];


		// Get the email content
		$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
								FROM
									general_settings_site_letter_templates
								WHERE
									sites_site_id = $ecom_siteid
									AND lettertemplate_letter_type = 'ORDER_REFUNDED'
								LIMIT
									1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 		= $db->fetch_array($ret_template);
			$email_from			= stripslashes($row_template['lettertemplate_from']);
			$email_subject		= stripslashes($row_template['lettertemplate_subject']);
			$email_content		= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
			// Get some details from orders table for current order
			/*$sql_ords = "SELECT order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,
									order_custemail,order_currency_convertionrate,order_currency_symbol
							FROM
								orders
							WHERE
								order_id = $order_id
							LIMIT
								1";*/
			$sql_ords = "SELECT *
							FROM
								orders
							WHERE
								order_id = $order_id
							LIMIT
								1";					
			$ret_ords = $db->query($sql_ords);
			if ($db->num_rows($ret_ords))
			{
				$row_ords = $db->fetch_array($ret_ords);
			}
			// Calling function to get the product details of current order
			$pass_arr['prods']		= $refund_arr;
			$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ords,$pass_arr);
			$cname					= stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname'])." ".stripslashes($row_ords['order_custsurname']);
			$search_arr	= array (
			'[cust_name]',
			'[domain]',
			'[orderid]',
			'[refund_date]',
			'[product_details]',
			'[note]',
			'[refund_amt]'
			);
			$replace_arr= array(
			$cname,
			$ecom_hostname,
			$row_ords['order_id'],
			date('d-M-Y'),
			$prod_details_str,
			$refund_note,
			$refund_amt
			);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Building email headers to be used with the mail
			$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
			$email_headers 	.= "MIME-Version: 1.0\n";
			$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

			// Saving the email to order_emails table
			$insert_array						= array();
			$insert_array['orders_order_id']	= $ord_arr['order_id'];
			$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ords['order_custemail'])));
			$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
			//$insert_array['email_message']		= addslashes(stripslashes($email_content));
			$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
			$insert_array['email_type']			= 'ORDER_REFUNDED';
			$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
			$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
			$db->insert_from_array($insert_array,'order_emails');
			$email_insertid = $db->insert_id();
			$email_content = add_line_break($email_content);
			write_email_as_file('ord',$email_insertid,stripslashes($email_content));
			if($email_disabled==0)// check whether mail sending is disabled
			{
				mail($row_ords['order_custemail'], $email_subject,$email_content, $email_headers);
			}
		}
		break;
		case 'DEFERRED_RELEASE':
			// Get the content of email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
									FROM
										general_settings_site_letter_templates
									WHERE
										sites_site_id = $ecom_siteid
										AND lettertemplate_letter_type = 'ORDER_PAYMENT_RELEASE'
									LIMIT
										1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from			= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);

				$billing_str		= '';
				$delivery_str		= '';
				$gift_str			= '';
				$bonus_str			= '';
				$tax_str			= '';
				$prod_details_str	= '';

				$note				= $ord_arr['note'];

				// Get the full details of current order from orders table
				$row_ord 			= get_FullOrderDetails($ord_arr['order_id']);

				// Calling function to get the billing details
				$billing_str		= get_EmailBillingDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the delivery details
				$delivery_str		= get_EmailDeliveryDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the giftwrap details
				$gift_str			= get_EmailGiftwrapDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the bonus points details
				$bonus_str			= get_EmailBonusDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the tax details
				$tax_str			= get_EmailTaxDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the promotional or voucher details
				$voucher_str		= get_EmailPromVoucherDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the product details of current order
				$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ord);
				$cname					= stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname'])." ".stripslashes($row_ord['order_custsurname']);
				$search_arr	= array (
				'[cust_name]',
				'[domain]',
				'[orderid]',
				'[orderdate]',
				'[billing_details]',
				'[delivery_details]',
				'[giftwrap_details]',
				'[bonus_details]',
				'[tax_details]',
				'[promvoucher_details]',
				'[product_details]',
				'[release_date]',
				'[note]'
				);
				$replace_arr= array(
				$cname,
				$ecom_hostname,
				$ord_arr['order_id'],
				dateFormat($row_ord['order_date'],'datetime'),
				$billing_str,
				$delivery_str,
				$gift_str,
				$bonus_str,
				$tax_str,
				$voucher_str,
				$prod_details_str,
				dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime'),
				$note
				);
				// Do the replacement in email template content
				$email_content = str_replace($search_arr,$replace_arr,$email_content);
				// Building email headers to be used with the mail
				$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
				$email_headers 	.= "MIME-Version: 1.0\n";
				$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

				// Saving the email to order_emails table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $ord_arr['order_id'];
				$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ord['order_custemail'])));
				$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
				//$insert_array['email_message']		= addslashes(stripslashes($email_content));
				$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
				$insert_array['email_type']			= 'ORDER_PAYMENT_RELEASE';
				$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
				$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
				$db->insert_from_array($insert_array,'order_emails');
				$email_insertid = $db->insert_id();
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$email_insertid,stripslashes($email_content));
				if($email_disabled==0)// check whether mail sending is disabled
				{
					mail($row_ord['order_custemail'], $email_subject,$email_content, $email_headers);
				}

		}
		break;
		case 'DEFERRED_ABORT': // Aborting deferrred payment
			// Get the content of email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
									FROM
										general_settings_site_letter_templates
									WHERE
										sites_site_id = $ecom_siteid
										AND lettertemplate_letter_type = 'ORDER_PAYMENT_ABORT'
									LIMIT
										1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from			= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);

				$billing_str		= '';
				$delivery_str		= '';
				$gift_str			= '';
				$bonus_str			= '';
				$tax_str			= '';
				$prod_details_str	= '';

				$note				= $ord_arr['note'];

				// Get the full details of current order from orders table
				$row_ord 			= get_FullOrderDetails($ord_arr['order_id']);

				// Calling function to get the billing details
				$billing_str		= get_EmailBillingDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the delivery details
				$delivery_str		= get_EmailDeliveryDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the giftwrap details
				$gift_str			= get_EmailGiftwrapDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the bonus points details
				$bonus_str			= get_EmailBonusDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the tax details
				$tax_str			= get_EmailTaxDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the promotional or voucher details
				$voucher_str		= get_EmailPromVoucherDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the product details of current order
				$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ord);
				$cname					= stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname'])." ".stripslashes($row_ord['order_custsurname']);
				$search_arr	= array (
				'[cust_name]',
				'[domain]',
				'[orderid]',
				'[orderdate]',
				'[billing_details]',
				'[delivery_details]',
				'[giftwrap_details]',
				'[bonus_details]',
				'[tax_details]',
				'[promvoucher_details]',
				'[product_details]',
				'[abort_date]',
				'[note]'
				);
				$replace_arr= array(
				$cname,
				$ecom_hostname,
				$ord_arr['order_id'],
				dateFormat($row_ord['order_date'],'datetime'),
				$billing_str,
				$delivery_str,
				$gift_str,
				$bonus_str,
				$tax_str,
				$voucher_str,
				$prod_details_str,
				dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime'),
				$note
				);
				// Do the replacement in email template content
				$email_content = str_replace($search_arr,$replace_arr,$email_content);
				// Building email headers to be used with the mail
				$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
				$email_headers 	.= "MIME-Version: 1.0\n";
				$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

				// Saving the email to order_emails table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $ord_arr['order_id'];
				$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ord['order_custemail'])));
				$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
				//$insert_array['email_message']		= addslashes(stripslashes($email_content));
				$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
				$insert_array['email_type']			= 'ORDER_PAYMENT_RELEASE';
				$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
				$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
				$db->insert_from_array($insert_array,'order_emails');
				$email_insertid = $db->insert_id();
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$email_insertid,stripslashes($email_content));
				if($email_disabled==0)// check whether mail sending is disabled
				{
					mail($row_ord['order_custemail'], $email_subject,$email_content, $email_headers);
				}
			}
		break;
		case 'PREAUTH_REPEAT': // Repeat Preauth payment
			// Get the content of email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
									FROM
										general_settings_site_letter_templates
									WHERE
										sites_site_id = $ecom_siteid
										AND lettertemplate_letter_type = 'ORDER_PAYMENT_REPEAT'
									LIMIT
										1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from			= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);

				$billing_str		= '';
				$delivery_str		= '';
				$gift_str			= '';
				$bonus_str			= '';
				$tax_str			= '';
				$prod_details_str	= '';

				$note				= $ord_arr['note'];

				// Get the full details of current order from orders table
				$row_ord 			= get_FullOrderDetails($ord_arr['order_id']);

				// Calling function to get the billing details
				$billing_str		= get_EmailBillingDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the delivery details
				$delivery_str		= get_EmailDeliveryDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the giftwrap details
				$gift_str			= get_EmailGiftwrapDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the bonus points details
				$bonus_str			= get_EmailBonusDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the tax details
				$tax_str			= get_EmailTaxDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the promotional or voucher details

				$voucher_str		= get_EmailPromVoucherDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the product details of current order
				$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ord);
				$cname					= stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname'])." ".stripslashes($row_ord['order_custsurname']);
				$search_arr	= array (
				'[cust_name]',
				'[domain]',
				'[orderid]',
				'[orderdate]',
				'[billing_details]',
				'[delivery_details]',
				'[giftwrap_details]',
				'[bonus_details]',
				'[tax_details]',
				'[promvoucher_details]',
				'[product_details]',
				'[repeat_date]',
				'[note]'
				);
				$replace_arr= array(
				$cname,
				$ecom_hostname,
				$ord_arr['order_id'],
				dateFormat($row_ord['order_date'],'datetime'),
				$billing_str,
				$delivery_str,
				$gift_str,
				$bonus_str,
				$tax_str,
				$voucher_str,
				$prod_details_str,
				dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime'),
				$note
				);
				// Do the replacement in email template content
				$email_content = str_replace($search_arr,$replace_arr,$email_content);
				// Building email headers to be used with the mail
				$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
				$email_headers 	.= "MIME-Version: 1.0\n";
				$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

				// Saving the email to order_emails table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $ord_arr['order_id'];
				$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ord['order_custemail'])));
				$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
				//$insert_array['email_message']		= addslashes(stripslashes($email_content));
				$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
				$insert_array['email_type']			= 'ORDER_PAYMENT_RELEASE';
				$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
				$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
				$db->insert_from_array($insert_array,'order_emails');
				$email_insertid = $db->insert_id();
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$email_insertid,stripslashes($email_content));
				if($email_disabled==0)// check whether mail sending is disabled
				{
					mail($row_ord['order_custemail'], $email_subject,$email_content, $email_headers);
				}
			}
		break;
		case 'AUTHENTICATE_AUTHORISE': // Repeat Authenticate Authorise
			// Get the content of email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
									FROM
										general_settings_site_letter_templates
									WHERE
										sites_site_id = $ecom_siteid
										AND lettertemplate_letter_type = 'ORDER_PAYMENT_AUTHORISE'
									LIMIT
										1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from			= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);

				$billing_str		= '';
				$delivery_str		= '';
				$gift_str			= '';
				$bonus_str			= '';
				$tax_str			= '';
				$prod_details_str	= '';

				$note				= $ord_arr['note'];
				$amt				= $ord_arr['amt'];

				// Get the full details of current order from orders table
				$row_ord 			= get_FullOrderDetails($ord_arr['order_id']);

				// Calling function to get the billing details
				$billing_str		= get_EmailBillingDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the delivery details
				$delivery_str		= get_EmailDeliveryDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the giftwrap details
				$gift_str			= get_EmailGiftwrapDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the bonus points details
				$bonus_str			= get_EmailBonusDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the tax details
				$tax_str			= get_EmailTaxDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the promotional or voucher details
				$voucher_str		= get_EmailPromVoucherDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the product details of current order
				$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ord);
				$cname					= stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname'])." ".stripslashes($row_ord['order_custsurname']);
				if($note!='')
					{
					
					$note_str=   ' <tr>
												<td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top">Note</td>
								 </tr>';
					$note_str .= '<tr>
												<td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px 0pt; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(91, 91, 91); font-weight: normal;" align="left" valign="top">'.$note.'</td>
								 </tr>'	;
					}
				$search_arr	= array (
				'[cust_name]',
				'[domain]',
				'[orderid]',
				'[orderdate]',
				'[billing_details]',
				'[delivery_details]',
				'[giftwrap_details]',
				'[bonus_details]',
				'[tax_details]',
				'[promvoucher_details]',
				'[product_details]',
				'[authorise_date]',
				'[authorise_amt]',
				'[note]'
				);
				$replace_arr= array(
				$cname,
				$ecom_hostname,
				$ord_arr['order_id'],
				dateFormat($row_ord['order_date'],'datetime'),
				$billing_str,
				$delivery_str,
				$gift_str,
				$bonus_str,
				$tax_str,
				$voucher_str,
				$prod_details_str,
				dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime'),
				$amt,
				$note_str
				);
				// Do the replacement in email template content
				$email_content = str_replace($search_arr,$replace_arr,$email_content);
				// Building email headers to be used with the mail
				$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
				$email_headers 	.= "MIME-Version: 1.0\n";
				$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

				// Saving the email to order_emails table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $ord_arr['order_id'];
				$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ord['order_custemail'])));
				$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
				//$insert_array['email_message']		= addslashes(stripslashes($email_content));
				$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
				$insert_array['email_type']			= 'ORDER_PAYMENT_RELEASE';
				$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
				$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
				$db->insert_from_array($insert_array,'order_emails');
				$email_insertid = $db->insert_id();
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$email_insertid,stripslashes($email_content));
				if($email_disabled==0)// check whether mail sending is disabled
				{
					mail($row_ord['order_custemail'], $email_subject,$email_content, $email_headers);
				}
			}
		break;
		case 'AUTHENTICATE_CANCEL': // Repeat Authenticate Cancel payment
			// Get the content of email template
			$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
									FROM
										general_settings_site_letter_templates
									WHERE
										sites_site_id = $ecom_siteid
										AND lettertemplate_letter_type = 'ORDER_PAYMENT_CANCEL'
									LIMIT
										1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from			= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);

				$billing_str		= '';
				$delivery_str		= '';
				$gift_str			= '';
				$bonus_str			= '';
				$tax_str			= '';
				$prod_details_str	= '';

				$note				= $ord_arr['note'];
				$amt				= $ord_arr['amt'];

				// Get the full details of current order from orders table
				$row_ord 			= get_FullOrderDetails($ord_arr['order_id']);

				// Calling function to get the billing details
				$billing_str		= get_EmailBillingDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the delivery details
				$delivery_str		= get_EmailDeliveryDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the giftwrap details
				$gift_str			= get_EmailGiftwrapDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the bonus points details
				$bonus_str			= get_EmailBonusDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the tax details
				$tax_str			= get_EmailTaxDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the promotional or voucher details
				$voucher_str		= get_EmailPromVoucherDetails($ord_arr['order_id'],$row_ord);

				// Calling function to get the product details of current order
				$prod_details_str		= get_ProductsInOrdersForMail($ord_arr['order_id'],$row_ord);
				$cname					= stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname'])." ".stripslashes($row_ord['order_custsurname']);
				$search_arr	= array (
				'[cust_name]',
				'[domain]',
				'[orderid]',
				'[orderdate]',
				'[billing_details]',
				'[delivery_details]',
				'[giftwrap_details]',
				'[bonus_details]',
				'[tax_details]',
				'[promvoucher_details]',
				'[product_details]',
				'[cancel_date]',
				'[note]'
				);
				$replace_arr= array(
				$cname,
				$ecom_hostname,
				$ord_arr['order_id'],
				dateFormat($row_ord['order_date'],'datetime'),
				$billing_str,
				$delivery_str,
				$gift_str,
				$bonus_str,
				$tax_str,
				$voucher_str,
				$prod_details_str,
				dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime'),
				$note
				);
				// Do the replacement in email template content
				$email_content = str_replace($search_arr,$replace_arr,$email_content);
				// Building email headers to be used with the mail
				$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
				$email_headers 	.= "MIME-Version: 1.0\n";
				$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";

				// Saving the email to order_emails table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $ord_arr['order_id'];
				$insert_array['email_to']			= addslashes(stripslashes(strip_tags($row_ord['order_custemail'])));
				$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
				//$insert_array['email_message']		= addslashes(stripslashes($email_content));
				$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
				$insert_array['email_type']			= 'ORDER_PAYMENT_RELEASE';
				$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
				$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
				$db->insert_from_array($insert_array,'order_emails');
				$email_insertid = $db->insert_id();
				$email_content = add_line_break($email_content);
				write_email_as_file('ord',$email_insertid,stripslashes($email_content));			
				
					if($email_disabled==0)// check whether mail sending is disabled
					{
						mail($row_ord['order_custemail'], $email_subject,$email_content, $email_headers);
					}
				
			}
		break;
	};
	return;
}




/* Function to handle the case of cancelling the orders and returning the stock to respective products */
function do_ordercancelReturns($order_id,$stat_arr)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// Check whether the current site is maintaining a stock
	$sql_stockmaintain = "SELECT product_maintainstock,product_decrementstock 
							FROM
								general_settings_sites_common
							WHERE
								sites_site_id = $ecom_siteid
							LIMIT
								1";
	$ret_stockmaintain = $db->query($sql_stockmaintain);
	if($db->num_rows($ret_stockmaintain))
		$row_stockmaintain = $db->fetch_array($ret_stockmaintain);
	// Get few of the details required related to current order
	/* Donate bonus Start */
	$sql_ord = "SELECT customers_customer_id,order_bonuspoints_used,gift_vouchers_voucher_id,
						costperclick_id,order_bonuspoint_inorder,promotional_code_code_id,
						order_bonuspoints_donated 
					FROM
						orders
					WHERE
						order_id=$order_id
					LIMIT
						1";
	/* Donate bonus End */
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}

	// Check whether there exists any product which have been refunded or despatched in current order details
	$sql_check = "SELECT orderdet_id
					FROM
						order_details
					WHERE
						orders_order_id = $order_id
						AND (order_refunded = 'Y' OR order_dispatched='Y')
					LIMIT
						1";
	$ret_check = $db->query($sql_check);
	$prodext_arr	= array(-1);
	if ($db->num_rows($ret_check)==0 or $stat_arr['force_cancel']==1) // case if no products refunded or despatched in current order or forcefully done by console user
	{
			// Get all the products involved with the current order
			$sql_prods = "SELECT products_product_id,order_qty,order_refunded,order_dispatched,order_stock_combination_id,order_preorder 
							FROM
								order_details
							WHERE
								orders_order_id = $order_id";
			$ret_prods = $db->query($sql_prods);
			if ($db->num_rows($ret_prods))
			{
				while ($row_prods = $db->fetch_array($ret_prods))
				{
					// Check whether the product is in fixed or variable stock
					$sql_prodcheck = "SELECT product_variablestock_allowed,product_preorder_allowed,product_total_preorder_allowed 
										FROM
											products
										WHERE
											product_id = ".$row_prods['products_product_id']."
										LIMIT
											1";
					$ret_prodcheck = $db->query($sql_prodcheck);
					if ($db->num_rows($ret_prodcheck))
					{
						$row_prodcheck = $db->fetch_array($ret_prodcheck);
						if ($row_prodcheck['product_preorder_allowed']=='N' and $row_prods['order_preorder']=='N') // return the stock only if the product was not is preorder at the time of ordering and also at present
						{
							if($row_stockmaintain['product_maintainstock']==1 and $row_stockmaintain['product_decrementstock']==1 and $stat_arr['stock_return']==1)// Do the following only if current site maintains stock (settings from console)
							{
								// Check whether direct stock or combination stock
								if ($row_prods['order_stock_combination_id']==0)// fixed stock
								{
									// Check whether the product is still in fixed stock
									if($row_prodcheck['product_variablestock_allowed']=='N')
									{
										// Increment the web stock and actual stock field for the current product by qty in order
										// and also making the preorder to N. This is done to handle the case of curreent product is placed in preorder
		
										$sql_update = "UPDATE products
														SET
															product_webstock 	= product_webstock + ".$row_prods['order_qty']." ,
															product_actualstock = product_actualstock + ".$row_prods['order_qty']." ,
															product_preorder_allowed = 'N',product_total_preorder_allowed=0,
															product_instock_date='0000-00-00'
														WHERE
															product_id = ".$row_prods['products_product_id']."
																LIMIT
															1";
		
										$db->query($sql_update);
									}
								}
								else // case of variable stock
								{
									// Check whether the product is still in variable stock
									if($row_prodcheck['product_variablestock_allowed']=='Y')
									{
										// Check whether the combination still exists
										$sql_check = 'SELECT comb_id
														FROM
															product_variable_combination_stock
														WHERE
															comb_id = '.$row_prods['order_stock_combination_id']."
															AND products_product_id = ".$row_prods['products_product_id']."
														LIMIT
															1";
										$ret_check = $db->query($sql_check);
										if ($db->num_rows($ret_check)) // case if combination already exists
										{
											$sql_update = "UPDATE product_variable_combination_stock
															SET
																web_stock = web_stock + ".$row_prods['order_qty'].",
																actual_stock = actual_stock + ".$row_prods['order_qty']."
															WHERE
																comb_id = ".$row_prods['order_stock_combination_id']."
																AND products_product_id = ".$row_prods['products_product_id']."
															LIMIT
																1";
											$db->query($sql_update);
		
											//Updating the products table in the fields product_preorder_allowed to make it to 'N'
											$sql_upd = "UPDATE products
															SET
																product_preorder_allowed ='N',product_total_preorder_allowed=0,
																product_instock_date='0000-00-00'
															WHERE
																product_id = ".$row_prods['products_product_id']."
															LIMIT
																1";
											$db->query($sql_upd);
										}
									}
								}
							}
						}
						elseif($row_prodcheck['product_preorder_allowed']=='Y' and $row_prods['order_preorder']=='Y')// case product was in preorder at the time of ordering also is in preorder at present
						{
							if(!in_array($row_prods['products_product_id'],$prodext_arr))// This is done to handle the case to decrement the total preorder value only once even if the product exists in cart more than once
							{
								$update_sql = "UPDATE 
													products 
												SET 
													product_total_preorder_allowed = product_total_preorder_allowed + 1 
												WHERE 
													product_total_preorder_allowed > 0 
													AND product_id = ".$row_prods['products_product_id']." 
													AND sites_site_id = $ecom_siteid  
												LIMIT 
													1";
								$db->query($update_sql);
								$prodext_arr[] = $row_prods['products_product_id'];
							}	
						}
					}
				}
			}
		// ############################################################################
		// Check whether bonus points used in current order.
		// ############################################################################
		if($row_ord['order_bonuspoints_used']>0 and $row_ord['customers_customer_id']>0 and $stat_arr['bonusused_return']==1)
		{
			// Return the bonus points used to the respective customer account
			$sql_bonus = "UPDATE customers
							SET
								customer_bonus = customer_bonus + ".$row_ord['order_bonuspoints_used']."
							WHERE
								customer_id = ".$row_ord['customers_customer_id']."
								AND sites_site_id = $ecom_siteid
							LIMIT
								1";
			$db->query($sql_bonus);
		}
		
		/* Donate bonus Start */	
		if($row_ord['order_bonuspoints_donated']>0 and $row_ord['customers_customer_id']>0 and $stat_arr['bonusused_return']==1)
		{
			// Return the bonus points used to the respective customer account
			$sql_bonus = "UPDATE customers
							SET
								customer_bonus = customer_bonus + ".$row_ord['order_bonuspoints_donated']."
							WHERE
								customer_id = ".$row_ord['customers_customer_id']."
								AND sites_site_id = $ecom_siteid
							LIMIT
								1";
			$db->query($sql_bonus);
		}
		/* Donate bonus End */	
		// ############################################################################
		// Check whether customer received any bonus points due to this order
		// ############################################################################
		if($row_ord['order_bonuspoint_inorder']>0 and $row_ord['customers_customer_id']>0 and $stat_arr['bonusearned_return']==1)
		{
			// Get how many bonus points exists for current customer
			$sql_cust = "SELECT customer_bonus
							FROM
								customers
							WHERE
								customer_id = ".$row_ord['customers_customer_id']."
							LIMIT
								1";
			$ret_cust = $db->query($sql_cust);
			if ($db->num_rows($ret_cust))
			{
				$row_cust 	= $db->fetch_array($ret_cust);
				$upd_bonus	= 0;
				// case if customer bonus is >= to the bonus earned from current order
				if($row_cust['customer_bonus']>=$row_ord['order_bonuspoint_inorder'])
				{
					$upd_bonus = $row_cust['customer_bonus'] - $row_ord['order_bonuspoint_inorder'];
				}
				else
					$upd_bonus = 0;
				// Updating the customer table with this new bonus value
				$sql_upd = "UPDATE customers
								SET
									customer_bonus = $upd_bonus
								WHERE
									customer_id = ".$row_ord['customers_customer_id']."
								LIMIT
									1";
				$db->query($sql_upd);
			}
			
			// Return the bonus points used to the respective customer account
			$sql_bonus = "UPDATE customers
							SET
								customer_bonus = customer_bonus + ".$row_ord['order_bonuspoints_used']."
							WHERE
								customer_id = ".$row_ord['customers_customer_id']."
								AND sites_site_id = $ecom_siteid
							LIMIT
								1";
								
			$db->query($sql_bonus);
		}
		/* Donate bonus Start */
		if($row_ord['order_bonuspoints_donated']>0 and $row_ord['customers_customer_id']>0 and $stat_arr['bonusearned_return']==1)
		{
			$sql_bonus = "UPDATE customers
							SET
								customer_bonus = customer_bonus + ".$row_ord['order_bonuspoints_donated']."
							WHERE
								customer_id = ".$row_ord['customers_customer_id']."
								AND sites_site_id = $ecom_siteid
							LIMIT
								1";
			/* Donate bonus End */					
			$db->query($sql_bonus);
		}
		/* Donate bonus End */
		// ############################################################################
		// Check whether voucher exists in current order
		// ############################################################################
		if($row_ord['gift_vouchers_voucher_id']>0 and $stat_arr['maxvoucher_return']==1)
		{
			// Check whether current voucher still exists
			$sql_check = "SELECT voucher_id
							FROM
								gift_vouchers
							WHERE
								voucher_id = ".$row_ord['gift_vouchers_voucher_id']."
								AND sites_site_id = $ecom_siteid
								AND voucher_max_usage >0
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$sql_update = "UPDATE gift_vouchers
									SET
										voucher_usage = voucher_usage - 1
									WHERE
										voucher_id = ".$row_ord['gift_vouchers_voucher_id']."
										and sites_site_id = $ecom_siteid
									LIMIT
										1";
				$db->query($sql_update);
			}
		}
		// ############################################################################
		// Check whether promotional code exists in current order
		// ############################################################################
		if($row_ord['promotional_code_code_id']>0)
		{
			$sql_del = "DELETE FROM 
							order_promotionalcode_track 
						WHERE 
							orders_order_id = $order_id 
						LIMIT 
							1";
			$db->query($sql_del);
			// Check whether logged in customer used this promotional code
			if($row_ord['customers_customer_id'])
			{
				// Check whether login req for current promotional code and also whether customer have limited access
				$sql_get = "SELECT code_login_to_use,code_customer_limit,code_customer_usedlimit,code_customer_unlimit_check   
								FROM 
									promotional_code 
								WHERE 
									code_id = ".$row_ord['promotional_code_code_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_get = $db->query($sql_get);
				if ($db->num->rows($ret_get))
				{
					$row_get = $db->fetch_array($ret_get);
					if ($row_get['code_login_to_use']==1 and $row_get['code_customer_unlimit_check']==0 and $row_get['code_customer_usedlimit']>0)
					{
						$sql_update = "UPDATE promotional_code 
											SET 
												code_customer_usedlimit = code_customer_usedlimit -1 
											WHERE 
												code_id = ".$row_ord['promotional_code_code_id']." 
												AND code_customer_usedlimit >0
											LIMIT 
												1";
						$db->query($sql_update);			
					}
				}
			}
			$sql_check = "SELECT code_id,code_usedlimit 
							FROM
								promotional_code
							WHERE
								code_id = ".$row_ord['promotional_code_code_id']."
								AND sites_site_id = $ecom_siteid
								AND code_usedlimit >0
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				$sql_update = "UPDATE promotional_code 
								SET 
									code_usedlimit = code_usedlimit - 1 
								WHERE 
									code_id = ".$row_ord['promotional_code_code_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$db->query($sql_update);					
			}						
		}
		
		
		// ############################################################################
		// Check whether this order is related to any cost per click section
		// ############################################################################
		if($row_ord['costperclick_id']>0)
		{
			// handle the reduction of total and other things from cost per click table here

			// THIS SECTION CAN BE IMPLEMENTED AFTER THE COSTPERCLICK DB DESIGN




		}
	}
	else
	{
		$ret_arr['order_id'] 	= $order_id;
		$ret_arr['msg']			= 'REFUND_OR_DESPATCH';
		return $ret_arr;
	}
}

/* Function to show excluding vat dialogue*/
function show_excluding_vat_msg($row_prod,$cls='vat_div',$prefix='',$suffix='')
{
	global $Captions_arr,$PriceSettings_arr;
	global $ecom_selfhttp;
	// Check whether appy tax is ticked for current product and also the price display type is price + tax
	if($row_prod['product_applytax']=='Y' and $PriceSettings_arr['price_displaytype']=='show_price_plus_tax')
	{
		if($Captions_arr['COMMON']['COMMON_EXC_VAT']!='')
		{
		 if($prefix!='')
	 		echo $prefix;
?>			
			<div class="<?php echo $cls?>"><?php echo $Captions_arr['COMMON']['COMMON_EXC_VAT']?></div>
<?php
 		 if($suffix!='')
    		 echo $suffix;
		}
	}	
}

/* Function to show the bonus points message */
function show_bonus_points_msg($row_prod,$cls='bonus_point',$prefix='',$suffix='')
{
	global $Captions_arr;
	global $ecom_selfhttp;
	if($row_prod['product_bonuspoints']>0)
	{
	 if($prefix!='')
	 echo $prefix;
?>
		<div class="<?php echo $cls?>"><?php echo $row_prod['product_bonuspoints'].' '.$Captions_arr['COMMON']['COMMON_BONUS_POINTS']?></div>
<?php
 if($suffix!='')
     echo $suffix;
	}
}
function show_bonus_points_msg_multicolor($row_prod,$cls_arr,$prefix='',$suffix='')
{
	global $Captions_arr;
	global $ecom_selfhttp;
	if($row_prod['product_bonuspoints']>0)
	{
	 if($prefix!='')
	 echo $prefix;
	 $cls 					= $cls_arr['main_cls'];
	 $caption_span_start 	= $point_span_end = $point_span_start = $point_span_end = '';
	 if($cls_arr['caption_cls']!='')
	 {
	 	$caption_span_start	= '<span class="'.$cls_arr['caption_cls'].'">';
	 	$caption_span_end	= '</span>';
	}
	if ($cls_arr['point_cls']!='')
	{	
	 	$point_span_start	= '<span class="'.$cls_arr['point_cls'].'">';
		$point_span_end		= '</span>';
	} 
	 
?>
		<div class="<?php echo $cls?>"><?php echo $caption_span_start.$row_prod['product_bonuspoints'].$caption_span_end.' '.$point_span_start.$Captions_arr['COMMON']['COMMON_BONUS_POINTS'].$point_span_end?></div>
<?php
 if($suffix!='')
     echo $suffix;
	}
}
function get_orderenquirypostnewcount($order_id)
{
  	 global $db,$ecom_siteid;	
  	 global $ecom_selfhttp;
		 $new_cnt =0;
		 
		 $check_new = "SELECT query_id FROM order_queries   WHERE query_status='N' AND  sites_site_id=$ecom_siteid  AND orders_order_id=".$order_id." LIMIT 1 ";
		 $ret_check = $db->query($check_new); 
		 if($db->num_rows($ret_check)>0)
		 { 
		 	$new_cnt = 1;
		 }
		 else
		 {
		  $check_new_posts = "SELECT oqp.post_id FROM order_queries_posts oqp,order_queries oq WHERE oqp.order_queries_query_id=oq.query_id AND oqp.post_status='N' AND oq.orders_order_id=".$order_id." LIMIT 1"; 
		 // echo $check_new_posts;
		  $ret_new_posts = $db->query($check_new_posts);  
			 if($db->num_rows($ret_new_posts)>0)
			 { 
				$new_cnt = 1;
			 }
		 }
		 return $new_cnt;
}
function seo_revenue_report($row_ord,$order_amount) {
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	/*$db->query("UPDATE seo_revenue_total SET click_to_sale=click_to_sale+1,click_total=click_total+$order_amount WHERE site_id=$ecom_siteid AND click_id=".$_SESSION['cpc_click_id']);
	$db->query("UPDATE seo_revenue_month SET click_to_sale=click_to_sale+1,click_total=click_total+$order_amount WHERE click_pm_id=".$_SESSION['cpc_click_pm_id']);
	*/
	$db->query("UPDATE seo_revenue_total SET click_to_sale=click_to_sale+1,click_total=click_total+$order_amount WHERE site_id=$ecom_siteid AND click_id=".$row_ord['order_cpc_click_id']);
	$db->query("UPDATE seo_revenue_month SET click_to_sale=click_to_sale+1,click_total=click_total+$order_amount WHERE click_pm_id=".$row_ord['order_cpc_click_pm_id']);
	
}
function  cost_per_click($id_arr,$total)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$id_arr 			= explode('_',$id_arr);
	$url_id 			= $id_arr[0];
	$month_id		= $id_arr[1];
	// Update both the table costperclick_adverturl and costperclick_month 
	$sql_update = "UPDATE 
								costperclick_adverturl 
							SET 
								url_total_sale_clicks 	= url_total_sale_clicks + 1,
								url_total_sale_amount = url_total_sale_amount + $total 
							WHERE 
								url_id = $url_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
	$db->query($sql_update);
	
	// Update both the table costperclick_adverturl and costperclick_month 
	$sql_update = "UPDATE 
								costperclick_month 
							SET 
								month_total_sale_clicks = month_total_sale_clicks + 1,
								month_total_sale_amount = month_total_sale_amount + $total 
							WHERE 
								month_id = $month_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
	$db->query($sql_update);
}
function split_date_new($date)
{
	global $ecom_selfhttp;
  	$first_date_arr = explode(" ",$date);
	$date_arr 	= explode('-',$first_date_arr[0]);
	$time_arr   = explode(':',$first_date_arr[1]);
	$sdate		= mktime($time_arr[0],$time_arr[1],$time_arr[2],$date_arr[1],$date_arr[2],$date_arr[0]);	
    return $sdate;
}
// Function to send the payonaccont payment approval mail. In customer area this will be send only if payment is approved.
function send_PayonAccountApproval($pay_id)
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	// Get the content of email template
	 $sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
							FROM
								general_settings_site_letter_templates
							WHERE
								sites_site_id = $ecom_siteid
								AND lettertemplate_letter_type = 'PAY_ON_ACCOUNT_PAYMENT_APPROVAL'
							LIMIT
								1";
	$ret_template = $db->query($sql_template);
	if ($db->num_rows($ret_template))
	{
		$row_template 		= $db->fetch_array($ret_template);
		$email_from			= stripslashes($row_template['lettertemplate_from']);
		$email_subject		= stripslashes($row_template['lettertemplate_subject']);
		$email_content		= stripslashes($row_template['lettertemplate_contents']);
		$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
		if($email_disabled==0)// check whether mail sending is disabled
		{
			// Get the payment details from order_payonaccount_details 
			$sql_pay = "SELECT DATE_FORMAT(pay_date,'%d-%b-%Y') pdate,pay_amount,pay_paystatus,pay_paymenttype,pay_paymentmethod ,pay_curr_rate, pay_curr_symbol,customers_customer_id  
									FROM 
										order_payonaccount_details 
									WHERE 
										pay_id = $pay_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
			$ret_pay = $db->query($sql_pay);
			if ($db->num_rows($ret_pay))
			{
				$row_pay = $db->fetch_array($ret_pay);
				// Get the details of customer
				$sql_cust = "SELECT customer_title,customer_fname,customer_surname,customer_email_7503  
										FROM 
											customers 
										WHERE 
											customer_id = ".$row_pay['customers_customer_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$ret_cust = $db->query($sql_cust);
				if ($db->num_rows($ret_cust))
				{
					$row_cust = $db->fetch_array($ret_cust);
				}	
			}
			else // case if invalid details
				return false;
		
			$cname					= stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname'])." ".stripslashes($row_cust['customer_surname']);
			$search_arr	= array (
										'[name]',
										'[domain]',
										'[date]',
										'[amount]'
										);
			$replace_arr= array(
										$cname,
										$ecom_hostname,
										$row_pay['pdate'],
										print_price($row_pay['pay_amount'])
										);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Building email headers to be used with the mail
			$email_headers 	= "From: $ecom_hostname	<$email_from>\n";
			$email_headers 	.= "MIME-Version: 1.0\n";
			$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
			mail($row_cust['customer_email_7503'], $email_subject,$email_content, $email_headers); 
		}
	}
}
function clear_all_cache($cache_path='')
{
	global $image_path;
	global $ecom_selfhttp;
	if ($cache_path=='' or !$cache_path)		// If parameter is empty then set it to the root of cache
	$cache_path = $image_path.'/cache';
	if ($cache_path=='' or !$cache_path)		// just for a double protection
	exit;

	if (file_exists($cache_path))				// Check whether directory exists
	{
		if (is_dir($cache_path))
		{
			$dirhandle=opendir($cache_path);
			while(($file = readdir($dirhandle)) !== false)
			{
				if (($file!=".")&&($file!=".."))
				{
					//echo '<br>'.$currentfile=$cache_path."/".$file;
					if (!$i) $i = 0;
					if(!is_dir($currentfile))
					{
						$file_arr = explode('.',$file);
						if($file_arr[1]=='txt')
						{
							//echo "<br>".$currentfile;
							unlink($currentfile);
						}
					}
					$i++;
					if(is_dir($currentfile))
					{
						clear_all_cache($currentfile);
					}
				}
			}
		}
	}
}
function get_current_currency_details()
{
	global $db,$ecom_siteid,$ecom_section_Arr,$image_path,$ecom_hostname,$default_Currency_arr,$sitesel_curr;
	global $ecom_selfhttp;
	$file_path 	= $image_path .'/settings_cache/currency.php';
	$ret_arr		= array();
	if(file_exists($file_path))
	{
		include ($file_path);
	}
	if($default_Currency_arr['currency_id']!=$sitesel_curr)
	{
		$sel_arr = $sel_curr[$sitesel_curr];
		if(is_array($sel_arr))
		{
			if (count($sel_arr))
			{
				$ret_arr['curr_rate'] 			= $sel_arr['curr_rate'];
				$ret_arr['curr_margin'] 		= $sel_arr['curr_margin'];
				$ret_arr['curr_sign_char']		= $sel_arr['curr_sign_char'];
			}	
		}
	}
	if(count($ret_arr)==0)
	{
		$ret_arr['curr_rate'] 		=  1;
		$ret_arr['curr_margin'] 	=  0;
		$ret_arr['curr_sign_char']	=  $default_Currency_arr['curr_sign_char'];
	}	
	return $ret_arr;
}
function get_current_currency_details_OLD()
{
	global $db,$ecom_siteid,$default_Currency_arr,$sitesel_curr;
	global $ecom_selfhttp;
	if($default_Currency_arr['currency_id']!=$sitesel_curr)
	{
		//Get the rate for current currency from site_currencies table
		$sql_sitecur = "SELECT curr_rate,curr_margin ,curr_sign_char  
								FROM 
									general_settings_site_currency 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND currency_id=$sitesel_curr 
								LIMIT 
									1";
		$ret_sitecur = $db->query($sql_sitecur);
		if($db->num_rows($ret_sitecur))
		{
			$row_sitecur					= $db->fetch_array($ret_sitecur);
			$ret_arr['curr_rate'] 		=  $row_sitecur['curr_rate'];
			$ret_arr['curr_margin'] 	=  $row_sitecur['curr_margin'];
			$ret_arr['curr_sign_char']	=  stripslashes($row_sitecur['curr_sign_char']);
		}
		else
		{
			$ret_arr['curr_rate'] 		=  1;
			$ret_arr['curr_margin'] 	=  0;
			$ret_arr['curr_sign_char']	=  $default_Currency_arr['curr_sign_char'];
		}	
	}
	else
	{
		$ret_arr['curr_rate'] 		=  1;
		$ret_arr['curr_margin'] 	=  0;
		$ret_arr['curr_sign_char']	=  $default_Currency_arr['curr_sign_char'];
	}	
	return $ret_arr;
}
function get_inlineSiteComponents()
{
	global $db,$ecom_siteid,$ecom_section_Arr,$Captions_arr,$image_path,$ecom_hostname;
	global $ecom_selfhttp;
	$file_path = $image_path .'/settings_cache/site_menu.php';
	if(file_exists($file_path))
	{
		include ($file_path);
		return $inlineSiteComponents_Arr;
	}
}
function get_inlineConsoleComponents()
{
	global $db,$ecom_siteid,$ecom_section_Arr,$Captions_arr,$image_path,$ecom_hostname;
	global $ecom_selfhttp;
	$file_path = $image_path .'/settings_cache/mod_menu.php';
	if(file_exists($file_path))
	{
		include ($file_path);
		return $consoleSiteComponents_Arr;
	}
}
/* Function to delete the unwanted / expired entries from the display_settings table for the current site */
function removefrom_Display_Settings($component_id,$module_name)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// Get the feature id of current feature
	$sql_feat = "SELECT feature_id 
							FROM 
								features 
							WHERE 
								feature_modulename = '".$module_name."' 
							LIMIT 
								1";
	$ret_feat  = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat = $db->fetch_array($ret_feat);
		$sql_del = "DELETE FROM 
							display_settings 
						WHERE 
							display_component_id = $component_id 
							AND features_feature_id=".$row_feat['feature_id']." 
							AND sites_site_id = $ecom_siteid";
		$db->query($sql_del);					
	}				
}
/* function to get the full details from orders table for a given order id*/
function get_FullOrderDetails($order_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$sql_ords = "SELECT *
						FROM
							orders
						WHERE
							order_id =".$order_id."
						LIMIT
							1";
	$ret_ords = $db->query($sql_ords);
	if ($db->num_rows($ret_ords))
	{
		$row_ords = $db->fetch_array($ret_ords);
	}
	return $row_ords;
}
/* Function to get the billing related details */
function get_EmailBillingDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;

	$cname = stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname']).' '.stripslashes($row_ords['order_custmname']).' '.stripslashes($row_ords['order_custsurname']);
	// ##############################################################################
	// 								Billing details
	// ##############################################################################
	// Get the checkout fields from general_settings_sites_checkoutfields table
	$sql_checkout = "SELECT field_key,field_name
						FROM
							general_settings_site_checkoutfields
						WHERE
							sites_site_id = $ecom_siteid
							AND field_type IN ('PERSONAL')
						ORDER BY
							field_order";
	$ret_checkout = $db->query($sql_checkout);
	if($db->num_rows($ret_checkout))
	{
		while ($row_checkout = $db->fetch_array($ret_checkout))
		{
			$chkorder_arr[$row_checkout['field_key']] = stripslashes($row_checkout['field_name']);
		}
	}
	$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
	$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

	// ##############################################################################
	// Dynamic Values on top of billing details
	// ##############################################################################
	$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
					FROM
						order_dynamicvalues
					WHERE
						orders_order_id = $order_id
						AND position='Top'
					ORDER BY
						section_id,id";
	$ret_dynamic = $db->query($sql_dynamic);
	if($db->num_rows($ret_dynamic))
	{
		$prev_sec = 0;
		$dynamictop_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		while ($row_dynamic = $db->fetch_array($ret_dynamic))
		{
			if ($prev_sec!=$row_dynamic['section_id']) // Check whether section name is to be displayed
			{
				$prev_sec = $row_dynamic['section_id'];
				if ($row_dynamic['section_name']!='')
					$dynamictop_str		.= '<tr><td align="left" colspan="2" '.$style_head.'>'.stripslashes($row_dynamic['section_name']).'</td></tr>';
			}
			$dynamictop_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).'</td></tr>';
		}
		$dynamictop_str	.= '</table>';
	}

	$bill_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	// ##############################################################################
	// Dynamic Values on topinstatic of billing details
	// ##############################################################################
	$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
					FROM
						order_dynamicvalues
					WHERE
						orders_order_id = $order_id
						AND position='TopInStatic'
					ORDER BY
						section_id,id";
	$ret_dynamic = $db->query($sql_dynamic);
	if($db->num_rows($ret_dynamic))
	{
		$prev_sec = 0;
		while ($row_dynamic = $db->fetch_array($ret_dynamic))
		{
			$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).'</td></tr>';
		}
	}
	// ##############################################################################
	// Main Billing address details
	// ##############################################################################
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Name</td><td align="left" width="50%">'.$cname.'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_comp_name'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custcompany']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_building'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_buildingnumber']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_street'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_street']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_city'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_city']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_state'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_state']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_country'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_country']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_zipcode'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custpostcode']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_phone'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custphone']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_mobile'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custmobile']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_fax'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custfax']).'</td></tr>';
	$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkout_email'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_custemail']).'</td></tr>';

	// ##############################################################################
	// Dynamic Values on bottominstatic of billing details
	// ##############################################################################
	$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
					FROM
						order_dynamicvalues
					WHERE
						orders_order_id = $order_id
						AND position='BottomInStatic'
					ORDER BY
						section_id,id";
	$ret_dynamic = $db->query($sql_dynamic);
	if($db->num_rows($ret_dynamic))
	{
		$prev_sec = 0;
		while ($row_dynamic = $db->fetch_array($ret_dynamic))
		{
			$bill_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).'</td></tr>';
		}
	}
	$bill_str		.= '</table>';
	// ##############################################################################
	// Dynamic Values on top of billing details
	// ##############################################################################
	$sql_dynamic = "SELECT section_id,section_name,dynamic_label,dynamic_value
					FROM
						order_dynamicvalues
					WHERE
						orders_order_id = $order_id
						AND position='Bottom'
					ORDER BY
						section_id,id";
	$ret_dynamic = $db->query($sql_dynamic);
	if($db->num_rows($ret_dynamic))
	{
		$prev_sec = 0;
		$dynamicbottom_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		while ($row_dynamic = $db->fetch_array($ret_dynamic))
		{
			if ($prev_sec!=$row_dynamic['section_id']) // Check whether section name is to be displayed
			{
				$prev_sec = $row_dynamic['section_id'];
				if ($row_dynamic['section_name']!='')
					$dynamicbottom_str		.= '<tr><td align="left" colspan="2" '.$style_head.'>'.stripslashes($row_dynamic['section_name']).'</td></tr>';
			}
			$dynamicbottom_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_dynamic['dynamic_label']).'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_dynamic['dynamic_value']).'</td></tr>';
		}
		$dynamicbottom_str	.= '</table>';
	}
	// ##############################################################################
	// Concatenating the billing address
	// ##############################################################################
	$billing_addr		= $dynamictop_str.$bill_str.$dynamicbottom_str;
	return $billing_addr;
}
/* Function to get the billing related details */
function get_EmailDeliveryDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// ##############################################################################
	// Delivery details
	// ##############################################################################
	// ##############################################################################
	// 								Billing details
	// ##############################################################################
	// Get the checkout fields from general_settings_sites_checkoutfields table
	$sql_checkout = "SELECT field_key,field_name
						FROM
							general_settings_site_checkoutfields
						WHERE
							sites_site_id = $ecom_siteid
							AND field_type IN ('DELIVERY')
						ORDER BY
							field_order";
	$ret_checkout = $db->query($sql_checkout);
	if($db->num_rows($ret_checkout))
	{
		while ($row_checkout = $db->fetch_array($ret_checkout))
		{
			$chkorder_arr[$row_checkout['field_key']] = stripslashes($row_checkout['field_name']);
		}
	}
	$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
	$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

	// Get the delivery details corresponding to current order
	$sql_del		= "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,
						delivery_companyname,delivery_buildingnumber,delivery_street,delivery_city,delivery_state,
						delivery_country,delivery_zip,delivery_phone,delivery_fax,delivery_mobile,
						delivery_email
					FROM
						order_delivery_data
					WHERE
						orders_order_id = $order_id
					LIMIT
						1";
	$ret_del		= $db->query($sql_del);
	if ($db->num_rows($ret_del))
	{
		$row_del = $db->fetch_array($ret_del);
	}
	$del_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Name</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_title']).''.stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']).' '.'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_comp_name'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_companyname']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_building'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_buildingnumber']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_street'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_street']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_city'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_city']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_state'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_state']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_country'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_country']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_zipcode'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_zip']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_phone'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_phone']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_mobile'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_mobile']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_fax'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_fax']).'</td></tr>';
	$del_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$chkorder_arr['checkoutdelivery_email'] .'</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_del['delivery_email']).'</td></tr>';
	$del_str		.= '</table>';

	// ##############################################################################
	// Delivery Type
	// ##############################################################################
	if ($row_ord['order_delivery_type']!='None')
	{
		$del_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Method</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_deliverytype']).'</td></tr>';
		if($row_ords['order_delivery_option']!='') // case if delivery option exists
			$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Option</td><td align="left" width="50%" '.$style_desc.'>'.stripslashes($row_ords['order_delivery_option']).'</td></tr>';
		if ($row_ord['order_deliveryprice_only']>0) // case of delivery charge along
		{
			$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Base Delivery Charge</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_deliveryprice_only'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		}
		if ($row_ord['order_splitdeliveryreq']!='Yes') // Case of split delivery
		{
			$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Split Delivery</td><td align="left" width="50%" '.$style_desc.'>Yes</td></tr>';
		}
		if ($row_ord['order_extrashipping']>0) // case of extra shipping exists
		{
			$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Extra Shipping Cost</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_extrashipping'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		}
		$del_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Delivery Total</td><td align="right" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_deliverytotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		$del_str 	.= '</table>';
	}
	return $del_str;
}
/* Function to get the giftwrap related details */
function get_EmailGiftwrapDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	// ##############################################################################
	// Gift wrap details
	// ##############################################################################
	// Check whether gift wrap exists
	if($row_ords['order_giftwrap']=='Y')
	{
		$giftdet_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
			$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
			$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
			$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

		$giftdet_str		.= ' <tr>
                                    <td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top" colspan="2">Giftwrap Details</td>
                                </tr>';
		if ($row_ords['order_giftwrap_per']=='order')
		{
			$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Apply to </td><td align="left" width="50%" '.$style_desc.'>Order</td></tr>';
		}
		else
			$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Apply to </td><td align="left" width="50%" '.$style_desc.'>Individual Items</td></tr>';

		if ($row_ords['order_giftwrap_minprice']>0)
			$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Minimum Price for Gift wrap </td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_giftwrap_minprice'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';

		if ($row_ords['order_giftwrapmessage']=='Y')
		{
			$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Message</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_giftwrap_message_charge'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
			$giftdet_str		.= '<tr><td align="left" colspan="2" '.$style_desc.'>'.stripslashes($row_ords['order_giftwrapmessage_text']).'</td></tr>';
		}

		$sql_gift			= "SELECT giftwrap_name,giftwrap_price,giftwrap_price
								FROM
									order_giftwrap_details
								WHERE
									orders_order_id=$order_id";
		$ret_gift			= $db->query($sql_gift);
		if ($db->num_rows($ret_gift))
		{
			while ($row_gift = $db->fetch_array($ret_gift))
			{
				$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_gift['giftwrap_name']).'</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_gift['giftwrap_price'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
			}
		}
		$giftdet_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Gift Wrap Total</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_giftwraptotal'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		// Catting the total of gift wrap to giftdet_str variable
		$giftdet_str		.= '</table>';
	}
	else
		$giftdet_str = '';
	return $giftdet_str;
}
/* Function to get the bonus details */
function get_EmailBonusDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$bonus_str = '';
	// ##############################################################################
	// Bonus Points Checking
	// ##############################################################################
	$style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";
	$style_desc_prodname ="style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;text-decoration:none;'";

	if($row_ords['order_bonuspoints_used']>0 or $row_ords['order_bonuspoint_inorder']>0)// if bonus points used or bonus points achieved in currenr order
	{
		$bonus_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		if ($row_ords['order_bonuspoints_used']>0)
		{
			$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points Used</td><td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoints_used'].'</td></tr>';
		}
		if ($row_ords['order_bonuspoints_used']>0)
		{
			$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points Rate</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_bonusrate'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		}
		if($row_ords['order_bonuspoint_discount']>0)
		{
			$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points Discount</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_bonuspoint_discount'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		}
		if ($row_ords['order_bonuspoint_inorder']>0)
		{
			$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points gained due to this order</td><td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoint_inorder'].'</td></tr>';
		}
		$bonus_str 	.= 	'</table>';
	}
	/* Donate bonus Start */
	if($row_ords['order_bonuspoints_donated']>0)
	{
		$bonus_str	.= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		if ($row_ords['order_bonuspoints_donated']>0)
		{
			$bonus_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Bonus Points Donated</td><td align="left" width="50%" '.$style_desc.'>'.$row_ords['order_bonuspoints_donated'].'</td></tr>';
		}
		$bonus_str 	.= 	'</table>';
	}
	/* Donate bonus End */
	return $bonus_str;
}
/* Function to get the tax details */
function get_EmailTaxDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$tax_str = '';
	// ##############################################################################
	// Tax Details
	// ##############################################################################
    $style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	$style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";

	$sql_tax 	= "SELECT tax_name,tax_percent,tax_charge
					FROM
						order_tax_details
					WHERE
						orders_order_id = $order_id";
	$ret_tax	= $db->query($sql_tax);
	if ($db->num_rows($ret_tax))
	{
		$tax_str	= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		$tax_str	.=  '<tr>
                                    <td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top" colspan="2">Tax Details</td>
                        </tr>';
		while ($row_tax = $db->fetch_array($ret_tax))
		{
			$tax_str	.= '<tr><td align="left" width="50%" '.$style_head.'>'.stripslashes($row_tax['tax_name']).'('.$row_tax['tax_percent'].'%)'.'</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_tax['tax_charge'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		}
		$new_tot_tax_str = 'Total Tax';
		if($ecom_siteid == 87) // case of sumpandpump
			$new_tot_tax_str = 'VAT @ 20%';
		$tax_str		.= '<tr><td align="left" width="50%" '.$style_head.'>'.$new_tot_tax_str.'</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_ords['order_tax_total'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
		$tax_str 		.= 	'</table>';
	}
	return  $tax_str;
}

/* Function to get the promotional / gift voucher details */
function get_EmailPromVoucherDetails($order_id,$row_ords)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$prom_str = '';
	// ##############################################################################
	// Promotional Code or Gift Voucher Details
	// ##############################################################################
	  $style_head = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:bold;border-bottom:1px solid  #f8f8f8;'";
	  $style_desc = "style='padding:5px;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#5b5b5b;font-weight:normal;border-bottom:1px solid  #f8f8f8;'";

	if($row_ords['gift_vouchers_voucher_id'] or $row_ords['promotional_code_code_id'])
	{
		$prom_str		= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
		if($row_ords['gift_vouchers_voucher_id'])
		{
			// Get the gift voucher details
			$sql_voucher = "SELECT voucher_no,voucher_value_used
								FROM
									order_voucher
								WHERE
									orders_order_id = $order_id
								LIMIT
									1";
			$ret_voucher = $db->query($sql_voucher);
			if ($db->num_rows($ret_voucher))
			{
				$row_voucher 	= $db->fetch_array($ret_voucher);
				$prom_str	.=  '<tr>
                                    <td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top" colspan="2">Gift voucher Details</td>
                        		</tr>';
				$prom_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Gift Voucher Code</td><td align="left" width="50%" '.$style_desc.'>'.$row_voucher['voucher_no'].'</td></tr>';
				$prom_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Gift Voucher Discount</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_voucher['voucher_value_used'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
				$promtotal_str	= '<tr><td align="left" width="50%" colspan="2" '.$style_head.'>Gift Voucher Discount</td><td align="left" width="50%" colspan="3" '.$style_desc.'>'.print_price_selected_currency($row_voucher['voucher_value_used'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
			}

		}
		elseif($row_ords['promotional_code_code_id'])
		{
			// Get the promotional code details
			$sql_prom = "SELECT code_number,code_lessval,code_type
								FROM
									order_promotional_code
								WHERE
									orders_order_id = $order_id
								LIMIT
									1";
			$ret_prom = $db->query($sql_prom);
			if ($db->num_rows($ret_prom))
			{
				$row_prom 	= $db->fetch_array($ret_prom);
				$prom_str	.=  '<tr>
                                    <td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top" colspan="2">Promotional Code Details</td>
                        		</tr>';
				$prom_str	.= '<tr><td align="left" width="50%" '.$style_head.'>Promotional Code</td><td align="left" width="50%" '.$style_desc.'>'.$row_prom['code_number'].'</td></tr>';
				if ($row_prom['code_type']!='product') // show only if not of type 'product' if type is product discount will be shown with product listing
				{
					$prom_str		.= '<tr><td align="left" width="50%" '.$style_head.'>Promotional Code Discount</td><td align="left" width="50%" '.$style_desc.'>'.print_price_selected_currency($row_prom['code_lessval'],$row_ords['order_currency_convertionrate'],$row_ords['order_currency_symbol'],true).'</td></tr>';
				}
			}

		}
		$prom_str 		.= 	'</table>';
	}
	return $prom_str;
}
// Function to generate the starting of page number
function getStartOfPageno($recs,$pg)
{
	global $ecom_selfhttp;
	if(!is_numeric($pg) or $pg<1) $pg=1;
	if(!is_numeric($recs) or $recs<1) $recs=10;
	if($pg>1)
		return ($recs*($pg-1)+1);
	else
		return 1;
}
// ** Function to the combination Id if available
function get_combination_id($prodid,$var_arr)
{
	global $db,$ecom_siteid,$ecom_hostname;
	global $ecom_selfhttp;
	if(!is_array($var_arr))
	{
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}
	if(count($var_arr)==0)
	{
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}	
	$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
						product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,product_variablecombocommon_image_allowed  
					FROM
						products
					WHERE
						product_id=$prodid
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);
		foreach ($var_arr as $k=>$v)
		{
			// Check whether the variable is a check box or a drop down box
			$sql_check = "SELECT var_id
							FROM
								product_variables
							WHERE
								var_id=$k
								AND var_value_exists = 1
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$row_check 	= $db->fetch_array($ret_check);
				$varids[] 	= $k; // populate only the id's of variables which have values to the array
			}
		}
		if (count($varids))
		{
			if ($row_prod['product_variablestock_allowed'] == 'Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y') // Case if variable stock is maintained
			{
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id 
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{
	
						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}
							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
				else // case if variable stock is not maintained
				{
					$ret_data['combid']	= 0;
					return $ret_data; // return from function as soon as the combination found
				}
			}	
		}
	$ret_data['combid']			= 0;
	return $ret_data; // return from function as soon as the combination found
}
function CreateInvoice_file($email_orderid,$email_invoiceid,$email_name,$email_orderid1,$billing_cust_arr,$del_str,$giftdet_str,$bonus_str,$tax_str,$prod_str,$email_notes,$paystatus,$paytype,$paymethod,$email_date)
	{
		global $db,$ecom_siteid,$ecom_hostname,$image_path;
		global $ecom_selfhttp;
		$sql_template = "SELECT lettertemplate_contents 
						FROM
							general_settings_site_letter_templates
						WHERE
							sites_site_id = $ecom_siteid
							AND lettertemplate_letter_type = 'ORDER_CONFIRM_INVOICE'
						LIMIT
							1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 	= $db->fetch_array($ret_template);
			$email_content	= stripslashes($row_template['lettertemplate_contents']);
			$email_domain	= $ecom_hostname;
		}	
		$billing_cust_arr['order_custcompany'] = (trim($billing_cust_arr['order_custcompany'])!='')?$billing_cust_arr['order_custcompany']:' -- ';
		$search_arr = array
							(
									'[cust_name]',
									'[domain]',
									'[orderid]',
									'[invoiceid]',
									'[orderdate]',
									'[name]',
									'[company_name]',
									'[building_no]',
									'[street]',
									'[city]',
									'[region]',
									'[country]',
									'[zip]',
									'[phone]',
									'[mobile]',
									'[fax]',
									'[email]',
									'[giftwrap_details]',
									'[bonus_details]',
									'[tax_details]',
									'[product_details]',
									'[notes]',
									'[payment_status]',
									'[payment_type]',
									'[payment_method]',
									'[date]'
							);
				// Building the array to replace the values in above array
				$replace_arr = array
								(
									$email_name,
									$ecom_hostname,
									$email_orderid,
									'INV-'.$email_invoiceid,
									$email_date,
									stripslashes($email_name),
									stripslashes($billing_cust_arr['order_custcompany']),
									stripslashes($billing_cust_arr['order_buildingnumber']),
									stripslashes($billing_cust_arr['order_street']),
									stripslashes($billing_cust_arr['order_city']),
									stripslashes($billing_cust_arr['order_state']),
									stripslashes($billing_cust_arr['order_country']),
									stripslashes($billing_cust_arr['order_custpostcode']),
									stripslashes($billing_cust_arr['order_custphone']),
									stripslashes($billing_cust_arr['order_custmobile']),
									stripslashes($billing_cust_arr['order_custfax']),
									stripslashes($billing_cust_arr['order_custemail']),
									$giftdet_str,
									$bonus_str,
									$tax_str,
									$prod_str,
									$email_notes,
									'<paystat>'.$paystatus.'</paystat>',
									$paytype,
									$paymethod,
									$email_date
								);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
			// Destination folder
			$dest_folder = $image_path.'/invoices';
			if(!file_exists($dest_folder)) mkdir($dest_folder, 0777);
			$invoice_filename = 'INV-'.$email_invoiceid.'.html';
			// Saving the file as html
			$fp = fopen($dest_folder.'/'.$invoice_filename,'w');
			fwrite($fp,$email_content);
			fclose($fp);
			// Updating the order_invoice table with the filename
			$update_sql = "UPDATE order_invoice 
							SET 
								invoice_filename = '".$invoice_filename."' 
							WHERE 
								invoice_id = $email_invoiceid 
							LIMIT 
								1";
			$db->query($update_sql);
	}
	function headed_email($from,$to, $subject, $content, $attachments = array())
	{
		global $ecom_selfhttp;
			$m = new Mime($from,	// From
						  $to,											// To
						  "",		// CC
						  $subject, 									// Subject
						  "multipart/mixed");
			//$b1st_logo_id = $m->generate_cid();
			//$msoft_logo_id = $m->generate_cid();
	
			//$message = file("emails/template.html");
			//$message = implode("", $message);
			//$message = str_replace("{ title }", $subject, $message);
			//$message = str_replace("{ content }", $content, $message);
			//$message = str_replace("{ b1st_logo }", "cid:$b1st_logo_id", $message);
			//$message = str_replace("{ msoft_logo }", "cid:$msoft_logo_id", $message);
	
			$m->start_multipart("related");
			$m->insert_text("html", $content);
			//m->insert_image("emails/b1st-logo.jpg", $b1st_logo_id);
			//$m->insert_image("emails/$logo", $bshop_logo_id);
			$m->end_multipart();
	
			foreach($attachments as $attach) $m->insert_attachment($attach["type"], $attach["filename"]);
			$m->send();
	}	
	function sendOrderMailWithAttachment($email_orderid,$order_invoice_id,$email_to,$email_subject,$email_content,$pick_paystatus=0)
	{
		global $db,$ecom_siteid,$image_path;
		global $ecom_selfhttp;
		$sql_ptype= "SELECT order_paymenttype  
								FROM 
									orders 
								WHERE 
									order_id = $email_orderid 
								LIMIT 
									1";
		$ret_ptype = $db->query($sql_ptype);
		if($db->num_rows($ret_ptype))
		{
			$row_ptype = $db->fetch_array($ret_ptype);
		}
		// Get the name of file to be attached
		$sql_attach = "SELECT invoice_filename 
							FROM 
								order_invoice 
							WHERE 
								invoice_id = $order_invoice_id 
							LIMIT 
								1";
		$ret_attach = $db->query($sql_attach);
		if($db->num_rows($ret_attach))
		{
			$row_attach 		= $db->fetch_array($ret_attach);
			$full_filename		= $image_path.'/invoices/'.$row_attach['invoice_filename'];
			if($pick_paystatus==1) // if payment status is to be picked from the order table and update it in the invoice file before attaching it with confirmation mail
			{
				$sql_stat = "SELECT order_paystatus 
								FROM 
									orders 
								WHERE 
									order_id = $email_orderid 
								LIMIT 
									1";
				$ret_stat = $db->query($sql_stat);
				if ($db->num_rows($ret_stat))
				{
					$row_stat = $db->fetch_array($ret_stat);
					$cur_stat = getpaymentstatus_Name($row_stat['order_paystatus']);
					// Read the conten of invoice file
					$fp 			= fopen($full_filename,'r');
					$file_content 	= fread($fp,filesize($full_filename));
					fclose($fp);
					$file_content 	= preg_replace ("/<paystat>(.*)<\/paystat>/", "<paystat>".$cur_stat."</paystat>", $file_content);
					$fp				= fopen($full_filename,'w');
					fwrite($fp,$file_content);
					fclose($fp);
				}					
			}
			if($row_ptype['order_paymenttype']=='invoice')
				$order_conf_type = 'ORDER_CONFIRM_CUST_INVOICED';
			else
				$order_conf_type = 'ORDER_CONFIRM_CUST';
			// Get the from address for the email
			$sql_temp = "SELECT lettertemplate_from 
							FROM 
								general_settings_site_letter_templates 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND lettertemplate_letter_type = '".$order_conf_type."' 
							LIMIT 
								1";
			$ret_temp = $db->query($sql_temp);
			if ($db->num_rows($ret_temp))
			{
				$row_temp 	= $db->fetch_array($ret_temp);
				$email_from = stripslashes($row_temp['lettertemplate_from']);
			}					
			$file_type			= 'text/html';
			headed_email($email_from,$email_to, $email_subject, $email_content,array(array("filename" => $full_filename, "type" => $file_type)));
		}						
	}	
	
	/* Function to check whether a combo product is there in cart */
	function Is_matching_with_Combo_Product($comb_array,$cartProducts)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$sess_id 		= Get_session_Id_from();
		$cart_array		= array();
		if($comb_array['products_product_id']==$cartProducts['product_id'])
		{
			// Check whether variable message exists for current cart entry with values . If exists, then return with out proceeding to lower sections
			$sql_msgs = "SELECT message_id, message_value 
							FROM 
								cart_messages 
							WHERE 
								cart_id = ".$cartProducts['cart_id'];
			$ret_msgs = $db->query($sql_msgs);
			if($db->num_rows($ret_msgs))
			{
				return 0;
			}
			$sql_cart = "SELECT var_id,var_value_id 
							FROM 
								cart_variables 
							WHERE 
								cart_id = ".$cartProducts['cart_id'];
			$ret_cart = $db->query($sql_cart);
			if($db->num_rows($ret_cart))
			{
				while ($row_cart = $db->fetch_array($ret_cart))
				{
					$cart_var_arr[$row_cart['var_id']] = $row_cart['var_value_id'];
				}
			}		
			// get the combinations for this product
			$sql_comb = "SELECT comb_id
							FROM 
								combo_products_variable_combination 
							WHERE 
								combo_products_comboprod_id = ".$comb_array['comboprod_id'];
			$ret_comb = $db->query($sql_comb);
			if($db->num_rows($ret_comb))
			{
				while ($row_comb = $db->fetch_array($ret_comb))
				{
					$comb_var_arr= array();
					// Get the varid and varvalue id for the current combination 
					$sql_combval = "SELECT var_id, var_value_id 
										FROM 
											combo_products_variable_combination_map 
										WHERE 
											combo_products_variable_combination_comb_id=".$row_comb['comb_id'];
					$ret_combval = $db->query($sql_combval);
					if ($db->num_rows($ret_combval))
					{
						while ($row_combval = $db->fetch_array($ret_combval))
						{
							$comb_var_arr[$row_combval['var_id']] = $row_combval['var_value_id'];
						}
					}
					
					$retcartid = More_matching_with_Combo_Product($comb_array,$cartProducts,$comb_var_arr,$cart_var_arr);
					if ($retcartid)
						return $retcartid;
						
				}			
			}	
			else // case if variables does not exists for current product
				return $cartProducts['cart_id'];
				
			
		}	
	}
	/* Function to check whether a combo product is there in cart */
	function More_matching_with_Combo_Product($comb_array,$cartProducts,$comb_var_arr,$cart_var_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		
			// Check whether variable message exists for current cart entry with values . If exists, then return with out proceeding to lower sections
			$combvar_cnt = count($comb_var_arr);
			$cartvar_cnt = count($cart_var_arr);
			if($combvar_cnt>0 and $cartvar_cnt>0)
			{
				if($combvar_cnt==$cartvar_cnt)
				{
					foreach ($comb_var_arr as $k=>$v)
					{
						// Check whether value exists for variables
						$sql_check = "SELECT var_value_exists 
										FROM 
											product_variables 
										WHERE 
											var_id = $k 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
							if($row_check['var_value_exists']==1)
								$var_val_exists = 1;
							else
								$var_val_exists = 0;
						}	
						foreach ($cart_var_arr as $kk=>$vv)
						{
							if($k==$kk) // if both variable ids are same
							{
								if($var_val_exists==1)
								{
									if ($v==$vv)
										$same_cnt++;
								}
								else
								{
									$same_cnt++;
								}
							}
						}
					}
					if($same_cnt==$combvar_cnt)
					{
						return $cartProducts['cart_id'];
					}
				}
			}
			else
				 return $cartProducts['cart_id'];
			
			return 0;
	}
	function get_combo_variable_arr($combmap_combid)
	{
		global 	$ecom_siteid,$db,$ecom_hostname;
		global $ecom_selfhttp;
		$var_arr = array();
		$sql_combo = "SELECT var_id, var_value_id 
						FROM 
							combo_products_variable_combination_map 
						WHERE 
							combo_products_variable_combination_comb_id = $combmap_combid ";
		$ret_combo = $db->query($sql_combo);
		if($db->num_rows($ret_combo))
		{
			while ($row_combo = $db->fetch_array($ret_combo))
			{
				$var_arr[$row_combo['var_id']] = $row_combo['var_value_id'];
			}
		}
		return $var_arr;
	}
	function auto_linker($content) {
		global 	$ecom_siteid,$db,$ecom_hostname;
		global $ecom_selfhttp;
		$sql_auto_links = "SELECT autolinker_keyword,autolinker_url,autolinker_no_of_times,autolinker_allow_no_follow,autolinker_css_class FROM seo_autolinker WHERE sites_site_id=$ecom_siteid";
		$res_auto_links = $db->query($sql_auto_links);
		$replace_count = 0;
		$pos = strpos($content, '</p>');
		if($pos === false) {
			$seperator = ".";
		} else {
			$seperator = "</p>";
		}
		$subject = explode($seperator, $content);
		
		while($row_auto_links = $db->fetch_array($res_auto_links)) {
			if($row_auto_links['autolinker_allow_no_follow']) {
				$extra = "rel=\"nofollow\"";
			}
			if ($row_auto_links['autolinker_css_class']) {
				$extra .= " class=\"".$row_auto_links['autolinker_css_class']."\"";
			}
			foreach($subject as $k => $v) {
				$subject[$k] = preg_replace("/".$row_auto_links['autolinker_keyword']."/", "<a href=\"".$row_auto_links['autolinker_url']."\" title=\"".$row_auto_links['autolinker_keyword']."\" $extra>".$row_auto_links['autolinker_keyword']."</a>", $v, 1, $count);
				$replace_count += $count;
				if($replace_count >= $row_auto_links['autolinker_no_of_times']) {
					$replace_count = 0;
					break;
				}
			}
			reset($subject);	
			$extra = '';	
		}
		return implode($seperator,$subject);
	}
	function is_product_in_any_valid_combo($row_prod)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$sql_combo = "SELECT distinct combo_combo_id 
						FROM 
							combo_products 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND products_product_id = ".$row_prod['product_id'];
		$ret_combo = $db->query($sql_combo);
		if($db->num_rows($ret_combo))
		{
			while ($row_combo = $db->fetch_array($ret_combo))
			{
				$cur_combo_id = $row_combo['combo_combo_id'];
				$sql_main = "SELECT combo_id, combo_active, combo_activateperiodchange, combo_displaystartdate, combo_displayenddate 
								FROM 
									combo 
								WHERE 
									combo_id = $cur_combo_id 
									AND combo_active=1";
				$ret_main = $db->query($sql_main);
				if($db->num_rows($ret_main))
				{
					$row_main = $db->fetch_array($ret_main);
					if($row_main['combo_activateperiodchange']==0)
						return 1;
					else
					{
						// Check whether given period is valid
						$proceed	= validate_component_dates($row_main['combo_displaystartdate'],$row_main['combo_displayenddate']);
						if($proceed)
							return 1;	
					}
				}
			}
		}
		else
			return 0;
			
		return 0;
	}
	function Is_product_match_with_promotional_combination($prodvar_arr,$pcode_det_id)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		// get all combinations for current product from promotional_code_products_variable_combination
		$sql_comb = "SELECT comb_id, prom_price 
						FROM 
							promotional_code_products_variable_combination 
						WHERE 
							promotional_code_product_pcode_det_id=$pcode_det_id";
		$ret_comb = $db->query($sql_comb);
		if($db->num_rows($ret_comb))
		{
			while ($row_comb = $db->fetch_array($ret_comb))
			{
				$cur_var_arr = get_promotional_variable_arr($row_comb['comb_id']);
				$match_cnt=0;
				if(count($cur_var_arr)==count($prodvar_arr))
				{
					foreach($cur_var_arr as $k=>$v)
					{
						if (array_key_exists($k, $prodvar_arr))
						{ 
							// Check whether value exists for current variable
							$sql_var = "SELECT var_value_exists 
											FROM 
												product_variables 
											WHERE 
												var_id=".$k." 
											LIMIT 
												1";
							$ret_var = $db->query($sql_var);
							if($db->num_rows($ret_var))
							{
								$row_var = $db->fetch_array($ret_var);
							}
							if($row_var['var_value_exists']==1)
							{
								if($prodvar_arr[$k]==$v)
									$match_cnt++;
							}
							else
								$match_cnt++;
						}		
					}
					if($match_cnt==count($cur_var_arr))
					{
						$ret_arr['match_found']	= true;
						$ret_arr['prom_price'] 	= $row_comb['prom_price'];
						return $ret_arr;
					}
				}
			}
		}
		$ret_arr['match_found']	= false;
		return $ret_arr;
	}
	function get_promotional_variable_arr($combmap_combid)
	{
		global 	$ecom_siteid,$db,$ecom_hostname;
		global $ecom_selfhttp;
		$var_arr = array();
		$sql_combo = "SELECT var_id, var_value_id 
						FROM 
							promotional_code_products_variable_combination_map  
						WHERE 
							promotional_code_products_variable_combination_comb_id = $combmap_combid ";
		$ret_combo = $db->query($sql_combo);
		if($db->num_rows($ret_combo))
		{
			while ($row_combo = $db->fetch_array($ret_combo))
			{
				$var_arr[$row_combo['var_id']] = $row_combo['var_value_id'];
			}
		}
		return $var_arr;
	}
	function show_price_combo($prod_id,$comb_id)
	{
	  		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$PriceSettings_arr; 
	  		global $ecom_selfhttp;
	  			  $PriceSettings_arr['price_applydiscount_tovariable'];
	              $sql_prod	= "SELECT 
				  				a.product_id,
								a.product_webprice,a.product_discount,a.product_discount_enteredasval,
								a.product_variablecomboprice_allowed,
								a.product_variables_exists   
							FROM 
								products a
							WHERE 
								a.product_id =".$prod_id." 
								AND a.product_hide='N' 
								AND a.sites_site_id=$ecom_siteid 
								LIMIT 1";
					   $ret_prod	= $db->query($sql_prod);
		               $row_prod   =  $db->fetch_array($ret_prod);
					   $prod_webprice =  $row_prod['product_webprice'];
		               $var_price=0;
					   if( $row_prod['product_variablecomboprice_allowed']=='N')
						{
						   $sql_comb_det = "SELECT a.var_id,a.var_value_id 
											FROM 
												combo_products_variable_combination_map a,product_variables b
											WHERE 
												combo_products_variable_combination_comb_id = $comb_id 
											AND a.var_id=b.var_id AND b.products_product_id=".$prod_id." 
											ORDER BY b.var_order";
							$ret_comb_det = $db->query($sql_comb_det);
							if ($db->num_rows($ret_comb_det) )
							{ 
								while ($row_comb_det = $db->fetch_array($ret_comb_det))
								{
									$sql_var = "SELECT var_id,var_name,var_value_exists,var_price
												FROM 
													product_variables 
												WHERE 
													var_id = ".$row_comb_det['var_id']." 
												LIMIT 
													1";
									$ret_var = $db->query($sql_var);
									if($db->num_rows($ret_var))
									{
										$row_var = $db->fetch_array($ret_var);
									}
									if($row_var['var_value_exists'])
									{
									   $sql_var_price = "SELECT var_addprice 
														FROM 
															product_variable_data 
														WHERE 
														var_value_id=".$row_comb_det['var_value_id'];
									   $ret_var_price = $db->query($sql_var_price);
									   $row_var_price = $db->fetch_array($ret_var_price);
									   $var_price += $row_var_price['var_addprice']; 
									} 
									else
									{
									   $var_price +=$row_var['var_price'];
									} 
								}
								 $prod_tot_var = $row_prod['product_webprice'] + $var_price;
								 if($PriceSettings_arr['price_applydiscount_tovariable']==1)
								  {
									 if($row_prod['product_discount_enteredasval']==0)
									 { 
									   $prod_final = $prod_tot_var - $prod_tot_var * $row_prod['product_discount']/100;

									 }
									 elseif($row_prod['product_discount_enteredasval']==1)
									 {
									   $prod_final = $prod_tot_var - $row_prod['product_discount'];
									 }
									 elseif($row_prod['product_discount_enteredasval']==2)
									 {
										$prod_final = $row_prod['product_discount'];
									 }
								  }
								  else
								  {
									if($row_prod['product_discount_enteredasval']==0)
									 {
									   $prod_final = $prod_webprice + $var_price - ($prod_webprice * $row_prod['product_discount']/100) ;
									 }
									 elseif($row_prod['product_discount_enteredasval']==1)
									 {
									   $prod_final = $prod_tot_var - $row_prod['product_discount'];
									 }
									 elseif($row_prod['product_discount_enteredasval']==2)
									 {
										$prod_final = $row_prod['product_discount'];
									 }
									
								  }
							}
							else
							{ 
								  if($row_prod['product_discount_enteredasval']==0)
									 {
									   $prod_final = $prod_webprice - $prod_webprice * $row_prod['product_discount']/100;
									 }
									 elseif($row_prod['product_discount_enteredasval']==1)
									 {
									   $prod_final = $prod_webprice - $row_prod['product_discount'];
									 }
									 elseif($row_prod['product_discount_enteredasval']==2)
									 {
										$prod_final = $row_prod['product_discount'];
									 }
							}
						}
						else
						{  
						$sql_comb_det = "SELECT a.var_id,a.var_value_id 
								FROM 
									combo_products_variable_combination_map a,product_variables b
								WHERE 
									combo_products_variable_combination_comb_id = $comb_id 
								AND a.var_id=b.var_id AND b.products_product_id=".$prod_id." 
									ORDER BY b.var_order";
						$ret_comb_det = $db->query($sql_comb_det);
						if ($db->num_rows($ret_comb_det) )
						{ 
						$var_arr =array();
						while ($row_comb_det = $db->fetch_array($ret_comb_det))
						{
							$curvar_id= $row_comb_det['var_id'];
							$var_arr[$curvar_id] = $row_comb_det['var_value_id'];
							$sql_var = "SELECT var_id,var_name,var_value_exists,var_price
									FROM 
										product_variables 
									WHERE 
										var_id = ".$row_comb_det['var_id']." 
									LIMIT 
										1";
							$ret_var = $db->query($sql_var);
							if($db->num_rows($ret_var))
							{
								$row_var = $db->fetch_array($ret_var);
							}
							if($row_var['var_value_exists']==0)
							{
								$var_price +=$row_var['var_price'];
							} 
						}
						//print_r($var_arr);
						}	
						$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
						$sql_price = "SELECT comb_price 
									FROM 
										product_variable_combination_stock 
									WHERE comb_id=".$comb_arr['combid']." 
									AND products_product_id=".$row_prod['product_id']."";
						$ret_price = $db->query($sql_price);
						$row_price = $db->fetch_array($ret_price);
						$prod_comb_price = $row_price['comb_price'];
						$prod_tot_var = $prod_comb_price + $var_price;
						if($PriceSettings_arr['price_applydiscount_tovariable']==1)
						{
							if($row_prod['product_discount_enteredasval']==0)
							{
								$prod_final = $prod_tot_var - $prod_tot_var * $row_prod['product_discount']/100;
							}
							elseif($row_prod['product_discount_enteredasval']==1)
							{
								$prod_final = $prod_tot_var - $row_prod['product_discount'];
							}
							elseif($row_prod['product_discount_enteredasval']==2)
							{
								$prod_final = $row_prod['product_discount'];
							}
						}
						else
						{
							if($row_prod['product_discount_enteredasval']==0)
							{
								$prod_final = $prod_comb_price + $var_price - ($prod_comb_price * $row_prod['product_discount']/100) ;
							}
							elseif($row_prod['product_discount_enteredasval']==1)
							{
								$prod_final = $prod_tot_var - $row_prod['product_discount'];
							}
							elseif($row_prod['product_discount_enteredasval']==2)
							{
								$prod_final = $row_prod['product_discount'];
							}
						}
						}

						return $prod_final;
}
function price_promise_status($stat)
{
	global $ecom_selfhttp;
	switch ($stat)
	{
		case 'Accept':
			$msg = 'Accepted';
		break;
		case 'Reject':
			$msg = 'Rejected';
		break;
		case 'New':
			$msg = 'Pending';
		break;
		default:
			$msg = $stat;
		break;
	};
	return $msg;
}
function var_color_display_check($var_name)
{
	global $ecom_selfhttp;
	$clr_arr	= array ('color','colour','colors','colours');
	$tmp_var	= strtolower($var_name);
	for($i=0;$i<count($clr_arr);$i++)
	{
		$pos = strpos($tmp_var, $clr_arr[$i]);
		if($pos===false)
		{
		
		}
		else
			return true;
	}
	return false;
}
function show_cart_bonus_point_section($cartData,$cust_id)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$proceed_bonus = false;
	if (is_Feature_exists('mod_bonuspoints'))
	{  
		if($cust_id)
		{
			if ($cartData["customer"]["customer_bonus"] or $cartData["totals"]["bonus"]>0) // does current customer have bonus points
			{
				$proceed_bonus =true;
			}
		}
		else
		{
			if($cartData["totals"]["bonus"]>0)
			 $proceed_bonus =true;
		}	
	}
	return $proceed_bonus;
}
function handle_variable_color_section($row_vals,$first_val,$color_type)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$image_icontype =$row_vals['image_icontype'] ;
	if($image_icontype!='')
	{
	$img_def  = $row_vals['image_icontype'];
	}
	else
	{
	 $img_def  = 'image_iconpath';
	}
	if($first_val=='')
		$first_val = $row_vals['var_value_id'];
	$show_value		= true;
	$clr_val = '';
	$color_code 		= trim($row_vals['var_colorcode']);
	$images_image_id	= $row_vals['images_image_id'];
	$normal_cls 	= 	$special_cls 	= '';
	$color_image = false;
	
	if ($images_image_id) // check whether image directly asigned
	{
		// Get the path of image
		$sql_img = "SELECT a.image_id,a.image_thumbpath,a.$img_def,a.images_directory_directory_id 
						FROM 
							images a 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.image_id=".$images_image_id." 
						LIMIT 
							1";	
		$ret_img = $db->query($sql_img);
		if($db->num_rows($ret_img))
		{
			$row_img = $db->fetch_array($ret_img);
		}
		
		if($image_icontype!='')
		{
		$image_type = $row_img[$image_icontype];
		}
		else
		{
		$image_type = $row_img['image_thumbpath'];
		}	
		$clr_val 		= ' style="background:url('.url_root_image($image_type,1).') left top no-repeat" ';
		/*$normal_cls 	= "color_div";
		$special_cls 	= "color_div_sel";*/
		$show_value		= false;
		$color_image	= true;
	}
	else // case if image not assigned directly
	{
		if($color_type==true) // check whether the variable is of color type
		{
			// Check whether current value is there in general_settings_site_colors table
			$sql_clr = "SELECT color_hexcode,images_image_id  
							FROM 
								general_settings_site_colors 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND LOWER(color_name)='".addslashes(strtolower($row_vals['var_value']))."' 
							LIMIT 
								1";
			$ret_clr = $db->query($sql_clr);
			if($db->num_rows($ret_clr))
			{
				$row_clr = $db->fetch_array($ret_clr);
				$images_image_id	= $row_clr['images_image_id'];
			}
			if ($images_image_id) // case if image assigned to common color
			{
				// Get the path of image
				$sql_img = "SELECT a.image_id,a.image_thumbpath,a.$img_def,a.images_directory_directory_id 
								FROM 
									images a 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND a.image_id=".$images_image_id." 
								LIMIT 
									1";	
				$ret_img = $db->query($sql_img);
				if($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
				}	
				if($image_icontype!='')
				{
				$image_type = $row_img[$image_icontype];
				}
				else
				{
				$image_type = $row_img['image_thumbpath'];
				}	
				$clr_val 		= ' style="background:url('.url_root_image($image_type,1).') left top no-repeat"';
				/*$normal_cls 	= "color_div";
				$special_cls 	= "color_div_sel";*/

				$show_value	= false;
				$color_image	= true;
			}
			else // case if image id not assigned to common color
			{
				if($color_code!='') // check whether color code assigned directly
				{	
					$clr_val 		= ' style="background:'.$color_code.'"';
					/*$normal_cls 	= "color_div";
					$special_cls 	= "color_div_sel";*/
					$show_value		= false;
				}
				else
				{
					$color_code = stripslashes(trim($row_clr['color_hexcode']));
					if($color_code!='') // check whether color code assigned to common color
					{
						$clr_val 		= ' style="background:'.$color_code.'"';
						/*$normal_cls 	= "color_div";
						$special_cls 	= "color_div_sel";*/
						$show_value		= false;
					}	
				}
			}
			/*if($color_code=='') // case if color code is not assigned directly
			{
				// Check whether current value is there in general_settings_site_colors table
				$sql_clr = "SELECT color_hexcode,images_image_id  
								FROM 
									general_settings_site_colors 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND LOWER(color_name)='".strtolower($row_vals['var_value'])."' 
								LIMIT 
									1";
				$ret_clr = $db->query($sql_clr);
				if($db->num_rows($ret_clr))
				{
					$row_clr = $db->fetch_array($ret_clr);
					$color_code = stripslashes(trim($row_clr['color_hexcode']));
					$images_image_id	= $row_vals['images_image_id'];
				}
			}*/
				
		}
	}
	if($show_value)
	{
		$normal_cls 	= "size_var_div";
		$special_cls 	= "size_var_div_sel";
	}
	else
	{
		if($color_image)
		{
			$normal_cls 	= "colorimg_div";
			$special_cls 	= "colorimg_div_sel";
		}
		else
		{
			$normal_cls 	= "color_div";
			$special_cls 	= "color_div_sel";
		}	
	}
	$ret_arr				= array();										
	$ret_arr['normal_cls'] 	= $normal_cls;
	$ret_arr['special_cls'] = $special_cls;	
	$ret_arr['clr_val'] 	= $clr_val;	
	$ret_arr['show_value'] 	= $show_value;	
	return $ret_arr;
}
function write_email_as_file($mod,$id,$content)
{
	global $image_path,$db;
	global $ecom_selfhttp;
	$fname 	= $id.'.txt';
	$folder = '';
	switch($mod)
	{
		case 'ord':
			if(!is_dir($image_path.'/email_messages'))
			{
			 	mkdir($image_path.'/email_messages',0777);
			} 
			if(!is_dir($image_path.'/email_messages/order_emails'))
			{
			 	mkdir($image_path.'/email_messages/order_emails',0777);
			} 
			$folder	= 'email_messages/order_emails';
			$sql_update = "UPDATE order_emails 
							SET 
								email_messagepath='".$folder.'/'.$fname."' 
							WHERE 
								email_id = $id 
							LIMIT 
								1";
			$db->query($sql_update);
		break;
		case 'vouch':
			if(!is_dir($image_path.'/email_messages'))
			{
			 	mkdir($image_path.'/email_messages',0777);
			} 
			if(!is_dir($image_path.'/email_messages/gift_voucher_emails'))
			{
			 	mkdir($image_path.'/email_messages/gift_voucher_emails',0777);
			} 
			$folder	= 'email_messages/gift_voucher_emails';
			$sql_update = "UPDATE gift_voucher_emails  
							SET 
								email_messagepath='".$folder.'/'.$fname."' 
							WHERE 
								email_id = $id 
							LIMIT 
								1";
			$db->query($sql_update);
		break;
	};
	if($folder)
	{
		$fp = fopen($image_path.'/'.$folder.'/'.$fname,'w');
		fwrite($fp,$content);
		fclose($fp);
	}
}
function read_email_from_file($mod,$id)
{
	global $image_path,$db;
	global $ecom_selfhttp;
	switch($mod)
	{
		case 'ord':
			$sql_sel = "SELECT email_messagepath 
							FROM 
								order_emails 
							WHERE 
								email_id = $id 
							LIMIT 
								1";
			$ret_sel = $db->query($sql_sel);
			if($db->num_rows($ret_sel))
			{
				$row_sel = $db->fetch_array($ret_sel);
				$file_path = $row_sel['email_messagepath'];
			}
		break;
		case 'vouch':
			$sql_sel = "SELECT email_messagepath 
							FROM 
								gift_voucher_emails  
							WHERE 
								email_id = $id 
							LIMIT 
								1";
			$ret_sel = $db->query($sql_sel);
			if($db->num_rows($ret_sel))
			{
				$row_sel = $db->fetch_array($ret_sel);
				$file_path = $row_sel['email_messagepath'];
			}
		break;
	};	
	// read the contents of file
	$full_file_path = $image_path.'/'.$file_path;
	$fp = fopen($full_file_path,'r');
	$content = fread($fp,filesize($full_file_path));
	fclose($fp);
	return $content;
}
function google_analytics_ecom_tracking_code($order_id)
{
	global $db,$ecom_hostname,$ecom_siteid,$ecom_isecomtracker,$ecom_ecomtrackercode;
	global $ecom_selfhttp;
	
	//if($ecom_siteid==95) // case of demo2.bsho4.co.uk
	{
		if($ecom_isecomtracker == 1 and $order_id)
		{
			// Check whether the order id is valid
			$track_arr = explode(',',$ecom_ecomtrackercode);
			// Get the details of current order from orders table
			$sql_ord = "SELECT order_city, order_state, order_country,order_deliverytotal,
								order_tax_total, order_totalprice 
							FROM 
								orders 
							WHERE 
								order_id = $order_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{	
				$row_ord = $db->fetch_array($ret_ord);		
				$storename = ($ecom_siteid==72)?'checkout':$ecom_hostname;			
				foreach ($track_arr as $kkey=>$vval)
				{
				?>
					<script type="text/javascript">
					  var _gaq = _gaq || [];
					  _gaq.push(['_setAccount', '<?php echo $vval?>']);
					  _gaq.push(['_trackPageview']);
					  _gaq.push(['_addTrans',
						'<?php echo $order_id?>', // order ID - required
						'<?php echo $storename?>', // affiliation or store name
						'<?php echo $row_ord['order_totalprice']?>', // total - required
						'<?php echo $row_ord['order_tax_total']?>', // tax
						'<?php echo $row_ord['order_deliverytotal']?>', // shipping
						'<?php echo addslashes(stripslashes($row_ord['order_city']))?>', // city
						'<?php echo addslashes(stripslashes($row_ord['order_state']))?>', // state or province
						'<?php echo addslashes(stripslashes($row_ord['order_country']))?>' // country
					  ]);
					<?php
						// Get the details of current order
						$sql_orddet = "SELECT orderdet_id,products_product_id, product_name, order_orgqty,
											product_soldprice,order_stock_combination_id 
										FROM 
											order_details 
										WHERE 
											orders_order_id = $order_id";
						$ret_orddet = $db->query($sql_orddet);
						while ($row_orddet = $db->fetch_array($ret_orddet))
						{
								$var_details = '';
								$var_comb_str = '';
								// Check whether any variables exists for current order
								$sql_vars = "SELECT var_name,var_value,var_id  
												FROM 
													order_details_variables 
												WHERE 
													order_details_orderdet_id = ".$row_orddet['orderdet_id'];
								$ret_vars = $db->query($sql_vars);
								if($db->num_rows($ret_vars))
								{
									while ($row_vars = $db->fetch_array($ret_vars))
									{
											if($var_details!='')
												$var_details .= ',';
											
											$var_details .= $row_vars['var_name'];
											if($var_comb_str!='')
											{
												$var_comb_str .='C';
											}	
											$var_comb_str .=$row_vars['var_id'];
											if(trim($row_vars['var_value'])!='')
											{
												$var_details .= ':'.$row_vars['var_value'];
												// Get the id of the variable value
												$sql_vid = "SELECT var_value_id 
													FROM 
														product_variable_data 
													WHERE 
														product_variables_var_id = ".$row_vars['var_id']." 
														AND var_value='".addslashes($row_vars['var_value'])."' 
													LIMIT 
														1";
												$ret_vid = $db->query($sql_vid);
												if($db->num_rows($ret_vid))
												{
													$row_vid = $db->fetch_array($ret_vid);
													if($var_comb_str!='')
													{
														$var_comb_str .='C';
													}	
													$var_comb_str .=$row_vid['var_value_id'];
												}
											}		
												
									}
								}
								// Check whether any variables messages exists for current order
								$sql_vars = "SELECT message_caption,message_value
												FROM 
													order_details_messages  
												WHERE 
													order_details_orderdet_id = ".$row_orddet['orderdet_id'];
								$ret_vars = $db->query($sql_vars);
								if($db->num_rows($ret_vars))
								{
									while ($row_vars = $db->fetch_array($ret_vars))
									{
											if($var_details!='')
												$var_details .= ',';
											
											$var_details .= $row_vars['message_caption'];
											if(trim($row_vars['message_value'])!='')
												$var_details .= ':'.$row_vars['message_value'];
									}
								}
								$curcombid = trim($row_orddet['order_stock_combination_id']);
								if($curcombid)
								{
									$cursku = $row_orddet['products_product_id'].'CO'.$curcombid;
								}
								else
								{
									if($var_comb_str!='')
									{
										$var_comb_str = $row_orddet['products_product_id'].'C'.$var_comb_str;
										$cursku = $var_comb_str;
									}
									else
									{
										$cursku = $row_orddet['products_product_id'];		
									}		
								}
								
								$categorynames = 'Default';
								// get the name of default category id for this product
								$sql_prods = "SELECT product_default_category_id 
												FROM products 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND product_id =".$row_orddet['products_product_id']." LIMIT 1";
								$ret_prods = $db->query($sql_prods);
								if($db->num_rows($ret_prods))
								{
									$row_prods = $db->fetch_array($ret_prods);
									$sql_cats = "SELECT category_name FROM product_categories WHERE category_id=".$row_prods['product_default_category_id']." AND sites_site_id = $ecom_siteid LIMIT 1";
									$ret_cats = $db->query($sql_cats);
									if($db->num_rows($ret_cats))
									{
										$row_cats = $db->fetch_array($ret_cats);
										$categorynames =$row_cats['category_name'];
									}
								}
													
					?>	
							   // add item might be called for every item in the shopping cart
							   // where your ecommerce engine loops through each item in the cart and
							   // prints out _addItem for each
								_gaq.push(['_addItem',
								'<?php echo $order_id?>', // order ID - required
								'<?php echo $cursku?>', // SKU/code - required
								'<?php echo addslashes(stripslashes($row_orddet['product_name']))?>', // product name
								'<?php echo addslashes(stripslashes($categorynames))?>', // category or variation
								'<?php echo $row_orddet['product_soldprice']?>', // unit price - required
								'<?php echo $row_orddet['order_orgqty']?>' // quantity - required
							  ]);
					<?php
						}
					?>		
					  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

					  (function() {
						var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						ga.src = ('https:' == document.location.protocol ? 'https://ssl':'http://www')+ '.google-analytics.com/ga.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					  })();
					</script>
				<?php	
				}
			}		
		}
	}
	/*else
	{
		if($ecom_isecomtracker == 1 and $order_id)
		{
			// Check whether the order id is valid
			$track_arr = explode(',',$ecom_ecomtrackercode);
			// Get the details of current order from orders table
			$sql_ord = "SELECT order_city, order_state, order_country,order_deliverytotal,
								order_tax_total, order_totalprice 
							FROM 
								orders 
							WHERE 
								order_id = $order_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{	
				$row_ord = $db->fetch_array($ret_ord);		
				$storename = ($ecom_siteid==72)?'checkout':$ecom_hostname;			
				foreach ($track_arr as $kkey=>$vval)
				{
				?>
					<script type="text/javascript">
					  var _gaq = _gaq || [];
					  _gaq.push(['_setAccount', '<?php echo $vval?>']);
					  _gaq.push(['_trackPageview']);
					  _gaq.push(['_addTrans',
						'<?php echo $order_id?>', // order ID - required
						'<?php echo $storename?>', // affiliation or store name
						'<?php echo $row_ord['order_totalprice']?>', // total - required
						'<?php echo $row_ord['order_tax_total']?>', // tax
						'<?php echo $row_ord['order_deliverytotal']?>', // shipping
						'<?php echo addslashes(stripslashes($row_ord['order_city']))?>', // city
						'<?php echo addslashes(stripslashes($row_ord['order_state']))?>', // state or province
						'<?php echo addslashes(stripslashes($row_ord['order_country']))?>' // country
					  ]);
					<?php
						// Get the details of current order
						$sql_orddet = "SELECT orderdet_id,products_product_id, product_name, order_orgqty,
											product_soldprice 
										FROM 
											order_details 
										WHERE 
											orders_order_id = $order_id";
						$ret_orddet = $db->query($sql_orddet);
						while ($row_orddet = $db->fetch_array($ret_orddet))
						{
								$var_details = '';
								// Check whether any variables exists for current order
								$sql_vars = "SELECT var_name,var_value 
												FROM 
													order_details_variables 
												WHERE 
													order_details_orderdet_id = ".$row_orddet['orderdet_id'];
								$ret_vars = $db->query($sql_vars);
								if($db->num_rows($ret_vars))
								{
									while ($row_vars = $db->fetch_array($ret_vars))
									{
											if($var_details!='')
												$var_details .= ',';
											
											$var_details .= $row_vars['var_name'];
											if(trim($row_vars['var_value'])!='')
												$var_details .= ':'.$row_vars['var_value'];
									}
								}
								// Check whether any variables messages exists for current order
								$sql_vars = "SELECT message_caption,message_value
												FROM 
													order_details_messages  
												WHERE 
													order_details_orderdet_id = ".$row_orddet['orderdet_id'];
								$ret_vars = $db->query($sql_vars);
								if($db->num_rows($ret_vars))
								{
									while ($row_vars = $db->fetch_array($ret_vars))
									{
											if($var_details!='')
												$var_details .= ',';
											
											$var_details .= $row_vars['message_caption'];
											if(trim($row_vars['message_value'])!='')
												$var_details .= ':'.$row_vars['message_value'];
									}
								}
													
					?>	
							   // add item might be called for every item in the shopping cart
							   // where your ecommerce engine loops through each item in the cart and
							   // prints out _addItem for each
								_gaq.push(['_addItem',
								'<?php echo $order_id?>', // order ID - required
								'<?php echo $row_orddet['products_product_id']?>', // SKU/code - required
								'<?php echo addslashes(stripslashes($row_orddet['product_name']))?>', // product name
								'<?php echo addslashes(stripslashes($var_details))?>', // category or variation
								'<?php echo $row_orddet['product_soldprice']?>', // unit price - required
								'<?php echo $row_orddet['order_orgqty']?>' // quantity - required
							  ]);
					<?php
						}
					?>		
					  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

					  (function() {
						var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						ga.src = ('https:' == document.location.protocol ? 'https://ssl':'http://www')+ '.google-analytics.com/ga.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					  })();
					</script>
				<?php	
				}
			}		
		}	
	}*/
}
function get_proddet_available_date ($row_prod,$var_arr=array())
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$ret_date = '';
	// Check whether variable stock is ticked for this product
	if($row_prod['product_variablestock_allowed'] =='Y')
	{
		$sql_prods1 = "SELECT product_instock_date
					FROM
						products
					WHERE
						product_id=".$row_prod['product_id']."
						AND sites_site_id=$ecom_siteid
						AND product_preorder_allowed='Y'
						AND product_total_preorder_allowed > 0
					LIMIT
						1";
		$ret_prods1 = $db->query($sql_prods1);
		if ($db->num_rows($ret_prods1))
		{
			$row_prods1 				= $db->fetch_array($ret_prods1);
			$indate_arr				= explode("-",$row_prods1['product_instock_date']);
			$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
			$ret_date				= $indate;
		}
		else
		{
				$sql_prods = "SELECT product_alloworder_notinstock,product_order_outstock_instock_date 
						FROM
							products
						WHERE
							product_id=".$row_prod['product_id']."
							AND sites_site_id=$ecom_siteid
						LIMIT
							1";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
					$row_prods				= $db->fetch_array($ret_prods);
					if($row_prods['product_alloworder_notinstock']=='Y' and $row_prods['product_order_outstock_instock_date']!='' and $row_prods['product_order_outstock_instock_date']!='0000-00-00')
					{
						if(count($var_arr)==0) // case if variable details does not exists
						{
							// get the list of all variables with values for current product which is not hidden.
							$sql_vars = "SELECT var_id FROM product_variables WHERE products_product_id = ".$row_prod['product_id']." AND var_value_exists = 1 AND var_hide = 0 ORDER BY var_order";
							$ret_vars = $db->query($sql_vars);
							if($db->num_rows($ret_vars))
							{
								while ($row_vars = $db->fetch_array($ret_vars))
								{
									// get the id of first value of the current variable
									$sql_vals = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id =".$row_vars['var_id']." ORDER BY var_order LIMIT 1";
									$ret_vals = $db->query($sql_vals);
									if($db->num_rows($ret_vals))
									{
										$row_vals = $db->fetch_array($ret_vals);
										$var_arr[$row_vars['var_id']] = $row_vals['var_value_id'];
									}
								}
							}
						}
						// Check the stock details here
						$stock_arr			= check_stock_available($row_prod['product_id'],$var_arr);
						$stock				= $stock_arr['stock'];
						if($stock==0)
						{
							$indate_arr				= explode("-",$row_prods['product_order_outstock_instock_date']);
							$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
							$ret_date	= $indate;
						}
					}
				}
					
			}
	}
	else // case if fixed stock
	{
		if($row_prod['product_webstock']==0)
		{
			$sql_prods1 = "SELECT product_instock_date
						FROM
							products
						WHERE
							product_id=".$row_prod['product_id']."
							AND sites_site_id=$ecom_siteid
							AND product_preorder_allowed='Y'
							AND product_total_preorder_allowed > 0
						LIMIT
							1";
			$ret_prods1 = $db->query($sql_prods1);
			if ($db->num_rows($ret_prods1))
			{
				$row_prods1 				= $db->fetch_array($ret_prods1);
				$indate_arr				= explode("-",$row_prods1['product_instock_date']);
				$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
				$ret_date				= $indate;
			}
			else
			{
				$sql_prods = "SELECT product_alloworder_notinstock,product_order_outstock_instock_date 
								FROM
									products
								WHERE
									product_id=".$row_prod['product_id']."
									AND sites_site_id=$ecom_siteid
								LIMIT
									1";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
					$row_prods 				= $db->fetch_array($ret_prods);
					if($row_prods['product_alloworder_notinstock']=='Y' and $row_prods['product_order_outstock_instock_date']!='' and $row_prods['product_order_outstock_instock_date']!='0000-00-00')
					{
						$indate_arr				= explode("-",$row_prods['product_order_outstock_instock_date']);
						$indate					= date('d-M-Y',mktime(0,0,0,$indate_arr[1],$indate_arr[2],$indate_arr[0]));
						$ret_date				= $indate;
					}
				}	
			}	
			
		}
	}
	return $ret_date;
}
// Function to show the add to cart link
	function show_addtocart_v5($prod_arr,$class_arr,$frm,$return = false,$prefix='',$suffix='',$istable=false,$class_tdarr=array(),$override_hideqty=0)
	{ 
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
		global $ecom_selfhttp;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		$addto_cart_withajax    = $Settings_arr['enable_ajax_in_site'];//checking for the ajax function for adding to cart is enabled or not

		//to sheck whether quantity box should be shown or not
		$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		$quantity_div_class     = ($class_arr['QTY_TD']!='')?$class_arr['QTY_TD']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';   
		$class_qty = $class_tdarr['QTY'];
		$class_txt = $class_tdarr['TXT'];
		$class_btn = $class_tdarr['BTN'];
		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty && $override_hideqty!=1)
		{
			$quantity_box  = '<td style="text-align:right;vertical-align:middle" class="'.$class_qty.'">';
			if($prefix!='')
			{
				  $quantity_box  .=  $prefix;
			}
			$quantity_box  .= $Captions_arr['COMMON']['COMMON_QTY'].'</td>
							 <td style="text-align:left;vertical-align:middle" class="'.$class_txt.'"> <input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></td>';
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
			{
				if($ecom_siteid==102)// case of kqf
				{
					echo "<div class='red_loginforprice_big'>Login to buy</div>";	
				}
				return;
			}
		}
		$show_buy_now = false;
		$variable_check_forajax = false;	
		
		if($ecom_siteid==104) /* DM Website*/
		{
			// Insurance check array
			$insurance_chk_arr = array(532537,532540,532541,540424);				
		}
		
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{
			$variable_check_forajax = true;
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// if show cart link is set to display
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{
					//if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
					*/	
					//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1,0,0);
						$quantity_box_display = true;
					//}
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					/*if ($var_exists){*/
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
					}*/
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						/*if ($var_exists){*/
							//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						/*}
						else {
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
						}*/
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					/*if ($var_exists){*/
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					/*}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
					}*/
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{
			
			// Check whether variables exists for current product
			/*$sql_prod = "SELECT var_id
							FROM
								product_variables
							WHERE
								products_product_id=".$prod_arr['product_id']."
								AND var_hide = 0
							LIMIT
								1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$var_exists = true;
			}
			else
				$var_exists = false;*/
			
			if($prod_arr['product_variables_exists']=='Y')
			{
				$var_exists = true;
				$variable_check_forajax = true;	//this is for checking for variable exists for ajax enabled cart adding
			}
			else
			{
				$var_exists = false;	
				$variable_check_forajax = false;				
			}
				
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{
				
				//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// show preorder link only if show cart link is set to display
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					//if($prod_arr['product_show_cartlink']==1 and $prod_arr['product_webprice']>0)// show preorder link only if show cart link is set to display
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$curtype	= 'Prod_Preorder';						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						//$link 		= url_product($prod_arr['product_id'],$prod_arr['product_name'],1);
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}	
		if(($ecom_siteid==95 || $ecom_siteid==61) AND $curtype=='Prod_Enquire' AND $variable_check_forajax==false)		
		{
			$addto_cart_withajax =0;
		}
		
		if ($return==true)
		{
			return $mod;
		}	
		elseif ($link)
		{  
		
		$class_qty = $class_tdarr['QTY'];
		$class_txt = $class_tdarr['TXT'];
		$class_btn = $class_tdarr['BTN'];
		if($addto_cart_withajax==1)
		{ 
			$link ="";
			
			$hide_ajax_divholder_here = false;
			if (($_REQUEST['req']=='' or $_REQUEST['req']=='categories') and ($ecom_siteid==104 or $ecom_siteid==74))
			{
				$hide_ajax_divholder_here = true;
			}
			if(!$hide_ajax_divholder_here)
			{
		   ?>
		   	<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		   <?php
			}
		   ?>	
			<input type='hidden' name='fproduct_id' value="<?=$prod_arr['product_id']?>"/>
		   	<input type='hidden' name='product_id' value="<?=$prod_arr['product_id']?>"/>
			<input type="hidden" <?php echo (!$hide_ajax_divholder_here)?'id="product_id_ajax"':''?> name="product_id" value="<?=$prod_arr['product_id']?>" />
			<input type='hidden' name='ajaxform_name' <?php echo (!$hide_ajax_divholder_here)?'id="ajaxform_name"':''?> value="<?=$frm?>"/>
		   <?php
			if ($variable_check_forajax){					 
			$link  ="ajax_addto_cart_fromlist('show_prod_det_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
			else
			{		
			//$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
			$link  ="ajax_addto_cart_fromlist('add_prod_tocart_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
			
			if($ecom_siteid==104) /* DM Website*/
			{
				if(in_array($prod_arr['product_id'],$insurance_chk_arr))
				{
					$link = "javascript:window.location='".url_product($prod_arr['product_id'],$prod_arr['product_name'],1)."'";
				}	
			}
			
	    }
        //$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
		$btn_box  ='<td style="text-align:left;vertical-align:middle" class="'.$class_btn.'"> <input value="'.$caption.'" name="'.$caption.'" type="button" onclick="'.$link.'" /></td>';
		if($istable=='true')
		{
		?>
		 <table style="padding:0;border-spacing:0; border:0;width:100%" <?php/*width="100%" border="0" cellspacing="0" cellpadding="0"*/?>>
			  <tr>			  
			  <?php
			  if($quantity_box_display)
			   echo $quantity_box;?>
			  <?php echo $btn_box ;?>
			 
			</tr>
			</table>
			<input type="hidden" name="prod_list_submit_common" value="<?php echo $curtype?>" />			
		<?php
		}		
		    if($suffix!='')
			echo $suffix;
		}
	}
	// Function to get image property for mobile theme
	function image_property($path)
	{
		global $ecom_selfhttp;
		$imgParams		=	array();
		$imgProperty	=	array();
		if($path != "")
		{
			$imgParams	=	getimagesize($path);
			
			$imgProperty['width']	=	$imgParams[0];
			$imgProperty['height']	=	$imgParams[1];
			$imgProperty['type']	=	$imgParams['mime'];
		}
		return $imgProperty;
	}
	
	// Function to display a given image with a title for mobile theme
	function show_image_mobile($img,$alt,$title,$class='',$id='',$ret=0,$width = '',$height = '')
	{ 
		global $ecom_selfhttp;
		$sr_arr = array('"',"'");
		$rp_arr = array('','');
		if($alt!='')
			$alt 	= stripslashes(str_replace($sr_arr,$rp_arr,$alt));
		if($title!='')
			$title 	= stripslashes(str_replace($sr_arr,$rp_arr,$title));
		if ($alt=='')
			$alt = $title;
		if ($title=='')
			$title = $alt;
		if($class!='')
			$class = 'class="'.$class.'"';
		if($id!='')
			$id = 'id="'.$id.'"';
		if($width!='')
			$width = 'width="'.$width.'"';
		if($height!='')
			$height = 'height="'.$height.'"';
		if($ret==0)
		{
?>			<img src="<?php echo $img ?>" alt="<?php echo $alt?>" title="<?php echo $title?>" border="0" <?php echo $class;?> <?php echo $id;?> <?php echo $width;?>  <?php echo $height;?> />
<?php	}
		elseif($ret==1)
			return '<img src="'.$img.'" alt="'.$alt.'" title="'.$title.'" border="0" '.$class.' '.$id.' '.$width.' '.$height.'/>';
	}
		// Function to show the add to cart link with ajax and noramal
	function show_addtocart_v5_ajax($frm,$prod_arr,$class_arr,$istable=false,$class_tdarr=array(),$isbutton=false,$return = false,$prefix='',$suffix='',$override_hideqty=0)
	{ 
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
		global $ecom_selfhttp;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		$addto_cart_withajax    = $Settings_arr['enable_ajax_in_site'];//checking for the ajax function for adding to cart is enabled or not

		//to sheck whether quantity box should be shown or not
		$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		
        if($istable == true)
		{
		$class_qty = $class_tdarr['QTY'];
		$class_txt = $class_tdarr['TXT'];
		$class_btn = $class_tdarr['BTN'];	
		$quantity_div_class     = ($class_arr['QTY_TD']!='')?$class_arr['QTY_TD']:'quantity';  
        $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';		
		}  
		else
		{			
		        $quantity_div_class     = ($class_arr['QTY_DIV']!='')?$class_arr['QTY_DIV']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';     
		}	
		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty && $override_hideqty!=1)
		{
			if($istable == true)
			{
				$quantity_box  = '<td align="right" valign="middle" class="'.$class_qty.'">';
				if($prefix!='')
				{
					  $quantity_box  .=  $prefix;
				}
				$quantity_box  .= $Captions_arr['COMMON']['COMMON_QTY'].'</td>
								 <td align="left" valign="middle" class="'.$class_txt.'"> <input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></td>';
		    }
		    else
		    {
			  	$quantity_box  = '<div class="'.$quantity_div_class.'">'.$Captions_arr['COMMON']['COMMON_QTY'].'<input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></div>';

			}					 
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
				return;
		}
		$show_buy_now = false;
		$variable_check_forajax = false;//to check whether there is a variable exists for the product
		$var_exists 			= false; 
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{
			$var_exists = true;
			$variable_check_forajax = true;
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{				
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					    $quantity_box_display = true;					
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						    $link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{	
			if($prod_arr['product_variables_exists']=='Y')
			{
				$var_exists = true;
				$variable_check_forajax = true;	//this is for checking for variable exists for ajax enabled cart adding
			}
			else
			{
				$var_exists = false;	
				$variable_check_forajax = false;				
			}	
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{				
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$curtype	= 'Prod_Preorder';						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		
		if ($return==true)
		{
			return $mod;
		}	
		elseif ($link)
		{	
		
		if($addto_cart_withajax==1)
		{ 
			$link ="";
			if($ecom_siteid!=70 and $ecom_siteid!=114)
			{
		   ?>
		   	<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		   	<?php
		   	}?>
			<input type='hidden' name='fproduct_id' value="<?=$prod_arr['product_id']?>"/>
		   	<input type='hidden' name='product_id' value="<?=$prod_arr['product_id']?>"/>
			
			<?php 
			if($ecom_siteid!=70 and $ecom_siteid!=114)
			{
			?>
			<input type="hidden" id="product_id_ajax" name="product_id" value="<?=$prod_arr['product_id']?>" />
			<input type='hidden' name='ajaxform_name' id="ajaxform_name" value="<?=$frm?>"/>
			<?php
			}
			?>

		   <?php
		    if ($variable_check_forajax==true){					 
			$link  ="ajax_addto_cart_fromlist('show_prod_det_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
			else
			{		
			//$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
			$link  ="ajax_addto_cart_fromlist('add_prod_tocart_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
	    }
	    else
	    {
			if ($var_exists==true){					 
				$link = "javascript:submit_to_det_form('".$frm."')";
			}
			else
			{		
				$link = "javascript:submit_form('".$frm."','".$curtype."','".$prod_arr['product_id']."')";
			}
		   
		}
		
		$outer_cont = "";
		if($istable=='true')
		{
			$outer_cont        = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
						          <tr>';
			$btn_box  		   = '<td align="left" valign="middle" class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</td>';
			$outer_cont_bottom = '</tr></table>';
		}
		else
		{
		    $outer_cont        = '';
		    if($class_arr['BTN_CLS']!='')
		    {
				$class_btn = $class_arr['BTN_CLS'];
		    
			$btn_box  		   = '<div class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</div>';
		     }
			else
			{
				$btn_box ='';
			    $btn_box_bottom    = '';
			}
			$outer_cont_bottom = '';
		}
		$show_but ='';
		if($isbutton == true)
		{
			$show_but =  '<input value="'.$caption.'" name="'.$caption.'" type="button" onclick="'.$link.'" />';			
		}
		else
		{
			 $check_arr = is_grid_display_enabled_prod($prod_arr['product_id']);
				if($check_arr['enabled']==false)
				{
					
					$show_but =  '<a href="javascript:void(0);" onclick="'.$link.'" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 
				}
				else
				{
					$show_but =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" onclick="" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 

				}	
		}	
			$show_but .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		    echo $outer_cont;
			  if($quantity_box_display)
			   echo $quantity_box;
			   echo $btn_box;
			   echo $show_but;
			   echo $btn_box_bottom;
			   echo $outer_cont_bottom;
					
			
		    if($suffix!='')
			echo $suffix;
		}
	}
	//For ajax adding to wishlist
	function add_products_wishlist_ajax($varN='var_',$varM='varmsg_')
	{
		global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
		global $ecom_selfhttp;
		$url = $_REQUEST['pass_url'];
		$custom_id = get_session_var('ecom_login_customer'); 
		$Captions_arr['WISHLIST'] 	= getCaptions('WISHLIST'); // Getting the captions to be used in this page
		$fproduct_id = $_REQUEST['fproduct_id'];
		$session_id = Get_session_Id_from();			// Get the session id for the current section
		$var_arr 	= $varmsg_arr = array();	// Initialize the array to store variables and variable messages
		$stock		= 0;
		if($custom_id){
		// Get the variable and variable messages set for this product to an array 
		foreach ($_REQUEST as $k=>$v)
		{
			$var_nameLimit = strlen($varN);
			$var_messageLimit = strlen($varM);
			if (substr($k,0,$var_nameLimit) == $varN)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				$var_arr[$curid[1]] 	= trim($v);
				// Check whether curent var have values
				//$sql_check = "SELECT 
			}
			elseif (substr($k,0,$var_messageLimit) == $varM)
			{
				$curid 					= explode("_",$k);	// explode the name of variable to get the variable id
				$varmsg_arr[$curid[1]] 	= trim($v);
			}
		}
		// Calling the function to check whether the item is already there in cart. 
		// If it is already there then the cart id wil be returned. otherwise -1 will be returned as cart id
		$ret_wishid 	= Is_Wishlist_already_Exists($var_arr,$varmsg_arr,$custom_id);	
		$comb_arr = get_combination_id($_REQUEST['fproduct_id'],$var_arr);
		//print_r($_REQUEST);
		
				if ($ret_wishid==-1) // Case item does not exists in cart. So need to insert the details
				{
				// Make an entry to the cart and its related tables
				$insert_array							= array();
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['session_id']				= $session_id;	
				$insert_array['products_product_id']	= $_REQUEST['fproduct_id'];
				$insert_array['customer_id']	= $custom_id;
				$insert_array['product_qty']	= $_REQUEST['qty'] ;
				$insert_array['comb_id']		= $comb_arr['combid'];	
	
				$db->insert_from_array($insert_array,'wishlist');
				$insert_wishid 							= $db->insert_id();
				// Making entries to the cart_variables table if any variables exists
				if (count($var_arr))
				{	
					// Inserting the variable details cart_variables table
					foreach ($var_arr as $k=>$v)
					{
						$insert_array											= array();
						$insert_array['wishlist_wishlist_id']		= $insert_wishid;
						$insert_array['wishlist_var_id']				= $k;
						$insert_array['wishlist_var_value_id']	= $v;
						$db->insert_from_array($insert_array,'wishlist_variables');
					}
				}
				// Making entries to the cart_messages table if any messages exists
				if (count($varmsg_arr))
				{
					foreach ($varmsg_arr as $k=>$v)
					{ 
					
						// Get the type of current message from product_variable_messages table
						$sql_msg = "SELECT message_type 
									FROM 
										product_variable_messages 
									WHERE 
										message_id = $k 
									LIMIT 
										1";
						$ret_msg = $db->query($sql_msg);
						if ($db->num_rows($ret_msg))
						{
							$row_msg  = $db->fetch_array($ret_msg);
							$msg_type = $row_msg['message_type'];
						}				
						$insert_array					= array();
						$insert_array['wishlist_wishlist_id']						= $insert_wishid;
						$insert_array['message_id']								= $k;
						$insert_array['message_value']							= add_slash($v);
						$insert_array['message_type']							= $msg_type;
						$db->insert_from_array($insert_array,'wishlist_messages');
					}
				}					  
				}			
			}
		else{				
					$alert = $Captions_arr['WISHLIST']['WISHLIST_LOGIN_REQ'];
					return $alert;
					/*echo "<form method='post' action ='login_home.html' name='frm_addwish'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='$url'/><input type='hidden' name='fproduct_id' value='$fproduct_id'/></form><script type='text/javascript'>document.frm_addwish.submit();</script>";*/
				
			}
	}
	
	/*Refine Search Functions Starts Here*/
	function paging_refine_search($path,$query_string,$numcount,$pg,$pages,$perpage,$pg_var,$page_type,$class_arr,$total_req=1)
	{
		global $Captions_arr;
		global $ecom_selfhttp;
		if ($pages<=1)
			return ;
		if($total_req==1)
			echo "$numcount $page_type ".$Captions_arr['COMMON']['COMMON_FOUND'].". ".$Captions_arr['COMMON']['COMMON_PAGE']." <b>$pg</b> ".$Captions_arr['COMMON']['COMMON_OF']." <b>$pages</b>";
		if($numcount>1)
		{
			if($total_req==1)
				echo "<br />".pageNavApp_refine ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path);
			else
			
				echo pageNavApp_refine ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path);
		}
	}
	function pageNavApp_refine ($pagenum, $pages, $query_str,$class_arr,$perpage,$pg_var,$path)
	{
		global $Captions_arr;
		global $ecom_selfhttp;
		/*$a = "<a href='javascript: void(0);' onclick='javascript: pageAction();'>";
		$b = "'>";*/
		$c = "</a>\n";
		$nav = "<div class='pro_nav_links' align='right'>"; // init page nav string
		
		if($pg_var == 'catdet_pg')
		{
			$page_name	=	1;
		}
		else if($pg_var == 'search_pg')
		{
			$page_name	=	2;
		}
		
		
		if ($pagenum == 1) {
	
				//$nav .= "<img src='images/paging/left2_disabled.gif' border='0'>[First]&nbsp;&nbsp;";
				$nav .= $Captions_arr['COMMON']['COMMON_FIRST']."&nbsp;&nbsp;";
				//$nav .= "<img src='images/paging/left_disabled.gif' border='0'>[Prev]&nbsp;&nbsp;&nbsp;&nbsp;";
				$nav .= $Captions_arr['COMMON']['COMMON_PREV']."&nbsp;&nbsp;";
	
		} else {
	
				//$nav .= $a."1".$b."<img src='images/paging/left2.gif' border='0'>[First]".$c."&nbsp;&nbsp;";
				$nav .= "<a href='javascript: void(0);' onclick='javascript: pageAction(1,".$page_name.");'>".$Captions_arr['COMMON']['COMMON_FIRST'].$c."&nbsp;&nbsp;";
				//$nav .= $a.($pagenum - 1).$b."<img src='images/paging/left.gif' border='0'>[Prev]".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
				$nav .= "<a href='javascript: void(0);' onclick='javascript: pageAction(".($pagenum - 1).",".$page_name.");'>"."Prev".$c."&nbsp;&nbsp;&nbsp;&nbsp;";
	
		}
	
	
		if ($pagenum == $pages) {
	
				//$nav .= "<img src='images/paging/right_disabled.gif' border='0'>[Next]&nbsp;&nbsp;";
				$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_NEXT'];
				//$nav .= "<img src='images/paging/right2_disabled.gif' border='0'>[Last]<br>";
				$nav .= "&nbsp;&nbsp;".$Captions_arr['COMMON']['COMMON_LAST']."<br/>";
	
		} else {
				//$nav .= $a.($pagenum +1).$b."<img src='images/paging/right.gif' border='0'>[Next]".$c."&nbsp;&nbsp;";
				$nav .= "<a href='javascript: void(0);' onclick='javascript: pageAction(".($pagenum +1).",".$page_name.");'>".$Captions_arr['COMMON']['COMMON_NEXT'].$c."&nbsp;&nbsp;";
				//$nav .= $a.($pages).$b."<img src='images/paging/right2.gif' border='0'>[Last]".$c."<br>";
				$nav .= "<a href='javascript: void(0);' onclick='javascript: pageAction(".$pages.",".$page_name.");'>".$Captions_arr['COMMON']['COMMON_LAST'].$c."<br/>";
	
		}
		
		$nav .= '</div>';
		$nav .= '<div class="pro_nav_page" align="right">';
		$nav .= makeNavApp_refine ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path,$page_name);
		$nav .= '</div>';
		return $nav;
	}
	function makeNavApp_refine ($pages, $pagenum, $query_str='', $nav = "", $mag = 1,$class_arr,$perpage,$pg_var,$path,$page_name) 
	{
		global $theme_folder,$Captions_arr;
		global $ecom_selfhttp;
		$n = 10; // Number of pages or groupings
		$m = 10; // Order of magnitude of groupings
		//$a = "<a href='$query_str&amp;pg=";
		//$b = "'>";
		//$c = "</a>\n";
		if ($pages<=1)
			return;
		/*if($query_str) {
			if ($perpage)
					$add = '&amp;'.$perpage;
			$a = "<a class='edittextlink' href='$path?$query_str$add&amp;$pg_var=";
		} else {
			if ($perpage)
					$add = $perpage."&amp;$pg_var=";
				else
					$add = "$pg_var=";
			$a = "<a class='edittextlink' href='$path?$add";
		}*/
		$a = "<a class='edittextlink' href='javascript: void(0);' onclick='javascript: pageAction(";
		$b = ",".$page_name.");'>";
		$c = "</a>\n";
		if ($mag == 1) {
			// single page level
			$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
			for ($i = $minpage; $i < $pagenum; $i++) {
				if ( isset($nav[1]) ) {
					$nav[1] .= $a.($i).$b;
				} else {
					$nav[1] = $a.($i).$b;
				}
				$nav[1] .= "$i ";
				$nav[1] .= $c;
			}
			if ( isset($nav[1]) ) {
				$nav[1] .= "<span>&nbsp;$pagenum </span> ";
			} else {
				$nav[1] = "<span>&nbsp;$pagenum </span>";
			}
			$maxpage = ceil ($pagenum/$n) * $n;
			if ( $pages >= $maxpage ) {
				for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
					$nav[1] .= $a.($i).$b;
					$nav[1] .= "$i";
					$nav[1] .= $c;
				}
				$nav[1] .= "<br />";
			} else {
				for ($i = ($pagenum+1); $i <= $pages; $i++) {
					$nav[1] .= $a.($i).$b;
					$nav[1] .= "$i";
					$nav[1] .= $c;
				}
				$nav[1] .= "<br />";
			}
			if ( $minpage > 1 || $pages > $n ) {
				// go to next level
				$nav = makeNavApp_refine ($pages, $pagenum, $query_str, $nav, $n,$class_arr,$perpage,$pg_var,$path);
			}
			// Construct outgoing string from pieces in the array
			$out = $nav[1];
			for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
				if (isset($nav[$i][1]) && isset($nav[$i][2])) {
					$out = $nav[$i][1].$out.$nav[$i][2];
				} else if (isset($nav[$i][1])) {
					$out = $nav[$i][1].$out;
				} else if (isset($nav[$i][2])) {
					$out = $out.$nav[$i][2];
				} else {
					$out = $out;
				}
			}
			return $out;
		}
		$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
		$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
		if ( $prevpage > $minpage ) {
			for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
				if (isset($nav[$mag][1])) {
					$nav[$mag][1] .= $a.($i+1).$b;
				} else {
					$nav[$mag][1] = $a.($i+1).$b;
				}
				$nav[$mag][1] .= $a.($i+1).$b;
				$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][1] .= $c;
			}
			$nav[$mag][1] .= "<br />";
		} // Otherwise, it's this page's group, which is handled the mag level below, so skip
		$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
		if ( $pages >= $maxpage ) {
			// If there are more pages than we are accounting for here
			$nextpage = ceil ($pagenum/$mag) * $mag;
			if ($maxpage > $nextpage) {
				for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
					if (isset($nav[$mag][2])) {
						$nav[$mag][2] .= $a.($i+1).$b;
					} else {
						$nav[$mag][2] = $a.($i+1).$b;
					}
					$nav[$mag][2] .= $a.($i+1).$b;
					$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
					$nav[$mag][2] .= $c;
				}
				$nav[$mag][2] .= "<br />";
			}
		} else {
			// This is the end
			if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
				// If there are more pages than just this page's group
				for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
					if (isset($nav[$mag][2])) {
						$nav[$mag][2] .= $a.($i+1).$b;
					} else {
						$nav[$mag][2] = $a.($i+1).$b;
					}
					$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
					$nav[$mag][2] .= $c;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
				$nav[$mag][2] .= $c;
				$nav[$mag][2] .= "<br />";
			}
		}
		if ( $minpage > 1 || $pages >= $maxpage ) {
			$nav = makeNavApp_refine ($pages, $pagenum, $query_str, $nav, $mag * $m,$class_arr,$perpage,$pg_var,$path);
		}
		return $nav;
	}
	/*Refine Search Functions Ends Here*/
	
	
	
	/* Combination id finder if variable price or variable stock or variable images exists*/
	function find_combination_id_special($prodid,$var_arr)
	{ 
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
							product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,
							product_variablecombocommon_image_allowed  
						FROM
							products
						WHERE
							product_id=$prodid
							AND sites_site_id = $ecom_siteid
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			

			
				if (count($var_arr))
				{
					$varids = array();
					foreach ($var_arr as $k=>$v)
					{
						// Check whether the variable is a check box or a drop down box
						$sql_check = "SELECT var_id
										FROM
											product_variables
										WHERE
											var_id=$k
											AND var_value_exists = 1
										LIMIT
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check 	= $db->fetch_array($ret_check);
							$varids[] 	= $k; // populate only the id's of variables which have values to the array
						}
					}
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{

						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{  
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}					

							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{ 
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
			
			
		}
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}
	function is_product_variable_weight_active()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$enable = false;
		$sql_set = "SELECT enable_variable_weight FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
		$ret_set = $db->query($sql_set);
		if($db->num_rows($ret_set))
		{
			$row_set = $db->fetch_array($ret_set);
			if($row_set['enable_variable_weight']==1)
			{
				$enable = true;
			}
		}	
		return $enable;
	}
	
	function is_delivery_group_free_delivery_active()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$enable = false;
		$sql_set = "SELECT enable_delivery_group_free_delivery FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
		$ret_set = $db->query($sql_set);
		if($db->num_rows($ret_set))
		{
			$row_set = $db->fetch_array($ret_set);
			if($row_set['enable_delivery_group_free_delivery']==1)
			{
				$enable = true;
			}
		}	
		return $enable;
	}
	function is_custogroup_catdiscount_enabled()
	{
		global $db,$ecom_siteid;
		global $ecom_selfhttp;
		$enable = false;
		$sql_set = "SELECT enable_custgroup_category_disc FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
		$ret_set = $db->query($sql_set);
		if($db->num_rows($ret_set))
		{
			$row_set = $db->fetch_array($ret_set);
			if($row_set['enable_custgroup_category_disc']==1)
			{
				$enable = true;
			}
		}	
		return $enable;
	}
	function customer_group_special_arrays()
	{
		global $ecom_siteid,$db;
		global $ecom_selfhttp;
		$grp_arr = array();
		/* Sony Jul 01, 2013 */
		// Check whether customer is logged in and special display is required for each of the customers
		$cust_id 					= get_session_var("ecom_login_customer");
		$discthm_group_shelf_array = $discthm_group_static_array = $discthm_group_prod_array= $disgthm_group_cat_array = array();
		$sql_site = "SELECT site_custgroup_special_display_enable FROM sites WHERE site_id = $ecom_siteid LIMIT 1";
			$ret_site = $db->query($sql_site);
			if ($db->num_rows($ret_site))
			{
				$row_site = $db->fetch_array($ret_site);
			}
			if($row_site['site_custgroup_special_display_enable']==1)
			{
			if($cust_id)
			{

				// Check whether this customer is related to any customer group
				$sql_custgroup = "SELECT a.cust_disc_grp_id 
									FROM 
										customer_discount_group a,customer_discount_customers_map b 
									WHERE 
										a.cust_disc_grp_active = 1 
										AND a.sites_site_id = $ecom_siteid 
										AND b.customers_customer_id = ".$cust_id ."
										AND a.cust_disc_grp_id = b.customer_discount_group_cust_disc_grp_id";
				$ret_custgroup 		= $db->query($sql_custgroup);
										
				if ($db->num_rows($ret_custgroup))
				{
					while($row_custgroup = $db->fetch_array($ret_custgroup))
					{
						$grp_arr[] = $row_custgroup['cust_disc_grp_id'];
						// Shelf Section
						$sql_shelfgp = "SELECT shelves_shelf_id 
											FROM 
												customer_discount_group_shelfs_map 
											WHERE 
												sites_site_id = $ecom_siteid 
												AND customer_discount_group_cust_disc_grp_id = ".$row_custgroup['cust_disc_grp_id'];
						$ret_shelfgp = 	$db->query($sql_shelfgp);
						if($db->num_rows($ret_shelfgp))
						{
							while ($row_shelfgp = $db->fetch_array($ret_shelfgp))
							{
								$discthm_group_shelf_array[] = $row_shelfgp['shelves_shelf_id'];
							}
						}
						
						// Static Section
						$sql_staticgp = "SELECT static_page_id  
											FROM 
												customer_discount_group_staticpage_map  
											WHERE 
												sites_site_id = $ecom_siteid 
												AND customer_discount_group_cust_disc_grp_id = ".$row_custgroup['cust_disc_grp_id'];
						$ret_staticgp = 	$db->query($sql_staticgp);
						if($db->num_rows($ret_staticgp))
						{
							while ($row_staticgp = $db->fetch_array($ret_staticgp))
							{
								$discthm_group_static_array[] = $row_staticgp['static_page_id'];
							}
						}	
						
						// Products Section
						$sql_prodgp = "SELECT products_product_id   
											FROM 
												customer_discount_group_products_map   
											WHERE 
												sites_site_id = $ecom_siteid 
												AND customer_discount_group_cust_disc_grp_id = ".$row_custgroup['cust_disc_grp_id'];
						$ret_prodgp = 	$db->query($sql_prodgp);
						if($db->num_rows($ret_prodgp))
						{
							while ($row_prodgp = $db->fetch_array($ret_prodgp))
							{
								$discthm_group_prod_array[] = $row_prodgp['products_product_id'];
							}
						}	
						
						// Categories Section
						$sql_catgp = "SELECT product_categories_category_id    
											FROM 
												customer_discount_group_categories_map    
											WHERE 
												sites_site_id = $ecom_siteid 
												AND customer_discount_group_cust_disc_grp_id = ".$row_custgroup['cust_disc_grp_id'];
						$ret_catgp = 	$db->query($sql_catgp);
						if($db->num_rows($ret_catgp))
						{
							while ($row_catgp = $db->fetch_array($ret_catgp))
							{
								$disgthm_group_cat_array[] = $row_catgp['product_categories_category_id'];
							}
						}	
						
						$ret_arr['shelf'] 		= $discthm_group_shelf_array;
						$ret_arr['static'] 		= $discthm_group_static_array;
						$ret_arr['product'] 	= $discthm_group_prod_array;
						$ret_arr['category'] 	= $disgthm_group_cat_array;
						$ret_arr['group']		= $grp_arr;
						
						return $ret_arr;
												
												
					}
				}
			} //Sony 
			else
			{
			// Categories Section
						$sql_catgp = "SELECT category_id    
											FROM 
												product_categories    
											WHERE 
												sites_site_id = $ecom_siteid
												AND category_hide = 0  
												AND display_to_guest =1";
						$ret_catgp = 	$db->query($sql_catgp);
						if($db->num_rows($ret_catgp))
						{
							while ($row_catgp = $db->fetch_array($ret_catgp))
							{
								$disgthm_group_cat_array[] = $row_catgp['category_id'];
							}
						}	
						$ret_arr['category'] 	= $disgthm_group_cat_array;
						
						return $ret_arr;
			}
			/* Latheesh Jul 01, 2013 */				
			}		
	}	
	function get_function_for_variables($product_id,$var_id,$val_exists)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		$addprice_exists = false;
		$ret_val = array();
		$ret_val['fn'] 	= '';
		$ret_val['var'] = '';
		if($val_exists==1)
		{
			// Check whether any additional price exists for the values of this variable
			$sql_valprice = "SELECT var_addprice FROM product_variable_data WHERE product_variables_var_id = $var_id AND var_addprice >0 LIMIT 1";
			$ret_valprice = $db->query($sql_valprice);
			if($db->num_rows($ret_valprice))
			{
				$addprice_exists = true;
			}
		}
		else
		{
			// case if value does not exists. Then check whether additional price exists for the variable itself
			$sql_varprice = "SELECT var_price FROM product_variables WHERE var_id = $var_id LIMIT 1";
			$ret_varprice = $db->query($sql_varprice);
			if($db->num_rows($ret_varprice))
			{
				$addprice_exists = true;
			}
		}
		if($addprice_exists)
		{
			$ret_val['fn'] 	= " onchange ='handle_show_prod_det_addprice(\"price\",\"".SITE_URL."\",\"".$product_id."\",\"".url_site_image('loading.gif',1)."\")' ";
			$ret_val['var'] = 'price';
		}
		else
		{
			$ret_val['fn'] 	= '';
			$ret_val['var'] = '';
		}		
		return $ret_val;
	}
	/* Function to decide whether grid display is supported for a given category */
	function is_grid_display_enabled($catid)
	{
		global $db,$ecom_siteid,$ecom_gridenable;
		global $ecom_selfhttp;
		$grid_display_valid = false; // variable which decides whether grid display is enabled or not
		if($ecom_gridenable==1)
		{ 
		// Check whether grid display is active for this category and also variable group is assigned to this category
		$sql_cat = "SELECT product_variables_group_id,enable_grid_display,category_name 
						FROM 
							product_categories 
						WHERE 
							category_id = $catid 
							AND sites_site_id = $ecom_siteid 
							AND category_hide = 0
						LIMIT 
							1";
		$ret_cat = $db->query($sql_cat);
		if($db->num_rows($ret_cat))
		{
			 $row_cat = $db->fetch_array($ret_cat);
			 $def_cat_name = $row_cat['category_name'];

			if($row_cat['enable_grid_display']==1)
			{
				if($row_cat['product_variables_group_id']!=0)
				{
					// Check whether the group is hidden or not
					$sql_vargrp = "SELECT var_group_hide 
									FROM 
										product_variables_group 
									WHERE 
										var_group_id = ".$row_cat['product_variables_group_id']." 
										AND sites_site_id = $ecom_siteid 
										AND var_group_hide = 0 
									LIMIT 
										1";
					$ret_vargrp = $db->query($sql_vargrp);
					if($db->num_rows($ret_vargrp))
					{
						$groupid = $row_cat['product_variables_group_id'];
						// Check whether preset variables assigned to this group is not hidden
						$sql_preset = "SELECT prd_var_group_var_mapid 
										FROM 
											product_variables_group_variables_map a,product_preset_variables b
										WHERE 
											a.sites_site_id = $ecom_siteid 
											AND a.product_variables_id = b.var_id 
											AND a.product_variables_group_id = ".$groupid ."
											AND b.var_hide = 0 
										LIMIT 1";
						$ret_preset = $db->query($sql_preset);
						if($db->num_rows($ret_preset))
						{
						
							// check whether a horizontal variable is set in this group and is not hidden
							$sql_hori = "SELECT product_variables_id,var_name 
										FROM 
											product_variables_group_variables_map a,product_preset_variables b
										WHERE 
											a.sites_site_id = $ecom_siteid 
											AND a.product_variables_id = b.var_id 
											AND a.product_variables_group_id = ".$groupid ."
											AND b.var_hide = 0 
											AND a.prd_var_group_var_horizontal = 1 
										LIMIT 1";
							$ret_hori = $db->query($sql_hori);
							if($db->num_rows($ret_hori))
							{
								$grid_display_valid = true;
								$row_hori 			= $db->fetch_array($ret_hori);
								$hori_varid 		= $row_hori['product_variables_id'];
								$hori_varname 		= $row_hori['var_name'];
								// get the name of horizontal variable
							}	
						}	
					}
				}
			}
		}
		$ret_arr['enabled'] 		= $grid_display_valid;
		$ret_arr['groupid'] 		= $groupid;
		$ret_arr['hori_varid'] 		= $hori_varid;
		$ret_arr['hori_varname'] 	= $hori_varname;
		$ret_arr['def_catid'] 		= $catid;
		$ret_arr['category_name'] 	= $def_cat_name;
		return $ret_arr;
		}
	}
	/* Function to decide whether grid display is supported for a given category */
	function is_grid_display_enabled_prod($prod_id)
	{
		global $db,$ecom_siteid,$ecom_gridenable;
		global $ecom_selfhttp;
		    $grid_display_valid = false; // variable which decides whether grid display is enabled or not
			if($ecom_gridenable==1)
			{ 
				if($prod_id>0)
				{
				$sql_prod = "SELECT product_name,product_default_category_id FROM products WHERE product_id=".$prod_id." AND sites_site_id=".$ecom_siteid." LIMIT 1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{ 
				$row_prod    = $db->fetch_array($ret_prod);
				$def_cat_id  = $row_prod['product_default_category_id'];
					if($def_cat_id>0)
					{
						$check_arr   = is_grid_display_enabled($def_cat_id);
					    return $check_arr;
					}
				}				
			}
		}
	}
	
	
	function Check_cart_variable_integrity_2013()
	{
		global $db,$ecom_siteid,$ecom_hostname;
		global $ecom_selfhttp;
		
		// get the session id
		$session_id = Get_session_Id_from();
		// Get the cart items 
		$sql_cart = "SELECT cart_id,sites_site_id,products_product_id 
						FROM 
							cart 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND session_id ='".$session_id."'";
		$ret_cart = $db->query($sql_cart);
		while ($row_cart = $db->fetch_array($ret_cart))
		{
			$cartid = $row_cart['cart_id'];
			// Get the list of details in cart variables table for current cart id
			$sql_cartvar = "SELECT var_id, var_value_id 
								FROM 
									cart_variables 
								WHERE 
									cart_id = $cartid";
			$ret_cartvar = $db->query($sql_cartvar);
			$varcheck_arr = array();
			if($db->num_rows($ret_cartvar))
			{
				while ($row_cartvar = $db->fetch_array($ret_cartvar))
				{
					$varcheck_arr[$row_cartvar['var_id']][] = $row_cartvar['var_value_id'];
				}
				
				$checkerror_exists = false;
				//echo '<pre>';
				//print_r($varcheck_arr);
				//echo '</pre>';
				// Check whether same variable is repeated more than once
				foreach ($varcheck_arr as $kkk=>$vvk)
				{
					if(count($vvk)>1)
					{
						$checkerror_exists = true;
					}
				}
				if($checkerror_exists)
				{
					$mailcontent = "Variable error on cart at $ecom_hostname on ".date('r');
					
					ob_start();
					print '<br><br>============================<br> Error Condition <br>============================<br>';
					print $mailcontent;
					print '<br><br>============================<br> Variable Array<br>============================<br>';
					echo '<pre>';
					var_dump($varcheck_arr);
					echo '</pre>';
					$mailcontent = ob_get_contents();
					ob_end_clean();
					
					$email_headers = "From: $ecom_hostname  <info@bshop.com>\n";
					$email_headers .= "MIME-Version: 1.0\n";
					$email_headers .= "Content-type: text/html; charset=iso-8859-1\n";
					mail('sony.joy@thewebclinic.co.uk','Variable Error on Cart',$mailcontent,$email_headers);
					
					
					$del_sql = "DELETE FROM cart_variables WHERE cart_id = $cartid";
					$db->query($del_sql);
					$del_sql = "DELETE FROM cart WHERE cart_id = $cartid LIMIT 1";
					$db->query($del_sql);
				}
			}
		}
	}
	
	/* Functions to handle the case of microdata*/
	
	function getmicrodata_productscope()
	{
		global $ecom_selfhttp;
		return 'itemscope itemtype="'.$ecom_selfhttp.'schema.org/Product" ';
	}
	
	function getmicrodata_productname()
	{
		global $ecom_selfhttp;
		return 'itemprop="name" ';
	}
	
	function getmicrodata_producturl()
	{
		global $ecom_selfhttp;
		return 'itemprop="url" ';
	}
	
	function getmicrodata_productimage()
	{
		global $ecom_selfhttp;
		return 'itemprop="image"  ';
	}
	
	function getmicrodata_productdesc()
	{
		global $ecom_selfhttp;
		return 'itemprop="description"  ';
	}
	
	function getmicrodata_productpricescope()
	{
		global $ecom_selfhttp;
		return 'itemprop="offers" itemscope itemtype="'.$ecom_selfhttp.'schema.org/Offer"  ';
	}
	
	function getmicrodata_productprice()
	{
		global $ecom_selfhttp;
		return 'itemprop="price" ';
	}
	// Function to temporarly save the values in checkout page
function check_intermediate_required()
{
	global $db,$ecom_siteid,$show_cart_password,$Settings_arr;
	global $ecom_selfhttp;
	$sess_id	= Get_session_Id_from();

	$link_exists = false;
	$prod_id[][] = array();
		 $sql_cart    = "SELECT a.link_product_id,a.link_parent_id 
																FROM 
																	product_linkedproducts a 
																LEFT JOIN cart b ON b.products_product_id = a.link_parent_id
																LEFT JOIN products c ON c.product_id = a.link_product_id  
																WHERE b.session_id='$sess_id' 
																AND b.sites_site_id=$ecom_siteid 
																AND c.product_hide = 'N' 
																AND (a.show_in='C' OR a.show_in='CP') "; 
	$ret_cart = $db->query($sql_cart);
	$inter_req    = $Settings_arr['enable_intermediate_cart'];
	if($inter_req==1)
	{
		if($db->num_rows($ret_cart)>0)
		{
			 while($row_cart=$db->fetch_array($ret_cart))
				{
				  $prod_id[$row_cart['link_parent_id']][] = $row_cart['link_product_id'];
				  
				}
				if(count($prod_id)>0)
				{
				  $link_exists = true;
				}
		}
	}
	return  $link_exists;	
}
// function which checks whether the v3 protocol is enabled
function sagepay_v3protocol_enabled()
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	$retval = false;
	if ($ecom_siteid)
	{
			$sql_sitecheck = "SELECT sagepay_form_v3protocol_enabled 
								FROM 
									sites
								WHERE 
									site_id = $ecom_siteid 
								LIMIT 
									1";
			$ret_sitecheck = $db->query($sql_sitecheck);
			if($db->num_rows($ret_sitecheck))
			{
				$row_sitecheck = $db->fetch_array($ret_sitecheck);
				if($row_sitecheck['sagepay_form_v3protocol_enabled']==1)
				{
					$retval = true;
				}	
			}
	}
	return $retval;
}
function insert_cartids_into_orders($order_id,$sess_id)
{
  	global $db,$ecom_siteid;
  	global $ecom_selfhttp;
  	$cart_ids = '';
  	$sql_cartids = "SELECT cart_id FROM cart WHERE session_id='$sess_id' AND sites_site_id=$ecom_siteid";
  	$ret_cartids = $db->query($sql_cartids);
  	while($row_cartids=$db->fetch_array($ret_cartids))
  	{
	   if($cart_ids !='')
	   {
	     $cart_ids .= '~';
	   }
	   $cart_ids .=$row_cartids['cart_id'];
	}
	if($cart_ids!='')
	{
	   $update_array = array();
	   $update_array['cartid_incomplete'] = $cart_ids;
	   $db->update_from_array($update_array,'orders',array('order_id'=>$order_id,'sites_site_id'=>$ecom_siteid));
	}
}

function get_expected_deliverydate()
{
	global $ecom_siteid,$db;
	global $ecom_selfhttp;
	
	// Check whether expected delivery date feature is active in current website
	$sql_settings = "SELECT enable_exp_deliverydate,exp_deliverydate_normal_days,exp_deliverydate_normal_time 
						FROM 
							general_settings_sites_common_onoff 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_settings = $db->query($sql_settings);
	if($db->num_rows($ret_settings))
	{
		$row_settings = $db->fetch_array($ret_settings);
	}
	
	if($row_settings['enable_exp_deliverydate']==1) // Check whether the feature is active for the current website.
	{
		$delivery_days 	= $row_settings['exp_deliverydate_normal_days'];
		$delivery_time	= $row_settings['exp_deliverydate_normal_time'];
		$del_time_split = explode(':',$row_settings['exp_deliverydate_normal_time']);
		
		$difference_in_time = (mktime($del_time_split[0], $del_time_split[1], 0, date("m")  , date("d"), date("Y")))-(mktime(date('H'), date('i'), 0, date("m")  , date("d"), date("Y")));
		
		//echo "curtime --- ".date('r')."  <br>time in db ".' --- '.$row_settings['exp_deliverydate_normal_time'].'  <br> Difference ---  '.$difference_in_time;
		
		if($difference_in_time>=0)
		{	
			
			$nextdeldaynum = date('w',mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days, date("Y")));	
			//echo "Nxt".$nextdeldaynum;
			if($nextdeldaynum==6) // saturday
			{
				$day_buffer = 2;
			}
			elseif($nextdeldaynum==0) // sunday
			{
				$day_buffer = 2;
			}
			else
			{
				$day_buffer = 0;
			}
			$nextdelday = mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days+$day_buffer, date("Y"));	
			
			return format_expected_deliverydate($difference_in_time,$nextdelday);
		}
		else
		{
			$curtime  	= mktime(date('H'), date('i'), 0, date("m")  , date("d"), date("Y"));
			$tomorrow_deltime = mktime($del_time_split[0], $del_time_split[1], 0, date("m")  , date("d")+1, date("Y"));
			$difference_in_time = ($tomorrow_deltime)-($curtime);
			
			$nextdeldaynum = date('w',mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+1+$delivery_days, date("Y")));	
			$cntr=0;
			if($nextdeldaynum>5 or $nextdeldaynum==0)
			{
				if($nextdeldaynum==6)// Saturday
				{
					$day_buffer = 3;
					$nextdeldaynum = date('w',mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+1+$day_buffer+$delivery_days, date("Y")));	
					$nextdelday = mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days+1+$day_buffer, date("Y"));	
				}
				if($nextdeldaynum==0)// Sunday
				{
					$day_buffer = 2;
					$nextdeldaynum = date('w',mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+1+$day_buffer+$delivery_days, date("Y")));	
					$nextdelday = mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days+1+$day_buffer, date("Y"));	
				}	
			}	
			else
			{
				$nextdelday = mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days+1, date("Y"));	
			}
			/*if($nextdeldaynum==6)// saturday
			{
				$day_buffer = 3;
			}
			elseif($nextdeldaynum==0)// sunday
			{
				$day_buffer = 2;
			}
			else
			{
				$day_buffer = 1;
			}*/
			
			
			
			//$nextdelday = mktime($del_time_split[0], $del_time_split[1], 0, date("m"), date("d")+$delivery_days+1, date("Y"));
			
			
			
			
			return format_expected_deliverydate($difference_in_time,$nextdelday);
		}	
	}
}
function format_expected_deliverydate($difference_in_time,$nextdelday)
{
	global $db,$ecom_siteid;
	global $ecom_selfhttp;
	if($difference_in_time)
	{
		$hoursandmins 		= date('H:i',$difference_in_time);
	}
	else
	{
		$hoursandmins		= '00:01';
	}	
	$hoursandmins_arr 	= explode(':',$hoursandmins);
	
	$timediff_hour	 	= ltrim($hoursandmins_arr[0],'0');
	$timediff_min 		= ltrim($hoursandmins_arr[1],'0');
	
	$timediff_hour 		= ($timediff_hour)?$timediff_hour:0;
	$timediff_min 		= ($timediff_min)?$timediff_min:0;
	
	$ret_arr = array('hr'=>$timediff_hour,'min'=>$timediff_min,'del_date'=>$nextdelday);
	
	return $ret_arr;
}
// Function for unipad tenant
function get_Field_tenant($ext_name,$key='',$saved_checkoutvals,$customer_arr,$cur_form='',$class_array=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr,$ecom_load_mobile_theme;
	global $ecom_selfhttp;
	if($ecom_load_mobile_theme)
		$box_size = 20;
	else
		$box_size = 30;
	// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
	
	$txt_cls 		= ($class_array['txtbox_cls'])?'class="'.$class_array['txtbox_cls'].'"':'';
	$txtarea_cls 	= ($class_array['txtarea_cls'])?'class="'.$class_array['txtarea_cls'].'"':'';
	$select_cls 	= ($class_array['select_cls'])?'class="'.$class_array['select_cls'].'"':'';
	
	$txt_onblur 		= ($class_array['onblur'])?$class_array['onblur']:'';
///echo "'".$ext_name."checkout_title'";
	// Deciding which is the field to be displayed
	switch($key)
	{ 
		case $ext_name."checkout_title":
		case $ext_name."checkout_vouchertitle":
		case $ext_name."checkoutdelivery_title":
		case $ext_name."customer_title":
			$ret = '<select name="'.$key.'" id="'.$key.'"'.$select_cls.'>';
			$sel = ($saved_checkoutvals[$key]=='Mr.')?'selected':'';
			$ret .='<option value="Mr." '.$sel.'>Mr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Mrs.')?'selected':'';
			$ret .='<option value="Mrs." '.$sel.'>Mrs.</option>';
			$sel = ($saved_checkoutvals[$key]=='Miss.')?'selected':'';
			$ret .='<option value="Miss." '.$sel.'>Miss.</option>';
			$sel = ($saved_checkoutvals[$key]=='Ms.')?'selected':'';
			$ret .='<option value="Ms." '.$sel.'>Ms.</option>';
			$sel = ($saved_checkoutvals[$key]=='M/s.')?'selected':'';
			$ret .='<option value="M/s." '.$sel.'>M/s.</option>';
			$sel = ($saved_checkoutvals[$key]=='Dr.')?'selected':'';
			$ret .='<option value="Dr." '.$sel.'>Dr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Sir.')?'selected':'';
			$ret .='<option value="Sir." '.$sel.'>Sir.</option>';
			$sel = ($saved_checkoutvals[$key]=='Rev.')?'selected':'';
			$ret .='<option value="Rev." '.$sel.'>Rev.</option>';
			$ret .='</select>';
		break;
		case $ext_name.'checkout_country':
		$field_name = $ext_name.'checkout_country';
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkout_country" id="'.$ext_name.'checkout_country">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}	
							
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case $ext_name.'checkoutdelivery_country':
				$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order  	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkoutdelivery_country" id="'.$ext_name.'checkoutdelivery_country">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!trim($saved_checkoutvals[$field_name]))
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}
							
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}	
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case $ext_name.'checkout_vouchercountry':
						$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkout_vouchercountry" id="'.$ext_name.'checkout_vouchercountry">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case $ext_name.'cbo_country':
			$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'cbo_country" id="'.$ext_name.'cbo_country">';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['cbo_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.'/>';
			}
		break;
		case $ext_name.'checkout_comp_name':
		case $ext_name.'checkout_fname':
		case $ext_name.'checkout_mname':
		case $ext_name.'checkout_surname':
		case $ext_name.'checkout_building':
		case $ext_name.'checkout_address2':
		case $ext_name.'checkout_street':
		case $ext_name.'checkout_city':
		case $ext_name.'checkout_state':
		case $ext_name.'checkout_zipcode':
		case $ext_name.'checkout_phone':
		case $ext_name.'checkout_mobile':
		case $ext_name.'checkout_fax':
		case $ext_name.'checkout_email':
		

		case $ext_name.'checkout_vouchercomp_name':
		case $ext_name.'checkout_voucherfname':
		case $ext_name.'checkout_vouchermname':
		case $ext_name.'checkout_vouchersurname':
		case $ext_name.'checkout_voucherbuilding':
		case $ext_name.'checkout_voucherstreet':
		case $ext_name.'checkout_vouchercity':
		case $ext_name.'checkout_voucherstate':
		case $ext_name.'checkout_voucherzipcode':
		case $ext_name.'checkout_voucherphone':
		case $ext_name.'checkout_vouchermobile':
		case $ext_name.'checkout_voucherfax':
		case $ext_name.'checkout_voucheremail':
		

		case $ext_name.'checkoutdelivery_comp_name':
		case $ext_name.'checkoutdelivery_fname':
		case $ext_name.'checkoutdelivery_mname':
		case $ext_name.'checkoutdelivery_surname':
		case $ext_name.'checkoutdelivery_building':
		case $ext_name.'checkoutdelivery_address2':
		case $ext_name.'checkoutdelivery_street':
		case $ext_name.'checkoutdelivery_city':
		case $ext_name.'checkoutdelivery_state':
		case $ext_name.'checkoutdelivery_zipcode':
		case $ext_name.'checkoutdelivery_phone':
		case $ext_name.'checkoutdelivery_mobile':
		case $ext_name.'checkoutdelivery_fax':
		case $ext_name.'checkoutdelivery_email':
		
		case $ext_name.'customer_fname':
		case $ext_name.'customer_mname':
		case $ext_name.'customer_surname':
		case $ext_name.'customer_position':
		case $ext_name.'customer_buildingname':
		case $ext_name.'customer_streetname':
		case $ext_name.'customer_towncity':
		case $ext_name.'cbo_state':
		case $ext_name.'customer_postcode':
		/*case 'cbo_country':*/
		case $ext_name.'customer_phone':
		case $ext_name.'customer_mobile':
		case $ext_name.'customer_fax':
		
		case $ext_name.'customer_compname':
		case $ext_name.'customer_compregno':
		case $ext_name.'customer_compvatregno':

		case $ext_name.'checkoutpay_nameoncard':

		case $ext_name.'checkoutchq_number':
		case $ext_name.'checkoutchq_bankname':

		case $ext_name.'checkoutpay_cardnumber':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="'.$box_size.'" '.$txt_cls.' '.$txt_onblur.'/>';
		break;
		case $ext_name.'checkoutpay_issuenumber':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case $ext_name.'checkoutpay_securitycode':
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" size="4" '.$txt_cls.'/>';
		break;
		case $ext_name.'customer_comptype':
			$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			$sql = "SELECT comptype_id,comptype_name
						FROM 
							general_settings_sites_customer_company_types 
						WHERE 
							sites_site_id=$ecom_siteid 
						AND 
							comptype_hide=0 
						ORDER BY 
							comptype_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				/*while ($row = $db->fetch_array($rets))
				{
					$key = $row['comptype_id'];
					$ret .= '<option value="'.$key.'">'.stripslashes($row['comptype_name']).'</option>';
				}*/
				while ($row = $db->fetch_array($rets))
				{
					$key1 = $row['comptype_id'];
					$selc='';
					if($saved_checkoutvals[$key]==$key1)
					{
						$selc = 'selected';
					}
					$ret .= '<option value="'.$key1.'" '.$selc.'>'.stripslashes($row['comptype_name']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_cardtype':
			/*if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card_voucher(this)">';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card(this)">';*/
			
			if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';	

			$ret .= "<option value=''>-- Select --</option>";
			
			$sql = "SELECT a.cardtype_key,a.cardtype_caption,a.cardtype_issuenumber_req,a.cardtype_securitycode_count,cardtype_numberofdigits,a.cardtype_paypalprokey 
					FROM
						payment_methods_supported_cards a,payment_methods_sites_supported_cards b
					WHERE
						b.sites_site_id = $ecom_siteid
						AND a.cardtype_id=b.payment_methods_supported_cards_cardtype_id
					ORDER BY
						b.supportcard_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				while ($row = $db->fetch_array($rets))
				{
					if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO')
						$key = $row['cardtype_paypalprokey'];
					else
						$key = $row['cardtype_key'];
					$ret .= '<option value="'.$key.'_'.$row['cardtype_issuenumber_req'].'_'.$row['cardtype_securitycode_count'].'_'.$row['cardtype_numberofdigits'].'">'.stripslashes($row['cardtype_caption']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_expirydate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			for($i=date('Y');$i<date('Y')+10;$i++)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_issuedate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=date('Y');$i>date('Y')-20;$i--)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case $ext_name.'checkoutchq_date':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" id="'.$key.'" value="" size="10" maxlength="10" '.$txt_cls.'/> (e.g. 01-01-2008)';
		break;
		case $ext_name.'checkout_notes':
		case 'checkout_vouchernotes':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'>'.$saved_checkoutvals[$key].'</textarea>';
		break;
		case $ext_name.'checkoutchq_bankbranch':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'></textarea>';
		break;
	};
	return $ret;
}

function save_CheckoutDetails_passport()
{
	
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$image_path,$ecom_themename;
	include_once('includes/cartCalc.php');
	$sess_id	  							= Get_session_Id_from();
	$cartData   							= cartCalc();
	$qty   = $cartData['totals']['qty'];
	 $errors_passport = array();   							
	if($qty)
	{
		for($i=0;$i<$qty;$i++)
		{ 
			$cnt = $i+1;
			$ext_name = "tenant".$cnt."_";
			$tenantname = "tenant".$cnt;
			$input_filename   = $tenantname.'_passport';
			
			if($_FILES[$input_filename]['name']!='')
			{
				    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
					$detectedType = exif_imagetype($_FILES[$input_filename]['tmp_name']);
					if(in_array($detectedType, $allowedTypes))
					{
				    $_FILES[$input_filename]['tmp_name'];
					$passfilename  = $_FILES[$input_filename]['name'];

					$sql_check = "SELECT *
							FROM
								cart_passport_details
							WHERE
								session_id = '".$sess_id."'
								AND sites_site_id = $ecom_siteid 
								AND tenant_name='$tenantname'";
					$ret_check = $db->query($sql_check); 
						if($db->num_rows($ret_check)==0)
						{
							//	echo "<pre>";
							//print_r($_FILES);
							//echo $_FILES['tenant1_passport']['name'].'test';
							$insert_array									= array();

							$insert_array['session_id']			    = $sess_id;
							$insert_array['sites_site_id']			= $ecom_siteid;
							$insert_array['passport_orgfilename']		= $passfilename;
							$insert_array['tenant_name']			= $tenantname;
							$insert_array['deposit_amount']			= $_REQUEST['deposit'];

							$db->insert_from_array($insert_array,'cart_passport_details');
							$insert_cartid 							= $db->insert_id();
							$errors_passport = passport_fileupload($input_filename,$insert_cartid);
						}
						else
						 {
							$row_check = $db->fetch_array($ret_check);
							$insert_cartid 	= $row_check['id'];

							$update_array									= array();

							$update_array['passport_orgfilename']		= $passfilename;
							$update_array['deposit_amount']			= $_REQUEST['deposit'];
							$db->update_from_array($update_array,'cart_passport_details',array('session_id'=>$sess_id,'sites_site_id'=>$ecom_siteid,'id'=>$insert_cartid));

							$errors_passport = passport_fileupload($input_filename,$insert_cartid,$tenantname);
						 }
					 }
					 else
					 {
					 $errors_passport['number'] = $tenantname;
					 }
				
			    }
				else
				{
						$sess_id	= Get_session_Id_from();
				        $sql_sel = "SELECT passport_orgfilename,tenant_name FROM cart_passport_details WHERE session_id = '$sess_id' AND tenant_name='".strtolower($tenantname)."' AND sites_site_id=$ecom_siteid" ;
						$ret_sel = $db->query($sql_sel);
						$row_sel = $db->fetch_array($ret_sel);
						$sel_file = $row_sel['passport_orgfilename'];
						$ten_file = $row_sel['tenant_name'];
						if($sel_file=='')
						{
							$errors_passport['number'] = $tenantname;
						}
				}
			//print_r($errors_passport);exit;
			     if($_REQUEST['cart_mod']!='show_cart' && $_REQUEST['cart_mod']!='place_order_preview')
			     {
					
					 if(count($errors_passport))
					 {
					    $whichtenant = $errors_passport['number'];
						//echo $whichtenant;
						
						if($cartHtml=="")
						{ 
							require("themes/$ecom_themename/html/cartHtml.php");
							$cartHtml= new cart_Html(); // Creating an object for the cart_Html class
						}
						
						echo '
							<script type="text/javascript">
							window.location = "'.url_link('checkout.html?&alertp='.$whichtenant.'',1).'";
							</script>
						';
						exit;
						 
						 
					 }
					 
			     }
		}
		
	}

	//fileupload
	
}
function passport_fileupload($input_filename,$insert_id,$tenantname)
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$image_path;
         $sess_id = Get_session_Id_from();
         if(isset($_FILES[$input_filename])){
			        $passfilename  = $insert_id.'_'.$_FILES[$input_filename]['name'];
					$errors= array();
					//echo $_FILES[$input_filename]['name'];

					$file_name 	= $passfilename;
					$file_size 	=$_FILES[$input_filename]['size'];
					$file_tmp 	=$_FILES[$input_filename]['tmp_name'];
					$file_type	=$_FILES[$input_filename]['type'];
					$file_ext 	=strtolower(end(explode('.',$_FILES[$input_filename]['name'])));

					$expensions= array("jpeg","jpg","png");
					/*			
					  if(in_array($file_ext,$expensions)=== false){
						 $errors[]="extension not allowed, please choose a JPEG or PNG file.";
					  }
					   
					 if($file_size > 2097152){
					$errors[]='File size must be excately 2 MB';
					}*/
                    $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
					$detectedType = exif_imagetype($_FILES[$input_filename]['tmp_name']);
					
					if(!in_array($detectedType, $allowedTypes))
					{ 
					  $errors['number'] = $tenantname;
					}
					if(empty($errors)==true){
						    $update_array	= array();
							$update_array['passport_filename']		= $passfilename;
							$db->update_from_array($update_array,'cart_passport_details',array('session_id'=>$sess_id,'sites_site_id'=>$ecom_siteid,'id'=>$insert_id));

						$dir_name = $image_path.'/passport_details';
						if (!file_exists($dir_name)) {
							$oldmask = umask(0);
							mkdir($dir_name, 0777, true);
							umask($oldmask);
						}
						$ext_path = $dir_name."/".$passfilename;

					 //move_uploaded_file($file_tmp,$ext_path);
					 resize_image_passport($file_tmp,$passfilename,'600x600>',$file_type,1,$ext_path);


					 
					 //echo "Success";
					}
				}
				
				//echo $_FILES[$input_filename]['tmp_name'];
				return $errors ;
}
//Function to resize and copy the uploaded files to required location
function resize_image_passport($old, $new, $geometry, $exten,$resize_me = 1,$ext_path,$overwrite=false)
{
	
	$convert_path = '/usr/bin';
	// Probably not necessary, but might as well
	if(!is_uploaded_file($old)) {
		return FALSE;
	}
	//echo "Image_path".$image_path.' <br> convert path'.$convert_path;
	$base = substr($new, 0, strrpos($new, "."));
	if($exten == "image/gif")
	{
		$new = "$base.gif";
	}
	elseif($exten == "image/png")
	{
		$new = "$base.png";
	}
	else
	{
		$new = "$base.jpg";
	}
	$n = 0;
	
	if ($resize_me==1)
	{
		$command = $convert_path."/convert \"$old\" -auto-orient -geometry \"$geometry\" -interlace Line  \"$ext_path\" 2>&1";
		//echo htmlentities($command) . "<br><br>";

		$p = popen($command, "r");
		$error = "";
		while(!feof($p)) {
			$s = fgets($p, 1024);
			$error .= $s;
		}
		$res = pclose($p);

		if($res == 0) return $new;
		else {
			echo ("Failed to resize image: $error<br>");
			return FALSE;
		}
	}
	
}
function save_CheckoutDetails_extrabilling()
{
	global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr;
	global $ecom_selfhttp;
	include_once('includes/cartCalc.php');

	$sess_id	= Get_session_Id_from();
	$cartData   = cartCalc();
	$qty      = $cartData['totals']['qty'];
	
    //$ext_name = "tenant".$cnt."_";

	//$cartData['totals']['qty'];
	if($qty>1)
	{
	$cnt = 0;
	$ext_name = "";
	$tenantname = "";
	// Delete any record existing in cart_checkout_values table for current site and current session
	$sql_check = "DELETE
					FROM
						cart_checkout_values_extrabilling
					WHERE
						session_id = '".$sess_id."'
						AND sites_site_id = $ecom_siteid ";
	$ret_check = $db->query($sql_check);
		for($i=1;$i<$qty;$i++)
		{ 
		$cnt = $i+1;
		$ext_name = "tenant".$cnt."_";
		$tenantname = "tenant".$cnt;
		// Building an array which decides which all fields to be saved in this table
		$fieldname_arr = array();
		$fieldtext_arr = array();
		$field_type_arr = array();
		$sql_checkout = "SELECT field_key,field_name
						FROM
							general_settings_site_checkoutfields
						WHERE
							sites_site_id = $ecom_siteid
							AND field_type IN('PERSONAL')";
		$ret_checkout = $db->query($sql_checkout);
		if ($db->num_rows($ret_checkout))
		{
			while ($row_checkout = $db->fetch_array($ret_checkout))
			{
				$fieldname_arr[] 						  = $ext_name.$row_checkout['field_key'];
				$fieldtext_arr[$ext_name.$row_checkout['field_key']] = $row_checkout['field_name'];
			}
		}	
			if(count($fieldname_arr))
			{
				// Saving the custom fields and dynamic fields values to checkout value save table
					foreach ($_REQUEST as $k=>$v)
					{ 
						//$k = $ext_name.$k;
						// Check whether the current fields name is there in the fieldname_arr array. If yes then save it in the cart_checkout_value table
						if (in_array($k,$fieldname_arr))
						{ 
							
							$insert_array									= array();
							$insert_array['session_id']					=	 $sess_id;
							$insert_array['sites_site_id']				= $ecom_siteid;
							$insert_array['checkout_fieldname']	= add_slash($k);
							$insert_array['checkout_orgname']	= $fieldtext_arr[$k];

							if($field_type_arr[$k]=='checkbox' || $field_type_arr[$k]=='radio')
							{ 
							  if(is_array($v)){
								  foreach($v as $key=>$value)
								  { 
								   $insert_array['checkout_value']			= add_slash($value);
								  }
							  }
							}
							else
							$insert_array['checkout_value']			= add_slash($v);
							$insert_array['tenant_name']			= $tenantname;

							$db->insert_from_array($insert_array,'cart_checkout_values_extrabilling');
						}

				}		
			}
		}
	}
	
}	
// Function to get the checkout values temporarly saved for current cart
function get_CheckoutValues_extrabilling($pass_arr=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox;
	global $ecom_selfhttp;
	$sess_id = Get_session_Id_from();
	$ret_arr = array();
	if(count($pass_arr)==0)
	{
		$sql = "SELECT checkout_fieldname,checkout_value
					FROM
						cart_checkout_values_extrabilling
					WHERE
						session_id='".$sess_id."'
						AND sites_site_id = $ecom_siteid";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			while($row = $db->fetch_array($ret))
			{
				$ret_arr[$row['checkout_fieldname']] = stripslashes($row['checkout_value']);
			}
		}
	}
	
	return $ret_arr;
}
function save_extrabilling_details_order($order_id)
{
    global $db,$ecom_common_settings,$ecom_siteid,$ecom_hostname,
		$Settings_arr,$Captions_arr,$ecom_is_country_textbox;	
	global $ecom_selfhttp;	
	$cust_id 				= get_session_var('ecom_login_customer'); // Getting the id of customer .. if logged in
	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	$sess_id 				= Get_session_Id_from(); // getting the id of current session
	$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
    $cnt = 1;
    $extcnt = 0;
    $extname = "";
    $dbextname = "";
    if(($cartData['totals']['qty'])>1)
		{
			while($cnt<$cartData['totals']['qty'])
			{
				$extcnt    = $cnt+1;
				$extname   = "tenant".$extcnt."_";
				$dbextname = "tenant".$extcnt;
				$checkout_arr = array();
				$checkoutorg_arr = array();
			// Get all the checkout details saved for current site in current session
			$sql_checkoutDetails = "SELECT session_id,sites_site_id,checkout_fieldname,checkout_orgname,checkout_value,tenant_name 
							FROM
								cart_checkout_values_extrabilling
							WHERE
								sites_site_id = $ecom_siteid
								AND session_id = '".$sess_id."'
								AND tenant_name='".$dbextname."'";
			$ret_checkoutDetails = $db->query($sql_checkoutDetails);
			if ($db->num_rows($ret_checkoutDetails))
			{
				while($row_checkoutDetails = $db->fetch_array($ret_checkoutDetails))
				{
					$checkout_arr[$row_checkoutDetails['checkout_fieldname']] = $row_checkoutDetails['checkout_value'];
					$checkoutorg_arr[$row_checkoutDetails['checkout_fieldname']] = $row_checkoutDetails['checkout_orgname'];			

				}
			}
			$cust_id 					= get_session_var('ecom_login_customer'); // Getting the id of customer .. if logged in
			$cust_title 					= $checkout_arr[$extname."checkout_title"];
			$cust_companyname 	= $checkout_arr[$extname."checkout_comp_name"];
			$cust_fname		 		= $checkout_arr[$extname.'checkout_fname'];
			$cust_mname		 		= $checkout_arr[$extname.'checkout_mname'];
			$cust_surname			= $checkout_arr[$extname.'checkout_surname'];
			$cust_buildingno 		= $checkout_arr[$extname.'checkout_building'];
			$cust_street	 		= $checkout_arr[$extname.'checkout_street'];
			$cust_city		 		= $checkout_arr[$extname.'checkout_city'];
			$cust_state		 		= $checkout_arr[$extname.'checkout_state'];
			$cust_country	 		= $checkout_arr[$extname.'checkout_country'];
			$cust_zip		 		= $checkout_arr[$extname.'checkout_zipcode'];
			$cust_phone		 		= $checkout_arr[$extname.'checkout_phone'];
			$cust_mobile	 			= $checkout_arr[$extname.'checkout_mobile'];
			$cust_fax	 				= $checkout_arr[$extname.'checkout_fax'];
			$cust_email	 			= $checkout_arr[$extname.'checkout_email'];
			$cust_notes	 			= $checkout_arr[$extname.'checkout_notes'];
						
			if($ecom_is_country_textbox!=1) // case if country is displayes as drop down box .. so the name of the country should be picked from database
			{
				$sql_countryname = "SELECT country_name 
										FROM 
											general_settings_site_country 
										WHERE 
											country_id='".$cust_country."' 
											AND sites_site_id = $ecom_siteid  
										LIMIT 
											1";
				$ret_countryname = $db->query($sql_countryname);
				if($db->num_rows($ret_countryname))
				{
					$row_countryname 	= $db->fetch_array($ret_countryname);
					$cust_country		= stripslashes($row_countryname['country_name']);
				}
			}
			
			// Inserting the details to orders table and get the order id
			$insert_array										= array();
			$insert_array['customers_customer_id']				= $cust_id ;
			$insert_array['sites_site_id']						= $ecom_siteid ;
			$insert_array['order_id']						    = $order_id ;
			$insert_array['order_tenant_name']					= $dbextname ;

			// Billing
			$insert_array['order_custtitle']					= addslashes(stripslashes($cust_title));
			$insert_array['order_custfname']					= addslashes(stripslashes($cust_fname));
			$insert_array['order_custmname']					= addslashes(stripslashes($cust_mname));
			$insert_array['order_custsurname']					= addslashes(stripslashes($cust_surname));
			$insert_array['order_custcompany']					= addslashes(stripslashes($cust_companyname));
			$insert_array['order_buildingnumber']				= addslashes(stripslashes($cust_buildingno));
			$insert_array['order_street']						= addslashes(stripslashes($cust_street));
			$insert_array['order_city']							= addslashes(stripslashes($cust_city));
			$insert_array['order_state']						= addslashes(stripslashes($cust_state));
			$insert_array['order_country']						= addslashes(stripslashes($cust_country));
			$insert_array['order_custpostcode']					= addslashes(stripslashes($cust_zip));
			$insert_array['order_custphone']					= addslashes(stripslashes($cust_phone));
			$insert_array['order_custmobile']					= addslashes(stripslashes($cust_mobile));
			$insert_array['order_custfax']						= addslashes(stripslashes($cust_fax));
			$insert_array['order_custemail']					= addslashes(stripslashes($cust_email));
			$insert_array['order_notes']						= addslashes(stripslashes($cust_notes));
			$db->insert_from_array($insert_array,'order_extra_billing_details');
			$cnt++;
			}
	}
			
}
function save_passport_details_order($order_id)
{
    global $db,$ecom_common_settings,$ecom_siteid,$ecom_hostname,
		$Settings_arr,$Captions_arr,$ecom_is_country_textbox;	
	global $ecom_selfhttp;
	$cust_id 				= get_session_var('ecom_login_customer'); // Getting the id of customer .. if logged in
	$Captions_arr['CART'] 	= getCaptions('CART'); // Getting the captions to be used in this page
	$sess_id 				= Get_session_Id_from(); // getting the id of current session
	$cartData 				= cartCalc(); // Calling the function to calculate the details related to cart
    $cnt = 1;
    $extcnt = 0;
    $extname = "";
    $dbextname = "";
	if(($cartData['totals']['qty'])>0)
			{
			
				$extcnt    = $cnt+1;
				$extname   = "tenant".$extcnt."_";
				$dbextname = "tenant".$extcnt;
				$pass_qry = " SELECT * FROM
						cart_passport_details
					WHERE
						session_id = '".$sess_id."'
						AND sites_site_id = $ecom_siteid ";
				$ret_pass_qry = $db->query($pass_qry);
				while($row_pass_qry=$db->fetch_array($ret_pass_qry))	
				{
				        $DEL_sql = "DELETE FROM order_passport_details WHERE orders_order_id=$order_id AND tenant_name='".$row_pass_qry['tenant_name']."'";
				        $db->query($DEL_sql);
				        $insert_array = array();
				        $insert_array['orders_order_id']= $order_id;
				        $insert_array['passport_orgfilename'] =     $row_pass_qry['passport_orgfilename'];
				        $insert_array['passport_filename'] 	  = 	$row_pass_qry['passport_filename'];

				        $insert_array['tenant_name'] = $row_pass_qry['tenant_name'];
				        $insert_array['deposit_amount'] = $row_pass_qry['deposit_amount'];

				        $db->insert_from_array($insert_array,'order_passport_details');
				        
				}	
			
			}
}
function check_IndividualSslActive() // Check whether ssl is activated for individual domains
{
	global $db, $ecom_siteid, $ecom_hostname,$ecom_advancedseo,$ecom_selfssl_active;
	if($ecom_selfssl_active==1)
	{
		return true;
	}
}
function mail_Phpmaler($toaddr_cust, $email_subject,$email_content,$email_from,$ecom_hostname,$email_headers)
{  
	global $db, $ecom_siteid, $ecom_hostname;	
			//if($ecom_siteid==111 || $ecom_siteid==53)
			{
			if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
				 require_once 'PHPMailer-6.0.5/src/Exception.php';
				 require_once 'PHPMailer-6.0.5/src/PHPMailer.php';
				 require_once 'PHPMailer-6.0.5/src/SMTP.php';
				}

				$mail = new PHPMailer\PHPMailer\PHPMailer(true);									
				try {
				$mail->SMTPDebug = false;
			//$mail->SMTPDebug = 2;
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 587;
			$mail->SMTPSecure = 'tls';
			$mail->CharSet = 'utf-8';
			$mail->Encoding = 'base64';
			// set the SMTP port for the GMAIL server							
				$mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
				$mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
				$mail->Password = '-s8Uh:cz-ECL9/N9';                           // SMTP password
				$mail->SMTPKeepAlive = true; 
				//error_reporting(E_ALL);
			//ini_set('display_errors', '1');
			//echo "test";							
			//$email_from ='';
			$mail->setFrom($email_from, $ecom_hostname);
			$mail->addAddress($toaddr_cust);
			//$mail->addReplyTo('sales-5@sales.webclinicmailer.co.uk'); 
			$mail->addReplyTo($email_from); 
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $email_subject;
			$mail->Body    = $email_content;  
			$mail->send();
			return true;
			 $mail->ClearAllRecipients();
			$mail->SmtpClose();
		}	catch (phpmailerException $e) {
			return $e->errorMessage;
		} catch (Exception $e) {
		  return $e->getMessage();
		}
			//error_reporting(E_ALL);
			//ini_set('display_errors', '1');
			//echo "test";
			}   
     //echo "testphp mailer";exit;
}
function mail_Phpmaler_admin($toaddr_cust_arr=array(), $email_subject,$email_content,$email_from,$ecom_hostname,$email_headers)
{  
	global $db, $ecom_siteid, $ecom_hostname;	
	$toaddr_cust_arr = array_filter($toaddr_cust_arr);
	if($email_reply=='')
	{
	$email_reply = $email_from;
	}	
  
    //require("PHPMailer/class.smtp.php");							
	if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) 
		{
		 require_once 'PHPMailer-6.0.5/src/Exception.php';
		 require_once 'PHPMailer-6.0.5/src/PHPMailer.php';
		 require_once 'PHPMailer-6.0.5/src/SMTP.php';
		}

   $mail = new PHPMailer\PHPMailer\PHPMailer(true);
	try {
			$mail->SMTPDebug = false;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->CharSet = 'utf-8';
			$mail->Encoding = 'base64';
			//if($ecom_siteid==111)
			//{
			 $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
			 $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
			 $mail->Password = '-s8Uh:cz-ECL9/N9';
			 $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			 $mail->Port = 587; 
			 $mail->SMTPKeepAlive = true;
			 //error_reporting(E_ALL);
			//ini_set('display_errors', '1');
			//echo "test";
			//}											 
			// TCP port to connect to
			//Recipients
			$mail->setFrom($email_from,$ecom_hostname);
			for($i=0;$i<count($toaddr_cust_arr);$i++) // send the order confm mail to as many mail ids which are set
				{
					if ($toaddr_cust_arr[$i]!='')
					{
						if($i==0)
						{
							$mail->addAddress($toaddr_cust_arr[$i]); 
						}
						else
						{
							$mail->addBCC($toaddr_cust_arr[$i]); 
						}
					}
				}
			 // Name is optional
			$mail->addReplyTo($email_reply);
			//Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $email_subject;
			$mail->Body    = $email_content;
			$mail->send();
			$mail->ClearAllRecipients();
			$mail->SmtpClose();
			 return true;
		} catch (phpmailerException $e) {
			return $e->errorMessage;
		} catch (Exception $e) {
		  return $e->getMessage();
		}
}
function mail_Phpmaler_admin_new_bak($toaddr_cust_arr,$email_subject,$email_content,$email_from,$ecom_hostname,$email_headers,$email_reply='')
{  
	global $db, $ecom_siteid, $ecom_hostname;
	if($email_reply=='')
	{
	$email_reply = $email_from;
	}	
		//error_reporting(E_ALL);
		//ini_set('display_errors', '1');
	  require_once('PHPMailer/src/Exception.php');
	  require_once('PHPMailer/src/SMTP.php');
      require_once("PHPMailer/src/PHPMailer.php");
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    //$mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    /*if($ecom_siteid==104)
    {
	$mail->Host = 'mail.discount-mobility.co.uk';  // Specify main and backup SMTP servers
    $mail->Username = 'online.orders@discount-mobility.co.uk';                 // SMTP username
    $mail->Password = 'fln75g';     
	}
    else
    {*/
    $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
    $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
    $mail->Password = '-s8Uh:cz-ECL9/N9';                           // SMTP password
	//}
    $mail->SMTPAuth = true;                               // Enable SMTP authentication

    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;
    //$email_from ='';
     $mail->setFrom($email_from, $ecom_hostname);
		for($i=0;$i<count($toaddr_cust_arr);$i++) // send the order confm mail to as many mail ids which are set
		{
			if ($toaddr_cust_arr[$i]!='')
			{
				if($i==0)
				{
				    $mail->AddAddress($toaddr_cust_arr[$i]); 
				}
				else
				{
					$mail->addBCC($toaddr_cust_arr[$i]); 
				}
			}
		}
     //$mail->addReplyTo('sales-5@sales.webclinicmailer.co.uk'); 
     $mail->addReplyTo($email_reply); 
     $mail->Subject = $email_subject;
     $mail->Body    = $email_content; 
     $mail->isHTML(true);    // Set email format to HTML     
     //$mail->send();
     if(!$mail->send()) {
		 return $mail->ErrorInfo;
	 }
	 else
	 {
	   return true;
	 }  
     //exit;   
}
function mail_Phpmaler_admin_new($toaddr_cust_arr=array(),$email_subject,$email_content,$email_from,$ecom_hostname,$email_headers,$email_reply='')
{  
	global $db, $ecom_siteid, $ecom_hostname;
	$toaddr_cust_arr = array_filter($toaddr_cust_arr);

	if($email_reply=='')
	{
	$email_reply = $email_from;
	}	
	if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
	 require_once 'PHPMailer-6.0.5/src/Exception.php';
	 require_once 'PHPMailer-6.0.5/src/PHPMailer.php';
	 require_once 'PHPMailer-6.0.5/src/SMTP.php';
	}
	 $mail = new PHPMailer\PHPMailer\PHPMailer(true);	 
    try {
    $mail->SMTPDebug = false;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->CharSet = 'utf-8';
	$mail->Encoding = 'base64';
    //if($ecom_siteid==111)
    {
     $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
     $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
     $mail->Password = '-s8Uh:cz-ECL9/N9';
     $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
     $mail->Port = 587; 
     $mail->SMTPKeepAlive = true;
     //error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	//echo "test";
    }
    /*
    else
    {                          // SMTP password
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Username   = "bshopmail3@gmail.com";  // GMAIL username
	$mail->Password   = "calpine*123"; 
	 $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
     $mail->Port = 587; 
	}
	*/  
    // TCP port to connect to
    //Recipients
    $mail->setFrom($email_from,$ecom_hostname);
    for($i=0;$i<count($toaddr_cust_arr);$i++) // send the order confm mail to as many mail ids which are set
		{
			if ($toaddr_cust_arr[$i]!='')
			{
				if($i==0)
				{
				    $mail->addAddress($toaddr_cust_arr[$i]); 
				}
				else
				{
					$mail->addBCC($toaddr_cust_arr[$i]); 
				}
			}
		}
     // Name is optional
    $mail->addReplyTo($email_reply);
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $email_subject;
    $mail->Body    = $email_content;
    $mail->send();
    $mail->ClearAllRecipients();
	$mail->SmtpClose();
     return true;
} catch (phpmailerException $e) {
	return $e->errorMessage;
} catch (Exception $e) {
  return $e->getMessage();
}
}
function write_email_as_file_error($mod,$id,$content,$error)
{
	global $image_path,$db;
	global $ecom_selfhttp;
	$fname 	= $id.'.txt';
	$folder = '';
			if(!is_dir($image_path.'/email_messages_error'))
			{
			 	mkdir($image_path.'/email_messages_error',0777);
			} 
			if(!is_dir($image_path.'/email_messages_error'))
			{
			 	mkdir($image_path.'/email_messages_error',0777);
			} 
			$folder	= 'email_messages_error';
			
		
	
	if($folder)
	{
		$fp = fopen($image_path.'/'.$folder.'/'.$fname,'w');
		fwrite($fp,$error);
		fclose($fp);
	}
}
?>
