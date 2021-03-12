<?php

	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	
	$dest_siteid	= 105; // to siteid  - purogusto.bshop4.co.uk

	$sql_qry = "SELECT main_id,email_from,email_subject,email_content,send_date,scheduled_date,
					hostname,site_id,site_type 
				FROM newsletter_cron_main ";
	
	$sql_qry = "select count(mail_id) from newsletter_cron_mails";
	
	$ret_qry = $db->query($sql_qry);
	if($db->num_rows($ret_qry))
	{
		while($row_qry = $db->fetch_array($ret_qry))
		{
			echo "<br>======<br>";
			print_r($row_qry);
		}
	}
	else
	{
		echo "No record found";
	}

?>
