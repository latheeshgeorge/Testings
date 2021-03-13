<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/product_enquire/list_prodenquire.php");
}
if($_REQUEST['fpurpose']=='save_note')
{
  include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include "../includes/product_enquire/ajax/enquire_ajax_functions.php";

		$insert_array =array();
		$insert_array['note_addedon'] = 'now()';
		$insert_array['note']  = add_slash($_REQUEST['enq_note']);
		$insert_array['product_enquiries_enquiry_id'] = $_REQUEST['enquiry_id'];
		$insert_array['added_by'] = $_SESSION['console_id'];
		$db->insert_from_array($insert_array, 'product_enquiry_notes');
			$insert_id = $db->insert_id();
		$alert = 'Note Saved successfully.';
		function_displaynote_add($_REQUEST['enquiry_id'],$alert);
		//include ('../includes/product_enquire/list_proddetails.php');
}
if($_REQUEST['fpurpose']=='show_addnote')
{
 include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include "../includes/product_enquire/ajax/enquire_ajax_functions.php";
		function_displaynote_add($_REQUEST['enquiry_id'],$alert);
}
/*if($_REQUEST['fpurpose']=='view_note')
{
 include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include "../includes/product_enquire/ajax/enquire_ajax_functions.php";
		show_note($_REQUEST['enquiry_id'],'');
}*/
if($_REQUEST['fpurpose']=='delete_note')
{
        include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$del_id = $_REQUEST['note_id'];
		$enq_id = $_REQUEST['enquiry_id'];
		
				      $sql_del = "DELETE FROM product_enquiry_notes WHERE note_id=".$del_id ." AND product_enquiries_enquiry_id=".$enq_id."";
					  $db->query($sql_del);
					  if($alert) $alert .="<br />";
					  $alert .= "Note ID With -".$del_id." Deleted";
				  include "../ajax/ajax.php";
	    include "../includes/product_enquire/ajax/enquire_ajax_functions.php";
		function_displaynote_add($_REQUEST['enquiry_id'],$alert); 
}
if($_REQUEST['fpurpose']=='send_enquire_email')
{
$enq_id = $_REQUEST['enquiry_id'];
$title = $_REQUEST['mail_subject'];
$content = $_REQUEST['mail_content'];
$sql="SELECT enquiry_id,enquiry_fname,enquiry_middlename,enquiry_lastname,enquiry_email from product_enquiries WHERE sites_site_id=$ecom_siteid AND enquiry_id=".$enq_id." LIMIT 1";
		$res=$db->query($sql);
		$row=$db->fetch_array($res);
       $email = stripslashes($row['enquiry_email']);
							$headers 	= "From: $ecom_hostname <$ecom_email>\n";
							$headers 	.= "MIME-Version: 1.0\n";
							$headers 	.= "Content-type: text/html; charset=iso-8859-1\n";		
							mail($email,$title,$content,$headers);
							$alert = 'Mail Send successfully.';
//echo "<script>window.location='http://$ecom_hostname/console/home.php?request=product_enquire&fpurpose=list&enquiry_id=$enq_id '<script>";exit;
		$ajax_return_function = 'ajax_return_contents';
	    include "ajax/ajax.php";
		include ('includes/product_enquire/ajax/enquire_ajax_functions.php');
		include ('includes/product_enquire/list_proddetails.php');

				//include("includes/product_enquire/list_proddetails.php");
}
elseif($_REQUEST['fpurpose']=='change_status')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$enquire_ids_arr 		= explode('~',$_REQUEST['enquire_ids']);
		$new_status		= $_REQUEST['ch_status'];
	    for($i=0;$i<count($enquire_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['enquiry_status']	= $new_status;
			$enquiry_id 					= $enquire_ids_arr[$i];	
			$db->update_from_array($update_array,'product_enquiries',array('enquiry_id'=>$enquiry_id ));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/product_enquire/list_prodenquire.php');
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
			$alert = 'Sorry Enquiry not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM product_enquiries WHERE enquiry_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count++;				
				}	
			}
			if($del_count > 0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." Enquiry request(s) Deleted Successfully";
			}		  
		}
		include ('../includes/product_enquire/list_prodenquire.php');
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
else if($_REQUEST['fpurpose']=='list_customer_maininfo') // Listing products assigned to shelf
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_enquire/ajax/enquire_ajax_functions.php');
		show_customer_details($_REQUEST['enquiry_id'],$alert);
}
else if($_REQUEST['fpurpose']=='list_productdetails') // Listing products assigned to shelf
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_enquire/ajax/enquire_ajax_functions.php');
		show_product_details_list($_REQUEST['enquiry_id'],$alert);
}

?>
