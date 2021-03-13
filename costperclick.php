<?
	/*#######################################################################
	# Script Name 	: costperclick.php
	# Description 		: This is the common page to which the sponsored cost per click url will be taken from various sites
	# Coded by 		: SNY
	# Created on		: 30-Oct-2008
	# Modified by		: 
	# Modified On		: 
	#######################################################################*/
	require("functions/functions.php");
	require("includes/session.php");
	require("config.php");
	
	// Get all the components to be shown in the site
	// ################################################################
	$inlineSiteComponents =get_inlineSiteComponents();
	
	// ################################################################
	// Get all the components which are active in console area
	// ################################################################
	$consoleSiteComponents = get_inlineConsoleComponents();
	// Get all the caption sections to an array
	$fraud_detection 	= false;
	$url_id 					=$_REQUEST['url_id'];
	
	$module_name = 'mod_costperclickurls';
	// Check whether cost per click module is currently active for current site
	if(is_Feature_exists($module_name))
	{
		//Checking whether the specified url_id and referrer is valid or not
		$sql_cpc = "SELECT url_id, url_mypage, costperclick_adverplaced_on_advertplace_id, url_setting_noofclicks, url_setting_days, url_setting_rateperclick,now()  as curdatetime 
									FROM 
										costperclick_adverturl a,costperclick_advertplacedon b
									WHERE 
										url_id = $url_id 
										AND a.costperclick_adverplaced_on_advertplace_id = b.advertplace_id 
										AND INSTR('".$_SERVER['HTTP_REFERER']."', advertplace_name) > 0 
										AND a.sites_site_id=$ecom_siteid 
										AND url_hidden=0 
									LIMIT 
										1";
		$res_cpc = $db->query($sql_cpc);
		if ($db->num_rows($res_cpc)==0) // case if url id is not valid
		{
			header("Location:invalid_input.html");
			exit;
		}
		else // case if url is valid
		{
			$row_cpc 		= $db->fetch_array($res_cpc);
			$send_email	= '';
			// Check whether any email id set
			$sql_settings = "SELECT send_email 
												FROM 
													costperclick_settings 
												WHERE 
													sites_site_id=$ecom_siteid 
												LIMIT 
													1";
			$res_settings = $db->query($sql_settings);
			if($db->num_rows($res_settings))
			{
				$row_settings 	= $db->fetch_array($res_settings);
				$send_email		= stripslashes($row_settings['send_email']);
			}
			$cur_time 				= time();
			$check_time			= $cur_time - ($row_cpc['url_setting_days']*24*60*60);
			$cur_month			= date('n');
			$cur_year				= date('Y');
			$cur_to_url				= $row_cpc['url_mypage'];
			$cur_place_id			= $row_cpc['costperclick_adverplaced_on_advertplace_id'];
			// Get the name of place from costperclick_advertplacedon
			$sql_place = "SELECT advertplace_name 
									FROM 
										costperclick_advertplacedon 
									WHERE 
										advertplace_id = $cur_place_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
			$ret_place = $db->query($sql_place);
			if ($db->num_rows($ret_place))
			{
				$row_place = $db->fetch_array($ret_place);
				$cur_from_url = stripslashes($row_place['advertplace_name']);
			}
			// Get the number of clicks registered with in the given period and now against current url from current ip 
			$sql_time = "SELECT time_id 
									FROM 
										costperclick_time 
									WHERE 
										costperclick_adverturl_url_id = $url_id 
										AND time_ipaddress='".$_SERVER['REMOTE_ADDR']."' 
										AND time_time>=$check_time 
										AND time_time<=$cur_time ";
									//LIMIT 
										//".$row_cpc['url_setting_noofclicks'];
			$ret_time = $db->query($sql_time);
			if($db->num_rows($ret_time)>=$row_cpc['url_setting_noofclicks']) // check whether number of clicks with the specified range of dates is >= to that allowed number of clicks
			{
				$fraud_detection 	= true; // case if current click is identified as fraud click
				$url_click_field	= 'url_total_fraud_clicks';	
				$month_click_field	= 'month_total_fraud_clicks';
			}
			else
			{
				$url_click_field	= 'url_total_clicks';	
				$month_click_field	= 'month_total_clicks';
			}
			
			// Updating the click field in costperclick_adverturl 
			$sql_update = "UPDATE 
										costperclick_adverturl 
									SET 
										$url_click_field = $url_click_field + 1 
									WHERE 
										url_id = $url_id 
									LIMIT 
										1";
			$db->query($sql_update);
			
			// Check whether there exists any entry for current url_id in costperclick_month table
			$sql_chk = "SELECT month_id 
								FROM 
									costperclick_month 
								WHERE 
									costperclick_adverturl_url_id = $url_id 
									AND sites_site_id = $ecom_siteid 
									AND month_mon = $cur_month 
									AND month_year  = $cur_year 
								LIMIT 
									1";
			$ret_chk = $db->query($sql_chk);
			if($db->num_rows($ret_chk))  // case if entry is already there in costperclick_month
			{
				// case if record already exists in costperclick_month 
				$row_chk = $db->fetch_array($ret_chk);
				// Update the total click count 
				$update_sql = "UPDATE 
											costperclick_month 
										SET 
											$month_click_field = $month_click_field + 1 
										WHERE 
											month_id = ".$row_chk['month_id']." 
										LIMIT 
											1";
				$db->query($update_sql);
				$month_id 	= $row_chk['month_id'];
			}	
			else	 // case if entry is not there in costperclick_month
			{
				$insert_array												= array();
				$insert_array['sites_site_id']							= $ecom_siteid;
				$insert_array['costperclick_adverturl_url_id']	= $url_id;
				$insert_array[$month_click_field]					= 1;
				$insert_array['month_mon']							= $cur_month;
				$insert_array['month_year']							= $cur_year;
				$db->insert_from_array($insert_array,'costperclick_month');
				$month_id 													= $db->insert_id();
			}
			
			// Make an entry in the costperclick_time table
			$insert_array													= array();
			$insert_array['sites_site_id']								= $ecom_siteid;
			$insert_array['costperclick_month_month_id']		= $month_id;
			$insert_array['costperclick_adverturl_url_id']		= $url_id;
			$insert_array['time_time']									= $cur_time;
			$insert_array['time_ipaddress']							= $_SERVER['REMOTE_ADDR'];
			$insert_array['time_isfraud']								= ($fraud_detection)?1:0;
			$db->insert_from_array($insert_array,'costperclick_time');
			
			if($fraud_detection and $send_email!='') // if fraud and also email id is set to send fraud emails
			{
				$headers 			= "From: $ecom_hostname<$ecom_email>\n";
				$headers 			.= "MIME-Version: 1.0\n";
				$headers 			.= "Content-type: text/html; charset=iso-8859-1\n";
				$subject 			= 'Cost Per Click @ $ecom_hostname';
				$fraud_content 	= "Dear Customer,<br><br>BSHOP v4.0 Defender has identified a fraud click @ your website $ecom_hostname<br><br>Following are the click details:<br><br>Click came from: ".$cur_frm_url."<br>Click came to: $cur_to_url <br> IP Address: ".$_SERVER['REMOTE_ADDR']."<br>Date/Time: ".date("d-M-Y g:i a",$cur_time)."<br><br>This is an automated email. Please don't reply to this message.";
				//mail($send_email,$subject,$fraud_content,$headers);
			}

			// If current click is not a fraud, then set the url id and month id in session otherwise clear the session			
			if($fraud_detection==false)
			{
				// Set the session for pay on click
				set_session_var('COST_PER_CLICK',$url_id.'_'.$month_id);
				// Setting the session variable to indicate it is not a genuine click
				set_session_var('COST_PER_CLICK_FRAUD',0);
			}	
			else
			{
				// clearing the session 
				set_session_var('COST_PER_CLICK','');
				// Setting the session variable to indicate it is a fraud click
				set_session_var('COST_PER_CLICK_FRAUD',1);
			}	
			// Redirecting the url to the original page 
			header("Location:".$cur_to_url);
			exit;
		}	
	}
	else
	{
		header("Location:invalid_input.html");
		exit;
	}	
?>

