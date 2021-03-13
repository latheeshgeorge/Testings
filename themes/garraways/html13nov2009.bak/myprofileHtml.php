<?php
/*############################################################################
	# Script Name 	: myprofileHtml.php
	# Description 	: Page which holds the display logic for Editing a customer(My profiel)
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myprofile_Html
	{
		// Defining function to show the site review
		function Show_Myprofile($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$short,$long,$medium;
			
			//Get the list of all countries for this site for which state is added
			$sql_country = "SELECT a.country_id,a.country_name 
							FROM 
								general_settings_site_country a 
							WHERE 
								a.sites_site_id = $ecom_siteid
							AND
							    a.country_hide=1 
							ORDER BY country_name";
			$ret_country = $db->query($sql_country);
			$country_arr = array(0=>'-- Select Country --');
			if ($db->num_rows($ret_country)){
				while ($row_country = $db->fetch_array($ret_country))	{
					$country_id 				= $row_country['country_id'];
					$country_name 				= stripslashes($row_country['country_name']);
					$country_arr[$country_id] 	= $country_name;		
					//Get the list of states under this country
					/*$sql_state = "SELECT *
								  FROM 
								  	general_settings_site_state 
								  WHERE 
								  	sites_site_id=$ecom_siteid 
									AND 
									general_settings_site_country_country_id=$country_id
									AND
									state_hide=1";
					$ret_state = $db->query($sql_state);
					$statehold_arr = array(-1=>$Captions_arr['CUST_REG']['OTHER_STATE']);
					if ($db->num_rows($ret_state)){
						while ($row_state = $db->fetch_array($ret_state)){	
							$state_id					= $row_state['state_id'];
							$state_name					= stripslashes($row_state['state_name']);
							$statehold_arr[$state_id] 	= $state_name;
						}
						$countrystate_arr[$country_id] = $statehold_arr;
					}
					else
						$countrystate_arr[$country_id] = $statehold_arr;*/
					}
				/*
			//Building the javascript array for state to be shown based on the selected country
				/*echo "<script>";
				foreach ($countrystate_arr as $k=>$v){
					$arrvalname = 'countryval'.$k;
					$arrkeyname	= 'countrykey'.$k;
					echo "var $arrkeyname = new Array();var $arrvalname = new Array();";
					$ii = 0;
					foreach ($v as $kk=>$vv){
						echo "
						$arrkeyname"."[$ii] ='".$kk."';
						$arrvalname"."[$ii]  ='".$vv."';
						";
						$ii++;
					}	
				}
				echo "</script>";*/
				
			}

$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
 $Captions_arr['PAYONACC'] = getCaptions('PAYONACC'); 
/*			$Topparameters =  getParameters_DynamicFormAdd('Top','register'); // to get the feild and the error messages for the dynamic form added on the top
			if($Topparameters[2]){
				$topstr =  array_keys($Topparameters[2]);
				$topmsg =  array_values($Topparameters[2]);
			}
			if($Topparameters[0] || $Topparameters[1]){
				$checkboxfld_arrTop = $Topparameters[0];
				$radiofld_arrTop = $Topparameters[1];
			}
			$Bottomparameters =  getParameters_DynamicFormAdd('Bottom','register');  // to get the feild and the error messages for the dynamic form added at the bottom
			if($Bottomparameters[2]){
				$bottomstr =  array_keys($Bottomparameters[2]);
				$bottommsg =  array_values($Bottomparameters[2]);
			}
			if($Bottomparameters[0] || $Bottomparameters[1]){
				$checkboxfld_arrBottom = $Bottomparameters[0];
				$radiofld_arrBottom = $Bottomparameters[1];
			}
			*/
			$customer_id = get_session_var('ecom_login_customer');
			$sql_customer	= "SELECT * FROM customers  WHERE customer_id=".$customer_id." LIMIT 1";
			$res_customer	= $db->query($sql_customer);
			$row_customer 	= $db->fetch_array($res_customer);

?>
<script language="javascript" type="text/javascript">
function showstate(cid)
{
	arrval = eval('countryval'+cid);
	arrkey = eval('countrykey'+cid);
	
	for(i=document.frm_myprofile.cbo_state.options.length-1;i>0;i--)
	{
		 document.frm_myprofile.cbo_state.remove(i);
	}
	for(i=0;i<arrkey.length;i++)
	{
		var lgth = document.frm_myprofile.cbo_state.options.length;
		document.frm_myprofile.cbo_state.options[lgth]= new Option(arrval[i],arrkey[i]);
	}
}
		
