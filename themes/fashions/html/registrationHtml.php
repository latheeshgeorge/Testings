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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype,$short,$long,$medium,$ecom_common_settings;
			$chkout_Req			= $chkout_Req_Desc	= $chkout_multi= $chkout_multi_msg = $chkout_Special = $chkout_Special_Req = array();	
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
?>
<script type="text/javascript">
function maillinglist_onchange(obj)
{
	if(obj.checked==false)
	{
		for(i=0;i<document.frm_registration.elements.length;i++)
		{
			if (document.frm_registration.elements[i].type =='checkbox' && document.frm_registration.elements[i].name.substr(0,14)=='newsletergroup')
			{ 
				document.frm_registration.elements[i].checked = false;
			}
		}	
	}
}
function mailinglist_mainsel()
{
	var atleast_one = false;
	for(i=0;i<document.frm_registration.elements.length;i++)
	{
		if (document.frm_registration.elements[i].type =='checkbox' && document.frm_registration.elements[i].name.substr(0,14)=='newsletergroup')
		{ 
			if(document.frm_registration.elements[i].checked==true)
				atleast_one = true;
		}
	}	
	if(atleast_one)
		document.frm_registration.customer_in_mailing_list.checked = true;
	
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
						 
				?>				</td>
			</tr>
		<?php 
		} 
		// Caption array
		$custstat_caption_map_array = array(
												'customer_title'		=> $Captions_arr['CUST_REG']['CUSTOMER_TITLE'],
												'customer_fname'		=> $Captions_arr['CUST_REG']['CUSTOMER_FNAME'],
												'customer_mname'		=> $Captions_arr['CUST_REG']['CUSTOMER_MNAME'],
												'customer_surname'		=> $Captions_arr['CUST_REG']['CUSTOMER_SURNAME'],
												'customer_position'		=> $Captions_arr['CUST_REG']['CUSTOMER_POSITION'],
												'customer_buildingname'	=> $Captions_arr['CUST_REG']['CUSTOMER_BUILDING_NAME'],
												'customer_streetname'	=> $Captions_arr['CUST_REG']['CUSTOMER_STREET_NAME'],
												'customer_towncity'		=> $Captions_arr['CUST_REG']['CUSTOMER_TOWN_CITY'],
												'cbo_state'				=> $Captions_arr['CUST_REG']['CUSTOMER_STATE_COUNTY'],
												'customer_postcode'		=> $Captions_arr['CUST_REG']['CUSTOMER_POSTCODE'],
												'cbo_country'			=> $Captions_arr['CUST_REG']['CUSTOMER_COUNTRY'],
												'customer_phone'		=> $Captions_arr['CUST_REG']['CUSTOMER_PHONE'],
												'customer_mobile'		=> $Captions_arr['CUST_REG']['CUST_MOB'],
												'customer_fax'			=> $Captions_arr['CUST_REG']['CUSTOMER_FAX'],
												'customer_compname'		=> $Captions_arr['CUST_REG']['CUSTOMER_COMP_NAME'],
												'customer_comptype'		=> $Captions_arr['CUST_REG']['CUSTOMER_COMP_TYPE'],
												'customer_compregno'	=> $Captions_arr['CUST_REG']['CUSTOMER_COMP_REGNO'],
												'customer_compvatregno'	=> $Captions_arr['CUST_REG']['CUSTOMER_COMP_VATREGNO']
											);
		//section for custom registration form 
		
		$cur_pos = 'Top';
		$section_typ= 'register'; 
		$formname = 'frm_registration';
		$cont_leftwidth = '44%';
		$cont_rightwidth = '56%';
		$cellspacing = 1;
		$cont_class = 'regiconent'; 
		$cellpadding = 1;		
		include 'show_dynamic_fields.php';
		?>
		<!--<tr>
			<td colspan="2" class="regiheader" align="left"><?//=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER']?></td>
		</tr> -->
		<?php
			if($Captions_arr['CUST_REG']['CUSTOMER_DESC']!='')
			{
		?>
				<tr>
					<td colspan="2" align="left" class="regifontnormal"><?=$Captions_arr['CUST_REG']['CUSTOMER_DESC']?></td>
				</tr>
		<?PHP
			}
			//section for custom registration form 
			$cur_pos = 'TopInStatic';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			$cont_leftwidth = '44%';
			$cont_rightwidth = '56%';
			$cellspacing = 1;
			$cont_class = 'regiconent'; 
			$cellpadding = 1;		
			include 'show_dynamic_fields.php';
		?>
		
		<tr>
			<td width="44%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE']?></td>
			<td width="56%" align="left" valign="middle"><select name="customer_accounttype" class="regiinput" id="customer_accounttype" onchange="showAccountTypeDetails(this);" >
			<option value="personal"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL']?></option>
			<option value="business"><?=$Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS']?></option> 
			</select></td>
		</tr>
		<tr  id="companydetails" style="display:none;">
			<td colspan="2">
				<table width="100%" border="0" cellpadding="0" cellspacing="6" align="left" >
					<tr>
						<td colspan="2" class="regiheader"><?=$Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER']?> </td>
					</tr>
					<?php
			  		// Get the list of static fields to be display for business accounts
					$sql_custcompany = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND field_hidden=0 
											AND field_type='CUSTREG_COMPANY' 
										ORDER BY 
											field_order";
					$ret_custcompany = $db->query($sql_custcompany);
					if($db->num_rows($ret_custcompany))
					{	
						while($row_custcompany = $db->fetch_array($ret_custcompany))
						{
							if($row_custcompany['field_req']==1)
							{
								$chkoutcompany_Req[]		= $row_custcompany['field_key'];
								$chkoutcompany_Req_Desc[]	= $row_custcompany['field_error_msg'];
							}	
						?>
							 <tr>
								<td class="regiconent" valign="middle" width="44%" align="left">
								<?php echo stripslashes($custstat_caption_map_array[$row_custcompany['field_key']]); if($row_custcompany['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
								</td>
								<td width="56%" align="left" valign="middle">
								<?php
								$pass_class_arr['txtbox_cls'] 		= 'regiinput'; 
								$pass_class_arr['txtarea_cls'] 		= 'regiinput'; 
								echo get_Field($row_custcompany['field_key'],$_REQUEST,array(),'',$pass_class_arr);
								?>
								</td>
							  </tr>
						<?php	
						}
					}
			  ?>
				</table>			</td> 
		</tr>
		
		<tr>
			<td colspan="2" class="regiheader" align="left"><?=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER']?></td>
		</tr>
		<?php
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
						<tr>
							<td class="regiconent" valign="middle" align="left" width="44%">
							<?php echo stripslashes($custstat_caption_map_array[$row_custstat['field_key']]); if($row_custstat['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
							</td>
							<td align="left" valign="middle" width="56%">
							<?php
								$pass_class_arr['txtbox_cls'] 		= 'regiinput'; 
								$pass_class_arr['txtarea_cls'] 		= 'regiinput'; 
								echo get_Field($row_custstat['field_key'],$_REQUEST,array(),'',$pass_class_arr);
							?>
						</td>
						</tr>
					<?php
					}
				}	
		?>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="25" value="<?=$_REQUEST['customer_email']?>" maxlength="<?=$medium?>"/></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="25" value="" /></td>
		</tr>	
		<tr>
			<td align="left" valign="middle" class="regiconent"><?=$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']?><span class="redtext">*</span></td>
			<td align="left" valign="middle"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="25" value="" /></td>
		</tr>
		<?php 
			$chkout_Req[]			= "'customer_email'";
			$chkout_Req_Desc[]		= "'".$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']."'";
			$chkout_Req[]			= "'customer_pwd'";
			$chkout_Req_Desc[]		= "'".$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']."'";
			$chkout_Req[]			= "'customer_pwd_cnf'";
			$chkout_Req_Desc[]		= "'".$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']."'";
			
			$chkout_Email[]			= "'customer_email'";
			$chkout_Password[]		= "'customer_pwd'";
			$chkout_Password_Desc[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_PASSWORD']."'";
			$chkout_Password[]		= "'customer_pwd_cnf'";
			$chkout_Password_Desc[]	= "'".$Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD']."'";
			//section for custom registration form 
			$cur_pos = 'BottomInStatic';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			$cont_leftwidth = '44%';
			$cont_rightwidth = '56%';
			$cellspacing = 1;
			$cont_class = 'regiconent'; 
			$cellpadding = 1;		
			include 'show_dynamic_fields.php';
	
			//section for custom registration form 
			$cur_pos = 'Bottom';
			$section_typ= 'register'; 
			$formname = 'frm_registration';
			$cont_leftwidth = '44%';
			$cont_rightwidth = '56%';
			$cellspacing = 1;
			$cont_class = 'regiconent'; 
			$cellpadding = 1;		

			include 'show_dynamic_fields.php';
			// Check whether payonaccount feature is active in current website			 
			if($ecom_common_settings['paytypeCode']['pay_on_account']['paytype_code']=='pay_on_account')
			{
			?>
			<tr>
		<td colspan="2" valign="top" align="left">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					  <input type="checkbox" name="customer_payonaccount_status" value="1"  /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['PAY_ON_ACCOUNT']?></td>
						
					</tr>
		  </table>		</td>
		</tr>
		<?php 
			}
		?>	
		<tr>
		<td colspan="2" valign="top" align="left">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					  <input type="checkbox" name="chk_newsletter" value="1"  /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['REQUEST_NEWS_RECEIVE_NEWPROD'] //RECEIVING_NEWSLETTER?></td>  
						
					</tr>
		  </table>		</td>
		</tr>
			<?
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
			<td align="left" valign="top" class="" colspan="2">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					 <input type="checkbox" name="customer_in_mailing_list" id="customer_in_mailing_list" value="1" onchange="maillinglist_onchange(this)" <?php if ($_REQUEST['customer_in_mailing_list']==1) echo 'checked="checked"';?> /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?></td>  
						
					</tr>
		  </table>	
			</td>
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
						
							
							<input type="checkbox" name="newsletergroup[]" onchange="mailinglist_mainsel()" value="<?=$customer_grp['custgroup_id']?>" <?=($customer_grp['custgroup_id']==$customer_grp['selected_grp'])?"checked":""?> />						</td>
						
						<td  align="left" valign="middle" width="30%"><?=$customer_grp['custgroup_name']?></td>
						<?php 
							if(	$grp_cnt==3)
							{
								echo "</tr><tr>";
								$grp_cnt =0;
							}
						}?>
					</tr>
		  </table>		</td>
		</tr>
		
		<?php
		}
		else
		{
		?>
			<tr>
				<td align="left" valign="top" class="" colspan="2">
				<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
					<tr>
				
					<td   align="left" valign="middle" width="4%">
					 <input type="checkbox" name="customer_in_mailing_list" id="customer_in_mailing_list" value="1" onchange="maillinglist_onchange(this)" <?php if ($_REQUEST['customer_in_mailing_list']==1) echo 'checked="checked"';?> /></td>
					<td  align="left" valign="middle" width="96%">
					<?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER_ONLY']?></td>  
					</tr>
			  	</table>	
				</td>
			</tr>
		<?php
		}
		?>
		
		<?PHP
		 if($Settings_arr['imageverification_req_customreg']) {?>
		<tr>
			<td class="regiconent" align="right"><?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_CODE']?></td>
			<td align="left" valign="middle"><?php 
			// showing the textbox to enter the image verification code
			$vImage->showCodBox(1,'registration_Vimg','class="inputA_imgver"'); 
			?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="left" valign="middle">
				<img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=registration_Vimg')?>" border="0" alt="Image Verification"/>			</td>
		</tr><? }?>
		<tr>
			<td align="left" valign="middle" class="regiconent">
				<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>"/>
				<input type="hidden" name="action_purpose" value="insert" />
				<input type="hidden" name="pagetype" value="<?PHP echo $_REQUEST['pagetype']; ?>" />			</td>
			<td align="left" valign="middle"><input name="registration_Submit" type="submit" class="buttongray" id="registration_Submit" value="<?=$Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON']?>" /></td>
		</tr>
	</table>
		</form>
		<script type="text/javascript">
			function validateChkorRadio(frm,objstr)
			{ 
				var result = false;
				for(var i=0; i<frm.elements.length; i++)
				{ 
					if (frm.elements[i].name == objstr)
					{
						if(frm.elements[i].checked==true ) 
							result = true;
					}		
				} 
				return result;
			} 
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
				if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc))
				{
					<?php 
					if(count($chkout_multi))
					{
						for($i=0;$i<count($chkout_multi);$i++)
						{
							echo "
									if(!validateChkorRadio(frm,'".$chkout_multi[$i]."[]'))
									{
										alert('".$chkout_multi_msg[$i]."');
										document.getElementById('".$chkout_multi[$i]."[]').focus();
										return false;
									}
								";
						}
					}
					if($Settings_arr['imageverification_req_customreg'])
					{ // code for validating the image verification- needs only if it is enabled
					?>
						if(frm.registration_Vimg.value=='')
						{
							alert('Enter- verification Code');
							frm.registration_Vimg.focus();
							return false;
						}
						else
						{
							return true;
						}
					<?php	
					}
					else
					{
					?>
						return true;
					<?php
					}
					?>
				}
				else
					return false;	
			}
			</script>	
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" ><?php echo $mesgHeader;?></td>
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
      </tr>
        </table>
		<?php	
		}
		function Display_Login()
		{
			$show_morelinks = 0;
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
			$Captions_arr['CART'] 				= getCaptions('CART');
			$Captions_arr['CUST_LOGIN'] 	= getCaptions('CUST_LOGIN');
			$pagetype = $_REQUEST['pagetype'];
			if($pagetype == 'cart') {
				$redirect_back = 1;		
			}
			else if($pagetype=='enquire' or $pagetype=='prodhtml' or $pagetype=='priceprom') {
				$redirect_back = 1;		
			}
			else {
				$redirect_back = '';				
			} 
			if($pagetype=='prodhtml')
				$pass_url = $_REQUEST['pricepromise_url'];
			else
				$pass_url = $_REQUEST['pass_url'];
		?>
		<form name="frm_custlogin" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
		<table class="regifontnormal" border="0" cellpadding="0" cellspacing="4" width="100%">
		<tbody>
		<tr>
			<td colspan="2" class="shoppingcartlogintdtext1"> </td>
		</tr>
		<tr>
			<td colspan="2" class="message_header" align="left" valign="middle"><?php echo $Captions_arr['CART']['CART_LOGIN']?></td>
		</tr>
		<tr>
			<td colspan="2" align="left" valign="middle"  class="regiconent">
			<div id="RemoveUsernameBanner">
			<?php echo $Captions_arr['CUST_LOGIN']['TOP_MSG']?>
			</div>
			</td>
		</tr>
		<tr>
			<td class="regiconent" align="left" valign="top" width="46%">
			<div class="new-customer">
			<div class="custo_header"><?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST']?></div>
			
			<div class="custo_text"><?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST_TOP_MSG']?></div>	
			<ul>
			<?php
				if($Captions_arr['CUST_LOGIN']['NEW_CUST_CATLOG_MESG']!='')
				{
				?>
				<li> <?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST_CATLOG_MESG']?></li>
				<?
				 }
				 if($Captions_arr['CUST_LOGIN']['NEW_CUST_ENQ_MESG']!='')
				{
				?>
				<li> <?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST_ENQ_MESG']?></li>
				<?
				 }
				 if($Captions_arr['CUST_LOGIN']['NEW_CUST_PAYON_MESG']!='')
				{
				?>
				<li> <?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST_PAYON_MESG']?></li>
				<?
				 }
				 if($Captions_arr['CUST_LOGIN']['NEW_CUST_WISH_MESG']!='')
				{
				?>
				<li> <?php echo $Captions_arr['CUST_LOGIN']['NEW_CUST_WISH_MESG']?></li>
				<?
				 }
				 ?>
			</ul>
			<a href="<?php url_link('registration.html')?>" class="cust_login_button">Create New Account&nbsp;</a></div>
			</td>
			<td width="54%" align="left" valign="top" class="regiconent">
		
			<div class="existing-customer">
			<div class="custo_header">Customers Login</div>
			
			
			<div class="cust_loginA"><?php echo $Captions_arr['CART']['CART_EMAIL']?></div>
			
			<div class="cust_loginB"><input  class="textfeild" id="custlogin_uname" name="custlogin_uname" value="" maxlength="255" type="text"></div>
			
			<div class="cust_loginA"><?php echo $Captions_arr['CART']['CART_PASSWORD']?></div>
			<div class="cust_loginB"><input  class="textfeild" name="custlogin_pass" id="custlogin_pass" maxlength="32" type="password"></div>
			<div class="cust_loginA"></div>
			<div class="cust_loginB">
			<input type="hidden" name="redirect_back" value="<?PHP echo $redirect_back; ?>" /> 
			<input type="hidden" name="pass_url" id="pass_url" value="<?php echo $pass_url?>" />
			<input type="hidden" name="pagetype" id="pagetype" value="<?php echo $pagetype; ?>" />
			<input type="hidden" name="prom_id" id="prom_id" value="<?php echo $_REQUEST['prom_id']; ?>" />
			<? if($pagetype == 'cart')
			{
			?>			
			<input type="hidden" name="cart_mod" value="show_cart" /> 
			<input name="custcartlogin_Submit" type="submit" class="buttonred_cart" value="Login" onclick="document.frm_custlogin_cart.submit();" />
			<? }
			elseif($pagetype == 'enquire')
			{
			?>
			<input type="hidden" name="enq_mod" value="show_enquiry" /> 
			<input name="custenquirelogin_Submit" type="submit" class="buttonred_cart" value="Login" onclick="document.frm_custlogin_enquire.submit();" />
			<?
			} else {?>
			<input name="custenquirelogin_Submit" type="submit" class="buttonred_cart" value="Login" onclick="document.frm_custlogin.submit();" />
			<? } ?>
			
			</div>
			<?php
			if($show_morelinks==1)
			{
			if($hide_newuser==0) // check whether new user link is disabled from main shop settings
			{
			?>
			<a href="<?php url_link('registration.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?>"><?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?></a>
			<?php
			}
			if($hide_forgotpass==0) // check whether the forgot password link is disabled from main shop settings
			{
			?>				
			<a href="<?php url_link('forgotpassword.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?>"><?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?></a>	
			<?php
			}
			}
			if($_REQUEST['pagetype']=='prodhtml')
			{
				$checkarray = array ('redirect_back','pass_url','pagetype','return_nocheck','custlogin_uname','custlogin_pass');
				foreach ($_REQUEST as $k=>$v)
				{
					if (!in_array($k,$checkarray))
					{
						echo '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
					}
				}
			}			
			?>								
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right"></td>
		</tr>
		</tbody>
		</table>
		</form>
		<?php	
		}
		function Forgot_Password(){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
		$Captions_arr['FORGOT_PASSWORD'] = getCaptions('FORGOT_PASSWORD'); // to get values for the captions from the general settings site captions
		?>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_HEADER']?></div>
			<form method="post" action="" name="frm_forgotpassword" id="frm_forgotpassword" class="frm_cls" onsubmit="return validate_form(this);">
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regitable">
      <tr>
        <td colspan="2" align="left" valign="middle" class="regiconent"><?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_MESSAGE']?></td>
      </tr>
	  <tr>
        <td width="35%" align="left" valign="middle" class="regiconent"><?=$Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_LABEL']?></td>
		<td width="65%" align="left" valign="middle" class="regiconent"><input name="forgotpwd_email" type="text" class="regiinput" id="forgotpwd_email" size="25" value=""  /></td>
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
		
		function show_Middle_Login()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
			$Captions_arr['CUST_LOGIN'] 	= getCaptions('CUST_LOGIN'); // to get values for the captions from the general settings site captions
			$cust_id 								= get_session_var("ecom_login_customer");
			if (!$cust_id) // case customer is not logged in
			{
				$hide_newuser 					=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 				=  $Settings_arr['hide_forgotpass'];
				?>
				<form name="frm_custlogin" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
				<table border="0" cellpadding="0" cellspacing="0" class="logintable">
				<?php   
				
				if ($title)
				{
				?>
					<tr>
					<td colspan="2" class="logintableheader"><?php echo $title?></td>
					</tr>
				<?php
				}
				?>		
				<tr>
					<td class="logintablecontent"><?php echo $Captions_arr['CUST_LOGIN']['EMAIL']?></td>
						<td align="right" valign="top" class="logintablecontentright"><input name="custlogin_uname" type="text" class="inputA" id="custlogin_uname" size="15" /></td>
					</tr>
					<tr>
						<td class="logintablecontent"><?php echo $Captions_arr['CUST_LOGIN']['PASSWORD']?></td>
						<td align="right" valign="top" class="logintablecontentright"><input name="custlogin_pass" type="password" class="inputA" id="custlogin_pass" size="15" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="right" valign="top" class="logintablecontentright"> <input name="custologin_Submit" type="submit" class="buttongray" id="custologin_Submit" value="<?php echo $Captions_arr['CUST_LOGIN']['LOGIN']?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
					<?php
					if($hide_newuser==0) // check whether new user link is disabled from main shop settings
					{
					?>
					<a href="<?php url_link('registration.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?>"><?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?></a>
					<?php
					}
					if($hide_forgotpass==0) // check whether the forgot password link is disabled from main shop settings
					{
					?>
					<a href="<?php url_link('forgotpassword.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?>"><?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?></a>
					<?php
					}
					?>		
					</td>
				</tr>
				</table>
				<input type="hidden" name="redirect_back" value="0" /> 
				</form>
			<?php	
			}
		}
	};	
		
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
			