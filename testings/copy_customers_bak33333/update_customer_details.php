<?php
// ###############################################################################
// pick customer with id > this one
// ###############################################################################
//$last_existing_id = 4853; 


// ###############################################################################
// Source Site Details
// ###############################################################################
$src_siteid			= 176; //www.innhousebrewery.co.uk on old domain
$src_host 			= 'localhost';  
$src_user 			= 'newserver';
$src_pass 			= 'yuj*dff';
$src_db 			= 'bshop_3_1';


// ###############################################################################
// Destination Site Details
// ###############################################################################
$dest_siteid		= 99; // http://innhousebrewery.bshop4.co.uk on new domain
$dest_host			= 'localhost';
$dest_user			= 'bshop_am4';
$dest_pass			= 'b$H0pF@Ur';
$dest_db			= 'business1st_bshop4';

$tot_customers = 0;
// ###############################################################################
// Make connection to souce db
// ###############################################################################
$src_link = mysql_connect($src_host,$src_user,$src_pass);
if(!$src_link)
{
	echo "Cannot connect to $src_db";
	exit;
}

// ###############################################################################
// Make connection to destination db
// ###############################################################################
$dest_link = mysql_connect($dest_host,$dest_user,$dest_pass);
if(!$dest_link)
{
	echo "Cannot connect to $dest_db";
	exit;
}
// ###############################################################################
// Get the name of source site
// ###############################################################################
$sql_src = "SELECT domain 
					FROM 
						sites 
					WHERE 
						site_id = $src_siteid 
					LIMIT 
						1";
$ret_src = mysql_db_query($src_db,$sql_src,$src_link);

// ###############################################################################
// Get the details of destination site
// ###############################################################################
$sql_dest = "SELECT site_domain,themes_theme_id   
					FROM 
						sites 
					WHERE 
						site_id = $dest_siteid 
					LIMIT 
						1";
$ret_dest = mysql_db_query($dest_db,$sql_dest,$dest_link);

if (mysql_num_rows($ret_src))
{
	$row_src 		= mysql_fetch_array($ret_src);
	$src_domain		= $row_src['domain'];
}
else
{
		echo "Source site does not exists";
		exit;
}
if (mysql_num_rows($ret_dest))
{
	$row_dest 		= mysql_fetch_array($ret_dest);	
	$cur_theme_id 	= $row_dest['themes_theme_id'];
	$dest_domain	= $row_dest['site_domain'];
}	
else
{
		echo "Destination site does not exists";
		exit;
}

echo $tree = '<strong>Import Customers From</strong> '.$row_src['domain'].'<strong> to </strong>'.$row_dest['site_domain'];

$process_div = '<div id="import_processing_div" style="display:none; color:#FF0000" align="center">
				<br>
 				.... Processing Please wait ....
 				<br><br>
				</div>';
