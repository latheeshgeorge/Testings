<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	
	$var_map			= 'map/product_variable_map.csv';
	$varval_map			= 'map/product_variablevalue_map.csv';
	
	$varcomb_map		= 'map/product_variablecomb_map.csv';
	
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
	$fp_prodmap = fopen($product_map,'r');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	$fp_varmap = fopen($var_map,'r');
	if (!$fp_varmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$fp_varvalmap = fopen($varval_map,'r');
	if (!$fp_varvalmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$fp_varcombmap = fopen($varcomb_map,'w');
	if (!$fp_varcombmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	
	$product_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$product_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodmap);
	
	
	$var_arr = array();
	while (($data = fgetcsv($fp_varmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$var_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_varmap);
	
	
	$varvalue_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_varvalmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$varvalue_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_varvalmap);
	
		
	fwrite($fp_varcombmap,'Old comb Id,New comb Id,Image id'."\r\n"); // writing the header row
	// Get the list of all products from source site
	$sql_prod = "SELECT * FROM products WHERE sites_site_id = $src_siteid";
	$ret_prod = $db->query($sql_prod);
	
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$err_msg 									= '';
			
			
			$productid	 	= $row_prod['product_id'];
			$product_id		= $product_arr[$productid];
			// Consider the case of variable combinations
			$sql_varcomb = "SELECT * FROM product_variable_combination_stock WHERE products_product_id = $productid";
			$ret_varcomb = $db->query($sql_varcomb);
			if ($db->num_rows($ret_varcomb))
			{
				while ($row_varcomb = $db->fetch_array($ret_varcomb))
				{
					$insert_array 								= array();
					$insert_array['products_product_id'] 		= $product_id;
					$insert_array['web_stock'] 					= addslashes(stripslashes($row_varcomb['web_stock']));
					$insert_array['actual_stock'] 				= addslashes(stripslashes($row_varcomb['actual_stock']));
					$insert_array['comb_barcode'] 				= addslashes(stripslashes($row_varcomb['comb_barcode']));
					$insert_array['comb_special_product_code'] 	= addslashes(stripslashes($row_varcomb['comb_special_product_code']));
					$insert_array['comb_price'] 				= addslashes(stripslashes($row_varcomb['comb_price']));
					$db->insert_from_array($insert_array,'product_variable_combination_stock');
					$comb_id = $db->insert_id();
					
					$combid = $row_varcomb['comb_id'];
					$imageid = $row_varcomb['comb_img_assigned'];
					
					fwrite($fp_varcombmap,"$combid,$comb_id,$imageid"."\r\n"); // writing the header row
					
					// Get variable combination details
					$sql_varcombdet = "SELECT * FROM product_variable_combination_stock_details WHERE comb_id = $combid and products_product_id = $productid";
					$ret_varcombdet = $db->query($sql_varcombdet);
					if($db->num_rows($ret_varcombdet))
					{
						while ($row_varcombdet = $db->fetch_array($ret_varcombdet))
						{
							$var_id 											= $var_arr[$row_varcombdet['product_variables_var_id']];
							$varvalue_id 										= $varvalue_arr[$row_varcombdet['product_variable_data_var_value_id']];
							$insert_array 										= array();
							$insert_array['comb_id'] 							= $comb_id;
							$insert_array['product_variables_var_id'] 			= $var_id ;
							$insert_array['product_variable_data_var_value_id'] = $varvalue_id;
							$insert_array['products_product_id'] 				= $product_id;
							$db->insert_from_array($insert_array,'product_variable_combination_stock_details');	
						}
					}
					
					// Handle the case of combination bulk discounts
					$sql_bulk = "SELECT * FROM product_bulkdiscount WHERE comb_id = $combid AND products_product_id = $productid";
					$ret_bulk = $db->query($sql_bulk);
					if($db->num_rows($ret_bulk))
					{
						while ($row_bulk = $db->fetch_array($ret_bulk))
						{
							$insert_array 								= array();
							$insert_array['products_product_id'] 		= $product_id;
							$insert_array['bulk_qty'] 					= addslashes(stripslashes($row_bulk['bulk_qty']));
							$insert_array['bulk_price'] 				= addslashes(stripslashes($row_bulk['bulk_price']));
							$insert_array['comb_id'] 					= $comb_id;
							$db->insert_from_array($insert_array,'product_bulkdiscount');
						}
					}
						
				}
			}
		}
	}	
	fclose($fp_varcombmap);
	fclose($fp_varmap);
	fclose($fp_varvalmap);
	fclose($fp_prodmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Products Variable Combinations Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
