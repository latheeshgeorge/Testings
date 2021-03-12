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
		<td><strong>Site Id</strong></td>
		<td><strong>Name</strong></td>
		<td><strong>Key</strong></td>
		<td><strong>Cur Org name</strong></td>
		<td><strong>Org name</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
	<?php
	$cnt =1 ;
	// Get the list of entries from general_settings_site_checkoutfields for all sites
	$sql_prod = "SELECT sites_site_id,field_det_id, field_key, field_name, field_orgname 
					FROM 
				 		general_settings_site_checkoutfields 
					WHERE 
						field_type='VOUCHER' ORDER BY sites_site_id";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			// get the value for field_orgname for current key from common_checkoutfields
			$sql_var = "SELECT field_name, field_type, field_orgname 
							FROM 
								common_checkoutfields 
							WHERE 
								field_type = 'VOUCHER' 
								AND field_key ='".$row_prod['field_key']."' 
							LIMIT 
								1";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var))
			{
				$row_var = $db->fetch_array($ret_var);
				if($row_prod['field_orgname']!=$row_var['field_orgname'])
				{
					$sql_update = "UPDATE general_settings_site_checkoutfields 
									SET 
										field_orgname = '".$row_var['field_orgname']."' 
									WHERE 
										field_det_id = ".$row_prod['field_det_id']." 
									LIMIT 
										1";
					$db->query($sql_update);
					$status = 'Changed';
				}	
				else
					$status = 'Correct';
								
			}
		?>
		<tr>
		<td><?php echo $cnt++?></td>
		<td><?php echo $row_prod['sites_site_id']?></td>
		<td><?php echo $row_prod['field_name']?></td>
		<td><?php echo $row_prod['field_key']?></td>
		<td><?php echo $row_prod['field_orgname']?></td>
		<td><?php echo $row_var['field_orgname']?></td>
		<td><?php echo $status?></td>
	</tr>
		<?php		
		}
	}
?>
<tr>
<td align="center" colspan="5"><strong>--- Completed ---</strong></td>
</tr>
</table>