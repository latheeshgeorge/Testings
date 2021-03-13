<?php
/*#################################################################
# Script Name 	: cart_submit.php
# Description 		: Cart intermediate page
# Coded by 		: Sny
# Created on		: 5-Dec-2008
# Modified by		:
# Modified On		:
#################################################################*/
	include_once("functions/functions.php");
	include('includes/session.php');
	require_once("config.php");
	require("includes/payment.php");
	require("includes/price_display.php");
	$save_det = trim($_REQUEST['cart_mod']);
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	// Calling the function to get all the general settings variables and store it in an array
	//$Settings_arr 			= getGeneralSettings();

	// Calling function ot get all the price display settings for the current site
	//$PriceSettings_arr		= getPriceDisplaySettings();
	
	// Including the general settings array file
	if(file_exists($image_path.'/settings_cache/general_settings.php'))
		include "$image_path/settings_cache/general_settings.php";
	
// Including the price display settings array file
	if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
		include "$image_path/settings_cache/price_display_settings.php";	

	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']	= getCaptions('COMMON');

	// Calling the function to get the details of default currency
	$default_Currency_arr	= get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr			= get_session_var('SEL_CURR');
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
	}
	// Handling the case of coming to this page directly
	if(!$save_det)
	{
		displayInvalidInput();
		exit;
	}
	// Saving the checkout details
	save_CheckoutDetails();
	if ($cart_msg)
		$_REQUEST['hold_section'] = $cart_msg;
	
	if($ecom_siteid==103)
	{
		$bgcolor = '#4DAFBB';
		$color 	= '#FFFFFF';
	}
	else
	{
		$bgcolor = '#CC0000';
		$color = '#FFFFFF';
	}	
	echo "<div style='position:absolute; left:0;top:0;padding:5px;background-color:".$bgcolor.";color:".$color.";font-size:12px;font-weight:bold'>Loading...</div>.";
	echo "
				<script type='text/javascript'>
					window.location = '".$ecom_selfhttp.$ecom_hostname."/cart.html';
				</script>
			";	
	exit;
?>
