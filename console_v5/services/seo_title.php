<?php
	#################################################################
	# Script Name 	: seo_titles.php
	# Description 	: Action page for Site Titles
	# Coded by 		: Latheesh
	# Created on	: 10-Sep-2007
	# Modified by	: Anu
	# Modified On	: 
	#################################################################
if($_REQUEST['fpurpose'] == '') {
	include("includes/seo/ad_sitetitles_list.php");
} 
elseif($_REQUEST['fpurpose'] == 'search_cat') {

include("includes/seo/ad_sitetitles_list.php");
}elseif($_REQUEST['fpurpose'] == 'search_prod') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_shop') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_shelf') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_combo') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_stat') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_saved') {
include("includes/seo/ad_sitetitles_list.php");
}
elseif ($_REQUEST['fpurpose'] == 'Assign_titles')
{
	if($_REQUEST['type_change']==1 && $_REQUEST['retain_val'])
		$keytype = $_REQUEST['retain_val'];
	else
		$keytype = $_REQUEST['cbo_keytype'];

	$unq_id = uniqid("");
	
			foreach($_REQUEST as $k=>$v)
			{
				if(substr($k,0,6) == 'txtcat') //This section will be executed only in case of categories
				{
			
					$cat_arr = explode("_",$k);
					$cur_id	 = $cat_arr[1];
					$cur_val = $v;
					//Check whether the current there exists a record for current category in current site
					$sql_check = "SELECT id FROM se_category_title WHERE 
										 product_categories_category_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['product_categories_category_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetacat_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_category_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetacat_'.$cur_id]);
						$db->update_from_array($update_array, 'se_category_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtprod')//This section will be executed only in case of properties
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_product_title WHERE 
										 products_product_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetaprod_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_product_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetaprod_'.$cur_id]);
						$db->update_from_array($update_array, 'se_product_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
						if($_REQUEST['is_apparel_site']==1)
						{
							
						$update_array			= array();
						$update_array['apparel_agegroup']  = addslashes($_REQUEST['txtage_'.$cur_id]);
						$update_array['apparel_gender']  = addslashes($_REQUEST['txtgender_'.$cur_id]);
						$update_array['apparel_color']  = addslashes($_REQUEST['txtcolour_'.$cur_id]);
						$update_array['apparel_size']  = addslashes($_REQUEST['txtsize_'.$cur_id]);
						$db->update_from_array($update_array, 'products', array('sites_site_id' => $ecom_siteid ,  'product_id' => $cur_id));
					     }

					}
				}elseif(substr($k,0,7) == 'txthome'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_home_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetahome_']);
						$db->insert_from_array($insert_array, 'se_home_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetahome_']);
						$db->update_from_array($update_array, 'se_home_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,7) == 'txthelp'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_help_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetahelp_']);
						$db->insert_from_array($insert_array, 'se_help_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetahelp_']);
						$db->update_from_array($update_array, 'se_help_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,6) == 'txtfaq'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_faq_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetafaq_']);
						$db->insert_from_array($insert_array, 'se_faq_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetafaq_']);
						$db->update_from_array($update_array, 'se_faq_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,15) == 'txtregistration'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_registration_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetaregistration_']);
						$db->insert_from_array($insert_array, 'se_registration_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetaregistration_']);
						$db->update_from_array($update_array, 'se_registration_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,18) == 'txtsavedsearchmain'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_savedsearchmain_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtsavedsearchmain_']);
						$db->insert_from_array($insert_array, 'se_savedsearchmain_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetasavedsearchmain_']);
						$db->update_from_array($update_array, 'se_savedsearchmain_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,10) == 'txtsitemap'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_sitemap_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetasitemap_']);
						$db->insert_from_array($insert_array, 'se_sitemap_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetasitemap_']);
						$db->update_from_array($update_array, 'se_sitemap_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,17) == 'txtforgotpassword'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_forgotpassword_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetaforgotpassword_']);
						$db->insert_from_array($insert_array, 'se_forgotpassword_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetaforgotpassword_']);
						$db->update_from_array($update_array, 'se_forgotpassword_title', array('sites_site_id' => $ecom_siteid ,'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,14) == 'txtsitereviews'){
					
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_sitereviews_title WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetasitereviews_']);
						$db->insert_from_array($insert_array, 'se_sitereviews_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetasitereviews_']);
						$db->update_from_array($update_array, 'se_sitereviews_title', array('sites_site_id' => $ecom_siteid ,'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,14) == 'txtbestsellers'){
				
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_bestseller_titles WHERE 
										  sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						//$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetabestsellers_']);
						$db->insert_from_array($insert_array, 'se_bestseller_titles');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetabestsellers_']);
						$db->update_from_array($update_array, 'se_bestseller_titles', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				
				}
				elseif(substr($k,0,8) == 'txtshelf')//This section will be executed only in case of shelf
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_shelf_title WHERE 
										 product_shelf_shelf_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['product_shelf_shelf_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetashelf_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_shelf_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetashelf_'.$cur_id]);
						$db->update_from_array($update_array, 'se_shelf_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtshop')//This section will be executed only in case of properties
				{
				
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_shop_title WHERE 
										 product_shopbybrand_shopbrand_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['product_shopbybrand_shopbrand_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetashop_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_shop_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetashop_'.$cur_id]);
						$db->update_from_array($update_array, 'se_shop_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,8) == 'txtcombo')//This section will be executed only in case of properties
				{
				
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_combo_title WHERE 
										 combo_combo_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['combo_combo_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetacombo_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_combo_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetacombo_'.$cur_id]);
						$db->update_from_array($update_array, 'se_combo_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,8) == 'txtsaved')//This section will be executed only in case of Saved search
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_search_title WHERE 
										 saved_search_search_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['saved_search_search_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetasaved_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_search_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetasaved_'.$cur_id]);
						$db->update_from_array($update_array, 'se_search_title', array('sites_site_id' => $ecom_siteid ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtstat')//This section will be executed only in case of staticpages
				{
					$stat_arr = explode("_",$k);
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_static_title WHERE 
										 static_pages_page_id=$cur_id AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['static_pages_page_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$insert_array['meta_description']  		= addslashes($_REQUEST['txtmetastat_'.$cur_id]);
						$db->insert_from_array($insert_array, 'se_static_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$update_array['meta_description']  		= addslashes($_REQUEST['txtmetastat_'.$cur_id]);
						$db->update_from_array($update_array, 'se_static_title', array('sites_site_id' => $ecom_siteid ,'id' => $row_check['id']));
					}
				}
				
			}
			$alert = "Titles and Descriptions Saved Successfully";
		
		if($_REQUEST['type_change']==1 && $_REQUEST['retain_val']) //Done to handle the situation of returning back to page directly after saving.
		{
			include("includes/seo/ad_sitetitles_list.php");
		}
		else
		{
			if($_REQUEST['TitleReturn'])
			{
			include("includes/seo/ad_sitetitles_list.php");
			}
			else
			{
			echo "<br><span class='redtext'><b>$alert</b></span><br>";
			?>
			<br /><a class="smalllink" href="home.php?request=seo_title&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&search_name=<?=$_REQUEST['search_name']?>&parentid=<?=$_REQUEST['parentid']?>">Go Back to the Title and Description Listing page</a><br /><br />
		    
<?php
			}
		}
}
?>
