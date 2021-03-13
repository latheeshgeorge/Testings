<?php
	/*############################################################################
	# Script Name 	: payonaccountHtml.php
	# Description 		: Page which holds the display logic for My Payon Account Details
	# Coded by 		: LH
	# Created on		: 20-Oct-2008
	# Modified by		: Sny
	# Modified on		: 08-Dec-2008
	##########################################################################*/
	class payonaccountHtml
	{
		  function show_Summary($alert='')
		  {
					global $insmsg, $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
					$session_id = session_id();	// Get the session id for the current section
					$customer_id = get_session_var('ecom_login_customer');
					$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC');
					
					$sql_comp 			= "SELECT customer_title,customer_fname,customer_mname,customer_surname,
													customer_payonaccount_status,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,
													(customer_payonaccount_maxlimit - customer_payonaccount_usedlimit) as remaining,
													customer_payonaccount_billcycle_day,customer_payonaccount_rejectreason,customer_payonaccount_laststatementdate,
													customer_payonaccount_billcycle_day 
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
				// Getting the previous outstanding details from orders_payonaccount_details 
				$sql_payonaccount = "SELECT  pay_id,pay_date,pay_amount 
													FROM 
														order_payonaccount_details  
													WHERE 
														customers_customer_id = $customer_id 
														AND pay_transaction_type ='O' 
													ORDER BY 
														pay_id DESC 
													LIMIT 
														1";
				$ret_payonaccount = $db->query($sql_payonaccount);
				if ($db->num_rows($ret_payonaccount))
				{
					$row_payonaccount 	= $db->fetch_array($ret_payonaccount);
					$prev_balance				= $row_payonaccount['pay_amount'];
					$prev_id					= $row_payonaccount['pay_id'];
					$prev_date					= $row_payonaccount['pay_date'];
				}
				else
				{
					$prev_balance				= 0;										
					$prev_id						= 0;
				}
	
	
			?>
			<script type="text/javascript">
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
				var tot_pending 				= '<?php echo convertPrice_to_selectedCurrrency($row_cust['customer_payonaccount_usedlimit'])?>';
				var curr_disptot				= '<?php echo print_price($row_cust['customer_payonaccount_usedlimit'])?>';
				fieldRequired 		= Array('pay_amt');
	
				fieldDescription 	= Array('Amount');
			
				fieldEmail 			= Array();
			
				fieldConfirm 		= Array();
			
				fieldConfirmDesc  	= Array();
				
				fieldNumeric 			= Array('pay_amt');
			
				if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
				{
				payamt = parseFloat(document.getElementById('pay_amt').value);
				tot_pending = roundNumber(tot_pending,2);
				if (payamt>tot_pending)
				{
					alert('Sorry!! maximum amount that can be paid is '+curr_disptot);
					return false;
				}
				if(confirm('Are you sure you want to make the payment for  the specified amount?'))
				{
					 frm.submit();
				}
					return true;
				}
				else
			
				{
					return false;
				}
				
	
			}
			</script>
			<form name='frmpayonaccount_summary' action='<?php url_link('payonaccountpayment.html')?>' method="post">
	
				 <table width="100%" border="0" cellpadding="3" cellspacing="0"  class="tbl_float">
				  <tr>
					<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['PAYONACC']['PAYONACCOUNT_MAINHEADING']?></div>			    </td>
				  </tr>
				 <? 
				  if($alert!='')
				  {
				  ?>
				   <tr>
					<td colspan="6" align="center" valign="middle" class="errormsg"><?php echo $Captions_arr['PAYONACC'][$alert]?></td>
				  </tr>
				 <? } ?>
				  <tr>
				  <td colspan="6" >
					  <table border="0" cellpadding="0" cellspacing="0" width="100%">
						  <tr>
							  <td width="13%" align="left" valign="middle" class="userordercontent"><?php echo $Captions_arr['PAYONACC']['CREDIT_LIMIT']?></td>
							  <td width="33%" align="left" valign="middle" class="userordercontent">: <?php echo print_price($row_cust['customer_payonaccount_maxlimit'])?></td>
							  <td width="26%" align="left" valign="middle" class="userordercontent"><?php echo $Captions_arr['PAYONACC']['CURRENTACC_BALANCE']?></td>
						  <td width="28%"  align="left" valign="middle" class="userordercontent">: <?php echo print_price($row_cust['customer_payonaccount_usedlimit'],true)?></td>
						</tr>
					   </table>
				   </td>
				   </tr>
				   <?
				 $where_conditions = " WHERE 
															a.sites_site_id=$ecom_siteid 
															AND customers_customer_id=$customer_id 
															AND pay_transaction_type ='O' ";
						
					//#Select condition for getting total count
					$sql_billcount = "SELECT pay_id  remaining  FROM order_payonaccount_details a $where_conditions";
					$res_billcount = $db->query($sql_billcount);
					$numbillcount = $db->num_rows($res_billcount);
				
					 if($row_cust['customer_payonaccount_usedlimit']>0 || $numbillcount>0)
					 {
					?>
					
				   <tr>
				   <td colspan="2"></td><!--onclick="window.location='<?php //url_link('payonaccountpayment.html')?>'"-->
					<td align="right"><div id="make_pay_top_div"><? if($row_cust['customer_payonaccount_usedlimit']>0){?><input  type="button" name="make_payment" value="<?php echo $Captions_arr['PAYONACC']['MAKEPAY_BUTTON']?>" class="buttonred_cart" onclick="handle_make_payment_top()" />
					<? }?></div></td>
					 <td colspan="2"><? if($numbillcount>0){?><input type="button" name="view_statements" title="View Statements" value="<?php echo $Captions_arr['PAYONACC']['VIEWSTATEMENT_BUTTON']?>" class="buttonred_cart" onclick="window.location='<?php url_link('accountviewbills.html')?>'" /><? }?>
					</td>
				   </tr>
				   <? }?>
				   <tr id="makepay_tr" style="display:none;">
					<td colspan="6" align="center">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
							<td width="10%" >&nbsp;</td>
							<td align="center" width="75%">
									<table cellspacing="4" cellpadding="4" border="0" width="100%" bgcolor="#D9D9D9">
										<tr>
										<td  align="left" valign="top" width="15%" colspan="2"  class="pagingcontainertd">
										 <?php echo $Captions_arr['PAYONACC']['MAKEPAY_FILLMSG']?>  </td>
										
										</tr>
										<tr>
										<td  align="left" valign="top" width="15%" class="redtext" >
										 <?php echo $Captions_arr['PAYONACC']['AMOUNT']?><span class="redtext">*</span></td>
										 <td width="41%"  align="left"   >
										 <input name="pay_amt" type="text" id="pay_amt"  value=""/> 
										 </td>
										</tr>
										<tr>
										<td  align="left" valign="top" width="29%" class="redtext"  ><?php echo $Captions_arr['PAYONACC']['ADDITIONAL_DETAILS']?></td>
										<td align="left"   width="71%" ><textarea name="pay_additional_details" id="pay_additional_details" cols="30" rows="5"></textarea>
										  </td>
										</tr>
										<tr>
										  <td  valign="middle" align="center"  > <input name="Cancel" type="button" class="buttonred_cart" id="cancel" value="<?php echo $Captions_arr['PAYONACC']['CANCEL_BUTTON']?>" onclick="handle_make_payment_cancel()"  /></td>
										  <td  align="left" valign="middle"  ><input name="make_payment" type="button" class="buttonred_cart" id="make_payment" value="<?php echo $Captions_arr['PAYONACC']['MAKEPAY_BUTTON']?>" onclick="validate_payment(this.form)" /> </td>
										</tr>
								</table>
							  </td>
								<td width="14%" >&nbsp;</td>
							</tr>
						</table>
					</td>
					</tr>
					<tr>
					<td align="left" class="prod_orderheader" colspan="6">
					<?php echo $Captions_arr['PAYONACC']['UNBILLED_TRANSACTION']?> 			
					</td>
					</tr>
				  <tr>
					<td align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['SLNO']?></td>
					<td align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['PAYONACC_DATE']?></td>
					 <td   align="middle" valign="middle" class="ordertableheader"><?php echo $Captions_arr['PAYONACC']['PAYONACC_DETAIL']?></td>
					 <td  align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['AMOUNT']?></td>
					 <td  align="middle" valign="middle" class="ordertableheader" >&nbsp;</td>
					 <td  align="middle" valign="middle" class="ordertableheader" width="18%">&nbsp;</td>
				  </tr>
			<?
			 if($prev_id) // case if atleast one statement exists for current customer
				 {
					$where_add = " AND pay_id>$prev_id ";
				 }
				 else
				 {
					$where_add = ' ';
				 }
			$sql_payondetails = "SELECT pay_id,pay_date,customers_customer_id,pay_amount,pay_transaction_type,pay_details,orders_order_id,pay_additional_details  
													FROM 
														order_payonaccount_details 
													WHERE 
														customers_customer_id = $customer_id 
														$where_add 
														AND pay_transaction_type != 'O' 
													ORDER BY 
														pay_date ASC";
					$ret_payondetails = $db->query($sql_payondetails);
					if ($db->num_rows($ret_payondetails))
					{		
						$srno = 0;
						while ($row_payondetails = $db->fetch_array($ret_payondetails))
						{
							 ($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
							$srno++;
						?>
						<tr onclick="" style="" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
							<td align="middle" valign="middle" class="<?=$cls?>" > <?php echo $srno?>.</td>
							<td align="middle" valign="middle" class="<?=$cls?>"><?php echo dateFormat($row_payondetails['pay_date'],'datetime');?></td>
							<td align="middle" valign="middle" class="<?=$cls?>">
							<?php 
							$ord_id = $row_payondetails['orders_order_id'];
							  if($row_payondetails['orders_order_id']!=0)
							  { 
								$link_from = '<a href="index.php?req=orders&reqtype=order_det&order_id='.$ord_id.'" class="favoriteprodlink" title="Click for order details" style="cursor:pointer">';;
								$link_to		= '</a>';
							  }
							  else
							  {
								$link_from = '';
								$link_to		= '';
							  }
								echo $link_from.stripslashes($row_payondetails['pay_details']).$link_to;
							  ?>	 	</td>
							<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"><?php echo print_price($row_payondetails['pay_amount']);?></td>
							 <td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"> <?php
									  if($row_payondetails['pay_transaction_type']=='C')
										echo ' <strong>(Cr.)</strong>';
								?>	</td>
								<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap">
								<?php
								if($row_payondetails['pay_additional_details']!='')
								{
							  ?>
									<div id="accpaydet_<?php echo $row_payondetails['pay_id']?>" onClick="handle_accountdetails('<?php echo $row_payondetails['pay_id']?>')" style="padding-left:0px; cursor:pointer">
									<strong>Show Details</strong> </div>						  
									<?php
							  }?>		</td>
						</tr>
						<?php
							// Check whether there exists any additional details added for current entry
							if($row_payondetails['pay_additional_details']!='')
							{
							?>
							<tr id="accpaydetdiv_<?php echo $row_payondetails['pay_id']?>" style="display:none">
								<td colspan="3" class="<?=$cls?>" align="left" valign="middle">&nbsp;</td>
							<td colspan="3" class="show_details_td" align="left" valign="middle" >
							<?php
								echo stripslashes(nl2br($row_payondetails['pay_additional_details']));
							?>						</td>
							</tr>
				<?php
							}
						
						}
					}
					else
					{
					?>
								 <tr>
										<td align="center" valign="middle" class="shoppingcartcontent" colspan="6" >
											<?php echo $Captions_arr['PAYONACC']['PAYONACC_NOTFOUNDORDER']?></td>
								</tr>
					<?
					}		
				?> 			
				  </table>
				  </form>
				  
			<?php 
				 
		  }
		  function view_Statements()
		  {
		   global $insmsg, $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
					$session_id = session_id();	// Get the session id for the current section
					$customer_id = get_session_var('ecom_login_customer');
					$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC');
					
									//#Search terms
					$limit_from = $_REQUEST['limit_from'];
					$limit_to = $_REQUEST['limit_to'];
					$search_fields = array('limit_from','limit_to');
					foreach($search_fields as $v) {
						$query_string .= "$v=${$v}&";#For passing searh terms to javascript for passing to different pages.
					}
						
					//#Sort
					$sort_by = (!$_REQUEST['sort_by'])?'pay_date':$_REQUEST['sort_by'];
					$sort_order = (!$_REQUEST['sort_order'])?'DESC':$_REQUEST['sort_order'];
					$sort_options = array('pay_date' => 'Statement Date');
					$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
					$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
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
					//#Search Options
					
					
					$where_conditions = " WHERE 
															a.sites_site_id=$ecom_siteid 
															AND customers_customer_id=$customer_id 
															AND pay_transaction_type ='O' ";
						$min_exists 			= $max_exists = false;
						$min 						= trim($_REQUEST['limit_from']);
						$max						= trim($_REQUEST['limit_to']);
						$between_column 	= '';
						
						if($_REQUEST['limit_from'] && $_REQUEST['limit_to'] ) {
						$fromdate_arr = explode("-",add_slash($_REQUEST['limit_from']));
						$fromdate = $fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
						$todate_arr = explode("-",add_slash($_REQUEST['limit_to']));
						$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
						$where_conditions .= "AND (pay_date >= '".$fromdate."' AND pay_date <= '".$todate."' )";
					}
					if($_REQUEST['limit_from'] && $_REQUEST['limit_to']=='' ) {
						$fromdate_arr = explode("-",add_slash($_REQUEST['limit_from']));
						$fromdate =$fromdate_arr[2]."-".$fromdate_arr[1]."-".$fromdate_arr[0];
						$where_conditions .= "AND (pay_date >= '".$fromdate."')";
					}
					if($_REQUEST['limit_from']=='' && $_REQUEST['limit_to'] ) {
						$todate_arr = explode("-",add_slash($_REQUEST['limit_to']));
						$todate =$todate_arr[2]."-".$todate_arr[1]."-".$todate_arr[0];
						$where_conditions .= "AND (pay_date <= '".$todate."' )";
					}
					
					//#Select condition for getting total count
					$sql_count = "SELECT pay_id  remaining  FROM order_payonaccount_details a $where_conditions";
					$res_count = $db->query($sql_count);
					$numcount = $db->num_rows($res_count);
					 
					 $bills_per_page = $Settings_arr['payon_maxcntperpage_statements'];#Total records shown in a page
			/////////////////////////////////For paging///////////////////////////////////////////
			
			// Call the function which prepares variables to implement paging
									$ret_arr 		= array();
									$pg_variable	= 'bill_pg';
									if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
									{
										$start_var 		= prepare_paging($_REQUEST[$pg_variable],$bills_per_page,$numcount);
										$Limit			= " LIMIT ".$start_var['startrec'].", ".$bills_per_page;
									}	
									else
										$Limit = '';				
		  ?>
		   <form method="post" name="frm_viewbills" class="frm_cls" action="<?php url_link('accountviewbills.html')?>">
				  <table width="100%" border="0" cellpadding="3" cellspacing="0" >
						  <tr>
							<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_link('mypayonaccountpayment.html');?>"><?php echo $Captions_arr['PAYONACC']['PAYONACCOUNT_MAINHEADING']?></a> >> <?php echo $Captions_arr['PAYONACC']['VIEWBILLS_MAINHEADING']?></div>			    </td>
						  </tr>
						  <tr>
					<td colspan="12" align="left" valign="middle"  ><br />
					<table  border="0" cellpadding="2" cellspacing="3" width="100%" class="userordertablestyleA">
					  <tr>
						<td colspan="5" nowrap="nowrap" class="usermenucontent"><?php echo $Captions_arr['PAYONACC']['VIEWBILLS_BETDATE']?> </td>
						<td colspan="4" nowrap="nowrap" class="usermenucontent"><?php echo $Captions_arr['PAYONACC']['VIEWBILLS_SORTBY']?></td>
					  </tr>
					  <tr>
						<td width="11%" nowrap="nowrap" class="usermenucontent"><input name="limit_from" class="textfeild" type="text" size="8" value="<?php echo $_REQUEST['limit_from']?>" /></td>
						<td width="6%" nowrap="nowrap"><a href="javascript:show_calendar('frm_viewbills.limit_from');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
						<td width="5%" nowrap="nowrap" class="usermenucontent">and</td>
						<td width="11%" nowrap="nowrap" class="usermenucontent">
						  <input name="limit_to" class="textfeild" id="limit_to" type="text" size="8" value="<?php echo $_REQUEST['limit_to']?>" />
						</td>
						<td width="10%" nowrap="nowrap"><a href="javascript:show_calendar('frm_viewbills.limit_to');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
						<td width="7%" nowrap="nowrap"><?php echo $sort_option_txt;?></td>
						<td width="5%" nowrap="nowrap" class="usermenucontent">In</td>
						<td width="7%" nowrap="nowrap"><?php echo $sort_by_txt?></td>
						<td width="38%" nowrap="nowrap" class="usermenucontent">
						  <input name="Search_go" type="submit" class="buttongray" id="Search_go" value="Go" onclick="document.frm_viewbills.search_click.value=1" />
						</td>
					  </tr>
					  <!--<tr>
						<td colspan="9" nowrap="nowrap"><a href="javascript:show_calendar('frm_orders.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a><a href="javascript:show_calendar('frm_orders.ord_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
					  </tr>-->
					</table></td>
				  </tr>
				  <?
					if($numcount)
					{
				   ?>
				  <tr>
					<td colspan="2" align="center" valign="middle" class="pagingcontainertd" ><?php 
															$path = '';
															$query_string .= "sort_by=".$_REQUEST['sort_by']."&sort_order=".$_REQUEST['sort_order']."";
															paging_footer($path,$query_string,$numcount,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Statements',$pageclass_arr); 	
														?></td>
				  </tr>
				  <? }?>
						   <tr>
							<td align="middle" valign="middle" class="ordertableheader" width="10%" ><?php echo $Captions_arr['PAYONACC']['SLNO']?></td>
							<td align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['STATEMENT_DATE']?></td>
							</tr> 
							<?
								if($numcount)
								{
									$sql_payoncust = "SELECT a.pay_id,a.pay_date,a.orders_order_id  
														FROM 
															order_payonaccount_details a 
														$where_conditions 
														ORDER BY 
															$sort_by $sort_order 
														$Limit ";
									
									$res = $db->query($sql_payoncust);
									$srno = 1; 
									$tot_used = 0;
									while($row = $db->fetch_array($res))
									{   $pay_id = $row['pay_id'];
										$count_no++;
										
										$array_values = array();
										($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
										$tot_used += $row['customer_payonaccount_usedlimit'];
										 ?>
										<tr onclick="" style="" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
												<td align="middle" valign="middle" class="<?=$cls?>" > <?php echo $srno?>.</td>
												<td align="middle" valign="middle" class="<?=$cls?>" onclick="window.location='<?php url_link('accountbill'.$pay_id.'-details.html')?>'" style="cursor:pointer;"><?php echo dateFormat($row['pay_date'],'datetime');?></a></td>
										</tr>
										<?
										$srno++;
									}
								}
								else
								{
								?>
								 <tr>
										<td align="center" valign="middle" class="shoppingcartcontent" colspan="2" >
											<?php echo $Captions_arr['PAYONACC']['VIEWBILLS_NOTFOUNDORDER']?></td>
								</tr>
								<?
								}
								?>
						   
				</table>
				</form>
		  <?
		  }
		  function statement_Details()
		  {
		   global $insmsg, $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
					$session_id = session_id();	// Get the session id for the current section
					$customer_id = get_session_var('ecom_login_customer');
					$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC');
	
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
															AND customers_customer_id = ".$customer_id."  
															AND pay_transaction_type ='O' 
														ORDER BY 
															pay_id DESC 
														LIMIT 
															1";
					$ret_payonaccount = $db->query($sql_payonaccount);
					if ($db->num_rows($ret_payonaccount))
					{
						$row_payonaccount 		= $db->fetch_array($ret_payonaccount);
						$closing_balance				= $row_payonaccount['pay_amount'];
						$closing_id						= $row_payonaccount['pay_id'];
						$closing_date					= $row_payonaccount['pay_date']; 
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
															AND customers_customer_id = ".$customer_id."  
															AND pay_transaction_type ='O' 
														ORDER BY 
															pay_id DESC 
														LIMIT 
															1";
					$ret_payonaccount = $db->query($sql_payonaccount);
					if ($db->num_rows($ret_payonaccount))
					{
						$row_payonaccount 	= $db->fetch_array($ret_payonaccount);
						$prev_balance				= $row_payonaccount['pay_amount'];
						$prev_id						= $row_payonaccount['pay_id'];
					}
					else
					{
						$prev_balance				= 0;										
						$prev_id						= 0;
					}	
					?>
					 <table width="100%" border="0" cellpadding="3" cellspacing="0" >
						<tr>
								<td colspan="6" align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_link('mypayonaccountpayment.html');?>">  <?php echo $Captions_arr['PAYONACC']['PAYONACCOUNT_MAINHEADING']?></a> >> <a href="<? url_link('accountviewbills.html');?>">  <?php echo $Captions_arr['PAYONACC']['VIEWBILLS_MAINHEADING']?></a> >> <?php echo $Captions_arr['PAYONACC']['VIEWBILLS_STATEMENT_ON']?> <?php echo dateFormat($closing_date,'datetime');?></div>			    </td>
						 </tr>
						  <tr>
							  <td colspan="6" >
								  <table border="0" cellpadding="0" cellspacing="0" width="100%">
									  <tr>
										  <td width="13%" align="left" valign="middle" class="userordercontent"><?php echo $Captions_arr['PAYONACC']['CREDIT_LIMIT']?> </td>
										  <td width="33%" align="left" valign="middle" class="userordercontent">: <?php echo print_price($row_cust['customer_payonaccount_maxlimit'])?></td>
										  <td width="26%" align="left" valign="middle" class="userordercontent"><?php echo $Captions_arr['PAYONACC']['CURRENTACC_BALANCE']?></td>
									  <td width="28%"  align="left" valign="middle" class="userordercontent">: <?php echo print_price($row_cust['customer_payonaccount_usedlimit'])?></td>
									</tr>
									 <tr>
										 <td colspan="2" class="prod_orderheader"><?php echo $Captions_arr['PAYONACC']['CURRENTST_TRANSACTION_MESS']?></td>
										 <td  class="prod_orderheader" align="right"><?php echo $Captions_arr['PAYONACC']['VIEWBILLS_STATEMENT_ON']?></td>
										 <td  class="prod_orderheader">&nbsp;:&nbsp;<?php echo dateFormat($closing_date,'datetime');?></td>
									 </tr>
								   </table>
							   </td>
						</tr>
				  
						 <tr>
					<td align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['SLNO']?></td>
					<td align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['PAYONACC_DATE']?></td>
					 <td   align="middle" valign="middle" class="ordertableheader"><?php echo $Captions_arr['PAYONACC']['PAYONACC_DETAIL']?></td>
					 <td  align="middle" valign="middle" class="ordertableheader" ><?php echo $Captions_arr['PAYONACC']['AMOUNT']?></td>
					 <td  align="middle" valign="middle" class="ordertableheader" >&nbsp;</td>
					 <td  align="middle" valign="middle" class="ordertableheader" width="18%">&nbsp;</td>
				  </tr>
						 <? 
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
							
							 ($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
							$srno++;
						?>
						<tr onclick="" style="" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
							<td align="middle" valign="middle" class="<?=$cls?>" > <?php echo $srno?>.</td>
							<td align="middle" valign="middle" class="<?=$cls?>"><?php echo dateFormat($row_payondetails['pay_date'],'datetime');?></td>
							<td align="middle" valign="middle" class="<?=$cls?>">
							<?php 
							$ord_id = $row_payondetails['orders_order_id'];
							  if($row_payondetails['orders_order_id']!=0)
							  { 
								$link_from = '<a href="index.php?req=orders&reqtype=order_det&order_id='.$ord_id.'" class="favoriteprodlink" title="Click for order details" style="cursor:pointer">';;
								$link_to		= '</a>';
							  }
							  else
							  {
								$link_from = '';
								$link_to		= '';
							  }
								echo $link_from.stripslashes($row_payondetails['pay_details']).$link_to;
							  ?>	 	</td>
							<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"><?php echo print_price($row_payondetails['pay_amount']);?></td>
							 <td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"> <?php
									  if($row_payondetails['pay_transaction_type']=='C')
										echo ' <strong>(Cr.)</strong>';
								?>	</td>
								<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap">
								<?php
								if($row_payondetails['pay_additional_details']!='')
								{
							  ?>
									<div id="accpaydet_<?php echo $row_payondetails['pay_id']?>" onClick="handle_accountdetails('<?php echo $row_payondetails['pay_id']?>')" style="padding-left:0px; cursor:pointer">
									<strong><? $Captions_arr['PAYONACC']['SHOW_DETAILS']?></strong> </div>						  
									<?php
							  }?>		</td>
						</tr>
						<?php
							// Check whether there exists any additional details added for current entry
							if($row_payondetails['pay_additional_details']!='')
							{
							?>
							<tr id="accpaydetdiv_<?php echo $row_payondetails['pay_id']?>" style="display:none" >
								<td colspan="3" class="<?=$cls?>" align="left" valign="middle">&nbsp;</td>
							<td colspan="3" class="<?=$cls?>" align="left" valign="middle" bgcolor="#D9D9D9">
							<?php
								echo stripslashes(nl2br($row_payondetails['pay_additional_details']));
							?>						</td>
							</tr>
				<?php
							}
						
						
						}
					}		
						 ?>
					 </table>
					<?
			
		  }
	  		// Defining function to show the site review
		function take_payment_details()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$product_id,$product_name,$alert,$sitesel_curr,$default_Currency_arr,$ecom_testing,$protectedUrl;
			$customer_id 						= get_session_var("ecom_login_customer"); // get the id of current customer from session
			// Get the zip code for current customer
			$sql_cust = "SELECT customer_postcode 
								FROM 
									customers 
								WHERE 
									customer_id = $customer_id 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
			$ret_cust = $db->query($sql_cust);
			if ($db->num_rows($ret_cust))
			{
				$row_cust 					= $db->fetch_array($ret_cust);
				$cust_zipcode				= stripslashes($row_cust['customer_postcode']);
			}	
			$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC'); // Getting the captions to be used in this page
			$sess_id								= session_id();
			
			if($protectedUrl)
				$http = url_protected('payonaccountpayment.html',1);
			else 	
				$http = url_link('payonaccountpayment.html',1);	
			
			// Get the details from payonaccount_cartvalues for current site in current session 
			$pay_cart = payonaccount_CartDetails($sess_id);							
			if($_REQUEST['rt']==1) // This is to handle the case of returning to this page by clicking the back button in browser
				$alert = 'PAYON_ERROR_OCCURED';			
			elseif($_REQUEST['rt']==2) // case of image verification failed
				$alert = 'PAYON_IMAGE_VERIFICATION_FAILED';
			
			$sql_comp 			= "SELECT customer_title,customer_fname,customer_mname,customer_surname,customer_email_7503,
										customer_payonaccount_status,customer_payonaccount_maxlimit,customer_payonaccount_usedlimit,
										(customer_payonaccount_maxlimit - customer_payonaccount_usedlimit) as remaining,
										customer_payonaccount_billcycle_day,customer_payonaccount_rejectreason,customer_payonaccount_laststatementdate,
										customer_payonaccount_billcycle_day 
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
				echo "Sorry!! Invalid input";
				exit;
			}	
			$row_cust 		= $db->fetch_array($ret_cust);
			// Getting the previous outstanding details from orders_payonaccount_details 
			$sql_payonaccount = "SELECT  pay_id,pay_date,pay_amount 
												FROM 
													order_payonaccount_details  
												WHERE 
													customers_customer_id = $customer_id 
													AND pay_transaction_type ='O' 
												ORDER BY 
													pay_id DESC 
												LIMIT 
													1";
			$ret_payonaccount = $db->query($sql_payonaccount);
			if ($db->num_rows($ret_payonaccount))
			{
				$row_payonaccount 	= $db->fetch_array($ret_payonaccount);
				$prev_balance				= $row_payonaccount['pay_amount'];
				$prev_id						= $row_payonaccount['pay_id'];
				$prev_date					= $row_payonaccount['pay_date'];
			}
			else
			{
				$prev_balance				= 0;										
				$prev_id						= 0;
			}	
			
			$paying_amt 		= ($_REQUEST['pay_amt'])?$_REQUEST['pay_amt']:$pay_cart['pay_amount'];
			$additional_det	= ($_REQUEST['pay_additional_details'])?$_REQUEST['pay_additional_details']:$pay_cart['pay_additional_details'];
			
			
			 	// Check whether google checkout is required
			$sql_google = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
										  a.paymethod_takecarddetails,a.payment_minvalue,
										  b.payment_method_sites_caption 
									FROM 
										payment_methods a,
										payment_methods_forsites b 
									WHERE 
										a.paymethod_id=b.payment_methods_paymethod_id 
										AND a.paymethod_showinpayoncredit=1  
										AND sites_site_id = $ecom_siteid 
										AND paymethod_key='GOOGLE_CHECKOUT' 
									LIMIT 
										1";
			$ret_google = $db->query($sql_google);
			if($db->num_rows($ret_google))
			{
				$google_exists = true;
			}
			else 	
				$google_exists = false;
			
			$sql_paymethods = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
												  a.paymethod_takecarddetails,a.payment_minvalue,a.paymethod_ssl_imagelink,
												  b.payment_method_sites_caption 
											FROM 
												payment_methods a,
												payment_methods_forsites b 
											WHERE 
												a.paymethod_id=b.payment_methods_paymethod_id 
												AND a.paymethod_showinpayoncredit = 1
												AND paymethod_key<>'GOOGLE_CHECKOUT' 
												AND b.sites_site_id=$ecom_siteid";
			$ret_paymethods = $db->query($sql_paymethods);
			$totpaycnt = $db->num_rows($ret_paymethods);			
			if ($totpaycnt==0)
			{
				$paytype_moreadd_condition = " AND a.paytype_code <> 'credit_card'";
			}
			else
				$paytype_moreadd_condition = '';		
		?>
		<script type="text/javascript">
		/* Function to be triggered when selecting the credit card type*/
function sel_credit_card_payonaccount(obj)
{
	if (obj.value!='')
	{
		objarr = obj.value.split('_');
		if(objarr.length==4) /* if the value splitted to exactly 4 elements*/
		{
			var key 			= objarr[0];
			var issuereq 	= objarr[1];
			var seccount 	= objarr[2];
			var cc_count 	= objarr[3];
			if (issuereq==1)
			{
				document.frm_payonaccount_payment.checkoutpay_issuenumber.className = 'inputissue_normal';
				document.frm_payonaccount_payment.checkoutpay_issuenumber.disabled	= false;
			}
			else
			{
				document.frm_payonaccount_payment.checkoutpay_issuenumber.className = 'inputissue_disabled';	
				document.frm_payonaccount_payment.checkoutpay_issuenumber.disabled	= true;
			}
		}
	}
}
function handle_paytypeselect_payonaccount(obj)
{
	var curpaytype = paytype_arr[obj.value];
	var ptypecnts = <?php echo $totpaycnt?>;
	if (curpaytype=='credit_card')
	{
		if(document.getElementById('payonaccount_paymethod_tr'))
			document.getElementById('payonaccount_paymethod_tr').style.display = '';	
		if(document.getElementById('payonaccount_cheque_tr'))
			document.getElementById('payonaccount_cheque_tr').style.display = 'none';	
		if(document.getElementById('payonaccount_self_tr'))
			document.getElementById('payonaccount_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = 1;
		if (document.getElementById('payonaccount_paymethod'))
		{
			var lens = document.getElementById('payonaccount_paymethod').length;	
			if(lens==undefined && ptypecnts==1)
			{
				var curval	 = document.getElementById('payonaccount_paymethod').value;
				cur_arr 		= curval.split('_');
				if ((cur_arr[0]=='SELF' || cur_arr[0]=='PROTX') && cur_arr.length<=2)
				{
					if(document.getElementById('payonaccount_cheque_tr'))
						document.getElementById('payonaccount_cheque_tr').style.display = 'none';
					if(document.getElementById('payonaccount_self_tr'))
						document.getElementById('payonaccount_self_tr').style.display 	= '';		
				}
				else
				{
					if(document.getElementById('payonaccount_self_tr'))
						document.getElementById('payonaccount_self_tr').style.display 	= 'none';
				}	
			}	
		}	
	}
	else if(curpaytype=='cheque')
	{
		if(document.getElementById('payonaccount_paymethod_tr'))
			document.getElementById('payonaccount_paymethod_tr').style.display = 'none';		
		if(document.getElementById('payonaccount_cheque_tr'))
			document.getElementById('payonaccount_cheque_tr').style.display = '';	
		if(document.getElementById('payonaccount_self_tr'))
			document.getElementById('payonaccount_self_tr').style.display = 'none';	
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if(curpaytype=='invoice')
	{
		if(document.getElementById('payonaccount_paymethod_tr'))
			document.getElementById('payonaccount_paymethod_tr').style.display = 'none';		
		if(document.getElementById('payonaccount_cheque_tr'))
			document.getElementById('payonaccount_cheque_tr').style.display = 'none';
		if(document.getElementById('payonaccount_self_tr'))
			document.getElementById('payonaccount_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else if(curpaytype=='pay_on_phone')
	{
		if(document.getElementById('payonaccount_paymethod_tr'))
			document.getElementById('payonaccount_paymethod_tr').style.display = 'none';		
		if(document.getElementById('payonaccount_cheque_tr'))
			document.getElementById('payonaccount_cheque_tr').style.display = 'none';
		if(document.getElementById('payonaccount_self_tr'))
			document.getElementById('payonaccount_self_tr').style.display = 'none';
		if(document.getElementById('paymentmethod_req'))
			document.getElementById('paymentmethod_req').value = '';
	}
	else 
	{
		cur_arr = obj.value.split('_');
		if ((cur_arr[0]=='SELF' || cur_arr[0]=='PROTX') && cur_arr.length<=2)
		{
			if(document.getElementById('payonaccount_cheque_tr'))
				document.getElementById('payonaccount_cheque_tr').style.display = 'none';
			if(document.getElementById('payonaccount_self_tr'))
				document.getElementById('payonaccount_self_tr').style.display 	= '';		
		}
		else
		{
			if(document.getElementById('payonaccount_self_tr'))
				document.getElementById('payonaccount_self_tr').style.display 	= 'none';
		}	
	}
}
</script>
			
			
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?="Pay on Account Payment"?></div>
		
			<table width="100%" border="0" cellpadding="0" cellspacing="3" class="tbl_float">
			<form method="post" action="<?php echo $http?>" name='frm_payonaccount_payment' id="frm_payonaccount_payment" class="frm_cls" onsubmit="return validate_payonaccount(this)">
			<input type="hidden" name="paymentmethod_req" id="paymentmethod_req" value="" />
			<input type="hidden" name="payonaccount_unique_key" id="payonaccount_unique_key" value="<?php echo uniqid('')?>" />
			<input type="hidden" name="save_payondetails" id="save_payondetails" value="" />
			<input type="hidden" name="nrm" id="nrm" value="1" />
			<input type="hidden" name="action_purpose" id="action_purpose" value="buy"  />
			<input type="hidden" name="checkout_zipcode" id="checkout_zipcode" value="<?php echo $cust_zipcode?>" />
			<?php 
			if($alert){ 
			?>
			<tr>
				<td colspan="4" class="errormsg" align="center">
				<?php 
						if($Captions_arr['PAYONACC'][$alert])
							echo $Captions_arr['PAYONACC'][$alert];
						else
					  		echo $alert;
				?>
				</td>
			</tr>
		<?php } ?>
  <tr>
    <td colspan="4" class="emailfriendtextheader"><?=$Captions_arr['EMAIL_A_FRIEND']['EMAIL_FRIEND_HEADER_TEXT']?>      </td>
    </tr>
  <tr>
    <td width="33%" align="left" class="regiconent"><?php echo $Captions_arr['PAYONACC']['CURRENTACC_BALANCE']?> </td>
    <td width="22%" align="left" class="regiconent">:<?php echo print_price($row_cust['customer_payonaccount_usedlimit'])?> </td>
    <td width="27%" align="left" class="regiconent"><?php echo $Captions_arr['PAYONACC']['CREDIT_LIMIT']?></td>
    <td width="18%" align="left" class="regiconent">:<?php echo print_price($row_cust['customer_payonaccount_maxlimit'])?> </td>
  </tr>
  <tr>
    <td align="left" class="regiconent"><?php echo $Captions_arr['PAYONACC']['LAST_STATE_BALANCE']?> </td>
    <td align="left" class="regiconent">:<?php echo print_price($prev_balance)?> </td>
    <td align="left" class="regiconent"><?php echo $Captions_arr['PAYONACC']['CREDIT_REMAINING']?> </td>
    <td align="left" class="regiconent">: <?php echo print_price(($row_cust['customer_payonaccount_maxlimit']-$row_cust['customer_payonaccount_usedlimit']))?></td>
  </tr>
  <tr>
    <td align="left" class="regiconent">&nbsp;</td>
    <td align="left" class="regiconent">&nbsp;</td>
    <td align="left" class="regiconent">&nbsp;</td>
    <td align="left" class="regiconent">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="regiconent"><?php echo $Captions_arr['PAYONACC']['AMT_BEING_PAID']?> </td>
    <td align="left" class="regiconent">: <?php echo get_selected_currency_symbol()?><?php echo $paying_amt?> <input name="pay_amt" id="pay_amt" type="hidden" size="10" value="<?php echo $paying_amt?>" /></td>
    <td align="left" class="regiconent">&nbsp;</td>
    <td align="left" class="regiconent">&nbsp;</td>
  </tr>
  <?php
  	if($additional_det!='')
	{
  ?>
		<tr>
		<td align="left" class="regiconent" valign="top"><?php echo $Captions_arr['PAYONACC']['ADDITIONAL_DETAILS']?> </td>
		<td align="left" class="regiconent">: <?php echo nl2br($additional_det)?> <input name="pay_additional_details" id="pay_additional_details" type="hidden" value="<?php echo $additional_det?>" /></td>
		<td align="left" class="regiconent">&nbsp;</td>
		<td align="left" class="regiconent">&nbsp;</td>
	  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="4" align="center" class="regiconent">&nbsp;</td>
    </tr>
	  <? if($Settings_arr['imageverification_req_payonaccount']) {?>
  <tr>
    <td colspan="4" align="center" class="emailfriendtextnormal"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=payonaccountpayment_Vimg')?>" border="0" alt="Image Verification"/>&nbsp;	</td>
    </tr>
  <tr>
    <td colspan="6" align="center" class="emailfriendtextnormal"><?=$Captions_arr['PAYONACC']['PAYON_VERIFICATION_CODE']?>&nbsp;<span class="redtext">*</span><span class="emailfriendtext">
	  <?php 
		// showing the textbox to enter the image verification code
		$vImage->showCodBox(1,'payonaccountpayment_Vimg','class="inputA_imgver"'); 
	?>
	</span> </td>
    </tr>
  <? }?>
  <?php
 	if($google_exists && $Captions_arr['PAYONACC'] ['PAYON_PAYMENT_MULTIPLE_MSG'])
	{	
  ?>
  <tr>
  	<td colspan="4" align="left" class="google_header_text"><?php echo $Captions_arr['PAYONACC'] ['PAYON_PAYMENT_MULTIPLE_MSG']?>
  	</td>
  </tr>
 <?php
 }
 ?> 
  <tr>
  <td colspan="4">
  <table width="100%" cellpadding="1" cellspacing="1" border="0">
  <tr>
  <td colspan="2">
			<?php
				$cc_exists 			= 0;
				$cc_seq_req 		= check_Paymethod_SSL_Req_Status();
				$sql_paytypes 	= "SELECT a.paytype_code,b.paytype_forsites_id,a.paytype_id,a.paytype_name,b.images_image_id  
												FROM 
													payment_types a, payment_types_forsites b 
												WHERE 
													b.sites_site_id = $ecom_siteid 
													AND paytype_forsites_active=1 
													AND paytype_forsites_userdisabled=0 
													AND a.paytype_id=b.paytype_id 
													AND a.paytype_showinpayoncredit=1   
													$paytype_moreadd_condition 
												ORDER BY 
													a.paytype_order";
				$ret_paytypes = $db->query($sql_paytypes);
				$paytypes_cnt = $db->num_rows($ret_paytypes);
				if ($db->num_rows($ret_paytypes))
				{
					if($db->num_rows($ret_paytypes)==1)// Check whether there are more than 1 payment type. If no then dont show the payment option to user, just use hidden field
					{
						echo '<script type="text/javascript">
							paytype_arr  = new Array();		
								</script>';
						$row_paytypes = $db->fetch_array($ret_paytypes);
						echo '<script type="text/javascript">';
						echo "paytype_arr[".$row_paytypes['paytype_id']."] = '".$row_paytypes['paytype_code']."';";
						echo '</script>';
						if($row_paytypes['paytype_code']=='credit_card')
						{
							//if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
								$cc_exists = true;
						}	
					?>
						<input type="hidden" name="payonaccount_paytype" id="payonaccount_paytype" value="<?php echo $row_paytypes['paytype_id']?>" />
					<?php
					}
					else
					{
						
							$pay_maxcnt = 2;
							$pay_cnt	= 0;
					?>
							  <div class="shoppaymentdiv">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tr>
									<td colspan="<?php echo $pay_maxcnt?>" class="cart_payment_header"><?php echo $Captions_arr['PAYONACC']['PAYON_SEL_PAYTYPE']?></td>
								  </tr>
								  <tr>
								  <?php
									echo '<script type="text/javascript">
											paytype_arr  = new Array();		
											</script>';
									while ($row_paytypes = $db->fetch_array($ret_paytypes))
									{
										if($row_paytypes['paytype_code']=='credit_card')
										{
											if($row_paytypes['paytype_id']==$row_cartdet['paytype_id'])
												$cc_exists = true;
											if (($protectedUrl==true and $cc_seq_req==false) or ($protectedUrl==false and $cc_seq_req==true))
											{
												$paytype_onclick = "handle_form_submit(document.frm_payonaccount_payment,'','')";	
											}
											else
												$paytype_onclick = 'handle_paytypeselect_payonaccount(this)';
												
										}	
										else // if pay type is not credit card.
										{
											if ($protectedUrl==true)
											{
												$paytype_onclick = "handle_form_submit(document.frm_payonaccount_payment,'','')";	
											}
											else
												$paytype_onclick = 'handle_paytypeselect_payonaccount(this)';
										}
										echo '<script type="text/javascript">';
										echo "paytype_arr[".$row_paytypes['paytype_id']."] = '".$row_paytypes['paytype_code']."';";
										echo '</script>';
								  ?>
										<td width="25%" align="left" class="emailfriendtextnormal">
										<?php
											// image to shown for payment types
											$pass_type = 'image_thumbpath';
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('paytype',$row_paytypes['paytype_forsites_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_paytypes['paytype_name'],$row_paytypes['paytype_name']);
											}
											else
											{
											?>
												<img src="<?php url_site_image('cash.gif')?>" alt="Payment Type"/>
											<?php	
											}	
																?>
										
										<input class="shoppingcart_radio" type="radio" name="payonaccount_paytype" id="payonaccount_paytype" value="<?php echo $row_paytypes['paytype_id']?>" onclick="<?php echo $paytype_onclick?>" <?php echo ($_REQUEST['payonaccount_paytype']==$row_paytypes['paytype_id'])?'checked="checked"':''?> /><?php echo stripslashes($row_paytypes['paytype_name'])?>										</td>
								<?php
										$pay_cnt++;
										if ($pay_cnt>=$pay_maxcnt)
										{
											echo "</tr><tr>";
											$pay_cnt = 0;
										}
									}
									if ($pay_cnt<$pay_maxcnt)
									{
										echo "<td colspan=".($pay_maxcnt-$pay_cnt).">&nbsp;</td>";
									}
								?>	
								  </tr>
								</table>
							</div>
					<?php
						
					}
				}
			?>		</td>
   </tr>
   	<?php 
	$self_disp = 'none';
	if($_REQUEST['payonaccount_paytype'])
	{
		// get the paytype code for current paytype
		$sql_pcode = "SELECT paytype_code 
								FROM 
									payment_types 
								WHERE 
									paytype_id = ".$_REQUEST['payonaccount_paytype']." 
								LIMIT 
									1";
		$ret_pcode = $db->query($sql_pcode);
		if ($db->num_rows($ret_pcode))
		{
			$row_pcode 	= $db->fetch_array($ret_pcode);
			$sel_ptype 	= $row_pcode['paytype_code'];
		}
	}
	if($sel_ptype=='credit_card')
		$paymethoddisp_none = '';
	else
		$paymethoddisp_none = 'none';
	if($sel_ptype=='cheque')
		$chequedisp_none = '';
	else
		$chequedisp_none = 'none';	
	$sql_paymethods = "SELECT a.paymethod_id,a.paymethod_name,a.paymethod_key,
									  a.paymethod_takecarddetails,a.payment_minvalue,a.paymethod_secured_req,a.paymethod_ssl_imagelink,
									  b.payment_method_sites_caption 
								FROM 
									payment_methods a,
									payment_methods_forsites b 
								WHERE 
									b.sites_site_id = $ecom_siteid 
									AND paymethod_showinpayoncredit =1 
									AND paymethod_key <> 'GOOGLE_CHECKOUT'  
									AND a.paymethod_id=b.payment_methods_paymethod_id";
	$ret_paymethods = $db->query($sql_paymethods);
	if ($db->num_rows($ret_paymethods))
	{
		if ($db->num_rows($ret_paymethods)==1)
		{
			$row_paymethods = $db->fetch_array($ret_paymethods);
			if ($row_paymethods['paymethod_key']=='SELF' or $row_paymethods['paymethod_key']=='PROTX')
			{
				if($paytypes_cnt==1 or $sel_ptype =='credit_card')
					$self_disp = '';
			}	
			?>
			<input type="hidden" name="payonaccount_paymethod" id="payonaccount_paymethod" value="<?php echo $row_paymethods['paymethod_key'].'_'.$row_paymethods['paymethod_id']?>" />
			<?php
		}
		else
		{
			/*if($db->num_rows($ret_paytypes)==1 and $cc_exists == true)
				$disp = '';
			else
				$disp = 'none';
			*/		
			?>
			<tr id="payonaccount_paymethod_tr" style="display:<?php echo $paymethoddisp_none?>">
				<td colspan="2" align="left" valign="middle">
				<div class="shoppayment_type_div">
					<?php
						$pay_maxcnt = 2;
						$pay_cnt	= 0;
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td colspan="<?php echo $pay_maxcnt?>" class="cart_payment_header"><?php echo $Captions_arr['PAYONACC']['PAYON_SEL_PAYGATEWAY']?></td>
					  </tr>
					  <tr>
					  <?php
						while ($row_paymethods = $db->fetch_array($ret_paymethods))
						{
							$caption = ($row_paymethods['payment_method_sites_caption'])?$row_paymethods['payment_method_sites_caption']:$row_paymethods['paymethod_name'];
							
							if($row_paymethods['paymethod_secured_req']==1 and $protectedUrl==true) // if secured is required for current pay method and currently in secured. so no reload is required
							{
								$on_paymethod_click = 'handle_paytypeselect_payonaccount(this)';
							}	
							elseif ($row_paymethods['paymethod_secured_req']==1 and $protectedUrl==false) // case if secured is required and current not is secured. so reload is required
							{
									$on_paymethod_click = "handle_form_submit(document.frm_payonaccount_payment,'','')";
							}
							elseif ($row_paymethods['paymethod_secured_req']==0 and $protectedUrl==false) // case if secured is required and current not is secured. so reload is required
							{
									$on_paymethod_click = 'handle_paytypeselect_payonaccount(this)';
							}
							elseif ($row_paymethods['paymethod_secured_req']==0 and $protectedUrl==true) // case if secured is not required and current is secured. so reload is required
							{
									$on_paymethod_click = "handle_form_submit(document.frm_payonaccount_payment,'','')";
							}
							else
							{
									$on_paymethod_click = 'handle_paytypeselect_payonaccount(this)';
							}
							$curname = $row_paymethods['paymethod_key'].'_'.$row_paymethods['paymethod_id'];
							if($curname==$_REQUEST['payonaccount_paymethod'])
							{
								if (($row_paymethods['paymethod_key']=='SELF' or $row_paymethods['paymethod_key']=='PROTX') and $sel_ptype=='credit_card')
									$self_disp = '';
								if($sel_ptype=='credit_card')	
									$sel = 'checked="checked"';
							}	
							else
								$sel = '';
							$img_path="./images/".$ecom_hostname."/site_images/payment_methods_images/".$row_paymethods['paymethod_ssl_imagelink'];										
							 if(file_exists($img_path))
								$caption = '<img src="'.$img_path.'" border="0" alt="'.$caption.'" />';
							
					  ?>
							<td width="25%" align="left" class="emailfriendtextnormal">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="left" valign="top" width="2%">
								<input class="shoppingcart_radio" type="radio" name="payonaccount_paymethod" id="payonaccount_paymethod" value="<?php echo $row_paymethods['paymethod_key'].'_'.$row_paymethods['paymethod_id']?>" <?php echo $sel ?>  onclick="<?php echo $on_paymethod_click?>" />
								<td align="left">
								<?php echo stripslashes($caption)?>
							</td>
							</tr>
							</table>
								
								</td>
					<?php
							$pay_cnt++;
							if ($pay_cnt>=$pay_maxcnt)
							{
								echo "</tr><tr>";
								$pay_cnt = 0;
							}
						}
						if ($pay_cnt<$pay_maxcnt)
						{
							echo "<td colspan=".($pay_maxcnt-$pay_cnt).">&nbsp;</td>";
						}
					?>	
					  </tr>
					</table>
				</div>				</td>
			</tr>	
			<?php
		}
	}
	?>
	<tr id="payonaccount_cheque_tr" style="display:<?php echo $chequedisp_none?>">
	<td colspan="2" align="left" valign="middle">	
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader"><?php echo $Captions_arr['PAYONACC']['PAY_ON_CHEQUE_DETAILS']?></td>
		</tr>
		<?php
			// Get the list of credit card static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
								FROM 
									general_settings_site_checkoutfields 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND field_hidden=0 
									AND field_type='CHEQUE' 
								ORDER BY 
									field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{						
				while($row_checkout = $db->fetch_array($ret_checkout))
				{			
					// Section to handle the case of required fields
					if($row_checkout['field_req']==1)
					{

						$chkoutadd_Req[]		= "'".$row_checkout['field_key']."'";
						$chkoutadd_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
					}			
		?>
				<tr>
					<td align="left" width="50%" class="emailfriendtextnormal" valign="top">
					<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>					</td>
					<td align="left" width="50%" class="emailfriendtextnormal" valign="top">
					<?php
						echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData);
					?>					</td>
				</tr>
		<?php
				}
			}
		?>
			</table>		</td>
	 </tr>	
	<tr id="payonaccount_self_tr" style="display:<?php echo $self_disp?>">
		<td colspan="2" align="left" valign="middle">	
		<table width="100%" cellpadding="1" cellspacing="1" border="0">
		<tr>
			<td colspan="2" align="left" class="shoppingcartheader"><?php echo $Captions_arr['PAYONACC']['PAYON_CREDIT_CARD_DETAILS']?></td>
		</tr>
		<?php
			$cur_form = 'frm_payonaccount_payment';
			// Get the list of credit card static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_det_id,field_key,field_name,field_req,field_error_msg   
								FROM 
									general_settings_site_checkoutfields 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND field_hidden=0 
									AND field_type='CARD' 
								ORDER BY 
									field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{						
				while($row_checkout = $db->fetch_array($ret_checkout))
				{			
					// Section to handle the case of required fields
					if($row_checkout['field_req']==1)
					{
						if($row_checkout['field_key']=='checkoutpay_expirydate' or $row_checkout['field_key']=='checkoutpay_issuedate')
						{
							$chkoutcc_Req[]			= "'".$row_checkout['field_key']."_month'";
							$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'";
							$chkoutcc_Req[]			= "'".$row_checkout['field_key']."_year'";
							$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
						}
						else
						{
							$chkoutcc_Req[]			= "'".$row_checkout['field_key']."'";
							$chkoutcc_Req_Desc[]	= "'".$row_checkout['field_error_msg']."'"; 
						}	
					}			
		?>
				<tr>
					<td align="left" width="50%" class="emailfriendtextnormal">
					<?php echo stripslashes($row_checkout['field_name']); if($row_checkout['field_req']==1) { echo '&nbsp;<span class="redtext">*</span>';}?>					</td>
					<td align="left" width="50%" class="emailfriendtextnormal">
					<?php
						echo get_Field($row_checkout['field_key'],$saved_checkoutvals,$cartData,$cur_form);
					?>					</td>
				</tr>
		<?php
				}
			}
		?> 
		</table>	</td>
	</tr>
	 <tr>
    <td colspan="4" align="right" class="emailfriendtext"><input name="payonaccount_payment_Submit" type="submit" class="buttongray" id="payonaccount_payment_Submit" value="<?="Make Payment"?>"/></td>
    </tr>
			</form>
	<?php
		    	// Check whether the google checkout button is to be displayed
		if($google_exists)
		{
			$row_google = $db->fetch_array($ret_google);
	?>
	<tr>
	<td colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shoppingcarttable">
		<tr>
			<td align="center" valign="middle" class="google_or">
			Or
			</td>
		</tr>	
		<tr>
			<td colspan="6" align="right" valign="middle" class="google_td">
			<?php
				// Get the details of current customer to pass to google checkout
				$pass_type 	= 'payonaccount';
				$cust_details 	= stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_surname']).' - '.stripslashes($row_cust['customer_email_7503']).' - '.$ecom_hostname;
				$cartData["totals"]["bonus_price"] = $paying_amt;
				require_once('includes/google_library/googlecart.php');
				require_once('includes/google_library/googleitem.php');
				require_once('includes/google_library/googleshipping.php');
				require_once('includes/google_library/googletax.php');
				include("includes/google_checkout.php");
			?>	
			</td>
		</tr>	
		</table>
	</td>
	</tr>
	<?php
			}
	?>	
	</table>	</td>
	</tr>
</table>

		<script type="text/javascript">
		function validate_payonaccount(frm)
		{
		<?php
			if(count($chkoutadd_Req))
			{
			?>
				reqadd_arr 			= new Array(<?php echo implode(",",$chkoutadd_Req)?>);
				reqadd_arr_str		= new Array(<?php echo implode(",",$chkoutadd_Req_Desc)?>);
			<?php
			}
			if(count($chkoutcc_Req))
			{
			?>
				reqcc_arr 			= new Array(<?php echo implode(",",$chkoutcc_Req)?>);
				reqcc_arr_str		= new Array(<?php echo implode(",",$chkoutcc_Req_Desc)?>);
			<?php
			}
			?>
			fieldRequired		= new Array();
			fieldDescription	= new Array();
			var i=0;
			<?php
			if($Settings_arr['imageverification_req_payonaccount'])
			{
			?>
			fieldRequired[i] 		= 'payonaccountpayment_Vimg';
			fieldDescription[i]	 = 'Image Verification Code';
			i++;
			<?php
			}
			if (count($chkoutadd_Req))
			{
			?>
			if(document.getElementById('payonaccount_cheque_tr').style.display=='') /* do the following only if checque is selected */
			{
				for(j=0;j<reqadd_arr.length;j++)
				{
					fieldRequired[i] 	= reqadd_arr[j];
					fieldDescription[i] = reqadd_arr_str[j];
					i++;
				}
			}
			<?php
			}
			if (count($chkoutcc_Req))
			{
			?>
			if(document.getElementById('payonaccount_self_tr').style.display=='') /* do the following only if protx or self  is selected */
			{
				for(j=0;j<reqcc_arr.length;j++)
				{
					fieldRequired[i] 	= reqcc_arr[j];
					fieldDescription[i] = reqcc_arr_str[j];
					i++;
				}
			}	
			<?php
			}	
			if (count($chkout_Email))
			{
			$chkout_Email_Str 		= implode(",",$chkout_Email);
			echo "fieldEmail 		= Array(".$chkout_Email_Str.");";
			}
			else
			echo "fieldEmail 		= Array();";
			// Password checking
			if (count($chkout_Confirm))
			{
			$chkout_Confirm_Str 	= implode(",",$chkout_Confirm);
			$chkout_Confirmdesc_Str	= implode(",",$chkout_Confirmdesc);
			echo "fieldConfirm 		= Array(".$chkout_Confirm_Str.");";
			echo "fieldConfirmDesc 	= Array(".$chkout_Req_Desc_Str.");";
			}
			else
			{
			echo "fieldConfirm 		= Array();";
			echo "fieldConfirmDesc 	= Array();";
			}	
			if (count($chkout_Numeric))
			{
				$chkout_Numeric_Str 		= implode(",",$chkout_Numeric);
				echo "fieldNumeric 			= Array(".$chkout_Numeric_Str.");";
			}
			else
				echo "fieldNumeric 			= Array();";
			?>
			if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
			{
			/* Check whether atleast one payment type is selected */
			var atleastpay = false;
			for(k=0;k<frm.elements.length;k++)
			{
				if(frm.elements[k].name=='payonaccount_paytype')
				{
					if(frm.elements[k].type=='hidden')
						atleastpay = true; /* Done to handle the case of only one payment type */
					else if(frm.elements[k].checked==true)
						atleastpay = true;	
				}	
			}	
			if(atleastpay==false)
			{
				alert('Please select payment type');
				return false;	
			}
			if (document.getElementById('paymentmethod_req').value==1)
			{
				var atleast = false;
				for(k=0;k<frm.elements.length;k++)
				{
					if(frm.elements[k].name=='payonaccount_paymethod')
					{
						if(frm.elements[k].type=='hidden')
							atleast = true; /* Done to handle the case of only one payment method */
						else if(frm.elements[k].checked==true)
							atleast = true;	
					}	
				}	
				if(atleast ==false)
				{
					alert('Please select a payment method');
					return false;	
				}	
			}	
			else
			{
				if (document.getElementById('payonaccount_paymethod'))
					document.getElementById('payonaccount_paymethod').value = 0;
			}
			
			/* Handling the case of credit card related sections*/
			if(frm.checkoutpay_cardtype)
			{
				if(frm.checkoutpay_cardtype.value)
				{
					objarr = frm.checkoutpay_cardtype.value.split('_');
					if(objarr.length==4) /* if the value splitted to exactly 4 elements*/
					{
						var key 		= objarr[0];
						var issuereq 	= objarr[1];
						var seccount 	= objarr[2];
						var cc_count 	= objarr[3];
						if (isNaN(frm.checkoutpay_cardnumber.value))
						{
							alert('Credit card number should be numeric');
							frm.checkoutpay_cardnumber.focus();
							return false;
						}
						if (frm.checkoutpay_cardnumber.value.length>cc_count)
						{
							alert('Credit card number should not contain more than '+cc_count+' digits');
							frm.checkoutpay_cardnumber.focus();
							return false;
						}
						if (frm.checkoutpay_securitycode.value.length>seccount)
						{
							alert('Security Code should not contain more than '+seccount+' digits');
							frm.checkoutpay_securitycode.focus();
							return false;
						}
					}
				}
			}			
			/* If reached here then everything is valid 
				change the action of the form to the desired value
			*/
				if(document.getElementById('save_payondetails'))
					document.getElementById('save_payondetails').value  	= 1;
				show_wait_button(document.getElementById('payonaccount_payment_Submit'),'Please wait...');
				frm.action = 'payonaccount_payment_submit.php?bsessid=<?php echo base64_encode($ecom_hostname)?>';
				frm.submit();
				return true;
			}	
			else
			return false;
		}	
		</script>	
		<?php	
		}
		
		/* Function to show the payonaccount failed message*/
		function Show_payonaccountFailed()
		{
			global $db,$ecom_hostname,$Captions_arr,$ecom_siteid;
			$sess_id = session_id();
			// Get the error details from voucher cart table
			$sql_cart = "SELECT pay_error_msg  
							FROM 
								payonaccount_cartvalues   
							WHERE 
								sites_site_id =$ecom_siteid
								AND session_id='".$sess_id."'";
			$ret_cart = $db->query($sql_cart);
			if($db->num_rows($ret_cart))
			{
				$row_cart 	= $db->fetch_array($ret_cart);
				$msg		= stripslashes(trim($row_cart['pay_error_msg']));
			}
			// update the cart_error_msg_ret field with blank 
			$update_array						= array();
			$update_array['pay_error_msg']	= '';
			$db->update_from_array($update_array,'payonaccount_cartvalues',array('sites_site_id'=>$ecom_siteid,'session_id'=>$sess_id));					
			
			$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC'); // Getting the captions to be used in this page
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tbl_float">
			<tr>
				<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['PAYONACC']['PAYON_FAILED_TITLE']?></div></td>
			</tr>
			<?php
				if($msg)
				{
			?>
					<tr>
						<td align="left" class="shoppingcartcontent_indent_highlight">
						<?php echo $msg?>
						</td>
					</tr>
			<?php
				}
				else
				{
			?>
					<tr>
						<td align="left" class="shoppingcartcontent_indent_highlight">
						<?php echo $Captions_arr['PAYONACC']['PAYON_FAILED_MSG']?><br /><br />
						<?php
						if($_REQUEST['error'])
						{
							echo $_REQUEST['error'];
						}
						?>
						</td>
					</tr>
			<?php	
				}
			?>
			</table>	
		<?php	
		}
		/* 
			Function to show the preview for the gift voucher details
		*/
		function Show_payonaccountPreview($return_pay_arr,$alert='',$just_for_display=false)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,
						$Captions_arr,$inlineSiteComponents,$ecom_email;
			$Captions_arr['PAYONACC'] 	= getCaptions('PAYONACC'); // Getting the captions to be used in this page
			$sessid	= session_id();
			$customer_id 		= get_session_var("ecom_login_customer"); // get the id of current customer from session
			
			if ($alert=='preview_before_gateway') // handing the case of session only if required
			{
				// Setting the current voucher id in session 
				set_session_var('gateway_payonaccount_id',$return_pay_arr['pay_id']);
			}
			if(!$return_pay_arr['pay_id'])
			{
				echo '<script type="text/javascript">window.location = "http://'.$ecom_hostname.'/payonaccountpayment.html?rt=1"</script>';
				exit;
			}
			if ($alert=='preview_before_gateway' or $alert = 'preview_after_ptype') // handing the case of session only if required
			{
				// Get the details regarding current payment from pending details table
				$sql_pay = "SELECT pendingpay_id, pay_date, sites_site_id, customers_customer_id, pay_amount, pay_transaction_type, pay_details, pay_paystatus,
											pay_paymenttype, pay_paymentmethod, pay_paystatus_changed_by, pay_paystatus_changed_on, pay_paystatus_changed_paytype,
											pay_additional_details, pay_curr_rate, pay_curr_code, pay_curr_symbol, pay_curr_numeric_code, pay_unique_key  
										FROM 
											order_payonaccount_pending_details 
										WHERE 
											pendingpay_id = ".$return_pay_arr['pay_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
			}
			else // case of coming to show the success details
			{
				// Get the details regarding current payment from pending details table
				$sql_pay = "SELECT pay_id as pendingpay_id, pay_date, sites_site_id, customers_customer_id, pay_amount, pay_transaction_type, pay_details, pay_paystatus,
											pay_paymenttype, pay_paymentmethod, pay_paystatus_changed_by, pay_paystatus_changed_on, pay_paystatus_changed_paytype,
											pay_additional_details, pay_curr_rate, pay_curr_code, pay_curr_symbol, pay_curr_numeric_code   
										FROM 
											order_payonaccount_details  
										WHERE 
											pay_id = ".$return_pay_arr['pay_id']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
			}								
			$ret_pay = $db->query($sql_pay);
			if ($db->num_rows($ret_pay))
			{
				$row_pay = $db->fetch_array($ret_pay);
				// get other details related to current payment
				$sql_pay_cust = "SELECT customer_title, customer_fname, customer_mname, customer_surname 
												FROM 
													customers  
												WHERE 
													customer_id = ".$row_pay['customers_customer_id']." 
												LIMIT 
													1";
				$ret_pay_cust = $db->query($sql_pay_cust);
				if ($db->num_rows($ret_pay_cust))
					$row_pay_cust = $db->fetch_array($ret_pay_cust);
				
			}
			else // return back to payment initation page purchase page
			{
				// If voucher id is fake then redirect back to cart page
					echo "<script type='text/javascript'>window.location='http://".$ecom_hostname."/mypayonaccountpayment.html'</script>";
					exit;	
			}
			
			switch($alert)
			{
				case 'preview_before_gateway':// case of HSBC or worldpay
					$alert = 'PAYON_BEFORE_GATEWAY';
				break;	
				case 'preview_after_protx': // case of protx payment successfull
					$alert = 'PAYON_PROTX_SUCCESS';
				break;
				case 'preview_after_ptype': // checque/payonphone etc
				case 'preview_after_self': // case of self
					$alert = 'PAYON_PREVIEW_DONE_AWAITING';	
				break;
				case 'pay_succ': // case of coming directly after payment success from gateway
					// A double checking to see whether the payment status of currrent voucher is 'Paid'
					if($row_pay['pay_paystatus']=='Paid') // is paid
						$alert = 'PAYON_PREVIEW_DONE_PAYMENT';	
					else 	// if not paid. case came here by directly typing the url
						$alert = '';
				break;			
					
			};
			switch($row_pay['pay_paymentmethod'])
			{
				case 'WORLD_PAY':
				case 'HSBC':
				case 'PROTX_VSP':
				?>
				<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>  >><a href="<? url_link('mypayonaccountpayment.html');?>"> <?=$Captions_arr['PAYONACC']['PAYONACCOUNT_MAINHEADING']?></a> >> <?=$Captions_arr['PAYONACC']['PAYON_BUY_TREEMENU_PREVIEWTITLE']?></div>
				<?php				
				break;
				default
			?>
					<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>  >><a href="<? url_link('mypayonaccountpayment.html');?>"> <?=$Captions_arr['PAYONACC']['PAYONACCOUNT_MAINHEADING']?></a> >> <?=$Captions_arr['PAYONACC']['PAYON_BUY_TREEMENU_PREVIEWTITLE']?></div>
			<?php							
			};
	?>		
			<table width="100%" border="0" cellpadding="0" cellspacing="3" class="tbl_float">
			<?php 
				if($alert)
				{ 
			?>
					<tr>
						<td colspan="2" class="errormsg" align="center">
					<?php 
						if($Captions_arr['PAYONACC'][$alert])
						{
							echo $Captions_arr['PAYONACC'][$alert];
						}
						else
						{
							echo $alert;
						}
					?>	
						</td>
					</tr>
			<?php 
				}
			?>
			<?php
			if($Captions_arr['PAYONACC']['PAYON_VAL_PREVIEW_MESSAGE_TEXT'])
			{
			?>
				<tr>
				<td colspan="2" class="shoppingcartheader"><?=$Captions_arr['PAYONACC']['PAYON_VAL_PREVIEW_MESSAGE_TEXT']?> </td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td width="40%" class="emailfriendtextnormal" id="vouch_caption_td"><?=$Captions_arr['PAYONACC']['PAY_AMT_LABEL']?></td>
				<td width="60%" align="left" valign="middle" class="emailfriendtext"><?php echo print_price($row_pay['pay_amount'])?></td>
			</tr>
			<?php
				if($row_pay['pay_additional_details']!='')
				{
			?>
					<tr>
					<td width="40%" class="emailfriendtextnormal" id="vouch_caption_td" valign="top"><?="Additional Details"?></td>
					<td width="60%" align="left" valign="middle" class="emailfriendtext"><?php echo nl2br($row_pay['pay_additional_details'])?></td>
					</tr>
			<?php
				}
			?>		
			<tr>
				<td align="left" width="40%" class="emailfriendtextnormal" valign="top">
				<?=$Captions_arr['PAYONACC']['PAYON_PAYMENT_TYPE']?>
				</td>
				<td align="left" width="60%" class="emailfriendtext">
				<?php	echo getpaymenttype_Name($row_pay['pay_paymenttype']);?>
				</td>
			</tr>
			<?php
				if ($row_pay['pay_paymentmethod']!='')
				{
			?>
					<tr>
						<td align="left" width="40%" class="emailfriendtextnormal" valign="top">
						<?=$Captions_arr['PAYONACC']['PAYON_PAYMENT_METHOD']?>
						</td>
						<td align="left" width="60%" class="emailfriendtext">
						<?php	echo getpaymentmethod_Name($row_pay['pay_paymentmethod']);?>
						</td>
					</tr>
			<?php
				}
				if($just_for_display==false) // show the following section only if required
				{
					// Get the details of current customer
					$sql_cust = "SELECT customer_fname,customer_mname,customer_surname,customer_buildingname, customer_streetname, customer_towncity, customer_statecounty, 
													customer_phone, customer_fax, customer_postcode, customer_email_7503,country_id  
												FROM 
													customers 
												WHERE 
													customer_id = $customer_id  
													AND sites_site_id = $ecom_siteid 
												LIMIT 
													1";
					$ret_cust = $db->query($sql_cust);
					if($db->num_rows($ret_cust))
					{
						$row_cust = $db->fetch_array($ret_cust);
						if($row_cust['country_id'])
						{
							$sql_country = "SELECT country_name 
														FROM 
															general_settings_site_country 
														WHERE 
															country_id = ".$row_cust['country_id']." 
														LIMIT 
															1";
							$ret_country = $db->query($sql_country);
							if ($db->num_rows($ret_country))
							{
								$row_country	 	= $db->fetch_array($ret_couintry);
								$cust_country		= stripslashes($row_country['country_name']);		
							}
						}
					}	
			?>				
					<tr>
						<td colspan="2" align="right" class="emailfriendtextnormal">
						<?php
							switch($row_pay['pay_paymentmethod'])
							{
								case 'WORLD_PAY': // Display the form for worldpay
									$pass_type					= 'payonaccount';
									$button_maincaption 	= 'Confirm Pay On Account Payment';
									$button_clickmsg		= 'Please wait...';
									$button_class				= 'buttonred_cart';
									// Setting up the values to be passed to worldpay gateway
									$return_order_arr['payData']['gateway_addons']['worldpay_instId'] 	= $_REQUEST['pass_world_instId'];
									$return_order_arr['order_id']															= $_REQUEST['pass_world_cartId'];
									$return_order_arr['payData']['gateway_addons']['curr_code']			= $_REQUEST['pass_world_currency'];
									$return_order_arr['payData']['gateway_addons']['desc']					= $_REQUEST['pass_world_desc'];
									$return_order_arr['payData']['gateway_addons']['pass_total']			= $_REQUEST['pass_world_total'];
									$return_order_arr['payData']['gateway_addons']['mode']					= $_REQUEST['pass_world_mode'];
									
									$row_ord['order_custfname']														= $row_cust['customer_fname'];
									$row_ord['order_buildingnumber']													= $row_cust['customer_buildingname'];	
									$row_ord['order_street']																= $row_cust['customer_streetname'];
									$row_ord['order_city']																	= $row_cust['customer_towncity'];	
									$row_ord['order_state']																= $row_cust['customer_statecounty'];	
									$row_ord['order_country']															= $cust_country;
									$row_ord['order_custpostcode']													= $row_cust['customer_postcode'];
									$row_ord['order_custphone']														= $row_cust['customer_phone'];
									$row_ord['order_custfax']															= $row_cust['customer_fax'];
									$row_ord['order_custemail']															= $row_cust['customer_email_7503'];	
								
									include 'includes/world_pay.php';
								break;
								case 'HSBC':
									$button_maincaption 	= 'Confirm Pay On Account Payment';
									$button_clickmsg		= 'Please wait...';
									$button_class				= 'buttonred_cart';
				
									$return_order_arr['order_id'] 															= $return_pay_arr['pay_id'];
									$return_order_arr['payData']['gateway_addons']['time']						= $_REQUEST['pass_hsbc_timestamp'];
									$return_order_arr['payData']['gateway_addons']['CpiReturnUrl']				= $_REQUEST['pass_hsbc_CpiReturnUrl'];
									$return_order_arr['payData']['gateway_addons']['CpiDirectResultUrl']		= $_REQUEST['pass_hsbc_CpiDirectResultUrl'];
									$return_order_arr['payData']['gateway_addons']['hsbc_merchant_id']		= $_REQUEST['pass_hsbc_merchant_id'];
									$return_order_arr['payData']['gateway_addons']['OrderDesc']				= $_REQUEST['pass_hsbc_OrderDesc'];			
									$return_order_arr['payData']['gateway_addons']['pass_total']				= $_REQUEST['pass_hsbc_PurchaseAmount'];
									$return_order_arr['payData']['gateway_addons']['PurchaseCurrency']		= $_REQUEST['pass_hsbc_PurchaseCurrency'];
									$return_order_arr['payData']['gateway_addons']['TransactionType']		= $_REQUEST['pass_hsbc_TransactionType'];
									$return_order_arr['payData']['gateway_addons']['Mode']						= $_REQUEST['pass_hsbc_Mode'];
									$return_order_arr['payData']['gateway_addons']['MerchantData']			= $_REQUEST['pass_hsbc_MerchantData'];
									$row_ord['order_custfname']															= $_REQUEST['pass_hsbc_BillingFirstName'];
									$row_ord['order_custsurname']															= $_REQUEST['pass_hsbc_BillingLastName'];
									$row_ord['order_custemail']																= $_REQUEST['pass_hsbc_ShopperEmail'];
									$row_ord['order_buildingnumber']														= $_REQUEST['pass_hsbc_Billingbuildingname'];
									$row_ord['order_street']																	= $_REQUEST['pass_hsbc_Billingstreet'];
									$row_ord['order_city']																		= $_REQUEST['pass_hsbc_BillingCity'];
									$row_ord['order_state']																	= $_REQUEST['pass_hsbc_BillingCounty'];
									$row_ord['order_custpostcode']														= $_REQUEST['pass_hsbc_BillingPostal'];
									$return_order_arr['payData']['gateway_addons']['pass_country']			= $_REQUEST['pass_hsbc_BillingCountry'];
									$return_order_arr['payData']['gateway_addons']['del_name']				= $_REQUEST['pass_hsbc_ShippingFirstName'];
									$return_order_arr['payData']['gateway_addons']['del_last_name']			= $_REQUEST['pass_hsbc_ShippingLastName'];
									$return_order_arr['payData']['gateway_addons']['del_building_name']	= $_REQUEST['pass_hsbc_Shippingbuildingname'];
									$return_order_arr['payData']['gateway_addons']['del_street']				= $_REQUEST['pass_hsbc_Shippingstreet'];
									$return_order_arr['payData']['gateway_addons']['del_city']					= $_REQUEST['pass_hsbc_ShippingCity'];
									$return_order_arr['payData']['gateway_addons']['del_county']				= $_REQUEST['pass_hsbc_ShippingCounty'];
									$return_order_arr['payData']['gateway_addons']['del_postCode']			= $_REQUEST['pass_hsbc_ShippingPostal'];
									$return_order_arr['payData']['gateway_addons']['pass_country']			= $_REQUEST['pass_hsbc_ShippingCountry'];
									$return_order_arr['payData']['gateway_addons']['hash']						= $_REQUEST['pass_hsbc_hash'];
									
									include("includes/hsbc_pay.php");
								break;
								case 'PROTX_VSP':
									$button_maincaption 	= 'Confirm Pay On Account Payment';
									$pass_type					= 'payonaccount';
									$button_clickmsg		= 'Please wait...';
									$button_class				= 'buttonred_cart';
									$return_order_arr['payData']['gateway_addons']['strPurchaseURL']			= $_REQUEST['pass_strPurchaseURL'];
									$return_order_arr['payData']['gateway_addons']['strTransactionType']	= $_REQUEST['pass_TxType'];
									$return_order_arr['payData']['gateway_addons']['strVSPVendorName']	= $_REQUEST['pass_Vendor'];
									$return_order_arr['payData']['gateway_addons']['strCrypt']					= $_REQUEST['pass_Crypt'];
									include 'includes/protx_vsp.php';
								break;
							};
							?>			 
						</td>
					</tr>
			<?php
				}
				else 
				{
			?>
					<tr>
						<td colspan="2" align="right" class="emailfriendtextnormal">
							<input type="button" name="submit_backhome" value="<?php echo $Captions_arr['PAYONACC']['PAYON_PREVIEW_BACH_HOME']?>" onclick="window.location ='<?php url_link('')?>'" />
						</td>
					</tr>	
			<?php
				}	
			?>
			</table>
	<?php		
			// #############################################################################
			}
    }
	  ?>		