<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	$ecom_siteid = 72;
	
	// Get all product reviews
	$sql_review = "SELECT review_id, products_product_id, review_rating,review_status 
						FROM 
							product_reviews 
						WHERE sites_site_id=$ecom_siteid";
	$ret_review = $db->query($sql_review);
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><strong>#</strong></td>
		<td><strong>Product Id</strong></td>	
		<td><strong>Current Rating</strong></td>
		<td><strong>Modified Rating</strong></td>
	</tr>	
<?php	
	if($db->num_rows($ret_review))
	{
		while($row_review = $db->fetch_array($ret_review))
		{
			$i++;
			if($row_review['review_rating']>=2)
				$rating = ceil($row_review['review_rating']/2);
			else
				$rating = $row_review['review_rating'];
				$sql_update = "UPDATE product_reviews 
								SET 
									review_rating = $rating 
								WHERE 
									review_id = ".$row_review['review_id']." 
								LIMIT 
									1";
				$db->query($sql_update);
				$style = ($i%2==0)?'background-color:#999999':'background-color:#FFFFFF';
?>	
				<tr>
				<td style="<?php echo $style?>"><strong><?php echo $i?></strong></td>
				<td style="<?php echo $style?>"><?php echo $row_review['products_product_id']?></td>
				<td style="<?php echo $style?>"><?php echo $row_review['review_rating']?></td>
				<td style="<?php echo $style?>"><?php echo $rating?></td>
				</tr>	
<?php
		}			
	}
?>
	</table>	
<tr>
<td align="center" colspan="4"><strong>--- Completed ---</strong></td>
</tr>
</table>