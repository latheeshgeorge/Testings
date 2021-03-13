<?
$defaultTitle = "The Web Clinic";
$defaultText = "The requested website is currently unavailable from The Web Clinic. <br><br>We apologise for the inconvenience this may cause.";

//$defaultText = "This Website has been suspended due to non payment of fees or have ceased trading.";

if ($_SERVER['HTTP_HOST']=='www.skatesrus.co.uk' or $_SERVER['HTTP_HOST']=='skatesrus.co.uk')
	$defaultText ='The Domain and Ecommerce Website for skatesrus.co.uk is for Sale.<br><br>Please Telephone The Web Clinic <br>on +44 1524 888880 for more information.';


if ($_SERVER['HTTP_HOST']=='www.jellybeangroup.co.uk' or $_SERVER['HTTP_HOST']=='jellybeangroup.co.uk')
	$defaultText ='This Website has been suspended due to non payment of web services.<br><br>We apologise for the inconvenience this may cause.';

if ($_SERVER['HTTP_HOST']=='www.ilovepens.co.uk' or $_SERVER['HTTP_HOST']=='ilovepens.co.uk')
	$defaultText = "This website is no longer operational.";
if ($_SERVER['HTTP_HOST']=='www.shootuk.co.uk' or $_SERVER['HTTP_HOST']=='shootuk.co.uk')
	$defaultText = "The Web Clinic Ltd Has Suspended This Website.<br>
					Please Telephone 01524 888880 With All Enquiries";
//$defaultText1 = "<br><br>This domain name maybe available for sale. <br><br>Please contact The Web Clinic on 01524 888880 or email support@thewebclinic.co.uk";

if ($_SERVER['HTTP_HOST']=='www.iloveflooring.co.uk' or $_SERVER['HTTP_HOST']=='iloveflooring.co.uk')
{
	/*?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr>
	<td align="center" style="padding-top:40px;"><img src="http://www.iloveflooring.co.uk/images/www.iloveflooring.co.uk/site_images/logo.png" alt="logo"></td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><br><br>I Love Flooring is Currently Offline While We Update Our Stock With More Low Priced Products.</td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><br><br>Email: <a href="mailto:info@iloveflooring.co.uk" style="color:#E1A11B">info@iloveflooring.co.uk</a> for all enquiries </td>
	</tr>
	</table>

	<?php
	*/
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr>
	<td align="center" style="padding-top:40px;">&nbsp;</td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><br><br>I Love Flooring Has Been Suspended</td>
	</tr>
	</table>	
	<?php
	 
	exit;	
}

if ($_SERVER['HTTP_HOST']=='www.aromocoffee.co.uk' or $_SERVER['HTTP_HOST']=='aromocoffee.co.uk')
{
	/*?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr>
	<td align="center" style="padding-top:40px;"><img src="http://www.iloveflooring.co.uk/images/www.iloveflooring.co.uk/site_images/logo.png" alt="logo"></td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><br><br>I Love Flooring is Currently Offline While We Update Our Stock With More Low Priced Products.</td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><br><br>Email: <a href="mailto:info@iloveflooring.co.uk" style="color:#E1A11B">info@iloveflooring.co.uk</a> for all enquiries </td>
	</tr>
	</table>

	<?php
	*/
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2">
	<tr>
	<td align="center" style="padding-top:40px;">&nbsp;</td>
	</tr>
	<tr>
	<td align="center" style="font-size:18px;color:#6b5d3e;font-weight:bold;padding-top:20px;"><img src="http://www.aromocoffee.co.uk/images/www.aromocoffee.co.uk/site_images/Aromo Coffee Ltd.png" alt="logo"></td>
	</tr>
	</table>	
	<?php
	 
	exit;	
}

$defaultLinks = "<a href='http://www.thewebclinic.co.uk/terms.htm'>Terms and Conditions</a>";
$defaultDesc = "The Web Clinic";
$defaultKeywords = "www.thewebclinic.co.uk";
$defaultAddress = "
              The Web Clinic<br />
              7 Kings Arcade<br />
King Street<br />
Lancaster<br />
LA1 1LE<br />

			  Phone: +44 01524 888880<br />
			  <a href=\"http://www.thewebclinic.co.uk/\">www.thewebclinic.co.uk</a><br />";
?>


<html>

<head>
<title>
<?
echo $defaultTitle;
?>

</title>
<meta name="description" content="<? echo $defaultDesc; ?>" />
<meta name="keywords" content="<? echo $defaultKeywords; ?>" />

<style>
body{
background-color:#6375D7;
margin-top:10%
}
.maintable{
background-color:#95A3EF;
width:700px;
height:300px;
}
.alerttd{
border-top:1px solid #B0BBF4;
border-bottom:1px solid #B0BBF4;
padding:5px 0px 5px 0px;
font-family:Arial, Helvetica, sans-serif;
font-size:26px;
font-weight:bold;
color:#B90000;
text-align:center;
}

.alerttd span{
padding:5px 0px 5px 0px;
font-family:Arial, Helvetica, sans-serif;
font-size:14px;
font-weight:bold;
color:#B90000;
}
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	padding-top:5px;
}
a:link {
	color: #000000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #000000;
}
a:hover {
	color: #000000;
}
a:active {
	text-decoration: none;
}
</style>
</head>
<body>

<div style="font-size:10px; font:status-bar; text-align:center;">
<a href="http://www.thewebclinic.co.uk">The Web Clinic</a>
</div>
<div align="center" class="style1">



</div>
<center>
 
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
    <tr>
      <td width="2%" align="left" valign="top"><img src="images/suspend_cancel_images/left_top.gif" width="25" height="25" /></td>
      <td width="96%">&nbsp;</td>
      <td width="2%" align="right" valign="top"><img src="images/suspend_cancel_images/right_top.gif" width="25" height="25" /></td>
    </tr>
    <tr>
      <td height="249">&nbsp;</td>
      <td align="center" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" align="right" valign="top" style="height:70px"><?php /*if($ecom_siteid!=120) { ?>
              <a href="http://www.thewebclinic.co.uk/"><img src="images/suspend_cancel_images/bshop_logo.gif" alt="www.thewebclinic.co.uk internet consultancy" border="0" /></a>
              <?php }*/ ?></td>
          </tr>
          <tr>
            <td class="alerttd" ><a href="http://www.thewebclinic.co.uk/hosting.html"><img src="images/suspend_cancel_images/alert.gif" alt="www.thewebclinic.co.uk ecommerce hosting" width="54" height="46" border="0" /></a></td>
            <td align="left" class="alerttd" >
			<?
			echo $defaultText;
			?>
			<span>
			<?
			echo $defaultText1;
			?>
			</span>
			</td>
          </tr>
          <tr>
            <td colspan="2" align="right" valign="top" class="style1"><?php if($ecom_siteid!=120) { ?>
              <a href="http://www.thewebclinic.co.uk/about-us.html"><img src="images/suspend_cancel_images/b1st_logo.gif" alt="www.thewebclinic.co.uk" width="131" height="51" border="0"/></a>
			  <br />
			  <?
			  echo $defaultAddress;
			  ?>
			  
			  
			  
              
            <?php } ?></td>
          </tr>
        </table></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="bottom"><img src="images/suspend_cancel_images/right_left.gif" width="25" height="25" /></td>
      <td>&nbsp;</td>
      <td align="right" valign="bottom"><img src="images/suspend_cancel_images/right_bottom.gif" width="25" height="25" /></td>
    </tr>
  </table>
  <?

echo $defaultLinks;

?>
</center>




</body>
</html>
