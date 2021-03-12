<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the domain name is being changed
	$sql_site = "SELECT site_id,site_domain FROM sites";
	$ret_site = $db->query($sql_site);
	if($db->num_rows($ret_site))
	{
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		while($row_site = $db->fetch_array($ret_site))
		{
			$site_id = $row_site['site_id'];
			$sql_prod = "SELECT product_id 
							FROM 
								products  
							WHERE 
								sites_site_id = $site_id";
			$ret_prod = $db->query($sql_prod);
	?>
	
	<tr>
		<td align="left" width="60%"><strong><?php echo $row_site['site_domain']?></strong></td>
	<?php
			if($db->num_rows($ret_prod))
			{
				$cnt = 1;
				while ($row_prod = $db->fetch_array($ret_prod))
				{
					handle_default_comp_price_and_id($row_prod['product_id'],$site_id);
				}
			}
	?>
	<td align="left">Done</td>
	</tr>
	<?php
		}
	}	
	?>
<tr>
<td colspan="2" align="center"><strong>-- Operation completed --</strong></td>
</tr>																
</table>
<?php
// ** Function to the combination Id if available
function get_combination_id($site_id,$prodid,$var_arr=array())
{
	global $db;
	$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
						product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,product_variablecombocommon_image_allowed 
					FROM
						products
					WHERE
						product_id=$prodid
						AND sites_site_id = $site_id
					LIMIT
						1";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);
		
		if (count($var_arr)==0 and ($row_prod['product_variablestock_allowed']=='Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y')) // case if variable combination price is allowed and also if var arr is null
		{
			$sql_var = "SELECT var_id,var_name  
							FROM 
								product_variables 
							WHERE 
								products_product_id = ".$prodid." 
								AND var_hide= 0 
								AND var_value_exists = 1 
							ORDER BY 
								var_order";
			$ret_var = $db->query($sql_var);
			if($db->num_rows($ret_var))
			{
				while ($row_var = $db->fetch_array($ret_var))
				{
					$curvar_id= $row_var['var_id'];
					// Get the value id of first value for this variable
					$sql_datas = "SELECT var_value_id 
											FROM 
												product_variable_data 
											WHERE 
												product_variables_var_id = ".$curvar_id." 
											ORDER BY var_order  
											LIMIT 
												1";
					$ret_datas = $db->query($sql_datas);
					if ($db->num_rows($ret_datas))
					{
						$row_data = $db->fetch_array($ret_datas);
					}							
					$var_arr[$curvar_id] = $row_data['var_value_id'];
				}
			}
		}
		
		
		foreach ($var_arr as $k=>$v)
		{
			// Check whether the variable is a check box or a drop down box
			$sql_check = "SELECT var_id
							FROM
								product_variables
							WHERE
								var_id=$k
								AND var_value_exists = 1
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$row_check 	= $db->fetch_array($ret_check);
				$varids[] 	= $k; // populate only the id's of variables which have values to the array
			}
		}
		if (count($varids))
		{
			if ($row_prod['product_variablestock_allowed'] == 'Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y') // Case if variable stock is maintained
			{
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id 
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{
	
						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}
							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
				else // case if variable stock is not maintained
				{
					$ret_data['combid']	= 0;
					return $ret_data; // return from function as soon as the combination found
				}
			}	
		}
	$ret_data['combid']			= 0;
	return $ret_data; // return from function as soon as the combination found
}
function handle_default_comp_price_and_id($prodid,$site_id)
{
	global $db;
	// Check whether product_variablecomboprice_allowed option is active for current product
		$sql_check = "SELECT product_variablestock_allowed, product_variablecomboprice_allowed, product_variablecombocommon_image_allowed  
								FROM 
									products 
								WHERE 
									product_id = ".$prodid." 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$row_check = $db->fetch_array($ret_check);
		}									
		if($row_check['product_variablestock_allowed']=='Y' or $row_check['product_variablecomboprice_allowed']=='Y' or $row_check['product_variablecombocommon_image_allowed']=='Y') // do the following only if product_variablecomboprice_allowed is set to Y
		{
			// Get the combination id of first combination for thie product
			$comb_arr = get_combination_id($site_id,$prodid);
			if($comb_arr['combid'])
			{
					// Updating the product_webprice field in products table with this value
					$update_sql = "UPDATE products 
												SET 
													default_comb_id = ".$comb_arr['combid']." 
												WHERE 
													product_id = ".$prodid."
													AND sites_site_id =$site_id 
												LIMIT 
													1";
					$db->query($update_sql);									
			}
			else
			{
				$update_sql = "UPDATE products 
											SET 
												default_comb_id = 0 
											WHERE 
												product_id = $prodid 
												AND sites_site_id = $site_id 
											LIMIT 
												1";
				$db->query($update_sql);						
			}
		}
}
?>