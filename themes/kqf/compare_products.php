<?php
	/*#################################################################
	# Script Name 	: default.php
	# Description 	: This is the default page for the theme.
	# Coded by 		: Sny
	# Created on	: 05-Dec-2007
	# Modified by	: Sny
	# Modified On	: 10-Jan-2008
	#################################################################*/
	// Layout Type
	$default_layout 		= '3col';
	// Including the website layout settings cached file (if any)
	$layout_cache_file_included = false;
	$filename = $image_path.'/cache/websitelayout/'.$default_layout.'.php';
	if(file_exists($filename))
	{
		include_once "$filename";
		$layout_cache_file_included = true;
	}
	
	// Calling the function to get all the captions set in the COMMON section
	$Captions_arr['COMMON']	= getCaptions('COMMON');
	// Get the captions for price
	$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');
	
	// Calling the function to get the details of default currency
	$default_Currency_arr	= get_default_currency();
	
	// Assigning the current currency to the variable
	$sitesel_curr			= get_session_var('SITE_CURR');
	// If sitesel_curr have no value then set it as the default currency
	if (!$sitesel_curr)
	{
		$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		//clear_all_cache();
	}
	// Get details of current currency
	$current_currency_details = get_current_currency_details();
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
	?>
			<script language="javascript" type="text/javascript">window.location ='http://<?php echo $ecom_hostname?>/cart.html';</script>
	<?php	
		}
		else
		{
	?>
			<script language="javascript" type="text/javascript">document.location.replace("/")</script>
	<?
		}
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? 
	// Including the header file
	require("head.php");
	// Including the page which validates various section such as newsletter signup, bestsellers, login etc
	require ("actions.php");

?>
</head>
<!--[if lt IE 7 ]> <html class="ie ie6 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 lte9 lte8 lte7 no-js"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 lte9 lte8 no-js"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 lte9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="notie no-js"> <!--<![endif]-->
<body>
<center>
	<div class="processing_divcls" id="processing_div" style="display:none;" align="center">
	<br/>
	 Processing Please wait ...
	 <br/><br/>
	</div>
<?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
?>	
<form name="frm_forcesubmit" id="frm_forcesubmit" action="" method="post" class="frm_cls">
<input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
<input type="hidden" name="prod_curtab" id="prod_curtab" value="" />
</form>
<div class="wrapper">
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
		include "themes/$ecom_themename/modules/mod_topmenu.php";
	}
?>
<div class="centerwrap">

 	<?php
   	// Including the page which decides the display logic for the middle section
   	include ("mainbody.php");
   ?>
   </div>
   <div class="footerwrap">
<div class="footertop"></div>
<div class="footerbg">

<div class="footer">
	 <?php
		// Check whether bottom table exists for current site. If exists then include it
		$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
		if(file_exists($bottomtable))
		{
		  include("$bottomtable"); 
		}
	?>



</div>


</div>
<div class="footerbottom"></div>

</div>
<?php 
	// ##################################################################################################
	// Calling the function to display the web tracker bottom script. This is the webtracker from b-1st 
	// ##################################################################################################
	
	//get_WebtrackerBottomScript();
	
	// #######################################################################
	// google webtracker script
	// #######################################################################
	if($ecom_iswebtracker)
	{
?>
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		var pageTracker = _gat._getTracker("<?php echo $ecom_webtrackercode?>"); <?php /*UA-3702067-1*/?>
		pageTracker._initData();
		pageTracker._trackPageview();
		</script>
<?php
	}	
	// #######################################################################
	// urchin google webtracker code
	// #######################################################################
	if($ecom_isurchinwebtracker and $protectedUrl==false)
	{
?>
	
		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
		</script>
		<script type="text/javascript">
		_uacct = "<?php echo $ecom_urchinwebtrackercode?>"; <?php /*UA-4022502-1*/?>
		urchinTracker();
		</script>
<?php		
	}
?>
</center>
<?php
if(trim($ecom_footer_script)!='' and $protectedUrl==false)
	echo stripslashes($ecom_footer_script);
?>
</body>
</html>
