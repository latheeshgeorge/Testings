<?
/*#################################################################
	# Script Name 	: mobilehome.php
	# Description 	: This is the home page for the mobile responsive theme.
	# Coded by 	: LSH
	# Created on	: 28-Jan-2016
	# Modified by	:
	# Modified On	:
	#################################################################*/
	
	// Layout Type
	$default_layout =	'home';
	
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
<!DOCTYPE html>
<html lang="en">
<head>
   
   
    <?php    
    require("responsive_head.php");
    if($_REQUEST['req'] =='prod_detail')
    {
	    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.elevatezoom1.js",1)."\"></script>";

	}
	echo 
    "
     <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/bootstrap.min.1.css",1)."\" rel=\"stylesheet\">";
    ?>

</head><!--/head-->

<body>
	<form name="frm_forcesubmit" id="frm_forcesubmit" action="" method="post" class="frm_cls">
	<input type="hidden" name="prodimgdet" id="prodimgdet" value="" />
	<input type="hidden" name="prod_curtab" id="prod_curtab" value="" />
	<input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1" />
	<input type="hidden" name="compare_products" id="compare_products" value="" />
	<?php
	if($_REQUEST['disp_id']) 
	{ ?>
		<input type="hidden" name="disp_id" id="disp_id" value="<?=$_REQUEST['disp_id']?>" />
	<?
	}

//End 
if($_REQUEST['req']=='search')
{
?>
	<input type="hidden" name="quick_search" value="<?=$_REQUEST['quick_search']?>" />
	<input type="hidden" name="search_category_id" value="<?=$_REQUEST['search_category_id']?>" />
	<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
	<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />
	<input type="hidden" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
	<input type="hidden" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
	<input type="hidden" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
	<input type="hidden" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>" />
	<input type="hidden" name="cbo_keyword_look_option" value="<?=$_REQUEST['cbo_keyword_look_option']?>" />
	<input type="hidden" name="rdo_mainoption" value="<?=$_REQUEST['rdo_mainoption']?>" />
	<input type="hidden" name="rdo_suboption" value="<?=$_REQUEST['rdo_suboption']?>" />


	<?php 
   //Section for making hidden values labels
	if(count($_REQUEST['search_label_value'])>0){
		foreach($_REQUEST['search_label_value'] as $v)
		{
			?>
		<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
			<?
		}	
	}

}
?>
</form>

	<div id="inner-wrap">
	<div id="main" role="main">
	<?php
	
	// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
    // Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/otherfiles/toptable.php";
	if(file_exists($toptable))
	{
           include("$toptable");
	}
	?>
	
	 <?php	include("mainbody.php");
	 
	  // Check whether top table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($bottomtable))
	{
           include("$bottomtable");
	}
	 
	  ?>
      <!--/middle-->
       </div>
  </div> 

	
	<?php

   echo "
              <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/bootstrap.min.js",1)."\"></script>
                            <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/bootsnav.js",1)."\"></script>

<!-- jQueryTab.js --> 
                            <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jQueryTab.js",1)."\"></script>
                             
                                                         <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/owl.carousel.js",1)."\"></script>";


                             global $js_owl,$css_owl;
                             ?>
                              <style>
						<?php
						echo $css_owl;
						?>
						.customNavigation{
						text-align: center;
						}
						.customNavigation a{
						-webkit-user-select: none;
						-khtml-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
						-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
						}
						</style>
						<script>
						$(document).ready(function () {
						<?php echo $js_owl;?>
						 });
						</script>
<?php echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/jquery-ui.css",1)."\" rel=\"stylesheet\">"; ?>
                         <script type="text/javascript" src="http://dbushell.github.io/Responsive-Off-Canvas-Menu/js/main.js"></script>
     <?php
    echo "<script src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.backstretch.min.js",1)."\"></script> 
          <script src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/backstretch_scripts.js",1)."\"></script>";

     ?>

</body>
</html>