<?

$defaultTitle = "The Web Clinic";
$defaultText = "The requested website is currently unavailable from The Web Clinic. We apologise for the inconvenience this may cause.";
$defaultLinks = "<a href='http://www.thewebclinic.co.uk/terms.htm'>Terms and Conditions</a>";
$defaultDesc = "The Web Clinic";
$defaultKeywords = "www.thewebclinic.co.uk";
$defaultAddress = "
              The Web Clinic<br />
              114 St. Leonardsgate<br />
              Lancaster<br />
              LA1 1NN<br />
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
font-size:16px;
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
            <td colspan="2" align="right" valign="top"><?php if($ecom_siteid!=120) { ?>
              <a href="http://www.thewebclinic.co.uk/"><img src="images/suspend_cancel_images/bshop_logo.gif" alt="www.thewebclinic.co.uk internet consultancy" width="111" height="105" border="0" /></a>
              <?php } ?></td>
          </tr>
          <tr>
            <td class="alerttd" ><a href="http://www.thewebclinic.co.uk/hosting.html"><img src="images/suspend_cancel_images/alert.gif" alt="www.thewebclinic.co.uk ecommerce hosting" width="54" height="46" border="0" /></a></td>
            <td align="left" class="alerttd" >
			
			<?
			
			echo $defaultText;
			
			?>
			
	
			
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