<?php
	include_once('header.php'); 
	
	$sql_prod = "SELECT product_id,product_name FROM products WHERE sites_site_id = $siteid AND product_variables_exists = 'Y'";
	$ret_prod = $db->query($sql_prod);
	
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="center" style="color:#006600"><strong>#</strong></td>
		<td align="center" style="color:#006600"><strong>Product Id</strong></td>
		<td align="left" style="color:#006600"><strong>Product</strong></td>
	</tr>
	<?php
	$cnt=1;
	while ($row_prod = $db->fetch_array($ret_prod))
	{	
		// Check whether there exists combination for this product in combination table
		$sql_comb = "SELECT comb_id FROM product_variable_combination_stock WHERE products_product_id = ".$row_prod['product_id']." LIMIT 1"	;
		$ret_comb = $db->query($sql_comb);
		if($db->num_rows($ret_comb)==0)
		{
			?>
			<tr>
				<td align="center" style="color:#006600"><strong><?php echo $cnt;$cnt++;?></strong></td>
				<td align="center" style="color:#006600"><strong><?php echo $row_prod['product_id']?></strong></td>
				<td align="left" style="color:#006600"><strong><?php echo $row_prod['product_name']?></strong></td>
			</tr>
			<?php
		}
	}
	?>
	</table>