?>
<table width="100%" cellpadding="2" cellspacing="2" border="1">
<tr>
<td align="left">#</td>
<td align="left">Email Id</td>
<td align="left">Status</td>
</tr>
<?php
$cnts = 1;
	// #######################################################################################################
	/* Get the list of customers from source db */
	$sql_cust = "SELECT customer_id,corp_dept_id,activated,accountType,title,firstname,middlename,surname,postion,companyName,companyType,
						companyRegNo,companyVatRegNo,addressBuildingName,addressStreetName,addressTownCity,addressStateCounty,phone,
						fax,mobile,town_id,postal_code,country_id,email,pwd,site_id,block,bonus,discount,prod_discount,use_bonus_points,
						mailing_list_member,referredBy,date_added 
					FROM 
						customers 
					WHERE 
						site_id = $src_siteid ";
	$ret_cust = mysql_db_query($src_db,$sql_cust,$src_link) or die($sql_cust.'-'.mysql_error());
	$cust_cnt = $comptype_cnt = $country_cnt=$state_cnt = $newsgroupmap_cnt = 00;
	if (mysql_num_rows($ret_cust))
	{
		while ($row_cust = mysql_fetch_array($ret_cust))
		{
			$tot_customers++;
			// Check whether current customer exists in destination site
			$sql_dest_check = "SELECT customer_id,customer_pwd_9501  
							FROM 
								customers 
							WHERE 
								customer_email_7503 = '".addslashes(stripslashes($row_cust['email']))."' 
								AND sites_site_id=$dest_siteid 
							LIMIT 
								1";
			$ret_dest_check = 	mysql_db_query($dest_db,$sql_dest_check,$dest_link) or die($sql_dest_check.'-'.mysql_error());		
			if (mysql_num_rows($ret_dest_check)==0)
			{
					
			$acc_type	= ($row_cust['accountType']=='Corporate')?'business':'personal';
			if(substr($row_cust['title'],-1)!='.')
				$title = $row_cust['title'].'.';
			else 	
				$title = $row_cust['title'];
			$comp_type 			= $row_cust['companyType'];
			$allow_prod_disc 	= ($row_cust['prod_discount']=='1')?1:0;
			$use_bonus			= (!$row_cust['use_bonus_points'])?0:1;
			if($comp_type!='')
			{
				// Check whether current company type is there in general_settings_sites_customer_company_types table
				$sql_check = "SELECT comptype_id 
							FROM 
								general_settings_sites_customer_company_types 
							WHERE 
								sites_site_id = $dest_siteid 
								AND LOWER(comptype_name) ='".strtolower(addslashes($comp_type))."' 
							LIMIT 
								1";	
				$ret_check = mysql_db_query($dest_db,$sql_check,$dest_link) or die($sql_check.'-'.mysql_error());
				if (mysql_num_rows($ret_check)==0)
				{
					// Case if no company type exists matching the current on. So make a new entry in general_settings_sites_customer_company_types table
					$sql_insert = "INSERT INTO 
										general_settings_sites_customer_company_types 
									SET 
										sites_site_id=$dest_siteid,
										comptype_name='".addslashes(strip_tags(stripslashes($comp_type)))."',
										comptype_order=0,
										comptype_hide=0";
					mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
					$ctype = mysql_insert_id();
					$ret_arr['comptype_cnt']++;
				}
				else 
				{
					$row_check = mysql_fetch_array($ret_check);
					$ctype = $row_check['comptype_id'];
				}
			
			}
			else 	
				$ctype = 0;
			// Calling function to get the id for country and state
			$country_arr	= get_CountryState_ids($row_cust['country_id'],$row_cust['addressStateCounty']);
			$newcountry	 	+= $country_arr['country_cnt'];
			$newstate		+= $country_arr['state_cnt'];
			$sql_insert = "INSERT INTO customers 
								SET 
									sites_site_id=$dest_siteid,
									customers_corporation_department_department_id=".$row_cust['corp_dept_id'].",
									customer_activated=".$row_cust['activated'].",
									customer_accounttype='".$acc_type."',
									customer_title='".$title."',
									customer_fname='".addslashes(strip_tags(stripslashes($row_cust['firstname'])))."',
									customer_mname='".addslashes(strip_tags(stripslashes($row_cust['middlename'])))."',
									customer_surname='".addslashes(strip_tags(stripslashes($row_cust['surname'])))."',
									customer_position='".addslashes(strip_tags(stripslashes($row_cust['postion'])))."',
									customer_compname='".addslashes(strip_tags(stripslashes($row_cust['companyName'])))."',
									customer_comptype=".$ctype.",
									customer_compregno='".addslashes(strip_tags(stripslashes($row_cust['companyRegNo'])))."',
									customer_compvatregno='".addslashes(strip_tags(stripslashes($row_cust['companyVatRegNo'])))."',
									customer_buildingname='".addslashes(strip_tags(stripslashes($row_cust['addressBuildingName'])))."',
									customer_streetname='".addslashes(strip_tags(stripslashes($row_cust['addressStreetName'])))."',
									customer_towncity='".addslashes(strip_tags(stripslashes($row_cust['addressTownCity'])))."',
									customer_statecounty=".$country_arr['state_id'].",
									customer_phone='".addslashes(strip_tags(stripslashes($row_cust['phone'])))."',
									customer_fax='".addslashes(strip_tags(stripslashes($row_cust['fax'])))."',
									customer_mobile='".addslashes(strip_tags(stripslashes($row_cust['mobile'])))."',
									customer_postcode='".addslashes(strip_tags(stripslashes($row_cust['postal_code'])))."',
									country_id=".$country_arr['country_id'].",
									customer_email_7503='".addslashes(strip_tags(stripslashes($row_cust['email'])))."',
									customer_pwd_9501='".addslashes(strip_tags(stripslashes($row_cust['pwd'])))."',
									customer_bonus='".$row_cust['bonus']."',
									customer_discount='".$row_cust['discount']."',
									customer_allow_product_discount='".$allow_prod_disc."',
									customer_use_bonus_points='".$use_bonus."',
									customer_referred_by= 0,
									customer_addedon='".$row_cust['date_added']."',
									customer_anaffiliate=0,
									customer_approved_affiliate=0,
									customer_approved_affiliate_on='0000-00-00',
									customer_affiliate_commission=0,
									customer_affiliate_taxid='',
									shop_id=0,
									customer_hide=0";
			mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
			$row_cust['customer_id'] = mysql_insert_id();
			$ret_arr['cust_cnt']++;
			
			// Check whether current customer is in mailing list
			if($row_cust['mailing_list_member'])
			{
				// Making an entry to newsletter_customers
				$sql_insert = "INSERT INTO 
									newsletter_customers 
								SET 
									sites_site_id = $dest_siteid,
									news_title='".addslashes(strip_tags(stripslashes($title)))."',
									news_custname='".addslashes(strip_tags(stripslashes($row_cust['firstname']))).' '.addslashes(strip_tags(stripslashes($row_cust['middlename']))).' '.addslashes(strip_tags(stripslashes($row_cust['surname'])))."',
									news_custemail='".addslashes(strip_tags(stripslashes($row_cust['email'])))."',
									news_custphone='".addslashes(strip_tags(stripslashes($row_cust['phone'])))."',
									news_join_date='".$row_cust['date_added']."',
									customer_id=".$row_cust['customer_id'].",
									news_custhide=0";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.' '.mysql_error());
				$newscust_id = mysql_insert_id();
				$ret_arr['mailinglist_cnt']++;
			}	
			$status = 'Customer Added<br>b3: '.$row_cust['pwd'];
			$added_cnt++;
		}
		else
		{
		//		echo  '<br>Error occured'.$row_cust['customer_id'];
			$row_dest_check = mysql_fetch_array($ret_dest_check);
			if($row_cust['pwd']!=$row_dest_check['customer_pwd_9501'])
			{
				// Update the password field for this customer
				$update_sql = "UPDATE customers SET customer_pwd_9501 ='".$row_cust['pwd']."' WHERE customer_id = ".$row_dest_check['customer_id']." LIMIT 1";
				mysql_db_query($dest_db,$update_sql,$dest_link) or die($sql_insert.'-'.mysql_error());
				$status = '<span style="color:#FF0000">Password Updated</span><br>b3: '.$row_cust['pwd']."<br>b4: ".$row_dest_check['customer_pwd_9501'];
				$update_cnt++;
			}	
			else
				$status = 'Not Updated<br>b3: '.$row_cust['pwd']."<br>b4: ".$row_dest_check['customer_pwd_9501'];
		}
		
		
		?>
		<tr>
		<td align="left"><?php echo $cnts; $cnts++?></td>
		<td align="left"><?php echo $row_cust['email']?></td>
		<td align="left"><?php echo $status?></td>
		</tr>
		<?php
		}
	}
