<?php
	include_once('header.php');
	
	$spcode = trim($_REQUEST['sp_code']);
	
	echo "<br>".$sql_prod = "SELECT product_id,product_name FROM products WHERE product_special_product_code = '".$spcode."'";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
			$row_prod = $db->fetch_array($ret_prod);
			echo "<br> Products Table: - Id - ".$row_prod['product_id']." Product Name: ".$row_prod['product_name'];
	}
	else
	{
			echo '<br> Not found in the products table';
	}
			
	echo "<br>".$sql_prod = "SELECT products_product_id FROM  product_variable_combination_stock WHERE comb_special_product_code = '".$spcode."'";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
			$row_prod = $db->fetch_array($ret_prod);
			$sql_prod1 = "SELECT product_id,product_name FROM products WHERE product_id = ".$row_prod['products_product_id']." LIMIT 1";
			$ret_prod1 = $db->query($sql_prod1);
			$row_prod1 = $db->fetch_array($ret_prod1);
			if($db->num_rows($ret_prod1))
			{
				echo "<br> Combination Table: Id - ".$row_prod1['product_id']." Product Name: ".$row_prod1['product_name'];
			}	
	}
	else
	{
			echo '<br> Not found in the product combination table';
	}		
		
	
?>
