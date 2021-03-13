<?php
/*
	#################################################################
	# Script Name 	: view_profile.php
	# Description 		: Action Page for changing the details of the logged in users
	# Coded by 		: ANU
	# Created on		: 13-Jun-2007
	# Modified by		: Sny
	# Modified On		: 24-Nov-2008
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	include("includes/general_settings/main_general.php");
} elseif($_REQUEST['fpurpose'] == 'captions') {
$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/general_settings/list_settings_options.php");
}
elseif($_REQUEST['fpurpose'] == 'add_captions') {
	include("includes/general_settings/add_settings_captions.php");
}
elseif($_REQUEST['fpurpose'] == 'insert_settings_captions') {
if($_REQUEST['Submit'])
	{
	
		$alert = '';
		$fieldRequired	 		= array($_REQUEST['settings_section_id'],$_REQUEST['general_key']);
		$fieldDescription 		= array('General Settings Section','General Settings Key');
		$fieldEmail 				= array();
		$fieldConfirm 			= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 			= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check_key 	= "SELECT count(*) as key_cnt FROM general_settings_site_captions WHERE general_key='".add_slash($_REQUEST['general_key'])."' AND sites_site_id=$ecom_siteid AND general_settings_section_section_id=".$_REQUEST['settings_section_id'];
		$res_check_key 	= $db->query($sql_check_key);
		$row_check_key = $db->fetch_array($res_check_key);
		if($row_check_key['key_cnt'] > 0) {
			$alert = 'Error:   General Settings Section Key already exists for selected Section!!';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['general_settings_section_section_id'] = add_slash($_REQUEST['settings_section_id']);
			$insert_array['sites_site_id'] 									= $ecom_siteid;	
			$insert_array['general_key']   								= add_slash($_REQUEST['general_key']);	
			$insert_array['general_text']  								= add_slash($_REQUEST['general_text']);
			
			$db->insert_from_array($insert_array, 'general_settings_site_captions');
			$insert_id = $db->insert_id();
			
			clear_all_cache();// Clearing all cache
			
			// Creating the respective cache files for captions for the current section
			 create_Captions_CacheFile($_REQUEST['settings_section_id']);
			
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a  class="smalllink" href="home.php?request=general_settings&fpurpose=captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the General Settings Captions Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings&fpurpose=edit_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&general_id=<?=$insert_id?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Caption</a>
			<br /><br />
			<a class="smalllink" href="home.php?request=general_settings&fpurpose=add_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Captions</a>
			</center>
			<?
		}else{
		include("includes/general_settings/add_settings_captions.php");
		}
	}
}
elseif($_REQUEST['fpurpose'] == 'edit_captions') {
	include("includes/general_settings/edit_settings_captions.php");
}elseif($_REQUEST['fpurpose'] == 'update_settings_captions') {
if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array($_REQUEST['settings_section_id'],$_REQUEST['general_key']);
		$fieldDescription 		= array('General Settings Section','General Settings Key');
		$fieldEmail 				= array();
		$fieldConfirm 			= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 			= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check_key 		= "SELECT count(*) as key_cnt FROM general_settings_site_captions WHERE general_key='".add_slash($_REQUEST['general_key'])."' AND sites_site_id=$ecom_siteid AND general_settings_section_section_id=".$_REQUEST['settings_section_id']." AND general_id !=".$_REQUEST['general_id']."";
		$res_check_key 		= $db->query($sql_check_key);
		$row_check_key 	= $db->fetch_array($res_check_key);
		if($row_check_key['key_cnt'] > 0) {
			$alert = 'Error:   General Settings Section Key already exists for selected Section!!';
			include("includes/general_settings/edit_settings_captions.php");
		}
		if(!$alert) {
			$update_array = array();
			$update_array['general_settings_section_section_id'] 	= add_slash($_REQUEST['settings_section_id']);
			$update_array['sites_site_id'] 									= $ecom_siteid;	
			$update_array['general_key']   									= add_slash($_REQUEST['general_key']);	
			$update_array['general_text']  									= add_slash($_REQUEST['general_text']);
			
			$db->update_from_array($update_array, 'general_settings_site_captions','general_id', $_REQUEST['general_id']);
			
			clear_all_cache();// Clearing all cache
			
			// Creating the respective cache files for captions for the current section
			 create_Captions_CacheFile($_REQUEST['settings_section_id']);
			 
			// create_Captions_CacheFile_All();
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
		?>
		<br />
		<a class="smalllink"  href="home.php?request=general_settings&fpurpose=captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the General Settings Captions Listing page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=general_settings&fpurpose=edit_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&general_id=<?=$_REQUEST['general_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Caption</a>
			<br /><br />
			<a class="smalllink" href="home.php?request=general_settings&fpurpose=add_captions&site_captions=<?=$_REQUEST['site_captions']?>&settings_section=<?=$_REQUEST['settings_section']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Add New Captions</a>
			</center>
		<? }else{
		include("includes/general_settings/edit_settings_captions.php");
		}
}
}elseif($_REQUEST['fpurpose'] == 'settings_default') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/general_settings/ajax/main_shop_ajax_functions.php");
	include("includes/general_settings/edit_settings_default.php");
}else if($_REQUEST['fpurpose'] == 'list_design_maininfo') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/general_settings/ajax/main_shop_ajax_functions.php");
	include("includes/general_settings/edit_settings_default.php");
}
else if($_REQUEST['fpurpose'] == 'settings_default_update_settings') {
	if($_REQUEST['Submit']) {
		$empty_cart 						= ($_REQUEST['empty_cart'])?1:0;
		$empty_wishlist 							= ($_REQUEST['empty_wishlist'])?1:0;
		$product_compare_enable				= ($_REQUEST['product_compare_enable'])?1:0;
		$bonus_points_instock 					= ($_REQUEST['bonus_points_instock'])?1:0;
      
	  		$update_array = array();

		$update_array['empty_cart']   							= $empty_cart;
		$update_array['empty_wishlist']   						= $empty_wishlist;
		$update_array['product_compare_enable']   				= $product_compare_enable;
		$update_array['hide_newuser']   						= ($_REQUEST['hide_newuser'])?1:0;
		$update_array['show_cart_promotional_voucher']   		= ($_REQUEST['show_cart_promotional_voucher'])?1:0;
		$update_array['hide_forgotpass']   						= ($_REQUEST['hide_forgotpass'])?1:0;
		$update_array['search_prodlisting']   					= add_slash($_REQUEST['search_prodlisting']);
		$update_array['linked_prodlisting']   					= add_slash($_REQUEST['linked_prodlisting']);
		$update_array['bestseller_prodlisting']   				= add_slash($_REQUEST['bestseller_prodlisting']);
		$update_array['favorite_prodlisting']   				= add_slash($_REQUEST['favorite_prodlisting']);
		$update_array['favoritecategory_prodlisting']   		= add_slash($_REQUEST['favoritecategory_prodlisting']);
		$update_array['recentpurchased_prodlisting']   			= add_slash($_REQUEST['recentpurchased_prodlisting']);
		$update_array['promo_prodlisting']   					= add_slash($_REQUEST['promo_prodlisting']);
		$update_array['preorder_prodlisting']   				= add_slash($_REQUEST['preorder_prodlisting']);
		$update_array['show_qty_box']   						= ($_REQUEST['show_qty_box'])?1:0;
		$update_array['bonus_points_instock']   				= $bonus_points_instock;
		$update_array['config_continue_shopping']  				= $_REQUEST['config_continue_shopping'];
		$update_array['paytype_listingtype']   					= add_slash($_REQUEST['paytype_listingtype']);
                $update_array['themes_layouts_layout_id']                               = $_REQUEST['themes_layouts_layout_id'];
                if($_REQUEST['themes_layouts_layout_id'])
                {
                    // Get the layout code for current layout id
                    $sql_layout = "SELECT layout_code 
                                        FROM  
                                            themes_layouts 
                                        WHERE 
                                            layout_id=".$_REQUEST['themes_layouts_layout_id']." 
                                        LIMIT 
                                            1";
                    $ret_layout = $db->query($sql_layout);
                    if($db->num_rows($ret_layout))
                    {
                        $row_layout = $db->fetch_array($ret_layout);
                        $update_array['themes_layouts_layout_code']                = add_slash($row_layout['layout_code']);
                    }
                }
                else
                    $update_array['themes_layouts_layout_code']                = '';
		//not used
		$update_array['show_variable_new_row']   				= 0;//($_REQUEST['show_variable_new_row'])?1:0;
		$update_array['show_prod_image_inflash']   				= ($_REQUEST['show_prod_image_inflash'])?1:0;
         // end
		$update_array['no_of_products_to_compare']   			= ($product_compare_enable)?add_slash($_REQUEST['no_of_products_to_compare']):2;
		$update_array['product_compare_prodlist_enable']   		= ($product_compare_enable)?add_slash($_REQUEST['product_compare_prodlist_enable']):0;
		$update_array['product_compare_proddetail_enable']  	= ($product_compare_enable)?add_slash($_REQUEST['product_compare_proddetail_enable']):0;
  
       //Category
	   $update_array['category_subcatlisttype']					= add_slash($_REQUEST['category_subcatlisttype']);
	   $update_array['category_subcatlistmethod']				= add_slash($_REQUEST['category_subcatlistmethod']);
	   $update_array['subcategory_showimagetype']				= add_slash($_REQUEST['subcategory_showimagetype']);
	   $update_array['category_showname']						= ($_REQUEST['category_showname'])?1:0;
	   $update_array['category_showshortdesc']					= ($_REQUEST['category_showshortdesc'])?1:0;
	   $update_array['category_showimage']						= ($_REQUEST['category_showimage'])?1:0;
	   $update_array['category_turnoff_moreimages']				= ($_REQUEST['category_turnoff_moreimages'])?1:0;
	   $update_array['category_turnoff_mainimage']				= ($_REQUEST['category_turnoff_mainimage'])?1:0;
	   $update_array['category_turnoff_noproducts']				= ($_REQUEST['category_turnoff_noproducts'])?1:0;
	   $update_array['product_orderfield']						= add_slash($_REQUEST['product_orderfield']);
	   $update_array['product_orderby']							= add_slash($_REQUEST['product_orderby']);
	   $update_array['category_turnoff_treemenu']				= ($_REQUEST['chk_category_turnoff_treemenu'])?1:0;
	    $update_array['category_turnoff_pdf']					= ($_REQUEST['chk_category_turnoff_pdf'])?1:0;
	   
		$update_array['product_displaytype']					= add_slash($_REQUEST['product_displaytype']);
		$update_array['product_displaywhere']					= add_slash($_REQUEST['product_displaywhere']);
		$update_array['product_showimage']						= ($_REQUEST['product_showimage']==1)?1:0;
		$update_array['product_showtitle']						= ($_REQUEST['product_showtitle']==1)?1:0;
		$update_array['product_showshortdescription']			= ($_REQUEST['product_showshortdescription']==1)?1:0;
		$update_array['product_showprice']						= ($_REQUEST['product_showprice']==1)?1:0;
		$update_array['product_showrating']						= ($_REQUEST['product_showrating']==1)?1:0;
		$update_array['product_showbonuspoints']						= ($_REQUEST['product_showbonuspoints']==1)?1:0;
		$update_array['gift_voucher_apply_customer_direct_disc_also']	= ($_REQUEST['gift_voucher_apply_customer_direct_disc_also']==1)?'Y':'N';
		$update_array['gift_voucher_apply_customer_group_disc_also']	= ($_REQUEST['gift_voucher_apply_customer_group_disc_also']==1)?'Y':'N';
		$update_array['gift_voucher_apply_direct_product_discount_also']= ($_REQUEST['gift_voucher_apply_direct_product_discount_also']==1)?'Y':'N';
		$update_array['show_downloads_newrow']					= ($_REQUEST['show_downloads_newrow']==1)?1:0;
		$update_array['show_bookmarks']							= ($_REQUEST['show_bookmarks']==1)?1:0;
		
	   //
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);

		$update_array = array();
		//Advanced Search
		$update_array['adv_showkeyword']   						= ($_REQUEST['adv_showkeyword']==1)?1:0;
		$update_array['adv_showcategory']   					= ($_REQUEST['adv_showcategory']==1)?1:0; 
		$update_array['adv_showstocklevel']   					= ($_REQUEST['adv_showstocklevel']==1)?1:0;
        $update_array['adv_showpricerange']   					= ($_REQUEST['adv_showpricerange']==1)?1:0;
		$update_array['adv_showcharacteristics']   				= ($_REQUEST['adv_showcharacteristics']==1)?1:0;
		$update_array['adv_showproductmodel']   				= ($_REQUEST['adv_showproductmodel']==1)?1:0;
		$update_array['adv_showlabel']   						= ($_REQUEST['adv_showlabel']==1)?1:0;
		$update_array['adv_showsearchfor']   					= ($_REQUEST['adv_showsearchfor']==1)?1:0;
		$update_array['adv_showsearchincluding']   				= ($_REQUEST['adv_showsearchincluding']==1)?1:0;
		$update_array['adv_shosearchsortby']   					= ($_REQUEST['adv_shosearchsortby']==1)?1:0;
		$update_array['adv_showsearchperpage']   				= ($_REQUEST['adv_showsearchperpage']==1)?1:0;
       //Compare page
	   $update_array['comp_showprice']							= ($_REQUEST['comp_showprice']==1)?1:0;
		$update_array['comp_showstock']							= ($_REQUEST['comp_showstock']==1)?1:0;
		$update_array['comp_showlabels']							= ($_REQUEST['comp_showlabels']==1)?1:0;
		$update_array['comp_showdesc']							= ($_REQUEST['comp_showdesc']==1)?1:0;
		$update_array['comp_showbulkdisc']						= ($_REQUEST['comp_showbulkdisc']==1)?1:0;
		$update_array['comp_showshipping']						= ($_REQUEST['comp_showshipping']==1)?1:0;
		$update_array['comp_showmanufact']						= ($_REQUEST['comp_showmanufact']==1)?1:0;
		$update_array['comp_showmodel']							= ($_REQUEST['comp_showmodel']==1)?1:0;
		$update_array['comp_showbonus']							= ($_REQUEST['comp_showbonus']==1)?1:0;
		$update_array['comp_showrating']						= ($_REQUEST['comp_showrating']==1)?1:0;
		$update_array['comp_showweight']						= ($_REQUEST['comp_showweight']==1)?1:0;
		$update_array['comp_showfreedelivery']					= ($_REQUEST['comp_showfreedelivery']==1)?1:0;
		$update_array['proddet_showfavourite']					= ($_REQUEST['proddet_showfavourite']==1)?1:0;
		$update_array['proddet_showwritereview']				= ($_REQUEST['proddet_showwritereview']==1)?1:0;
		$update_array['proddet_showemailfriend']				= ($_REQUEST['proddet_showemailfriend']==1)?1:0;
		$update_array['proddet_showpdf']						= ($_REQUEST['proddet_showpdf']==1)?1:0;
		$update_array['proddet_showreadreview']					= ($_REQUEST['proddet_showreadreview']==1)?1:0;
		$update_array['proddet_showwishlist']					= ($_REQUEST['proddet_showwishlist']==1)?1:0;
		
		
		$update_array['newsletter_title_req']					= ($_REQUEST['newsletter_title_req']==1)?1:0;
		$update_array['newsletter_name_req']					= ($_REQUEST['newsletter_name_req']==1)?1:0;
		$update_array['newsletter_phone_req']					= ($_REQUEST['newsletter_phone_req']==1)?1:0;
		$update_array['newsletter_group_req']					= ($_REQUEST['newsletter_group_req']==1)?1:0;
		$update_array['showcustomerlogin_as_banner']			= ($_REQUEST['showcustomerlogin_as_banner']==1)?1:0;
		
		$update_array['showsizechart_in_popup']					= ($_REQUEST['showsizechart_in_popup']==1)?1:0;
		$update_array['shownewsletter_as_banner']				= ($_REQUEST['shownewsletter_as_banner']==1)?1:0;
		
		$update_array['show_custreg_newanddiscprod_newsletter_checkbox']	= ($_REQUEST['show_custreg_newanddiscprod_newsletter_checkbox']==1)?1:0;
		$update_array['show_custreg_newslettergroup_checkbox']				= ($_REQUEST['show_custreg_newslettergroup_checkbox']==1)?1:0;
		
		$update_array['proddet_showbarcode']			= ($_REQUEST['proddet_showbarcode']==1)?1:0;
		
		
		$db->update_from_array($update_array, 'general_settings_sites_common_onoff','sites_site_id', $ecom_siteid);
		
		clear_all_cache();// Clearing all cache
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		 
		$alert = '<center><b>Successfully Updated</b><br>';
		
}
$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/main_shop_ajax_functions.php");
		include("includes/general_settings/edit_settings_default.php");	
}
else if($_REQUEST['fpurpose'] == 'list_security') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/main_shop_ajax_functions.php");
	show_security_list($alert);
}
else if($_REQUEST['fpurpose'] == 'settings_security_update') {
			$encrypted_cc_numbers 					= ($_REQUEST['encrypted_cc_numbers'])?1:0;

			$update_array = array();
		$update_array['encrypted_cc_numbers']   				= $encrypted_cc_numbers;
		$update_array['ban_ipaddress']   						= $_REQUEST['ban_ipaddress'];
		$update_array['imageverification_req_newsletter']   	= ($_REQUEST['imageverification_news_req'])?1:0;
		$update_array['imageverification_req_voucher']   		= ($_REQUEST['imageverification_vouch_req'])?1:0;
		$update_array['imageverification_req_sitereview']   	= ($_REQUEST['imageverification_site_req'])?1:0;
		$update_array['imageverification_req_customreg']   		= ($_REQUEST['imageverification_cust_req'])?1:0;
		$update_array['imageverification_req_prodreview']   	= ($_REQUEST['imageverification_prod_req'])?1:0;
		$update_array['imageverification_req_payonaccount']   	= ($_REQUEST['imageverification_req_payonaccount'])?1:0;
		$update_array['imageverification_req_callback']   		= ($_REQUEST['imageverification_callback_req'])?1:0;
		
		$update_array['steamdesk_security_key']   				= trim($_REQUEST['steamdesk_security_key']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$curtab='security_tab_td';
		clear_all_cache();// Clearing all cache
		$alert = '<center><b>Successfully Updated</b><br>';
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/main_shop_ajax_functions.php");
		include("includes/general_settings/edit_settings_default.php");	
	
	}
	else if($_REQUEST['fpurpose'] == 'list_administration') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/main_shop_ajax_functions.php");
	show_administration_list($alert);
}
else if($_REQUEST['fpurpose'] == 'settings_administration_update') {
		$forcecustomer_login_checkout 				= ($_REQUEST['forcecustomer_login_checkout'])?1:0;
		$terms_and_condition_at_checkout 			= ($_REQUEST['terms_and_condition_at_checkout'])?1:0;
		$same_billing_shipping_checkout 			= ($_REQUEST['same_billing_shipping_checkout'])?1:0;
		$hide_addtocart_login 						= ($_REQUEST['hide_addtocart_login'])?1:0;
		$hide_price_login 							= ($_REQUEST['hide_price_login'])?1:0;
		$enable_caching_in_site 					= ($_REQUEST['enable_caching_in_site'])?1:0;
		$javascript_lightbox						= ($_REQUEST['javascript_lightbox'])?1:0;
		$javascript_imageswap						= ($_REQUEST['javascript_imageswap'])?1:0;
		$javascript_jquery							= ($_REQUEST['javascript_jquery'])?1:0;
		$enable_ajax								= ($_REQUEST['enable_ajax_in_site'])?1:0;
		$proddet_special							= ($_REQUEST['proddet_special_display'])?1:0;

		$printerfriendly_include_delivery_address	= ($_REQUEST['printerfriendly_include_delivery_address'])?1:0;
		$showproddet_showbarcode					= ($_REQUEST['proddet_showbarcode'])?1:0;
		$showadd_barcode_to_product_keyword			= ($_REQUEST['add_barcode_to_product_keyword'])?1:0;
		
		
		$update_array = array();
		$update_array['terms_and_condition_at_checkout']   		= $terms_and_condition_at_checkout;
		$update_array['same_billing_shipping_checkout']   		= $same_billing_shipping_checkout;
		$update_array['hide_addtocart_login']   				= $hide_addtocart_login;
		$update_array['hide_price_login'] 						= $hide_price_login; 
		$update_array['enable_caching_in_site']   				= $enable_caching_in_site;
		$update_array['voucher_prefix'] 						= add_slash($_REQUEST['voucher_prefix']); 
		//$update_array['pick_currency_rate_automatically']			= ($_REQUEST['pick_currency_rate_automatically']==1)?1:0;
		$update_array['unit_of_weight']  						= $_REQUEST['unit_of_weight'];
		$update_array['product_sizechart_default_mainheading']	= add_slash($_REQUEST['product_sizechart_default_mainheading']);
		$update_array['forcecustomer_login_checkout']   		= $forcecustomer_login_checkout;
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		$update_array 												= array();
		$update_array['javascript_lightbox']   						= $javascript_lightbox;
		$update_array['javascript_imageswap']   					= $javascript_imageswap;
		$update_array['javascript_jquery']   						= $javascript_jquery;
		$update_array['enable_ajax_in_site']   						= $enable_ajax;
		$update_array['proddet_special_display']   					= $proddet_special;
		$update_array['printerfriendly_include_delivery_address']   = $printerfriendly_include_delivery_address;
		$update_array['add_barcode_to_product_keyword']  	 		= $showadd_barcode_to_product_keyword;
		$update_array['product_variable_display_type']  	 		= $_REQUEST['product_variable_display_type'];
		$db->update_from_array($update_array, 'general_settings_sites_common_onoff','sites_site_id', $ecom_siteid);
		
		$site_hide_console_error_msgs = ($_REQUEST['site_hide_console_error_msgs'])?1:0;
		$update_sql = "UPDATE 
							sites 
						SET 
							site_hide_console_error_msgs = $site_hide_console_error_msgs 
						WHERE 
							site_id = $ecom_siteid 
						LIMIT 
							1";
		$db->query($update_sql);
		$ecom_site_hide_console_error_msgs = $site_hide_console_error_msgs;// Resetting the value in global variable
		$curtab='administration_tab_td';
		clear_all_cache();// Clearing all cache 
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$alert = '<center><b>Successfully Updated</b><br>';
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		//create_GeneralSettings_CacheFile();
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/main_shop_ajax_functions.php");
		include("includes/general_settings/edit_settings_default.php");	
	
	}
	else if($_REQUEST['fpurpose'] == 'list_inventory') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/main_shop_ajax_functions.php");
	show_inventory_list($alert);
}
else if($_REQUEST['fpurpose'] == 'settings_inventory_update') {
		$product_maintainstock 								= ($_REQUEST['product_maintainstock'])?1:0;
		$product_show_instock 								= ($_REQUEST['product_show_instock'])?1:0;
		$product_hide_preorder_msg  						= ($_REQUEST['product_hide_preorder_msg'])?1:0;
		$product_reorder_qty  								= $_REQUEST['product_reorder_qty'];
		$product_decrstock 									= ($_REQUEST['product_decrstock'])?1:0;
		$check_stock_management_before_checkout = ($_REQUEST['check_stock_management_before_checkout'])?1:0;
		if($product_maintainstock==0) {
			$product_decrstock 									 = 0; 
			$product_show_instock								 = 0;
			$check_stock_management_before_checkout = 0;
		}
		
		
		$update_array = array();
		$update_array['product_maintainstock']   								= $product_maintainstock;
		$update_array['product_show_instock']   								= $product_show_instock;
		$update_array['product_hide_preorder_msg']   							= $product_hide_preorder_msg ;
		$update_array['product_reorder_qty']   									= $product_reorder_qty ;

		$update_array['product_decrementstock']								= $product_decrstock;	
		$update_array['check_stock_management_before_checkout']   = $check_stock_management_before_checkout;
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$curtab='inventory_tab_td';
		clear_all_cache();// Clearing all cache
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$alert = '<center><b>Successfully Updated</b><br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/main_shop_ajax_functions.php");
		include("includes/general_settings/edit_settings_default.php");	
	
	}
elseif($_REQUEST['fpurpose'] == 'list_order') {
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/general_settings/ajax/list_order_ajax_functions.php");
	include("includes/general_settings/edit_settings_list_order.php");
}else if($_REQUEST['fpurpose'] == 'list_prodlisting_maininfo') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/list_order_ajax_functions.php");
	show_prod_listing($alert);
}
else if($_REQUEST['fpurpose'] == 'list_regcustomers_details') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/list_order_ajax_functions.php");
	show_regcustomers_listing($alert);
}
else if($_REQUEST['fpurpose'] == 'list_others_cat_shop') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include("../includes/general_settings/ajax/list_order_ajax_functions.php");
	show_others_listing($alert);
}
else if($_REQUEST['fpurpose'] == 'settings_prod_list_order_update') {
	if($_REQUEST['Submit']) {
	 $update_array = array();
	    		$update_array['product_orderfield_search']   			= $_REQUEST['product_orderfield_search'];
		$update_array['product_orderby_search']   					= $_REQUEST['product_orderby_search'];
		$update_array['product_maxcntperpage_search']   			= $_REQUEST['product_maxcntperpage_search'];
		$update_array['product_orderfield_bestseller']   				= $_REQUEST['product_orderfield_bestseller'];
		$update_array['product_maxcntperpage_bestseller']  		= $_REQUEST['product_maxcntperpage_bestseller'];
		$update_array['product_maxbestseller_in_component']  	= $_REQUEST['product_maxcnt_leftrightpanel'];
		$update_array['productshop_orderfield'] 						= $_REQUEST['productshop_orderfield'];	
		$update_array['productshop_orderby']   						= $_REQUEST['productshop_orderby'];	
		$update_array['product_maxcntperpage_shops']   			= $_REQUEST['product_maxcntperpage_shops'];
		$update_array['product_orderfield_shelf']   					= $_REQUEST['product_orderfield_shelf'];
		$update_array['product_orderby_shelf']   						= $_REQUEST['product_orderby_shelf'];
		$update_array['product_maxcntperpage_shelf']   			= $_REQUEST['product_maxcntperpage_shelf'];
		$update_array['product_maxshelfprod_in_component']  	= $_REQUEST['product_maxcnt_leftrightshelf'];
		$update_array['product_orderfield_preorder']  				= $_REQUEST['product_orderfield_preorder'];
		$update_array['product_orderby_preorder']   					= $_REQUEST['product_orderby_preorder'];
		$update_array['product_maxcntperpage_preorder']  		= $_REQUEST['product_maxcntperpage_preorder'];
		$update_array['product_preorder_in_component']   		= $_REQUEST['product_maxcnt_preorder_leftrightpanel'];
		$update_array['product_orderfield_combo']   					= $_REQUEST['product_orderfield_combo'];
		$update_array['product_orderby_combo']   					= $_REQUEST['product_orderby_combo'];
		$update_array['product_maxcntperpage']   					= $_REQUEST['product_maxcntperpage'];
		/*$update_array['product_orderfield_favorite']   				= $_REQUEST['product_orderfield_favorite'];
		$update_array['product_orderby_favorite']   					= $_REQUEST['product_orderby_favorite'];
		$update_array['product_maxcntperpage_favorite']   		= $_REQUEST['product_maxcntperpage_favorite'];*/
		$update_array['product_orderby_bestseller']   				= $_REQUEST['product_orderby_bestseller'];
        $update_array['product_orderfield']   							= $_REQUEST['product_orderfield'];
		$update_array['product_orderby']   								= $_REQUEST['product_orderby'];
		
		$productlist_maxval_hold												= (is_numeric($_REQUEST['productlist_maxval']))?$_REQUEST['productlist_maxval']:30;
		$productlist_interval_hold			   									= (is_numeric($_REQUEST['productlist_interval']))?$_REQUEST['productlist_interval']:3;
		if ($productlist_interval_hold<1)
			$productlist_interval_hold = 1;
		if($productlist_maxval_hold<$productlist_interval_hold)	
			$productlist_maxval_hold =$productlist_interval_hold;
		$update_array['productlist_maxval']   								= (is_numeric($productlist_maxval_hold))?$productlist_maxval_hold:30;
		$update_array['productlist_interval']   							= (is_numeric($productlist_interval_hold))?$productlist_interval_hold:3;
		
		
		$db->update_from_array($update_array, 'general_settings_sites_listorders','sites_site_id', $ecom_siteid);
		clear_all_cache();// Clearing all cache
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$alert 						= '<center> <b>Successfully Updated</b> <br>';
		$ajax_return_function 	= 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/list_order_ajax_functions.php");
		include("includes/general_settings/edit_settings_list_order.php");
	 }
	}
	else if($_REQUEST['fpurpose'] == 'settings_regcust_update') {
		$update_array = array();
				$update_array['enquiry_orderfield_settings']   			= $_REQUEST['enquiry_orderfield_settings'];
		$update_array['enquiry_orderby_settings']   					= $_REQUEST['enquiry_orderby_settings'];
		$update_array['enquiry_maxcntperpage']   				    	= $_REQUEST['enquiry_maxcntperpage'];
		$update_array['orders_orderfield_settings']   					= $_REQUEST['orders_orderfield_settings'];
		$update_array['orders_orderby_settings']   				    = $_REQUEST['orders_orderby_settings'];
		$update_array['orders_maxcntperpage']   				    	= $_REQUEST['orders_maxcntperpage'];
		$update_array['orders_orderfield_enquiry']   					= $_REQUEST['orders_orderfield_enquiry'];
		$update_array['orders_orderby_enquiry']   				   	 	= $_REQUEST['orders_orderby_enquiry'];
		$update_array['orders_maxcntperpage_enquiry']   			= $_REQUEST['orders_maxcntperpage_enquiry'];
		$update_array['orders_orderfield_enqposts']   				= $_REQUEST['orders_orderfield_enqposts'];
		$update_array['orders_orderby_enqposts']   			     	= $_REQUEST['orders_orderby_enqposts'];
		$update_array['orders_maxcntperpage_enqposts']   	 	= $_REQUEST['orders_maxcntperpage_enqposts'];
		$update_array['payon_maxcntperpage_statements']   	 	= $_REQUEST['payon_maxcntperpage_statements'];
		$update_array['product_limit_homepage_favcat_recent']	= ($_REQUEST['product_limit_homepage_favcat_recent']>0)?$_REQUEST['product_limit_homepage_favcat_recent']:0;
		$update_array['product_maxcnt_fav_category']        		= $_REQUEST['product_maxcnt_fav_category'];
		$update_array['product_maxcnt_recent_purchased']    	= $_REQUEST['product_maxcnt_recent_purchased'];
		
		$update_array['product_orderfield_favorite']   				= $_REQUEST['product_orderfield_favorite'];
		$update_array['product_orderby_favorite']   					= $_REQUEST['product_orderby_favorite'];
		$update_array['product_maxcntperpage_favorite']   		= $_REQUEST['product_maxcntperpage_favorite'];
		
		
		$db->update_from_array($update_array, 'general_settings_sites_listorders','sites_site_id', $ecom_siteid);
		clear_all_cache();// Clearing all cache
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$alert = '<center> <b>Successfully Updated</b> <br>';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/list_order_ajax_functions.php");
		$curtab = 'regcustomers_tab_td';
		include("includes/general_settings/edit_settings_list_order.php");
	}
	else if($_REQUEST['fpurpose'] == 'settings_others_catshop_update') {
		$update_array = array();
		$update_array['category_orderfield'] 					= $_REQUEST['category_orderfield'];	
		$update_array['category_orderby']   					= $_REQUEST['category_orderby'];	
		$update_array['shopbybrand_shops_orderfield'] 	= $_REQUEST['shopbybrand_shops_orderfield'];	
		$update_array['shopbybrand_shops_orderby']   	= $_REQUEST['shopbybrand_shops_orderby'];	
		$update_array['prodreview_ord_fld']   				= $_REQUEST['prodreview_ord_fld'];
		$update_array['prodreview_ord_orderby']   		= $_REQUEST['prodreview_ord_orderby'];
		$update_array['productreview_maxcntperpage'] 	= $_REQUEST['productreview_maxcntperpage'];
		$update_array['sitereview_ord_fld']   					= $_REQUEST['sitereview_ord_fld'];
		$update_array['sitereview_ord_orderby']   			= $_REQUEST['sitereview_ord_orderby'];
		$update_array['sitereview_maxcntperpage']   		= $_REQUEST['sitereview_maxcntperpage'];
		$update_array['saved_search_display_cnt']   			= $_REQUEST['saved_search_display_cnt'];
		$db->update_from_array($update_array, 'general_settings_sites_listorders','sites_site_id', $ecom_siteid);
		clear_all_cache();// Clearing all cache
		
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		
		$curtab 						= 'others_tab_td';
		$alert 						= '<center> <b>Successfully Updated</b> <br>';
		$ajax_return_function 	= 'ajax_return_contents';
		include "ajax/ajax.php";
		include("includes/general_settings/ajax/list_order_ajax_functions.php");
		include("includes/general_settings/edit_settings_list_order.php");
	}
