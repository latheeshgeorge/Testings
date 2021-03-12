<?php
	set_time_limit(0);
	include_once('header.php');
	
	$product_map		= 'map/product_map.csv';
	$category_map		= 'map/category_map.csv';
	$static_map			= 'map/static_map.csv';
	
	$fp_prodmap = fopen($product_map,'r');
	if (!$fp_prodmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$prod_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_prodmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$prod_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_prodmap);
	
	$fp_catmap = fopen($category_map,'r');
	if (!$fp_catmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$cat_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_catmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$cat_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_catmap);
	
	$fp_statmap = fopen($static_map,'r');
	if (!$fp_statmap)
	{
		echo "Cannot open the file for writing";
		exit;
	}
	$stat_arr = array();
	$i=0;
	while (($data = fgetcsv($fp_statmap, 1000, ",")) !== FALSE)
	{
		if($i!=0)
		{
			$stat_arr[$data[0]]= $data[1];
		}
		$i++;
	}	
	fclose($fp_statmap);
		
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		$sql_rev = "SELECT * FROM adverts WHERE sites_site_id = $src_siteid";
		$ret_rev = $db->query($sql_rev);
		if($db->num_rows($ret_rev))
		{
			while ($row_rev = $db->fetch_array($ret_rev))
			{
				$insert_array 								= array();
				$insert_array['sites_site_id'] 				= $dest_siteid;
				$insert_array['advert_order'] 				= addslashes(stripslashes($row_rev['advert_order']));
				$insert_array['advert_hide'] 				= addslashes(stripslashes($row_rev['advert_hide']));
				$insert_array['advert_showinall'] 			= addslashes(stripslashes($row_rev['advert_showinall']));
				$insert_array['advert_showinhome'] 			= addslashes(stripslashes($row_rev['advert_showinhome']));
				$insert_array['advert_source'] 				= addslashes(stripslashes($row_rev['advert_source']));
				$insert_array['advert_link'] 				= addslashes(stripslashes($row_rev['advert_link']));
				
				$insert_array['advert_type'] 				= addslashes(stripslashes($row_rev['advert_type']));
				$insert_array['advert_target'] 				= addslashes(stripslashes($row_rev['advert_target']));
				$insert_array['advert_title'] 				= addslashes(stripslashes($row_rev['advert_title']));
				$insert_array['advert_activateperiodchange']= addslashes(stripslashes($row_rev['advert_activateperiodchange']));
				$insert_array['advert_displaystartdate'] 	= addslashes(stripslashes($row_rev['advert_displaystartdate']));
				$insert_array['advert_displayenddate'] 		= addslashes(stripslashes($row_rev['advert_displayenddate']));
				$insert_array['advert_rotate_height'] 		= addslashes(stripslashes($row_rev['advert_rotate_height']));
				$insert_array['advert_rotate_speed'] 		= addslashes(stripslashes($row_rev['advert_rotate_speed']));
				$db->insert_from_array($insert_array,'adverts');
				
				$advert_id = $db->insert_id();
				$advertid = $row_rev['advert_id'];
				
				if($row_rev['advert_type']=='ROTATE')
				{
					$sql_rot = "SELECT * FROM advert_rotate WHERE adverts_advert_id = $advertid";
					$ret_rot = $db->query($sql_rot);
					if($db->num_rows($ret_rot))
					{
						while ($row_rot = $db->fetch_array($ret_rot))
						{
							$insert_array 								= array();
							$insert_array['adverts_advert_id'] 			= $advert_id;
							$insert_array['rotate_image'] 				= addslashes(stripslashes($row_rot['rotate_image']));
							$insert_array['rotate_link'] 				= addslashes(stripslashes($row_rot['rotate_link']));
							$insert_array['rotate_order'] 				= addslashes(stripslashes($row_rot['rotate_order']));
							$insert_array['rotate_alttext'] 			= addslashes(stripslashes($row_rot['rotate_alttext']));
							$db->insert_from_array($insert_array,'advert_rotate');
							
							if(!file_exists(ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/adverts/rotate/'))
							{
								mkdir(ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/adverts/rotate/');
								chmod(ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/adverts/rotate/',0777);
							}	
							
							$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/adverts/rotate/'.$row_rot['rotate_image'];
							$dest_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/adverts/rotate/'.$row_rot['rotate_image'];
							if(!copy($src_img,$dest_img))
								echo '<br>Error - rotate image - '.$row_rot['rotate_image'];
						}
					}
				}	
				elseif($row_rev['advert_type']=='IMG')
				{
					$src_img = ORG_DOCROOT.'/images/'.getmydomainname($src_siteid).'/adverts/'.$row_rev['advert_source'];
					$dest_img = ORG_DOCROOT.'/images/'.getmydomainname($dest_siteid).'/adverts/'.$row_rev['advert_source'];
					if(!copy($src_img,$dest_img))
							echo '<br>Error - '.$row_rev['row_rev'];
				}			
				
				// display category
				$sql_disp = "SELECT * FROM advert_display_category WHERE adverts_advert_id = $advertid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 				= $dest_siteid;
						$insert_array['adverts_advert_id'] 				= $advert_id;
						$insert_array['product_categories_category_id'] = $cat_arr[$row_disp['product_categories_category_id']];
						$insert_array['advert_display_category_hide'] 	= addslashes(stripslashes($row_disp['advert_display_category_hide']));
						$db->insert_from_array($insert_array,'advert_display_category');
					}
				}
				
				// display products
				$sql_disp = "SELECT * FROM advert_display_product WHERE adverts_advert_id = $advertid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 				= $dest_siteid;
						$insert_array['adverts_advert_id'] 				= $advert_id;
						$insert_array['products_product_id'] 			= $prod_arr[$row_disp['products_product_id']];
						$insert_array['advert_display_product_hide'] 	= addslashes(stripslashes($row_disp['advert_display_product_hide']));
						$db->insert_from_array($insert_array,'advert_display_product');
					}
				}
				
				// display static pages
				$sql_disp = "SELECT * FROM advert_display_static WHERE adverts_advert_id = $advertid";
				$ret_disp = $db->query($sql_disp);
				if($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{
						$insert_array 									= array();
						$insert_array['sites_site_id'] 				= $dest_siteid;
						$insert_array['adverts_advert_id'] 				= $advert_id;
						$insert_array['static_pages_page_id'] 			= $stat_arr[$row_disp['static_pages_page_id']];
						$insert_array['advert_display_static_hide'] 	= addslashes(stripslashes($row_disp['advert_display_static_hide']));
						$db->insert_from_array($insert_array,'advert_display_static');
					}
				}


				
			}
		}
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Advert Copied Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
