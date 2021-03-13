<?php
require("functions/functions.php");
require("includes/session.php");
require("includes/price_display.php");
require("includes/urls.php");
require("config.php");
function identify_type($pass_type)
{
	$type_arr = explode('_',$pass_type);
	switch($type_arr[0])
	{
		case 'PRODUCTSRECC'://Order
			$itemname 	= 'Order';
			$m_id		= $type_arr[1];
		break;	
		//case 'GIFT_VOCUHER_PURCHASERECC': // voucher
		case 'GIFTVOCUHERPURCHASERECC': // voucher
			$itemname 	= 'Voucher';
			$m_id		= $type_arr[1];
		break;
		case 'PAYONBILLRECC': // payonaccount
			$itemname 	= 'Payonaccount';
			$m_id		= $type_arr[1];
		break;	
	};
	$ret_arr[0] = $itemname;
	$ret_arr[1] = $m_id;
	return $ret_arr;
}

if($_REQUEST["_type"] == 'new-order-notification') {
	//$type_arr = identify_type($_REQUEST["shopping-cart_items_item-1_item-name"]);
	$type_arr 	= identify_type($_REQUEST["shopping-cart_items_item-1_merchant-item-id"]);
	$typename 	= $type_arr[0];
	$typeid		= $type_arr[1];	
	if($typename == 'Order')
	{
		if ($typeid)
		{
			// Check whether an entry exists in order_payment_main table for current order id
			$sql_check = "SELECT orders_order_id 
							FROM 
								order_payment_main  
							WHERE 
								orders_order_id=$typeid 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);					
			if($db->num_rows($ret_check))
			{					
				$sql_update	= "UPDATE order_payment_main  
								SET 
									order_googletransId='".$_REQUEST["google-order-number"]."' 
								WHERE 
									orders_order_id=$typeid 
								LIMIT 
									1";
				mysql_query($sql_update);
			}
			else
			{
				$sql_insert	= "INSERT INTO order_payment_main  
								SET 
									order_googletransId='".$_REQUEST["google-order-number"]."',
									orders_order_id=$typeid,
									sites_site_id=$ecom_siteid";
				mysql_query($sql_insert);
			}	
		}
	}
	if($typename == 'Voucher')
	{
		if ($typeid)
		{
			// Check whether an entry exists in gift_vouchers_payment table for current voucher id
			$sql_check = "SELECT payment_id  
							FROM 
								gift_vouchers_payment 
							WHERE 
								gift_vouchers_voucher_id=$typeid 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);	
						
			if($db->num_rows($ret_check))
			{					
				$row_check = $db->fetch_array($ret_check);
				$sql_update	= "UPDATE gift_vouchers_payment 
								SET 
									google_checkoutid='".$_REQUEST["google-order-number"]."' 
								WHERE 
									gift_vouchers_voucher_id=$typeid 
									AND payment_id=".$row_check['payment_id']." 
								LIMIT 
									1";
				mysql_query($sql_update);
			}
			else
			{
				$sql_insert	= "INSERT INTO gift_vouchers_payment 
								SET 
									google_checkoutid='".$_REQUEST["google-order-number"]."',
									gift_vouchers_voucher_id=$typeid";
				mysql_query($sql_insert);
			}	
		}
	}
	if($typename == 'Payonaccount')
	{
		if ($typeid)
		{
			// Check whether an entry exists in gift_vouchers_payment table for current voucher id
			$sql_check = "SELECT payment_id  
							FROM 
								order_payonaccount_pending_details_payment  
							WHERE 
								order_payonaccount_pendingpay_id=$typeid 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);					
			if($db->num_rows($ret_check))
			{					
				$row_check = $db->fetch_array($ret_check);
				$sql_update	= "UPDATE order_payonaccount_pending_details_payment 
								SET 
									google_checkoutid='".$_REQUEST["google-order-number"]."' 
								WHERE 
									order_payonaccount_pendingpay_id=$typeid 
									AND payment_id=".$row_check['payment_id']." 
								LIMIT 
									1";
				mysql_query($sql_update);
			}
			else
			{
				$sql_insert	= "INSERT INTO order_payonaccount_pending_details_payment 
								SET 
									google_checkoutid='".$_REQUEST["google-order-number"]."',
									order_payonaccount_pendingpay_id=$typeid";
				mysql_query($sql_insert);
			}
		}
	
	}
}
if($_REQUEST["_type"] == 'order-state-change-notification')
{
	$order_id = $voucher_id = $pay_id = 0;
	$checkoutid = $_REQUEST["google-order-number"];
	// Check whether this id is related to order or gift voucher or payonaccount 
	$sql_check = "SELECT orders_order_id  
					FROM 
						order_payment_main 
					WHERE 
						order_googletransId='".$checkoutid."' 
					LIMIT 
						1";
	$ret_check = $db->query($sql_check);					
	if($db->num_rows($ret_check))
	{
		$row_check 	= $db->fetch_array($ret_check);
		$order_id	= $row_check['orders_order_id'];  
	}
	if(!$order_id)
	{
		// Checking in gift voucher section 
		$sql_check = "SELECT gift_vouchers_voucher_id  
					FROM 
						gift_vouchers_payment  
					WHERE 
						google_checkoutid='".$checkoutid."' 
					LIMIT 
						1";
		$ret_check = $db->query($sql_check);					
		if($db->num_rows($ret_check))
		{
			$row_check 	= $db->fetch_array($ret_check);
			$voucher_id	= $row_check['gift_vouchers_voucher_id'];  
		}
	}
	if(!$order_id and !$voucher_id)
	{
		// Check in payonaccount 
		$sql_check = "SELECT order_payonaccount_pendingpay_id   
					FROM 
						order_payonaccount_pending_details_payment   
					WHERE 
						google_checkoutid='".$checkoutid."' 
					LIMIT 
						1";
		$ret_check = $db->query($sql_check);					
		if($db->num_rows($ret_check))
		{
			$row_check 	= $db->fetch_array($ret_check);
			$pay_id		= $row_check['order_payonaccount_pendingpay_id'];  
		}
	}
	if($_REQUEST['new-financial-order-state'] == 'CHARGEABLE')
	{
		if($order_id)
		{
			// Sending required emails
			send_RequiredOrderMails($order_id,"Payment Confirmed<br> Google Checkout Id: $checkoutid");
		}	
	}
	if($_REQUEST['new-financial-order-state'] == 'CHARGED') 
	{
		if ($order_id)
		{
			// Check whether cost per click and/or hits to sale report is to be updated
			$sql_ord = "SELECT order_totalprice,order_cpc_keyword, order_cpc_se_id, order_cpc_click_id,
								order_cpc_click_pm_id, order_cost_per_click_id  
								FROM 
									orders 
								WHERE 
									order_id=".$order_id." 
								LIMIT 
									1";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{
				$row_ord 		= $db->fetch_array($ret_ord);
				$total_price 	= $row_ord['order_totalprice'];
				// Deciding whether to call the cost per click order total saving section
				$const_ids 		= trim($row_ord['order_cost_per_click_id']);
				if ($const_ids!='')
				{
					cost_per_click($const_ids,$total_price);
				}
				// Case of hits to sale report
				if($row_ord['order_cpc_click_id'] > 0)
				{
					seo_revenue_report($row_ord,$total_price);
				}
			}
			$update_sql = "UPDATE orders 
							SET 
								order_paystatus = 'Paid',
								order_status='NEW',
								order_cost_per_click_id='',
								order_cpc_keyword='',
								order_cpc_se_id=0,
								order_cpc_click_id=0,
								order_cpc_click_pm_id=0  
							WHERE 
								order_id=$order_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($update_sql);						
			do_PostOrderSuccessOperations($order_id);
		}
		if($voucher_id)
		{
			$sql_update = "UPDATE gift_vouchers
							SET
								voucher_paystatus='Paid',
								voucher_activatedon = curdate(),
								voucher_incomplete=0,
								voucher_expireson	= DATE_ADD(curdate(),INTERVAL voucher_activedays DAY)
							WHERE
								voucher_id = ".$voucher_id." 
								AND sites_site_id=$ecom_siteid 
							LIMIT
								1";
			$db->query($sql_update);
			send_RequiredVoucherMails($voucher_id,"Paid (Google Checkout Id: $checkoutid)");
		}
		if($pay_id)
		{
			do_PostPayonAccountSuccessOperations($pay_id);
		}
	}
}
if($_REQUEST["_type"] == 'refund-amount-notification') {
	$google_id 	= $_REQUEST["google-order-number"];
	$ref_amt	= $_REQUEST["latest-refund-amount"];
	// Check whether any order exists with current google checkout id in current site
	$sql_pay = "SELECT orders_order_id 
					FROM 
						order_payment_main 
					WHERE 
						sites_site_id = $ecom_siteid 
						AND order_googletransId = '".$google_id."' 
					LIMIT 
						1" ;
	$ret_pay = $db->query($sql_pay);
	if ($db->num_rows($ret_pay))
	{
		$row_pay 	= $db->fetch_array($ret_pay);
		$order_id 	= $row_pay['orders_order_id'];
		$sql_ord = "SELECT order_status,order_deposit_amt,order_deposit_cleared,customers_customer_id,
							order_currency_symbol,order_currency_convertionrate,
							order_currency_numeric_code,order_currency_convertionrate,
							order_totalprice,order_refundamt,order_paystatus,
							order_paymenttype,order_paymentmethod,order_currency_code,
							order_totalauthorizeamt,order_paystatus_changed_manually     
					FROM 
						orders 
					WHERE 
						order_id = $order_id  
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord))
		{
			$err = 0;
			$row_ord = $db->fetch_array($ret_ord);
			if($row_ord['order_paystatus']=='REFUNDED')// Check whether the order is already refunded
			{
				$err = 1;
			}
			elseif($row_ord['order_paystatus']!='Paid')
			{
				$err = 1;
			}
			else
			{
				if ($row_ord['order_deposit_amt']>0)
				{
					if ($row_ord['order_deposit_cleared']==0) // if remaining amount not cleared
					{
						if ($row_ord['order_totalauthorizeamt']>0)	
							$allowable_refund_amt = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];
						else
							$allowable_refund_amt = $row_ord['order_deposit_amt']-$row_ord['order_refundamt'];
					}
					else 
					{
						if ($row_ord['order_totalauthorizeamt']>0)		
							$allowable_refund_amt = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];	
						else 	
							$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
					}	
				}
				else
				{
					if ($row_ord['order_totalauthorizeamt']>0)		
						$allowable_refund_amt = $row_ord['order_totalauthorizeamt']-$row_ord['order_refundamt'];	
					else	
						$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
				}
				$allowable_refund_amt = print_price_selected_currency($allowable_refund_amt,$row_ord['order_currency_convertionrate'],'',true);
				
				//$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
				if ($ref_amt>$allowable_refund_amt)
				{
					$err = 1;
				}
				else
				{
					if($row_ord['order_totalauthorizeamt']>0)
					{
						if ($row_ord['order_totalauthorizeamt']==$row_ord['order_refundamt'])
						{
							$err = 1;
						}
					}	
					else 
					{
						if ($row_ord['order_totalprice']==$row_ord['order_refundamt'])
						{
							$err = 1;
						}
					}
				}
			}
			if($err == 0)
			{
				$ref_amt 		= convert_price_default_currency($ref_amt,$row_ord['order_currency_convertionrate']);
				//Update the orders table to add the refunded amount to the order_refundamt 
				$update_sql = "UPDATE orders 
								SET 
									order_refundamt = order_refundamt + $ref_amt 
								WHERE 
									order_id = $order_id 
								LIMIT 
									1";
				$db->query($update_sql);
				// Insert the refund amount to order_details_refunded table
				$insert_array						= array();
				$insert_array['refund_on']			= 'now()';
				$insert_array['refund_by']			= 0;
				$insert_array['refund_amt']			= $ref_amt;
				$insert_array['orders_order_id']	= $order_id;
				$db->insert_from_array($insert_array,'order_details_refunded');
				$refunded_id = $db->insert_id();
				
				// Making an entry for reason in order_notes table
				$insert_array						= array();
				$insert_array['orders_order_id']	= $order_id;
				$insert_array['note_add_date']		= 'now()';
				$insert_array['user_id']			= 0;
				$insert_array['note_text']			= 'Refunded Directly from Google Checkout';
				$insert_array['note_type']			= 7;
				$insert_array['note_related_id']	= $refunded_id;
				$db->insert_from_array($insert_array,'order_notes');
				// Check whether full amount is refunded. If so change the payment status to REFUNDED and order status to not CANCELLED
				$sql_update = "UPDATE orders 
									SET 
										order_paystatus = 'REFUNDED',
										order_status = 'CANCELLED' 
									WHERE 
										order_id = $order_id 
										AND order_refundamt = order_totalprice 
									LIMIT 
										1";
				$db->query($sql_update);
				// Saving and sending mail over here
				$ord_arr['order_id']						= $order_id;
				$ord_arr['refund_amt']						= $ref_amt; 
				$ord_arr['refund_note']						= 'Refunded Directly from Google Checkout'; 
				$ord_arr['refunded_prods'] 					= $refunded_arr;
				$ord_arr['order_currency_convertionrate'] 	= $row_ord['order_currency_convertionrate'];
				$ord_arr['order_currency_symbol'] 			= $row_ord['order_currency_symbol'];
				save_and_send_OrderMail('REFUNDED',$ord_arr);
			}	
		}	
	}					
	/*$sql = "SELECT order_id,total_price,payStatus FROM orders WHERE site_id=$ecom_siteid AND google_checkoutid='".$_REQUEST["google-order-number"]."'";
	$res = mysql_query($sql);
	$refund = $_REQUEST["latest-refund-amount"];
	list($order_id,$total_price,$payStatus) = mysql_fetch_array($res);
	$tot_price = convert_price($total_price,$order_id)- $refund;
	//Adding current amount to the refund field in order table
	$sql_update = "UPDATE orders SET refund_amt = refund_amt + $refund,total_price=total_price-$refund WHERE order_id=$order_id";
	mysql_query($sql_update);
	$sql_update = "UPDATE orders SET total_price=0 WHERE total_price<0 and order_id=$order_id";
	mysql_query($sql_update);
	$update_status = "UPDATE orders SET status='Refunded',payStatus='Refunded',refundcomp_date=now() WHERE order_id=$order_id AND total_price=0";
	mysql_query($update_status);
	$refund_note = "Refunded from Google Checkout";*/
}
?>
