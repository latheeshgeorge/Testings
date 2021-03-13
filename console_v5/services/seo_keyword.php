<?php
	#################################################################
	# Script Name 	: se_keyword.php
	# Description 	: Action page for Keywords
	# Coded by 		: LHG
	# Created on	: 11-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
if($_REQUEST['fpurpose'] == '') {
	include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_cat') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_prod') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_shop') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_shelf') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_combo') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_stat') {
include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'search_saved') {
include("includes/seo/ad_keywords_list.php");
}
elseif ($_REQUEST['fpurpose'] == 'Assign_keywords')
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
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current category with current unq_id
						$sql_check = "SELECT id FROM se_category_keywords WHERE 
											se_keywords_keyword_id=$curkey AND product_categories_category_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array	= array();
							$insert_array['product_categories_category_id'] 	= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_category_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current category from se_cat_kw table
					$sql_del = "DELETE FROM se_category_keywords WHERE product_categories_category_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				}
				elseif(substr($k,0,7) == 'txtprod')//This section will be executed only in case of properties
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current property with current unq_id
						$sql_check = "SELECT id FROM se_product_keywords WHERE 
											se_keywords_keyword_id=$curkey AND products_product_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array	= array();
							$insert_array['products_product_id'] 	= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_product_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_product_keywords WHERE products_product_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				}
				elseif(substr($k,0,7) == 'txtstat')//This section will be executed only in case of staticpages
				{
					$stat_arr = explode("_",$k);
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_static_keywords WHERE 
											se_keywords_keyword_id=$curkey AND static_pages_page_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['static_pages_page_id'] 		= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_static_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_static_keywords WHERE static_pages_page_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				}elseif(substr($k,0,7) == 'txtshop')
				{
				
					$shop_arr = explode("_",$k);
					$cur_id	 = $shop_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_shop_keywords WHERE 
											se_keywords_keyword_id=$curkey AND product_shopbybrand_shopbrand_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['product_shopbybrand_shopbrand_id'] 		= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_shop_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_shop_keywords WHERE product_shopbybrand_shopbrand_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}elseif(substr($k,0,8) == 'txtshelf')
				{
				
				
					$shelf_arr = explode("_",$k);
					$cur_id	 = $shelf_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_shelf_keywords WHERE 
											se_keywords_keyword_id=$curkey AND product_shelf_shelf_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['product_shelf_shelf_id'] 		= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_shelf_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_shelf_keywords WHERE product_shelf_shelf_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				
				}elseif(substr($k,0,8) == 'txtcombo')
				{
					$shelf_arr = explode("_",$k);
					$cur_id	 = $shelf_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_combo_keywords WHERE 
											se_keywords_keyword_id=$curkey AND combo_combo_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['combo_combo_id'] 		= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							$db->insert_from_array($insert_array, 'se_combo_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_combo_keywords WHERE combo_combo_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				} 
				
				
				elseif(substr($k,0,9) == 'txtsearch')
				{
				
					$stat_arr = explode("_",$k);
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					/*echo "<pre>";
					print_r($stat_arr);*/
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_search_keyword WHERE 
											se_keywords_keyword_id=$curkey AND saved_search_search_id=$cur_id AND uniq_id = '$unq_id' LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['saved_search_search_id'] 		= $cur_id;
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_search_keyword');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_search_keyword WHERE saved_search_search_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}elseif(substr($k,0,14) == 'txtbestsellers'){
				
				 
				
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_bestseller_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_bestseller_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_bestseller_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				
				}
				elseif(substr($k,0,7) == 'txthome')
				{
				 
				
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_home_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_home_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_home_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
				
				elseif(substr($k,0,7) == 'txthelp')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_help_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_help_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_help_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
				elseif(substr($k,0,6) == 'txtfaq')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_help_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_faq_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_faq_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
			elseif(substr($k,0,15) == 'txtregistration'){
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_registration_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_registration_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_registration_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
				elseif(substr($k,0,18) == 'txtsavedsearchmain'){
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current saved search main page with current unq_id
						$sql_check = "SELECT id FROM se_savedsearch_main_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_savedsearch_main_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_savedsearch_main_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
			elseif(substr($k,0,10) == 'txtsitemap')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_sitemap_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_sitemap_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current site from se_property_kw table
					$sql_del = "DELETE FROM se_sitemap_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				}
					elseif(substr($k,0,17) == 'txtforgotpassword')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_forgotpassword_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_forgotpassword_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_forgotpassword_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
			elseif(substr($k,0,14) == 'txtsitereviews')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$ecom_siteid AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							//echo "test";
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v);
							//echo $v; 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $ecom_siteid;
							$insert_array['keyword_keyword'] = addslashes($v);
							$db->insert_from_array($insert_array, 'se_keywords');
							$curkey = $db->insert_id();
						}
						//Check whether the current keyword_id Exists for current static page with current unq_id
						$sql_check = "SELECT id FROM se_sitereviews_keywords WHERE 
											se_keywords_keyword_id=$curkey AND sites_site_id=$cur_id AND uniq_id = '$unq_id'  LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0)
						{
							$insert_array					= array();
							$insert_array['se_keywords_keyword_id']  	= $curkey;
							$insert_array['sites_site_id']  		= $ecom_siteid;
							$insert_array['uniq_id']  		= $unq_id;
							/*echo "<pre>";
							print_r($insert_array);*/
							$db->insert_from_array($insert_array, 'se_sitereviews_keywords');
						}	
					}	
					//Delete all the keywords assigned for the current property from se_property_kw table
					$sql_del = "DELETE FROM se_sitereviews_keywords WHERE  sites_site_id=$cur_id AND uniq_id <> '$unq_id'";
					$db->query($sql_del);
				
				}
				
			}
			$alert = "Keywords updated Successfully";
		
		if($_REQUEST['type_change']==1 && $_REQUEST['retain_val']) //Done to handle the situation of returning back to page directly after saving.
		{
			include("includes/seo/ad_keywords_list.php");
		}
		else
		{
			echo "<br><span class='redtext'><b>$alert</b></span><br>";
			?>
			<br /><a class="smalllink" href="home.php?request=seo_keyword&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&search_name=<?=$_REQUEST['search_name']?>&parentid=<?=$_REQUEST['parentid']?>">Go Back to the Keyword Listing page</a><br /><br />
<?php
		}
}
elseif ($_REQUEST['fpurpose'] =='entire_keywords')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";

	include("includes/seo/list_entire_keywords.php");
} elseif ($_REQUEST['fpurpose'] =='saved_keyword')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/seo/saved_keywords_list.php");
} 

