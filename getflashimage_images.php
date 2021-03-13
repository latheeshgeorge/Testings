<?php
	require("config.php");
	
	if($ecom_selfssl_active==1)
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	//$product_id = 81;
	//$product_id =$_REQUEST['product_id'];
	//$tab_id		= 22;//
	$prod_tab_id		=$_REQUEST['prod_tab_id'];
	$id_arr 				= explode('~',$prod_tab_id);
	$product_id 		= $id_arr[0];
	$tab_id 				= $id_arr[1];
	$show_normal = true;
	// case if tab id exists
	if ($tab_id)
	{
		// Get the list of images assigned to this product
		$sql_images = "SELECT b.image_title,a.image_thumbpath,a.image_bigpath,a.image_extralargepath 
						FROM 
							images a,images_product_tab b 
						WHERE 
							b.product_tabs_tab_id=$tab_id 
							AND a.image_id = b.images_image_id 
						ORDER BY 
							b.image_order";
		$ret_images = $db->query($sql_images);
		if ($db->num_rows($ret_images))
			$show_normal = false; // if tab images exists then show the tab image
	}
	
	if ($show_normal) // Decides whether to query for product images.
	{
		// Get the list of images assigned to this product
		$sql_images = "SELECT b.image_title,a.image_thumbpath,a.image_bigpath,a.image_extralargepath 
						FROM 
							images a,images_product b 
						WHERE 
							b.products_product_id=$product_id 
							AND a.image_id = b.images_image_id 
						ORDER BY 
							b.image_order";
		$ret_images = $db->query($sql_images);
	}	
	if($db->num_rows($ret_images))
	{
		$path = $ecom_selfhttp.$ecom_hostname."/images/".$ecom_hostname;
		header("Content-Type: text/xml");
		print "<?xml version=\"1.0\" ?>\n";
    	print "<images>\n";
		while ($row_image = $db->fetch_array($ret_images))
		{
			$extra = ($row_image['image_bigpath'])?$path.'/'.$row_image['image_extralargepath']:'-';
			print "<image thumb='".$path.'/'.$row_image['image_thumbpath']."' big='".$path.'/'.$row_image['image_bigpath']."' extralarge='".$extra."' imgtitle='".$row_image['image_title']."' />\n";
		}
		 print "</images>";
	}
	else // Done to handle the case of no images 
	{
		header("Content-Type: text/xml");
		print "<?xml version=\"1.0\" ?>\n";
    	print "<images>\n";
    	print "</images>";
	}
?>
