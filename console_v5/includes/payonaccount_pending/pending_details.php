<?php
/*#################################################################
# Script Name 	: pending_details.php
# Description 		: Page for showing the payonaccount pending details
# Created by 		: Sny
# Created on		: 23-Oct-2008	
# Modified by 		: 
# Modified on		: 
#################################################################*/
//Define constants for this page
$page_type	= 'Pay on Account Pending Details ';
$help_msg 		= get_help_messages('LIST_PAYONACCOUNT_PENDING_MESS1');

$custsql 		= "SELECT customer_title, customer_fname, customer_mname, customer_surname, customer_buildingname, customer_streetname, customer_towncity, customer_statecounty,
							customer_phone, customer_mobile, customer_postcode, customer_email_7503,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,country_id  
						FROM 
							customers 
						WHERE 
							customer_id = $customer_id 
							AND sites_site_id = $ecom_siteid  
						LIMIT 
							1";
$custres 		= $db->query($custsql);
if($db->num_rows($custres))
{
	$custrow 		= $db->fetch_array($custres);
	$custname 		= stripslashes($custrow['customer_title'])." ".stripslashes($custrow['customer_fname'])." ".stripslashes($custrow['customer_mname'])." ".stripslashes($custrow['customer_surname']);	
	$country		= $custrow['country_id'];
	$sql_country 	= "SELECT country_name 
								FROM 
									general_settings_site_country 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND country_id = $country 
								LIMIT 
									1";
	$ret_country = $db->query($sql_country);
	if ($db->num_rows($ret_country))
	{
		$row_country 		= $db->fetch_array($ret_country);
		$country_name		= stripslashes($row_country['country_name']);
	}
}
$paysql			= "SELECT DATE_FORMAT(pay_date,'%d %b %Y') AS pay_date, pay_amount,pay_additional_details,pay_paystatus,
						pay_paymenttype, pay_paymentmethod, pay_incomplete  
					FROM 
						order_payonaccount_pending_details 
					WHERE 
						pendingpay_id='".$pending_id."' 
					LIMIT 
						1";
$payres 			= $db->query($paysql);
if($db->num_rows($payres) and $db->num_rows($custres))
{
	$payrow 		= $db->fetch_array($payres);
	$ptype			= getpaymenttype_Name($payrow['pay_paymenttype']);
	$pstat			= getpaymentstatus_Name($payrow['pay_paystatus']);
	if($payrow['pay_paymentmethod']!='')
		$pmethod		= getpaymentmethod_Name($payrow['pay_paymentmethod']);
}
else
{
	echo "Sorry!! Invalid Input";	
	exit;
}	


?>
<script language="javascript">
	function approve(val) 
	{
		if(document.frm_payonpendingaccount.cbo_paymethod.value=='')
		{
			alert('Please select the method by which the payment collected');
			return false;
		}
		if(confirm('Are you sure you want to approve this transaction?\n\n  Note: This actions is not reversible.'))
		{
			frm = document.frm_payonpendingaccount;
			frm.fpurpose.value = 'pay_approve';
			frm.paytype.value = val;		
			frm.submit();
		}	
	}
