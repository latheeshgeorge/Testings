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
	 $ecom_siteid 		= 109;// http://unipad.co.uk
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
	$sql_images = "SELECT * FROM images WHERE sites_site_id = $ecom_siteid";
	$ret_images = $db->query($sql_images);
	if($db->num_rows($ret_images)>0)
	{
		while($row_images = $db->fetch_array($ret_images))
		{
		   $bigname = str_replace('big/',"",stripcslashes($row_images['image_bigpath']));
		   $thumbname = str_replace('thumb/',"",stripcslashes($row_images['image_thumbpath']));
		   $categoryname = str_replace('category/',"",stripcslashes($row_images['image_bigcategorypath']));
		   $thumbcategoryname = str_replace('category_thumb/',"",stripcslashes($row_images['image_thumbcategorypath']));
		   $extralargename = str_replace('extralarge/',"",stripcslashes($row_images['image_extralargepath']));
		   //$extralargename = stripcslashes($row_images['image_extralargepath']);
		   $gallerythumbname = str_replace('gallerythumb/',"",stripcslashes($row_images['image_gallerythumbpath']));
		   $iconname = str_replace('icon/',"",stripcslashes($row_images['image_iconpath']));
           //$src_dir = $image_path.'/extralarge'; 
           //$srcfile = $image_path."/".$extralargename;
           
			$srcfile1 = $image_path."/big_06mar2017_bak/".$bigname;
           	resize_image($srcfile1, "big/".$bigname, $geometry["big"]);
           	
           	$srcfile2 = $image_path."/thumb_06mar2017_bak/".$thumbname;
           	resize_image($srcfile2, "thumb/".$thumbname, $geometry["thumb"]);
            
            $srcfile3 = $image_path."/category_06mar2017_bak/".$categoryname;
			resize_image($srcfile3, "category/".$categoryname, $geometry["cat"]);
			
			$srcfile4 = $image_path."/category_thumb_06mar2017_bak/".$thumbcategoryname;
			resize_image($srcfile4, "category_thumb/".$thumbcategoryname, $geometry["catthumb"]);
			//resize_image($srcfile, "gallerythumb/".$gallerythumbname, '90>');
			
			$srcfile5 = $image_path."/icon_06mar2017_bak/".$iconname;
			resize_image($srcfile5, "icon/".$iconname, $geometry["icon"]);
			
			$srcfile6 = $image_path."/extralarge_06mar2017_bak/".$extralargename;
			resize_image($srcfile6, "extralarge/".$extralargename, '1200x1200');
			
			$stat = '<font style="color:#00FF00">Resized</font>';
		?>
		<tr>
			<td align="left"><?php echo $cnt++?></td>
			<td align="left"><?php echo $extralargename?></td>
			<td align="left"><?php echo $stat?></td>
		</tr>
		<?php	
		   
		}
    }
	
?>
<tr>
<td colspan="3">-- Operation Completed --</td>
</tr>
</table>
<?php
//Function to resize and copy the uploaded files to required location
function resize_image($old, $new, $geometry)
{
	global $image_path;
	$convert_path = '/usr/bin';
	// Probably not necessary, but might as well
	$command = $convert_path."/convert \"$old\" -geometry \"$geometry\" -interlace Line \"$image_path/$new\" 2>&1";
	//return;
	$p = popen($command, "r");
	$error = "";
	while(!feof($p)) {
		$s = fgets($p, 1024);
		$error .= $s;
	}
	$res = pclose($p);

	if($res == 0) return $new;
	else {
		echo ("Failed to resize image: $error<br>");
		return FALSE;
	}
}
?>
