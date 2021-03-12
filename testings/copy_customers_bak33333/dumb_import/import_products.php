<?php
	include_once('header.php');
		
	$import_file 			= 'csv/products.csv';	// Import filename
	$i=0;
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
	
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
		
		
	$atleast_one_err = 0;
	$i =0;
	while (($data = fgetcsv($fp, 20000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 				= '';

			$products_id 				= trim($data[0]);
			$categories_id 				= trim($data[1]);
			$products_quantity 			= trim($data[2]);
			$products_model 			= trim($data[3]);
			$products_image 			= trim($data[4]);
			$products_price 			= trim($data[5]);
			$products_date_available 	= trim($data[6]);
			$products_weight 			= trim($data[7]);
			$products_status			= trim($data[8]);
			$products_tax_class_id		= trim($data[9]);
			$manufacturers_id			= trim($data[10]);
			$manufacturers_name			= trim($data[11]);
			$manufacturers_image		= trim($data[12]);
			$manufacturers_url			= trim($data[13]);
			$products_name				= trim($data[14]);
			$products_description		= trim($data[15]);
			
			
			
			/*finding the category Id*/
			$fp2 = fopen('category_relations.csv','r');
			if (!$fp2)
			{
				echo "Cannot open the file";
				exit;
			}
			//echo 'hai';
			//echo '<br><br>';
			$j =0;
			//$cnt = 1;
			while (($data2 = fgetcsv($fp2, 20000, ",")) !== FALSE)
			{
				
				if($j!=0) // case of header row
				{
					$category_id 				= trim($data2[0]);
					$old_category_id 			= trim($data2[1]);
					$image 						= trim($data2[2]);
					
					//echo '<br>';
					$cur_category_id = 0;
					if($categories_id == $old_category_id)
					{
						$cur_category_id = $category_id;
					
					//if($cnt == 1)
					//{
						//echo $category_id;
						//echo '<br>';
						//echo $old_category_id ;
						//echo '<br>';
						//echo $image ;
						//echo '<br><br>';
						
						$insert_array['sites_site_id']					= $siteid;
						$insert_array['manufacture_id']					= addslashes(stripslashes($manufacturers_name));	
						$insert_array['product_name']					= addslashes(stripslashes($products_name));
						$insert_array['product_model']					= addslashes(stripslashes($products_model));
						$insert_array['product_longdesc']				= addslashes(stripslashes($products_description));
						$insert_array['product_webprice']				= addslashes(stripslashes($products_price));
						$insert_array['product_costprice']				= addslashes(stripslashes($products_price));
						$insert_array['product_webstock']				= addslashes(stripslashes($products_quantity));
						$insert_array['product_actualstock']			= addslashes(stripslashes($products_quantity));
						$insert_array['product_weight']					= addslashes(stripslashes($products_weight));

						
						$insert_array['product_default_category_id']	= $cur_category_id;
						
						$insert_array['product_applytax']					= 'N';
						
									
						if($products_status == 1)
						{
						$insert_array['product_hide']					= 'N';
						}
						else
						{
						$insert_array['product_hide']					= 'Y';
						}
						
						
						/*inserting a new product*/
						
						$var_order = 0;
						$var_order1 = 0;
						$var_data_order = 0;
						
						/* checking product duplicancy */
						$sql_check_qry = "SELECT product_name FROM products WHERE sites_site_id = $siteid AND product_name = '".addslashes(stripslashes($products_name))."' AND product_model = '".addslashes(stripslashes($products_model))."'  AND product_id IN (SELECT products_product_id FROM product_category_map WHERE product_categories_category_id = $cur_category_id)";
						$check_qry = $db->query($sql_check_qry);
						if($db->num_rows($check_qry))
						{
							$row = $db->fetch_array($check_qry);
							$product_name = $row['product_name'];
							
							?>
							<tr>
								<td colspan="3" align="left" style="color:#E50000"><strong><?php echo "Product Duplicated    :  ".$row['product_name'];?></strong></td>
							</tr>
							<?php
						}
						else
						{
						
						/*inserting a new product -- starts*/
						$db->insert_from_array($insert_array,'products');
						$product_id = $db->insert_id();
						
						
						
						/*inserting product_category_map table -- starts*/
						$insert_cat_array									= array();
						$insert_cat_array['products_product_id']			= $product_id;
						$insert_cat_array['product_categories_category_id']	= $cur_category_id;
						$db->insert_from_array($insert_cat_array,'product_category_map');
						
						unset ($insert_array);
						unset ($insert_cat_array);
						
						/*inserting product_category_map table -- ended*/
						
						
						/*inserting product image -- starts*/
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
						
						
						if($product_id !='')
						{
							
								
								$defcatid	= $cur_category_id;
								$name		= stripslashes($products_name);
								$status1 = $status2 = $status3 = $status4 = $status5 = '--No Image --';
								// get the name of current category
								$sql_cat = "SELECT category_name 
												FROM 
													product_categories 
												WHERE 
													category_id = $defcatid 
													AND sites_site_id = $siteid 
												LIMIT 
													1";
								$ret_cat = $db->query($sql_cat);
								if($db->num_rows($ret_cat))
								{
									$row_cat = $db->fetch_array($ret_cat);
									$catname = stripslashes($row_cat['category_name']);
								}
								else
								{
									$catname = 'Product Images';
									
								}
								// Check whether the image exists
								if($products_image!='')
								{
									$status1 = save_image($products_image,$geometry,$product_id,$catname,1);
								}
								
								$status  = "Image 1 : ".$status1."<br/>";
								
							
						}
						
						echo $products_name.'->'.$status.$err_msg;
				    	}
						
						/*inserting product image -- ends*/
						
						
						/*inserting a new product -- ends*/
				//}		
				//$cnt++;	
				}
				/*
				else
				{
					echo addslashes(stripslashes($products_name)).' s Category Not Found';
				}
				*/	
				}
				$j++;
		    }
			fclose($fp2);
			
			
	
							
		}
		$i++;
	}
	fclose($fp);
	
	?>
    <tr>
		<td colspan="3" align="center" style="color:#006600"><strong>----- Products Imported Successfully ------</strong></td>
	</tr>
	</table>
    
    <?php
	function rmv_double_qt($dbl_qt_str)
	{
		$bodytag = str_replace("\"", "\'\'", $dbl_qt_str);
		return $bodytag;
	}
	
	
	function get_img_addr($ImageAddressURL)
	{
		$ImageAddress_arr  =  explode("/", $ImageAddressURL);
		return $ImageAddress = end($ImageAddress_arr);
	}
	
	
	function save_image($img,$geometry,$product_id,$catname,$order)
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
					$insert_array['products_product_id']		= $product_id;
					$insert_array['images_image_id']		= $image_id;
					$insert_array['image_title']			= addslashes($caption);
					$insert_array['image_order']			= $order;
					$db->insert_from_array($insert_array,'images_product');
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
