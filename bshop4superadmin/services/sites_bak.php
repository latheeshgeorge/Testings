<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/sites/list_sites.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/sites/add_site.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['site_id']))
	{
		include("includes/sites/edit_site.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/sites/list_sites.php");
	}	
	
} else if($_REQUEST['fpurpose'] == 'user') {
	include("includes/sites/user_site.php");
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Addsite_submit'])
	{
		$alert = '';
		//#Server side validation
		$fieldRequired = array($_REQUEST['site_title'],$_REQUEST['site_domain'],$_REQUEST['site_email'],$_REQUEST['client_id'],$_REQUEST['level_id'],$_REQUEST['site_type'],$_REQUEST['site_status']); //$_REQUEST['theme_id'],
		$fieldDescription = array('Site title','Domain name','Site Admin Email','Client','Console Level','Site Type','Status'); //'Theme',
		$fieldEmail = array($_REQUEST['site_email']);
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['site_monthly_fee']);
		$fieldNumericDesc = array('Monthly Fee');
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		

		$_REQUEST['site_domain'] = str_replace('http://','',$_REQUEST['site_domain']);//#Removing http:// from domain name if entered
		$_REQUEST['site_domain'] = str_replace('/','',$_REQUEST['site_domain']);//#Removing / from domain name if entered
		$_REQUEST['site_domain'] = str_replace(' ','',$_REQUEST['site_domain']);//#Removing space from domain name if entered
		$_REQUEST['site_domain'] = trim($_REQUEST['site_domain']);//#Removing / from domain name if entered
		//#Validating the domain name entered...whether it exists or not.
		$sql_domain_check = $db->value_exists(array('site_domain' => $_REQUEST['site_domain']),'sites');
		$alert = ($sql_domain_check)?'Domain name exists':$alert;
		
		
				
		if(!$alert) {
			$insert_array = array();
			//##########################################################################################################
			//Inserting into sites table
			//##########################################################################################################
			$insert_array['console_levels_level_id']		= $_REQUEST['level_id'];
			$insert_array['clients_client_id']					= $_REQUEST['client_id'];
			$insert_array['site_title']							= add_slash($_REQUEST['site_title']);
			$insert_array['site_domain']						= $_REQUEST['site_domain'];
			$insert_array['site_date_bought']				= add_slash($_REQUEST['site_date_bought']);
			$insert_array['site_email']							= add_slash($_REQUEST['site_email']);
			$insert_array['site_status']						= add_slash($_REQUEST['site_status']);
			$insert_array['site_monthly_fee']				= add_slash($_REQUEST['site_monthly_fee']);
			$insert_array['site_type']							= add_slash($_REQUEST['site_type']);
			$insert_array['themes_theme_id']				= add_slash($_REQUEST['theme_id']);
			$insert_array['site_xml_filename']				= add_slash($_REQUEST['site_xml_filename']);
			$insert_array['site_xml_key']						= add_slash($_REQUEST['site_xml_key']);
			$insert_array['is_managed_seo']				= ($_REQUEST['is_managed_seo'])?1:0;
			$insert_array['in_web_clinic']						= ($_REQUEST['in_web_clinic'])?1:0;
			$insert_array['site_intestmod']					= ($_REQUEST['site_intestmod'])?1:0;
			$insert_array['advanced_seo']					= ($_REQUEST['advanced_seo'])?'Y':'N';
			
			
			$insert_array['is_meta_verificationcode']	= ($_REQUEST['is_meta_verificationcode'])?1:0;
			if($_REQUEST['is_meta_verificationcode'])
			{
				$insert_array['meta_verificationcode']	= addslashes($_REQUEST['meta_verificationcode']); 
			}
			$insert_array['is_google_urchinwebtracker_code']	= ($_REQUEST['is_google_urchinwebtracker_code'])?1:0;
			if($_REQUEST['is_google_urchinwebtracker_code'])
			{
				$insert_array['google_webtracker_urchin_code']	= addslashes($_REQUEST['google_webtracker_urchin_code']); 
				}
			$insert_array['is_google_webtracker_code']	= ($_REQUEST['is_google_webtracker_code'])?1:0; 
			if($_REQUEST['is_google_webtracker_code'])
			{
				$insert_array['google_webtracker_code']	= addslashes($_REQUEST['google_webtracker_code']); 
			}
			$insert_array['is_google_adword_checkout']	= ($_REQUEST['is_google_adword_checkout'])?1:0; 
			if($_REQUEST['is_google_adword_checkout'])
			{
				$insert_array['google_adword_conversion_id']		= addslashes($_REQUEST['google_adword_conversion_id']); 
				$insert_array['google_adword_conversion_language']	= addslashes($_REQUEST['google_adword_conversion_language']); 
				$insert_array['google_adword_conversion_format']	= addslashes($_REQUEST['google_adword_conversion_format']); 
				$insert_array['google_adword_conversion_color']		= addslashes($_REQUEST['google_adword_conversion_color']); 
				$insert_array['google_adword_conversion_label']		= addslashes($_REQUEST['google_adword_conversion_label']); 
			}
			
			
			$db->insert_from_array($insert_array, 'sites');
			$insert_siteid = $db->insert_id();//#Getting generated site id
			
			//##########################################################################################################
			//#Creating the default page group with assigned position = top
			//##########################################################################################################
			$insert_static_group_array = array();
			$insert_static_group_array['sites_site_id'] = $insert_siteid;
			$insert_static_group_array['group_name'] = 'Top';
			
			//##########################################################################################################
			//#theme static page position
			//##########################################################################################################
			$sql = "SELECT page_positions FROM themes WHERE theme_id=".$_REQUEST['theme_id'];
			$res = $db->query($sql);
			$positions = $db->fetch_array($res);
			$position_array = explode(",",$positions['page_positions']);
			//$insert_static_group_array['group_position'] 	= $position_array[0];
			$insert_static_group_array['group_showinall'] 	= 1;
			$insert_static_group_array['group_hide'] 		= 0;
			$insert_static_group_array['group_order'] 		= 0;
			$insert_static_group_array['group_hidename'] 	= 1;
			$db->insert_from_array($insert_static_group_array, 'static_pagegroup');
			$insert_group_id = $db->insert_id();//#Getting generated Page Group id
			
			//##########################################################################################################
			//#Inserting 4 Static pages
			//##########################################################################################################
			$static_page_array = array('Home','About us','FAQ','Contact us');
			foreach($static_page_array as $k => $page_name)
			{
				$insert_static_array = array();
				$insert_static_array['sites_site_id']  	= $insert_siteid;
				$insert_static_array['title']  			= $page_name;
				$insert_static_array['content']  		= "$page_name page content comes here";
				$insert_static_array['hide']  			= 0;
				$insert_static_array['pname']  			= $page_name;
				$db->insert_from_array($insert_static_array, 'static_pages');
				//#Inserted page id
				$insert_pageid = $db->insert_id();
				
				$insert_page_position = array();
				$insert_page_position['static_pages_page_id'] 		= $insert_pageid;
				$insert_page_position['static_pagegroup_group_id'] 	= $insert_group_id;
				$insert_page_position['static_pages_order'] 		= $k;
				$db->insert_from_array($insert_page_position, 'static_pagegroup_static_page_map');
				
			}
			//##########################################################################################################
			//Create the images directories
			//##########################################################################################################
			umask(0);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain'], 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain'], 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/big", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/big", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/thumb", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/thumb", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/otherfiles", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/otherfiles", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/otherfiles/uploads", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/otherfiles/uploads", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/css", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/css", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/site_images", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/site_images", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/scripts", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/scripts", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/adverts", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/adverts", 0777);
			mkdir(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/attachments", 0777);
			chmod(IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']."/attachments", 0777);
	
			//Update sites list in confs dir
			//writeSiteList();
			
			//##############################
			//#Create administrator login for the new site
			//##############################
			$rstClientQ=$db->query("Select client_fname,client_email from clients where client_id=".$_REQUEST['client_id']);
			$rstClientR=$db->fetch_array($rstClientQ);
			$pwd=$rstClientR['client_fname'] . $_REQUEST['client_id'] . $insert_siteid;
			
			$userdetails_array = array();
			$userdetails_array['sites_site_id'] 	= $insert_siteid;
			$userdetails_array['user_fname'] 		= $rstClientR['client_fname'];
			$userdetails_array['user_email_9568'] 	= $rstClientR['client_email'];
			$userdetails_array['user_pwd_5124'] 	= base64_encode($pwd);
			$userdetails_array['user_type'] 		= 'sa';
			$userdetails_array['user_active'] 		= 1;
			$userdetails_array['default_user'] 		= 1;
			$db->insert_from_array($userdetails_array, 'sites_users_7584');
		
			//##########################################################################################################
			// Email Templates
			//##########################################################################################################
			$sql_temp = "SELECT * FROM common_emailtemplates";
			$ret_temp = $db->query($sql_temp);
			if ($db->num_rows($ret_temp))
			{
				while ($row_temp = $db->fetch_array($ret_temp))
				{
					$insert_array = array();
					$insert_array['sites_site_id']				= $insert_siteid;
					$insert_array['lettertemplate_letter_type']	= add_slash($row_temp['template_lettertype']);
					$insert_array['lettertemplate_title']		= add_slash($row_temp['template_lettertitle']);
					$insert_array['lettertemplate_subject']		= add_slash($row_temp['template_lettersubject']);
					$insert_array['lettertemplate_contents']	= add_slash($row_temp['template_content']);
					$db->insert_from_array($insert_array,'general_settings_site_letter_templates');
				}
			}	
			//########################################################################
			// Section to handle the limits for products, categories and static pages
			//########################################################################
			/*$site_product	= $site_category = $site_static = 0;	
			$sql_featurelimit = "SELECT b.services_limit,a.feature_modulename FROM features a,
								console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
								a.feature_id=b.features_feature_id AND a.feature_modulename 
								IN ('mod_products','mod_categories','mod_static')";
			$ret_featurelimit = $db->query($sql_featurelimit);
			if ($db->num_rows($ret_featurelimit))
			{
				while ($row_featurelimit = $db->fetch_array($ret_featurelimit))
				{
					if($row_featurelimit['feature_modulename']=='mod_products')
						$site_product = $row_featurelimit['services_limit'];
					elseif($row_featurelimit['feature_modulename']=='mod_categories')
						$site_category = $row_featurelimit['services_limit'];
					elseif($row_featurelimit['feature_modulename']=='mod_static')
						$site_static = $row_featurelimit['services_limit'];	
				}
			}			
			$update_array							= array();
			$update_array['site_maxproducts']		= $site_product;
			$update_array['site_maxcategories']		= $site_category;
			$update_array['site_maxstaticpages']	= $site_static;
			$db->update_from_array($update_array,'sites',array('site_id'=>$insert_siteid)); */
			
			//##########################################################################################################
			// Site Menu
			//##########################################################################################################
			$layout_arr		= array();
			$sql_layouts 	= "SELECT layout_id,layout_code FROM themes_layouts WHERE themes_theme_id =".$_REQUEST['theme_id'];
			$ret_layouts 	= $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					$layid 				= $row_layouts['layout_id'];
					$laycode			= $row_layouts['layout_code'];
					$layout_arr[$layid] = $laycode;
				}
			}			
			// Find the features associated with the current console level to be inserted to site_menu
			$sql_level = "SELECT a.feature_id,a.feature_title, a.feature_modulename FROM features a,
						console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
						a.feature_id=b.features_feature_id AND a.feature_insite=1 ORDER BY a.feature_ordering";
			$ret_feat = $db->query($sql_level);
			while($row_feat = $db->fetch_array($ret_feat)) 
			{
				$feat_id 	= $row_feat['feature_id'];
				$feat_title	= $row_feat['feature_title'];
				$module		= $row_feat['feature_modulename'];
				
				//Check whether feature already exists for the site
				$sql_check = "SELECT menu_id FROM site_menu WHERE sites_site_id=$insert_siteid AND features_feature_id=$feat_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{	
					$insert_array 							= array();
					$insert_array['sites_site_id']			= $insert_siteid;
					$insert_array['menu_title']				= $feat_title;
					$insert_array['features_feature_id']	= $feat_id;
					$db->insert_from_array($insert_array,'site_menu');
				}
				
				//########################################################################
				// Default positions details to display_settings table Based on layouts
				//########################################################################
				
				if (count($layout_arr))
				{
					foreach($layout_arr as $k=>$v)
					{
						$layid 		= $k;
						$laycode	= $v;
						
						// Check whether default position is set for current feature in current layout for this theme
						$sql_default = "SELECT def_position,def_order FROM themes_layouts_features_default_positions WHERE 
										themes_layouts_layout_id = $layid AND features_feature_id=$feat_id";
						$ret_default = $db->query($sql_default);	
						if($db->num_rows($ret_default))
						{
							$row_default = $db->fetch_array($ret_default);
							$insert_array								= array();
							$insert_array['sites_site_id']				= $insert_siteid;
							$insert_array['features_feature_id']		= $feat_id;
							$insert_array['display_position']			= $row_default['def_position'];
							$insert_array['themes_layouts_layout_id']	= $layid;
							$insert_array['layout_code']				= $laycode;
							$insert_array['display_title']				= $feat_title;
							$insert_array['display_order']				= $row_default['def_order'];
							$db->insert_from_array($insert_array,'display_settings');
						}	
					}
				}
			}
			
			
			//##########################################################################################################
			// Mod Menu
			//##########################################################################################################
			// Find the features associated with the current console level to be inserted to mod_menu
			$sql_level = "SELECT a.feature_id,a.feature_title, a.feature_modulename,a.feature_consoleurl,a.feature_ordering,a.services_service_id,a.parent_id FROM features a,
						console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
						a.feature_id=b.features_feature_id AND a.feature_inconsole=1  
						ORDER BY a.feature_ordering";
			$ret_feat = $db->query($sql_level);
			while($row_feat = $db->fetch_array($ret_feat))
			{
				$feat_id 			= $row_feat['feature_id'];
				$feat_title			= $row_feat['feature_title'];
				$module				= $row_feat['feature_modulename'];
				$url				= $row_feat['feature_consoleurl'];
				$ordering			= $row_feat['feature_ordering'];
				$serviceid			= $row_feat['services_service_id'];
				$parentid			= $row_feat['parent_id'];
				//Check whether feature already exists for the site
				$sql_check = "SELECT menu_id FROM mod_menu WHERE sites_site_id=$insert_siteid AND features_feature_id=$feat_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{	
					$insert_array							= array();
					$insert_array['sites_site_id']			= $insert_siteid;
					$insert_array['services_service_id']	= $serviceid;
					$insert_array['features_feature_id']	= $feat_id;
					$insert_array['parent_id']				= $parentid;
					$insert_array['menu_title']				= $feat_title;
					$insert_array['menu_url']				= $url;
					$insert_array['menu_order']				= $ordering;
					$db->insert_from_array($insert_array,'mod_menu');
				}
			}
			//##########################################################################################################
			//#currencies
			//##########################################################################################################
			$sql = "SELECT * FROM common_currency";
			$res = $db->query($sql);
			$currency_default = 1;
			while($row = $db->fetch_array($res))
			{
				$insert_array 					= array();
				$insert_array['sites_site_id']	= 	$insert_siteid;
				$insert_array['curr_name']		= 	$row['curr_name'];
				$insert_array['curr_sign']		= 	$row['curr_sign'];
				$insert_array['curr_sign_char']	= 	$row['curr_sign_char'];
				$insert_array['curr_code']		= 	$row['curr_code'];
				$insert_array['curr_rate']		= 	$row['curr_rate'];
				$insert_array['curr_default']	= 	$currency_default;
				$insert_array['curr_numeric_code']		= 	$row['curr_numeric_code'];
				$db->insert_from_array($insert_array,'general_settings_site_currency');				
				$currency_default = 0;
			}
			//##########################################################################################################
			//#country
			//##########################################################################################################
			$sql = "SELECT country_name,country_numeric_code FROM common_country";
			$res = $db->query($sql);
			while($row = $db->fetch_array($res))
			{
				$country_name 									= stripslashes($row['country_name']);
				$insert_array										= array();
				$insert_array['sites_site_id']					= $insert_siteid;
				$insert_array['country_name']				= addslashes($country_name);
				$insert_array['country_numeric_code']	= addslashes($row['country_numeric_code']);
				$db->insert_from_array($insert_array,'general_settings_site_country');
			}
			//##########################################################################################################
			//Payment types
			//##########################################################################################################
			$sql = "SELECT paytype_id,paytype_name FROM payment_types ORDER BY paytype_id";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				while ($row = $db->fetch_array($ret))
				{
					$payid	 													= $row['paytype_id'];
					$payname 													= addslashes(stripslashes($row['paytype_name']));
					$insert_array												= array();
					$insert_array['paytype_id']							= $payid;
					$insert_array['sites_site_id']							= $insert_siteid;
					$insert_array['paytype_forsites_active']			= 1;
					$insert_array['paytype_forsites_userdisabled']	= 0;
					$db->insert_from_array($insert_array,'payment_types_forsites');
				}
			}
			
			//##########################################################################################################
			// Inserting common values to Customer Company Types Table
			//##########################################################################################################
			$sql = "SELECT comptype_id,comptype_name,comptype_order FROM common_customer_company_types ORDER BY comptype_order";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				while ($row = $db->fetch_array($ret))
				{
					$comptype_id	 									= $row['comptype_id'];
					$comptype_name 								= addslashes(stripslashes($row['comptype_name']));
					$insert_array										= array();
					$insert_array['sites_site_id']					= $insert_siteid;
					$insert_array['comptype_name']			= $comptype_name;
					$insert_array['comptype_order']			= $row['comptype_order'];
					$db->insert_from_array($insert_array,'general_settings_sites_customer_company_types');
				}
			}
			/*#General Settings
			$sql = "SELECT * FROM general_labels WHERE site_id=1";
			$res = $db->query($sql);
			$insert = "INSERT INTO general_labels SET site_id=$insert_siteid ";
			$start_cnt = 2;
			while($row = $db->fetch_array($res)){
			$i=1;
				foreach ($row as $k=>$v)
				{
					if($i%2==0 && $i>4)
						$insert .= ", $k='".addslashes($v)."'";
					$i++;	
				}	
				$db->query($insert);
			}
			*/
			
			// ############################################################################################################
			// Inserting to general_settings_sites_common for current site by picking the value set for site with id as 1
			// ############################################################################################################
			$sql_common = "SELECT * 
								FROM 
									general_settings_sites_common 
								WHERE 
									sites_site_id=1";
			$ret_common = $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_assoc($ret_common);
				
				$insert_array											= array();
				$insert_array['sites_site_id']						= $insert_siteid;
				foreach ($row_common as $k=>$v)
				{
					if ($k!=='listing_id' and $k!=='sites_site_id')
						$insert_array[$k]									= addslashes(stripslashes($v));
				}	
				$db->insert_from_array($insert_array,'general_settings_sites_common');
			}
			// ############################################################################################################
			// Inserting to general_settings_sites_common_onoff for current site by picking the value set for site with id as 1
			// ############################################################################################################
			$sql_common = "SELECT * 
										FROM 
											general_settings_sites_common_onoff 
										WHERE 
											sites_site_id=1";
			$ret_common = $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_assoc($ret_common);
				
				$insert_array											= array();
				$insert_array['sites_site_id']							= $insert_siteid;
				foreach ($row_common as $k=>$v)
				{
					if ($k!=='listing_id' and $k!=='sites_site_id')
						$insert_array[$k]									= addslashes(stripslashes($v));
				}	
				$db->insert_from_array($insert_array,'general_settings_sites_common_onoff');
			}
			
			// ############################################################################################################
			// Inserting to general_settings_sites_listorders for current site by picking the value set for site with id as 1
			// ############################################################################################################
			$sql_common = "SELECT * 
								FROM 
									general_settings_sites_listorders 
								WHERE 
									sites_site_id=1";
			$ret_common = $db->query($sql_common);
			if ($db->num_rows($ret_common))
			{
				$row_common = $db->fetch_assoc($ret_common);
				
				$insert_array											= array();
				$insert_array['sites_site_id']							= $insert_siteid;
				foreach ($row_common as $k=>$v)
				{
					if ($k!=='listing_id' and $k!=='sites_site_id')
						$insert_array[$k]									= addslashes(stripslashes($v));
				}	
				$db->insert_from_array($insert_array,'general_settings_sites_listorders');
			}
			
			
			// ############################################################################################################
			// Inserting to general_settings_site_captions for current site by picking the value set for site with id as 1
			// ############################################################################################################
			$sql_caption = "SELECT * 
								FROM 
									general_settings_site_captions 
								WHERE 
									sites_site_id = 1";
			$ret_caption = $db->query($sql_caption);
			if ($db->num_rows($ret_caption))
			{
				while ($row_caption = $db->fetch_array($ret_caption))
				{
					$insert_array											= array();
					$insert_array['general_settings_section_section_id']	= $row_caption['general_settings_section_section_id'];
					$insert_array['sites_site_id']							= $insert_siteid;
					$insert_array['general_key']							= addslashes(stripslashes($row_caption['general_key']));
					$insert_array['general_text']							= addslashes(stripslashes($row_caption['general_text']));
					$db->insert_from_array($insert_array,'general_settings_site_captions');
				}
			}
			
			// ###################################################################################################################
			// Inserting to general_settings_site_checkoutfields for current site by picking the value from common_checkoutfields
			// ###################################################################################################################
			
			$sql_fields = "SELECT * 
								FROM 
									common_checkoutfields";
			$ret_fields = $db->query($sql_fields);
			if ($db->num_rows($ret_fields))
			{
				while ($row_fields = $db->fetch_array($ret_fields))
				{
					$insert_array						= array();
					$insert_array['field_key']			= addslashes(stripslashes($row_fields['field_key']));
					$insert_array['field_name']			= addslashes(stripslashes($row_fields['field_name']));
					$insert_array['field_req']			= addslashes(stripslashes($row_fields['field_req']));
					$insert_array['field_order']		= $row_fields['field_order'];
					$insert_array['sites_site_id']		= $insert_siteid;
					$insert_array['field_hidden']		= 0;
					$insert_array['field_type']			= $row_fields['field_type'];
					$insert_array['field_error_msg']	= addslashes(stripslashes($row_fields['field_error_msg']));
					$insert_array['field_orgname']		= addslashes(stripslashes($row_fields['field_orgname']));
					$db->insert_from_array($insert_array,'general_settings_site_checkoutfields');
				}
			}
			
			// ################################################################################################################
			// Inserting to general_settings_site_pricedisplay for current site by picking the value set for site with id as 1
			// ################################################################################################################
			$sql_price = "SELECT * 
								FROM 
									general_settings_site_pricedisplay 
								WHERE 
									sites_site_id = 1";
			$ret_price = $db->query($sql_price);
			if ($db->num_rows($ret_price))
			{
				$row_price = $db->fetch_assoc($ret_price);
				$insert_array								= array();
				$insert_array['sites_site_id']				= $insert_siteid;
				foreach($row_price as $k=>$v)
				{	
					if ($k!=='price_id' and $k!=='sites_site_id')
						$insert_array[$k]					= addslashes(stripslashes($v));
				}	
				$db->insert_from_array($insert_array,'general_settings_site_pricedisplay');
			}
			
			//##############################
			//#Success Msg		
			//##############################
			?>
		
			<?php
			$alert = 'Successfully Added Site '.$_REQUEST['domain'];
			$alert .= "<br>New user has been created for the site and details are as follows: - <br>";
			$alert .= "<strong>Username:</strong> ".$rstClientR['client_email']."<br>";
			$alert .= "<strong>Password:</strong> ". $pwd;
			echo $alert;
			?>
			<center>
			<br /><br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&status=<?=$_REQUEST['status']?>&theme=<?=$_REQUEST['theme']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=edit&site_id=<?=$insert_siteid?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Site</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=add&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add a New Site</a></center>
		<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/add_site.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Updatesite_submit'])
	{
		$alert = '';
		#Server side validation
		$fieldRequired = array($_REQUEST['site_title'],$_REQUEST['site_domain'],$_REQUEST['site_email'],$_REQUEST['client_id'],$_REQUEST['theme_id'],$_REQUEST['level_id'],$_REQUEST['site_type'],$_REQUEST['site_status']);
		$fieldDescription = array('Site title','Domain name','Site Admin Email','Client','Theme','Console Level','Site Type','Status');
		$fieldEmail = array($_REQUEST['site_email']);
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['site_monthly_fee']);
		$fieldNumericDesc = array('Monthly Fee');
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$_REQUEST['site_domain'] = str_replace('http://','',$_REQUEST['site_domain']);//#Removing http:// from domain name if entered
		$_REQUEST['site_domain'] = str_replace('/','',$_REQUEST['site_domain']);//#Removing / from domain name if entered
		$_REQUEST['site_domain'] = str_replace(' ','',$_REQUEST['site_domain']);//#Removing space from domain name if entered
		$_REQUEST['site_domain'] = trim($_REQUEST['site_domain']);//#Removing space from domain name if entered
		$sql_check = "SELECT count(*) as cnt FROM sites WHERE site_domain='".$_REQUEST['site_domain']."' AND site_id<>".$_REQUEST['site_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Domain name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['console_levels_level_id']		= $_REQUEST['level_id'];
			$update_array['clients_client_id']				= $_REQUEST['client_id'];
			$update_array['site_title']						= add_slash($_REQUEST['site_title']);
			$update_array['site_domain']					= $_REQUEST['site_domain'];
			$update_array['site_date_bought']				= add_slash($_REQUEST['site_date_bought']);
			$update_array['site_email']						= add_slash($_REQUEST['site_email']);
			$update_array['site_status']					= add_slash($_REQUEST['site_status']);
			$update_array['site_monthly_fee']				= add_slash($_REQUEST['site_monthly_fee']);
			$update_array['site_type']						= add_slash($_REQUEST['site_type']);
			$update_array['themes_theme_id']				= add_slash($_REQUEST['theme_id']);
			$update_array['site_xml_filename']				= add_slash($_REQUEST['site_xml_filename']);
			$update_array['site_xml_key']					= add_slash($_REQUEST['site_xml_key']);
			$update_array['is_managed_seo']					= ($_REQUEST['is_managed_seo'])?1:0;
			$update_array['in_web_clinic']					= ($_REQUEST['in_web_clinic'])?1:0;
			$update_array['site_intestmod']					= ($_REQUEST['site_intestmod'])?1:0;
			$update_array['advanced_seo']					= ($_REQUEST['advanced_seo'])?'Y':'N';
			
			$update_array['is_meta_verificationcode']	= ($_REQUEST['is_meta_verificationcode'])?1:0;
			if($_REQUEST['is_meta_verificationcode'])
			{
				$update_array['meta_verificationcode']	= addslashes($_REQUEST['meta_verificationcode']); 
			}
			$update_array['is_google_urchinwebtracker_code']	= ($_REQUEST['is_google_urchinwebtracker_code'])?1:0;
			if($_REQUEST['is_google_urchinwebtracker_code'])
			{
				$update_array['google_webtracker_urchin_code']	= addslashes($_REQUEST['google_webtracker_urchin_code']); 
			}
			$update_array['is_google_webtracker_code']	= ($_REQUEST['is_google_webtracker_code'])?1:0; 
			if($_REQUEST['is_google_webtracker_code'])
			{
				$update_array['google_webtracker_code']	= addslashes($_REQUEST['google_webtracker_code']); 
			}
			$update_array['is_google_adword_checkout']	= ($_REQUEST['is_google_adword_checkout'])?1:0; 
			if($_REQUEST['is_google_adword_checkout'])
			{
				$update_array['google_adword_conversion_id']		= addslashes($_REQUEST['google_adword_conversion_id']); 
				$update_array['google_adword_conversion_language']	= addslashes($_REQUEST['google_adword_conversion_language']); 
				$update_array['google_adword_conversion_format']	= addslashes($_REQUEST['google_adword_conversion_format']); 
				$update_array['google_adword_conversion_color']		= addslashes($_REQUEST['google_adword_conversion_color']); 
				$update_array['google_adword_conversion_label']		= addslashes($_REQUEST['google_adword_conversion_label']); 
			}

			
			$db->update_from_array($update_array, 'sites', 'site_id', $_REQUEST['site_id']);
			
			//##############################
			// Email Templates
			//##############################
			$sql_temp = "SELECT * FROM common_emailtemplates";
			$ret_temp = $db->query($sql_temp);
			if ($db->num_rows($ret_temp))
			{
				while ($row_temp = $db->fetch_array($ret_temp))
				{
					// check whether the current email template already exists for current site
					$sql_check = "SELECT count(*) FROM general_settings_site_letter_templates WHERE 
								sites_site_id=".$_REQUEST['site_id']." AND lettertemplate_letter_type='".$row_temp['template_lettertype']."'";
					$ret_check = $db->query($sql_check);
					list($chk_cnt) = $db->fetch_array($ret_check);
					if ($chk_cnt==0)
					{			
						$insert_array = array();
						$insert_array['sites_site_id']				= $_REQUEST['site_id'];
						$insert_array['lettertemplate_letter_type']	= add_slash($row_temp['template_lettertype']);
						$insert_array['lettertemplate_title']		= add_slash($row_temp['template_lettertitle']);
						$insert_array['lettertemplate_subject']		= add_slash($row_temp['template_lettersubject']);
						$insert_array['lettertemplate_contents']	= add_slash($row_temp['template_content']);
						$db->insert_from_array($insert_array,'general_settings_site_letter_templates');
					}	
				}
			}	
			
			//########################################################################
			// Section to handle the limits for products, categories and static pages
			//########################################################################
			/*$site_product	= $site_category = $site_static = 0;	
			$sql_featurelimit = "SELECT b.services_limit,a.feature_modulename FROM features a,
								console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
								a.feature_id=b.features_feature_id AND a.feature_modulename 
								IN ('mod_products','mod_categories','mod_static')";
			$ret_featurelimit = $db->query($sql_featurelimit);
			if ($db->num_rows($ret_featurelimit))
			{
				while ($row_featurelimit = $db->fetch_array($ret_featurelimit))
				{
					if($row_featurelimit['feature_modulename']=='mod_products')
						$site_product = $row_featurelimit['services_limit'];
					elseif($row_featurelimit['feature_modulename']=='mod_categories')
						$site_category = $row_featurelimit['services_limit'];
					elseif($row_featurelimit['feature_modulename']=='mod_static')
						$site_static = $row_featurelimit['services_limit'];	
				}
			}			
			$update_array							= array();
			$update_array['site_maxproducts']		= $site_product;
			$update_array['site_maxcategories']		= $site_category;
			$update_array['site_maxstaticpages']	= $site_static;
			$db->update_from_array($update_array,'sites',array('site_id'=>$_REQUEST['site_id'])); */
		
			//##############################
			// Site Menu
			//##############################
			// Find the features associated with the current console level to be inserted to site_menu
			$sql_level = "SELECT a.feature_id,a.feature_title, a.feature_modulename FROM features a,
						console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
						a.feature_id=b.features_feature_id AND a.feature_insite=1 ORDER BY a.feature_ordering";
			$ret_feat = $db->query($sql_level);
			while($row_feat = $db->fetch_array($ret_feat)) 
			{
				$feat_id 	= $row_feat['feature_id'];
				$feat_title	= $row_feat['feature_title'];
				$module		= $row_feat['feature_modulename'];
				
				//Check whether feature already exists for the site
				$sql_check = "SELECT menu_id FROM site_menu WHERE sites_site_id=".$_REQUEST['site_id']." AND features_feature_id=$feat_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{	
					$insert_array 							= array();
					$insert_array['sites_site_id']			= $_REQUEST['site_id'];
					$insert_array['menu_title']				= $feat_title;
					$insert_array['features_feature_id']	= $feat_id;
					$db->insert_from_array($insert_array,'site_menu');
				}
			}
			
			//##############################
			// Mod Menu
			//##############################
			// Find the features associated with the current console level to be inserted to mod_menu
			$sql_level = "SELECT a.feature_id,a.feature_title, a.feature_modulename,a.feature_consoleurl,a.feature_ordering,a.services_service_id,a.parent_id FROM features a,
						console_levels_details b WHERE b.console_levels_level_id=".$_REQUEST['level_id']." AND 
						a.feature_id=b.features_feature_id AND a.feature_inconsole='1'  
						ORDER BY a.feature_ordering";
			$ret_feat = $db->query($sql_level);
			while($row_feat = $db->fetch_array($ret_feat))
			{
				$feat_id 			= $row_feat['feature_id'];
				$feat_title			= $row_feat['feature_title'];
				$module				= $row_feat['feature_modulename'];
				$url				= $row_feat['feature_consoleurl'];
				$ordering			= $row_feat['feature_ordering'];
				$serviceid			= $row_feat['services_service_id'];
				$parentid			= $row_feat['parent_id'];
				//Check whether feature already exists for the site
				$sql_check = "SELECT menu_id FROM mod_menu WHERE sites_site_id=".$_REQUEST['site_id']." AND features_feature_id=$feat_id";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{	
					$insert_array							= array();
					$insert_array['sites_site_id']			= $_REQUEST['site_id'];
					$insert_array['services_service_id']	= $serviceid;
					$insert_array['features_feature_id']	= $feat_id;
					$insert_array['parent_id']				= $parentid;
					$insert_array['menu_title']				= $feat_title;
					$insert_array['menu_url']				= $url;
					$insert_array['menu_order']				= $ordering;
					$db->insert_from_array($insert_array,'mod_menu');
				}
			}
			
			//###############################
			//Payment types
			// Check whether any of the payment types are missing for the current site. If yes then add it
			//###############################
			$sql = "SELECT paytype_id,paytype_name FROM payment_types ORDER BY paytype_id";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				while ($row = $db->fetch_array($ret))
				{
					$payid	 										= $row['paytype_id'];
					$payname 										= $row['paytype_name'];
					//  Check whether this payment type exists for current site
					$sql_exists = "SELECT paytype_forsites_id FROM payment_types_forsites WHERE paytype_id=$payid AND 
									sites_site_id=".$_REQUEST['site_id'];
					$ret_exists	= $db->query($sql_exists);
					if ($db->num_rows($ret_exists)==0)
					{
						$insert_array									= array();
						$insert_array['paytype_id']						= $payid;
						$insert_array['sites_site_id']					= $_REQUEST['site_id'];
						$insert_array['paytype_forsites_active']		= 1;
						$insert_array['paytype_forsites_userdisabled']	= 0;
						$db->insert_from_array($insert_array,'payment_types_forsites');
					}	
				}
			}
			
			
			// ############################################################################################################
			// Inserting to general_settings_site_captions for current site by picking the value set for site with id as 1
			// ############################################################################################################
			$sql_caption = "SELECT * 
								FROM 
									general_settings_site_captions 
								WHERE 
									sites_site_id = 1";
			$ret_caption = $db->query($sql_caption);
			if ($db->num_rows($ret_caption))
			{
				while ($row_caption = $db->fetch_array($ret_caption))
				{
					// Check whether this key already exists in current section for current site
					$sql_check = "SELECT general_id 
										FROM 
											general_settings_site_captions 
										WHERE 
											sites_site_id=".$_REQUEST['site_id']." 
											AND general_settings_section_section_id=".$row_caption['general_settings_section_section_id']." 
											AND general_key='".$row_caption['general_key']."' 
										LIMIT 
											1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0) // case if does not exist. so insert it
					{
						$insert_array											= array();
						$insert_array['general_settings_section_section_id']	= $row_caption['general_settings_section_section_id'];
						$insert_array['sites_site_id']							= $_REQUEST['site_id'];
						$insert_array['general_key']							= addslashes(stripslashes($row_caption['general_key']));
						$insert_array['general_text']							= addslashes(stripslashes($row_caption['general_text']));
						$db->insert_from_array($insert_array,'general_settings_site_captions');
					}	
				}
			}
			
			// ###################################################################################################################
			// Inserting to general_settings_site_checkoutfields for current site by picking the value from common_checkoutfields
			// ###################################################################################################################
			
			$sql_fields = "SELECT * 
								FROM 
									common_checkoutfields";
			$ret_fields = $db->query($sql_fields);
			if ($db->num_rows($ret_fields))
			{
				while ($row_fields = $db->fetch_array($ret_fields))
				{
					// Check whether field_key already exists cor current site
					$sql_check = "SELECT field_det_id 
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id=".$_REQUEST['site_id']." 
											AND field_key='".$row_fields['field_key']."' 
										LIMIT 
											1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0) // case if field key does not exists for current site. so in sert it
					{
						$insert_array						= array();
						$insert_array['field_key']			= addslashes(stripslashes($row_fields['field_key']));
						$insert_array['field_name']			= addslashes(stripslashes($row_fields['field_name']));
						$insert_array['field_req']			= addslashes(stripslashes($row_fields['field_req']));
						$insert_array['field_order']		= $row_fields['field_order'];
						$insert_array['sites_site_id']		= $_REQUEST['site_id'];
						$insert_array['field_hidden']		= 0;
						$insert_array['field_type']			= $row_fields['field_type'];
						$insert_array['field_error_msg']	= addslashes(stripslashes($row_fields['field_error_msg']));
						$insert_array['field_orgname']		= addslashes(stripslashes($row_fields['field_orgname']));
						$db->insert_from_array($insert_array,'general_settings_site_checkoutfields');
					}	
				}
			}
			
			
			
			
			if($_REQUEST['domain'] != $_REQUEST['old_domain']) {

				rename(IMAGE_ROOT_PATH."/".$_REQUEST['old_domain'], IMAGE_ROOT_PATH."/".$_REQUEST['site_domain']);
			}
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=edit&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Site</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=add&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Add a New Site</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/edit_site.php");
		}
		
	}
	
}/* for listing and adding the payment methods*/
else if ($_REQUEST['fpurpose'] == 'list_pymt_methods')
{
	include("includes/sites/list_site_pymt_methods.php");
}
else if ($_REQUEST['fpurpose'] == 'add_paymethod') // for assigning the payment methods for the site
{
	if($_REQUEST['assign_payment_methods'] == 'Save Selected Payment Methods') {
		//print_r($_REQUEST);
		//Select the checked payment methods for the sites
		$sql_existing_Paymethod = "SELECT payment_methods_paymethod_id FROM payment_methods_forsites WHERE sites_site_id=".$_REQUEST['site_id'];	
		$ret_existing_Paymethod = $db->query($sql_existing_Paymethod);
		if($db->num_rows($ret_existing_Paymethod)) {
			while($row_sitePaymethod = $db->fetch_array($ret_existing_Paymethod)){
			$existingPymethod[] = $row_sitePaymethod['payment_methods_paymethod_id'];
			}
		}
		
		if(is_array($existingPymethod)){ // to delete an existing payment method from the already existing array
			foreach ($existingPymethod as $key => $val){
				if(!is_array($paymt_method))
				$paymt_method = array();
				if(!array_key_exists($val,$paymt_method)){
					$sql_del = "DELETE FROM payment_methods_forsites WHERE payment_methods_paymethod_id =$val AND sites_site_id =".$_REQUEST['site_id'];
				 	$db->query($sql_del);
				}
			}
		}
		if(is_array($paymt_method)){ // to insert new methods from the selected check box array
			foreach ($paymt_method as $key => $val){
				$insert_array = array();
				if(!is_array($existingPymethod)) {
				$existingPymethod = array();
				}
				if(!in_array($key,$existingPymethod)){
					$insert_array['payment_methods_paymethod_id']  = $key;
					$insert_array['sites_site_id']  = $_REQUEST['site_id'];
					$db->insert_from_array($insert_array, 'payment_methods_forsites');
				}
			//print_r($insert_array);
			}
			$error_msg = '<center><font color="red"><b>Successfully Saved the selected Payment Methods</b></font><br>';
		}
		include("includes/sites/list_site_pymt_methods.php");
}
}
else if($_REQUEST['fpurpose'] == 'view_paymethod_values')
{
	include("includes/sites/view_site_pymt_method_values.php");
}
else if($_REQUEST['fpurpose'] == 'update_paymethod_values')
{
	
	$sql_existing_values="SELECT paydet_id,payment_methods_details_payment_method_details_id,payment_methods_forsites_details_values FROM payment_methods_details as pmd LEFT JOIN payment_methods_forsites_details as pmsd ON pmd.payment_method_details_id = pmsd.payment_methods_details_payment_method_details_id WHERE (pmd.payment_methods_paymethod_id= ".$_REQUEST['paymethod_id']." AND pmsd.sites_site_id=".$_REQUEST['site_id'].")";
	$res_existing_values = $db->query($sql_existing_values);
	while($row_existing_values 	= $db->fetch_array($res_existing_values)){
		//update all the values passed from the form
		if((in_array($row_existing_values['paydet_id'], $payment_methods_details_id)) && ($details_values[$row_existing_values[	'payment_methods_details_payment_method_details_id']] !='')) {
			//echo $row_existing_values['paydet_id'];
			//foreach($payment_methods_details_id as $key => $val){
			$update_array												         = array();
			//$update_array['payment_methods_details_payment_method_details_id']   = $val;
			$update_array['sites_site_id']		                                 = add_slash($_REQUEST['site_id']);
			$update_array['payment_methods_forsites_id']	                     = add_slash($_REQUEST['payment_methods_forsites_id']);
			$update_array['payment_methods_forsites_details_values']	         = add_slash($details_values[$row_existing_values['payment_methods_details_payment_method_details_id']]);
			$db->update_from_array($update_array,'payment_methods_forsites_details','paydet_id',$row_existing_values['paydet_id']);
		//print_r($update_array);
	}
	// delete the removed value in the form from the table
	if( ($details_values[$row_existing_values['payment_methods_details_payment_method_details_id']] =='') && ($row_existing_values['payment_methods_forsites_details_values'] !='')){
	
		//echo "DELETE ID=".$row_existing_values['paydet_id'];
		$sql_del = "DELETE FROM payment_methods_forsites_details WHERE paydet_id =".$row_existing_values['paydet_id'];
		$db->query($sql_del);
	}
	
	}
	 // to insert new values from the form to the table
	foreach($payment_methods_details_id as $key => $val){
	if (($val == '') && ($details_values[$key]) !='' ){ //checking whether a new value in the array passed from the form 

		//echo "insert detail id=".$key;
			$insert_array = array();
			$insert_array['sites_site_id']				  = add_slash($_REQUEST['site_id']);
			$insert_array['payment_methods_forsites_id']  = add_slash($_REQUEST['payment_methods_forsites_id']);
			$insert_array['payment_methods_forsites_details_values'] = add_slash($details_values[$key]);
			$insert_array['payment_methods_details_payment_method_details_id']	 = add_slash($key);
			
			//print_r($insert_array);
			$db->insert_from_array($insert_array, 'payment_methods_forsites_details');
	}
	}
	$error_msg = '<center><font color="red"><b>Values Updated Successfully </b></font><br>';
	include("includes/sites/view_site_pymt_method_values.php");
}
else if($_REQUEST['fpurpose'] == 'delete')
{
	if($_REQUEST['site_id'])
	{
		$del_id = $_REQUEST['site_id'];
		// Deleting from all the table related to site id
		//########################################################33
		// Adverts
		$sql_del = "DELETE FROM advert_display_category WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		 $sql_del = "DELETE FROM advert_display_product WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		 $sql_del = "DELETE FROM advert_display_static WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		 $sql_del = "DELETE FROM adverts WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
//-------------------------------------------------------------------------------		
		// Call Back
		 $sql_del = "DELETE FROM callback WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
//-------------------------------------------------------------------------------		
		// Delete  All data from Cart table and sub tables
		
		$sqldel = "SELECT cart_id 
						  FROM cart WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {
			$cartid = $rowdel['cart_id'];
			
			 $sql_del = "DELETE FROM cart_messages WHERE sites_site_id = $cartid";
			$db->query($sql_del);
			
			 $sql_del = "DELETE FROM cart_variables WHERE sites_site_id = $cartid";
			$db->query($sql_del);
			
		} 		  
		
		
		$sql_del = "DELETE FROM cart_supportdetails WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM cart_checkout_values WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM cart WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// Client Table DIdin't queried ===================
		
		// Combo Table 
		
			$sql_del = "DELETE FROM combo_display_category WHERE sites_site_id = $del_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM combo_display_product WHERE sites_site_id = $del_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM combo_display_static WHERE sites_site_id = $del_id";
			$db->query($sql_del);
		
			$sql_del = "DELETE FROM combo_products WHERE sites_site_id = $del_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM combo WHERE sites_site_id = $del_id";
			$db->query($sql_del);
		
		// Deleting Customer Tables 
		$sql_del = "DELETE FROM customer_addressbook WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customer_addressbook_group WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customer_discount_customers_map WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customer_discount_group WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customer_discount_group_products_map WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
	
		
		$sql_del = "DELETE FROM customer_fav_categories WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customer_fav_products WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// Deleting Customer News letter group relations 
		//---------------------------------
		$sqldel = "SELECT custgroup_id 
						  FROM customer_newsletter_group WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$custgroup_id = $rowdel['custgroup_id'];
			
			$sql_del = "DELETE FROM customer_newsletter_group_customers_map WHERE custgroup_id = $custgroup_id";
			$db->query($sql_del);
			
		} 	
		
		$sql_del = "DELETE FROM customer_newsletter_group WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		//-----------------------------
		
		$sql_del = "DELETE FROM customer_registration_values WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT customer_id 
						  FROM customers WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$customerid = $rowdel['customer_id'];
			
			$sql_del = "DELETE FROM customer_websites WHERE customers_customer_id = $customerid";
			$db->query($sql_del);

		} 	
		
		$sql_del = "DELETE FROM customers WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customers_corporation WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM customers_corporation_department WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// END DELETING CUSTOMER TABLES
		
		
		// Deleting delivery details of site
		
		$sql_del = "DELETE FROM delivery_site_location WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM delivery_site_option_details WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// Deleting Display settings
		$sql_del = "DELETE FROM display_settings WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// Deleting Element sectio
		$sql_del = "DELETE FROM element_section_products WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM element_sections WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT element_id 
						  FROM elements WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$element_id = $rowdel['element_id'];
			
			$sql_del = "DELETE FROM element_value WHERE elements_element_id = $element_id";
			$db->query($sql_del);
		} 	
		
		$sql_del = "DELETE FROM elements WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// END elements Section
		// Start General Settings section 

		$sql_del = "DELETE FROM general_settings_site_bestseller WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_captions WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_country WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_currency WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_delivery WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_delivery_group WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_giftwrap WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_letter_templates WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_checkoutfields WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_paymentcapture_type WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_pricedisplay WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_state WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_site_tax WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_sites_common WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_sites_common_onoff WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_sites_listorders WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM general_settings_sites_customer_company_types WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		
		$sql_del = "DELETE FROM gift_voucherbuy_cartvalues WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT voucher_id 
						  FROM gift_vouchers WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$voucher_id = $rowdel['voucher_id'];
			
			$sql_del = "DELETE FROM gift_voucher_details_authorized_amount WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_details_refunded WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_emails WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_emails_console_send WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_notes WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_emails_console_send WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del); 
			
			$sql_del = "DELETE FROM gift_voucher_details_refunded WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_vouchers_cheque_details WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_vouchers_customer WHERE voucher_id = $voucher_id"; 
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_voucher_payment_authorizedata WHERE gift_vouchers_voucher_id = $voucher_id"; 
			$db->query($sql_del);
			$sql_del = "DELETE FROM gift_voucher_payment_cleardata WHERE gift_vouchers_voucher_id = $voucher_id"; 
			$db->query($sql_del);
			$sql_del = "DELETE FROM gift_voucher_payment_repeatdata WHERE gift_vouchers_voucher_id = $voucher_id"; 
			$db->query($sql_del);
			$sql_del = "DELETE FROM gift_voucher_refunded WHERE gift_vouchers_voucher_id = $voucher_id"; 
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM gift_vouchers_payment WHERE gift_vouchers_voucher_id = $voucher_id";
			$db->query($sql_del);
		} 	
		
		$sql_del = "DELETE FROM gift_vouchers WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		
		$sql_del = "DELETE FROM giftwrap_bows WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM giftwrap_card WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM giftwrap_paper WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM giftwrap_ribbon WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		//Deelting Header Display Category
		
		$sql_del = "DELETE FROM header_display_category WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM header_display_product WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM header_display_static WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		
		$sqldel = "SELECT image_id 
						  FROM images WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$image_id = $rowdel['image_id'];
			
			$sql_del = "DELETE FROM images_combo WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_giftwrap_bow WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_giftwrap_card WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_giftwrap_paper WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_giftwrap_ribbon WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_product WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_product_category WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_product_tab WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_shopbybrand WHERE images_image_id = $image_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM images_ssl WHERE images_image_id = $image_id";
			$db->query($sql_del);
		}
		
		
		$sql_del = "DELETE FROM images WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM images_directory WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		
		//Delete From Mod MEnu
		
		$sql_del = "DELETE FROM mod_menu WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// DEelte From Newsletter Customers
		$sql_del = "DELETE FROM newsletter_customers WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT newsletter_id 
						  FROM newsletters WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$newsletter_id = $rowdel['newsletter_id'];
			
			$sql_del = "DELETE FROM newsletter_products WHERE newsletters_newsletter_id = $newsletter_id";
			$db->query($sql_del);
		
		}	
		
		$sql_del = "DELETE FROM newsletters WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		/// Deleting Order Details
		
		$sqldel = "SELECT order_id 
						  FROM orders WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
		while($rowdel = $db->fetch_array($resdel)) {

			$order_id = $rowdel['order_id'];
			
			$sql_del = "DELETE FROM order_cheque_details WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_delivery_data WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sqldel = "SELECT orderdet_id 
						  FROM order_details WHERE orders_order_id = $order_id";
			$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
					$orderdet_id = $rowdel['orderdet_id'];
					
					$sql_del = "DELETE FROM order_details_despatched WHERE orderdet_id = $orderdet_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM order_details_refunded_products WHERE orderdet_id = $orderdet_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM order_details_removed WHERE orderdet_id = $orderdet_id";
					$db->query($sql_del);
			}
			
			$sql_del = "DELETE FROM order_details WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
				
			$sql_del = "DELETE FROM order_details_authorized_amount WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_cheque_details WHERE orders_order_id = $order_id";
			$db->query($sql_del); 
			
			$sql_del = "DELETE FROM order_details_messages WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_details_refunded WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_details_variables WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_dynamicvalues WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_emails WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_emails_console_send WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_giftwrap_details WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_notes WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_payment_authorizedata WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_payment_cleardata WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_payment_main WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_payment_repeatdata WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_promotional_code WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_promotionalcode_track WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sqldel = "SELECT query_id 
						  FROM order_queries WHERE orders_order_id = $order_id";
			$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
					$query_id = $rowdel['query_id'];
					
					$sql_del = "DELETE FROM order_queries_posts WHERE order_queries_query_id = $query_id";
					$db->query($sql_del);
				
			}
			
			
			$sql_del = "DELETE FROM order_queries WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_tax_details WHERE orders_order_id = $order_id";
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM order_voucher WHERE orders_order_id = $order_id";
			$db->query($sql_del);
		
		}	
		
		$sql_del = "DELETE FROM orders WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		// Deleting Payment method Tables
		
		$sql_del = "DELETE FROM payment_method_forsites_able2buy_details WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM payment_methods_forsites WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM payment_methods_forsites_details WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM payment_methods_sites_supported_cards WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM payment_types_forsites WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM payment_types_forsites WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT category_id 
						  FROM product_categories WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
					$category_id = $rowdel['category_id'];
					
					$sql_del = "DELETE FROM product_category_map WHERE product_categories_category_id = $category_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id = $category_id";
					$db->query($sql_del);
			}
			
		
		$sql_del = "DELETE FROM product_categories WHERE sites_site_id = $del_id";
		$db->query($sql_del);	
		
		$sql_del = "DELETE FROM product_categorygroup WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_categorygroup_display_category WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_categorygroup_display_products WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_categorygroup_display_staticpages WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_enquiries WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT enquiry_id 
						  FROM product_enquiries WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
					$enquiry_id = $rowdel['enquiry_id'];
					
					$sqlproddatadel = "SELECT id 
											FROM product_enquiry_data 
												WHERE product_enquiries_enquiry_id = $enquiry_id";
					$resproddatadel = $db->query($sqlproddatadel);	
					while($rowproddatadel = $db->fetch_array($resproddatadel)) {
					
						$dataid = $rowproddatadel['id'];
						
						$sql_del = "DELETE FROM product_enquiry_data_messages WHERE product_enquiry_data_messages = $dataid";
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_enquiry_data_vars WHERE product_enquiry_data_id = $dataid";
						$db->query($sql_del);
					}					
					
					$sql_del = "DELETE FROM product_enquiry_data WHERE product_enquiries_enquiry_id = $enquiry_id";
					$db->query($sql_del); 
					
					$sql_del = "DELETE FROM product_enquiry_notes WHERE product_enquiries_enquiry_id = $enquiry_id";
					$db->query($sql_del);
			}
		
		
		$sql_del = "DELETE FROM product_enquiries WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sqldel = "SELECT enquiry_id 
						  FROM product_enquiries_cart WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
					$enquiry_id = $rowdel['enquiry_id'];
					
					$sql_del = "DELETE FROM product_enquiries_cart_messages WHERE product_enquiries_cart_enquiry_id = $enquiry_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_enquiries_cart_vars WHERE product_enquiries_cart_enquiry_id = $category_id";
					$db->query($sql_del);
			} 
		
		$sql_del = "DELETE FROM product_enquiries_cart WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_enquiry_dynamic_values WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_featured WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_linkedproducts WHERE sites_site_id = $del_id";
		
		$db->query($sql_del); 
		
		$sql_del = "DELETE FROM product_reviews WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shelf WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shelf_display_category WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shelf_display_product WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shelf_display_static WHERE sites_site_id = $del_id";
		
		$db->query($sql_del);
		
		
		$sqldel = "SELECT product_id 
						  FROM products WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
			
					$product_id = $rowdel['product_id'];
					
					
					$sql_del = "DELETE FROM product_bulkdiscount WHERE products_product_id = $product_id";
					$db->query($sql_del);					
					
					$sql_del = "DELETE FROM product_attachments WHERE products_product_id = $product_id";
					$db->query($sql_del);					

					$sql_del = "DELETE FROM product_labels WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_tabs WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_shop_stock WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_shelf_product WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE products_product_id = $product_id";
					$db->query($sql_del); 
					$sql_del = "DELETE FROM product_shop_variables WHERE products_product_id = $product_id";
					$db->query($sql_del); 
					
					$prodel = "SELECT var_id 
						  FROM product_variables WHERE products_product_id = $product_id";
					$rsprodel = $db->query($prodel);	
			
					while($rowprdel = $db->fetch_array($rsprodel)) {
						$var_id = $rowprdel['var_id'];
						
						$sql_del = "DELETE FROM product_variable_data WHERE product_variables_var_id = $var_id";
						$db->query($sql_del);	
			
					}
					
					$sql_del = "DELETE FROM product_variable_combination_stock WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_variable_combination_stock_details WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_variable_messages WHERE products_product_id = $product_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_variables WHERE products_product_id = $product_id";
					$db->query($sql_del); 
					
					$sql_del = "DELETE FROM product_shopbybrand_product_map WHERE products_product_id = $product_id";
					$db->query($sql_del); 
			} 
		
		$sql_del = "DELETE FROM products WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		
		$sqldel = "SELECT vendor_id 
						  FROM product_vendors WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	

		while($rowdel = $db->fetch_array($resdel)) {
				
				$vendor_id = $rowdel['vendor_id'];
				
				$sql_del = "DELETE FROM product_vendor_contacts WHERE product_vendors_vendor_id = $vendor_id";
				$db->query($sql_del);
				
				$sql_del = "DELETE FROM product_vendor_map WHERE product_vendors_vendor_id = $vendor_id";
				$db->query($sql_del);
		}
		
		$sql_del = "DELETE FROM product_vendors WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sqldel = "SELECT shopbrand_id 
						  FROM product_shopbybrand WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
			
					$shopbrand_id = $rowdel['shopbrand_id'];
					
					$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id = $shopbrand_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM product_shopbybrand_product_map WHERE product_shopbybrand_shopbrand_id = $shopbrand_id";
					$db->query($sql_del);
				} 
		
		$sql_del = "DELETE FROM product_shopbybrand WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		
		$sql_del = "DELETE FROM product_shopbybrand_group WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shopbybrand_group_display_products WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_shopbybrand_group_display_staticpages WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM product_shopbybrand_group_display_category WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sqldel = "SELECT label_id 
						  FROM product_site_labels WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
			
					$label_id = $rowdel['label_id'];
					
					$sql_del = "DELETE FROM product_site_labels_values WHERE label_value_id = $label_id";
					$db->query($sql_del);
					
				
				} 
		
		
		$sql_del = "DELETE FROM product_site_labels WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_sizechart_heading WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_sizechart_heading_product_map WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_sizechart_values WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM product_stock_request WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM promotional_code WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM promotional_code_product WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sqldel = "SELECT quote_id 
						  FROM quote WHERE sites_site_id = $del_id";
		$resdel = $db->query($sqldel);	
			
			while($rowdel = $db->fetch_array($resdel)) {
			
					$quote_id = $rowdel['quote_id'];
					
					$sql_del = "DELETE FROM quote_admin_notes WHERE quote_quote_id = $quote_id";
					$db->query($sql_del);
					
					$qsql = "SELECT quotedet_id 
						  FROM quote WHERE sites_site_id = $del_id";
					$qres = $db->query($qsql);	
			
					while($qrow = $db->fetch_array($qres)) {	  
							$quotedet_id = $rowdel['quotedet_id'];	
							
							$sql_del = "DELETE FROM quote_details_messages WHERE quote_details_quotedet_id = $quotedet_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM quote_details_variables WHERE quote_details_quotedet_id = $quotedet_id";
							$db->query($sql_del);
					}
				   	
					$sql_del = "DELETE FROM quote_details WHERE quote_id = $quote_id";
					$db->query($sql_del);
					
					$sql_del = "DELETE FROM quote_giftwrap_details WHERE quote_quote_id = $quote_id";
					$db->query($sql_del);
				} 
		
		
		$sql_del = "DELETE FROM quote WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM saved_search WHERE sites_site_id = $del_id";
					$db->query($sql_del);
					
		$sql_del = "DELETE FROM se_bestseller_keywords WHERE sites_site_id = $del_id";
		$db->query($sql_del);
					
		$sql_del = "DELETE FROM se_category_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$qsql = "SELECT keyword_id 
						  FROM se_keywords WHERE sites_site_id = $del_id";
					$qres = $db->query($qsql);	
			
					while($qrow = $db->fetch_array($qres)) {	  
							$keyword_id = $qrow['keyword_id'];	
							
							$sql_del = "DELETE FROM se_category_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_combo_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_forgotpassword_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_help_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_home_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_product_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_registration_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_savedsearch_main_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_search_keyword WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_shelf_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_shop_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_sitemap_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_sitereviews_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM se_static_keywords WHERE se_keywords_keyword_id = $keyword_id";
							$db->query($sql_del);
					}
		$sql_del = "DELETE FROM se_keywords WHERE sites_site_id = $del_id";
		$db->query($sql_del);			
		
		$sql_del = "DELETE FROM se_combo_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM se_forgotpassword_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM se_help_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM se_home_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_meta_description WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_product_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_registration_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_savedsearchmain_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_search_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_shelf_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_shop_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_sitemap_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_sitereviews_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM se_static_title WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM site_headers WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM site_menu WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM site_user_permissions WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		

		$sql_del = "DELETE FROM sites_footer WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM sites_reviews WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$qsql = "SELECT shop_id 
						  FROM sites_shops WHERE sites_site_id = $del_id";
					$qres = $db->query($qsql);	
			
					while($qrow = $db->fetch_array($qres)) {	  
							$shop_id = $qrow['shop_id'];	
		$sql_del = "DELETE FROM product_shop_stock WHERE sites_shops_shop_id = $shop_id";
		$db->query($sql_del);					
		}
		
		$sql_del = "DELETE FROM sites_shops WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$sql_del = "DELETE FROM sites_users_7584 WHERE sites_site_id = $del_id";
		$db->query($sql_del);
		
		$qsql = "SELECT group_id 
						  FROM static_pagegroup WHERE sites_site_id = $del_id";
					$qres = $db->query($qsql);	
			
					while($qrow = $db->fetch_array($qres)) {	  
							$group_id = $qrow['group_id'];	
							
							$sql_del = "DELETE FROM static_pagegroup_position WHERE static_pagegroup_group_id = $group_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM static_pagegroup_static_page_map WHERE static_pagegroup_group_id = $group_id";
							$db->query($sql_del);
							
					}		
		
		
		$sql_del = "DELETE FROM static_pagegroup WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM static_pagegroup_display_category WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM static_pagegroup_display_product WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM static_pagegroup_display_static WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM static_pages WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$qsql = "SELECT survey_id 
						  FROM survey WHERE sites_site_id = $del_id";
					$qres = $db->query($qsql);	
			
					while($qrow = $db->fetch_array($qres)) {	  
							$survey_id = $qrow['survey_id'];	
							
							$sql_del = "DELETE FROM survey_option WHERE survey_id = $survey_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM survey_results WHERE survey_id = $survey_id";
							$db->query($sql_del);
							
					}	



		$sql_del = "DELETE FROM survey WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM survey_display_category WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM survey_display_product WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM survey_display_product WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$sql_del = "DELETE FROM survey_display_static WHERE sites_site_id = $del_id";
		$db->query($sql_del);

		$qsql = "SELECT wishlist_id 
						  FROM wishlist WHERE sites_site_id = $del_id";
		$qres = $db->query($qsql);	
			
		while($qrow = $db->fetch_array($qres)) {	  
							$wishlist_id = $rowdel['wishlist_id'];	
							
							$sql_del = "DELETE FROM wishlist_messages WHERE wishlist_wishlist_id = $wishlist_id";
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM wishlist_variables WHERE wishlist_wishlist_id = $wishlist_id";
							$db->query($sql_del);
							
					}	

		$sql_del = "DELETE FROM wishlist WHERE sites_site_id = $del_id";
		$db->query($sql_del);

