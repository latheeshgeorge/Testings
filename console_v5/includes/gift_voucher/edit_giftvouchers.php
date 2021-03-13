<?php
	/*#################################################################
	# Script Name 	: edit_giftvouchers.php
	# Description 	: Page for adding giftvouchers
	# Coded by 		: Sny
	# Created on	: 01-Aug-2007
	# Modified by	: Sny
	# Modified On	: 21-May-2008
	#################################################################*/
//Define constants for this page
$page_type = 'Gift Vouchers';
$help_msg = get_help_messages('EDIT_GIFT_VOUCH_MESS1');
if($curtab=='')
{
$curtab		= ($_REQUEST['curtab'])?$_REQUEST['curtab']:'main_tab_td';
}

($_REQUEST['voucher_id']>0)?$voucher_id=$_REQUEST['voucher_id']:$voucher_id=$_REQUEST['checkbox'][0];

$sql_gift = "SELECT *, date_format(voucher_activatedon,'%d-%m-%Y') startd, date_format(voucher_expireson,'%d-%m-%Y') endd 
				FROM gift_vouchers 
					WHERE voucher_id=".$voucher_id." AND sites_site_id='".$ecom_siteid."'" ;
					
$ret_gift = $db->query($sql_gift);
if($db->num_rows($ret_gift)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; } 
if($db->num_rows($ret_gift))
{
	$row_gift = $db->fetch_array($ret_gift);
}
$edit_id = $voucher_id;
?>
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	var used_limit = '<?php echo $row_gift['voucher_usage']; ?>';
	//if (document.getElementById('voucher_activatedon'))
	//{
		fieldRequired 		= Array('voucher_activatedon','voucher_expireson','voucher_value','voucher_max_usage');
		fieldDescription 	= Array('Start Date','End Date','Discount','Maximum Usage');
//	}
	//else
