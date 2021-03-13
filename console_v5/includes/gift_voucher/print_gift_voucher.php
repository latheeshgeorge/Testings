<?php
	/*#################################################################
	# Script Name 	: list_giftvouchers.php
	# Description 	: Page for listing Gift Vouchers
	# Coded by 		: Sny
	# Created on	: 31-Jul-2007
	# Modified by	: Sny
	# Modified On	: 16-May-2008
	#################################################################*/
// Define constants for this page

include_once("../../functions/functions.php");
include('../../session.php');
require_once("../../config.php");

$voucher_id = $_REQUEST['voucherid'];

$page_type 	= 'Gift Vouchers';
$help_msg 	= get_help_messages('EDIT_PRODUCT_STORE_SHORT');

global $ecom_hostname;
$voucher_id = $_REQUEST['voucherid'];

//Define constants for this page
$page_type = 'Gift Vouchers';
$help_msg = 'This section helps in editing Gift Vouchers';
$sql_gift = "SELECT *, date_format(voucher_boughton,'%d-%m-%Y') startd, date_format(voucher_expireson,'%d-%b-%Y') endd 
			FROM gift_vouchers WHERE voucher_id=".$voucher_id;
$ret_gift = $db->query($sql_gift);
if($db->num_rows($ret_gift))
{
	$row_gift = $db->fetch_array($ret_gift);
}
?>	
<html>
<head>
	<link href="../../css/style_print.css" rel="stylesheet" media="screen">
	<link href="../../css/style_screen.css" rel="stylesheet" media="screen">
