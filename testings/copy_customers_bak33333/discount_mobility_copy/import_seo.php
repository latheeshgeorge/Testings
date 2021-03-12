	<?php

	include_once('header.php');
		
	$import_key_map		= 'map/seokeyword_map.csv';
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$static_map			= 'map/static_map.csv';
	$shop_map			= 'map/shop_map.csv';
	
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
	
	
	$i=0;
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
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_keymap = fopen($import_key_map,'w');
		if (!$fp_keymap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all categories from source site
	$sql_key = "SELECT * FROM se_keywords WHERE sites_site_id = $src_siteid";
	$ret_key = $db->query($sql_key);
	
	fwrite($fp_keymap,'Old kw Id,New kw Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_key))
	{
		while ($row_key = $db->fetch_array($ret_key))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['keyword_keyword'] 					= addslashes(stripslashes($row_key['keyword_keyword']));
			$db->insert_from_array($insert_array,'se_keywords');
			$kw_id 	= $db->insert_id();
			
			$kwid 			= $row_key['keyword_id'];
			
			fwrite($fp_keymap,"$kwid,$kw_id"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_keymap);
	
	$fp_keymap = fopen($import_key_map,'r');
	if (!$fp_keymap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$kw_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_keymap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$kw_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_keymap);	
	
	$sql_seo = "SELECT * FROM se_bestseller_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_bestseller_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_bestseller_titles WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_bestseller_titles');
		}
	}
	
	$sql_seo = "SELECT * FROM se_faq_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_faq_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_faq_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_faq_title');
		}
	}
	
	
	$sql_seo = "SELECT * FROM se_forgotpassword_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_forgotpassword_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_forgotpassword_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_forgotpassword_title');
		}
	}
	
	
	
	$sql_seo = "SELECT * FROM se_help_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_help_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_help_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_help_title');
		}
	}
	
	
	$sql_seo = "SELECT * FROM se_home_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_home_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_home_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_home_title');
		}
	}
	
	
	$sql_seo = "SELECT * FROM se_registration_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_registration_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_registration_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_registration_title');
		}
	}
	
	
	$sql_seo = "SELECT * FROM se_sitemap_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_sitemap_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_sitemap_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_sitemap_title');
		}
	}
	
	
	$sql_seo = "SELECT * FROM se_sitereviews_keywords WHERE sites_site_id = $src_siteid ORDER BY id";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
			$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
			$db->insert_from_array($insert_array,'se_sitereviews_keywords');
		}
	}
	
	$sql_seo = "SELECT * FROM se_sitereviews_title WHERE sites_site_id = $src_siteid";
	$ret_seo = $db->query($sql_seo);
	if($db->num_rows($ret_seo))
	{
		while ($row_seo = $db->fetch_array($ret_seo))
		{
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
			$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
			$db->insert_from_array($insert_array,'se_sitereviews_title');
		}
	}
	
	foreach ($cat_arr as $kk=>$vv)
	{
		$sql_seo = "SELECT * FROM se_category_keywords WHERE product_categories_category_id = $kk ORDER BY id";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
				$insert_array['product_categories_category_id'] 	= $cat_arr[$row_seo['product_categories_category_id']];
				$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
				$db->insert_from_array($insert_array,'se_category_keywords');
			}
		}
		
		$sql_seo = "SELECT * FROM se_category_title WHERE product_categories_category_id = $kk";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['sites_site_id'] 						= $dest_siteid;
				$insert_array['product_categories_category_id'] 	= $cat_arr[$row_seo['product_categories_category_id']];
				$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
				$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
				$db->insert_from_array($insert_array,'se_category_title');
			}
		}
	}
	foreach ($prod_arr as $kk=>$vv)
	{
		$sql_seo = "SELECT * FROM se_product_keywords WHERE products_product_id = $kk ORDER BY id";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
				$insert_array['products_product_id'] 				= $prod_arr[$row_seo['products_product_id']];
				$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
				$db->insert_from_array($insert_array,'se_product_keywords');
			}
		}
		
		$sql_seo = "SELECT * FROM se_product_title WHERE products_product_id = $kk";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['sites_site_id'] 						= $dest_siteid;
				$insert_array['products_product_id'] 				= $prod_arr[$row_seo['products_product_id']];
				$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
				$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
				$db->insert_from_array($insert_array,'se_product_title');
			}
		}
	}	
	
	foreach ($shop_arr as $kk=>$vv)
	{
		$sql_seo = "SELECT * FROM se_shop_keywords WHERE product_shopbybrand_shopbrand_id = $kk ORDER BY id";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
				$insert_array['product_shopbybrand_shopbrand_id'] 	= $shop_arr[$row_seo['product_shopbybrand_shopbrand_id']];
				$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
				$db->insert_from_array($insert_array,'se_shop_keywords');
			}
		}
		
		$sql_seo = "SELECT * FROM se_shop_title WHERE product_shopbybrand_shopbrand_id = $kk";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['sites_site_id'] 						= $dest_siteid;
				$insert_array['product_shopbybrand_shopbrand_id'] 	= $shop_arr[$row_seo['product_shopbybrand_shopbrand_id']];
				$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
				$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
				$db->insert_from_array($insert_array,'se_shop_title');
			}
		}
	}
	foreach ($stat_arr as $kk=>$vv)
	{
		$sql_seo = "SELECT * FROM se_static_keywords WHERE static_pages_page_id = $kk ORDER BY id";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['se_keywords_keyword_id'] 			= $kw_arr[$row_seo['se_keywords_keyword_id']];
				$insert_array['static_pages_page_id'] 				= $stat_arr[$row_seo['static_pages_page_id']];
				$insert_array['uniq_id'] 							= addslashes(stripslashes($dest_siteid.'_'.$row_seo['uniq_id']));
				$db->insert_from_array($insert_array,'se_static_keywords');
			}
		}
		
		$sql_seo = "SELECT * FROM se_static_title WHERE static_pages_page_id = $kk";
		$ret_seo = $db->query($sql_seo);
		if($db->num_rows($ret_seo))
		{
			while ($row_seo = $db->fetch_array($ret_seo))
			{
				$insert_array 										= array();
				$insert_array['sites_site_id'] 						= $dest_siteid;
				$insert_array['static_pages_page_id'] 				= $stat_arr[$row_seo['static_pages_page_id']];
				$insert_array['title'] 								= addslashes(stripslashes($row_seo['title']));
				$insert_array['meta_description'] 					= addslashes(stripslashes($row_seo['meta_description']));
				$db->insert_from_array($insert_array,'se_static_title');
			}
		}
	}
	
	
	
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- SEO Details Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
