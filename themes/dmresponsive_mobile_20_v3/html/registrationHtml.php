<?php
/*############################################################################
	# Script Name 	: registrationHtml.php
	# Description 	: Page which holds the display logic for adding a customer(customer registration)
	# Coded by 		: Sny
	# Created on	: 17-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class registration_Html
	{
		// Defining function to show the site review
		function Show_Registration($alert)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype,$short,$long,$medium,$ecom_common_settings;
			
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
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
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
	<?php
		$HTML_img = $HTML_alert = $HTML_treemenu='';

$HTML_treemenu = '	<div class="breadcrump">
					<nav class="breadcrumb">
						 <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>     
						 <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['CUST_REG']['REGISTRATION_TREEMENU_TITLE']).'</span>					</nav>
				
				</div>';
				
				
				
  echo $HTML_treemenu;	
		?>	
		<form method="post" action="" name="frm_registration" id="frm_registration" class="frm_cls" onsubmit="return validate_allforms(this);">
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />
							<div class="inner-bg">

					<div class="container">
						<div class="container">

           <div class="reg_top_hdr">

		<?php if($alert){ ?>
				<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt alert alert-danger">
				<?php 
				  if($Captions_arr['CUST_REG'][$alert]){
						echo "Error !! ". stripslash_normal($Captions_arr['CUST_REG'][$alert]);
				  }else{
						echo  "Error !! ". $alert;
				  }
				 
				?>			</div>
				<div class="cart_msg_bottomA"></div>
				</div>
		<?php 
		} 
		
		if($Captions_arr['CUST_REG']['CUSTOMER_DESC']!='')
		{
		?>
		 <div class="reg_top_hdr_msg"><?php echo stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_DESC'])?></div>
		<?php
		}
		/*
		?>	
			 <div class="reg_top_txt"><div><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE'])?></div><div>
			<select name="customer_accounttype" class="form-control" id="customer_accounttype" onchange="showAccountTypeDetails(this);" >
			<option value="personal"><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL'])?></option>
			<option value="business"><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS'])?></option> 
			</select>
			</div></div>
		 
		<?php
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
		?>
	<div class="inner_con"  id="companydetails" style="display:none;" >
     	<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
           <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
          <fieldset>
			  														<div class="form-bottom">

														<div class="form-top-left">
															    <h3><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER'])?></h3>

															</div>
															<div class="alert_red"></div>
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
							 
							  <div class="form-group">
																 <label class="sr-only" for="form-<?php echo $row_custcompany['field_key'];?>"><?php echo stripslash_normal($row_custcompany['field_name']); if($row_custcompany['field_req']==1) { echo '<span class="redtext">*</span>';}?></label>


																	<?php
																	$class_array['txtarea_cls'] = 'regiinput';
																		echo get_Field_responsive($row_custcompany['field_key'],$row_custcompany['field_name'],$_REQUEST,array(),'',$pass_class_arr);
																	?>
																  </div>
						<?php	
						}
					}
			  ?>
          </div>
          </fieldset>
           </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>
	 	 </div>
	
</div>
*/
?>
<input type="hidden" name ="customer_accounttype" value="personal" id ="customer_accounttype">
			
			<fieldset>
																		<div class="form-bottom">

														<div class="form-top-left">
															    <h3><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER'])?></h3>

														</div>
															<div class="alert_red"></div>	<?php
				//section for custom registration form 
				
				$cur_pos 				= 'TopInStatic';
				$section_typ			= 'register'; 
				$formname 				= 'frm_registration';
				$cont_leftwidth 		= '50%%';
				$cont_rightwidth 		= '50%%';
				$cellspacing 			= 0;
				$head_class				= 'regiheader';
				$specialhead_tag_start 	= '<span class="reg_header"><span>';
				$specialhead_tag_end 	= '</span></span>';
				$cont_class 			= 'regiconentA'; 
				$texttd_class			= 'regi_txtfeildA';
				$cellpadding 			= 0;		
				$table_class            = 'reg_table';		
				//include 'show_dynamic_fields.php';
				
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
																		echo get_Field_responsive($row_custstat['field_key'],$row_custstat['field_name'],$_REQUEST,array(),'',$pass_class_arr);
																	?>
																  </div>
					<?php
					}
				}
				?>            
              <div class="form-group">
