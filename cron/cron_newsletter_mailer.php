<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: Latheesh
	# Modified On	: 15 march 2013
	#################################################################*/
	//require_once("/var/www/html/webclinic/bshop4/config_db.php");

	require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");//live
	$proceed_curl = false;
	$proceed = false;
	$sql_check = "SELECT * FROM newsletter_disable";
	$res_check = $db->query($sql_check);
	$row_check = $db->fetch_array($res_check);
	if($row_check['disable_now'] == 1) {
		if($row_check['disable_end_on'] <= date("Y-m-d")) {
			$proceed = true;
	 	}
	}
	else {
		$proceed = true;
	}
		/* commented for uploading product review only Aug2014
	$sql_inactive = "SELECT * FROM inactivecustomers_cron_main ORDER BY send_date asc LIMIT 1 ";
	$res_inactive = $db->query($sql_inactive);
	*/
	if($proceed == true) {
	$sql = "SELECT * FROM newsletter_cron_main ORDER BY send_date asc LIMIT 1 ";
	$res = $db->query($sql);
		if($db->num_rows($res))
		{
		   $proceed_curl = true;
		}
	}
	if($db->num_rows($res_inactive))
	{
		$proceed_curl = true;
	}
	if($proceed_curl == true) {
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

		curl_close ($curlSession);
	}
	$max_cnt = 500;
	//$limi_news = 500;
	
	$limi_news = 300; // this line is used to reduce the number of newsletter sending our perhour to 300 rather than 500
	
	$limit_inact = "";
		/* commented for uploading product review only Aug2014

	if($db->num_rows($res_inactive)>0)
	{
		$row_inactive = $db->fetch_array($res_inactive);
		if($row_inactive['main_id']){
			$limit_inact = "";
			$sql_check = $sql_mails = "SELECT mail_id FROM inactivecustomers_cron_mails WHERE main_id=".$row_inactive['main_id']."";
			$res_check = $db->query($sql_check);
			 $tot_cntinact = $db->num_rows($res_check)."*";
			if($db->num_rows($res_check)>0)
			{
				if($db->num_rows($res_check)>50)
				{
					$limit_inact = " LIMIT 0,50";	
					$limi_news   = $limi_news - 50;		      
				}
				else
				{
					 $limi_news   = $limi_news - $tot_cntinact;
				}
			}
			$sql_mails_in = "SELECT * FROM inactivecustomers_cron_mails WHERE main_id=".$row_inactive['main_id']." ORDER BY mail_id asc $limit_inact";
			$res_mails_in = $db->query($sql_mails_in);
			if($db->num_rows($res_mails_in)>0)
			{
			$mail_id_string_in = '';
			while($row_mails_in = $db->fetch_array($res_mails_in)) {
				$send_cust_array_in[$row_mails_in['mail_id']] 	= array('email'=>stripslashes($row_mails_in['send_email']),'name'=>stripslashes($row_mails_in['send_name']));
				$mail_id_string_in .= $row_mails_in['mail_id'].",";
			}
			$mail_id_string_in = substr($mail_id_string_in,0,-1);
			if($row_inactive['site_type'] == 'v4') {
				if(strpos($row_inactive['hostname'],"www.") === false) {
					$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
				} else {
					$temp_a = explode(".",$row_inactive['hostname']);
					$default_from = $temp_a[1].'@'.$temp_a[1].'.webclinicmailer.co.uk';
				}
			} else {
				$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
			}
			send_Newsletter_emails_to_customers($default_from,$row_inactive['email_from'],stripslashes($row_inactive['email_subject']),stripslashes($row_inactive['email_content']),$row_inactive['hostname'],$send_cust_array_in);
									//echo "<br/>inactive<br/>";

			//echo $default_from;
			//echo '<br>sub :'.$row_inactive['email_subject'];
			//echo '<br> From: '.$row_inactive['email_from'];
			//echo '<br>'.$row_inactive['email_content'];
			//print_r($send_cust_array_in);
			//exit;
			$db->query("DELETE FROM inactivecustomers_cron_mails WHERE mail_id IN ($mail_id_string_in)");
			}
			$sql_mail_count = "SELECT count(*) as cnt from inactivecustomers_cron_mails WHERE main_id=".$row_inactive['main_id'];
			$res_mail_count = $db->query($sql_mail_count);
			$row_mail_count = $db->fetch_array($res_mail_count);
			if($row_mail_count['cnt'] == 0) {
				$db->query("DELETE FROM inactivecustomers_cron_main WHERE main_id=".$row_inactive['main_id']);
			}			
		}
	}	
	*/ 
	$sql_review = "SELECT * FROM product_review_cron_mails";
	$res_review = $db->query($sql_review);
	$tot_review = $db->num_rows($res_review);
	$limit_review = "";
    $proceed_news = true;
	if($db->num_rows($res_review)>0)
	{
				if($db->num_rows($res_review)>50)
				{
					$limit_review = " LIMIT 0,50";	
					$limi_news   = $limi_news - 50;		      
				}
				else
				{
					 $limi_news   = $limi_news - $tot_review;
				}
			$sql_mails_rw = "SELECT * FROM product_review_cron_mails  ORDER BY mail_id asc $limit_review";
			$res_mails_rw = $db->query($sql_mails_rw);
			if($db->num_rows($res_mails_rw)>0)
			{
				$mail_id_string_rw = '';
				$send_cust_array_rw = array();
				while($row_mails_rw = $db->fetch_array($res_mails_rw)) 
				{
					
					$send_cust_array_rw[$row_mails_rw['mail_id']] 	= array('email'=>stripslashes($row_mails_rw['send_email']),'name'=>stripslashes($row_mails_rw['send_name']),'from'=>stripslashes($row_mails_rw['send_email_from']),'subject'=>stripslashes($row_mails_rw['send_subject']),'content'=>stripslashes($row_mails_rw['send_content']),'hostname'=>stripslashes($row_mails_rw['send_hostname']),'send_site_id'=>stripslashes($row_mails_rw['send_site_id']));
					$mail_id_string_rw .= $row_mails_rw['mail_id'].",";
				
				
					if($row_mails_rw['send_site_type'] == 'v4') {
						if(strpos($row_mails_rw['send_hostname'],"www.") === false) {
							$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
						} else {
							$temp_a = explode(".",$row_mails_rw['send_hostname']);
							$default_from = $temp_a[1].'@'.$temp_a[1].'.webclinicmailer.co.uk';
						}
					} else {
						$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
					}
				}	
					$proceed_news = false;
					send_Newsletter_emails_to_customers_review($default_from,$send_cust_array_rw);
				
				$mail_id_string_rw = substr($mail_id_string_rw,0,-1);
				
						//echo "<br/>review<br/>";

				//echo $default_from;
				//echo '<br>sub :'.$row_mails_rw['send_subject'];
				//echo '<br> From: '.$row_mails_rw['send_email_from'];
				//echo '<br>'.$row_mails_rw['email_content'];
				//print_r($send_cust_array_rw);
				//exit;
				$db->query("DELETE FROM product_review_cron_mails WHERE mail_id IN ($mail_id_string_rw)");
			}			
	}
	$sql_incomp = "SELECT * FROM incomplete_order_cron_mails";
	$res_incomp = $db->query($sql_incomp);
	$tot_incomp = $db->num_rows($res_incomp);
	$limit_incomp = "";
	if($db->num_rows($res_incomp)>0)
	{
				if($db->num_rows($res_incomp)>50)
				{
					$limit_incomp = " LIMIT 0,50";	
					$limi_news   = $limi_news - 50;		      
				}
				else
				{
					 $limi_news   = $limi_news - $tot_incomp;
				}
			$sql_mails_incomp = "SELECT * FROM incomplete_order_cron_mails  ORDER BY mail_id asc $limit_incomp";
			$res_mails_incomp = $db->query($sql_mails_incomp);
			if($db->num_rows($res_mails_incomp)>0)
			{
				$mail_id_string_incomp = '';
				$send_cust_array_incomp = array();
				while($row_mails_incomp = $db->fetch_array($res_mails_incomp)) 
				{
					
					$send_cust_array_incomp[$row_mails_incomp['mail_id']] 	= array('email'=>stripslashes($row_mails_incomp['send_email']),'name'=>stripslashes($row_mails_incomp['send_name']),'from'=>stripslashes($row_mails_incomp['send_email_from']),'subject'=>stripslashes($row_mails_incomp['send_subject']),'content'=>stripslashes($row_mails_incomp['send_content']),'hostname'=>stripslashes($row_mails_incomp['send_hostname']),'send_site_id'=>stripslashes($row_mails_incomp['send_site_id']));
					$mail_id_string_incomp .= $row_mails_incomp['mail_id'].",";
				
				
					if($row_mails_incomp['send_site_type'] == 'v4') {
						if(strpos($row_mails_incomp['send_hostname'],"www.") === false) {
							$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
						} else {
							$temp_a = explode(".",$row_mails_incomp['send_hostname']);
							$default_from = $temp_a[1].'@'.$temp_a[1].'.webclinicmailer.co.uk';
						}
					} else {
						$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
					}
				}	
					$proceed_news = false;
					send_Newsletter_emails_to_customers_incomp($default_from,$send_cust_array_incomp);
				
				$mail_id_string_incomp = substr($mail_id_string_incomp,0,-1);
				
						//echo "<br/>review<br/>";

				//echo $default_from;
				//echo '<br>sub :'.$row_mails_rw['send_subject'];
				//echo '<br> From: '.$row_mails_rw['send_email_from'];
				//echo '<br>'.$row_mails_rw['email_content'];
				//print_r($send_cust_array_rw);
				//exit;
				$db->query("DELETE FROM incomplete_order_cron_mails WHERE mail_id IN ($mail_id_string_incomp)");
			}			
	}
