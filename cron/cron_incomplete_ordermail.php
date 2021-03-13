<?php
	/*#################################################################
	# Script Name 	: cron_incomplete_ordermail.php
	# Description 	: Page to Send Reminder Email to Customers about the incomplete order
	# Coded by 		: Latheesh
	# Created on	: 24-March-2015
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	 //define('ORG_DOCROOT','/var/www/html/webclinic/bshop4'); // Local path
		define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live path

		require_once(ORG_DOCROOT."/config.php");

		require_once(ORG_DOCROOT."/config_db.php");
		require_once(ORG_DOCROOT.'/functions/functions.php');
		require_once(ORG_DOCROOT.'/includes/session.php');
		require_once(ORG_DOCROOT.'/includes/price_display.php');

		//require_once("/var/www/vhosts/bshop4.co.uk/httpdocs/config_db.php");//live
	 $sql_check	=	"SELECT  sites_site_id,incomplete_mail_interval FROM general_settings_sites_common WHERE is_incompleteorder_site = 1";//echo $sql_check;echo "<br>";
	$res_check	=	$db->query($sql_check);
	$cart_idarr = array();
	$do_n_proceed = false;
	$cart_ids = '';
	$cartid_incomplete='';
	if($db->num_rows($res_check) > 0)
	{
		while($row_check = $db->fetch_array($res_check))
			{
			$site_id 	= $row_check['sites_site_id'];
			$incomplete_mail_interval   = ($row_check['incomplete_mail_interval'])?$row_check['incomplete_mail_interval']:2;  

			$sql_site					=	"SELECT site_domain FROM sites WHERE site_id = ".$site_id." LIMIT 1";//echo $sql_site;echo "<br>";
			$ret_site					=	$db->query($sql_site); 
			$row_site					=	$db->fetch_array($ret_site);
			$sites_hostname				=	$row_site['site_domain'];
			//
			$img_url   = "http://".$sites_hostname."/images/".$sites_hostname; 

			/*$sql_email	=	"SELECT
			template_lettertitle,template_lettersubject,template_content,template_code
			FROM
			common_emailtemplates
			WHERE
			template_lettertype = 'REMINDER_SUBMIT_REVIEW' 
			LIMIT 1";echo $sql_email;echo "<br>";*/
			$sql_email	=	"SELECT
			*
			FROM
			general_settings_site_letter_templates
			WHERE
			lettertemplate_letter_type = 'INCOMPLETE_ORDER_CUSTOMER'
			AND
			sites_site_id = ".$site_id."
			LIMIT 1";//echo $sql_email;echo "<br>";
			$ret_email	=	$db->query($sql_email);
			if($db->num_rows($ret_email))
			{
				$row_email	=	$db->fetch_array($ret_email);
			}

			 $sql_order  	= "SELECT  
			DISTINCT cartid_incomplete,order_id,order_date,order_custemail,order_custsurname  
			FROM 
			orders WHERE sites_site_id =  $site_id AND order_status='NOT_AUTH' 	";
			/*if($incomplete_mail_interval>0)
			{					
				$sql_order  .= "AND order_date <= DATE_SUB(NOW(),INTERVAL ".$incomplete_mail_interval." DAY)";					
			}
			*/ 
			$date = strtotime(date('Y-m-d') . ' -'.$incomplete_mail_interval.' day');
			$date = date('Y-m-d', $date);
			//$start_date  = '2015-04-17 00:00:00';
			//$end_date    = '2015-04-17 23:59:59';
			$start_date  = $date.' 00:00:00';
			$end_date    = $date.' 23:59:59';
				if($incomplete_mail_interval>0)
			{					
				$sql_order  .= "AND order_date BETWEEN '$start_date' AND '$end_date'";					
			}
			$sql_order .= " GROUP BY cartid_incomplete ";
			echo $sql_order;
				$ret_order  =  $db->query($sql_order);
			if($db->num_rows($ret_order))
			{
				while($row_order = $db->fetch_array($ret_order))
				{
					$do_n_proceed = false;
					$cart_ids = $row_order['cartid_incomplete'];
					if($cart_ids!='')
					{	
						$cart_idarr = explode('~',$cart_ids);

						if(count($cart_idarr)>0)
						{
							foreach($cart_idarr as $k=>$kc)
							{
								$sql_cart_chk = "SELECT cart_id FROM cart WHERE cart_id = $kc AND sites_site_id = $site_id LIMIT 1";
								$ret_cart_cht = $db->query($sql_cart_chk);
								if($db->num_rows($ret_cart_cht)==0)
								{
									$do_n_proceed = true;
								}
							}
						}
					}
					
					if($do_n_proceed==false)
					{
                      
						if($row_email['lettertemplate_contents'] != "")
						{
							$mailcontents	=	stripslashes($row_email['lettertemplate_contents']);
							$customer_name = $row_order['order_custsurname'];
							$customer_name = addslashes($customer_name);
							$customer_email	=	$row_order['order_custemail'];
							$order_id		=	$row_order['order_id'];
							$order_date		=	$row_order['order_date'];
							if($cart_ids!='')
							{
								$cartid_incomplete = $cart_ids;
							}
							//echo "test".$cartid_incomplete;
							if($cartid_incomplete!='')
							{
								$sites_hostname_link   =    "<a href=\"http://".$sites_hostname."\" target=\"_blank\" style=\"color: #139ac4;text-decoration:none\">".$sites_hostname."</a>"; 
								$email_link			   =	"<a href=\"http://".$sites_hostname."/incomplete-ord".$order_id.".html\" target=\"_blank\">http://".$sites_hostname."/incomplete-ord".$order_id.".html</a>";
								$email_link =         "<a href=\"http://".$sites_hostname."/incomplete-ord".$order_id.".html\" target=\"_blank\">here</a>";

								$search_arr     = array('[orderid]','[orderdate]','[cust_name]','[link]','[domain]'); 
								$rp_arr         = array($order_id,$order_date,$customer_name,$email_link,$sites_hostname_link);
								$mailcontents	= str_replace($search_arr,$rp_arr,$mailcontents);
								//echo $mailcontents; 
								$sql_review_chk = " SELECT * FROM incomplete_order_mail WHERE order_id=$order_id LIMIT 1 ";
								$ret_review_chk = $db->query($sql_review_chk);
								$proceed_mail = false;
								if($db->num_rows($ret_review_chk)>0)
								{
									$row_review_chk = $db->fetch_array($ret_review_chk);
									if($row_review_chk['incomplete_mail_sent']=='No')
									{
										$proceed_mail = true;
										$upd_review		=	"UPDATE incomplete_order_mail SET incomplete_mail_sent = 'Yes' WHERE order_id = ".$order_id;
										$ret_review		=	$db->query($upd_review);
									}
								}
								else
								{
									$proceed_mail = true;
									$ins_review		=	"INSERT INTO incomplete_order_mail (order_id,incomplete_mail_sent,order_cart_ids) VALUES (".$order_id.",'Yes','".$cartid_incomplete."')";//echo $ins_review;echo "<br>";
									$ret_review		=	$db->query($ins_review);
								}
								if($proceed_mail == true)
								{
									$ins_cronreview	="INSERT INTO incomplete_order_cron_mails  
									(send_email_from,send_name,send_email,send_subject,send_content,send_hostname,send_site_id)
									VALUES
										('".$row_email['lettertemplate_from']."','".$customer_name."','".$customer_email."',
										'".$row_email['lettertemplate_subject']."','".addslashes($mailcontents)."',
										'".$sites_hostname."',".$site_id.")";
									//echo $ins_cronreview;echo "<br>";
									$ret_cronreview=	$db->query($ins_cronreview);						

								}
								//echo $mailcontents;echo "<br>";
								$mailcontents	=	"";
							}

						}
					}
					else
					{
					   //echo $row_order['order_id'];
					}

				}
			}
		    
		}
	} 	
?>		
