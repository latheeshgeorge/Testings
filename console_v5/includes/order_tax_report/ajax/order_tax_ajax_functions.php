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
 						costperclick_id,order_despatched_completly_on,order_specialtax_calculation,
 						order_specialtax_totalamt,order_specialtax_productamt, order_specialtax_deliveryamt,
 						order_specialtax_extrashippingamt  
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
	$cls = 'listingtablestyleB';
?>
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<?php 
		if($help_msg!='')
		{
		?>
			<tr>
				<td colspan="5" align="left" class="helpmsgtd">
				<?php echo $help_msg?>				</td>
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
           <a href="home.php?request=orders&fpurpose=ord_details&edit_id=<?php echo $order_id?>" class="edittextlink">
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
			</a>
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
		 	$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
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
	</td>
         </tr>
		 <?php
		 	$pay_str = $pay_usr = '';
		    if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				$pay_usr = getConsoleUserName($row_ord['order_paystatus_changed_manually_by']).' ( on '.dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime').')';
				$pay_str =  "Payment Status Changed By ";
			}
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
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
	  </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" >
		  </td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		  </td>
           <td colspan="2" align="left" valign="middle" class="<?php echo $cls?>">
		  
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
	
	// Get the details of current order
	$row_ord = fetch_Order_Details($order_id);
	// Calling function to show the header section
	if($fromprint!=1)
		show_orderdetails_header($order_id);
	else // case if coming from print_order_details.php page
		show_orderdetails_header($order_id,0,'',1);
		
	// Calling function to show the items remaining in order
	show_Products_Remaining_In_Order($order_id,$row_ord,'main',$fromprint);
	
 }
   /*  Function to show the notes and emails details */
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
						order_discount_group_name,order_discount_group_percentage,order_freedelivery,
						order_detail_discount_type,order_prom_id,orderdet_specialtax_productamt,
						orderdet_specialtax_extrashippingamt,order_taxcalc_qty     
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
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td colspan="4" align="left" valign="bottom">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
				<td width="62%" align="left" class="seperationtd_special" colspan="2">
				Products Remaining in Order
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
		$table_headers 		= array('Product','Sale Price','Qty in Order','Total Price','Tax on Product','Tax on Extra Shipping','Total Tax');
		$header_positions	= array('left','right','right','right','right','right','right');
		$colspan 		= count($table_headers);
		?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			$srno=1;
			$atleast_one = false;
			echo table_header($table_headers,$header_positions);
			$total_tax =$prodtotal_tax=$extratotal_tax = 0;
			if ($db->num_rows($ret_prods))
			{
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
						$total_tax += ($row_prods['orderdet_specialtax_extrashippingamt']+$row_prods['orderdet_specialtax_productamt']);
						$prodtotal_tax+= $row_prods['orderdet_specialtax_productamt'];
						$extratotal_tax+= $row_prods['orderdet_specialtax_extrashippingamt'];
						
						$srno++;
						
						$org_qty 			= $row_prods['order_taxcalc_qty'];
						$sale_price			= $row_prods['product_soldprice'];
						$disc				= $row_prods['order_discount'];
						$disc_per_item		= ($disc/$org_qty);
						$net_total			= $sale_price * $row_prods['order_taxcalc_qty'];
						if($row_prods['order_discount']>0)
							$cur_disc = $row_prods['order_taxcalc_qty'] * $disc_per_item;
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
					<td width="25%" align="left" valign="top" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?><?php echo $show_man_id?><?php echo $show_model?>&nbsp;
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
				<td width="10%" align="right" valign="top" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
				<td width="8%" align="center" valign="top" class="<?php echo $cls?>">
				<?php
					echo $row_prods['order_taxcalc_qty'];
				?>
				</td>
				<td width="12%" align="right" valign="top" class="<?php echo $cls?>">
				<?php 
					echo print_price_selected_currency($net_total,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				<td width="12%" align="right" valign="top" class="<?php echo $cls?>">
				<?php 
					echo print_price_selected_currency($row_prods['orderdet_specialtax_productamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				<td width="12%" align="right" valign="top" class="<?php echo $cls?>">
				<?php 
					echo print_price_selected_currency($row_prods['orderdet_specialtax_extrashippingamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				<td width="12%" align="right" valign="top" class="<?php echo $cls?>">
				<?php 
					echo print_price_selected_currency(($row_prods['orderdet_specialtax_extrashippingamt']+$row_prods['orderdet_specialtax_productamt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
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
				?>
				<tr>
				<td align="right" colspan="4" class="shoppingcartpriceB">&nbsp;
				</td>
				<td align="right" class="shoppingcartpriceB" >
				-----------------
				</td>
				<td align="right" class="shoppingcartpriceB" >
				-----------------
				</td>
				<td align="right" class="shoppingcartpriceB" >
				-----------------
				</td>
				</tr>
				<tr>
				<td align="right" colspan="4" class="shoppingcartpriceB">Total
				</td>
				<td align="right" class="shoppingcartpriceB" >
				<?php 
					echo print_price_selected_currency($prodtotal_tax,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				<td align="right" class="shoppingcartpriceB" >
				<?php 
					echo print_price_selected_currency($extratotal_tax,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				<td align="right" class="shoppingcartpriceB" >
				<?php 
					echo print_price_selected_currency($total_tax,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				</tr>
				<tr>
				<td align="right" colspan="6" class="shoppingcartpriceB">(Delivery Charge: <?php 
					echo print_price_selected_currency($row_ord['order_deliveryprice_only'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?> )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( + ) Tax on Delivery Charge 
				</td>
				<td align="right" class="shoppingcartpriceB" >
				<?php 
					echo print_price_selected_currency($row_ord['order_specialtax_deliveryamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				</tr>
				<tr>
				<td align="right" colspan="6" class="shoppingcartpriceB">&nbsp;
				</td>
				<td align="right" class="shoppingcartpriceB" >
				-----------------
				</td>
				</tr>
				<tr>
				<td align="right" colspan="6" class="shoppingcartpriceB">Total Tax Payable
				</td>
				<td align="right" class="shoppingcartpriceB" >
				<?php 
					echo print_price_selected_currency($row_ord['order_specialtax_totalamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				?>
				</td>
				</tr>
				<tr>
				<td align="right" colspan="6" class="shoppingcartpriceB">&nbsp;
				</td>
				<td align="right" class="shoppingcartpriceB" >
				=================
				</td>
				</tr>
				<?php
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
?>
