<?php

	include_once('header.php');
	
	
	$import_cat_map			= 'map/category_map.csv';
	
	$i=0;
	
	
	// read the content of csv file
?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<?php
		// Open a csv file to hold the category mappings
		$fp_catmap = fopen($import_cat_map,'w');
		if (!$fp_catmap)
		{
			echo "Cannot open the file for writing";
			exit;
		}
	
	
	// Get the list of all categories from source site
	$sql_cat = "SELECT * FROM product_categories WHERE sites_site_id = $src_siteid";
	$ret_cat = $db->query($sql_cat);
	
	
	
	
		
	fwrite($fp_catmap,'Old Category Id,New Category Id,Parent Id'."\r\n"); // writing the header row
	$atleast_one_err = 0;
	$i =0;
	if ($db->num_rows($ret_cat))
	{
		while ($row_cat = $db->fetch_array($ret_cat))
		{
			$err_msg 											= '';
			
			$insert_array 										= array();
			$insert_array['sites_site_id'] 						= $dest_siteid;
			$insert_array['parent_id'] 							= 0;
			$insert_array['category_name'] 						= addslashes(stripslashes($row_cat['category_name']));
			$insert_array['category_shortdescription'] 			= addslashes(stripslashes($row_cat['category_shortdescription']));
			$insert_array['category_paid_for_longdescription'] 	= addslashes(stripslashes($row_cat['category_paid_for_longdescription']));
			$insert_array['category_paid_description'] 			= addslashes(stripslashes($row_cat['category_paid_description']));
			$insert_array['category_bottom_description'] 		= addslashes(stripslashes($row_cat['category_bottom_description']));
			$insert_array['category_hide'] 						= addslashes(stripslashes($row_cat['category_hide']));
			$insert_array['category_order'] 					= addslashes(stripslashes($row_cat['category_order']));
			$insert_array['category_showimageofproduct'] 		= addslashes(stripslashes($row_cat['category_showimageofproduct']));
			$insert_array['category_turnoff_treemenu'] 			= addslashes(stripslashes($row_cat['category_turnoff_treemenu']));
			$insert_array['category_turnoff_pdf'] 				= addslashes(stripslashes($row_cat['category_turnoff_pdf']));
			$insert_array['category_turnoff_mainimage'] 		= addslashes(stripslashes($row_cat['category_turnoff_mainimage']));
			$insert_array['category_subcatlisttype'] 			= addslashes(stripslashes($row_cat['category_subcatlisttype']));
			$insert_array['category_subcatlistmethod'] 			= addslashes(stripslashes($row_cat['category_subcatlistmethod']));
			$insert_array['subcategory_showimagetype'] 			= addslashes(stripslashes($row_cat['subcategory_showimagetype']));
			$insert_array['product_displaytype'] 				= addslashes(stripslashes($row_cat['product_displaytype']));
			$insert_array['product_displaywhere'] 				= addslashes(stripslashes($row_cat['product_displaywhere']));
			$insert_array['product_orderfield'] 				= addslashes(stripslashes($row_cat['product_orderfield']));
			$insert_array['product_orderby'] 					= addslashes(stripslashes($row_cat['product_orderby']));
			$insert_array['product_showimage'] 					= addslashes(stripslashes($row_cat['product_showimage']));
			$insert_array['product_showtitle'] 					= addslashes(stripslashes($row_cat['product_showtitle']));
			$insert_array['product_showshortdescription'] 		= addslashes(stripslashes($row_cat['product_showshortdescription']));
			$insert_array['product_showprice'] 					= addslashes(stripslashes($row_cat['product_showprice']));
			$insert_array['product_showrating'] 				= addslashes(stripslashes($row_cat['product_showrating']));
			$insert_array['product_showbonuspoints'] 			= addslashes(stripslashes($row_cat['product_showbonuspoints']));
			$insert_array['category_turnoff_moreimages'] 		= addslashes(stripslashes($row_cat['category_turnoff_moreimages']));
			$insert_array['category_turnoff_noproducts'] 		= addslashes(stripslashes($row_cat['category_turnoff_noproducts']));
			$insert_array['category_showname'] 					= addslashes(stripslashes($row_cat['category_showname']));
			$insert_array['category_showshortdesc'] 			= addslashes(stripslashes($row_cat['category_showshortdesc']));
			$insert_array['category_showimage'] 				= addslashes(stripslashes($row_cat['category_showimage']));
			$insert_array['special_detailspage_required'] 		= addslashes(stripslashes($row_cat['special_detailspage_required']));
			$insert_array['google_taxonomy_id'] 				= addslashes(stripslashes($row_cat['google_taxonomy_id']));
			
						
			$db->insert_from_array($insert_array,'product_categories');
			$category_id 	= $db->insert_id();
			
			$parent_id 		= $row_cat['parent_id'];
			$catid 			= $row_cat['category_id'];
			
			fwrite($fp_catmap,"$catid,$category_id,$parent_id"."\r\n"); // writing the header row
		}
	}	
	fclose($fp_catmap);
	
	?>
<tr>
	<td colspan="3" align="center" style="color:#006600"><strong>----- Catagories Imported Successfully ------ <?php echo date('r')?></strong></td>
</tr>
</table>
	
