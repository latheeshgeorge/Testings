<?php

	include_once('header.php');
		
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$shop_map			= 'map/shop_map.csv';
	$image_map			= 'map/image_map.csv';
	$imagedir_map		= 'map/imagedir_map.csv';
	$varcomb_map		= 'map/product_variablecomb_map.csv';
	
	
	$fp_imagemap = fopen($image_map,'r');
	if (!$fp_imagemap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$img_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_imagemap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$img_arr[$data[0]]= $data[1];
		}
		$i++;
	}
	fclose($fp_imagemap);
	
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
	
	
	$fp_varcombmap = fopen($varcomb_map,'r');
	if (!$fp_varcombmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	
	$varcomb_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_varcombmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$varcomb_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_varcombmap);
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	if(count($prod_arr))
	{	
		foreach ($prod_arr as $k=>$v)
		{
			// Get the list of all categories from source site
			$sql_key = "SELECT * FROM images_product WHERE products_product_id = $k";
			$ret_key = $db->query($sql_key);
			$atleast_one_err = 0;
			$i =0;
			if ($db->num_rows($ret_key))
			{
				while ($row_key = $db->fetch_array($ret_key))
				{
					$err_msg 											= '';
					$insert_array 										= array();
					$insert_array['products_product_id'] 				= $v;
					$insert_array['images_image_id'] 					= $img_arr[$row_key['images_image_id']];
					$insert_array['image_title'] 						= addslashes(stripslashes($row_key['image_title']));
					$insert_array['image_order'] 						= addslashes(stripslashes($row_key['image_order']));
					$db->insert_from_array($insert_array,'images_product');
				}
			}
		}
	}	
	if(count($cat_arr))
	{
		foreach ($cat_arr as $k=>$v)
		{
			// Get the list of all categories from source site
			$sql_key = "SELECT * FROM images_product_category WHERE product_categories_category_id = $k";
			$ret_key = $db->query($sql_key);
			$atleast_one_err = 0;
			$i =0;
			if ($db->num_rows($ret_key))
			{
				while ($row_key = $db->fetch_array($ret_key))
				{
					$err_msg 											= '';
					$insert_array 										= array();
					$insert_array['product_categories_category_id'] 	= $v;
					$insert_array['images_image_id'] 					= $img_arr[$row_key['images_image_id']];
					$insert_array['image_title'] 						= addslashes(stripslashes($row_key['image_title']));
					$insert_array['image_order'] 						= addslashes(stripslashes($row_key['image_order']));
					$db->insert_from_array($insert_array,'images_product_category');
				}
			}
		}	
	}	
	if(count($varcomb_arr))
	{
		foreach ($varcomb_arr as $k=>$v)
		{
			// Get the list of all categories from source site
			$sql_key = "SELECT * FROM images_variable_combination WHERE comb_id = $k";
			$ret_key = $db->query($sql_key);
			$atleast_one_err = 0;
			$i =0;
			if ($db->num_rows($ret_key))
			{
				while ($row_key = $db->fetch_array($ret_key))
				{
					$err_msg 											= '';
					$insert_array 										= array();
					$insert_array['comb_id'] 							= $v;
					$insert_array['images_image_id'] 					= $img_arr[$row_key['images_image_id']];
					$insert_array['image_title'] 						= addslashes(stripslashes($row_key['image_title']));
					$insert_array['image_order'] 						= addslashes(stripslashes($row_key['image_order']));
					$db->insert_from_array($insert_array,'images_variable_combination');
				}
				$sql_update = "UPDATE product_variable_combination_stock SET comb_img_assigned =1 WHERE comb_id = $v";
				$db->query($sql_update);
			}
		}	
	}
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Image Mappings Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
