<?php
	/*#################################################################
	# Script Name 	: mainbody.php
	# Description 	: Page which decides the page to be shown in middle area
	# Coded by 		: Sny
	# Created on	: 03-Dec-2007
	# Modified by	: Sny
	# Modified On	: 22-Jan-2008
	#################################################################*/
	switch($_REQUEST['req'])
	{
		case 'prod_shelf': 		// ** Product Shelves
			include ("includes/base_files/shelf.php");
		break;
		case 'combo_deal': 		// ** Product Shelves
			include ("includes/base_files/combo.php");
		break;
		case 'best_sellers': 		// ** Best Sellers
			include ("includes/base_files/bestseller.php");
		break;
		case 'site_review': 	// ** Site Reviews
			include ("includes/base_files/sitereview.php");
		break;
		case 'prod_review': 	// ** Product Reviews
			include ("includes/base_files/productreview.php");
		break;
		case 'email_friend': 	// ** email a friend
			include ("includes/base_files/emailafriend.php");
		break;
		case 'registration': 	// ** Customer Registration related
			include ("includes/base_files/registration.php");
		break;
		case 'static_page': 	// ** Static page 
			include ("includes/base_files/staticpage.php");
		break;
		case 'sitemap': 		// ** Display Sitemap
			include ("includes/base_files/sitemap.php");
		break;
		case 'site_help': 		// ** Display Help
			include ("includes/base_files/help.php");
		break;
		case 'site_faq': 		// ** Display FAQ
			include ("includes/base_files/faq.php");
		break;
		case 'site_terms': 		// ** Display Terms and conditions
			include ("includes/base_files/terms.php");
		break;
		case 'callback': // request a call back
			include ("includes/base_files/callback.php");
		break;
		case 'survey': 		// ** Display Survey
			include ("includes/base_files/survey.php");
		break;
		case 'voucher': 		// ** Display Buy Gift voucher
			include ("includes/base_files/gift_voucher.php");
		break;
		case 'login_home': 	// ** customer login
			include ("includes/base_files/myhome.php");
		break;
		case 'compare_products': 	// ** Customer Profile - Edit
			include ("includes/base_files/compare_products.php");
		break;
		case 'myprofile': 	// ** Customer Profile - Edit
			include ("includes/base_files/myprofile.php");
		break;
		case 'myaddressbook': 	// ** Customer Address book - Add/Edit
			include ("includes/base_files/myaddressbook.php");
		break;
		case 'myfavorites': 	// ** Customer favorites - listing and remove
			include ("includes/base_files/myfavorites.php");
		break;
		case 'payonaccountdetails': // payonaccountdetails
			include ("includes/base_files/payonaccount.php");
		break;
		case 'categories': 		// ** Product Category Details
			include ("includes/base_files/categories.php");
		break;
		case 'prod_shop': 		// ** Product Shop
			include ("includes/base_files/shops.php");
		break;
		case 'preorder': 		// ** Product Shop
			include ("includes/base_files/preorder.php");
		break;
		case 'category_showall': 		// ** Product Shop
			include ("includes/base_files/favcategory_showall.php");
		break;
		case 'favprod_showall': 		// ** Product Shop
			include ("includes/base_files/favproducts_showall.php");
		break;
		case 'prod_detail':		// ** Product Details Page
			include ("includes/base_files/products.php");
		break;
		case 'search':			// ** Product Search
			include ("includes/base_files/search.php");
		break;
		case 'savedsearch':			// ** Product Search
			include ("includes/base_files/saved_search.php");
		break;
		case 'manage_product':
			include ("includes/base_files/manage_products.php");
		break;
		case 'cart':
			include ("includes/base_files/cart.php");
		break;
		case 'enquiry':
			include ("includes/base_files/enquiry.php");
		break;
		case 'wishlist':
			include ("includes/base_files/wishlist.php");
		break;
		case 'orders':
			include ("includes/base_files/myorders.php");
		break;
		case 'downloadable_prod': // downl links for downloadable products
			include ("includes/base_files/mydownloads.php");
		break;
		case 'showpurchaseall': // purchase all
			include ("includes/base_files/showpurchaseall.php");
		break;
		case 'pricepromise': // purchase all
			include ("includes/base_files/pricepromise.php");
		break;
		case 'prod_free_delivery': // purchase all
			include ("includes/base_files/product_free_delivery.php");
		break;
		case 'newsletter': // purchase all
			include ("includes/base_files/newsletter.php");
		break;
		//case 'orderdetails':
		//include ("includes/base_files/myorderdetail.php");
		//break;
		case '3dsecure':
			include ("3dsecure.php");
		break;
		case 'vsp_success': // protx vsp form success
			include ("vsporderSuccessful.php");
		break;
		case 'vsp_fail': // protx vsp form failed
				include ("vsporderFailed.php");
		break;
		case 'general_downloads': // general downloads
			include ("includes/base_files/general_downloads.php");
		break;
		case 'worldpay_return':
			include ("includes/base_files/worldpay_return.php");
		break;
		case 'realex_return':
			include ("includes/base_files/realex_return.php");
		break;
		case 'bulkdisc':
			include ("includes/base_files/bulkdiscount.php");
		break;
		case 'bonus_details':
			include ("includes/base_files/bonus_point_details.php");
		break;
		case 'verifyemail':
			include ("includes/base_files/verifyemail.php");
		break;
		case 'cartinter':
			include ("includes/base_files/cartintermediate.php");
		break;
		case 'error':
			include ("includes/base_files/error.php");
		break;
		case 'submit_review':
			include ("includes/base_files/submit_review.php");
		break;
		case 'common_message_display':
			include ("includes/base_files/common_message.php");
		break;
		case 'incomplete_order':
		include ("includes/base_files/incomplete_to_cart.php");
		break;
		case 'abandoned':
		include ("includes/base_files/abandoned_cart.php");
		break;
		case 'search_model':			// ** for search model ,make
			include ("includes/base_files/search_model.php");
		break;
		case 'propsearch':			// ** for search model ,make
			include ("includes/base_files/propsearch.php");
		break;		
		default:
			include ("themes/$ecom_themename/html/body.php");
		break;
	}
?>
