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
	$product_id = $_REQUEST['p_id'];
	// Get the images to be used in the flash rotation
	$sql_prod = "SELECT product_flashrotate_filenames 
							FROM 
								products 
							WHERE 
								product_id = '".$product_id."' 
								AND sites_site_id = $ecom_siteid 
								AND product_hide = 'N'  
							LIMIT 
								1";
	$ret_prod = $db->query($sql_prod);
	if($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);
		$file_arr		= explode(',',$row_prod['product_flashrotate_filenames']);
		$path = $ecom_selfhttp.$ecom_hostname."/images/".$ecom_hostname."/product_rotate/p$product_id";
		header("Content-Type: text/xml");
		print "<?xml version=\"1.0\" ?>\n";
    	print "<images>\n";
		$i = 1;
		for($j=0;$j<count($file_arr);$j++)
		{
			print "<image mcname='mc_".$i."' imgurl='".$path.'/'.$file_arr[$j]."' />\n";
			$i++;
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
