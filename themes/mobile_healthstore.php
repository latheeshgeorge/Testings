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
	
// Image Settings
define("IMG_MODE","image_bigpath");
if($ecom_siteid==70)
{
define("IMG_SIZE",2);
}
else
{
define("IMG_SIZE",3);
}

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
if($ecom_siteid==104)
{
$insurance_cat =  array();
$insurance_cat = array(78052,77855,77704,77690,77753,78053,77702,77871,77866,77864);
global $insurance_cat;
$cust_id 					= get_session_var("ecom_login_customer");
$show_cart_password         = 0;
if(($ecom_siteid==104) and !$cust_id)
{
	$show_cart_password     = 1;
	global $show_cart_password;
}
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
include($ecom_themename.'/mobilehome.php');
?>
