<?php
	/*#################################################################
	# Script Name 	: mod_shelf.php
	# Description 	: Page which call the function to display the shelf
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	#################################################################*/
	$shelf_array 	= array();
	$sql_shelf		= '';
	// Find the shelf
	if ($_REQUEST['product_id']) // If specific product is selected
	{
		$sql_shelf	=	"SELECT
										a.shelf_id, a.shelf_name, a.shelf_displaytype, a.shelf_showimage, a.shelf_showtitle,
										a.shelf_showdescription, a.shelf_showprice, a.shelf_currentstyle, a.shelf_activateperiodchange,
										a.shelf_displaystartdate, a.shelf_displayenddate, a.shelf_showbonuspoints, NOW() AS date
								FROM
										product_shelf a LEFT JOIN product_shelf_display_product b ON (a.shelf_id = b.product_shelf_shelf_id)
								WHERE
										a.sites_site_id = $ecom_siteid
								AND		a.shelf_id = $display_componentid
								AND		a.shelf_hide = 0
								AND		( a.shelf_showinall = 1 OR b.products_product_id = ".$_REQUEST['product_id']." )
								LIMIT	1";
	}
	elseif($_REQUEST['category_id']) // If specific category is selected
	{
		$sql_shelf	=	"SELECT
										a.shelf_id, a.shelf_name, a.shelf_displaytype, a.shelf_showimage, a.shelf_showtitle,
										a.shelf_showdescription, a.shelf_showprice, a.shelf_currentstyle, a.shelf_activateperiodchange,
										a.shelf_displaystartdate, a.shelf_displayenddate, a.shelf_showbonuspoints, NOW() AS date
								FROM
										product_shelf a LEFT JOIN product_shelf_display_category b ON (a.shelf_id = b.product_shelf_shelf_id)
								WHERE
										a.sites_site_id = $ecom_siteid
								AND		a.shelf_id = $display_componentid
								AND		a.shelf_hide = 0
								AND		( a.shelf_showinall = 1 OR b.product_categories_category_id = ".$_REQUEST['category_id']." )
								LIMIT	1";
	}
	elseif ($_REQUEST['page_id']) // If static pages is selected
	{
		$sql_shelf	=	"SELECT
										a.shelf_id, a.shelf_name, a.shelf_displaytype, a.shelf_showimage, a.shelf_showtitle,
										a.shelf_showdescription, a.shelf_showprice, a.shelf_currentstyle, a.shelf_activateperiodchange,
										a.shelf_displaystartdate, a.shelf_displayenddate, a.shelf_showbonuspoints, NOW() AS date
								FROM
										product_shelf a LEFT JOIN product_shelf_display_static b ON (a.shelf_id = b.product_shelf_shelf_id) 
								WHERE
										a.sites_site_id = $ecom_siteid
								AND		a.shelf_id = $display_componentid
								AND		a.shelf_hide = 0
								AND		( a.shelf_showinall = 1 OR b.static_pages_page_id = ".$_REQUEST['page_id']." )
								LIMIT	1";
	}
	elseif ($_REQUEST['shop_id']) // case if page id exists
	{
		$sql_shelf	=	"SELECT
										a.shelf_id, a.shelf_name, a.shelf_displaytype, a.shelf_showimage, a.shelf_showtitle,
										a.shelf_showdescription, a.shelf_showprice, a.shelf_currentstyle, a.shelf_activateperiodchange,
										a.shelf_displaystartdate, a.shelf_displayenddate, a.shelf_showbonuspoints, NOW() AS date
								FROM
										product_shelf a LEFT JOIN product_shelf_display_shop b ON ( a.shelf_id = b.product_shelf_shelf_id )
								WHERE
										a.sites_site_id = $ecom_siteid
								AND		a.shelf_id = $display_componentid 
								AND		a.shelf_hide = 0
								AND		( a.shelf_showinall = 1 OR b.product_shop_shop_id = ".$_REQUEST['shop_id']. ")
								ORDER BY a.shelf_order
								LIMIT	1";
	}
	else
	{
		$sql_shelf	=	"SELECT
										a.shelf_id, a.shelf_name, a.shelf_displaytype, a.shelf_showimage, a.shelf_showtitle,
										a.shelf_showdescription, a.shelf_showprice, a.shelf_currentstyle, a.shelf_activateperiodchange,
										a.shelf_displaystartdate, a.shelf_displayenddate, a.shelf_showbonuspoints, NOW() AS date
								FROM
										product_shelf a
								WHERE
										a.sites_site_id = $ecom_siteid
								AND		a.shelf_id = $display_componentid
								AND		a.shelf_showinall = 1
								AND		a.shelf_hide = 0
								LIMIT	1";
	}
	$ret_shelf = $db->query($sql_shelf);
	if ($db->num_rows($ret_shelf))
	{
		while ($row_shelf = $db->fetch_array($ret_shelf))
		{
			if($row_shelf['shelf_activateperiodchange']==1)
			{
				$sdate  = split_date_new($row_shelf['shelf_displaystartdate']);
				$edate 	 = split_date_new($row_shelf['shelf_displayenddate']);
				$today  	 = split_date_new($row_shelf['date']);
				
				if($today>=$sdate && $today<=$edate)
				{
					$shelf_array[] = $row_shelf;
				}
				else
				{
					removefrom_Display_Settings($row_shelf['shelf_id'],'mod_shelf');
					$shelf_array 		= array();
				}
			}
			else
			{
				$shelf_array[] = $row_shelf;
			}
		}
	}
	
	/*if (count($shelf_array)==0) // to handle the case of not found in above queries
	{ 
		$sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
								shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
								shelf_displaystartdate,shelf_displayenddate,NOW() as date  
						FROM 
							product_shelf a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.shelf_id = $display_componentid 
							AND a.shelf_showinall = 1 
							AND a.shelf_hide = 0  
						LIMIT 1";
		$ret_shelf = $db->query($sql_shelf);
		if ($db->num_rows($ret_shelf))
		{
			while ($row_shelf = $db->fetch_array($ret_shelf))
			{
				if($row_shelf['shelf_activateperiodchange']==1)
				{  
					$sdate  = split_date_new($row_shelf['shelf_displaystartdate']);
    				$edate 	 = split_date_new($row_shelf['shelf_displayenddate']);
					$today  	 = split_date_new($row_shelf['date']);
					
					if($today>=$sdate && $today<=$edate)
					 {
					   $shelf_array[] = $row_shelf;
					 }
					  else
					   $shelf_array 		= array();
					}
				else
				$shelf_array[] = $row_shelf;
			}
		}
	}*/
	// Check whether the function to display the shelf is to be called
	if (count($shelf_array))	
		$components->mod_shelf($shelf_array,$display_title);
?>
