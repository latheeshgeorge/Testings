<?php
	function add_slash($varial,$strip_html=true)
	{
		if ($strip_html)
			$varial = strip_tags($varial);
		$ret 	= stripslashes($varial);
		$ret	= addslashes($varial);
		$ret 	= $varial;
		return $ret;
	}
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
				/*if(trim($statename)!='')// if state exists for customer in source site
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
				}*/
			}
		}
		if ($country_id)
			$ret_arr['country_id'] 	= $country_id;
		/*if($state_id)
			$ret_arr['state_id']	= $state_id;
		*/	
		return $ret_arr;
	}
	/* Function to import product categories */
	function import_Product_Categories()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		
		$ret_arr	= array 
							(
								'cat_cnt'=>0,
								'catmap_cnt'=>0
							);
		// get all the categories from src db
		$sql_src_cat = "SELECT category_id,cname,description,parent,site_id,paidDesc,cat_order,
								short_description,hide_category,prod_sort 
							FROM 
								mod_categories 
							WHERE 
								site_id = $src_siteid";
		$ret_src_cat = mysql_db_query($src_db,$sql_src_cat,$src_link); 
		$tot_categories = mysql_num_rows($ret_src_cat);
		if($tot_categories)
		{
			$sql_settings = "SELECT category_subcatlisttype,product_displaytype,product_displaywhere,
									product_showimage,product_showtitle,product_showshortdescription,product_showprice 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = ".$dest_siteid." 
									LIMIT 
										1";
			$ret_settings = mysql_db_query($dest_db,$sql_settings,$dest_link) or die(mysql_error());
			$row_settings = mysql_fetch_array($ret_settings);
			while ($row_src_cat = mysql_fetch_array($ret_src_cat))
			{
				$subcatlist 			= $row_settings['category_subcatlisttype'];
				$displaytype 		= $row_settings['product_displaytype'];
				$displaywhere 	= $row_settings['product_displaywhere'];
				$showimage 		= $row_settings['product_showimage'];
				$showtitle 			= $row_settings['product_showtitle'];
				$showshort 		= $row_settings['product_showshortdescription'];
				$showprice 		= $row_settings['product_showprice'];
				
				$cat_hide = ($row_src_cat['hide_category']=='Y')?1:0;
				$sql_insert = "INSERT INTO product_categories 
								SET 
									category_id=".$row_src_cat['category_id'].", 
									sites_site_id=".$dest_siteid.",
									parent_id=".$row_src_cat['parent'].", 
									category_name='".addslashes(strip_tags(stripslashes($row_src_cat['cname'])))."',
									category_shortdescription='".addslashes(strip_tags(stripslashes($row_src_cat['short_description'])))."',
									category_paid_description='".addslashes(stripslashes($row_src_cat['description']))."',
									category_paid_for_longdescription='".$row_src_cat['paidDesc']."',
									category_hide=".$cat_hide.",
									category_order=".$row_src_cat['cat_order'].", 
									category_subcatlisttype='".$subcatlist."',
									product_displaytype='".$displaytype."',
									product_displaywhere='".$displaywhere."',
									product_showimage=".$showimage.",
									product_showtitle=".$showtitle.",
									product_showshortdescription=".$showshort.",
									product_showprice=".$showprice;
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['cat_cnt']++;
				
				/*###############################################################################*/
				/* Get the list keyword mapping to product category*/
				/*###############################################################################*/
				$sql_catkw =  "SELECT keyword_num,keyword_id  
								FROM 
									se_cat_kw 
								WHERE 
									category_id = ".$row_src_cat['category_id']." 
								ORDER BY 
									keyword_num";
				$ret_catkw = mysql_db_query($src_db,$sql_catkw,$src_link) or die($sql_catkw.' '.mysql_error());
				if(mysql_num_rows($ret_catkw))
				{
					while ($row_catkw = mysql_fetch_array($ret_catkw))
					{
						$uniq_id = uniqid('');
						$sql_insert = "INSERT INTO 
											se_category_keywords  
										SET 
											se_keywords_keyword_id=".$row_catkw['keyword_id'].",
											product_categories_category_id=".$row_src_cat['category_id'].",
											uniq_id='".$uniq_id."'";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
						$ret_arr['catmap_cnt']++;
					}
				}
				
			}
		}
		return $ret_arr;
	}
	
	/* Function to import customer details*/
	function import_Customer_Details()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr = array(
								'corp_cnt'=>0,
								'dept_cnt'=>0,
								'newsgroup_cnt'=>0,
								'comptype_cnt'=>0,
								'country_cnt'=>0,
								'state_cnt'=>0,
								'cust_cnt'=>0,
								'newsgroupmap_cnt'=>0,
								'mailinglist_cnt'=>0
							);
		$newcountry = $newstate = 0;
		// #######################################################################################################
		// Get the customer corporation details from source db
		$corp_cnt= 0;
		$sql_corp = "SELECT corporation_id,corporation_name,corporation_type,corporation_reg_no,corporation_vat_no,corporation_admin_id,
						corporation_billing_id,corporation_discount_method,corporation_discount,prod_discount,corporation_costplus,site_id 
					 FROM 
					 	customers_corps 
					 WHERE 
					 	site_id = $src_siteid";
		$ret_corp = mysql_db_query($src_db,$sql_corp,$src_link) or die($sql_corp.'-'.mysql_error());
		if (mysql_num_rows($ret_corp))
		{
			while($row_corp = mysql_fetch_array($ret_corp))
			{
				$ret_arr['corp_cnt']++;
				// Inserting the corporation details to destination db
				$insert_sql = "INSERT INTO 
								customers_corporation 
									SET 
										corporation_id=".$row_corp['corporation_id'].",
										sites_site_id=".$dest_siteid.",  
										corporation_name='".addslashes(strip_tags(stripslashes($row_corp['corporation_name'])))."',
										corporation_type='".$row_corp['corporation_type']."',
										corporation_regno='".$row_corp['corporation_reg_no']."',
										corporation_vatno='".$row_corp['corporation_vat_no']."',
										corporation_admin_id=".$row_corp['corporation_admin_id'].",
										corporation_billing_id=".$row_corp['corporation_billing_id'].",
										corporation_discount_method='".$row_corp['corporation_discount_method']."',
										corporation_discount=".$row_corp['corporation_discount'].",
										corporation_allow_product_discount='".$row_corp['prod_discount']."',
										corporation_costplus=".$row_corp['corporation_costplus'].",
										corporation_hide = 0";
				mysql_db_query($dest_db,$insert_sql,$dest_link) or die($insert_sql.'-'.mysql_error());			
				
				// #######################################################################################################
				// Get the customer corporation department details from source db
				$dept_cnt= 0;
				$sql_dept = "SELECT corp_dept_id,corporation_id,corp_dept_name,corp_dept_building,corp_dept_street,corp_dept_town,
								corp_dept_county,corp_dept_postcode,country_id,corp_dept_phone,corp_dept_fax  
							 FROM 
							 	customers_corps_dept  
							 WHERE 
							 	corporation_id =". $row_corp['corporation_id'];
				$ret_dept = mysql_db_query($src_db,$sql_dept,$src_link) or die($sql_dept.'-'.mysql_error());
				if (mysql_num_rows($ret_dept))
				{
					while($row_dept = mysql_fetch_array($ret_dept))
					{
						$ret_arr['dept_cnt']++;
						// Calling function to get the id for country and state
						$country_arr = get_CountryState_ids($row_dept['country_id'],$row_dept['corp_dept_county']);
						$newcountry	= $country_arr['country_cnt'];
						//$newstate	= $country_arr['state_cnt'];
						// Inserting the department details to destination db
						$insert_sql = "INSERT INTO 
										customers_corporation_department 
											SET 
												department_id=".$row_dept['corp_dept_id'].",
												customers_corporation_corporation_id=".$row_dept['corporation_id'].",  
												department_name='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_name'])))."',
												sites_site_id=".$dest_siteid.",  
												department_building='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_building'])))."',
												department_street='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_street'])))."',
												department_town='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_town'])))."',
												state_id='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_county'])))."',
												department_postcode='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_postcode'])))."',
												country_id='".$country_arr['country_id']."',
												department_phone='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_phone'])))."',
												department_fax='".addslashes(strip_tags(stripslashes($row_dept['corp_dept_fax'])))."',
												department_hide = 0";
						mysql_db_query($dest_db,$insert_sql,$dest_link) or die($insert_sql.'-'.mysql_error());						
					}
				}			
			}
		}
		
		
		// #######################################################################################################
		/* Get the newsletter groups from source db*/
		$sql_news	 	= "SELECT group_id,group_name 
							FROM 
								newsletter_group 
							WHERE 
								site_id= $src_siteid";
		$ret_news 		= mysql_db_query($src_db,$sql_news,$src_link) or die($sql_news.'-'.mysql_error());
		$newsgrp_cnt	= 0;
		if (mysql_num_rows($ret_news))
		{
			while($row_news = mysql_fetch_array($ret_news))
			{
				$ret_arr['newsgroup_cnt']++;
			 	// Insert the newletter group to dest db
				$sql_insert = "INSERT INTO 
									customer_newsletter_group 
								SET 
									custgroup_id=".$row_news['group_id'].",
									custgroup_name='".addslashes(strip_tags(stripslashes($row_news['group_name'])))."',
									sites_site_id = $dest_siteid,
									custgroup_active=1";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
			}
		}
		
		// #######################################################################################################
		/* Get the list of customers from source db */
		$sql_cust = "SELECT customer_id,corp_dept_id,activated,accountType,title,firstname,middlename,surname,postion,companyName,companyType,
							companyRegNo,companyVatRegNo,addressBuildingName,addressStreetName,addressTownCity,addressStateCounty,phone,
							fax,mobile,town_id,postal_code,country_id,email,pwd,site_id,block,bonus,discount,prod_discount,use_bonus_points,
							mailing_list_member,referredBy,date_added 
						FROM 
							customers 
						WHERE 
							site_id = $src_siteid";
		$ret_cust = mysql_db_query($src_db,$sql_cust,$src_link) or die($sql_cust.'-'.mysql_error());
		$cust_cnt = $comptype_cnt = $country_cnt=$state_cnt = $newsgroupmap_cnt = 00;
		if (mysql_num_rows($ret_cust))
		{
			while ($row_cust = mysql_fetch_array($ret_cust))
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
				//$newstate		+= $country_arr['state_cnt'];
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
										customer_statecounty='".addslashes(strip_tags(stripslashes($row_cust['addressStateCounty'])))."',
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
				$current_cust_id 			= mysql_insert_id();
				$row_cust['customer_id'] 	= $current_cust_id;
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
					// #######################################################################################################
					/* Mapping customers to newsletter groups*/
					// Get the mappings of customers and newsletter group from source db
					$sql_newsmap = "SELECT group_id 
									FROM 
										customers_newsgroups 
									WHERE 
										customer_id=".$row_cust['customer_id'];
					$ret_newsmap = mysql_db_query($src_db,$sql_newsmap,$src_link) or die($sql_newsmap.'-'.mysql_error());
					if(mysql_num_rows($ret_newsmap))
					{
						while($row_newsmap = mysql_fetch_array($ret_newsmap))
						{
							$sql_insert = "INSERT INTO 
												customer_newsletter_group_customers_map 
										 	SET 
										 		custgroup_id=".$row_newsmap['group_id'].",
										 		customer_id=".$newscust_id;
							mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
							$ret_arr['newsgroupmap_cnt']++;
						}
					}
				}	
			}
		}
		$ret_arr['country_cnt'] = $newcountry;
		//$ret_arr['state_cnt']	= $newstate;
		return $ret_arr;
	}
	
	/* Function to import entire keywords, saved searches and also the keyword mappings for saved searches */
	function import_Keyword_Savedsearches()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr = array 
						(
							'keyword_cnt'=>0,
							'saved_cnt'=>0,
							'savedmap_cnt'=>0
						);
		/*###############################################################################*/
		/* Get the list of keywords from source site */	
		/*###############################################################################*/
		$sql_keyword = "SELECT keyword_id,keyword 
							FROM 
								se_keywords 
							WHERE 
								site_id = $src_siteid";
		$ret_keyword = mysql_db_query($src_db,$sql_keyword,$src_link) or die($sql_keyword.' '.mysql_error());
		if (mysql_num_rows($ret_keyword))
		{
			while($row_keyword = mysql_fetch_array($ret_keyword))
			{
				$sql_insert = "INSERT INTO 
									se_keywords
								SET 
									keyword_id = ".$row_keyword['keyword_id'].",
									sites_site_id=$dest_siteid,
									keyword_keyword='".addslashes(strip_tags(stripslashes($row_keyword['keyword'])))."'";
				//echo $sql_insert;
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.' '.mysql_error());
				$ret_arr['keyword_cnt']++;
			}
		}
		/*###############################################################################*/
		/* Get the list of saved searches */
		/*###############################################################################*/
		$sql_saved =  "SELECT search_id,search_keyword,search_date 
						FROM 
							search_index 
						WHERE 
							site_id = $src_siteid";
		$ret_saved = mysql_db_query($src_db,$sql_saved,$src_link) or die($sql_saved.' '.mysql_error());
		if(mysql_num_rows($ret_saved))
		{
			while ($row_saved = mysql_fetch_array($ret_saved))
			{
				$sql_insert = "INSERT INTO 
									saved_search 
								SET 
									search_id=".$row_saved['search_id'].",
									sites_site_id=".$dest_siteid.",
									search_keyword='".addslashes(strip_tags(stripslashes($row_saved['search_keyword'])))."'";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
				
				$ret_arr['saved_cnt']++;
				
				/*###############################################################################*/
				/* Get the list keyword mapping to saved searches*/
				/*###############################################################################*/
				$sql_savedkw =  "SELECT keyword_num,keyword_id  
								FROM 
									se_search_kw 
								WHERE 
									search_id = ".$row_saved['search_id']." 
								ORDER BY 
									keyword_num";
				$ret_savedkw = mysql_db_query($src_db,$sql_savedkw,$src_link) or die($sql_savedkw.' '.mysql_error());
				if(mysql_num_rows($ret_savedkw))
				{
					while ($row_savedkw = mysql_fetch_array($ret_savedkw))
					{
						$uniq_id = uniqid('');
						$sql_insert = "INSERT INTO 
											se_search_keyword  
										SET 
											se_keywords_keyword_id=".$row_savedkw['keyword_id'].",
											saved_search_search_id=".$row_saved['search_id'].",
											uniq_id='".$uniq_id."'";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
						$ret_arr['savedmap_cnt']++;
					}
				}
			}
		}
		return $ret_arr;
	}
	
	/* FUnction to import product vendors, vendor contacts and size chart headings */
	function import_Product_Vendors_SizechartHeadings()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr = array
						(
							'vendor_cnt' => 0,
							'vendorcontact_cnt'=>0,
							'sizecharthead_cnt'=>0
						);
		
		// Get the vendor details from source db
		$sql_vendor = "SELECT vendor_id,vendor_name,vendor_address,vendor_tel,vendor_fax,vendor_email,vendor_website 
						FROM 
							vendors 
						WHERE 
							site_id = $src_siteid";
		$ret_vendor = mysql_db_query($src_db,$sql_vendor,$src_link) or die($sql_vendor."-".mysql_error());
		if (mysql_num_rows($ret_vendor))
		{
			while ($row_vendor = mysql_fetch_array($ret_vendor))
			{
				// Importing the vendor details
				$sql_insert = "INSERT INTO 
									product_vendors 
								SET 
									vendor_id = ".$row_vendor['vendor_id'].",
									sites_site_id=$dest_siteid,
									vendor_name='".addslashes(strip_tags(stripslashes($row_vendor['vendor_name'])))."',
									vendor_address='".addslashes(strip_tags(stripslashes($row_vendor['vendor_address'])))."',
									vendor_telephone='".addslashes(strip_tags(stripslashes($row_vendor['vendor_tel'])))."',
									vendor_fax='".addslashes(strip_tags(stripslashes($row_vendor['vendor_fax'])))."',
									vendor_email='".addslashes(strip_tags(stripslashes($row_vendor['vendor_email'])))."',
									vendor_website='".addslashes(strip_tags(stripslashes($row_vendor['vendor_website'])))."',
									vendor_hide='N'";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
				$ret_arr['vendor_cnt']++;
				// Check whether there exists any contact details for this vendor from source db
				$sql_contact = "SELECT vendor_contact_id,vendor_cname,vendor_ctel,vendor_cfax,vendor_cemail,vendor_cmobile,vendor_cposition 
									FROM 
										vendors_contacts 
									WHERE 
										vendor_id =".$row_vendor['vendor_id'];
				$ret_contact = mysql_db_query($src_db,$sql_contact,$src_link) or die($sql_contact."-".mysql_error());
				if(mysql_num_rows($ret_contact))
				{
					while($row_contact = mysql_fetch_array($ret_contact))
					{
						// Importing vendor contact details
						$sql_insert = "INSERT INTO 	
											product_vendor_contacts 
										SET 
											product_vendors_vendor_id = ".$row_vendor['vendor_id'].",
											contact_name='".addslashes(strip_tags(stripslashes($row_contact['vendor_cname'])))."',
											contact_address='',
											contact_phone='".addslashes(strip_tags(stripslashes($row_contact['vendor_ctel'])))."',
											contact_fax='".addslashes(strip_tags(stripslashes($row_contact['vendor_fax'])))."',
											contact_email='".addslashes(strip_tags(stripslashes($row_contact['vendor_cemail'])))."',
											contact_mobile='".addslashes(strip_tags(stripslashes($row_contact['vendor_cmobile'])))."',
											contact_position='',
											contact_sortorder=".$row_contact['vendor_cposition'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
						$ret_arr['vendorcontact_cnt']++;
					}
				}
			
			}
		}
		
		/* Check whether any size chart headings exists */
		$sql_sizechart = "SELECT heading_id,heading_title,heading_hide,heading_sortorder 
								FROM 
									sizechart_heading 
								WHERE 
									site_id=$src_siteid 
								ORDER BY 
									heading_sortorder";
		$ret_sizechart = mysql_db_query($src_db,$sql_sizechart,$src_link) or die($sql_sizechart."-".mysql_error());
		if (mysql_num_rows($ret_sizechart))
		{
			while ($row_sizechart = mysql_fetch_array($ret_sizechart))
			{
				$sql_insert = "INSERT INTO 
									product_sizechart_heading 
								SET 
									heading_id=".$row_sizechart['heading_id'].",
									heading_title='".addslashes(strip_tags(stripslashes($row_sizechart['heading_title'])))."',
									heading_hide=0,
									heading_sortorder=".$row_sizechart['heading_sortorder'].",
									sites_site_id= $dest_siteid"; 
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
				$ret_arr['sizecharthead_cnt']++;
			}
		}
		return $ret_arr;
	}	
	
	/* FUnction to import custom form elements */
	function import_Custom_Forms()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr	= array
							(
								'section_cnt'=>0,
								'element_cnt'=>0								
							);
		// Get the list of custom section 
		$sql_section = "SELECT section_id,section_name,form_type,activate,sort_no,position,message,status_type 
							FROM 
								element_sections 
							WHERE 
								site_id = $src_siteid";
		$ret_section = mysql_db_query($src_db,$sql_section,$src_link) or die($sql_section."-".mysql_error());
		if (mysql_num_rows($ret_section))
		{
			while($row_section = mysql_fetch_array($ret_section))
			{
				$specific_prod = ($row_section['status_type']!='default')?1:0;	
				$insert_section = "INSERT INTO element_sections 
										SET 
											section_id =".$row_section['section_id'].",
											sites_site_id=".$dest_siteid.",
											section_name='".addslashes(strip_tags(stripslashes($row_section['section_name'])))."',
											activate=".$row_section['activate'].",
											sort_no=".$row_section['sort_no'].",
											message='".addslashes(stripslashes($row_section['message']))."',
											position='".$row_section['position']."',
											section_type='".$row_section['form_type']."',
											section_to_specific_products=$specific_prod";
				mysql_db_query($dest_db,$insert_section,$dest_link) or die($insert_section."-".mysql_error());
				$ret_arr['section_cnt']++;
				// Get the list of form elements in current section
				$sql_form = "SELECT element_id,form_type,element_name,element_type,element_label,element_valign,
									element_align,element_size,sort_no,status,element_rows,element_cols,mandatory,error_msg,maxlength 
								FROM 
									elements 
								WHERE 
									section_id=".$row_section['section_id']." 
									AND site_id=$src_siteid";
				$ret_form = mysql_db_query($src_db,$sql_form,$src_link) or die($sql_form."-".mysql_error());
				if(mysql_num_rows($ret_form))
				{
					while ($row_form = mysql_fetch_array($ret_form))
					{
						$rows = (!$row_form['element_rows'])?0:$row_form['element_rows'];
						$cols = (!$row_form['element_cols'])?0:$row_form['element_cols'];
						$sql_insert = "INSERT INTO 
											elements 
										SET 
											element_id=".$row_form['element_id'].",
											sites_site_id=".$dest_siteid.",
											element_sections_section_id =".$row_section['section_id'].",
											element_name='".$row_form['element_name']."',
											element_type='".$row_form['element_type']."',
											element_label='".addslashes(strip_tags(stripslashes($row_form['element_label'])))."',
											element_valign='".addslashes(strip_tags(stripslashes($row_form['element_valign'])))."',
											element_align='".addslashes(strip_tags(stripslashes($row_form['element_align'])))."',
											element_size='".addslashes(strip_tags(stripslashes($row_form['element_size'])))."',
											sort_no=".$row_form['sort_no'].",
											element_rows=".$rows.",
											element_cols=".$cols.",
											mandatory='".$row_form['mandatory']."',
											error_msg='".addslashes(strip_tags(stripslashes($row_form['error_msg'])))."',
											maxlength=".$row_form['maxlength'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
						$ret_arr['element_cnt']++;
						if ($row_form['element_type']=='select')
						{
							// Check whether values exists for current element
							$sql_values = "SELECT value_id,element_id,element_values,selected 
												FROM 
													element_value 
												WHERE 
													element_id =".$row_form['element_id'];
							$ret_values = mysql_db_query($src_db,$sql_values,$src_link) or die($sql_values."-".mysql_error());
							if (mysql_num_rows($ret_values))
							{
								while($row_values = mysql_fetch_array($ret_values))
								{
									$sql_insert = "INSERT INTO 
														element_value 
													SET 
														value_id=".$row_values['value_id'].",
														elements_element_id=".$row_form['element_id'].",
														element_values='".addslashes(strip_tags(stripslashes($row_values['element_values'])))."',
														selected=".$row_values['selected'];
									mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert."-".mysql_error());
								}
							}
						}
					}
				}
			}
		}
		return $ret_arr;
	}	
	
	/* FUnction to import Product Details */
	function import_Products()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr		= array
								(
									'prod_cnt'=>0,
									'prodcatmap_cnt'=>0,
									'prodvendmap_cnt'=>0,
									'prodbulk_cnt'=>0,
									'prodsizeheadmap_cnt'=>0,
									'prodsizevalues_cnt'=>0,
									'review_cnt'=>0,
									'tab_cnt'=>0,
									'linked_cnt'=>0
								);
		// Get the list of products from source db
		$sql_prod = "SELECT product_id,parent,m_id,pname,model,short_description,long_description,stock,category_id,price,cost_price,weight,
							shipping_cost,new_tag,offer_tag,bonus,discount,bulk_discount,apply_tax,variable_stock,prod_order,shop_prod_order,
							pack_size,hide_product,height,width,instock_date,allow_preorder,max_pre_order_stock,show_enquire,
							show_cart,product_deposit,product_deposit_message,product_code 
						FROM 
							products 
						WHERE 
							site_id = $src_siteid";
		$ret_prod = mysql_db_query($src_db,$sql_prod,$src_link) or die($sql_prod.'-'.mysql_error());
		if(mysql_num_rows($ret_prod))
		{
			while($row_prod = mysql_fetch_array($ret_prod))
			{
				$total_actual_stock = 0;
				if ($row_prod['variable_stock']=='Y')// if variable stock is maintained then at present the stock is set to 0 will be updated while importing the variable details
				{
					$webstock 		= 0;
					$actualstock 	= 0;
				}
				else  // case if variable stock is not maintained, then the actual stock and webstock is set to the imported stock value
				{
					$actualstock = $webstock = $row_prod['stock'];
				}
				// Inserting to products table in destination db
				$sql_insert = "INSERT INTO 
									products 
								SET 
									product_id=".$row_prod['product_id'].",
									sites_site_id=$dest_siteid,
									parent_id=".$row_prod['parent'].",
									product_adddate=now(),
									product_barcode='',
									manufacture_id='".addslashes(strip_tags(stripslashes($row_prod['m_id'])))."',
									product_name='".addslashes(strip_tags(stripslashes($row_prod['pname'])))."',
									product_model='".addslashes(strip_tags(stripslashes($row_prod['model'])))."',
									product_shortdesc='".addslashes(strip_tags(stripslashes($row_prod['short_description'])))."',
									product_longdesc='".addslashes(stripslashes($row_prod['long_description']))."',
									product_hide='".$row_prod['hide_product']."',
									product_webstock=$webstock,
									product_actualstock=$actualstock,
									product_costprice=".$row_prod['cost_price'].",
									product_webprice=".$row_prod['price'].",
									product_weight=".$row_prod['weight'].",
									product_reorderqty=0,
									product_extrashippingcost=".$row_prod['shipping_cost'].",
									product_bonuspoints=".$row_prod['bonus'].",
									product_discount=".$row_prod['discount'].",
									product_discount_enteredasval=0,
									product_bulkdiscount_allowed='".$row_prod['bulk_discount']."',
									product_applytax='".$row_prod['apply_tax']."',
									product_variablestock_allowed='".$row_prod['variable_stock']."',
									product_preorder_allowed='".$row_prod['allow_preorder']."',
									product_total_preorder_allowed=".$row_prod['max_pre_order_stock'].",
									product_instock_date='".$row_prod['instock_date']."',
									product_deposit=".$row_prod['product_deposit'].",
									product_deposit_message='".addslashes(strip_tags(stripslashes($row_prod['product_deposit_message'])))."',
									product_show_cartlink=".$row_prod['show_cart'].",
									product_show_enquirelink=".$row_prod['show_enquire'].",
									product_default_category_id=".$row_prod['category_id'].",
									product_code='".$row_prod['product_code']."',
									product_averagerating=0,
									product_variable_display_type='ADD'";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['prod_cnt']++;
				
				$var_exists 					= 'N';
				$var_addprice_exists		= 'N';
				// Check whether variables exists for current product in source db
				$sql_var = "SELECT var_id,site_id,vname,message,variable_order,message_size,show_fullprice 
								FROM 
									variables 
								WHERE 
									product_id=".$row_prod['product_id']." 
								ORDER BY 
									var_id";
				$ret_var = mysql_db_query($src_db,$sql_var,$src_link) or die($sql_var.'-'.mysql_error());
				if (mysql_num_rows($ret_var))
				{
					$vars 			= array();
					$vnames 		= array();
					$indices 			= array();
					$values 			= array();
					$hashes 		= array();
					$valueid_arr	= array();
					while ($row_var = mysql_fetch_array($ret_var))
					{
						if ($row_var['message']=='N')// If this is a variable
						{
							$var_exists = 'Y';		
							// Get the values set for the current variables
							$sql_varval = "SELECT var_value_id,var_value,price,var_order,variable_code 
												FROM 
													variables_data 
												WHERE 
													var_id =".$row_var['var_id']." 
												ORDER BY 
													var_value_id";
							$ret_varval = mysql_db_query($src_db,$sql_varval,$src_link) or die($sql_var.'-'.mysql_error());
							if (mysql_num_rows($ret_varval))
							{
								// Variable inserting is done here because the variables are imported only if there exists atlease one value for it.
								$var_id 				= $row_var['var_id'];
								array_push($vars, $var_id);
								$vnames[$var_id] 		= $row_var['vname'];
								$indices[$var_id] 		= 0;
								$values[$var_id] 		= array();
								$valueid_arr[$var_id]	= array();
								$sql_insert = "INSERT INTO 
													product_variables 
												SET 
													var_id=".$row_var['var_id'].",
													products_product_id=".$row_prod['product_id'].",
													var_name='".addslashes(strip_tags(stripslashes($row_var['vname'])))."',
													var_order=".$row_var['variable_order'].",
													var_hide=0,
													var_value_exists=1,
													var_price=0";
								mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());	
								while ($row_varval = mysql_fetch_array($ret_varval))
								{
									$var_id = $row_var['var_id'];
									array_push($values[$var_id], $row_varval['var_value']);
									$c_order = ($row_varval['var_order'])?$row_varval['var_order']:0;
									$c_price = ($row_varval['price'])?$row_varval['price']:0;
									//Inserting to the varvalue in destination db
									$sql_insert = "INSERT INTO 
														product_variable_data 
													SET 
														product_variables_var_id=".$row_var['var_id'].",
														var_value='".addslashes(strip_tags(stripslashes($row_varval['var_value'])))."',
														var_addprice=".$c_price.",
														var_order=".$c_order.",
														var_code='".$row_varval['variable_code']."'";
									mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
									$var_value_insert_id = mysql_insert_id();
									array_push($valueid_arr[$var_id], $var_value_insert_id);
									if ($row_varval['price']>0)
										$var_addprice_exists = 'Y';
								}
							}
						}
						else // case not a variable, but message
						{
							$var_order = ($row_var['variable_order'])?$row_var['variable_order']:0;
							$sql_insert = "INSERT INTO 
												product_variable_messages 
											SET 
												products_product_id=".$row_prod['product_id'].",
												message_title='".addslashes(strip_tags(stripslashes($row_var['vname'])))."',
												message_type='TXTBX',
												message_hide=0,
												message_order=".$var_order;
							mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						}
					}
				}
				if ($var_addprice_exists == 'Y' or $var_exists =='Y')
				{
					$update_prods = "UPDATE products 
												SET 
													product_variables_exists = '$var_exists', 
													product_variablesaddonprice_exists='$var_addprice_exists'  
												WHERE 
													product_id = ".$row_prod['product_id']."  
												LIMIT 
													1";
					mysql_db_query($dest_db,$update_prods,$dest_link) or die($update_prods.'-'.mysql_error());								
				}
				/* If variable stock is maintained then the following section will have to pick the stock for each of the combinations*/
				if ($row_prod['variable_stock']=='Y')
				{
					if(count($vars)) // if variables exists
					{
						
							do
							{
								// Building the hash value to pick the stock from source db
								$var_hash = "";
								$varvalue_arr = array();
								foreach($vars as $var_id)
								{
									$var_hash 		.= $values[$var_id][$indices[$var_id]];
									$varvalue_arr[] = $valueid_arr[$var_id][$indices[$var_id]].'~'.$var_id;
								}
								if (count($varvalue_arr))
								{
									$var_hash = sprintf("%u", crc32($var_hash));
									// Get the value of stock for current has value for current product
									$sql_stock = "SELECT stock 
													FROM 
														variable_stock 
													WHERE 
														product_id=".$row_prod['product_id']." 
														AND var_hash='".$var_hash."' 
													LIMIT 
														1";
									$ret_stock = mysql_db_query($src_db,$sql_stock,$src_link) or die($sql_stock.'-'.mysql_error());
									if (mysql_num_rows($ret_stock))
									{
										$row_stock = mysql_fetch_array($ret_stock);
										if ($row_stock['stock']>0)
										{
											// Create a new combination entry
											$sql_insert = "INSERT INTO 
																product_variable_combination_stock 
															SET 
																products_product_id=".$row_prod['product_id'].",
																web_stock=".$row_stock['stock'].",
																actual_stock=".$row_stock['stock'].",
																comb_barcode=''";
											mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
											$total_actual_stock += $row_stock['stock'];
											$comb_id = mysql_insert_id();
											
											// Making entries to combination details table
											foreach ($varvalue_arr as $k=>$v)
											{
												$det_arr 		= explode('~',$v);
												$var_value_id	= $det_arr[0];
												$var_id 		= $det_arr[1];
												if ($var_value_id and $var_id)
												{
													$sql_insert = "INSERT INTO 
																		product_variable_combination_stock_details 
																	SET 
																		comb_id=".$comb_id.",
																		product_variables_var_id=".$var_id.",
																		product_variable_data_var_value_id=".$var_value_id.",
																		products_product_id=".$row_prod['product_id'];
													mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
												}
											}
										}
									}
								}	
							} while(advance_index_new($vars, $indices, $values));
					}	
				}
				
				// ##########################################################################
				// Section to handle the product category mapping for current product
				// ##########################################################################
				// Getting the mapping from source db for current product
				$sql_catmap = "SELECT category_id 
								FROM 
									product_category_map 
								WHERE 
									product_id=".$row_prod['product_id'];
				$ret_catmap = mysql_db_query($src_db,$sql_catmap,$src_link) or die($sql_catmap.'-'.mysql_error());
				if (mysql_num_rows($ret_catmap))
				{
					// Get the product order from products table 
					$sql_prodorder = "SELECT prod_order 
												FROM 
													products 
												WHERE 
													product_id=".$row_prod['product_id']." 
												LIMIT 
													1";
					$ret_prodorder = mysql_db_query($src_db,$sql_prodorder,$src_link) or die($sql_prodorder.'-'.mysql_error());		
					if(mysql_num_rows($ret_prodorder))
					{
						$row_prodorder = mysql_fetch_array($ret_prodorder);
						$prodorder 		= $row_prodorder['prod_order'];
					}						
					if(!$prodorder	or $prodorder =='')
						$prodorder = 0;
								
					while ($row_catmap = mysql_fetch_array($ret_catmap))
					{
						$sql_insert = "INSERT INTO 
											product_category_map	
										SET
											products_product_id=".$row_prod['product_id'].",
											product_categories_category_id=".$row_catmap['category_id'].",
											product_order = $prodorder";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['prodcatmap_cnt']++;
					}
				}
				
				// ##########################################################################
				// Section to handle the product vendor mapping for current product
				// ##########################################################################
				// Getting the mapping from source db for current product
				$sql_vendprod = "SELECT assign_id,vendor_id  
								FROM 
									vendors_productassign  
								WHERE 
									product_id=".$row_prod['product_id'];
				$ret_vendprod = mysql_db_query($src_db,$sql_vendprod,$src_link) or die($sql_vendprod.'-'.mysql_error());
				if (mysql_num_rows($ret_vendprod))
				{
					while ($row_vendprod = mysql_fetch_array($ret_vendprod))
					{
						$sql_insert = "INSERT INTO 
											product_vendor_map 	
										SET 
											map_id=".$row_vendprod['assign_id'].",
											products_product_id=".$row_prod['product_id'].",
											product_vendors_vendor_id=".$row_vendprod['vendor_id'].",
											sites_site_id=$dest_siteid";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['prodvendmap_cnt']++;
					}
				}
				
				// ##############################################################################
				// Section to handle the bulk discount for current product
				// ##############################################################################
				$sql_bulk = "SELECT bulk_id,quantity,price 
								FROM 
									bulk_discounts 
								WHERE 
									product_id=".$row_prod['product_id']." 
								ORDER BY 
									bulk_id";
				$ret_bulk = mysql_db_query($src_db,$sql_bulk,$src_link) or die($sql_bulk.'-'.mysql_error());
				if (mysql_num_rows($ret_bulk))
				{
					while($row_bulk = mysql_fetch_array($ret_bulk))
					{
						$sql_insert = "INSERT INTO 
											product_bulkdiscount 
										SET 
											products_product_id=".$row_prod['product_id'].',
											bulk_qty='.$row_bulk['quantity'].",
											bulk_price=".$row_bulk['price'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['prodbulk_cnt']++;
					}
				}
				
				
				// ##############################################################################
				// Section to handle the product size chart heading mapping for current product
				// ##############################################################################
				$sql_sizeheading = "SELECT map_id,heading_id,product_id,site_id,map_order 
										FROM 
											sizechart_heading_product_map 
										WHERE 
											product_id =".$row_prod['product_id'];
				$ret_sizeheading = mysql_db_query($src_db,$sql_sizeheading,$src_link) or die($sql_sizeheading.'-'.mysql_error());
				if (mysql_num_rows($ret_sizeheading))
				{
					while ($row_sizeheading = mysql_fetch_array($ret_sizeheading))
					{
						$sql_insert = "INSERT INTO 
											product_sizechart_heading_product_map 
										SET 
											map_id=".$row_sizeheading['map_id'].",
											heading_id=".$row_sizeheading['heading_id'].",
											products_product_id=".$row_prod['product_id'].",
											sites_site_id=$dest_siteid,
											map_order=".$row_sizeheading['map_order'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['prodsizeheadmap_cnt']++;
						// Get the size chart values to be saved for current heading map
						$sql_sizevalue = "SELECT size_id,size_value,size_sortorder 
											FROM 
												sizechart_values 
											WHERE 
												site_id=$src_siteid 
												AND map_id=".$row_sizeheading['map_id']." 
											ORDER BY 
												size_id";
						$ret_sizevalue = mysql_db_query($src_db,$sql_sizevalue,$src_link) or die($sql_sizevalue.'-'.mysql_error());
						if(mysql_num_rows($ret_sizevalue))
						{
							while ($row_sizevalue = mysql_fetch_array($ret_sizevalue))
							{
								$sql_insert = "INSERT INTO 
													product_sizechart_values 
												SET 
													size_id=".$row_sizevalue['size_id'].",
													map_id=".$row_sizeheading['map_id'].",
													heading_id=".$row_sizeheading['heading_id'].",
													products_product_id=".$row_prod['product_id'].",
													sites_site_id=$dest_siteid,
													size_value='".addslashes(strip_tags(stripslashes($row_sizevalue['size_value'])))."',
													size_sortorder=".$row_sizevalue['size_sortorder'];
								mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
								$ret_arr['prodsizevalues_cnt']++;
							}
						}
					}
				}
				// ##############################################################################
				// Section to handle the product reviews for current product
				// ##############################################################################
				//  Get the product reviews from source db
				$sql_review = "SELECT id,rating,review,author 
									FROM 
										reviews 
									WHERE 
										product_id =".$row_prod['product_id'];
				$ret_review = mysql_db_query($src_db,$sql_review,$src_link) or die($sql_review.'-'.mysql_error());
				$tot_review = $tot_cnt = 0;
				if(mysql_num_rows($ret_review))
				{
					while ($row_review = mysql_fetch_array($ret_review))
					{
						// Inserting the review to the destination db
						$sql_insert = "INSERT INTO 
											product_reviews 
										SET 
											review_id=".$row_review['id'].",
											sites_site_id=$dest_siteid,
											products_product_id=".$row_prod['product_id'].",
											review_date=now(),
											review_author='".addslashes(strip_tags(stripslashes($row_review['author'])))."',
											review_author_email='',
											review_details='".addslashes(strip_tags(stripslashes($row_review['review'])))."',
											review_rating=".$row_review['rating'].",
											review_status='NEW',
											review_approved_by=0,
											review_hide=0";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['review_cnt']++;
					}
					/* This section get the average rating. This value will be placed in the products table to be used in product details page in client area*/
					$sql_avg = "SELECT avg(review_rating) as average  
									FROM 
										product_reviews 
									WHERE 
										products_product_id=".$row_prod['product_id'];
					$ret_avg = mysql_db_query($dest_db,$sql_avg,$dest_link);
					list($avg_rate) = mysql_fetch_array($ret_avg);
					if ($avg_rate>0)
					{
						$sql_update = "UPDATE 
											products 
										SET 
											product_averagerating=".$avg_rate." 
										WHERE 
											product_id=".$row_prod['product_id']." 
										LIMIT 
											1";
						mysql_db_query($dest_db,$sql_update,$dest_link) or die($sql_update.'-'.mysql_error());
					}
				}
				// ##############################################################################
				// Section to handle the tabs of current product
				// ##############################################################################
				// Get the tab details for current product
				$sql_tab = "SELECT tab_id,tab_title,tab_description 
								FROM 
									product_tabdetails 
								WHERE 
									product_id=".$row_prod['product_id'];
				$ret_tab = mysql_db_query($src_db,$sql_tab,$src_link) or die($sql_tab.'-'.mysql_error());
				if (mysql_num_rows($ret_tab))
				{
					while ($row_tab = mysql_fetch_array($ret_tab))
					{
						// Inserting the tab details to destination db
						$sql_insert = "INSERT INTO 
											product_tabs 
										SET 
											tab_id=".$row_tab['tab_id'].",
											products_product_id=".$row_prod['product_id'].",
											tab_title='".addslashes(strip_tags(stripslashes($row_tab['tab_title'])))."',
											tab_content='".addslashes(stripslashes($row_tab['tab_description']))."',
											tab_order=0,
											tab_hide=0";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['tab_cnt']++;
					}
				}
				
				// ##############################################################################
				// Section to handle the products linked with current product
				// ##############################################################################
				$sql_link = "SELECT linked_id 
								FROM 
									linked_products 
								WHERE 
									product_id = ".$row_prod['product_id'];
				$ret_link = mysql_db_query($src_db,$sql_link,$src_link) or die($sql_link.'-'.mysql_error());
				if(mysql_num_rows($ret_link))
				{
					while($row_link = mysql_fetch_array($ret_link))
					{
						// Inserting the linked products to destination db
						$sql_insert = "INSERT INTO 
											product_linkedproducts 
										SET 
											sites_site_id=$dest_siteid,
											link_parent_id=".$row_prod['product_id'].",
											link_product_id=".$row_link['linked_id'].",
											link_order =0,
											link_hide=0";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['linked_cnt']++;
					}
				}
				
				// ####################################################################################
				// Handling the case of pack_size
				// ####################################################################################
				if(($row_prod['pack_size'])!='')
				{
					$label_id = 0;
					// Check whether the label named 'Pack Size' exists for destination db in product_site_labels
					$sql_check = "SELECT label_id 
									FROM 
										product_site_labels 
									WHERE 
										sites_site_id=$dest_siteid 
										AND label_name='Pack Size' 
									LIMIT 
										1";
					$ret_check = mysql_db_query($dest_db,$sql_check,$dest_link) or die($sql_check.'-'.mysql_error());
					if (mysql_num_rows($ret_check))
					{
						$row_check = mysql_fetch_array($ret_check);
						$label_id = $row_check['label_id'];
					}
					else 
					{
						$sql_insert = "INSERT INTO 
											product_site_labels 
										SET 
											sites_site_id = $dest_siteid,
											label_name='Pack Size',
											in_search=0,
											is_textbox=1,
											label_hide=0,
											label_order=1";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$label_id = mysql_insert_id();
					}
					// Making an entry to the table product_labels
					if ($label_id)
					{
						$sql_insert = "INSERT INTO 
											product_labels 
										SET 
											products_product_id=".$row_prod['product_id'].",
											product_site_labels_label_id=$label_id,
											product_site_labels_values_label_value_id=0,
											label_value='".addslashes(strip_tags(stripslashes($row_prod['pack_size'])))."',
											is_textbox=1";
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
					}	
				}
			}// product main end
		}
		return $ret_arr;
	}	
	function advance_index_new($vars, &$indices, $values)
	{
		$var_id = array_shift($vars);
		if(!$var_id) return FALSE;
		$indices[$var_id]++;
		if($indices[$var_id] >= count($values[$var_id]))
		{
			$indices[$var_id] = 0;
			return advance_index_new($vars, $indices, $values);
		}
		return true;
	}
	
	/* Function to import the featured product, customer form section product map, promotional code and products for promotional code*/
	function Import_Featured_Promo()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr = array
						(
							'featured_cnt'=>0,
							'secprod_cnt'=>0,
							'promo_cnt'=>0,
							'promoprod_cnt'=>0
						);
		// ####################################################################
		// get the featured product details from src db
		// ####################################################################
		$sql_feat = "SELECT product_id,site_id,featured_desc 
						FROM 
							featured 
						WHERE 
							site_id=$src_siteid
						LIMIT 
							1";
		$ret_feat = mysql_db_query($src_db,$sql_feat,$src_link) or die($sql_feat.'-'.mysql_error());
		if (mysql_num_rows($ret_feat))
		{
			$row_feat = mysql_fetch_array($ret_feat);
			// Importing the details to destination db
			$sql_insert = "INSERT INTO 
									product_featured 
								SET 
									sites_site_id=$dest_siteid,
									products_product_id=".$row_feat['product_id'].",
									featured_desc='".addslashes(stripslashes($row_feat['featured_desc']))."',
									featured_showimage=1,
									featured_showtitle=1,
									featured_showshortdescription=1,
									featured_showprice=1,
									featured_showimagetype='Thumb'";
			mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
			$ret_arr['featured_cnt']=1;
		}
		// ####################################################################
		// Get the products mapped to custom section from source db
		// ####################################################################
		$sql_section_prod = "SELECT id,site_id,product_id,section_id 
									FROM 
										section_products 
									WHERE 
										site_id=$src_siteid";
		$ret_section_prod = mysql_db_query($src_db,$sql_section_prod,$src_link) or die($sql_section_prod.'-'.mysql_error());
		if(mysql_num_rows($ret_section_prod))
		{
			while($row_section_prod = mysql_fetch_array($ret_section_prod))
			{
				$section_valid = false;
				$product_valid = false;
				// Check whether the section_id exists in destination db
				$sql_sec = "SELECT section_id 
								FROM 
									element_sections 
								WHERE 
									sites_site_id=$dest_siteid 
									AND section_id=".$row_section_prod['section_id']." 
								LIMIT 
									1";
				$ret_sec = mysql_db_query($dest_db,$sql_sec,$dest_link) or die($sql_sec.'-'.mysql_error());
				if(mysql_num_rows($ret_sec))
					$section_valid = true;
				
				// Check whether product id exists in destination db
				$sql_prod = "SELECT product_id 
								FROM 
									products 
								WHERE 
									sites_site_id=$dest_siteid 
									AND product_id=".$row_section_prod['product_id']." 
								LIMIT 
									1";
				$ret_prod = mysql_db_query($dest_db,$sql_prod,$dest_link) or die($sql_prod.'-'.mysql_error());
				if(mysql_num_rows($ret_prod))
					$product_valid = true;
				
				// add the mapping only if both product and section are valid
				if($section_valid and $product_valid)
				{
					$sql_insert = "INSERT INTO 
										element_section_products 
									SET
										element_sections_section_id=".$row_section_prod['section_id'].",
										products_product_id=".$row_section_prod['product_id'].",
										product_active=1,
										sites_site_id=$dest_siteid";
					mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
					$ret_arr['secprod_cnt']++;
				}
			}
		}
		
		// ####################################################################
		// Importing promotional code details from source db
		// ####################################################################
		$sql_prom = "SELECT code_id,code_number,code_startdate,code_enddate,code_type,code_minimum,code_value,code_active 
						FROM 
							promotional_code 
						WHERE 
							site_id = $src_siteid";
		$ret_prom = mysql_db_query($src_db,$sql_prom,$src_link) or die($sql_prom.'-'.mysql_error());
		if (mysql_num_rows($ret_prom))
		{
			while($row_prom = mysql_fetch_array($ret_prom))
			{
				$hidden = ($row_prom['code_active']==1)?0:1;
				$sql_insert = "INSERT INTO 
									promotional_code 
								SET 
									code_id=".$row_prom['code_id'].",
									sites_site_id=$dest_siteid,
									code_number='".$row_prom['code_number']."',
									code_startdate='".$row_prom['code_startdate']."',
									code_enddate='".$row_prom['code_enddate']."',
									code_type='".$row_prom['code_type']."',
									code_minimum=".$row_prom['code_minimum'].",
									code_value=".$row_prom['code_value'].",
									code_hidden=".$hidden;
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['promo_cnt']++;
				// Check whether products need to be picked for this promotional code
				if($row_prom['code_type']=='product')
				{
					$sql_prom_prod = "SELECT pcode_det_id,product_id,product_price,product_active 
										FROM 
											promotional_code_product 
										WHERE 
											code_id=".$row_prom['code_id'];
					$ret_prom_prod = mysql_db_query($src_db,$sql_prom_prod,$src_link) or die($sql_prom.'-'.mysql_error());
					if(mysql_num_rows($ret_prom_prod))
					{
						while ($row_prom_prod = mysql_fetch_array($ret_prom_prod))
						{
							// Check whether the product exists in new db
							$sql_prod = "SELECT product_id 
												FROM 
													products 
												WHERE 
													product_id=".$row_prom_prod['product_id']."
													AND sites_site_id=$dest_siteid 
												LIMIT 
													1";
							$ret_prod = mysql_db_query($dest_db,$sql_prod,$dest_link) or die($sql_prod.'-'.mysql_error());
							if(mysql_num_rows($ret_prod))
							{
								// Importing to destination db
								$sql_insert = "INSERT INTO 
													promotional_code_product 
												SET 
													promotional_code_code_id=".$row_prom['code_id'].",
													products_product_id=".$row_prom_prod['product_id'].",
													sites_site_id=$dest_siteid,
													product_price=".$row_prom_prod['product_price'].",
													product_active=".$row_prom_prod['product_active'];
								mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
								$ret_arr['promoprod_cnt']++;
							}	
						}
					}
				}
			}
		}
		return $ret_arr;
	}
	
	/* Function to import shop by brand details*/
	function Import_ShopbyBrand()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr = array
						(
							'shop_cnt'=>0,
							'shopprod_cnt'=>0,
							'shopkw_cnt'=>0
						);
		// get the shop by brand from source db
		$sql_shop = "SELECT shop_id,shop_name,description,parent,shop_order,prod_sort 
						FROM 
							shops 
						WHERE 
							site_id =$src_siteid";
		$ret_shop = mysql_db_query($src_db,$sql_shop,$src_link) or die($sql_shop.'-'.mysql_error());
		if (mysql_num_rows($ret_shop))
		{
			while ($row_shop = mysql_fetch_array($ret_shop))
			{
				// ########################################################################
				// Importing the shop by brand to destination db
				// ########################################################################
				$sql_insert = "INSERT INTO 
									product_shopbybrand 
								SET 
									shopbrand_id=".$row_shop['shop_id'].",
									shopbrand_parent_id=".$row_shop['parent'].",
									sites_site_id=$dest_siteid,
									shopbrand_name='".addslashes(strip_tags(stripslashes($row_shop['shop_name'])))."',
									shopbrand_hide=0,
									shopbrand_product_displaytype='OneinRow',
									shopbrand_order=".$row_shop['shop_order'].",
									shopbrand_showimageofproduct=0,
									shopbrand_subshoplisttype='Middle',
									shopbrand_default_shopbrandgroup_id=0,
									shopbrand_product_showimage=1,
									shopbrand_product_showtitle=1,
									shopbrand_product_showshortdescription=0,
									shopbrand_product_showprice=1";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['shop_cnt']++;
				// ########################################################################
				// Get the products linked with the current shop
				// ########################################################################
				$sql_shopprod = "SELECT data_id,shop_id,product_id 
									FROM 
										shop_data 
									WHERE 
										shop_id=".$row_shop['shop_id'];
				$ret_shopprod = mysql_db_query($src_db,$sql_shopprod,$src_link) or die($sql_shopprod.'-'.mysql_error());
				if (mysql_num_rows($ret_shopprod))
				{
					while ($row_shopprod = mysql_fetch_array($ret_shopprod))
					{
						// Check whether current product exists in destination db
						$sql_prod = "SELECT product_id 
											FROM 
												products 
											WHERE 
												product_id=".$row_shopprod['product_id']." 
												AND sites_site_id=$dest_siteid 
											LIMIT 
												1";
						$ret_prod = mysql_db_query($dest_db,$sql_prod,$dest_link) or die($sql_prod.'-'.mysql_error());
						if (mysql_num_rows($ret_prod))
						{
							// Get the product order from products table 
							$sql_prodorder = "SELECT shop_prod_order 
														FROM 
															products 
														WHERE 
															product_id=".$row_shopprod['product_id']." 
														LIMIT 
															1";
							$ret_prodorder = mysql_db_query($src_db,$sql_prodorder,$src_link) or die($sql_prodorder.'-'.mysql_error());		
							if(mysql_num_rows($ret_prodorder))
							{
								$row_prodorder = mysql_fetch_array($ret_prodorder);
								$prodorder 		= $row_prodorder['shop_prod_order'];
							}						
							if(!$prodorder	or $prodorder =='')
								$prodorder = 0;
							// Impprting the products for the shop
							$sql_insert = "INSERT INTO 
												product_shopbybrand_product_map 
											SET 
												product_shopbybrand_shopbrand_id=".$row_shop['shop_id'].",
												products_product_id=".$row_shopprod['product_id'].",
												map_sortorder =$prodorder";
							mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
							$ret_arr['shopprod_cnt']++;
						}	
					}
				}
				// ########################################################################
				// Get the keyword mapping associated with current shop
				// ########################################################################
				$sql_kw = "SELECT keyword_id 
								FROM 
									se_shop_kw 
								WHERE 
									shop_id=".$row_shop['shop_id']." 
								ORDER BY 
									keyword_num";
				$ret_kw = mysql_db_query($src_db,$sql_kw,$src_link) or die($sql_kw.'-'.mysql_error());
				if (mysql_num_rows($ret_kw))
				{
					while ($row_kw = mysql_fetch_array($ret_kw))
					{
						// Check whether keyword exists in current site
						$sqldest_kw = "SELECT keyword_id 
										FROM 
											se_keywords  
										WHERE 
											sites_site_id=$dest_siteid 
											AND keyword_id=".$row_kw['keyword_id']." 
										LIMIT 
											1";
						$retdest_kw = mysql_db_query($dest_db,$sqldest_kw,$dest_link) or die($sqldest_kw.'-'.mysql_error());
						if(mysql_num_rows($retdest_kw))
						{
							$uniq_id = uniqid('');
							// Importing the keyword mapping to destination db
							$sql_insert = "INSERT INTO 
												se_shop_keywords 
											SET 
												se_keywords_keyword_id=".$row_kw['keyword_id'].",
												product_shopbybrand_shopbrand_id=".$row_shop['shop_id'].",
												uniq_id='".$uniq_id."'";
							mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
							$ret_arr['shopkw_cnt']++;
						}
					}
				}
			}
		}
		return  $ret_arr;
	}
	
	/* Function to import static page details*/
	function Import_Staticpages()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		// Get the list of static pages from the source db
		$sql_stat = "SELECT page_id,pname,title,hide_edit,page_hide 
						FROM 
							static_pages 
						WHERE 
							site_id=$src_siteid";
		$ret_stat = mysql_db_query($src_db,$sql_stat,$src_link) or die($sql_stat.'-'.mysql_error());
		if (mysql_num_rows($ret_stat))
		{
			while ($row_stat = mysql_fetch_array($ret_stat))
			{
				// Get the page content from page_cells table from the source db
				$sql_content = "SELECT content 
									FROM 
										page_cells 
									WHERE 
										page_id=".$row_stat['page_id']." 
									LIMIT 
										1";
				$ret_content = mysql_db_query($src_db,$sql_content,$src_link) or die($sql_content.'-'.mysql_error());
				if(mysql_num_rows($ret_content))
				{
					$row_content 	= mysql_fetch_array($ret_content);
					$allow_edit		= ($row_stat['hide_edit'])?0:1;
					
					// Importing the details to destination db
					$sql_insert = "INSERT INTO 
										static_pages 
									SET 
										page_id=".$row_stat['page_id'].",
										sites_site_id=$dest_siteid,
										title='".addslashes(strip_tags(stripslashes($row_stat['title'])))."',
										content='".addslashes(stripslashes($row_content['content']))."',
										hide=".$row_stat['page_hide'].",
										pname='".addslashes(strip_tags(stripslashes($row_stat['pname'])))."',
										page_type='Page',
										page_link='',
										allow_edit=".$allow_edit;
					mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
					$ret_arr['stat_cnt']++;
					
					// Check whether keyword assigned to current static page in source db
					$sql_kw = "SELECT keyword_id 
									FROM 
										se_static_kw 
									WHERE 
										page_id=".$row_stat['page_id']." 
									ORDER BY
									 keyword_num";
					$ret_kw = mysql_db_query($src_db,$sql_kw,$src_link) or die($sql_kw.'-'.mysql_error());
					
					if(mysql_num_rows($ret_kw))
					{
						while($row_kw = mysql_fetch_array($ret_kw))
						{
							// check whether current keyword exists in destination db
							$sql_check = "SELECT keyword_id 
										FROM 
											se_keywords  
										WHERE 
											sites_site_id=$dest_siteid 
											AND keyword_id=".$row_kw['keyword_id']." 
										LIMIT 
											1";
							$ret_check = mysql_db_query($dest_db,$sql_check,$dest_link) or die($sql_check.'-'.mysql_error());
							if(mysql_num_rows($ret_check))
							{
								$uniq_id = uniqid('');
								// Importing the kws mapped to current page
								$sql_insert = "INSERT INTO 
													se_static_keywords 
												SET 
													static_pages_page_id=".$row_stat['page_id'].",
													se_keywords_keyword_id=".$row_kw['keyword_id'].",
													uniq_id='".$uniq_id."'";
								mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
								$ret_arr['statkw_cnt']++;
							}
						}
					}
				}
			}
		}
		return $ret_arr;
	}
	
	/* Function to import survey details */
	
	function Import_Survey()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		$ret_arr	= array
							(
								'sur_cnt'=>0,
								'surres_cnt'=>0
							);
		// Get the list of survey from source db
		$sql_sur = "SELECT survey_id,title,question,status,display_results 
						FROM 
							surveys 
						WHERE 
							site_id=$src_siteid";
		$ret_sur = mysql_db_query($src_db,$sql_sur,$src_link) or die($sql_sur.'-'.mysql_error());
		if (mysql_num_rows($ret_sur))
		{
			while ($row_sur = mysql_fetch_array($ret_sur))
			{
				$display_res = ($row_sur['display_results']=='Y')?1:0;
				// Import the survery details to destination db
				$sql_insert = "INSERT INTO 
									survey 
								SET 
									survey_id=".$row_sur['survey_id'].",
									sites_site_id=$dest_siteid,
									survey_title='".addslashes(stripslashes($row_sur['title']))."',
									survey_question='".addslashes(stripslashes($row_sur['question']))."',
									survey_status=".$row_sur['status'].",
									survey_displayresults='".$display_res."',
									survey_showinall=1,
									survey_hide=0";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['sur_cnt']++;
				// Get the options for the current survey
				$sql_opt = "SELECT option_id,option_text,ordering 
								FROM 
									survey_options 
								WHERE 
									survey_id=".$row_sur['survey_id']." 
								ORDER BY 
									ordering";
				$ret_opt = mysql_db_query($src_db,$sql_opt,$src_link) or die($sql_opt.'-'.mysql_error());
				if (mysql_num_rows($ret_opt))
				{
					while ($row_opt = mysql_fetch_array($ret_opt))
					{
						// Importing the survey options to destination db
						$sql_insert = "INSERT INTO 
											survey_option 
										SET 
											option_id=".$row_opt['option_id'].",
											survey_id=".$row_sur['survey_id'].",
											option_text='".addslashes(strip_tags(stripslashes($row_opt['option_text'])))."',
											option_order=".$row_opt['ordering'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						
					}
				}
				
				// get the result details for survey from source db
				$sql_res = "SELECT session_id,option_id 
								FROM 
									survey_results 
								WHERE 
									survey_id=".$row_sur['survey_id'];
				$ret_res = mysql_db_query($src_db,$sql_res,$src_link) or die($sql_res.'-'.mysql_error());
				if (mysql_num_rows($ret_res))
				{
					while ($row_res = mysql_fetch_array($ret_res))
					{
						// Importing the result to destination db
						$sql_insert = "INSERT INTO 
											survey_results 
										SET 
											survey_id = ".$row_sur['survey_id'].",
											session_id='".$row_res['session_id']."',
											survey_option_option_id=".$row_res['option_id'];
						mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
						$ret_arr['surres_cnt']++;
					}
				}
			}
		}
		return $ret_arr;
	}
	
	/* Function to import console users */ 
	function Import_ConsoleUsers()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link;
		// Get the list of users from source db
		$ret_arr	= array
							(
								'usr_cnt'=>0	
							);
		$sql_usr = "SELECT user_id,name,client_id,site_id,email,pwd,type 
						FROM 
							users 
						WHERE 
							site_id=$src_siteid";
		$ret_usr = mysql_db_query($src_db,$sql_usr,$src_link) or die($sql_usr.'-'.mysql_error());
		if (mysql_num_rows($ret_usr))
		{
			while ($row_usr = mysql_fetch_array($ret_usr))
			{
				$pass = base64_encode($row_usr['pwd']);
				$sql_insert = "INSERT INTO 
									sites_users_7584 
								SET 
									sites_site_id=$dest_siteid,
									shop_id=0,
									user_title='Mr',
									user_fname='".addslashes(strip_tags(stripslashes($row_usr['name'])))."',
									user_lname='',
									user_address='',
									user_phone='',
									user_mobile='',
									user_email_9568='".addslashes(strip_tags(stripslashes($row_usr['email'])))."',
									user_pwd_5124='".$pass."',
									user_type='".$row_usr['type']."',
									user_active=1";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['usr_cnt']++;
			}
		}
		return $ret_arr;
	}
		
	/* Import Images in gallery */
	function Import_Images()
	{
		global $src_db,$dest_db,$src_siteid,$dest_siteid,$src_link,$dest_link,$cur_theme_id,$src_image_path,$dest_image_path,$Img_Resize,$src_domain,$dest_domain;
		$ret_arr	= array
							(
								'imgdir_cnt'=>0,
								'img_cnt'=>0,
								'prodmap_cnt'=>0,
								'catmap_cnt'=>0,
								'shopmap_cnt'=>0
							);
		// get the list of image directories from the source db
		$sql_imgdir = "SELECT directory_id,parent_id,name 
							FROM 
								image_dirs 
							WHERE 
								site_id=$src_siteid";
		$ret_imgdir = mysql_db_query($src_db,$sql_imgdir,$src_link) or die($sql_imgdir.'-'.mysql_error());
		if (mysql_num_rows($ret_imgdir))
		{
			while ($row_imgdir = mysql_fetch_array($ret_imgdir))
			{
				// Importing the image directories to the destination db
				$sql_insert = "INSERT INTO 
									images_directory 
								SET 
									directory_id=".$row_imgdir['directory_id'].",
									parent_id=".$row_imgdir['parent_id'].",
									sites_site_id=$dest_siteid,
									directory_name='".addslashes(strip_tags(stripslashes($row_imgdir['name'])))."'";
				mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
				$ret_arr['imgdir_cnt']++;
			}
		}
		
		// Get the list of image entries from source db
		$sql_img = "SELECT image_id,image_name,bigimage_path,thumb_path,pg_img_path,product_id,site_id,page_id,
						category_id,directory_id,shop_id,image_order,combo_id  
					FROM 
						images 
					WHERE 
						site_id=$src_siteid";
		$ret_img = mysql_db_query($src_db,$sql_img,$src_link) or die($sql_img.'-'.mysql_error());
		if (mysql_num_rows($ret_img))
		{
			// Get the details related to images from the themes table
			$sql_theme = "SELECT thumbimage_geometry,bigimage_geometry,categoryimage_geometry,
									categorythumbimage_geometry,iconimage_geometry 
							FROM 
								themes 
							WHERE 
								theme_id = $cur_theme_id 
							LIMIT 
								1";
			$ret_theme = mysql_db_query($dest_db,$sql_theme,$dest_link) or die($sql_theme.'-'.mysql_error);
			if(mysql_num_rows($ret_theme))
			{
				$row_theme = mysql_fetch_array($ret_theme);
				
				$geometry["thumb"]		= $row_theme['thumbimage_geometry'];
				$geometry["big"]			= $row_theme['bigimage_geometry'];
				$geometry["cat"]			= $row_theme['categoryimage_geometry'];
				$geometry["catthumb"]	= $row_theme['categorythumbimage_geometry'];
				$geometry["icon"]			= $row_theme['iconimage_geometry'];
				
				// Create the required folder if they does not exists for destination site
				if(!file_exists("$dest_image_path/big")) mkdir("$dest_image_path/big", 0777);
				if(!file_exists("$dest_image_path/thumb")) mkdir("$dest_image_path/thumb", 0777);
				if(!file_exists("$dest_image_path/category")) mkdir("$dest_image_path/category", 0777);
				if(!file_exists("$dest_image_path/category_thumb")) mkdir("$dest_image_path/category_thumb", 0777);
				if(!file_exists("$dest_image_path/extralarge")) mkdir("$dest_image_path/extralarge", 0777);
				if(!file_exists("$dest_image_path/gallerythumb")) mkdir("$dest_image_path/gallerythumb", 0777);
				if(!file_exists("$dest_image_path/icon")) mkdir("$dest_image_path/icon", 0777);
				
				// Iterating through the image record set
				while ($row_img = mysql_fetch_array($ret_img))
				{
					if (trim($row_img['bigimage_path'])!='')
					{
						// Taking the big image from the source site and resize it accordingly for other images
						$big_img_name 	= explode('/',$row_img['bigimage_path']);
						$src_img 		= $row_img['bigimage_path'];
						$src_img_check	= $src_image_path."/".$row_img['bigimage_path'];
						//$dest_img= $dest_image_path.'/'.$row_img['bigimage_path'];
						// check whether this file exists are is not a directory
						if (file_exists($src_img_check))
						{
							if(!is_dir($src_img_check))
							{
								$img_det = getimagesize($src_img_check);
								$img_type = $img_det['mime'];
								$bigimage_path = $catimage_path = $icon_path = $extralarge_path = $cathumbimage_path = '';
								//echo "Src: ".$src_img."<br>Dest: ".$dest_image_path.'/xxxxxx/'.$big_img_name[1]."<br><br>";	
								
								$bigimage_path  	= resize_image($src_img,'big/'.$big_img_name[1], $geometry["big"],$img_type,$Img_Resize);
								$catimage_path 		= resize_image($src_img,'category/'.$big_img_name[1], $geometry["cat"], $img_type,$Img_Resize);
								$icon_path 			= resize_image($src_img,'icon/'.$big_img_name[1], $geometry["icon"], $img_type,$Img_Resize);
								$extralarge_path	= resize_image($src_img,'extralarge/'.$big_img_name[1], '', $img_type,2);// no resize required
								$cathumbimage_path 	= resize_image($src_img,'category_thumb/'.$big_img_name[1], $geometry["catthumb"], $img_type,$Img_Resize);
								$thumb_path 		= resize_image($src_img,'thumb/'.$big_img_name[1], $geometry["thumb"],$img_type,$Img_Resize);
								$gallery_thumb_path	= resize_image($src_img,'gallerythumb/'.$big_img_name[1], '90>', $img_type,$Img_Resize);
								
								if($bigimage_path!='') // is image is resized and copied to destination section
								{
									$atleastone = 1;
									// Inserting the file details to the images table
									$sql_insert = "INSERT INTO 
														images
													SET 
														image_id=".$row_img['image_id'].",
														images_directory_directory_id=".$row_img['directory_id'].",
														sites_site_id=$dest_siteid,
														image_title='".addslashes(stripslashes($row_img['image_name']))."',
														image_bigpath='".$bigimage_path."',
														image_thumbpath='".$thumb_path."',
														image_bigcategorypath='".$catimage_path."',
														image_thumbcategorypath='".$cathumbimage_path."',
														image_extralargepath='".$extralarge_path."',
														image_gallerythumbpath='".$gallery_thumb_path."',
														image_iconpath='".$icon_path."'";
									mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
									$ret_arr['img_cnt']++;
									// Check whether this image is mapped to any of the products
									if($row_img['product_id']!=0)
									{
										// Check whether this product still exists in destination db
										$sql_prod = "SELECT product_id 
														FROM 
															products 
														WHERE
															product_id=".$row_img['product_id']." 
															AND sites_site_id=$dest_siteid 
														LIMIT 
															1";
										$ret_prod = mysql_db_query($dest_db,$sql_prod,$dest_link) or die($sql_prod.'-'.mysql_error());
										if(mysql_num_rows($ret_prod))
										{
											// case if product exists in destination db. then insert the product and image mapping
											$sql_insert = "INSERT INTO 
																images_product 
															SET 
																products_product_id=".$row_img['product_id'].",
																images_image_id=".$row_img['image_id'].",
																image_title='',
																image_order=0";
											mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert.'-'.mysql_error());
											$ret_arr['prodmap_cnt']++;
										}
									}
									
									// Check whether image is mapped with any of the category
									if($row_img['category_id']!=0)
									{
										// Check whether this category still exists in destination db
										$sql_cat = "SELECT category_id 
														FROM 
															product_categories  
														WHERE
															category_id=".$row_img['category_id']." 
															AND sites_site_id=$dest_siteid 
														LIMIT 
															1";
										$ret_cat = mysql_db_query($dest_db,$sql_cat,$dest_link) or die($sql_cat.'-'.mysql_error());
										if(mysql_num_rows($ret_cat))
										{
											// case if category exists in destination db. then insert the category and image mapping
											$sql_insert = "INSERT INTO 
																images_product_category 
															SET 
																product_categories_category_id=".$row_img['category_id'].",
																images_image_id=".$row_img['image_id'].",
																image_title='',
																image_order=0";
											mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert);
											$ret_arr['catmap_cnt']++;
										}		
									}
									// Check whether image is mapped with any of the shops
									if($row_img['shop_id']!=0)
									{
										// Check whether this shop still exists in destination db
										$sql_shop = "SELECT shopbrand_id 
														FROM 
															product_shopbybrand 
														WHERE
															shopbrand_id=".$row_img['shop_id']." 
															AND sites_site_id=$dest_siteid 
														LIMIT 
															1";
										$ret_shop = mysql_db_query($dest_db,$sql_shop,$dest_link) or die($sql_shop.'-'.mysql_error());
										if(mysql_num_rows($ret_shop))
										{
											// case if category exists in destination db. then insert the category and image mapping
											$sql_insert = "INSERT INTO 
																	images_shopbybrand  
																SET 
																	product_shopbybrand_shopbrand_id=".$row_img['shop_id'].",
																	images_image_id=".$row_img['image_id'].",
																	image_title='',
																	image_order=0";
											mysql_db_query($dest_db,$sql_insert,$dest_link) or die($sql_insert);
											$ret_arr['shopmap_cnt']++;
										}		
									}
								}
							}
						}
					}	
				}
			}
		}
		return $ret_arr;
	}
	
	
