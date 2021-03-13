<?
	/*#################################################################
	# Script Name 	: index.php
	# Description 	: This is the common page which will be loaded when a site is referenced
	# Coded by 		: Sny
	# Created on	: 03-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	require("functions/functions.php");
	require("includes/urls.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/cartCalc.php");
	require("classes/mime.php");
	//The required constants for the database and also for the site and also create an object to access database.
	require("config.php");
	// Handling the case of hit registration for products and categories while viewing the product details and category details page
	if($_REQUEST['req'] == "prod_detail") {
		set_cookie_product($_REQUEST['product_id']);
	} else if($_REQUEST['req'] == "categories") {
		set_cookie_category($_REQUEST['category_id']);
	}
	require("includes/session_log.php"); // Including the file which records the site hits
	$display_pdf = true;
	include ("$ecom_themepath"); // Calling the required theme file
	$db->db_close();
?>

