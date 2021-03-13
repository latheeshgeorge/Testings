<?php
/*#################################################################
	# Script Name 	: prod-detail.php
	# Description 	: This is the home page for the theme.
	# Coded by 	    : Sobin Babu
	# Created on	: 30-July-2013
	# Modified by	:
	# Modified On	:
	#################################################################*/
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	// Layout Type
	$default_layout 		= 'product-detail';
	//echo "DCD90A19AD53865525 ";
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}
	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']		= getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');

	// Calling the function to get the details of default currency
	$default_Currency_arr		= get_default_currency();

	// Assigning the current currency to the variable
	$sitesel_curr			= get_session_var('SITE_CURR');
	
	if($_REQUEST['cbo_selcurrency'])
	{
		$sitesel_curr   = $_REQUEST['cbo_selcurrency'];
		set_session_var('SITE_CURR',$sitesel_curr);
		//Finding the symbol for current currency
		$sql_curr  	= "SELECT curr_sign_char FROM general_settings_site_currency WHERE currency_id = $sitesel_curr";
		$ret_curr	= $db->query($sql_curr);
		if($db->num_rows($ret_curr))
		{
			$row_curr  			= $db->fetch_array($ret_curr);
			$sitesel_currsign 		= $row_curr['curr_sign_char'];
		}
		set_session_var('SITE_CURR_SIGN',$sitesel_currsign);
	}
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
	}
	// Get details of current currency
	$current_currency_details = get_current_currency_details();
	// Including the page which validates various section such as newsletter signup, bestsellers, login etc
	require ("actions.php");
	// Handling the case of logout
	if($_REQUEST['req'] == "logout")
	{
		clear_session_var('ecom_login_customer');
		clear_session_var('ecom_cust_group_exists');
		clear_session_var('ecom_cust_group_prod_array');
		clear_session_var('ecom_cust_group_array');
		clear_session_var('ecom_cust_direct_exists');
		clear_session_var('ecom_cust_direct_disc');
		// Check whether logout is coming by clicking the logout link in cart page. if so redirect to cart page itself
		if ($_REQUEST['rets']==1)
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='".$ecom_selfhttp.$ecom_hostname."/cart.html';</script>";
		}
		else if ($_REQUEST['rets']==2)
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='".$ecom_selfhttp.$ecom_hostname."/enquiry.html';</script>";
		}
		else
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">document.location.replace(\"/\")</script>";
		}
		exit;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "<?php echo $ecom_selfhttp;?>www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="<?php echo $ecom_selfhttp;?>www.w3.org/1999/xhtml">
<head>
	<?php

    require("head.php");
	echo " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/searchfilter/jquery-ui.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";

    // Check whether a fraud click is registered
    $fraud_detection = get_session_var('COST_PER_CLICK_FRAUD');
    // Resetting the fraud click session variable to 0
    set_session_var('COST_PER_CLICK_FRAUD',0);
    if($fraud_detection==1) 
    {
        echo "
        <style>
            .transparent
            {
                filter:alpha(opacity=70); 
                -moz-opacity: 0.70; 
                opacity: 0.70; 
                z-index:1000;
                display:none;
                position:absolute;
                left:0;
                top:0;
                margin:0;
                width:100%;
                height:100%;
                background-color:#f2f2f2;
            }
            .def_div{
                display:none;
                position:absolute;
                left:0;
                top:0;
                padding-top:12%;
                width:100%;
                z-index:2000;
            }
        </style>
        <script language=\"javascript\" type=\"text/javascript\">
        function  helpmsg() 
        {
            document.getElementById('helpdiv').style.display='block';
            document.getElementById('def_content').style.display='block';
        }
        function  helpmsClse() 
        {
            document.getElementById('helpdiv').style.display='none';
            document.getElementById('def_content').style.display='none';
        }
</script>";
    }
