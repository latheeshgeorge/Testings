<?php
/*############################################################################
	# Script Name 	: registrationHtml.php
	# Description 	: Page which holds the display logic for adding a customer(customer registration)
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class registration_Html
	{
		// Defining function to show the site review
		function Show_Registration($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			
			//Get the list of all countries for this site for which state is added
			$sql_country = "SELECT a.country_id,a.country_name 
							FROM 
								general_settings_site_country a 
							WHERE 
								a.sites_site_id = $ecom_siteid 
							ORDER BY country_name";
			$ret_country = $db->query($sql_country);
			$country_arr = array(0=>'-- Select Country --');
			if ($db->num_rows($ret_country)){
				while ($row_country = $db->fetch_array($ret_country))	{
					$country_id 				= $row_country['country_id'];
					$country_name 				= stripslashes($row_country['country_name']);
					$country_arr[$country_id] 	= $country_name;		
					//Get the list of states under this country
					$sql_state = "SELECT *
								  FROM 
								  	general_settings_site_state 
								  WHERE 
								  	sites_site_id=$ecom_siteid 
									AND general_settings_site_country_country_id=$country_id";
					$ret_state = $db->query($sql_state);
					$statehold_arr = array();
					if ($db->num_rows($ret_state)){
						while ($row_state = $db->fetch_array($ret_state)){	
							$state_id					= $row_state['state_id'];
							$state_name					= stripslashes($row_state['state_name']);
							$statehold_arr[$state_id] 	= $state_name;
						}
						$countrystate_arr[$country_id] = $statehold_arr;
					}
					else
						$countrystate_arr[$country_id] = $statehold_arr;
					}
	
			//Building the javascript array for state to be shown based on the selected country
				echo "<script type='text/javascript'>";
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
				echo "</script>";
			}

$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
/*			
		// Including the file to show the dynamic fields for checkout to the top of static fields
		  		$colspan 		= 6;
		  		$head_class  	= 'shoppingcartheader';
				$cur_pos 		= 'Top';
				$section_typ	= 'register'; 
				include 'show_dynamic_fields.php';
				$head_class  	= '';
				$colspan 		= '';
		
		
		
		// Including the file to show the dynamic fields for checkout to the top of static fields in same section as that of static fields
				$colspan 		= 6;
				$head_class  	= 'shoppingcartheader';
				$cur_pos 		= 'TopInStatic';
				$section_typ	= 'register'; 
				include 'show_dynamic_fields.php';
				$head_class  	= '';
				$colspan 		= '';

*/
			$Topparameters =  getParameters_DynamicFormAdd('Top','register'); // to get the feild and the error messages for the dynamic form added on the top
			if($Topparameters[2]){
				$topstr =  array_keys($Topparameters[2]);
				$topmsg =  array_values($Topparameters[2]);
			}
			if($Topparameters[0] || $Topparameters[1]){
				$checkboxfld_arrTop = $Topparameters[0];
				$radiofld_arrTop = $Topparameters[1];
			}
			
			
			$TopInStaticparameters =  getParameters_DynamicFormAdd('TopInStatic','register'); // to get the feild and the error messages for the dynamic form added on the top
			if($TopInStaticparameters[2]){
				$topinstaticstr =  array_keys($TopInStaticparameters[2]);
				$topinstaticmsg =  array_values($TopInStaticparameters[2]);
			}
			if($TopInStaticparameters[0] || $TopInStaticparameters[1]){
				$checkboxfld_arrTopInStatic = $TopInStaticparameters[0];
				$radiofld_arrTopInStatic = $TopInStaticparameters[1];
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
			
			$BottomInStaticparameters =  getParameters_DynamicFormAdd('BottomInStatic','register');  // to get the feild and the error messages for the dynamic form added at the bottom
			if($BottomInStaticparameters[2]){
				$bottominstaticstr =  array_keys($BottomInStaticparameters[2]);
				$bottominstaticmsg =  array_values($BottomInStaticparameters[2]);
			}
			if($BottomInStaticparameters[0] || $BottomInStaticparameters[1]){
				$checkboxfld_arrBottomInStatic = $BottomInStaticparameters[0];
				$radiofld_arrBottomInStatic = $BottomInStaticparameters[1];
			}
		

		
?>
<script type="text/javascript">
/* Function to validate the Customer Registration */
function validate_defaultregistration(frm)
{
	//alert(feildmsg);
	fieldRequired 		= Array('customer_title','customer_fname','customer_phone','customer_postcode','customer_email','customer_pwd','customer_pwd_cnf');
	fieldDescription 	= Array('<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_TITLE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_FIRSTNAME']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_PHONE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_POSTCODE']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_EMAIL']?>','<?=$Captions_arr['CUST_REG']['ALERT_CUSTOMER_PASSWORD']?>','<?=$Captions_arr['CUST_REG']['ALERT_CONFIRM_PASSWORD']?>');
	fieldEmail 			= Array('customer_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
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

/* Function to validate the Customer Registration */
function validate_TopInStaticregistration(frm)
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
	if(count($checkboxfld_arrTopInStatic)){
		$ptr = 0;
		foreach ($checkboxfld_arrTopInStatic as $k=>$v) {
			
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
	if(count($radiofld_arrTopInStatic)){
		$ptr = 0;
		foreach ($radiofld_arrTopInStatic as $k=>$v) {
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
			<?php if($Settings_arr['imageverification_req']){ // code for validating the image verification- needs only if it is enabled
			echo "if(bottomfrm){";
				echo "if(form.registration_Vimg.value==''){
					alert('Enter- verification Code');
					return false;
				}else{
					return true;
				}
			}";
		}
		?>
		return bottomfrm;
		}else{
			return false;
		}
	}else{
		return topfrm;
	}
}
		</script>
			<form method="post" action="" name="frm_registration" id="frm_registration" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['CUST_REG']['REGISTRATION_TREEMENU_TITLE']?></div>
		
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
				?>
				</td>
			</tr>
		<?php } 
		//section for custom registration form 
		
		$cur_pos = 'Top';
		$section_typ= 'register'; 
		$formname = 'frm_registration';
		include 'show_dynamic_fields.php';
		?>
		<tr>
			<td colspan="2" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER']?></td>
		</tr>
		<?PHP
			//section for custom registration form 
			$cur_pos = 'TopInStatic';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			include 'show_dynamic_fields.php';
		?>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE']?></td>
			<td width="57%" align="left" valign="middle"><select name="customer_accounttype" class="regiinput" id="customer_accounttype" onchange="showAccountTypeDetails(this);" >
			<option value="personal"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL']?></option>
			<option value="business"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS']?></option> 
			</select></td>
		</tr>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TITLE']?> <span class="redtext">*</span></td>
			<td width="57%" align="left" valign="middle"><select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr">Mr.</option>
			<option value="Mrs">Mrs.</option> 
			<option value="M/S">M/S.</option>
			</select></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FNAME']?> <span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_fname" type="text" class="regiinput" id="customer_fname" size="15" value="<?=$_REQUEST['customer_fname']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_MNAME']?></td>
			<td align="left" valign="middle">
		<input name="customer_mname" type="text" class="regiinput" id="customer_mname" size="15" value="<?=$_REQUEST['customer_mname']?>"  />   </td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_SURNAME']?> </td>
			<td align="left" valign="middle"><input name="customer_surname" type="text" class="regiinput" id="customer_surname" size="15" value="<?=$_REQUEST['customer_surname']?>"  /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSITION']?></td>
			<td align="left" valign="middle"><input name="customer_position" type="text" class="regiinput" id="customer_position" size="15" value="<?=$_REQUEST['customer_position']?>"  /></td>
		</tr>
		<tr  id="companydetails" style="display:none;">
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="6" >
					<tr>
						<td colspan="2" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER']?> </td>
					</tr>
					<tr>
						<td width="40%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_NAME']?></td>
					  <td width="60%" align="left" valign="middle"><input name="customer_compname" type="text" class="regiinput" id="customer_compname" size="15" value="<?=$_REQUEST['customer_compname']?>"  /></td>
					</tr>
					<?php $sql_selcompany_types = "SELECT comptype_id,comptype_name
														FROM common_customer_company_types ORDER BY comptype_order ";
							$ret_company_type = $db->query($sql_selcompany_types);
							while($company_type= $db->fetch_array($ret_company_type)){
								$companytype_id[] = $company_type['comptype_id'];
								$companytype_name[$company_type['comptype_id']] = $company_type['comptype_name'];
							}
		 ?>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_TYPE']?></td>
						<td align="left" valign="middle"><?=generateselectbox('customer_comptype',$companytype_name,$_REQUEST['customer_comptype']);?></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_REGNO']?></td>
						<td align="left" valign="middle"><input name="customer_compregno" type="text" class="regiinput" id="customer_compregno" size="15" value="<?=$_REQUEST['customer_compregno']?>"  /></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_VATREGNO']?></td>
						<td align="left" valign="middle"><input name="customer_compvatregno" type="text" class="regiinput" id="customer_compvatregno" size="15" value="<?=$_REQUEST['customer_compvatregno']?>"/></td>
					</tr>
				</table>
			</td> 
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_BUILDING_NAME']?></td>
			<td align="left" valign="middle"><input name="customer_buildingname" type="text" class="regiinput" id="customer_buildingname" size="15" value="<?=$_REQUEST['customer_buildingname']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STREET_NAME']?></td>
			<td align="left" valign="middle"><input name="customer_streetname" type="text" class="regiinput" id="customer_streetname" size="15" value="<?=$_REQUEST['customer_streetname']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TOWN_CITY']?></td>
			<td align="left" valign="middle"><input name="customer_towncity" type="text" class="regiinput" id="customer_towncity" size="15" value="<?=$_REQUEST['customer_towncity']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COUNTRY']?></td>
			<td align="left" valign="middle"><?php
					  	echo generateselectbox('cbo_country',$country_arr,0,'','showstate(this.value)');
					  ?></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STATE_COUNTY']?></td>
			<td align="left" valign="middle">
		<?php 		$state_arr = array(-1=>'-- Select State --');
					  	echo generateselectbox('cbo_state',$state_arr,0);?></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PHONE']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_phone" type="text" class="regiinput" id="customer_phone" size="15" value="<?=$_REQUEST['customer_phone']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FAX']?></td>
			<td align="left" valign="middle"><input name="customer_fax" type="text" class="regiinput" id="customer_fax" size="15" value="<?=$_REQUEST['customer_fax']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSTCODE']?></td>
			<td align="left" valign="middle"><input name="customer_postcode" type="text" class="regiinput" id="customer_postcode" size="15" value="<?=$_REQUEST['customer_postcode']?>" /></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="15" value="<?=$_REQUEST['customer_email']?>" /></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="15" value="" /></td>
		</tr>	
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="15" value="" /></td>
		</tr>	
		
		<?php 
		
			//section for custom registration form 
			$cur_pos = 'BottomInStatic';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			include 'show_dynamic_fields.php';
	
			//section for custom registration form 
			$cur_pos = 'Bottom';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			include 'show_dynamic_fields.php';
			// to list the news letter groups
			$sql_customer_grp = "SELECT  custgroup_id,custgroup_name  
								FROM 
									customer_newsletter_group 
								WHERE 
									custgroup_active = 1 AND sites_site_id=".$ecom_siteid;
		$ret_customer_grp = $db->query($sql_customer_grp);
		if($db->num_rows($ret_customer_grp)){
		?>
		
		<tr>
			<td align="left" valign="top" class="regiconentred" colspan="2"><?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?></td>
		</tr>
		<tr>
		<td colspan="2" valign="top" align="left">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				<?php 
					$grp_cnt=0;
					while($customer_grp = $db->fetch_array($ret_customer_grp)) {
					$grp_cnt++;
					?>
						<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" value="<?=$customer_grp['custgroup_id']?>" <?=($customer_grp['custgroup_id']==$customer_grp['selected_grp'])?"checked":""?> />
						
						</td>
						
						<td  align="left" valign="middle" width="30%"><?=$customer_grp['custgroup_name']?></td>
						<?php 
							if(	$grp_cnt==3)
							{
								echo "</tr><tr>";
								$grp_cnt =0;
							}
						}?>
					</tr>
				</table>
		</td>
		</tr>
		<?php
		}
		 if($Settings_arr['imageverification_req']) {?>
		<tr>
			<td class="regiconent">&nbsp;</td>
			<td align="left" valign="middle" class="regiconent"><img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=registration_Vimg')?>" border="0" alt="Image Verification"/>			</td>
		</tr>
		<tr>
			<td class="regiconent">&nbsp;</td>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_CODE']?>
			<?php 
			// showing the textbox to enter the image verification code
			$vImage->showCodBox(1,'registration_Vimg','class="inputA"'); 
			?>	
			</td>
		</tr><? }?>
		<tr>
			<td align="left" valign="middle" class="regiconent">
				<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>"/>
				<input type="hidden" name="action_purpose" value="insert" />
			</td>
			<td align="left" valign="middle"><input name="registration_Submit" type="submit" class="buttongray" id="registration_Submit" value="<?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON']?>" /></td>
		</tr>
	</table>
			</form>
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
         <?php echo $mesgHeader;?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr>
        </table>
		<?php	
		}
		function Forgot_Password(){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
		$Captions_arr['FORGOT_PASSWORD'] = getCaptions('FORGOT_PASSWORD'); // to get values for the captions from the general settings site captions

		
		?>
			<form method="post" action="" name="frm_forgotpassword" id="frm_forgotpassword" class="frm_cls" onsubmit="return validate_form(this);">
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regitable">
      <tr>
        <td colspan="2" align="left" valign="middle" class="regiheader" > 
        <?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_HEADER']?></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle" class="regiconent"><?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_MESSAGE']?></td>
      </tr>
	  <tr>
        <td width="35%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_LABEL']?></td>
		<td width="65%" align="left" valign="middle" class="regiconent"><input name="forgotpwd_email" type="text" class="regiinput" id="forgotpwd_email" size="15" value=""  /></td>
      </tr>
	  <tr>
	    <td width="35%" align="left" valign="middle" class="regiconent"><input name="action_purpose" id="action_purpose" type="hidden" value="ForgotPassword_send"></td>
	    <td align="left" valign="middle" class="regiconent"><input name="forgotpassword_Submit" type="submit" class="buttongray" id="forgotpassword_Submit" value="<?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_SUBMIT']?>" /></td>
	    </tr>
           </table></form>
		<script type="text/javascript"> 
			function validate_form(frm)
			{
				//alert(feildmsg);
				fieldRequired 		= Array('forgotpwd_email');
				fieldDescription 	= Array('<?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_ALERT_EMAIL']?>');
				fieldEmail 			= Array('forgotpwd_email');
				fieldConfirm 		= Array();
				fieldConfirmDesc  	= Array();
				fieldNumeric 		= Array();
				if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
				return true;
				}
				else
				{
				return false;
				}
			}
		</script>
		<?php	
		}
		
	};	
?>

	<?php 
		
function getParameters_DynamicFormAdd($position,$section_type){
// #######################################################################################################
// Start ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################
global $ecom_siteid,$db;
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
			