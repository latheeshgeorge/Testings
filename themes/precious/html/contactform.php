<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?
require("../../../functions/functions.php");
require("../../../includes/session.php");
include ("../../../config.php");
global $ecom_hostname,$ecom_theme_name;
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
?>
<html >
<head>
<link href="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/bshop4_default.css" type="text/css" rel="stylesheet">
<title>Garraways Contact Form, Coffee Machines, Coffee Beans, Espresso Machines</title>
 <style>
 	.whitebackground {
		BACKGROUND-COLOR: #ffffff;
	}
	.fontcolor {
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size: 13px;
		color:#800000;
	}
 </style>
<script language="JavaScript">
<!--
 
function SymError()
{
  return true;
}
 
window.onerror = SymError;
 
var SymRealWinOpen = window.open;
 
function SymWinOpen(url, name, attributes)
{
  return (new Object());
}
 
window.open = SymWinOpen;
 
//-->
</script>
 
<script type="text/javascript" src="<? echo $ecom_hostname; ?>/themes/garrawaysnew/javascript.js"></script>
<script type="text/javascript" >
function checkdata() {
 var error = "";
 if (document.FrontPage_Form2.Name.value == "") { error = "Please enter the required field: Name"; 
 alert(error);  
 document.FrontPage_Form2.Name.focus();
 return false;
 } 
 else if (document.FrontPage_Form2.Company.value == "") { error = "Please enter the required field: Company Name "; 
 alert(error);
 document.FrontPage_Form2.Company.focus();
 return false;
 }
 else if (document.FrontPage_Form2.Address1.value == "") { error = "Please enter the required field: Address 1"; 
 alert(error);
  document.FrontPage_Form2.Address1.focus();
 return false;
 }
 else if (document.FrontPage_Form2.Address2.value == "") { error = "Please enter the required field: Address 2"; 
 alert(error); 
 document.FrontPage_Form2.Address2.focus();
 return false;
 }
 else if (document.FrontPage_Form2.Postcode.value == "") { error = "Please enter the required field: Postcode"; 
 alert(error); 
 document.FrontPage_Form2.Postcode.focus();
 return false;
 }
 else if (document.FrontPage_Form2.Town.value == "") { error = "Please enter the required field: Town"; 
 alert(error); 
 document.FrontPage_Form2.Town.focus(); 
 return false;
 }
 else if (document.FrontPage_Form2.Email.value == "") { error = "Please enter the required field: E-mail Address"; 
 alert(error); 
 document.FrontPage_Form2.Email.focus();  
 return false;
 }
 else if (document.FrontPage_Form2.Tel.value == "") { error = "Please enter the required field: Telephone Number"; 
 alert(error); 
 document.FrontPage_Form2.Tel.focus();  
 return false;
 }
 else { return true; }
}
</script>
</head>
<body>
 
<TABLE style="BORDER-COLLAPSE: collapse" borderColor=#111111 cellSpacing=0 cellPadding=0 width=460 border=0> <tr>
  <td colspan="2" align=center>
  <IMG src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/site_images/logo.gif" width="171" height="59" align="center">  </td>
 </tr>
 <TR>
  
    <TD style="WIDTH: 150px" align=left width=150 bgColor=#ffffff> <SPAN class="whitebackground"><FONT face=Verdana size=2><strong>Free 
      Phone</strong></FONT></SPAN> </TD>
  <TD style="WIDTH: 350px" align=right width=350 bgColor=#ffffff>
    <font class="fontcolor" >
    <SPAN class="whitebackground">0800 0560736</SPAN>
            </font>
   </TD>
  </TR>
 <TR>
  
    <TD vAlign=top align=left bgColor=#ffffff> <strong>
    <span class="whitebackground"><font face="Verdana" size="2">Email:</font></span></strong><p>
    <SPAN class="whitebackground"><FONT face=Verdana size=2><strong>Postal 
      Address</strong></FONT></SPAN> </TD>
  <TD align=right bgColor=#ffffff>
 
    <span class="whitebackground">
    <font class="fontcolor">
    <a href="mailto:Info@garraways.co.uk"><font class="fontcolor">
    Info@garraways.co.uk</font></a></font></span><p><font class="fontcolor">
    <SPAN class="whitebackground"><FONT class="fontcolor">
            Garraways<br/>Unit 1<br/>Taylor Street Industrial Estate<br/>Taylor 
            Street<br/>Bury<br/>Lancs<br/>BL9 6DT</FONT></SPAN> </font>
   </TD>
  </TR>
</TABLE>
          
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td>
   <FORM name="FrontPage_Form2" method="post" action="./mail.php">
    <P><FONT face=Verdana size=2><SPAN lang=en-gb><strong>What are you enquiring 
          about</strong></SPAN><strong>?</strong></FONT></P>
    
