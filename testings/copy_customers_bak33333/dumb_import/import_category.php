<?php
	include_once('header.php');
	$export_file = 'category_relations.csv';
	$fp1 = fopen($export_file, 'w');
	fwrite($fp1, "category_id,old_category_id,image\r\n");

	$import_file 			= 'csv/categories.csv';	// Import filename
	$i=0;
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	
	<?php
	
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
	
	$atleast_one_err = 0;
	$i =0;
	
	while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';
			
			$categories_id 			= trim($data[0]);
			$categories_image 		= trim($data[1]);
			$parent_id 				= trim($data[2]);
			$categories_name 		= trim($data[3]);
			$categories_seo_url 	= trim($data[4]);
			
			
			$exist = 0;
			$insert_array									= array();
			$insert_array['sites_site_id']					= $siteid;
			$insert_array['category_name']					= addslashes(stripslashes($categories_name));
			//$insert_array['category_shortdescription']		= addslashes(stripslashes($Description));
			//$insert_array['category_paid_description']		= addslashes(stripslashes($InCategoryDescription));
			$insert_array['category_hide']					= 0;
			
			
			
			$insert_array['parent_id']						= 0;
			// Check whether there exists a category with the name Imported
			$sql_cat = "SELECT category_id 
			FROM 
				product_categories 
			WHERE 
				sites_site_id = $siteid 
				AND LOWER(category_name) = '".strtolower(addslashes(stripslashes($categories_name)))."' 
			AND parent_id = 0 LIMIT 
				1";
			$ret_cat = $db->query($sql_cat);
			if($db->num_rows($ret_cat))
			{
				$exist = 1;
			}
	
			if($exist == 1)
			{
				?>
				<tr>
					<td colspan="3" align="left" style="color:#fe0103"><strong><?php echo '"'.stripslashes($categories_name).'" is already exists..'; ?></strong></td>
				</tr>
			  <?php	
				
			}
			else
			{
			
				$db->insert_from_array($insert_array,'product_categories');
				$category_id = $db->insert_id();
								
				fwrite($fp1, $category_id.",".$categories_id.",".$categories_image."\r\n");
				
				//unset ($insert_array);
				//unset ($cat_id);
				
				
			}	
			
			
		}
		
		
		
		$i++;
	}
	fclose($fp);
	fclose($fp1);
	?>
	<tr>
		<td colspan="3" align="center" style="color:#006600"><strong>----- Catagories Imported Successfully ------</strong></td>
	</tr>
	</table>
	
