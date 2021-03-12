<? 
	set_time_limit(0);
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	include_once("../../../console/import_export_variables.php");
	$headers 	= array();
	$data 		= array();
	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	$cur_siteid =61;
	$fp = fopen('ord.csv','w');
	
			
			// ##############################################################################################
			// Case of Exporting order Details
			// ##############################################################################################
		
				$mod 		= 'export';
				$filename	= 'orders';
				$cnt_del=0;
				$cnt_ord=0;
				$cnt_gft =0;
				$_REQUEST['export_fields'][] = 'order_id';
				
				$_REQUEST['export_fields'][] = 'order_date';
				$_REQUEST['export_fields'][] = 'order_custfname';
				$_REQUEST['export_fields'][] = 'order_custmname';
				$_REQUEST['export_fields'][] = 'order_custsurname';
				$_REQUEST['export_fields'][] = 'order_custemail';
				$_REQUEST['export_fields'][] = 'order_pre_order';
				$_REQUEST['export_fields'][] = 'order_totalprice';
				$_REQUEST['export_fields'][] = 'order_status';
				$_REQUEST['export_fields'][] = 'order_deposit_amt';
				$_REQUEST['export_fields'][] = 'order_refundamt';
				$_REQUEST['export_fields'][] = 'order_paystatus';
				$_REQUEST['export_fields'][] = 'order_deliverytype';
				$_REQUEST['export_fields'][] = 'order_deliverylocation';
				$_REQUEST['export_fields'][] = 'order_delivery_option';
				$_REQUEST['export_fields'][] = 'order_paymenttype';
				$_REQUEST['export_fields'][] = 'order_paymentmethod';
				$_REQUEST['export_fields'][] = 'order_deliverytotal';
				$_REQUEST['export_fields'][] = 'order_giftwrap_message_charge';
				$_REQUEST['export_fields'][] = 'order_giftwrap_minprice';
				$_REQUEST['export_fields'][] = 'order_giftwraptotal';
				$_REQUEST['export_fields'][] = 'order_bonusrate';
				$_REQUEST['export_fields'][] = 'order_bonuspoint_discount';
				$_REQUEST['export_fields'][] = 'promotional_code_code_number';
				$_REQUEST['export_fields'][] = 'order_gift_voucher_number';
				$_REQUEST['export_fields'][] = 'delivery_fname';
				$_REQUEST['export_fields'][] = 'delivery_mname';
				$_REQUEST['export_fields'][] = 'delivery_lname';
				$_REQUEST['export_fields'][] = 'delivery_companyname';
				$_REQUEST['export_fields'][] = 'delivery_buildingnumber';
				$_REQUEST['export_fields'][] = 'delivery_street';
				$_REQUEST['export_fields'][] = 'delivery_city';
				$_REQUEST['export_fields'][] = 'delivery_state';
				$_REQUEST['export_fields'][] = 'delivery_country';
				$_REQUEST['export_fields'][] = 'delivery_zip';
				$_REQUEST['export_fields'][] = 'delivery_phone';
				$_REQUEST['export_fields'][] = 'delivery_mobile';
				$_REQUEST['export_fields'][] = 'delivery_email';
				$_REQUEST['export_fields'][] = 'delivery_completed';
				$_REQUEST['export_fields'][] = 'delivery_same_as_billing';
				$_REQUEST['export_fields'][] = 'products_order';
				$_REQUEST['export_fields'][] = 'gift_wrap';
				
				$_REQUEST['export_output_format'] = '';
				foreach ($order_field_arr as $k=>$v)
				{
					if (in_array($k,$_REQUEST['export_fields']))
					{	
						$headers[] = $v;
						if($k=='promotional_code_code_number')
						{
							$headers[] = 'Promotional Code Discount Value';
							$promdisc_value_required = true;
						}
						if($k=='order_gift_voucher_number')
						{
							$headers[] = 'Gift Voucher Discount Value';
							$voucherisc_value_required = true;
						}	
					}	
				}		
				//$field_list = implode(",",$_REQUEST['export_fields']);
				$prod_exists		= false;
				$field_list = implode(",",$_REQUEST['export_fields']);
				if(count($order_special_arr))
				{
					$temp 			= array();
					
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$order_special_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='products_order')
								$prod_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
					
				}
				
				
				// Check whether the special fields are to shown in the header row
				if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)
				{
					if(count($order_special_arr))
					{
						foreach ($order_special_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
				}
				$gift_exists		= false;
				if(count($order_special_gift_arr))
				{
					$temp 			= array();
					for($i=0;$i<count($_REQUEST['export_fields']);$i++)
					{
						if (!array_key_exists($_REQUEST['export_fields'][$i],$order_special_gift_arr))
						{
							$temp[] = $_REQUEST['export_fields'][$i];
						}
						else
						{
							if ($_REQUEST['export_fields'][$i]=='gift_wrap')
								$gift_exists = true;
						}	
					}
					$field_list = implode(",",$temp);
					
				}
				
				
				// Check whether the special fields are to shown in the header row
				if($_REQUEST['export_output_format'] != 'sql' and $gift_exists)
				{
					if(count($order_special_gift_arr))
					{
						foreach ($order_special_gift_arr as $k=>$v)
						{
							$headers[$k] = $v;
						}
					}
					if(count($order_gift_arr))
					{
						foreach ($order_gift_arr as $k=>$v)
						{
							$fields[] = $k;
						}
					}
					$field_gift_list = implode(',',$fields);	
				}	
				
					
				foreach($_REQUEST['export_fields'] as $v)
					{
						 if(substr($v,0,5)=='order' OR substr($v,0,11)=='promotional')
						 {
							$cnt_ord++;
						  	if($v!=''){
								if($cnt_ord>1)
								{
									$field_order_list .=",";
								}
								$field_order_list .=$v;
						  	}
						 }
						 elseif(substr($v,0,8)=='delivery')
						 {
							   $cnt_del++;
							   if($v!='')
							   {
									if($cnt_del>1){
									$field_delivery_list .=",";
								}
									$field_delivery_list .=$v;
						  }
						  
						 }
						 
					}
					
					foreach ($headers as $kkk=>$vvv)
						{
							$narr[$kkk] = add_quotes($vvv);
						}
					$headers = $narr;
				
				$header_str = implode(",", $headers);
				fwrite($fp,$header_str."\n");
				$ststr = explode('-',$_GET['st']);
				$edstr = explode('-',$_GET['ed']);
				$startdate 	= $ststr[2].'-'.$ststr[1].'-'.$ststr[0].' 00:00:00';
				$enddate 	= $edstr[2].'-'.$edstr[1].'-'.$edstr[0].' 23:59:59';
				echo "$startdate to $enddate<br><br>";
				$where_add = " AND (order_date between '".$startdate."' AND '".$enddate."' ) ";
				$sql 		= "SELECT $field_order_list FROM orders WHERE order_status NOT IN ('CANCELLED','NOT_AUTH') 
								AND sites_site_id = $cur_siteid $where_add ORDER BY order_id ASC";	
				$ret 		= $db->query($sql);
				
				if ($db->num_rows($ret))
				{     $ii =0;
					while ($row = $db->fetch_array($ret))
					{	
						$temp = array();
						$cnt = 1;
						foreach ($row as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							if($cnt%2==0)
							{
								if($_REQUEST['export_output_format'] != 'sql')
								{
									if($k=='order_pre_order')// case of preorder
										$v = ($v==1)?'Y':'N';
										if($k=='order_status')// case of preorder
										$v = getorderstatus_Name($row['order_status'],true);
										if($k=='order_paymenttype')// case of preorder
										$v = getpaymenttype_Name($row['order_paymenttype']);
										if($k=='order_paystatus')// case of preorder
										$v = getpaymentstatus_Name($row['order_paystatus']);
										if($k=='order_paymentmethod')// case of preorder
										$v = getpaymentmethod_Name($row['order_paymentmethod']);
										if($k=='order_date')// case of preorder
										$v = dateFormat($row['order_date'],'');
										
								}		
								$temp[$k] = $v;
							}
							$cnt++;
						}	
						$hold_temp = array();
						foreach ($temp as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
						{
							//echo "<br>".$k.' v--'.$v;
							$hold_temp[$k]=$v;
							if($k=='promotional_code_code_number')
							{
								if($v!='')
								{
									// Get the promotional code discount value from the table order_promotional_code
									$sql_ordprm = "SELECT code_lessval 
													FROM 
														order_promotional_code 
													WHERE 
														orders_order_id=".$row['order_id']." 
														AND code_number='".$v."' 
													LIMIT 
														1";
									$ret_ordprm = $db->query($sql_ordprm);
									if($db->num_rows($ret_ordprm))
									{
										$row_ordprm = $db->fetch_array($ret_ordprm);
										$hold_temp['promotional_code_discount'] = $row_ordprm['code_lessval'];
									}
									else
									{
										$hold_temp['promotional_code_discount']='';
									}
								}
								else
								{
									$hold_temp['promotional_code_discount']='';
								}	
							}
							if($k=='order_gift_voucher_number')
							{
								if($v!='')
								{
									// Get the gift vocher value from the table order_voucher
									$sql_ordprm = "SELECT voucher_value_used 
													FROM 
														order_voucher 
													WHERE 
														orders_order_id=".$row['order_id']." 
														AND voucher_no='".$v."' 
													LIMIT 
														1";
									$ret_ordprm = $db->query($sql_ordprm);
									if($db->num_rows($ret_ordprm))
									{
										$row_ordprm = $db->fetch_array($ret_ordprm);
										$hold_temp['order_gift_voucher_discount'] = $row_ordprm['voucher_value_used'];
									}
									else
									{
										$hold_temp['order_gift_voucher_discount']='';
									}
								}
								else
								{
									$hold_temp['order_gift_voucher_discount']='';
								}
							}
							
						}
						$temp = $hold_temp;
						//print_r($temp);
					$res =array();
					if($row['order_id'])//case of orderid exists
					{	
					   if($field_delivery_list!=''){//Gets the delivery details section.
					   $sql_del		= "SELECT $field_delivery_list FROM order_delivery_data WHERE orders_order_id=".$row['order_id']." LIMIT 1";
	                   $ret_del		= $db->query($sql_del);
						if ($db->num_rows($ret_del))
						{
							while ($row_del = $db->fetch_array($ret_del))
							{
								$temp_del = array();
								$cnt_del = 1;
								foreach ($row_del as $k=>$v)//filtering the repeated fields due to mysql_fetch_array
								{
									if($cnt_del%2==0)
									{
										if($_REQUEST['export_output_format'] != 'sql')
										{
											if($k=='delivery_same_as_billing')// case of preorder
												$v = ($v==1)?'Y':'N';
										}		
										$temp[$k] = $v;
									}
									$cnt_del++;
								}
							}
					    }
						else
						{
						 for($i=0;$i<$cnt_del;$i++)
						  {
						  $temp[$i] = "--";
						  }
						}
					  }
					  $row_prod_arrys_str ='';
					  $row_prod_str = '';
					  $row_prod_arrys = array();
					  $row_field_list = array();
					   if($_REQUEST['export_output_format'] != 'sql' and $prod_exists)// case if products for order picked from database
						{
						  foreach($order_product_arr as $k=>$v)
							{
							  $order_prod_name[] = $v;
							  $order_prod_arr[]=$k;
							}
							$order_product_list = implode(',',$order_prod_arr);
							$sql_prods = "SELECT $order_product_list,orderdet_id,order_stock_combination_id,products_product_id  
									FROM 
										order_details 
									WHERE 
										orders_order_id = ".$row['order_id']." ORDER BY orderdet_id";
							$ret_prods = $db->query($sql_prods);
						  if($db->num_rows($ret_prods)>0)
							{
								$cnt_prods=0;
									while($row_prods = $db->fetch_array($ret_prods))
									{
										$row_field_list = array();
										$row_field_list[$cnt_prods]=$row_prods['product_name'];
										$cnt_prods ++;
										$sql_var = "SELECT var_name,var_value
										FROM 
											order_details_variables 
										WHERE 
											orders_order_id = ".$row['order_id']."
										AND order_details_orderdet_id =".$row_prods['orderdet_id'];
							
										$ret_var = $db->query($sql_var);
										$cnt_vars = $cnt_prods;//1;
										if ($db->num_rows($ret_var))
											{
												while ($row_var = $db->fetch_array($ret_var))
												{ 
													//$cnt_vars++;
													$row_field_list[$cnt_vars]= stripslashes($row_var['var_name']).': '.stripslashes($row_var['var_value']);
														$cnt_vars++;
												}
											}
										$row_prod_str = implode(',',$row_field_list);
										$specialcodeval = $specialcodeval_str = '';
										if(is_product_special_product_code_active())
										{
											$specialcodeval = show_specialcode($row_prods['products_product_id'],$row_prods['order_stock_combination_id'],$row_prods['orderdet_id']);	
											if($specialcodeval!='')
											{
												$specialcodeval_str = ', Special Code: '.$specialcodeval;
											}
										}	
										$row_prod_arrys[$cnt_prods]=$row_prod_str.$specialcodeval_str." x ".$row_prods['order_orgqty'] .'(qty)';
									}
									$row_prod_arrys_str = implode('~',$row_prod_arrys);
									$temp['products_order'] = $row_prod_arrys_str;
								}
							}
							$row_gfield_list = array();
							$row_gift_arrys_str ='';
							if($_REQUEST['export_output_format'] != 'sql' and $gift_exists)// case if products for order picked from database
							{	
								$sql_gift		= "SELECT $field_gift_list,id FROM order_giftwrap_details WHERE orders_order_id=".$row['order_id'];
								$ret_gift		= $db->query($sql_gift);
								if ($db->num_rows($ret_gift))
								{ 
									$cnt_gfts=0;
									while ($row_gift = $db->fetch_array($ret_gift))
									{
										 $id= $row_gift['id'];
										 $cnt_gfts++;
										 $row_gfield_list[$cnt_gfts]=$row_gift['giftwrap_name'].",".$row_gift['giftwrap_price'];
									}  
									  $row_gift_arrys_str = implode('~',$row_gfield_list);
									  $temp['gift_wrap'] = $row_gift_arrys_str;
								}
							}	
						}
						$ii++;
						array_push($data,$temp);
						//print implode(",", $headers) . "\r\n";
						foreach($data as $r) {
							$narr = array();
							foreach ($r as $kkk=>$vvv)
							{
								$narr[$kkk] = add_quotes($vvv);
							}
							
							//array_walk($r, "add_quotes");
							$data_str = implode(",", $narr);
							fwrite($fp,$data_str."\n");
							
						}
						$data = array();
					  }
					}
	fclose($fp);

	$db->db_close();
	
	echo "Operation Completed";
	
	function add_quotes($d)
	{
		return $d = '"' . str_replace('"', '""', stripslashes($d)) . '"';

	}
				
	function is_product_special_product_code_active()
	{
		global $db,$cur_siteid;
		$enable = false;
		$sql_set = "SELECT enable_special_product_code FROM general_settings_sites_common_onoff WHERE sites_site_id = $cur_siteid LIMIT 1";
		$ret_set = $db->query($sql_set);
		if($db->num_rows($ret_set))
		{
			$row_set = $db->fetch_array($ret_set);
			if($row_set['enable_special_product_code']==1)
			{
				$enable = true;
			}
		}	
		return $enable;
	}
	
	function find_combination_id_special($prodid,$var_arr)
	{ 
		global $db,$cur_siteid,$ecom_hostname;
		$sql_prod = "SELECT product_variablestock_allowed,product_webstock,product_preorder_allowed,
							product_total_preorder_allowed,product_instock_date,product_variablecomboprice_allowed,
							product_variablecombocommon_image_allowed  
						FROM
							products
						WHERE
							product_id=$prodid
							AND sites_site_id = $cur_siteid
						LIMIT
							1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
			

			
				if (count($var_arr))
				{
					$varids = array();
					foreach ($var_arr as $k=>$v)
					{
						// Check whether the variable is a check box or a drop down box
						$sql_check = "SELECT var_id
										FROM
											product_variables
										WHERE
											var_id=$k
											AND var_value_exists = 1
										LIMIT
											1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check))
						{
							$row_check 	= $db->fetch_array($ret_check);
							$varids[] 	= $k; // populate only the id's of variables which have values to the array
						}
					}
					// Find the various combinations available for current product
					$sql_comb = "SELECT comb_id
									FROM
										product_variable_combination_stock
									WHERE
										products_product_id = $prodid";
					$ret_comb = $db->query($sql_comb);
					if ($db->num_rows($ret_comb))
					{

						while ($row_comb = $db->fetch_array($ret_comb))
						{
							$comb_found_cnt = 0;
							// Get the combination details for current combination
							$sql_combdet = "SELECT comb_id,product_variables_var_id,product_variable_data_var_value_id
												FROM
													product_variable_combination_stock_details
												WHERE
													comb_id = ".$row_comb['comb_id']."
													AND products_product_id=$prodid";
							$ret_combdet = $db->query($sql_combdet);
							if ($db->num_rows($ret_combdet))
							{  
								if ($db->num_rows($ret_combdet)==count($varids))// check whether count in table is same as that of count in array
								{
									while ($row_combdet = $db->fetch_array($ret_combdet))
									{
										if (in_array($row_combdet['product_variables_var_id'],$varids))
										{
											if ($var_arr[$row_combdet['product_variables_var_id']]==$row_combdet['product_variable_data_var_value_id'])
											{
												$comb_found_cnt++;
											}
										}
									}
								}
							}					

							if (($comb_found_cnt and count($varids)) and $comb_found_cnt==count($varids))
							{ 
								$ret_data['combid']	= $row_comb['comb_id'];
								return $ret_data; // return from function as soon as the combination found
							}
						}
					}
				}
		}
		$ret_data['combid']			= 0;
		return $ret_data; // return from function as soon as the combination found
	}
