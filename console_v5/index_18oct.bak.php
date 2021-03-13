<?php
session_start();
include_once("functions/functions.php");
include('sites.php');
include('config.php');
if($_SESSION['console_id'] != '' && $_SESSION['site_id'] != '' && $_SESSION['domain'] != '' && $_SESSION['domain'] == $_SERVER['HTTP_HOST']) {
	redirect('home.php','');
}

if($failure == 'true'){
	$showmsg = "Sorry! Invalid Login";
} else if ($logout == 'true') {
	$showmsg = "Logged out successfully";
} else if ($session == 'true') {
	$showmsg = "Your Session expired, Please Login";
} else if ($failure == 'disabled') {
	$showmsg = "Sorry! Your account id disabled";
} else if ($store == 'disabled') {
	$showmsg = "Sorry! Your have no access to this store";
}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eAgent Console Area</title>
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
<body class="login">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="10">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="logintabletd">
	<br><br><br>
	<table width="219" border="0" cellpadding="0" cellspacing="0">
      <tr><form action="login.php" method="post" name="frmAuth" id="frmAuth" onSubmit="return valform(this);">
        <td align="center" valign="middle" ><table width="222" border="0" cellpadding="0" cellspacing="0" class="innertable">
                <tr> 
                  <td colspan="3" align="left" bgcolor="#F5F5F5"><img src="images/logo.gif"></td>
                </tr>
                <tr> 
                  <td colspan="3"></td>
                </tr>
                <tr> 
                  <td colspan="2" align="left" class="redtext">&nbsp;&nbsp;<?php echo $showmsg; ?> </td>
                  <td align="left" class="redtext">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="right" class="fontblacknormal">Email:</td>
                  <td width="144"><input name="txtuname" type="text" id="txtuname" value="<?php echo htmlentities($uname, ENT_QUOTES)?>"></td>
                  <td width="5" class="inputtd">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="right" class="fontblacknormal">Password:</td>
                  <td><input name="txtpass" type="password" id="txtpass"></td>
                  <td class="inputtd">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="right"><input type="hidden" name="login_check"></td>
                  <td align="right"> <input type="image" src="images/logoin.gif" class="loginimage" border="0"></td>
                  <td>&nbsp;</td></form>
                </tr>
                <tr> 
                  <td colspan="3" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="7">
                      <tr> 
                        <td height="154" class="fontblacksmall"><div align="justify"><span class="fontredheading">WARNING!</span> 
                            <span class="fontblackverysmall">ACCESS AND USE OF 
                            THIS COMPUTER SYSTEM BY ANYONE WITHOUT THE PERMISSION 
                            IS STRICTLY PROHIBITED BY STATE AND FEDERAL LAWS AND 
                            MAY SUBJECT AN UNAUTHORIZED USER, INCLUDING EMPLOYEES 
                            NOT HAVING AUTHORIZATION, TO CRIMINAL AND CIVIL PENALTIES 
                            AS WELL AS COMPANY-INITIATED DISCIPLINARY ACTION</span></div></td>
                      </tr>
                    </table></td>
                </tr>
				
              </table></td>
      </tr>
	  <tr height="115"><td>&nbsp;</td></tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>