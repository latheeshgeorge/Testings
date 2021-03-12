<?php
    set_time_limit(0);
	include_once('header.php');
	$prods_file 		= "combined1.csv";//$_REQUEST['prod_file'];
	$import_prod_map	= 'prductcode_map/prductcode_map.csv';
	$import_prod		= "productimg_csv/$prods_file";

	// Local variables
	//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4');
	// Live variables
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');
	
	define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	
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
	
	// Open a csv file to hold the product mappings
	$fp_prodmap = fopen($import_prod_map,'r');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	
	$fp_feat = fopen($import_prod,'r');
	if (!$fp_feat)
	{
		echo "Cannot open the file product";
		exit;
	}
	
	$i=0;
	while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
	{
		if($i!=0) // done to avoid header row and also the categories with hidden status set to Y
		{
			$prodid						= trim($data[0]);
			$productcode						= trim($data[1]);
			$prodmapping_arr[$productcode] 	= $prodid; 
		}
		$i++;
	}
	fclose($fp_prodmap);
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
		<td align="center" style="color:#006600"><strong>#</strong></td>
		<td align="center" style="color:#006600"><strong>Product Id</strong></td>
		<td align="left" style="color:#006600"><strong>Product Code</strong></td>
		<td align="center" style="color:#006600"><strong>Product Name</strong></td>
		<td align="left" style="color:#006600"><strong>Status</strong></td>
	</tr>
	<?php
	$cnt = 1;
		$i = 0;
		$prev_id = 0;
		$diff_i = 1;
		$image_arr = array();
		$sr_arr = array('"',"'");
		$rp_Arr = array("''",'');
		$img_splitarry = array();
		$not_existarr = array();
		while (($data = fgetcsv($fp_feat, 2000, ",")) !== FALSE)
		{   $totcnt = count($data);
			if($i>0)
			{
				$err_msg 		= '';
				$code			= trim(stripslashes($data[0]));
				$name			= trim(stripslashes($data[1]));
				
				$img 			= array();
				for($j=2;$j<=$totcnt;$j++)
				{
					if($data[$j]!='')
					{	$image 			= '';
			            $img_splitarry  = array(); 					
						$image 			= trim(stripslashes($data[$j]));
						$img_splitarry  = explode('/',$image);
						array_push($img,$img_splitarry[6]);
					}
				  
				}				
				// get the new product id
				$product_id		= $prodmapping_arr[$code];
				if($product_id !='')
				{
					$image_arr[$product_id]['img']  = $img;
					$image_arr[$product_id]['name'] = addslashes(str_replace($sr_arr,$rp_arr,$name));
					$image_arr[$product_id]['code'] = $code;

				}
				else
				{
				 $not_existarr[] = $code;
				}
			}
			$i++;
		}	
		if (count($image_arr))
		{
			$diff_i=1;
			foreach ($image_arr as $k=>$v)
			{
				//$err_msg = '';
				$product_id 	= $k;
				$sql_prod = "SELECT product_name,product_default_category_id 
									FROM 
										products 
									WHERE 
										product_id = $product_id 
										AND sites_site_id = $siteid 
									LIMIT 
										1";
					$ret_prod = $db->query($sql_prod);
					if($db->num_rows($ret_prod))
					{
						$row_prod 	= $db->fetch_array($ret_prod);
						$defcatid	= $row_prod['product_default_category_id'];
						$image_arr_product = $v['img'];
						if(count($image_arr_product)>0)
						{
							//$img_arr = array();
							//echo "<pre>";
							//print_r($image_arr_product);
							$cnt_img = 0;
							foreach($image_arr_product as $k1=>$v1)
							{
							$cnt_img++;
							$err_msg = '';
							//$img_arr	= explode(',',$v1);	
							$name 		= $v['name'];
							$code 		= $v['code'];
								//if(count($img_arr))// and $diff_i<=50)
								{  
									 
									//for($img_id=0;$img_id<count($img_arr);$img_id++)
									{ 
										//if($img_arr[$img_id]!='')
										{
										    $err_msg .= $cnt_img." ";
											$err_msg .= save_image($v1,$product_id,$cnt_img);
										}	
									
									}
								}
							}
						}
					}
					else
						$err_msg = '-- Product Does not exists in database';	
				//if($prev_id!=$product_id)
				{
				?>
					<tr>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $diff_i?></td>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $product_id?></td>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $code?></td>
					<td align="center" style="color:#FFFFFF; background-color:#666666"><?php echo $name?></td>
					<td align="left" style="color:#FFFFFF; background-color:#666666">&nbsp;</td>
				</tr>
				<?php
					$prev_id = $prodid;
					$diff_i++;
				}
				?>
				<tr>
					<td align="center" style="color:#000000">"</td>
					<td align="center" style="color:#000000">"</td>
					<td align="center" style="color:#000000">"</td>
					<td align="center" style="color:#000000">"</td>
					<td align="left" style="color:#000000" width="15%"><?php echo $err_msg?></td>
				</tr>
		<?php		
			}
		}
		if(count($not_existarr)>0)
		{
		    foreach ($not_existarr as $k=>$v)
			{
		?>		
		
		<tr>
					<td align="center" style="color:#000000">"</td>
					<td align="center" style="color:#000000">"</td>
					<td align="center" style="color:#000000"><?php echo $v?></td>
					<td align="center" style="color:#000000">"</td>
					<td align="left" style="color:#000000" width="15%">Code not exists!!!</td>
		</tr>
		 <?php

			}
		}
		?>
		</table>
		<?php	

		
		function save_image($img_arr,$product_id,$cnt_img)
		{
			global $db,$siteid,$geometry;
			
			$Img_Resize = 1;
			
			$src_img	= 'pdtimages/'.$img_arr;
			if(file_exists($src_img))
			{
				save_org_image($img_arr,$product_id,$cnt_img);
				$ret_msg = "Image Saved Successfully<br>";
			}
			else
				$ret_msg = $img_arr.'- Image does not exists';
			
			return $ret_msg;
			
		}
		
		function save_org_image($img_arr,$product_id,$cnt_img)
		{
			global $db,$geometry,$siteid;
			$src_img				= 'pdtimages/'.$img_arr;
			$bigimage["name"] 		= uniqid('').'_'.$img_arr;
			$bigimage["tmp_name"]	= $src_img;
			$sr_arr 				= array (" ","'");
			$rp_arr 				= array("_","");
			$bigimage["name"]		= str_replace($sr_arr,$rp_arr,$bigimage["name"]);
			// If no caption, use filename
			$caption 			= '';
			$bigimagename 		= $curtime.$bigimage["name"];

			$copy_only			= true;
			$bigimage_path  	= resize_image($bigimage["tmp_name"], "big/".$bigimagename, $geometry["big"], '',1);
			$copy_only 			= true;
			$catimage_path 		= resize_image($bigimage["tmp_name"], "category/".$bigimagename, $geometry["cat"], '',1);
			$copy_only 			= true;
			$icon_path 			= resize_image($bigimage["tmp_name"], "icon/".$bigimagename, $geometry["icon"],  '',1);
			$copy_only 			= true;
			$extralarge_path	= resize_image($bigimage["tmp_name"], "extralarge/".$bigimagename, '',  '',2);// no resize required
			//$copy_only 			= ($sameasbig)?true:false;
			$copy_only          = true;
			$cathumbimage_path 	= resize_image($bigimage["tmp_name"], "category_thumb/".$bigimagename, $geometry["catthumb"], '',1);
			
			$copy_only			= true;
			$thumb_path 		= resize_image($bigimage["tmp_name"], "thumb/".$bigimagename, $geometry["thumb"], '',1);
			
			$copy_only			= true;
			$gallery_thumb_path	= resize_image($bigimage["tmp_name"], "gallerythumb/".$bigimagename, '90>', '',1);
			
			
			//Make an entry to the 	
			if($bigimage_path || $thumb_path)
			{
				// Check whether there exists an image directory with the name as default category of current product
				/*$sql_dir = "SELECT directory_id 
								FROM 
									images_directory 
								WHERE 
									directory_name = '".addslashes($catname)."' 
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
					$insert_array['directory_name']		= addslashes($catname);
					$db->insert_from_array($insert_array,'images_directory');
					$directory_id = $db->insert_id();
				}*/
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
				$insert_array['images_directory_directory_id']	= 0;//$directory_id;
				$db->insert_from_array($insert_array,'images');	
				$image_id = $db->insert_id();
				
				// Map this image with current product
				$insert_array					= array();
				$insert_array['products_product_id']	= $product_id;
				$insert_array['images_image_id']		= $image_id;
				$insert_array['image_title']			= '';
				$insert_array['image_order']			= $cnt_img;
				$db->insert_from_array($insert_array,'images_product');
				
				$err_msg .= 'Done'; 
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
			$copy_only = true;
			$new_path = "$image_path/$new";
			if ($copy_only==true)
			{
				
				$res = copy($old,$new_path);
			}	
			/*else
				$res = move_uploaded_file($old,$new_path);*/
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
