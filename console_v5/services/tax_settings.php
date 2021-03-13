<?php
if($_REQUEST['fpurpose']=='')
{
	
	include("includes/sale_tax/sale_tax_settings.php");
	
}

elseif($_REQUEST['fpurpose'] == 'sale_tax_settings_update') {
		
		$saletax_before_discount 				= ($_REQUEST['saletax_before_discount'])?1:0;
		$apply_tax_ondelivery					= ($_REQUEST['apply_tax_ondelivery'])?1:0;
		$apply_tax_ongiftwrap					= ($_REQUEST['apply_tax_ongiftwrap'])?1:0;
		$apply_tax_value_promgift_calc			= ($_REQUEST['apply_tax_value_promgift_calc'])?1:0;

		$update_array = array();

		$update_array['saletax_before_discount']   						= $saletax_before_discount;
		$update_array['apply_tax_ondelivery']   						= $apply_tax_ondelivery;
		$update_array['apply_tax_ongiftwrap']   						= $apply_tax_ongiftwrap;
		$update_array['apply_tax_value_promgift_calc']   				= $apply_tax_value_promgift_calc;
	
		
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		clear_all_cache();// Clearing all cache
		create_GeneralSettings_CacheFile(); // generating general settings cache file
			
		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/sale_tax/sale_tax_settings.php");
}
?>