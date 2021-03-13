<?php
	/*###########################################################################################
	# Script Name 	: mod_header.php
	# Description 	: Page which call the function to display the adverts in left / right panel
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	// Building the sql 
	$header_arr		= array ();
	$sql_header		= '';
	if($_REQUEST['product_id'])
	{
			$sql_header = "SELECT a.header_id,a.header_filename,a.header_period_change_required,a.header_startdate,
							  a.header_enddate,NOW() as date  
						FROM 
							site_headers a LEFT JOIN header_display_product b ON (a.header_id = b.site_headers_header_id ) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.header_hide= 0 
							AND (a.header_showinall = 1  
							OR b.products_product_id = ".$_REQUEST['product_id']." 
							) 
						ORDER BY 
							b.products_product_id DESC, a.header_id ASC LIMIT 
							1";	
	}
	elseif ($_REQUEST['category_id'])
	{
		$sql_header = "SELECT a.header_id,a.header_filename,a.header_period_change_required,a.header_startdate,
							  a.header_enddate,NOW() as date  
						FROM 
							site_headers a LEFT JOIN header_display_category b ON (a.header_id = b.site_headers_header_id ) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.header_hide= 0 
							AND (a.header_showinall = 1  
							OR b.product_categories_category_id = ".$_REQUEST['category_id']." 
							)
						ORDER BY 
							b.product_categories_category_id DESC,a.header_id ASC LIMIT 
							1";	
	}
	elseif ($_REQUEST['page_id'])
	{
		$sql_header = "SELECT a.header_id,a.header_filename,a.header_period_change_required,a.header_startdate,
							  a.header_enddate,NOW() as date  
						FROM 
							site_headers a LEFT JOIN header_display_static b ON (a.header_id = b.site_headers_header_id ) 
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.header_hide= 0  
							AND (a.header_showinall = 1  
							OR b.static_pages_page_id = ".$_REQUEST['page_id']." 
							) 
						ORDER BY 
							b.static_pages_page_id DESC ,a.header_id ASC LIMIT 
							1";
	} else {
		$sql_header = "SELECT a.header_id,a.header_filename,a.header_period_change_required,a.header_startdate,
							  a.header_enddate,NOW() as date  
						FROM 
							site_headers a
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.header_hide= 0 
							AND a.header_showinall = 1 
						ORDER BY 
							a.header_id ASC LIMIT 
							1";
	}
	//echo $sql_header;
		$ret_header = $db->query($sql_header);
		if ($db->num_rows($ret_header))// Check whether result is there
		{
			while ($row_header = $db->fetch_array($ret_header))
			{
			     if($row_header['header_period_change_required']==1)
					{
						$sdate       = split_date_new($row_header['header_startdate']);
    					$edate 	 = split_date_new($row_header['header_enddate']);
						$today  	 = split_date_new($row_header['date']);
						if($today>=$sdate && $today<=$edate)
						 {
						   $header_arr[] = $row_header['header_filename'];
						 }
						 /* else
						   $header_arr[] 		= array(); */
						} 
					else
					$header_arr[] = $row_header['header_filename'];
			}		
		}		

	// Taking care of the show in all adverts section
	/*$sql_header = "SELECT a.header_id,a.header_filename,a.header_period_change_required,a.header_startdate,
							  a.header_enddate,NOW() as date  
						FROM 
							site_headers a
						WHERE 
							a.sites_site_id = $ecom_siteid 
							AND a.header_hide= 0 
							AND a.header_showinall = 1 
						ORDER BY 
							a.header_id ASC ";
	$ret_header = $db->query($sql_header);
	if ($db->num_rows($ret_header))
	{
		while ($row_header = $db->fetch_array($ret_header))
		{
			 if($row_header['header_period_change_required']==1)
					{
						$sdate       = split_date_new($row_header['header_startdate']);
    					$edate 	 = split_date_new($row_header['header_enddate']);
						$today  	 = split_date_new($row_header['date']);
						if($today>=$sdate && $today<=$edate)
						 {
						   $header_arr[] = $row_header['header_filename'];
						 }
						/*  else
						   $header_arr[] 		= array(); */
					/*} 
			 else
				$header_arr[] = $row_header['header_filename'];
		}
	}
//print_r($header_arr);
	/*
	$sql_header = "SELECT header_filename 
				      FROM site_headers 
				          WHERE sites_site_id=$ecom_siteid AND header_hide='0'";
	$res = $db->query($sql_header);
	while($row_header = $db->fetch_array($res)) {
		$header_arr[] = $row_header['header_filename'];
	} */			  
	//if (count($header_arr))
	 $components->mod_header($header_arr); // call the function to show the advert
?>