</head>
<body>
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('voucher_boughton','voucher_expireson','voucher_value','voucher_max_usage');
	fieldDescription = Array('Start Date','End Date','Discount','Maximum Usage');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('voucher_value','voucher_max_usage');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(frm.voucher_type.value=='per' && (isNaN(frm.voucher_value.value)|| frm.voucher_value.value >100))
		{
			alert("Please Enter a numeric value less than 100 as Discount Pecentage");
			frm.voucher_value.focus();
			return false;
		}
		else if(frm.voucher_type.value=='val' && frm.voucher_value.value == "" )
		{
			alert("Please Enter-Discount for Minimum");
			frm.voucher_value.focus();
			return false;
		}
		val_dates = compareDates(frm.voucher_boughton,"Start Date\n Correct Format:dd-mm-yyyy ",frm.voucher_expireson,"End Date\n Correct Format:dd-mm-yyyy");
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
	if(val=='val')
	{
		document.getElementById('dis_val').innerHTML = 'Discount Value';
	}
	else if (val=='per')
	{
		document.getElementById('dis_val').innerHTML = 'Discount %';
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
	};
}
function call_ajax_showlistall(mod)
{
	var atleastone 										= 0;
	var voucher_id										= '<?php echo $_REQUEST['checkbox'][0];?>';
	var cat_orders										= '';
	var fpurpose										= '';
	var retdivid										= '';
	var moredivid										= '';
	var qrystr											= '';
	switch(mod)
	{
		case 'show_voucherorder': // Case of orders linked with vouchers
			retdivid   	= 'voucherorder_div';
			fpurpose	= 'list_orders';
		break;
		case 'show_vouchercustomer': // Case of details of customer who bought the voucher
			retdivid   	= 'vouchercustomer_div';
			fpurpose	= 'voucher_customer_details';
		break;
		case 'show_voucherpayment': /* Case of showing payment details of current voucher*/
			retdivid   	= 'voucherpayment_div';
			fpurpose	= 'voucher_payment_details';
		break;
		case 'show_voucheremail': /* Case of showing emails linked with current voucher*/
			retdivid   	= 'voucheremail_div';
			fpurpose	= 'voucher_email_details';
		break;		
		case 'resendEmail': 	/* case of resending order emails*/
			retdivid   	= 'voucheremail_div';
			fpurpose	= 'resend_VoucherEmail';
			var emailid	= document.frmEditGiftVoucher.del_note_id.value
			qrystr		= 'emailid='+emailid;
		break;
		case 'show_voucheroperation': 	/* case of opetations on vouchers*/
			retdivid   	= 'voucheroperation_div';
			fpurpose	= 'show_voucheroperation';
			qrystr		= '';
		break;
		case 'operation_changevoucherpaystatus_sel':
			retdivid   	= 'additionaldet_div';
			fpurpose	= 'operation_changevoucherpaystatus_sel';
			document.getElementById('additionaldet_div').innerHTML='';
			sel_stat	= document.getElementById('cbo_voucherpaystatus').value;
			if(sel_stat=='')
			{
				document.getElementById('additionaldet_div').innerHTML='';
				return;
			}	
			qrystr		= 'sel_stat='+sel_stat;
		break;
	};
	document.getElementById('retdiv_id').value 		= retdivid;/* Name of div to show the result */	
	retobj 											= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 								= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	
	/* Calling the ajax function */
	Handlewith_Ajax('services/gift_voucher.php','fpurpose='+fpurpose+'&'+qrystr+'&voucher_id='+voucher_id);
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
			/* Decide the display of action buttons*/
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
</script>
<form name='frmEditGiftVoucher' action='home.php?request=gift_voucher' method="post" onSubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" >&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" >&nbsp;</td>
        </tr>
		
		<tr>
          <td colspan="4" align="left" valign="bottom">
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td class="seperationtd">Details of Gift Voucher</td>
              </tr>
          </table></td>
        </tr>
         
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgraynormal" >
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td  align="left" class="subcaption">Voucher Number</td>
               <td align="left" valign="top" class="listingtablestyleB"><?php echo $row_gift['voucher_number'];?></td>
               <td  align="left" class="subcaption">Created on</td>
               <td align="left" valign="top" class="listingtablestyleB"><?php echo dateFormat($row_gift['voucher_boughton'],'');?>
               <?php
               		if($row_gift['voucher_createdby']=='C') // case if created by
               		{
               			echo '(Customer)';	
               		}
               	?>               </td>
             </tr>
             <tr>
               <td align="left" class="subcaption">Payment Status</td>
               <td align="left" valign="top" class="listingtablestyleB"><?php echo getpaymentstatus_Name($row_gift['voucher_paystatus'],'');?></td>
               <td align="left" class="subcaption" nowrap="nowrap">Payment Type</td>
               <td align="left" valign="top" class="listingtablestyleB"> <?php echo getpaymenttype_Name($row_gift['voucher_paymenttype']);?></td>
             </tr>
             <?php
             	if($row_gift['voucher_paymentmethod']!='')
             	{
             ?>
             <tr>
               <td align="left" class="subcaption">Payment Method</td>
               <td align="left" valign="top" class="listingtablestyleB"><?php echo $row_gift['voucher_paymentmethod'];?></td>
               <td align="left" class="subcaption" colspan="2"></td>
               </td>
             </tr>
             <?php
             	}
             ?>
             
            <tr>
               <td align="left" class="subcaption" >Activation Date  </td>
               <td align="left" valign="top"  class="listingtablestyleB">
               <?php 
               	if($row_gift['voucher_paystatus']=='Paid')
               	{
               		if($row_gift['voucher_createdby']=='A') // case if created by admin
               		{
               ?>
		              <?php echo $row_gift['startd']?>&nbsp;&nbsp;&nbsp;
	               <?php
               		}
               		else 
               			echo dateFormat($row_gift['voucher_activatedon'],'');
               	}	
               	else 
               	{
               		echo '<span class="redtext">-- Not Activated yet -- </span>';              	
               	}
               ?>               </td>
               <td width="14%" align="left" class="subcaption" >Expires On </td>
               <td align="left"  class="listingtablestyleB">
			   <?php
               	if($row_gift['voucher_paystatus']=='Paid' or $row_gift['voucher_paystatus']=='REFUNDED')
               	{
              			 echo $row_gift['endd']; 
               	}
               	else
               	{
               		echo '<span class="redtext">-- Not Activated yet-- </span>';
               	}
               	?>
               <?php 
             /*  	if($row_gift['voucher_paystatus']=='Paid')
               	{
                echo $row_gift['endd']; 
               	}
               	else 
               	{
               		echo '<span class="redtext">-- Not Activated yet-- </span>';              	
               	} */
               	?>               </td>
             </tr>
             <tr>
               <td align="left" class="subcaption" nowrap="nowrap">Voucher Type </td>
               <td align="left" class="listingtablestyleB"><?PHP if($row_gift['voucher_type']=='val') echo " Value "; else echo " Percentage "; ?>               </td>
               <td align="left" class="subcaption">Hide</td>
               <td align="left" class="listingtablestyleB">
			 <?PHP if($row_gift['voucher_hide']==1) echo "Yes"; else echo "No"; ?>			  </td>
             </tr>
             <tr id="tr_discval" class="subcaption">
               <td align="left"><div id='dis_val'>Discount Value </div></td>  
               <td align="left" class="listingtablestyleB"><?php 
			   if($row_gift['voucher_type']=='val') {
			    echo $row_gift['voucher_curr_symbol'].$row_gift['voucher_value']; } else {
				echo $row_gift['voucher_value']."%"; 
				}
				 ?>
			   &nbsp;</td>
                
               <td align="left" class="subcaption" nowrap="nowrap">Maximum Usage</td>
               <td align="left" class="listingtablestyleB"><?php echo $row_gift['voucher_max_usage']?></td>
             </tr>
             <tr>
               <td colspan="4" align="right">&nbsp;</td>
             </tr>
             <tr>
               <td align="right">&nbsp;</td>
               <td align="right">&nbsp;</td>
             </tr>
           </table></td>
         </tr>
         <tr>
           <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="21%" align="left" valign="middle" class="tdcolorgray">		  		  </td>
          <td width="58%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td width="3%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
		<tr >
        <?php
        	// Check whether customer details tab to be displayed
        	if ($row_gift['voucher_createdby']=='C')
        	{
        ?>
		        <tr>
		          <td colspan="4" align="left" valign="bottom">
		            <table width="100%" border="0" cellspacing="1" cellpadding="1">
		            <tr>
		              <td width="100%" align="left" class="seperationtd">Details of Customer who bought this voucher</td>
		            </tr>
		          </table></td>
		        </tr>
				<tr >
				<tr id="vouchercustomer_tr" >
				  <td align="right" colspan="4" class="tdcolorgray_buttons">&nbsp;</td>
    </tr>
				<tr id="vouchercustomer_tr" >
					<td align="right" colspan="4" class="tdcolorgray_buttons">
						<div id="vouchercustomer_div" style="text-align:center"><?PHP
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
	?>
				<table width="100%" cellpadding="1" cellspacing="1" border="0">
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
				<td align="left" colspan="3" class="listingtablestyleB" valign="top"><?php echo nl2br(stripslashes($row_vouchcust['voucher_company']))?></td>
				</tr>
				</table>	
	<?php
		}
						 ?></div>					</td>
				</tr>
		<?php
        	}
		?>
		
		<tr>
          <td colspan="4" align="center" valign="bottom"><input type="button" name="Submit" value=" Print " onClick="javascript:window.print();" /></td>
		</tr>
		  </table>
</form>	  
</body></html>