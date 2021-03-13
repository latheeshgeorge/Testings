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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<link href="<? print $ecom_selfhttp.$ecom_hostname ?>/images/<? echo $ecom_hostname; ?>/<? echo $ecom_theme_name; ?>.css" type="text/css" rel="stylesheet">
<title>Leasing Form,</title>

 

</head>

<body class="popupwindow" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<P align=center><IMG src="<? print $ecom_selfhttp.$ecom_hostname ?>/images/<? print $ecom_hostname ?>/site_images/logo.gif"></P>

<P align=center><FONT face=Verdana size=2>Garraways offer leasing possibilities on all items over £750.00, 

Advantages of Garraways leasing includes:</FONT></P>

<div align="center">

            <table width="588" border="0" id="table1">

                        <tr>

                                    <td width="339"><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> Fast 

                                    Leasing Quotes</FONT></td>

                                    <td width="239">

                                    <P align=left><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> 100% Tax 

                                    Relief</FONT></P></td>

                        </tr>

                        <tr>

                                    <td width="339" height="22"><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> Improve 

                                    Your Cash Flow By Spreading Payments</FONT></td>

                                    <td width="239" height="22"><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> Fixed, 

                                    Low Monthly Payments</FONT></td>

                        </tr>

                        <tr>

                                    <td width="339"><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> Lease 

                                    Purchase Means You Own The Machine</FONT></td>

                                    <td width="239"><FONT face=Verdana size=2>

                                    <img border="0" src="<? echo $ecom_selfhttp.$ecom_hostname; ?>/images/<? echo $ecom_hostname; ?>/big/swirlstop.jpg" width="14" height="16"> You 

                                    Select The Terms 2-5 Years</FONT></td>

                        </tr>

  </table>

            <p></div>

                        <TABLE height="100%" width="799" align=left border=0 id="table2">

                                    <!-- MSTableType="layout" --><FORM name="leasingform" method="post" action="./maillease.php">

<script type="text/javascript">

function checkleasedata() {

 var error = "";
 
 if (document.leasingform.Lease_Period.value == "") 
 { 
 error = "Please enter the required field: Lease Period"; 
 alert(error); 
 document.leasingform.Lease_Period.focus();
 return false;
 }
 
 else if (document.leasingform.Contact.value == "") 
 { 		error = "Please enter the required field: Contact Name"; 
  		alert(error); 
	    document.leasingform.Contact.focus();
 	    return false;
 }

 else if (document.leasingform.Business.value == "") 
 	{ 
		error = "Please enter the required field: Business Type"; 
  		alert(error); 
	    document.leasingform.Business.focus();
 	    return false;

	}
 else if (document.leasingform.Business_Name.value == "") 
 	{ 
		error = "Please enter the required field: Business_Name"; 
  		alert(error); 
	    document.leasingform.Business_Name.focus();
 	    return false;
	}
else  if (document.leasingform.Email.value == "") 
 	{ 
		error = "Please enter the required field: E-mail Address"; 
  		alert(error); 
	    document.leasingform.Email.focus();
 	    return false;

	}

 else if (document.leasingform.Tel.value == "") 
 	{ 
		error = "Please enter the required field: Telephone Number"; 
  		alert(error); 
	    document.leasingform.Tel.focus();
 	    return false;
	}


else  if (document.leasingform.Address1.value == "") 
 	{ 
		error = "Please enter the required field: Address 1"; 
  		alert(error); 
	    document.leasingform.Address1.focus();
 	    return false;
	}

else if (document.leasingform.Address2.value == "") 
 	{ 
		error = "Please enter the required field: Address 2"; 
	  	alert(error); 
	    document.leasingform.Address2.focus();
 	    return false;

	}

 else if (document.leasingform.Town.value == "") 
 	{ 
		error = "Please enter the required field: Town"; 
	  	alert(error); 
	    document.leasingform.Town.focus();
 	    return false;
	}

else  if (document.leasingform.PostCode.value == "") 
 	{ 
		error = "Please enter the required field: PostCode"; 
	  	alert(error); 
	    document.leasingform.PostCode.focus();
 	    return false;

	}

 else { return true; }

}