//	{
//		fieldRequired 		= Array('voucher_value','voucher_max_usage');
//		fieldDescription 	= Array('Discount','Maximum Usage');
//	}
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('voucher_value','voucher_max_usage');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(frm.voucher_type.value=='per' && (isNaN(frm.voucher_value.value) || frm.voucher_value.value >100 || frm.voucher_value.value <0))
		{
			alert("Please Enter a numeric value less than or equal to 100 as Discount Percentage");
			frm.voucher_value.focus();
			return false;
		}
		else if(frm.voucher_type.value=='val' && (frm.voucher_value.value == "" || frm.voucher_value.value < 0) )
		{
			alert("Please Enter-Discount value");
			frm.voucher_value.focus();
			return false;
		}
		else if(frm.voucher_max_usage.value < 0 || parseInt(frm.voucher_max_usage.value) < used_limit)
		{
			alert("Please Enter-Positive value for Maximum usage that should be greater than used limit "+used_limit);
			frm.voucher_max_usage.focus();
			return false;
		}
		val_dates = compareDates_giftvoucher(frm.voucher_activatedon,"Start Date\n Correct Format:dd-mm-yyyy ",frm.voucher_expireson,"End Date\n Correct Format:dd-mm-yyyy");
 		if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
	}
	else
		return false;
}
function handle_codetype(val)
{
	var symbol = '<?php echo $row_gift['voucher_curr_symbol']?>';
	if (symbol!='')
		symbol = '('+symbol+')';
	if(val=='val')
	{
		document.getElementById('dis_val').innerHTML = 'Discount '+symbol;
	}
	else if (val=='per')
	{
		document.getElementById('dis_val').innerHTML = 'Discount (%)';
	}
}
function handle_expansion(imgobj,mod)
{
	var src 			= imgobj.src;
	var retindx 		= src.search('plus.gif');
	switch(mod)
	{
		case 'voucherorder': /* Case of orders which used the voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('voucherorder_tr'))
					document.getElementById('voucherorder_tr').style.display = '';
				if(document.getElementById('voucherorderunassign_div'))
					document.getElementById('voucherorderunassign_div').style.display = '';
				call_ajax_showlistall('show_voucherorder');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('voucherorder_tr'))
					document.getElementById('voucherorder_tr').style.display = 'none';
				if(document.getElementById('voucherorderunassign_div'))
					document.getElementById('voucherorderunassign_div').style.display = 'none';
			}
		break;
		case 'vouchercustomer': /* Case of viewing customer who bought this voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('vouchercustomer_tr'))
					document.getElementById('vouchercustomer_tr').style.display = '';
				call_ajax_showlistall('show_vouchercustomer');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('vouchercustomer_tr'))
					document.getElementById('vouchercustomer_tr').style.display = 'none';
			}
		break;
		case 'voucherpayment': /* Case of viewing payment details foc current voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('voucherpayment_tr'))
					document.getElementById('voucherpayment_tr').style.display = '';
				call_ajax_showlistall('show_voucherpayment');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('voucherpayment_tr'))
					document.getElementById('voucherpayment_tr').style.display = 'none';
			}
		break;
		case 'voucheremail': /* Case of viewing emails linked with current voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('voucheremail_tr'))
					document.getElementById('voucheremail_tr').style.display = '';
				call_ajax_showlistall('show_voucheremail');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('voucheremail_tr'))
					document.getElementById('voucheremail_tr').style.display = 'none';
			}
		break;
		case 'voucheroperation': /* Case of viewing operation on vouchers*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('voucheroperation_tr'))
					document.getElementById('voucheroperation_tr').style.display = '';
				call_ajax_showlistall('show_voucheroperation');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('voucheroperation_tr'))
					document.getElementById('voucheroperation_tr').style.display = 'none';
			}
		break;
		case 'vouchernotes': /* Case of viewing notes added for current voucher*/
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('vouchernotes_tr'))
					document.getElementById('vouchernotes_tr').style.display = '';
				call_ajax_showlistall('show_vouchernotes');
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('vouchernotes_tr'))
					document.getElementById('vouchernotes_tr').style.display = 'none';
			}
		break;
		case 'refund_details': // case of refund details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('refunddet_tr'))
					document.getElementById('refunddet_tr').style.display = '';
				call_ajax_showlistall('refund_details',0);
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('refunddet_tr'))
					document.getElementById('refunddet_tr').style.display = 'none';
			}
		break;
		case 'authorise_amount_details': // case of authorise amount details
			if (retindx!=-1)
			{
				imgobj.src = 'images/minus.gif';
				if(document.getElementById('authdet_tr'))
					document.getElementById('authdet_tr').style.display = '';
				call_ajax_showlistall('authorise_amount_details',0);
			}
			else
			{
				imgobj.src = 'images/plus.gif';
				if(document.getElementById('authdet_tr'))
					document.getElementById('authdet_tr').style.display = 'none';
			}
		break;
	};
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var voucher_id										= '<?php echo $voucher_id;?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	var vouchernumber					='<?php echo $_REQUEST['vouchernumber']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'vouchernumber='+vouchernumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&voucher_id='+voucher_id+'&curtab='+curtab+'&showinall='+showinall;
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
	if(document.getElementById('mainerror_div'))
		document.getElementById('mainerror_div').style.display = 'none';

	if(document.getElementById('refundtop_div'))
	{
		document.getElementById('refundtop_div').style.display='';

	}
	switch(mod)
	{
		case 'show_voucherorder': // Case of orders linked with vouchers
		//	retdivid   	= 'voucherorder_div';
			fpurpose	= 'list_orders';
		break;
		case 'show_vouchercustomer': // Case of details of customer who bought the voucher
		//	retdivid   	= 'vouchercustomer_div';
			fpurpose	= 'voucher_customer_details';
		break;
		case 'show_voucherpayment': /* Case of showing payment details of current voucher*/
		//	retdivid   	= 'voucherpayment_div';
			fpurpose	= 'voucher_payment_details';
		break;
		case 'show_voucheremail': /* Case of showing emails linked with current voucher*/
		//	retdivid   	= 'voucheremail_div';
			fpurpose	= 'voucher_email_details';
		break;
		case 'resendEmail': 	/* case of resending order emails*/
			//retdivid   	= 'voucheremail_div';
			fpurpose	= 'resend_VoucherEmail';
			var emailid	= document.frmEditGiftVoucher.del_note_id.value
			qrystr		= 'emailid='+emailid;
		break;
		case 'show_voucheroperation': 	/* case of opetations on vouchers*/
			//retdivid   	= 'voucheroperation_div';
			fpurpose	= 'show_voucheroperation';
			qrystr		= '';
		break;
		case 'operation_changevoucherpaystatus_sel':
		//	retdivid   	= 'additionaldet_div';
			//fpurpose	= 'operation_changevoucherpaystatus_sel';
			document.getElementById('additionaldet_div').innerHTML='';
			sel_stat	= document.getElementById('cbo_voucherpaystatus').value;
			if(sel_stat!='')
			{
				if(sel_stat=='Paid')
					fpurpose	= 'operation_changevoucherpaystatus_Paidsel';
				else
					fpurpose	= 'operation_changevoucherpaystatus_Failsel';			
			}
			else if(sel_stat=='')
			{
				document.getElementById('additionaldet_div').innerHTML='';
				return;
			}
			document.frmEditGiftVoucher.retdiv_id.value ='additionaldet_div';
			qrystr		= 'sel_stat='+sel_stat;		

		break;
		case 'operation_changeorderpaystatus_do': // case of changing the order payment status
		//	retdivid   	= 'voucheroperation_div';
			fpurpose	= 'operation_changeorderpaystatus_do';
			var note	= '';
			var p_ids 	= '';
			var sel_stat= document.frmEditGiftVoucher.cbo_voucherpaystatus.value;
			if(sel_stat=='')
			{
				alert('Please select the Payment Status');
				return false;
			}
			if (confirm('Are you sure you want to change the payment status of current gift voucher. \n\nYou cannot Undo this operation?')==false)
			{
				return false;
			}
			if(document.getElementById('txt_additionalnote'))
			{
				note = document.getElementById('txt_additionalnote').value;
			}
			document.getElementById('req_change_vouchpaystat').value = 1;
			qrystr		= 'sel_stat='+sel_stat+'&note='+note;
		break;
		case 'show_vouchernotes': 	/* case of notes of voucher*/
		//	retdivid   	= 'vouchernotes_div';
			fpurpose	= 'show_vouchernotes';
			qrystr		= '';
		break;
		case 'savenote':
		//	retdivid   	= 'vouchernotes_div';
			fpurpose	= 'save_note';
			var note	= document.frmEditGiftVoucher.txt_notes.value;
			qrystr		= 'note='+note;
		break;
		case 'deletenote': 	// case of deleting voucher notes
		//	retdivid   	= 'vouchernotes_div';
			fpurpose	= 'delete_note';
			var noteid	= document.frmEditGiftVoucher.del_note_id.value
			qrystr		= 'noteid='+noteid;
		break;
		case 'operation_refund_sel': 	// case of refund clicked
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_refund_sel';
			document.getElementById('refundtop_div').style.display='none';
		break;
		case 'refund_details': 	// case of refund details
		//	retdivid   	= 'refunddet_div';
			fpurpose	= 'refund_details';
		break;
		case 'RELEASE': // case of deferred release
		//	retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_release_sel';
			document.getElementById('retdiv_id').value='additionaldet_div';
			qrystr		= '';
			if (document.getElementById('capturetypereleasemain_div'))
				document.getElementById('capturetypereleasemain_div').style.display ='none';
		break;
		case 'ABORT': // case of deferred abort
			document.getElementById('retdiv_id').value='additionaldet_div';
			fpurpose	= 'operation_abort_sel';
			qrystr		= '';
			// Hiding the top Abort Button
			if (document.getElementById('capturetypeabortmain_div'))
				document.getElementById('capturetypeabortmain_div').style.display ='none';
		break;
		case 'REPEAT': // case of preauth repeat
			document.getElementById('retdiv_id').value='additionaldet_div';
			fpurpose	= 'operation_repeat_sel';
			qrystr		= '';
			// Hiding the top Repeat Button
			if (document.getElementById('capturetyperepeatmain_div'))
				document.getElementById('capturetyperepeatmain_div').style.display ='none';
		break;
		case 'AUTHORISE': // case of authenticate Authorise
			document.getElementById('retdiv_id').value='additionaldet_div';
			fpurpose	= 'operation_authorise_sel';
			qrystr		= '';
			// Hiding the top Authorise Button
			if (document.getElementById('capturetypeauthorisemain_div'))
				document.getElementById('capturetypeauthorisemain_div').style.display ='none';
		break;
		case 'CANCEL': // case of authenticate Cancel
			document.getElementById('retdiv_id').value='additionaldet_div';
			fpurpose	= 'operation_cancel_sel';
			qrystr		= '';
			// Hiding the top cancel Button
			if (document.getElementById('capturetypecancelmain_div'))
				document.getElementById('capturetypecancelmain_div').style.display ='none';
		break;
		case 'authorise_amount_details': // case of authenticate Authorise details
			//retdivid   	= 'authdet_div';
			fpurpose	= 'authorise_amount_details';
			qrystr		= '';
		break;
	};
	//document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */
	retobj 											= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';
	/* Calling the ajax function */
	Handlewith_Ajax('services/gift_voucher.php','fpurpose='+fpurpose+'&'+qrystr+'&voucher_id='+voucher_id);
}
function validate_changepaystatus(sel_stat)
{
	fpurpose	= 'operation_changeorderpaystatus_do';
	var note	= '';
	var p_ids 	= '';
	//var sel_stat= document.frmEditGiftVoucher.cbo_voucherpaystatus.value;
	if(sel_stat=='Paid')
	{
	   if(document.frmEditGiftVoucher.cbo_paymethod.value=='')
	   {
	   alert('Please select the Payment Method');
		return false;
	   }
	}
	if(sel_stat=='')
	{
		alert('Please select the Payment Status');
		return false;
	}
	
	if (confirm('Are you sure you want to change the payment status of current gift voucher. \n\nYou cannot Undo this operation?')==false)
	{
		return false;
	}
	document.frmEditGiftVoucher.fpurpose.value =	'operation_changeorderpaystatus_do';
	document.frmEditGiftVoucher.pass_cbo_voucherpaystatus.value = sel_stat;
	document.getElementById('req_change_vouchpaystat').value = 1;
	show_processing();
	document.frmEditGiftVoucher.submit();
}
function validate_refund()
{
	frm 				= document.frmEditGiftVoucher;
	var max_allowed	 	= parseFloat(document.frmEditGiftVoucher.max_refundamt_allowed.value);
	var refund_amt 		= parseFloat(document.frmEditGiftVoucher.txt_refundamt.value);
	var curr_symbol		= document.frmEditGiftVoucher.currency_symbol.value;
	fieldRequired 		= Array('txt_refundamt','txt_refundreason');
	fieldDescription 	= Array('Amount to be refunded','Refund Reason');
	fieldEmail 			= Array();
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array('txt_refundamt');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(refund_amt < 0)
		{
			alert('Refund Amound Should not be -ve');
			document.frmEditGiftVoucher.txt_refundamt.focus();
			return false;
		}
		else if(refund_amt > max_allowed)
		{
			alert('Maximum amount to be refunded is only '+curr_symbol+max_allowed);
			document.frmEditGiftVoucher.txt_refundamt.focus();
			return false;
		}
		if (confirm('Are you sure you want to refund the specified amount?'))
		{
			document.frmEditGiftVoucher.fpurpose.value ='operation_refund_do';
			show_processing();
			document.frmEditGiftVoucher.submit();
		}
	}
	else
	{
		return false;
	}
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			document.frmEditGiftVoucher.retdiv_id.value ='master_div';
		}
		else
		{
			show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
/*
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
			targetobj.innerHTML = ret_val; /* Setting the output to required div /
			/* Decide the display of action buttons/
			switch(targetdiv)
			{
				case 'customer_div':
					if(document.getElementById('voucherorder_norec'))
					{
						if(document.getElementById('voucherorder_norec').value==1)
						{
							disp = 'none';
						}
						else
							disp = '';
					}
					else
						disp = '';
				break;

			};
			if (disp!='no')
			{
				norecobj 	= eval("document.getElementById('"+norecdiv+"')");
				if (norecobj)
					norecobj.style.display = disp;
			}
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
} */
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
	if(confirm('Are you sure you want to resend the selected email?'))
	{
		document.frmEditGiftVoucher.del_note_id.value = emailid;
		call_ajax_showlistall('resendEmail',0);
	}
}
function save_note()
{
	var ordid 			= '<?php echo $edit_id?>';
	frm					= document.frmEditGiftVoucher;
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
	frm					= document.frmEditGiftVoucher;
	if (confirm('Are you sure you want to delete this note?'))
	{
		frm.del_note_id.value = noteid;
		call_ajax_showlistall('deletenote',0);
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
		document.frmEditGiftVoucher.fpurpose.value 		= 'operation_paycapture_do';
		document.frmEditGiftVoucher.paycapture_type.value 	= mod;
		show_processing();
		document.frmEditGiftVoucher.submit();
	}
	else
		return false;
}
			 
