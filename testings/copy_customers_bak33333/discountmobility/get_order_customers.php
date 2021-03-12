<?php
	include_once('header.php');	
	$starthere = $_REQUEST['start'];
?>
	<table width='100%' cellpadding='0' cellspacing='0' border='0'>
	<tr>
	<td align='left'>#</td>
	<td align='left'>Customer Name</td>
	<td align='left'>Email id</td>
	<td align='left'>Status</td>
	</tr>
	<?php
	// get the details of customers in orders who are not yet registered with the website
	$sql_ord = "SELECT order_id, customers_customer_id, order_custtitle, order_custfname, order_custmname,
					order_custsurname,order_custcompany,order_buildingnumber,order_street,order_city,
					order_state,order_country,order_custpostcode,order_custphone,order_custfax,order_custmobile,
					order_custemail 
				FROM 
					orders  
				WHERE 
					sites_site_id = $siteid  
					AND customers_customer_id = 0 
				ORDER BY order_id 
				LIMIT $starthere,500";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_cnt = $starthere;
		while ($row_ord = $db->fetch_array($ret_ord))
		{
			// Check whether the current customer exists in customer table in current website
			$sql_check = "SELECT customer_id FROM 
								customers 
							WHERE 
								sites_site_id = $siteid 
								AND customer_email_7503 = '".$row_ord['order_custemail']."' 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0) // case if email id does not exists. Then insert should be done to customers table
			{
				$insert_array = array();
				$insert_array['customer_accounttype'] 			= 'personal';
				$insert_array['customer_activated'] 			= 1;
				$insert_array['customer_title']     			= add_slash(stripslashes($row_ord['order_custtitle']));
				$insert_array['customer_fname']					= add_slash(stripslashes($row_ord['order_custfname']));
				$insert_array['customer_mname']					= add_slash(stripslashes($row_ord['order_custmname']));
				$insert_array['customer_surname']				= add_slash(stripslashes($row_ord['order_custsurname']));
				$insert_array['customer_buildingname']			= add_slash(stripslashes($row_ord['order_buildingnumber']));
				$insert_array['customer_streetname']			= add_slash(stripslashes($row_ord['order_street']));
				$insert_array['customer_towncity']				= add_slash(stripslashes($row_ord['order_city']));
				
				$insert_array['customer_statecounty']			= add_slash(stripslashes($row_ord['order_state']));
				$insert_array['customer_phone']					= add_slash(stripslashes($row_ord['order_custphone']));
				$insert_array['customer_fax']					= add_slash(stripslashes($row_ord['order_custfax']));
				$insert_array['customer_mobile']				= add_slash(stripslashes($row_ord['order_custmobile']));
				$insert_array['customer_postcode']				= add_slash(stripslashes($row_ord['order_custpostcode']));
				$insert_array['country_id']						= 0;
				
				$insert_array['customer_email_7503']			= add_slash($row_ord['order_custemail']);
				$insert_array['customer_pwd_9501']				= md5('bshop999');
												
				$insert_array['customer_referred_by']			= 0;
				$insert_array['customer_addedon']				= 'curdate()';
				
				
				$insert_array['customer_hide']					= 0;
				
				$insert_array['customer_compname']				= add_slash(stripslashes($row_ord['order_custcompany']));
				$insert_array['customer_comptype']				= 0;
								
				$insert_array['sites_site_id']					= $siteid;
				//$db->insert_from_array($insert_array, 'customers');
				//$insert_id = $db->insert_id();
				$status = 'Created New Customer - '.$insert_id;
			}
			else
				$status = 'Already Exists';
			?>
				<tr>
					<td align='left'><?php echo $row_cnt;$row_cnt++;?>.</td>
					<td align='left'><?php echo trim($row_ord['order_custtitle']).''.trim($row_ord['order_custfname']).''.trim($row_ord['order_custsurname']);?></td>
					<td align='left'><?php echo trim($row_ord['order_custemail'])?></td>
					<td align='left'><?php echo $status?></td>
				</tr>
			<?php
			
		}
	}
	else
	{
		?>
			<tr>
				<td align='center' colspan="4">-- No Products Found --</td>
			</tr>
		<?php
	}
	
	
	
	function add_slash($varial,$strip_html=true)
	{
		if ($strip_html)
			$varial = strip_tags($varial);
		#checking whether magic quotes are on
		if (!get_magic_quotes_gpc()){
			$ret=addslashes($varial);
		} else {
			$ret=$varial;
		}
		return $ret;
	}
?>

			<tr>
				<td align='center' colspan="4"><br><br><br>-- Completed Successfully -- <?php echo $starthere.' to '.($starthere+500).' Next '.($starthere+500+1);?></td>
			</tr>
</table>