<DL>
<DD><SELECT id="Subject" name="Subject" size="1"> 
<OPTION selected>Representative Contact</OPTION> 
<OPTION>Products</OPTION> 
<OPTION>General Equipment</OPTION> 
<OPTION>Company</OPTION> 
<OPTION>Web Site</OPTION> 
<OPTION>Delivery</OPTION> 
<OPTION>(Other)</OPTION> 
<OPTION>Brochure Request</OPTION> 
</SELECT> <FONT face=Verdana size=2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<font color="#800000">Other: </font> </FONT><INPUT maxLength="256" size="26" name="SubjectOther"></DD></DL>
                <p><b><font size="2" face="Verdana">If requesting a brochure please 
                specify the brochure sections you wish to receive:</font></b></p>


<DD><font class="fontcolor">
<INPUT id="EspressoMachines" type="checkbox" value="Espresso Machines" name="Brochure2">Espresso 
Machines</font></DD>
<DD><font class="fontcolor">
<INPUT id="BeanToCup" type="checkbox" value="Bean To Cup" name="Brochure3">Bean 
To Cup Systems</font></DD>
<DD><font class="fontcolor">
<INPUT id="CappuccinoSystems" type="checkbox" value="Cappuccino Systems" name="Brochure4">Cappuccino 
Systems</font></DD>
<DD><font class="fontcolor">
<INPUT id="HotChoc" type="checkbox" value="Hot Choc" name="Brochure5">Hot 
Chocolate Systems</font></DD>
<DD><font class="fontcolor">
<INPUT id="KencoSingles" type="checkbox" value="Kenco Singles" name="Brochure6">Kenco 
Singles</font></DD>
<DD><font class="fontcolor">
<INPUT id="FilterCoffeeEquipment" type="checkbox" value="Filter Coffee Equipment" name="Brochure7">Filter 
Coffee Equipment</font></DD></DL>
                <blockquote>
                  <p><font class="fontcolor">
                  <INPUT id="ProductList" type="checkbox" value="Include Product Price List" name="Brochure8">Include 
                  A Product Price List</font></p>
                </blockquote>
 
        <P><FONT class="fontcolor"><strong>Enter your comments in the space 
          provided below</strong>:</FONT></P>
<DL>
<DD><TEXTAREA name="Comments" rows="4" cols="40"></TEXTAREA></DD></DL>
<P><b><FONT class="fontcolor">Tell us how to get in touch with you:&nbsp;&nbsp; </FONT></b><SPAN lang=en-gb><FONT class="fontcolor">
(*)&nbsp; = required fields</FONT></SPAN></P>
<DL>
<DD>
<TABLE>
 
<TR>
                <TD><FONT class="fontcolor">
                <strong style="font-weight: 400">Name<SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength="256" size="35" name="Name"><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor">
                <strong style="font-weight: 400">Company Name<SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Company><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor"><SPAN lang=en-gb>
                <strong style="font-weight: 400">Address 
                  1*</strong></SPAN></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Address1><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor"><SPAN lang=en-gb>
                <strong style="font-weight: 400">Address 
                  2</strong></SPAN><strong style="font-weight: 400"><SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Address2><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor"><SPAN lang=en-gb>
                <strong style="font-weight: 400">Town</strong></SPAN><strong style="font-weight: 400"><SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Town><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor"><SPAN lang=en-gb>
                <strong style="font-weight: 400">Postcode</strong></SPAN><strong style="font-weight: 400"><SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Postcode><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor">
                <strong style="font-weight: 400">E-mail<SPAN lang=en-gb>*</SPAN></strong></FONT></TD>
<TD><INPUT style="BACKGROUND-COLOR: #ffffa0" maxLength=256 size=35 name=Email><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor">
                <strong style="font-weight: 400">Te<SPAN>l*</SPAN></strong></FONT></TD>
<TD><INPUT maxLength=256 size=35 name=Tel><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR>
<TR>
                <TD><FONT class="fontcolor"><strong style="font-weight: 400">FAX</strong></FONT></TD>
<TD><INPUT maxLength=256 size=35 name=Fax><FONT face=Verdana size=2>&nbsp;</FONT></TD></TR></TBODY></TABLE></DD></DL>
<P>
<INPUT onclick="if (checkdata()) { FrontPage_Form2.submit();  }" type=button  class="button" value="Submit Comments"><FONT class="fontcolor"> </FONT>
<INPUT type=reset class="button"  value="Restart Form"><FONT class="fontcolor"> </FONT></P>
   </FORM>
  </td>
 </tr>
 </table>
 
</body>
 
<script language="JavaScript">
<!--
 
window.open = SymRealWinOpen;
 
//-->
</script>
 
</html>
