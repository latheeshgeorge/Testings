<?php
/* Function to show the top section of order details */
function show_orderdetails_header($order_id,$pay_tab=0,$help_msg='',$fromprint=0)
{
	global $db,$ecom_siteid,$ecom_hostname,$print_buttons,$ecom_site_activate_invoice;
	$sql_ord = "SELECT order_id,customers_customer_id,sites_site_id,sites_shops_shop_id,order_date,order_custtitle,order_custfname,
 						order_custmname,order_custsurname,order_custcompany,order_buildingnumber,order_street,order_city,order_state,
 						order_country,order_custpostcode,order_custphone,order_custfax,order_custmobile,order_custemail,order_notes,
 						order_giftwrap,order_giftwrap_per,order_giftwrapmessage,order_giftwrapmessage_text,order_giftwrap_message_charge,
 						order_giftwrap_minprice,order_giftwraptotal,order_deliverytype,order_deliverylocation,order_delivery_option,
 						order_deliveryprice_only,order_deliverytotal,order_splitdeliveryreq,order_extrashipping,order_bonusrate,
 						order_bonuspoint_discount,order_bonuspoints_used,order_bonuspoint_inorder,order_paymenttype,order_paymentmethod,
 						order_paystatus,order_paystatus_changed_manually,order_paystatus_changed_manually_by,order_paystatus_changed_manually_on,order_paystatus_changed_manually_paytype,
 						order_hide,order_status,order_cancelled_by,order_cancelled_from,order_cancelled_on,order_refundamt,order_refundcomp_date,
 						order_deposit_amt,order_deposit_cleared,order_deposit_cleared_on,order_deposit_cleared_by,order_currency_code,
 						order_currency_numeric_code,order_currency_symbol,order_currency_convertionrate,order_tax_total,order_tax_to_delivery,
 						order_tax_to_giftwrap,order_customer_or_corporate_disc,order_customer_discount_type,order_customer_discount_percent,
 						order_customer_discount_value,order_totalprice,order_totalauthorizeamt,order_subtotal,order_pre_order,
 						gift_vouchers_voucher_id,order_gift_voucher_number,promotional_code_code_id,promotional_code_code_number,order_able2buy_cgid,
 						costperclick_id,order_despatched_completly_on  
					FROM
						orders
					WHERE
						order_id=".$order_id."
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
		$cur_inv = $cur_inv_file = '';
		if($ecom_site_activate_invoice==1) // case if invoice feature is active in current website
		{
			// Check whether invoice related to current order exists. If exist then get the details
			$sql_inv = "SELECT invoice_id, invoice_filename 
							FROM 
								order_invoice 
							WHERE 
								orders_order_id = $order_id 
							LIMIT 
								1";
			$ret_inv = $db->query($sql_inv);
			if ($db->num_rows($ret_inv))
			{
				$row_inv 		= $db->fetch_array($ret_inv);
				$cur_org_inv	= $row_inv['invoice_id'];
				$cur_inv		= 'INV-'.$row_inv['invoice_id'];
				$cur_inv_file	= stripslashes($row_inv['invoice_filename']);
			}					
			
		}
	}
	$cls = 'listingtablestyleB_n';
?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<?php 
		if($help_msg!='')
		{
		?>
			<tr>
				<td colspan="5" align="left" class="helpmsgtd">
				<div class="helpmsg_divcls"><?php echo $help_msg?></div>				</td>
			</tr>	
		<?php
		}
		if($cur_inv and !$fromprint) // Show the following tr only if invoice exists
		{
		?>
		 <tr>
		   <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >Invoice ID </td>
		   <td align="left" valign="middle" class="<?php echo $cls?>" ><a href="javascript:show_invoicepopup('<?php echo $cur_org_inv?>')" class="edittextlink" title="Click to view the invoice details"><? echo $cur_inv?></a></td>
		   <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >&nbsp;</td>
		   <td colspan="2" align="left" valign="middle" class="<?php echo $cls?>">&nbsp;</td>
	     </tr>
		 <?
		 }
		 ?>
		 <tr>
           <td width="12%" align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		   <?php 
		   if($cur_inv and $fromprint)
		   {
		   ?>
		   Invoice Id & Date
		   <?php
		   }
		   else
		   {
		   ?>
		   	Order ID & Date
		   <?php
		   }
		   ?>
		    </td>
           <td width="33%" align="left" valign="middle" class="<?php echo $cls?>" >
		   <?php 
		  	if($cur_inv and $fromprint)
		   	{
		   	?>
			<?php echo $cur_inv?>&nbsp;&nbsp;(<?php echo dateFormat($row_ord['order_date'],'datetime')?>)
			<?php	
			}
			else
			{
			?>
				<?php echo $row_ord['order_id']?>&nbsp;&nbsp;(<?php echo dateFormat($row_ord['order_date'],'datetime')?>)
			<?php	
			}	
			?>
			</td>
           <td width="15%" align="left" valign="middle" class="subcaption <?php echo $cls?>" ><?php
           	if(trim($row_ord['order_paymenttype'])!='')
           	{
           	?>
Payment Type
  <?php
           	}
           ?></td>
           <td colspan="2" align="left" valign="middle" class="<?php echo $cls?>"><?php echo getpaymenttype_Name($row_ord['order_paymenttype'])?>
           <?php
		   	if($row_ord['order_paymentmethod']!='')
			{
				echo '('.getpaymentmethod_Name($row_ord['order_paymentmethod']).')';
			}
			else
				echo '&nbsp;';
			?></td>
         </tr>
		 <?php
		 	$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
			$srno++;
		 ?>
         <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		   <?php
		   	if($fromprint!=1)
			{
			?>
		   	Order Status
			<?php
			}
			?>
			 </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" ><div id="orderstatus_maindiv">
		   <?php 
		   	if($fromprint!=1)
			{
		   		echo getorderstatus_Name($row_ord['order_status']);

				// If order is cancelled, the date of cancellation and also the person who cancelled it
				if($row_ord['order_cancelled_by']!=0)
				{
					if($row_ord['order_cancelled_from']=='A') // case cancelled from admin area
					{
						$cancelled_by = getConsoleUserName($row_ord['order_cancelled_by']);
					}
					else // case cancelled from client area
					{
						$sql_customer = "SELECT customer_title,customer_fname,customer_mname,customer_surname
											FROM
												customers
											WHERE
												customer_id = ".$row_ord['order_cancelled_by']."
												AND sites_site_id = $ecom_siteid
											LIMIT
												1";
						$ret_customer = $db->query($sql_customer);
						if($db->num_rows($ret_customer))
						{
							$row_usr = $db->fetch_array($ret_customer);
							$cancelled_by = stripslashes($row_usr['customer_title']).stripslashes($row_usr['customer_fname'])." ".stripslashes($row_usr['customer_mname'])." ".stripslashes($row_usr['customer_surname']).' (customer)';
						}
					}
	
					echo " (By ".$cancelled_by." on ".dateFormat($row_ord['order_cancelled_on'],'datetime').")";
				}
			}
		   ?> </div></td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >Payment Status </td>
           <td width="27%" align="left" valign="middle" class="<?php echo $cls?>"><div id="paymentstatus_maindiv">
		   <?php echo  getpaymentstatus_Name($row_ord['order_paystatus']);
		     if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				if($row_ord['order_paystatus_changed_manually_paytype']!='')
					echo  ' ('.ucwords(strtolower($row_ord['order_paystatus_changed_manually_paytype'])).')';
			}		
		   ?> </div>	      
		    </td>
           <td width="13%" align="right" valign="middle" class="<?php echo $cls?>">
		 <?PHP  if($print_buttons!=1) { ?>
             <input type="button" name="printerfriendly_Submit" value="Printer Friendly?" class="red" onclick="window.open('includes/orders/print_order_details.php?orderid=<?PHP echo $order_id; ?>','po','height=600,width=980,scrollbars=yes,resizable=yes')" />             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_FRIENDLY_DET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> <? } ?></td>
         </tr>
		 <?php
		 	$pay_str = $pay_usr = '';
		    if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				$pay_usr = getConsoleUserName($row_ord['order_paystatus_changed_manually_by']).' ( on '.dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime').')';
				$pay_str =  "Payment Status Changed By ";
			}
			$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
			$srno++;

		  $chk_show_ord_status = false;
		  $chk_show_pay_status = false;
		  if ($row_ord['order_status']!='CANCELLED' and $row_ord['order_status']!='DESPATCHED' ) // Order and payment status can be changed only if order status is not cancelled
		  {
		  	$chk_show_ord_status = true;
		  } 
		  $dont_show_payment = false;
		  $preauth_pay_msg = '';	
		  //if($row_ord['order_paystatus']=='Pay_Hold')
		  if($row_ord['order_paystatus']!='Paid')
		  {
		  	if ($row_ord['order_status']=='NOT_AUTH') // if incomplete order, then check whether any of the product is linked with price promise. if yes, check whether the usage count can be incremented
			{
				// check whether any of the products in current order is linked with price promise
				$sql_price = "SELECT  orderdet_id, order_prom_id 
								FROM 
									order_details 
								WHERE 
									orders_order_id = $order_id 
									AND order_prom_id <>0";
				$ret_price = $db->query($sql_price);
				$price_cnt = $db->num_rows($ret_price);
				if($price_cnt>0)
				{
					$cnt_price = 0;
					while ($row_price = $db->fetch_array($ret_price))
					{
						$sql_check = "SELECT  prom_max_usage,prom_used 
										FROM 
											pricepromise 
										WHERE 
											prom_id = ".$row_price['order_prom_id']." 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
							if($row_check['prom_max_usage']>$row_check['prom_used'])
								 $cnt_price++;
						}
						else // case if price promise does not exists
							$cnt_price++;	 
					}
					if($price_cnt==$cnt_price) // If all price promise are valid to increment the usage count the show the payment status change option else show a msg
					{
						$chk_show_pay_status = true;
					}	
					else
						$preauth_pay_msg = 'The product(s) in current order is linked to price promise which have already reached the maximum usage. You can change the payment status only if you increase the maximum usage of respective price promise.<br> 
											Click on "<strong>Order Summary</strong>" tab to find the product(s) which are linked with price promise.';
					
				}
				else
					$chk_show_pay_status = true;
			}
		  	elseif ($row_ord['order_status']!='CANCELLED') // Order and payment status can be changed only if order status is not cancelled
			{
				$chk_show_pay_status = true;
			} 
		  }
		  
		  //if ($row_ord['order_status']!='CANCELLED' and $row_ord['order_status']!='DESPATCHED' ) // Order and payment status can be changed only if order status is not cancelled
		  if($chk_show_ord_status or $chk_show_pay_status)
		  {
		?>
		 <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		   <?PHP if($print_buttons != 1 and $row_ord['order_status']!='NOT_AUTH' and $chk_show_ord_status) {  ?>Change Order Status<? } ?></td>
           <td align="left" valign="middle" class="<?php echo $cls?>" >
		    <?php
			
			if($print_buttons != 1 and $row_ord['order_status']!='NOT_AUTH' and $chk_show_ord_status) {
			
					  $orderstat_array = array(''=>'-- Select --');
					  if ($row_ord['order_status']!='NEW')
					  $orderstat_array['NEW'] = 'Unviewed';
					  if ($row_ord['order_status']!='PENDING')
					  $orderstat_array['PENDING'] = 'Pending';
					  if ($row_ord['order_status']!='ONHOLD')
					  $orderstat_array['ONHOLD'] = 'On Hold';
					  if ($row_ord['order_status']!='BACK')
					  $orderstat_array['BACK'] = 'Back Orders';
					  //if ($row_ord['order_paystatus']!='Paid') // Show the cancel status only if payment status is notpaid
					  $orderstat_array['CANCELLED'] = 'Cancel';
					  echo generateselectbox('cbo_orderstatus',$orderstat_array,'','','call_ajax_showlistall("operation_changeorderstatus_sel")');
				?>
				<div id="orderstatchange_div" style=" display:none"><input type="button" name="changeorderstatus_Submit" id="changeorderstatus_Submit" value="Change Order Status?" class="red" onclick="validate_changeorderstatus()" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_CHANGE_STATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div><? } ?>		   </td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		  <?php
		   if($pay_str!='')
		   {
		   		echo $pay_str;
				if($row_ord['order_paystatus']=='Pay_Hold' and $pay_tab==1)
					echo '<br/><br/>';
		   }
		
			if($pay_tab==1)
				$disp = '';
			else
				$disp = 'none';
			// Check whether the payment is deferred or preauth or authenticate
			if($row_ord['order_paystatus']=='DEFERRED' or $row_ord['order_paystatus']=='PREAUTH' or  $row_ord['order_paystatus']=='AUTHENTICATE')
			{
				
			}
			//elseif($row_ord['order_paystatus']!='Paid' and $row_ord['order_paystatus']!='free' and $row_ord['order_paystatus']!='Pay_Failed'  and $row_ord['order_paystatus']!='REFUNDED')
			elseif($row_ord['order_paystatus']!='free' and $row_ord['order_paystatus']!='REFUNDED' and $row_ord['order_paystatus']!='Paid')
			{
				if($row_ord['order_paystatus']!='Pay_Hold')
				{
					/*if ($row_ord['order_paystatus']=='Paid')
					{
						$orderpaystat_array = array(
						''=>'-- Select --',
						'Pay_Failed'=>'Payment Failed',
						'Pay_Hold'=>'Placed On Account'
						);
					}
					else*/if ($row_ord['order_paystatus']=='Pay_Failed')
					{
						$orderpaystat_array = array(
						''=>'-- Select --',
						'Paid'=>'Payment Received',
						'Pay_Hold'=>'Placed On Account'
						);
					}
					else
					{
						$orderpaystat_array = array(
						''=>'-- Select --',
						'Paid'=>'Payment Received',
						'Pay_Failed'=>'Payment Failed',
						'Pay_Hold'=>'Placed On Account'
						);
					}	
				}
				else
				{
					$orderpaystat_array = array(
					''=>'-- Select --',
					'Paid'=>'Payment Received',
					'Pay_Failed'=>'Payment Failed'
					);
				}	
				echo "<div id='paystatus1_div' style='display:".$disp."'>Change Payment Status</div>";
			}
			?>		   </td>
           <td colspan="2" align="left" valign="middle" class="<?php echo $cls?>">
		   <?php
				if($pay_usr!='')
				{
				 echo $pay_usr;
				 if($row_ord['order_paystatus']=='Pay_Hold' and $pay_tab==1)
					echo '<br/><br/>';
				}
				// If status array have values then show the payment status change section
				if (count($orderpaystat_array) and $preauth_pay_msg=='')
				{
					echo "<div id='paystatus2_div' style='display:".$disp."'>".generateselectbox('cbo_orderpaystatus',$orderpaystat_array,'','','call_ajax_showlistall("operation_changeorderpaystatus_sel")')."</div>";
				}
				if($preauth_pay_msg != '')
					echo '<span style="color: rgb(255, 0, 0);">'.$preauth_pay_msg.'</span>';	
		   ?>
		   </td>
         </tr>
		 <?php
		 }
		 ?>
		 <tr>
		   <td colspan="5" align="left" valign="middle"><div id="additionaldet_div"></div></td>
	     </tr>
