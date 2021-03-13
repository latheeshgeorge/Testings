<?php
$cbo_sites= $_REQUEST['cbo_sites'];
if($_REQUEST['fpurpose'] == '') {
	include("includes/seo/list_seo.php");
}
elseif($_REQUEST['fpurpose'] == 'AssignKeyword')
{
	include("includes/seo/ad_keywords_list.php");
}
elseif($_REQUEST['fpurpose'] == 'AssignTitle')
{
	include("includes/seo/ad_title_list.php");
}
elseif($_REQUEST['fpurpose'] == 'staticpage') 
{
	include("includes/seo/list_staticpages.php");
}
elseif($_REQUEST['fpurpose'] == 'add_stat_page') 
{
include("includes/seo/add_staticpages.php");
}

elseif($_REQUEST['fpurpose'] == 'edit_stat_page') 
{
	$page_id = $_REQUEST['page_id'];
	$cbo_sites = $_REQUEST['cbo_sites'];
include("includes/seo/edit_staticpages.php");
}

elseif($_REQUEST['fpurpose'] == 'deletestat') 
{
	$page_id = $_REQUEST['page_id'];
	
	$sql = "DELETE FROM static_pages 
				WHERE page_id = $page_id";
	$res = $db->query($sql);
				
	include("includes/seo/list_staticpages.php");
}
else if($_REQUEST['fpurpose'] == 'stat_update') {
	if($_REQUEST['Submit'])
	{
		$cbo_sites = $_REQUEST['cbo_sites'];
		$page_id = $_REQUEST['page_id'];

			$alert				= '';
		$fieldRequired 		= array($_REQUEST['title']);
		$fieldDescription 	= array('Page Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM static_pages WHERE title = '".trim(add_slash($_REQUEST['title']))."' AND sites_site_id=$cbo_sites AND page_id<>".$_REQUEST['page_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Page Name Already exists '; 
		if(!$alert) {
			$update_array 							= array();
			if($_REQUEST['pname']!='Home'){
			$update_array['title']					= trim(add_slash($_REQUEST['title']));
			}
			else
			{
			 $alert ='<span class="redtext">Home page title cannot be changed!</span><br>';
			}
			//$update_array['pname']					= add_slash($_REQUEST['pname']);
			$update_array['content']				= add_slash($_REQUEST['content'],'true');
			$update_array['hide']					= $_REQUEST['hide'];
			$update_array['page_type']				= $_REQUEST['page_type'];
			$update_array['page_link']				= add_slash($_REQUEST['page_link']);
			$update_array['page_link_newwindow']	= add_slash($_REQUEST['page_link_newwindow']);
			$db->update_from_array($update_array, 'static_pages', 'page_id', $_REQUEST['page_id']);
			
			#Page Group
			
			#Deleting existing records
			$sql_del = "DELETE FROM static_pagegroup_static_page_map WHERE static_pages_page_id=".$_REQUEST['page_id'];;
			$db->query($sql_del);
			if(count($page_group)>0)
			{
				foreach($page_group as $v)
				{
					$insert_array 								= array();
					$insert_array['static_pages_page_id']		= $_REQUEST['page_id'];
					$insert_array['static_pagegroup_group_id']	= $v;
					$db->insert_from_array($insert_array, 'static_pagegroup_static_page_map');
				}
			
			}
			$alert .= '<br><span class="redtext"><b>Static Page Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=seo&fpurpose=staticpage&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=seo&fpurpose=edit_stat_page&page_id=<?=$_REQUEST['page_id']?>&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Page</a>
			<br /><br />
			<a href="home.php?request=seo&fpurpose=add_stat_page&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Page</a></center>
			
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/clients/edit_staticpages.php");
		}
		
	}
}
elseif($_REQUEST['fpurpose'] == 'stat_insert') 
{

	if($_REQUEST['Submit'])
	{
		$cbo_sites = $_REQUEST['cbo_sites'];
		$page_id = $_REQUEST['page_id'];
		
			
		$alert				= '';
		$fieldRequired 		= array($_REQUEST['title']);
		$fieldDescription 	= array('Page Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM static_pages WHERE title = '".trim(add_slash($_REQUEST['title']))."' AND sites_site_id=$cbo_sites";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Page Title Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['title']					= add_slash($_REQUEST['title']);
			$insert_array['pname']					= add_slash($_REQUEST['title']);
			$insert_array['content']				= add_slash($_REQUEST['content'],'true');
			$insert_array['hide']					= $_REQUEST['hide'];
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['page_type']				= $_REQUEST['page_type'];
			$insert_array['page_link']				= add_slash($_REQUEST['page_link']);
			$insert_array['page_link_newwindow']	= add_slash($_REQUEST['page_link_newwindow']);
			$db->insert_from_array($insert_array, 'static_pages');
			$insert_id = $db->insert_id();
			#Page Groups Position
			if(count($page_group)>0)
			{
				foreach($page_group as $v)
				{
					$insert_array 								= array();
					$insert_array['static_pages_page_id']		= $insert_id;
					$insert_array['static_pagegroup_group_id']	= $v;
					$db->insert_from_array($insert_array, 'static_pagegroup_static_page_map');
				}
			}
			$alert .= '<br><span class="redtext"><b>Page added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=seo&fpurpose=staticpage&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=seo&fpurpose=edit_stat_page&page_id=<?=$insert_id?>&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Page</a>
			<br /><br />
			<a href="home.php?request=seo&fpurpose=add_stat_page&cbo_sites=<?=$_REQUEST['cbo_sites']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add New Page</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/seo/edit_staticpages.php");
		}
   }
}

