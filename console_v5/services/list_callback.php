<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/listof_callback/list_callback.php");
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
			$db->update_from_array($update_array,'customer_productcallback_details',array('prodcallback_id'=>$enquiry_id ));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/listof_callback/list_callback.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Callback not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM customer_productcallback_details WHERE prodcallback_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count++;				
				}	
			}
			if($del_count > 0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." Callback(s) Deleted Successfully";
			}		  
		}
		include ('../includes/listof_callback/list_callback.php');
	}
?>
