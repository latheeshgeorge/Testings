<?php

	include_once('header.php');
		
	$imagedir_map		= 'map/imagedir_map.csv';
	
	
	$fp_imagedirmap = fopen($imagedir_map,'w');
	if (!$fp_imagedirmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		
	
	
	// Get the list of all categories from source site
	$sql_key = "SELECT * FROM images_directory WHERE sites_site_id = $src_siteid and directory_name <>''";
	$ret_key = $db->query($sql_key);
	
	fwrite($fp_imagedirmap,'Old dir Id,New dir Id,Parent id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_key))
	{
		while ($row_key = $db->fetch_array($ret_key))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['parent_id'] 							= 0;
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['directory_name'] 					= addslashes(stripslashes($row_key['directory_name']));
			$db->insert_from_array($insert_array,'images_directory');
			$dir_id 	= $db->insert_id();
			
			$dirid		= $row_key['directory_id'];
			$parent		= $row_key['parent_id'];
			
			fwrite($fp_imagedirmap,"$dirid,$dir_id,$parent"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_imagedirmap);
	
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
	$fp_imagedirmap = fopen($imagedir_map,'r');
	if (!$fp_imagedirmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$i=0;
	while (($data = fgetcsv($fp_imagedirmap, 1000, ",")) !== FALSE)
	{
		if($i!=0 and trim($data[2]))
		{
			$parent = $dir_arr[$data[2]];
			if($parent)
			{
				$dir_id = $data[1];
				$sql_update = "UPDATE images_directory SET parent_id = $parent WHERE directory_id = $dir_id LIMIT 1";
				$db->query($sql_update);
			}	
		}
		$i++;
	}	
	fclose($fp_imagedirmap);	
	
?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Image Directories Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>