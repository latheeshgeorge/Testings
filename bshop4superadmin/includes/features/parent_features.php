<?php
	include_once("../../functions/functions.php");
	include('../../session.php');
	require_once("../../config.php");
	// Find the domain name for the site
	$sql_feature = "SELECT feature_name FROM features WHERE feature_id=".$_REQUEST['featureid'];
	$ret_feature = $db->query($sql_feature);
	if ($db->num_rows($ret_feature))
	{
		$row_feature	= $db->fetch_array($ret_feature);
		$featurename	= stripslashes($row_feature['feature_name']);
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
	<title>Bshop v4.0 Super Admin - Parent  Features</title>
   <link href="../../css/bv4.css" rel="stylesheet" type="text/css">
	</head>
<body>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" class="menutabletoptd">&nbsp;Parent  Features of "<?php echo $featurename?>"</td>
  </tr>
  <tr>
    <td width="4%" align="left">&nbsp;</td>
    <td width="96%" align="left">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
	<?php
	$arr=array();
	getParentTree($_REQUEST['featureid'],1,$arr);
?>
	</table>
</td>
  </tr>
</table>
</body>
</html>
