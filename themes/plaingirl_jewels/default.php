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
	$default_layout 		= 'default';
	
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
	if($_REQUEST['req']=='cart')
	{
		$sitesel_curr			= 0;
	}
	else
	{
		$sitesel_curr			= get_session_var('SITE_CURR');
	}	
	
	if($_REQUEST['cbo_selcurrency'] and $_REQUEST['req']!='cart')
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
		if($_REQUEST['req']=='cart')
		{
			set_session_var('SITE_CURR',$sitesel_curr);
		}
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
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jQuery.js",1)?>"></script>
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jQuery_002.js",1)?>"></script>
<script type="text/javascript">
		var $aj = jQuery;
		$aj(document).ready(function(){
		// alert ('jQuery running');
		
		$aj('#pagePeel').pagePeel({
			introAnim: true
		});
		$aj('a').linkControl({overlay:true});
	});
</script>
<?php */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <?php         
    require("head.php");
    //65 live id
if($ecom_siteid==106)//local
{
?>
 <script type="text/javascript" language="javascript" src="http://<?php echo $ecom_hostname ?>/images/<?php echo $ecom_hostname ?>/scripts/crawler.js"></script>
<?php
}

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
<body>
<center>


        <?php
        // Check whether top_misc file exists for current site. If exists then include it
        $top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
        if(file_exists($top_misc))
        {
            include("$top_misc");
        }
        ?>
        <div class="topout_bg"><div class="topout_bgA">

<?php 
		  		$query		=	"SELECT 
								a.display_title,a.display_id,feature_id,feature_modulename,display_component_id 
							FROM 
								display_settings a, features b 
							WHERE 
								a.sites_site_id='$ecom_siteid' 
								AND a.display_position LIKE 'top' 
								AND a.features_feature_id=b.feature_id 
								AND b.feature_modulename = 'mod_staticgroup' 
								AND a.layout_code='$default_layout' 
							ORDER BY 
								a.display_order limit 1";	
		$result = $db->query($query);	
		if($db->num_rows($result))
		{
			while ($row_result = $db->fetch_array($result))
			{					
				$display_title 			= stripslashes($row_result['display_title']);
				$display_id				= $row_result['display_id'];
				$display_module 		= $row_result['feature_modulename'];
				$display_componentid	= $row_result['display_component_id'];
				$position = 'topout';
				include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_staticgroup.php");
			}
		}
		
            ?>
</div> </div>
            <div class="main_otr">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
                <?php
                // Check whether top table exists for current site. If exists then include it
                $toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
                if(file_exists($toptable))
                {
                    include("$toptable");
                }
                // Decide whether to display the top menu or not
                // Decide whether to display the top menu or not
                   // Decide whether to display the top menu or not
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
                ?>
                <tr>
                <td class="maintoptd">
                <div class="inner_detailsB">
                <?php
                include("mainbody.php");
                ?>
                </div>
                </td>
                </tr>
                <?php
                // Check whether bottom table exists for current site. If exists then include it
                $bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
                if(file_exists($bottomtable))
                {
                    include("$bottomtable");
                }
                ?>
                <tr>
                <td>&nbsp;</td>
                </tr>
                </table>
            </div>
        </center>
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