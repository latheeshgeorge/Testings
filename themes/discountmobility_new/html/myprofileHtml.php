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
				while ($row_country = $db->fetch_array($ret_country))
				{
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
			if($ecom_is_country_textbox!=1)
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
		$formname = 'frm_myprofile';
		$cont_leftwidth 		= '43%';
		$cont_rightwidth 		= '57%';
		include 'show_dynamic_fields_myprofile.php';
		?>
	
		<?PHP
			//section for custom registration form 
			$cur_pos = 'TopInStatic';
			$formname = 'frm_myprofile';
			$cont_leftwidth 		= '43%';
			$cont_rightwidth 		= '57%';
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
								<td class="regiconent" valign="middle" width="43%" align="left">
								<?php echo stripslashes($custstat_caption_map_array[$row_custcompany['field_key']]); if($row_custcompany['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
								</td>
								<td width="57%" align="left" valign="middle" class="regi_txtfeild">
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
			</td> 
		</tr>
		<tr>
			<td colspan="2" class="regiheader">
			<?=$Captions_arr['CUST_REG']['CUSTOMER_PERSONAL_DETAILS_HEADER_EDIT']?>
			</td>
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
							<td width="43%" class="regiconent" valign="middle" align="left">
							<?php echo stripslashes($custstat_caption_map_array[$row_custstat['field_key']]); if($row_custstat['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
							</td>
							<td align="left" valign="middle" width="57%">
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
			$chkout_Req[]			= "'customer_email'";
			$chkout_Req_Desc[]		= "'".$Captions_arr['CUST_REG']['CUSTOMER_EMAIL']."'";
			
			
			$chkout_Email[]			= "'customer_email'";
			//section for custom registration form 
			$cur_pos = 'BottomInStatic';
			$formname = 'frm_myprofile';
			$cont_leftwidth 		= '43%';
			$cont_rightwidth 		= '57%';
			include 'show_dynamic_fields_myprofile.php';

			//section for custom registration form 
			$cur_pos = 'Bottom';
			$cont_leftwidth 		= '43%';
			$cont_rightwidth 		= '57%';
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
		/*
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
		<? */}?>
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
				<tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal_newsletter">
			<tr>
				<td align="left" valign="middle" width="3%">
				<input type="checkbox" name="customer_in_mailing_list" id="customer_in_mailing_list" value="1" onchange="maillinglist_onchange(this)" <?php echo ($row_customer['customer_in_mailing_list']==1)?'checked="checked"':'';?> />	
				</td>
				<td align="left" valign="middle" width="97%"><b>
				<?=$Captions_arr['CUST_REG']['CUSTOMER_RECEIVE_NEWSLETTER_HEADER']?></b>
				</td>
			</tr>
			<tr>
			<td colspan="2" align="left" valign="top">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" >
					
					
					<?php 
					$grp_cnt=0;
					while($customer_grp = $db->fetch_array($res_group)) {
					if($grp_cnt==0)
					{
					echo '<tr><td align="right" valign="middle" width="3%">&nbsp;</td>';
					}
					$grp_cnt++;
					?>
						<td   align="right" valign="middle" width="3%">
						
							
							<input type="checkbox" name="newsletergroup[]" id="newsletergroup[]" onchange="mailinglist_mainsel()" value="<?=$customer_grp['custgroup_id']?>" <? if(in_array($customer_grp['custgroup_id'],$arr_assigned)) echo "checked"; //($customer_grp['custgroup_id']==$customer_grp['selected_grp'])?"checked":""?> />						</td>
						
						<td  align="left" valign="middle" width="30%"><?=$customer_grp['custgroup_name']?></td>
						<?php 
							if(	$grp_cnt==3){
								echo "</tr>";
								$grp_cnt=0;
							}
						}
						if(	$grp_cnt<3){
								echo "</tr>";
							}?>
					</tr>
				</table>			</td>
		</tr>
			</table>
			</td>
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
					    <td  colspan="2"  align="right" >First Name<span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="fname" value="<?=$fname; ?>" maxlength="<?=$short?>"/></td>
				    </tr> 
					 <tr>
					    <td  colspan="2"  align="right" >Last Name<span class="redtext">*</span> </td>
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
					    <td  colspan="2"  align="right" >Email</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_email" <?php if($row_sql['through_email']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" <?php if($row_sql['through_email']==0){?> checked="checked" <?php }?> name="through_email"  value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >If Yes Enter Email Id</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="email_id" value="<?=$row_sql['email_id'] ?>" maxlength="<?=$medium?>"/></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >Telephone</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_phone" <?php if($row_sql['through_phone']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_phone"  <?php if($row_sql['through_phone']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >If Yes Enter Phone Number</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="phone_number" value="<?=$row_sql['phone_number'] ?>" maxlength="<?=$medium?>"/></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >Post</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_post" <?php if($row_sql['through_post']==1){?> checked="checked" <?php }?> value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_post" <?php if($row_sql['through_post']==0){?> checked="checked" <?php }?>  value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right"  valign="top">If Yes Enter Address here</td>
					    <td  align="center" valign="top">:</td>
					    <td  align="left" ><textarea  name="postal_address"  rows="5" cols="30" /><?=$row_sql['postal_address'] ?></textarea></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >Text</td>
					    <td  align="center" >:</td>
					    <td  align="left" >Yes&nbsp;<input type="radio" name="through_text" <?php if($row_sql['through_text']==1){?> checked="checked" <?php }?>  value="1">&nbsp;&nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="through_text" <?php if($row_sql['through_text']==0){?> checked="checked" <?php }?> value="0"></td>
				    </tr>
				     <tr>
					    <td  colspan="2"  align="right" >If Yes Enter Text number</td>
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
					    <td  colspan="3"  align="left" >I give my permission for Discount Mobility to send me the following.Please tick 'Yes' or 'No' for each option.</td>
					    
				    </tr>
				      <tr>
					    <td    align="right" width="50%" >Special seasonal offers/discount codes/new product Range</td>
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
If you decide to change your preferences, you can do so at any time. Please just call our customer services team on 01245 905144 during office hours Monday to Friday and let us know what you want us to do. For example, you can call us to update your contact details or you can opt out from communications altogether. Alternatively, you can let us know your wishes by email: <strong><a href="mailto:online.enquiries@discount-mobility.co.uk">online.enquiries@discount-mobility.co.uk</a>.</strong></p>
<p><strong>Terms and Privacy:</strong></br>
Discount Mobility will keep your contact details safe. We will not share them with any other person or organisation. We will only contact you by the channels you have chosen (phone, email etc.) and we will only contact you about the subjects you have chosen. You may unsubscribe at any time. For more details, please see our privacy policy and data protection to see how we use and protect your data. <br/>
For more details, please check our <a href="http://www.discount-mobility.co.uk/pg50545/gdpr.html" target="_blank"><strong>data protection</strong></a> to see how we use and protect your data. 
</p>
</td>
						</tr>
				   <tr>
				     <td align="center" valign="middle" class="shoppingcartcontent" colspan="4" ><input class="buttonred_cart" type="button" name="Submit" value="Save" onclick="javascript:add_newconsent(document.add_address)" /></td>
			        </tr>	
				</table>
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
			
