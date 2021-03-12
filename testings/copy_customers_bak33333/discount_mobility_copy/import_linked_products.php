<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	
	$fp_prodmap = fopen($product_map,'r');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$prod_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$prod_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodmap);
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$sql_prod = "SELECT * FROM products WHERE sites_site_id = $src_siteid";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			while ($row_prod = $db->fetch_array($ret_prod))
			{
				$oldprodid = $row_prod['product_id'];
				// check whether linked products exists for current product
				$sql_linked = "SELECT * FROM product_linkedproducts WHERE link_product_id=$oldprodid";
				$ret_linked = $db->query($sql_linked);
				if($db->num_rows($ret_linked))
				{
					while ($row_linked = $db->fetch_array($ret_linked))
					{
						$parentid = $prod_arr[$row_linked['link_parent_id']];
						$linkid  = $prod_arr[$row_linked['link_product_id']];
						
						$insert_array 						= array();
						$insert_array['sites_site_id'] 		= $dest_siteid;
						$insert_array['link_parent_id'] 	= $parentid;
						$insert_array['link_product_id'] 	= $linkid;
						$insert_array['link_order'] 		= addslashes(stripslashes($row_linked['link_order']));
						$insert_array['link_hide'] 			= addslashes(stripslashes($row_linked['link_hide']));
						$db->insert_from_array($insert_array,'product_linkedproducts');
					}
				}
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Linked Products Mapped Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
