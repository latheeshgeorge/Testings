<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/listof_quotes/list_quote.php");
}
if($_REQUEST['fpurpose']=='change_status')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$enquire_ids_arr 		= explode('~',$_REQUEST['enquire_ids']);
		$new_status		= $_REQUEST['ch_status'];
	    for($i=0;$i<count($enquire_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['status']	= $new_status;
			$update_array['status_changed_by'] 	= $_SESSION['console_id'];
		$update_array['status_changed_date'] 	= 'now()';

			
			$enquiry_id 					= $enquire_ids_arr[$i];	
			$db->update_from_array($update_array,'customer_productquotes_details',array('organisation_id'=>$enquiry_id ));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/listof_quotes/list_quote.php');
}
else if($_REQUEST['fpurpose']=='list')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/product_enquire/ajax/enquire_ajax_functions.php";
		include_once("classes/fckeditor.php");
		$review_id = $_REQUEST['checkbox'][0];
		if($review_id)
		{
			$sql_check = "SELECT enquiry_status FROM product_enquiries WHERE sites_site_id=$ecom_siteid AND enquiry_id=".$review_id;
			$ret_check = $db->query($sql_check);
			$row_check = $db->fetch_array($ret_check);
			if($row_check['enquiry_status']=='NEW')
			{
				$update_array['enquiry_status']	= 'PENDING';
				$db->update_from_array($update_array,'product_enquiries',array('enquiry_id'=>$review_id ));
			}
		}
	include("includes/product_enquire/list_proddetails.php");
	
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Quote not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM customer_productquotes_details WHERE organisation_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count++;				
				}	
			}
			if($del_count > 0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." Quote(s) Deleted Successfully";
			}		  
		}
		include ('../includes/listof_quotes/list_quote.php');
	}
else if($_REQUEST['fpurpose'] == 'update_enquiry')
{    $ajax_return_function = 'ajax_return_contents';
	 include "ajax/ajax.php";
		include ('includes/product_enquire/ajax/enquire_ajax_functions.php');
 // for updating the Review
  $enquiry_id = $_REQUEST['enquiry_id'];
			if($_REQUEST['enquiry_statuss'])
				{
				//echo $_REQUEST['enquiry_statuss'].$_REQUEST['enquiry_idd'];
				$sel_enq_status = $_REQUEST['enquiry_statuss'];
								$update_array							= array();
								$update_array['sites_site_id'] 			= $ecom_siteid;
								$update_array['enquiry_status'] 			= $sel_enq_status;
								$db->update_from_array($update_array, 'product_enquiries', array('enquiry_id' =>$_REQUEST['enquiry_id'], 'sites_site_id' => $ecom_siteid));
				$alert = 'Status changed successfully.';
				}
			include ('includes/product_enquire/list_proddetails.php');			
}
?>
