<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/callback/list_callback.php");
}
elseif($_REQUEST['fpurpose']=='list_state') // show state list
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/callback/ajax/callback_ajax_functions.php');
		
		show_display_state_list($_REQUEST['country_id'],$_REQUEST['state_id']);
}

elseif($fpurpose=='change_status')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$callback_ids_arr 		= explode('~',$_REQUEST['callback_ids']);
		$new_status		= $_REQUEST['ch_status'];
	
		for($i=0;$i<count($callback_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['callback_status']	= $new_status;
			$callback_id 					= $callback_ids_arr[$i];	
			$db->update_from_array($update_array,'callback',array('callback_id'=>$callback_id));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/callback/list_callback.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($del_ids == '')
		{
			$alert = 'Sorry callback not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM callback WHERE callback_id=".$del_arr[$i];
					  $db->query($sql_del);
					  if($alert) $alert .="<br />";
					  $alert .= "Callback request with ID -".$del_arr[$i]." Deleted";
						
				}	
			}
		}
		include ('../includes/callback/list_callback.php');
}
else if($_REQUEST['fpurpose']=='add')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/callback/add_callback.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	if($_REQUEST['checkbox'][0]){
	$sql_check ="SELECT callback_status FROM callback WHERE sites_site_id=$ecom_siteid AND callback_id=".$_REQUEST['checkbox'][0];
	$ret_check = $db->query($sql_check);
	$row_check = $db->fetch_array($ret_check);
		if($row_check['callback_status']=='NEW')
		{
				$update_array['callback_status']				= 'READ';
				$db->update_from_array($update_array, 'callback', array('callback_id'=>$_REQUEST['checkbox'][0],'sites_site_id'=>$ecom_siteid));
		}
	}
	
	include("includes/callback/edit_callback.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['callback_fname'],$_REQUEST['callback_email'],$_REQUEST['callback_status']);
		$fieldDescription = array('First Name','Email','Status');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM callback WHERE callback_email = '".trim(add_slash($_REQUEST['callback_email']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0)
			$alert = 'Email Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['callback_fname']					=	add_slash($_REQUEST['callback_fname']);
			$insert_array['callback_lname']					=	add_slash($_REQUEST['callback_lname']);
			$insert_array['callback_email']					=	trim(add_slash($_REQUEST['callback_email']));
			$insert_array['callback_phone']					=	add_slash($_REQUEST['callback_phone']);
			$insert_array['callback_country']				=	$_REQUEST['country_id'];
			$insert_array['callback_comments']				=	add_slash($_REQUEST['callback_comments']);
			$insert_array['callback_adddate']				=	'curdate()';
			$insert_array['callback_status']				=	add_slash($_REQUEST['callback_status']);
			$insert_array['sites_site_id']					=	$ecom_siteid;
			$db->insert_from_array($insert_array, 'callback');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>callback added successfully</b></span><br>';
			echo $alert;
			 ?>
			<br /><a class="smalllink" href="home.php?request=callback&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=callback&fpurpose=edit&callback_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=callback&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/callback/add_callback.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['callback_fname'],$_REQUEST['callback_email'],$_REQUEST['callback_status']);
		$fieldDescription = array('First Name','Email','Status');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM callback WHERE callback_email = '".trim(add_slash($_REQUEST['callback_email']))."' AND sites_site_id=$ecom_siteid AND callback_id<>".$_REQUEST['callback_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Email Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['callback_fname']					= add_slash($_REQUEST['callback_fname']);
			$update_array['callback_lname']					= add_slash($_REQUEST['callback_lname']);
			$update_array['callback_email']					= trim(add_slash($_REQUEST['callback_email']));
			$update_array['callback_phone']					= add_slash($_REQUEST['callback_phone']);
			$update_array['callback_country']				= $_REQUEST['country_id'];
			$update_array['callback_comments']				= add_slash($_REQUEST['callback_comments']);
			$update_array['callback_adddate']				='curdate()';
			$update_array['callback_status']				= add_slash($_REQUEST['callback_status']);
			$update_array['sites_site_id']					= $ecom_siteid;
			$db->update_from_array($update_array, 'callback', 'callback_id', $_REQUEST['callback_id']);
			#callback groups mapping section
			$alert .= '<br><span class="redtext"><b>CallBack Details Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=callback&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=callback&fpurpose=edit&callback_id=<?=$_REQUEST['callback_id']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=callback&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&search_email=<?=$_REQUEST['search_email']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center>Error! '.$alert;
			$alert .= '</center>';
		?>
			<br />
			<?php
			include("includes/callback/edit_callback.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='list_callback') // show callback list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/callback/ajax/cust_group_ajax_functions.php');
		show_display_callback_list($_REQUEST['custgroup_id']);
	}
elseif($_REQUEST['fpurpose']=='unassign_customerdetails') // Unassign customers from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer/ajax/cust_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Customer(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM customer_customers_map WHERE map_id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Customer(s) Unassigned Successfully';
		}	
		show_display_customer_list($_REQUEST['custgroup_id'],$alert);
}		
?>