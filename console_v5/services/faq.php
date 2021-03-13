<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/faq/list_faq.php");
}
elseif($_REQUEST['fpurpose']=='save_faq_order') // faq order 
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
		$update_array['faq_sortorder']	= $OrderArr[$i];
		$db->update_from_array($update_array,'faq',array('faq_id'=>$IdArr[$i]));
	}
	$alert = 'Order saved successfully.';
	include ('../includes/faq/list_faq.php');
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update faq status
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$type_ids_arr 		= explode('~',$_REQUEST['type_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($type_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['faq_hide']	= $new_status;
			$faq_id 						= $type_ids_arr[$i];	
			$db->update_from_array($update_array,'faq',array('faq_id'=>$faq_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/faq/list_faq.php');
}
else if($_REQUEST['fpurpose']=='delete') // Delete faq
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry FAQ not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					
					  $sql_del = "DELETE FROM faq WHERE sites_site_id = $ecom_siteid AND faq_id=".$del_arr[$i];
					  $db->query($sql_del);
				 }	  
			}
		}
		$alert = 'Delete operation successfull';
		include ('../includes/faq/list_faq.php');
}
else if($_REQUEST['fpurpose']=='add') // New faq
{
	//include_once("classes/fckeditor.php");
	include("includes/faq/add_faq.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit faq
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include_once("classes/fckeditor.php");
	include("includes/faq/edit_faq.php");
}
else if($_REQUEST['fpurpose']=='insert') // Save new faq
{
	if($_REQUEST['Submit'])
	{
		
		
		$alert='';
		$fieldRequired = array($_REQUEST['faq_question']);
		$fieldDescription = array('Question');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT faq_id FROM faq WHERE faq_question = '".trim(add_slash($_REQUEST['faq_question']))."' AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		if($db->num_rows($res_check))
			$alert = 'Question already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['faq_question']			= add_slash($_REQUEST['faq_question']);
			$insert_array['faq_answer']			= add_slash($_REQUEST['faq_answer'],false);
			$insert_array['faq_sortorder']		= (is_numeric($_REQUEST['faq_sortorder']))?$_REQUEST['faq_sortorder']:0;
			$insert_array['faq_hide']				= ($_REQUEST['faq_hide'])?1:0;
			$db->insert_from_array($insert_array, 'faq');
			$insert_id = $db->insert_id();
			
				
				
			$alert .= '<br><span class="redtext"><b>FAQ added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=faq&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=faq&fpurpose=edit&faq_id=<?=$insert_id?>&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=faq&fpurpose=add&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			//include_once("classes/fckeditor.php");
			include("includes/faq/add_faq.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['faq_question']);
		$fieldDescription = array('Question');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				
		$sql_check = "SELECT faq_id FROM faq WHERE faq_question = '".add_slash($_REQUEST['faq_question'])."' AND sites_site_id=$ecom_siteid AND faq_id != ".$_REQUEST['faq_id']." LIMIT 1 ";
		$res_check = $db->query($sql_check);
	
		if($db->num_rows($res_check))
			$alert = ' Question Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['faq_question']			= add_slash($_REQUEST['faq_question']);
			$update_array['faq_answer']				= add_slash($_REQUEST['faq_answer'],false);
			$update_array['sites_site_id']			= $ecom_siteid;
			$update_array['faq_sortorder']			= (is_numeric($_REQUEST['faq_sortorder']))?$_REQUEST['faq_sortorder']:0;
			$update_array['faq_hide']				= $_REQUEST['faq_hide'];
			$db->update_from_array($update_array, 'faq', 'faq_id', $_REQUEST['faq_id']);
			$alert .= '<br><span class="redtext"><b>FAQ Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=faq&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=faq&fpurpose=edit&faq_id=<?=$_REQUEST['faq_id']?>&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=faq&fpurpose=add&search_faq_question=<?=$_REQUEST['search_faq_question']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			//include_once("classes/fckeditor.php");
			include("includes/faq/edit_faq.php");
		}
	}
}
?>