?>
<tr>
<td colspan="3" align="left">Added Cnt: <?php echo $added_cnt?>
<br /><br />
Updated Cnt: <?php echo $update_cnt?>
</td>
</tr>
</table>
<?php	
	
echo '<br> Completed';
	/* Function to get the details of country and state*/
	function get_CountryState_ids($country_id,$statename)
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;	
		$ret_arr	= array 
							(
								'country_cnt'=>0,
								'state_cnt'=>0,
								'country_id'=>0,
								'state_id'=>0
							);
		if($country_id)
		{
			// get the name of country for current customer from source db
			$sql_country = "SELECT country_name 
								FROM 
									country 
								WHERE 
									country_id=".$country_id." 
								LIMIT 
									1";
			$ret_country = mysql_db_query($src_db,$sql_country,$src_link) or die($sql_country.'-'.mysql_error());
			if (mysql_num_rows($ret_country))
			{
				$row_country 	= mysql_fetch_array($ret_country);
				$country_name	= $row_country['country_name'];
				// Check whether this country exists for destination site
				$sql_destcountry = "SELECT country_id 
										FROM 
											general_settings_site_country 
										WHERE 
											sites_site_id = $dest_siteid 
											AND LOWER(country_name)='".strtolower(addslashes($country_name))."' 
										LIMIT 
											1";
				$ret_destcountry = mysql_db_query($dest_db,$sql_destcountry,$dest_link) or die($sql_destcountry.'-'.mysql_error());
				if(mysql_num_rows($ret_destcountry)) // case if country exists in destination site
				{
					$row_destcountry 	= mysql_fetch_array($ret_destcountry);
					$country_id			= $row_destcountry['country_id']; 
				}
				else // case if country does not exists in destination site
				{
					$sql_insert = "INSERT INTO 
										general_settings_site_country 
									SET 
										sites_site_id=$dest_siteid,
										country_name='".addslashes(strip_tags(stripslashes($country_name)))."',
										country_hide=1,
										country_numeric_code=''";
					mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
					$country_id = mysql_insert_id();
					$ret_arr['country_cnt']++;
				}
				if(trim($statename)!='')// if state exists for customer in source site
				{
					$sql_check= "SELECT state_id 
									FROM 
										general_settings_site_state 
									WHERE 
										sites_site_id = $dest_siteid
										AND LOWER(state_name)='".strtolower(addslashes($statename))."' 
									LIMIT 
										1";
					$ret_check = mysql_db_query($dest_db,$sql_check,$dest_link) or die($sql_check.'-'.mysql_error());
					if(mysql_num_rows($ret_check)) // case if state already exists in destination site
					{
						$row_check 	= mysql_fetch_array($ret_check);
						$state_id	= $row_check['state_id'];
					}
					else // case if state does not exists in destination site
					{
						$sql_insert = "INSERT INTO 
											general_settings_site_state 
										SET 
											sites_site_id=$dest_siteid,
											general_settings_site_country_country_id=$country_id,
											state_name='".addslashes(strip_tags(stripslashes($statename)))."',
											state_hide=1";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.mysql_error());
						$state_id  = mysql_insert_id();
						$ret_arr['state_cnt']++;
					}
				}
			}
		}
		if ($country_id)
			$ret_arr['country_id'] 	= $country_id;
		if($state_id)
			$ret_arr['state_id']	= $state_id;
		return $ret_arr;
	}
?>