/* Function to validate the Customer Registration */
function validate_defaultregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array('customer_title','customer_fname','customer_phone','customer_postcode','customer_email');
	fieldDescription 	= Array('<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_TITLE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_FIRSTNAME']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_PHONE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_POSTCODE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_EMAIL']?>');
	fieldEmail 			= Array('customer_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	fieldSpecChars 		= Array('customer_fname','customer_mname','customer_surname','customer_phone');
	fieldCharDesc       = Array('First Name','Second Name','Sur Name','Phone');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
		if(frm.customer_pwd.value==frm.customer_pwd_cnf.value){
			return true;
		}else{
			alert("Password and Confirm password should be the same");
			return false;
		}
	}
	else
	{
		return false;
	}
}

/* Function to validate the Customer Registration */
function validate_Topregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$topstr[0]?>);
	fieldDescription 	= Array(<?=$topmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php

	// Logic to build the dynamic field validation
	if(count($checkboxfld_arrTop)){
		$ptr = 0;
		foreach ($checkboxfld_arrTop as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			//echo "for (var i=0, i < document.frmEditCustomer.elements.length; i++) {";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrTop)){
		$ptr = 0;
		foreach ($radiofld_arrTop as $k=>$v) {
			echo "checkvalue='';";
			//	echo "alert(document.frm_myprofile.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
					//echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}
/* Function to validate the Customer Registration */
function validate_Topregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$topstr[0]?>);
	fieldDescription 	= Array(<?=$topmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php

	// Logic to build the dynamic field validation
	if(count($checkboxfld_arrTop)){
		$ptr = 0;
		foreach ($checkboxfld_arrTop as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			//echo "for (var i=0, i < document.frmEditCustomer.elements.length; i++) {";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrTop)){
		$ptr = 0;
		foreach ($radiofld_arrTop as $k=>$v) {
			echo "checkvalue='';";
			//	echo "alert(document.frm_myprofile.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
					 if (frm.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = frm.$k"."[i]".".value;
    		 		 	break;
					 }
				 }";
				 echo "if(checkvalue==''){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
					//echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}
/* Function to validate the Customer Registration */
function validate_Bottomregistration(frm)
{
	
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$bottomstr[0]?>);
	fieldDescription 	= Array(<?= $bottommsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
	<?php
		// Logic to build the dynamic field validation
		
	if(count($checkboxfld_arrBottom)){
		$ptr = 0;
		foreach ($checkboxfld_arrBottom as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			//echo "for (var i=0, i < document.frmEditCustomer.elements.length; i++) {";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrBottom)){
		$ptr = 0;
		foreach ($radiofld_arrBottom as $k=>$v) {
			echo "checkvalue='';";
		//	echo "alert(document.frm_myprofile.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
  					 if (frm.$k"."[i]".".checked) {
   				  	 	 var checkvalue = frm.$k"."[i]".".value;
    				 	 break;
						 }
					 }";
					 echo "if(checkvalue==''){";
					 echo "alert('".$v."');";
					 echo "return false;
				 }";
							 //echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}

/* Function to validate the Customer Registration */
function validate_BottomInStaticregistration(frm)
{
	
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$bottomstr[0]?>);
	fieldDescription 	= Array(<?= $bottommsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
	<?php
		// Logic to build the dynamic field validation
		
	if(count($checkboxfld_arrBottomInStatic)){
		$ptr = 0;
		foreach ($checkboxfld_arrBottomInStatic as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
			//echo "for (var i=0, i < document.frmEditCustomer.elements.length; i++) {";
			echo "for(var i=0; i < frm.elements.length; i++) {;
						var el = frm.elements[i];";
					//echo "alert(el);";
					echo " if(el.type == 'checkbox' && el.name == '$k"."[]'"." && el.checked) {
					 	retval_$k.push(el.value);
					 };";
				 echo "}";
				 //echo "alert(retval_$k.length);";
				 echo "if(!retval_$k.length){";
				 echo "alert('".$v."');";
				 echo "return false;
					   }";
		
					//echo "return true;";
		 }
	}
	// case of radio button
	if(count($radiofld_arrBottomInStatic)){
		$ptr = 0;
		foreach ($radiofld_arrBottomInStatic as $k=>$v) {
			echo "checkvalue='';";
		//	echo "alert(document.frm_registration.$k.length);";
			echo "for (i=0, n=frm.$k.length; i<n; i++) {
  					 if (frm.$k"."[i]".".checked) {
   				  	 	 var checkvalue = frm.$k"."[i]".".value;
    				 	 break;
						 }
					 }";
					 echo "if(checkvalue==''){";
					 echo "alert('".$v."');";
					 echo "return false;
				 }";
							 //echo "return true;";
		 }
	}
		?>	
	return true;
	}
	else
	{
		return false;
	}
}

function validate_allforms(form){
	topfrm =  validate_Topregistration(form);
	if(topfrm){
		defalutfrm = validate_defaultregistration(form);
		if(defalutfrm){
			bottomfrm =  validate_Bottomregistration(form);
			<?php // if($Settings_arr['imageverification_req']){ // code for validating the image verification- needs only if it is enabled
		//	echo "if(bottomfrm){";
			//	echo "if(form.registration_Vimg.value==''){
			//		alert('Enter- verification Code');
			//		return false;
			//	}else{
			//		return true;
			//	}
			//}";
		//}
		?>
		return bottomfrm;
		}else{
			return false;
		}
	}else{
		return topfrm;
	}
}
function maillinglist_onchange(obj)
{
	if(obj.checked==false)
	{
		for(i=0;i<document.frm_myprofile.elements.length;i++)
		{
			if (document.frm_myprofile.elements[i].type =='checkbox' && document.frm_myprofile.elements[i].name.substr(0,14)=='newsletergroup')
			{ 
				document.frm_myprofile.elements[i].checked = false;
			}
		}	
	}
}
function mailinglist_mainsel()
{
	var atleast_one = false;
	for(i=0;i<document.frm_myprofile.elements.length;i++)
	{
		if (document.frm_myprofile.elements[i].type =='checkbox' && document.frm_myprofile.elements[i].name.substr(0,14)=='newsletergroup')
		{ 
			if(document.frm_myprofile.elements[i].checked==true)
				atleast_one = true;
		}
	}	
	if(atleast_one)
		document.frm_myprofile.customer_in_mailing_list.checked = true;
	
}	

		</script>
			<form method="post" action="" name="frm_myprofile" id="frm_myprofile" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>>> <?=$Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']?></div>
		
		<table width="100%" border="0" cellpadding="0" cellspacing="8"  class="regitable">
		<?php if($alert){ ?>
			<tr>
				<td colspan="2" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['CUST_REG'][$alert]){
						  		echo "Error !! ". $Captions_arr['CUST_REG'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>				</td>
			</tr>
		<?php } 
		//section for custom registration form 
		
		$cur_pos = 'Top';
		$formname = 'frm_myprofile';
		include 'show_dynamic_fields_myprofile.php';
		?>
	
		<?PHP
			//section for custom registration form 
			$cur_pos = 'TopInStatic';
			$formname = 'frm_myprofile';
			include 'show_dynamic_fields_myprofile.php';
		?>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE']?></td>
			<td width="57%" align="left" valign="middle"><select name="customer_accounttype" class="regiinput" id="customer_account_type" onchange="showAccountTypeDetails(this);" >
			<option value="personal" <?=($row_customer['customer_accounttype']=='personal')?"selected":''?>><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL']?></option>
			<option value="business" <?=($row_customer['customer_accounttype']=='business')?"selected":''?>><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS']?></option> 
			</select></td>
		</tr>
		<tr  id="companydetails" style="display:none;">
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="2" >
					<tr>
						<td colspan="2" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER_EDIT']?> </td>
					</tr>
					<tr>
						<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_NAME']?></td>
					  <td width="57%" align="left" valign="middle"><input name="customer_compname" type="text" class="regiinput" id="customer_compname" size="25" value="<?=$row_customer['customer_compname']?>"  maxlength="<?=$short?>"/>
					  <input type="hidden" name="customer_comptype" id="customer_comptype" value="<?php echo $row_customer['customer_comptype']?>" />
					  </td>
					</tr>
					<?php /*$sql_selcompany_types = "SELECT comptype_id,comptype_name
														FROM general_settings_sites_customer_company_types WHERE sites_site_id=$ecom_siteid AND comptype_hide=0 ORDER BY comptype_order";
							$ret_company_type = $db->query($sql_selcompany_types);
							while($company_type= $db->fetch_array($ret_company_type)){
								$companytype_id[] = $company_type['comptype_id'];
								$companytype_name[$company_type['comptype_id']] = $company_type['comptype_name'];
							}*/
														?>
					<?php /*?><tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_TYPE']?></td>
						<td align="left" valign="middle"><?=generateselectbox('customer_comptype',$companytype_name,$row_customer['customer_comptype']);?></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_REGNO']?></td>
						<td align="left" valign="middle"><input name="customer_compregno" type="text" class="regiinput" id="customer_compregno" size="25" value="<?=$row_customer['customer_compregno']?>"  maxlength="<?=$short?>"/></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_VATREGNO']?></td>
						<td align="left" valign="middle"><input name="customer_compvatregno" type="text" class="regiinput" id="customer_compvatregno" size="25" value="<?=$row_customer['customer_compvatregno']?>" maxlength="<?=$short?>"/></td>
					</tr><?php */?>
				</table>
			</td> 
		</tr>
		<tr>
			<td colspan="2" class="regiheader">
			<?=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER_EDIT']?>
			
			</td>
		</tr>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TITLE']?> <span class="redtext">*</span></td>
			<td width="57%" align="left" valign="middle"><select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($row_customer['customer_title']=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($row_customer['customer_title']=='Mrs.')?"selected":''?>>Mrs.</option> 
			<option value="M/S." <?=($row_customer['customer_title']=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FNAME']?> <span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_fname" type="text" class="regiinput" id="customer_fname" size="25" value="<?=$row_customer['customer_fname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_MNAME']?></td>
			<td align="left" valign="middle">
		<input name="customer_mname" type="text" class="regiinput" id="customer_mname" size="25" value="<?=$row_customer['customer_mname']?>"  maxlength="<?=$short?>"/>   </td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_SURNAME']?> </td>
			<td align="left" valign="middle"><input name="customer_surname" type="text" class="regiinput" id="customer_surname" size="25" value="<?=$row_customer['customer_surname']?>"  maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSITION']?></td>
			<td align="left" valign="middle"><input name="customer_position" type="text" class="regiinput" id="customer_position" size="25" value="<?=$row_customer['customer_position']?>"  maxlength="<?=$short?>"/></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_BUILDING_NAME']?></td>
			<td align="left" valign="middle"><input name="customer_buildingname" type="text" class="regiinput" id="customer_buildingname" size="25" value="<?=$row_customer['customer_buildingname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STREET_NAME']?></td>
			<td align="left" valign="middle"><input name="customer_streetname" type="text" class="regiinput" id="customer_streetname" size="25" value="<?=$row_customer['customer_streetname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TOWN_CITY']?></td>
			<td align="left" valign="middle"><input name="customer_towncity" type="text" class="regiinput" id="customer_towncity" size="25" value="<?=$row_customer['customer_towncity']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<?php /*?><tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COUNTRY']?></td>
			<td align="left" valign="middle"><?php 
					  	echo generateselectbox('cbo_country',$country_arr,$row_customer['country_id'],'','showstate(this.value)');
					  ?></td>
		</tr>*/?>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STATE_COUNTY']?></td>
			<td align="left" valign="middle">
			<input type="text" name="cbo_state" id="cbo_state" value="<?php echo stripslashes($row_customer['customer_statecounty']);?>"  class="regiinput" size="25"/>
		</td>
		</tr> 
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PHONE']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_phone" type="text" class="regiinput" id="customer_phone" size="25" value="<?=$row_customer['customer_phone']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FAX']?></td>
			<td align="left" valign="middle"><input name="customer_fax" type="text" class="regiinput" id="customer_fax" size="25" value="<?=$row_customer['customer_fax']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUST_MOB']?></td>
			<td align="left" valign="middle"><input name="customer_mobile" type="text" class="regiinput" id="customer_mobile" size="25" value="<?=$row_customer['customer_mobile']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSTCODE']?></td>
			<td align="left" valign="middle"><input name="customer_postcode" type="text" class="regiinput" id="customer_postcode" size="25" value="<?=$row_customer['customer_postcode']?>" maxlength="<?=$short?>"/></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="25" value="<?=$row_customer['customer_email_7503']?>" maxlength="<?=$medium?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?></td>
			<td align="left" valign="middle"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="25" value="" /></td>
		</tr>	
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?> </td>
			<td align="left" valign="middle"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="25" value="" /></td>
		</tr>	
		
		<?php 
			//section for custom registration form 
			$cur_pos = 'BottomInStatic';
			$formname = 'frm_myprofile';
			include 'show_dynamic_fields_myprofile.php';

			//section for custom registration form 
			$cur_pos = 'Bottom';
			include 'show_dynamic_fields_myprofile.php';
	
	if($row_customer['customer_payonaccount_status']=='NO')
	{
	?>
	<?php /*?><tr>
		<td colspan="2" valign="top" align="left">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					  <input type="checkbox" name="customer_payonaccount_status" value="1"  /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['REQUEST_PAYONACC']?></td>
						
					</tr>
		  </table>		</td>
		</tr><?php */?>
		<?
		}
		else
		{
		?>	
		<tr>
		<td colspan="2" valign="top" align="center" >
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="regifontnormal"  >
					<tr>
					<td valign="top" align="center" colspan="2">
					<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal"  >
					<tr>
					<td   align="left" valign="middle" colspan="2" class="regiheader"><?=$Captions_arr['PAYONACC']['MY_PAYONACCDETAILS'];?> 
					</td>
					</tr>
					<tr>
					<td   align="left" valign="middle" width="43%" class="regiconent" ><?=$Captions_arr['CUST_REG']['REQUEST_PAYONACCSTATUS']?>					  </td>
					<td  align="left" valign="middle" width="57%" >:&nbsp;&nbsp;&nbsp;&nbsp;<?=$row_customer['customer_payonaccount_status']?>					</td>
					</tr>
					<? if($row_customer['customer_payonaccount_maxlimit']>0)
					{?>
					<tr>
					<td   align="left" valign="middle" width="43%" class="regiconent"><?=$Captions_arr['CUST_REG']['REQUEST_PAYONACCLIMIT']?>					  </td>
					<td  align="left" valign="middle" width="57%">:&nbsp;&nbsp;&nbsp;&nbsp;<?=print_price($row_customer['customer_payonaccount_maxlimit'])?>					</td>
					</tr>
					<? }
					
					$rem_limit = $row_customer['customer_payonaccount_maxlimit'] - $row_customer['customer_payonaccount_usedlimit'];
					if($rem_limit > 0)
					{
					?>
					<tr>
					<td   align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['REQUEST_PAYONCREDITBALANCE']?>
					  </td>
					<td  align="left" valign="middle">:&nbsp;&nbsp;&nbsp;&nbsp;<?=print_price($rem_limit)?>
					</td>
					</tr>
					<?
					}
					if($row_customer['customer_payonaccount_usedlimit']!=0)
					{?>
					<tr>
					<td   align="left" valign="middle" class="regiconent" ><?=$Captions_arr['CUST_REG']['REQUEST_PAYONACCBALANCE']?>
					  </td>
					<td  align="left" valign="middle"  >:&nbsp;&nbsp;&nbsp;&nbsp;<?=print_price($row_customer['customer_payonaccount_usedlimit'],true)?>
					</td>
					</tr>
					<? }
					if($row_customer['customer_payonaccount_status']=='REJECTED' )
					{?>
					<tr>
					<td   align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUST_REJECT_REASON']?>
					  </td>
					<td  align="left" valign="middle">:&nbsp;&nbsp;&nbsp;&nbsp;<?=$row_customer['customer_payonaccount_rejectreason']?>
					</td>
					</tr>
					<? }
					if($row_customer['customer_payonaccount_status']=='ACTIVE' && $row_customer['customer_payonaccount_status']=='INACTIVE' ) //$row_customer['customer_payonaccount_billcycle_day']>0 &&
					{?>
					<tr>
					<td   align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['REQUEST_PAYONBILLDATE']?>
					  </td>
					<td  align="left" valign="middle">:&nbsp;&nbsp;&nbsp;&nbsp;<?=$Captions_arr['CUST_REG']['DAY']?>&nbsp;&nbsp;<?=$row_customer['customer_payonaccount_billcycle_day']?>&nbsp;&nbsp;<?=$Captions_arr['CUST_REG']['OF_EVERY_MONTH']?>
					</td>
					</tr>
					<? }
					if(trim($row_customer['customer_payonaccount_laststatementdate'])!='0000-00-00')
					{
					$date = $row_customer['customer_payonaccount_laststatementdate'];
					$format='j F  Y';
					$format_date = date($format, strtotime($date))
					?>
					<tr>
					<td   align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['REQUEST_PAYONSTATEMENTDATE']?>
					  </td>
					<td  align="left" valign="middle">:&nbsp;&nbsp;&nbsp;&nbsp;<?=$format_date?>
					</td>
					</tr>
					<? }?>
					</table></tr>
		  </table>		</td>
		</tr>
		<? }?>
	<?php /*?><tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
						<td   align="left" valign="middle" width="3%">
							 <input type="checkbox" name="chk_newsletter" value="1" <?PHP if( $row_customer['customer_prod_disc_newsletter_receive']=='Y') echo "checked"; ?> />	
						</td><td  align="left" valign="middle" width="97%">
							<?=$Captions_arr['CUST_REG']['REQUEST_NEWS_RECEIVE_NEWPROD']?>
						</td>
					</tr>
				</table>			</td>
		</tr><?php */?>
	<?
			
			// to display the news letter groups from the customer_newsletter_group table
		$sql = "SELECT news_customer_id FROM newsletter_customers WHERE customer_id=".$customer_id;  
		$res = $db->query($sql);
		$row = $db->fetch_array($res);
		$news_cust_id = $row['news_customer_id'];
		if($news_cust_id>0) {
		 // #Selecting already assigned groups
		  $sql_group_assign="SELECT custgroup_id FROM customer_newsletter_group_customers_map WHERE customer_id=".$news_cust_id;
		  
		  $res_group_assign = $db->query($sql_group_assign);
		  $arr_assigned=array();

		  while($row_assigned = $db->fetch_array($res_group_assign))
		  {
				$arr_assigned[]=$row_assigned['custgroup_id'];
					
		   }
		} else {
			$arr_assigned=array();
		}		
		
		 $sql_group="SELECT custgroup_id,custgroup_name 
		 					FROM customer_newsletter_group 
									WHERE sites_site_id=".$ecom_siteid." AND custgroup_active = '1'";
		  $res_group = $db->query($sql_group);
		  if($db->num_rows($res_group)>0)
		  { 
		?>
		
		
		<?php /*?><tr>
			<td colspan="2" align="left" valign="top" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?></td>
		</tr><?php */?>
		<tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
			<tr>
				<td align="left" valign="middle" width="3%">
				<input type="checkbox" name="customer_in_mailing_list" id="customer_in_mailing_list" value="1" onchange="maillinglist_onchange(this)" <?php echo ($row_customer['customer_in_mailing_list']==1)?'checked="checked"':'';?> />	
				</td>
				<td align="left" valign="middle" width="97%">
				<?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
					
					<?php 
					$grp_cnt=0;
					while($customer_grp = $db->fetch_array($res_group)) {
					$grp_cnt++;
					?>
						<td   align="left" valign="middle" width="4%">
						
							
							<input type="checkbox" name="newsletergroup[]" id="newsletergroup[]" onchange="mailinglist_mainsel()" value="<?=$customer_grp['custgroup_id']?>" <? if(in_array($customer_grp['custgroup_id'],$arr_assigned)) echo "checked"; //($customer_grp['custgroup_id']==$customer_grp['selected_grp'])?"checked":""?> />						</td>
						
						<td  align="left" valign="middle" width="96%"><?=$customer_grp['custgroup_name']?></td>
						<?php 
							if(	$grp_cnt==3){
								echo "</tr><tr>";
							}
						}?>
					</tr>
				</table>			</td>
		</tr>	
		<?php 
		}
		else
		{
		?>
		<tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
			<tr>
				<td align="left" valign="middle" width="3%">
				<input type="checkbox" name="customer_in_mailing_list" id="customer_in_mailing_list" value="1" onchange="maillinglist_onchange(this)" <?php echo ($row_customer['customer_in_mailing_list']==1)?'checked="checked"':'';?> />	
				</td>
				<td align="left" valign="middle" width="97%">
				<?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER_ONLY']?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<?php
		}
		//if($Settings_arr['imageverification_req']) {?>
			
		<!--<tr>
			<td class="regiconent">&nbsp;</td>
			<td align="left" valign="middle" class="regiconent"><img src="<?php //url_verification_image('includes/vimg.php?size=4&amp;pass_vname=myprofile_Vimg')?>" border="0" alt="Image Verification"/>			</td>
		</tr>
		<tr>
			<td class="regiconent">&nbsp;</td>
			<td align="left" valign="middle" class="regiconent"><?php //echo $Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_CODE']?>
			<?php 
			// showing the textbox to enter the image verification code
			//$vImage->showCodBox(1,'myprofile_Vimg','class="inputA"'); 
			?>	
			</td>
		</tr><? //}?>-->
		<tr>
			<td align="left" valign="middle" class="regiconent">
				<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>">
				<input type="hidden" name="action_purpose" value="update" />			</td>
			<td align="left" valign="middle"><input name="myprofile_Submit" type="submit" class="buttongray" id="myprofile_Submit" value="<?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON']?>" /></td>
		</tr>
	</table>
	</form>
<script language="javascript">
showAccountTypeDetails(document.frm_myprofile.customer_accounttype);
</script>
<?php	
}
	function Display_Message($mesgHeader,$Message)
	{
		global $Captions_arr;
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
		<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
				<?php
				echo $mesgHeader;
				?>
			</td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
		</tr>
		<?php
		if(get_session_var("ecom_login_customer"))
		{
		?>
			<tr>
				<td  valign="middle" class="regiconent" align="center"><a href="<?=$ecom_hostname?>/myprofile.html" class="message_backlink"><?=$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_LINK'];?></a><? /*$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_TEXT'];*/?> </td>
			</tr>
		<? 
		}
		?>
		</table>
		<?php	
	}
		
};	
		
function getParameters_DynamicFormAdd($position,$section_type){
// #######################################################################################################
// Start ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################
global $ecom_siteid,$db,$ecom_hostname;
$field_str = '';
$field_msg = '';

// Check whether any dynamic section set up for customer registration in current site  and is compulsory
$sql_dyn = "SELECT section_id FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
			activate = 1 AND section_type = '".$section_type."' AND position= '".$position."' ORDER BY sort_no";
$ret_dyn = $db->query($sql_dyn);
if ($db->num_rows($ret_dyn))
{
	while ($row_dyn = $db->fetch_array($ret_dyn))
	{
		$sql_elem = "SELECT element_id,element_name,error_msg,element_type FROM elements WHERE sites_site_id=$ecom_siteid AND 
					element_sections_section_id =".$row_dyn['section_id']." AND mandatory ='Y' ORDER BY sort_no";
		
		$ret_elem = $db->query($sql_elem);
		if ($db->num_rows($ret_elem))
		{
			while ($row_elem = $db->fetch_array($ret_elem))
			{
		
				if($row_elem['error_msg'])// check whether error message is specified
				{
					if ($row_elem['element_type'] == 'checkbox')
					{
						// Check whether their exists values- to get values of each element
						$sql_val = "SELECT value_id FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
						$ret_val = $db->query($sql_val);
						if ($db->num_rows($ret_val))
						{
							$mandatory_element_name = $row_elem['element_name'];
							$ret_values_array[0][$mandatory_element_name] = $row_elem['error_msg'];					
						}	
						
					}
					elseif ($row_elem['element_type'] == 'radio')
					{
						// Check whether their exists values
						$sql_val = "SELECT value_id FROM element_value WHERE elements_element_id = ".$row_elem['element_id'];
						$ret_val = $db->query($sql_val);
						if ($db->num_rows($ret_val))
						{
							$mandatory_element_name = $row_elem['element_name'];
							$ret_values_array[1][$mandatory_element_name] = $row_elem['error_msg'];
						}	
						
					}
					else
					{
						if($field_str!='')
						{
							$field_str .= ',';
							$field_msg .= ',';
						}
						$field_str .= "'".trim($row_elem['element_name'])."'";	
						$field_msg .= "'".trim($row_elem['error_msg'])."'";	
					}	
				}	
			}
		}
							
	}
	if($field_str)	{
		//$field_str = ",".$field_str;
		//$field_msg = ",".$field_msg;
		$ret_values_array[2][$field_str] = $field_msg;
		}		
}
return $ret_values_array;
// #######################################################################################################
// Finish ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################		
}			
	



?>
			