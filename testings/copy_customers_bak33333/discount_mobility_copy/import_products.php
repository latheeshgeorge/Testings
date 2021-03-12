<?php
	set_time_limit(0);
	include_once('header.php');
	
	$category_map 		= 'map/category_map.csv';
	$labelgroups_map 	= 'map/labelgroup_map.csv';
	$label_map 			= 'map/label_map.csv';
	$labelvalue_map 	= 'map/labelval_map.csv';
	$vendor_map		 	= 'map/vendor_map.csv';
	$product_map		= 'map/product_map.csv';
	
	$var_map			= 'map/product_variable_map.csv';
	$varval_map			= 'map/product_variablevalue_map.csv';
	
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
	$fp_prodmap = fopen($product_map,'w');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	$fp_varmap = fopen($var_map,'w');
	if (!$fp_varmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$fp_varvalmap = fopen($varval_map,'w');
	if (!$fp_varvalmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	
	$fp_catmap = fopen($category_map,'r');
	if (!$fp_catmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$cat_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_catmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$cat_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_catmap);
	
	
	$fp_labelgroupmap = fopen($labelgroups_map,'r');
	if (!$fp_labelgroupmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$labelgroup_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_labelgroupmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$labelgroup_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_labelgroupmap);
	
	
	$fp_labelmap = fopen($label_map,'r');
	if (!$fp_labelmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$label_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_labelmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$label_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_labelmap);
	
	
	$fp_labelvaluemap = fopen($labelvalue_map,'r');
	if (!$fp_labelvaluemap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$labelvalue_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_labelvaluemap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$labelvalue_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_labelvaluemap);
	
	
	
	$fp_vendormap = fopen($vendor_map,'r');
	if (!$fp_vendormap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$vendor_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_vendormap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$vendor_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_vendormap);
		

	// Get the list of all products from source site
	$sql_prod = "SELECT * FROM products WHERE sites_site_id = $src_siteid";
	$ret_prod = $db->query($sql_prod);
	
	fwrite($fp_prodmap,'Old productid Id,New product Id'."\r\n"); // writing the header row
	fwrite($fp_varmap,'Old varid Id,New var Id'."\r\n"); // writing the header row
	fwrite($fp_varvalmap,'Old varvalid Id,New varval Id'."\r\n"); // writing the header row
	
	
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$err_msg 									= '';
			
			$insert_array 											= array();
			$insert_array['sites_site_id'] 							= $dest_siteid;
			$insert_array['product_adddate'] 						= 'now()';
			$insert_array['product_barcode'] 						= addslashes(stripslashes($row_prod['product_barcode']));
			$insert_array['manufacture_id'] 						= addslashes(stripslashes($row_prod['manufacture_id']));
			$insert_array['product_name'] 							= addslashes(stripslashes($row_prod['product_name']));
			$insert_array['product_model'] 							= addslashes(stripslashes($row_prod['product_model']));
			$insert_array['product_shortdesc'] 						= addslashes(stripslashes($row_prod['product_shortdesc']));
			$insert_array['product_longdesc'] 						= addslashes(stripslashes($row_prod['product_longdesc']));
			$insert_array['product_hide'] 							= addslashes(stripslashes($row_prod['product_hide']));
			$insert_array['product_webstock'] 						= addslashes(stripslashes($row_prod['product_webstock']));
			$insert_array['product_actualstock'] 					= addslashes(stripslashes($row_prod['product_actualstock']));
			$insert_array['product_costprice'] 						= addslashes(stripslashes($row_prod['product_costprice']));
			$insert_array['product_webprice'] 						= addslashes(stripslashes($row_prod['product_webprice']));
			$insert_array['product_weight'] 						= addslashes(stripslashes($row_prod['product_weight']));
			$insert_array['productdetail_moreimages_showimagetype'] = addslashes(stripslashes($row_prod['productdetail_moreimages_showimagetype']));
			$insert_array['product_reorderqty'] 					= addslashes(stripslashes($row_prod['product_reorderqty']));
			$insert_array['product_extrashippingcost'] 				= addslashes(stripslashes($row_prod['product_extrashippingcost']));
			$insert_array['product_bonuspoints'] 					= addslashes(stripslashes($row_prod['product_bonuspoints']));
			$insert_array['product_discount'] 						= addslashes(stripslashes($row_prod['product_discount']));
			$insert_array['product_discount_enteredasval'] 			= addslashes(stripslashes($row_prod['product_discount_enteredasval']));
			$insert_array['product_bulkdiscount_allowed'] 			= addslashes(stripslashes($row_prod['product_bulkdiscount_allowed']));
			$insert_array['product_applytax'] 						= addslashes(stripslashes($row_prod['product_applytax']));
			$insert_array['product_variablestock_allowed'] 			= 'N';
			$insert_array['product_preorder_allowed'] 				= addslashes(stripslashes($row_prod['product_preorder_allowed']));
			$insert_array['product_preorder_custom_order'] 			= addslashes(stripslashes($row_prod['product_preorder_custom_order']));
			$insert_array['product_instock_date'] 					= addslashes(stripslashes($row_prod['product_instock_date']));
			$insert_array['product_deposit'] 						= addslashes(stripslashes($row_prod['product_deposit']));
			$insert_array['product_deposit_message'] 				= addslashes(stripslashes($row_prod['product_deposit_message']));
			$insert_array['product_show_cartlink'] 					= addslashes(stripslashes($row_prod['product_show_cartlink']));
			$insert_array['product_show_enquirelink'] 				= addslashes(stripslashes($row_prod['product_show_enquirelink']));
			$insert_array['product_default_category_id'] 			= $cat_arr[$row_prod['product_default_category_id']];
			$insert_array['product_code'] 							= addslashes(stripslashes($row_prod['product_code']));
			$insert_array['product_variable_display_type'] 			= addslashes(stripslashes($row_prod['product_variable_display_type']));
			$insert_array['product_variable_in_newrow'] 			= addslashes(stripslashes($row_prod['product_variable_in_newrow']));
			$insert_array['product_downloadable_allowed'] 			= addslashes(stripslashes($row_prod['product_downloadable_allowed']));
			$insert_array['product_stock_notification_required'] 	= addslashes(stripslashes($row_prod['product_stock_notification_required']));
			$insert_array['product_hide_on_nostock'] 				= addslashes(stripslashes($row_prod['product_hide_on_nostock']));
			$insert_array['product_details_image_type'] 			= addslashes(stripslashes($row_prod['product_details_image_type']));
			$insert_array['product_alloworder_notinstock'] 			= addslashes(stripslashes($row_prod['product_alloworder_notinstock']));
			$insert_array['product_keywords'] 						= addslashes(stripslashes($row_prod['product_keywords']));
			$insert_array['product_freedelivery'] 					= addslashes(stripslashes($row_prod['product_freedelivery']));
			$insert_array['product_variablecomboprice_allowed'] 		= 'N';
			$insert_array['product_variablecombocommon_image_allowed'] 	= 'N';
			$insert_array['product_det_qty_type'] 					= addslashes(stripslashes($row_prod['product_det_qty_type']));
			$insert_array['product_det_qty_caption'] 				= addslashes(stripslashes($row_prod['product_det_qty_caption']));
			$insert_array['product_det_qty_drop_values'] 			= addslashes(stripslashes($row_prod['product_det_qty_drop_values']));
			$insert_array['product_det_qty_drop_prefix'] 			= addslashes(stripslashes($row_prod['product_det_qty_drop_prefix']));
			$insert_array['product_det_qty_drop_suffix'] 			= addslashes(stripslashes($row_prod['product_det_qty_drop_suffix']));
			$insert_array['price_normalprefix'] 					= addslashes(stripslashes($row_prod['price_normalprefix']));
			$insert_array['price_normalsuffix'] 					= addslashes(stripslashes($row_prod['price_normalsuffix']));
			$insert_array['price_fromprefix'] 						= addslashes(stripslashes($row_prod['price_fromprefix']));
			$insert_array['price_fromsuffix'] 						= addslashes(stripslashes($row_prod['price_fromsuffix']));
			$insert_array['price_specialofferprefix'] 				= addslashes(stripslashes($row_prod['price_specialofferprefix']));
			$insert_array['price_specialoffersuffix'] 				= addslashes(stripslashes($row_prod['price_specialoffersuffix']));
			$insert_array['price_discountprefix'] 					= addslashes(stripslashes($row_prod['price_discountprefix']));
			$insert_array['price_discountsuffix'] 					= addslashes(stripslashes($row_prod['price_discountsuffix']));
			$insert_array['price_yousaveprefix'] 					= addslashes(stripslashes($row_prod['price_yousaveprefix']));
			$insert_array['price_yousavesuffix'] 					= addslashes(stripslashes($row_prod['price_yousavesuffix']));
			$insert_array['price_noprice'] 							= addslashes(stripslashes($row_prod['price_noprice']));
			$insert_array['product_show_pricepromise'] 				= addslashes(stripslashes($row_prod['product_show_pricepromise']));
			$insert_array['product_saleicon_show'] 					= addslashes(stripslashes($row_prod['product_saleicon_show']));
			$insert_array['product_saleicon_text'] 					= addslashes(stripslashes($row_prod['product_saleicon_text']));
			$insert_array['product_newicon_show'] 					= addslashes(stripslashes($row_prod['product_newicon_show']));
			$insert_array['product_newicon_text'] 					= addslashes(stripslashes($row_prod['product_newicon_text']));
			$insert_array['product_total_preorder_allowed'] 		= addslashes(stripslashes($row_prod['product_total_preorder_allowed']));
			$insert_array['product_order_outstock_instock_date'] 	= addslashes(stripslashes($row_prod['product_order_outstock_instock_date']));
			$insert_array['product_exclude_from_feed'] 				= addslashes(stripslashes($row_prod['product_exclude_from_feed']));
			$insert_array['product_variableweight_allowed'] 		= 'N';
			
			
			$insert_array['product_variablestock_allowed'] 				= addslashes(stripslashes($row_prod['product_variablestock_allowed']));
			$insert_array['product_variablecomboprice_allowed'] 		= addslashes(stripslashes($row_prod['product_variablecomboprice_allowed']));
			$insert_array['product_variablecombocommon_image_allowed'] 	= addslashes(stripslashes($row_prod['product_variablecombocommon_image_allowed']));
			
			




	
			$db->insert_from_array($insert_array,'products');
			$product_id 	= $db->insert_id();
			$productid	 	= $row_prod['product_id'];
			
			fwrite($fp_prodmap,"$productid,$product_id"."\r\n"); // writing the header row
			
			
			
			// handle the case of categories to which the products to be mapped
			$sql_cats = "SELECT * FROM product_category_map WHERE products_product_id = $productid";
			$ret_cats = $db->query($sql_cats);
			if($db->num_rows($ret_cats))
			{
				while ($row_cats = $db->fetch_array($ret_cats))
				{
					$curcatid = $cat_arr[$row_cats['product_categories_category_id']];
					$insert_array 											= array();
					$insert_array['products_product_id'] 					= $product_id;
					$insert_array['product_categories_category_id'] 		= $curcatid;
					$db->insert_from_array($insert_array,'product_category_map');
				}
			}
			
			
			// handle the case of mapping products to vendors
			$sql_vendors = "SELECT * FROM product_vendor_map WHERE products_product_id = $productid";
			$ret_vendors = $db->query($sql_vendors);
			if($db->num_rows($ret_vendors))
			{
				while ($row_vendors = $db->fetch_array($ret_vendors))
				{
					$curvendorid = $vendor_arr[$row_vendors['product_vendors_vendor_id']];
					$insert_array 											= array();
					$insert_array['product_vendors_vendor_id'] 				= $curvendorid;
					$insert_array['products_product_id'] 					= $product_id;
					$insert_array['sites_site_id'] 							= $dest_siteid;
					$db->insert_from_array($insert_array,'product_vendor_map');
				}
			}
			
			// handle the case of mapping bulkdiscounts
			$sql_bulk = "SELECT * FROM product_bulkdiscount WHERE products_product_id = $productid and comb_id = 0";
			$ret_bulk = $db->query($sql_bulk);
			if($db->num_rows($ret_bulk))
			{
				while ($row_bulk = $db->fetch_array($ret_bulk))
				{
					$insert_array 								= array();
					$insert_array['products_product_id'] 		= $product_id;
					$insert_array['bulk_qty'] 					= addslashes(stripslashes($row_bulk['bulk_qty']));
					$insert_array['bulk_price'] 				= addslashes(stripslashes($row_bulk['bulk_price']));
					$db->insert_from_array($insert_array,'product_bulkdiscount');
				}
			}
			
			$var_exists = $var_price_exists = false;
			// get the list of variables linked with this product
			$sql_var = "SELECT * FROM product_variables WHERE products_product_id = $productid";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var))
			{
				$var_exists = true;
				while ($row_var = $db->fetch_array($ret_var))
				{
					$insert_array 								= array();
					$insert_array['products_product_id'] 		= $product_id;
					$insert_array['var_name'] 					= addslashes(stripslashes($row_var['var_name']));
					$insert_array['var_order'] 					= addslashes(stripslashes($row_var['var_order']));
					$insert_array['var_hide'] 					= addslashes(stripslashes($row_var['var_hide']));
					$insert_array['var_value_exists'] 			= addslashes(stripslashes($row_var['var_value_exists']));
					$insert_array['var_price'] 					= addslashes(stripslashes($row_var['var_price']));
					$insert_array['var_value_display_dropdown'] = addslashes(stripslashes($row_var['var_value_display_dropdown']));
					$db->insert_from_array($insert_array,'product_variables');
					$var_id = $db->insert_id();
					$varid = $row_var['var_id'];
					
					fwrite($fp_varmap,"$varid,$var_id"."\r\n"); // writing the header row
					
					if($row_var['var_price']>0 and $row_var['var_value_exists']==0)
						$var_price_exists = true;
					
					if($row_var['var_value_exists'] == 1)
					{
						// Get the variable values for this variable
						$sql_varval = "SELECT * FROM product_variable_data WHERE product_variables_var_id = $varid";
						$ret_varval = $db->query($sql_varval);
						if($db->num_rows($ret_varval))
						{
							while ($row_varval = $db->fetch_array($ret_varval))
							{
								$insert_array 								= array();
								$insert_array['product_variables_var_id'] 	= $var_id;
								$insert_array['var_value'] 					= addslashes(stripslashes($row_varval['var_value']));
								$insert_array['var_addprice'] 				= addslashes(stripslashes($row_varval['var_addprice']));
								$insert_array['var_order'] 					= addslashes(stripslashes($row_varval['var_order']));
								$insert_array['var_code'] 					= addslashes(stripslashes($row_varval['var_code']));
								$insert_array['var_colorcode'] 				= addslashes(stripslashes($row_varval['var_colorcode']));
								$insert_array['var_mpn'] 					= addslashes(stripslashes($row_varval['var_mpn']));
								$db->insert_from_array($insert_array,'product_variable_data');
								$varval_id = $db->insert_id();
								$varvalid = $row_varval['var_value_id'];
								if($row_varval['var_addprice']>0)
									$var_price_exists = true;
								
								fwrite($fp_varvalmap,"$varvalid,$varval_id"."\r\n"); // writing the header row	

							}
						}
						
					}
					
				}
				$var_exists_check = ($var_exists==true)?'Y':'N';
				$var_addonprice_check = ($var_price_exists==true)?'Y':'N';
				$sql_update = "UPDATE products SET product_variables_exists = '".$var_exists_check."',product_variablesaddonprice_exists ='".$var_addonprice_check."' WHERE product_id = $product_id LIMIT 1";
				$db->query($sql_update);
				
			}	
			
			// Mapping the labels and its respective values for the product
			$sql_labels = "SELECT * FROM product_labels WHERE products_product_id = $productid";
			$ret_labels = $db->query($sql_labels);
			if($db->num_rows($ret_labels))
			{
				while ($row_labels = $db->fetch_array($ret_labels))
				{
					$curlabelid = $label_arr[$row_labels['product_site_labels_label_id']];
					if($row_labels['product_site_labels_values_label_value_id']>0)
						$curlabelvalueid = $labelvalue_arr[$row_labels['product_site_labels_values_label_value_id']];
					else
						$curlabelvalueid = 0;
					
					$insert_array 												= array();
					$insert_array['products_product_id'] 						= $product_id;
					$insert_array['product_site_labels_label_id'] 				= $curlabelid;
					$insert_array['product_site_labels_values_label_value_id'] 	= $curlabelvalueid;
					$insert_array['label_value'] 								= addslashes(stripslashes($row_labels['label_value']));
					$insert_array['is_textbox'] 								= addslashes(stripslashes($row_labels['is_textbox']));
					$db->insert_from_array($insert_array,'product_labels');
				}				
			}
			
			
			// handle the case of product tabs
			$sql_tab = "SELECT * FROM product_tabs WHERE products_product_id = $productid";
			$ret_tab = $db->query($sql_tab);
			if($db->num_rows($ret_tab))
			{
				while ($row_tab = $db->fetch_array($ret_tab))
				{
					$insert_array 								= array();
					$insert_array['products_product_id'] 		= $product_id;
					$insert_array['tab_title'] 					= addslashes(stripslashes($row_tab['tab_title']));
					$insert_array['tab_content'] 				= addslashes(stripslashes($row_tab['tab_content']));
					$insert_array['tab_order'] 					= addslashes(stripslashes($row_tab['tab_order']));
					$insert_array['tab_hide'] 					= addslashes(stripslashes($row_tab['tab_hide']));
					$db->insert_from_array($insert_array,'product_tabs');
				}
			}
			
			
			
			
			
		}
	}	
	fclose($varcomb_map);
	fclose($fp_varmap);
	fclose($fp_varvalmap);
	fclose($fp_prodmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Products Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
