<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
//use PHPMailer\PHPMailer\PHPMailer;
///use PHPMailer\PHPMailer\Exception;
	  require_once('PHPMailer/src/Exception.php');
	  require_once('PHPMailer/src/SMTP.php');
      require_once("PHPMailer/src/PHPMailer.php");
//Load composer's autoloader
//require 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'auth.smtp.1and1.co.uk';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'sales-5@s426558865.onlinehome.info';                 // SMTP username
    $mail->Password = '-s8Uh:cz-ECL9/N9';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('test@discount-mobility.co.uk', 'dm');
    //$mail->addAddress('latheeshgeorge@gmail.com', 'Joe User');     // Add a recipient
   // $mail->addAddress('latheesh.george@calpinetech.com'); 
    //$mail->addAddress("manu.venketesh@gmail.com","Manu"); 
              // Name is optional
    // $mail->addAddress("online.orders@discount-mobility.co.uk","dm"); 

     //$mail->addAddress("sales@dincwear.eu","dinc");
     $mail->addAddress("test@thewebclinic.co.uk","latheesh");
     //$mail->addAddress("manuvprabhu1@outlook.com","Manu");
     $mail->addReplyTo('test@thewebclinic.co.uk');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->Subject = 'Here is the subject';
    $content     = 'callback notification';
	$mail->Body = $content;
	echo $content;
    $mail->isHTML(true);                                 // Set email format to HTML

    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   $mail->send();
    echo 'Message has been sent';
?>
