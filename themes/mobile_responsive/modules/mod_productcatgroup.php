<?php
	/*###########################################################################################
	# Script Name 	: mod_productcatgroup.php
	# Description 	: Page which call the function to display the product category groups
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 24-Aug-2010
	############################################################################################*/
	$catgroup_arr 	= array();
	$sql_catgroup	= '';
	if ($_REQUEST['product_id']) // Pick category group based on product if product id is present
	{
	 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
						FROM 
							product_categorygroup a LEFT JOIN product_categorygroup_display_products b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND ( a.catgroup_showinall = 1 
							OR b.products_product_id = ".$_REQUEST['product_id']." 
						) LIMIT 1"; 
	}
	
	elseif ($_REQUEST['category_id']) //Pick category group based on category if category id is present
	{
	 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
						FROM 
							product_categorygroup a LEFT JOIN product_categorygroup_display_category b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND ( a.catgroup_showinall = 1 
							OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
						) LIMIT 1"; 
	}
	elseif ($_REQUEST['page_id']) // Pick adverts based on static page if page id is present
	{
	$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
						FROM 
							product_categorygroup a LEFT JOIN product_categorygroup_display_staticpages b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND ( a.catgroup_showinall = 1 
							OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
						) LIMIT 1";
	} else {
		$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount     
						FROM 
							product_categorygroup a 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND a.catgroup_showinall = 1 
						LIMIT 1";
	}
	
	// Assigning the obtained advert details to advert_arr array;
	
		$ret_catgroup = $db->query($sql_catgroup);
		if ($db->num_rows($ret_catgroup))
		{
			while ($row_catgroup = $db->fetch_array($ret_catgroup))
			{
				$catgroup_arr[] = $row_catgroup;
			}
		}

	/*if (count($catgroup_arr)==0) // to handle the case of show in all pages
	{
		// Picking the category group for which the catgroup_showall is set to 1
		$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype   
						FROM 
							product_categorygroup a 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND a.catgroup_showinall = 1 
						LIMIT 1";
		$ret_catgroup = $db->query($sql_catgroup);
		if ($db->num_rows($ret_catgroup))
		{
			while($row_catgroup = $db->fetch_array($ret_catgroup))
			{
				$catgroup_arr[] = $row_catgroup;
			}
		}	
	}	*/
	if(count($catgroup_arr))
		$components->mod_productcatgroup($catgroup_arr,$display_title);	
?>
