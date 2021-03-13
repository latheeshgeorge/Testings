<?php 
	$ecom_bypass_loggedin_check = 1; // done to bypass the "is logged in?" checking inside the session.php file
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");
	// Check whether any ga data avaiable for this website
	$sql_ga = "SELECT * FROM seo_ga_data WHERE sites_site_id = $ecom_siteid LIMIT 1";
	$ret_ga = $db->query($sql_ga);
	if($db->num_rows($ret_ga))
	{
		$row_ga = $db->fetch_array($ret_ga);
		$no_ga_details_found = false;
	}
?>
<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Sources', 'Count'],
          ['Search Engines',<?php echo $row_ga['searchengine_total']?>],
          ['Direct Traffic',<?php echo $row_ga['direct_total']?>],
          ['Referring Sites',<?php echo $row_ga['refering_total']?>]
        ]);

        var options = {
          'title': 'Traffic Sources',
		  'width': '315',
		  'height': '200',
		  'fontSize':'9',
		  'is3D':'true',
		  'legend':{'position':'right'},
		  'chartArea':{'left':'0','top':'10','width':'100%','height':'100%'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>	 
</head>
<body>
<div id="chart_div" style="width: 335px; height: 200px;"></div>
</body>
</html>
