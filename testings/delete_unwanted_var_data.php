<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the domain name is being changed
	
	$sql_var_data = "SELECT var_value_id, product_variables_var_id, var_value, var_addprice, var_order, var_code 
								FROM 
									product_variable_data 
								ORDER BY var_value_id ";
	$ret_var_data = $db->query($sql_var_data);
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Value Id </strong></td>
		<td><strong>Value </strong></td>
		<td><strong>Status</strong></td>
	</tr>	
	<?php
	$cnt =1 ;
	if ($db->num_rows($ret_var_data))
	{
		while ($row_var_data = $db->fetch_array($ret_var_data))
		{
			// Check whether current variable exists in database 
			$sql_var = "SELECT var_id,var_name  
							FROM 
								product_variables 
							WHERE 
								var_id = ".$row_var_data['product_variables_var_id']."
							LIMIT 
								1";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var)==0)
			{
		?>
		<tr>
		<td><?php echo $cnt++?></td>
		<td><?php echo $row_var_data['var_value_id']?></td>
		<td><?php echo $row_var_data['var_value']?></td>
		<td>Does not exists</td>
		</tr>
		<?php	

			}
		?>
		<?php		
		}
	}
?>
<tr>
<td align="center" colspan="4"><strong>--- Completed ---</strong></td>
</tr>
</table>