if($proceed_news == true)
{			
	if($proceed == true) {
	// Extracting cart Id, Session Id From Cart table
	//echo 'Sending';
	$LIMIT_NEW = "";
	$sql = "SELECT * FROM newsletter_cron_main ORDER BY send_date asc LIMIT 1 ";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	if($row['main_id']){		
		$LIMIT_NEW = " LIMIT 0,$limi_news";
		$sql_mails = "SELECT * FROM newsletter_cron_mails WHERE main_id=".$row['main_id']." ORDER BY mail_id asc $LIMIT_NEW";
		$res_mails = $db->query($sql_mails);
		$mail_id_string = '';
		while($row_mails = $db->fetch_array($res_mails)) {
			$send_cust_array[$row_mails['mail_id']] 	= array('email'=>stripslashes($row_mails['send_email']),'name'=>stripslashes($row_mails['send_name']));
			$mail_id_string .= $row_mails['mail_id'].",";
		}
		$mail_id_string = substr($mail_id_string,0,-1);
		if($row['site_type'] == 'v4') {
			if(strpos($row['hostname'],"www.") === false) {
				$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
			} else {
				$temp_a = explode(".",$row['hostname']);
				$default_from = $temp_a[1].'@'.$temp_a[1].'.webclinicmailer.co.uk';
			}
		} else {
			$default_from = 'newsletter@bshop.webclinicmailer.co.uk';
		}
		send_Newsletter_emails_to_customers($default_from,$row['email_from'],stripslashes($row['email_subject']),stripslashes($row['email_content']),$row['hostname'],$send_cust_array);
		//echo "<br/>news<br/>";
		//echo $default_from;
		//echo '<br>sub :'.$row['email_subject'];
		//echo '<br> From: '.$row['email_from'];
		//echo '<br>'.$row['email_content'];
		//print_r($send_cust_array);
		//exit;
		$db->query("DELETE FROM newsletter_cron_mails WHERE mail_id IN ($mail_id_string)");
		
		 $sql_mail_count = "SELECT count(*) as cnt from newsletter_cron_mails WHERE main_id=".$row['main_id'];
		$res_mail_count = $db->query($sql_mail_count);
		$row_mail_count = $db->fetch_array($res_mail_count);
		if($row_mail_count['cnt'] == 0) {
			$db->query("DELETE FROM newsletter_cron_main WHERE main_id=".$row['main_id']);
		}
	}
	}
}

