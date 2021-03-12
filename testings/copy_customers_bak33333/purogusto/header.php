<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$src_siteid 	= 61; // from siteid - garraways.co.uk
	$dest_siteid	= 105; // to siteid  - purogusto.bshop4.co.uk
	
	// Local variables
	define('ORG_DOCROOT','/var/www/html/webclinic/bshop4');
	
	// Live variables
	//define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');
	//define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	
	define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	

?>
