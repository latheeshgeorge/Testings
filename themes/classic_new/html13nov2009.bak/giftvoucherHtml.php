<?php
/*############################################################################
	# Script Name 	: giftvoucherHtml.php
	# Description 		: Page which holds the display logic for Buy giftvoucher
	# Coded by 		: ANU
	# Created on		: 17-Apr-2008
	# Modified by		: Sny
	# Modified On		: 08-Dec-2008
	##########################################################################*/
	class giftvoucher_Html
	{
		// Defining function to show the Buy gift voucher
		function Buy_Giftvoucher($alert='')
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$sitesel_curr,$default_Currency_arr,$ecom_testing,$ecom_themename,$components,
					$ecom_common_settings,$protectedUrl,$vImage;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$Saved_vouchervalues = get_VoucherBuyValues();
			$cust_id				= get_session_var("ecom_login_customer"); // Get the customer id from session
			$curr_sign = get_selected_currency_symbol();
			if($_REQUEST['rt']==1) // This is to handle the case of returning to this page by clicking the back button in browser
				$alert = 'GIFT_VOUCHER_ERROR_OCCURED';			
			elseif($_REQUEST['rt']==2) // case of image verification failed
				$alert = 'GIFT_VOUCHER_IMAGE_VERIFICATION_FAILED';
				
			if($protectedUrl)
				$http = url_protected('index.php?req=voucher&action_purpose=buy',1);
			else 	
				$http = url_link('buy_voucher.html',1);		
			
			$cc_exists 		= 0;
			$cc_seq_req 	= check_Paymethod_SSL_Req_Status('gift');
			$sql_paymethods = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
												  a.paymethod_takecarddetails,a.payment_minvalue,a.paymethod_secured_req,a.paymethod_ssl_imagelink,
												  b.payment_method_sites_caption 
											FROM 
												payment_methods a,
												payment_methods_forsites b 
											WHERE 
												b.sites_site_id = $ecom_siteid 
												AND paymethod_showinvoucher =1 
												AND b.payment_method_sites_active = 1 
												AND a.paymethod_id=b.payment_methods_paymethod_id";
			$ret_paymethods = $db->query($sql_paymethods);
			$totpaycnt		= $totpaymethodcnt = $db->num_rows($ret_paymethods);
			if ($totpaycnt==0)
			{
				$paytype_moreadd_condition = " AND a.paytype_code <> 'credit_card'";
			}
			else
				$paytype_moreadd_condition = '';	
				
			
			// Running the qry to pick the payment types to be displayed
				if($cust_id) // case if customer is logged in
				{
					// Check whether pay_on_account is active for current customer
					$sql_custcheck = "SELECT customer_payonaccount_status ,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit 
													FROM 
														customers 
													WHERE 
														customer_id = $cust_id 
														AND sites_site_id = $ecom_siteid 
														AND customer_payonaccount_status ='ACTIVE' 
													LIMIT 
														1";
					$ret_custcheck = $db->query($sql_custcheck);
					if ($db->num_rows($ret_custcheck)) // case if payon account is active for current customer
					{
						$row_custcheck 						= $db->fetch_array($ret_custcheck);
						$payonaccount_maxlimit 			= $row_custcheck['customer_payonaccount_maxlimit'];
						$payonaccount_usedmaxlimit 	= $row_custcheck['customer_payonaccount_usedlimit'];
						$payonaccount_remlimit			= $payonaccount_maxlimit - $payonaccount_usedmaxlimit;
						
					}
					else
					{
						$paytype_add_cond				= " AND a.paytype_code <> 'pay_on_account' ";
						$payonaccount_remlimit = 0;
					}	
					$sql_paytypes 	= "SELECT a.paytype_code,b.paytype_forsites_id,a.paytype_id,a.paytype_name,b.images_image_id,
										b.paytype_caption  
										FROM 
											payment_types a, payment_types_forsites b 
										WHERE 
											b.sites_site_id = $ecom_siteid 
											AND paytype_forsites_active=1 
											AND paytype_forsites_userdisabled=0 
											AND a.paytype_id=b.paytype_id 
											AND a.paytype_showinvoucher=1 
											$paytype_add_cond 
											$paytype_moreadd_condition 
										ORDER BY 
											a.paytype_order";
				}
				else // case if customer in not logged in. So show only those payment types whose value for paytype_logintouse is set to 0
				{
					$sql_paytypes 	= "SELECT a.paytype_code,b.paytype_forsites_id,a.paytype_id,a.paytype_name,b.images_image_id,
											b.paytype_caption   
										FROM 
											payment_types a, payment_types_forsites b 
										WHERE 
											b.sites_site_id = $ecom_siteid 
											AND paytype_forsites_active=1 
											AND paytype_forsites_userdisabled=0 
											AND a.paytype_id=b.paytype_id 
											AND a.paytype_showinvoucher=1  
											$paytype_moreadd_condition
											AND paytype_logintouse = 0 
										ORDER BY 
											a.paytype_order";
				}	
				$ret_paytypes = $db->query($sql_paytypes);
				$paytypes_cnt = $db->num_rows($ret_paytypes);
				
				if($paytypes_cnt==1 && $totpaymethodcnt>=1)
					$card_req = 1;
				else
					$card_req = '';		
	?>
			<form method="post" action="<?php echo $http?>" name='frm_buygiftvoucher' id="frm_buygiftvoucher" class="frm_cls">
			<input type="hidden" name="paymentmethod_req" id="paymentmethod_req" value="<?php echo $card_req?>" />
			<input type="hidden" name="save_voucherdetails" id="save_voucherdetails" value="" />
			<input type="hidden" name="voucher_unique_key" id="voucher_unique_key" value="<?php echo uniqid('')?>" />
			<input type="hidden" name="voucher_type" id="voucher_type" value="val" /> <?php /* only value type voucher can be added from client side */?>
			<input type="hidden" name="nrm" id="nrm" value="1" />
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_TREEMENU_TITLE']?></div>
		<div class="gift_mid_con">
        <div class="gift_mid_top"></div>
        <div class="gift_mid_middle" >
     	<div class="gift_mid_des">
		<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="gift_mid_table">
		<tr>
			<td width="100%" class="gift_buy_header"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_TREEMENU_TITLE']?></td>
		</tr>
		<?php 
		if($alert)
		{ 
		?>
		<tr>
		<td class="errormsg" align="center">
		<?php 
		if($Captions_arr['GIFT_VOUCHER'][$alert])
		{
			echo $Captions_arr['GIFT_VOUCHER'][$alert];
		}
		else
		{
		echo  $alert;
		}
		?>
		</td>
		</tr>
		<?php 
		}
		if(trim($Settings_arr['voucher_buy_text'])!='')
		{
		?>
		<tr>
			<td class="gift_mid_table_td">
			<div class="div_gift_cnt">
			<?=stripslashes($Settings_arr['voucher_buy_text'])?>
			</div>
			</td>
		</tr>
		<? 
		}
		if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_HEADER_TEXT'])
		{
		?>
			<tr>
				<td class="gift-hed-bg_td"><div class="gift-hed-bg"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_HEADER_TEXT']?></div></td>
			</tr>
		<? 
		}
		?>
		<tr id="emailvoucher_div">
		<td class="gift-send-bg_td">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr class="gift_mid_table">
			<td align="left" valign="top" class="gift-send-inner_hed" id="vouch_caption_td"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VALUE_LABEL']?>&nbsp;<span class="redtext">*</span></td>
			<td class="gift-send-inner_hed" valign="top" align="left">&nbsp;<?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_NOOFDAYS_LABEL']?>&nbsp;<span class="redtext">*</span></td>
		</tr>
		<tr class="gift_mid_table">
			<td align="left" valign="top" class="gift-send-inner_cnt"><?php echo $curr_sign?>&nbsp;<input name="voucher_value" type="text" class="gift_sendinput" id="voucher_value" size="18" value="<?=$Saved_vouchervalues['voucher_value']?>"/>
		<br />
		<span class="gift-red_small_txt"> Please enter the value of voucher you wish to buy.</span> </td>
		<td class="gift-send-inner_cnt" valign="top" align="left"><input name="voucher_noofdaysactive" type="text" class="gift_sendinput" id="voucher_noofdaysactive" size="3" value="<?php echo $Saved_vouchervalues['voucher_activedays']?>"/>
		<br />
		<span class="gift-red_small_txt"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_NOOFDAYS_STARTDATE_NOTE']?></span> </td>
		</tr>
		<?php
		if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_MESSAGE_TEXT'])
		{
		?>
		<tr>
		<td colspan="2" class="gift-send-inner_hed"><strong><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_MESSAGE_TEXT']?></strong> </td>
		</tr>
		<?php
		}
		?>
		<tr>
		<td align="left" valign="top" class="gift-send-inner_hed"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_TO_NAME_LABEL']?>&nbsp;<span class="redtext">*</span></td>
		<td class="gift-send-inner_hed" valign="top" width="60%" align="left">Email id&nbsp;<span class="redtext">*</span></td>
		</tr>
		<tr>
		<td align="left" valign="top" class="gift-send-inner_hed">
		<input name="email_to" type="text" class="gift_sendinput" id="email_to" size="18" value="<?php echo $Saved_vouchervalues['voucher_toname']?>"/>
		</td>
		<td class="gift-send-inner_hed" valign="top" width="60%" align="left"><input name="email_id" type="text" class="gift_sendinput" id="email_id" size="18" value="<?php echo $Saved_vouchervalues['voucher_toemail']?>"/></td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="top" class="gift-send-inner_hed"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_TO_MESSAGE_LABEL']?>&nbsp;<span class="redtext">*</span></td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="top" class="gift-send-inner_cnt"><textarea name="email_message" type="text" class="gift_sendinput" id="email_message" rows="5" cols="60"><?php echo $Saved_vouchervalues['voucher_tomessage']?></textarea></td>
		</tr>
		</table>
		</td>
		</tr>
		
		<?
		
		// Initializing the array to hold the values to be used in the javascript validation of the static and dynamic fields
		$chkout_Req					= $chkout_Req_Desc = $chkout_Email = $chkout_Confirm = $chkout_Confirmdesc = array();
		$chkout_Numeric 			= $chkout_multi = $chkout_multi_msg	= array();
		$chkout_Req					= array("'voucher_value'","'voucher_noofdaysactive'","'email_to'","'email_id'","'email_message'");
		$chkout_Req_Desc			= array("'Voucher Value'","'Number of Active Days'","'Name'","'Email id'","'Message'") ; 
		$chkout_Email     			= array("'email_id'");
		$chkout_Numeric  			= array("'voucher_value'","'voucher_noofdaysactive'");
		if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_BUYERS_MESSAGE_TEXT'])
		{
		?>
		 	<tr>
				<td class="gift-hed-bg_td"><div class="gift-hed-bg"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_BUYERS_MESSAGE_TEXT']?></div></td>
			</tr>
		<? 
		}
		?>
		<tr>
	      <td class="gift-send-bg_td">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<?php
		// Get the list of delivery address static fields to be shown in the checkout out page in required order
		$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
		FROM 
		general_settings_site_checkoutfields 
		WHERE 
		sites_site_id = $ecom_siteid 
		AND field_hidden=0 
		AND field_type='VOUCHER' 
		ORDER BY 
		field_order";
		$cartData = array();
		$ret_checkout = $db->query($sql_checkout);
		if($db->num_rows($ret_checkout))
		{	
		while($row_checkout = $db->fetch_array($ret_checkout))
		{			
		// Section to handle the case of required fields
		if($row_checkout['field_req']==1)
		{
		if($row_checkout['field_key']=='checkout_voucheremail')
		{
		$chkout_Email[] = "'".$row_checkout['field_key']."'";
		
		}
			$chkout_Req[]				= "'".$row_checkout['field_key']."'";
			$chkout_Req_Desc[]			= "'".$row_checkout['field_error_msg']."'"; 		
		}
		
		?>
		<tr class="gift_mid_table">
		<td align="left" class="gift-send-inner_cnt" valign="top">
		<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
		</td>
		<td align="left" class="gift_mid_table_td">
		<?php
		$pass_class_arr['txtbox_cls'] 		= 'gift_sendinput'; 
		$pass_class_arr['txtarea_cls'] 		= 'gift_sendinput'; 
		echo get_Field($row_checkout['field_key'],$Saved_vouchervalues,$cartData,'',$pass_class_arr);
		?>
		</td>
		</tr>
		<?php
		}
		}
		?>
		<? if($Settings_arr['imageverification_req_voucher']) {?>
		<tr class="gift_mid_table">
		<td class="gift-send-inner_cnt" width="40%">&nbsp;</td>
		<td align="left" valign="middle" width="60%" class="gift_mid_table_td"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=buygiftvoucher_Vimg')?>" border="0" alt="Image Verification"/></td>
		</tr>
		
		
		<tr class="gift_mid_table">
		<td class="gift-send-inner_cnt" align="left"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VERIFICATION_CODE']?>&nbsp;<span class="redtext">*</span></td>
		<td align="left" valign="middle" class="gift_mid_table_td">
		<?php 
		// showing the textbox to enter the image verification code
		$vImage->showCodBox(1,'buygiftvoucher_Vimg','class="inputA_imgver"'); 
		?>
		</td>
		</tr>
		<? }?>
		</table>
		</td>
    	</tr>
		<tr>
		<td colspan="2">
		<?php
		if ($db->num_rows($ret_paytypes))
		{
		$paytype_cnts = $db->num_rows($ret_paytypes);
		if($db->num_rows($ret_paytypes)==1)// Check whether there are more than 1 payment type. If no then dont show the payment option to user, just use hidden field
		{
		$row_paytypes = $db->fetch_array($ret_paytypes);
		if($row_paytypes['paytype_code']=='credit_card')
		{
		if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
		$cc_exists = true;
		}	
		$single_curtype = $row_paytypes['paytype_code'];
		?>
		<input type="hidden" name="voucher_paytype" id="voucher_paytype" value="<?php echo $row_paytypes['paytype_id']?>" />
		<?php
		}
		else
		{
		$pay_maxcnt = 2;
		$pay_cnt	= 0;
		?>
		 <tr>
	  	<td class="gift_mid_table_td">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td colspan="<?php echo $pay_maxcnt?>" class="gift-hed-bg_td"><div class="gift-hed-bg"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SEL_PAYTYPE']?></div></td>
		</tr>
		<tr class="gift_mid_table">
		<?php
		echo '<script type="text/javascript">
		paytype_arr  = new Array();		
		</script>';
		
		while ($row_paytypes = $db->fetch_array($ret_paytypes))
		{
		if($row_paytypes['paytype_code']=='credit_card')
		{
		if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
		$cc_exists = true;
		if (($protectedUrl==true and $cc_seq_req==false) or ($protectedUrl==false and $cc_seq_req==true))
		{
		$paytype_onclick = "handle_form_submit(document.frm_buygiftvoucher,'','')";	
		}
		else
		$paytype_onclick = 'handle_paytypeselect(this)';
		}	
		else // if pay type is not credit card.
		{
		if ($protectedUrl==true)
		{
		$paytype_onclick = "handle_form_submit(document.frm_buygiftvoucher,'','')";	
		}
		else
		$paytype_onclick = 'handle_paytypeselect(this)';
		}
		if($row_paytypes['paytype_code']=='pay_on_account')
		{
		$add_text = ' (Credit Available '. print_price($payonaccount_remlimit).')';
		}	
		else
		$add_text = '';
		echo '<script type="text/javascript">';
		echo "paytype_arr[".$row_paytypes['paytype_id']."] = '".$row_paytypes['paytype_code']."';";
		echo '</script>';
		?>
		<td width="50%" align="left" class="gift-send-inner_cnt">
		<?php
		// image to shown for payment types
		$pass_type = 'image_iconpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('paytype',$row_paytypes['paytype_forsites_id'],$pass_type,0,0,1);
		if(count($img_arr))
		{
		show_image(url_root_image($img_arr[0][$pass_type],1),$row_paytypes['paytype_caption'],$row_paytypes['paytype_caption']);
		}
		else
		{
		?>
		<img src="<?php url_site_image('cash.gif')?>" alt="Payment Type"/>
		<?php	
		}	
		?>
		
		<input class="shoppingcart_radio" type="radio" name="voucher_paytype" id="voucher_paytype" value="<?php echo $row_paytypes['paytype_id']?>" onclick="<?php echo $paytype_onclick?>" <?php echo ($_REQUEST['voucher_paytype']==$row_paytypes['paytype_id'])?'checked="checked"':''?> /><?php echo stripslashes($row_paytypes['paytype_caption']).$add_text?>
		</td>
		<?php
		$pay_cnt++;
		if ($pay_cnt>=$pay_maxcnt)
		{
		echo "</tr><tr class='gift_mid_table'>";
		$pay_cnt = 0;
		}
		}
		if ($pay_cnt<$pay_maxcnt)
		{
		echo "<td colspan=".($pay_maxcnt-$pay_cnt).">&nbsp;</td>";
		}
		?>	
		</tr>
		</table>
		<?php
		
		}
		}
		?>
		</td>
		</tr>
		<?php 
		$self_disp = 'none';
		if($_REQUEST['voucher_paytype'])
		{
		// get the paytype code for current paytype
		$sql_pcode = "SELECT paytype_code 
		FROM 
		payment_types 
		WHERE 
		paytype_id = ".$_REQUEST['voucher_paytype']." 
		LIMIT 
		1";
		$ret_pcode = $db->query($sql_pcode);
		if ($db->num_rows($ret_pcode))
		{
		$row_pcode 	= $db->fetch_array($ret_pcode);
		$sel_ptype 	= $row_pcode['paytype_code'];
		}
		}
		
		if($sel_ptype=='credit_card' or $single_curtype=='credit_card')
		$paymethoddisp_none = '';
		else
		$paymethoddisp_none = 'none';
		if($sel_ptype=='cheque')
		$chequedisp_none = '';
		else
		$chequedisp_none = 'none';	
		if ($db->num_rows($ret_paymethods))
		{
		if ($db->num_rows($ret_paymethods)==1)
		{
		$row_paymethods = $db->fetch_array($ret_paymethods);
		if ($row_paymethods['paymethod_key']=='SELF' or $row_paymethods['paymethod_key']=='PROTX')
		{
		if($paytypes_cnt==1 or $sel_ptype =='credit_card')
		$self_disp = '';
		}	
		?>
		<input type="hidden" name="voucher_paymethod" id="voucher_paymethod" value="<?php echo $row_paymethods['paymethod_key'].'_'.$row_paymethods['paymethod_id'];//echo $row_paymethods['paymethod_id']?>" />
		<?php
		}
		else
		{
		?>
		<tr id="voucher_paymethod_tr" style="display:<?php echo $paymethoddisp_none?>">
		<td colspan="2" align="left" valign="middle" class="gift_mid_table_td">
		<div class="shoppayment_type_div">
		<?php
		$pay_maxcnt 	= 2;
		$pay_cnt		= 0;
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td colspan="<?php echo $pay_maxcnt?>" class="gift-hed-bg_td"><div class="gift-hed-bg"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SEL_PAYGATEWAY']?></div></td>
		</tr>
		<tr>
		<?php
		while ($row_paymethods = $db->fetch_array($ret_paymethods))
		{
		$caption = ($row_paymethods['payment_method_sites_caption'])?$row_paymethods['payment_method_sites_caption']:$row_paymethods['paymethod_name'];
		
		if($row_paymethods['paymethod_secured_req']==1 and $protectedUrl==true) // if secured is required for current pay method and currently in secured. so no reload is required
		{
		$on_paymethod_click = 'handle_paytypeselect(this)';
		}	
		elseif ($row_paymethods['paymethod_secured_req']==1 and $protectedUrl==false) // case if secured is required and current not is secured. so reload is required
		{
		$on_paymethod_click = "handle_form_submit(document.frm_buygiftvoucher,'','')";
		}
		elseif ($row_paymethods['paymethod_secured_req']==0 and $protectedUrl==false) // case if secured is required and current not is secured. so reload is required
		{
		$on_paymethod_click = 'handle_paytypeselect(this)';
		}
		elseif ($row_paymethods['paymethod_secured_req']==0 and $protectedUrl==true) // case if secured is not required and current is secured. so reload is required
		{
		$on_paymethod_click = "handle_form_submit(document.frm_buygiftvoucher,'','')";
		}
		else
		{
		$on_paymethod_click = 'handle_paytypeselect(this)';
		}
		$curname = $row_paymethods['paymethod_key'].'_'.$row_paymethods['paymethod_id'];
		if($curname==$_REQUEST['voucher_paymethod'])
		{
		if (($row_paymethods['paymethod_key']=='SELF' or $row_paymethods['paymethod_key']=='PROTX') and $sel_ptype=='credit_card')
		{
		$self_disp = '';
		}	
		if($sel_ptype=='credit_card')	
		$sel = 'checked="checked"';
		}	
		else
		$sel = '';
		
		$img_path="./images/".$ecom_hostname."/site_images/payment_methods_images/".$row_paymethods['paymethod_ssl_imagelink'];										
		if(file_exists($img_path))
		$caption = '<img src="'.$img_path.'" border="0" alt="'.$caption.'" />';
		?>
		<td width="25%" align="left" class="gift_mid_table_td">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td align="left" valign="top" width="2%">
		<input class="shoppingcart_radio" type="radio" name="voucher_paymethod" id="voucher_paymethod" value="<?php echo $curname?>" <?php echo $sel ?>  onclick="<?php echo $on_paymethod_click?>" /></td>
		<td align="left">
		<?php echo stripslashes($caption)?>
		</td>
		</tr>
		</table>
		
		</td>
		
		<?php
		$pay_cnt++;
		if ($pay_cnt>=$pay_maxcnt)
		{
		echo "</tr><tr>";
		$pay_cnt = 0;
		}
		}
		if ($pay_cnt<$pay_maxcnt)
		{
		echo "<td colspan=".($pay_maxcnt-$pay_cnt).">&nbsp;</td>";
		}
		?>	
		</tr>
		</table>
		</div>
		</td>
		</tr>	
		<?php
		}
		}
		if($paytypes_cnt==1 && $totpaymethodcnt==0 && $single_curtype=='cheque')
		{
		$chequedisp_none = '';
		}	
		?>
		<tr id="voucher_cheque_tr" style="display:<?php echo $chequedisp_none?>" class="gift_mid_table_td">
		<td colspan="2" align="left" valign="middle" class="gift-send-bg_td">	
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td class="gift-hed-bg_td" colspan="2"><div class="gift-hed-bg"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CHEQUE_DETAILS']?></div>     </td>
		</tr>
		<?php
		// Get the list of credit card static fields to be shown in the checkout out page in required order
		$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
		FROM 
		general_settings_site_checkoutfields 
		WHERE 
		sites_site_id = $ecom_siteid 
		AND field_hidden=0 
		AND field_type='CHEQUE' 
		ORDER BY 
		field_order";
		$ret_checkout = $db->query($sql_checkout);
		if($db->num_rows($ret_checkout))
		{						
		while($row_checkout = $db->fetch_array($ret_checkout))
		{			
		// Section to handle the case of required fields
		if($row_checkout['field_req']==1)
		{
		$chkoutadd_Req[]		= "'".$row_checkout['field_key']."'";
		$chkoutadd_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
		}			
		?>
		<tr class="gift_mid_table">
		<td align="left" class="gift-send-inner_cnt" valign="top">
		<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
		</td>
		<td align="left" class="gift_mid_table_td" valign="top">
		<?php
		$pass_class_arr['txtbox_cls'] 		= 'gift_sendinput'; 
		$pass_class_arr['txtarea_cls'] 		= 'gift_sendinput'; 
		echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,'',$pass_class_arr);
		//echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
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
		<tr id="voucher_self_tr" style="display:<?php echo $self_disp?>">
		<td colspan="2" align="left" valign="middle" class="gift-send-bg_td">	
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
		<td class="gift-hed-bg_td" colspan="2"><div class="gift-hed-bg"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CREDIT_CARD_DETAILS']?></div>     </td>
		</tr>
		<?php
		$cur_form = 'frm_buygiftvoucher';
		// Get the list of credit card static fields to be shown in the checkout out page in required order
		$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
		FROM 
		general_settings_site_checkoutfields 
		WHERE 
		sites_site_id = $ecom_siteid 
		AND field_hidden=0 
		AND field_type='CARD' 
		ORDER BY 
		field_order";
		$ret_checkout = $db->query($sql_checkout);
		if($db->num_rows($ret_checkout))
		{						
		while($row_checkout = $db->fetch_array($ret_checkout))
		{			
		// Section to handle the case of required fields
		if($row_checkout['field_req']==1)
		{
		if($row_checkout['field_key']=='checkoutpay_expirydate' or $row_checkout['field_key']=='checkoutpay_issuedate')
		{
		$chkoutcc_Req[]			= "'".$row_checkout['field_key']."_month'";
		$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'";
		$chkoutcc_Req[]			= "'".$row_checkout['field_key']."_year'";
		$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
		}
		else
		{
		$chkoutcc_Req[]			= "'".$row_checkout['field_key']."'";
		$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
		}	
		}			
		?>
		<tr class="gift_mid_table">
		<td align="left" class="gift-send-inner_cnt">
		<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
		</td>
		<td align="left" class="gift_mid_table_td">
		<?php
		$pass_class_arr['txtbox_cls'] 		= 'gift_sendinput'; 
		$pass_class_arr['txtarea_cls'] 		= 'gift_sendinput'; 
		echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,$cur_form,$pass_class_arr);
		//echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,$cur_form);
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
		<td colspan="2" align="center" class="gift_mid_table_td">&nbsp;</td>
		</tr>
		<tr>
		<td colspan="2" align="center" class="gift_mid_table_td"><input name="buygiftvoucher_Submit" type="button" class="gift_buy_btn" id="buygiftvoucher_Submit" value="<?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_BUY_BUTTON']?>" onclick="validate_buygiftvoucher(document.frm_buygiftvoucher)" /></td>
		</tr>
		</table>		</div>
		</div>
		<div class="gift_mid_bottom"></div>
		</div>
</form>
<script type="text/javascript">
/* Function to be triggered when selecting the credit card type*/
function sel_credit_card_voucher(obj)
{
	if (obj.value!='')
	{
		objarr = obj.value.split('_');
		if(objarr.length==4) /* if the value splitted to exactly 4 elements*/
		{
			var key 		= objarr[0];
			var issuereq 	= objarr[1];
			var seccount 	= objarr[2];
			var cc_count 	= objarr[3];
			if (issuereq==1)
			{
				document.frm_buygiftvoucher.checkoutpay_issuenumber.className = 'inputissue_normal';
				document.frm_buygiftvoucher.checkoutpay_issuenumber.disabled	= false;
			}
			else
			{
				document.frm_buygiftvoucher.checkoutpay_issuenumber.className = 'inputissue_disabled';	
				document.frm_buygiftvoucher.checkoutpay_issuenumber.disabled	= true;
			}
		}
	}
}
function handle_paytypeselect(obj)
{
	var curpaytype = paytype_arr[obj.value];
	var ptypecnts = <?php echo $totpaycnt	?>;
	if (curpaytype=='credit_card')
	{
		if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = '';	
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = 'none';	
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = 1;
		if (document.getElementById('voucher_paymethod'))
		{
			var lens = document.getElementById('voucher_paymethod').length;	
			if(lens==undefined && ptypecnts==1)
			{
				var curval	 = document.getElementById('voucher_paymethod').value;
				cur_arr 		= curval.split('_');
				if ((cur_arr[0]=='SELF' || cur_arr[0]=='PROTX') && cur_arr.length<=2)
				{
					if(document.getElementById('voucher_cheque_tr'))
						document.getElementById('voucher_cheque_tr').style.display = 'none';
					if(document.getElementById('voucher_self_tr'))
						document.getElementById('voucher_self_tr').style.display 	= '';		
				}
				else
				{
					if(document.getElementById('voucher_self_tr'))
						document.getElementById('voucher_self_tr').style.display 	= 'none';
				}	
			}	
		}		
	}
	else if(curpaytype=='cheque')
	{
		if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = 'none';		
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = '';	
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';	
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if(curpaytype=='invoice')
	{
		if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = 'none';		
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = 'none';
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if(curpaytype=='pay_on_phone')
	{
		if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = 'none';		
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = 'none';
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if(curpaytype=='cash_on_delivery')
	{
		if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = 'none';		
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = 'none';
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if (curpaytype =='pay_on_account')
	{
			if(document.getElementById('voucher_paymethod_tr'))
			document.getElementById('voucher_paymethod_tr').style.display = 'none';		
		if(document.getElementById('voucher_cheque_tr'))
			document.getElementById('voucher_cheque_tr').style.display = 'none';
		if(document.getElementById('voucher_self_tr'))
			document.getElementById('voucher_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else 
	{
		cur_arr = obj.value.split('_');
		if ((cur_arr[0]=='SELF' || cur_arr[0]=='PROTX') && cur_arr.length<=2)
		{
			if(document.getElementById('voucher_cheque_tr'))
				document.getElementById('voucher_cheque_tr').style.display = 'none';
			if(document.getElementById('voucher_self_tr'))
				document.getElementById('voucher_self_tr').style.display 	= '';		
		}
		else
		{
			if(document.getElementById('voucher_self_tr'))
				document.getElementById('voucher_self_tr').style.display 	= 'none';
		}	
	}
}
function validate_buygiftvoucher(frm)
{
	<?php
		if(count($chkout_Req))
		{
	?>
			req_arr 			= new Array(<?php echo implode(",",$chkout_Req)?>);
			req_arr_str			= new Array(<?php echo implode(",",$chkout_Req_Desc)?>);
	<?php
		}
		if(count($chkoutadd_Req))
		{
	?>
			reqadd_arr 			= new Array(<?php echo implode(",",$chkoutadd_Req)?>);
			reqadd_arr_str		= new Array(<?php echo implode(",",$chkoutadd_Req_Desc)?>);
	<?php
		}
		if(count($chkoutcc_Req))
		{
	?>
			reqcc_arr 			= new Array(<?php echo implode(",",$chkoutcc_Req)?>);
			reqcc_arr_str		= new Array(<?php echo implode(",",$chkoutcc_Req_Desc)?>);
	<?php
		}
	?>
	fieldRequired		= new Array();
	fieldDescription	= new Array();
	for(i=0;i<req_arr.length;i++)
	{
		fieldRequired[i] 	= req_arr[i];
		fieldDescription[i] = req_arr_str[i];
	}
<?php
	if($Settings_arr['imageverification_req_voucher'])
	{
?>
		fieldRequired[i] 	= 'buygiftvoucher_Vimg';
		fieldDescription[i] = 'Image Verification Code';
		i++;
<?php
		
	}	
	if (count($chkoutadd_Req))
	{
?>
		if(document.getElementById('voucher_cheque_tr').style.display=='') /* do the following only if checque is selected */
		{
			for(j=0;j<reqadd_arr.length;j++)
			{
				fieldRequired[i] 	= reqadd_arr[j];
				fieldDescription[i] = reqadd_arr_str[j];
				i++;
			}
		}
<?php
	}
	if (count($chkoutcc_Req))
	{
?>
		if(document.getElementById('voucher_self_tr').style.display=='') /* do the following only if protx or self  is selected */
		{
			for(j=0;j<reqcc_arr.length;j++)
			{
				fieldRequired[i] 	= reqcc_arr[j];
				fieldDescription[i] = reqcc_arr_str[j];
				i++;
			}
		}	
	<?php
	}	
	if (count($chkout_Email))
	{
		$chkout_Email_Str 		= implode(",",$chkout_Email);
		echo "fieldEmail 			= Array(".$chkout_Email_Str.");";
	}
	else
		echo "fieldEmail 			= Array();";
	// Password checking
	if (count($chkout_Confirm))
	{
		$chkout_Confirm_Str 			= implode(",",$chkout_Confirm);
		$chkout_Confirmdesc_Str		= implode(",",$chkout_Confirmdesc);
		echo "fieldConfirm 				= Array(".$chkout_Confirm_Str.");";
		echo "fieldConfirmDesc 		= Array(".$chkout_Req_Desc_Str.");";
	}
	else
	{
		echo "fieldConfirm 			= Array();";
		echo "fieldConfirmDesc 	= Array();";
	}	
	if (count($chkout_Numeric))
	{
		$chkout_Numeric_Str 		= implode(",",$chkout_Numeric);
		echo "fieldNumeric 			= Array(".$chkout_Numeric_Str.");";
	}
	else
		echo "fieldNumeric 			= Array();";
	?>
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		/* Check whether voucher value and active days > 0 */
		if (frm.voucher_value.value<=0)
		{
			alert ('Please specify voucher value')
			frm.voucher_value.focus();
			return false;
		}
		if (frm.voucher_noofdaysactive.value<=0)
		{
			alert ('Please specify number of active days')
			frm.voucher_noofdaysactive.focus();
			return false;
		}
		/* Check whether atleast one payment type is selected */
		var atleastpay = false;
		for(k=0;k<frm.elements.length;k++)
		{
			if(frm.elements[k].name=='voucher_paytype')
			{
				if(frm.elements[k].type=='hidden')
					atleastpay = true; /* Done to handle the case of only one payment type */
				else if(frm.elements[k].checked==true)
					atleastpay = true;	
			}	
		}	
		if(atleastpay==false)
		{
			alert('Please select payment type');
			return false;	
		}
		if (document.getElementById('paymentmethod_req').value==1)
		{
			var atleast = false;
			for(k=0;k<frm.elements.length;k++)
			{
				if(frm.elements[k].name=='voucher_paymethod')
				{
					if(frm.elements[k].type=='hidden')
						atleast = true; /* Done to handle the case of only one payment method */
					else if(frm.elements[k].checked==true)
						atleast = true;	
				}	
			}	
			if(atleast ==false)
			{
				alert('Please select a payment method');
				return false;	
			}	
		}	
		else
		{
			if (document.getElementById('voucher_paymethod'))
				document.getElementById('voucher_paymethod').value = 0;
		}	
		
		/* Handling the case of credit card related sections*/
		if(frm.checkoutpay_cardtype)
		{
			if(frm.checkoutpay_cardtype.value)
			{
				objarr = frm.checkoutpay_cardtype.value.split('_');
				if(objarr.length==4) /* if the value splitted to exactly 4 elements*/
				{
					var key 		= objarr[0];
					var issuereq 	= objarr[1];
					var seccount 	= objarr[2];
					var cc_count 	= objarr[3];
					if (isNaN(frm.checkoutpay_cardnumber.value))
					{
						alert('Credit card number should be numeric');
						frm.checkoutpay_cardnumber.focus();
						return false;
					}
					if (frm.checkoutpay_cardnumber.value.length>cc_count)
					{
						alert('Credit card number should not contain more than '+cc_count+' digits');
						frm.checkoutpay_cardnumber.focus();
						return false;
					}
					if (frm.checkoutpay_securitycode.value.length>seccount)
					{
						alert('Security Code should not contain more than '+seccount+' digits');
						frm.checkoutpay_securitycode.focus();
						return false;
					}
				}
			}
		}			
		/* If reached here then everything is valid 
			change the action of the form to the desired value
		*/
			/*frm.action = '<?php //url_link('submit_voucher.html',2)?>';*/
			/*frm.action = 'gift_voucher_submit.php?bsessid=<?php //echo base64_encode($ecom_hostname)?>';*/
			frm.action = 'gift_voucher_submit.php';
			if(document.getElementById('nrm'))
					document.getElementById('nrm').value  	= 2;
			if(document.getElementById('save_voucherdetails'))
					document.getElementById('save_voucherdetails').value  	= 1;
		frm.submit();
	}	
	else
		return false;
}	
</script>	
<?php	
}   

	/* 
		Function to show the preview for the gift voucher details
	*/
	function Show_giftvoucherPreview($return_voucher_arr,$alert='',$just_for_display=false)
	{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
					$Captions_arr,$inlineSiteComponents,$ecom_email,$ecom_common_settings,$ecom_testing,$sitesel_curr;
		$Captions_arr['GIFT_VOUCHER'] 	= getCaptions('GIFT_VOUCHER'); // Getting the captions to be used in this page
		$sessid	= session_id();
		if ($alert=='preview_before_gateway') // handing the case of session only if 
		{
			// Setting the current voucher id in session 
			set_session_var('gateway_voucher_id',$return_voucher_arr['voucher_id']);
		}
		if(!$return_voucher_arr['voucher_id'])
		{
			// If voucher id is fake then redirect back to cart page
				echo "<script type='text/javascript'>window.location='http://".$ecom_hostname."/buy_voucher.html'</script>";
				exit;	
		}
		// Get the details regarding current voucher from gift_vouchers table
		 $sql_vouch = "SELECT *  
						FROM 
							gift_vouchers 
						WHERE 
							voucher_id = ".$return_voucher_arr['voucher_id']." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_vouch = $db->query($sql_vouch);
		if ($db->num_rows($ret_vouch))
		{
			$row_vouch = $db->fetch_array($ret_vouch);
			// get other details related to current order
			$sql_vouch_cust = "SELECT * 
									FROM 
										gift_vouchers_customer 
									WHERE 
										voucher_id = ".$return_voucher_arr['voucher_id']." 
									LIMIT 
										1";
			$ret_vouch_cust = $db->query($sql_vouch_cust);
			if ($db->num_rows($ret_vouch_cust))
				$row_vouch_cust = $db->fetch_array($ret_vouch_cust);
			
		}
		else // return back to gift voucher purchase page
		{
			// If voucher id is fake then redirect back to cart page
				echo "<script type='text/javascript'>window.location='http://".$ecom_hostname."/buy_voucher.html'</script>";
				exit;	
		}
		
		switch($alert)
		{
			case 'preview_before_gateway':// case of HSBC or worldpay
				$alert = 'GIFT_VOUCHER_BEFORE_GATEWAY';
			break;	
			case 'preview_after_protx': // case of protx payment successfull
				$alert = 'GIFT_VOUCHER_PROTX_SUCCESS';
			break;
			case 'preview_after_ptype': // checque/payonphone etc
			case 'preview_after_self': // case of self
				$alert = 'GIFT_VOUCHER_PREVIEW_DONE_AWAITING';	
			break;
			case 'pay_succ': // case of coming directly after payment success from gateway
				// A double checking to see whether the payment status of currrent voucher is 'Paid'
				if($row_vouch['voucher_paystatus']=='Paid') // is paid
					$alert = 'GIFT_VOUCHER_PREVIEW_DONE_PAYMENT';	
				else 	// if not paid. case came here by directly typing the url
					$alert = '';
			break;			
				
		};
?>		
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_TREEMENU_PREVIEWTITLE']?></div>
		<div class="gift_mid_con">
        <div class="gift_mid_top"></div>
        <div class="gift_mid_middle" >
     	<div class="gift_mid_des">
		<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="gift_mid_table">
		<tr>
			<td width="100%" class="gift_buy_header" colspan="2"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_TREEMENU_PREVIEWTITLE']?></td>
		</tr>
		<?php 
			if($alert)
			{ 
		?>
				<tr>
					<td colspan="2" class="errormsg" align="center">
				<?php 
					if($Captions_arr['GIFT_VOUCHER'][$alert])
					{
						echo $Captions_arr['GIFT_VOUCHER'][$alert];
					}
					else
					{
						echo $alert;
					}
				?>	
					</td>
				</tr>
		<?php 
			}
		?>
		<?php
		if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VALUE_PREVIEW_MESSAGE_TEXT'])
		{
		?>
			<tr>
				<td class="gift-hed-bg_td" colspan="2"><div class="gift-hed-bg"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VALUE_PREVIEW_MESSAGE_TEXT']?></div>     </td>
			</tr>
		<?php
		}
		if($just_for_display==false) // show the following section only if required
		{
		?>				
			<tr>
				<td colspan="2" align="right" class="emailfriendtextnormal">
				<?php
					$display_option = 'ALL';
					include 'voucher_preview_gateway_include.php';
					?>			 
				</td>
			</tr>
		<?php
			}
		?>
		<tr class="gift_mid_table">
		<td class="gift-send-inner_cnt" id="vouch_caption_td" width="45%"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_PERCENT_LABEL']?></td>
		<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo print_price($row_vouch['voucher_value'])?></td>
		</tr>
		<?php
			if($row_vouch['voucher_paystatus']!='Paid') // case payment is not successfull yet
			{
		?>		
			<tr class="gift_mid_table">
				<td class="gift-send-inner_cnt"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_NOOFDAYS_LABEL']?></td>
				<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo $row_vouch['voucher_activedays']?></td>
			</tr>
			<tr class="gift_mid_table">
			<td colspan="2" align="center" valign="middle" class="redtext"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_NOOFDAYS_STARTDATE_NOTE']?></td>
			</tr>
		<?php
			}
			else // case payment is successfully
			{
		?>
				<tr class="gift_mid_table">
					<td class="gift-send-inner_cnt">Activation Date</td>
					<td  align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo dateFormat($row_vouch['voucher_activatedon'])?></td>
				</tr>
				<tr class="gift_mid_table">
					<td class="gift-send-inner_cnt">Expires On</td>
					<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo dateFormat($row_vouch['voucher_expireson'])?></td>
				</tr>
		<?php	
			}
		?>
		<tr id="emailvoucher_div">
		<td colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?php
			if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_MESSAGE_TEXT'])
			{
			?>
				<tr>
				<td colspan="2" class="shoppingcartheader"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_MESSAGE_TEXT']?> </td>
				</tr>
			<?php
			}
			?>
			<tr class="gift_mid_table">
				<td class="gift-send-inner_cnt" width="45%"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_TO_NAME_LABEL']?></td>
				<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo stripslashes($row_vouch_cust['voucher_toname'])?></td>
			</tr>
			<tr class="gift_mid_table">
				<td class="gift-send-inner_cnt"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_TO_EMAIL_LABEL']?></td>
				<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo stripslashes($row_vouch_cust['voucher_toemail'])?></td>
			</tr>
			<tr class="gift_mid_table">
				<td class="gift-send-inner_cnt" valign="top"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_EMAIL_VOUCHER_TO_MESSAGE_LABEL']?></td>
				<td align="left" valign="middle" class="gift-send-inner_cnt">: <?php echo stripslashes($row_vouch_cust['voucher_tomessage'])?>
				</td>
			</tr>
			</table>
		</td>
		</tr>
		<?
		if($Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_BUYERS_PREVIEW_MESSAGE_TEXT'])
		{
		?>
			<tr>
				<td class="gift-hed-bg_td" colspan="2"><div class="gift-hed-bg"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_BUYERS_PREVIEW_MESSAGE_TEXT']?> </div>     </td>
			</tr>
		<?
		}?>
		<?php
		
		// Get the list of delivery address static fields to be shown in the checkout out page in required order
	  $sql_checkout = "SELECT field_key,field_name,field_orgname 
						FROM 
						general_settings_site_checkoutfields 
						WHERE 
						sites_site_id = $ecom_siteid 
						AND field_hidden=0 
						AND field_type='VOUCHER' 
						ORDER BY 
						field_order";
		$ret_checkout = $db->query($sql_checkout);
		if($db->num_rows($ret_checkout))
		{						
			while($row_checkout = $db->fetch_array($ret_checkout))
			{		
				?>
				<tr class="gift_mid_table">
					<td align="left" class="gift-send-inner_cnt" valign="top">
					<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>
					</td>
					<td align="left" class="gift-send-inner_cnt">: 
					<?php	echo stripslashes($row_vouch_cust[$row_checkout['field_orgname']]);?>
					</td>
				</tr>
	<?php
			}
		}
			if($just_for_display==false) // show the following section only if required
			{
		?>				
				<tr class="gift_mid_table">
					<td colspan="2" align="right" class="gift-send-inner_cnt">
					<?php
						$display_option = 'BUTTON_ONLY';
						include 'voucher_preview_gateway_include.php';
					?>			 
					</td>
				</tr>
		<?php
			}
			else 
			{
		?>
				<tr class="gift_mid_table">
					<td colspan="2" align="right" class="gift-send-inner_cnt">
						<input type="button" name="submit_backhome" value="<?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_PREVIEW_BACH_HOME']?>" onclick="window.location ='<?php url_link('')?>'" />
					</td>
				</tr>	
		<?php
			}	
		?>
		</table>
		</div>
</div>
<div class="gift_mid_bottom"></div>
</div>
<?php		
		// #############################################################################
		
	
	}
	
	/* Function to show the voucher failed message*/
	function Show_VoucherFailed()
	{
		global $db,$ecom_hostname,$Captions_arr,$ecom_siteid;
		$sess_id = session_id();
		// Get the error details from voucher cart table
		$sql_cart = "SELECT voucher_error_msg 
						FROM 
							gift_voucherbuy_cartvalues  
						WHERE 
							sites_site_id =$ecom_siteid
							AND session_id='".$sess_id."'";
		$ret_cart = $db->query($sql_cart);
		if($db->num_rows($ret_cart))
		{
			$row_cart 	= $db->fetch_array($ret_cart);
			$msg		= stripslashes(trim($row_cart['cart_error_msg_ret']));
		}
		// update the cart_error_msg_ret field with blank 
		$update_array						= array();
		$update_array['voucher_error_msg']	= '';
		$db->update_from_array($update_array,'gift_voucherbuy_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));					
		
		$Captions_arr['GIFT_VOUCHER'] 	= getCaptions('GIFT_VOUCHER'); // Getting the captions to be used in this page
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
		<tr>
			<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_FAILED_TITLE']?></div></td>
		</tr>
		<?php
			if($msg)
			{
		?>
				<tr>
					<td align="left" class="shoppingcartcontent_indent_highlight">
					<?php echo $msg?>
					</td>
				</tr>
		<?php
			}
			else
			{
		?>
				<tr>
					<td align="left" class="shoppingcartcontent_indent_highlight">
					<?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_FAILED_MSG']?><br /><br />
					<?php
					if($_REQUEST['error'])
					{
						echo $_REQUEST['error'];
					}
					?>
					</td>
				</tr>
		<?php	
			}
		?>
		</table>	
	<?php	
	}
	// #########################################################################################################
	// Function to show the section to display the details related to gift voucher or promotional code spending
	// #########################################################################################################
	function Spend_voucher()
	{
		global $db,$ecom_hostname,$Captions_arr,$ecom_siteid,$spend_alert,$Settings_arr,$vImage,$inlineSiteComponents;
		$sess_id 	= session_id();
		$cust_id	= get_session_var("ecom_login_customer"); // Get the customer id from session
		$Captions_arr['GIFT_VOUCHER'] 	= getCaptions('GIFT_VOUCHER'); // Getting the captions to be used in this page
		$sql_cartdet = "SELECT promotionalcode_id, voucher_id 
							FROM 
								cart_supportdetails 
							WHERE 
								session_id='".$sess_id."'  
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_cartdet = $db->query($sql_cartdet);
		if($db->num_rows($ret_cartdet)) 
			$row_cartdet = $db->fetch_array($ret_cartdet); // Fetch the details to a record set
		if (($row_cartdet['promotionalcode_id']==0 and $row_cartdet['voucher_id']==0))
		{	
	?>
		<form method="post" name="voucher_frm" id="voucher_frm" action="" class="frm_cls" onsubmit="return validate_smallvoucher(this,'<?php echo $vimgfield?>')">
		<input type='hidden' name='cart_savepromotional' id="cart_savepromotional_comp" value="1" />
		<input type='hidden' name='from_section' id="from_section" value="spend_section" />
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SPEND_HEADER']?></div>
		<div class="gift_mid_con">
        <div class="gift_mid_top"></div>
        <div class="gift_mid_middle" >
    	 <div class="gift_mid_des">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="gift_mid_table">
			 <tr>
				<td width="100%" class="gift_spend_header" align="left"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SPEND_HEADER']?></td>
			 </tr>
			<?php
			if($spend_alert)
			{
			?>
				<tr>
					<td align="center" valign="middle" colspan="2" class="redtext"><?php echo $spend_alert?></td>
				</tr>
			<?php
			}
			?>
			<?php
			if(trim($Settings_arr['voucher_buy_text'])!='')
			{
	?>
				  <tr>
					<td class="gift_spend_table_td">
					<div class="div_gift_spend_cnt">
					<?=stripslashes($Settings_arr['voucher_buy_text'])?>
					</div>
					</td>
				  </tr>
	<? 
			}
	?>			
				<tr>
				<td class="gift_spend_table_td">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="gift_user_iner">
				<tr class="gift_mid_table">
				  <td align="left" valign="middle" class="gift_user_fontA" id="vouch_caption_td"><?php echo $Captions_arr['GIFT_VOUCHER']['ENTER_VOUCHER']?><span class="redtext">*</span> </td>
				  <td width="28%" align="left" valign="middle" class="gift_user_fontB">
					<label>
					<input name="cart_promotionalcode" id="cart_promotionalcode" type="text" class="gift_user_textbox" />
				  </label></td>
				<td width="32%" align="left" valign="middle" class="gift-send-inner_hed">
				<?php 
					if(!$Settings_arr['imageverification_req_voucher']) // if image verification is required
					{
					?>
					<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
					<?php
					}
					?>
				  </td>
				</tr>
				<?php 
					if($Settings_arr['imageverification_req_voucher']) // if image verification is required
					{
				?>
	
				  <tr>
					<td align="left" class="gift_user_fontA"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VERIFICATION_CODE']?>&nbsp;<span class="redtext">*</span></td>
				 	 <td align="left" colspan="2" valign="middle" class="gift_user_fontB"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=buycompgiftvoucher_Vimg')?>" border="0" alt="Image Verification"/></td>
				  </tr>
				  <tr>
				  	<td>&nbsp;</td>
					<td align="left" class="gift_user_fontA" colspan="2">
					  <?php 
						// showing the textbox to enter the image verification code
						$vImage->showCodBox(1,'buycompgiftvoucher_Vimg','class="inputA_imgver"'); 
					  ?>
					</td>
					</tr>
					<tr>
					<td class="gift_user_fontA">&nbsp;</td>
					<td align="left"  class="gift_user_fontB" colspan="2">
						<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
					</td>
				  </tr>
			  <?php
					}
				?>
				</table>
				</td>
				</tr>
				</table>
		 </div>
        </div>
        <div class="gift_mid_bottom"></div>
      </div>	
			</form>
	<?php	
		}
		else // case if gift voucher or promotional code already specified. Then show the details
		{ 
		?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SPEND_HEADER']?></div>
			<div class="gift_mid_con">
        <div class="gift_mid_top"></div>
        <div class="gift_mid_middle" >
    	 <div class="gift_mid_des">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="gift_mid_table">
			 <tr>
				<td width="100%" class="gift_spend_header"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_SPEND_HEADER']?></td>
			 </tr>
			<?php
			if($spend_alert)
			{
			?>
				<tr>
					<td align="center" valign="middle" colspan="2" class="redtext"><?php echo $spend_alert?></td>
				</tr>
			<?php
			}
			//if (($row_cartdet['promotionalcode_id']==0 and $row_cartdet['voucher_id']==0))
			if ($row_cartdet['voucher_id']!=0) // case if code is a gift voucher
			{
				$cur_code_type = 'VOUC';
				$cancel_caption = $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CANCEL_VOUCH'];
			}
			else // case if code is promotional code
			{
				$cur_code_type = 'PROM';
				$cancel_caption = $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CANCEL_PROM'];
			}
		?>	
			<tr>
				<td align="right" valign="middle" class="cancel_promcode">
				<form method="post" action="" id="frm_giftcode_cancel" name="frm_giftcode_cancel">
					<input type="hidden" name="new_purpose" id="new_purpose" value="spend_cancel" />
					<input type="button" name="cancel_spend_gift_button" id="cancel_spend_gift_button" value="<?php echo $cancel_caption?>" class="buttongray" onclick="if(confirm('<?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CANCEL_CONF']?>')) { document.frm_giftcode_cancel.submit()}"/>
				</form>
				</td>
			</tr>
			<tr>
				<td class="gift_spend_table_td">
			<?php
			if ($row_cartdet['voucher_id']!=0) // case if code is a gift voucher
			{
				// Get the details of current gift voucher from 
				$sql_gift = "SELECT DATE_FORMAT(voucher_expireson,'%d-%b-%Y') showdate, voucher_type, voucher_value,
									voucher_number,voucher_freedelivery,voucher_max_usage,
									voucher_usage 
								FROM 
									gift_vouchers 
								WHERE 
									voucher_id = ".$row_cartdet['voucher_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_gift = $db->query($sql_gift);
				if($db->num_rows($ret_gift))
				{
					$row_gift = $db->fetch_array($ret_gift);
				}
			?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
					<td width="50%" align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_NUMBER']?></td>
					<td align="left" class="gift_user_fontB">: <?php echo stripslashes($row_gift['voucher_number'])?></td>
				</tr>
				<tr>
					<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_VALID_TILL']?></td>
					<td align="left" class="gift_user_fontB">: <?php echo stripslashes($row_gift['showdate'])?></td>
				</tr>
				<tr>
					<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_MAX_DISC']?></td>
					<td align="left" class="gift_user_fontB">: 
					<?php 
						if($row_gift['voucher_type']=='per')
							echo $row_gift['voucher_value']. $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_PER_TOT'];
						elseif($row_gift['voucher_type']=='val')
							echo print_price($row_gift['voucher_value'],true);
					?>
					</td>
				</tr>
				<tr>
					<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_NUMBER_TIMES']?></td>
					<td align="left" class="gift_user_fontB">: <?php echo ($row_gift['voucher_max_usage']-$row_gift['voucher_usage'])?>
					</td>
				</tr>
				<?php
					if($row_gift['voucher_freedelivery']==1)
					{
				?>
					<tr>
						<td colspan="2" align="center" class="redtext"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_FREE_OF_COST']?></td>
					</tr>
				<?php
				}
				?>
				</table>
	
			<?php
			}	
			elseif($row_cartdet['promotionalcode_id']!=0)
			{
				// Get the details of current gift voucher from 
				$sql_prom = "SELECT code_id,code_number, DATE_FORMAT(code_enddate,'%d-%b-%Y') showdate, code_type, code_minimum,
									code_value, code_unlimit_check, code_limit, code_usedlimit,
									code_customer_unlimit_check, code_customer_limit, code_customer_usedlimit,
									code_freedelivery,code_login_to_use 
								FROM 
									promotional_code  
								WHERE 
									code_id = ".$row_cartdet['promotionalcode_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prom = $db->query($sql_prom);
				if($db->num_rows($ret_prom))
				{
					$row_prom = $db->fetch_array($ret_prom);
					if ($cust_id)
					{
						// Find the total number of time this code has been used by current customer
						$sql_cnt = "SELECT count(orders_order_id) as cust_usedcnt 
										FROM
											order_promotionalcode_track a, orders b 
										WHERE
											a.sites_site_id=$ecom_siteid 
											AND b.order_id = a.orders_order_id 
											AND b.order_status NOT IN ('NOT_AUTH') 
											AND code_number='".stripslashes($row_prom['code_number'])."' 
											AND a.promotional_code_code_id = ".$row_cartdet['promotionalcode_id']." 
											AND a.customers_customer_id = $cust_id";						
						$ret_cnt = $db->query($sql_cnt);
						list($custused_cnt) = $db->fetch_array($ret_cnt);
					}					
					// Find the total number of time this code has been used by all customer
					$sql_cnt = "SELECT count(orders_order_id) as cust_usedcnt 
										FROM
											order_promotionalcode_track a, orders b 
										WHERE
											a.sites_site_id=$ecom_siteid 
											AND b.order_id = a.orders_order_id 
											AND b.order_status NOT IN ('NOT_AUTH') 
											AND code_number='".stripslashes($row_prom['code_number'])."' 
											AND a.promotional_code_code_id = ".$row_cartdet['promotionalcode_id'];
					$ret_cnt = $db->query($sql_cnt);
					list($totalused_cnt) = $db->fetch_array($ret_cnt);
				}
			?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
					<td width="40%" align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_PROM_CODE']?></td>
					<td align="left" class="gift_user_fontB">: <?php echo stripslashes($row_prom['code_number'])?></td>
				</tr>
				<tr>
					<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_VALID_TILL']?></td>
					<td align="left" class="gift_user_fontB">: <?php echo stripslashes($row_prom['showdate'])?></td>
				</tr>
				<tr>
					<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_CODE_MAX_TIMES']?></td>
					<td align="left" class="gift_user_fontB">: 
					<?php 
						$rem_total 	= ($row_prom['code_limit'] - $totalused_cnt); 
						if ($cust_id)
						{
							$rem_cust 	= ($row_prom['code_customer_limit']-$custused_cnt);
							if($row_prom['code_customer_unlimit_check']==1 and $row_prom['code_unlimit_check']==1)
								echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_UNLIMITED'];
							elseif($row_prom['code_customer_unlimit_check']==0 and $row_prom['code_unlimit_check']==1)	
							{
								echo $rem_cust;
							}	
							elseif($row_prom['code_customer_unlimit_check']==1 and $row_prom['code_unlimit_check']==0)	
							{
								echo $rem_total;
									
							}	
							elseif($row_prom['code_customer_unlimit_check']==0 and $row_prom['code_unlimit_check']==0)	
							{
								if($rem_total>=$rem_cust)
									echo $rem_cust;
								else
									echo $rem_total;
							}	
						}
						else
						{
							if($row_prom['code_unlimit_check']==1)
								echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_UNLIMITED'];
							else
								echo $rem_total;
						}
						
					?>
					</td>
				</tr>
				<?php
				if ($row_prom['code_type'] != 'product')
				{
				?>
					<tr>
						<td align="left" class="gift_user_fontA"><?php echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_MAX_DISC']?></td>
						<td align="left" class="gift_user_fontB">: 
						<?php 
							if($row_prom['code_type']=='default')
								echo $row_prom['code_value'].' '.$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_PER_TOT'];
							elseif($row_prom['code_type']=='money')
								echo print_price($row_prom['code_value'],true) .' '.$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_MIN_PURCHASE'].' '.print_price($row_prom['code_minimum'],true);
							elseif($row_prom['code_type']=='percent')
								echo $row_prom['code_value'] .' '.$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_MIN_PER_PURCHASE'].' '.print_price($row_prom['code_minimum'],true);	
						?>
						</td>
					</tr>
					</table>
					</td>
			</tr>
			</table>  
				<?php
				}
				else // case if discount is allowed for certain products
				{
				?>
					<tr>
						<td align="left" colspan="2" class="gift_prod_heading">
						<?php 
							echo $Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_PROM_PRODUCTS']
						?>	
						</td>
					</tr>	
					</table>
					</td>
			</tr>
			</table>  
						<?php
							$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
											a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
											a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
											product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
											a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
											a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
											a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
											a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
											a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								            a.product_freedelivery ,  
											b.product_price      
										FROM 
											products a,promotional_code_product b 
										WHERE 
											a.sites_site_id = $ecom_siteid 
											AND a.product_id = b.products_product_id 
											AND b.promotional_code_code_id = ".$row_cartdet['promotionalcode_id']." 
											AND a.product_hide ='N' 
										ORDER BY 
											product_price";
							$ret_prod = $db->query($sql_prod);
							if($db->num_rows($ret_prod))
							{
								$list_style 			= $Settings_arr['promo_prodlisting'];
								$pass_type				= get_default_imagetype('midshelf');
								$prod_compare_enabled 	= isProductCompareEnabled();
									switch($list_style)
									{
										case '1row':
										?>
										<div class="shelfBtable">
										<?php
										while ($row_prod = $db->fetch_array($ret_prod))
										{
										?>
											<div class="shelfBtabletd">
												<div class="shelfBtabletdinner">
												<div class="shelfBprodname">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a>
												</div>
												<div class="shelfBleft">
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
												<?php
													
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
													if(count($img_arr))
													{
														show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
														}	
													}	
												?>
												</a>
												<div class="compare_li">
												<?php
												if($prod_compare_enabled)
												{
													dislplayCompareButton($row_prod['product_id']);
												}
												?>	
												</div> 
												<?php
													$module_name = 'mod_product_reviews';
												?>
												</div>
												<div class="shelfBmid">	
												<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
												<?php
												   $price_class_arr['ul_class'] 		= 'shelfBpriceul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'bestseller_1');
													if($row_prod['product_saleicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_sale"><?php echo $desc?></div>
												<?php
														}
													}	
													if($row_prod['product_newicon_show']==1)
													{
														$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
														if($desc!='')
														{
												?>
															<div class="shelfB_newsale"><?php echo $desc?></div>		
												<?php
														}
													}	
													
												if(in_array($module_name,$inlineSiteComponents))
													{
														if($row_prod['product_averagerating']>=0)
														{
														?>
															<div class="shelfB_rate">
															<?php
																display_rating($row_prod['product_averagerating']);
															?>
															</div>
														<?php
														}
													}	
													?>						  
												</div>
												<div class="shelfBright"> 
												<?php 
												if($row_prod['product_freedelivery']==1)
												{	
												?>
													<div class="shelfB_free"></div>
												<?php
												}
												if($row_prod['product_bulkdiscount_allowed']=='Y')
												{
												?>
													<div class="shelfB_bulk"></div>
												<?php
												}
												$frm_name = uniqid('best_');
												?>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
												<div class="infodivB">
												<div class="infodivBleft">
												<?php show_moreinfo($row_prod,'infolink')?></div>
												<div class="infodivBright">
												<?php
													$class_arr 					= array();
													$class_arr['ADD_TO_CART']	= 'infolinkB';
													$class_arr['PREORDER']		= 'infolinkB';
													$class_arr['ENQUIRE']		= 'infolinkB';
													show_addtocart($row_prod,$class_arr,$frm_name)
												?>
												</div>		
												</div>
												</form>
												</div>
												</div>
												</div>
										<?php
										}
										?>
										</div>	
										<?php		
										break;
										case '3row':
										?>
										<div class="shelfAtable" >
											<?php
											$max_col = 3;
											$cur_col = 0;
											$prodcur_arr = array();	
											while($row_prod = $db->fetch_array($ret_prod))
											{
												if($cur_col == 0)
												{ 
													echo '<div class="mid_shlf2_con_main">';
												}
												$cur_col ++;
											?>		
																		
											<div class="shelfAtabletd">
											<div class="shelfAtabletdinner">
											<ul class="shelfAul">
											<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
											<li class="compare_li">
											<?php
											if($prod_compare_enabled)
											{
												dislplayCompareButton($row_prod['product_id']);
											}?>
											</li>														
											<li class="shelfimg">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
											<?php
											// Calling the function to get the type of image to shown for current 
											//$pass_type = get_default_imagetype('midshelf');
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
											}
											else
											{
											// calling the function to get the default image
											$no_img = get_noimage('prod',$pass_type); 
											if ($no_img)
											{
											show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
											}	
											}	
											?>
											</a>
											<?php
											if($row_prod['product_freedelivery']==1)
											{
											?>
											<div class="shelfA_free"></div>
											<?php
											}
											if($row_prod['product_bulkdiscount_allowed']=='Y')
											{
											?>
											<div class="shelfA_bulk"></div>
											<?php
											}
											?>
											</li>
											<?
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
												if($row_prod['product_averagerating']>=0)
												{
												?>
													<li><div class="shelfB_rate">
												<?php
													display_rating($row_prod['product_averagerating']);
												?>
													</div></li>
												<?php
												}
											}	
											?>
												<li>
													<?php
													$price_class_arr['ul_class'] 		= 'shelfApriceul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'bestseller_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													?>	
												</li>
												<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
											</ul>
											<?
											if($row_prod['product_saleicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
												if($desc!='')
												{
											?>	
												<div class="shelfA_sale"><?php echo $desc?></div>
											<?php
												}
											}
											if($row_prod['product_newicon_show']==1)
											{
												$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
												if($desc!='')
												{
											?>
												<div class="shelfA_newsale"><?php echo $desc?></div>
											<?php
												}
											}
											$frm_name = uniqid('best_');
											
											?>
											<div class="bonus_point"><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
											</div>
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
											<div class="infodiv">
											<div class="infodivleft">
											<?php show_moreinfo($row_prod,'infolink')?></div>
											<div class="infodivright">
											<?php
											$class_arr 					= array();
											$class_arr['ADD_TO_CART']	= 'infolink';
											$class_arr['PREORDER']		= 'infolink';
											$class_arr['ENQUIRE']		= 'infolink';
											show_addtocart($row_prod,$class_arr,$frm_name)
											?> 
											</div>		
											</div>
											</form>
											</div>
											</div>
											<?
											if($cur_col>=$max_col)
												{
													$cur_col =0;
													echo "</div>";
												}
											}
											// If in case total product is less than the max allowed per row then handle that situation
											if($cur_col<$max_col)
											{
												if($cur_col!=0)
												{ 
													echo "</div>";
												} 
											}
											?>
											</div>
										<?php
										break;
									};
									
									}
									?>
										
					<?php
					}
					?>
			<?php
			}
			?>
			
		</div>
        </div>
        <div class="gift_mid_bottom"></div>
      	</div>
		<?php	
		}
	}
};	
?>