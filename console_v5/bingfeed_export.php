<?php
	//include_once("functions/functions.php");
	//include('session.php');
	require_once("sites.php");
	require_once("config.php");
			

	function url_product($prodId,$prodName='',$ret=-1)
{
    global $productPageUrlHash, $db, $ecom_siteid, $ecom_hostname,$ecom_advancedseo;                
    
    if($prodName=='') // case if product name is not passed, then get it from product table
    {
            $sql_prod = "SELECT product_name 
                                    FROM 
                                            products 
                                    WHERE
                                            product_id = $prodId 
                                            AND sites_site_id = $ecom_siteid 
                                    LIMIT 1";
            $ret_prod = $db->query($sql_prod);
            if ($db->num_rows($ret_prod))
            {
                    $row_prod = $db->fetch_array($ret_prod);
                    $prodName = stripslashes($row_prod['product_name']);
            }
    }
    $prodName = strip_url_console($prodName); // Stripping unwanted characters from the product name
    
    if($ecom_advancedseo=='Y')
    {
            $productPageUrlHash = "http://".$ecom_hostname."/".$prodName."-p$prodId.html";
    }
    else
    {
            $productPageUrlHash = "http://".$ecom_hostname."/p$prodId/".$prodName.".html";
    }       
    
    if($ret == -1) // default case of printing the url
    {
            echo ($productPageUrlHash);
    }
    else  // just return the url for the page
    {
            return $productPageUrlHash;
    }       
}

	// Function to strip unwanted things from urls
	function strip_url_console($name)
	{
		$name = trim($name);
		$name = str_replace(" ","-",$name);
		$name = str_replace("_","-",$name);
		$name = preg_replace("/[^0-9a-zA-Z-]+/", "", $name);
		$name = str_replace("----","-",$name);
		$name = str_replace("---","-",$name);
		$name = str_replace("--","-",$name);
		$name = str_replace(".","-",$name);
		return strtolower($name);
	}
	$sec_key = trim($_REQUEST['key']);
	$error_msg = '';
    if($sec_key=='')
	{
		$error_msg = 'Security key is empty';
	}		
	else
	{
		// Check whether security key is valid
		$sql_check = "SELECT sites_site_id 
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND steamdesk_security_key ='". mysql_real_escape_string($sec_key)."' 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rowS($ret_check)==0)
		{
			$error_msg .= 'Invalid Security Key';
		}
		else // case if security key is valid
		{			
			/*
			//$shippping_check_site_arr 	= array(88,70); // skatesrus
	$include_shipping 		= false;
	$shipping_arr = array();
	
		
		
		// Get the delivery id for current website
		$sql_delivery = "SELECT delivery_methods_delivery_id 
							FROM 
								general_settings_site_delivery 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
								
		$ret_delivery = $db->query($sql_delivery);
		if($db->num_rows($ret_delivery))
		{
			$row_delivery = $db->fetch_array($ret_delivery);
			// Check whether this delivery method linked to location
			$sql_del_loc_req = "SELECT deliverymethod_location_required,deliverymethod_text  
									FROM 
										delivery_methods 
									WHERE 
										deliverymethod_id = ".$row_delivery['delivery_methods_delivery_id']." 
									LIMIT 
										1";
			$ret_del_loc_req = $db->query($sql_del_loc_req);
			if($db->num_rows($ret_del_loc_req))
			{
				$row_del_loc_req = $db->fetch_array($ret_del_loc_req);
				if($row_del_loc_req['deliverymethod_location_required']==1)
				{
					
					$sql_site_loc = "SELECT location_id ,delivery_methods_deliverymethod_id, location_name 
										FROM 
											delivery_site_location 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND delivery_methods_deliverymethod_id = ".$row_delivery['delivery_methods_delivery_id']." 
										ORDER BY 
											location_order ";
					$ret_site_loc = $db->query($sql_site_loc);
					if($db->num_rows($ret_site_loc))
					{
						while ($row_site_loc = $db->fetch_array($ret_site_loc))
						{
							$sql_loc_data = "SELECT delopt_price 
												FROM 
													delivery_site_option_details 
												WHERE 
													delivery_site_location_location_id = ".$row_site_loc['location_id']." 
													AND delivery_methods_deliverymethod_id =".$row_delivery['delivery_methods_delivery_id']." 
													AND sites_site_id = $ecom_siteid 
												ORDER BY 
													delopt_option
												LIMIT 
													1";
							$ret_loc_data = $db->query($sql_loc_data);
							if($db->num_rows($ret_loc_data))
							{
								$row_loc_data = $db->fetch_array($ret_loc_data);
								$shipping_arr[] = array('loc'=>stripslashes($row_site_loc['location_name']),'price'=>$row_loc_data['delopt_price']);
							}						
													
						}
					}
				}
				else // case if location is not there
				{
					$del_method = $row_del_loc_req['deliverymethod_text'];
					switch($del_method)
					{
						case 'Weight':
							$sql_gen_get = "SELECT unit_of_weight FROM general_settings_sites_common WHERE sites_site_id = $ecom_siteid LIMIT 1";
							$ret_gen_get = $db->query($sql_gen_get);
							if($db->num_rows($ret_gen_get))
							{
								$row_gen_get = $db->fetch_array($ret_gen_get);
								$caption_prefix = 'Weight';
								$caption_suffix = '('.stripslashes($row_gen_get['unit_of_weight']).')';
							}
							$process_with_delivery_values = true;
						break;
						case 'Amount':
							$process_with_delivery_values = true;
							$caption_prefix = 'Amount';
							$caption_suffix = '';
						break;
						case 'Items':
							$process_with_delivery_values = true;
							$caption_prefix = 'No of Items';
							$caption_suffix = '';
						break;
						case 'None':
							$process_with_delivery_values = false;
							$caption_prefix = '';
							$caption_suffix = '';
						break;
						
					}
					if($process_with_delivery_values==true)
					{
						$sql_loc_data = "SELECT delopt_option,delopt_price 
													FROM 
														delivery_site_option_details 
													WHERE 
														delivery_site_location_location_id = 0  
														AND delivery_methods_deliverymethod_id =".$row_delivery['delivery_methods_delivery_id']." 
														AND sites_site_id = $ecom_siteid 
													ORDER BY 
														delopt_option";
						$ret_loc_data = $db->query($sql_loc_data);
						if($db->num_rows($ret_loc_data))
						{
							$i_cnt =0;
							$prev_cnt = 0;
							while ($row_loc_data = $db->fetch_array($ret_loc_data))
							{
								$caption = $caption_prefix.' more than or equal to '.$prev_cnt.' but less than '.$row_loc_data['delopt_option'].' '.$caption_suffix;
								$shipping_arr[] = array('loc'=>stripslashes($caption),'price'=>$row_loc_data['delopt_price']);
								$i_cnt++;
								$prev_cnt = $row_loc_data['delopt_option'];
							}
						}	
					}
				}
			}
		}
		if(count($shipping_arr))
		{
			$shipping = "\tShipping";		
			$include_shipping = true;
		}
		else
		{
			$shipping = "";		

		}	
		*/ 
		
				$table_name 		= 'products';
				//#Sort
				$sort_by 			= (!$_REQUEST['sort_by'])?'product_name':$_REQUEST['product_name'];
				$sort_order 		= (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
					$sql_prod 		= "SELECT * FROM products WHERE sites_site_id = '$ecom_siteid' AND product_hide='N' AND product_exclude_from_feed ='N' ORDER BY product_name";
					$ret_prod		= $db->query($sql_prod);
				
				
					// Setting the header type
					header("Content-Type: text/plain");
					header("Pragma: no-cache");
								
				print "MPID\tTitle\tBrand\tProductURL\tPrice\tAvailability\tDescription\tImageURL\tMerchantCategory\r\n";
				$sr_arr 		= array("'",'"','®','™');
				$rp_arr 		= array("","",'','');
				
				  while($row_prod = $db->fetch_array($ret_prod))
						{
						$brand_valid = false;
							$bigimage_path = '';
							// Getting any one of the images for the product
							/* $sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_product b 
							WHERE b.products_product_id = ".$row_prod[product_id]." AND a.image_id=b.images_image_id
							ORDER BY b.image_order LIMIT 1";*/
							$sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_product b 
							WHERE b.products_product_id = ".$row_prod['product_id']." AND a.image_id=b.images_image_id
							ORDER BY b.image_order ";
							$ret_img = $db->query($sql_img);
							$bigimage_path_arr = array();
							if ($db->num_rows($ret_img))
							{
							$cnt = 0;
							while ($row_img = $db->fetch_array($ret_img))
							{
							//$row_img 		= $db->fetch_array($ret_img);
							if($cnt == 0)
							{
							$images_id		= $row_img['image_id'];
							$bigimage_path	= $row_img['image_bigpath'];
							$thumb_path		= $row_img['image_thumbpath'];
							}
							else
							{
							if($cnt <= 10)
							{
							$bigimage_path_arr[]	= $row_img['image_bigpath'];
							}
							}

							$cnt++;
							}
							}
							if($bigimage_path=='') // if image is not assigned directly, then check whether variable images option is ticked
							{
							if($row_prod['product_variablecombocommon_image_allowed']=='Y') // case if variable images option is ticked
								{
								$comb_arr = array();
								$comb_arr[] = -1;
								// Get the combination ids related to current product
								$sql_combs = "SELECT comb_id 
											FROM 
												product_variable_combination_stock 
											WHERE 
												products_product_id = ".$row_prod['product_id']." 
											ORDER BY comb_id";
								$ret_combs = $db->query($sql_combs);
								if($db->num_rows($ret_combs))
								{
									while ($row_combs = $db->fetch_array($ret_combs))
									{
										$comb_arr[] = $row_combs['comb_id'];
									}
								}	
								/*$sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_variable_combination b 
										WHERE b.comb_id IN (".implode(',',$comb_arr).") AND a.image_id=b.images_image_id
										ORDER BY b.image_order LIMIT 1";*/
								$sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_variable_combination b 
										WHERE b.comb_id IN (".implode(',',$comb_arr).") AND a.image_id=b.images_image_id
										ORDER BY b.image_order ";
								$ret_img = $db->query($sql_img);
									if ($db->num_rows($ret_img))
									{
										$cnt1 = 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
												//$row_img 		= $db->fetch_array($ret_img);
												if($cnt1 == 0)
												{
													$images_id		= $row_img['image_id'];
													$bigimage_path	= $row_img['image_bigpath'];
													$thumb_path		= $row_img['image_thumbpath'];
												}
												else
												{
													if($cnt1 <= 10)
													{
														$bigimage_path_arr[]	= $row_img['image_bigpath'];
													}
												}
												
												$cnt1++;
										}
									}	
								}
							}	
							$bigimage_path = trim($bigimage_path);
							$thumb_path = trim($thumb_path);						  
						    $big_image_path 	= "../images/".$ecom_hostname."/".$bigimage_path;

							if(file_exists($big_image_path) and $bigimage_path!='')
							{
								
								$bigimage_path = str_replace('[','%5B',$bigimage_path);
								$bigimage_path = str_replace(']','%5D',$bigimage_path);
								$big_image_url = "\thttp://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path;
								
							}
							$pid   		= $row_prod['product_id'];
							$desc 		= "\t".str_replace($sr_arr,$rp_arr,$row_prod['product_shortdesc']);
							
							$prod_url   = "\t".url_product($row_prod['product_id'],$row_prod['product_name'],1);
							$price      = "\t".$row_prod['product_webprice'];
							$category   = "\t".generate_tree_menu($row_prod['product_id']);
							$availability = "";
							$brand_arr = get_shop_name($row_prod);
							$brand_name = $brand_arr['name'];
							if($brand_arr['brand_valid'])
								$p_brand = ' ('.$brand_name.')';
							else
								$p_brand = '';
							$title 		= "\t".str_replace($sr_arr,$rp_arr,$row_prod['product_name'].$p_brand);	
							$shop        = "\t".str_replace($sr_arr,$rp_arr,$brand_name);  
							$web_stock   = $row_prod['product_actualstock'];
							if($row_prod['product_preorder_allowed'] == "Y")
							{
							$availability = "Pre-Order";
							}
							elseif($web_stock > 0)
							{
							$availability = "In Stock";
							}
							else
							{
							if($row_prod['product_alloworder_notinstock'] == "Y")
							{
							$availability = "Pre-Order";
							}
							else
							{
								
								$availability = "Out Of Stock";
							
							}
							
							}
							$avail =  "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($availability)));

							/*if($include_shipping)
							{
								$ship_str = '';
								for($sh_i = 0;$sh_i<count($shipping_arr);$sh_i++)
								{
									if($ship_str!='')
										$ship_str .= ',';
									$loc_name = $shipping_arr[$sh_i]['loc'];
									$loc_price = $shipping_arr[$sh_i]['price'] + $row_prod['product_extrashippingcost'];
									//$ship_str .= '::'.$loc_name.':'.$loc_price;	
									//$ship_str .= 'GB:::'.$loc_price.' GBP';	
									//$ship_str .= 'GB:'.str_replace(':','',$loc_name).'::'.$loc_price.' GBP';
									$new_sr = array(':','&amp;','&',',');
									$new_rp = array('',' and ','and','/');
									//$ship_str .= 'GB:'.str_replace($new_sr,$new_rp,$loc_name).'::'.$loc_price.' GBP';
									$ship_str .= utf8_encode(str_replace($new_sr,$new_rp,$loc_name)).':::'.$loc_price;		
								}
								$ship =  "\t".str_replace($sr_arr_1,$rp_arr_1,strip_tags($ship_str))."\n";
							}
							else
								$ship =  "\n";
								*/ 
							//if($big_image_url=='')
								//$big_image_url = "\t";
	
							$print_rows=true;
							if($row_prod['product_webprice']<=0 or $big_image_url=='' or $desc=='')
								$print_rows=false;							
							if($print_rows)	
								print "$pid$title$shop$prod_url$price$avail$desc$big_image_url$category\r\n";
						
						}
		        }    
	}
	function get_shop_name($row_prod)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		$brand_valid = false;
		$brand_name = '';
		/*$sql_shop = "SELECT a.shopbrand_name
							FROM
								product_shopbybrand a , product_shopbybrand_product_map b 
							WHERE
								b.products_product_id=$pid 
								AND a.sites_site_id = $ecom_siteid 
								AND a.shopbrand_id=b.product_shopbybrand_shopbrand_id LIMIT 1";
			$ret_shop = $db->query($sql_shop);
			 $row_shop = $db->fetch_array($ret_shop);
			return $row_shop['shopbrand_name'];
		*/
		
		//brand
		if($row_prod['product_model']) // check whether product model exists
		{
			//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_model']))). "\t";
			$brnd = str_replace('[','',$row_prod['product_model']);
			$brnd = str_replace(']','',$brnd);
			$brand_name =  utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd)));
			$brand_valid = true;
		}
		else if($row_prod['manufacture_id']) // check whether manufacturer id exists
		{
			//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
			$brnd = str_replace('[','',$row_prod['manufacture_id']);
			$brnd = str_replace(']','',$brnd);
			$brand_name =  utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd)));
			$brand_valid = true;
		}
		else // case if mode or manufacturer id does not exists
		{
			//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
			$brnd = str_replace('[','',$row_prod['product_name']);
			$brnd = str_replace(']','',$brnd);
			$brand_name = utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd)));
		}
		$ret_arr['name'] = $brand_name;
		$ret_arr['brand_valid'] = $brand_valid;
		return $ret_arr;
	  
	}
	function generate_tree_menu($prod_id,$seperator=' > ')
	{
		global $db,$ecom_siteid,$ecom_hostname,$sr_arr,$rp_arr;
		$break_counter_at 	= 10000; // Variable to break the infinite loop
		$counter_val		= 1;
		$found				= false;
		$ret_str			= '';

		
			$cur_id = $_REQUEST['category_id'];// Getting the category id from $_REQUEST object
			// Get the detail of current product
			$sql_prod = "SELECT product_name,product_default_category_id
							FROM
								products
							WHERE
								product_id=$prod_id
								AND sites_site_id=$ecom_siteid LIMIT 1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				if ($cur_id=='')
				{
					$cur_id = $row_prod['product_default_category_id'];
				}
				//$ret_str	= $prefix.stripslashes($row_prod['product_name']).$suffix; // place the name of the product in tree.
			}
        $cnt = 0;
		while($cur_id>0 and $counter_val<$break_counter_at)
		{
			if($cnt==0)
			{
			$seperator = '';	
			}
			else
			{
				$seperator = ' > ';
			}
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
				//if($row_det['category_hide']==0)
					$ret_str	= str_replace($sr_arr,$rp_arr,stripslashes($row_det['category_name'])).$seperator.$ret_str;
				//else
					//$ret_str	= "$prefix ".stripslashes($row_det['category_name'])." $seperator $suffix ".$ret_str;
				$cur_id		= $row_det['parent_id'];
			}
			else
				$break_counter_at = $break_counter_at; // done to handle the case if details of a category is not able to retrieve
		$cnt++;
		}
		if($cnt == 1)
		$seperator = ' > ';
		$ret_str			= "Home".$seperator.$ret_str;
		return $ret_str;
	}
?>
