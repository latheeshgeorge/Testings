<?php
include_once("../../../functions/functions.php");
include_once('../../../session.php');
include_once("../../../config.php");
if($_REQUEST['ajax_fpurpose']=='show_bulk_despatch')
{
 	$orders_arr = explode('~',$_REQUEST['bulk_desp_id']);
 	$orders_str = implode(',',$orders_arr);
	// Get the list of all selected orders from the order table
	$sql_order = "SELECT order_id,order_paystatus,order_status FROM orders WHERE sites_site_id = $ecom_siteid AND order_id IN ($orders_str) ORDER BY order_date desc";
	$ret_order = $db->query($sql_order);
	?>
	<div class="p_close"><a href="javascript:void(0)" onclick="close_ajax_div()"><img src="images/close.png" width="47" height="45" border="0" /></a></div>
	<div class="p_main">
	<div class="p_close_inner"><input name="Despatch_bulk_button" id="Despatch_bulk_button" type="button" value="Despatch" onclick="handle_bulkdespatch_form()" /></div>
	<div class="p_content_otr">
	<?php
	$cnt = 1;
	$total_selected = 0;
	$valid_selected = 0;
	while($row_order = $db->fetch_array($ret_order))
	{
		$total_selected++;
		$show_msg = '';
		$show_details = false;
		if ($row_order['order_status']!='CANCELLED' ) // Order and payment status can be changed only if order status is not cancelled
		{
			if ($row_order['order_status']!='DESPATCHED')
			{
				if($row_order['order_paystatus']=='Paid' or $row_order['order_paystatus']=='Pay_Hold' or $row_order['order_paystatus']=='free') // show the despatch option only if payment is successfull
				{
					$show_details = true;
					$valid_selected++;
				}
			}
		}		
		
		
		//if($show_details)
		{
			
			if($cnt%2!=0)
			{
		?>
			<div class="p_content_inner">
			<?php
			}
		
		if($show_details)
		{
		?>
		<form method="post" name="bulkdespatch_form_<?php echo $row_order['order_id']?>">
		<input type="hidden" name="despatch_orderid_<?php echo $row_order['order_id']?>" id="despatch_orderid_<?php echo $row_order['order_id']?>" value="<?php echo $row_order['order_id']?>" />
		<?php
		}
		?>
		<div class="<?php echo ($cnt%2==0)?'p_content_r':'p_content_l'?>">
		<div class="p_content_con"><a name="Details_<?php echo $row_order['order_id']?>">
		
		<div class="p_content_id">Order ID : <?php echo $row_order['order_id'];?></div>
		<div class="p_error_msg" style="color:#FF0000;font-weight:bold;text-align:center;display:none" id="p_err_msg_<?php echo $row_order['order_id'];?>"><img src="images/ajax-loader.gif" alt="Loading..." width="47" height="15" border="0" /></div>
		<?php
		if($show_details)
		{
		?>
		<div id="lower_container_<?php echo $row_order['order_id'];?>">
		<div class="p_content_ref">Despatch Reference Number (optional) </div>
		<div class="p_content_txt"> <input name="refno_<?php echo $row_order['order_id']?>" id="refno_<?php echo $row_order['order_id']?>" type="text" /></div>
		<div class="p_content_ref">Expected Delivery Date (dd / mm / yy)</div>
		<div class="p_content_txt"> <input name="date_<?php echo $row_order['order_id']?>" id="date_<?php echo $row_order['order_id']?>" type="text" value="<?php echo date('d-m-Y')?>" readonly="readonly"/>
		<a href="javascript:show_calendar('bulkdespatch_form_<?php echo $row_order['order_id']?>.date_<?php echo $row_order['order_id']?>');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" height="22" border="0" width="24"></a>
		</div>
		<div class="p_content_note">Additional Note (optional)</div>
		<div class="p_content_txt"> <textarea name="addnote_<?php echo $row_order['order_id']?>" id="addnote_<?php echo $row_order['order_id']?>" cols="" rows=""></textarea>
		<em>Note, if specified, will be automatically added to the notes section</em>
		</div>
		</div>
		<?php
		}
		else
		{
		?>
		<div class="p_content_txt">
		<center>
		<?php 
		if($row_order['order_status']=='DESPATCHED')
			$msg = '<span style="color:#FF0000;">Sorry!! This order has been already despatched</span>';
		else
			$msg = '<span style="color:#FF0000;">Sorry!! This order cannot be despatched</span>';
			
			echo $msg;
		?>	
		</center>
		</div>
		<?php
		}
		?>
		</div>
		<?php
		if($show_details)
		{
		?>
		</form>
		<?php
		}
		if($cnt%2==0)
		{
		?>
		</div>
		<?php
		}
		?>
	</div>
	<?php
			$cnt++;
		}
	}
	?>
	<input type="hidden" name="valid_despatch_total_count" id="valid_despatch_total_count" value="<?php echo $valid_selected?>" />
	</div>
	</div>
<?php
}
elseif($_REQUEST['ajax_fpurpose']=='do_bulk_despatch')
{
	$cur_refno 		= trim($_REQUEST['refno']);//echo "Despatched Successfully";
	$cur_expdate 	= trim($_REQUEST['expdate']);
	$cur_addnote	= trim($_REQUEST['addnote']);
	$cur_ordid		= trim($_REQUEST['ordid']);
	$curconsoleuser	= trim($_REQUEST['consoleuser']);
	//$det_arr 				= explode("~",$_REQUEST['id_str']);
	$order_id				= $cur_ordid;
	$prod_remains			= false;
	$despatch_note			= $cur_addnote;
	$despatch_id			= $cur_refno;
	$exp_delivery_date		= $cur_expdate;
	$completly_despatched 	= false;
	

	// Get the qty remaining for current item in order details table
	$sql_orderdet = "SELECT orderdet_id,order_qty,products_product_id 
						FROM 
							order_details 
						WHERE 
							orders_order_id = ".$order_id." 
							AND order_qty > 0";
	$ret_orderdet = $db->query($sql_orderdet);
	if($db->num_rows($ret_orderdet))
	{
		while($row_orderdet = $db->fetch_array($ret_orderdet))
		{
			if ($row_orderdet['order_qty']>0)
			{
				// Inserting a record to the order details details table to track the despatches
				$atleast_one 							= true;
				$insert_array						= array();
				$insert_array['orderdet_id']		= $row_orderdet['orderdet_id'];
				$insert_array['despatched_qty']		= $row_orderdet['order_qty'];
				$insert_array['despatched_on']		= 'now()';
				$insert_array['despatched_by']		= $curconsoleuser;
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
												orderdet_id =".$row_orderdet['orderdet_id']." 
											LIMIT 
												1";
				$db->query($sql_update);
				
				$despatchid_arr[] 					= $row_orderdet['orderdet_id'];
				$despatchqty_arr[$row_orderdet['orderdet_id']] 		= $row_orderdet['order_qty'];
				
				// Check whether any qty exists for current product in back order for current order
				$sql_back = "SELECT order_backorder_id 
										FROM 
											order_details_backorder 
										WHERE 
											orderdet_id = ".$row_orderdet['orderdet_id']." 
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
											orderdet_id=".$row_orderdet['orderdet_id']."  
											AND order_qty=0 
										LIMIT 
											1";
						$db->query($sql_update);
				}	
			}
		}
		$alert = 'Despatched Successfully';
	}
	else
	{
		$alert = "Sorry!! No Products to Despatch";
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
			$alert .= '. <br><br>Additional note saved in notes section';
		}
			// Saving and sending mail over here
			$ord_arr['order_id']				= $order_id;
			$ord_arr['despatch_id']				= $despatch_id; 
			$ord_arr['despatch_note']			= $despatch_note; 
			$ord_arr['despatched_prods'] 		= $despatchid_arr;
			$ord_arr['despatched_qtys'] 		= $despatchqty_arr;
			$ord_arr['completly_despatched']	= $completly_despatched;
			$ord_arr['despatched_delivery_date']= $exp_delivery_date;
			save_and_send_OrderMail('DESPATCHED',$ord_arr);
			// calling function to recalculate the valid quantity and special tax related field values in orders and order_details table	
			handle_recalculate_specialtax_calculation($order_id);
			echo $alert;
}
?>