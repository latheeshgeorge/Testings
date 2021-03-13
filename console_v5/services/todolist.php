<?php
if($_REQUEST['fpurpose']=='')
{ 
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/todolist/list_todo.php");
}
elseif($_REQUEST['fpurpose']=='change_status')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$event_ids_arr 		= explode('~',$_REQUEST['event_ids']);
		 $new_status		= $_REQUEST['ch_status'];
	    for($i=0;$i<count($event_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['event_suspend']	= $new_status;
			$event_id 					= $event_ids_arr[$i];	
			$db->update_from_array($update_array,'events_calendar',array('event_id'=>$event_id ,'sites_site_id'=>$ecom_siteid));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/todolist/list_todo.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Event not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM events_calendar WHERE event_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count++;				
				}	
			}
			if($del_count > 0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." Event(s) Deleted Successfully";
			}		  
		}
		include ('../includes/todolist/list_todo.php');
	}
else if($_REQUEST['fpurpose'] == 'list')
{   
		include ('includes/todolist/edit_todo.php');
}
else if($_REQUEST['fpurpose'] == 'add')
{   
		include ('includes/todolist/add_todo.php');
}
else if($_REQUEST['fpurpose'] == 'update')
{   
 // for updating the Review
  $event_id = $_REQUEST['event_id'];
  $date 		= $_REQUEST['event_date'];
  $hr 			= $_REQUEST['hr']; 
  $min          = $_REQUEST['min'];
  if($date!='')
		{
			$date_arr 	= explode(' ',$date);
			$date_array = explode('-',$date_arr[0]) ;
			$hr_array	= explode(':',$date_arr[1]); 		
			$date_format = $date_array[2].'-'.$date_array[1].'-'.$date_array[0];
		    $date     = $date_format." ".$hr.":".$min.":00";

		}
			
								$update_array							= array();
								$update_array['sites_site_id'] 			= $ecom_siteid;
								$update_array['event_title'] 			= $_REQUEST['event_title'];
								$update_array['event_description'] 		= $_REQUEST['event_description'];
								$update_array['event_date'] 			= $date;
								$update_array['event_order'] 			= $_REQUEST['event_order'];

								$update_array['event_suspend'] 			= $_REQUEST['event_suspend'];

								$db->update_from_array($update_array, 'events_calendar', array('event_id' =>$_REQUEST['event_id'], 'sites_site_id' => $ecom_siteid));
				$alert = '<span class="redtext"><b>Updated Successfully.</b></span><br>';
				echo $alert;
				?>
				<br /><a class="smalllink" href="home.php?request=todo&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Events Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=todo&fpurpose=list&event_id=<?=$event_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Edit Events  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=todo&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Add New Event Page</a>
<?php
}
else if($_REQUEST['fpurpose'] == 'insert')
{   
 // for updating the Review
  $date 		= $_REQUEST['event_date'];
  $hr 			= $_REQUEST['hr']; 
  $min          = $_REQUEST['min'];
  if($date!='')
		{
			$date_arr 	= explode(' ',$date);
			$date_array = explode('-',$date_arr[0]) ;
			$hr_array	= explode(':',$date_arr[1]); 		
			$date_format = $date_array[2].'-'.$date_array[1].'-'.$date_array[0];
			$date     = $date_format." ".$hr.":".$min.":00";

		}
			
								$insert_array							= array();
								$insert_array['sites_site_id'] 			= $ecom_siteid;
								$insert_array['event_title'] 			= $_REQUEST['event_title'];
								$insert_array['event_description'] 		= $_REQUEST['event_description'];
								$insert_array['event_date'] 			= $date;
								$insert_array['event_order'] 			= $_REQUEST['event_order'];

								$insert_array['event_suspend'] 			= $_REQUEST['event_suspend'];

								$db->insert_from_array($insert_array, 'events_calendar');
								$insert_id = $db->insert_id();
				$alert = '<span class="redtext"><b>Inserted Successfully.</b></span><br>';
				echo $alert;
				?>
				<br /><a class="smalllink" href="home.php?request=todo&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Events Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=todo&fpurpose=list&event_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Edit Events  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=todo&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Add New Event Page</a>
<?php
}
?>
