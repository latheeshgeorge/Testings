<?php
	include_once('header.php');
	
	$import_file 		= 'csv/disc_new_data_file.csv';	// Import filename
	
	$i=0;
	
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Pid</td>
	<td align="left">Row</td>
	<td align="left">Product</td>
	<td align="left">Web</td>
	<td align="left">Cost</td>
	<td align="left">Disc</td>
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
	$cnt=1;
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';
			$remove				= trim($data[3]);
			$type				= trim($data[4]);
			$pname				= trim($data[5]);
			$catname			= trim($data[1]);
			$cost				= trim($data[13]);
			$web				= trim($data[14]);
			$disctype			= trim($data[19]);
			$disc				= trim($data[20]);
			if(strtoupper($type)!='NEW' and strtoupper($remove)!='YES')
			{
				$pid 				= str_replace('P-','',trim($type));	
				// Check whether this product exists in database
				$sql_check = "SELECT product_id FROM products WHERE product_id = $pid AND sites_site_id = $siteid LIMIT 1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$newweb				= cutoff($web);
					$newdisc			= $disc;
					$newcost			= cutoff($cost);
					if($disc!='' and $disc!='0.00')
					{
						switch($disctype)
						{
							case 'Value':
							case 'Exact':
								$newdisc = cutoff($disc);
							break;
						};	
					}
					$update_sql = "UPDATE products SET 
										product_webprice = $newweb,
										product_costprice = $newcost,
										product_discount = $newdisc 
									WHERE 
										product_id = $pid  
										AND sites_site_id = $siteid 
									LIMIT 
										1";
					$db->query($update_sql);
					$msg = 'Updated';
					?>
				<tr>
				<td align="left"><?php echo $cnt?></td>	
				<td align="left"><?php echo $type?></td>	
				<td align="left"><?php echo $pid?></td>
				<td align="left"><?php echo $pname?></td>
				<td align="left"><?php echo $newweb?></td>
				<td align="left"><?php echo $newcost?></td>
				<td align="left"><?php echo $disctype.': '.$newdisc?></td>
				<td align="left"><?php echo $msg?></td>
				</tr>
				<?php	
				}
				//else
					//$msg = '<span style="color:#FF0000">Product Does not exists</span>';
					
				$cnt++;
			}
		}	
		$i++;
	}
	fclose($fp);
	function cutoff($vals)
	{
		$str = explode('.',$vals);
		return $str[0];
	}
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Completed Successfully ------</strong></td>
	</tr>
	</table>
