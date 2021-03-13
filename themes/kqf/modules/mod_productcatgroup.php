<?php
	/*###########################################################################################
	# Script Name 	: mod_productcatgroup.php
	# Description 	: Page which call the function to display the product category groups
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 24-Aug-2010
	############################################################################################*/
	$catgroup_arr 	= array();
	$sql_catgroup	= '';
	if ($_REQUEST['product_id']) // Pick category group based on product if product id is present
	{
	 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
						FROM 
							product_categorygroup a LEFT JOIN product_categorygroup_display_products b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND ( a.catgroup_showinall = 1 
							OR b.products_product_id = ".$_REQUEST['product_id']." 
						) LIMIT 1"; 
	}
	
	elseif ($_REQUEST['category_id']) //Pick category group based on category if category id is present
	{
	
		if($position=='topband')
		{
			$cust_id 		= get_session_var("ecom_login_customer");
			$more_cond = '';
			if($cust_id) // case of logged in 
			{
				if($_REQUEST['category_id']==771501 || $_REQUEST['category_id']==77210)
				{
					 if (array_key_exists($discthm_group_custgroup_val,$kqf_schoolarr))
					 {
						if(!in_array($kqf_schoolcatmenuid,$kqf_topband_already_displayed))
						{
							$display_componentid = $kqf_schoolcatmenuid;
							$more_cond = " AND ( a.catgroup_showinall != 1 AND b.product_categories_category_id = ".$_REQUEST['category_id'].")";
							$kqf_topband_already_displayed[] = $kqf_schoolcatmenuid;
						}
						else
							$display_componentid = 0;	
					 }	
					 elseif (array_key_exists($discthm_group_custgroup_val,$kqf_tradearr))	
					 {
						if(!in_array($kqf_tradecatmenuid,$kqf_topband_already_displayed))
						{
							$display_componentid = $kqf_tradecatmenuid;
							$more_cond = " AND ( a.catgroup_showinall != 1 AND b.product_categories_category_id = ".$_REQUEST['category_id'].")";
							$kqf_topband_already_displayed[] = $kqf_tradecatmenuid;
						}	
						else
							$display_componentid = 0;
					 }
				}	 
				else
					$more_cond = " AND ( a.catgroup_showinall = 1 OR b.product_categories_category_id = ".$_REQUEST['category_id'].") AND a.catgroup_id NOT IN($kqf_schoolcatmenuid,$kqf_tradecatmenuid)";
				
				 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
									FROM 
										product_categorygroup a LEFT JOIN product_categorygroup_display_category b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
									WHERE 
										a.sites_site_id=$ecom_siteid 
										AND a.catgroup_id = $display_componentid 
										AND a.catgroup_hide = 0 
										$more_cond 
									LIMIT 1"; 
			}		
			else // case if not logged in 
			{
				if($_REQUEST['category_id']==771501 || $_REQUEST['category_id']==77210 || $_REQUEST['category_id']==77451 || $_REQUEST['category_id']==77453)
				{
					 if ($_REQUEST['category_id']==77150 || $_REQUEST['category_id']==77451) // school
					 {
						if(!in_array($kqf_schoolcatmenuid,$kqf_topband_already_displayed))
						{
							$display_componentid = $kqf_schoolcatmenuid;
							$more_cond = " AND ( a.catgroup_showinall != 1 AND b.product_categories_category_id = 77150)";
							$kqf_topband_already_displayed[] = $kqf_schoolcatmenuid;
						}
						else
							$display_componentid = 0;	
					 }	
					 elseif ($_REQUEST['category_id']==77210 || $_REQUEST['category_id']==77453) // trade
					 {
						if(!in_array($kqf_tradecatmenuid,$kqf_topband_already_displayed))
						{
							$display_componentid = $kqf_tradecatmenuid;
							$more_cond = " AND ( a.catgroup_showinall != 1 AND b.product_categories_category_id = 77210)";
							$kqf_topband_already_displayed[] = $kqf_tradecatmenuid;
						}	
						else
							$display_componentid = 0;
					 }
				}	 
				else
					$more_cond = " AND ( a.catgroup_showinall = 1 OR b.product_categories_category_id = ".$_REQUEST['category_id'].") AND a.catgroup_id NOT IN($kqf_schoolcatmenuid,$kqf_tradecatmenuid)";
				
				 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
									FROM 
										product_categorygroup a LEFT JOIN product_categorygroup_display_category b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
									WHERE 
										a.sites_site_id=$ecom_siteid 
										AND a.catgroup_id = $display_componentid 
										AND a.catgroup_hide = 0 
										$more_cond 
									LIMIT 1"; 
			}
			
			
			
						
		}
		else
		{
			 $sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
								FROM 
									product_categorygroup a LEFT JOIN product_categorygroup_display_category b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
								WHERE 
									a.sites_site_id=$ecom_siteid 
									AND a.catgroup_id = $display_componentid 
									AND a.catgroup_hide = 0 
									AND ( a.catgroup_showinall = 1 
									OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
								) LIMIT 1"; 
		}						
	}
	elseif ($_REQUEST['page_id']) // Pick adverts based on static page if page id is present
	{
	$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount   
						FROM 
							product_categorygroup a LEFT JOIN product_categorygroup_display_staticpages b ON (a.catgroup_id = b.product_categorygroup_catgroup_id) 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND ( a.catgroup_showinall = 1 
							OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
						) LIMIT 1";
	} else {
		$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype,a.catgroup_show_subcat_indropdown,a.catgroup_show_subcat_indropdown_subcount     
						FROM 
							product_categorygroup a 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND a.catgroup_showinall = 1 
						LIMIT 1";
	}
	
	// Assigning the obtained advert details to advert_arr array;
	
		$ret_catgroup = $db->query($sql_catgroup);
		if ($db->num_rows($ret_catgroup))
		{
			while ($row_catgroup = $db->fetch_array($ret_catgroup))
			{
				$catgroup_arr[] = $row_catgroup;
			}
		}

	/*if (count($catgroup_arr)==0) // to handle the case of show in all pages
	{
		// Picking the category group for which the catgroup_showall is set to 1
		$sql_catgroup = "SELECT a.catgroup_id,a.catgroup_name,a.catgroup_hidename,a.catgroup_listtype   
						FROM 
							product_categorygroup a 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.catgroup_id = $display_componentid 
							AND a.catgroup_hide = 0 
							AND a.catgroup_showinall = 1 
						LIMIT 1";
		$ret_catgroup = $db->query($sql_catgroup);
		if ($db->num_rows($ret_catgroup))
		{
			while($row_catgroup = $db->fetch_array($ret_catgroup))
			{
				$catgroup_arr[] = $row_catgroup;
			}
		}	
	}	*/
	if(count($catgroup_arr))
		$components->mod_productcatgroup($catgroup_arr,$display_title);	
?>