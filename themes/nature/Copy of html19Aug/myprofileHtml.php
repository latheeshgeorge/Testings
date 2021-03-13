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
					}
			}

$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
 $Captions_arr['PAYONACC'] = getCaptions('PAYONACC'); 
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
	fieldSpecChars 		= Array();
	fieldCharDesc       = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))  {
	<?php

	// Logic to build the dynamic field validation
	if(count($checkboxfld_arrTop)){
		$ptr = 0;
		foreach ($checkboxfld_arrTop as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
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
	fieldSpecChars 		= Array();
	fieldCharDesc       = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))  {
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
	fieldSpecChars 		= Array();
	fieldCharDesc       = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))  
	{
	<?php
		// Logic to build the dynamic field validation
		
	if(count($checkboxfld_arrBottom)){
		$ptr = 0;
		foreach ($checkboxfld_arrBottom as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
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
	fieldSpecChars 		= Array();
	fieldCharDesc       = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))  
	{
	<?php
		// Logic to build the dynamic field validation
		
	if(count($checkboxfld_arrBottomInStatic)){
		$ptr = 0;
		foreach ($checkboxfld_arrBottomInStatic as $k=>$v) {
			
			echo  "var retval_$k = new Array(); ";
				
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
		return bottomfrm;
		}else{
			return false;
		}
	}else{
		return topfrm;
	}
}


		</script>
			<form method="post" action="" name="frm_myprofile" id="frm_myprofile" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
			<div class="treemenu">
			<ul>
			  <li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
			  <li><?=$Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']?></li>
			</ul>
		 </div>
	  <div class="inner_header"><?=$Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']?></div>
	 <div class="inner_con_clr1" >
        <div class="inner_clr1_top"></div>
		<div class="inner_clr1_middle">	
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
		if($Captions_arr['CUST_REG']['CUSTOMER_DESC']!='')
		{
		?>
		<tr>
			<td colspan="2" align="left" valign="top" class="regiconentA"><?php echo stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_DESC'])?></td>
		</tr>
		<?php
		}
		?>			<tr>
			<td width="43%" align="left" valign="middle" class="regiconentA"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE']?></td>
			<td width="57%" align="left" valign="middle" class="regi_txtfeild"><select name="customer_accounttype" class="regiinput" id="customer_account_type" onchange="showAccountTypeDetails(this);" >
			<option value="personal" <?=($row_customer['customer_accounttype']=='personal')?"selected":''?>><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL']?></option>
			<option value="business" <?=($row_customer['customer_accounttype']=='business')?"selected":''?>><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS']?></option> 
			</select></td>
		</tr>
		</table>
		</div>
		<div class="inner_clr1_bottom"></div>
	  	</div>
		<?php
		//section for custom registration form 
		
		$cur_pos 				= 'Top';
		$section_typ			= 'register'; 
		$formname 				= 'frm_myprofile';
		$cont_leftwidth 		= '43%';
		$cont_rightwidth 		= '57%';
		$cellspacing 			= 0;
		$head_class				= 'regiheader';
		$specialhead_tag_start 	= '<span class="reg_header"><span>';
		$specialhead_tag_end 	= '</span></span>';
		$cont_class 			= 'regiconent'; 
		$texttd_class			= 'regi_txtfeild';
		$cellpadding 			= 0;		
		include 'show_dynamic_fields_myprofile.php';?>
		
		<?php
		//section for custom registration form 
		
		/*$cur_pos = 'Top';
		$formname = 'frm_myprofile';
		include 'show_dynamic_fields_myprofile.php';*/
		?>
	
		
		<div class="inner_con"  id="companydetails" style="display:none;" >
        <div class="inner_top"></div>
        <div class="inner_middle">
          <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2" class="regiheader"><span class="reg_header"><span><?=$Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER_EDIT']?> </span></span></td>
					</tr>
					<tr>
						<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_NAME']?></td>
					  <td width="57%" align="left" valign="middle" class="regi_txtfeild"><input name="customer_compname" type="text" class="regiinput" id="customer_compname" size="25" value="<?=$row_customer['customer_compname']?>" maxlength="<?=$short?>"/></td>
					</tr>
					<?php $sql_selcompany_types = "SELECT comptype_id,comptype_name
														FROM general_settings_sites_customer_company_types WHERE sites_site_id=$ecom_siteid AND comptype_hide=0 ORDER BY comptype_order";
							$ret_company_type = $db->query($sql_selcompany_types);
							while($company_type= $db->fetch_array($ret_company_type)){
								$companytype_id[] = $company_type['comptype_id'];
								$companytype_name[$company_type['comptype_id']] = $company_type['comptype_name'];
							}
														?>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_TYPE']?></td>
						<td align="left" valign="middle" class="regi_txtfeild"><?=generateselectbox('customer_comptype',$companytype_name,$row_customer['customer_comptype']);?></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_REGNO']?></td>
						<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_compregno" type="text" class="regiinput" id="customer_compregno" size="25" value="<?=$row_customer['customer_compregno']?>"  maxlength="<?=$short?>"/></td>
					</tr>
					<tr>
						<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMP_VATREGNO']?></td>
						<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_compvatregno" type="text" class="regiinput" id="customer_compvatregno" size="25" value="<?=$row_customer['customer_compvatregno']?>" maxlength="<?=$short?>"/></td>
					</tr>
				</table>
		 </div>
        <div class="inner_bottom"></div>
	  </div>
	  <div class="inner_con" >
        <div class="inner_top"></div>
        <div class="inner_middle">	
	   <table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
           <tbody>

		<tr>
			<td colspan="2" class="regiheader"><span class="reg_header"><span><?=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER_EDIT']?></span></span></td>
		</tr>
		<?php
				//section for custom registration form 
				
				$cur_pos 				= 'TopInStatic';
				$section_typ			= 'register'; 
				$formname 				= 'frm_myprofile';
				$cont_leftwidth 		= '43%';
				$cont_rightwidth 		= '57%';
				$cellspacing 			= 0;
				$head_class				= 'regiheader';
				$specialhead_tag_start 	= '<span class="reg_header"><span>';
				$specialhead_tag_end 	= '</span></span>';
				$cont_class 			= 'regiconent'; 
				$texttd_class			= 'regi_txtfeild';
				$cellpadding 			= 0;		
				include 'show_dynamic_fields_myprofile.php';
				?>
		<tr>
			<td width="43%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TITLE']?> <span class="redtext">*</span></td>
			<td width="57%" align="left" valign="middle" class="regi_txtfeild"><select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($row_customer['customer_title']=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($row_customer['customer_title']=='Mrs.')?"selected":''?>>Mrs.</option> 
			<option value="M/S." <?=($row_customer['customer_title']=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FNAME']?> <span class="redtext">*</span></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_fname" type="text" class="regiinput" id="customer_fname" size="25" value="<?=$row_customer['customer_fname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_MNAME']?></td>
			<td align="left" valign="middle" class="regi_txtfeild">
		<input name="customer_mname" type="text" class="regiinput" id="customer_mname" size="25" value="<?=$row_customer['customer_mname']?>"  maxlength="<?=$short?>"/>   </td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_SURNAME']?> </td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_surname" type="text" class="regiinput" id="customer_surname" size="25" value="<?=$row_customer['customer_surname']?>"  maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSITION']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_position" type="text" class="regiinput" id="customer_position" size="25" value="<?=$row_customer['customer_position']?>"  maxlength="<?=$short?>"/></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_BUILDING_NAME']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_buildingname" type="text" class="regiinput" id="customer_buildingname" size="25" value="<?=$row_customer['customer_buildingname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STREET_NAME']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_streetname" type="text" class="regiinput" id="customer_streetname" size="25" value="<?=$row_customer['customer_streetname']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_TOWN_CITY']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_towncity" type="text" class="regiinput" id="customer_towncity" size="25" value="<?=$row_customer['customer_towncity']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_COUNTRY']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><?php 
					  	echo generateselectbox('cbo_country',$country_arr,$row_customer['country_id'],'','');
					  ?></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_STATE_COUNTY']?></td>
			<td align="left" valign="middle" class="regi_txtfeild">
				<input type="text" name="cbo_state" id="cbo_state" value="<?php echo stripslashes($row_customer['customer_statecounty']);?>"  class="regiinput" size="25"/>		
						</td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PHONE']?><span class="redtext">*</span></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_phone" type="text" class="regiinput" id="customer_phone" size="25" value="<?=$row_customer['customer_phone']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_FAX']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_fax" type="text" class="regiinput" id="customer_fax" size="25" value="<?=$row_customer['customer_fax']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUST_MOB']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_mobile" type="text" class="regiinput" id="customer_mobile" size="25" value="<?=$row_customer['customer_mobile']?>" maxlength="<?=$short?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_POSTCODE']?></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_postcode" type="text" class="regiinput" id="customer_postcode" size="25" value="<?=$row_customer['customer_postcode']?>" maxlength="<?=$short?>"/></td>
		</tr>
		
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']?><span class="redtext">*</span></td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="25" value="<?=$row_customer['customer_email_7503']?>" maxlength="<?=$medium?>"/></td>
		</tr>
		 </tbody>
        </table>
	  
		</div>
        <div class="inner_bottom"></div>
	  </div>
	   <div class="inner_con" >
        <div class="inner_top"></div>
        <div class="inner_middle">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr class="regitable">
			<td align="left" valign="middle" class="regiconent"  width="43%"><?=$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?></td>
			<td align="left" valign="middle" class="regi_txtfeild" width="57%"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="25" value="" /></td>
		</tr>	
		 <tr class="regitable">
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?> </td>
			<td align="left" valign="middle" class="regi_txtfeild"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="25" value="" /></td>
		</tr>	
		<?php
				//section for custom registration form 
				
				$cur_pos 				= 'BottomInStatic';
				$section_typ			= 'register'; 
				$formname 				= 'frm_myprofile';
				$cont_leftwidth 		= '43%';
				$cont_rightwidth 		= '57%';
				$cellspacing 			= 0;
				$head_class				= 'regiheader';
				$specialhead_tag_start 	= '<span class="reg_header"><span>';
				$specialhead_tag_end 	= '</span></span>';
				$cont_class 			= 'regiconent'; 
				$texttd_class			= 'regi_txtfeild';
				$cellpadding 			= 0;		
				include 'show_dynamic_fields_myprofile.php';
				?>
		</table>
        </div>
        <div class="inner_bottom"></div>
	  </div>
	 
			<?php
			//section for custom registration form 
			
			$cur_pos 				= 'Bottom';
			$section_typ			= 'register'; 
			$formname 				= 'frm_myprofile';
			$cont_leftwidth 		= '43%';
			$cont_rightwidth 		= '57%';
			$cellspacing 			= 0;
			$head_class				= 'regiheader';
			$specialhead_tag_start 	= '<span class="reg_header"><span>';
			$specialhead_tag_end 	= '</span></span>';
			$cont_class 			= 'regiconent'; 
			$texttd_class			= 'regi_txtfeild';
			$cellpadding 			= 0;		
			include 'show_dynamic_fields_myprofile.php';?>
			
		<?php 
			
	if($row_customer['customer_payonaccount_status']=='NO')
	{
	?>
	 <div class="inner_con_clr1" >
        <div class="inner_clr1_top"></div>
        	 
        <div class="inner_clr1_middle">
          <table class="regi_tableA" width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					  <input type="checkbox" name="customer_payonaccount_status" value="1"  /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['REQUEST_PAYONACC']?></td>
						
					</tr>
		 </tbody>
          </table>
        </div>
        <div class="inner_clr1_bottom"></div>
	  </div>
		<?
		}
		else
		{
		?>	
		<div class="inner_con_clr1" >
        <div class="inner_clr1_top"></div>
        	 
        <div class="inner_clr1_middle">
          <table class="regi_tableA" width="100%" border="0" cellpadding="0" cellspacing="0">
            <tbody>
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
					 </tbody>
          </table>
		  </div>
        <div class="inner_clr1_bottom"></div>
	  </div>
		<? }?>
	    <div class="inner_con_clr1" >
        <div class="inner_clr1_top"></div>
        <div class="inner_clr1_middle">
		 <table class="regi_tableA" width="100%" border="0" cellpadding="0" cellspacing="4">
            <tbody>
	<tr>
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
		</tr>
		</tbody>
          </table>
        </div>
        <div class="inner_clr1_bottom"></div>
	  </div>
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
	<div class="inner_con_clr1" >
		<div class="inner_clr1_top"></div>
		<div class="inner_clr1_middle">
	<table class="regi_tableA" width="100%" border="0" cellpadding="0" cellspacing="4">
	<tbody>
		<tr>
			<td colspan="2" align="left" valign="top" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?></td>
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
						<td   align="left" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" id="newsletergroup[]" value="<?=$customer_grp['custgroup_id']?>" <? if(in_array($customer_grp['custgroup_id'],$arr_assigned)) echo "checked"; //($customer_grp['custgroup_id']==$customer_grp['selected_grp'])?"checked":""?> />						</td>
						
						<td  align="left" valign="middle" width="30%"><?=$customer_grp['custgroup_name']?></td>
						<?php 
							if(	$grp_cnt==3){
								echo "</tr><tr>";
							}
						}?>
					</tr>
				</table>			</td>
		</tr>
		</tbody>
			  </table>
			</div>
			<div class="inner_clr1_bottom"></div>
		  </div>	
		<?php 
		}
		//if($Settings_arr['imageverification_req']) {?>
		 <div class="inner_con" >
				<div class="inner_top"></div>
				<div class="inner_middle">
		<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<tr class="regitable">
				<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>">
				<input type="hidden" name="action_purpose" value="update" />			
			<td align="left" valign="middle"><input name="myprofile_Submit" type="submit" class="inner_button_red" id="myprofile_Submit" value="<?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON']?>" /></td>
		</tr>
	    </tbody>
				  </table>
				</div>
				<div class="inner_bottom"></div>
			  </div>
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
		<div class="inner_header"><?php echo $mesgHeader;?></div>
			<div class="inner_con_clr1" >
			<div class="inner_clr1_top"></div>
			<div class="inner_clr1_middle">
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
		<tr>
			<td align="left" valign="middle" class="regicontentA"><?php echo $Message; ?></td>
		</tr>
		<?php
		if(get_session_var("ecom_login_customer"))
		{
		?>
			<tr>
				<td  valign="middle" class="regicontentA" align="center"><a href="<?=$ecom_hostname?>/myprofile.html" class="message_backlink"><?=$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_LINK'];?></a><? /*$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_TEXT'];*/?> </td>
			</tr>
		<? 
		}
		?>
		</table>
		</div>
		<div class="inner_clr1_bottom"></div>
		</div>
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
		$ret_values_array[2][$field_str] = $field_msg;
		}		
}
return $ret_values_array;
// #######################################################################################################
// Finish ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################		
}			
	



?>
			