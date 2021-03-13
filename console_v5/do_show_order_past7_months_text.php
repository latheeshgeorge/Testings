<?php 
	$ecom_bypass_loggedin_check = 1; // done to bypass the "is logged in?" checking inside the session.php file
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");
?>
<html>
<head>
<link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="admin_data_table_hdr" width="30%">Month</td>
<td class="admin_data_table_hdr" width="30%">Order Count</td>
<td class="admin_data_table_hdr" width="40%">Payment Amount</td>
</tr>
<?php
$month_arr = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
$row_val = 0;
if($ecom_siteid==61)
	$gp_max = 15;
else
	$gp_max = 25;
for($i=0;$i<$gp_max;$i++){

$date_start = date("Y-m-d",mktime(0, 0, 0, date("m")-$i, 1, date("Y")));
$date_end = date("Y-m-d",mktime(0, 0, 0, (date("m")-$i)+1, 0, date("Y")));
/*$sql_order_total = "SELECT count(order_id) as cnt, SUM(order_totalprice) as tot 
								FROM 
									orders 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND order_date >='$date_start 00:00:00' 
									AND order_date <= '$date_end 23:59:59' 
									AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
									AND order_paystatus NOT IN ('Pay_Failed','REFUNDED','PROTX','HSBC','GOOGLE_CHECKOUT','WORLD_PAY','ABLE2BUY','PROTX_VSP','REALEX','NOCHEX','PAYPAL_EXPRESS','PAYPALPRO') ";*/
$sql_order_total = "SELECT count(order_id) as cnt, SUM(order_totalprice) as tot 
								FROM 
									orders 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND order_date >='$date_start 00:00:00' 
									AND order_date <= '$date_end 23:59:59' 
									AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
									AND order_paystatus IN ('Paid','VERIFIED','COMPLETE','FULFILLED') ";
//echo $sql_order_total;
$res_order_total = $db->query($sql_order_total);
while($row_order_total = $db->fetch_array($res_order_total)) {
	$date_arr  = explode('-',$date_start);
	$show_date = $month_arr[$date_arr[1]].'-'.$date_arr[0];
	$cls = ($row_val%2==0)?'admin_data_table_tdA':'admin_data_table_tdB';
	$row_val++;
?>
<tr>
<td class="<?php echo $cls?>"><?=$show_date?></td>
<td class="<?php echo $cls?>"><?=$row_order_total['cnt']?></td>
<td class="<?php echo $cls?>"><?=display_price($row_order_total['tot'])?></td>
</tr>
<?php
}
}
?>
</table>
</body>
</html>
