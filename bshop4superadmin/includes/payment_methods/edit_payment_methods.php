<?php
/*#################################################################
# Script Name 	: edit_payment_methods.php
# Description 	: Page for editing Payment methods
# Coded by 		: ANU
# Created on	: 1-June-2007
# Modified by	: ANU
# Modified On	: 07 April 08
#################################################################
*/
//#Define constants for this page
$page_type = 'Payment Methods';
$help_msg = 'This section helps in editing the values for a Payment Method.';

//#Sql
$sql_payment_method 	= "SELECT paymethod_id,paymethod_secured_req,paymethod_name,paymethod_key,paymethod_description,
												paymethod_takecarddetails,payment_minvalue,paymethod_ssl_imagelink,payment_hide,paymethod_showinvoucher,
												paymethod_showinsetup,paymethod_showinpayoncredit,paymethod_showinmobile  
											FROM 
												payment_methods 
											WHERE 
												paymethod_id='".add_slash($_REQUEST['paymethod_id'])."'";
$res_payment_method 	= $db->query($sql_payment_method);
$row 							= $db->fetch_array($res_payment_method);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired 		= Array('paymethod_name','paymethod_key');
	fieldDescription 	= Array('Payment Method Name','Payment Method Key');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  = Array();
	fieldNumeric	 	= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) 
	{
		return true;
	}
	 else
	 {
		return false;
	}
}
</script>
<form name='frmEditPaymentMethod' action='home.php?request=payment_methods' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=payment_methods&pay_method=<?=$_REQUEST['pay_method']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Payment Methods </a> <font size="1">>></font> <strong>Edit <?=$page_type?></strong></td>
      </tr>
      
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2" valign="top" >
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td colspan="2" align="right" class="fontblacknormal"><table width="100%" border="0">
                    <tr class="">
                      <td width="46%" align="right" class="fontblacknormal">Payment Method  name</td>
                      <td width="7%" align="center">:</td>
                      <td width="47%" align="left"><input name="paymethod_name" type="text" id="paymethod_name" value="<?=$row['paymethod_name']?>" size="30" />
                          <span class="redtext">*</span></td>
                    </tr>
                    <tr class="">
                      <td align="right" class="fontblacknormal">Payment Method Key </td>
                      <td align="center">:</td>
                      <td align="left"><input name="paymethod_key" type="text" id="paymethod_key" value="<?=$row['paymethod_key']?>" size="30" />
                          <span class="redtext">*</span></td>
                    </tr>
                    <tr class="">
                      <td align="right" class="fontblacknormal">Payment Method Description </td>
                      <td align="center">:</td>
                      <td align="left"><textarea name="paymethod_description" cols="27" rows="7"><?=$row['paymethod_description']?>
                    </textarea></td>
                    </tr>
                    <tr class="">
                      <td align="right" class="fontblacknormal">Payment Method Take card details </td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_takecarddetails" id="paymethod_takecarddetails" value="1" <?php echo($row['paymethod_takecarddetails']==1)?'checked':'';?>/></td>
                    </tr>
					 <?php /*<tr class="">
                      <td align="right" class="fontblacknormal">Payment Minimum value</td>
                      <td align="center">:</td>
                      <td align="left"><input name="payment_minvalue" type="text" id="payment_minvalue" value="<?=$row['payment_minvalue']?>" size="30" /></td>
                    </tr>
                    */?>
                    <!--<tr class="">
                      <td align="right" class="fontblacknormal">Payment Method SSL Image Link </td>
                      <td align="center">:</td>
                      <td align="left"><input name="paymethod_ssl_imagelink" type="text" id="paymethod_ssl_imagelink" value="<?//=$row['paymethod_ssl_imagelink']?>" size="30" /></td>
                    </tr>-->
                    <tr>
                      <td align="right" class="fontblacknormal">Payment Method Hide </td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_hide" id="paymethod_hide" value="1" <?php echo($row['payment_hide']==1)?'checked':'';?>/></td>
                    </tr>
                    <tr>
                      <td align="right">Payment Method Show in Voucher</td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_showinvoucher" id="paymethod_showinvoucher" value="1" <?php echo($row['paymethod_showinvoucher']==1)?'checked':'';?>/></td>
                    </tr>
                     <tr>
                       <td align="right">Payment Method Show in Setupwizard </td>
                       <td align="center">:</td>
                       <td align="left"><input type="checkbox" name="paymethod_showinsetup" id="paymethod_showinsetup" value="1" <?php echo($row['paymethod_showinsetup']==1)?'checked':'';?>/></td>
                     </tr>
                     <tr>
                      <td align="right">Secured Area Required</td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_secured_req" id="paymethod_secured_req" value="1" <?php echo($row['paymethod_secured_req']==1)?'checked':'';?>/></td>
                    </tr>
					<tr class="">
                      <td align="right" class="fontblacknormal">Payment Method show in pay on credit </td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_showinpayoncredit" id="  	paymethod_showinpayoncredit" value="1" <?php echo($row['paymethod_showinpayoncredit']==1)?'checked':'';?>/></td>
                    </tr>
                    <tr>
                      <td align="right">Show in Mobile?</td>
                      <td align="center">:</td>
                      <td align="left"><input type="checkbox" name="paymethod_showinmobile" id="paymethod_showinmobile" value="1" <?php echo($row['paymethod_showinmobile']==1)?'checked':'';?>/></td>
                    </tr>
                    <tr>
                      <td colspan="3">&nbsp;</td>
                    </tr>
                  </table></td>
			      <td width="27%" align="right"  valign="top"><table width="100%" border="0" class="maininnertabletd1">
                    <tr>
                      <td colspan="2" align="left" class="maininnertabletd3">Details added for this Payment Method </td>
                    </tr>
                    <?php $sql_payment_method_details = "SELECT payment_method_details_id,payment_methods_paymethod_id,payment_methods_details_caption FROM payment_methods_details WHERE payment_methods_paymethod_id ='".add_slash($_REQUEST['paymethod_id'])."'";
					$res = $db->query($sql_payment_method_details); 
					 if (mysql_num_rows($res)) {
					 $cnt_details = 0;
						 while($row = $db->fetch_array($res)) { 
						 $cnt_details ++;
						?>
						<tr>
						<td align="left">&nbsp;<?=$cnt_details;?></td>
						<td align="left"><a href="home.php?request=payment_methods&fpurpose=edit_paymethod_details&payment_method_details_id=<?=$row['payment_method_details_id']?>&pass_paymethod_id=<?=$_REQUEST['paymethod_id']?>&pass_pay_method=<?=$_REQUEST['pay_method']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_pg=<?=$_REQUEST['pg']?>" title="Edit"><?php echo stripslashes($row['payment_methods_details_caption']); ?></a><?php //echo stripslashes($row['payment_methods_details_caption']); ?></td>
						</tr>
						
						<?php 
							}
					}?>
					<tr>
						  <td align="left">&nbsp;</td>
						  <td align="right"><a href="home.php?request=payment_methods&fpurpose=add_paymethod_details&pass_paymethod_id=<?=$_REQUEST['paymethod_id']?>&pass_pay_method=<?=$_REQUEST['pay_method']?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_pg=<?=$_REQUEST['pg']?>" title="Add New Details">Add new Details</a></td>
				    </tr>
                  </table></td>
			  </tr>
				<tr align="center">
				<td width="30%">&nbsp;</td>
				<td align="left">
				<input type="hidden" name="paymethod_id" id="paymethod_id" value="<?=$_REQUEST['paymethod_id']?>" />
				<input type="hidden" name="pay_method" id="pay_method" value="<?=$_REQUEST['pay_method']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
				<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button">				</td>
				<td align="left">&nbsp;</td>
				<td align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>