<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of newsletter group to which the customer to be mapped
	$map_group_id 	= 25078;
	$ecom_siteid 	= 65;
	// Get the list of newsletter customers for current site
	$sql_cust = "SELECT news_customer_id, sites_site_id, customer_id 
					FROM 
						newsletter_customers 
					WHERE 
						sites_site_id = $ecom_siteid ";
	$ret_cust = $db->query($sql_cust);
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Customer id</td>
	<td align="left">Site Id</td>
	<td align="left">Status</td>
	</tr>
	<?php
	if($db->num_rows($ret_cust))
	{
		$cnt = 1;
		while ($row_cust = $db->fetch_array($ret_cust))
		{
			$news_cust_id = $row_cust['news_customer_id'];
			// Check whether current customer is mapped with the given newsletter groups
			$sql_check = "SELECT map_id 
							FROM 
								customer_newsletter_group_customers_map 
							WHERE 
								customer_id = $news_cust_id 
								AND custgroup_id = $map_group_id 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0) // if no mapping exists
			{
				$sql_insert = "INSERT INTO 
									customer_newsletter_group_customers_map 
								SET 
									custgroup_id = $map_group_id,
									customer_id = $news_cust_id";
				$db->query($sql_insert);
				$stat = 'Mapped';
			}
			else
				$stat = ' Already Mapped '
		?>
		<tr>
			<td align="left"><?php echo $cnt++?></td>
			<td align="left"><?php echo $row_cust['news_customer_id']?></td>
			<td align="left"><?php echo $row_cust['sites_site_id']?></td>
			<td align="left"><?php echo $stat?></td>
		</tr>
		<?php		
		}
	}
?>	

<tr>
<td colspan="4" align="center">-- done --</td>
</tr>
</table>
