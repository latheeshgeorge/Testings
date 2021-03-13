<?
/*#################################################################
	# Script Name 	: default.php
	# Description 	: This is the default page for the theme.
	# Coded by 		: Joby
	# Created on	: 26-May-2011
	# Modified by	:
	# Modified On	:
	#################################################################*/
	
	// Layout Type
	$default_layout 		= 'product-detail';
	
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
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='http://".$ecom_hostname."/cart.html';</script>";
		}
		else if ($_REQUEST['rets']==2)
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">window.location ='http://".$ecom_hostname."/enquiry.html';</script>";
		}
		else
		{
                    echo "<script language=\"javascript\" type=\"text/javascript\">document.location.replace(\"/\")</script>";
		}
		exit;
	}
	/*
?>
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jquery.js",1)?>"></script>
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jQuery_002.js",1)?>"></script>
<!--<script type="text/javascript">
		var $aj = jQuery;
		$aj(document).ready(function(){
		// alert ('jQuery running');
		
		$aj('#pagePeel').pagePeel({
			introAnim: true
		});
		$aj('a').linkControl({overlay:true});
	});
</script>-->
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jssor.js",1)?>"></script>
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jssor.slider.js",1)?>"></script>
    <script>
        jQuery(document).ready(function ($) {
            //Reference http://www.jssor.com/development/slider-with-slideshow-jquery.html
            //Reference http://www.jssor.com/development/tool-slideshow-transition-viewer.html

            var _SlideshowTransitions = [
            //Fade in R
            {$Duration: 1200, x: -0.3, $During: { $Left: [0.3, 0.7] }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 }
            //Fade out L
            , { $Duration: 1200, x: 0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 }
            ];

            var options = {
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
                    $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
                    $Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
                    $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                    $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
                },

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX: 10,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY: 10                                    //[Optional] Vertical space between each item in pixel, default value is 0
                },

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 2,                                //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 2                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                },

                $ThumbnailNavigatorOptions: {
                    $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $ActionMode: 0,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                    $DisableDrag: true                              //[Optional] Disable drag or not, default value is false
                }
            };

            var jssor_sliderb = new $JssorSlider$("sliderb_container", options);
            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
                var parentWidth = jssor_sliderb.$Elmt.parentNode.clientWidth;
                if (parentWidth)
                    jssor_sliderb.$ScaleWidth(Math.min(parentWidth, 672));
                else
                    window.setTimeout(ScaleSlider, 30);
            }

            ScaleSlider();

            if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
                $(window).bind('resize', ScaleSlider);
            }


            //if (navigator.userAgent.match(/(iPhone|iPod|iPad)/)) {
            //    $(window).bind("orientationchange", ScaleSlider);
            //}
            //responsive code end
        });
    </script>
    */
    ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <!--[if lt IE 7]>  <html class="ie ie6 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8 lte9 lte8"> <![endif]-->
<!--[if IE 9]>     <html class="ie ie9 lte9"> <![endif]-->
<!--[if gt IE 9]>  <html> <![endif]-->
<!--[if !IE]><!--> <html xmlns="http://www.w3.org/1999/xhtml">  <!--<![endif]-->
<head>
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />

	<?php

    require("head.php");
    echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/lightbox/jquery.colorbox.js",1)."\" ></script>";
    echo "<link href=\"".url_head_link("images/".$ecom_hostname."/css/lightbox/colorbox.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
        echo "<link href=\"".url_head_link("images/".$ecom_hostname."/css/zoom/jquery.fancybox.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
?>
<?php
		echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/zoom/jquery-1.8.3.min.js",1)."\" ></script>";

		echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/zoom/jquery.elevatezoom.js",1)."\" ></script>";
    		echo "<script  type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/scripts/zoom/jquery.fancybox.pack.js",1)."\" ></script>";

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
</head>

<body >

<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
?>
<div class="wrapper">
     <?php
         // Check whether top table exists for current site. If exists then include it
        $toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
        if(file_exists($toptable))
        {
			include("$toptable");
        }
    ?>

<div class="middlecontent">
	<?php
		//echo "Req Value - ".$_REQUEST['req'];
		if(get_session_var('ecom_login_customer'))
		{
				switch ($_REQUEST['req']) // show top menu only while viewing the after login pages
				{
					case 'login_home':	
					case 'myprofile':
					case 'enquiry':
					case 'wishlist':
					case 'myfavorites':
					case 'myaddressbook':		
					case 'orders':
					case 'downloadable_prod':
					case 'payonaccountdetails':
						include "themes/$ecom_themename/modules/mod_topmenu.php";
					break;
					case 'pricepromise':
						switch ($_REQUEST['action_pricepurpose'])
						{
							case 'mypromises':
							case 'mypromises_det':
							case 'mypromisespost_det':
								include "themes/$ecom_themename/modules/mod_topmenu.php";
							break;
						};
					break;	
				}
		}
   		include("mainbody.php");
    ?>	
    <div class="midcontentarea">
    <?php
        $position = 'bottomband';
        include("Components.php");
    ?>
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
</div>
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
        <script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\"></script>
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
