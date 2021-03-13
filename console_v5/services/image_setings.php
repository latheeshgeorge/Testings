<?php
if($_REQUEST['fpurpose']=='')
{
    include("includes/image_settings/image_settings.php");
}
elseif($_REQUEST['fpurpose'] == 'images_default_update')
{
		$thumbnail_in_viewcart 					= ($_REQUEST['thumbnail_in_viewcart'])?1:0;
		$thumbnail_in_wishlist 					= ($_REQUEST['thumbnail_in_wishlist'])?1:0;
		$thumbnail_in_enquiry 					= ($_REQUEST['thumbnail_in_enquiry'])?1:0;
		$turnoff_catimage 						= ($_REQUEST['turnoff_catimage'])?1:0;
		$turnoff_recently 						= ($_REQUEST['recentlyviewed_hide_image'])?1:0;
		
		$update_array = array();

		$update_array['thumbnail_in_viewcart'] 							= $thumbnail_in_viewcart;
		$update_array['thumbnail_in_wishlist'] 							= $thumbnail_in_wishlist;
		$update_array['thumbnail_in_enquiry'] 							= $thumbnail_in_enquiry;
		$update_array['turnoff_catimage']   							= $turnoff_catimage;
		$update_array['recentlyviewed_hide_image']   					= $turnoff_recently;
		
		$update_array['compshelf_showimagetype']   					= add_slash($_REQUEST['compshelf_showimagetype']);
		$update_array['midshelf_showimagetype']   					= add_slash($_REQUEST['midshelf_showimagetype']);
		$update_array['recent_showimagetype']   						= add_slash($_REQUEST['recent_showimagetype']);
		$update_array['search_showimagetype']   						= add_slash($_REQUEST['search_showimagetype']);
		$update_array['search_catshowimagetype']   						= add_slash($_REQUEST['search_catshowimagetype']);
		$update_array['category_showimagetype']   					= add_slash($_REQUEST['category_showimagetype']);
		
		//$update_array['subcategory_showimagetype']   				= add_slash($_REQUEST['subcategory_showimagetype']);
		
		$update_array['categoryprod_showimagetype']   			= add_slash($_REQUEST['categoryprod_showimagetype']);
		$update_array['productdetail_showimagetype']   			= add_slash($_REQUEST['productdetail_showimagetype']);
		$update_array['linkedprod_showimagetype']   				= add_slash($_REQUEST['linkedprod_showimagetype']);
		$update_array['midcombo_showimagetype']   				= add_slash($_REQUEST['midcombo_showimagetype']);
		$update_array['shop_showimagetype']   						= add_slash($_REQUEST['shop_showimagetype']);
		$update_array['subshop_showimagetype']   					= add_slash($_REQUEST['subshop_showimagetype']);
		$update_array['shopprod_showimagetype']   					= add_slash($_REQUEST['shopprod_showimagetype']);
		$update_array['myfavouritecategory_showimagetype']   	= add_slash($_REQUEST['myfavouritecategory_showimagetype']);
		$update_array['myfavouriteproduct_showimagetype']   	= add_slash($_REQUEST['myfavouriteproduct_showimagetype']);
		$update_array['product_cart_showimagetype']				= add_slash($_REQUEST['product_cart_showimagetype']);
		$update_array['product_enquiry_showimagetype']			= add_slash($_REQUEST['product_enquiry_showimagetype']);
		$update_array['product_wishlist_showimagetype']			= add_slash($_REQUEST['product_wishlist_showimagetype']);
		
	
		
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		clear_all_cache();// Clearing all cache
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
			
		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/image_settings/image_settings.php");
}
elseif($_REQUEST['fpurpose']=='add_sslimg') // show image gallery to select the required images
	{
	 $ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
elseif($_REQUEST['fpurpose']=='add_default_sslimg'){
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$sql_img =	"SELECT payment_methods_forsites_id 
						FROM payment_methods_forsites pms,payment_methods pm
						WHERE  pms.sites_site_id = $ecom_siteid and pm.paymethod_id=pms.payment_methods_paymethod_id"; 
	$ret_img = $db->query($sql_img);
	while($ssl_img = $db->fetch_array($ret_img)){
	if($_REQUEST["'ssl_image_".$ssl_img['payment_methods_forsites_id']."'"])
	$update_array									= array();
	$update_array['payment_method_sites_image_id']	= 0 ; 
	$db->update_from_array($update_array,'payment_methods_forsites',array('payment_methods_forsites_id'=>$ssl_img['payment_methods_forsites_id'],'sites_site_id'=>$ecom_siteid));
	}
	include("includes/image_settings/image_settings.php");

}
	elseif($_REQUEST['fpurpose']=='unassign_ssl_img') // show image gallery to select the required images
	{
	$id = $id_arr[$i];	
			$sql_del = "DELETE FROM images_ssl WHERE sites_site_id=$ecom_siteid";
			$db->query($sql_del);
				$alert = 'SSL Image Un Assigned Successfully.';
				include("includes/image_settings/image_settings.php");

	}
?>