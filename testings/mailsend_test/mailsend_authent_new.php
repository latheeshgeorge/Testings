<?php
 if (!class_exists('../../PHPMailer\PHPMailer\PHPMailer')) {
	 require_once '../../PHPMailer-6.0.5/src/Exception.php';
	 require_once '../../PHPMailer-6.0.5/src/PHPMailer.php';
	 require_once '../../PHPMailer-6.0.5/src/SMTP.php'; }


    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->SMTPAuth = true;
    //$mail->SMTPKeepAlive = true;  
    // Enable SMTP authentication
    $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
    $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
    $mail->Password = '-s8Uh:cz-ECL9/N9';                           // SMTP password
   	//$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
								//$mail->Username   = "bshopmail3@gmail.com";  // GMAIL username
								//$mail->Password   = "calpine*123";   
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                  // TCP port to connect to

    //Recipients
    $mail->setFrom('info@webclinic.co.uk', 'web');
    //$mail->addAddress('latheeshgeorge@gmail.com', 'Joe User');     // Add a recipient
   // $mail->addAddress('latheesh.george@calpinetech.com'); 
    //$mail->addAddress("manu.venketesh@gmail.com","Manu");
    //online.orders@discount-mobility.co.uk 
              // Name is optional
     $mail->addAddress("manuvprabhu@gmail.com","Manu"); 
              // Name is optional
    //$mail->addAddress("manuvprabhu1@outlook.com","Manu");
    $mail->addAddress("latheesh.george@thewebclinic.co.uk","latheesh"); 
    $mail->addBCC("latheesh.george@calpinetech.com","latheesh"); 

     //$mail->addAddress("sales@dincwear.eu","dinc");
    // $mail->addAddress("latheesh.george@thewebclinic.co.uk","latheesh");
     //$mail->addAddress("manuvprabhu1@outlook.com","Manu");
     $mail->addReplyTo('info@webclinic.co.uk');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->Subject = 'Here is the test subject1';
    $content     = 'Test mail for dinc from support';
	$mail->Body = $content;
	echo $content;
    $mail->isHTML(true);                                 // Set email format to HTML

    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   $mail->send();
   $mail->ClearAllRecipients();
   $mail->SmtpClose();
    echo 'Message has been sent';
    
    
    
    /*
    if (!class_exists('../../PHPMailer\PHPMailer\PHPMailer')) {
	 require_once '../../PHPMailer-6.0.5/src/Exception.php';
	 require_once '../../PHPMailer-6.0.5/src/PHPMailer.php';
	 require_once '../../PHPMailer-6.0.5/src/SMTP.php';
 }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;      
    $mail->SMTPKeepAlive = true;                         // Enable SMTP authentication
    $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
    $mail->Password = '-s8Uh:cz-ECL9/N9';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                  // TCP port to connect to

    //Recipients
    $mail->setFrom('info@puregusto.co.uk', 'pure2');
    //$mail->addAddress('latheeshgeorge@gmail.com', 'Joe User');     // Add a recipient
   // $mail->addAddress('latheesh.george@calpinetech.com'); 
    //$mail->addAddress("manu.venketesh@gmail.com","Manu");
    //online.orders@discount-mobility.co.uk 
              // Name is optional
     //$mail->addAddress("manuvprabhu@gmail.com","Manu"); 
              // Name is optional
    //$mail->addAddress("manuvprabhu1@outlook.com","Manu");
    $mail->addAddress("latheesh.george@thewebclinic.co.uk","latheesh"); 
    $mail->addBCC("latheesh.george@calpinetech.com","latheesh"); 

     //$mail->addAddress("sales@dincwear.eu","dinc");
    // $mail->addAddress("latheesh.george@thewebclinic.co.uk","latheesh");
     //$mail->addAddress("manuvprabhu1@outlook.com","Manu");
     $mail->addReplyTo('info@puregusto.co.uk');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->Subject = 'Here is the test subject2';
    $content     = 'Test mail for dinc from support';
	$mail->Body = $content;
	echo $content;
    $mail->isHTML(true);                                 // Set email format to HTML

    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   $mail->send();
   $mail->ClearAllRecipients();
    echo 'Message has been sent2';
    */ 
?>
