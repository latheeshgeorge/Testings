<?php
	include_once("../../functions/functions.php");
	include('../../session.php');
	require_once("../../config.php");
	// Find the domain name for the site
	$sql_site = "SELECT site_domain FROM sites WHERE site_id=".$_REQUEST['sid'];
	$ret_site = $db->query($sql_site);
	if ($db->num_rows($ret_site))
	{
		$row_site	= $db->fetch_array($ret_site);
		$sitename	= stripslashes($row_site['site_domain']);
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
	<title>Bshop v4.0 Super Admin - Console Menu</title>
   <link href="../../css/bv4.css" rel="stylesheet" type="text/css">
	</head>
<body>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" class="menutabletoptd">&nbsp;Console Menu Hierarchy of "<?php echo $sitename?>"</td>
  </tr>
  <tr>
    <td width="4%" align="left">&nbsp;</td>
    <td width="96%" align="left"><?php
	generate_console_menu($_REQUEST['sid']);
?></td>
  </tr>
</table>
</body>
</html>
