<?php
$theme_folder = 'default';
$help_arr = load_Help_HTML();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Bshop v5.0 Console Area</title>
<link href="css/style.css" rel="stylesheet" media="screen">
<link href="css/top_menu.css" rel="stylesheet" media="screen">
<?php /*?><link rel="stylesheet" type="text/css" href="css/float.css"><?php */?>
<script language="javascript" src="js/helpbox.js"></script>
<script language="JavaScript" src="js/date_picker.js"></script>
<?php /*<script language="JavaScript" src="js/ajax.js"></script>*/?>
<script language="JavaScript" src="js/validation.js"></script>
<script language="JavaScript" src="js/JSCookMenu_mini.js" type="text/javascript"></script>
<script language="JavaScript" src="js/ThemeOffice/theme.js" type="text/javascript"></script>
<script language="JavaScript" src="js/tooltip.js"></script>
<script language="JavaScript" src="js/ssm.js"></script>
<script language="JavaScript" src="js/ssmItems.js"></script>
<script language="JavaScript" src="js/jquery.js"></script>
<script language="JavaScript" src="js/scrollnav/scroll-nav.js"></script>
<script language="javascript" src="js/scrollnav/scroll-action.js"></script>

<?php /* for jquery datepicker */?>
<link rel="stylesheet" href="css/jquery-ui.css" media="screen" />
<script src="js/jquery-1.8.3.js"></script>
<script src="js/jquery-ui.js"></script>

<link href="css/auto_complete.css" media="screen" type="text/css" rel="stylesheet" />
<script  type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script  type="text/javascript" src="js/auto_complete.js"></script>

</head>
<?php
/*if($_REQUEST['request']!='')
{
	if ($help_arr['html_path']!='' )
	{
	?>
		<script language="javascript">
		ssmItems[0]=["<img src='images/help-link.gif' border='0'>", "javascript:showPopup('<?php echo $ecom_hostname?>','<?php echo $help_arr['html_path']?>',800,750)", ""];
		buildMenu();
		</script>
	<?php
	}
	else
	{
	?>
	<script language="javascript">
		ssmItems[0]=["<img src='images/help-link.gif' border='0'>", "javascript:alert('Coming soon...')", ""];
		buildMenu();
		</script>
<?php
	}
}	*/
?>	
<body>
<div id="ajax_error" align="left" style="width:400px;background:#e1e1e1;border:2px solid #c8c8c8;padding:5px;height:120px; z-index:3; position:absolute; left:30%; top:35%; display:none">
</div>
<div id="disccalc_div" align="left" class="percalcu" style="display:none">
<div align="right">
<a href="javascript:hide_discount_calculator()"><img src="images/close_cal.png" border="0" /></a></div>
<form name="discountcalc_form">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="per_table">
<tbody><tr>
	<td colspan="3" align="middle" class="per_header">
	Percentage Calculator</b>	</td>
</tr><tr>
	<td class="per_table_td">What is <input size="5" name="a"> % of <input size="5" name="b">?</td>
	<td width="32%" class="per_table_td">Answer: <input maxlength="40" size="10" name="total1"></td>
	<td width="18%" class="per_table_td"><input onclick="perc1()" value="Calculate" type="button"></td>
</tr><tr>
	<td class="per_table_td"><input size="5" name="c"> is what percent of 
	  <input size="5" name="d" /> ?</td>
	<td class="per_table_td">Answer: <input size="10" name="total2"> %</td>
	<td class="per_table_td"><input onclick="perc2()" value="Calculate" type="button"></td>
</tr><tr>
	<td align="middle" class="per_table_td">&nbsp;</td>
    <td align="middle" class="per_table_td">&nbsp;</td>
    <td align="middle" class="per_table_td"><input value="Reset" type="reset" class="reset" /></td>
</tr>
</tbody></table>
</form>
</div>
<center>
  <table border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td align="left" valign="middle" class="toptd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="middle" class="logo_td">
          <div class="logo_td_div">
          <div class="logo_td_div_l"> <a href="<?php echo 'home.php'?>" class="toplogolink1"></a></div><div class="logo_td_div_r"> <a href="<?php echo 'home.php'?>" class="toplogolink2"></a><span> Logged in as <?php echo $_SESSION['log_user']?> !</span></div>
          </div>
          </td>
          <td align="right" valign="middle">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="login_nav">
              <tbody><tr>
                <td width="5%"></td>
                <td width="95%">
					<a href="home.php?request=logout" class="iconlogout">logout</a>
					<a href="../index.php" class="iconpreview" target="_blank">preview</a>
					<a href="#" onclick="history.forward()" class="iconforward">forward</a>
					<a href="#" onclick="history.back()" class="iconback">back</a>
					<a href="home.php" class="iconhome">home</a>				</td>
                </tr>
                </tbody></table>
          <?php 
          	/*if ($help_arr['html_path']!='')
          	{
          ?>
          		<a href="javascript:showPopup('<?php echo $ecom_hostname?>','<?php echo $help_arr['html_path']?>',800,750)" class="toptext" title="Help">HTML Help</a>
		 <?php
          	}
          	else 
          	{
		 ?>
		 		<a href="#" onclick="alert('Coming soon...')" class="toptext" title="Help">HTML Help</a>
		 <?php
          	}*/
          	/* $help_arr = load_Help_Swf();
		 	if ($help_arr['flash_path']!='')
          	{
          ?>
          		<a href="<?php echo $help_arr['flash_path']?>" class="toptext" title="Help" target="_blank">Flash Help</a>
		 <?php
          	}
          	else 
          	{
		 ?>
		 		<a href="#" onclick="alert('Coming soon...')" class="toptext" title="Help">Flash Help</a>
		 <?php
          	}	*/
          ?>	
          <?php /*<a href="#" class="toptext">FAQ</a><a href="#" class="toptext_plain">Support</a>*/?></td>
        </tr>
      </table></td>
    </tr>
   <!-- <tr>
      <td align="left" valign="middle" class="icontd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="5%"><?php /*?><img src="images/epos.gif" width="95" height="50" /><?php */?></td>
          <td width="95%"><a href="home.php?request=logout" class="iconlogout">logout</a>
       	<a href="../index.php" class="iconpreview" target="_blank">preview</a> 
       	<a href="home.php" class="iconhome">home</a>
        <a href="#" onclick="history.forward()" class="iconforward">forward</a>
      	<a href="#" onclick="history.back()" class="iconback">back</a></td>
        </tr>
      </table>       </td>
    </tr>-->
    <tr>
      <td align="left" class="topnav">
	   		<?php 
				// Displaying the usermenu
				include ("usermenu.php");
			?>
	 </td>
    </tr>
	
	<tr>
		<td align="center" valign="top" class="maincontent_class">
		<div id="maincontent">
			<?php 
				// Displying the middle area
				include("mainbody.php"); 
			?>
		</div>	
		</td>
	</tr>	
	 <tr>
      <td align="right" valign="middle" class="bottomcopyright">Console Area powered by BSHOP V5.0. Copyright &copy; <?php echo date('Y')?> The Web Clinic Limited.</td>
    </tr>
  </table>
<iframe src="processing_div_new.html" scrolling="no" frameborder="0"  class="processing_divcls" id="processing_div" style="display:none;">
</iframe>
</center>
</body>
</html>
