<?php
echo "sddsf";
//display_errors(1);
//use PHPMailer\PHPMailer\PHPMailer;
require("PHPMailer_5.2.0/class.phpmailer.php");


$mail = new PHPMailer();

 //Server settings
    //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
   // $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
    //$mail->Host = "smtp.1and1.com";
    $mail->Host = "auth.smtp.1and1.co.uk";
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
  //  $mail->Username = 'user@example.com';                 // SMTP username
   // $mail->Password = 'secret';                           // SMTP password
    $mail->Username = "bshop@s426558865.onlinehome.info";  // SMTP username
$mail->Password = "-s8Uh:cz-ECL9/N9"; // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
   // $mail->setFrom('from@example.com', 'Mailer');
    $mail->setFrom('sales-5@sales.webclinicmailer.co.uk', 'Mailernew');
    $mail->addAddress('latheeshgeorge@gmail.com', 'Joe User');     // Add a recipient
    $mail->AddBCC("manu.venketesh@gmail.com","Manu"); 
    $mail->AddBCC("latheesh.george@calpinetech.com","Latheesh");
    $mail->AddBCC("latheesh.george@thewebclinic.co.uk","latheesh"); 
   // $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');



    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body in bold';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    echo 'Message has been sent';
    
    exit;
/*
$mail->IsSMTP();                                      // set mailer to use SMTP
//$mail->Host = "mail.sales.webclinicmailer.co.uk";  // specify main and backup server
$mail->Host = "smtp.1and1.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication

$mail->SMTPSecure ='tls';
$mail->SMTPDebug = 4;
$mail->Port = 587;
$mail->setFrom('sales-5@sales.webclinicmailer.co.uk', 'Mailernew');
//$mail->From = "sales-5@sales.webclinicmailer.co.uk";
//$mail->FromName = "Mailer";
$mail->AddAddress("sales-5@sales.webclinicmailer.co.uk", "latheeshg");
$mail->AddBCC("latheeshgeorge@gmail.com", "latheeshg");
$mail->AddBCC("latheesh.george@thewebclinic.co.uk","latheesh"); 
$mail->AddBCC("manuvprabhu1@outlook.com","Manu"); 
$mail->AddBCC("latheeshgeorge@gmail.com","Latheesh"); 
//$mail->AddBCC("sales-2@webclinicmailer.co.uk"); 
$mail->AddBCC("manu.venketesh@gmail.com","Manu"); 
$mail->AddBCC("latheesh.george@calpinetech.com","Latheesh"); 
$mail->AddBCC("manu.venketesh@calpinetech.com"); 
$mail->AddBCC("manu_calpine@yahoo.com"); 
$mail->AddBCC("latheesh_calpine@yahoo.com"); 
                 // name is optional
$mail->AddReplyTo("sales-5@sales.webclinicmailer.co.uk", "Reply");

$mail->Subject = "Here is the subject";
$mail->Body    = "This is the HTML message body in bold!</b>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

$mail->Send();

echo "Message has been sent";
*/ 
?>
