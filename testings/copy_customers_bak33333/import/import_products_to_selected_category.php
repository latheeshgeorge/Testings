<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	//$siteid 				= 79; // Local destination site id
	$siteid 				= 66; // Live destination site id
	// Local variables
	//define('ORG_DOCROOT','/var/www/html/bshop4');
	// Live variables
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');



	define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	$import_file 			= 'import_file.csv';	// Import filename
	$import_category_name 	= 'Imported'; // name of category to which the products will be imported
	$img_dir_name			= "Imported Images"; // name of image directory
	
		
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
	
	
	$fp = fopen($import_file,'r');
	if (!$fp)
	{
		echo "Cannot open the file";
		exit;
	}
	// Check whether there exists a category with the name Imported
	$sql_cat = "SELECT category_id 
					FROM 
						product_categories 
					WHERE 
						sites_site_id = $siteid 
						AND LOWER(category_name) = '".strtolower($import_category_name)."' 
					LIMIT 
						1";
	$ret_cat = $db->query($sql_cat);
	if($db->num_rows($ret_cat))
	{
		$row_cat = $db->fetch_array($ret_cat);
		$category_id = $row_cat['category_id'];
	}
	else
	{
		$insert_array 								= array();
		$insert_array['sites_site_id']				= $siteid;
		$insert_array['category_name']				= $import_category_name;
		$insert_array['category_shortdescription']	= 'Imported Category';
		$db->insert_from_array($insert_array,'product_categories');
		$category_id = $db->insert_id();
	}
	
	$sql_dimension 		= "SELECT bigimage_geometry,thumbimage_geometry,categoryimage_geometry,
											categorythumbimage_geometry,iconimage_geometry 
										FROM 
											themes 
										WHERE 
											theme_id=".$row_theme['themes_theme_id'];
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
	$i=0;
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
				<td><strong>#</strong></td>
				<td><strong>Product name</strong></td>
				<td><strong>Status</strong></td>
				</tr>
	<?php
	$atleast_one_err = 0;
	while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 	= '';
			$name 		= trim($data[0]);
			$desc 		= trim($data[1]);
			$img 		= trim($data[2]);
			$price 		= trim($data[3]);
			$color 		= trim($data[4]);
			$finish		= trim($data[5]);
			$size		= trim($data[6]);
			$supplier	= trim($data[7]);
			// Check whether there already exists any products with same name in website
			$sql_prodcheck = "SELECT product_id 
								FROM 
									products 
								WHERE 
									LOWER(product_name) = '".addslashes(strtolower($name))."' 
									AND sites_site_id = $siteid 
								LIMIT 
									1";
			$ret_prodcheck = $db->query($sql_prodcheck);
			if ($db->num_rows($ret_prodcheck))
			{
				$err_msg = 'Product already exists in website -- Row No: '.($i+1);
			} 
			if(!is_numeric($price))
			{
				if($err_msg!='')
					$err_msg .= '<br>';
					$err_msg = 'Price '.$price.' not valid -- Row No: '.($i+1);
			}
			if($err_msg)
			{
				$atleast_one_err = 1;
			?>
				<tr>
				<td><strong><?php echo ($i)?></strong></td>
				<td><strong><?php echo $name?></strong></td>
				<td style="color:#FF0000"><strong><?php echo $err_msg?></strong></td>
				</tr>	
			<?php
			}
		}
		$i++;
	}
	fclose($fp);
	if($atleast_one_err) // case if atleast one error exists
	{
	?>
		<tr>
		<td colspan="3" align="center" style="color:#FF0000"><strong>-- Sorry no products imported --- </strong><br /><br /> Please correct the above errors in csv file and try again...</td>
		</tr>
	<?php			
	} 
	else // case if no errors exists
	{
		$fp = fopen($import_file,'r');
		if (!$fp)
		{
			echo "Cannot open the file";
			exit;
		}
	
	$atleast_one_err = 0;
	$i =0;
	while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
	{
		if($i!=0) // case of header row
		{
			$err_msg 	= '';
			$name 		= trim($data[0]);
			$desc 		= trim($data[1]);
			$img 		= trim($data[2]);
			$price 		= trim($data[3]);
			$color 		= trim($data[4]);
			$finish		= trim($data[5]);
			$size		= trim($data[6]);
			$supplier	= trim($data[7]);
			
			$insert_array									= array();
			$insert_array['sites_site_id']					= $siteid;
			$insert_array['product_adddate']				= 'now()';
			$insert_array['manufacture_id']					= addslashes(stripslashes($supplier));
			$insert_array['product_name']					= addslashes(stripslashes($name));
			$insert_array['product_shortdesc']				= addslashes(stripslashes($name));
			$insert_array['product_longdesc']				= addslashes(stripslashes($desc));
			$insert_array['product_hide']					= 'N';
			$insert_array['product_costprice']				= $price;
			$insert_array['product_webprice']				= $price;
			$insert_array['product_show_cartlink']			= 1;
			$insert_array['product_show_enquirelink']		= 1;
			
			if($price!='' or $color !='' or $finish!='') // case if atleast one variable exists
			{
				$insert_array['product_variables_exists']		= 'Y';
			}
			else
			{
				$insert_array['product_variables_exists']		= 'N';
			}
			$insert_array['product_default_category_id ']	= $category_id;
			$db->insert_from_array($insert_array,'products');
			$product_id = $db->insert_id();
			
			if($img!='')
			{
				$img 		= basename($img);
				$src_img	= 'pdtimages/'.$img;
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
					$bigimagename 		= $curtime.$bigimage["name"];
					$copy_only			= true;
					$bigimage_path  	= resize_image($bigimage["tmp_name"], "big/".$bigimagename, $geometry["big"], '',$Img_Resize);
					$copy_only 			= true;
					$catimage_path 		= resize_image($bigimage["tmp_name"], "category/".$bigimagename, $geometry["cat"], '',$Img_Resize);
					$copy_only 			= true;
					$icon_path 			= resize_image($bigimage["tmp_name"], "icon/".$bigimagename, $geometry["icon"],  '',$Img_Resize);
					$copy_only 			= true;
					$extralarge_path	= resize_image($bigimage["tmp_name"], "extralarge/".$bigimagename, '',  '',2);// no resize required
					//$copy_only 			= ($sameasbig)?true:false;
					$copy_only          = true;
					$cathumbimage_path 	= resize_image($bigimage["tmp_name"], "category_thumb/".$bigimagename, $geometry["catthumb"], '',$Img_Resize);
					
					$copy_only			= true;
					$thumb_path 		= resize_image($bigimage["tmp_name"], "thumb/".$bigimagename, $geometry["thumb"], '',$Img_Resize);
					
					$copy_only			= true;
					$gallery_thumb_path	= resize_image($bigimage["tmp_name"], "gallerythumb/".$bigimagename, '90>', '',$Img_Resize);
					
					if(!$directory_id)
						$directory_id = 0;
					//Make an entry to the 	
					if($bigimage_path || $thumb_path)
					{
						// Check whether there exists an image directory with the name as "Importe Images"
						$sql_dir = "SELECT directory_id 
										FROM 
											images_directory 
										WHERE 
											directory_name = '".$img_dir_name."' 
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
							$insert_array['directory_name']		= $img_dir_name;
							$db->insert_from_array($insert_array,'images_directory');
							$directory_id = $db->insert_id();
						}
						$atleastone = 1;
						$insert_array = array();
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
						$insert_array							= array();
						$insert_array['products_product_id']	= $product_id;
						$insert_array['images_image_id']		= $image_id;
						$insert_array['image_title']			= addslashes($caption);
						$insert_array['image_order']			= 0;
						$db->insert_from_array($insert_array,'images_product');
					}
				}
			}
			// Make an entry to the product_category_map table		 
			$insert_array 									= array();
			$insert_array['products_product_id']			= $product_id;
			$insert_array['product_categories_category_id']	= $category_id;
			$db->insert_from_array($insert_array,'product_category_map');						
			
			if ($color!='') // case if variable COLOUR is required for current product
			{
				$color_arr 								= explode(',',$color);
				// Create a variable with the name as colour for current product
				$insert_array							= array();
				$insert_array['products_product_id']	= $product_id;
				$insert_array['var_name']				= 'Colour';
				$insert_array['var_value_exists']		= 1;
				$db->insert_from_array($insert_array,'product_variables');
				$var_id = $db->insert_id();
				
				// Insert the respective values for this variable
				for($i=0;$i<count($color_arr);$i++)
				{
					$insert_array								= array();
					$insert_array['product_variables_var_id']	= $var_id;
					$insert_array['var_value']					= addslashes(stripslashes(trim(str_replace('"',"''",$color_arr[$i]))));
					$insert_array['var_addprice']				= 0;
					$insert_array['var_order']					= $i;
					$db->insert_from_array($insert_array,'product_variable_data');
				}
			}
			
			if ($finish != '') // case if variable finish is required for current product
			{
				$finish_arr 								= explode(',',$finish);
				// Create a variable with the name as colour for current product
				$insert_array							= array();
				$insert_array['products_product_id']	= $product_id;
				$insert_array['var_name']				= 'Finish';
				$insert_array['var_value_exists']		= 1;
				$db->insert_from_array($insert_array,'product_variables');
				$var_id = $db->insert_id();
				
				// Insert the respective values for this variable
				for($i=0;$i<count($finish_arr);$i++)
				{
					$insert_array								= array();
					$insert_array['product_variables_var_id']	= $var_id;
					$insert_array['var_value']					= addslashes(stripslashes(trim(str_replace('"',"''",$finish_arr[$i]))));
					$insert_array['var_addprice']				= 0;
					$insert_array['var_order']					= $i;
					$db->insert_from_array($insert_array,'product_variable_data');
				}
			}
			
			if ($size != '') // case if variable size is required for current product
			{
				$size_arr 							= explode(',',$size);
				// Create a variable with the name as colour for current product
				$insert_array							= array();
				$insert_array['products_product_id']	= $product_id;
				$insert_array['var_name']				= 'Size';
				$insert_array['var_value_exists']		= 1;
				$db->insert_from_array($insert_array,'product_variables');
				$var_id = $db->insert_id();
				
				// Insert the respective values for this variable
				for($i=0;$i<count($size_arr);$i++)
				{
					$insert_array								= array();
					$insert_array['product_variables_var_id']	= $var_id;
					$insert_array['var_value']					= addslashes(stripslashes(trim(str_replace('"',"''",$size_arr[$i]))));
					$insert_array['var_addprice']				= 0;
					$insert_array['var_order']					= $i;
					$db->insert_from_array($insert_array,'product_variable_data');
				}
			}
		}
		$i++;
	}
	fclose($fp);
	?>
	<tr>
		<td colspan="3" align="center" style="color:#006600"><strong>----- Products Imported Successfully ------</strong></td>
	</tr>
	<?php
	}
	?>
	<tr>
	</table>
	
	<?php
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
		$new_path = "$image_path/$new";
		if ($copy_only==true)
			$res = copy($old,$new_path);
		else
			$res = move_uploaded_file($old,$new_path);
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