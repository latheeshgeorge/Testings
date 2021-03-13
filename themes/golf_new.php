<?php
/*#################################################################
# Script Name 	: golf.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on		: 06-May-2009
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

$ecom_common_settings 	= get_Common_Settings();
$ecom_tax_total_arr 			= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
// Check whether seo_revenue module is active for current site
if(is_Feature_exists('mod_seo_revenue'))
{
	include("includes/seo_revenue_report.php");
}	
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
    case 'compare_products':
        include($ecom_themename.'/compare_products.php');
    break;
    case 'prod_detail':
        if($_REQUEST['prod_mod']=='')
        {
            if($Settings_arr['themes_layouts_layout_id']==0) // case if this field is not set from general settings section or no layout exists
                include($ecom_themename.'/prod_detail.php'); 
            else
            {
                switch($Settings_arr['themes_layouts_layout_code']) // Get the layout code for current layout id
                {
                    case '2col':
                        include($ecom_themename.'/prod_detail.php');
                    break;
                    case 'cart':
                        include($ecom_themename.'/cart.php');
                    break;
                };
            }
        }
        else
            include($ecom_themename.'/default.php');
    break;
    case 'cart':
        include($ecom_themename.'/cart.php'); 
        
    break;
    default: //showing the index page for the theme
            include($ecom_themename.'/default.php');
}
?>