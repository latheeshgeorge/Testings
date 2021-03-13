<?php
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/orders/list_orders.php');
	}
	elseif($_REQUEST['fpurpose']=='ord_details') //  View details page
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$_REQUEST['checkbox'][0] = $_REQUEST['edit_id'];
		include ('includes/orders/order_details.php');
	}
	elseif($_REQUEST['fpurpose']=='show_order_summary') // show order summary tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Summary($_REQUEST['order_id']);
		//show_billing_address($_REQUEST['ord_id']);	
		/*
		case 'order_refund':
			fpurpose ='';
		break;
		case 'order_return':
			fpurpose ='';
		break;
		case 'order_notes':
			fpurpose ='';
		break;
		case 'order_custquery':
			fpurpose ='';
		break;
		case 'order_other':
			fpurpose ='';
		break;
		*/
	}
	elseif($_REQUEST['fpurpose']=='show_order_payment') //  show payment tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Payments($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_despatch') //  show despatch tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Despatch($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_refund') //  show refund tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Refunds($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_return') //  show return tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Returns($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_notes') //  show notes tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_NotesandEmails($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_custquery') //  show customer query tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_CustomerQueries($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='show_order_other') //  show others tab
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		 show_Order_Others($_REQUEST['order_id']);
	} 
	elseif($_REQUEST['fpurpose']=='list_billing_address') //  List billing details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_billing_address($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_delivery_address') //  List delivery details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_delivery_address($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_giftwrap_details') //  List giftwrap details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_giftwrap_details($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_tax_details') //  List tax details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_tax_details($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_voucher_details') //  List voucher details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_voucher_details($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_promotional_details') //  List promotional code details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_promotional_details($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_payment_details') //  List payment details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_payment_details($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='list_notes') //  List order notes section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_order_notes($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='save_note') //  Save order notes
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		// Validating the fields
		if(trim($_REQUEST['note'])!='')
		{
			// Inserting the note
			$insert_array						= array();
			$insert_array['orders_order_id']	= $_REQUEST['ord_id'];
			$insert_array['note_add_date']		= 'now()';
			$insert_array['user_id']			= $_SESSION['console_id'];
			$insert_array['note_text']			= add_slash($_REQUEST['note']);
			$insert_array['note_type']			= 0;
			$db->insert_from_array($insert_array,'order_notes');
			$alert = 'Note added successfully';
		}
		else
			$alert = 'Please specify the note';
		show_order_notes($_REQUEST['ord_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose']=='delete_note') //  Delete order notes
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		// Validating the fields
		if(trim($_REQUEST['noteid'])!='')
		{
			// Deleting the selected order note
			$sql_del = "DELETE FROM order_notes WHERE note_id = ".$_REQUEST['noteid']." LIMIT 1";
			$db->query($sql_del);
			$alert = 'Note deleted successfully';
		}
		else
			$alert = 'Please select the note to be deleted';
		show_order_notes($_REQUEST['ord_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose']=='list_order_emails') //  List Emails Related to orders
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_order_emails($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='resend_OrderEmail') //  Resend selected order email
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		if($_REQUEST['emailid'])
		{
			$sql = "SELECT email_id,email_to,email_subject,email_message,email_headers,
							email_type,email_was_disabled,email_sendonce,email_lastsenddate 
						FROM 
							order_emails 
						WHERE 
							email_id = ".$_REQUEST['emailid']." 
						LIMIT 
							1";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				$row = $db->fetch_array($ret);
				// Call function to send the selected mail again
				resend_orderEmail($_REQUEST['emailid'],$_REQUEST['ord_id']);
				
				// Calling the function to save the email history
				save_EmailHistory($_REQUEST['emailid'],$_REQUEST['ord_id']);
				
				$alert = 'Mail Send Successfully';
			}	 
			else
				$alert ='Sorry!! Email not found';
		}
		else
			$alert = 'Select the order mail to be send';
		show_order_emails($_REQUEST['ord_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose']=='list_operations') //  Resend selected order email
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_operations($_REQUEST['ord_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_changeorderstatus_sel') //  Selected order status from drop down for changing the status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		switch($_REQUEST['sel_stat'])
		{
			case 'CANCELLED':
				order_additionaldet_cancel($_REQUEST['ord_id']);
			break;
			case 'ONHOLD':
				order_additionaldet_common();
			break;
			case 'BACK':
				order_additionaldet_common();
			break;
		};
	}
	elseif($_REQUEST['fpurpose']=='operation_changeorderstatus_do') //  Changing the order status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		
		$order_id 				= $_REQUEST['ord_id'];
		$ch_stat				= $_REQUEST['sel_stat'];
		$alt_prods				= $_REQUEST['p_ids'];
		
		$stat_arr['stock_return'] 			= $_REQUEST['stock_return'];
		$stat_arr['bonusused_return'] 		= $_REQUEST['bonusused_return'];	
		$stat_arr['bonusearned_return'] 	= $_REQUEST['bonusearned_return'];	
		$stat_arr['maxvoucher_return'] 		= $_REQUEST['maxvoucher_return'];
		$stat_arr['force_cancel'] 			= $_REQUEST['force_cancel'];	
				
		// Get the current status of current order
		$sql_ord = "SELECT 	order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,order_status,
							order_currency_convertionrate,order_currency_symbol,order_custemail,
							order_subtotal,order_giftwraptotal,order_deliverytotal,order_tax_total,
							order_customer_discount_value,order_customer_or_corporate_disc,
							order_customer_discount_type,order_customer_discount_percent,order_totalprice,
							order_deposit_amt,order_deposit_amt,gift_vouchers_voucher_id,promotional_code_code_id,
							order_bonuspoint_discount,order_paymentmethod
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
			if ($row_ord['order_status']!=$ch_stat)
			{
				if($ch_stat!='CANCELLED') // case of status changing to other than Cancelled
				{
					$update_array					= array();
					$update_array['order_status']	= add_slash($ch_stat);
					$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
					$alert = 'Order Status Changed Successfully';
					// Check whether note is added
					$not = trim($_REQUEST['note']);
					if($not!='') // case if note exists
					{
						// Inserting the note to the order_notes table
						$insert_array						= array();
						$insert_array['orders_order_id']	= $_REQUEST['ord_id'];
						$insert_array['note_add_date']		= 'now()';
						$insert_array['user_id']			= $_SESSION['console_id'];
						$insert_array['note_text']			= add_slash($not);
						$insert_array['note_type']			= get_order_status_text_to_number($ch_stat);
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Reason added as note';
					}
				}
				else // case of status changing to Cancelled
				{
						
					$ret_arr = do_ordercancelReturns($order_id,$stat_arr);
					if($ret_arr['msg']=='REFUND_OR_DESPATCH')
					{
						$alert = 'Sorry!! Status cannot be changed since some of the products have been already despatched/refunded';
					}
					else
					{
						$update_array							= array();
						$update_array['order_status']			= add_slash($ch_stat);
						$update_array['order_cancelled_on']		= 'now()';
						$update_array['order_cancelled_from']	= 'A'; // from admin side
						// When an order is cancelled, if the payment method is not protx change the payment status also to Refunded
						// and set the refund amount = total amount
						if ($row_ord['order_paymentmethod']!='PROTX')
						{
							$update_array['order_paystatus']	= 'REFUNDED'; 
							$update_array['order_refundamt']	= $row_ord['order_totalprice']; 
						}	
						$update_array['order_cancelled_by']		= $_SESSION['console_id'];
						$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
						$alert = 'Order Status Changed Successfully';
						
						
						$not = trim($_REQUEST['note']);
						// Inserting the note to the order_notes table
						$insert_array								= array();
						$insert_array['orders_order_id']	= $_REQUEST['ord_id'];
						$insert_array['note_add_date']	= 'now()';
						$insert_array['user_id']				= $_SESSION['console_id'];
						$insert_array['note_text']			= add_slash($not);
						$insert_array['note_type']			= 5;
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Reason added as note';
						
						// Calling the function to get the details related to the selected alternate products
						$alternate_str = get_AlternateProductDetailsString($alt_prods,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol']);
						
						// Calling the function to send order cancellation mail
						$row_ord['alt_prods'] 	= $alternate_str;
						$row_ord['reason'] 		= $not;
						save_and_send_OrderMail('cancel',$row_ord);
					}	
					
					// Removing the downloadable product entry against this order
					$sql_down = "SELECT ord_down_id 
											FROM 
												order_product_downloadable_products 
											WHERE 
												orders_order_id = $order_id";
					$ret_down = $db->query($sql_down);
					if ($db->num_rows($ret_down))
					{
						while ($row_down = $db->fetch_array($ret_down))
						{
							// Deleting the customer download track for the items in order being tracked
							$sql_del = "DELETE FROM 
												order_product_downloadable_products_customer_track 
											WHERE 
												order_product_downloadable_products_ord_down_id=".$row_down['ord_down_id']." 
												AND sites_site_id=$ecom_siteid";
							$db->query($sql_del);
						}
						$sql_del = "DELETE FROM 
												order_product_downloadable_products 
											WHERE
												orders_order_id = $order_id";
						$db->query($sql_del);
					}
				}
				// Calling the function to decide upon which tab details to be displayed
				decide_show_tab($_REQUEST['curtab'],$order_id,$alert);
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='show_alternateproduct_selsection') //  Case of showing the products to be added to alternate product list
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		select_AlternateProduct($_REQUEST['sel_cat'],$_REQUEST['sel_pn'],$_REQUEST['sel_prd']);
	}
	/*elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_sel') //  Selected order payment status from drop down for changing the payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_additionaldet_paymentstatus();
	}*/
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_Paidsel') //  Selected order payment status from drop down for changing the payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_PayReceived_TakeDetails();
	}
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_Failsel') //  Selected order payment status from drop down for changing the payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_PayFailed_TakeDetails();
	}
	
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_do') //  Changing the order payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$order_id 		= $_REQUEST['ord_id'];
		$ch_stat		= $_REQUEST['sel_stat'];
		// Get the current status of current order
		$sql_ord = "SELECT 	order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,
							order_status,order_paystatus,
							order_currency_convertionrate,order_currency_symbol,order_custemail,
							order_subtotal,order_giftwraptotal,order_deliverytotal,order_tax_total,
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
		$ret_ord = $db->query($sql_ord);
		if ($db->num_rows($ret_ord))
		{
			$row_ord = $db->fetch_array($ret_ord);
			if ($row_ord['order_paystatus']!=$ch_stat)
			{
				$update_array											= array();
				$update_array['order_paystatus']						= add_slash($ch_stat);
				$update_array['order_paystatus_changed_manually']		= 1;
				$update_array['order_paystatus_changed_manually_by']	= $_SESSION['console_id'];
				$update_array['order_paystatus_changed_manually_on']	= 'now()';
				$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
				
				if ($ch_stat=='Paid')
				{
					// Check whether there exists any downloadable products linked with current order. If yes then decide whether the start and end date is to be set for any
					$update_sql = "UPDATE order_product_downloadable_products 
												SET 
													proddown_days_active_start = now(),
													proddown_days_active_end = DATE_ADD(now(), INTERVAL proddown_days DAY) 
												WHERE 
													orders_order_id = $order_id 
													AND proddown_days_active=1";
					$db->query($update_sql);
					// If status is changed to paid, then send the confirmation and other mails 
					send_RequiredOrderMails($order_id);		
					$typ_str = 1;
				}
				else
					$typ_str = 2;
				// Calling function to save and send payment status change mail
				save_and_send_OrderMail($ch_stat,$row_ord);
				
				$alert = 'Payment Status Changed Successfully';
				// Check whether note is added
				$not = trim($_REQUEST['note']);
				if($not!='') // case if note exists
				{
					// Inserting the note to the order_notes table
					$insert_array								= array();
					$insert_array['orders_order_id']	= $_REQUEST['ord_id'];
					$insert_array['note_add_date']	= 'now()';
					$insert_array['user_id']				= $_SESSION['console_id'];
					$insert_array['note_text']			= add_slash($not);
					$insert_array['note_type']			= $typ_str;
					$db->insert_from_array($insert_array,'order_notes');
					$alert .= '. Reason added as note';
				}
				// Calling the function to show the operation section in order details page
				show_operations($_REQUEST['ord_id'],$alert,1);
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='release_proddeposit')// Case of releasing remaining product deposit
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$order_id = $_REQUEST['ord_id'];
		
		// Check whether deposit is already cleared for this order
		$sql_check = "SELECT order_deposit_cleared 
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$row_check = $db->fetch_array($ret_check);
			if ($row_check['order_deposit_cleared']==0)
			{
				$update_array								= array();
				$update_array['order_deposit_cleared']		= 1;
				$update_array['order_deposit_cleared_on']	= 'now()';
				$update_array['order_deposit_cleared_by']	= $_SESSION['console_id'];
				
				$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
				$alert = 'Remaining Amount released Successfully';
			}
			else
				$alert = 'Sorry!! Amount already released';
			// Calling the function to show the operation section in order details page
			show_operations($_REQUEST['ord_id'],$alert,1);	
		}
	}
	elseif($_REQUEST['fpurpose']=='operation_despatched_sel') //  clicked the despatched button and now show the reason and despatched id section
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_additionaldet_despatched();
	}
	elseif($_REQUEST['fpurpose']=='operation_despatched_do') //  came by submitting the form in case of items despatched.
	{
		if (count($_REQUEST['checkboxprod']))
		{
			$atleast_one 	= false;
			$despatchid_arr 	= $despatchqty_arr = array();
			// Making updations to the respective products in order details table
			$despatch_id 	= (trim($_REQUEST['txt_despatch_id'])!='')?trim($_REQUEST['txt_despatch_id']):'';
			$despatch_note	= (trim($_REQUEST['txt_despatch_note'])!='')?trim($_REQUEST['txt_despatch_note']):'';
			for($i=0;$i<count($_REQUEST['checkboxprod']);$i++)
			{
				$cur_qty = $_REQUEST['qty_'.$_REQUEST['checkboxprod'][$i]];
				if($cur_qty>0)
				{
					// Get the qty remaining for current item in order details table
					$sql_orderdet = "SELECT order_qty 
										FROM 
											order_details 
										WHERE 
											orderdet_id = ".$_REQUEST['checkboxprod'][$i]." 
										LIMIT 
											1";
					$ret_orderdet = $db->query($sql_orderdet);
					if($db->num_rows($ret_orderdet))
					{
						$row_orderdet = $db->fetch_array($ret_orderdet);
					}						
					if ($row_orderdet['order_qty']>=$cur_qty)
					{
						$atleast_one 						= true;
						$insert_array						= array();
						$insert_array['orderdet_id']		= $_REQUEST['checkboxprod'][$i];
						$insert_array['despatched_qty']		= $cur_qty;
						$insert_array['despatched_on']		= 'now()';
						$insert_array['despatched_by']		= $_SESSION['console_id'];
						if ($despatch_id!='')
							$insert_array['despatched_reference']	= $despatch_id;
						$db->insert_from_array($insert_array,'order_details_despatched');	
						
						$despatchid_arr[] 								= $_REQUEST['checkboxprod'][$i];
						$despatchqty_arr[$_REQUEST['checkboxprod'][$i]] = $cur_qty;
						//Updating the qty in order details table
						$sql_update = "UPDATE order_details 
										SET 
											order_qty = order_qty - $cur_qty 
										WHERE 
											orderdet_id =".$_REQUEST['checkboxprod'][$i]." 
										LIMIT 
											1";
						$db->query($sql_update);
						// Check whether the order_qty for current product is 0, if yes then mark it as despatched='Y'
						$sql_check = "SELECT order_qty,order_dispatched  
										FROM 
											order_details 
										WHERE 
											orderdet_id =".$_REQUEST['checkboxprod'][$i]." 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check = $db->fetch_array($ret_check);
							if ($row_check['order_qty']==0 and $row_check['order_dispatched']=='N')
							{
								$sql_update = "UPDATE order_details 
												SET 
													order_dispatched = 'Y' 
												WHERE 
													orderdet_id=".$_REQUEST['checkboxprod'][$i]." 
												LIMIT 
													1";
								$db->query($sql_update);
							}
						}
					}
				}
			}
			if($atleast_one==true)
			{
				$alert = 'Items despatched Successfully';
				// Check whether all items in current order are despatched. If yes, then change the order status to despatched.
				$sql_check = "SELECT orderdet_id 
								FROM 
									order_details 
								WHERE 
									order_dispatched ='N' 
									AND orders_order_id = ".$_REQUEST['checkbox'][0]." 
								LIMIT 
									1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0) // case if all items are despatched
				{
					$sql_update = "UPDATE 
										orders 
									SET 
										order_despatched_completly_on='now()',
										order_status='DESPATCHED'  
									WHERE 
										order_id = ".$_REQUEST['checkbox'][0]." 
									LIMIT 
										1";
					$db->query($sql_update);
					$completly_despatched = true;
				}
				else
					$completly_despatched = false;
				// Making entries to notes section in case if any additional note is specified
				if($despatch_note!='')
				{
					$insert_array								= array();
					$insert_array['orders_order_id']	= $_REQUEST['checkbox'][0];
					$insert_array['note_add_date']	= 'now()';
					$insert_array['user_id']				= $_SESSION['console_id'];
					$insert_array['note_text']			= add_slash($despatch_note);
					$insert_array['note_type']			= 6;
					$db->insert_from_array($insert_array,'order_notes');
					$alert .= '. Additional note saved in notes section';
				}
				// Saving and sending mail over here
				$ord_arr['order_id']				= $_REQUEST['checkbox'][0];
				$ord_arr['despatch_id']				= $despatch_id; 
				$ord_arr['despatch_note']			= $despatch_note; 
				$ord_arr['despatched_prods'] 		= $despatchid_arr;
				$ord_arr['despatched_qtys'] 		= $despatchqty_arr;
				$ord_arr['completly_despatched']	= $completly_despatched;
				save_and_send_OrderMail('DESPATCHED',$ord_arr);
			}	
		}
		else
			$alert = 'Please select the products to be despatched';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/order_details.php');	
	}
	elseif($_REQUEST['fpurpose']=='operation_updateqty_do') //  came by submitting the form in case of update qty of ordered items.
	{
		$cnt = count($_REQUEST['checkboxprod']);	
		if ($cnt>0)
		{
			for($i=0;$i<$cnt;$i++)
			{
				$detid 		= $_REQUEST['checkboxprod'][$i];
				$ch_qty		= $_REQUEST['qty_'.$detid];
				$org_qty	= $_REQUEST['orgqty_'.$detid];
				$rem_qty 	= $ch_qty;
				// Get the existing qty for current item
				$sql_check = "SELECT order_qty 
									FROM 
										order_details 
									WHERE 
										orderdet_id = $detid 
									LIMIT 
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
				}
				$mov_qty	= $row_check['order_qty']-$ch_qty;
				if ($row_check['order_qty']>=$mov_qty)
				{
					if ($mov_qty>0)
					{
						// Check whether an entry exists for current order details id in order_details_removed table
						$sql_check = "SELECT orderdet_id 
										FROM 
											order_details_removed 
										WHERE 
											orderdet_id = $detid 
										LIMIT 
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							// case if exists in order_details_removed
							$sql_update = "UPDATE order_details_removed 
											SET 
												order_qty = order_qty + $mov_qty,
												order_removedon = now() 
											WHERE 
												orderdet_id = $detid 
											LIMIT 
												1";
							$db->query($sql_update);
						}
						else
						{
							$insert_array						= array();
							$insert_array['orderdet_id']		= $detid;
							$insert_array['order_qty']			= $mov_qty;
							$insert_array['order_removedon']	= 'now()';
							$db->insert_from_array($insert_array,'order_details_removed');
						}
						// Updating the qty in order_details table
						$update_array						= array();
						$update_array['order_qty']			= $rem_qty;
						$db->update_from_array($update_array,'order_details',array('orderdet_id'=>$detid));
					}
				}
			}
			$alert = 'Quantity updated successfully';
		}
		else
			$alert = 'Please select the products to be moved';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/order_details.php');	
	}
	elseif($_REQUEST['fpurpose']=='operation_updateqty_back_do') //  came by submitting the form in case of update qty back to order.
	{
		$cnt = count($_REQUEST['checkboxprod_rem']);	
		if ($cnt>0)
		{
			for($i=0;$i<$cnt;$i++)
			{
				$detid 		= $_REQUEST['checkboxprod_rem'][$i];
				$ch_qty		= $_REQUEST['qtyrem_'.$detid];
				$org_qty	= $_REQUEST['orgqtyrem_'.$detid];
				$rem_qty 	= $ch_qty;
				// Get the existing qty for current item
				$sql_check = "SELECT order_qty 
									FROM 
										order_details_removed  
									WHERE 
										orderdet_id = $detid 
									LIMIT 
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					$row_check = $db->fetch_array($ret_check);
				}
				$mov_qty	= $row_check['order_qty']-$ch_qty;
				if ($row_check['order_qty']>=$mov_qty)
				{
					if ($mov_qty>0)
					{
						$sql_update = "UPDATE order_details 
							SET 
								order_qty = order_qty + $mov_qty, 
								order_refunded = 'N',
								order_refundedon = '0000-00-00 00:00:00',
								order_refundedby = 0 
							WHERE 
								orderdet_id = $detid 
							LIMIT 
								1";
						$db->query($sql_update);
						if ($rem_qty==0)
						{
							// remove the entry from order_details_removed table
							$sql_del = "DELETE 
											FROM 
												order_details_removed 
											WHERE 
												orderdet_id = $detid";
							$db->query($sql_del);
											
						}
						else
						{						
							// Updating the qty in order_details table
							$update_array						= array();
							$update_array['order_qty']			= $rem_qty;
							$db->update_from_array($update_array,'order_details_removed',array('orderdet_id'=>$detid));
						}	
					}
				}
			}
			$alert = 'Quantity moved back successfully';
		}
		else
			$alert = 'Please select the products to be moved back';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/order_details.php');	
	}
	elseif($_REQUEST['fpurpose']=='operation_var_despatch') //  view the product variables and despatched details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		if($_REQUEST['show_despatch']==2) // decide whether to show the despatch details along with variable. This is done to reuse the code to show the variables and despatch details for items in order and item removed from order
			$despatch = false;
		else
			$despatch = true;
		order_prodvar_despatch_details($_REQUEST['ord_det'],$_REQUEST['ord_id'],$despatch);
	}
	elseif($_REQUEST['fpurpose']=='operation_refund_sel') //  clicked the refund button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_additionaldet_refund($_REQUEST['ord_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_refund_do')
	{
		$order_id 	= $_REQUEST['checkbox'][0];
		$ref_amt	= sprintf('%.2f',trim($_REQUEST['txt_refundamt']));
		$ref_reason	= trim($_REQUEST['txt_refundreason']);
		if ($order_id)
		{
			// Get the details required from orders table
			$sql_ord = "SELECT order_status,order_deposit_amt,order_deposit_cleared,
								order_currency_symbol,order_currency_convertionrate,
								order_currency_numeric_code,order_currency_convertionrate,
								order_totalprice,order_refundamt,order_paystatus,
								order_paymenttype,order_paymentmethod,order_currency_code,order_totalauthorizeamt     
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{
				$row_ord = $db->fetch_array($ret_ord);
				
				if($row_ord['order_paystatus']=='REFUNDED')// Check whether the order is already refunded
				{
					$alert = 'Sorry!! order already refunded';
				}
				elseif($row_ord['order_paystatus']!='Paid')
				{
					$alert = 'Sorry!! payment not made for this order';
				}
				/*elseif ($row_ord['order_status']=='CANCELLED' and $row_ord['order_paymentmethod']!='PROTX') // Check whether the status of order is cancelled
				{
					$alert = 'Sorry!! this is a cancelled order. Refund not allowed';
				}
				*/
				else
				{
					// Check whether product deposit exists
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
					
					/*// Check whether sufficient amount is there to refund
					if($row_ord['order_deposit_amt']>0)
					{
						if($row_ord['order_deposit_cleared']==1)
							$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
						else
							$allowable_refund_amt = $row_ord['order_deposit_amt']-$row_ord['order_refundamt'];
					}
					else
					{
						$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
					}*/
					
					// Converting the refund amount to the required currency value
					$allowable_refund_amt = print_price_selected_currency($allowable_refund_amt,$row_ord['order_currency_convertionrate'],'',true);
					
					//$allowable_refund_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
					if ($ref_amt>$allowable_refund_amt)
					{
						$alert = 'Refund amount is greater than the actual amount to be refunded';
					}
					else
					{
						if($row_ord['order_totalauthorizeamt']>0)
						{
							if ($row_ord['order_totalauthorizeamt']==$row_ord['order_refundamt'])
							{
								$alert = 'Sorry!! order already refunded';
							}
						}	
						else 
						{
							if ($row_ord['order_totalprice']==$row_ord['order_refundamt'])
							{
								$alert = 'Sorry!! order already refunded';
							}
						}
					}
					
					// If reached here then it is legal to refund the order
					// Now check for the payment method and payment types used in order
					if ($row_ord['order_paymenttype']=='credit_card') // case if credit card in involved
					{
						if ($row_ord['order_paymentmethod']=='PROTX') // check whether the payment method used in protx
						{
							include 'console_refund.php';
						}
						else // gateway is not protx, so just change the status directly, no need to go to payment gateway
						{
							$baseStatus = "OK"; // forcing the refund to be successful
						}
					}
					else // case if credit card is not used directly in order. so just change the status directly, no need to go to payment gateway
					{
						$baseStatus = "OK"; // forcing the refund to be successful
					}
					// Check whether refund was successfull.
					if ($baseStatus == "OK")
					{
						// Converting the specified amount to default currency
						$ref_amt 		= print_price_default_currency($ref_amt,$row_ord['order_currency_convertionrate'],'',true);
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
						$insert_array['refund_by']			= $_SESSION['console_id'];
						$insert_array['refund_amt']			= $ref_amt;
						$insert_array['orders_order_id']	= $order_id;
						$db->insert_from_array($insert_array,'order_details_refunded');
						$refunded_id = $db->insert_id();
						
						// Making an entry for reason in order_notes table
						$insert_array								= array();
						$insert_array['orders_order_id']	= $order_id;
						$insert_array['note_add_date']	= 'now()';
						$insert_array['user_id']				= $_SESSION['console_id'];
						$insert_array['note_text']			= add_slash($ref_reason);
						$insert_array['note_type']			= 7;
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Additional note saved in notes section';
						$refunded_arr = array();
						// Check whether any of the products are selected
						for($i=0;$i<count($_REQUEST['checkboxprod']);$i++)
						{
							// Check whether this item is already refunded in order_details table
							$sql_check = "SELECT order_refunded 
											FROM 
												order_details 
											WHERE 
												orderdet_id = ".$_REQUEST['checkboxprod'][$i]." 
											LIMIT
											 1"; 
							$ret_check = $db->query($sql_check);
							if ($db->num_rows($ret_check))
							{
								$row_check = $db->fetch_array($ret_check);
							}
							if ($row_check['order_refunded']=='N') // case if the item is not refunded
							{
								$refunded_arr[] = $_REQUEST['checkboxprod'][$i];
								// Making entry to order_details_refunded_products table
								$insert_array					= array();
								$insert_array['refund_id']		= $refunded_id;
								$insert_array['orderdet_id']	= $_REQUEST['checkboxprod'][$i];
								$db->insert_from_array($insert_array,'order_details_refunded_products');
								
								// Update the refunded status for current product in order_details table
								$update_array						= array();
								$update_array['order_refunded']		= 'Y';
								$update_array['order_refundedon']	= 'now()';
								$update_array['order_refundedby']	= $_SESSION['console_id'];
								$db->update_from_array($update_array,'order_details',array('orderdet_id'=>$_REQUEST['checkboxprod'][$i]));
							}
						}
						if($row_ord['order_totalauthorizeamt']>0) // if authorize amount is >0
						{
							// Check whether full amount is refunded. If so change the payment status to REFUNDED and order status to not CANCELLED
							$sql_update = "UPDATE orders 
												SET 
													order_paystatus = 'REFUNDED',
													order_status = 'CANCELLED'  
												WHERE 
													order_id = $order_id 
													AND order_refundamt = order_totalauthorizeamt  
												LIMIT 
													1";
							$db->query($sql_update);		
						}
						else  // if authorize amount does not exists
						{
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
						}	
						// Saving and sending mail over here
						$ord_arr['order_id']						= $order_id;
						$ord_arr['refund_amt']						= $ref_amt; 
						$ord_arr['refund_note']						= $ref_reason; 
						$ord_arr['refunded_prods'] 					= $refunded_arr;
						$ord_arr['order_currency_convertionrate'] 	= $row_ord['order_currency_convertionrate'];
						$ord_arr['order_currency_symbol'] 			= $row_ord['order_currency_symbol'];
						save_and_send_OrderMail('REFUNDED',$ord_arr);
						$alert = 'Refund Successfull';	
					}
					else
						$alert = 'Refund not Successfull'.'<br><br>'.$ret_status;
				}
				
			}
		}	
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/ajax/order_ajax_functions.php');
		include ('includes/orders/order_details.php');	
	}
	elseif($_REQUEST['fpurpose']=='refund_details') //  clicked for refund details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_refund_details($_REQUEST['ord_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_refund_prods') //  clicked for products clubbed with refund
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_refund_product_details($_REQUEST['ref_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_release_sel') //  clicked the release button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_release_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_abort_sel') //  clicked the abort button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_abort_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_repeat_sel') //  clicked the repeat button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_repeat_note();
	}
	elseif($_REQUEST['fpurpose']=='operation_authorise_sel') //  clicked the repeat button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_authorise_details($_REQUEST['ord_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_paycapture_do') // section to handle the case of doing some operation related to payment captures
	{
		$order_id 	= $_REQUEST['checkbox'][0];
		if(trim($_REQUEST['txt_authamt'])!='')
		{
			$auth_amt	= sprintf('%.2f',trim($_REQUEST['txt_authamt']));
		}	
		$cur_note	= trim($_REQUEST['txt_additionalnote']);
		if ($order_id)
		{
			// Get the details required from orders table
			$sql_ord = "SELECT order_id,order_status,order_paystatus,order_totalprice,order_totalauthorizeamt,
						order_currency_convertionrate,order_currency_code 
						order_deposit_amt,order_deposit_cleared,order_refundamt,
						order_custfname,order_custmname,order_custsurname 
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{
				$row_ord = $db->fetch_array($ret_ord);
				if($_REQUEST['paycapture_type']=='RELEASE') // case of coming to release 
				{
					if ($row_ord['order_paystatus']=='DEFERRED')// check whether the order is still in Deferred pay status
					{
						$curmod 	= 'RELEASE';
						include 'console_manage_paymentcapture.php'; //Page which goes to Protx for Releasing the Transaction
						if($baseStatus=="OK")
						{
							//Change the payment status to Paid for current order
							$update_sql = "UPDATE orders 
											SET 
												order_paystatus = 'Paid',
												order_paystatus_changed_manually = 1,
												order_paystatus_changed_manually_by=".$_SESSION['console_id'].",
												order_paystatus_changed_manually_on=now() 
											WHERE 
												order_id = $order_id 
											LIMIT 
												1";
							$db->query($update_sql);										
							$alert 		= 'Released Successfully'; 
							
							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array								= array();
								$insert_array['orders_order_id']	= $order_id;
								$insert_array['note_add_date']	= 'now()';
								$insert_array['user_id']				= $_SESSION['console_id'];
								$insert_array['note_text']			= add_slash($cur_note);
								$insert_array['note_type']			= 8;
								$db->insert_from_array($insert_array,'order_notes');
								$alert .= '. Additional note saved in notes section';
							}	
							// Saving and sending mail to customer;
							$ord_arr['order_id'] 	= $order_id;
							$ord_arr['note']		= $cur_note;
							send_RequiredOrderMails($order_id);
							save_and_send_OrderMail('DEFERRED_RELEASE',$ord_arr);

						}
						else
							$alert		= 'Release Not Successfull';
					}
					else
					{
						$alert = 'Sorry!! Release not successfull. Payment status is not Deferred.';
					}
				}	
				elseif($_REQUEST['paycapture_type']=='ABORT') // case of coming to abort Deferred transaction 
				{
					if ($row_ord['order_paystatus']=='DEFERRED')// check whether the order is still in Deferred pay status
					{
						$curmod 		= 'ABORT';
						include 'console_manage_paymentcapture.php'; // case of aborting 
						if($baseStatus=="OK")
						{
							//Change the payment status to Paid for current order
							$update_sql = "UPDATE orders 
											SET 
												order_paystatus = 'ABORTED',
												order_paystatus_changed_manually = 1,
												order_paystatus_changed_manually_by=".$_SESSION['console_id'].",
												order_paystatus_changed_manually_on=now() 
											WHERE 
												order_id = $order_id 
											LIMIT 
												1";
							$db->query($update_sql);										
							$alert 		= 'Aborted Successfully'; 
							
							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array						= array();
								$insert_array['orders_order_id']	= $order_id;
								$insert_array['note_add_date']		= 'now()';
								$insert_array['user_id']			= $_SESSION['console_id'];
								$insert_array['note_text']			= add_slash($cur_note);
								$insert_array['note_type']			= 9;
								$db->insert_from_array($insert_array,'order_notes');
								$alert .= '. Additional note saved in notes section';
							}
							// Saving and sending mail to customer;
							$ord_arr['order_id'] 	= $order_id;
							$ord_arr['note']		= $cur_note;
							save_and_send_OrderMail('DEFERRED_ABORT',$ord_arr);	
						}
						else
							$alert		= 'Abort Not Successfull';
					}
					else
					{
						$alert = 'Sorry!! About not successfull. Payment status is not Deferred.';
					}
				
				}
				elseif($_REQUEST['paycapture_type']=='REPEAT') // case of coming to repeating Preauth transaction 
				{
					if ($row_ord['order_paystatus']=='PREAUTH')// check whether the order is still in Preauth pay status
					{
						$curmod 		= 'REPEAT';
						include 'console_manage_paymentcapture.php'; // case of repeating 
						if($baseStatus=="OK")
						{
							//Change the payment status to Paid for current order
							$update_sql = "UPDATE orders 
											SET 
												order_paystatus = 'Paid',
												order_paystatus_changed_manually = 1,
												order_paystatus_changed_manually_by=".$_SESSION['console_id'].",
												order_paystatus_changed_manually_on=now() 
											WHERE 
												order_id = $order_id 
											LIMIT 
												1";
							$db->query($update_sql);										
							$alert 		= 'Repeated Successfully'; 
							if ($cur_note!='')
							{
								// Making insertion to notes table if note added
								$insert_array						= array();
								$insert_array['orders_order_id']	= $order_id;
								$insert_array['note_add_date']		= 'now()';
								$insert_array['user_id']			= $_SESSION['console_id'];
								$insert_array['note_text']			= add_slash($cur_note);
								$insert_array['note_type']			= 10;
								$db->insert_from_array($insert_array,'order_notes');
								$alert .= '. Additional note saved in notes section';
							}	
							// Saving and sending mail to customer;
							$ord_arr['order_id'] 	= $order_id;
							$ord_arr['note']		= $cur_note;
							send_RequiredOrderMails($order_id);		
							save_and_send_OrderMail('PREAUTH_REPEAT',$ord_arr);
						}
						else
							$alert		= 'Repeat Not Successfull';
					
					}
					else
					{
						$alert = 'Sorry!! Repeat not successfull. Payment status is not Preauth.';
					}
				}
				elseif($_REQUEST['paycapture_type']=='AUTHORISE') // case of coming to authorise Authenticate transaction 
				{
					if ($row_ord['order_paystatus']=='AUTHENTICATE')// check whether the order is still in Preauth pay status
					{
						$curmod 		= 'AUTHORISE';
						include 'console_manage_paymentcapture.php'; // case of repeating 
						if($baseStatus == "OK")
						{
							$cur_authamt 		= trim($auth_amt);
							$total_orderauth	= $row_ord['order_totalauthorizeamt'];
							
							
							/*// converting the current auth amount to default currency
							$cur_authamt		= print_price_default_currency($cur_authamt,$row_ord['order_currency_convertionrate'],'',true);
							// find the new total of auth amount by adding it to te auth total in orders table
							$tot_authdef		= ($total_orderauth + $cur_authamt);
							*/
							// Finding the max amount that can be authorised
							//check whether partial payment is made (product deposit case)
							if ($row_ord['order_deposit_amt']>0)
							{
								if($row_ord['order_deposit_cleared']==0)// case if product deposit is not cleared. so the total amount that can be authorized is the product deposit amount itself
									$rem_amt	= $row_ord['order_deposit_amt'];
								else // if product deposit is cleared
									$rem_amt	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
							}
							else
							{
								$rem_amt 	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
							}
							
							//$rem_amt 	= ($row_ord['order_totalprice'] - $row_ord['order_refundamt']);
							// taking 115% of rem_amt
							$rem_amt_per			= $rem_amt * 115/100;
							// Less the amount already authorized
							$rem_amt_per			= $rem_amt_per - $row_ord['order_totalauthorizeamt'];
							$max_authn_allowed		= $rem_amt_per; // only the paid amount is set to variable


							// converting the current auth amount to default currency
							$cur_authamt		= print_price_default_currency($cur_authamt,$row_ord['order_currency_convertionrate'],'',true);
							// find the new total of auth amount by adding it to te auth total in orders table
							$tot_authdef		= ($total_orderauth + $cur_authamt);
							
							
							
							
							
							// Check whether the amount being authorized is valid to authorize
							/*if ($_REQUEST['max_auth_allowed_def']>=$cur_authamt)*/
							// Check whether the amount being authorized is valid to authorize
							if ($max_authn_allowed>=$cur_authamt)
							{
								//if ($tot_authdef >= $pass_tot) // case if total of auth amount (including current amt) is >= remaining amount in order total 
								if (($max_authn_allowed==$cur_authamt) or ($tot_authdef>=$rem_amt)) // if the maximum auth allowed amount == current authorising amount or total auth amount is > remaining amount to be authorized the make the status to Paid
								{
									// Change the payment status of order to Paid				
									$update_sql = "UPDATE orders 
													SET 
														order_paystatus = 'Paid',
														order_totalauthorizeamt = $tot_authdef,
														order_paystatus_changed_manually = 1,
														order_paystatus_changed_manually_by=".$_SESSION['console_id'].",
														order_paystatus_changed_manually_on=now() 
													WHERE 
														order_id = $order_id 
													LIMIT 
														1";
									$db->query($update_sql);
									// Sending remaining mails 
									send_RequiredOrderMails($order_id);	
									// Inserting an entry to the order_details_authorized_amount table
									$insert_array						= array();
									$insert_array['auth_on']			= 'now()';
									$insert_array['auth_by']			= $_SESSION['console_id'];
									$insert_array['auth_amt']			= $cur_authamt;
									$insert_array['orders_order_id']	= $order_id;
									$db->insert_from_array($insert_array,'order_details_authorized_amount');
									$alert 		= 'Amount authorised Successfully and order status changed'; 		
								}
								else // case if auth amt is < pass_tot
								{
									// updating the total authamount in orders table
									$update_sql = "UPDATE orders 
													SET 
														order_totalauthorizeamt = $tot_authdef 
													WHERE 
														order_id = $order_id 
													LIMIT 
														1";
									$db->query($update_sql);
									// Inserting an entry to the order_details_authorized_amount table
									$insert_array						= array();
									$insert_array['auth_on']			= 'now()';
									$insert_array['auth_by']			= $_SESSION['console_id'];
									$insert_array['auth_amt']			= $cur_authamt;
									$insert_array['orders_order_id']	= $order_id;
									$db->insert_from_array($insert_array,'order_details_authorized_amount');
									$alert 		= 'Amount authorised Successfully'; 		
								}
								
								// Saving and sending mail to customer;
								$ord_arr['order_id'] 	= $order_id;
								$ord_arr['note']		= $cur_note;
								$ord_arr['amt']			= $cur_authamt;
								save_and_send_OrderMail('AUTHENTICATE_AUTHORISE',$ord_arr);
								if ($cur_note!='')
								{
									// Making insertion to notes table if not added
									$insert_array								= array();
									$insert_array['orders_order_id']	= $order_id;
									$insert_array['note_add_date']	= 'now()';
									$insert_array['user_id']				= $_SESSION['console_id'];
									$insert_array['note_text']			= add_slash($cur_note);
									$insert_array['note_type']			= 11;
									$db->insert_from_array($insert_array,'order_notes');
									$alert .= '. Additional note saved in notes section';
								}
							}
							else 
							{
								$alert = 'Sorry!! Maximum amount that can be authorized is '.print_price_selected_currency($max_authn_allowed,$row_ord['order_currency_convertionrate'],$_REQUEST['order_currency_symbol'],true);		
							}	
						}
						else
							$alert		= 'Authorise Not Successfull';
					
					}
					else
					{
						$alert = 'Sorry!! Authorise not successfull. Payment status is not Authenticate.';
					}
				}
				elseif($_REQUEST['paycapture_type']=='CANCEL') // case of coming to cancel authorise Authenticate transaction 
				{
					if ($row_ord['order_paystatus']=='AUTHENTICATE')// check whether the order is still in Preauth pay status
					{
						$curmod 		= 'CANCEL';
						include 'console_manage_paymentcapture.php'; // case of repeating 
						if($baseStatus == "OK")
						{
							// Change the payment status of order to Paid				
							$update_sql = "UPDATE orders 
											SET 
												order_paystatus = 'CANCELLED',
												order_paystatus_changed_manually = 1,
												order_paystatus_changed_manually_by=".$_SESSION['console_id'].",
												order_paystatus_changed_manually_on=now() 
											WHERE 
												order_id = $order_id 
											LIMIT 
												1";
							$db->query($update_sql);
							$alert 		= 'Cancelled Successfully'; 
							if ($cur_note!='')
							{
								// Making insertion to notes table if not added
								$insert_array						= array();
								$insert_array['orders_order_id']	= $order_id;
								$insert_array['note_add_date']		= 'now()';
								$insert_array['user_id']			= $_SESSION['console_id'];
								$insert_array['note_text']			= add_slash($cur_note);
								$insert_array['note_type']			= 12;
								$db->insert_from_array($insert_array,'order_notes');
								$alert .= '. Additional note saved in notes section';
							}	
							// Saving and sending mail to customer;
							$ord_arr['order_id'] 	= $order_id;
							$ord_arr['note']		= $cur_note;
							save_and_send_OrderMail('AUTHENTICATE_CANCEL',$ord_arr);
						}
						else
							$alert		= 'Cancel Not Successfull';
					
					}
					else
					{
						$alert = 'Sorry!! Cancel not successfull. Payment status is not Authenticate.';
					}
				}
			}
		}	
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/order_details.php');			
	}
	elseif ($_REQUEST['fpurpose'] == 'authorise_amount_details')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_authorise_amount_details($_REQUEST['ord_id']);
	}
	elseif($_REQUEST['fpurpose']=='operation_cancel_sel') //  clicked the cancel button
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_cancel_note();
	}
	elseif($_REQUEST['fpurpose']=='order_queries') //  List Order queries
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_order_queries($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='order_download') //  List Order downloadables
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_order_download($_REQUEST['ord_id']);	
	}
	elseif($_REQUEST['fpurpose']=='order_download_change_status') // Change status of downloadable items
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$chstat 	= $_REQUEST['ch_status'];
		$ordid	= $_REQUEST['ord_id'];
		$download_arr = array();
		if($_REQUEST['downid'])
		{
			$download_arr = explode('~',$_REQUEST['downid']);
		}
		if (count($download_arr))
		{
			for($i=0;$i<count($download_arr);$i++)
			{
				$sql_update = "UPDATE order_product_downloadable_products 
										SET 
											proddown_disabled =".$chstat." 
										WHERE 
											ord_down_id = ".$download_arr[$i]." 
											AND orders_order_id = $ordid 
										LIMIT 
											1";
				$db->query($sql_update);
			}
			$alert = 'Status changed successfully';
		}
		else
		{
			$alert ='Sorry!! status not changed.. Please select the downloadable items';
		}	
		show_order_download($_REQUEST['ord_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose']=='order_download_save_details') // Save details of downloadable items
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$chstat 	= $_REQUEST['ch_status'];
		$ordid	= $_REQUEST['ord_id'];
		$download_arr = array();
		if($_REQUEST['downid'])
		{
			$download_arr 	= explode('~',$_REQUEST['downid']);
			$varlimit_arr		= explode('~',$_REQUEST['varlimit']);
			$startdate_arr		= explode('~',$_REQUEST['startdate']);
			$enddate_arr		= explode('~',$_REQUEST['enddate']);
		}
		if (count($download_arr))
		{
			$succ_cnt = $fail_cnt = 0;
			for($i=0;$i<count($download_arr);$i++)
			{
				$error					= 0;
				$cur_limit 				= $varlimit_arr[$i];
				// Generating the start date and time
				$cur_startdate_arr	= explode(" ",$startdate_arr[$i]);
				$cur_starttime_arr	= explode(":",$cur_startdate_arr[1]);
				$cur_start_arr			= explode('-',$cur_startdate_arr[0]);
				$cur_start_date		= $cur_start_arr[2].'-'.$cur_start_arr[1].'-'.$cur_start_arr[0].' '.$cur_startdate_arr[1];
				
				// Generating the end date and time
				$cur_enddate_arr	= explode(" ",$enddate_arr[$i]);
				$cur_endtime_arr	= explode(":",$cur_enddate_arr[1]);
				$cur_end_arr			= explode('-',$cur_enddate_arr[0]);
				$cur_end_date		= $cur_end_arr[2].'-'.$cur_end_arr[1].'-'.$cur_end_arr[0].' '.$cur_enddate_arr[1];
				
				
				// Building mktime to compare dates
				if ($cur_startdate_arr!='0000-00-00 00:00:00' and $cur_enddate_arr!='0000-00-00 00:00:00')
				{
					$mk_start				= mktime($cur_starttime_arr[0],$cur_starttime_arr[1],$cur_starttime_arr[2],$cur_start_arr[1],$cur_start_arr[0],$cur_start_arr[2]);
					$mk_end				= mktime($cur_endtime_arr[0],$cur_endtime_arr[1],$cur_endtime_arr[2],$cur_end_arr[1],$cur_end_arr[0],$cur_end_arr[2]);
				}				
				if (!is_numeric($cur_limit))
				{
					$error = 1;
				}
				if ($cur_start_date!='0000-00-00 00:00:00')
				{
					if($mk_end<=$mk_start)
					{
						$error = 2; 
					}	
				}	
				if($error==0)
				{
				
					if($cur_start_date!='0000-00-00 00:00:00')
					{
						$diff_days = date('j',($mk_end-$mk_start));
						if($diff_days<1)
							$diff_days = 1;
					}
					else
						$diff_days = 0;
					$sql_update = "UPDATE order_product_downloadable_products 
											SET 
												proddown_limit =".$cur_limit." ,
												proddown_days_active_start ='".$cur_start_date."',
												proddown_days_active_end='".$cur_end_date."' ,
												proddown_days = $diff_days 
											WHERE 
												ord_down_id = ".$download_arr[$i]." 
												AND orders_order_id = $ordid 
											LIMIT 
												1";
					$db->query($sql_update);
					$succ_cnt++;
				}	
				else
					$fail_cnt++;
			}
			if ($fail_cnt==0)
				$alert = 'Details saved successfully';
			elseif($fail_cnt>0 and $succ_cnt>0)
				$alert = 'Details saved .. but errors occured for some downloadables. Downloadable with errors not saved';
			elseif($succ_cnt==0)
				$alert = 'Sorry!! downloadable details not saved... error occured';	
		}
		else
		{
			$alert ='Sorry!! Details not saved.. Please select the downloadable items';
		}	
		show_order_download($_REQUEST['ord_id'],$alert);	
	}
	
	
	function decide_show_tab($cur_tab,$order_id,$alert='')
	{
		switch($cut_tab)
		{
			case 'summary_tab_td':
				show_Order_Summary($order_id,$alert);
			break;
			case 'payment_tab_td':
				show_Order_Payments($order_id,$alert);
			break;
			case 'despatch_tab_td':
				show_Order_Despatch($order_id,$alert);
			break;
			case 'refund_tab_td':
				show_Order_Refunds($order_id,$alert);
			break;
			case 'returns_tab_td':
				show_Order_Returns($order_id,$alert);
			break;
			case 'notes_tab_td':
				show_Order_NotesandEmails($order_id,$alert);
			break;
			case 'query_tab_td':
				show_Order_CustomerQueries($order_id,$alert);
			break;
			case 'other_tab_td':
				show_Order_Others($order_id,$alert);
			break;
		}
		echo 'curtab'.$cur_tab;
	}
?>