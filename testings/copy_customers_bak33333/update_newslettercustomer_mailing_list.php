<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$siteid = 83; // destination site id
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
	// Get the list of all newsletter customers in entire sites
	$sql_cust = "SELECT news_customer_id, sites_site_id, news_title, news_custname, news_custemail, customer_id 
					FROM 
				 		newsletter_customers  
					WHERE 
						sites_site_id = $siteid 
					ORDER BY 
						sites_site_id";
	$ret_cust = $db->query($sql_cust);
	if ($db->num_rows($ret_cust))
	{
		while ($row_cust = $db->fetch_array($ret_cust))
		{
			$status = '';
			// Check whether there exists an entry in cusstomers table with the same email id and site id
			$sql_check = "SELECT customer_id ,customer_in_mailing_list,sites_site_id  
							FROM 
								customers  
							WHERE 
								customer_email_7503='".addslashes($row_cust['news_custemail'])."' 
								AND sites_site_id = ".$row_cust['sites_site_id']." 
							LIMIT 
								2";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				if($db->num_rows($ret_check)==1)
				{
					$row_check = $db->fetch_array($ret_check);
					if($row_check['customer_in_mailing_list']==0)
					{
						$update_customer = "UPDATE 
												customers  
											SET 
												customer_in_mailing_list=1 
											WHERE 
												customer_id=".$row_check['customer_id']." 
												AND sites_site_id =".$row_check['sites_site_id']." 
											LIMIT 
												1";
						$db->query($update_customer);		

						//Updated the newsletter_customers table with the current customer id
						$update_news = "UPDATE 
											newsletter_customers 
										SET 
											customer_id= ".$row_check['customer_id']." 
										WHERE 
											news_customer_id = ".$row_cust['news_customer_id']." 
											AND sites_site_id = ".$row_check['sites_site_id']." 
										LIMIT 
											1";
						$db->query($update_news);
						$status = '<span style=\"color="#00FF00"\">Updated</span>';
					}	
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
					<td><?php echo $row_cust['news_title'].' '. $row_cust['news_custname']?></td>
					<td><?php echo $row_cust['news_custemail']?></td>
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