$db->db_close();
	function send_Newsletter_emails_to_customers($default_from,$from,$subject,$content,$ecom_hostname,$cust_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
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
					//$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
					$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Marketing");
					$mail->AddBCC($snd_email,"$snd_name");  
					$mail->Send();
					$mail->ClearBCCs();
					$mail->ClearAddress();
				}
			}
			if ($send_var)
			{
				//$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
				$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Marketing");
				$mail->Send();
			}	
		}
	}
	function send_Newsletter_emails_to_customers_review($default_from,$cust_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		if (count($cust_arr))
		{
			include("class.phpmailer_review.php");
			//SMTP Mail function starts
			$send_var 				= 0;
			$mail 					= new PHPMailer();
			$mail->Username     	= $default_from; //Fake from address
			$mail->From     		= $default_from; //Fake from address
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->ClearBody();
			$mail->ClearSubject();
			foreach ($cust_arr as $k=>$v)
			{	
		
				$send_site_id = $v['send_site_id'];	
				if($send_site_id>0)
				{
				$sql = "SELECT newsletter_replytoaddress FROM general_settings_sites_common WHERE sites_site_id=".$send_site_id." LIMIT 1";
				$res_admin 			= $db->query($sql);
				$fetch_arr_admin 	= $db->fetch_array($res_admin);
				$newsletter_replytoaddress = $fetch_arr_admin['newsletter_replytoaddress'];	
				}
				if($newsletter_replytoaddress!='')
				{
				   $from 			 = $newsletter_replytoaddress;
				}
				else
				{
				   $from             = $v['from'];
				}   
				$ecom_hostname           = $v['hostname'];
				$mail->FromName 		= $ecom_hostname; //Fake from name
				$mail->AddReplyTo($from,$ecom_hostname);
				$mail->Subject 			=  $v['subject'];
				$mail->Body     		=  $v['content'];
				$snd_name 				= $v['name'];
				$snd_email 				= $v['email'];
					
				$send_var=0;
				//$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
				$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Product Reviews");
				$mail->AddBCC($snd_email,"$snd_name");
				$mail->AddBCC("sony.joy@thewebclinic.co.uk","Sony Joy");
				$mail->AddBCC("jm@thewebclinic.co.uk","John - UK");
				$mail->Send();
				
				$mail->ClearAllRecipients();
				$mail->ClearReplyTos();
				$mail->ClearBody();
				$mail->ClearSubject();
				$mail->ClearFromName();
				
			}
		}
	}
	function send_Newsletter_emails_to_customers_incomp($default_from,$cust_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		if (count($cust_arr))
		{
			include("class.phpmailer_incomp.php");
			//SMTP Mail function starts
			$send_var 				= 0;
			$mail 					= new PHPMailer();
			$mail->Username     	= $default_from; //Fake from address
			$mail->From     		= $default_from; //Fake from address
			$mail->ClearAllRecipients();
			$mail->ClearReplyTos();
			$mail->ClearBody();
			$mail->ClearSubject();
			foreach ($cust_arr as $k=>$v)
			{	
		
				$send_site_id = $v['send_site_id'];	
				if($send_site_id>0)
				{
				$sql = "SELECT newsletter_replytoaddress FROM general_settings_sites_common WHERE sites_site_id=".$send_site_id." LIMIT 1";
				$res_admin 			= $db->query($sql);
				$fetch_arr_admin 	= $db->fetch_array($res_admin);
				$newsletter_replytoaddress = $fetch_arr_admin['newsletter_replytoaddress'];	
				}
				if($newsletter_replytoaddress!='')
				{
				   $from 			 = $newsletter_replytoaddress;
				}
				else
				{
				   $from             = $v['from'];
				}   
				$ecom_hostname           = $v['hostname'];
				$mail->FromName 		= $ecom_hostname; //Fake from name
				$mail->AddReplyTo($from,$ecom_hostname);
				$mail->Subject 			=  $v['subject'];
				$mail->Body     		=  $v['content'];
				$snd_name 				= $v['name'];
				$snd_email 				= $v['email'];
					
				$send_var=0;
				//$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
				$mail->AddAddress('sales-2@webclinicmailer.co.uk',"Sales Order Incomplete");
				$mail->AddBCC($snd_email,"$snd_name");
				$mail->AddBCC("sony.joy@thewebclinic.co.uk","Sony Joy");
				$mail->AddBCC("jm@thewebclinic.co.uk","John - UK");
				$mail->Send();
				
				$mail->ClearAllRecipients();
				$mail->ClearReplyTos();
				$mail->ClearBody();
				$mail->ClearSubject();
				$mail->ClearFromName();
				
			}
		}
	}
?>
