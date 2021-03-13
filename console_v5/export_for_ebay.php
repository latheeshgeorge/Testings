<? 
	set_time_limit(0);
	require_once("sites.php");
	require_once("config.php");
	
	// Setting up the things to be replaced
	$_strip_search 	= array("![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
							'%[\r\n]+%m'); // remove CRs and newlines
	$_strip_replace = array('','');
	$_cleaner_array = array(">" => "> ", "&reg;" => "", "�" => "", "&trade;" => "", "�" => "", "\n" => "", "\t" => "    ","&nbsp;"=>"  ","&amp;"=>"&");
	$sr_arr 		= array("&","<",">","/","'",'"');
	$rp_arr 		= array("&amp;","&lt;","&gt;","","","");
	$sr_arr_1 		= array("&","<",">","'",'"');
	$rp_arr_1 		= array("&amp;","&lt;","&gt;","","");
	// Getting the details of all products in current site 
	$sql_prod 		= "SELECT product_id,product_name,product_shortdesc,product_actualstock,product_default_category_id,
						  	product_webprice, product_discount_enteredasval, product_applytax, product_variablestock_allowed,
							product_discount 
						FROM 
						  	products 
						WHERE 
							sites_site_id = '$ecom_siteid' 
							AND  product_actualstock > 0 
							AND product_hide='N' 
							AND product_exclude_from_feed = 'N' 
						ORDER BY 
							product_name";
	$ret_prod		= $db->query($sql_prod);
	$headers        = array('Site','Format','Currency','Title','SubtitleText','Note','Description','Category 1*','Category 2','Shop Category','PicURL','Quantity','Duration',
							'Starting Price','Reserve Price','BIN Price','Private Auction','Counter',' Payment Instructions','Specifying Shipping Costs','Insurance Option',
							'Insurance Amount','Sales Tax Amount','Sales Tax State','Apply tax to total','Accept PayPal','PayPal Email Address','Accept MO Cashiers','Accept Personal Check',
							'Accept Visa/Mastercard','Accept AmEx','Accept Discover','Accept COD','Payment See Description','Accept Money Xfer','MoneyXferAccepted inCheckout','Ship-To Option',
							'Location -City/State','Location - Region','Location - Country','Gallery','Gallery Plus','Gallery URL','PicInDesc','Bold','Highlight','Featured Plus','Home Page Featured',
							'ShippingType','ShippingPackage','ShippingIrregular','ShippingWeightUnit','WeightMajor','WeightMinor','Package Length','Package Width','Package Depth','ShipFromZipCode','PackagingHandling Costs',
							'ThemeId','LayoutId','Apply Multi-item Shipping Discount','Attributes','ShippingServiceOptions+','SMPFolderName','SMPUnitCost','SMPPartNumber','SMPQuantity');
		$site     = $_REQUEST['cbo_site'];
		$city     = $_REQUEST['txt_city'];
		$country  = $_REQUEST['cbo_country'];
		$duration = $_REQUEST['cbo_duration'];
		$counter  = $_REQUEST['cbo_counter'];
	   
    $data = array();
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
	while($row_prod = $db->fetch_array($ret_prod))
	{	$cnt= 0;  
		$curprice 	= generate_price($row_prod,$row_price,$row_settings,$tax_val);	
			if($row_prod['product_default_category_id']>0)
			{
				$sql_cat = "SELECT ebay_category_id FROM product_categories WHERE category_id = ".$row_prod['product_default_category_id']." AND sites_site_id = '$ecom_siteid'";
				$ret_cat = $db->query($sql_cat);
				$row_cat = $db->fetch_array($ret_cat);
				if($row_cat['ebay_category_id']>0)
				{
				$temp         = array(); 
				$temp[0]   	  = $site;
				$temp[1] 	  = 7; 
				$temp[2]	  = 3; 
				$temp[3] 		=  strip_tags($row_prod['product_name']);
				$temp[4] 		= '';
				$temp[5]        = '';
				$temp[6]  		= strip_tags($row_prod['product_shortdesc']);
				$temp[7]    	= $row_cat['ebay_category_id'];
				$temp[8]    	= '';
				$temp[9]   		= 0;
				$url 			= get_imageurl($row_prod['product_id']);
				$temp[10]       = $url;
				$temp[11]       = $row_prod['product_actualstock'];
				$temp[12] = $duration;
				$temp[13] = $curprice;
				$temp[14] = '';
				$temp[15] = '';
				$temp[16] = 0;
				$temp[17] = $counter;
				$temp[18] = '';
				$temp[19] = 0;
				$temp[20] = 0;
				$temp[21] = 0;
				$temp[22] = 0;
				$temp[23] = 0;
				$temp[24] = 0;
				$temp[25] = 0 ;
				$temp[26] ='';
				$temp[27] = 0;
				$temp[28] = 0;
				$temp[29] = 0;
				$temp[30] = 0;
				$temp[31] = 0;
				$temp[32] = 0;
				$temp[33] = 0;
				$temp[34] = 0;
				$temp[35] = 0;
				$temp[36] = 'SiteOnly';
				$temp[37] = $city;
				$temp[38]  ='';
				$temp[39]  = $country;
				$temp[40]  = 0;
				$temp[41]  = 0;
				$temp[42]  = 0;
				$temp[43]  = 0;
				$temp[44]  =  0;
				$temp[45]  =  0;
				$temp[46]  =  0;
				$temp[47]  =  0;
				$temp[48]  =  0;
				$temp[49]  =  0;
				$temp[50]  =  0;
				$temp[51]  =  0;
				$temp[52]  =  0;
				$temp[53]  =  0;
				$temp[54]  =  0;
				$temp[55]  =  0;
				$temp[56]  =  0;
				$temp[57]  =  0;
				$temp[58]  =  0;
				$temp[59]  =  0;
				$temp[60]  =  '';
				$temp[61]  =  '';
				$temp[62]  =  0;				
				$temp[63]  =  '';
				$temp[64]  =  '';
				$temp[65]  =  '';
				$temp[66]  =  0;				
				$temp[67]  =  ''; 
				
				  array_push($data,$temp);
			    }
			 } 

	}	
	
	$download_name 	= str_replace(" ","_",trim($ecom_title));
	header("Content-Type: text/plain");
	header("Content-Disposition: attachment; filename=$download_name.csv");
	array_walk($headers, "add_quotes");
	print implode(",", $headers) . "\r\n";
	foreach($data as $v)
	{
    	array_walk($v,"add_quotes");
		//$v = '"'.$v.'"';
		print implode(",", $v) . "\r\n";
    }
    function add_quotes(&$d)
	{
		$d = '"' . str_replace('"', '""', stripslashes($d)) . '"';

	}
function get_imageurl($prod_id)
{
  global $db,$ecom_siteid,$ecom_hostname;
  $sql_img = "SELECT a.image_extralargepath FROM images a,images_product b WHERE b.products_product_id = '$prod_id' AND b.images_image_id=a.image_id AND sites_site_id='$ecom_siteid'";
  $ret_img = $db->query($sql_img);
  $row_img = $db->fetch_array($ret_img);
  if($row_img['image_extralargepath']!='')
  {
  
  $path = $row_img['image_extralargepath'];
  $url_build = "http://$ecom_hostname/images/$ecom_hostname/$path";
  return $url_build;
 }
}
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
	
	function generate_price($prod_arr,$row_price,$row_settings,$tax_val)
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
		if($ecom_siteid==70) // bypassed tax calculation for nationwide fireextinguisher
			$apply_tax = 'N';
		else
			$apply_tax		= $prod_arr['product_applytax'];
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
?>
