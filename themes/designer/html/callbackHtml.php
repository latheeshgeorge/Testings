<?php
/*############################################################################
	# Script Name 	: callbackHtml.php
	# Description 	: Page which holds the display logic for call back
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class callback_Html
	{
		// Defining function to show the Call Back
		function Show_Callback()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
			$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		   $HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
			if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							  if($Captions_arr['REQUEST_A_CALLBACK'][$alert]){
											$HTML_alert .= "Error !! ". stripslash_normal($Captions_arr['REQUEST_A_CALLBACK'][$alert]);
									  }else{
											$HTML_alert .=  "Error !! ". $alert;
									  }
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
		?>	
			<form method="post" action="" name'frm_callback' id="frm_callback" class="frm_cls" onsubmit="return validate_callback(this)">
				<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
							<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="reg_table">
								<tr>
									<td width="38%" class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_FIRSTNAME'])?><span class="redtext">*</span></td>
									<td width="62%" align="left" valign="middle" class="regi_txtfeildA"><input name="callback_fname" type="text" class="addreivewinput" id="callback_fname" size="39" value="<?=$_REQUEST['callback_fname']?>"
									/></td>
								</tr>
								<tr>
									<td width="38%" class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_LASTNAME'])?><br /></td>
									<td width="62%" align="left" valign="middle" class="regi_txtfeildA"><input name="callback_lname" type="text" class="addreivewinput" id="callback_lname" size="39" value="<?=$_REQUEST['callback_lname']?>"
									/></td>
								</tr>
								<tr>
									<td class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_EMAIL'])?><span class="redtext">*</span> </td>
									<td align="left" valign="middle" class="regi_txtfeildA"><input name="callback_email" type="text" class="addreivewinput" id="callback_email" size="39" value="<?=$_REQUEST['callback_email']?>" /></td>
								</tr>
								<tr>
									<td class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COUNTRY'])?> </td>
									<td align="left" valign="middle" class="regi_txtfeildA"><label>
									<select name="callback_country" id="callback_country" class="addreivewinput">
									<?PHP 
									$sql = "SELECT country_id, country_name FROM general_settings_site_country WHERE sites_site_id='".$ecom_siteid."' AND country_hide = '1'";
									$res = $db->query($sql);
									while($row = $db->fetch_array($res)) {
									if($row['country_id']==$Settings_arr['default_country_id'])
										$checked = 'selected="selected"';
										else
										$checked = '';
									echo "<option value='".$row['country_id']."'".$checked.">".$row['country_name']."</option>";
									}
									?>
									</select>
									<!--      <input name="callback_country" type="text" class="addreivewinput" id="callback_country" size="18" value="<?=$_REQUEST['callback_country']?>"/>
									-->    </label></td>
								</tr>
								<tr>
									<td class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_PHONE'])?> <span class="redtext">*</span></td>
									<td align="left" valign="middle" class="regi_txtfeildA"><input name="callback_phone" type="text" class="addreivewinput" id="callback_phone" size="18" value="<?=$_REQUEST['callback_phone']?>" /></td>
								</tr>
								<tr>
									<td class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COMMENTS'])?></td>
									<td align="left" valign="middle" class="regi_txtfeildA"><textarea name="callback_comments" cols="45" rows="5" class="regiinput" id="callback_comments"><?=$_REQUEST['callback_comments']?></textarea></td>
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
	   	
				   <table class="reg_shlf_inner_table" width="100%" border="0" cellpadding="2" cellspacing="0">
					<tbody>
					<? if($Settings_arr['imageverification_req_callback']) {?>
					  <tr class="regitable">
					  <td colspan="2" align="left">					  </td>
					  </tr><tr>
					  <td colspan="3" align="left">					</td>
					  </tr>
					  <tr>
					    <td colspan="3" align="left"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_VERIFICATION_CODE'])?> </td>
					    </tr>
					  <tr>
					    <td align="left"><img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=callback_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1')?>" border="0" alt="Image Verification" class="captcha"/></td>
					    <td colspan="2" align="left">  <div class="imgver_text"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_LONG_TEXT'])?></div></td>
					    </tr>
					  <tr>
					    <td colspan="3" align="left"><?php 
							// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'callback_Vimg','class="img_input"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div></td>
					    </tr>
						<? }?>
						 <tr>
						<td width="19%"><div class="cart_shop_cont" style="float:left;"><div>
						<input type="hidden" name="action_purpose" id="action_purpose" value="send_callback">
						<input name="callback_Submit" type="submit" class="inner_btn_red" id="callback_Submit" value="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_SEND_BUTTON'])?>"  />
						</div></div></td>
						<td colspan="2" align="left">&nbsp;</td>
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
<script language="javascript">
/* Function to validate the product review */
function validate_callback(frm)
{
	fieldRequired 		= Array('callback_fname','callback_email','callback_phone');
	fieldDescription 	= Array('<?=add_slash($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_ALERT_FIRSTNAME'])?>','<?=add_slash($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_ALERT_EMAIL'])?>','<?=add_slash($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_ALERT_PHONE'])?>');
	fieldEmail 			= Array('callback_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	fieldSpecChars 		= Array('callback_fname','callback_lname','callback_phone');
	fieldCharDesc       = Array('First Name','Last Name','Phone');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
	<?php if($Settings_arr['imageverification_req']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.callback_Vimg.value==''){
					alert('Enter-".stripslash_javascript($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_ALERT_IMAGE_VERIFICATION'])."');
					return false;
				}else{
					return true;
				}";
			}?>
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
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
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
								<td align="left" valign="middle" class="regicontentA"><?php echo $Message; ?></td>
								</tr><? 
								if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_BACK_TO_HOME']){?>
								<tr>
								<td  colspan="2" align="left" valign="top"  class="regicontentA" ><a href="<?php url_link('')?>" title="" class="message_backlink"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_BACK_TO_HOME'])?></a></td>
								</tr>
								<? }?>
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