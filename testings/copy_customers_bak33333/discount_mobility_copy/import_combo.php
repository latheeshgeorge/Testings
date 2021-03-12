<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$static_map			= 'map/static_map.csv';
	$var_map			= 'map/product_variable_map.csv';
	$varval_map			= 'map/product_variablevalue_map.csv';
	
	$fp_prodmap = fopen($product_map,'r');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$prod_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$prod_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodmap);
	
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
	
	$fp_statmap = fopen($static_map,'r');
	if (!$fp_statmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$stat_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_statmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$stat_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_statmap);
		
		
	$fp_varmap = fopen($var_map,'r');
	if (!$fp_varmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
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
	$fp_varvalmap = fopen($varval_map,'r');
	if (!$fp_varvalmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}	
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
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		$sql_rev = "SELECT * FROM combo WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 										= array();
				$insert_array['sites_site_id'] 						= $dest_siteid;
				$insert_array['combo_name'] 						= addslashes(stripslashes($row_rev['combo_name']));
				$insert_array['combo_description'] 					= addslashes(stripslashes($row_rev['combo_description']));
				$insert_array['combo_active'] 						= addslashes(stripslashes($row_rev['combo_active']));
				
				$insert_array['combo_showinall'] 					= addslashes(stripslashes($row_rev['combo_showinall']));
				$insert_array['combo_activateperiodchange'] 		= addslashes(stripslashes($row_rev['combo_activateperiodchange']));
				$insert_array['combo_displaystartdate'] 			= addslashes(stripslashes($row_rev['combo_displaystartdate']));
				
				$insert_array['combo_displayenddate'] 				= addslashes(stripslashes($row_rev['combo_displayenddate']));
				$insert_array['combo_hidename'] 					= addslashes(stripslashes($row_rev['combo_hidename']));
				$insert_array['combo_totproducts'] 					= addslashes(stripslashes($row_rev['combo_totproducts']));
				$insert_array['combo_bundleprice']					= addslashes(stripslashes($row_rev['combo_bundleprice']));
				$insert_array['combo_apply_direct_discount_also'] 	= addslashes(stripslashes($row_rev['combo_apply_direct_discount_also']));
				$insert_array['combo_apply_custgroup_discount_also']= addslashes(stripslashes($row_rev['combo_apply_custgroup_discount_also']));
				$db->insert_from_array($insert_array,'combo');
				$combo_id = $db->insert_id();
				$comboid = $row_rev['combo_id'];
				
							
				$sql_combprod = "SELECT * FROM combo_products WHERE combo_combo_id = $comboid";
				$ret_combprod = $db->query($sql_combprod);
				if($db->num_rows($ret_combprod))
				{
					while ($row_combprod = $db->fetch_array($ret_combprod))
					{
						$insert_array 									= array();
						$insert_array['combo_combo_id'] 				= $combo_id;
						$insert_array['products_product_id'] 			= $prod_arr[$row_combprod['products_product_id']];
						$insert_array['combo_discount'] 				= addslashes(stripslashes($row_combprod['combo_discount']));
						$insert_array['comboprod_order'] 				= addslashes(stripslashes($row_combprod['comboprod_order']));
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$db->insert_from_array($insert_array,'combo_products');
						$comboprod_id = $db->insert_id();
						$comboprodid = $row_combprod['comboprod_id'];
						
						$sql_varcomb = "SELECT * FROM combo_products_variable_combination WHERE combo_products_comboprod_id = $comboprodid";
						$ret_varcomb = $db->query($sql_varcomb);
						if($db->num_rows($ret_varcomb))
						{
							while ($row_varcomb = $db->fetch_array($ret_varcomb))
							{
								$insert_array 									= array();
								$insert_array['combo_products_comboprod_id'] 	= $comboprod_id;
								$insert_array['products_product_id'] 			= $prod_arr[$row_varcomb['products_product_id']];
								$db->insert_from_array($insert_array,'combo_products_variable_combination');
								$curcomb_id = $db->insert_id();
								$curcombid = $row_varcomb['comb_id'];
								
								$sql_combmap = "SELECT * FROM combo_products_variable_combination_map WHERE combo_products_variable_combination_comb_id = $curcombid";
								$ret_combmap = $db->query($sql_combmap);
								if($db->num_rows($ret_combmap))
								{
									while ($row_combmap = $db->fetch_array($ret_combmap))
									{
										$insert_array 													= array();
										$insert_array['combo_products_variable_combination_comb_id'] 	= $curcomb_id;
										$insert_array['var_id'] 										= $var_arr[$row_combmap['var_id']];
										$insert_array['var_value_id'] 									= $varvalue_arr[$row_combmap['var_value_id']];
										$insert_array['products_product_id'] 							= $prod_arr[$row_combmap['products_product_id']];
										$db->insert_from_array($insert_array,'combo_products_variable_combination_map');
									}
								}	
								
								
							}
						}
						
					}
				}
				
				
				
				
				
				
				
				
				
				// display category
				$sql_disp = "SELECT * FROM combo_display_category WHERE combo_id = $comboid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['combo_id'] 						= $combo_id;
						$insert_array['product_categories_category_id'] = $cat_arr[$row_disp['product_categories_category_id']];
						$db->insert_from_array($insert_array,'combo_display_category');
					}
				}
				
				// display products
				$sql_disp = "SELECT * FROM combo_display_product WHERE combo_id = $comboid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['combo_id'] 						= $combo_id;
						$insert_array['products_product_id'] 			= $prod_arr[$row_disp['products_product_id']];
						$db->insert_from_array($insert_array,'combo_display_product');
					}
				}
				
				// display static pages
				$sql_disp = "SELECT * FROM combo_display_static WHERE combo_id = $comboid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['combo_id'] 						= $combo_id;
						$insert_array['static_pages_page_id'] 			= $stat_arr[$row_disp['static_pages_page_id']];
						$db->insert_from_array($insert_array,'combo_display_static');
					}
				}

			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Combo Deals Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