function show_specialcode($product_id,$comb_id,$orddet_id)
{
	global $db,$cur_siteid;
	$specialcode = '';
	if($comb_id!=0) // case of combination stock
	{
		$sql_sel = "SELECT comb_special_product_code 
						FROM 
							product_variable_combination_stock 
						WHERE 
							comb_id=$comb_id 
						LIMIT 1";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			$row_sel = $db->fetch_array($ret_sel);
			$specialcode = trim(stripslashes($row_sel['comb_special_product_code']));		
		}	
	}
	else
	{
		$combination = 0;
		$sql_det_sp = "SELECT var_id, var_value FROM order_details_variables WHERE order_details_orderdet_id = $orddet_id AND var_value !=''";
		$ret_det_sp = $db->query($sql_det_sp);
		$sp_var_arr = array();
		if($db->num_rows($ret_det_sp))
		{
			while ($row_det_sp = $db->fetch_array($ret_det_sp))
			{
				// find the value id
				$sql_valid = "SELECT var_value_id FROM product_variable_data WHERE product_variables_var_id =".$row_det_sp['var_id']. " AND var_value ='".$row_det_sp['var_value']."' LIMIT 1";
				$ret_valdid = $db->query($sql_valid);
				if($db->num_rows($ret_valdid))
				{
					$row_valid = $db->fetch_array($ret_valdid);
					$sp_var_arr[$row_det_sp['var_id']] = $row_valid['var_value_id'];
				}
			}
			$combination_arr_sp = find_combination_id_special($product_id ,$sp_var_arr);
			$combination = $combination_arr_sp['combid'];
		}
		if($combination!=0) // case if combination id can be found 
		{
			$sql_unqprod = "SELECT comb_special_product_code FROM product_variable_combination_stock WHERE comb_id = ".$combination." LIMIT 1";
			$ret_unqprod = $db->query($sql_unqprod);
			if($db->num_rows($ret_unqprod))
			{
				$row_unqprod 	= $db->fetch_array($ret_unqprod);
				$specialcode	= $row_unqprod['comb_special_product_code'];
			}
		}
		else
		{
			// try to get the product code directly from products table
			$sql_prod= "SELECT product_special_product_code 
							FROM 
								products 
							WHERE 
								product_id = $product_id 
							LIMIT 
								1";
			$ret_prod = $db->query($sql_prod);
			if($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$specialcode = trim(stripslashes($row_prod['product_special_product_code']));
			} 
		}	
	}
	if($specialcode!='')
	{
		return $specialcode;
	}
}
function getpaymenttype_Name($key)
{
	global $db,$cur_siteid;
	$site_cap = '';
	if ($key)
	{
		if($key=='none')
		{
			return 'None';
		}
		else
		{
			$sql = "SELECT paytype_id,paytype_name
					FROM
						payment_types
					WHERE
						paytype_code = '".$key."'
					LIMIT
						1";
			$ret = $db->query($sql);
			if ($db->num_rows($ret))
			{
				$row = $db->fetch_array($ret);
				return $row['paytype_name'];
			}
		}
	}
}
/*
Function to get the name of payment method
*/
function getpaymentmethod_Name($key)
{
	global $db,$cur_siteid;
	$site_cap = '';
	if ($key)
	{
		$sql = "SELECT paymethod_id,paymethod_name
				FROM
					payment_methods
				WHERE
					paymethod_key = '".$key."'
				LIMIT
					1";
		$ret = $db->query($sql);
		if ($db->num_rows($ret))
		{
			$row = $db->fetch_array($ret);
			return $row['paymethod_name'];
		}
	}
}
function getpaymentstatus_Name($key)
{
	global $db,$cur_siteid;
	switch($key)
	{
		case 'pay_on_phone':
		case 'pay_on_account':
		case 'cash_on_delivery':
		case 'invoice':
		case 'cheque':
		case 'SELF':
			$caption = 'Not Paid';
		break;
		/* ------------------- 4 min finance - start ---------------------------*/
		case '4min_finance':
			$caption = 'Check 4 Minute Finance';
		break;
		case 'INITIALISE':
		case 'PREDECLINE':
		case 'ACCEPT':
		case 'DECLINE':
		case 'REFER':
		case 'VERIFIED':
		case 'AMENDED':
		case 'FULFILLED':
		case 'COMPLETE':
		case 'CANCELLED':
		case 'CANCEL':
			$caption = ucwords(strtolower($key));
		break;
		case 'ACTION-CUSTOMER':
			$caption = 'Pending Verification';
		break;
		
		/* ------------------- 4 min finance - end ---------------------------*/
		case 'HSBC':
		case 'GOOGLE_CHECKOUT':
		case 'WORLD_PAY':
		case 'PAYPAL_EXPRESS':
		case 'PAYPALPRO':
		case 'PAYPAL_HOSTED':
		case 'NOCHEX':
		case 'REALEX':
		case 'ABLE2BUY':
		case 'PROTX_VSP':
		case 'PROTX':
		case 'BARCLAYCARD':
		case 'VERIFONE':
		case 'CARDSAVE':
			$caption = 'Check '.getpaymentmethod_Name($key);
		break;
		case 'Pay_Failed':
			$caption = 'Payment Failed';
		break;
		case 'Paid':
			$caption = 'Paid';
		break;
		case 'Pay_Hold':
			$caption = 'Placed on Account';
		break;
		case 'REFUNDED':
			$caption = 'Refunded';
		break;
		case 'DEFERRED':
			$caption = 'Deferred';
		break;
		case 'PREAUTH':
			$caption = 'Preauth';
		break;
		case 'AUTHENTICATE':
			$caption = 'Authenticate';
		break;
		case 'ABORTED':
			$caption = 'Deferred Aborted';
		break;
		case 'CANCELLED':
			$caption = 'Authorise Cancelled';
		break;
		case 'free':
			$caption = 'Free';
		break;
		case 'FRAUD_REVIEW':
			$caption = 'Fraud rule review check';
		break;
		
		/* additional statsus */
		case 'CARD':
			$caption = 'Credit Card';
		break;
		case 'CHEQUE':
			$caption = 'Cheque / DD';
		break;
		case 'BANK':
			$caption = 'Bank Transfer';
		break;
		case 'PHONE':
			$caption = 'Pay on Phone';
		break;
		case 'CASH':
			$caption = 'Cash';
		break;
		case 'OTHER':
			$caption = 'Other';
		break;
		case '3D_SEC_CHECK':
			$caption = 'Redirected for 3D Secure Password';
		break;
	};
	return $caption;
}
function getorderstatus_Name($key,$clean_output=false)
{
	global $db,$cur_siteid;
	switch($key)
	{
		case 'NEW':
		$caption = 'Unviewed';
		break;
		case 'PENDING':
		$caption = 'Pending';
		break;
		case 'INPROGRESS':
		$caption = 'In Progress';
		break;
		case 'DESPATCHED':
		$caption = 'Despatched';
		break;
		case 'ONHOLD':
		$caption = 'On Hold';
		break;
		case 'BACK':
		$caption = 'Back Order';
		break;
		case 'CANCELLED':
		$caption = 'Cancelled';
		break;
		case 'NOT_AUTH':
		if ($clean_output==false)
			$caption = '<span style="color:#FF0000">Incomplete Order</span>';
		else
			$caption = 'Incomplete Order';
		break;

	};
	return $caption;
}	
function dateFormat($passdt, $type = "default") {
	#############fromat of displaying date '4:21pm -Mar 11 Sat'
	//$arow[orddt] in yyyy-mm-dd hh:mm:sec format
	$sp_dt1=explode(" ",$passdt);
	$sp_dt = explode("-",$sp_dt1[0]);
	$rt_year=intval($sp_dt[0]);
	$rt_month=(integer)$sp_dt[1];
	$rt_day=(integer)$sp_dt[2];
	$sp_dt2 = explode(":",$sp_dt1[1]);
	$rt_hr = (integer)$sp_dt2[0];
	$rt_min = (integer)$sp_dt2[1];
	$rt_sec = (integer)$sp_dt2[2];
	$unixstamp=mktime ($rt_hr,$rt_min,$rt_sec,$rt_month,$rt_day,$rt_year);
	// $dtdisp=@date("h :i a"." - "."M d Y D",$unixstamp);
	if($type == 'time') {
		$dtdisp = @date("h :i a",$unixstamp);
	}
	elseif($type == 'datetime'){
		$dtdisp = @date("d-M-Y",$unixstamp)."&nbsp;".@date("h:i a",$unixstamp);
	}
	elseif($type == 'datetime_break'){
		$dtdisp = @date("d-M-Y",$unixstamp)."<br/>".@date("h:i a",$unixstamp);
	}
	else {
		$dtdisp = @date("d-M-Y",$unixstamp);
	}
	return $dtdisp;
	//int mktime (int hour, int minute, int second, int month, int day, int year [, int is_dst])
}	
?>
