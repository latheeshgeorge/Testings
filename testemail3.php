<?php
$to 		= 'sales@dincwear.eu';
//$to 		= 'sony.joy@thewebclinic.co.uk';
$from 		= 'sales@dincwear.eu';
$subject 	= 'Test email using script';
$content 	= 'This is a test email';
$subject 	= 'Test Email';
$message 	= 'This is a test email using script';
$headers 	= 'From: '.$from . "\r\n" .
				'Reply-To: '.$from . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $content, $headers);
?>
