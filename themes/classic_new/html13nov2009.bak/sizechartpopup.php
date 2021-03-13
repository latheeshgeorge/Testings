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
<html><head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
 <TITLE>Zoom</TITLE>
 <link href="<? url_head_link("images/".$ecom_hostname."/css/".$ecom_themename.".css")?>" media="screen" type="text/css" rel="stylesheet" />
<style type="text/css">
centeredContent, TH, #cartEmptyText, #cartBoxGVButton, #cartBoxEmpty, #cartBoxVoucherBalance, #navCatTabsWrapper, #navEZPageNextPrev, #bannerOne, #bannerTwo, #bannerThree, #bannerFour, #bannerFive, #bannerSix, #siteinfoLegal, #siteinfoCredits, #siteinfoStatus, #siteinfoIP, .center, .cartRemoveItemDisplay, .cartQuantityUpdate, .cartQuantity, .cartTotalsDisplay, #cartBoxGVBalance, .leftBoxHeading, .centerBoxHeading,.rightBoxHeading, .productListing-data, .accountQuantityDisplay, .ratingRow, LABEL#textAreaReviews, #productMainImage, #reviewsInfoDefaultProductImage, #productReviewsDefaultProductImage, #reviewWriteMainImage, .centerBoxContents, .specialsListBoxContents, .categoryListBoxContents, .additionalImages, .centerBoxContentsSpecials, .centerBoxContentsAlsoPurch, .centerBoxContentsFeatured, .centerBoxContentsNew, .gvBal, .attribImg 
{
	text-align: center;
}
</style>
</head>
<body id="popupImage" class="centeredContent" >
<div  class="sizechart_logo" align="left"><img src="<?php url_site_image('logo.gif')?>"></div>
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="center">

<tbody>
<tr>
	<td  colspan="4" >
	<div id="sizechart_close_div" class="sizechart_close">
<a href="javascript:window.close()" title="Click to Close"><img src="<?php url_site_image('comp-icn.gif')?>" width="22" height="22"></a></div>
<div id="sizechart_prodname_div" class="sizechart_productname"><?php echo $prodname?></div>
</td>
    </tr>
<tr>
<?php 
foreach($heading AS $val)
{ 
?>
	<td class="productsizechartheading" align="center"><?PHP echo $val; ?></td>
<? }?>
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
<tr>
  <td colspan="4" ><div id="sizechart_close_div2" class="sizechart_close" align="RIGHT"> <a href="javascript:window.close()" title="Click to Close"><img src="<?php url_site_image('comp-icn.gif')?>" width="22" height="22"></a></div></td>
</tr>
</tbody></table>
</body>
</html>
