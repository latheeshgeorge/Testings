<?php
	include_once('../../../classes/db_class.inc.php');	// Page which holds the class for db operations
	include '../../../config_db.php';
	include_once '../../../functions/functions.php';

	include_once '../../../includes/urls.php';

	$db	 				= new db_mysql($dbhost,$dbuname,$dbpass,$dbname);
	$db->connect();
	$db->select_db();
	//$siteid 				= 126;//local
	$siteid 				= 105;//local
	
	$filename ='csv/orders/order_list.csv';
	$det_filename ='csv/orders/order_list_details.csv';
	$perpage = 1000;
	$totcnt = 0;
	
	$writedet_header = false;
	$start = $_REQUEST['st'];
	
	if($start==0) {
		$fp = fopen ($filename,'w');
		$writedet_header = true;
	} else {
		$fp = fopen ($filename,'a+');
	}
	
	$header = array(
					'order_id',
					'order_date',
					'order_custtitle',
					'order_custfname',
					'order_custmname',
					'order_custsurname',
					'order_custcompany',
					'order_buildingnumber',
					'order_street',
					'order_city',
					'order_state',
					'order_country',
					'order_custpostcode',
					'order_custphone',
					'order_custfax',
					'order_custmobile',
					'order_custemail',
					
					'delivery_title',
					'delivery_fname',
					'delivery_mname',
					'delivery_lname',
					'delivery_companyname',
					'delivery_buildingnumber',
					'delivery_street',
					'delivery_city',
					'delivery_state',
					'delivery_country',
					'delivery_zip',
					'delivery_phone',
					'delivery_fax',
					'delivery_mobile',
					'delivery_email',
					
					'order_deliverytype',
					'order_deliverylocation',
					'order_paymenttype',
					'order_paymentmethod',
					'order_paystatus',
					'order_status',
					'order_refundamt',
					'order_deposit_amt',
					'order_totalprice'
					);
	

	if($start==0)	{
		fwrite($fp,implode(',',$header)."\n");
	}
	
	// get the list of orders using the given limit
	
	$sql_ord = "SELECT * 
					FROM 
						orders 
					WHERE 
						sites_site_id = $siteid 
						AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
					ORDER BY 
						order_id 
					LIMIT 
						$start , $perpage";
						//AND order_status NOT IN ('CANCELLED','NOT_AUTH')
	$ret_ord = $db->query($sql_ord);
	if ($db->num_rows($ret_ord)) {
		while ($row_ord = $db->fetch_array($ret_ord)) {
				$totcnt++;
				$insert_arr = array();
				
				$insert_arr[] = add_quotes($row_ord['order_id']);
				$insert_arr[] = add_quotes($row_ord['order_date']);
				$insert_arr[] = add_quotes($row_ord['order_custtitle']);
				$insert_arr[] = add_quotes($row_ord['order_custfname']);
				$insert_arr[] = add_quotes($row_ord['order_custmname']);
				$insert_arr[] = add_quotes($row_ord['order_custsurname']);
				$insert_arr[] = add_quotes($row_ord['order_custcompany']);
				$insert_arr[] = add_quotes($row_ord['order_buildingnumber']);
				$insert_arr[] = add_quotes($row_ord['order_street']);
				$insert_arr[] = add_quotes($row_ord['order_city']);
				$insert_arr[] = add_quotes($row_ord['order_state']);
				$insert_arr[] = add_quotes($row_ord['order_country']);
				$insert_arr[] = add_quotes($row_ord['order_custpostcode']);
				$insert_arr[] = add_quotes($row_ord['order_custphone']);
				$insert_arr[] = add_quotes($row_ord['order_custfax']);
				$insert_arr[] = add_quotes($row_ord['order_custmobile']);
				$insert_arr[] = add_quotes($row_ord['order_custemail']);
				
			$sql_del = "SELECT * FROM order_delivery_data WHERE orders_order_id = ".$row_ord['order_id']." LIMIT 1";
			$ret_del = $db->query($sql_del);
			if ($db->num_rows($ret_del)) {
				$row_del = $db->fetch_array($ret_del);
				$insert_arr[] = add_quotes($row_del['delivery_title']);
				$insert_arr[] = add_quotes($row_del['delivery_fname']);
				$insert_arr[] = add_quotes($row_del['delivery_mname']);
				$insert_arr[] = add_quotes($row_del['delivery_lname']);
				$insert_arr[] = add_quotes($row_del['delivery_companyname']);
				$insert_arr[] = add_quotes($row_del['delivery_buildingnumber']);
				$insert_arr[] = add_quotes($row_del['delivery_street']);
				$insert_arr[] = add_quotes($row_del['delivery_city']);
				$insert_arr[] = add_quotes($row_del['delivery_state']);
				$insert_arr[] = add_quotes($row_del['delivery_country']);
				$insert_arr[] = add_quotes($row_del['delivery_zip']);
				$insert_arr[] = add_quotes($row_del['delivery_phone']);
				$insert_arr[] = add_quotes($row_del['delivery_fax']);
				$insert_arr[] = add_quotes($row_del['delivery_mobile']);
				$insert_arr[] = add_quotes($row_del['delivery_email']);
				
			}
			
			$insert_arr[] = add_quotes($row_ord['order_deliverytype']);
			$insert_arr[] = add_quotes($row_ord['order_deliverylocation']);
			$insert_arr[] = add_quotes(getpaymenttype_Name1($row_ord['order_paymenttype']));
			$insert_arr[] = add_quotes(getpaymentmethod_Name1($row_ord['order_paymentmethod']));
			$insert_arr[] = add_quotes(getpaymentstatus_Name1($row_ord['order_paystatus']));
			$insert_arr[] = add_quotes(getorderstatus_Name1($row_ord['order_status']));
			$insert_arr[] = add_quotes($row_ord['order_refundamt']);
			$insert_arr[] = add_quotes($row_ord['order_deposit_amt']);
			$insert_arr[] = add_quotes($row_ord['order_totalprice']);
			
			fwrite($fp,implode(',',$insert_arr)."\n");
			getOrderdetails($row_ord['order_id']);
			$writedet_header = false;
			//echo $row_ord['order_paymenttype']." -- ".add_quotes(getpaymenttype_Name($row_ord['order_paymenttype']))."<br>";
		}
	}
	
	
	
	
	fclose($fp);
	echo "Done ".$totcnt." -- ";
	if ($totcnt==$perpage){
		echo '<a href="generate_order_list.php?st='.($start + $perpage).'">'.($start + $perpage).'</a>';
	}
	
	
	
	function getOrderdetails($orderid) {
		global $db, $siteid, $det_filename,$writedet_header;
		
		$filename =$det_filename;
			
		$header_arr = array();
		$header_arr[] = 'Order Id';
		$header_arr[] = 'Det Id';
		$header_arr[] = 'Product Id';
		$header_arr[] = 'Product Name';
		$header_arr[] = 'Order Qty';
		$header_arr[] = 'Despatched Qty';
		$header_arr[] = 'Backorder Qty';
		$header_arr[] = 'Cancelled Qty';
		$header_arr[] = 'Returned Qty';
		$header_arr[] = 'Sale price';
		//$header_arr[] = 'Discount';
		//$header_arr[] = 'Discount type';
		$header_arr[] = 'Row Total';
		$header_arr[] = 'Variable Details';
		
		if($writedet_header) {
			$fp = fopen ($filename,'w');
			fwrite($fp,implode(',',$header_arr)."\n");
		} else {
			$fp = fopen ($filename,'a+');
		}
		
		$sql_det = "SELECT * FROM 
						order_details 
					WHERE 
						orders_order_id = $orderid";
		$ret_det = $db->query($sql_det);
		if($db->num_rows($ret_det)) {
			while ($row_det = $db->fetch_array($ret_det)) {
				$insert_arr = array();
				$insert_arr[] = add_quotes($row_det['orders_order_id']);
				$insert_arr[] = add_quotes($row_det['orderdet_id']);
				$insert_arr[] = add_quotes($row_det['products_product_id']);
				$insert_arr[] = add_quotes($row_det['product_name']);
				$insert_arr[] = add_quotes($row_det['order_orgqty']);
				
				$sql_des = "SELECT sum(despatched_qty) as cnt FROM order_details_despatched WHERE orderdet_id=".$row_det['orderdet_id'];
				$ret_des = $db->query($sql_des);
				$row_des = $db->fetch_array($ret_des);
				
				$sql_back = "SELECT sum(backorder_qty) as cnt FROM order_details_backorder WHERE orderdet_id=".$row_det['orderdet_id'];
				$ret_back = $db->query($sql_back);
				$row_back = $db->fetch_array($ret_back);
				
				$sql_can = "SELECT sum(cancelled_qty) as cnt FROM order_details_cancelled WHERE orderdet_id=".$row_det['orderdet_id'];
				$ret_can = $db->query($sql_can);
				$row_can = $db->fetch_array($ret_can);
				
				
				
				$sql_ret = "SELECT sum(return_qty) as cnt FROM order_details_return WHERE orderdet_id=".$row_det['orderdet_id'];
				$ret_ret = $db->query($sql_ret);
				$row_ret = $db->fetch_array($ret_ret);
				
				$insert_arr[] = add_quotes(($row_des['cnt']=='')?0:$row_des['cnt']);//'Despatched Qty';
				$insert_arr[] = add_quotes(($row_back['cnt']=='')?0:$row_back['cnt']);//'Backorder Qty';
				$insert_arr[] = add_quotes(($row_can['cnt']=='')?0:$row_can['cnt']);//'Cancelled Qty';
				
				$insert_arr[] = add_quotes(($row_ret['cnt']=='')?0:$row_ret['cnt']);//'Returned Qty';
				
				$insert_arr[] = add_quotes($row_det['product_soldprice']);
				//$insert_arr[] = $row_det['order_discount'];
			//	$insert_arr[] = $row_det['order_discount_type'];
				$insert_arr[] = add_quotes($row_det['order_rowtotal']);
				
				$sql_var = "SELECT * FROM order_details_variables WHERE order_details_orderdet_id=".$row_det['orderdet_id'];
				$ret_var = $db->query($sql_var);
				$var_str = '';
				if ($db->num_rows($ret_var)) {
					while ($row_var = $db->fetch_array($ret_var)) {
						if($var_str!='') {
							$var_str .= ', ';
						}
							
						$var_str .= $row_var['var_name'].':'.$row_var['var_value'];
					}
				}
				$var_str = add_quotes($var_str);
				$insert_arr[] = $var_str; // variables
				fwrite($fp,implode(',',$insert_arr)."\n");
				
			}
		}
		fclose($fp);

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function add_quotes($d)
	{
		return $d = '"' . str_replace('"', '""', stripslashes($d)) . '"';

	}
	function getpaymenttype_Name1($key)
{
	global $db,$siteid;
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
function getpaymentmethod_Name1($key)
{
	global $db,$siteid;
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
function getpaymentstatus_Name1($key)
{
	global $db,$siteid;
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
			$caption = 'Check '.getpaymentmethod_Name1($key);
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
function getorderstatus_Name1($key,$clean_output=false)
{
	global $db,$siteid;
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
			$caption = 'Incomplete Order';
		else
			$caption = 'Incomplete Order';
		break;

	};
	return $caption;
}	
	
?>