elseif($_REQUEST['fpurpose'] == 'Assign_titles')
{
	if($_REQUEST['type_change']==1 && $_REQUEST['retain_val'])
		$keytype = $_REQUEST['retain_val'];
	else
		$keytype = $_REQUEST['cbo_keytype'];

	$unq_id = uniqid("");
			foreach($_REQUEST as $k=>$v)
			{
				if(substr($k,0,7) == 'txthome'){
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_home_title WHERE 
										  sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_home_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				
				}	
			
				elseif(substr($k,0,6) == 'txtcat') //This section will be executed only in case of categories
				{
					$cat_arr = explode("_",$k);
					$cur_id	 = $cat_arr[1];
					$cur_val = $v;
					//Check whether the current there exists a record for current category in current site
					$sql_check = "SELECT id FROM se_category_title WHERE 
										 product_categories_category_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_category_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtprod')//This section will be executed only in case of properties
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_product_title WHERE 
										 products_product_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['products_product_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$db->insert_from_array($insert_array, 'se_product_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$db->update_from_array($update_array, 'se_product_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtstat')//This section will be executed only in case of staticpages
				{
					$stat_arr = explode("_",$k);
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_static_title WHERE 
										 static_pages_page_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $ecom_siteid;
						$insert_array['static_pages_page_id'] 	= $cur_id;
						$insert_array['title']  		= addslashes($v);
						$db->insert_from_array($insert_array, 'se_static_title');
					}	
					else
					{
						$row_check				= $db->fetch_array($ret_check);
						$update_array			= array();
						$update_array['title']  = addslashes($v);
						$db->update_from_array($update_array, 'se_static_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,7) == 'txtshop')//This section will be executed only in case of properties
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_shop_title WHERE 
										 product_shopbybrand_shopbrand_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_shop_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,8) == 'txtcombo')//This section will be executed only in case of properties
				{
				
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_combo_title WHERE 
										 combo_combo_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_combo_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,8) == 'txtshelf')//This section will be executed only in case of shelf
				{
					$prop_arr = explode("_",$k);
					$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_shelf_title WHERE 
										 product_shelf_shelf_id=$cur_id AND sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_shelf_title', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				}
				elseif(substr($k,0,14) == 'txtbestsellers'){
				
					//$prop_arr = explode("_",$k);
					//$cur_id	 = $prop_arr[1];
					$cur_val = $v;
					//Check whether exists a record for current property in the table
					$sql_check = "SELECT id FROM se_bestseller_titles WHERE 
										  sites_site_id = $cbo_sites LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0)
					{
						$insert_array	= array();
						$insert_array['sites_site_id'] 		= $cbo_sites;
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
						$db->update_from_array($update_array, 'se_bestseller_titles', array('sites_site_id' => $cbo_sites ,  'id' => $row_check['id']));
					}
				
				}
				
			}
		$alert = '<center><font color="red"><b>Site Titles Saved Successfully</b></font><br>';
		if($_REQUEST['type_change']==1 && $_REQUEST['retain_val']) //Done to handle the situation of returning back to page directly after saving.
		{
			include("includes/seo/ad_title_list.php");
		}
		else
		{
			echo "<br><span class='redtext'>$alert</span><br>";
			?>
			<br /><a href="home.php?request=seo&fpurpose=AssignTitle&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&cbo_sites=<?=$cbo_sites?>">Go Back to the Title Listing page</a><br /><br />
<?php
		}
}

elseif($_REQUEST['fpurpose'] == "AssKey")
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
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$cbo_sites AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $cbo_sites;
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
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$cbo_sites AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $cbo_sites;
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
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$cbo_sites AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $cbo_sites;
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
				} elseif(substr($k,0,9) == 'txtsearch')
				{
				
					$stat_arr = explode("_",$k);
					$cur_id	 = $stat_arr[1];
					$cur_val = $v;
					/*echo "<pre>";
					print_r($stat_arr);*/
					if($v)
					{
						//Check whether the current keyword already exists for current site
						$sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$cbo_sites AND keyword_keyword ='".addslashes($v)."'";
						$ret_keyword = $db->query($sql_keyword);
						if($db->num_rows($ret_keyword))
						{
							$row_keyword = $db->fetch_array($ret_keyword);
							$curkey = $row_keyword['keyword_id'];
							//Making an updation to the keywords table. This is done to handle the change case is done to an existing
							//keyword
							$update_array 				= array();
							$update_array['keyword_keyword']	= addslashes($v); 
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $curkey));
						}
						else
						{
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $cbo_sites;
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
				
				}
				elseif(substr($k,0,7) == 'txthome')
				{
					$stat_arr = explode("_",$k);
					/*echo "<pre>";
					print_r($stat_arr);*/
					$cur_id	 = $stat_arr[1];
					//echo $cur_id	;
					$cur_val = $v;
					if($v)
					{
						//Check whether the current keyword already exists for current site
					   $sql_keyword = "SELECT keyword_id FROM se_keywords WHERE sites_site_id=$cbo_sites AND keyword_keyword ='".addslashes($v)."'";
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
							$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $curkey));
						}
						else
						{
							
							//If the added keyword is not there in se_keywords table inserting it.
							$insert_array	= array();
							$insert_array['sites_site_id'] = $cbo_sites;
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
							$insert_array['sites_site_id']  		= $cbo_sites;
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
				
			}
		$alert = '<center><font color="red"><b>Keywords updated Successfully</b></font><br>';
		
		if($_REQUEST['type_change']==1 && $_REQUEST['retain_val']) //Done to handle the situation of returning back to page directly after saving.
		{
			include("includes/seo/ad_keywords_list.php");
		}
		else
		{
			echo "<br><span class='redtext'>$alert</span><br>";
			?>
			<br /><a href="home.php?request=seo&fpurpose=AssignKeyword&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&cbo_sites=<?=$cbo_sites?>">Go Back to the Keyword Listing page</a><br /><br />
<?php
		}
}
elseif($_REQUEST['fpurpose']== 'Save_entire_keyword')
{
	//print_r($_REQUEST);
	foreach ($_REQUEST as $k=>$v)
	{
		if(substr($k,0,6) =='txtkey')
		{
		   	$cur_arr 	= explode("_",$k);
			$cur_id 	= $cur_arr[1];
			if ($cur_id)
			{
				//Check whether any other keyword with the current name exists for current site. if so dont update
				$sql_check = "SELECT keyword_id FROM se_keywords WHERE keyword_keyword='".addslashes($v)."' AND sites_site_id=$cbo_sites AND 
				keyword_id <> $cur_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
			
					$update_array				= array();
					$update_array['keyword_keyword']	= addslashes($v); 
					$db->update_from_array($update_array, 'se_keywords', array('sites_site_id' => $cbo_sites ,  'keyword_id' => $cur_id));
						
				}	
				else
					$alert .= "<br><strong>Error! - </strong>$v - Already Exists - Not Updated";	
				
			}	
		}	
	}
	//echo "<span class='redtext'>$alert<span><br>";
	
	if($_REQUEST['type_change']==1 && $_REQUEST['retain_val']==1) //Done to handle the situation of returning back to page directly after saving.
		{
				include ('includes/seo/list_entire_keywords.php');
		}
		else
		{
		if(!$alert){
			$alert = '<center><font color="red"><b>Updated Successfully</b></font><br>';
			}
			echo "<br><span class='redtext'>$alert</span><br>";
			?>
			<br /><a href="home.php?request=seo&fpurpose=Entirekeywords&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&keywords=<? echo $_REQUEST['keywords']?>&sort_order=<? echo $_REQUEST['sort_order']?>&cbo_sites=<?=$cbo_sites?>">Go Back to the EntireKeyword Listing page</a><br /><br />
<?php
		}
	
}

