<?php
/*#################################################################
# Script Name 	: bshop.php
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
// Section to send the Request a quote details for garraways site
if ($_REQUEST['Quote_Submitted']==1)
{
	$message = "Name : " .  $_REQUEST["Contact"]. "\nBusiness Name : " . $_REQUEST["Business_Name"] . "\nAddress1 : " . $_REQUEST["Address1"] . "\nAddress2 : " . $_REQUEST["Address2"];
	$message = $message . "\nTown : " . $_REQUEST["Town"] . "\nPostcode : " . $_REQUEST["PostCode"] ."\nEmail : " . $_REQUEST["Email"];
	$message = $message . "\nTelephone Number : " . $_REQUEST["Tel"];
	$message = $message . "\nBusiness Type : " . $_REQUEST["Business"] . "\nEstablished : " . $_REQUEST["Established"];
	
	if($_REQUEST["Fax"]) {  
		$message = $message . "\nFax Number : " . $_REQUEST["Fax"] .  "\n";
	}   
	
		$message = $message . "\nProduct : " . $_REQUEST["Product"] . "\nProduct Price : " . $_REQUEST["Product_Price"] . "\nLease Period : " . $_REQUEST["Lease_Period"];

	if($_REQUEST["Comments"]) {     
		$message = $message . "\nComments : " . $_REQUEST["Comments"]; 
	}   
  	if($_REQUEST["ContactRequested"])
	{  
		$message = $message . "\n\nPlease Contact Me As Soon As Possible About This Subject.\nThank You";
	}
	$email = $_REQUEST["email"];
	$headers = "From: " . $_REQUEST["Contact"] . " <" . $_REQUEST["Email"] . ">";
	$address = "leasing@garraways.co.uk";
	mail($address, "Leasing Form", $message,$headers);
	echo "
			<script type='text/javascript'>
			alert('Quote Send Successfully');
			</script>
		";
}

// Section to send the Contact Us details for garraways site
if ($_REQUEST['ContactUs_Submitted']==1)
{
	$message = "Name : " .  $_REQUEST["Name"] . "\nCompany Name : " . $_REQUEST["Company"] . " \nAddress1 : " . $_REQUEST["Address1"] . "\nAddress2 : " . $_REQUEST["Address2"];
	$message = $message . "\nTown : " . $_REQUEST["Town"] . "\nPostcode : " . $_REQUEST["Postcode"] ."\nEmail : " . $_REQUEST["Email"];
	$message = $message . "\nTelephone Number : " . $_REQUEST["Tel"];
	if($_REQUEST["Fax"]) {  
		$message = $message . "\nFax Number : " . $_REQUEST["Fax"] .  "\n";
	}   
	if($_REQUEST["Product"]) {  
		$message = $message . "\nProduct : " . $_REQUEST["Product"];
	}
	if($_REQUEST["SubjectOther"]) {
		$message = $message . "\nSubject : " . $_REQUEST["SubjectOther"]; 
	}
	else
	{ 
		$message = $message . "\nSubject : " . $_REQUEST["Subject"]; 
	}
	if($_REQUEST["Brochure1"]) {
		$message = $message . "\nRequested : Full Brochure Pack";
	}
	else
	{
		if($_REQUEST["Brochure2"]) 
		{
		$message = $message . "\nRequested: Espresso Machines";
		}
		if($_REQUEST["Brochure3"]) 
		{
		$message = $message . "\nRequested: Bean To Cup";
		}
		if($_REQUEST["Brochure4"]) 
		{
		$message = $message . "\nRequested: Cappuccino Systems";
		}
		if($_REQUEST["Brochure5"]) 
		{
		$message = $message . "\nRequested: Hot Choc";
		}
		if($_REQUEST["Brochure6"])
		{
		$message = $message . "\nRequested: Kenco Singles";
		}
		if($_REQUEST["Brochure7"]) 
		{
		$message = $message . "\nRequested: Filter Coffee Equipment";
		}
	}
	if($_REQUEST["Brochure8"]) 
	{
		$message = $message . "\nRequested: Include Product Price List\n";
	}
	if($_REQUEST["Comments"]) {     
		$message = $message . "\nComments : " . $_REQUEST["Comments"]; 
	}   
  	if($_REQUEST["ContactRequested"])
	{  
		$message = $message . "\n\nPlease Contact Me As Soon As Possible About This Subject.\nThank You";
	}
	$headers = "From: " . $_REQUEST["Name"] . " <" .$_REQUEST["Email"] . ">";
	$address = "contactus@garraways.co.uk";
	mail($address, "Contact Us Form", $message, $headers);
echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			</script>
		";
}
//$ecom_is_country_textbox = 1;
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
	case 'compare_products':
		include($ecom_themename.'/compare_products.php');
	break;
	case 'cart':
		include($ecom_themename.'/cart.php');
	break;
	default: //showing the index page for the theme
		include($ecom_themename.'/default.php');
}
?>