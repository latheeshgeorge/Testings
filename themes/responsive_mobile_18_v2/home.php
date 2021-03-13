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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" class="no-js">
<head>
 <?php
     require("responsive_head_v2.php");
 if($_REQUEST['req'] =='prod_detail')
    {
		
		echo "<script src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery-migrate-1.2.1.js",1)."\"></script>";

		
	    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.elevatezoom.js",1)."\"></script>";
	    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/fancybox.js",1)."\"></script>";
		echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/fancybox.css",1)."\" rel=\"stylesheet\">";
		
	}
	    //echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.js",1)."\"></script>";

 ?>	

 <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
</head>
<body class="nav-is-fixed foo">
<div id="fb-root"></div>
<?php
/*
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
	<?php
	*/
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
	$details_class = '';	 
   if($_REQUEST['req']=='prod_details')
   {
      $details_class = 'product-details';
   }
	?>
<div class="container-fluid mid-wrap <?php echo $details_class;?>">
<?php
	
	if($_REQUEST['req']=='')
	{
		 
	?>
  <div class="row">
	  
    <?php
    $sql_inline = "SELECT display_order,b.feature_modulename,display_component_id,display_id,display_title 
						FROM 
							display_settings a,features b 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.display_position='middle' 
							AND b.feature_allowedinmiddlesection = 1  
							AND layout_code='$default_layout' 
							AND a.features_feature_id=b.feature_id 
						ORDER BY 
								display_order 
						ASC";
		$ret_inline = $db->query($sql_inline);
		global $from_home;
		$from_home = true;
		if ($db->num_rows($ret_inline))
		{
			while ($row_inline = $db->fetch_array($ret_inline))
			{
				$modname 			= $row_inline['feature_modulename'];
				$body_dispcompid	= $row_inline['display_component_id'];
				$body_dispid		= $row_inline['display_id'];
				$body_title			= $row_inline['display_title'];
				switch($modname)
				{					
					case 'mod_shelfgroup': // case of home page content 
						include ("includes/base_files/shelfgroup.php");
					break;
					case 'mod_featured': // case of featured product
						include ("includes/base_files/featured.php");
					break;
					  
				};
			}
		}
		$from_home = false;

    ?>
</div>
  <?php
}
  include("mainbody.php");
  ?>
  <?php
	
	if($_REQUEST['req']=='')
	{
		 
	?>
 <div class="row">
	  <?php
     $sql_inline = "SELECT display_order,b.feature_modulename,display_component_id,display_id,display_title 
						FROM 
							display_settings a,features b 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.display_position='middle' 
							AND b.feature_allowedinmiddlesection = 1  
							AND layout_code='$default_layout' 
							AND a.features_feature_id=b.feature_id 
						ORDER BY 
								display_order 
						ASC";
		$ret_inline = $db->query($sql_inline);
		global $from_home;
		$from_home = true;
		if ($db->num_rows($ret_inline))
		{
			while ($row_inline = $db->fetch_array($ret_inline))
			{
				$modname 			= $row_inline['feature_modulename'];
				$body_dispcompid	= $row_inline['display_component_id'];
				$body_dispid		= $row_inline['display_id'];
				$body_title			= $row_inline['display_title'];
				if($modname=='mod_shelf')
				{					
					include ("includes/base_files/shelf.php");				
					  
				}
			}
		}
		$from_home = false;
    ?>
  </div>
<div class="row">
<div class="container-fluid">
<div class="row social"></div>
<?php
/*	
<div class="col-md-6 col-sm-6 col-xs-12"><div class="facebook"><a class="twitter-timeline" data-width="300" data-height="390" data-theme="light" href="https://twitter.com/puregustouk?ref_src=twsrc%5Etfw">Tweets by puregustouk</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></div></div>
<div class="col-md-6 col-sm-6 col-xs-12"><div class="twitter"><iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fpuregusto&tabs=timeline&width=300&height=390&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="300" height="390" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe></div></div>
*/
?>
</div>
</div>
</div>
<?php
}
?>
</div>
	 <?php	
 	  // Check whether top table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($toptable))
	{
           include("$bottomtable");
	}
	 
	  ?>  
	<script>$("#slideshow > div:gt(0)").hide();
setInterval(function() { 
  $('#slideshow > div:first')
    .fadeOut(1000)
    .next()
    .fadeIn(1000)
    .end()
    .appendTo('#slideshow');
},  3000);</script>

<script>
  AOS.init();
   // tabbed content
    // http://www.entheosweb.com/tutorials/css/tabs.asp
    $(".tab_content").hide();
    $(".tab_content:first").show();

  /* if in tab mode */
    $("ul.tabs li").click(function() {
		
      $(".tab_content").hide();
      var activeTab = $(this).attr("rel"); 
      $("#"+activeTab).fadeIn();		
		
      $("ul.tabs li").removeClass("active");
      $(this).addClass("active");

	  $(".tab_drawer_heading").removeClass("d_active");
	  $(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
	  
    });
	/* if in drawer mode */
	$(".tab_drawer_heading").click(function() {
      
      $(".tab_content").hide();
      var d_activeTab = $(this).attr("rel"); 
      $("#"+d_activeTab).fadeIn();
	  
	  $(".tab_drawer_heading").removeClass("d_active");
      $(this).addClass("d_active");
	  
	  $("ul.tabs li").removeClass("active");
	  $("ul.tabs li[rel^='"+d_activeTab+"']").addClass("active");
    });
	/* Extra class "tab_last" 
	   to add border to right side
	   of last tab */
	$('ul.tabs li').last().addClass("tab_last");
</script>
<?php 
//echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery-2.1.1.js",1)."\"></script>";
//echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.js",1)."\"></script>";
echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.mobile.custom.min.js",1)."\"></script>";
echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/main.js",1)."\"></script>";
?>
<script>$(document).ready(function () {
    $(document).click(function (event) {
        var clickover = $(event.target);
        var _opened = $(".navbar-collapse").hasClass("show");
        if (_opened === true && !clickover.hasClass("navbar-toggler")) {
            $(".navbar-toggler").click();
        }
    });
});</script>
<?php
echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/jquery-ui.css",1)."\" rel=\"stylesheet\">";
?>
<div class="cd-overlay"></div>
</body>
</html>
