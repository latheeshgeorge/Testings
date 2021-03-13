<?php
	$file = $_REQUEST['f'];
	$host = $_REQUEST['host'];
	$filepath = 'http://'.$host.'/console/help_swf/help_files/'.$file.".swf";
?>
<html>
<head>
<title>Bshop V4 help Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<center>
<body bgcolor="#3D3D3D" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="width:100%;height:100%;margin:0;padding:0;">
<div style="width:100%;padding-top:30px;" align="center">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="950" height="711">
    <param name="movie" value="main1.swf?filepath=<?php echo $filepath?>">
    <param name="quality" value="high">
    <param name="BGCOLOR" value="#3D3D3D">
    <embed src="main1.swf?filepath=<?php echo $filepath?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="959" height="711" bgcolor="#3D3D3D"></embed>
  </object>
</div>
</body>
</center>
</html>