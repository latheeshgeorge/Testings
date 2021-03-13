<?php
/*#################################################################
# Script Name 		: order_details.php
# Description 		: Page for showing the details of selected orders
# Coded by 			: Sny
# Created on		: 21-Apr-2008
# Modified by		: Sny
# Modified On		: 03-Oct-2008
#################################################################*/
//#Define constants for this page
$page_type 	= 'Order Details';
$help_msg 	= get_help_messages('ORD_DET_MAIN_MSG');
$edit_id	= $_REQUEST['checkbox'][0];
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'summary_tab_td';
if($edit_id) // Get the details of selected order
{
	// Update the status of order to 'PENDING' if the status is NEW
	$sql_update = "UPDATE orders
					SET
						order_status ='PENDING'
					WHERE
						order_id = $edit_id
						AND order_status ='NEW'
					LIMIT
						1";
	$db->query($sql_update);

	// Get the details of current order
 	$sql_ord = "SELECT order_id,customers_customer_id,sites_site_id,sites_shops_shop_id,order_date,order_custtitle,order_custfname,
 						order_custmname,order_custsurname,order_custcompany,order_buildingnumber,order_street,order_city,order_state,
 						order_country,order_custpostcode,order_custphone,order_custfax,order_custmobile,order_custemail,order_notes,
 						order_giftwrap,order_giftwrap_per,order_giftwrapmessage,order_giftwrapmessage_text,order_giftwrap_message_charge,
 						order_giftwrap_minprice,order_giftwraptotal,order_deliverytype,order_deliverylocation,order_delivery_option,
 						order_deliveryprice_only,order_deliverytotal,order_splitdeliveryreq,order_extrashipping,order_bonusrate,
 						order_bonuspoint_discount,order_bonuspoints_used,order_bonuspoint_inorder,order_paymenttype,order_paymentmethod,
 						order_paystatus,order_paystatus_changed_manually,order_paystatus_changed_manually_by,order_paystatus_changed_manually_on,
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
						order_id=".$edit_id."
						AND sites_site_id = $ecom_siteid
					LIMIT
						1";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	else // case if not record found
	{
		echo "Sorry Invalid Input";
		exit;
	}
}
$show_custom = false;//this section decides for the custom email to customer from order details page
if($ecom_siteid==104 || $ecom_siteid==113 || $ecom_siteid==100)
{
	$show_custom = true;
}
if($show_custom == true)
{
?>							
<!-- Trigger/Open The Modal -->

<!-- The Modal -->
<div id="myModal" class="modal" style="display:none;">

  <!-- Modal content -->
  <div class="modal-content" >
    <span class="close">&times;</span>
    <div id="email_cust_success"></div>

    <div id="whole_content">
    <p id="email_cust_subjectP"><div class="subcaption_modal" >Subject :</div><div class="subcaption_rt"><input type="text" size="100" name="email_cust_subject" id="email_cust_subject"> </div></p>
    <p id="email_cust_contentP"><div class="subcaption_modal" > Content :</div><div class="subcaption_rt"><textarea cols="100" rows="10" id="email_cust_content" name="email_cust_content"></textarea></div></p>
    <p><input type="button" name="email_cust_submit" onclick="send_mail_cust(<?php echo stripslashes($row_ord['order_id']);?>)" value="Send Mail" class="red"></p>
    </div>
  </div>

</div>
<?php
}
?>
							
<div id="moveto_returnorder_div" class="processing_divcls_big_width" style="display:none">
</div>
<div id="moveto_backorder_div" class="processing_divcls_big_height" style="display:none">
</div>
<div id="moveto_cancel_div" class="processing_divcls_main" style="display:none">
  <?php /*?><table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td colspan="4" align="left" class="shoppingcartheader">Please select the quantity of items to be Cancelled </td>
    </tr>
    <tr>
      <td width="6%" align="center" class="listingtableheader">#</td>
      <td width="57%" align="left" class="listingtableheader">Product</td>
      <td width="18%" align="center" class="listingtableheader"> Qty in Order </td>
      <td width="19%" align="center" class="listingtableheader">Qty to be Ca </td>
    </tr>
    <tr>
      <td align="center" valign="top"  class="listingtablestyleB">1.</td>
      <td align="left" class="listingtablestyleB"><a href="#" title="View Product Details" class="edittextlink">HP Photosmart C4100 All-in-One series</a>
          <table border="0" cellpadding="1" cellspacing="1" width="100%">
            <tr>
              <td align="left" valign="top" width="34%"> Size: 1<br />
                Color: Red </td>
              <td valign="top" width="66%"></td>
            </tr>
        </table></td>
      <td align="center" class="listingtablestyleB">1</td>
      <td align="center" class="listingtablestyleB">        <select name="select2">
          <option value="1">1</option>
                </select>      </td>
    </tr>
    <tr>
      <td colspan="4" align="center" class="redtext"><strong>Note:</strong> This action is not reversible</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><input name="Submit3" type="button" class="red" value="Cancel" onclick="document.getElementById('moveto_cancel_div').style.display='none'" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="Button" type="button" class="red" value="Done" onclick="call_ajax_showlistall('movetobackorder_do',0)" /></td>
    </tr>
  </table><?php */?>
