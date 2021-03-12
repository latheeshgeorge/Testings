<?php
	set_time_limit(0);
	include_once('header.php');
	
	
	$shop_map			= 'map/shop_map.csv';
	$product_map		= 'map/product_map.csv';	
	
	$fp_shopmap = fopen($shop_map,'w');
	if (!$fp_shopmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	fwrite($fp_shopmap,'Old Shop Id,New ShopId,Parent Id'."\r\n"); // writing the header row
		
	$i=0;
	
	
	
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
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		$sql_rev = "SELECT * FROM product_shopbybrand WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 									= array();
				$insert_array['shopbrand_parent_id'] 			= 0;
				$insert_array['sites_site_id'] 					= $dest_siteid;
				$insert_array['shopbrand_name'] 				= addslashes(stripslashes($row_rev['shopbrand_name']));
				$insert_array['shopbrand_description'] 			= addslashes(stripslashes($row_rev['shopbrand_description']));
				$insert_array['shopbrand_bottomdescription'] 	= addslashes(stripslashes($row_rev['shopbrand_bottomdescription']));
				$insert_array['shopbrand_hide'] 				= addslashes(stripslashes($row_rev['shopbrand_hide']));
				$insert_array['shopbrand_product_displaytype'] 	= addslashes(stripslashes($row_rev['shopbrand_product_displaytype']));
				$insert_array['shopbrand_order'] 				= addslashes(stripslashes($row_rev['shopbrand_order']));
				
				$insert_array['shopbrand_showimageofproduct'] 	= addslashes(stripslashes($row_rev['shopbrand_showimageofproduct']));
				$insert_array['shopbrand_subshoplisttype'] 		= addslashes(stripslashes($row_rev['shopbrand_subshoplisttype']));
				$insert_array['shopbrand_default_shopbrandgroup_id'] = 0;
				$insert_array['shopbrand_product_showimage']	= addslashes(stripslashes($row_rev['shopbrand_product_showimage']));
				$insert_array['shopbrand_product_showtitle'] 	= addslashes(stripslashes($row_rev['shopbrand_product_showtitle']));
				$insert_array['shopbrand_product_showshortdescription'] = addslashes(stripslashes($row_rev['shopbrand_product_showshortdescription']));
				$insert_array['shopbrand_product_showprice'] 		= addslashes(stripslashes($row_rev['shopbrand_product_showprice']));
				$insert_array['shopbrand_product_showrating'] 		= addslashes(stripslashes($row_rev['shopbrand_product_showrating']));
				$insert_array['shopbrand_product_showbonuspoints'] 	= addslashes(stripslashes($row_rev['shopbrand_product_showbonuspoints']));
				$insert_array['shopbrand_turnoff_mainimage'] 		= addslashes(stripslashes($row_rev['shopbrand_turnoff_mainimage']));
				$insert_array['shopbrand_turnoff_moreimages'] 		= addslashes(stripslashes($row_rev['shopbrand_turnoff_moreimages']));
				$db->insert_from_array($insert_array,'product_shopbybrand');
			
				$shop_id = $db->insert_id();
				$shopid = $row_rev['shopbrand_id'];
				$parent_id = $row_rev['shopbrand_parent_id'];
				fwrite($fp_shopmap,"$shopid,$shop_id,$parent_id"."\r\n"); // writing the header row
				
				
				// Get mapped products
				$sql_prod = "SELECT * FROM product_shopbybrand_product_map WHERE product_shopbybrand_shopbrand_id = $shopid";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					while ($row_prod = $db->fetch_array($ret_prod))
					{
						$insert_array 										= array();
						$insert_array['product_shopbybrand_shopbrand_id'] 	= $shop_id;
						$insert_array['products_product_id'] 				= $prod_arr[$row_prod['products_product_id']];
						$insert_array['map_sortorder'] 						= addslashes(stripslashes($row_rev['map_sortorder']));
						$db->insert_from_array($insert_array,'product_shopbybrand_product_map');
					}
				}
				
			}
		}
		fclose($fp_shopmap);
		
		
		$fp_shopmap = fopen($shop_map,'r');
		if (!$fp_shopmap)
		{
			echo "Cannot open the file map";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$shop_arr = array();
		while (($data = fgetcsv($fp_shopmap, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$shop_arr[$data[0]]= $data[1];
			}
			$i++;
		}	
		fclose($fp_shopmap);
		
		
		$fp_shopmap = fopen($shop_map,'r');
		if (!$fp_shopmap)
		{
			echo "Cannot open the file";
			exit;
		}
		$atleast_one_err = 0;
		$i =0;
		while (($data = fgetcsv($fp_shopmap, 1000, ",")) !== FALSE)
		{
			if($i!=0) // case of header row
			{
				$err_msg 				= '';
				$old_shopid	 				= trim($data[0]);
				$new_shopid	 				= trim($data[1]);
				$parent						= trim($data[2]);
				if($parent)
				{	
					$update_array							= array();
					$update_array['shopbrand_parent_id']	= $shop_arr[$parent];
					$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$new_shopid));
				}	
				
			}			
			
			$i++;
		}
		fclose($fp_shopmap);
		
		
		
		
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Shop by Brand Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
