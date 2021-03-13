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

// ======================================================
// Settings to show the captcha code
// ======================================================

$publickey 	= "6LeSNugSAAAAAIo57r56ExXrGl4XSQCZzPfXe7i4"; // live
$privatekey = "6LeSNugSAAAAAPYErO17NlJ4Ejmc9oxE6SZEysA7"; // live

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
require("includes/recaptchalib.php");

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
	$show_error = 0;
	if ($_POST["recaptcha_response_field"] || $_POST["recaptcha_response_field"] == "")
	{
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

		//echo "<pre>";print_r($resp);
		if ($resp->is_valid) {
				$show_error = 0;
		} else {
				# set the error code so that we can display it
				$error = $resp->error;
				$show_error = 1;
		}
	}
	if($show_error==0)
	{
		$name		=	$_POST["fullname"];
		$company	=	$_POST["company"];
		$email		=	$_POST["email"];
		$msg		=	$_POST["message"];
		$message	=	"Name : " .  $name . "\nCompany Name : " . $company;
		$message	=	$message."\nEmail : " . $email;
		if($msg) 
		{     
			$message = $message . "\nEnquiry : " . $msg; 
		}   
		$headers = "From: " . $name . " <" .$email . ">";
		if($ecom_siteid==70)
		{
			//$address = "sobinbabue@gmail.com";
			$address = "info@nationwidefireextinguishers.co.uk";
		}
		mail($address, "Contact Us Form", $message, $headers);
		echo "	<script type='text/javascript'>
				alert('Details Send Successfully');
				</script>
			";
	}
	else
	{
		echo "	<script type='text/javascript'>
				alert('Sorry! Incorrect Verification Code');
				</script>
			";
	}
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
switch($_REQUEST['req'])
{		
	case 'cart':// Cart page
	 include($ecom_themename.'/cart.php');
	 break;
	default: // none of above cases
		 include($ecom_themename.'/home.php');
	break;
}
?>
