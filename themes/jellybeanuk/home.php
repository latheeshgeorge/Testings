<?
/*#################################################################
	# Script Name 	: home.php
	# Description 	: This is the home page for the theme.
	# Coded by 	: Sny
	# Created on	: 05-Feb-2010
	# Modified by	:
	# Modified On	:
	#################################################################*/
	
	// Layout Type
	$default_layout 		= 'home';
	
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	if($ecom_siteid==62) // eurolabels
	{
	?>
	<script type="text/javascript" src="http://infra-gtc.com/js/37535.js" ></script>
	<noscript><img src="http://infra-gtc.com/37535.png" style="display:none;" /></noscript>
	<?php	
	}

    require("head.php");
    
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
<meta name="viewport" content="width=device-width" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<link href="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/jquery.flexisel.js"></script>
</head>

<body>
<center>
	<table width="100%"  cellspacing="0" cellpadding="0" class="main" >
 <tr>
    <td  class="maintoptd">
<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
?>

<?php
     // Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
            include("$toptable");
	}
	// Decide whether to display the top menu or not
	if(get_session_var('ecom_login_customer'))
	{
		/*if($ecom_siteid!=84)
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
		else
		{*/
			include "themes/$ecom_themename/modules/mod_topmenu.php";
		/*}*/
	}
?>
</td>
</tr>
  <tr>   
    <td align="left" valign="top" class="mainmiddletd">
	<?php
		include("mainbody.php");
	?>
    </td>
  
  </tr>
   <tr>
    <td  class="mainbottomtdA">
   <?php
		// Check whether bottom table exists for current site. If exists then include it
		$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
		if(file_exists($bottomtable))
		{
		  include("$bottomtable");
		}
	?>
	 </td>
  
  </tr>
	  </table>

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