<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 	: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on		: 13-Jun-2007
	# Modified by	: Sny
	# Modified On	: 08-Jul-2008
	#################################################################
	
	*/
if($_REQUEST['fpurpose'] == '') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
			
	//include ('includes/bestseller/ajax/bestseller_ajax_functions.php');
	include("includes/bestseller/bestseller.php");
} 

else if($_REQUEST['fpurpose'] == 'settings_default_update') {
		$update_array													    = array();
		$update_array['best_seller_picktype']   							= ($_REQUEST['best_seller_picktype'])?1:0;
		
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);

		clear_all_cache();// Clearing all cache
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();

			
		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/bestseller/bestseller.php");
} 
elseif($_REQUEST['fpurpose']=='list_best_maininfo')//Listing products assigned to best seller
{	   
        
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		
		include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
		bestseller_maininfo($alert);
}
	
elseif($_REQUEST['fpurpose']=='list_productbestseller')//Listing products assigned to best seller
{	   
        
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		
		include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
		show_product_bestseller_list($alert);
}
elseif($_REQUEST['fpurpose']=='list_productupsell')//Listing products assigned as upsell
{	   
        
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		
		include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
		show_product_upsell_list($alert);
}
elseif($_REQUEST['fpurpose']=='prodBestsellerAssign') // Assign products to best sellers
{
	include ('includes/bestseller/list_bestseller_selproduct.php');
}
elseif($_REQUEST['fpurpose'] == 'save_prodBestsellerAssign') // Save products to best sellers
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		// Check whether this product is already assigned
		$sql_check = "SELECT products_product_id FROM general_settings_site_bestseller WHERE sites_site_id = $ecom_siteid AND 
						products_product_id = $v";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0)
		{
			$insert_array							= array();
			$insert_array['sites_site_id'] 			= $ecom_siteid;
			$insert_array['products_product_id']	= $v;
			$db->insert_from_array($insert_array, 'general_settings_site_bestseller');
		}			
	}
	
	clear_all_cache();// Clearing all cache
	// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
	create_GeneralSettings_CacheFile();
	
	$alert	='Product Assigned Successfullly';
	$alert 	= '<center><font color="red"><b>'.$alert;
	$alert 	.= '</b></font></center>';
	echo '<br />'.$alert;
	?>
	<br /><a class="smalllink" href="home.php?request=bestseller&curtab=prods_tab_td&pick_type=<?=$_REQUEST['pick_type']?>" onclick="show_processing()">Go Back to the Bestsellers page</a><br /><br />

<?		
}
elseif($_REQUEST['fpurpose']=='save_order') // Products order for bestseller list
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['ch_ids']);
	$OrderArr=explode('~',$_REQUEST['ch_order']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array						= array();
		$update_array['bestsel_sortorder']	= $OrderArr[$i];
		$db->update_from_array($update_array,'general_settings_site_bestseller',array('bestsel_id'=>$IdArr[$i]));
		
	}
	
	clear_all_cache();// Clearing all cache
	// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
	create_GeneralSettings_CacheFile();
			
	$alert = 'Order saved successfully.';
	include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
	show_product_bestseller_list($alert);
}
elseif($_REQUEST['fpurpose']=='prodbestsellerUnAssign') //Un assign product from best seller list
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM general_settings_site_bestseller WHERE bestsel_id=$id";
			$db->query($sql_del);
			
	}
	
	clear_all_cache();// Clearing all cache
	// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
	create_GeneralSettings_CacheFile();
		
	$alert = 'Product unassigned successfully.';
	include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
	show_product_bestseller_list($alert);
}
elseif($_REQUEST['fpurpose']=='prodbestsellerUnAssign') //Un assign product from best seller list
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM general_settings_site_bestseller WHERE bestsel_id=$id";
			$db->query($sql_del);
			
	}
	
	clear_all_cache();// Clearing all cache
	// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
	create_GeneralSettings_CacheFile();
		
	$alert = 'Product unassigned successfully.';
	include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
	show_product_bestseller_list($alert);
}
elseif($_REQUEST['fpurpose']=='prodUpsellAssign') // Assign products to best sellers
{
	include ('includes/bestseller/list_upsell_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_order_upsell') // Products order for bestseller list
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['ch_ids']);
	$OrderArr=explode('~',$_REQUEST['ch_order']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array						= array();
		$update_array['upsell_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'upsell_products_map',array('upsell_id'=>$IdArr[$i]));
		
	}
			
	$alert = 'Order saved successfully.';
	include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
	show_product_upsell_list($alert);
}
elseif($_REQUEST['fpurpose']=='produpsellUnAssign') //Un assign product from best seller list
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM upsell_products_map WHERE upsell_id=$id LIMIT 1";
			$db->query($sql_del);
			
	}
	
	$alert = 'Product unassigned successfully.';
	include ('../includes/bestseller/ajax/bestseller_ajax_functions.php');
	show_product_upsell_list($alert);
}
elseif($_REQUEST['fpurpose'] == 'save_prodUpsellAssign') // Save products to best sellers
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		// Check whether this product is already assigned
		$sql_check = "SELECT products_product_id FROM upsell_products_map WHERE sites_site_id = $ecom_siteid AND 
						products_product_id = $v LIMIT 1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0)
		{
			$insert_array							= array();
			$insert_array['sites_site_id'] 			= $ecom_siteid;
			$insert_array['products_product_id']	= $v;
			$db->insert_from_array($insert_array, 'upsell_products_map');
		}			
	}
	
	
	$alert	='Product Assigned Successfullly';
	$alert 	= '<center><font color="red"><b>'.$alert;
	$alert 	.= '</b></font></center>';
	echo '<br />'.$alert;
	?>
	<br /><a class="smalllink" href="home.php?request=bestseller&curtab=upselprods_tab_td" onclick="show_processing()">Go Back to the Upsell products page</a><br /><br />

<?		
}
?>