elseif($_REQUEST['fpurpose'] == 'bonus_rate') {
	include("includes/general_settings/edit_settings_bonus_rate.php");
}else if($_REQUEST['fpurpose'] == 'settings_bonus_rate_update') {
	if($_REQUEST['Submit']) {
		
		$alert 					= '';
		$fieldRequired	 		= array();
		$fieldDescription 		= array();
		$fieldEmail 				= array();
		$fieldConfirm 			= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 			= array($_REQUEST['bonuspoint_rate']);
		$fieldNumericDesc 	= array('Enter numeric value');
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if($alert == '') {			
			$update_array 								= array();
			$update_array['bonuspoint_rate'] 			= $_REQUEST['bonuspoint_rate'];
			$update_array['minimum_bonuspoints'] 		= $_REQUEST['minimum_bonuspoints'];
			$update_array['cust_allowspendbonuspoints'] = ($_REQUEST['cust_allowspendbonuspoints'])?1:0;

			$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
			clear_all_cache();// Clearing all cache
		
			// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
			create_GeneralSettings_CacheFile();
			$alert = '<center> <b>Successfully Updated</b> <br>';
		}
		include("includes/general_settings/edit_settings_bonus_rate.php");
	}
}elseif($_REQUEST['fpurpose'] == 'orderconfirmemail') {
	include("includes/general_settings/edit_order_confirmemail.php");
}else if($_REQUEST['fpurpose'] == 'orderconfirmemail_update') {
	if($_REQUEST['Submit']) {
			$update_array 								= array();
			$update_array['order_confirmationmail'] 	= $_REQUEST['confirmemail1'].",".$_REQUEST['confirmemail2'].",".$_REQUEST['confirmemail3'].",".$_REQUEST['confirmemail4'].",".$_REQUEST['confirmemail5'];
			$update_array['order_despatch_additional_email']		= addslashes($_REQUEST['order_despatch_additional_email']);
			$update_array['pricepromise_toaddress']					= addslashes($_REQUEST['pricepromise_toaddress']);
			$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert = '<center> <b>Successfully Updated</b> <br>';
		clear_all_cache();// Clearing all cache
		// Creating the general settings cache files to be included in client area to save time to access the settings each time from db
		create_GeneralSettings_CacheFile();
		include("includes/general_settings/edit_order_confirmemail.php");
	}
}else if($_REQUEST['fpurpose'] == 'captions_delete') {
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Captions  not selected';
	}
	else
	{
		$del_arr = explode("~",$_REQUEST['del_ids']);
		foreach($del_arr as $key => $val)
		{
			$sql_delCaptions = "DELETE FROM general_settings_site_captions WHERE general_id = ".$val;
			$db->query($sql_delCaptions);
			$alert 				= '<center><b>Successfully Deleted</b></font><br>';
			clear_all_cache();// Clearing all cache
		}
		include("../includes/general_settings/list_settings_options.php");
	}
}
?>
