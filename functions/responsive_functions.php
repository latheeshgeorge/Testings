<?php
// Function to return the static fields to be used in the checkout page
function get_Field_responsive($key='',$field_name,$saved_checkoutvals,$customer_arr,$cur_form='',$class_array=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr,$ecom_load_mobile_theme;
	if($ecom_load_mobile_theme)
		$box_size = 20;
	else
		$box_size = 30;
	// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
	// If logged in then show the default values for billing address
	if (count($saved_checkoutvals)==0)
	{
		// Check whether logged in
		if(get_session_var('ecom_login_customer'))
		{
			if($cur_form=='frm_buygiftvoucher') // case of // gift voucher buy section
			{
				$sql_cust = "SELECT customer_title,customer_compname,customer_fname,customer_mname
									customer_surname,customer_buildingname,customer_streetname,
									customer_towncity,customer_statecounty,country_id,
									customer_postcode,customer_phone,customer_mobile,
									customer_fax,customer_email_7503 
								FROM
									customers
								WHERE
									customer_id =".get_session_var('ecom_login_customer')."
								LIMIT
									1";
				$ret_cust = $db->query($sql_cust);
				if ($db->num_rows($ret_cust))
					$row_cust = $db->fetch_array($ret_cust);

				// Set the values to be shown for voucher fields
				$saved_checkoutvals['checkout_vouchertitle'] 			= $row_cust['customer_title'];
				$saved_checkoutvals['checkout_vouchercomp_name'] 		= $row_cust['customer_compname'];
				$saved_checkoutvals['checkout_voucherfname'] 			= $row_cust['customer_fname'];
				$saved_checkoutvals['checkout_vouchermname'] 			= $row_cust['customer_mname'];
				$saved_checkoutvals['checkout_vouchersurname'] 			= $row_cust['customer_surname'];
				$saved_checkoutvals['checkout_voucherbuilding']			= $row_cust['customer_buildingname'];
				$saved_checkoutvals['checkout_voucherstreet'] 			= $row_cust['customer_streetname'];
				$saved_checkoutvals['checkout_vouchercity'] 			= $row_cust['customer_towncity'];
				$saved_checkoutvals['checkout_voucherstate'] 			= $row_cust['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 				= $row_cust['country_id'];
				$saved_checkoutvals['checkout_voucherzipcode'] 			= $row_cust['customer_postcode'];
				$saved_checkoutvals['checkout_voucherphone'] 			= $row_cust['customer_phone'];
				$saved_checkoutvals['checkout_vouchermobile'] 			= $row_cust['customer_mobile'];
				$saved_checkoutvals['checkout_voucherfax'] 				= $row_cust['customer_fax'];
				$saved_checkoutvals['checkout_voucheremail'] 			= $row_cust['customer_email_7503'];
			}
			else
			{
				// Set the values to be shown for billing address fields
				$saved_checkoutvals['checkout_title'] 				= $customer_arr['customer_title'];
				$saved_checkoutvals['checkout_comp_name'] 			= $customer_arr['customer_compname'];
				$saved_checkoutvals['checkout_fname'] 				= $customer_arr['customer_fname'];
				$saved_checkoutvals['checkout_mname'] 				= $customer_arr['customer_mname'];
				$saved_checkoutvals['checkout_surname'] 			= $customer_arr['customer_surname'];
				$saved_checkoutvals['checkout_building']			= $customer_arr['customer_buildingname'];
				$saved_checkoutvals['checkout_street'] 				= $customer_arr['customer_streetname'];
				$saved_checkoutvals['checkout_city'] 				= $customer_arr['customer_towncity'];
				$saved_checkoutvals['checkout_state'] 				= $customer_arr['customer_statecounty'];
				$saved_checkoutvals['checkout_country'] 			= $customer_arr['country_id'];
				$saved_checkoutvals['checkout_zipcode'] 			= $customer_arr['customer_postcode'];
				$saved_checkoutvals['checkout_phone'] 				= $customer_arr['customer_phone'];
				$saved_checkoutvals['checkout_mobile'] 				= $customer_arr['customer_mobile'];
				$saved_checkoutvals['checkout_fax'] 				= $customer_arr['customer_fax'];
				$saved_checkoutvals['checkout_email'] 				= $customer_arr['customer_email_7503'];
			}

			// Get the name of state
			/*if ($saved_checkoutvals['checkout_state']!=0)
			{

				$sql_state = "SELECT state_name
								FROM
									general_settings_site_state
								WHERE
									state_id=".$saved_checkoutvals['checkout_state']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_state = $db->query($sql_state);
				if ($db->num_rows($ret_state))
				{
					$row_state = $db->fetch_array($ret_state);
					$saved_checkoutvals['checkout_state'] = stripslashes($row_state['state_name']);
				}
				else
					$saved_checkoutvals['checkout_state'] = '';
			}
			else
				$saved_checkoutvals['checkout_state'] = '';*/

			// Get the name of country
			/*if ($saved_checkoutvals['checkout_country']!=0)
			{

				$sql_country = "SELECT country_name
								FROM
									general_settings_site_country
								WHERE
									country_id=".$saved_checkoutvals['checkout_country']."
									AND sites_site_id = $ecom_siteid
								LIMIT
									1";
				$ret_country = $db->query($sql_country);
				if ($db->num_rows($ret_country))
				{
					$row_country = $db->fetch_array($ret_country);
					$saved_checkoutvals['checkout_country'] = stripslashes($row_country['country_name']);
				}
				else
				 $saved_checkoutvals['checkout_country']='';
			}
			else
				$saved_checkoutvals['checkout_country'] = '';
				
				*/

		}
	}
	$txt_cls 		= ($class_array['txtbox_cls'])?'class="'.$class_array['txtbox_cls'].'"':'';
	$txtarea_cls 	= ($class_array['txtarea_cls'])?'class="'.$class_array['txtarea_cls'].'"':'';
	$select_cls 	= ($class_array['select_cls'])?'class="'.$class_array['select_cls'].'"':'';
	$txtarea_cls 	= 'class="form-control"';
	$txt_cls 	= 'class="form-control"';
	$select_cls     = 'class="form-control"';
	$txt_onblur 	= 'class="form-control"';

	// Deciding which is the field to be displayed
	switch($key)
	{
		case 'checkout_title':
		case 'checkout_vouchertitle':
		case 'checkoutdelivery_title':
		case 'customer_title':
			$ret = '<select name="'.$key.'" id="'.$key.'"'.$select_cls.'>';
			$sel = ($saved_checkoutvals[$key]=='Mr.')?'selected':'';
			$ret .='<option value="Mr." '.$sel.'>Mr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Mrs.')?'selected':'';
			$ret .='<option value="Mrs." '.$sel.'>Mrs.</option>';
			$sel = ($saved_checkoutvals[$key]=='Miss.')?'selected':'';
			$ret .='<option value="Miss." '.$sel.'>Miss.</option>';
			$sel = ($saved_checkoutvals[$key]=='Ms.')?'selected':'';
			$ret .='<option value="Ms." '.$sel.'>Ms.</option>';
			$sel = ($saved_checkoutvals[$key]=='M/s.')?'selected':'';
			$ret .='<option value="M/s." '.$sel.'>M/s.</option>';
			$sel = ($saved_checkoutvals[$key]=='Dr.')?'selected':'';
			$ret .='<option value="Dr." '.$sel.'>Dr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Sir.')?'selected':'';
			$ret .='<option value="Sir." '.$sel.'>Sir.</option>';
			$sel = ($saved_checkoutvals[$key]=='Rev.')?'selected':'';
			$ret .='<option value="Rev." '.$sel.'>Rev.</option>';
			$ret .='</select>';
		break;
		case 'checkout_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_country" id="checkout_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_country'])
						$saved_checkoutvals['checkout_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}	
							
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
				$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';

			}
		break;
		case 'checkoutdelivery_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order  	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkoutdelivery_country" id="checkoutdelivery_country"  	'.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!trim($saved_checkoutvals['checkoutdelivery_country']))
						$saved_checkoutvals['checkoutdelivery_country'] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkoutdelivery_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}
							
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}	
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case 'checkout_vouchercountry':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="checkout_vouchercountry" id="checkout_vouchercountry" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['checkout_vouchercountry'])
						$saved_checkoutvals['checkout_vouchercountry'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['checkout_vouchercountry'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case 'cbo_country':
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="cbo_country" id="cbo_country"  '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals['cbo_country'])
						$saved_checkoutvals['cbo_country'] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['cbo_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case 'checkout_comp_name':
		case 'checkout_fname':
		case 'checkout_mname':
		case 'checkout_surname':
		case 'checkout_building':
		case 'checkout_address2':
		case 'checkout_street':
		case 'checkout_city':
		case 'checkout_state':
		case 'checkout_zipcode':
		case 'checkout_phone':
		case 'checkout_mobile':
		case 'checkout_fax':
		case 'checkout_email':
		

		case 'checkout_vouchercomp_name':
		case 'checkout_voucherfname':
		case 'checkout_vouchermname':
		case 'checkout_vouchersurname':
		case 'checkout_voucherbuilding':
		case 'checkout_voucherstreet':
		case 'checkout_vouchercity':
		case 'checkout_voucherstate':
		case 'checkout_voucherzipcode':
		case 'checkout_voucherphone':
		case 'checkout_vouchermobile':
		case 'checkout_voucherfax':
		case 'checkout_voucheremail':
		

		case 'checkoutdelivery_comp_name':
		case 'checkoutdelivery_fname':
		case 'checkoutdelivery_mname':
		case 'checkoutdelivery_surname':
		case 'checkoutdelivery_building':
		case 'checkoutdelivery_address2':
		case 'checkoutdelivery_street':
		case 'checkoutdelivery_city':
		case 'checkoutdelivery_state':
		case 'checkoutdelivery_zipcode':
		case 'checkoutdelivery_phone':
		case 'checkoutdelivery_mobile':
		case 'checkoutdelivery_fax':
		case 'checkoutdelivery_email':
		
		case 'customer_fname':
		case 'customer_mname':
		case 'customer_surname':
		case 'customer_position':
		case 'customer_buildingname':
		case 'customer_streetname':
		case 'customer_towncity':
		case 'cbo_state':
		case 'customer_postcode':
		/*case 'cbo_country':*/
		case 'customer_phone':
		case 'customer_mobile':
		case 'customer_fax':
		
		case 'customer_compname':
		case 'customer_compregno':
		case 'customer_compvatregno':

		case 'checkoutpay_nameoncard':

		case 'checkoutchq_number':
		case 'checkoutchq_bankname':

		case 'checkoutpay_cardnumber':
		//print_r($saved_checkoutvals);
//echo $saved_checkoutvals[$key];
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
		break;
		case 'checkoutpay_issuenumber':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
		break;
		case 'checkoutpay_securitycode':
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
		break;
		case 'customer_comptype':
			$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			$sql = "SELECT comptype_id,comptype_name
						FROM 
							general_settings_sites_customer_company_types 
						WHERE 
							sites_site_id=$ecom_siteid 
						AND 
							comptype_hide=0 
						ORDER BY 
							comptype_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				/*while ($row = $db->fetch_array($rets))
				{
					$key = $row['comptype_id'];
					$ret .= '<option value="'.$key.'">'.stripslashes($row['comptype_name']).'</option>';
				}*/
				while ($row = $db->fetch_array($rets))
				{
					$key1 = $row['comptype_id'];
					$selc='';
					if($saved_checkoutvals[$key]==$key1)
					{
						$selc = 'selected';
					}
					$ret .= '<option value="'.$key1.'" '.$selc.'>'.stripslashes($row['comptype_name']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_cardtype':
			/*if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card_voucher(this)">';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card(this)">';*/
			
			if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';	

			$ret .= "<option value=''>-- Select --</option>";
			
			$sql = "SELECT a.cardtype_key,a.cardtype_caption,a.cardtype_issuenumber_req,a.cardtype_securitycode_count,cardtype_numberofdigits,a.cardtype_paypalprokey 
					FROM
						payment_methods_supported_cards a,payment_methods_sites_supported_cards b
					WHERE
						b.sites_site_id = $ecom_siteid
						AND a.cardtype_id=b.payment_methods_supported_cards_cardtype_id
					ORDER BY
						b.supportcard_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				while ($row = $db->fetch_array($rets))
				{
					if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO')
						$key = $row['cardtype_paypalprokey'];
					else
						$key = $row['cardtype_key'];
					$ret .= '<option value="'.$key.'_'.$row['cardtype_issuenumber_req'].'_'.$row['cardtype_securitycode_count'].'_'.$row['cardtype_numberofdigits'].'">'.stripslashes($row['cardtype_caption']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case 'checkoutpay_expirydate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			for($i=date('Y');$i<date('Y')+10;$i++)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutpay_issuedate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=date('Y');$i>date('Y')-20;$i--)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case 'checkoutchq_date':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value=""  '.$txt_cls.'/>(e.g. 01-01-2008)';

		break;
		case 'checkout_notes':
		case 'checkout_vouchernotes':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'>'.$saved_checkoutvals[$key].'</textarea>';
		break;
		case 'checkoutchq_bankbranch':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'></textarea>';
		break;
	};
	return $ret;
}
function get_Field_tenant_responsive($ext_name,$key='',$field_name,$saved_checkoutvals,$customer,$cur_form='',$class_array=array())
{
	global $db,$ecom_siteid,$ecom_is_country_textbox,$Settings_arr,$ecom_load_mobile_theme;
	if($ecom_load_mobile_theme)
		$box_size = 20;
	else
		$box_size = 30;
	// If not values exists for checkout fields for current site in current session, then check whether customer is logged in
	
	$txt_cls 		= ($class_array['txtbox_cls'])?'class="'.$class_array['txtbox_cls'].'"':'';
	$txtarea_cls 	= ($class_array['txtarea_cls'])?'class="'.$class_array['txtarea_cls'].'"':'';
	$select_cls 	= ($class_array['select_cls'])?'class="'.$class_array['select_cls'].'"':'';
	
	$txt_onblur 		= ($class_array['onblur'])?$class_array['onblur']:'';
	$txtarea_cls 	= 'class="form-control"';
	$select_cls     = 'class="form-control"';
	$txt_onblur 	= 'class="form-control"';
		$txt_cls 	= 'class="form-control"';

///echo "'".$ext_name."checkout_title'";
	// Deciding which is the field to be displayed
	switch($key)
	{ 
		case $ext_name."checkout_title":
		case $ext_name."checkout_vouchertitle":
		case $ext_name."checkoutdelivery_title":
		case $ext_name."customer_title":
			$ret = '<select name="'.$key.'" id="'.$key.'"'.$select_cls.'>';
			$sel = ($saved_checkoutvals[$key]=='Mr.')?'selected':'';
			$ret .='<option value="Mr." '.$sel.'>Mr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Mrs.')?'selected':'';
			$ret .='<option value="Mrs." '.$sel.'>Mrs.</option>';
			$sel = ($saved_checkoutvals[$key]=='Miss.')?'selected':'';
			$ret .='<option value="Miss." '.$sel.'>Miss.</option>';
			$sel = ($saved_checkoutvals[$key]=='Ms.')?'selected':'';
			$ret .='<option value="Ms." '.$sel.'>Ms.</option>';
			$sel = ($saved_checkoutvals[$key]=='M/s.')?'selected':'';
			$ret .='<option value="M/s." '.$sel.'>M/s.</option>';
			$sel = ($saved_checkoutvals[$key]=='Dr.')?'selected':'';
			$ret .='<option value="Dr." '.$sel.'>Dr.</option>';
			$sel = ($saved_checkoutvals[$key]=='Sir.')?'selected':'';
			$ret .='<option value="Sir." '.$sel.'>Sir.</option>';
			$sel = ($saved_checkoutvals[$key]=='Rev.')?'selected':'';
			$ret .='<option value="Rev." '.$sel.'>Rev.</option>';
			$ret .='</select>';
		break;
		case $ext_name.'checkout_country':
		$field_name = $ext_name.'checkout_country';
			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkout_country" id="'.$ext_name.'checkout_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}	
							
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case $ext_name.'checkoutdelivery_country':
				$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name,country_order  	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_order DESC, country_name ASC";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkoutdelivery_country" id="'.$ext_name.'checkoutdelivery_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!trim($saved_checkoutvals[$field_name]))
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					$special_exists = false;
					$display_seperator = false;
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						
						if($row_country['country_order']!=0)
						{
							$special_exists = true;
						}
						if($special_exists and !$display_seperator and $row_country['country_order']==0)
						{
								$ret.= '<optgroup label="== == == == == == == == == =="></optgroup>';
								$display_seperator = true;
						}
							
						$ret .= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}	
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case $ext_name.'checkout_vouchercountry':
						$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'checkout_vouchercountry" id="'.$ext_name.'checkout_vouchercountry" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals[$field_name])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case $ext_name.'cbo_country':
			$field_name = $ext_name.'checkout_country';

			if($ecom_is_country_textbox!=1)
			{
				// get the list of countries to be displayed here
				$sql_country = "SELECT country_id,country_name 	
									FROM 
										general_settings_site_country 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND country_hide=1  
									ORDER BY 
										country_name";
				$ret_country = $db->query($sql_country);
				$ret = '<select name="'.$ext_name.'cbo_country" id="'.$ext_name.'cbo_country" '.$select_cls.'>';
				$ret .= '<option value="">- Select -</option>';
				if($db->num_rows($ret_country))
				{
					if(!$saved_checkoutvals[$field_name])
						$saved_checkoutvals[$field_name] = $Settings_arr['default_country_id'];
					while ($row_country = $db->fetch_array($ret_country))
					{
						if($row_country['country_id']==$saved_checkoutvals['cbo_country'])
							$checked = ' selected="selected"';
						else
							$checked = '';
						$ret.= '<option value="'.$row_country['country_id'].'"'.$checked.'>'.stripslashes($row_country['country_name']).'</option>';
					}					
				}
				$ret .= '</select>';
			}
			elseif($ecom_is_country_textbox==1)
			{
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
			}
		break;
		case $ext_name.'checkout_comp_name':
		case $ext_name.'checkout_fname':
		case $ext_name.'checkout_mname':
		case $ext_name.'checkout_surname':
		case $ext_name.'checkout_building':
		case $ext_name.'checkout_address2':
		case $ext_name.'checkout_street':
		case $ext_name.'checkout_city':
		case $ext_name.'checkout_state':
		case $ext_name.'checkout_zipcode':
		case $ext_name.'checkout_phone':
		case $ext_name.'checkout_mobile':
		case $ext_name.'checkout_fax':
		case $ext_name.'checkout_email':
		

		case $ext_name.'checkout_vouchercomp_name':
		case $ext_name.'checkout_voucherfname':
		case $ext_name.'checkout_vouchermname':
		case $ext_name.'checkout_vouchersurname':
		case $ext_name.'checkout_voucherbuilding':
		case $ext_name.'checkout_voucherstreet':
		case $ext_name.'checkout_vouchercity':
		case $ext_name.'checkout_voucherstate':
		case $ext_name.'checkout_voucherzipcode':
		case $ext_name.'checkout_voucherphone':
		case $ext_name.'checkout_vouchermobile':
		case $ext_name.'checkout_voucherfax':
		case $ext_name.'checkout_voucheremail':
		

		case $ext_name.'checkoutdelivery_comp_name':
		case $ext_name.'checkoutdelivery_fname':
		case $ext_name.'checkoutdelivery_mname':
		case $ext_name.'checkoutdelivery_surname':
		case $ext_name.'checkoutdelivery_building':
		case $ext_name.'checkoutdelivery_address2':
		case $ext_name.'checkoutdelivery_street':
		case $ext_name.'checkoutdelivery_city':
		case $ext_name.'checkoutdelivery_state':
		case $ext_name.'checkoutdelivery_zipcode':
		case $ext_name.'checkoutdelivery_phone':
		case $ext_name.'checkoutdelivery_mobile':
		case $ext_name.'checkoutdelivery_fax':
		case $ext_name.'checkoutdelivery_email':
		
		case $ext_name.'customer_fname':
		case $ext_name.'customer_mname':
		case $ext_name.'customer_surname':
		case $ext_name.'customer_position':
		case $ext_name.'customer_buildingname':
		case $ext_name.'customer_streetname':
		case $ext_name.'customer_towncity':
		case $ext_name.'cbo_state':
		case $ext_name.'customer_postcode':
		/*case 'cbo_country':*/
		case $ext_name.'customer_phone':
		case $ext_name.'customer_mobile':
		case $ext_name.'customer_fax':
		
		case $ext_name.'customer_compname':
		case $ext_name.'customer_compregno':
		case $ext_name.'customer_compvatregno':

		case $ext_name.'checkoutpay_nameoncard':

		case $ext_name.'checkoutchq_number':
		case $ext_name.'checkoutchq_bankname':

		case $ext_name.'checkoutpay_cardnumber':
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';

		break;
		case $ext_name.'checkoutpay_issuenumber':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
		break;
		case $ext_name.'checkoutpay_securitycode':
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="'.$saved_checkoutvals[$key].'" '.$txt_cls.' />';
		break;
		case $ext_name.'customer_comptype':
			$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			$sql = "SELECT comptype_id,comptype_name
						FROM 
							general_settings_sites_customer_company_types 
						WHERE 
							sites_site_id=$ecom_siteid 
						AND 
							comptype_hide=0 
						ORDER BY 
							comptype_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				/*while ($row = $db->fetch_array($rets))
				{
					$key = $row['comptype_id'];
					$ret .= '<option value="'.$key.'">'.stripslashes($row['comptype_name']).'</option>';
				}*/
				while ($row = $db->fetch_array($rets))
				{
					$key1 = $row['comptype_id'];
					$selc='';
					if($saved_checkoutvals[$key]==$key1)
					{
						$selc = 'selected';
					}
					$ret .= '<option value="'.$key1.'" '.$selc.'>'.stripslashes($row['comptype_name']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_cardtype':
			/*if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card_voucher(this)">';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" onchange="sel_credit_card(this)">';*/
			
			if ($cur_form=='frm_buygiftvoucher') // case of gift voucher buying section
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';
			else
				$ret = '<select name="'.$key.'" id="'.$key.'" '.$select_cls.'>';	

			$ret .= "<option value=''>-- Select --</option>";
			
			$sql = "SELECT a.cardtype_key,a.cardtype_caption,a.cardtype_issuenumber_req,a.cardtype_securitycode_count,cardtype_numberofdigits,a.cardtype_paypalprokey 
					FROM
						payment_methods_supported_cards a,payment_methods_sites_supported_cards b
					WHERE
						b.sites_site_id = $ecom_siteid
						AND a.cardtype_id=b.payment_methods_supported_cards_cardtype_id
					ORDER BY
						b.supportcard_order";
			$rets = $db->query($sql);
			if ($db->num_rows($rets))
			{
				while ($row = $db->fetch_array($rets))
				{
					if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO')
						$key = $row['cardtype_paypalprokey'];
					else
						$key = $row['cardtype_key'];
					$ret .= '<option value="'.$key.'_'.$row['cardtype_issuenumber_req'].'_'.$row['cardtype_securitycode_count'].'_'.$row['cardtype_numberofdigits'].'">'.stripslashes($row['cardtype_caption']).'</option>';
				}
			}

			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_expirydate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			for($i=date('Y');$i<date('Y')+10;$i++)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case $ext_name.'checkoutpay_issuedate':
			$ret = '<select name="'.$key.'_month" id="'.$key.'_month" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=1;$i<=12;$i++)
			{
				if($i<10)
					$j = '0'.$i;
				else
					$j = $i;
				$ret .= "<option value='".$j."'>$j</option>";
			}
			$ret .= '</select>';
			$ret .="&nbsp;";
			$ret .= '<select name="'.$key.'_year" id="'.$key.'_year" '.$select_cls.'>';
			$ret .= "<option value=''>--</option>";
			for($i=date('Y');$i>date('Y')-20;$i--)
			{
				if($customer_arr['payment']['method']['paymethod_key']=='PAYPALPRO' or $customer_arr['from_giftvoucher']==1)
					$ret .= "<option value='".$i."'>".substr($i,-2)."</option>";
				else
					$ret .= "<option value='".substr($i,-2)."'>".substr($i,-2)."</option>";
			}
			$ret .= '</select>';
		break;
		case $ext_name.'checkoutchq_date':
			if ($txt_cls=='')
				$txt_cls = 'class="inputissue_normal"';
			$ret = '<input type="text" name="'.$key.'" placeholder="'.$field_name.'" id="'.$key.'" value="" '.$txt_cls.' />(e.g. 01-01-2008)';
		break;
		case $ext_name.'checkout_notes':
		case 'checkout_vouchernotes':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'>'.$saved_checkoutvals[$key].'</textarea>';
		break;
		case $ext_name.'checkoutchq_bankbranch':
			$ret = '<textarea name="'.$key.'" id="'.$key.'" rows="5" cols="25" '.$txtarea_cls.'></textarea>';
		break;
	};
	return $ret;
}
function paging_footer_advanced_responsive($path,$query_string,$numcount,$pg,$pages,$perpage,$pg_var,$page_type,$class_arr,$mod='')
{
    global $Captions_arr;
    $ret_arr    = array();
    $nav_arr    = array(); 
   // if ($pages<=1)
    //    return ;
    $ret_arr['total_cnt'] = "$numcount $page_type ".$Captions_arr['COMMON']['COMMON_FOUND'].". ".$Captions_arr['COMMON']['COMMON_PAGE']." <b>$pg</b> ".$Captions_arr['COMMON']['COMMON_OF']." <b>$pages</b>";
    if($numcount>1)
    {
        $ret_arr['navigation'] = pageNavApp_Advanced_responsive ($pg, $pages, $query_string,$class_arr,$perpage,$pg_var,$path,$mod);
    }
    return $ret_arr;
}
function pageNavApp_Advanced_responsive ($pagenum, $pages, $query_str,$class_arr,$perpage,$pg_var,$path,$mod='')
{
        global $Captions_arr;
        if($query_str)
        {
                if ($perpage)
                        $add = '&amp;'.$perpage;
                $a = "<a href='$path?$query_str$add&amp;$pg_var=";
        }
        else
        {
                if ($perpage)
                        $add = $perpage."&$pg_var=";
                else
                        $add = "$pg_var=";
                $a = "<a href='$path?$add";
        }
        $b = "'>";
        $c = "</a>";
        

        if ($pagenum == 1)
        {
            $nav_left = "<li class='nolinkright'>"; // init page nav string
            $nav_left .= $Captions_arr['COMMON']['COMMON_FIRST']."</li>";
			$nav_left .= "<li class='nolinkright'>"; // init page nav string
            $nav_left .= $Captions_arr['COMMON']['COMMON_PREV']."&nbsp;&nbsp;";
            $nav_left .= '</li>';
        }
        else 
        {
           $nav_left = "<li class='blacklinkleft'>"; // init page nav string
           $nav_left .= $a."1".$b.$Captions_arr['COMMON']['COMMON_FIRST'].$c."</li>";
		   $nav_left .= "<li class='blacklinkleft'>"; // init page nav string
           $nav_left .= $a.($pagenum - 1).$b.$Captions_arr['COMMON']['COMMON_PREV'].$c;
           $nav_left .= '</li>';
        }
        if ($pagenum == $pages)
        {
            $nav_right = "<li class='nolinkright'>"; // init page nav string
            $nav_right .= $Captions_arr['COMMON']['COMMON_NEXT'].'</li>';
			$nav_right .= "<li class='nolinkright'>"; // init page nav string
            $nav_right .= $Captions_arr['COMMON']['COMMON_LAST'];
            $nav_right .= '</li>';
        }
        else 
        {
            $nav_right = "<li class='blacklinkright'>"; // init page nav string
            $nav_right .= $a.($pagenum +1).$b.$Captions_arr['COMMON']['COMMON_NEXT'].$c."</li>";
			$nav_right .= "<li class='blacklinkright'>"; // init page nav string
            $nav_right .= $a.($pages).$b.$Captions_arr['COMMON']['COMMON_LAST'].$c."<br/>";
            $nav_right .= '</li>';
        }
        //$nav_middle .= '<li class="blacklink">';
			if($mod=='resp')
			{
				$nav_middle .= makeNavApp_advanced_responsive ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path,$mod);

			}
			else
			{
				$nav_middle .= makeNavApp_advanced ($pages, $pagenum, $query_str, $javascript_fn,1,$class_arr,$perpage,$pg_var,$path);

			}
        //$nav_middle .= '</li>';
        $nav['start_nav']       = $nav_left;
        $nav['page_no']         = $nav_middle;
        $nav['end_nav']         = $nav_right;
        return $nav;
}
function makeNavApp_advanced_responsive ($pages, $pagenum, $query_str='', $nav = "", $mag = 1,$class_arr,$perpage,$pg_var,$path,$mod='') 
{ 
	global $theme_folder,$Captions_arr;
	global $ecom_selfhttp;
	$n = 5; // Number of pages or groupings
	$m = 10; // Order of magnitude of groupings
	//$a = "<a href='$query_str&amp;pg=";
	//$b = "'>";
	//$c = "</a>\n";
	if($mod=='resp')
	{
	 $sel_class = 'active';
	}
	else
	{
	 $sel_class = 'redlink';
	}
	if ($pages<=1)
		return;
	if($query_str) {
		if ($perpage)
				$add = '&amp;'.$perpage;
		$a = "<li class='blacklink'><a href='$path?$query_str$add&amp;$pg_var=";
	} else {
		if ($perpage)
				$add = $perpage."&amp;$pg_var=";
			else
				$add = "$pg_var=";
		$a = "<li class='blacklink'><a href='$path?$add";
	}
	$b = "'>";
	$c = "</a></li>";
	if ($mag == 1) {
		// single page level
		$minpage = (ceil ($pagenum/$n) * $n) + (1-$n);
		for ($i = $minpage; $i < $pagenum; $i++) {
			if ( isset($nav[1]) ) {
				$nav[1] .= $a.($i).$b;
			} else {
				$nav[1] = $a.($i).$b;
			}
			$nav[1] .= "$i ";
			$nav[1] .= $c;
		}
		if ( isset($nav[1]) ) {
			$nav[1] .= "<li class='".$sel_class."'>$pagenum</li>";
		} else {
			$nav[1] = "<li class='".$sel_class."'>$pagenum</li>";
		}
		$maxpage = ceil ($pagenum/$n) * $n;
		if ( $pages >= $maxpage ) {
			for ($i = ($pagenum+1); $i <= $maxpage; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			//$nav[1] .= "<br />";
		} else {
			for ($i = ($pagenum+1); $i <= $pages; $i++) {
				$nav[1] .= $a.($i).$b;
				$nav[1] .= "$i";
				$nav[1] .= $c;
			}
			//$nav[1] .= "<br />";
		}
		if ( $minpage > 1 || $pages > $n ) {
			// go to next level
			$nav = makeNavApp_advanced_responsive ($pages, $pagenum, $query_str, $nav, $n,$class_arr,$perpage,$pg_var,$path,$mod);
		}
		// Construct outgoing string from pieces in the array
		$out = $nav[1];
		for ($i = $n; isset ($nav[$i]); $i = $i * $m) {
			if (isset($nav[$i][1]) && isset($nav[$i][2])) {
				$out = $nav[$i][1].$out.$nav[$i][2];
			} else if (isset($nav[$i][1])) {
				$out = $nav[$i][1].$out;
			} else if (isset($nav[$i][2])) {
				$out = $out.$nav[$i][2];
			} else {
				$out = $out;
			}
		}
		return $out;
	}
	$minpage = (ceil ($pagenum/$mag/$m) * $mag * $m) + (1-($mag * $m));
	$prevpage = (ceil ($pagenum/$mag) * $mag) - $mag; // Page # of last pagegroup before pagenum's page group
	if ( $prevpage > $minpage ) {
		for ($i = ($minpage - 1); $i < $prevpage; $i = $i + $mag) {
			if (isset($nav[$mag][1])) {
				$nav[$mag][1] .= $a.($i+1).$b;
			} else {
				$nav[$mag][1] = $a.($i+1).$b;
			}
			//$nav[$mag][1] .= $a.($i+1).$b;
			$nav[$mag][1] .= "[".($i+1)."-".($i+$mag)."]";
			$nav[$mag][1] .= $c;
		}
		//$nav[$mag][1] .= "<br />";
	} // Otherwise, it's this page's group, which is handled the mag level below, so skip
	$maxpage = ceil ($pagenum/$mag/$m) * $mag * $m;
	/*if ( $pages >= $maxpage ) {
		// If there are more pages than we are accounting for here
		$nextpage = ceil ($pagenum/$mag) * $mag;
		if ($maxpage > $nextpage) {
			for ($i = $nextpage; $i < $maxpage; $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= $a.($i+1).$b;
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			//$nav[$mag][2] .= "<br />";
		}
	} else {
		// This is the end
		if ( $pages >= ((ceil ($pagenum/$mag) * $mag) + 1) ) {
			// If there are more pages than just this page's group
			for ($i = (ceil ($pagenum/$mag) * $mag); $i < ($pages-$mag); $i = $i + $mag) {
				if (isset($nav[$mag][2])) {
					$nav[$mag][2] .= $a.($i+1).$b;
				} else {
					$nav[$mag][2] = $a.($i+1).$b;
				}
				$nav[$mag][2] .= "[".($i+1)."-".($i+$mag)."]";
				$nav[$mag][2] .= $c;
			}
			$nav[$mag][2] .= $a.($i+1).$b;
			$nav[$mag][2] .= "[".($i+1)."-".$pages."]";
			$nav[$mag][2] .= $c;
			//$nav[$mag][2] .= "<br />";
		}
	}
	*/ 
	if ( $minpage > 1 || $pages >= $maxpage ) {
		$nav = makeNavApp_advanced_responsive ($pages, $pagenum, $query_str, $nav, $mag * $m,$class_arr,$perpage,$pg_var,$path,$mod);
	}
	return $nav;
}
function show_addtocart_responsive($frm,$prod_arr,$class_arr,$showqty,$mod,$return=false,$isbutton = false,$respmod='',$frompg='',$type='submit')
	{  
		global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
		// Getting the values of captions from the common caption table
		$addtocart_cap 			= $Captions_arr['COMMON']['ADD_TO_CART'];
		$enquire_cap			= $Captions_arr['COMMON']['ENQUIRE'];
		$preorder_cap			= $Captions_arr['COMMON']['PREORDER'];
		$show_only_on_login 	= $Settings_arr['hide_addtocart_login'];
		$mod					= '';
		$curtype				= '';
		$addto_cart_withajax    = $Settings_arr['enable_ajax_in_site'];//checking for the ajax function for adding to cart is enabled or not

		//to sheck whether quantity box should be shown or not
		//$showqty		= $Settings_arr['show_qty_box'];
		$quantity_box_display   = false;
		
       

		        $quantity_div_class     = ($class_arr['QTY_DIV']!='')?$class_arr['QTY_DIV']:'quantity';  
                $quantity_class         = ($class_arr['QTY']!='')?$class_arr['QTY']:'quainput';     

		//echo "herer".$showqty.' - '.$override_hideqty;
		if($showqty!=0)
		{
			        
			  	$quantity_box  = '<div class="'.$quantity_div_class.'">'.$Captions_arr['COMMON']['COMMON_QTY'].'<input type="text" class="'.$quantity_class.'" name="qty"  value="1" /></div>';
										 
		}
		if($override_hideqty==1)
		{
			$quantity_box  = '<input type="hidden" class="quainput" name="qty"  value="1" />';
		}
		// Check whether add to cart should be hidden when not logged in
		if ($show_only_on_login==1)
		{
			// Get the customer id from the session
			$cust_id 			= get_session_var("ecom_login_customer");
			if (!$cust_id)
				return;
		}
		$show_buy_now = false;
		$variable_check_forajax = false;//to check whether there is a variable exists for the product
		$var_exists 			= false; 
		if($prod_arr['product_variablestock_allowed']=='Y') // case if variable stock exists. if variable stock exists then variables exists
		{  
			$var_exists = true;
			$variable_check_forajax = true;
			// Call function to check whether there exists stock for atleast one variable combination for current product
			$stock = get_atleastone_variablestock($prod_arr['product_id']);
			if ($return==true and $stock==0 and $prod_arr['product_stock_notification_required']=='Y')// if stock notification is set to y and also in variable stock and if no stock then the buy button is to be displayed. so this condition is used
				$show_buy_now = true;
			// Check whether stock exists
			if($stock > 0 or $prod_arr['product_alloworder_notinstock']=='Y' or $show_buy_now==true)
			{
				if($prod_arr['product_show_cartlink']==1)// if show cart link is set to display
				{				
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					    $quantity_box_display = true;					
					
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
					if($_REQUEST['req']=='prod_detail' && $frompg=='prod_det')
					{
					$curtype	= 'Prod_Addcart';
					}
					else
					{
					  $curtype	= '';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
			else // case stock does not exists
			{ 
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						    $link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';

					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$curtype	= 'Prod_Enquire';					
					$mod		= '';
				}
			}
		}
		else // case if variable stock does not exists
		{	
			if($prod_arr['product_variables_exists']=='Y')
			{
				$var_exists = true;
				$variable_check_forajax = true;	//this is for checking for variable exists for ajax enabled cart adding
			}
			else
			{
				$var_exists = false;	
				$variable_check_forajax = false;				
			}
			
			// Check whether stock exists and
			if($prod_arr['product_webstock']>0  or $prod_arr['product_alloworder_notinstock']=='Y')
			{				
				if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
				{
					if ($var_exists){ 
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
						if($_REQUEST['req']=='prod_detail' && $frompg=='prod_det')
						{  
							$curtype	= 'Prod_Addcart';
						}
						else
						{
						   $curtype	= '';
						}  

					}
					else{
						$link 		= "javascript:submit_form('".$frm."','Prod_Addcart','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Addcart';
					}
					$caption	= $addtocart_cap;
					$class		= $class_arr['ADD_TO_CART'];
					$mod		= 'PRODDET_BUYNOW';
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
			else // case stock does not exists
			{ 
				// Check whether preorder is allowed
				if ($prod_arr['product_preorder_allowed']=='Y' and $prod_arr['product_total_preorder_allowed']>0)
				{
					if($prod_arr['product_show_cartlink']==1)// show preorder link only if show cart link is set to display
					{
						if ($var_exists){
							$link = "javascript:submit_to_det_form('".$frm."')";
							$quantity_box_display = true;
						}
						else{
							$link 		= "javascript:submit_form('".$frm."','Prod_Preorder','".$prod_arr['product_id']."')";
							$quantity_box_display = true;
							$curtype	= 'Prod_Preorder';
						}
						$curtype	= 'Prod_Preorder';						
						$caption	= $preorder_cap;
						$class		= $class_arr['PREORDER'];
						$mod		= 'PRODDET_PREORDER';
					}
				}
				elseif ($prod_arr['product_show_enquirelink'])// Check whether enquire link is set to display
				{
					if ($var_exists){
						$link = "javascript:submit_to_det_form('".$frm."')";
						$quantity_box_display = true;
					}
					else{
						$link		= "javascript:submit_form('".$frm."','Prod_Enquire','".$prod_arr['product_id']."')";
						$quantity_box_display = true;
						$curtype	= 'Prod_Enquire';
					}
					$curtype	= 'Prod_Enquire';					
					$caption	= $enquire_cap;
					$class		= $class_arr['ENQUIRE'];
					$mod		= '';
				}
			}
		}
		if ($return==true)
		{
			return $mod;
		}	
		elseif ($link)
		{	
		if($addto_cart_withajax==1)
		{ 
			$link ="";
			if($_REQUEST['req']!='prod_detail')
			{
		   ?>
		   	<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		    <?php
			}
		    ?>
			<input type='hidden' name='fproduct_id' value="<?=$prod_arr['product_id']?>"/>
		   	<input type='hidden' name='product_id' value="<?=$prod_arr['product_id']?>"/>
			<input type='hidden' name='site_url' value="<?=SITE_URL?>" id="site_url<?=$prod_arr['product_id']?>"/>
			 <input type='hidden' name='curtype' id='curtype<?=$prod_arr['product_id']?>' value="<?=$curtype ?>"/>

			
			<input type="hidden" id="product_id_ajax<?=$prod_arr['product_id']?>" name="product_id" value="<?=$prod_arr['product_id']?>" />
			<input type='hidden' name='ajaxform_name' id="ajaxform_name" value="<?=$frm?>"/>
			

		   <?php
		    if ($variable_check_forajax==true){					 
			$link  ="responsive_addto_cart_fromlist('show_prod_det_ajax','".$curtype."','".$frm."','".SITE_URL."')";
//echo $_REQUEST['req']."--here--".$frompg;
			if($_REQUEST['req']=='prod_detail' && $frompg=='prod_det')
			{ 
			?>			
			<input type='hidden' name='mod' id='mod<?=$prod_arr['product_id']?>' value="add_prod_tocart_ajax"/>
			<?php	
			}
			else
				{ 
			        if($ecom_siteid==112 || $ecom_siteid==105)
					{
					  $show_submitbut = 1;
					}
			?>
						<input type='hidden' name='mod' id='mod<?=$prod_arr['product_id']?>' value="show_prod_det_ajax"/>

			<?php
				}
				
			}
			else
			{			
	
			//$btn_box  ='<td align="left" valign="middle" class="'.$class_btn.'"> <a href="'.$link.'" title="'.$caption.'" class="'.$class.'"><input name="'.$caption.'" type="button" /></a></td>';
			$link  ="responsive_addto_cart_fromlist('add_prod_tocart_ajax','".$curtype."','".$frm."','".SITE_URL."')";
			?>
			 <input type='hidden' name='mod' id='mod<?=$prod_arr['product_id']?>' value="add_prod_tocart_ajax"/>

			<?php
			}
	    }
	    else
	    {
			if ($var_exists==true){					 
				$link = "javascript:submit_to_det_form('".$frm."')";
			}
			else
			{		
				$link = "javascript:submit_form('".$frm."','".$curtype."','".$prod_arr['product_id']."')";
			}
		   
		}
		
		$outer_cont = "";
		
		

		    $outer_cont        = '';
		    if($class_arr['BTN_CLS']!='')
		    {
				$class_btn = $class_arr['BTN_CLS'];
		    			
		    }
			else
			{
				$btn_box ='';
			    $btn_box_bottom    = '';
			}
			$outer_cont_bottom = '';

		$show_but ='';
		if($isbutton == true)
		{
			$show_but =  '<input value="'.$caption.'" name="'.$caption.'" class="btncart '.$class_btn.'" type="'.$type.'"  id="'.$prod_arr['product_id'].'" />';			
		}
		else
		{ 
			 $check_arr = is_grid_display_enabled_prod($prod_arr['product_id']);
				if($check_arr['enabled']==false)
				{
					
					$show_but =  '<a href="javascript:void(0);" onclick="'.$link.'" title="'.$caption.'" class="'.$class.'" >'.$caption.'</a>'; 
				}
				else
				{
					$show_but =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" onclick="" title="'.$caption.'" class="'.$class.'">'.$caption.'</a>'; 

				}	
		}	
			$show_but .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		    echo $outer_cont;
			 if($respmod=='new')
			 { 
				 $show_but_new ='';
				 if($check_arr['enabled']==false)
				{
					$show_but_new =  '<a href="javascript:void(0);" title="'.$caption.'" class=" btncart '.$class.'" id="'.$prod_arr['product_id'].'">'.$caption.'</a>';
				} 
				else
				{
				   $show_but_new =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" title="'.$caption.'" class=" btncart '.$class.'" id="'.$prod_arr['product_id'].'">'.$caption.'</a>';
				}
			  			//$show_but_new =  '<div id="'.$prod_arr['product_id'].'" class="'.$class.'"><input value="'.$caption.'" name="'.$caption.'" class=" btncart '.$class.'" type="submit"  id="'.$prod_arr['product_id'].'" /></div>';	
			  	$show_but_new .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		

			  $class_btn_new = $class_arr['BTN_CLS'];
			
			  echo "<div class=\"".$class_btn_new."\">";	 
			  if($quantity_box_display)
			  {
				
				$quantity_box_new  = '<input type="text" class="'.$quantity_class.'" name="qty" placeholder="qty" value="1" />';	
				
			  }
			  else
			  {
				$quantity_box_new  = '<input type="hidden" name="qty" value="1" />';

			  }
			   echo $quantity_box_new;
			   echo $show_but_new;
			   echo "</div>";
			   
			  
			 }
			 else if($respmod=='new_v2') 
			 {
				  $show_but_new ='';
				 if($check_arr['enabled']==false)
				{
					$show_but_new =  '<a href="javascript:void(0);" title="'.$caption.'" class=" btncart addcartbtn '.$class.'" id="'.$prod_arr['product_id'].'">'.$caption.'</a>';
				} 
				else
				{
				   $show_but_new =  '<a href="'.url_product($prod_arr['product_id'],$prod_arr['product_name'],1).'" title="'.$caption.'" class=" btncart '.$class.'" id="'.$prod_arr['product_id'].'">'.$caption.'</a>';
				}
			  			//$show_but_new =  '<div id="'.$prod_arr['product_id'].'" class="'.$class.'"><input value="'.$caption.'" name="'.$caption.'" class=" btncart '.$class.'" type="submit"  id="'.$prod_arr['product_id'].'" /></div>';	
			  	$show_but_new .='<input type="hidden" name="prod_list_submit_common" value="'.$curtype.'" />';
		

			  $class_btn_new = $class_arr['BTN_CLS'];
			  if($quantity_box_display)
			  {
				
				$quantity_box_new  = '<input type="text" class="'.$quantity_class.'" name="qty" placeholder="QTY" aria-label="QTY" value="1" aria-describedby="basic-addon2"/>';	
				
			  }
			  else
			  {
				$quantity_box_new  = '<input type="hidden" name="qty" value="1" />';

			  }
			        if($ecom_siteid==112 || $ecom_siteid==105)
					{
					  $link_submit = "javascript:submit_to_det_form_button('".$frm."')";
					  if($show_submitbut == 1)
					  {
						  echo $quantity_box_new;
					     ?>
					      <button type="button" class="addcartbtn btn btn-outline-secondary addbt" onclick="<?php echo $link_submit ?>">ADD TO CART</button>
					     
					     <?php
					  }
					  else
					  {
							echo $quantity_box_new;
							echo $show_but_new;


					  }
					}
					else
					{
						echo $quantity_box_new;
						echo $show_but_new;
					}

			   ?>
			   <?php 
			 }
			 else
			 {
			  if($quantity_box_display)
			   echo $quantity_box;
			   echo $show_but;
			   echo $outer_cont_bottom;
			  } 
					
			
		    
		}
	}
	function curprice_tax($price_arr = array(),$row_prod=array())
	{
	global $db,$ecom_hostname,$Captions_arr,$Settings_arr,$ecom_siteid;
	if($price_arr['prince_without_captions']['discounted_price'])
				{
					/*$was_price = $price_arr['prince_without_captions']['base_price'];
					$cur_price = $price_arr['prince_without_captions']['discounted_price'];*/
					
					$ss_arr = array('+ VAT','&pound;');
					$rps_arr = array('','');
					$holdwas_price = str_replace($ss_arr,$rps_arr,$price_arr['prince_without_captions']['base_price']);
					$holdcur_price = str_replace($ss_arr,$rps_arr,$price_arr['prince_without_captions']['discounted_price']);
					
					$was_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
					$cur_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['discounted_price']);
					$cur_price_micro = $cur_price;
					
					$holdcur_price = str_replace('£', '', $holdcur_price);

					$cur_price_tax= '';
					if($holdcur_price and $row_prod['product_applytax']=='Y')
					{
						$cur_price_tax = $holdcur_price + ($holdcur_price*20/100);
						//$cur_price_tax = sprintf('%0.2f',round($cur_price_tax,2));
						$cur_price_tax = sprintf('%0.2f',($cur_price_tax));
						$cur_price_micro = $cur_price_tax;
						$cur_price_tax = '<span class=\"vat\">( Inc VAT &pound;'.$cur_price_tax.' )</span>';
						
					}
					else
					{
						$cur_price_tax = " <span class=\"vat\">(vat exempt)</span>";
					}
					
					
					if($price_arr['prince_without_captions']['disc_percent'])
						$sav_price = $price_arr['prince_without_captions']['disc'];
					else
						$sav_price = $price_arr['prince_without_captions']['yousave_price'];
				}
				else
				{
					/*$was_price = '';
					$cur_price = $price_arr['prince_without_captions']['base_price'];
					$sav_price = '';*/
					
					$ss_arr = array('+ VAT','&pound;');
					$rps_arr = array('','');
					$was_price = '';
					$holdcur_price = str_replace($ss_arr,$rps_arr,$price_arr['prince_without_captions']['base_price']);
					$cur_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
					$cur_price_micro = $cur_price;
					$holdcur_price = str_replace('£', '', $holdcur_price);
					$sav_price = '';
					$cur_price_tax= '';
					if($holdcur_price and $row_prod['product_applytax']=='Y')
					{
						$cur_price_tax = $holdcur_price + ($holdcur_price*20/100);
						//$cur_price_tax = sprintf('%0.2f',round($cur_price_tax,2));
						$cur_price_tax = sprintf('%0.2f',($cur_price_tax));
						$cur_price_micro = $cur_price_tax;
						$cur_price_tax = '<span class=\"vat\">( Inc VAT &pound;'.$cur_price_tax.' )</span>';
					}
					else
					{
						$cur_price_tax = " <span class=\"vat\">(vat exempt)</span>";
					}
					
					  
				}
return $cur_price_tax;
}
?>
