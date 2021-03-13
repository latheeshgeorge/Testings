<?
include_once("../functions/functions.php");
include('../session.php');
require_once("../config.php");
if(isset($_REQUEST['emt_id']) and ($_REQUEST['emt_id']<>''))
{
	$sql_element = "SELECT element_type FROM elements WHERE element_id = '".$_REQUEST['emt_id']."' AND sites_site_id=$ecom_siteid";
	$res_element = $db->query($sql_element);
	$row_element = $db->fetch_array($res_element);
	$type		 = $row_element['element_type'];
}
else
{ 
   $type = $_REQUEST['type'];
}
?>
<html>
<head>
<title>HTML Elements</title>
<link href="../css/style.css" rel="stylesheet" media="screen">
<link href="../css/default.css" rel="stylesheet" media="screen">
</head>
<body style="border:0px; padding:0px; margin:0px;">
<?php
	/*With the value of type the respective element page is selected.*/
	switch($type)
	{
		case 'text': include('../includes/custom_form/tb_element.php');
				break;
		case 'textarea': include('../includes/custom_form/ta_element.php');
				break;
		case 'checkbox': include('../includes/custom_form/cb_element.php');
				break;
		case 'radio': include('../includes/custom_form/rb_element.php');
				break;
		case 'select': include('../includes/custom_form/sb_element.php');
				break;
		case 'date': include('../includes/custom_form/td_element.php');
				break;		
	}
?>
</body>
</html>