function handle_tabs(id,mod)
{
	tab_arr 							= new Array('main_tab_td','opergift_tab_td','notes_tab_td','gftcust_tab_td','payment_tab_td','refund_tab_td','refundamt_tab_td','authamnt_tab_td','order_tab_td','emails_tab_td');
	var atleastone 						= 0;
	var voucher_id						= '<?php echo $voucher_id?>';
	var cat_orders						= '';
	var fpurpose						= '';
	var retdivid						= '';
	var moredivid						= '';
	var vouchernumber					='<?php echo $_REQUEST['vouchernumber']?>';
	var sortby							= '<?php echo $_REQUEST['sort_by']?>';
	var sortorder						= '<?php echo $_REQUEST['sort_order']?>';
	var recs							= '<?php echo $_REQUEST['records_per_page']?>';
	var start							= '<?php echo $_REQUEST['start']?>';
	var pg									= '<?php echo $_REQUEST['pg']?>';
	var curtab								= '<?php echo $curtab?>';
	var showinall							= '<?php echo $showinallpages?>';
	var qrystr									= 'vouchernumber='+vouchernumber+'&sort_by='+sortby+'&sort_order='+sortorder+'&records_per_page='+recs+'&start='+start+'&pg='+pg+'&voucher_id='+voucher_id+'&curtab='+curtab+'&showinall='+showinall;
	
	for(i=0;i<tab_arr.length;i++)
	{
		
		if(tab_arr[i]!=id)
		{
		
			obj = eval ("document.getElementById('"+tab_arr[i]+"')");
			if (obj)
				obj.className = 'toptab';
		}
	}
	obj = eval ("document.getElementById('"+id+"')");
	obj.className = 'toptab_sel';
	
	switch(mod)
	{
		case 'giftmain_info':
			fpurpose ='show_giftvoucher_main';
		break;
		case 'operation_giftvoucher': // Case of Categories in the group
			
			//retdivid   	= 'master_div';
			fpurpose	= 'show_voucheroperation';
			//moredivid	= 'category_groupunassign_div';
			
		break;
		case 'notes': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'show_vouchernotes';
			//moredivid	= 'displaycategory_groupunassign_div';
		break;
		case 'giftvoucher_customers': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'voucher_customer_details';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'payment_details': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'voucher_payment_details';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'refund_details': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'refund_details';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
			case 'refund_amount': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'refund_amount';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'authorised_amount': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'authorise_amount_details';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'giftvoucher_orders': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'list_orders';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		case 'giftvoucher_emails': // Case of Display Categories in the group
			//retdivid   	= 'master_div';
			fpurpose	= 'voucher_email_details';
			//moredivid	= 'displaystatic_groupunassign_div';
		break;
		
	}
	retobj 									= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	//retobj										= document.getElementById('retdiv_id');
	retobj.innerHTML 						= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	//alert('fpurpose='+fpurpose+'&cur_groupid='+group_id);
	Handlewith_Ajax('services/gift_voucher.php','fpurpose='+fpurpose+'&voucher_id='+voucher_id+'&'+qrystr);	
}			
</script>
<form name='frmEditGiftVoucher' action='home.php?request=gift_voucher' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=gift_voucher&vouchernumber=<?php echo $_REQUEST['vouchernumber']?>&paystatus=<?php echo $_REQUEST['paystatus']?>&addedby=<?php echo $_REQUEST['addedby']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>">List Gift Vouchers</a><span> Edit Gift Vouchers</span></div></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="9">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
        <tr>
          <td colspan="9" align="left" valign="middle"  >	
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabmenu_x">
		 
				<tr>
						<td  align="left"   onClick="handle_tabs('main_tab_td','giftmain_info')" class="<?php if($curtab=='main_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="main_tab_td"><span>Main Info</span></td>
