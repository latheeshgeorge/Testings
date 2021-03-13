<?php
	/*######################################################################################################
	# Script Name 	: mod_bestseller.php
	# Description 	: Page which call the function to display the bestseller products in left / right panel
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	######################################################################################################*/
	// Getting the settings for best sellers form the settings table
	$bestseller_type 		= $Settings_arr['best_seller_picktype']; 
	$max_bestseller_allowed	= $Settings_arr['product_maxbestseller_in_component'];
	// Deciding the sort by field
	$bestsort_by			= $Settings_arr['product_orderfield_bestseller'];
	switch ($bestsort_by)
	{
		case 'custom':
			$bestsort_by	= 'b.bestsel_sortorder';
		break;
		case 'product_name':
			$bestsort_by	= 'a.product_name';
		break;
		case 'price':
			$bestsort_by	= 'a.product_webprice';
		break;
	};
	$bestsort_order			= $Settings_arr['product_orderby_bestseller'];
	// Building the sql 
	$sql_best				= '';
	if($bestseller_type == 1) // Case of manual picking
	{
		$sql_best = "SELECT a.product_id,a.product_name,a.product_default_category_id 
						FROM 
							products a,general_settings_site_bestseller b 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_id = b.products_product_id 
							AND a.product_hide ='N' 
						ORDER BY 
							$bestsort_by $bestsort_order 
						LIMIT 
							0,$max_bestseller_allowed";
	}
	elseif ($bestseller_type == 0) // case of automatic picking
	{
		$sql_best = "SELECT p.product_id,p.product_name,p.product_default_category_id,sum(b.order_qty) as totcnt 
						FROM 
							orders a,order_details b,products p 
						WHERE 
							a.order_id=b.orders_order_id 
							AND a.sites_site_id=$ecom_siteid 
							AND a.order_status != 'CANCELLED' 
							AND b.products_product_id=p.product_id 
							AND p.product_hide ='N' 
						GROUP BY 
							b.products_product_id  
						ORDER BY 
							totcnt $bestsort_order 
						LIMIT 
							0,$max_bestseller_allowed";
	}

	if ($sql_best!='')
	{
		$ret_best = $db->query($sql_best);
		if ($db->num_rows($ret_best))// Check whether best sellers exists
		{
			$components->mod_bestsellers($ret_best,$display_title,$display_id); // call the best seller module to show the bestsellers
		}		
	}
?>