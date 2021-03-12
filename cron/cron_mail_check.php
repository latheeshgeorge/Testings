<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	$proceed = true;
		$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, "http://webclinicmailer.co.uk/qmail1.php");
		curl_setopt($curlSession, CURLOPT_HEADER, 0);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curlSession, CURLOPT_TIMEOUT,60); 

		$response = curl_exec ($curlSession);
		
		if (curl_error($curlSession)){
			$output['Status'] = "FAIL";
			$output['StatusDetail'] = curl_error($curlSession);
		}
		//print_r($output);
		//echo "Started = ".date('r');
		curl_close ($curlSession);
		$default_from 			= 'garraways@garraways.webclinicmailer.co.uk';
		$send_cust_array[1] 	= array('email'=>'sony.joy@thewebclinic.co.uk','name'=>'Sony Joy');
		$send_cust_array[2] 	= array('email'=>'sony.joy@calpinetech.com','name'=>'Sony Joy');
		$send_cust_array[3] 	= array('email'=>'sonyjoy007@gmail.com','name'=>'Sony Joy');
		//$send_cust_array[3] 	= array('email'=>'sonyoy007@gmail.com','name'=>'Sony Joy');
		//$send_cust_array[4] 	= array('email'=>'sonyoy007@yahoo.in','name'=>'Sony Joy');
		//$send_cust_array[5] 	= array('email'=>'santhosh.sivan@thewebclinic.co.uk','name'=>'Santhosh Sivan');
		send_Newsletter_emails_to_customers($default_from,'test@test.com','This is the test email','This is the test email content','www.peterfieldonlinegolfshop.co.uk',$send_cust_array);
		//echo "<br><br><br>Done = ".date('r');
		//echo "<br><br> Send to <br><pre>";
		//var_dump($send_cust_array);
		//echo "</pre>";
		
		echo "done";
	
	
	
	function send_Newsletter_emails_to_customers($default_from,$from,$subject,$content,$ecom_hostname,$cust_arr)
	{
		if (count($cust_arr))
		{
			include("class.phpmailer.php");
			//SMTP Mail function starts
			$send_var 				= 0;
			$mail 					= new PHPMailer();
			$mail->Username     	= $default_from; //Fake from address
			$mail->From     		= $default_from; //Fake from address
			$mail->FromName 		= $ecom_hostname; //Fake from name
			$mail->AddReplyTo($from,$ecom_hostname);
			$mail->ClearAddress();
			$mail->ClearBCCs();
			$mail->Subject 			=  $subject;
			$mail->Body     		=  $content;
			foreach ($cust_arr as $k=>$v)
			{
				$snd_name 	= $v['name'];
				$snd_email 	= $v['email'];
				if($send_var < 3)
				{
					$send_var++;
					$mail->AddBCC($snd_email,"$snd_name");
				}
				else
				{
					$send_var=0;
					$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Marketing");
					$mail->AddBCC($snd_email,"$snd_name");  
					$mail->Send();
					$mail->ClearBCCs();
					$mail->ClearAddress();
				}
			}
			if ($send_var)
			{
				$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Marketing");
				$mail->Send();
			}	
		}
	}
?>
