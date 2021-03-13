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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert,$site_key;
		
		$HTML_treemenu = '<div class="breadcrumbs">
				<div class="container-fluid">
        <div class="container-tree">
          <ul><li class="home"><a  href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> &#8594; </li>
<li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a> </li>
				 <li>'.stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']).'</li>				 </ul>
        </div>
      
    </div>
  </div>';
			echo $HTML_treemenu;
		?>
			
			<div class="container-fluid">
			<form method="post" action="" name='frm_emailafriend' id="frm_emailafriend" class="frm_cls" onsubmit="return validate_emailafriend(this)">
		   <?php
		   $HTML_img = $HTML_alert = $HTML_treemenu='';
		
			
		
		
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
		    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

		  <div class="inner-bg">
				<div class="container-fluid">
				<div class="container2">
				<div class="form-bottom">

				<?php
				if($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_HEADER_TEXT']){
				?>
				<div style="float:left;display:block;font-weight:bold;width:100%;text-align:left;"><span><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_HEADER_TEXT'])?> </span></div>
				<? }?>
				<div class="form-group">
				<label class="sr-onlyA" for="form-friend_name"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_NAME'])?></label>

				<input name="friend_name" class="form-control" id="friend_name"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_NAME'])?>" />
				</div>									
				<div class="form-group">
				<label class="sr-onlyA" for="form-friend_email"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_EMAIL'])?><span class="redtext"></span></label>

				<input name="friend_email" class="form-control" id="friend_email"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_FRIEND_EMAIL'])?>" />
				</div>				
				
				<div class="form-group">
				<label class="sr-onlyA" for="form-customer_name"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_NAME'])?></label>

				<input name="customer_name" class="form-control" id="customer_name"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_NAME'])?>" />
				</div> 
				
				
				
				<div class="form-group">
				<label class="sr-onlyA" for="form-customer_email"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_EMAIL'])?><span class="redtext">*</span></label>

				<input name="customer_email" class="form-control" id="customer_email"  type="text"  placeholder="<?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_CUSTOMER_EMAIL'])?>" />
				</div> 
				 
				  
				
				<div class="form-group">
				<label class="sr-onlyA" for="form-customer_message"><?=stripslash_normal($Captions_arr['REQUEST_A_CALLBACK']['CALLBACK_REQUEST_COMMENTS'])?></label>

				<textarea name="customer_message"  class="form-control"  id="customer_message"><?=$_REQUEST['customer_message']?></textarea>  </div>
                       <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>

                 <input type="hidden" name="action_purpose" id="action_purpose" value="send_emailafriend"  />
				<input type="submit" id="emailafriend_Submit" name="emailafriend_Submit" class="buttoninput submitEnquiry btn btn-add-to-cart btn-lg sharp" value="SEND"/>
				</div>
				<div style="float:left;display:block;height:10px;width:100%;">&nbsp;</div>
				</div>
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
	/*
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
	*/
	isValid = check_validate_newcommon(fieldRequired,fieldDescription,fieldEmail);	
    return isValid; 
}
</script>
</div>
		<?php	
		}
		function Display_Message($mesgHeader,$Message){
		echo $product_id;
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
			
		
		$HTML_treemenu = '<div class="breadcrumbs">
    <div class="'.CONTAINER_CLASS.'">
      <div class="row">
        <div class="col-xs-12">
          <ul><li class="home"><a  href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a><span>&#8594; </span></li>
				 <li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a> </li>
				 <li>'.stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_TREEMENU_TITLE']).'</li>
				 </ul>
        </div>
      </div>
    </div>
  </div>';		
		echo $HTML_treemenu;	
			?>

			   
		<div class="container-fluid">


		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="reg_table">
		<tr>
		<td align="center" valign="middle" class="regicontentA"><div class="alert-success callback-success"><?php echo $Message; ?></div></td>
		</tr><? /*
		if($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK']){?>
		<tr>
		<td  colspan="2" align="left" valign="top"  class="regicontentA" ><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="message_backlink"><?=stripslash_normal($Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_BACK_TO_DETAILS_LINK'])?></a></td>
		</tr>
		<?
		
		 }*/?>
		</table>
		</div>
		
		<?php	
		}
	};	
?>
