<?php
	include_once('header.php');
	$filename 		= 'csv/update_tracking.csv';
	$chk_variable 	= 'Scooter Starter Pack';
	$old_price_chk 	= 66.99;
	$newvar_price 	= 79.99;
	
	$fp = fopen ($filename,'w');
	fwrite($fp,'"Product Id","Product Name","Var Id","Var Name","Var Value Id","Var Value","Old Price","New Price","Status"'."\n");

	$sql_prods = "SELECT a.product_name,a.product_id,a.product_hide, b.* 
					FROM 
						product_variables b, products a 
					WHERE 
						a.sites_site_id = $siteid  
						AND a.product_id = b.products_product_id 
						AND b.var_name like '%".$chk_variable."%'";
	$ret_prods = $db->query($sql_prods);
?>
<table width="100%" border="1" cellpadding="2" cellspacing="2">
<tr>
	<td>Srno</td>
	<td>Product Id</td>
	<td>Product Name</td>
	<td>Var Id</td>
	<td>Var Name</td>
	<td>Var Value Id</td>
	<td>Var Value</td>
	<td>Old Price</td>
	<td>New Price</td>
	<td>Status</td>
</tr>
<?php
if($db->num_rows($ret_prods))
{
	$srno = 1;
	while ($row_prods = $db->fetch_array($ret_prods))
	{
		$status			= '';
		$product_id 	= $row_prods['product_id'];
		$product_name 	= $row_prods['product_name'];
		$var_id			=  $row_prods['var_id'];
		$var_name		=  $row_prods['var_name'];
		
		/* get the details of variable value */
		$sql_var = "SELECT var_value_id,var_value,product_variables_var_id,var_value,var_addprice 
						FROM 
							product_variable_data 
						WHERE 
							product_variables_var_id = $var_id 
							AND var_addprice > 0";
		$ret_var = $db->query($sql_var);
		if($db->num_rows($ret_var)==1)
		{
			$row_var 		= $db->fetch_array($ret_var);
			$var_value_id 	= $row_var['var_value_id'];
			$var_value 		= $row_var['var_value'];
			$oldvar_price 	= $row_var['var_addprice'];
			
			//echo strtolower($var_name)."==".strtolower($chk_variable)." and ".$oldvar_price."==".$old_price_chk."<br>";
			
			$err = 0;
			//if(strtolower($var_name)==strtolower($chk_variable) and $oldvar_price==$old_price_chk)
			if(strtolower($var_name)==strtolower($chk_variable))
			{
				$sql_update = "UPDATE product_variable_data 
									SET 
										var_addprice = $newvar_price 
									WHERE 
										var_value_id = $var_value_id 
									LIMIT 
										1";
				$db->query($sql_update);						
								
				$status = "-- Updated --";
			}	
			else
			{
				$status = '<span style="color:#FF000;">Var value or price mismatch</span>';
			}
			?>
				<tr>
				<td><?php echo $srno?></td>
				<td><?php echo $product_id?></td>
				<td><?php echo $product_name?></td>
				<td><?php echo $var_id?></td>
				<td><?php echo $var_name?></td>
				<td><?php echo $var_value_id?></td>
				<td><?php echo $var_value?></td>
				<td><?php echo $oldvar_price?></td>
				<td><?php echo $newvar_price?></td>
				<td><?php echo $status?></td>
				</tr>
			<?php	
				fwrite($fp,'"'.$product_id.'","'.$product_name.'","'.$var_id.'","'.$var_name.'","'.$var_value_id.'","'.$var_value.'","'.$oldvar_price.'","'.$newvar_price.'","'.$status.'"'."\n");
				$srno++;
		}
		else
		{
			echo $status = '<br><span style="color:#FF000;">Price set for more than one value</span> '.$product_name.'('.$row_prods['product_hide'].')';
		}
		
		
	}
}
else
{
	echo "Sorry no details found with the given criteria";
}
fclose($fp);
$db->db_close();
?>
<tr>
<td colspan="10">Operation Completed</td>
</tr>
</table>