function goback_order()
{
	document.frm_payonpendingaccount.fpurpose.value='';
	document.frm_payonpendingaccount.submit();
}</script>
<form name="frm_payonpendingaccount" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="payonaccount_pending" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />
<input type="hidden" name="paytype" />
<input type="hidden" name="pending_id" value="<?=$pending_id?>" />
<input type="hidden" name="customer_id" value="<?=$customer_id?>" /> 
<input type="hidden" name="sel_pay_type" value="<?=$_REQUEST['sel_pay_type']?>" /> 
<input type="hidden" name="txt_name" value="<?=$_REQUEST['txt_name']?>" /> 
<input type="hidden" name="pay_fromdate" value="<?=$_REQUEST['pay_fromdate']?>" />
<input type="hidden" name="pay_todate" value="<?=$_REQUEST['pay_todate']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" /> 
<input type="hidden" name="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="chk_incomplete_pend" value="<?=$_REQUEST['chk_incomplete_pend']?>" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="#" onclick="goback_order()">List Pay on Account Pending Details </a><span> Account Pending Details of <?=$custname?></span></div></td>
    </tr>
	 <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
	<?php 
		if($alert)
			{			
	?>
        		<tr>
          			<td align="center" valign="middle" class="errormsg"><?php echo($alert);?></td>
          		</tr>
    <?php
		 	}
	?> 
    <tr>
      <td align="left"><div class="editarea_div">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1" >
            
              <tr >
                <td  class="listingtablestyleB"><strong>Credit Limit </strong></td>
                <td  class="listingtablestyleB"><strong><?php echo display_price($custrow['customer_payonaccount_maxlimit'])?></strong></td>
                <td align="left" class="listingtablestyleB"><strong>Current Account Balance </strong></td>
                <td width="46%"  align="left" class="listingtablestyleB"><strong><?php echo display_price($custrow['customer_payonaccount_usedlimit'])?></strong></td>
                </tr>
              <tr >
                <td colspan="4" align="left" valign="top">&nbsp;</td>
                </tr>
              <tr >
              <td width="13%" align="left" valign="top" class="listingtablestyleB"><strong>Customer</strong></td>
              <td width="21%" align="left" valign="top" class="listingtablestyleB"><?=$custname?>             </td>
              <td width="20%" align="left" valign="top" class="listingtablestyleB"><strong>Email Id </strong></td>
              <td align="left" valign="top" class="listingtablestyleB"><?php echo stripslashes($custrow['customer_email_7503'])?></td>
              </tr>
            <tr>
              <td align="left" valign="top"  class="listingtablestyleB"><strong>Building</strong></td>
              <td align="left" valign="top"  class="listingtablestyleB"><?php echo stripslashes($custrow['customer_buildingname'])?></td>
              <td align="left" valign="top" class="listingtablestyleB"><strong>Street</strong></td>
              <td colspan="3"  align="left" valign="top" class="listingtablestyleB"><?php echo stripslashes($custrow['customer_streetname'])?></td>
              </tr>
            <tr>
              <td align="left" valign="top"  class="listingtablestyleB"><strong>City</strong></td>
              <td align="left" valign="top"  class="listingtablestyleB"><?php echo stripslashes($custrow['customer_towncity'])?></td>
              <td align="left" valign="top" class="listingtablestyleB"><strong>State</strong></td>
              <td align="left" valign="top" class="listingtablestyleB"><?php echo stripslashes($custrow['customer_statecounty'])?></td>
            </tr>
            <tr>
              <td align="left" valign="top"  class="listingtablestyleB"><strong>Country</strong></td>
              <td align="left" valign="top"  class="listingtablestyleB"><?php echo stripslashes($country_name)?></td>
              <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
              <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" valign="top"  class="listingtablestyleB"><strong>Fax</strong></td>
              <td align="left" valign="top"  class="listingtablestyleB"><?php echo stripslashes($custrow['customer_phone'])?></td>
              <td align="left" valign="top" class="listingtablestyleB"><strong>Phone No </strong></td>
              <td align="left" valign="top" class="listingtablestyleB"><?php echo stripslashes($custrow['customer_phone'])?></td>
              </tr>
			     <tr >
                <td colspan="4" align="left" valign="top">&nbsp;</td>
              </tr>
			   <tr>
              <td  class="listingtablestyleB"><strong>Transaction Date</strong></td>
              <td  class="listingtablestyleB"><?=$payrow['pay_date'] ?></td>
              <td align="left" class="listingtablestyleB"><strong>Transaction Amount</strong></td>
              <td align="left" class="listingtablestyleB"><?=display_price($payrow['pay_amount']) ?></td>
              </tr>
			  <tr>
                <td  class="listingtablestyleB"><strong>Payment Type </strong></td>
                <td  class="listingtablestyleB"><?=$ptype ?></td>
                <td align="left" valign="top" class="listingtablestyleB"><strong>Payment Status </strong></td>
                <td   align="left" valign="top" class="listingtablestyleB"><?=$pstat ?><?php if ($payrow['pay_incomplete']==1) echo ' <span style="color:#FF0000">(Incomplete)</span>';?></td>
              </tr>
              <tr>
              <td  class="listingtablestyleB">
			  <?php 
			  	if($pmethod!='')
				{
				?>
			  <strong>Payment Method </strong>
			  <?php
			  }
			  ?>			  </td>
              <td  class="listingtablestyleB"><?=$pmethod ?></td>
              <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
              <td   align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
              </tr>
			  <?php
			  	if(trim($payrow['pay_additional_details'])!='')
				{
			  ?>
              <tr>
              <td  class="listingtablestyleB">			  </td>
              <td  class="listingtablestyleB"></td>
              <td align="left" valign="top" class="listingtablestyleB"><strong>Additional Description</strong></td>
              <td   align="left" valign="top" class="listingtablestyleB"><?=nl2br(stripslashes($payrow['pay_additional_details'])) ?></td>
              </tr>
			 <?php
			 	}
			 ?> 
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
              </tr>
			  <?PHP
			  if($payrow['pay_paymentmethod'] == 'PROTX' or $payrow['pay_paymentmethod'] == 'SELF')
			  {
			  $paytype = '';
				$cr_sql = "SELECT payment_id, card_type, card_number, name_on_card, sec_code, expiry_date_m, expiry_date_y, issue_number,
									issue_date_m, issue_date_y, card_encrypted 
										FROM order_payonaccount_pending_details_payment 
											 WHERE order_payonaccount_pendingpay_id='".$pending_id."'";
				$cr_res = $db->query($cr_sql);
				$cr_num = $db->num_rows($cr_res);
				
				if($cr_num > 0) 
				{
					$paytype = 'Creditcart';
			?>	 
            <tr>
             
			  <td colspan="4" valign="top"><table width="100%" border="0">
                <tr>
                  <td colspan="2" class="listingtableheader"><strong>Credit Card Details </strong></td>
                  </tr>
			 <?PHP
			 	$cr_row = $db->fetch_array($cr_res);
				if($cr_row['card_encrypted']==1)
						 $cc = base64_decode(base64_decode($cr_row['card_number']));
					else
						$cc = $cr_row['card_number'];
							/*$len	= (strlen($cc)-4);
							$cc 	= substr($cc,-4);
							for($i=0;$i<$len;$i++)
							{
								$ccs .='x';
							}
							$cc		= $ccs.$cc;*/
			 ?>
                <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Card Type</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?=$cr_row['card_type']?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Name on Card</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?=$cr_row['name_on_card']?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Card Number</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?=$cc?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Security Code</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?=$cr_row['sec_code']?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Expiry Date	</strong></td>
                  <td width="58%" class="listingtablestyleA"><?php echo $cr_row['expiry_date_m'].'/'.$cr_row['expiry_date_y']?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Issue Number</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?=$cr_row['issue_number']?></td>
                </tr>
				 <tr>
                  <td width="42%" class="listingtablestyleA">&nbsp;<strong>Issue Date</strong>	</td>
                  <td width="58%" class="listingtablestyleA"><?php echo $cr_row['issue_date_m'].'/'.$cr_row['issue_date_y']?></td>
                </tr>
              </table></td>
			  </tr>
			 <?PHP
			 		}
			 	}
				$ch_sql = "SELECT cheque_date, cheque_number, cheque_bankname, cheque_branchdetails 
										FROM order_payonaccount_pending_details_cheque_details 
											 WHERE order_payonaccount_pending_details_pending_id='".$pending_id."'";
				$ch_res = $db->query($ch_sql);
				$ch_num = $db->num_rows($ch_res);
				if($ch_num > 0) 
				{
					$paytype = 'Cheque';
			 ?>
				 <tr>
				  <td colspan="4" valign="top">
				  <table width="100%" border="0">
					<tr>
					  <td colspan="4" class="listingtableheader"><strong>Cheque Details </strong></td>
					  </tr>
					<tr>
					  <td width="21%" class="listingtablestyleB"><div align="center"><strong>Cheque Date</strong></div></td>
					  <td width="24%" class="listingtablestyleB"><div align="center"><strong>Cheque Number</strong></div></td>
					  <td width="26%" class="listingtablestyleB"><div align="center"><strong>Bank Name</strong></div></td>
					  <td width="29%" class="listingtablestyleB"><div align="center"><strong>Branch Details</strong></div></td>
					</tr>
					<? while($row = $db->fetch_array($ch_res)) { ?>
					<tr>
					  <td class="listingtablestyleA" ><div align="center"><?=$row['cheque_date']?></div></td>
					  <td class="listingtablestyleA"><div align="center"><?=$row['cheque_number']?></div></td>
					  <td class="listingtablestyleA"><div align="center"><?=$row['cheque_bankname']?></div></td>
					  <td class="listingtablestyleA"><div align="center"><?=$row['cheque_branchdetails']?></div></td>
					</tr>
					<? }  ?>
				  </table></td>
				  </tr>
           <? } ?>
            

          </table></td>
          </tr>
		  <? if($payrow['pay_amount']<=$custrow['customer_payonaccount_usedlimit']){?>
		   <tr>
              <td  valign="top" colspan="4">
			  <table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
							<td colspan="2" align="left" valign="top" class="listingtableheader">Please fill in the following details to approve the transaction </td>
						    </tr>
							<tr>
							  <td width="29%" align="left" class="listingtablestyleB">Payment Method <span class="redtext">*</span></td>
							  <td width="71%" align="left" class="listingtablestyleB"><select name="cbo_paymethod" id="cbopaymethod">
								<option value="">-- Select --</option>
								<option value="CARD">Credit Card</option>
								<option value="CHEQUE">Cheque / DD</option>
								<option value="BANK">Bank Transfer</option>
								<option value="PHONE">Pay on Phone</option>
								<option value="CASH" >Cash</option>
								<option value="OTHER">Other</option>
							  </select></td>
							</tr>
							<tr>
							<td class="listingtablestyleB" align="left" valign="top" width="29%" >Additional details (optional) </td>
							<td align="left"  class="listingtablestyleB" width="71%"><textarea name="pay_additional_details" id="pay_additional_details" cols="30" rows="5"></textarea>							  </td>
							</tr>
							<tr>
							  <td align="center" valign="top" class="listingtablestyleB" >&nbsp;</td>
			                  <td align="center" valign="top" class="listingtablestyleB" >&nbsp;</td>
				</tr>
							<tr>
							  <td align="center" valign="top" class="listingtablestyleB" >&nbsp;</td>
			                  <td align="left" valign="top" class="listingtablestyleB" ><input type="button" name="Submit" value=" Approve Payment " onclick="approve('<?=$paytype?>')" class="red" /></td>
				</tr>
		      </table>			  </td>
          </tr>
		  <? }
		  else
		  {
		   ?> 
		   <tr>
              <td  valign="top" colspan="4"  align="center">&nbsp;
			  </td>
			  </tr>
			  <tr>
              <td  valign="top" colspan="4"  align="center">&nbsp;
			  </td>
			  </tr>
		   <tr>
              <td  valign="top" colspan="4" class="homecontentusertabletdA" align="center"> --This transaction cannot be processed since the account balance is only <? echo display_price($custrow['customer_payonaccount_usedlimit'])?> --
			  </td>
			  </tr>
		   <?  
		  }?>
		   <tr>
		     <td  valign="top"><div align="center"></div></td>
	      </tr>
		   <tr>
		     <td  valign="top">&nbsp;</td>
	      </tr>
      </table></div>	  </td>
    </tr>
  </table>
</form>
