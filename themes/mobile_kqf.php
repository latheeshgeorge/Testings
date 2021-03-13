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
define("IMG_SIZE",3);

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();
// ======================================================
// Settings to show the captcha code
// ======================================================
//$publickey 	= "6LclDfESAAAAAFMuAep_ExeaXbHRvCPQfr9LRz5k"; // local
//$privatekey = "6LclDfESAAAAAG0ZObjxTFhOfWUMaKlfD2Ku5P2-"; // local

$publickey 	= "6LeVC_ESAAAAANuAH_qtIiz1XY4uPy20kLHVhTAi"; // live kqf
$privatekey = "6LeVC_ESAAAAAN6wfOrjkcBr37Kr8jiS2HaCe_Pr"; // live kqf

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
/*
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
*/
// Section to send the Contact Us details for garraways site
if ($_REQUEST['ContactUs_Submitted']==1)
{
	$show_error = 0;
	if ($_POST["recaptcha_response_field"])
	{
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

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
		$message = "First Name : " .  $_REQUEST["fname"] . "<br />Last Name : " . $_REQUEST["lname"] . " <br />Company : " . $_REQUEST["company"] . "<br />Telephone : " . $_REQUEST["telephone"];
	    if($_REQUEST["enquiry"]) {     
		$message = $message . "<br />Enquiry details : " . nl2br($_REQUEST["enquiry"]); 
	   }   
		$headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: " . $_REQUEST["fname"] . " <" .$_REQUEST["email"] . ">";
		//$address = "latheeshgeorge@gmail.com";
		$address  = "sales@kqf-foods.com";
		mail($address, "Contact Us Form", $message, $headers);
	echo "
				<script type='text/javascript'>
				alert('Details Send Successfully');
				</script>
			";
	}
	else
	{
		echo "
				<script type='text/javascript'>
				alert('Sorry! Incorrect Verification Code');
				</script>
			";
	}
} 

/* Sony Jul 01, 2013 */
$discthm_group_shelf_array = $discthm_group_static_array =$discthm_group_prod_array =$disgthm_group_cat_array= $custgroup_special_arr =array();
$custgroup_special_arr 		= customer_group_special_arrays();
if(count($custgroup_special_arr))
{
$discthm_group_shelf_array 	= $custgroup_special_arr['shelf'];
$discthm_group_static_array = $custgroup_special_arr['static'];
$discthm_group_prod_array 	= $custgroup_special_arr['product'];
$disgthm_group_cat_array 	= $custgroup_special_arr['category'];
/*echo "Shelf<br>";
						print_r($discthm_group_shelf_array);				 
						
						echo "<br>Static<br>";
						print_r($discthm_group_static_array);	
						
						echo "<br>Product<br>";
						print_r($discthm_group_prod_array);	
						
						echo "<br>Category<br>";
						print_r($disgthm_group_cat_array);	
						*/ 					

/* Sony Jul 01, 2013 */
}

$kqf_arr = array();
$kqf_arr = array(70=>"http://www.kqf-foods.com/schools",71=>"http://www.kqf-foods.com/trade");
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
include($ecom_themename.'/mobilehome.php');
?>
