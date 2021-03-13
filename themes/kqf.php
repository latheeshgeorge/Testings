<?php
/*#################################################################
# Script Name 	: bshop.php
# Description 	: This page decides the actual pages to be loaded based on the value of the variable request
# Coded by 		: Sny
# Created on	: 05-Dec-2007
# Modified by	: Sny
# Modified On	: 07-Jan-08
#################################################################*/

if(trim($_SERVER['REDIRECT_STATUS'])==404)
{
	if(trim($_SERVER['REDIRECT_URL'])=='/sitemap.html')
	{
		$_REQUEST['req'] = 'error';
	}	
}

// Moving the current session id to a variable
$sess_id = session_id();

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
	//$address = "sony.joy@thewebclinic.co.uk";
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
$discthm_group_shelf_array = $discthm_group_static_array =$discthm_group_prod_array =$disgthm_group_cat_array= $custgroup_special_arr = $custgroup_group_arr = array();
$custgroup_special_arr 		= customer_group_special_arrays();
if(count($custgroup_special_arr))
{
$discthm_group_shelf_array 	= $custgroup_special_arr['shelf'];
$discthm_group_static_array = $custgroup_special_arr['static'];
$discthm_group_prod_array 	= $custgroup_special_arr['product'];
$disgthm_group_cat_array 	= $custgroup_special_arr['category'];
$discthm_group_custgroup_val = $custgroup_special_arr['group'][0];


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
//$kqf_arr = array(70=>"http://www.kqf-foods.com/schools",71=>"http://www.kqf-foods.com/trade",76=>"http://www.kqf-foods.com/trade",75=>"http://www.kqf-foods.com/trade",72=>"http://www.kqf-foods.com/trade",74=>"http://www.kqf-foods.com/trade");
$kqf_schoolarr = array(70=>"http://www.kqf-foods.com/schools");
$kqf_tradearr = array(71=>"http://www.kqf-foods.com/trade",76=>"http://www.kqf-foods.com/trade",75=>"http://www.kqf-foods.com/trade",72=>"http://www.kqf-foods.com/trade",74=>"http://www.kqf-foods.com/trade"); 
$kqf_schoolcatmenuid = 365;
$kqf_tradecatmenuid = 366;

$kqf_topband_already_displayed = array();

foreach ($kqf_schoolarr as $kk=>$vv)
{
	$kqf_arr[$kk]=$vv;
}
foreach ($kqf_tradearr as $kk=>$vv)
{
	$kqf_arr[$kk]=$vv;
}
	//43 local
	//102 live
	$rd_url = '';
	if($ecom_siteid==102 && $_REQUEST['req']=='login_home')
	{ 
		if(isset($kqf_arr))
		{
			/* 
		 $sql_custgroup_id = " SELECT customer_discount_group_cust_disc_grp_id FROM customer_discount_customers_map WHERE customers_customer_id = $customer_id LIMIT 1 ";
		 $ret_custgroup_id = $db->query($sql_custgroup_id);
		 $row_custgroup_id = $db->fetch_array($ret_custgroup_id);
		 $grp_id           = $row_custgroup_id['customer_discount_group_cust_disc_grp_id'];
			 if(array_key_exists($grp_id,$kqf_arr))
			 { 
				$rd_url = $kqf_arr[$grp_id];
				
			 }
			 */ 
			 if(array_key_exists($discthm_group_custgroup_val,$kqf_arr))
			 { 
				$rd_url = $kqf_arr[$discthm_group_custgroup_val];
			 }
		}

	}
	if($rd_url!='')
	{
	  echo "<script> window.location='$rd_url'; </script> ";
	  exit;
	}
$show_login = false;
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
		include($ecom_themename.'/default.php');
	break;
	case 'categories':
	   //if($_REQUEST['category_id']==77150 || $_REQUEST['category_id']==77210)
	    if($_REQUEST['category_id']==77210)
	   {
		 global $show_login;
		 $show_login = true;
		 include($ecom_themename.'/categoryspl.php');
	   }
	   else
	   {
	     include($ecom_themename.'/default.php');
	   }
	break;
	default: //showing the index page for the theme
		include($ecom_themename.'/default.php');
}
?>