<label class="sr-onlyA" for="form-customer_email"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_EMAIL'])?></label>

  <input name="customer_email" class="form-control" id="customer_email"  type="text"  placeholder="Email" value="<?=$_REQUEST['customer_email']?>" />
  </div>
              			  
			  <?php
			//section for custom registration form 
			$cur_pos 				= 'BottomInStatic';
			$section_typ			= 'register'; 
			$formname 				= 'frm_registration';
			$cont_leftwidth 		= '50%';
			$cont_rightwidth 		= '50%';
			$cellspacing 			= 0;
			$head_class				= 'regiheader';
			$specialhead_tag_start 	= '<span class="reg_header"><span>';
			$specialhead_tag_end 	= '</span></span>';
			$cont_class 			= 'regiconentA'; 
			$texttd_class			= 'regi_txtfeildA';
			$cellpadding 			= 0;		
			$table_class            = 'reg_table';		
			//include 'show_dynamic_fields.php';
		  ?>
          </div>
          </fieldset>
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
  			
         
			<?
			//section for custom registration form 
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
			
				
			?>
         
		   		
	  	<? 
			// to list the news letter groups
			$sql_customer_grp = "SELECT  custgroup_id,custgroup_name  
								FROM 
									customer_newsletter_group 
								WHERE 
									custgroup_active = 1 AND sites_site_id=".$ecom_siteid;
			$ret_customer_grp = $db->query($sql_customer_grp);
			?>
		
			<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
	   	
			 
				   <table class="reg_shlf_inner_table" width="100%" border="0" cellpadding="2" cellspacing="0">
					<tbody>
					<?php
					 if($Settings_arr['imageverification_req_customreg'])
					 {
					?>
					  <tr class="regitable">
					  <td colspan="2" align="left">					  </td>
					  </tr><tr>
					  <td colspan="3" align="left">					</td>
					  </tr>
					  <tr>
					    <td colspan="3" align="left"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_CODE'])?> </td>
					    </tr>
					  <tr>
					    <td align="left"><img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=registration_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1')?>" border="0" alt="Image Verification" class="captcha"/></td>
					   <td align="left" colspan="2">&nbsp;</td>
					    </tr>
					    <tr>
					    <td colspan="3" align="left">  <div class="imgver_text"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_LONG_TEXT'])?></div></td>
					    </tr>
					  <tr>
					    <td colspan="3" align="left"><?php 
							// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'registration_Vimg','class="img_input"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div></td>
					    </tr>
						<?php
						}
						/*
						?>
					  <tr>
						<td width="19%"><div class="cart_shop_cont" style="float:left;"><div>
						<input type="submit" value="<?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON'])?>" class="inner_btn_red" />
						</div></div></td>
						<td colspan="2" align="left">&nbsp;</td>
						</tr>
						*/
						?> 
						<tr>
						<td colspan="3" align="center"></td>
						</tr>
					</tbody>
				  </table>
             </div>
           </div>
            </div>
            </div>
           <button type="submit" id="chkbutton-bill" class="btn btn-lg btn-primary btn-block regbt" ><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON'])?></button>
