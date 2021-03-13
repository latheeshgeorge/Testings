<?
set_time_limit(0);  
// Google sitemap generator
// Cobbled together 09/06/2005 
// Author: Sunil George

include_once("functions/functions.php");
include('includes/session.php');
include('includes/urls.php');
require_once("config.php");

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}

// gSiteMap_outputTerm - Generates an entry in the sitemap
//  parameters $priority, $changeFreq and $lastMod are optional
//  set to 0 if not required.
function gSiteMap_outputTerm($pageUrl, $priority, $changeFreq, $lastMod)
{
	print("<url>\n");
	$pageUrl = str_replace("&","&amp;",$pageUrl);
	$pageUrl = str_replace("'","&apos;",$pageUrl);
	$pageUrl = str_replace("\"","&quot;",$pageUrl);
	$pageUrl = str_replace(">","&gt;",$pageUrl);
	$pageUrl = str_replace("<","&lt;",$pageUrl);
	print(" <loc>" . utf8_encode($pageUrl) . "</loc>\n");
	if($priority != 0)
	{
		print(" <priority>" . $priority . "</priority>\n");
	}
	if($changeFreq != "")
	{
		print(" <changefreq>" . $changeFreq . "</changefreq>\n");
	}
	if($lastMod != 0)
	{
		print(" <lastmod>" . $lastMod . "</lastmod>\n");
	}
	print("</url>\n");
}

function gSiteMap_listSiteMap()
{
	global $ecom_hostname,$ecom_selfhttp;
	
	$pageUrl = $ecom_selfhttp."$ecom_hostname/sitemap.html";
	gSiteMap_outputTerm($pageUrl, 0.3, "monthly", 0);
}

function gSiteMap_listStatics()
{
	global $ecom_hostname, $db, $ecom_siteid;
	
	$staticQuer = "SELECT page_id, title 
								FROM 
									static_pages 
								WHERE 
									sites_site_id='$ecom_siteid' 
									AND pname<>'Home'
									AND page_type='Page'  
									AND hide=0";
	$staticResult = $db->query($staticQuer);
	
	while(list($pageId, $pName) = $db->fetch_array($staticResult))
	{
		$url = url_static_page($pageId,$pName,1);
		//$pageUrl = "http://$ecom_hostname/index.php?option=static&amp;page_id=" . urlencode($pageId) . "&amp;pname=" . urlencode($pName);
		$pageUrl = $url[1];
		gSiteMap_outputTerm($pageUrl, 0.8, "weekly", 0);
	}
}

function gSiteMap_listProducts()
{
	global $db, $ecom_siteid, $ecom_hostname;
	
	$prodQuer = "SELECT product_id,product_name 
							FROM 
								products 
							WHERE 
								sites_site_id='$ecom_siteid' 
								AND product_hide='N' 
								AND product_subproduct=0";
	$prodResult = $db->query($prodQuer);
	
	while(list($prodId,$prodNum) = $db->fetch_array($prodResult))
	{
		//$pageUrl = "http://$ecom_hostname/?ecom_siteid=$ecom_siteid&amp;product_id=" . urlencode($prodNum) . "&amp;option=Prod_detail";
		$pageUrl =url_product($prodId,$prodNum,1);
		gSiteMap_outputTerm($pageUrl, 0.9, "weekly", 0);
	}
}
function gSiteMap_listShops()
{
	global $db, $ecom_siteid, $ecom_hostname;
	
	$prodQuer = "SELECT shopbrand_id,shopbrand_name 
							FROM 
								product_shopbybrand 
							WHERE 
								sites_site_id='$ecom_siteid' 
								AND shopbrand_hide=0";
	$prodResult = $db->query($prodQuer);
	
	while(list($shopId,$shopNam) = $db->fetch_array($prodResult))
	{
		//$pageUrl = "http://$ecom_hostname/?ecom_siteid=$ecom_siteid&amp;product_id=" . urlencode($prodNum) . "&amp;option=Prod_detail";
		$pageUrl = url_shops($shopId,$shopNam,1);
		gSiteMap_outputTerm($pageUrl, 0.9, "weekly", 0);
	}
}

