<?php
include_once("functions/functions.php");
include('includes/session.php');
require_once("config.php");
// Check whether kml location file exists for current site. If exists then include it
$kml_location = "./images/".$ecom_hostname."/otherfiles/location.kml";
if(file_exists($kml_location))
{
	  readfile($kml_location,'r');
}
?>
