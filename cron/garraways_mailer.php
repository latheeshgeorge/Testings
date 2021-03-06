<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");
	
	//require '../config_db.php';
	
	$ecom_hostname = 'www.puregusto.co.uk';
	if ($ecom_hostname == SECURED_URL or $ecom_hostname == SECURED_URL_ALIAS)
	{
		$dn						= base64_decode($_REQUEST['bsessid']);
		$ecom_hostname 	= $dn;
		$protectedUrl = TRUE;
	}

//#Getting site details
	$sql_site = "SELECT 
				a.site_id,a.site_domain
			FROM 
					sites a
			WHERE 
				(a.site_domain like '".$ecom_hostname."' 
				OR a. site_domain_alias like '".$ecom_hostname."' )
			LIMIT 
				1";
	$res_site = $db->query($sql_site);
	list($ecom_siteid, $ecom_hostname) = $db->fetch_array($res_site);
	
	$proceed = true;
	if($proceed == true) {
	// Extracting cart Id, Session Id From Cart table
	//echo 'Sending';
	$sql = "SELECT * FROM garrawaysnewsletter_cron_main ORDER BY send_date asc LIMIT 1 ";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	if($row['main_id']){
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
		
		$sql_mails = "SELECT * FROM garrawaysnewsletter_cron_mails WHERE main_id=".$row['main_id']." ORDER BY mail_id asc LIMIT 0,500";
		$res_mails = $db->query($sql_mails);
		$mail_id_string = '';
		while($row_mails = $db->fetch_array($res_mails)) {
			$send_cust_array[$row_mails['mail_id']] 	= array('email'=>stripslashes($row_mails['send_email']),'name'=>stripslashes($row_mails['send_name']),'customers_customer_id'=>stripslashes($row_mails['customers_customer_id']));
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
		}
		
		send_Newsletter_emails_to_customers($default_from,$row['email_from'],stripslashes($row['email_subject']),stripslashes($row['email_content']),$row['hostname'],$send_cust_array);
		//echo $default_from;
		//echo '<br>'.$row['email_subject'];
		//echo '<br>'.$row['email_content'];
		//print_r($send_cust_array);
		//exit;
		$db->query("DELETE FROM garrawaysnewsletter_cron_mails WHERE mail_id IN ($mail_id_string)");
		
		$sql_mail_count = "SELECT count(*) as cnt from garrawaysnewsletter_cron_mails WHERE main_id=".$row['main_id'];
		$res_mail_count = $db->query($sql_mail_count);
		$row_mail_count = $db->fetch_array($res_mail_count);
		if($row_mail_count['cnt'] == 0) {
			$db->query("DELETE FROM garrawaysnewsletter_cron_main WHERE main_id=".$row['main_id']);
		}
	}
}
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
			$sql_bonusrate = "SELECT bonuspoint_rate 
								FROM 
									general_settings_sites_common 
								WHERE 
									sites_site_id = $ecom_siteid 
								LIMIT 
									1";
			$ret_bonusrate = $db->query($sql_bonusrate);
			$row_bonusrate = $db->fetch_array($ret_bonusrate);
			$bonusrate		= $row_bonusrate['bonuspoint_rate'];
			if($bonusrate>0)
				$bonusrate = 1/$bonusrate;
			else
				$bonusrate = 0;
									
			
			foreach ($cust_arr as $k=>$v)
			{
				$snd_name 	= $v['name'];
				$snd_email 	= $v['email'];
				$custid		= $v['customers_customer_id'];
				$bonus_points = 0;
				$bonus_values = 0;
				if($bonusrate)
				{
					// Get the bonus points for the current customer
					$sql_cust = "SELECT customer_bonus 
								FROM 
									customers 
								WHERE 
									customer_id = $custid 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
					$ret_cust = $db->query($sql_cust);
					if($db->num_rows($ret_cust))
					{
						$row_cust = $db->fetch_array($ret_cust);
						$bonus_value = $row_cust['customer_bonus'] * $bonusrate;
						$bonus_points = $row_cust['customer_bonus'];
						$bonus_values = '&pound;'.$bonus_value;
					}	
				}
				
				$content_temp = $content;
				
				/*$bonus_text = 'Did you know you have <span style="color:#ffae00;">[bonus_points]</span> points worth <span style="color:#ffae00;">[bonus_value] </span>that you can redeem today!';
				if($bonus_points==0)
				{
					$content_temp = str_replace($bonus_text,'',$content_temp);					  		
				}*/	
				$sr_arr = array('[name]','[bonus_points]','[bonus_value]');
				$rp_arr = array($snd_name,$bonus_points,$bonus_values);
				
				$content_temp = str_replace($sr_arr,$rp_arr,$content_temp);					  
				//echo "<br><br>".$content_temp;
				$mail->Body =  $content_temp;
				$send_var=0;
				$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
				$mail->AddBCC($snd_email,"$snd_name");  
				$mail->Send();
				$mail->ClearBCCs();
				$mail->ClearAddress();
				
			}
		}
	}
?>
