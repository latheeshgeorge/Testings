<?php
    include_once('../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../config_db.php';
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$startval = $_REQUEST['st'];
	     $sql_site = "SELECT site_id  
						FROM 
							sites WHERE site_domain ='www.peterfieldonlinegolfshop.co.uk' LIMIT 1
						";
	    $ret_site = $db->query($sql_site);
		while($row_siteid = $db->fetch_array($ret_site))
		{
		   $site_idd = $row_siteid['site_id'];
		   write_orderemails($site_idd);
		}
	    function write_orderemails($site_id)
		{
			global $startval,$db;
			//$email_path	= '/var/www/html/webclinic/bshop4/images';//local
			$email_path	= '/var/www/vhosts/bshop4.co.uk/httpdocs/images';//live
			$sql_site_name = "SELECT site_id,site_domain
							FROM 
								sites 
							WHERE 
								site_id = $site_id 
							LIMIT 
								1";
		    $ret_site_name = $db->query($sql_site_name);
			if($db->num_rows($ret_site_name))
			{	
				$row_site_name = $db->fetch_array($ret_site_name);
				$ecom_hostname = $row_site_name['site_domain'];
			
			$email_path			.= '/'.$ecom_hostname.'/email_messages';
			if(!file_exists($email_path)) mkdir($email_path, 0777); 
			$sql_email = " SELECT b.email_id,b.email_message,email_messagepath FROM orders a,order_emails b WHERE 
						a.sites_site_id = $site_id 
						AND a.order_id = b.orders_order_id 
					ORDER BY b.email_id 
					LIMIT 
						$startval,10000";
			$ret_sql = $db->query($sql_email);
			if($db->num_rows($ret_sql))
			{	
				$flv_path = $email_path.'/order_emails'; 
				if(!file_exists($flv_path)) 
				{
					mkdir($flv_path, 0777);
					//echo '<br> Created folder - '.$flv_path;
				}
				while($row_email= $db->fetch_array($ret_sql))
				{
                                  if($row_email['email_message']!='')
                                  {
                                      $FileName = $row_email['email_id'].".txt";
                                      $fh = fopen($flv_path.'/'.$FileName, 'w') ;
				     if(!$fh)
					echo "<br>Cannot open file for writing - ".$flv_path.'/'.$FileName;
                                      fwrite($fh, $row_email['email_message']);
                                      fclose($fh);
                                      $update_array = array();
                                      $update_array['email_messagepath'] = 'email_messages/order_emails/'.$row_email['email_id'].".txt";
                                      $db->update_from_array($update_array, 'order_emails', 'email_id', $row_email['email_id']);
                                  }
				}
			}
			echo "Order emails write successfully for ".$ecom_hostname." <br />";
		  }
		  else
		  {
		    echo "Error!!";
		  }
	  }
	
?>
