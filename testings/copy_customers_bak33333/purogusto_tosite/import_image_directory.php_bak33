<?php

	include_once('header.php');
	
	
	$import_cat_map			= 'map/image_directory_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the image directory mappings
		$fp_catmap = fopen($import_cat_map,'w');
		if (!$fp_catmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all image directories from source site
	$sql_cat = "SELECT * FROM images_directory WHERE sites_site_id = $src_siteid";
	$ret_cat = $db->query($sql_cat);
	
	
	
	
		
	fwrite($fp_catmap,'Old Dir Id,New Dir Id,Parent Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_cat))
	{
		while ($row_cat = $db->fetch_array($ret_cat))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['parent_id'] 							= 0;
			$insert_array['directory_name'] 					= addslashes(stripslashes($row_cat['directory_name']));
			
						
			$db->insert_from_array($insert_array,'images_directory');
			$category_id 	= $db->insert_id();
			
			$parent_id 		= $row_cat['parent_id'];
			$catid 			= $row_cat['directory_id'];
			
			fwrite($fp_catmap,"$catid,$category_id,$parent_id"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_catmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Image Directores Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
