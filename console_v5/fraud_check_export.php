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
	switch ($_REQUEST['visit_type']) {
		case 0: $where_cond = '';
		break;
		case 1: $where_cond = ' AND b.is_fraud=1';
		break;
		case 2: $where_cond = ' AND b.is_fraud=0';
		break; 
	}
	$sql_se = "SELECT a.adv_url,a.mypage_url FROM fraud_check_urls a WHERE a.site_id=$ecom_siteid AND a.url_id=".$_REQUEST['url_id'];
	$res_se = $db->query($sql_se);
	$row_se = $db->fetch_array($res_se);
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
		$where_cond .= ' AND b.date_time >= '.$start_date_time.' AND b.date_time <= '.$end_date_time;
	} elseif($start_date_time) {
		$where_cond .= ' AND b.date_time >= '.$start_date_time;
	} elseif($end_date_time) {
		$where_cond .= ' AND b.date_time <= '.$end_date_time;
	}
	if($_REQUEST['url_id'] && $_REQUEST['fraud_id']) {
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=fraud_check.csv");
		print "Advertise URL,".$row_se['adv_url']."\r\n";
		print "My Site URL,".$row_se['mypage_url']."\r\n";
		print "Visit Type,".$visit_type_array[$_REQUEST['visit_type']]."\r\n";
		print "Visit Date,Start - ".$_REQUEST['start_date'].",End - ".$_REQUEST['end_date']."\r\n";
		print "\r\n";
		print "IP-Address,Click Date,Click Time,Fraud Click\r\n";
		$sql = "SELECT a.ip_address,b.date_time,b.is_fraud FROM fraud_click_time b,fraud_clicks a WHERE a.site_id=$ecom_siteid AND b.fraud_id=a.fraud_id $where_cond AND a.url_id=".$_REQUEST['url_id']." AND b.fraud_id=".$_REQUEST['fraud_id']." ORDER BY a.ip_address,b.date_time ASC";
		$res = $db->query($sql);
		while($row = $db->fetch_array($res)) {
			print $row['ip_address'].",";
			print date("d-m-Y",$row['date_time']).",";
			print date("H:i:a",$row['date_time']).",";
			print $fraud_array[$row['is_fraud']]."\r\n";
		}
	} else if($_REQUEST['url_id']) {
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=fraud_check.csv");
		print "Advertise URL,".$row_se['adv_url']."\r\n";
		print "My Site URL,".$row_se['mypage_url']."\r\n";
		print "Visit Type,".$visit_type_array[$_REQUEST['visit_type']]."\r\n";
		print "Visit Date,Start - ".$_REQUEST['start_date'].",End - ".$_REQUEST['end_date']."\r\n";
		print "\r\n";
		print "IP-Address,Click Date, Click Time, Fraud Click\r\n";
		$sql = "SELECT a.ip_address,b.date_time,b.is_fraud FROM fraud_click_time b,fraud_clicks a  WHERE a.site_id=$ecom_siteid AND b.fraud_id=a.fraud_id $where_cond AND a.url_id=".$_REQUEST['url_id']." ORDER BY a.ip_address,b.date_time ASC";
		$res = $db->query($sql);
		while($row = $db->fetch_array($res)) {
			print $row['ip_address'].",";
			print date("d-m-Y",$row['date_time']).",";
			print date("H:i:s",$row['date_time']).",";
			print $fraud_array[$row['is_fraud']]."\r\n";
		}
	}
?>