/*Function to resize and copy the uploaded files to required location*/
function resize_image($old, $new, $geometry, $exten,$resize_me = 1,$overwrite=false)
{
	global $src_image_path,$dest_image_path,$copy_only;
	
	$convert_path = CONVERT_PATH;
	$base = substr($new, 0, strrpos($new, "."));
	if($exten == "image/gif")
	{
		$new = "$base.gif";
	}
	else
	{
		$new = "$base.jpg";
	}
	$n = 0;
	//echo '<br>'.$old.' - '.$new;
	//echo "<br> Resize ".$resize_me;
	$sr_file	= $src_image_path.'/'.$old;
	$dest_file	= $dest_image_path.'/'.$new;
	if ($resize_me==1)
	{
		// this is the live server path
		//$command = "/usr/local/bin/convert \"$old\" -geometry \"$geometry\" -interlace Line \"$dest_image_path/$new\" 2>&1";
		
		// this is the local server path
		//$command = $convert_path."/convert \".$sr_file."/".$old\" -geometry \"$geometry\" -interlace Line \".$dest_image_path."/".$new\" 2>&1";
		$command = $convert_path."/convert \"$sr_file\" -geometry \"$geometry\" -interlace Line \"$dest_file\" 2>&1";

		$p = popen($command, "r");
		$error = "";
		while(!feof($p))
		{
			$s = fgets($p, 1024);
			$error .= $s;
		}
		$res = pclose($p);

		if($res == 0) 
			return $new;
		else 
		{
			return FALSE;
		}
	}
	else
	{
		$res 		= copy($sr_file,$dest_file);
		if ($res)
			return $new;
		else
		{
			return FALSE;
		}

	}
}
?>