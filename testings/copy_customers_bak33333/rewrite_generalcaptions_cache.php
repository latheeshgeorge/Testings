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
	<td align="left" colspan="2">
	<strong>Rewriting the captions caches for all websites</strong>
	</td>
	</tr>
	<tr>
	<td align="left"><strong>Domain</strong></td>
	<td align="center"><strong>Status</strong></td>
	</tr>
	<?php
		while ($row_sites = $db->fetch_array($ret_sites))
		{
			$image_path = ORG_DOCROOT.'/images/'.$row_sites['site_domain'];
			create_Captions_CacheFile_All($row_sites['site_id'],$image_path);
	?>
			<tr>
				<td align="left"><?php echo $row_sites['site_domain']?></td>
				<td align="center">Done</td>
			</tr>
	<?php		
		}
	?>
	<tr>
	<td align="center" colspan="2" style="color:#009900">
	<strong>--- Completed ---</strong>
	</td>
	</tr>
	</table>
	<?php	
}
function create_Captions_CacheFile_All($site_id,$image_path)
{
	global $db;
	
	// Get the name of section 
	$sql_section = "SELECT section_id,section_code 
							FROM 
								general_settings_section ";
	$ret_section = $db->query($sql_section);
	if($db->num_rows($ret_section))
	{
		while($row_section 	= $db->fetch_array($ret_section))
		{
			$section_code	=  strtolower($row_section['section_code']);	
			$section_id			= $row_section['section_id'];
			if(!file_exists($image_path .'/settings_cache'))
				mkdir($image_path .'/settings_cache');
			$file_path = $image_path .'/settings_cache/settings_captions';
			if(!file_exists($file_path))
				mkdir($file_path);
			$file_name = $file_path.'/'.$section_code.'.php';
			// Open the file in write mod 
			$fp = fopen($file_name,'w');
			fwrite($fp,'<?php'."\n");
			// get the details from general_settings_site_pricedisplay
			$sql_cap = "SELECT general_key,general_text 
									FROM 
										general_settings_site_captions  
									WHERE 
										sites_site_id = $site_id 
										AND general_settings_section_section_id = $section_id 
									";
			$ret_cap = $db->query($sql_cap);
			if ($db->num_rows($ret_cap))
			{
				while($row_cap = $db->fetch_array($ret_cap))
				{	
					fwrite($fp,'$Cache_captions_arr["'.$row_cap['general_key'].'"] = "'. addslashes(stripslashes($row_cap['general_text'])).'";'."\n");
				}	
			}
			fwrite($fp,'?>');		
			fclose($fp);	
		}
	}		
}
?>
	