?>
<script type="text/javascript">
			jQuery.noConflict();
		var $ajax_j = jQuery;
    $ajax_j(document).ready(function(){ 
 
        $ajax_j(window).scroll(function(){
            if ($ajax_j(this).scrollTop() > 100) {
                $ajax_j('.scrollup').fadeIn();
            } else {
                $ajax_j('.scrollup').fadeOut();
            }
        }); 
 
        $ajax_j('.scrollup').click(function(){
            $ajax_j("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
    });
</script>
<?php

?>
</head>
<!--[if lt IE 7 ]> <html class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if gte IE 9 | !IE ]><html class="notie no-js"> <![endif]-->

<body >
<?php
/*	
<body  style='background: url("http://www.puregusto.co.uk/images/www.puregusto.co.uk/site_images/xmas_bg_new.jpg") no-repeat fixed 100% 100% transparent;'>
*/
?> 
<?php
/*	
<body  style='background: url("http://www.puregusto.co.uk/images/www.puregusto.co.uk/site_images/black-friday-bg.jpg") no-repeat fixed 100% 100% transparent;'>
*/
?>
<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
	$cust_id						=	get_session_var("ecom_login_customer");
	$Captions_arr['LOGIN_MENU'] 	=	getCaptions('LOGIN_MENU');
	$Captions_arr['CUST_LOGIN'] 	=	getCaptions('CUST_LOGIN');
   $sr_arr 							=	array('Mr.','Mrs.','Miss.','Ms.','M/s.','Dr.','Sir.','Rev.');
   $rp_arr							=	array('','','','','','','','');
?>
<div class="wrapper2">
	
		<?php
			// Check whether top table exists for current site. If exists then include it
			$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
			if(file_exists($toptable))
			{
				include("$toptable");
			}
        ?>
        <div class="middle_wrapper" >
        	<div class="middle_top"></div>
        	<div class="middle_bg">				
        		<div class="middlecontent" >
        <?php	include("mainbody.php");
        ?>        	</div>

                <div class="rightcontent" >
        <?php	//Showing the top static page groups
				$position = 'right';
				include("Components.php");
        ?>		</div>
         <div>
        <?
			/*$this->show_product_downloads($row_prod['product_id']);*/
			// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
							a.product_variablesaddonprice_exists,a.product_variablecomboprice_allowed,
							a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery        
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$_REQUEST['product_id']." 
								AND a.sites_site_id=$ecom_siteid 
								AND a.product_id = b.link_product_id 
								AND a.product_hide = 'N' 
								AND b.link_hide=0
							ORDER BY 
								b.link_order";
			$ret_linked = $db->query($sql_linked);
			if ($productHtml=="")
			{ 
				require("themes/$ecom_themename/html/productHtml.php");
				$productHtml= new product_Html(); // Creating an object for the product_Html class
			}
			if ($db->num_rows($ret_linked))
			{
				$productHtml->Show_Linked_Product($ret_linked);
			}
			 global $shelf_for_inner;
			$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
				FROM 
					display_settings a,features b 
				WHERE 
					a.sites_site_id=$ecom_siteid 
					AND a.display_position='middle' 
					AND b.feature_allowedinmiddlesection = 1  
					AND layout_code='".$default_layout."' 
					AND a.features_feature_id=b.feature_id 
					AND b.feature_modulename='mod_shelf' 
				ORDER BY 
						display_order 
						ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					include ("includes/base_files/shelf.php");
					$shelf_for_inner	= false;

				}
			}
			?>
        </div>
        </div>
       
                	<div class="middle_bottom"></div>
         
         </div>       
         </div>	

<?php
	// Check whether bottom table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($bottomtable))
	{
		include("$bottomtable");
	}
?>

<?php
// #######################################################################
if($ecom_iswebtracker)
{
    echo "
        <script type=\"text/javascript\">
            var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
            document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
        </script>
        <script type=\"text/javascript\">
            var pageTracker = _gat._getTracker(\"".$ecom_webtrackercode."\");
            pageTracker._initData();
            pageTracker._trackPageview();
        </script>
        ";
}	
// #######################################################################
// urchin google webtracker code
// #######################################################################
if($ecom_isurchinwebtracker and $protectedUrl==false)
{
    echo "
        <script src=\"".$ecom_selfhttp."www.google-analytics.com/urchin.js\" type=\"text/javascript\"></script>
        <script type=\"text/javascript\">
            _uacct = \"".$ecom_urchinwebtrackercode."\"; 
            urchinTracker();
        </script>
        ";
}
if($fraud_detection==1)
{
    echo "<script language=\"javascript\" type=\"text/javascript\"> helpmsg() </script>";
}
echo "</center>";
    if(trim($ecom_footer_script)!='' and $protectedUrl==false)
            echo stripslashes($ecom_footer_script);
echo "
        <div id=\"bw\" style=\"display:none;\"></div>"
?>
</body>
</html>
