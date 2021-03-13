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
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<?php url_product($product_id,$product_name,-1)?>"><?=$product_name?></a>  >> <?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']?></div>
		
			<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="emailfriendtable">
			<tr>
          <td colspan="2" class="message_header"><?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']?></td>
        </tr>
			<?php if($alert){ 
			?>
			<tr>
				<td colspan="2" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['EMAIL_A_FRIEND'][$alert]){
						  		echo "Error !! ". $Captions_arr['EMAIL_A_FRIEND'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>
				</td>
			</tr>
		<?php } ?>
  <tr>
    <td colspan="2" class="emailfriendtextheader"><?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_HEADER_TEXT']?>
      </td>
    </tr>
	<?php 
	$pass_type = 'image_thumbpath';
	// Calling the function to get the image to be shown
	$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
	if($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_PRODUCT_TITLE']) { ?>
	<tr>
    <td colspan="2" class="emailfriendtextheader">
	<table width="100%" border="0" cellpadding="0" cellspacing="2" class="addproreivewtable">
        <tr>
          <td width="62%" align="left" valign="middle"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_PRODUCT_TITLE'])?>&nbsp;<span class="readreivewname"><?=$product_name?></span></td>
          <td width="38%" align="left" valign="middle"><a href="<?php url_product($product_id,$product_name,-1)?>" title="<?php echo stripslashes($product_name)?>"><? show_image(url_root_image($img_arr[0]['image_thumbpath'],1),$product_name,$product_name);?></a></td>
        </tr>
      </table>
	  </td></tr>
	  <?php } ?>
  <tr>
    <td colspan="2" class="emailfriendtextnormal"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_MESSAGE_TEXT'])?> </td>
    </tr>
  <tr>
    <td width="23%" class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_NAME'])?><br /></td>
    <td width="77%" align="left" valign="middle" class="emailfriendtext"><input name="friend_name" type="text" class="addreivewinput" id="friend_name" size="32" value="<?=$_REQUEST['friend_name']?>"
	/></td>
  </tr>
  <tr>
    <td class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_EMAIL'])?><span class="redtext">*</span> </td>
    <td align="left" valign="middle" class="emailfriendtext"><input name="friend_email" type="text" class="addreivewinput" id="friend_email" size="32" value="<?=$_REQUEST['friend_email']?>" /></td>
  </tr>
  <tr>
    <td class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_NAME'])?> </td>
    <td align="left" valign="middle" class="emailfriendtext"><label>
      <input name="customer_name" type="text" class="addreivewinput" id="customer_name" size="32" value="<?=$_REQUEST['customer_name']?>"/>
    </label></td>
  </tr>
  <tr>
    <td class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_EMAIL'])?> <span class="redtext">*</span></td>
    <td align="left" valign="middle" class="emailfriendtext"><input name="customer_email" type="text" class="addreivewinput" id="customer_email" size="32" value="<?=$_REQUEST['customer_email']?>" /></td>
  </tr>
  <tr>
    <td class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_MESSAGE'])?></td>
    <td align="left" valign="middle" class="emailfriendtext"><textarea name="customer_message" cols="32" rows="4" class="addreivewinput" id="customer_message"><?=$_REQUEST['customer_message']?></textarea></td>
  </tr>
  <? if($Settings_arr['imageverification_req']) {?>
  <tr>
    <td class="emailfriendtext">&nbsp;</td>
    <td align="left" valign="middle" class="emailfriendtext"><img src="<?php url_verification_image('includes/vimg.php?size=3&amp;pass_vname=emailafriend_Vimg')?>" border="0" alt="Image Verification"/></td>
  </tr>
  			

  <tr>
    <td class="emailfriendtext"><?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_VERIFICATION_CODE'])?></td>
    <td align="left" valign="middle" class="emailfriendtext">
      <?php 
				// showing the textbox to enter the image verification code
				$vImage->showCodBox(1,'emailafriend_Vimg','class="inputA"'); 
			?></td>
  </tr>
  <? }?>
  <tr>
    <td class="emailfriendtext"><input type="hidden" name="action_purpose" id="action_purpose" value="send_emailafriend"  /></td>
    <td align="left" valign="middle" class="emailfriendtext"><input name="emailafriend_Submit" type="submit" class="buttongray" id="emailafriend_Submit" value="<?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_SEND_BUTTON']?>" onclick="show_wait_button(this,'Please wait...')"/></td>
  </tr>
</table></form>
<script type="text/javascript">
/* Function to validate the product review */
function validate_emailafriend(frm)
{
	fieldRequired 		= Array('friend_email','customer_email');
	fieldDescription 	= Array("<?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_FRIEND_EMAIL'])?>","<?=stripslashes($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_CUSTOMER_EMAIL'])?>");
	fieldEmail 			= Array('friend_email','customer_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	<?php if($Settings_arr['imageverification_req']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.emailafriend_Vimg.value==''){
					alert('Enter-".$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_ALERT_IMAGE_VERIFICATION']."');
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
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
         <?php echo $mesgHeader;?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr><? 
	  if($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK']){?>
			 <tr>
          <td  colspan="2" align="left" valign="top"  class="addreivewconent" ><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="message_backlink"><?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK']?></a></td>
        </tr>
		<? }?>
        </table>
		<?php	
		}
	};	
?>