<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
		
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	$src_siteid 			= 72; // Local destination site id
	$des_siteid 			= 104; // Local destination site id 
	
	$sql_srcsite = "SELECT site_domain FROM sites WHERE site_id = $src_siteid LIMIT 1";
	$ret_srcsite = $db->query($sql_srcsite);
	if($db->num_rows($ret_srcsite))
	{
		$row_srcsite = $db->fetch_array($ret_srcsite);
		$source_domain = stripslashes($row_srcsite['site_domain']);
	}
	
	$sql_dessite = "SELECT site_domain FROM sites WHERE site_id = $des_siteid LIMIT 1";
	$ret_dessite = $db->query($sql_dessite);
	if($db->num_rows($ret_dessite))
	{
		$row_dessite = $db->fetch_array($ret_dessite);
		$dest_domain = stripslashes($row_dessite['site_domain']);
	}

?>