</div>
<script language="javascript" type="text/javascript">
function nl2br(text)
{
	 return text.replace("\n","<br />");
}
function show_invoicepopup(invid)
{
	var hostname = '<?php echo $ecom_hostname?>'
	win_name = window.open('includes/orders/showorder_invioce.php?f='+invid,'order_invoice', 'top=0, left=0, menubar=0, resizable=1, scrollbars=1, toolbar=0,width=750,height=550');
	win_name.focus();
}
function show_receiptpopup(order_id)
{
	var hostname = '<?php echo $ecom_hostname?>'
	win_name = window.open('includes/orders/showorder_receipt.php?f='+order_id,'order_receipts', 'top=0, left=0, menubar=0, resizable=1, scrollbars=1, toolbar=0,width=950,height=600');
	win_name.focus();
}
function show_canceldiv()
{
	var j = 0;
	for(i=0;i<document.frmOrderDetails.elements.length;i++)
	{
		if (document.frmOrderDetails.elements[i].name=='checkboxprod[]')
		{
			if (j==1)
				document.frmOrderDetails.elements[i].checked = true;
			else
				document.frmOrderDetails.elements[i].checked = false;
			j++;
		}	
	}
	document.getElementById('moveto_cancel_div').style.top = getTop(150)+'px';
	document.getElementById('moveto_cancel_div').style.display = '';
}
function ajax_return_contents()
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/*if(document.getElementById('req_change_ordstat').value==1)
			{
				document.getElementById('orderstatus_maindiv').innerHTML = document.getElementById('pass_change_stat').value;
				document.getElementById('req_change_ordstat').value = '';
			}
			if(document.getElementById('req_change_ordpaystat').value==1)
			{
				document.getElementById('paymentstatus_maindiv').innerHTML = document.getElementById('pass_change_paystat').value;
				document.getElementById('req_change_ordpaystat').value = '';
			}
			if (document.getElementById('req_release_amt').value==1)
			{
				document.getElementById('req_release_amt').value = '';
				document.getElementById('productdeposit_div').innerHTML = document.getElementById('pass_productdeposit_release').value;
			}
			if (document.getElementById('req_change_ordstat').value==1 || document.getElementById('req_change_ordpaystat').value==1)
				call_ajax_showlistall('notes');  /* calling the section to reload the notes area also*/

			if(document.getElementById('retdiv_id'))
				document.getElementById('retdiv_id').value = 'ordermain_div';
			
			if(document.getElementById('txt_expected_delivery_date'))	
				show_date_field();		
			hide_processing();
		}
		else
		{
			show_request_alert(req.status);
		}
	}
}
function handle_tabs(id,mod,dont_hide)
{
	<?php 
	$cust_emailtab = '';
	if($show_custom == true)
	{
		$cust_emailtab =",'other_custtab_td'";
	}
	
	?>
	tab_arr 									= new Array('summary_tab_td','payment_tab_td','despatch_tab_td','refund_tab_td','returns_tab_td','notes_tab_td','query_tab_td','other_tab_td'<?php echo $cust_emailtab ?>);
	var atleastone 						= 0;
	var order_id							= '<?php echo $edit_id?>';
	var cat_orders						= '';
	var fpurpose							= '';
	var retdivid								= '';
	var moredivid							= '';
	<?php /*?>var ord_status						= '<?php echo ($_REQUEST['ord_status'])?$_REQUEST['ord_status']:$_REQUEST['ser_ord_status']?>';
	var ord_email							= '<?php echo ($_REQUEST['ord_email'])?$_REQUEST['ord_email']:$_REQUEST['ser_ord_email']?>';
	var ord_fromdate					= '<?php echo ($_REQUEST['ord_fromdate'])?$_REQUEST['ord_fromdate']:$_REQUEST['ser_ord_fromdate']?>';
	var ord_todate						= '<?php echo ($_REQUEST['ord_todate'])?$_REQUEST['ord_todate']:$_REQUEST['ser_ord_todate']?>';
	var ord_stores						= '<?php echo ($_REQUEST['ord_stores'])?$_REQUEST['ord_stores']:$_REQUEST['ser_ord_stores']?>';
	
	var sortby								= '<?php echo ($_REQUEST['ord_sort_by'])?$_REQUEST['ord_sort_by']:$_REQUEST['ser_ord_sort_by']?>';
	var sortorder							= '<?php echo ($_REQUEST['ord_sort_order'])?$_REQUEST['ord_sort_order']:$_REQUEST['ser_ord_sort_order']?>';
	var recs									= '<?php echo ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:$_REQUEST['records_per_page']?>';
	var start								= '<?php echo ($_REQUEST['start'])?$_REQUEST['start']:$_REQUEST['ser_start']?>';
	var pg									= '<?php echo ($_REQUEST['pg'])?$_REQUEST['pg']:$_REQUEST['ser_pg']?>';
	var curtab								= '<?php echo $curtab?>';<?php */?>
	var qrystr								= '';/*ord_status='+ord_status+'&ord_email='+ord_email+'&ord_fromdate='+ord_fromdate+'&ord_todate='+ord_todate+'&ord_stores='+ord_stores+'&ord_sort_by='+sortby+'&ord_sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&curtab='+curtab;*/
	if(document.getElementById('mainerror_div') && dont_hide==0)
		document.getElementById('mainerror_div').style.display = 'none';
	for(i=0;i<tab_arr.length;i++)
	{
		if(tab_arr[i]!=id)
		{
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			obj.className = 'toptab';
		}
	}
	document.getElementById('selected_tab').value = id;
	<?php /*?>if (id=='payment_tab_td')
	{
		if(document.getElementById('paystatus1_div'))
		{
			document.getElementById('paystatus1_div').style.display = '';
			document.getElementById('paystatus2_div').style.display = '';
		}
	}
	else
	{
		if(document.getElementById('paystatus1_div'))
		{
			document.getElementById('paystatus1_div').style.display = 'none';
			document.getElementById('paystatus2_div').style.display = 'none';
		}
	}<?php */?>
	if(document.getElementById('additionaldet_div'))
		document.getElementById('additionaldet_div').innerHTML='';
	if(document.getElementById('cbo_orderstatus'))
		document.getElementById('cbo_orderstatus').value='';	
	if(document.getElementById('cbo_orderpaystatus'))
		document.getElementById('cbo_orderpaystatus').value='';	
		
		
		
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	
	switch(mod)
	{
		case 'order_summary':
			fpurpose ='show_order_summary';
		break;
		case 'order_payment':
			fpurpose ='show_order_payment';
		break;
		case 'order_despatch':
			fpurpose ='show_order_despatch';
		break;
		case 'order_refund':
			fpurpose ='show_order_refund';
		break;
		case 'order_return':
			fpurpose ='show_order_return';
		break;
		case 'order_notes':
			fpurpose ='show_order_notes';
		break;
		case 'order_custquery':
			fpurpose ='show_order_custquery';
		break;
		case 'order_other':
			fpurpose ='show_order_other';
		break;
		case 'order_cust_mail':
			fpurpose ='show_order_other_cust_mail';
		break;
	};
	retobj 										= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/orders.php','fpurpose='+fpurpose+'&order_id='+order_id+'&'+qrystr);	
} 
function call_ajax_showlistall(mod,unhide_errdiv)
{
	var atleastone 									= 0;
	var ord_id											= '<?php echo $edit_id;?>';
	var cat_orders									= '';
	var fpurpose										= '';
	var retdivid											= '';
	var moredivid										= '';
	var qrystr											= '';
	var curtab											= document.getElementById('selected_tab').value;
	/*
		section to handle the div in case of payment capture type
	*/
		if (document.getElementById('capturetypereleasemain_div'))
		{
			document.getElementById('capturetypereleasemain_div').style.display = '';
		}
		if (document.getElementById('capturetypeauthorisemain_div'))
		{
			document.getElementById('capturetypeauthorisemain_div').style.display = '';
		}
		if (document.getElementById('capturetyperepeatmain_div'))
		{
			document.getElementById('capturetyperepeatmain_div').style.display = '';
		}
		if (document.getElementById('capturetypeabortmain_div'))
		{
			document.getElementById('capturetypeabortmain_div').style.display = '';
		}
		if (document.getElementById('capturetypecancelmain_div'))
		{
			document.getElementById('capturetypecancelmain_div').style.display = '';
		}
	/*
		end here
	*/
	if(document.getElementById('mainerror_div') && unhide_errdiv==0)
		document.getElementById('mainerror_div').style.display = 'none';

	/*if(document.getElementById('despatchmain_div'))
		document.getElementById('despatchmain_div').style.display='';
	if(document.getElementById('refundtop_div'))
	{
		document.getElementById('refundtop_div').style.display='';

	}*/
	switch(mod)
	{
		/*case 'bill': // Case of billing details
			retdivid   	= 'bill_div';
			fpurpose	= 'list_billing_address';
		break;
		case 'delivery': // case of delivery details
			retdivid   	= 'delivery_div';
			fpurpose	= 'list_delivery_address';
		break;*/
		case 'gift_wrap': // case of gift wrap details
			retdivid   	= 'gift_div';
			fpurpose	= 'list_giftwrap_details';
		break;
		case 'tax':			// case of tax details
			retdivid   	= 'tax_div';
			fpurpose	= 'list_tax_details';
		break;
		case 'voucher':		// case of voucher details
			retdivid   	= 'voucher_div';
			fpurpose	= 'list_voucher_details';
		break;
		case 'promotional': // case of promotional details
			retdivid   	= 'prom_div';
			fpurpose	= 'list_promotional_details';
		break;
		/*case 'payment':		// case of payment details
			retdivid   	= 'payment_div';
			fpurpose	= 'list_payment_details';
		break;*/
		case 'notes':		// case of order notes
			retdivid   	= 'note_div';
			fpurpose	= 'list_notes';
		break;
		/*case 'operation':	// case of operations on orders
			retdivid   	= 'operation_div';
			fpurpose	= 'list_operations';
		break;*/
		case 'savenote':	// case of saving order notes
			retdivid   	= 'note_div';
			fpurpose	= 'save_note';
			var note	= document.frmOrderDetails.txt_notes.value;
			qrystr		= 'note='+encodeURIComponent(note);
		break;
		case 'deletenote': 	// case of deleting order notes
			retdivid   	= 'note_div';
			fpurpose	= 'delete_note';
			var noteid	= document.frmOrderDetails.del_note_id.value
			qrystr		= 'noteid='+noteid;
		break;
		case 'emails': 	// case of order emails
			retdivid   	= 'email_div';
			fpurpose	= 'list_order_emails';
		break;
		case 'resendEmail': 	// case of resending order emails
			retdivid   	= 'email_div';
			fpurpose	= 'resend_OrderEmail';
			var emailid	= document.frmOrderDetails.del_note_id.value
			qrystr		= 'emailid='+emailid;
		break;
		case 'resendEmail_cust': 	// case of resending order emails
			retdivid   	= 'ordermain_div';
			fpurpose	= 'resend_OrderEmail_cust';
			var emailid	= document.frmOrderDetails.del_note_id.value
			qrystr		= 'emailid='+emailid;
		break;
		case 'operation_changeorderstatus_sel': 	// case of selected order status from drop down
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_changeorderstatus_sel';
			var sel_stat= document.frmOrderDetails.cbo_orderstatus.value
			if(sel_stat=='CANCELLED')
			{
				document.getElementById('orderstatchange_div').style.display='none';
			}
			else if(sel_stat=='ONHOLD' || sel_stat =='BACK')
			{
				document.getElementById('orderstatchange_div').style.display='none';
			}
			else
			{
				document.getElementById('orderstatchange_div').style.display='inline';
				document.getElementById('additionaldet_div').innerHTML='';
				return;
			}
			if(document.getElementById('additionaldet_div'))
				document.getElementById('additionaldet_div').style.display = '';
			qrystr		= 'sel_stat='+sel_stat;
		break;
		case 'operation_changeorderstatus_do': 	// case of change order status button is clicked( case other than order cancel case)
			retdivid   	= 'ordermain_div';
			fpurpose	= 'operation_changeorderstatus_do';
			var note	= '';
			var p_ids 	= '';
			var additional_str = '';
			var sel_stat= document.frmOrderDetails.cbo_orderstatus.value;
			if(document.frmOrderDetails.cbo_orderstatus.value=='')
			{
				alert('Please select the Order Status');
				return false;
			}
			if(document.getElementById('txt_additionalnote'))
			{
				note = document.getElementById('txt_additionalnote').value;
			}
			if(document.frmOrderDetails.cbo_orderstatus.value=='CANCELLED')
			{
				var additional_str = '';
				obj = document.getElementById('cbo_selectedalternate_products[]');
				if (obj)
				{
					if(obj.length>0)
					{
						for(i=0;i<obj.length;i++)
						{
							if(p_ids!='')
								p_ids += '~';
							p_ids += obj.options[i].value;
						}
					}
					additional_str = '&p_ids='+p_ids;
				}
				//retdivid   	= 'selectalternateproduct_div';
				/* Section which check the checkboxes ticked by console user while cancelling*/
				var	stock_return 		= 0;
				var	bonusused_return	= 0;
				var	bonusdonated_return	= 0;
				var	bonusearned_return 	= 0;
				var maxvoucher_return	= 0;
				var	force_cancel	 	= 0;
				/* Check whether stock is to be returned */
				if(document.getElementById('chk_stock_return'))
				{
					if(document.getElementById('chk_stock_return').checked==true)
						stock_return = 1;
				}
				/* Check whether bonus points used is to be returned */
				if(document.getElementById('chk_bonusused_return'))
				{
					if(document.getElementById('chk_bonusused_return').checked==true)
						bonusused_return = 1;
				}
				/* Donate bonus Start */
				/* Check whether bonus points donated is to be returned */
				if(document.getElementById('chk_bonusdonated_return'))
				{
					if(document.getElementById('chk_bonusdonated_return').checked==true)
						bonusdonated_return = 1;
				}
				/* Donate bonus End */
				/* Check whether bonus earned is to be returned */
				if(document.getElementById('chk_bonusearned_return'))
				{
					if(document.getElementById('chk_bonusearned_return').checked==true)
						bonusearned_return = 1;
				}
				/* Check whether gift voucher max count is to be reduced by 1 */
				if(document.getElementById('chk_gift_usage'))
				{
					if(document.getElementById('chk_gift_usage').checked==true)
						maxvoucher_return = 1;
				}
				/* Check whether to forcefully cancel the order even if any of the products are despatched or refunded */
				if(document.getElementById('chk_cancel_forced'))
				{
					if(document.getElementById('chk_cancel_forced').checked==true)
						force_cancel = 1;
				}
				/* Donate bonus Start */
				additional_str += '&stock_return='+stock_return+'&bonusused_return='+bonusused_return+'&bonusearned_return='+bonusearned_return+'&maxvoucher_return='+maxvoucher_return+'&force_cancel='+force_cancel+'&bonusdonated_return='+bonusdonated_return;
				/* Donate bonus End */
			}
			else
			{
				if(!confirm('Are you sure you want to change the status of this order?'))
					return false;
			}
				if(document.getElementById('additionaldet_div'))
					document.getElementById('additionaldet_div').style.display = 'none';
				document.getElementById('req_change_ordpaystat').value = 1;
				document.getElementById('req_change_ordstat').value = 1;
				qrystr		= 'sel_stat='+sel_stat+'&note='+note+additional_str;
		break;
		case 'show_alternateproduct_selsection': // case of showing the box to select the alternate products section
			retdivid   	= 'selectalternateproduct_div';
			fpurpose	= 'show_alternateproduct_selsection';
			var note	= '';
			var sel_cat = document.frmOrderDetails.sel_alternatecategory.value;
			var sel_pn	= document.frmOrderDetails.txt_alternateprodname.value;
			var p_ids 	= '';
			obj 		= document.getElementById('cbo_selectedalternate_products[]');
			if (obj)
			{
				if(obj.length>0)
				{
					for(i=0;i<obj.length;i++)
					{	if(p_ids!='')
							p_ids += '~';
						p_ids += obj.options[i].value;
					}
				}
			}
			qrystr		= 'sel_cat='+sel_cat+'&sel_pn='+sel_pn+'&sel_prd='+p_ids;
		break;
		case 'operation_changeorderpaystatus_sel': 	// case of selected order payment status from drop down
			retdivid   	= 'paydet_div';
			document.getElementById('additionaldet_div').innerHTML='';
			if (document.getElementById('payment_mainerror_tr'))
				document.getElementById('payment_mainerror_tr').style.display = 'none';
			if(document.frmOrderDetails.cbo_orderstatus)
				document.frmOrderDetails.cbo_orderstatus.value = '';
			var sel_stat= document.frmOrderDetails.cbo_orderpaystatus.value
			if(sel_stat!='')
			{
				if(sel_stat=='Paid')
					fpurpose	= 'operation_changeorderpaystatus_Paidsel';
				else if(sel_stat=='Pay_Hold')
					fpurpose	= 'operation_changeorderpaystatus_PayHoldsel';
				else
					fpurpose	= 'operation_changeorderpaystatus_Failsel';			
				/*document.getElementById('orderpaychange_div').style.display='none';*/
			}
			else
			{
				/*<!--document.getElementById('orderpaychange_div').style.display='';-->*/
				document.getElementById('additionaldet_div').innerHTML='';
				document.getElementById('paydet_div').innerHTML='';
				return;
			}

			qrystr		= 'sel_stat='+sel_stat;
		break;
		case 'operation_changeorderpaystatus_do': // case of changing the order payment status
			retdivid   	= 'ordermain_div';
			fpurpose	= 'operation_changeorderpaystatus_do';
			var note	= '';
			var p_ids 	= '';
			var sel_stat			= document.frmOrderDetails.cbo_orderpaystatus.value;
			if(document.frmOrderDetails.cbo_paymethod)
				var cbopaymethod = document.frmOrderDetails.cbo_paymethod.value;
			else
			     var cbopaymethod = '';	
			if(sel_stat=='')
			{
				alert('Please select the Payment Status');
				return false;
			}
			if (document.frmOrderDetails.cbo_orderpaystatus.value=='Pay_Failed')
			{
			 if(document.frmOrderDetails.txt_additionalnote.value=='')
				{
				alert('Please enter the reason for payment fail');
				return false;
				}
			}
			/*if (document.frmOrderDetails.cbo_orderpaystatus.value!='Pay_Hold')
				var conf_msg = 'Are you sure you want to change the payment status of current order. \n\nYou cannot Undo this operation?';
			else*/
				var conf_msg = 'Are you sure you want to change the payment status of current order.?';
			if (confirm(conf_msg)==false)
			{
				return false;
			}
			if(document.getElementById('txt_additionalnote'))
			{
				note = document.getElementById('txt_additionalnote').value;
			}
			document.getElementById('req_change_ordpaystat').value = 1;
			qrystr		= 'sel_stat='+sel_stat+'&cbo_paymethod='+cbopaymethod+'&note='+note;
		break;
		case 'release_proddeposit': /*case of release remaining amount after product deposit*/
			retdivid   	= 'ordermain_div';
			fpurpose	= 'release_proddeposit';
			document.getElementById('req_release_amt').value = 1;
			qrystr		= '';
		break;
		case 'operation_despatched_sel': 	// case of despatch clicked
			var id_str = '';
			var j = 0;
			for(i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if (document.frmOrderDetails.elements[i].name=='checkboxprod[]')
				{
					if(document.frmOrderDetails.elements[i].checked==true)
					{
						if (id_str!='')
							id_str += '~';
						id_str += document.frmOrderDetails.elements[i].value;
					}
				}		
			}
			if (id_str=='')
			{
				alert('Please select the products to be despatched');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').style.top = getTop(150)+'px';
				document.getElementById('moveto_backorder_div').style.display = '';
			}	
			retdivid   	= 'moveto_backorder_div';
			fpurpose	= 'operation_despatched_sel';
			qrystr			= 'prod_id_str='+id_str;
			if(document.getElementById('despatch_src'))
			{
				document.getElementById('despatch_src').value = id_str;
			}
		break;
		case 'operation_despatched_do': 	// case of despatch done clicked
			var atleast_one 	= false;
			var atleast_one_zero = false;
			/*Check whether any of the items in order are ticked*/
			if(document.getElementById('despatch_src')=='')
			{
				alert('Please select the product to be despatched');
				return false;
			}
			else
			{
				if (confirm('Are you sure you want to despatch the selected products in current order?'))
				{
					retdivid   	= 'ordermain_div';
					fpurpose	= 'operation_despatched_do';
					var id_str 	= document.getElementById('despatch_src').value;
					var refno	= document.getElementById('txt_despatch_id').value;
					var note	= document.getElementById('txt_despatch_note').value;
					var exp_del_date	= document.getElementById('txt_expected_delivery_date').value;
					document.getElementById('moveto_backorder_div').style.display = 'none';
					qrystr		= 'id_str='+id_str+'&refno='+refno+'&exp_del_date='+exp_del_date+'&note='+encodeURIComponent(note);
					document.getElementById('despatch_src').value = '';
				}
			}	
		break;
		case 'operation_despatched_email_preview_do_done': 	// case of need to show the email preview for despatch emails
				if (confirm('Are you sure you want to continue with the Despatch Process?'))
				{	
					document.frmOrderDetails.fpurpose.value = 'operation_despatched_email_preview_do_done';
					show_processing();
					document.frmOrderDetails.submit();
					return true;
				}	
		break;
		case 'operation_despatched_email_preview_do_cancel':
				if (confirm('Are you sure you want to Cancel the Despatch Process?'))
				{	
					document.frmOrderDetails.fpurpose.value = 'operation_despatched_email_preview_do_cancel';
					show_processing();
					document.frmOrderDetails.submit();
					return true;
				}
		break;
		case 'operation_despatched_email_preview_do': 	// case of need to show the email preview for despatch emails
			var atleast_one 	= false;
			var atleast_one_zero = false;
			/*Check whether any of the items in order are ticked*/
			if(document.getElementById('despatch_src')=='')
			{
				alert('Please select the product to be despatched');
				return false;
			}
			else
			{
				if (confirm('Are you sure you want to continue to the Despatch email preview page?'))
				{
					retdivid   	= 'ordermain_div';
					fpurpose	= 'operation_despatched_email_preview_do';
					var id_str 	= document.getElementById('despatch_src').value;
					var refno	= document.getElementById('txt_despatch_id').value;
					var note	= document.getElementById('txt_despatch_note').value;
					var exp_date= document.getElementById('txt_expected_delivery_date').value;
					document.getElementById('moveto_backorder_div').style.display = 'none';
					qrystr		= 'id_str='+id_str+'&refno='+refno+'&note='+encodeURIComponent(note);
					document.getElementById('despatch_src').value = '';
					document.frmOrderDetails.fpurpose.value = 'operation_despatched_email_preview_do';
					document.frmOrderDetails.holded_despatch_id.value = refno;
					document.frmOrderDetails.holded_despatch_note.value = nl2br(note);
					document.frmOrderDetails.holded_id_str.value = id_str;
					document.frmOrderDetails.holded_exp_delivery_date_str.value = exp_date;
					
					show_processing();
					document.frmOrderDetails.submit();
					return true;
				}
			}	
		break;
		case 'operation_despatch_cancel_do':
			retdivid   	= 'ordermain_div';
			fpurpose	= 'operation_despatch_cancel_do';
			if (confirm('All details related to this despatch will be removed and item will be placed back in order as not despatched.\n\n Are you sure you want to cancel this despatch?'))
			{
				var desp_id = document.getElementById('despatch_del').value;
				qrystr		= 'desp_id='+desp_id;
			}
			else
				return false;
		break;
		case 'operation_var_despatch_rem': 	// case of showing the product variables and despatch details if any
			var ord_det = document.getElementById('sel_orddet').value;
			retdivid   	= 'orddetrem_'+ord_det+'_div';
			fpurpose	= 'operation_var_despatch';

			atleast_one = false;
			qrystr		= 'ord_det='+ord_det+'&show_despatch=2';

		break;
		case 'operation_refund_sel': 	// case of refund clicked
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_refund_sel';
			if (document.frmOrderDetails.cbo_orderstatus)
				document.frmOrderDetails.cbo_orderstatus.value = '';
			document.getElementById('refundtop_div').style.display='none';
		break;
		/*case 'refund_details': 	// case of refund details
			retdivid   	= 'refunddet_div';
			fpurpose	= 'refund_details';
		break;*/
		case 'operation_refund_prods': // case of refund product details
			var ref_id = document.getElementById('sel_orddet').value;
			retdivid   	= 'refunddet_div_'+ref_id;
			fpurpose	= 'operation_refund_prods';
			qrystr		= 'ref_id='+ref_id;
		break;
		case 'operation_returnrefund_prods': // case of return product details while refund on return
			var ret_id_arr = document.getElementById('sel_orddet').value.split('~');
			retdivid   	= 'refunddet_div_'+ret_id_arr[1];
			fpurpose	= 'operation_returnrefund_prods';
			qrystr		= 'ret_id='+ret_id_arr[0];
		break;
		case 'RELEASE': // case of deferred release
			retdivid   	= 'paydet_div';
			fpurpose	= 'operation_release_sel';
			qrystr		= '';
			if (document.getElementById('capturetypereleasemain_div'))
				document.getElementById('capturetypereleasemain_div').style.display ='none';
		break;
		case 'ABORT': // case of deferred abort
			retdivid   	= 'paydet_div';
			fpurpose	= 'operation_abort_sel';
			qrystr		= '';
			// Hiding the top Abort Button
			if (document.getElementById('capturetypeabortmain_div'))
				document.getElementById('capturetypeabortmain_div').style.display ='none';
		break;
		case 'REPEAT': // case of preauth repeat
			retdivid   	= 'paydet_div';
			fpurpose	= 'operation_repeat_sel';
			qrystr		= '';
			// Hiding the top Repeat Button
			if (document.getElementById('capturetyperepeatmain_div'))
				document.getElementById('capturetyperepeatmain_div').style.display ='none';
		break;
		case 'AUTHORISE': // case of authenticate Authorise
			retdivid   	= 'paydet_div';
			fpurpose	= 'operation_authorise_sel';
			qrystr		= '';
			// Hiding the top Authorise Button
			if (document.getElementById('capturetypeauthorisemain_div'))
				document.getElementById('capturetypeauthorisemain_div').style.display ='none';
		break;
		case 'authorise_amount_details': // case of authenticate Authorise details
			retdivid   	= 'authdet_div';
			fpurpose	= 'authorise_amount_details';
			qrystr		= '';
		break;
		case 'CANCEL': // case of authenticate Cancel
			retdivid   	= 'paydet_div';
			fpurpose	= 'operation_cancel_sel';
			qrystr		= '';
			// Hiding the top cancel Button
			if (document.getElementById('capturetypecancelmain_div'))
				document.getElementById('capturetypecancelmain_div').style.display ='none';
		break;
		/*case 'order_queries': // case of order queries 
			retdivid   	= 'orderquery_div';
			fpurpose	= 'order_queries';
			atleast_one = false;
			qrystr		= '';
		break;*/
		case 'order_download': // case of order downloadables
			retdivid   	= 'orderdownload_div';
			fpurpose	= 'order_download';
			atleast_one = false;
			qrystr		= '';
		break;
		case 'order_download_change_status': // case of order downloadables change status  
			retdivid   	= 'orderdownload_div';
			fpurpose	= 'order_download_change_status';
			var ch_stat = document.getElementById('download_changestatus').value;	
			var downid  = '';
			atleast_one = false;
			/*Check whether any of the items in order are ticked*/
			for (i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if(document.frmOrderDetails.elements[i].type=='checkbox')
				{
					if(document.frmOrderDetails.elements[i].name=='download_check[]')
					{
						if(document.frmOrderDetails.elements[i].checked)
						{
							if (downid!='')
								downid += '~';
							downid +=document.frmOrderDetails.elements[i].value; 	
							atleast_one = true;
						}
					}
				}
			}
			if (atleast_one==false)
			{
				alert('Please select the downloadable item(s) to change the status');
				return false;
			}
			qrystr		= 'ch_status='+ch_stat+'&downid='+downid;
			if (!confirm('Are you sure you want to change the status of downloadable product(s)'))
				return false;
		break;
		case 'order_download_save_details': // case of order downloadables save details
			retdivid   				= 'orderdownload_div';
			fpurpose				= 'order_download_save_details';
			var downid  			= '';
			var varlimit 			= '';
			var startdate 			='';
			var startdate_time	= '';
			var enddate 			= '';
			var enddate_time	= '';
			atleast_one 			= false;
			/*Check whether any of the items in order are ticked*/
			for (i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if(document.frmOrderDetails.elements[i].type=='checkbox')
				{
					if(document.frmOrderDetails.elements[i].name=='download_check[]')
					{
						if(document.frmOrderDetails.elements[i].checked)
						{
							if (downid!='')
								downid += '~';
							if (varlimit!='')
								varlimit += '~';
							if (startdate!='')
								startdate += '~';	
							
									
							if (enddate!='')
								enddate += '~';		
							
									
								
							downid 		+=document.frmOrderDetails.elements[i].value; 	
							obj			= eval("document.getElementById('download_limit_"+document.frmOrderDetails.elements[i].value+"')");
							varlimit 		+= obj.value;
							obj			= eval("document.getElementById('download_startdate_"+document.frmOrderDetails.elements[i].value+"')");
							startdate 	+= obj.value;
							objhr			= eval("document.getElementById('download_starttime_hr_"+document.frmOrderDetails.elements[i].value+"')");
							objmn		= eval("document.getElementById('download_starttime_mn_"+document.frmOrderDetails.elements[i].value+"')");
							objss			= eval("document.getElementById('download_starttime_ss_"+document.frmOrderDetails.elements[i].value+"')");
							startdate 	+= ' '+objhr.value+':'+objmn.value+':'+objss.value;
							
							obj		= eval("document.getElementById('download_enddate_"+document.frmOrderDetails.elements[i].value+"')");
							enddate += obj.value;
							objhr			= eval("document.getElementById('download_endtime_hr_"+document.frmOrderDetails.elements[i].value+"')");
							objmn		= eval("document.getElementById('download_endtime_mn_"+document.frmOrderDetails.elements[i].value+"')");
							objss			= eval("document.getElementById('download_endtime_ss_"+document.frmOrderDetails.elements[i].value+"')");
							enddate 	+= ' '+objhr.value+':'+objmn.value+':'+objss.value;
							atleast_one = true;
						}
					}
				}
			}
			if (atleast_one==false)
			{
				alert('Please select the downloadable item(s) to save the details');
				return false;
			}
			qrystr		= 'downid='+downid+'&varlimit='+varlimit+'&startdate='+startdate+'&enddate='+enddate;
			if (!confirm('Are you sure you want to save the details of selected downloadable product(s)'))
				return false;
		break;
		case 'movetobackorder_select': // show move to back order div
			var id_str = '';
			var j = 0;
			for(i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if (document.frmOrderDetails.elements[i].name=='checkboxprod[]')
				{
					if(document.frmOrderDetails.elements[i].checked==true)
					{
						if (id_str!='')
							id_str += '~';
						id_str += document.frmOrderDetails.elements[i].value;
					}
				}		
			}
			if (id_str=='')
			{
				alert('Please select the products to be moved to back order');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').style.top = getTop(150)+'px';
				document.getElementById('moveto_backorder_div').style.display = '';
			}	
			retdivid   	= 'moveto_backorder_div';
			fpurpose	= 'show_movetobackorder_select';
			qrystr			= 'prod_id_str='+id_str;
		break;
		case 'movetobackorder_do': // move to back order action
			var id_str = '';
			var j = 0;
			var curqty = 0;
			split_arr = new Array();
			
			var id_str = showOfDiv('moveto_backorder_div');
			
			if (id_str=='')
			{
				alert('Please select the products to be moved to back order');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').innerHTML = '';
				document.getElementById('moveto_backorder_div').style.display = 'none';
			}	
			retdivid   	= 'ordermain_div';
			fpurpose	= 'show_movetobackorder_do';
			qrystr			= 'prod_id_str='+id_str;
		break;
		case 'movebacktoorder_select': // show move back to order div
			var id_str = '';
			var j = 0;
			for(i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if (document.frmOrderDetails.elements[i].name=='checkboxbackprod[]')
				{
					if(document.frmOrderDetails.elements[i].checked==true)
					{
						if (id_str!='')
							id_str += '~';
						id_str += document.frmOrderDetails.elements[i].value;
					}
				}		
			}
			if (id_str=='')
			{
				alert('Please select the products to be moved back to order');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').style.top = getTop(150)+'px';
				document.getElementById('moveto_backorder_div').style.display = '';
			}	
			retdivid   	= 'moveto_backorder_div';
			fpurpose	= 'show_movebacktoorder_select';
			qrystr			= 'prod_id_str='+id_str;
		break;
		case 'movebacktoorder_do':
			var id_str = '';
			var j = 0;
			var curqty = 0;
			split_arr = new Array();
			
			var id_str = showOfDiv('moveto_backorder_div');
			
			if (id_str=='')
			{
				alert('Please select the products to be moved to back order');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').innerHTML = '';
				document.getElementById('moveto_backorder_div').style.display = 'none';
			}	
			retdivid   	= 'ordermain_div';
			fpurpose	= 'show_movebacktoorder_do';
			qrystr			= 'prod_id_str='+id_str;
		break;
		case 'movetocancel_select_main': // show move to cancel select in case of coming from main
		case 'movetocancel_select_back': // show move to cancel select in case of coming from backorder listing
			if(mod=='movetocancel_select_main')
			{
				qrystr			= 'cancel_src=main&';
				document.getElementById('cancel_src').value = 'main';
				var chkprod = 'checkboxprod[]';
			}	
			else if(mod=='movetocancel_select_back')
			{
				qrystr			= 'cancel_src=back&';
				document.getElementById('cancel_src').value = 'back';
				var chkprod = 'checkboxbackprod[]';
			}	
			var id_str = '';
			var j = 0;
			for(i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if (document.frmOrderDetails.elements[i].name==chkprod)
				{
					if(document.frmOrderDetails.elements[i].checked==true)
					{
						if (id_str!='')
							id_str += '~';
						id_str += document.frmOrderDetails.elements[i].value;
					}
				}		
			}
			if (id_str=='')
			{
				alert('Please select the products to be cancelled');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').style.top = getTop(150)+'px';
				document.getElementById('moveto_backorder_div').style.display = '';
			}	
			retdivid   	= 'moveto_backorder_div';
			fpurpose	= 'show_movebacktocancel_select';
			qrystr			+= 'prod_id_str='+id_str;
		break;
		case 'movetocancel_do': //  move items to cancel
			var id_str = '';
			var j = 0;
			var curqty = 0;
			split_arr = new Array();
			
			var id_str = showOfDiv('moveto_backorder_div');
			
			if (id_str=='')
			{
				alert('Please select the products to be cancelled');
				return false;
			}
			else
			{
				document.getElementById('moveto_backorder_div').innerHTML = '';
				document.getElementById('moveto_backorder_div').style.display = 'none';
			}	
			retdivid   	= 'ordermain_div';
			fpurpose	= 'show_movetocancel_do';
			var cancel_src = document.getElementById('cancel_src').value;
			qrystr			= 'prod_id_str='+id_str+'&cancel_src='+cancel_src;
		break;
		case 'return_select': // show return qty selection
			var id_str = '';
			var j = 0;
			for(i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if (document.frmOrderDetails.elements[i].name=='checkboxdespatchprod[]')
				{
					if(document.frmOrderDetails.elements[i].checked==true)
					{
						if (id_str!='')
							id_str += '~';
						id_str += document.frmOrderDetails.elements[i].value;
					}
				}		
			}
			if (id_str=='')
			{
				alert('Please select the products to be returned');
				return false;
			}
			else
			{
				document.getElementById('moveto_returnorder_div').style.top = getTop(150)+'px';
				document.getElementById('moveto_returnorder_div').style.display = '';
			}	
			retdivid   	= 'moveto_returnorder_div';
			fpurpose	= 'show_return_select';
			qrystr			= 'prod_id_str='+id_str;
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
	retobj 															= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 											= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/orders.php','fpurpose='+fpurpose+'&curtab='+curtab+'&'+qrystr+'&ord_id='+ord_id);
}
function showOfDiv(div) 
{
      if(!div) 
	  {
        return;
      }
	  var retstr = '';
	  var split_str = '';
      div = typeof div === "string" ? document.getElementById(div) : div;
      var elms = div.getElementsByTagName("*");
      for(var i = 0, maxI = elms.length; i < maxI; ++i) 
	  {
        var elm = elms[i];
        switch(elm.type)
		{
          case "select-one":
           	if (retstr!='')
				retstr = retstr +',';
				split_str = elm.name.split('_');
				retstr = retstr + split_str[3] +'~'+elm.value;
		 	break;
        };
      }
	  return retstr;
}
function showOfhiddenDiv(div,main_typ,sub_typ,cnt,sep) 
{
      if(!div) 
	  {
        return;
      }
	  var retstr = '';
	  var split_str = '';
      div = typeof div === "string" ? document.getElementById(div) : div;
      var elms = div.getElementsByTagName("*");
      for(var i = 0, maxI = elms.length; i < maxI; ++i) 
	  {
        var elm = elms[i];
		
        if (elm.type==main_typ)
		{
			  if (elm.name.substr(0,cnt)==sub_typ)
			  {
					if (retstr!='')
						retstr = retstr +sep;
						retstr = retstr + elm.value;
				}	
        }
      }
	  return retstr;
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('sel_tab_no.gif');
	switch(mod)
	{
		/*case 'bill': /* Case of billing address
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('bill_tr'))
					document.getElementById('bill_tr').style.display = '';
				call_ajax_showlistall('bill',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('bill_tr'))
					document.getElementById('bill_tr').style.display = 'none';
			}
		break;
		case 'delivery': /* Case of delivery details
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('delivery_tr'))
					document.getElementById('delivery_tr').style.display = '';
				call_ajax_showlistall('delivery',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('delivery_tr'))
					document.getElementById('delivery_tr').style.display = 'none';
			}
		break;*/
		case 'gift_wrap': /* Case of giftwrap details*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('gift_tr'))
					document.getElementById('gift_tr').style.display = '';
				call_ajax_showlistall('gift_wrap',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('gift_tr'))
					document.getElementById('gift_tr').style.display = 'none';
			}
		break;
		case 'tax': /* Case of tax details*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('tax_tr'))
					document.getElementById('tax_tr').style.display = '';
				call_ajax_showlistall('tax',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('tax_tr'))
					document.getElementById('tax_tr').style.display = 'none';
			}
		break;
		case 'voucher': /* Case of gift voucher details*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('voucher_tr'))
					document.getElementById('voucher_tr').style.display = '';
				call_ajax_showlistall('voucher',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('voucher_tr'))
					document.getElementById('voucher_tr').style.display = 'none';
			}
		break;
		case 'prom': /* Case of promotional code details*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('prom_tr'))
					document.getElementById('prom_tr').style.display = '';
				call_ajax_showlistall('promotional',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('prom_tr'))
					document.getElementById('prom_tr').style.display = 'none';
			}
		break;
		/*case 'payment': /* Case of payment details
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('payment_tr'))
					document.getElementById('payment_tr').style.display = '';
				call_ajax_showlistall('payment',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('payment_tr'))
					document.getElementById('payment_tr').style.display = 'none';
			}
		break;*/
		case 'notes': /* Case of notes*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('note_tr'))
					document.getElementById('note_tr').style.display = '';
				call_ajax_showlistall('notes',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('note_tr'))
					document.getElementById('note_tr').style.display = 'none';
			}
		break;
		/*case 'operation_def': // case of operations called from bottom of order details page
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('operation_tr'))
					document.getElementById('operation_tr').style.display = '';
				call_ajax_showlistall('operation',1);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('operation_tr'))
					document.getElementById('operation_tr').style.display = 'none';
			}
		break;
		case 'operation': // case of operations
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('operation_tr'))
					document.getElementById('operation_tr').style.display = '';
				call_ajax_showlistall('operation',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('operation_tr'))
					document.getElementById('operation_tr').style.display = 'none';
			}
		break;*/
		case 'emails': // case of order emails
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('email_tr'))
					document.getElementById('email_tr').style.display = '';
				call_ajax_showlistall('emails',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('email_tr'))
					document.getElementById('email_tr').style.display = 'none';
			}
		break;
		/*case 'refund_details': // case of refund details
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('refunddet_tr'))
					document.getElementById('refunddet_tr').style.display = '';
				call_ajax_showlistall('refund_details',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('refunddet_tr'))
					document.getElementById('refunddet_tr').style.display = 'none';
			}
		break;*/
		case 'authorise_amount_details': // case of authorise amount details
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('authdet_tr'))
					document.getElementById('authdet_tr').style.display = '';
				call_ajax_showlistall('authorise_amount_details',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('authdet_tr'))
					document.getElementById('authdet_tr').style.display = 'none';
			}
		break;
		/*case 'order_queries': // case of order queries
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('orderquery_tr'))
					document.getElementById('orderquery_tr').style.display = '';
				call_ajax_showlistall('order_queries',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('orderquery_tr'))
					document.getElementById('orderquery_tr').style.display = 'none';
			}
		break;*/
		case 'order_download': // case of order downloadables
			if (retindx!=-1)
			{
				imgobj.src = 'images/sel_tab_yes.gif';
				if(document.getElementById('orderdownload_tr'))
					document.getElementById('orderdownload_tr').style.display = '';
				call_ajax_showlistall('order_download',0);
			}
			else
			{
				imgobj.src = 'images/sel_tab_no.gif';
				if(document.getElementById('orderdownload_tr'))
					document.getElementById('orderdownload_tr').style.display = 'none';
			}
		break;
		
	};
}
function save_note()
{
	var ordid 			= '<?php echo $edit_id?>';
	frm					= document.frmOrderDetails;
	fieldRequired 		= Array('txt_notes');
	fieldDescription 	= Array('Note');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(document.getElementById('additionaldet_div'))
		document.getElementById('additionaldet_div').innerHTML = '';
	if(document.getElementById('cbo_orderstatus'))
		document.getElementById('cbo_orderstatus').value = '';
	if (document.getElementById('notes_mainerror_tr'))
		document.getElementById('notes_mainerror_tr').style.display = 'none';
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		call_ajax_showlistall('savenote',0);
	}
	else
		return false
}
function delete_note(noteid)
{
	var ordid 			= '<?php echo $edit_id?>';
	frm					= document.frmOrderDetails;
	if (document.getElementById('notes_mainerror_tr'))
		document.getElementById('notes_mainerror_tr').style.display = 'none';
	if(document.getElementById('additionaldet_div'))
		document.getElementById('additionaldet_div').innerHTML = '';
	if(document.getElementById('cbo_orderstatus'))
		document.getElementById('cbo_orderstatus').value = '';
	if (confirm('Are you sure you want to delete this note?'))
	{
		document.frmOrderDetails.del_note_id.value = noteid;
		call_ajax_showlistall('deletenote',0);
	}
}
function handle_showdetailsdiv(trid,divid)
{
	trobj 	= eval("document.getElementById('"+trid+"')");
	divobj	= eval("document.getElementById('"+divid+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		divobj.innerHTML = 'Details<img src="images/right_arr.gif" />';
	}
	else
	{
		trobj.style.display ='';
		divobj.innerHTML = 'Details<img src="images/down_arr.gif" /> ';
	}
}
function handle_downloadhistory(id)
{
	trobj 	= eval("document.getElementById('downloadhistory_tr_"+id+"')");
	divobj	= eval("document.getElementById('downloadhistory_div_"+id+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		divobj.innerHTML = 'Click to view download history<img src="images/right_arr.gif" />';
	}
	else
	{
		trobj.style.display ='';
		divobj.innerHTML = 'Click to hide download history<img src="images/down_arr.gif" /> ';
	}
}
function handle_showmessageiv(trid,divid)
{
	trobj 		= eval("document.getElementById('"+trid+"')");
	trdownobj 	= eval("document.getElementById('"+trid+"_down')");
	divobj		= eval("document.getElementById('"+divid+"')");
	if(trobj.style.display=='')
	{
		trobj.style.display = 'none';
		trdownobj.style.display ='none';
		divobj.innerHTML = 'Message<img src="images/right_arr.gif" />';
	}
	else
	{
		trobj.style.display ='';
		trdownobj.style.display ='';
		divobj.innerHTML = 'Message<img src="images/down_arr.gif" /> ';
	}
}
function resend_orderemail(emailid)
{
	var ordid 				= '<?php echo $edit_id?>';
	frm						= document.frmOrderDetails;
	fieldRequired 		= Array();
	fieldDescription 	= Array();
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		document.frmOrderDetails.del_note_id.value = emailid;
		call_ajax_showlistall('resendEmail',0);
	}
	else
		return false
}
function resend_orderemail_cust(emailid)
{
	var ordid 				= '<?php echo $edit_id?>';
	frm						= document.frmOrderDetails;
	fieldRequired 		= Array();
	fieldDescription 	= Array();
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		document.frmOrderDetails.del_note_id.value = emailid;
		call_ajax_showlistall('resendEmail_cust',0);
	}
	else
		return false
}
function handle_alternatecheckbox()
{
	if(document.frmOrderDetails.chk_cancelalternate.checked)
		document.getElementById('alternateprod_tr').style.display ='';
	else
		document.getElementById('alternateprod_tr').style.display ='none';
}
function validate_cancelorder()
{
	fieldRequired 		= Array('txt_additionalnote');
	fieldDescription 	= Array('Reason for Cancellation');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(document.getElementById('operation_errordiv'))
		document.getElementById('operation_errordiv').style.display = 'none';
	if(document.getElementById('chk_cancelalternate'))
	{
		if(document.getElementById('chk_cancelalternate').checked)
		{
			obj = document.getElementById('cbo_selectedalternate_products[]');
			if (obj)
			{
				if(obj.length==0)
				{
					alert('Please select the alternate products');
					return false;
				}
				else
				{
					for(i=0;i<obj.length;i++)
					{
						obj.options[i].selected = true;
					}
				}
			}
		}
	}
	if(Validate_Form_Objects(document.frmOrderDetails,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if (confirm('When the order status is set to Cancelled, no other operation will be allowed to the current order. \n\n Are you sure you want to cancel this order?'))
		{
			if(document.getElementById('retdiv_id'))
				document.getElementById('retdiv_id').value = 'ordermain_div';
			call_ajax_showlistall('operation_changeorderstatus_do',0);
		}
	}
}
function validate_changeorderstatus()
{
	if(document.getElementById('operation_errordiv'))
		document.getElementById('operation_errordiv').style.display = 'none';
	if(document.frmOrderDetails.cbo_orderstatus.value=='')
	{
		alert('Please select the Order Status');
		return false;
	}
	//if(confirm('Are you sure you want to change the status of order?'))
		call_ajax_showlistall('operation_changeorderstatus_do',0)
}
function validate_despatch_cancel(id)
{
	document.getElementById('despatch_del').value = id;
	call_ajax_showlistall('operation_despatch_cancel_do',0);
}
function add_alternate()
{
	prod_ids = new Array();
	obj = document.getElementById('cbo_selectalternate_products[]');
	if (obj)
	{
		var j =0;
		if(obj.length>0)
		{
			for(i=0;i<obj.length;i++)
			{
				if (obj.options[i].selected)
				{
					prod_ids[j] 	= obj.options[i].value;
					j++;
				}
			}
		}
	}
	if (j==0)
	{
		alert('Please select the alternate product(s) to add');
		return false;
	}
	else
	{
		move_right();
	}
}
function move_right()
{
	var rem_arr = new Array();
	var indx = 0;
	var val = 0;
	src_loc = document.getElementById('cbo_selectalternate_products[]');
	des_loc = document.getElementById('cbo_selectedalternate_products[]');
	for(i=0;i<src_loc.options.length;i++)
	{
			if(src_loc.options[i].selected)
			{
				var lgth = des_loc.options.length;
				des_loc.options[lgth]= new Option(src_loc.options[i].text,src_loc.options[i].value);
				rem_arr[indx] = i;
				indx++;
			}
	}
	for(i=rem_arr.length-1;i>=0;--i)
	{
		val = rem_arr[i];
		src_loc.remove(val);
	}
}
function remove_selectedalternateproducts()
{
	obj = document.getElementById('cbo_selectedalternate_products[]');
	rem = new Array();
	if (obj)
	{
		if(obj.length>0)
		{
			var j = 0;
			for(i=0;i<obj.length;i++)
			{
				if(obj.options[i].selected)
				{
					rem[j] = i;
					j++;
				}
			}
			if(j==0)
			{
				alert('No product selected to remove');
				return;
			}
			else
			{
				for(i=rem.length-1;i>=0;--i)
				{
					val = rem[i];
					obj.remove(val);
				}
			}
		}
	}
}
function release_remaining_amount()
{
	if(confirm('Are you sure you want to release the amount remaining after product deposit'))
		call_ajax_showlistall('release_proddeposit',0);
}
function validate_payReceivedstatus()
{
	if (document.frmOrderDetails.cbo_paymethod.value=='')
	{
		alert('Please select the payment method');
		document.frmOrderDetails.cbo_paymethod.focus();
		return false
	}
	call_ajax_showlistall('operation_changeorderpaystatus_do')
}
function handle_showvariables(detid)
{
	objtr 		= eval("document.getElementById('vartr_"+detid+"')");
	objimg 		= eval("document.getElementById('vardiv_"+detid+"')");
	objvardiv	= eval("document.getElementById('orddet_"+detid+"_div')");
	document.getElementById('sel_orddet').value = detid;
	if(objtr.style.display=='')
	{
		objtr.style.display = 'none';
		objimg.innerHTML = '<img src="images/right_arr.gif" />';
	}
	else
	{
		objtr.style.display ='';
		objimg.innerHTML = '<img src="images/down_arr.gif" /> ';
		call_ajax_showlistall('operation_var_despatch',0);
	}
}
function handle_showvariables_rem(detid)
{
	objtr 		= eval("document.getElementById('varremtr_"+detid+"')");
	objimg 		= eval("document.getElementById('varremdiv_"+detid+"')");
	objvardiv	= eval("document.getElementById('orddetrem_"+detid+"_div')");
	document.getElementById('sel_orddet').value = detid;
	if(objtr.style.display=='')
	{
		objtr.style.display = 'none';
		objimg.innerHTML = '<img src="images/right_arr.gif" />';
	}
	else
	{
		objtr.style.display ='';
		objimg.innerHTML = '<img src="images/down_arr.gif" /> ';
		call_ajax_showlistall('operation_var_despatch_rem',0);
	}
}
function handle_showrefund_prods(refid)
{
	objtr 		= eval("document.getElementById('refunddet_tr_"+refid+"')");
	objimg 		= eval("document.getElementById('refprodimgdiv_"+refid+"')");
	objvardiv	= eval("document.getElementById('refunddet_div_"+refid+"_div')");
	document.getElementById('sel_orddet').value = refid;
	if(objtr.style.display=='')
	{
		objtr.style.display = 'none';
		objimg.innerHTML 	= '<img src="images/right_arr.gif" />';
	}
	else
	{
		objtr.style.display ='';
		objimg.innerHTML 	= '<img src="images/down_arr.gif" /> ';
		call_ajax_showlistall('operation_refund_prods',0);
	}
}
function handle_showreturnrefund_prods(refid,retid)
{
	objtr 		= eval("document.getElementById('refunddet_tr_"+retid+"')");
	objimg 		= eval("document.getElementById('refprodimgdiv_"+retid+"')");
	objvardiv	= eval("document.getElementById('refunddet_div_"+retid+"_div')");
	document.getElementById('sel_orddet').value = refid+'~'+retid;
	if(objtr.style.display=='')
	{
		objtr.style.display = 'none';
		objimg.innerHTML 	= '<img src="images/right_arr.gif" />';
	}
	else
	{
		objtr.style.display ='';
		objimg.innerHTML 	= '<img src="images/down_arr.gif" /> ';
		call_ajax_showlistall('operation_returnrefund_prods',0);
	}
}
function validate_refund()
{
	frm 				= document.frmOrderDetails;
	var max_allowed	 	= parseFloat(document.frmOrderDetails.max_refundamt_allowed.value);
	var refund_amt 		= parseFloat(document.frmOrderDetails.txt_refundamt.value);
	var curr_symbol		= document.frmOrderDetails.currency_symbol.value;
	fieldRequired 		= Array('txt_refundamt','txt_refundreason');
	fieldDescription 	= Array('Amount to be refunded','Refund Reason');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array('txt_refundamt');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(refund_amt > max_allowed || refund_amt < 0)
		{
			alert('Maximum amount to be refunded is only '+curr_symbol+max_allowed+' and Minimum amount should be greater than zero.');
			document.frmOrderDetails.txt_refundamt.focus();
			return false;
		}
		if (confirm('Are you sure you want to refund the specified amount?'))
		{
			document.frmOrderDetails.fpurpose.value ='operation_refund_do';
			show_processing();
			document.frmOrderDetails.submit();
		}
	}
	else
	{
		return false;
	}
}
function validate_return()
{
	frm 						= document.frmOrderDetails;
	var max_allowed	 	= parseFloat(showOfhiddenDiv('moveto_returnorder_div','hidden','max_refundamt_',14,'~'));
	var refund_amt 		= 0;
	if(document.getElementById('currency_symbol'))
		var curr_symbol		= document.getElementById('currency_symbol').value;
	else
		var curr_symbol		= '';
	var add_str			= '';
	refund_amt 		= parseFloat(showOfhiddenDiv('moveto_returnorder_div','text','txt_refundamt',13,'~'));
	if (refund_amt!='')
	{
		if(refund_amt!=0 && refund_amt!='NaN')
		{
			if(refund_amt>max_allowed || refund_amt < 0)
			{
				alert('Maximum amount to be refunded is only '+curr_symbol+max_allowed+' and Minimum amount should be greater than zero');
				return false;
			}
			add_str = ' and refund the specified amount';
		}	
	}	
	if (confirm('Are you sure you mark the selected quantity as returned'+add_str+'?'))
	{
		// Find the items to placed in the hidden fields before submitting the form
		 var return_id_str 			= showOfhiddenDiv('moveto_returnorder_div','hidden','txt_sel',7,'~') ;
		 var return_qty_str 			= showOfhiddenDiv('moveto_returnorder_div','select-one','cbo_return_qty',14,'~') ;
		 var return_type_str 		= showOfhiddenDiv('moveto_returnorder_div','select-one','cbo_return_type',15,'~') ;
		 var return_reason_str 	= showOfhiddenDiv('moveto_returnorder_div','textarea','return_reason',13,'^~~^') ;

		document.frmOrderDetails.fpurpose.value 				='return_do';
		document.frmOrderDetails.return_amt.value 			= refund_amt;
		document.frmOrderDetails.return_id_str.value	 		= return_id_str;
		document.frmOrderDetails.return_qty_str.value 		= return_qty_str;
		document.frmOrderDetails.return_type_str.value 		= return_type_str;
		document.frmOrderDetails.return_reason_str.value 	= return_reason_str;
		show_processing();
		document.frmOrderDetails.submit();
	}
}
function perform_capturetype(mod)
{
	var msg ='';
	switch(mod)
	{
		case 'RELEASE':
			msg = 'Are you sure you want to Release the Transaction?';
		break;
		case 'REPEAT':
			msg = 'Are you sure you want to Repeat the Transaction?';
		break;
		case 'ABORT':
			msg = 'Are you sure you want to Abort the Transaction?';
		break;
		case 'AUTHORISE':
			var cur_amt 	= parseFloat(document.getElementById('txt_authamt').value);
			var all_amt 	= parseFloat(document.getElementById('max_auth_allowed').value);
			var tot_auth 	= parseFloat(document.getElementById('tot_auth').value);
			if(isNaN(cur_amt) || cur_amt <= 0)
			{
				alert("Please Enter numeric value");
				document.getElementById('txt_authamt').focus();
				return false;
			}

			var tot_amt = (all_amt);
			if(cur_amt>tot_amt)
			{
				alert("Sorry!! Authorised amount greater than the maximum amount that can be authorised.\n\nMaximum amount that can be authorised is "+document.getElementById('order_currency_symbol').value+document.getElementById('max_auth_allowed').value);
				document.getElementById('txt_authamt').focus();
				return false;

			}
			msg = 'Are you sure you want to mark the specified amount as Authorized?';
		break;
		case 'CANCEL':
			msg = 'Are you sure you want to Cancel the Transaction?';
		break;
	}
	if (confirm(msg) == true)
	{
		document.frmOrderDetails.fpurpose.value 		= 'operation_paycapture_do';
		document.frmOrderDetails.paycapture_type.value 	= mod;
		show_processing();
		document.frmOrderDetails.submit();
	}
	else
		return false;
}
/* Function which takes user back to order listing page*/
function goback_order()
{
	document.frmOrderDetails.fpurpose.value='';
	document.frmOrderDetails.submit();
}
function handle_despatch_note(divid)
{
	divobj	= eval("document.getElementById('notereason_"+divid+"')");
	obj 		= eval("document.getElementById('notereason_"+divid+"_div')");
	if (obj)
	{
		if(obj.style.display=='none')
		{
			divobj.innerHTML		='<b>Hide Despatch note</b><img src="images/down_arr.gif" border="0" />';
			obj.style.display		='';
		}	
		else
		{
			divobj.innerHTML 	= '<b>View Despatch note</b><img src="images/right_arr.gif" border="0" />';
			obj.style.display 		='none';
		}	
	}
}	
function handle_return_reason(divid)
{
	divobj	= eval("document.getElementById('return_reason_div_"+divid+"')");
	obj 		= eval("document.getElementById('return_reason_tr_"+divid+"')");
	if (obj)
	{
		if(obj.style.display=='none')
		{
			divobj.innerHTML		='<b>Hide Return reason</b><img src="images/down_arr.gif" border="0" />';
			obj.style.display		='';
		}	
		else
		{
			divobj.innerHTML 	= '<b>View Return reason</b><img src="images/right_arr.gif" border="0" />';
			obj.style.display 		='none';
		}	
	}
}	

