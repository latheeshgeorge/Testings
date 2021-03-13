<?php
	set_time_limit(0);
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
	
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=productlist.csv;");
	header("Accept-Ranges:bytes");
	
	$mon_arr = array('6'=>'August 2012','5'=>'September 2012','4'=>'October 2012','3'=>'November 2012');
	print "Date,Order id,Product, Qty"."\n";
	for($ii=6;$ii>2;$ii--)
	{
		print "For the Month of ".$mon_arr[$ii]."\n";
		if($month_days==31)
			$month_days	= 30;
		else	
			$month_days	= 31;
		$mon_start 	= date("m")-$ii;
		$start 		= date("Y-m-d",mktime(0, 0, 0, $mon_start, 1, date("Y")));
		$end 		= date("Y-m-d",mktime(0, 0, 0, $mon_start, $month_days, date("Y")));
		$sql_order_total = "SELECT *,DATE_FORMAT(order_date,'%d-%b-%Y %r') as date_formated 
								FROM 
									orders 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND order_date >='$start 00:00:00' 
									AND order_date <= '$end 23:59:59' 
									AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
									AND order_paystatus NOT IN (
										'Pay_Failed','REFUNDED','PROTX','HSBC',
										'GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY',
										'PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";
		$ret_order_total = $db->query($sql_order_total);
		if($db->num_rows($ret_order_total))
		{
			while ($row_order_total = $db->fetch_array($ret_order_total))
			{
				print $row_order_total['date_formated'];
				print ",".$row_order_total['order_id']."\n";
				$sql_det = "SELECT orderdet_id,products_product_id,product_name,order_orgqty FROM order_details WHERE orders_order_id = ".$row_order_total['order_id']." ORDER BY orderdet_id";
				$ret_det = $db->query($sql_det);
				while ($row_det = $db->fetch_array($ret_det))
				{
					$prodname = $row_det['product_name'];
					// get the variables if any
					$sql_var = "SELECT * FROM order_details_variables WHERE order_details_orderdet_id = ".$row_det['orderdet_id'];
					$ret_var = $db->query($sql_var);
					$var_str = '';
					if($db->num_rows($ret_var))
					{
						while ($row_var = $db->fetch_array($ret_var))
						{
							$var_str .= ' - '.stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']);
						}
					}
					if ($var_str !='')
						$prodname .= '('.$var_str.')';
					print ",,".$prodname;
					print ",".$row_det['order_orgqty']."\n";
				} 
				
			}
		}								
      }      
?>