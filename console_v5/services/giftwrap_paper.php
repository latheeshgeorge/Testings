<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/giftwrap_paper/list_paper.php");
}
elseif($_REQUEST['fpurpose']=='save_paper_order') // Paper order 
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['paper_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'giftwrap_paper',array('paper_id'=>$IdArr[$i]));
		
	}
	
	$alert = 'Order saved successfully.';
	include ('../includes/giftwrap_paper/list_paper.php');
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$paper_ids_arr 		= explode('~',$_REQUEST['paper_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($paper_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['paper_active']	= $new_status;
			$paper_id 					= $paper_ids_arr[$i];	
			$db->update_from_array($update_array,'giftwrap_paper',array('paper_id'=>$paper_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/giftwrap_paper/list_paper.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Paper not selected';
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
									images_giftwrap_paper 
								WHERE 
									giftwrap_paper_paper_id=".$del_arr[$i];
					$db->query($sql_delimg);
					$sql_del = "DELETE FROM giftwrap_paper WHERE paper_id=".$del_arr[$i];
					$db->query($sql_del);
					$del_count++;			
					
				}	
			}
				if($del_count>0)
				{
				if($alert) $alert .="<br />";
					$alert .= $del_count." Paper(s) Deleted Successfully";
				}	
		}
		include ('../includes/giftwrap_paper/list_paper.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/giftwrap_paper/add_paper.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
	include("includes/giftwrap_paper/edit_paper.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['paper_name']);
		$fieldDescription = array('Paper Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_paper WHERE paper_name = '".trim(add_slash($_REQUEST['paper_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Paper Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['paper_name']=trim(add_slash($_REQUEST['paper_name']));
			$insert_array['paper_extraprice']=add_slash($_REQUEST['paper_extraprice']);
			$insert_array['paper_order']=add_slash($_REQUEST['paper_order']);
			$insert_array['paper_active']=$_REQUEST['paper_active'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'giftwrap_paper');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Paper added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_papers&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_papers&fpurpose=edit&paper_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_papers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/giftwrap_paper/add_paper.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['paper_name']);
		$fieldDescription = array('Paper Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_paper WHERE paper_name = '".trim(add_slash($_REQUEST['paper_name']))."' AND sites_site_id=$ecom_siteid AND paper_id<>".$_REQUEST['paper_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Paper Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['paper_name']=trim(add_slash($_REQUEST['paper_name']));
			$update_array['paper_extraprice']=add_slash($_REQUEST['paper_extraprice']);
			$update_array['paper_order']=add_slash($_REQUEST['paper_order']);
			$update_array['paper_active']=$_REQUEST['paper_active'];
			$update_array['sites_site_id']=$ecom_siteid;
			$db->update_from_array($update_array, 'giftwrap_paper', 'paper_id', $_REQUEST['paper_id']);
			$alert .= '<br><span class="redtext"><b>Paper Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_papers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_papers&fpurpose=edit&paper_id=<?=$_REQUEST['paper_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_papers&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = 'Error! '.$alert;
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
			include("includes/giftwrap_paper/edit_paper.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='list_paper_maininfo') // show card image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
		show_paper_maininfo($_REQUEST['paper_id']);
	}
elseif($_REQUEST['fpurpose']=='list_paper_img') // show paper image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
		show_paper_image_list($_REQUEST['paper_id']);
	}
elseif($_REQUEST['fpurpose']=='add_paper_img') // show image gallery to select the required images
	{
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}	
elseif($_REQUEST['fpurpose']=='unassign_paperimagedetails') // Unassign images from paper
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_giftwrap_paper WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_paper_image_list($_REQUEST['paper_id'],$alert);
}	
elseif($_REQUEST['fpurpose']=='save_paperimagedetails') //Save image details
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_paper/ajax/giftwrap_paper_ajax_functions.php');
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
				$sql_change = "UPDATE images_giftwrap_paper SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Image details Saved Successfully';
		}	
		show_paper_image_list($_REQUEST['paper_id'],$alert);
}
?>