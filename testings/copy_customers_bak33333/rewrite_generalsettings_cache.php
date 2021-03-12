<?php
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$i=0;
	
	// Path to be set correctly when uploading to live server
	//define('ORG_DOCROOT','/var/www/html/bshop4');
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');

	// get the list of all existing websites
	$sql_sites = "SELECT site_id,site_domain 
					FROM 
						sites ";
	$ret_sites = $db->query($sql_sites);
	if($db->num_rows($ret_sites))
	{
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left" colspan="3">
	<strong>Rewriting the general settings caches for all websites</strong>
	</td>
	</tr>
	<tr>
	<td align="left"><strong>Domain</strong></td>
	<td align="center"><strong>General Settings</strong></td>
	<td align="center"><strong>Price Display Settings</strong></td>
	</tr>
	<?php
		while ($row_sites = $db->fetch_array($ret_sites))
		{
			$image_path = ORG_DOCROOT.'/images/'.$row_sites['site_domain'];
			create_GeneralSettings_CacheFile($row_sites['site_id'],$image_path);
			create_PriceDisplaySettings_CacheFile($row_sites['site_id'],$image_path);
	?>
			<tr>
				<td align="left"><?php echo $row_sites['site_domain']?></td>
				<td align="center">Done</td>
				<td align="center">Done</td>
			</tr>
	<?php		
		}
	?>
	<tr>
	<td align="center" colspan="3" style="color:#009900">
	<strong>--- Completed ---</strong>
	</td>
	</tr>
	</table>
	<?php	
	}
function create_GeneralSettings_CacheFile($site_id,$image_path)
{
	global $db;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
	$file_name = $file_path.'/general_settings.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_sites_common
	$sql_common = "SELECT * 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id=$site_id 
							LIMIT 
								1";
	$ret_common = $db->query($sql_common);
	if ($db->num_rows($ret_common))
	{
		$row_common = $db->fetch_assoc($ret_common);
		$check_array = array('voucher_buy_text','voucher_spend_text','pricepromise_topcontent','pricepromise_bottomcontent',
								'product_freedelivery_content','bonus_point_details_content','payon_account_details_content',
								'general_download_topcontent','general_shopsall_topcontent','general_shopsall_bottomcontent',
								'general_comboall_topcontent','general_comboall_bottomcontent','general_savedsearch_topcontent',
								'general_savedsearch_bottomcontent','general_pricepromise_addtocart'
							);
		foreach ($row_common as $k=>$v)
		{
			if ($k!=='listing_id' and $k!=='sites_site_id' and !in_array($k,$check_array))
				fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	
	// get the details from general_settings_sites_common_onoff
	$sql_common = "SELECT * 
						FROM 
							general_settings_sites_common_onoff 
						WHERE 
							sites_site_id=$site_id 
						LIMIT 
							1";
	$ret_common = $db->query($sql_common);
	if ($db->num_rows($ret_common))
	{
		$row_common = $db->fetch_assoc($ret_common);
		foreach ($row_common as $k=>$v)
		{
			if ($k!=='listing_id' and $k!=='sites_site_id')
				fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	
	// get the details from general_settings_sites_listorders
	$sql_common = "SELECT * 
							FROM 
								general_settings_sites_listorders 
							WHERE 
								sites_site_id=$site_id 
							LIMIT 
								1";
			$ret_common = $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_assoc($ret_common);
				foreach ($row_common as $k=>$v)
				{
					if ($k!=='listing_id' and $k!=='sites_site_id')
						fwrite($fp,'$Settings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
				}	
			}
	fwrite($fp,'?>');		
	fclose($fp);
}
function create_PriceDisplaySettings_CacheFile($site_id,$image_path)
{

	global $db,$ecom_siteid;
	$file_path = $image_path .'/settings_cache';
	if(!file_exists($file_path))
		mkdir($file_path);
	$file_name = $file_path.'/price_display_settings.php';
	// Open the file in write mod 
	$fp = fopen($file_name,'w');
	fwrite($fp,'<?php'."\n");
	// get the details from general_settings_site_pricedisplay
	$sql_price = "SELECT * 
							FROM 
								general_settings_site_pricedisplay 
							WHERE 
								sites_site_id = $site_id 
							LIMIT 
								1";
	$ret_price = $db->query($sql_price);
	if ($db->num_rows($ret_price))
	{
		$row_price = $db->fetch_assoc($ret_price);
		foreach($row_price as $k=>$v)
		{	
			if ($k!=='price_id' and $k!=='sites_site_id')
				fwrite($fp,'$PriceSettings_arr["'.$k.'"] = "'. addslashes(stripslashes($v)).'";'."\n");
		}	
	}
	fwrite($fp,'?>');		
	fclose($fp);	
}
?>
	