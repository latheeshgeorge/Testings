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
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 <?php
     require("responsive_head_v2.php");

 ?>	
 
 
 <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	
</head>
<body class="nav-is-fixed foo">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
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
	$details_class = '';	 
   if($_REQUEST['req']=='prod_details')
   {
      $details_class = 'product-details';
   }
	?>
	<div class="container-fluid mid-wrap product-list">
<div class="breadcrump"><nav class="breadcrumb">
  <a class="breadcrumb-item" href="#">Home</a>
  <a class="breadcrumb-item" href="#">Library</a>
  <a class="breadcrumb-item" href="#">Data</a>
  <span class="breadcrumb-item active">Bootstrap</span>
</nav></div>
<div class="row">
<div class="col-md-3">
		<div class="image-wraps">
		<p><img src="img/ad-banner1.jpg" /></p>
		<p><img src="img/ad-banner2.jpg" /></p>
		<p><img src="img/ad-banner3.jpg" /></p></div>
		
		<p><div class="head-title-blue">product Spotlight</div>
		<div class="spotlight-grid product-spot">
		<div class="product-title">Cafe Maid Cream 
Pots</div>
<div class="pro-img-wrap"><img src="img/pro-img-2.jpg" /></div>
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></span></p>
		
		</div>
		
		<div class="spotlight-grid product-spot">
		<div class="product-title">Cafe Maid Cream 
Pots</div>
<div class="pro-img-wrap"><img src="img/pro-img-2.jpg" /></div>
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></span></p>
		
		</div>
		
		
		
		</p>
		
		
		<p class="row bestsell">
		<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left"><img src="img/best-pro-1.jpg" /></div>
		<div class="best-detail-right">puregusto Italian caramelised biscuts x 300</div>
		
		</div></div>
		<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left"><img src="img/best-pro-1.jpg" /></div>
		<div class="best-detail-right">puregusto Italian caramelised biscuts x 300</div>
		
		</div></div>
		<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left"><img src="img/best-pro-1.jpg" /></div>
		<div class="best-detail-right">puregusto Italian caramelised biscuts x 300</div>
		
		</div></div>
		<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left"><img src="img/best-pro-1.jpg" /></div>
		<div class="best-detail-right">puregusto Italian caramelised biscuts x 300</div>
		
		</div></div>
		<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left"><img src="img/best-pro-1.jpg" /></div>
		<div class="best-detail-right">puregusto Italian caramelised biscuts x 300</div>
		
		</div></div>
		</p>
		
		</div>
<div class="col-md-9">
<div class="row listing-row"><div class="col"><div class="toolbar-amount"><p class="toolbar-amount" id="toolbar-amount"> Items <span class="toolbar-number">1</span>-<span class="toolbar-number">18</span> of <span class="toolbar-number">44</span> </p></div>

<div class="sorter"><div class="toolbar-sorter"> <!--<label class="sorter-label" for="sorter">Sort By</label>--><select id="sorter" data-role="sorter" class="sorter-options"> <option value="position" selected="selected">Sort By</option>
<option value="position" >Recommended</option> <option value="priceDesc">Price (High to Low)</option> <option value="priceAsc">Price (Low to High)</option> <option value="nameAsc">Product Name (A-Z)</option> <option value="nameDesc">Product Name (Z-A)</option></select></div></div>
</div></div>
<div class="row">

<div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div>
<div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div>
<div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div>
<div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div><div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div><div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div><div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div><div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div><div class=" col-lg-4 col-md-6 col-sm-6">
<div class="product-grid">
	  <div class="product-title">Cafe Maid Cream 
Pots</div>
<p class="product-info">With long shelf life and easy to use pots, these cafe maid cream pots are useful to have. These can be used in many occassions such as the house.</p>

<div class="product-img-wrap"><img src="img/main-pro2.jpg" /></div>  
<p class="price-details"><span class="price">&#163; 329.99 <span class="vat">( Inc VAT &#163; 395.99 )</span></p>
<div class="product-table"><table class="qty-discount-table"><thead><tr><th>Qty</th> <th>1+</th> <th>6+</th> <th>12+</th></tr></thead><tbody><tr><td>Price</td> <td>&#163; 17.99</td> <td>&#163; 17.09</td> <td>&#163; 16.19</td></tr></tbody></table></div>
<div class="addwrap">
	<input type="text" class="form-control qty_txt" placeholder="QTY" aria-label="QTY" aria-describedby="basic-addon2">
	<button class="btn btn-outline-secondary addbt" type="button">Add To Cart</button>
	<button class="btn btn-outline-secondary detailbt" type="button">Details</button>
	</div>
	  
	  </div>
</div>
</div>
</div>

</div>
</div>
	


	 <?php	
 
	 
	  // Check whether top table exists for current site. If exists then include it
	$bottomtable="./images/".$ecom_hostname."/otherfiles/bottomtable.php";
	if(file_exists($toptable))
	{
           include("$bottomtable");
	}
	 
	  ?>  


	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
	echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\" />";

 echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/reset.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
	echo "<link href=\"https://fonts.googleapis.com/css?family=Oswald\" rel=\"stylesheet\">";
	echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/".$ecom_themename.".css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
 	echo "<link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.4.1/css/all.css\" integrity=\"sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz\" crossorigin=\"anonymous\">";
    	//echo "<link href=\"".url_head_link("images/".$ecom_hostname."/".$css_folder."/all.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
	echo "<link href=\"https://fonts.googleapis.com/css?family=Oswald\" rel=\"stylesheet\">";
	echo "<link href=\"https://unpkg.com/aos@2.3.1/dist/aos.css\" rel=\"stylesheet\">"; 
//echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery-2.1.1.js",1)."\"></script>";
echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/jquery.mobile.custom.min.js",1)."\"></script>";
echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/".$scripts_folder."/main.js",1)."\"></script>";
?>

<div class="cd-overlay"></div>
</body>
</html>