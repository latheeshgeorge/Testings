<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
<script type="text/javascript">
function showchart(cnt)
{
	if(cnt==1)
		document.getElementById('maingraph_iframe').src = 'stacked_test.php';
	else if (cnt==2)
		document.getElementById('maingraph_iframe').src = 'stacked_test2.php';
}
</script>
</head>
<body>
<p>sadsdf</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%"><a href="javascript:showchart(1)">Show Stack 1</a></td>
    <td width="85%"><a href="javascript:showchart(2)">Show Stack 2</a> </td>
  </tr>
  <tr>
    <td colspan="2">
	<iframe id="maingraph_iframe" height="540" allowTransparency="true" frameborder="0" scrolling="no" style="width:100%;border:none" src="stacked_test.php" title=""></iframe>
	
	
	</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