</div>
  </fieldset>
            </div>
            </div>
            </div>
			<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>"/>
			<input type="hidden" name="action_purpose" value="insert" />
			<input type="hidden" name="pagetype" value="<?PHP echo $_REQUEST['pagetype']; ?>" />
			<input type="hidden" name="registration_Submit" value="1" />
			</form>
			</div>
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
					//alert(fieldRequired);
					for (var i = 0; i < fieldConfirmEmail.length; i++)
					{ 
						var obj11 = frm.elements[fieldConfirmEmail[i]];
						var obj22 = frm.elements[fieldConfirmEmail[i+1]];
						if (obj11 && obj22)
						{
						 if(obj11.value != obj22.value)
						  {
									alertConfirm  = "Email Id and Confirmation Email Not Matching";
									alert(alertConfirm);
									obj11.focus();
									obj11.select();
									return false;
						  } // END IF obj1.value
						} // END IF obj1
					}
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
		function Display_Message($mesgHeader,$Message)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
		?>
				<table width="100%" border="0" cellspacing="4" cellpadding="0">
				<tr>
				<td align="left" valign="middle" class="regicontentA"><?php echo $Message; ?></td>
				</tr>
				</table>
		<?php	
		}
		function Display_Login()
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype;
		$Captions_arr['CART'] = getCaptions('CART');
		$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
		$pagetype = $_REQUEST['pagetype'];
		$msgtype = $_REQUEST['msgtype'];
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
		<div class="container">
		<form class="form-signin" name="frm_custlogin_middle" id="frm_custlogin_middle" method="post" action="" onsubmit="return validate_login(this)">
        <input type="hidden" name="redirect_back" value="<?PHP echo $redirect_back; ?>" /> 
				<input type="hidden" name="pass_url" id="pass_url" value="<?php echo $pass_url?>" />
				<input type="hidden" name="pagetype" id="pagetype" value="<?php echo $pagetype; ?>" />
				<input type="hidden" name="prom_id" id="prom_id" value="<?php echo $_REQUEST['prom_id']; ?>" />
				<? if($pagetype == 'cart')
				{
				?>			
					<input type="hidden" name="cart_mod" value="show_cart" /> 
					<input type="hidden" name="custcartlogin_Submit" value="Login" />
				<? 
				}
				elseif($pagetype == 'enquire')
				{
				?>
					<input type="hidden" name="enq_mod" value="show_enquiry" /> 
					<input type="hidden" name="custenquirelogin_Submit" value="Login" />
				<?
				}
				else
				{
				?>
					<input type="hidden" name="custenquirelogin_Submit" value="Login" />
				<? 
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
        <h1 class="h3 mb-3 font-weight-normal"><?php echo stripslash_normal($Captions_arr['CART']['CART_LOGIN'])?></h1>
        <label for="inputEmail" class="sr-only"><?php echo stripslash_normal($Captions_arr['CART']['CART_EMAIL'])?></label>
        <input id="custlogin_uname" class="form-control" name="custlogin_uname" placeholder="Email address" required="" autofocus="" type="email">
        <label for="inputPassword" class="sr-only"><?php echo stripslash_normal($Captions_arr['CART']['CART_PASSWORD'])?></label>
        <input id="custlogin_pass" name="custlogin_pass" class="form-control" placeholder="Password" required="" type="password">
        
        <button class="btn btn-lg btn-primary btn-block regbt" type="submit" onclick="javascript:document.frm_custlogin_middle.submit()" name="button3" id="button3" >Sign in</button>
      <div class="checkbox1">
          <a class="signup_a" href="<?php url_link('registration.html')?>">Signup Now</a>
			<a href="<?php url_link('forgotpassword.html')?>" class="lgn_txt_link"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?></a> 
        </div>
      </form>
      </div>
  		
		<?php	
		}
		function Forgot_Password(){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr;
		$Captions_arr['FORGOT_PASSWORD'] = getCaptions('FORGOT_PASSWORD'); // to get values for the captions from the general settings site captions
		$HTML_img = $HTML_alert = $HTML_treemenu='';
				
				
			
  $HTML_treemenu = '	<div class="breadcrump">
					<nav class="breadcrumb">
						 <a class="breadcrumb-item" href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>     
						 <span class="breadcrumb-item active">'.stripslash_normal($Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_HEADER']).'</span>					</nav>
				
				</div>';
				
  echo $HTML_treemenu;	
		?>
			<div class="container-fluid">
			<div class="container forgotcont">
				<div class="form-bottom">

				<form method="post" action="" name="frm_forgotpassword" id="frm_forgotpassword" class="frm_cls" onsubmit="return validate_form(this);">
				<div class="inner_header"><?php echo $mesgHeader;?></div>


				<div class="cart_top_info_link"><?=stripslash_normal($Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_MESSAGE'])?></div>
				<div class="form-group">
				<label class="sr-only" for="form-customer_email"><?=stripslash_normal($Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_LABEL'])?></label>

				<input name="forgotpwd_email" class="form-control" id="forgotpwd_email"  type="text"  placeholder="Email"  />
				</div>
				</div>
				    <input name="action_purpose" id="action_purpose" type="hidden" value="ForgotPassword_send">
                    <input name="forgotpassword_Submit" id="forgotpassword_Submit" type="hidden" value="1">

				<input name="forgotpassword_Submit" type="submit" id="forgotpassword_Submit" class="btn btn-lg btn-primary btn-block regbt" value="<?=stripslash_normal($Captions_arr['FORGOT_PASSWORD']['FORGOT_PWD_SUBMIT'])?>">

				</div>

		   </form>
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
				fieldSpecChars 		= Array();
				fieldCharDesc       = Array();
				/*
				if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
				return true;
				}
				else
				{
				return false;
				}
				*/
				isValid = check_validate_newcommon(fieldRequired,fieldDescription,fieldEmail);	
		    return isValid;
				
			}
		</script>
		</div>
		</div>
		<?php	
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
