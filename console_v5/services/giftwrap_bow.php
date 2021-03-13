<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/giftwrap_bow/list_bow.php");
}
elseif($_REQUEST['fpurpose']=='save_bow_order') // Bow order 
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['bow_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'giftwrap_bows',array('bow_id'=>$IdArr[$i]));
		
	}
	
	$alert = 'Order saved successfully.';
	include ('../includes/giftwrap_bow/list_bow.php');
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$bow_ids_arr 		= explode('~',$_REQUEST['bow_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($bow_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['bow_active']	= $new_status;
			$bow_id 					= $bow_ids_arr[$i];	
			$db->update_from_array($update_array,'giftwrap_bows',array('bow_id'=>$bow_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/giftwrap_bow/list_bow.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Adverts Placed not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Removing the image mappings
					$sql_delimg = "DELETE FROM 
										images_giftwrap_bow 
									WHERE 
										giftwrap_bows_bow_id=".$del_arr[$i];
					$db->query($sql_delimg);
					$sql_del = "DELETE FROM giftwrap_bows WHERE bow_id=".$del_arr[$i];
					$db->query($sql_del);
					$del_count++;			
					
				}	
			}
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
					$alert .= $del_count." Bow(s) Deleted Successfully";
			}		
		}
		include ('../includes/giftwrap_bow/list_bow.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/giftwrap_bow/add_bow.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');

	include("includes/giftwrap_bow/edit_bow.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['bow_name']);
		$fieldDescription = array('Bow Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_bows WHERE bow_name = '".trim(add_slash($_REQUEST['bow_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Bow Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['bow_name']=trim(add_slash($_REQUEST['bow_name']));
			$insert_array['bow_extraprice']=add_slash($_REQUEST['bow_extraprice']);
			$insert_array['bow_order']=add_slash($_REQUEST['bow_order']);
			$insert_array['bow_active']=$_REQUEST['bow_active'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'giftwrap_bows');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Bow added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_bows&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_bows&fpurpose=edit&bow_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_bows&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = 'Error!!'.$alert;
			include("includes/giftwrap_bow/add_bow.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['bow_name']);
		$fieldDescription = array('Bow Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_bows WHERE bow_name = '".trim(add_slash($_REQUEST['bow_name']))."' AND sites_site_id=$ecom_siteid AND bow_id<>".$_REQUEST['bow_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Bow Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['bow_name']=trim(add_slash($_REQUEST['bow_name']));
			$update_array['bow_extraprice']=add_slash($_REQUEST['bow_extraprice']);
			$update_array['bow_order']=add_slash($_REQUEST['bow_order']);
			$update_array['bow_active']=$_REQUEST['bow_active'];
			$update_array['sites_site_id']=$ecom_siteid;
			$db->update_from_array($update_array, 'giftwrap_bows', 'bow_id', $_REQUEST['bow_id']);
			$alert .= '<br><span class="redtext"><b>Bow Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_bows&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_bows&fpurpose=edit&bow_id=<?=$_REQUEST['bow_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_bows&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
		
			$alert = 'Error! '.$alert;	
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');
			include("includes/giftwrap_bow/edit_bow.php");
		}
	}
} 
elseif($_REQUEST['fpurpose']=='list_bow_maininfo') // show bow image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');
		show_bow_maininfo($_REQUEST['bow_id']);
	}

elseif($_REQUEST['fpurpose']=='list_bow_img') // show bow image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');
		show_bow_image_list($_REQUEST['bow_id']);
	}
elseif($_REQUEST['fpurpose']=='add_bow_img') // show image gallery to select the required images
	{
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}	
elseif($_REQUEST['fpurpose']=='unassign_bowimagedetails') // Unassign images from bow
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_giftwrap_bow WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_bow_image_list($_REQUEST['bow_id'],$alert);
}	
elseif($_REQUEST['fpurpose']=='save_bowimagedetails') //Save image details
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_bow/ajax/giftwrap_bow_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_title	= explode("~",$_REQUEST['ch_title']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE images_giftwrap_bow SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Image details Saved Successfully';
		}	
		show_bow_image_list($_REQUEST['bow_id'],$alert);
}
?>