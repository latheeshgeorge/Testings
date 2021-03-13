<?PHP
	function show_giftvoucher_main($edit_id,$alert='')
	{
	global $db,$ecom_siteid,$ecom_hostname;
	$voucher_id = $edit_id;
	$sql_gift = "SELECT *, date_format(voucher_activatedon,'%d-%m-%Y') startd, date_format(voucher_expireson,'%d-%m-%Y') endd 
				FROM gift_vouchers 
					WHERE voucher_id=".$voucher_id;
	$ret_gift = $db->query($sql_gift);
	if($db->num_rows($ret_gift))
	{
		$row_gift = $db->fetch_array($ret_gift);
	}
?>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="fieldtable">
<?php
			if($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" id="mainerror_div" >
          <?=$alert?></td>
    	</tr>
		 <?php
		 	}
		 ?>
         <tr>
           <td colspan="4" align="left" valign="top"  >
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="15%" align="left" class="tdcolorgray">Voucher Number</td>
               <td align="left" valign="top" class="tdcolorgray"><?php echo $row_gift['voucher_number'];?></td>
               <td width="15%" align="left" class="tdcolorgray">Created on</td>
               <td align="left" valign="top" class="tdcolorgray"><?php echo dateFormat($row_gift['voucher_boughton'],'');?>
               <?php
               		if($row_gift['voucher_createdby']=='C') // case if created by
               		{
               			echo '(Customer)';
               		}
               	?>               </td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray" >Payment Status</td>
               <td align="left" valign="top" class="tdcolorgray">
			   <?php echo getpaymentstatus_Name($row_gift['voucher_paystatus'],'');
			   if($row_gift['voucher_incomplete']==1)
				echo  ' <span style="color:#FF0000">(Incomplete)</span>';
			   ?></td>
              <td align="left" class="tdcolorgray">Activated on * </td>
               <td align="left" valign="top" class="tdcolorgray">
               <?php
               	if($row_gift['voucher_paystatus']=='Paid' or $row_gift['voucher_paystatus']=='REFUNDED')
               	{
               		if($row_gift['voucher_createdby']=='A') // case if created by admin
               		{
               ?>
		               <input name="voucher_activatedon" type="text" id="voucher_activatedon" value="<?php echo $row_gift['startd']?>" size="10"/>&nbsp;&nbsp;<a style="vertical-align:bottom;" href="javascript:show_calendar('frmEditGiftVoucher.voucher_activatedon');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>&nbsp;
					   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_STDATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	               <?php
               		}
               		else
               		{
               			echo $row_gift['startd'];
						?>
						<input type="hidden" name="voucher_activatedon" id="voucher_activatedon" value="<? echo $row_gift['startd']?>" />
						<?
               		}
               	}
               	else
               	{
               		echo '<span class="redtext">-- Not Activated yet -- </span>';
               	}
               ?>               </td>
             </tr>
             <?php
             	if($row_gift['voucher_paymentmethod']!='')
             	{
             ?>
             <tr>
               <td align="left" class="tdcolorgray">Payment Method</td>
               <td align="left" valign="top" class="tdcolorgray"><?php echo getpaymentmethod_Name($row_gift['voucher_paymentmethod']);?></td>
               <td align="left" class="tdcolorgray" colspan="2">&nbsp;</td>
             </tr>
             <?php
             	}
             ?>

            <tr>
			 <td align="left" class="tdcolorgray"><?PHP if($row_gift['voucher_createdby']!='A') { ?>Payment Type <? } ?></td>
               <td align="left" valign="top" class="tdcolorgray"><?php 
			   if($row_gift['voucher_createdby']!='A') { 
			   			echo getpaymenttype_Name($row_gift['voucher_paymenttype']);
			   		} ?></td>
               
               <td align="left" class="tdcolorgray">Expires On * </td>
               <td align="left" valign="top" class="tdcolorgray">
               <?php
               	if($row_gift['voucher_paystatus']=='Paid' or $row_gift['voucher_paystatus']=='REFUNDED')
               	{
               ?>
	              <input name="voucher_expireson" type="text" id="voucher_expireson" value="<?php echo $row_gift['endd']?>" size="10"/>
	               &nbsp;<a style="vertical-align:bottom;" href="javascript:show_calendar('frmEditGiftVoucher.voucher_expireson');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
				   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_ENDDATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	           <?php
               	}
               	else
               	{
               		echo '<span class="redtext">-- Not Activated yet-- </span>';
               	}
               	?>               </td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">Voucher Type </td>
               <td align="left" class="tdcolorgray">
                 <select name="voucher_type" id="voucher_type" onchange="handle_codetype(this.value)">
                   <option value="val" <?php echo ($row_gift['voucher_type']=='val')?'selected':''?>>Value</option>
                   <option value="per" <?php echo ($row_gift['voucher_type']=='per')?'selected':''?>>%</option>
                 </select>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left" class="tdcolorgray">Hide</td>
               <td align="left" class="tdcolorgray"><input type="radio" name="voucher_hide" value="1" <?php echo ($row_gift['voucher_hide']==1)?'checked="checked"':''?> />
			Yes
  <input name="voucher_hide" type="radio" value="0" <?php echo ($row_gift['voucher_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFT_VOUCH_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr id="tr_discval">
               <td align="left" class="tdcolorgray">
               <?php
               	If ($row_gift['voucher_type']=='val')
               	{
            	 	echo "Discount (".$row_gift['voucher_curr_symbol'].")";
				}
               	else
               	{
               		echo "Discount (%)";
               	}
				?> <span class="redtext">*</span>                </td>
               <td align="left" class="tdcolorgray"><input name="voucher_value" type="text" size="8" value="<?php echo $row_gift['voucher_value']?>" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_DISVALUE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

               <td align="left" class="tdcolorgray">Maximum Usage *</td>
               <td align="left" class="tdcolorgray"><input name="voucher_max_usage" type="text" id="voucher_max_usage" value="<?php echo $row_gift['voucher_max_usage']?>" size="3"/>
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_NO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			   <div style="float:right; padding-right:5px;padding-bottom:3px">
              <input type="button" name="printerfriendly_Submit" value="Printer Friendly?" class="red" onclick="window.open('includes/gift_voucher/print_gift_voucher.php?voucherid=<?PHP echo $voucher_id; ?>','popvoucher','height=600,width=600,scrollbars=yes,resizable=yes')" />
			  </div></td>
             </tr>
             <tr>
               <td colspan="4" align="right">&nbsp;</td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_login_touse" value="1" <?php echo ($row_gift['voucher_login_touse']==1)?'checked="checked"':''?> /></td>
               <td align="left" colspan="2">Users must be logged in to use this voucher (tick box for yes)
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_LOG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_freedelivery" value="1"  <?php echo ($row_gift['voucher_freedelivery']==1)?'checked="checked"':''?>/></td>
               <td align="left" colspan="2">Allow Free Delivery&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ALLOW_FREE_DELIVERY_GIFT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
              <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_apply_direct_discount_also" value="1"  <?php echo ($row_gift['voucher_apply_direct_discount_also']=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="2" >Apply Customer Direct Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_apply_custgroup_discount_also" value="1"  <?php echo ($row_gift['voucher_apply_custgroup_discount_also']=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="2" >Allow Customer Group Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
			<tr>
               <td align="right">&nbsp;</td>
               <td align="right"><input type="checkbox" name="voucher_apply_direct_product_discount_also" value="1"  <?php echo ($row_gift['voucher_apply_direct_product_discount_also']=='Y')?'checked="checked"':''?>/></td>
               <td align="left" colspan="2" >Allow Product Direct Discount also?&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
           </table></td>
         </tr>
		</table></div> 
		<div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">	
				<?php
					if ($row_gift['voucher_paystatus']!='REFUNDED')
					{
				  ?>
					<input name="prod_Submit" type="submit" class="red" value="Save" />
				  <?php
					}
					else
						echo '<span class="redtext">-- Voucher details cannot be modified since the payment status is Refunded --</span>';
				  ?>
				</td>
			</tr>
		</table>
		</div>
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products to which voucher is linked when called using ajax;
	// ###############################################################################################################
	function show_product_list($editid,$alert='')
	{
		global $db,$ecom_siteid;
	    // Get the list of assigned products
		$sql_prod = "SELECT a.product_id,a.product_name FROM products a,gift_voucher_assignedtoproducts b
					 WHERE b.gift_vouchers_voucher_id=$editid AND a.product_id=b.product_id ORDER BY a.product_name";
		$ret_prod = $db->query($sql_prod);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prod))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditGiftVoucher,\'checkboxprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditGiftVoucher,\'checkboxprod[]\')"/>','Slno.','Product Name');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions);
							$cnt = 1;
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
						<tr>
								<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprod[]" value="<?php echo $row_prod['product_id'];?>" /></td>
								<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
								<td align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_prod['product_name']);?></td>
								</tr>
					   <?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="prod_norec" id="prod_norec" value="1" />
								  Not Assigned to any products.</td>
								</tr>
						<?php
						}
						?>
				</table>
	<?php
	}
	function show_order_list($voucherid,$alert='')
	{
		global $db,$ecom_siteid;
			// Get the voucher number related to current voucher id
			$sql_voucher = "SELECT voucher_number FROM gift_vouchers WHERE voucher_id=$voucherid";
			$ret_voucher = $db->query($sql_voucher);
			if ($db->num_rows($ret_voucher))
			{
				$row_voucher 	= $db->fetch_array($ret_voucher);
				$vnum			= stripslashes($row_voucher['voucher_number']);
			}
			 // Check whether any order has been placed with the current voucher number
			$sql_order= "SELECT order_id ,order_date,order_totalprice,order_custtitle,order_custfname,order_custmname,order_custsurname,order_status 
									FROM 
										orders 
									WHERE 
										gift_vouchers_voucher_id=$voucherid
										AND order_status NOT IN ('CANCELLED','NOT_AUTH')  
										AND order_gift_voucher_number ='".$vnum."' 
										AND sites_site_id=$ecom_siteid 
									ORDER BY 
										order_date 
									DESC";
			$ret_order = $db->query($sql_order);
	?>				<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_ORDERS')?></div></td>
        </tr>
					<?php
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				   <?php
				 		}
						if ($db->num_rows($ret_order))
						{
							$table_headers = array('Slno.','Order Id','Order Date','Customer name','Order Total','Order Status');
							$header_positions=array('center','center','center','left','right','left');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions);
							$cnt = 1;
							while ($row_order = $db->fetch_array($ret_order))
							{
								$date = dateFormat($row_order['order_date'],'');
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>

								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="center" class="<?php echo $cls?>"><a class="edittextlink" href="home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo stripslashes($row_order['order_id']);?>" title="Click to view the order details"><?php echo stripslashes($row_order['order_id']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($date);?></td>
									<td align="left" class="<?php echo $cls?>"><?php echo stripslashes($row_order['order_custtitle']).".".stripslashes($row_order['order_custfname'])." ".stripslashes($row_order['order_custmname'])." ".stripslashes($row_order['order_custsurname']);?></td>
									<td align="right" class="<?php echo $cls?>"><?php echo display_price($row_order['order_totalprice']);?></td>
									<td align="left" class="<?php echo $cls?>"><?php echo getorderstatus_Name($row_order['order_status']);?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="order_norec" id="order_norec" value="1" />
								  No Orders found.</td>
								</tr>
						<?php
						}
						?>
				</table></div>
	<?php
	}
	/* Function to hold the display logic to show the details of customer who bought this voucher */
	function show_customer_details($voucher_id)
	{
		global $db,$ecom_siteid;
		$sql_vouchcust = "SELECT voucher_toname,voucher_toemail,voucher_tomessage,voucher_title,voucher_fname,
									voucher_mname,voucher_surname,voucher_buildingno,voucher_street,
									voucher_city,voucher_state,voucher_country,voucher_zip,voucher_phone,
									voucher_mobile,voucher_company,voucher_fax,voucher_email,voucher_note
							FROM
								gift_vouchers_customer
							WHERE
								voucher_id = $voucher_id
							LIMIT
								1";
		$ret_vouchcust = $db->query($sql_vouchcust);
		if ($db->num_rows($ret_vouchcust))
		{
			$row_vouchcust = $db->fetch_array($ret_vouchcust);
	?>		<div class="editarea_div">
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_CUSTOMERS')?></div></td>
        </tr>
			<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
					</tr>
		 <?php
		 		}
		 			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB'
		 ?>
				<tr>
				<td align="left" width="15%" class="subcaption listingtablestyleB">Name</td>
				<td align="left" width="25%" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_title']).stripslashes($row_vouchcust['voucher_fname']).' '.stripslashes($row_vouchcust['voucher_mname']).' '.stripslashes($row_vouchcust['voucher_surname'])?></td>
				<td align="left" width="15%" class="subcaption listingtablestyleB">Building No</td>
				<td align="left" width="25%" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_buildingno'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleA">Street</td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_street'])?></td>
				<td align="left" class="subcaption listingtablestyleA">City</td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_city'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleB">State</td>
				<td align="left" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_state'])?></td>
				<td align="left" class="subcaption listingtablestyleB">Country</td>
				<td align="left" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_country'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleA">Post Code</td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_zip'])?></td>
				<td align="left" class="subcaption listingtablestyleA"><strong>Phone</strong></td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_phone'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleB">Mobile</td>
				<td align="left" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_mobile'])?></td>
				<td align="left" class="subcaption listingtablestyleB">Fax</td>
				<td align="left" class="listingtablestyleB"><?php echo stripslashes($row_vouchcust['voucher_fax'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleA">Company Name</td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_company'])?></td>
				<td align="left" class="subcaption listingtablestyleA">Email Id</td>
				<td align="left" class="listingtablestyleA"><?php echo stripslashes($row_vouchcust['voucher_email'])?></td>
				</tr>
				<tr>
				<td align="left" class="subcaption listingtablestyleB" valign="top">Note</td>
				<td align="left" colspan="3" class="listingtablestyleB" valign="top"><?php echo nl2br(stripslashes($row_vouchcust['voucher_note']))?></td>
				</tr>
				</table>
				</div>
	<?php
		}
	}

