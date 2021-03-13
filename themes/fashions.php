<?php
/*#################################################################
# Script Name 	: fashions.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on		: 13-nov-2008
# Modified by		: 
# Modified On		: 
#################################################################*/

// Moving the current session id to a variable
$sess_id = session_id();

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();

// ################################################################
// Get all the components to be shown in the site
// ################################################################
$inlineSiteComponents =get_inlineSiteComponents();

// ################################################################
// Get all the components which are active in console area
// ################################################################
$consoleSiteComponents = get_inlineConsoleComponents();

// Get all the caption sections to an array
//$ecom_section_Arr = get_CaptionSections();

// Including the general settings array file
if(file_exists($image_path.'/settings_cache/general_settings.php'))
	include "$image_path/settings_cache/general_settings.php";
	
// Including the price display settings array file
if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
	include "$image_path/settings_cache/price_display_settings.php";	

// Check whether seo_revenue module is active for current site
if(is_Feature_exists('mod_seo_revenue'))
{
	include("includes/seo_revenue_report.php");
}	
$ecom_common_settings 	= get_Common_Settings(); // get all the common settings
$ecom_tax_total_arr 			=$ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
	//case 'compare_products':
	//	include($ecom_themename.'/compare_products.php');
	//break;
	case 'categories': // category details
		include($ecom_themename.'/category.php');
	break;
	case 'prod_detail': // Product details
		include($ecom_themename.'/prod_details.php');
	break;
	case 'cart': // case of cart related layout
		include($ecom_themename.'/cart.php');
	break;
	case 'compare_products': // case of product compare
		include($ecom_themename.'/compare_products.php');
	break;
	case '': //showing the index page for the theme
		include($ecom_themename.'/home.php');
	break;
	default:
		include($ecom_themename.'/common.php');
	break;	
};
?>