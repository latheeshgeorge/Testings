<?php
	include_once("../functions/functions.php");
	require_once("../config.php");
	$customer_list = 'garr_csv/unsubscribe_clean.csv';
	$fp_feat = fopen($customer_list,'r');
	if (!$fp_feat)
	{
		echo "Cannot open the file product";
		exit;
	}

?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="center" style="color:#006600"><strong>#</strong></td>
		<td align="center" style="color:#006600"><strong>Customer Email</strong></td>
		<td align="left" style="color:#006600"><strong>Status</strong></td>
	</tr>
<?php
	
	$cnt = 1;
	$i = 0;
	$row_i = 1;
	$prev_prodid = 0;
	while (($data = fgetcsv($fp_feat, 1000, ",")) !== FALSE)
	{
		$emailid = trim($data[0]);
		$status = '';
		// Check whether email id exists in customers table
		$sql_cust = "SELECT customer_id 
						FROM 
							customers 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND customer_email_7503 ='".$emailid."' 
						LIMIT 
							1";
		$ret_cust = $db->query($sql_cust);

		if($db->num_rows($ret_cust))
		{
			$row_cust = $db->fetch_array($ret_cust);
			$del_sql = "DELETE FROM customers WHERE customer_id = ".$row_cust['customer_id']." AND sites_site_id = $ecom_siteid LIMIT 1";
			$db->query($del_sql);
			$status = 'Registered Customer Deleted';	
		}
		else
		{
			$status = 'Not found in registered customer table';
		}	
		// Check whether email id exists in newsletter_customers  table
		$sql_cust = "SELECT news_customer_id 
						FROM 
							newsletter_customers 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND news_custemail ='".$emailid."' 
						LIMIT 
							1";
		$ret_cust = $db->query($sql_cust);
		if($db->num_rows($ret_cust))
		{
			$row_cust = $db->fetch_array($ret_cust);
			$del_sql = "DELETE FROM newsletter_customers WHERE news_customer_id = ".$row_cust['news_customer_id']." AND sites_site_id = $ecom_siteid LIMIT 1";
			$db->query($del_sql);
			$status .= '<br>Newsletter Customer Deleted';	
		}
		else
		{
			$status .= '<br>Not found in Newsletter Customer Table';
		}
		?>
		<tr>
		<td align="center" style="background-color:#E5E5E5"><?php echo $cnt;$cnt++;?></td>
		<td align="center" style="background-color:#E5E5E5"><?php echo $emailid?></td>
		<td align="left" style="background-color:#E5E5E5"><?php echo $status?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
?>
