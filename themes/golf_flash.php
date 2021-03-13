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
//require_once('includes/recaptchalib.php');

$privatekey = "6Lff3aIUAAAAAHIQZlJSAoTZGFnAZVhVwypCYTrc";//live
// $privatekey = "6LcvchEUAAAAAM4AmcVbOKgufArTKRHTONIPeo2s";//local
// ======================================================
// Settings to show the captcha code
// ======================================================
$site_key = "6Lff3aIUAAAAAJg1D_IK7LsAQ1g9Ma-lkeQC6dbG";//live
//$site_key = "6LcvchEUAAAAAPiqqcY_EPwHGh81iUxYrevQwsId";//local

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
	$address = "latheeshgeorge@gmail.com";
	//$address = "info@nationwidefireextinguishers.co.uk";
	}
	mail($address, "Contact Us Form", $message, $headers);
     echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			</script>
		";
}
if($ecom_siteid == 	80)
{
	if($_REQUEST['cart_deliverylocation']==111 || $_REQUEST['cart_deliverylocation']==276)
	{
	  $_REQUEST['euvat_id'] = 3;
	}
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
switch($_REQUEST['req'])
{	
	case 'prod_detail':
		if($_REQUEST['prod_mod']=='comp_list')
			include($ecom_themename.'/home.php');
		else
			include($ecom_themename.'/prod_detail.php');
	break;
	case 'cart':// Cart page
	case 'compare_products':// Compare page
		include($ecom_themename.'/cart.php');
	break;
	case 'login_home': // login home page
	case 'voucher': // login home page
	case 'prod_review': // product review
	case 'site_review': // site review
	case 'registration': // login home page
	case 'myprofile': // login home page
	case 'email_friend': // login home page
	case 'enquiry': // login home page
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
		include($ecom_themename.'/default.php');
	break;
	case 'search':
		if($_REQUEST['search_meth']== 'advanced')
			include($ecom_themename.'/default.php');
		else
			 include($ecom_themename.'/home.php');
	break;
	default: // none of above cases
		 include($ecom_themename.'/home.php');
}
?>