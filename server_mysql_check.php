<?php

	$dbhost  			= 'localhost';
	$dbuname 			= 'bshop_am4';
	$dbpass  			= 'b$H0pF@Ur9642';
	
	$conn_res 			= mysql_connect($dbhost, $dbuname, $dbpass);
	if (!$conn_res) 
	{	
		$subject = 'Bshop Database Connection Issue';
		$message = 'Database connection error occured on : '.date(DATE_RFC822);
		$headers = 'From: admin@bshop4.co.uk' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		
		mail('sony.joy@thewebclinic.co.uk', $subject, $message, $headers);
		mail('sony.joy@calpinetech.com', $subject, $message, $headers);
		mail('sonyjoy007@gmail.com', $subject, $message, $headers);
		mail('latheesh.george@calpinetech.com', $subject, $message, $headers);
		mail('sonyjoymiphone@gmail.com', $subject, $message, $headers);
                mail('manu.venketesh@calpinetech.com', $subject, $message, $headers);
		exit;
	}
	
	mysql_close($conn_res);
?>
