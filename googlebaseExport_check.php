<? 
	set_time_limit(0);
	include_once("functions/functions.php");
	include('includes/session.php');
	include('includes/urls.php');
	require_once("config.php");
	$proceed_with_valueURL = false;
	if($ecom_siteid==105)
	{
	 $proceed_with_valueURL = true;
 	}
 	function url_product_values($prodId,$prodName='',$ret=-1,$value_id=0,$value_name='')
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
				$prodName = strip_url($prodName); // Stripping unwanted characters from the product name
				if($value_id > 0 && $value_name!='' )
				{
				    $value_name = strip_url($value_name); // Stripping unwanted characters from the value name
					$productPageUrlHash = "http://".$ecom_hostname."/".$prodName."-".$value_name."-p$prodId-x$value_id.html";
				}
				else
				{
					if($ecom_advancedseo=='Y')
					{
						$productPageUrlHash = "http://".$ecom_hostname."/".$prodName."-p$prodId.html";
					}
					else
					{
						$productPageUrlHash = "http://".$ecom_hostname."/p$prodId/".$prodName.".html";
					}	
				}
				if($ret == -1) // default case of printing the url
				{
				url_inline($productPageUrlHash);
				}
				else  // just return the url for the page
				{
				return $productPageUrlHash;
				}
	}
	// Function to strip unwanted things from urls
	function strip_url_console($name)
	{
		/*$name = trim($name);
		$name = preg_replace("/[^0-9a-zA-Z-]+/", "", $name);
		$name = str_replace("---","-",$name);
		$name = str_replace("----","-",$name);
		$name = str_replace("--","-",$name);
		return $name;*/
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
	$_strip_search 	= array("![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
							'%[\r\n]+%m'); // remove CRs and newlines
	$_strip_replace = array('','');
	$_cleaner_array = array(">" => "> ", "&reg;" => "", "�" => "", "&trade;" => "", "�" => "", "\n" => "", "\t" => "    ","&nbsp;"=>"  ","&amp;"=>"&");
	$sr_arr 		= array("&","<",">","/","'",'"','Â£','£');
	$rp_arr 		= array("&amp;","&lt;","&gt;","","","",'&pound;','&pound;');
	$sr_arr_1 		= array("&","<",">","'",'"');
	$rp_arr_1 		= array("&amp;","&lt;","&gt;","","");
	// Getting the details of all products in current site 
	$sql_prod 		= "SELECT * FROM products WHERE sites_site_id = '$ecom_siteid' AND product_hide='N' AND product_exclude_from_feed ='N' ORDER BY product_name";
	$ret_prod		= $db->query($sql_prod);
	$download_name 	= str_replace(" ","_",trim($ecom_title));
	
	// Setting the header type
	header("Content-Type: text/plain");
	//header("Content-Disposition: Attachment; filename=test.txt");
	header("Pragma: no-cache");
	//print "id\tbrand\tcondition\tdescription\texpiration_date\tid\timage_link\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";
	
	/*if($_REQUEST['brn']==1 and $_REQUEST['brc']==1)
		print "id\tbrand\tgtin\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";
	elseif($_REQUEST['brn']==1 and !$_REQUEST['brc'])
		print "id\tbrand\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";
	elseif(!$_REQUEST['brn'] and $_REQUEST['brc']==1)
		print "id\tgtin\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";	
	else
		print "id\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";*/
		
	$str = "";
	$str1 = "";
	$str2 = "";
	$str5 = "";
	if($_REQUEST['brn']==1)
	{
		$str .= "\tbrand";
	}
	if($_REQUEST['brc']==1)
	{
		$str .= "\tgtin";
	}
	if($_REQUEST['mpn']==1)
	{
		$str .= "\tmpn";
	}
	if($_REQUEST['gpc']==1)
	{
		$str3 .= "\tgoogle_product_category";
	}
	if($_REQUEST['avi']==1)
	{
		$str2 .= "\tavailability";
	}
	if($_REQUEST['ail']==1)
	{
		$str1 .= "\tadditional_image_link";
	}
	if($_REQUEST['gpag']==1)
	{
		$str5 .= "\tage_group";
	}
	if($_REQUEST['gpge']==1)
	{
		$str5 .= "\tgender";
	}
	if($_REQUEST['gpcol']==1)
	{
		$str5 .= "\tcolour";
	}
	if($_REQUEST['gpsz']==1)
	{
		$str5 .= "\tsize";
	}	
	
	$shippping_check_site_arr 	= array(88,70,62,76,81); // skatesrus
	$include_shipping 		= false;
	$shipping_arr = array();
	/*if(in_array($ecom_siteid,$shippping_check_site_arr))
	{*/
	
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
					if($ecom_siteid==80)
					{
						$sql_site_loc = "SELECT location_id ,delivery_methods_deliverymethod_id, location_name 
										FROM 
											delivery_site_location 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND delivery_methods_deliverymethod_id = ".$row_delivery['delivery_methods_delivery_id']." 
											AND location_id IN (111,276)
										ORDER BY 
											location_order ";
					}
					elseif($ecom_siteid==77) //shootuk
					{
						$sql_site_loc = "SELECT location_id ,delivery_methods_deliverymethod_id, location_name 
										FROM 
											delivery_site_location 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND delivery_methods_deliverymethod_id = ".$row_delivery['delivery_methods_delivery_id']." 
											AND location_id IN (122)
										ORDER BY 
											location_order ";
					}
					else
					{
						$sql_site_loc = "SELECT location_id ,delivery_methods_deliverymethod_id, location_name 
										FROM 
											delivery_site_location 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND delivery_methods_deliverymethod_id = ".$row_delivery['delivery_methods_delivery_id']." 
										ORDER BY 
											location_order ";
					}						
					$ret_site_loc = $db->query($sql_site_loc);
					if($db->num_rows($ret_site_loc))
					{
						while ($row_site_loc = $db->fetch_array($ret_site_loc))
						{
							
							$del_method = $row_del_loc_req['deliverymethod_text'];
							switch($del_method)
							{
								case 'Location_And_Weight':
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
								case 'Location_And_Amount':
									$process_with_delivery_values = true;
									$caption_prefix = 'Amount';
									$caption_suffix = '';
								break;
								case 'Location_And_Items':
									$process_with_delivery_values = true;
									$caption_prefix = 'No of Items';
									$caption_suffix = '';
								break;
								case 'Location':
									$process_with_delivery_values = false;
									$caption_prefix = '';
									$caption_suffix = '';
								break;
								
							}
							if($process_with_delivery_values==false) // case of location only
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
							else
							{
								$sql_loc_data = "SELECT delopt_option,delopt_price 
													FROM 
														delivery_site_option_details 
													WHERE 
														delivery_site_location_location_id = ".$row_site_loc['location_id']."   
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
										$caption = stripslashes($row_site_loc['location_name']).' - '.$caption_prefix.' more than or equal to '.$prev_cnt.' but less than '.$row_loc_data['delopt_option'].' '.$caption_suffix;
										$shipping_arr[] = array('loc'=>stripslashes($caption),'price'=>$row_loc_data['delopt_price']);
										$i_cnt++;
										$prev_cnt = $row_loc_data['delopt_option'];
									}
								}
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
			//if($ecom_siteid!=61)
			if($ecom_siteid!=61 and $ecom_siteid!=105)
			{
				$str4 = "\tshipping";		
				$include_shipping = true;
			}	
		}	
	/*}
	else
		$str4 = '';*/
	
	//print "id".$str."\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link".$str1.$str2.$str3."\tlink\tprice\tproduct_type\tquantity\ttitle"."\n";	
	print "id".$str."\tcondition\tdescription\texpiration_date\tc:bshopid\timage_link".$str1.$str2.$str3."\tlink\tprice\tproduct_type\tquantity\ttitle\tidentifier_exists".$str4.$str5."\n";
		
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
		//if($row_prod['product_id']==531682)
		{
		$identifier_exists = 'FALSE';
		$identifier_exists_cnt = 0;
		//amount section start 
			$pid 		= $row_prod['product_id'];
			$curprice 	= generate_price($row_prod,$row_price,$row_settings,$tax_val);
			$curprice_withouttax = generate_price($row_prod,$row_price,$row_settings,$tax_val,1);
			$curprice = $curprice - .001;
			//echo "Curprice".sprintf('%0.2f',($curprice));
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
		// Generating the relative path for the images
		$big_image_path 	= "images/".$ecom_hostname."/".$bigimage_path;
		$thumb_image_path 	= "images/".$ecom_hostname."/".$thumb_path;
		/*if($row_prod['product_id']==497696)
		{
			echo '<br>file exists big '.file_exists($big_image_path);
			echo '<br>file exists small'.file_exists($thumb_image_path);
			echo '<br>big'.$bigimage_path.'---thumb '.$thumb_path;
			exit;
					
		}*/	
		//checking for whether there is value in image field of db and whether file exists in directory and checking whether the amount field is empty
		//if ((file_exists($big_image_path) or file_exists($thumb_image_path)) and ($bigimage_path != "" or $thumb_path != "") and ($curprice > 0) and ($row_prod['product_default_category_id'])) 
		if ((file_exists($big_image_path)) and ($bigimage_path != "")  and ($curprice > 0) and ($row_prod['product_default_category_id'])) 
		{
			/*checking barcode --(starts) if barcode ticked then the printing row should have barcode */
			if($_REQUEST['brc']==1)
			{
				$barcode = '';
				//Check whether there exists variables with values for current product
				$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order 
									LIMIT 
										1";
				$ret_varcheck = $db->query($sql_varcheck);
				if($db->num_rows($ret_varcheck))
				{
					$row_varcheck = $db->fetch_array($ret_varcheck);
					$sql_comb = "SELECT comb_barcode 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = ".$row_prod['product_id']." 
										AND comb_barcode!=''
									ORDER BY 
										comb_id 
									LIMIT 
										1";
					$ret_comb = $db->query($sql_comb);	
					if($db->num_rows($ret_comb))
					{
						$row_comb = $db->fetch_array($ret_comb);
						$barcode = $row_comb['comb_barcode'];
					}				
				}
				else
				{
					$barcode = $row_prod['product_barcode'];
				}
				$barcode = trim($barcode);
				if($barcode == '')
				{
					$print_this_row = 0;
				}
				else
				{
					$print_this_row = 1;
				}
			}
			else
			{
				$print_this_row = 1;
			}
		   /*checking barcode --(ends) if barcode ticked then the printing row should have barcode */
		   
		if($print_this_row == 1)
		{		
			if($proceed_with_valueURL == true)
			{
				$sql_varchck = "SELECT var_id ,var_name FROM product_variables WHERE var_value_exists=1 AND var_hide=0 AND products_product_id=".$row_prod['product_id']."";		
				$ret_varchck = $db->query($sql_varchck);
				if($db->num_rows($ret_varchck)>0)
				{
					$row_varchck = $db->fetch_array($ret_varchck);
                }
				if($db->num_rows($ret_varchck)==1)
				{
				   $sql_var_data = "SELECT var_value_id,var_value FROM product_variable_data WHERE product_variables_var_id=".$row_varchck['var_id']." ORDER BY var_order";
                   $ret_var_data = $db->query($sql_var_data);
                   $cn_val =0;
                   while($row_var_data = $db->fetch_array($ret_var_data))
                   {
					   
			// id
						$row_prod['cur_var_value_id'] = $row_var_data['var_value_id'];
						$curprice 	= generate_price_refined($row_prod,$row_price,$row_settings,$tax_val);
						$curprice_withouttax = generate_price_refined($row_prod,$row_price,$row_settings,$tax_val,1);
			
			
			        $cn_val++;
			        $identifier_exists = 'FALSE';
					$identifier_exists_cnt=0;
					print  $row_prod['product_id']."-".$cn_val."\t";			
			if($_REQUEST['brn']==1)
			{   
				$identifier_exists_cnt++;
				if($ecom_siteid==88)//skatesrus,co.uk live siteid
				{
				     //brand
					if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
				else
				{
					//brand
					if($row_prod['product_model']) // check whether product model exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_model']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_model']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
			}	
			if($_REQUEST['brc']==1)
			{
				//barcode
				$barcode = '';
				//Check whether there exists variables with values for current product
				$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order 
									LIMIT 
										1";
				$ret_varcheck = $db->query($sql_varcheck);
				if($db->num_rows($ret_varcheck))
				{
					$row_varcheck = $db->fetch_array($ret_varcheck);
					/*$sql_comb = "SELECT comb_barcode 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = ".$row_prod['product_id']." 
										AND comb_barcode!=''
									ORDER BY 
										comb_id 
									LIMIT 
										1";
					$ret_comb = $db->query($sql_comb);	*/
					$sql_comb = "SELECT comb_barcode 
									FROM 
										product_variable_combination_stock a, product_variable_combination_stock_details b
									WHERE 
										a.comb_id=b.comb_id
										AND a.products_product_id = ".$row_prod['product_id']." 
										AND product_variable_data_var_value_id = ".$row_var_data['var_value_id']."
										AND comb_barcode!='' 
									LIMIT 
										1";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb))
					{
						$row_comb = $db->fetch_array($ret_comb);
						$barcode = $row_comb['comb_barcode'];
					}				
				}
				else
				{
					$barcode = $row_prod['product_barcode'];
				}	
				if(trim($barcode)!='')
				{
					$identifier_exists_cnt++;
				}						
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($barcode))). "\t";
			}
			if($_REQUEST['mpn']==1)	
			{
			  $mpn = '';
			  //check whether mpn exists for the product main 
			   $sql_mpn_check = "SELECT product_model 
									 FROM 
										products 
									 WHERE 
									 product_id = ".$row_prod['product_id']." 
									 AND 
									 product_model!=''
									 LIMIT 1 ";
			   $ret_mpn_check = $db->query($sql_mpn_check);	
			   if($db->num_rows($ret_mpn_check)>0)
			   {
				     $row_mpn 	= $db->fetch_array($ret_mpn_check);  
				     $mpn 		= $row_mpn['product_model'];  
			   }
			   else
			   {
				     /*$sql_varcheck = "SELECT b.var_mpn 
									FROM 
										product_variables a,product_variable_data b 
									WHERE 
										a.products_product_id = ".$row_prod['product_id']."
										AND
										 b.	product_variables_var_id = a.var_id
										AND 
										a.var_value_exists = 1 
										AND 
										b.var_mpn !=''  
									LIMIT 1 ";
									*/
						$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order ";				 
				    $ret_varcheck = $db->query($sql_varcheck);
					if($db->num_rows($ret_varcheck))
					{
						 while($row_var 	= $db->fetch_array($ret_varcheck))
						 {  
										
						   $sql_varvalcheck = "SELECT var_mpn 
									FROM 
										product_variable_data 
									WHERE 
										product_variables_var_id=".$row_var['var_id']." 
										AND var_mpn!='' 
									ORDER BY 
										var_order ";				 
				             $ret_varvalcheck = $db->query($sql_varvalcheck);
				             if($db->num_rows($ret_varvalcheck))
								{
								$row_mpn 	= $db->fetch_array($ret_varvalcheck);  
								$mpn 		= $row_mpn['var_mpn'];
								}  
						 }
					}
				   
			   }
			   if(trim($mpn)!='')
				{
					$identifier_exists_cnt++;
				}
			   print utf8_encode(str_replace($sr_arr_1,$rp_arr_1,strip_tags($mpn))). "\t";					 
			}		
			//condition
			print utf8_encode("new"). "\t";
						
			//Description
			if(trim($row_prod['google_shopping_desc']))
			{
				$google_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['google_shopping_desc'], $_cleaner_array))) ;
				$googletext = str_replace($sr_arr,$rp_arr,$google_desc);
				$googlesub  = substr($googletext,0,999);
				$googlesub =sentence_case(strtolower($googlesub));
				$googlesub	=  str_replace ( "\t", "", $googlesub );	
				print utf8_encode($googlesub). "\t";
				
			}			
			elseif(trim($row_prod['product_longdesc']))
			{
				$long_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_longdesc'], $_cleaner_array))) ;
				$longtext = str_replace($sr_arr,$rp_arr,$long_desc);
				$longsub  = substr($longtext,0,999);
				$longsub = sentence_case(strtolower($longsub));
				$longsub	=  str_replace ( "\t", "", $longsub );	
				print utf8_encode($longsub). "\t";
				
			}
			elseif (trim($row_prod['product_shortdesc'])) 
			{
				$short_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_shortdesc'], $_cleaner_array))) ;
				$short_desc  = substr($short_desc,0,999);
				$short_desc	=  str_replace ( "\t", "", $short_desc );
				print utf8_encode(str_replace($sr_arr,$rp_arr,$short_desc)). "\t";
			}
			else
			{
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
			}
		
			//Expiration date
			$next_year	= date('Y-m-d',mktime(0,0,0,date('m'),date('d')+28,date('Y')));
			print $next_year ."\t";
			
			//ID
			print utf8_encode($ecom_hostname.":".$row_prod['product_id']) ."\t";
		
		
			//image link
			if(file_exists($big_image_path) and $bigimage_path!='')
			{
				//print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path . "\t";
				$bigimage_path = str_replace('[','%5B',$bigimage_path);
				$bigimage_path = str_replace(']','%5D',$bigimage_path);
				$big_image_url = "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path;
				print $big_image_url . "\t";
			}
			/*else if (file_exists($thumb_image_path) and $thumb_path!='')
			{
				print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $thumb_path . "\t";
			}*/
			
			
			//print_r($bigimage_path_arr);
			 //additional image link
			if($_REQUEST['ail']==1)
			{	
			
				$add_image_urls = "";	
				if($bigimage_path_arr)
				{
					foreach($bigimage_path_arr as $bigimg_path)
					{
						$bigimg_path = trim($bigimg_path);
						$big_img_path 	= "images/".$ecom_hostname."/".$bigimg_path;
						if(file_exists($big_img_path) and $bigimg_path!='')
						{
							if($add_image_urls!='')
								$add_image_urls .= ",";
							$bigimg_path = str_replace('[','%5B',$bigimg_path);
							$bigimg_path = str_replace(']','%5D',$bigimg_path);
							$add_image_urls .= "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimg_path ;
						}
					}
				}
				print $add_image_urls. "\t";
			}
			
			//availability
			if($_REQUEST['avi']==1)
			{
				
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
					$availability = "preorder";
				}
				elseif($web_stock > 0)
				{
					$availability = "in stock";
				}
				else
				{
					if($row_prod['product_alloworder_notinstock'] == "Y")
					{
						//$availability = "available for order";
						if($ecom_siteid==104)
						{
							$availability = "in stock";	
						}
						else
						{
							$availability = "preorder";
						}	
					}
					else
					{
						$availability = "out of stock";
					}
				}
				
					print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($availability))). "\t";
				
			}
			
			//google product category
			if($_REQUEST['gpc']==1)
			{	
				$add_google_product_category = "";	
				
				$category_id = $row_prod['product_default_category_id'];
				
				$sql_check = "SELECT google_taxonomy_id
								FROM 
									product_categories
								WHERE 
									category_id = ".$category_id."";
				$ret_check = $db->query($sql_check);	
				if($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$google_taxonomy_id = $row_check['google_taxonomy_id'];
					if($google_taxonomy_id != 0)
					{
						$sql_taxonomy_keyword = "SELECT google_taxonomy_keyword
								FROM 
									google_productcategory_taxonomy
								WHERE 
									google_taxonomy_id = ".$google_taxonomy_id."";
						$ret_taxonomy_keyword = $db->query($sql_taxonomy_keyword);	
						if($db->num_rows($ret_taxonomy_keyword))
						{
							$row_taxonomy_keyword = $db->fetch_array($ret_taxonomy_keyword);
							$add_google_product_category = stripslashes($row_taxonomy_keyword['google_taxonomy_keyword']);
						}

					}
				}
				print $add_google_product_category. "\t";
			}
		
			//link
			//print "http://".$ecom_hostname."/p".$row_prod['product_id']."/" . strip_url_console($row_prod['product_name']) . ".html\t";
			$ret_link = url_product_values($row_prod['product_id'],$row_prod['product_name'],1,$row_var_data['var_value_id'],$row_var_data['var_value']);
			print $ret_link."\t";
			//$pp = sprintf("%01.2f GBP",$curprice);
			$pp = sprintf("%0.2f GBP",$curprice);
			print  $pp."\t";
		
			
			//product type
			$cname = '';
			$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." LIMIT 1";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{
				$row_cat 	= $db->fetch_array($ret_cat);
				$cname 		= $row_cat['category_name'];
				if($cname)
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$cname)) . "\t";
				}
				else
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$row_prod['product_name'])) . "\t";
				}
			}
			
			//quantity
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
				$web_stock = 0;
			print  $web_stock."\t";
			
			//title
			
			if($row_prod['prod_googlefeed_name']!='')
			{
			   $title = $row_prod['prod_googlefeed_name'];
			}
			else
			{
				$title = $row_prod['product_name'];
			}
			$title  = substr($title,0,70);
			//print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title))))). "\n";
			print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title)))));
			
			if($identifier_exists_cnt>=2)
			{
				$identifier_exists = 'TRUE';
			}	
			/*if($ecom_siteid==104 or $ecom_siteid==77) // for DM or shootuk setting it to TRUE always
			{
				$identifier_exists = 'TRUE';
			}*/	
			print "\t".$identifier_exists;
			
			/* Building the shipping module */
			if($include_shipping)
			{
				$ship_str = '';
				/*if($ecom_siteid==77) //shootuk.co.uk
				{
					$ship_str = 'GB::'.utf8_encode('Delivery Charge').':6.50 GBP';
				}
				else*/
				{
					for($sh_i = 0;$sh_i<count($shipping_arr);$sh_i++)
					{
						if($ship_str!='')
							$ship_str .= ',';
						$loc_name = $shipping_arr[$sh_i]['loc'];
						$loc_price = $shipping_arr[$sh_i]['price'] + $row_prod['product_extrashippingcost'];
						
						if ($ecom_siteid==105) // case of puregusto
						{
							$loc_price = $loc_price + ($loc_price * $tax_val/100);
							//$loc_price = sprintf("%01.2f",$loc_price);
							$loc_price = sprintf("%0.2f",$loc_price);
						}
						
						//$ship_str .= '::'.$loc_name.':'.$loc_price;	
						//$ship_str .= 'GB:::'.$loc_price.' GBP';	
						//$ship_str .= 'GB:'.str_replace(':','',$loc_name).'::'.$loc_price.' GBP';
						$new_sr = array(':','&amp;','&',',');
						$new_rp = array('',' and ','and','/');
						//$ship_str .= 'GB:'.str_replace($new_sr,$new_rp,$loc_name).'::'.$loc_price.' GBP';
						$ship_str .= 'GB::'.utf8_encode(trim(str_replace($new_sr,$new_rp,$loc_name))).':'.$loc_price.' GBP';	
					}
				}
				print "\t".str_replace($sr_arr_1,$rp_arr_1,strip_tags($ship_str));
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpag']==1)
			{
				
				$age_group = '';
				if($row_prod['apparel_agegroup']!='')
				{
					$age_group		= trim($row_prod['apparel_agegroup']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($age_group))) ;
				
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpge']==1)
			{
				
				$gender = '';
				if($row_prod['apparel_gender']!='')
				{
					$gender		= trim($row_prod['apparel_gender']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($gender)));
				
			}
			//apparel colour latheesh 
			if($_REQUEST['gpcol']==1)
			{
				
				$colour = '';
				if($row_prod['apparel_color']!='')
				{
					$colour		= trim($row_prod['apparel_color']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($colour)));
				
			}
			//apparel gender latheesh 
			if($_REQUEST['gpsz']==1)
			{
				
				$size = '';
				if($row_prod['apparel_size']!='')
				{
					$size		= trim($row_prod['apparel_size']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($size)));
				
			}
			
			print "\n";
					   
				   }
			   }
			   else
			   {
			   
			// id
			print  $row_prod['product_id']."\t";		
			if($_REQUEST['brn']==1)
			{   
				$identifier_exists_cnt++;
				if($ecom_siteid==88)//skatesrus,co.uk live siteid
				{
				     //brand
					if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
				else
				{
					//brand
					if($row_prod['product_model']) // check whether product model exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_model']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_model']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
			}	
			if($_REQUEST['brc']==1)
			{
				//barcode
				$barcode = '';
				//Check whether there exists variables with values for current product
				$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order 
									LIMIT 
										1";
				$ret_varcheck = $db->query($sql_varcheck);
				if($db->num_rows($ret_varcheck))
				{
					$row_varcheck = $db->fetch_array($ret_varcheck);
					$sql_comb = "SELECT comb_barcode 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = ".$row_prod['product_id']." 
										AND comb_barcode!=''
									ORDER BY 
										comb_id 
									LIMIT 
										1";
					$ret_comb = $db->query($sql_comb);	
					if($db->num_rows($ret_comb))
					{
						$row_comb = $db->fetch_array($ret_comb);
						$barcode = $row_comb['comb_barcode'];
					}				
				}
				else
				{
					$barcode = $row_prod['product_barcode'];
				}	
				if(trim($barcode)!='')
				{
					$identifier_exists_cnt++;
				}						
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($barcode))). "\t";
			}
			if($_REQUEST['mpn']==1)	
			{
			  $mpn = '';
			  //check whether mpn exists for the product main 
			   $sql_mpn_check = "SELECT product_model 
									 FROM 
										products 
									 WHERE 
									 product_id = ".$row_prod['product_id']." 
									 AND 
									 product_model!=''
									 LIMIT 1 ";
			   $ret_mpn_check = $db->query($sql_mpn_check);	
			   if($db->num_rows($ret_mpn_check)>0)
			   {
				     $row_mpn 	= $db->fetch_array($ret_mpn_check);  
				     $mpn 		= $row_mpn['product_model'];  
			   }
			   else
			   {
				     /*$sql_varcheck = "SELECT b.var_mpn 
									FROM 
										product_variables a,product_variable_data b 
									WHERE 
										a.products_product_id = ".$row_prod['product_id']."
										AND
										 b.	product_variables_var_id = a.var_id
										AND 
										a.var_value_exists = 1 
										AND 
										b.var_mpn !=''  
									LIMIT 1 ";
									*/
						$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order ";				 
				    $ret_varcheck = $db->query($sql_varcheck);
					if($db->num_rows($ret_varcheck))
					{
						 while($row_var 	= $db->fetch_array($ret_varcheck))
						 {  
										
						   $sql_varvalcheck = "SELECT var_mpn 
									FROM 
										product_variable_data 
									WHERE 
										product_variables_var_id=".$row_var['var_id']." 
										AND var_mpn!='' 
									ORDER BY 
										var_order ";				 
				             $ret_varvalcheck = $db->query($sql_varvalcheck);
				             if($db->num_rows($ret_varvalcheck))
								{
								$row_mpn 	= $db->fetch_array($ret_varvalcheck);  
								$mpn 		= $row_mpn['var_mpn'];
								}  
						 }
					}
				   
			   }
			   if(trim($mpn)!='')
				{
					$identifier_exists_cnt++;
				}
			   print utf8_encode(str_replace($sr_arr_1,$rp_arr_1,strip_tags($mpn))). "\t";					 
			}		
			//condition
			print utf8_encode("new"). "\t";
						
			//Description
			if(trim($row_prod['google_shopping_desc']))
			{
				$google_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['google_shopping_desc'], $_cleaner_array))) ;
				$googletext = str_replace($sr_arr,$rp_arr,$google_desc);
				$googlesub  = substr($googletext,0,999);
				$googlesub =sentence_case(strtolower($googlesub));
				$googlesub	=  str_replace ( "\t", "", $googlesub );	
				print utf8_encode($googlesub). "\t";
				
			}			
			elseif(trim($row_prod['product_longdesc']))
			{
				$long_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_longdesc'], $_cleaner_array))) ;
				$longtext = str_replace($sr_arr,$rp_arr,$long_desc);
				$longsub  = substr($longtext,0,999);
				$longsub = sentence_case(strtolower($longsub));
				$longsub	=  str_replace ( "\t", "", $longsub );	
				print utf8_encode($longsub). "\t";
				
			}
			elseif (trim($row_prod['product_shortdesc'])) 
			{
				$short_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_shortdesc'], $_cleaner_array))) ;
				$short_desc  = substr($short_desc,0,999);
				$short_desc	=  str_replace ( "\t", "", $short_desc );
				print utf8_encode(str_replace($sr_arr,$rp_arr,$short_desc)). "\t";
			}
			else
			{
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
			}
		
			//Expiration date
			$next_year	= date('Y-m-d',mktime(0,0,0,date('m'),date('d')+28,date('Y')));
			print $next_year ."\t";
			
			//ID
			print utf8_encode($ecom_hostname.":".$row_prod['product_id']) ."\t";
		
		
			//image link
			if(file_exists($big_image_path) and $bigimage_path!='')
			{
				//print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path . "\t";
				$bigimage_path = str_replace('[','%5B',$bigimage_path);
				$bigimage_path = str_replace(']','%5D',$bigimage_path);
				$big_image_url = "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path;
				print $big_image_url . "\t";
			}
			/*else if (file_exists($thumb_image_path) and $thumb_path!='')
			{
				print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $thumb_path . "\t";
			}*/
			
			
			//print_r($bigimage_path_arr);
			 //additional image link
			if($_REQUEST['ail']==1)
			{	
			
				$add_image_urls = "";	
				if($bigimage_path_arr)
				{
					foreach($bigimage_path_arr as $bigimg_path)
					{
						$bigimg_path = trim($bigimg_path);
						$big_img_path 	= "images/".$ecom_hostname."/".$bigimg_path;
						if(file_exists($big_img_path) and $bigimg_path!='')
						{
							if($add_image_urls!='')
								$add_image_urls .= ",";
							$bigimg_path = str_replace('[','%5B',$bigimg_path);
							$bigimg_path = str_replace(']','%5D',$bigimg_path);
							$add_image_urls .= "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimg_path ;
						}
					}
				}
				print $add_image_urls. "\t";
			}
			
			//availability
			if($_REQUEST['avi']==1)
			{
				
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
					$availability = "preorder";
				}
				elseif($web_stock > 0)
				{
					$availability = "in stock";
				}
				else
				{
					if($row_prod['product_alloworder_notinstock'] == "Y")
					{
						//$availability = "available for order";
						if($ecom_siteid==104)
						{
							$availability = "in stock";	
						}
						else
						{
							$availability = "preorder";
						}	
					}
					else
					{
						$availability = "out of stock";
					}
				}
				
					print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($availability))). "\t";
				
			}
			
			//google product category
			if($_REQUEST['gpc']==1)
			{	
				$add_google_product_category = "";	
				
				$category_id = $row_prod['product_default_category_id'];
				
				$sql_check = "SELECT google_taxonomy_id
								FROM 
									product_categories
								WHERE 
									category_id = ".$category_id."";
				$ret_check = $db->query($sql_check);	
				if($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$google_taxonomy_id = $row_check['google_taxonomy_id'];
					if($google_taxonomy_id != 0)
					{
						$sql_taxonomy_keyword = "SELECT google_taxonomy_keyword
								FROM 
									google_productcategory_taxonomy
								WHERE 
									google_taxonomy_id = ".$google_taxonomy_id."";
						$ret_taxonomy_keyword = $db->query($sql_taxonomy_keyword);	
						if($db->num_rows($ret_taxonomy_keyword))
						{
							$row_taxonomy_keyword = $db->fetch_array($ret_taxonomy_keyword);
							$add_google_product_category = stripslashes($row_taxonomy_keyword['google_taxonomy_keyword']);
						}

					}
				}
				print $add_google_product_category. "\t";
			}
		
			//link
			//print "http://".$ecom_hostname."/p".$row_prod['product_id']."/" . strip_url_console($row_prod['product_name']) . ".html\t";
			$ret_link = url_product($row_prod['product_id'],$row_prod['product_name'],1);
			print $ret_link."\t";
			//$pp = sprintf("%01.2f GBP",$curprice);
			$pp = sprintf("%0.2f GBP",$curprice);
			print  $pp."\t";
		
			
			//product type
			$cname = '';
			$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." LIMIT 1";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{
				$row_cat 	= $db->fetch_array($ret_cat);
				$cname 		= $row_cat['category_name'];
				if($cname)
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$cname)) . "\t";
				}
				else
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$row_prod['product_name'])) . "\t";
				}
			}
			
			//quantity
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
				$web_stock = 0;
			print  $web_stock."\t";
			
			//title
			
			if($row_prod['prod_googlefeed_name']!='')
			{
			   $title = $row_prod['prod_googlefeed_name'];
			}
			else
			{
				$title = $row_prod['product_name'];
			}
			$title  = substr($title,0,70);
			//print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title))))). "\n";
			print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title)))));
			
			if($identifier_exists_cnt>=2)
			{
				$identifier_exists = 'TRUE';
			}	
			/*if($ecom_siteid==104 or $ecom_siteid==77) // for DM or shootuk setting it to TRUE always
			{
				$identifier_exists = 'TRUE';
			}*/	
			print "\t".$identifier_exists;
			
			/* Building the shipping module */
			if($include_shipping)
			{
				$ship_str = '';
				/*if($ecom_siteid==77) //shootuk.co.uk
				{
					$ship_str = 'GB::'.utf8_encode('Delivery Charge').':6.50 GBP';
				}
				else*/
				{
					for($sh_i = 0;$sh_i<count($shipping_arr);$sh_i++)
					{
						if($ship_str!='')
							$ship_str .= ',';
						$loc_name = $shipping_arr[$sh_i]['loc'];
						$loc_price = $shipping_arr[$sh_i]['price'] + $row_prod['product_extrashippingcost'];
						
						if ($ecom_siteid==105) // case of puregusto
						{
							$loc_price = $loc_price + ($loc_price * $tax_val/100);
							$loc_price = sprintf("%01.2f",$loc_price);
						}
						
						//$ship_str .= '::'.$loc_name.':'.$loc_price;	
						//$ship_str .= 'GB:::'.$loc_price.' GBP';	
						//$ship_str .= 'GB:'.str_replace(':','',$loc_name).'::'.$loc_price.' GBP';
						$new_sr = array(':','&amp;','&',',');
						$new_rp = array('',' and ','and','/');
						//$ship_str .= 'GB:'.str_replace($new_sr,$new_rp,$loc_name).'::'.$loc_price.' GBP';
						$ship_str .= 'GB::'.utf8_encode(trim(str_replace($new_sr,$new_rp,$loc_name))).':'.$loc_price.' GBP';	
					}
				}
				print "\t".str_replace($sr_arr_1,$rp_arr_1,strip_tags($ship_str));
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpag']==1)
			{
				
				$age_group = '';
				if($row_prod['apparel_agegroup']!='')
				{
					$age_group		= trim($row_prod['apparel_agegroup']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($age_group))) ;
				
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpge']==1)
			{
				
				$gender = '';
				if($row_prod['apparel_gender']!='')
				{
					$gender		= trim($row_prod['apparel_gender']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($gender)));
				
			}
			//apparel colour latheesh 
			if($_REQUEST['gpcol']==1)
			{
				
				$colour = '';
				if($row_prod['apparel_color']!='')
				{
					$colour		= trim($row_prod['apparel_color']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($colour)));
				
			}
			//apparel gender latheesh 
			if($_REQUEST['gpsz']==1)
			{
				
				$size = '';
				if($row_prod['apparel_size']!='')
				{
					$size		= trim($row_prod['apparel_size']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($size)));
				
			}
			
			print "\n";
			   }
			}
			else
			{
			// id
			print  $row_prod['product_id']."\t";		
			if($_REQUEST['brn']==1)
			{   
				$identifier_exists_cnt++;
				if($ecom_siteid==88)//skatesrus,co.uk live siteid
				{
				     //brand
					if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
				else
				{
					//brand
					if($row_prod['product_model']) // check whether product model exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_model']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_model']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else if($row_prod['manufacture_id']) // check whether manufacturer id exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['manufacture_id']))). "\t";
						$brnd = str_replace('[','',$row_prod['manufacture_id']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
					else // case if mode or manufacturer id does not exists
					{
						//print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
						$brnd = str_replace('[','',$row_prod['product_name']);
						$brnd = str_replace(']','',$brnd);
						print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($brnd))). "\t";
					}
				}
			}	
			if($_REQUEST['brc']==1)
			{
				//barcode
				$barcode = '';
				//Check whether there exists variables with values for current product
				$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order 
									LIMIT 
										1";
				$ret_varcheck = $db->query($sql_varcheck);
				if($db->num_rows($ret_varcheck))
				{
					$row_varcheck = $db->fetch_array($ret_varcheck);
					$sql_comb = "SELECT comb_barcode 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = ".$row_prod['product_id']." 
										AND comb_barcode!=''
									ORDER BY 
										comb_id 
									LIMIT 
										1";
					$ret_comb = $db->query($sql_comb);	
					if($db->num_rows($ret_comb))
					{
						$row_comb = $db->fetch_array($ret_comb);
						$barcode = $row_comb['comb_barcode'];
					}				
				}
				else
				{
					$barcode = $row_prod['product_barcode'];
				}	
				if(trim($barcode)!='')
				{
					$identifier_exists_cnt++;
				}						
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($barcode))). "\t";
			}
			if($_REQUEST['mpn']==1)	
			{
			  $mpn = '';
			  //check whether mpn exists for the product main 
			   $sql_mpn_check = "SELECT product_model 
									 FROM 
										products 
									 WHERE 
									 product_id = ".$row_prod['product_id']." 
									 AND 
									 product_model!=''
									 LIMIT 1 ";
			   $ret_mpn_check = $db->query($sql_mpn_check);	
			   if($db->num_rows($ret_mpn_check)>0)
			   {
				     $row_mpn 	= $db->fetch_array($ret_mpn_check);  
				     $mpn 		= $row_mpn['product_model'];  
			   }
			   else
			   {
				     /*$sql_varcheck = "SELECT b.var_mpn 
									FROM 
										product_variables a,product_variable_data b 
									WHERE 
										a.products_product_id = ".$row_prod['product_id']."
										AND
										 b.	product_variables_var_id = a.var_id
										AND 
										a.var_value_exists = 1 
										AND 
										b.var_mpn !=''  
									LIMIT 1 ";
									*/
						$sql_varcheck = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id=".$row_prod['product_id']." 
										AND var_value_exists=1 
									ORDER BY 
										var_order ";				 
				    $ret_varcheck = $db->query($sql_varcheck);
					if($db->num_rows($ret_varcheck))
					{
						 while($row_var 	= $db->fetch_array($ret_varcheck))
						 {  
										
						   $sql_varvalcheck = "SELECT var_mpn 
									FROM 
										product_variable_data 
									WHERE 
										product_variables_var_id=".$row_var['var_id']." 
										AND var_mpn!='' 
									ORDER BY 
										var_order ";				 
				             $ret_varvalcheck = $db->query($sql_varvalcheck);
				             if($db->num_rows($ret_varvalcheck))
								{
								$row_mpn 	= $db->fetch_array($ret_varvalcheck);  
								$mpn 		= $row_mpn['var_mpn'];
								}  
						 }
					}
				   
			   }
			   if(trim($mpn)!='')
				{
					$identifier_exists_cnt++;
				}
			   print utf8_encode(str_replace($sr_arr_1,$rp_arr_1,strip_tags($mpn))). "\t";					 
			}		
			//condition
			print utf8_encode("new"). "\t";
						
			//Description
			if(trim($row_prod['google_shopping_desc']))
			{
				$google_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['google_shopping_desc'], $_cleaner_array))) ;
				$googletext = str_replace($sr_arr,$rp_arr,$google_desc);
				$googlesub  = substr($googletext,0,999);
				$googlesub =sentence_case(strtolower($googlesub));
				
				$curprice_withouttax = sprintf("%01.2f GBP",$curprice_withouttax);
				$googlesub = str_replace('[exprice]',$curprice_withouttax,$googlesub);
				
				$googlesub	=  str_replace ( "\t", "", $googlesub );	
				print utf8_encode($googlesub). "\t";
				
			}			
			elseif(trim($row_prod['product_longdesc']))
			{
				$long_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_longdesc'], $_cleaner_array))) ;
				$longtext = str_replace($sr_arr,$rp_arr,$long_desc);
				$longsub  = substr($longtext,0,999);
				$longsub = sentence_case(strtolower($longsub));
				$longsub	=  str_replace ( "\t", "", $longsub );	
				print utf8_encode($longsub). "\t";
				
			}
			elseif (trim($row_prod['product_shortdesc'])) 
			{
				$short_desc = preg_replace($_strip_search, $_strip_replace, strip_tags(strtr($row_prod['product_shortdesc'], $_cleaner_array))) ;
				$short_desc  = substr($short_desc,0,999);
				$short_desc	=  str_replace ( "\t", "", $short_desc );
				print utf8_encode(str_replace($sr_arr,$rp_arr,$short_desc)). "\t";
			}
			else
			{
				print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($row_prod['product_name']))). "\t";
			}
		
			//Expiration date
			$next_year	= date('Y-m-d',mktime(0,0,0,date('m'),date('d')+28,date('Y')));
			print $next_year ."\t";
			
			//ID
			print utf8_encode($ecom_hostname.":".$row_prod['product_id']) ."\t";
		
		
			//image link
			if(file_exists($big_image_path) and $bigimage_path!='')
			{
				//print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path . "\t";
				$bigimage_path = str_replace('[','%5B',$bigimage_path);
				$bigimage_path = str_replace(']','%5D',$bigimage_path);
				$big_image_url = "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimage_path;
				print $big_image_url . "\t";
			}
			/*else if (file_exists($thumb_image_path) and $thumb_path!='')
			{
				print "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $thumb_path . "\t";
			}*/
			
			
			//print_r($bigimage_path_arr);
			 //additional image link
			if($_REQUEST['ail']==1)
			{	
			
				$add_image_urls = "";	
				if($bigimage_path_arr)
				{
					foreach($bigimage_path_arr as $bigimg_path)
					{
						$bigimg_path = trim($bigimg_path);
						$big_img_path 	= "images/".$ecom_hostname."/".$bigimg_path;
						if(file_exists($big_img_path) and $bigimg_path!='')
						{
							if($add_image_urls!='')
								$add_image_urls .= ",";
							$bigimg_path = str_replace('[','%5B',$bigimg_path);
							$bigimg_path = str_replace(']','%5D',$bigimg_path);
							$add_image_urls .= "http://".$ecom_hostname."/images/".$ecom_hostname."/" . $bigimg_path ;
						}
					}
				}
				print $add_image_urls. "\t";
			}
			
			//availability
			if($_REQUEST['avi']==1)
			{
				
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
					$availability = "preorder";
				}
				elseif($web_stock > 0)
				{
					$availability = "in stock";
				}
				else
				{
					if($row_prod['product_alloworder_notinstock'] == "Y")
					{
						//$availability = "available for order";
						if($ecom_siteid==104)
						{
							$availability = "in stock";	
						}
						else
						{
							$availability = "preorder";
						}	
					}
					else
					{
						$availability = "out of stock";
					}
				}
				
					print utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($availability))). "\t";
				
			}
			
			//google product category
			if($_REQUEST['gpc']==1)
			{	
				$add_google_product_category = "";	
				
				$category_id = $row_prod['product_default_category_id'];
				
				$sql_check = "SELECT google_taxonomy_id
								FROM 
									product_categories
								WHERE 
									category_id = ".$category_id."";
				$ret_check = $db->query($sql_check);	
				if($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$google_taxonomy_id = $row_check['google_taxonomy_id'];
					if($google_taxonomy_id != 0)
					{
						$sql_taxonomy_keyword = "SELECT google_taxonomy_keyword
								FROM 
									google_productcategory_taxonomy
								WHERE 
									google_taxonomy_id = ".$google_taxonomy_id."";
						$ret_taxonomy_keyword = $db->query($sql_taxonomy_keyword);	
						if($db->num_rows($ret_taxonomy_keyword))
						{
							$row_taxonomy_keyword = $db->fetch_array($ret_taxonomy_keyword);
							$add_google_product_category = stripslashes($row_taxonomy_keyword['google_taxonomy_keyword']);
						}

					}
				}
				print $add_google_product_category. "\t";
			}
		
			//link
			//print "http://".$ecom_hostname."/p".$row_prod['product_id']."/" . strip_url_console($row_prod['product_name']) . ".html\t";
			$ret_link = url_product($row_prod['product_id'],$row_prod['product_name'],1);
			print $ret_link."\t";
			//$pp = sprintf("%01.2f GBP",$curprice);
			$pp = sprintf("%0.2f GBP",$curprice);
			print  $pp."\t";
		
			
			//product type
			$cname = '';
			$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." LIMIT 1";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{
				$row_cat 	= $db->fetch_array($ret_cat);
				$cname 		= $row_cat['category_name'];
				if($cname)
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$cname)) . "\t";
				}
				else
				{
					print utf8_encode(str_replace($sr_arr,$rp_arr,$row_prod['product_name'])) . "\t";
				}
			}
			
			//quantity
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
				$web_stock = 0;
			print  $web_stock."\t";
			
			//title
			
			if($row_prod['prod_googlefeed_name']!='')
			{
			   $title = $row_prod['prod_googlefeed_name'];
			}
			else
			{
				$title = $row_prod['product_name'];
			}
			$title  = substr($title,0,70);
			//print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title))))). "\n";
			print utf8_encode(ucwords(strtolower(str_replace($sr_arr,$rp_arr,strip_tags($title)))));
			
			if($identifier_exists_cnt>=2)
			{
				$identifier_exists = 'TRUE';
			}	
			/*if($ecom_siteid==104 or $ecom_siteid==77) // for DM or shootuk setting it to TRUE always
			{
				$identifier_exists = 'TRUE';
			}*/	
			print "\t".$identifier_exists;
			
			/* Building the shipping module */
			if($include_shipping)
			{
				$ship_str = '';
				/*if($ecom_siteid==77) //shootuk.co.uk
				{
					$ship_str = 'GB::'.utf8_encode('Delivery Charge').':6.50 GBP';
				}
				else*/
				{
					for($sh_i = 0;$sh_i<count($shipping_arr);$sh_i++)
					{
						if($ship_str!='')
							$ship_str .= ',';
						$loc_name = $shipping_arr[$sh_i]['loc'];
						$loc_price = $shipping_arr[$sh_i]['price'] + $row_prod['product_extrashippingcost'];
						
						if ($ecom_siteid==105) // case of puregusto
						{
							$loc_price = $loc_price + ($loc_price * $tax_val/100);
							$loc_price = sprintf("%01.2f",$loc_price);
						}
						
						//$ship_str .= '::'.$loc_name.':'.$loc_price;	
						//$ship_str .= 'GB:::'.$loc_price.' GBP';	
						//$ship_str .= 'GB:'.str_replace(':','',$loc_name).'::'.$loc_price.' GBP';
						$new_sr = array(':','&amp;','&',',');
						$new_rp = array('',' and ','and','/');
						//$ship_str .= 'GB:'.str_replace($new_sr,$new_rp,$loc_name).'::'.$loc_price.' GBP';
						$ship_str .= 'GB::'.utf8_encode(trim(str_replace($new_sr,$new_rp,$loc_name))).':'.$loc_price.' GBP';	
					}
				}
				print "\t".str_replace($sr_arr_1,$rp_arr_1,strip_tags($ship_str));
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpag']==1)
			{
				
				$age_group = '';
				if($row_prod['apparel_agegroup']!='')
				{
					$age_group		= trim($row_prod['apparel_agegroup']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($age_group))) ;
				
			}
			//apparel agegorup latheesh 
			if($_REQUEST['gpge']==1)
			{
				
				$gender = '';
				if($row_prod['apparel_gender']!='')
				{
					$gender		= trim($row_prod['apparel_gender']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($gender)));
				
			}
			//apparel colour latheesh 
			if($_REQUEST['gpcol']==1)
			{
				
				$colour = '';
				if($row_prod['apparel_color']!='')
				{
					$colour		= trim($row_prod['apparel_color']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($colour)));
				
			}
			//apparel gender latheesh 
			if($_REQUEST['gpsz']==1)
			{
				
				$size = '';
				if($row_prod['apparel_size']!='')
				{
					$size		= trim($row_prod['apparel_size']);
				}
				
				
			    print "\t".utf8_encode(str_replace($sr_arr,$rp_arr,strip_tags($size)));
				
			}
			
			print "\n";
	  
				}
			}
		}
	  }	 
	}//end while 
	$db->db_close();
	function generate_price($prod_arr,$row_price,$row_settings,$tax_val,$return_price_withouttax=0)
	{
		// Function to generate the price should be specified here
		global $db,$ecom_siteid,$ecom_allpricewithtax;
		
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
		//if($ecom_siteid==70 or $ecom_siteid==104) // bypassed tax calculation for nationwide fireextinguisher and discount mobility
		
		if($ecom_siteid==104) // bypassed tax calculation for discount mobility and puregusto
			$apply_tax = 'Y';
		else	
			$apply_tax		= $prod_arr['product_applytax'];
			
		
		if($return_price_withouttax==1)
		{
			$apply_tax = 'N';
		}
			
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
					$disc_price	= $webprice + ($webprice * $tax_val/100);
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
	
	function generate_price_refined($prod_arr,$row_price,$row_settings,$tax_val,$return_price_withouttax=0)
	{
		// Function to generate the price should be specified here
		global $db,$ecom_siteid,$ecom_allpricewithtax;
		
		$webprice 			= $prod_arr['product_webprice'];
		$disc_asval			= $prod_arr['product_discount_enteredasval'];
		$tax_before_disc	= $row_settings['saletax_before_discount'];
		$discount			= 0;
		$additional_price = 0;
		if($prod_arr['cur_var_value_id'])
		{
			$additional_price = calculat_special_price_new($prod_arr);
		
			$webprice = $webprice + $additional_price; // done to add the additional price for variable for special cases
		}	
		
		if($prod_arr['product_discount']>0)
		{
			if($disc_asval==2)  // For Exact Discount Price 
				$discount	= $webprice-$prod_arr['product_discount']; 	
			else
				$discount	= $prod_arr['product_discount'];
		}
		//if($ecom_siteid==70 or $ecom_siteid==104) // bypassed tax calculation for nationwide fireextinguisher and discount mobility
		
		if($ecom_siteid==104) // bypassed tax calculation for discount mobility and puregusto
			$apply_tax = 'Y';
		else	
			$apply_tax		= $prod_arr['product_applytax'];
			
		
		if($return_price_withouttax==1)
		{
			$apply_tax = 'N';
		}
		
					
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
					$disc_price	= $webprice + ($webprice * $tax_val/100);
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
						//$disc_price = $discount;
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
	function calculat_special_price_new($prod_arr)
	{
		global $db,$ecom_siteid;
		$add_price = 0;
		// Check whether variable value id is passed with the product details
		if($prod_arr['cur_var_value_id'])
		{
			if($prod_arr['cur_var_value_id']>0)
			{
				// Check whether additional price is set for this variable value
				$sql_newvarval = "SELECT var_addprice FROM product_variable_data WHERE var_value_id = ".$prod_arr['cur_var_value_id']." LIMIT 1";
				$ret_newvarval = $db->query($sql_newvarval);
				if($db->num_rows($ret_newvarval))
				{
					$row_newvarval = $db->fetch_array($ret_newvarval);
					$add_price = $row_newvarval['var_addprice'];
				}
			}
		}
		return $add_price;
	}
?>
