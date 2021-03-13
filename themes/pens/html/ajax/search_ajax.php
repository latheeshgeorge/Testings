<?php
function show_characteristics_value($var_name)
{
	global $db,$ecom_siteid;
	$prod_sql = "SELECT product_id FROM products WHERE sites_site_id=$ecom_siteid AND product_hide='N'";
	$ret_prod= $db->query($prod_sql);
	while($row_prod= $db->fetch_array($ret_prod))
	{
		$prod_ids[]=$row_prod['product_id']; 
	}
	if(count($prod_ids))
		$prod_str = implode(",",$prod_ids);
	else
		$prod_str ='-1';
	$AdvSearchVariablesOptions = "SELECT DISTINCT var_value 
													FROM 
														product_variable_data ,
														product_variables 
													WHERE 
														product_variables.var_id=product_variable_data.product_variables_var_id 
														AND product_variables.products_product_id IN ($prod_str) 
														AND product_variables.var_name='".addslashes($var_name)."' 
													ORDER BY 
														var_value;";
	$rstAdvSearchVariablesOptions = $db->query($AdvSearchVariablesOptions);
	?>
	<select name="searchVariableOption" id="searchVariableOption">
	<option value=" ">Of any type</option>
	<?php
	if ($db->num_rows($rstAdvSearchVariablesOptions))
	{
		while ($rowAdvSearch = $db->fetch_array($rstAdvSearchVariablesOptions))
		{
	?>
			<option value="<?php echo stripslash_normal($rowAdvSearch['var_value'])?>"><?php echo stripslash_normal($rowAdvSearch['var_value'])?></option>
	<?php		
		}
	}
	?>
	</select>
	<?php
}

?>