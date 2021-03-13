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
			
			
		?>
			<form method="post" action="" name'frm_callback' id="frm_callback" class="frm_cls" onsubmit="return validate_callback(this)">
		<div class="treemenu">
				<ul>
				  <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				  <li><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE'])?></li>
				</ul>
		   </div>
		   <?php
		  if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_HEADER_TEXT']){
		 ?>
		   <div class="inner_header"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_HEADER_TEXT'])?></div>
		<?php }?>
		<div class="inner_con_clr1" >
			<div class="inner_clr1_top"></div>
				<div class="inner_clr1_middle">	
					<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
						<?php if($alert){ 
						?>
							<tr>
							<td colspan="2" class="errormsg" align="center">
							<?php 
									  if($Captions_arr['REQUEST_A_CALLBACK'][$alert]){
											echo "Error !! ". stripslash_normal($Captions_arr['REQUEST_A_CALLBACK'][$alert]);
									  }else{
											echo  "Error !! ". $alert;
									  }
							?>	</td>
							</tr>
							<?php }
							if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT']){
							?>
							<tr>
							<td colspan="2" class="regiconentA"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT'])?> </td>
							</tr>
						<? }?>
					</table>
				</div>
			<div class="inner_clr1_bottom"></div>
		</div>		
		<div class="inner_con" >
			<div class="inner_top"></div>
				<div class="inner_middle">
					<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="regitable">  			
						<tr>
							<td width="38%" class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_FIRSTNAME'])?><span class="redtext">*</span></td>
							<td width="62%" align="left" valign="middle" class="regi_txtfeild"><input name="callback_fname" type="text" class="addreivewinput" id="callback_fname" size="39" value="<?=$_REQUEST['callback_fname']?>"
							/></td>
						</tr>
						<tr>
							<td width="38%" class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_LASTNAME'])?><br /></td>
							<td width="62%" align="left" valign="middle" class="regi_txtfeild"><input name="callback_lname" type="text" class="addreivewinput" id="callback_lname" size="39" value="<?=$_REQUEST['callback_lname']?>"
							/></td>
						</tr>
						<tr>
							<td class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_EMAIL'])?><span class="redtext">*</span> </td>
							<td align="left" valign="middle" class="regi_txtfeild"><input name="callback_email" type="text" class="addreivewinput" id="callback_email" size="39" value="<?=$_REQUEST['callback_email']?>" /></td>
						</tr>
						<tr>
							<td class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COUNTRY'])?> </td>
							<td align="left" valign="middle" class="regi_txtfeild"><label>
							<select name="callback_country" id="callback_country" class="addreivewinput">
							<?PHP 
							$sql = "SELECT country_id, country_name FROM general_settings_site_country WHERE sites_site_id='".$ecom_siteid."' AND country_hide = '1'";
							$res = $db->query($sql);
							while($row = $db->fetch_array($res)) {
							echo "<option value='".$row['country_id']."'>".$row['country_name']."</option>";
							}
							?>
							</select>
							<!--      <input name="callback_country" type="text" class="addreivewinput" id="callback_country" size="18" value="<?=$_REQUEST['callback_country']?>"/>
							-->    </label></td>
						</tr>
						<tr>
							<td class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_PHONE'])?> <span class="redtext">*</span></td>
							<td align="left" valign="middle" class="regi_txtfeild"><input name="callback_phone" type="text" class="addreivewinput" id="callback_phone" size="18" value="<?=$_REQUEST['callback_phone']?>" /></td>
						</tr>
						<tr>
							<td class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COMMENTS'])?></td>
							<td align="left" valign="middle" class="regi_txtfeild"><textarea name="callback_comments" cols="45" rows="5" class="addreivewinput" id="callback_comments"><?=$_REQUEST['callback_comments']?></textarea></td>
						</tr>
					</table>
				</div>
			<div class="inner_bottom"></div>
		</div>
			
  <? if($Settings_arr['imageverification_req_callback']) {?>
		<div class="inner_con" >
			<div class="inner_top"></div>
				<div class="inner_middle">
					<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							 <td class="regi_txtfeild">&nbsp;</td>
								<td align="left" valign="middle" class="regiconent"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=callback_Vimg&amp;bg=143 186 2')?>" border="0" alt="Image Verification"/></td>
								</tr>
								<tr>
								<td class="regiconent"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_VERIFICATION_CODE'])?><span class="redtext">*</span></td>
								<td align="left" valign="middle" class="regi_txtfeild">
								<?php 
								// showing the textbox to enter the image verification code
								$vImage->showCodBox(1,'callback_Vimg','class="img_input"'); 
								?></td>
						</tr>
					</table>
				</div>
			<div class="inner_bottom"></div>
		</div>
  <? }?>
		<div class="inner_con" >
			<div class="inner_top"></div>
				<div class="inner_middle">
					<table class="regifontnormal" width="100%" border="0" cellpadding="0" cellspacing="0">  
						<tr>
						<td class="regi_button"><input type="hidden" name="action_purpose" id="action_purpose" value="send_callback">      <input name="callback_Submit" type="submit" class="inner_btn_red" id="callback_Submit" value="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_SEND_BUTTON'])?>" /></td>
						</tr>
					</table>
				</div>
			<div class="inner_bottom"></div>
		</div>			</form>
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
		?>
		<div class="treemenu">
				<ul>
				  <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				  <li><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE'])?></li>
				</ul>
		   </div>
		   <div class="inner_header"> <?php echo $mesgHeader;?></div>
				<div class="inner_con_clr1" >
					<div class="inner_clr1_top"></div>
						<div class="inner_clr1_middle">
							<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regi_table">
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
				<div class="inner_clr1_bottom"></div>
			</div>
		<?php	
		}
	};	
?>