$domsql = "SELECT site_domain FROM sites WHERE site_id = $del_id";
$domres = $db->query($domsql);
$domrow = $db->fetch_array($domres);
$sitedomain = $domrow['site_domain'];
$newsite = $sitedomain."_bak";

		$sql_del = "DELETE FROM sites WHERE site_id = $del_id";
		$db->query($sql_del);

		//rename(IMAGE_ROOT_PATH."/".$sitedomain, IMAGE_ROOT_PATH."/".$newsite);
		if ($sitedomain and $sitedomain!='')
					{
						//Remove the folder for the current site from images folder of document root
						//removefiles("/var/www/html/bshop/images/".$cur_domain); // Local server
						removefiles(IMAGE_ROOT_PATH."/".$sitedomain); // Live server
					}	

		$alert = '<center><font color="red"><b> Site has been removed </b></font><br>';
		include("includes/sites/list_sites.php");

		//########################################################33
	}
}elseif($_REQUEST['fpurpose'] == 'List_Payment_Types'){
	include("includes/sites/list_site_pymt_types.php");

}elseif($_REQUEST['fpurpose'] == 'Select_Payment_Types'){
	//include("includes/sites/list_site_pymt_types.php");
/*$sql_paytypes_insite ="SELECT paytype_forsites_id,paytype_id FROM payment_types_forsites WHERE sites_site_id =".$_REQUEST['site_id'];
$res_paytypes_insite = $db->query($sql_paytypes_insite);
$arr_paytype_id = array();
while($paytypes_insite = $db->fetch_array()){
	$arr_paytype_id = $paytypes_insite['paytype_id'];
	}*/
if($_REQUEST['assign_payment_methods'] == 'Save Selected Payment Types') {
//$sql_chk_existing_pymttype = "SELECT paytype_forsites_id FROM payment_types_forsites WHERE sites_site_id = ".$_REQUEST['site_id']." AND paytype_forsites_active = 1";
$sql_reset_pymttype = "UPDATE payment_types_forsites SET paytype_forsites_active = 0 WHERE sites_site_id = ".$_REQUEST['site_id'];
$db->query($sql_reset_pymttype);
	if(is_array($_REQUEST['paytypeid'])){
		foreach($_REQUEST['paytypeid'] as $key => $val){
			$update_array						      = array();
			$update_array['paytype_id']		          = add_slash($val);
			//$update_array['sites_site_id']	      = add_slash($_REQUEST['site_id']);
			$update_array['paytype_forsites_active']  = 1;
			$db->update_from_array($update_array,'payment_types_forsites','paytype_forsites_id',$key);
		}
	}
	$error_msg = '<center><font color="red"><b>Successfully Saved the selected Payment Type</b></font><br>';;
	
}
include("includes/sites/list_site_pymt_types.php");
}
/*For the lsting of shops in the site*/
else if ($_REQUEST['fpurpose'] == 'List_Shops')
{

	include("includes/sites/list_site_shops.php");
}
else if ($_REQUEST['fpurpose'] == 'Save_shopChanges')
{
foreach($_REQUEST['shop_order'] as $key => $val){
	$update_array = array();
	
	$update_array['shop_order'] 		= $shop_order[$key];
	$update_array['shop_active'] 			= $shop_active[$key];
	$db->update_from_array($update_array, 'sites_shops', 'shop_id', $key);
}
	include("includes/sites/list_site_shops.php");
}else if ($_REQUEST['fpurpose'] == 'Add_shops')
{	
include("includes/sites/add_site_shop.php");
}elseif($_REQUEST['fpurpose'] == 'insert_shops'){

	if($_REQUEST['AddShop_submit'])
	{
		$alert = '';
		//#Server side validation

		$fieldRequired = array($_REQUEST['shop_title'],$_REQUEST['shop_address'],$_REQUEST['shop_phone'],$_REQUEST['shop_mobile'],$_REQUEST['shop_email']);
		$fieldDescription = array('Shop Title','Shop Address','Shop Phone','Mobile','Email');
		$fieldEmail = array($_REQUEST['shop_email']);
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM sites_shops WHERE shop_title='".$_REQUEST['shop_title']."' AND sites_site_id=".$_REQUEST['site_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Shop name already exists for the Same site';
		}
		
		if(!$alert) {
			$insert_array = array();
			//##############################
			//Inserting into sites_shops table
			//##############################
			$insert_array['sites_site_id']					= $_REQUEST['site_id'];
			$insert_array['shop_title']						= $_REQUEST['shop_title'];
			$insert_array['shop_address']					= $_REQUEST['shop_address'];
			$insert_array['shop_phone']						= add_slash($_REQUEST['shop_phone']);
			$insert_array['shop_mobile']					= $_REQUEST['shop_mobile'];
			$insert_array['shop_email']						= add_slash($_REQUEST['shop_email']);
			$insert_array['shop_contactperson']				= add_slash($_REQUEST['shop_contactperson']);
			$insert_array['shop_conatactperson_designation']= add_slash($_REQUEST['shop_conatactperson_designation']);
			$insert_array['shop_order']						= add_slash($_REQUEST['shop_order']);
			$insert_array['shop_active']					= ($_REQUEST['shop_active'])?1:0;
			$db->insert_from_array($insert_array, 'sites_shops');
			$insert_shopid = $db->insert_id();//#Getting generated site id
		$alert = '<center><font color="red"><b>Successfully Added</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Sites Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=List_Shops&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to List Shops In this Site</a>
			<br /><br />
			<a href="home.php?request=sites&fpurpose=Edit_shops&shop_id=<?=$insert_shopid?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Shop</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=Add_shops&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Add a New Shop</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/add_site_shop.php");
		}
	}

}else if($_REQUEST['fpurpose'] == 'Edit_shops')
{	
include("includes/sites/edit_site_shop.php");
}else if($_REQUEST['fpurpose'] == 'update_shops'){
if($_REQUEST['UpdateShop_submit']){
$sql_check = "SELECT count(*) as cnt FROM sites_shops WHERE shop_title='".$_REQUEST['shop_title']."' AND shop_id<>".$_REQUEST['shop_id']." AND sites_site_id=".$_REQUEST['site_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Shop name already exists for the Same site';
		}if(!$alert) {
			$update_array = array();
			//##############################
			//Updating into sites_shops table
			//##############################
			$update_array['sites_site_id']					= $_REQUEST['site_id'];
			$update_array['shop_title']						= $_REQUEST['shop_title'];
			$update_array['shop_address']					= $_REQUEST['shop_address'];
			$update_array['shop_phone']						= add_slash($_REQUEST['shop_phone']);
			$update_array['shop_mobile']					= $_REQUEST['shop_mobile'];
			$update_array['shop_email']						= add_slash($_REQUEST['shop_email']);
			$update_array['shop_contactperson']				= add_slash($_REQUEST['shop_contactperson']);
			$update_array['shop_conatactperson_designation']= add_slash($_REQUEST['shop_conatactperson_designation']);
			$update_array['shop_order']						= add_slash($_REQUEST['shop_order']);
			$update_array['shop_active']					= ($_REQUEST['shop_active'])?1:0;
			$db->update_from_array($update_array, 'sites_shops','shop_id', $_REQUEST['shop_id']);
			$insert_shopid = $db->insert_id();//#Getting generated site id
		$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Sites Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=List_Shops&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to List Shops In this Site</a>
			<br /><br />
			<a href="home.php?request=sites&fpurpose=Edit_shops&shop_id=<?=$_REQUEST['shop_id']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Shop</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=Add_shops&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Add a New Shop</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/add_site_shop.php");
		}
}

}else if($_REQUEST['fpurpose'] == 'delete_shop')
{	
if($_REQUEST['shop_id'])
	{
		$sql = "DELETE FROM sites_shops WHERE shop_id=".$_REQUEST['shop_id'] ." AND sites_site_id=".$_REQUEST['site_id'];
		$db->query($sql);
		$sql = "DELETE FROM product_shop_stock WHERE sites_shops_shop_id=".$_REQUEST['shop_id'];
		$db->query($sql);
		$sql = "DELETE FROM product_shop_variable_combination_stock WHERE sites_shops_shop_id=".$_REQUEST['shop_id'];
		$db->query($sql);
		$sql = "DELETE FROM product_shop_variable_data WHERE sites_shops_shop_id=".$_REQUEST['shop_id'];
		$db->query($sql);
		$sql = "DELETE FROM product_shop_variables WHERE sites_shops_shop_id=".$_REQUEST['shop_id'];
		$db->query($sql);
		$alert = "Sucessfully deleted";
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;

include("includes/sites/list_site_shops.php");
}
else if ($_REQUEST['fpurpose'] == 'List_AbleToBy_details')
{
	include("includes/sites/List_AbletoBy_details.php");
}
else if ($_REQUEST['fpurpose'] == 'Edit_AlbeToBy')
{
	include("includes/sites/Edit_AbletoBy_details.php");
}
else if($_REQUEST['fpurpose'] == 'update_AbleToBY'){
if($_REQUEST['UpdateAbleToBy_submit']){
$sql_check = "SELECT count(*) as cnt FROM payment_method_forsites_able2buy_details WHERE det_code='".$_REQUEST['det_code']."' AND det_id<>".$_REQUEST['det_id']." AND sites_site_id=".$_REQUEST['site_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Code already exists for the Same site';
		}if(!$alert) {
			$update_array = array();
			//##############################
			//Updating into sites_shops table
			//##############################
			$update_array['sites_site_id']					= $_REQUEST['site_id'];
			$update_array['det_code']						= $_REQUEST['det_code'];
			$update_array['det_caption']= add_slash($_REQUEST['det_caption']);
			$update_array['det_order']						= add_slash($_REQUEST['det_order']);
			$update_array['det_hidden']					= ($_REQUEST['det_hidden'])?0:1;
			$db->update_from_array($update_array, 'payment_method_forsites_able2buy_details','det_id', $_REQUEST['det_id']);
			$insert_shopid = $db->insert_id();//#Getting generated site id
		$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Sites Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=List_AbleToBy_details&site_id=<?=$_REQUEST['site_id']?>&code_name=<?=$_REQUEST['code_name']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to List AbleToBy Details In this Site</a>
			<br /><br />
			<a href="home.php?request=sites&fpurpose=Edit_AlbeToBy&det_id=<?=$_REQUEST['det_id']?>&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to Edit this Code</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=Add_AbleToBy&site_id=<?=$_REQUEST['site_id']?>&code_name=<?=$_REQUEST['code_name']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Add a New Entry</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/Edit_AbletoBy_details.php");
		}
}
}
else if($_REQUEST['fpurpose'] == 'Add_AbleToBy')
{
	include("includes/sites/Add_AbletoBy_details.php");
}
else if($_REQUEST['fpurpose'] == 'delete_AbletoBy')
{	
if($_REQUEST['det_id'])
	{
		$sql = "DELETE FROM payment_method_forsites_able2buy_details WHERE det_id=".$_REQUEST['det_id'] ." AND sites_site_id=".$_REQUEST['site_id'];
		$db->query($sql);
		$alert = "Successfully deleted";
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;

include("includes/sites/List_AbletoBy_details.php");
}
elseif($_REQUEST['fpurpose'] == 'insert_AbleToBY'){

	if($_REQUEST['AddAbleToBy_submit'])
	{
		$alert = '';
		//#Server side validation

		$fieldRequired = array($_REQUEST['det_code'],$_REQUEST['det_caption']);
		$fieldDescription = array('Code','Caption');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM payment_method_forsites_able2buy_details WHERE det_code='".$_REQUEST['det_code']."' AND sites_site_id=".$_REQUEST['site_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Code already exists for the Same site';
		}
		
		if(!$alert) {
			$insert_array = array();
			//##############################
			//Inserting into sites_shops table
			//##############################
			$insert_array['sites_site_id']					= $_REQUEST['site_id'];
			$insert_array['det_code']						= $_REQUEST['det_code'];
			$insert_array['det_caption']= add_slash($_REQUEST['det_caption']);
			$insert_array['det_order']						= add_slash($_REQUEST['det_order']);
			$insert_array['det_hidden']					= ($_REQUEST['det_hidden'])?0:1;
			$db->insert_from_array($insert_array, 'payment_method_forsites_able2buy_details');
			$insert_shopid = $db->insert_id();//#Getting generated site id
		$alert = '<center><font color="red"><b>Successfully Added</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=sites&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Sites Listing page</a><br /><br />
			<a href="home.php?request=sites&fpurpose=List_AbleToBy_details&det_id=<?=$_REQUEST['det_id']?>&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to List AbleToBy Details In this Site</a>
			<br /><br />
			<a href="home.php?request=sites&fpurpose=Edit_AlbeToBy&det_id=<?=$_REQUEST['det_id']?>&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to Edit this Code</a>
			<br /><br /><a href="home.php?request=sites&fpurpose=Add_AbleToBy&code_name=<?=$_REQUEST['code_name']?>&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&client=<?=$_REQUEST['client']?>&theme=<?=$_REQUEST['theme']?>&status=<?=$_REQUEST['status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Add a New Entry</a>

			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/sites/Add_AbletoBy_details.php");
		}
	}
}
else if ($_REQUEST['fpurpose'] == 'SaveAble_changes')
{
foreach($_REQUEST['ableto_order'] as $key => $val){
	$update_array = array();
	$update_array['det_order'] 		= $ableto_order[$key];
	$update_array['det_hidden'] 			= $ableto_active[$key];
	$db->update_from_array($update_array, 'payment_method_forsites_able2buy_details', 'det_id', $key);
}
	include("includes/sites/List_AbletoBy_details.php");
}

function removefiles($dir)
	{
		if($dir != IMAGE_ROOT_PATH)
		{
			if ($handle = opendir($dir))
			{
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != "..")
					{
						if (is_dir($dir.'/'.$file))
						{
							// calling the function recursively in case of subdirectories
							removefiles($dir.'/'.$file);
						}	 
						else
						{
							//Remove the files
							unlink($dir.'/'.$file);
						}	
					}
				}
				//Remove current directory
				rmdir($dir);
				closedir($handle);
			}
		}	
	}
?>