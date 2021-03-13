<?php
/*#################################################################
# Script Name 		: nature.php
# Description 		: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 			: Sny
# Created on		: 10-Aug-2009
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
$site_key = "6LdomsIZAAAAAEHB6iO_gF4b_cQeHICrykPTGpih";
$private_key = "6LdomsIZAAAAAH9iHlHe_5vChvFGmAXX6fHtlykG";

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
	$fName		=	$_POST["txt_fname"];
	$sName		=	$_POST["txt_sname"];
	$Email		=	$_POST["txt_email"];
	$Phone		=	$_POST["txt_phone"];
	$Comments	=	nl2br($_POST["txt_comments"]);
	$Title		=	$_POST['cbo_title'];
	$Subject	=	"Contact Form";
	
	$Message = "
				<html>
				<head>
					<title>$Title</title>
				</head>
				<body>
					<p><strong>First Name :</strong> $fName</p>
					<p><strong>Surname :</strong> $sName</p>
					<p><strong>Email :</strong> $Email</p>
					<p><strong>Phone :</strong> $Phone</p>
					<p><strong>Comments :</strong> $Comments</p>
				</body>
				</html>
	";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 
	$headers .= "From: " . $fName . " <" .$Email . ">"; 
	$headers .= "Cc: sales@healthstore.uk.com\r\n";
 

	//if($ecom_siteid==70)
	{
	    $address = "countryliving@btconnect.com";
	    //$address = "latheeshgeorge@gmail.com";
	}
	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$private_key.'&response='.$_POST['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);

    if($responseData->success) {
		$error_val ="";
	}
	else
	{
	 $error_val = "Robot verification failed, please try again.";	
	}
	if($error_val)
		{
			echo "
				<script type='text/javascript'>
				alert(' Robot verification failed !!!');
				location.href='https://www.healthstore.uk.com/pg89/contact-us.html';
				</script>
			";
		}
		else
		{
			$address = array("countryliving@btconnect.com","sales@healthstore.uk.com");
			$ret_err =mail_Phpmaler_admin_new($address,"Contact Form",nl2br($Message),"sales@healthstore.uk.com",$ecom_hostname,$email_headers);

	//mail($address, $Subject, $Message, $headers);
	echo "
		<script type='text/javascript'>
		alert('Message Sent Succesfully');
		window.location='".$ecom_selfhttp.$_SERVER['HTTP_HOST']."';
		</script>
		";
	}
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
switch($_REQUEST['req'])
{	
	case 'compare_products':
		include($ecom_themename.'/compare_products.php');
	break;
	case '':// Index page
	case 'categories':
	case 'preorder':
	case 'best_sellers':
	case 'prod_shelf':
	case 'prod_shop';
	case 'login_home':
	case 'showpurchaseall':
	case 'category_showall':
	case 'favprod_showall':
	case 'bulkdisc':
		include($ecom_themename.'/default.php');
	break;
	case 'search':
		if($_REQUEST['search_meth']=='advanced') // case of displaying advanced search
			include($ecom_themename.'/special.php');
		else // case of showing search results
			include($ecom_themename.'/default.php');
	break;
	case 'prod_detail':
		if($_REQUEST['prod_mod']=='comp_list') // case of compare products in product html
			include($ecom_themename.'/default.php');
		else 
                {
                    if($Settings_arr['themes_layouts_layout_id']==0) // case if this field is not set from general settings section or no layout exists
                        include($ecom_themename.'/special.php'); 
                    else
                    {
                        switch($Settings_arr['themes_layouts_layout_code']) // Get the layout code for current layout id
                        {
                                case 'prod_detail':
                                    include($ecom_themename.'/prod_detail.php');
                                break;
                                case 'special':
                                    include($ecom_themename.'/special.php'); 
                                break;
                        };
                    }
                }
	break;
	case 'combo_deal':
		if($_REQUEST['combo_mod']!='showall')
			include($ecom_themename.'/default.php');
		else
			include($ecom_themename.'/special.php');
	break;
	default: // none of above cases
		include($ecom_themename.'/special.php');
		
}
?>
