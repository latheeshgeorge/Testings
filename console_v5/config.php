<?php
/*######################################################################
# Include Necessary Files*/
include_once('classes/db_class.inc.php');
include_once('classes/resize_image.php');
//require_once('classes/browser.php');
//$br = new Browser;
/*######################################################################
# Database Config
#
# dbhost:       SQL Database Hostname
# dbuname:      SQL Username
# dbpass:       SQL Password
# dbname:       SQL Database Name
######################################################################
*/
$d = '213.171.200.92';
$dbhost  			= '213.171.200.92';
$dbuname 			= 'unipad_db_user';
$dbpass  			= 'X58hsoO8Ov36c0f';
$dbname				= 'unipad_db';


$default_rowcnt = 15;
$db 					= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
$db->connect();
$db->select_db();
//$sql_1 = $db->query("SELECT @@SESSION.sql_mode;");
//$row1 = $db->fetch_array($sql_1);
//print_r($row1);
$db->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';");

//$sql_11 = $db->query("SELECT @@SESSION.sql_mode;");
//$row11 = $db->fetch_array($sql_11);
//print_r($row11);
define('SITE_DOCUMENT_ROOT',$_SERVER['DOCUMENT_ROOT']);
define('SITE_URL','http://'.$_SERVER['HTTP_HOST']);
define('IMAGE_ROOT_PATH',$_SERVER['DOCUMENT_ROOT'].'/images');
define('CLIENT_IMAGE_URL','http://'.$_SERVER['HTTP_HOST'].'/images');
define('ORG_DOCROOT','/home/storage/452/3266452/user/htdocs');
define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images

//#User Types
$usertype_array = array('sa' => 'System Admin', 'su' => 'System User', 'sm' => 'Shop Manager');
$enable_array   = array(0 => 'No', 1 => 'Yes');
$status_array   = array(0 => 'off.gif', 1 => 'on.gif');

//#Getting site details
$sql_site = "SELECT site_id,site_domain,site_domain_alias,clients_client_id,site_status,themes_theme_id,site_email,console_levels_level_id,site_title,site_intestmod,site_hide_console_error_msgs,site_activate_invoice,site_allpricewithtax,site_delivery_location_country_map,advanced_seo,mobile_themes_theme_id,in_mobile_api,site_grid_enable,enable_searchrefine_category,selfssl_active  FROM sites WHERE $site_where";
$res_site = $db->query($sql_site);
list($ecom_siteid, $ecom_hostname, $ecom_hostname_alias, $ecom_client, $ecom_status,$ecom_themeid,$ecom_email,$ecom_levelid,$ecom_title,$ecom_testing,$ecom_site_hide_console_error_msgs,$ecom_site_activate_invoice,$ecom_allpricewithtax,$ecom_site_delivery_location_country_map,$ecom_advancedseo,$ecom_mobilethemeid,$ecom_site_mobile_api,$ecom_gridenable,$ecom_enable_searchrefine_category,$ecom_selfssl_active) = $db->fetch_array($res_site);
if(!$ecom_siteid) {
	echo 'Error! This domain does not exists in our database';
	exit;
}
if(strtolower($ecom_status) == 'suspended') {
	echo 'Error! This domain is suspended';
	exit;
} else if(strtolower($ecom_status) == 'cancelled') {
	echo 'Error! This domain is cancelled';
	exit;
}
// Image path
$image_path 		= ORG_DOCROOT . '/images/' . $ecom_hostname;

// Decides whether the resize to be applied to the images. Images will be resize if this variabel is set to 1
$Img_Resize			= 1;
?>
