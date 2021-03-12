<?php

	include_once('header.php');
		
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$shop_map			= 'map/shop_map.csv';
	$image_map			= 'map/image_map.csv';
	$imagedir_map		= 'map/imagedir_map.csv';
	
	
	$fp_imagemap = fopen($image_map,'w');
	if (!$fp_imagemap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$fp_imagedirmap = fopen($imagedir_map,'r');
	if (!$fp_imagedirmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$dir_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_imagedirmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$dir_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_imagedirmap);
	
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
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		
	
	
	// Get the list of all categories from source site
	$sql_key = "SELECT * FROM images WHERE sites_site_id = $src_siteid";
	$ret_key = $db->query($sql_key);
	
	fwrite($fp_imagemap,'Old Id,New Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_key))
	{
		while ($row_key = $db->fetch_array($ret_key))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['images_directory_directory_id'] 		= $dir_arr[$row_key['images_directory_directory_id']];
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['image_title'] 						= addslashes(stripslashes($row_key['image_title']));
			$insert_array['image_bigpath'] 						= addslashes(stripslashes($row_key['image_bigpath']));
			$insert_array['image_thumbpath'] 					= addslashes(stripslashes($row_key['image_thumbpath']));
			$insert_array['image_bigcategorypath'] 				= addslashes(stripslashes($row_key['image_bigcategorypath']));
			$insert_array['image_thumbcategorypath'] 			= addslashes(stripslashes($row_key['image_thumbcategorypath']));
			$insert_array['image_extralargepath'] 				= addslashes(stripslashes($row_key['image_extralargepath']));
			$insert_array['image_gallerythumbpath'] 			= addslashes(stripslashes($row_key['image_gallerythumbpath']));
			$insert_array['image_iconpath'] 					= addslashes(stripslashes($row_key['image_iconpath']));
			$db->insert_from_array($insert_array,'images');
			$image_id 	= $db->insert_id();
			$imageid		= $row_key['image_id'];
			fwrite($fp_imagemap,"$imageid,$image_id"."\r\n"); // writing the header row
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_bigpath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_bigpath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_thumbpath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_thumbpath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_bigcategorypath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_bigcategorypath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_thumbcategorypath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_thumbcategorypath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_extralargepath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_extralargepath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_gallerythumbpath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_gallerythumbpath']);
			copy($src_img,$des_img);
			
			//Copying the images
			$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/'.stripslashes($row_key['image_iconpath']);
			$des_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/'.stripslashes($row_key['image_iconpath']);
			copy($src_img,$des_img);
		}
	}	
	fclose($fp_imagemap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Imaged Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
