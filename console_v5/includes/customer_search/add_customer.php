<?php
	/*#################################################################
	# Script Name 	: add_customer.php
	# Description 	: Page for adding Customer
	# Coded by 		: SKR
	# Created on	: 13-Aug-2007
	# Modified by	: ANU
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer';
$help_msg = get_help_messages('ADD_CUSTOMER_MESS1');

// #######################################################################################################
// Start ## Section to builds the dynamic fields to be placed in the javascript validation
// #######################################################################################################
$field_str = '';
$field_msg = '';
$Topparameters =  getParameters_DynamicFormAdd('Top','register'); // to get the feild and the error messages for the dynamic form added on the top
			if($Topparameters[2]){
				$topstr =  array_keys($Topparameters[2]);
				$topmsg =  array_values($Topparameters[2]);
			}
			if($Topparameters[0] || $Topparameters[1]){
				$checkboxfld_arrTop = $Topparameters[0];
				//print_r($checkboxfld_arrTop);
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

$TopInStaticparameters =  getParameters_DynamicFormAdd('TopInStatic','register');  // to get the feild and the error messages for the dynamic form added at the TopInStatic postiton
			if($TopInStaticparameters[2]){
				$TopInStaticstr =  array_keys($TopInStaticparameters[2]);
				$TopInStaticmsg =  array_values($TopInStaticparameters[2]);
			}
			if($TopInStaticparameters[0] || $TopInStaticparameters[1]){
				$checkboxfld_arrTopInStatic = $TopInStaticparameters[0];
				$radiofld_arrTopInStatic = $TopInStaticparameters[1];
			}
$BottomInStaticparameters =  getParameters_DynamicFormAdd('BottomInStatic','register');  // to get the feild and the error messages for the dynamic form added at the BottomInStatic postiton
			if($BottomInStaticparameters[2]){
				$BottomInStaticstr =  array_keys($BottomInStaticparameters[2]);
				$BottomInStaticmsg =  array_values($BottomInStaticparameters[2]);
			}
			if($BottomInStaticparameters[0] || $BottomInStaticparameters[1]){
				$checkboxfld_arrBottomInStatic = $BottomInStaticparameters[0];
				$radiofld_arrBottomInStatic = $BottomInStaticparameters[1];
			}		
?>	
<script language="javascript" type="text/javascript">
/*ANU*/
// function to validate the dynamic form feilds in the top with in the static section
function validate_TopInStaticregistration(frm){
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$TopInStaticstr[0]?>);
	fieldDescription 	= Array(<?=$TopInStaticmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation for check boxes
	if(count($checkboxfld_arrTopInStatic)){
		foreach ($checkboxfld_arrTopInStatic as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
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

// function to validate the dynamic form feilds in the Bottom with in the static section
function validate_BottomInStaticregistration(frm){
	//alert(feildmsg);
	fieldRequired 		= Array(<?=$BottomInStaticstr[0]?>);
	fieldDescription 	= Array(<?=$BottomInStaticmsg[0]?>);
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))  {
	<?php
	// Logic to build the dynamic field validation for check boxes
	if(count($checkboxfld_arrBottomInStatic)){
		foreach ($checkboxfld_arrBottomInStatic as $k=>$v) {
			echo  "var retval_$k = new Array(); ";
				//echo "alert(frm.elements.length);";
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


/* Function to validate the Customer Registration */
function validate_defaultregistration(frm)
{
	//alert(feildmsg);
	<?php if($ecom_siteid==76)
	{
	?>
	fieldRequired 		= Array('customer_title','customer_fname','customer_phone','customer_email','customer_pwd','customer_pwd_cnf');
	fieldDescription 	= Array('Customer Title','Customer Name','Customer Phone','Customer email','Password','Confirm Password');
	<?php 
    }
    else
    {
    ?>
    fieldRequired 		= Array('customer_title','customer_fname','customer_postcode','customer_phone','customer_email','customer_pwd','customer_pwd_cnf');
	fieldDescription 	= Array('Customer Title','Customer Name','Customer Postcode','Customer Phone','Customer email','Password','Confirm Password');

     <?php
	}
    ?>

	fieldEmail 			= Array('customer_email');
	fieldConfirm 		= Array('customer_pwd','customer_pwd_cnf');
	fieldConfirmDesc  	= Array('Password','Confirm Password');
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	 if(frm.country_id.value!=0)
		{
			var state_ids=document.frmAddCustomer.customer_statecounty.value;
			if(state_ids==-1)
			{
				 if(document.frmAddCustomer.other_state.value=='')
				 {
					alert("Enter other state name");
					document.frmAddCustomer.other_state.focus();
					 return false;
				 }
				 return true;
			}
		}	
	    if(parseInt(frm.customer_affiliate_commission.value)>=100 || parseInt(frm.customer_affiliate_commission.value) < 0) {
			alert("Customer Affiliate Commission Rate Should Be Positive and below 100% ");
			document.frmAddCustomer.customer_affiliate_commission.focus();
			return false;
		} 
		 if(parseInt(frm.customer_discount.value)>=100 || parseInt(frm.customer_discount.value)<0) {
			alert("Customer discount Should Be Positive and Be below 100% ");
			document.frmAddCustomer.customer_discount.focus();
			return false;
		} 
		show_processing();
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
			//echo "for (var i=0, i < document.frmAddCustomer.elements.length; i++) {";
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
			echo "for (i=0, n=document.frmAddCustomer.$k.length; i<n; i++) {
					 if (document.frmAddCustomer.$k"."[i]".".checked) {
   		 	 		 	var checkvalue = document.frmAddCustomer.$k"."[i]".".value;
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
			//echo "for (var i=0, i < document.frmAddCustomer.elements.length; i++) {";
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
			echo "for (i=0, n=document.frmAddCustomer.$k.length; i<n; i++) {
  					 if (document.frmAddCustomer.$k"."[i]".".checked) {
   				  	 	 var checkvalue = document.frmAddCustomer.$k"."[i]".".value;
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


/*function validate_allforms(form){
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
*/
function validate_allforms(form){
	topfrm =  validate_Topregistration(form);
	if(topfrm){
		TopInStaticfrm = validate_TopInStaticregistration(form);
		if(TopInStaticfrm) {
			defalutfrm = validate_defaultregistration(form);
			if(defalutfrm){
				BottomInStaticfrm = validate_BottomInStaticregistration(form);
				if(BottomInStaticfrm){
					bottomfrm =  validate_Bottomregistration(form);
					return bottomfrm;
				}else{
				return BottomInStaticfrm;
				}
			}else{
				return defalutfrm;
			}
		}else{
			return TopInStaticfrm;
		}
	}else{
		return topfrm;
	}
}
/*ANU*/





function changestate()
{
	var retdivid='state_tr';
	var country_id;
	var fpurpose;
	country_id=document.frmAddCustomer.country_id.value;
	if(country_id!="")
	{
		document.getElementById('state_tr').style.display='';
		fpurpose	= 'list_state';
		document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
		retobj 											= eval("document.getElementById('"+retdivid+"')");
		retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
		var qrystr										= '';
		/* Calling the ajax function */
		Handlewith_Ajax('services/customer_search.php','fpurpose='+fpurpose+'&'+qrystr+'&country_id='+country_id);
	}
	else
	{
		
		document.getElementById('state_tr').style.display='none';
	}
}
//Other state section.
function state_other()
{
	var stte_ids=document.frmAddCustomer.customer_statecounty.value;
	if(stte_ids==-1)
	{
		document.getElementById('state_other_tr').style.display='';
	}
	else
	{
		document.getElementById('state_other_tr').style.display='none';

	}
	
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function maillinglist_onchange(obj)
{
	if(obj.checked==false)
	{
		for(i=0;i<document.frmAddCustomer.elements.length;i++)
		{
			if (document.frmAddCustomer.elements[i].type =='checkbox' && document.frmAddCustomer.elements[i].name.substr(0,9)=='chk_group')
			{ 
				document.frmAddCustomer.elements[i].checked = false;
			}
		}	
	}
}
function mailinglist_mainsel()
{
	var atleast_one = false;
	for(i=0;i<document.frmAddCustomer.elements.length;i++)
	{
		if (document.frmAddCustomer.elements[i].type =='checkbox' && document.frmAddCustomer.elements[i].name.substr(0,9)=='chk_group')
		{ 
			if(document.frmAddCustomer.elements[i].checked==true)
				atleast_one = true;
		}
	}	
	if(atleast_one)
		document.frmAddCustomer.customer_in_mailing_list.checked = true;
	/*else
		document.frmAddCustomer.customer_in_mailing_list.checked = false;*/
}	
</script>
<form name='frmAddCustomer' action='home.php?request=customer_search' method="post" onsubmit="return validate_allforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=customer_search&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&search_compname=<?php echo $_REQUEST['search_compname']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer</a><span> Add Customer</span></div></td>
        </tr>
       <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >
		   <div class="editarea_div">
		   <table width="100%">
		<tr>
		   <td align="left" class="seperationtd" colspan="2">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="14%" align="left">Account Type </td>
               <td>
			   <select name="customer_accounttype" id="customer_accounttype">
			   <option value="personal" <?php echo ($_REQUEST['customer_accounttype']=='personal')?'selected':''?>>Personal Account</option>
			   <option value="business" <?php echo ($_REQUEST['customer_accounttype']=='business')?'selected':''?>>Business Account</option>
               </select>
               </td>
             </tr>
           </table></td>
		 </tr>
		<?php
		$cur_pos = 'Top';
		$formname = 'frmAddCustomer';
		include 'show_dynamic_fields.php';
		?>
		 <tr>
		<td colspan="2" align="left" class="seperationtd">Personal Details</td>
		</tr>
		<?
		$cur_pos = 'TopInStatic';
		$formname = 'frmAddCustomer';
		include 'show_dynamic_fields.php';
		?>
		<tr>
		<td width="50%" valign="top"  class="tdcolorgrayleft">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		
	
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Customer Title <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="customer_title"  />
		  <option value="" >-select-</option>
		  <option value="Mr." <?php if($_REQUEST['customer_title']=='Mr.') echo "selected";?> >Mr.</option>
		  <option value="Ms." <?php if($_REQUEST['customer_title']=='Ms.') echo "selected";?>>Ms.</option>
		  <option value="Mrs." <?php if($_REQUEST['customer_title']=='Mrs.') echo "selected";?>>Mrs.</option>
		  <option value="Miss." <?php if($_REQUEST['customer_title']=='Miss.') echo "selected";?>>Miss.</option>
		  <option value="M/s." <?php if($_REQUEST['customer_title']=='M/s.') echo "selected";?>>M/s.</option>
		  <option value="Dr." <?php if($_REQUEST['customer_title']=='Dr.') echo "selected";?>>Dr.</option>
		  <option value="Sir." <?php if($_REQUEST['customer_title']=='Sir.') echo "selected";?>>Sir.</option>
		  <option value="Rev." <?php if($_REQUEST['customer_title']=='Rev.') echo "selected";?>>Rev.</option>
		 </select>
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >First Name <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fname" value="<?=$_REQUEST['customer_fname']?>" maxlength="100"   />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Middle Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mname"  value="<?=$_REQUEST['customer_mname']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Surname</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_surname" value="<?=$_REQUEST['customer_surname']?>"  />
		  </td>
        </tr>
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Building Name / No.</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_buildingname"  value="<?=$_REQUEST['customer_buildingname']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Street Name</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_streetname" value="<?=$_REQUEST['customer_streetname']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Town/City</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_towncity"  value="<?=$_REQUEST['customer_towncity']?>" />
		  </td>
        </tr>
		
		
		</table></td>
		<td valign="top" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Country</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <select class="input" name="country_id"<?php /*?> onchange="changestate()"<?php */?>>
		  <option value="0">-select-</option>
		  <?
		  $sql_country="SELECT country_id,country_name FROM general_settings_site_country WHERE sites_site_id=".$ecom_siteid;
		  $res_country=$db->query($sql_country);
		  while($row_country=$db->fetch_array($res_country))
		  {
		  ?>
		  <option value="<?=$row_country['country_id']?>"><?=$row_country['country_name']?></option>
		  <?
		  }
		  ?>
		  </select>		  </td>
        </tr>
		<tr >
				<td align="left" valign="middle" class="tdcolorgray" >State</td>
				<td align="left" valign="middle" class="tdcolorgray"> <input type="text" name="customer_statecounty" id="customer_statecounty" value="<?php echo $_REQUEST['customer_statecounty']?>"  /></td>
		</tr> 
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Postcode <?php if($ecom_siteid!=76){ ?><span class="redtext">*</span><?php } ?></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_postcode" value="<?=$_REQUEST['customer_postcode']?>"  />		  </td>
        </tr>
		
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Phone  <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_phone" value="<?=$_REQUEST['customer_phone']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Fax</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_fax" value="<?=$_REQUEST['customer_fax']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Mobile</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_mobile" value="<?=$_REQUEST['customer_mobile']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Activate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_activated" value="1"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_ACTIVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_hide"  value="1" />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="tdcolorgray" colspan="2">Receive Newsletters On New or Discount Products <input class="input" type="checkbox" name="customer_prod_disc_newsletter_receive"  value="1" <? if($_REQUEST['customer_prod_disc_newsletter_receive']=='Y') echo "checked";?> /></td>
		  </tr>
		</table>
		</td>
		</tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		<tr>
		<td colspan="2" align="left" class="seperationtd">Company Details</td>
		</tr>
		<tr>
		<td  width="50%" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Company Type</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <? $sqlcomp = "SELECT comptype_id,comptype_name FROM general_settings_sites_customer_company_types WHERE  sites_site_id=".$ecom_siteid. " ORDER BY comptype_order";
		     $res_sqlcomp =$db->query($sqlcomp);
			 
		  ?>
		  <select name="comptype_id">
		  <option value="0">-select-</option>
		  <? 
		   while($row_sqlcomp=$db->fetch_array($res_sqlcomp)) {
		   ?>
		  <option value="<?=$row_sqlcomp['comptype_id']?>"<? if($row_sqlcomp['comptype_id']==$row_customer['customer_comptype']){ echo "selected"; }?>><?=$row_sqlcomp['comptype_name']?></option>
		  <?
		   } 
		  ?>
		  </select>	</td>
        </tr>
		
		<tr>
         <td width="28%" align="left" valign="middle" class="tdcolorgray" >Company Name</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compname" value="<?=$_REQUEST['customer_compname']?>"  /></td> 
        </tr>
		</table>		</td>
		<td class="tdcolorgray" >
		<?php /*?><table  width="100%" border="0" cellspacing="0" cellpadding="0">
	
		
		<tr>
          <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company RegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compregno" value="<?=$row_customer['customer_compregno']?>"  />       	    </td>
        </tr>
		<tr>
		 <td width="35%" align="left" valign="middle" class="tdcolorgray" >Company VatRegNo</td>
          <td width="65%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_compvatregno" value="<?=$row_customer['customer_compvatregno']?>"  />		</tr>
		</table><?php */?>		</td>
		</tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		<tr>
		<td colspan="2" >&nbsp;</td>
		</tr>
		
		<tr>
		<td colspan="2" align="left" class="seperationtd">Customer Login</td>
		</tr>
		<tr>
		<td width="50%">
		<table width="100%" cellpadding="0" cellspacing="0"> 
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span></td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_email" value="<?=$_REQUEST['customer_email']?>"  />		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Password <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="password" name="customer_pwd"  />		  </td>
        </tr>
		<tr>
		  <td width="25%" align="left" valign="middle" class="tdcolorgray" >Confirm Password<span class="redtext">*</span> </td>
		  <td align="left" valign="middle" class="tdcolorgray">		  <input class="input" type="password" name="customer_pwd_cnf"  />	</td>
		  </tr>
		</table></td>
		<td width="50%" class="tdcolorgray">&nbsp;</td>
		</tr>
		<?
		$cur_pos = 'BottomInStatic';
		$formname = 'frmAddCustomer';
		include 'show_dynamic_fields.php';
		?>
		<tr>
		<td colspan="2" >&nbsp;</td>
		</tr>
		<tr>
		<td width="50%" align="left" class="seperationtd">Affiliate</td>
		<td align="left" class="seperationtd">Other</td>
		</tr>
		<tr>
		<td width="50%" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Affiliate</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_anaffiliate" value="1"   />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_AFFILIATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Approved Affiliate</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_approved_affiliate" value="1"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_AFFAPPR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Commission</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_commission" size="3"  />(%)
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_COMMAFF')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Affiliate Tax Id</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_affiliate_taxid"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_AFFTAXID')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</td>
		<td width="50%" valign="top" class="tdcolorgrayleft">
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Bonus Point</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_bonus" size="3"  value="<?=$row_customer['customer_bonus']?>" />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap" >Discount</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="customer_discount" size="3"  value="<?=$row_customer['customer_discount']?>" />
(%)		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shop</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shop_id">
		  <option value="web">web</option>
		  </select>
		  
		  </td>
        </tr>
		<tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Allow Product Discount</td>
          <td width="72%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_allow_product_discount" value="1"  />
		  </td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Use Bonus Point</td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="customer_use_bonus_points" value="1"  />
		  </td>
        </tr>
		</table>
		</td>
		</tr>
		
		<tr>
		<td width="50%">
		<table width="100%" cellpadding="0" cellspacing="0">
		
		
		</table>
		</td>
		</tr>
		<?php
		  $cur_pos = 'Bottom';
		  $formname = 'frmAddCustomer';
		  include 'show_dynamic_fields.php';
		?>
		<tr>
          <td colspan="2" align="left" valign="middle" class="seperationtd" >To recieve newsletters please select from the following:&nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUSTOMERS_NEWSLETT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td></tr>
        <tr>
          <td  align="left" valign="middle" class="tdcolorgray" >
		  <strong>Would you like to receive newsletters </strong><input name="customer_in_mailing_list" id="customer_in_mailing_list" type="checkbox" value="1" <?php echo ($_REQUEST['customer_in_mailing_list'])?'checked':''?> onchange="maillinglist_onchange(this)" />
		  </td>
		  </tr>
		<tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
		  <?
		  $sql_group="SELECT custgroup_id,custgroup_name FROM customer_newsletter_group WHERE sites_site_id=".$ecom_siteid;
		  $res_group = $db->query($sql_group);
		  if($db->num_rows($res_group)>0)
		  {
		  ?>
			  <table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			  <?
			  $tmp_grcnt=0;
			  while($row_group = $db->fetch_array($res_group))
			  {
			  ?>
			  <td><input type="checkbox" name="chk_group[]" value="<?=$row_group['custgroup_id']?>" onchange="mailinglist_mainsel()"/><?=$row_group['custgroup_name']?></td>
			  <?
			  $tmp_grcnt++;
			  if($tmp_grcnt>2)
			  {
			  	echo "</tr><tr>";
				$tmp_grcnt=0;
			  }
			  }
			  ?>
			  </tr>
			  </table>
		  <?
		  }
		  ?>
		  &nbsp;</td>
    </tr>
	</table>
	</div></td></tr>
		<tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2">
		   <div class="editarea_div">
		   <table width="100%">
		   <tr>
		   	<td align="right" valign="middle">		  
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			  <input type="hidden" name="search_compname" id="search_compname" value="<?=$_REQUEST['search_compname']?>" />
			  <input type="hidden" name="search_email" id="search_email" value="<?=$_REQUEST['search_email']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="corporation_id" id="corporation_id" value="<?=$corporation_id?>" />
			  <input type="hidden" name="customer_payonaccount_status" id="customer_payonaccount_status" value="<?=$_REQUEST['customer_payonaccount_status']?>" />
			  <input type="hidden" name="cbo_dept" id="cbo_dept" value="<?=$cbo_dept?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
			  <input name="Submit" type="submit" class="red" value="Submit"/></td>
			</tr>
			</table>
			</div>
        </tr>
  </table>
</form>	  
