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
	$default_layout =	'mobilehome';
	
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
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
 <?php         
    require("mobresponsive_head.php");
    ?>
    <!--[if (gt IE 8) | (IEMobile)]><!-->
    <!--<![endif]-->
    <!--[if (lt IE 9) & (!IEMobile)]>
    <link rel="stylesheet" href="css/ie.css">
    <![endif]-->
    
    <!--caraousal-->
    <!-- Latest compiled and minified CSS -->
		

		<!-- Animate.css library -->


        
            <!--caraousal-->
    
    
    
    
    
</head>
<body>
	 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" >
        <div class="modal-content"> 
			 <div class="modal-footer">
		<button class="modall-close" data-dismiss="modal">Close</button>
	</div>        
          <div class="modal-body">                
          </div>
         
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
<div class="row-container">
<div id="outer-wrap">
<div id="inner-wrap">
 <?php
// Check whether top_misc file exists for current site. If exists then include it
	$top_misc="./images/".$ecom_hostname."/mobile_otherfiles/top_misc.php";
	if(file_exists($top_misc))
	{
            include("$top_misc");
	}
    // Check whether top table exists for current site. If exists then include it
	$toptable="./images/".$ecom_hostname."/mobile_otherfiles/toptable.php";
	if(file_exists($toptable))
	{
           include("$toptable");
	}
	?>
   

    <nav id="nav" role="navigation">
        <div class="block">
			 <ul>
			 <?php
                    $position = 'mobiletop';
                    include("Components.php");
                ?>
    
                <!--
             
             -->
            
            </ul>
            <a class="close-btn" id="nav-close-btn" href="#top">Return to Content</a>
        </div>
    </nav>

<?php
              if($_REQUEST['req']=='')
              {
                    $position = 'mobiletopband';
                    include("Components.php");
                    $position = 'mobilemiddleband';
                    include("Components.php");
              }
                    include ("mainbody.php");
                     $position = 'bottom';
                    include("Components.php");
                    
  ?>        
      


                                    
                              
   







































   
<?php
   	$bottomtable="./images/".$ecom_hostname."/mobile_otherfiles/bottomtable.php";
	if(file_exists($bottomtable))
	{
           include("$bottomtable");
	}
?>   

<!--/#inner-wrap-->
</div>
<!--/#outer-wrap-->
</div>
 	

	
	
	</div>

</body>
</html>
