<?php
	/*###########################################################################################
	# Script Name 	: mod_combo.php
	# Description 	: Page which call the function to display the combo deals in left / right panel
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	$combo_array 	= array();
	$sql_combo		= '';
	// Find the combo deals
	if ($_REQUEST['product_id']) // If specific product is selected
	{
		$sql_combo = "SELECT a.combo_id,a.combo_hidename,a.combo_name,
							a.combo_activateperiodchange,a.combo_displaystartdate,
							a.combo_displayenddate,NOW() as date  
					FROM 
						combo a LEFT JOIN combo_display_product b ON (a.combo_id = b.combo_id)  
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.combo_id = $display_componentid 
						AND a.combo_active = 1  
						AND (a.combo_showinall = 1 
						OR b.products_product_id = ".$_REQUEST['product_id']." 
					) LIMIT 1";
	}
	elseif($_REQUEST['category_id']) // If specific category is selected
	{
		$sql_combo = "SELECT a.combo_id,a.combo_hidename,a.combo_name,
							a.combo_activateperiodchange,a.combo_displaystartdate,
							a.combo_displayenddate,NOW() as date    
					FROM 
						combo a LEFT JOIN combo_display_category b ON (a.combo_id = b.combo_id) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.combo_id = $display_componentid 
						AND a.combo_active = 1  
						AND (a.combo_showinall = 1 
						OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
					) LIMIT 1";
	}
	elseif ($_REQUEST['page_id']) // If static pages is selected
	{
		$sql_combo = "SELECT a.combo_id,a.combo_hidename,a.combo_name,
							a.combo_activateperiodchange,a.combo_displaystartdate,
							a.combo_displayenddate,NOW() as date   
					FROM 
						combo a LEFT JOIN combo_display_static b ON (a.combo_id = b.combo_id) 
					WHERE
						a.sites_site_id = $ecom_siteid 
						AND a.combo_id = $display_componentid 
						AND a.combo_active = 1  
						AND ( a.combo_showinall = 1 
						OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
						) LIMIT 1";
	} else {
		$sql_combo = "SELECT a.combo_id,a.combo_hidename,a.combo_name,
							a.combo_activateperiodchange,a.combo_displaystartdate,
							a.combo_displayenddate,NOW() as date   
						FROM 
							combo a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.combo_id = $display_componentid 
							AND a.combo_showinall = 1 
							AND a.combo_active = 1  
						LIMIT 1";
	}
	
		$ret_combo = $db->query($sql_combo);
		if ($db->num_rows($ret_combo))
		{
			while ($row_combo = $db->fetch_array($ret_combo))
			{
				if($row_combo['combo_activateperiodchange']==1)
				{
					    $sdate 		 = split_date_new($row_combo['combo_displaystartdate']);
    					$edate 	 	 = split_date_new($row_combo['combo_displayenddate']);
						$today  	 = split_date_new($row_combo['date']);
					if($today>=$sdate && $today<=$edate)
					 {
					   $combo_array[] = $row_combo;
					 }
					  else
					   $combo_array 		= array();
					}
				else
				$combo_array[] = $row_combo;
			}
		}
	/*if (count($combo_array)==0) // to handle the case of not found in above queries
	{
		$sql_combo = "SELECT a.combo_id,a.combo_hidename,a.combo_name,
							a.combo_activateperiodchange,a.combo_displaystartdate,
							a.combo_displayenddate,NOW() as date   
						FROM 
							combo a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.combo_id = $display_componentid 
							AND a.combo_showinall = 1 
							AND a.combo_active = 1  
						LIMIT 1";
		$ret_combo = $db->query($sql_combo);
		if ($db->num_rows($ret_combo))
		{
			while ($row_combo = $db->fetch_array($ret_combo))
			{
				if($row_combo['combo_activateperiodchange']==1)
				{
					    $sdate      = split_date_new($row_combo['combo_displaystartdate']);
    					$edate 	    = split_date_new($row_combo['combo_displayenddate']);
						$today  	= split_date_new($row_combo['date']);
					if($today>=$sdate && $today<=$edate)
					 {
					   $combo_array[] = $row_combo;
					 }
					  else
					   $combo_array 		= array();
					}
				else
				$combo_array[] = $row_combo;
			}
		}
	}*/
	// Check whether the function to display the combo deal logic is to be called
	if (count($combo_array))						
		$components->mod_combo($combo_array,$display_title);
?>