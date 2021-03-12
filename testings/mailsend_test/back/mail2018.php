<?php
require_once "Mail.php";

$from = "test <test@bshop.webclinicmailer.co.uk>";
$to = "Manu <manuvprabhu@gmail.com>";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";

$host = "mail.bshop.webclinicmailer.co.uk";
$username = "test@bshop.webclinicmailer.co.uk";
$password = "Zjbk68@5";

$headers = array ('From' => $from,  'To' => $to, 'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));

$mail = $smtp->send($to, $headers, $body);

>?

