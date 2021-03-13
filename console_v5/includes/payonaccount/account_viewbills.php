<?php
	/*#################################################################
	# Script Name 	: account_summary.php
	# Description 		: pay on account summary
	# Created by 		: Sny
	# Created on		: 16-Oct-2008
	# Modified by 		: 
	# Modified on		: 
	
	#################################################################*/
#Define constants for this page
$page_type 		= 'Company Type';
$help_msg 			= get_help_messages('EDIT_PAYONACC_VIEWBILL_MESS1');
$customer_id		=$_REQUEST['customer_id'];
$sql_comp 			= "SELECT customer_title,customer_fname,customer_mname,customer_surname,
										customer_payonaccount_status,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,
										(customer_payonaccount_maxlimit - customer_payonaccount_usedlimit) as remaining,
										customer_payonaccount_billcycle_day,customer_payonaccount_rejectreason,customer_payonaccount_laststatementdate
									FROM 
										customers 
									WHERE 
										customer_id = $customer_id 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
$ret_cust = $db->query($sql_comp);
if ($db->num_rows($ret_cust)==0)
{	
	echo "Sorry!! no details found";
	exit;
}	
$row_cust 		= $db->fetch_array($ret_cust);
$cust_name	= stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_mname']).' '.stripslashes($row_cust['customer_surname']);
// Find the closing balance
$sql_payonaccount = "SELECT pay_id, pay_date,pay_amount 
									FROM 
										order_payonaccount_details  
									WHERE 
										pay_id = ".$_REQUEST['pay_id']." 
										AND customers_customer_id = ".$_REQUEST['customer_id']."  
										AND pay_transaction_type ='O' 
									ORDER BY 
										pay_id DESC 
									LIMIT 
										1";
$ret_payonaccount = $db->query($sql_payonaccount);
if ($db->num_rows($ret_payonaccount))
{
	$row_payonaccount 		= $db->fetch_array($ret_payonaccount);
	$closing_balance		= $row_payonaccount['pay_amount'];
	$closing_id				= $row_payonaccount['pay_id'];
	$closing_date			= $row_payonaccount['pay_date']; 
}
else
{
	$closing_balance				= 0;										
	$closing_id						= 0;
}	
// Getting the previous outstanding details from orders_payonaccount_details 
$sql_payonaccount = "SELECT pay_id, pay_date,pay_amount 
									FROM 
										order_payonaccount_details  
									WHERE 
										pay_id < ".$_REQUEST['pay_id']." 
										AND customers_customer_id = ".$_REQUEST['customer_id']."  
										AND pay_transaction_type ='O' 
									ORDER BY 
										pay_id DESC 
									LIMIT 
										1";