elseif ($_REQUEST['fpurpose'] =='SaveKeywordDesc')
{	

	foreach($_POST['checkbox'] AS $id) {
		$update_array				= array();
		$update_array['search_desc']	= addslashes($_POST["txt_".$id]); 
		$update_array['search_count']	= addslashes($_POST["searchcount_".$id]); 
		//echo addslashes($_POST["txt_".$id]); 
	//	echo "<br>";
		//print_r($update_array);
		$db->update_from_array($update_array, 'saved_search', array('search_id' => $id));
	}
	$alert = 'Details saved successfully';
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/seo/saved_keywords_list.php");
}

elseif ($_REQUEST['fpurpose'] =='delete_entirekeyword')
{
include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Keyword(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				//Remove all assignments for current keyword if any from se_cat_kw table
		$sql_del = 	"DELETE FROM se_category_keywords WHERE se_keywords_keyword_id = ".$ch_arr[$i];
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_property_kw table
		$sql_del = 	"DELETE FROM se_product_keywords WHERE se_keywords_keyword_id =".$ch_arr[$i];
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_static_kw table
		$sql_del = 	"DELETE FROM se_static_keywords WHERE se_keywords_keyword_id = ".$ch_arr[$i];
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_seach_kw table
		$sql_del = 	"DELETE FROM se_search_keyword WHERE se_keywords_keyword_id =".$ch_arr[$i];
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_home_keywords table
		$sql_del = 	"DELETE FROM se_home_keywords WHERE se_keywords_keyword_id =".$ch_arr[$i];
		$db->query($sql_del);
		//Now Deleting from se_keywords table
		$sql_del = 	"DELETE FROM se_keywords WHERE keyword_id = ".$ch_arr[$i];
		$db->query($sql_del);
		
			}
			$alert = 'Keyword Deleted Successfully';
			include("../includes/seo/list_entire_keywords.php");
		}		
	?>
