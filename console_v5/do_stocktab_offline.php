<?php
/*#################################################################
	# Script Name 		: do_stocktab_offline.php
	# Description 		: variables to be used in the database offline export and upload
	# Coded by 		: Sny
	# Created on		: 27-Aug-2010
	# Modified by		: Sny
	# Modified On		: 39-Aug-2010
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
		$var_prodcombstock_arr = array	(
											'product_name'								=> 'Product Name (Don\'t Modify)',
											'comb_name'									=> 'Variable Combination (Don\'t Modify)',
											'product_barcode'							=> 'Barcode',
											'product_varstock'							=> 'Stock',
											'product_id'								=> 'Product Id (Don\'t Modify)',
											'comb_id'									=> 'Combination Id (Don\'t Modify)'
										);
		// Array to used for combination price products
		$var_prodcombprice_arr = array	(
											'product_name'								=> 'Product Name (Don\'t Modify)',
											'comb_name'									=> 'Variable Combination (Don\'t Modify)',
											'product_barcode'							=> 'Barcode',
											'product_varprice'							=> 'Price',
											'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
											'product_id'								=> 'Product Id (Don\'t Modify)',
											'comb_id'									=> 'Combination Id (Don\'t Modify)'
										);
		// Array to used for combination Imageids products
		$var_prodcombimage_arr = array	(
											'product_name'								=> 'Product Name (Don\'t Modify)',
											'comb_name'									=> 'Variable Combination (Don\'t Modify)',
											'product_barcode'							=> 'Barcode',
											'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
											'product_id'								=> 'Product Id (Don\'t Modify)',
											'comb_id'									=> 'Combination Id (Don\'t Modify)'
										); 
		
		// Array to be used for combination stock and combination price
		$var_prodcombstockprice_arr	= array	(
												'product_name'								=> 'Product Name (Don\'t Modify)',
												'comb_name'									=> 'Variable Combination (Don\'t Modify)',
												'product_barcode'							=> 'Barcode',
												'product_varstock'							=> 'Stock',
												'product_varprice'							=> 'Price',
												'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
												'product_id'								=> 'Product Id (Don\'t Modify)',
												'comb_id'									=> 'Combination Id (Don\'t Modify)'
											);		
		// Array to be used for combination stock and combination price
		$var_prodcombstockimage_arr	= array	(
												'product_name'								=> 'Product Name (Don\'t Modify)',
												'comb_name'									=> 'Variable Combination (Don\'t Modify)',
												'product_barcode'							=> 'Barcode',
												'product_varstock'							=> 'Stock',
												'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
												'product_id'								=> 'Product Id (Don\'t Modify)',
												'comb_id'									=> 'Combination Id (Don\'t Modify)'
											);	
		
		// Array to be used for combination stock and combination price
		$var_prodcombpriceimage_arr		= array	(
													'product_name'								=> 'Product Name (Don\'t Modify)',
													'comb_name'									=> 'Variable Combination (Don\'t Modify)',
													'product_barcode'							=> 'Barcode',
													'product_varprice'							=> 'Price',
													'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
													'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
													'product_id'								=> 'Product Id (Don\'t Modify)',
													'comb_id'									=> 'Combination Id (Don\'t Modify)'
												);		
		
		// Array to be used for combination stock and combination price
		$var_prodcombstockpriceimage_arr= array	(
													'product_name'								=> 'Product Name (Don\'t Modify)',
													'comb_name'									=> 'Variable Combination (Don\'t Modify)',
													'product_barcode'							=> 'Barcode',
													'product_varstock'							=> 'Stock',
													'product_varprice'							=> 'Price',
													'product_bulkdiscount_value'				=> 'Bulk Discount Values (Qty1=>Price1,Qty2=>Price2)',
													'product_image_ids'							=> 'Unique Ids of Images from Image gallery. Seperate Id\'s using \'=>\' . Already assigned Images which are not specified in this column will be unassigned automatically before saving the current id\'s',
													'product_id'								=> 'Product Id (Don\'t Modify)',
													'comb_id'									=> 'Combination Id (Don\'t Modify)'
												);		

	if($_REQUEST['cur_mod']=='stock_download') // case of download
	{
		$cur_prodid = $_REQUEST['prod_id'];
		$additional_condition = '';
		// get the main product details from products
		$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
							product_variablecomboprice_allowed, product_variablecombocommon_image_allowed   
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
			if($row_stock['product_variablestock_allowed']=='Y')
				$var_stock = true;
			if($row_stock['product_variablecomboprice_allowed']=='Y')
				$var_price = true;	
			if($row_stock['product_variablecombocommon_image_allowed']=='Y')
				$var_img = true;		
			
			if(!$var_stock and !$var_price and $var_img) //001
			{
				$file_prefix = 'Var_Image';
				$head_arr = $var_prodcombimage_arr;
			}	
			if(!$var_stock and $var_price and !$var_img) //010
			{
				$file_prefix = 'Var_Price';
				$head_arr = $var_prodcombprice_arr;
			}	
			if(!$var_stock and $var_price and $var_img) //011
			{
				$file_prefix = 'Var_Price_Image';
				$head_arr = $var_prodcombpriceimage_arr;
			}	
			if($var_stock and !$var_price and !$var_img) //100
			{
				$file_prefix = 'Var_Stock';
				$head_arr = $var_prodcombstock_arr;
			}	
			if($var_stock and !$var_price and $var_img) //101
			{
				$file_prefix = 'Var_Stock_Image';
				$head_arr = $var_prodcombstockimage_arr;
			}	
			if($var_stock and $var_price and !$var_img) //110
			{
				$file_prefix = 'Var_Stock_Price';
				$head_arr = $var_prodcombstockprice_arr;
			}	
			if($var_stock and $var_price and $var_img) //111
			{
				$file_prefix = 'Var_Stock_Price_Image';
				$head_arr = $var_prodcombstockpriceimage_arr;	
			}	
			$filename = 'Product_'.$file_prefix.'_'.$cur_prodid.'_'.date('d-m-Y');	
			
				
			header("Content-Type: text/plain");
			header("Content-Disposition: attachment; filename=$filename.csv");
			array_walk($head_arr, "add_quotes");
			print implode(",", $head_arr) . "\r\n";
			
			
			// Get the combination details
			$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned 
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
					if($prev_id==0)
					{
						$pname 		= stripslashes($row_stock['product_name']);
						$prev_id 	= 1;
					}
					else
						$pname 		=  '    "';
					$data['product_name'] 		= $pname;	
					$comb_name_arr 				= array();
					// Get the combination details
					$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
										FROM 
											product_variable_combination_stock_details 
										WHERE 
											comb_id=".$row_comb['comb_id']." 
											AND products_product_id='".$cur_prodid."'";
					$ret_combdet = $db->query($sql_combdet);
					if($db->num_rows($ret_combdet))
					{
						while ($row_combdet = $db->fetch_array($ret_combdet))
						{
							// Get the respective variable name
							$sql_varname = "SELECT var_name 
												FROM 
													product_variables 
												WHERE 
													var_id = '".$row_combdet['product_variables_var_id']."'  
												LIMIT 
													1";
							$ret_varname = $db->query($sql_varname);
							if($db->num_rows($ret_varname))
							{
								$row_varname = $db->fetch_array($ret_varname);											
							} 
							// Get the variable value details
							$sql_varvalname = "SELECT var_value  
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = '".$row_combdet['product_variables_var_id']."' 
													AND var_value_id = '".$row_combdet['product_variable_data_var_value_id']."'  
												LIMIT 
													1";
							$ret_varvalname = $db->query($sql_varvalname);
							if($db->num_rows($ret_varvalname))
							{
								$row_varvalname = $db->fetch_array($ret_varvalname);											
							} 
							$comb_name_arr[] = stripslashes($row_varname['var_name']).': '.stripslashes($row_varvalname['var_value']);
						}
					}
						
					$data['comb_name']						= implode(',',$comb_name_arr);
					$data['product_barcode'] 				= $row_comb['comb_barcode'];
					$data['product_varstock'] 				= $row_comb['web_stock'];
					$data['product_id'] 					= 'P-'.$cur_prodid;
					$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
					
					
					// combination price
					if($var_price)
					{
						$data['product_varprice'] 		= $row_comb['comb_price'];
						$bulk_arr = array();
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
								$data['product_bulkdiscount_value']	= implode(',',$bulk_arr);
							else
								$data['product_bulkdiscount_value'] = '';
						}
						else
							$data['product_bulkdiscount_value'] = '';
					}
					
					// Combination Image
					if($var_img) 
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
								$prod_img_arr[] = $row_img_id['images_image_id'];
							}
							if(count($prod_img_arr))
								$data['product_image_ids'] 	= implode('=>',$prod_img_arr);
							else
								$data['product_image_ids'] = '';
						}
						else
							$data['product_image_ids'] = '';
					}		
					
				
				
				
					$print_data = array();
					if(!$var_stock and !$var_price and $var_img) //001
					{
						$print_data 	= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												); 
					}	
					if(!$var_stock and $var_price and !$var_img) //010
					{
						$print_data	 	= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varprice'					=> $data['product_varprice'],
													'product_bulkdiscount_value'		=> $data['product_bulkdiscount_value'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);
					}
					if(!$var_stock and $var_price and $var_img) //011
					{
						$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varprice'					=> $data['product_varprice'],
													'product_bulkdiscount_value'		=> $data['product_bulkdiscount_value'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);	
					}
					if($var_stock and !$var_price and !$var_img) //100
					{
						$print_data	 	= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varstock'					=> $data['product_varstock'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);
					}
					if($var_stock and !$var_price and $var_img) //101
					{
						$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varstock'					=> $data['product_varstock'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);
					}
					if($var_stock and $var_price and !$var_img) //110
					{
						$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varstock'					=> $data['product_varstock'],
													'product_varprice'					=> $data['product_varprice'],
													'product_bulkdiscount_value'		=> $data['product_bulkdiscount_value'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);		
					}
					if($var_stock and $var_price and $var_img) //111
					{
						$print_data		= array	(
													'product_name'						=> $data['product_name'],
													'comb_name'							=> $data['comb_name'],
													'product_barcode'					=> $data['product_barcode'],
													'product_varstock'					=> $data['product_varstock'],
													'product_varprice'					=> $data['product_varprice'],
													'product_bulkdiscount_value'		=> $data['product_bulkdiscount_value'],
													'product_image_ids'					=> $data['product_image_ids'],
													'product_id'						=> $data['product_id'],
													'comb_id'							=> $data['comb_id']
												);
					}
					
					array_walk($print_data, "add_quotes");
					print implode(",", $print_data) ."\r\n";
				}
			}
			
		}
	}
	elseif($_REQUEST['cur_mod']=='stock_upload') // case of upload
	{
		$prod_id = $_REQUEST['checkbox'][0];
		
		$var_stock = $var_price = $var_img = false;
		$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode,product_variablestock_allowed,
								product_variablecomboprice_allowed, product_variablecombocommon_image_allowed   
						FROM 
							products
						WHERE 
							sites_site_id=$ecom_siteid 
							AND product_id = $prod_id 
						LIMIT 
							1";	
		$ret_stock = $db->query($sql_stock);				
		if($db->num_rows($ret_stock))
		{
			$row_stock = $db->fetch_array($ret_stock);
			if($row_stock['product_variablestock_allowed']=='Y')
				$var_stock = true;
			if($row_stock['product_variablecomboprice_allowed']=='Y')
				$var_price = true;	
			if($row_stock['product_variablecombocommon_image_allowed']=='Y')
				$var_img = true;		

			if(!$var_stock and !$var_price and $var_img) //001
				$head_arr = $var_prodcombimage_arr;
			if(!$var_stock and $var_price and !$var_img) //010
				$head_arr = $var_prodcombprice_arr;
			if(!$var_stock and $var_price and $var_img) //011
				$head_arr = $var_prodcombpriceimage_arr;
			if($var_stock and !$var_price and !$var_img) //100
				$head_arr = $var_prodcombstock_arr;
			if($var_stock and !$var_price and $var_img) //101
				$head_arr = $var_prodcombstockimage_arr;
			if($var_stock and $var_price and !$var_img) //110
				$head_arr = $var_prodcombstockprice_arr;
			if($var_stock and $var_price and $var_img) //111
				$head_arr = $var_prodcombstockpriceimage_arr;	
		}
		$err_no = 0;
		
		if(!$_FILES['file_stock_upload']['name'])
		{
			$err_no = 1;
		}
		if (strtolower($_FILES['file_stock_upload']['type'])!='text/csv' and strtolower($_FILES['file_stock_upload']['type'])!='text/plain' and strtolower($_FILES['file_stock_upload']['type'])!='application/vnd.ms-excel' and strtolower($_FILES['file_stock_upload']['type'])!='application/octet-stream' and strtolower($_FILES['file_stock_upload']['type'])!='text/comma-separated-values')
		{
			$err_no = 2;
		}
	
		if($err_no!=0)
		{
			$error = '<span class="redtext">-- Select a CSV file --</span>';
			$err_cnt = 1;
		}
		else
		{
			$fp		= fopen($_FILES['file_stock_upload']['tmp_name'],'r');
			$fs1 	= filesize($_FILES["file_stock_upload"]["tmp_name"]);
			$table 	= "
						<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
						$table .="<tr>";
						$table .="<td width='5%' class='listingtableheader'>#</td>";
						$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
						$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
						$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
						$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
						if($var_stock)
							$table .="<td width='10%' class='listingtableheader'>Stock</td>";
						if($var_price)
							$table .="<td width='10%' class='listingtableheader'>Price</td>";	
						if($var_img)
							$table .="<td width='10%' class='listingtableheader'>Image</td>";		
						$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
						$table .="</tr>";
			// Get the first row
			$data = fgetcsv($fp,$fs1, ",");
			$line	= $done_cnt = $err_cnt =  0;
			if(count($data))
			{
				if(count($data)!=count($head_arr))
				{
					$error = '<span class="redtext">-- Error in File header --</span>';
					$err_cnt = 1;
				}
				else
				{
					$i = 0;
					foreach ($head_arr as $k=>$v)
					{
						if($data[$i]!=$v)
						{
							$error = '<span class="redtext">-- Error in File header --</span>';
							$err_cnt = 1;
							break;
						}	
						$i++;
					}
				}	
			}
			$line++;
		}
		if($error=='')
		{
			while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
			{
					$num 		= count($data);
					$cur_msg	= '';
					$error 		= '';
					if($line!=0)
					{		
						$product_name				= trim($data[0]);
						$combination				= trim($data[1]);
						$barcode					= trim($data[2]);
						if(!$var_stock and !$var_price and $var_img) //001
						{
							$img						= trim($data[3]);
							$product_id					= trim($data[4]);
							$comb_id					= trim($data[5]);
						}	
						if(!$var_stock and $var_price and !$var_img) //010
						{
							$price						= trim($data[3]);
							$bulk_values				= trim($data[4]);
							$product_id					= trim($data[5]);
							$comb_id					= trim($data[6]);
						}
						if(!$var_stock and $var_price and $var_img) //011
						{
							$price						= trim($data[3]);
							$bulk_values				= trim($data[4]);
							$imageids					= trim($data[5]);
							$product_id					= trim($data[6]);
							$comb_id					= trim($data[7]);
						}
						if($var_stock and !$var_price and !$var_img) //100
						{
							$stock						= trim($data[3]);
							$product_id					= trim($data[4]);
							$comb_id					= trim($data[5]);
						}
						if($var_stock and !$var_price and $var_img) //101
						{
							$stock						= trim($data[3]);
							$imageids					= trim($data[4]);
							$product_id					= trim($data[5]);
							$comb_id					= trim($data[6]);
						}
						if($var_stock and $var_price and !$var_img) //110
						{
							$stock						= trim($data[3]);
							$price						= trim($data[4]);
							$bulk_values				= trim($data[5]);
							$product_id					= trim($data[6]);
							$comb_id					= trim($data[7]);
						}
						if($var_stock and $var_price and $var_img) //111
						{
							$stock						= trim($data[3]);
							$price						= trim($data[4]);
							$bulk_values				= trim($data[5]);
							$imageids					= trim($data[6]);
							$product_id					= trim($data[7]);
							$comb_id					= trim($data[8]);
						}
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
							$product_id = addslashes(trim($product_arr[1]));
							if($product_id != $prod_id)
							{
								if ($error!='')
										$error .= '<br/>';
									$error .= '-- Missmatch in product id';
							}
							else
							{
								// Check whether the product exists in current database for current site
								$sql_prod_check = "SELECT product_id,product_name,product_variablecomboprice_allowed,
														product_bulkdiscount_allowed,product_variablecombocommon_image_allowed,
														product_variablestock_allowed 
													FROM 
														products 
													WHERE 
														product_id = '".$product_id."' 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
								$ret_prod_check = $db->query($sql_prod_check);
								if ($db->num_rows($ret_prod_check)==0)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Product not found in site';
								}
								else // case if product found in website.. 
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
										$comb_id = $comb_arr[1];
										// Check whether current combination id is valid
										$sql_comb_check = "SELECT comb_id 
																FROM 
																	product_variable_combination_stock  
																WHERE 
																	comb_id= '".$comb_id."' 
																	AND products_product_id ='".$product_id."' 
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
									// Check whether variable stock is maintained for product. If so it is an error
									$row_prod_check = $db->fetch_array($ret_prod_check);
									if($var_stock)
									{
										$stock_update = true;
										if($stock)
										{
											if(!is_numeric($stock))
											{
												if ($error!='')
													$error .= '<br/>';
												$error .= '-- Stock value is not numeric';
											}
										}
									}
									if($var_price)
									{	
										$price_update = true;
										if($price)
										{	
											if(!is_numeric($price))
											{
												if ($error!='')
													$error .= '<br/>';
												$error .= '-- Price is not numeric';
											}
										}	
										$blkqty_arr = $blkprice_arr = $bulk_arr = array();
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
									if($var_img)
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
																			ANd image_id = '".$img_arr[$i]."' 
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
								}
							}
							$cur_msg = $error;
							if($error=='') // case if no errors found
							{
								$add_spcondition = " comb_barcode = '".addslashes($barcode)."'" ;
								if($stock_update)
								{
									if($add_spcondition!='')
										$add_spcondition .= ',';
									$add_spcondition .= " web_stock = $stock ";
								}
								if($price_update)
								{
									if($add_spcondition!='')
										$add_spcondition .= ',';
									$add_spcondition .= " comb_price = $price ";
								}	
								//Update the product_variable_combination_stock table with the details
								$update_sql = "UPDATE 
													product_variable_combination_stock  
												SET 
													$add_spcondition  
												WHERE 
													comb_id = '".$comb_id."'   
													AND products_product_id = '".$product_id."' 
												LIMIT 
													1";
								$db->query($update_sql);
								
								// handling the case of bulk discount
								if($var_price)
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
															products_product_id='".$product_id."' 
															AND comb_id='".$comb_id."'";
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
															products_product_id='".$product_id."' 
															AND comb_id='".$comb_id."'";
										$db->query($sel_del);
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
																images_image_id='".$img_arr[$i]."'  
																AND comb_id='".$comb_id."'   
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
																	ANd image_id = '".$img_arr[$i]."' 
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
															comb_id = '".$comb_id."'  
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
															comb_id = '".$comb_id."'  
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
															product_id = $prod_id 
															AND sites_site_id = $ecom_siteid 
														LIMIT 
															1";
									$db->query($update_prd);
								}
								// Calling function to recalculate the product stock
								$cur_msg  = '';
								$done_cnt++;	
							}
							else // case if error occured
							{
								if($product_name=='"')
								{
									$sql_prodname = "SELECT product_name 
													FROM 
														products 
													WHERE 
														product_id = '".$product_id."' 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
									$ret_prodname = $db->query($sql_prodname);
									if($db->num_rows($ret_prodname))
									{
										$row_prodname = $db->fetch_array($ret_prodname);
										$product_name = stripslashes($row_prodname['product_name']);
									}
								}
								$err_cnt++;
								$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$err_cnt."</td>";
								$table .="<td class='".$cls."'>".$product_id."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$combination."</td>";
								$table .="<td class='".$cls."'>".$barcode."</td>";
								if($var_stock)
									$table .="<td class='".$cls."'>".$stock."</td>";
								if($var_price)
									$table .="<td class='".$cls."'>".$price."</td>";
								if($var_img)
									$table .="<td class='".$cls."'>".$imageids."</td>";
								$table .="<td class='".$cls."'>".$cur_msg."</td>";
								$table .="</tr>";
							}
						}	
					}
					$line++;
				}
		}
		else
		{
			$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
			$table .="<tr>";
			$table .="<td class='".$cls."' colspan='".count($head_arr)."' align='center'>".$error."</td>";
			$table .="</tr>";
		}
		$table .="</table>";
		if ($done_cnt==0)
		{
			/*$msg = '<span class="redtext">Sorry!! no combination(s) updated</span>';*/
		}	
		else
		{
			// Calling function to recalculate the stock for the current product
			recalculate_actual_stock($prod_id);
			if($var_price)
				handle_default_comp_price_and_id($prod_id);
			$msg = '<span class="redtext"><strong>Product Combination(s) Update Operation Completed</strong></span>';
		}	
		$alert = '<br/><center>'.$msg ;
		$alert .='<br/><br/><strong>Total Combination(s) Updated: '.$done_cnt.'</strong><br><br></center>';
		if ($err_cnt>0)
		{
			//$alert .='<br/><br/><span class="redtext">Following Error(s) occured</span><br/>';
			$alert .=$table;
		}
		$special_alert = $alert;
		$alert = '';
	}
?>