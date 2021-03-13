<?php
	/*###########################################################################################
	# Script Name 	: mod_compare_products.php
	# Description 	: Page which call the function to display the Product comparesection
	# Coded by 		: ANU
	# Created on	: 10-mar-2008
	# Modified by	: ANU
	# Modified On	: 10-mar-2008
	############################################################################################*/
	//$compare_pdt_array	= array();	
	 if(isProductCompareEnabled()) {
		
		if (is_array($_SESSION['compare_products'])) { // to handle the case only if the session values exist
			$compare_pdt_id_arr =  array_unique($_SESSION['compare_products']);
			$compare_pdt_ids = implode(",",$_SESSION['compare_products']);
			if($compare_pdt_ids){
				$sql_compare_pdts = "SELECT product_name,product_id FROM products WHERE product_id IN ($compare_pdt_ids) ";
				$ret_compare_pdts = $db->query($sql_compare_pdts);
				$components->mod_compare_products($ret_compare_pdts,$display_title); // calls the compare product section
			}
		}
	
	}						
?>