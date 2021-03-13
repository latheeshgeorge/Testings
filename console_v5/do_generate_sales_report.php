<?php
	
	/*#################################################################
	# Script Name 	: do_generate_sales_report.php
	# Description 	: file to generate the sales report details in csv file
	# Coded by 		: Sny
	# Created on	: 09-May-2016
	# Modified by	: Sny
	# Modified On	: 09-May-2016
	#################################################################*/
	
	set_time_limit(0);

	include_once("functions/functions.php");
	include('session.php');
	require_once("sites.php");
	require_once("config.php");
	include_once("import_export_variables.php");
	$headers 	= array();
	$data 		= array();

	if($ecom_selfssl_active==1)
	{
		$http = "https://";
	}
	else
	{
		$http = "http://";
	}
	
	$reqfrom = str_replace($http.$ecom_hostname,'',$_SERVER['HTTP_REFERER']);

	if ($reqfrom!='/console_v5/home.php?request=orders&fpurpose=salesrepshow')
	{
		echo 'Sorry Invalid Parameter';
		exit;
	}
	
	$headers = array('Date','Order Id','Actual Order Total' ,'Tax Value','Order Total Without Tax','Total Delivery Charge','Payment Status');
	
	$frm_date = trim($_REQUEST['ord_fromdate']);
	$to_date = trim($_REQUEST['ord_todate']);
	
	if($frm_date=='' or $to_date=='')
	{
		exit;
	}
	$frm_date_arr 	= explode('-',$frm_date);
	$to_date_arr 	= explode('-',$to_date);
	$date_start 	= $frm_date_arr[2]."-".$frm_date_arr[1]."-".$frm_date_arr[0];
	$date_end 		= $to_date_arr[2]."-".$to_date_arr[1]."-".$to_date_arr[0];
	$filename 		= "sales_report_".$frm_date."_to_".$to_date;
	header("Content-Type: text/plain");
	header("Content-Disposition: attachment; filename=$filename.csv");
	array_walk($headers, "add_quotes");
	print implode(",", $headers) . "\r\n";

	
	
	$sql_rep = "SELECT order_id,DATE_FORMAT(order_date,'%d %b %Y') as ordate ,order_totalprice,order_tax_total,order_deliverytotal,order_paystatus 
					FROM 
						orders 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND order_date >='$date_start 00:00:00' 
						AND order_date <= '$date_end 23:59:59' 
						AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
						AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') 
					ORDER BY 
						order_id";
	$ret_rep = $db->query($sql_rep);
	if($db->num_rows($ret_rep))
	{
		$actual_total = $tax_total = $exmt_total = $del_total =0;
		$i=1;
		while($row_rep = $db->fetch_array($ret_rep))
		{
			$actual 	= $row_rep['order_totalprice'];
			$tax 		= $row_rep['order_tax_total'];
			$exmt 		= ($row_rep['order_totalprice']-$row_rep['order_tax_total']);
			
			$data = array();
					
			$data[] = $row_rep['ordate'];		
			$data[] = $row_rep['order_id'];		
			$data[] = $actual;		
			$data[] = $tax;		
			$data[] = $exmt;
			$data[] = $row_rep['order_deliverytotal'];
			$data[] = getpaymentstatus_Name($row_rep['order_paystatus']);
			
			$actual_total 	+=  $actual;
			$tax_total 		+=  $tax;
			$exmt_total 	+=  $exmt;
			$del_total 		+=  $row_rep['order_deliverytotal'];
					
			//print_r($data);
			print implode(",", $data) ."\r\n";
			$i++;
		}	
		$headers = array('','','' ,'','');
		array_walk($headers, "add_quotes");
		print implode(",", $headers) . "\r\n";
		$headers = array('','Totals',$actual_total ,$tax_total,$exmt_total,$del_total,'');
		array_walk($headers, "add_quotes");
		print implode(",", $headers) . "\r\n";
	}	
	else
	{
		print "Sorry!! No data found" ."\r\n";
	}						
?>
