<?php
#################################################################
# Script Name 	: network_upload.php
# Description 	: Page for uploading network logos & flash intro
# Coded by 		: SG
# Created on	: 22-Jun-2006
# Modified by	: SG
# Modified On	: 22-Jun-2006
#################################################################
#	Include Common routines
include_once("functions/functions.php");
include('session.php');
require_once("config.php");
#	Message Handler
if($_REQUEST['Save'] == 'Save') {
	$content = stripslashes(str_replace("'","&#039;",$_REQUEST['tareacontents']));
	$content = str_replace("\n","",$content);
	$content = str_replace("\r","",$content);
	?>
	<script language="JavaScript">
		opener.document.<?=$_REQUEST['form_name']?>.<?=$_REQUEST['element_name']?>.value = '<?php echo $content;?>';
		this.window.close();
	</script>
	<?
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Editor</title>
<script language="JavaScript" type="text/JavaScript">
<!--
	_editor_url = 'htmlarea/';          // URL to htmlarea files
	var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
	if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }

	if (win_ie_ver >= 5.5) {
		document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
		document.write(' language="Javascript"></scr' + 'ipt>');
	} else {
		document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>');
	}
//-->
</script> 
</head>
<body>
<?php
if($_REQUEST['Save'] == 'Save') echo '<center><font color="red">Incorrect Html content Entered.</font></center>';
?>
<form name="frmEditor" action="htmlarea.php" method="POST">
<table width="100%">
<tr>
	<td class="subhead" valign="top">
		<?=$_REQUEST['label']?>
	</td>
	<td>
		<textarea name="tareacontents" id="tareacontents" rows="20" cols="60"></textarea>
	</td>
</tr>
<tr>
	<td align="center" colspan="2">
		<input type="submit" name="Save" value="Save">
	</td>
</tr>
<input type="hidden" name="element_name" id="element_name" value="<?=$_REQUEST['element_name']?>">
<input type="hidden" name="label" id="label" value="<?=$_REQUEST['label']?>">
<input type="hidden" name="form_name" id="form_name" value="<?=$_REQUEST['form_name']?>">
</table>
</form>
<script language="JavaScript" defer>
	editor_generate('tareacontents');
	document.getElementById("tareacontents").value = opener.document.<?=$_REQUEST['form_name']?>.<?=$_REQUEST['element_name']?>.value;
</script>
</body>
</html>
