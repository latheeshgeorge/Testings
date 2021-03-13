<?php
	//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
include_once("functions/functions.php");
include('sites.php');
include('config.php');
//include('classes/mime.php');
if($_SESSION['console_id'] != '' && $_SESSION['site_id'] != '' && $_SESSION['domain'] != '' && $_SESSION['domain'] == $_SERVER['HTTP_HOST']) {
	redirect('home.php','');
}
if($ecom_hostname != $_SERVER['HTTP_HOST']) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: http://$ecom_hostname/console/");
	exit;
}
if($failure == 'true'){
	$showmsg = "Sorry! Invalid Login";
} else if ($failure == 4) {
	$showmsg = "Sorry! Password Too Long";
}else if ($logout == 'true') {
	$showmsg = "Logged out successfully";
} else if ($session == 'true') {
	$showmsg = "Your Session expired, Please Login";
} else if ($failure == 'disabled') {
	$showmsg = "Sorry! Your account id disabled";
} else if ($store == 'disabled') {
	$showmsg = "Sorry! Your have no access to this store";
}

?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Bshop v4.0 Console Area</title>
	<link href="css/default.css" rel="stylesheet" type="text/css">
	<script src="js/validation.js" language="javascript"></script>
	<script language="JavaScript">
	function valform(frm)
	{
		fieldRequired = Array('txtuname','txtpass');
		fieldDescription = Array('Username','Password');
		fieldEmail = Array('txtuname');
		fieldConfirm = Array();
		fieldConfirmDesc  = Array();
		fieldNumeric = Array();
		if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
			document.frmAuth.login_check.value=1;
			return true;
		} else {
			return false;
		}
	}	
	
	</script>
</head>
<body>
<div class="main_login" >
<?php
if ($showmsg!='')
{
?>
<div class="main_login_err"><?php echo $showmsg;?></div>
<?php
}
?>
<div class="main_login_otr">

<form action="login.php" method="post" name="frmAuth" id="frmAuth" onSubmit="return valform(this);">
<div class="loginmaintop_v5" >Admin Login</div>
<div align="left"  class="loginmaintdlogo_v5"><input name="txtuname" id="txtuname" value="" type="text" class="v5_login"></div><div align="left" valign="middle" class="loginmaintdlogo_a_v5"><input name="txtpass" id="txtpass" type="password" class="v5_login"></div>
<input name="login_check" type="hidden">
<div class="loginmaintop_v5_btn" ><input name="login_submit" id="login_submit" type="submit" class="v5_login_submit" value="">
</div>
<input name="request" value="<?php echo $_REQUEST['request']?>" type="hidden">
</form>
</div>
<div class="main_login_otr_b" ></div>
</div>
<div class="loginmain_w_v5"><span class="spanred_v5">WARNING!</span>
ACCESS AND USE OF THIS COMPUTER SYSTEM BY ANYONE WITHOUT THE PERMISSION IS STRICTLY 
PROHIBITED BY STATE AND FEDERAL LAWS AND MAY SUBJECT AN UNAUTHORIZED 
USER, INCLUDING EMPLOYEES NOT HAVING AUTHORIZATION, TO CRIMINAL AND 
CIVIL PENALTIES AS WELL AS COMPANY-INITIATED DISCIPLINARY ACTION</div>  
</body>
</html>
