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
	/* Sony Jul 01, 2013 */
	$more_conditions = '';
	if(count($discthm_group_prod_array))
	{
		$more_conditions = " AND a.product_id IN ( ".implode(',',$discthm_group_prod_array).") ";
	}
	/* Sony Jul 01, 2013 */

	 $sql_best = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice, a.product_default_category_id
						FROM 
							products a 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_preorder_allowed = 'Y' 
							AND a.product_hide ='N' 
							AND a.product_alloworder_notinstock='N'
							$more_conditions  
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