<?php
	set_time_limit(0); // done to avoid page getting timed out
	include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	/********************************************************
		 Variable Section Start here 
	 *********************************************************/
	//$ecom_siteid 		= 24;//local
	$ecom_siteid 		= 65;//live
	//$image_path	= '/var/www/html/bshop4/images';//local
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
	$sql_images 		= "SELECT image_id, image_bigpath, image_extralargepath  
										FROM 
											images 
										WHERE 
											sites_site_id=$ecom_siteid";
	$ret_images 		= $db->query($sql_images);
	
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left" colspan="3"><?php echo $ecom_hostname?></td>
	</tr>
	<tr>
	<td align="left">#</td>
	<td align="left">Image Id</td>
	<td align="left">Source</td>
	<td align="left">Destination</td>
	<td align="left">Sql</td>
	</tr>
	<?php
	echo $db->num_rows($ret_images);
	if($db->num_rows($ret_images))
	{
		$dest_dir = $image_path.'/extralarge';
		$cnt = 1;
	    while ($row_images = $db->fetch_array($ret_images))
	    {
	    	$extra = trim($row_images['image_extralargepath']);
	    	if(strpos($extra,'extralarge/')===false)
	    	{
	    		$src = $image_path.'/'.$extra;
	    		if(file_exists($src))
	    		{
		    		$temp = explode('big/',$row_images['image_bigpath']);
		    		$dest = $dest_dir.'/'.$temp[1];
		    		$update_sql = "UPDATE images 
		    						SET 
		    							image_extralargepath ='extralarge/".$temp[1]."' 
		    						WHERE 
		    							image_id = ".$row_images['image_id']." 
		    							AND sites_site_id=$ecom_siteid 
		    						LIMIT 
		    							1";
		    		$db->query($update_sql);
		?>
					<tr>
						<td align="left"><?php echo $cnt++?></td>
						<td align="left"><?php echo $row_images['image_id']?></td>
						<td align="left"><?php echo $src?></td>
						<td align="left"><?php echo $dest?></td>
						<td align="left"><?php echo $update_sql?></td>
					</tr>
			<?php	
	    		}
	    	}
        }
	}
?>
<tr>
<td colspan="5">-- Operation Completed --</td>
</tr>
</table>