// ###############################################################################################################
// 				Function which holds the display logic of payment details to be shown when called using ajax;
// ###############################################################################################################
function show_payment_details($voucher_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	
   $sql_vouch = "SELECT voucher_paystatus,voucher_value,voucher_createdby,voucher_value,voucher_refundamt,
  						voucher_paystatus_manuallychanged,voucher_paystatus_manuallychanged_on,voucher_paystatus_manuallychanged_by,
						  	voucher_paystatus_changed_manually_paytype,voucher_paymentmethod,voucher_paymenttype,voucher_incomplete 
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch = $db->fetch_array($ret_vouch);
	}
	$pay_str = $pay_usr = '';
		if($row_vouch['voucher_paystatus_manuallychanged']==1) // case if payment status changed manually
		{
			$pay_usr = getConsoleUserName($row_vouch['voucher_paystatus_manuallychanged_by']).' ( on '.dateFormat($row_vouch['voucher_paystatus_manuallychanged_on'],'datetime').')';
			$pay_str =  "Payment Status Changed By  ";
		}
	?>
	<div class="editarea_div">
	<table width="100%" cellpadding="1" cellspacing="1" border="0">
			<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="2" align="center" class="errormsg">
				<?php
				if($alert==1)
				echo 'Payment Status Changed Successfully';
				else if($alert==2)
				echo 'Payment Status Changed Successfully. Reason added as note';
				else
				echo 'Sorry!! Voucher already in selected status';
				?>
				</td>
				</tr>
		<?php
		}
		 
		?>	
				<tr>
		  <td width="60%" align="left" valign="top">
		  
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
			
			<tr>
 			<td align="left" valign="middle" class="listingtablestyleB" width="30%" ><strong>Payment Status </strong></td>
           <td  align="left" valign="middle" class="listingtablestyleB"><div id="paymentstatus_maindiv">
		   <?php echo  getpaymentstatus_Name($row_vouch['voucher_paystatus']);
		   if($row_vouch['voucher_incomplete']==1)
				echo  ' <span style="color:#FF0000">(Incomplete)</span>';
		     if($row_vouch['voucher_paystatus_manuallychanged']==1) // case if payment status changed manually
			{
				if($row_vouch['voucher_paystatus_changed_manually_paytype']!='')
					echo  ' ('.ucwords(strtolower($row_vouch['voucher_paystatus_changed_manually_paytype'])).')';
			}		
		   ?> </div>
	       </td>			 </tr>
			<?
				$show_paystat = false;
			// If status array have values then show the payment status change section
			// Check whether the payment is deferred or preauth or authenticate
			if($row_vouch['voucher_paystatus']=='DEFERRED' or $row_vouch['voucher_paystatus']=='PREAUTH' or  $row_vouch['voucher_paystatus']=='AUTHENTICATE')
			{

			}
			elseif($row_vouch['voucher_paystatus']!='Paid' and $row_vouch['voucher_paystatus']!='Pay_Failed' and $row_vouch['voucher_paystatus']!='REFUNDED' and $row_vouch['voucher_paystatus']!='CANCELLED')
			{
				$show_paystat = true;
			}
			if ($pay_str!='' or $show_paystat)
			{
			?>			
				 <tr>
					<td class="listingtablestyleB"><strong><?
				if($pay_str!='')
				{
					echo $pay_str;
				}
				// Check whether the payment is deferred or preauth or authenticate
				if($show_paystat)
				{
					$voucherpaystat_array = array(
												''=>'-- Select --',
												'Paid'=>'Payment Received',
												'Pay_Failed'=>'Payment Failed'
											);
									echo "<div id='paystatus1_div' style='display:".$disp."'>Change Payment Status</div>"; 
				 }
					// If status array have values then show the payment status change section
					?></strong></td>
					  <td align="left" class="listingtablestyleB">
			   <?php
					if($pay_usr!='')
					{
					 echo $pay_usr;
					}
					echo generateselectbox('cbo_voucherpaystatus',$voucherpaystat_array,'','','call_ajax_showlistall("operation_changevoucherpaystatus_sel")');				
	?>				</td>
					</tr>
<?php
			}
