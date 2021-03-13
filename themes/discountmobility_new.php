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

if($ecom_siteid==108)
{
	echo "<center style='color:#FF0000;font-size:14px;font-weight:bold'><br><br><br>This domain is under construction</center>";
	exit;
	
}
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();

// ======================================================
// Settings to show the captcha code
// ======================================================
//$publickey 	= "6Lf9Y8ESAAAAAMuNMA0GiRxL32Gg14XE5gqA_n5p"; // local
//$privatekey = "6Lf9Y8ESAAAAAO-r1U6IL7INGvw4K4a8_YwvyVN6"; // local

$publickey 	= "6LfaSVkUAAAAAFTu0OxsyfD1VnGIWIHe-ovZ-DQX"; // live
$privatekey = "6LfaSVkUAAAAAJcwPrxz--Fh38e2KCZU4qA63w-M"; // live

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
?>

<?php
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

// Section to send the Contact Us details for garraways site
if ($_REQUEST['ContactUs_Submitted']==1)
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
			$cnt_ent = 0;
			$message = $message . "\nWhat Kind Of Beverage Equipment You Are Interested In?";
			if($_REQUEST["Brochure2"]) 
			{
			$message = $message . "\nCommercial Espresso Machines";
			$cnt_ent = 1;
			}
			if($_REQUEST["Brochure3"]) 
			{
			$message = $message . "\nBean To Cup Systems";
			$cnt_ent = 1;
			}
			if($_REQUEST["Brochure4"]) 
			{
			$message = $message . "\nInstant (Soluble) Cappuccino Systems";
			$cnt_ent = 1;
			}
			if($_REQUEST["Brochure5"]) 
			{
			$message = $message . "\nHot Chocolate Systems";
			$cnt_ent = 1;
			}
			if($_REQUEST["Brochure6"])
			{
			$message = $message . "\nKenco Singles Brewer";
			$cnt_ent = 1;
			}
			if($_REQUEST["Brochure7"]) 
			{
			$message = $message . "\nFilter Coffee Equipment";
			$cnt_ent = 1;
			}
		}
		if($_REQUEST["Brochure8"]) 
		{
			$message = $message . "\nBlendtec Blenders\n";
			$cnt_ent = 1;
		}
		if($cnt_ent==0) 
		{
			$message = $message . "\nN/A\n";
			$cnt_ent = 1;
		}
		if($_REQUEST["Comments"]) {     
			$message = $message . "\nComments : " . $_REQUEST["Comments"]; 
		}   
		if($_REQUEST["ContactRequested"])
		{  
			$message = $message . "\n\nPlease Contact Me As Soon As Possible About This Subject.\nThank You";
		}
		$headers = "From: " . $_REQUEST["Name"] . " <" .$_REQUEST["Email"] . ">";
		//$address = "contactus@garraways.co.uk";
		$address = "sony.joy@thewebclinic.co.uk";
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
		//$address = "jm@thewebclinic.co.uk";
		 $address = "online.enquiries@discount-mobility.co.uk";
		$to_arr = array($address);
		mail_Phpmaler_admin_new($to_arr,"Stairlift information pack & quote",$message,$_REQUEST["Email"],$ecom_hostname,$headers,'');

		//mail($address, "Stairlift information pack & quote", $message,$headers);
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
// Section to send the Request a quote details for garraways site
if ($_REQUEST['quote_submitdiscount']==1)
{
		$show_error = 1;
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
	    if($_REQUEST["g-recaptcha-response"]=='')
		{       
					$show_error = 1;
		} 
		else 
		{
					# set the error code so that we can display it
					//$error = $resp->error;
					$show_error = 0;
		} 
	if($show_error==0)
	{
	$message = "Organisation Name : " .  $_REQUEST["org_name"]. "\nFull Name : " . $_REQUEST["full_name"] . "\nPosition : " . $_REQUEST["position"] . "\nTel Number : " . $_REQUEST["telephone"]. "\nMobile : " . $_REQUEST["mobile"]. "\nFax : " . $_REQUEST["fax"]. "\nEmail : " . $_REQUEST["email"]. "\nQuote Reference Number : " . $_REQUEST["ref_number"]. "\nDetails of products to quote : " . $_REQUEST["quote_details"];
		
	$_REQUEST["Email"] = "enquiries@discount-mobility.co.uk";
	$headers = "From: " . $_REQUEST["name"] . " <" . $_REQUEST["Email"] . ">";
	//$address = "online.enquiries@discount-mobility.co.uk, Scott@scootamart.com";
	//quotes@discount-mobility.co.uk
	//$address = "latheeshgeorge@gmail.com,sony.joy@thewebclinic.co.uk";
	//$address = "online.enquiries@discount-mobility.co.uk,jm@thewebclinic.co.uk,sony.joy@thewebclinic.co.uk";
	//$address = "sonyjoy007@gmail.com,sony.joy@thewebclinic.co.uk";
	$address = "online.enquiries@discount-mobility.co.uk";
	$to_arr = array($address);
	mail_Phpmaler_admin_new($to_arr,"Quote Request - www.discount-mobility.co.uk",$message,$_REQUEST["Email"],$ecom_hostname,$headers,'');
	//mail($address, "Quote Request - www.discount-mobility.co.uk", $message,$headers);
		$insert_array										= array();
		$insert_array['sites_site_id'] 						= $ecom_siteid;
		$insert_array['org_name'] 					= add_slash($_REQUEST['org_name']);
		$insert_array['org_otherdetails'] 			= add_slash($_REQUEST['quote_details']);
		//print_r($update_array);

		$insert_array['contact_full_name'] 					= add_slash($_REQUEST['full_name']);
		$insert_array['contact_position']	 				= add_slash($_REQUEST['position']);
		$insert_array['contact_tel'] 					= add_slash($_REQUEST['telephone']);
		$insert_array['contact_mobile'] 			= add_slash($_REQUEST['mobile']);
		$insert_array['contact_fax'] 				= add_slash($_REQUEST['fax']);
		$insert_array['contact_email'] 			= add_slash($_REQUEST['email']);
		$insert_array['contact_refno'] 		= add_slash($_REQUEST['ref_number']);
		$insert_array['date_added'] 		= 'now()';
		$insert_array['status'] 		= 'OPEN';
		
		//print_r($update_array);

		//print_r($update_array);

		$db->insert_from_array($insert_array, 'customer_productquotes_details');
		$insert_id = $db->insert_id();	
	/*
	echo "
			<script type='text/javascript'>
			alert('Details Send Successfully');
			</script>
		";
		*/ 
	//echo "<script type='text/javascript'>window.location='http://v4demo41.arys.net/quote-success-pg718.html'</script>"	;
		echo "<script type='text/javascript'>window.location='".$ecom_selfhttp."www.discount-mobility.co.uk/pg50384/quote-success.html'</script>"	;

	//http://www.discount-mobility.co.uk/pg50384/quote-success.html
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
$insurance_cat =  array();
$insurance_cat = array(78052,77855,77704,77690,77753,78053,77702,77871,77866,77864,78121);
global $insurance_cat;
$cust_id 					= get_session_var("ecom_login_customer");
$show_cart_password         = 0;
if(($ecom_siteid==72 || $ecom_siteid==104) and !$cust_id)
{
	$show_cart_password     = 1;
	global $show_cart_password;
}
function show_finanacebanner($row_prod,$class='finanace_banner')
{
	global $db,$ecom_siteid,$Settings_arr;
	$price_arr = show_Price($row_prod,$price_class_arr,'','',6);
	//print_r($price_arr);
	if($class=='finanace_banner')
	{
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
	else
	{
	 echo "<div class='".$class."'></div>";
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

function old_show_fontsizer()
{
	return;
?>
	<style type="text/css">
	.changeFont1 {
	float: left;
	padding: 5px 2px 2px 2px;
	cursor: pointer;
	color: #000;
	}
	.changeFont2 {
	float: left;
	padding: 3px 2px 2px 2px;
	cursor: pointer;
	color: #000;
	}
	.resetFont {
	float: left;
	padding: 8px 2px 2px 2px;
	cursor: pointer;
	color: #000;
	}
	.fontsize_text {
	float: left;
	font-weight: bold;
	padding: 8px 2px 2px 2px;
	color: #000;
	}
	.fontsize_outer{
	float: right;
margin-right: 9px;
height: 15px;
background-color: #FFF;
padding: 0 2px 0 2px;

	}

	</style>
	<script type="text/javascript">
	jQuery.noConflict();
	var $j = jQuery;
	function changeFont(siz)
	{
	$j('.external_main_wrapper').each(function() {
		
		$j(this).find("*").css('font-size', siz+'px');
	});

	$j('.top_cart_x_table').each(function() {
		
		$j(this).find("*").css('font-size', '15px');
	});
	$j('.category_con').each(function() {
		
		$j(this).find("*").css('font-size', '13px');
	});
	
	$j('.fontsize_outer').css('font-size', '12px');
	$j('.fontsize_text').css('font-size', '12px');
	$j('.resetFont').css('font-size', '12px');
	$j('.changeFont1').css('font-size', '16px');
	$j('.changeFont2').css('font-size', '18px');

	}
	function decreaseFont()
	{
	$j('.external_main_wrapper').each(function() {
		$j(this).find("*").css('font-size', '12px');
	});
	}
	function resetFont()
	{
	window.location = window.location;

	}
	</script>
	<div class='fontsize_outer'>
	<div class='fontsize_text'>Font Size: </div><div class='resetFont' onclick="resetFont()">A</div>
	<div class='changeFont1' onclick="changeFont(16)" style="font-size:16px">A</div>
	<div class='changeFont2' onclick="changeFont(18)" style="font-size:18px">A</div>
	</div>	
<?php
}

function show_fontsizer()
{
?>
	<style type="text/css">
	.changeFont1 {
	font-weight:bold;
	font-size:12px;	
	float: left;
	margin:2px;
	padding: 2px 2px 2px 2px;
	cursor: pointer;
	color: #000;
	text-align:center;
	}
	.changeFont2 {
	font-weight:bold;
	font-size:12px;
	float: left;
	margin:2px;
	padding: 2px 2px 2px 2px;
	cursor: pointer;
	color: #000;

	
	text-align:center;
	}
	.resetFont {
	font-weight:bold;
	font-size:12px;
	float: left;
	padding: 8px 2px 2px 10px;
	cursor: pointer;
	color: #000;
	}
	.fontsize_text {
	float: left;
	font-weight: bold;
	padding: 8px 2px 2px 2px;
	color: #000;
	}
	.fontsize_outer{
	<?php
	if($_REQUEST['req']=='cart')
	{
	?>
		float: left;
		margin-left: 5px;
		height: 15px;
		background-color: #FFF;
		padding: 3px 6px 0px 12px;
		width: 981px;
	<?php	
	}
	else
	{
		?>
		float: left;
		margin-left: 0px;
		height: 15px;
		background-color: #FFF;
		padding: 3px 6px 0px 12px;
		width: 981px;
	<?php
	}
	?>	
	}

	</style>
	<script type="text/javascript">
	jQuery.noConflict();
	var $j = jQuery;
	function changeFont(siz)
	{
	$j('.external_main_wrapper').each(function() {
		
		$j(this).find("*").css('font-size', siz+'px');
	});

	}
	function decreaseFont()
	{
	$j('.external_main_wrapper').each(function() {
		$j(this).find("*").css('font-size', '12px');
	});
	}
	function resetFont()
	{
	window.location = window.location;

	}
	
	
	var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.fn.center = function () {
    this.css("position","absolute");
  /*  this.css("top", (($j(window).height() - (this.outerHeight())+710)/ 2) + $j(window).scrollTop() + "px");*/
  alert($j(window).width() + ' - '+ this.outerWidth());
    this.css("left", (($j(window).width() - this.outerWidth()) / 2) + $j(window).scrollLeft() + "px");
    return this;
}


jQuery.browser = browser;
	
	$j(document).ready(function() {
	
    var currFFZoom = 1;
    var currIEZoom = 100;
    $j(".changeFont2").click(function(){
        var step;
        //only firefox sux in this case
        if ($j.browser.mozilla){
            step = 0.2;
            currFFZoom += step;
            $j('.external_main_wrapper').css('MozTransform','scale(' + currFFZoom + ','+ currFFZoom + ')');
            $j('.external_main_wrapper').css('transform-origin','0 0');
        }
        else
        {
            step = 15;
            currIEZoom += step;
            $j('body').css('zoom', ' ' + currIEZoom + '%');
        }
       $j('html,body').animate({scrollLeft: 210}, 200);
    });

    $j(".changeFont1").click(function(){
        var step;
        //only firefox sux in this case
        if ($j.browser.mozilla){
            step = 0.2;
            currFFZoom -= step;
            $j('.external_main_wrapper').css('MozTransform','scale(' + currFFZoom + ','+ currFFZoom +')');
            $j('.external_main_wrapper').css('transform-origin','0 0');
        }
        else
        {
            step = 15;
            currIEZoom -= step;
            $j('body').css('zoom', ' ' + currIEZoom + '%');
        }
        $j('html,body').animate({scrollLeft: 210}, 200);
    });
    
    $j(".resetFont").click(function(){
        var step;
        //only firefox sux in this case
        if ($j.browser.mozilla){
            step = 0.2;
            currFFZoom =1
            $j('.external_main_wrapper').css('MozTransform','scale(' + currFFZoom + ','+ currFFZoom + ')');
            $j('.external_main_wrapper').css('transform-origin','0 0');
        }
        else
        {
            step = 15;
            currIEZoom = 100;
            $j('body').css('zoom', ' ' + currIEZoom + '%');
        }
 
    });
    
});
	
	</script>
	<div class='fontsize_outer'>
	<div class='fontsize_text'>Zoom: </div>
	<div class='changeFont2' title="Zoom In"><img src="<?php url_site_image('zoomplus.png')?>" style="width:15px;height:15px;"></div>
	<div class='changeFont1' title="Zoom Out"><img src="<?php url_site_image('zoomminus.png')?>" style="width:15px;height:15px;"></div>
	<div class='resetFont' title="Reset">Reset</div>
	</div>	
	
	
<?php
}

//print_r($_REQUEST);
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
	case 'prod_detail':
		include($ecom_themename.'/proddetails.php');
	break;
	 
	//case 'cart':
	
	case 'search':
	case 'categories':
	case 'prod_shelf':
	case 'prod_shop':
	case 'preorder':
	case 'category_showall':
	case 'favprod_showall':
	case 'bulkdisc':
		include($ecom_themename.'/prodlist.php');
	break;
	case 'cartinter':
		include($ecom_themename.'/intermediate.php');
	break;
	default: //showing the index page for the theme
		include($ecom_themename.'/prodlist.php');
		//include($ecom_themename.'/default.php');
    break;
}
?>
