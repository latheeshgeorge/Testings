<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/giftwrap_card/list_card.php");
}
elseif($_REQUEST['fpurpose']=='save_card_order') // Card order 
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['card_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'giftwrap_card',array('card_id'=>$IdArr[$i]));
		
	}
	
	$alert = 'Order saved successfully.';
	include ('../includes/giftwrap_card/list_card.php');
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$card_ids_arr 		= explode('~',$_REQUEST['card_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($card_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['card_active']	= $new_status;
			$card_id 					= $card_ids_arr[$i];	
			$db->update_from_array($update_array,'giftwrap_card',array('card_id'=>$card_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/giftwrap_card/list_card.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Card not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count =0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Removing the image mappings
					$sql_delimg = "DELETE FROM 
									images_giftwrap_card 
								WHERE 
									giftwrap_card_card_id=".$del_arr[$i];
					$db->query($sql_delimg);
						
					$sql_del = "DELETE FROM giftwrap_card WHERE card_id=".$del_arr[$i];
					$db->query($sql_del);
                    $del_count++;
				}	
			}
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
						$alert .= $del_count." Card(s) Deleted Successfully";
			
			}
		}
		include ('../includes/giftwrap_card/list_card.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/giftwrap_card/add_card.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
	include("includes/giftwrap_card/edit_card.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['card_name']);
		$fieldDescription = array('Card Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_card WHERE card_name = '".trim(add_slash($_REQUEST['card_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Card Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['card_name']=trim(add_slash($_REQUEST['card_name']));
			$insert_array['card_extraprice']=add_slash($_REQUEST['card_extraprice']);
			$insert_array['card_order']=add_slash($_REQUEST['card_order']);
			$insert_array['card_active']=$_REQUEST['card_active'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'giftwrap_card');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Card added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_cards&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_cards&fpurpose=edit&card_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_cards&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '</span>';
			include("includes/giftwrap_card/add_card.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['card_name']);
		$fieldDescription = array('Card Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM giftwrap_card WHERE card_name = '".trim(add_slash($_REQUEST['card_name']))."' AND sites_site_id=$ecom_siteid AND card_id<>".$_REQUEST['card_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Card Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['card_name']=trim(add_slash($_REQUEST['card_name']));
			$update_array['card_extraprice']=add_slash($_REQUEST['card_extraprice']);
			$update_array['card_order']=add_slash($_REQUEST['card_order']);
			$update_array['card_active']=$_REQUEST['card_active'];
			$update_array['sites_site_id']=$ecom_siteid;
			$db->update_from_array($update_array, 'giftwrap_card', 'card_id', $_REQUEST['card_id']);
			$alert .= '<br><span class="redtext"><b>Card Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=giftwrap_cards&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_cards&fpurpose=edit&card_id=<?=$_REQUEST['card_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=giftwrap_cards&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = ' Error! '.$alert;
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
			include("includes/giftwrap_card/edit_card.php");
		}
	}
} 
elseif($_REQUEST['fpurpose']=='list_card_maininfo') // show card image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
		show_card_maininfo($_REQUEST['card_id']);
	}

elseif($_REQUEST['fpurpose']=='list_card_img') // show card image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
		show_card_image_list($_REQUEST['card_id']);
	}
elseif($_REQUEST['fpurpose']=='add_card_img') // show image gallery to select the required images
	{
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}	
elseif($_REQUEST['fpurpose']=='unassign_cardimagedetails') // Unassign images from card
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_giftwrap_card WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		show_card_image_list($_REQUEST['card_id'],$alert);
}	
elseif($_REQUEST['fpurpose']=='save_cardimagedetails') //Save image details
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/giftwrap_card/ajax/giftwrap_card_ajax_functions.php');
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
				$sql_change = "UPDATE images_giftwrap_card SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Image details Saved Successfully';
		}	
		show_card_image_list($_REQUEST['card_id'],$alert);
}

?>