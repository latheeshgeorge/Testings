<?php
/*
	#################################################################
	# Script Name 	: payment_types.php
	# Description 	: Action Page for listing and enabling/disabling the payment type for the site
	# Coded by 		: ANU
	# Created on	: 27-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/payment_types/list_payment_types.php');
}
elseif($_REQUEST['fpurpose'] == 'select_paymenttype') { // to enable or disable a payment type for the site by the site admin(from console).
	$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	/*$sql_update_default = "UPDATE  general_settings_site_currency SET curr_default = 0 WHERE sites_site_id= $ecom_siteid";
	$db->query($sql_update_default); 
	$sql_newDefault = "UPDATE  general_settings_site_currency SET curr_default = 1 WHERE sites_site_id= $ecom_siteid AND currency_id= ".$_REQUEST['curr_default']."";
	$db->query($sql_newDefault);*/
	$del_arr = array();
	$del_arr = explode("~",$_REQUEST['pymt_ids']);
	$sql_enableAll = "UPDATE payment_types_forsites SET paytype_forsites_userdisabled = 0 WHERE sites_site_id = $ecom_siteid";
	$db->query($sql_enableAll);
	if($del_arr[0]){
		for($i=0;$i<count($del_arr);$i++){
		$sql_selectPayment = "UPDATE  payment_types_forsites SET paytype_forsites_userdisabled = 1 WHERE sites_site_id = $ecom_siteid AND paytype_forsites_id =".$del_arr[$i];
		$db->query($sql_selectPayment);
			}
	}	
	$payid_arr 	= explode('~',$_REQUEST['payids']);
	$paycap_arr	= explode('~',$_REQUEST['paycaptions']);
	for($i=0;$i<count($payid_arr);$i++)
	{
		// case if caption not set for a payment type. so get the name from main paytype table
		$cap =  add_slash($paycap_arr[$i]);
		if ($cap=='')
		{
			$sql_caps = "SELECT paytype_name 
							FROM 
								payment_types 
							WHERE 
								paytype_id=".$payid_arr[$i]." 
						LIMIT 
							1";
			$ret_caps = $db->query($sql_caps);
			if ($db->num_rows($ret_caps))
			{
				$row_capts  = $db->fetch_array($ret_caps);
				$cap 		= addslashes(stripslashes($row_capts['paytype_name']));
			}				
		}
		$sql_update = "UPDATE payment_types_forsites 
						SET 
							paytype_caption ='".$cap."' 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND paytype_id=".$payid_arr[$i]." 
						LIMIT 
							1";
		$db->query($sql_update);					
	}
	// Check whether paytype credit card is active for current website
	$sql_check = "SELECT a.paytype_id 
					FROM 
						payment_types a, payment_types_forsites b 
					WHERE 
						a.paytype_id=b.paytype_id 
						AND b.sites_site_id = $ecom_siteid 
						AND b.paytype_forsites_active = 1
						AND a.paytype_code = 'credit_card'  
						AND b.paytype_forsites_userdisabled = 0 
					LIMIT 
						1";
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check)) // case if payment type credit_card is active for current site
	{
		// Check whether atleast one payment method is active for current site. If not set the first one as active.
		$sql_check = "SELECT payment_methods_forsites_id 
						FROM 
							payment_methods_forsites 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND payment_method_sites_active = 1 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0)
		{
			$alert_extend = '<br> Please select atleast one payment method for your website by clicking the Credit Card option';
		}					
	}
	else // case if payment type credit_card is not active for current site
	{
		// Deactivate all payment methods set for current site
		$sql_update = "UPDATE payment_methods_forsites 
							SET 
								payment_method_sites_active = 0 
							WHERE 
								sites_site_id = $ecom_siteid";
		$db->query($sql_update);						
	}					
					
	$alert = '';
	create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
	$alert = 'Payment Type Details Changed Successfully'.$alert_extend; 
	include("../includes/payment_types/list_payment_types.php");
}
elseif($_REQUEST['fpurpose'] == 'view_methods') { // to view the payment methods set for this site
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/payment_types/list_payment_methods.php');
}
elseif($_REQUEST['fpurpose'] == 'select_paymentmethod') { // to enable or disable a payment type for the site by the site admin(from console).
	$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$del_arr = array();
	$del_arr = explode("~",$_REQUEST['pymt_ids']);
	$sql_enableAll = "UPDATE payment_methods_forsites SET payment_method_sites_active = 0 WHERE sites_site_id = $ecom_siteid";
	$db->query($sql_enableAll);
	if($del_arr[0]){
		for($i=0;$i<count($del_arr);$i++){
		$sql_selectPayment = "UPDATE payment_methods_forsites SET payment_method_sites_active = 1 WHERE sites_site_id = $ecom_siteid AND payment_methods_paymethod_id =".$del_arr[$i];
		$db->query($sql_selectPayment);
			}
	}	
	$payid_arr 	= explode('~',$_REQUEST['payids']);
	$paycap_arr	= explode('~',$_REQUEST['paycaptions']);
	for($i=0;$i<count($payid_arr);$i++)
	{
		// case if caption not set for a payment type. so get the name from main paytype table
		$cap =  add_slash($paycap_arr[$i]);
		if ($cap=='')
		{
			$sql_caps = "SELECT paymethod_name  
							FROM 
								payment_methods  
							WHERE 
								paymethod_id=".$payid_arr[$i]." 
						LIMIT 
							1";
			$ret_caps = $db->query($sql_caps);
			if ($db->num_rows($ret_caps))
			{
				$row_capts  = $db->fetch_array($ret_caps);
				$cap 		= addslashes(stripslashes($row_capts['paymethod_name']));
			}				
		}
		$sql_update = "UPDATE payment_methods_forsites  
						SET 
							payment_method_sites_caption ='".$cap."' 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND payment_methods_paymethod_id=".$payid_arr[$i]." 
						LIMIT 
							1";
		$db->query($sql_update);					
	}
	if(trim($_REQUEST['prevreq_payids'])!='')
	{
		$prevreq_arr = explode('~',$_REQUEST['prevreq_payids']);
		$sql_update = "UPDATE 
							payment_methods_forsites 
						SET 
							payment_method_preview_req = 0
						WHERE 
							sites_site_id =$ecom_siteid";
		$db->query($sql_update);
		for($i=0;$i<count($prevreq_arr);$i++)
		{
			$sql_update = "UPDATE 
							payment_methods_forsites 
						SET 
							payment_method_preview_req = 1
						WHERE 
							sites_site_id =$ecom_siteid 
							AND payment_methods_paymethod_id=".$prevreq_arr[$i]." 
						LIMIT 
							1";
			$db->query($sql_update);				
		}
	}
	else
	{
		$sql_update = "UPDATE 
							payment_methods_forsites 
						SET 
							payment_method_preview_req = 0
						WHERE 
							sites_site_id =$ecom_siteid";
		$db->query($sql_update);
	}	
	if($_REQUEST['card_modify'])
	{
		$sql_enabled_cc = "SELECT payment_methods_supported_cards_cardtype_id FROM payment_methods_sites_supported_cards WHERE sites_site_id=".$ecom_siteid."";
		$ret_enabled_cc = $db->query($sql_enabled_cc);
		$arr_enabled_card_ids = array();
		while($enabled_cc = $db->fetch_array($ret_enabled_cc)){
		$arr_enabled_card_ids[] = $enabled_cc['payment_methods_supported_cards_cardtype_id'];
		//echo $enabled_cc['cardtype_id'];
		}
		if($_REQUEST['card_ids']){
			$card_arr = array();
			$card_arr = explode("~",$_REQUEST['card_ids']);
			if($_REQUEST['sort_str']){
			$sort_arr = explode("~",$_REQUEST['sort_str']);
			}
			foreach($arr_enabled_card_ids as $key_existing => $val_existing) // Deleting the card type which are no longer supported by site
			{
				if(!in_array($val_existing,$card_arr)){
				$sql_delete_unselected = "DELETE FROM payment_methods_sites_supported_cards WHERE payment_methods_supported_cards_cardtype_id = ".$val_existing." AND sites_site_id= ".$ecom_siteid."";
				$db->query($sql_delete_unselected);
				}
			}
			$i=0;
			foreach($card_arr as $key => $val){
				if($sort_arr[$i]!='')
				{
					$sort = $sort_arr[$i];
				}
				else
					$sort = 0;
				$i++;
				if(is_array($arr_enabled_card_ids))
				{
					if (!is_numeric($sort))
						$sort = 0;
					if(!in_array($val,$arr_enabled_card_ids))// if current card not already exist with the current site
					{
						$insert_array = array();
						$insert_array['sites_site_id'] 									= $ecom_siteid;
						$insert_array['payment_methods_supported_cards_cardtype_id'] 	= $val;
						$insert_array['supportcard_order'] 								= $sort;
						$db->insert_from_array($insert_array, 'payment_methods_sites_supported_cards');
					}
					else // if card type already exists with the site. so update only the sort order
					{
						$update_array							= array();
						$update_array['supportcard_order']		= $sort;
						$db->update_from_array($update_array,'payment_methods_sites_supported_cards',array('payment_methods_supported_cards_cardtype_id'=>$val,'sites_site_id'=>$ecom_siteid));
					}
				}
			}
		}
		else
		{
			$sql_delete_unselected = "DELETE FROM payment_methods_sites_supported_cards WHERE sites_site_id= ".$ecom_siteid."";
			$db->query($sql_delete_unselected);
		}
	}	
	// Check whether there exists atleast one payment method for this website.
	$sql_check = "SELECT payment_methods_forsites_id 
					FROM 
						payment_methods_forsites 
					WHERE 
						sites_site_id = $ecom_siteid 
						AND payment_method_sites_active=1 
					LIMIT 
						1";
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check)==0)
	{
		$alert_extend = "<br>Please select atleast one payment method for your website";
	}
	$alert = '';
	create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
	$alert = 'Payment Methods Details Saved Successfully'.$alert_extend; 
	include("../includes/payment_types/list_payment_methods.php");
}
elseif($_REQUEST['fpurpose'] == 'view_cards') { // to view the credit cards accpeted by this site).
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/payment_types/list_payment_cards.php');
}
elseif($_REQUEST['fpurpose'] == 'select_creditcards'){
	//$ajax_return_function = 'ajax_return_contents';
	//include "../ajax/ajax.php";
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$sql_enabled_cc = "SELECT payment_methods_supported_cards_cardtype_id FROM payment_methods_sites_supported_cards WHERE sites_site_id=".$ecom_siteid."";
	$ret_enabled_cc = $db->query($sql_enabled_cc);
	$arr_enabled_card_ids = array();
	while($enabled_cc = $db->fetch_array($ret_enabled_cc)){
	$arr_enabled_card_ids[] = $enabled_cc['payment_methods_supported_cards_cardtype_id'];
	//echo $enabled_cc['cardtype_id'];
	}
	if($_REQUEST['card_ids']){
		$card_arr = array();
		$card_arr = explode("~",$_REQUEST['card_ids']);
		if($_REQUEST['sort_str']){
		$sort_arr = explode("~",$_REQUEST['sort_str']);
		}
		foreach($arr_enabled_card_ids as $key_existing => $val_existing) // Deleting the card type which are no longer supported by site
		{
	 		if(!in_array($val_existing,$card_arr)){
		 	$sql_delete_unselected = "DELETE FROM payment_methods_sites_supported_cards WHERE payment_methods_supported_cards_cardtype_id = ".$val_existing." AND sites_site_id= ".$ecom_siteid."";
			$db->query($sql_delete_unselected);
			}
		}
		$i=0;
		foreach($card_arr as $key => $val){
			if($sort_arr[$i]!='')
			{
				$sort = $sort_arr[$i];
			}
			else
				$sort = 0;
			$i++;
			if(is_array($arr_enabled_card_ids))
			{
				if(!in_array($val,$arr_enabled_card_ids))// if current card not already exist with the current site
				{
					
					$insert_array = array();
					$insert_array['sites_site_id'] 									= $ecom_siteid;
					$insert_array['payment_methods_supported_cards_cardtype_id'] 	= $val;
					$insert_array['supportcard_order'] 								= $sort;
					$db->insert_from_array($insert_array, 'payment_methods_sites_supported_cards');
				}
				else // if card type already exists with the site. so update only the sort order
				{
					$update_array							= array();
					$update_array['supportcard_order']		= $sort;
					$db->update_from_array($update_array,'payment_methods_sites_supported_cards',array('payment_methods_supported_cards_cardtype_id'=>$val,'sites_site_id'=>$ecom_siteid));
				}
			}
		}
	}
	else
	{
		$sql_delete_unselected = "DELETE FROM payment_methods_sites_supported_cards WHERE sites_site_id= ".$ecom_siteid."";
		$db->query($sql_delete_unselected);
		
	}
	$alert = "Successfully saved";
		include ('../includes/payment_types/list_payment_cards.php');
}
elseif ($_REQUEST['fpurpose']=='add_paytype_img')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	include("includes/image_gallery/list_images.php");
}
elseif($_REQUEST['fpurpose']=='rem_paytype_img') //Un assign products from combo
{  
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$paytypes_id		= $_REQUEST['src_id'];
	if($paytypes_id){
	$sql_del = "UPDATE  payment_types_forsites SET images_image_id=0  WHERE paytype_forsites_id=$paytypes_id";
	$db->query($sql_del);
	}
	$alert = 'Image unassigned successfully.';
	include("includes/payment_types/list_payment_types.php");
}
elseif ($_REQUEST['fpurpose']=='view_paytype_entry')
{
	include("includes/payment_types/list_payment_method_details.php");
}
elseif ($_REQUEST['fpurpose']=='payment_details_save')
{
	$sql_sites = "SELECT payment_methods_forsites_id 
					FROM 
						payment_methods_forsites 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND payment_methods_paymethod_id=".$_REQUEST['paymethod_id']." 
					LIMIT 
						1";
	$ret_sites = $db->query($sql_sites);
	if ($db->num_rows($ret_sites))
	{
		$row_sites = $db->fetch_array($ret_sites);
		$method_site_id = $row_sites['payment_methods_forsites_id'];
	}					
	foreach ($_REQUEST as $k=>$v)
	{
		if (substr($k,0,4)=='det_')
		{
			$id_arr = explode('_',$k);
			$curid = $id_arr[1];
			// Check whether there already exists an entry for current details for current site
			$sql_check = "SELECT paydet_id 
							FROM 
								payment_methods_forsites_details  
							WHERE 
								payment_methods_details_payment_method_details_id = $curid 
								AND sites_site_id = $ecom_siteid 
								AND payment_methods_forsites_id=$method_site_id 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
				$row_check = $db->fetch_array($ret_check);
				// Updating the value
				$update_sql = "UPDATE payment_methods_forsites_details 
								SET 
									payment_methods_forsites_details_values = '".add_slash($v)."' 
								WHERE 
									paydet_id=".$row_check['paydet_id']." 
								LIMIT 
									1";
				$db->query($update_sql);					
			}					
			else
			{
				$insert_array														= array();
				$insert_array['payment_methods_details_payment_method_details_id']	= $curid;
			 	$insert_array['sites_site_id']										= $ecom_siteid;
				$insert_array['payment_methods_forsites_id']						= $method_site_id;
				$insert_array['payment_methods_forsites_details_values']			= add_slash($v);
				$db->insert_from_array($insert_array,'payment_methods_forsites_details');
			}
		}
		if($_REQUEST['google_type']==1)
		{
			$recomm = $_REQUEST['payment_method_google_recommended'];
			$sql_update = "UPDATE payment_methods_forsites  
							SET 
								payment_method_google_recommended = $recomm 
							WHERE 
								payment_methods_forsites_id = $method_site_id 
								AND sites_site_id =$ecom_siteid 
							LIMIT 
								1";
			$db->query($sql_update);					
		}
	}
	create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
	$alert = "Details Saved Successfully";
	include("includes/payment_types/list_payment_method_details.php");
}
?>