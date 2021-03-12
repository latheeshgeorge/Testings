<?php
	include "header.php";
	
	$productexport_file = 'csv/products.csv';
	$productbulkexport_file = 'csv/products_bulk.csv';
	$productvarexport_file = 'csv/products_variable.csv';
	
	$fp_prod = fopen($productexport_file,'w');
	if (!$fp_prod)
	{
		echo "Cannot open the product file";
		exit;
	}
	
	$fp_bulk = fopen($productbulkexport_file,'w');
	if (!$fp_bulk)
	{
		echo "Cannot open the bulk file";
		exit;
	}
	
	$fp_var = fopen($productvarexport_file,'w');
	if (!$fp_var)
	{
		echo "Cannot open the bulk file";
		exit;
	}
	
	fwrite($fp_prod,'"Product Id","Product Name","Web Price","Cost Price","Discount Type","Discount Value","Bulk Discount Exists"'."\n");
	fwrite($fp_bulk,'"Product Id","Bulk Id","Bulk Qty","Bulk Price","Combination Id"'."\n");
	fwrite($fp_var,'"Product Id","Var Id","Var Name","Value Exists","Value Id","Value Caption","Var Price"'."\n");
	$sql_prod = "SELECT product_id,product_name,product_webprice,product_costprice,product_discount,product_discount_enteredasval,product_bulkdiscount_allowed 
					FROM 
						products 
					WHERE 
						sites_site_id = $siteid 
					ORDER BY product_name";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$orgproduct_id 	= ($row_prod['product_id']);
			$product_id 	= add_quotes($row_prod['product_id']);
			$product_name	= add_quotes(stripslashes($row_prod['product_name']));
			$product_webprice	= add_quotes(stripslashes($row_prod['product_webprice']));
			$product_costprice	= add_quotes(stripslashes($row_prod['product_costprice']));
			$product_discount	= add_quotes(stripslashes($row_prod['product_discount']));
			$product_discount_enteredasval	= add_quotes(stripslashes($row_prod['product_discount_enteredasval']));
			$product_bulkdiscount_allowed	= add_quotes(stripslashes($row_prod['product_bulkdiscount_allowed']));
			fwrite($fp_prod,"$product_id,$product_name,$product_webprice,$product_costprice,$product_discount_enteredasval,$product_discount,$product_bulkdiscount_allowed"."\n");
			
			// Check whether bulk discount is enabled for this product
			if($row_prod['product_bulkdiscount_allowed']=='Y')
			{
				$sql_bulk = "SELECT bulk_id,bulk_qty,bulk_price
								FROM 
									product_bulkdiscount 
								WHERE 
									products_product_id = $orgproduct_id 
									AND comb_id = 0
								ORDER BY bulk_qty ASC";
				$ret_bulk = $db->query($sql_bulk);
				if($db->num_rows($ret_bulk))
				{
					while ($row_bulk = $db->fetch_array($ret_bulk))
					{
						$bulk_id 	= add_quotes($row_bulk['bulk_id']);
						$bulk_qty 	= add_quotes($row_bulk['bulk_qty']);
						$bulk_price = add_quotes($row_bulk['bulk_price']);
						$comb_id 	= add_quotes($row_bulk['comb_id']);
						fwrite($fp_bulk,"$product_id,$bulk_id,$bulk_qty,$bulk_price,$comb_id"."\n");
					}
				}
			}
			
			// Check whether variables exists for this product
			$sql_var = "SELECT var_id,var_name,var_value_exists,var_price 
							FROM 
								product_variables 
							WHERE 
								products_product_id = $orgproduct_id 
							ORDER BY
								var_order";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var))
			{
				while ($row_var = $db->fetch_array($ret_var))
				{
					$var_id 			= add_quotes($row_var['var_id']);
					$var_name 			= add_quotes($row_var['var_name']);
					$var_value_exists 	= add_quotes($row_var['var_value_exists']);
					$var_price 			= add_quotes($row_var['var_price']);
					
					// Check whether values exists for this variable
					if($row_var['var_value_exists']==1)
					{
						$sql_value = "SELECT var_value_id,var_value,var_addprice
										FROM 
											product_variable_data 
										WHERE 
											product_variables_var_id = ".$row_var['var_id']." 
										ORDER BY 
											var_order";
						$ret_value = $db->query($sql_value);
						if($db->num_rows($ret_value))
						{
							while ($row_value = $db->fetch_array($ret_value))
							{
								$var_value_id = add_quotes($row_value['var_value_id']);
								$var_value = add_quotes($row_value['var_value']);
								$var_addprice = add_quotes($row_value['var_addprice']);
								fwrite($fp_var,"$product_id,$var_id,$var_name,$var_value_exists,$var_value_id,$var_value,$var_addprice"."\n");
							}
						}
					}
					else
					{
						$var_value_id = "";
						$var_value = "";
						$var_addprice = "0";
						fwrite($fp_var,"$product_id,$var_id,$var_name,$var_value_exists,$var_value_id,$var_value,$var_price"."\n");
					}
				}
			}
		}
	}
	fclose($fp_prod);
	fclose($fp_bulk);
	fclose($fp_var);
	
	echo "Done";
	
	function add_quotes($str)
	{
		return '"'.$str.'"';
	}
?>

