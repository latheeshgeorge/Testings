<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the state is being changed
	$ecom_siteid 	= 61;
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="left"><strong>#</strong></td>
		<td align="left"><strong>Customer Name</strong></td>
		<td align="left"><strong>State Id</strong></td>
		<td align="left"><strong>State Name</strong></td>
	</tr>	
	<?php
	$cnt = 1;
	// Get the list of customers of current site 
	$sql_cust = "SELECT customer_id,customer_fname,customer_surname,customer_statecounty 
					FROM 
						customers 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_cust = $db->query($sql_cust);					
	if($db->num_rows($ret_cust))
	{
		while ($row_cust = $db->fetch_array($ret_cust))
		{
			$cur_state = '';
			if ($row_cust['customer_statecounty'])
			{
				// Get the name of state from general_settings_site_state 
				$sql_state = "SELECT state_name 
								FROM 
									general_settings_site_state 
								WHERE 
									state_id = '".$row_cust['customer_statecounty']."' 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_state = $db->query($sql_state);
				if  ($db->num_rows($ret_state))
				{
					$row_state = $db->fetch_array($ret_state);
					$cur_state = $row_state['state_name'];
				}					
			}
			?>
			<tr>
				<td align="left"><?php echo $cnt++?></td>
				<td align="left"><?php echo stripslashes($row_cust['customer_fname'])." ". stripslashes($row_cust['customer_surname'])?></td>
				<td align="left"><?php echo $row_cust['customer_statecounty']?></td>
				<td align="left"><?php echo $cur_state?></td>
			</tr>	
			<?php
			$update_sql = "UPDATE customers 
							SET 
								customer_statecounty = '".addslashes(stripslashes($cur_state))."' 
							WHERE 
								customer_id = ".$row_cust['customer_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);
		}
	}
?>
<tr>
<td colspan="4" align="center"><strong>-- Completed --</strong></td>
</tr>
</table>