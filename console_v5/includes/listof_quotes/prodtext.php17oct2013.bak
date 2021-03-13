<?php
include_once("../../functions/functions.php");
include_once("../../classes/db_class.inc.php");
include_once('../../session.php');
include_once("../../config.php");
?>
<html>
<head>
<link href="../../css/style.css" rel="stylesheet" />
</head>
<body>
<?
$prod_id =$_REQUEST['prod_id'];
$sqlp = "select pr.product_text from product_enquiry_data pr where pr.products_product_id=".$prod_id." ";
		$resp=$db->query($sqlp);
		$rowp = $db->fetch_array($resp);
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		 <td align="left" valign="middle" class="seperationtd">
		 <strong>Product Description</strong>
		 </td>
		</tr>
		<tr>
		<td align="center" class="listingtablestyleB">
		 <? echo $rowp['product_text'];?>
		</td>
		</tr>
		</table>
</body>		
</html>		
		

