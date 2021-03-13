<?php

	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/order_tax_report/list_order_tax_report.php');
	}
	elseif($_REQUEST['fpurpose']=='ord_taxdetails') //  View details page
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$_REQUEST['checkbox'][0] = $_REQUEST['edit_id'];
		include ('includes/order_tax_report/ajax/order_tax_ajax_functions.php');
		include ('includes/order_tax_report/order_tax_details.php');
	}
	
?>
