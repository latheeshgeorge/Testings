<?php
	/*###########################################################################################
	# Script Name 	: mod_survey.php
	# Description 	: Page which call the function to display the survey in left / right panel
	# Coded by 		: Sny
	# Created on	: 20-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Jan-2008
	############################################################################################*/
	$survey_array 		= array();
	$sql_survey			= '';
	// Check whether any surveys exists in cookie
	$ext_sur_str 		= $_COOKIE['ecom_surveys'];
	if(substr($ext_sur_str, -1) == ',')
	{
		$ext_sur_str	= substr($ext_sur_str, 0, -1);  
	} 
	if ($ext_sur_str=='')
		$ext_sur_str = 0;
	// Find the survey
	if ($_REQUEST['product_id']) // If specific product is selected
	{
		$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date  
						FROM 
							survey a LEFT JOIN survey_display_product b ON (a.survey_id = b.survey_survey_id )    
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $display_componentid   
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND (b.products_product_id = ".$_REQUEST['product_id']." 
							OR a.survey_showinall = 1 
							) 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
	}
	elseif($_REQUEST['category_id']) // If specific category is selected
	{
		$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date  
						FROM 
							survey a LEFT JOIN survey_display_category b  ON (a.survey_id = b.survey_survey_id )    
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $display_componentid 
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND (b.product_categories_category_id = ".$_REQUEST['category_id']." 
							OR a.survey_showinall = 1 
							) 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
	}
	elseif ($_REQUEST['page_id']) // If static pages is selected
	{
		$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date  
						FROM 
							survey a LEFT JOIN survey_display_static b  ON (a.survey_id = b.survey_survey_id )    
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $display_componentid 
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND ( b.static_pages_page_id = ".$_REQUEST['page_id']." 
							OR a.survey_showinall = 1 
							) 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
						
	} else {
		$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date 
						FROM 
							survey a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $display_componentid 
							AND a.survey_showinall = 1 
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
	}
	
		$ret_survey = $db->query($sql_survey);
		if ($db->num_rows($ret_survey))
		{
			while ($row_survey = $db->fetch_array($ret_survey))
			{
				if($row_survey['survay_activateperiodchange']==1)
				{
					$sdate  = split_date_new($row_survey['survay_displaystartdate']);
					$edate 	 = split_date_new($row_survey['survay_displayenddate']);
					$today  	 = split_date_new($row_survey['date']);
					if($today>=$sdate && $today<=$edate)
					 {
					   $survey_array[] = $row_survey;
					 }
					  else
					   $survey_array 		= array();
					}
				else
				$survey_array[] = $row_survey;
			}
		}
	
	/*if (count($survey_array)==0) // to handle the case of not found in above queries
	{
		$sql_survey = "SELECT a.survey_id,a.survey_question,
							a.survay_activateperiodchange,a.survay_displaystartdate,
							a.survay_displayenddate,NOW() as date 
						FROM 
							survey a
						WHERE
							a.sites_site_id = $ecom_siteid 
							AND a.survey_id = $display_componentid 
							AND a.survey_showinall = 1 
							AND a.survey_hide = 0  
							AND a.survey_status = 2 
							AND a.survey_id NOT IN($ext_sur_str) 
						LIMIT 1";
						
		$ret_survey = $db->query($sql_survey);
		if ($db->num_rows($ret_survey))
		{
			while ($row_survey = $db->fetch_array($ret_survey))
			{
				if($row_survey['survay_activateperiodchange']==1)
				{
					$sdate  = split_date_new($row_survey['survay_displaystartdate']);
					$edate 	 = split_date_new($row_survey['survay_displayenddate']);
					$today  	 = split_date_new($row_survey['date']);
					if($today>=$sdate && $today<=$edate)
					   $survey_array[] = $row_survey;
					  else
					   $survey_array 		= array();
				}
				else
				$survey_array[] = $row_survey;
			}
		}
	}*/
	// Check whether the function to display the shelf is to be called
	if (count($survey_array))						
		$components->mod_survey($survey_array,$display_title);
?>