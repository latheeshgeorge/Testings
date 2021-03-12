<?php
	
	$toaddress_arr = array(
						//'sonyjoy007@gmail.com',
						//'sony.joy@calpinetech.com',
                        // 'raghunath686@gmail.com',
                        //'manuvprabhu1@outlook.com',  
                        'latheeshgeorge@gmail.com',                     
                        'latheesh.george@thewebclinic.co.uk',
                        'latheesh.george@calpinetech.com'                        
						);
	
	$fromaddress = 'sales-5@sales.webclinicmailer.co.uk';
	$password = '#Miqy263KAlcjeny';
	
	send_via_smtp($fromaddress,$toaddress_arr,$password);		
	
	echo "<br><br>Script Completed";					
	
	function send_via_smtp($fromaddress,$to_arr,$password)
	{
		if (count($to_arr))
		{
			include("class.phpmailer.php");
			//SMTP Mail function starts
			$send_var 				= 0;
			$mail 					= new PHPMailer();
			$mail->IsSMTP(); 
			$mail->SMTPAuth = true; 
			$mail->Host = "mail.sales.webclinicmailer.co.uk"; 
			$mail->Username     	= $fromaddress;
			$mail->Password     	= $password; 
			$mail->From     		= $fromaddress; 
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->ClearBody();
			$mail->ClearSubject();
			//echo "<br>Username ".$mail->Username;
			//echo "<br>Password ".$mail->Password;	
			
			foreach ($to_arr as $k=>$v)
			{	
				$mail->FromName 		= 'SMTP tester';
				$mail->AddReplyTo($from,$ecom_hostname);
				$mail->Subject 			= 'SMTP Tester - '.date('r');
				$mail->Body     		= 'This is the test content';
				$snd_name 				= 'SMTP Test Sender';
				$snd_email 				= $v;
				echo "<br>".$snd_email;	
				$send_var=0;
				$mail->AddAddress('sales-5@sales.webclinicmailer.co.uk',"SMTP Check");
				$mail->AddBCC($snd_email,"$snd_name");
				$mail->Send();
				//if($mail->ErrorInfo!=='') {
				//	echo "<br>Error ".$mail->ErrorInfo;
				//}
								
				$mail->ClearAllRecipients();
				$mail->ClearReplyTos();
				$mail->ClearBody();
				$mail->ClearSubject();
				$mail->ClearFromName();
				
			}
		}
	}
?>
