<?php 
	$ecom_bypass_loggedin_check = 1; // done to bypass the "is logged in?" checking inside the session.php file
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");
	
	if($ecom_siteid==61)
		$gp_width = 948;
	else
		$gp_width = 848;
/*		
?>
<html>
<head>
<script type="text/javascript" src="openflashchart/js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF(
"openflashchart/open-flash-chart.swf", "my_chart",
"<?php echo $gp_width?>", "400", "9.0.0", "openflashchart/expressInstall.swf",
{"data-file":"do_show_order_past7months_graph.php"},{"wmode" : "transparent"});
</script>
</head>
<body>
<div id="my_chart"></div>
</body>
</html>

<?php
*/

// Building the data to be displayed in the graph
	$xaxis_arr = array();
	$data_arr = array();
	$max_val = 0; 
	$month_arr = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	$curr_symbol = utf8_decode('£');
	if($ecom_siteid==61)
		$gp_max = 15;
	else
		$gp_max = 25;
	  for($i=0;$i<$gp_max;$i++)
	  {
	  	
				  
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
					
						$date_arr = explode('-',$date_start);
						$date_str = $month_arr[$date_arr[1]].' '.$date_arr[0];
						$tot = ($row_order_total['tot'])?$row_order_total['tot']:'0';
						$cnt = intval(($row_order_total['cnt'])?$row_order_total['cnt']:0);
						if($cnt>$max_val) $max_val = $cnt;
											
						//$xaxis_arr[] = $date_str.' <br>Total: '.$curr_symbol.$tot;
						$data_arr[] = array('caption'=>$date_str,'cnt'=>$cnt,'tot'=>$tot);
					}
				  
	  }
 ?>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <div id="chart_div"></div>
  
 <script type="text/javascript">
	google.charts.load('current', {packages: ['corechart', 'bar']});
	google.charts.setOnLoadCallback(drawBasic);

	function drawBasic() {

      
       var data = google.visualization.arrayToDataTable([
        ['date', 'No of Orders', { role: 'style' }, { role: 'annotation' }, { role: 'tooltip' }  ],
        <?php 
        for($ch=0;$ch<count($data_arr);$ch++)
        {
        ?>
			['<?php echo $data_arr[$ch]['caption']?>', <?php echo $data_arr[$ch]['cnt']?>, 'color: #FAAD41;stroke-color: #D2D2D2; stroke-opacity: 0.6; stroke-width: 1;','<?php echo $data_arr[$ch]['cnt']?>','<?php echo $data_arr[$ch]['caption'].' ('.$curr_symbol.$data_arr[$ch]['tot'].')'?>'],
        <?php
		}
        ?>
      ]);
     
      var options = {
		chartArea:{left:30,top:40,bottom:50,width:"85%",height:"400"}, 
		width: 2000,
		height: 400,
		animation: {
			startup:true,
			duration: 300,
			easing: 'inAndOut',
		},
		annotations: {
			textStyle: {
				bold: false,
				color: '#000',     // The color of the text.
				opacity: 1          // The transparency of the text.
			}
		},
		legend:'none',
        title: 'No of Orders By Month (Past 24 months)',
        hAxis: {
          title: '',
		  
        },
        vAxis: {
          title: 'No of Orders',
          minValue:10
        }
      };

      var chart = new google.visualization.ColumnChart(
        document.getElementById('chart_div'));

			chart.draw(data, options);
	}    
</script> 
