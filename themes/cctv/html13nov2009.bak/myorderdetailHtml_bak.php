<?php
	/*############################################################################
	# Script Name 	: myordersHtml.php
	# Description 	: Page which holds the display logic for My Address Book
	# Coded by 		: Randeep
	# Created on	: 02-May-2008
	##########################################################################*/
	/*class myorderdetail_Html
	{
	  function orderdet_Showform()
	  {
	    global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$insmsg;
		$session_id = session_id();	// Get the session id for the current section
		$customer_id = get_session_var('ecom_login_customer');
		$Captions_arr['ORDERS'] 	= getCaptions('ORDERS');
		
		$sort_by 			= (!$_REQUEST['ord_sort_by'])?'order_date':$_REQUEST['ord_sort_by'];
		$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
	//	$sort_options 		= array('order_date' => 'Order Date','order_status'=>'Order Status','order_pre_order'=>'Preorder');
	//	$sort_option_txt 	= generateselectbox('ord_sort_by',$sort_options,$sort_by);
	//	$sort_by_txt		= generateselectbox('ord_sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);
		
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
	?>
 	<form method="post" name="frm_orderdetail" class="frm_cls">
 	<input type="hidden" name="hid_ordid" />	
	<?PHP if(trim($insmsg)) { ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="userorderheader">
      <td colspan="5" align="center"><? echo $insmsg; ?></td>
    </tr>
</table>
<? } ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="4" align="left" valign="middle" class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >>  <a href="index.php?req=orders"><?php echo $Captions_arr['ORDERS']['ORDER_MAINHEADING']; ?></a> >> <?php echo $Captions_arr['ORDERS']['ORDER_DETAILS']?></td>
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
			$cls = 'usermenucontentbold';
			$srno++;
		 ?>
  <tr>
    <td width="14%" align="left" valign="top" class="<?php echo $cls?>" nowrap="nowrap" >Order ID &amp; Date </td>
    <td width="35%" align="left" valign="top" class="userordercontent" nowrap="nowrap" ><?php echo $row_select_ord['order_id']?>&nbsp;&nbsp;(<?php echo dateFormat($row_select_ord['order_date'],'datetime');?>)</td>
    <td width="20%" align="left" valign="top" class="<?php echo $cls?>" >Order Status</td>
    <td width="31%" align="left" valign="top" class="userordercontent"> 
	    <?php echo getorderstatus_Name($row_select_ord['order_status']);
		      
			//if($row_select_ord['order_status']=='PENDING' || $row_select_ord['order_status']=='NEW') echo "<span id='cancelBut'>&nbsp;&nbsp;<input type=\"button\" name=\"cancel\" value=\" Cancel \" class=\"buttonred_cart\" onClick=\"javascript:ord_cancel(document.frm_orderdetail, '$row_select_ord[order_id]')\"></span>";
			if ((strtoupper($row_select_ord['order_status'])=='NEW' or strtoupper($row_select_ord['orde_status'])=='PENDING') 
					and (strtoupper($row_select_ord['order_paymentmethod'])=='SELF' or $row_select_ord['order_paymentmethod']=='') 
					and (strtoupper($row_select_ord['order_paystatus'])!='PAID' and strtoupper($row_select_ord['order_paystatus'])!='REFUNDED'))// If order is still in new or pending status
			{
				echo "<span id='cancelBut'>&nbsp;&nbsp;<input type=\"button\" name=\"cancel\" value=\" Cancel \" class=\"buttonred_cart\" onClick=\"javascript:ord_cancel(document.frm_orderdetail, '$row_select_ord[order_id]')\"></span>";				
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
						$cancelled_by = stripslashes($row_customer['customer_title']).stripslashes($row_customer['customer_fname'])." ".stripslashes($row_customer['customer_mname'])." ".stripslashes($row_customer['customer_surname']).' (customer)';
					}
				}	
				
				echo " (By ".$cancelled_by." on ".dateFormat($row_select_ord['order_cancelled_on'],'datetime').")";
			}
		   ?> </td>
  </tr>
  
  <tr id="cancelId" style="display:none;">
   <td colspan="4" width="14%" align="left" valign="middle" nowrap="nowrap" />
    <textarea name="txt_cancel"></textarea>
	<input type="button" name="cancelSub" class="buttonred_cart" value=" Submit Cancel Reason " onclick="javascript:cancelSubmit(document.frm_orderdetail,'<?PHP echo $row_select_ord[order_id]; ?>')"/>
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
	<td align="left" valign="middle" class="<?php echo $cls?>"><?php
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
						$pay_usr = stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']).' ('.dateFormat($row_select_ord['order_paystatus_changed_manually_on'],'datetime').')';
					else
						$pay_usr = stripslashes($row_user['user_title']).'.'.stripslashes($row_user['user_fname'])." ".stripslashes($row_user['user_lname']).' ('.dateFormat($row_select_ord['order_paystatus_changed_manually_on'],'datetime').')';
				}
				$pay_str =  "Payment Status Changed By ";
			}		
			$cls = 'usermenucontentbold'; 
			$srno++;
			?>
  <tr>
    <td align="left" valign="middle" class="<?php echo $cls?>" nowrap="nowrap" >Payment Status </td>
    <td colspan="3" align="left" valign="middle" class="userordercontent" > 
	<?php echo  getpaymentstatus_Name($row_select_ord['order_paystatus'])?></td>
    
  </tr>
  
  <tr>
    <td colspan="4" align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td width="79%" align="left" class="userorderheader">Products in Order</td>
        <td width="21%" align="left" class="seperationtd_special">&nbsp;</td>
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
									order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id 
							 	FROM 
									order_details 
								WHERE 
									orders_order_id = ".$_REQUEST['order_id'];//									AND order_qty>0";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				$cls = 'ordertableheader';
			?>
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
            <td class="<?php echo $cls?>" align="center" nowrap="nowrap"><div align="center">Rem Qty</div></td>
            <td class="<?php echo $cls?>" align="center" nowrap="nowrap"><div align="center">Ord Qty</div></td>
            <td class="<?php echo $cls?>" align="right" nowrap="nowrap"><div align="center">Net</div></td>
          </tr>

          <?php
					$srno=1;
					//echo table_header($table_headers,$header_positions); 
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						
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
							$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edithreflink">';
							$link_req_suffix = '</a>';
						}
						else
							$link_req = $link_req_suffix= '';
							($cls=='ordertabletdcolorB')?$cls='ordertabletdcolorA':$cls='ordertabletdcolorB';
				?>
          <tr>  
            <td width="30%" align="left" class="<?PHP echo $cls;  ?>"><a href="<?php url_product($row_prods['products_product_id'],$row_prods['product_name'],-1)?>" class="edithreflink" title="<?php echo stripslashes($row_prods['product_name'])?>"><?php echo stripslashes($row_prods['product_name'])?></a>
                <?php
							// Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id 
												FROM 
													order_details_variables 
												WHERE 
													orders_order_id = ".$_REQUEST['order_id']." 
												LIMIT 
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($row_prods['order_dispatched']=='Y' or $db->num_rows($ret_varcheck))
							{
						
							}
						?>            </td>
            <td width="10%" align="center" class="<?PHP echo $cls;  ?>"><?php 
							if ($row_prods['order_preorder']=='N')
							{
								echo 'In Stock';
							}
							else
								echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
						?></td>
            <td width="10%"  class="<?PHP echo $cls;  ?>" align="center"><?php echo print_price_selected_currency($row_prods['order_retailprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
            <td width="8%"   class="<?PHP echo $cls;  ?>" align="center"><?php echo print_price_selected_currency($row_prods['order_discount'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
            <td width="10%"  class="<?PHP echo $cls;  ?>" align="center"><?php echo print_price_selected_currency($row_prods['product_soldprice'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
            <td width="8%"  class="<?PHP echo $cls;  ?>" align="center"><?php  echo $row_prods['order_qty'];
							
						?>
                <input type="hidden" name="orgqty_<?php echo $row_prods['orderdet_id']?>" id="orgqty_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />            </td>
            <td width="7%"   class="<?PHP echo $cls;  ?>" align="center"><?php echo $row_prods['order_orgqty']?> </td>
            <td width="20%"   class="<?PHP echo $cls;  ?>" align="right"><?php echo print_price_selected_currency($row_prods['order_rowtotal'],$row_select_ord['order_currency_convertionrate'],$row_select_ord['order_currency_symbol'],true)?></td>
          </tr>
          
          <?php		
						
					}
				?>
      </table></td>
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
									orders_order_id = ".$_REQUEST['order_id']." 
									AND a.orderdet_id=b.orderdet_id";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
				?>
  <tr>
    <td colspan="4" align="left" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="right" colspan="4" class="tdcolorgray_normal"><table width="100%" border="0" cellspacing="1" cellpadding="1">
          <?php
						$srno=1;
						$cnts = 0;
						//echo table_header($table_headers,$header_positions); 
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							$cls = 'shoppingcartheaderA';
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
								$link_req_prefix = '<a href="home.php?request=products&fpurpose=edit&checkbox[0]='.$row_prods['products_product_id'].'" title="View Product Details" class="edithreflink">';
								$link_req_suffix = '</a>';
							}
							else
								$link_req = $link_req_suffix= '';
					?>
          <tr>
            <td width="30%" align="left" class="<?php echo $cls?>"><?php echo $link_req_prefix.stripslashes($row_prods['product_name']).$link_req_suffix?>
                <?php /*?><div id='varremdiv_<?php echo $row_prods['orderdet_id']?>' class="show_vardiv_big" onclick="handle_showvariables(document.getElementById('varremtr_<?php echo $row_prods['orderdet_id']?>'),document.getElementById('varremdiv_<?php echo $row_prods['orderdet_id']?>'))" title="Click here"><img src="images/right_arr.gif" /></div><?php */?>
                <?php
							// Check whether the arrow is to be displayed here
							// So check whether variables exists for products or whether it is despatched
							$sql_varcheck = "SELECT orders_order_id 
												FROM 
													order_details_variables 
												WHERE 
													orders_order_id = ".$_REQUEST['order_id']." 
												LIMIT 
													1";
							$ret_varcheck = $db->query($sql_varcheck);
							if($row_prods['orderdet_dispatched']=='Y' or $db->num_rows($ret_varcheck))
							{
				
							}
							?>            </td>
            <td width="15%" align="center" class="<?php echo $cls?>"><?php 
								if ($row_prods['order_preorder']=='N')
								{
									echo 'In Stock';
								}
								else
									echo 'Available on <br/>'.dateFormat($row_prods['order_preorder_available_date'],'datetime');
							?>            </td>
            <td width="20%" class="<?php echo $cls?>" align="center">
			  <?php  echo $row_prods['order_qty']; ?>
	                <input type="hidden" name="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" id="orgqtyrem_<?php echo $row_prods['orderdet_id']?>" value="<?php echo $row_prods['order_qty']?>" />            </td>
            <td width="30%" align="center" class="<?php echo $cls?>"><?php echo dateFormat($row_prods['order_removedon'],'datetime')?></td>
          </tr>
          
          <?php
						}
					?>
      </table></td>
  </tr>
  <?php
				}
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
  <?php	
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
        <td width="3%" class="userorderheader" ><img id="refunddet_imgtag" src="<?PHP echo url_site_image('sel_tab_no.gif'); ?>" border="0" onclick="handle_expansionall(this,'bill','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
		<td width="97%" align="left" class="userorderheader" colspan="3">Billing Address</td>
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
				$name = stripslashes($row_ord['order_custtitle']).stripslashes($row_ord['order_custfname']).' '.stripslashes($row_ord['order_custmname']).' '.stripslashes($row_ord['order_custsurname']);
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
						$caption = stripslashes($row_checkout['field_name']);
						$value	= stripslashes($row_ord[$row_checkout['field_orgname']]);
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
        <td width="3%" class="userorderheader"><img id="billing_imgtag" src="<?PHP echo url_site_image('sel_tab_no.gif'); ?>" border="0" onclick="handle_expansionall(this,'delivery','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
		<td width="97%" align="left" class="userorderheader">Delivery Details</td>
      </tr>
    </table></td>
  </tr>
  <tr >
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
				$name = stripslashes($row_del['delivery_title']).stripslashes($row_del['delivery_fname']).' '.stripslashes($row_del['delivery_mname']).' '.stripslashes($row_del['delivery_lname']);
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
						$caption = stripslashes($row_checkout['field_name']);
						$value	= stripslashes($row_del[$row_checkout['field_orgname']]);
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
						<td align="left" width="25%" class="userordercontent"><?php echo ucwords(strtolower(stripslashes($row_ord['order_deliverytype'])))?></td>
						<td align="left" width="25%" class="shoppingcartheaderA">&nbsp;</td>
						<td align="left" width="25%" class="shoppingcartheaderA">&nbsp;</td>
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
						<td align="left" width="25%" class="userordercontent"><?php echo ucwords(strtolower(stripslashes($row_ord['order_deliverylocation'])))?>(<?php echo ucwords(strtolower(stripslashes($row_ord['order_delivery_option'])))?>)</td>
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
  <table width="100%" border="0"> <tr>    
   <td width="3%" class="userorderheader"><img id="billing_imgtag" src="<?PHP if(!$_REQUEST['ord_pg']) { echo url_site_image('sel_tab_no.gif'); } else {  echo url_site_image('sel_tab_yes.gif'); } ?>" border="0" onclick="handle_expansionall(this,'enqDetails','<?PHP echo $ecom_hostname; ?>')" title="Click"/></td>
     <td width="97%" align="left" class="userorderheader">Enquiry Details</td>
		</tr>
