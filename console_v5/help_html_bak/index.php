<?php
	$file 			= $_REQUEST['f'];
	$host 		= $_REQUEST['host'];
	$filepath 	= 'help_files/'.$file.".html";
?>
<html>
<head>
<title>Bshop V4 help Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="help_css/help_style.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:30px;">
<center>
<div style="width:100%" align="center">
<?php 
	include "$filepath";
?>
</div>
</center>
</body>
</html>