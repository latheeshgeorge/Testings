<?php
	set_time_limit(0);
	include_once('header.php');
	
	
	$shop_map			= 'map/shop_map.csv';
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$static_map			= 'map/static_map.csv';	
	
	$fp_shopmap = fopen($shop_map,'r');
	if (!$fp_shopmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$shop_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_shopmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$shop_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_shopmap);
	
	
	
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
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		$sql_rev = "SELECT * FROM product_shelf WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 								= array();
				$insert_array['sites_site_id'] 				= $dest_siteid;
				$insert_array['shelf_name'] 				= addslashes(stripslashes($row_rev['shelf_name']));
				$insert_array['shelf_description'] 			= addslashes(stripslashes($row_rev['shelf_description']));
				$insert_array['shelf_order'] 				= addslashes(stripslashes($row_rev['shelf_order']));
				$insert_array['shelf_hide'] 				= addslashes(stripslashes($row_rev['shelf_hide']));
				$insert_array['shelf_displaytype'] 			= addslashes(stripslashes($row_rev['shelf_displaytype']));
				$insert_array['shelf_showinall'] 			= addslashes(stripslashes($row_rev['shelf_showinall']));
				
				$insert_array['shelf_showimage'] 			= addslashes(stripslashes($row_rev['shelf_showimage']));
				$insert_array['shelf_showinhome'] 			= addslashes(stripslashes($row_rev['shelf_showinhome']));
				$insert_array['shelf_showtitle']			= addslashes(stripslashes($row_rev['shelf_showtitle']));
				$insert_array['shelf_showdescription'] 		= addslashes(stripslashes($row_rev['shelf_showdescription']));
				$insert_array['shelf_showprice'] 			= addslashes(stripslashes($row_rev['shelf_showprice']));
				$insert_array['shelf_showrating'] 			= addslashes(stripslashes($row_rev['shelf_showrating']));
				$insert_array['shelf_showbonuspoints'] 		= addslashes(stripslashes($row_rev['shelf_showbonuspoints']));
				$insert_array['shelf_currentstyle'] 		= addslashes(stripslashes($row_rev['shelf_currentstyle']));
				$insert_array['shelf_activateperiodchange'] = addslashes(stripslashes($row_rev['shelf_activateperiodchange']));
				$insert_array['shelf_displaystartdate'] 	= addslashes(stripslashes($row_rev['shelf_displaystartdate']));
				$insert_array['shelf_displayenddate'] 		= addslashes(stripslashes($row_rev['shelf_displayenddate']));
				$db->insert_from_array($insert_array,'product_shelf');
				$shelf_id = $db->insert_id();
				$shelfid = $row_rev['shelf_id'];
				
				// Get mapped products
				$sql_prod = "SELECT * FROM product_shelf_product WHERE product_shelf_shelf_id = $shelfid";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					while ($row_prod = $db->fetch_array($ret_prod))
					{
						$insert_array 								= array();
						$insert_array['product_shelf_shelf_id'] 	= $shelf_id;
						$insert_array['products_product_id'] 		= $prod_arr[$row_prod['products_product_id']];
						$insert_array['product_order'] 				= addslashes(stripslashes($row_prod['product_order']));
						$db->insert_from_array($insert_array,'product_shelf_product');
					}
				}
				
				
				// display category
				$sql_disp = "SELECT * FROM product_shelf_display_category WHERE product_shelf_shelf_id = $shelfid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['product_shelf_shelf_id'] 		= $shelf_id;
						$insert_array['product_categories_category_id'] = $cat_arr[$row_disp['product_categories_category_id']];
						$db->insert_from_array($insert_array,'product_shelf_display_category');
					}
				}
				
				// display products
				$sql_disp = "SELECT * FROM product_shelf_display_product WHERE product_shelf_shelf_id = $shelfid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['product_shelf_shelf_id'] 		= $shelf_id;
						$insert_array['products_product_id'] 			= $prod_arr[$row_disp['products_product_id']];
						$db->insert_from_array($insert_array,'product_shelf_display_product');
					}
				}
				
				// display static pages
				$sql_disp = "SELECT * FROM product_shelf_display_static WHERE product_shelf_shelf_id = $shelfid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['product_shelf_shelf_id'] 		= $shelf_id;
						$insert_array['static_pages_page_id'] 			= $stat_arr[$row_disp['static_pages_page_id']];
						$db->insert_from_array($insert_array,'product_shelf_display_static');
					}
				}
				
				// display shops
				$sql_disp = "SELECT * FROM product_shelf_display_shop WHERE product_shelf_shelf_id = $shelfid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 					= $dest_siteid;
						$insert_array['product_shelf_shelf_id'] 		= $shelf_id;
						$insert_array['product_shop_shop_id'] 			= $shop_arr[$row_disp['product_shop_shop_id']];
						$db->insert_from_array($insert_array,'product_shelf_display_shop');
					}
				}
				
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Shelves Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