</table>
  <table width="100%" border="0" cellpadding="3" cellspacing="0" id="enqDetails" <?PHP if(!$_REQUEST['ord_pg']) { ?> style="display:none;" <? } ?> >
    <tr class="userorderheader">
      <td colspan="5" align="right"><a href="javascript:newEnqury(document.frm_orderdetail)" class="edithreflink" > 
        <input type="hidden" name="hid_enq" />
        Add New  Enquiry </a></td>
      </tr>
    <tr id="EnqId4" style="display:none;" >
      <td colspan="5" class="userorderheader"><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_PLACEENQ']; ?></td>
      </tr>
    <tr id="EnqId1" style="display:none;" class="ordertableheader">
      <td colspan="2">&nbsp;Enquiry Title </td>
      <td colspan="3" ><input type="text" name="txt_enqtitle" size="40" /></td>
    </tr>
    <tr id="EnqId2" style="display:none;" class="ordertableheader">
      <td colspan="2">&nbsp;Enquiry Content</td>
      <td colspan="3"><textarea name="txt_enquiry" cols="40" rows="6"></textarea>       
	   &nbsp;&nbsp;<br/>        </td>
      </tr>
    <tr id="EnqId3" style="display:none;"  class="ordertableheader">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" name="Submit" value="Add Enquiry" class="buttonred_cart" onclick="javascript:enqSub(document.frm_orderdetail)" />
        <input type="hidden" name="hidenq" /></td>
      <td >&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr  class="userorderheader">
      <td colspan="5" class="userorderheader"  ><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_EXIST']?></td>
      </tr>
	  <?PHP 
	  
	    $sort_by			= $Settings_arr['orders_orderfield_enquiry'];
       	$sort_order			= $Settings_arr['orders_orderby_enquiry'];
	  	$ordperpage         = $Settings_arr['orders_maxcntperpage_enquiry'];
 		$enqsql = "SELECT query_id, DATE_FORMAT(query_date,'%d-%b-%Y %H:%i %p') AS query_date, query_subject, query_status 
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
		if($num==0) {

	  ?>
	  <tr  class="ordertableheader">
          <td colspan="5" align="center" class="userorderheader" bgcolor="#FFFFFF"  > No Details Found </td>
      </tr> 
	  <?PHP }  else { ?>
    <tr>
      <td width="9%" class="ordertableheader" align="center" valign="top"><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_SLNO']?></td>
      <td width="15%" class="ordertableheader" align="center" valign="top"><?php echo $Captions_arr['ORDERS']['ORDER_DATE']?></td>
      <td width="43%" class="ordertableheader" align="left" valign="top"><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_SUB']?></td>
      <td width="18%" class="ordertableheader" align="center" valign="top"><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_STATUS']?></td>
      <td width="15%" class="ordertableheader" align="center" valign="top"><?php echo $Captions_arr['ORDERS']['ORDER_ENQ_NEWPOSTS']?></td>
    </tr>
	<?PHP
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
		    <tr>
		      <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $count; ?></td>
		      <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><a class="edithreflink" href="index.php?req=orders&reqtype=enqposts&enqid=<?PHP echo $row['query_id']; ?>&order_id=<?PHP echo $_REQUEST['order_id']; ?>"><?PHP echo $row['query_date']; ?></a></td>
		      <td class="<?PHP  echo $cls; ?>" align="left" valign="top"><a class="edithreflink" href="index.php?req=orders&reqtype=enqposts&enqid=<?PHP echo $row['query_id']; ?>&order_id=<?PHP echo $_REQUEST['order_id']; ?>"><?PHP echo $row['query_subject']; ?></a></td>
		      <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $row['query_status']; ?></td>
		      <td class="<?PHP  echo $cls; ?>" align="center" valign="top"><?PHP echo $postcnt; ?></td>
		    </tr>
  
	<?PHP 
		}
	?> 
	  <tr>
	      <td colspan="5" class="pagingcontainertd" align="center"> 
		  <?php 
		     $path = '';
		     $query_string .= "";
		     $query_string .= "&amp;req=orders&amp;reqtype=order_det&amp;order_id=".$_REQUEST['order_id']."";
		     paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Orders',$pageclass_arr); 	
	      ?>
		  </td>
      </tr>
	  <?PHP 
		} //Else Ends Here
		?>
  </table>
 </form>
<?
	  } 
	}  */
	  ?>	