<!--						<td width="9%" align="left"  onClick="handle_tabs('opergift_tab_td','operation_giftvoucher')" class="<?php //if($curtab=='opergift_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="opergift_tab_td">Operations </td>
-->						
		<?php
        	// Check whether customer details tab to be displayed
        	if ($row_gift['voucher_createdby']=='C')
        	{
        		/*// Check whether payment details exists. if exists show the payment details section
        		$sql_checkpay = "SELECT payment_id
        						FROM
        							gift_vouchers_payment
        						WHERE
        							gift_vouchers_voucher_id = ".$_REQUEST['checkbox'][0]."
        						LIMIT
        							1";
        		$ret_checkpay = $db->query($sql_checkpay);
        		$sql_checkcheque = "SELECT gift_vouchers_voucher_id
        								FROM
        									gift_vouchers_cheque_details
        								WHERE
        									gift_vouchers_voucher_id = ".$_REQUEST['checkbox'][0]."
        								LIMIT
        									1";
        		$ret_checkcheque = $db->query($sql_checkcheque);
        		if ($db->num_rows($ret_checkpay) or $db->num_rows($ret_checkcheque))
        		{*/
        ?>				<td  align="left"  onClick="handle_tabs('payment_tab_td','payment_details')" class="<?php if($curtab=='payment_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="payment_tab_td"><span>Payment Details</span></td>
		<?PHP //} 
		} 
			// Check whether refund details exists. If exists
				/*$sql_refcheck = "SELECT gift_vouchers_voucher_id
									FROM
										gift_voucher_details_refunded
									WHERE
										gift_vouchers_voucher_id = ".$_REQUEST['checkbox'][0]. "
									LIMIT
										1";
				$ret_refcheck = $db->query($sql_refcheck);
				if($db->num_rows($ret_refcheck))
				{*/
		?>
