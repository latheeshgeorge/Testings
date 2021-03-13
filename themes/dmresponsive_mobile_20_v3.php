<?php
/*
<script type="text/javascript">

if(window.self !== window.top) {
window.location = 'https://www.thewebclinic.co.uk/suspended.html';
}else{
// console.log("no frame");
}

</script>
*/
?> 
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
//if($_SERVER['REMOTE_ADDR']!='220.225.193.237' AND $_SERVER['REMOTE_ADDR']!='122.164.123.111' AND $_SERVER['REMOTE_ADDR']!='192.168.2.20' AND $_SERVER['REMOTE_ADDR']!='192.168.2.13' AND $_SERVER['REMOTE_ADDR']!='182.72.159.170')
{
 // exit;
}
// Image Settings
define("IMG_MODE","image_bigpath");


define("IMG_SIZE",3);

//including mobile responsive specific functions
require("functions/responsive_functions.php");

// Including the components file and making a object for the components class
require ("themes/$ecom_themename/html/components.php"); 
$components = new components();
$privatekey = "6LcJcxEUAAAAAIfR2esO61yor76udR4mefgxbKST";//live
// $privatekey = "6LcvchEUAAAAAM4AmcVbOKgufArTKRHTONIPeo2s";//local
// ======================================================
// Settings to show the captcha code
// ======================================================
$site_key = "6LcJcxEUAAAAAJdhE3XEPp123aqziR4ldGuYtjvE";//live
//$site_key = "6LcvchEUAAAAAPiqqcY_EPwHGh81iUxYrevQwsId";//local

