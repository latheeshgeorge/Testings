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
				 <li> &#8594; '.stripslash_normal($Captions_arr['CUST_REG']['EDITPROFILE_TREEMENU_TITLE']).'</li>

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
			
