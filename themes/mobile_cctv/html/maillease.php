<?php
if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "<?php echo $ecom_selfhttp ?>www.w3.org/TR/html4/loose.dtd">
<? 	

?>
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
	$message = "Name : " .  $HTTP_POST_VARS["Contact"]. "\nBusiness Name : " . $HTTP_POST_VARS["Business_Name"] . "\nAddress1 : " . $HTTP_POST_VARS["Address1"] . "\nAddress2 : " . $HTTP_POST_VARS["Address2"];
	$message = $message . "\nTown : " . $HTTP_POST_VARS["Town"] . "\nPostcode : " . $HTTP_POST_VARS["PostCode"] ."\nEmail : " . $HTTP_POST_VARS["Email"];
	$message = $message . "\nTelephone Number : " . $HTTP_POST_VARS["Tel"];
	$message = $message . "\nBusiness Type : " . $HTTP_POST_VARS["Business"] . "\nEstablished : " . $HTTP_POST_VARS["Established"];
	
	if($HTTP_POST_VARS["Fax"]) {  
		$message = $message . "\nFax Number : " . $HTTP_POST_VARS["Fax"] .  "\n";
	}   
	
		$message = $message . "\nProduct : " . $HTTP_POST_VARS["Product"] . "\nProduct Price : " . $HTTP_POST_VARS["Product_Price"] . "\nLease Period : " . $HTTP_POST_VARS["Lease_Period"];

	if($HTTP_POST_VARS["Comments"]) {     
		$message = $message . "\nComments : " . $HTTP_POST_VARS["Comments"]; 
	}   
  	if($HTTP_POST_VARS["ContactRequested"])
	{  
		$message = $message . "\n\nPlease Contact Me As Soon As Possible About This Subject.\nThank You";
	}

	$email = $HTTP_POST_VARS["email"];
	$headers = "From: " . $HTTP_POST_VARS["Contact"] . " <" . $HTTP_POST_VARS["Email"] . ">";
	$address = "leasing@garraways.co.uk";
	mail($address, "Leasing Form", $message,$headers);
?>
<p class="standardtxt">Your question has been sent. Thank 
	you.</p>
	<a href="#" onClick="window.close()">Close Window</a>
</body>
</html>