# the response from reCAPTCHA
//$resp = null;
# the error code from reCAPTCHA, if any
//$error = null;
require("includes/autoload.php");
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
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
$Settings_arr['imageverification_req_newsletter'] = 0; 
 
 function getpacksize_puregusto($prodid)
 {
	 global $db,$ecom_siteid;
	 $sql = "SELECT label_value 
				FROM 
					product_labels 
				WHERE
					products_product_id = $prodid 
					AND product_site_labels_label_id = 177  
					AND label_value !='' 
				LIMIT 
					1";
	$ret = $db->query($sql);
	if($db->num_rows($ret))
	{
		$row = $db->fetch_array($ret);
		if (trim($row['label_value'])!='')
		{
		?>
		<div class='packsize_listing'><strong>Pack Size:</strong> <?php echo trim($row['label_value'])?></div>
		<?php	
		}
	}
 }
 
 
 	// Function to show the add to cart link with ajax and noramal
	function show_addtocart_v5_ajax_local($frm,$prod_arr,$class_arr,$istable=false,$class_tdarr=array(),$isbutton=false,$return = false,$prefix='',$suffix='',$override_hideqty=0)
	{ 
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		$addto_cart_withajax    = $Settings_arr['enable_ajax_in_site'];//checking for the ajax function for adding to cart is enabled or not

		//to sheck whether quantity box should be shown or not
		$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		
		// Check whether there exists any sub products mapped for the current product
		$subprod_exists = false;
		$sql_mapsub = "SELECT map_id FROM products_subproductsmap a, products b
							WHERE 
								a.products_product_id = ".$prod_arr['product_id']." 
								AND a.products_subproduct_id = b.product_id 
								ANd b.product_subproduct=1 
								AND b.product_hide='N' 
							LIMIT
								1";
		$ret_map_sub = $db->query($sql_mapsub);
		if($db->num_rows($ret_map_sub))
		{
			$subprod_exists = true;
		}						
		
        if($istable == true)
		{
		$class_qty = $class_tdarr['QTY'];
		$class_txt = $class_tdarr['TXT'];
		$class_btn = $class_tdarr['BTN'];	
		$quantity_div_class     = ($class_arr['QTY_TD']!='')?$class_arr['QTY_TD']:'quantity';  
        $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';		
		}  
		else
		{			
		        $quantity_div_class     = ($class_arr['QTY_DIV']!='')?$class_arr['QTY_DIV']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';     
		}	
		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty && $override_hideqty!=1)
		{
			if($istable == true)
			{
				$quantity_box  = '<td align="right" valign="middle" class="'.$class_qty.'">';
				if($prefix!='')
				{
					  $quantity_box  .=  $prefix;
				}
				$quantity_box  .= $Captions_arr['COMMON']['COMMON_QTY'].'</td>
								 <td align="left" valign="middle" class="'.$class_txt.'"> <input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></td>';
		    }
		    else
		    {
			  	$quantity_box  = '<div class="'.$quantity_div_class.'">'.$Captions_arr['COMMON']['COMMON_QTY'].'<input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></div>';

			}					 
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
				return;
		}
		$show_buy_now = false;
		$variable_check_forajax = false;//to check whether there is a variable exists for the product
		$var_exists 			= false; 
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{
			$var_exists = true;
			$variable_check_forajax = true;
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{				
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					    $quantity_box_display = true;					
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						    $link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{	
			if($prod_arr['product_variables_exists']=='Y')
			{
				$var_exists = true;
				$variable_check_forajax = true;	//this is for checking for variable exists for ajax enabled cart adding
			}
			else
			{
				$var_exists = false;	
				$variable_check_forajax = false;				
			}	
			
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{				
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$curtype	= 'Prod_Preorder';						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		
		if ($return==true)
		{
			return $mod;
		}	
		elseif ($link)
		{	
		
		if($addto_cart_withajax==1)
		{ 
			$link ="";
			if($ecom_siteid!=70)
			{
		   ?>
		   	<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		   	<?php
		   	}?>
			<input type='hidden' name='fproduct_id' value="<?=$prod_arr['product_id']?>"/>
		   	<input type='hidden' name='product_id' value="<?=$prod_arr['product_id']?>"/>
			
			<?php 
			if($ecom_siteid!=70)
			{
			?>
			<input type="hidden" id="product_id_ajax" name="product_id" value="<?=$prod_arr['product_id']?>" />
			<input type='hidden' name='ajaxform_name' <?php /*id="ajaxform_name"*/?> value="<?=$frm?>"/>
			<?php
			}
			?>

		   <?php
		    if ($variable_check_forajax==true){					 
			//$link  ="ajax_addto_cart_fromlist('show_prod_det_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			$link = "javascript:window.location='".url_product($prod_arr['product_id'],$prod_arr['product_name'],1)."'";
			}
			else
			{		
			//$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
			$link  ="ajax_addto_cart_fromlist('add_prod_tocart_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			}
	    }
	    else
	    {
			if ($var_exists==true){					 
				$link = "javascript:submit_to_det_form('".$frm."')";
			}
			else
			{		
				$link = "javascript:submit_form('".$frm."','".$curtype."','".$prod_arr['product_id']."')";
			}
		   
		}
		
		if($subprod_exists == true)
		{
				$link = "javascript:window.location='".url_product($prod_arr['product_id'],$prod_arr['product_name'],1)."'";
		}
		
		$outer_cont = "";
		if($istable=='true')
		{
			$outer_cont        = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
						          <tr>';
			$btn_box  		   = '<td align="left" valign="middle" class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</td>';
			$outer_cont_bottom = '</tr></table>';
		}
		else
		{
		    $outer_cont        = '';
		    if($class_arr['BTN_CLS']!='')
		    {
				$class_btn = $class_arr['BTN_CLS'];
		    
			$btn_box  		   = '<div class="'.$class_btn.'">';
			
			$btn_box_bottom    = '</div>';
		     }
			else
			{
				$btn_box ='';
			    $btn_box_bottom    = '';
			}
			$outer_cont_bottom = '';
		}
		$show_but ='';
		if($isbutton == true)
		{
			$show_but =  '<input value="'.$caption.'" name="'.$caption.'" type="button" onclick="'.$link.'" class="'.$class.'"/>';			
		}
		else
		{
			 $check_arr = is_grid_display_enabled_prod($prod_arr['product_id']);
				if($check_arr['enabled']==false)
				{
					
					$show_but =  '<a href="javascript:void(0);" onclick="'.$link.'" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 
				}
				else
				{
					$show_but =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" onclick="" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 

				}	
		}	
			$show_but .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		    echo $outer_cont;
			  if($quantity_box_display)
			   echo $quantity_box;
			   echo $btn_box;
			   echo $show_but;
			   echo $btn_box_bottom;
			   echo $outer_cont_bottom;
					
			
		    if($suffix!='')
			echo $suffix;
		}
	}
 
 
 if($_REQUEST['modal_control']==111) // case of sending email for Request information from product details page
 {
	$error_val ="";
	$urgent = ($_REQUEST["modal_urgent"]==1)?'Yes':'No';
	$message = "Details Submitted are the following \n\n";
	$message .= "Full Name : " .  $_REQUEST["modal_fullname"] . "\nBusiness Name : " . $_REQUEST["modal_businessname"] . " \nContact Number : " . $_REQUEST["modal_contact"] . "\nEmail Address  : " . $_REQUEST["modal_email"]. "\nOther Information  : " . $_REQUEST["modal_other"];
	$message .= "\nEnquiry is Urgent : " . $urgent;
	$message .= "\nRequested From : ".$ecom_selfhttp."$ecom_hostname" . $_REQUEST["modal_return_url"];
		$modal_return_url = $_REQUEST["modal_return_url"];
		$headers = "From: " . $_REQUEST["modal_fullname"] . " <" .$_REQUEST["modal_email"] . ">";
		 //$address = "sales@puregusto.co.uk,info@puregusto.co.uk";
	$address[] = "sales@puregusto.co.uk";

		 //$address = "latheeshgeorge@gmail.com";
		
		 /* $resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

		  if (!$resp->is_valid) {		
				 $error_val = "CAPTCHA wasn't entered correctly!!!";
		  } else {
				$error_val ="";
		  }
		  */
		    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
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
		echo "<form name=\"modal_formmain_reload\" id=\"modal_formmain\" method=\"post\" action=\"\">
				<input name=\"modal_return_url\" value=\"$modal_return_url\" type=\"hidden\">	
				<input name=\"modal_control\" id=\"modal_control\" value=\"222\" type=\"hidden\">
				<input name=\"modal_fullname\" value=\"".$_REQUEST["modal_fullname"]."\" type=\"hidden\">	
				<input name=\"modal_businessname\" id=\"modal_businessname\" value=\"".$_REQUEST["modal_businessname"]."\" type=\"hidden\">
				<input name=\"modal_urgent\" id=\"modal_urgent\" value=\"".$urgent."\" type=\"hidden\">
				<input name=\"modal_fullname\" id=\"modal_fullname\" value=\"".$_REQUEST["modal_fullname"]."\" type=\"hidden\">
				<input name=\"modal_contact\" id=\"modal_contact\" value=\"".$_REQUEST["modal_contact"]."\" type=\"hidden\">
				<input name=\"modal_other\" id=\"modal_other\" value=\"".$_REQUEST["modal_other"]."\" type=\"hidden\">
				<input name=\"modal_email\" id=\"modal_email\" value=\"".$_REQUEST["modal_email"]."\" type=\"hidden\">
				<input name=\"error_alert\" id=\"error_alert\" value=\"".$error_val."\" type=\"hidden\">

		
		</form>";
		echo "
				<script type='text/javascript'>
				alert(' Robot verification failed !!!');
				location.href='$modal_return_url';
				document.modal_formmain_reload.submit();
				</script>
			";
		}
		else
		{
			//mail($address, "Request Information - $ecom_hostname", $message, $headers);
		    $ret_err =mail_Phpmaler_admin_new($address,"Request Information - $ecom_hostname",nl2br($message),"info@puregusto.co.uk",$ecom_hostname,$email_adminheaders);

			echo "
				<script type='text/javascript'>
				alert('Details Sent Successfully');
				window.location = '$modal_return_url';
				</script>
			";
		}	
			 
	exit; 
 }
 
 
 if($_REQUEST['modal_control']==444) // case of sending email for Request information from product details page
 {
	
	switch($_REQUEST['modalq_period'])
	{
		case 1:
			$modalq_period = '24 Months (2 years)';
		break;
		case 2:
			$modalq_period = '36 Months (3 years)';
		break;
		case 3:
			$modalq_period = '48 Months (4 years)';
		break;
		case 4:
			$modalq_period = '60 Months (5 years)';
		break;
	};
	
	switch($_REQUEST['modalq_businesstype'])
	{
		case 'ST':
			$modal_btype = 'Sole Trader';
		break;
		case 'PS':
			$modal_btype = 'Partnership';
		break;
		default:
			$modal_btype = $_REQUEST['modalq_businesstype'];
		break;
	};
	 
	$error_val ="";
	$message = "Lease Quote Details \n\n";
	$message .= 
	"Product : " .  $_REQUEST["modalq_product"] . 
	"\nLease Period : " . $modalq_period . 
	"\nContact Name : " . $_REQUEST["modalq_contactname"] . 
	"\nEmail  : " . $_REQUEST["modalq_email"]. 
	"\nPhone  : " . $_REQUEST["modalq_phone"].
	"\nBusiness Name  : " . $_REQUEST["modalq_businessname"].
	"\nBusiness Type  : " . $modal_btype.
	"\nTrading Time (In years)  : " . $_REQUEST["modalq_tradingtime"].
	"\nAddress  : " . $_REQUEST["modalq_address1"].' '.$_REQUEST["modalq_address2"].
	"\nTown : " . $_REQUEST["modalq_town"].
	"\nPost Code: " . $_REQUEST["modalq_postcode"].
	"\nComments: " . $_REQUEST["modalq_comments"];
		
	$message .= "\nRequested From : ".$ecom_selfhttp."$ecom_hostname" . $_REQUEST["modal_return_url"];
	
		$modal_return_url = $_REQUEST["modal_return_url"];
		$headers = "From: " . $_REQUEST["modalq_contactname"] . " <" .$_REQUEST["modalq_email"] . ">";
		          $address[] = "sales@puregusto.co.uk";

		         //$address = "sales@puregusto.co.uk";
				// $address = "latheeshgeorge@gmail.com";

		/*  $resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

		  if (!$resp->is_valid) {		
				 $error_val = "CAPTCHA wasn't entered correctly!!!";
		  } else {
				$error_val ="";
		  }*/

		    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
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
		echo "<form name=\"modal_formlease_reload\" id=\"modal_formlease_reload\" method=\"post\" action=\"\">
				<input name=\"modal_return_url\" value=\"$modal_return_url\" type=\"hidden\">	
				<input name=\"modal_control\" id=\"modal_control\" value=\"666\" type=\"hidden\">
				<input name=\"modalq_product\" value=\"".$_REQUEST["modalq_product"]."\" type=\"hidden\">	
				<input name=\"modalq_period\" id=\"modalq_period\" value=\"".$_REQUEST["modalq_period"]."\" type=\"hidden\">
				<input name=\"modalq_contactname\" id=\"modalq_contactname\" value=\"".$_REQUEST["modalq_contactname"]."\" type=\"hidden\">
				<input name=\"modalq_email\" id=\"modalq_email\" value=\"".$_REQUEST["modalq_email"]."\" type=\"hidden\">
				<input name=\"modalq_phone\" id=\"modalq_phone\" value=\"".$_REQUEST["modalq_phone"]."\" type=\"hidden\">
				<input name=\"modalq_businessname\" id=\"modalq_businessname\" value=\"".$_REQUEST["modalq_businessname"]."\" type=\"hidden\">
				<input name=\"modalq_businesstype\" id=\"modalq_businesstype\" value=\"".$_REQUEST["modalq_businesstype"]."\" type=\"hidden\">
				
				<input name=\"modalq_tradingtime\" id=\"modalq_tradingtime\" value=\"".$_REQUEST["modalq_tradingtime"]."\" type=\"hidden\">
				<input name=\"modalq_address1\" id=\"modalq_address1\" value=\"".$_REQUEST["modalq_address1"]."\" type=\"hidden\">
				<input name=\"modalq_address2\" id=\"modalq_address2\" value=\"".$_REQUEST["modalq_address2"]."\" type=\"hidden\">
				<input name=\"modalq_town\" id=\"modalq_town\" value=\"".$_REQUEST["modalq_town"]."\" type=\"hidden\">
				<input name=\"modalq_postcode\" id=\"modalq_postcode\" value=\"".$_REQUEST["modalq_postcode"]."\" type=\"hidden\">
				<input name=\"modalq_comments\" id=\"modalq_comments\" value=\"".$_REQUEST["modalq_comments"]."\" type=\"hidden\">
				
				<input name=\"error_alert\" id=\"error_alert\" value=\"".$error_val."\" type=\"hidden\">

		
		</form>";
		echo "
				<script type='text/javascript'>
				alert('Robot verification failed, please try again.');
				location.href='$modal_return_url';
				document.modal_formlease_reload.submit();
				</script>
			";
		}
		else
		{
			//mail($address, "Request Lease Quote - $ecom_hostname", $message, $headers);
		    $ret_err =mail_Phpmaler_admin_new($address,"Request Lease Quote - $ecom_hostname",nl2br($message),"info@puregusto.co.uk",$ecom_hostname,$email_headers);

			echo "
				<script type='text/javascript'>
				alert('Quote Request Sent Successfully');
				window.location = '$modal_return_url';
				</script>
			";
		}	
			 
	exit; 
 }
 
 
 
// Section to send the Contact Us details for garraways site
if ($_REQUEST['ContactUs_Submitted']==1)
{
	$show_error = "";

	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
	$responseData = json_decode($verifyResponse);

    if($responseData->success) {
		$show_error ="";
	}
	else
	{
	 $show_error = "Robot verification failed, please try again.";	
	}
	

	if($show_error=="")
	{
		$cont_name 		= trim($_REQUEST['firstname']).' '.trim($_REQUEST['lastname']);
		$cont_email 	= trim($_REQUEST['emailaddress']);
		$cont_phone 	= trim($_REQUEST['phonenumber']);
		$cont_company 	= trim($_REQUEST['companyname']);
		$cont_message 	= trim($_REQUEST['message_contact']);
		
		$message = "Name : " .  $cont_name. "\nEmail Id : " . $cont_email . " \nPhone : " . $cont_phone;
		if($cont_company)
		{
			$message = $message."\nCompany : ".$cont_company;
		}
		$message = $message."\nMessage :\n ".$cont_message;
		
		
		
		$headers = "From: " . $_REQUEST["firstname"] . " <" .$_REQUEST["emailaddress"] . ">";
		$address[] = 'sales@puregusto.co.uk';//"contactus@Purogusto.co.uk";
		// $address[] = 'latheeshgeorge@gmail.com';//"contactus@Purogusto.co.uk";
		
		//$address = 'latheeshgeorge@gmail.com';
		//$address = "sony.joy@thewebclinic.co.uk";
		$ret_err =mail_Phpmaler_admin_new($address,"Contact Us Form",nl2br($message),"info@puregusto.co.uk",$ecom_hostname,$email_headers);
	echo "
				<script type='text/javascript'>
				alert('Details Sent Successfully');
				</script>
			";
	}
	else
	{
		echo "
				<script type='text/javascript'>
				alert('Robot verification failed, please try again.');
				</script>
			";
	}
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
//echo $_REQUEST['req'];
if($_REQUEST['req']=='')
{
	include($ecom_themename.'/home.php');
}
else if($_REQUEST['req']=='prod_detail')
{
	include($ecom_themename.'/proddetails.php');	
}
else	
{
    include($ecom_themename.'/default.php');
}
function display_cart_discount_puregusto($products_arr,$Captions_arr,$ret=false)
{

	global $ecom_selfhttp;
	$ret_arr = array();
	if ($products_arr["prom_prodcode_disc"] and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
	{
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}
	}
	elseif ($products_arr["cust_disc_type"] !='' and $products_arr["savings"]["bulk"]) // Case if promotional code disc is there for current product
	{	
		if($ret==false)
			echo print_price($products_arr["savings"]["bulk"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["bulk"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["bulk"];
		}	
	}	
	elseif ($products_arr["cust_disc_type"] !='' and $products_arr["savings"]["product"]) // Case if promotional code disc is there for current product
	{	
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}	
	}
	elseif($products_arr['savings']['product_combo'] or $products_arr['userin_combo']) // Check whether combo discount is there
	{
		if($ret==false)
			echo print_price($products_arr["savings"]["product_combo"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product_combo"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product_combo"];
		}	
	}
	elseif($products_arr["savings"]["bulk"]) // Check whether bulk discount is there
	{
		//$products_arr["savings"]["bulk"] +=$products_arr["savings"]["product"];// adding the normal discount value as well, if there any exists
		if($ret==false)
			echo print_price($products_arr["savings"]["bulk"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["bulk"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["bulk"];
		}	
	}
	else
	{
		//echo print_price($products_arr["savings"]["product"],true);
		if($ret==false)
			echo print_price($products_arr["savings"]["product"],true);
		else
		{
			$ret_arr['val'] = print_price($products_arr["savings"]["product"],true);
			$ret_arr['valonly'] = $products_arr["savings"]["product"];
		}
	}	
	if($products_arr['discount_type']!='')
	{
		$disc_caption = '';
		$disc_arr = explode(',',$products_arr['discount_type']);
		$disp_cap = array();
		
		foreach ($disc_arr as $k=>$v)
		{
			switch ($v)
			{
				case 'PROM':
					$disp_cap[] = $Captions_arr['CART']['CART_PROM_DISC'];
				break;
				case 'CUST_DIR':
					$disp_cap[] =  $Captions_arr['CART']['CART_CUST_DIR_DISC'];
				break;
				case 'CUST_GROUP':
					$disp_cap[] =  $Captions_arr['CART']['CART_GROUP_DISC'];
				break;
				case 'COMBO':
					$disp_cap[] =  $Captions_arr['CART']['CART_COMBO_DISC'];
				break;
				case 'BULK':
                    $disp_cap[] =  $Captions_arr['CART']['CART_BULK_DISC'];
				break;	
				case 'PRICE_PROMISE_DISC':
					$disp_cap[] =  $Captions_arr['CART']['PRICE_PROMISE_DISC'];
				break;
				default:
                    if(trim($Captions_arr['CART']['CART_PROD_DIR_DISC'])!='' and $products_arr["savings"]["product"]>0)
					{
				    	$disp_cap[] =  $Captions_arr['CART']['CART_PROD_DIR_DISC'];
					}	
				break;	
			};
		}
		if(count($disp_cap))
		{
			$disc_caption = '<br/>'.implode('<br/>',$disp_cap);
		}
		if($ret==false)
			echo $disc_caption;
		else
		{
			$ret_arr['caption'] = $disc_caption;
		}	
		if($ret==true)
		{
			return $ret_arr;
		}
	}
} 

/* Function to show the bulk discount*/
function show_BulkDiscounts($row_prod,$var_arr=array())
{
	global $db,$ecom_siteid,$Captions_arr;
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
	{
		$sql_var = "SELECT var_id,var_name  
						FROM 
							product_variables 
						WHERE 
							products_product_id = ".$row_prod['product_id']." 
							AND var_hide= 0 
							AND var_value_exists = 1 
						ORDER BY 
							var_order";
		$ret_var = $db->query($sql_var);
		if($db->num_rows($ret_var))
		{
			while ($row_var = $db->fetch_array($ret_var))
			{
				$curvar_id= $row_var['var_id'];
				// Get the value id of first value for this variable
				$sql_data = "SELECT var_value_id 
										FROM 
											product_variable_data 
										WHERE 
											product_variables_var_id = ".$curvar_id." 
										ORDER BY var_order  
										LIMIT 
											1";
				$ret_data = $db->query($sql_data);
				if ($db->num_rows($ret_data))
				{
					$row_data = $db->fetch_array($ret_data);
				}							
				$var_arr[$curvar_id] = $row_data['var_value_id'];
			}
		}
	}
	// Section to show the bulk discount details
	$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
	$bulkdisc_details = product_BulkDiscount_Details_Puregusto($row_prod['product_id'],$comb_arr['combid'],$var_arr);
	?>
	<div class="product-table">

	
	<?php
	if (count($bulkdisc_details['qty']))
	{
	?>	                    
			<table class="qty-discount-table">
			<thead>
				<tr><th>Qty</th>
		<?php
			for($i=0;$i<count($bulkdisc_details['qty']);$i++)
			{
				echo '<th>'.$bulkdisc_details['qty'][$i].'+</th>';
				//echo '<span>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
				//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				//echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				//echo '</span>';
			}
		?>
			</tr>
			</thead>
			
			<tbody>
				<tr><td>Price</td>
			<?php
			for($i=0;$i<count($bulkdisc_details['qty']);$i++)
			{
				//echo '<th>'.$bulkdisc_details['qty'][$i].'+</th>';
				//echo '<span>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
				//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				//echo '<td>'.product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']).'</td>';
				//echo '<td>'.print_price($bulkdisc_details['price'][$i]).'</td>';
				echo '<td>'.product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).'</td>';

				//echo '</span>';
			}
		?>
			  </tr>
			</tbody>
			</table>
	<?php
	}
	?>
				</div>

	<?php
}

function replace_unwanted_quotes($str) {
	$search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 

    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-');
    return str_replace($search, $replace, $str);
}
?>

