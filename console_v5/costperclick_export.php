<?php
	set_time_limit(0);
	if ($_REQUEST['cur_mod']=='')
	{
		echo '<script type="text/javascript">alert("Invalid Parameter");</script>';
		exit;
	}
	include_once("functions/functions.php");
	include('session.php');
	require_once("sites.php");
	require_once("config.php");
	$where_cond = '';
	$visit_type_array = array(0 => 'ALL',1 => 'Fraud',2 => 'Genuine');
	$fraud_array = array(0 => 'N',1 => 'Y');
	
	$sql_urls = "SELECT a.url_id, a.url_mypage,a.url_hidden,a.url_total_clicks,a.url_total_sale_clicks,a.url_total_fraud_clicks,a.url_total_sale_amount,
													b.keyword_word,c.advertplace_name,a.url_setting_rateperclick 
						FROM 
							costperclick_adverturl a,costperclick_keywords b,costperclick_advertplacedon c 
						WHERE 
							a.sites_site_id=$ecom_siteid 
							AND a.url_id=".$_REQUEST['url_id']." 
							AND a.costperclick_keywords_keyword_id=b.keyword_id 
							AND a.costperclick_adverplaced_on_advertplace_id = c.advertplace_id 
						LIMIT 
							1";
	$ret_urls = $db->query($sql_urls);
	if ($db->num_rows($ret_urls))
	{
		$row_urls 				= $db->fetch_array($ret_urls);
		$show_keyword		= stripslashes($row_urls['keyword_word']);
		$show_adverton		= stripslashes($row_urls['advertplace_name']);
		$show_mypage		= stripslashes($row_urls['url_mypage']);
		$show_rate				= $row_urls['url_setting_rateperclick'];
	}
	
	$show_keyword		= stripslashes($row_urls['keyword_word']);
	$show_adverton		= stripslashes($row_urls['advertplace_name']);
	$show_mypage		= stripslashes($row_urls['url_mypage']);
	$show_rate			= $row_urls['url_setting_rateperclick'];
	$where_conditions = " WHERE sites_site_id = $ecom_siteid 
											AND costperclick_month_month_id = ".$_REQUEST['month_id'];	
	if($_REQUEST['url_id'] && $_REQUEST['month_id']) 
	{
		if(trim($_REQUEST['search_ipaddress'])!='')
		{
			$where_conditions .= " AND time_ipaddress LIKE '%".add_slash(trim($_REQUEST['search_ipaddress']))."%' ";
		}
		if($_REQUEST['click_type']!=-1)
		{
			$where_conditions .= " AND time_isfraud = ".$_REQUEST['click_type'];
		}
		if($_REQUEST['start_date']) {
			$start_array = explode("-",$_REQUEST['start_date']);
			$start_date_time = mktime(0,0,0,$start_array[1],$start_array[0],$start_array[2]);
		} else {
			$start_date_time = 0;
		}
		if($_REQUEST['end_date']) {
			$end_array = explode("-",$_REQUEST['end_date']);
			$end_date_time = mktime(23,59,59,$end_array[1],$end_array[0],$end_array[2]);
		} else {
			$end_date_time = 0;
		}
		if ($start_date_time && $end_date_time) {
			$where_conditions .= ' AND time_time >= '.$start_date_time.' AND time_time <= '.$end_date_time;
		} elseif($start_date_time) {
			$where_conditions .= ' AND time_time >= '.$start_date_time;
		} elseif($end_date_time) {
			$where_conditions .= ' AND time_time <= '.$end_date_time;
		}
		 $sql_click = "SELECT  time_time, time_ipaddress, time_isfraud 
		   								FROM 
											costperclick_time 
											$where_conditions 
										ORDER BY 
											$sort_by $sort_order ";
		$ret_click = $db->query($sql_click);									
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=clicks_for_selected_month.csv");
			
		
		print "Keyword,".$show_keyword."\r\n";
		print "Advertised On,".$show_adverton."\r\n";
		print "My Site URL,".$show_mypage."\r\n";
		print "Cost Per Click,".sprintf('%.02f',$show_rate)."\r\n";
		print "\r\n";
		
		print "IP-Address,Click Date,Click Time,Fraud Click\r\n";
		
		while($row = $db->fetch_array($ret_click)) 
		{
			print $row['time_ipaddress'].",";
			print date("d-m-Y",$row['time_time']).",";
			print date("H:i:a",$row['time_time']).",";
			print ($row['time_isfraud']==1)?'Yes':'No'."\r\n";
		}
	} 
	else if($_REQUEST['url_id']) 
	{
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=costperclick_monthly_report.csv");
		
		print "Keyword,".$show_keyword."\r\n";
		print "Advertised On,".$show_adverton."\r\n";
		print "My Site URL,".$show_mypage."\r\n";
		print "Cost Per Click,".sprintf('%.02f',$show_rate)."\r\n";
		print "\r\n";
		$where_conditions = "WHERE 
									sites_site_id=$ecom_siteid ";
		if($_REQUEST['cbo_month']!='')
		{
			$where_conditions .=  " AND month_mon = ".$_REQUEST['cbo_month'];
		}
		if($_REQUEST['cbo_year']!='')
		{
			$where_conditions .=  " AND month_year = ".$_REQUEST['cbo_year'];
		}
		print "Date,Total Genuine Clicks,Total Sale Clicks,Total Fraud Clicks,Total Sale Amount,Total Cost Per Click \r\n";
		$sql_monthly = "SELECT month_id,costperclick_adverturl_url_id,month_total_clicks,month_total_sale_clicks,month_total_fraud_clicks,
												month_total_sale_amount,month_mon,month_year 
											FROM 
												costperclick_month
												$where_conditions 
											ORDER BY 
												$sort_by $sort_order";
		$ret_monthly = $db->query($sql_monthly);
		if($db->num_rows($ret_monthly))
		{
		   $tot_gen_clicks = $tot_fraud_clicks = $tot_sale_amt = $tot_sale_clicks =  $tot_cost = 0;
			while($row = $db->fetch_array($ret_monthly)) 
			{
				$cur_cost = $row['month_total_clicks']*$show_rate;
				print date('M-Y',mktime(0,0,0,$row['month_mon'],1,$row['month_year'])).",";
				print $row['month_total_clicks'].",";
				print $row['month_total_sale_clicks'].",";
				print $row['month_total_fraud_clicks'].",";
				print  $row['month_total_sale_amount'].",";
				print sprintf('%.02f',$cur_cost)."\r\n";
				
				$tot_gen_clicks += $row['month_total_clicks'];
				$tot_fraud_clicks += $row['month_total_fraud_clicks'];
				$tot_sale_amt += $row['month_total_sale_amount'];
				$tot_sale_clicks += $row['month_total_sale_clicks'];
				$tot_cost			+= $cur_cost;
			}
			print 'Total'.',';
			print $tot_gen_clicks.",";	
			print $tot_sale_clicks.",";	
			print $tot_fraud_clicks.",";
			print sprintf('%.02f',$tot_sale_amt).",";
			print sprintf('%.02f',$tot_cost)."\r\n";
		}	
	}
?>