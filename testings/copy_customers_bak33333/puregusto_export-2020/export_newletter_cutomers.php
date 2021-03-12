<?php
	
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	//$siteid 				= 126;//local
	$siteid 				= 105;//local
	$added_date 			= '2010-01-10 00:00:00';
	$filename ='puregusto_newsletter_customers.csv';
	$fp = fopen ($filename,'w');
	
	fwrite($fp,'"Title","Name","Email","Phone","Join Date","Customer ID","Hidden"'."\n");

	// Get the list of customers existing in the source website
	echo $sql_prod = "SELECT news_customer_id,sites_site_id,news_title,news_custname,news_custemail,news_custphone,news_join_date,customer_id,news_custhide 
					FROM 
						newsletter_customers
					WHERE 
						sites_site_id = $siteid 
					ORDER By news_custname";
	$ret_prod = $db->query($sql_prod);
	
	$i = 1;
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$err_msg 					= '';
			$newscustomer_title				= (stripslashes($row_prod['news_title']));
			$newscustomer_name				= (stripslashes($row_prod['news_custname']));
			$newscustomer_email			= (stripslashes($row_prod['news_custemail']));
			$newscustomer_phone			= (stripslashes($row_prod['news_custphone']));
			$join_date					= (stripslashes($row_prod['news_join_date']));
			$customer_id			= (stripslashes($row_prod['customer_id']));			
			$newscustomer_hide				= (stripslashes($row_prod['news_custhide']));
			
				
			fwrite($fp,add_qts(ucwords($newscustomer_title)).','.add_qts($newscustomer_name).','.add_qts($newscustomer_email).','.add_qts($newscustomer_phone).','.add_qts($join_date).','.add_qts($customer_id).','.add_qts($newscustomer_hide)."\n");
			$i++;
		}
	}
	echo "<br><br>Done";
	fclose($fp);
	$db->db_close();
	
	function add_qts(&$str)
	{
		$str = '"' . str_replace('"', '""', stripslashes($str)) . '"';
		return $str;
	}
?>
