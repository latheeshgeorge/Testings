<?php
/*######################################################################
# Include Necessary Files
*/
include_once('classes/db_class.inc.php');
require_once('classes/browser.php');
$br = new Browser;
/*######################################################################
# Database Config
#
# dbhost:       SQL Database Hostname
# dbuname:      SQL Username
# dbpass:       SQL Password
# dbname:       SQL Database Name
######################################################################
*/
// For the local system
$dbhost  			= '127.0.0.1';
$dbname  			= 'business1st_bshop4_local';
$dbuname 			= 'root';
$dbpass  			= 'calpine*123';

$db = new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
$db->connect();
$db->select_db();
//Constants
define('SITE_DOCUMENT_ROOT','/var/www/html/httpdocroot/bshop4');
define('SITE_URL','http://bshopadmin4.arys.net');
$site_main_docroot 	= '/var/www/html/httpdocroot/bshop4';
define('IMAGE_ROOT_PATH',$site_main_docroot . '/images');
?>
