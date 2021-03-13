<?php
	/*#################################################################
	# Script Name 	: cronjob.php
	# Description 	: Page to Send Email Notifications
	# Coded by 		: Randeep
	# Created on	: 07-Oct-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	include_once("../functions/functions.php");
	include('../includes/session.php');
	require_once("../config.php");
	
	$curdate = date("Y-m-d");

	// to Get Sites In Bshop4
	$sitesql = "SELECT site_id FROM sites WHERE site_status='Live'";//
	$siteres = $db->query($sitesql);
	while($siterow = $db->fetch_array($siteres)) 
	{
				// Extracting Newsletters To send
				$sql = "SELECT news_id, newsletter_title, newsletter_content, set_senttype, week_day, month_date 
							FROM customer_email_notification 
								 WHERE email_status='1' AND sites_site_id=".$siterow['site_id'];
				$res = $db->query($sql);
				while($row = $db->fetch_array($res)) 
				{
					$process = 0;
					// To check weekly mails or monthly mails 
					if($row['set_senttype'] == 'Week') 
					{
						if($row['week_day'] == date("D")) 
						{
							$newsletterTitle = $row['newsletter_title'];
							$newsletterContent = $row['newsletter_content'];							
							$process = 1;
						}					
					} else {
						if($row['month_date'] == date("d"))
						{
							$newsletterTitle = $row['newsletter_title'];
							$newsletterContent = $row['newsletter_content'];		
							$process = 1;					
						}
					}
				if($process == 1)
				{			 
					// Selecting Customers
					$cust_sql = "SELECT customer_email_7503
										FROM customers
											WHERE customer_prod_disc_newsletter_receive='Y' AND customer_hide='0'
												  AND sites_site_id=".$siterow['site_id'];
					$cust_res = $db->query($cust_sql);						
					while($cust_row = $db->fetch_array($cust_res)) 
					{
						$headers  = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$headers .= "From: NewsLetter<test@test.com>\r\n";
						$subject  = $newsletterTitle;
						$mailcontents = $newsletterContent;
						
						$to 	  = $cust_row['customer_email_7503'];	
						mail($to,$mailsubject,$mailcontents,$headers);
					}
				}	
		  }	// Notification While Ends Here
		
	} // Site Row Ends Here	
	
	
	
	
?>
