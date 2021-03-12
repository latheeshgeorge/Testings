<?php

	include_once('header.php');
	
	
	$import_review_map			= 'map/sitereview_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_reviewmap = fopen($import_review_map,'w');
		if (!$fp_reviewmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all categories from source site
	$sql_review = "SELECT * FROM sites_reviews WHERE sites_site_id = $src_siteid";
	$ret_review = $db->query($sql_review);
	
	
	
	
		
	fwrite($fp_reviewmap,'Old Review Id,New Review Id'."\r\n"); // writing the header row
	
	// Get the id of the System admin in the website
	$sql_user = "SELECT user_id FROM sites_users_7584 WHERE sites_site_id = $dest_siteid AND user_type = 'sa' and user_active = 1 and default_user=1";
	$ret_user = $db->query($sql_user);
	$admin_id = 0;
	if($db->num_rows($ret_user))
	{
		$row_user = $db->fetch_array($ret_user);
		$admin_id = $row_user['user_id'];
	}
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_review))
	{
		while ($row_review = $db->fetch_array($ret_review))
		{
			$err_msg 											= '';
			
			$insert_array 							= array();
			$insert_array['sites_site_id'] 			= $dest_siteid;
			$insert_array['review_date'] 			= addslashes(stripslashes($row_review['review_date']));
			$insert_array['review_author'] 			= addslashes(stripslashes($row_review['review_author']));
			$insert_array['review_rating'] 			= addslashes(stripslashes($row_review['review_rating']));
			$insert_array['review_details'] 		= addslashes(stripslashes($row_review['review_details']));
			$insert_array['review_author_email'] 	= addslashes(stripslashes($row_review['review_author_email']));
			$insert_array['review_approved_by'] 	= ($row_review['review_approved_by'])?$admin_id:0;
			$insert_array['review_status'] 			= addslashes(stripslashes($row_review['review_status']));
			$insert_array['review_hide'] 			= addslashes(stripslashes($row_review['review_hide']));
		
						
			$db->insert_from_array($insert_array,'sites_reviews');
			$review_id 	= $db->insert_id();
			
			$reviewid 	= $row_review['review_id'];
			
			fwrite($fp_reviewmap,"$reviewid,$review_id"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_reviewmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Site Reviews Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
