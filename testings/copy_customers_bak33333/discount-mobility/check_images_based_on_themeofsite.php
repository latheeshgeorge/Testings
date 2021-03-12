<?php
	set_time_limit(0); // done to avoid page getting timed out
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	/********************************************************
		 Variable Section Start here 
	 *********************************************************/
	 $ecom_siteid 		= 72;// metrodent_new theme
	 //$image_path	= '/var/www/html/webclinic/bshop4/images';//local
	 $image_path		= '/var/www/vhosts/bshop4.co.uk/httpdocs/images';//live
	
	/********************************************************
		 Variable Section Ends here 
	*********************************************************/
	
	$sql_site = "SELECT site_id,site_domain,themes_theme_id 
						FROM 
							sites 
						WHERE 
							site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_site = $db->query($sql_site);
	if($db->num_rows($ret_site))
	{	
		$row_site = $db->fetch_array($ret_site);
		$ecom_themeid = $row_site['themes_theme_id'];
		$ecom_hostname = $row_site['site_domain'];
	}
	$image_path			.= '/'.$ecom_hostname;
	$sql_dimension 		= "SELECT bigimage_geometry,thumbimage_geometry,categoryimage_geometry,
											categorythumbimage_geometry,iconimage_geometry 
										FROM 
											themes 
										WHERE 
											theme_id=$ecom_themeid";
	$ret_dimension 		= $db->query($sql_dimension);
	if($db->num_rows($ret_dimension))
	{
		$row_dimension 				= $db->fetch_array($ret_dimension);
		$geometry["thumb"]		= $row_dimension['thumbimage_geometry'];
		$geometry["big"]			= $row_dimension['bigimage_geometry'];
		$geometry["cat"]			= $row_dimension['categoryimage_geometry'];
		$geometry["catthumb"]	= $row_dimension['categorythumbimage_geometry'];
		$geometry["icon"]			= $row_dimension['iconimage_geometry'];
	}

	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left" colspan="3"><?php echo $ecom_hostname?></td>
	</tr>
	<tr>
	<td align="left">#</td>
	<td align="left">File Name</td>
	<td align="left">Status</td>
	</tr>
	<?php
	
	$src_dir = $image_path.'/extralarge';
	if( $dh = opendir($src_dir))
    {
		$cnt = 1;
        while (false !== ($file = readdir($dh)))
        {
			$stat = '';
            // Skip '.' and '..'
            if( $file == '.' || $file == '..')
                continue;
            $srcfile	= $src_dir.'/'.$file;
			$fls = 0;
			if(!file_exists($image_path."/thumb/".$file))
			{
				$stat .= '<br>Thumb Img not found';
				$fls = 1;
			}
			if(!file_exists($image_path."/big/".$file))
			{
				$stat .= '<br>Big Img not found';
				$fls = 1;
			}
			if(!file_exists($image_path."/category/".$file))
			{
				$stat .= '<br>Cat Img not found';
				$fls = 1;
			}
			if(!file_exists($image_path."/category_thumb/".$file))
			{
				$stat .= '<br>Cat thumb Img not found';
				$fls = 1;
			}
			if(!file_exists($image_path."/gallerythumb/".$file))
			{
				$stat .= '<br>Gallery thumb Img not found';
				$fls = 1;
			}
			if(!file_exists($image_path."/icon/".$file))
			{
				$stat .= '<br>Icon Img not found';
				$fls = 1;
			}
			if($fls==0)
				$stat = "http://$ecom_hostname/images/$ecom_hostname/thumb/".$file.'<br>Image Ok';
		?>
		<tr>
			<td align="left"><?php echo $cnt++?></td>
			<td align="left"><?php echo $file?></td>
			<td align="left"><?php echo $stat?></td>
		</tr>
		<?php	
        }
        closedir($dh);
    }	
?>
<tr>
<td colspan="3">-- Operation Completed --</td>
</tr>
</table>
