<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Siteid</strong></td>
		<td><strong>Customer name</strong></td>
		<td><strong>Customer Email</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
	<?php
	$cnt =1 ;
	// Get the list of customers in entire site
	$sql_cust = "SELECT customer_id, sites_site_id, customer_title, customer_fname, customer_mname, customer_email_7503,customer_in_mailing_list 
					FROM 
				 		customers 
					ORDER BY 
						sites_site_id";
	$ret_cust = $db->query($sql_cust);
	if ($db->num_rows($ret_cust))
	{
		while ($row_cust = $db->fetch_array($ret_cust))
		{
			$status = '';
			// Check whether there exists an entry in newsletter_customers table with the same email id and site id
			
			$sql_check = "SELECT news_customer_id,customer_id ,sites_site_id 
							FROM 
								newsletter_customers  
							WHERE 
								news_custemail='".addslashes($row_cust['customer_email_7503'])."' 
								AND sites_site_id = ".$row_cust['sites_site_id']." 
							LIMIT 
								2";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				if($db->num_rows($ret_check)==1 and $row_cust['customer_in_mailing_list']!=1)
				{
					$row_check = $db->fetch_array($ret_check);
					$update_customer = "UPDATE 
											customers  
										SET 
											customer_in_mailing_list=1 
										WHERE 
											customer_id=".$row_cust['customer_id']." 
											AND sites_site_id =".$row_cust['sites_site_id']." 
										LIMIT 
											1";
					//$db->query($update_customer);		
					
					$update_news = "UPDATE 
										newsletter_customers 
									SET 
										customer_id = ".$row_cust['customer_id']." 
									WHERE 
										news_customer_id = ".$row_check['news_customer_id']." 
										AND sites_site_id = ".$row_check['sites_site_id']." 
									LIMIT 
										1";
					//$db->query($update_news);
					
					$status = '<span style=\"color="#00FF00"\">Updated</span>';
				}
				elseif($db->num_rows($ret_check)>1)
				{
					$status = '<span style=\"color="#FF0000"\">More than one customer with same email id</span>';
				}	
			}
			if($status!='')
			{
		?>
				<tr>
					<td><?php echo $cnt++?></td>
					<td><?php echo $row_cust['sites_site_id']?></td>
					<td><?php echo $row_cust['customer_title'].' '. $row_cust['customer_fname'].' '. $row_cust['customer_mname']?></td>
					<td><?php echo $row_cust['customer_email_7503']?></td>
					<td><?php echo $status?></td>
				</tr>
		<?php
			}		
		}
	}
?>
<tr>
<td align="center" colspan="5"><strong>--- Completed ---</strong></td>
</tr>
</table>