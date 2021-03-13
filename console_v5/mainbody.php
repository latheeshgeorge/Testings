<?php
//print_r($_POST);
// get the feature id for current value of request variable to see whether the current feature is active for current site
$bypass_array 	= array('logout','suggest','console_news','todo','seo_ga_details','ebay_category','events'); // array to hold the request values to be bypassed while doing the following checkings
$settings_array	= array(
							'general_settings_country',
							'general_settings_state',
							'general_settings_currency',
							'settings_letter_templates',
							'general_settings_price',
							'general_settings_tax',
							'general_settings_Gift_Wrap',
							'payment_types',
							'site_headers',
							'delivery_settings',
							'general_settings_comptype',
							'payment_capture_types',
							'product_reviews',
							'site_reviews',
							'settings_static_checkfields',
							'newsletter_customers',
							'order_tax_report',
							'login_track' ,
							'listof_console_useractions',
							'listquotes',
							'listofcallback'
							
						);
$hold_request 	= $_REQUEST['request'];// holding the original value of request variable
/*$templatecode = array(get_help_messages('LIST_NEWSLETTER_CODE_NAME_MESS1')=>'[Name]',
						get_help_messages('LIST_NEWSLETTER_CODE_EMAIL_MESS1')=>'[Email]',
						get_help_messages('LIST_NEWSLETTER_CODE_PRODUCT_MESS1')=>'[Products]',
						get_help_messages('LIST_NEWSLETTER_CODE_DATE_MESS1')=>'[date]');*/
$templatecode = array(get_help_messages('LIST_NEWSLETTER_CODE_PRODUCT_MESS1')=>'[Products]');						
						
$notifycode = array(get_help_messages('LIST_NOTIFICATION_CODE_NAME_MESS1')=>'[Name]',
						get_help_messages('LIST_NOTIFICATION_CODE_EMAIL_MESS1')=>'[Email]',
						get_help_messages('LIST_NOTIFICATION_CODE_PRODUCT_MESS1')=>'[NEWProducts]',
						get_help_messages('LIST_NOTIFICATION_CODE_DATE_MESS1')=>'[date]',
						get_help_messages('LIST_NOTIFICATION_CODE_DISCPROD_MESS1')=>'[DiscProducts]');


if(in_array($_REQUEST['request'],$settings_array))
{
	// check whether the current value of request is in settings_array. if yes then change the value of request variable
	// to general_settings and check whether general settings is avaiable for current site.
	$_REQUEST['request'] = 'general_settings';
}
if (!in_array($_REQUEST['request'],$bypass_array) and $_REQUEST['request'])
{
	$sql_option = "SELECT feature_id FROM features WHERE feature_option='".$_REQUEST['request']."' LIMIT 1";
	$ret_option = $db->query($sql_option);
	if ($db->num_rows($ret_option))
	{
		$row_option = $db->fetch_array($ret_option);
		// Check whether the current feature id is there in the current console level for current site
		$sql_level = "SELECT console_levels_level_id FROM console_levels_details WHERE console_levels_level_id=$ecom_levelid  and features_feature_id=".$row_option['feature_id']." LIMIT 1";
		$ret_level = $db->query($sql_level);
		if (!$db->num_rows($ret_level)){
			$_REQUEST['request'] = 'NoAuth';
			}elseif($_SESSION['user_type']=='su'){
				$sql_available_mod_menu = "SELECT menu_id FROM mod_menu WHERE  features_feature_id=".$row_option['feature_id']." AND sites_site_id =".$ecom_siteid;
				$ret_available_mod_menu = $db->query($sql_available_mod_menu);
				if ($db->num_rows($ret_available_mod_menu))
				{
					$row_available_mod_menu = $db->fetch_array($ret_available_mod_menu);
					$sql_chk_site_usr_permission =  "SELECT permission_id FROM site_user_permissions WHERE  sites_users_user_id=".$_SESSION['console_id']." AND sites_site_id =".$ecom_siteid." AND mod_menu_menu_id = ".$row_available_mod_menu['menu_id'];
					$ret_chk_site_usr_permission = $db->query($sql_chk_site_usr_permission);
					$user_allowed = $db->num_rows($ret_chk_site_usr_permission);
					if (!$user_allowed){
						 $_REQUEST['request'] = 'NoAuth';
					}
				}
				
			}
	}
	else
		 $_REQUEST['request'] = 'NoAuth';
}

if($_REQUEST['request'] != 'NoAuth'){
	$_REQUEST['request'] = $hold_request; // writing back the original value of request variable
}

if(check_show_special_no_auth_msg())
{
	$_REQUEST['request'] = 'NoAuth';
}

	

