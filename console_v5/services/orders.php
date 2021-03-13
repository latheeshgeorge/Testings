<?php
/* Function to decide which tab funciton is to be displayed */
	function decide_show_tab($cur_tab,$order_id,$alert='')
	{
		global $db,$ecom_siteid;
		switch($cur_tab)
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
		};
	}
	function get_updatable_order_status($order_id,$typ='normal')
	{ 
		global $db,$ecom_siteid;
		$prod_remains = $change_true = false;
		if ($typ=='normal')
		{
			$fields = " order_refundamt amt1,order_totalprice amt2 ";
		}
		elseif($typ =='auth')
		{
			$fields = " order_refundamt ant1,order_totalauthorizeamt amt2";
		}
		$sql_sel = "SELECT $fields 
						FROM 
							orders 
						WHERE 
							order_id = $order_id 
						LIMIT 
							1";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			$row_sel = $db->fetch_array($ret_sel);
			// Check whether there exists any items in back order for current order
			$sql_back = "SELECT order_backorder_id 
							FROM 
									order_details a,order_details_backorder b 
							WHERE 
									a.orders_order_id = $order_id 
									AND a.orderdet_id = b.orderdet_id 
							LIMIT 
									1";
			$ret_back = $db->query($sql_back);
			if($db->num_rows($ret_back))
			{
					$prod_remains = true;
			}
			if($prod_remains==false)
			{
				// Check whether there remain any items in order_details with order_qty >0
				$sql_check = "SELECT orderdet_id 
								FROM 
										order_details 
								WHERE 
										orders_order_id = $order_id 
										AND order_qty>0 
								LIMIT 
										1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)>0)
				{                       
					$prod_remains = true;                           
				}
			}
			//if($row_sel['amt1']==$row_sel['amt2']) 
			{
				$det_arr = array();
				// check whether there exists atleast one valid despatched items
				$sql_desp = "SELECT orderdet_id 
								FROM 
									order_details 
								WHERE 
									orders_order_id = $order_id";
				$ret_desp = $db->query($sql_desp);
				if($db->num_rows($ret_desp))
				{
					while ($row_desp = $db->fetch_array($ret_desp))
					{
						$det_arr[] = $row_desp['orderdet_id'];
					}
				}
				if(count($det_arr))
				{
					$sql_check = "SELECT despatched_id 
									FROM 
										order_details_despatched 
									WHERE 
										orderdet_id IN (".implode(',',$det_arr).")  
										AND despatched_qty >despatched_returned_qty";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))
					{
						if ($prod_remains==false)
							return true;
						else
							return false;
					}
				}	
			}
			if($prod_remains==true)
				return false;
		}
		return false;	
	}
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/list_orders.php');
	}
	elseif($_REQUEST['fpurpose']=='salesrepshow') //  Show page to select the date range to generate sales report without tax
	{
		include ('includes/orders/generate_sales_report.php');
	}
	elseif($_REQUEST['fpurpose']=='ord_delete') //  Delete Selected orders
	{
		$alert = "";
		if(count($_REQUEST['checkbox'])==0)
		{
			$alert = 'Please select the order(s) to be deleted';
		}
		else
		{
			for($i=0;$i<count($_REQUEST['checkbox']);$i++)
			{
				// Check whether order exists in orders table
				$sql_ord_get = "SELECT * FROM orders WHERE order_id=".$_REQUEST['checkbox'][$i]." AND sites_site_id = $ecom_siteid LIMIT 1";
				$ret_ord_get = $db->query($sql_ord_get);
				if($db->num_rows($ret_ord_get))
				{
					$row_ord_get = $db->fetch_assoc($ret_ord_get);
					// Check whether there exists an entry in the orders_delete table with the same order id
					$sql_check = "SELECT order_id FROM orders_delete WHERE order_id = ".$_REQUEST['checkbox'][$i]." AND sites_site_id = $ecom_siteid LIMIT 1";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check)==0) // case if order does not exists in order_delete table
					{
						$sql_build = 'INSERT INTO orders_delete SET ';
						$condition = '';
						foreach ($row_ord_get as $k=>$v)
						{
							if($condition != '')
								$condition .= ',';
							$condition .= "$k = '".addslashes(stripslashes($v))."'";
						}
						$sql_build .= $condition;
						$db->query($sql_build);
						$sql_delete = "DELETE FROM orders WHERE order_id = ".$_REQUEST['checkbox'][$i]." AND sites_site_id = $ecom_siteid LIMIT 1";
						$db->query($sql_delete);
						// GEt the order details 
						$sql_ordet_get = "SELECT * FROM order_details WHERE orders_order_id = ".$_REQUEST['checkbox'][$i];
						$ret_ordet_get = $db->query($sql_ordet_get);
						if($db->num_rows($ret_ordet_get))
						{
								while ($row_ordet_get = $db->fetch_assoc($ret_ordet_get))
								{
									// Check whether there already exists an entry with the current order details id in order_details_delete table
									$sql_check_det = "SELECT orderdet_id FROM order_details_delete WHERE orderdet_id =".$row_ordet_get['orderdet_id']." AND orders_order_id = ".$_REQUEST['checkbox'][$i]." LIMIT 1";
									$ret_check_det = $db->query($sql_check_det);
									if($db->num_rows($ret_check_det)==0)
									{
										$sql_build = 'INSERT INTO order_details_delete SET ';
										$condition = '';
										foreach ($row_ordet_get as $k=>$v)
										{
											if($condition != '')
												$condition .= ',';
											$condition .= "$k = '".addslashes(stripslashes($v))."'";
										}
										$sql_build .= $condition;
										$db->query($sql_build);
										$sql_delete = "DELETE FROM order_details WHERE orderdet_id = ".$row_ordet_get['orderdet_id']." AND orders_order_id = ".$_REQUEST['checkbox'][$i]." LIMIT 1";
										$db->query($sql_delete);
									}	
								}	
							
						}
					}
					$alert = "Order(s) Deleted Successfully";
				}
				else
				{
					$alert .= "<br> Order Id : ".$_REQUEST['checkbox'][$i]." Does Not Found"; 
				}
				
			}
		}
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/list_orders.php');
	}
	elseif($_REQUEST['fpurpose']=='ord_details') //  View details page
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$_REQUEST['checkbox'][0] = $_REQUEST['edit_id'];
		include ('includes/orders/ajax/order_ajax_functions.php');
		include ('includes/orders/order_details.php');
	}
	
		
	elseif($_REQUEST['fpurpose']=='archive') //  archive 
	{
			
		
		/*$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";*/
		$archive_arr = explode("~",$_REQUEST['archive_order_ids']);
		//$edit_id = $_REQUEST['checkbox'][0];

		if($_REQUEST['archive_order_ids'] == '')
		{
			$alert = 'Sorry Order(s) not selected';
		}
		else
		{
			$count = 0;
			$mov = 'original_archive';
			for($i=0;$i<count($archive_arr);$i++)
			{
				if(trim($archive_arr[$i]))
				{
					move_archive($archive_arr[$i],$ecom_siteid,$mov);
					$count ++;
				}
			}
			if($count!=0)
			{
				if($alert!='')
					$alert .= "<br>";
				$alert .= "<span><b>$count Order(s) Archived Successfully</b></span>";
			}
	
		}
     	include_once ('includes/orders/list_orders.php');
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
		include_once('../classes/mime.php');
		if($_REQUEST['emailid'])
		{
			$sql = "SELECT email_id,email_to,email_subject,email_messagepath,email_headers,
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
				
				$alert = 'Mail Sent Successfully';
			}	 
			else
				$alert ='Sorry!! Email not found';
		}
		else
			$alert = 'Select the order mail to be send';
		show_order_emails($_REQUEST['ord_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose']=='resend_OrderEmail_cust') //  Resend selected order email
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		include_once('../classes/mime.php');
		if($_REQUEST['emailid'])
		{
			$sql = "SELECT order_email_cust_id 
						FROM 
							order_emails_tocust 
						WHERE 
							order_email_cust_id = ".$_REQUEST['emailid']." 
						LIMIT 
							1";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				$row = $db->fetch_array($ret);
				// Call function to send the selected mail again
				resend_orderEmail_cust($_REQUEST['emailid'],$_REQUEST['ord_id']);
				
				// Calling the function to save the email history
				save_EmailHistory_cust($_REQUEST['emailid'],$_REQUEST['ord_id']);
				
				$alert = 'Mail Sent Successfully';
			}	 
			else
				$alert ='Sorry!! Email not found';
		}
		else
			$alert = 'Select the order mail to be send';
		show_order_emails_custom_cust($_REQUEST['ord_id'],$alert);	
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
		// Check whether coming from order listing page or from order details page
		if($_REQUEST['action_source']=='order_listing') // case of coming from order listing page
		{
			$ch_stat			= $_REQUEST['ch_ord_stat'];
			if(count($_REQUEST['checkbox']))
			{
				$change_cnt = 0;
				foreach ($_REQUEST['checkbox'] as $k=>$v)
				{
					$note = "Order status changed to '".getorderstatus_Name($ch_stat)."' from order listing page.";
					$cnt = do_changeorderstatus_operation($v,$ch_stat,'',array(),$note,'order_listing');
					if($cnt)
						$change_cnt += $cnt;
				}
				if ($change_cnt>0)
				{
					if($change_cnt ==1)
						$cap = 'Order';
					else
						$cap = 'Orders';
					$alert = 'Order status changed for '.$change_cnt.' '.$cap;	
				}
				else
					$alert = 'Order status not changed for any of the selected orders';
			}
			else
				$alert = 'Please select orders to change the status';
			include_once "includes/orders/list_orders.php";	
		}
		else // case of coming from order details page
		{
			include_once("../functions/functions.php");
			include_once('../session.php');
			include_once("../config.php");
			include ('../includes/orders/ajax/order_ajax_functions.php');
			
			$order_id 				= $_REQUEST['ord_id'];
			$ch_stat				= $_REQUEST['sel_stat'];
			$alt_prods				= $_REQUEST['p_ids'];
			$stat_arr['stock_return'] 				= $_REQUEST['stock_return'];
			$stat_arr['bonusused_return'] 			= $_REQUEST['bonusused_return'];	
			$stat_arr['bonusearned_return'] 		= $_REQUEST['bonusearned_return'];	
			$stat_arr['maxvoucher_return'] 			= $_REQUEST['maxvoucher_return'];
			$stat_arr['force_cancel'] 				= $_REQUEST['force_cancel'];
			/* Donate bonus Start */
			$stat_arr['bonusdonated_return'] 		= $_REQUEST['bonusdonated_return'];
			/* Donate bonus End */
			$alert = do_changeorderstatus_operation($order_id,$ch_stat,$alt_prods,$stat_arr,$_REQUEST['note'],'det');
			// Calling the function to decide upon which tab details to be displayed
			decide_show_tab($_REQUEST['curtab'],$order_id,$alert);
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
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_PayHoldsel') //  Selected order payment status from drop down for changing the payment status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_PayHold_TakeDetails();
	}
	
	elseif($_REQUEST['fpurpose']=='operation_changeorderpaystatus_do') //  Changing the order payment status
	{
		// Check whether coming from order listing page or from order details page
		if($_REQUEST['action_source']=='order_listing') // case of coming from order listing page
		{
			$pay_cnt = 0;
			$ch_stat = $_REQUEST['cbo_paymentstatus'];
			if(count($_REQUEST['checkbox']))
			{
				foreach ($_REQUEST['checkbox'] as $k=>$v)
				{
					$note = "Payment status changed to '".getpaymentstatus_Name($ch_stat)."' from order listing page.";
					$cnt = do_changeorderpaystatus_operation($v,$ch_stat,'',$note,'order_listing');
					if ($cnt)
						$pay_cnt += $cnt;
				}
				if($pay_cnt>0)
				{
					if ($pay_cnt==1)
						$pay_ord = 'Order';
					else
						$pay_ord = 'Orders';
					$alert	= 'Payment status changed for '.$pay_cnt.' '.$pay_ord;
				}
				else
					$alert = 'Payment status not changed for any of the orders';
			}
			else
				$alert = 'Please select the orders to change the payment status';
			include_once 'includes/orders/list_orders.php';	
		}
		else
		{
			include_once("../functions/functions.php");
			include_once('../session.php');
			include_once("../config.php");
			include ('../includes/orders/ajax/order_ajax_functions.php');
			include_once('../classes/mime.php');
			$order_id 		= $_REQUEST['ord_id'];
			$ch_stat		= $_REQUEST['sel_stat'];
			$sel_pay_method	= $_REQUEST['cbo_paymethod'];
			$alert = do_changeorderpaystatus_operation($order_id,$ch_stat,$sel_pay_method,$note,'det');
			// Calling the function to show the operation section in order details page
			decide_show_tab($_REQUEST['curtab'],$order_id,$alert);
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
			// Calling the function to show the payment section in order details page
			decide_show_tab($_REQUEST['curtab'],$_REQUEST['ord_id'],$alert);
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
	elseif($_REQUEST['fpurpose']=='operation_despatched_email_preview_do') //  came by submitting the form in case of items despatched but email preview is required.
	{
		$despatchhold_det_arr 		= $_REQUEST['holded_id_str'];
		$order_id					= $_REQUEST['checkbox'][0];
		$despatchhold_note			= $_REQUEST['holded_despatch_note'];
		$despatchhold_id			= $_REQUEST['holded_despatch_id'];
		$despatch_exp_delivery_date	= $_REQUEST['holded_exp_delivery_date_str'];
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/ajax/order_ajax_functions.php');
		include ('includes/orders/order_details.php');
	}
	elseif($_REQUEST['fpurpose']=='operation_despatched_email_preview_do_cancel') //  came by submitting the form in case of items despatched but email preview is required.
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";		
		include ('includes/orders/ajax/order_ajax_functions.php');
		//show_Order_Despatch($order_id,$alert);
		$alert = 'Despatch Cancelled';
		$_REQUEST['curtab'] ='despatch_tab_td';
		include ('includes/orders/order_details.php');
	}	
	elseif($_REQUEST['fpurpose']=='operation_despatched_do') //  came by submitting the form in case of items despatched.
	{ 
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$det_arr 				= explode("~",$_REQUEST['id_str']);
		$order_id				= $_REQUEST['ord_id'];
		$prod_remains			= false;
		$despatch_note			= $_REQUEST['note'];
		$despatch_id			= $_REQUEST['refno'];
		$exp_delivery_date		= $_REQUEST['exp_del_date'];
		$completly_despatched 	= false;
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
						// Inserting a record to the order details details table to track the despatches
						$atleast_one 								= true;
						$insert_array								= array();
						$insert_array['orderdet_id']			= $det_arr[$i];
						$insert_array['despatched_qty']		= $row_orderdet['order_qty'];
						$insert_array['despatched_on']		= 'now()';
						$insert_array['despatched_by']		= $_SESSION['console_id'];
						if ($despatch_id!='')
							$insert_array['despatched_reference']	= $despatch_id;
						if ($exp_delivery_date!='')
						{
							$exp_date_arr = explode('-',$exp_delivery_date);
							$exp_delivery_date_str = $exp_date_arr[2].'-'.$exp_date_arr[1].'-'.$exp_date_arr[0];
							$insert_array['despatched_expected_delivery_date']	= $exp_delivery_date_str;
						}	
							
						$db->insert_from_array($insert_array,'order_details_despatched');	
						$cur_dep_id = $db->insert_id();
						// decrementing the quantity in order_details table
						$sql_update = "UPDATE order_details 
													SET 
														order_qty = 0  
													WHERE 
														orderdet_id =".$det_arr[$i]." 
													LIMIT 
														1";
						$db->query($sql_update);
						
						$despatchid_arr[] 					= $det_arr[$i];
						$despatchqty_arr[$det_arr[$i]] = $row_orderdet['order_qty'];
						
						// Check whether any qty exists for current product in back order for current order
						$sql_back = "SELECT order_backorder_id 
												FROM 
													order_details_backorder 
												WHERE 
													orderdet_id = ".$det_arr[$i]." 
												LIMIT 
													1";
						$ret_back = $db->query($sql_back);
						if ($db->num_rows($ret_back)==0)
						{
							// Update the despatched status of current product to Y in order details table
							$sql_update = "UPDATE order_details 
												SET 
													order_dispatched = 'Y' 
												WHERE 
													orderdet_id=".$det_arr[$i]."  
													AND order_qty=0 
												LIMIT 
													1";
								$db->query($sql_update);
						}	
				}
			}						
		}
		
		// Check whether the order status is to be changed to despatched
			// Check whether there exists any items in back order for current order
			$sql_back = "SELECT order_backorder_id 
									FROM 
										order_details a,order_details_backorder b 
									WHERE 
										a.orders_order_id = $order_id 
										AND a.orderdet_id = b.orderdet_id 
									LIMIT 
										1";
			$ret_back = $db->query($sql_back);
			if($db->num_rows($ret_back))
			{
				$prod_remains = true;
			}
			if($prod_remains==false)
			{
				// Check whether there remain any items in order_details with order_qty >0
				$sql_check = "SELECT orderdet_id 
										FROM 
											order_details 
										WHERE 
											orders_order_id = $order_id 
											AND order_qty>0 
										LIMIT 
											1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{			
						$sql_update = "UPDATE 
													orders
												SET 
													order_status = 'DESPATCHED' , 
													order_despatched_completly_on = now()  
												WHERE 
													order_id = $order_id 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
												1";
						$db->query($sql_update);		
						$completly_despatched = true;				
				}
			}
			$alert = 'Items Despatched Successfully';
			// Making entries to notes section in case if any additional note is specified
			if($despatch_note!='')
			{
				$insert_array								= array();
				$insert_array['orders_order_id']		= $order_id;
				$insert_array['note_add_date']		= 'now()';
				$insert_array['user_id']					= $_SESSION['console_id'];
				$insert_array['note_text']				= add_slash($despatch_note);
				$insert_array['note_type']			= 6;
				$insert_array['note_related_id']		= $cur_dep_id;
				$db->insert_from_array($insert_array,'order_notes');
				$alert .= '. Additional note saved in notes section';
			}
				// Saving and sending mail over here
				$ord_arr['order_id']						= $order_id;
				$ord_arr['despatch_id']				= $despatch_id; 
				$ord_arr['despatch_note']			= $despatch_note; 
				$ord_arr['despatched_prods'] 		= $despatchid_arr;
				$ord_arr['despatched_qtys'] 		= $despatchqty_arr;
				$ord_arr['completly_despatched']	= $completly_despatched;
				$ord_arr['despatched_delivery_date']= $exp_delivery_date;
				save_and_send_OrderMail('DESPATCHED',$ord_arr);
				// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
				handle_recalculate_specialtax_calculation($order_id);
				show_Order_Despatch($order_id,$alert);
		
	/*	if (count($_REQUEST['checkboxprod']))
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
			$alert = 'Please select the products to be despatched';*/
		//$ajax_return_function = 'ajax_return_contents';
		//include "ajax/ajax.php";
		//include ('includes/orders/order_details.php');	
	}
	elseif($_REQUEST['fpurpose']=='operation_despatched_email_preview_do_done') //  came by submitting the form in case of items despatched email preview page.
	{
		$det_arr 					= explode("~",$_REQUEST['holded_id_str']);
		$order_id					= $_REQUEST['checkbox'][0];
		$prod_remains				= false;
		$despatch_note				= trim($_REQUEST['holded_despatch_note']);
		$despatch_id				= trim($_REQUEST['holded_despatch_id']);
		$email_content_despatch		= trim($_REQUEST['despatch_email_content']);
		$email_customer_preview		= trim($_REQUEST['preview_custemail']);
		$additional_emailid			= trim($_REQUEST['preview_ccemail']);
		$despatch_exp_delivery_date	= $_REQUEST['holded_exp_delivery_date_str'];
		$completly_despatched 		= false;
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
						// Inserting a record to the order details details table to track the despatches
						$atleast_one 								= true;
						$insert_array								= array();
						$insert_array['orderdet_id']			= $det_arr[$i];
						$insert_array['despatched_qty']		= $row_orderdet['order_qty'];
						$insert_array['despatched_on']		= 'now()';
						$insert_array['despatched_by']		= $_SESSION['console_id'];
						if ($despatch_id!='')
							$insert_array['despatched_reference']	= $despatch_id;
							
						if ($despatch_exp_delivery_date!='')
						{
							$exp_date_arr = explode('-',$despatch_exp_delivery_date);
							$exp_delivery_date_str = $exp_date_arr[2].'-'.$exp_date_arr[1].'-'.$exp_date_arr[0];
							$insert_array['despatched_expected_delivery_date']	= $exp_delivery_date_str;
						}	
						
						$db->insert_from_array($insert_array,'order_details_despatched');	
						$cur_dep_id = $db->insert_id();
						// decrementing the quantity in order_details table
						$sql_update = "UPDATE order_details 
													SET 
														order_qty = 0  
													WHERE 
														orderdet_id =".$det_arr[$i]." 
													LIMIT 
														1";
						$db->query($sql_update);
						
						$despatchid_arr[] 					= $det_arr[$i];
						$despatchqty_arr[$det_arr[$i]] = $row_orderdet['order_qty'];
						
						// Check whether any qty exists for current product in back order for current order
						$sql_back = "SELECT order_backorder_id 
												FROM 
													order_details_backorder 
												WHERE 
													orderdet_id = ".$det_arr[$i]." 
												LIMIT 
													1";
						$ret_back = $db->query($sql_back);
						if ($db->num_rows($ret_back)==0)
						{
							// Update the despatched status of current product to Y in order details table
							$sql_update = "UPDATE order_details 
												SET 
													order_dispatched = 'Y' 
												WHERE 
													orderdet_id=".$det_arr[$i]."  
													AND order_qty=0 
												LIMIT 
													1";
								$db->query($sql_update);
						}	
				}
			}						
		}
		
		// Check whether the order status is to be changed to despatched
			// Check whether there exists any items in back order for current order
			$sql_back = "SELECT order_backorder_id 
									FROM 
										order_details a,order_details_backorder b 
									WHERE 
										a.orders_order_id = $order_id 
										AND a.orderdet_id = b.orderdet_id 
									LIMIT 
										1";
			$ret_back = $db->query($sql_back);
			if($db->num_rows($ret_back))
			{
				$prod_remains = true;
			}
			if($prod_remains==false)
			{
				// Check whether there remain any items in order_details with order_qty >0
				$sql_check = "SELECT orderdet_id 
										FROM 
											order_details 
										WHERE 
											orders_order_id = $order_id 
											AND order_qty>0 
										LIMIT 
											1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{			
						$sql_update = "UPDATE 
											orders
										SET 
											order_status = 'DESPATCHED'  
										WHERE 
											order_id = $order_id 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
										1";
						$db->query($sql_update);		
						$completly_despatched = true;				
				}
			}
			$alert = 'Items Despatched Successfully';
			// Making entries to notes section in case if any additional note is specified
			if($despatch_note!='')
			{
				$insert_array						= array();
				$insert_array['orders_order_id']	= $order_id;
				$insert_array['note_add_date']		= 'now()';
				$insert_array['user_id']			= $_SESSION['console_id'];
				$insert_array['note_text']			= addslashes($despatch_note);
				$insert_array['note_type']			= 6;
				$insert_array['note_related_id']	= $cur_dep_id;
				$db->insert_from_array($insert_array,'order_notes');
				$alert .= '. Additional note saved in notes section';
			}
				// Saving and sending mail over here
				$ord_arr['order_id']				= $order_id;
				$ord_arr['despatch_id']				= $despatch_id; 
				$ord_arr['despatch_note']			= $despatch_note; 
				$ord_arr['despatched_prods'] 		= $despatchid_arr;
				$ord_arr['despatched_qtys'] 		= $despatchqty_arr;
				$ord_arr['completly_despatched']	= $completly_despatched;
				//save_and_send_OrderMail('DESPATCHED',$ord_arr);
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
					$email_disabled		= stripslashes($row_template['lettertemplate_disabled']);
					// Check whether despatch email is to be send to any other email id
					//$additional_emailid = '';
					/*$sql_gen = "SELECT order_despatch_additional_email 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 1";
					$ret_gen = $db->query($sql_gen);
					if($db->num_rowS($ret_gen))
					{
						$row_gen = $db->fetch_array($ret_gen);
						$additional_emailid = trim(stripslashes($row_gen['order_despatch_additional_email']));
					}*/
					
					// Building email headers to be used with the mail
					$email_headers 	 = "From: $ecom_hostname	<$email_from>\n";
					if($additional_emailid != '')
						$email_headers  .= "Cc: ".$additional_emailid."\n";
					$email_headers 	.= "MIME-Version: 1.0\n";
					$email_headers 	.= "Content-type: text/html; charset=iso-8859-1\n";
					// get the customer email from orders table
					$sql_ords = "SELECT order_custemail 
									FROM 
										orders 
									WHERE 
										order_id = $order_id 
									LIMIT 
										1";
					$ret_ords = $db->query($sql_ords);
					if($db->num_rows($ret_ords))
					{
						$row_ords = $db->fetch_array($ret_ords);
					}
					if($email_customer_preview=='')
						$email_customer_preview = stripslashes($row_ords['order_custemail']);
					// Saving the email to order_emails table
					$insert_array						= array();
					$insert_array['orders_order_id']	= $order_id;
					$insert_array['email_to']			= addslashes(stripslashes(strip_tags($email_customer_preview)));
					$insert_array['email_subject']		= addslashes(stripslashes(strip_tags($email_subject)));
					$insert_array['email_headers']		= addslashes(stripslashes($email_headers));
					$insert_array['email_type']			= 'ORDER_DESPATCHED';
					$insert_array['email_sendonce']		= ($email_disabled==0)?1:0;
					$insert_array['email_lastsenddate']	= ($email_disabled==0)?'now()':'0000-00-00 00:00:00';
					$db->insert_from_array($insert_array,'order_emails');
					$mail_insert_id = $db->insert_id();
					write_email_as_file('ord',$mail_insert_id,(stripslashes($email_content_despatch)));
					if($email_disabled==0)// check whether mail sending is disabled
					{
						mail($email_customer_preview, $email_subject,$email_content_despatch, $email_headers);
						//if($additional_emailid != '')
						//	mail($additional_emailid, $email_subject,$email_content, $email_headers);
					}
					
					
					
				}	
					
				// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
				handle_recalculate_specialtax_calculation($order_id);
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";		
				include ('includes/orders/ajax/order_ajax_functions.php');
				//show_Order_Despatch($order_id,$alert);
				$alert = 'Product(s) Despatched Successfully';
				$_REQUEST['curtab'] ='despatch_tab_td';
				include ('includes/orders/order_details.php');
	
	}
	elseif($_REQUEST['fpurpose']=='operation_despatch_cancel_do')// Case of cancelling despatch details
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		
		$del_id 		= $_REQUEST['desp_id'];
		$order_id		= $_REQUEST['ord_id'];
		if($del_id)
		{
			// get the quantity in despatch table
			$sql_desp = "SELECT orderdet_id,despatched_qty 
									FROM 
										order_details_despatched 
									WHERE 
										despatched_id = $del_id 
									LIMIT 
										1";
			$ret_desp = $db->query($sql_desp);
			if ($db->num_rows($ret_desp))
			{
				$row_desp = $db->fetch_array($ret_desp);
				$desp_qty	= $row_desp['despatched_qty'];
				$det_id		= $row_desp['orderdet_id'];
				if($desp_qty>0)
				{
					// add the quantity to the order_details section
					$sql_update  = "UPDATE 
												order_details 
												SET 
													order_qty = order_qty + $desp_qty ,
													order_dispatched = 'N',
													order_dispatched_on = '0000-00-00 00:00:00',
													order_dispatchedby =0 
												WHERE 
													orderdet_id = $det_id 
												LIMIT 
													1";
					$db->query($sql_update);
					
					// Delete from despatch table
					$sql_del = "DELETE 
											FROM 
												order_details_despatched 
											WHERE 
												despatched_id = $del_id 
											LIMIT 
												1";
					$db->query($sql_del);
					
					// If the order_status is DESPATCHED, then change it back to 'PENDING' in orders table
					$sql_update = "UPDATE 
												orders 
											SET
												order_status = 'PENDING' 
											WHERE 
												order_id = $order_id 
												AND order_status = 'DESPATCHED' 
											LIMIT 
												1";
					$db->query($sql_update);							
					$alert = 'Despatch details cancelled and product placed back in order';	
					
					// Remove any note added during this despatch
					$note_del = "DELETE 
											FROM 
												order_notes 
											WHERE 
												note_type = 6 
												AND note_related_id = $del_id 
												AND orders_order_id = $order_id 
											LIMIT 
												1";										
					$db->query($note_del);							
				}
			}
		}
		show_Order_Despatch($order_id,$alert);
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
		$order_id 						= $_REQUEST['checkbox'][0];
		$ref_amt						= sprintf('%.2f',trim($_REQUEST['txt_refundamt']));
		$ref_reason						= trim($_REQUEST['txt_refundreason']);
		$ret_stock						= trim($_REQUEST['chk_stock_return']);
		$stock_return_required 			= false;
		$alert							= '';
		if ($order_id)
		{
			// check whether stock is managed in this site and also stock decrement is opted
			$sql_settings = "SELECT product_maintainstock,product_decrementstock 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
			$ret_settings = $db->query($sql_settings);
			if ($db->num_rows($ret_settings))
			{
				$row_settings = $db->fetch_array($ret_settings);
				if ($row_settings['product_maintainstock'] == 1 and $row_settings['product_decrementstock']==1 and $ret_stock==1)
					$stock_return_required = true;
			}
			
			// Get the details required from orders table
			$sql_ord = "SELECT order_status,order_deposit_amt,order_deposit_cleared,customers_customer_id,
								order_currency_symbol,order_currency_convertionrate,
								order_currency_numeric_code,order_currency_convertionrate,
								order_totalprice,order_refundamt,order_paystatus,
								order_paymenttype,order_paymentmethod,order_currency_code,order_totalauthorizeamt,order_paystatus_changed_manually     
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
						if($ref_amt==$allowable_refund_amt) // case if refunding amount equal to total amount remaining in order
						{
							$det_arr = array();
							// check whether there exists atleast one valid despatched items
							$sql_desp = "SELECT orderdet_id 
											FROM 
												order_details 
											WHERE 
												orders_order_id = $order_id";
							$ret_desp = $db->query($sql_desp);
							if($db->num_rows($ret_desp))
							{
								while ($row_desp = $db->fetch_array($ret_desp))
								{
									$det_arr[] = $row_desp['orderdet_id'];
								}
							}
							if(count($det_arr))
							{
								$sql_check = "SELECT despatched_id 
												FROM 
													order_details_despatched 
												WHERE 
													orderdet_id IN (".implode(',',$det_arr).")  
													AND despatched_qty >despatched_returned_qty";
								$ret_check = $db->query($sql_check);
								if($db->num_rows($ret_check))
								{
									$alert = "Sorry!! full amount cannot be refunded since there are despatched item(s) in order";

								}
							}	
						}
						if(!alert)
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
					}
					
					if($alert=='')
					{
						// If reached here then it is legal to refund the order
						// Now check for the payment method and payment types used in order
						if ($row_ord['order_paymenttype']=='credit_card') // case if credit card in involved
						{
							if ($row_ord['order_paymentmethod']=='PROTX' ) // check whether the payment method used in protx and payment status is not changed manually from console. If gateway is protx, if order fails, console user can manually mark it as payment received. This is the situation
							{
								if($ecom_siteid==88) // if refund is done in skatesrus then do not need to refund automatically.
								{
									$baseStatus = "OK"; // forcing the refund to be successful
								}
								else
								{
									if($row_ord['order_paystatus_changed_manually']!=1) // case if paystatus is changes by console user
										include 'console_refund.php';
									else // case if payment status is set to paid manually by console user
									{
										$baseStatus = "OK"; // forcing the refund to be successful
										$alert_more = '<br>Since payment status of current order was changed to Payment Received manually by console user, refund details not send to Protx gateway.';
									}
								}	
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
					}
					else
						$ret_status = $alert;
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
						
						// Check whether the payment type for current order is pay_on_account
							if ($row_ord['order_paymenttype']=='pay_on_account' and $row_ord['customers_customer_id'] and $row_ord['order_paystatus']=='Paid')
							{
								// Finding amount remaining in the order after any of the refunds made
								if ($ref_amt>0)
								{
									$sql_currency = "SELECT curr_code,curr_numeric_code,curr_sign_char FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default=1";
									$ret_currency = $db->query($sql_currency);
									if ($db->num_rows($ret_currency))
									{
										$row_currency	= $db->fetch_array($ret_currency);
									}	
									// Making and entry to the order_payonaccount_details table with the details as Refunded due to order cancellation 
									$insert_array												 	= array();
									$insert_array['pay_date']									= 'now()';
									$insert_array['orders_order_id']							= $order_id;
									$insert_array['sites_site_id']								= $ecom_siteid;
									$insert_array['customers_customer_id']				= $row_ord['customers_customer_id'];
									$insert_array['pay_amount']								= $ref_amt;
									$insert_array['pay_transaction_type']					= 'C';
									$insert_array['pay_details']								= 'Refunded due to order refund - Order Id '.$order_id;
									$insert_array['pay_paystatus']							= 'Paid';
									$insert_array['pay_paymenttype']						= 'OTHER';
									$insert_array['pay_paystatus_changed_by']			= $_SESSION['console_id'];
									$insert_array['pay_paystatus_changed_on']			= 'now()';
									$insert_array['pay_curr_rate']							= 1;
									$insert_array['pay_curr_code']							= $row_currency['curr_code'];
									$insert_array['pay_curr_symbol']						= $row_currency['curr_sign_char'];
									$insert_array['pay_curr_numeric_code']				= $row_currency['curr_numeric_code'];
									$db->insert_from_array($insert_array,'order_payonaccount_details');
									// Decrementing the used limit for current customer
									$update_cust = "UPDATE 
																customers 
																	SET 
																		customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - $ref_amt 
																WHERE 
																	customer_id =".$row_ord['customers_customer_id']." 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
									$db->query($update_cust);
																	
								}	
							}
						
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
						$insert_array['note_related_id']		= $refunded_id;
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Additional note saved in notes section';
						$refunded_arr = array();
						$prodext_arr	= array();
						// Check whether any of the products currently in order_details are selected for refunding. In this case that product will be moved to cancelled section and will be marked as refunded in 
						// cancelled section
						for($i=0;$i<count($_REQUEST['checkboxprod']);$i++)
						{
							// Check whether this item is already refunded in order_details table
							$sql_check = "SELECT order_refunded ,order_qty,order_stock_combination_id,products_product_id,order_preorder  
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
								
								// Moving the qty to the cancelled table and mark it as refunded
								$insert_array												= array();
								$insert_array['orderdet_id']							= $_REQUEST['checkboxprod'][$i];
								$insert_array['cancelled_qty']						=  $row_check['order_qty'];
								$insert_array['order_cancelledon']					= 'now()';
								$insert_array['order_cancelledby']					=  $_SESSION['console_id'];
								$insert_array['order_refunded']						= 'Y';
								$insert_array['order_refundedon']					= 'now()';
								$insert_array['order_refundedby']					= $_SESSION['console_id'];
								$db->insert_from_array($insert_array,'order_details_cancelled');
								
								//Update order details table to decrement the qty moved to cancelled
								$sql_update = "UPDATE 
															order_details 
														SET 
															order_qty = order_qty - ".$row_check['order_qty']." 
														WHERE 
															orderdet_id = ".$_REQUEST['checkboxprod'][$i]." 
														LIMIT 
															1";
								$db->query($sql_update);
								
								// Making entry to order_details_refunded_products table
								$insert_array					= array();
								$insert_array['refund_id']		= $refunded_id;
								$insert_array['orderdet_id']	= $_REQUEST['checkboxprod'][$i];
								$insert_array['refund_qty']	= $row_check['order_qty'];
								$db->insert_from_array($insert_array,'order_details_refunded_products');
								
								// Update the refunded status for current product in order_details table
								$update_array						= array();
								$update_array['order_refunded']		= 'Y';
								$update_array['order_refundedon']	= 'now()';
								$update_array['order_refundedby']	= $_SESSION['console_id'];
								$db->update_from_array($update_array,'order_details',array('orderdet_id'=>$_REQUEST['checkboxprod'][$i]));
								
								// Checking stock of refunded products to be returned to stock
								if($stock_return_required == true)
								{
									do_Refund_Stock_Return($row_check);
								}
							}
						}
						
						
						for($i=0;$i<count($_REQUEST['checkboxcancelprod']);$i++)
						{
						
							// Check whether this item is already refunded in order_details table
							$sql_check = "SELECT a.order_refunded,a.orderdet_id,a.cancelled_qty as order_qty,b.order_stock_combination_id,
															b.products_product_id,b.order_preorder 
													FROM 
														order_details_cancelled a,order_details b 
													WHERE 
														a. order_cancelled_id = ".$_REQUEST['checkboxcancelprod'][$i]." 
														AND a.orderdet_id=b.orderdet_id 
													LIMIT
													 1"; 
							$ret_check = $db->query($sql_check);
							if ($db->num_rows($ret_check))
							{
								$row_check = $db->fetch_array($ret_check);
							}
							if ($row_check['order_refunded']=='N') // case if the item is not refunded
							{
								if(!in_array($row_check['orderdet_id'],$refunded_arr))
									$refunded_arr[] = $row_check['orderdet_id'];				
								// Making entry to order_details_refunded_products table
								$insert_array						= array();
								$insert_array['refund_id']		= $refunded_id;
								$insert_array['orderdet_id']	= $row_check['orderdet_id'];
								$insert_array['refund_qty']	= $row_check['order_qty'];
								$db->insert_from_array($insert_array,'order_details_refunded_products');			
								// Update the refunded status for current product in order_details_cancelled table
								$update_array						= array();
								$update_array['order_refunded']		= 'Y';
								$update_array['order_refundedon']	= 'now()';
								$update_array['order_refundedby']	= $_SESSION['console_id'];
								$db->update_from_array($update_array,'order_details_cancelled',array('order_cancelled_id'=>$_REQUEST['checkboxcancelprod'][$i]));
							}
							
							// Checking stock of refunded products to be returned to stock
							if($stock_return_required == true)
							{
								do_Refund_Stock_Return($row_check);
							}
						}
						
						
						if($row_ord['order_totalauthorizeamt']>0) // if authorize amount is >0
						{
							$rets = get_updatable_order_status($order_id,'auth');
							if($rets==true)
							{
								$order_fields = "order_status='DESPATCHED'"; 
								$add_condition = "   ";
							}	
							else
							{
								$order_fields = "order_paystatus = 'REFUNDED',order_status='CANCELLED',order_cancelled_on=now()";
								$add_condition = " AND order_refundamt = order_totalauthorizeamt  ";
							}	
							// Check whether full amount is refunded. If so change the payment status to REFUNDED and order status to not CANCELLED
							$sql_update = "UPDATE orders 
												SET 
													$order_fields   
												WHERE 
													order_id = $order_id 
													$add_condition
												LIMIT 
													1";
							$db->query($sql_update);		
						}
						else  // if authorize amount does not exists
						{
							$rets = get_updatable_order_status($order_id,'normal');
							if($rets==true)
							{
								$order_fields = "order_status='DESPATCHED'"; 
								$add_condition = "   ";
							}	
							else
							{
								$order_fields = "order_paystatus = 'REFUNDED',order_status='CANCELLED',order_cancelled_on=now()";
								$add_condition = " AND order_refundamt = order_totalprice  ";
							}	
							// Check whether full amount is refunded. If so change the payment status to REFUNDED and order status to not CANCELLED
							$sql_update = "UPDATE orders 
												SET 
													$order_fields  
												WHERE 
													order_id = $order_id 
													$add_condition  
												LIMIT 
													1";
							$db->query($sql_update);
							//Check whether the status of order is CANCELLED
							$sql_check = "SELECT order_status 
											FROM 
												orders 
											WHERE 
												order_id = $order_id 
											LIMIT 
												1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check))
							{
								$row_check = $db->fetch_array($ret_check);
								if($row_check['order_status'] == 'CANCELLED') //If status is cancelled then decrement the price promise usage count
								{
									do_pricepromise_handling($order_id,'decrement'); // calling function to handle the price promise usage count
								}
							}		
						}	
						// Saving and sending mail over here
						$ord_arr['order_id']									= $order_id;
						$ord_arr['refund_amt']								= $ref_amt; 
						$ord_arr['refund_note']							= $ref_reason; 
						$ord_arr['refunded_prods'] 						= $refunded_arr;
						$ord_arr['order_currency_convertionrate'] 	= $row_ord['order_currency_convertionrate'];
						$ord_arr['order_currency_symbol'] 			= $row_ord['order_currency_symbol'];
						save_and_send_OrderMail('REFUNDED',$ord_arr);
						$alert = 'Refund Successfull'.$alert_more;	
					}
					else
						$alert = 'Refund not Successfull'.'<br><br>'.$ret_status;
				}
				
			}
		}	
		// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
		handle_recalculate_specialtax_calculation($order_id);
		
		/*$_REQUEST['curtab']= 'refund_tab_td';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/ajax/order_ajax_functions.php');
		include ('includes/orders/order_details.php');	*/
		echo '<form method="post" action="home.php?request=orders" name="auto_refund_form" id="auto_refund_form">
				<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="'.$order_id.'"/>.
				<input type="hidden" name="edit_id" id="edit_id" value="'.$order_id.'"/>
				<input type="hidden" name="ord_status" value="'.$_REQUEST['ord_status'].'" />
				<input type="hidden" name="records_per_page" value="'.$_REQUEST['records_per_page'].'" />
				<input type="hidden" name="ord_email" value="'.$_REQUEST['ord_email'].'" />
				<input type="hidden" name="ord_fromdate" value="'.$_REQUEST['ord_fromdate'].'" />
				<input type="hidden" name="ord_todate" value="'.$_REQUEST['ord_todate'].'" />
				<input type="hidden" name="ord_stores" value="'.$_REQUEST['ord_stores'].'" />
				<input type="hidden" name="ord_sort_by" value="'.$_REQUEST['ord_sort_by'].'" />
				<input type="hidden" name="ord_sort_order" value="'.$_REQUEST['ord_sort_order'].'" />
				<input type="hidden" name="pg" value="'.$_REQUEST['pg'].'" />
				<input type="hidden" name="start" value="'.$_REQUEST['start'].'" />
				<input type="hidden" name="records_per_page" value="'.$_REQUEST['records_per_page'].'" />
				<input type="hidden" name="selected_tab" id="selected_tab" value="'.$_REQUEST['curtab'].'" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="ord_details" />
				<input type="hidden" name="curtab" id="curtab" value="refund_tab_td" />
				<input type="hidden" name="post_alert" id="post_alert" value="'.$alert.'" />
			</form>
				<script type="text/javascript">
					document.auto_refund_form.submit();
				</script>
				';
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
	elseif($_REQUEST['fpurpose']=='operation_returnrefund_prods') //  clicked for products clubbed with refund
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		order_returnrefund_product_details($_REQUEST['ret_id']);
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
						order_currency_convertionrate,order_currency_code, 
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
		$edit_id = $order_id;
		$_REQUEST['curtab'] = 'payment_tab_td';
		$_REQUEST['post_alert'] = $alert;
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/ajax/order_ajax_functions.php');
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
	elseif($_REQUEST['fpurpose']=='show_movetobackorder_select') // cae of showing the div to select the qty to be move to back order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode('~',$_REQUEST['prod_id_str']);
		move_to_BackOrder_Select($_REQUEST['ord_id'],$prod_sel_arr);
	}
	elseif($_REQUEST['fpurpose']=='show_movetobackorder_do') // moving the selected quantity to Back order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode(',',$_REQUEST['prod_id_str']);
		$order_id = $_REQUEST['ord_id'];
		if(count($prod_sel_arr))
		{
			for($i=0;$i<count($prod_sel_arr);$i++)
			{
				$cur_arr 		= explode('~',$prod_sel_arr[$i]);
				$det_id			= $cur_arr[0];
				$move_qty		= $cur_arr[1];
				// Decrement the qty in orders table
				$sql_update = "UPDATE 
											order_details 
										SET 
											order_qty = order_qty - $move_qty 
										WHERE 
											orderdet_id = $det_id 
											AND orders_order_id = $order_id 
										LIMIT 
											1";
				$db->query($sql_update);
				// Check wheter an entry already exists for current item in back order 
				$sql_backord = "SELECT order_backorder_id  
											FROM 
												order_details_backorder 
											WHERE 
												orderdet_id = $det_id 
											LIMIT 
												1";
				$ret_backord = $db->query($sql_backord);
				if ($db->num_rows($ret_backord)==0)
				{
					// Make a new entry in order_details_backorder 
					$insert_array												= array();
					$insert_array['orderdet_id']							= $det_id;
					$insert_array['backorder_qty']						= $move_qty;
					$insert_array['order_backorderon']					= 'now()';
					$insert_array['order_backorderby']					=  $_SESSION['console_id'];
					$db->insert_from_array($insert_array,'order_details_backorder');
				}
				else
				{
					$row_backord = $db->fetch_array($ret_backord);
					$sql_update = "UPDATE order_details_backorder 
											SET 
												backorder_qty = backorder_qty + $move_qty, 
												order_backorderon = now(),
												order_backorderby =".$_SESSION['console_id']." 
											WHERE 
												order_backorder_id = ".$row_backord['order_backorder_id']." 
											LIMIT 
												1";
					$db->query($sql_update);
				}	
			}
			$alert = 'Qty moved to back order successfully';
		}
		else
			$alert = 'Sorry!! invalid quantity';	
		
		// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
		handle_recalculate_specialtax_calculation($order_id);	
		// calling function to reload the order summary details
		 show_Order_Summary($order_id,$alert);
	}
	elseif($_REQUEST['fpurpose']=='show_movebacktoorder_select') // cae of showing the div to select the qty to be move back to order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode('~',$_REQUEST['prod_id_str']);
		move_back_ToOrder_Select($_REQUEST['ord_id'],$prod_sel_arr);
	}
	elseif($_REQUEST['fpurpose']=='show_movebacktoorder_do') // moving the selected quantity back to order
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode(',',$_REQUEST['prod_id_str']);
		$order_id = $_REQUEST['ord_id'];
		if(count($prod_sel_arr))
		{
			for($i=0;$i<count($prod_sel_arr);$i++)
			{
				$cur_arr 		= explode('~',$prod_sel_arr[$i]);
				$det_id			= $cur_arr[0];
				$move_qty		= $cur_arr[1];
				$ext_qty		= 0;
				// Get the quantity existsing in back order table for current item
				$sql_sel = "SELECT backorder_qty 
									FROM 
										order_details_backorder 
									WHERE 
										orderdet_id = $det_id 
									LIMIT 
										1";
				$ret_sel = $db->query($sql_sel);
				if ($db->num_rows($ret_sel))
				{
					$row_sel = $db->fetch_array($ret_sel);
					$ext_qty = $row_sel ['backorder_qty'];
				}
				if($ext_qty>$move_qty)
				{
					// Decrement the qty in back order table
					$sql_update = "UPDATE 
												order_details_backorder 
											SET 
												backorder_qty = backorder_qty - $move_qty 
											WHERE 
												orderdet_id = $det_id 
											LIMIT 
												1";
					$db->query($sql_update);
				}
				else
				{
					$sql_del = "DELETE FROM 
										order_details_backorder 
									WHERE 
										orderdet_id = $det_id 
									LIMIT 
										1";
					$db->query($sql_del);
				}	
				// Updating the order_details table with the qty
				$sql_update = "UPDATE order_details 
											SET 
												order_qty = order_qty + $move_qty 
											WHERE 
												orderdet_id = $det_id  
											LIMIT 
												1";
				$db->query($sql_update);
			}
			$alert = 'Qty moved back to order successfully';
		}
		else
			$alert = 'Sorry!! invalid quantity';	
		// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
		handle_recalculate_specialtax_calculation($order_id);	
		// calling function to reload the order summary details
		 show_Order_Summary($order_id,$alert);
	}
	elseif($_REQUEST['fpurpose']=='show_movebacktocancel_select') // cae of showing the div to select the qty to be move back to cancel
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode('~',$_REQUEST['prod_id_str']);
		move_to_Cancel_Select($_REQUEST['ord_id'],$prod_sel_arr,$_REQUEST['cancel_src']);
	}
	elseif($_REQUEST['fpurpose']=='show_movetocancel_do') // moving the selected quantity as cancelled
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr 	= explode(',',$_REQUEST['prod_id_str']);
		$order_id 		= $_REQUEST['ord_id'];
		$cancel_src 	= $_REQUEST['cancel_src'];
		
		if(count($prod_sel_arr))
		{
			for($i=0;$i<count($prod_sel_arr);$i++)
			{
				$cur_arr 		= explode('~',$prod_sel_arr[$i]);
				$det_id			= $cur_arr[0];
				$move_qty		= $cur_arr[1];
				if ($cancel_src=='main') // case of cancelling from main
				{
					// Decrement the qty in orders table
					$sql_update = "UPDATE 
												order_details 
											SET 
												order_qty = order_qty - $move_qty 
											WHERE 
												orderdet_id = $det_id 
												AND orders_order_id = $order_id 
											LIMIT 
												1";
					$db->query($sql_update);
				}	
				elseif($cancel_src=='back') // case of cancelling from back order 
				{
					$cur_qty = 0;
					// Get the current qty in back order for current item
					$sql_curqty = "SELECT backorder_qty 
												FROM 
													order_details_backorder 
												WHERE 
													orderdet_id = $det_id 
												LIMIT 
													1";
					$ret_curqty = $db->query($sql_curqty);
					if ($db->num_rows($ret_curqty))
					{
						$row_curqty = $db->fetch_array($ret_curqty);
						$cur_qty		= $row_curqty['backorder_qty'];
					}
					if($cur_qty>$move_qty)
					{
						// Decrement the qty in orders table
						$sql_update = "UPDATE 
													order_details_backorder 
												SET 
													backorder_qty = backorder_qty - $move_qty 
												WHERE 
													orderdet_id = $det_id 
												LIMIT 
													1";
						$db->query($sql_update);
					}
					else
					{
						$sql_del = "DELETE FROM 
											order_details_backorder 
										WHERE 
											orderdet_id = $det_id 
										LIMIT 
											1";
						$db->query($sql_del);
					}	
				}
				/*// Check wheter an entry already exists for current item in cancelled section 
				$sql_cancelord = "SELECT order_cancelled_id   
											FROM 
												order_details_cancelled 
											WHERE 
												orderdet_id = $det_id 
											LIMIT 
												1";
				$ret_cancelord = $db->query($sql_cancelord);
				if ($db->num_rows($ret_cancelord)==0)
				{*/
					// Make a new entry in order_details_backorder 
					$insert_array												= array();
					$insert_array['orderdet_id']							= $det_id;
					$insert_array['cancelled_qty']						= $move_qty;
					$insert_array['order_cancelledon']					= 'now()';
					$insert_array['order_cancelledby']					=  $_SESSION['console_id'];
					$db->insert_from_array($insert_array,'order_details_cancelled');
				/*}
				else
				{
					$row_cancelord = $db->fetch_array($ret_cancelord);
					$sql_update = "UPDATE order_details_cancelled 
											SET 
												cancelled_qty = cancelled_qty + $move_qty ,
												order_cancelledon = now(),
												order_cancelledby =".$_SESSION['console_id']." 
											WHERE 
												order_cancelled_id = ".$row_cancelord['order_cancelled_id']." 
											LIMIT 
												1";
					$db->query($sql_update);
				}*/	

			}
                        // calling function to check whether order status is to be changes to Despatched 
                        check_order_despatch($order_id);
			$alert = 'Qty moved to back order successfully';
		}
		else
			$alert = 'Sorry!! invalid quantity';
         // calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
		handle_recalculate_specialtax_calculation($order_id);	       
		// calling function to reload the order summary details
		 show_Order_Summary($order_id,$alert);
	}
	elseif($_REQUEST['fpurpose']=='show_return_select') // cae of showing the div to select the qty to be move back to cancel
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		$prod_sel_arr = explode('~',$_REQUEST['prod_id_str']);
		return_Select($_REQUEST['ord_id'],$prod_sel_arr,$_REQUEST['cancel_src']);
	}
	elseif($_REQUEST['fpurpose']=='return_do') // cae of despatch retun action is to be performed
	{
		// getting the values passed via post in variables
		$ref_amt 				= trim($_REQUEST['return_amt']);
		$return_id_arr			= explode('~',$_REQUEST['return_id_str']);
		$return_qty_arr		= explode('~',$_REQUEST['return_qty_str']);
		$return_type_arr		= explode('~',$_REQUEST['return_type_str']);
		$return_reason_arr	= explode('^~~^',$_REQUEST['return_reason_str']);
		$order_id				= $_REQUEST['checkbox'][0];
		$baseStatus			= '';
		if($ref_amt!='' and $ref_amt!=0) // case if refund amount is specified
		{
			if(is_numeric($ref_amt))
			{
				if ($ref_amt>0)
				{
					// Call the procedure to refund the specified amount
					// Get the details required from orders table
					$sql_ord = "SELECT order_status,order_deposit_amt,order_deposit_cleared,
										order_currency_symbol,order_currency_convertionrate,
										order_currency_numeric_code,order_currency_convertionrate,
										order_totalprice,order_refundamt,order_paystatus,order_paystatus_changed_manually,
										order_paymenttype,order_paymentmethod,order_currency_code,order_totalauthorizeamt,
										customers_customer_id      
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
						if ($alert)
						{
							echo $alert;
							exit;
						}
						if(!$alert)
						{	
							// If reached here then it is legal to refund the order
							// Now check for the payment method and payment types used in order
							if ($row_ord['order_paymenttype']=='credit_card') // case if credit card in involved
							{
								if ($row_ord['order_paymentmethod']=='PROTX') // check whether the payment method used in protx
								{
									if($row_ord['order_paystatus_changed_manually']!=1) // case if paystatus is changes by console user
										include 'console_refund.php';
									else // case if payment status is set to paid manually by console user
									{
										$baseStatus = "OK"; // forcing the refund to be successful
										//$alert_more = '<br>Since payment status of current order was changed to Payment Received manually by console user, refund details not send to Protx gateway.';
									}					
									//include 'console_refund.php';
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
						}
						if($baseStatus=='OK' and !$alert)
						{
							// Converting the specified amount to default currency
							$ref_amt 		= print_price_default_currency($ref_amt,$row_ord['order_currency_convertionrate'],'',true);
							$update_sql = "UPDATE orders 
														SET 
															order_refundamt = order_refundamt + $ref_amt 
														WHERE 
															order_id = $order_id 
														LIMIT 
															1";
							$db->query($update_sql);
							
							
							if ($row_ord['order_paymenttype']=='pay_on_account' and $row_ord['customers_customer_id'] and $row_ord['order_paystatus']=='Paid')
							{
								// Finding amount remaining in the order after any of the refunds made
								if ($ref_amt>0)
								{
									$sql_currency = "SELECT curr_code,curr_numeric_code,curr_sign_char FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default=1";
									$ret_currency = $db->query($sql_currency);
									if ($db->num_rows($ret_currency))
									{
										$row_currency	= $db->fetch_array($ret_currency);
									}	
									// Making and entry to the order_payonaccount_details table with the details as Refunded due to order cancellation 
									$insert_array												 	= array();
									$insert_array['pay_date']									= 'now()';
									$insert_array['orders_order_id']							= $order_id;
									$insert_array['sites_site_id']								= $ecom_siteid;
									$insert_array['customers_customer_id']				= $row_ord['customers_customer_id'];
									$insert_array['pay_amount']								= $ref_amt;
									$insert_array['pay_transaction_type']					= 'C';
									$insert_array['pay_details']								= 'Refunded due to order return - Order Id '.$order_id;
									$insert_array['pay_paystatus']							= 'Paid';
									$insert_array['pay_paymenttype']						= 'OTHER';
									$insert_array['pay_paystatus_changed_by']			= $_SESSION['console_id'];
									$insert_array['pay_paystatus_changed_on']			= 'now()';
									$insert_array['pay_curr_rate']							= 1;
									$insert_array['pay_curr_code']							= $row_currency['curr_code'];
									$insert_array['pay_curr_symbol']						= $row_currency['curr_sign_char'];
									$insert_array['pay_curr_numeric_code']				= $row_currency['curr_numeric_code'];
									$db->insert_from_array($insert_array,'order_payonaccount_details');
									// Decrementing the used limit for current customer
									$update_cust = "UPDATE 
																customers 
																	SET 
																		customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - $ref_amt 
																WHERE 
																	customer_id =".$row_ord['customers_customer_id']." 
																	AND sites_site_id = $ecom_siteid 
																LIMIT 
																	1";
									$db->query($update_cust);
																	
								}	
							}
							
							
							
							// Insert the refund amount to order_details_refunded table
							$insert_array								= array();
							$insert_array['refund_on']				= 'now()';
							$insert_array['refund_by']				= $_SESSION['console_id'];
							$insert_array['refund_amt']			= $ref_amt;
							$insert_array['orders_order_id']		= $order_id;
							$db->insert_from_array($insert_array,'order_details_refunded');
							$refunded_id = $db->insert_id();
							
							// Making an entry for reason in order_notes table
							$ref_reason								= 'Refunded '.print_price_selected_currency($ref_amt,$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true).' during order return';
							$insert_array								= array();
							$insert_array['orders_order_id']		= $order_id;
							$insert_array['note_add_date']		= 'now()';
							$insert_array['user_id']					= $_SESSION['console_id'];
							$insert_array['note_text']				= add_slash($ref_reason);
							$insert_array['note_type']			= 7;
							$insert_array['note_related_id']		= $refunded_id;
							
							$db->insert_from_array($insert_array,'order_notes');
							$curnote_id = $db->insert_id();
							//$succ_alert .= '. Additional note saved in notes section';
						}
					}
				}
			}
			else
				$alert .= 'Refunding amount should be numeric';	
		}
		else // case if refund amount is not specified
			$baseStatus = 'OK';
		
		if(!$refunded_id) $refunded_id =0;
		// Continue the following only if the proceed signal is obtained
		if($baseStatus =='OK' and !$alert)
		{
			
			$prodext_arr	= array();
			// Making changes in respective table for returning the products
			for($i=0;$i<count($return_id_arr);$i++)
			{
				$desp_id 			= $return_id_arr[$i];
				$desp_qty			= $return_qty_arr[$i];
				$desp_type		= $return_type_arr[$i];
				$desp_reason		= $return_reason_arr[$i];
				// Get the orderdetid for current despatch id
				$sql_desp = "SELECT a.orderdet_id,a.products_product_id,a.order_stock_combination_id,a.products_product_id,a.order_preorder   
										FROM 
											order_details a,order_details_despatched b
										WHERE 
											despatched_id = ".$desp_id." 
											AND a.orderdet_id=b.orderdet_id 
											AND despatched_qty >= (despatched_returned_qty+$desp_qty) 
										LIMIT 
											1";
				$ret_desp = $db->query($sql_desp);
				if($db->num_rows($ret_desp))
				{
					$row_dep = $db->fetch_array($ret_desp);
					$det_id = $row_dep['orderdet_id'];
					
					// Inserting the return details to order_details_return table
					$insert_array																= array();
					$insert_array['order_details_despatched_despatch_id']		= $desp_id;
					$insert_array['orderdet_id']											= $det_id;
					$insert_array['return_qty']											= $desp_qty;
					$insert_array['return_on']												= 'now()';
					$insert_array['return_by']												= $_SESSION['console_id'];
					$insert_array['return_type']											= add_slash($desp_type);
					$insert_array['return_reason']										= add_slash($desp_reason);
					$insert_array['order_details_refunded_refund_id']			= $refunded_id; // done to make a link to the refunded amount with the returned product. if no refund done then this will be 0
					$db->insert_from_array($insert_array,'order_details_return');
					$cur_return_id = $db->insert_id();
										
					// Add the returned quantity in the order_details_despatched table 
					$sql_update = "UPDATE 
												order_details_despatched 
											SET 
												despatched_returned_qty = despatched_returned_qty + $desp_qty,
												despatched_returned_atleastone = 'Y' 
											WHERE 
												despatched_id = $desp_id 
											LIMIT 
												1";
					$db->query($sql_update);
					
					
					if($desp_type=='STK_BACK') // should be placed back in stock
					{
						$row_dep['order_qty'] = $desp_qty;
						do_Refund_Stock_Return($row_dep); // calling function to return the stock for current product 
					}
				}
                                 // calling function to check whether order status is to be changes to Despatched 
                                check_order_despatch($order_id);
				$alert = 'Products returned successfully ';
			}
		}
		else
		{
			$alert = 'Sorry!! no products returned .. '.$alert;
		}
		/*$_REQUEST['curtab']= 'returns_tab_td';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/orders/ajax/order_ajax_functions.php');
		include ('includes/orders/order_details.php');	
		*/
		// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
		handle_recalculate_specialtax_calculation($order_id);	
		
		echo '<form method="post" action="home.php?request=orders" name="auto_return_form" id="auto_return_form">
				<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="'.$order_id.'"/>.
				<input type="hidden" name="edit_id" id="edit_id" value="'.$order_id.'"/>
				<input type="hidden" name="ord_status" value="'.$_REQUEST['ord_status'].'" />
				<input type="hidden" name="records_per_page" value="'.$_REQUEST['records_per_page'].'" />
				<input type="hidden" name="ord_email" value="'.$_REQUEST['ord_email'].'" />
				<input type="hidden" name="ord_fromdate" value="'.$_REQUEST['ord_fromdate'].'" />
				<input type="hidden" name="ord_todate" value="'.$_REQUEST['ord_todate'].'" />
				<input type="hidden" name="ord_stores" value="'.$_REQUEST['ord_stores'].'" />
				<input type="hidden" name="ord_sort_by" value="'.$_REQUEST['ord_sort_by'].'" />
				<input type="hidden" name="ord_sort_order" value="'.$_REQUEST['ord_sort_order'].'" />
				<input type="hidden" name="pg" value="'.$_REQUEST['pg'].'" />
				<input type="hidden" name="start" value="'.$_REQUEST['start'].'" />
				<input type="hidden" name="records_per_page" value="'.$_REQUEST['records_per_page'].'" />
				<input type="hidden" name="selected_tab" id="selected_tab" value="'.$_REQUEST['curtab'].'" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="ord_details" />
				<input type="hidden" name="curtab" id="curtab" value="returns_tab_td" />
				<input type="hidden" name="post_alert" id="post_alert" value="'.$alert.'" />
			</form>
				<script type="text/javascript">
				document.auto_return_form.submit();
				</script>
				';
	}
	elseif($_REQUEST['fpurpose']=='send_email_cust') // cae of showing the div to select the qty to be move back to cancel
	{
		//print_r($_REQUEST);
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		// Always set content-type when sending HTML email
		if($ecom_siteid == 100)
		{
			$headers = "From: <info@dincweardancewear.com>"."\n";
		}
		else if($ecom_siteid == 104)
		{
			$headers = "From: <online.enquiries@discount-mobility.co.uk>"."\n";
		}
			$headers .= "MIME-Version: 1.0"."\n";
			$headers .= "Content-type: text/plain;charset=UTF-8" ."\n";

			// More headers
			//$headers .= 'Cc: myboss@example.com' . "\r\n";
			$sql_ord = "SELECT order_custemail FROM
						orders
					WHERE
						order_id=".$_REQUEST['order_id']."
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
			$ret_ord = $db->query($sql_ord);
			$row_ord = 	$db->fetch_array($ret_ord);		
			$emailcust = $row_ord['order_custemail'];
			//$emailcust ='latheesh.george@thewebclinic.co.uk';
			$subust = $_REQUEST['subject'];
			$contentcust = $_REQUEST['content'];
                        $insert_array						= array();
						$insert_array['orders_order_id']	= $_REQUEST['order_id'];
						$insert_array['email_to']			= addslashes(stripslashes($emailcust));
						$insert_array['email_subject']		= addslashes(stripslashes($subust));
						$insert_array['old_email_message']	= addslashes(stripslashes($contentcust));
						$insert_array['email_headers']		= addslashes(stripslashes($headers));
						$insert_array['email_sendonce']		= 1;
						$insert_array['email_lastsenddate']	= 'now()';
						$db->insert_from_array($insert_array,'order_emails_tocust');
						$id_cust = $db->insert_id();
						write_emailcust_as_file($id_cust,addslashes(stripslashes($contentcust)));
			mail($emailcust,$subust,$contentcust,$headers);
			echo "Mail Sent Successfully!!!";
	}
	elseif($_REQUEST['fpurpose']=='show_order_other_cust_mail') // cae of showing the div to select the qty to be move back to cancel
	{
		//print_r($_REQUEST);
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/orders/ajax/order_ajax_functions.php');
		show_order_emails_custom_cust($_REQUEST['order_id'],$alert='');
	}	
	function write_emailcust_as_file($id,$content)
	{
		global $image_path,$db;
		$fname 	= $id.'.txt';
		$folder = '';
		
				if(!is_dir($image_path.'/email_messages_eachcust'))
				{
					mkdir($image_path.'/email_messages_eachcust',0777);
				} 
				if(!is_dir($image_path.'/email_messages_eachcust/order_emails_cust'))
				{
					mkdir($image_path.'/email_messages_eachcust/order_emails_cust',0777);
				} 
				$folder	= '/email_messages_eachcust/order_emails_cust';
				$sql_update = "UPDATE order_emails_tocust 
								SET 
									email_messagepath='".$folder.'/'.$fname."' 
								WHERE 
									 order_email_cust_id = $id 
								LIMIT 
									1";
				$db->query($sql_update);
		
		if($folder)
		{
			$fp = fopen($image_path.'/'.$folder.'/'.$fname,'w');
			fwrite($fp,$content);
			fclose($fp);
		}
	}
function read_emailcust_from_file($id)
{
	global $image_path,$db;
	
			$sql_sel = "SELECT email_messagepath 
							FROM 
								order_emails_tocust 
							WHERE 
								order_email_cust_id = $id 
							LIMIT 
								1";
			$ret_sel = $db->query($sql_sel);
			if($db->num_rows($ret_sel))
			{
				$row_sel = $db->fetch_array($ret_sel);
				$file_path = $row_sel['email_messagepath'];
			}	
	// read the contents of file
	$full_file_path = $image_path.'/'.$file_path;
	$fp = fopen($full_file_path,'r');
	$content = fread($fp,filesize($full_file_path));
	fclose($fp);
	return nl2br($content);
}
/* Function to resend a selected email to particular customer from order details */
function resend_orderEmail_cust($emailid,$orderid)
{
	global $db,$ecom_siteid,$ecom_site_activate_invoice;
	// Get the details of the email
	$sql_email = "SELECT email_to,email_subject,email_messagepath,email_headers							
						FROM
							order_emails_tocust
						WHERE
							orders_order_id = $orderid
							AND order_email_cust_id = $emailid
						LIMIT
							1";
	$ret_email = $db->query($sql_email);
	if ($db->num_rows($ret_email))
	{
		$row_email 		= $db->fetch_array($ret_email);
		$to				= explode(",",stripslashes($row_email['email_to']));
		$header			= stripslashes($row_email['email_headers']);
		$subject		= stripslashes($row_email['email_subject']);
		//$content		= stripslashes($row_email['email_message']);
		$content		= strip_tags(read_emailcust_from_file($emailid));		
		
			for ($i=0;$i<count($to);$i++)
			{
				mail($to[$i],$subject,$content,$header);
			}
			
	}
}
//save the details of the mail resent to particular customer
function save_EmailHistory_cust($emailid,$orderid)
{
	global $db, $ecom_siteid;

	// Inserting to order email history table
	$insert_array						= array();
	$insert_array['orders_order_id']	= $orderid;
	$insert_array['email_id']			= $emailid;
	$insert_array['send_date']			= 'now()';
	$insert_array['send_by']			= $_SESSION['console_id'];
	$db->insert_from_array($insert_array,'order_emails_custom_cust_resend');

	// Updating the fields related to emails
	$update_array						= array();
	$update_array['email_sendonce']		= 1;
	$update_array['email_lastsenddate']	= 'now()';
	$db->update_from_array($update_array,'order_emails_tocust',array('order_email_cust_id'=>$emailid));
	return true;
}
	function do_changeorderstatus_operation($order_id,$ch_stat,$alt_prods,$stat_arr,$note,$src='det')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		// Get the current status of current order
		$sql_ord = "SELECT 	order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,order_status,customers_customer_id,
							order_currency_convertionrate,order_currency_symbol,order_custemail,
							order_subtotal,order_giftwraptotal,order_deliverytotal,order_deliveryprice_only,order_extrashipping,order_tax_total,
							order_customer_discount_value,order_customer_or_corporate_disc,
							order_customer_discount_type,order_customer_discount_percent,order_totalprice,
							order_deposit_amt,order_deposit_amt,gift_vouchers_voucher_id,promotional_code_code_id,
							order_bonuspoint_discount,order_paymentmethod,order_paymenttype,order_refundamt,order_paystatus  
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
			
			if ($src!='det')
			{
				$sr_arr = array ('NEW','PENDING','ONHOLD','BACK','NOT_AUTH');
				if(!in_array($row_ord['order_status'],$sr_arr))
					return 0;	
			}
			if ($row_ord['order_status']!=$ch_stat)
			{
				if($ch_stat!='CANCELLED') // case of status changing to other than Cancelled
				{
					$update_array					= array();
					$update_array['order_status']	= add_slash($ch_stat);
					$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
					$alert = 'Order Status Changed Successfully';
					$succ_cnt=1;
					// Check whether note is added
					$not = trim($note);
					if($not!='') // case if note exists
					{
						// Inserting the note to the order_notes table
						$insert_array						= array();
						$insert_array['orders_order_id']	= $order_id;
						$insert_array['note_add_date']		= 'now()';
						$insert_array['user_id']			= $_SESSION['console_id'];
						$insert_array['note_text']			= addslashes($not);
						$insert_array['note_type']			= get_order_status_text_to_number($ch_stat);
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Reason added as note';
					}
				}
				else // case of status changing to Cancelled
				{
					if ($src!='det') // case of coming from listing page
					{
						/*$ret_arr = do_ordercancelReturns($order_id,$stat_arr);
						if($ret_arr['msg']=='REFUND_OR_DESPATCH')
						{
							$alert = 'Sorry!! Status cannot be changed since some of the products have been already despatched/refunded';
						}
						else
						{*/
							$update_array							= array();
							$update_array['order_status']			= add_slash($ch_stat);
							$update_array['order_cancelled_on']		= 'now()';
							$update_array['order_cancelled_from']	= 'A'; // from admin side
							$update_array['order_cancelled_by']		= $_SESSION['console_id'];
							$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
							$alert = 'Order Status Changed Successfully';
							$succ_cnt=1;
							/*$row_ord['alt_prods'] 	= $alternate_str;
							$row_ord['reason'] 		= $not;
							save_and_send_OrderMail('cancel',$row_ord);	*/
							
							$not = trim($note);
							if($not!='') // case if note exists
							{
								// Inserting the note to the order_notes table
								$insert_array						= array();
								$insert_array['orders_order_id']	= $order_id;
								$insert_array['note_add_date']		= 'now()';
								$insert_array['user_id']			= $_SESSION['console_id'];
								$insert_array['note_text']			= addslashes($not);
								$insert_array['note_type']			= get_order_status_text_to_number($ch_stat);
								$db->insert_from_array($insert_array,'order_notes');
								$alert .= '. Reason added as note';
							}
							
						//}
					}
					else
					{						
					$ret_arr = do_ordercancelReturns($order_id,$stat_arr);
					if($ret_arr['msg']=='REFUND_OR_DESPATCH')
					{
						$alert = 'Sorry!! Status cannot be changed since some of the products have been already despatched/refunded';
					}
					else
					{
						do_pricepromise_handling($order_id,'decrement'); // calling function to handle the price promise usage count
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
							$update_array['order_cancelled_by']		= $_SESSION['console_id'];
							$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
							$alert = 'Order Status Changed Successfully';
						}
						elseif($row_ord['order_paymentmethod']=='PROTX' and ($row_ord['order_paystatus']=='ABORTED' or $row_ord['order_paystatus']=='Pay_Failed'))
						{
							$update_array['order_cancelled_by']		= $_SESSION['console_id'];
							$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
							$alert = 'Order Status Changed Successfully';
						}
						
						// Check whether the payment type for current order is pay_on_account
						if ($row_ord['order_paymenttype']=='pay_on_account' and $row_ord['customers_customer_id'] and $row_ord['order_paystatus']=='Paid')
						{
							// Finding amount remaining in the order after any of the refunds made
							$remaining_amt = ($row_ord['order_totalprice']-$row_ord['order_refundamt']);
							if ($remaining_amt>0)
							{
								$sql_currency = "SELECT curr_code,curr_numeric_code,curr_sign_char FROM general_settings_site_currency WHERE sites_site_id=$ecom_siteid AND curr_default=1";
								$ret_currency = $db->query($sql_currency);
								if ($db->num_rows($ret_currency))
								{
									$row_currency	= $db->fetch_array($ret_currency);
								}	
								// Making and entry to the order_payonaccount_details table with the details as Refunded due to order cancellation 
								$insert_array												 	= array();
								$insert_array['pay_date']									= 'now()';

								$insert_array['orders_order_id']							= $order_id;
								$insert_array['sites_site_id']								= $ecom_siteid;
								$insert_array['customers_customer_id']				= $row_ord['customers_customer_id'];
								$insert_array['pay_amount']								= $remaining_amt;
								$insert_array['pay_transaction_type']					= 'C';
								$insert_array['pay_details']								= 'Refunded due to order cancellation - Order Id '.$order_id;
								$insert_array['pay_paystatus']							= 'Paid';
								$insert_array['pay_paymenttype']						= 'OTHER';
								$insert_array['pay_paystatus_changed_by']			= $_SESSION['console_id'];
								$insert_array['pay_paystatus_changed_on']			= 'now()';
								$insert_array['pay_curr_rate']							= 1;
								$insert_array['pay_curr_code']							= $row_currency['curr_code'];
								$insert_array['pay_curr_symbol']						= $row_currency['curr_sign_char'];
								$insert_array['pay_curr_numeric_code']				= $row_currency['curr_numeric_code'];
								$db->insert_from_array($insert_array,'order_payonaccount_details');
								// Decrementing the used limit for current customer
								$update_cust = "UPDATE 
															customers 
																SET 
																	customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - $remaining_amt  
															WHERE 
																customer_id =".$row_ord['customers_customer_id']." 
																AND sites_site_id = $ecom_siteid 
															LIMIT 
																1";
								$db->query($update_cust);
							}	
						}
						
						$not = trim($note);
						// Inserting the note to the order_notes table
						$insert_array						= array();
						$insert_array['orders_order_id']	= $order_id;
						$insert_array['note_add_date']		= 'now()';
						$insert_array['user_id']			= $_SESSION['console_id'];
						$insert_array['note_text']			= addslashes($not);
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
			}
		}
		if($src=='det')
			return $alert;
		else
		{
			return $succ_cnt;
		}
	}
	
	
	function do_changeorderpaystatus_operation($order_id,$ch_stat,$sel_pay_method,$note,$src='det')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		// Get the current status of current order
			$sql_ord = "SELECT 	order_id,order_custtitle,order_custfname,order_custmname,order_custsurname,
								order_status,order_paystatus,
								order_currency_convertionrate,order_currency_symbol,order_custemail,
								order_subtotal,order_giftwraptotal,order_deliverytotal,order_extrashipping,order_deliveryprice_only,order_tax_total,
								order_customer_discount_value,order_customer_or_corporate_disc,
								order_customer_discount_type,order_customer_discount_percent,order_totalprice,
								order_deposit_amt,order_deposit_amt,gift_vouchers_voucher_id,promotional_code_code_id,
								order_bonuspoint_discount,order_cpc_keyword, order_cpc_se_id, order_cpc_click_id,
								order_cpc_click_pm_id, order_cost_per_click_id 
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
				if ($src!='det')
				{
					$sr_arr = array ('Paid','free','REFUNDED','CANCELLED','DEFERRED','PREAUTH','AUTHENTICATE');
					if(in_array($row_ord['order_paystatus'],$sr_arr))
						return 0;	
				}
				if ($row_ord['order_paystatus']!=$ch_stat)
				{
					$succ_cnt = 1;
					$update_array														= array();
					$update_array['order_paystatus']									= add_slash($ch_stat);
					if ($row_ord['order_status']=='NOT_AUTH' and ($ch_stat=='Paid' or $ch_stat=='Pay_Hold')) // if this is an incomplete order then change the order status to pending while marking the payment as received
					{
						$update_array['order_status']									= 'PENDING';
					}
					$update_array['order_paystatus_changed_manually']					= 1;
					$update_array['order_paystatus_changed_manually_by']				= $_SESSION['console_id'];
					$update_array['order_paystatus_changed_manually_on']				= 'now()';
					if ($ch_stat=='Paid')
						$update_array['order_paystatus_changed_manually_paytype']	= add_slash($sel_pay_method);
					$db->update_from_array($update_array,'orders',array('order_id'=>$order_id));
					if ($ch_stat=='Paid')
					{
						$total_price 	= $row_ord['order_totalprice'];
						$const_ids 		= trim($row_ord['order_cost_per_click_id']);
						// cost per click
						if ($const_ids!='')
						{
							if($ch_stat == 'Paid')
							{
								// Resetting the cost per click id field in orders table to '' to avoid adding multiple times
								$sql_update = "UPDATE orders 
												SET 
													order_cost_per_click_id = ''  
												WHERE 
													order_id = $order_id 
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";	
								$db->query($sql_update);					
								cost_per_click($const_ids,$total_price);
							}	
						}
						
						// Case of hits to sale report
						if($row_ord['order_cpc_click_id'] > 0 && $ch_stat == 'Paid')
						{
							// Resetting the hits to sale ration fields in orders table to 0 to avoid adding multiple times
							$sql_update = "UPDATE orders 
											SET 
												order_cpc_keyword= '',
												order_cpc_se_id=0,
												order_cpc_click_id=0,
												order_cpc_click_pm_id=0 
											WHERE 
												order_id = $order_id 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$db->query($sql_update);
							seo_revenue_report($row_ord,$total_price);
						}
						
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
						
						// If current order is an incomplete order, then do the post order success operations
						if ($row_ord['order_status']=='NOT_AUTH')
						{
							do_pricepromise_handling($order_id,'increment'); // calling function to handle the price promise usage count
							do_PostOrderSuccessOperations($order_id); // calling the function to perform the post order success operations
						}	
						$typ_str = 1;
					}
					elseif($ch_stat =='Pay_Hold')
					{
						$typ_str = 13;
						if ($row_ord['order_status']=='NOT_AUTH')
						{
							do_pricepromise_handling($order_id,'increment'); // calling function to handle the price promise usage count
							do_PostOrderSuccessOperations($order_id); // calling the function to perform the post order success operations
						}
						send_RequiredOrderMails($order_id);	
					}	
					else
						$typ_str = 2;
					// Calling function to save and send payment status change mail
					$row_ord['reason'] = trim($note);
					if ($ch_stat != 'Pay_Hold')
						save_and_send_OrderMail($ch_stat,$row_ord);
					
					$alert = 'Payment Status Changed Successfully';
					// Check whether note is added
					$not = trim($note);
					if($not!='') // case if note exists
					{
						// Inserting the note to the order_notes table
						$insert_array						= array();
						$insert_array['orders_order_id']	= $order_id;
						$insert_array['note_add_date']		= 'now()';
						$insert_array['user_id']			= $_SESSION['console_id'];
						$insert_array['note_text']			= addslashes($not);
						$insert_array['note_type']			= $typ_str;
						$db->insert_from_array($insert_array,'order_notes');
						$alert .= '. Reason added as note';
					}
				}
			}
		if($src=='det')
			return $alert;
		else
		{
			return $succ_cnt;
		}
	}
         function check_order_despatch($order_id)
         {
			 global $db,$ecom_siteid;
            
            // Check whether the order status is to be changed to despatched
            $proceed_change = false;
            // Check whether atleast one product has been despatched in this order
            $sql_orddet = "SELECT orderdet_id 
                            FROM 
                                order_details 
                            WHERE 
                                orders_order_id=$order_id ";
            $ret_orddet = $db->query($sql_orddet);
            if($db->num_rows($ret_orddet))
            {
                while ($row_orddet = $db->fetch_array($ret_orddet))
                {
                    $det_arr[] = $row_orddet['orderdet_id'];
                }
                if(count($det_arr))
                {
                    $det_str = implode(',',$det_arr);
                    // Check whether there exists any entry in order_details_despatched table which are not fully returned after despatch in current order
                    $sql_desp = "SELECT despatched_id 
                                    FROM 
                                        order_details_despatched 
                                    WHERE 
                                        orderdet_id IN ($det_str) 
                                        AND despatched_qty>despatched_returned_qty 
                                    LIMIT 
                                        1";
                    $ret_desp = $db->query($sql_desp);
                    if ($db->num_rows($ret_desp))
                        $proceed_change = true;
                }
            }
            if($proceed_change==true)
            {
                // Check whether there exists any items in back order for current order
                $sql_back = "SELECT order_backorder_id 
                                FROM 
                                        order_details a,order_details_backorder b 
                                WHERE 
                                        a.orders_order_id = $order_id 
                                        AND a.orderdet_id = b.orderdet_id 
                                LIMIT 
                                        1";
                $ret_back = $db->query($sql_back);
                if($db->num_rows($ret_back))
                {
                        $prod_remains = true;
                }
                if($prod_remains==false)
                {
                    // Check whether there remain any items in order_details with order_qty >0
                    $sql_check = "SELECT orderdet_id 
                                    FROM 
                                            order_details 
                                    WHERE 
                                            orders_order_id = $order_id 
                                            AND order_qty>0 
                                    LIMIT 
                                            1";
                    $ret_check = $db->query($sql_check);
                    if ($db->num_rows($ret_check)==0)
                    {                       
                        $sql_update = "UPDATE 
                                                orders
                                        SET 
                                                order_status = 'DESPATCHED'  
                                        WHERE 
                                                order_id = $order_id 
                                                AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                        1";
                        $db->query($sql_update);                
                        $completly_despatched = true;                           
                    }
                }
            }    
         }
		 function do_pricepromise_handling($order_id,$operation)
		 {
		 	global $db,$ecom_siteid;
		 	// Check whether there exists any products in current order linked with price promise
			$sql_orderdet = "SELECT  orderdet_id, order_prom_id 
								FROM 
									order_details 
								WHERE 
									orders_order_id = $order_id 
									AND order_prom_id <> 0";
			$ret_orderdet = $db->query($sql_orderdet);
			if($db->num_rows($ret_orderdet))
			{
				while ($row_orderdet = $db->fetch_array($ret_orderdet))
				{
					$cur_promid = $row_orderdet['order_prom_id'];
					// get the used count of current price promise entry
					$sql_price = "SELECT  prom_used,  prom_max_usage 
									FROM 
										pricepromise 
									WHERE 
										prom_id = $cur_promid 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_price = $db->query($sql_price);
					if ($db->num_rows($ret_price))
					{
						$row_price = $db->fetch_array($ret_price);
						if($operation == 'decrement')
						{
							if($row_price['prom_used']>0)
							{
								// Decrementing the price promise usage count by 1
								$sql_update = "UPDATE pricepromise 
													SET 
														prom_used = prom_used-1 
													WHERE 
														prom_id = $cur_promid 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
								$db->query($sql_update);
							}	
						}
						elseif($operation == 'increment')
						{
							if($row_price['prom_used']<$row_price['prom_max_usage'])
							{
								// Incrementing the price promise usage count by 1
								$sql_update = "UPDATE pricepromise 
													SET 
														prom_used = prom_used+1 
													WHERE 
														prom_id = $cur_promid 
														AND sites_site_id = $ecom_siteid 
													LIMIT 
														1";
								$db->query($sql_update);
							}	
						}	
					}
				}
			}
		 }

