<?php
/*#################################################################
# Script Name 		: golf_flash.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on		: 05-Feb-2010
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
$inlineSiteComponents = get_inlineSiteComponents();


// ################################################################
// Get all the components which are active in console area
// ################################################################
$consoleSiteComponents = get_inlineConsoleComponents();

// Including the general settings array file
if(file_exists($image_path.'/settings_cache/general_settings.php'))
	include "$image_path/settings_cache/general_settings.php";
	
// Including the price display settings array file
if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
	include "$image_path/settings_cache/price_display_settings.php";	

$ecom_common_settings 	= get_Common_Settings();
$ecom_tax_total_arr 	= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
// Check whether seo_revenue module is active for current site
if(is_Feature_exists('mod_seo_revenue'))
{
	include("includes/seo_revenue_report.php");
}	
 $Settings_arr['imageverification_req_newsletter'] = 0; 
// Section to send the Contact Us details for the site
if ($_REQUEST['ContactUs_Submitted']==1)
{
	$name = $_POST["fullname"];
	$company = $_POST["company"];
	$email = $_POST["email"];
	$msg = $_POST["message"];
	$message = "Name : " .  $name . "\nCompany Name : " . $company;
	$message = $message."\nEmail : " . $email;
	if($msg) {     
		$message = $message . "\nEnquiry : " . $msg; 
	}   
	$headers = "From: " . $name . " <" .$email . ">";
	if($ecom_siteid==70)
	{
	//$address = "latheeshgeorge@gmail.com";
	$address = "info@nationwidefireextinguishers.co.uk";
	}
	mail($address, "Contact Us Form", $message, $headers);
     echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			</script>
		";
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
	/*case '':
		include($ecom_themename.'/home.php');
	break;*/
	case 'login_home': // login home page
	case 'prod_review': // product review
	case 'site_review': // site review
	case 'myprofile': // login home page
	case 'email_friend': // login home page
	case 'prod_free_delivery': // login home page
	case 'pricepromise': // login home page
	case 'wishlist': // wishlist
	case 'myfavorites': // myfavorites
	case 'myaddressbook': // myaddressbook
	case 'orders': // orders
	case 'newsletter':
	case 'callback':
	case 'survey_result':
	case 'survey':
	case 'payonaccountdetails':
	case 'downloadable_prod':
	case 'bonus_details':
	case 'bulkdisc':
	case 'voucher': // login home page
	case 'enquiry': // login home page
	case 'search':
	case 'cart':
	case 'prod_shelf':
	case 'prod_shop':
	case 'best_sellers':
	case 'categories':
	case 'preorder':
	case 'registration': // login home page
	case 'static_page':
	case 'sitemap':
	case 'savedsearch':	
	case 'site_faq':
	case 'site_help':
	case 'compare_products': 
		include($ecom_themename.'/default.php');
	break;
    case 'prod_detail':
		 include($ecom_themename.'/prod-detail.php');
	break;
	default: // none of above cases
		 include($ecom_themename.'/home.php');
}
?>
