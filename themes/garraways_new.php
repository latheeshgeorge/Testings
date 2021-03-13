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
//$publickey 	= "6Lf9Y8ESAAAAAMuNMA0GiRxL32Gg14XE5gqA_n5p"; // local
//$privatekey = "6Lf9Y8ESAAAAAO-r1U6IL7INGvw4K4a8_YwvyVN6"; // local

$publickey 	= "6Ld0YsESAAAAAH5l1IUMpwdZJKE05UNz_D3iHyMY"; // live
$privatekey = "6Ld0YsESAAAAAAn5q7krNF_RRruuS8HEILzInDSh"; // live

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
		$address = "contactus@garraways.co.uk";
		//$address = "sony.joy@thewebclinic.co.uk";
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

function Google_conversion_onpage()
{
	global $ecom_siteid,$ecom_isadword,$db,$sql_outerprod,$ecom_adword_conversionid;
	if($ecom_siteid==61) // garraways
	{
		if($ecom_isadword)
		{
			switch($_REQUEST['req'])
			{
				case 'categories':
					$newconv_prodid = array();
					$newconv_pagetype = 'category';
					$newconv_totalval = '';
				break;
				case 'prod_detail':
					$newconv_prodid = array($_REQUEST['product_id']);
					$newconv_pagetype = 'product';
					$newconv_retprod = $db->query($sql_outerprod);
					if($db->num_rows($newconv_retprod))
					{
						$newconv_rowprod 	= $db->fetch_array($newconv_retprod);
						
						if($newconv_rowprod['product_discount']>0)
						{
							switch($newconv_rowprod['product_discount_enteredasval'])
							{
								case 0: //%
									$newconv_totalval = $newconv_rowprod['product_webprice'] - ($newconv_rowprod['product_webprice']*$newconv_rowprod['product_discount']/100);
								break;
								case 1: // discount val
									$newconv_totalval = $newconv_rowprod['product_webprice'] - $newconv_rowprod['product_discount'];
									if($newconv_totalval<0)
										$newconv_totalval='';
								break;
								
								case 2: // direct discount price
									$newconv_totalval = $newconv_rowprod['product_discount'];
								break;
							};
						}
						else
						{
							$newconv_totalval = $newconv_rowprod['product_webprice'];
						}
						
					}	
					/*$newconv_price_arr = show_Price($newconv_rowprod,$price_class_arr,'prod_detail',false,4);
					print_r($newconv_price_arr);
					if ($newconv_price_arr['discounted_price'])
						$newconv_price_str = $newconv_price_arr['discounted_price'];
					else
						$newconv_price_str = $newconv_price_arr['base_price'];
					$newconv_totalval = trim(str_replace('&pound;','',$newconv_price_str));*/
					
					
					
				break;
				case 'cart':
					switch ($_REQUEST['cart_mod'])
					{
						case 'clear_cart':
						case 'show_checkout':
						case 'show_orderplace_preview':
						case 'show_checkoutfailed': 
							// no action
						break;
						case 'show_cart': // case of viewing the normal cart page
							// Get the ids of products in the current cart
							$newconv_sessionid  	= session_id();	// Get the session id for the current section
							$sql_cartprod = "SELECT distinct products_product_id 
												FROM 
													cart 
												WHERE 
													sites_site_id = $ecom_siteid 
													AND session_id='".$newconv_sessionid."'";
							$ret_cartprod = $db->query($sql_cartprod);
													
							$newconv_totstr		= get_session_var('cart_total');
							
							if ($db->num_rows($ret_cartprod)>0)
							{
								while ($row_cartprod = $db->fetch_array($ret_cartprod))
								{
										$newconv_prodid[] = $row_cartprod['products_product_id'];
								}
							}
							else	
								$newconv_prodid 	= array();
							
							if($newconv_totstr=='' or $newconv_totstr==0)
							{
								$newconv_totalval 	= '';
							}
							else
								$newconv_totalval 	= $newconv_totstr;
							
							$newconv_pagetype 	= 'cart';
						break;
						case 'show_checkoutsuccess': // successfull checkout 
							if($_REQUEST['passorder_id'])
							{
								$passed_order_id = $_REQUEST['passorder_id'];
								$sql_ord_conv = "SELECT order_totalprice 
									FROM 
										orders 
									WHERE 
										order_id=".$passed_order_id." 
										AND sites_site_id=$ecom_siteid 
									LIMIT 
										1";
								$ret_ord_conv = $db->query($sql_ord_conv);
								if($db->num_rows($ret_ord_conv))
								{
									$row_ord_conv 		= $db->fetch_array($ret_ord_conv);
									$total_price 	= $row_ord_conv['order_totalprice'];
									// Get the distinct ids of products in this order
									$sql_convproddet = "SELECT distinct products_product_id 
															FROM
																order_details 
															WHERE 
																orders_order_id = $passed_order_id";
									$ret_convproddet = $db->query($sql_convproddet);
									$newconv_prodid = array();
									if($db->num_rows($ret_convproddet))
									{
										while ($row_convproddet = $db->fetch_array($ret_convproddet))
										{
											$newconv_prodid[] = $row_convproddet['products_product_id'];
										}
									}	
									$newconv_pagetype = 'purchase';
									$newconv_totalval = $total_price;
								}
							}
							else
							{
								$newconv_prodid = array();
								$newconv_pagetype = 'other';
								$newconv_totalval = '';
							}
						break;
					}
				break;
				default:
					if($_REQUEST['req']=='') // case of home page
					{
						$newconv_prodid = array();
						$newconv_pagetype = 'home';
						$newconv_totalval = '';
					}
					else // case of pages which are not mentioned in the above cases
					{
							$newconv_prodid = array();
							$newconv_pagetype = 'other';
							$newconv_totalval = '';
					}
			};
			//ob_start();
			
			if($newconv_pagetype!='')
			{
		?>
				<script type="text/javascript">
					var google_tag_params = {
					<?php
					if(count($newconv_prodid)>1)
					{
						$newconv_str = '';
						for($newconv_i=0;$newconv_i<count($newconv_prodid);$newconv_i++)
						{
							if ($newconv_str!='')
								$newconv_str .=',';
							$newconv_str .= "'".$newconv_prodid[$newconv_i]."'";
						}
					?>
						ecomm_prodid: [<?php echo $newconv_str?>],
					<?php
					}
					else
					{	
					?>
						ecomm_prodid: '<?php echo $newconv_prodid[0]?>',
					<?php	
					}	
					?>
					ecomm_pagetype: '<?php echo $newconv_pagetype?>',
					ecomm_totalvalue: '<?php echo $newconv_totalval?>'
					};
				</script>
				<script type="text/javascript">

				/* <![CDATA[ */

				var google_conversion_id = <?php echo $ecom_adword_conversionid?>;

				var google_custom_params = window.google_tag_params;

				var google_remarketing_only = true;

				/* ]]> */

				</script>

				<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">

				</script>

				<noscript>

				<div style="display:inline;">

				<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/<?php echo $ecom_adword_conversionid?>/?value=0&amp;guid=ON&amp;script=0"/>

				</div>

				</noscript>
		<?php
			}
				//$newconv_content = ob_get_contents();
				//ob_end_clean();
				//echo $newconv_content;
		}
		
	}
}



$insurance_cat = array(78052,77855,77704,77690,77753,78053,77702,77871,77866,77864);
global $insurance_cat;
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
