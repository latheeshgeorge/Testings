<?php
	/*###########################################################################################
	# Script Name 	: mod_shopbybrandgroup.php
	# Description 	: Page which call the function to display the shop groups
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	$shopgroup_array 	= array();
	$sql_shop		= '';
	// Find the shop groups to be displayed
	if ($_REQUEST['product_id']) // If specific product is selected
	{
		$sql_shop = "SELECT a.shopbrandgroup_id,a.shopbrandgroup_listtype,a.shopbrandgroup_subshoplisttype,
								a.shopbrandgroup_showinall,a.product_displaytype,a.shopbrandgroup_hidename,
								a.shopbrandgroup_product_showimage,a.shopbrandgroup_product_showtitle,
								a.shopbrandgroup_product_showshortdescription,
								a.shopbrandgroup_product_showprice,a.shopbrandgroup_activateperiodchange,
								a.shopbrandgroup_displaystartdate,a.shopbrandgroup_displayenddate,NOW() as date  
					FROM 
						product_shopbybrand_group a LEFT JOIN product_shopbybrand_group_display_products b ON (a.shopbrandgroup_id = b.product_shopbybrand_group_shopbrandgroup_id) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.shopbrandgroup_id = $display_componentid 
						AND a.shopbrandgroup_hide = 0  
						AND (a.shopbrandgroup_showinall = 1 
						OR b.products_product_id = ".$_REQUEST['product_id']." 
					) LIMIT 1";
	}
	elseif($_REQUEST['category_id']) // If specific category is selected
	{
		$sql_shop = "SELECT a.shopbrandgroup_id,a.shopbrandgroup_listtype,a.shopbrandgroup_subshoplisttype,
								a.shopbrandgroup_showinall,a.product_displaytype,a.shopbrandgroup_hidename,
								a.shopbrandgroup_product_showimage,a.shopbrandgroup_product_showtitle,
								a.shopbrandgroup_product_showshortdescription,
								a.shopbrandgroup_product_showprice,a.shopbrandgroup_activateperiodchange,
								a.shopbrandgroup_displaystartdate,a.shopbrandgroup_displayenddate,NOW() as date  
					FROM 
						product_shopbybrand_group a LEFT JOIN product_shopbybrand_group_display_category b ON (a.shopbrandgroup_id = b.product_shopbybrand_group_shopbrandgroup_id) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.shopbrandgroup_id = $display_componentid 
						AND a.shopbrandgroup_hide = 0  
						AND (a.shopbrandgroup_showinall = 1 
						OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
					) LIMIT 1";
	}
	elseif ($_REQUEST['page_id']) // If static pages is selected
	{
		$sql_shop = "SELECT a.shopbrandgroup_id,a.shopbrandgroup_listtype,a.shopbrandgroup_subshoplisttype,
								a.shopbrandgroup_showinall,a.product_displaytype,a.shopbrandgroup_hidename,
								a.shopbrandgroup_product_showimage,a.shopbrandgroup_product_showtitle,
								a.shopbrandgroup_product_showshortdescription,
								a.shopbrandgroup_product_showprice,a.shopbrandgroup_activateperiodchange,
								a.shopbrandgroup_displaystartdate,a.shopbrandgroup_displayenddate,NOW() as date
					FROM 
						product_shopbybrand_group a LEFT JOIN product_shopbybrand_group_display_staticpages b ON (a.shopbrandgroup_id = b.product_shopbybrand_group_shopbrandgroup_id) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.shopbrandgroup_id = $display_componentid 
						AND a.shopbrandgroup_hide = 0  
						AND (a.shopbrandgroup_showinall = 1 
						OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
					) LIMIT 1";
	} else {
		$sql_shop = "SELECT a.shopbrandgroup_id,a.shopbrandgroup_listtype,a.shopbrandgroup_subshoplisttype,
								a.shopbrandgroup_showinall,a.product_displaytype,a.shopbrandgroup_hidename, 
								a.shopbrandgroup_product_showimage,a.shopbrandgroup_product_showtitle,
								a.shopbrandgroup_product_showshortdescription,
								a.shopbrandgroup_product_showprice,a.shopbrandgroup_activateperiodchange,
								a.shopbrandgroup_displaystartdate,a.shopbrandgroup_displayenddate,NOW() as date  
					FROM 
						product_shopbybrand_group a 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.shopbrandgroup_id = $display_componentid 
						AND a.shopbrandgroup_showinall = 1 
						AND a.shopbrandgroup_hide = 0  
					LIMIT 1";
	}
	
	
		$ret_shop = $db->query($sql_shop);
		if ($db->num_rows($ret_shop))
		{
			while ($row_shop = $db->fetch_array($ret_shop))
			{
			if($row_shop['shopbrandgroup_activateperiodchange']==1)
				{  
					$sdate  	= split_date_new($row_shop['shopbrandgroup_displaystartdate']);
    				$edate 	 	= split_date_new($row_shop['shopbrandgroup_displayenddate']);
					$today  	= split_date_new($row_shop['date']);
					
					if($today>=$sdate && $today<=$edate)
					 {
					   $shopgroup_array[] = $row_shop;
					 }
					  else
					   $shopgroup_array 		= array();
					}
				else
				$shopgroup_array[] = $row_shop;
			}
		}
	
	/*if (count($shopgroup_array)==0) // to handle the case of show in all pages
	{
		$sql_shop = "SELECT a.shopbrandgroup_id,a.shopbrandgroup_listtype,a.shopbrandgroup_subshoplisttype,
								a.shopbrandgroup_showinall,a.product_displaytype,a.shopbrandgroup_hidename, 
								a.shopbrandgroup_product_showimage,a.shopbrandgroup_product_showtitle,
								a.shopbrandgroup_product_showshortdescription,
								a.shopbrandgroup_product_showprice,a.shopbrandgroup_activateperiodchange,
								a.shopbrandgroup_displaystartdate,a.shopbrandgroup_displayenddate,NOW() as date  
					FROM 
						product_shopbybrand_group a 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.shopbrandgroup_id = $display_componentid 
						AND a.shopbrandgroup_showinall = 1 
						AND a.shopbrandgroup_hide = 0  
					LIMIT 1";
		$ret_shop = $db->query($sql_shop);
		if ($db->num_rows($ret_shop))
		{
			while ($row_shop = $db->fetch_array($ret_shop))
			{
				if($row_shop['shopbrandgroup_activateperiodchange']==1)
				{  
					$sdate  	= split_date_new($row_shop['shopbrandgroup_displaystartdate']);
    				$edate 	 	= split_date_new($row_shop['shopbrandgroup_displayenddate']);
					$today  	= split_date_new($row_shop['date']);
					if($today>=$sdate && $today<=$edate)
					 {
					   $shopgroup_array[] = $row_shop;
					 }
					  else
					   $shopgroup_array 		= array();
					}
				else
				$shopgroup_array[] = $row_shop;
			}
		}
	}*/
	// Check whether the function to display the shop is to be called
	if (count($shopgroup_array))						
		$components->mod_shopbybrandgroup($shopgroup_array,$display_title,$display_componentid);
?>