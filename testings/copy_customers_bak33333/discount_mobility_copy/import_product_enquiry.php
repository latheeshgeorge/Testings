<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	$product_varmap		= 'map/product_variable_map.csv';
	$product_varvalmap	= 'map/product_variablevalue_map.csv';
	
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
	
	$fp_prodvarmap = fopen($product_varmap,'r');
	if (!$fp_prodvarmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$prodvar_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodvarmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$prodvar_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodvarmap);
	
	$fp_prodvarvalmap = fopen($product_varvalmap,'r');
	if (!$fp_prodvarvalmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$prodvarval_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodvarvalmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$prodvarval_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodvarvalmap);
	
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$sql_enq = "SELECT * FROM product_enquiries WHERE sites_site_id = $src_siteid";
		$ret_eng = $db->query($sql_enq);
		if($db->num_rows($ret_eng))
		{
			while ($row_enq = $db->fetch_array($ret_eng))
			{
				$insert_array 							= array();
				$insert_array['sites_site_id'] 			= $dest_siteid;
				$insert_array['enquiry_date'] 			= addslashes(stripslashes($row_enq['enquiry_date']));
				$insert_array['customers_customer_id'] 	= 0;
				$insert_array['enquiry_title'] 			= addslashes(stripslashes($row_enq['enquiry_title']));
				$insert_array['enquiry_fname'] 			= addslashes(stripslashes($row_enq['enquiry_fname']));
				$insert_array['enquiry_middlename'] 	= addslashes(stripslashes($row_enq['enquiry_middlename']));
				$insert_array['enquiry_lastname'] 		= addslashes(stripslashes($row_enq['enquiry_lastname']));
				$insert_array['enqiury_address'] 		= addslashes(stripslashes($row_enq['enqiury_address']));
				$insert_array['enquiry_postcode'] 		= addslashes(stripslashes($row_enq['enquiry_postcode']));
				$insert_array['enquiry_email'] 			= addslashes(stripslashes($row_enq['enquiry_email']));
				$insert_array['enquiry_phone'] 			= addslashes(stripslashes($row_enq['enquiry_phone']));
				$insert_array['enquiry_fax'] 			= addslashes(stripslashes($row_enq['enquiry_fax']));
				$insert_array['enquiry_mobile'] 		= addslashes(stripslashes($row_enq['enquiry_mobile']));
				$insert_array['site_state_state_id'] 	= 0;
				$insert_array['site_country_country_id']= 0;
				$insert_array['enquiry_status'] 		= addslashes(stripslashes($row_enq['enquiry_status']));
				$insert_array['enquiry_text'] 			= addslashes(stripslashes($row_enq['enquiry_text']));
				$insert_array['enquiry_hidden'] 		= addslashes(stripslashes($row_enq['enquiry_hidden']));
				$db->insert_from_array($insert_array,'product_enquiries');
				
				$enq_id = $db->insert_id();
				$enqid 	= $row_enq['enquiry_id'];
				
				$sql_enqdata = "SELECT * FROM product_enquiry_data WHERE product_enquiries_enquiry_id = $enqid";
				$ret_enqdata = $db->query($sql_enqdata);
				if($db->num_rows($ret_enqdata))
				{
					while ($row_enqdata = $db->fetch_array($ret_enqdata))
					{
						$insert_array 									= array();
						$insert_array['product_enquiries_enquiry_id'] 	= $enq_id;
						$insert_array['products_product_id'] 			= $prod_arr[$row_enqdata['products_product_id']];
						$insert_array['product_text'] 					= addslashes(stripslashes($row_enqdata['product_text']));
						$db->insert_from_array($insert_array,'product_enquiry_data');
						$data_id = $db->insert_id();
						$dataid = $row_enqdata['id'];
						
						// Get the variables for the enquiry data
						$sql_datavar = "SELECT * FROM  product_enquiry_data_vars WHERE product_enquiry_data_id = $dataid";
						$ret_datavar = $db->query($sql_datavar);
						if($db->num_rows($ret_datavar))
						{
							while ($row_datavar = $db->fetch_array($ret_datavar))
							{
								$insert_array 								= array();
								$insert_array['product_enquiry_data_id'] 	= $data_id;
								$insert_array['variable_name'] 				= addslashes(stripslashes($row_datavar['variable_name']));
								$insert_array['variable_value'] 			= addslashes(stripslashes($row_datavar['variable_value']));
								$db->insert_from_array($insert_array,'product_enquiry_data_vars');
							}
						}
						
					}
				}
				
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Product Enquiry Details Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
