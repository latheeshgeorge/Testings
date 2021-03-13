<?php
/*############################################################################
	# Script Name 	: emailafriendHtml.php
	# Description 	: Page which holds the display logic for emailafriend
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class emailafriend_Html
	{
		// Defining function to show the site review
		function Show_Emailafriend()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert;
		?>
			<form method="post" action="" name='frm_emailafriend' id="frm_emailafriend" class="frm_cls" onsubmit="return validate_emailafriend(this)">
		   <?php
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a> </li>
			 <li>'.stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']).'</li>
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
							if($Captions_arr['EMAIL_A_FRIEND'][$alert]){
									$HTML_alert .=  "Error !! ". stripslash_normal($Captions_arr['EMAIL_A_FRIEND'][$alert]);
							  }else{
									$HTML_alert .=   "Error !! ". stripslash_normal($alert);
							  }
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
		?>
		  <div class="reg_shlf_outr">
         
           
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_HEADER_TEXT'])?> '<?php echo stripslash_normal($product_name)?>'</span></div></div>

            <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con">	
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reg_table">
				
					<tr>
						<td colspan="2" class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_MESSAGE_TEXT'])?> </td>
					</tr>
					<tr>
						<td width="50%" class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_NAME'])?><br /></td>
						<td width="50%" align="left" valign="middle" class="regi_txtfeildA"><input name="friend_name" type="text" class="addreivewinput" id="friend_name" size="32" value="<?=$_REQUEST['friend_name']?>"/></td>
					</tr>
					<tr>
						<td class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_EMAIL'])?><span class="redtext">*</span> </td>
						<td align="left" valign="middle" class="regi_txtfeildA"><input name="friend_email" type="text" class="addreivewinput" id="friend_email" size="32" value="<?=$_REQUEST['friend_email']?>" /></td>
					</tr>
					<tr>
						<td class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_NAME'])?> </td>
						<td align="left" valign="middle" class="regi_txtfeildA">
					<input name="customer_name" type="text" class="addreivewinput" id="customer_name" size="32" value="<?=$_REQUEST['customer_name']?>"/>
					</td>
					</tr>
					<tr>
						<td class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_EMAIL'])?> <span class="redtext">*</span></td>
						<td align="left" valign="middle" class="regi_txtfeildA"><input name="customer_email" type="text" class="addreivewinput" id="customer_email" size="32" value="<?=$_REQUEST['customer_email']?>" /></td>
					</tr>
					<tr>
						<td class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_MESSAGE'])?></td>
						<td align="left" valign="middle" class="regi_txtfeildA"><textarea name="customer_message" cols="32" rows="4" class="addreivewinput" id="customer_message"><?=$_REQUEST['customer_message']?></textarea></td>
					</tr>
			<? if($Settings_arr['imageverification_req']) {?>
			
								<tr class="regitable">
									<td class="regiconentA">&nbsp;</td>
									<td align="left" valign="middle" class="regi_txtfeildA"><img src="<?php url_verification_image('includes/vimg.php?size=3&amp;pass_vname=emailafriend_Vimg&amp;bg=143 186 2')?>" border="0" alt="Image Verification"/></td>
								</tr>
								<tr>
									<td class="regiconentA"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_VERIFICATION_CODE'])?></td>
									<td align="left" valign="middle" class="regi_txtfeildA">
									<?php 
									// showing the textbox to enter the image verification code
									$vImage->showCodBox(1,'emailafriend_Vimg','class="img_input"'); 
									?></td>
							
			<? }?>
			<tr>
				<td class="regi_button"  align="right"><input type="hidden" name="action_purpose" id="action_purpose" value="send_emailafriend"  />
				</td>
			</tr>
			</table>
			<div style="margin-right:45%">
				<div class="cart_shop_cont"><div>
								<input name="emailafriend_Submit" type="submit" class="inner_btn_red" id="emailafriend_Submit" value="<?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_SEND_BUTTON'])?>" onclick="show_wait_button(this,'Please wait...')"/>
				</div></div>
			</div>
			</div>
		</div>
		 </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>
</form>
<script type="text/javascript">
/* Function to validate the product review */
function validate_emailafriend(frm)
{
	fieldRequired 		= Array('friend_email','customer_email');
	fieldDescription 	= Array('<?=stripslash_javascript($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_FRIEND_EMAIL'])?>','<?=stripslash_javascript($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_CUSTOMER_EMAIL'])?>');
	fieldEmail 			= Array('friend_email','customer_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	<?php if($Settings_arr['imageverification_req']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.emailafriend_Vimg.value==''){
					alert('Enter-".stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_IMAGE_VERIFICATION'])."');
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
		echo $product_id;
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
		echo '<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a> </li>
			 <li>'.stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		?>
			<div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?php echo $mesgHeader;?></span></div></div>
            <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con">	
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="reg_table">
			<tr>
			<td align="center" valign="middle" class="regicontentA"><?php echo $Message; ?></td>
			</tr><? 
			if($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK']){?>
			 <tr>
			<td  colspan="2" align="left" valign="top"  class="regicontentA" ><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="message_backlink"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK'])?></a></td>
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