</script>
<link rel="stylesheet" type="text/css" media="all" href="includes/orders/img/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="includes/orders/img/jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
	function show_date_field()
	{
		new JsDatePick({
			useMode:2,
			target:"txt_expected_delivery_date",
			dateFormat:"%d-%m-%Y"
			/*selectedDate:{				
				day:5,						
				month:9,
				year:2006
			},*/
			/*yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"includes/orders/img/"
			weekStartDay:1*/
		});
	}
</script>
<form name='frmOrderDetails' action='home.php?request=orders' method="post">
 		<table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="#" onclick="goback_order()">List Orders </a><span>  Order Details</span></div></td>
        </tr>
        <tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
		<?php
		
			if($alert or $_REQUEST['post_alert']) // section to show the alert message if any
			{
		?>
			<tr id="mainerror_div">
			  <td colspan="5" align="center" valign="middle" class="errormsg" >
			  <?
			  if($alert)
			  	echo $alert;
			else
				echo $_REQUEST['post_alert'];
			  ?></td>
			</tr>
		 <?php
		 	}
			if($desp_alert) // section to show the alert message if any
			{
		?>
			<tr>
			  <td colspan="5" align="center" valign="middle" class="errormsg" >
			  <?
			  	echo $desp_alert;
			  ?></td>
			</tr>
		 <?php
		 	}
			$srno = 1;
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
			
			// Check whether any new order enquiry exists for current order
			$sql_check = "SELECT count(query_id) 
										FROM 
											order_queries 
										WHERE 
											orders_order_id = $edit_id 
											AND query_status ='N' 
											AND 
													CASE query_source 
															WHEN ( 'A') 
																THEN user_id <> ".$_SESSION['console_id']." 
															ELSE 
																1
													END
												";
			$ret_check 		= $db->query($sql_check);
			list($query_cnt) 	= $db->fetch_array($ret_check);
			
			// Check whether any new post exists for current order
			// Check whether any new order enquiry exists for current order
			$sql_check = "SELECT count(post_id) 
										FROM 
											order_queries_posts a,order_queries b
										WHERE 
											b.orders_order_id = $edit_id 
											ANd a.order_queries_query_id=b.query_id
											AND post_status ='N' 
											AND 
													CASE post_source 
															WHEN ( 'A') 
																THEN user_id <> ".$_SESSION['console_id']." 
															ELSE 
																1
													END
												";
			$ret_check 		= $db->query($sql_check);
			list($post_cnt) 	= $db->fetch_array($ret_check);		
			$tot_query_cnt 	= $query_cnt + $post_cnt;							
		 ?>
		<tr>
		<td colspan="5" align="left" valign="middle">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tabmenu_x">
			<tr>
			<td onclick="handle_tabs('summary_tab_td','order_summary',0)" class="<?php echo ($curtab=='summary_tab_td' and $_REQUEST['fpurpose']!='operation_despatched_email_preview_do')?'toptab_sel':'toptab'?>" align="left" id="summary_tab_td"><span>Order Summary</span></td>
			<td onclick="handle_tabs('payment_tab_td','order_payment',0)" class="<?php echo ($curtab=='payment_tab_td')?'toptab_sel':'toptab'?>" id="payment_tab_td" align="left"><span>Payments</span></td>
			<td onclick="handle_tabs('despatch_tab_td','order_despatch',0)" class="<?php echo ($curtab=='despatch_tab_td' or $_REQUEST['fpurpose']=='operation_despatched_email_preview_do')?'toptab_sel':'toptab'?>" id="despatch_tab_td" align="left"><span>Despatch</span></td>
			<td onclick="handle_tabs('refund_tab_td','order_refund',0)" class="<?php echo ($curtab=='refund_tab_td')?'toptab_sel':'toptab'?>" id="refund_tab_td" align="left"><span>Refund</span></td>
			<td onclick="handle_tabs('returns_tab_td','order_return',0)" class="<?php echo ($curtab=='returns_tab_td')?'toptab_sel':'toptab'?>" id="returns_tab_td" align="left"><span>Returns</span></td>
			<td onclick="handle_tabs('notes_tab_td','order_notes',0)" class="<?php echo ($curtab=='notes_tab_td')?'toptab_sel':'toptab'?>" id="notes_tab_td" align="left"><span>Notes &amp; Emails</span></td>
			<td onclick="handle_tabs('query_tab_td','order_custquery',0)" class="<?php echo ($curtab=='query_tab_td')?'toptab_sel':'toptab'?>" id="query_tab_td" align="left"><span><div id="customer_query_tab_div">Customer Queries (<?php echo $tot_query_cnt?>)</div></span></td>
			<td onclick="handle_tabs('other_tab_td','order_other',0)" class="<?php echo ($curtab=='order_other')?'other_tab_td':'toptab'?>" id="other_tab_td" align="left"><span>Others</span></td>
			<?php 
			if($show_custom == true)
			{
			?>
			<td onclick="handle_tabs('other_custtab_td','order_cust_mail',0)" class="<?php echo ($curtab=='order_cust_mail')?'other_custtab_td':'toptab'?>" id="other_custtab_td" align="left"><span>Custom Emails</span></td>
			<?php
			}
			?>
			<td width="70%">&nbsp;</td>
			</tr>
			</table>		</td>
		</tr>
		 <tr>
		   <td colspan="5" align="left" valign="middle">
		   <div id="ordermain_div">
		   <?php 
		   		$call_src = 'from_details_page';
			   	//decide_show_tab($curtab,$edit_id);
				if($_REQUEST['fpurpose']=='operation_despatched_email_preview_do')
				{
					$editor_elements = "long_desc";
					include_once(ORG_DOCROOT."/console/js/tinymce.php");
					$show_email_content = show_Order_Despatch_email_content($edit_id,$despatchhold_det_arr,$despatchhold_note,$despatchhold_id,'',$despatch_exp_delivery_date);	
							
				?>
	<div class="listingarea_div">
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
				<tr>
				<td colspan="2" align="left" class="listingtablestyleB"><strong>Despatch Email Content Preview</strong>				</td>
				</tr>
				<tr>
				<td colspan="2" align="left" class="helpmsgtd">The content of the email that will be send to the customer while performing the despatch of selected products can be managed from this section. <br /><br />The displayed email content has been build based on the email template set for the despatch emails. <br /><br />If you would like to make any changes to the email content, please do that and click on the "Despatch Now" button to do the Despatch.				</td>
				</tr>
				<?php
				$additional_emailid = '';
				$sql_gen = "SELECT order_despatch_additional_email 
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
				}
				?>
				<tr>
				  <td colspan="2" align="left">
				  <table width="100%%" border="0" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="4%" align="left" class="subcaption listingtablestyleB" style="padding:8px">To:</td>
                      <td width="96%" align="left" class="subcaption listingtablestyleB" style="padding:8px"><input type="text" name="preview_custemail" size="50" value="<?php echo trim($row_ord['order_custemail'])?>" /></td>
                    </tr>
					<?php
					if($additional_emailid!='')
					{
					?>
					<tr>
					<td align="left" class="subcaption listingtablestyleB" style="padding:8px">CC:</td>
					<td align="left" class="subcaption listingtablestyleB" style="padding:8px"><input type="text" name="preview_ccemail" size="50"  value="<?php echo $additional_emailid?>" /></td>
					</tr>
					<?php
					}
					?>  
				</table></td>
				</tr>	
				<tr>
				<td width="55%" align="left">
				<textarea style="height:600px; width:650px" id="long_desc" name="despatch_email_content"><?=stripslashes($show_email_content)?></textarea>				</td>
				<td width="45%" align="center" valign="top"><table width="100%%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="46%" align="left"><input type="button" name="cancel_desp" value="Cancel Despatch" class="red" onclick="call_ajax_showlistall('operation_despatched_email_preview_do_cancel')" /></td>
                    <td width="54%" align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center">&nbsp;</td>
                    <td align="center">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" align="left"><input type="button" name="Button" value="Save & Continue with Despatch" class="red" onclick="call_ajax_showlistall('operation_despatched_email_preview_do_done')" /></td>
                  </tr>
                </table>
				  <br />
				  <br />
				  <br />
				  <br />
				  <br /></td>
				</tr>
				</table>
				</div>
				<?php
				}
				
			?>
		   </div></td>
	     </tr>
	    </table>
		<?php /*<input type="hidden" name="storename" id="storename" value="<?=$_REQUEST['storename']?>" />*/ ?>
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?=$edit_id?>" />
		<input type="hidden" name="ord_status" value="<?php echo ($_REQUEST['ord_status'])?$_REQUEST['ord_status']:$_REQUEST['ser_ord_status']?>" />
		<input type="hidden" name="order_placed_from" value="<?php echo ($_REQUEST['order_placed_from'])?$_REQUEST['order_placed_from']:$_REQUEST['ser_order_placed_from']?>" />
		<input type="hidden" name="records_per_page" value="<?php echo ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:$_REQUEST['records_per_page']?>" />
		<input type="hidden" name="ord_email" value="<?php echo ($_REQUEST['ord_email'])?$_REQUEST['ord_email']:$_REQUEST['ser_ord_email']?>" />
		<input type="hidden" name="ord_fromdate" value="<?php echo ($_REQUEST['ord_fromdate'])?$_REQUEST['ord_fromdate']:$_REQUEST['ser_ord_fromdate']?>" />
		<input type="hidden" name="ord_todate" value="<?php echo ($_REQUEST['ord_todate'])?$_REQUEST['ord_todate']:$_REQUEST['ser_ord_todate']?>" />
		<input type="hidden" name="ord_stores" value="<?php echo ($_REQUEST['ord_stores'])?$_REQUEST['ord_stores']:$_REQUEST['ser_ord_stores']?>" />
		<input type="hidden" name="ord_sort_by" value="<?php echo ($_REQUEST['ord_sort_by'])?$_REQUEST['ord_sort_by']:$_REQUEST['ser_ord_sort_by']?>" />
		<input type="hidden" name="ord_sort_order" value="<?php echo ($_REQUEST['ord_sort_order'])?$_REQUEST['ord_sort_order']:$_REQUEST['ser_ord_sort_order']?>" />
		<input type="hidden" name="pg" value="<?php echo ($_REQUEST['pg'])?$_REQUEST['pg']:$_REQUEST['ser_pg']?>" />
		<input type="hidden" name="start" value="<?php echo ($_REQUEST['start'])?$_REQUEST['start']:$_REQUEST['ser_start']?>" />
		<input type="hidden" name="records_per_page" value="<?php echo ($_REQUEST['records_per_page'])?$_REQUEST['records_per_page']:$_REQUEST['ser_records_per_page']?>" />

		<input type="hidden" name="req_change_ordstat" id="req_change_ordstat" value="" />
		<input type="hidden" name="req_change_ordpaystat" id="req_change_ordpaystat" value="" />
		<input type="hidden" name="sel_orddet" id="sel_orddet" value="" />
		<input type="hidden" name="req_release_amt" id="req_release_amt" value="" />
		<input type="hidden" name="del_note_id" id="del_note_id" value="" />
		<input type="hidden" name="paycapture_type" id="paycapture_type" value="" />
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="ordermain_div" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		<input type="hidden" name="selected_tab" id="selected_tab" value="<?php echo $curtab?>" />
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />
		<input type="hidden" name="cancel_src" id="cancel_src" value="" />
		<input type="hidden" name="despatch_src" id="despatch_src" value="" />
		<input type="hidden" name="despatch_del" id="despatch_del" value="" />
		<input type="hidden" name="return_amt" id="return_amt" value="" />
		<input type="hidden" name="return_id_str" id="return_id_str" value="" />
		<input type="hidden" name="return_qty_str" id="return_qty_str" value="" />
		<input type="hidden" name="return_type_str" id="return_type_str" value="" />
		<input type="hidden" name="return_reason_str" id="return_reason_str" value="" />
		
		<?php
		$show_note = nl2br($despatchhold_note);
		?>
		<input type="hidden" name="holded_despatch_id" id="holded_despatch_id" value="<?php echo $despatchhold_id?>" />
		<input type="hidden" name="holded_despatch_note" id="holded_despatch_note" value="<?php echo $show_note?>" />
		<input type="hidden" name="holded_id_str" id="holded_id_str" value="<?php echo $despatchhold_det_arr?>" />
		<input type="hidden" name="holded_exp_delivery_date_str" id="holded_exp_delivery_date_str" value="<?php echo $despatch_exp_delivery_date?>" />
		
		
