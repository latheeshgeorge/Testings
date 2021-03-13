<?php
// Array with month names to be  used in various sections of payon account
$month_arr			= array(1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
if($_REQUEST['fpurpose']=='')
{
	include("includes/payonaccount/list_payonaccount_customers.php");
}
elseif($_REQUEST['fpurpose']=='account_summary')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/payonaccount/account_summary.php");
}
elseif($_REQUEST['fpurpose']=='make_payment')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$pay_amt = trim($_REQUEST['pay_amt']);
	// Making an entry to the table for the amount being paid with current date
	// Get the remaining amount for current customer
	$sql_cust = "SELECT customer_payonaccount_usedlimit 
								FROM 
									customers 
								WHERE 
									customer_id = ".$_REQUEST['customer_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
	$ret_cust= $db->query($sql_cust);
	$alert = '';
	if($db->num_rows($ret_cust))
	{
		$row_cust = $db->fetch_array($ret_cust);
		if($row_cust['customer_payonaccount_usedlimit']<$pay_amt)
			$alert = 'Sorry!! you can make the payment for only '.display_curr_symbol().$row_cust['customer_payonaccount_usedlimit'];
	}
	if($alert=='')
	{
		$insert_array 																= array();
		$insert_array['pay_date']												= 'now()';
		$insert_array['orders_order_id']										= 0;
		$insert_array['sites_site_id']											= $ecom_siteid;
		$insert_array['customers_customer_id']							= $_REQUEST['customer_id'];
		$insert_array['pay_amount']											= $pay_amt;
		$insert_array['pay_transaction_type']								= 'C';
		$insert_array['pay_details']											= 'Payment - Thank You';
		$insert_array['pay_paystatus']										= 'Paid';
		$insert_array['pay_paystatus_changed_by']						= $_SESSION['console_id'];
		$insert_array['pay_additional_details']								= add_slash($_REQUEST['pay_additional_details']);
		$insert_array['pay_paystatus_changed_paytype']				= add_slash($_REQUEST['cbo_paymethod']);
		$db->insert_from_array($insert_array,'order_payonaccount_details');
		$alert = 'Payment Recorded Successfully';
		// Decrement the paid amount from the customers used limit field in customers table
		$update_sql = "UPDATE 
									customers 
								SET 
									customer_payonaccount_usedlimit = customer_payonaccount_usedlimit - $pay_amt 
								WHERE 
									customer_id = ".$_REQUEST['customer_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
		$db->query($update_sql);
		
	}
	include("../includes/payonaccount/account_summary.php");
}
elseif ($_REQUEST['fpurpose']=='view_bills')
{
	include("includes/payonaccount/list_payonaccount_bills.php");
}
elseif ($_REQUEST['fpurpose']=='bill_details')
{
	include("includes/payonaccount/account_viewbills.php");
}
?>