<?php

	include_once('header.php');
	
	// read the content of csv file
	$import_vendor_map	= 'map/vendor_map.csv';
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	$fp_vendormap = fopen($import_vendor_map,'w');
	if (!$fp_vendormap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	// Get the list of all product label groups from source site
	$sql_grp = "SELECT * FROM product_vendors WHERE sites_site_id = $src_siteid";
	$ret_grp = $db->query($sql_grp);
	
	fwrite($fp_vendormap,'Old vendorid Id,New vendor Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_grp))
	{
		while ($row_grp = $db->fetch_array($ret_grp))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['vendor_name'] 						= addslashes(stripslashes($row_grp['vendor_name']));
			$insert_array['vendor_address'] 					= addslashes(stripslashes($row_grp['vendor_address']));
			$insert_array['vendor_telephone'] 					= addslashes(stripslashes($row_grp['vendor_telephone']));
			$insert_array['vendor_fax'] 						= addslashes(stripslashes($row_grp['vendor_fax']));
			$insert_array['vendor_email'] 						= addslashes(stripslashes($row_grp['vendor_email']));
			$insert_array['vendor_website'] 					= addslashes(stripslashes($row_grp['vendor_website']));
			$insert_array['vendor_hide'] 						= addslashes(stripslashes($row_grp['vendor_hide']));
			
		
			$db->insert_from_array($insert_array,'product_vendors');
			$vendor_id 	= $db->insert_id();
			$vendorid 	= $row_grp['vendor_id'];
			
			fwrite($fp_vendormap,"$vendorid,$vendor_id"."\r\n"); // writing the header row
			
			// Get the label group to category mapping from source website
			
			$sql_grpmap = "SELECT * FROM product_vendor_contacts WHERE product_vendors_vendor_id = $vendorid";
			$ret_grpmap = $db->query($sql_grpmap);
			if($db->num_rows($ret_grpmap))
			{
				while ($row_grpmap = $db->fetch_array($ret_grpmap))
				{
					$insert_array 								= array();
					$insert_array['product_vendors_vendor_id'] 	= $vendor_id;
					$insert_array['contact_name'] 				= addslashes(stripslashes($row_grpmap['contact_name']));
					$insert_array['contact_address'] 			= addslashes(stripslashes($row_grpmap['contact_address']));
					$insert_array['contact_phone'] 				= addslashes(stripslashes($row_grpmap['contact_phone']));
					$insert_array['contact_fax'] 				= addslashes(stripslashes($row_grpmap['contact_fax']));
					$insert_array['contact_email'] 				= addslashes(stripslashes($row_grpmap['contact_email']));
					$insert_array['contact_mobile'] 			= addslashes(stripslashes($row_grpmap['contact_mobile']));
					$insert_array['contact_position'] 			= addslashes(stripslashes($row_grpmap['contact_position']));
					$insert_array['contact_sortorder'] 			= addslashes(stripslashes($row_grpmap['contact_sortorder']));
					$db->insert_from_array($insert_array,'product_vendor_contacts');
				}
			}
			
		}
	}	
	fclose($fp_vendormap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Product Vendors and vendor contacts Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
