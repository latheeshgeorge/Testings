<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	$siteid 			= 105; // Local destination site id puregusto website
	//$siteid 			= 60; // Live destination site id 
	
?>