</form>
<?php
if($_REQUEST['fpurpose']!='operation_despatched_email_preview_do')
{
?>
<script type="text/javascript">
var curtab = '<?php echo $curtab?>';
	switch(curtab)
	{
		case 'summary_tab_td':
			handle_tabs('summary_tab_td','order_summary',0);
		break;
		case 'payment_tab_td':
			handle_tabs('payment_tab_td','order_payment',1);
		break;
		case 'despatch_tab_td':
			handle_tabs('despatch_tab_td','order_despatch',1);
		break;
		case 'refund_tab_td':
			handle_tabs('refund_tab_td','order_refund',1);
		break;
		case 'returns_tab_td':
			handle_tabs('returns_tab_td','order_return',1);
		break;
		case 'notes_tab_td':
			handle_tabs('notes_tab_td','order_notes',0);
		break;
		case 'query_tab_td':
			handle_tabs('query_tab_td','order_custquery',0);
		break;
		case 'other_tab_td':
			handle_tabs('other_tab_td','order_other',0);
		break;
	};
</script>
<?php
}
if($show_custom == true)
{
?>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 51%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.subcaption_modal {
    padding: 8px;
    font-size: 12px;
    font-weight: bold;
    color: #000000;
    float: left;
    width: 9%;
    text-align: right;
}
.subcaption_rt {
    padding: 8px;
    text-align: left;
    width: 72%;
}
</style>
<script>
function open_modal_cust()
{
	modal.style.display = "block";
	$('#whole_content').show();
	$('#email_cust_success').hide();
	
}
function send_mail_cust(order_id)
{
    var subject = $("#email_cust_subject").val();
    var content = $("#email_cust_content").val();
  // var emailid = '<?php echo stripslashes($row_ord['order_custemail']);?>';
    if(subject=='')
    {
	 alert('Enter Subject!!!');
	 $("#email_cust_subject").focus();
	 return false;
	}
	else if(content=='')
	{
	 alert('Enter Content!!!');
	 $("#email_cust_content").focus();
	 return false;
	}
	var fpurpose = 'send_email_cust';
	$.ajax({
	  method: "POST",
	  url: "services/orders.php",
	  data: { subject: subject, content: content,fpurpose:fpurpose,order_id:order_id },
	   success: function(data) {
	   $('#whole_content').hide();
	   $('#email_cust_success').show();
	   $('#email_cust_success').html(data);
	    $("#email_cust_subject").val('');
        $("#email_cust_content").val('');
}
});
}
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("send_email_cust");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<?php
}
?>