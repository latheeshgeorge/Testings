<?php
/*######################################################################
# Database Config
#
# dbhost:       SQL Database Hostname
# dbuname:      SQL Username
# dbpass:       SQL Password
# dbname:       SQL Database Name
######################################################################
*/
include_once('classes/db_class.inc.php');	// Page which holds the class for db operations
$dbhost  			= '213.171.200.103';
$dbuname 			= 'healthst_db_user';
$dbpass  			= 'gPAoPb6p0vAYwQjn';
$dbname				= 'healthst_db';
$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
$db->connect();
$db->select_db();

?>
