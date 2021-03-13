<?
set_time_limit(0); 
// Google sitemap generator
require("functions/functions.php");
require("includes/session.php");
require("includes/urls.php");
require("config.php");
require_once("config.php");
$cat_id = $_REQUEST['catrss_id'];

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}

// Calling the function to get the details of default currency
$default_Currency_arr	= get_default_currency();

// Get the details of default currency
$default_crr_code		= $default_Currency_arr['curr_code'];
$symbol = $default_crr_code;
$kw_limit	= 5;
$meta_arr = get_PageMetaDetails('CATEGORY_PAGE',$kw_limit,$row_metatemplate,array('category_id'=>$cat_id));	

$sql_cat = "SELECT category_name FROM product_categories WHERE category_id = $cat_id";
$ret_cat = $db->query($sql_cat);
if ($db->num_rows($ret_cat))
{
		$row_cat 		= $db->fetch_array($ret_cat);
		$showname	= stripslashes($row_cat['category_name']); 
}
$sr_arr = array("&","<",">","/","'",'"');
$rp_arr = array("&amp;","&lt;","&gt;","","","");
header('Content-type: text/xml');
print("<" . "?" . 'xml version="1.0" encoding="UTF-8" standalone="no"' . "?" . ">\n");
?>

<rss version="2.0" xmlns:atom="<?php echo $ecom_selfhttp?>www.w3.org/2005/Atom">
	<channel>
    	<title><? echo utf8_encode(str_replace($sr_arr,$rp_arr,$meta_arr['title'])); ?></title>
        <link><?php echo url_category_rss($cat_id,$showname,1)?></link>
		<category><?=$showname?></category>
        <description><? echo utf8_encode(str_replace($sr_arr,$rp_arr,$meta_arr['desc']))?></description>
		<atom:link href="<?php echo url_category_rss($cat_id,$showname,1)?>" rel="self" type="application/rss+xml" />
<?php
	$desc  		 = "";
	
	$sql_product = "SELECT a.product_id, a.product_name , a.product_shortdesc, a.product_model,a.product_webprice 
						FROM products a, product_category_map b 
							WHERE a.sites_site_id=$ecom_siteid AND a.product_hide='N' AND a.product_id=b.products_product_id 
								  AND b.product_categories_category_id=".$cat_id;
	
	$res_product = $db->query($sql_product);
	while($row_product = $db->fetch_array($res_product)) {
		
		
		$desc .= "<b>Product Name: ".$row_product['product_name']."</b><br>";
		$desc .= "<b>Product Short Description: ".$row_product['product_shortdesc']."</b><br>";


		$desc .= "<b>Model: ".$row_product['product_model']."</b><br>";
		

		$desc .= "<b>Web Price: ".$symbol.' '.$row_product['product_webprice']."</b><br>";
		
		$desc = utf8_encode(str_replace($sr_arr,$rp_arr,$desc));
		
		echo "\t\t<item>\n\t\t\t<title>".utf8_encode(str_replace($sr_arr,$rp_arr,$row_product['product_name']))."</title>\n\t\t\t<link>".url_product($row_product['product_id'],$row_product['product_name'],1)."</link>\n\t\t\t<guid isPermaLink=\"true\">".url_product($row_product['product_id'],$row_product['product_name'],1)."</guid>\n\t\t\t<description>".$desc."</description>\n\t\t</item>\n";
		//echo "\t\t<item>\n\t\t\t<name>".utf8_encode(str_replace($sr_arr,$rp_arr,$row_product['product_name']))."</name>\r\n\t\t\t<description>".$desc."</description>\r\n\t\t\t</item>\n";
		$desc = '';				
	}
?>
	</channel>
</rss>
