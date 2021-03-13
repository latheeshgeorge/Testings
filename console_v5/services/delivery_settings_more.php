<?php
if($_REQUEST['fpurpose']=='')
{
	
	include("includes/delivery_settings_more/delivery_settings_more.php");
	
}

elseif($_REQUEST['fpurpose'] == 'delivery_settings_update') {
        
		$update_array = array();
         
		$delivery_exclude_from_gift_prom_disc	= ($_REQUEST['delivery_exclude_from_gift_prom_disc'])?1:0;
		$enable_location_datetime				= ($_REQUEST['enable_location_datetime'])?1:0;
		$allow_free_delivery					= ($_REQUEST['allow_free_delivery'])?1:0;
		$free_delivery_mintotal					= ($allow_free_delivery > 0)?$_REQUEST['free_delivery_mintotal']:0;
		$allow_free_delivery_location			= ($_REQUEST['allow_free_delivery_location'])?1:0;
		
		$date_compulsary     = ($_REQUEST['date_compulsary'])?1:0;
		$time_compulsary     = ($_REQUEST['time_compulsary'])?1:0;
		$update_array['delivery_settings_common_min']   		= add_slash($_REQUEST['txt_min_gen']);
		$update_array['delivery_settings_common_max']   		= add_slash($_REQUEST['txt_max_gen']);
		$update_array['delivery_settings_common_increment']   	= add_slash($_REQUEST['txt_inc_gen']);
		
		/*$update_array['delivery_settings_weight_min_limit']  	= add_slash($_REQUEST['txt_min_wgt']);
		$update_array['delivery_settings_weight_max_limit']   	= add_slash($_REQUEST['txt_max_wgt']);
		$update_array['delivery_settings_weight_increment']   	= add_slash($_REQUEST['txt_inc_wgt']);*/
		$update_array['delivery_exclude_from_gift_prom_disc']   = $delivery_exclude_from_gift_prom_disc;
		$update_array['enable_location_datetime']   			= $enable_location_datetime;
		//echo "<pre>";print_r($update_array);
		$update_array['delivery_extra_shipping_minimum_qty']   	= ($_REQUEST['delivery_extra_shipping_minimum_qty'])?1:0;
		/*$update_array['allow_free_delivery']   					= $allow_free_delivery;
		  $update_array['free_delivery_mintotal']   				= $free_delivery_mintotal;
		*/ 
		$update_array['allow_free_delivery_location']   		= $allow_free_delivery_location;
		$update_array['date_compulsary']   						= $date_compulsary;
		$update_array['time_compulsary']   						= $time_compulsary;
		
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		clear_all_cache();// Clearing all cache
		create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting cache file
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
			
		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/delivery_settings_more/delivery_settings_more.php");
}
?>
