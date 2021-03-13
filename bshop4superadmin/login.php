<?
/*
if($_SERVER['HTTP_HOST'] != 'www.bsecured.co.uk' && $_SERVER['HTTP_HOST'] != 'bsecured.co.uk') {
		exit;
}
if($_SERVER["HTTPS"] != "on") {
	header("Location: https://www.bsecured.co.uk/bshop4superadmin");
	exit;
}
*/ 
session_start();
if($_POST['login_check'] && $_POST['txtuname'] != '' && $_POST['txtpass'] != '') {
	include('config.php');
	include_once("functions/functions.php");
	$username 	= addslashes($_POST['txtuname']);
	$passwd 	= base64_encode(addslashes($_POST['txtpass']));
	$sql = "SELECT user_id FROM sites_users_7584 WHERE user_email_9568='$username' AND user_pwd_5124='$passwd' AND sites_site_id=0";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	if($db->num_rows($res)) {
		$_SESSION['user_id'] 	= $row['user_id'];
		redirect('home.php');
	} else {
		redirect('index.php',$arg = "failure=true");
	}
	
} else {
	redirect('index.php',$arg = "failure=true");
}
?>
