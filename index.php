<?
	/*#################################################################
	# Script Name 	: index.php
	# Description1 	: This is the common page which will be loaded when a site is referenced
	# Coded by 		: Sny
	# Created on	: 03-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	
	/*if($_SERVER['HTTP_HOST'] =='kqf-foods.com' or $_SERVER['HTTP_HOST'] == 'www.kqf-foods.com' or $_SERVER['HTTP_HOST'] =='kqf-foods.co.uk' or $_SERVER['HTTP_HOST'] == 'www.kqf-foods.co.uk') 
	{
		include "comingsoon.php";
		exit;
	}*/	
	if($_SERVER['HTTP_HOST'] =='dentaldiamonds.co.uk' or $_SERVER['HTTP_HOST'] =='www.dentaldiamonds.co.uk')
	{
		if($_SERVER['REQUEST_URI']=='/') // case of domain name only
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://www.metrodentclinical.com/welcome-to-metrodent-clinical-pg50414.html"); 
			exit;
		}
		else
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://www.metrodentclinical.com".$_SERVER['REQUEST_URI']); 
			exit;
		}
		
		
	}	
	if($_SERVER['HTTP_HOST'] =='discountfloorings.bshop4.co.uk')
	{
		//if($_SERVER['REMOTE_ADDR']=='182.72.159.170')
		{
			$refr = trim($_SERVER['HTTP_REFERER']);
			$pos = strpos($refr, 'www.google.co');
			if ($pos === false) {
			}
			else
			{
				echo "Sorry!! you are not authorized to view this page";
				
				header("HTTP/1.1 301 Moved Permanently");
				//header("Location: http://discountfloorings.bshop4.co.uk"); 
				exit;
			}	
		}
	}
	
	if($_SERVER['HTTP_HOST'] =='doorhandles.uk' or $_SERVER['HTTP_HOST'] =='www.doorhandles.uk')
	{
		//echo "<div style='width:100%;text-align:center;font-size:24px;font-weight:bold;color:#FF0000;padding-top:250px;'>... Website Under Construction ...</div>";
		echo '<div style="text-align:center;width:100%;padding-top:125px;"><img src="http://www.discount-mobility.co.uk/images/under-construction.jpg" alt="Under Construction"></div>';
		exit;
	}
	
	require("functions/functions.php");	
	require("includes/urls.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/cartCalc.php");
	require("classes/mime.php");
	//The required constants for the database and also for the site and also create an object to access database.
	require("config.php");
	
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
		
	$cur_urls 	= $_SERVER['REQUEST_URI'];
	include "redirect301_handler.php";
	$pos 		= strpos($cur_urls, '?option=');
	if ($pos === false) 
	{
	}
	else
	{
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.$ecom_selfhttp.$ecom_hostname.'/sitemap.html');
		exit;
	}
	
	
	// Handling the case of hit registration for products and categories while viewing the product details and category details page
	if($_REQUEST['req'] == "prod_detail" and $_REQUEST['product_id'])
	{
		set_cookie_product($_REQUEST['product_id']);
		// Case of coming to show the normal product details. Building the query for products. If this product is hidden then
		// user will be redirected to sitemap page using header tag.
		if($_REQUEST['product_id'] and $_REQUEST['prod_mod']=='') 
		{
			$sql_outerprod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
                                                    product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
                                                    product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
                                                    product_total_preorder_allowed,product_applytax,product_shortdesc,product_longdesc,
                                                    product_averagerating,product_deposit,product_deposit_message,product_bonuspoints,
                                                    product_variable_display_type,product_variable_in_newrow,productdetail_moreimages_showimagetype,
                                                    product_default_category_id,product_details_image_type,product_flv_filename,product_stock_notification_required,
                                                    product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
                                                    product_variablecomboprice_allowed,product_det_qty_type,product_det_qty_caption,product_det_qty_drop_values,
                                                    product_det_qty_drop_prefix,product_det_qty_drop_suffix,product_variablecombocommon_image_allowed,default_comb_id,
                                                    price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
                                                    price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix, price_noprice,product_freedelivery,product_show_pricepromise,
                                                    product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text,product_commonsizechart_link,produt_common_sizechart_target,product_weight,product_intensivecode,product_discontinue        
                                            FROM 
                                                products 
                                            WHERE 
                                                product_id=".$_REQUEST['product_id']." 
                                                AND sites_site_id=$ecom_siteid 
                                                AND product_hide ='N' 
                                            LIMIT 
                                                1";
			$ret_outerprod		= $db->query($sql_outerprod);
			if($db->num_rows($ret_outerprod))
				$row_outerprods 	= $db->fetch_array($ret_outerprod);
			else // case if product is hidden or not available with the website
			{
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: ".$ecom_selfhttp."$ecom_hostname/sitemap.html");
				exit();
			}
		}	
	}
	else if($_REQUEST['req'] == "categories" and $_REQUEST['category_id'])
	{
		set_cookie_category($_REQUEST['category_id']);
		// Case of coming to show the normal category details. Building the query for category. If this category is hidden then
		// user will be redirected to sitemap page using header tag.
		$sql_outercat = "SELECT category_id,category_name,category_shortdescription,parent_id,category_paid_description,
                                            category_paid_for_longdescription,category_showimageofproduct,default_catgroup_id,
                                            category_subcatlisttype,product_displaytype,product_displaywhere,
                                            product_showimage,product_showtitle,product_showshortdescription,product_showprice,
                                            category_turnoff_treemenu,category_turnoff_pdf,category_subcatlistmethod,product_orderfield,
                                            product_orderby,category_turnoff_moreimages,category_turnoff_mainimage,category_turnoff_noproducts,category_showname,
                                            category_showshortdesc,category_showimage,subcategory_showimagetype,special_detailspage_required,
                                            product_showrating,product_showbonuspoints ,category_bottom_description,grid_column_cnt      
					FROM 
                                            product_categories 
					WHERE 
                                            sites_site_id		= $ecom_siteid 
                                            AND category_id 	= ".$_REQUEST['category_id']." 
                                            AND category_hide	= 0
					LIMIT 
                                            1";
		$ret_outercat 	= $db->query($sql_outercat);
		if($db->num_rows($ret_outercat))
			$row_outercats 	= $db->fetch_array($ret_outercat);
		else // case if category is hidden or not available with the website
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$ecom_selfhttp."$ecom_hostname/sitemap.html");
			exit();
		}
		
	}
	else if($_REQUEST['req'] == "static_page" and $_REQUEST['page_id']) // case of static pages
	{
		$sql_outerstatpage = "SELECT page_id,title,content,pname,page_type,page_link,allow_auto_linker,page_link_newwindow 
                                        FROM 
                                            static_pages	 
                                        WHERE 
                                            page_id=".$_REQUEST['page_id']." 
                                            AND sites_site_id=$ecom_siteid 
                                            AND hide = 0 
                                        LIMIT 
                                            1";
		$ret_outerstatpage = $db->query($sql_outerstatpage);
		if($db->num_rows($ret_outerstatpage))
			$row_outerstatpage 	= $db->fetch_array($ret_outerstatpage);
		else // case if static page is hidden or not available with the website
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$ecom_selfhttp."$ecom_hostname/sitemap.html");
			exit();
		}
	}
	/*
	if($ecom_siteid ==86 )
	{
		if($_REQUEST['req'] == "registration")
		{
			switch($_REQUEST['action_purpose'])
			{
				case  'insert':
				case 'ForgotPassword':
				case 'ForgotPassword_send':
				case 'login':
				break;
			default:
				if(!$protectedUrl)
				{
					echo '<script type="text/javascript">
							window.location = "https://www.bsecured.co.uk/'.$ecom_hostname.'/index.php?req=registration";
						  </script>';
					exit;
				}
			}
		}	
	}
	*/ 
	
	//22 Nov 2011 Start
	//to display the download link on the top of the iphone home page
	if($ecom_load_mobile_theme==false)
	{
		if($load_mobile_theme_arr[1]=='Apple' and $ecom_siteid == 61)
		{
			if ($_REQUEST['req']=='') // case of home page only
			{
				//echo '<div style="position:absolute;left:0;top:0;width:96%;text-align:center;padding: 10px 2%;background-color:#F00;color:#FFF;font-size:12px;font-weight:bold"><a href="http://itunes.apple.com/us/app/garraways/id508386575?ls=1&amp;mt=8"><img src="http://'.$_SERVER['HTTP_HOST'].'/images/'.$_SERVER['HTTP_HOST'].'/site_images/iphone_logo.gif" border="0"></a><p style=""><a style="color:#FFF" href="http://itunes.apple.com/us/app/garraways/id508386575?ls=1&amp;mt=8">Click here to download Garraways iPhone Application.</a></p></div>';
			}
		}
	}	
	// 22 Nov 2011 End
	
	require("includes/session_log.php"); // Including the file which records the site hits
	include ("$ecom_themepath"); // Calling the required theme file

