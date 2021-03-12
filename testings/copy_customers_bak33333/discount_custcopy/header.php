<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	$src_siteid 			= 72; // Local destination site id
	$des_siteid 			= 104; // Local destination site id 
	
	

?>
