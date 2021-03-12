<?php
	include_once('header.php');
	
	// Get the list of customers existing in the source website
	$sql_custsrc = "SELECT * FROM customers WHERE sites_site_id = $src_siteid";
	$ret_custsrc = $db->query($sql_custsrc);
	
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
	<td align="left">#</td>
	<td align="left">Name</td>
	<td align="left">Email Id</td>
	<td align="left">Status</td>
	</tr>	
	<?php
	$i = 1;
	if($db->num_rows($ret_custsrc))
	{
		while ($row_custsrc = $db->fetch_array($ret_custsrc))
		{
			$err_msg 				= '';
			$name				= stripslashes($row_custsrc['customer_title']).stripslashes($row_custsrc['customer_fname']).' '.stripslashes($row_custsrc['customer_surname']);
			$pid				= stripslashes($row_custsrc['customer_email_7503']);
			
			
			// Check whether the email id already exists in the website
			$sql_prodloc = "SELECT customer_id FROM customers WHERE sites_site_id = $des_siteid AND customer_email_7503='".$row_custsrc['customer_email_7503']."' LIMIT 1";
			$ret_prodloc = $db->query($sql_prodloc);
			if($db->num_rows($ret_prodloc))
			{
				$status = 'Already Exists';	
			}
			else
			{
				$sql_insert = "INSERT INTO customers SET 
								sites_site_id=".$des_siteid.",
								customers_corporation_department_department_id='".addslashes(stripcslashes($row_custsrc['customers_corporation_department_department_id']))."',
								customer_activated='".addslashes(stripcslashes($row_custsrc['customer_activated']))."',
								customer_accounttype='".addslashes(stripcslashes($row_custsrc['customer_accounttype']))."',
								customer_title='".addslashes(stripcslashes($row_custsrc['customer_title']))."',
								customer_fname='".addslashes(stripcslashes($row_custsrc['customer_fname']))."',
								customer_mname='".addslashes(stripcslashes($row_custsrc['customer_mname']))."',
								customer_surname='".addslashes(stripcslashes($row_custsrc['customer_surname']))."',
								customer_position='".addslashes(stripcslashes($row_custsrc['customer_position']))."',
								customer_compname='".addslashes(stripcslashes($row_custsrc['customer_compname']))."',
								customer_comptype='".addslashes(stripcslashes($row_custsrc['customer_comptype']))."',
								customer_compregno='".addslashes(stripcslashes($row_custsrc['customer_compregno']))."',
								customer_compvatregno='".addslashes(stripcslashes($row_custsrc['customer_compvatregno']))."',
								customer_buildingname='".addslashes(stripcslashes($row_custsrc['customer_buildingname']))."',
								customer_streetname='".addslashes(stripcslashes($row_custsrc['customer_streetname']))."',
								customer_towncity='".addslashes(stripcslashes($row_custsrc['customer_towncity']))."',
								customer_statecounty='".addslashes(stripcslashes($row_custsrc['customer_statecounty']))."',
								customer_phone='".addslashes(stripcslashes($row_custsrc['customer_phone']))."',
								customer_fax='".addslashes(stripcslashes($row_custsrc['customer_fax']))."',
								customer_mobile='".addslashes(stripcslashes($row_custsrc['customer_mobile']))."',
								customer_postcode='".addslashes(stripcslashes($row_custsrc['customer_postcode']))."',
								country_id='19985',
								customer_email_7503='".addslashes(stripcslashes($row_custsrc['customer_email_7503']))."',
								customer_pwd_9501='".addslashes(stripcslashes($row_custsrc['customer_pwd_9501']))."',
								customer_bonus='".addslashes(stripcslashes($row_custsrc['customer_bonus']))."',
								customer_discount='".addslashes(stripcslashes($row_custsrc['customer_discount']))."',
								customer_allow_product_discount='".addslashes(stripcslashes($row_custsrc['customer_allow_product_discount']))."',
								customer_use_bonus_points='".addslashes(stripcslashes($row_custsrc['customer_use_bonus_points']))."',
								customer_referred_by='".addslashes(stripcslashes($row_custsrc['customer_referred_by']))."',
								customer_addedon='".addslashes(stripcslashes($row_custsrc['customer_addedon']))."',
								customer_anaffiliate='".addslashes(stripcslashes($row_custsrc['customer_anaffiliate']))."',
								customer_approved_affiliate='".addslashes(stripcslashes($row_custsrc['customer_approved_affiliate']))."',
								customer_approved_affiliate_on='".addslashes(stripcslashes($row_custsrc['customer_approved_affiliate_on']))."',
								customer_affiliate_commission='".addslashes(stripcslashes($row_custsrc['customer_affiliate_commission']))."',
								customer_affiliate_taxid='".addslashes(stripcslashes($row_custsrc['customer_affiliate_taxid']))."',
								shop_id='".addslashes(stripcslashes($row_custsrc['shop_id']))."',
								customer_hide='".addslashes(stripcslashes($row_custsrc['customer_hide']))."',
								customer_last_login_date='".addslashes(stripcslashes($row_custsrc['customer_last_login_date']))."',
								customer_prod_disc_newsletter_receive='".addslashes(stripcslashes($row_custsrc['customer_prod_disc_newsletter_receive']))."',
								customer_payonaccount_status='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_status']))."',
								customer_payonaccount_maxlimit='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_maxlimit']))."',
								customer_payonaccount_usedlimit='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_usedlimit']))."',
								customer_payonaccount_billcycle_day='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_billcycle_day']))."',
								customer_payonaccount_billcycle_month_duration='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_billcycle_month_duration']))."',
								customer_payonaccount_rejectreason='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_rejectreason']))."',
								customer_payonaccount_laststatementdate='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_laststatementdate']))."',
								customer_payonaccount_atleast_one_statement='".addslashes(stripcslashes($row_custsrc['customer_payonaccount_atleast_one_statement']))."',
								customer_in_mailing_list='".addslashes(stripcslashes($row_custsrc['customer_in_mailing_list']))."',
								customer_fbid='".addslashes(stripcslashes($row_custsrc['customer_fbid']))."',
								customer_activated_on='".addslashes(stripcslashes($row_custsrc['customer_activated_on']))."',
								metrodent_account_number='".addslashes(stripcslashes($row_custsrc['metrodent_account_number']))."',
								req_metrodent_account='".addslashes(stripcslashes($row_custsrc['req_metrodent_account']))."'";
								$db->query($sql_insert);
						$status = 'Done';	
			}	
			?>
			<tr>
			<td align="left"><?php echo $i?></td>
			<td align="left"><?php echo $name?></td>
			<td align="left"><?php echo $pid?></td>
			<td align="left"><?php echo $status?></td>
			</tr>		
			<?php
		
			$i++;
		}
	}
	$db->db_close();
	?>
    <tr>
		<td colspan="7" align="center" style="color:#006600"><strong>----- Customers Copied Successfully ------</strong></td>
	</tr>
	</table>
