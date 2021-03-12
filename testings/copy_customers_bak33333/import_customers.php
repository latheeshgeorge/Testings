<?php
	include 'header.php';
	
	$countrycheck_arr = array(
					'GB'=>'United Kingdom',
					'US'=>'United States',
					'NO'=>'Norway',
					'IT'=>'Italy',
					'IE'=>'Ireland',
					'NL'=>'Netherlands',
					'ES'=>'Spain'
				);
	$import_file 		= 'csv/activ_ecom_users_tlc.csv';	// Import filename
	$fp = fopen($import_file,'r');
	if (!$fp)
	{
		echo "Cannot open the file";
		exit;
	}
	$i=0;
	// read the content of csv file
	?>
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
	<tr>
				<td><strong>#</strong></td>
				<td><strong>Name</strong></td>
				<td><strong>Email Id</strong></td>
				<td><strong>Status</strong></td>
				</tr>
	<?php
	  
	$atleast_one_err = 0;
	$tot_cnt = 0;
	while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
	{	
		
		if($i!=0 ) 
		{
			$err_msg 	= '';
			$title		= trim($data[1]).'.';
			$fname 		= trim($data[2]);
			$surname 	= trim($data[3]);
			$building	= trim($data[4]);
			$street		= trim($data[5]);
			$city		= trim($data[6]);
			$state		= trim($data[7]);
			$postcode	= trim($data[8]);
			$country	= trim($data[9]);
			$telephone	= trim($data[10]);
			$email		= trim($data[11]);
			$pass		= '';
			$countryname	= $countrycheck_arr[$country];
			$country_id	= 0;
			// Check whether current country exists in database
			 $sql_country_check = "SELECT country_id 
						FROM 
							general_settings_site_country 
						WHERE 
							sites_site_id = $siteid 
							AND country_name ='".$countryname."' 
						LIMIT 
							1";
			$ret_country_check = $db->query($sql_country_check);
			if ($db->num_rows($ret_country_check))
			{
				$row_country_check = $db->fetch_array($ret_country_check);
				$country_id = $row_country_check['country_id'];
			}
                        
			// Check whether there already exists any customer with same email id
			 $sql_catcheck = "SELECT customer_id   
						FROM 
							customers   
						WHERE 
							customer_email_7503 = '".addslashes($email)."' 
							AND sites_site_id = $siteid 
						LIMIT 
							1";
			$ret_catcheck = $db->query($sql_catcheck);
			
			if ($db->num_rows($ret_catcheck))
			{
				$err_msg = 'Customer exists in website';
			} 
			else
			{
				$pass_arr	= explode('@',$email);
				$insert_array 				= array();
                                $insert_array['sites_site_id']		= $siteid;
				$insert_array['customer_accounttype']	= 'personal';
				$insert_array['customer_title']		= addslashes($title);
                                $insert_array['customer_fname']		= addslashes($fname);
                                $insert_array['customer_surname']	= addslashes($surname);
                                $insert_array['customer_buildingname']	= addslashes($building);
				$insert_array['customer_streetname']	= addslashes($street);
				$insert_array['customer_towncity']	= addslashes($city);
				$insert_array['customer_statecounty']	= addslashes($state);
				$insert_array['customer_phone']		= addslashes($telephone);
				$insert_array['country_id']		= addslashes($country_id);
				$insert_array['customer_postcode']	= addslashes($postcode); 
				$insert_array['customer_email_7503']	= addslashes($email);
				$insert_array['customer_pwd_9501']	= addslashes(md5($pass_arr[0]));
				$insert_array['customer_addedon']	= 'curdate()';
				$insert_array['customer_last_login_date']= 'now()';
				$insert_array['customer_activated']	= 1;
				$db->insert_from_array($insert_array,'customers');
				$tot_cnt++;
				$err_msg = 'Done';
			}
			//if($err_msg)
			{
				$atleast_one_err = 1;
			?>
				<tr>
				<td><strong><?php echo ($i)?></strong></td>
				<td><strong><?php echo $title.$name?></strong></td>
				<td><strong><?php echo $email?></strong></td>
				<td style="color:#FF0000"><strong><?php echo $err_msg?></strong></td>
				</tr>	
			<?php
			}
		}
		$i++;
	}
	fclose($fp);
	?>
	<tr>
		<td colspan="4" align="center" style="color:#006600"><strong>----- Customers Imported Successfully ------</strong><br />
		Total Imported: <?php echo $tot_cnt?>
		</td>
	</tr>
	</table>
