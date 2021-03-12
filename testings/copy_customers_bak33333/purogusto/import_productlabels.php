<?php

	include_once('header.php');
	
	$import_labelval_map	= 'map/labelval_map.csv';
	$import_label_map		= 'map/label_map.csv';
	$import_labelgroup_map	= 'map/labelgroup_map.csv';

	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_labelmap = fopen($import_label_map,'w');
		if (!$fp_labelmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
		
		$fp_valmap = fopen($import_labelval_map,'w');
		if (!$fp_valmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
		fwrite($fp_valmap,'Old labelvalue Id,New labelvalue id'."\r\n"); // writing the header row
		
		$fp_groupmap = fopen($import_labelgroup_map,'r');
		if (!$fp_groupmap)
		{
			echo "Cannot open the file map";
			exit;
		}
		$i = 0;
		$row_i = 1;
		$labelgroup_arr = array();
		while (($data = fgetcsv($fp_groupmap, 1000, ",")) !== FALSE)
		{
			if($i!=0)
			{
				$labelgroup_arr[$data[0]] = $data[1];
			}
			$i++;
		}	
		fclose($fp_groupmap);
		
	
	
	// Get the list of all categories from source site
	$sql_cat = "SELECT * FROM product_site_labels WHERE sites_site_id = $src_siteid";
	$ret_cat = $db->query($sql_cat);
		
	fwrite($fp_labelmap,'Old label Id,New label'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_cat))
	{
		while ($row_cat = $db->fetch_array($ret_cat))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['label_name'] 						= addslashes(stripslashes($row_cat['label_name']));
			$insert_array['in_search'] 							= addslashes(stripslashes($row_cat['in_search']));
			$insert_array['is_textbox'] 						= addslashes(stripslashes($row_cat['is_textbox']));
			$insert_array['label_hide'] 						= addslashes(stripslashes($row_cat['label_hide']));
			$insert_array['label_order'] 						= addslashes(stripslashes($row_cat['label_order']));
			$db->insert_from_array($insert_array,'product_site_labels');
			$label_id 	= $db->insert_id();
			$labelid 	= $row_cat['label_id'];
			
			fwrite($fp_labelmap,"$labelid,$label_id"."\r\n"); // writing the header row
			
			// Get the list of label values
			$sql_label = "SELECT * FROM product_site_labels_values WHERE product_site_labels_label_id = $labelid";
			$ret_label = $db->query($sql_label);
			if($db->num_rows($ret_label))
			{
				
				while ($row_label = $db->fetch_array($ret_label))
				{
					$insert_array 										= array();
					$insert_array['product_site_labels_label_id'] 		= $label_id;
					$insert_array['label_value'] 						= addslashes(stripslashes($row_label['label_value']));
					$insert_array['label_value_order'] 					= addslashes(stripslashes($row_label['label_value_order']));
					$db->insert_from_array($insert_array,'product_site_labels_values');
					
					$labelval_id 				= $db->insert_id();
					$labelvalid 				= $row_label['label_value_id'];
					
					fwrite($fp_valmap,"$labelvalid,$labelval_id"."\r\n"); // writing the header row
					
				}
				
			}
			
			// Getting label mapping to label groups
			$sql_labelgroupmap = "SELECT * FROM product_labels_group_label_map WHERE product_site_labels_label_id = $labelid";
			$ret_labelgroupmap = $db->query($sql_labelgroupmap);
			if($db->num_rows($ret_labelgroupmap))
			{
				while ($row_labelgroupmap = $db->fetch_array($ret_labelgroupmap))
				{	
					$groupid 											= $labelgroup_arr[$row_labelgroupmap['product_labels_group_group_id']];
					$insert_array 										= array();
					$insert_array['product_labels_group_group_id'] 		= $groupid;
					$insert_array['product_site_labels_label_id'] 		= $label_id;
					$insert_array['map_order'] 							= addslashes(stripslashes($row_labelgroupmap['map_order']));
					$db->insert_from_array($insert_array,'product_labels_group_label_map');
				}
			}
			
			
			
			
		}
	}	
	fclose($fp_valmap);
	fclose($fp_labelmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Site Labels, Label values and Label to Label group mappings Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
