<?php
	
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$siteid 				= 105;
	$added_date 			= '2010-01-10 00:00:00';
	$filename ='puregusto_customers.csv';
	$fp = fopen ($filename,'w');
	
	fwrite($fp,'"Account Type","Title","First Name","Middle Name","Surname","Position","Company Name","Company Regno","Company Vatregno","Building Name","Street","Town","County","Phone","Fax","Mobile","Post Code","Email Id","Encrypted Password","Bonus Points","Customer Discount %","Hidden"'."\n");

	// Get the list of customers existing in the source website
	echo $sql_prod = "SELECT customer_accounttype,customer_title,customer_fname,customer_mname,customer_surname,customer_position,
					customer_compname,customer_compregno,customer_compvatregno,customer_buildingname,
					customer_streetname,customer_towncity,customer_statecounty,customer_phone,customer_fax,
					customer_mobile,customer_postcode,country_id,customer_email_7503,customer_pwd_9501,
					customer_bonus,customer_discount,customer_hide
					FROM 
						customers
					WHERE 
						sites_site_id = $siteid 
					ORDER By customer_fname";
	$ret_prod = $db->query($sql_prod);
	
	$i = 1;
	if($db->num_rows($ret_prod))
	{
		while ($row_prod = $db->fetch_array($ret_prod))
		{
			$err_msg 					= '';
			$customer_accounttype		= (stripslashes($row_prod['customer_accounttype']));
			$customer_title				= (stripslashes($row_prod['customer_title']));
			$customer_fname				= (stripslashes($row_prod['customer_fname']));
			$customer_mname				= (stripslashes($row_prod['customer_mname']));
			$customer_surname			= (stripslashes($row_prod['customer_surname']));
			$customer_position			= (stripslashes($row_prod['customer_position']));
			$customer_compname			= (stripslashes($row_prod['customer_compname']));
			$customer_compregno			= (stripslashes($row_prod['customer_compregno']));
			$customer_compvatregno		= (stripslashes($row_prod['customer_compvatregno']));
			$customer_buildingname		= (stripslashes($row_prod['customer_buildingname']));
			
			$customer_streetname		= (stripslashes($row_prod['customer_streetname']));
			$customer_towncity			= (stripslashes($row_prod['customer_towncity']));
			$customer_statecounty		= (stripslashes($row_prod['customer_statecounty']));
			$customer_phone				= (stripslashes($row_prod['customer_phone']));
			$customer_fax				= (stripslashes($row_prod['customer_fax']));
			$customer_mobile			= (stripslashes($row_prod['customer_mobile']));
			$customer_postcode			= (stripslashes($row_prod['customer_postcode']));
			$customer_email				= (stripslashes($row_prod['customer_email_7503']));
			$customer_pass				= (stripslashes($row_prod['customer_pwd_9501']));
			$customer_bonus				= (stripslashes($row_prod['customer_bonus']));
			$customer_discount			= (stripslashes($row_prod['customer_discount']));
			$customer_hide				= (stripslashes($row_prod['customer_hide']));
			
				
			fwrite($fp,add_qts(ucwords($customer_accounttype)).','.add_qts($customer_title).','.add_qts($customer_fname).','.add_qts($customer_mname).','.add_qts($customer_surname).','.add_qts($customer_position).','.add_qts($customer_compname).','.add_qts($customer_compregno).','.add_qts($customer_compvatregno).','.add_qts($customer_buildingname).','.add_qts($customer_streetname).','.add_qts($customer_towncity).','.add_qts($customer_statecounty).','.add_qts($customer_phone).','.add_qts($customer_fax).','.add_qts($customer_mobile).','.add_qts($customer_postcode).','.add_qts($customer_email).','.add_qts($customer_pass).','.add_qts($customer_bonus).','.add_qts($customer_discount).','.add_qts($customer_hide)."\n");
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
