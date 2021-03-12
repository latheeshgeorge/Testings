<?php
	include_once('header.php');
	
	$import_file 		= 'csv/bshop4.discount.csv';	// Import filename
	//$import_file 		= 'csv/product_offline_local.csv';	// Import filename
	
	
	$i=0;
	
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">Id</td>
	<td align="left">Name</td>
	<td align="left">Old Web</td>
	<td align="left">New Web</td>
	<td align="left">Disc type</td>
	<td align="left">Old Disc</td>
	<td align="left">New Disc</td>
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
			$id 				= str_replace('P-','',trim($data[0]));
			$name				= trim($data[1]);
			$cost				= trim($data[2]);
			$web				= trim($data[3]);
			$disctype			= trim($data[4]);
			$disc				= trim($data[5]);
			$newweb				= cutoff($web);
			$newdisc			= $disc;
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
								product_discount = $newdisc 
							WHERE 
								product_id = $id 
								AND sites_site_id = $siteid 
							LIMIT 
								1";
			$db->query($update_sql);
			
			/*$update_sql = "UPDATE products SET 
								product_webprice = $newweb 
							WHERE 
								product_id = $id 
								AND sites_site_id = $siteid 
							LIMIT 
								1";
			$db->query($update_sql);*/
			?>
			<tr>
			<td align="left"><?php echo $id?></td>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $web?></td>
			<td align="left"><?php echo $newweb?></td>
			<td align="left"><?php echo $disctype?></td>
			<td align="left"><?php echo $disc?></td>
			<td align="left"><?php echo $newdisc?></td>
			</tr>		
			<?php
		}	
		$i++;
	}
	fclose($fp);
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Product Price Updated Successfully ------</strong></td>
	</tr>
	</table>
	
	<?php 
	function cutoff($vals)
	{
		$str = explode('.',$vals);
		return $str[0];
	}
	
	?>
