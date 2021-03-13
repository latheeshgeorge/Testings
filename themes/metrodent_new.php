<?php
/*#################################################################
# Script Name 		: metrodent_new.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 			: Sobin Babu
# Created on		: 31-July-2013
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
	$Name = $_POST["name"];
	$Email = $_POST["email"];
	$subject = $_POST["subject"];
	$msg = nl2br($_POST["message"]);
	$Message = "
				<html>
					<head>
						<title>$title</title>
					</head>
					<body>
						<p><strong>Name :</strong> $name</p>
						<p><strong>Email :</strong> $email</p>
					    <p><strong>Subject :</strong> $subject</p>
						<p><strong>Message:</strong> $msg</p>
					</body>
				</html>
				";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: " . $Name . " <" .$Email . ">";  

	//if($ecom_siteid==70)
	{
	    //$address = "latheeshgeorge@gmail.com";
		$address = "orders@beverleyhillshome.co.uk";
	}
	mail($address, "Contact Us Form", $Message, $headers);
     echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			window.location='http://".$_SERVER['HTTP_HOST']."';
			</script>
		";
}
if($_REQUEST['product_id'])
{
	$prodId= $_REQUEST['product_id'];
	$cat_url ='';
	$check_arr = is_grid_display_enabled_prod($prodId);
		if($check_arr['enabled']==true)
		{ 
		    $def_catid = $check_arr['def_catid'] ;
		    if($def_catid > 0)
		    {
			$def_catname = $check_arr['category_name'] ;
			$cat_url =  url_category($def_catid,$def_catname,1);
			if($cat_url!='')
			echo "<script type='text/javascript'>window.location='".$cat_url."'</script>";			
			}
	    }	

}

// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo "request - ".$_REQUEST['req'];
switch($_REQUEST['req'])
{	
	case '':
		include($ecom_themename.'/home.php');
	break;
	/*case 'prod_review': // product review
	case 'site_review': // site review
	case 'email_friend': // login home page
	case 'prod_free_delivery': // login home page
	case 'newsletter':
	case 'survey_result':
	case 'survey':
	case 'payonaccountdetails':
	case 'downloadable_prod':
	case 'bonus_details':
	case 'bulkdisc':
	case 'prod_shelf':
	case 'prod_shop':
	case 'best_sellers':
	case 'preorder':
	case 'static_page':
	case 'sitemap':
	case 'site_faq':
	case 'site_help':*/
	/*case 'vsp_success':
	case 'vsp_fail':
	case 'general_downloads':*/
		//include($ecom_themename.'/default.php');
	//break;
	case 'search':
	if($_REQUEST['search_meth'] == 'advanced')
	{
	   include($ecom_themename.'/cart.php');
	}
	else 
       include($ecom_themename.'/default.php');
	break;	 
    case 'prod_detail':
		 include($ecom_themename.'/prod-detail.php');
	break;
	case 'categories':
		 include($ecom_themename.'/category.php');
	break;
	case 'cart':
	case 'callback':
	case 'voucher':
	case 'enquiry': // login home page
	case 'registration': // login home page
	case 'myprofile': // login home page
	case 'pricepromise': // login home page
	case 'myaddressbook': // myaddressbook
	case 'myfavorites': // myfavorites
	case 'login_home': // login home page
	case 'orders': // orders
	case 'wishlist': // wishlist
	case 'compare_products':
	case 'verifyemail': 
	case 'savedsearch':
		include($ecom_themename.'/cart.php');
	break;
	default: // none of above cases
		include($ecom_themename.'/default.php');
		
}
?>