elseif ($_REQUEST['fpurpose'] =='Entirekeywords')
{	
	include("includes/seo/list_entire_keywords.php");
} 
elseif ($_REQUEST['fpurpose'] =='deletekey')
{
		$key_id= $_REQUEST['key_id'];
		
		//Remove all assignments for current keyword if any from se_cat_kw table
		$sql_del = 	"DELETE FROM se_category_keywords WHERE se_keywords_keyword_id = ".$key_id;
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_property_kw table
		$sql_del = 	"DELETE FROM se_product_keywords WHERE se_keywords_keyword_id =".$key_id;
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_static_kw table
		$sql_del = 	"DELETE FROM se_static_keywords WHERE se_keywords_keyword_id = ".$key_id;
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_seach_kw table
		$sql_del = 	"DELETE FROM se_search_keyword WHERE se_keywords_keyword_id =".$key_id;
		$db->query($sql_del);
		//Remove all assignments for current keyword if any from se_home_keywords table
		$sql_del = 	"DELETE FROM se_home_keywords WHERE se_keywords_keyword_id =".$key_id;
		$db->query($sql_del);
		//Now Deleting from se_keywords table
		$sql_del = 	"DELETE FROM se_keywords WHERE keyword_id = ".$key_id;
		$db->query($sql_del);
		
			
			$alert = 'Keyword Deleted Successfully';
	    include("includes/seo/list_entire_keywords.php");
		
} 
else if($_REQUEST['fpurpose']=='SavedKeywords'){
       include("includes/seo/saved_keywords_list.php");
}

