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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$short,$long,$medium,$ecom_common_settings;
			
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
					$country_name 				= stripslash_normal($row_country['country_name']);
					$country_arr[$country_id] 	= $country_name;		
					}
			}

			$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['PAYONACC'] = getCaptions('PAYONACC'); 
			$customer_id = get_session_var('ecom_login_customer');
			$sql_customer	= "SELECT * FROM customers  WHERE customer_id=".$customer_id." LIMIT 1";
			$res_customer	= $db->query($sql_customer);
			$row_customer 	= $db->fetch_array($res_customer);
			$row_customer['cbo_country'] 	= $row_customer['country_id'];
			$row_customer['cbo_state'] 		= $row_customer['customer_statecounty'];

?>
<script language="javascript" type="text/javascript">


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

		 <?php
		$HTML_img = $HTML_alert = $HTML_treemenu='';
		
			$HTML_treemenu = '<div class="row breadcrumbs">
				<div class="container">
        <div class="container-tree">
          <ul>
				<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
				 <li> → '.stripslash_normal($Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']).'</li>

			 </ul>
    </div>
  </div></div>';	
  echo $HTML_treemenu;	
		?>
			<form method="post" action="" name="frm_myprofile" id="frm_myprofile" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
					<div class="container">
					<div class="container">
		<?php if($alert){ ?>
				<div class="cart_msg_txt">
				<?php 
						  if($Captions_arr['CUST_REG'][$alert]){
						  		echo "Error !! ". stripslash_normal($Captions_arr['CUST_REG'][$alert]);
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?></div>
		<?php } 
		if($Captions_arr['CUST_REG']['CUSTOMER_DESC']!='')
		{
		?>
		<div class="reg_top_hdr_msg"><?php echo stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_DESC'])?></div>
		<?php
		}
		
	?> 
	 	<input type="hidden" name ="customer_accounttype" value="personal" id ="customer_accounttype">
			   
			   <div class="form-bottom">

				<div class="form-top-left">
						<h3><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER_EDIT'])?></h3>

				</div>
					<div class="alert_red"></div>
				<?php
				//section for custom registration form 	
								
				// Get the list of static fields to be display for business accounts
				$sql_custstat = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
									FROM 
										general_settings_site_checkoutfields 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND field_hidden=0 
										AND field_type='CUSTREG' 
									ORDER BY 
										field_order";
				$ret_custstat = $db->query($sql_custstat);
				if($db->num_rows($ret_custstat))
				{	
					while($row_custstat = $db->fetch_array($ret_custstat))
					{
						if($row_custstat['field_req']==1)
						{
							$chkout_Req[]		= "'".$row_custstat['field_key']."'";
							$chkout_Req_Desc[]	= "'".$row_custstat['field_error_msg']."'";
							switch($row_custstat['field_key'])
							{
								case 'customer_fname':
									$chkout_Special[] 		= "'".$row_custstat['field_key']."'";
									$chkout_Special_req[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_FNAME']."'";
								break;
								case 'customer_mname':
									$chkout_Special[] 		= "'".$row_custstat['field_key']."'";
									$chkout_Special_Req[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_MNAME']."'";
								break;
								case 'customer_surname':
									$chkout_Special[] 		= "'".$row_custstat['field_key']."'";
									$chkout_Special_Req[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_SURNAME']."'";
								break;
								case 'customer_phone':
									$chkout_Special[] 		= "'".$row_custstat['field_key']."'";
									$chkout_Special_Req[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_PHONE']."'";
								break;
							}
						}
				?>
										  <div class="form-group">
																 <label class="sr-onlyA" for="form-<?php echo $row_custstat['field_key'];?>"><?php echo stripslash_normal($row_custstat['field_name']); if($row_custstat['field_req']==1) { echo '<span class="redtext">*</span>';}?></label>


																	<?php
																	$pass_class_arr['txtbox_cls'] 		= 'regiinput'; 
																	$pass_class_arr['txtarea_cls'] 		= 'regiinput';
																	$class_array['txtarea_cls'] = 'regiinput';
																		echo get_Field_responsive($row_custstat['field_key'],$row_custstat['field_name'],$row_customer,array(),'',$pass_class_arr);
																	?>
																  </div>
					
					<?php
					}
				}
				?>
				 <div class="form-group">
<label class="sr-onlyA" for="form-customer_email"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_EMAIL'])?></label>

  <input name="customer_email" class="form-control" id="customer_email"  type="text"  placeholder="Email" value="<?=$row_customer['customer_email_7503']?>" />
  </div>		
	
			  
		 </div>
		 
		 <fieldset>
			   <div class="form-bottom">

	  		<div class="form-group">
<label class="sr-onlyA" for="form-customer_pwd"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PASSWORD'])?><span class="redtext">*</span></label>

  <input name="customer_pwd" class="form-control" id="customer_pwd"  type="password" placeholder="Password" >
  </div>
  <div class="form-group">
<label class="sr-onlyA" for="form-customer_pwd_cnf"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD'])?><span class="redtext">*</span></label>

  <input name="customer_pwd_cnf" class="form-control" id="customer_pwd_cnf"  type="password" placeholder="<?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD'])?>" >
  </div>
  <input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>">
					<input type="hidden" name="action_purpose" value="update" />
  			<input type="submit" id="myprofile_Submit"  name="myprofile_Submit" class="btn-primary-bt topcart-bt" value="<?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON'])?>"/></div>
  </fieldset>	 
		 
			
		
		</div>
		</div>
	</form>
<script language="javascript">
showAccountTypeDetails(document.frm_myprofile.customer_accounttype);
/* Function to validate the Customer Registration */
function validate_allforms(frm)
{
	<?php
	if(count($chkout_Req))
	{
	?>
		fieldRequired 		= Array(<?php echo implode(',',$chkout_Req)?>);
		fieldDescription 	= Array(<?php echo implode(',',$chkout_Req_Desc)?>);	
	<?php	
	}
	else
	{
	?>
		fieldRequired 		= Array();
		fieldDescription 	= Array();
	<?php
	}
	?>
	<?php
	if(count($chkout_Email))
	{
	?>
		fieldEmail 		= Array(<?php echo implode(',',$chkout_Email)?>);	
	<?php	
	}
	else
	{
	?>
		fieldEmail 		= Array();
	<?php
	}
	?>
	<?php
	if(count($chkout_Password))
	{
	?>
		fieldConfirm 		= Array(<?php echo implode(',',$chkout_Password)?>);
		fieldConfirmDesc 	= Array(<?php echo implode(',',$chkout_Password_Desc)?>);
	<?php	
	}
	else
	{
	?>
		fieldConfirm 		= Array();
		fieldConfirmDesc  	= Array();
	<?php
	}
	?>
	<?php
	if(count($chkout_Special))
	{
	?>
		fieldSpecChars 	= Array(<?php echo implode(',',$chkout_Special)?>);
		fieldCharDesc 	= Array(<?php echo implode(',',$chkout_Special_Req)?>);	
	<?php	
	}
	else
	{
	?>
		fieldSpecChars 	= Array();
		fieldCharDesc  	= Array();
	<?php
	}
	?>
	fieldNumeric 		= Array();
	<?php
	if(count($chkoutcompany_Req))
	{
		echo "
			if(frm.customer_accounttype.value=='business')
			{";
		for($i=0;$i<count($chkoutcompany_Req);$i++)
		{
			echo "
					if(frm.".$chkoutcompany_Req[$i].".value=='')
					{
						alert('".$chkoutcompany_Req_Desc[$i]."');
						frm.".$chkoutcompany_Req[$i].".focus();
						return false;
					}
				";
		}
		echo "	
			}
			";							
	}
	?>
	/*
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))
	{
		if(frm.customer_pwd.value !='' || frm.customer_pwd_cnf.value !='')
		{
			fieldRequired 		= new Array('customer_pwd','customer_pwd_cnf');
			fieldDescription 	= new Array('<?php echo $Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?>','<?php echo $Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?>');
			fieldEmail 			= new Array();
			fieldConfirm 		= new Array('customer_pwd','customer_pwd_cnf');
			fieldConfirmDesc 	= new Array('<?php echo $Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?>','<?php echo $Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?>');
			fieldNumeric 		= new Array();
			fieldSpecChars 		= new Array();
			fieldCharDesc 		= new Array();
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))
				return true;
			else
				return false;
		}
		return true;
	}
	else
		return false;	
	*/
		isValid = check_validate_newcommon(fieldRequired,fieldDescription,fieldEmail);	
		return isValid; 	
}
</script>
<?php	
}
	function Display_Message($mesgHeader,$Message)
	{
		global $Captions_arr;
		?>
	<div class="reg_shlf_outr">
	<div class="reg_shlf_inner">
		<div class="reg_shlf_inner_top"></div>
		<div class="reg_shlf_inner_cont">
		<?php
		if($mesgHeader){
		?>
		<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=$mesgHeader?> </span></div></div>
		<? }?>
		<div class="reg_shlf_cont_div">
		<div class="reg_shlf_pdt_con">	
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="reg_table">
		<tr>
		<td align="center" valign="middle" class="regicontentA"><?php echo $Message; ?></td>
		</tr>
		<?php
		if(get_session_var("ecom_login_customer"))
		{
		?>
		<tr>
		<td  valign="middle" class="regicontentA" align="center"><a href="<?=$ecom_hostname?>/myprofile.html" class="message_backlink"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_LINK']);?></a><? /*$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_TEXT'];*/?> </td>
		</tr>
		<? 
		}
		?>
		</table>
		</div>
		</div>
		</div>
		<div class="reg_shlf_inner_bottom"></div>
		</div>
		</div>	
		<?php	
	}
	function Show_Myconsent(){
				global $alert,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$short,$long,$medium;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['CUST_REG'] 	= getCaptions('CUST_REG');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
				$customer_id = ($customer_id)?$customer_id:0;
				if($customer_id>0)
				{
			    $sql_customer	= "SELECT * FROM customers  WHERE customer_id=".$customer_id." LIMIT 1";
				$res_customer	= $db->query($sql_customer);
				$row_customer 	= $db->fetch_array($res_customer);
				
			     $sql = "SELECT * FROM customer_gdpr_consent where sites_site_id=".$ecom_siteid." AND cust_id=".$customer_id." LIMIT 1"; 
			     $ret_sql = $db->query($sql); 
			     $row_sql = $db->fetch_array($ret_sql);
			    }
			     if($row_sql['fname']!='')
			     {
					 $fname = $row_sql['fname'];
					 $lname = $row_sql['lname'];
				 }
				 else
				 {
					$fname = $row_customer['customer_fname'];
					$lname = $row_customer['customer_surname'];
				 }
					?>
					<form method="post" name="add_address" class="frm_cls" action="<?php url_link('gdproptin.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="address_id" value="" />
					<input type="hidden" name="news_id" value="<?php echo $_REQUEST['news_id'] ?>" />
					<input type="hidden" name="action_purpose" />
				  <div class="container-fluid">
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td  align="left" valign="middle" colspan="4"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> consent Form</div></td>
					  </tr>
					 
				<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="4" align="center" valign="middle" class="red_msg">
						- <?php echo $alert?> -
						</td>
					</tr>
			<?php
				}
				
				?>
				<tr>
							<td align="left" valign="middle" class="shoppingcartcontent" colspan="4" ><p><strong>Keeping In Touch - On Your Terms</strong></p>

<p>In May 2018, new regulations come into force. They are designed to give customers more control over what information they receive from businesses and other organisations.</p>

<p>Importantly, they mean that if you want us to stay in touch with you, you will need to give us your permission. Without your consent, we won't be able to send you things like discount codes, product news or special seasonal offers at Eid, Ramadan and other occasions.</p>

<p>By following the simple instructions below, you can give us your permission for us to stay in touch. The form also helps us to know which forms of contact you prefer, and exactly what information you do and don't want to receive. 
</p>
</td>
						</tr>
					  <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Title</td>
					    <td  align="center" >:</td>
					    <td  align="left" >
			<select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($row_sql['customer_title']=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($row_sql['customer_title']=='Mrs.')?"selected":''?>>Mrs.</option>
			<option value="Miss." <?=($row_sql['customer_title']=='Miss.')?"selected":''?>>Miss.</option> 
			<option value="M/S." <?=($row_sql['customer_title']=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
				    </tr>
					  <tr>
					    <td  colspan="4"  align="left" >First Name<span class="redtext">*</span> </td>
					  
				    </tr> 
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp; </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="fname" value="<?=$fname; ?>" maxlength="<?=$short?>"/></td>
				    </tr> 
					 <tr>
					    <td  colspan="4"  align="left" >Last Name<span class="redtext">*</span> </td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
					 <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="lname" value="<?=$lname; ?>" maxlength="<?=$short?>"/></td>
				    </tr>
				      <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
				    <tr>
					    <td  colspan="4"  align="left" >I would like to be contacted in the following way(s).Please tick 'Yes' or 'No' for each option.</td>
					    
				    </tr>
				    <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
					  <tr>
					    <td  colspan="4"  align="left" >Email</td>
					  </tr>
					  <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_email" <?php if($row_sql['through_email']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" <?php if($row_sql['through_email']==0){?> checked="checked" <?php }?> name="through_email"  value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >If Yes Enter Email Id</td>
					    
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="email_id" value="<?=$row_sql['email_id'] ?>" maxlength="<?=$medium?>"/></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >Telephone</td>
					  </tr>
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_phone" <?php if($row_sql['through_phone']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_phone"  <?php if($row_sql['through_phone']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >If Yes Enter Phone Number</td>
					    
				    </tr>
				    <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="phone_number" value="<?=$row_sql['phone_number'] ?>" maxlength="<?=$medium?>"/></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >Post</td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" ></td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_post" <?php if($row_sql['through_post']==1){?> checked="checked" <?php }?> value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_post" <?php if($row_sql['through_post']==0){?> checked="checked" <?php }?>  value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left"  valign="top">If Yes Enter Address here</td>
					   
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right"  valign="top">&nbsp;</td>
					    <td  align="center" valign="top">:</td>
					    <td  align="left" ><textarea  name="postal_address"  rows="5" cols="30" /><?=$row_sql['postal_address'] ?></textarea></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >Text</td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_text" <?php if($row_sql['through_text']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_text" <?php if($row_sql['through_text']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="4"  align="left" >If Yes Enter Text number</td>
					   
				    </tr>	
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="text_number" value="<?=$row_sql['text_number'] ?>" maxlength="<?=$medium?>"/></td>
				    </tr>	
				      <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
				    <tr>
					    <td  colspan="4"  align="left" >
							<table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
				      <tr>
					    <td  colspan="4"  align="left" >I give my permission for Unipad to send me the following.Please tick 'Yes' or 'No' for each option.</td>
					    
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
				      <tr>
					    <td  colspan="4"   align="left" width="50%" >Special seasonal offers/discount codes/new product Range</td>
					   
				    </tr>
				     <tr>
					    <td   colspan="2" align="right"  >&nbsp;</td>
					    <td  align="center" valign="top" >:</td>
					    <td  align="left" valign="top">Yes&nbsp;<input type="radio" name="special_seasonal_offers" <?php if($row_sql['special_seasonal_offers']==1){?> checked="checked" <?php }?>   value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="special_seasonal_offers"  <?php if($row_sql['special_seasonal_offers']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				    <?php
				    /*
				       <tr>
					    <td    align="right" >Invitations to events (such as free taster sessions, store openings, community BBQs etc.)</td>
					    <td  align="center" valign="top">:</td>
					    <td  align="left" valign="top">Yes&nbsp;<input type="radio" name="invitation_to_event" <?php if($row_sql['invitation_to_event']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="invitation_to_event"  <?php if($row_sql['invitation_to_event']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				    
				     <tr>
					    <td    align="right" >Details of customer competitions</td>
					    <td  align="center" valign="top">:</td>
					    <td  align="left" valign="top">Yes&nbsp;<input type="radio" name="details_cust_competitions" <?php if($row_sql['details_cust_competitions']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="details_cust_competitions" <?php if($row_sql['details_cust_competitions']==0){?> checked="checked" <?php }?>  value="0"></td>
				    </tr>
				    */
				    ?> 
				    </table>
				    </td>
				    </tr>
		            
				 
				   <tr>
							<td align="left" valign="middle" class="shoppingcartcontent" colspan="4" ><p><strong>If you change your mind:</strong></br>
If you decide to change your preferences, you can do so at any time. Please just call our customer services team on 07872 377266 during office hours Monday to Friday and let us know what you want us to do. For example, you can call us to update your contact details or you can opt out from communications altogether. Alternatively, you can let us know your wishes by email: <strong><a href="mailto:enquiries@unipad.co.uk">enquiries@unipad.co.uk</a>.</strong></p>
<p><strong>Terms and Privacy:</strong></br>
Unipad will keep your contact details safe. We will not share them with any other person or organisation. We will only contact you by the channels you have chosen (phone, email etc.) and we will only contact you about the subjects you have chosen. You may unsubscribe at any time. For more details, please see our privacy policy and data protection to see how we use and protect your data. <br/>
For more details, please check our <a href="https://www.unipad.co.uk/gdpr-pg50547.html " target="_blank"><strong>data protection</strong></a> to see how we use and protect your data. 
</p>
</td>
						</tr>
				   <tr>
				     <td align="center" valign="middle" class="shoppingcartcontent" colspan="4" ><input class="buttoninput submitEnquiry" type="button" name="Submit" value="Save" onclick="javascript:add_newconsent(document.add_address)" /></td>
			        </tr>	
				</table>
				</div>
				</form>	
				<script language="javascript">
					function add_newconsent(frm)
					{
						//alert(feildmsg);
						fieldRequired 		= Array('fname','lname');
						fieldDescription 	= Array('First Name','Last Name');
						if(frm.through_phone.value==1)
						{
							fieldRequired.push('phone_number');
							fieldDescription.push('Enter phone number');
						}
						if(frm.through_post.value==1)
						{
							fieldRequired.push('postal_address');
							fieldDescription.push('Enter Address');
						}
						if(frm.through_text.value==1)
						{
							fieldRequired.push('text_number');
							fieldDescription.push('Enter Text Number');
						}						
						if(frm.through_email.value==1)
						{
							fieldEmail 			= Array('email_id');
						}
						else
						{
							fieldEmail 			= Array();
						}
						fieldConfirm 		= Array();
						fieldConfirmDesc  	= Array();
						fieldNumeric 		= Array();
						fieldSpecChars 		= Array('fname','lname','phone_number');
						fieldCharDesc       = Array('First Name','Last Name','Phone');
						if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
							   frm.action_purpose.value = 'insert_consent';
							   frm.submit();
						}
						
					}
				</script>
				<?
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
			
