<?php
/*#################################################################
# Script Name 	: order_details.php
# Description 		: Page for showing the details of selected orders
# Coded by 		: Sny
# Created on		: 21-Apr-2008
# Modified by		: Sny
# Modified On		: 27-Aug-2008
#################################################################*/
//#Define constants for this page
$page_type 	= 'Order Details';
$help_msg 	= get_help_messages('EDIT_PRODUCT_STORE_SHORT');
$edit_id	= $_REQUEST['checkbox'][0];
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
?>
<script language="javascript" type="text/javascript">
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
			if(document.getElementById('req_change_ordstat').value==1)
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


			hide_processing();
		}
		else
		{
			show_request_alert(req.status);
		}
	}
}
function call_ajax_showlistall(mod,unhide_errdiv)
{
	var atleastone 										= 0;
	var ord_id											= '<?php echo $edit_id;?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	var qrystr											= '';

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

	if(document.getElementById('despatchmain_div'))
		document.getElementById('despatchmain_div').style.display='';
	if(document.getElementById('refundtop_div'))
	{
		document.getElementById('refundtop_div').style.display='';

	}
	switch(mod)
	{
		case 'bill': // Case of billing details
			retdivid   	= 'bill_div';
			fpurpose	= 'list_billing_address';
		break;
		case 'delivery': // case of delivery details
			retdivid   	= 'delivery_div';
			fpurpose	= 'list_delivery_address';
		break;
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
		case 'payment':		// case of payment details
			retdivid   	= 'payment_div';
			fpurpose	= 'list_payment_details';
		break;
		case 'notes':		// case of order notes
			retdivid   	= 'note_div';
			fpurpose	= 'list_notes';
		break;
		case 'operation':	// case of operations on orders
			retdivid   	= 'operation_div';
			fpurpose	= 'list_operations';
		break;
		case 'savenote':	// case of saving order notes
			retdivid   	= 'note_div';
			fpurpose	= 'save_note';
			var note	= document.frmOrderDetails.txt_notes.value;
			qrystr		= 'note='+note;
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
				document.getElementById('orderstatchange_div').style.display='';
				document.getElementById('additionaldet_div').innerHTML='';
				return;
			}

			qrystr		= 'sel_stat='+sel_stat;
		break;
		case 'operation_changeorderstatus_do': 	// case of change order status button is clicked( case other than order cancel case)
			retdivid   	= 'operation_div';
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
				/* Section which check the checkboxes ticked by console user while cancelling*/
				var	stock_return 		= 0;
				var	bonusused_return	= 0;
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
				additional_str += '&stock_return='+stock_return+'&bonusused_return='+bonusused_return+'&bonusearned_return='+bonusearned_return+'&maxvoucher_return='+maxvoucher_return+'&force_cancel='+force_cancel;
			}
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
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_changeorderpaystatus_sel';
			document.frmOrderDetails.cbo_orderstatus.value = '';
			document.getElementById('orderstatchange_div').style.display='';
			var sel_stat= document.frmOrderDetails.cbo_orderpaystatus.value
			if(sel_stat!='')
			{
				/*document.getElementById('orderpaychange_div').style.display='none';*/
			}
			else
			{
				/*<!--document.getElementById('orderpaychange_div').style.display='';-->*/
				document.getElementById('additionaldet_div').innerHTML='';
				return;
			}

			qrystr		= 'sel_stat='+sel_stat;
		break;
		case 'operation_changeorderpaystatus_do': // case of changing the order payment status
			retdivid   	= 'operation_div';
			fpurpose	= 'operation_changeorderpaystatus_do';
			var note	= '';
			var p_ids 	= '';
			var sel_stat= document.frmOrderDetails.cbo_orderpaystatus.value;
			if(sel_stat=='')
			{
				alert('Please select the Payment Status');
				return false;
			}
			if (confirm('Are you sure you want to change the payment status of current order. \n\nYou cannot Undo this operation?')==false)
			{
				return false;
			}
			if(document.getElementById('txt_additionalnote'))
			{
				note = document.getElementById('txt_additionalnote').value;
			}
			document.getElementById('req_change_ordpaystat').value = 1;
			qrystr		= 'sel_stat='+sel_stat+'&note='+note;
		break;
		case 'release_proddeposit': /*case of release remaining amount after product deposit*/
			retdivid   	= 'operation_div';
			fpurpose	= 'release_proddeposit';
			document.getElementById('req_release_amt').value = 1;
			qrystr		= '';
		break;
		case 'operation_despatched_sel': 	// case of despatch clicked
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_despatched_sel';
			atleast_one = false;
			/*Check whether any of the items in order are ticked*/
			for (i=0;i<document.frmOrderDetails.elements.length;i++)
			{
				if(document.frmOrderDetails.elements[i].type=='checkbox')
				{
					if(document.frmOrderDetails.elements[i].name=='checkboxprod[]')
					{
						if(document.frmOrderDetails.elements[i].checked)
						{
							atleast_one = true;
						}
					}
				}
			}
			if (atleast_one==false)
			{
				alert('Please select the items to be despatched');
				return false;
			}

			document.frmOrderDetails.cbo_orderstatus.value = '';
			document.getElementById('orderstatchange_div').style.display='';
			document.getElementById('despatchmain_div').style.display='none';
			qrystr		= 'sel_stat='+sel_stat;
		break;
		case 'operation_var_despatch': 	// case of showing the product variables and despatch details if any
			var ord_det = document.getElementById('sel_orddet').value;
			retdivid   	= 'orddet_'+ord_det+'_div';
			fpurpose	= 'operation_var_despatch';

			atleast_one = false;
			qrystr		= 'ord_det='+ord_det;

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
		case 'refund_details': 	// case of refund details
			retdivid   	= 'refunddet_div';
			fpurpose	= 'refund_details';
		break;
		case 'operation_refund_prods': // case of refund product details
			var ref_id = document.getElementById('sel_orddet').value;
			retdivid   	= 'refunddet_div_'+ref_id;
			fpurpose	= 'operation_refund_prods';
			qrystr		= 'ref_id='+ref_id;
		break;
		case 'RELEASE': // case of deferred release
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_release_sel';
			qrystr		= '';
			if (document.getElementById('capturetypereleasemain_div'))
				document.getElementById('capturetypereleasemain_div').style.display ='none';
		break;
		case 'ABORT': // case of deferred abort
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_abort_sel';
			qrystr		= '';
			// Hiding the top Abort Button
			if (document.getElementById('capturetypeabortmain_div'))
				document.getElementById('capturetypeabortmain_div').style.display ='none';
		break;
		case 'REPEAT': // case of preauth repeat
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_repeat_sel';
			qrystr		= '';
			// Hiding the top Repeat Button
			if (document.getElementById('capturetyperepeatmain_div'))
				document.getElementById('capturetyperepeatmain_div').style.display ='none';
		break;
		case 'AUTHORISE': // case of authenticate Authorise
			retdivid   	= 'additionaldet_div';
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
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_cancel_sel';
			qrystr		= '';
			// Hiding the top cancel Button
			if (document.getElementById('capturetypecancelmain_div'))
				document.getElementById('capturetypecancelmain_div').style.display ='none';
		break;
		case 'order_queries': // case of order queries 
			retdivid   	= 'orderquery_div';
			fpurpose	= 'order_queries';
			atleast_one = false;
			qrystr		= '';
		break;
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

	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
	retobj 															= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 											= '<center><img src="images/loading.gif" alt="Loading"></center>';

	/* Calling the ajax function */
	Handlewith_Ajax('services/orders.php','fpurpose='+fpurpose+'&'+qrystr+'&ord_id='+ord_id);
}
function handle_expansionall(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('sel_tab_no.gif');
	switch(mod)
	{
		case 'bill': /* Case of billing address*/
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
		case 'delivery': /* Case of delivery details*/
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
		break;
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
		case 'payment': /* Case of payment details*/
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
		break;
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
		case 'operation_def': // case of operations called from bottom of order details page
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
		break;
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
		case 'refund_details': // case of refund details
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
		break;
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
		case 'order_queries': // case of order queries
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
		break;
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
	if(confirm('Are you sure you want to change the status of order?'))
		call_ajax_showlistall('operation_changeorderstatus_do',0)
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
function validate_despatch()
{
	var atleast_one 	= false;
	var atleast_one_zero = false;
	/*Check whether any of the items in order are ticked*/
	for (i=0;i<document.frmOrderDetails.elements.length;i++)
	{
		if(document.frmOrderDetails.elements[i].type=='checkbox')
		{
			if(document.frmOrderDetails.elements[i].name=='checkboxprod[]')
			{
				if(document.frmOrderDetails.elements[i].checked)
				{
					atleast_one = true;
					obj = eval("document.getElementById('qty_"+document.frmOrderDetails.elements[i].value+"')");
					if(obj.value==0)
						atleast_one_zero = true;
				}
			}
		}
	}
	if (atleast_one==false)
	{
		alert('Please select the items to be despatched');
		return false;
	}
	if (atleast_one_zero==true)
	{
		alert('Please select the quantity of selected product(s) that need to be despatched');
		return false;
	}
	if (confirm('Are you sure you want to despatch the selected products in current order?'))
	{
		document.frmOrderDetails.fpurpose.value ='operation_despatched_do';
		show_processing();
		document.frmOrderDetails.submit();
	}
}
function validate_updateqty()
{
	var atleast_one = false;
	/*Check whether any of the items in order are ticked*/
	for (i=0;i<document.frmOrderDetails.elements.length;i++)
	{
		if(document.frmOrderDetails.elements[i].type=='checkbox')
		{
			if(document.frmOrderDetails.elements[i].name=='checkboxprod[]')
			{
				if(document.frmOrderDetails.elements[i].checked)
				{
					atleast_one = true;
				}
			}
		}
	}
	if (atleast_one==false)
	{
		alert('Please select the items for which the qty is to be updated');
		return false;
	}
	else
	{
		atleast_one = false;
		for (i=0;i<document.frmOrderDetails.elements.length;i++)
		{
			if(document.frmOrderDetails.elements[i].type=='checkbox')
			{
				if(document.frmOrderDetails.elements[i].name=='checkboxprod[]')
				{
					var p_arr 	= document.frmOrderDetails.elements[i].value;
					orgobj 		= eval("document.getElementById('orgqty_"+p_arr+"')");
					chobj 		= eval("document.getElementById('qty_"+p_arr+"')");
					if(orgobj.value!=chobj.value)
					{
						atleast_one = true;
					}
				}
			}
		}
		if (atleast_one==false)
		{
			alert('Please select the quantity to be updated for selected products in the order');
			return false;
		}
		if (confirm('Are you sure you want to updated the qty of selected items in order?'))
		{
			document.frmOrderDetails.fpurpose.value ='operation_updateqty_do';
			show_processing();
			document.frmOrderDetails.submit();
		}
	}
}
function validate_updatebackqty()
{
	var atleast_one = false;
	/*Check whether any of the items in order are ticked*/
	for (i=0;i<document.frmOrderDetails.elements.length;i++)
	{
		if(document.frmOrderDetails.elements[i].type=='checkbox')
		{
			if(document.frmOrderDetails.elements[i].name=='checkboxprod_rem[]')
			{
				if(document.frmOrderDetails.elements[i].checked)
				{
					atleast_one = true;
				}
			}
		}
	}
	if (atleast_one==false)
	{
		alert('Please select the items for which the qty is to be placed back to order');
		return false;
	}
	else
	{
		atleast_one = false;
		for (i=0;i<document.frmOrderDetails.elements.length;i++)
		{
			if(document.frmOrderDetails.elements[i].type=='checkbox')
			{
				if(document.frmOrderDetails.elements[i].name=='checkboxprod_rem[]')
				{
					var p_arr 	= document.frmOrderDetails.elements[i].value;
					orgobj 		= eval("document.getElementById('orgqtyrem_"+p_arr+"')");
					chobj 		= eval("document.getElementById('qtyrem_"+p_arr+"')");
					if(orgobj.value!=chobj.value)
					{
						atleast_one = true;
					}
				}
			}
		}
		if (atleast_one==false)
		{
			alert('Please select the quantity to be updated back for selected products in the order');
			return false;
		}
		if (confirm('Are you sure you want to updated back the qty of selected items in order?'))
		{
			document.frmOrderDetails.fpurpose.value ='operation_updateqty_back_do';
			show_processing();
			document.frmOrderDetails.submit();
		}
	}
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
		if(refund_amt > max_allowed)
		{
			alert('Maximum amount to be refunded is only '+curr_symbol+max_allowed);
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
</script>
<form name='frmOrderDetails' action='home.php?request=orders' method="post">
 		<table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="#" onclick="goback_order()">List Orders </a> &gt;&gt; Order Details </td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php
			if($alert) // section to show the alert message if any
			{
		?>
			<tr id="mainerror_div">
			  <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
			</tr>
		 <?php
		 	}
			$srno = 1;
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		 ?>
         <tr>
           <td width="14%" align="left" valign="middle" class="subcaption <?php echo $cls?>" >Order ID & Date </td>
           <td width="35%" align="left" valign="middle" class="<?php echo $cls?>" ><?php echo $row_ord['order_id']?>&nbsp;&nbsp;(<?php echo dateFormat($row_ord['order_date'],'datetime')?>)</td>
           <td width="20%" align="left" valign="middle" class="subcaption <?php echo $cls?>" >Order Status </td>
           <td width="31%" align="left" valign="middle" class="<?php echo $cls?>">
		   <div id="orderstatus_maindiv">
		   <?php echo getorderstatus_Name($row_ord['order_status']);

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
		   ?>
		   </div>
		   </td>
         </tr>
		 <?php
		 	$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
		 ?>
         <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           <?php
           	if(trim($row_ord['order_paymenttype'])!='')
           	{
           	?>
           		Payment Type
           <?php
           	}
           ?>
           </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" ><?php echo getpaymenttype_Name($row_ord['order_paymenttype'])?></td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           Payment Status

			</td>
           <td align="left" valign="middle" class="<?php echo $cls?>">
            <div id="paymentstatus_maindiv">
		   <?php echo  getpaymentstatus_Name($row_ord['order_paystatus'])?>
		   </div>

		   </td>
         </tr>
		 <?php
		 	$pay_str = $pay_usr = '';
		    if($row_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				$pay_usr = getConsoleUserName($row_ord['order_paystatus_changed_manually_by']).' ( on '.dateFormat($row_ord['order_paystatus_changed_manually_on'],'datetime').')'; ;
				$pay_str =  "Payment Status Changed By ";
			}
			$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleB';
			$srno++;
			?>
		 <tr>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
           <?php
		   	if($row_ord['order_paymentmethod']!='')
			{
			?>
		    	Payment Method
			<?php
			}
			else
				echo '&nbsp;';
			?>
            </td>
           <td align="left" valign="middle" class="<?php echo $cls?>" >
		   <?php
		   	if($row_ord['order_paymentmethod']!='')
			{
				echo getpaymentmethod_Name($row_ord['order_paymentmethod']);
			}
			else
				echo '&nbsp;';
			?>
		   </td>
           <td align="left" valign="middle" class="subcaption <?php echo $cls?>" >
		  <?php
		   if($pay_str!='')
		   {
		   		echo $pay_str;
		   }
		   ?>
		   </td>
           <td align="left" valign="middle" class="<?php echo $cls?>">
		   <?php
		   	if($pay_usr!='')
		   	{
		  	 echo $pay_usr;
			}
		   ?>
		   </td>
         </tr>
		 <tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="operation_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'operation')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Operations</td>
            </tr>
          </table></td>
        </tr>
		<tr id="operation_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="operation_div" style="text-align:center"></div></td>
		</tr>
		 <tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td width="79%" align="left" class="seperationtd_special">Products in Order</td>
                <td width="21%" align="left" class="seperationtd_special">
				<?php
					if($row_ord['order_status']!='CANCELLED')
					{
				?>
						<input type="button" name="updateqty_Submit" value="Update Quantity?" class="red" onclick="validate_updateqty()" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('UPDATE_IN_ORDER_QTY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				<?php
					}
				?>
				</td>
            </tr>
          </table></td>
        </tr>
			<?php
				// Get the products in this order
				$sql_prods = "SELECT orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,order_qty,
									order_orgqty,product_soldprice,order_retailprice,product_costprice,order_discount,
									order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
									order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
									order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
									order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
									order_discount_group_name,order_discount_group_percentage 
							 	FROM
									order_details
								WHERE
									orders_order_id = $edit_id ";//									AND order_qty>0";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
			?>
					<tr>
					<td align="left" colspan="4" class="tdcolorgray_normal">
			<?php
					$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxprod[]\')"/>','Product','Available?','Retail Price','Disc','Sale Price','Rem Qty','Ord Qty','Net');
					$header_positions	= array('center','left','center','right','right','right','center','center','right');
					$colspan 			= count($table_headers);
				?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<?php
					$srno=1;
					echo table_header($table_headers,$header_positions);
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
						$srno++;
						// Check whether the current product still exists in products table
						$sql_check = "SELECT product_id
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
						}
						else
							$link_req = $link_req_suffix= '';
				?>
						<tr>
						<td width="6%" align="left" class="<?php echo $cls?>">
						<?php
							if($row_prods['order_refunded']=='N' and $row_ord['order_paystatus']!='REFUNDED') // case if item is not refunded and also the order status is not refunded
							{
						?>
								<input type="checkbox" name="checkboxprod[]" id="checkboxprod[]" value="<?php echo $row_prods['orderdet_id']?>" />
						<?php
							}
							else
							{
								if($row_prods['order_refunded']=='Y')
									echo "Refunded";
							}
							/*if($row_prods['order_dispatched']=='Y' and $row_ord['order_status']!='DESPATCHED') // case if item is not despatched and also the order status is not despatched
							{*/
								/*// Get the despatched details
								$despatched_on = dateFormat($row_prods['order_dispatched_on'],'datetime');
								$sql_usr		= "SELECT sites_site_id,user_title,user_fname,user_lname
													FROM
														sites_users_7584
													WHERE
														user_id = ".$row_prods['order_dispatchedby']."
													LIMIT
														1";
								$ret_usr 	= $db->query($sql_usr);
								if ($db->num_rows($ret_usr))
								{
									$row_user = $db->fetch_array($ret_usr);
									if ($row_user['sites_site_id']==0) // case of super admin
										$despatched_by = stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
									else
										$despatched_by = stripslashes($row_user['user_title']).".".stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']);
								}
								$despatch_id 	= stripslashes($row_prods['order_dispatched_id']);
								$despatch_msg 	= 'Despatched By: '.$despatched_by.'<br/> <strong>On</strong> '.$despatched_on;
								if ($despatch_id != '')
								{
									$despatch_msg .="<br> <strong>Despatch Reference:</strong> ".$despatch_id;
								}*/
						?>
								<?php /*?><a href="#" onmouseover ="ddrivetip('<?php echo $despatch_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>
						<?php
							//}
						?>
						</td>
						<td width="30%" align="left" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?>&nbsp;
						<?php
							// Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id
												FROM
													order_details_variables
												WHERE
													order_details_orderdet_id = ".$row_prods['orderdet_id']."
												LIMIT
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							// Check whether any despatch details exists for current product
							$sql_desp = "SELECT despatched_id 
												FROM 
													order_details_despatched 
												WHERE 
													orderdet_id=".$row_prods['orderdet_id']." 
												LIMIT 
													1";
							$ret_desp = $db->query($sql_desp);
							if (mysql_num_rows($ret_desp))
								$desp_ext= true;
							else 	
								$dest_exxt = false;
							if($row_prods['order_dispatched']=='Y' or $db->num_rows($ret_varcheck)>0 or $desp_ext==true)
							{
						?>
								<div id='vardiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onclick="handle_showvariables('<?php echo $row_prods['orderdet_id']?>')" title="Click here"><img src="images/right_arr.gif" /></div>
						<?php
							}
						?>
						</td>
						<td width="10%" align="center" class="<?php echo $cls?>">
						<?php
							if ($row_prods['order_preorder']=='N')
							{
								echo 'In Stock';
							}
							else
								echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
						?></td>
						<td width="10%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						<td width="8%" class="<?php echo $cls?>" align="right">
						<?php
						if($row_prods['order_discount']>0)
						{
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
						?>
						<a href="#" onmouseover ="ddrivetip('<?=$disp_msg?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	
						<?php
						}
						?>
						<?php echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?>
						</td>
						<td width="10%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						<td width="8%" class="<?php echo $cls?>" align="center">
						<?php
							if($row_prods['order_refunded']=='N')
							{
						?>
						<select name="qty_<?php echo $row_prods['orderdet_id']?>" id="qty_<?php echo $row_prods['orderdet_id']?>">
						<?php
								for($i=$row_prods['order_qty'];$i>=0;$i--)
								{
						?>
									<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php
								}
						?>
						</select>
						<?php
							}
							else
								echo $row_prods['order_qty'];
						?>
						<input type="hidden" name="orgqty_<?php echo $row_prods['orderdet_id']?>" id="orgqty_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />
						</td>
						<td width="7%" class="<?php echo $cls?>" align="center">
						<?php echo $row_prods['order_orgqty']?>
						</td>
						<td width="20%" class="<?php echo $cls?>" align="right"><?php echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
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
				// Get the products removed from current order
				$sql_prods = "SELECT a.orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,b.order_qty,
									product_soldprice,order_retailprice,product_costprice,order_discount,
									order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
									order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
									order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
									order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
									b.order_removedon
								FROM
									order_details a,order_details_removed b
								WHERE
									orders_order_id = $edit_id
									AND a.orderdet_id=b.orderdet_id";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				?>
					<tr>
					  <td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
						  <td colspan="2" align="left">&nbsp;</td>
						  </tr>
						<tr>
							<td width="79%" align="left" class="seperationtd_special">Products Placed on Hold</td>
						    <td width="21%" align="left" class="seperationtd_special">
							<?php
								if($row_ord['order_status']!='CANCELLED')
								{
							?>
									<input type="button" name="backqty_Submit" value="Update On Hold Quantity?" class="red" onclick="validate_updatebackqty()" /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('UPDATE_ON_HOLD_QTY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							<?php
								}
							?>						  </td>
						</tr>
					  </table></td>
					</tr>
					<tr>
					<td align="right" colspan="4" class="tdcolorgray_normal">
				<?php
						$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxprod_rem[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxprod_rem[]\')"/>','Product','Available?','Qty','Removed on');
						$header_positions	= array('center','left','center','center','center');
						$colspan 			= count($table_headers);
					?>
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<?php
						$srno=1;
						$cnts = 0;
						echo table_header($table_headers,$header_positions);
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							$cls = ($srno%2==0)?'listingtablestyleA':'listingtablestyleB';
							$srno++;
							// Check whether the current product still exists in products table
							$sql_check = "SELECT product_id
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
							}
							else
								$link_req = $link_req_suffix= '';
					?>
							<tr>
							<td width="6%" align="center" class="<?php echo $cls?>">
							<?php
								if($row_ord['order_paystatus']!='REFUNDED')
								{
							?>
									<input type="checkbox" name="checkboxprod_rem[]" id="checkboxprod_rem[]" value="<?php echo $row_prods['orderdet_id']?>" />
							<?php
								}
							?>
							</td>
							<td width="30%" align="left" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?>
							<?php /*?><div id='varremdiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onclick="handle_showvariables(document.getElementById('varremtr_<?php echo $row_prods['orderdet_id']?>'),document.getElementById('varremdiv_<?php echo $row_prods['orderdet_id']?>'))" title="Click here"><img src="images/right_arr.gif" /></div><?php */?>
							<?php
								// Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id
												FROM
													order_details_variables
												WHERE
													orders_order_id = ".$edit_id."
												LIMIT
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($row_prods['orderdet_dispatched']=='Y' or $db->num_rows($ret_varcheck))
							{
							?>
								<div id='varremdiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onclick="handle_showvariables_rem('<?php echo $row_prods['orderdet_id']?>')" title="Click here"><img src="images/right_arr.gif" /></div>
							<?php
							}
							?>
							</td>
							<td width="15%" align="center" class="<?php echo $cls?>">
							<?php
								if ($row_prods['order_preorder']=='N')
								{
									echo 'In Stock';
								}
								else
									echo 'Available on <br>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
							?>
							</td>
							<td width="20%" class="<?php echo $cls?>" align="center">
							<select name="qtyrem_<?php echo $row_prods['orderdet_id']?>" id="qtyrem_<?php echo $row_prods['orderdet_id']?>">
							<?php
									for($i=$row_prods['order_qty'];$i>=0;$i--)
									{
							?>
										<option value="<?php echo $i?>"><?php echo $i?></option>
							<?php
									}
							?>
							</select>
							<input type="hidden" name="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" id="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />
							</td>
							<td width="30%" align="center" class="<?php echo $cls?>"><?php echo dateFormat($row_prods['order_removedon'],'datetime')?></td>
						</tr>
						<tr id="varremtr_<?php echo $row_prods['orderdet_id']?>" style="display:none">
						<td align="left" class="<?php echo $cls?>_var" colspan="<?php echo $colspan?>">
						<div id="orddetrem_<?php echo $row_prods['orderdet_id']?>_div" style="text-align:center"></div>
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
					<tr>
					<td align="right" colspan="4" class="tdcolorgray_normal">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">Sub Total</td>
							<td width="24%" colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_subtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Delivery Charge</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
						</tr>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Tax</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_tax_total'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
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
						  <td colspan="2" align="right" class="shoppingcartpriceB">&nbsp;<?PHP echo $usedprice ?></td>
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
						  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;- Total Promotional Code Used</td>
						  <td colspan="2" align="right" class="shoppingcartpriceB">&nbsp;<?PHP echo $usedprice ?></td>
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
						  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
						  <td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
						  </tr>
						<tr>
							<td colspan="6" align="right" class="shoppingcartpriceB">Grand Total</td>
							<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_totalprice'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
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
								<td colspan="6" align="right" class="shoppingcartpriceB">Total Refunded</td>
								<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_ord['order_refundamt'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
							</tr>
							<tr>
								<td colspan="6" align="right" class="shoppingcartpriceB">Total Remaining after Refund</td>
								<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_refundamt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
							</tr>
							<tr>
							  <td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
							  <td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
						  </tr>
						<?php
							}
						?>
						<?php
							if($row_ord['order_deposit_amt']>0) // Check whether deposit exists and not cleared yet
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
											$cleared_msg  = 'Released Amount Remaining by '.$cleared_by.' ('.$cleared_on.')';
											echo $cleared_msg;
										}
										else
										{
									?>
											Amount Remaining to be Released
									<?php
										}
									?>
									</div></td>
									<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_ord['order_totalprice']-$row_ord['order_deposit_amt']),$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
								</tr>
								<tr>
								<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
								<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
								</tr>
						<?php
							}
						?>
						</table>
				</td>
					</tr>
				<?php
				// Check whether refund details exists. If exists
			 $sql_refcheck = "SELECT orders_order_id
									FROM
										order_details_refunded
									WHERE
										orders_order_id = $edit_id
									LIMIT
										1";
				$ret_refcheck = $db->query($sql_refcheck);
				if($db->num_rows($ret_refcheck))
				{

				?>
					<tr>
						<td colspan="4" align="left" valign="bottom">
							<table width="100%" border="0" cellspacing="1" cellpadding="1">
							<tr>
							  <td colspan="2">&nbsp;</td>
							  </tr>
							<tr>
							<td width="3%" class="seperationtd_special"><img id="refunddet_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'refund_details')" title="Click"/></td>
							<td width="97%" align="left" class="seperationtd_special">Refund Details</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr id="refunddet_tr" style="display:none">
						<td align="right" colspan="4" class="tdcolorgray_buttons">
							<div id="refunddet_div" style="text-align:left"></div>
						</td>
					</tr>
				<?php
				}
			?>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'notes')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Notes</td>
            </tr>
          </table></td>
        </tr>
		<tr id="note_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="note_div" style="text-align:center"></div></td>
		</tr>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'bill')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Billing Details</td>
            </tr>
          </table></td>
        </tr>
		<tr id="bill_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="bill_div" style="text-align:center"></div></td>
		</tr>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'delivery')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Delivery Details</td>
            </tr>
          </table></td>
        </tr>
		<tr id="delivery_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="delivery_div" style="text-align:center"></div></td>
		</tr>
		<?php
			if($row_ord['order_giftwrap']=='Y') // show only if gift wrap exists
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'gift_wrap')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd_special">Giftwrap Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="gift_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="gift_div" style="text-align:center"></div></td>
				</tr>
		<?php
			}
			if($row_ord['order_tax_total']>0)
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'tax')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd_special">Tax Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="tax_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="tax_div" style="text-align:center"></div></td>
				</tr>
		<?php
			}
			if($row_ord['gift_vouchers_voucher_id']>0)// show only if gift voucher exists in current order
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'voucher')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd_special">Gift Voucher Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="voucher_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="voucher_div" style="text-align:center"></div></td>
				</tr>
		<?php
			}
			if($row_ord['promotional_code_code_id']>0)// show only if promotional code exists in current order
			{
		?>
				<tr>
				  <td colspan="4" align="left" valign="bottom">
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
					  <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'prom')" title="Click"/></td>
					  <td width="97%" align="left" class="seperationtd_special">Promotional Code Details</td>
					</tr>
				  </table></td>
				</tr>
				<tr id="prom_tr" style="display:none">
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="prom_div" style="text-align:center"></div></td>
				</tr>
		<?php
			}
		?>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'payment')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Payment Details</td>
            </tr>
          </table></td>
        </tr>
		<tr id="payment_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="payment_div" style="text-align:center"></div></td>
		</tr>
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'emails')" title="Click"/></td>
              <td width="97%" align="left" class="seperationtd_special">Emails</td>
            </tr>
          </table></td>
        </tr>
		<tr id="email_tr" style="display:none">
			<td align="right" colspan="4" class="tdcolorgray_buttons">
				<div id="email_div" style="text-align:center"></div></td>
		</tr>
		<?php
			// Decide whether the authorize amount section is to be displayed
			$sql_check = "SELECT auth_id
							FROM
								order_details_authorized_amount
							WHERE
								orders_order_id = $edit_id
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
		?>
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
						<td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'authorise_amount_details')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd_special">Authorise Amount Details</td>
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
		<?php
			// Decide whether the downloadable section is to be displayed
			$sql_check = "SELECT ord_down_id  
							FROM
								order_product_downloadable_products 
							WHERE
								orders_order_id = $edit_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
		?>
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
						<td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'order_download')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd_special">Order Downloadable Items</td>
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
		<?php
			// Decide whether the order queries section is to be displayed
			$sql_check = "SELECT query_id 
							FROM
								order_queries 
							WHERE
								orders_order_id = $edit_id
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
		?>
				<tr>
					<td colspan="4" align="left" valign="bottom">
						<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
						<td width="3%" class="seperationtd_special"><img id="billing_imgtag" src="images/sel_tab_no.gif" border="0" onclick="handle_expansionall(this,'order_queries')" title="Click"/></td>
						<td width="97%" align="left" class="seperationtd_special">Order Queries</td>
						</tr>
						</table>
					</td>
				</tr>

				<tr id="orderquery_tr" style="display:none">
						<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="orderquery_div" style="text-align:center"></div>
						</td>
				</tr>
		<?php
			}
		?>
	</table>
		<?php/*<input type="hidden" name="storename" id="storename" value="<?=$_REQUEST['storename']?>" />*/?>
		<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<?=$edit_id?>" />

		<input type="hidden" name="ord_status" value="<?php echo ($_REQUEST['ord_status'])?$_REQUEST['ord_status']:$_REQUEST['ser_ord_status']?>" />
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
		<input type="hidden" name="retdiv_id" id="retdiv_id" value="" />
		<input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />
</form>
<script type="text/javascript">
handle_expansionall(document.getElementById('operation_imgtag'),'operation_def'); /* Done to keep the operation tab expanded on load*/
</script>

