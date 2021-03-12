<?php
	include_once('header.php');
	
	$import_file 		= 'csv/discount_product_offline_forupload.csv';	// Import filename
	
	$i=0;
	
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="1">
	<tr>
	<td align="left">Name</td>
	<td align="left">Product Id</td>
	<td align="left">Old Bonus Points</td>
	<td align="left">New Bonus Points</td>
	<td align="left">Status</td>
	</tr>	
	<?php
	
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
	
	$atleast_one_err = 0;
	$i =0;
	$previous_product_id = 0;
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';
			$pid				= trim(str_replace('P-','',$data[0]));
			$points				= trim($data[4]);
			$name				= trim($data[1]);
			
			$oldpoints = 0;
			// Check whether the product can be located using the pid
			$sql_prodloc = "SELECT product_id,product_bonuspoints FROM products WHERE sites_site_id = $siteid AND product_id ='".$pid."' LIMIT 1";
			$ret_prodloc = $db->query($sql_prodloc);
			if($db->num_rows($ret_prodloc))
			{
				$row_prodloc = $db->fetch_array($ret_prodloc);
				$oldpoints = (trim($row_prodloc['product_bonuspoints'])!='')?$row_prodloc['product_bonuspoints']:0;
				$update_sql = "UPDATE products SET 
									product_bonuspoints ='". trim($points)."' 
								WHERE 
									product_id = $pid 
									AND sites_site_id = $siteid 
								LIMIT 
									1";
				$db->query($update_sql);
				$status = 'Points Updated ';
			}
			else
			{
				$status = 'Err! - Pid do not Match';	
			}	
			?>
			<tr>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $pid?></td>
			<td align="left"><?php echo $oldpoints?></td>
			<td align="left"><?php echo $points?></td>
			<td align="left"><?php echo $status?></td>
			</tr>		
			<?php
		}	
		$i++;
	}
	fclose($fp);
	$db->db_close();
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Bonus Points Updated Successfully ------</strong></td>
	</tr>
	</table>
