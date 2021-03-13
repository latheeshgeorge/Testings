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

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();

$publickey 	= "6LfaSVkUAAAAAFTu0OxsyfD1VnGIWIHe-ovZ-DQX"; // live
$privatekey = "6LfaSVkUAAAAAJcwPrxz--Fh38e2KCZU4qA63w-M"; // live

?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<?php
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
// Section to send the Request a quote details for garraways site
if ($_REQUEST['Quote_Submitted']==1)
{
	$show_error = 0;
	/*if ($_POST["recaptcha_response_field"])
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
	*/
	if($_POST["g-recaptcha-response"]!='')
		{
		
					$show_error = 0;
		} 
		else 
		{
					# set the error code so that we can display it
					//$error = $resp->error;
					$show_error = 1;
		}  
	if($show_error==0)
	{
	
	
		//$message = "Name : " .  $_REQUEST["name"]. "\nEmail Id : " . $_REQUEST["emailid"]. "\nAddress : " . $_REQUEST["address"] . "\nPost code : " . $_REQUEST["postcode"] . "\nTel Number : " . $_REQUEST["telno"]. "\nProperty Type : " . $_REQUEST["ptype"]. "\nYou : " . $_REQUEST["areyou"];
		
		$message = "Name : " .  $_REQUEST["str_title"].' '.$_REQUEST["str_firstname"].' '.$_REQUEST["str_surname"]. "
					\nBuilding No : " . $_REQUEST["str_buildingno"]. "
					\nStreet Name : " . $_REQUEST["str_streetname"]. "
					\nTown : " . $_REQUEST["str_town"]. "
					\nCounty : " . $_REQUEST["str_county"] . "
					\nPost code : " . $_REQUEST["str_postcode"] . "
					\nPhone : " . $_REQUEST["str_phone"]. "
					\nEmail Id : " . $_REQUEST["str_emailid"]. "
					\nProperty Type : " . $_REQUEST["ptype"]. "
					\nYou : " . $_REQUEST["areyou"]. "
					\nNotes : ".$_REQUEST["str_notes"];
			
		$_REQUEST["Email"] = "enquiries@discount-mobility.co.uk";
		$headers = "From: " . $_REQUEST["name"] . " <" . $_REQUEST["Email"] . ">";
		//$address = "online.enquiries@discount-mobility.co.uk, Scott@scootamart.com";
		//$address = "sony.joy@thewebclinic.co.uk";
		$address = "jm@thewebclinic.co.uk";
		mail($address, "Stairlift information pack & quote", $message,$headers);
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
/* Start ---- Category array to handle the case of terms and conditions checkbox in cart page */
	
	$tc_cart_arr = array(
							//5955 => 'motor', //http://v4demo41.arys.net/brands-c5955.html/local
							//5953 => 'motor', //http://v4demo41.arys.net/home-accessories-c5953.html/local
							
							78052=> 'motor',
							78053=> 'motor',
							77871=> 'motor',
							77855=> 'motor',
							78010=> 'motor',
							77703=> 'motor',
							78121=> 'motor',
							77865=> 'motor',
							
							//6001 => 'manual', //http://v4demo41.arys.net/accessory-sets-c6001.html/local
							
							77695=> 'manual',
							77767=> 'manual',
							77821=> 'manual',
							77726=> 'manual',
							77700=> 'manual',
							77737=> 'manual'
							//,5954=>'manual'
							
						);
	$tckey_motor_arr['motor'] = array(
								'3m'=>'http://rescs.premiercare.info/keyfacts/3MonthScooterKeyFactsVer002_001_01-14.pdf',
								'y'=>'http://rescs.premiercare.info/keyfacts/ScooterInsuranceWarrantyKeyFactsVer002_001_01-14.pdf'
								);
	$tckey_motor_arr['manual'] = array(
								'3m'=>'http://rescs.premiercare.info/keyfacts/3MonthWheelchairInsuranceKeyFactsVer002_001_01-14.pdf',
								'y'=>'http://rescs.premiercare.info/keyfacts/WheelchairInsuranceKeyFactsVer002_001_01-14.pdf'
								);	
	
	$tc_motor_arr['motor'] = array(
								'3m'=>'http://dealer.premiercare.info/introducer/12771/TOB12771.pdf',
								'y'=>'http://dealer.premiercare.info/introducer/12771/IDD12771.pdf'
								);
	$tc_motor_arr['manual'] = array(
								'3m'=>'http://dealer.premiercare.info/introducer/12771/TOB12771.pdf',
								'y'=>'http://dealer.premiercare.info/introducer/12771/IDD12771.pdf'
								);									
								
														 
	$tc_varcheck_arr['3monthfreeinsurance'] 	= '3m';
	$tc_varcheck_arr['3monthsfreeinsurance']	= '3m';
	$tc_varcheck_arr['1yearinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearsinsurance'] 		= 'y';
	$tc_varcheck_arr['3yearinsurance'] 			= 'y';
	$tc_varcheck_arr['3yearsinsurance'] 		= 'y';
	$tc_varcheck_arr['4yearinsurance'] 			= 'y';
	$tc_varcheck_arr['5yearsinsurance'] 		= 'y';
	
	$tc_varcheck_arr['1yearstandardinsurance'] 		= 'y';
	$tc_varcheck_arr['2yearsstandardinsurance'] 	= 'y';
	$tc_varcheck_arr['3yearsstandardinsurance'] 	= 'y';
	$tc_varcheck_arr['1yearplusinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearsplusinsurance'] 		= 'y';
	$tc_varcheck_arr['3yearsplusinsurance'] 		= 'y';
	
	
	$tc_varcheck_arr['year1'] 		= 'y';
	$tc_varcheck_arr['year2'] 		= 'y';
	$tc_varcheck_arr['year3'] 		= 'y';
	$tc_varcheck_arr['year4'] 		= 'y';
	
	$tc_varcheck_arr['1year(79.80)'] 		= 'y';
	$tc_varcheck_arr['2year(139.80)'] 		= 'y';
	$tc_varcheck_arr['3year(199.80)'] 		= 'y';
	$tc_varcheck_arr['4year'] 		= 'y';
	
	
	
	function tc_remove_spaces ($str)
	{
		return str_replace(' ','',$str);
	}



//if($ecom_siteid==104)
{
$insurance_cat =  array();
$insurance_cat = array(78052,77855,77704,77690,77753,78053,77702,77871,77866,77864,78121);
global $insurance_cat;
$cust_id 					= get_session_var("ecom_login_customer");
$show_cart_password         = 0;
if(!$cust_id)
{
	$show_cart_password     = 1;
	global $show_cart_password;
}
}
function show_finanacebanner($row_prod)
{
	global $db,$ecom_siteid,$Settings_arr;
	$price_arr = show_Price($row_prod,$price_class_arr,'','',6);
	//print_r($price_arr);
	      if($price_arr['discounted_price'])
			{
				$calcprice = ($price_arr['discounted_price']/.95);
			}
			else
			{
				$calcprice = ($price_arr['base_price']/.95);
			}
			if($calcprice and ($ecom_siteid== 106 or $ecom_siteid==104) and $calcprice>277.78)
			{				
				echo "<div class='finanace_banner'></div>";
			}

}
// Function to create a message signature fidelity payment
function createSignature(array $data, $key) {
	// Sort by field name
ksort($data);
// Create the URL encoded signature string 
$ret= http_build_query($data, '', '&');
// Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)
$ret= str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);
// Hash the signature string and the key together
return hash('SHA512',$ret . $key);
}
// ################################################################
// Decision making section for the layout to be shown 
// ################################################################
//echo $_REQUEST['req'];
include($ecom_themename.'/mobilehome.php');
?>