?>				
			<tr>
				<td align="left" valign="middle" class="listingtablestyleB" width="30%" ><strong>Payment Type </strong></td>
			   <td  align="left" valign="middle" class="listingtablestyleB">	<?php echo getpaymenttype_Name($row_vouch['voucher_paymenttype'])?>
			   </td>
			 </tr>  
		<?php
			if($row_vouch['voucher_paymentmethod']!='')
			{
		?>
			<tr>
				<td align="left" valign="middle" class="listingtablestyleB" width="30%" ><strong>Payment Method </strong></td>
			   <td  align="left" valign="middle" class="listingtablestyleB">	<?php echo getpaymentmethod_Name($row_vouch['voucher_paymentmethod'])?>
			   </td>
			 </tr>  
		<?php	
			}
		?>
		    </table>
		  </td>
		  <td width="40%" align="left" valign="top">
			  <!--<div style="float:left; padding-right:5px;padding-bottom:3px">
              <input type="button" name="printerfriendly_Submit" value="Printer Friendly?" class="red" onclick="window.open('includes/gift_voucher/print_gift_voucher.php?voucherid=<?PHP echo $voucher_id; ?>','popvoucher','height=600,width=600,scrollbars=yes,resizable=yes')" />
			  </div>-->
		  <?php

		 
			   // case of payment method is protx and the capture type is not NORMAL
			   if($row_vouch['voucher_paystatus']=='DEFERRED' or $row_vouch['voucher_paystatus']=='PREAUTH' or  $row_vouch['voucher_paystatus']=='AUTHENTICATE')
			   { 
			   	if($row_vouch['voucher_paystatus'] == 'DEFERRED')
			   	{
			   		$show_curnote		= 'To Release Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>';
			   		$buttoncaption		= 'Release';
			   		$pass_mod			= 'RELEASE';
					?>
							<div id="capturetypereleasemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
								<input name="release_button" type="button" value="<?php echo $buttoncaption?>" class="red" onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);"/>
								<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</div>
					<?php
			   	}
			   	elseif($row_vouch['voucher_paystatus'] == 'AUTHENTICATE') {
			   		$show_curnote	= 'To Authorise this AUTHENTICATED Transaction Please use this button. You can authorise an amount upto 115% of the Voucher Amount <br><br> This action is not Reversible.<br>';
			   		$buttoncaption	= 'Authorise';
			   		$pass_mod		= 'AUTHORISE';
					?>
						<div id="capturetypeauthorisemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
						<input type="button" name="auth_button" value="<?php echo $buttoncaption?>" class="red"  onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);" />
						<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
					<?php
			   	}
			   	elseif($row_vouch['voucher_paystatus'] == 'PREAUTH')
			   	{
			   		$buttoncaption		= 'Repeat';
			   		$pass_mod			= 'REPEAT';
			   		$show_curnote= 'To Repeat Preauth Transaction Please use this button. <br><br> This action is not Reversible.<br>';
					?>
							<div id="capturetyperepeatmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
								<input name="release_button" type="button" value="<?php echo $buttoncaption?>" class="red" onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);"/>
								<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</div>
					<?php
			   	}
			   	// Handling the case of Abort or Cancel butttons
			   	if($row_vouch['voucher_paystatus']=='DEFERRED')
			   	{
			   		$show_curnote= 'To Abort Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>';
				?>
						<div id="capturetypeabortmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
				  			<input name="abort_button" type="button" value="Abort" class="red"  onclick="call_ajax_showlistall('ABORT');"/>
							<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
				<?php
			   	}
			   	else if($row_vouch['voucher_paystatus'] == 'AUTHENTICATE')
			   	{
			   		$show_curnote= 'To Cancel Authenticated Transaction Please use this button. <br><br> This action is not Reversible.<br>';
				?>
						<div id="capturetypecancelmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
							<input name="cancel_button" type="button" value="Cancel" class="red"  onclick="call_ajax_showlistall('CANCEL');"/>
							<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
				<?php
			   	}
			   }
				?>
		  </td>
		</tr>
		<tr>
		  <td align="left" valign="top"><div id="additionaldet_div"></div></td>
		  </tr>
		  <?php
		  if($change_main_status==1) // following are used to pass back certains values to make some decision in ajax_return_contents function in order details page
		  {
		  ?>
		  		<input type="hidden" name="pass_change_paystat" id="pass_change_paystat" value="<?php echo getpaymentstatus_Name($row_vouch['voucher_paystatus'])?>" />
		<?php
		  }
		?>
				</table>
	<?
	// Get the voucher details from gift_vouchers table inorder to show the price in the currency selected by
	// the customer
	$sql_vouch = "SELECT voucher_curr_rate,voucher_curr_symbol,voucher_paymenttype,
							voucher_paymentmethod,voucher_paystatus,voucher_paystatus_manuallychanged,
							voucher_paystatus_manuallychanged_by,voucher_paystatus_manuallychanged_on
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch = $db->fetch_array($ret_vouch);
	}
	if($row_vouch['voucher_paymenttype']=='credit_card') // case of payment is by credit card
	{
	     $sql_pay = "SELECT *
									FROM
										gift_vouchers_payment
									WHERE
										gift_vouchers_voucher_id = $voucher_id
									LIMIT 1";
					$ret_pay = $db->query($sql_pay);
		if ($row_vouch['voucher_paymentmethod']=='SELF' or $row_vouch['voucher_paymentmethod']=='PROTX' or $row_vouch['voucher_paymentmethod']=='PROTX_VSP') // If method is self or protx direct or protx vsp
		{
	?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_PAYMENTS')?></div></td>
        </tr>
				<tr>
				<td align="left" colspan="2">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php
					// Check whether any record exists for current order in order_payment_main table
					
					if ($db->num_rows($ret_pay))
					{
						$row_pay= $db->fetch_array($ret_pay);
					?>
						<tr>
							<td align="left" colspan="2" class="shoppingcartheader">
							Credit Card Details
							</td>
						</tr>
					<?php
					$srno=1;
					if ($row_pay['card_type']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Card Type</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
									<?php echo $row_pay['card_type']?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['name_on_card']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Name on Card</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['name_on_card'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['card_number']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
						if($row_pay['card_encrypted']==1)
						$cc = base64_decode(base64_decode($row_pay['card_number']));
						else
						$cc = $row_pay['card_number'];
						if($row_vouch['voucher_paystatus']=='Paid')
						{
							$len	= (strlen($cc)-4);
							$cc 	= substr($cc,-4);
							for($i=0;$i<$len;$i++)
							{
								$ccs .='x';
							}
							$cc		= $ccs.$cc;
						}
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Card Number</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($cc)?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['sec_code']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Security Code</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['sec_code'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['expiry_date_m']!=0 and $row_pay['expiry_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Expiry Date</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['expiry_date_m'].'/'.$row_pay['expiry_date_y']?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['issue_number']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Issue Number</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['issue_number']?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['issue_date_m']!=0 and $row_pay['issue_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Issue Date</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['issue_date_m'].'/'.$row_pay['issue_date_y']?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['vendorTxCode']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Vendor TxCode</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['vendorTxCode'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['protStatus']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Protx Status</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['protStatus'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['protStatusDetail']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Protx Status Detail</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['protStatusDetail'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['vPSTxId']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>VPS TxId</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['vPSTxId'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['securityKey']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Security Key</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['securityKey'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['txAuthNo']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Tx Auth No</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['txAuthNo'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['txType']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Tx Type</strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['txType'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['avscv2']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>AVSCV2 </strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['avscv2'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['cavv']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>CAVV </strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['cavv'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['3dsecurestatus']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>3dsecure Status </strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['3dsecurestatus'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['pareq']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>PAREQ </strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['pareq'])?>
								</td>
							</tr>
					<?php
					}
					if ($row_pay['md']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>MD </strong>
								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['md'])?>
								</td>
							</tr>
					<?php
					}
				}
					?>
					</table>
				</td>
			</tr>
			</table>
	<?php
		}
                elseif($row_vouch['voucher_paymentmethod']=='PAYPAL_EXPRESS' or $row_vouch['voucher_paymentmethod']=='PAYPALPRO')
                {
                    // Get the details given back to us from paypal
                    $sql_paypal = "SELECT paypal_transactions_id, paypal_transaction_type, paypal_payment_type,
                                                            paypal_ordertime, paypal_amt, paypal_currency_code, paypal_feeamt, 
                                                            paypal_settleamt, paypal_taxamt, paypal_exchange_rate, paypal_paymentstatus,
                                                            paypal_pending_reason, paypal_reasoncode,paypal_avscode, paypal_cvv2match,paypal_VPAS 
                                                    FROM 
                                                            gift_voucher_payment_paypal 
                                                    WHERE 
                                                            gift_vouchers_voucher_id = $voucher_id  
                                                            AND sites_site_id = $ecom_siteid
                                                    LIMIT 
                                                            1";
                    $ret_paypal = $db->query($sql_paypal);
                    if($db->num_rows($ret_paypal))
                    {
                        $row_paypal = $db->fetch_array($ret_paypal);
                    ?>
                            <table width="60%" cellpadding="1" cellspacing="1" border="0">
                            <tr>
                                <td align="left" colspan="2" class="shoppingcartheader">
                                Payment Gateway Return Details</td>
                            </tr>
                            <?php
                            if($row_paypal['paypal_transactions_id']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Unique transaction ID of the payment</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_transactions_id']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_transaction_type']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Transaction Type</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_transaction_type']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_payment_type']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Payment Type</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_payment_type']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_ordertime']!='')
                            {
                                    $tarr = explode('T',$row_paypal['paypal_ordertime']);
                                    $datarr = explode('-',$tarr[0]);
                                    $showdate = $datarr[2].'-'.$datarr[1].'-'.$datarr[0];
                                    $timearr = explode('Z',$tarr[1]);
                                    $showdate .= ' '.$timearr[0];
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Order Time</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $showdate?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_amt']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Final Amount</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_amt']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_currency_code']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Currency Code</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_currency_code']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_feeamt']!='' and $row_paypal['paypal_feeamt']>0)
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>PayPal fee amount charged for the transaction</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_feeamt']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_settleamt']!='' and $row_paypal['paypal_settleamt']>0)
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Amount deposited in your PayPal account after a currency conversion</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_settleamt']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_taxamt']!='' and $row_paypal['paypal_taxamt']>0)
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Tax charged on the transaction</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_taxamt']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_exchange_rate']!='' and $row_paypal['paypal_exchange_rate']>0)
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Exchange rate if a currency conversion occurred</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_exchange_rate']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_paymentstatus']!='')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>Status of the payment</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_paymentstatus']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_pending_reason']!='' and strtolower($row_paypal['paypal_pending_reason'])!='none')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>The reason the payment is pending</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_pending_reason']?></td>
                                </tr>
                            <?php
                            }
                            if($row_paypal['paypal_reasoncode']!='' and strtolower($row_paypal['paypal_reasoncode'])!='none')
                            {
                            ?>
                                <tr>
                                    <td align="left" width="45%" class="listingtablestyleB">
                                    <strong>The reason code</strong></td>
                                    <td align="left" class="listingtablestyleB">
                                    <?php echo $row_paypal['paypal_reasoncode']?></td>
                                </tr>
                            <?php
                            }
							if($row_paypal['paypal_avscode']!='' and strtolower($row_paypal['paypal_avscode'])!='none')
							{
							?>
								<tr>
									<td align="left" width="45%" class="listingtablestyleB">
									<strong>AVS code</strong></td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_paypal['paypal_avscode']?></td>
								</tr>
							<?php
							}
							if($row_paypal['paypal_cvv2match']!='' and strtolower($row_paypal['paypal_cvv2match'])!='none')
							{
							?>
								<tr>
									<td align="left" width="45%" class="listingtablestyleB">
									<strong>CVV2 Match</strong></td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_paypal['paypal_cvv2match']?></td>
								</tr>
							<?php
							}
							if($row_paypal['paypal_cvv2match']!='' and strtolower($row_paypal['paypal_cvv2match'])!='none')
							{
							?>
								<tr>
									<td align="left" width="45%" class="listingtablestyleB" valign="top">
									<strong>Correlation ID</strong></td>
									<td align="left" class="listingtablestyleB">
									<?php echo $row_paypal['paypal_VPAS']?></td>
								</tr>
							<?php
							}
				?>
				</table>
                    <?php
                    }
        }
		elseif ($row_vouch['voucher_paymentmethod']=='BARCLAYCARD') // case if payment method is BARCLAYCARD
		{
			// Get the details given back to us from paypal
			$sql_barclay = "SELECT currency, amount, pm,
								acceptance, status, cardno, ed, 
								cn, trxdate, payid, ncerror,
								brand, complus,ip, pay_type 
							FROM 
								order_payment_barclaycard 
							WHERE 
								orders_order_id = $voucher_id 
								AND sites_site_id = $ecom_siteid 
								AND pay_type ='Voucher' 
							LIMIT 
								1";
			$ret_barclay = $db->query($sql_barclay);
			if($db->num_rows($ret_barclay))
			{
				$row_barclay = $db->fetch_array($ret_barclay);
		?>
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					Barclaycard Gateway Return Details</td>
				</tr>
				<?php
				if($row_barclay['currency']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Order currency</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['currency']?></td>
					</tr>
				<?php
				}
				if($row_barclay['amount']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Order amount</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['amount']?></td>
					</tr>
				<?php
				}
				if($row_barclay['pm']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Payment method</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['pm']?></td>
					</tr>
				<?php
				}
				if($row_barclay['acceptance']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Acceptance code returned by acquirer</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['acceptance']?></td>
					</tr>
				<?php
				}
				if($row_barclay['status']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Transaction status</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['status']?></td>
					</tr>
				<?php
				}
				if($row_barclay['cardno']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Masked card number</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['cardno']?></td>
					</tr>
				<?php
				}
				if($row_barclay['ed']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Expiry date</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['ed']?></td>
					</tr>
				<?php
				}
				if($row_barclay['cn']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Cardholder / Customer name</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['cn']?></td>
					</tr>
				<?php
				}
				if($row_barclay['trxdate']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Transaction date</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['trxdate']?></td>
					</tr>
				<?php
				}
				if($row_barclay['payid']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Payment reference in Barclaycard system</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['payid']?></td>
					</tr>
				<?php
				}
				if($row_barclay['brand']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Card brand</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['brand']?></td>
					</tr>
				<?php
				}
				if($row_barclay['pay_type']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB">
						<strong>Payment Type</strong></td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_barclay['pay_type']?></td>
					</tr>
				<?php
				}
				?>
				</table>
		<?php
			}
		}		
		else // case of paymethod other than self or protx
		{
			if ($db->num_rows($ret_pay))
			{
				$row_pay= $db->fetch_array($ret_pay);
				$caption = '';
				switch($row_vouch['voucher_paymentmethod'])
				{
					case 'WORLD_PAY':
						$caption 	= 'World Pay Transaction Id';
						$cap_val	= $row_pay['worldpay_transid'];
					break;
					case 'HSBC':
						$caption 	= 'HSBC CPI resultcode';
						$cap_val	= $row_pay['hsbc_cpiresultcode'];
					break;
				};
				if ($caption!='')
				{
			?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
						<td align="left" colspan="2" class="shoppingcartheader">
						Payment Gateway Return Details
						</td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong><?php echo $caption?></strong>
						</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $cap_val?>
						</td>
					</tr>
					</table>
			<?php
				}
				if($row_vouch['voucher_paymentmethod'] == 'NOCHEX')
				{
				?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
						<td align="left" colspan="2" class="shoppingcartheader">
						NoChex Return Details</td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Transaction Id</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_transaction_id']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Transaction Date</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_transaction_date']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Voucher Id</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_order_id']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Amount</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_amount']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Your Email</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_to_email']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Customer Email</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_from_email']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Security Key</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_security_key']?></td>
					</tr>
					<tr>
						<td align="left" width="25%" class="listingtablestyleB">
						<strong>Status</strong>		</td>
						<td align="left" class="listingtablestyleB">
						<?php echo $row_pay['voucher_nochex_status']?></td>
					</tr>
					</table>
			<?php
				}
				if($row_vouch['voucher_paymentmethod'] == 'REALEX')
				{
				?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					RealEx Return Details</td>
				</tr>
				<?php 
				if($row_pay['voucher_realex_timestamp'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Time Stamp</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_timestamp'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_result'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Result</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_result'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_orderid'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Order Id</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_orderid'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_message'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Message</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_message'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_authcode'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Auth Code</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_authcode'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_passref'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Pass Ref</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_passref'])?></td>
				</tr>
				<?php
				}
				if($row_pay['voucher_realex_md5hash'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB">
					<strong>Hash</strong>		</td>
					<td align="left" class="listingtablestyleB">
					<?php echo stripslashes($row_pay['voucher_realex_md5hash'])?></td>
				</tr>
				<?php
				}
				?>
				</table>
			<?php
				}
			}	
		}
	}
	elseif ($row_vouch['voucher_paymenttype']=='cheque')
	{
		// Get the cheque details from gift_voucher_cheque_details table
		$sql_cheque = "SELECT cheque_date,cheque_number,cheque_bankname,cheque_branchdetails
                                    FROM
                                        gift_vouchers_cheque_details
                                    WHERE
                                        gift_vouchers_voucher_id = $voucher_id
                                    LIMIT
                                        1";
		$ret_cheque = $db->query($sql_cheque);
		if($db->num_rows($ret_cheque))
		{
			$row_cheque = $db->fetch_array($ret_cheque);
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		?>
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
					<td align="left" colspan="4" class="shoppingcartheader">
					Cheque Details
					</td>
				</tr>
				<tr>
					<td align="left" width="15%" class="listingtablestyleB" valign="top">
					<strong>Date of Cheque</strong>
					</td>
					<td align="left" class="listingtablestyleB" valign="top">
					<?php echo stripslashes($row_cheque['cheque_date'])?>
					</td>
					<td align="left" width="15%" class="listingtablestyleB" valign="top">
					<strong>Cheque Number</strong>
					</td>
					<td align="left"  class="listingtablestyleB" valign="top">
					<?php echo stripslashes($row_cheque['cheque_number'])?>
					</td>
				</tr>
				<tr>
					<td align="left" class="listingtablestyleA" valign="top">
					<strong>Bank Name</strong>
					</td>
					<td align="left" class="listingtablestyleA" valign="top">
					<?php echo stripslashes($row_cheque['cheque_bankname'])?>
					</td>
					<td align="left" class="listingtablestyleA" valign="top">
					<strong>Branch Details</strong>
					</td>
					<td align="left" class="listingtablestyleA" valign="top">
					<?php echo nl2br(stripslashes($row_cheque['cheque_branchdetails']))?>
					</td>
				</tr>
				</table>
		<?php
		}
	}
	?>
	</div>
	<?php
}
// ###############################################################################################################
// 	Function which holds the display logic of emails related to voucher to be shown when called using ajax;
// ###############################################################################################################
function show_voucher_emails($voucher_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the list of email related to current order
	$sql_email = "SELECT email_id,email_to,email_subject,email_headers,
                            email_type,email_was_disabled,email_sendonce,email_lastsenddate
                        FROM
                            gift_voucher_emails
                        WHERE
                            gift_vouchers_voucher_id = $voucher_id
                        ORDER BY
                            email_id";
	$ret_email = $db->query($sql_email);
	if ($db->num_rows($ret_email))
	{
		//$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_orderdetails,\'checkbox_email[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_orderdetails,\'checkbox_email[]\')"/>','#','Type','Subject','Mail Disabled?','Send Atleast Once');
		$table_headers 		= array('#','Type','Subject','Mail Disabled?','Send Atleast Once','Details');
		$header_positions	= array('center','left','left','center','center','center',);
		$colspan 			= count($table_headers);
	?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_EMAILS')?></div></td>
        </tr>
		<?php
		if($alert)
		{
		?>
                    <tr>
                        <td colspan="<?php echo $colspan?>" align="center" class="errormsg">
                        <?php
                        echo $alert;
                        ?>
                        </td>
                    </tr>
		<?php
		}
		?>
		<?php
		$srno = 1;
		echo table_header($table_headers,$header_positions);
		while ($row_email = $db->fetch_array($ret_email))
		{
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';

		?>
				<tr>
                                <td width="2%" align="center" class="<?php echo $cls?>">
                                <?php echo $srno++?>.
                                </td>
                                <td width="30%" align="left" class="<?php echo $cls?>">
                                <?php echo getorderEmailname($row_email['email_type'])?>
                                </td>
                                <td width="30%" align="left" class="<?php echo $cls?>">
                                <?php echo stripslashes($row_email['email_subject'])?>
                                </td>
                                <td width="12%" align="center" class="<?php echo $cls?>">
                                <?php echo ($row_email['email_was_disabled']==1)?'Y':'N'?>
                                </td>
                                <td width="15%" align="center" class="<?php echo $cls?>">
                                <?php echo ($row_email['email_sendonce']==1)?'Y':'N'?>
                                </td>
                                <td width="15%" align="center" class="<?php echo $cls?>">
                                <div id='<?php echo $row_email['email_id']?>_div' onclick="handle_showdetailsdiv('<?php echo $row_email['email_id']?>_tr','<?php echo $row_email['email_id']?>_div')" title="Click here" style="cursor:pointer">Details<img src="images/right_arr.gif" /></div>
                                </td>
				</tr>
				<tr id="<?php echo $row_email['email_id']?>_tr" <?php echo ($_REQUEST['emailid']!=$row_email['email_id'])?'style="display:none"':''?>>
                                <td colspan="<?php echo $colspan?>" align="center" valign="top">
                                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                <td width="62%" align="left" valign="top">
                                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                <td colspan="2" align="left" class="shoppingcartheader">Email Details </td>
                                </tr>
                                <tr>
                                <td width="21%" align="left" valign="top" class="subcaption">Email To </td>
                                <td width="79%" align="left" valign="top">
                                <?php
                                $to_arr = explode(',',$row_email['email_to']);
                                for($i=0;$i<count($to_arr);$i++)
                                {
                                    if($i!=0)
                                    echo '<br/>';
                                    echo $to_arr[$i];
                                }
                                ?>
                                </td>
                                </tr>
                                <tr>
                                <td align="left" class="subcaption listingtablestyleB">Subject</td>
                                <td align="left" class="listingtablestyleB"><?php echo $row_email['email_subject']?></td>
                                </tr>
                                <tr>
                                <td align="left" class="subcaption listingtablestyleB"><div id='<?php echo $row_email['email_id']?>_messagediv' onclick="handle_showmessageiv('<?php echo $row_email['email_id']?>_messagetr','<?php echo $row_email['email_id']?>_messagediv')" title="Click here" style="cursor:pointer">Message<img src="images/right_arr.gif" /></div></td>
                                <td align="right" class="listingtablestyleB"><input type="button" name="Mail_Send_button2" value="Send This Mail" class="red" onclick="resend_orderemail('<?php echo $row_email['email_id']?>')"/></td>
                                </tr>
                                <tr id="<?php echo $row_email['email_id']?>_messagetr" style="display:none">
                                <td colspan="2" align="left">
                                    <div class="emaildiv_cls">
									<?php //echo $row_email['email_message']?>
									<?php //echo $row_email['email_message']
						  				echo read_email_from_file('vouch',$row_email['email_id']);
									?>
									</div>
                                    </td>
                                </tr>
                                <tr>
                                <td colspan="2" align="left">&nbsp;</td>
                                </tr>
                                <tr id="<?php echo $row_email['email_id']?>_messagetr_down" style="display:none">
                                    <td colspan="2" align="right"><input type="button" name="Mail_Send_button" value="Send This Mail" class="red" onclick="resend_orderemail('<?php echo $row_email['email_id']?>')" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">&nbsp;</td>
                                </tr>
                                </table>
                                </td>
                                <td width="38%" align="left" valign="top">
                                <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td colspan="3" class="shoppingcartheader" align="left">Email ReSend History </td>
                                </tr>
                                <?php
                                $table_headers_1 		= array('#','Send Date','Send By');
                                $header_positions_1	= array('center','center','left');
                                echo table_header($table_headers_1,$header_positions_1);
                                // Get the resend history for emails
                                $sql_resend = "SELECT send_id,send_date,send_by
                                                                        FROM
                                                                                gift_voucher_emails_console_send
                                                                        WHERE
                                                                                email_id = ".$row_email['email_id']."
                                                                        ORDER BY
                                                                                send_date
                                                                                        DESC";
                                $ret_resend = $db->query($sql_resend);

                                if($db->num_rows($ret_resend))
                                {
                                        $srnlo = 1;
                                        while ($row_resend = $db->fetch_array($ret_resend))
                                        {
                                                $cls = ($srnlo%2==0)?'listingtablestyleB':'listingtablestyleB';
                                        ?>
                                                        <tr>
                                                                <td width="5%" class="<?php echo $cls?>"><?php echo $srnlo++?></td>
                                                                <td width="40%" class="<?php echo $cls?>"><?php echo dateFormat($row_resend['send_date'],'datetime')?></td>
                                                                <td width="55%" align="left" class="<?php echo $cls?>">
                                                                <?php
                                                                $sql_user = "SELECT sites_site_id,user_title,user_fname,user_lname
                                                                                                        FROM
                                                                                                                sites_users_7584
                                                                                                        WHERE
                                                                                                                user_id = ".$row_resend['send_by']."
                                                                                                        LIMIT
                                                                                                                1";
                                                                $ret_user = $db->query($sql_user);
                                                                if ($db->num_rows($ret_user))
                                                                {
                                                                        $row_user = $db->fetch_array($ret_user);
                                                                        if($row_user['sites_site_id']==0) // case of super admin
                                                                        {
                                                                                echo stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
                                                                        }
                                                                        else
                                                                        {
                                                                                echo stripslashes($row_user['user_title']).'.'.stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
                                                                        }
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                        <?php
                                        }
                                }
                                else
                                {
                                        ?>
                                                        <tr>
                                                                <td colspan="3" align="center">No Details found.</td>
                                                        </tr>

                                        <?php
                                }
                                ?>
                                </table>
                            </td>
                            </tr>
                        </table></td>
                        </tr>
		<?php

		}
		?>
		</table></div>
	<?php
	}
	else
	{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		<td align="center" class="redtext">No Emails found.
		</td>
		</tr>
	<?php
	}
}
// ###############################################################################################################
// Function which holds the display logic of operations on selected voucher;
// ###############################################################################################################
function show_operations($voucher_id,$alert='',$change_main_status=0)
{
	global $db,$ecom_siteid;
	// Get the current status of current voucher
	$sql_vouch = "SELECT voucher_paystatus,voucher_value,voucher_createdby,voucher_value,voucher_refundamt
						FROM
							gift_vouchers
						WHERE
							voucher_id = $voucher_id
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch = $db->fetch_array($ret_vouch);
	}
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_OPERATIONS')?></div></td>
        </tr>
		<?php
		if($alert)
		{
		?>
		<tr>
		  <td colspan="2" align="center" class="errormsg" id="operation_errordiv">
		  	<?php echo $alert?>
		  </td>
		 </tr>
		 <?php
		}
		 ?>
		<tr>
		  <td width="57%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
			<?php
			// Check whether the payment is deferred or preauth or authenticate
			if($row_vouch['voucher_paystatus']=='DEFERRED' or $row_vouch['voucher_paystatus']=='PREAUTH' or  $row_vouch['voucher_paystatus']=='AUTHENTICATE')
			{

			}
			elseif($row_vouch['voucher_paystatus']!='Paid' and $row_vouch['voucher_paystatus']!='Pay_Failed' and $row_vouch['voucher_paystatus']!='REFUNDED' and $row_vouch['voucher_paystatus']!='CANCELLED')
			{
				$voucherpaystat_array = array(
											''=>'-- Select --',
											'Paid'=>'Payment Received',
											'Pay_Failed'=>'Payment Failed'
										);
			}
			// If status array have values then show the payment status change section
			if (count($voucherpaystat_array))
			{
?>
				<tr>
				  <td>Change Payment Status </td>
				  <td align="left">
<?php
			 	 echo generateselectbox('cbo_voucherpaystatus',$voucherpaystat_array,'','','call_ajax_showlistall("operation_changevoucherpaystatus_sel")');
?>
				</td>
				</tr>
		<?php
			}
		?>
		    </table>
		  </td>
		  <td width="43%" align="left" valign="top">
			  <!--<div style="float:left; padding-right:5px;padding-bottom:3px">
              <input type="button" name="printerfriendly_Submit" value="Printer Friendly?" class="red" onclick="window.open('includes/gift_voucher/print_gift_voucher.php?voucherid=<?PHP echo $voucher_id; ?>','popvoucher','height=600,width=600,scrollbars=yes,resizable=yes')" />
			  </div>-->
		  <?php

		  // Allow to refund even if the order status is cancelled until order_totalprice is > order_refundamt
		  if ($row_vouch['voucher_value']>0) // case if deposit amount exists
		  {
		  	$check_tot_amt = $row_vouch['voucher_value'];

		  }
		  if($row_vouch['voucher_paystatus']=='Paid' and  $row_vouch['voucher_createdby']!='A' and ($check_tot_amt>$row_vouch['voucher_refundamt'])) // Show the refund button only if payment is successfulll
		  	{
				?>
						<div id="refundtop_div" style="float:left; padding-right:5px;padding-bottom:3px">
						<input name="refund_Submit" type="button" class="red" id="refund_Submit" value="Refund?" onclick="call_ajax_showlistall('operation_refund_sel')" />
						</div>
				<?php
		  	}
			   // case of payment method is protx and the capture type is not NORMAL
			   if($row_vouch['voucher_paystatus']=='DEFERRED' or $row_vouch['voucher_paystatus']=='PREAUTH' or  $row_vouch['voucher_paystatus']=='AUTHENTICATE')
			   {
			   	if($row_vouch['voucher_paystatus'] == 'DEFERRED')
			   	{
			   		$show_curnote		= 'To Release Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>';
			   		$buttoncaption		= 'Release';
			   		$pass_mod			= 'RELEASE';
					?>
							<div id="capturetypereleasemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
								<input name="release_button" type="button" value="<?php echo $buttoncaption?>" class="red" onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);"/>
								<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</div>
					<?php
			   	}
			   	elseif($row_vouch['voucher_paystatus'] == 'AUTHENTICATE') {
			   		$show_curnote	= 'To Authorise this AUTHENTICATED Transaction Please use this button. You can authorise an amount upto 115% of the Voucher Amount <br><br> This action is not Reversible.<br>';
			   		$buttoncaption	= 'Authorise';
			   		$pass_mod		= 'AUTHORISE';
					?>
						<div id="capturetypeauthorisemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
						<input type="button" name="auth_button" value="<?php echo $buttoncaption?>" class="red"  onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);" />
						<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
					<?php
			   	}
			   	elseif($row_vouch['voucher_paystatus'] == 'PREAUTH')
			   	{
			   		$buttoncaption		= 'Repeat';
			   		$pass_mod			= 'REPEAT';
			   		$show_curnote= 'To Repeat Preauth Transaction Please use this button. <br><br> This action is not Reversible.<br>';
					?>
							<div id="capturetyperepeatmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
								<input name="release_button" type="button" value="<?php echo $buttoncaption?>" class="red" onclick="call_ajax_showlistall('<?php echo $pass_mod?>',0);"/>
								<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							</div>
					<?php
			   	}
			   	// Handling the case of Abort or Cancel butttons
			   	if($row_vouch['voucher_paystatus']=='DEFERRED')
			   	{
			   		$show_curnote= 'To Abort Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>';
				?>
						<div id="capturetypeabortmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
				  			<input name="abort_button" type="button" value="Abort" class="red"  onclick="call_ajax_showlistall('ABORT');"/>
							<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
				<?php
			   	}
			   	else if($row_vouch['voucher_paystatus'] == 'AUTHENTICATE')
			   	{
			   		$show_curnote= 'To Cancel Authenticated Transaction Please use this button. <br><br> This action is not Reversible.<br>';
				?>
						<div id="capturetypecancelmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
							<input name="cancel_button" type="button" value="Cancel" class="red"  onclick="call_ajax_showlistall('CANCEL');"/>
							<a href="#" onmouseover ="ddrivetip('<?php echo $show_curnote?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>
				<?php
			   	}
			   }
				?>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" align="right" valign="top">
	      </td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="top"><div id="additionaldet_div"></div></td>
		  </tr>
		  <?php
		  if($change_main_status==1) // following are used to pass back certains values to make some decision in ajax_return_contents function in order details page
		  {
		  ?>
		  		<input type="hidden" name="pass_change_paystat" id="pass_change_paystat" value="<?php echo getpaymentstatus_Name($row_vouch['voucher_paystatus'])?>" />
		<?php
		  }
		?>
