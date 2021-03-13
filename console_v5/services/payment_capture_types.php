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
	include ('includes/payment_capture_types/list_payment_capture_types.php');
}elseif($_REQUEST['fpurpose'] == 'select_paymenttype') { // to enable or disable a payment type for the site by the site admin(from console).
$ajax_return_function = 'ajax_return_contents';
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$del_arr = array();
	$del_arr = explode("~",$_REQUEST['pymt_ids']);
	$sqlcheckp = "SELECT count(*) as cnt FROM general_settings_site_paymentcapture_type WHERE sites_site_id = $ecom_siteid ";
	$res_sqlcheck=$db->query($sqlcheckp);
	$rowcheck=$db->fetch_array($res_sqlcheck);
	$test=$rowcheck['cnt'];
	if($del_arr[0]){
		for($i=0;$i<count($del_arr);$i++){
		if($test > 0){
		 $sql_selectPayment = "UPDATE  general_settings_site_paymentcapture_type SET payment_capture_types_paymentcapture_id =".$del_arr[$i]." WHERE sites_site_id = $ecom_siteid";
		$db->query($sql_selectPayment);
		}else{
		 $insert_array = array();
			$insert_array['sites_site_id'] = $ecom_siteid;
			$insert_array['payment_capture_types_paymentcapture_id'] = $del_arr[$i];
			$db->insert_from_array($insert_array, 'general_settings_site_paymentcapture_type');
	     }	
	}
	}	
	
	$alert = '';
	
	$alert = 'Changed payment capture types Successfully'; 
	clear_all_cache();// Clearing all cache
	include("../includes/payment_capture_types/list_payment_capture_types.php");
}elseif($_REQUEST['fpurpose'] == 'view_cards') { // to view the credit cards accpeted by this site).
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/payment_types/list_payment_cards.php');
}elseif($_REQUEST['fpurpose'] == 'select_creditcards'){
	//$ajax_return_function = 'ajax_return_contents';
	//include "../ajax/ajax.php";
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$sql_enabled_cc = "SELECT payment_methods_supported_cards_cardtype_id FROM payment_methods_sites_supported_cards WHERE sites_site_id=".$ecom_siteid."";
	$ret_enabled_cc = $db->query($sql_enabled_cc);
	while($enabled_cc = $db->fetch_array($ret_enabled_cc)){
	$arr_enabled_card_ids[] = $enabled_cc['payment_methods_supported_cards_cardtype_id'];
	//echo $enabled_cc['cardtype_id'];
	}
	if($_REQUEST['card_ids']){
		$card_arr = array();
		$card_arr = explode("~",$_REQUEST['card_ids']);
		foreach($arr_enabled_card_ids as $key_existing => $val_existing){
	 		if(!in_array($val_existing,$card_arr)){
		 	$sql_delete_unselected = "DELETE FROM payment_methods_sites_supported_cards WHERE payment_methods_supported_cards_cardtype_id = ".$val_existing." AND sites_site_id= ".$ecom_siteid."";
			$db->query($sql_delete_unselected);
			}
		}
		foreach($card_arr as $key => $val){
	 		if(!in_array($val,$arr_enabled_card_ids)){
			$insert_array = array();
			$insert_array['sites_site_id'] = $ecom_siteid;
			$insert_array['payment_methods_supported_cards_cardtype_id'] = $val;
			$db->insert_from_array($insert_array, 'payment_methods_sites_supported_cards');
			}
		}
	}
	$alert = "Successfully saved";
		include ('../includes/payment_types/list_payment_cards.php');

}
?>