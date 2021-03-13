<?php
	/*#################################################################
	# Script Name 		: do_database_offline.php
	# Description 		: variables to be used in the database offline export and upload
	# Coded by 		: Sny
	# Created on		: 14-Aug-2008
	# Modified by		: Sny
	# Modified On		: 03-Feb-2010
	# Modified by		: Joby
	# Modified On		: 24-Oct-2011
	#################################################################*/
	set_time_limit(0);

	if ($_REQUEST['cur_mod']=='')
	{
		echo '<script type="text/javascript">alert("Invalid Parameter");</script>';
		exit;
	}
	include_once("functions/functions.php");
	include('session.php');
	require_once("sites.php");
	require_once("config.php");
	include_once("database_offline_variables.php");

	/*Including excel convertion class - starts*/
	$root_path = $_SERVER['DOCUMENT_ROOT'].'/console_v5/';
	// Include PEAR::Spreadsheet_Excel_Writer
	require_once "Spreadsheet/Excel/Writer.php";
	$xls =& new Spreadsheet_Excel_Writer();
	/*Including excel convertion class - ends*/
	




	$headers 	= array();
	$data 		= array();
	$exists 		= false;
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
	if($_REQUEST['cur_mod']=='offline_download') // case of download
	{
		$all_prods	= $_REQUEST['chk_selallprods'];
		$sel_cats	= $_REQUEST['sel_category_id'];
		$main_opt	= $_REQUEST['main_select'];
                
		if(!$all_prods) // case if all products not selected
		{
			if(count($sel_cats)==0)
			{
				echo '<script type="text/javascript">alert("Invalid Parameter");
						window.location = \'home.php?request=database_offline\';
					</script>';
			}
			else
			{
				if($main_opt=='prod')
				{
					// Get the list of ids of product under selected categories
					$prodids_arr = array(-1);
					$sql_prodids = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(",",$sel_cats).")" ;
					$ret_prodids = $db->query($sql_prodids);
					if($db->num_rows($ret_prodids))
					{
						while ($row_prodids = $db->fetch_array($ret_prodids))
						{
							$prodids_arr[] = $row_prodids['products_product_id'];
						}
					}
					$prodids_str = implode(",",$prodids_arr);
					$sql_prod = "SELECT   b.product_id,
													DATE_FORMAT(b.product_adddate,'D-%d/%m/%Y %H:%i:%S') as prod_date,
													b.product_barcode,
													b.manufacture_id,
													b.product_name,
													b.product_model,
													b.product_shortdesc,
													b.product_longdesc,
													product_keywords,
													b.product_hide,
													b.product_webstock,
													b.product_costprice,
													b.product_webprice,
													b.product_weight,
													b.product_reorderqty,
													b.product_extrashippingcost,
													b.product_bonuspoints,
													CASE b.product_discount_enteredasval 
														WHEN 0 
															THEN ('%') 
														WHEN 1 
															THEN ('Value') 
														WHEN 2 
															THEN ('Exact') 
													END as discount_type,
													b.product_discount,
													b.product_applytax,
													b.product_variablestock_allowed,
													b.product_preorder_allowed,
													b.product_total_preorder_allowed,
													DATE_FORMAT(b.product_instock_date,'D-%d/%m/%Y') as instock_date,
													b.product_deposit,
													b.product_deposit_message,
													case b.product_show_cartlink 
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as show_cartlink,
													case b.product_show_enquirelink
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as show_enquirelink,
													b.product_code,
													b.product_sizechart_mainheading,
													case b.product_variable_in_newrow 
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as variable_in_newrow,
													b.product_stock_notification_required,
													b.product_hide_on_nostock,
													b.product_alloworder_notinstock,
													case b.product_freedelivery 
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as freedelivery,
													b.product_det_qty_caption,
													case b.product_det_qty_type  
														WHEN 'NOR' 
															THEN ('Normal') 
														WHEN 'DROP' 
															THEN ('Dropdown') 
													END as det_qty_type,
													b.product_det_qty_drop_values,
													b.product_det_qty_drop_prefix,
													b.product_det_qty_drop_suffix,
													b.price_normalprefix,
													b.price_normalsuffix,
													b.price_fromprefix,
													b.price_fromsuffix,
													b.price_specialofferprefix,
													b.price_specialoffersuffix,
													b.price_discountprefix,
													b.price_discountsuffix,
													b.price_yousaveprefix,
													b.price_yousavesuffix,
													b.price_noprice,
													case b.product_show_pricepromise  
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as show_pricepromise,
													case b.product_saleicon_show  
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as saleicon_show,
													b.product_saleicon_text,
													case b.product_newicon_show  
														WHEN 1 
															THEN ('Y') 
														WHEN 0 
															THEN ('N') 
													END as newicon_show,
													b.product_newicon_text,
													b.product_variablecombocommon_image_allowed,
													b.product_variablecomboprice_allowed,
													b.product_bulkdiscount_allowed
												FROM 
													products b
												WHERE 
													b.sites_site_id = $ecom_siteid 
													AND product_id IN ($prodids_str)  
												ORDER BY 
													b.product_name";
						$ret_prod = $db->query($sql_prod);		
						if($db->num_rows($ret_prod))
							$exists = true;											
				}
				elseif($main_opt=='prod_var') // case of product variables 
				{
					$prodids_arr = array(-1);
					$sql_prodids = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(",",$sel_cats).")" ;
					$ret_prodids = $db->query($sql_prodids);
					
					if($db->num_rows($ret_prodids))
					{
						while ($row_prodids = $db->fetch_array($ret_prodids))
						{
							$prodids_arr[] = $row_prodids['products_product_id'];
						}
					}
					$prodids_str = implode(",",$prodids_arr);
					$sql_prod = "SELECT b.product_id,b.product_name,a.var_id,a.var_name,a.var_order,
													CASE a.var_hide 
														WHEN 1 THEN 'Y' 
														WHEN 0 THEN 'N'
													 END as hide,
													 CASE a.var_value_exists 
														WHEN 1 THEN 'Y' 
														WHEN 0 THEN 'N'
														END as val_exists,a.var_price,'VAR'  as vartype    
											FROM 
												product_variables a,products b
											WHERE 
												b.sites_site_id=$ecom_siteid 
												AND b.product_id IN ($prodids_str)  
												AND a.products_product_id = b.product_id 
											ORDER BY 
												b.product_name";
					$ret_prod = $db->query($sql_prod);			
					if($db->num_rows($ret_prod))
						$exists = true;			
					
					// check whether messages exists
					$sql_msg = "SELECT b.product_id,b.product_name,a.message_id,a.message_title,a.message_order,message_type,
											CASE a.message_hide 
												WHEN 1 THEN 'Y' 
												WHEN 0 THEN 'N'
											 END as hide,'MSG'  as vartype 
											FROM 
												product_variable_messages a,products b
											WHERE 
												b.sites_site_id=$ecom_siteid 
												AND b.product_id IN ($prodids_str) 
												AND a.products_product_id = b.product_id  
											ORDER BY 
												b.product_name";
						$ret_msg = $db->query($sql_msg);				
						if($db->num_rows($ret_msg))
								$exists = true;	
							
				}	
				elseif($main_opt=='prod_label') // case of product labels and also categories selected
				{
					$prodids_arr = array(-1);
					$sql_prodids = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(",",$sel_cats).")" ;
					$ret_prodids = $db->query($sql_prodids);
					if($db->num_rows($ret_prodids))
					{
						while ($row_prodids = $db->fetch_array($ret_prodids))
						{
							$prodids_arr[] = $row_prodids['products_product_id'];
						}
					}
					$prodids_str = implode(",",$prodids_arr);
					$sql_label = "SELECT b.product_id,b.product_name,a.id,a.label_value,c.label_name,c.is_textbox, a.product_site_labels_values_label_value_id  
										FROM 
											product_labels a,products b,product_site_labels c
										WHERE 
											b.sites_site_id=$ecom_siteid 
											AND b.product_id IN($prodids_str)   
											AND 
												CASE c.is_textbox 
													WHEN 1 
														THEN a.label_value!='' 
													WHEN 0 
														THEN a.product_site_labels_values_label_value_id<>0 
												END
											AND a.products_product_id = b.product_id 
											AND c.label_id = a.product_site_labels_label_id  
										ORDER BY 
											b.product_name";						
					$ret_label = $db->query($sql_label);			
					if($db->num_rows($ret_label))
						$exists = true;				
				}
				elseif($main_opt=='prod_fixedall' or $main_opt=='prod_fixedstock' or $main_opt == 'prod_fixedprice' or $main_opt == 'prod_normalimage' or $main_opt=='prod_combstock' or $main_opt == 'prod_combprice' or $main_opt == 'prod_combimage' or $main_opt == 'prod_comball') // case of product fixed stock is selected and all products not ticked
				{	
					$additional_condition = '';
					switch ($main_opt)
					{
						case 'prod_fixedstock':
							$additional_condition = " AND product_variablestock_allowed = 'N' ";
						break;					
						case 'prod_fixedprice':
							$additional_condition = " AND product_variablecomboprice_allowed = 'N' ";
						break;	
						case 'prod_normalimage':
							$additional_condition = " AND product_variablecombocommon_image_allowed = 'N' ";
						break;
						case 'prod_combstock':
							$additional_condition = " AND product_variablestock_allowed = 'Y' ";
						break;					
						case 'prod_combprice':
							$additional_condition = " AND product_variablecomboprice_allowed = 'Y' ";
						break;	
						case 'prod_combimage':
							$additional_condition = " AND product_variablecombocommon_image_allowed = 'Y' ";
						break;
						case 'prod_comball':
							$additional_condition = " AND product_variablestock_allowed = 'Y' AND product_variablecomboprice_allowed = 'Y' AND product_variablecombocommon_image_allowed = 'Y' ";
						break;
						case 'prod_fixedall':
							$additional_condition = " AND product_variablestock_allowed = 'N' AND product_variablecomboprice_allowed ='N' AND  product_variablecombocommon_image_allowed = 'N' ";
						break;						
					};
					// get the main product details from products table
					$prodids_arr = array(-1);
					$sql_prodids = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(",",$sel_cats).")" ;
					$ret_prodids = $db->query($sql_prodids);
					if($db->num_rows($ret_prodids))
					{
						while ($row_prodids = $db->fetch_array($ret_prodids))
						{
							$prodids_arr[] = $row_prodids['products_product_id'];
						}
					}
					$prodids_str = implode(",",$prodids_arr);
					$sql_stock = "SELECT product_id,product_name,product_webstock, 
										product_barcode,product_webprice,product_costprice     
									FROM 
										products
									WHERE 
										sites_site_id=$ecom_siteid 
										AND product_id IN($prodids_str)  
										$additional_condition  
									ORDER BY 
										product_name";						
					$ret_stock = $db->query($sql_stock);			
					if($db->num_rows($ret_stock))
						$exists = true;
				}
			}
		}
		else // case if all product selected
		{
			if($main_opt=='prod')
			{
				$sql_prod = "SELECT product_id,
									DATE_FORMAT(product_adddate,'D-%d/%m/%Y %H:%i:%S') as prod_date,
									product_barcode,
									manufacture_id,
									product_name,
									product_model,
									product_shortdesc,
									product_longdesc,
									product_keywords,
									product_hide,
									product_webstock,
									product_costprice,
									product_webprice,
									product_weight,
									product_reorderqty,
									product_extrashippingcost,
									product_bonuspoints,
									CASE product_discount_enteredasval 
										WHEN 0 
											THEN ('%') 
										WHEN 1 
											THEN ('Value') 
										WHEN 2 
											THEN ('Exact') 
									END as discount_type,
									product_discount,
									product_applytax,
									product_variablestock_allowed,
									product_preorder_allowed,
									product_total_preorder_allowed,
									DATE_FORMAT(product_instock_date,'D-%d/%m/%Y') as instock_date,
									product_deposit,
									product_deposit_message,
									case product_show_cartlink 
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as show_cartlink,
									case product_show_enquirelink
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as show_enquirelink,
									product_code,
									product_sizechart_mainheading,
									case product_variable_in_newrow 
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as variable_in_newrow,
									product_stock_notification_required,
									product_hide_on_nostock,
									product_alloworder_notinstock,
									case product_freedelivery 
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as freedelivery,
									product_det_qty_caption,
									case product_det_qty_type  
										WHEN 'NOR' 
											THEN ('Normal') 
										WHEN 'DROP' 
											THEN ('Dropdown') 
									END as det_qty_type,
									product_det_qty_drop_values,
									product_det_qty_drop_prefix,
									product_det_qty_drop_suffix,
									price_normalprefix,
									price_normalsuffix,
									price_fromprefix,
									price_fromsuffix,
									price_specialofferprefix,
									price_specialoffersuffix,
									price_discountprefix,
									price_discountsuffix,
									price_yousaveprefix,
									price_yousavesuffix,
									price_noprice,
									case product_show_pricepromise  
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as show_pricepromise,
									case product_saleicon_show  
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as saleicon_show,
									product_saleicon_text,
									case product_newicon_show  
										WHEN 1 
											THEN ('Y') 
										WHEN 0 
											THEN ('N') 
									END as newicon_show,
									product_newicon_text,
									product_variablecombocommon_image_allowed,
									product_variablecomboprice_allowed,
									product_bulkdiscount_allowed
								FROM 
									products 
								WHERE 
									sites_site_id = $ecom_siteid 
								ORDER BY 
									product_name";
                                                $ret_prod = $db->query($sql_prod);					
						if($db->num_rows($ret_prod))
							$exists = true;
			}	
			elseif($main_opt=='prod_var') // case of product variables and categories not selected
			{
				// check whether variables exists 
				$sql_prod = "SELECT b.product_id,b.product_name,a.var_id,a.var_name,a.var_order,
										CASE a.var_hide 
											WHEN 1 THEN 'Y' 
											WHEN 0 THEN 'N'
										 END as hide,
										 CASE a.var_value_exists 
										 	WHEN 1 THEN 'Y' 
											WHEN 0 THEN 'N'
											END as val_exists,a.var_price,'VAR'  as vartype 
										FROM 
											product_variables a,products b
										WHERE 
											b.sites_site_id=$ecom_siteid 
											AND a.products_product_id = b.product_id 
										ORDER BY 
											b.product_name";
					$ret_prod = $db->query($sql_prod);				
					if($db->num_rows($ret_prod))
							$exists = true;			
													
				// check whether messages exists
				$sql_msg = "SELECT b.product_id,b.product_name,a.message_id,a.message_title,a.message_order,message_type,
											CASE a.message_hide 
												WHEN 1 
													THEN 'Y' 
												WHEN 0 
													THEN 'N'
											 END as hide,'MSG'  as vartype 
											FROM 
												product_variable_messages a,products b
											WHERE 
												b.sites_site_id=$ecom_siteid 
												AND a.products_product_id = b.product_id 
											ORDER BY 
												b.product_name";
						$ret_msg = $db->query($sql_msg);				
						if($db->num_rows($ret_msg))
								$exists = true;			
			}	
			elseif($main_opt=='prod_label') // case of product labels and categories not selected
			{
				// check whether labels exists
				$sql_label = "SELECT b.product_id,b.product_name,a.id,a.label_value,c.label_name,c.is_textbox, a.product_site_labels_values_label_value_id   
								FROM 
									product_labels a,products b,product_site_labels c 
								WHERE 
									b.sites_site_id=$ecom_siteid 
									AND 
										CASE c.is_textbox 
										WHEN 1 
											THEN a.label_value!='' 
										WHEN 0 
											THEN a.product_site_labels_values_label_value_id<>0 
										END
									AND a.products_product_id = b.product_id 
									AND c.label_id = a.product_site_labels_label_id  
								ORDER BY 
									b.product_name";
					$ret_label = $db->query($sql_label);				
					if($db->num_rows($ret_label))
							$exists = true;			
			}
			elseif($main_opt=='prod_fixedall' or $main_opt=='prod_fixedstock'or $main_opt=='prod_fixedprice' or $main_opt=='prod_normalimage' or $main_opt=='prod_combstock' or $main_opt=='prod_combprice' or $main_opt=='prod_combimage' or $main_opt=='prod_comball') // case of product fixed stock is selected and all products not ticked
			{	
				$additional_condition = '';
				switch ($main_opt)
				{
					case 'prod_fixedstock':
						$additional_condition = " AND product_variablestock_allowed = 'N' ";
					break;					
					case 'prod_fixedprice':
						$additional_condition = " AND product_variablecomboprice_allowed = 'N' ";
					break;	
					case 'prod_normalimage':
						$additional_condition = " AND product_variablecombocommon_image_allowed = 'N' ";
					break;
					case 'prod_combstock':
							$additional_condition = " AND product_variablestock_allowed = 'Y' ";
					break;					
					case 'prod_combprice':
						$additional_condition = " AND product_variablecomboprice_allowed = 'Y' ";
					break;	
					case 'prod_combimage':
						$additional_condition = " AND product_variablecombocommon_image_allowed = 'Y' ";
					break;
					case 'prod_fixedall':
						$additional_condition = " AND product_variablestock_allowed = 'N' AND product_variablecomboprice_allowed ='N' AND  product_variablecombocommon_image_allowed = 'N' ";
					break;
					case 'prod_comball':
						$additional_condition = " AND product_variablestock_allowed = 'Y' AND product_variablecomboprice_allowed ='Y' AND  product_variablecombocommon_image_allowed = 'Y' ";
					break;					
				};
				// get the main product details from products
				$sql_stock = "SELECT product_id,product_name,product_webstock, product_webprice,product_barcode ,product_costprice    
								FROM 
									products
								WHERE 
									sites_site_id=$ecom_siteid 
									$additional_condition
								ORDER BY 
									product_name";	
				$ret_stock = $db->query($sql_stock);				
				if($db->num_rows($ret_stock))
					$exists = true;		
			}
		}
		if($exists)
		{
			if($main_opt=='prod')
				$filename ='product_offline';
			elseif($main_opt=='prod_var')
				$filename ='product_variables_offline';	
			elseif($main_opt=='prod_label')
				$filename ='product_labels_offline';
			elseif($main_opt=='prod_fixedstock')
				$filename ='product_fixedstock_offline';
			elseif($main_opt=='prod_fixedprice')
				$filename ='product_fixedprice_offline';
			elseif($main_opt=='prod_normalimage')
				$filename ='product_normalimage_offline';
			elseif($main_opt=='prod_combstock')
				$filename ='product_combinationstock_offline';
			elseif($main_opt=='prod_combprice')
				$filename ='product_combinationprice_offline';
			elseif($main_opt=='prod_combimage')
				$filename ='product_combinationimage_offline';
			elseif($main_opt=='prod_fixedall')
				$filename ='product_fixedstock_fixedprice_fixedimages_offline';		
			elseif($main_opt=='prod_comball')
				$filename ='product_combinationstock_combinationprice_combinationimages_offline';					
			// Defining the header type	
			/*header("Content-Type: text/plain");
			header("Content-Disposition: attachment; filename=$filename.csv");*/
			
			// Create an instance for excel class 
			$xls->send("$filename.xls");
			// Add a worksheet to the file, returning an object to add data to 
			$worksheet =& $xls->addWorksheet('Binary Count');  

			// Printing the heading
			$row_count = 0;
			$cell_count = 0;
			if($main_opt=='prod') // case of products
			{
				/*array_walk($var_prod_arr, "add_quotes");
				print implode(",", $var_prod_arr) . "\r\n";*/
				foreach($var_prod_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}
			}	
			elseif($main_opt=='prod_var') // case of product variables
			{
				/*array_walk($var_prodvars_arr, "add_quotes");
				print implode(",", $var_prodvars_arr) . "\r\n";*/
				foreach($var_prodvars_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}
				
			}	
			elseif($main_opt=='prod_label') // case of product labels
			{
				/*array_walk($var_prodlabel_arr, "add_quotes");
				print implode(",", $var_prodlabel_arr) . "\r\n";*/
				foreach($var_prodlabel_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}
			}	
			elseif($main_opt=='prod_fixedstock') // case of product fixed stock
			{
				/*array_walk($var_prodfixedstock_arr, "add_quotes");
				print implode(",", $var_prodfixedstock_arr) . "\r\n";*/
				foreach($var_prodfixedstock_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}	
			elseif($main_opt=='prod_fixedprice') // case of product fixed price
			{
				/*array_walk($var_prodfixedprice_arr, "add_quotes");
				print implode(",", $var_prodfixedprice_arr) . "\r\n";*/
				foreach($var_prodfixedprice_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}
			elseif($main_opt=='prod_normalimage') // case of product normal image
			{
				/*array_walk($var_prodnormalimage_arr, "add_quotes");
				print implode(",", $var_prodnormalimage_arr) . "\r\n";*/
				foreach($var_prodnormalimage_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}
			elseif($main_opt=='prod_combstock') // case of product fixed stock
			{
				/*array_walk($var_prodcombstock_arr, "add_quotes");
				print implode(",", $var_prodcombstock_arr) . "\r\n";*/
				foreach($var_prodcombstock_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}	
			elseif($main_opt=='prod_combprice') // case of product fixed price
			{
				/*array_walk($var_prodcombprice_arr, "add_quotes");
				print implode(",", $var_prodcombprice_arr) . "\r\n";*/
				foreach($var_prodcombprice_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}
			elseif($main_opt=='prod_combimage') // case of product normal image
			{
				/*array_walk($var_prodcombimage_arr, "add_quotes");
				print implode(",", $var_prodcombimage_arr) . "\r\n";*/
				foreach($var_prodcombimage_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}
			elseif($main_opt=='prod_fixedall') // case of fixed stock, fixed price and normal images
			{
				/*array_walk($var_prodfixedall_arr, "add_quotes");
				print implode(",", $var_prodfixedall_arr) . "\r\n";*/
				foreach($var_prodfixedall_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}
			elseif($main_opt=='prod_comball') // case of comb stock, comb price and comb images
			{
				/*array_walk($var_prodcomball_arr, "add_quotes");
				print implode(",", $var_prodcomball_arr) . "\r\n";*/
				foreach($var_prodcomball_arr as $cell_data)
				{
				$worksheet->writeString($row_count, $cell_count, "$cell_data");
				$cell_count++;
				}	
			}	
			if($main_opt=='prod') // case of products
			{
				$row_count = 1;	
				while ($row_prod=$db->fetch_array($ret_prod))
				{
					$data		= array();
					
					$data['product_id'] 						= 'P-'.$row_prod['product_id'];
					$data['product_name'] 						= $row_prod['product_name'];
					$data['product_adddate'] 					= $row_prod['prod_date'];
					$data['product_barcode'] 					= $row_prod['product_barcode'];
					$data['manufacture_id'] 					= $row_prod['manufacture_id'];
					$data['product_model'] 						= $row_prod['product_model'];
					$data['product_shortdesc'] 					= $row_prod['product_shortdesc'];
											if($_REQUEST['download_long_desc_include']==1)
												$data['product_longdesc'] 					= $row_prod['product_longdesc'];
					$data['product_keywords'] 					= $row_prod['product_keywords'];
					$data['product_hide'] 						= $row_prod['product_hide'];
					
					$data['product_costprice'] 					= $row_prod['product_costprice'];
					$data['product_webprice'] 					= $row_prod['product_webprice'];
					$data['product_weight'] 					= $row_prod['product_weight'];
					$data['product_reorderqty'] 				= $row_prod['product_reorderqty'];
					$data['product_extrashippingcost'] 			= $row_prod['product_extrashippingcost'];
					$data['product_bonuspoints'] 				= $row_prod['product_bonuspoints'];
					$data['product_discount_enteredasval'] 		= $row_prod['discount_type'];	
					$data['product_discount'] 					= $row_prod['product_discount'];
					$data['product_applytax'] 					= $row_prod['product_applytax'];
					$data['product_variablestock_allowed'] 		= $row_prod['product_variablestock_allowed'];
					$data['product_webstock'] 					= ($row_prod['product_variablestock_allowed']=='Y')?0:$row_prod['product_webstock'];
					$data['product_preorder_allowed'] 			= $row_prod['product_preorder_allowed'];
					$data['product_total_preorder_allowed'] 	= $row_prod['product_total_preorder_allowed'];
					$data['product_instock_date'] 				= $row_prod['instock_date'];
					$data['product_deposit'] 					= $row_prod['product_deposit'];
					$data['product_deposit_message'] 			= $row_prod['product_deposit_message'];
					$data['product_show_cartlink'] 				= $row_prod['show_cartlink'];
					$data['product_show_enquirelink'] 			= $row_prod['show_enquirelink'];
					$data['product_sizechart_mainheading'] 		= $row_prod['product_sizechart_mainheading'];
					
					$data['product_variable_in_newrow'] 		= $row_prod['variable_in_newrow'];
					$data['product_stock_notification_required']= $row_prod['product_stock_notification_required'];
					$data['product_hide_on_nostock'] 			= $row_prod['product_hide_on_nostock'];
					$data['product_alloworder_notinstock'] 		= $row_prod['product_alloworder_notinstock'];
					$data['product_freedelivery'] 				= $row_prod['freedelivery'];
					$data['product_det_qty_caption'] 			= $row_prod['product_det_qty_caption'];
					$data['product_det_qty_type'] 				= $row_prod['det_qty_type'];
					$data['product_det_qty_drop_values'] 		= $row_prod['product_det_qty_drop_values'];
					$data['product_det_qty_drop_prefix'] 		= $row_prod['product_det_qty_drop_prefix'];
					$data['product_det_qty_drop_suffix'] 		= $row_prod['product_det_qty_drop_suffix'];
					$data['price_normalprefix'] 				= $row_prod['price_normalprefix'];
					$data['price_normalsuffix'] 				= $row_prod['price_normalsuffix'];
					$data['price_fromprefix'] 					= $row_prod['price_fromprefix'];
					$data['price_fromsuffix'] 					= $row_prod['price_fromsuffix'];
					$data['price_specialofferprefix'] 			= $row_prod['price_specialofferprefix'];
					$data['price_specialoffersuffix'] 			= $row_prod['price_specialoffersuffix'];
					$data['price_discountprefix'] 				= $row_prod['price_discountprefix'];
					$data['price_discountsuffix'] 				= $row_prod['price_discountsuffix'];
					$data['price_yousaveprefix'] 				= $row_prod['price_yousaveprefix'];
					$data['price_yousavesuffix'] 				= $row_prod['price_yousavesuffix'];
					$data['price_noprice'] 						= $row_prod['price_noprice'];
					$data['product_show_pricepromise'] 			= $row_prod['show_pricepromise'];
					$data['product_saleicon_show'] 				= $row_prod['saleicon_show'];
					$data['product_saleicon_text'] 				= $row_prod['product_saleicon_text'];
					$data['product_newicon_show'] 				= $row_prod['newicon_show'];
					$data['product_newicon_text'] 				= $row_prod['product_newicon_text'];
					$data['product_variablecombocommon_image_allowed'] = $row_prod['product_variablecombocommon_image_allowed'];
					$data['product_image_ids'] 					= '';
					$prod_img_arr = array();
					if($row_prod['product_variablecombocommon_image_allowed']=='N') // case if variable combination image is not ticked
					{
						// Get the ids of images linked with current product
						$sql_img_id = "SELECT images_image_id  
											FROM 
												images_product  
											WHERE 
												products_product_id=".$row_prod['product_id']." 
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
								$data['product_image_ids'] = implode('=>',$prod_img_arr);
						}
					}
					else
						$data['product_image_ids'] = '';

					$data['product_variablecomboprice_allowed']	= $row_prod['product_variablecomboprice_allowed'];
					$data['product_bulkdiscount_allowed']		= $row_prod['product_bulkdiscount_allowed'];
					$data['product_bulkdiscount_value']			= '';
					$bulk_arr									= array();
					if($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_bulkdiscount_allowed']=='Y')
					{
						// get the direct bulk discount details for current product
						$sql_bulk = "SELECT bulk_qty, bulk_price 
										FROM 
											product_bulkdiscount 
										WHERE 
											products_product_id = ".$row_prod['product_id']." 
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
								$data['product_bulkdiscount_value']	= implode(',',$bulk_arr);
						}
					}
					/*array_walk($data, "add_quotes");
					print implode(",", $data) ."\r\n";*/
					$cell_count = 0;
					foreach($data as $cell_data)
					{
					$worksheet->writeString($row_count, $cell_count, "$cell_data");
					$cell_count++;
					}
					$row_count++;
				}
 
			}		
			elseif($main_opt=='prod_var') // case of product variables
			{
				if($db->num_rows($ret_prod))
				{
					$prev_id = 0;
					$row_count = 1;	
					while ($row_prod=$db->fetch_array($ret_prod))
					{
						$data		= array();
						
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 						= $row_prod['product_name'];
						else
							$data['product_name'] 						= '    "';	
						$prev_id 												= $row_prod['product_id'];	
						
						$data['var_name'] 									= $row_prod['var_name'];
						$data['var_type'] 									= $row_prod['vartype'];
						$data['var_order'] 									= $row_prod['var_order'];
						$data['var_hide'] 									= $row_prod['hide'];
						$data['var_value_exists'] 							= $row_prod['val_exists'];
						$data['var_price'] 									= ($row_prod['val_exists']=='N')?$row_prod['var_price']:0;
						$data['var_value'] 									= '';
						$data['var_value_price'] 							= '';
						$data['var_value_order'] 							= '';
						$data['product_id'] 									= 'P-'.$row_prod['product_id'];
						$data['var_id'] 										= 'V-'.$row_prod['var_id'];
						$data['var_value_id'] 								= '';
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
						
						if($row_prod['val_exists']=='Y') // if values exists for variables
						{
							// Get the values 
							$sql_vals = "SELECT var_value_id,var_value,var_addprice,var_order 
													FROM 
														product_variable_data 
													WHERE 
														product_variables_var_id = '".$row_prod['var_id']."'  
													ORDER BY 
														var_order";
							$ret_vals = $db->query($sql_vals);
							if($db->num_rows($ret_vals))
							{
								$data = array();
								$prev_varid=0;
								while ($row_vals = $db->fetch_array($ret_vals))
								{
										$data		= array();
										
										$data['product_name'] 							= '    "';	
										
										$data['var_name'] 									= '    "';	
										$prev_varid												= $row_prod['var_id'];	
										$data['var_type'] 									= '';
										$data['var_order'] 									= '';
										$data['var_hide'] 									= '';
										$data['var_value_exists'] 							= '';
										$data['var_price'] 									= '';
										$data['var_value'] 									= $row_vals['var_value'];
										$data['var_value_price'] 							= $row_vals['var_addprice'];
										$data['var_value_order'] 							= $row_vals['var_order'];
										$data['product_id'] 									='P-'.$row_prod['product_id'];	
										$data['var_id'] 										= 'V-'.$row_prod['var_id'];
										$data['var_value_id'] 								= 'Vv-'.$row_vals['var_value_id'];
										/*array_walk($data, "add_quotes");
										print implode(",", $data) ."\r\n";*/
										$cell_count = 0;
										foreach($data as $cell_data)
										{
										$worksheet->writeString($row_count, $cell_count, "$cell_data");
										$cell_count++;
										}
										$row_count++;
								}
							}
						}
					}	
				}
				if($db->num_rows($ret_msg))
				{
					$prevmsg_id=0;
					while ($row_msg=$db->fetch_array($ret_msg))
					{
						$data		= array();
						if ($prevmsg_id!=$row_msg['product_id'])
							$data['product_name'] 							= $row_msg['product_name'];
						else
							$data['product_name'] 							= '    "';	
						$prevmsg_id											= $row_msg['product_id'];
						
						$data['var_name'] 									= $row_msg['message_title'];
						$data['var_type'] 									= $row_msg['vartype'];
						$data['var_order'] 									= $row_msg['message_order'];
						$data['var_hide'] 									= $row_msg['hide'];
						$data['var_value_exists'] 							= $row_msg['message_type'];
						$data['var_price'] 									= '';
						$data['var_value'] 									= '';
						$data['var_value_price'] 							= '';
						$data['var_value_order'] 							= '';
						$data['product_id'] 								= 'P-'.$row_msg['product_id'];
						$data['var_id'] 									= 'V-'.$row_msg['message_id'];
						$data['var_value_id'] 								= '';
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}	
				}	
			}
			elseif($main_opt=='prod_label') // case of product labels
			{
				if($db->num_rows($ret_label))
				{
					$prevlabel_id=0;
					$row_count = 1;	
					while ($row_label=$db->fetch_array($ret_label))
					{
						$data		= array();
						if ($prevlabel_id!=$row_label['product_id'])
							$data['product_name'] 							= $row_label['product_name'];
						else
							$data['product_name'] 							= '    "';	
						$prevlabel_id											= $row_label['product_id'];
						$data['label_name'] 								= $row_label['label_name'];
						if ($row_label['is_textbox']==1) // case if label type is textbox then show the value directly.
							$data['label_value'] 									= $row_label['label_value'];
						else // if label type is not textbox then it would be dropdown box. so pick the value for dropdown box from value table
						{
							$sql_labelval = "SELECT label_value 
														FROM 
															product_site_labels_values 
														WHERE 
															label_value_id = '".$row_label['product_site_labels_values_label_value_id']."'  
														LIMIT 
															1";
							$ret_labelval = $db->query($sql_labelval);
							if ($db->num_rows($ret_labelval))
							{
								$row_labelval = $db->fetch_array($ret_labelval);
								$data['label_value'] 									= $row_labelval['label_value'];
							}
						}
						$data['product_id'] 									= 'P-'.$row_label['product_id'];
						$data['labelmap_id'] 								= 'L-'.$row_label['id'];
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}	
				}	
			}
			elseif($main_opt=='prod_fixedall') // case of product with fixed stock, fixed price and direct images only
			{
				if($db->num_rows($ret_stock))
				{
					$prev_id = 0;
					$row_count = 1;	
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$data		= $prod_img_arr = $bulk_arr = array();
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 					= $row_prod['product_name'];
						else
							$data['product_name'] 					= '    "';	
						$prev_id 									= $row_prod['product_id'];
						$data['product_barcode'] 					= $row_prod['product_barcode'];
						$data['product_webstock'] 					= $row_prod['product_webstock'];
						$data['product_webprice'] 					= $row_prod['product_webprice'];
						
						// get the direct bulk discount details for current product
						$sql_bulk = "SELECT bulk_qty, bulk_price 
										FROM 
											product_bulkdiscount 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
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
								$data['product_bulkdiscount_value']	= implode(',',$bulk_arr);
							else 
								$data['product_bulkdiscount_value'] = '';
						}
						else 
							$data['product_bulkdiscount_value'] = '';
						// Get the ids of images linked with current product
						$sql_img_id = "SELECT images_image_id  
											FROM 
												images_product  
											WHERE 
												products_product_id='".$row_prod['product_id']."'  
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
								$data['product_image_ids'] = implode('=>',$prod_img_arr);
							else
								$data['product_image_ids'] = '';
						}
						else
							$data['product_image_ids'] = '';
						$data['product_id'] 						= 'P-'.$row_prod['product_id'];
						
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}
				}		
			}
			elseif($main_opt=='prod_fixedstock') // case of product with fixed stock only
			{
				if($db->num_rows($ret_stock))
				{
					$prev_id = 0;
					$row_count = 1;	
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$data		= $prod_img_arr = array();
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 					= $row_prod['product_name'];
						else
							$data['product_name'] 					= '    "';	
						$prev_id 									= $row_prod['product_id'];
						$data['product_barcode'] 					= $row_prod['product_barcode'];
						$data['product_webstock'] 					= $row_prod['product_webstock'];
						$data['product_id'] 						= 'P-'.$row_prod['product_id'];
						
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}
				}		
			}
			elseif($main_opt=='prod_fixedprice') // case of product with fixed price only
			{
				if($db->num_rows($ret_stock))
				{
					$prev_id = 0;
					$row_count = 1;	
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$data		= $prod_img_arr = $bulk_arr = array();
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 				= $row_prod['product_name'];
						else
							$data['product_name'] 				= '    "';	
						$prev_id 								= $row_prod['product_id'];
						$data['product_barcode'] 				= $row_prod['product_barcode'];
						$data['product_webprice'] 				= $row_prod['product_webprice'];
						$data['product_costprice'] 				= $row_prod['product_costprice'];
						// get the direct bulk discount details for current product
						$sql_bulk = "SELECT bulk_qty, bulk_price 
										FROM 
											product_bulkdiscount 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
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
								$data['product_bulkdiscount_value']	= implode(',',$bulk_arr);
							else 
								$data['product_bulkdiscount_value'] = '';
						}
						else 
							$data['product_bulkdiscount_value'] = '';
						$data['product_id'] 						= 'P-'.$row_prod['product_id'];
						
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}
				}		
			}
			elseif($main_opt=='prod_normalimage') // case of product with normal images only
			{
				if($db->num_rows($ret_stock))
				{
					$prev_id = 0;
					$row_count = 1;	
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$data		= $prod_img_arr = array();
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 				= $row_prod['product_name'];
						else
							$data['product_name'] 				= '    "';	
						$prev_id 								= $row_prod['product_id'];
						$data['product_barcode'] 				= $row_prod['product_barcode'];
						// Get the ids of images linked with current product
						$sql_img_id = "SELECT images_image_id  
											FROM 
												images_product  
											WHERE 
												products_product_id='".$row_prod['product_id']."'  
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
								$data['product_image_ids'] = implode('=>',$prod_img_arr);
							else
								$data['product_image_ids'] = '';
						}
						else
							$data['product_image_ids'] = '';
						$data['product_id'] 						= 'P-'.$row_prod['product_id'];
						
						/*array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";*/
						$cell_count = 0;
						foreach($data as $cell_data)
						{
						$worksheet->writeString($row_count, $cell_count, "$cell_data");
						$cell_count++;
						}
						$row_count++;
					}
				}		
			}
			elseif($main_opt=='prod_combstock') // case of product with combination stock only
			{
				if($db->num_rows($ret_stock))
				{
					
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$prev_id = 0;
						// Get the combination details
						$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned 
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$row_count = 1;	
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if($prev_id==0)
								{
									$pname 		= stripslashes($row_prod['product_name']);
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
														AND products_product_id='".$row_prod['product_id']."'";
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
								$data['product_id'] 					= 'P-'.$row_prod['product_id'];
								$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
								/*array_walk($data, "add_quotes");
								print implode(",", $data) ."\r\n";*/
								$cell_count = 0;
								foreach($data as $cell_data)
								{
								$worksheet->writeString($row_count, $cell_count, "$cell_data");
								$cell_count++;
								}
								$row_count++;
							}
						}
					}
				}		
			}
			elseif($main_opt=='prod_combprice') // case of product with combination price only
			{
				if($db->num_rows($ret_stock))
				{
					
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$prev_id = 0;
						// Get the combination details
						$sql_comb = "SELECT comb_id, comb_barcode, comb_price  
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$row_count = 1;	
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if($prev_id==0)
								{
									$pname 		= stripslashes($row_prod['product_name']);
									$prev_id 	= 1;
								}
								else
									$pname 		=  '    "';
								$data['product_name'] 	= $pname;	
								$comb_name_arr 			= array();
								// Get the combination details
								$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
													FROM 
														product_variable_combination_stock_details 
													WHERE 
														comb_id=".$row_comb['comb_id']." 
														AND products_product_id='".$row_prod['product_id']."'";
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
									
								$data['comb_name']				= implode(',',$comb_name_arr);
								$data['product_barcode'] 		= $row_comb['comb_barcode'];
								$data['product_varprice'] 		= $row_comb['comb_price'];
								$bulk_arr = array();
								// get the direct bulk discount details for current product
								$sql_bulk = "SELECT bulk_qty, bulk_price 
												FROM 
													product_bulkdiscount 
												WHERE 
													products_product_id = '".$row_prod['product_id']."'  
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
								$data['product_id'] 					= 'P-'.$row_prod['product_id'];
								$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
								/*array_walk($data, "add_quotes");
								print implode(",", $data) ."\r\n";*/
								$cell_count = 0;
								foreach($data as $cell_data)
								{
								$worksheet->writeString($row_count, $cell_count, "$cell_data");
								$cell_count++;
								}
								$row_count++;
							}
						}
					}
				}			
			}
			elseif($main_opt=='prod_combimage') // case of product with combination images only
			{
				if($db->num_rows($ret_stock))
				{
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$prev_id = 0;
						// Get the combination details
						$sql_comb = "SELECT comb_id, comb_barcode  
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$row_count = 1;	
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if($prev_id==0)
								{
									$pname 		= stripslashes($row_prod['product_name']);
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
														AND products_product_id='".$row_prod['product_id']."'";
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
								$prod_img_arr							= array();
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
								$data['product_id'] 					= 'P-'.$row_prod['product_id'];
								$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
								/*array_walk($data, "add_quotes");
								print implode(",", $data) ."\r\n";*/
								$cell_count = 0;
								foreach($data as $cell_data)
								{
								$worksheet->writeString($row_count, $cell_count, "$cell_data");
								$cell_count++;
								}
								$row_count++;
							}
						}
					}
				}		
			}
			elseif($main_opt=='prod_comball') // case of product stock
			{
				if($db->num_rows($ret_stock))
				{
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$prev_id = 0;
						// Get the combination details
						$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned 
										FROM 
											product_variable_combination_stock 
										WHERE 
											products_product_id = '".$row_prod['product_id']."'  
										ORDER BY 
											comb_id";
						$ret_comb = $db->query($sql_comb);
						if($db->num_rows($ret_comb))
						{
							$row_count = 1;	
							while ($row_comb = $db->fetch_array($ret_comb))
							{
								if($prev_id==0)
								{
									$pname 		= stripslashes($row_prod['product_name']);
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
														AND products_product_id='".$row_prod['product_id']."'";
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
									
								$data['comb_name']				= implode(',',$comb_name_arr);
								$data['product_barcode'] 		= $row_comb['comb_barcode'];
								$data['product_varstock'] 		= $row_comb['web_stock'];
								// Combination price
								$data['product_varprice'] 		= $row_comb['comb_price'];
								$bulk_arr = array();
								// get the direct bulk discount details for current product
								$sql_bulk = "SELECT bulk_qty, bulk_price 
												FROM 
													product_bulkdiscount 
												WHERE 
													products_product_id = '".$row_prod['product_id']."'  
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
									
								// Combination images
								$prod_img_arr							= array();
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
									
								$data['product_id'] 					= 'P-'.$row_prod['product_id'];
								$data['comb_id'] 						= 'C-'.$row_comb['comb_id'];
								/*array_walk($data, "add_quotes");
								print implode(",", $data) ."\r\n";*/
								$cell_count = 0;
								foreach($data as $cell_data)
								{
								$worksheet->writeString($row_count, $cell_count, "$cell_data");
								$cell_count++;
								}
								$row_count++;
							}
						}
					}
				}	
			}
		
		// Finish the spreadsheet, dumping it to the browser 
		$xls->close();


			/*elseif($main_opt=='prod_stock') // case of product stock
			{
				if($db->num_rows($ret_stock))
				{
					$prev_id = 0;
					while ($row_prod=$db->fetch_array($ret_stock))
					{
						$data		= $prod_img_arr = array();
						if ($prev_id!=$row_prod['product_id'])
							$data['product_name'] 							= $row_prod['product_name'];
						else
							$data['product_name'] 							= '    "';	
						$prev_id 											= $row_prod['product_id'];	
						$data['comb_name']									= '';
						if($row_prod['product_variablestock_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y' or $row_prod['product_variablecomboprice_allowed']=='Y')
							$data['product_barcode'] 							= 'N/A';
						else
							$data['product_barcode'] 						= $row_prod['product_barcode'];
						$data['product_variablestock_allowed']				= $row_prod['product_variablestock_allowed'];
						$data['product_webstock'] 							= ($row_prod['product_variablestock_allowed']=='N')?$row_prod['product_webstock']:'N/A';
						$data['product_variablecomboprice_allowed']			= $row_prod['product_variablecomboprice_allowed'];
						if($data['product_variablecomboprice_allowed']=='Y')
							$data['product_webprice']						= 'N/A';
						else
							$data['product_webprice'] 							= $row_prod['product_webprice'];
						$data['product_bulkdiscount_allowed']				= $row_prod['product_bulkdiscount_allowed'];
						$data['product_bulkdiscount_value']					= '';
						if($row_prod['product_variablecomboprice_allowed']=='Y') // If combination price is set to Y, then there is no bulk discount
							$data['product_bulkdiscount_value']	= 'N/A';				
						else // case if combination price is not set Y. So there can be direct bulk discount
						{
							// get the direct bulk discount details for current product
							$sql_bulk = "SELECT bulk_qty, bulk_price 
											FROM 
												product_bulkdiscount 
											WHERE 
												products_product_id = '".$row_prod['product_id']."'  
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
									$data['product_bulkdiscount_value']	= implode(',',$bulk_arr);
							}
						}	
						$data['product_variablecombocommon_image_allowed']	= $row_prod['product_variablecombocommon_image_allowed'];
						$data['product_image_ids']							= '';
						if($row_prod['product_variablecombocommon_image_allowed']=='Y') // Direct images are applicable only if variable comb 
							$data['product_image_ids']							= 'N/A';
						else
						{
							// Get the ids of images linked with current product
							$sql_img_id = "SELECT images_image_id  
												FROM 
													images_product  
												WHERE 
													products_product_id='".$row_prod['product_id']."'  
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
									$data['product_image_ids'] = implode(',',$prod_img_arr);
							}
						}
						
						$data['product_id'] 						= 'P-'.$row_prod['product_id'];
						$data['comb_id'] 							= '';
						array_walk($data, "add_quotes");
						print implode(",", $data) ."\r\n";
						
						// Check whether variable combination exists
						if($row_prod['product_variablestock_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y' or $row_prod['product_variablecomboprice_allowed']=='Y')
						{
							// Get the combination details
							$sql_comb = "SELECT comb_id, web_stock, comb_barcode, comb_price, comb_img_assigned 
											FROM 
												product_variable_combination_stock 
											WHERE 
												products_product_id = '".$row_prod['product_id']."'  
											ORDER BY 
												comb_id";
							$ret_comb = $db->query($sql_comb);
							if($db->num_rows($ret_comb))
							{
								while ($row_comb = $db->fetch_array($ret_comb))
								{
									$data['product_name'] 		= '    "';	
									$comb_name_arr 				= $prod_img_arr = array();
									// Get the combination details
									$sql_combdet = "SELECT product_variables_var_id, product_variable_data_var_value_id 
														FROM 
															product_variable_combination_stock_details 
														WHERE 
															comb_id=".$row_comb['comb_id']." 
															AND products_product_id='".$row_prod['product_id']."'";
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
										
									$data['comb_name']									= implode(',',$comb_name_arr);
									$data['product_barcode'] 							= $row_comb['comb_barcode'];
									$data['product_variablestock_allowed']				= $row_prod['product_variablestock_allowed'];
									if($data['product_variablestock_allowed']=='N')
										$data['product_webstock'] 						= 'N/A';
									else
										$data['product_webstock'] 						= $row_comb['web_stock'];
									$data['product_variablecomboprice_allowed']			= $row_prod['product_variablecomboprice_allowed'];
									if($data['product_variablecomboprice_allowed']=='N')
										$data['product_webprice']						= 'N/A';
									else
										$data['product_webprice'] 						= $row_comb['comb_price'];
									$data['product_bulkdiscount_allowed']				= $row_prod['product_bulkdiscount_allowed'];
									$data['product_bulkdiscount_value']					= '';
									if($row_prod['product_variablecomboprice_allowed']=='Y') // If combination price is set to Y
									{	
										$bulk_arr = array();
										// get the direct bulk discount details for current product
										$sql_bulk = "SELECT bulk_qty, bulk_price 
														FROM 
															product_bulkdiscount 
														WHERE 
															products_product_id = '".$row_prod['product_id']."'  
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
										}
									
									}	
									else
										$data['product_bulkdiscount_value']				= 'N/A';
									$data['product_variablecombocommon_image_allowed']	= $row_prod['product_variablecombocommon_image_allowed'];
									$data['product_image_ids'] 							= '';
									if($row_prod['product_variablecombocommon_image_allowed']=='Y') // Get combination images
									{
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
												$data['product_image_ids'] 	= implode(',',$prod_img_arr);
										}
									}
									else
										$data['product_image_ids'] 			= 'N/A';
									$data['product_id'] 								= 'P-'.$row_prod['product_id'];
									$data['comb_id'] 									= 'C-'.$row_comb['comb_id'];
									array_walk($data, "add_quotes");
									print implode(",", $data) ."\r\n";
								}
							}
						}
					}	
				}
			}*/
		}
		else
		{
			if($main_opt=='prod')
			{	
				echo '<script type="text/javascript">alert("No products found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_var')
			{			
				echo '<script type="text/javascript">alert("No products variables/ variable messages found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_label')
			{			
				echo '<script type="text/javascript">alert("No products labels found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_fixedstock' or $main_opt=='prod_combstock')
			{			
				echo '<script type="text/javascript">alert("No Stock Details found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_fixedprice'  or $main_opt=='prod_combprice')
			{			
				echo '<script type="text/javascript">alert("No Price Details found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_normalimage' or $main_opt=='prod_combimage')
			{			
				echo '<script type="text/javascript">alert("No Image Details found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			elseif($main_opt=='prod_fixedall' or $main_opt=='prod_comball')
			{			
				echo '<script type="text/javascript">alert("No Details found");
							window.location = \'home.php?request=database_offline\';
						</script>';
			}
			exit;
		}
	}
	elseif($_REQUEST['cur_mod']=='offline_upload') // ################################# case of upload ####################################
	{
		$select_type 	= $_REQUEST['select_upload'];
		$alert 			= '';
		// Validating the file being uploaded
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
			echo '<script type="text/javascript">	window.location = \'home.php?request=database_offline&c='.$err_no.'\';
					</script>';
			exit;
		}
		else // case if no error so parsing of file is required
		{ 
			if($select_type=='prod') // case of uploading products
			{
				$fp			= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 		= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	 	= $done_cnt = $err_cnt =  0;
				$css		="<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 		= "				
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='45%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
                                $long_desc_exist = false;
				while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) {
					$num = count($data);
					$cur_msg='';
					$error = '';
                                        if($line==0) // case of header row
                                        {
                                            // Check whether header details are correct
                                            $data_count = count($data);
                                            $var_count  = count($var_prod_arr);
                                             
                                            if($data_count!=$var_count) // in case if array counts in database_offline_variables file and csv are different
                                            {
                                                if($data_count=($var_count-1))
                                                {
                                                    unset($var_prod_arr['product_longdesc']); // unsetting the long desc from array
                                                    $i=0;
                                                    foreach ($var_prod_arr as $k=>$v)
                                                    {
														if($v != trim($data[$i]))
                                                        {
                                                          $error = 'Sorry!! Header columns does not match'; 
                                                        }
                                                        $i++;
                                                    }
                                                    if($data[7]=='Long Description') // case if long description is missing
                                                    {
                                                        $long_desc_exist = true;
                                                    }
                                                }
                                                else
                                                    $error = 'Sorry!! Header columns does not match';
                                            }
                                            else
                                                $long_desc_exists = true;
                                            if($error!='')
                                            {
                                                $table .="<tr>";
                                                $table .="<td class='listingtablestyleA' colspan='4' align='center'>".$error."</td>";
                                                $table .="</tr></table>";
                                                echo '<br><br>';
                                                echo $css;
                                                echo $table;
                                                echo $alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()"><strong>Click here to go back to Database Offline section</strong></a></center>';
                                                exit;
                                            }
                                        }
					if($line!=0)
					{	
						$i=0;
                        $product_id 									= trim($data[$i]);
						$i++;
						$product_name									= trim($data[$i]);
						$i++;
                        $product_adddate								= trim($data[$i]);
						$i++;
                        $product_barcode								= trim($data[$i]);
						$i++;
                        $manufacture_id									= trim($data[$i]);
						$i++;
                        $product_model									= trim($data[$i]);
						$i++;
                        $product_shortdesc								= trim($data[$i]);
						if($long_desc_exists)
						{
						   $i++;
						   $product_longdesc							= trim($data[$i]);
						}
                        $i++;
						$product_keyword								= trim( $data[$i]);
						$i++;
						$product_hide									= trim( $data[$i]);
                        $i++;
						$product_costprice								= trim($data[$i]);
                        $i++;
						$product_webprice								= trim($data[$i]);
                        $i++;
						$product_weight									= trim($data[$i]);
						$i++;
                        $product_reorderqty								= trim($data[$i]);
						$i++;
                        $product_extrashippingcost						= trim($data[$i]);
						$i++;
                        $product_bonuspoints							= trim($data[$i]);
						$i++;
                        $product_discount_enteredasval					= trim($data[$i]);
						$i++;
                        $product_discount								= trim($data[$i]);
						$i++;
                        $product_applytax								= trim($data[$i]);
						$i++;
                        $product_variablestock_allowed					= trim($data[$i]);
						$i++;
                        $product_webstock								= trim($data[$i]);
						$i++;
                        $product_preorder_allowed						= trim($data[$i]);
						$i++;
                        $product_total_preorder_allowed					= trim($data[$i]);
						$i++;
                        $product_instock_date							= trim($data[$i]);
						$i++;
                        $product_deposit								= trim($data[$i]);
						$i++;
                        $product_deposit_message						= trim($data[$i]);
						$i++;
                        $product_show_cartlink							= trim($data[$i]);
						$i++;
                        $product_show_enquirelink						= trim($data[$i]);
						$i++;
                        $product_sizechart_mainheading					= trim($data[$i]);
						$i++;
                        $product_show_var_newrow						= trim($data[$i]);
						$i++;
                        $product_stock_notification						= trim($data[$i]);
						$i++;
                        $product_hide_outofstock						= trim($data[$i]);
						$i++;
                        $product_order_even_outofstock					= trim($data[$i]);
						$i++;
                        $product_allow_free_delivery					= trim($data[$i]);
						$i++;
                        $product_qty_caption							= trim($data[$i]);
						$i++;
                        $product_qty_type								= trim($data[$i]);
						$i++;
                        $product_qty_drop_values						= trim($data[$i]);
						$i++;
                        $product_qty_prefix								= trim($data[$i]);
						$i++;
                        $product_qty_suffix								= trim($data[$i]);
						$i++;
                        $product_normal_price_prefix					= trim($data[$i]);
						$i++;
                        $product_normal_price_suffix					= trim($data[$i]);
						$i++;
                        $product_from_price_prefix						= trim($data[$i]);
						$i++;
                        $product_from_price_suffix						= trim($data[$i]);
						$i++;
                        $product_special_price_prefix					= trim($data[$i]);
						$i++;
                        $product_special_price_suffix					= trim($data[$i]);
						$i++;
                        $product_discount_prefix						= trim($data[$i]);
						$i++;
                        $product_discount_suffix						= trim($data[$i]);
						$i++;
                        $product_yousave_prefix							= trim($data[$i]);
						$i++;
                        $product_yousave_suffix							= trim($data[$i]);
						$i++;
                        $product_noprice_caption						= trim($data[$i]);
						$i++;
                        $product_show_pricepromise						= trim($data[$i]);
						$i++;
                        $product_show_saleicon							= trim($data[$i]);
						$i++;
                        $product_sale_caption							= trim($data[$i]);
						$i++;
                        $product_show_newicon							= trim($data[$i]);
						$i++;
                        $product_new_caption							= trim($data[$i]);
						$i++;
                        $product_var_comb_img_allow						= trim($data[$i]);
						$i++;
                        $product_images									= trim($data[$i]);
						$i++;
                        $product_var_comb_price_allow					= trim($data[$i]);
						$i++;
                        $product_bulkdiscount_allow						= trim($data[$i]);
						$i++;
                        $product_bulkdiscount_values					= trim($data[$i]);	
                        // ############### Validating the fields #######################
						// Extract the product id
						$product_arr									= explode('-',$product_id);
						if ($product_arr[0]!='P' or count($product_arr)!=2 or (!is_numeric($product_arr[1])))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
							if(is_numeric($product_id))
							{
								// Check whether the product exists in current database for current site
								$sql_prod_check = "SELECT product_id,product_variablestock_allowed, product_variablecomboprice_allowed, 
														product_variablecombocommon_image_allowed 
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
									$error .= '-- Product not found in site --';
								}
								else
									$row_prod_check = $db->fetch_array($ret_prod_check);
							} 
							else
							{
							  if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';  
							}
						}
						// Check whether product name exists
						if($product_name=='')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= ' -- Product name cannot be blank --';
						}
						// Validating the product add date
						$datefirst_arr				= explode('-',$product_adddate);
						if ($datefirst_arr[0]!='D' or count($datefirst_arr)!=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Add Date Format -- ('.$product_adddate.')';
						}	
						else
						{
							$add_main_arr 	= explode(" ",$datefirst_arr[1]);
							$time_arr			= explode(":",$add_main_arr[1]);
							$date_arr			= explode("/",$add_main_arr[0]);
							if (count($date_arr)!=3)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product add date -- ('.$product_adddate.')';
							}
							elseif (!is_numeric($date_arr[0]) or !is_numeric($date_arr[1]) or !is_numeric($date_arr[2]))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product add date -- ('.$product_adddate.')';
							}
							else
							{
								$day					= $date_arr[0];
								$month					= $date_arr[1];
								$year					= $date_arr[2];
								// Check whether date is valid or not
								if(checkdate($month,$day,$year))
								{
									// Check whether time is valid
									$hour			= $time_arr[0];
									$minute			= $time_arr[1];
									$second			= $time_arr[2];
									// If hour, minute or second is non numeric then the all parameters 
									if (!is_numeric($hour) or !is_numeric($minute) or !is_numeric($second))
									{
										$hour 	= 0;
										$minute = 0;
										$second = 0;		
									}	
									else
									{
										if ($hour>=24 or $hour<0 or $minute>=60 or $minute<0 or $second>=60 or $second<0) 
										{
											$hour = 0;
											$minute = 0;
											$second = 0;		
										} 
									}
									$time = $hour.':'.$minute.':'.$second;
									$product_adddate = $year.'-'.$month.'-'.$day.' '.$time; // concatenation the date and time to a string
								}	
								else
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Invalid Product add date --  ('.$product_adddate.')';	
								}						
							}
						}	
						// Checking whether short description in specified
						if($product_shortdesc=='')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Short Description not specified -- ';
						}
						
						// Checking whether format of product hide is correct or not
						if(strtolower($product_hide)!='y' and strtolower($product_hide)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Product hide should have either Y or N value -- ('.$product_hide.')';
						}
						
						if(!is_numeric($product_costprice))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Cost price should be numeric -- ('.$product_costprice.')';
						}						
						if(!is_numeric($product_webprice))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Web price should be numeric -- ('.$product_webprice.')';
						}
						if(!is_numeric($product_weight))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Product weight should be numeric -- ('.$product_weight.')';
						}
						if(!is_numeric($product_reorderqty))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Reorder Qty should  be numeric -- ('.$product_reorderqty.')';
						}
						if( !is_numeric($product_extrashippingcost))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Extra shipping cost should be numeric -- ('.$product_extrashippingcost.')';
						}
						if($product_bonuspoints)
						{
							if(!is_numeric($product_bonuspoints))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Bonus points should be numeric -- ('.$product_bonuspoints.')';
							}
						}
						else
							$product_bonuspoints = 0;
						
						// product discount section
						if($product_discount!='')
						{
							if (!is_numeric($product_discount))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Product discount should be numeric -- ('.$product_discount.')';
							}
							else
							{
								switch($product_discount_enteredasval)
								{
									case '%': // case if discount type is %
										// Check whether discount iis >=100
										if ($product_discount>=100 or $product_discount<0)
										{
											if ($error!='')
												$error .= '<br/>';
											$error .= '-- Discount % should be greater than or equal to 0 and less than 100 -- ('.$product_discount.')';
										}	
										else
											$product_discount_enteredasval 	= 0;
									break;
									case 'Exact': // case if discount type is exact discounted price
									case 'exact':
										$product_discount_enteredasval 		= 2;
									break;
									case 'Value': // case if discount type is discounted value
									case 'value':
										$product_discount_enteredasval 		= 1;
									break;
									default: // case if iinvalid discount type is specified
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Invalid Discount Type. The allowed values are  %, Value and Exact --  ('.$product_discount_enteredasval.')';
									break;
								};
							}
						}
						else
						{
							$product_discount 						= 0;
							$product_discount_enteredasval  	= 0;
						}
						// Bulk Discount allowed or not
						
						/*if(strtolower($product_bulkdiscount_allowed)!='y' and strtolower($product_bulkdiscount_allowed)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Bulk discount allowed  should have either Y or N value -- ('.$product_bulkdiscount_allowed.')';
						}*/
						// Apply tax
						if(strtolower($product_applytax)!='y' and strtolower($product_applytax)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Apply tax should have either Y or N value -- ('.$product_applytax.')';
						}
						if ($row_prod_check['product_variablestock_allowed']=='N')
						{
							if(!is_numeric($product_webstock))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Webstock should be numeric -- ('.$product_webstock.')';
							}
						}	
						// Variable stock allowed
						if($row_prod_check['product_variablestock_allowed']=='Y') // if variable stock is set to Y, then webstock should be 0 otherwise error message.
						{
							if(is_numeric($product_webstock))// do the following only if webstock is numeric
							{
								if ($product_webstock>0)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Variable stock is set to Y so Fixed webstock should be set to 0 -- ('.$product_webstock.')';
								}	
							}	
						} 
						
						if(strtolower($product_preorder_allowed)!='y' and strtolower($product_preorder_allowed)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Preorder allowed  should have either Y or N value -- ('.$product_preorder_allowed.')';
						}
						elseif(strtolower($product_preorder_allowed)=='y')
						{
							if(!is_numeric($product_total_preorder_allowed))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Preorder allowed  should have either Y or N value -- ('.$product_total_preorder_allowed.')';
							}
							$datefirst_arr				= explode('-',$product_instock_date);
							if ($datefirst_arr[0]!='D' or count($datefirst_arr)!=2)
							{
								if ($error!='')
								$error .= '<br/>';
								$error .= '-- Invalid Instock Date Format -- ('.$product_instock_date.')';
							}	
							else
							{	
								$instock_arr = explode("/",$datefirst_arr[1]);
								if (count($instock_arr)!=3)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Invalid instock date --';
								}	
								else // case if count is 3
								{
									$day 		= $instock_arr[0];
									$month	= $instock_arr[1];
									$year		= $instock_arr[2];
									if ($month=='00' and $day =='00' and $year='0000')
									{
										$product_instock_date = '0000-00-00';
									}
									if(!is_numeric($day) or !is_numeric($month) or !is_numeric($year))
									{
										if ($error!='')
											$error .= '<br/>';
										$error .='-- Invalid instock date --  ('.$product_instock_date.')';
									}
									else // check whether the date if valid using checkdate function
									{
										if(!checkdate($month,$day,$year))
										{
											if ($error!='')
												$error .= '<br/>';
											$error .='-- Invalid instock date -- ('.$product_instock_date.')';
										}
										else
											$product_instock_date = $year.'-'.$month.'-'.$day;
									}
								}
							}	
						}
						elseif(strtolower($product_preorder_allowed)=='n')
						{
							$product_instock_date 				= '0000-00-00';
							$product_total_preorder_allowed	= 0;
						}
						if ($product_deposit!='')
						{
							if(is_numeric($product_deposit))
							{
								if($product_deposit>100 or $product_deposit<0)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Product deposit should be greater or equal to 0 and less than 100 -- ('.$product_deposit.')';
								}
							}
							else
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Product deposit should be numeric -- ('.$product_deposit.')';
							}	
						}
						if(strtolower($product_show_cartlink)!='y' and strtolower($product_show_cartlink)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show cart link should have either Y or N value -- ('.$product_show_cartlink.')';
						}	
						else
							$product_show_cartlink = (strtolower($product_show_cartlink)=='y')?1:0;
						
						if(strtolower($product_show_enquirelink)!='y' and strtolower($product_show_enquirelink)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show enquiry link should have either Y or N value -- ('.$product_show_enquirelink.')';
						}	
						else
							$product_show_enquirelink = (strtolower($product_show_enquirelink)=='y')?1:0;
						
							
						if(strtolower($product_show_var_newrow)!='y' and strtolower($product_show_var_newrow)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show Variables in new row should have either Y or N value -- ('.$product_show_var_newrow.')';
						}	
						else
							$product_show_var_newrow = (strtolower($product_show_var_newrow)=='y')?1:0;	

						
						if(strtolower($product_stock_notification)!='y' and strtolower($product_stock_notification)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Allow stock notification should have either Y or N value -- ('.$product_stock_notification.')';
						}	
						else
							$product_stock_notification = (strtolower($product_stock_notification)=='y')?'Y':'N';	
						
						if(strtolower($product_hide_outofstock)!='y' and strtolower($product_hide_outofstock)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Hide Product when out of stock should have either Y or N value -- ('.$product_hide_outofstock.')';
						}	
						else
							$product_hide_outofstock = (strtolower($product_hide_outofstock)=='y')?'Y':'N';	
						
						if(strtolower($product_order_even_outofstock)!='y' and strtolower($product_order_even_outofstock)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Allow ordering even if out of stock should have either Y or N value -- ('.$product_order_even_outofstock.')';
						}	
						else
							$product_order_even_outofstock = (strtolower($product_order_even_outofstock)=='y')?'Y':'N';	
						
						if(strtolower($product_allow_free_delivery)!='y' and strtolower($product_allow_free_delivery)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Allow free Delivery should have either Y or N value -- ('.$product_allow_free_delivery.')';
						}	
						else
							$product_allow_free_delivery = (strtolower($product_allow_free_delivery)=='y')?1:0;	
						
						if(strtolower($product_qty_type)!='normal' and strtolower($product_qty_type)!='dropdown')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Qty type should have either Normal or Dropdown as value -- ('.$product_qty_type.')';
						}	
						else
						{
							$product_qty_type = (strtolower($product_qty_type)=='normal')?'NOR':'DROP';
							if($product_qty_type=='DROP')
							{
								if($product_qty_drop_values=='')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Values to be displayed in Qty drop down box not specified';
								}
								else
								{
									$qtydrop_arr = explode(',',$product_qty_drop_values);
									for($i=0;$i<count($qtydrop_arr);$i++)
									{
										$qtydrop_arr[$i] = trim($qtydrop_arr[$i]);
										if(!is_numeric(trim($qtydrop_arr[$i])))
										{
											if ($error!='')
												$error .= '<br/>';
											$error .='-- Qty drop down value should be numeric ('.$qtydrop_arr[$i].')';
										
										}
									}
									$product_qty_drop_values = implode(',',$qtydrop_arr);
								}
							}
						}	
						
						if(strtolower($product_show_pricepromise)!='y' and strtolower($product_show_pricepromise)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show Price Promise button in details page should have either Y or N value -- ('.$product_show_pricepromise.')';
						}	
						else
							$product_show_pricepromise = (strtolower($product_show_pricepromise)=='y')?1:0;

						if(strtolower($product_show_saleicon)!='y' and strtolower($product_show_saleicon)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show Sale Icon should have either Y or N value -- ('.$product_show_saleicon.')';
						}	
						else
						{
							$product_show_saleicon = (strtolower($product_show_saleicon)=='y')?1:0;
							if($product_show_saleicon==1)
							{
								/*if($product_sale_caption=='')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Please specify the caption for sale icon.';
								}*/
							}
						}	

						if(strtolower($product_show_newicon)!='y' and strtolower($product_show_newicon)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Show Sale Icon should have either Y or N value -- ('.$product_show_newicon.')';
						}	
						else
						{
							$product_show_newicon = (strtolower($product_show_newicon)=='y')?1:0;
							if($product_show_newicon==1)
							{
								/*if($product_new_caption=='')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .='-- Please specify the caption for New icon.';
								}*/
							}
						}
						$img_arr = array();
						if($row_prod_check['product_variablecombocommon_image_allowed']=='Y') // if variable comb image is set to Y then direct image setting not allowed
						{
							if ($product_images!='')
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Variable combination images is activated for product. So direct image assigning not allowed';
							}	
								
						}
						else
						{
							if ($product_images!='')
							{
								$img_arr = explode('=>',$product_images);
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
									}	
								}
							}
						}
						if(strtolower($product_bulkdiscount_allow)!='y' and strtolower($product_bulkdiscount_allow)!='n')
						{
							if ($error!='')
								$error .= '<br/>';
							$error .='-- Bulk Discount allowed should have either Y or N value -- ('.$product_bulkdiscount_allow.')';
						}	
						$bulk_arr = array();
						if($row_prod_check['product_variablecomboprice_allowed']=='Y') // if variable comb price is set to Y then direct bulk discount not allowed
						{
							if($product_bulkdiscount_values!='')
							{
								if ($error!='')
									$error .= '<br/>';
								$error .='-- Direct bulk discount not allowed as variable combination price is activated for the product.';
							}
						}
						else
						{
							if($product_bulkdiscount_allow=='Y')
							{
								if($product_bulkdiscount_values=='')
								{
									if ($error!='')
									$error .= '<br/>';
									$error .='-- Please specify the bulk discount values for the product';
								}
								else
								{
									$bulk_arr 	= explode(',',$product_bulkdiscount_values);
									$blkqty_arr = $blkprice_arr = array();
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
									if(count($blkqty_arr))
									{
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
									}
								}
							}
						}
						
						$cur_msg = $error;
						if($error=='') // update the product table
						{
						
							// Executing the update statement in products table
							$sql_update = "UPDATE products 
													SET 
														product_adddate 				= '".$product_adddate."',
														product_name					='".add_slash($product_name)."' ";
							if($row_prod_check['product_variablestock_allowed'] =='N' and $row_prod_check['product_variablecomboprice_allowed'] =='N' and $row_prod_check['product_variablecombocommon_image_allowed']=='N')
							{
								$sql_update .=",product_barcode							= '".add_slash($product_barcode)."'";
							}
							$sql_update .= ",manufacture_id							= '".add_slash($manufacture_id)."',
											product_model							= '".add_slash($product_model)."',
											product_shortdesc						= '".add_slash($product_shortdesc)."',";
                                                        if($long_desc_exists)
                                                            $sql_update .=" product_longdesc						= '".add_slash($product_longdesc,false)."',";
                                                        $sql_update .="                                
											product_keywords						= '".add_slash($product_keyword,false)."',
											product_hide							= '".strtoupper(add_slash($product_hide))."',
											product_costprice						= '".add_slash($product_costprice)."',
											product_webprice						= '".add_slash($product_webprice)."',
											product_weight							= '".add_slash($product_weight)."',
											product_reorderqty						= '".add_slash($product_reorderqty)."',
											product_extrashippingcost				= '".add_slash($product_extrashippingcost)."',
											product_bonuspoints						= '".add_slash($product_bonuspoints)."',
											product_discount						= '".add_slash($product_discount)."',
											product_discount_enteredasval			= '".add_slash($product_discount_enteredasval)."',
											product_applytax						= '".strtoupper(add_slash($product_applytax))."'";
							if($row_prod_check['product_variablestock_allowed'] =='N')
							{				
								$sql_update .= ",product_webstock='".$product_webstock."'";
							}
							$sql_update .=",product_preorder_allowed				= '".strtoupper(add_slash($product_preorder_allowed))."',
											product_total_preorder_allowed			= '".add_slash($product_total_preorder_allowed)."',
											product_instock_date					= '".add_slash($product_instock_date)."',
											product_deposit							= '".add_slash($product_deposit)."',
											product_deposit_message					= '".add_slash($product_deposit_message)."',
											product_show_cartlink					= '".add_slash($product_show_cartlink)."',
											product_show_enquirelink				= '".add_slash($product_show_enquirelink)."',
											product_sizechart_mainheading			= '".add_slash($product_sizechart_mainheading)."',
											product_variable_in_newrow				= '".add_slash($product_show_var_newrow)."',
											product_stock_notification_required		= '".add_slash($product_stock_notification)."',
											product_hide_on_nostock					= '".add_slash($product_hide_outofstock)."',
											product_alloworder_notinstock			= '".add_slash($product_order_even_outofstock)."',
											product_freedelivery					= '".add_slash($product_allow_free_delivery)."',
											product_det_qty_caption					= '".add_slash($product_qty_caption)."',
											product_det_qty_type					= '".add_slash($product_qty_type)."'";
							
							if($product_qty_type=='DROP')
							{
								$drop_values = $product_qty_drop_values;
							}
							else
								$drop_values = '';
							
							$sql_update .= ",product_det_qty_drop_values			= '".add_slash($drop_values)."',
											product_det_qty_drop_prefix				= '".add_slash($product_qty_prefix)."',
											product_det_qty_drop_suffix				= '".add_slash($product_qty_suffix)."',
											price_normalprefix						= '".add_slash($product_normal_price_prefix)."',
											price_normalsuffix						= '".add_slash($product_normal_price_suffix)."',
											price_fromprefix						= '".add_slash($product_from_price_prefix)."',
											price_fromsuffix						= '".add_slash($product_from_price_suffix)."',
											price_specialofferprefix				= '".add_slash($product_special_price_prefix)."',
											price_specialoffersuffix				= '".add_slash($product_special_price_suffix)."',
											price_discountprefix					= '".add_slash($product_discount_prefix)."',
											price_discountsuffix					= '".add_slash($product_discount_suffix)."',
											price_yousaveprefix						= '".add_slash($product_yousave_prefix)."',
											price_yousavesuffix						= '".add_slash($product_yousave_suffix)."',
											price_noprice							= '".add_slash($product_noprice_caption)."',
											product_show_pricepromise				= '".add_slash($product_show_pricepromise)."',
											product_saleicon_show					= '".add_slash($product_show_saleicon)."',
											product_saleicon_text					= '".add_slash($product_sale_caption)."',
											product_newicon_show					= '".add_slash($product_show_newicon)."',
											product_newicon_text					= '".add_slash($product_new_caption)."',
											product_bulkdiscount_allowed			= '".add_slash($product_bulkdiscount_allow)."' ";
							
							$sql_update .= " WHERE 
												product_id = $product_id  
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$db->query($sql_update);
							$assigned_image_id = array();
							if(count($img_arr))
							{
								$existingimg_arr = array();
								for($i=0;$i<count($img_arr);$i++)
								{
									// Check whether current image id is valid
									$sql_img_checkagain = "SELECT image_id 
															FROM 
																images 
															WHERE 
																image_id='".$img_arr[$i]."' 
																AND sites_site_id=$ecom_siteid 
															LIMIT 
																1";
									$ret_img_checkagain = $db->query($sql_img_checkagain);
									if($db->num_rows($ret_img_checkagain))
									{
									   $assigned_image_id[] = $img_arr[$i];
										// Check whether the image already assigned for current product
										$sql_check = "SELECT id 
														FROM 
																images_product 
														WHERE 
																images_image_id='".$img_arr[$i]."'  
																AND products_product_id=$product_id 
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
												$insert_array['products_product_id']	= $product_id;
												$insert_array['images_image_id']		= $img_arr[$i];
												$insert_array['image_title']			= add_slash(stripslashes($row_img_check['image_title']));
												$insert_array['image_order']			= 0;
												$db->insert_from_array($insert_array,'images_product');										
										}
									 } 
								}	
								// Delete unwanted images
								if(count($assigned_image_id))
								{
									$img_str = implode(',',$assigned_image_id);
									if($img_str)
									{
											$del_unwanted_img = "DELETE FROM 
																	images_product 
																WHERE 
																	products_product_id = $product_id 
																	AND images_image_id NOT IN ($img_str)";
											$db->query($del_unwanted_img);
									}
								}
							}	
							else // case if no image ids specified
							{
								// Delete all direct image mapping for current product
								$sql_delete = "DELETE FROM 
														images_product 
													WHERE 
														products_product_id=$product_id";
								$db->query($sql_delete);
							}
							

							// Handling the case of bulk discount
							if(count($blkqty_arr))
							{
								// delete all direct bulk discounts
								$sel_del = "DELETE FROM product_bulkdiscount 
												WHERE 
													products_product_id=$product_id 
													AND comb_id=0";
								$db->query($sel_del);
								for($i=0;$i<count($blkqty_arr);$i++)
								{
									$insert_array								= array();
									$insert_array['products_product_id']		= $product_id;
									$insert_array['bulk_qty']					= $blkqty_arr[$i];
									$insert_array['bulk_price']					= $blkprice_arr[$i];
									$insert_array['comb_id']					= 0;
									$db->insert_from_array($insert_array,'product_bulkdiscount');
								}
							}
							else // case if no direct bulk discount is not specified. So deleting any direct bulk discount exits
							{
								// delete all direct bulk discounts
								$sel_del = "DELETE FROM product_bulkdiscount 
												WHERE 
													products_product_id=$product_id 
													AND comb_id=0";
								$db->query($sel_del);
							}
							
							// Run function to recalculate the stock for current product and place it in in the actual stock field
							recalculate_actual_stock($product_id);
							$cur_msg  = '';
							$done_cnt++;
						}		  	
						if($cur_msg!='')
						{	
							$err_cnt++;
							$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							$table .="<tr>";
							$table .="<td class='".$cls."'>".$err_cnt."</td>";
							$table .="<td class='".$cls."'>".$product_id."</td>";
							$table .="<td class='".$cls."'>".$product_name."</td>";
							$table .="<td class='".$cls."'>".$cur_msg."</td>";
							$table .="</tr>";
						}	
					}	
					$line++;
				}
				$table .="</table>";
				check_promotionalcode_integrity();
				check_combo_integrity();
				$top_alert = '<br/><center>Product Update Operation Completed' ;
				$top_alert .='<br/><br/>Total Products Updated : '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following Product not updated due to errors<br/>'.$table;//.$top_alert;
				}
			}
			elseif($select_type=='prod_var') // case of uploading product variables
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 		= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	 	= $done_cnt = $err_cnt =  0;
				$css		= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Variable Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Variable Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Variable Type</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
				{
					$num = count($data);
					$cur_msg='';
					$error = '';
					if($line!=0)
					{				
						
						$product_name			= trim($data[0]);
						$var_name				= trim($data[1]);
						$var_type				= trim($data[2]);
						$var_sort_order			= trim($data[3]);
						$var_hidden				= trim($data[4]);
						$var_value_exists		= trim($data[5]);
						$var_price				= trim( $data[6]);
						$var_value				= trim($data[7]);
						$var_value_price		= trim($data[8]);
						$var_value_sort_order	= trim($data[9]);
						$product_id 			= trim($data[10]);
						$var_id					= trim($data[11]);
						$var_value_id			= trim($data[12]);
					
						// ############### Validating the fields #######################
						
						// Extract the product id
						$product_arr				= explode('-',$product_id);
						if ($product_arr[0]!='P' or count($product_arr)!=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
							if(is_numeric($product_id))
							{
								// Check whether the product exists in current database for current site
								$sql_prod_check = "SELECT product_id,product_name  
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
										$error .= '-- Product not found in site --';
								}
								else
								{
										$row_prod_check = $db->fetch_array($ret_prod_check);
										$product_name		= stripslashes($row_prod_check['product_name']);
								}
							}
							else
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}
						}
						// Extract the variable / message 
						$var_arr										= explode('-',$var_id);
						if ($var_arr[0]!='V' or count($var_arr)!=2 or (!is_numeric($var_arr[1])))
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Variable Id Format --('.$var_id.')';
						}	
						else
						{
							$var_id = addslashes(trim($var_arr[1]));
                                                        if(!is_numeric($var_id))
                                                        {
                                                            if ($error!='')
                                                                $error .= '<br/>';
                                                            $error .= '-- Invalid Variable Id Format --('.$var_id.')';
                                                        }
						}
						// Check whether var_value_id exists
						if ($var_value_id=='') 
						{
							// case if current row contains the variable details itself
							// check whether variable type is valid or not
							if(strtolower($var_type)!='var' and strtolower($var_type)!='msg')
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid variable type -- ('.$var_type.')';
							}
							else
							{
								// If type is variable, then check whether variable exists in site
								if (strtolower($var_type)=='var')
								{
									// Check whether the variable exists in current database for current site
									$sql_var_check = "SELECT a.var_id,a.var_name  
														FROM 
															product_variables a,products b
														WHERE 
															a.var_id='".$var_id ."' 
															AND b.product_id = '".$product_id."' 
															AND b.sites_site_id = $ecom_siteid 
															AND a.products_product_id=b.product_id 
														LIMIT 
															1";
									$ret_var_check = $db->query($sql_var_check);
									if ($db->num_rows($ret_var_check)==0)
									{
										$error .= '-- Variable not found in site --';
									}
									else
									{
										$row_var_check 			= $db->fetch_array($ret_var_check);
										$temp_var_name		= stripslashes($row_var_check['var_name']);
									}
									$typ = 'Variable';
								}
								else
								{
									// Check whether the variable message exists in current database for current site
									$sql_varmsg_check = "SELECT a.message_id,a.message_title  
															FROM 
																product_variable_messages a,products b
															WHERE 
																a.message_id='".$var_id ."' 
																AND b.product_id = '".$product_id."' 
																AND b.sites_site_id = $ecom_siteid 
																AND a.products_product_id=b.product_id 
															LIMIT 
																1";
									$ret_varmsg_check = $db->query($sql_varmsg_check);
									if ($db->num_rows($ret_varmsg_check)==0)
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Variable Message not found in site --';
									}
									else
									{
										$row_varmsg_check 		= $db->fetch_array($ret_varmsg_check);
										$temp_var_name			= stripslashes($row_varmsg_check['message_title']);
									}
									$typ = 'Message';
								}	
							}
                                                        if(trim($var_name)=='')
                                                        {
                                                            if ($error!='')
                                                                $error .= '<br/>';
                                                            if($typ=='Variable')
                                                                $error .= 'Variable name should not be blank';  
                                                            elseif($typ=='Message')
                                                                $error .= 'Variable Message name should not be blank';  
                                                            
                                                        }
							// checking sort order 
							if (!is_numeric($var_sort_order))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- '.$typ.' sort order should be numeric -- ('.$var_sort_order.')';
							}	
							// check the value given for var hidden field
							if(strtolower($var_hidden)!='y' and strtolower($var_hidden)!='n')
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid value given for "Is Hidden" column -- ('.$var_hidden.')';
							}
							if ($typ=='Variable')
							{
								// check the value given for var_value_exists field
								if(strtolower($var_value_exists)!='y' and strtolower($var_value_exists)!='n')
								{                                       
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Invalid value given for "Value Exists" column -- ('.$var_value_exists.')';
								}
								// variable additional price
								if (!is_numeric($var_price))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- '.$typ.' price should be numeric -- ('.$var_price.')';
								}	
							}
							$cur_msg = $error;
							if($error=='') // update the product_variable table
							{
								$var_hide			 = (strtolower($var_hidden)=='y')?1:0;
								if($typ=='Variable')
								{
									$var_value_exists = (strtolower($var_value_exists)=='y')?1:0;
									$update_var = "UPDATE product_variables 
														SET 
															var_name 			='".add_slash($var_name)."',
															var_order  			= ".$var_sort_order.",
															var_hide			= '".$var_hide."',
															var_value_exists	        ='".$var_value_exists."',
															var_price			='".$var_price ."' 
														WHERE 
															products_product_id = '".$product_id."'  
															AND var_id = '".$var_id."'  
														LIMIT 
															1";
									$db->query($update_var);
									if ($var_value_exists==0)
									{
										// Calling function to delete the variable stock for current product (if any exists)
										delete_var_stock($product_id,true);
										// If value exists is 0 then the variable value details should be removed from 
										// product_variable_data and product_shop_variable_data
										// Check whether any values exists for current variable
										$sql_checkval = "SELECT var_value_id 
															FROM 
																product_variable_data 
															WHERE 
																product_variables_var_id='".$var_id."'";
										$ret_checkval = $db->query($sql_checkval);
										if($db->num_rows($ret_checkval))
										{
											while ($row_checkval = $db->fetch_array($ret_checkval))
											{
												$delval_arr[] = $row_checkval['var_value_id'];
											}				
											for($i=0;$i<count($delval_arr);$i++)
											{
												$sql_del = "DELETE FROM 
																product_shop_variable_data  
															WHERE 
																product_variable_data_var_value_id='".$delval_arr[$i]."'";
												$db->query($sql_del);
												$sql_del = "DELETE FROM 
																product_variable_data   
															WHERE 
																var_value_id='".$delval_arr[$i]."'";
												$db->query($sql_del);
											}
										}				
									}
								}
								elseif($typ=='Message')
								{
									if(strtolower($var_value_exists)=='txtarea')
										$var_value_exists = 'TXTAREA';
									else
										$var_value_exists = 'TXTBX';
									$update_var = "UPDATE product_variable_messages 
															SET 
																message_title='".add_slash($var_name)."',
																message_type='".$var_value_exists."',
																message_hide=$var_hide,
																message_order=$var_sort_order 
															WHERE 
																message_id = '".$var_id."'  
																AND products_product_id='".$product_id."'
															LIMIT 
																1";
									$db->query($update_var);	
								}	
								$cur_msg  = '';
								$done_cnt++;
							}
							else
							{	
								$err_cnt++;
								$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$err_cnt."</td>";
								$table .="<td class='".$cls."'>".$product_id."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$var_id."</td>";
								$table .="<td class='".$cls."'>".$var_name."</td>";
								$table .="<td class='".$cls."'>".$var_type."</td>";
								$table .="<td class='".$cls."'>".$cur_msg."</td>";
								$table .="</tr>";
							}	
						}	
						else
						{
							// Check whether the variable exists in current database for current site
							$sql_var_check = "SELECT a.var_id,a.var_name  
												FROM 
													product_variables a,products b
												WHERE 
													a.var_id='".$var_id."'  
													AND b.product_id = '".$product_id."' 
													AND b.sites_site_id = $ecom_siteid 
													AND a.products_product_id=b.product_id 
												LIMIT 
													1";
							$ret_var_check = $db->query($sql_var_check);
							if ($db->num_rows($ret_var_check)==0)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Variable not found in site --';
								$var_type = 'VAR';
							}
							else
							{
								$row_var_check 		= $db->fetch_array($ret_var_check);
								$temp_var_name		= stripslashes($row_var_check['var_name']);
							}
							// case if the current row contains the variable value details
							$varvalue_arr						= explode('-',$var_value_id);
							if ($varvalue_arr[0]!='Vv' or count($varvalue_arr)!=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Variable Value Id Format --('.$var_value_id.')';
							}	
							else
							{
								$var_value_id = addslashes(trim($varvalue_arr[1]));
								// Check whether the variable exists in current database for current site
								$sql_varval_check = "SELECT a.var_value_id 
                                                                                        FROM 
                                                                                                product_variable_data a,product_variables b,products c
                                                                                        WHERE 
                                                                                                a.var_value_id='".$var_value_id ."'
                                                                                                AND b.products_product_id = '".$product_id."' 
                                                                                                AND c.sites_site_id = $ecom_siteid 
                                                                                                AND a.product_variables_var_id	=b.var_id 
                                                                                                AND b.products_product_id=c.product_id 
                                                                                        LIMIT 
                                                                                                1";
								$ret_varval_check = $db->query($sql_varval_check);
								if ($db->num_rows($ret_varval_check)==0)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Variable value not found in site --';
								}
							}
							// check whether variable value specified
							/*if ($var_value=='')
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Variable value not specified --';
							}*/
							// check whether variable value additional price is numeric
							if (!is_numeric($var_value_price))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Variable value additional price should be numeric -- ('.$var_value_price.')';
							}	
							// check whether variable value additional price is numeric
							if (!is_numeric($var_value_sort_order))
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Variable value sort order should be numeric -- ('.$var_value_sort_order.')';
							}	
							$cur_msg = $error;
							if($error=='')
							{
								$var_value = trim($var_value);
								if($var_value!='')
								{
									$update_varvalue = "UPDATE product_variable_data 
                                                                                                SET 
                                                                                                        var_value		='".add_slash($var_value)."',
                                                                                                        var_addprice	= '". $var_value_price."',
                                                                                                        var_order		= $var_value_sort_order 
                                                                                                WHERE 
                                                                                                        var_value_id = '".$var_value_id."'  
                                                                                                LIMIT 
                                                                                                        1";
									$db->query($update_varvalue);
									$cur_msg  = '';
									$done_cnt++;
								}
								else // case if value is left blank. so the respective value should be deleted from the variable.
								{
									// Calling function to delete the variable stock for current product (if any exists)
									delete_var_stock($product_id,true);
									// Deleting the variable value details
									$sql_del = "DELETE FROM 
													product_shop_variable_data  
												WHERE 
													product_variable_data_var_value_id='".$var_value_id."'";
									$db->query($sql_del);
									$sql_del = "DELETE FROM 
													product_variable_data   
												WHERE 
													var_value_id='".$var_value_id."'";
									$db->query($sql_del);
								}	
							}
							else // case if error exists
							{
								$var_name = $temp_var_name;
								$err_cnt++;
								$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$err_cnt."</td>";
								$table .="<td class='".$cls."'>".$product_id."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$var_id."</td>";
								$table .="<td class='".$cls."'>".$var_name."</td>";
								$table .="<td class='".$cls."'>".$var_type."</td>";
								$table .="<td class='".$cls."'>".$cur_msg."</td>";
								$table .="</tr>";
							}
						}
                                                if($error=='')
                                                {
                                                    check_productIntegrity_including_varstock($product_id);
                                                    check_productvariable_consistancy($product_id);
                                                    recalculate_actual_stock($product_id);
                                                }
					}
					$line++;
				}
                                if($done_cnt>0)
                                {
                                    check_promotionalcode_integrity();
                                    check_combo_integrity();
                                }  
				$table .="</table>";
				$top_alert = '<br/><center>Product Variable Details Upload Operation Completed' ;
				$top_alert .='<br/><br/>Total Product variables / messages Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following variables / variable Values not updated due to errors<br/>'.$table;//.$top_alert;
				}
			}
			elseif($select_type=='prod_label') // case of uploading product labels
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	 = $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='15%' class='listingtableheader'>Label  Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Label Value</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
				{
					$num = count($data);
					$cur_msg='';
					$error = '';
					if($line!=0)
					{				
						$product_name			= trim($data[0]);
						$label_caption				= trim($data[1]);
						$label_value				= trim($data[2]);
						$product_id				= trim($data[3]);
						$valuemap_id				= trim($data[4]);

						// ############### Validating the fields #######################
						// Extract the product id
						$product_arr										= explode('-',$product_id);
						if ($product_arr[0] !='P' or count($product_arr) !=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
							if(is_numeric($product_id))
							{
								// Check whether the product exists in current database for current site
								$sql_prod_check = "SELECT product_id,product_name  
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
										$error .= '-- Product not found in site --';
								}
								else
								{
										$row_prod_check = $db->fetch_array($ret_prod_check);
										$product_name		= stripslashes($row_prod_check['product_name']);
								}	
							}
							else
							{
								 if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}
						}
						// Extract the label
						$label_arr			= explode('-',$valuemap_id);
						if ($label_arr[0]!='L' or count($label_arr)!=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Label Map Id Format --('.$valuemap_id.')';
						}	
						else
						{
							$valuemap_id = addslashes(trim($label_arr[1]));
							if(!is_numeric($valuemap_id))
							{
								if ($error!='')
								$error .= '<br/>';
								$error .= '-- Label Map Id not found in site --('.$valuemap_id.')';
							}
							else
								{
								// Check whether the label mapping id is valid or not and if valid get the details of variables
								$sql_label = "SELECT a.id,a.product_site_labels_values_label_value_id,b.label_id,b.label_name,b.is_textbox 
                                                                                    FROM 
                                                                                            product_labels a, product_site_labels b
                                                                                    WHERE 
                                                                                            b.sites_site_id = $ecom_siteid 
                                                                                            AND a.id='".$valuemap_id."'  
                                                                                            AND a.product_site_labels_label_id = b.label_id 
                                                                                    LIMIT 
                                                                                            1";
															
								$ret_label = $db->query($sql_label);
								if ($db->num_rows($ret_label)==0)
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Label Map Id not found in site --('.$valuemap_id.')';
								}
								else
								{
									$row_label 		= $db->fetch_array($ret_label);
									$label_type 	= $row_label['is_textbox']; 
									$label_id			= $row_label['label_id'];
								}
							}	
						}
						$cur_msg = $error;
						if($error=='') // update the product table
						{
							if($label_type==1) // case of textbox
							{
								$update_label = "UPDATE product_labels 
                                                                                    SET 
                                                                                            label_value ='".add_slash($label_value)."' 
                                                                                    WHERE 
                                                                                            id='".$valuemap_id."' 
                                                                                    LIMIT 
                                                                                            1";
								$db->query($update_label);
								$cur_msg  = '';
								$done_cnt++;
							}
							else // case of dropdown
							{
								// Check whether there exists an entry in the product_site_labels_values table for current variable which matches with the currently specified value
								$sql_check = "SELECT label_value_id 
                                                                                FROM 
                                                                                        product_site_labels_values 
                                                                                WHERE 
                                                                                        product_site_labels_label_id ='".$label_id."'  
                                                                                        AND LOWER(label_value)='".add_slash(strtolower($label_value))."' 
                                                                                LIMIT 
                                                                                        1";
								$ret_check = $db->query($sql_check);
								if ($db->num_rows($ret_check))
								{
									$row_check 		= $db->fetch_array($ret_check);
									$label_value_id	= $row_check['label_value_id'];
								}
								else // case if no such value exists. so create that value and update the value id for that value in the product label table
								{
									// Find the max value of order for current values for variables
									$sql_max = "SELECT  max(label_value_order) as maxorder 
                                                                                                FROM 
                                                                                                        product_site_labels_values 
                                                                                                WHERE 
                                                                                                        product_site_labels_label_id='".$label_id."'";
									$ret_max = $db->query($sql_max);
									list($maxorder) = $db->fetch_array($ret_max);
									$insert_array												= array();
									$insert_array['product_site_labels_label_id']		= $label_id;
									$insert_array['label_value']							= $label_value;
									$insert_array['label_value_order']					= ($maxorder+1);
									$db->insert_from_array($insert_array,'product_site_labels_values') ;
									$label_value_id											= $db->insert_id();
								}
								// Updating the label value id field in the product_labels table with the current label value
								$update_label = "UPDATE product_labels 
															SET 
																product_site_labels_values_label_value_id = $label_value_id 
															WHERE 	
																id='".$valuemap_id."'  
															LIMIT 
																1";
								$db->query($update_label);	
								$cur_msg  = '';
								$done_cnt++;			
							}
						}
						if($cur_msg!='')
						{	
							$err_cnt++;
							$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							$table .="<tr>";
							$table .="<td class='".$cls."'>".$err_cnt."</td>";
							$table .="<td class='".$cls."'>".$product_id."</td>";
							$table .="<td class='".$cls."'>".$product_name."</td>";
							$table .="<td class='".$cls."'>".$label_caption."</td>";
							$table .="<td class='".$cls."'>".$label_value."</td>";
							$table .="<td class='".$cls."'>".$cur_msg."</td>";
							$table .="</tr>";
						}	
					}
					$line++;
				}
				$table .="</table>";
				$top_alert = '<br/><center>Product Label Details Upload Operation Completed' ;
				$top_alert .='<br/><br/>Total product labels Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product labels not updated due to errors<br/>'.$table;//.$top_alert;
				}
			}
			elseif($select_type=='prod_fixedall') // case of uploading products with fixed stock, fixed price and direct images only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Stock</td>";
				$table .="<td width='10%' class='listingtableheader'>Price</td>";
				$table .="<td width='10%' class='listingtableheader'>Image ids</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodfixedall_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodfixedall_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
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
						$barcode					= trim($data[1]);
						$stock						= trim($data[2]);
						$price						= trim($data[3]);
						$bulk_values				= trim($data[4]);
						$imageids					= trim($data[5]);
						$product_id					= trim($data[6]);
						$product_arr				= explode('-',$product_id);
						if ($product_arr[0] !='P' or count($product_arr) !=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
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
								// Check whether variable stock is maintained for product. If so it is an error
								$row_prod_check = $db->fetch_array($ret_prod_check);
								if($row_prod_check['product_variablestock_allowed']=='Y')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination stock is activated for this product ';
								}
								if($row_prod_check['product_variablecomboprice_allowed']=='Y')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination Price is activated for this product ';
								}
								if($row_prod_check['product_variablecombocommon_image_allowed']=='Y')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination Image is activated for this product ';
								}
								if(!is_numeric($stock))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Stock value is not numeric';
								}
								if(!is_numeric($price))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Price is not numeric';
								}	
								$blkqty_arr = $blkprice_arr = $bulk_arr = $img_arr = array();
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
										}	
									}
								}
							}
							$cur_msg = $error;
							if($error=='') // case if no errors found
							{
								// Check whether variable price or variable image exists for current product
								if ($row_prod_check['product_variablecomboprice_allowed']=='Y' or $row_prod_check['product_variablecombocommon_image_allowed']=='Y')
									$barcode_str = '';
								else
									$barcode_str = ",product_barcode = '".addslashes($barcode)."' ";
								if(count($blkqty_arr)) // case if bulk discount exists
									$bulk_disc_str = ",product_bulkdiscount_allowed='Y' ";
								else
									$bulk_disc_str = ",product_bulkdiscount_allowed='N' ";	
								//Update the products table with the details
								$update_sql = "UPDATE 
													products 
												SET 
													product_webstock = $stock,
													product_webprice = $price   
													$barcode_str 
													$bulk_disc_str 
												WHERE 
													product_id = '".$product_id."' 
													AND sites_site_id =$ecom_siteid 
												LIMIT 
													1";
								$db->query($update_sql);
								if(count($blkqty_arr))
								{
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
									// delete all direct bulk discounts
									$sel_del = "DELETE FROM product_bulkdiscount 
													WHERE 
														products_product_id='".$product_id."'";
									$db->query($sel_del);
									for($i=0;$i<count($blkqty_arr);$i++)
									{
										$insert_array								= array();
										$insert_array['products_product_id']		= $product_id;
										$insert_array['bulk_qty']					= $blkqty_arr[$i];
										$insert_array['bulk_price']					= $blkprice_arr[$i];
										$db->insert_from_array($insert_array,'product_bulkdiscount');
									}
								}
								else
								{
									// delete all direct bulk discounts
									$sel_del = "DELETE FROM product_bulkdiscount 
													WHERE 
														products_product_id='".$product_id."'";
									$db->query($sel_del);
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
															images_image_id='".$img_arr[$i]."'  
															AND products_product_id=$product_id  
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
													products_product_id = $product_id 
													AND images_image_id NOT IN ($img_str) ";
									$db->query($sql_del);			
								}
								else
								{
									$sql_del = "DELETE FROM 
													images_product 
												WHERE 
													products_product_id = $product_id";
									$db->query($sql_del);
								}
								// Calling function to recalculate the product stock
								recalculate_actual_stock($product_id);
								$cur_msg  = '';
								$done_cnt++;	
							}
							else // case if error occured
							{
								$err_cnt++;
								$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$err_cnt."</td>";
								$table .="<td class='".$cls."'>".$product_id."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$barcode."</td>";
								$table .="<td class='".$cls."'>".$stock."</td>";
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
					$table .="<td class='".$cls."' colspan='6' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Fixed Stock, Fixed Price & Normal Images Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total product Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}			
			}
			elseif($select_type=='prod_fixedstock') // case of uploading products with fixed stock only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Stock</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodfixedstock_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodfixedstock_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
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
						$barcode					= trim($data[1]);
						$stock						= trim($data[2]);
						$product_id					= trim($data[3]);
						$product_arr				= explode('-',$product_id);
						if ($product_arr[0] !='P' or count($product_arr) !=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
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
								// Check whether variable stock is maintained for product. If so it is an error
								$row_prod_check = $db->fetch_array($ret_prod_check);
								if($row_prod_check['product_variablestock_allowed']=='Y')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination stock is activated for this product ';
								}
								if(!is_numeric($stock))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Stock value is not numeric';
								}	
								
							}
							$cur_msg = $error;
							if($error=='') // case if no errors found
							{
								// Check whether variable price or variable image exists for current product
								if ($row_prod_check['product_variablecomboprice_allowed']=='Y' or $row_prod_check['product_variablecombocommon_image_allowed']=='Y')
									$barcode_str = '';
								else
									$barcode_str = ",product_barcode = '".addslashes($barcode)."' ";
								//Update the products table with the details
								$update_sql = "UPDATE 
													products 
												SET 
													product_webstock = $stock 
													$barcode_str 
												WHERE 
													product_id = '".$product_id."' 
													AND sites_site_id =$ecom_siteid 
												LIMIT 
													1";
								$db->query($update_sql);
								// Calling function to recalculate the product stock
								recalculate_actual_stock($product_id);
								$cur_msg  = '';
								$done_cnt++;	
							}
							else // case if error occured
							{
								$err_cnt++;
								$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$table .="<tr>";
								$table .="<td class='".$cls."'>".$err_cnt."</td>";
								$table .="<td class='".$cls."'>".$product_id."</td>";
								$table .="<td class='".$cls."'>".$product_name."</td>";
								$table .="<td class='".$cls."'>".$barcode."</td>";
								$table .="<td class='".$cls."'>".$stock."</td>";
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
					$table .="<td class='".$cls."' colspan='6' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Fixed Stock Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total product Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}			
			}
			elseif($select_type=='prod_fixedprice') // case of uploading products with fixed price only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Web Price</td>";
				$table .="<td width='10%' class='listingtableheader'>Cost Price</td>";
				$table .="<td width='10%' class='listingtableheader'>Discount Values</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodfixedprice_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodfixedprice_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
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
							$barcode					= trim($data[1]);
							$price						= trim($data[2]);
							$costprice					= trim($data[3]);
							$bulk_values				= trim($data[4]);
							$product_id					= trim($data[5]);
							$product_arr				= explode('-',$product_id);
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								$product_id = addslashes(trim($product_arr[1]));
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
									// Check whether variable price is maintained for product. If so it is an error
									$row_prod_check = $db->fetch_array($ret_prod_check);
									if($row_prod_check['product_variablecomboprice_allowed']=='Y')
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Variable price is activated for this product ';
									}
									if(!is_numeric($price))
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Web Price is not numeric';
									}
									if(!is_numeric($costprice))
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Cost Price is not numeric';
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
								$cur_msg = $error;
								if($error=='') // case if no errors found
								{
									// Check whether variable price or variable image exists for current product
									if ($row_prod_check['product_variablecombocommon_image_allowed']=='Y' or $row_prod_check['product_variablestock_allowed']=='Y')
										$barcode_str = '';
									else
										$barcode_str = ",product_barcode = '".addslashes($barcode)."' ";
									if(count($blkqty_arr)) // case if bulk discount exists
										$bulk_disc_str = ",product_bulkdiscount_allowed='Y' ";
									else
										$bulk_disc_str = ",product_bulkdiscount_allowed='N' ";
										
									//Update the products table with the details
									$update_sql = "UPDATE 
														products 
													SET 
														product_webprice = $price,
														product_costprice = $costprice   
														$barcode_str 
														$bulk_disc_str 
													WHERE 
														product_id = '".$product_id."' 
														AND sites_site_id =$ecom_siteid 
													LIMIT 
														1";
									$db->query($update_sql);
									
									if(count($blkqty_arr))
									{
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
										// delete all direct bulk discounts
										$sel_del = "DELETE FROM product_bulkdiscount 
														WHERE 
															products_product_id='".$product_id."'";
										$db->query($sel_del);
										for($i=0;$i<count($blkqty_arr);$i++)
										{
											$insert_array								= array();
											$insert_array['products_product_id']		= $product_id;
											$insert_array['bulk_qty']					= $blkqty_arr[$i];
											$insert_array['bulk_price']					= $blkprice_arr[$i];
											$db->insert_from_array($insert_array,'product_bulkdiscount');
										}
									}
									else
									{
										// delete all direct bulk discounts
										$sel_del = "DELETE FROM product_bulkdiscount 
														WHERE 
															products_product_id='".$product_id."'";
										$db->query($sel_del);
									}
									$cur_msg  = '';
									$done_cnt++;	
								}
								else // case if error occured
								{
									$err_cnt++;
									$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
									$table .="<tr>";
									$table .="<td class='".$cls."'>".$err_cnt."</td>";
									$table .="<td class='".$cls."'>".$product_id."</td>";
									$table .="<td class='".$cls."'>".$product_name."</td>";
									$table .="<td class='".$cls."'>".$barcode."</td>";
									$table .="<td class='".$cls."'>".$price."</td>";
									$table .="<td class='".$cls."'>".$costprice."</td>";
									$table .="<td class='".$cls."'>".$bulk_values."</td>";
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
					$table .="<td class='".$cls."' colspan='8' align='center'>".$error."</td>";
					$table .="</tr>";
				}	
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Fixed Price Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total product Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}			
			}
			elseif($select_type=='prod_normalimage') // case of uploading products with normal images only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Image Ids</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodnormalimage_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodnormalimage_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
				if($error=='')
				{
					while (($data = fgetcsv($fp,$fs1, ",")) !== FALSE) 
					{
						$num 		= count($data);
						$img_arr	= array();
						$cur_msg	= '';
						$error 		= '';
						if($line!=0)
						{		
							$product_name				= trim($data[0]);
							$barcode					= trim($data[1]);
							$imageids					= trim($data[2]);
							$product_id					= trim($data[3]);
							$product_arr				= explode('-',$product_id);
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								$product_id = addslashes(trim($product_arr[1]));
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
									// Check whether variable stock is maintained for product. If so it is an error
									$row_prod_check = $db->fetch_array($ret_prod_check);
									if($row_prod_check['product_variablecombocommon_image_allowed']=='Y')
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Combination Image is activated for this product ';
									}
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
											}	
										}
									}	
								}
								$cur_msg = $error;
								if($error=='') // case if no errors found
								{
									// Check whether variable price or variable image exists for current product
									if ($row_prod_check['product_variablecomboprice_allowed']=='Y' or $row_prod_check['product_variablestock_allowed']=='Y')
									{
										//Update the products table with the details
										$update_sql = "UPDATE 
															products 
														SET 
															product_barcode = '".addslashes($barcode)."' 
														WHERE 
		
															product_id = '".$product_id."' 
															AND sites_site_id =$ecom_siteid 
														LIMIT 
															1";
										$db->query($update_sql);
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
																images_image_id='".$img_arr[$i]."'  
																AND products_product_id=$product_id  
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
														products_product_id = $product_id 
														AND images_image_id NOT IN ($img_str) ";
										$db->query($sql_del);			
									}
									else
									{
										$sql_del = "DELETE FROM 
														images_product 
													WHERE 
														products_product_id = $product_id ";
										$db->query($sql_del);
									}				
									$cur_msg  = '';
									$done_cnt++;	
								}
								else // case if error occured
								{
									$err_cnt++;
									$cls = ($err_cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
									$table .="<tr>";
									$table .="<td class='".$cls."'>".$err_cnt."</td>";
									$table .="<td class='".$cls."'>".$product_id."</td>";
									$table .="<td class='".$cls."'>".$product_name."</td>";
									$table .="<td class='".$cls."'>".$barcode."</td>";
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
					$table .="<td class='".$cls."' colspan='6' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Normal Image Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total product Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}			
			}
			elseif($select_type=='prod_combstock') // case of uploading products with combination stock only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Stock</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodcombstock_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodcombstock_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
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
						$stock						= trim($data[3]);
						$product_id					= trim($data[4]);
						$comb_id					= trim($data[5]);
						$product_arr				= explode('-',$product_id);
						if ($product_arr[0] !='P' or count($product_arr) !=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
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
								if($row_prod_check['product_variablestock_allowed']=='N')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination stock is not activated for this product ';
								}
								if(!is_numeric($stock))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Stock value is not numeric';
								}	
								
							}
							$cur_msg = $error;
							if($error=='') // case if no errors found
							{
								//Update the product_variable_combination_stock table with the details
								$update_sql = "UPDATE 
													product_variable_combination_stock  
												SET 
													web_stock=$stock,
													comb_barcode = '".addslashes($barcode)."' 
												WHERE 
													comb_id = '".$comb_id."'   
													AND products_product_id = '".$product_id."' 
												LIMIT 
													1";
								$db->query($update_sql);
								// Calling function to recalculate the product stock
								recalculate_actual_stock($product_id);
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
								$table .="<td class='".$cls."'>".$stock."</td>";
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
					$table .="<td class='".$cls."' colspan='7' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Combination Stock Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total Combinations Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}
			}
			elseif($select_type=='prod_combprice') // case of uploading products with combination price only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Web Price</td>";
				$table .="<td width='10%' class='listingtableheader'>Discount Values</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodcombprice_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodcombprice_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
				if($error=='')
				{
					$change_prod_arr = array();
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
							$price						= trim($data[3]);
							$bulk_values				= trim($data[4]);
							$product_id					= trim($data[5]);
							$comb_id					= trim($data[6]);
							$product_arr				= explode('-',$product_id);
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								$product_id = addslashes(trim($product_arr[1]));
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
										$comb_id = addslashes(trim($comb_arr[1]));	
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
										
									// Check whether variable price is maintained for product. If so it is an error
									$row_prod_check = $db->fetch_array($ret_prod_check);
									if($row_prod_check['product_variablecomboprice_allowed']=='N')
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Variable price is not activated for this product ';
									}
									if(!is_numeric($price))
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Price is not numeric';
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
								$cur_msg = $error;
								if($error=='') // case if no errors found
								{
									//Update the product_variable_combination_stock table with the details
									$update_sql = "UPDATE 
														product_variable_combination_stock  
													SET 
														comb_price = $price,  
														comb_barcode ='".addslashes($barcode)."' 
													WHERE 
														products_product_id = '".$product_id."' 
														AND comb_id =$comb_id  
													LIMIT 
														1";
									$db->query($update_sql);
									
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
											$insert_array								= array();
											$insert_array['products_product_id']		= $product_id;
											$insert_array['comb_id']					= $comb_id;
											$insert_array['bulk_qty']					= $blkqty_arr[$i];
											$insert_array['bulk_price']					= $blkprice_arr[$i];
											$db->insert_from_array($insert_array,'product_bulkdiscount');
										}
									}
									else
										$bulk_exists = false;
										
									if(!in_array($product_id,$change_prod_arr))
									{
										$change_prod_arr[] = $product_id;
									}
									/*$bulk_cap = ($bulk_exists)?'Y':'N';
									//Updating the products table to set the value of allowed bulk discount 
									$update_sql = "UPDATE 
														products 
													SET 
														product_bulkdiscount_allowed = '".$bulk_cap."' 
													WHERE 
														product_id = $product_id 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
									$db->query($update_sql);*/
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
									$table .="<td class='".$cls."'>".$price."</td>";
									$table .="<td class='".$cls."'>".$bulk_values."</td>";
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
					$table .="<td class='".$cls."' colspan='8' align='center'>".$error."</td>";
					$table .="</tr>";
				}	
				$table .="</table>";
				
				if(count($change_prod_arr))
				{
					for($i=0;$i<count($change_prod_arr);$i++)
					{
						if($change_prod_arr[$i])
						{
							// Check whether bulk discount is to be activated for current product
							$sql_bulkcheck = "SELECT bulk_id 
												FROM 
													product_bulkdiscount 
												WHERE 
													products_product_id ='".$change_prod_arr[$i]."' 
													AND comb_id <> 0";
							$ret_bulkcheck = $db->query($sql_bulkcheck);
							if($db->num_rows($ret_bulkcheck))
							{
								$update_prod = "UPDATE products 
													SET product_bulkdiscount_allowed = 'Y' 
												WHERE 
													product_id = '".$change_prod_arr[$i]."' 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
								$db->query($update_prod);
							}	
							// Calling function to variable price of first combination as the product webprice
							handle_default_comp_price_and_id($change_prod_arr[$i]);
						}	
					}	
				}	
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Combination Price Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total Combinations Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}	
			}
			elseif($select_type=='prod_combimage') // case of uploading products with combination images only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Image Ids</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodcombimage_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodcombimage_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
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
							$imageids					= trim($data[3]);
							$product_id					= trim($data[4]);
							$comb_id					= trim($data[5]);
							$product_arr				= explode('-',$product_id);
							if ($product_arr[0] !='P' or count($product_arr) !=2)
							{
								if ($error!='')
									$error .= '<br/>';
								$error .= '-- Invalid Product Id Format --('.$product_id.')';
							}	
							else
							{
								$product_id = addslashes(trim($product_arr[1]));
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
									$comb_arr		= explode('-',$comb_id);
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
									if($row_prod_check['product_variablecombocommon_image_allowed']=='N')
									{
										if ($error!='')
											$error .= '<br/>';
										$error .= '-- Combination Image is not activated for this product ';
									}
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
											}	
										}
									}	
								}
								$cur_msg = $error;
								if($error=='') // case if no errors found
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
					$table .="<td class='".$cls."' colspan='7' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Normal Image Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total Combinations Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}
			}
			elseif($select_type=='prod_comball') // case of uploading products with combination stock, price and images only
			{
				$fp		= fopen($_FILES['upload_file']['tmp_name'],'r');
				$fs1 	= filesize($_FILES["upload_file"]["tmp_name"]);
				$line	= $done_cnt = $err_cnt =  0;
				$css	= "<link href='css/style.css'  rel='stylesheet' type='text/css'/>";
				$table 	= "
				<table width='100%' cellpadding='1' cellspacing='1' border='0'>";
				$table .="<tr>";
				$table .="<td width='5%' class='listingtableheader'>#</td>";
				$table .="<td width='10%' class='listingtableheader'>Product Id</td>";
				$table .="<td width='15%' class='listingtableheader'>Product Name</td>";
				$table .="<td width='15%' class='listingtableheader'>Variable Combination</td>";
				$table .="<td width='10%' class='listingtableheader'>Barcode</td>";
				$table .="<td width='10%' class='listingtableheader'>Stock</td>";
				$table .="<td width='10%' class='listingtableheader'>Price</td>";
				$table .="<td width='10%' class='listingtableheader'>Bulk Discount</td>";
				$table .="<td width='10%' class='listingtableheader'>Images</td>";
				$table .="<td width='40%' class='listingtableheader'>Error Message</td>";
				$table .="</tr>";
				// Get the first row
				$data = fgetcsv($fp,$fs1, ",");
				if(count($data))
				{
					if(count($data)!=count($var_prodcomball_arr))
					{
						$error = '-- Error in File header --';
						$err_cnt = 1;
					}
					else
					{
						$i = 0;
						foreach ($var_prodcomball_arr as $k=>$v)
						{
							if($data[$i]!=$v)
							{
								$error = '-- Error in File header --';
								$err_cnt = 1;
								break;
							}	
							$i++;
						}
					}	
				}
				$line++;
				if($error=='')
				{
					$change_prod_arr = array();
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
						$stock						= trim($data[3]);
						$price						= trim($data[4]);
						$bulk_values				= trim($data[5]);
						$imageids					= trim($data[6]);
						$product_id					= trim($data[7]);
						$comb_id					= trim($data[8]);
						$product_arr				= explode('-',$product_id);
						if ($product_arr[0] !='P' or count($product_arr) !=2)
						{
							if ($error!='')
								$error .= '<br/>';
							$error .= '-- Invalid Product Id Format --('.$product_id.')';
						}	
						else
						{
							$product_id = addslashes(trim($product_arr[1]));
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
								if($row_prod_check['product_variablestock_allowed']=='N')
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Combination stock is not activated for this product ';
								}
								if(!is_numeric($stock))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Stock value is not numeric';
								}	
								if(!is_numeric($price))
								{
									if ($error!='')
										$error .= '<br/>';
									$error .= '-- Price is not numeric';
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
										}	
									}
								}
							}
							$cur_msg = $error;
							if($error=='') // case if no errors found
							{
								//Update the product_variable_combination_stock table with the details
								$update_sql = "UPDATE 
													product_variable_combination_stock  
												SET 
													web_stock=$stock,
													comb_price=$price,
													comb_barcode = '".addslashes($barcode)."' 
												WHERE 
													comb_id = '".$comb_id."'   
													AND products_product_id = '".$product_id."' 
												LIMIT 
													1";
								$db->query($update_sql);
								if(!in_array($product_id,$change_prod_arr))
								{
									$change_prod_arr[] = $product_id;
								}
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
										$insert_array								= array();
										$insert_array['products_product_id']		= $product_id;
										$insert_array['comb_id']					= $comb_id;
										$insert_array['bulk_qty']					= $blkqty_arr[$i];
										$insert_array['bulk_price']					= $blkprice_arr[$i];
										$db->insert_from_array($insert_array,'product_bulkdiscount');
									}
								}
								else
									$bulk_exists = false;
									
								if(!in_array($product_id,$change_prod_arr))
								{
									$change_prod_arr[] = $product_id;
								}
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
								
								
								// Calling function to recalculate the product stock
								recalculate_actual_stock($product_id);
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
								$table .="<td class='".$cls."'>".$stock."</td>";
								$table .="<td class='".$cls."'>".$price."</td>";
								$table .="<td class='".$cls."'>".$bulk_values."</td>";
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
					$table .="<td class='".$cls."' colspan='10' align='center'>".$error."</td>";
					$table .="</tr>";
				}
				$table .="</table>";
				
				if(count($change_prod_arr))
				{
					for($i=0;$i<count($change_prod_arr);$i++)
					{
						if($change_prod_arr[$i])
						{
							// Check whether bulk discount is to be activated for current product
							$sql_bulkcheck = "SELECT bulk_id 
												FROM 
													product_bulkdiscount 
												WHERE 
													products_product_id ='".$change_prod_arr[$i]."' 
													AND comb_id <> 0";
							$ret_bulkcheck = $db->query($sql_bulkcheck);
							if($db->num_rows($ret_bulkcheck))
							{
								$update_prod = "UPDATE products 
													SET product_bulkdiscount_allowed = 'Y' 
												WHERE 
													product_id = '".$change_prod_arr[$i]."' 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
								$db->query($update_prod);
							}	
							// Calling function to variable price of first combination as the product webprice
							handle_default_comp_price_and_id($change_prod_arr[$i]);
						}	
					}	
				}
				
				if ($done_cnt==0)
					$msg = 'Sorry!! no products updated';
				else
					$msg = 'Product Combination Stock, Price and Image Update Operation Completed';
				$top_alert = '<br/><center>'.$msg ;
				$top_alert .='<br/><br/>Total Combinations Updated: '.$done_cnt.'</center>';
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()">Click here to go back to Database Offline section</a></center>';
				if ($err_cnt>0)
				{
					$alert .='<br/><br/>Following product not updated due to errors<br/>'.$table;
				}
			}
			echo $css;
			$alert = '<br><span class="redtext"><b>'.$top_alert.$alert.'</b></span><br>';
			if ($err_cnt>0)
			{
				$alert .='<br /><br /><center><a class="smalllink" href="home.php?request=database_offline" onclick="show_processing()"><strong>Click here to go back to Database Offline section</strong></a></center>';
			}	
			echo $alert;
		}
	}
?>