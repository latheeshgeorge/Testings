<?php
/*############################################################################
	# Script Name 	: registrationHtml.php
	# Description 	: Page which holds the display logic for adding a customer(customer registration)
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class newsletter_Html
	{
		// Defining function to show the site review
		function Show_newsletter()
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$pagetype,$alert;
		$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER'); 
		$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		 $HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'
		<div class="treemenu">
					<a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
					<span>&raquo;</span>'.stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE']).'
					</div>	
		
		';
		echo $HTML_treemenu;
		if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							$HTML_alert .=  $alert;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
		?>
  		<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
		<input type="hidden" name="action_purpose" value="insert_news" />
		<div class="reg_shlf_outr">
			   <div class="reg_shlf_inner">
				<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
						<?php
						if($Captions_arr['NEWS_LETTER']['NEWS_CUSTOMER_DESC']!='')
						{
						?>
						<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['NEWS_CUSTOMER_DESC'])?></span></div></div>
						<?php
						}
						?>	   
					<div class="reg_shlf_cont_div">
						<div class="reg_shlf_pdt_con">	
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reg_table">
								<tr>
								<td colspan="2" class="regiheader" align="left"><span class="reg_header"><span><?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSCUSTOMER_DETAILS_HEADER'])?></span></span></td>
								</tr>
							<?php
							if ($title)
							{
							?>		
								<tr>
								<td colspan="2" class="newsletterheader"><?php echo $title?></td>
								</tr>
							<?php
							}
							if($Settings_arr['newsletter_title_req']==1)
							{
							?>	
								<tr>
								<td class="regiconentA"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['TITLE'])?></td>
								<td align="left" valign="top" class="regi_txtfeildA">
								<select name="newsletter_title" class="regiinput" id="newsletter_title" >
								<option value="">Select</option>
								<option value="Mr.">Mr.</option>
								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Miss.">Miss.</option> 
								<option value="M/S.">M/S.</option>
								</select>
								</td>
								</tr>	
							<?php
							}
							if($Settings_arr['newsletter_name_req']==1)
							{
							?>
							<tr>
								<td class="regiconentA"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['NAME'])?></td>
								<td align="left" valign="top" class="regi_txtfeildA">
								<input name="newsletter_name" type="text" class="regiinput" id="newsletter_name" size="25" />				</td>
							</tr>	
							<?php
							}
							?>		 
							<tr>
								<td class="regiconentA" width="19%"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['EMAIL'])?></td>
								<td align="left" valign="top" class="regi_txtfeildA" width="81%">
								<input name="newsletter_email" type="text" class="regiinput" id="newsletter_email" size="25" />				</td>
							</tr>
							<?php
							if($Settings_arr['newsletter_phone_req']==1)
							{
							?>
							<tr>
								<td class="regiconentA"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['PHONE'])?></td>
								<td align="left" valign="top" class="regi_txtfeildA">
								<input name="newsletter_phone" type="text" class="regiinput" id="newsletter_phone" size="25" />				</td>
							</tr>
							<?php
							}
							if($Settings_arr['newsletter_group_req']==1)
							{
							// Check whether any customer groups exists
							$sql_groups = "SELECT custgroup_id,custgroup_name 
											FROM 
												customer_newsletter_group 
											WHERE 
												sites_site_id = $ecom_siteid AND custgroup_active='1'
											ORDER BY custgroup_name ";
							$ret_groups = $db->query($sql_groups);
							if ($db->num_rows($ret_groups))
							{			
							$cust_group_arr = array();
							while ($row_groups = $db->fetch_array($ret_groups))
							{
								$cst_id 					= $row_groups['custgroup_id'];
								$cust_group_arr[$cst_id]	= stripslash_normal($row_groups['custgroup_name']);
							}						
							?>
							<tr>
								<td valign="top" class="regiconentA"><?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['GROUP'])?></td>
								<td  align="left" valign="top" class="regi_txtfeildA">
								<?php
								if (count($cust_group_arr))
								{ 
								echo generateselectbox('newsletter_group[]',$cust_group_arr,0,'','',5,'',false,'sel_newsletter_group');
								}	
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
							<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
							 <table class="reg_shlf_inner_table" width="100%" border="0" cellpadding="2" cellspacing="0">
					<tbody>
					<?php
					if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
					 {
					?>
					  <tr>
					    <td align="left" width="20%" >&nbsp;</td>
					    <td  align="left" width="80%"> <img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=newsletter_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1')?>" border="0" alt="Image Verification" class="captcha"/> </td>
					    </tr>
					    
					     <tr>
					    <td align="left" width="20%" class="regiconentA" ><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_REGISTRATION_CODE'])?> </td>
					    <td>
							<?php 
							// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'newsletter_Vimg','class="img_input"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div></td>
					    </tr>
					    
					  
					  <tr>
					    <td align="left"></td>
					    <td colspan="2" align="left">  <div class="imgver_text"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_LONG_TEXT'])?></div></td>
					    </tr>					  
						<?php
						}
						?>
					  <tr>
						<td width="19%"><div class="cart_shop_cont" style="float:left;"><div>
							
						</div></div></td>
						<td colspan="2" align="left"><input name="newslettermiddle_Submit" type="submit" class="inner_btn_red" id="newslettermiddle_Submit" value="<?php echo stripslash_normal($Captions_arr['NEWS_LETTER']['SUBSCRIBE'])?>" onclick="show_wait_button(this,'Please wait...')"/></td>
						</tr>
						<tr>
						<td colspan="3" align="center"></td>
						</tr>
					</tbody>
				  </table>
				   </div>
            <div class="reg_shlf_inner_bottom"></div>
           </div>
            </div>
						
						
			</form>
		<?php	
		}
		function Display_Message($alert){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
	    $Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER'); 

$HTML_treemenu .=
		'<div class="treemenu">
					<a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
					<span>&raquo;</span>'.stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE']).'
					</div>
		
		';
		echo $HTML_treemenu;
		?>
			
			<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['NEWS_LETTER']['NEWSLETTER_TREEMENU_TITLE'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="reg_table">
			<tr>
			<td align="left" valign="middle" class="regicontentA"><?php echo $alert; ?></td>
			</tr>
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
?>
	
			
