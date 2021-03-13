<?php
// ##############################################################################################################
		// Building the query for featured product
		// ##############################################################################################################
		$sql_featured = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,
							a.product_shortdesc,a.product_longdesc,b.featured_desc,b.featured_showimage,
							b.featured_showtitle,b.featured_showshortdescription,b.featured_showprice,b.featured_showimagetype,a.product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice  
						FROM 
							products a, product_featured b 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_hide = 'N' 
							AND a.product_id=b.products_product_id 
						LIMIT 
							1";
		$ret_featured = $db->query($sql_featured);
		if ($db->num_rows($ret_featured))// Case if featured is set
		{
			$components->mod_featured($ret_featured,$display_title,$display_id); // calling the display logic to show the featured section.
		}
?>

