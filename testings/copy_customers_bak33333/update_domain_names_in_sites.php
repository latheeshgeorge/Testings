<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	//id of site of which the domain name is being changed
	/*
	$ecom_siteid 	= 104;
	$old_domain		= 'http://www.discount-mobility.co.uk';
	$new_domain		= 'https://www.discount-mobility.co.uk'; 
	*/ 
	$ecom_siteid 	= 89;
	$old_domain		= 'http://www.cablestripping.co.uk';
	$new_domain		= 'https://www.cablestripping.co.uk'; 
	
	$sql_advert = "SELECT advert_id,advert_source, advert_link, advert_title
					FROM 
						adverts  
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret_advert = $db->query($sql_advert);
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="left" width="60%"><strong>Advert</strong></td>
	<?php
	if($db->num_rows($ret_advert))
	{
		$cnt = 1;
		while ($row_advert = $db->fetch_array($ret_advert))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row_advert['advert_source']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row_advert['advert_link']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row_advert['advert_title']));
			$update_sql = "UPDATE 
								adverts  
							SET 
								advert_source = '".addslashes($string1)."' ,
								advert_link = '".addslashes($string2)."' ,
								advert_title = '".addslashes($string3)."' 
							WHERE 
								advert_id = ".$row_advert['advert_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
	<?php
	$sql = "SELECT category_id, category_shortdescription, category_paid_description,category_bottom_description 
					FROM 
						product_categories        
					WHERE 
						sites_site_id = $ecom_siteid";
	$ret = $db->query($sql);
?>	
	<tr>
		<td align="left"><strong>Product Categories</strong></td>
	<?php
	if($db->num_rows($ret))
	{
		$cnt = 1;
		while ($row = $db->fetch_array($ret))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($row['category_shortdescription']));
			$string2 = str_replace($old_domain,$new_domain,stripslashes($row['category_paid_description']));
			$string3 = str_replace($old_domain,$new_domain,stripslashes($row['category_bottom_description']));
			$update_sql = "UPDATE 
								product_categories     
							SET 
								category_shortdescription 	= '".addslashes(($string1))."',
								category_paid_description 	= '".addslashes(($string2))."',
								category_bottom_description = '".addslashes(($string3))."'
							WHERE 
								category_id 	= ".$row['category_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);	
		}
	}
	?>
	<?php
	$sqlprod = "SELECT product_id, product_longdesc 
					FROM 
						products        
					WHERE 
						sites_site_id = $ecom_siteid";
	$retprod = $db->query($sqlprod);
?>	
	<tr>
		<td align="left"><strong>Products</strong></td>
	<?php
	if($db->num_rows($retprod))
	{
		$cnt = 1;
		while ($rowprod = $db->fetch_array($retprod))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($rowprod['product_longdesc']));
			$update_sqlp = "UPDATE 
								products     
							SET 
								product_longdesc 	= '".addslashes(($string1))."'
							WHERE 
								product_id 	= ".$rowprod['product_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sqlp);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
	<?php
	$sqlstat = "SELECT page_id, content 
					FROM 
						static_pages        
					WHERE 
						sites_site_id = $ecom_siteid";
	$retstat = $db->query($sqlstat);
?>	
	<tr>
		<td align="left"><strong>Static Pages</strong></td>
	<?php
	if($db->num_rows($retstat))
	{
		$cnt = 1;
		while ($rowstat = $db->fetch_array($retstat))
		{
			$string1 = str_replace($old_domain,$new_domain,stripslashes($rowstat['content']));
			$update_sqls = "UPDATE 
								static_pages     
							SET 
								content 	= '".addslashes(($string1))."'
							WHERE 
								page_id 	= ".$rowstat['page_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sqls);	
		}
	}
	?>
		<td align="center">
			-- Done
		</td>
	</tr>
		
<tr>
<td colspan="2" align="center"><strong>-- Operation completed --</strong></td>
</tr>																
</table>
