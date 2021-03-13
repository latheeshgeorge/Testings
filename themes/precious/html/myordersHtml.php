<?php
	/*############################################################################
	# Script Name 	: myordersHtml.php
	# Description 	: Page which holds the display logic for My Address Book
	# Coded by 		: Randeep
	# Created on	: 02-May-2008
	# Modified by	: Sny
	# Modified on	: 15-Jul-2008
	##########################################################################*/
	class myorders_Html
	{
	  function orders_Showform()
	  {
	    global $insmsg, $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
		$session_id = session_id();	// Get the session id for the current section
		$customer_id = get_session_var('ecom_login_customer');
		$Captions_arr['ORDER'] 	= getCaptions('ORDER');
		
		$sort_by 			= (!$_REQUEST['ord_sort_by'])?$Settings_arr['orders_orderfield_settings']:$_REQUEST['ord_sort_by']; ; //
		$sort_order 		= (!$_REQUEST['ord_sort_order'])?$Settings_arr['orders_orderby_settings']:$_REQUEST['ord_sort_order'] ;//;
		$sort_options 		= array('order_date' => 'Order Date','order_status'=>'Order Status','order_pre_order'=>'Preorder');
		$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
		$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
	
		//##########################################################################################################
		// Building the query to be used to display the orders
		//##########################################################################################################
		$where_conditions 	= "WHERE sites_site_id=$ecom_siteid  AND customers_customer_id='$customer_id' AND order_status NOT IN ('NOT_AUTH','CANCELLED') ";
		
		//##########################################################################################################
		// Check whether order id is given
		if($_REQUEST['ord_id'])
		{
			$where_conditions .= " AND order_id='".add_slash($_REQUEST['ord_id'])."'";
		}
		//##########################################################################################################
		// Check whether cust/company name is given
		if($_REQUEST['ord_name'])
		{
			$sr_name = add_slash($_REQUEST['ord_name']);
			$where_conditions .= " AND (
										order_custfname LIKE '%".$sr_name."%' 
										OR order_custmname LIKE '%".$sr_name."%' 
										OR order_custsurname LIKE '%".$sr_name."%' 
										OR order_custcompany LIKE '%".$sr_name."%'  
										) ";
		}
	//##########################################################################################################
	// If customer email is given
	if($_REQUEST['ord_email'])
	{
		$where_conditions .= " AND order_custemail LIKE '%".add_slash($_REQUEST['ord_email'])."%' ";
	}
	//##########################################################################################################
	// Case if from or to date is given
	$from_date 	= add_slash($_REQUEST['ord_fromdate']);
	$to_date 	= add_slash($_REQUEST['ord_todate']);
	if ($from_date or $to_date)
	{
		// Check whether from and to dates are valid
		$valid_fromdate = is_valid_date($from_date,'normal','-');
		$valid_todate	= is_valid_date($to_date,'normal','-');
		if($valid_fromdate)
		{
			$frm_arr 		= explode('-',$from_date);
			$mysql_fromdate = $frm_arr[2].'-'.$frm_arr[1].'-'.$frm_arr[0]; 
		}
		else// case of invalid from date
			$_REQUEST['ord_fromdate'] = '';
			
		if($valid_todate)
		{
			$to_arr 		= explode('-',$to_date);
			$mysql_todate 	= $to_arr[2].'-'.$to_arr[1].'-'.$to_arr[0]; 
		}
		else // case of invalid to date
			$_REQUEST['ord_todate'] = '';
		if($valid_fromdate and $valid_todate)// both dates are valid
		{
			$where_conditions .= " AND (order_date BETWEEN '".$mysql_fromdate."' AND '".$mysql_todate."') ";
		}
		elseif($valid_fromdate and !$valid_todate) // only from date is valid
		{
			$where_conditions .= " AND order_date >= '".$mysql_fromdate."' ";
		}
		elseif(!$valid_fromdate and $valid_todate) // only to date is valid
		{
			$where_conditions .= " AND order_date <= '".$mysql_todate."' ";
		}
	}
		//##########################################################################################################
		//#Select condition for getting total count
		$sql_count 			= "SELECT count(*) as cnt 
								FROM 
									orders  
									$where_conditions";
		$res_count 			= $db->query($sql_count);
		
		list($tot_cnt) 	= $db->fetch_array($res_count);//#Getting total count of records
		
		$ordperpage	= $Settings_arr['orders_maxcntperpage'];// product per page					
		/////////////////////////////////For paging///////////////////////////////////////////
		
		// Call the function which prepares variables to implement paging
								$ret_arr 		= array();
								$pg_variable	= 'ord_pg';
								if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
								{
									$start_var 		= prepare_paging($_REQUEST[$pg_variable],$ordperpage,$tot_cnt);
									$Limit			= " LIMIT ".$start_var['startrec'].", ".$ordperpage;
								}	
								else
									$Limit = '';
		/////////////////////////////////////////////////////////////////////////////////////

        $sql_select_ord = "SELECT order_id, DATE_FORMAT(order_date,'%d %b %Y') AS order_dates, order_custtitle, 
		                   CONCAT(order_custfname,'',order_custmname,'',order_custsurname) AS name, 
		                   order_custemail, order_status, order_totalprice, order_refundamt, order_paystatus,
						   order_currency_convertionrate,order_currency_symbol
				FROM 
					orders
					$where_conditions 
				ORDER BY 
					$sort_by $sort_order 
				
				    $Limit
					 "; //$start,$records_per_page
		$ret_select_ord = $db->query($sql_select_ord);
		$ord_array = array();
		$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									  <ul class="tree_menu">
									<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									 <li>'.stripslash_normal($Captions_arr['ORDER']['ORDER_MAINHEADING']).'</li>
									</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
		?>
			<form method="post" name="frm_orders" class="frm_cls" action="<?php url_link('myorders.html')?>">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="ord_mod" value="show_order" />
			<input type="hidden" name="search_click" value="search_click" />
			<input type="hidden" id="note_count" name="note_count" value="0" />
			<input type="hidden" name="order_id" value="" />
			<?=$HTML_treemenu?>
			<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['ORDER']['ORDER_MAINHEADING']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['ORDER']['ORDER_MAINHEADING'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
			<table width="100%" border="0" cellpadding="3" cellspacing="0" class="reg_table" >
			<tr>
			<td colspan="7" align="left" valign="middle">
			<table  border="0" cellpadding="2" cellspacing="3" width="100%" class="userordertablestyleA">
			  <tr>
				<td colspan="1" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDERID'])?></td>
				<td colspan="3" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_EMAIL'])?></td>
				<td colspan="5" nowrap="nowrap" class="usermenucontent" ><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_CUSTDET'])?></td>
			  </tr>
			  <tr>
				<td colspan="1" nowrap="nowrap" class="usermenucontent"><input name="ord_id" type="text" class="textfeild" id="ord_id" value="<?php echo $_REQUEST['ord_id']?>" size="6" /></td>
				<td colspan="3" nowrap="nowrap" class="usermenucontent"><input name="ord_email" id="ord_email" type="text"  value="<?php echo $_REQUEST['ord_email']?>" /></td>
				<td colspan="5" nowrap="nowrap" class="usermenucontent"><input type="text" class="textfeild" name="ord_name" id="ord_name" value="<?php echo $_REQUEST['ord_name']?>"/></td>
			  </tr>
			  <!--<tr>
				<td colspan="5" nowrap="nowrap" class="usermenucontent">Customer/ Company Name like</td>
				<td colspan="4" nowrap="nowrap" class="usermenucontent"><input type="text" class="textfeild" name="ord_name" id="ord_name" value="<?php echo $_REQUEST['ord_name']?>"/></td>
			  </tr>-->
			  <tr>
				<td colspan="5" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_BETDATE'])?> </td>
				<td colspan="4" nowrap="nowrap" class="usermenucontent"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_SORTBY'])?></td>
			  </tr>
			  <tr>
				<td width="11%" nowrap="nowrap" class="usermenucontent"><input name="ord_fromdate" class="textfeild" type="text" size="8" value="<?php echo $_REQUEST['ord_fromdate']?>" /></td>
				<td width="6%" nowrap="nowrap"><a href="javascript:show_calendar('frm_orders.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
				<td width="5%" nowrap="nowrap" class="usermenucontent">and</td>
				<td width="11%" nowrap="nowrap" class="usermenucontent"><a href="javascript:show_calendar('frm_orders.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;">
				  <input name="ord_todate" class="textfeild" id="ord_todate" type="text" size="8" value="<?php echo $_REQUEST['ord_todate']?>" />
				</a></td>
				<td width="10%" nowrap="nowrap"><a href="javascript:show_calendar('frm_orders.ord_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="<?php url_site_image('show-calendar.gif')?>" width="24" height="22" border="0" /></a></td>
				<td width="7%" nowrap="nowrap"><?php echo $sort_option_txt;?></td>
				<td width="5%" nowrap="nowrap" class="usermenucontent">In</td>
				<td width="7%" nowrap="nowrap"><?php echo $sort_by_txt?></td>
				<td class="usermenucontentA" width="38%">	
						<div class="cart_shop_cont"><div>
										 <input name="Search_go" type="submit" class="inner_btn_red" id="Search_go" value="Go" onclick="document.frm_orders.search_click.value=1" />
						</div></div>
				      </td>
			  </tr>
			  <!--<tr>
				<td colspan="9" nowrap="nowrap"><a href="javascript:show_calendar('frm_orders.ord_fromdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a><a href="javascript:show_calendar('frm_orders.ord_todate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
			  </tr>-->
			</table></td>
			</tr>
			<tr>
			<td colspan="7" align="center" valign="middle" class="pagingcontainertd_normal" ><?php 
													$path = '';
													$query_string .= "";
													paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
												?></td>
			</tr>
			<tr>
			<td colspan="7" align="left" valign="middle" class="pagingcontainertd_normal" ><img src="<?php url_site_image('new_post.gif')?>"   border="0" title="New" />
			&nbsp;<?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_NEWPOSTS'])?></td>
			</tr>	 
			<tr>
			<td align="middle" valign="middle" class="ordertableheader" width="4%" ><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ID'])?></td>
			<td align="middle" valign="middle" class="ordertableheader" width="7%"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_DATE'])?></td>
			<td   align="middle" valign="middle" class="ordertableheader" width="8%"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_EMAIL'])?></td>
			<td  align="middle" valign="middle" class="ordertableheader" width="5%" nowrap="nowrap"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_STATUS'])?></td>
			<td  align="middle" valign="middle" class="ordertableheader" width="4%"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_TOTAL'])?></td>
			<td  align="middle" valign="middle" class="ordertableheader" width="4%"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_REFUND'])?></td>
			<td   align="middle" valign="middle" class="ordertableheader" width="4%"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_PAYSTAT'])?></td>
			</tr>
			<?php
			if($db->num_rows($ret_select_ord))
			{  
			while($row_select_ord = $db->fetch_array($ret_select_ord))
			{ 
			
			$count_new = get_orderenquirypostnewcount($row_select_ord['order_id']);
			
			($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
			?>
			<tr onclick="window.location='index.php?req=orders&reqtype=order_det&order_id=<?php echo $row_select_ord['order_id']?>'" style="cursor:pointer" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
			<td align="middle" valign="middle" class="<?=$cls?>" > <?php echo $row_select_ord['order_id']; ?></td>
			<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"  ><?php echo $row_select_ord['order_dates']; ?></td>
			<td align="left" valign="middle" class="<?=$cls?>">
			<?php echo $row_select_ord['order_custemail']; ?></td>
			<td align="middle" valign="middle" class="<?=$cls?>"><?php echo getorderstatus_Name($row_select_ord['order_status']); ?></td>
			<td align="middle" valign="middle" class="<?=$cls?>"><?php echo print_price_selected_currency($row_select_ord['order_totalprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true);  ?></td>
			<td align="middle" valign="middle" class="<?=$cls?>"> <?php echo print_price_selected_currency($row_select_ord['order_refundamt'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true);  ?>	</td>
			<td align="middle" valign="middle" class="<?=$cls?>" nowrap="nowrap"><?php  echo getpaymentstatus_Name($row_select_ord['order_paystatus']); if($count_new==1){?> <img src="<?php url_site_image('new_post.gif')?>"   border="0" title="New" /><? }?></td>
			</tr>
			<?php
			}	
			?>
			<tr>
			<td align="center" valign="middle" class="pagingcontainertd_normal" colspan="7" >
			<?php 
				$path = '';
				$query_string .= "";
				paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
			?></td>
			</tr>
			<?PHP 
			}
			else 
			{
			?>
			
			<tr>
				<td align="center" valign="middle" class="shoppingcartcontent" colspan="7" >
					<?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_NOTFOUNDORDER'])?></td>
			</tr>	
			<?
			
			} 
			?>
			</table>
			</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>	
		</form>	
		<?
	  } 
	  
	  
	  /* ===========================================   ORDER DETAILS ============================= */
	  
	   function orderdet_Showform()
	  {
	    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$insmsg;
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
		$session_id = session_id();	// Get the session id for the current section
		$customer_id = get_session_var('ecom_login_customer');
		$Captions_arr['ORDER'] 					= getCaptions('ORDER');
		$Captions_arr['MY_DOWNLOADS'] 	= getCaptions('MY_DOWNLOADS'); // to get values for the captions from the general settings site captions
		
		$sort_by 			= (!$_REQUEST['ord_sort_by'])?'order_date':$_REQUEST['ord_sort_by'];
		$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
		//##########################################################################################################
		// Building the query to be used to display the orders
		//##########################################################################################################
		$where_conditions 	= "WHERE sites_site_id=$ecom_siteid  AND customers_customer_id='$customer_id' ";
	
		//##########################################################################################################
		// Check whether order id is given
		if($_REQUEST['order_id'])
		{
			$where_conditions .= " AND order_id='".add_slash($_REQUEST['order_id'])."'";
		}
		//##########################################################################################################
		//#Select condition for getting total count
		$sql_count 			= "SELECT count(*) as cnt 
								FROM 
									orders  
									$where_conditions";
		$res_count 			= $db->query($sql_count);
	
		list($tot_cnt) 	= $db->fetch_array($res_count);//#Getting total count of records
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Get the details of current order
		$sql_ord = "SELECT 	order_id,customers_customer_id,sites_site_id,sites_shops_shop_id,order_date,order_custtitle,order_custfname,
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
						$where_conditions 
					ORDER BY 
						$sort_by $sort_order ";
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord))
		{
			$row_select_ord = $db->fetch_array($ret_ord);
		}
		else // case if not record found
		{
			echo "Sorry Invalid Input";
			exit;
		}
	// Decide the error message to be displayed (if any)
	switch($_REQUEST['ern'])
	{
		case 1:
			$alert = 'Please login to download';
		break;
		case 2:
			$alert = 'Sorry!! not a valid customer';
		break;
		case 3:
			$alert = 'Sorry!! payment is not yet cleared for this order';
		break;
		case 4:
			$alert = 'Sorry!! this is a cancelled order';
		break;
		case 5:
			$alert 	= 'Sorry! Invalid Input';
		break;
		case 6:
			$alert = 'Sorry!! this download have been disabled. Please Contact Site Administrator';
		break;
		case 7:
			$alert = 'Sorry!! you are not authorized to download.';
		break;
		default:
			$alert = '';
	};
	$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									  <ul class="tree_menu">
									<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									<li><a href="'.url_link('myorders.html',1).'">'.stripslash_normal($Captions_arr['ORDER']['ORDER_MAINHEADING']).'</a></li>
									</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
	?>
 	<form method="post" name="frm_orderdetail" class="frm_cls">
 	<input type="hidden" name="hid_ordid" />	
	<?=$HTML_treemenu?>
	<?PHP if($_REQUEST['alert1']==1) { 
	$insmsg = "Enquiry Posted Sucessfully";?>
<? } 
if($insmsg)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							 $HTML_alert .=$insmsg;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
				?>
	<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
			
	
	<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
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
	$cls = 'usermenucontentbold';
	$srno++;
	 ?>
	<tr>
	<td width="14%" align="left" valign="top" class="<?php echo $cls?>" nowrap="nowrap" >Order ID &amp; Date </td>
	<td width="35%" align="left" valign="top" class="userordercontent" nowrap="nowrap" ><?php echo $row_select_ord['order_id']?>&nbsp;&nbsp;(<?php echo dateFormat($row_select_ord['order_date'],'datetime');?>)</td>
	<td width="20%" align="left" valign="top" class="<?php echo $cls?>" >Order Status</td>
	<td width="31%" align="left" valign="top" class="usermenucontentA"> 
		<?php echo getorderstatus_Name($row_select_ord['order_status']);
			  
			//if($row_select_ord['order_status']=='PENDING' || $row_select_ord['order_status']=='NEW') echo "<span id='cancelBut'>&nbsp;&nbsp;<input type=\"button\" name=\"cancel\" value=\" Cancel \" class=\"cart_btn\" onClick=\"javascript:ord_cancel(document.frm_orderdetail, '$row_select_ord[order_id]')\"></span>";
			if ((strtoupper($row_select_ord['order_status'])=='NEW' or strtoupper($row_select_ord['orde_status'])=='PENDING') 
					and (strtoupper($row_select_ord['order_paymentmethod'])=='SELF' or $row_select_ord['order_paymentmethod']=='') 
					and (strtoupper($row_select_ord['order_paystatus'])!='PAID' and strtoupper($row_select_ord['order_paystatus'])!='REFUNDED'))// If order is still in new or pending status
			{
				echo "<span id='cancelBut'>&nbsp;&nbsp;<div class=\"cart_shop_cont\"><div><input type=\"button\" name=\"cancel\" value=\" Cancel \" class=\"inner_btn_red\" onClick=\"javascript:ord_cancel(document.frm_orderdetail, '$row_select_ord[order_id]')\"></div></div></span>";				
			}	
			// If order is cancelled, the date of cancellation and also the person who cancelled it
			if($row_select_ord['order_cancelled_by']!=0)
			{
				if($row_select_ord['order_cancelled_from']=='A') // case cancelled from admin area
				{
					$cancelled_by = getConsoleUserName($row_select_ord['order_cancelled_by']);
				}
				else // case cancelled from client area
				{
					$sql_customer = "SELECT customer_title,customer_fname,customer_mname,customer_surname 
										FROM 
											customers 
										WHERE 
											customer_id = ".$row_select_ord['order_cancelled_by']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_customer = $db->query($sql_customer);
					if($db->num_rows($ret_customer))
					{
						$row_customer = $db->fetch_array($ret_customer);
						$cancelled_by = stripslash_normal($row_customer['customer_title']).stripslash_normal($row_customer['customer_fname'])." ".stripslash_normal($row_customer['customer_mname'])." ".stripslash_normal($row_customer['customer_surname']).' (customer)';
					}
				}	
				
				echo " (By ".$cancelled_by." on ".dateFormat($row_select_ord['order_cancelled_on'],'datetime').")";
			}
		   ?> </td>
	</tr>
	
	<tr id="cancelId" style="display:none;">
	<td colspan="4" width="14%" align="left" valign="middle" nowrap="nowrap"  class="usermenucontentA"/>
	<textarea name="txt_cancel" class="regiinput" cols="40" rows="3"></textarea>
		<div style="margin-right:30%">
	<div class="cart_shop_cont"><div><input type="button" name="cancelSub" class="inner_btn_red" value=" Submit Cancel Reason " onclick="javascript:cancelSubmit(document.frm_orderdetail,'<?PHP echo $row_select_ord[order_id]; ?>')"/></div></div>
	</div>
	</td>
	</tr>
	<?php 
			$srno++;
		 ?>
		 
	<tr>
	<td align="left" valign="middle" class="usermenucontentbold" >Payment Type </td>
	<td align="left" valign="middle" class="userordercontent" ><?php echo getpaymenttype_Name($row_select_ord['order_paymenttype'])?></td>
	<td align="left" valign="middle" class="usermenucontentbold" ><?php
			if($row_select_ord['order_paymentmethod']!='')
			{
			?>
	  Payment Method
	  <?php
			}
			else
				echo '&nbsp;';
			?>    
	</td>
	<td align="left" valign="middle" class="<?php echo $cls?>">
			<?php
			if($row_select_ord['order_paymentmethod']!='')
			{
				echo getpaymentmethod_Name($row_select_ord['order_paymentmethod']);
			}
			else
				echo '&nbsp;';
			?>
	</td>
	</tr>
	<?php
			$pay_str = $pay_usr = '';
			if($row_select_ord['order_paystatus_changed_manually']==1) // case if payment status changed manually
			{
				$sql_user = "SELECT user_title,user_fname,user_lname,sites_site_id  
								FROM 
									sites_users_7584 
								WHERE 
									user_id = ".$row_select_ord['order_paystatus_changed_manually_by']." 
								LIMIT
									1";
				$ret_user = $db->query($sql_user);
				if ($db->num_rows($ret_user))
				{
					$row_user 	= $db->fetch_array($ret_user);
					if ($row_user['sites_site_id']==0) // case of super admin
						$pay_usr = stripslash_normal($row_user['user_fname'])." ".stripslash_normal($row_user['user_lname']).' ('.dateFormat($row_select_ord['order_paystatus_changed_manually_on'],'datetime').')';
					else
						$pay_usr = stripslash_normal($row_user['user_title']).'.'.stripslash_normal($row_user['user_fname'])." ".stripslash_normal($row_user['user_lname']).' ('.dateFormat($row_select_ord['order_paystatus_changed_manually_on'],'datetime').')';
				}
				$pay_str =  "Payment Status Changed By ";
			}		
			$cls = 'usermenucontentbold'; 
			$srno++;
			?>
	<tr>
	<td align="left" valign="middle" class="<?php echo $cls?>" nowrap="nowrap" >Payment Status </td>
	<td colspan="3" align="left" valign="middle" class="userordercontent" > 
	<?php echo getpaymentstatus_Name($row_select_ord['order_paystatus'])?></td>
	</tr>
	<tr>
	<td colspan="4" align="left" valign="bottom"><!--<table width="100%" border="0" cellspacing="0" cellpadding="3">
	  <tr>
		<td width="79%" align="left" class="prod_orderheader">Products Awaiting Despatch </td>
	  </tr>
	</table> --></td>
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
									orders_order_id = ".$_REQUEST['order_id']." AND order_qty>0" ;//									AND order_qty>0";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				$cls = 'ordertableheader';
			?>
			<tr >
		<td colspan="7" class="prod_orderheader">Products Awaiting Despatch</td>
	  </tr>
	<tr>
	<td align="right" colspan="4" class="tdcolorgray_normal"><?php	
					$table_headers 		= array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmOrderDetails,\'checkboxprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmOrderDetails,\'checkboxprod[]\')"/>','Product','Available?','Retail Price','Disc','Sale Price','Rem Qty','Ord Qty','Net');
					$header_positions	= array('center','left','center','right','right','right','center','center','right');
					$colspan 			= count($table_headers);
				?>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr >
			<td align="left" class="<?php echo $cls?>" nowrap="nowrap"><div align="center">Product</div></td>
			<td align="center" class="<?php echo $cls?>" nowrap="nowrap"><div align="center">Available ? </div></td>
			<td class="<?php echo $cls?>" align="right" nowrap="nowrap"><div align="center">Retail Price</div></td>
			<td class="<?php echo $cls?>" align="right" nowrap="nowrap"><div align="center">Disc</div></td>
			<td class="<?php echo $cls?>" align="right" nowrap="nowrap"><div align="center">Sale Price</div></td>
			<td class="<?php echo $cls?>" align="center" nowrap="nowrap"><div align="center">Qty in Order</div></td>
		   <!-- <td class="<?php// echo $cls?>" align="center" nowrap="nowrap"><div align="center">Ord Qty</div></td> -->
			<td class="<?php echo $cls?>" align="right" nowrap="nowrap"><div align="center">Net</div></td>
		  </tr>
	
		  <?php
					$srno=1;
					//echo table_header($table_headers,$header_positions); 
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						
						$srno++;
						
						$org_qty 			= $row_prods['order_orgqty'];
						$sale_price			= $row_prods['product_soldprice'];
						$disc					= $row_prods['order_discount'];
						$disc_per_item	= ($disc/$org_qty);
						$net_total			= $sale_price * $row_prods['order_qty'];
						if($row_prods['order_discount']>0)
							$cur_disc = $row_prods['order_qty'] * $disc_per_item;
						else
							$cur_disc = 0;
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
							($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
				?>
		  <tr>  
			<td width="30%" align="left" class="<?PHP echo $cls;  ?>"><a class="edittextlink" href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prods['product_name']); ?></a>&nbsp;
					<?php
						echo get_ProductVarandMessage($_REQUEST['order_id'],$row_prods['orderdet_id']);
					?>	      </td>
			<td width="10%" align="center" class="<?PHP echo $cls;  ?>"><?php
						if ($row_prods['order_preorder']=='N')
						{
							echo 'In Stock';
						}
						else
							echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
					?>	</td>
			<td width="10%"  class="<?PHP echo $cls;  ?>" align="center">
			<?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?>
			</td>
			<td width="8%"   class="<?PHP echo $cls;  ?>" align="center">
			<?php
					if($row_prods['order_discount']>0)
					{
						if ($row_prods['order_discount_type']=='custgroup')
						{
							$disp_msg = 'Customer Group Discount<br/>Group: '.stripslash_normal($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
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
						
					}
					// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
					  echo print_price_selected_currency($cur_disc,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)
					 ?>
			</td>
			<td width="10%"  class="<?PHP echo $cls;  ?>" align="center"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
			<td width="8%"  class="<?PHP echo $cls;  ?>" align="center"><?php
					echo $row_prods['order_qty'];
				?>
				<input type="hidden" name="orgqty_<?php echo $row_prods['orderdet_id']?>" id="orgqty_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />            </td>
		   <!-- <td width="7%"   class="<?PHP// echo $cls;  ?>" align="center"><?php// echo $row_prods['order_orgqty']?> </td> -->
			<td width="20%"   class="<?PHP echo $cls;  ?>" align="right"><?php 
					//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
					echo print_price_selected_currency($net_total,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)
				?></td>
		  </tr>
		  
		  <?php		
						
					}
				?>
	  </table></td>
	</tr>
	<?php
				}
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,
						b.order_backorder_id ,b.backorder_qty,b.order_backorderon,b.order_backorderby 
					FROM
						order_details a,order_details_backorder b
					WHERE
						a.orders_order_id = ".$_REQUEST['order_id']." 
						AND a.orderdet_id = b.orderdet_id ";
	$ret_prods = $db->query($sql_prods);
	$atleast_one = false;
	if ($db->num_rows($ret_prods))
	{	
	$cls = 'ordertableheader';
	$clshead = 'usermenucontentbold';
	?>
	<tr>
	<td colspan="4" align="left" valign="bottom">
	
	<table width="100%" border="0">
	  <tr >
		<td colspan="7" class="prod_orderheader">Products Placed in Back Order</td>
	  </tr>
	  <tr>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Product</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Available?</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Retail Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Discount</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Sale Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">BackOrder Qty</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Net</div></td>
	  </tr>
	  <?PHP
		while ($row_prods = $db->fetch_array($ret_prods))
			{
				($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
				$srno++;
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['backorder_qty'];
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['backorder_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
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
				$atleast_one = true;
	   ?>
	  <tr>
		<td   class="<?PHP echo $cls;  ?>"><div align="left"><a class="edittextlink" href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prods['product_name']);?></a>&nbsp;
			<?php
				echo get_ProductVarandMessage($_REQUEST['order_id'],$row_prods['orderdet_id']);
			?>		</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?>	</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php 
	
		echo print_price_selected_currency($row_prods['order_retailprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			if($row_prods['order_discount']>0)
			{
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br/>Group: '.stripslash_normal($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
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
				
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			  echo print_price_selected_currency($cur_disc,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			echo $row_prods['backorder_qty'];
		?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)
			?></div></td>
	  </tr>
	  <? } ?>
	</table>
	
	
	  </td>
	</tr>
	
	<?PHP		
	}	
	
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
							a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
							a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
							a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
							a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
							a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
							a.order_discount_group_name,a.order_discount_group_percentage,
							b.despatched_id ,b.despatched_qty,b.despatched_on,b.despatched_by,
							b.despatched_reference,b.despatched_returned_atleastone,b.despatched_returned_qty   
					FROM
						order_details a,order_details_despatched b
					WHERE
						a.orders_order_id = ".$_REQUEST['order_id'] ." 
						AND a.orderdet_id = b.orderdet_id 
						$add_condition 
					ORDER BY 
						b.despatched_on DESC ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods) or $type=='despatch' or $type=='return')
	{
	$cls = 'ordertableheader';
	$clshead = 'usermenucontentbold';
	?>
	<tr>
	<td colspan="4" align="left" valign="bottom">
	
	<table width="100%" border="0">
	  <tr >
		<td colspan="7" class="prod_orderheader">Despatched Products</td>
	  </tr>
	  <tr>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Product</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Available?</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Retail Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Discount</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Sale Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Despatch Qty</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Net</div></td>
	  </tr>
	  <?PHP
		while ($row_prods = $db->fetch_array($ret_prods))
			{
				($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['despatched_qty'];
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['despatched_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
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
		<td   class="<?PHP echo $cls;  ?>"><div align="left"><a class="edittextlink" href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prods['product_name']);?></a>&nbsp;
			<?php
				echo get_ProductVarandMessage($_REQUEST['order_id'],$row_prods['orderdet_id']);
			?>		</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?>		</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?> </div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			if($row_prods['order_discount']>0)
			{
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br/>Group: '.stripslash_normal($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
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
				
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			  echo print_price_selected_currency($cur_disc,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?> </div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			echo $row_prods['despatched_qty'];
		?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)
			?></div></td>
	  </tr>
	  <? } ?>
	</table>
	
	
	  </td>
	</tr>
	
	<?PHP		
	}	
	
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
						a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
						a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
						a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
						a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
						a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
						a.order_discount_group_name,a.order_discount_group_percentage,
						b.order_cancelled_id ,b.cancelled_qty,b.order_cancelledon,b.order_cancelledby,b.order_refunded as cancel_refunded   
					FROM
						order_details a,order_details_cancelled b
					WHERE
						a.orders_order_id = ".$_REQUEST['order_id']."
						AND a.orderdet_id = b.orderdet_id ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
	$cls = 'ordertableheader';
	$clshead = 'usermenucontentbold';
	?>
	<tr>
	<td colspan="4" align="left" valign="bottom">
	
	<table width="100%" border="0">
	  <tr >
		<td colspan="7" class="prod_orderheader">Cancelled Products</td>
	  </tr>
	  <tr>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Product</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Available?</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Retail Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Discount</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Sale Price</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Cancelled Qty</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Net</div></td>
	  </tr>
	  <?PHP
		while ($row_prods = $db->fetch_array($ret_prods))
			{
				($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
				$srno++;
				$org_qty 			= $row_prods['order_orgqty'];
				$sale_price			= $row_prods['product_soldprice'];
				$disc					= $row_prods['order_discount'];
				$disc_per_item	= ($disc/$org_qty);
				$net_total			= $sale_price * $row_prods['cancelled_qty'];
				$cancel_str			= '<b>Cancelled by:</b> '.getConsoleUserName($row_prods['order_cancelledby']).' <br/><b>Cancelled On :</b>'.dateFormat($row_prods['order_cancelledon'],'datetime');
				if($row_prods['order_discount']>0)
					$cur_disc = $row_prods['cancelled_qty'] * $disc_per_item;
				else
					$cur_disc = 0;
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
		<td   class="<?PHP echo $cls;  ?>"><div align="left"><a class="edittextlink" href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prods['product_name']);?></a>&nbsp;
			<?php
				echo get_ProductVarandMessage($_REQUEST['order_id'],$row_prods['orderdet_id']);
			?>			</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				if ($row_prods['order_preorder']=='N')
				{
					echo 'In Stock';
				}
				else
					echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
			?>			</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">
		<?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?>
		</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			if($row_prods['order_discount']>0)
			{
				if ($row_prods['order_discount_type']=='custgroup')
				{
					$disp_msg = 'Customer Group Discount<br/>Group: '.stripslash_normal($row_prods['order_discount_group_name']);//.' ('.$row_prods['order_discount_group_percentage'].'%)';
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
				
			}
			// echo print_price_selected_currency($row_prods['order_discount'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
			  echo print_price_selected_currency($cur_disc,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?> </div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
			echo $row_prods['cancelled_qty'];
				?></div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php 
			//echo print_price_selected_currency($row_prods['order_rowtotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)
				echo print_price_selected_currency($net_total,$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)
			?></div></td>
	  </tr>
	  <? } ?>
	</table>
	
	
	  </td>
	</tr>
	
	<?PHP		
	}		
				// Get the products removed from current order
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
											orders_order_id = ".$_REQUEST['order_id']." 
									"; //,order_details_removed b  AND a.orderdet_id=b.orderdet_id
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				?>
				  <tr>
					<td colspan="4" align="left" valign="bottom">&nbsp;</td>
				  </tr>
	<? 		    }
				?>
	<tr>
	<td align="right" colspan="4" class="tdcolorgray_normal"><table width="100%" border="0" cellspacing="1" cellpadding="1">
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">Sub Total</td>
		<td width="24%" colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_subtotal'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Delivery Charge</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_deliverytotal'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Tax</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_tax_total'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">+ Total Gift Wrap Charge</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_giftwraptotal'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <?php
							if($row_select_ord['order_customer_discount_value']>0) // Check whether discount exists
							{
								if($row_select_ord['order_customer_or_corporate_disc']=='CUST')
								{
									if($row_select_ord['order_customer_discount_type']=='Disc_Group')
									$caption = 'Customer Group Discount ('.$row_select_ord['order_customer_discount_percent'].'%)';
								else
									$caption = 'Customer Discount ('.$row_select_ord['order_customer_discount_percent'].'%)';
								}	
								elseif($row_select_ord['order_customer_or_corporate_disc']=='CORP')
								{
									$caption = 'Corporate Discount ('.$row_select_ord['order_customer_discount_percent'].'%)';
								}						
						?>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">- <?php echo $caption?></td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_customer_discount_value'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <?php
							}
						?>
	  <?php
							if($row_select_ord['order_bonuspoint_discount']>0) // Check whether discount due to bonus points exists
							{
						?>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">- Bonus Points Discount (<?php echo $row_select_ord['order_bonuspoints_used']?> used)</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_bonuspoint_discount'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
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
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_totalprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
	  </tr>
	  <?php
							if($row_select_ord['order_refundamt']>0) // Check whether deposit exists and not cleared yet
							{
						?>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">Total Refunded</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_refundamt'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">Total Remaining after Refund</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_select_ord['order_totalprice']-$row_select_ord['order_refundamt']),$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
	  </tr>
	  <?php
							}
						?>
	  <?php
							if($row_select_ord['order_deposit_amt']>0) // Check whether deposit exists and not cleared yet
							{
						?>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">Product Deposit Amount</td>
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency($row_select_ord['order_deposit_amt'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		<td colspan="2" align="right" class="shoppingcartpriceB">------------------------------</td>
	  </tr>
	  <tr> </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB"><div id="productdeposit_div">
		  <?php
										if($row_select_ord['order_deposit_cleared']==1)
										{	
											$cleared_on = dateFormat($row_select_ord['order_deposit_cleared_on'],'datetime');
											$sql_usr	= "SELECT sites_site_id,user_title,user_fname,user_lname 
															FROM 
																sites_users_7584 
															WHERE 
																user_id = ".$row_select_ord['order_deposit_cleared_by']." 
															LIMIT 
																1";
											$ret_usr 	= $db->query($sql_usr);
											if ($db->num_rows($ret_usr))
											{
												$row_user = $db->fetch_array($ret_usr);
												if ($row_user['sites_site_id']==0) // case of super admin
													$cleared_by = stripslash_normal($row_user['user_fname'])." ".stripslash_normal($row_user['user_lname']);
												else
													$cleared_by = stripslash_normal($row_user['user_title']).".".stripslash_normal($row_user['user_fname'])." ".stripslash_normal($row_user['user_lname']);
											}	
											$cleared_msg  = 'Released Amount Cleared On '.$cleared_on; //Remaining by '.$cleared_by.' ('.$cleared_on.')'
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
		<td colspan="2" align="right" class="shoppingcartpriceB"><?php echo print_price_selected_currency(($row_select_ord['order_totalprice']-$row_select_ord['order_deposit_amt']),$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
	  </tr>
	  <tr>
		<td colspan="6" align="right" class="shoppingcartpriceB">&nbsp;</td>
		<td colspan="2" align="right" class="shoppingcartpriceB">====================</td>
	  </tr>
	  <?php
							}
						?>
	</table></td>
	</tr>
	
	<?PHP
	$sql_prods = "SELECT a.orderdet_id,a.order_delivery_data_delivery_id,a.products_product_id,a.product_name,a.order_qty,
							a.order_orgqty,a.product_soldprice,a.order_retailprice,a.product_costprice,a.order_discount,
							a.order_discount_type,a.order_rowtotal,a.order_preorder,a.order_preorder_available_date,
							a.order_refunded,a.order_refundedon,a.order_deposit_percentage,a.order_deposit_value,
							a.order_refundedby,a.order_deposit_percentage,a.order_deposit_value,a.order_dispatched,
							a.order_dispatched_on,a.order_dispatchedby,a.order_dispatched_id,a.order_stock_combination_id,
							a.order_discount_group_name,a.order_discount_group_percentage,
							b.return_id ,b.order_details_despatched_despatch_id,b.return_qty,b.return_on,b.return_by,
							b.return_type,b.return_reason    
					FROM
						order_details a,order_details_return b,order_details_despatched c
					WHERE
						a.orders_order_id = ".$_REQUEST['order_id']."
						AND a.orderdet_id = c.orderdet_id 
						AND b.order_details_despatched_despatch_id =c.despatched_id 
						$add_condition 
					ORDER BY 
						b.return_on DESC ";
	$ret_prods = $db->query($sql_prods);
	if ($db->num_rows($ret_prods))
	{
	$cls = 'ordertableheader';
	$clshead = 'usermenucontentbold';
	?>
	<tr>
	<td colspan="5" align="left" valign="bottom">
	
	<table width="100%" border="0">
	  <tr >
		<td colspan="5" class="prod_orderheader">Order Returns</td>
	  </tr>
	  <tr>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"> # </div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center">Return Date</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Product</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Return Qty</div></td>
		<td  class="<?PHP echo $cls;  ?>"><div align="center">Return By</div></td>
	 
	  </tr>
	  <?PHP
			while ($row_prods = $db->fetch_array($ret_prods))
				{
				($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
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
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				echo $srno;
				$srno++;
				?>	</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php echo dateFormat($row_prods['return_on'],'datetime_break');?>			</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="left">
		<a class="edittextlink" href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prods['product_name']);?></a>&nbsp;
				<?php
				echo get_ProductVarandMessage($_REQUEST['order_id'],$row_prods['orderdet_id']);
				?>	
		</div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				echo $row_prods['return_qty'];
				$return_type = ($row_prods['return_type']=='STK_BACK')?'Returned to Stock':'Marked as Damaged';
				echo '<br/><span class="homecontentusertabletdA">'.$return_type.'</span>';
			?>	 </div></td>
		<td   class="<?PHP echo $cls;  ?>"><div align="center"><?php
				echo getConsoleUserName($row_prods['return_by']);;
				?>	</div></td>
		</tr>
		
	  <?PHP
				}
	  ?>
	</table>
	
	
	  </td>
	</tr>
	
	<?PHP		
	}	
		// Check whether refund details exists. If exists 
				$sql_refcheck = "SELECT orders_order_id 
									FROM 
										order_details_refunded 
									WHERE 
										orders_order_id = ".$_REQUEST['order_id']."
									LIMIT 
										1";
				$ret_refcheck = $db->query($sql_refcheck);
				if($db->num_rows($ret_refcheck))
				{
									
				?>
	<tr>
	<td colspan="4" align="left" valign="bottom">&nbsp;</td>
	</tr>
	<?php
				}
		$sql_ord = "SELECT order_custtitle,order_custfname,order_custmname,order_custsurname,order_custcompany,
							order_buildingnumber,order_street,order_city,order_state,order_country,
							order_custpostcode,order_custphone,order_custfax,order_custmobile,
							order_custemail,order_notes  
					FROM 
						orders 
					WHERE 
						order_id = '".$_REQUEST['order_id']."' 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	
	?>
	
	<tr>
		<td   align="left"  colspan="4">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
	  <tr>
		<td width="3%" class="userorderheadernew" ><img id="refunddet_imgtag" src="<?PHP echo url_site_image('sel_tab_no.gif'); ?>" border="0" onclick="handle_expansionall(this,'bill','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
		<td width="97%" align="left" class="userorderheadernew" colspan="3">Billing Details</td>
	  </tr>
	</table></td></tr>
	<tr id="billDetails" style="display:none;"><td  width="100%" colspan="4">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
		$max_cols	= 1;
		$cur_col	= 0;
		// Including the file to show the dynamic fields for orders to the give position of static fields
		$cur_pos 		= 'Top';
		$show_header	= 1;
		include 'show_dynamic_fields_bill_orders.php';
		?>
			<tr>
				<td colspan="2" align="left" class="userorderheader">Billing Address</td>
			</tr>
			<?php
	
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'TopInStatic';
			$show_header	= 0;
			$max_cols	= 1;
			$cur_col	= 0;
			include 'show_dynamic_fields_bill_orders.php';
	
			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND field_hidden = 0 
											AND field_type = 'PERSONAL' 
										ORDER BY 
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$srno = 0;
				$name = stripslash_normal($row_ord['order_custtitle']).stripslash_normal($row_ord['order_custfname']).' '.stripslash_normal($row_ord['order_custmname']).' '.stripslash_normal($row_ord['order_custsurname']);
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkout_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkout_fname' and $row_checkout['field_key']!= 'checkout_mname' and $row_checkout['field_key'] != 'checkout_surname')
					{
						$caption = stripslash_normal($row_checkout['field_name']);
						$value	= stripslash_normal($row_ord[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}
	
					if ($proceed==true and $value!='')
					{
						if($cur_col==0)
						echo "<tr>";
				?>
									<td align="left" width="50%" >
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="left" width="50%" class="userordercontentbold">
											<?php echo $caption?>											</td>
											<td align="left" valign="middle" class="userordercontent">
											<?php echo $value?>											</td>
										</tr>
										</table>									</td>	
				<?php
				$cur_col++;
				//echo '<br/> curcol: '.$cur_col.' ---';
				if ($cur_col>=$max_cols)
				{
					echo "</tr>";
					$cur_col = 0;
					$cls = 'userordercontent';
					$srno++;
				}
					}
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'BottomInStatic';
			$show_header	= 0;
			include 'show_dynamic_fields_bill_orders.php';
			if ($cur_col<$max_cols)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>		
		</table> </td></tr>
	
	<tr>
	<td colspan="4" align="left" valign="bottom"><table width="100%" border="0" cellspacing="1" cellpadding="1">
	  <tr>
		<td width="3%" class="userorderheadernew"><img id="billing_imgtag" src="<?PHP echo url_site_image('sel_tab_no.gif'); ?>" border="0" onclick="handle_expansionall(this,'delivery','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
		<td width="97%" align="left" class="userorderheadernew">Delivery Details</td>
	  </tr>
	</table></td>
	</tr>
	<tr ><td  width="100%" colspan="4">
	<?PHP
	$sql_ord = "SELECT order_currency_convertionrate,order_currency_symbol,
							order_deliverytype,order_deliverylocation,order_delivery_option,
							order_deliverytotal,order_splitdeliveryreq,order_extrashipping,
							order_deliveryprice_only    
						FROM 
							orders 
						WHERE 
							order_id = '". $_REQUEST['order_id'] ."'
						LIMIT 
							1";
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord))
	{
		$row_ord = $db->fetch_array($ret_ord);
	}
	
	$sql_del = "SELECT delivery_title,delivery_fname,delivery_mname,delivery_lname,delivery_companyname,
							delivery_buildingnumber,delivery_street,delivery_city,delivery_state,delivery_country,
							delivery_zip,delivery_phone,delivery_fax,delivery_mobile,delivery_email 
					FROM 
						order_delivery_data  
					WHERE 
						orders_order_id =  '". $_REQUEST['order_id'] ."' 
					LIMIT 
						1";
	$ret_del = $db->query($sql_del);
	if ($db->num_rows($ret_del))
	{
		$row_del = $db->fetch_array($ret_del);
	}
	$cls = 'userordercontent';
	?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1" id="deliverDetails" style="display:none;"  >
		<tr>
			<td colspan="2" align="left" class="userorderheader">Delivery Address</td>
		</tr>
			<?php
			$max_cols	= 1;
			$cur_col	= 0;
			// Get the list of billing address static fields to be shown in the checkout out page in required order
			$sql_checkout = "SELECT field_key,field_name,field_orgname
										FROM 
											general_settings_site_checkoutfields 
										WHERE 
											sites_site_id = $ecom_siteid 
											AND field_hidden = 0 
											AND field_type = 'DELIVERY' 
										ORDER BY 
											field_order";
			$ret_checkout = $db->query($sql_checkout);
			if($db->num_rows($ret_checkout))
			{
				$name = stripslash_normal($row_del['delivery_title']).stripslash_normal($row_del['delivery_fname']).' '.stripslash_normal($row_del['delivery_mname']).' '.stripslash_normal($row_del['delivery_lname']);
				while($row_checkout = $db->fetch_array($ret_checkout))
				{
					$caption 	= '';
					$value		= '';
					if ($row_checkout['field_key']=='checkoutdelivery_title')
					{
						$caption 	= 'Name';
						$value		= $name;
						$proceed 	= true;
					}
					else if($row_checkout['field_key'] !='checkoutdelivery_fname' and $row_checkout['field_key']!= 'checkoutdelivery_mname' and $row_checkout['field_key'] != 'checkoutdelivery_surname')
					{
						$caption = stripslash_normal($row_checkout['field_name']);
						$value	= stripslash_normal($row_del[$row_checkout['field_orgname']]);
						$proceed = true;
					}
					else
					{
						$proceed = false;
					}
	
					if ($proceed==true and $value!='')
					{
						if($cur_col==0)
						echo "<tr>";
				?>
									<td align="left" width="50%">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="left" width="50%" class="userordercontentbold">
											<?php echo $caption?>											</td>
											<td align="left" valign="middle" class="userordercontent" >
											<?php echo $value?>											</td>
										</tr>
										</table>									</td>	
				<?php
				$cur_col++;
				//echo '<br/> curcol: '.$cur_col.' ---';
				if ($cur_col>=$max_cols)
				{
					echo "</tr>";
					$cur_col = 0;
					$cls = 'userordercontent';
					$srno++;
				}
					}
				}
			}
			// Including the file to show the dynamic fields for orders to the give position of static fields
			$cur_pos 		= 'Bottom';
			$show_header	= 1;
			$max_cols		= 1;
			$cur_col		= 0;
			include 'show_dynamic_fields_bill_orders.php';
			if ($cur_col<$max_cols)
			echo '<td colspan="'.($max_cols-$cur_col).'" class="'.$cls.'">&nbsp;</td></tr>';
		?>	
		<tr>
			<td colspan="2" align="left" class="userorderheader">Delivery Method Details</td>
		</tr>	
		<tr>
			<td colspan="2" align="left">
			<table width="100%" cellpadding="1" cellspacing="1" border="0">
			<?php
			$srno =1;
			if($row_ord['order_deliverytype']!='None')
			{
			?>
					<tr>
						<td align="left" width="25%" class="userordercontentbold">Delivery Type</td>
						<td align="left"  class="userordercontent" colspan="3"><?php echo ucwords(strtolower(stripslash_normal($row_ord['order_deliverytype'])))?></td>
						
					</tr>
			<?php
			$srno++;
			}
			if($row_ord['order_deliverylocation']!='')
			{
				$cls = 'userordercontent';
				$srno++;
			?>
					<tr>
						<td align="left" width="25%" class="userordercontentbold"  nowrap="nowrap">Delivery Location</td>
						<td align="left" width="25%" class="userordercontent"><?php echo ucwords(strtolower(stripslash_normal($row_ord['order_deliverylocation'])))?>(<?php echo ucwords(strtolower(stripslash_normal($row_ord['order_delivery_option'])))?>)</td>
						<td align="left" width="25%" class="<?php echo $cls?>"></td>
						<td align="left" width="25%" class="userordercontent"><?php echo print_price_selected_currency($row_ord['order_deliveryprice_only'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			if($row_ord['order_extrashipping']>0)
			{
				$cls = 'userordercontent';
				$srno++;
			?>	
					<tr>
						<td align="left" width="25%" class="userordercontentbold" nowrap="nowrap">Extra Shipping Charge</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="<?php echo $cls?>">&nbsp;</td>
						<td align="left" width="25%" class="<?php echo $cls?>"><?php echo print_price_selected_currency($row_ord['order_extrashipping'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			if ($row_ord['order_deliverytotal']>0)
			{
			?>
					<tr>
						<td align="left" width="25%" class="shoppingcartpriceB">&nbsp;</td>
						<td colspan="2" align="right" class="userordercontentbold" nowrap="nowrap">Total Delivery Charge</td>
						<td align="left" width="25%" class="userordercontent" nowrap="nowrap"><?php echo print_price_selected_currency($row_ord['order_deliverytotal'],$row_ord['order_currency_convertionrate'],$row_ord['order_currency_symbol'],true)?></td>
					</tr>
			<?php
			}
			?>		
			</table>			</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr><td  width="100%" colspan="4">
	<table width="100%" border="0"> <tr>    
	<td width="3%" class="userorderheadernew"><img id="billing_imgtag" src="<?PHP if(!$_REQUEST['ord_pg']) { echo url_site_image('sel_tab_no.gif'); } else {  echo url_site_image('sel_tab_yes.gif'); } ?>" border="0" onclick="handle_expansionall(this,'enqDetails','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
	 <td width="97%" align="left" class="userorderheadernew">Enquiry Details</td>
		</tr>
	</table>
	</td>
	</tr>
	<tr><td  width="100%" colspan="4">
	<table width="100%" border="0" cellpadding="1" cellspacing="0" id="enqDetails" <?PHP if(!$_REQUEST['ord_pg']) { ?> style="display:none;" <? } ?>  class="expandtable_cls">
	<tr class="userorderheader">
	  <td colspan="5" align="right"><a href="javascript:newEnqury(document.frm_orderdetail)" class="edithreflink" > 
		<input type="hidden" name="hid_enq" /><input type="hidden" name="reqtype" />
		Add New  Enquiry </a></td>
	  </tr>
	<tr id="EnqId4" style="display:none;" >
	  <td colspan="5" class="userorderheader"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_PLACEENQ']); ?></td>
	  </tr>
	<tr id="EnqId1" style="display:none;" >
	  <td colspan="2">&nbsp;Enquiry Title </td>
	  <td colspan="3" ><input type="text" name="txt_enqtitle" size="40" /></td>
	</tr>
	<tr id="EnqId2" style="display:none;" >
	  <td colspan="2">&nbsp;Enquiry Content</td>
	  <td colspan="3"><textarea name="txt_enquiry" cols="40" rows="6"></textarea>       
	   &nbsp;&nbsp;<br/>        </td>
	  </tr>
	<tr id="EnqId3" style="display:none;"  >
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td class="usermenucontentA">	
						<div class="cart_shop_cont"><div>
										<input type="button" name="Submit" value="Add Enquiry" class="inner_btn_red" onclick="javascript:enqSub(document.frm_orderdetail)" />
										<input type="hidden" name="hidenq" />
						</div></div>
				</td>
	  <td >&nbsp;</td>
	  <td>&nbsp;</td>
	</tr>
	<tr  class="userorderheader">
	  <td colspan="5"   ><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_EXIST'])?></td>
	  </tr>
	  <?PHP 
	  
		$sort_by			= $Settings_arr['orders_orderfield_enquiry'];
		$sort_order			= $Settings_arr['orders_orderby_enquiry'];
		$ordperpage         = $Settings_arr['orders_maxcntperpage_enquiry'];
		$enqsql = "SELECT query_id, DATE_FORMAT(query_date,'%d-%b-%Y %H:%i %p') AS query_dates, query_subject, query_status 
					FROM 
						order_queries 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND orders_order_id='".$_REQUEST['order_id']."' 
					ORDER BY 
						$sort_by $sort_order ";
		$res_count = $db->query($enqsql);
		$enqtot_cnt 	= $db->num_rows($res_count); //#Getting total count of records
		
		 // Call the function which prepares variables to implement paging
		$ret_arr 		= array();
		$pg_variable	= 'ord_pg';
		if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
		{
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$ordperpage,$enqtot_cnt);
			$Limit			= " LIMIT ".$start_var['startrec'].", ".$ordperpage;
		}	
		else
			$Limit = '';
	
		$enqsql .= "  $Limit "; 
			   
		$res = $db->query($enqsql);
		$num = $db->num_rows($res);
		?>
	  
	<tr>
	  <td width="9%" class="ordertableheader" align="center" valign="top"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_SLNO'])?></td>
	  <td width="42%" class="ordertableheader" align="center" valign="top"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_DATE'])?></td>
	  <td width="23%" class="ordertableheader" align="left" valign="top"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_SUB'])?></td>
	  <td width="14%" class="ordertableheader" align="center" valign="top"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_STATUS'])?></td>
	  <td width="12%" class="ordertableheader" align="center" valign="top"><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_NEWPOSTS'])?></td>
	</tr>
	<?PHP if($num>0) {
	
		while($row = $db->fetch_array($res))
		{			  
			$postsql = "SELECT count(post_status) AS postcnt 
							FROM 
								order_queries_posts 
							WHERE 
								order_queries_query_id= '".$row['query_id']."' 
								AND post_status='N'";
			$postres = $db->query($postsql);
			$postrow = $db->fetch_array($postres);
			$postcnt = $postrow['postcnt'];					
			$count++;
			($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
	?>
			<tr onclick="window.location='index.php?req=orders&reqtype=enqposts&enqid=<?PHP echo $row['query_id']; ?>&order_id=<?PHP echo $_REQUEST['order_id']; ?>'" style="cursor:pointer" class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
			  <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $count; ?></td>
			  <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $row['query_dates']; ?></td>
			  <td class="<?PHP  echo $cls; ?>" align="left" valign="top"><?PHP echo $row['query_subject']; ?></td>
			  <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $row['query_status']; ?></td>
			  <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $postcnt; ?></td>
			</tr>
	
	<?PHP 
		}
	?> 
	  <tr>
		  <td colspan="5" class="pagingcontainertd_normal" align="center"> 
		  <?php 
			 $path = '';
			 $query_string .= "";
			 $query_string .= "&amp;req=orders&amp;reqtype=order_det&amp;order_id=".$_REQUEST['order_id']."";
			 paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
		  ?>
		  </td>
	  </tr>
	  <?PHP 
		}elseif($num==0) {
	
	  ?>
	  <tr  class="ordertableheader">
		  <td colspan="5" align="center" class="userorderheader" bgcolor="#FFFFFF"  > No Details Found </td>
	  </tr> 
	  <?
		}//Else Ends Here
		?>
	</table>
	</td>
	</tr>
	<?php
		$sql_tot_download			 		= "SELECT count(ord_down_id) 
																FROM 
																		order_product_downloadable_products  
																WHERE
																		sites_site_id = $ecom_siteid 
																		AND customers_customer_id = $customer_id";
		$ret_tot_download 					= $db->query($sql_tot_download);
		list($tot_cntdownload) 			= $db->fetch_array($ret_tot_download); 
		
			$sql_download						= "SELECT a.ord_down_id,b.proddown_title,a.proddown_limited,a.proddown_limit,a.proddown_days_active,a.order_details_orderdet_id,
																					a.product_downloadable_products_proddown_id,a.ord_down_id,c.order_id,c.order_paystatus, 
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_start,'%d-%m-%Y') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_start,'%d-%m-%Y') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_startdate,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid' 
																								THEN date_format(a.proddown_days_active_start,'%H:%i:%S') 
																							WHEN 'free' 
																								THEN date_format(a.proddown_days_active_start,'%H:%i:%S') 
																							ELSE 
																								'--'
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_starttime,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_end,'%d-%m-%Y') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_end,'%d-%m-%Y') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_enddate,
																					CASE (a.proddown_days_active)
																					WHEN 1 
																						THEN 
																							CASE (c.order_paystatus)
																							WHEN 'Paid'
																								THEN date_format(a.proddown_days_active_end,'%H:%i:%S') 
																							WHEN 'free'
																								THEN date_format(a.proddown_days_active_end,'%H:%i:%S') 
																							ELSE 
																								'--'	
																							END
																					WHEN 0 
																						THEN ''
																					END as  active_endtime,
																					case (a.proddown_disabled)
																					WHEN 1
																						THEN 'Y' 
																					WHEN 0 
																						THEN 'N' 
																					END as disabled 
																		FROM
																			order_product_downloadable_products a, product_downloadable_products b ,orders c
																		WHERE
																			a.sites_site_id = $ecom_siteid  
																			AND c.order_id='".$_REQUEST['order_id']."' 
																			AND a.customers_customer_id = $customer_id 
																			AND a.product_downloadable_products_proddown_id = b.proddown_id 
																			AND a.orders_order_id = c.order_id 
																		ORDER BY
																			c.order_date ";																													
			$ret_download = $db->query($sql_download);
			if($db->num_rows($ret_download))
			{
		?>
		<tr>
			<td colspan="4" align="left" valign="bottom"><table width="100%" border="0" cellspacing="1" cellpadding="1">
			  <tr>
				<td width="3%" class="userorderheadernew"><img id="download_imgtag" src="<?PHP echo url_site_image('sel_tab_no.gif'); ?>" border="0" onclick="handle_expansionall(this,'downloads_table','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
				<td width="97%" align="left" class="userorderheadernew"><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_ORD_DOWN'])?></td>
			  </tr>
			</table></td>
		  </tr>
		<tr>
			<td colspan="4" align="left" valign="bottom">
			<table width="100%" border="0" cellspacing="1" cellpadding="1" style="display:none" id="downloads_table">
			<?php
				if($alert!='')
				{
			?>
					<tr>
					<td  colspan="8" align="center" valign="middle" class="userorderheader"><?php echo $alert?></td>
					</tr>
			<?php
				}
			?>
				<tr>
					<td align="left" width="3%" class="ordertableheader">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SLNO'])?></td>
					<td align="left" width="24%" class="ordertableheader">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_TITLE'])?></td>
					<td align="center" width="14%" class="ordertableheader">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_LIMIT'])?></td>
					<td align="left" width="14%" class="ordertableheader">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_START'])?></td>
					<td align="left" width="23%" class="ordertableheader">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_END'])?></td>
					<td align="left" width="5%" class="ordertableheader">&nbsp;					</td>
					<td align="left" width="7%" class="ordertableheader">&nbsp;</td>
				</tr>
				<?PHP 
					if($db->num_rows($ret_download)==0) {
				?>
				<tr>
					<td align="center" width="3%" class="userorderheader" colspan="7">
					<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NOT_FOUND'])?>&nbsp;</td>
				</tr>
				<?php
				} else {
				$i=1;
				while ($row_query = $db->fetch_array($ret_download))
				{
					// Find the id of product linked with current downloadable product
					$sql_prod = "SELECT products_product_id 
											FROM 
												order_details 
											WHERE 
												orderdet_id = ".$row_query['order_details_orderdet_id']." 
												AND orders_order_id = ".$row_query['order_id']."  
											LIMIT 
												1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
					}
					$force_hide_download_link	= false;
					// Check whether any download history exists for current downlodable item
					$sql_history = "SELECT DATE_FORMAT(track_date ,'%d-%m-%Y %h:%i:%s %r') as download_date 
												FROM 
													order_product_downloadable_products_customer_track 
												WHERE 
													order_product_downloadable_products_ord_down_id = ".$row_query['ord_down_id']." 
												ORDER BY 
													track_date DESC";
					$ret_history = $db->query($sql_history);
					$check_limited 		= $row_query['proddown_limited'];
					$check_daysactive	= $row_query['proddown_days_active'];
					if ($row_query['proddown_limited']==1) // case if download limit is set
					{
						if ($row_query['proddown_limit']<=$db->num_rows($ret_history)) // case if download limit reached
						{
							$force_hide_download_link = true;
						}	
					}
					if ($row_query['proddown_days_active']==1) // case if download period is set
					{
							if($row_query['active_startdate']!='--' and $row_query['active_enddate']!='--' )
							{
									$sp_date_arr = explode('-',$row_query['active_startdate']);
									$sp_time_arr	= explode('-',$row_query['active_starttime']);
									$st_mktime	= mktime($sp_time_arr[0],$sp_time_arr[1],$sp_time_arr[2],$sp_date_arr[1],$sp_date_arr[0],$sp_date_arr[2]);
									
									$sp_date_arr = explode('-',$row_query['active_enddate']);
									$sp_time_arr	= explode('-',$row_query['active_endtime']);
									$en_mktime	= mktime($sp_time_arr[0],$sp_time_arr[1],$sp_time_arr[2],$sp_date_arr[1],$sp_date_arr[0],$sp_date_arr[2]);
	
									$now_mktime  = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
									if ($now_mktime>$en_mktime or $now_mktime<$st_mktime)
										$force_hide_download_link = true;
							}			
							elseif($row_query['active_startdate']=='--' or $row_query['active_enddate']=='--' )
							{
								$force_hide_download_link = true;
							}
					}
					if ($row_query['disabled']=='Y')
						$force_hide_download_link = true;
				?>
					<tr class="edithreflink_tronmouse" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DOWNLOAD'])?>" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
						<td align="left" valign="middle" class="favcontent"><?php echo $i++;?></td>
						<td align="left" valign="middle" class="favcontent"><?php echo  stripslash_normal($row_query['proddown_title'])?></a></td>
						<td align="center" valign="middle" class="favcontent">
						<?php 
							if($row_query['proddown_limited']==1)
							{
								echo $row_query['proddown_limit'];
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}
						?>						</td>
						<td align="center" valign="middle" class="favcontent">
						<?php
							if ($row_query['proddown_days_active']==1)
							{
								if($row_query['active_startdate']!='--')
								{
										echo $row_query['active_startdate'].' '.$row_query['active_starttime'];
								}
								else
								{
									echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SET_PAY_SUCC']);
								}	
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}	
						?>						</td>
						<td width="23%" align="center" valign="middle" class="favcontent">
					  <?php
							if ($row_query['proddown_days_active']==1)
							{
								if($row_query['active_enddate']!='--')
								{	
									echo $row_query['active_enddate'].' '.$row_query['active_endtime'];
								}
								else
								{
									echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_SET_PAY_SUCC']);
								}	
							}
							else
							{
								echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NA']);
							}							
						?>						</td>
						<td width="5%" align="center" valign="middle" class="favcontent">
						<?php
							if(($row_query['order_paystatus']=='Paid' or $row_query['order_paystatus']=='free') and $force_hide_download_link==false)
							{
								$dld =$row_query['product_downloadable_products_proddown_id'].'~'.$row_query['order_id'].'~'.$row_query['ord_down_id'].'~ord';
								$dld =  urlencode(base64_encode($dld));
						?>
								<a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/download_product.php?dld=<?php echo $dld?>" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_CLICK_DOWN']);?>"><img src="<?php url_site_image('download.gif')?>" alt="Click to Download" border="0" /></a>
						<?php
							}
							else
							{
								if($row_query['order_paystatus']=='Paid'  or $row_query['order_paystatus']=='free')
								{
									if ($row_query['disabled']=='Y')
										$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DISABLE_ADM']);
									else
										$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_EXP']);
								}	
								else
									$hint = stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_PAY_NOT']);
						?>
								<img src="<?php url_site_image('download_disabled.gif')?>" alt="<?php echo $hint?>" title="<?php echo $hint?>" border="0" />
						<?php	
							}
						?>						</td>
						<td width="7%" align="center" valign="middle" class="favcontent">
						<?php 
						if ($db->num_rows($ret_history))
						{
						?>
							<a href="javascript:handle_downloadhistory('<?php echo $row_query['ord_down_id']?>')" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>">
							<img src="<?php url_site_image('download_viewhistory_enabled.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>" border="0" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HSO_HIST']);?>" /></a>
						<?php
						}
						else
						{
						?>
							<img src="<?php url_site_image('download_viewhistory_disabled.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NO_HIST']);?>" border="0" title="<?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_NO_HIST']);?>"/>
						<?php
						}						
						?>						</td>
					</tr>
				<?php
					// Building the customer download history
					if ($db->num_rows($ret_history))
					{
				?>
						<tr id="downloadhistory_tr_<?php echo $row_query['ord_down_id']?>" style="display:none" >
							<td colspan="4" align="left">&nbsp;</td>
							<td colspan="3" align="center">
								<table width="100%" cellpadding="1" cellspacing="1" border="0">
								<tr>
									<td align="center" width="5%" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_HASH']);?></td>
									<td align="left" width="95%" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_DOWN_ON']);?></td>
								</tr>
								<?php
								$cnt = 1;
								while ($row_history = $db->fetch_array($ret_history))
								{
								?>
									<tr>
										<td align="center" width="7%" class="favcontent"><?php echo $cnt++?>.</td>
										<td align="left" width="93%" class="favcontent"><?php echo $row_history['download_date']?></td>
									</tr>
								<?php
								}
								?>
								<tr>
									<td align="right" colspan="2" class="favcontent"><strong><?php echo stripslash_normal($Captions_arr['MY_DOWNLOADS']['DOWN_TOT_DOWN']);?> <?php echo ($cnt-1)?></strong></td>
								</tr>
								</table>							</td>
						</tr>
				<?php
					}
					
				}
			}	
				?>
		  </table>
			</td>
		</tr>
	<?php
			}
	?>	
	</table>
	</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>	
 </form>
<script language="javascript">
function newEnqury(frm) { 
    if(frm.hid_enq.value=='on') {
	frm.hid_enq.value = 'off'; 
	document.getElementById('EnqId4').style.display = 'none';
	document.getElementById('EnqId1').style.display = 'none';
	document.getElementById('EnqId2').style.display = 'none';
	document.getElementById('EnqId3').style.display = 'none';
	} else {
	frm.hid_enq.value = 'on';	
	document.getElementById('EnqId4').style.display = '';
	document.getElementById('EnqId1').style.display = '';
	document.getElementById('EnqId2').style.display = '';
	document.getElementById('EnqId3').style.display = '';
	}
}

 function enqSub(frm) {
	if(frm.txt_enqtitle.value=="") {
		alert("Please Enter Enquiry title");
		frm.txt_enqtitle.focus();
	} else if(frm.txt_enquiry.value=="") {
		alert("Please Enter Enquiry");
		frm.txt_enquiry.focus();
	} else {
		frm.hidenq.value='yes';
		frm.reqtype.value='Enquiry_Submit'
		frm.submit();
	}
}
function cancelSubmit(frm,value) {
	if(frm.txt_cancel.value=="") {
		alert("Please Select Reason For Cancellation");
		frm.txt_cancel.focus();
	} else {
		frm.hid_ordid.value=value;
		frm.reqtype.value='Order_Cancel';
		frm.submit();
	}
}

</script>
<?
	  } 
	  
	  /* ============================================= Enquiry Posts ================================= */
	  
function enqpost_Showpost()
{
	global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$inspost;
				if(check_IndividualSslActive())
				{
					$ecom_selfhttp = "https://";
				}
				else
				{
					$ecom_selfhttp = "http://";
				}
	$session_id = session_id();	// Get the session id for the current section
	$customer_id = get_session_var('ecom_login_customer');
	$Captions_arr['ORDER'] 	= getCaptions('ORDER');
	
	$sort_by 			=  $Settings_arr['orders_orderfield_enqposts']; //(!$_REQUEST['post_sort_by'])?'post_date':$_REQUEST['post_sort_by'];
	$sort_order 		=  $Settings_arr['orders_orderby_enqposts'];  //(!$_REQUEST['post_sort_order'])?'ASC':$_REQUEST['post_sort_order'];

//##########################################################################################################
// Building the query to be used to display the orders
//##########################################################################################################

//##########################################################################################################
// Check whether order id is given

if($_REQUEST['enqid'])
{
	$queryId = $_REQUEST['enqid'];
	$where_conditions 	= "WHERE order_queries_query_id='".$queryId."'";
}
//##########################################################################################################
//#Select condition for getting total count
$sql_count 			= "SELECT count(*) as cnt 
						FROM 
							order_queries_posts  
							$where_conditions";
$res_count 			= $db->query($sql_count);

list($tot_cnt) 	= $db->fetch_array($res_count);//#Getting total count of records
$ordperpage	= $Settings_arr['orders_maxcntperpage_enqposts'];// product per page
/////////////////////////////////For paging///////////////////////////////////////////
$sql = "SELECT query_subject, query_content FROM order_queries WHERE query_id='".$queryId."'";
$res = $db->query($sql);
$row = $db->fetch_array($res);

$query_subject = $row['query_subject'];
$querycontent = $row['query_content'];

// Call the function which prepares variables to implement paging
						$ret_arr 		= array();
						$pg_variable	= 'ord_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$ordperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$ordperpage;
						}	
						else
							$Limit = '';
// Get the details of current order
 	$sql_ord = "SELECT * FROM 
					order_queries_posts 
					$where_conditions 
				ORDER BY 
					$sort_by $sort_order ";
	$ret_ord = $db->query($sql_ord);
	if($db->num_rows($ret_ord))
	{
		$row_select_ord = $db->fetch_array($ret_ord);
	}
	
	?>
 <form method="post" name="frm_enqposts" class="frm_cls">
 <input type="hidden" name="hid_qryid" />
 <?PHP  $HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li>'.stripslash_normal($Captions_arr['ORDER']['ORDER_MAINHEADING']).'</li>
			 			 <li><a href="index.php?req=orders&reqtype=order_det&order_id='.$_REQUEST['order_id'].'">'.stripslash_normal($Captions_arr['ORDER']['ORDER_DETAILS']).'</a></li>

			 <li>'.stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTS']).'</li>

			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
		 if($_REQUEST['alert1']==2) {
 $inspost = "Post Details Added Sucessfully "; 
 }
 $HTML_img = $HTML_alert = $HTML_treemenu='';
		 
			if($inspost)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							 $HTML_alert .= $inspost;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;	
				?>	

<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['ORDER']['ORDER_ENQ_POSTS']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTS'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	

  <table width="100%" border="0" cellspacing="0" cellpadding="1"  class="reg_table">
  <tr><td>
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="expandtable_cls">
    <tr>
      <td colspan="5" align="left" class="userorderheader"><?=stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTHEAD']); ?></td>
    </tr>
    <tr>
      <td colspan="5" align="left" class="usermenucontentbold"><?=$query_subject; ?></td>
    </tr>
    <tr>
      <td colspan="5" align="left" class="userordercontent"><?PHP echo nl2br($querycontent); ?></td>
    </tr>
    <tr>
      <td colspan="5" align="right" class="userorderheader"><a href="javascript:postDisplay(document.frm_enqposts)" class="edithreflink" > 
        <input type="hidden" name="hid_post" />
        Add New Post </a>&nbsp;</td>
      </tr>
    
    <tr id="postDet4" style="display:none;"  >
      <td colspan="2">&nbsp;<strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_PLACEPOST'])?></strong></td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr id="postDet1"   style="display:none;" >
      <td colspan="2">&nbsp;Post Details </td>
      <td colspan="3"><br/><textarea name="txt_post" cols="35" rows="6"></textarea>        &nbsp;</td>
    </tr>
    <tr id="postDet2" style="display:none;"  >
      <td colspan="2">&nbsp;</td>
	     <td colspan="3" class="usermenucontentA">
		<div style="margin-right:44%">
		 <div class="cart_shop_cont"><div><input type="button" name="Submit" value="Add New Post" onclick="javascript:postSub(document.frm_enqposts)" class="inner_btn_red" />	</div></div></div>
        <input type="hidden" name="order_id" value="<?=$_REQUEST['order_id']?>" /> 
		        <input type="hidden" name="hidpost" /> 
		<input type="hidden" name="reqtype" />
		<input type="hidden" name="hidview" /></td>
      </tr>
      <tr id="postDet3" style="display:none;"  class="ordertableheader">
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
	  <?PHP if(is_array($row_select_ord)) { ?>
      <tr>
        <td colspan="5" class="userorderheader"> <?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQPOST_EXIST'])?> </td>
        </tr>
      <tr>
      <td width="9%" class="ordertableheader"><div align="center"><strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_SLNO'])?></strong></div></td>
      <td width="18%" class="ordertableheader"><div align="center"><strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_DATE'])?></strong></div></td>
      <td width="14%" class="ordertableheader"><div align="center"><strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTBY'])?></strong></div></td>
      <td width="8%" class="ordertableheader"><div align="center"><strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTNR'])?></strong></div></td>
      <td width="8%" class="ordertableheader"><div align="center"><strong><?php echo stripslash_normal($Captions_arr['ORDER']['ORDER_ENQ_POSTDET'])?></strong></div></td>
    </tr>
	<?PHP
			$postsql = "SELECT DATE_FORMAT(post_date,'%d-%b-%Y') AS post_dates, post_details, post_source, post_userid, post_status 
							FROM order_queries_posts 
							    WHERE order_queries_query_id= '".$_REQUEST['enqid']."' 
								ORDER BY 
					$sort_by $sort_order 
				
				    $Limit
			";
														
			$postres = $db->query($postsql);
			
			while($postrow = $db->fetch_array($postres)) {
						
		$count++;
	 ($cls=='order_detailstabletdcolorB')?$cls='order_detailstabletdcolorA':$cls='order_detailstabletdcolorB';

	?>
    <tr>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $count; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $postrow['post_dates']; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $postedby = ($postrow['post_source']=='C')?'Customer':'Administrator'; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>"><div align="center"><?PHP echo $post_status = ($postrow['post_status']=='N')?'New':'Read'; ?></div></td>
      <td nowrap="nowrap" class=" <?PHP echo $cls; ?>" align="center">&nbsp;<a href="#" onclick="javascript:view_post(document.frm_enqposts,'<?PHP echo $count; ?>')" class="edithreflink"> View </a></td>
    </tr>
	<tr id="view_<?PHP echo $count; ?>" style="display:none;">
      <td colspan="5" class="viewPostdetails" nowrap="nowrap"><div align="left"><?PHP echo nl2br($postrow['post_details']); ?></div></td>
    </tr>
	<?PHP } ?>
		   <tr>
      <td colspan="5" class="pagingcontainertd_normal" align="center">
	  <?php 
	     $path = '';
	     $query_string .= "";
	     $query_string .= "&amp;req=orders&amp;reqtype=enqposts&amp;enqid=".$_REQUEST['enqid']."&amp;order_id=".$_REQUEST['order_id']."";
	     paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
?></td>
      </tr>
	  <? } ?>
  </table></td></tr>
  </table>
								</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
 </form>
 <script language="javascript">
 function postDisplay(frm) {
	if(frm.hid_post.value=='on') { 
		frm.hid_post.value = 'off';
		document.getElementById('postDet4').style.display = 'none';
		document.getElementById('postDet1').style.display = 'none';
		document.getElementById('postDet2').style.display = 'none';
		document.getElementById('postDet3').style.display = 'none';
	} else {
		frm.hid_post.value = 'on';	
		document.getElementById('postDet4').style.display = '';
		document.getElementById('postDet1').style.display = '';
		document.getElementById('postDet2').style.display = '';
		document.getElementById('postDet3').style.display = '';
	}
}
function postSub(frm) {
	if(frm.txt_post.value=="") {
		alert("Please Enter Post Details");
		frm.txt_post.focus();
	} else {
		frm.hidpost.value='yes';
		frm.reqtype.value='Enquirypost_Submit'
		frm.submit();
	}
}
 </script>
<?
	  } 
	}  
	function get_ProductVarandMessage($order_id,$orderdet_id)
{
	global $db,$ecom_siteid;
	$ret_val = '';
	// Check whether any variables exists for current product in order_details_variables
	$sql_var = "SELECT var_name,var_value
						FROM
							order_details_variables
						WHERE
							orders_order_id = $order_id
							AND order_details_orderdet_id =".$orderdet_id;
	$ret_var = $db->query($sql_var);
	$cnts = 1;
	if ($db->num_rows($ret_var))
	{
		while ($row_var = $db->fetch_array($ret_var))
		{
			if($cnts>0)
			$ret_val .= "<br/>";
			$cnts++;
			$ret_val .= '<strong>'.stripslash_normal($row_var['var_name']).': </strong>'.stripslash_normal($row_var['var_value']);
		}
	}
	// Check whether any variables messages exists for current product in order_details_messages
	$sql_msg = "SELECT message_caption,message_value
							FROM
								order_details_messages
							WHERE
								orders_order_id = $order_id
								AND order_details_orderdet_id =".$orderdet_id;
	$ret_msg = $db->query($sql_msg);
	if ($db->num_rows($ret_msg))
	{
		while ($row_msg = $db->fetch_array($ret_msg))
		{
			if($cnts>0)
			$ret_val .= "<br/>";
			$cnts++;
			$ret_val .= '<strong>'.stripslash_normal($row_msg['message_caption']).':</strong> '.stripslash_normal($row_msg['message_value']);
		}
	}
	return $ret_val;
}
	  ?>		
