<?php
/*#################################################################
# Script Name 	: classic.php
# Description 	: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on	: 05-Dec-2007
# Modified by	: Sny
# Modified On	: 07-Jan-08
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
//Contct form submit 
if($_REQUEST['submit_contact']==1)
{
   
    $message = "Full name : " .  $_REQUEST["fullname"]. "\nEmail address : " . $_REQUEST["emailaddr"] . "\nPhone : " . $_REQUEST["phone"] . "\nEnquiry: : " . $_REQUEST["enquiry"];
	$email = $_REQUEST["email"];
	$headers = "From: info@eurolabels.co.uk";
	$address = "sales@eurolabels.co.uk";
	//$address = "latheeshgeorge@gmail.com";
	mail($address, "Contact Us", $message,$headers);
	echo "
			<script type='text/javascript'>
			alert('Mail Send Successfully');
			</script>
		";
}
if($_REQUEST['bespokequote_submit']==1)
{
    
	$message = "Email address : " .  $_REQUEST["field5"]. "\nContact tel no : " . $_REQUEST["field6"] . "\nName : " . $_REQUEST["field7"] . "\nCompany name : " . $_REQUEST["field8"];
	$message .= "\nFormat : " .  $_REQUEST["field28"][0]. "\nLabel width  : " . $_REQUEST["field9"] . "\nLabel depth : " . $_REQUEST["field10"] . "\ndescription of product : " . $_REQUEST["field29"];
	$message .= "\nPrinted or plain : " .  $_REQUEST["field11"][0]. "\nother printing method : " . $_REQUEST["field30"] . "\nUsual order quantity : " . $_REQUEST["field12"] . "\nApprox annual usage : " . $_REQUEST["field13"];
	$message .= "\nFace material / Grade : " .  $_REQUEST["field14"][0]. "\nCore size (Internal) : " . $_REQUEST["field15"][0] . "\nAmount per roll / Box (labels only) : " . $_REQUEST["field16"] . "\nAdhesive (labels only) : " . $_REQUEST["field17"][0];
	$message .= "\nNumber across the web (labels only) : " .  $_REQUEST["field18"]. "\nArtwork supplied : " . $_REQUEST["field19"][0] . "\nDelivery Address and Postcode : " . $_REQUEST["field20"] . "\nwhat the label is to be used for, and what the label will be applied to : " . $_REQUEST["field21"];
    $message .= "\nif you have had any previous problems with labels : " .  $_REQUEST["field22"]. "\nTell me you have received this enquiry : " . $_REQUEST["field23"];
    $email = $_REQUEST["email"];
	$headers = "From: info@eurolabels.co.uk";
	$address = "sales@eurolabels.co.uk";
	//$address = "latheeshgeorge@gmail.com";
	mail($address, "Bespoke Quote", $message,$headers);
	echo "
			<script type='text/javascript'>
			alert('Mail Send Successfully');
			</script>
		";
}
	
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
	case '':
		include($ecom_themename.'/home.php');
	break;
	case 'prod_detail':
		include($ecom_themename.'/details.php');
	break;
	case 'cart':
		include($ecom_themename.'/cart.php');
	break;
	case 'compare_products':
		include($ecom_themename.'/compare_products.php');
	break;
	default: //showing the index page for the theme
		include($ecom_themename.'/default.php');
}
?>
