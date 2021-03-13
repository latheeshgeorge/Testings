<?php
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
		
	$cust_arr = array();
	
	$check_date = date('Y-m-d',mktime(0,0,0,date('m')-18,1,date('Y')));
	$sql_ord = "SELECT distinct customers_customer_id FROM orders where sites_site_id = $ecom_siteid AND customers_customer_id !=0 AND order_date >= '$check_date 00:00:00'";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		while ($row_ord = $db->fetch_array($ret_ord))
		{
			$cust_arr[] = $row_ord['customers_customer_id'];
		}		
	}
	$rowcnt = 1;
	$result_arr = array();
	if(count($cust_arr))
	{
		
		$sql_query = "SHOW columns FROM newsletter_customers";
		$ret_query = $db->query($sql_query);
		if($db->num_rows($ret_query))
		{
			$title_str = '';
			while ($row_query = $db->fetch_array($ret_query))
			{
				if($title_str!='')
					$title_str .=',';
				$title_str .= $row_query[0];
			} 
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=garraways_deleted_newscustlist.csv;");
		header("Accept-Ranges:bytes");
		print $title_str."\n";
		$str = implode(',',$cust_arr);
		$cond_custid = array();
		$sql_cust = "SELECT customer_id  
						FROM 
							customers 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND customer_hide = 0 
							AND customer_activated = 1 
							AND customer_id NOT IN ($str)";
		$ret_cust = $db->query($sql_cust);
		if($db->num_rows($ret_cust))
		{
			while ($row_cust = $db->fetch_assoc($ret_cust))
			{
				$cond_custid[] = $row_cust['customer_id'];
			}	
			$str = implode(',',$cond_custid);
			$sql_newscust = "SELECT * 
						FROM 
							newsletter_customers 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND customer_id IN ($str)";
			$ret_newscust = $db->query($sql_newscust);
			if($db->num_rows($ret_newscust))
			{
				while ($row_newscust = $db->fetch_assoc($ret_newscust))
				{
					$row_data = '';
					foreach ($row_newscust as $k=>$v)
					{
						if($row_data!='')
							$row_data .= ',';
						$data = '"'.$v.'"';	
						$row_data .= $data;
					}
					print $row_data."\n";
					$result_arr[] = 1;
				}	
			}
			
			
		}
	}	
	else
		echo "<br><br>No newsletter customers found";
	exit;
?>