</script>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Product</strong></FONT></div></TD>

                                                            <TD height=25 align="left">
                                                              <div align="left">
                                                                <INPUT size=19 name=Product>
                                                                <FONT face=Verdana size=2>&nbsp; &amp; / or Product Price</FONT>
                                                                <INPUT size=15 name=Product_Price>                                                            
                                                              </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink vAlign=top align=center>

                                                            <DIV align=right valign="top">
                                                              <div align="left"><strong>
                                                                
                                                                          <FONT face=Verdana size=2>Lease Period</FONT><FONT face=Verdana size=1>*</FONT></strong></div>
                                                  </DIV>                                                  </TD>

                                                            <TD height=25 align="left">

                                                              <div align="left">
                                                                <SELECT id=Lease_Period name=Lease_Period>
                                                                  
                                                                  <OPTION value="24 Months (2 years)" selected>24 Months (2 years)                                                                  </OPTION>
                                                                  
                                                                  <OPTION value="36 Months (3 years)">36 Months (3 years)                                                                  </OPTION>
                                                                  
                                                                  <OPTION value="48 Months (4 years)">48 Months (4 years)                                                                  </OPTION>
                                                                  
                                                                  <OPTION value="60 Months (5 years)">60 Months (5 years)                                                                  </OPTION>
                                                                </SELECT>
                                                              </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Contact Name</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=25 align="left">                                                              <div align="left">
                                                              <INPUT maxLength=50 size=19 name=Contact>                                                            
                                                            </div></TD>
                                                </tr>
												
												<tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Business Name</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=25 align="left">                                                              <div align="left">
                                                              <INPUT size=15 name=Business_Name>                                                            
                                                            </div></TD>
                                                </tr>
												
                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><b>Email</b></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=25 align="left">                                                              <div align="left">
                                                              <INPUT maxLength=100 size=24 name=Email>                                                            
                                                            </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Phone Number</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=25 align="left">                                                              <div align="left">
                                                              <INPUT maxLength=100 size=24 name=Tel>                                                            
                                                            </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Business Type</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=25 align="left">

                                                              <div align="left">
                                                                <SELECT id=Business name=Business>
                                                                  
                                                                  <OPTION value=LTD selected>LTD</OPTION>
                                                                  
                                                                  <OPTION value=PLC>PLC</OPTION>
                                                                  
                                                                  <OPTION value=ST>Sole Trader</OPTION>
                                                                  
                                                                  <OPTION value=PS>Partnership</OPTION>
                                                                </SELECT>
                                                              </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Established</strong>
                                                                
                                                                  <SPAN class=footnote>(In 
                                                                    
                                                  years)</SPAN></FONT></div></TD>

                                                            <TD height=25 align="left">                                                              <div align="left">
                                                              <INPUT size=6 name=Established>                                                            
                                                            </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink vAlign=top align=center rowSpan=3>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Address</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong>&nbsp;&nbsp;&nbsp;<FONT face=Verdana size=2><strong>Town</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD height=29 align="left">                                                              <div align="left">
                                                              <INPUT size=22 name=Address1>                                                            
                                                            </div></TD>
                                                </tr>

                                                <tr>

                                                  <TD height=29 align="left">                                                    <div align="left">
                                                    <INPUT size=22 name=Address2>                                                    
                                                  </div></TD>
                                                </tr>

                                                <tr>

                                                  <TD height=29 align="left">                                                    <div align="left">
                                                    <INPUT size=22 name=Town>                                                    
                                                  </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Post Code</strong></FONT><strong><FONT face=Verdana size=1>*</FONT></strong></div></TD>

                                                            <TD class=bodytext height=25 align="left">                                                              <div align="left">
                                                              <INPUT size=15 name=PostCode>                                                            
                                                            </div></TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytextlink vAlign=top align=center>

                                                              <div align="left"><FONT face=Verdana size=2><strong>Comments</strong> </FONT></div></TD>

                                                            <TD class=bodytext height=118 align="center">

                                                            <p align="left"><TEXTAREA name=Comments rows=7 cols=38></TEXTAREA>
                                                  </TD>
                                                </tr>

                                                <tr>

                                                            <TD class=bodytext colSpan=2 height="100%" align="center">
                                                              <div align="left">
                                                            <input type="hidden" name=ecom_hostname value="www.garraways.co.uk">

                                                            <input type="hidden" name="ecom_theme_name" value="garraways">

                                                            <font size="2" face="Verdana">Upon submission you will be 

            contacted within 2 working days with a quotation.</font>&nbsp;            
            <INPUT class="button"  onclick="if (checkleasedata()) { leasingform.submit(); }" type="button" value="Send Lease"  name="Submit22">                                                  
                                                              </div></TD>
                                                </tr>

                                                <tr>

                                                            <td width="174"></td>

                                                            <td height="3" width="615"></td>
                                                </tr>

                                    </FORM></TBODY>
</TABLE>

</DIV>

 

</body>

</html>
