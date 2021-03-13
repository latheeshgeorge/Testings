<?php
	include_once("functions/functions.php");
	include('includes/session.php');
	require_once("config.php");
	
	if(check_IndividualSslActive())
	{
		$ecom_selfhttp = "https://";
	}
	else
	{
		$ecom_selfhttp = "http://";
	}
	
	//print("<" . "?" . 'xml version="1.0" encoding="UTF-8"' . "?" . ">\n");
?>
<?php /*?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0">
<url>
<loc>http://<?php echo $ecom_hostname?>/geo-sitemap.kml</loc>
<geo:geo>
<geo:format>kml</geo:format>
</geo:geo>
</url>
</urlset><?php */
$xml_value = '&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;urlset xmlns="'.$ecom_selfhttp.'www.sitemaps.org/schemas/sitemap/0.9" xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0"&gt;
&lt;url&gt;
&lt;loc&gt;'.$ecom_selfhttp.$ecom_hostname.'/geo-sitemap.kml&lt;/loc&gt;
&lt;/url&gt;
&lt;/urlset&gt;';
echo html_entity_decode($xml_value);
/*
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:geo="http://www.google.com/geo/schemas/sitemap/1.0">
	<url>
		<loc>http://www.bshop4.co.uk/locations.kml</loc>
	</url>
</urlset>
*/

?>