</table>
<?php	
}
 /* Function to fetch the order details */
 function fetch_Order_Details($order_id)
 {
 	global $db,$ecom_siteid;
	$sql_ord = "SELECT * 
							FROM
								orders
							WHERE
								order_id=".$order_id."
								AND sites_site_id = $ecom_siteid
							LIMIT
								1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	return $row_ord;
 }
 /*  Function to show the order summary */
 function show_Order_Summary($order_id,$alert='',$fromprint=0)
 {
 	global $db,$ecom_siteid,$ecom_hostname,$show_order_details,$print_buttons;
	
	?>
	<div class="editarea_div">
	<?php
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	// Calling function to show the header section
	if($fromprint!=1)
		show_orderdetails_header($order_id);
	else // case if coming from print_order_details.php page
		show_orderdetails_header($order_id,0,'',1);
	// Calling function to show the billing address
	if($fromprint!=1)
		show_billing_address($order_id);
	else
	{
		// Check whether only billing address is to be displayed or both billing and delivery address is to be displayed
		$sql_check = "SELECT printerfriendly_include_delivery_address 
						FROM 
							general_settings_sites_common_onoff 
						WHERE 
							sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check))
		{
			$row_check = $db->fetch_array($ret_check);
		}
		if($row_check['printerfriendly_include_delivery_address']==1) // Check whether both delivery and billing address is to be displayed
			show_billing_address_and_delivery_address($order_id,1);
		else
			show_billing_address($order_id,1);
		
	}	
	show_giftwrap_details($order_id);	
	// Calling function to show the items remaining in order
	show_Products_Remaining_In_Order($order_id,$row_ord,'main',$fromprint);
	// Calling function to show items currently in backorder 
	show_Products_Placed_In_BackOrder($order_id,$row_ord,$fromprint);
	// Calling function to show the list of dispatched products
	show_Products_Despatched($order_id,$row_ord,'main',$fromprint);
	// Calling the function to show the list of cancelled products
	show_Products_Cancelled($order_id,$row_ord,'',$fromprint);
	// Calling function to show the order totals
	show_OrderTotals($order_id,$row_ord);
	?>
	</div>
	<?php
 }

 /*  Function to show the payment details */
 function show_Order_Payments($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname,$print_buttons;
	$gone_in = false;
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	$ord_tot	= print_price_selected_currency($row_ord['order_totalprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
	?>
	<div class="editarea_div">
	<?php
	show_orderdetails_header($order_id,1,get_help_messages('ORD_DET_PAY_MAIN_MSG'));
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	if($alert!='')
	{
	?>
		<tr  id='payment_mainerror_tr'>
		<td colspan="2" class="errormsg" align="center"><?php echo $alert?></td>
		</tr>
	<?php
	}
	?>
  <tr>
    <td class="listingtableheader" width="50%">Payment Details </td>
    <td class="listingtableheader"><span class="blue">Order Total: &nbsp;<?php echo $ord_tot?></span></td>
  </tr>
  <tr>
	<td align="left" valign="top">
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
	
	<?php
	   	$gone_in = true;
	?>
			<tr>
				<td align="left">
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" class="listingtablestyleB_n" width="40%"><strong>Payment Type</strong></td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo getpaymenttype_Name($row_ord['order_paymenttype'])?><?php
					if($row_ord['order_paymentmethod']!='')
					{
						echo '('.getpaymentmethod_Name($row_ord['order_paymentmethod']).')';
					}
					else
						echo '&nbsp;';
					?></td>
				</tr>
				<tr>
					<td align="left" class="listingtablestyleB_n"><strong>Payment Status</strong></td>
					<td align="left" class="listingtablestyleB_n">  <?php echo  getpaymentstatus_Name($row_ord['order_paystatus']);?></td>
				</tr>
				<?php
				if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
				{
				?>
				<tr>
					<td align="left" class="listingtablestyleB_n"><strong>Payment Status Manually Changed by</strong></td>
					<td align="left" class="listingtablestyleB_n">
					<?php  echo getConsoleUserName($row_ord['order_paystatus_changed_manually_by']).' on '.dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime');
					 ?></td>
				</tr>
				<?php
					if($row_ord['order_paystatus_changed_manually_paytype']!='')
					{
				?>
					<tr>
						<td align="left" class="listingtablestyleB_n"><strong>Payment Manually done by</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo  ucwords(strtolower($row_ord['order_paystatus_changed_manually_paytype']));
						 ?></td>
					</tr>
				<?php
					}
				}
				 //if ($row_ord['order_status']!='CANCELLED' and $row_ord['order_deposit_amt']>0 and $row_ord['order_deposit_cleared']==0) // Decide whether to show the collect remaining amount in case of product deposit
				  if ($row_ord['order_deposit_amt']>0) // Decide whether to show the collect remaining amount in case of product deposit
				  {
				?>
					<tr>
					<td align="left" colspan="2">&nbsp;
					</td>
					</tr>
					<tr>
					<td align="left" class="listingtablestyleB_n"><strong>Amount Paid while placing the order</strong></td>
					<td align="left" class="listingtablestyleB_n"><strong><?php echo print_price_selected_currency($row_ord['order_deposit_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></strong> (Product Deposit)</td>
					</tr>
					<?php
					if($row_ord['order_deposit_cleared']==0)
					{
					?>
						<tr>
						<td align="left" class="listingtablestyleB_n"><strong>Amount Remaining to be Released</strong></td>
						<td align="left" class="listingtablestyleB_n"><strong><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></strong></td>
						</tr>
					<?php
					}
					if($row_ord['order_paystatus']=='Paid' and $row_ord['order_deposit_cleared']==0)
					{
					?>
					<tr>
					<td>&nbsp;
					</td>
					<td align="left">
						<input name="productdeposit_Submit" type="button" class="red" id="productdeposit_Submit" value="Release Remaining Amount" onclick="release_remaining_amount()" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_RELEASE_REM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					</td>
					</tr>
				<?php
					}
					elseif($row_ord['order_paystatus']!='Paid' and $row_ord['order_deposit_cleared']==0)
					{
						echo '<tr><td>&nbsp;</td><td align="left"><span class="redtext"><strong>Note: </strong>Remaining amount can be released only if payment status is Paid</span></td></tr>';
					}	
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
						$cleared_by = getConsoleUserName($row_ord['order_deposit_cleared_by']);
						$cleared_msg  = $cleared_by.' on '.$cleared_on;
				?>
						<tr>
						<td align="left" class="listingtablestyleB_n"><strong>Released Remaining Amount</strong></td>
						<td align="left" class="listingtablestyleB_n"><strong><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></strong> By <?php echo $cleared_msg?></td>
						</tr>
				<?php	
					}
				}
				if($row_ord['order_paystatus']=='Paid')
				{
				?>
				<tr>
					<td align="center" class="shoppingcartpriceB"><strong>Total Amount Paid</strong></td>
					<td align="left" class="shoppingcartpriceB">
					<?php
						if($row_ord['order_deposit_amt']>0)
						{
							if($row_ord['order_deposit_cleared']==0)
								$tot_paid = $row_ord['order_deposit_amt'];
							else
								$tot_paid = $row_ord['order_totalprice'];
						}
						else
						{
							$tot_paid	= $row_ord['order_totalprice'];
						}	
					?>
						<strong><?php echo print_price_selected_currency($tot_paid,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				</tr>
				<?php
				}
				?>
				</table>
				</td>
			</tr>
	<?php
		if($row_ord['order_paystatus'] == 'DEFERRED')
		{
			$gone_in = true;
		?>
				<tr>
				<td align="left">
					<div id="capturetypereleasemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
						<input name="release_button" type="button" value="Release" class="red" onclick="call_ajax_showlistall('RELEASE',0);"/>
						<a href="#" onmouseover ="ddrivetip('To Release Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					</div>
					<?php 
					// Handling the case of Abort or Cancel butttons?>
					<div id="capturetypeabortmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
				  			<input name="abort_button" type="button" value="Abort" class="red"  onclick="call_ajax_showlistall('ABORT');"/>
							<a href="#" onmouseover ="ddrivetip('To Abort Deferred Transaction Please use this button. <br><br> This action is not Reversible.<br>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				  </div>
				</td>
				</tr>	
		<?php
		}
		elseif($row_ord['order_paystatus'] == 'PREAUTH')
		{
			$gone_in = true;
		?>
			<tr>
			<td align="left">
					<input name="release_button" type="button" value="Repeat" class="red" onclick="call_ajax_showlistall('REPEAT',0);"/>
					<a href="#" onmouseover ="ddrivetip('To Repeat Preauth Transaction Please use this button. <br><br> This action is not Reversible.<br>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
			</tr>	
		<?php
		}
		elseif($row_ord['order_paystatus'] == 'AUTHENTICATE') 
		{
			$gone_in = true;
		?>
			<tr>
				<td align="left">
				<div id="capturetypeauthorisemain_div" style="float:left; padding-right:5px;padding-bottom:3px">
					<input type="button" name="auth_button" value="Authorise" class="red"  onclick="call_ajax_showlistall('AUTHORISE',0);" />
					<a href="#" onmouseover ="ddrivetip('To Authorise this AUTHENTICATED Transaction Please use this button. You can authorise an amount upto 115% of the Order Amount <br><br> This action is not Reversible.<br>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				</div>
				<div id="capturetypecancelmain_div" style="float:left; padding-right:5px;padding-bottom:3px">
					<input name="cancel_button" type="button" value="Cancel" class="red"  onclick="call_ajax_showlistall('CANCEL');"/>
					<a href="#" onmouseover ="ddrivetip('To Cancel Authenticated Transaction Please use this button. <br><br> This action is not Reversible.<br>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				</div>
				</td>
			</tr>		
		<?php
		}
		?>	 
		  
	</table></td>
	<td align="left" valign="top">
	<div id="paydet_div"></div>
	</td>
</tr>
  <tr>
    <td valign="top">
	<?php
	
	
	if($row_ord['order_paymenttype']=='credit_card') // case of payment is by credit card
	{
		if ($row_ord['order_paymentmethod']=='SELF' or $row_ord['order_paymentmethod']=='PROTX' or $row_ord['order_paymentmethod']=='PROTX_VSP') // If method is self or protx direct or protx vsp
		{
			$gone_in = true;
	?>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td align="left" colspan="2">
					<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<?php
					// Check whether any record exists for current order in order_payment_main table
					$sql_pay = "SELECT orders_order_id, sites_site_id, order_card_type, order_name_on_card, order_card_number, order_card_encrypted, order_sec_code,
												order_expiry_date_m, order_expiry_date_y, order_issue_number, order_issue_date_m, order_issue_date_y, order_vendorTxCode, 
												order_protStatus, order_protStatusDetail, order_vPSTxId, order_securityKey, order_txAuthNo, order_txType, order_avscv2,
												order_cavv, order_3dsecurestatus, order_acsurl, order_pareq, order_md, order_orgtxType, order_googletransId 
									FROM
										order_payment_main
									WHERE
										orders_order_id = $order_id 
										AND sites_site_id = $ecom_siteid
									LIMIT 1";
					$ret_pay = $db->query($sql_pay);
					if ($db->num_rows($ret_pay))
					{
						$row_pay= $db->fetch_array($ret_pay);
					?>
						<tr>
							<td align="left" colspan="2" class="shoppingcartheader">
							Credit Card Details							</td>
						</tr>
					<?php
					$srno=1;
					if ($row_pay['order_card_type']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Card Type		</strong>						</td>
								<td align="left" class="<?php echo $cls?>">
									<?php echo $row_pay['order_card_type']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_name_on_card']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Name on Card		</strong>						</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_name_on_card'])?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_card_number']!='')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
						if($row_pay['order_card_encrypted']==1)
						$cc = base64_decode(base64_decode($row_pay['order_card_number']));
						else
						$cc = $row_pay['order_card_number'];
						if($row_ord['order_paystatus']=='Paid')
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
								<strong>Card Number	</strong>							</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($cc)?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_sec_code']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Security Code</strong>								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_sec_code'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_expiry_date_m']!=0 and $row_pay['order_expiry_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Expiry Date</strong>								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_expiry_date_m'].'/'.$row_pay['order_expiry_date_y']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_issue_number']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Issue Number	</strong>							</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_issue_number']?>								</td>
							</tr>
					<?php
					}
					if ($row_pay['order_issue_date_m']!=0 and $row_pay['order_issue_date_y']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Issue Date</strong>								</td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo $row_pay['order_issue_date_m'].'/'.$row_pay['order_issue_date_y']?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_vendorTxCode']!='' and $row_pay['order_vendorTxCode']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Vendor TXcode</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_vendorTxCode'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_protStatus']!='' and $row_pay['order_protStatus']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Prot Status</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_protStatus'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_protStatusDetail']!='' and $row_pay['order_protStatusDetail']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Prot Status Details</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_protStatusDetail'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_vPSTxId']!='' and $row_pay['order_vPSTxId']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>VPS Txid</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_vPSTxId'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_securityKey']!='' and $row_pay['order_securityKey']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Security Key</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_securityKey'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_txAuthNo']!='' and $row_pay['order_txAuthNo']!='NULL' and $row_pay['order_txAuthNo']!=0)
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Tx Auth No</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_txAuthNo'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_txType']!='' and $row_pay['order_txType']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>Tx Type</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_txType'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_avscv2']!='' and $row_pay['order_avscv2']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>AVSC V2</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_avscv2'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_cavv']!='' and $row_pay['order_cavv']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>CAVV</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_cavv'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_3dsecurestatus']!='' and $row_pay['order_3dsecurestatus']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>3d Secure Status</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_3dsecurestatus'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_acsurl']!='' and $row_pay['order_acsurl']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>ACS URL</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_acsurl'])?></td>
							</tr>
					<?php
					}
					if ($row_pay['order_pareq']!='' and $row_pay['order_pareq']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						/*$srno++;
						
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>PAREQ</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_pareq'])?></td>
							</tr>
					<?php
						*/
					}
					if ($row_pay['order_md']!='' and $row_pay['order_md']!='NULL')
					{
						$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
						$srno++;
					?>
							<tr>
								<td align="left" width="25%" class="<?php echo $cls?>">
								<strong>MD</strong></td>
								<td align="left" class="<?php echo $cls?>">
								<?php echo stripslashes($row_pay['order_md'])?></td>
							</tr>
					<?php
					}
				}
					?>
					</table>				</td>
			</tr>
			</table>
	<?php
		}
		elseif ($row_ord['order_paymentmethod']=='PAYPAL_EXPRESS' or $row_ord['order_paymentmethod']=='PAYPALPRO') // case if payment method is paypal express or paypal pro
		{
			// Get the details given back to us from paypal
			$sql_paypal = "SELECT paypal_transactions_id, paypal_transaction_type, paypal_payment_type,
								paypal_ordertime, paypal_amt, paypal_currency_code, paypal_feeamt, 
								paypal_settleamt, paypal_taxamt, paypal_exchange_rate, paypal_paymentstatus,
								paypal_pending_reason, paypal_reasoncode,paypal_avscode, paypal_cvv2match,paypal_VPAS 
							FROM 
								order_payment_paypal 
							WHERE 
								orders_order_id = $order_id 
								AND sites_site_id = $ecom_siteid
							LIMIT 
								1";
			$ret_paypal = $db->query($sql_paypal);
			if($db->num_rows($ret_paypal))
			{
				$row_paypal = $db->fetch_array($ret_paypal);
		?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					Payment Gateway Return Details</td>
				</tr>
				<?php
				if($row_paypal['paypal_transactions_id']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Unique transaction ID of the payment</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_transactions_id']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_transaction_type']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Transaction Type</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_transaction_type']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_payment_type']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Payment Type</strong></td>
						<td align="left" class="listingtablestyleB_n">
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
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Order Time</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $showdate?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_amt']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Final Amount</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_amt']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_currency_code']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Currency Code</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_currency_code']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_feeamt']!='' and $row_paypal['paypal_feeamt']>0)
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>PayPal fee amount charged for the transaction</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_feeamt']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_settleamt']!='' and $row_paypal['paypal_settleamt']>0)
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Amount deposited in your PayPal account after a currency conversion</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_settleamt']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_taxamt']!='' and $row_paypal['paypal_taxamt']>0)
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Tax charged on the transaction</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_taxamt']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_exchange_rate']!='' and $row_paypal['paypal_exchange_rate']>0)
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Exchange rate if a currency conversion occurred</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_exchange_rate']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_paymentstatus']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Status of the payment</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_paymentstatus']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_pending_reason']!='' and strtolower($row_paypal['paypal_pending_reason'])!='none')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>The reason the payment is pending</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_pending_reason']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_reasoncode']!='' and strtolower($row_paypal['paypal_reasoncode'])!='none')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>The reason code</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_reasoncode']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_avscode']!='' and strtolower($row_paypal['paypal_avscode'])!='none')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>AVS code</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_avscode']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_cvv2match']!='' and strtolower($row_paypal['paypal_cvv2match'])!='none')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>CVV2 Match</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_cvv2match']?></td>
					</tr>
				<?php
				}
				if($row_paypal['paypal_cvv2match']!='' and strtolower($row_paypal['paypal_cvv2match'])!='none')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n" valign="top">
						<strong>Correlation ID</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_paypal['paypal_VPAS']?></td>
					</tr>
				<?php
				}
				?>
				</table>
		<?php
			}
		}
		elseif ($row_ord['order_paymentmethod']=='BARCLAYCARD') // case if payment method is BARCLAYCARD
		{
			// Get the details given back to us from paypal
			$sql_barclay = "SELECT currency, amount, pm,
								acceptance, status, cardno, ed, 
								cn, trxdate, payid, ncerror,
								brand, complus,ip, pay_type 
							FROM 
								order_payment_barclaycard 
							WHERE 
								orders_order_id = $order_id 
								AND sites_site_id = $ecom_siteid 
								AND pay_type ='Order' 
							LIMIT 
								1";
			$ret_barclay = $db->query($sql_barclay);
			if($db->num_rows($ret_barclay))
			{
				$row_barclay = $db->fetch_array($ret_barclay);
		?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					Barclaycard Gateway Return Details</td>
				</tr>
				<?php
				if($row_barclay['currency']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Order currency</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['currency']?></td>
					</tr>
				<?php
				}
				if($row_barclay['amount']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Order amount</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['amount']?></td>
					</tr>
				<?php
				}
				if($row_barclay['pm']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Payment method</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['pm']?></td>
					</tr>
				<?php
				}
				if($row_barclay['acceptance']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Acceptance code returned by acquirer</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['acceptance']?></td>
					</tr>
				<?php
				}
				if($row_barclay['status']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Transaction status</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['status']?></td>
					</tr>
				<?php
				}
				if($row_barclay['cardno']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Masked card number</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['cardno']?></td>
					</tr>
				<?php
				}
				if($row_barclay['ed']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Expiry date</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['ed']?></td>
					</tr>
				<?php
				}
				if($row_barclay['cn']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Cardholder / Customer name</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['cn']?></td>
					</tr>
				<?php
				}
				if($row_barclay['trxdate']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Transaction date</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['trxdate']?></td>
					</tr>
				<?php
				}
				if($row_barclay['payid']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Payment reference in Barclaycard system</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['payid']?></td>
					</tr>
				<?php
				}
				if($row_barclay['brand']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Card brand</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['brand']?></td>
					</tr>
				<?php
				}
				if($row_barclay['pay_type']!='')
				{
				?>
					<tr>
						<td align="left" width="45%" class="listingtablestyleB_n">
						<strong>Payment Type</strong></td>
						<td align="left" class="listingtablestyleB_n">
						<?php echo $row_barclay['pay_type']?></td>
					</tr>
				<?php
				}
				?>
				</table>
		<?php
			}
		}
		else // case of paymethod other than self or protx or protx vsp
		{
			$gone_in = true;
			// Check whether any record exists for current order in order_payment_main table
			$sql_pay = "SELECT order_googletransId,order_nochex_transaction_id, order_nochex_transaction_date,order_nochex_order_id,
								order_nochex_amount, order_nochex_from_email, order_nochex_to_email, order_nochex_security_key, order_nochex_status,
								 order_realex_timestamp, order_realex_result, order_realex_orderid, order_realex_message, order_realex_authcode ,
								 order_realex_passref ,order_realex_md5hash 
							FROM
								order_payment_main
							WHERE
								orders_order_id = $order_id
								AND sites_site_id = $ecom_siteid 
							LIMIT 1";
			$ret_pay = $db->query($sql_pay);
			if ($db->num_rows($ret_pay))
			{
				$row_pay= $db->fetch_array($ret_pay);
			}	
			$caption = '';
			switch($row_ord['order_paymentmethod'])
			{
				case 'WORLD_PAY':
					$caption 	= 'World Pay Transaction Id';
				break;
				case 'HSBC':
					$caption 	= 'HSBC CPI resultcode';
				break;
				case 'GOOGLE_CHECKOUT':
					$caption 	= 'Google Transaction Id';
				break;
			};
			if ($caption!='')
			{
		?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					Payment Gateway Return Details					</td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong><?php echo $caption?></strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_googletransId']?>					</td>
				</tr>
				</table>
		<?php
			}
			if($row_ord['order_paymentmethod'] == 'NOCHEX')
			{
			?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					NoChex Return Details</td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Transaction Id</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_transaction_id']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Transaction Date</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_transaction_date']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Order Id</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_order_id']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Amount</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_amount']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Your Email</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_to_email']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Customer Email</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_from_email']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Security Key</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_security_key']?></td>
				</tr>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Status</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo $row_pay['order_nochex_status']?></td>
				</tr>
				</table>
		<?php
			}
			if($row_ord['order_paymentmethod'] == 'REALEX')
			{
			?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="2" class="shoppingcartheader">
					RealEx Return Details</td>
				</tr>
				<?php 
				if($row_pay['order_realex_timestamp'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Time Stamp</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_timestamp'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_result'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Result</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_result'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_orderid'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Order Id</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_orderid'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_message'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Message</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_message'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_authcode'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Auth Code</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_authcode'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_passref'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Pass Ref</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_passref'])?></td>
				</tr>
				<?php
				}
				if($row_pay['order_realex_md5hash'])
				{
				?>
				<tr>
					<td align="left" width="25%" class="listingtablestyleB_n">
					<strong>Hash</strong>		</td>
					<td align="left" class="listingtablestyleB_n">
					<?php echo stripslashes($row_pay['order_realex_md5hash'])?></td>
				</tr>
				<?php
				}
				?>
				</table>
		<?php
			}
		}
	}
	elseif ($row_ord['order_paymenttype']=='cheque')
	{
		// Get the cheque details from order_cheque_details table
		$sql_cheque = "SELECT cheque_date,cheque_number,cheque_bankname,cheque_branchdetails
							FROM
								order_cheque_details
							WHERE
								orders_order_id = $order_id
							LIMIT
								1";
		$ret_cheque = $db->query($sql_cheque);
		if($db->num_rows($ret_cheque))
		{
			$gone_in = true;
			$row_cheque = $db->fetch_array($ret_cheque);
		?>
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td align="left" colspan="4" class="shoppingcartheader">
					Cheque Details					</td>
				</tr>
				<tr>
					<td align="left" width="15%" class="subcaption listingtablestyleB_n" valign="top">
					Date of Cheque					</td>
					<td align="left" class="listingtablestyleB_n" valign="top">
					<?php echo stripslashes($row_cheque['cheque_date'])?>					</td>
					<td align="left" width="15%" class="subcaption listingtablestyleB_n" valign="top">
					Cheque Number					</td>
					<td align="left" class="listingtablestyleB_n" valign="top">
					<?php echo stripslashes($row_cheque['cheque_number'])?>					</td>
				</tr>
				<tr>
					<td align="left" class="subcaption listingtablestyleA" valign="top">
					Bank Name					</td>
					<td align="left" class="listingtablestyleA" valign="top">
					<?php echo stripslashes($row_cheque['cheque_bankname'])?>					</td>
					<td align="left" class="subcaption listingtablestyleA" valign="top">
					Branch Details					</td>
					<td align="left" class="listingtablestyleA" valign="top">
					<?php echo nl2br(stripslashes($row_cheque['cheque_branchdetails']))?>					</td>
				</tr>
				</table>
		<?php
		}
	}
	if($gone_in == false)
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td class="redtext" align="center">
				No Payment details found			</td>
		</tr>
		</table>		
	<?php
	}
	?>	</td>
    <td align="left" valign="top">
	</td>
  </tr>
   <?php /*?><tr>
    <td width="60%" align="left" valign="top"></td>
  </tr><?php */?>
	<?php
			// Decide whether the authorize amount section is to be displayed
			$sql_check = "SELECT auth_id
							FROM
								order_details_authorized_amount
							WHERE
								orders_order_id = $order_id
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
		?>
				<tr>
					<td  align="left" valign="bottom" colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
						<tr>
						<td width="3%" class="seperationtd_special"><img id="auth_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'authorise_amount_details')" title="Click" style="cursor:pointer"/></td>
						<td width="97%" align="left" class="seperationtd_special"  onclick="handle_expansionall(document.getElementById('auth_imgtag'),'authorise_amount_details')" style="cursor:pointer">Authorise Amount Details</td>
						</tr>
						</table>
					</td>
				</tr>

				<tr id="authdet_tr" style="display:none">
						<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="authdet_div" style="text-align:center"></div>
						</td>
				</tr>
		<?php
			}
		?>  
  </table>
  </div>
	<?php
	//hold_Order_status($order_id);
 }
    
  /*  Function to show the despatch details */
 function show_Order_Despatch($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
 	?>
	<div class="editarea_div">
	<?php
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	// Calling function to show the header
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_DESP_MAIN_MSG'));
	// Calling function to show the delivery details
	show_delivery_address($order_id);
	show_giftwrap_details($order_id);
	if($row_ord['order_paystatus']=='Paid' || $row_ord['order_paystatus']=='REFUNDED' || $row_ord['order_paystatus']=='Pay_Hold' || $row_ord['order_paystatus']=='free')
	{
		
		// Calling function to show the items remaining in order which can be dispatched
		show_Products_Remaining_In_Order($order_id,$row_ord,'despatch');
		// Calling function to show the list of dispatched products
		show_Products_Despatched($order_id,$row_ord,'despatch');
	}
	else
	{
		echo "<span class='redtext'>Sorry!! Product Despatch is possible only if the Payment is Successfull or Payment Status is 'Placed on Account'. Payment status can be changed from the <a href=\"javascript:handle_tabs('payment_tab_td','order_payment')\" class='edittextlink'><strong>'Payments'</strong></a> tab</span>";
	}	
	?>
	</div>
	<?php	
 }
 
  /*  Function to show the refund details */
 function show_Order_Refunds($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
 	?>
	<div class="editarea_div">
	<?php
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	// Calling function to show the header
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_REFUND_MAIN_MSG'));
	if($row_ord['order_paystatus']=='Paid' || $row_ord['order_paystatus']=='REFUNDED')
	{
		// Calling function to show the items remaining in order which can be dispatched
		show_Products_Remaining_In_Order($order_id,$row_ord,'main_sel');
		 // Calling the function to show the list of cancelled products
		 show_Products_Cancelled($order_id,$row_ord,'main_sel');
		 // Calling function to show the details of refund already made
		 order_refund_details($order_id,$row_ord);
		 // Calling function to show the input boxes to enter the refund details
		 show_Refund_Input_Section($order_id,$row_ord);
	}
	else
	{
		echo "<span class='redtext'>Sorry!! Refund is possible only if the Payment is Successfull. Payment status can be changed from the <a href=\"javascript:handle_tabs('payment_tab_td','order_payment')\" class='edittextlink'><strong>'Payments'</strong></a> tab</span>";
	}	
	?>
	</div>
	<?php
 }
 
  /*  Function to show the order return details */
 function show_Order_Returns($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
	?>
	<div class="editarea_div">
	<?php
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	// Calling function to show the header
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_RETURN_MAIN_MSG'));
	if($row_ord['order_paystatus']=='Paid' || $row_ord['order_paystatus']=='Pay_Hold' || $row_ord['order_paystatus']=='REFUNDED' || $row_ord['order_paystatus']=='free')
	{
		 show_Products_Despatched($order_id,$row_ord,'return');
		 show_Products_Returned($order_id,$row_ord,$type='main');
	}
	else
	{
		echo "<span class='redtext'>Sorry!! Returns can be done only if the Payment is Successfull or Payment Status is 'Placed on Account'. Payment status can be changed from the <a href=\"javascript:handle_tabs('payment_tab_td','order_payment')\" class='edittextlink'><strong>'Payments'</strong></a> tab</span>";
	}	
	//hold_Order_status($order_id);
	?>
	</div>
	<?php
 }
  /*  Function to show the notes and emails details */
 function show_Order_NotesandEmails($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
 	?>
	<div class="editarea_div">
	<?php
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_NOTES_MAIN_MSG'));
?>
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
	<?php
	if($alert!='')
	{
	?>
		<tr   id='notes_mainerror_tr'>
		<td colspan="2" class="errormsg" align="center"><?php echo $alert?></td>
		</tr>
	<?php
	}
	?>
	<tr>
	<td colspan="4" align="left" valign="bottom">
	<div class="productdet_mainoutercls">
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
	
	<tr>
		<td colspan="4" align="left" valign="bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td width="3%" class="seperationtd_special"><img id="notes_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'notes')" title="Click" style="cursor:pointer"/></td>
			<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('notes_imgtag'),'notes')"style="cursor:pointer">Notes</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr id="note_tr" style="display:none">
		<td align="right" colspan="4" class="tdcolorgray_buttons">
		<div id="note_div" style="text-align:center"></div>
		</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
	
	<tr>
	<td colspan="4" align="left" valign="bottom">
	<div class="productdet_mainoutercls">
	<table width="100%" border="0" cellspacing="0" cellpadding="1">

	<tr>
		<td colspan="4" align="left" valign="bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td width="3%" class="seperationtd_special"><img id="email_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'emails')" title="Click" style="cursor:pointer"/></td>
			<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('email_imgtag'),'emails')" style="cursor:pointer">Emails</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr id="email_tr" style="display:none">
		<td align="right" colspan="4" class="tdcolorgray_buttons">
		<div id="email_div" style="text-align:center"></div>
		</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
	</table>
	</div>
<?php
	//hold_Order_status($order_id);
 }
   /*  Function to show the notes and customer query details */
 function show_Order_CustomerQueries($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
 	?>
	<div class="editarea_div">
	<?php
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_CUSTQUERY_MAIN_MSG'));
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		<td align="left" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
				<td align="left" width="5%" class="listingtableheader">
				Slno
				</td>
				<td align="left" width="15%" class="listingtableheader">
				Date Added
				</td>
				<td align="left" width="55%" class="listingtableheader">&nbsp;
				Subject
				</td>
				<td align="left" width="15%" class="listingtableheader">
				Added By
				</td>
				<td align="left" width="10%" class="listingtableheader">
				New Posts
				</td>
		</tr>
		<?php
		// Get all the notes added for this order
		$order_query = "SELECT query_id,query_date,query_subject,query_source,user_id,query_status 
							FROM
								order_queries
							WHERE
								orders_order_id = $order_id
							ORDER BY
								query_date
									DESC";
		$ret_query = $db->query($order_query);
		if($db->num_rows($ret_query))
		{
				$i=1;
				while ($row_query = $db->fetch_array($ret_query))
				{
				?>
					<tr>
						<td align="left" class="listingtablestyleB_n"><?php echo $i++;?></td>
					<td align="left" class="listingtablestyleB_n"><?php echo dateFormat($row_query['query_date'],'datetime')?></td>
					<td align="left" class="listingtablestyleB_n"><a href="home.php?request=order_enquiries&fpurpose=edit&checkbox[0]=<?php echo $row_query['query_id']?>&order_id=<?php echo $order_id?>" title="Click to view the query details" class="edittextlink"><?php echo stripslashes($row_query['query_subject'])?></a></td>
					<td align="left" class="listingtablestyleB_n">
					<?php
						if($row_query['query_source']=='A')
						{
							echo getConsoleUserName($row_query['user_id']).' (Admin)';
						}
						else
						{
							$sql_cust = "SELECT customer_title,customer_fname,customer_surname 
													FROM 
														customers 
													WHERE 
														customer_id=".$row_query['user_id']." 
													LIMIT 
														1";
							$ret_cust = $db->query($sql_cust);
							if($db->num_rows($ret_cust))
							{
								$row_cust = $db->fetch_array($ret_cust);
								$cname = $row_cust['customer_title'].' '.$row_cust['customer_fname'].' '.$row_cust['customer_surname'];
							}		
							echo $cname.' (Customer)';
						}
					?>
					</td>
					<td align="left" width="15%" class="listingtablestyleB_n">
					<?php
						// Get the count of new posts for this query
						$sql_posts = "SELECT count(post_id) 
												FROM 
													order_queries_posts 
												WHERE 
													order_queries_query_id = ".$row_query['query_id']." 
													AND post_status='N'";
													
						$ret_posts = $db->query($sql_posts);
						list($post_cnt) =$db->fetch_array($ret_posts);
						echo $post_cnt;
					?>
					</td>
					</tr>
				<?php
				}
				?>
		<?php
		}
		else
		{
		?>
				<tr>
				<td align="center" class="subcaption" colspan="5">No Queries added yet.</td>
				</tr>
		<?php
		}
		?>
		</table>
		</td>
		</tr>
		</table>
		</div>
	<?php
	//hold_Order_status($order_id);
 }
   /*  Function to show the notes and emails details */
 function show_Order_Others($order_id,$alert='')
 {
 	global $db,$ecom_siteid,$ecom_hostname;
	// Get the details relavent in current section from orders table
	$sql_ord = "SELECT order_tax_total,gift_vouchers_voucher_id,promotional_code_code_id 
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	?>
	<div class="editarea_div">
	<?php	
	show_orderdetails_header($order_id,0,get_help_messages('ORD_DET_OTHER_MAIN_MSG'));
?>
	<table width="100%" cellpadding="1" cellspacing="0" border="0">
	<?php
	if($alert!='')
	{
	?>
		<tr>
		<td colspan="2" class="errormsg" align="center" id='other_mainerror_td'><?php echo $alert?></td>
		</tr>
	<?php
	}
	?>
	<?php
		if($row_ord['order_tax_total']>0) // decide whether tax details to be displayed or not
		{
	?>
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td width="3%" class="seperationtd_special"><img id="tax_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'tax')" title="Click" style="cursor:pointer"/></td>
				<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('tax_imgtag'),'tax')" style="cursor:pointer">Tax Details</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr id="tax_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="tax_div" style="text-align:center"></div>
			</td>
		</tr>
	<?php
	}
	if($row_ord['gift_vouchers_voucher_id']>0)// show only if gift voucher exists in current order
	{
	?>
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td width="3%" class="seperationtd_special"><img id="voucher_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'voucher')" title="Click" style="cursor:pointer"/></td>
				<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('voucher_imgtag'),'voucher')" style="cursor:pointer">Gift Voucher Details</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr id="voucher_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons"><div id="voucher_div" style="text-align:center"></div></td>
		</tr>
	<?php
	}
	if($row_ord['promotional_code_code_id']>0)// show only if promotional code exists in current order
	{
	?>
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td width="3%" class="seperationtd_special"><img id="promotional_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'prom')" title="Click" style="cursor:pointer"/></td>
				<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('promotional_imgtag'),'prom')" style="cursor:pointer">Promotional Code Details</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr id="prom_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
			<div id="prom_div" style="text-align:center"></div>
			</td>
		</tr>
	<?php
	}
	// Decide whether the downloadable section is to be displayed
	$sql_check = "SELECT ord_down_id  
					FROM
						order_product_downloadable_products 
					WHERE
						orders_order_id = $order_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT
						1";
	$ret_check = $db->query($sql_check);
	if ($db->num_rows($ret_check))
	{
	?>
	<tr>
		<td colspan="4" align="left" valign="bottom">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td width="3%" class="seperationtd_special"><img id="download_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'order_download')" title="Click" style="cursor:pointer"/></td>
			<td width="97%" align="left" class="seperationtd_special" onclick="handle_expansionall(document.getElementById('download_imgtag'),'order_download')" style="cursor:pointer">Downloadable Items</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr id="orderdownload_tr" style="display:none">
		<td align="right" colspan="4" class="tdcolorgray_buttons">
		<div id="orderdownload_div" style="text-align:center"></div>
		</td>
	</tr>
<?php
	}
