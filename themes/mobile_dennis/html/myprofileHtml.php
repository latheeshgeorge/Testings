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
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
			
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
			<form method="post" action="" name="frm_myprofile" id="frm_myprofile" class="frm_cls" onsubmit="return validate_allforms(this);">
			<input type="hidden" name="fpurpose" id="fpurpose" value="" />
		 <?php
		$HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
		?>
 <div class="reg_top_outr">
           <div class="reg_top_hdr">
		<?php if($alert){ ?>
			<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">
				<?php 
						  if($Captions_arr['CUST_REG'][$alert]){
						  		echo "Error !! ". stripslash_normal($Captions_arr['CUST_REG'][$alert]);
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>			</div>
				<div class="cart_msg_bottomA"></div>
				</div>
		<?php } 
		if($Captions_arr['CUST_REG']['CUSTOMER_DESC']!='')
		{
		?>
		<div class="reg_top_hdr_msg"><?php echo stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_DESC'])?></div>
		<?php
		}
		?>
		<div class="reg_top_txt"><div><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE'])?></div><div>
			<select name="customer_accounttype" class="regiinput" id="customer_account_type" onchange="showAccountTypeDetails(this);" >
			<option value="personal" <?=($row_customer['customer_accounttype']=='personal')?"selected":''?>><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE_PERSONAL'])?></option>
			<option value="business" <?=($row_customer['customer_accounttype']=='business')?"selected":''?>><?=stripslash_normal($Captions_arr['CUST_REG']['ACCNT_TYPE_BUSINESS'])?></option> 
			</select></div></div>
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
	</div>
	<div class="reg_top_bottom"></div>
	</div>
	
	<div class="inner_con"  id="companydetails" style="display:none;" >
     	<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
           <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_COMPANY_DETAILS_HEADER'])?></span></div></div>
           <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con"> 
          <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" class="reg_table">
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
								<td class="regiconentA" valign="middle" width="50%" align="left">
								<?php echo stripslashes($custstat_caption_map_array[$row_custcompany['field_key']]); if($row_custcompany['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
								</td>
								<td width="50%" align="left" valign="middle" class="regi_txtfeildA">
								<?php
								$pass_class_arr['txtbox_cls'] 		= 'regiinput'; 
								$pass_class_arr['txtarea_cls'] 		= 'regiinput'; 
								echo get_Field($row_custcompany['field_key'],$row_customer,array(),'',$pass_class_arr);
								?>
								</td>
							  </tr>
						<?php	
						}
					}
			  ?>
				</table>
	 </div>
           </div>
           </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>
	</div>	  
	 	
			<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
           <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER_EDIT'])?></span></div></div>
           <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con"> 
	   <table  width="100%" border="0" cellpadding="0" cellspacing="0" class="reg_table">
           <tbody>
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
						<tr>
							<td  valign="middle" align="left" width="50%" class="regi_txtfeildA">
							<?php echo stripslashes($custstat_caption_map_array[$row_custstat['field_key']]); if($row_custstat['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
							</td>
							</tr>
							<tr>
							<td align="left" valign="middle" class="regi_txtfeildA" width="50%">
							<?php
								$pass_class_arr['txtbox_cls'] 		= 'regiinput'; 
								$pass_class_arr['txtarea_cls'] 		= 'regiinput'; 
								echo get_Field($row_custstat['field_key'],$row_customer,array(),'',$pass_class_arr);
							?>
						</td>
						</tr>
					<?php
					}
				}
				?>
		<tr>
			<td align="left" valign="middle" class="regi_txtfeildA" width="50%"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_EMAIL'])?><span class="redtext">*</span></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="regi_txtfeildA" width="50%"><input name="customer_email" type="text" class="regiinput" id="customer_email" size="25" value="<?=$row_customer['customer_email_7503']?>" maxlength="<?=$medium?>"/></td>
		</tr>
	
		 </tbody>
        </table>	  
		 </div>
           </div>
		 </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>
		<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
		    <div class="reg_shlf_hdr_outr"><br><br><br>&nbsp;<div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_PASSWORD'])?></span></div></div>
             <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="reg_table">
		 <tr >
			<td align="left" valign="middle" class="regiconentA"  width="50%"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_PASSWORD'])?></td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="regi_txtfeildA" width="50%"><input name="customer_pwd" type="password" class="regiinput" id="customer_pwd" size="25" value="" /></td>
		</tr>	
		 <tr >
			<td align="left" valign="middle" class="regiconentA" width="50%"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_CONF_PASSWORD'])?> </td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="regi_txtfeildA" width="50%"><input name="customer_pwd_cnf" type="password" class="regiinput" id="customer_pwd_cnf" size="25" value="" /></td>
		</tr>	
		</table>
		 </div>
           </div>
		 </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>	
		<div class="reg_shlf_outr">
		<div class="reg_shlf_inner">
		<div class="reg_shlf_inner_top"></div>
		<div class="reg_shlf_inner_cont">
		<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
		<td class="regi_button" >
				<div class="cart_top_links"><div class="cart_shop_cont"><div>
					<input type="hidden" name="customer_referred_by" value="<?=$_REQUEST['customer_referred_by']?>">
					<input type="hidden" name="action_purpose" value="update" />			
					<input name="myprofile_Submit" type="submit" class="inner_btn_red" id="myprofile_Submit" value="<?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_SAVE_BUTTON'])?>" />
				</div>
				</div>
			</div>
		</td>
		</tr>
		</tbody>
			  </table>
			 </div>
		<div class="reg_shlf_inner_bottom"></div>
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
}
</script>
<?php	
}
	function Display_Message($mesgHeader,$Message)
	{
		global $Captions_arr;
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
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
		<td  valign="middle" class="regicontentA" align="center"><a href="<?=$ecom_selfhttp.$ecom_hostname?>/myprofile.html" class="message_backlink"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_LINK']);?></a><? /*$Captions_arr['CUST_REG']['CUSTOMER_UPDATE_SUCESSFULL_GO_BACK_TEXT'];*/?> </td>
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
			