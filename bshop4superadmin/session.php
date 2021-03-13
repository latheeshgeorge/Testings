<?php
header('P3P: CP="CAO PSA OUR"');

if($_GET["PHPSESSID"]) session_id($_GET["PHPSESSID"]); elseif($_GET["console_sess"]) session_id($_GET["console_sess"]);
{
	session_start();
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
}
if($_SESSION['user_id'] == '') {
	redirect('index.php','session=true');
}
if($_REQUEST['request'] == 'logout') {
	include('logout.php');
}
?>
