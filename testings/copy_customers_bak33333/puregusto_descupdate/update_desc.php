<?php
include "header.php";

$import_file = "csv/products_puregusto_only.csv";

$fp = fopen($import_file,'r');
if (!$fp)
{
	echo "Cannot open the file";
	exit;
}
$i=1;
?>
<table width="100%" cellpadding="1" cellspacing="1" border="1">
<tr>
<td>#</td>
<td>Prod Id</td>
<td>Prod Name</td>
<td>Status</td>
</tr>

<?php
while (($data = fgetcsv($fp, 5000, ",")) !== FALSE)
{
	//if($i<3) // case of header row
	{
		$err_msg 	= '';
		$prodid 	= trim($data[0]);
		$prodname 	= trim($data[7]);
		$desc 		= trim($data[13]);
		// Check whether the product id is valid
		$sql_prod = "SELECT product_id FROM products WHERE sites_site_id = $siteid and product_id = $prodid LIMIT 1";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			$sql_update = "UPDATE products SET 
											product_longdesc = '".addslashes($desc)."' 
											WHERE 
												product_id = $prodid 
												and sites_site_id = $siteid 
												
											LIMIT 
												1";
			$ret_update = $db->query($sql_update);
			$status = "Updated Succ";
		}
		else
		{
			$status = "<span color='#FF0000;'>Product Not found - $prodid </span>";
		}	
		?>
		<tr>
		<td><?php echo $i?></td>
		<td><?php echo $prodid?></td>
		<td><?php echo $prodname?></td>
		<td><?php echo $status?></td>
		</tr>
		<?php
	}
	$i++;
}	
?>
</table>
<?php	
fclose($fp);
echo "Done";
?>
