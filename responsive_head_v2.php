<?php 
	/*#################################################################
	# Script Name 	: mobileresponsive_head.php
	# Description 	: Page builds the information to be shown at the Head section of html.
	# Coded by 		: LSH
	# Created on	: 28-Jan-2016
	# Modified by	: 
	#################################################################*/
	
	if(check_IndividualSslActive())
	{
		$http = "https://";
	}	
	else
	{
		$http = "http://";
	}	
	
	$kw_limit	= 5; // limit which will be used in the keyword sql queries
	// ##########################################################################################################################
	// If product id is there and category_id is not there then get the default category id for the product
	// ##########################################################################################################################
	if($_REQUEST['product_id'] && !$_REQUEST['category_id'])
	{
		/*// Case if product id is there and category_id is not there. Then pick the default category id for the product
		$sql	= "SELECT 
						product_default_category_id 
					FROM 
						products 
					WHERE 
						product_id=".$_REQUEST['product_id']." 
					LIMIT 
						1";
		$ret 	= $db->query($sql);
		if ($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);*/
			$_REQUEST['category_id'] = $row_outerprods['product_default_category_id'];
		/*}*/
	}	
	
	$cat_id 	= $_REQUEST['category_id'];
	$prod_id	= $_REQUEST['product_id'];
	$pg_id		= $_REQUEST['page_id'];
	$meta_arr	= $homemeta_arr = array();
	
	// ##########################################################################################################################
	// Section which check whether the id's passed via url are numeric or not. if not numeric then set it to 0 and call the invalid input page
	// This is done to void the case of hacking by injecting values of query string.
	// ##########################################################################################################################

	$input_arr = array (
							$catid,
							$prod_id,
							$pg_id,
							$_REQUEST['search_id'],
							$_REQUEST['combo_id'],
							$_REQUEST['catgroup_id'],
							$_REQUEST['shelf_id'],
							$_REQUEST['shopgroup_id'],
							$_REQUEST['shop_id'],
							$_REQUEST['det_image_id'],
							$_REQUEST['tab_id'],
							$_REQUEST['fav_id'],
							$_REQUEST['prodimgdet'],
							$_REQUEST['prod_curtab'],
							$_REQUEST['start_key']
						);	
	if(!is_InputValid($input_arr))
		displayInvalidInput();
	
	$canonical = '';
	if($_REQUEST['page_id'])
	{
		$sql_pgs = "SELECT title 
						FROM 
							static_pages 
						WHERE 
							page_id = '".$_REQUEST['page_id']."'
						LIMIT 
							1";
		$ret_pgs = $db->query($sql_pgs);
		if($db->num_rows($ret_pgs))
		{
			$row_pgs = $db->fetch_array($ret_pgs);
			$cann_arr = url_static_page($_REQUEST['page_id'],$row_pgs['title'],1);
			$canonical = $cann_arr[1];
		}	
	}
	elseif($_REQUEST['product_id'])
	{
		$sql_pgs = "SELECT product_name  
						FROM 
							products 
						WHERE 
							product_id = '".$_REQUEST['product_id']."'
						LIMIT 
							1";
		$ret_pgs = $db->query($sql_pgs);
		if($db->num_rows($ret_pgs))
		{
			$row_pgs = $db->fetch_array($ret_pgs);
			$cann_arr = url_product($_REQUEST['product_id'],$row_pgs['product_name'],1);
			$canonical = $cann_arr;
		}	
	}
	elseif($_REQUEST['category_id'])
	{
		$sql_pgs = "SELECT category_name  
						FROM 
							product_categories 
						WHERE 
							category_id = '".$_REQUEST['category_id']."'
						LIMIT 
							1";
		$ret_pgs = $db->query($sql_pgs);
		if($db->num_rows($ret_pgs))
		{
			$row_pgs = $db->fetch_array($ret_pgs);
			$cann_arr = url_category($_REQUEST['category_id'],$row_pgs['category_name'],1);
			$canonical = $cann_arr;
		}	
	}
	
	if($_REQUEST['search_id'])
	{
		$sql_pgs = "SELECT search_keyword  
						FROM 
							saved_search  
						WHERE 
							search_id = '".$_REQUEST['search_id']."'
						LIMIT 
							1";
		$ret_pgs = $db->query($sql_pgs);
		if($db->num_rows($ret_pgs))
		{
			$row_pgs = $db->fetch_array($ret_pgs);
			$cann_arr = url_savedsearch($_REQUEST['search_id'],$row_pgs['search_keyword'],1);
			$canonical = $cann_arr;
		}
	}
	
	
	
	// ##########################################################################################################################	
	// Finding the page id for home page and assign it to $_REQUEST['page_id'] to handle the case of showing various
	// components which are assigned to home page
	// ##########################################################################################################################
	if($_REQUEST['req']=='')
	{
		$sql_pid = "SELECT page_id 
						FROM 
							static_pages 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND pname ='Home' 
						LIMIT 
							1";
		$ret_pid = $db->query($sql_pid);
		if($db->num_rows($ret_pid))
		{
			$row_pid 				= $db->fetch_array($ret_pid);
			$_REQUEST['page_id'] 	= $row_pid['page_id'];
		}
	}

	// ##########################################################################################################################
	// Section which picks the title and meta related details for static pages, products and categories
	// ##########################################################################################################################
	if($pg_id and is_numeric($pg_id))/*// Case if page id exists*/
	{
		$meta_arr = get_PageMetaDetails('STATIC_PAGE',$kw_limit,$row_metatemplate,array('page_id'=>$pg_id));
	}
	elseif ($prod_id and is_numeric($prod_id)) // Case if product id exists
	{
		//print_r($_REQUEST);
		if($_REQUEST['req']=='prod_review')
		{
			if ($_REQUEST['action_purpose']=='writeprodreview')
			{
				$meta_arr = get_PageMetaDetails('PRODUCTREVIEW_WRITE_PAGE',$kw_limit,$row_metatemplate,array('product_id'=>$prod_id));	
			}
			else // case of readreview page
			{
				$meta_arr = get_PageMetaDetails('PRODUCTREVIEW_READ_PAGE',$kw_limit,$row_metatemplate,array('product_id'=>$prod_id));	
			}
		}
		elseif($_REQUEST['req']=='email_friend')
		{
			$meta_arr = get_PageMetaDetails('PRODUCT_EMAILFRIEND_PAGE',$kw_limit,$row_metatemplate,array('product_id'=>$prod_id));	
		}
		else // case of normal product page
		{
			$meta_arr = get_PageMetaDetails('PRODUCT_PAGE',$kw_limit,$row_metatemplate,array('product_id'=>$prod_id));	
		}
		if($_REQUEST['req']=='prod_review' or $_REQUEST['req']=='email_friend')
		{
			//echo "here1";
			//if($meta_arr['title']=='' or strtolower($meta_arr['title'])==' | '.strtolower($ecom_title) or strtolower($meta_arr['title'])==' | ')
			if($meta_arr['title']=='' or strtolower($meta_arr['title'])==' | '.strtolower($ecom_hostname))
			{
					//echo "here2";
				$meta_arr = get_PageMetaDetails('PRODUCT_PAGE',$kw_limit,$row_metatemplate,array('product_id'=>$prod_id));	
			}	
		}
			
	}
	elseif ($cat_id and is_numeric($cat_id)) // Case if category id exists
	{
		$meta_arr = get_PageMetaDetails('CATEGORY_PAGE',$kw_limit,$row_metatemplate,array('category_id'=>$cat_id));		
	}
	// ##########################################################################################################################
	// Section which picks the title and meta related details to saved search result pages
	// ##########################################################################################################################
	// case of coming from the saved search page so user the search id to find the keyword stored in the saved search table
	
	if(!$_REQUEST['quick_search'] && $_REQUEST['search_id']) 
	{
		$sql_search_kw 				= "SELECT 
													search_keyword 
												FROM 
													saved_search 
												WHERE 
													sites_site_id=$ecom_siteid 
													AND search_id=".$_REQUEST['search_id']." 
												LIMIT 
													1";
		$res_search_kw 				= $db->query($sql_search_kw);
		list($kw) 					= $db->fetch_array($res_search_kw);
		
		$_REQUEST['quick_search'] 	= $kw;
		$search_id					= $_REQUEST['search_id'];
		if($search_id)
		{
			$meta_arr = get_PageMetaDetails('SAVED_SEARCH',$kw_limit,$row_metatemplate,array('search_id'=>$search_id,'sr_kw'=>$kw));	
		}
	}
	elseif($_REQUEST['quick_search'] and !$_REQUEST['search_id'])
	{
		$_REQUEST['quick_search'] = str_replace("+"," ",trim($_REQUEST['quick_search']));
		//$_REQUEST['quick_search'] = preg_replace("/[^0-9a-zA-Z-\s]+/", "", $_REQUEST['quick_search']);
		$_REQUEST['quick_search'] = preg_replace("/[^0-9a-zA-Z-\s.\/&\[\]{}|#@*!,-]+/", "", $_REQUEST['quick_search']);
		// Check whether this keyword already in our saved search list. If exists try to pick the keywords and titles assigned for it
		$sql_search_id 				= "SELECT 
											search_id  
										FROM 
											saved_search 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND search_keyword='".$_REQUEST['quick_search']."' 
										LIMIT 
											1";
		$res_search_id				= $db->query($sql_search_id);
		if ($db->num_rows($res_search_id))
		{
			$row_search_id = $db->fetch_array($res_search_id);
			//$_REQUEST['quick_search'] = $row_search_id['search_id'];
			$meta_arr = get_PageMetaDetails('SAVED_SEARCH',$kw_limit,$row_metatemplate,array('search_id'=>$row_search_id['search_id'],'sr_kw'=>$_REQUEST['quick_search']));
		}	
		else 
		{
			$meta_arr = get_PageMetaDetails('SEARCH',$kw_limit,$row_metatemplate,array('sr_kw'=>$_REQUEST['quick_search']));	
		}	
	}
	elseif($_REQUEST['quick_search'] and $_REQUEST['search_id'])
	{
		$_REQUEST['quick_search'] = str_replace("+"," ",trim($_REQUEST['quick_search']));
		$_REQUEST['quick_search'] = preg_replace("/[^0-9a-zA-Z-\s.\/&\[\]{}|#@*!,-]+/", "", $_REQUEST['quick_search']);
		// Check whether this keyword already in our saved search list. If exists try to pick the keywords and titles assigned for it
		$sql_search_id 				= "SELECT 
											search_id  
										FROM 
											saved_search 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND search_keyword='".$_REQUEST['quick_search']."' 
										LIMIT 
											1";
		$res_search_id				= $db->query($sql_search_id);
		if ($db->num_rows($res_search_id))
		{
			$row_search_id = $db->fetch_array($res_search_id);
			$meta_arr = get_PageMetaDetails('SAVED_SEARCH',$kw_limit,$row_metatemplate,array('search_id'=>$row_search_id['search_id'],'sr_kw'=>$_REQUEST['quick_search']));
		}	
		else 
		{
			$meta_arr = get_PageMetaDetails('SEARCH',$kw_limit,$row_metatemplate,array('sr_kw'=>$_REQUEST['quick_search']));	
		}	
	}
	$site_title = ucwords(strtolower($ecom_title));
	
	// ###############################################################################################################################
	// Following section makes the decision regarding the title, keywords and metadescripts to be used for the currently viewing page
	// ###############################################################################################################################
	switch($_REQUEST['req'])
	{
		case 'prod_shelf': 
			$meta_arr = get_PageMetaDetails('PRODUCT_SHELF',$kw_limit,$row_metatemplate,array('shelf_id'=>$_REQUEST['shelf_id']));
		break;
		
		case 'combo_deal': 		// ** Combo deals
			if($_REQUEST['combo_id'])
				$meta_arr = get_PageMetaDetails('COMBO_DEALS',$kw_limit,$row_metatemplate,array('combo_id'=>$_REQUEST['combo_id']));
		break;
		
		case 'best_sellers': 		// ** Best Sellers
			$meta_arr = get_PageMetaDetails('BEST_SELLER',$kw_limit,$row_metatemplate);
		break;
		
		case 'site_review': 	// ** Site Reviews
			$meta_arr = get_PageMetaDetails('SITE_REVIEW',$kw_limit,$row_metatemplate);
		break;
		
		case 'registration': 	// ** Customer Registration related
			switch($_REQUEST['action_purpose'])
			{
				case 'ForgotPassword':	// case of password forgot page
					$meta_arr = get_PageMetaDetails('FORGOT_PASSWORD',$kw_limit,$row_metatemplate);
				break;
				default: // case of registration
					$meta_arr = get_PageMetaDetails('CUSTOMER_REGISTRATION',$kw_limit,$row_metatemplate);
				break;
			};
		break;
		
		case 'sitemap': 		// ** Display Sitemap
			$meta_arr = get_PageMetaDetails('SITE_MAP',$kw_limit,$row_metatemplate);
		break;
		
		case 'site_help': 		// ** Display Help
			$meta_arr = get_PageMetaDetails('SITE_HELP',$kw_limit,$row_metatemplate);
		break;
		
		case 'site_faq': 		// ** Display faq
			$meta_arr = get_PageMetaDetails('SITE_FAQ',$kw_limit,$row_metatemplate);
		break;
		
		case 'prod_shop': // Product shops
		    if($_REQUEST['shop_id'])
			$meta_arr = get_PageMetaDetails('PRODUCT_SHOP',$kw_limit,$row_metatemplate,array('shop_id'=>$_REQUEST['shop_id']));
		break;

		case 'savedsearch':			// ** saved Search main page
			$meta_arr = get_PageMetaDetails('SAVED_SEARCH_MAIN',$kw_limit,$row_metatemplate);
		break;
		
		// ###############################################################################################################################
		// Following section assign the title and keyword statically
		// ###############################################################################################################################
		
		case 'search':
			if($_REQUEST['search_src']=='advanced')
			{
				$meta_arr['title']			= $site_title.' - Advanced Search';
				$meta_arr['keywords']	= $homemeta_arr['keywords'];	
				$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
			}
			/*elseif($_REQUEST['search_src']!='advanced' and !$_REQUEST['search_id']) // if not advanced search
			{
				$meta_arr = get_PageMetaDetails('SEARCH',$kw_limit,$row_metatemplate,array('sr_kw'=>$_REQUEST['quick_search']));	
			}*/
		break;
		case 'site_terms': 		// ** Display Terms and conditions
			$meta_arr['title']		= $site_title.' - Terms And Conditions';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];	
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'callback': // request a call back
			$meta_arr['title']		= $site_title.' - Callback';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];	
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'survey': 		// ** Display Survey
			$meta_arr['title']		= $site_title.' - Survey';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];	
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'voucher': 		// ** Display Buy Gift voucher
			$meta_arr['title']		= $site_title.' - Gift Vouchers';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'login_home': 	// ** customer login
			$meta_arr['title']			= $site_title.' - Welcome';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];	
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'compare_products': 	// ** Customer Profile - Edit
			$meta_arr['title']		= $site_title.' - Compare Products';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];	
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'myprofile': 	// ** Customer Profile - Edit
			$meta_arr['title']		= $site_title.' - My Profile';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];		
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'myaddressbook': 	// ** Customer Address book - Add/Edit
			$meta_arr['title']		= $site_title.' - My Address Book';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];		
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'myfavorites': 	// ** Customer favorites - listing and remove
			$meta_arr['title']		= $site_title.' - My Favourite Categories & Products';		
			$meta_arr['desc']		= $homemeta_arr['desc'];
			$meta_arr['keywords']	= $homemeta_arr['keywords'];		
		break;
		case 'downloadable_prod': 	// ** Downloadable products
			$meta_arr['title']		= $site_title.' - My Downloads';		
			$meta_arr['keywords']	= $homemeta_arr['keywords'];		
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		case 'cart':
			switch($_REQUEST['cart_mod'])
			{
				case 'clear_cart':
				case 'show_cart':
					$meta_arr['title']		= $site_title.' - View Cart';
				break;
				case 'show_checkout':
					$meta_arr['title']		= $site_title.' - Checkout';
				break;
				case 'show_checkoutsuccess':
					$meta_arr['title']		= $site_title.' - Checkout Successfull';
				break;
				case 'show_checkoutfailed':
					$meta_arr['title']		= $site_title.' - Checkout Failed';
				break;
                                case 'show_orderplace_preview':
                                        $meta_arr['title']              = $site_title.' - Checkout Preview';
                                break;
			};
			//$meta_arr['desc']		= $homemeta_arr['desc'];
			$meta_arr['keywords']	= $homemeta_arr['keywords'];			
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'enquiry':
			switch($_REQUEST['enq_mod'])
			{
				case 'show_enquiry':
					$meta_arr['title']		= $site_title.' - Product Enquiry';
				break;
				case 'show_enquiryform':
					$meta_arr['title']		= $site_title.' - Fill Product Enquiry Details';
				break;
				case 'disp_result':
					$meta_arr['title']		= $site_title.' - Product Enquiry Submitted';
				break;
				case 'list_enquiries':
					$meta_arr['title']		= $site_title.' - My Enquiries';
				break;
			};
			//$meta_arr['desc']		= $homemeta_arr['desc'];
			$meta_arr['keywords']	= $homemeta_arr['keywords'];
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'wishlist':
			$meta_arr['title']		= $site_title.' - My Wishlist';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
		
		case 'orders':
			$meta_arr['title']		= $site_title.' - My Orders';
			$meta_arr['keywords']	= $homemeta_arr['keywords'];
			$meta_arr['desc']		= get_othermetadesc($row_metatemplate['other_meta'],$homemeta_arr['desc'],$meta_arr['keywords']);
		break;
	
	};
	if(count($meta_arr)==0) // Done to handle the situation of meta detail not obtained from any of the above section. So take the meta details of home page
		$meta_arr = get_PageMetaDetails('HOME',$kw_limit,$row_metatemplate);;
		
	if (count($meta_arr['keywords']))
		$keywords = implode(', ',$meta_arr['keywords']);
	$head_keywords = $keywords;
	if($ecom_siteid==113 and $_REQUEST['category_id']) // only for Discount mobility
	{
		$ecom_hostnamecheck = ucwords($ecom_hostname);
		$meta_arr['title'] = str_replace($ecom_hostnamecheck,'',$meta_arr['title']);
			
		if(substr($meta_arr['title'],-2)=='| ')
		{	
			$meta_arr['title'] = substr($meta_arr['title'],0,strlen($meta_arr['title'])-2);
		}
	}
     echo 
            "<title>".stripslashes($meta_arr['title'])."</title>
