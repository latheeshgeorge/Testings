<? 
	set_time_limit(0);
	require_once("sites.php");
	require_once("config.php");
	
	if($ecom_selfssl_active==1)
	{
		$http = "https://";
	}
	else
	{
		$http = "http://";
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
	// Setting up the things to be replaced
	$_strip_search 	= array("![, ]+$|^[, ]+!m", // remove leading/trailing space chars
							'%[\r\n]+%m'); // remove CRs and newlines
	$_strip_replace = array('','');
	$_cleaner_array = array(">" => "> ", "&reg;" => "", "�" => "", "&trade;" => "", "�" => "", "\n" => "", "," => "    ","&nbsp;"=>"  ","&amp;"=>"&");
	$sr_arr 		= array("&","<",">","/","'",'"');
	$rp_arr 		= array("&amp;","&lt;","&gt;","","","");
	// Getting the details of all products in current site 
	$sql_prod 		= "SELECT * FROM products WHERE sites_site_id = '$ecom_siteid' AND product_hide='N' AND product_exclude_from_feed ='N' ORDER BY product_name";
	$ret_prod		= $db->query($sql_prod);
		
	$download_name 	= str_replace(" ","_",trim($ecom_title));
	$download_name 	= 'nextag_data_'.$download_name;
	// Setting the header type
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=".$download_name.".csv;");
	header("Accept-Ranges:bytes");
	
	print '"Manufacturer","Manufacturer Part #","Product Name","Product Description","Click-Out URL","Price","Category: Other Format","Category: Nextag Numeric ID","Image URL","Ground Shipping","Stock Status","Product Condition","Marketing Message","Weight","Cost-per-Click","UPC","Distributor ID","MUZE ID","ISBN"'."\n";
	$sql_price = "SELECT price_displaytype FROM general_settings_site_pricedisplay WHERE sites_site_id =$ecom_siteid LIMIT 1";
	$ret_price = $db->query($sql_price);
	if ($db->num_rows($ret_price))
	{
		$row_price = $db->fetch_array($ret_price);
	}
	$sql_settings = "SELECT saletax_before_discount FROM general_settings_sites_common WHERE sites_site_id = $ecom_siteid LIMIT 1"; 
	$ret_settings = $db->query($sql_settings);
	if ($db->num_rows($ret_settings))
	{
		$row_settings = $db->fetch_array($ret_settings);
	}
	// Get the tax value from general_settings_site_tax
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
			$tax_val += $row_tax['tax_val'];
		}
	}
	// Iterating thorough the products of current site
	while($row_prod = $db->fetch_array($ret_prod)) 
	{
		//amount section start 
			$pid 		= $row_prod['product_id'];
			$curprice 	= generate_price($row_prod,$row_price,$row_settings,$tax_val);
		//amount section end
		$bigimage_path = '';
		// Getting any one of the images for the product
		/* $sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_product b 
					WHERE b.products_product_id = ".$row_prod[product_id]." AND a.image_id=b.images_image_id
					ORDER BY b.image_order LIMIT 1";*/
		 $sql_img = "SELECT a.image_id, a.image_bigpath, a.image_thumbpath FROM images a,images_product b 
					WHERE b.products_product_id = ".$row_prod[product_id]." AND a.image_id=b.images_image_id
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
		// Generating the relative path for the images
		$big_image_path 	= "../images/".$ecom_hostname."/".$bigimage_path;
		$thumb_image_path 	= "../images/".$ecom_hostname."/".$thumb_path;
		
		
		$cname = '';
		$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." LIMIT 1";
		$ret_cat = $db->query($sql_cat);
		if ($db->num_rows($ret_cat))
		{
			$row_cat 	= $db->fetch_array($ret_cat);
			$cname 		= $row_cat['category_name'];
		}
		
		//checking for whether there is value in image field of db and whether file exists in directory and checking whether the amount field is empty
		//if ((file_exists($big_image_path) or file_exists($thumb_image_path)) and ($bigimage_path != "" or $thumb_path != "") and ($curprice > 0) and ($row_prod['product_default_category_id'])) 
		if ((file_exists($big_image_path)) and ($bigimage_path != "")  and ($curprice > 0) and ($row_prod['product_default_category_id']) and ($cname != '')) 
		{				
			// id
			//print  $row_prod['product_id'].",";		
			
			//Manufacturer
			if($row_prod['product_model']) // check whether product model exists
			{
				$row_prod['product_model'] = addquotes($row_prod['product_model']);
				$brnd = str_replace('[','',$row_prod['product_model']);
				$brnd = str_replace(']','',$brnd);
				print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))).'"'. ",";
			}
			else if($row_prod['manufacture_id']) // check whether manufacturer id exists
			{
				$brnd = str_replace('[','',addquotes($row_prod['manufacture_id']));
				$brnd = str_replace(']','',$brnd);
				print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))).'"'. ",";
			}
			else // case if mode or manufacturer id does not exists
			{
				$brnd = str_replace('[','',addquotes($row_prod['product_name']));
				$brnd = str_replace(']','',$brnd);
				print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))).'"'. ",";
			}
			
			
			//Manufacturer Part #
			print '""'.",";  
			
			//Product Name
			$title = addquotes($row_prod['product_name']);
			$title  = substr($title,0,70);
			print '"'.utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title))))).'"'.",";
				
			//Product Description
			if (trim($row_prod['product_shortdesc'])) 
			{
				$short_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_shortdesc'], $_cleaner_array))) ;
				$short_desc  = substr($short_desc,0,999);
				print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,addquotes($short_desc))).'"'. ",";
			}
			else
			{
				print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags(addquotes($row_prod['product_name'])))).'"'.",";
			}
			
			//Click-Out URL
			print '"'.$http.$ecom_hostname."/p".$row_prod['product_id']."/" . strip_url_console(addquotes($row_prod['product_name'])). ".html".'"'.",";
			
			//Price
			$pp = sprintf("%01.2f",$curprice);
			print  '"'.$pp.'"'.",";
			
			//Category: Other Format
			$cname = '';
			$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." LIMIT 1";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{
				$row_cat 	= $db->fetch_array($ret_cat);
				$cname 		= addquotes($row_cat['category_name']);
				if($cname)
				{
					print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,$cname)).'"'.",";
				}
				else
				{
					print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,addquotes($row_prod['product_name']))).'"'.",";
				}
			}
			else
			{
				print '""'.",";
			}
			
			//Category: Nextag Numeric ID
			print '""'.",";
			
			//Image URL
			if(file_exists($big_image_path) and $bigimage_path!='')
			{
				$bigimage_path = str_replace('[','%5B',$bigimage_path);
				$bigimage_path = str_replace(']','%5D',$bigimage_path);
				$big_image_url = $http.$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path;
				print '"'.$big_image_url.'"'.",";
			}
			
			//Ground Shipping
			print '"0"'.",";
			
			//Stock Status	
			$web_stock = 0;
			if($row_prod['product_variablestock_allowed']=='Y')
			{
				$ret_stock  	= get_product_stock($row_prod);
				$web_stock		= trim($ret_stock['web_stock']);
			}
			else
			{
				$web_stock		= trim($row_prod['product_webstock']);
			}
			if($web_stock<0 or !is_numeric($web_stock))
			{
				$web_stock = 0;
			}
			
			$availability = "";
			if($row_prod['product_preorder_allowed'] == "Y")
			{
				$availability = "Yes";
			}
			elseif($web_stock > 0)
			{
				$availability = "Yes";
			}
			else
			{
				if($row_prod['product_alloworder_notinstock'] == "Y")
				{
					$availability = "Yes";
				}
				else
				{
					$availability = "No";
				}
			}
		   print '"'.utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($availability))).'"'.",";
				
			
			
			//Product Condition
			print '"'.utf8_encode("New").'"'.",";
			
			
			//Marketing Message
			print '""'.",";
			
			//Weight
			print '""'.",";
			
			//Cost-per-Click
			print '""'.",";
			
			//UPC
			print '""'.",";
			
			//Distributor ID
			print '""'.",";
						
			//MUZE ID
			print '""'.",";
			
			//ISBN
			print '""'."\n";
						
	   
		} 
	}//end while
	
	
	function addquotes($dd)
	{
		$dd =  str_replace('"', ' ', stripslashes($dd));
		//$dd = '"'.$dd.'"';
		//$dd = '"' . str_replace(',', ' ', stripslashes($dd)) . '"';
		return $dd;
	}
	 
	function generate_price($prod_arr,$row_price,$row_settings,$tax_val)
	{
		// Function to generate the price should be specified here
		global $db,$ecom_siteid,$ecom_allpricewithtax;
		global $http;
		$webprice 			= $prod_arr['product_webprice'];
		$disc_asval			= $prod_arr['product_discount_enteredasval'];
		$tax_before_disc	= $row_settings['saletax_before_discount'];
		$discount			= 0;
		
		if($prod_arr['product_discount']>0)
		{
			if($disc_asval==2)  // For Exact Discount Price 
				$discount	= $webprice-$prod_arr['product_discount']; 	
			else
				$discount	= $prod_arr['product_discount'];
		}	
		$apply_tax		= $prod_arr['product_applytax'];
		/*switch($row_price['price_displaytype'])
		{
			case 'show_price_only':					
			case 'show_price_plus_tax':
				if($discount>0)
				{
					if($disc_asval==1) // If discount is specified as value
					{
						$disc_price = $webprice - $discount;
					}	
					else if($disc_asval==2) 
					{ // For Exact Discount Price 
						$disc_price	= $webprice-$discount; 	// For Exact Discount Price 
					}	
					else // case if discount is given as percentage
					{
						$disc_price = $webprice - ($webprice * $discount/100);
					}
				}	
			break;
			case 'show_price_inc_tax': // Show price including tax
			case 'show_both':*/
				if ($apply_tax=='Y' and $ecom_allpricewithtax==0)	
					$disc_price					= $webprice + ($webprice * $tax_val/100);
				else
					$disc_price = $webprice;
				if ($discount>0)
				{
					if($disc_asval==1) // If discount is specified as value
					{
						$disc_price 			= $webprice - $discount; // calculate the original discount
						if ($apply_tax=='Y')
						{
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
						}
						else
							$disc_price_with_tax = $disc_price;

					}	
					else if($disc_asval==2)  // For Exact Discount Price 
					{
						$disc_price = $webprice - $discount;
						if ($apply_tax=='Y')
						{
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 	= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 	= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
						}
						else
							$disc_price_with_tax = $disc_price;
					}	
					else // case if discount is given as percentage
					{
						$disc_price 				= $webprice - ($webprice * $discount/100);// calculate the original discount
						if ($apply_tax=='Y')
						{						
							if($tax_before_disc==1)// case of apply tax before discount
							{
								$disc_price_with_tax 		= $disc_price + ($webprice * $tax_val/100); // apply tax to it
							}
							else
							{
								$disc_price_with_tax 		= $disc_price + ($disc_price * $tax_val/100); // apply tax to it
							}	
						}
						else
							$disc_price_with_tax = $disc_price;
					}
					$disc_price = $disc_price_with_tax;
					
				}
			/*break;
		};	*/
		if($disc_price>0)
			return $disc_price;
		else
			return $webprice;
	}
	function get_product_stock($row_prod)
	{
		global $db;
		global $http;
		if($row_prod['product_variablestock_allowed'] == 'Y') // Case of variable stock exists
		{
			// Get the combinations for current product
			$sql_comb = "SELECT sum(actual_stock) as actual_stock_sum,sum(web_stock) as totwebstock FROM product_variable_combination_stock WHERE products_product_id=".$row_prod['product_id'];
			$ret_comb = $db->query($sql_comb);
			if ($db->num_rows($ret_comb))
			{
				list($actual_stock,$web_stock) = $db->fetch_array($ret_comb);
				$ret_arr['act_stock'] = $actual_stock;
				$ret_arr['web_stock'] = $web_stock;
			}
		}
		else // Case variable stock does not exists
		{
			$ret_arr['act_stock'] = $row_prod['product_actualstock'];
			$ret_arr['web_stock'] = $row_prod['product_webstock'];;
		}
		return $ret_arr;
	}
	
	function sentence_case($string) 
	{
		$sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		$new_string = '';
		foreach ($sentences as $key => $sentence) {
			$new_string .= ($key & 1) == 0?
				ucfirst(strtolower(trim($sentence))) :
				$sentence.' '; 
		}
		return trim($new_string);
	}
	
	
?>
