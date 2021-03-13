<?php
	include_once("../../functions/functions.php");
	include('../../session.php');
	require_once("../../config.php");
	// Find the domain name for the site
	$sql_service = "SELECT service_name FROM services WHERE service_id=".$_REQUEST['serviceid'];
	$ret_service = $db->query($sql_service);
	if ($db->num_rows($ret_service))
	{
		$row_service	= $db->fetch_array($ret_service);
		$servicename	= stripslashes($row_service['service_name']);
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
	<title>Bshop v4.0 Super Admin - Service Hierarchy</title>
   <link href="../../css/bv4.css" rel="stylesheet" type="text/css">
	</head>
<body>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" class="menutabletoptd">&nbsp;Service Hierarchy of "<?php echo $servicename?>"</td>
  </tr>
  <tr>
    <td width="4%" align="left">&nbsp;</td>
    <td width="96%" align="left"><?php
	generate_service_hierarchy($_REQUEST['serviceid']);
?></td>
  </tr>
</table>
</body>
</html>
