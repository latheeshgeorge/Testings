<?php 
	require("../../../functions/functions.php");
	require("../../../includes/session.php");
	include ("../../../config.php");
	include("../../../includes/urls.php");
	global $ecom_hostname,$ecom_theme_name;
	$prod_id = $_REQUEST['pid'];
	// Get the name of product
	$sql_prods = "SELECT product_name 
					FROM 
						products 
					WHERE 
						product_id=$prod_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_prods = $db->query($sql_prods);
	if($db->num_rows($ret_prods))
	{
		$row_prods = $db->fetch_array($ret_prods);
		$prodname	= stripslash_normal($row_prods['product_name']);
	}
	else
	{
		echo '<script type="text/javascript">window.location=\'invalid_input.html\';';
		exit;
	}
	// Check whether size chart details exists for current product
	 $sql = "SELECT heading_title, product_sizechart_heading.heading_id
				FROM 
					product_sizechart_heading, product_sizechart_heading_product_map 
				WHERE 
					product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
					AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
					AND product_sizechart_heading_product_map.products_product_id = '".$prod_id."' 
				ORDER BY 
					product_sizechart_heading_product_map.map_order" ;
	 $res = $db->query($sql);
	 while(list($heading_title, $heading_id) = $db->fetch_array($res))
	 {
		
		$heading[] = $heading_title;
		$charsql = "SELECT size_value 
					 FROM 
						product_sizechart_values 
					 WHERE 
						heading_id='".$heading_id."' 
						AND products_product_id = '".$prod_id."' 
						AND sites_site_id  ='".$ecom_siteid."' 
					 ORDER BY 
						size_sortorder ";
				   
		$charres = $db->query($charsql);
		while(list($size_value) = $db->fetch_array($charres))
		{
			$sizevalue[$heading_id][] = $size_value;
		}
	 }

   $cnt =   count($sizevalue);
   $sql_prods = "SELECT product_sizechart_mainheading 
					FROM 
						products 
					WHERE 
						product_id = '".$prod_id."'
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
		$row_prods 				= $db->fetch_array($ret_prods);
		$sizechartmain_title 	= stripslash_normal($row_prods['product_sizechart_mainheading']); 
	}
	if($sizechartmain_title == '')
	{
		$sizechartmain_title 	= stripslash_normal($Settings_arr['product_sizechart_default_mainheading']);
	}
		
	if(count($sizevalue))
	{
		foreach($sizevalue as $k=>$v)
		{
			$cnt_hd = count($v);
		}
	}
	$url = 'http://'.$_REQUEST['h'].'/images/'.$_REQUEST['h'].'/'.$_REQUEST['f'];
?>
<HTML>
<HEAD>
 <TITLE>Zoom</TITLE>
 <link href="<? url_head_link("images/".$ecom_hostname."/css/".$ecom_themename.".css")?>" media="screen" type="text/css" rel="stylesheet" />
 <script language="javascript" type="text/javascript">
<!--
var i=0;
function resize()
{
  i=0;
  //  if (navigator.appName == 'Netscape') i=20;
  if (window.navigator.userAgent.indexOf('MSIE 6.0') != -1 && window.navigator.userAgent.indexOf('SV1') != -1) {
      i=30; //This browser is Internet Explorer 6.x on Windows XP SP2
  } else if (window.navigator.userAgent.indexOf('MSIE 6.0') != -1) {
      i=0; //This browser is Internet Explorer 6.x
  } else if (window.navigator.userAgent.indexOf('Firefox') != -1 && window.navigator.userAgent.indexOf("Windows") != -1) {
      i=25; //This browser is Firefox on Windows
  } else if (window.navigator.userAgent.indexOf('Mozilla') != -1 && window.navigator.userAgent.indexOf("Windows") != -1) {
      i=45; //This browser is Mozilla on Windows
  } else {
      i=80; //This is all other browsers including Mozilla on Linux
  }

  imgHeight = document.images[0].height+40-i;
  imgWidth = document.images[0].width+20;

  var height = screen.height;
  var width = screen.width;
  var leftpos = width / 2 - imgWidth / 2;
  var toppos = height / 2 - imgHeight / 2;

    frameWidth = imgWidth;
    frameHeight = imgHeight+i;

  window.moveTo(leftpos, toppos);
  window.resizeTo(frameWidth,frameHeight+i);

  self.focus();
}
//--></script>
<style type="text/css">
centeredContent, TH, #cartEmptyText, #cartBoxGVButton, #cartBoxEmpty, #cartBoxVoucherBalance, #navCatTabsWrapper, #navEZPageNextPrev, #bannerOne, #bannerTwo, #bannerThree, #bannerFour, #bannerFive, #bannerSix, #siteinfoLegal, #siteinfoCredits, #siteinfoStatus, #siteinfoIP, .center, .cartRemoveItemDisplay, .cartQuantityUpdate, .cartQuantity, .cartTotalsDisplay, #cartBoxGVBalance, .leftBoxHeading, .centerBoxHeading,.rightBoxHeading, .productListing-data, .accountQuantityDisplay, .ratingRow, LABEL#textAreaReviews, #productMainImage, #reviewsInfoDefaultProductImage, #productReviewsDefaultProductImage, #reviewWriteMainImage, .centerBoxContents, .specialsListBoxContents, .categoryListBoxContents, .additionalImages, .centerBoxContentsSpecials, .centerBoxContentsAlsoPurch, .centerBoxContentsFeatured, .centerBoxContentsNew, .gvBal, .attribImg 
{
	text-align: center;
}
</style>
</head>
<body id="popupImage" class="centeredContent" onLoad="resize();">

<table width="100%" border="0" cellpadding="2" cellspacing="0">
<tr>
	<td  colspan="4" ><div  class="sizechart_logo" align="left"><img src="<?php url_site_image('logo.gif')?>"></div>
<div id="sizechart_prodname_div" class="sizechart_productname"><?php echo $prodname?></div>
<div id="sizechart_close_div" class="sizechart_close" align="RIGHT">
<a href="javascript:window.close()" title="Click to Close"><img src="<?php url_site_image('comp-icn.gif')?>" width="22" height="22"></a></div></td>
    </tr>
<tr>
<?php 
foreach($heading AS $val)
{ 
?>
	<td align="center"  class="productsizechartheading" ><?PHP echo $val; ?></td>
<?php
} 
?>
</tr>
 <?php 
for($i=0; $i<$cnt_hd; $i++)
{
	$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
?>
<tr>
<?php
foreach($sizevalue as $k=>$v)
{
?>
  <td class="<?php echo $cls; ?>" align="center" ><?PHP echo ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; ?></td>
<?php
} 
?>
</tr>
<? 
}
?>
</table>
<div id="sizechart_close_div" class="sizechart_close">
<a href="javascript:window.close()" title="Click to Close">[x] Close</a>
</div>
</body>
</html>