$ret_payonaccount = $db->query($sql_payonaccount);
if ($db->num_rows($ret_payonaccount))
{
	$row_payonaccount 	= $db->fetch_array($ret_payonaccount);
	$prev_balance		= $row_payonaccount['pay_amount'];
	$prev_id			= $row_payonaccount['pay_id'];
}
else
{
	$prev_balance		= 0;										
	$prev_id			= 0;
}	
?>	
<script type="text/javascript">
function ajax_return_contents() 
{
	var ret_val = '';
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetobj 	= document.getElementById('maincontent');
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			/* Decide the display of action buttons*/
			hide_processing();
		}
		else
		{
		    show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
} 
function call_ajax_makepaymentl()
{
	var atleastone 			= 0;
	var customer_id			= '<?php echo $customer_id?>';
	var txt_name 			= '<?php echo $_REQUEST['txt_name']?>';
	var qrystr					= '';
	var cbo_selectlimit 		= '<?php echo $_REQUEST['cbo_selectlimit']?>';
	var limit_from 			= '<?php echo $_REQUEST['limit_from']?>';
	var limit_to 				= '<?php echo $_REQUEST['limit_to']?>';
	var cbo_payon_status 	= '<?php echo $_REQUEST['cbo_payon_status']?>';
	var pay_additional_det	= document.getElementById('pay_additional_details').value;
	var pay_amt				= document.getElementById('pay_amt').value;
	var fpurpose				= 'make_payment';
	retobj 						= eval("document.getElementById('"+document.getElementById('retdiv_id').value+"')");
	retobj.innerHTML 		= '<center><img src="images/loading.gif" alt="Loading"></center>';
	qrystr							= 'txt_name='+txt_name+'&cbo_selectlimit='+cbo_selectlimit+'&limit_from='+limit_from+'&limit_to='+limit_to+'&cbo_payon_status='+cbo_payon_status+'&pay_amt='+pay_amt+'&pay_additional_details='+pay_additional_det;
	Handlewith_Ajax('services/payonaccount.php','fpurpose='+fpurpose+'&customer_id='+customer_id+'&'+qrystr);
}
function handle_make_payment_top()
{
	if(document.getElementById('main_error_tr'))
		document.getElementById('main_error_tr').style.display = 'none';
	document.getElementById('make_pay_top_div').style.display 	= 'none';
	document.getElementById('pay_amt').value  				= '';
	document.getElementById('makepay_tr').style.display 	= '';
}
function handle_make_payment_cancel()
{
	document.getElementById('make_pay_top_div').style.display 	= 'inline';
	document.getElementById('pay_amt').value  				= '';
	document.getElementById('makepay_tr').style.display 	= 'none';
}
function validate_payment(frm)
{
	var tot_pending 		= '<?php echo $row_cust['customer_payonaccount_usedlimit']?>';
	var curr					= '<?php echo display_curr_symbol()?>';
	fieldRequired		 	= Array('pay_amt','cbo_paymethod');
	fieldDescription 		= Array('Amount','Payment Type');
	fieldEmail 				= Array();
	fieldConfirm 			= Array();
	fieldSpecChars 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 			= Array('pay_amt');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars))
	{
		payamt = parseFloat(document.getElementById('pay_amt').value);
		if (payamt>tot_pending)
		{
			alert('Sorry!! maximum amount that can be paid is '+curr+tot_pending);
			return false;
		}
		if(confirm('Are you sure you want to make the payment for  the specified amount?'))
		{
			 call_ajax_makepaymentl();
		}	
	}
}
function handle_details(id)
{
	obj 	= eval("document.getElementById('paydet_"+id+"')");
	objdet= eval("document.getElementById('paydetdiv_"+id+"')");
	if (objdet.style.display =='none')
	{
		obj .innerHTML = '<strong>Hide Additional details</strong> <img src="images/down_arr.gif" align="Details" border="0">';
		objdet.style.display = '';
	}
	else
	{
		obj .innerHTML = '<strong>Show Additional details</strong> <img src="images/right_arr.gif" align="Details" border="0">';
		objdet.style.display = 'none';
	}
}
function handle_bills()
{

	window.location = 'home.php?request=payonaccount&fpurpose=view_bills&customer_id=<?php echo $customer_id?>&pass_sort_by=<?=$_REQUEST['sort_by']?>&pass_sort_order=<?=$_REQUEST['sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_txt_name=<?=$_REQUEST['txt_name']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&pass_limit_from=<?=$_REQUEST['limit_from']?>&pass_limit_to=<?=$_REQUEST['limit_to']?>&pass_cbo_payon_status=<?=$_REQUEST['cbo_payon_status']?>&pass_start=<?=$_REQUEST['start']?>&pass_pg=<?=$_REQUEST['pg']?>';
}
</script>
<form name='frmpayonaccount_summary' action='home.php?request=payonaccount' method="post">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="txt_name" value="<?php echo $_REQUEST['txt_name']?>" />
<input type="hidden" name="cbo_selectlimit"  value="<?php echo $_REQUEST['cbo_selectlimit']?>"/>
<input type="hidden" name="limit_from" value="<?php echo $_REQUEST['limit_from']?>"/>
<input type="hidden" name="limit_to"  value="<?php echo $_REQUEST['limit_to']?>"/>
<input type="hidden" name="cbo_payon_status"  value="<?php echo $_REQUEST['cbo_payon_status']?>" />
<input type="hidden" name="customer_id"  value="<?php echo $customer_id?>" />
<input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=payonaccount&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&limit_from=<?=$_REQUEST['pass_limit_from']?>&limit_to=<?=$_REQUEST['pass_limit_to']?>&cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>">List Pay on Account Details </a> <a href="home.php?request=payonaccount&fpurpose=account_summary&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&limit_from=<?=$_REQUEST['pass_limit_from']?>&limit_to=<?=$_REQUEST['pass_limit_to']?>&cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&customer_id=<?php echo $_REQUEST['customer_id']?>">Account Summary</a> &gt;&gt; <a href="home.php?request=payonaccount&fpurpose=view_bills&customer_id=<?php echo $customer_id?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_txt_name=<?=$_REQUEST['pass_txt_name']?>&cbo_selectlimit=<?=$_REQUEST['cbo_selectlimit']?>&pass_limit_from=<?=$_REQUEST['pass_limit_from']?>&pass_limit_to=<?=$_REQUEST['pass_limit_to']?>&pass_cbo_payon_status=<?=$_REQUEST['pass_cbo_payon_status']?>&pass_start=<?=$_REQUEST['pass_start']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">View Statements</a> <span>Statement  of <strong><?php echo $cust_name?></strong> on <strong><?php echo dateFormat($closing_date,'date')?></strong></span></td>
        </tr>
       <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr id="main_error_tr">
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td width="100%" valign="top">
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
				  <td align="left" valign="middle" class="listingtablestyleB" >Customer</td>
				  <td width="34%" align="left" valign="middle" class="listingtablestyleB">: <a href="home.php?request=customer_search&fpurpose=edit&checkbox[]=<?php echo $customer_id?>" class="edittextlink"><?php echo $cust_name?></a></td>
		          <td width="16%" align="left" valign="middle" class="listingtablestyleB"><strong>Credit Limit </strong></td>
                  <td width="33%" align="left" valign="middle" class="listingtablestyleB"><strong>: <?php echo display_price($row_cust['customer_payonaccount_maxlimit'])?></strong></td>
		</tr>
		  <?php
			if($row_cust['customer_payonaccount_laststatementdate']!='0000-00-00')
			{
		?>
			<?php
			}
		?>	
			<tr>
			  <td width="17%" align="left" valign="middle" class="shoppingcartpriceB" >&nbsp;</td>
			  <td align="left" valign="middle" class="shoppingcartpriceB">&nbsp;</td>
			  <td align="left" valign="middle" class="shoppingcartpriceB">Current Account Balance</td>
		      <td align="left" valign="middle" class="shoppingcartpriceB">: <?php echo display_price($row_cust['customer_payonaccount_usedlimit'])?></td>
			</tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="homecontentusertabletdA" >Transaction in Current Statement </td>
          <td colspan="2" align="left" valign="middle" class="homecontentusertabletdA" >Statement Date <strong><?php echo dateFormat($closing_date,'date')?></strong></td>
          </tr>
        <tr>
          <td colspan="4" align="center" valign="middle" >
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
		  <?php
		  	$table_headers 		= array('Slno.','Date','Details','Amount','','');
			$header_positions	= array('left','center','left','right','center','left');
			$colspan = count($table_headers);
			 echo table_header($table_headers,$header_positions);
			 
			 // Get the list of transactions to be displayed here
		 	$where_add = " AND pay_id>=$prev_id AND pay_id<".$_REQUEST['pay_id']." ";
			$sql_payondetails = "SELECT pay_id,pay_date,customers_customer_id,pay_amount,pay_transaction_type,pay_details,orders_order_id,pay_additional_details  
			 									FROM 
													order_payonaccount_details 
												WHERE 
													customers_customer_id = $customer_id 
													$where_add 
												ORDER BY 
													pay_date ASC";
				$ret_payondetails = $db->query($sql_payondetails);
				if ($db->num_rows($ret_payondetails))
				{		
					$srno = 0;
					while ($row_payondetails = $db->fetch_array($ret_payondetails))
					{
						$cls = ($srno%2==0)?'listingtablestyleB':'listingtablestyleA';
						$srno++;
		  ?>
						<tr>
						  <td align="left" width="2%" class="<?php echo $cls?>"><?php echo $srno?>.</td>
						  <td width="15%" align="center" class="<?php echo $cls?>"><?php echo dateFormat($row_payondetails['pay_date'],'datetime');?></td>
						  <td width="38%" align="left" class="<?php echo $cls?>">
						  <?php 
						  if($row_payondetails['orders_order_id']!=0)
						  {
						  	$link_from = '<a href="home.php?request=orders&fpurpose=ord_details&edit_id='.$row_payondetails['orders_order_id'].'" class="edittextlink" title="Click for order details">';;
							$link_to		= '</a>';
						  }
						  else
						  {
						  	$link_from = '';
							$link_to		= '';
						  }
						  	echo $link_from.stripslashes($row_payondetails['pay_details']).$link_to;
						  ?>						  </td>
						  <td width="15%" align="right" class="<?php echo $cls?>"><?php echo display_price($row_payondetails['pay_amount']);?></td>
						  <td align="center"  class="<?php echo $cls?>" width="5%">
						  <?php
								  if($row_payondetails['pay_transaction_type']=='C')
									echo ' <strong>(Cr.)</strong>';
							?>						</td>
						  <td width="25%" align="left" class="<?php echo $cls?>">
						  <?php
						  	if($row_payondetails['pay_additional_details']!='')
							{
						  ?>
						  		<div id="paydet_<?php echo $row_payondetails['pay_id']?>" onClick="handle_details('<?php echo $row_payondetails['pay_id']?>')" style="padding-left:50px; cursor:pointer">
								<strong>Show Additional details</strong> <img src="images/right_arr.gif" align="Details" border="0">								</div>						  
						        <?php
						  }?>						  </td>
						</tr>
						
						<?php
						// Check whether there exists any additional details added for current entry
						if($row_payondetails['pay_additional_details']!='')
						{
						?>
						<tr id="paydetdiv_<?php echo $row_payondetails['pay_id']?>" style="display:none">
						<td colspan="4">&nbsp;						</td>
						<td colspan="2" class="listingtablestyleB">
						<?php
							echo stripslashes(nl2br($row_payondetails['pay_additional_details']));
						?>						</td>
						</tr>
			<?php
						}
					}
					?>
					<tr>
					<td colspan="3" align="right" class="homecontentsugtabletdA">&nbsp;					</td>
					<td align="right" class="homecontentusertabletdA">
					----------------------------					</td>
					<td align="right" colspan="2">					</td>
					</tr>
					<tr>
					<td colspan="3" align="right" class="homecontentusertabletdA">
					Closing Balance					</td>
					<td align="right" class="homecontentusertabletdA">
					<?php echo display_price($closing_balance)?>					</td>
					<td align="right" colspan="2">					</td>
					</tr>
					<tr>
					<td colspan="3" align="right" class="homecontentsugtabletdA">&nbsp;					</td>
					<td align="right" class="homecontentusertabletdA">
					==============					</td>
					<td align="right" colspan="2">					</td>
					</tr>
					<?php
				}
				else
				{
			?>
					<tr>
             		 <td  align="center" class="redtext" colspan="<?php echo $colspan?>">
					 	Sorry!! no pay on account details found.</td>
					 </tr>
			<?php	
				}
				
			?>
          </table></td>
        </tr>
		</table>	
		</div>
			</td>
		</tr>
  </table>
</form>	  

