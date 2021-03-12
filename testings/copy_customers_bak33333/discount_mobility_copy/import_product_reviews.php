<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	
	
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
		
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		// Get the id of the System admin in the website
		$sql_user = "SELECT user_id FROM sites_users_7584 WHERE sites_site_id = $dest_siteid AND user_type = 'sa' and user_active = 1 and default_user=1";
		$ret_user = $db->query($sql_user);
		$admin_id = 0;
		if($db->num_rows($ret_user))
		{
			$row_user = $db->fetch_array($ret_user);
			$admin_id = $row_user['user_id'];
		}
	
		$sql_rev = "SELECT * FROM product_reviews WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 							= array();
				$insert_array['sites_site_id'] 			= $dest_siteid;
				$insert_array['products_product_id'] 	= $prod_arr[$row_rev['products_product_id']];
				$insert_array['review_date'] 			= addslashes(stripslashes($row_rev['review_date']));
				$insert_array['review_author'] 			= addslashes(stripslashes($row_rev['review_author']));
				$insert_array['review_author_email'] 	= addslashes(stripslashes($row_rev['review_author_email']));
				$insert_array['review_details'] 		= addslashes(stripslashes($row_rev['review_details']));
				$insert_array['review_rating'] 			= addslashes(stripslashes($row_rev['review_rating']));
				$insert_array['review_status'] 			= addslashes(stripslashes($row_rev['review_status']));
				$insert_array['review_approved_by'] 	= ($row_rev['review_approved_by'])?$admin_id:0;
				$insert_array['review_hide'] 			= addslashes(stripslashes($row_rev['review_hide']));
				$db->insert_from_array($insert_array,'product_reviews');
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Product Reviews Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
