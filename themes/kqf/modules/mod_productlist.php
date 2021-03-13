<?php
	/*####################################################################################
	# Script Name 	: mod_productlist.php
	# Description 	: Page which call the function to display product in current category
	# Coded by 		: Sny
	# Created on	: 19-Jan-2008
	# Modified by	: 
	# Modified On	: 
	#####################################################################################*/
	$sql_prod		= '';
	
	/* Sony Jul 01, 2013 */
	$more_conditions = '';
	if(count($discthm_group_prod_array))
	{
		$more_conditions = " AND a.product_id IN ( ".implode(',',$discthm_group_prod_array).") ";
	}
	/* Sony Jul 01, 2013 */
	
	// If category id and category group id exists the proceed to the following 
	if ($_REQUEST['category_id'])
	{
		// Find the category group details 
		$sql_cats = "SELECT category_subcatlisttype,product_displaytype,product_displaywhere 
							FROM 
								product_categories  
							WHERE 
								category_id = ".$_REQUEST['category_id'];
		$ret_cats = $db->query($sql_cats);
		if ($db->num_rows($ret_cats))
		{
			$row_cats = $db->fetch_array($ret_cats);	
			// Whether products are to displayed in the components or in both components and middle
			if ($row_cats['product_displaywhere']=='menu' or $row_cats['product_displaywhere']=='both')
			{
				// Get the list of products to be displayed 
				$sql_prod = "SELECT a.product_id, a.product_name,a.product_default_category_id  
								FROM 
									products a,product_category_map b 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND a.product_hide ='N' 
									AND b.product_categories_category_id =".$_REQUEST['category_id']." 
									AND a.product_id=b.products_product_id 
									$more_conditions 
								ORDER BY
									product_order";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$components->mod_productlist($ret_prod,$display_title);
				}
			}	
		}
	}
?>