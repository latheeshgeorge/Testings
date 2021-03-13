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

$privatekey = "6LcJcxEUAAAAAIfR2esO61yor76udR4mefgxbKST";//live
 //$privatekey = "6LcvchEUAAAAAM4AmcVbOKgufArTKRHTONIPeo2s";//local
// ======================================================
// Settings to show the captcha code
// ======================================================
$site_key = "6LcJcxEUAAAAAJdhE3XEPp123aqziR4ldGuYtjvE";//live
//$site_key = "6LcvchEUAAAAAPiqqcY_EPwHGh81iUxYrevQwsId";//local

# the response from reCAPTCHA
//$resp = null;
# the error code from reCAPTCHA, if any
//$error = null;
require("includes/autoload.php");

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
	$show_error = 0;
	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
	$responseData = json_decode($verifyResponse);

    if($responseData->success) {
		$show_error ="";
	}
	else
	{
	 $show_error = "Robot verification failed, please try again.";	
	}

	if($show_error=="")
	{
		$cont_name 		= trim($_REQUEST['firstname']).' '.trim($_REQUEST['lastname']);
		$cont_email 	= trim($_REQUEST['emailaddress']);
		$cont_phone 	= trim($_REQUEST['phonenumber']);
		$cont_company 	= trim($_REQUEST['companyname']);
		$cont_message 	= trim($_REQUEST['message_contact']);
		
		$message = "Name : " .  $cont_name. "\nEmail Id : " . $cont_email . " \nPhone : " . $cont_phone;
		if($cont_company)
		{
			$message = $message."\nCompany : ".$cont_company;
		}
		$message = $message."\nMessage :\n ".$cont_message;
		
		
		
		$headers = "From: " . $_REQUEST["firstname"] . " <" .$_REQUEST["emailaddress"] . ">";
		$address = 'sales@puregusto.co.uk';//"contactus@Purogusto.co.uk";
		//$address = 'latheeshgeorge@gmail.com';
		//$address = "sony.joy@thewebclinic.co.uk";
		//$address = 'sales@puregusto.co.uk';
		mail($address, "Contact Us Form", $message, $headers);
	echo "
				<script type='text/javascript'>
				alert('Details Sent Successfully');
				</script>
			";
	}
	else
	{
		echo "
				<script type='text/javascript'>
				alert('Robot verification failed, please try again.');
				</script>
			";
	}
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
