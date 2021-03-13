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
	// Find all the features existing for the current site 
	$sql_feat = "SELECT a.feature_name,a.feature_modulename FROM features a,site_menu b WHERE b.sites_site_id=".$_REQUEST['sid']."
	 AND a.feature_id=b.features_feature_id ORDER BY a.services_service_id";
	$ret_feat = $db->query($sql_feat);
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
    <td colspan="3" class="menutabletoptd">&nbsp;Features Existing in "<?php echo $sitename?>" </td>
  </tr>
  <?php
  	if($db->num_rows($ret_feat))
	{
  ?>
		  <tr class="maininnertabletd4">
			<td width="5%" align="left"><strong>&nbsp;#</strong></td>
			<td width="46%" align="left"><strong>&nbsp;Feature Name </strong></td>
		    <td width="49%" align="left"><strong>&nbsp;Module Name </strong></td>
		  </tr>
		  	<?php
					$cnt = 1;
		  		  	while ($row_feat = $db->fetch_array($ret_feat))
				  	{
			?>		  
					  <tr>
						<td width="5%" align="left">&nbsp;<?php echo $cnt++?>.</td>
						<td width="46%" align="left">&nbsp;<?php echo $row_feat['feature_name']?></td>
						<td width="49%" align="left">&nbsp;<?php echo ($row_feat['feature_modulename'])?$row_feat['feature_modulename']:'----'?></td>
					  </tr>
  <?php
  					}
	}
	else
	{
  ?>
		  <tr>
			<td align="center" colspan="3" class="error_msg">-- No Modules Assigned to "<?php echo $sitename?>" --</td>
		  </tr>
		  
  <?php
  	}
  ?>
  <tr>
		<td align="center" colspan="3" class="error_msg">&nbsp;</td>
  </tr>
  <tr>
	    <td align="center" colspan="3" class="error_msg">&nbsp;</td>
  </tr>
  <tr>
	    <td align="center" colspan="3" class="error_msg"><input type='button' value='Close' class='input-button' onclick='window.close();'></td>
  </tr>
  <tr>
	    <td align="center" colspan="3" class="error_msg">&nbsp;</td>
  </tr>
</table>
</body>
</html>