elseif ($_REQUEST['fpurpose']=='deletekeysaved')
{ 
		$key_id=$_REQUEST['key_id'];
		
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
				$sql_del = "DELETE FROM saved_search WHERE search_id=".$key_id;
				$db->query($sql_del);
						$alert = 'Keyword Deleted Successfully';
	
	    include("includes/seo/saved_keywords_list.php");
} 
else if($_REQUEST['fpurpose']=='saved_keyword'){
       include("includes/seo/saved_keywords_list.php");
}
else if($_REQUEST['fpurpose']=='SiteMetadescription'){
       include("includes/seo/seo_meta_description.php");
}
elseif($_REQUEST['fpurpose'] == 'Save_SiteDesc') { 
if($_REQUEST['Submit']){
		$alert = '';
		$fieldRequired = array();
		$fieldDescription = array();
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert)
		{
			//Check whether there exists record for current site in the se_description table
			$sql_check = "SELECT meta_id FROM se_meta_description WHERE sites_site_id =$cbo_sites";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)) // Case if a record exists in the table for current site
			{
				$row_check 						= $db->fetch_array($ret_check);
				$update_array					= array();
				//$update_array['home_title']		= addslashes($_REQUEST['txt_title']);
				$update_array['home_meta']		= addslashes($_REQUEST['txt_homemeta']);
				$update_array['static_meta']	= addslashes($_REQUEST['txt_staticmeta']);
				$update_array['product_meta']	= addslashes($_REQUEST['txt_productmeta']);
				$update_array['category_meta']	= addslashes($_REQUEST['txt_categorymeta']);
				$update_array['search_meta']	= addslashes($_REQUEST['txt_searchmeta']);
				$update_array['search_content']	= addslashes($_REQUEST['txt_searchcontent']);
				$db->update_from_array($update_array, 'se_meta_description', array('sites_site_id' => $cbo_sites , 'meta_id' => $row_check['meta_id']));
			}
			else
			{
				$insert_array					= array();
				$insert_array['sites_site_id']		= $cbo_sites;
				//$insert_array['home_title']		= addslashes($_REQUEST['txt_title']);
				$insert_array['home_meta']		= addslashes($_REQUEST['txt_homemeta']);
				$insert_array['static_meta']	= addslashes($_REQUEST['txt_staticmeta']);
				$insert_array['product_meta']	= addslashes($_REQUEST['txt_productmeta']);
				$insert_array['category_meta']	= addslashes($_REQUEST['txt_categorymeta']);
				$insert_array['search_meta']	= addslashes($_REQUEST['txt_searchmeta']);
				$insert_array['search_content']	= addslashes($_REQUEST['txt_searchcontent']);
				$db->insert_from_array($insert_array,'se_meta_description');
			}
			$alert = '<center><font color="red"><b>Meta Descriptions Saved Successfully</b></font><br>';
		}
		else
		{
			$alert = "<strong>Error!!</strong> $alert";
		}	
		echo "<br /><span class='redtext'> <b>$alert</b></span><br />";
		?>
		<br /><a class="smalllink" href="home.php?request=seo&fpurpose=SiteMetadescription&pg=<?=$_REQUEST['pg']?>&cbo_keytype=<?php echo $_REQUEST['cbo_keytype']?>&cbo_sites=<?=$cbo_sites?>">Go Back to the Site Meta Descrition Manage page</a><br /><br />
		<?php
	}
			
			
}

else  {

	include("includes/seo/list_seo.php");
}
?>