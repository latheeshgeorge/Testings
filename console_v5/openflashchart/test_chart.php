<html>
<head>
 
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
 
swfobject.embedSWF(
  "open-flash-chart.swf", "bar_chart",
  "600", "400", "9.0.0", "expressInstall.swf",
  {"data-file":"bargraphdatafile.php"} );
  
 swfobject.embedSWF(
  "open-flash-chart.swf", "pie_chart",
  "600", "400", "9.0.0", "expressInstall.swf",
  {"data-file":"piegraphdatefile.php"} ); 
 
 function show_bar()
 {
	if(document.getElementById('bartd_id').style.display=='none')
		document.getElementById('bartd_id').style.display='';
	else
		document.getElementById('bartd_id').style.display='none';
 }
 
 function show_pie()
 {
	if(document.getElementById('pietd_id').style.display=='none')
		document.getElementById('pietd_id').style.display='';
	else
		document.getElementById('pietd_id').style.display='none';
 }
</script>
</head>
<body>
<table border="1" cellpaddin="0" cellspacing="1" width="100%">
<tr>
<td align="center" colspan='2'>
 <a href="javascript:show_bar()">Bar</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:show_pie()">Pie</a>
</td>
</tr>
	
<tr>
<td align="center" style="display:non1" id="bartd_id">
 <div id="bar_chart"></div>
</td>
<td align="center" style="display:none1" id="pietd_id">	
 <div id="pie_chart"></div>
</td> 
</tr>
 </table>
</body>
</html>
