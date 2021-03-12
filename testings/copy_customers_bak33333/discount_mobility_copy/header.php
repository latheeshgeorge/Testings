<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$src_siteid 	= 72; // from siteid - http://www.discount-mobility.co.uk
	$dest_siteid	= 104; // to siteid  - http://bshop4.discount-mobility.co.uk
	
	// Local variables
	//define('ORG_DOCROOT','/var/www/html/webclinic/bshop4');
	
	// Live variables
	define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs');
	//define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	
	define('CONVERT_PATH','/usr/bin'); // path of the convert command to resize the images
	
	function getmydomainname($id)
	{
		global $db;
		$sql = "SELECT site_domain FROM sites WHERE site_id = $id LIMIT 1";
		$ret = $db->query($sql);
		if($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			return $row['site_domain'];
		}
	}

?>
