<?php

	include_once('header.php');
	
	
	$import_cat_map			= 'map/labelgroup_map.csv';
	$import_map				= 'map/category_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_catmap = fopen($import_cat_map,'w');
		if (!$fp_catmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
		
		
		$fp_map = fopen($import_map,'r');
		if (!$fp_map)
		{
			echo "Cannot open the file map";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$cat_arr = array();
		while (($data = fgetcsv($fp_map, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$cat_arr[$data[0]]= $data[1];
			}
			$i++;
		}	
		fclose($fp_map);
	
	// Get the list of all product label groups from source site
	$sql_grp = "SELECT * FROM product_labels_group WHERE sites_site_id = $src_siteid";
	$ret_grp = $db->query($sql_grp);
	
	
	
	
		
	fwrite($fp_catmap,'Old groupid Id,New group Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_grp))
	{
		while ($row_grp = $db->fetch_array($ret_grp))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['group_name'] 						= addslashes(stripslashes($row_grp['group_name']));
			$insert_array['group_name_hide'] 					= addslashes(stripslashes($row_grp['group_name_hide']));
			$insert_array['group_hide'] 						= addslashes(stripslashes($row_grp['group_hide']));
			$insert_array['group_order'] 						= addslashes(stripslashes($row_grp['group_order']));
		
			$db->insert_from_array($insert_array,'product_labels_group');
			$group_id 	= $db->insert_id();
			$groupid 	= $row_grp['group_id'];
			
			fwrite($fp_catmap,"$groupid,$group_id"."\r\n"); // writing the header row
			
			// Get the label group to category mapping from source website
			
			$sql_grpmap = "SELECT * FROM product_category_product_labels_group_map WHERE product_labels_group_group_id = $groupid";
			$ret_grpmap = $db->query($sql_grpmap);
			if($db->num_rows($ret_grpmap))
			{
				while ($row_grpmap = $db->fetch_array($ret_grpmap))
				{
					$product_categories_category_id = $row_grpmap['product_categories_category_id'];
					$insert_array 										= array();
					$insert_array['product_labels_group_group_id'] 		= $group_id;
					$insert_array['product_categories_category_id'] 	= $cat_arr[$product_categories_category_id];
					$db->insert_from_array($insert_array,'product_category_product_labels_group_map');
				}
			}
			
		}
	}	
	fclose($fp_catmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Label Groups Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
