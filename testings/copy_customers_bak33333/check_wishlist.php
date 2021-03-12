<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="left" width="2%"><strong>#</strong></td>
		<td align="left" width="10%"><strong>prodid</strong></td>
		<td align="left" width="68%"><strong>prod name</strong></td>
		<td align="left" width="20%"><strong>wishid</strong></td>
	</tr>	
	<?php
	//id of site of which the domain name is being changed
	$ecom_siteid 	= 65;
	$sql = "Select * FROM wishlist WHERE sites_site_id = $ecom_siteid";
	$ret_advert = $db->query($sql);
	if($db->num_rows($ret_advert))
	{
		$cnt = 1;
		
		while ($row_advert = $db->fetch_array($ret_advert))
		{
			$err = false;
			$prodid = $row_advert['products_product_id'];
			$wishid = $row_advert['wishlist_id'];
			// get the buy now link is to be displayed for this product
			$sql_prod = "SELECT product_name,product_show_cartlink FROM products WHERE product_id = $prodid LIMIT 1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				if($row_prod['product_show_cartlink']==0)
						$err = true;
			}
			
			if($err==true)
			{
				
			?>
				<tr>
				<td align="left"><strong><?php echo $cnt;$cnt++;?></strong></td>
				<td align="left"><strong><?php echo $prodid?></strong></td>
				<td align="left"><strong><?php echo $row_prod['product_name']?></strong></td>
				<td align="left"><strong><?php echo $wishid;
					echo '<br>'.$sql_del = "DELETE FROM wishlist_variables WHERE wishlist_wishlist_id = $wishid";
					$db->query($sql_del);
					echo '<br>'.$sql_del = "DELETE FROM wishlist_messages WHERE wishlist_wishlist_id = $wishid";
					$db->query($sql_del);
					echo '<br>'.$sql_del = "DELETE FROM wishlist WHERE wishlist_id = $wishid LIMIT 1";
					$db->query($sql_del);
				
				?></td>
				</tr>	
			<?php
				
			}
		}
	}
	?>
		<td align="center" colspan="3">
			-- Done
		</td>
	</tr>
	
<tr>
<td colspan="3" align="center"><strong>-- Operation completed --</strong></td>
</tr>																
</table>
