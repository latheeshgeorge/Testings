<?php
	
	include_once('header.php');
	
	$import_images		= 'category_relations.csv';

	$sql_theme = "SELECT themes_theme_id,site_domain  
					FROM 
						sites 
					WHERE 
						site_id = $siteid 
					LIMIT 
						1";
	$ret_theme = $db->query($sql_theme);
	if($db->num_rows($ret_theme))
	{
		$row_theme = $db->fetch_array($ret_theme);
	}
	
	$image_path 		= ORG_DOCROOT . '/images/' . $row_theme['site_domain'];
	
	$sql_dimension 		= "SELECT bigimage_geometry,thumbimage_geometry,categoryimage_geometry,
								categorythumbimage_geometry,iconimage_geometry 
							FROM 
								themes 
							WHERE 
								theme_id=".$row_theme['themes_theme_id'];
	$ret_dimension 		= $db->query($sql_dimension);
	if($db->num_rows($ret_dimension))
	{
		$row_dimension 			= $db->fetch_array($ret_dimension);
		$geometry["thumb"]		= $row_dimension['thumbimage_geometry'];
		$geometry["big"]		= $row_dimension['bigimage_geometry'];
		$geometry["cat"]		= $row_dimension['categoryimage_geometry'];
		$geometry["catthumb"]	= $row_dimension['categorythumbimage_geometry'];
		$geometry["icon"]		= $row_dimension['iconimage_geometry'];
	}
	
	
			
	$fp_image = fopen($import_images,'r');
	if (!$fp_image)
	{
		echo "Cannot open the file";
		exit;
	}
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="center" style="color:#006600"><strong>#</strong></td>
		<td align="center" style="color:#006600"><strong>Category Id</strong></td>
		<td align="center" style="color:#006600"><strong>Old Category Id</strong></td>
		<td align="left" style="color:#006600"><strong>Satus</strong></td>
	</tr>
	<?php
		$cnt = 1;
		$i = 0;
		$prev_id = 0;
		$diff_i = 1;
		while (($data = fgetcsv($fp_image, 10000, ",")) !== FALSE)
		{
			if($i!=0) // done to avoid header row and also the categories with hidden status set to Y
			{
				$err_msg 					= '';
				$status						= '';
				$category_id				= trim(stripslashes($data[0]));
				$old_category_id			= trim(stripslashes($data[1]));
				$image 						= basename(trim(stripslashes($data[2])));
			
				if($category_id !='')
				{
					// Check whether category exists in database 
					$sql_cat = "SELECT category_id,category_name
									FROM 
										product_categories 
									WHERE 
										category_id = $category_id 
										AND sites_site_id = $siteid 
									LIMIT 
										1";
					$ret_cat = $db->query($sql_cat);
					if($db->num_rows($ret_cat))
					{
						$row_cat 	= $db->fetch_array($ret_cat);
						$catid	= $row_cat['category_id'];
						$catname = stripslashes($row_cat['category_name']);
						
						// Check whether the image exists
						if($image!='')
						{
							$status = save_image($image,$geometry,$category_id,$catname,1);
						}
						else
						{
							$status = '--No Image --';		
						}
						
					}
					else
					{
						$catname = 'Category Images';
						$err_msg 		= '<strong>Category Does not exists in database</strong>';
							
					}
					
					
				}
				else
				{
					$err_msg 		= '<strong>Category Id Not Found</strong>';
				}	
				
				
				?>
					<tr>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $diff_i?></td>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $category_id?></td>
					<td align="left" style="color:#FFFFFF; background-color:#666666" width="20%"><?php echo $old_category_id?></td>
					<td align="left" style="color:#FFFFFF; background-color:#666666"><?php echo $status.$err_msg?></td>
					</tr>
				<?php
					$prev_id = $product_id;
					$diff_i++;
				
			}
			$i++;
		}
		fclose($fp_image);
	?>
	</table>
	
	<?php
	
	function get_img_addr($ImageAddressURL)
	{
		$ImageAddress_arr  =  explode("/", $ImageAddressURL);
		return $ImageAddress = end($ImageAddress_arr);
	}
	
	
	function save_image($img,$geometry,$category_id,$catname,$order)
	{
		global $db,$siteid;
		if($img!='')
		{
			$src_img	= 'cablestripping_images/'.$img;
			$Img_Resize = 1;
			// check whether the current file exists
			if(file_exists($src_img))
			{
				$bigimage["name"] 		= $img;
				$bigimage["tmp_name"]	= $src_img;
				$sr_arr 				= array (" ","'");
				$rp_arr 				= array("_","");
				$bigimage["name"]		= str_replace($sr_arr,$rp_arr,$bigimage["name"]);
				// If no caption, use filename
				$caption 			= substr($bigimage["name"], 0, strrpos($bigimage["name"], "."));
				$bigimagename 		= $curtime.strtolower($bigimage["name"]);
				$copy_only			= true;
				$bigimage_path  	= resize_image($bigimage["tmp_name"], "big/".$bigimagename, $geometry["big"], '',$Img_Resize);
				$copy_only 			= true;
				$catimage_path 		= resize_image($bigimage["tmp_name"], "category/".$bigimagename, $geometry["cat"], '',$Img_Resize);
				$copy_only 			= true;
				$icon_path 			= resize_image($bigimage["tmp_name"], "icon/".$bigimagename, $geometry["icon"],  '',$Img_Resize);
				$copy_only 			= true;
				$extralarge_path	= resize_image($bigimage["tmp_name"], "extralarge/".$bigimagename, '',  '',2);// no resize required
				$copy_only          = true;
				$cathumbimage_path 	= resize_image($bigimage["tmp_name"], "category_thumb/".$bigimagename, $geometry["catthumb"], '',$Img_Resize);
		
				$copy_only			= true;
				$thumb_path 		= resize_image($bigimage["tmp_name"], "thumb/".$bigimagename, $geometry["thumb"], '',$Img_Resize);
		
				$copy_only			= true;
				$gallery_thumb_path	= resize_image($bigimage["tmp_name"], "gallerythumb/".$bigimagename, '90>', '',$Img_Resize);
		
				//Make an entry to the 	
				if($bigimage_path || $thumb_path)
				{
					// Check whether there exists an image directory with the name as default category of current product
					$sql_dir = "SELECT directory_id 
									FROM 
										images_directory 
									WHERE 
										directory_name = '".$catname."' 
										AND sites_site_id = $siteid 
									LIMIT 
										1";
					$ret_dir = $db->query($sql_dir);
					if($db->num_rows($ret_dir))
					{
						$row_dir 		= $db->fetch_array($ret_dir);
						$directory_id	= $row_dir['directory_id'];
					}
					else
					{
						$insert_array						= array();
						$insert_array['parent_id']			= 0;
						$insert_array['sites_site_id']		= $siteid;
						$insert_array['directory_name']		= $catname;
						$db->insert_from_array($insert_array,'images_directory');
						$directory_id = $db->insert_id();
					}
					$atleastone 									= 1;
					$insert_array 									= array();
					$insert_array['sites_site_id']					= $siteid;
					$insert_array['image_title']					= addslashes($caption);
					$insert_array['image_bigpath']					= $bigimage_path;
					$insert_array['image_bigcategorypath']			= $catimage_path;
					$insert_array['image_thumbcategorypath']		= $cathumbimage_path;
					$insert_array['image_extralargepath']			= $extralarge_path;
					$insert_array['image_thumbpath']				= $thumb_path;
					$insert_array['image_gallerythumbpath']			= $gallery_thumb_path;
					$insert_array['image_iconpath']					= $icon_path;
					$insert_array['images_directory_directory_id']	= $directory_id;
					$db->insert_from_array($insert_array,'images');	
					$image_id = $db->insert_id();
			
					// Map this image with current product
					$insert_array					= array();
					$insert_array['product_categories_category_id']		= $category_id;
					$insert_array['images_image_id']					= $image_id;
					$insert_array['image_title']						= addslashes($caption);
					$insert_array['image_order']						= $order;
					$db->insert_from_array($insert_array,'images_product_category');
					$err_msg .= 'Done'; 
				}
			}
			else
			{
				$err_msg 		= '<strong>Image Does not exist</strong>';
			}
		}
		return $err_msg;
	}
	
	
	
	function resize_image($old, $new, $geometry, $exten,$resize_me = 1,$overwrite=false)
	{
		global $image_path,$copy_only;
		$convert_path = CONVERT_PATH;
		$base = substr($new, 0, strrpos($new, "."));
		$new = "$base.jpg";
		$n = 0;
		if ($overwrite==false)
		{
			while(file_exists("$image_path/$new"))
			{
				$n++;
				$new = "$base$n.jpg";
			}
		}
		if ($resize_me==1)
		{
			$command = $convert_path."/convert \"$old\" -geometry \"$geometry\" -interlace Line \"$image_path/$new\" 2>&1";
			//echo htmlentities($command) . "<br><br>";
	
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
		else
		{
			echo '<br>New '.$new_path = "$image_path/$new";
			echo '<br> Old '.$old;
			$res = copy($old,$new_path);
			if ($res)
			return $new;
			else
			{
				echo "Upload Failed";
				return FALSE;
			}
		}
	}
	?>