?>	
	</table>	
	</div>		
	<?php
	//hold_Order_status($order_id);
 }
 function order_additionaldet_common()
{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Order Status Change Reason (optional)</td>
		</tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left" class="normltdtext">Reason (if specified) will be automatically added to the notes section</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">		  </td>
		</tr>
		<tr>
		  <td colspan="2" align="center" valign="top"><input name="Change_submit3" type="button" class="red" id="Change_submit3" value="Click here to Change the status" onclick="call_ajax_showlistall('operation_changeorderstatus_do')" /></td>
		  </tr>
		<tr>
		  <td colspan="2" align="center" valign="top">&nbsp;</td>
		  </tr>
	  </table>
	<?php
}
function select_AlternateProduct($sel_cat,$sel_kw,$ext_str)
{
	global $db,$ecom_siteid;
	$add_condition = '';
	if($sel_kw) // case if search keyword is specified
	{
		$add_condition = " AND product_name LIKE '%".$sel_kw."%' ";
	}
	if($ext_str!='') // case if few product already selected as alternate products
	{
		$ext_arr = explode("~",$ext_str);
		$add_condition .= " AND product_id NOT IN(".implode(",",$ext_arr).") ";
	}
	if ($sel_cat)
	{
		$incprod_arr = array();
		// Find the ids of products mapped with selected category which are not hidden
		$sql_prodmap = "SELECT products_product_id
								FROM
									product_category_map
								WHERE
									product_categories_category_id = ".$sel_cat."
								ORDER BY
									product_order";
		$ret_prodmap = $db->query($sql_prodmap);
		if ($db->num_rows($ret_prodmap))
		{
			while ($row_prodmap = $db->fetch_array($ret_prodmap))
			{
				$incprod_arr[] = $row_prodmap['products_product_id'];
			}
		}
		if(count($incprod_arr)>0)
		{
			$prod_str = implode(",",$incprod_arr);
		}
		else
		$prod_str = -1;
		$add_condition .= " AND product_id IN($prod_str) ";
	}
	$sql_prods = "SELECT product_id,product_name,product_webprice,product_discount,product_discount_enteredasval
						FROM
							products
						WHERE
							sites_site_id=$ecom_siteid
							AND product_hide = 'N'
							$add_condition
						ORDER BY
							product_name";
	$ret_prods = $db->query($sql_prods);
	if($db->num_rows($ret_prods))
	{
	?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			  <tr>
				<td width="40%">
				<select name="cbo_selectalternate_products[]" size="10" multiple="multiple" id="cbo_selectalternate_products[]" style="width:250px">
				<?php
				while ($row_prods = $db->fetch_array($ret_prods))
				{
				?>
						<option value="<?php echo $row_prods['product_id']?>"><?php echo stripslashes($row_prods['product_name'])?></option>
				<?php
				}
				?>
				</select></td>
				<td width="60%" align="left"><input name="addselect_Submit" type="button" class="red" id="addselect_Submit" value="Add Seleted" onclick="add_alternate()" /></td>
			  </tr>
			</table>
	<?php
	}
	else
	echo "Sorry!! No products found";
}
function order_additionaldet_cancel($order_id)
{
	global $db,$ecom_siteid;
	// Get the general settings details to check whether this site maintains stock
	$sql_stockmaintain = "SELECT product_maintainstock
							FROM
								general_settings_sites_common
							WHERE
								sites_site_id = $ecom_siteid
							LIMIT
								1";
	$ret_stockmaintain = $db->query($sql_stockmaintain);
	if($db->num_rows($ret_stockmaintain))
	{
		$row_stockmaintain 	= $db->fetch_array($ret_stockmaintain);
		$maintain_stock		= $row_stockmaintain['product_maintainstock'];
	}
	/* Donate bonus Start */
	// Fetch few details from orders table
	$sql_ord = "SELECT customers_customer_id,order_bonuspoints_used,gift_vouchers_voucher_id,
						costperclick_id,order_bonuspoint_inorder,promotional_code_code_id,order_status,
						order_bonuspoints_donated  
					FROM
						orders
					WHERE
						order_id=$order_id
					LIMIT
						1";
	/* Donate bonus End */
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Reason For Order Cancellation *</td>
		</tr>
		<tr>
		  <td width="45%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td class="normltdtext">Reason for cancellation will be included in the cancellation mail to customer and also will be added as a note. Quantity of products in order will be added back to the stock of respective product. </td>
            </tr>
          </table></td>
		  <td width="55%" align="left" valign="top">
		  <?php if ($row_ord['order_status']!='NOT_AUTH')
		  {
		  ?>
		  	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		  <tr>
          	<td align="left" colspan="2" class="shoppingcartheader">Options available while cancellation          </td>
          </tr>
          <?php
          	if($maintain_stock==1) // show the following option only if stock is maintained by current site
          	{
          ?>
	          <tr>
	          	<td width="7%" align="left">
	          		<input type="checkbox" name="chk_stock_return" id="chk_stock_return" value="1" />
	          	</td>
	         	 <td width="93%" align="left" class="subcaption">
	         		 Place the quantity of products in order back to stock.	         	 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_BACK_STOCK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	          </tr>
          <?php
          	}
          	if($row_ord['customers_customer_id']) // Check whether this order is placed after logging in
          	{
				
				if ($row_ord['order_bonuspoints_used']>0)
          		{
          ?>
		          <tr>
		          	<td width="7%" align="left">
		          		<input name="chk_bonusused_return" type="checkbox" id="chk_bonusused_return" value="1" checked="checked" />
		          	</td>
		         	 <td align="left" class="subcaption">
		         		 Return the bonus points used by customer back to their account.
         	         <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_BACK_BONUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		          </tr>
		    <?php
          		}
          		/* Donate bonus Start */
          		// Check whether any bonus points donated in current order
          		if ($row_ord['order_bonuspoints_donated']>0)
          		{
          ?>
		          <tr>
		          	<td width="7%" align="left">
		          		<input name="chk_bonusdonated_return" type="checkbox" id="chk_bonusdonated_return" value="1" checked="checked" />
		          	</td>
		         	 <td align="left" class="subcaption">
		         		 Return the bonus points donated by customer back to their account.
         	         <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_DONATE_BONUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		          </tr>
		    <?php
          		}
          		/* Donate bonus End */
          		// Check whether any bonus points used in current order
          		/*if ($row_ord['order_bonuspoints_used']>0)
          		{
          ?>
		          <tr>
		          	<td width="7%" align="left">
		          		<input name="chk_bonusused_return" type="checkbox" id="chk_bonusused_return" value="1" checked="checked" />
		          	</td>
		         	 <td align="left" class="subcaption">
		         		 Return the bonus points used by customer back to their account.
         	         <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_BACK_BONUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		          </tr>
		    <?php
          		}*/
          		// Check whether any bonus points earned from current order
          		if ($row_ord['order_bonuspoint_inorder']>0)
          		{
		    ?>
				  <tr>
		          	<td width="7%" align="left">
		          		<input type="checkbox" name="chk_bonusearned_return" id="chk_bonusearned_return" value="1" checked="checked" />
		          	</td>
		         	 <td align="left" class="subcaption">
		         		 Take back the bonus points earned by customer from this order.
						 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_BACK_EARNEDBONUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		         	 </td>
		          </tr>
          <?php
          		}
			}
          		if ($row_ord['gift_vouchers_voucher_id']>0 or $row_ord['promotional_code_code_id']>0)
          		{
          ?>
		          <tr>
		          	<td width="7%" align="left">
		          		<input name="chk_gift_usage" type="checkbox" id="chk_gift_usage" value="1" checked="checked" />
		          	</td>
		         	 <td align="left" class="subcaption">
		         		 Decrement the maximum usage count for the gift voucher / Promotional code (if any). 
						 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_BACK_VOCUH_USE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		         	 </td>
		          </tr>
		   <?php
          		}
		   ?>
 		  <tr>
          	<td width="7%" align="left">
          		<input type="checkbox" name="chk_cancel_forced" id="chk_cancel_forced" value="1"/>
          	</td>
         	 <td align="left" class="subcaption">
         		 Cancel the order even if any of the products have already despatched or refunded.
				 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_FORCE_DESP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
         	 </td>
          </tr>
          <tr>
          <td width="7%" align="left">
          		<input type="checkbox" value="1" name="chk_cancelalternate" id="chk_cancelalternate" onclick="handle_alternatecheckbox()" />
          	</td>
         	 <td align="left" class="subcaption">
         		Send Alternate Products <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ALTERNATE_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
         	 </td>
          </tr>
          </table>
		  <?php
		  }
		  ?>
		  </td>
		</tr>
		<tr id="alternateprod_tr" style="display:none">
		  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3" class="shoppingcartheader">Choose Alternate Products </td>
            </tr>
            <tr>
              <td width="24%" align="left" valign="top" class="normltdtext">In Category</td>
              <td colspan="2" align="left" valign="top"><?php
              $cat_arr = generate_category_tree(0,0,false,false,true);
              if(is_array($cat_arr))
              {
              	echo generateselectbox('sel_alternatecategory',$cat_arr,0,'','');
              }
			  ?></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="normltdtext">Product Name like </td>
              <td width="46%"  align="left"><input name="txt_alternateprodname" type="text" id="txt_alternateprodname" />
              &nbsp;</td>
              <td width="30%"  align="left"><input type="button" name="alternate_selgo" class="red" value="Go" onclick="call_ajax_showlistall('show_alternateproduct_selsection')" />
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ALTERNATE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			  </td>
            </tr>
            <tr>
              <td colspan="3" align="center" valign="top">
			  <div id="selectalternateproduct_div"></div></td>
            </tr>
          </table></td>
		  <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="left" class="shoppingcartheader">Selected Alternate Products </td>
            </tr>
            <tr>
              <td colspan="2" align="center" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td width="60%" align="center" valign="top">
			  <select name="cbo_selectedalternate_products[]" size="10" multiple="multiple" id="cbo_selectedalternate_products[]" style="width:250px">
              </select></td>
              <td width="40%" align="left"><br />
                  <input name="remove_alternate" type="button" class="red" id="remove_alternate" value="Remove" onclick="remove_selectedalternateproducts()" /></td></tr>
          </table></td>
		  </tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top"><input name="cancelorder_submit" type="button" class="red" id="cancelorder_submit" value="Click here to Cancel this  Order" onclick="validate_cancelorder()" /></td>
		  </tr>
	  </table>
	  <input type="hidden" name="displayed_cancel_div" id="displayed_cancel_div" value="1" />
	<?php
}
// ###############################################################################################################
// 				Function which holds the display logic of tax details to be shown when called using ajax;
// ###############################################################################################################
function show_tax_details($order_id)
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_tax_total,order_tax_to_delivery,
							order_tax_to_giftwrap
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

	$sql_tax = "SELECT tax_name,tax_percent,tax_charge
					FROM
						order_tax_details
					WHERE
						orders_order_id = $order_id";
	$ret_tax = $db->query($sql_tax);
	if ($db->num_rows($ret_tax))
	{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="left" colspan="2">
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
				<?php
				$srno=1;
				while ($row_tax = $db->fetch_array($ret_tax))
				{
					$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
					$srno++;
				?>
						<tr>
							<td align="left" width="25%" class="subcaption <?php echo $cls?>">
							<?php echo stripslashes($row_tax['tax_name'])?>
							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">
							<?php echo stripslashes($row_tax['tax_percent'])?>%
							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">
							</td>
							<td align="left" width="25%" class="<?php echo $cls?>">
							<?php
							echo print_price_selected_currency($row_tax['tax_charge'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>
							</td>
						</tr>
				<?php
				}
				?>
				<tr>
					<td align="left" width="25%" class="shoppingcartpriceB"></td>
					<td align="left" width="25%" class="shoppingcartpriceB"></td>
					<td align="left" width="25%" class="shoppingcartpriceB">Total Tax</td>
					<td align="left" width="25%" class="shoppingcartpriceB">
					<?php
					echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php
		/*$max_cols	= 2;
		$cur_col	= 0;
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Top';
		$show_header	= 1;
		include '../includes/orders/show_dynamic_fields_orders.php';*/
		?>

		</table>

	<?php
	}
}
// ###############################################################################################################
// 				Function which holds the display logic of voucher details to be shown when called using ajax;
// ###############################################################################################################
function show_voucher_details($order_id)
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol
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

	$sql_voucher = "SELECT voucher_no,voucher_value_used,voucher_type,actual_voucher_value
					FROM
						order_voucher
					WHERE
						orders_order_id = $order_id";
	$ret_voucher = $db->query($sql_voucher);
	if ($db->num_rows($ret_voucher))
	{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td align="left" colspan="2">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="25%" class="shoppingcartheader">
					Voucher Number
					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Type
					</td>
					<td align="left" width="25%" class="shoppingcartheader">&nbsp;

					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Total Discount
					</td>
				</tr>
				<?php
				while ($row_voucher = $db->fetch_array($ret_voucher))
				{
				?>
						<tr>
							<td align="left" width="25%" class="shoppingcartpriceA">
							<?php echo stripslashes($row_voucher['voucher_no'])?>
							</td>
							<td align="left" width="25%" class="shoppingcartpriceA">
							<?php 
							if($row_voucher['voucher_type']=='val') 
								echo "Value"; 
							else 
							    echo "Percentage";
							//echo stripslashes($row_voucher['voucher_type'])
							?>
							</td>
							<td align="left" width="25%" class="shoppingcartpriceA">&nbsp;

							</td>
							<td align="left" width="25%" class="shoppingcartpriceA">
							<?php
							echo print_price_selected_currency($row_voucher['voucher_value_used'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>
							</td>
						</tr>
				<?php
				}
				?>
				</table>
			</td>
		</tr>
		</table>
	<?php
	}
}
// ###############################################################################################################
// 				Function which holds the display logic of promotional code details to be shown when called using ajax;
// ###############################################################################################################
function show_promotional_details($order_id)
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol
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

	$sql_prom = "SELECT code_type,code_number,code_orgvalue,code_lessval,code_minimum,code_value
					FROM
						order_promotional_code
					WHERE
						orders_order_id = $order_id";
	$ret_prom = $db->query($sql_prom);
	if ($db->num_rows($ret_prom))
	{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td align="left">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="25%" class="shoppingcartheader">
					Promotional Code
					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Type
					</td>
					<td align="left" width="25%" class="shoppingcartheader">&nbsp;

					</td>
					<td align="left" width="25%" class="shoppingcartheader">
					Total Discount
					</td>
				</tr>
				<?php
				while ($row_prom = $db->fetch_array($ret_prom))
				{
				?>
						<tr>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?php echo stripslashes($row_prom['code_number'])?>
							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?= get_promotional_type(stripslashes($row_prom['code_type']));//echo stripslashes($row_prom['code_type'])?>
							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;

							</td>
							<td align="left" width="25%" class="shoppingcartpriceB">
							<?php
							echo print_price_selected_currency($row_prom['code_lessval'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
							?>
							</td>
						</tr>
				<?php
				}
				?>
				</table>
			</td>
		</tr>
		</table>
	<?php
	}
}
// ###############################################################################################################
// 				Function which holds the display logic of order downloadables to be shown when called using ajax;
// ###############################################################################################################
function show_order_download($order_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the payment status of current order
	$sql_ord = "SELECT order_paystatus 
						FROM 
							orders 
						WHERE 
							order_id=$order_id 
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
		if($alert)
		{
		?>
				<tr>
				<td align="center" class="errormsg">
				<?php
				echo $alert;
				?>				</td>
				</tr>
		<?php
		}
		?>
		<tr>
		<td align="left" valign="top">
		<?php
		// Get all the notes added for this order
		$order_query = "SELECT a.ord_down_id,b.proddown_title,a.proddown_limited,a.proddown_limit,a.proddown_days_active,a.order_details_orderdet_id,
											a.product_downloadable_products_proddown_id,
										CASE (a.proddown_days_active)
										WHEN 1 
											THEN IF(c.order_paystatus='Paid',date_format(a.proddown_days_active_start,'%d-%m-%Y'),'--')
										WHEN 0 
											THEN ''
										END as  active_startdate,
										CASE (a.proddown_days_active)
										WHEN 1 
											THEN IF(c.order_paystatus='Paid',date_format(a.proddown_days_active_start,'%H:%i:%S'),'--')
										WHEN 0 
											THEN ''
										END as  active_starttime,
										CASE (a.proddown_days_active)
										WHEN 1 
											THEN IF(c.order_paystatus='Paid',date_format(a.proddown_days_active_end,'%d-%m-%Y'),'--')
										WHEN 0 
											THEN ''
										END as  active_enddate,
										CASE (a.proddown_days_active)
										WHEN 1 
											THEN IF(c.order_paystatus='Paid',date_format(a.proddown_days_active_end,'%H:%i:%S'),'--')
										WHEN 0 
											THEN ''
										END as  active_endtime,
										case (a.proddown_disabled)
										WHEN 1
											THEN 'Yes' 
										WHEN 0 
											THEN 'No' 
										END as disabled 
							FROM
								order_product_downloadable_products a, product_downloadable_products b ,orders c
							WHERE
								a.orders_order_id = $order_id 
								AND a.product_downloadable_products_proddown_id = b.proddown_id 
								AND a.orders_order_id = c.order_id 
							ORDER BY
								a.ord_down_id ";
		$ret_query = $db->query($order_query);
		if($db->num_rows($ret_query))
		{
		?>
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<?php
					if($row_ord['order_paystatus']=='Paid' or $row_ord['order_paystatus']=='free')
					{
				?>
				<tr>
				<td colspan="7" align="right">
				<div class="unassign_div">
				<input type="button" name="download_savedetails" id="download_savedetails" value="Save Details" class="red" onclick="call_ajax_showlistall('order_download_save_details')" />
				<a href="#" onmouseover ="ddrivetip('Use \'Save Details\' button to save the details. Make the required changes, tick mark the changed downloadable items and then click the Save Details button to save the changes')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				&nbsp;&nbsp; Change Disable Status 
				<select name="download_changestatus" id="download_changestatus">
					<option value="0">No</option>
					<option value="1">Yes</option>
				</select>
				<input type="button" name="download_changedisabled" id="download_changedisabled" value="Change" class="red" onclick="call_ajax_showlistall('order_download_change_status')" />
				<a href="#" onmouseover ="ddrivetip('If any download is to be suspended for some time or activate a suspended download, it can be done using this section. Select the required download by tick marking them, select the required status from the dropdown box and then click on \'Change \' button make the change.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>				</td>
				</tr>
				<?php
					}
				?>
				<tr>
				<td align="left" width="5%" class="listingtableheader">
					<img src="images/checkbox.gif" onclick="select_all(document.frmOrderDetails,'download_check[]')" border="0"><img src="images/uncheckbox.gif" onclick="select_none(document.frmOrderDetails,'download_check[]')" border="0">				</td>
					<td align="left" width="5%" class="listingtableheader">
					Slno					</td>
					<td align="left" width="35%" class="listingtableheader">
					Title					</td>
					<td align="center" width="10%" class="listingtableheader">
					Download Limit					</td>
					<td align="left" width="20%" class="listingtableheader">
					Download Start Date					</td>
					<td align="left" width="20%" class="listingtableheader">
					Download End Date					</td>
					<td align="center" width="15%" class="listingtableheader">
					Disabled					</td>
				</tr>
				<?php
				$i=1;
				// options req for hour minute and seconds dropdownboxes
				for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
				$slno = 1;
				while ($row_query = $db->fetch_array($ret_query))
				{
					// Find the id of product linked with current downloadable product
					$sql_prod = "SELECT products_product_id 
											FROM 
												order_details 
											WHERE 
												orderdet_id = ".$row_query['order_details_orderdet_id']." 
												AND orders_order_id = $order_id 
											LIMIT 
												1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
					}
					
					// Check whether any download history exists for current downlodable item
					$sql_history = "SELECT DATE_FORMAT(track_date ,'%d-%m-%Y %h:%i:%s %r') as download_date 
												FROM 
													order_product_downloadable_products_customer_track 
												WHERE 
													order_product_downloadable_products_ord_down_id = ".$row_query['ord_down_id']." 
												ORDER BY 
													track_date DESC";
					$ret_history = $db->query($sql_history);
				?>
					<tr>
						<td width="5%" align="left" valign="middle" class="listingtablestyleB_n">
						<input type="checkbox" name="download_check[]" id="download_check[]" value="<?php echo $row_query['ord_down_id']?>" />					  </td>
						<td align="left" valign="middle" class="listingtablestyleB_n"><?php echo $slno++;?>.</td>
						<td align="left" valign="middle" class="listingtablestyleB_n"><a href="home.php?request=products&fpurpose=edit_proddownload&checkbox[0]=<?php echo $row_prod['products_product_id']?>&edit_id=<?php echo $row_query['product_downloadable_products_proddown_id']?>&curtab=download_tab_td" title="Click to go to downlod edit page" class="edittextlink"><?php echo  stripslashes($row_query['proddown_title'])?></a>
						<?php 
						if ($db->num_rows($ret_history))
						{
						?>
							<div id="downloadhistory_div_<?php echo $row_query['ord_down_id']?>" onclick="handle_downloadhistory('<?php echo $row_query['ord_down_id']?>')" style="width:180px; float:right; cursor:pointer">
							Click for view download history</a><img src="images/right_arr.gif" border="0" /></div>
						<?php
						}
						?>
						</td>
						<td align="center" valign="middle" class="listingtablestyleB_n">
						<?php 
							if($row_query['proddown_limited']==1)
							{
						?>
								<input type="text" name="download_limit_<?php echo $row_query['ord_down_id']?>" id="download_limit_<?php echo $row_query['ord_down_id']?>" value="<?php echo $row_query['proddown_limit']?>" size="4" />
						<?php		
							}
							else
							{
								echo 'N/A';
						?>
								<input type="hidden" name="download_limit_<?php echo $row_query['ord_down_id']?>" id="download_limit_<?php echo $row_query['ord_down_id']?>" value="0" />
					  <?php	
							}
						?></td>
						<td align="center" valign="middle" class="listingtablestyleB_n">
						<?php
							if ($row_query['proddown_days_active']==1)
							{
								$active_starttime_arr 	= explode(":",$row_query['active_starttime']);
								$active_start_hr			= $active_starttime_arr[0];
								$active_start_mn			= $active_starttime_arr[1];
								$active_start_ss			= $active_starttime_arr[2];	
								$active_endttime_arr 	= explode(":",$row_query['active_endtime']);
								$active_end_hr			= $active_endttime_arr[0];
								$active_end_mn			= $active_endttime_arr[1];
								$active_end_ss			= $active_endttime_arr[2];	
								if($row_query['active_startdate']!='--')
								{
								?>
									<table width="100%" border="0">
                                      <tr>
                                        <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="5%"><input type="text" name="download_startdate_<?php echo $row_query['ord_down_id']?>" id="download_startdate_<?php echo $row_query['ord_down_id']?>" value="<?php echo $row_query['active_startdate']?>" size="10" readonly="true"/></td>
                                            <td width="95%"><a href="javascript:show_calendar('frmOrderDetails.download_startdate_<?php echo $row_query['ord_down_id']?>');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                                          </tr>
                                          
                                        </table></td>
                                      </tr>
                                      <tr>
                                        <td width="13%" align="left">HH</td>
                                        <td width="14%" align="left">MM</td>
                                        <td width="11%" align="left">SS</td>
                                        <td width="62%" align="center">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td align="left">
										<select name="download_starttime_hr_<?php echo $row_query['ord_down_id']?>" id="download_starttime_hr_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select>
										</td>
                                        <td align="left">
										<select name="download_starttime_mn_<?php echo $row_query['ord_down_id']?>" id="download_starttime_mn_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select>
										</td>
                                        <td align="left">
										<select name="download_starttime_ss_<?php echo $row_query['ord_down_id']?>" id="download_starttime_ss_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select>
										</td>
                                        <td align="center">&nbsp;</td>
                                      </tr>
                          </table>
								<?php
								}
								else
								{
									echo "Will be set when payment is success";
								?>
									<input type="hidden" name="download_startdate_<?php echo $row_query['ord_down_id']?>" id="download_startdate_<?php echo $row_query['ord_down_id']?>" value="<?php echo $row_query['active_startdate']?>" />							
									<input type="hidden" name="download_starttime_hr_<?php echo $row_query['ord_down_id']?>" id="download_starttime_hr_<?php echo $row_query['ord_down_id']?>" value="<?php echo $active_start_hr?>" />							
									<input type="hidden" name="download_starttime_mn_<?php echo $row_query['ord_down_id']?>" id="download_starttime_mn_<?php echo $row_query['ord_down_id']?>" value="<?php echo $active_start_mn?>" />							
									<input type="hidden" name="download_starttime_ss_<?php echo $row_query['ord_down_id']?>" id="download_starttime_ss_<?php echo $row_query['ord_down_id']?>" value="<?php echo $active_start_ss?>" />							
								<?php
								}	
							}
							else
							{
								echo 'N/A';
							?>
								<input type="hidden" name="download_startdate_<?php echo $row_query['ord_down_id']?>" id="download_startdate_<?php echo $row_query['ord_down_id']?>" value="00-00-0000" />							
								<input type="hidden" name="download_starttime_hr_<?php echo $row_query['ord_down_id']?>" id="download_starttime_hr_<?php echo $row_query['ord_down_id']?>" value="00" />							
								<input type="hidden" name="download_starttime_mn_<?php echo $row_query['ord_down_id']?>" id="download_starttime_mn_<?php echo $row_query['ord_down_id']?>" value="00" />							
								<input type="hidden" name="download_starttime_ss_<?php echo $row_query['ord_down_id']?>" id="download_starttime_ss_<?php echo $row_query['ord_down_id']?>" value="00" />							
							<?php
							}	
						?>					  </td>
						<td width="20%" align="center" valign="middle" class="listingtablestyleB_n">
						<?php
							if ($row_query['proddown_days_active']==1)
							{
								if($row_query['active_enddate']!='--')
								{
								?>
									<table width="100%" border="0">
                                      <tr>
                                        <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="5%"><input type="text" name="download_enddate_<?php echo $row_query['ord_down_id']?>" id="download_enddate_<?php echo $row_query['ord_down_id']?>" value="<?php echo $row_query['active_enddate']?>" size="10" readonly="true" /></td>
                                            <td width="95%"><a href="javascript:show_calendar('frmOrderDetails.download_enddate_<?php echo $row_query['ord_down_id']?>');" onMouseOver="window.status='Date Picker';return true;" onMouseOut="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                                          </tr>
                                          
                                        </table></td>
                                      </tr>
                                      <tr>
                                        <td width="13%" align="left">HH</td>
                                        <td width="14%" align="left">MM</td>
                                        <td width="11%" align="left">SS</td>
                                        <td width="62%" align="center">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td align="left">
										<select name="download_endtime_hr_<?php echo $row_query['ord_down_id']?>" id="download_endtime_hr_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select>
										</td>
                                        <td align="left">
										<select name="download_endtime_mn_<?php echo $row_query['ord_down_id']?>" id="download_endtime_mn_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select>
										</td>
                                        <td align="left">
										<select name="download_endtime_ss_<?php echo $row_query['ord_down_id']?>" id="download_endtime_ss_<?php echo $row_query['ord_down_id']?>">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select>
										</td>
                                        <td align="center">&nbsp;</td>
                                      </tr>
                          </table>
								<?php
								}
								else
								{
									echo "Will be set when payment is success";
								?>
									<input type="hidden" name="download_enddate_<?php echo $row_query['ord_down_id']?>" id="download_enddate_<?php echo $row_query['ord_down_id']?>" value="00-00-0000" />							
									<input type="hidden" name="download_endtime_hr_<?php echo $row_query['ord_down_id']?>" id="download_endtime_hr_<?php echo $row_query['ord_down_id']?>" value="00" />							
									<input type="hidden" name="download_endtime_mn_<?php echo $row_query['ord_down_id']?>" id="download_endtime_mn_<?php echo $row_query['ord_down_id']?>" value="00" />							
									<input type="hidden" name="download_endtime_ss_<?php echo $row_query['ord_down_id']?>" id="download_endtime_ss_<?php echo $row_query['ord_down_id']?>" value="00" />							
								<?php
								}	
							}
							else
							{
								echo 'N/A';
							?>
								<input type="hidden" name="download_enddate_<?php echo $row_query['ord_down_id']?>" id="download_enddate_<?php echo $row_query['ord_down_id']?>" value="00-00-0000" />
								<input type="hidden" name="download_endtime_hr_<?php echo $row_query['ord_down_id']?>" id="download_endtime_hr_<?php echo $row_query['ord_down_id']?>" value="00" />							
								<input type="hidden" name="download_endtime_mn_<?php echo $row_query['ord_down_id']?>" id="download_endtime_mn_<?php echo $row_query['ord_down_id']?>" value="00" />							
								<input type="hidden" name="download_endtime_ss_<?php echo $row_query['ord_down_id']?>" id="download_endtime_ss_<?php echo $row_query['ord_down_id']?>" value="00" />														
							<?php
							}							
						?>					  </td>
						<td width="15%" align="center" valign="middle" class="listingtablestyleB_n">
						<?php echo $row_query['disabled']?>					  </td>
					</tr>
				<?php
					if ($db->num_rows($ret_history))
					{
				?>
					<tr id="downloadhistory_tr_<?php echo $row_query['ord_down_id']?>" style="display:none">
					<td colspan="2" align="left">&nbsp;</td>
					<td align="left" width="35%">
					  </td>
					<td colspan="4" align="center">
						<table width="100%" cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td align="center" width="5%" class="listingtableheader">#</td>
							<td align="left" width="95%" class="listingtableheader">Downloaded On</td>
						</tr>
							<?php
							$cnt = 1;
							while ($row_history = $db->fetch_array($ret_history))
							{
							?>
									<tr>
										<td align="center" width="5%" class="listingtablestyleB_n"><?php echo $cnt++?></td>
										<td align="left" width="95%" class="listingtablestyleB_n"><?php echo $row_history['download_date']?></td>
									</tr>
							<?php
							}
							?>
							<tr>
									<td align="right" colspan="2" class="listingtablestyleB_n"><strong>Total Downloads: <?php echo ($cnt-1)?></strong></td>
						  </tr>
						</table>
					</td>
					</tr>
				<?php
					}
					
				}
				?>
		  </table>
		<?php
		}
		else
		{
		?>
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td align="center" class="subcaption">No Downloadable items found.</td>
				</tr>
				</table>
		<?php
		}
		?></td>
		</tr>
		</table>
<?php
}
// ###############################################################################################################
// 				Function which holds the display logic of promotional code details to be shown when called using ajax;
// ###############################################################################################################
function show_order_notes($order_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
		if($alert)
		{
		?>
				<tr>
				<td colspan="3" align="center" class="errormsg">
				<?php
				echo $alert;
				?>				</td>
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
								order_notes
							WHERE
								orders_order_id = $order_id
							ORDER BY
								note_add_date
									DESC";
		$ret_notes = $db->query($sql_notes);
		if($db->num_rows($ret_notes))
		{
		?>
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<?php
				while ($row_notes = $db->fetch_array($ret_notes))
				{
					$extra = get_order_status_number_to_text($row_notes['note_type']);
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
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td align="center" class="subcaption">No Notes added yet.</td>
				</tr>
				</table>
		<?php
		}
		?>		</td>
		<td width="1%" align="left">&nbsp;</td>
		<td width="48%" align="left" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr>
			<td><textarea name="txt_notes" id="txt_notes" cols="50" rows="5"></textarea></td>
			</tr>
			<tr>
			<td align="right"><input type="button" name="note_submit" value="Save Note" class="red" onclick="save_note()">
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ORDER_NOTE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			</table>		</td>
		</tr>
		</table>
<?php
}
// ###############################################################################################################
// 				Function which holds the display logic of emails related to order emails to be shown when called using ajax;
// ###############################################################################################################
function show_order_emails($order_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get the list of email related to current order
	$sql_email = "SELECT email_id,email_to,email_subject,email_headers,
							email_type,email_was_disabled,email_sendonce,email_lastsenddate
						FROM
							order_emails
						WHERE
							orders_order_id = $order_id
						ORDER BY
							email_id";
	$ret_email = $db->query($sql_email);
	if ($db->num_rows($ret_email))
	{
		//$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frm_orderdetails,\'checkbox_email[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frm_orderdetails,\'checkbox_email[]\')"/>','#','Type','Subject','Mail Disabled?','Sent Atleast Once');
		$table_headers 		= array('#','Type','Subject','Mail Disabled?','Sent Atleast Once','Details');
		$header_positions	= array('center','left','left','center','center','center',);
		$colspan 				= count($table_headers);
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
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
			$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
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
				  <table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr>
                      <td width="62%" align="left" valign="top">
					  <table width="100%" border="0" cellspacing="0" cellpadding="1">
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
						  ?></td>
                        </tr>

                        <tr>
                          <td align="left" class="subcaption listingtablestyleB_n">Subject</td>
                          <td align="left" class="listingtablestyleB_n"><?php echo $row_email['email_subject']?></td>
                        </tr>
                        <tr>
                          <td align="left" class="subcaption listingtablestyleB_n"><div id='<?php echo $row_email['email_id']?>_messagediv' <?php /*?>onclick="handle_showmessageiv('<?php echo $row_email['email_id']?>_messagetr','<?php echo $row_email['email_id']?>_messagediv')"<?php */?> title="Click here"<?php /*?> style="cursor:pointer"<?php */?>>Message<?php /*?><img src="images/right_arr.gif" /><?php */?></div></td>
                          <td align="right" class="listingtablestyleB_n"><input type="button" name="Mail_Send_button2" value="Send This Mail" class="red" onclick="resend_orderemail('<?php echo $row_email['email_id']?>')"/></td>
                        </tr>
                        <tr id="<?php echo $row_email['email_id']?>_messagetr"<?php /*?> style="display:none"<?php */?>>
                          <td colspan="2" align="left">
						  <!--<textarea name="just_display" rows="20" cols="60"><?php //echo $row_email['email_message']?></textarea>-->
						  <div class="emaildiv_cls">
						  <?php //echo $row_email['email_message']
						  	echo read_email_from_file('ord',$row_email['email_id']);
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
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
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
												order_emails_console_send
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
								$cls = ($srnlo%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
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
		</table>
	<?php
	}
}
/* Function to show the additional details text area for payment status */
function order_additionaldet_paymentstatus()
{
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
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
		  <td width="65%" align="left" valign="top"><input name="Change_submit2" type="button" class="red" id="Change_submit2" value="Click here to Change the Payment status" onclick="call_ajax_showlistall('operation_changeorderpaystatus_do')" />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_CHANGE_PAYSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		  </td>
		</tr>
	  </table>
<?php
}

/* Function which show the details on payment received change status selected */
function show_PayReceived_TakeDetails()
{
?>
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tbody>
<tr>
<td class="fontredheading">Please fill in the following details to mark the payment as Received </td>
</tr>

<tr>
<td align="left" valign="top" width="50%">
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tbody>
<tr>
  <td width="29%" align="left" class="normltdtext">Payment Method <span class="redtext">*</span></td>
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
  <td align="left" valign="top" class="normltdtext">Additional Details (Optional) </td>
  <td align="left" valign="top" class="normltdtext"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
</tr>
<tr>
<td colspan="2" align="left" class="normltdtext"><span class="redtext"><strong>Note:</strong></span> Additional Details  (if specified) will be automatically added to the notes section</td>
</tr>
</tbody></table>	      </td>
</tr>
<tr>
<td align="right" valign="top"><input name="Change_submit" class="red" id="Change_submit" value="Click here to Change the status" onClick="validate_payReceivedstatus()" type="button"></td>
</tr>
</tbody></table>
<?php	
}
/* Function which show the details on payment received change status selected */
function show_PayFailed_TakeDetails()
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
<tbody>
<tr>
<td class="fontredheading">Please specify the reason for payment failure</td>
</tr>
<tr>
<td align="left" valign="top" width="50%">
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tbody>
<tr>
<td width="25%" align="left" valign="top" class="normltdtext">Reason <span class="redtext">*</span></td>
<td width="75%" align="left" valign="top" class="normltdtext"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
</tr>
<tr>
<td colspan="2" align="left" class="normltdtext"><span class="redtext"><strong>Note:</strong></span> Payment Failure reason will be automatically added to the notes section</td>
</tr>
</tbody></table>	      </td>
</tr>
<tr>
<td align="right" valign="top"><input name="Change_submit" class="red" id="Change_submit" value="Click here to Change the status" onClick="call_ajax_showlistall('operation_changeorderpaystatus_do')" type="button"></td>
</tr>
</tbody></table>
<?php	
}
/* Function which show the details on payment on hold change status selected */
function show_PayHold_TakeDetails()
{
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
<tbody>
<tr>
<td class="fontredheading">Please specify the reason (if any)</td>
</tr>
<tr>
<td align="left" valign="top" width="50%">
<table border="0" cellpadding="1" cellspacing="0" width="100%">
<tbody>
<tr>
<td width="25%" align="left" valign="top" class="normltdtext">Reason </td>
<td width="75%" align="left" valign="top" class="normltdtext"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
</tr>
<tr>
<td colspan="2" align="left" class="normltdtext"><span class="redtext"><strong>Note:</strong></span> Payment Failure reason will be automatically added to the notes section</td>
</tr>
</tbody></table>	      </td>
</tr>
<tr>
<td align="right" valign="top"><input name="Change_submit" class="red" id="Change_submit" value="Click here to Change the status" onClick="call_ajax_showlistall('operation_changeorderpaystatus_do')" type="button"></td>
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
function order_authorise_details($order_id)
{
	global $db,$ecom_siteid;
	// Get the currency symbol and conversion rate in current order
	$sql_ord = "SELECT order_currency_symbol,order_currency_convertionrate,order_totalprice,
							order_refundamt,order_deposit_amt,order_deposit_cleared,order_status,
							order_paystatus,order_totalauthorizeamt
						FROM
							orders
						WHERE
							order_id = ".$order_id."
						LIMIT
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord 	= $db->fetch_array($ret_ord);

		//check whether partial payment is made (product deposit case)
		if ($row_ord['order_deposit_amt']>0)
		{
			if($row_ord['order_deposit_cleared']==0)
			$rem_amt	= $row_ord['order_deposit_amt'];
			else
			$rem_amt	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);//-$row_ord['order_deposit_amt'];
		}
		else
		{
			$rem_amt 	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
		}
		// taking 115% of rem_amt
		$rem_amt_per			= $rem_amt * 115/100;

		// Less the amount already authorized
		$rem_amt_per			= $rem_amt_per - $row_ord['order_totalauthorizeamt'];

		$max_auth_allowed		= print_price_selected_currency($rem_amt_per,$row_ord['order_currency_convertionrate'],'',true); // only the paid amount is set to variable
		$tot_auth				= print_price_selected_currency($row_ord['order_totalauthorizeamt'],$row_ord['order_currency_convertionrate'],'',true); // only the paid amount is set to variable
		if($tot_auth=='0.00')
		$tot_auth = 0;
	}

	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader" align="left">Authorise Payment</td>
		</tr>
		<tr>
		  <td width="31%" valign="top" align="left" class="normltdtext">Total Amount authorised till now </td>
		  <td width="69%" valign="top" align="left" class="normltdtext">
		  <?php
		  echo print_price_selected_currency($row_ord['order_totalauthorizeamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
		  ?>
		  </td>
		  </tr>

		<tr>
		  <td valign="top" align="left" class="normltdtext">Amount to be authorised </td>
		  <td valign="top" align="left"><input type="text" name="txt_authamt" id="txt_authamt" />
		  &nbsp;<span class="shoppingcartpriceB">(maximum allowed  <?php
		  echo print_price_selected_currency($max_auth_allowed,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
		  ?>)</span>
		  </td>
		  </tr>
		<tr>
		  <td valign="top" align="left" class="normltdtext">Note (optional)</td>
		  <td valign="top" align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
		</tr>
		<tr>
		  <td colspan="2" align="left" valign="top" class="normltdtext">This action is not Reversible</td>
		  </tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="AUTHORISE" />
		  <input type="hidden" name="max_auth_allowed" id="max_auth_allowed" value="<?php echo $max_auth_allowed?>" />
		  <input type="hidden" name="tot_auth" id="tot_auth" value="<?php echo $tot_auth?>" />
		  <input type="hidden" name="order_currency_symbol" id="order_currency_symbol" value="<?php echo $row_ord['order_currency_symbol']?>" />
		  <input type="hidden" name="max_auth_allowed_def" id="max_auth_allowed_def" value="<?php echo $rem_amt_per?>" />

		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Authorise" onclick="perform_capturetype('AUTHORISE')" /></td>
		  </tr>
	  </table>
	<?php
}
// ###############################################################################################################
// Function which holds the display logic of authorize amount details related to order when called using ajax;
// ###############################################################################################################
function order_authorise_amount_details($order_id,$alert='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	// Get some of the relevant details from order table
	$sql_ord = "SELECT order_currency_symbol,order_currency_convertionrate
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
	// Get the list of authorized amount related to current order
	$sql_auth = "SELECT auth_id,auth_on,auth_by,auth_amt
						FROM
							order_details_authorized_amount
						WHERE
							orders_order_id = $order_id
						ORDER BY
							auth_on DESC";
	$ret_auth = $db->query($sql_auth);
	if ($db->num_rows($ret_auth))
	{
		$table_headers 		= array('#','Date','Authorised By','Amount');
		$header_positions	= array('center','center','left','right');
		$colspan 			= count($table_headers);
	?>
		<table width="80%" border="0" cellspacing="0" cellpadding="1">
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
			$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
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
					<?php echo print_price_selected_currency($row_auth['auth_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>
					</td>
				</tr>
		<?php
		}
		?>
				<tr>
					<td colspan="3" class="shoppingcartpriceB" align="right">
					Total Authorised
					</td>
					<td align="right" class="shoppingcartpriceB">
					<?php echo print_price_selected_currency($tot_auth,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>
					</td>
				</tr>
		</table>
	<?php
	}
}
// Function to show the input to be taken from user in case of cancelling the Authenticate order
function order_cancel_note()
{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Cancel Authorised Transacation</td>
		</tr>
		<tr>
		  <td align="left" valign="top" class="normltdtext">Note (optional)</td>
		  <td align="left" valign="top"></td>
		  </tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left" class="normltdtext">This action is not Reversible</td>
            </tr>
          </table>	      </td>
		  <td width="65%" align="left" valign="top">		  </td>
		</tr>
		<tr>
		  <td align="left" valign="top">&nbsp;</td>
		  <td align="center" valign="top">
		  <input type="hidden" name="cur_paycapture" id="cur_paycapture" value="CANCEL" />
		  <input name="Change_submit" type="button" class="red" id="Change_submit" value="Click here to Cancel" onclick="perform_capturetype('CANCEL')" /></td>
		  </tr>
	  </table>
	<?php
}

function order_repeat_note()
{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Repeat Preauth Payment</td>
		</tr>
		<tr>
		  <td align="left" valign="top" class="normltdtext">Note (optional)</td>
		  <td align="left" valign="top"></td>
		  </tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left" class="normltdtext">This action is not Reversible</td>
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
function order_release_note()
{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Release Deferred Payment</td>
		</tr>
		<tr>
		  <td colspan="2" class="normltdtext">Note (optional)</td>
		</tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left" class="normltdtext">This action is not Reversible</td>
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
function order_abort_note()
{
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="2" class="shoppingcartheader">Abort Deferred Payment</td>
		</tr>
		<tr>
		  <td colspan="2" class="normltdtext">Note (optional)</td>
		</tr>
		<tr>
		  <td width="35%" align="left" valign="top">
		  <table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr>
                <td align="left"><textarea name="txt_additionalnote" id="txt_additionalnote" cols="50" rows="6"></textarea></td>
              </tr>
              <tr>
                <td align="left" class="normltdtext">This action is not Reversible</td>
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

/* Function to print the list of products remainig in order */
function show_Products_Remaining_In_Order($order_id,$row_ord,$type='main',$from_print=0)
{
	global $db,$ecom_siteid,$print_buttons,$print_buttons,$product_no_link;
	// Get the products in this order
	$sql_prods = "SELECT orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,order_qty,
						order_orgqty,product_soldprice,order_retailprice,product_costprice,order_discount,
						order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
						order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
						order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
						order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
						order_discount_group_name,order_discount_group_percentage,order_freedelivery,order_detail_discount_type,order_prom_id    
					FROM
						order_details
					WHERE
						orders_order_id = $order_id 
						AND order_qty>0";
	$ret_prods = $db->query($sql_prods);
	$show_details = false;
	if($type=='main')
	{
			$show_details = true;
	}
	elseif($type=='main_sel' and $db->num_rows($ret_prods))
	{
		$show_details = true;
	}
	elseif($type=='despatch' and $db->num_rows($ret_prods))
	{
		$show_details = true;
	}
	if($show_details == true)
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td width="62%" align="left" class="seperationtd_special">
				<?php 
				if($type=='main' or $type=='main_sel')
				{
				?>
				Products in Order
				<?php
				}
				elseif ($type=='despatch' and $db->num_rows($ret_prods))
				{
				?>
				Products Awaiting Despatch
				<?php
				}
				?>
				</td>
				<td width="38%" align="center" class="seperationtd_special">
				<?php
			if($print_buttons != 1)
			{	
				if ($db->num_rows($ret_prods))
				{
						if($type=='main' and $db->num_rows($ret_prods))
						{
							if($row_ord['order_status']!='CANCELLED')
							{
						?>
							<input type="button" name="updateqty_Submit" value="Move Items to Back Order" class="red" onclick="call_ajax_showlistall('movetobackorder_select',0)<?php /*?>validate_updateqty()<?php */?>" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_MOVE_TO_BACK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						<?php
							}
						?>
						<?php
							if($row_ord['order_status']!='CANCELLED' and $db->num_rows($ret_prods))
							{
						?>
							<input type="button" name="updateqty_Submit2" value="Cancel Items" class="red" onclick="call_ajax_showlistall('movetocancel_select_main',0)" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_MOVE_TO_CANCEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						<?php
							}
						}	
						elseif ($type=='despatch' and $db->num_rows($ret_prods))
						{
							 if ($row_ord['order_status']!='CANCELLED' ) // Order and payment status can be changed only if order status is not cancelled
							  {
								if ($row_ord['order_status']!='DESPATCHED')
								{
									if($row_ord['order_paystatus']=='Paid' or $row_ord['order_paystatus']=='Pay_Hold' or $row_ord['order_paystatus']=='free') // show the despatch option only if payment is successfull
									{
									 ?>
										<input name="despatched_Submit" type="button" class="red" id="despatched_Submit" value="Click to Despatch Products" onclick="call_ajax_showlistall('operation_despatched_sel')" />
									<?php
									}
								}
							  }
						}
					}
				}	
					?>
				</div>
				</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		$table_sel_head = ' ';
		$header_pos = ' ';
		if($print_buttons != 1) 
		{
			$table_sel_head = '<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxprod[]\')"/>';
			$header_pos     =  'center'.','.'left';
		}
		if($from_print==1)
		{
			$table_headers 		= array($table_sel_head,'Product','Retail Price','Discount','Sale Price','Qty in Order','Net');
			$header_positions	= array($header_pos,'left','right','right','center','right','right');
		}
		else
		{
			$table_headers 		= array($table_sel_head,'Product','Available?','Retail Price','Discount','Sale Price','Qty in Order','Net');
			$header_positions	= array($header_pos,'left','right','right','right','center','right','right');
		}	
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			$atleast_one = false;
			echo table_header($table_headers,$header_positions);
			if ($db->num_rows($ret_prods))
			{
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
						$srno++;
						
						$org_qty 			= $row_prods['order_orgqty'];
						$sale_price			= $row_prods['product_soldprice'];
						$disc					= $row_prods['order_discount'];
						$disc_per_item	= ($disc/$org_qty);
						$net_total			= $sale_price * $row_prods['order_qty'];
						if($row_prods['order_discount']>0)
							$cur_disc = $row_prods['order_qty'] * $disc_per_item;
						else
							$cur_disc = 0;
						$show_man_id = '';
						$show_model = '';
						// Check whether the current product still exists in products table
						$sql_check = "SELECT product_id,manufacture_id,product_default_category_id,product_model    
												FROM
												products
												WHERE
												product_id = ".$row_prods['products_product_id']."
												AND sites_site_id = $ecom_siteid
												LIMIT
												1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
							if($product_no_link!=1)
							{
								$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
								$link_req_suffix = '</a>';
							}	
							if(trim($row_check['manufacture_id'])!='')
							{
								$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
							}
							if(trim($row_check['product_model'])!='')
							{
								$show_model = ' (Model - '.stripslashes(trim($row_check['product_model'])).')';
							}
						}
						else
							$link_req = $link_req_suffix= '';
				?>
				<tr>
					<td width="6%" align="center" class="<?php echo $cls?>" valign="top">
					<?php
					if($print_buttons != 1) 
					{
						if($row_prods['order_refunded']=='N' and $row_ord['order_paystatus']!='REFUNDED') // case if item is not refunded and also the order status is not refunded
						{
							$atleast_one = true;
						?>
							<input type="checkbox" name="checkboxprod[]" id="checkboxprod[]" value="<?php echo $row_prods['orderdet_id']?>" />
						<?php
						}
						else
						{
							if($row_prods['order_refunded']=='Y')
								echo "Refunded";
						}
					}	
					?>
					</td>
					<td width="30%" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
					<?php
					if($row_prods['order_freedelivery']==1 and $product_no_link!=1)
						echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
						echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
						if($product_no_link!=1)
							show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
						$cat_str = '';
						if($ecom_siteid==62) // only for eurolabels
						{
							$cat_str = show_category($row_check['product_default_category_id']);
							if ($cat_str!='')
								echo '<br>'.$cat_str;
						}
							
					?>					</td>
					<?php
					if($from_print!=1)
					{
					?>
					<td width="10%" align="center" valign="top" class="<?php echo $cls?>">
					<?php
						if ($row_prods['order_preorder']=='N')
						{
							echo 'In Stock';
						}
						else
							echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
					?></td>
					<?php
					}
					?>
					<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					<td width="8%" align="right" valign="top" class="<?php echo $cls?>">
				<?php
					$disp_msg = '';
					if($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
					{
						$disp_msg = '';
						if ($row_prods['order_discount_type']=='custgroup')
						{
							$disp_msg = 'Customer Group Discount<br>Group: '.stripslashes($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
						}
						elseif ($row_prods['order_discount_type']=='customer')
						{
							$disp_msg = 'Customer Discount ';
						}
						elseif ($row_prods['order_discount_type']=='bulk')
						{
							$disp_msg = 'Bulk Discount ';
						}
						elseif ($row_prods['order_discount_type']=='combo')
						{
							$disp_msg = 'Combo Discount ';
						}
						elseif ($row_prods['order_discount_type']=='promotional')
						{
							$disp_msg = 'Promotional Discount ';
						}
						elseif ($row_prods['order_discount_type']=='normal')
						{
							$disp_msg = 'Normal Product Discount ';
						}
					}
					elseif($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
					{
						$disp_msg = display_discount_type($row_prods);	
					}
					// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
					  	
						echo print_price_selected_currency($cur_disc,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					  	if($print_buttons != 1 and $disp_msg!='')
					  	{
						?>
						<br /><a href="#" onmouseover ="ddrivetip('<?=$disp_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							
					<?php
						}
						if($row_prods['order_detail_discount_type']=='PRICE_PROMISE_DISC' and $print_buttons != 1) // case if discount type is price promise
						{
							 // Check whether the price promise still exists
							 $sql_prom = "SELECT prom_id 
							 				FROM 
												pricepromise 
											WHERE 
												prom_id = ".$row_prods['order_prom_id']." 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$ret_prom = $db->query($sql_prom);
							if($db->num_rows($ret_prom))
							{
							?>
								<a href="home.php?request=price_promise&fpurpose=edit&checkbox[0]=<?php echo $row_prods['order_prom_id']?>" title="Click to view the price promise details"><img border="0" src="images/price_promise.gif" title="Click to view the price promise details" style="cursor:pointer" /></a>
							<?php
							}
						}
					 ?>
				</td>
				<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				<td width="8%" align="center" valign="top" class="<?php echo $cls?>">
				<?php
					echo $row_prods['order_qty'];
				?>
				</td>
				<td width="20%" align="right" valign="top" class="<?php echo $cls?>">
				<?php 
					echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				</tr>
				<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
				<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
					<div id="orddet_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
				</td>
				</tr>
				<?php
				}
			}
			else
			{
			?>
			<tr>
				<td colspan="<?php echo $colspan?>" class="norecordredtext" align="center">
					No Items remain in order for despatch
				</td>
			</tr>	
			<?php
			}
		?>
		</table>
			</td>
		</tr>
		<?php
		}
		?>
		</table>
	<?php	
}	
/* Function to print the list of products remainig in order */
function show_Products_Placed_In_BackOrder($order_id,$row_ord,$fromprint=0)
{
	global $db,$ecom_siteid,$print_buttons;
	// Get the products  placed in back order
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,a.order_freedelivery,a.order_detail_discount_type,a.order_prom_id, 
						b.order_backorder_id ,b.backorder_qty,b.order_backorderon,b.order_backorderby   
					FROM
						order_details a,order_details_backorder b
					WHERE
						a.orders_order_id = $order_id 
						AND a.orderdet_id = b.orderdet_id ";
	$ret_prods = $db->query($sql_prods);
	$atleast_one = false;
	if ($db->num_rows($ret_prods))
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td width="62%" align="left" class="seperationtd_special">Products Placed in Back Order</td>
				<td width="38%" align="center" class="seperationtd_special">
				<div id="back_product_div">
				<?php
			if($print_buttons != 1) 
			{	
				if($row_ord['order_status']!='CANCELLED')
				{
				?>
				<input type="button" name="updateqty_Submit" value="Move Items Back to Order" class="red" onclick="call_ajax_showlistall('movebacktoorder_select',0)<?php /*?>validate_updateqty()<?php */?>" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_MOVE_TO_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				<?php
				}
				?>
				<?php
				if($row_ord['order_status']!='CANCELLED')
				{
				?>
                <input type="button" name="updateqty_Submit22" value="Cancel Items" class="red" onclick="call_ajax_showlistall('movetocancel_select_back',0)" />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ORD_DET_MOVE_TO_CANCEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
                <?php
				}
			}	
				?>
				</div>
				</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php
		
		?>
		<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		$table_sel_head = ' ';
		$header_pos = ' ';
		if($print_buttons != 1) 
		{
			$table_sel_head = '<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxbackprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxbackprod[]\')"/>';
			$header_pos     =  'center'.','.'left';
		}
		if($fromprint==1)
		{
			$table_headers 		= array($table_sel_head,'Product','Retail Price','Discount','Sale Price','BackOrder Qty','Net');
			$header_positions	= array($header_pos,'left','right','right','center','right','right');
		}
		else
		{
			$table_headers 		= array($table_sel_head,'Product','Available?','Retail Price','Discount','Sale Price','BackOrder Qty','Net');
			$header_positions	= array($header_pos,'left','right','right','right','center','right','right');

		}	
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			echo table_header($table_headers,$header_positions);
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				$srno++;
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['backorder_qty'];
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['backorder_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
				$show_man_id = '';
				$show_model	 = '';
				// Check whether the current product still exists in products table
				$sql_check = "SELECT product_id,manufacture_id,product_model   
										FROM
										products
										WHERE
										product_id = ".$row_prods['products_product_id']."
										AND sites_site_id = $ecom_siteid
										LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
					$link_req_suffix = '</a>';
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
					}
					if(trim($row_check['product_model'])!='')
					{
						$show_model = ' (Model - '.stripslashes(trim($row_check['product_model'])).')';
					}
				}
				else
					$link_req = $link_req_suffix= '';
				$atleast_one = true;
		?>
		<tr>
			<td width="6%" align="center" class="<?php echo $cls?>" valign="top">
			<? 
			if($print_buttons != 1) 
			{
			?>
			<input type="checkbox" name="checkboxbackprod[]" id="checkboxbackprod[]" value="<?php echo $row_prods['orderdet_id']?>" />
			<? } ?>
			</td>
			<td width="30%" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
			<?php
				if($row_prods['order_freedelivery']==1 and $print_buttons != 1)
					echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
				echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
				show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
			?>			</td>
			<?php
			if($fromprint!=1)
			{
			?>
			<td width="10%" align="center" valign="top" class="<?php echo $cls?>">
			<?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?></td>
			<?php
			}
			?>
			<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			<td width="8%" align="right" valign="top" class="<?php echo $cls?>">
		<?php
			$disp_msg = '';
			if($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
			{
				$disp_msg = '';
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br>Group: '.stripslashes($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
				}
				elseif ($row_prods['order_discount_type']=='customer')
				{
					$disp_msg = 'Customer Discount ';
				}
				elseif ($row_prods['order_discount_type']=='bulk')
				{
					$disp_msg = 'Bulk Discount ';
				}
				elseif ($row_prods['order_discount_type']=='combo')
				{
					$disp_msg = 'Combo Discount ';
				}
				elseif ($row_prods['order_discount_type']=='promotional')
				{
					$disp_msg = 'Promotional Discount ';
				}
				elseif ($row_prods['order_discount_type']=='normal')
				{
					$disp_msg = 'Normal Product Discount ';
				}
			}
			elseif($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
			{
				$disp_msg = display_discount_type($row_prods);	
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			echo print_price_selected_currency($cur_disc,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
			if($print_buttons != 1 and $disp_msg!='')
			{
			?>
			<br /><a href="#" onmouseover ="ddrivetip('<?=$disp_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
		<?php
			}
			if($row_prods['order_detail_discount_type']=='PRICE_PROMISE_DISC' and $print_buttons != 1) // case if discount type is price promise
			{
				 // Check whether the price promise still exists
				 $sql_prom = "SELECT prom_id 
								FROM 
									pricepromise 
								WHERE 
									prom_id = ".$row_prods['order_prom_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prom = $db->query($sql_prom);
				if($db->num_rows($ret_prom))
				{
				?>
					<a href="home.php?request=price_promise&fpurpose=edit&checkbox[0]=<?php echo $row_prods['order_prom_id']?>" title="Click to view the price promise details"><img border="0" src="images/price_promise.gif" title="Click to view the price promise details" style="cursor:pointer" /></a>
				<?php
				}
			}
			  ?>
		</td>
		<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		<td width="8%" align="center" valign="top" class="<?php echo $cls?>">
		<?php
			echo $row_prods['backorder_qty'];
		?>
		</td>
		<td width="20%" align="right" valign="top" class="<?php echo $cls?>">
		<?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			?></td>
		</tr>
		<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
		<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
			<div id="orddet_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
		</td>
		</tr>
		<?php
		}
		?>
		</table>
			</td>
		</tr>
		</table>
	<?php	
	}
}	
/* Function to print the list of products remainig in order */
function show_Products_Cancelled($order_id,$row_ord,$type='main',$fromprint=0)
{
	global $db,$ecom_siteid,$print_buttons;
	// Get the products  placed in back order
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,a.order_freedelivery,a.order_detail_discount_type,a.order_prom_id, 
						b.order_cancelled_id ,b.cancelled_qty,b.order_cancelledon,b.order_cancelledby,b.order_refunded as cancel_refunded      
					FROM
						order_details a,order_details_cancelled b
					WHERE
						a.orders_order_id = $order_id 
						AND a.orderdet_id = b.orderdet_id ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td align="left" class="seperationtd_special">Cancelled Products</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php
		
		?>
		<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		if($fromprint==1)
		{
			$table_headers 		= array('','Product','Retail Price','Discount','Sale Price','Cancelled Qty','Net');
			$header_positions	= array('center','center','right','right','right','center','right');
		}
		else
		{
			$table_headers 		= array('','Product','Available?','Retail Price','Discount','Sale Price','Cancelled Qty','Net');
			$header_positions	= array('center','left','center','right','right','right','center','right');

		}	
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			echo table_header($table_headers,$header_positions);
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				$srno++;
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['cancelled_qty'];
				$cancel_str			= '<b>Cancelled by:</b> '.getConsoleUserName($row_prods['order_cancelledby']).' <br><b>Cancelled On :</b>'.dateFormat($row_prods['order_cancelledon'],'datetime');
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['cancelled_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
				$show_man_id = '';
				$show_model = '';
				// Check whether the current product still exists in products table
				$sql_check = "SELECT product_id,manufacture_id,product_model   
										FROM
										products
										WHERE
										product_id = ".$row_prods['products_product_id']."
										AND sites_site_id = $ecom_siteid
										LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
					$link_req_suffix = '</a>';
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
					}
					if(trim($row_check['product_model'])!='')
					{
						$show_model = ' (Model - '.stripslashes(trim($row_check['product_model'])).')';
					}
				}
				else
					$link_req = $link_req_suffix= '';
		?>
		<tr>
			<td width="6%" align="center" class="<?php echo $cls?>" valign="top">
			
				<?php
				if($row_prods['cancel_refunded']=='N' and $row_ord['order_paystatus']!='REFUNDED' and $type=='main_sel') // case if item is not refunded and also the order status is not refunded
				{
				?>
					<input type="checkbox" name="checkboxcancelprod[]" id="checkboxbackprod[]" value="<?php echo $row_prods['order_cancelled_id']?>" />
				<?php
				}
				else
				{
					if($row_prods['cancel_refunded']=='Y')
						echo "Refunded";
				}
			?>
			
			</td>
			<td width="30%" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
			<?php
				if($row_prods['order_freedelivery']==1 and $print_buttons != 1)
					echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
				echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
				show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
			?>			</td>
			<?php 
			if($fromprint!=1)
			{
			?>
			<td width="10%" align="center" valign="top" class="<?php echo $cls?>">
			<?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?></td>
			<?php
			}
			?>
			<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			<td width="8%" align="right" valign="top" class="<?php echo $cls?>">
		<?php
			$disp_msg = '';
			if($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
			{
				$disp_msg = '';
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br>Group: '.stripslashes($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
				}
				elseif ($row_prods['order_discount_type']=='customer')
				{
					$disp_msg = 'Customer Discount ';
				}
				elseif ($row_prods['order_discount_type']=='bulk')
				{
					$disp_msg = 'Bulk Discount ';
				}
				elseif ($row_prods['order_discount_type']=='combo')
				{
					$disp_msg = 'Combo Discount ';
				}
				elseif ($row_prods['order_discount_type']=='promotional')
				{
					$disp_msg = 'Promotional Discount ';
				}
				elseif ($row_prods['order_discount_type']=='normal')
				{
					$disp_msg = 'Normal Product Discount ';
				}
			}
			elseif($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
			{
				$disp_msg = display_discount_type($row_prods);	
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			echo print_price_selected_currency($cur_disc,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
			if($print_buttons != 1 and $disp_msg!='')
			{
			?>
			<br /><a href="#" onmouseover ="ddrivetip('<?=$disp_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
		<?php
			}
			if($row_prods['order_detail_discount_type']=='PRICE_PROMISE_DISC' and $print_buttons != 1) // case if discount type is price promise
			{
				 // Check whether the price promise still exists
				 $sql_prom = "SELECT prom_id 
								FROM 
									pricepromise 
								WHERE 
									prom_id = ".$row_prods['order_prom_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prom = $db->query($sql_prom);
				if($db->num_rows($ret_prom))
				{
				?>
					<a href="home.php?request=price_promise&fpurpose=edit&checkbox[0]=<?php echo $row_prods['order_prom_id']?>" title="Click to view the price promise details"><img border="0" src="images/price_promise.gif" title="Click to view the price promise details" style="cursor:pointer" /></a>
				<?php
				}
			}
			  
			  
			  ?>
		</td>
		<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		<td width="8%" align="center" valign="top" class="<?php echo $cls?>">
		<?php
			echo $row_prods['cancelled_qty'];
				if($print_buttons != 1) {
		?>
		<a href="#" onmouseover ="ddrivetip('<?=$cancel_str?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<? } ?>
		</td>
		<td width="20%" align="right" valign="top" class="<?php echo $cls?>">
		<?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			?></td>
		</tr>
		<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
		<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
			<div id="orddet_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
		</td>
		</tr>
		<?php
		}
		?>
		</table>
			</td>
		</tr>
		</table>
	<?php	
	
	}
}	
/* Function to print the list of despatched products  in order */
function show_Products_Despatched($order_id,$row_ord,$type='main',$fromprint=0)
{
	global $db,$ecom_siteid,$print_buttons;
	//print_r($row_ord);
	if($type=='return') // while viewing the despatch list in returns section, the despatched which are not fully returned only will be displayed 
		$add_condition = ' AND b.despatched_returned_qty<> b.despatched_qty ';
	// Get the products  placed in back order
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,a.order_freedelivery,a.order_detail_discount_type,a.order_prom_id, 
						b.despatched_id ,b.despatched_qty,b.despatched_on,b.despatched_by,
						b.despatched_reference,b.despatched_returned_atleastone,b.despatched_returned_qty 
					FROM
						order_details a,order_details_despatched b
					WHERE
						a.orders_order_id = $order_id 
						AND a.orderdet_id = b.orderdet_id 
						$add_condition 
					ORDER BY 
						b.despatched_on DESC ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods) or $type=='despatch' or $type=='return')
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<?php
					if ($type!='return')
					{
				?>
				<tr>
				<td align="left" class="seperationtd_special">Despatched Products</td>
				</tr>
				<?php
					}
					else
					{
					?>
					<tr>
					<td align="left" class="seperationtd_special" width="60%">Despatched Products which can be returned</td>
					<td align="center" class="seperationtd_special" width="40%">
					<?php
					if($db->num_rows($ret_prods))
					{
					?>
						<input type='button' name="button_returns" id="button_returns" value="Return Despatched Products" class="red" onclick="call_ajax_showlistall('return_select',0)" />
					<?php
					}
					?>
					</td>
					</tr>
					<?php					
					}
				?>
				</table>
			</td>
		</tr>
		<?php
		if($type=='main' or $type=='main_sel') // case if showing the despatch details in main page
		{
		?>
		<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		if($fromprint==1)
		{
			$table_headers 		= array('','Product','Retail Price','Discount','Sale Price','Despatch Qty','Net');
			$header_positions	= array('center','center','right','right','right','center','right');
		}
		else
		{
			$table_headers 		= array('','Product','Available?','Retail Price','Discount','Sale Price','Despatch Qty','Net');
			$header_positions	= array('center','left','center','right','right','right','center','right');
		}	
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			echo table_header($table_headers,$header_positions);
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['despatched_qty'];
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['despatched_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
				$show_man_id ='';
				$show_model = '';
				// Check whether the current product still exists in products table
				$sql_check = "SELECT product_id,manufacture_id,product_model  
										FROM
										products
										WHERE
										product_id = ".$row_prods['products_product_id']."
										AND sites_site_id = $ecom_siteid
										LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
					$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
					$link_req_suffix = '</a>';
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
					}
					if(trim($row_check['product_model'])!='')
					{
						$show_model = ' (Model - '.stripslashes(trim($row_check['product_model'])).')';
					}
				}
				else
					$link_req = $link_req_suffix= '';
		?>
		<tr>
			<td width="6%" align="center" class="<?php echo $cls?>" valign="top">
			
				<?php if($row_prods['order_refunded']=='N' and $row_ord['order_paystatus']!='REFUNDED' and $type=='main_sel') // case if item is not refunded and also the order status is not refunded
				{
				?>
					<input type="checkbox" name="checkboxdespatchprod[]" id="checkboxdespatchprod[]" value="<?php echo $row_prods['orderdet_id']?>" />
				<?php
				}
				else
				{
					if($row_prods['order_refunded']=='Y')
						echo "Refunded";
					else
						echo $srno;
				}
				$srno++;
			?>
			</td>
			<td width="30%" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
			<?php
				if($row_prods['order_freedelivery']==1 and $print_buttons != 1)
					echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
				echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
				show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
			?>			</td>
			<?php
				if($fromprint!=1)
				{
			?>
			<td width="10%" align="center" valign="top" class="<?php echo $cls?>">
			<?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?></td>
			<?php
				}
			?>
			<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			<td width="8%" align="right" valign="top" class="<?php echo $cls?>">
		<?php
			$disp_msg = '';
			if($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
			{
				$disp_msg = '';
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br>Group: '.stripslashes($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
				}
				elseif ($row_prods['order_discount_type']=='customer')
				{
					$disp_msg = 'Customer Discount ';
				}
				elseif ($row_prods['order_discount_type']=='bulk')
				{
					$disp_msg = 'Bulk Discount ';
				}
				elseif ($row_prods['order_discount_type']=='combo')
				{
					$disp_msg = 'Combo Discount ';
				}
				elseif ($row_prods['order_discount_type']=='promotional')
				{
					$disp_msg = 'Promotional Discount ';
				}
				elseif ($row_prods['order_discount_type']=='normal')
				{
					$disp_msg = 'Normal Product Discount ';
				}
			}
			elseif($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
			{
				$disp_msg = display_discount_type($row_prods);	
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
						
			echo print_price_selected_currency($cur_disc,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
			if($print_buttons != 1 and $disp_msg!='')
			{
			?>
			<br /><a href="#" onmouseover ="ddrivetip('<?=$disp_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				
		<?php
			}
			if($row_prods['order_detail_discount_type']=='PRICE_PROMISE_DISC' and $print_buttons != 1) // case if discount type is price promise
			{
				 // Check whether the price promise still exists
				 $sql_prom = "SELECT prom_id 
								FROM 
									pricepromise 
								WHERE 
									prom_id = ".$row_prods['order_prom_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prom = $db->query($sql_prom);
				if($db->num_rows($ret_prom))
				{
				?>
					<a href="home.php?request=price_promise&fpurpose=edit&checkbox[0]=<?php echo $row_prods['order_prom_id']?>" title="Click to view the price promise details"><img border="0" src="images/price_promise.gif" title="Click to view the price promise details" style="cursor:pointer" /></a>
				<?php
				}
			}
			  
			  ?>
		</td>
		<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		<td width="8%" align="center" valign="top" class="<?php echo $cls?>">
		<?php
			echo $row_prods['despatched_qty'];
		?>
		</td>
		<td width="20%" align="right" valign="top" class="<?php echo $cls?>">
		<?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			?></td>
		</tr>
		<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
		<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
			<div id="orddet_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
		</td>
		</tr>
		<?php
		}
		?>
		</table>
			</td>
		</tr>
		<?php
		}
		elseif($type=='despatch' or $type=='return') // case of show the despatch details in despatch tab or returns tabe 
		{
		?>
			<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		if ($type=='despatch')
		{
			$table_headers 		= array('#','Date','Product','Despatch Qty','Sale Price','Reference #','Despatched By','Action');
			$header_positions	= array('center','center','left','center','right','left','left','center');
		}
		elseif ($type=='return')
		{
			$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxdespatchprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxdespatchprod[]\')"/>','Date','Product','Despatch Qty','Sale Price','Reference #','Despatched By');
			$header_positions	= array('center','center','left','center','right','left','left');
		}	
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			echo table_header($table_headers,$header_positions);
			if($db->num_rows($ret_prods))
			{
				while ($row_prods = $db->fetch_array($ret_prods))
				{
					
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['despatched_qty'];
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['despatched_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
				$show_man_id = '';
				// Check whether the current product still exists in products table
				$sql_check = "SELECT product_id,manufacture_id  
										FROM
										products
										WHERE
										product_id = ".$row_prods['products_product_id']."
										AND sites_site_id = $ecom_siteid
										LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
					$link_req_suffix = '</a>';
					$row_check = $db->fetch_array($ret_check);
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
					}
				}
				else
					$link_req = $link_req_suffix= '';
		?>
		<tr>
			<td width="6%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
			
				<?php
				if($type=='return') // case if showing the despatch details in returns tab
				{
				i?>
					<input type="checkbox" name="checkboxdespatchprod[]" id="checkboxdespatchprod[]" value="<?php echo $row_prods['despatched_id']?>" />
				<?php
				}
				else
					echo $srno;
				$srno++;
			?>			</td>
			<td width="10%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
			<?php echo dateFormat($row_prods['despatched_on'],'datetime_break');?>			</td>
			<td width="25%" rowspan="2" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?>&nbsp;
			<?php
				if($row_prods['order_freedelivery']==1 and $print_buttons != 1)
					echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
				echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
				show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
			?>
	</td>
			<td width="8%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
		<?php
			echo ($row_prods['despatched_qty']-$row_prods['despatched_returned_qty']);
		?>		</td>
			<td width="10%" rowspan="2" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			<td width="15%" align="left" valign="top" class="<?php echo $cls?>">
				<?php
					echo stripslashes($row_prods['despatched_reference']);
				?>
		  </td>
			<td width="15%" rowspan="2" align="left" valign="top" class="<?php echo $cls?>">
		<?php
			echo getConsoleUserName($row_prods['despatched_by']);;
		?>		</td>
		<?php
		if($type=='despatch')
		{
		?>
			<td width="20%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
			<?php 
			if($row_prods['despatched_returned_atleastone']=='N') // Show the cancel despatch link only if no returns happened against current despatch
			{
			?>
				<a href="javascript:void(0)" onclick="validate_despatch_cancel('<?php echo $row_prods['despatched_id']?>')" class="edittextlink">Cancel Despatch</a>
			<?php
			}
			else
				echo '-- No Action Allowed --';
			?>			</td>
		<?php
		}
		?>
		</tr>
		<tr>
		  <td align="left" valign="middle" class="<?php echo $cls?>" style="height:20px; cursor:pointer">
		  <?php
		  // Check whether any reason added for despatch
			$sql_note = "SELECT note_text 
									FROM 
										order_notes 
									WHERE 
										note_type = 6 
										AND note_related_id = ".$row_prods['despatched_id']." 
									LIMIT 
										1";
			$ret_note = $db->query($sql_note);
			if ($db->num_rows($ret_note))
			{
				$row_note = $db->fetch_array($ret_note);
				$note 		= $row_note['note_text'];
				echo '<div id="notereason_'.$row_prods['despatched_id'].'" align="left" onclick="handle_despatch_note('.$row_prods['despatched_id'].')"><b>View Despatch note</b><img src="images/right_arr.gif" border="0" /></div>';
			}					
			else
				$note = '';
		  ?>
		  </td>
		  </tr>
		  <tr id="notereason_<?php echo $row_prods['despatched_id']?>_div" style="display:none">
		  <td colspan="5">
		  </td>
		  <td colspan="3" class="<?php echo $cls?>">
		  <?php echo stripslashes(nl2br($note))?>
		  </td>
		<?php
		
				}
			}	
			else // will reach here only if type is despatch and also nothing to despatch
			{
		?>
			<tr>
				<td colspan="<?php echo $colspan ?> "align="center" class="listingtablestyleB">
				<span class="redtext">
				No Despatch details found.</span>				</td>
			</tr>	
		<?php	
			}
		?>
		</table>
			</td>
		</tr>
		<?php	
		}
		?>
		</table>
	<?php	
	
	}
	else
	{
		if($type=='despatch')
		{
		?>
		
		<?php
		}
	}
}	
/* Function to print the list of returned products  in order */
function show_Products_Returned($order_id,$row_ord,$type='main')
{
	global $db,$ecom_siteid,$print_buttons;
	// Get the products  placed in back order
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,a.order_freedelivery,a.order_detail_discount_type,a.order_prom_id, 
						b.return_id ,b.order_details_despatched_despatch_id,b.return_qty,b.return_on,b.return_by,
						b.return_type,b.return_reason        
					FROM
						order_details a,order_details_return b,order_details_despatched c
					WHERE
						a.orders_order_id = $order_id 
						AND a.orderdet_id = c.orderdet_id 
						AND b.order_details_despatched_despatch_id =c.despatched_id 
						$add_condition 
					ORDER BY 
						b.return_on DESC ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
	?>
		<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
				<tr>
				<td align="left" class="seperationtd_special">Order Returns</td>
				</tr>
				</table>
			</td>
		</tr>

			<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
			$table_headers 		= array('#','Return Date','Product','Return Qty','Return By');
			$header_positions	= array('center','center','left','center','left');
			$colspan 				= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			echo table_header($table_headers,$header_positions);
			if($db->num_rows($ret_prods))
			{
				while ($row_prods = $db->fetch_array($ret_prods))
				{
					
				$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
				$show_man_id = '';
				$show_model = '';
				// Check whether the current product still exists in products table
				$sql_check = "SELECT product_id,manufacture_id,product_model   
										FROM
										products
										WHERE
										product_id = ".$row_prods['products_product_id']."
										AND sites_site_id = $ecom_siteid
										LIMIT
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edittextlink">';
					$link_req_suffix = '</a>';
					$row_check = $db->fetch_array($ret_check);
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
					}
					if(trim($row_check['manufacture_id'])!='')
					{
						$show_model = ' (Model - '.stripslashes(trim($row_check['product_model'])).')';
					}
				}
				else
					$link_req = $link_req_suffix= '';
		?>
			<tr>
			<td width="5%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
				<?php
				echo $srno;
				$srno++;
				?>			</td>
			<td width="8%" rowspan="2" align="center" valign="top" class="<?php echo $cls?>">
				<?php echo dateFormat($row_prods['return_on'],'datetime_break');?>			</td>
			<td width="20%" rowspan="2" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
				<?php
				if($row_prods['order_freedelivery']==1 and $print_buttons != 1)
					echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
				echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
				show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
				?>			</td>
			<td width="8%" align="center" valign="top" class="<?php echo $cls?>"  rowspan="2">
			<?php
				echo $row_prods['return_qty'];
				$return_type = ($row_prods['return_type']=='STK_BACK')?'Returned to Stock':'Marked as Damaged';
				echo '<br/><span class="homecontentusertabletdA">'.$return_type.'</span>';
			?>			</td>
			<td width="15%" align="left" valign="top" class="<?php echo $cls?>" >
				<?php
				echo getConsoleUserName($row_prods['return_by']);;
				?>			</td>
			</tr>
			<tr>
			  <td align="left" valign="top" class="<?php echo $cls?>">
			  <?php 
			  if($row_prods['return_reason'])
			  {
			  ?>
			  	<div id="return_reason_div_<?php echo $row_prods['return_id']?>" align="left" onclick="handle_return_reason(<?php echo $row_prods['return_id']?>)" style="cursor:pointer">
				<b>View Return reason</b><img src="images/right_arr.gif" border="0" />
				</div>
			<?php
			}
			?>	
			  </td>
			  </tr>
			  <?php 
				  if($row_prods['return_reason'])
				  {
			  ?>
				  <tr id="return_reason_tr_<?php echo $row_prods['return_id']?>" style="display:none">
				  <td colspan="4"></td>
				  <td  class="<?php echo $cls?>" colspan="1">
				  <?php
					echo stripslashes(nl2br($row_prods['return_reason']));
					?>
				  </td>
				  </tr>
		<?php
					}
				}
			}	
			else // will reach here only if type is despatch and also nothing to despatch
			{
		?>
				<tr>
					<td colspan="<?php echo $colspan ?> "align="center" class="listingtablestyleB">
						<span class="redtext">No Order returns found.</span>					</td>
				</tr>	
		<?php	
			}
		?>
		</table>
		</td>
		</tr>
		</table>
	<?php	
	
	}
}	
/* Function to show the order totals */
function show_OrderTotals($order_id,$row_ord)
{
	global $db,$ecom_siteid,$print_buttons,$ecom_allpricewithtax;
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">Sub Total</td>
		<td width="24%" colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_subtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">
		<?php 
		if($row_ord['order_freedeliverytype'] !='None' and trim($row_ord['order_freedeliverytype']) !='' )
			echo ' <span class="redtext"><strong>[</strong>'.getFreedeliveryCaption($row_ord['order_freedeliverytype']).'<strong>]</strong> </span>';
		?>+ Total Delivery Charge</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">
		<?php
		if($print_buttons!=1) 
		{ 
			if($row_ord['order_specialtax_calculation']==1 and $row_ord['order_specialtax_orgtotalamt']>0 and $ecom_allpricewithtax==1)
			{
			?>
				<div style="background-color:#CEDDF4;float:left;width:180px;color:#000000;text-align:left;padding-left:15px;padding-bottom:5px;padding-top:5px;">Total Tax Payable: <?php echo print_price_selected_currency($row_ord['order_specialtax_orgtotalamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>&nbsp;&nbsp;&nbsp;<?php /*?><a href="home.php?request=order_tax_report&fpurpose=ord_taxdetails&edit_id=<?php echo $order_id?>" title="Click to view the tax details"?><img title="Click to view details" src='images/contacts.gif' border="0" style="padding-top:3px"/></a><?php */?></div>
			<?php
			}
		}
		if($row_ord['order_specialtax_calculation']!=1)
		{
		?>
		+ Total Tax
		<?php
		}
		?>
		</td>
		<td colspan="2" align="right" class="shoppingcartpriceB">
		<?php
		if($row_ord['order_specialtax_calculation']!=1)
		{
		?>
		<?php echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>
		<?php
	}
		?>
		</td>
	</tr>
	<?PHP if($row_ord['order_giftwraptotal']>0) { ?>
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Gift Wrap Charge</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_giftwraptotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	
	<?PHP 
		}
	if($row_ord['gift_vouchers_voucher_id']>0) {
	$sql = "SELECT voucher_value_used FROM order_voucher WHERE orders_order_id='".$row_ord['order_id']."'";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	$usedprice = $row['voucher_value_used'];
	?>
	<tr>
	  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;- Total Gift Voucher Used</td>
	  <td colspan="2" align="right" class="shoppingcartpriceB">&nbsp;<?PHP echo print_price_selected_currency($usedprice,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true) ?></td>
	  </tr>
	 <?PHP } 
	if($row_ord['promotional_code_code_id']>0) {
	$sql = "SELECT code_lessval FROM order_promotional_code WHERE orders_order_id='".$row_ord['order_id']."'";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	$usedprice = $row['code_lessval'];
	if($usedprice > 0) {
	?>
	<tr>
	  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;- Total Promotional Code Discount</td>
	  <td colspan="2" align="right" class="shoppingcartpriceB">&nbsp;<?PHP echo print_price_selected_currency($usedprice,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true) ?></td>
	  </tr>
	 <?PHP }  } ?> 
	<?php
		if($row_ord['order_customer_discount_value']>0) // Check whether discount exists
		{
			if($row_ord['order_customer_or_corporate_disc']=='CUST')
			{
				if($row_ord['order_customer_discount_type']=='Disc_Group')
				$caption = 'Customer Group Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			else
				$caption = 'Customer Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			}
			elseif($row_ord['order_customer_or_corporate_disc']=='CORP')
			{
				$caption = 'Corporate Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			}
	?>
	
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">- <?php echo $caption?></td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_customer_discount_value'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<?php
		}
	?>
	<?php
		if($row_ord['order_bonuspoint_discount']>0) // Check whether discount due to bonus points exists
		{
	?>
	<tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">- Bonus Points Discount (<?php echo $row_ord['order_bonuspoints_used']?> used)</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_bonuspoint_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<?php
		}
	?>
	<tr>
	  <td colspan="6" align="center" class="shoppingcartpriceB">
		<?  
		 /* Donate bonus Start */
		 if($row_ord['order_bonuspoint_inorder']>0) 
		{
			echo "<span class='homecontentusertabletdA'>Bonus Points Earned: ".$row_ord['order_bonuspoint_inorder'].'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		if($row_ord['order_bonuspoints_donated']>0) 
		{
			echo "<span class='homecontentusertabletdA'>Bonus Points Donated: ".$row_ord['order_bonuspoints_donated'].'</span>';
		}
		 /* Donate bonus End */
		?>
		  &nbsp;</td>
	  <td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
	  </tr>
	<tr>
		<td colspan="6" align="right" class="listingtableheader">Grand Total </td>
		<td colspan="2" align="right" class="listingtableheader"><?php echo print_price_selected_currency($row_ord['order_totalprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<tr>
		  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		  <td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
	  </tr>
	<?php
		if($row_ord['order_refundamt']>0) // Check whether deposit exists and not cleared yet
		{
	?>
		<tr>
			<td colspan="6" align="right" class="shoppingcartpriceB" style="cursor:pointer" onclick="handle_tabs('refund_tab_td','order_refund')">Total Refunded</a></td>
			<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_refundamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		</tr>
		<tr>
			<td colspan="6" align="right" class="listingtableheader">Total Remaining after Refund</td>
			<td colspan="2" align="right" class="listingtableheader"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_refundamt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		</tr>
		<tr>
		  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		  <td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
	  </tr>
	<?php
		}
	?>
	<?php
		if($row_ord['order_deposit_amt']>0 and $row_ord['order_deposit_cleared']!=1) // Check whether deposit exists and not cleared yet
		{
	?>
			<tr>
				<td colspan="6" align="right" class="shoppingcartpriceB">Product Deposit Amount</td>
				<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deposit_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			</tr>
			<tr>
			  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
			  <td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
	  </tr>
			<tr>
			<tr>
				<td colspan="6" align="right" class="shoppingcartpriceB">
				<div id="productdeposit_div">
				<span class='homecontentusertabletdA'>Amount Remaining to be Released</span>
			
				</div></td>
				<td colspan="2" align="right" class="shoppingcartpriceB"><span class="homecontentusertabletdA"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></span></td>
			</tr>
			<tr>
			<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
			<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
			</tr>
	<?php
		}
	?>
	</table>
<?php	
}
/* Function to list the variables and messages for a given item in order */
function get_ProductVarandMessage($order_id,$orderdet_id)
{
	global $db,$ecom_siteid;
	$ret_val = '';
	// Check whether any variables exists for current product in order_details_variables
	$sql_var = "SELECT var_name,var_value
						FROM
							order_details_variables
						WHERE
							orders_order_id = $order_id
							AND order_details_orderdet_id =".$orderdet_id;
	$ret_var = $db->query($sql_var);
	$cnts = 1;
	if ($db->num_rows($ret_var))
	{
		while ($row_var = $db->fetch_array($ret_var))
		{
			if($cnts>0)
			$ret_val .= "<br>";
			$cnts++;
			$ret_val .= '<strong>'.stripslashes($row_var['var_name']).': </strong>'.stripslashes($row_var['var_value']);
		}
	}
	// Check whether any variables messages exists for current product in order_details_messages
	$sql_msg = "SELECT message_caption,message_value
							FROM
								order_details_messages
							WHERE
								orders_order_id = $order_id
								AND order_details_orderdet_id =".$orderdet_id;
	$ret_msg = $db->query($sql_msg);
	if ($db->num_rows($ret_msg))
	{
		while ($row_msg = $db->fetch_array($ret_msg))
		{
			if($cnts>0)
			$ret_val .= "<br>";
			$cnts++;
			$ret_val .= '<strong>'.stripslashes($row_msg['message_caption']).':</strong> '.stripslashes($row_msg['message_value']);
		}
	}
	return $ret_val;
}
/* Function to show the list of items to be moved to backorder in div */
function move_to_BackOrder_Select($order_id,$prod_sel_arr)
{
	global $db,$ecom_siteid;
	// Get the order details related to current order 
	$sql_prods = "SELECT orderdet_id,products_product_id,product_name,order_qty
							FROM
								order_details
							WHERE
								orders_order_id = $order_id 
								AND orderdet_id IN(".implode(',',$prod_sel_arr).") 
								AND order_qty>0";
	$ret_prods = $db->query($sql_prods);
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="4" align="left" class="shoppingcartheader">Please select the quantity of items to be placed in Back Order </td>
		</tr>
		<tr>
		  <td width="6%" align="center" class="listingtableheader">#</td>
		  <td width="57%" align="left" class="listingtableheader">Product</td>
		  <td width="18%" align="center" class="listingtableheader"> Qty in Order </td>
		  <td width="19%" align="center" class="listingtableheader">Qty to be moved </td>
		</tr>
		<?php
			$exists = false;
			if ($db->num_rows($ret_prods))
			{
				$srno = 1;
				$exists = true;
				while ($row_prods = $db->fetch_array($ret_prods))
				{
		?>
				<tr>
					<td align="center" valign="top"  class="listingtablestyleB"><?php echo $srno++?></td>
					<td align="left" class="listingtablestyleB"><strong><?php echo stripslashes($row_prods['product_name'])?></strong>
					<?php echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id'])?></td>
					<td align="center" class="listingtablestyleB"><?php echo $row_prods['order_qty']?></td>
					<td align="center" class="listingtablestyleB">
					<select name="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>"  id="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>">
					<?php
						for($i=$row_prods['order_qty'];$i>0;$i--)
						{
					?>
							<option value="<?php echo $i?>"><?php echo $i?></option>
					<?php
						}
					?>
					</select>
					</td>
				</tr>
		<?php
				}
			}
		?>
		<tr>
		  <td colspan="4" align="center">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="45%" align="right"><input name="Submit3" type="button" class="red" value="Cancel" onclick="document.getElementById('moveto_backorder_div').style.display='none'" /></td>
              <td width="10%">&nbsp;</td>
              <td width="45%" align="left"><input name="Button" type="button" class="red" value="Done" onclick="call_ajax_showlistall('movetobackorder_do',0)" /></td>
            </tr>
          </table></td>
		</tr>
</table>
<?php	
}
/* Function to show the list of items to be moved  back to order in div */
function move_back_ToOrder_Select($order_id,$prod_sel_arr)
{
	global $db,$ecom_siteid;
	// Get the order details related to current order 
	$sql_prods = "SELECT a.orderdet_id,a.products_product_id,a.product_name,a.order_qty,b.backorder_qty
							FROM
								order_details a,order_details_backorder b
							WHERE
								a.orders_order_id = $order_id 
								AND a.orderdet_id = b.orderdet_id 
								AND a.orderdet_id IN(".implode(',',$prod_sel_arr).") ";
	$ret_prods = $db->query($sql_prods);
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="4" align="left" class="shoppingcartheader">Please select the quantity of items to be placed back in Order </td>
		</tr>
		<tr>
		  <td width="6%" align="center" class="listingtableheader">#</td>
		  <td width="57%" align="left" class="listingtableheader">Product</td>
		  <td width="18%" align="center" class="listingtableheader"> Qty in Back Order </td>
		  <td width="19%" align="center" class="listingtableheader">Qty to be moved </td>
		</tr>
		<?php
			$exists = false;
			if ($db->num_rows($ret_prods))
			{
				$srno = 1;
				$exists = true;
				while ($row_prods = $db->fetch_array($ret_prods))
				{
		?>
				<tr>
					<td align="center" valign="top"  class="listingtablestyleB"><?php echo $srno++?></td>
					<td align="left" class="listingtablestyleB"><strong><?php echo stripslashes($row_prods['product_name'])?></strong>
					<?php echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id'])?></td>
					<td align="center" class="listingtablestyleB"><?php echo $row_prods['backorder_qty']?></td>
					<td align="center" class="listingtablestyleB">
					<select name="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>"  id="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>">
					<?php
						for($i=$row_prods['backorder_qty'];$i>0;$i--)
						{
					?>
							<option value="<?php echo $i?>"><?php echo $i?></option>
					<?php
						}
					?>
					</select>
					</td>
				</tr>
		<?php
				}
			}
		?>
		<tr>
		  <td colspan="4" align="center">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="45%" align="right"><input name="Submit3" type="button" class="red" value="Cancel" onclick="document.getElementById('moveto_backorder_div').style.display='none'" /></td>
              <td width="10%">&nbsp;</td>
              <td width="45%" align="left"><input name="Button" type="button" class="red" value="Done" onclick="call_ajax_showlistall('movebacktoorder_do',0)" /></td>
            </tr>
          </table></td>
		</tr>
</table>
<?php	
}
/* Function to show the list of items to be moved to cancelled in div */
function move_to_Cancel_Select($order_id,$prod_sel_arr,$cancel_src)
{
	global $db,$ecom_siteid;
	if ($cancel_src=='main')
	{
		// Get the order details related to current order 
		$sql_prods = "SELECT orderdet_id,products_product_id,product_name,order_qty
								FROM
									order_details
								WHERE
									orders_order_id = $order_id 
									AND orderdet_id IN(".implode(',',$prod_sel_arr).") 
									AND order_qty>0";
		$ret_prods = $db->query($sql_prods);
	}
	elseif($cancel_src=='back')
	{
		$sql_prods = "SELECT a.orderdet_id,a.products_product_id,a.product_name,b.backorder_qty as order_qty
							FROM
								order_details a,order_details_backorder b
							WHERE
								a.orders_order_id = $order_id 
								AND a.orderdet_id = b.orderdet_id 
								AND a.orderdet_id IN(".implode(',',$prod_sel_arr).") ";
		$ret_prods = $db->query($sql_prods);
	}	
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		  <td colspan="4" align="left" class="shoppingcartheader">Please select the quantity of items to be Cancelled </td>
		</tr>
		<tr>
		  <td width="6%" align="center" class="listingtableheader">#</td>
		  <td width="57%" align="left" class="listingtableheader">Product</td>
		  <td width="18%" align="center" class="listingtableheader"> Qty in Order </td>
		  <td width="19%" align="center" class="listingtableheader">Qty to be Cancelled </td>
		</tr>
		<?php
			$exists = false;
			if ($db->num_rows($ret_prods))
			{
				$srno = 1;
				$exists = true;
				while ($row_prods = $db->fetch_array($ret_prods))
				{
		?>
				<tr>
					<td align="center" valign="top"  class="listingtablestyleB"><?php echo $srno++?></td>
					<td align="left" class="listingtablestyleB"><strong><?php echo stripslashes($row_prods['product_name'])?></strong>
					<?php echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id'])?></td>
					<td align="center" class="listingtablestyleB"><?php echo $row_prods['order_qty']?></td>
					<td align="center" class="listingtablestyleB">
					<select name="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>"  id="cbo_cancel_move_<?php echo $row_prods['orderdet_id']?>">
					<?php
						for($i=$row_prods['order_qty'];$i>0;$i--)
						{
					?>
							<option value="<?php echo $i?>"><?php echo $i?></option>
					<?php
						}
					?>
					</select>
					</td>
				</tr>
		<?php
				}
			}
		?>
		<tr>
		  <td colspan="4" align="center" class="redtext"><strong>Note:</strong> This action is not reversible</td>
		</tr>
		<tr>
		  <td colspan="4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="45%" align="right"><input name="Submit3" type="button" class="red" value="Close" onclick="document.getElementById('moveto_backorder_div').style.display='none'" /></td>
              <td width="10%">&nbsp;</td>
              <td width="45%" align="left"><input name="Button" type="button" class="red" value="Done" onclick="call_ajax_showlistall('movetocancel_do',0)" />
			  </td>
            </tr>
          </table></td>
		</tr>
</table>
<?php	
}


// ###############################################################################################################
// 				Function which holds the display logic of billing address 
// ###############################################################################################################
function show_billing_address($order_id,$fromprint=0)
{
	global $db,$ecom_siteid,$ecom_hostname,$call_src,$show_order_details;
	$sql_ord = "SELECT customers_customer_id,order_custtitle,order_custfname,order_custmname,order_custsurname,order_custcompany,
							order_buildingnumber,order_street,order_city,order_state,order_country,
							order_custpostcode,order_custphone,order_custfax,order_custmobile,
							order_custemail,order_notes
					FROM
						orders
					WHERE
						order_id = $order_id
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	$cls = 'listingtablestyleB_n';
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
		$max_cols	= 2;
		$cur_col	= 0;
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Top';
		$show_header	= 1;
		if($call_src=='from_details_page')
		{
			include 'includes/orders/show_dynamic_fields_orders.php';
		}	
		else 
		{
			if($show_order_details==1) {
				include 'show_dynamic_fields_orders.php';
			} else {
				include '../includes/orders/show_dynamic_fields_orders.php';
			}
		}	
		if ($cur_col<$max_cols and $cur_col>0)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>
			<tr>
				<td colspan="2" align="left" class="shoppingcartheader" valign="middle">Billing Address
				<?php 
          	if($row_ord['customers_customer_id']>0 and !$fromprint==1)
          	{	
				// Check whether customer exists
				$sql_cust_exist = "SELECT customer_id 
									FROM 
										customers 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND customer_id =".$row_ord['customers_customer_id']." 
									LIMIT 
										1";
				$ret_cust_exist = $db->query($sql_cust_exist);
				if ($db->num_rows($ret_cust_exist))
				{
          ?>
					  <img src="images/cust-icon.gif" align="Registered" border="0" alt="Registered Customer" title="Registered Customer"/>
		  <?php
		  		}
          	}
		  ?>	
				</td>
			</tr>
			<?php

			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'TopInStatic';
			$show_header	= 0;
			$max_cols		= 2;
			$cur_col		= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else
			{
				if($show_order_details==1)
				{
					include 'show_dynamic_fields_orders.php';
				}
				else
				{
					include '../includes/orders/show_dynamic_fields_orders.php';
				}
			}

			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM
											general_settings_site_checkoutfields
										WHERE
											sites_site_id = $ecom_siteid
											AND field_hidden = 0
											AND field_type = 'PERSONAL'
										ORDER BY
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$srno = 0;
				$show_caption_array = $show_value_array = array();
				$name = stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname']).' '.stripslashes($row_ord['order_custmname']).' '.stripslashes($row_ord['order_custsurname']);
				// The captions and values to be displayed are moved to 2 different arrays
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkout_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkout_fname' and $row_checkout['field_key']!= 'checkout_mname' and $row_checkout['field_key'] != 'checkout_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						if($row_checkout['field_key']=='checkout_email' and $fromprint==0)
							$value	= '<a style="color:#FF0000" href="mailto:'.stripslashes($row_ord[$row_checkout['field_orgname']]).'">'.stripslashes($row_ord[$row_checkout['field_orgname']]).'</a>';
						else
							$value	= stripslashes($row_ord[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						$show_caption_array[] 	= $caption;
						$show_value_array[] 	= ': '.$value;
					}
				}
				// Find the number of items in the array and calculate the number of items to be there in each of the arrays
				$max_cnt = round(count($show_caption_array)/2);
				if (count($show_caption_array)>0)
				{
					$splt_caption_array = array_chunk($show_caption_array,$max_cnt,false);
					$splt_value_array 	= array_chunk($show_value_array,$max_cnt,false);
				}
				// Displaying the values in both the arrays using the following loop
				for($i=0;$i<$max_cnt;$i++)
				{	
					$cls = ($i%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				?>
					<tr>	
						<td align="left" width="40%" class="<?php echo $cls?>">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="left" width="25%" class="subcaption">
								<?php echo $splt_caption_array[0][$i]?>
								</td>
								<td align="left" valign="middle" class="normltdtext">
								<?php echo $splt_value_array[0][$i]?>
								</td>
							</tr>
							</table>
						</td>
						<td align="left" width="50%" class="<?php echo $cls?>">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="left" width="25%" class="subcaption">
								<?php echo $splt_caption_array[1][$i]?>
								</td>
								<td align="left" valign="middle" class="normltdtext">
								<?php echo $splt_value_array[1][$i]?>
								</td>
							</tr>
							</table>
						</td>
					</tr>
				<?php
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'BottomInStatic';
			$show_header	= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else 
			{
				if($show_order_details==1) {
					include 'show_dynamic_fields_orders.php';
				} else {
					include '../includes/orders/show_dynamic_fields_orders.php';
				}
			}	
			if ($cur_col<$max_cols and $cur_col>0)
				echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>
		</table>
	<?php
}

// ###############################################################################################################
// 				Function which holds the display logic of showing both billing address and delivery addresses
// ###############################################################################################################
function show_billing_address_and_delivery_address($order_id,$fromprint=0)
{
	global $db,$ecom_siteid,$ecom_hostname,$call_src,$show_order_details;
	$sql_ord = "SELECT customers_customer_id,order_custtitle,order_custfname,order_custmname,order_custsurname,order_custcompany,
							order_buildingnumber,order_street,order_city,order_state,order_country,
							order_custpostcode,order_custphone,order_custfax,order_custmobile,
							order_custemail,order_notes
					FROM
						orders
					WHERE
						order_id = $order_id
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	$cls = 'listingtablestyleB_n';
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		<td width="50%" align="left" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
		$max_cols	= 1;
		$cur_col	= 0;
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Top';
		$show_header	= 1;
		if($call_src=='from_details_page')
		{
			include 'includes/orders/show_dynamic_fields_orders.php';
		}	
		else 
		{
			if($show_order_details==1) {
				include 'show_dynamic_fields_orders.php';
			} else {
				include '../includes/orders/show_dynamic_fields_orders.php';
			}
		}	
		if ($cur_col<$max_cols and $cur_col>0)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>
			<tr>
				<td colspan="2" align="left" class="shoppingcartheader" valign="middle">Billing Address
				<?php 
          	if($row_ord['customers_customer_id']>0 and !$fromprint==1)
          	{	
				// Check whether customer exists
				$sql_cust_exist = "SELECT customer_id 
									FROM 
										customers 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND customer_id =".$row_ord['customers_customer_id']." 
									LIMIT 
										1";
				$ret_cust_exist = $db->query($sql_cust_exist);
				if ($db->num_rows($ret_cust_exist))
				{
          ?>
					  <img src="images/cust-icon.gif" align="Registered" border="0" alt="Registered Customer" title="Registered Customer"/>
		  <?php
		  		}
          	}
		  ?>	
				</td>
			</tr>
			<?php

			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'TopInStatic';
			$show_header	= 0;
			$max_cols		= 1;
			$cur_col		= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else
			{
				if($show_order_details==1)
				{
					include 'show_dynamic_fields_orders.php';
				}
				else
				{
					include '../includes/orders/show_dynamic_fields_orders.php';
				}
			}

			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM
											general_settings_site_checkoutfields
										WHERE
											sites_site_id = $ecom_siteid
											AND field_hidden = 0
											AND field_type = 'PERSONAL'
										ORDER BY
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$srno = 0;
				$show_caption_array = $show_value_array = array();
				$name = stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname']).' '.stripslashes($row_ord['order_custmname']).' '.stripslashes($row_ord['order_custsurname']);
				// The captions and values to be displayed are moved to 2 different arrays
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkout_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkout_fname' and $row_checkout['field_key']!= 'checkout_mname' and $row_checkout['field_key'] != 'checkout_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						//$value	= stripslashes($row_ord[$row_checkout['field_orgname']]);
						if($row_checkout['field_key']=='checkout_email' and $fromprint==0)
							$value	= '<a style="color:#FF0000" href="mailto:'.stripslashes($row_ord[$row_checkout['field_orgname']]).'">'.stripslashes($row_ord[$row_checkout['field_orgname']]).'</a>';
						else
							$value	= stripslashes($row_ord[$row_checkout['field_orgname']]);
						
						
						
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						$show_caption_array[] 	= $caption;
						$show_value_array[] 	= ': '.$value;
					}
				}
				// Find the number of items in the array and calculate the number of items to be there in each of the arrays
				//$max_cnt = round(count($show_caption_array)/2);
				$max_cnt = count($show_caption_array);
				if (count($show_caption_array)>0)
				{
					//$splt_caption_array = array_chunk($show_caption_array,$max_cnt,false);
					//$splt_value_array 	= array_chunk($show_value_array,$max_cnt,false);
					$splt_caption_array = $show_caption_array;
					$splt_value_array 	= $show_value_array;
				}
				// Displaying the values in both the arrays using the following loop
				for($i=0;$i<$max_cnt;$i++)
				{	
					$cls = ($i%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				?>
					<tr>	
						<td align="left" class="<?php echo $cls?>">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="left" width="25%" class="subcaption">
								<?php echo $splt_caption_array[$i]?>
								</td>
								<td align="left" valign="middle" class="normltdtext">
								<?php echo $splt_value_array[$i]?>
								</td>
							</tr>
							</table>
						</td>
					</tr>
				<?php
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'BottomInStatic';
			$show_header	= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else 
			{
				if($show_order_details==1) {
					include 'show_dynamic_fields_orders.php';
				} else {
					include '../includes/orders/show_dynamic_fields_orders.php';
				}
			}	
			if ($cur_col<$max_cols and $cur_col>0)
				echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>
		</table>
		</td>
		<td width="50%" align="left" valign="top">
		<?php
		// Get the order current related things from order table inorder to show the price in the currency selected by
		// the customer
		$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
								order_deliverytype,order_deliverylocation,order_delivery_option,
								order_deliverytotal,order_splitdeliveryreq,order_extrashipping,
								order_deliveryprice_only,order_freedeliverytype 
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
	
		$sql_del = "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,delivery_companyname,
								delivery_buildingnumber,delivery_street,delivery_city,delivery_state,delivery_country,
								delivery_zip,delivery_phone,delivery_fax,delivery_mobile,delivery_email
						FROM
							order_delivery_data
						WHERE
							orders_order_id = $order_id
						LIMIT
							1";
		$ret_del = $db->query($sql_del);
		if ($db->num_rows($ret_del))
		{
			$row_del = $db->fetch_array($ret_del);
		}
		$cls = 'listingtablestyleB_n';
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Delivery Address</td>
		</tr>
			<?php
			$max_cols	= 1;
			$cur_col	= 0;
			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM
											general_settings_site_checkoutfields
										WHERE
											sites_site_id = $ecom_siteid
											AND field_hidden = 0
											AND field_type = 'DELIVERY'
										ORDER BY
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$show_caption_array = $show_value_array = array();
				$name = stripslashes($row_del['delivery_title']).stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']);
				// Moving the captions and values to 2 arrays
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkoutdelivery_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkoutdelivery_fname' and $row_checkout['field_key']!= 'checkoutdelivery_mname' and $row_checkout['field_key'] != 'checkoutdelivery_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						//$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
						if($row_checkout['field_key']=='checkoutdelivery_email' and $fromprint==0)
							$value	= '<a style="color:#FF0000" href="mailto:'.stripslashes($row_del[$row_checkout['field_orgname']]).'">'.stripslashes($row_del[$row_checkout['field_orgname']]).'</a>';
						else
							$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						$show_caption_array[] 	= $caption;
						$show_value_array[] 	= ': '.$value;
					}
				}
				// Calculating the number of items to be stored in each of the arrays
				//$max_cnt = round(count($show_caption_array)/2);
				$max_cnt = count($show_caption_array);
				if (count($show_caption_array)>0)
				{
					/*$splt_caption_array = array_chunk($show_caption_array,$max_cnt,false);
					$splt_value_array 	= array_chunk($show_value_array,$max_cnt,false);*/
					$splt_caption_array = $show_caption_array;
					$splt_value_array 	= $show_value_array;
				}
				// The following section show the values in array
				for($i=0;$i<$max_cnt;$i++)
				{	
					$cls = ($i%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				?>
					<tr>
					<td align="left" class="<?php echo $cls?>">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="left" width="25%" class="subcaption">
							<?php echo $splt_caption_array[$i]?>
							</td>
							<td align="left" valign="middle" class="normltdtext">
							<?php echo $splt_value_array[$i]?>
							</td>
						</tr>
						</table>
					</td>
					</tr>
				<?php
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'Bottom';
			$show_header	= 1;
			$max_cols		= 1;
			$cur_col		= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else
				include '../../includes/orders/show_dynamic_fields_orders.php';
			if ($cur_col<$max_cols and $cur_col>0)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
			if($ecom_siteid == 66) // only for stoneybrook website
			{
		?>
				<tr>
				<td align="right" colspan="2" class="<?php echo $cls?>">
					<input type="button" id="print_receipt" name="print_receipt" value="Print Receipt?" class="red" onclick="show_receiptpopup(<?php echo $order_id?>)" />
				</td>
				</tr>
		<?php
			}
		?>
		</table>
		</td>
		</tr>
		</table>
	<?php
}

// ###############################################################################################################
// 				Function which holds the display logic of delivery address to be shown 
// ###############################################################################################################
function show_giftwrap_details($order_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$call_src;
	$sql_ord = "SELECT order_giftwrap,order_giftwrap_per,order_giftwrapmessage,order_giftwrapmessage_text,order_giftwrap_message_charge,
 						order_giftwrap_minprice,order_giftwraptotal,order_currency_convertionrate,order_currency_symbol  
					FROM
						orders
					WHERE
						order_id=".$order_id."
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	if ($row_ord['order_giftwrap']=='Y')
	{
?>	
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Gift Wrap Details</td>
		</tr>
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
				<td class="listingtablestyleB_n" width="20%">Gift Wrap Per</td>
				<td class="listingtablestyleB_n"><?php echo $row_ord['order_giftwrap_per']?></td>
				<td width="60%" class="listingtablestyleB_n">&nbsp;</td>
			</tr>
			<?php
			if($row_ord['order_giftwrap_minprice'])
			{
			?>
				<tr>
					<td class="listingtablestyleB_n" width="20%">Gift Wrap Mininum Price? </td>
					<td class="listingtablestyleB_n">&nbsp;</td>
					<td width="60%" class="listingtablestyleB_n"><?php echo print_price_selected_currency($row_ord['order_giftwrap_minprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				</tr>
			<?php
			}
			?>	
			<?php
			if($row_ord['order_giftwrapmessage']=='Y')
			{
			?>
				<tr>
					<td class="listingtablestyleB_n" width="20%" align="left" valign="top">Gift Wrap Message</td>
					<td class="listingtablestyleB_n" valign="top"><?php echo stripslashes($row_ord['order_giftwrapmessage_text'])?></td>
					<td width="60%" class="listingtablestyleB_n" valign="top"><?php echo print_price_selected_currency($row_ord['order_giftwrap_message_charge'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				</tr>
			<?php
			}
		?>
			</table>
			</td>	
			</tr>
		<?php	
		// Check whether other gift wrap details required
		$sql_wrap_details = "SELECT  giftwrap_name, giftwrap_price, giftwrap_type 
								FROM 
									order_giftwrap_details 
								WHERE 
									orders_order_id = $order_id 
								ORDER BY 
									id";
		$ret_wrap_details = $db->query($sql_wrap_details);
		if($db->num_rowS($ret_wrap_details))
		{
		?>
		<?php /*?><tr>
			<td colspan="2" align="left" class="shoppingcartheader">Gift Wrap Additional Details</td>
		</tr><?php */?>
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
		<?php
			while ($row_wrap_details = $db->fetch_array($ret_wrap_details))
			{
		?>
				<tr>
				<td class="listingtablestyleB_n" align="left" width="20%"><?php echo ucwords(strtolower(stripslashes($row_wrap_details['giftwrap_type']))) ?>
				</td>
				<td class="listingtablestyleB_n" align="left" width="20%"><?php echo stripslashes($row_wrap_details['giftwrap_name'])?>
				</td>
				<td width="60%" class="listingtablestyleB_n"><?php echo print_price_selected_currency($row_wrap_details['giftwrap_price'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				</tr>
		<?php
			}
		?>
			</table>
			</td>
		</tr>	
		<?php	
		}	
		?>	
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
				<td class="listingtablestyleB_n" align="right"><strong>Gift Wrap Total</strong></td>
				<td width="60%" class="listingtablestyleB_n"><strong><?php echo print_price_selected_currency($row_ord['order_giftwraptotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></strong></td>
				</tr>
			</table>
			</td>
		</tr>
		</table>
<?php
	}		
}	
// ###############################################################################################################
// 				Function which holds the display logic of delivery address to be shown 
// ###############################################################################################################
function show_delivery_address($order_id)
{
	global $db,$ecom_siteid,$ecom_hostname,$call_src;
	// Get the order current related things from order table inorder to show the price in the currency selected by
	// the customer
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_deliverytype,order_deliverylocation,order_delivery_option,
							order_deliverytotal,order_splitdeliveryreq,order_extrashipping,
							order_deliveryprice_only,order_freedeliverytype 
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

	$sql_del = "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,delivery_companyname,
							delivery_buildingnumber,delivery_street,delivery_city,delivery_state,delivery_country,
							delivery_zip,delivery_phone,delivery_fax,delivery_mobile,delivery_email
					FROM
						order_delivery_data
					WHERE
						orders_order_id = $order_id
					LIMIT
						1";
	$ret_del = $db->query($sql_del);
	if ($db->num_rows($ret_del))
	{
		$row_del = $db->fetch_array($ret_del);
	}
	$cls = 'listingtablestyleB_n';
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Delivery Address</td>
		</tr>
			<?php
			$max_cols	= 2;
			$cur_col	= 0;
			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM
											general_settings_site_checkoutfields
										WHERE
											sites_site_id = $ecom_siteid
											AND field_hidden = 0
											AND field_type = 'DELIVERY'
										ORDER BY
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$show_caption_array = $show_value_array = array();
				$name = stripslashes($row_del['delivery_title']).stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']);
				// Moving the captions and values to 2 arrays
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkoutdelivery_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkoutdelivery_fname' and $row_checkout['field_key']!= 'checkoutdelivery_mname' and $row_checkout['field_key'] != 'checkoutdelivery_surname')
					{
						$caption = stripslashes($row_checkout['field_name']);
						//$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
						if($row_checkout['field_key']=='checkoutdelivery_email')
							$value	= '<a style="color:#FF0000" href="mailto:'.stripslashes($row_del[$row_checkout['field_orgname']]).'">'.stripslashes($row_del[$row_checkout['field_orgname']]).'</a>';
						else
							$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}

					if ($proceed==true and $value!='')
					{
						$show_caption_array[] 	= $caption;
						$show_value_array[] 	= ': '.$value;
					}
				}
				// Calculating the number of items to be stored in each of the arrays
				$max_cnt = round(count($show_caption_array)/2);
				if (count($show_caption_array)>0)
				{
					$splt_caption_array = array_chunk($show_caption_array,$max_cnt,false);
					$splt_value_array 	= array_chunk($show_value_array,$max_cnt,false);
				}
				// The following section show the values in array
				for($i=0;$i<$max_cnt;$i++)
				{	
					$cls = ($i%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				?>
					<tr>
					<td align="left" width="50%" class="<?php echo $cls?>">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="left" width="25%" class="subcaption">
							<?php echo $splt_caption_array[0][$i]?>
							</td>
							<td align="left" valign="middle">
							<?php echo $splt_value_array[0][$i]?>
							</td>
						</tr>
						</table>
					</td>
					<td align="left" width="50%" class="<?php echo $cls?>">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="left" width="25%" class="subcaption">
							<?php echo $splt_caption_array[1][$i]?>
							</td>
							<td align="left" valign="middle">
							<?php echo $splt_value_array[1][$i]?>
							</td>
						</tr>
						</table>
					</td>
					</tr>
				<?php
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'Bottom';
			$show_header	= 1;
			$max_cols		= 2;
			$cur_col		= 0;
			if($call_src=='from_details_page')
			{
				include 'includes/orders/show_dynamic_fields_orders.php';
			}	
			else
				include '../includes/orders/show_dynamic_fields_orders.php';
			if ($cur_col<$max_cols and $cur_col>0)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
			if($ecom_siteid == 66) // only for stoneybrook website
			{
		?>
				<tr>
				<td align="right" colspan="2" class="<?php echo $cls?>">
					<input type="button" id="print_receipt" name="print_receipt" value="Print Receipt?" class="red" onclick="show_receiptpopup(<?php echo $order_id?>)" />
				</td>
				</tr>
		<?php
			}
		?>
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader">Delivery Method Details</td>
		</tr>
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<?php
			$srno =1;
			if($row_ord['order_deliverytype']!='None')
			{
			?>
					<tr>
						<td align="left" width="25%" class="subcaption listingtablestyleB_n">&nbsp;Delivery Type</td>
						<td align="left" width="25%" class="listingtablestyleB_n"><?php echo ucwords(strtolower(stripslashes($row_ord['order_deliverytype'])))?>
						<?php
							if(trim($row_ord['order_delivery_option'])!='')
							{
								echo '('.ucwords(strtolower(stripslashes(trim($row_ord['order_delivery_option'])))).')';
								$displayed_option = 1;
							}
						?>						
						</td>
						<td align="left" width="25%" class="subcaption listingtablestyleB_n">&nbsp;Delivery Charge</td>
						<td align="left" width="25%" class="listingtablestyleB_n"><?php echo print_price_selected_currency($row_ord['order_deliveryprice_only'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>
						<?php 
							if($row_ord['order_freedeliverytype'] !='None')
								echo ' <span class="redtext"><strong>[</strong>'.getFreedeliveryCaption($row_ord['order_freedeliverytype']).'<strong>]</strong> </span>';
						?>
						</td>
					</tr>
			<?php
			$srno++;
			}
			if($row_ord['order_deliverylocation']!='')
			{
				$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				$srno++;
			?>
					<tr>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">&nbsp;Delivery Location</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo ucwords(strtolower(stripslashes($row_ord['order_deliverylocation'])));
							if($row_ord['order_delivery_option']!='' and $displayed_option!=1)
							{
							?>
							(
							<?php echo ucwords(strtolower(stripslashes(trim($row_ord['order_delivery_option']))))?>
							)
							<?php 
							}
							?></td>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>"><?php /*?>Delivery Charge<?php */?></td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php //echo print_price_selected_currency($row_ord['order_deliveryprice_only'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			else
			{
				//$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				//$srno++;
			?>
					<?php /*?><tr>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">Delivery Charge</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">Delivery Charge</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_ord['order_deliveryprice_only'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr><?php */?>
			<?php
			}
			if($row_ord['order_extrashipping']>0)
			{
				$cls = ($srno%2==0)?'listingtablestyleB_n':'listingtablestyleB_n';
				$srno++;
			?>
					<tr>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="subcaption <?php echo $cls?>">&nbsp;Extra Shipping Charge</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_ord['order_extrashipping'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			if ($row_ord['order_deliverytotal']>0)
			{
			?>
					<tr>
						<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
						<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
						<td align="left" width="25%" class="shoppingcartpriceB">Total Delivery Charge</td>
						<td align="left" width="25%" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			?>
			</table>
			</td>
		</tr>
		</table>
	<?php
}
// Reason and despatch id details entering section
function order_additionaldet_despatched()
{
	global $db,$ecom_siteid;
	// Get the general settings details to check whether email preview is required for despatch
	$sql_set = "SELECT despatch_email_preview_req 
					FROM 
						general_settings_sites_common_onoff 
					WHERE 
						sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_set = $db->query($sql_set);
	$row_set = $db->fetch_array($ret_set);
	?>
	   <table border="0" cellpadding="1" cellspacing="0" width="100%">

		<tbody><tr>
		  <td colspan="2" class="shoppingcartheader" align="left">Please fill in the following details</td>
		</tr>
		  <tr>
		    <td colspan="2" align="left" class="listingtablestyleA">Despatch Reference Number (optional) </td>
	      </tr>
		  <tr>
		  <td width="25%" align="left" class="listingtablestyleB_n">&nbsp;</td>
		  <td width="75%" align="left" class="listingtablestyleB_n"><input name="txt_despatch_id" id="txt_despatch_id" size="55" type="text" value=""></td>
		</tr>
		 <tr>
		    <td colspan="2" align="left" class="listingtablestyleA">Expected Delivery Date </td>
	      </tr>
		  <tr>
		  <td width="25%" align="left" class="listingtablestyleB_n">&nbsp;</td>
		  <td width="75%" align="left" class="listingtablestyleB_n"><input name="txt_expected_delivery_date" id="txt_expected_delivery_date" size="12" type="text" value="<?php echo date('d-m-Y')?>" readonly="readonly"> (dd-mm-yyyy)
		  
		  </td>
		</tr>
		  <tr>
		    <td colspan="2" align="left" valign="top" class="listingtablestyleA">Additional Note (optional)</td>
	      </tr>
		  <tr>

		  <td align="left" valign="top" class="listingtablestyleB_n">&nbsp;</td>
		  <td align="left" class="listingtablestyleB_n"><textarea name="txt_despatch_note" id="txt_despatch_note" cols="50" rows="6"></textarea></td>
		</tr>
		<tr>
		  <td colspan="2" align="center" valign="top" class="listingtablestyleA"><span class="redtext">Note, if specified, will be automatically added to the notes section</span></td>
		  </tr>
		 <tr>
      <td colspan="4" align="center" class="listingtablestyleA"><input name="cancel_button" type="button" class="red" value="Cancel" onclick="<?php /*?>document.getElementById('moveto_backorder_div').innerHTML = '';alert(document.getElementById('moveto_backorder_div').style.display);<?php */?>document.getElementById('moveto_backorder_div').style.display='none';" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <?php 
	  if($row_set['despatch_email_preview_req']==0)
	  {
	  ?>
	  <input name="Despatch_submit" type="button" class="red" value="Done" onclick="call_ajax_showlistall('operation_despatched_do')" />
	  <?php
	  }
	  else
	  {
	  ?>
	  <input name="Despatch_submit" type="button" class="red" value="Continue to Despatch Email Preview" onclick="call_ajax_showlistall('operation_despatched_email_preview_do')" />
	  <?php
	  }
	  ?>
	  </td>
    </tr>
	  </tbody></table>
   
  </table>
	<?php
}
function show_Order_Despatch_email_content($order_id,$det_arr,$despatch_note,$despatch_number,$alert='',$despatch_delivery_date='')
{
	global $db,$ecom_siteid,$ecom_hostname;
	$ecom_content = '';
	$det_arr 						= explode("~",$det_arr);
	$del_mon_arr = array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	if($despatch_delivery_date)
	{
		$despatch_delivery_date_arr = explode('-',$despatch_delivery_date);
		$despatch_delivery_date = $despatch_delivery_date_arr[0].'-'.$del_mon_arr[$despatch_delivery_date_arr[1]].'-'.$despatch_delivery_date_arr[2];
	}
	// Get the email content
		$sql_template = "SELECT lettertemplate_from,lettertemplate_subject,lettertemplate_contents,lettertemplate_disabled
								FROM
									general_settings_site_letter_templates
								WHERE
									sites_site_id = $ecom_siteid
									AND lettertemplate_letter_type = 'ORDER_DESPATCHED'
								LIMIT
									1";
		$ret_template = $db->query($sql_template);
		if ($db->num_rows($ret_template))
		{
			$row_template 		= $db->fetch_array($ret_template);
			$email_from			= stripslashes($row_template['lettertemplate_from']);
			$email_subject		= stripslashes($row_template['lettertemplate_subject']);
			$email_content		= stripslashes($row_template['lettertemplate_contents']);
			$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
			// Get some details from orders table for current order
			$sql_ords = "SELECT order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,
									order_custemail,order_currency_convertionrate,order_currency_symbol,
									order_subtotal,order_giftwraptotal,order_deliverytotal,order_extrashipping,order_deliveryprice_only,order_tax_total,
							order_customer_discount_value,order_customer_or_corporate_disc,
							order_customer_discount_type,order_customer_discount_percent,order_totalprice,
							order_deposit_amt,order_deposit_amt,gift_vouchers_voucher_id,promotional_code_code_id,
							order_bonuspoint_discount
					FROM
						orders
					WHERE
						order_id = $order_id
					LIMIT
						1";
			$ret_ords = $db->query($sql_ords);
			if ($db->num_rows($ret_ords))
			{
				$row_ords = $db->fetch_array($ret_ords);
			}
			for($i=0;$i<count($det_arr);$i++)
			{
			// Get the qty remaining for current item in order details table
			$sql_orderdet = "SELECT order_qty,products_product_id 
								FROM 
									order_details 
								WHERE 
									orderdet_id = ".$det_arr[$i]." 
								LIMIT 
									1";
			$ret_orderdet = $db->query($sql_orderdet);
			if($db->num_rows($ret_orderdet))
			{
				$row_orderdet = $db->fetch_array($ret_orderdet);
				if ($row_orderdet['order_qty']>0)
				{
					$despatchid_arr[] 				= $det_arr[$i];
					$despatchqty_arr[$det_arr[$i]] 	= $row_orderdet['order_qty'];
				}
			}						
		}
			
			
			
			// Calling function to get the product details of current order
			$pass_arr['prods']		= $despatchid_arr;
			$pass_arr['qtys']		= $despatchqty_arr;
			$prod_details_str		= get_ProductsInOrdersForMail($order_id,$row_ords,$pass_arr);
			$cname					= stripslashes($row_ords['order_custtitle']).stripslashes($row_ords['order_custfname'])." ".stripslashes($row_ords['order_custsurname']);
			$search_arr	= array (
									'[cust_name]',
									'[domain]',
									'[orderid]',
									'[despatch_date]',
									'[product_details]',
									'[note]',
									'[despatch_id]',
									'[delivery_date]'
								);
			if($despatch_note!='')
			{
				if($ecom_siteid==88) // skatesrus
				{
					$despatch_note_str = nl2br($despatch_note);
				}
				else
				{
					$despatch_note_str=   ' <tr>
												<td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(172, 172, 172);" align="left" valign="top">Note</td>
											</tr>';
					$despatch_note_str .= '<tr>
												<td style="border-bottom: 1px solid rgb(172, 172, 172); padding: 5px 0pt; font-family: Arial,Helvetica,sans-serif; font-size: 12px; color: rgb(91, 91, 91); font-weight: normal;" align="left" valign="top">'.$despatch_note.'</td>
										  </tr>'	;
				}						  
			}
			$replace_arr= array(
					$cname,
					$ecom_hostname,
					$row_ords['order_id'],
					date('d-M-Y'),
					$prod_details_str,
					$despatch_note_str,
					$despatch_number,
					$despatch_delivery_date
			);
			// Do the replacement in email template content
			$email_content = str_replace($search_arr,$replace_arr,$email_content);
		}	
		return $email_content;
}

/*
	Function which show the section to specify the amount to be refunded and also the reason for refund
*/
function show_Refund_Input_Section($order_id,$row_ord)
{
	global $db,$ecom_siteid;
		// Check whether product deposit exists
		if ($row_ord['order_deposit_amt']>0)
		{
				if ($row_ord['order_deposit_cleared']==0) // if remaining amount not cleared
				{
					if ($row_ord['order_totalauthorizeamt']>0)
						$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
					else
						$max_refund_total = $row_ord['order_deposit_amt']-$row_ord['order_refundamt'];
				}
				else
				{
					if ($row_ord['order_totalauthorizeamt']>0)
						$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
					else
						$max_refund_total = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
				}
		}
		else
		{
			if ($row_ord['order_totalauthorizeamt']>0)
				$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
			else
				$max_refund_total = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
		}
		if($max_refund_total>0)
		{
	?>
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
			<tbody>
			<tr>
				<td colspan="3" class="seperationtd_special">Please fill in the following details  </td>
			</tr>
			<tr>
				<td align="left" valign="top" class="normltdtext">Amount to be refunded <span class="redtext">*</span></td>
				<td colspan="2" align="left"><input name="txt_refundamt" type="text" value=""> 
				&nbsp;&nbsp;<span class="shoppingcartpriceB">Total Amount Remaining in order: </span> <span class="shoppingcartpriceB"><?php echo print_price_selected_currency($max_refund_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></span>
				
				<input name="max_refundamt_allowed" id="max_refundamt_allowed" value="<?php echo print_price_selected_currency($max_refund_total,$row_ord['order_currency_convertionrate'],'',true)?>" type="hidden">
				<input type="hidden" name="currency_symbol" id="currency_symbol" value="<?php echo $row_ord['order_currency_symbol']?>" />		  </td>
			</tr>
			<tr>
				<td align="left" valign="top" width="21%" class="normltdtext">Reason <span class="redtext">*</span></td>
				<td colspan="2" align="left"><textarea name="txt_refundreason" id="txt_refundreason" cols="50" rows="6"></textarea>
				<span class="normltdtext">Reason will be automatically added to the notes section</span></td>
			</tr>
			
			<tr>
				<td align="left" valign="top">&nbsp;</td>
				<td width="2%" align="left" valign="top"><input name="chk_stock_return" id="chk_stock_return" value="1" type="checkbox" /></td>
				<td width="77%" align="left" valign="top"><span class="subcaption">Place the quantity of selected products (if any) back in stock</span></td>
			</tr>
			<tr>
				<td colspan="3" align="center" valign="top"><input name="refund_submit" type="button" class="red" id="refund_submit" value="Click here to Refund" onclick="validate_refund()" /></td>
			</tr>
			</tbody></table>
	<?php
	}
}
// Function to show the refund details
function order_refund_details($ordid,$row_ord)
{
	global $db,$ecom_siteid;
	$sql_ref = "SELECT refund_id,refund_on,refund_by,refund_amt
					FROM
						order_details_refunded
					WHERE
						orders_order_id = $ordid
					ORDER BY
						refund_on DESC";
	$ret_ref = $db->query($sql_ref);
	if($db->num_rows($ret_ref))
	{
		?>
			<table width="100%" cellpadding="1" cellspacing="0" border="0">
			<tr>
				<td colspan="5" align="left" class="seperationtd_special">
				Refunded Details
				</td>
			</tr>
		<?php
		$table_varheaders 		= array('#','Date','Refunded By','Reason','Refunded Amount');
		$headervar_positions	= array('center','left','left','left','right');
		$var_colspans			= count($table_varheaders);
		$varsrno				= 1;
		$tot_amt 				= 0;
		echo table_header($table_varheaders,$headervar_positions);
		while ($row_ref = $db->fetch_array($ret_ref))
		{
			$tot_amt += $row_ref['refund_amt'];
			$cls = ($varsrno%2==0)?'listingtablestyleA':'listingtablestyleB';
			// Check whether any note exists for current refund
			$sql_note = "SELECT note_text 
									FROM 
										order_notes 
									WHERE 
										note_type=7 
										AND note_related_id = ".$row_ref['refund_id']." 
									LIMIT 
										1";
			$ret_note = $db->query($sql_note);
			if ($db->num_rows($ret_note))
			{
				$row_note = $db->fetch_array($ret_note);
				$note		= $row_note['note_text'];
			}
			else
				$note='';
		?>
				<tr>
					<td align="center" width="6%" class="<?php echo $cls?>" valign="top">
						<?php echo $varsrno?>.
					</td>
					<td align="left" width="15%" class="<?php echo $cls?>">
						<?php
						echo dateFormat($row_ref['refund_on'],'datetime');
						
						// Check whether any products related to current refund
						$sql_refundprod = "SELECT refund_id
													FROM
														order_details_refunded_products
													WHERE
														refund_id = ".$row_ref['refund_id']."
													LIMIT
														1";
						$ret_refundprod = $db->query($sql_refundprod);
						if ($db->num_rows($ret_refundprod))
						{
						?>
								<div id='refprodimgdiv_<?php echo $row_ref['refund_id']?>' class="show_vardiv_big" onclick="handle_showrefund_prods('<?php echo $row_ref['refund_id']?>')" title="Click here"><img src="images/right_arr.gif" /></div>
						<?php
						}
						
						$sql_returncheck = "SELECT return_id 
											FROM 
												order_details_return 
											WHERE 
												order_details_refunded_refund_id = ".$row_ref['refund_id'];
						$ret_returncheck = $db->query($sql_returncheck);
						if($db->num_rows($ret_returncheck))
						{
							$row_returncheck = $db->fetch_array($ret_returncheck);
						?>
							<div id='refprodimgdiv_<?php echo $row_ref['refund_id']?>' class="show_vardiv_big" onclick="handle_showreturnrefund_prods('<?php echo $row_returncheck['return_id']?>','<?php echo $row_ref['refund_id']?>')" title="Click here"><img src="images/right_arr.gif" /></div>
						<?php
						}									
						?>
					</td>
					<td align="left" class="<?php echo $cls?>" width="25%">
					<?php
					echo getConsoleUserName($row_ref['refund_by']);
					?>
					</td>
					<td align="left" class="<?php echo $cls?>">
					<?php
					if($note!='')
						echo stripslashes(nl2br($note));
					else
						echo '-- No Reason Added --';
					?>
					</td>
					<td align="right" class="<?php echo $cls?>" width="15%">
					<?php
					echo print_price_selected_currency($row_ref['refund_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>
					</td>
				</tr>
				<tr id="refunddet_tr_<?php echo $row_ref['refund_id']?>" style="display:none">
				<td align="center" width="8%">&nbsp;</td>
				<td colspan="3">
				<div id="refunddet_div_<?php echo $row_ref['refund_id']?>">
				</div>
				</td>
				<td>
				</td>
				</tr>
		<?php
		$varsrno++;
		}
		?>
			<tr>
				<td align="center" class="shoppingcartpriceB">&nbsp;</td>
				<td align="center" class="shoppingcartpriceB" colspan="2">&nbsp;</td>
				<td align="right" class="shoppingcartpriceB">
				Total Refunded
				</td>
				<td align="right" class="shoppingcartpriceB">
					<?php
					echo print_price_selected_currency($tot_amt,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					?>
				</td>
			</tr>
		</table>
		<?php
	}
}
/* Function to show the products in refund */
function order_refund_product_details($refund_id)
{
	global $db,$ecom_siteid;
	// Get the product details linked with current refund id
	$sql_prod = "SELECT product_name,b.refund_qty 
							FROM
								order_details a, order_details_refunded_products b
						WHERE
							a.orderdet_id = b.orderdet_id
							AND b.refund_id = $refund_id ";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
	?>
			<table width="80%" cellpadding="1" cellspacing="0" border="0">
			<?php
			$table_varheaders 		= array('#','Product Name','Qty');
			$headervar_positions	= array('center','left','left');
			$var_colspans			= count($table_varheaders);
			$refsrno				= 1;
			echo table_header($table_varheaders,$headervar_positions);
			while ($row_prod = $db->fetch_array($ret_prod))
			{
				$cls = ($refsrno%2==0)?'listingtablestyleB':'listingtablestyleB';
			?>
					<tr>
						<td align="center" width="2%" class="<?php echo $cls?>">
						<?php echo $refsrno?>
						</td>
						<td align="left" width="75%" class="<?php echo $cls?>">
							<?php
							echo stripslashes($row_prod['product_name']);
							?>
						</td>
						<td align="left" width="23%" class="<?php echo $cls?>">
							<?php
							echo stripslashes($row_prod['refund_qty']);
							?>
						</td>
					</tr>
			<?php
			$refsrno++;
			}
			?>
			</table>
		<?php
	}
}

/* Function to show the products in refund during return */
function order_returnrefund_product_details($ret_id)
{
	global $db,$ecom_siteid;
	// Get the product details linked with current refund id
	$sql_prod = "SELECT product_name,b.return_qty 
							FROM
								order_details a, order_details_return b
						WHERE
							a.orderdet_id = b.orderdet_id
							AND b.return_id = $ret_id ";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
	?>
			<table width="80%" cellpadding="1" cellspacing="0" border="0">
			<?php
			$table_varheaders 		= array('#','Product Name (Order Return)','Qty');
			$headervar_positions	= array('center','left','left');
			$var_colspans			= count($table_varheaders);
			$refsrno				= 1;
			echo table_header($table_varheaders,$headervar_positions);
			while ($row_prod = $db->fetch_array($ret_prod))
			{
				$cls = ($refsrno%2==0)?'listingtablestyleB':'listingtablestyleB';
			?>
					<tr>
						<td align="center" width="2%" class="<?php echo $cls?>">
						<?php echo $refsrno?>
						</td>
						<td align="left" width="75%" class="<?php echo $cls?>">
							<?php
							echo stripslashes($row_prod['product_name']);
							?>
						</td>
						<td align="left" width="23%" class="<?php echo $cls?>">
							<?php
							echo stripslashes($row_prod['return_qty']);
							?>
						</td>
					</tr>
			<?php
			$refsrno++;
			}
			?>
			</table>
		<?php
	}
}

/* Function to show the list of items to be returned in div */
function return_Select($order_id,$prod_sel_arr)
{
	global $db,$ecom_siteid;
	$row_ord = fetch_Order_Details($order_id);
	// Get the order details related to current order 
	$sql_prods = "SELECT a.orderdet_id,a.products_product_id,a.product_name,a.order_qty,a.product_soldprice,a.order_orgqty,a.order_discount,b.despatched_id,b.despatched_qty,b.despatched_returned_qty
							FROM
								order_details a,order_details_despatched b
							WHERE
								a.orders_order_id = $order_id 
								AND a.orderdet_id = b.orderdet_id 
								AND b.despatched_id IN(".implode(',',$prod_sel_arr).") ";
	$ret_prods = $db->query($sql_prods);
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td colspan="6" align="left" class="shoppingcartheader">Please select the quantity of items to be marked as returned</td>
    </tr>
    <tr>
      <td width="3%" align="center" class="listingtableheader">#</td>
      <td width="35%" align="left" class="listingtableheader">Product</td>
      <td width="11%" align="center" class="listingtableheader">Despatch Qty</td>
      <td width="21%" align="center" class="listingtableheader">Returning Qty</td>
      <td width="11%" align="right" class="listingtableheader">Total</td>
      <td width="19%" align="left" class="listingtableheader">Reason</td>
    </tr>
	<?php
			$exists = false;
			if ($db->num_rows($ret_prods))
			{
				$srno 			= 1;
				$exists 			= true;
				$grandtotal		= 0;
				$refundable_tot = 0;
				//print_r($row_ord);
				$refundable_tot	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
					
				while ($row_prods = $db->fetch_array($ret_prods))
				{
					$org_qty 			= $row_prods['order_orgqty'];
					$sale_price			= $row_prods['product_soldprice'];
					$disc					= $row_prods['order_discount'];
					$disc_per_item	= ($disc/$org_qty);
					$net_total			= $sale_price * ($row_prods['despatched_qty']-$row_prods['despatched_returned_qty']);
					$grandtotal			+= $net_total;
		?>
					<tr>
					  <td align="center" valign="top"  class="listingtablestyleB"><?php echo $srno++?>.
					  	<input type="hidden" name="txt_sel_despatch_<?php echo $row_prods['despatched_id']?>" id="txt_sel_despatch_<?php echo $row_prods['despatched_id']?>" value="<?php echo $row_prods['despatched_id']?>"/>					  </td>
					  <td align="left" valign="top" class="listingtablestyleB">
					  	<?php echo stripslashes($row_prods['product_name'])?><?php echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id'])?>						</td>
					  <td align="center" valign="top" class="listingtablestyleB"><?php echo ($row_prods['despatched_qty']-$row_prods['despatched_returned_qty'])?></td>
					  <td align="center" valign="top" class="listingtablestyleB">
					 	 <select name="cbo_return_qty_<?php echo $row_prods['despatched_id']?>"  id="cbo_return_qty_<?php echo $row_prods['despatched_id']?>">
						<?php
							for($i=($row_prods['despatched_qty']-$row_prods['despatched_returned_qty']);$i>0;$i--)
							{
						?>
								<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
							}
						?>
						</select><br/><br />
						<select name="cbo_return_type_<?php echo $row_prods['despatched_id']?>">
							<option value="STK_BACK">Place Qty back in Stock</option>
							<option value="DAM_MARK">Mark as Damaged </option>
						</select>					  </td>
					  <td align="right" valign="top" class="listingtablestyleB"><?php echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);?></td>
					  <td align="left" valign="top" class="listingtablestyleB"><textarea name="return_reason_<?php echo $row_prods['despatched_id']?>" cols="20" rows="3"></textarea></td>
					</tr>
					<tr>
					  <td align="center" valign="top"  class="listingtablestyleB">&nbsp;</td>
					  <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
					  <td align="center" valign="top" class="listingtablestyleB">&nbsp;</td>
					  <td align="center" valign="top" class="shoppingcartpriceB">Net Total </td>
					  <td align="right" valign="top" class="shoppingcartpriceB"><?php echo print_price_selected_currency($grandtotal,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);?></td>
					  <td align="left" valign="top" class="listingtablestyleB">&nbsp;</td>
	  </tr>
		<?php
				}
				if ($row_ord['order_deposit_amt']>0)
				{
						if ($row_ord['order_deposit_cleared']==0) // if remaining amount not cleared
						{
							if ($row_ord['order_totalauthorizeamt']>0)
								$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
							else
								$max_refund_total = $row_ord['order_deposit_amt']-$row_ord['order_refundamt'];
						}
						else
						{
							if ($row_ord['order_totalauthorizeamt']>0)
								$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
							else
								$max_refund_total = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
						}
				}
				else
				{
					if ($row_ord['order_totalauthorizeamt']>0)
						$max_refund_total = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
					else
						$max_refund_total = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
				}
				if($max_refund_total>0 and $row_ord['order_paystatus']!='Pay_Hold')
				{
		?>				
					<tr>
					  <td colspan="6" align="left">&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="6" align="left" class="listingtableheader">If refund is to be done while returning the products, then please specify the refund amount in box below.</td>
					</tr>
					<tr>
					  <td colspan="6" align="left"><table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tbody>
						  
						  <tr>
							<td width="21%" align="left" valign="top" class="listingtablestyleB">Amount to be refunded </td>
							<td width="79%" align="left" class="listingtablestyleB">
							<input name="txt_refundamt" id="txt_refundamt" type="text" value="" />
							<input name="max_refundamt_allowed" id="max_refundamt_allowed" type="hidden" value="<?php echo print_price_selected_currency($max_refund_total,$row_ord['order_currency_convertionrate'],'',true)?>" />
							<input name="currency_symbol" id="currency_symbol" value="<?php echo $row_ord['order_currency_symbol']?>" type="hidden" />
							  &nbsp;&nbsp;<span class="shoppingcartpriceB">Total Amount Remaining in order: </span> <span class="shoppingcartpriceB"><?php echo print_price_selected_currency($max_refund_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></span></td>
						  </tr>
						</tbody>
					  </table></td>
					</tr>
<?php
			}
	}
?>    
    <tr>
      <td colspan="6" align="center">&nbsp;</td>
    </tr>
    <tr>
      	<td colspan="6" align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="45%" align="right"><input name="return_cancel" type="button" class="red" value="Cancel" onclick="document.getElementById('moveto_returnorder_div').style.display='none'" /></td>

			<td width="10%">&nbsp;</td>
			<td width="45%" align="left"><input name="Button" type="button" class="red" value="Done" onclick="validate_return()" /></td>
			</tr>
			</table></td>
    </tr>
  </table>
<?php	
}
function show_barcode($product_id,$comb_id)
{
	global $db,$ecom_siteid;
	$barcode = '';
	if($comb_id!=0) // case of combination stock
	{
		$sql_sel = "SELECT comb_barcode 
						FROM 
							product_variable_combination_stock 
						WHERE 
							comb_id=$comb_id 
						LIMIT 1";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			$row_sel = $db->fetch_array($ret_sel);
			$barcode = trim(stripslashes($row_sel['comb_barcode']));		
		}	
	}
	else
	{
		// try to get the bar code directly from products table
		$sql_prod= "SELECT product_barcode 
						FROM 
							products 
						WHERE 
							product_id = $product_id 
						LIMIT 
							1";
		$ret_prod = $db->query($sql_prod);
		if($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			$barcode = trim(stripslashes($row_prod['product_barcode']));
		} 
	}
	if($barcode!='')
	{
		echo '<br><span style="color:#FF0000"><strong>Barcode:</strong> '.$barcode.'</span>';	
	}
}
function show_category($cat_id)
{
	global $db,$ecom_siteid;
	$category_name = '';
	if($cat_id!=0) // case of combination stock
	{
		$sql_sel = "SELECT category_name  
						FROM 
							product_categories  
						WHERE 
							category_id=$cat_id 
						LIMIT 1";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			$row_sel = $db->fetch_array($ret_sel);
			$category_name = '<span style="color: rgb(255, 0, 0);"><strong>Category:</strong> '.trim(stripslashes($row_sel['category_name'])).'</span>';		
		}	
	}
	return $category_name;
}
function Receipt_show_Products_Remaining_In_Order($order_id,$row_ord,$type='main',$from_print=0)
{
	global $db,$ecom_siteid,$print_buttons,$print_buttons,$product_no_link;
	// Get the products in this order
	$sql_prods = "SELECT orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,order_qty,
						order_orgqty,product_soldprice,order_retailprice,product_costprice,order_discount,
						order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
						order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
						order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
						order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
						order_discount_group_name,order_discount_group_percentage,order_freedelivery,order_detail_discount_type,order_prom_id    
					FROM
						order_details
					WHERE
						orders_order_id = $order_id 
						AND order_qty>0";
	$ret_prods = $db->query($sql_prods);
	$show_details = true;
	if($show_details == true)
	{
	?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000">
		<tr>
			<td align="left" colspan="4" class="tdcolorgray_normal">
		<?php
		$table_headers 		= array('Products','<strong>Retail Price</strong>','<strong>Discount</strong>','<strong>Sale Price</strong>','<strong>Qty</strong>','<strong>Net</strong>');
		$header_positions	= array('left','right','right','right','center','right','right');
		$colspan 			= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<?php
			$srno=1;
			$atleast_one = false;
			//echo table_header($table_headers,$header_positions);
			?>
			<tr>
			<td align="left" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><strong>Product</strong></td>
			<td align="right" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><strong>Retail Price</strong></td>
			<td align="right" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><strong>Discount</strong></td>
			<td align="right" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><strong>Sale Price</strong></td>
			<td align="center" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><strong>Qty</strong></td>
			<td align="right" style="border-bottom:1px solid #000000;"><strong>Net</strong></td>
			</tr>
			<?php
			if ($db->num_rows($ret_prods))
			{
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$srno++;
						$org_qty 			= $row_prods['order_orgqty'];
						$sale_price			= $row_prods['product_soldprice'];
						$disc					= $row_prods['order_discount'];
						$disc_per_item	= ($disc/$org_qty);
						$net_total			= $sale_price * $row_prods['order_qty'];
						if($row_prods['order_discount']>0)
							$cur_disc = $row_prods['order_qty'] * $disc_per_item;
						else
							$cur_disc = 0;
						$show_man_id = '';
						// Check whether the current product still exists in products table
						$sql_check = "SELECT product_id,manufacture_id  
												FROM
												products
												WHERE
												product_id = ".$row_prods['products_product_id']."
												AND sites_site_id = $ecom_siteid
												LIMIT
												1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
							if(trim($row_check['manufacture_id'])!='')
							{
								$show_man_id = ' ('.stripslashes(trim($row_check['manufacture_id'])).')';
							}
						}
						else
							$link_req = $link_req_suffix= '';
				?>
				<tr>
					<td width="30%" align="left" valign="top" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?>&nbsp;
					<?php
					if($row_prods['order_freedelivery']==1 and $product_no_link!=1)
						echo '<img src="images/free_del.gif" border="0" alt="Free Delivery" title="Free Delivery was set for this product"/>';
						echo get_ProductVarandMessage($order_id,$row_prods['orderdet_id']);
						if($product_no_link!=1)
							show_barcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id']);
					?>					</td>
					<td width="15%" align="right" valign="top" style="border-bottom:1px solid #000000; border-right:1px solid #000000"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					<td width="8%" align="right" valign="top" style="border-bottom:1px solid #000000; border-right:1px solid #000000">
				<?php
					$disp_msg = '';
					if($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']=='') // case to handle the old orders that is before the introduction of the field order_detail_discount_type
					{
						$disp_msg = '';
						if ($row_prods['order_discount_type']=='custgroup')
						{
							$disp_msg = 'Customer Group Discount<br>Group: '.stripslashes($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
						}
						elseif ($row_prods['order_discount_type']=='customer')
						{
							$disp_msg = 'Customer Discount ';
						}
						elseif ($row_prods['order_discount_type']=='bulk')
						{
							$disp_msg = 'Bulk Discount ';
						}
						elseif ($row_prods['order_discount_type']=='combo')
						{
							$disp_msg = 'Combo Discount ';
						}
						elseif ($row_prods['order_discount_type']=='promotional')
						{
							$disp_msg = 'Promotional Discount ';
						}
						elseif ($row_prods['order_discount_type']=='normal')
						{
							$disp_msg = 'Normal Product Discount ';
						}
					}
					elseif($row_prods['order_discount']>0 and $row_prods['order_detail_discount_type']!='')// case to handle the new orders that is after the introduction of the field order_detail_discount_type
					{
						$disp_msg = display_discount_type($row_prods);	
					}
					  	echo print_price_selected_currency($cur_disc,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true);
					 ?>
				</td>
				<td width="10%" align="right" valign="top"  style="border-bottom:1px solid #000000; border-right:1px solid #000000"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				<td width="8%" align="center" valign="top"  style="border-bottom:1px solid #000000; border-right:1px solid #000000">
				<?php
					echo $row_prods['order_qty'];
				?>
				</td>
				<td width="20%" align="right" valign="top"  style="border-bottom:1px solid #000000">
				<?php 
					echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				</tr>
				<tr id="vartr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
				<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
					<div id="orddet_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
				</td>
				</tr>
				<?php
				}
			}
			else
			{
			?>
			<tr>
				<td colspan="<?php echo $colspan?>" class="norecordredtext" align="center">
					No Items remain in order for despatch
				</td>
			</tr>	
			<?php
			}
		?>
		</table>
			</td>
		</tr>
		<?php
		}
		?>
		</table>
	<?php	
}
function Receiptshow_OrderTotals($order_id,$row_ord)
{
	global $db,$ecom_siteid;
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="1" style="border-left:1px solid #000000;border-right:1px solid #000000">
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">Sub Total</td>
		<td width="24%" colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_subtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">
		<?php 
		if($row_ord['order_freedeliverytype'] !='None' and trim($row_ord['order_freedeliverytype']) !='' )
			echo ' <span class="redtext"><strong>[</strong>'.getFreedeliveryCaption($row_ord['order_freedeliverytype']).'<strong>]</strong> </span>';
		?>+ Total Delivery Charge</td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">+ Total Tax</td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<?PHP if($row_ord['order_giftwraptotal']>0) { ?>
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">+ Total Gift Wrap Charge</td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_giftwraptotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	
	<?PHP 
		}
	if($row_ord['gift_vouchers_voucher_id']>0) {
	$sql = "SELECT voucher_value_used FROM order_voucher WHERE orders_order_id='".$row_ord['order_id']."'";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	$usedprice = $row['voucher_value_used'];
	?>
	<tr>
	  <td colspan="6" align="right" style="border-bottom:1px solid #000000">&nbsp;- Total Gift Voucher Used</td>
	  <td colspan="2" align="right" style="border-bottom:1px solid #000000">&nbsp;<?PHP echo print_price_selected_currency($usedprice,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true) ?></td>
	  </tr>
	 <?PHP } 
	if($row_ord['promotional_code_code_id']>0) {
	$sql = "SELECT code_lessval FROM order_promotional_code WHERE orders_order_id='".$row_ord['order_id']."'";
	$res = $db->query($sql);
	$row = $db->fetch_array($res);
	$usedprice = $row['code_lessval'];
	if($usedprice > 0) {
	?>
	<tr>
	  <td colspan="6" align="right" style="border-bottom:1px solid #000000">&nbsp;- Total Promotional Code Discount</td>
	  <td colspan="2" align="right" style="border-bottom:1px solid #000000">&nbsp;<?PHP echo print_price_selected_currency($usedprice,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true) ?></td>
	  </tr>
	 <?PHP }  } ?> 
	<?php
		if($row_ord['order_customer_discount_value']>0) // Check whether discount exists
		{
			if($row_ord['order_customer_or_corporate_disc']=='CUST')
			{
				if($row_ord['order_customer_discount_type']=='Disc_Group')
				$caption = 'Customer Group Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			else
				$caption = 'Customer Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			}
			elseif($row_ord['order_customer_or_corporate_disc']=='CORP')
			{
				$caption = 'Corporate Discount ('.$row_ord['order_customer_discount_percent'].'%)';
			}
	?>
	
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">- <?php echo $caption?></td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_customer_discount_value'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<?php
		}
	?>
	<?php
		if($row_ord['order_bonuspoint_discount']>0) // Check whether discount due to bonus points exists
		{
	?>
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">- Bonus Points Discount (<?php echo $row_ord['order_bonuspoints_used']?> used)</td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_bonuspoint_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td colspan="6" align="right" style="border-bottom:1px solid #000000">Grand Total </td>
		<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_totalprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
	</tr>
	
	<?php
		if($row_ord['order_refundamt']>0) // Check whether deposit exists and not cleared yet
		{
	?>
		<tr>
			<td colspan="6" align="right" style="border-bottom:1px solid #000000">Total Refunded</a></td>
			<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_refundamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		</tr>
		<tr>
			<td colspan="6" align="right" style="border-bottom:1px solid #000000">Total Remaining after Refund</td>
			<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_refundamt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
		</tr>
	<?php
		}
	?>
	<?php
		if($row_ord['order_deposit_amt']>0 and $row_ord['order_deposit_cleared']!=1) // Check whether deposit exists and not cleared yet
		{
	?>
			<tr>
				<td colspan="6" align="right" style="border-bottom:1px solid #000000">Product Deposit Amount</td>
				<td colspan="2" align="right" style="border-bottom:1px solid #000000"><?php echo print_price_selected_currency($row_ord['order_deposit_amt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
			</tr>
			<tr>
			<tr>
				<td colspan="6" align="right" style="border-bottom:1px solid #000000">
				<div id="productdeposit_div">
				<span class='homecontentusertabletdA'>Amount Remaining to be Released</span>
			
				</div></td>
				<td colspan="2" align="right" style="border-bottom:1px solid #000000"><span class="homecontentusertabletdA"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></span></td>
			</tr>
	<?php
		}
	?>
	</table>
<?php	
}
?>