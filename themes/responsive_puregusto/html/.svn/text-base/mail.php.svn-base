<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<link href="./images/<? echo $HTTP_POST_VARS["ecom_hostname"] ?>/<? echo $HTTP_POST_VARS["ecom_theme_name"] ?>.css" type="text/css" rel="stylesheet">
<title>Leasing Form</title>
<style>
a:active			{ color: #000000; text-decoration:none; }
a:hover				{ color: #000000; text-decoration:none; }
a:visited			{ color: #000000; text-decoration:none; }
a:link				{ color: #000000; text-decoration:none; }

</style>

</head>
<body class="popupwindow" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"><?php

	$message = "Name : " .  $HTTP_POST_VARS["Name"] . "\nCompany Name : " . $HTTP_POST_VARS["Company"] . " \nAddress1 : " . $HTTP_POST_VARS["Address1"] . "\nAddress2 : " . $HTTP_POST_VARS["Address2"];
	$message = $message . "\nTown : " . $HTTP_POST_VARS["Town"] . "\nPostcode : " . $HTTP_POST_VARS["Postcode"] ."\nEmail : " . $HTTP_POST_VARS["Email"];
	$message = $message . "\nTelephone Number : " . $HTTP_POST_VARS["Tel"];
	if($HTTP_POST_VARS["Fax"]) {  
		$message = $message . "\nFax Number : " . $HTTP_POST_VARS["Fax"] .  "\n";
	}   
	if($HTTP_POST_VARS["Product"]) {  
		$message = $message . "\nProduct : " . $HTTP_POST_VARS["Product"];
	}
	if($HTTP_POST_VARS["SubjectOther"]) {
		$message = $message . "\nSubject : " . $HTTP_POST_VARS["SubjectOther"]; 
	}
	else
	{ 
		$message = $message . "\nSubject : " . $HTTP_POST_VARS["Subject"]; 
	}
	if($HTTP_POST_VARS["Brochure1"]) {
		$message = $message . "\nRequested : Full Brochure Pack";
	}
	else
	{
		if($HTTP_POST_VARS["Brochure2"]) 
		{
		$message = $message . "\nRequested: Espresso Machines";
		}
		if($HTTP_POST_VARS["Brochure3"]) 
		{
		$message = $message . "\nRequested: Bean To Cup";
		}
		if($HTTP_POST_VARS["Brochure4"]) 
		{
		$message = $message . "\nRequested: Cappuccino Systems";
		}
		if($HTTP_POST_VARS["Brochure5"]) 
		{
		$message = $message . "\nRequested: Hot Choc";
		}
		if($HTTP_POST_VARS["Brochure6"])
		{
		$message = $message . "\nRequested: Kenco Singles";
		}
		if($HTTP_POST_VARS["Brochure7"]) 
		{
		$message = $message . "\nRequested: Filter Coffee Equipment";
		}
	}
	if($HTTP_POST_VARS["Brochure8"]) 
	{
		$message = $message . "\nRequested: Include Product Price List\n";
	}
		
	
	
	if($HTTP_POST_VARS["Comments"]) {     
		$message = $message . "\nComments : " . $HTTP_POST_VARS["Comments"]; 
	}   
  	if($HTTP_POST_VARS["ContactRequested"])
	{  
		$message = $message . "\n\nPlease Contact Me As Soon As Possible About This Subject.\nThank You";
	}
	$headers = "From: " . $HTTP_POST_VARS["Name"] . " <" .$HTTP_POST_VARS["Email"] . ">";
		$address = "contactus@garraways.co.uk";
	mail($address, "Contact Us Form", $message, $headers);
?>
<p class="standardtxt">Your question has been sent. Thank 
	you.</p>
   <a href="#" onClick="window.close()">Close Window</a>
</body>
</html>