function move_archive($order_id,$siteid,$mov)
{
	global $db;
	$sql_ord = "SELECT * FROM orders WHERE order_id = $order_id AND sites_site_id = $siteid LIMIT 1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
		// Check whether an entry already exists in the archive table
		$sql_check = "SELECT order_id FROM orders_archieve WHERE order_id = $order_id and sites_site_id = $siteid LIMIT 1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check)==0)
		{
			$insert_array												= array();
			$insert_array['order_id']									= addslashes(stripslashes($row_ord['order_id']));
			$insert_array['customers_customer_id']						= addslashes(stripslashes($row_ord['customers_customer_id']));
			$insert_array['sites_site_id']								= addslashes(stripslashes($row_ord['sites_site_id']));
			$insert_array['sites_shops_shop_id']						= addslashes(stripslashes($row_ord['sites_shops_shop_id']));
			$insert_array['order_date']									= addslashes(stripslashes($row_ord['order_date']));
			$insert_array['order_custtitle']							= addslashes(stripslashes($row_ord['order_custtitle']));
			$insert_array['order_custfname']							= addslashes(stripslashes($row_ord['order_custfname']));
			$insert_array['order_custmname']							= addslashes(stripslashes($row_ord['order_custmname']));
			$insert_array['order_custsurname']							= addslashes(stripslashes($row_ord['order_custsurname']));
			$insert_array['order_custcompany']							= addslashes(stripslashes($row_ord['order_custcompany']));
			$insert_array['order_buildingnumber']						= addslashes(stripslashes($row_ord['order_buildingnumber']));
			$insert_array['order_street']								= addslashes(stripslashes($row_ord['order_street']));
			$insert_array['order_city']									= addslashes(stripslashes($row_ord['order_city']));
			$insert_array['order_state']								= addslashes(stripslashes($row_ord['order_state']));
			$insert_array['order_country']								= addslashes(stripslashes($row_ord['order_country']));
			$insert_array['order_custpostcode']							= addslashes(stripslashes($row_ord['order_custpostcode']));
			$insert_array['order_custphone']							= addslashes(stripslashes($row_ord['order_custphone']));
			$insert_array['order_custfax']								= addslashes(stripslashes($row_ord['order_custfax']));
			$insert_array['order_custmobile']							= addslashes(stripslashes($row_ord['order_custmobile']));
			$insert_array['order_custemail']							= addslashes(stripslashes($row_ord['order_custemail']));
			$insert_array['order_notes']								= addslashes(stripslashes($row_ord['order_notes']));
			$insert_array['order_giftwrap']								= addslashes(stripslashes($row_ord['order_giftwrap']));
			$insert_array['order_giftwrap_per']							= addslashes(stripslashes($row_ord['order_giftwrap_per']));
			$insert_array['order_giftwrapmessage']						= addslashes(stripslashes($row_ord['order_giftwrapmessage']));
			$insert_array['order_giftwrapmessage_text']					= addslashes(stripslashes($row_ord['order_giftwrapmessage_text']));
			$insert_array['order_giftwrap_message_charge']				= addslashes(stripslashes($row_ord['order_giftwrap_message_charge']));
			$insert_array['order_giftwrap_minprice']					= addslashes(stripslashes($row_ord['order_giftwrap_minprice']));
			$insert_array['order_giftwraptotal']						= addslashes(stripslashes($row_ord['order_giftwraptotal']));
			$insert_array['order_deliverytype']							= addslashes(stripslashes($row_ord['order_deliverytype']));
			$insert_array['order_deliverylocation']						= addslashes(stripslashes($row_ord['order_deliverylocation']));
			$insert_array['order_delivery_date']						= addslashes(stripslashes($row_ord['order_delivery_date']));
			$insert_array['order_delivery_time']						= addslashes(stripslashes($row_ord['order_delivery_time']));
			$insert_array['order_delivery_option']						= addslashes(stripslashes($row_ord['order_delivery_option']));
			$insert_array['order_deliveryprice_only']					= addslashes(stripslashes($row_ord['order_deliveryprice_only']));
			$insert_array['order_deliverytotal']						= addslashes(stripslashes($row_ord['order_deliverytotal']));
			$insert_array['order_freedeliverytype']						= addslashes(stripslashes($row_ord['order_freedeliverytype']));
			$insert_array['order_splitdeliveryreq']						= addslashes(stripslashes($row_ord['order_splitdeliveryreq']));
			$insert_array['order_extrashipping']						= addslashes(stripslashes($row_ord['order_extrashipping']));
			$insert_array['order_bonusrate']							= addslashes(stripslashes($row_ord['order_bonusrate']));
			$insert_array['order_bonuspoint_discount']					= addslashes(stripslashes($row_ord['order_bonuspoint_discount']));
			$insert_array['order_bonuspoints_used']						= addslashes(stripslashes($row_ord['order_bonuspoints_used']));
			$insert_array['order_bonuspoint_inorder']					= addslashes(stripslashes($row_ord['order_bonuspoint_inorder']));
			$insert_array['order_bonuspoints_donated']					= addslashes(stripslashes($row_ord['order_bonuspoints_donated']));
			$insert_array['order_paymenttype']							= addslashes(stripslashes($row_ord['order_paymenttype']));
			$insert_array['order_paymentmethod']						= addslashes(stripslashes($row_ord['order_paymentmethod']));
			$insert_array['order_paystatus']							= addslashes(stripslashes($row_ord['order_paystatus']));
			$insert_array['order_paystatus_changed_manually']			= addslashes(stripslashes($row_ord['order_paystatus_changed_manually']));
			$insert_array['order_paystatus_changed_manually_by']		= addslashes(stripslashes($row_ord['order_paystatus_changed_manually_by']));
			$insert_array['order_paystatus_changed_manually_on']		= addslashes(stripslashes($row_ord['order_paystatus_changed_manually_on']));
			$insert_array['order_paystatus_changed_manually_paytype']	= addslashes(stripslashes($row_ord['order_paystatus_changed_manually_paytype']));
			$insert_array['order_hide']									= addslashes(stripslashes($row_ord['order_hide']));
			$insert_array['order_status']								= addslashes(stripslashes($row_ord['order_status']));
			$insert_array['order_cancelled_by']							= addslashes(stripslashes($row_ord['order_cancelled_by']));
			$insert_array['order_cancelled_from']						= addslashes(stripslashes($row_ord['order_cancelled_from']));
			$insert_array['order_cancelled_on']							= addslashes(stripslashes($row_ord['order_cancelled_on']));
			$insert_array['order_refundamt']							= addslashes(stripslashes($row_ord['order_refundamt']));
			$insert_array['order_refundcomp_date']						= addslashes(stripslashes($row_ord['order_refundcomp_date']));
			$insert_array['order_deposit_amt']							= addslashes(stripslashes($row_ord['order_deposit_amt']));
			$insert_array['order_deposit_cleared']						= addslashes(stripslashes($row_ord['order_deposit_cleared']));
			$insert_array['order_deposit_cleared_on']					= addslashes(stripslashes($row_ord['order_deposit_cleared_on']));
			$insert_array['order_deposit_cleared_by']					= addslashes(stripslashes($row_ord['order_deposit_cleared_by']));
			$insert_array['order_currency_code']						= addslashes(stripslashes($row_ord['order_currency_code']));
			$insert_array['order_currency_numeric_code']				= addslashes(stripslashes($row_ord['order_currency_numeric_code']));
			$insert_array['order_currency_symbol']						= addslashes(stripslashes($row_ord['order_currency_symbol']));
			$insert_array['order_currency_convertionrate']				= addslashes(stripslashes($row_ord['order_currency_convertionrate']));
			$insert_array['order_tax_total']							= addslashes(stripslashes($row_ord['order_tax_total']));
			$insert_array['order_tax_to_delivery']						= addslashes(stripslashes($row_ord['order_tax_to_delivery']));
			$insert_array['order_tax_to_giftwrap']						= addslashes(stripslashes($row_ord['order_tax_to_giftwrap']));
			$insert_array['order_customer_or_corporate_disc']			= addslashes(stripslashes($row_ord['order_customer_or_corporate_disc']));
			$insert_array['order_customer_discount_type']				= addslashes(stripslashes($row_ord['order_customer_discount_type']));
			$insert_array['order_customer_discount_percent']			= addslashes(stripslashes($row_ord['order_customer_discount_percent']));
			$insert_array['order_customer_discount_value']				= addslashes(stripslashes($row_ord['order_customer_discount_value']));
			$insert_array['order_totalprice']							= addslashes(stripslashes($row_ord['order_totalprice']));
			$insert_array['order_totalauthorizeamt']					= addslashes(stripslashes($row_ord['order_totalauthorizeamt']));
			$insert_array['order_subtotal']								= addslashes(stripslashes($row_ord['order_subtotal']));
			$insert_array['order_pre_order']							= addslashes(stripslashes($row_ord['order_pre_order']));
			$insert_array['gift_vouchers_voucher_id']					= addslashes(stripslashes($row_ord['gift_vouchers_voucher_id']));
			$insert_array['order_gift_voucher_number']					= addslashes(stripslashes($row_ord['order_gift_voucher_number']));
			$insert_array['promotional_code_code_id']					= addslashes(stripslashes($row_ord['promotional_code_code_id']));
			$insert_array['promotional_code_code_number']				= addslashes(stripslashes($row_ord['promotional_code_code_number']));
			$insert_array['order_able2buy_cgid']						= addslashes(stripslashes($row_ord['order_able2buy_cgid']));
			$insert_array['costperclick_id']							= addslashes(stripslashes($row_ord['costperclick_id']));
			$insert_array['order_despatched_completly_on']				= addslashes(stripslashes($row_ord['order_despatched_completly_on']));
			$insert_array['order_cpc_keyword']							= addslashes(stripslashes($row_ord['order_cpc_keyword']));
			$insert_array['order_cpc_se_id']							= addslashes(stripslashes($row_ord['order_cpc_se_id']));
			$insert_array['order_cpc_click_id']							= addslashes(stripslashes($row_ord['order_cpc_click_id']));
			$insert_array['order_cpc_click_pm_id']						= addslashes(stripslashes($row_ord['order_cpc_click_pm_id']));
			$insert_array['order_cost_per_click_id']					= addslashes(stripslashes($row_ord['order_cost_per_click_id']));
			$insert_array['order_delivery_applytax']					= addslashes(stripslashes($row_ord['order_delivery_applytax']));
			$insert_array['order_specialtax_calculation']				= addslashes(stripslashes($row_ord['order_specialtax_calculation']));
			$insert_array['order_specialtax_totalamt']					= addslashes(stripslashes($row_ord['order_specialtax_totalamt']));
			$insert_array['order_specialtax_productamt']				= addslashes(stripslashes($row_ord['order_specialtax_productamt']));
			$insert_array['order_specialtax_deliveryamt']				= addslashes(stripslashes($row_ord['order_specialtax_deliveryamt']));
			$insert_array['order_specialtax_extrashippingamt']			= addslashes(stripslashes($row_ord['order_specialtax_extrashippingamt']));
			$insert_array['order_specialtax_orgtotalamt']				= addslashes(stripslashes($row_ord['order_specialtax_orgtotalamt']));
			$insert_array['order_specialtax_orgproductamt']				= addslashes(stripslashes($row_ord['order_specialtax_orgproductamt']));
			$insert_array['order_specialtax_orgdeliveryamt']			= addslashes(stripslashes($row_ord['order_specialtax_orgdeliveryamt']));
			$insert_array['order_specialtax_orgextrashippingamt']		= addslashes(stripslashes($row_ord['order_specialtax_orgextrashippingamt']));
			$insert_array['order_source']								= addslashes(stripslashes($row_ord['order_source']));
			$insert_array['order_steamdesk_exported']					= addslashes(stripslashes($row_ord['order_steamdesk_exported']));
			$insert_array['order_placed_from']							= addslashes(stripslashes($row_ord['order_placed_from']));
			$insert_array['product_reviewmail_sent']					= addslashes(stripslashes($row_ord['product_reviewmail_sent']));
			$db->insert_from_array($insert_array,'orders_archieve');
			
			$sql_del = "DELETE FROM orders WHERE order_id = $order_id AND sites_site_id = $siteid LIMIT 1";
			$db->query($sql_del);
		}
	}
}


?>