/*if($ecom_siteid==61 and $_REQUEST['cart_mod']!='show_checkoutsuccess') // for garraways
{
	
?>
<!-- Shopzilla Site Abandonment Survey Code -->
<script type="text/javascript">
<!--
//Adjust br_frequency to determine how often, in days, a survey invitation is offered to the same customer
var br_frequency = 7;
//Adjust br_percentage to determine percentage of traffic to see an invitation on each page load
var br_percentage = 100;
//Adjust br_pos_y to reposition the DHTML invitation vertically on your page, calculated in pixels from top-left corner
var br_pos_y = 470;
//Adjust br_pos_x to reposition the DHTML invitation horizontally on your page, calculated in pixels from top-left corner
var br_pos_x = 400;
var br_data = [];
//Pass a value for Q164 to report the page the customer was viewing when the invitation was offered
br_data['Q164'] = '<?php echo $cur_urls?>';
//Pass a value for Q165 to report the referring URL from which the customer arrived at your site
br_data['Q165'] = '';
//Pass a value for Q166 to report the customer, session, or web analytics ID
br_data['Q166'] = '';
//-->
</script>
<script type="text/javascript" src=" https://evaleu.shopzilla.com/js/survey_160350_1.js">
</script>
<!-- End Shopzilla Site Abandonment Survey Code -->
<?php
}*/	
$db->db_close();
?>
