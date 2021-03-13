<?php
	/*###########################################################################################
	# Script Name 	: mod_featured.php
	# Description 		: Page which call the function to display the featured product
	# Coded by 		: Sny
	# Created on		: 13-Nov-2008
	# Modified by		: 
	# Modified On		: 
	############################################################################################*/
	// Get the featured product set for current site
	$sql_featured = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,
							a.product_shortdesc,a.product_longdesc,b.featured_desc,b.featured_showimage,
							b.featured_showtitle,b.featured_showshortdescription,b.featured_showprice,b.featured_showimagetype,a.product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock   
						FROM 
							products a, product_featured b 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.product_hide = 'N' 
							AND a.product_id=b.products_product_id 
						LIMIT 
							1";
	$ret_featured = $db->query($sql_featured);
	if ($db->num_rows($ret_featured))
		$components->mod_featured($ret_featured,$display_title);
?>