<!--	<a href="home.php?request=seo_keyword&fpurpose=fulllist&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&pg=<?php echo $_REQUEST['pg']?>&sreach_keyword=<?php echo $_REQUEST['sreach_keyword']?>">Go Back to the Full Keyword Listing page</a>
	<br /><br /><a href="home.php?request=seo_keyword&fpurpose=fulllist&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>">Go Back to the Keyword Assigning page</a><br /><br />-->
	<?php
}
elseif($_REQUEST['fpurpose'] == 'Save_entire_keyword')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$alert = '';	
	foreach ($_REQUEST as $k=>$v)
	{
		if(substr($k,0,6) =='txtkey')
		{
			$cur_arr 	= explode("_",$k);
			$cur_id 	= $cur_arr[1];
			if ($cur_id)
			{
				//Check whether any other keyword with the current name exists for current site. if so dont update
				$sql_check = "SELECT keyword_id FROM se_keywords WHERE keyword_keyword='".addslashes($v)."' AND sites_site_id=$ecom_siteid AND 
				keyword_id <> $cur_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
			
					$update_array				= array();
					$update_array['keyword_keyword']	= addslashes($v); 
					$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $ecom_siteid ,  'keyword_id' => $cur_id));
						
				}	
				else
					$alert .= "<br><strong>Error! - </strong>$v - Already Exists - Not Updated";	
				
			}	
		}	
	}
	//echo "<span class='redtext'>$alert<span><br>";
	if(!$alert){
	$alert = "Updated Successfully";
	}
		include ('includes/seo/list_entire_keywords.php');
	?>
		<!--<br /><a href="home.php?request=seo_keyword&fpurpose=fulllist&pg=<?=$_REQUEST['pgs']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&records_per_page=<?php echo $_REQUEST['records_per_pages']?>&pg=<?php echo $_REQUEST['pg']?>&sreach_keyword=<?php echo $_REQUEST['sreach_keyword']?>">Go Back to the Full Keyword Listing pagzxdvce</a><br />-->
	<?php
}
elseif ($_REQUEST['fpurpose'] =='delete_savedkeyword')
{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = "<strong>Error!</strong> Keyword not deleted";
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM saved_search WHERE search_id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Keyword Deleted Successfully';
		}	
	
		include("../includes/seo/saved_keywords_list.php");
} 



elseif ($_REQUEST['fpurpose'] =='verification_code') {
	include("includes/seo/verification_code.php");
} elseif ($_REQUEST['fpurpose'] =='verification_code_saved') {
	$update_array	= array();
	$update_array['is_meta_verificationcode']	= ($_REQUEST['is_meta_verificationcode'])?1:0;
	if($_REQUEST['is_meta_verificationcode'])
	{
		$update_array['meta_verificationcode']	= addslashes($_REQUEST['meta_verificationcode']); 
	}
	$update_array['is_yahoometa_verificationcode']	= ($_REQUEST['is_yahoometa_verificationcode'])?1:0;
	if($_REQUEST['is_yahoometa_verificationcode'])
	{
		$update_array['yahoo_meta_verificationcode']	= addslashes($_REQUEST['yahoo_meta_verificationcode']); 
	}
	$update_array['is_msnmeta_verificationcode']	= ($_REQUEST['is_msnmeta_verificationcode'])?1:0;
	if($_REQUEST['is_msnmeta_verificationcode'])
	{
		$update_array['msn_meta_verificationcode']	= addslashes($_REQUEST['msn_meta_verificationcode']); 
	}
	$update_array['is_twitter_account']	= ($_REQUEST['is_twitter_account'])?1:0;
	if($_REQUEST['is_twitter_account'])
	{
		$update_array['site_twitter_account_id']	= addslashes($_REQUEST['site_twitter_account_id']); 
	}
	$update_array['is_google_urchinwebtracker_code']	= ($_REQUEST['is_google_urchinwebtracker_code'])?1:0;
	if($_REQUEST['is_google_urchinwebtracker_code'])
	{
		$update_array['google_webtracker_urchin_code']	= addslashes($_REQUEST['google_webtracker_urchin_code']); 
		}
	$update_array['is_google_webtracker_code']	= ($_REQUEST['is_google_webtracker_code'])?1:0; 
	if($_REQUEST['is_google_webtracker_code'])
	{
		$update_array['google_webtracker_code']	= addslashes($_REQUEST['google_webtracker_code']); 
	}
	$update_array['is_google_webtracker_ecom']	= ($_REQUEST['is_google_webtracker_ecom'])?1:0; 
	if($_REQUEST['is_google_webtracker_ecom'])
	{
		$update_array['google_ecomtracker_code']	= addslashes($_REQUEST['google_ecomtracker_code']); 
	}
	$update_array['is_google_adword_checkout']	= ($_REQUEST['is_google_adword_checkout'])?1:0; 
	if($_REQUEST['is_google_adword_checkout'])
	{
		$update_array['google_adword_conversion_id']		= addslashes($_REQUEST['google_adword_conversion_id']); 
		$update_array['google_adword_conversion_language']	= addslashes($_REQUEST['google_adword_conversion_language']); 
		$update_array['google_adword_conversion_format']	= addslashes($_REQUEST['google_adword_conversion_format']); 
		$update_array['google_adword_conversion_color']		= addslashes($_REQUEST['google_adword_conversion_color']); 
		$update_array['google_adword_conversion_label']		= addslashes($_REQUEST['google_adword_conversion_label']); 
	}
	$db->update_from_array($update_array, 'sites', array('site_id' => $ecom_siteid));
	$alert .= "<br>Search Engine settings Updated Successfully";	
	echo "<span class='redtext'><b>$alert</b><span><br>";
	?>
	<br /><a class="smalllink" href="home.php?request=seo_keyword&fpurpose=verification_code">Go Back to the Search Engine Settings page</a><br />
	<?php
}
?>
