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
    echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.js",1)."\"></script>";
    ?>
</head><!--/head-->

<body>
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
	 <?php	include("mainbody.php"); ?>
    
   <div class="container">  
 <h2>best sellers</h2>
    <div class="grid_1_of_4 images_1_of_4">
    <div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>
    

    </div>
                        
               <div class="grid_1_of_4 images_1_of_4">
    <div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>
    

    </div><div class="grid_1_of_4 images_1_of_4">
    <div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>
    

    </div><div class="grid_1_of_4 images_1_of_4">
    <div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>
    

    </div>         
                        

    
    
   </div>
    
    <div class="container">
   
<div class="tabs-7">
	<ul class="tabs">
		<li><a href="#tab1">Featured</a></li>
		<li><a href="#tab4">current offers</a></li>
	</ul>
	<section class="tab_content_wrapper">
        <article class="tab_content" id="tab1">


			<div id="main" role="main">
    <section class="slider">
      <div class="flexslider carousel">
        <ul class="slides"><li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li><li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li>
          <li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li>


<li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li>

<li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li><li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li><li><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" width="193" height="164"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div>


</li>
          
        </ul>
      </div>
    </section>
  </div>



































    
    

            
        </article>
       
        
        
        
        
        
        
        
        
        
        
        <article class="tab_content" id="tab4">
           <div class="row">
    <div class="col-sm-3"><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" height="164" width="193"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div></div><div class="col-sm-3"><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" height="164" width="193"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div></div><div class="col-sm-3"><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" height="164" width="193"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div></div><div class="col-sm-3"><div class="product-grid">
							<div class="product-grid-head">
								
								<div class="block">
									<div class="starbox small ghosting"><div class="positioner"><div class="stars"><div class="ghost" style="display: none; width: 42.5px;"></div><div class="colorbar" style="width: 42.5px;"></div><div class="star_holder"><div class="star star-0"></div><div class="star star-1"></div><div class="star star-2"></div><div class="star star-3"></div><div class="star star-4"></div></div></div></div></div> <span> (46)</span>
								</div>
							</div>
							<div class="product-pic">
								<a href="#"><img src="<?php echo url_site_image('pro-1.jpg')?>" height="164" width="193"></a>
								<p>
								<a href="#">Tempo Double Robe Hook</a>
								
								</p>
                                <ul class="price-avl">
								<li class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></li>
								
								<div class="clear"> </div>
							</ul>
							</div>
							<div class="product-info">
								<a href="details.html"><div class="product-info-cust">
									Details
								</div></a>
								<div class="product-info-price">
								<button type="button" class="btn btn-add-to-cart btn-lg sharp">Add To Cart</button>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="more-product-info">
								<span> </span>
							</div>
						</div></div>
    </div>
        </article>
    </section>
</div>

    
    
    </div>
    

    
    
    
    
    
    
    
    <div class="container  recent-product-wrap">
<div class="row recent-product">
<div class="col-md-4"><ul class="list-unstyled list-thumbs-pro">
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
											</ul></div>
                                            
                                            <div class="col-md-4"><ul class="list-unstyled list-thumbs-pro">
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
											</ul></div>
                                            <div class="col-md-4"><ul class="list-unstyled list-thumbs-pro">
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
												<li class="product">
													<div class="product-thumb-info">
														<div class="product-thumb-info-image">
															<a href="shop-product-detail1.html"><img src="<?php echo url_site_image('pro1-small.jpg')?>" width="107" height="94"></a>
														</div>
														
														<div class="product-thumb-info-content">
															<h4><a href="shop-product-detail2.html">Chain necklace</a></h4>
															
															<span class="price"><span>Price From £30.00 (£36.00 inc VAT)</span></span>
                                                             <button type="button" class="btn btn-default get">SHOP NOW</button>
														</div>
													</div>
												</li>
											</ul></div>

</div>    
    
    
    
    
    
    
    
    
    </div>
    
    
    
    
    
    <!--/middle-->
    
    
    
    
    
	
	<footer id="footer"><!--Footer-->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					
					<div class="col-sm-12">
                    <div class="container"><p class="subscribe">SUBSCRIBE TO OUR NEWSLETTER</p>

<p class="subscribe_content">Subscribe to the Expert mailing list to receive updates on new arrivals, special offers and other discount information.</p></p>

<form action="">
	                        <div class="input-group search-box-pad">
	                            <input class="form-control input-search" placeholder="Subscribe to the Expert mailing list" type="text">
	                            <span class="input-group-btn">
	                                <button class="btn btn-default no-border-left" type="submit"><i class="">go</i></button>
	                            </span>
	                        </div>
	                    </form>

</div>
                    
						
					
				</div>
			</div>
		</div>
		
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<div class="col-sm-2">
						<div class="single-widget">
                        <h2>Quock Shop</h2>
							<div class="footer-address">
											<ul>
												<li><i class="fa fa-map-marker"> </i>Unit 2,<br>
5 Effie Road, 
London,SW6 1EL</li>
												<li><i class="fa fa-phone"> </i> +44 20 7123 4567</li>
												<li><i class="fa fa-envelope-o"> </i>info@doorhandles.co.uk</li>
											</ul>
										</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Quock Shop</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">T-Shirt</a></li>
								<li><a href="#">Mens</a></li>
								<li><a href="#">Womens</a></li>
								<li><a href="#">Gift Cards</a></li>
								<li><a href="#">Shoes</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>Policies</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Terms of Use</a></li>
								<li><a href="#">Privecy Policy</a></li>
								<li><a href="#">Refund Policy</a></li>
								<li><a href="#">Billing System</a></li>
								<li><a href="#">Ticket System</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>About Shopper</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Company Information</a></li>
								<li><a href="#">Careers</a></li>
								<li><a href="#">Store Location</a></li>
								<li><a href="#">Affillate Program</a></li>
								<li><a href="#">Copyright</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="single-widget">
							<h2>About Shopper</h2>
							<ul class="nav nav-pills nav-stacked">
								<li><a href="#">Company Information</a></li>
								<li><a href="#">Careers</a></li>
								<li><a href="#">Store Location</a></li>
								<li><a href="#">Affillate Program</a></li>
								<li><a href="#">Copyright</a></li>
							</ul>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		
		<div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">All website images and website data copyright belong to The Webclinic Ltd.</p>
					<p class="pull-right"><div class="payment-history">
									<ul>
										<li><a href="#"><img src="<?php echo url_site_image('payment.png')?>" alt=""></a></li>
									</ul>
								</div><span></span></p>
				</div>
			</div>
		</div>
		
	</footer><!--/Footer-->
	<?php

   echo "
              <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/bootstrap.min.js",1)."\"></script>
                            <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/bootsnav.js",1)."\"></script>

<!-- jQueryTab.js --> 
                            <script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jQueryTab.js",1)."\"></script>";
                            ?>
                           

</body>
</html>
