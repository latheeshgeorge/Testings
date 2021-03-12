<?php

	include_once('header.php');	
	
	$import_prod_map			= 'map/product_map.csv';
	$import_dir_map             = 'map/image_directory_map.csv';
	$import_cat_map             = 'map/category_map.csv';
	$import_img_map             = 'map/image_map.csv';
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
<?php
        $fp_imgmap = fopen($import_img_map,'w');
		if (!$fp_imgmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
      
       $fp_prodmap = fopen($import_prod_map,'r');
		if (!$fp_prodmap)
		{
			echo "Cannot open the file map products";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$imgprod_arr = array();
		while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$imgprod_arr[$data[0]] = $data[1];
			}
			$i++;
		}	
		fclose($fp_prodmap);
		//image directory 
		$fp_dirmap = fopen($import_dir_map,'r');
		if (!$fp_dirmap)
		{
			echo "Cannot open the file map directory";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$imgdir_arr = array();
		while (($data = fgetcsv($fp_dirmap, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$imgdir_arr[$data[0]] = $data[1];
			}
			$i++;
		}	
		fclose($fp_dirmap);
		
	    $fp_catmap = fopen($import_cat_map,'r');
		if (!$fp_catmap)
		{
			echo "Cannot open the file map category";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$imgcat_arr = array();
		while (($data = fgetcsv($fp_catmap, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$imgcat_arr[$data[0]] = $data[1];
			}
			$i++;
		}	
		fclose($fp_catmap);	
		
		$sql_images = "SELECT * FROM images WHERE sites_site_id	= $src_siteid";
		$ret_images = $db->query($sql_images);
		fwrite($fp_imgmap,'Old Img Id,New Img Id'."\r\n"); // writing the header row
		$atleast_one_err = 0;
		$i =0;
		if($db->num_rows($ret_images)>0)
		{
		  while($row_images = $db->fetch_array($ret_images))
		  {
		    $err_msg 											= '';			
			$insert_array 										= array();
			$insert_array['images_directory_directory_id']   =  $imgdir_arr[$row_images['images_directory_directory_id']];
			$insert_array['sites_site_id'] 					=	$dest_siteid;
			$insert_array['image_title'] 					= 	addslashes(stripcslashes($row_images['image_title']));
			$insert_array['image_bigpath']  				= 	$row_images['image_bigpath'];
			$insert_array['image_thumbpath']  				=	$row_images['image_thumbpath'];
			$insert_array['image_bigcategorypath'] 			= 	$row_images['image_bigcategorypath'];
			$insert_array['image_thumbcategorypath'] 		=	$row_images['image_thumbcategorypath'];
			$insert_array['image_extralargepath'] 			=	$row_images['image_extralargepath'];
			$insert_array['image_gallerythumbpath']  		=	$row_images['image_gallerythumbpath'];
			$insert_array['image_iconpath'] 				= 	$row_images['image_iconpath'];
            $db->insert_from_array($insert_array,'images');
			$image_id 	= $db->insert_id();
			$old_img = $row_images['image_id'];
		    fwrite($fp_imgmap,"$old_img,$image_id"."\r\n"); // writing the header row
		   // $sql_prod_map  = "SELECT * FROM images_product WHERE images_image_id = ";
		    // Get the label group to category mapping from source website
			
			$sql_imgmap = "SELECT * FROM images_product WHERE images_image_id = $old_img";
			$ret_imgmap = $db->query($sql_imgmap);
			if($db->num_rows($ret_imgmap))
			{
				while ($row_imgmap = $db->fetch_array($ret_imgmap))
				{
					$products_product_id = $row_imgmap['products_product_id'];
					$insert_array 										= array();
					$insert_array['products_product_id'] 		= $imgprod_arr[$products_product_id];
					$insert_array['images_image_id'] 	        = $image_id;
					$insert_array['image_title'] 	       		= addslashes(stripslashes($row_imgmap['image_title']));
					$insert_array['image_order']				= $row_imgmap['image_order'];
					$db->insert_from_array($insert_array,'images_product');
				}
			}
			
			//category map 
			$sql_imgmap = "SELECT * FROM images_product_category WHERE images_image_id = $old_img";
			$ret_imgmap = $db->query($sql_imgmap);
			if($db->num_rows($ret_imgmap))
			{
				while ($row_imgmap = $db->fetch_array($ret_imgmap))
				{
					$product_categories_category_id = $row_imgmap['product_categories_category_id'];
					$insert_array 										= array();
					$insert_array['product_categories_category_id'] 		= $imgcat_arr[$product_categories_category_id];
					$insert_array['images_image_id'] 	        = $image_id;
					$insert_array['image_title'] 	       		= addslashes(stripcslashes($row_imgmap['image_title']));
					$insert_array['image_order']				= $row_imgmap['image_order'];
					$db->insert_from_array($insert_array,'images_product_category');
				}
			}
		  
		  }
		
		}		

		fclose($fp_imgmap);
		
		$sql_srchost 		= "SELECT site_domain FROM sites WHERE site_id = $src_siteid LIMIT 1";
		$ret_srchost        = $db->query($sql_srchost);
		$row_srchost  		= $db->fetch_array($ret_srchost);
		$srchostname    	= $row_srchost['site_domain'];  
		$sql_desthost 		= "SELECT site_domain FROM sites WHERE site_id = $dest_siteid LIMIT 1";
		$ret_desthost        = $db->query($sql_desthost);
		$row_desthost  		= $db->fetch_array($ret_desthost);
		$desthostname   	= $row_desthost['site_domain'];
		$src 	= ORG_DOCROOT."/images/$srchostname/extralarge/";
		$dest 	= ORG_DOCROOT."/images/$desthostname/extralarge/";
		if (is_dir($src)) {
			if ($dh = opendir($src)) {
			while (($file = readdir($dh)) !== false) {
				//echo "filename: $file : filetype: " . filetype($src . $file) . "\n";
				copy($src.$file,$dest.$file);
			}
			closedir($dh);
			}
		}
		

echo "<H3>Copy Paste completed!</H3>"; //output when done
?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Images imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
