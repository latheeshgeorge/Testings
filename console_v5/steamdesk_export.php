<?php
	include_once("functions/functions.php");
	//include('session.php');
	require_once("sites.php");
	require_once("config.php");
	$sec_key = trim($_REQUEST['key']);
	$error_msg = '';
    if($sec_key=='')
	{
		$error_msg = 'Security key is empty';
	}
	else
	{
		// Check whether security key is valid
		$sql_check = "SELECT sites_site_id 
						FROM 
							general_settings_sites_common 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND steamdesk_security_key ='". mysql_real_escape_string($sec_key)."' 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rowS($ret_check)==0)
		{
			$error_msg .= 'Invalid Security Key';
		}
		else // case if security key is valid
		{			
			
				$table_name 		= 'orders';
				//#Sort
				$sort_by 			= (!$_REQUEST['ord_sort_by'])?'order_date':$_REQUEST['ord_sort_by'];
				$sort_order 		= (!$_REQUEST['ord_sort_order'])?'DESC':$_REQUEST['ord_sort_order'];
								
				//##########################################################################################################
				// Building the query to be used to display the orders
				//##########################################################################################################
				$where_conditions 	= "WHERE sites_site_id=$ecom_siteid ";
				$where_conditions .= " AND order_status NOT IN ('CANCELLED','NOT_AUTH')"; // This is done to avoid the cancelled orders while asked to show all orders
				$disp_more = false; // variable which decides whether the more options is to be made visible by default
				
				// current financial year orders only
				$cur_year = date("Y"); 
				$cur_month = date("n"); 
				if($cur_month < 4)	
				{
					$fn_f_year = $cur_year -1;
				}
				else
				{
					$fn_f_year = $cur_year;
				}
				if($cur_month < 4)	
				{
					$fn_l_year = $cur_year;
				}
				else
				{
					$fn_l_year = $cur_year + 1;
				}
				
				$fn_f_date = $fn_f_year."-04-01 00:00:00";
				$fn_l_date = $fn_l_year."-03-31 23:59:59";
				$where_conditions .= " AND (order_date BETWEEN '".$fn_f_date."' AND '".$fn_l_date."') ";
				
			$xml_content .=
			'<?xml version="1.0" encoding="ISO-8859-1" ?>
			<ROOT xmlns:sql="urn:schemas-microsoft-com:xml-sql">';	
			
				$sql_qry_download = "SELECT order_id,sites_site_id,order_date,order_custtitle,order_custfname,order_custmname,order_custsurname,order_custcompany,order_buildingnumber,order_street,order_city,order_state,order_country,order_custpostcode,order_custphone,order_custemail,order_paymenttype,order_paymentmethod,order_deliverytotal,order_tax_total,order_paystatus,order_status,order_totalprice,order_totalauthorizeamt,
									gift_vouchers_voucher_id,promotional_code_code_id,order_bonuspoint_discount,
							CASE order_status 
							WHEN 'NEW' THEN 'Unviewed'
							else
								order_status
							END as ordstat  
						FROM 
							$table_name 
							$where_conditions AND order_steamdesk_exported = 0 
						ORDER BY 
							$sort_by $sort_order
						 ";
				$ret_order_download = $db->query($sql_qry_download);
				
				if ($db->num_rows($ret_order_download))
						{ 
							while ($row_order_download = $db->fetch_array($ret_order_download))
							{
								
								$order_id_download = $row_order_download['order_id'];
							
								$sql_prods_download = "SELECT orderdet_id,order_delivery_data_delivery_id,products_product_id,product_name,order_qty,
										order_orgqty,product_soldprice,order_retailprice,product_costprice,order_discount,
										order_discount_type,order_rowtotal,order_preorder,order_preorder_available_date,
										order_refunded,order_refundedon,order_deposit_percentage,order_deposit_value,
										order_refundedby,order_deposit_percentage,order_deposit_value,order_dispatched,
										order_dispatched_on,order_dispatchedby,order_dispatched_id,order_stock_combination_id,
										order_discount_group_name,order_discount_group_percentage,order_freedelivery,order_detail_discount_type,order_prom_id    
									FROM
										order_details
									WHERE
										orders_order_id = $order_id_download"; 
										//AND order_qty>0";
								
								//calculationg the total discount for DiscountTotal tag.
								$ret_prods_download = $db->query($sql_prods_download);
								$total_disc = 0;
								if ($db->num_rows($ret_prods_download))
								{
									while ($row_prods_download = $db->fetch_array($ret_prods_download))
									{
										$org_qty 			= $row_prods_download['order_orgqty'];
										$disc				= $row_prods_download['order_discount'];
										$disc_per_item		= ($disc/$org_qty);
										if($row_prods_download['order_discount']>0)
										{
											$cur_disc 		= $row_prods_download['order_orgqty'] * $disc_per_item;
										}
										else
										{
											$cur_disc = 0;
										}
										
										$total_disc = $total_disc + $cur_disc;
									}
								}
								
								/*$total_disc =$total_disc.".0000";*/
								$total_disc = discount_convert_to_four_dec($total_disc);
								
								//split the date and time for InvoiceDate,InvoiceTime tags
								list($day, $month, $year, $hour, $minute, $second) = split('[- :]', $row_order_download['order_date']); 
								$timestamp_order_download=mktime($hour, $minute,$second, $month, $day, $year);
			
								$order_deliverytotal = convert_to_four_dec($row_order_download['order_deliverytotal']);
								$order_totalprice 	 = convert_to_four_dec($row_order_download['order_totalprice']);				
								$order_tax_total 	 = convert_to_four_dec($row_order_download['order_tax_total']);
							
				
				$gift_discount = $prom_discount = $bonus_discount = '0.0000';
				$disc_code 		= 'Discount Voucher';
				$prom_code		= 'Promotional Code';
				$gift_nominal	= '1201';
				$prom_nominal	= '1202';
				$bonus_nominal 	= '1204';
				if($row_order_download['gift_vouchers_voucher_id']>0) 
				{
					$sql_vouch 		= "SELECT voucher_value_used FROM order_voucher WHERE orders_order_id='".$row_order_download['order_id']."'";
					$res_vouch 		= $db->query($sql_vouch);
					$row_vouch 		= $db->fetch_array($res_vouch);
					$gift_discount 	= $row_vouch['voucher_value_used'];
					if($gift_discount>0)
					{
						$gift_discount 	= discount_convert_to_four_dec($gift_discount);
						$gift_nominal	= '1201';
						$disc_code  = 'Discount Voucher';
					}	
				}
				if($row_order_download['promotional_code_code_id']>0) 
				{
					$sql_gift		= "SELECT code_lessval FROM order_promotional_code WHERE orders_order_id='".$row_order_download['order_id']."'";
					$res_gift 		= $db->query($sql_gift);
					$row_gift 		= $db->fetch_array($res_gift);
					$prom_discount 	= $row_gift['code_lessval'];
					if($prom_discount>0)
					{
						$prom_discount 	= discount_convert_to_four_dec($prom_discount);
						$prom_nominal	= '1202';
						$prom_code  	= 'Discount Promotional';
					}	
				}
				if($row_order_download['order_bonuspoint_discount']>0) 
				{
					$bonus_discount = discount_convert_to_four_dec($row_order_download['order_bonuspoint_discount']);
					$bonus_nominal	= '1204';
				}
				$xml_content .="
				<tblInvoicePosted>
					<InvoiceNumber>".$row_order_download['order_id']."</InvoiceNumber>
					<AccountRef></AccountRef>
					<CARR_NET>".$order_deliverytotal."</CARR_NET>
					<CARR_NOM_CODE>Standard</CARR_NOM_CODE>
					<CARR_TAX>0</CARR_TAX>
					<InvoiceDate>".date("Y-m-d", $timestamp_order_download)."</InvoiceDate>
					<InvoiceTime>".date("H:i", $timestamp_order_download)."</InvoiceTime>
					<InvoiceType>1</InvoiceType>
					<Payment_Type>".prepare_string(stripslashes($row_order_download['order_paymenttype']))."</Payment_Type>
					<SageCustomerName>". prepare_string(stripslashes($row_order_download['order_custtitle'])).prepare_string(stripslashes($row_order_download['order_custfname'])).' '.prepare_string(stripslashes($row_order_download['order_custsurname']))."</SageCustomerName>
					<SageCompanyName>".prepare_string(stripslashes($row_order_download['order_custcompany']))."</SageCompanyName>
					<EmailAddress>".prepare_string(stripslashes($row_order_download['order_custemail']))."</EmailAddress>
					<SageAddress1>".prepare_string(stripslashes($row_order_download['order_buildingnumber']))."</SageAddress1>
					<SageAddress2>".prepare_string(stripslashes($row_order_download['order_street']))."</SageAddress2>
					<SageAddress3>".prepare_string(stripslashes($row_order_download['order_city']))."</SageAddress3>
					<SageAddress4>".prepare_string(stripslashes($row_order_download['order_state']))."</SageAddress4>
					<SageAddress5>".prepare_string(stripslashes($row_order_download['order_country']))."</SageAddress5>
					<SageAddress6>".prepare_string(stripslashes($row_order_download['order_custpostcode']))."</SageAddress6>
					<Telephone>".prepare_string(stripslashes($row_order_download['order_custphone']))."</Telephone>
					<GoodsTotal>".$order_totalprice."</GoodsTotal>
					<ShippingTotal>".$order_deliverytotal."</ShippingTotal>
					<TaxTotal>".$order_tax_total."</TaxTotal>		
					<Discount>
					<Name>".$disc_code."</Name>
					<Nominal>".$gift_nominal."</Nominal>
					<Amount>".$gift_discount."</Amount>
					</Discount>
					<Discount>
					<Name>".$prom_code."</Name>
					<Nominal>".$prom_nominal."</Nominal>
					<Amount>".$prom_discount."</Amount>
					</Discount>
					<Discount>
					<Name>Bonus Points</Name>
					<Nominal>".$bonus_nominal."</Nominal>
					<Amount>".$bonus_discount."</Amount>
					</Discount>
					</tblInvoicePosted>";
								$ret_order_prods_download = $db->query($sql_prods_download);
								$total_disc = 0;
								if ($db->num_rows($ret_order_prods_download))
								{
									while ($row_order_prods_download = $db->fetch_array($ret_order_prods_download))
									{
										//for NET_AMOUNT tag
										$sale_price			= $row_order_prods_download['product_soldprice'];
										$net_total			= $sale_price ;//* $row_order_prods_download['order_qty'];
										
										//for TAX_CODE tag
										$product_id = $row_order_prods_download['products_product_id'];
										$sql_prod_tax = "SELECT product_applytax FROM products WHERE product_id = $product_id";
										$ret_prod_tax = $db->query($sql_prod_tax);
										$row_prod_tax = $db->fetch_array($ret_prod_tax);
										if($row_prod_tax['product_applytax']=="Y")
										{
											$product_applytax = 1;
										}
										else
										{
											$product_applytax = 0;
										}
										//for InvItemDESCRIPTION tag
										$order_id = $order_id_download;
										$orderdet_id = $row_order_prods_download['orderdet_id'];
										$product_name = stripslashes($row_order_prods_download['product_name']);
										$ret_val = '';
										/* Check whether any variables exists for current product in order_details_variables*/
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
												$ret_val .= ",";
												$cnts++;
												if(stripslashes($row_var['var_value']) == "")
												{
													$row_var['var_value'] = "Yes";
												}
												$ret_val .= stripslashes($row_var['var_name']).':'.stripslashes($row_var['var_value']);
											}
										}
										$product_des = $product_name.$ret_val;
										$net_total 	 = convert_to_two_dec($net_total);
										
				$xml_content .=  
					"<tblInvoiceItemPosted>
					<InvoiceNumber>".$row_order_download['order_id']."</InvoiceNumber>
					<STOCK_CODE></STOCK_CODE>
					<NOMINAL_CODE></NOMINAL_CODE>
					<NET_AMOUNT>".$net_total."</NET_AMOUNT>
					<TAX_CODE>".$product_applytax."</TAX_CODE>
					<QTY_ORDER>".$row_order_prods_download['order_orgqty']."</QTY_ORDER>
					<InvItemDESCRIPTION>".prepare_string($product_des)."</InvItemDESCRIPTION>
					<InvItemText></InvItemText>
					<Tax_Rate></Tax_Rate>
					<TAX_AMOUNT></TAX_AMOUNT>
				</tblInvoiceItemPosted>
					";
									}
								}
							}
			$xml_content.=
			'</ROOT>';	
							header ("Content-Type:text/xml");   
							header("Content-Disposition: attachment; filename=\"".str_replace('www.','',$ecom_hostname)."_orders_for_sage.xml");
							echo $xml_content;
						}
			

		}
	}
	
	
	// convert to four decimal places			
	function convert_to_four_dec($val)
	{
		
		$val_arr = explode(".", $val);
		if($val_arr[1])
		{
			$val= $val."00";
		}
		else
		{
			$val= $val.".0000";
		}
		return $val;
	}
	function discount_convert_to_four_dec($val)
	{
		
		$val_arr = explode(".", $val);
		if($val_arr[1])
		{
			$diff = (4 - strlen($val_arr[1]));
			if($diff>0)
			{
				$diff_str = '';
				for($i=0;$i<$diff;$i++)
				{
					$diff_str .= '0';
				}
				$val= $val.$diff_str;
			}	
			elseif($diff<0)
			{
				$val = $val_arr[0].'.'.substr($val_arr[1],0,4);
			}
		}
		else
		{
			$val= $val.".0000";
		}
		return $val;
	}
	
	// convert to two decimal places
	function convert_to_two_dec($val)
	{
		$val_arr = explode(".", $val);
		if($val_arr[1])
		{
			//$val= $val."00";
		}
		else
		{
			$val= $val.".00";
		}
		return $val;
	}
	function prepare_string($str)
	{
		$sr_arr = array("&","'","\"",">","<");
		$rp_arr = array("&amp;","&apos;","&quot;","&gt;","&lt;");
		$str = str_replace($sr_arr,$rp_arr,$str);
		$str = utf8_encode($str);
		return $str;
	}	
?>