<meta charset=\"UTF-8\" />
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />         
		<meta name=\"description\" content=\"".trim($meta_arr['desc'])."\" />
            <meta name=\"keywords\" content=\"".$keywords."\" />
            <meta name=\"robots\" content=\"noodp\" />";
if($ecom_ismetacode==1 && $ecom_metacode)
{
	$met_exp = explode(",",$ecom_metacode);
	for($i=0;$i<count($met_exp);$i++)
		echo '<meta name="google-site-verification" content="'.trim($met_exp[$i]).'" />';
}

if($ecom_isyahoometacode==1 && trim($ecom_yahoometacode))
{
	$met_exp = explode(",",$ecom_yahoometacode);
	for($i=0;$i<count($met_exp);$i++)
		echo '<meta name="y_key" content="'.trim($met_exp[$i]).'" />';
}
if($ecom_ismsnmetacode==1 && trim($ecom_msnmetacode))
{
	$met_exp = explode(",",$ecom_msnmetacode);
	for($i=0;$i<count($met_exp);$i++)
		echo '<meta name="msvalidate.01" content="'.trim($met_exp[$i]).'" />';
}
    $script_ver = "ver.2.0";
     echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/bootstrap.min.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
	//echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\" />";
    	//echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/bootstrap.min.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
    //commented by minu
        echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery-3.2.1.slim.min.js?ver=".$script_ver."",1)."\"></script>";
        echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/popper.min.js?ver=".$script_ver."",1)."\"></script>";

    ?>
   <?php /* <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>*/ ?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

    <?php
    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.js?ver=".$script_ver."",1)."\"></script>";
    
    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery-ui.min.js?ver=".$script_ver."",1)."\"></script>";
    echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/reset.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
	echo "<link href=\"https://fonts.googleapis.com/css?family=Oswald\" rel=\"stylesheet\">";
	echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/".$ecom_themename.".css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
 	echo "<link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.4.1/css/all.css\" >";
    	//echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/all.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
	echo "<link href=\"https://fonts.googleapis.com/css?family=Oswald\" rel=\"stylesheet\">";
	 echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/aos.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";

	//echo "<link href=\"https://unpkg.com/aos@2.3.1/dist/aos.css\" rel=\"stylesheet\">"; 
	    	     
