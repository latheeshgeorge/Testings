<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the domain name is being changed
	$ecom_siteid 	= 61;
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Product name</strong></td>
		<td><strong>Status</strong></td>
	</tr>	
	<?php
	$cnt =1 ;
	// Get the list of products in current site 
	$sql_prod = "SELECT product_id,product_name  
					FROM 
				 		products 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			// Check whether current product have variables 
			$sql_var = "SELECT var_id 
							FROM 
								product_variables 
							WHERE 
								products_product_id = ".$row_prod['product_id']."
							LIMIT 
								1";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var))
			{
				$var_exists = 'Y';
				$status = 'Var Exists';
				$sql_price = "SELECT a.var_id 
											FROM 
												product_variables a LEFT JOIN product_variable_data b  
												ON (a.var_id=b.product_variables_var_id)
											WHERE 
												a.products_product_id = ".$row_prod['product_id']."  
												AND a.var_hide=0 
												AND (b.var_addprice>0  OR a.var_price>0)
											LIMIT 1";
					$ret_price = $db->query($sql_price);
					if($db->num_rows($ret_price))
					{
						$var_price 	= 'Y';
						$status 	.= '.. Add price Exists';		
					}	
					else
					{
						$var_price	= 'N';
						$status 	.= '.. No Add price';
					}	
				$update_product = "UPDATE 
									products 
										SET 
											product_variables_exists = '".$var_exists."',
											product_variablesaddonprice_exists = '".$var_price."' 
										WHERE 
											product_id = ".$row_prod['product_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
				$db->query($update_product);		
								
			}
			else
			{
				$status = 'No Var Exists';
			}
		?>
		<tr>
		<td><?php echo $cnt++?></td>
		<td><?php echo $row_prod['product_name']?></td>
		<td><?php echo $status?></td>
	</tr>
		<?php		
		}
	}
?>
<tr>
<td align="center" colspan="3"><strong>--- Completed ---</strong></td>
</tr>
</table>