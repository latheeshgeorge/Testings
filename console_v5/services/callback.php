<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/callback/list_callback.php");
}
elseif($_REQUEST['fpurpose']=='change_status')
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
		if ($_REQUEST['del_ids'] == '')
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
					  
						
				}	
			}
			if($alert) $alert .="<br />";
			$alert .= "Callback(s) Deleted Successfully";
		}
		include ('../includes/callback/list_callback.php');
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
	
	include("includes/callback/callback_details.php");
	
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		if(!$alert) {
			$update_array = array();
			$update_array['callback_status']				= add_slash($_REQUEST['callback_status']);
			$update_array['sites_site_id']					= $ecom_siteid;
			$db->update_from_array($update_array, 'callback', 'callback_id', $_REQUEST['callback_id']);
			#callback groups mapping section
			$alert .= '<b>CallBack Status Changed successfully</b>';
			include("includes/callback/callback_details.php");?>
					<?	
		}
	}
}
?>