echo "<link rel=\"shortcut icon\" href=\"".url_head_link("images/".$ecom_hostname."/".$site_images_folder."/favicon.ico",1)."\"/>";
            
	 echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/aos.js?ver=".$script_ver."",1)."\"></script>";

	 echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/modernizr.js?ver=".$script_ver."",1)."\"></script>";
     echo "<script type=\"text/javascript\" src=\"".url_head_link("scripts/validation.js?ver=".$script_ver."",1)."\"></script>";
     
     echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/javascript.js?ver=".$script_ver."",1)."\"></script>";
	
	
	if($_REQUEST['req'] == "prod_detail" and $_REQUEST['product_id']) // case of product details page
	{
	?>
		<link rel="canonical" href="<?php url_product($_REQUEST['product_id'],$row_outerprods['product_name'],-1)?>"/>
	<?php
	}
	elseif(!$_REQUEST['product_id'] && $_REQUEST['category_id']) // case of category pages
	{
	?>
		<link rel="canonical" href="<?php url_category($_REQUEST['category_id'],$row_outercats['category_name'],-1)?>"/>
	<?php	
	}
	elseif($_REQUEST['search_id'] && $canonical!='') // case of category pages
	{
	?>
		<link rel="canonical" href="<?php echo $canonical?>"/>
	<?php	
	}
	
	if($_REQUEST['req'] == '') // case of home page
	{
		echo "<link rel=\"canonical\" href=\"".$http.$ecom_hostname."\" />";
	}
	
    if($_SERVER['REQUEST_URI'] =='/index.php' and $_REQUEST['req']=='')
    {
        echo "<link rel=\"canonical\" href=\"".$http.$ecom_hostname."\" />";
    }
	// Including the general settings cache file 
	if(file_exists($image_path.'/settings_cache/general_settings.php'))
	{
		include "$image_path/settings_cache/general_settings.php";
	}	
	
	//if($ecom_siteid==104 and $_REQUEST['product_id']==534854)
	if($_REQUEST['product_id'])
	{
		$prodimg_arr = get_imagelist('prod',$_REQUEST['product_id'],'image_bigcategorypath',0,0,1);
		if(count($prodimg_arr))
		{
	?>
			<meta property="og:image" content="<?php echo $http.$ecom_hostname?>/images/<?php echo $ecom_hostname?>/<?php echo $prodimg_arr[0]['image_extralargepath']?>" /> 
	<?php
		}
	}	
	
	/* Check settings for search auto complete product list ends here */
	if($ecom_iswebtracker)
	{
		$track_arr = explode(',',$ecom_webtrackercode);
		foreach ($track_arr as $kkey=>$vval)
		{
			$more_track = '';			
			echo '<script type="text/javascript">
					var _gaq = _gaq || [];
					_gaq.push([\'_setAccount\', \''.$vval.'\']);
					'.$more_track.'
					_gaq.push([\'_trackPageview\']);
				  (function()
					{
						var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
						ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
						var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
					})();
			</script>';
		}	
		$ecom_webtrackercode = '';
		$ecom_iswebtracker = 0;
	}
	
	if(file_exists($image_path.'/otherfiles/universal_Ga_script.js'))
	{
		readfile($image_path.'/otherfiles/universal_Ga_script.js','r');
	}	
?>
<!-- Place this render call where appropriate -->
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
