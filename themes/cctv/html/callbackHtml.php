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
		<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
			<ul>
			<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
			<li> <?=stripslashes($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE'])?> </li>
			</ul>
			</div>
		</div>
		<div class="tree_bottom"></div>
		</div>
		<div class="round_con">
		<div class="round_top"></div>
		<div class="round_middle">
			<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
			<?php if($alert){ 
			?>
			<tr>
			<td colspan="2" class="errormsg" align="center">
			<?php 
			if($Captions_arr['REQUEST_A_CALLBACK'][$alert]){
			echo "Error !! ". $Captions_arr['REQUEST_A_CALLBACK'][$alert];
			}else{
			echo  "Error !! ". $alert;
			}
			?>				</td>
			</tr>
			<?php }
			if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_HEADER_TEXT']){
			?>
			<tr>
			<td colspan="2" class="emailfriendtextheader"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_HEADER_TEXT']?>     </td>
			</tr>
			<? }
			if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT']){
			?>
			<tr>
			<td colspan="2" class="emailfriendtextnormal"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT']?> </td>
			</tr>
			<? }?>
			<tr>
			<td width="38%" class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_FIRSTNAME']?><span class="redtext">*</span></td>
			<td width="62%" align="left" valign="middle" class="emailfriendtext"><input name="callback_fname" type="text" class="addreivewinput" id="callback_fname" size="39" value="<?=$_REQUEST['callback_fname']?>"
			/></td>
			</tr>
			<tr>
			<td width="38%" class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_LASTNAME']?><br /></td>
			<td width="62%" align="left" valign="middle" class="emailfriendtext"><input name="callback_lname" type="text" class="addreivewinput" id="callback_lname" size="39" value="<?=$_REQUEST['callback_lname']?>"
			/></td>
			</tr>
			<tr>
			<td class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_EMAIL']?><span class="redtext">*</span> </td>
			<td align="left" valign="middle" class="emailfriendtext"><input name="callback_email" type="text" class="addreivewinput" id="callback_email" size="39" value="<?=$_REQUEST['callback_email']?>" /></td>
			</tr>
			<tr>
			<td class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COUNTRY']?> </td>
			<td align="left" valign="middle" class="emailfriendtext"><label>
			<select name="callback_country" id="callback_country" class="addreivewinput">
			<?PHP 
			$sql = "SELECT country_id, country_name FROM general_settings_site_country WHERE sites_site_id='".$ecom_siteid."' AND country_hide = '1'";
			$res = $db->query($sql);
			while($row = $db->fetch_array($res)) {
			echo "<option value='".$row['country_id']."'>".stripslashes($row['country_name'])."</option>";
			}
			?>
			</select>
			<!--      <input name="callback_country" type="text" class="addreivewinput" id="callback_country" size="18" value="<?=$_REQUEST['callback_country']?>"/>
			-->    </label></td>
			</tr>
			<tr>
			<td class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_PHONE']?> <span class="redtext">*</span></td>
			<td align="left" valign="middle" class="emailfriendtext"><input name="callback_phone" type="text" class="addreivewinput" id="callback_phone" size="18" value="<?=$_REQUEST['callback_phone']?>" /></td>
			</tr>
			<tr>
			<td class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COMMENTS']?></td>
			<td align="left" valign="middle" class="emailfriendtext"><textarea name="callback_comments" cols="45" rows="5" class="addreivewinput" id="callback_comments"><?=$_REQUEST['callback_comments']?></textarea></td>
			</tr>
			<? if($Settings_arr['imageverification_req_callback']) {?>
			<tr>
			<td class="emailfriendtext">&nbsp;</td>
			<td align="left" valign="middle" class="emailfriendtext"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=callback_Vimg')?>" border="0" alt="Image Verification"/></td>
			</tr>
			
			
			<tr>
			<td class="emailfriendtext"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_VERIFICATION_CODE']?><span class="redtext">*</span></td>
			<td align="left" valign="middle" class="emailfriendtext">
			<?php 
			// showing the textbox to enter the image verification code
			$vImage->showCodBox(1,'callback_Vimg','class="inputA"'); 
			?></td>
			</tr>
			<? }?>
			<tr>
			<td colspan="2" align="center" class="emailfriendtext">&nbsp;</td>
			</tr>
			<tr>
			<td colspan="2" align="center" class="emailfriendtext"><input type="hidden" name="action_purpose" id="action_purpose" value="send_callback">      <input name="callback_Submit" type="submit" class="buttongray" id="callback_Submit" value="<?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_SEND_BUTTON']?>" /></td>
			</tr>
			</table>
		</div>
		</div>
		<div class="round_bottom"></div>
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
					alert('Enter-".$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_ALERT_IMAGE_VERIFICATION']."');
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
		<div class="tree_con">
		<div class="tree_top"></div>
		<div class="tree_middle">
			<div class="pro_det_treemenu">
			<ul>
			<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> &gt;&gt; </li>
			<li> <?=stripslashes($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE'])?> </li>
			</ul>
			</div>
		</div>
		<div class="tree_bottom"></div>
		</div>
		<div class="round_con">
		<div class="round_top"></div>
		<div class="round_middle">
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="emailfriendtable">
			<tr>
				<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
			</tr><? 
			if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_BACK_TO_HOME']){?>
			<tr>
			<td  colspan="2" align="left" valign="top"  class="link" ><a href="<?php url_link('')?>" title="" class="link"><?=$Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_BACK_TO_HOME']?></a></td>
			</tr>
			<? }?>
			</table>
		</div>
		<div class="round_bottom"></div>
		</div>
		<?php	
		}
	};	
?>