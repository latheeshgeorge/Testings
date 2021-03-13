<?

if($_REQUEST['fpurpose'] == '')
{
    $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/price_display/ajax/price_display_ajax_functions.php");
	include("includes/price_display/list_pricedisplay.php");
}
else if($_REQUEST['fpurpose']=='list_pricedisplay_maininfo') 
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/price_display/ajax/price_display_ajax_functions.php");
	show_price_maininfo($alert); 
}
elseif($_REQUEST['fpurpose']=='list_pricedisplay_prod_details')
{
include_once("../functions/functions.php");
include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/price_display/ajax/price_display_ajax_functions.php");
	show_captions_list($alert);
}
elseif($_REQUEST['fpurpose']=='list_others')
{
include_once("../functions/functions.php");
include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/price_display/ajax/price_display_ajax_functions.php");
	show_others_list($alert);
}
else if($_REQUEST['fpurpose'] == 'update_settings')
{
		$update_array = array();
		$update_array['price_displaytype']					= $_REQUEST['price_displaytype'];
		//
		$update_array['price_middleshelf_1_reqbreak']		= 1;//$_REQUEST['price_middleshelf_1_reqbreak'];
		$update_array['price_middleshelf_3_reqbreak']		= 1;//$_REQUEST['price_middleshelf_3_reqbreak'];
		$update_array['price_compshelf_reqbreak']			= 1;//$_REQUEST['price_compshelf_reqbreak'];
		$update_array['price_searchresult_1_reqbreak']		= 1;//$_REQUEST['price_searchresult_1_reqbreak'];
		$update_array['price_searchresult_3_reqbreak']		= 1;//$_REQUEST['price_searchresult_3_reqbreak'];
		$update_array['price_categorydetails_1_reqbreak']	= 1;//$_REQUEST['price_categorydetails_1_reqbreak'];
		$update_array['price_categorydetails_3_reqbreak']	= 1;//$_REQUEST['price_categorydetails_3_reqbreak'];
		$update_array['price_combodeals_1_reqbreak']		= 1;//$_REQUEST['price_combodeals_1_reqbreak'];
		$update_array['price_combodeals_3_reqbreak']		= 1;//$_REQUEST['price_combodeals_3_reqbreak'];
		$update_array['price_best_1_reqbreak']				= 1;//$_REQUEST['price_best_1_reqbreak'];
		$update_array['price_best_3_reqbreak']				= 1;//$_REQUEST['price_best_3_reqbreak'];
		$update_array['price_linkedprod_1_reqbreak']		= 1;//$_REQUEST['price_linkedprod_1_reqbreak'];
		$update_array['price_linkedprod_3_reqbreak']		= 1;//$_REQUEST['price_linkedprod_3_reqbreak'];
		$update_array['price_shopbrand_1_reqbreak']			= 1;//$_REQUEST['price_shopbrand_1_reqbreak'];
		$update_array['price_shopbrand_3_reqbreak']			= 1;//$_REQUEST['price_shopbrand_3_reqbreak'];
		$update_array['price_proddetails_reqbreak']			= 1;//$_REQUEST['price_proddetails_reqbreak'];
		$update_array['price_other_1_reqbreak']				= 1;//$_REQUEST['price_other_1_reqbreak'];
		$update_array['price_other_3_reqbreak']				= 1;//$_REQUEST['price_other_3_reqbreak'];
		//
		$db->update_from_array($update_array, 'general_settings_site_pricedisplay', 'sites_site_id', $ecom_siteid);
		clear_all_cache();// Clearing all cache

		// Creating the  price display settings cache files to be included in client area to save time to access the price displaysettings each time from db
		create_PriceDisplaySettings_CacheFile();

		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/price_display/ajax/price_display_ajax_functions.php");
			include("includes/price_display/list_pricedisplay.php");
}
else if($_REQUEST['fpurpose'] == 'settings_captions_update')
{
$update_array = array();
			//
		$update_array['price_normalprefix']					= add_slash($_REQUEST['price_normalprefix']);
		$update_array['price_normalsuffix']					= add_slash($_REQUEST['price_normalsuffix']);
		$update_array['price_fromprefix']					= add_slash($_REQUEST['price_fromprefix']);
		$update_array['price_fromsuffix']					= add_slash($_REQUEST['price_fromsuffix']);
		$update_array['price_specialofferprefix']			= add_slash($_REQUEST['price_specialofferprefix']);
		$update_array['price_specialoffersuffix']			= add_slash($_REQUEST['price_specialoffersuffix']);
		$update_array['price_yousaveprefix']				= add_slash($_REQUEST['price_yousaveprefix']);
		$update_array['price_yousavesuffix']				= add_slash($_REQUEST['price_yousavesuffix']);
		$update_array['price_noprice']						= add_slash($_REQUEST['price_noprice']);
		$update_array['price_discountprefix']				= add_slash($_REQUEST['price_discountprefix']);
		$update_array['price_discountsuffix']				= add_slash($_REQUEST['price_discountsuffix']);
		$update_array['price_availabledateprefix']			= add_slash($_REQUEST['price_availabledateprefix']);
		$update_array['price_availabledatesuffix']			= add_slash($_REQUEST['price_availabledatesuffix']);
		$update_array['price_variablepriceadd_prefix']		= add_slash($_REQUEST['price_variablepriceadd_prefix']);
		$update_array['price_variablepriceadd_suffix']		= add_slash($_REQUEST['price_variablepriceadd_suffix']);
		$update_array['price_variablepriceless_prefix']		= add_slash($_REQUEST['price_variablepriceless_prefix']);
		$update_array['price_variablepriceless_suffix']		= add_slash($_REQUEST['price_variablepriceless_suffix']);
		$update_array['price_variablepricefull_prefix']		= add_slash($_REQUEST['price_variablepricefull_prefix']);
		$update_array['price_variablepricefull_suffix']		= add_slash($_REQUEST['price_variablepricefull_suffix']);
		$update_array['price_tax_plus']						= add_slash($_REQUEST['price_tax_plus']);
		$update_array['price_tax_inc']						= add_slash($_REQUEST['price_tax_inc']);
		$update_array['price_tax_exc']						= add_slash($_REQUEST['price_tax_exc']);
			//
		$db->update_from_array($update_array, 'general_settings_site_pricedisplay', 'sites_site_id', $ecom_siteid);
		$alert = '<center><b>Successfully Updated</b><br>';
		clear_all_cache();// Clearing all cache
		
		// Creating the  price display settings cache files to be included in client area to save time to access the price displaysettings each time from db
		create_PriceDisplaySettings_CacheFile();
		
		$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/price_display/ajax/price_display_ajax_functions.php");
			$curtab = 'proddetails_tab_td';
		include("includes/price_display/list_pricedisplay.php");
}
else if($_REQUEST['fpurpose'] == 'settings_others_update')
{
		$update_array = array();
		$update_array['price_variableprice_display']		= $_REQUEST['price_variableprice_display'];
		//
		$update_array['price_show_yousave']					= $_REQUEST['price_show_yousave'];
		$update_array['strike_baseprice']					= $_REQUEST['strike_baseprice'];
		$update_array['price_applydiscount_tovariable']		= $_REQUEST['price_applydiscount_tovariable'];
		$update_array['price_display_discount_with_price']	= ($_REQUEST['price_display_discount_with_price'])?1:0;
		//
		$db->update_from_array($update_array, 'general_settings_site_pricedisplay', 'sites_site_id', $ecom_siteid);
		$alert = '<center><b>Successfully Updated</b><br>';
		clear_all_cache();// Clearing all cache
		
		// Creating the  price display settings cache files to be included in client area to save time to access the price displaysettings each time from db
		create_PriceDisplaySettings_CacheFile();
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/price_display/ajax/price_display_ajax_functions.php");
		$curtab = 'others_tab_td';
		include("includes/price_display/list_pricedisplay.php");
}
?>