//#Including pages based on the request
switch ($_REQUEST['request']){
	case 'console_user':
		include("services/consoleuser.php");
	break;
	case 'account': //# Admin Options
		include("services/profile.php");
	break;
	case 'suggest': 
		include("services/suggest.php");
	break;
	case 'general_settings': //# General Settings
		include("services/general_settings.php");
	break;
	case 'general_settings_country': //# General Settings
		include("services/settings_countries.php");
	break;
	case 'general_settings_state': //# General Settings
		include("services/settings_state.php");
	break;
	case 'general_settings_currency': //# General Settings
		include("services/settings_currency.php");
	break;
	case 'general_settings_price': //# General Settings Price Display
		include("services/settings_pricedisplay.php");
	break;
	case 'general_settings_tax': //# General Settings Price Display
		include("services/settings_tax.php");
	break;
	case 'general_settings_Gift_Wrap': //# General Settings Gift Wrap
		include("services/settings_gift_wrap.php");
	break;
	case 'payment_types': //# Payment Types
		include("services/payment_types.php");
	break;
	case 'payment_capture_types':
	     include("services/payment_capture_types.php");
	break;
	case 'settings_letter_templates': //# General Settings
		include("services/settings_letter_templates.php");
	break;
	case 'settings_default': //# General Settings
		include("services/settings_default.php");
	break;
	case 'prod_cat_group': // Product Category groups
		include("services/product_category_groups.php");
	break;
	case 'prod_cat': // Product Categories
		include("services/product_category.php");
	break;
	case 'contest': 
		include("services/contests.php");
	break;
	case 'prod_labels': // Product Labels
		include("services/product_labels.php");
	break;
	case 'prod_label_groups': // Product Label Groups
		include("services/product_label_groups.php");
	break;
	case 'sizechart': // Product SSize Chart Headings
		include("services/sizechart.php");
	break;
	case 'prod_vendor': // Product Vendors
		include("services/prod_vendor.php");
	break;
	case 'products': // Products
		include("services/products.php");
	break;
	case 'preorder': // Products
		include("services/preorder.php");
	break;
    case 'stat_group': // Static page groups
		include("services/static_group.php");
	break;
	case 'stat_page': // Product Vendors
		include("services/static_page.php");
	break;
	case 'adverts': // Adverts
		include("services/adverts.php");
	break;
	case 'customform': // Dynamic Form
		include("services/custom_form.php");
	break;
	case 'featured': // Featured products
		include("services/featured_product.php");
	break;
	case 'shelfs':
		include("services/shelf.php");	
	break;
	case 'shelfgroup':
		include("services/shelfgroup.php");
	break;
	case 'img_gal': // Image gallery
		include("services/image_gallery.php");
	break;
	case 'giftwrap_bows': // Giftwrap Bows
		include("services/giftwrap_bow.php");
	break;
	case 'giftwrap_cards': // Giftwrap Cards
		include("services/giftwrap_card.php");
	break;
	case 'giftwrap_papers': // Giftwrap Papers
		include("services/giftwrap_paper.php");
	break;
	case 'giftwrap_ribbons': // Giftwrap Ribbons
		include("services/giftwrap_ribbon.php");
	break;
	case 'combo': // Combo
		include("services/combo.php");
	break;
	case 'comp_pos': // Component positions
		include("services/component_positions.php");
	break;
	case 'gift_voucher': // Gift Vouchers
		include("services/gift_voucher.php");
	break;
	case 'buy_voucher': // Gift Vouchers
		include("services/buy_gift_voucher.php");
	break;
	case 'spend_voucher': // Gift Vouchers
		include("services/spend_gift_voucher.php");
	break;
	case 'site_headers': // Site header images
		include("services/site_headers.php");
	break;
	case 'delivery_settings': // Delivery settings
		include("services/delivery_settings.php");
	break;
	case 'cust_group': // Customer Newsletter group
		include("services/customer_newsletter_group.php");
	break;
	case 'cust_discount_group': // Customer Discount group
		include("services/cust_discount_group.php");
	break;
	case 'survey': // Survey
		include("services/survey.php");
	break;
	case 'product_reviews': // Product Reviews
		include("services/product_reviews.php");
	break;
	case 'site_reviews': // Site Reviews
		include("services/site_reviews.php");
	break;
	case 'customer': // Customer
		include("services/customer.php");
	break;	
	case 'customer_corporation': // Customer
		include("services/customer_corporation.php");
	break;	
	case 'customer_search': // Customer Search
		include("services/customer_search.php");
	break;
	case 'newsletter_customers': // Listing of news letter customers 
		include("services/newsletter_customers.php");
	break;
	case 'newsletter': // News Letter
		include("services/newsletter.php");
	break;	
	case 'fraud_check': // News Letter
		include("services/fraud_check.php");
	break;
	case 'callback': //Callbacks
		include("services/callback.php");
	break;
	case 'cpc': //Callbacks
		include("services/cpc.php");
	break;
	case 'seo_title': // Seo Title
		include("services/seo_title.php");
	break;
	case 'seo_keyword': // Seo Keyword
		include("services/seo_keyword.php");
	break;	
	case 'seo_gadetails': // Seo Google analytics details
		include("services/seo_gadetails.php");
	break;	
	case 'seo_meta_description': // Seo Meta descritpion
		include("services/seo_meta_description.php");
	break;
	case 'product_enquire': // Product enquiries
		include("services/product_enquire.php");
	break;	
	case 'prom_code': // Promotional codes
		include("services/promotional_code.php");
	break;
	case 'NoAuth':
		include("services/noauth.php");
	break;
	case 'general_settings_comptype':
		include("services/comptype.php");
	break;
	case 'shopbybrandgroup': // shop by brand groups
		include("services/shopbybrandgroup.php");
	break;
	case 'shopbybrand'://shop by brand
		include("services/shopbybrand.php");
	break;
	case 'googlebase_export':
		include("services/googlebase_export.php");
	break;
	case 'amazon_export':
		include("services/amazon_export.php");
	break;
	case 'import_export':
		include("services/import_export.php");
	break;
	case 'product_stores':
		include("services/product_store.php");
	break;
	case 'newsletter_templates':
		include("services/newsletter_templates.php");
	break;
	case 'settings_static_checkfields':
		include("services/static_checkoutfields.php");
	break;
	case 'orders': // orders section
		include("services/orders.php");
	break;
	case 'order_archive': // orders archive section
		include("services/orders_archive.php");
	break;
	case 'order_enquiries': 
	// orders section
		include("services/order_enquire.php");
	break;
	case 'console_news': 
		include("services/console_news.php");
	break;
	case 'instock_notify':
		include("services/instock_notify.php");
	break;
	case 'database_offline':  // Manage database offline
		include("services/database_offline.php");
	break; 
	case 'advanced_offline': // Manage stock offline
		include("services/advanced_offline.php");
	break;
	case 'bestseller':  // Manage database offline
		include("services/bestseller.php");
	break;
	case 'delivery_settings_more':  // Manage delivery settings more
		include("services/delivery_settings_more.php");
	break;
	case 'tax_settings':  // Manage delivery settings more
		include("services/tax_settings.php");
	break;
	case 'image_setings':  // Manage delivery settings more
		include("services/image_setings.php");
	break;
	case 'email_notify':  // Manage delivery email notification
		include("services/email_notify.php");
	break;
	case 'payonaccount':  // Manage delivery settings more
		include("services/payonaccount.php");
	break;
	case 'payonaccount_pending':
		include("services/payonaccount_pending.php");
	break;	
	
	case 'costperclick_adverts':
		include("services/costperclick_adverts.php");
	break;	
	case 'costperclick_keyword':
		include("services/costperclick_keyword.php");
	break;	
	case 'costperclick_report':
		include("services/costperclick_report.php");
	break;	
	case 'costperclick_urls':
		include("services/costperclick_urls.php");
	break;	
	case 'faq':
		include("services/faq.php");
	break;	
	case 'help':
		include("services/help.php");
	break;	
	case 'price_promise_content':
		include("services/price_promise_content.php");
	break;
	case 'price_promise':
		include("services/pricepromise_enquiries.php");
	break;
	case 'product_free_delivery_content':
		include("services/product_free_delivery_content.php");
	break;
	case 'bonus_point_details':
		include("services/bonus_point_details_content.php");
	break;
	case 'general_commoncontent_details':
		include("services/general_commoncontent_details.php");
	break;
	case 'payon_account_content':
		include("services/payon_account_content.php");
	break;
	case 'autolinker':
		include("services/autolinker.php");
	break;
	case 'general_downloads':
		include("services/general_downloads.php");
	break;
	case 'general_downloads_topcontent':
		include("services/general_downloads_content.php");
	break;
	case 'kmlsitemap':
		include("services/kmlsitemap.php");
	break;
	case 'preset_var':
		include("services/preset_variable.php");
	break;
	case 'general_shopsall_content':
		include("services/general_shopsall_content.php");
	break;
	case 'colorcodes':
			include("services/colors.php");
	break;
	case 'newsletter_prod_layout':
			  include("services/newsletter_default_product_layout.php");
	break;
	case 'common_prod_attachment':
			include("services/common_product_attachment.php");
	break;
	case 'common_prod_tab':
			include("services/common_product_tab.php");
	break;
	case 'order_tax_report':
			include("services/order_tax_report.php");
	break;
	case 'mob_comp_pos': // Component positions
		include("services/mobile_component_positions.php");
	break;
	case 'seo_301redirect': // Component positions
		include("services/seo_301redirect.php");
	break;
	case 'nextag_export':
		include("services/export_nextag_data.php");
	break;
	case 'todo':
		include("services/todolist.php");
	break;
	case 'seo_ga_details':
		include("services/seo_ga_details.php");
	break;
	case 'facebook_tab':
		include("services/facebook_tab_content.php");
	break;
	case 'ebay_export':
		include("services/ebay_export.php");
	break;
	case 'ebay_category':
		include("services/ebay_category.php");
	break;
	case 'product_variable_group':
		include("services/product_variable_group.php");
	break;
	case 'events': // Events section
		include("services/events.php");
	break;
	case 'login_track':
		include("services/login_track.php");
	break;
	case 'listof_console_useractions':
		include("services/listof_console_useractions.php");
	break;
	case 'listquotes':
		include("services/listof_quotes.php");
	break;
	case 'listofcallback':
		include("services/list_callback.php");
	break;
	default:
		include("default.php");
	break;
}
?>