<!--						<td width="12%" align="left"  onClick="handle_tabs('refund_tab_td','refund_details')" class="<?php //if($curtab=='refund_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="refund_tab_td">Refund Details</td>
-->		<?PHP 
			//	} 
		
		   $sql_vouch = "SELECT voucher_paystatus,voucher_value,voucher_createdby,voucher_value,voucher_refundamt
						FROM
							gift_vouchers
						WHERE
							voucher_id = ".$voucher_id. "
						LIMIT
							1";
			$ret_vouch = $db->query($sql_vouch);
			if ($db->num_rows($ret_vouch))
			{
				$row_vouch = $db->fetch_array($ret_vouch);
			}
		     // Allow to refund even if the order status is cancelled until order_totalprice is > order_refundamt
		  if ($row_vouch['voucher_value']>0) // case if deposit amount exists
		  {
		  	$check_tot_amt = $row_vouch['voucher_value'];

		  }
		  if(($row_vouch['voucher_paystatus']=='Paid' or $row_vouch['voucher_paystatus']=='REFUNDED') and  $row_vouch['voucher_createdby']!='A' ) // Show the refund button only if payment is successfulll
		  	{
				?>
					<!--	<div id="refundtop_div" style="float:left; padding-right:5px;padding-bottom:3px">
						<input name="refund_Submit" type="button" class="red" id="refund_Submit" value="Refund?" onclick="call_ajax_showlistall('operation_refund_sel')" />
						</div>-->
					 <td  align="left"   onClick="handle_tabs('refundamt_tab_td','refund_amount')" class="<?php if($curtab=='refundamt_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="refundamt_tab_td"><span>Refund</span></td>

				<?php
		  	}
		   
			// Decide whether the authorize amount section is to be displayed
			$sql_check = "SELECT auth_id
							FROM
								gift_voucher_details_authorized_amount
							WHERE
								gift_vouchers_voucher_id = ".$voucher_id."
							LIMIT
								1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check))
			{
		

		?>			
						<td  align="left"   onClick="handle_tabs('authamnt_tab_td','authorised_amount')" class="<?php if($curtab=='authamnt_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="authamnt_tab_td"><span>Authorised Amount</span></td>
		<?PHP }
		?>				
	   
	   	<?PHP 
			if($row_gift['voucher_createdby'] != 'A') 
			{
		?>
						<td  align="left"   onClick="handle_tabs('emails_tab_td','giftvoucher_emails')" class="<?php if($curtab=='emails_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="emails_tab_td"><span>Emails</span></td>
		<?PHP 
			} 
		?>	
		<td  align="left"   onClick="handle_tabs('notes_tab_td','notes')" class="<?php if($curtab=='notes_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="notes_tab_td"><span>Notes</span></td>	
		 <?php
        	// Check whether customer details tab to be displayed 
			
        	if ($row_gift['voucher_createdby']=='C')
        	{
        ?>
						<td  align="left"  onClick="handle_tabs('gftcust_tab_td','giftvoucher_customers')" class="<?php if($curtab=='gftcust_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="gftcust_tab_td"><span>Giftvoucher Purchased By</span> </td>
		<?PHP } ?>
		<td  align="left"  onClick="handle_tabs('order_tab_td','giftvoucher_orders')" class="<?php if($curtab=='order_tab_td') echo "toptab_sel"; else echo "toptab"?>" id="order_tab_td"><span>Gift Voucher Orders </span></td>

						<td width="90%">&nbsp;</td>		
				</tr>
			</table></td>
        </tr>
     
	
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" ><div id='master_div'>
			<?php 
			$edit_id = $voucher_id;
			if ($curtab=='main_tab_td')
			{
				show_giftvoucher_main($edit_id,$alert);
			}
			elseif ($curtab=='opergift_tab_td')
			{
				show_operations($edit_id,$alert,$change_main_status);
			}
			elseif ($curtab=='notes_tab_td')
			{
				show_voucher_notes($edit_id,$alert);
			}
			elseif ($curtab=='gftcust_tab_td')
			{
				show_customer_details($edit_id,$alert);
			}
			elseif ($curtab=='payment_tab_td')
			{
				show_payment_details($edit_id,$_REQUEST['alert']);
			}
			elseif ($curtab=='refund_tab_td')
			{
				voucher_refund_details($edit_id,$alert);
			}
			elseif ($curtab=='refundamt_tab_td')
			{
				voucher_refund_amount($voucher_id,$_REQUEST['alert']);
			}
			elseif ($curtab=='authamnt_tab_td')
			{
				voucher_authorise_details($edit_id,$alert);
			}
			elseif ($curtab=='order_tab_td')
			{
				show_order_list($edit_id,$alert);
			}
			elseif ($curtab=='emails_tab_td')
			{
				show_voucher_emails($edit_id,$alert);
			}
			
			?>		
		  </div></td>
         </tr>
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="21%" align="left" valign="middle" class="tdcolorgray">

		  	<input type="hidden" name="vouchernumber" id="vouchernumber" value="<?=$_REQUEST['vouchernumber']?>" />
		  	<input type="hidden" name="paystatus" id="paystatus" value="<?=$_REQUEST['paystatus']?>" />
		  	<input type="hidden" name="addedby" id="addedby" value="<?=$_REQUEST['addedby']?>" />
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_update" />
			<input type="hidden" name="retdiv_id" id="retdiv_id" value="master_div" />
			<input type="hidden" name="checkbox[0]" value="<?php echo $voucher_id;?>" /> 
			<input type="hidden" name="voucher_id" id="voucher_id" value="<?PHP echo $voucher_id; ?>" />
			<input type="hidden" name="del_note_id" id="del_note_id" value="" />
			<input type="hidden" name="req_change_vouchpaystat" id="req_change_vouchpaystat" value="" />
			<input type="hidden" name="req_release_amt" id="req_release_amt" value="" />
			<input type="hidden" name="paycapture_type" id="paycapture_type" value="" />	
			<input type="hidden" name="pass_cbo_voucherpaystatus" id="pass_cbo_voucherpaystatus" value="" />	
			  </td>
          <td width="58%" align="left" valign="middle" class="tdcolorgray">
          <?php
          /*	if ($row_gift['voucher_paystatus']!='REFUNDED')
          	{
          ?>
          	<input name="prod_Submit" type="submit" class="red" value="Save" />
          <?php
          	}
          	else
          		echo '<span class="redtext">-- Voucher details cannot be modified since the payment status is Refunded --</span>';
         */ ?>          </td>
          <td width="3%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      
  </table>
</form>

