<?php
	/*###########################################################################################
	# Script Name 	: mod_staticgroup.php
	# Description 	: Page which call the function to display the Static page groups
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	$grp_array	= array();
	$sql_grp	= '';
	
	// Find the static page groups which are to be shown in current position
	if ($_REQUEST['product_id']) // If specific product is selected
	{
		$sql_grp = "SELECT a.group_id,a.group_name,group_hide,group_hidename,
							group_showhomelink,group_showsitemaplink,group_showhelplink,group_showsavedsearchlink,group_showxmlsitemaplink,group_showfaqlink   
					FROM 
						static_pagegroup a LEFT JOIN static_pagegroup_display_product b ON (a.group_id = b.static_pagegroup_group_id ) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.group_id = $display_componentid 
						AND a.group_hide = 0 
						AND (a.group_showinall = 1 
						OR b.products_product_id = ".$_REQUEST['product_id']."  
					) ORDER BY 
						group_order 
					LIMIT 1";
		
	}
	elseif($_REQUEST['category_id']) // If specific category is selected
	{
		$sql_grp = "SELECT a.group_id,a.group_name,group_hide,group_hidename,
							group_showhomelink,group_showsitemaplink,group_showhelplink,group_showsavedsearchlink,group_showxmlsitemaplink,group_showfaqlink   
					FROM 
						static_pagegroup a LEFT JOIN static_pagegroup_display_category b ON (a.group_id = b.static_pagegroup_group_id ) 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.group_id = $display_componentid 
						AND a.group_hide = 0 
						AND (b.product_categories_category_id = ".$_REQUEST['category_id']." 
						OR a.group_showinall = 1 
					) ORDER BY 
						group_order 
					LIMIT 1";
		
	}
	elseif ($_REQUEST['page_id']) // If static pages is selected
	{
		$sql_grp = "SELECT a.group_id,a.group_name,group_hide,group_hidename,
							group_showhomelink,group_showsitemaplink,group_showhelplink,group_showsavedsearchlink,group_showxmlsitemaplink,group_showfaqlink    
					FROM 
						static_pagegroup a LEFT JOIN static_pagegroup_display_static b ON (a.group_id = b.static_pagegroup_group_id ) 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.group_id = $display_componentid  
						AND a.group_hide = 0 
						AND (b.static_pages_page_id = ".$_REQUEST['page_id']." 
						OR a.group_showinall = 1 
					) ORDER BY 
						group_order 
					LIMIT 1";
		
	} else {
		$sql_grp = "SELECT group_id,group_name,group_hide,group_hidename,
							group_showhomelink,group_showsitemaplink,group_showhelplink,group_showsavedsearchlink,group_showxmlsitemaplink,group_showfaqlink    
					FROM 
						static_pagegroup 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND group_id = $display_componentid 
						AND group_showinall = 1 
						AND group_hide = 0 
					ORDER BY 
						group_order 
					LIMIT 1";
	}
	
			
		$ret_grp = $db->query($sql_grp);
		if ($db->num_rows($ret_grp))
		{
			while ($row_grp = $db->fetch_array($ret_grp))
			{
				$grp_array[] = $row_grp;
			}
		}
	
	// Check whether the static page display logic is to be called
	if (count($grp_array))						
		$components->mod_staticgroup($grp_array,$display_title);
?>