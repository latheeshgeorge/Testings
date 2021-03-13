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
	
	
	// Get the details from sites table
	$sql_site = "SELECT kml_companyname, kml_author 
						FROM 
							sites 
						WHERE 
							site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_site = $db->query($sql_site);
	if($db->num_rows($ret_site))
	{
		$row_site = $db->fetch_array($ret_site);
	}
?>
<kml xmlns="http://www.opengis.net/kml/2.2"  xmlns:atom="http://www.w3.org/2005/Atom">
<Document>
<name><?php echo stripslashes($row_site['kml_companyname'])?></name>
<atom:author>
<atom:name><?php echo stripslashes($row_site['kml_author'])?></atom:name>
</atom:author>
<atom:link href="<?php echo $ecom_selfhttp.$ecom_hostname?>/" />
   <?php
   	// get the list of locations added for the website
	$sql_loc = "SELECT kml_id, kml_location_name, kml_company_name, kml_street, kml_city, kml_state, kml_zip, kml_phone, kml_description,
								kml_latitude, kml_longitude 
						FROM 
							seo_kml_location 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND kml_hide = 0 
						ORDER BY 
							kml_order ASC";
	$ret_loc = $db->query($sql_loc);
	if($db->num_rows($ret_loc))
	{
		while ($row_loc = $db->fetch_array($ret_loc))
		{
   ?>
<Placemark>
<name><?php echo stripslashes($row_loc['kml_location_name'])?></name>
<description>
<![CDATA[
<address>
<a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/"><?php echo stripslashes($row_loc['kml_company_name'])?></a><br />
Address: <?php echo stripslashes($row_loc['kml_street'])?> <?php echo stripslashes($row_loc['kml_city'])?>, <?php echo stripslashes($row_loc['kml_state'])?> <?php echo stripslashes($row_loc['kml_zip'])?><br />
Phone: <?php echo stripslashes($row_loc['kml_phone'])?>
</address>
<?php echo stripslashes($row_loc['kml_description'])?>
]]>
</description>
<Point>
<coordinates><?php echo stripslashes($row_loc['kml_latitude'])?>,<?php echo stripslashes($row_loc['kml_longitude'])?></coordinates>
</Point>
</Placemark>
  <?php
  	}
  }
  ?>
</Document>
</kml>
