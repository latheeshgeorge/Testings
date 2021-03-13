<?php
if($_REQUEST['fpurpose']=='') // case of viewing the full image listing page
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	include("includes/image_gallery/list_images.php");
}
elseif ($_REQUEST['fpurpose']=='dir_add') // case of adding new directories
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$fieldRequired 		= array($_REQUEST['newdir_name']);
	$fieldDescription 	= array('directory name');
	$fieldEmail 		= array();
	$fieldConfirm 		= array();
	$fieldConfirmDesc 	= array();
	$fieldNumeric 		= array();
	$fieldNumericDesc 	= array();
	serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	if ($alert=='')
	{
		// Check whether any directory exists under current directory with the same name in current site
		$sql_check = "SELECT directory_id FROM images_directory WHERE sites_site_id = $ecom_siteid AND parent_id = ".$_REQUEST['curdirid']. "
						AND directory_name ='".$_REQUEST['newdir_name']."'";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$alert = "Directory name already exists";
			
		}
		else // case of inserting new directory
		{
			$insert_array					= array();
			$insert_array['parent_id']		= $_REQUEST['curdirid'];
			$insert_array['sites_site_id']	= $ecom_siteid;
			$insert_array['directory_name']	= add_slash($_REQUEST['newdir_name']);
			$db->insert_from_array($insert_array,'images_directory');
			$alert ='Directory Added Successfully';
		}
		create_subdirectory($alert);
	}
	else // case of error
	{
		create_subdirectory($alert);
	}
}
elseif ($_REQUEST['fpurpose']=='subdir_list')// used to refresh the sub directory list using ajax
{	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	if($_REQUEST['subdir_search'])
		$pass_search = trim($_REQUEST['subdir_search']);
	else
		$pass_search = '';
	subdirectory_listing($pass_search);
}
elseif ($_REQUEST['fpurpose']=='image_list') // case of showing the image listing using ajax
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	image_listing($_REQUEST['curdirid'],$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'',$_REQUEST['sel_prods'],$_REQUEST['src_click']);
}
elseif ($_REQUEST['fpurpose']=='show_move_directory_list') // case of showing the move to directory 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_move_directory();
}
elseif ($_REQUEST['fpurpose']=='subdir_update') // case of updating subdirectory using ajax
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$fieldRequired 		= array($_REQUEST['curname']);
	$fieldDescription 	= array('directory name');
	$fieldEmail 		= array();
	$fieldConfirm 		= array();
	$fieldConfirmDesc 	= array();
	$fieldNumeric 		= array();
	$fieldNumericDesc 	= array();
	serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	if ($alert=='')
	{
		// find the parent of current subdirectory
		$sql_parent = "SELECT parent_id FROM images_directory WHERE directory_id=".$_REQUEST['curdirid'];
		$ret_parent = $db->query($sql_parent);
		if ($db->num_rows($ret_parent))
		{
			$row_parent = $db->fetch_array($ret_parent);
			$parent		= $row_parent['parent_id'];
		}
		else
			$parent = 0;
		// Check whether any directory exists under current directory with the same name in current site
		$sql_check = "SELECT directory_id FROM images_directory WHERE sites_site_id = $ecom_siteid AND parent_id = $parent 
						AND directory_name ='".$_REQUEST['curname']."' AND directory_id <>".$_REQUEST['curdirid'];
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$alert = "Directory name already exists";
			
		}
		else // case of inserting new directory
		{
			$update_array					= array();
			$update_array['directory_name']	= add_slash($_REQUEST['curname']);
			$db->update_from_array($update_array,'images_directory',array('directory_id'=>$_REQUEST['curdirid']));
			$alert ='Directory Name Updated Successfully';
		}
	}
	image_listing($_REQUEST['curdirid'],$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'',$_REQUEST['sel_prods']);
}
elseif($_REQUEST['fpurpose']=='subdir_delete')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	if ($alert=='')
	{
		// find the parent of current subdirectory
		$sql_parent = "SELECT parent_id FROM images_directory WHERE directory_id=".$_REQUEST['curdirid'];
		$ret_parent = $db->query($sql_parent);
		if ($db->num_rows($ret_parent))
		{
			$row_parent = $db->fetch_array($ret_parent);
			$parent		= $row_parent['parent_id'];
		}
		else
			$parent = 0;
		// Deleting the selected directory
		$sql_check = "Delete FROM images_directory WHERE directory_id=".$_REQUEST['curdirid'];
		$db->query($sql_check);
		$alert = 'Subdirectory Deleted Successfully.';
	}
	image_listing($parent,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'del',$_REQUEST['sel_prods']);
}
elseif ($_REQUEST['fpurpose']=='upload_images')
{
	if($_REQUEST['mod'] == 'normal')
		include "includes/image_gallery/upload_images.php";
	else
		echo "<span class='maincontent_action'>Ftp Upload not implemented yet..</span>";	
}
elseif ($_REQUEST['fpurpose']=='save_upload')
{
	//Get the dimensions for thumb or big images
	$sql_dimension 		= "SELECT bigimage_geometry,thumbimage_geometry,categoryimage_geometry,
							categorythumbimage_geometry,iconimage_geometry FROM themes WHERE theme_id=$ecom_themeid";
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
	if(!file_exists("$image_path/big")) mkdir("$image_path/big", 0777);
	if(!file_exists("$image_path/thumb")) mkdir("$image_path/thumb", 0777);
	if(!file_exists("$image_path/category")) mkdir("$image_path/category", 0777);
	if(!file_exists("$image_path/category_thumb")) mkdir("$image_path/category_thumb", 0777);
	if(!file_exists("$image_path/extralarge")) mkdir("$image_path/extralarge", 0777);
	if(!file_exists("$image_path/gallerythumb")) mkdir("$image_path/gallerythumb", 0777);
	if(!file_exists("$image_path/icon")) mkdir("$image_path/icon", 0777);
	$atleastone = 0;
	$tab = '<br><br><table width="80%" cellpadding="1" cellspacing="1" border="0" align="center">';
	$tab .= '<tr><td align="left" colspan="3" class="redtext"><b>Image Upload Details</b></td></tr>';
	$tab .= '<tr><td align="center" width="10%" class="listingtableheader">Image</td><td align="left" width="20%" class="listingtableheader">Small Image</td><td class="listingtableheader" align="left" width="20%">Big Image</td></tr>';
	for($i=0;$i<10;$i++)
	{
		$cls = ($i%2==0)?'listingtablestyleB':'listingtablestyleA';
		$bigimage_path 	= $thumb_path = '';
		$caption 		= $_REQUEST["upload_caption_$i"];
		$bigimage 		= $_FILES["upload_big_$i"];			
		$thumb 			= $_FILES["upload_thumb_$i"];
		$directory_id	= $_REQUEST['curdir_id'];
		if($bigimage["name"] || $thumb["name"]) // case if big image or thumb image exists.
		{
			if($bigimage["type"]){
				if($bigimage["type"]=='image/jpeg' || $bigimage["type"]=='image/gif' || $bigimage["type"]=='image/pjpeg'  || $bigimage["type"]=='image/png') 
				{
				$valid_image_type = true;
				}
				else
				{
				 $valid_image_type = false;
				}
			}
			if($thumb["type"]){
				if($thumb["type"]=='image/jpeg' ||  $thumb["type"]=='image/gif' || $thumb["type"]=='image/pjpeg' || $thumb["type"]=='image/png')
				{
				$valid_image_type = true;
				}
				else
				{
				 $valid_image_type = false;
				} 
			}	
			$curtime = date('his');
			$tab .= '<tr><td align="center" width="2%" class="'.$cls.'">Image: '.($i+1).'</td>';
			//echo "<br><span class='maincontent_action'>Image ".($i+1).":</span><br>";
			if(!$bigimage["name"])
			{   $sameasthmb = 1;
				$bigimage = $thumb;
			}
			if(!$thumb["name"])
			{
				$sameasbig = 1;
				$thumb = $bigimage;
			}	
			else
				$sameasbig = 0;
			
			$gallery_thumb = $thumb;	
			// Validate image
			$valid_image = true;
			/*if ($bigimage['size']>301000 or $thumb['size']>301000)
			{
				$valid_image = false;
			}*/
				if($valid_image_type==true)
			    {
					if($valid_image==true)
					{
						// Replacing the unwanted characters from the big image name
						$sr_arr 			= array (" ","'");
						$rp_arr 			= array("_","");
						$bigimage["name"]	= str_replace($sr_arr,$rp_arr,$bigimage["name"]);
						// If no caption, use filename
						if(!$caption) $caption = substr($bigimage["name"], 0, strrpos($bigimage["name"], "."));
						$bigimagename 		= $curtime.$bigimage["name"];
						$copy_only			= true;
						$bigimage_path  	= resize_image($bigimage["tmp_name"], "big/" . $bigimagename, $geometry["big"], $bigimage["type"],$Img_Resize);
						if($bigimage_path) 
							$big_ok = 'Ok';
						else 
							$big_ok = 'Failed';
						
						
						
						if($ecom_siteid==109 or $ecom_siteid==117) // only for unipad
						{
							$extralarge_dimension = '1200x1200';
							$extralarge_resize	  = 1;
						}
						else
						{
							$extralarge_dimension = '';
							$extralarge_resize	  = 2;
						}
						
						$copy_only 			= true;
						$catimage_path 		= resize_image($bigimage["tmp_name"], "category/" . $bigimagename, $geometry["cat"], $bigimage["type"],$Img_Resize);
						$copy_only 			= true;
						$icon_path 			= resize_image($bigimage["tmp_name"], "icon/" . $bigimagename, $geometry["icon"], $bigimage["type"],$Img_Resize);
						$copy_only 			= true;
						$extralarge_path	= resize_image($bigimage["tmp_name"], "extralarge/" . $bigimagename, $extralarge_dimension, $bigimage["type"],$extralarge_resize);// no resize required
						//$copy_only 			= ($sameasbig)?true:false;
						$copy_only          = true;
						$cathumbimage_path 	= resize_image($bigimage["tmp_name"], "category_thumb/" . $bigimagename, $geometry["catthumb"], $bigimage["type"],$Img_Resize);
						
						// Replacing the unwanted characters from the thumb image name
						$sr_arr 			= array (" ","'");
						$rp_arr 			= array("_","");
						$thumb["name"]		= str_replace($sr_arr,$rp_arr,$thumb["name"]);
						$thumbname 			= $curtime.$thumb["name"];
						//$copy_only			= ($sameasthmb)?true:false;
						$copy_only			= true;
						$thumb_path 		= resize_image($thumb["tmp_name"], "thumb/" . $thumbname, $geometry["thumb"], $thumb["type"],$Img_Resize);
						if($thumb_path) 
							$thumb_ok = 'Ok';
						else 
							$thumb_ok = 'Failed';
						$copy_only			= false;
						$gallery_thumb_path	= resize_image($thumb["tmp_name"], "gallerythumb/" . $thumbname, '90>', $thumb["type"],$Img_Resize);
						
						if(!$directory_id)
							$directory_id = 0;
						//Make an entry to the 	
						if($bigimage_path || $thumb_path)
						{ 						

							$atleastone = 1;
							$insert_array = array();
							$insert_array['sites_site_id']					= $ecom_siteid;
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
						}	
						$tab .= '<td align="left" width="20%" class="'.$cls.'">'.$thumb_ok.'</td><td align="left" width="20%" class="'.$cls.'">'.$big_ok.'</td></tr>';
					}
					else
					{
						//$tab = '<td align="left" width="20%">$thumb_ok</td><td align="left" width="20%">$big_ok</td></tr>';
						$tab .= "<td align='left' width='20%' colspan='2' class='".$cls."'><span class='redtext'>Size of Image-".($i+1)." exceeds the Maximum Limit Allowed.</span></td></tr>";
						//echo "<span class='redtext'>Size of Image-$i exceeds the Maximum Limit Allowed.</span><br>";
					}
				}
				else
				{
					$tab .= "<td align='left' width='20%' colspan='2' class='".$cls."'><span class='redtext'><strong>Error:</strong> Invalid Image type. Enter jpeg or gif image.</span></td></tr>";	
					//echo "<span class='redtext'><strong>Error:</strong> Invalid Image type. Enter jpeg or gif image.</span><br>";
				}	
		}
	}
	$tab .= '</table>';
	echo $tab;
	//check whether atleast one image is selected
		if($atleastone==0)
		{
		?>
			<br /><font color="red"><strong>Error:</strong> No Images Uploaded</font>
			<br /><br /><a class="smalllink" href="home.php?request=img_gal&curdir_id=<?php echo $directory_id?>&txt_searchcaption=<?=$_REQUEST['txt_searchcaption']?>&search_option=<?php echo $_REQUEST['search_option']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Image Upload page</a>
		<?php	
		}
		?>
			<br /><br /><a class="smalllink" href="home.php?request=img_gal&curdir_id=<?php echo $directory_id?>&txt_searchcaption=<?=$_REQUEST['txt_searchcaption']?>&search_option=<?php echo $_REQUEST['search_option']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Image Gallery</a><br /><br />
		<?php

}
elseif ($_REQUEST['fpurpose']=='image_changedirectory')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$change_dir = $_REQUEST['ch_dir'];
	$cur_dir	= $_REQUEST['curdirid'];	
	$sel_arr	= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			$update_array									= array();
			$update_array['images_directory_directory_id']	= $change_dir;
			$db->update_from_array($update_array,'images',array('image_id'=>$sel_arr[$i]));
		}
		$alert = "Image(s) Moved Successfully";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	
}
elseif ($_REQUEST['fpurpose']=='image_assigncategory')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_category 	= $_REQUEST['assign_cat'];
	$cur_dir			= $_REQUEST['curdirid'];	
	$sel_arr			= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected category
			$sql_check = "SELECT id FROM images_product_category WHERE product_categories_category_id=$assign_category AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array										= array();
				$insert_array['product_categories_category_id']		= $assign_category;
				$insert_array['images_image_id']					= $sel_arr[$i];
				$insert_array['image_title']						= addslashes($img_title);
				$insert_array['image_order']						= 0;
				$db->insert_from_array($insert_array,'images_product_category');
			}
		}
		$alert = "Image(s) Assigned to Selected Category";
		// Find the parent
		$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$assign_category;
		$ret_par = $db->query($sql_par);
		if ($db->num_rows($ret_par))
		{
			$row_par = $db->fetch_array($ret_par);
			if ($row_par['parent_id']!=0)
				delete_category_cache($row_par['parent_id']);
		}
		delete_category_cache($assign_category);
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	
}
elseif ($_REQUEST['fpurpose']=='image_assignshop')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_shop 	= $_REQUEST['assign_shop'];
	$cur_dir			= $_REQUEST['curdirid'];	
	$sel_arr			= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected category
			$sql_check = "SELECT id FROM images_shopbybrand WHERE product_shopbybrand_shopbrand_id=$assign_shop AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array										= array();
				$insert_array['product_shopbybrand_shopbrand_id']		= $assign_shop;
				$insert_array['images_image_id']					= $sel_arr[$i];
				$insert_array['image_title']						= addslashes($img_title);
				$insert_array['image_order']						= 0;
				$db->insert_from_array($insert_array,'images_shopbybrand');
			}
		}
		$alert = "Image(s) Assigned to Selected Shop";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	
}
elseif ($_REQUEST['fpurpose']=='image_assigncombo')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_combo 	= $_REQUEST['assign_combo'];
	$cur_dir			= $_REQUEST['curdirid'];	
	$sel_arr			= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected combo
			$sql_check = "SELECT id FROM images_combo WHERE combo_combo_id=$assign_combo AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array										= array();
				$insert_array['combo_combo_id']		= $assign_combo;
				$insert_array['images_image_id']	= $sel_arr[$i];
				$insert_array['image_title']		= addslashes($img_title);
				$insert_array['image_order']		= 0;
				$db->insert_from_array($insert_array,'images_combo');
			}
		}
		$alert = "Image(s) Assigned to Selected Combo";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	

}
elseif ($_REQUEST['fpurpose']=='image_assignpaper') // giftwrap paper
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_paper 	= $_REQUEST['assign_paper'];
	$cur_dir		= $_REQUEST['curdirid'];	
	$sel_arr		= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected giftwrap paper
			$sql_check = "SELECT id FROM images_giftwrap_paper WHERE giftwrap_paper_paper_id=$assign_paper AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array								= array();
				$insert_array['giftwrap_paper_paper_id']	= $assign_paper;
				$insert_array['images_image_id']			= $sel_arr[$i];
				$insert_array['image_title']				= addslashes($img_title);
				$insert_array['image_order']				= 0;
				$db->insert_from_array($insert_array,'images_giftwrap_paper');
			}
		}
		$alert = "Image(s) Assigned to Selected Giftwrap Paper";
	}
	else
	{
		$alert = "Please select the images";
	}
	if ($_REQUEST['retsrc'] != 'edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	

}
elseif ($_REQUEST['fpurpose']=='image_assigncard') // giftwrap card
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_card 	= $_REQUEST['assign_card'];
	$cur_dir		= $_REQUEST['curdirid'];	
	$sel_arr		= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected giftwrap card
			$sql_check = "SELECT id FROM images_giftwrap_card WHERE giftwrap_card_card_id=$assign_card AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array								= array();
				$insert_array['giftwrap_card_card_id']		= $assign_card;
				$insert_array['images_image_id']			= $sel_arr[$i];
				$insert_array['image_title']				= addslashes($img_title);
				$insert_array['image_order']				= 0;
				$db->insert_from_array($insert_array,'images_giftwrap_card');
			}
		}
		$alert = "Image(s) Assigned to Selected Giftwrap Card";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);	

}
elseif ($_REQUEST['fpurpose']=='image_assignribbon') // giftwrap ribbon
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_ribbon 	= $_REQUEST['assign_ribbon'];
	$cur_dir		= $_REQUEST['curdirid'];	
	$sel_arr		= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected giftwrap ribbon
			$sql_check = "SELECT id FROM images_giftwrap_ribbon WHERE giftwrap_ribbon_ribbon_id=$assign_ribbon AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array								= array();
				$insert_array['giftwrap_ribbon_ribbon_id']	= $assign_ribbon;
				$insert_array['images_image_id']			= $sel_arr[$i];
				$insert_array['image_title']				= addslashes($img_title);
				$insert_array['image_order']				= 0;
				$db->insert_from_array($insert_array,'images_giftwrap_ribbon');
			}
		}
		$alert = "Image(s) Assigned to Selected Giftwrap Ribbon";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);
}
elseif ($_REQUEST['fpurpose']=='image_assignbow') // giftwrap bow
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$assign_bow 	= $_REQUEST['assign_bow'];
	$cur_dir		= $_REQUEST['curdirid'];	
	$sel_arr		= explode('~',$_REQUEST['sel_prods']);
	if (count($sel_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Check whether this image is already assigned to selected giftwrap bow
			$sql_check = "SELECT id FROM images_giftwrap_bow WHERE giftwrap_bows_bow_id=$assign_bow AND 
			images_image_id = ".$sel_arr[$i]." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				// Get the title of current image from images table
				$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
					$row_img = $db->fetch_array($ret_img);
					$img_title = $row_img['image_title'];
				}
				else
					$img_title = '';
				$insert_array								= array();
				$insert_array['giftwrap_bows_bow_id']	= $assign_bow;
				$insert_array['images_image_id']			= $sel_arr[$i];
				$insert_array['image_title']				= addslashes($img_title);
				$insert_array['image_order']				= 0;
				$db->insert_from_array($insert_array,'images_giftwrap_bow');
			}
		}
		$alert = "Image(s) Assigned to Selected Giftwrap Bow";
	}
	else
	{
		$alert = "Please select the images";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);
}
elseif ($_REQUEST['fpurpose']=='sel_prod_for_cat') // Case of coming to shown the page to select products under a particular category
{
		$sel_category 	= $_REQUEST['sel_category'];
		$sel_arr		= explode('~',$_REQUEST['sel_prods']);
		if (count($sel_arr))
		{
			include 'includes/image_gallery/list_sel_products.php';
		}
		else
		{
			$alert = "Please select the images";
		}
}
elseif ($_REQUEST['fpurpose']=='save_sel_prod_for_cat')
{

	$sel_arr			= explode('~',$_REQUEST['sel_prods']);
	$selprod_arr		= explode('~',$_REQUEST['sel_catprods']);
	if (count($sel_arr) and count($selprod_arr))
	{
		for($i=0;$i<count($sel_arr);$i++)
		{
			// Get the title of current image from images table
			$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i];
			$ret_img = $db->query($sql_img);
			if ($db->num_rows($ret_img))
			{
				$row_img 		= $db->fetch_array($ret_img);
				$current_title	= $row_img['image_title'];
			}
			for($j=0;$j<count($selprod_arr);$j++)
			{
				// Check whether current image is already assigned to current product
				$sql_check = "SELECT id FROM images_product WHERE products_product_id =".$selprod_arr[$j]." AND 
								images_image_id =".$sel_arr[$i];
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check)==0)
				{
					
					$insert_array							= array();
					$insert_array['products_product_id']	= $selprod_arr[$j];
					$insert_array['images_image_id']		= $sel_arr[$i];
					$insert_array['image_title']			= addslashes($current_title);
					$insert_array['image_order']			= 0;
					$db->insert_from_array($insert_array,'images_product');
				}
			}
		}
		$list_alert = "Images Assigned to products";
		
		if($_REQUEST['retsrc']!='edit')	
		{
			$_REQUEST['sel_prods']			= '';
			$_REQUEST['pg']					= $_REQUEST['pgs'];
			$_REQUEST['txt_searchcaption']	= $_REQUEST['src_caption'];
			$_REQUEST['search_option']		= $_REQUEST['src_option'];
			$_REQUEST['records_per_page']	= $_REQUEST['recs'];
			$ajax_return_function 			= 'ajax_return_contents';
			include "ajax/ajax.php";
			include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
			include("includes/image_gallery/list_images.php");
		}	
		else
		{
			$list_alert = "Image Assigned to product(s)";
			$_REQUEST['edit_id']			= $_REQUEST['sel_prods'];
			$ajax_return_function 			= 'ajax_return_contents';
			include "ajax/ajax.php";
			include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
			include 'includes/image_gallery/edit_image.php';
		}	
	}
	else
	{
		$alert = "Please select the products";
		include 'includes/image_gallery/list_sel_products.php';
	}
}
elseif($_REQUEST['fpurpose']=='edit_img')
{
	$ajax_return_function 			= 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	include 'includes/image_gallery/edit_image.php';
}
elseif ($_REQUEST['fpurpose']=='show_imageedit_general')// general tab Ajax
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	imageedit_general($_REQUEST['edit_id']);
}
elseif ($_REQUEST['fpurpose']=='show_imageedit_operation')// operation tab Ajax
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	imageedit_operation($_REQUEST['edit_id']);
}
elseif ($_REQUEST['fpurpose']=='show_imageedit_assigned')// assigned tab Ajax
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	imageedit_assigned($_REQUEST['edit_id']);
}	
elseif($_REQUEST['fpurpose']=='save_imageedit_general')
{
	$edit_id 	= $_REQUEST['edit_id'];
	// Get the details of current image from images table
	$sql_img = "SELECT * FROM images WHERE image_id=$edit_id";
	$ret_img = $db->query($sql_img);
	if ($db->num_rows($ret_img))
	{
		$row_img = $db->fetch_array($ret_img);
	}

	//Get the dimensions for thumb or big images
	$sql_dimension 		= "SELECT bigimage_geometry,thumbimage_geometry,categoryimage_geometry,
							categorythumbimage_geometry,iconimage_geometry FROM themes WHERE theme_id=$ecom_themeid";
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
	if(!file_exists("$image_path/big")) mkdir("$image_path/big", 0777);
	if(!file_exists("$image_path/thumb")) mkdir("$image_path/thumb", 0777);
	if(!file_exists("$image_path/category")) mkdir("$image_path/category", 0777);
	if(!file_exists("$image_path/category_thumb")) mkdir("$image_path/category_thumb", 0777);
	if(!file_exists("$image_path/extralarge")) mkdir("$image_path/extralarge", 0777);
	if(!file_exists("$image_path/gallerythumb")) mkdir("$image_path/gallerythumb", 0777);
	if(!file_exists("$image_path/icon")) mkdir("$image_path/icon", 0777);

	$bigimage_path 	= $thumb_path = '';
	$caption		= trim($_REQUEST['txt_caption']);
	$bigimage 		= $_FILES["file_big"];			
	$thumb 			= $_FILES["file_thumb"];
	$extra 			= $_FILES["file_extra"];
	$resizebig		= ($_REQUEST['resize_full'])?1:0;
	$resizethumb	= ($_REQUEST['resize_thumb'])?1:0;
	//Updating the caption
	$update_array	= array();
	$update_array['image_title'] = add_slash($caption);
	$db->update_from_array($update_array,'images',array('image_id'=>$edit_id));
	
	//Handling the case of images
	if($bigimage["name"]) // case if big image exists.
	{
		if($bigimage["type"]){
				if($bigimage["type"]=='image/jpeg' || $bigimage["type"]=='image/gif' || $bigimage["type"]=='image/pjpeg'  || $bigimage["type"]=='image/png') 
				{
				$valid_image_type = true;
				}
				else
				{
				 $valid_image_type = false;
				}
			}
			
		
		
		$gallery_thumb = $thumb;	
		// Validate image
		$valid_image = true;	
		/*if ($bigimage['size']>301000 or $thumb['size']>301000)
		{
			$valid_image = false;
		}*/
	if($valid_image_type==true)
	{
		if($valid_image==true)
		{
			// Replacing the unwanted characters from the big image name
			$sr_arr 			= array (" ","'");
			$rp_arr 			= array("_","");
			$bigimage["name"]	= str_replace($sr_arr,$rp_arr,$bigimage["name"]);
			$bigname_arr		= explode('big/',$row_img['image_bigpath']);
			$bigimagename 		= $bigname_arr[1];
			$copy_only			= true;
			$bigimage_path  	= resize_image($bigimage["tmp_name"], "big/" . $bigimagename, $geometry["big"], $bigimage["type"],$resizebig,true);
			if($bigimage_path) $dd =  "Full image ok.<br>";
			else $dd = "Failed to resize full image.<br>";
			$copy_only 			= true;
			$catimage_path 		= resize_image($bigimage["tmp_name"], "category/" . $bigimagename, $geometry["cat"], $bigimage["type"],1,true);
			$copy_only 			= true;
			//$extralarge_path	= resize_image($bigimage["tmp_name"], "extralarge/" . $bigimagename, '', $bigimage["type"],2,true);// no resize required
			$copy_only 			= true;
			$cathumbimage_path 	= resize_image($bigimage["tmp_name"], "category_thumb/" . $bigimagename, $geometry["catthumb"], $bigimage["type"],1,true);
			$copy_only 			= true;
			$icon_path 		= resize_image($bigimage["tmp_name"], "icon/" . $bigimagename, $geometry["icon"], $bigimage["type"],1,true);
			//Make an entry to the 	
			if($bigimage_path)
			{
				$update_array									= array();
				$update_array['image_bigpath']						= $bigimage_path;
				$update_array['image_bigcategorypath']			= $catimage_path;
				$update_array['image_thumbcategorypath']	= $cathumbimage_path;
				//$update_array['image_extralargepath']			= $extralarge_path;
				$update_array['image_iconpath']					= $icon_path;
				$db->update_from_array($update_array,'images',array('image_id'=>$edit_id));	
			}	
		}
		else
		{
			echo "<span class='redtext'>Size of Image exceeds the Maximum Limit Allowed.</span><br>";
		}
	 }
  else
	{
		echo "<span class='redtext'>Image type mismatch.Use gif,jpeg or png Images</span><br>";
	}		
	}
	if($thumb["name"]) // case if thumb image exists.
	{
		
		if($thumb["type"]){
				if($thumb["type"]=='image/jpeg' ||  $thumb["type"]=='image/gif' || $thumb["type"]=='image/pjpeg' || $thumb["type"]=='image/png')
				{
				$valid_thumbimage_type = true;
				}
				else
				{
				 $valid_thumbimage_type = false;
				} 
			}
		 
		// Validate image
		$valid_image = true;	
		/*if ($bigimage['size']>301000 or $thumb['size']>301000)
		{
			$valid_image = false;
		}*/
		if($valid_thumbimage_type == true)
		{	
			if($valid_image==true)
			{
				
				// Replacing the unwanted characters from the thumb image name
				$sr_arr 			= array (" ","'");
				$rp_arr 			= array("_","");
				//$thumb["name"]	= str_replace($sr_arr,$rp_arr,$thumb["name"]);
				$thumb["name"]		= str_replace($sr_arr,$rp_arr,$thumb["name"]);
				$thumbname_arr		= explode('thumb/',$row_img['image_thumbpath']);
				$thumbname 			= $thumbname_arr[1];
				//$thumbname 		= $curtime.$thumb["name"];
				$copy_only			= true;
				$thumb_path 		= resize_image($thumb["tmp_name"], "thumb/".$thumbname, $geometry["thumb"], $thumb["type"],$resizethumb,true);
				if($thumb_path) $dd= "Small Image ok.<br>";
				else $dd = "<span class='redtext'>Failed to resize thumbnail.</span><br>";
				
				$copy_only			= false;
				$gallery_thumb_path	= resize_image($thumb["tmp_name"], "gallerythumb/".$thumbname, '90>', $thumb["type"],1,true);
				//Make an entry to the 	
				if($thumb_path)
				{
					$update_array									= array();
					$update_array['image_thumbpath']				= $thumb_path;
					$update_array['image_gallerythumbpath']			= $gallery_thumb_path;
					$db->update_from_array($update_array,'images',array('image_id'=>$edit_id));	
				}	
			}
			else
			{   
				echo "<span class='redtext'>Size of Thumb Image exceeds the Maximum Limit Allowed.</span><br>";
			}
		}
		else
		{ 
			echo "<span class='redtext'>Thumb Image type mismatch.Use gif,jpeg or png.</span><br>";
		}	
	}
	
	if($extra["name"]) // case if thumb image exists.
	{
		
		if($extra["type"]){
				if($extra["type"]=='image/jpeg' ||  $extra["type"]=='image/gif' || $extra["type"]=='image/pjpeg' || $extra["type"]=='image/png')
				{
				$valid_extraimage_type = true;
				}
				else
				{
				 $valid_extraimage_type = false;
				} 
			}
		 
		// Validate image
		$valid_image = true;	
		/*if ($bigimage['size']>301000 or $thumb['size']>301000)
		{
			$valid_image = false;
		}*/
		if($valid_extraimage_type == true)
		{	
			if($valid_image==true)
			{
				
				// Replacing the unwanted characters from the thumb image name
				$sr_arr 			= array (" ","'");
				$rp_arr 			= array("_","");
				//$thumb["name"]	= str_replace($sr_arr,$rp_arr,$thumb["name"]);
				$extra["name"]		= str_replace($sr_arr,$rp_arr,$extra["name"]);
				if(strpos($row_img['image_extralargepath'],'extralarge/')===false)
				{
					$extraname 			= $row_img['image_extralargepath'];
				}
				else
				{
					$extraname_arr		= explode('extralarge/',$row_img['image_extralargepath']);
					$extraname 			= $extraname_arr[1];
				}	
				//$thumbname 		= $curtime.$thumb["name"];
				$copy_only			= true;
				
				if($ecom_siteid==109 or $ecom_siteid==117) // only for unipad
				{
					$extralarge_dimension = '1200x1200';
					$extralarge_resize	  = 1;
				}
				else
				{
					$extralarge_dimension = '';
					$extralarge_resize	  = 2;
				}
				
				$extralarge_path	= resize_image($extra["tmp_name"], "extralarge/".$extraname, $extralarge_dimension, $extra["type"],$extralarge_resize,false);// no resize required
				if($extralarge_path) $dd= "Full Size Image ok.<br>";
				else $dd = "<span class='redtext'>Failed to resize Full Size Image.</span><br>";
				//Make an entry to the 	
				if($extralarge_path)
				{
					$update_array											= array();
					$update_array['image_extralargepath']		= $extralarge_path;

					$db->update_from_array($update_array,'images',array('image_id'=>$edit_id));	
				}	
			}
			else
			{   
				echo "<span class='redtext'>Size of Full Size Image exceeds the Maximum Limit Allowed.</span><br>";
			}
		}
		else
		{ 
			echo "<span class='redtext'>Full Size Image type mismatch.Use gif,jpeg or png.</span><br>";
		}	
	}
	
			$alert = "General Information Saved Successfully";
			$ajax_return_function 			= 'ajax_return_contents';
			include "ajax/ajax.php";
			include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
			$_REQUEST['curtab']	= 'show_imageedit_general';
			include 'includes/image_gallery/edit_image.php';
}
elseif($_REQUEST['fpurpose']=='list_prodcat') // lising of product categoris for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_productcategory_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='list_prodshop') // lising of product categoris for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_shop_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_prodcat') // unassign image from selected product categories
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_product_category WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Product Categories ';
	}
	else
		$alert = 'No Product Categories Selected';
	show_imageassign_to_productcategory_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='unassign_prodshop') // unassign image from selected product categories
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_shopbybrand WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Shops ';
	}
	else
		$alert = 'No shop Selected';
	show_imageassign_to_shop_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_prods') // lising of products for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_products_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_prods') // unassign image from selected products
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_product WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Product(s) ';
	}
	else
		$alert = 'No Products Selected';
	show_imageassign_to_products_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_combo') // lising of combos for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_combo_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_combo') // unassign image from selected combos
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_combo WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Combo Deal(s) ';
	}
	else
		$alert = 'No Combo Deals Selected';
	show_imageassign_to_combo_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_paper') // lising of giftwrap paper for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_paper_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_paper') // unassign image from selected giftwrap paper
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_giftwrap_paper WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Giftwrap Paper(s) ';
	}
	else
		$alert = 'No Giftwrap Papers Selected';
	show_imageassign_to_paper_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_card') // lising of giftwrap card for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_card_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_card') // unassign image from selected giftwrap card
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_giftwrap_card WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Giftwrap Cards(s) ';
	}
	else
		$alert = 'No Giftwrap Cards Selected';
	show_imageassign_to_card_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_ribbon') // lising of giftwrap ribbon for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_ribbon_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_ribbon') // unassign image from selected giftwrap ribbon
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_giftwrap_ribbon WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Giftwrap ribbon(s) ';
	}
	else
		$alert = 'No Giftwrap ribbons Selected';
	show_imageassign_to_ribbon_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_bow') // lising of giftwrap bow for Assigned to tab in image edit page
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	show_imageassign_to_bow_list($_REQUEST['edit_id']);
}
elseif($_REQUEST['fpurpose']=='unassign_bow') // unassign image from selected giftwrap bow
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$del_arr = explode("~",$_REQUEST['del_ids']);
	if (count($del_arr))
	{
		for($i=0;$i<count($del_arr);$i++)
		{
			$sql_del = "DELETE FROM images_giftwrap_bow WHERE id =".$del_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image Successfully unassigned from selected Giftwrap bow(s) ';
	}
	else
		$alert = 'No Giftwrap bow Selected';
	show_imageassign_to_bow_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='delete_image')
{
	$del_str 	= trim($_REQUEST['sel_prods']);
	if($del_str!='' and $_REQUEST['from_ajax']==1) // case of calling using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include_once "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		$del_arr = explode('~',$_REQUEST['sel_prods']);
		for($i=0;$i<count($del_arr);$i++)
		{
			$del_id = $del_arr[$i];
			delete_selected_images($del_id);
		}
		$alert = 'Image(s) Deleted Successfully';
		image_listing($_REQUEST['curdirid'],$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	}
	else
	{
		$del_id = $_REQUEST['edit_id'];
		delete_selected_images($del_id);
		$alert = 'Image Deleted Successfully';
		echo "
			<script>
				window.location ='home.php?request=img_gal&txt_searchcaption=".$_REQUEST['src_caption']."&search_option=".$_REQUEST['src_option']."&records_per_page=".$_REQUEST['recs']."&pg=".$_REQUEST['pgs']."&curdir_id=".$_REQUEST['curdir_id']."&sel_prods=".$_REQUEST['sel_prods']."&alert=".$alert."'
			</script>
		";
		exit;
	}	
}
elseif ($_REQUEST['fpurpose']=='image_assignremote')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include "../includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	$alert='';
	$src_id		 	= $_REQUEST['src_id'];
	$src_page	 	= $_REQUEST['src_page'];
	$cur_dir			= $_REQUEST['curdirid'];	
	$cur_mod		= $_REQUEST['curmod'];
	$sel_arr			= explode('~',$_REQUEST['sel_prods']);
	$combid			= $_REQUEST['combid'];
	$strs				= $_REQUEST['pass_strs'];
	if (count($sel_arr))
	{
		switch($cur_mod)
		{
			case 'prodcomb_common':
				// Get the list of all combination of current product
				$sql = "SELECT comb_id 
								FROM 
									product_variable_combination_stock  
								WHERE 
									products_product_id = $src_id ";
				$ret = $db->query($sql);
				if ($db->num_rows($ret))
				{
					while ($row = $db->fetch_array($ret))
					{
						$atleast_one_comb_img= 0; 
						for($i=0;$i<count($sel_arr);$i++)
						{
								// Check whether this image is already assigned to selected product combination
							$sql_check = "SELECT id 
													FROM 
														images_variable_combination 
													WHERE 
														comb_id=".$row['comb_id']."
														AND images_image_id = ".$sel_arr[$i]." 
													LIMIT 
														1";
							$ret_check = $db->query($sql_check);
							if ($db->num_rows($ret_check)==0)
							{
								// Get the title of current image from images table
								$sql_img = "SELECT image_title 
														FROM 
															images 
														WHERE 
															image_id=".$sel_arr[$i]." 
														LIMIT 
															1";
								$ret_img = $db->query($sql_img);
								if ($db->num_rows($ret_img))
								{
									$row_img = $db->fetch_array($ret_img);
									$img_title = $row_img['image_title'];
								}
								else
									$img_title = '';
								$insert_array								= array();
								$insert_array['comb_id']				= $row['comb_id'];
								$insert_array['images_image_id']	= $sel_arr[$i];
								$insert_array['image_title']			= addslashes($img_title);
								$insert_array['image_order']			= 0;
								$db->insert_from_array($insert_array,'images_variable_combination');
								$atleast_one_comb_img = 1;
							}	
						}
						if ($atleast_one_comb_img==1)
						{
							$update_sql = "UPDATE product_variable_combination_stock   
													SET 
														comb_img_assigned = 1
													WHERE 
														comb_id = ".$row['comb_id']."
													LIMIT 
														1";
							$db->query($update_sql);
						}
						
					}
				}
			break;
		};
		$atleast_one_comb_img = 0;
		for($i=0;$i<count($sel_arr);$i++)
		{
			switch($cur_mod)
			{
				case 'prod': // case of assigning images to products
				case 'listprod':
					// Check whether this image is already assigned to selected product
					$sql_check = "SELECT id FROM images_product WHERE products_product_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array								= array();
						$insert_array['products_product_id']		= $src_id;
						$insert_array['images_image_id']			= $sel_arr[$i];
						$insert_array['image_title']				= addslashes($img_title);
						$insert_array['image_order']				= 0;
						$db->insert_from_array($insert_array,'images_product');
					}	
				break;
				case 'googleprod': // case of assigning images to products
					// Check whether this image is already assigned to selected product
					$sql_check = "SELECT id FROM images_googlefeed_product WHERE products_product_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array								= array();
						$insert_array['products_product_id']		= $src_id;
						$insert_array['images_image_id']			= $sel_arr[$i];
						$insert_array['image_title']				= addslashes($img_title);
						$insert_array['image_order']				= 0;
						$db->insert_from_array($insert_array,'images_googlefeed_product');
					}	
				break;
				case 'prodvarimg': // product variable image assiginig
					$sql_update = "UPDATE 
										product_variable_data 
									SET 
										images_image_id = ".$sel_arr[0]." 
									WHERE 
										var_value_id = ".$src_id." 
									LIMIT 
										1";
					$db->query($sql_update);
				break;
				case 'presetvarimg': // product variable image assiginig
					$sql_update = "UPDATE 
										product_preset_variable_data 
									SET 
										images_image_id = ".$sel_arr[0]." 
									WHERE 
										var_value_id = ".$src_id." 
									LIMIT 
										1";
					$db->query($sql_update);
				break;
				case 'add_colorimg': // product variable color assiginig
					$sql_update = "UPDATE 
										general_settings_site_colors  
									SET 
										images_image_id = ".$sel_arr[0]." 
									WHERE 
										color_id = ".$src_id." 
									LIMIT 
										1";
					$db->query($sql_update);
				break;
				case 'tab': // case of assigning images to product tabs
					// Check whether this image is already assigned to selected tab
					$sql_check = "SELECT id FROM images_product_tab WHERE product_tabs_tab_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array								= array();
						$insert_array['product_tabs_tab_id']		= $src_id;
						$insert_array['images_image_id']			= $sel_arr[$i];
						$insert_array['image_title']				= addslashes($img_title);
						$insert_array['image_order']				= 0;
						$db->insert_from_array($insert_array,'images_product_tab');
					}	
				break;
				case 'prodcat': // case of assigning images to product categories
				case 'listprodcat':
					// Check whether this image is already assigned to selected product category
					$sql_check = "SELECT id FROM images_product_category WHERE product_categories_category_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['product_categories_category_id']	= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i];
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_product_category');
						// Find the parent
						$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$src_id;
						$ret_par = $db->query($sql_par);
						if ($db->num_rows($ret_par))
						{
							$row_par = $db->fetch_array($ret_par);
							if ($row_par['parent_id']!=0)
								delete_category_cache($row_par['parent_id']);
						}
						delete_category_cache($src_id);
					}	
				break;
				
				case 'prodshop': // case of assigning images to product categories
				case 'listprodshop':	
					// Check whether this image is already assigned to selected product category
					$sql_check = "SELECT id FROM images_shopbybrand WHERE product_shopbybrand_shopbrand_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array										= array();
						$insert_array['product_shopbybrand_shopbrand_id']	= $src_id;
						$insert_array['images_image_id']					= $sel_arr[$i];
						$insert_array['image_title']						= addslashes($img_title);
						$insert_array['image_order']						= 0;
						$db->insert_from_array($insert_array,'images_shopbybrand');
					}	
				break;
				
				
				case 'gift_bow':  //case of assigning images to giftwrap bows
					 // Check whether this image is already assigned to selected bow
					$sql_check = "SELECT id FROM images_giftwrap_bow WHERE giftwrap_bows_bow_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['giftwrap_bows_bow_id']			= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i];
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_giftwrap_bow');
					}	
				break;
				case 'gift_card':  //case of assigning images to giftwrap cards
					 // Check whether this image is already assigned to selected card
					$sql_check = "SELECT id FROM images_giftwrap_card WHERE giftwrap_card_card_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['giftwrap_card_card_id']			= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i];
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_giftwrap_card');
					}	
				break;
				case 'gift_ribbon':  //case of assigning images to giftwrap ribbons
					 // Check whether this image is already assigned to selected ribbon
					$sql_check = "SELECT id FROM images_giftwrap_ribbon WHERE giftwrap_ribbon_ribbon_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['giftwrap_ribbon_ribbon_id']			= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i];
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_giftwrap_ribbon');
					}	
				break;
				case 'gift_paper':  //case of assigning images to giftwrap papers
					 // Check whether this image is already assigned to selected paper
					$sql_check = "SELECT id FROM images_giftwrap_paper WHERE giftwrap_paper_paper_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['giftwrap_paper_paper_id']		= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i];
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_giftwrap_paper');
					}	
				break;
				case 'comb_img':  //case of assigning images to combo deal
					 // Check whether this image is already assigned to selected deal
					$sql_check = "SELECT id FROM images_combo WHERE combo_combo_id=$src_id AND 
					images_image_id = ".$sel_arr[$i]." LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['combo_combo_id']					= $src_id;
						$insert_array['images_image_id']				= $sel_arr[$i]; 
						$insert_array['image_title']					= addslashes($img_title);
						$insert_array['image_order']					= 0;
						$db->insert_from_array($insert_array,'images_combo');
					}	
				break;
				case 'pay_type':  //case of assigning images to payment type
						$update_array									= array();
						$update_array['images_image_id']				= $sel_arr[$i]; 
						$db->update_from_array($update_array,'payment_types_forsites',array('paytype_forsites_id'=>$src_id));
				break;
				case 'mainshop':  //case of assigning images to  SSL
					 // Check whether this image is already assigned to SSL
					/*$sql_check = "SELECT id FROM images_ssl WHERE sites_site_id=$ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title FROM images WHERE image_id=".$sel_arr[$i]." LIMIT 1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array									= array();
						$insert_array['sites_site_id']					= $ecom_siteid;
						$insert_array['images_image_id']				= $sel_arr[$i]; 
						$db->insert_from_array($insert_array,'images_ssl');
					}
					else
					{
					    $update_array									= array();
						$update_array['images_image_id']				= $sel_arr[$i]; 
						$db->update_from_array($update_array,'images_ssl',array('sites_site_id'=>$ecom_siteid));
					}	*/
						$update_array									= array();
						$update_array['payment_method_sites_image_id']	= $sel_arr[$i]; 
						$db->update_from_array($update_array,'payment_methods_forsites',array('payment_methods_forsites_id'=>$_REQUEST['payment_methods_forsites_id'],'sites_site_id'=>$ecom_siteid));
				break;
				case 'prod_combo': // case of assigning images to product combinations
					// Check whether this image is already assigned to selected product combination
					$sql_check = "SELECT id 
											FROM 
												images_variable_combination 
											WHERE 
												comb_id=$combid 
												AND images_image_id = ".$sel_arr[$i]." 
											LIMIT 
												1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						// Get the title of current image from images table
						$sql_img = "SELECT image_title 
												FROM 
													images 
												WHERE 
													image_id=".$sel_arr[$i]." 
												LIMIT 
													1";
						$ret_img = $db->query($sql_img);
						if ($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_title = $row_img['image_title'];
						}
						else
							$img_title = '';
						$insert_array								= array();
						$insert_array['comb_id']				= $combid;
						$insert_array['images_image_id']	= $sel_arr[$i];
						$insert_array['image_title']			= addslashes($img_title);
						$insert_array['image_order']			= 0;
						$db->insert_from_array($insert_array,'images_variable_combination');
						$atleast_one_comb_img = 1;
					}	
				break;
			};
		}
		$alert = "Image(s) Assigned Successfully.";
		if ($atleast_one_comb_img==1)
		{
			$update_sql = "UPDATE product_variable_combination_stock   
									SET 
										comb_img_assigned = 1
									WHERE 
										comb_id = $combid 
									LIMIT 
										1";
			$db->query($update_sql);
		}
	}
	else
	{
		$alert = "Please select the image(s)";
	}
	if($_REQUEST['retsrc']!='edit')
		image_listing($cur_dir,$_REQUEST['src_caption'],$_REQUEST['src_option'],$_REQUEST['recs'],$_REQUEST['pg'],$alert,'','');
	else
		imageedit_operation($_REQUEST['sel_prods'],$alert);
}