function gSiteMap_listHome()
{
	global $ecom_hostname,$ecom_selfhttp,$ecom_siteid;;
	$pageUrl = $ecom_selfhttp."$ecom_hostname/";
	if($ecom_siteid==104)
	{
	gSiteMap_outputTerm($pageUrl, 1, "always", 0);
	}
	else
	{
	gSiteMap_outputTerm($pageUrl, 1, "daily", 0);
	}
}


function gSiteMap_listCategories()
{
	global $db, $ecom_hostname, $ecom_siteid;
	
	$catQuery = "SELECT category_id,category_name  
							FROM 
								product_categories 
							WHERE 
								sites_site_id='$ecom_siteid' 
								AND category_hide=0";
	$catResult = $db->query($catQuery);
	
	while(list($catId, $cName) = $db->fetch_array($catResult))
	{
		$pageUrl = url_category($catId,$cName,1);
		if($ecom_siteid==104)
		{
			gSiteMap_outputTerm($pageUrl, 0.7, "daily", 0);
		}
		else
		{
			gSiteMap_outputTerm($pageUrl, 0.7, "weekly", 0);
		}
	}
}

function gSiteMap_listSearchKeywords()
{
	global $db, $ecom_hostname, $ecom_siteid;
	
	$keyQuery = "SELECT search_keyword, search_id 
							FROM 
								saved_search 
							WHERE 
								sites_site_id='$ecom_siteid' 
							ORDER BY 
								search_count 
							DESC";
	$keyResult = $db->query($keyQuery);
	while(list($searchkeyword, $search_id) = $db->fetch_array($keyResult))
	{
		$pageUrl = url_savedsearch($search_id,$searchkeyword,1);
		
			gSiteMap_outputTerm($pageUrl, 0.6, "monthly", 0);
	}
}

function gSiteMap_Savedsearch()
{
	global $ecom_hostname,$ecom_webclinic,$ecom_siteid,$ecom_selfhttp;;
	global $ecom_hostname;
	$pageUrl = $ecom_selfhttp."$ecom_hostname/saved-search.html";
	gSiteMap_outputTerm($pageUrl, 0.5, "monthly", 0);
	
}

function gSiteMap_Getkml()
{
	global $ecom_hostname,$ecom_webclinic,$ecom_siteid;
	global $ecom_hostname,$ecom_selfhttp;;
	$kml_location = IMAGE_ROOT_PATH."/".$ecom_hostname."/otherfiles/location.kml";
	if(file_exists($kml_location))
	{?>
			<url>
				<loc><?php echo $ecom_selfhttp.$ecom_hostname?>/locations.kml</loc>
				<lastmod><?php echo date('Y-m-d')?></lastmod>
				<changefreq>monthly</changefreq>
				<priority>0.5</priority>
			</url>
    <?php
	}
}	


header('Content-type: text/xml');

$ecom_hostname = $_SERVER['HTTP_HOST'];//$_SERVER["HTTP_HOST"];

$query = "SELECT site_id 
					FROM 
						sites 
					WHERE 
						site_domain='$ecom_hostname' 
					LIMIT 
						1";
$result = $db->query($query);
if(!$db->num_rows($result))
{
  echo "Failed to find database entry for site";
}

list ($ecom_siteid) = $db->fetch_array($result);


print("<" . "?" . 'xml version="1.0" encoding="UTF-8"' . "?" . ">\n");
echo "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">";
gSiteMap_listHome();
gSiteMap_listStatics();
gSiteMap_listCategories();
gSiteMap_listSiteMap();
gSiteMap_listProducts();
gSiteMap_listSearchKeywords();
gSiteMap_Savedsearch();
gSiteMap_listShops();
gSiteMap_Getkml();
echo "</urlset>";
