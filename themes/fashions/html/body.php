<?php
	/*#################################################################
	# Script Name 	: body.php
	# Description 	: This is the home page for guest
	# Coded by 		: Sny
	# Created on	: 28-Dec-2007
	# Modified by	: Sny
	# Modified On	: 03-Jan-2008	
	#################################################################*/
	/*$cache_exists 	= false;
	$cache_required	= false;
	$cache_type		= 'body_normal';	
	if ($Settings_arr['enable_caching_in_site']==1)
	{
		$cache_required = true;
		if (exists_Cache($cache_type,$ecom_siteid))
		{
			$content_cache = getcontent_Cache($cache_type,$ecom_siteid);
			if ($content_cache) // if cache exists show it
			{
				echo $content_cache;
				$cache_exists = true;
			}
		}
	}*/
	$cache_required	= false;
	$cache_exists = false;	
	// Do the following only if caching is not enabled or cache does not exists
	if ($cache_exists==false)
	{
		if($cache_required)// if caching is required start recording the output
		{
			ob_start();
		}	
	 $sql_inline = "SELECT display_order,b.feature_modulename,display_component_id,display_id,display_title 
						FROM 
							display_settings a,features b 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.display_position='middle' 
							AND b.feature_allowedinmiddlesection = 1  
							AND layout_code='$default_layout' 
							AND a.features_feature_id=b.feature_id 
						ORDER BY 
								display_order 
						ASC";
		$ret_inline = $db->query($sql_inline);
		if ($db->num_rows($ret_inline))
		{
			while ($row_inline = $db->fetch_array($ret_inline))
			{
				$modname 			= $row_inline['feature_modulename'];
				$body_dispcompid	= $row_inline['display_component_id'];
				$body_dispid		= $row_inline['display_id'];
				$body_title			= $row_inline['display_title'];
				switch($modname)
				{
					//case 'mod_featured': // case of featured product
					//	include ("includes/base_files/featured.php");
					//break;
					case 'mod_adverts': // case of advert 
						include ("includes/base_files/advert.php");
					break;
					case 'mod_shelf': // case of shelf 
						include ("includes/base_files/shelf.php");
					break;
					//case 'mod_productcatgroup': // case of productcatgroup 
					//	include ("includes/base_files/homepage_categ_group.php");
					//break;
					case 'mod_homepagecontent': // case of home page content 
						include ("includes/base_files/homepage.php");
					break;
				};
			}
		}
		if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
		{
			$content = ob_get_contents();
			ob_end_clean();
			save_Cache($cache_type,$ecom_siteid,$content);
			echo $content;
		}
	}	
?>