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
		  
		
		$HTML_treemenu = ' <div class="breadcrumbs">
				<ol class="breadcrumb"><li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
				 <li>'.stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE']).'</li>
				</ol>
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
				<div class="inner-bg">
				<div class="container">
				<div class="container2">
				<div class="form-bottom">
										<input type="hidden" name="action_purpose" id="action_purpose" value="send_callback">

				<?php
				if($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT']){
				?>
				<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_MESSAGE_TEXT'])?> </span></div></div>
				<? }?>
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_fname"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_FIRSTNAME'])?><span class="redtext">*</span></label>

				<input name="callback_fname" class="form-control" id="callback_fname"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_FIRSTNAME'])?>" />
				</div>					
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_lname"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_LASTNAME'])?></label>

				<input name="callback_lname" class="form-control" id="callback_lname"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_LASTNAME'])?>" />
				</div>
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_email"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_EMAIL'])?><span class="redtext">*</span></label>

				<input name="callback_email" class="form-control" id="callback_email"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_EMAIL'])?>" />
				</div>  
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_country"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COUNTRY'])?><span class="redtext">*</span></label>
				<select name="callback_country" id="callback_country" class="form-control">
				<?PHP 
				$sql = "SELECT country_id, country_name FROM general_settings_site_country WHERE sites_site_id='".$ecom_siteid."' AND country_hide = '1'";
				$res = $db->query($sql);
				while($row = $db->fetch_array($res)) {
					$sel = '';
					if($row['country_id']==21225)
					{
						$sel = 'selected="selected"';
					}
				echo "<option value='".$row['country_id']."' $sel>".$row['country_name']."</option>";
				}
				?>
				</select>
				</div>  
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_phone"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_PHONE'])?><span class="redtext">*</span></label>

				<input name="callback_phone" class="form-control" id="callback_phone"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_PHONE'])?>" />
				</div>
				<div class="form-group">
				<label class="sr-onlyA" for="form-callback_comments"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COMMENTS'])?></label>

				<textarea name="callback_comments"  class="form-control"  id="callback_comments"><?=$_REQUEST['callback_comments']?></textarea>  </div>

				<input type="submit" id="callback_Submit" name="callback_Submit" class="btn-default topcart-bt" value="<?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_SEND_BUTTON'])?>"/>
				</div>
				</div>
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
	/*
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
	*/
	isValid = check_validate_newcommon(fieldRequired,fieldDescription,fieldEmail);	
    return isValid; 
}
</script>
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;

		$HTML_treemenu = ' <div class="breadcrumbs">
				<ol class="breadcrumb"><li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
				 <li>'.stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_TREEMENU_TITLE']).'</li>
				</ol>
			</div>';	
		echo $HTML_treemenu;
		?>
		<div class="container">
		<?php
		if($mesgHeader){
		?>
		<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=$mesgHeader?> </span></div></div>
		<? }?>
		   
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
		<?php	
		}
	};	
?>
