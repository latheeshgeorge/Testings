<?php
/*#################################################################
	# Script Name 		: do_stocktab_offline.php
	# Description 		: product details to be used in the database offline export and upload
	# Coded by 			: Joby
	# Created on		: 21-Sept-2011
	# Modified by 			: Joby
	# Modified on		: 24-Oct-2011
	#################################################################*/
	set_time_limit(0);
	if ($_REQUEST['cur_mod']=='')
	{
		echo '<script type="text/javascript">alert("Invalid Parameter");</script>';
		exit;
	}
	include_once ("functions/functions.php");
	include( 'session.php');
	require_once ("sites.php");
	require_once ("config.php");

	/*Including excel convertion class - starts*/
	$root_path = $_SERVER['DOCUMENT_ROOT'].'/console_v5/';
	// Include PEAR::Spreadsheet_Excel_Writer
	require_once "Spreadsheet/Excel/Writer.php";
	$xls =& new Spreadsheet_Excel_Writer();
	/*Including excel convertion class - ends*/


	$headers 	= array();
	$data 		= array();
	$exists 	= false;
	if (!$_SESSION['console_id']) // checking to see if the user is logged in and is the user of current site
	{
		echo '<script type="text/javascript">alert("User not logged in. Please login..");</script>';
		exit;
	}
	else
	{
		// Check whether current user exists in current site
		$sql_site = "SELECT user_id 
						FROM 
							sites_users_7584 
						WHERE 
							sites_site_id IN(0,$ecom_siteid ) 
							AND user_id = '".$_SESSION['console_id']."' 
						LIMIT 
							1";
		$ret_site = $db->query($sql_site);
		if ($db->num_rows($ret_site)==0)
		{
			echo '<script type="text/javascript">alert("Sorry!! Invalid User");</script>';
			exit;
		}
	}
	$special_product_code_req = false;
	if(	$ecom_siteid==61) // garraways
		$special_product_code_req = true;
		
		// Array to be used for advanced offline feature
		
		if($special_product_code_req) // for garraways website
		{
			$var_prodcombstockpriceimage_arr= array	(
													'product_name'								=> 'Product Name (Don\'t Modify)',
													'comb_name'									=> 'Variable Combination (Don\'t Modify)',
													'product_barcode'							=> 'Barcode',
													'product_special_product_code'				=> 'Special Product Code',
													'product_varstock'							=> 'Stock',
													'product_varprice'							=> 'Price',
													'product_discount_enteredasval'				=> 'Discount Type(Format %, Value, Exact)',
													'product_discount'							=> 'Discount',
													'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
													'product_bulkdiscount_allowed'              => 'Allow Bulk Discount ?',													
													'product_bulkdiscount_qty1'					=> 'Bulk Discount Qty (1)',
													'product_bulkdiscount_price1'				=> 'Bulk Discount Price (1)',
													'product_bulkdiscount_qty2'					=> 'Bulk Discount Qty (2)',
													'product_bulkdiscount_price2'				=> 'Bulk Discount Price (2)',
													'product_bulkdiscount_qty3'					=> 'Bulk Discount Qty (3)',
													'product_bulkdiscount_price3'				=> 'Bulk Discount Price (3)',
													'product_bulkdiscount_qty4'					=> 'Bulk Discount Qty (4)',
													'product_bulkdiscount_price4'				=> 'Bulk Discount Price (4)',
													'product_bulkdiscount_qty5'					=> 'Bulk Discount Qty (5)',
													'product_bulkdiscount_price5'				=> 'Bulk Discount Price (5)',
													'product_bulkdiscount_qty6'					=> 'Bulk Discount Qty (6)',
													'product_bulkdiscount_price6'				=> 'Bulk Discount Price (6)',
													'product_bulkdiscount_qty7'					=> 'Bulk Discount Qty (7)',
													'product_bulkdiscount_price7'				=> 'Bulk Discount Price (7)',
													'product_bulkdiscount_qty8'					=> 'Bulk Discount Qty (8)',
													'product_bulkdiscount_price8'				=> 'Bulk Discount Price (8)',
													'product_bulkdiscount_qty9'					=> 'Bulk Discount Qty (9)',
													'product_bulkdiscount_price9'				=> 'Bulk Discount Price (9)',
													'product_bulkdiscount_qty10'				=> 'Bulk Discount Qty (10)',
													'product_bulkdiscount_price10'				=> 'Bulk Discount Price (10)',
													'product_bulkdiscount_qty11'				=> 'Bulk Discount Qty (11)',
													'product_bulkdiscount_price11'				=> 'Bulk Discount Price (11)',
													'product_bulkdiscount_qty12'				=> 'Bulk Discount Qty (12)',
													'product_bulkdiscount_price12'				=> 'Bulk Discount Price (12)',
													'product_bulkdiscount_qty13'				=> 'Bulk Discount Qty (13)',
													'product_bulkdiscount_price13'				=> 'Bulk Discount Price (13)',
													'product_bulkdiscount_qty14'				=> 'Bulk Discount Qty (14)',
													'product_bulkdiscount_price14'				=> 'Bulk Discount Price (14)',
													'product_bulkdiscount_qty15'				=> 'Bulk Discount Qty (15)',
													'product_bulkdiscount_price15'				=> 'Bulk Discount Price (15)',
													'product_id'								=> 'Product Id (Don\'t Modify)',
													'comb_id'									=> 'Combination Id (Don\'t Modify)',
													'product_variablestock_allowed'				=> 'Variable Stock (Don\'t Modify)',
													'product_variablecomboprice_allowed'		=> 'Variable Price (Don\'t Modify)',
													'product_variablecombocommon_image_allowed'	=> 'Variable Image (Don\'t Modify)',
													'var_id_var_value_id'						=> 'Var Id => Var Value Id (Don\'t Modify)'
												);
		}
		else
		{
				$var_prodcombstockpriceimage_arr= array	(
													'product_name'								=> 'Product Name (Don\'t Modify)',
													'comb_name'									=> 'Variable Combination (Don\'t Modify)',
													'product_barcode'							=> 'Barcode',
													'product_varstock'							=> 'Stock',
													'product_varprice'							=> 'Price',
													'product_discount_enteredasval'				=> 'Discount Type(Format %, Value, Exact)',
													'product_discount'							=> 'Discount',
													'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
													'product_bulkdiscount_allowed'              => 'Allow Bulk Discount ?',													

													'product_bulkdiscount_qty1'					=> 'Bulk Discount Qty (1)',
													'product_bulkdiscount_price1'				=> 'Bulk Discount Price (1)',
													'product_bulkdiscount_qty2'					=> 'Bulk Discount Qty (2)',
													'product_bulkdiscount_price2'				=> 'Bulk Discount Price (2)',
													'product_bulkdiscount_qty3'					=> 'Bulk Discount Qty (3)',
													'product_bulkdiscount_price3'				=> 'Bulk Discount Price (3)',
													'product_bulkdiscount_qty4'					=> 'Bulk Discount Qty (4)',
													'product_bulkdiscount_price4'				=> 'Bulk Discount Price (4)',
													'product_bulkdiscount_qty5'					=> 'Bulk Discount Qty (5)',
													'product_bulkdiscount_price5'				=> 'Bulk Discount Price (5)',
													'product_bulkdiscount_qty6'					=> 'Bulk Discount Qty (6)',
													'product_bulkdiscount_price6'				=> 'Bulk Discount Price (6)',
													'product_bulkdiscount_qty7'					=> 'Bulk Discount Qty (7)',
													'product_bulkdiscount_price7'				=> 'Bulk Discount Price (7)',
													'product_bulkdiscount_qty8'					=> 'Bulk Discount Qty (8)',
													'product_bulkdiscount_price8'				=> 'Bulk Discount Price (8)',
													'product_bulkdiscount_qty9'					=> 'Bulk Discount Qty (9)',
													'product_bulkdiscount_price9'				=> 'Bulk Discount Price (9)',
													'product_bulkdiscount_qty10'				=> 'Bulk Discount Qty (10)',
													'product_bulkdiscount_price10'				=> 'Bulk Discount Price (10)',
													'product_bulkdiscount_qty11'				=> 'Bulk Discount Qty (11)',
													'product_bulkdiscount_price11'				=> 'Bulk Discount Price (11)',
													'product_bulkdiscount_qty12'				=> 'Bulk Discount Qty (12)',
													'product_bulkdiscount_price12'				=> 'Bulk Discount Price (12)',
													'product_bulkdiscount_qty13'				=> 'Bulk Discount Qty (13)',
													'product_bulkdiscount_price13'				=> 'Bulk Discount Price (13)',
													'product_bulkdiscount_qty14'				=> 'Bulk Discount Qty (14)',
													'product_bulkdiscount_price14'				=> 'Bulk Discount Price (14)',
													'product_bulkdiscount_qty15'				=> 'Bulk Discount Qty (15)',
													'product_bulkdiscount_price15'				=> 'Bulk Discount Price (15)',
													'product_id'								=> 'Product Id (Don\'t Modify)',
													'comb_id'									=> 'Combination Id (Don\'t Modify)',
													'product_variablestock_allowed'				=> 'Variable Stock (Don\'t Modify)',
													'product_variablecomboprice_allowed'		=> 'Variable Price (Don\'t Modify)',
													'product_variablecombocommon_image_allowed'	=> 'Variable Image (Don\'t Modify)',
													'var_id_var_value_id'						=> 'Var Id => Var Value Id (Don\'t Modify)'
												);
	}											
	
	
	$disc_type = array(0=>'%',1=>'Value',2=>'Exact');
	if($_REQUEST['cur_mod']=='stock_download') // case of download
	{
		$filename = $ecom_hostname.'Product_Advanced_Offline_Productdetails_'.date('d-m-Y');		
		
		/*header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=$filename.csv");*/

		// Create an instance for excel class 
		$xls->send("$filename.xls");
		// Add a worksheet to the file, returning an object to add data to 
		$worksheet =& $xls->addWorksheet('Binary Count');  
		
		
		$head_arr = $var_prodcombstockpriceimage_arr;	
		/*array_walk($head_arr, "add_quotes");
		print implode(",", $head_arr) . "\r\n";*/

		$row_count = 0;
		$cell_count = 0;
		foreach($head_arr as $cell_data)
		{
		$worksheet->writeString($row_count, $cell_count, "$cell_data");
		$cell_count++;
		}

			
		$sel_cats	= $_REQUEST['sel_category_id'];
		
		// Get the list of ids of product under selected categories
		$prodids_arr = array(-1);
		$sql_prodids = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(",",$sel_cats).")" ;
		$ret_prodids = $db->query($sql_prodids);
		if($db->num_rows($ret_prodids))
		{
			$row_count = 1;	
			while ($row_prodids = $db->fetch_array($ret_prodids))
			{
				$prodids_arr[] = $row_prodids['products_product_id'];
				$cur_prodid = $row_prodids['products_product_id'];
				$data = array();
				$sql_var_val_exists_count = "SELECT COUNT(*) FROM product_variables WHERE var_value_exists=1 AND products_product_id=$cur_prodid";
				$ret_var_val_exists_count = $db->query($sql_var_val_exists_count);
				$row_var_val_exists_count = $db->fetch_array($ret_var_val_exists_count);
				$var_val_exists_count     = $row_var_val_exists_count[0];
				
				$additional_condition = '';
				// get the main product details from products
				$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_special_product_code,product_variablestock_allowed,
									product_variablecomboprice_allowed, product_variablecombocommon_image_allowed, product_bulkdiscount_allowed,
									product_discount,product_discount_enteredasval 
								FROM 
									products
								WHERE 
									sites_site_id=$ecom_siteid 
									AND product_id = $cur_prodid 
								LIMIT 
									1";	
				$ret_stock = $db->query($sql_stock);				
				if($db->num_rows($ret_stock))
				{
					$row_stock = $db->fetch_array($ret_stock);
					$var_stock = $var_price = $var_img = false;
					$var_stock_variables = 0;
					if($row_stock['product_variablestock_allowed']=='Y')
					{
						$var_stock = true;
						$var_stock_variables = 1;
					}
					if($row_stock['product_variablecomboprice_allowed']=='Y')
					{
						$var_price = true;	
						$var_stock_variables = 1;
					}
					if($row_stock['product_variablecombocommon_image_allowed']=='Y')
					{
						$var_img = true;
						$var_stock_variables = 1;
					}
					
					$data['product_variablestock_allowed']  = $row_stock['product_variablestock_allowed'];
					$data['product_variablecomboprice_allowed'] = $row_stock['product_variablecomboprice_allowed'];
					$data['product_variablecombocommon_image_allowed'] = $row_stock['product_variablecombocommon_image_allowed'];
					
					//---------------------------- Printing First Row for Product -- starts -------------------------------------
					$data['product_name'] 		= stripslashes($row_stock['product_name']);
					$data['comb_name'] 			= '';
					$data['comb_id'] 			= '';	
					$data['product_id'] 		= 'P-'.$cur_prodid;
					$data['var_id_var_value_id']= '';	
					$data['product_discount_enteredasval'] 	= $disc_type[$row_stock['product_discount_enteredasval']];
					$data['product_discount'] 				= $row_stock['product_discount'];
					// Handling bulk discount allowed for the product
					$data['product_bulkdiscount_allowed']   = $row_stock['product_bulkdiscount_allowed'];
					// check that  all check boxes are unticked
					if($var_stock_variables == 0) 
					{
						$data['product_varstock'] 				= $row_stock['product_webstock'];
						$data['product_varprice'] 				= $row_stock['product_webprice'];
						
						$bulk_arr = array();
						if($row_stock['product_bulkdiscount_allowed']=='Y')
						{
							// get the direct bulk discount details for current product
							$sql_bulk = "SELECT bulk_qty, bulk_price 
											FROM 
												product_bulkdiscount 
											WHERE 
												products_product_id = '".$cur_prodid."'  
												AND comb_id = 0 
											ORDER BY 
												bulk_qty";
							$ret_bulk = $db->query($sql_bulk);
							if($db->num_rows($ret_bulk))
							{
								while ($row_bulk = $db->fetch_array($ret_bulk))
								{
									$bulk_arr[] = $row_bulk['bulk_qty'].'=>'.$row_bulk['bulk_price'];
								}
								if(count($bulk_arr))
									$data['product_bulkdiscount_value']	= implode(', ',$bulk_arr);
								else
									$data['product_bulkdiscount_value'] = '';
							}
							else
								$data['product_bulkdiscount_value'] = '';
						}
						else
						{
							$data['product_bulkdiscount_value'] = 'N/A';
						}
						
						
						// Combination Image
						$prod_img_arr	= array();
						// Get the ids of images linked with current product
						$sql_img_id = "SELECT images_image_id  
											FROM 
												images_product   
											WHERE 
												products_product_id ='".$cur_prodid."'  
											ORDER BY 
												image_order";
						$ret_img_id = $db->query($sql_img_id);
						if($db->num_rows($ret_img_id))
						{
							while ($row_img_id = $db->fetch_array($ret_img_id))
							{
								// Check whether this image exists in images table
								$check_img = check_image_exists($row_img_id['images_image_id']);
								if($check_img)
									$prod_img_arr[] = $row_img_id['images_image_id'];
							}
							if(count($prod_img_arr))
								$data['product_image_ids'] 	= implode('=> ',$prod_img_arr);
							else
								$data['product_image_ids'] = '';
						}
						else
							$data['product_image_ids'] = '';
							
							
							
							
						//check exists any combinations.. (only barcode exists condition).. if all checkbox unticked, but any of comb already exists in db	--starts
						$comb_exists_indb = 0;
						$sql_comb = "SELECT comb_id 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = '".$cur_prodid."'  
									ORDER BY 
										comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								$sql_combdet = "SELECT product_variables_var_id
													FROM 
														product_variable_combination_stock_details 
													WHERE 
														comb_id=".$row_comb['comb_id']." 
														AND products_product_id='".$cur_prodid."'";
								$ret_combdet = $db->query($sql_combdet);
								if($db->num_rows($ret_combdet) == $var_val_exists_count)
								{
									$comb_exists_indb ++;
								}
							}
						}
							
						if($comb_exists_indb !=0)
						{
							$data['product_barcode'] 	= 'N/A';
							if($special_product_code_req)
								$data['product_special_product_code'] 	= 'N/A';
						}
						else
						{
							$data['product_barcode'] 	= $row_stock['product_barcode'];
							if($special_product_code_req)
								$data['product_special_product_code'] 	= $row_stock['product_special_product_code'];
						}
							
						//check exists any combinations.. (only barcode exists condition).. if all checkbox unticked, but any of comb already exists in db	--ends				
					}
					else // if any of the check box are ticked
					{
						if($row_stock['product_variablestock_allowed']!='Y')
						{
							$data['product_varstock'] 	= $row_stock['product_webstock'];
						}
						else
						{
							$data['product_varstock'] 	= 'N/A';
						}
						
						$data['product_barcode'] 	= 'N/A';
						if($special_product_code_req)
							$data['product_special_product_code'] 	= 'N/A';
						
						//$data['product_discount_enteredasval'] 	= $disc_type[$row_stock['product_discount_enteredasval']];
						//$data['product_discount'] 				= $row_stock['product_discount'];
						if($row_stock['product_variablecomboprice_allowed']=='Y')
						{
							$data['product_varprice'] 	= 'N/A';
							$data['product_bulkdiscount_value'] = 'N/A';
						}
						else
						{
							$data['product_varprice'] 				= $row_stock['product_webprice'];
							
							$bulk_arr = array();
							if($row_stock['product_bulkdiscount_allowed']=='Y')
							{
								// get the direct bulk discount details for current product
								$sql_bulk = "SELECT bulk_qty, bulk_price 
												FROM 
													product_bulkdiscount 
												WHERE 
													products_product_id = '".$cur_prodid."'  
													AND comb_id = 0 
												ORDER BY 
													bulk_qty";
								$ret_bulk = $db->query($sql_bulk);
								if($db->num_rows($ret_bulk))
								{
									while ($row_bulk = $db->fetch_array($ret_bulk))
									{
										$bulk_arr[] = $row_bulk['bulk_qty'].'=>'.$row_bulk['bulk_price'];
									}
									if(count($bulk_arr))
										$data['product_bulkdiscount_value']	= implode(', ',$bulk_arr);
									else
										$data['product_bulkdiscount_value'] = '';
								}
								else
									$data['product_bulkdiscount_value'] = '';
							}
							else
							{
								$data['product_bulkdiscount_value'] = 'N/A';
							}
						}
						
						
						$prod_img_arr	= array();
						// Get the ids of images linked with current product
						$sql_img_id = "SELECT images_image_id  
											FROM 
												images_product   
											WHERE 
												products_product_id ='".$cur_prodid."'  
											ORDER BY 
												image_order";
						$ret_img_id = $db->query($sql_img_id);
						if($db->num_rows($ret_img_id))
						{
							while ($row_img_id = $db->fetch_array($ret_img_id))
							{
								// Check whether this image exists in images table
								$check_img = check_image_exists($row_img_id['images_image_id']);
								if($check_img)
									$prod_img_arr[] = $row_img_id['images_image_id'];
							}
							if(count($prod_img_arr))
								$data['product_image_ids'] 	= implode('=> ',$prod_img_arr);
							else
								$data['product_image_ids'] = '';
						}
						else
							$data['product_image_ids'] = '';
						
						//$data['product_image_ids'] = '';
					}
					
					/* Handling the case of bulk discounts and splitting them into max of 15 columns */
					
					if($data['product_bulkdiscount_value'] == 'N/A')
					{
						for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
						{
							$data['product_bulkdiscount_qty'][$bulk_i] 		= 'N/A';
							$data['product_bulkdiscount_price'][$bulk_i] 	= 'N/A';
						}	
					}
					elseif($data['product_bulkdiscount_value'] == '')
					{
						for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
						{
							$data['product_bulkdiscount_qty'][$bulk_i] 		= '';
							$data['product_bulkdiscount_price'][$bulk_i] 	= '';
						}
					}
					else
					{
						$bulk_data_arr = explode(',',$data['product_bulkdiscount_value']);
						$bulk_cnt = 1;
						foreach ($bulk_data_arr as $k=>$v)
						{
							$splt_arr = explode('=>',$v);
							$data['product_bulkdiscount_qty'][$bulk_cnt] 	= $splt_arr[0];
							$data['product_bulkdiscount_price'][$bulk_cnt] 	= $splt_arr[1];
							$bulk_cnt++;
						}
						if($bulk_cnt<=15)
						{
							for($bulk_i = $bulk_cnt;$bulk_i<=15;$bulk_i++)
							{
								$data['product_bulkdiscount_qty'][$bulk_cnt] 	= ' ';
								$data['product_bulkdiscount_price'][$bulk_cnt] 	= ' ';
								$bulk_cnt++;
							}	
						}
					}
				
					$print_data = array();
					if($special_product_code_req)
					{
							$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_special_product_code'		=> $data['product_special_product_code'],
													'product_varstock'					=> $data['product_varstock'],
													'product_varprice'					=> $data['product_varprice'],
													'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
													'product_discount'					=> $data['product_discount'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],
													'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
													'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
													'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
													'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
													'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
													'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
													'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
													'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
													'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
													'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
													'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
													'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
													'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
													'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
													'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
													'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
													'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
													'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
													'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
													'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
													'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
													'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
													'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
													'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
													'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
													'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
													'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
													'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
													'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
													'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id'],
													'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
													'product_variablecomboprice_allowed'=> $data['product_variablecomboprice_allowed'],
													'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
													'var_id_var_value_id'				=> $data['var_id_var_value_id']
													);

					}
					else
					{
						$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varstock'					=> $data['product_varstock'],
													'product_varprice'					=> $data['product_varprice'],
													'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
													'product_discount'					=> $data['product_discount'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],
													'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
													'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
													'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
													'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
													'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
													'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
													'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
													'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
													'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
													'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
													'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
													'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
													'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
													'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
													'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
													'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
													'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
													'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
													'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
													'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
													'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
													'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
													'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
													'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
													'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
													'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
													'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
													'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
													'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
													'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id'],
													'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
													'product_variablecomboprice_allowed'=> $data['product_variablecomboprice_allowed'],
													'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
													'var_id_var_value_id'				=> $data['var_id_var_value_id']
													);
					}
					
					/*array_walk($print_data, "add_quotes");
					print implode(",", $print_data) ."\r\n";*/
					$cell_count = 0;
					foreach($print_data as $cell_data)
					{
					$worksheet->writeString($row_count, $cell_count, "$cell_data");
					$cell_count++;
					}
					$row_count++;
					//---------------------------- Printing First Row for Product -- ends -------------------------------------
					


			        //----------- Printing the combination barcodes--------, if 3 of the check boxes unticked.. but the combinations are alredy saved in db --starts -----------
					if($var_stock_variables == 0) 
					{
						
						$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned,comb_special_product_code  
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = '".$cur_prodid."'  
									ORDER BY 
										comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$prev_id = 0;
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								$data						= array();
								$data['product_variablestock_allowed']  = $row_stock['product_variablestock_allowed'];
								$data['product_variablecomboprice_allowed'] = $row_stock['product_variablecomboprice_allowed'];
								$data['product_variablecombocommon_image_allowed'] = $row_stock['product_variablecombocommon_image_allowed'];
								
									
								$comb_name_arr 				= array();
								$comb_id_arr 				= array();
								// Get the combination details
								$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
													FROM 
														product_variable_combination_stock_details 
													WHERE 
														comb_id=".$row_comb['comb_id']." 
														AND products_product_id='".$cur_prodid."'";
								$ret_combdet = $db->query($sql_combdet);
								
								if($db->num_rows($ret_combdet) == $var_val_exists_count)
								{
									$pname 		=  '    "';
									$data['product_name'] 		= $pname;
									if($db->num_rows($ret_combdet))
									{
										while ($row_combdet = $db->fetch_array($ret_combdet))
										{
											// Get the respective variable name
											$sql_varname = "SELECT var_name,var_id 
																FROM 
																	product_variables 
																WHERE 
																	var_id = '".$row_combdet['product_variables_var_id']."'  
																	AND var_hide = 0  
																LIMIT 
																	1";
											$ret_varname = $db->query($sql_varname);
											if($db->num_rows($ret_varname))
											{
												$row_varname = $db->fetch_array($ret_varname);											
											 
											// Get the variable value details
											$sql_varvalname = "SELECT var_value,var_value_id 
																FROM 
																	product_variable_data 
																WHERE 
																	product_variables_var_id = '".$row_varname['var_id']."' 
																	AND var_value_id = '".$row_combdet['product_variable_data_var_value_id']."'  
																LIMIT 
																	1";
											$ret_varvalname = $db->query($sql_varvalname);
											if($db->num_rows($ret_varvalname))
											{
												$row_varvalname = $db->fetch_array($ret_varvalname);											
											 
											$comb_name_arr[] = stripslashes($row_varname['var_name']).': '.stripslashes($row_varvalname['var_value']);
											$comb_id_arr[] = $row_varname['var_id'].'=>'.$row_varvalname['var_value_id'];
											}
											}
										}
									}
										
									$data['comb_name']						= implode(',',$comb_name_arr);
									$data['var_id_var_value_id']			= implode(',',$comb_id_arr);
									$data['product_barcode'] 				= $row_comb['comb_barcode'];
									$data['product_special_product_code'] 	= $row_comb['comb_special_product_code'];
									$data['product_varstock'] 				= 'N/A';
									$data['product_id'] 					= 'P-'.$cur_prodid;
									$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
									$data['product_varprice'] 				= 'N/A';
									$data['product_bulkdiscount_value'] 	= 'N/A';
									$data['product_image_ids'] 				= 'N/A';	
									// Handling bulk discount allowed for the combinations
									$data['product_bulkdiscount_allowed']   = 'N/A'; 
									/* Handling the case of bulk discounts and splitting them into max of 15 columns */
					
									if($data['product_bulkdiscount_value'] == 'N/A')
									{
										for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
										{
											$data['product_bulkdiscount_qty'][$bulk_i] 		= 'N/A';
											$data['product_bulkdiscount_price'][$bulk_i] 	= 'N/A';
										}	
									}
									elseif($data['product_bulkdiscount_value'] == '')
									{
										for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
										{
											$data['product_bulkdiscount_qty'][$bulk_i] 		= '';
											$data['product_bulkdiscount_price'][$bulk_i] 	= '';
										}
									}
									else
									{
										$bulk_data_arr = explode(',',$data['product_bulkdiscount_value']);
										$bulk_cnt = 1;
										foreach ($bulk_data_arr as $k=>$v)
										{
											$splt_arr = explode('=>',$v);
											$data['product_bulkdiscount_qty'][$bulk_cnt] 	= $splt_arr[0];
											$data['product_bulkdiscount_price'][$bulk_cnt] 	= $splt_arr[1];
											$bulk_cnt++;
										}
										if($bulk_cnt<=15)
										{
											for($bulk_i = $bulk_cnt;$bulk_i<=15;$bulk_i++)
											{
												$data['product_bulkdiscount_qty'][$bulk_cnt] 	= '';
												$data['product_bulkdiscount_price'][$bulk_cnt] 	= '';
												$bulk_cnt++;
											}	
										}
									}
									
									$print_data = array();
									if($special_product_code_req)
									{
										$print_data		= array	(
																'product_name'						=> $data['product_name'],
																'comb_name'							=> $data['comb_name'],
																'product_barcode'					=> $data['product_barcode'],
																'product_special_product_code'		=> $data['product_special_product_code'],
																'product_varstock'					=> $data['product_varstock'],
																'product_varprice'					=> $data['product_varprice'],
																'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
																'product_discount'					=> $data['product_discount'],
																'product_image_ids'					=> $data['product_image_ids'],
																'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],

																'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
																'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
																'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
																'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
																'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
																'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
																'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
																'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
																'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
																'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
																'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
																'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
																'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
																'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
																'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
																'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
																'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
																'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
																'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
																'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
																'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
																'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
																'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
																'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
																'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
																'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
																'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
																'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
																'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
																'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
																'product_id'						=> $data['product_id'],
																'comb_id'							=> $data['comb_id'],
																'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
																'product_variablecomboprice_allowed'	=> $data['product_variablecomboprice_allowed'],
																'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
																'var_id_var_value_id'				=> $data['var_id_var_value_id']
															);
									}
									else
									{
											$print_data		= array	(
																	'product_name'						=> $data['product_name'],
																	'comb_name'							=> $data['comb_name'],
																	'product_barcode'					=> $data['product_barcode'],
																	'product_varstock'					=> $data['product_varstock'],
																	'product_varprice'					=> $data['product_varprice'],
																	'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
																	'product_discount'					=> $data['product_discount'],
																	'product_image_ids'					=> $data['product_image_ids'],
																	'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],
																	
																	'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
																	'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
																	'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
																	'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
																	'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
																	'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
																	'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
																	'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
																	'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
																	'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
																	'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
																	'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
																	'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
																	'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
																	'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
																	'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
																	'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
																	'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
																	'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
																	'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
																	'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
																	'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
																	'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
																	'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
																	'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
																	'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
																	'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
																	'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
																	'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
																	'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
																	'product_id'						=> $data['product_id'],
																	'comb_id'							=> $data['comb_id'],
																	'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
																	'product_variablecomboprice_allowed'	=> $data['product_variablecomboprice_allowed'],
																	'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
																	'var_id_var_value_id'				=> $data['var_id_var_value_id']
																);
									
									}
									/*array_walk($print_data, "add_quotes");
									print implode(",", $print_data) ."\r\n";*/
									$cell_count = 0;
									foreach($print_data as $cell_data)
									{
									$worksheet->writeString($row_count, $cell_count, "$cell_data");
									$cell_count++;
									}
									$row_count++;
									
									
								}
							}
						}
						
						
					}
				//----------- Printing the combination barcodes--------, if 3 of the check boxes unticked.. but the combinations are alredy saved in db --ends -----------



					//-------------------------------------------Printing Combination Rows -- starts -------------------------------------------
					if($var_stock_variables == 1) 
					{
					// Get the combination details
					$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned,comb_special_product_code 
									FROM 
										product_variable_combination_stock 
									WHERE 
										products_product_id = '".$cur_prodid."'  
									ORDER BY 
										comb_id";
					$ret_comb = $db->query($sql_comb);
					if($db->num_rows($ret_comb))
					{
						$prev_id = 0;
						while ($row_comb = $db->fetch_array($ret_comb))
						{	
							$comb_name_arr 				= array();
							$comb_id_arr 				= array();
							// Get the combination details
							$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
												FROM 
													product_variable_combination_stock_details 
												WHERE 
													comb_id=".$row_comb['comb_id']." 
													AND products_product_id='".$cur_prodid."'";
							$ret_combdet = $db->query($sql_combdet);
							if($db->num_rows($ret_combdet) == $var_val_exists_count)
							{
								$pname 		=  '    "';
								$data['product_name'] 		= $pname;
									
									
								if($db->num_rows($ret_combdet))
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										// Get the respective variable name
										$sql_varname = "SELECT var_name,var_id
															FROM 
																product_variables 
															WHERE 
																var_id = '".$row_combdet['product_variables_var_id']."'
																 AND var_hide = 0  
															LIMIT 
																1";
										$ret_varname = $db->query($sql_varname);
										if($db->num_rows($ret_varname))
										{
											$row_varname = $db->fetch_array($ret_varname);											
										
											// Get the variable value details
											$sql_varvalname = "SELECT var_value,var_value_id  
																FROM 
																	product_variable_data 
																WHERE 
																	product_variables_var_id = '".$row_varname['var_id']."' 
																	AND var_value_id = '".$row_combdet['product_variable_data_var_value_id']."'  
																LIMIT 
																	1";
											$ret_varvalname = $db->query($sql_varvalname);
											if($db->num_rows($ret_varvalname))
											{
												$row_varvalname = $db->fetch_array($ret_varvalname);											
												$comb_name_arr[] = stripslashes($row_varname['var_name']).': '.stripslashes($row_varvalname['var_value']);
												$comb_id_arr[] = $row_varname['var_id'].'=>'.$row_varvalname['var_value_id'];
											} 
										} 
									}
								}
									
								$data['comb_name']						= implode(', ',$comb_name_arr);
								$data['var_id_var_value_id']			= implode(',',$comb_id_arr);
								$data['product_barcode'] 				= $row_comb['comb_barcode'];
								$data['product_special_product_code'] 	= $row_comb['comb_special_product_code'];
								if($row_stock['product_variablestock_allowed'] =='Y')
								{
								$data['product_varstock'] 				= $row_comb['web_stock'];
								}
								else
								{
								$data['product_varstock'] 				= 'N/A';	
								}
								$data['product_id'] 					= 'P-'.$cur_prodid;
								$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
								
								
								// combination price
								if($row_stock['product_variablecomboprice_allowed'] =='Y')
								{
									$data['product_varprice'] 		= $row_comb['comb_price'];
									$bulk_arr = array();
									if($row_stock['product_bulkdiscount_allowed']=='Y')
									{
									// get the direct bulk discount details for current product
									$sql_bulk = "SELECT bulk_qty, bulk_price 
													FROM 
														product_bulkdiscount 
													WHERE 
														products_product_id = '".$cur_prodid."'  
														AND comb_id = '".$row_comb['comb_id']."'  
													ORDER BY 
														bulk_qty";
									$ret_bulk = $db->query($sql_bulk);
									if($db->num_rows($ret_bulk))
									{
										while ($row_bulk = $db->fetch_array($ret_bulk))
										{
											$bulk_arr[] = $row_bulk['bulk_qty'].'=>'.$row_bulk['bulk_price'];
										}
										if(count($bulk_arr))
											$data['product_bulkdiscount_value']	= implode(', ',$bulk_arr);
										else
											$data['product_bulkdiscount_value'] = '';
									}
									else
										$data['product_bulkdiscount_value'] = '';
									}
									else
									{
										$data['product_bulkdiscount_value'] = 'N/A';
									}
								}
								else
								{
									$data['product_varprice'] 		= 'N/A';
									$data['product_bulkdiscount_value'] = 'N/A';
								}
							
								// Combination Image
								if($row_stock['product_variablecombocommon_image_allowed'] =='Y')
								{
									$prod_img_arr	= array();
									// Get the ids of images linked with current product
									$sql_img_id = "SELECT images_image_id  
														FROM 
															images_variable_combination   
														WHERE 
															comb_id='".$row_comb['comb_id']."'  

														ORDER BY 
															image_order";
									$ret_img_id = $db->query($sql_img_id);
									if($db->num_rows($ret_img_id))
									{
										while ($row_img_id = $db->fetch_array($ret_img_id))
										{
											// Check whether this image exists in images table
											$check_img = check_image_exists($row_img_id['images_image_id']);
											if($check_img)
												$prod_img_arr[] = $row_img_id['images_image_id'];
										}
										if(count($prod_img_arr))
											$data['product_image_ids'] 	= implode('=> ',$prod_img_arr);
										else
											$data['product_image_ids'] = '';
									}
									else
										$data['product_image_ids'] = '';
								}
								else
								{
									$data['product_image_ids'] = 'N/A';
								}
								// Handling bulk discount allowed for the combinations
								$data['product_bulkdiscount_allowed'] = 'N/A';
								/* Handling the case of bulk discounts and splitting them into max of 15 columns */
					
								if($data['product_bulkdiscount_value'] == 'N/A')
								{
									for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
									{
										$data['product_bulkdiscount_qty'][$bulk_i] 		= 'N/A';
										$data['product_bulkdiscount_price'][$bulk_i] 	= 'N/A';
									}	
								}
								elseif($data['product_bulkdiscount_value'] == '')
								{
									for($bulk_i = 1;$bulk_i<=15;$bulk_i++)
									{
										$data['product_bulkdiscount_qty'][$bulk_i] 		= '';
										$data['product_bulkdiscount_price'][$bulk_i] 	= '';
									}
								}
								else
								{
									$bulk_data_arr = explode(',',$data['product_bulkdiscount_value']);
									$bulk_cnt = 1;
									foreach ($bulk_data_arr as $k=>$v)
									{
										$splt_arr = explode('=>',$v);
										$data['product_bulkdiscount_qty'][$bulk_cnt] 	= $splt_arr[0];
										$data['product_bulkdiscount_price'][$bulk_cnt] 	= $splt_arr[1];
										$bulk_cnt++;
									}
									if($bulk_cnt<=15)
									{
										for($bulk_i = $bulk_cnt;$bulk_i<=15;$bulk_i++)
										{
											$data['product_bulkdiscount_qty'][$bulk_cnt] 	= '';
											$data['product_bulkdiscount_price'][$bulk_cnt] 	= '';
											$bulk_cnt++;
										}	
									}
								}
								
								$print_data = array();
								if($special_product_code_req)
								{
									$print_data		= array	(
															'product_name'						=> $data['product_name'],
															'comb_name'							=> $data['comb_name'],
															'product_barcode'					=> $data['product_barcode'],
															'product_special_product_code'		=> $data['product_special_product_code'],
															'product_varstock'					=> $data['product_varstock'],
															'product_varprice'					=> $data['product_varprice'],
															'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
															'product_discount'					=> $data['product_discount'],
															'product_image_ids'					=> $data['product_image_ids'],
															'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],

															'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
															'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
															'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
															'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
															'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
															'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
															'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
															'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
															'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
															'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
															'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
															'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
															'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
															'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
															'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
															'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
															'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
															'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
															'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
															'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
															'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
															'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
															'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
															'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
															'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
															'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
															'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
															'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
															'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
															'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
															'product_id'						=> $data['product_id'],
															'comb_id'							=> $data['comb_id'],
															'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
															'product_variablecomboprice_allowed'	=> $data['product_variablecomboprice_allowed'],
															'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
															'var_id_var_value_id'								=> $data['var_id_var_value_id']
														);
								}
								else
								{
									$print_data		= array	(
															'product_name'						=> $data['product_name'],
															'comb_name'							=> $data['comb_name'],
															'product_barcode'					=> $data['product_barcode'],
															'product_varstock'					=> $data['product_varstock'],
															'product_varprice'					=> $data['product_varprice'],
															'product_discount_enteredasval'		=> $data['product_discount_enteredasval'],
															'product_discount'					=> $data['product_discount'],
															'product_image_ids'					=> $data['product_image_ids'],
															'product_bulkdiscount_allowed'      => $data['product_bulkdiscount_allowed'],
		
															'product_bulkdiscount_qty1'			=> $data['product_bulkdiscount_qty'][1],
															'product_bulkdiscount_price1'		=> $data['product_bulkdiscount_price'][1],
															'product_bulkdiscount_qty2'			=> $data['product_bulkdiscount_qty'][2],
															'product_bulkdiscount_price2'		=> $data['product_bulkdiscount_price'][2],
															'product_bulkdiscount_qty3'			=> $data['product_bulkdiscount_qty'][3],
															'product_bulkdiscount_price3'		=> $data['product_bulkdiscount_price'][3],
															'product_bulkdiscount_qty4'			=> $data['product_bulkdiscount_qty'][4],
															'product_bulkdiscount_price4'		=> $data['product_bulkdiscount_price'][4],
															'product_bulkdiscount_qty5'			=> $data['product_bulkdiscount_qty'][5],
															'product_bulkdiscount_price5'		=> $data['product_bulkdiscount_price'][5],
															'product_bulkdiscount_qty6'			=> $data['product_bulkdiscount_qty'][6],
															'product_bulkdiscount_price6'		=> $data['product_bulkdiscount_price'][6],
															'product_bulkdiscount_qty7'			=> $data['product_bulkdiscount_qty'][7],
															'product_bulkdiscount_price7'		=> $data['product_bulkdiscount_price'][7],
															'product_bulkdiscount_qty8'			=> $data['product_bulkdiscount_qty'][8],
															'product_bulkdiscount_price8'		=> $data['product_bulkdiscount_price'][8],
															'product_bulkdiscount_qty9'			=> $data['product_bulkdiscount_qty'][9],
															'product_bulkdiscount_price9'		=> $data['product_bulkdiscount_price'][9],
															'product_bulkdiscount_qty10'		=> $data['product_bulkdiscount_qty'][10],
															'product_bulkdiscount_price10'		=> $data['product_bulkdiscount_price'][10],
															'product_bulkdiscount_qty11'		=> $data['product_bulkdiscount_qty'][11],
															'product_bulkdiscount_price11'		=> $data['product_bulkdiscount_price'][11],
															'product_bulkdiscount_qty12'		=> $data['product_bulkdiscount_qty'][12],
															'product_bulkdiscount_price12'		=> $data['product_bulkdiscount_price'][12],
															'product_bulkdiscount_qty13'		=> $data['product_bulkdiscount_qty'][13],
															'product_bulkdiscount_price13'		=> $data['product_bulkdiscount_price'][13],
															'product_bulkdiscount_qty14'		=> $data['product_bulkdiscount_qty'][14],
															'product_bulkdiscount_price14'		=> $data['product_bulkdiscount_price'][14],
															'product_bulkdiscount_qty15'		=> $data['product_bulkdiscount_qty'][15],
															'product_bulkdiscount_price15'		=> $data['product_bulkdiscount_price'][15],
															'product_id'						=> $data['product_id'],
															'comb_id'							=> $data['comb_id'],
															'product_variablestock_allowed'		=> $data['product_variablestock_allowed'],
															'product_variablecomboprice_allowed'	=> $data['product_variablecomboprice_allowed'],
															'product_variablecombocommon_image_allowed'	=> $data['product_variablecombocommon_image_allowed'],
															'var_id_var_value_id'								=> $data['var_id_var_value_id']
														);
								}
								
								
								/*array_walk($print_data, "add_quotes");
								print implode(",", $print_data) ."\r\n";*/
								$cell_count = 0;
								foreach($print_data as $cell_data)
								{
								$worksheet->writeString($row_count, $cell_count, "$cell_data");
								$cell_count++;
								}
								$row_count++;
								
							}
						}
					}
				 }
				 //------------------------------------------- Printing Combination Rows-- ends -------------------------------------------
					
				}
			
		
		}
		}

	// Finish the spreadsheet, dumping it to the browser 
	$xls->close(); 	
		
	}
	elseif($_REQUEST['cur_mod']=='stock_upload') // case of upload
	{
		
		$table = '';
		$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
		$table .=	$css;
		$go_back ='<br /><br /><center><a class="smalllink" href="home.php?request=advanced_offline" onclick="show_processing()"><strong>Click here to go back</strong></a></center>';
		$table .=	$go_back;	
		$table .='<br/><br/><center><span class="redtext"><strong>Sorry.. Update operation Cancelled due to following errors</strong></span></center><br/>';
		$table .= "<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
		$table .="<tr>";
		$table .="<td width='5%' class='listingtableheader'>Row #</td>";
		$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
		$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
		$table .="<td width='5%' class='listingtableheader'>Barcode</td>";
		
		if($special_product_code_req)
			$table .="<td width='5%' class='listingtableheader'>Special Product Code</td>";		
		
		$table .="<td width='5%' class='listingtableheader'>Stock</td>";
		$table .="<td width='5%' class='listingtableheader'>Price</td>";
		$table .="<td width='5%' class='listingtableheader'>Discount Type</td>";
		$table .="<td width='5%' class='listingtableheader'>Discount</td>";	
		$table .="<td width='15%' class='listingtableheader'>Image Ids</td>";
		$table .="<td width='15%' class='listingtableheader'>Allow Bulk Discount</td>";		
		$table .="<td width='15%' class='listingtableheader'>Bulk Discount Values</td>";
		/*$table .="<td width='5%' style='font-weight:bold;'>Product Id</td>";
		$table .="<td width='5%' style='font-weight:bold;'>Combination Id</td>";*/
		/*$table .="<td width='5%' style='font-weight:bold;'>Variable Stock</td>";
		$table .="<td width='5%' style='font-weight:bold;'>Variable Price</td>";
		$table .="<td width='5%' style='font-weight:bold;'>Variable Image</td>";*/
		/*$table .="<td width='5%' style='font-weight:bold;'>VarId=>VarValId</td>";*/
		$table .="<td width='20%' class='listingtableheader'>Error Message</td>";
		$table .="</tr>";

		
		$head_arr = $var_prodcombstockpriceimage_arr;	
		$err_no = 0;
		$error_outer  ='';
		$sucess = '';
		
		// checking upload file type		
		if(!$_FILES['upload_file']['name'])
		{
			$err_no = 1;
		}
		if (strtolower($_FILES['upload_file']['type'])!='text/csv' and strtolower($_FILES['upload_file']['type'])!='text/plain' and strtolower($_FILES['upload_file']['type'])!='application/vnd.ms-excel' and strtolower($_FILES['upload_file']['type'])!='application/octet-stream' and strtolower($_FILES['upload_file']['type'])!='text/comma-separated-values')
		{
			$err_no = 2;
		}
	
		if($err_no!=0)
		{
			$error_outer = '<span class="redtext">-- Select a CSV file --</span>';
			$err_cnt = 1;
		}
		else
		{
			$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
			$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
			// Get the first row
			$data = fgetcsv($fp,$fs1, ",");
			$line	= $done_cnt = $err_cnt =  0;
			//echo count($data)."+++".count($head_arr);
			if(count($data))
			{
				if(count($data)!=count($head_arr))
				{
					$error_outer = '<span class="redtext">-- Error in File header --</span>';
					$err_cnt = 1;
				}
				else
				{
					$i = 0;
					foreach ($head_arr as $k=>$v)
					{
						if($data[$i]!=$v)
						{
							$error_outer = '<span class="redtext">-- Error in File header --</span>';
							$err_cnt = 1;
							break;
						}	
						$i++;
					}
				}	
			}
			if($error_outer=='')
			{
				$error  ='';
				$rowcnt=2;
				$error_count = 0;
				$line = 0;
				fclose ($fp);
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				/*-----------------------  First loop for checking the errors in the csv file, if error occured any of the row , that will be stored and finally it will display in table format  --------------------------- starts--*/
				while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
				{
					
						$num 		= count($data);
						$cur_msg	= '';
						$error 		= '';
						if($line!=0)
						{
						
							if($special_product_code_req)
							{
								$product_name				= trim($data[0]);
								$combination				= trim($data[1]);
								$barcode					= trim($data[2]);
								$specialproductcode			= trim($data[3]);
								$stock						= trim($data[4]);
								$price						= trim($data[5]);
								$discounttype				= trim($data[6]);
								$discount					= trim($data[7]);
								$imageids					= trim($data[8]);
								$bulk_allow					= trim($data[9]);
								$bulk_qty1					= trim($data[10]);
								$bulk_price1				= trim($data[11]);
								$bulk_qty2					= trim($data[12]);
								$bulk_price2				= trim($data[13]);
								$bulk_qty3					= trim($data[14]);
								$bulk_price3				= trim($data[15]);
								$bulk_qty4					= trim($data[16]);
								$bulk_price4				= trim($data[17]);
								$bulk_qty5					= trim($data[18]);
								$bulk_price5				= trim($data[19]);
								$bulk_qty6					= trim($data[20]);
								$bulk_price6				= trim($data[21]);
								$bulk_qty7					= trim($data[22]);
								$bulk_price7				= trim($data[23]);
								$bulk_qty8					= trim($data[24]);
								$bulk_price8				= trim($data[25]);
								$bulk_qty9					= trim($data[26]);
								$bulk_price9				= trim($data[27]);
								$bulk_qty10					= trim($data[28]);
								$bulk_price10				= trim($data[29]);
								$bulk_qty11					= trim($data[30]);
								$bulk_price11				= trim($data[31]);
								$bulk_qty12					= trim($data[32]);
								$bulk_price12				= trim($data[33]);
								$bulk_qty13					= trim($data[34]);
								$bulk_price13				= trim($data[35]);
								$bulk_qty14					= trim($data[36]);
								$bulk_price14				= trim($data[37]);
								$bulk_qty15					= trim($data[38]);
								$bulk_price15				= trim($data[39]);
								$product_id					= trim($data[40]);
								$comb_id					= trim($data[41]);
								$variable_stock_set			= trim($data[42]);
								$variable_price_set			= trim($data[43]);
								$variable_image_set			= trim($data[44]);
								$var_id_var_value_ids		= trim($data[45]);	

							}
							else
							{
								$product_name				= trim($data[0]);
								$combination				= trim($data[1]);
								$barcode					= trim($data[2]);
								$stock						= trim($data[3]);
								$price						= trim($data[4]);
								$discounttype				= trim($data[5]);
								$discount					= trim($data[6]);
								$imageids					= trim($data[7]);
								$bulk_allow					= trim($data[8]);
								//$bulk_values				= trim($data[7]);
								$bulk_qty1					= trim($data[9]);
								$bulk_price1				= trim($data[10]);
								$bulk_qty2					= trim($data[11]);
								$bulk_price2				= trim($data[12]);
								$bulk_qty3					= trim($data[13]);
								$bulk_price3				= trim($data[14]);
								$bulk_qty4					= trim($data[15]);
								$bulk_price4				= trim($data[16]);
								$bulk_qty5					= trim($data[17]);
								$bulk_price5				= trim($data[18]);
								$bulk_qty6					= trim($data[19]);
								$bulk_price6				= trim($data[20]);
								$bulk_qty7					= trim($data[21]);
								$bulk_price7				= trim($data[22]);
								$bulk_qty8					= trim($data[23]);
								$bulk_price8				= trim($data[24]);
								$bulk_qty9					= trim($data[25]);
								$bulk_price9				= trim($data[26]);
								$bulk_qty10					= trim($data[27]);
								$bulk_price10				= trim($data[28]);
								$bulk_qty11					= trim($data[29]);
								$bulk_price11				= trim($data[30]);
								$bulk_qty12					= trim($data[31]);
								$bulk_price12				= trim($data[32]);
								$bulk_qty13					= trim($data[33]);
								$bulk_price13				= trim($data[34]);
								$bulk_qty14					= trim($data[35]);
								$bulk_price14				= trim($data[36]);
								$bulk_qty15					= trim($data[37]);
								$bulk_price15				= trim($data[38]);
								$product_id					= trim($data[39]);
								$comb_id					= trim($data[40]);
								$variable_stock_set			= trim($data[41]);
								$variable_price_set			= trim($data[42]);
								$variable_image_set			= trim($data[43]);
								$var_id_var_value_ids		= trim($data[44]);	
							}
							
							
							// Generating the bulk discount string
							$bulk_values = '';
							
							if(($bulk_qty1=='N/A' and $bulk_price1=='N/A') and ($bulk_qty2=='N/A' and $bulk_price2=='N/A') and ($bulk_qty3=='N/A' and $bulk_price3=='N/A') 
							 and ($bulk_qty4=='N/A' and $bulk_price4=='N/A')  and ($bulk_qty5=='N/A' and $bulk_price5=='N/A') 
							 and ($bulk_qty6=='N/A' and $bulk_price6=='N/A') and ($bulk_qty7=='N/A' and $bulk_price7=='N/A')  and ($bulk_qty8=='N/A' and $bulk_price8=='N/A')
							 and ($bulk_qty9=='N/A' and $bulk_price9=='N/A') and ($bulk_qty10=='N/A' and $bulk_price10=='N/A')  and ($bulk_qty11=='N/A' and $bulk_price11=='N/A')
							 and ($bulk_qty12=='N/A' and $bulk_price12=='N/A') and ($bulk_qty13=='N/A' and $bulk_price13=='N/A') and ($bulk_qty14=='N/A' and $bulk_price14=='N/A') 
							 and ($bulk_qty15=='N/A' and $bulk_price15=='N/A'))
							{
								$bulk_values = 'N/A'; 
							}
							else
							{	
							
								$bulk_values = build_bulk_str($bulk_qty1,$bulk_price1,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty2,$bulk_price2,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty3,$bulk_price3,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty4,$bulk_price4,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty5,$bulk_price5,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty6,$bulk_price6,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty7,$bulk_price7,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty8,$bulk_price8,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty9,$bulk_price9,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty10,$bulk_price10,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty11,$bulk_price11,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty12,$bulk_price12,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty13,$bulk_price13,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty14,$bulk_price14,$bulk_values);
								$bulk_values = build_bulk_str($bulk_qty15,$bulk_price15,$bulk_values);
							}
							$var_stock = $var_price = $var_img = false;
							
							$comb_id_format = $comb_id;
							$product_id_format = $product_id;
													
							$product_arr					= explode('-',$product_id);
							$stock_update 					= $price_update = $image_update = false;
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								
								if(!is_numeric($product_arr[1]))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Invalid Product Id --('.$product_id.')';
								}
								else
								{
								$product_id = addslashes(trim($product_arr[1]));
								
								
								$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
															product_variablecomboprice_allowed, product_variablecombocommon_image_allowed,product_bulkdiscount_allowed   
													FROM 
														products
													WHERE 
														sites_site_id=$ecom_siteid 
														AND product_id = $product_id 
													LIMIT 
														1";	
									$ret_stock = $db->query($sql_stock);
									$var_stock_variables = 0;
									if($db->num_rows($ret_stock))
									{
										$row_stock = $db->fetch_array($ret_stock);
										if($row_stock['product_variablestock_allowed']=='Y')
										{
											$var_stock = true;
											$var_stock_variables = 1;
										}
										if($row_stock['product_variablecomboprice_allowed']=='Y')
										{
											$var_price = true;	
											$var_stock_variables = 1;
										}
										if($row_stock['product_variablecombocommon_image_allowed']=='Y')
										{
											$var_img = true;	
											$var_stock_variables = 1;
										}
										// Check is the settings chnaged after export the CSV
										if($row_stock['product_variablestock_allowed'] != $variable_stock_set)
										{
											if ($error!='')
												$error .= '<br/>';
											$error .= '-- Variable Stock Setting does not match with the website';
										}
										if($row_stock['product_variablecomboprice_allowed'] != $variable_price_set)
										{
											if ($error!='')
												$error .= '<br/>';
											$error .= '-- Variable Price Setting does not match with the website';
										}
										if($row_stock['product_variablecombocommon_image_allowed'] != $variable_image_set)
										{
											if ($error!='')
												$error .= '<br/>';
											$error .= '-- Variable Image Setting does not match with the website';
										}	
									}
									else
									{
										if ($error!='')
												$error .= '<br/>';
											$error .= '-- Product not found in website';
									}
									
									/*validate csv data row wise - starts*/
									if($error=='')
									{
										//-----------------------Validate Product Row - Starts-------------------
										if($comb_id == '')
										{
											$bulk_allow_prod = $bulk_allow;
											//validate Price & Bulk Discount Fields
											if($var_price)
											{
												if($price != 'N/A')
												{
													if ($error!='')
														$error .= '<br/>';
													$error .= '-- Price cannot be updated in this row';
												}
												//chnage made for new changes 
												if($bulk_allow=='Y')
										        {
													if($bulk_values != 'N/A')
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Bulk discount values cannot be updated in this row';
													}
											    }
											}
											else
											{
												if($price != '')
												{	
													if(!is_numeric($price))
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Price is not numeric --('.$price.')';
													}
												}
												$blkqty_arr = $blkprice_arr = $bulk_arr = array();
												if($bulk_allow=='Y')
										        {
												if($bulk_values!='')
												{
													$bulk_arr 	= explode(',',$bulk_values);
													// Check whether all the specified image ids are valid
													for ($i=0;$i<count($bulk_arr);$i++)
													{
														$temp_arr 		= explode('=>',$bulk_arr[$i]);
														if (!is_numeric(trim($temp_arr[0])) or !is_numeric(trim($temp_arr[1])))
														{
															if ($error!='')
																$error .= '<br/>';
															$error .='-- Numeric value required for Bulk Discount - ('.$temp_arr[0].' => '.$temp_arr[1].')';
														}
														else
														{
															if(strpos($temp_arr[0],'.')===false)
															{
															}
															else
															{
																if ($error!='')
																		$error .= '<br/>';
																	$error .='-- Bulk Discount Qty should not contain decimal points - ('.$temp_arr[0].')';
															}
															
																if(in_array(trim($temp_arr[0]),$blkqty_arr))
																{
																	if ($error!='')
																		$error .= '<br/>';
																	$error .='-- Bulk Discount Qty repeated - ('.$temp_arr[0].')';
																}
														}	
														$blkqty_arr[] 	= trim($temp_arr[0]);
														$blkprice_arr[]	= trim($temp_arr[1]);
													}
												}
												}	
											}
											//validate Stock Field
											if($var_stock)
											{
												if($stock != 'N/A')
												{
													if ($error!='')
														$error .= '<br/>';
													$error .= '-- Stock cannot be updated in this row';
												}	
											}
											else
											{
												if($stock != '')
												{	
													if(!is_numeric($stock))
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Stock is not numeric --('.$stock.')';
													}
												}
											}
											//validate Image Field
											if ($imageids!='')
											{
												$img_arr = explode('=>',$imageids);
												// Check whether all the specified image ids are valid
												for ($i=0;$i<count($img_arr);$i++)
												{
													$img_arr[$i] = trim($img_arr[$i]);
													if(!is_numeric($img_arr[$i]))
													{
														if ($error!='')
															$error .= '<br/>';
														$error .='-- Image Id '.$img_arr[$i].' is non numeric';
													}
													else
													{
														$sql_img_check = "SELECT image_id 
																			FROM 
																				images 
																			WHERE 
																				sites_site_id=$ecom_siteid 
																				AND image_id = '".$img_arr[$i]."' 
																			LIMIT 
																				1";
														$ret_img_check = $db->query($sql_img_check);
														if($db->num_rows($ret_img_check)==0)
														{
															if ($error!='')
																$error .= '<br/>';
															$error .='-- Image Id '.$img_arr[$i].' is not valid';
														
														}
														else
															$image_update = true;
													}	
												}
											}
											//modification for new changes latheesh
											//validate bulk allow,discount type,discount 
											   $array_disctyp = array('%','Value','Exact');
											   if(!in_array($discounttype,$array_disctyp))
												{
												   if ($error!='')
															$error .= '<br/>';
														$error .= '-- Discount Type Mismatch--('.$discounttype.')';
												}
											   if($discount != '')
												{	
													if(!is_numeric($discount))
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Discount is not numeric --('.$discount.')';
													}
												}
												$array_bulkallow = array('Y','N');
												if(!in_array($bulk_allow,$array_bulkallow))
												{
												   if ($error!='')
															$error .= '<br/>';
														$error .= '-- Allow Bulk Discount Mismatch--('.$bulk_allow.')';
												}	
										}
										//-----------------------Validate Product Row - Ends-------------------
																
										//-----------------------Validate Combination Row - Starts-------------------
										if($comb_id != '')
										{
											$comb_arr				= explode('-',$comb_id);
											if ($comb_arr[0] !='C' or count($comb_arr) !=2)
											{
												if ($error!='')
													$error .= '<br/>';
												$error .= '-- Invalid Combination Id Format --('.$comb_id.')';
											}
											else
											{
												if(!is_numeric($comb_arr[1]))
												{
													if ($error!='')
														$error .= '<br/>';
													$error .= '-- Invalid Combination Id --('.$comb_id.')';
												}
												else
												{
													$comb_id = $comb_arr[1];
													// Check whether current combination id is valid
													$sql_comb_check = "SELECT comb_id 
																			FROM 
																				product_variable_combination_stock  
																			WHERE 
																				comb_id= ".$comb_id." 
																				AND products_product_id =".$product_id." 
																			LIMIT 
																				1";
													$ret_comb_check = $db->query($sql_comb_check);
													if($db->num_rows($ret_comb_check)==0)
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Combination not found in site';
													}
												}
											}
											
										
											if($error=='')
											{
												// validate Price & Bulk Discount Fields
												if(!$var_price)
												{
													if($price != 'N/A')
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Price cannot be updated in this row';
													}
													if($bulk_allow_prod=='Y')
													{
													if($bulk_values != 'N/A')
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Bulk discount values cannot be updated in this row';
													}
													}
												}
												else
												{
													if($price != '')
													{	
														if(!is_numeric($price))
														{
															if ($error!='')
																$error .= '<br/>';
															$error .= '-- Price is not numeric --('.$price.')';
														}
													}
													$blkqty_arr = $blkprice_arr = $bulk_arr = array();
													//echo $bulk_allow_prod."+++".$bulk_values;exit;
													if($bulk_allow_prod=='Y')
													{
													if($bulk_values!='')
													{
														$bulk_arr 	= explode(',',$bulk_values);
														// Check whether all the specified image ids are valid
														for ($i=0;$i<count($bulk_arr);$i++)
														{
															$temp_arr 		= explode('=>',$bulk_arr[$i]);
															if (!is_numeric(trim($temp_arr[0])) or !is_numeric(trim($temp_arr[1])))
															{
																if ($error!='')
																	$error .= '<br/>';
																$error .='-- Numeric value required for Bulk Discount - ('.$temp_arr[0].' => '.$temp_arr[1].')';
															}
															else
															{
																if(strpos($temp_arr[0],'.')===false)
																{
																}
																else
																{
																	if ($error!='')
																			$error .= '<br/>';
																		$error .='-- Bulk Discount Qty should not contain decimal points - ('.$temp_arr[0].')';
																}
																if(in_array(trim($temp_arr[0]),$blkqty_arr))
																{
																	if ($error!='')
																		$error .= '<br/>';
																	$error .='-- Bulk Discount Qty repeated - ('.$temp_arr[0].')';
																}
															}	
															$blkqty_arr[] 	= trim($temp_arr[0]);
															$blkprice_arr[]	= trim($temp_arr[1]);
														}
													}
													}	
												}
												// validate Stock Field
												if(!$var_stock)
												{
													if($stock != 'N/A')
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Stock cannot be updated in this row';
													}	
												}
												else
												{
													if($stock != '')
													{	
														if(!is_numeric($stock))
														{
															if ($error!='')
																$error .= '<br/>';
															$error .= '-- Stock is not numeric --('.$stock.')';
														}
													}
												}
												
												// validate Image Field
												if(!$var_img)
												{
													if($imageids != 'N/A')
													{
														if ($error!='')
															$error .= '<br/>';
														$error .= '-- Images cannot be updated in this row';
													}	
												}
												else
												{
													$price_update = true;
													$img_arr = array();
													if ($imageids!='')
													{
														$img_arr = explode('=>',$imageids);
														// Check whether all the specified image ids are valid
														for ($i=0;$i<count($img_arr);$i++)
														{
															$img_arr[$i] = trim($img_arr[$i]);
															if(!is_numeric($img_arr[$i]))
															{
																if ($error!='')
																	$error .= '<br/>';
																$error .='-- Image Id '.$img_arr[$i].' is non numeric';
															}
															else
															{
																$sql_img_check = "SELECT image_id 
																					FROM 
																						images 
																					WHERE 
																						sites_site_id=$ecom_siteid 
																						AND image_id = '".$img_arr[$i]."' 
																					LIMIT 
																						1";
																$ret_img_check = $db->query($sql_img_check);
																if($db->num_rows($ret_img_check)==0)
																{
																	if ($error!='')
																		$error .= '<br/>';
																	$error .='-- Image Id '.$img_arr[$i].' is not valid';
																}
																else
																	$image_update = true;
															}	
														}
													}
												}
												
												
												// validate Var Id => Var Value Id Field (to check is there any changes done, for variable and its values after the CSV export)
												$var_id_arr = $var_value_id_arr = $var_id_var_value_id_arr = array();
												if($var_id_var_value_ids!='')
												{
													$var_id_var_value_id_arr 	= explode(',',$var_id_var_value_ids);
													if($error == '')
													{
															$var_id_var_value_id_cnt =count($var_id_var_value_id_arr);
															$sql_combdet = "SELECT product_variables_var_id 
															FROM 
																product_variable_combination_stock_details 
															WHERE 
																comb_id=".$comb_id." 
																AND products_product_id=".$product_id."";
															$ret_combdet = $db->query($sql_combdet);
															if($db->num_rows($ret_combdet) != $var_id_var_value_id_cnt)
															{
																if ($error!='')
																$error .= '<br/>';
																$error .='-- Issue on Stock Variable details - ('.$temp_arr1[0].' => '.$temp_arr1[1].')';	
															}
															
															$sql_var_val_exists_count = "SELECT COUNT(var_id) FROM product_variables WHERE var_value_exists=1 AND products_product_id=$product_id";
															$ret_var_val_exists_count = $db->query($sql_var_val_exists_count);
															$row_var_val_exists_count = $db->fetch_array($ret_var_val_exists_count);
															$var_val_exists_count     = $row_var_val_exists_count[0];
															
															
															if($var_id_var_value_id_cnt != $var_val_exists_count)
															{
																if ($error!='')
																$error .= '<br/>';
																$error .='-- Issue on Stock Variable details - ('.$temp_arr1[0].' => '.$temp_arr1[1].')';
															}	
													}

													// Check whether all the specified var,varval ids are valid
													for ($i=0;$i<count($var_id_var_value_id_arr);$i++)
													{
														$temp_arr1 		= explode('=>',$var_id_var_value_id_arr[$i]);
														if (!is_numeric(trim($temp_arr1[0])) or !is_numeric(trim($temp_arr1[1])))
														{
															if ($error!='')
																$error .= '<br/>';
															$error .='-- Numeric value required for both Var and Var Val Ids - ('.$temp_arr1[0].' => '.$temp_arr1[1].')';
														}
														else
														{
															if($error == '')
															{
																$sql_var_check = "SELECT var_id 
																					FROM 
																						product_variables 
																					WHERE 
																						 var_id = '".$temp_arr1[0]."'
																						  AND var_hide = '0' 
																						  AND products_product_id 	 = '".$product_id."' 
																					LIMIT 
																						1";
																$ret_var_check = $db->query($sql_var_check);
																if($db->num_rows($ret_var_check)==0)
																{
																	if ($error!='')
																		$error .= '<br/>';
																	$error .='-- Variable Id '.$temp_arr1[0].' is not valid';
																
																}
															}
														}	
														$var_id_arr[] 	= trim($temp_arr1[0]);
														$var_value_id_arr[]	= trim($temp_arr1[1]);
													}
												}
											}
										}
										//-----------------------Validate Combination Row - Ends-------------------
								}
										
									/*validate csv data row wise - ends*/
								}
								
							}
							
							$cls = ($rowcnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							if($error)
							{
								if ($bulk_values)
								{
									$bulk_arr = explode(',',$bulk_values);
									if(count($bulk_arr))
										$bulk_values = implode('<br>',$bulk_arr);
								}
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$rowcnt."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$combination."</td>";
								$table .="<td class='".$cls."'>".$barcode."</td>";
								if($special_product_code_req)
									$table .="<td class='".$cls."'>".$specialproductcode."</td>";
								$table .="<td class='".$cls."'>".$stock."</td>";
								$table .="<td class='".$cls."'>".$price."</td>";
								$table .="<td class='".$cls."'>".$discounttype."</td>";	
								$table .="<td class='".$cls."'>".$discount."</td>";		
								$table .="<td class='".$cls."'>".$imageids."</td>";	
								$table .="<td class='".$cls."'>".$bulk_allow."</td>";	

								$table .="<td class='".$cls."'>".$bulk_values."</td>";
								
								/*$table .="<td>".$product_id_format."</td>";
								$table .="<td>".$comb_id_format."</td>";*/
								/*$table .="<td>".$variable_stock_set."</td>";
								$table .="<td>".$variable_price_set."</td>";
								$table .="<td>".$variable_image_set."</td>";*/
								/*$table .="<td>".$var_id_var_value_ids."</td>";*/
								$table .="<td class='".$cls."' style='color:#FF0015;'>".$error."</td>";
								$table .="</tr>";
							}
							
							if($error != '')
							{
								$error_count++;
							}
							
							$rowcnt++;
						
						}
						
					$line ++;		
					}
					/*-----------------------  First loop for checking the errors in the csv file, if error occured in any of the row , that will be stored and finally it will display in table format  --------------------------- ends--*/
					
					
					fclose ($fp);
					$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
			        $fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
					
					if($error_count == 0)
					{
						$line = 0;
						$prev_product_id = '';	
						$comb_cnt = 0;
						
						/*-----------------------  Second loop for executing the product data updation, row by row ---------------------- starts--*/
						while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
						{
							//echo 'hai';
							$num 		= count($data);
							$cur_msg	= '';
							$error 		= '';
							if($line!=0)
							{		
								if($special_product_code_req)
								{
									$product_name			    = trim($data[0]);
									$combination				= trim($data[1]);
									$barcode					= trim($data[2]);
									$specialproductcode			= trim($data[3]);
									$stock						= trim($data[4]);
									$price						= trim($data[5]);
									$discounttype				= trim($data[6]);
									$discount					= trim($data[7]);
									//$bulk_values				= trim($data[7]);
									$imageids					= trim($data[8]);
									$bulk_allow                 = trim($data[9]);
									$bulk_qty1					= trim($data[10]);
									$bulk_price1				= trim($data[11]);
									$bulk_qty2					= trim($data[12]);
									$bulk_price2				= trim($data[13]);
									$bulk_qty3					= trim($data[14]);
									$bulk_price3				= trim($data[15]);
									$bulk_qty4					= trim($data[16]);
									$bulk_price4				= trim($data[17]);
									$bulk_qty5					= trim($data[18]);
									$bulk_price5				= trim($data[19]);
									$bulk_qty6					= trim($data[20]);
									$bulk_price6				= trim($data[21]);
									$bulk_qty7					= trim($data[22]);
									$bulk_price7				= trim($data[23]);
									$bulk_qty8					= trim($data[24]);
									$bulk_price8				= trim($data[25]);
									$bulk_qty9					= trim($data[26]);
									$bulk_price9				= trim($data[27]);
									$bulk_qty10					= trim($data[28]);
									$bulk_price10				= trim($data[29]);
									$bulk_qty11					= trim($data[30]);
									$bulk_price11				= trim($data[31]);
									$bulk_qty12					= trim($data[32]);
									$bulk_price12				= trim($data[33]);
									$bulk_qty13					= trim($data[34]);
									$bulk_price13				= trim($data[35]);
									$bulk_qty14					= trim($data[36]);
									$bulk_price14				= trim($data[37]);
									$bulk_qty15					= trim($data[38]);
									$bulk_price15				= trim($data[39]);
									$product_id					= trim($data[40]);
									$comb_id					= trim($data[41]);
									$variable_stock_set			= trim($data[42]);
									$variable_price_set			= trim($data[43]);
									$variable_image_set			= trim($data[44]);
									$var_id_var_value_ids		= trim($data[45]);

								}
								else
								{
									$product_name				= trim($data[0]);
								$combination				= trim($data[1]);
								$barcode					= trim($data[2]);
								$stock						= trim($data[3]);
								$price						= trim($data[4]);
								$discounttype				= trim($data[5]);
								$discount					= trim($data[6]);
								$imageids					= trim($data[7]);
								$bulk_allow					= trim($data[8]);
								//$bulk_values				= trim($data[7]);
								$bulk_qty1					= trim($data[9]);
								$bulk_price1				= trim($data[10]);
								$bulk_qty2					= trim($data[11]);
								$bulk_price2				= trim($data[12]);
								$bulk_qty3					= trim($data[13]);
								$bulk_price3				= trim($data[14]);
								$bulk_qty4					= trim($data[15]);
								$bulk_price4				= trim($data[16]);
								$bulk_qty5					= trim($data[17]);
								$bulk_price5				= trim($data[18]);
								$bulk_qty6					= trim($data[19]);
								$bulk_price6				= trim($data[20]);
								$bulk_qty7					= trim($data[21]);
								$bulk_price7				= trim($data[22]);
								$bulk_qty8					= trim($data[23]);
								$bulk_price8				= trim($data[24]);
								$bulk_qty9					= trim($data[25]);
								$bulk_price9				= trim($data[26]);
								$bulk_qty10					= trim($data[27]);
								$bulk_price10				= trim($data[28]);
								$bulk_qty11					= trim($data[29]);
								$bulk_price11				= trim($data[30]);
								$bulk_qty12					= trim($data[31]);
								$bulk_price12				= trim($data[32]);
								$bulk_qty13					= trim($data[33]);
								$bulk_price13				= trim($data[34]);
								$bulk_qty14					= trim($data[35]);
								$bulk_price14				= trim($data[36]);
								$bulk_qty15					= trim($data[37]);
								$bulk_price15				= trim($data[38]);
								$product_id					= trim($data[39]);
								$comb_id					= trim($data[40]);
								$variable_stock_set			= trim($data[41]);
								$variable_price_set			= trim($data[42]);
								$variable_image_set			= trim($data[43]);
								$var_id_var_value_ids		= trim($data[44]);			
									
									
								}
								// Generating the bulk discount string
								$bulk_values = '';
								if(($bulk_qty1=='N/A' and $bulk_price1=='N/A') and ($bulk_qty2=='N/A' and $bulk_price2=='N/A') and ($bulk_qty3=='N/A' and $bulk_price3=='N/A') 
								 and ($bulk_qty4=='N/A' and $bulk_price4=='N/A')  and ($bulk_qty5=='N/A' and $bulk_price5=='N/A') 
								 and ($bulk_qty6=='N/A' and $bulk_price6=='N/A') and ($bulk_qty7=='N/A' and $bulk_price7=='N/A')  and ($bulk_qty8=='N/A' and $bulk_price8=='N/A')
								 and ($bulk_qty9=='N/A' and $bulk_price9=='N/A') and ($bulk_qty10=='N/A' and $bulk_price10=='N/A')  and ($bulk_qty11=='N/A' and $bulk_price11=='N/A')
								 and ($bulk_qty12=='N/A' and $bulk_price12=='N/A') and ($bulk_qty13=='N/A' and $bulk_price13=='N/A') and ($bulk_qty14=='N/A' and $bulk_price14=='N/A') 
								 and ($bulk_qty15=='N/A' and $bulk_price15=='N/A'))
								{
									$bulk_values = 'N/A'; 
								}
								else
								{	
									$bulk_values = build_bulk_str($bulk_qty1,$bulk_price1,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty2,$bulk_price2,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty3,$bulk_price3,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty4,$bulk_price4,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty5,$bulk_price5,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty6,$bulk_price6,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty7,$bulk_price7,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty8,$bulk_price8,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty9,$bulk_price9,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty10,$bulk_price10,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty11,$bulk_price11,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty12,$bulk_price12,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty13,$bulk_price13,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty14,$bulk_price14,$bulk_values);
									$bulk_values = build_bulk_str($bulk_qty15,$bulk_price15,$bulk_values);
								}						
								$var_stock = $var_price = $var_img = false;
														
								$product_arr					= explode('-',$product_id);
														
								$product_id = addslashes(trim($product_arr[1]));
								
								$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
															product_variablecomboprice_allowed, product_variablecombocommon_image_allowed, product_bulkdiscount_allowed 
													FROM 
														products
													WHERE 
														sites_site_id=$ecom_siteid 
														AND product_id = $product_id 
													LIMIT 
														1";	
								$ret_stock = $db->query($sql_stock);
								$var_stock_variables = 0;
								$update_main_stock = 1;
								if($db->num_rows($ret_stock))
								{
									$row_stock = $db->fetch_array($ret_stock);
									if($row_stock['product_variablestock_allowed']=='Y')
									{
										$var_stock = true;
										$var_stock_variables = 1;
										$update_main_stock = 0;
									}
									if($row_stock['product_variablecomboprice_allowed']=='Y')
									{
										$var_price = true;	
										$var_stock_variables = 1;
									}
									if($row_stock['product_variablecombocommon_image_allowed']=='Y')
									{
										$var_img = true;	
										$var_stock_variables = 1;
									}
										
								}
								$blkqty_arr = $blkprice_arr = $bulk_arr = array();
								$img_arr = array();
					            
					            //-------------------------------- Update Product Row Details ------------- Starts -----------
								if($comb_id == '')
								{
											$bulk_allow_prod = $bulk_allow;

									$add_spcondition = '';
									//bulk disc
									if(!$var_price)
									{
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
										if($bulk_values!='')
										{
											$bulk_arr 	= explode(',',$bulk_values);
											for ($i=0;$i<count($bulk_arr);$i++)
											{
												$temp_arr 		= explode('=>',$bulk_arr[$i]);
												$blkqty_arr[] 	= trim($temp_arr[0]);
												$blkprice_arr[]	= trim($temp_arr[1]);
											}
										}
									}
									//image
									$img_arr = array();
									if ($imageids!='')
									{
										$img_arr = explode('=>',$imageids);
										for ($i=0;$i<count($img_arr);$i++)
										{
											$img_arr[$i] = trim($img_arr[$i]);
												
										}
									}
									if($barcode != 'N/A')
									{
										$add_spcondition = " product_barcode = '".addslashes($barcode)."'" ;
									}
									
									if($special_product_code_req)
									{
										if($specialproductcode != 'N/A')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_special_product_code = '".addslashes($specialproductcode)."'" ;
										}
									}
									
									
									if(!$var_stock)
									{
										if($stock != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_webstock = $stock ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_webstock = '0'";
											
										}
									}
									if(!$var_price)
									{
										if($price != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_webprice = $price ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_webprice = '0.00'";
										}
									}
									if($discounttype!='')
									{
									   $disc_ent = 0;
									   if($discounttype=='%')
									   $disc_ent = 0;
									   elseif($discounttype=='Value')
									   $disc_ent = 1;
									   elseif($discounttype=='Exact')
									   $disc_ent = 2;
									  if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_discount_enteredasval = $disc_ent";
									}
									if($discount!='')
									{
									   if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_discount = $discount";
									}
									if($bulk_allow!='')
									{
									   if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " product_bulkdiscount_allowed = '$bulk_allow'";
									}
									//Update the product_variable_combination_stock table with the details
									if($add_spcondition != '')
									{
									$update_sql = "UPDATE 
														products  
													SET 
														$add_spcondition  
													WHERE 
														 product_id = ".$product_id."
													LIMIT 
														1";
									$db->query($update_sql);
									}
									
									// handling the case of bulk discount
									
									if(!$var_price)
									{
										if($bulk_allow=='Y')
										{
											if(count($blkqty_arr))
											{
												$bulk_exists = true;
												$blk_cnt = count($blkqty_arr);
												$del_bulk = false;
												for ($j=0;$j<$blk_cnt;$j++)
												{
													if($blkqty_arr[$j]>0 AND $blkprice_arr[$j]>0)
													{
													  $del_bulk = true;
													}
												}
												// sorting the bulk discount details based on value of qty
												for($i=0;$i<ceil($blk_cnt/2);$i++)
												{
													for ($j=$i;$j<$blk_cnt;$j++)
													{
														if ($blkqty_arr[$i]>$blkqty_arr[$j])
														{
															$tempqty 			= $blkqty_arr[$j];
															$tempprc 			= $blkprice_arr[$j];
															
															$blkqty_arr[$j] 	= $blkqty_arr[$i];
															$blkprice_arr[$j]	= $blkprice_arr[$i];
															
															$blkqty_arr[$i] 	= $tempqty;
															$blkprice_arr[$i]	= $tempprc;
														}
													}
												}
												if($del_bulk==true)
												{
												// delete all combination bulk discounts
												 $sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id = ".$product_id."
																	AND comb_id = 0";
												$db->query($sel_del);
												}
												else
												{
													if(!$var_price)
													{
													//changes for new modification latheesh
													$update_bulkallow = "UPDATE
																			products 
																	SET 
																		product_bulkdiscount_allowed ='N' 
																	WHERE
																		product_id = $product_id AND sites_site_id=".$ecom_siteid." LIMIT 1";
																$db->query($update_bulkallow);	
													}				
												}
												for($i=0;$i<count($blkqty_arr);$i++)
												{
													$atleast_one_bulk							= true;
													$insert_array								= array();
													$insert_array['products_product_id']		= $product_id;
													$insert_array['comb_id']					= 0;
													$insert_array['bulk_qty']					= $blkqty_arr[$i];
													$insert_array['bulk_price']					= $blkprice_arr[$i];
													$db->insert_from_array($insert_array,'product_bulkdiscount');
												}
											}
											else
											{
												/*$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=0";
												$db->query($sel_del);
												*/
													//changes for new modification latheesh
													if(!$var_price)
													{
														$update_bulkallow = "UPDATE
																				products 
																			SET 
																				product_bulkdiscount_allowed ='N' 
																			WHERE
																				product_id = $product_id AND sites_site_id=".$ecom_siteid." LIMIT 1";
																$db->query($update_bulkallow); 
													}			
											}
									}
								 }
									// Handling the case of images ids
									if(count($img_arr))
									{
										for ($i=0;$i<count($img_arr);$i++)
										{
											$img_arr[$i] = trim($img_arr[$i]);
											// Check whether the image already assigned for current combination
											$sql_check = "SELECT id 
															FROM 
																images_product    
															WHERE 
																images_image_id=".$img_arr[$i]."  
																AND products_product_id=".$product_id."  
															LIMIT 
																1";
											$ret_check = $db->query($sql_check);
											if($db->num_rows($ret_check)==0)
											{
												$sql_img_check = "SELECT image_title  
																FROM 
																	images 
																WHERE 
																	sites_site_id=$ecom_siteid 
																	ANd image_id = ".$img_arr[$i]." 
																LIMIT 
																	1";
												$ret_img_check = $db->query($sql_img_check);
												if($db->num_rows($ret_img_check))
												{
													$row_img_check = $db->fetch_array($ret_img_check);
												}
												$insert_array 							= array();
												$insert_array['products_product_id']	= $product_id;
												$insert_array['images_image_id']		= $img_arr[$i];
												$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
												$insert_array['image_order']			= 0;
												$db->insert_from_array($insert_array,'images_product');										
											}
										}
										$img_str = implode(',',$img_arr);
										$sql_del = "DELETE FROM 
														images_product  
													WHERE 
														products_product_id = ".$product_id." 
														AND images_image_id NOT IN ($img_str) ";
										$db->query($sql_del);
									}
									else // case if image ids not specified
									{
										$sql_del = "DELETE FROM 
														images_product  
													WHERE 
														products_product_id = ".$product_id."";
										$db->query($sql_del);
										
									}	
									
									if($atleast_one_bulk)
									{
										// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
										$update_prd = "UPDATE products 
															SET 
																product_bulkdiscount_allowed = 'Y' 
															WHERE 
																product_id = $product_id 
																AND sites_site_id = $ecom_siteid 
															LIMIT 
																1";
										//$db->query($update_prd);
									}	
												
									
								} //-------------------------------- Update Product Row Details ------------- Ends -----------
								
								//-------------------------------- Update Combination Row Details ------------- Starts -----------
								else
								{
								  $add_spcondition = '';
									$blkqty_arr = $blkprice_arr = $bulk_arr = array();
									$img_arr = array();	
									if($var_stock and $var_price and $var_img) //------------111------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										//bulk disc
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
										if($bulk_values!='')
										{
											$bulk_arr 	= explode(',',$bulk_values);
											for ($i=0;$i<count($bulk_arr);$i++)
											{
												$temp_arr 		= explode('=>',$bulk_arr[$i]);
												$blkqty_arr[] 	= trim($temp_arr[0]);
												$blkprice_arr[]	= trim($temp_arr[1]);
											}
										}	
										//image
										$img_arr = array();
										if ($imageids!='')
										{
											$img_arr = explode('=>',$imageids);
											for ($i=0;$i<count($img_arr);$i++)
											{
												$img_arr[$i] = trim($img_arr[$i]);
													
											}
										}
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;
										}
										if($stock != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = '$stock '";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = '0'";
										}
										if($price != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = '$price '";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = '0.00'";
										}
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."  
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										// handling the case of bulk discount
										if($var_price)
										{
											//changes for new modification latheesh $row_stock['product_bulkdiscount_allowed']
											if($bulk_allow_prod=='Y')
										    {
											if(count($blkqty_arr))
											{
												$bulk_exists = true;
												$blk_cnt = count($blkqty_arr);
												//Mchanges for new modification latheesh
												for ($j=0;$j<$blk_cnt;$j++)
												{
													if($blkqty_arr[$j]>0 AND $blkprice_arr[$j]>0)
													{
													  $del_bulk = true;
													}
												}
												// sorting the bulk discount details based on value of qty
												for($i=0;$i<ceil($blk_cnt/2);$i++)
												{
													for ($j=$i;$j<$blk_cnt;$j++)
													{
														if ($blkqty_arr[$i]>$blkqty_arr[$j])
														{
															$tempqty 			= $blkqty_arr[$j];
															$tempprc 			= $blkprice_arr[$j];
															
															$blkqty_arr[$j] 	= $blkqty_arr[$i];
															$blkprice_arr[$j]	= $blkprice_arr[$i];
															
															$blkqty_arr[$i] 	= $tempqty;
															$blkprice_arr[$i]	= $tempprc;
														}
													}
												}
												// delete all combination bulk discounts
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												for($i=0;$i<count($blkqty_arr);$i++)
												{
													$atleast_one_bulk							= true;
													$insert_array								= array();
													$insert_array['products_product_id']		= $product_id;
													$insert_array['comb_id']					= $comb_id;
													$insert_array['bulk_qty']					= $blkqty_arr[$i];
													$insert_array['bulk_price']					= $blkprice_arr[$i];
													$db->insert_from_array($insert_array,'product_bulkdiscount');
												}
											}
											else
											{
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);																								
											}
											}
										}
										if($var_img)
										{	
											// Handling the case of images ids
											if(count($img_arr))
											{
												for ($i=0;$i<count($img_arr);$i++)
												{
													$img_arr[$i] = trim($img_arr[$i]);
													// Check whether the image already assigned for current combination
													$sql_check = "SELECT id 
																	FROM 
																		images_variable_combination    
																	WHERE 
																		images_image_id=".$img_arr[$i]."  
																		AND comb_id=".$comb_id."   
																	LIMIT 
																		1";
													$ret_check = $db->query($sql_check);
													if($db->num_rows($ret_check)==0)
													{
														$sql_img_check = "SELECT image_title  
																		FROM 
																			images 
																		WHERE 
																			sites_site_id=$ecom_siteid 
																			ANd image_id = ".$img_arr[$i]." 
																		LIMIT 
																			1";
														$ret_img_check = $db->query($sql_img_check);
														if($db->num_rows($ret_img_check))
														{
															$row_img_check = $db->fetch_array($ret_img_check);
														}
														$insert_array 							= array();
														$insert_array['comb_id']				= $comb_id;
														$insert_array['images_image_id']		= $img_arr[$i];
														$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
														$insert_array['image_order']			= 0;
														$db->insert_from_array($insert_array,'images_variable_combination');										
													}
												}
												$img_str = implode(',',$img_arr);
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = ".$comb_id." 
																AND images_image_id NOT IN ($img_str) ";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 1  
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);			
											}
											else // case if image ids not specified
											{
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = ".$comb_id."";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 0 
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);
											}	
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
											
									}
									
									if(!$var_stock and $var_price and $var_img) //------------011------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
																			
										//bulk disc
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
										if($bulk_values!='')
										{
											$bulk_arr 	= explode(',',$bulk_values);
											for ($i=0;$i<count($bulk_arr);$i++)
											{
												$temp_arr 		= explode('=>',$bulk_arr[$i]);
												$blkqty_arr[] 	= trim($temp_arr[0]);
												$blkprice_arr[]	= trim($temp_arr[1]);
											}
										}	
									
										//image
										$img_arr = array();
										if ($imageids!='')
										{
											$img_arr = explode('=>',$imageids);
											for ($i=0;$i<count($img_arr);$i++)
											{
												$img_arr[$i] = trim($img_arr[$i]);
													
											}
										}
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;

										if($price != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = $price ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = '0.00'";
										}
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										// handling the case of bulk discount
										if($var_price)
										{
											//change for new modification latheesh
											if($bulk_allow_prod=='Y')
											{
											if(count($blkqty_arr))
											{
												$bulk_exists = true;
												$blk_cnt = count($blkqty_arr);
												// sorting the bulk discount details based on value of qty
												for($i=0;$i<ceil($blk_cnt/2);$i++)
												{
													for ($j=$i;$j<$blk_cnt;$j++)
													{
														if ($blkqty_arr[$i]>$blkqty_arr[$j])
														{
															$tempqty 			= $blkqty_arr[$j];
															$tempprc 			= $blkprice_arr[$j];
															
															$blkqty_arr[$j] 	= $blkqty_arr[$i];
															$blkprice_arr[$j]	= $blkprice_arr[$i];
															
															$blkqty_arr[$i] 	= $tempqty;
															$blkprice_arr[$i]	= $tempprc;
														}
													}
												}
												// delete all combination bulk discounts
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												for($i=0;$i<count($blkqty_arr);$i++)
												{
													$atleast_one_bulk							= true;
													$insert_array								= array();
													$insert_array['products_product_id']		= $product_id;
													$insert_array['comb_id']					= $comb_id;
													$insert_array['bulk_qty']					= $blkqty_arr[$i];
													$insert_array['bulk_price']					= $blkprice_arr[$i];
													$db->insert_from_array($insert_array,'product_bulkdiscount');
												}
											}
											else
											{
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												 
												 
											}
											}
										}
										if($var_img)
										{	
											// Handling the case of images ids
											if(count($img_arr))
											{
												for ($i=0;$i<count($img_arr);$i++)
												{
													$img_arr[$i] = trim($img_arr[$i]);
													// Check whether the image already assigned for current combination
													$sql_check = "SELECT id 
																	FROM 
																		images_variable_combination    
																	WHERE 
																		images_image_id=".$img_arr[$i]." 
																		AND comb_id=".$comb_id."   
																	LIMIT 
																		1";
													$ret_check = $db->query($sql_check);
													if($db->num_rows($ret_check)==0)
													{
														$sql_img_check = "SELECT image_title  
																		FROM 
																			images 
																		WHERE 
																			sites_site_id=$ecom_siteid 
																			ANd image_id = ".$img_arr[$i]." 
																		LIMIT 
																			1";
														$ret_img_check = $db->query($sql_img_check);
														if($db->num_rows($ret_img_check))
														{
															$row_img_check = $db->fetch_array($ret_img_check);
														}
														
														$insert_array 							= array();
														$insert_array['comb_id']				= $comb_id;
														$insert_array['images_image_id']		= $img_arr[$i];
														$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
														$insert_array['image_order']			= 0;
														$db->insert_from_array($insert_array,'images_variable_combination');										
													}
												}
												$img_str = implode(',',$img_arr);
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = '".$comb_id."' 
																AND images_image_id NOT IN ($img_str) ";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 1  
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);			
											}
											else // case if image ids not specified
											{
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = ".$comb_id."";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 0 
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);
											}	
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}		
									}
									
									if($var_stock and !$var_price and $var_img) //------------101------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id."
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										
										//image
										$img_arr = array();
										if ($imageids!='')
										{
											$img_arr = explode('=>',$imageids);
											for ($i=0;$i<count($img_arr);$i++)
											{
												$img_arr[$i] = trim($img_arr[$i]);
													
											}
										}
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;	
										}
										if($stock != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = $stock ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = '0'";	
										}
										
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										// handling the case of bulk discount
										
										if($var_img)
										{	
											// Handling the case of images ids
											if(count($img_arr))
											{
												for ($i=0;$i<count($img_arr);$i++)
												{
													$img_arr[$i] = trim($img_arr[$i]);
													// Check whether the image already assigned for current combination
													$sql_check = "SELECT id 
																	FROM 
																		images_variable_combination    
																	WHERE 
																		images_image_id=".$img_arr[$i]."  
																		AND comb_id=".$comb_id."   
																	LIMIT 
																		1";
													$ret_check = $db->query($sql_check);
													if($db->num_rows($ret_check)==0)
													{
														$sql_img_check = "SELECT image_title  
																		FROM 
																			images 
																		WHERE 
																			sites_site_id=$ecom_siteid 
																			ANd image_id = ".$img_arr[$i]." 
																		LIMIT 
																			1";
														$ret_img_check = $db->query($sql_img_check);
														if($db->num_rows($ret_img_check))
														{
															$row_img_check = $db->fetch_array($ret_img_check);
														}
														$insert_array 							= array();
														$insert_array['comb_id']				= $comb_id;
														$insert_array['images_image_id']		= $img_arr[$i];
														$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
														$insert_array['image_order']			= 0;
														$db->insert_from_array($insert_array,'images_variable_combination');										
													}
												}
												$img_str = implode(',',$img_arr);
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = '".$comb_id."' 
																AND images_image_id NOT IN ($img_str) ";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 1  
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);			
											}
											else // case if image ids not specified
											{
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = ".$comb_id."";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 0 
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);
											}	
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
									}
									
									if($var_stock and $var_price and !$var_img) //------------110------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										//bulk disc
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
										if($bulk_values!='')
										{
											$bulk_arr 	= explode(',',$bulk_values);
											for ($i=0;$i<count($bulk_arr);$i++)
											{
												$temp_arr 		= explode('=>',$bulk_arr[$i]);
												$blkqty_arr[] 	= trim($temp_arr[0]);
												$blkprice_arr[]	= trim($temp_arr[1]);
											}
										}	
										
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;
										}
										if($stock != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = $stock ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = '0'";
											
										}
										if($price != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = $price ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = '0.00'";
										}
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										
										// handling the case of bulk discount
										if($var_price)
										{
											//changes for new modification latheesh
											if($bulk_allow_prod=='Y')
										   {
											if(count($blkqty_arr))
											{
												$bulk_exists = true;
												$blk_cnt = count($blkqty_arr);
												// sorting the bulk discount details based on value of qty
												for($i=0;$i<ceil($blk_cnt/2);$i++)
												{
													for ($j=$i;$j<$blk_cnt;$j++)
													{
														if ($blkqty_arr[$i]>$blkqty_arr[$j])
														{
															$tempqty 			= $blkqty_arr[$j];
															$tempprc 			= $blkprice_arr[$j];
															
															$blkqty_arr[$j] 	= $blkqty_arr[$i];
															$blkprice_arr[$j]	= $blkprice_arr[$i];
															
															$blkqty_arr[$i] 	= $tempqty;
															$blkprice_arr[$i]	= $tempprc;
														}
													}
												}
												// delete all combination bulk discounts
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												for($i=0;$i<count($blkqty_arr);$i++)
												{
													$atleast_one_bulk							= true;
													$insert_array								= array();
													$insert_array['products_product_id']		= $product_id;
													$insert_array['comb_id']					= $comb_id;
													$insert_array['bulk_qty']					= $blkqty_arr[$i];
													$insert_array['bulk_price']					= $blkprice_arr[$i];
													$db->insert_from_array($insert_array,'product_bulkdiscount');
												}
											}
											else
											{
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												
												
											}
										   }
										}

										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
									}
									
									if(!$var_stock and !$var_price and $var_img) //------------001------------
									{
										
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id."
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										//image
										$img_arr = array();
										if ($imageids!='')
										{
											$img_arr = explode('=>',$imageids);
											for ($i=0;$i<count($img_arr);$i++)
											{
												$img_arr[$i] = trim($img_arr[$i]);
													
											}
										}
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;
										}
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										if($var_img)
										{	
											// Handling the case of images ids
											if(count($img_arr))
											{
												for ($i=0;$i<count($img_arr);$i++)
												{
													$img_arr[$i] = trim($img_arr[$i]);
													// Check whether the image already assigned for current combination
													$sql_check = "SELECT id 
																	FROM 
																		images_variable_combination    
																	WHERE 
																		images_image_id=".$img_arr[$i]."  
																		AND comb_id=".$comb_id."  
																	LIMIT 
																		1";
													$ret_check = $db->query($sql_check);
													if($db->num_rows($ret_check)==0)
													{
														$sql_img_check = "SELECT image_title  
																		FROM 
																			images 
																		WHERE 
																			sites_site_id=$ecom_siteid 
																			ANd image_id = ".$img_arr[$i]." 
																		LIMIT 
																			1";
														$ret_img_check = $db->query($sql_img_check);
														if($db->num_rows($ret_img_check))
														{
															$row_img_check = $db->fetch_array($ret_img_check);
														}
														$insert_array 							= array();
														$insert_array['comb_id']				= $comb_id;
														$insert_array['images_image_id']		= $img_arr[$i];
														$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
														$insert_array['image_order']			= 0;
														$db->insert_from_array($insert_array,'images_variable_combination');										
													}
												}
												$img_str = implode(',',$img_arr);
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = '".$comb_id."' 
																AND images_image_id NOT IN ($img_str) ";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 1  
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);			
											}
											else // case if image ids not specified
											{
												$sql_del = "DELETE FROM 
																images_variable_combination  
															WHERE 
																comb_id = '".$comb_id."'";
												$db->query($sql_del);
												$update_comb = "UPDATE 
																	product_variable_combination_stock 
																SET 
																	comb_img_assigned = 0 
																WHERE 
																	comb_id = ".$comb_id."  
																LIMIT 
																	1";
												$db->query($update_comb);
											}	
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
									}	
									
									if(!$var_stock and $var_price and !$var_img) //------------010------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										//bulk disc
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
										if($bulk_values!='')
										{
											$bulk_arr 	= explode(',',$bulk_values);
											for ($i=0;$i<count($bulk_arr);$i++)
											{
												$temp_arr 		= explode('=>',$bulk_arr[$i]);
												$blkqty_arr[] 	= trim($temp_arr[0]);
												$blkprice_arr[]	= trim($temp_arr[1]);
											}
										}	
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
													$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;		
										}
										if($price != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = $price ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_price = '0.00'";
										}
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id." 
														LIMIT 
															1";
										$db->query($update_sql);
										}
										// handling the case of bulk discount
										if($var_price)
										{
											//changes for new modification latheesh
											if($bulk_allow_prod=='Y')
										    {
											if(count($blkqty_arr))
											{
												$bulk_exists = true;
												$blk_cnt = count($blkqty_arr);
												// sorting the bulk discount details based on value of qty
												for($i=0;$i<ceil($blk_cnt/2);$i++)
												{
													for ($j=$i;$j<$blk_cnt;$j++)
													{
														if ($blkqty_arr[$i]>$blkqty_arr[$j])
														{
															$tempqty 			= $blkqty_arr[$j];
															$tempprc 			= $blkprice_arr[$j];
															
															$blkqty_arr[$j] 	= $blkqty_arr[$i];
															$blkprice_arr[$j]	= $blkprice_arr[$i];
															
															$blkqty_arr[$i] 	= $tempqty;
															$blkprice_arr[$i]	= $tempprc;
														}
													}
												}
												// delete all combination bulk discounts
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												for($i=0;$i<count($blkqty_arr);$i++)
												{
													$atleast_one_bulk							= true;
													$insert_array								= array();
													$insert_array['products_product_id']		= $product_id;
													$insert_array['comb_id']					= $comb_id;
													$insert_array['bulk_qty']					= $blkqty_arr[$i];
													$insert_array['bulk_price']					= $blkprice_arr[$i];
													$db->insert_from_array($insert_array,'product_bulkdiscount');
												}
											}
											else
											{
												
												$sel_del = "DELETE FROM product_bulkdiscount 
																WHERE 
																	products_product_id=".$product_id." 
																	AND comb_id=".$comb_id."";
												$db->query($sel_del);
												
												   
											}
										   }
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
									}	
										
									if($var_stock and !$var_price and !$var_img) //------------100------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check)==0)
										{
											echo '-- Combination not found in site---'.$comb_id;
										}
										$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
										if($special_product_code_req)
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " comb_special_product_code = '".addslashes($specialproductcode)."'" ;	
										}
										if($stock != '')
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = $stock ";
										}
										else
										{
											if($add_spcondition!='')
												$add_spcondition .= ',';
											$add_spcondition .= " web_stock = '0'";
										}
										
										//Update the product_variable_combination_stock table with the details
										if($add_spcondition !='')
										{
										$update_sql = "UPDATE 
															product_variable_combination_stock  
														SET 
															$add_spcondition  
														WHERE 
															comb_id = ".$comb_id."   
															AND products_product_id = ".$product_id."
														LIMIT 
															1";
										$db->query($update_sql);
										}
										if($atleast_one_bulk)
										{
											// update the product_bulkdiscount_allowed field with Y if it is not Y for the current product as bulk discount specified 
											$update_prd = "UPDATE products 
																SET 
																	product_bulkdiscount_allowed = 'Y' 
																WHERE 
																	product_id = $product_id 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
											//$db->query($update_prd);
										}	
									}
									
									if(!$var_stock and !$var_price and !$var_img) //------------000------------
									{
										$comb_arr				= explode('-',$comb_id);
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= ".$comb_id." 
																	AND products_product_id =".$product_id." 
																LIMIT 
																	1";
										$ret_comb_check = $db->query($sql_comb_check);
										if($db->num_rows($ret_comb_check))
										{
											//checking the stock details exists in cmb table, if 3 of the check box unticked
											$sql_var_val_exists_count = "SELECT COUNT(*) FROM product_variables WHERE var_value_exists=1 AND products_product_id=$product_id";
											$ret_var_val_exists_count = $db->query($sql_var_val_exists_count);
											$row_var_val_exists_count = $db->fetch_array($ret_var_val_exists_count);
											$var_val_exists_count     = $row_var_val_exists_count[0];
											
											$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
													FROM 
														product_variable_combination_stock_details 
													WHERE 
														comb_id=".$comb_id." 
														AND products_product_id=".$product_id."";
											$ret_combdet = $db->query($sql_combdet);
								
											if($db->num_rows($ret_combdet) == $var_val_exists_count)
											{
												if($special_product_code_req)
												{
													$add_spcondition = ", comb_special_product_code = '".addslashes($specialproductcode)."'" ;	
												}
												else
													$add_spcondition = '';
												$update_sql = "UPDATE 
																product_variable_combination_stock  
															SET 
																comb_barcode = '".addslashes($barcode)."' 
																$add_spcondition 
															WHERE 
																comb_id = ".$comb_id."   
																AND products_product_id = ".$product_id." 
															LIMIT 
																1";
												$db->query($update_sql);
											}
										}
									
									}
								$comb_cnt ++;	
									
							}
							//-------------------------------- Update Combination Row Details ------------- Ends -----------
							
							if($prev_product_id == '')
							{
								$prev_product_id = $product_id;
								//$prev_product_name = $product_name;
							}
							if($prev_product_id != $product_id)
							{
								//echo 'hai'.$prev_product_id.' -- '.$prev_product_name.'<br>';
								// Calling the function to recalculate the actual stock, in case if any modification happened to stock
								recalculate_actual_stock($prev_product_id);
								// Check whether product_variablecomboprice_allowed option is active for current product
								handle_default_comp_price_and_id($prev_product_id);
								// calling function which decides and write barcodes in product keywords field based on general settings
								handle_barcode($prev_product_id);

								$prev_product_id = $product_id;
								//$prev_product_name = $product_name;
							}
							
							// Calling the function to recalculate the actual stock, in case if any modification happened to stock
							//recalculate_actual_stock($product_id);
							// Check whether product_variablecomboprice_allowed option is active for current product
							//handle_default_comp_price_and_id($product_id);
							// calling function which decides and write barcodes in product keywords field based on general settings
							//handle_barcode($product_id);
							}	
						$line ++;
								
						}
						
						/*for last row*/
						// Calling the function to recalculate the actual stock, in case if any modification happened to stock
						recalculate_actual_stock($product_id);
						// Check whether product_variablecomboprice_allowed option is active for current product
						handle_default_comp_price_and_id($product_id);
						// calling function which decides and write barcodes in product keywords field based on general settings
						handle_barcode($product_id);
						
						
						/*-----------------------  Second loop for executing the product data updation, row by row ---------------------- ends--*/
						$sucess .= '<br/><br/><div class="redtext" width="100%" align="center"><strong>Product Update Operation Completed Successfully</strong></div>';
						
					}	
			}
		}
		$table .="</table>";
		if($sucess != '')
		{
			$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
			$sucess =	$css.$sucess;
			$sucess .=	$go_back;
			echo $sucess;
		}
		elseif($error_outer!='')
		{
			$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
			$error_outer = '<br/><br/><div class="redtext" width="100%" align="center"><strong>'.$error_outer.'</strong></div>';
			$error_outer =	$css.$error_outer;
			$error_outer .=	$go_back;
			echo $error_outer;
		}
		else
		{
				$table .=	$go_back;
				echo $table;
		}
	}
	
    function handle_barcode($product_id)
	{
		global $db,$ecom_siteid;
		// check whether barcode is to be saved in product_keywords field for the current product
		$sql_gen = "SELECT add_barcode_to_product_keyword   
						FROM 
							general_settings_sites_common_onoff 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_gen = $db->query($sql_gen);
		if($db->num_rows($ret_gen))
		{
			$row_gen = $db->fetch_array($ret_gen);
			if($row_gen['add_barcode_to_product_keyword']==1)
			{
				$sql_prod = "SELECT product_barcode,product_variables_exists,product_keywords  
								FROM 
									products 
								WHERE 
									product_id = $product_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					$barcode_str = '';
					$keywords = stripslashes($row_prod['product_keywords']);
					$variable_exists = false;
					// Check whether there exists atleast one variable with values
					$sql_check = "SELECT var_id 
									FROM 
										product_variables 
									WHERE 
										products_product_id = $product_id 
										AND var_value_exists=1 
									LIMIT 
										1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))
					{
						$variable_exists = true; 
					}
					if($variable_exists)
					{
						$sql_comb = "SELECT comb_barcode 
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = $product_id 
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if (trim($row_comb['comb_barcode'])!='')
								{
									if($barcode_str!='')
										$barcode_str .= ',';
									$barcode_str .= stripslashes($row_comb['comb_barcode']);	
								}
							}
						}					
					}
					else
					{
						$barcode_str = stripslashes($row_prod['product_barcode']);
					}
					// removing the content within keywords for current product
					$keywords = preg_replace('#<barcodes>(.*?)</barcodes>#', '', $keywords);
					// removing the tag itself
					$sr_arr = array('<barcodes>','</barcodes>');
					$rp_arr = array('','');
					//$keywords = str_replace($sr_arr,$rp_arr,$keywords);
					if($barcode_str!='')
					{
						$keywords = '<barcodes>'.$barcode_str.'</barcodes>'.$keywords;
					}
					$sql_update = "UPDATE products 
									SET 
										product_keywords = '".$keywords."'  
									WHERE 
										product_id = $product_id 
									LIMIT 
										1";
					$db->query($sql_update);
				}
			}
		}
	}
	function build_bulk_str($qty,$price,$str)
	{
		if($qty or $price)
		{
			if($price!='N/A' and $qty != 'N/A')
			{
				if ($str!='')
				{
					$str .= ',';
				}
				$str .= "$qty=>$price";
			}
		}
		return $str;
	}
	function check_image_exists($image_id)
	{
		global $ecom_siteid,$db;
		$sql_img = "SELECT image_id 
						FROM 
							images 
						WHERE 
							image_id = $image_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_img = $db->query($sql_img);
		if($db->num_rows($ret_img))
			return true;
		else
			return false;
	}
?>
