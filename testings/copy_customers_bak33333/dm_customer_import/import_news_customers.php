<?php
	set_time_limit(0);
	include_once "header.php";
	
	$total_inserted = 0;
	$total_existed = 0;
	
	// get the list of newsletter customers from the source website
	
	$sql_news_cust = "SELECT news_title,news_custname,news_custemail 
						FROM 
							newsletter_customers 
						WHERE 
							sites_site_id = $src_siteid 
						ORDER BY 
							news_customer_id";
	$ret_news_cust = $db->query($sql_news_cust);
	if($db->num_rows($ret_news_cust))
	{
		while ($row_news_cust = $db->fetch_array($ret_news_cust))
		{
			$insert_array = array();
			
			
			$insert_array['sites_site_id'] 			= $dest_siteid;
			$insert_array['customer_title'] 		= addslashes(stripslashes(trim($row_news_cust['news_title'])));
			$insert_array['customer_name'] 			= addslashes(stripslashes(trim(str_replace("'","",$row_news_cust['news_custname']))));
			$insert_array['customer_email_7503'] 	= addslashes(stripslashes(trim(str_replace("'","",$row_news_cust['news_custemail']))));
			$chk_email = trim(str_replace("'","",$row_news_cust['news_custemail']));
			
			
			// Check whether email id already exists in the imported table
			$sql_chk = "SELECT imported_id FROM imported_customers WHERE sites_site_id = $dest_siteid AND customer_email_7503 ='".$chk_email."' LIMIT 1";
			$ret_chk = $db->query($sql_chk);				
			if ($db->num_rows($ret_chk)==0)
			{
				$db->insert_from_array($insert_array,'imported_customers');
				$total_inserted++;
			}
			else
			{
				$total_existed++;
				echo "<br> $chk_email already exists in the table";
			}	
			
				
		}
		echo "Total Inserted: ".$total_inserted;
		echo "<br><br>Total Existed: ".$total_existed;
	}
	else
	{
		echo "Sorry!!. No newsletter customers found in source website for importing";
	}
?>
