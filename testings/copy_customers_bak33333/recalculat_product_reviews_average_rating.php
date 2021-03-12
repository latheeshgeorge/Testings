<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	$ecom_siteid = 72;
	// Get all the products in database
	$sql_prod = "SELECT product_id,product_name,product_averagerating   
					FROM 
						products 
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_prod = $db->query($sql_prod);
	?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<?php
	if($db->num_rows($ret_prod))
	{
		$i=0;
		while ($row_prod = $db->fetch_array($ret_prod))
		{				
			$rating = 0;	
			$i++;
			// Get the product averages to be updates to products table
			$sql_review = "SELECT avg(review_rating) rateaverage 
									FROM 
										product_reviews 
									WHERE 
										review_status = 'APPROVED' 
										AND products_product_id = ".$row_prod['product_id'];
			$ret_review = $db->query($sql_review);
			$row_review = $db->fetch_array($ret_review);
			$rating = ceil($row_review['rateaverage']);
			if($rating<0)
				$rating = 0;
			$sql_update = "UPDATE products  
									SET 
										product_averagerating = $rating 
									WHERE 
										product_id = ".$row_prod['product_id']." 
									LIMIT 
										1";
			$db->query($sql_update);
			$style = ($i%2==0)?'background-color:#999999':'background-color:#FFFFFF';
			if ($row_prod['product_averagerating'] != $rating)
				$style1 = 'background-color:#FF0000';
			else
				$style1 = $style;
			$style3 = 'background-color:#00FF00';
?>	
			<tr>
			<td style="<?php echo $style?>"><strong><?php echo $i?></strong></td>
			<td style="<?php echo $style?>"><?php echo $row_prod['product_name'].' ('.$row_prod['product_id'].')'?></td>
			<td style="<?php echo $style1?>"><?php echo $row_prod['product_averagerating']?></td>
			<td style="<?php echo ($rating>0)?$style3:$style?>"><?php echo $rating?></td>
			</tr>	
<?php
		}			
	}
?>
<tr>
<td align="center" colspan="4"><strong>--- Completed ---</strong></td>
</tr>
</table>