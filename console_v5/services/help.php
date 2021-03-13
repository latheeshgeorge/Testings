<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/help/list_help.php");
}
elseif($_REQUEST['fpurpose']=='save_help_order') // help order 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	//print_r($OrderArr);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['help_sortorder']	= $OrderArr[$i];
		$db->update_from_array($update_array,'help',array('help_id'=>$IdArr[$i]));
	}
	$alert = 'Order saved successfully.';
	include ('../includes/help/list_help.php');
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update help status
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$type_ids_arr 		= explode('~',$_REQUEST['type_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($type_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['help_hide']	= $new_status;
			$help_id 						= $type_ids_arr[$i];	
			$db->update_from_array($update_array,'help',array('help_id'=>$help_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/help/list_help.php');
}
else if($_REQUEST['fpurpose']=='delete') // Delete help
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry HELP not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					
					  $sql_del = "DELETE FROM help WHERE sites_site_id = $ecom_siteid AND help_id=".$del_arr[$i];
					  $db->query($sql_del);
				 }	  
			}
		}
		$alert = 'Delete operation successfull';
		include ('../includes/help/list_help.php');
}
else if($_REQUEST['fpurpose']=='add') // New help
{
	include_once("classes/fckeditor.php");
	include("includes/help/add_help.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit help
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include_once("classes/fckeditor.php");
	include("includes/help/edit_help.php");
}
else if($_REQUEST['fpurpose']=='insert') // Save new help
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['help_heading'],$_REQUEST['help_description']);
		$fieldDescription = array('Heading','Description');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT help_id FROM help WHERE help_heading = '".trim(add_slash($_REQUEST['help_heading']))."' AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		if($db->num_rows($res_check))
			$alert = 'Heading already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['help_heading']			= add_slash($_REQUEST['help_heading']);
			$insert_array['help_description']			= add_slash($_REQUEST['help_description'],false);
			$insert_array['help_sortorder']		= (is_numeric($_REQUEST['help_sortorder']))?$_REQUEST['help_sortorder']:0;
			$insert_array['help_hide']				= ($_REQUEST['help_hide'])?1:0;
			$db->insert_from_array($insert_array, 'help');
			$insert_id = $db->insert_id();
			
				
				
			$alert .= '<br><span class="redtext"><b>HELP added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=help&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=help&fpurpose=edit&help_id=<?=$insert_id?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=help&fpurpose=add&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include_once("classes/fckeditor.php");
			include("includes/help/add_help.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['help_heading'],$_REQUEST['help_description']);
		$fieldDescription = array('Heading','Description');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				
		$sql_check = "SELECT help_id FROM help WHERE help_heading = '".add_slash($_REQUEST['help_heading'])."' AND sites_site_id=$ecom_siteid AND help_id != ".$_REQUEST['help_id']." LIMIT 1 ";
		$res_check = $db->query($sql_check);
	
		if($db->num_rows($res_check))
			$alert = ' Heading Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['help_heading']			= add_slash($_REQUEST['help_heading']);
			$update_array['help_description']				= add_slash($_REQUEST['help_description'],false);
			$update_array['sites_site_id']			= $ecom_siteid;
			$update_array['help_sortorder']			= (is_numeric($_REQUEST['help_sortorder']))?$_REQUEST['help_sortorder']:0;
			$update_array['help_hide']				= $_REQUEST['help_hide'];
			$db->update_from_array($update_array, 'help', 'help_id', $_REQUEST['help_id']);
			$alert .= '<br><span class="redtext"><b>HELP Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=help&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=help&fpurpose=edit&help_id=<?=$_REQUEST['help_id']?>&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=help&fpurpose=add&search_help_heading=<?=$_REQUEST['search_help_heading']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			include_once("classes/fckeditor.php");
			include("includes/help/edit_help.php");
		}
	}
}
?>