</table>
	<?php
}
function voucher_additionaldet_paymentstatus($status)
{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" align="left" class="shoppingcartheader">Reason (optional)</td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="top"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="top">Reason (if specified) will be automatically added to the notes section</td>
		  </tr>
		<tr>
		  <td width="35%" align="left" valign="top">&nbsp;</td>
		  <td width="65%" align="left" valign="top"><input name="Change_paystat" type="button" class="red" id="Change_paystat" value="Click here to Change the Payment status" onclick="validate_changepaystatus('<?=$status?>')" /></td>
		</tr>
	  </table>
<?php
}
// ###############################################################################################################
// Function which holds the display logic of notes added for vouchers to be shown when called using ajax;
// ###############################################################################################################
function show_voucher_notes($voucher_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1" class="fieldtable">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_NOTES')?></div></td>
        </tr>
		<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="3" align="center" class="errormsg">
				<?php
				echo $alert;
				?>
				</td>
				</tr>
		<?php
		}
		?>
		<tr>
		<td align="left" class="shoppingcartheader">Existing Notes</td>
		<td colspan="2" align="left" class="shoppingcartheader">Add Notes</td>
		</tr>
		<tr>
		<td width="49%" align="left" valign="top">
		<?php
		// Get all the notes added for this order
		$sql_notes = "SELECT note_id,note_add_date,user_id,note_text,note_type 
							FROM
								gift_voucher_notes
							WHERE
								gift_vouchers_voucher_id = $voucher_id
							ORDER BY
								note_add_date
									DESC";
		$ret_notes = $db->query($sql_notes);
		if($db->num_rows($ret_notes))
		{
		?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<?php
				while ($row_notes = $db->fetch_array($ret_notes))
				{
					$extra = get_voucher_status_number_to_text($row_notes['note_type']);
				?>
						<tr>
						<td width="96%" align="left" class="listingitallicstyleB"><?php echo dateFormat($row_notes['note_add_date'],'datetime').' '.$extra?></td>
						<td width="4%" align="center" class="listingitallicstyleB"><a href="javascript:delete_note('<?php echo $row_notes['note_id']?>')" title="Delete Note"><img src="images/del.gif" width="16" height="16" border="0" /></a></td>
						</tr>
						<tr>
						<td colspan="2" align="left" class="tdcolorgray_normal"><?php echo nl2br(stripslashes($row_notes['note_text']))?></td>
						</tr>
						<tr>
						<td colspan="2" align="right" valign="top" class="listingitallicstyleA">
						<?php
						// Find the name of user who added the note
						$sql_user = "SELECT user_title,user_fname,user_lname,sites_site_id
											FROM
												sites_users_7584
											WHERE
												user_id = ".$row_notes['user_id']."
											LIMIT
												1";
						$ret_user = $db->query($sql_user);
						if ($db->num_rows($ret_user))
						{
							$row_user 	= $db->fetch_array($ret_user);
							if($row_user['sites_site_id']!=0)
							$showuser	= stripslashes($row_user['user_title']).stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
							else
							$showuser	= stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
						}
						else
						$showuser	= 'User does not exist';
						echo $showuser;
						?>						</td>
						</tr>
				<?php
				}
				?>
				</table>
		<?php
		}
		else
		{
		?>
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				<td align="center" class="subcaption">No Notes added yet.</td>
				</tr>
				</table>
		<?php
		}
		?>		</td>
		<td width="1%" align="left">&nbsp;</td>
		<td width="48%" align="left" valign="top">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>
			<td><textarea name="txt_notes" id="txt_notes" cols="50" rows="5"></textarea></td>
			</tr>
			</table>		</td>
		</tr>
		</table></div>
		<div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">	
					<input type="button" name="note_submit" value="Save Note" class="red" onclick="save_note()">
				</td>
			</tr>
		</table>
	 </div>
