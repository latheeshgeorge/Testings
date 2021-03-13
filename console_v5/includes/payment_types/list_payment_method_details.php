<?php
	/*#################################################################
	# Script Name 	: list_payment_method_details.php
	# Description 	: Page for console users to set the details related to a selected payment method
	# Coded by 		: Sny
	# Created on	: 27-Mar-2009
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	$sel_method_id = $_REQUEST['paymethod_id'];
	$sql_method = "SELECT a.paymethod_name,a.paymethod_key,b.payment_method_sites_caption,b.payment_method_google_recommended  
					FROM 
						payment_methods a, payment_methods_forsites b 
					WHERE 
						a.paymethod_id = b.payment_methods_paymethod_id  
						AND b.sites_site_id=$ecom_siteid 
						AND a.paymethod_id = $sel_method_id 
					LIMIT 
						1";
	$ret_method = $db->query($sql_method);
	if($db->num_rows($ret_method))
	{
		$row_method = $db->fetch_array($ret_method);
		$caption	= stripslashes(($row_method['payment_method_sites_caption']!='')?$row_method['payment_method_sites_caption']:$row_method['paymethod_name']);
	}						
	//Define constants for this page
	$help_msg = 'Specify the details for the "'.$caption.'"';//get_help_messages('LIST_PAYMENT_CARDS_MESS1');
	?>
	<form method="post" name="frm_paymethod_details" class="frmcls" action="home.php" onsubmit="return valform(this)">
	<input type="hidden" name="request" value="payment_types" />
	<input type="hidden" name="fpurpose" value="payment_details_save" />
	<input type="hidden" name="paymethod_id" id="paymethod_id" value="<?php echo $_REQUEST['paymethod_id']?>" />
		<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
		  <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payment_types&sort_order=<? echo $_REQUEST['pass_sort_order']?>&sort_by=<? echo $_REQUEST['pass_sort_by']?>">List Payment types</a> <a href="home.php?request=payment_types&fpurpose=view_methods">List Payment Methods</a><span> Set Payment method details</span></div></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd" colspan="3">
		 <div class="helpmsg_divcls"> <?php 
			  echo $help_msg;
		  ?></div>
		 </td>
		</tr>
		<?php 
				if($alert)
				{			
			?>
					<tr>
						<td colspan="3" align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
					</tr>
			 <?php
				}
			 ?> 
		
		
		<tr>
		  <td colspan="3" class="listingarea">
		  <div class="editarea_div">
			<table width="100%" align="center" cellpadding="1" cellspacing="1" border="0">
			<?php
				$req_field 	= array();
				$req_name 	= array();
				$sql_det = "SELECT payment_method_details_id,payment_methods_details_caption,payment_methods_details_isrequired 
								FROM 
									payment_methods_details 
								WHERE 
									payment_methods_paymethod_id = $sel_method_id";
				$ret_det = $db->query($sql_det);
				if ($db->num_rows($ret_det))
				{
					while ($row_det = $db->fetch_array($ret_det))
					{
						$val ='';
						// Check whether any values exists for current detail for current site
						$sql_site = "SELECT payment_methods_forsites_details_values 
										FROM 
											payment_methods_forsites_details 
										WHERE 
											payment_methods_details_payment_method_details_id=".$row_det['payment_method_details_id']." 
											AND sites_site_id=$ecom_siteid 
										LIMIT 
											1";
						$ret_site = $db->query($sql_site);
						if ($db->num_rows($ret_site))
						{
							$row_site = $db->fetch_array($ret_site);
							$val = stripslashes($row_site['payment_methods_forsites_details_values']);
						}				
						if($row_det['payment_methods_details_isrequired']==1) 
						{	
							$req_caption 	= '<span class="redtext">&nbsp;*</span>';	
							$req_field[] 	= 'det_'.$row_det['payment_method_details_id'];
							$req_name[] 	= stripslashes($row_det['payment_methods_details_caption']);
						}	
			?>
						<tr>
							<td width="25%" align="left" class="tdcolorgray"><?php echo stripslashes($row_det['payment_methods_details_caption']); echo $req_caption?>
							</td>
							<td align="left">
							<input type="text" name="det_<?php echo $row_det['payment_method_details_id']?>" id="det_<?php echo $row_det['payment_method_details_id']?>" value="<?php echo $val?>" /> 
							</td>
						</tr>
			<?php
					}
				}
				if (count($req_field))
				{
					$req_field_name= "'".implode("','",$req_field)."'";
					$req_field_msg = "'".implode("','",$req_name)."'";
				}
				else
				{
					$req_field_name = '';
					$req_field_msg  = '';
				}
				if($row_method['paymethod_key']=='GOOGLE_CHECKOUT') // show this following only in case of google checkout
				{
			?>
					<tr>
						<td align="left" colspan="2" class="tdcolorgray">
						<input type="radio" name="payment_method_google_recommended" id="payment_method_google_recommended" value="1" <?php echo ($row_method['payment_method_google_recommended']==1)?'checked="checked"':''?> />&nbsp;Store transaction details in your console. <span class="redtext">NOT Recommended by Google.</span>
						</td>
						</tr>
						<tr>
						<td align="left" colspan="2" class="tdcolorgray">
						<input type="radio" name="payment_method_google_recommended" id="payment_method_google_recommended" value="0" <?php echo ($row_method['payment_method_google_recommended']==0)?'checked="checked"':''?>/>&nbsp;Do NOT store transaction details in your console. <span class="redtext">(Recommended by Google. You will have to login to Google's order processing section to manage orders.)</span>
						<input type="hidden" name="google_type" value="1" />
						</td>
					</tr>
			<?php
				}
			?>
			
			</table>
			</div>
		  </td>
		</tr>
		<tr>
		  <td colspan="3" align="right" valign="middle">
		  <div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			<td align="right" valign="middle">
			<input type="submit" name="submit_values" value="Save" class="red" />
			</td>
			</tr>
			</table>
		</div>
		</td>
		</tr>
		</table>
	</form>
	<script language="javascript" type="text/javascript">
	function valform(frm)
	{
		fieldRequired 		= Array(<?php echo $req_field_name?>);
		fieldDescription 	= Array(<?php echo $req_field_msg?>);
		fieldEmail 			= Array();
		fieldConfirm 		= Array();
		fieldConfirmDesc  	= Array();
		fieldNumeric 		= Array();
		fieldSpecChars 		= Array();
		fieldCharDesc 		= Array();
		if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)) {
			show_processing();
			return true;
		} else {
			return false;
		}
	}
	</script>