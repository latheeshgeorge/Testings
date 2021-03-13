<?php
include_once("../functions/functions.php");
include('../session.php');
require_once("../config.php");
if($_REQUEST['Submit'])
{
	if($_REQUEST['txt_msg']){
		$txt_msg = "'".$_REQUEST['txt_msg']."'";
		}
	else{
		$txt_msg='NULL';
		$db->query("UPDATE elements SET mandatory= 'N'  WHERE element_id=".$_REQUEST['emt_id']);
		}
		$db->query("UPDATE elements SET error_msg= $txt_msg  WHERE element_id=".$_REQUEST['emt_id']);
		
	?>
	<script language="JavaScript">
		window.close();
	</script>
	<?php
}
?>
<?php
/*$emt_id passed from the previous page also the category id ie cat_id*/
	$result = $db->query("select error_msg from elements where element_id=".$_REQUEST['emt_id']);
	$row = $db->fetch_array($result);
?>
<html>
<head>
<title>eAgent Console</title>
<link href="../css/default_style.css" rel="stylesheet" media="screen">
<link href="../css/default.css" rel="stylesheet" media="screen">
<link href="../css/style.css" rel="stylesheet" media="screen">
</head>
<body>
<form name="frmerror" method="POST" action="add_error_msg.php">
<table>
<tr class="subhead">
	<td>
		Error Message
	</td>
	<td>
		<textarea name="txt_msg" rows="6" cols="25"><?=$row[error_msg]?></textarea>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="submit" name="Submit" value="Submit" class="red">
	</td>
</tr>
</table>
<input type="hidden" name="emt_id" value="<?=$_REQUEST['emt_id']?>">
</form>
</body>
</html>
