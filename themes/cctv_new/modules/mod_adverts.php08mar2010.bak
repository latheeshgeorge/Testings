<?php
	/*###########################################################################################
	# Script Name 	: mod_adverts.php
	# Description 	: Page which call the function to display the adverts in left / right panel
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	// Building the sql 
	$advert_arr		= array ();
	$sql_advert		= '';
	if($_REQUEST['product_id'])
	{
			$sql_advert = "SELECT a.advert_id,a.advert_source,a.advert_link,a.advert_target,
									a.advert_type,a.advert_activateperiodchange,
									a.advert_displaystartdate,a.advert_displayenddate,NOW() as date  
						FROM 
							adverts a LEFT JOIN advert_display_product b ON (a.advert_id = b.adverts_advert_id) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.advert_id = $display_componentid 
							AND a.advert_hide= 0 
							AND ( a.advert_showinall = 1 
							OR b.products_product_id = ".$_REQUEST['product_id']." 
							)  
						ORDER BY 
							a.advert_order ASC 
						LIMIT 
							1";	
	}
	elseif ($_REQUEST['category_id'])
	{
		$sql_advert = "SELECT a.advert_id,a.advert_source,a.advert_link,a.advert_target,
									a.advert_type,a.advert_activateperiodchange,
									a.advert_displaystartdate,a.advert_displayenddate,NOW() as date  
						FROM 
							adverts a LEFT JOIN advert_display_category b ON (a.advert_id = b.adverts_advert_id) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.advert_id = $display_componentid 
							AND a.advert_hide= 0 
							AND (a.advert_showinall = 1 
							OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
							)  
						ORDER BY 
							a.advert_order ASC  
						LIMIT 
							1";	
	}
	elseif ($_REQUEST['page_id'])
	{
		$sql_advert = "SELECT a.advert_id,a.advert_source,a.advert_link,a.advert_target,
								a.advert_type,a.advert_activateperiodchange,
								a.advert_displaystartdate,a.advert_displayenddate,NOW() as date  
						FROM 
							adverts a LEFT JOIN advert_display_static b ON (a.advert_id = b.adverts_advert_id) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.advert_id = $display_componentid 
							AND a.advert_hide= 0 
							AND ( a.advert_showinall = 1  
							OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
							)  
						ORDER BY 
							a.advert_order ASC  
						LIMIT 
							1";
	} else {
		// Taking care of the show in all adverts section
		$sql_advert = "SELECT a.advert_id,a.advert_source,a.advert_link,a.advert_target,
									a.advert_type,a.advert_activateperiodchange,
									a.advert_displaystartdate,a.advert_displayenddate,NOW() as date  
							FROM 
								adverts a
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.advert_id = $display_componentid 
								AND a.advert_hide= 0 
								AND a.advert_showinall = 1 
							ORDER BY 
								a.advert_order ASC  
						LIMIT 
							1";
	}
		$ret_advert = $db->query($sql_advert);
		if ($db->num_rows($ret_advert))// Check whether result is there
		{
			while ($row_advert = $db->fetch_array($ret_advert))
			{
			     if($row_advert['advert_activateperiodchange']==1)
				{
					$start_date  = split_date_new($row_advert['advert_displaystartdate']);
					$end_date 	 = split_date_new($row_advert['advert_displayenddate']);
					$today  	 = split_date_new($row_advert['date']);
					if($today>=$start_date && $today<=$end_date)
					{
						$advert_arr[] = $row_advert;
					}
					else
					{
						removefrom_Display_Settings($row_advert['advert_id'],'mod_adverts');
						$advert_arr 		= array();
					}	
				}
				else
					$advert_arr[] = $row_advert;
			}		
		}		
	if (count($advert_arr))
		$components->mod_adverts($advert_arr,$display_title); // call the function to show the advert
?>