function delete_selected_images($del_id)
{
	global $db,$ecom_hostname,$ecom_siteid,$image_path;
	// ########################################################
	// Physically deleting the file
	// ########################################################
	$sql_img = "SELECT * FROM images WHERE image_id=$del_id";
	$ret_img = $db->query($sql_img);
	if ($db->num_rows($ret_img))
	{
		$row_img = $db->fetch_array($ret_img);
		if ($row_img['image_bigpath']!='')
		{
			if (file_exists($image_path.'/'.$row_img['image_bigpath']))
				unlink($image_path.'/'.$row_img['image_bigpath']);
		}		
		if ($row_img['image_thumbpath']!='')
		{
			if (file_exists($image_path.'/'.$row_img['image_thumbpath']))
				unlink($image_path.'/'.$row_img['image_thumbpath']);	
		}
		if ($row_img['image_bigcategorypath']!='')
		{
			if (file_exists($image_path.'/'.$row_img['image_bigcategorypath']))
				unlink($image_path.'/'.$row_img['image_bigcategorypath']);
		}
		if ($row_img['image_thumbcategorypath']!='')
		{
			if (file_exists($image_path.'/'.$row_img['image_thumbcategorypath']))
				unlink($image_path.'/'.$row_img['image_thumbcategorypath']);
		}
		if ($row_img['image_extralargepath']!='')
		{	
			if (file_exists($image_path.'/'.$row_img['image_extralargepath']))
				unlink($image_path.'/'.$row_img['image_extralargepath']);	
		}
		if ($row_img['image_gallerythumbpath']!='')
		{	
			if (file_exists($image_path.'/'.$row_img['image_gallerythumbpath']))
				unlink($image_path.'/'.$row_img['image_gallerythumbpath']);	
		}
		if ($row_img['image_iconpath']!='')
		{	
			if (file_exists($image_path.'/'.$row_img['image_iconpath']))
				unlink($image_path.'/'.$row_img['image_iconpath']);		
		}	
		// ######################################################################
		// Deleting respective entry from database
		// ######################################################################		
		$sql_del = "DELETE FROM images_combo WHERE images_image_id = $del_id";
		$db->query($sql_del);
	
		$sql_del = "DELETE FROM images_giftwrap_bow WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_giftwrap_card WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_giftwrap_paper WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_giftwrap_ribbon WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_product WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_product_category WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_product_tab WHERE images_image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM images_shopbybrand WHERE images_image_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM images_ssl WHERE images_image_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM images_variable_combination WHERE images_image_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM images WHERE image_id = $del_id";
		$db->query($sql_del);	
		
		$sql_update = "UPDATE product_variable_data 
						SET 
							images_image_id = 0 
						WHERE 
							images_image_id = $del_id";
		$db->query($sql_update);
		$sql_update = "UPDATE general_settings_site_colors  
						SET 
							images_image_id = 0 
						WHERE 
							images_image_id = $del_id";
		$db->query($sql_update);
		
	}	
}	
?>