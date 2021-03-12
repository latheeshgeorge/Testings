<?php

	include_once('header.php');
	
	
	$import_preset_map			= 'map/presetvar_map.csv';
	$import_presetvalue_map		= 'map/presetvarval_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_presetmap = fopen($import_preset_map,'w');
		if (!$fp_presetmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
		
		$fp_presetvalmap = fopen($import_presetvalue_map,'w');
		if (!$fp_presetvalmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all categories from source site
	$sql_preset = "SELECT * FROM product_preset_variables WHERE sites_site_id = $src_siteid";
	$ret_preset = $db->query($sql_preset);
	
	
	
	
		
	fwrite($fp_presetmap,'Old Preset id Id,New Preset Id'."\r\n"); // writing the header row
	fwrite($fp_presetvalmap,'Old Presetval id Id,New Presetval Id'."\r\n"); // writing the header row
	
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_preset))
	{
		while ($row_preset = $db->fetch_array($ret_preset))
		{
			$err_msg 											= '';
			
			$insert_array 						= array();
			$insert_array['sites_site_id'] 		= $dest_siteid;
			$insert_array['var_name'] 			= addslashes(stripslashes($row_preset['var_name']));
			$insert_array['var_order'] 			= addslashes(stripslashes($row_preset['var_order']));
			$insert_array['var_hide'] 			= addslashes(stripslashes($row_preset['var_hide']));
			$insert_array['var_value_exists'] 	= addslashes(stripslashes($row_preset['var_value_exists']));
			$insert_array['var_price'] 			= addslashes(stripslashes($row_preset['var_price']));
						
			$db->insert_from_array($insert_array,'product_preset_variables');
			$preset_id 	= $db->insert_id();
			
			$presetid 	= $row_preset['var_id'];
			
			fwrite($fp_presetmap,"$presetid,$preset_id"."\r\n"); // writing the header row
			
			if($row_preset['var_value_exists']==1)
			{
				// Get the values of variables
				$sql_val = "SELECT * FROM product_preset_variable_data WHERE product_variables_var_id = $presetid and sites_site_id = $src_siteid";
				$ret_val = $db->query($sql_val);
				if($db->num_rows($ret_val))
				{
					while ($row_val = $db->fetch_array($ret_val))
					{
						$insert_array 								= array();
						$insert_array['sites_site_id'] 				= $dest_siteid;
						$insert_array['product_variables_var_id'] 	= $preset_id;
						$insert_array['var_value'] 					= addslashes(stripslashes($row_val['var_value']));
						$insert_array['var_addprice'] 	= addslashes(stripslashes($row_val['var_addprice']));
						$insert_array['var_order'] 	= addslashes(stripslashes($row_val['var_order']));
						$insert_array['var_code'] 	= addslashes(stripslashes($row_val['var_code']));
						$db->insert_from_array($insert_array,'product_preset_variable_data');
						
						$presetval_id 	= $db->insert_id();
			
						$presetvalid 	= $row_val['var_value_id'];
						fwrite($fp_presetvalmap,"$presetvalid,$presetval_id"."\r\n"); // writing the header row
					}
				}
			}
			
			
			
		}
	}	
	fclose($fp_presetmap);
	fclose($fp_presetvalmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Preset variables and its values Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
