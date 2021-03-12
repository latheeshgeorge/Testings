<?php
	include_once('header.php');
	
	// Get the list of customers existing in the source website
	$sql_custsrc = "SELECT * FROM newsletter_customers WHERE sites_site_id = $src_siteid";
	$ret_custsrc = $db->query($sql_custsrc);
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Name</td>
	<td align="left">Email Id</td>
	<td align="left">Status</td>
	</tr>	
	<?php
	$i = 1;
	if($db->num_rows($ret_custsrc))
	{
		while ($row_custsrc = $db->fetch_array($ret_custsrc))
		{
			$err_msg 				= '';
			$name				= stripslashes($row_custsrc['news_title']).stripslashes($row_custsrc['news_custname']);
			$pid				= stripslashes($row_custsrc['news_custemail']);
			
			
			// Check whether the email id already exists in the website
			$sql_prodloc = "SELECT news_customer_id FROM newsletter_customers WHERE sites_site_id = $des_siteid AND news_custemail='".$row_custsrc['news_custemail']."' LIMIT 1";
			$ret_prodloc = $db->query($sql_prodloc);
			if($db->num_rows($ret_prodloc))
			{
				$status = 'Already Exists';	
			}
			else
			{
				// Check whether this customer exists in customer table, if yes then get the customer id
				$sql_check = "SELECT customer_id FROM customers WHERE sites_site_id = $des_siteid AND customer_email_7503='".$row_custsrc['news_custemail']."' LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$custid = $row_check['customer_id'];
				}	
				else
					$custid = 0;
				$sql_insert = "INSERT INTO newsletter_customers SET 
								sites_site_id=".$des_siteid.",
								news_title='".addslashes(stripcslashes($row_custsrc['news_title']))."',
								news_custname='".addslashes(stripcslashes($row_custsrc['news_custname']))."',
								news_custemail='".addslashes(stripcslashes($row_custsrc['news_custemail']))."',
								news_custphone='".addslashes(stripcslashes($row_custsrc['news_custphone']))."',
								news_join_date='".addslashes(stripcslashes($row_custsrc['news_join_date']))."',
								customer_id='".$custid."',
								news_custhide='".addslashes(stripcslashes($row_custsrc['news_custhide']))."'";
				$db->query($sql_insert);
				$status = 'Done';	
			}	
			?>
			<tr>
			<td align="left"><?php echo $i?></td>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $pid?></td>
			<td align="left"><?php echo $status?></td>
			</tr>		
			<?php
		
			$i++;
		}
	}
	$db->db_close();
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Newsletter Customers Copied Successfully ------</strong></td>
	</tr>
	</table>
