<?php
	require("functions/functions.php");
	require("includes/session.php");
	require("includes/price_display.php");
	require("includes/urls.php");
	require("config.php");

if(check_IndividualSslActive())
{
	$ecom_selfhttp = "https://";
}
else
{
	$ecom_selfhttp = "http://";
}
	
	// Create our Application instance (replace this with your appId and secret).
	/*$facebook	=	new Facebook(array(
							'appId'  => '383961441657835',
							'secret' => 'af06525c480fc20ca43b72bb355a0f47',
							'appId'  => '458282830898068',
							'secret' => '754709c00b5249663996262d93fdf2e7',
							));*/

	if($_GET['action'] == 'logout')
	{
		echo $facebook->destroySession();
		clear_session_var('ecom_login_customer');
		clear_session_var('ecom_cust_group_exists');
		clear_session_var('ecom_cust_group_prod_array');
		clear_session_var('ecom_cust_group_array');
		clear_session_var('ecom_cust_direct_exists');
		clear_session_var('ecom_cust_direct_disc');
		clear_session_var('ecom_cust_direct_disc');
		clear_session_var('ecom_login_customer_fbid');
		header("Location:".SITE_URL);
		exit;
	}
	else
	{
		//echo "<pre>";print_r($user_data);echo "<br>";
		if($user_data['email'] != "")
		{
			$action_url	=	"";
			$host_url	=	$ecom_selfhttp.$ecom_hostname.'/';
			if($_GET['action'] == 'cart')
			{
				$action_url		=	"<form method='post' action ='".$host_url."cart.html' name='frm_subcart'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$host_url."cart.html'/><input type='hidden' name='cart_mod' value='show_cart'/></form><script type='text/javascript'>document.frm_subcart.submit();window.opener.location.href = '".$host_url."cart.html'; window.close();</script>";
			}
			else if($_GET['action'] == 'enquiry')
			{
				$action_url		=	"<form method='post' action ='".$host_url."enquiry.html' name='frm_subenquire'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$host_url."enquiry.html'/><input type='hidden' name='enq_mod' value='show_enquiry'/></form><script type='text/javascript'>document.frm_subenquire.submit();window.opener.location.href = '".$host_url."enquiry.html'; window.close();</script>";
			}
			else if($_GET['action'] == 'payonaccount')
			{
				$action_url		=	"<form method='post' action ='".$host_url."payonaccount.html' name='frm_subpayonacc'><div style='position:absolute; left:0;top:0;padding:5px;background-color:#CC0000;color:#FFFFFF;font-size:12px;font-weight:bold'>Loading...</div><input type='hidden' name='pass_url' value='".$host_url."'/><input type='hidden' name='action_purpose' value='show_middle'/></form><script type='text/javascript'>document.frm_subpayonacc.submit();</script>";
			}
			else
			{
				/*$action_url		=	"<script type='text/javascript'>window.location.href = '".$host_url."login_home.html';</script>";*/
				$action_url		=	'<script language="javascript">
									window.opener.show_processing();
									window.opener.location.href = "'.$host_url.'login_home.html";  
									window.close();
									</script>';
			}
			$sql_customer	=	"SELECT 
												customer_id,customer_title,customer_mname,customer_surname,customer_fbid
										FROM
												customers
										WHERE
												customer_email_7503 = '".$user_data['email']."' AND sites_site_id = ".$ecom_siteid;
			//echo $sql_customer;echo "<br>";
			$ret_customer	=	$db->query($sql_customer);
			$row_customer	=	$db->fetch_array($ret_customer);
			//echo $row_customer['customer_id'];echo "<br>";
			
			if($row_customer['customer_id'] > 0)
			{
				set_session_var('ecom_login_customer',$row_customer['customer_id']);
				$curname	=	stripslashes($row_customer['customer_title']).stripslashes($row_customer['customer_fname']).' '.stripslashes($row_customer['customer_mname']).' '.stripslashes($row_customer['customer_surname']);
				set_session_var('ecom_login_customer_name',$curname);
				$curname	=	stripslashes($row_customer['customer_title']).stripslashes($row_customer['customer_fname']).' '.stripslashes($row_customer['customer_surname']);
				set_session_var('ecom_login_customer_shortname',$curname);
				
				//echo "customer fbid - ".$row_customer['customer_fbid'];echo "<br>";
				
				if($user_data['id'] > 0 && $row_customer['customer_fbid'] == 0)
				{
					$update_sql =	"UPDATE 
													customers
											SET
													customer_last_login_date = NOW(),
													customer_fbid = ".$user_data['id']."
											WHERE
													customer_id = ".$row_customer['customer_id']." 
											AND
													sites_site_id = $ecom_siteid  
											LIMIT	1";
					set_session_var('ecom_login_customer_fbid',$user_data['id']);
				}
				else
				{				
					$update_sql =	"UPDATE
													customers
											SET
													customer_last_login_date = NOW() 
											WHERE
													customer_id = ".$row_customer['customer_id']." 
											AND
													sites_site_id = $ecom_siteid  
											LIMIT	1";
					set_session_var('ecom_login_customer_fbid',$row_customer['customer_fbid']);
				}
				$db->query($update_sql);
				//echo $update_sql;die();
				//header("Location:".SITE_URL."/login_home.html");
				echo $action_url;die();
				exit;
			}
			else
			{
				$insert_array						=	array();
				$insert_array['sites_site_id']		=	$ecom_siteid;
				$insert_array['customer_activated']	=	1;
				$insert_array['customer_accounttype']=	'personal';
				$insert_array['customer_title']		=	($user_data['gender'] == 'male' ? 'Mr' : 'Ms');
				$insert_array['customer_fname']		=	$user_data['first_name'];
				$insert_array['customer_mname']		=	$user_data['middle_name'];
				$insert_array['customer_surname']	=	$user_data['last_name'];
				
				if(count($user_data['work']) > 0)
				{
					$insert_array['customer_compname']=	$user_data['work'][0]['employer']['name'];
				}
				else
				{
					$insert_array['customer_compname']=	'';
				}
				$location_array		=	array();
				$location_array		=	explode(", ",$user_data['location']);
				
				$insert_array['customer_towncity']		=	$location_array[0];
				$insert_array['customer_statecounty']	=	$location_array[1];
				$insert_array['customer_email_7503']	=	$user_data['email'];
				$insert_array['customer_addedon']		=	'NOW()';
				$insert_array['customer_last_login_date']=	'NOW()';
				$insert_array['customer_fbid']			=	$user_data['id'];
				
				$insert_id		=	$db->insert_from_array($insert_array,'customers');
				$customer_id	=	$db->insert_id();
				
				set_session_var('ecom_login_customer',$customer_id);
				$curname	=	stripslashes($insert_array['customer_title']).stripslashes($insert_array['customer_fname']).' '.stripslashes($insert_array['customer_mname']).' '.stripslashes($row_customer['customer_surname']);
				set_session_var('ecom_login_customer_name',$curname);
				$curname	=	stripslashes($insert_array['customer_title']).stripslashes($insert_array['customer_fname']).' '.stripslashes($insert_array['customer_surname']);
				set_session_var('ecom_login_customer_shortname',$curname);
				set_session_var('ecom_login_customer_fbid',$insert_array['customer_fbid']);
				
				$update_sql =	"UPDATE
												customers
										SET
												customer_last_login_date = NOW() 
										WHERE
												customer_id = ".$customer_id." 
										AND
												sites_site_id = $ecom_siteid  
										LIMIT	1";
				$db->query($update_sql);
				//header("Location:".SITE_URL."/login_home.html");
				echo $action_url;
				exit;
			}
		}
		else
		{
			echo "<script type='text/javascript'>window.location.href = '".SITE_URL."';</script>";
			exit;
		}
	}				
?>