<?php
}
/* Function to show the display logic to input the values required while refund */
function voucher_additionaldet_refund($voucherid)
{
	global $db,$ecom_siteid;
	// Get the currency symbol and conversion rate in current order
	$sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate,voucher_value,
							voucher_refundamt,voucher_paystatus,voucher_totalauthorizeamt
						FROM
							gift_vouchers
						WHERE
							voucher_id = ".$voucherid."
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch 	= $db->fetch_array($ret_vouch);
		// Decide whether refund is possible
		if ($row_vouch['voucher_paystatus']=='REFUNDED')
		$alert = 'Sorry!! this voucher has already been refunded';
		if ($row_vouch['voucher_totalauthorizeamt']>0)
			$max_refund_total = $row_vouch['voucher_totalauthorizeamt']-$row_vouch['voucher_refundamt'];
		else
			$max_refund_total = ($row_vouch['voucher_value']-$row_vouch['voucher_refundamt']);
		if($max_refund_total ==0)
		$alert = 'Nothing to refund';
	}
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Please fill in the following details</td>
		</tr>
		<tr>
		  <td align="left" valign="top">Amount to be refunded <span class="redtext">*</span></td>
		  <td align="left"><input type="text" name="txt_refundamt" /> &nbsp;&nbsp;<span class="shoppingcartpriceB">Total Refundable Amount: </span> <span class="shoppingcartpriceB"><?php echo print_price_selected_currency($max_refund_total,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true)?></span>
		  <input type="hidden" name="max_refundamt_allowed" id="max_refundamt_allowed" value="<?php echo print_price_selected_currency($max_refund_total,$row_vouch['voucher_curr_rate'],'',true)?>" />
		  <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?php echo $row_vouch['voucher_curr_symbol']?>" />
		  </td>
		  </tr>
		<tr>
		  <td width="21%" align="left" valign="top">Reason <span class="redtext">*</span></td>
		  <td width="79%" align="left"><textarea name="txt_refundreason" id="txt_refundreason" cols="50" rows="6"></textarea></td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="left" valign="top">Reason will be automatically added to the notes section</td>
		  </tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="right" valign="top"><input name="refund_submit" type="button" class="red" id="refund_submit" value="Click here to Refund" onclick="validate_refund()" /></td>
		  </tr>
	  </table>
	<?php
}
// Function to show the refund details
function voucher_refund_details($voucherid)
{
	global $db,$ecom_siteid;
	// Get the currency symbol and conversion rate in current voucher
	$sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate,voucher_value,voucher_refundamt,voucher_paystatus
						FROM
							gift_vouchers
						WHERE
							voucher_id = ".$voucherid."
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch 	= $db->fetch_array($ret_vouch);
	}
	$sql_ref = "SELECT refund_id,refund_on,refund_by,refund_amt
					FROM
						gift_voucher_details_refunded
					WHERE
						gift_vouchers_voucher_id = $voucherid
					ORDER BY
						refund_on DESC";
	$ret_ref = $db->query($sql_ref);
	if($db->num_rows($ret_ref))
	{
		?>
			<table width="80%" cellpadding="1" cellspacing="1" border="0">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_REFUND')?></div></td>
        </tr>
		<?php
		$table_varheaders 		= array('Sl No','Date','Refunded By','Refunded Amount');
		$headervar_positions	= array('center','left','left','right');
		$var_colspans			= count($table_varheaders);
		$varsrno				= 1;
		$tot_amt 				= 0;
		echo table_header($table_varheaders,$headervar_positions);
		while ($row_ref = $db->fetch_array($ret_ref))
		{
			$tot_amt += $row_ref['refund_amt'];
			$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
				<tr>
					<td align="center" width="8%" class="<?php echo $cls?>">
						<?php echo $varsrno?>.
					</td>
					<td align="left" width="20%" class="<?php echo $cls?>">
						<?php
						echo dateFormat($row_ref['refund_on'],'datetime');
						?>
					</td>
					<td align="left" class="<?php echo $cls?>" width="45%">
					<?php
					echo getConsoleUserName($row_ref['refund_by']);
					?>
					</td>
					<td align="right" class="<?php echo $cls?>">
					<?php
					echo print_price_selected_currency($row_ref['refund_amt'],$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
					?>
					</td>
				</tr>
				<tr id="refunddet_tr_<?php echo $row_ref['refund_id']?>" style="display:none">
				<td align="center" width="8%">&nbsp;</td>
				<td colspan="3">
				<div id="refunddet_div_<?php echo $row_ref['refund_id']?>">
				</div>
				</td>
				</tr>
		<?php
		$varsrno++;
		}
		?>
			<tr>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="right" class="shoppingcartpriceB">
				Total Refunded
				</td>
				<td align="right" class="shoppingcartpriceB">
					<?php
					echo print_price_selected_currency($tot_amt,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
					?>
				</td>
			</tr>
		</table>
		<?php
	}
}
// Function to show the refund amount
function voucher_refund_amount($voucherid,$alert='')
{
	global $db,$ecom_siteid;
	// Get the currency symbol and conversion rate in current order
	 $sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate,voucher_value,
							voucher_refundamt,voucher_paystatus,voucher_totalauthorizeamt
						FROM
							gift_vouchers
						WHERE
							voucher_id = ".$voucherid."
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch 	= $db->fetch_array($ret_vouch);
		// Decide whether refund is possible
		if ($row_vouch['voucher_paystatus']=='REFUNDED')
		$alert = 'Sorry!! this voucher has already been refunded';
		if ($row_vouch['voucher_totalauthorizeamt']>0)
			$max_refund_total = $row_vouch['voucher_totalauthorizeamt']-$row_vouch['voucher_refundamt'];
		else
			$max_refund_total = ($row_vouch['voucher_value']-$row_vouch['voucher_refundamt']);
		if($max_refund_total ==0)
		$alert = 'Nothing to refund';
	}
		if ($row_vouch['voucher_value']>0) // case if deposit amount exists
		  {
		  	$check_tot_amt = $row_vouch['voucher_value'];

		  }
		if(($check_tot_amt>$row_vouch['voucher_refundamt']))
		{ 
	?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1" class="fieldtable">
		<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="2" align="center" class="errormsg">
				<?php
				if($alert==1)
				echo "Refund successfull";
				else
				echo "Refund not successfull";
				?>
				</td>
				</tr>
		<?php
		}
		 
		?>
		<tr>
		  <td colspan="2" class="shoppingcartheader">Please fill in the following details</td>
		</tr>
		<tr>
		  <td align="left" valign="top">Amount to be refunded <span class="redtext">*</span></td>
		  <td align="left"><input type="text" name="txt_refundamt" /> &nbsp;&nbsp;<span class="shoppingcartpriceB">Total Refundable Amount: </span> <span class="shoppingcartpriceB"><?php echo print_price_selected_currency($max_refund_total,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true)?></span>
		  <input type="hidden" name="max_refundamt_allowed" id="max_refundamt_allowed" value="<?php echo print_price_selected_currency($max_refund_total,$row_vouch['voucher_curr_rate'],'',true)?>" />
		  <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?php echo $row_vouch['voucher_curr_symbol']?>" />
		  </td>
		  </tr>
		<tr>
		  <td width="21%" align="left" valign="top">Reason <span class="redtext">*</span></td>
		  <td width="79%" align="left"><textarea name="txt_refundreason" id="txt_refundreason" cols="50" rows="6"></textarea></td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="left" valign="top">Reason will be automatically added to the notes section</td>
		  </tr>
	  </table></div>
	  <div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">
					<input name="refund_submit" type="button" class="red" id="refund_submit" value="Click here to Refund" onclick="validate_refund()" />
				</td>
			</tr>
		</table>
	</div>
	<?php 
	}
	$sql_ref = "SELECT refund_id,refund_on,refund_by,refund_amt
					FROM
						gift_voucher_details_refunded
					WHERE
						gift_vouchers_voucher_id = $voucherid
					ORDER BY
						refund_on DESC";
	$ret_ref = $db->query($sql_ref);
	if($db->num_rows($ret_ref))
	{
		?>
			<table width="80%" cellpadding="1" cellspacing="1" border="0">
		
		<?php
		$table_varheaders 		= array('Sl No','Date','Refunded By','Refunded Amount');
		$headervar_positions	= array('center','left','left','right');
		$var_colspans			= count($table_varheaders);
		$varsrno				= 1;
		$tot_amt 				= 0;
		echo table_header($table_varheaders,$headervar_positions);
		while ($row_ref = $db->fetch_array($ret_ref))
		{
			$tot_amt += $row_ref['refund_amt'];
			$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
				<tr>
					<td align="center" width="8%" class="<?php echo $cls?>">
						<?php echo $varsrno?>.
					</td>
					<td align="left" width="20%" class="<?php echo $cls?>">
						<?php
						echo dateFormat($row_ref['refund_on'],'datetime');
						?>
					</td>
					<td align="left" class="<?php echo $cls?>" width="45%">
					<?php
					echo getConsoleUserName($row_ref['refund_by']);
					?>
					</td>
					<td align="right" class="<?php echo $cls?>">
					<?php
					echo print_price_selected_currency($row_ref['refund_amt'],$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
					?>
					</td>
				</tr>
				<tr id="refunddet_tr_<?php echo $row_ref['refund_id']?>" style="display:none">
				<td align="center" width="8%">&nbsp;</td>
				<td colspan="3">
				<div id="refunddet_div_<?php echo $row_ref['refund_id']?>">
				</div>
				</td>
				</tr>
		<?php
		$varsrno++;
		}
		?>
			<tr>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="right" class="shoppingcartpriceB">
				Total Refunded
				</td>
				<td align="right" class="shoppingcartpriceB">
					<?php
					echo print_price_selected_currency($tot_amt,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
					?>
				</td>
			</tr>
		</table>
		<?php
	}
}
/* Function which show the details on payment received change status selected */
function show_PayReceived_TakeDetails($status)
{ 
?>
<table border="0" cellpadding="1" cellspacing="1" width="100%">
<tbody>
<tr>
<td class="fontredheading">Please fill in the following details to mark the payment as Received </td>
</tr>

<tr>
<td align="left" valign="top" width="50%">
<table border="0" cellpadding="1" cellspacing="1" width="100%">
<tbody>
<tr>
  <td width="22%" align="left" class="normltdtext">Payment Method <span class="redtext">*</span></td>
  <td width="71%" align="left" class="normltdtext"><select name="cbo_paymethod" id="cbopaymethod">
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
		  <td colspan="2" align="left" class="shoppingcartheader">Reason (optional)</td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="top"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="45" rows="6"></textarea></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="top">Reason (if specified) will be automatically added to the notes section</td>
		  </tr>
		<tr>
		  <td  align="center" valign="top" colspan="2"><input name="Change_paystat" type="button" class="red" id="Change_paystat" value="Click here to Change the Payment status" onclick="validate_changepaystatus('<?=$status?>')" /></td>
		</tr>
</tbody></table>
</td>
</tr>
</tbody>
</table>
<?php	
}
/* Function which show the details on payment received change status selected */
function show_PayFailed_TakeDetails($status)
{
?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tbody>
<tr>
<td class="fontredheading">Please specify the reason for payment failure</td>
</tr>
<tr>
<td align="left" valign="top" width="50%">
<table border="0" cellpadding="1" cellspacing="1" width="100%">
<tbody>
<tr>
		  <td colspan="2" align="left" class="shoppingcartheader">Reason (optional)</td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="top"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="45" rows="6"></textarea></td>
		  </tr>
		<tr>
		  <td colspan="2" align="left" valign="top">Reason (if specified) will be automatically added to the notes section</td>
		  </tr>
		<tr>
		  
		  <td  align="center" valign="top" colspan="2"><input name="Change_paystat" type="button" class="red" id="Change_paystat" value="Click here to Change the Payment status" onclick="validate_changepaystatus('<?=$status?>')" /></td>
		</tr>
</tbody></table>	      </td>
</tr>
</tbody></table>
<?php	
}
 /*?>function hold_Order_status($order_id)
{
	global $db,$ecom_siteid;
	// Get the current status of current order
	$sql_ord = "SELECT order_status,order_paystatus,order_deposit_amt,order_deposit_cleared,
							order_deposit_cleared_on,order_deposit_cleared_by,
							order_totalprice,order_refundamt
						FROM
							orders
						WHERE
							order_id = $order_id
						LIMIT
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	
	?>
		<input type="hidden" name="pass_change_stat" id="pass_change_stat" value="<?php echo getorderstatus_Name($row_ord['order_status'])?>" />
		<input type="hidden" name="pass_change_paystat" id="pass_change_paystat" value="<?php echo getpaymentstatus_Name($row_ord['order_paystatus'])?>" />
		<?php
		if($row_ord['order_deposit_cleared']==1)
		{
			$cleared_on = dateFormat($row_ord['order_deposit_cleared_on'],'datetime');
			$sql_usr	= "SELECT sites_site_id,user_title,user_fname,user_lname
							FROM
								sites_users_7584
							WHERE
								user_id = ".$row_ord['order_deposit_cleared_by']."
							LIMIT
								1";
			$ret_usr 	= $db->query($sql_usr);
			if ($db->num_rows($ret_usr))
			{
				$row_user = $db->fetch_array($ret_usr);
				if ($row_user['sites_site_id']==0) // case of super admin
				$cleared_by = stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
				else
				$cleared_by = stripslashes($row_user['user_title']).".".stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
			}
	
			$cleared_by  = 'Released Amount Remaining by '.$cleared_by.' ('.$cleared_on.')';
	?>
		<input type="hidden" name="pass_productdeposit_release" id="pass_productdeposit_release" value="<?php echo $cleared_by?>" />
	<?php
		}
}<?php */?>
<?php
// release selected
function voucher_release_note()
{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Release Deferred Payment</td>
		</tr>
		<tr>
		  <td colspan="2">Note (optional)</td>
		</tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left">This action is not Reversible</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">
		  </td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="RELEASE" />
		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Release" onclick="perform_capturetype('RELEASE')" /></td>
		  </tr>
	  </table>
	<?php
}
function vocher_abort_note()
{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Abort Deferred Payment</td>
		</tr>
		<tr>
		  <td colspan="2">Note (optional)</td>
		</tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left">This action is not Reversible</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">
		  </td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="ABORT" />
		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Abort" onclick="perform_capturetype('ABORT')" /></td>
		  </tr>
	  </table>
	<?php
}
function voucher_repeat_note()
{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Repeat Preauth Payment</td>
		</tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" valign="top">Note (optional)</td>
		  <td align="left" valign="top"></td>
		  </tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left">This action is not Reversible</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">		  </td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="REPEAT" />
		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Repeat" onclick="perform_capturetype('REPEAT')" /></td>
		  </tr>
	  </table>
	<?php
}
function voucher_authorise_details($voucher_id)
{
	global $db,$ecom_siteid;
	// Get the currency symbol and conversion rate in current voucher
	 $sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate,voucher_value,
							voucher_refundamt,voucher_paystatus,voucher_totalauthorizeamt
						FROM
							gift_vouchers
						WHERE
							voucher_id = ".$voucher_id."
						LIMIT
							1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch 	= $db->fetch_array($ret_vouch);

		//check whether partial payment is made (product deposit case)
		$rem_amt 	= ($row_vouch['voucher_value'] - $row_vouch['voucher_refundamt']);
		// taking 115% of rem_amt
		$rem_amt_per			= $rem_amt * 115/100;

		// Less the amount already authorized
		$rem_amt_per			= $rem_amt_per - $row_vouch['voucher_totalauthorizeamt'];

		$max_auth_allowed		= $rem_amt_per; // only the paid amount is set to variable
		$tot_auth				= print_price_selected_currency($row_vouch['voucher_totalauthorizeamt'],$row_vouch['voucher_curr_rate'],'',true); // only the paid amount is set to variable
		if($tot_auth=='0.00')
		$tot_auth = 0;
	}

	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader" align="left">Authorise Payment</td>
		</tr>
		<tr>
		  <td width="51%" valign="top" align="left">Total Amount authorised till now </td>
		  <td width="49%" valign="top" align="left">
		  <?php
		  echo print_price_selected_currency($row_vouch['voucher_totalauthorizeamt'],$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
		  ?>
		  </td>
		  </tr>

		<tr>
		  <td valign="top" align="left">Amount to be authorised </td>
		  <td valign="top" align="left"><input type="text" name="txt_authamt" id="txt_authamt" />
		  &nbsp;<span class="shoppingcartpriceB">(maximum allowed  <?php
		  echo print_price_selected_currency($max_auth_allowed,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true);
		  ?>)</span>
		  </td>
		  </tr>
		<tr>
		  <td valign="top" align="left">Note (optional)</td>
		  <td valign="top" align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="45" rows="6"></textarea></td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="top">This action is not Reversible</td>
		  </tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="AUTHORISE" />
		  <input type="hidden" name="max_auth_allowed" id="max_auth_allowed" value="<?php echo $max_auth_allowed?>" />
		  <input type="hidden" name="tot_auth" id="tot_auth" value="<?php echo $tot_auth?>" />
		  <input type="hidden" name="order_currency_symbol" id="order_currency_symbol" value="<?php echo $row_vouch['voucher_curr_symbol']?>" />
		  <input type="hidden" name="max_auth_allowed_def" id="max_auth_allowed_def" value="<?php echo $rem_amt_per?>" />

		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Authorise" onclick="perform_capturetype('AUTHORISE')" /></td>
		  </tr>
	  </table>
	<?php
}
function voucher_cancel_note()
{
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Cancel Authorised Transacation</td>
		</tr>
		<tr>
		  <td colspan="2">&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" valign="top">Note (optional)</td>
		  <td align="left" valign="top"></td>
		  </tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left">This action is not Reversible</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">		  </td>
		</tr>
		<tr>
		  <td align="center" valign="top" colspan="2">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="CANCEL" />
		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Cancel" onclick="perform_capturetype('CANCEL')" /></td>
		  </tr>
	  </table>
	<?php
}
function voucher_authorise_amount_details($voucher_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get some of the relevant details from order table
	$sql_vouch = "SELECT voucher_curr_symbol,voucher_curr_rate
					 FROM
						gift_vouchers
					WHERE
						voucher_id = $voucher_id
					LIMIT
						1";
	$ret_vouch = $db->query($sql_vouch);
	if ($db->num_rows($ret_vouch))
	{
		$row_vouch = $db->fetch_array($ret_vouch);
	}
	// Get the list of authorized amount related to current order
	$sql_auth = "SELECT auth_id,auth_on,auth_by,auth_amt
						FROM
							gift_voucher_details_authorized_amount
						WHERE
							gift_vouchers_voucher_id= $voucher_id
						ORDER BY
							auth_on DESC";
	$ret_auth = $db->query($sql_auth);
	if ($db->num_rows($ret_auth))
	{
		$table_headers 		= array('#','Date','Authorised By','Amount');
		$header_positions	= array('center','center','left','right');
		$colspan 			= count($table_headers);
	?>
		<table width="80%" border="0" cellspacing="1" cellpadding="1">
		<tr>
          <td colspan="9" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_GIFT_VOUCH_AUTHAMOUNT')?></div></td>
        </tr>
		<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="<?php echo $colspan?>" align="center" class="errormsg">
				<?php
				echo $alert;
				?>
				</td>
				</tr>
		<?php
		}
		?>
		<?php
		$srno = 1;
		$tot_auth = 0;
		echo table_header($table_headers,$header_positions);
		while ($row_auth = $db->fetch_array($ret_auth))
		{
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$tot_auth += $row_auth['auth_amt'];
		?>
				<tr>
					<td width="2%" align="center" class="<?php echo $cls?>">
					<?php echo $srno++?>.
					</td>
					<td width="15%" align="center" class="<?php echo $cls?>">
					<?php echo dateFormat($row_auth['auth_on'],'datetime')?>
					</td>
					<td width="30%" align="left" class="<?php echo $cls?>">
					<?php echo getConsoleUserName($row_auth['auth_by']);?>
					</td>
					<td width="12%" align="right" class="<?php echo $cls?>">
					<?php echo print_price_selected_currency($row_auth['auth_amt'],$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true)?>
					</td>
				</tr>
		<?php
		}
		?>
				<tr>
					<td colspan="3" class="shoppingcartpriceB" align="right">
					Total Authorised
					</td>
					<td align="right"" class="shoppingcartpriceB">
					<?php echo print_price_selected_currency($tot_auth,$row_vouch['voucher_curr_rate'],$row_vouch['voucher_curr_symbol'],true)?>
					</td>
				</tr>
		</table>
	<?php
	}
}
?>
