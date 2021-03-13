<?php
	/*###########################################################################################
	# Script Name 	: mod_newsletter.php
	# Description 	: Page which call the function to display the newsletter subscription section
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	$bestseller_type 		= $Settings_arr['best_seller_picktype']; 
	
	$max_preorder_allowed	= $Settings_arr['product_preorder_in_component'];
	// Deciding the sort by field
	$bestsort_by			= $Settings_arr['product_orderfield_preorder'];
	switch ($bestsort_by)
	{
		case 'custom':
			$bestsort_by	= 'a.product_preorder_custom_order';
		break;
		case 'product_name':
			$bestsort_by	= 'a.product_name';
		break;
		case 'price':
			$bestsort_by	= 'a.product_webprice';
		break;
	};
	$bestsort_order			= $Settings_arr['product_orderby_preorder'];
	// Building the sql 

	 $sql_best = "SELECT a.product_id,a.product_name,a.product_default_category_id 
						FROM 
							products a 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_preorder_allowed = 'Y' 
							AND a.product_hide ='N' 
							AND a.product_alloworder_notinstock='N' 
						ORDER BY 
							$bestsort_by $bestsort_order 
						LIMIT 
							0,$max_preorder_allowed"; // ,general_settings_site_bestseller b

	

	if ($sql_best!='')
	{
		$ret_best = $db->query($sql_best);
		if ($db->num_rows($ret_best))// Check whether best sellers exists
		{
			$components->mod_preorder($ret_best,$display_title,$display_id); // call the best seller module to show the bestsellers
		}		
	}
	
?>