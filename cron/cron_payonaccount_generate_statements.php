<?php
	/*#################################################################
	# Script Name 	        : cron_payonaccount_generate_statements.php
	# Description 		: Page to Generate statements for pay on account customers
	# Coded by 		: Sny
	# Created on		: 18-Oct-2008
	# Modified by		: Sny
	# Modified On		: 05-Dec-2010
	#################################################################*/
	
        //define('ORG_DOCROOT','/var/www/html/bshop4'); // Local path
        define('ORG_DOCROOT','/var/www/vhosts/bshop4.co.uk/httpdocs'); // Live path
        require_once(ORG_DOCROOT."/config_db.php");
        require_once(ORG_DOCROOT.'/functions/functions.php');
        require_once(ORG_DOCROOT.'/includes/price_display.php');
        
        // Get all the sites In Bshop4
	$sql_site = "SELECT site_id,site_domain,site_email 
                        FROM 
                            sites where site_id=44";// at present only for bshop4
	$ret_site = $db->query($sql_site);
	while($row_site = $db->fetch_array($ret_site)) 
	{
		$siteid 	= $ecom_siteid = $row_site['site_id'];
                //Image path for current website
                $image_path     = ORG_DOCROOT . '/images/' . $row_site['site_domain'];
                
		// Calling the function to get the details of default currency
		$default_Currency_arr	= get_default_currency();
		// Assigning the current currency to the variable
		$sitesel_curr	= $default_Currency_arr['currency_id'];
                // If sitesel_curr have no value then set it as the default currency
                if (!$sitesel_curr)
                {
                        $sitesel_curr           = $default_Currency_arr['currency_id'];// setting the default currency value
                        //clear_all_cache();
                }
                // Get details of current currency
                $current_currency_details = get_current_currency_details();
		$fromemail	= stripslashes($row_site['site_email']);
		// Get the cur day of the month
		$day = date('d');
		$sql_cust = "SELECT customer_id,customer_payonaccount_status,customer_payonaccount_billcycle_day,customer_payonaccount_usedlimit,
                                    customer_title,customer_fname,customer_mname,customer_surname,customer_email_7503,
                                    customer_payonaccount_billcycle_month_duration,customer_payonaccount_laststatementdate  
                                FROM 
                                    customers 
                                WHERE 
                                    sites_site_id = $siteid 
                                    AND customer_payonaccount_status IN ('ACTIVE','INACTIVE') 
                                    AND customer_payonaccount_billcycle_day=$day ";
		$ret_cust = $db->query($sql_cust);
		if($db->num_rows($ret_cust))
		{
			  // Get the content of email template
			 $sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
                                            FROM
                                                general_settings_site_letter_templates
                                            WHERE
                                                sites_site_id = $siteid
                                                AND lettertemplate_letter_type = 'PAY_ON_ACCOUNT_STATEMENT'
                                            LIMIT
                                                    1";
			$ret_template = $db->query($sql_template);
			if ($db->num_rows($ret_template))
			{
				$row_template 		= $db->fetch_array($ret_template);
				$email_from		= stripslashes($row_template['lettertemplate_from']);
				$email_subject		= stripslashes($row_template['lettertemplate_subject']);
				$email_content		= stripslashes($row_template['lettertemplate_contents']);
				$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
			}	
			$search_arr	= array (
                                                    '[date]',
                                                    '[name]',
                                                    '[statement_period]',
                                                    '[statement]',
                                                    '[domain]'
                                                );
			$date 		= date('d-M-Y');
			$domain		= stripslashes($row_site['site_domain']);
			while ($row_cust = $db->fetch_array($ret_cust))
			{
                            $proceed_with_statement = false;
                            if($row_cust['customer_payonaccount_billcycle_month_duration']==1)// case if interval is every month
                            {
                                $proceed_with_statement = true;
                            }
                            elseif($row_cust['customer_payonaccount_billcycle_month_duration']>1)// case if interval is not every month
                            {
                                $last_statement_date    = explode('-',$row_cust['customer_payonaccount_laststatementdate']); // split the last statement date to get the numeric value of monthy. when payonaccount is activated for the first time the date of activation will be set as the last statement date by default.
                                $last_month             = $last_statement_date[1];
                                // get the numeric value of current month
                                $cur_month = date('n');
                                if($cur_month<$last_month)
                                {
                                    $cur_month += 12;   // Adding 12 to the current month numerical value to make it value > 12 so as to find the month difference
                                }
                                $month_diff = $cur_month - $last_month;
                                if($month_diff==$row_cust['customer_payonaccount_billcycle_month_duration'])
                                {
                                    $proceed_with_statement = true;
                                }
                            } 
                                if($proceed_with_statement==true)
                                {
                                    $cust_id				        = $row_cust['customer_id'];
                                    $cust_name				        = stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_mname']).' '.stripslashes($row_cust['customer_surname']);
                                    $cust_email				        = stripslashes($row_cust['customer_email_7503']);
                                    $insert_array				= array();
                                    $insert_array['pay_date']		        = 'now()';
                                    $insert_array['sites_site_id']		= $siteid;
                                    $insert_array['customers_customer_id']	= $cust_id;
                                    $insert_array['pay_amount']		        = $row_cust['customer_payonaccount_usedlimit'];
                                    $insert_array['pay_transaction_type']	= 'O';
                                    $insert_array['pay_details']		= 'Opening Balance';
                                    $db->insert_from_array($insert_array,'order_payonaccount_details');
                                    $insert_id 				        = $db->insert_id();
                                    //Updating the customer table with the last statememnt date
                                    $update_cust = "UPDATE 
                                                        customers 
                                                    SET 
                                                        customer_payonaccount_laststatementdate = now(),
                                                        customer_payonaccount_atleast_one_statement=1 
                                                    WHERE 
                                                        customer_id = ".$row_cust['customer_id']." 
                                                        AND sites_site_id = $siteid 
                                                    LIMIT 
                                                        1";
                                    $db->query($update_cust);
                                    
                                    if ($row_cust['customer_payonaccount_usedlimit']!=0) // send the mail only if balance exists (either + or -)
                                    {
                                            // Getting the previous outstanding details from orders_payonaccount_details 
                                            $sql_payonaccount   = "SELECT pay_id, pay_date,pay_amount 
                                                                    FROM 
                                                                        order_payonaccount_details  
                                                                    WHERE 
                                                                        pay_id < ".$insert_id." 
                                                                        AND customers_customer_id = ".$cust_id."  
                                                                        AND pay_transaction_type ='O' 
                                                                    ORDER BY 
                                                                        pay_id DESC 
                                                                    LIMIT 
                                                                        1";
                                            $ret_payonaccount = $db->query($sql_payonaccount);
                                            if ($db->num_rows($ret_payonaccount))
                                            {
                                                    $row_payonaccount 	= $db->fetch_array($ret_payonaccount);
                                                    $prev_balance	= $row_payonaccount['pay_amount'];
                                                    $prev_id	        = $row_payonaccount['pay_id'];
                                                    $prev_date		= $row_payonaccount['pay_date'];
                                            }
                                            else
                                            {
                                                    $prev_balance	= 0;										
                                                    $prev_id		= 0;
                                                    $prev_date		= '0000-00-00';
                                            }	
                                            if ($prev_id)
                                            {
                                                $sql_payondetails   = "SELECT pay_id,pay_date,customers_customer_id,pay_amount,
                                                                        pay_transaction_type,pay_details,orders_order_id,pay_additional_details  
                                                                        FROM 
                                                                            order_payonaccount_details 
                                                                        WHERE 
                                                                            customers_customer_id = $cust_id 
                                                                            AND pay_id>=$prev_id and pay_id<$insert_id  
                                                                        ORDER BY 
                                                                            pay_date ASC";
                                            }
                                            else
                                            {
                                                $sql_payondetails   = "SELECT pay_id,pay_date,customers_customer_id,pay_amount,pay_transaction_type,
                                                                            pay_details,orders_order_id,pay_additional_details  
                                                                        FROM 
                                                                            order_payonaccount_details 
                                                                        WHERE 
                                                                            customers_customer_id = $cust_id 
                                                                            AND pay_transaction_type !='O' 
                                                                        ORDER BY 
                                                                            pay_date ASC";
                                            }
                                            $ret_payondetails = $db->query($sql_payondetails);
                                            if ($db->num_rows($ret_payondetails))
                                            {		
                                                $srno 		= 0;
                                                if($prev_date!='0000-00-00')
                                                {
                                                    $statement_period = 'From '.dateFormat($prev_date,'date').' To '.date('d-M-Y');
                                                }
                                                else
                                                    $statement_period = 'Till '. date('d-M-Y');
                                                $table 		= '<table width="100%" border="0" cellspacing="1" cellpadding="1">';	
                                                $table 		.= '<tr>';
                                                $table 		.= '<td align="left" width="5%">#</td>';
                                                $table 		.= '<td align="left" width="15%">Date</td>';
                                                $table 		.= '<td align="left" width="40%">Details</td>';
                                                $table 		.= '<td align="right" width="20%">Amount</td>';
                                                $table 		.= '<td align="left" width="20%">&nbsp;</td>';
                                                $table 		.= '</tr>';
                                                while ($row_payondetails = $db->fetch_array($ret_payondetails))
                                                {
                                                    $srno++;
                                                    $pay_type 	= ($row_payondetails['pay_transaction_type']=='C')?'<strong>(Cr.)</strong>':'';
                                                    $table 		.= '<tr>';
                                                    $table 		.= '<td align="left">'.$srno.'</td>';
                                                    $table 		.= '<td align="left">'.dateFormat($row_payondetails['pay_date'],'date').'</td>';
                                                    $table 		.= '<td align="left">'.stripslashes($row_payondetails['pay_details']).'</td>';
                                                    $table 		.= '<td align="right">'.print_price($row_payondetails['pay_amount']).'</td>';
                                                    $table 		.= '<td align="left">'.$pay_type.'</td>';
                                                    $table 		.= '</tr>';
                                                }
                                                $table 		.= '<tr>';
                                                $table 		.= '<td align="right" colspan="3">&nbsp;</td>';
                                                $table 		.= '<td align="right">---------------------------</td>';
                                                $table 		.= '<td align="left">&nbsp;</td>';
                                                $table 		.= '</tr>';
                                                $table 		.= '<tr>';
                                                $table 		.= '<td align="right" colspan="3">Closing Balance</td>';
                                                $table 		.= '<td align="right">'.print_price($row_cust['customer_payonaccount_usedlimit']).'</td>';
                                                $table 		.= '<td align="left">&nbsp;</td>';
                                                $table 		.= '</tr>';
                                                $table 		.= '<tr>';
                                                $table 		.= '<td align="right" colspan="3">&nbsp;</td>';
                                                $table 		.= '<td align="right">===========</td>';
                                                $table 		.= '<td align="left">&nbsp;</td>';
                                                $table 		.= '</tr>';
                                                $table 		.= '</table>';
                                                    
                                                $replace_arr	= array (
                                                                            $date,
                                                                            $cust_name,
                                                                            $statement_period,
                                                                            $table,
                                                                            $domain
                                                                        );
                                                // Do the replacement in email template content
                                                $email_content 	= str_replace($search_arr,$replace_arr,$email_content);
                                                // Building email headers to be used with the mail
                                                $email_headers 	= "From: $domain <$fromemail>\n";
                                                $email_headers 	.= "MIME-Version: 1.0\n";
                                                $email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
                                                if($email_disabled==0)// check whether mail sending is disabled
                                                {
                                                    mail($cust_email, $email_subject,$email_content, $email_headers); 
                                                }
                                            }	
                                    }	
                            }
                    }
		}
	} // Site Row Ends Here	
?>
