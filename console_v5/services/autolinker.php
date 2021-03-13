<?php
$sql_site = "SELECT in_web_clinic FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
$ret_site = $db->query($sql_site);
$row_site = $db->fetch_array($ret_site);
// Check whether webclinic is active for current website
if($row_site['in_web_clinic']==1)
	$inWebclinic = 1;
else
	$inWebclinic = 0;

if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/autolinker/list_autolinker.php");
}

else if($_REQUEST['fpurpose']=='delete') // Delete faq
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Autolinker not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					
					  $sql_del = "DELETE FROM seo_autolinker WHERE sites_site_id = $ecom_siteid AND autolinker_id=".$del_arr[$i];
					  $db->query($sql_del);
				 }	  
			}
		}
		$alert = 'Details Deleted Successfull';
		include ('../includes/autolinker/list_autolinker.php');
}
else if($_REQUEST['fpurpose']=='add') // New autolinker
{
	include("includes/autolinker/add_autolinker.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit autolinker
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/autolinker/edit_autolinker.php");
}
else if($_REQUEST['fpurpose']=='insert') // Save new autolinker
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['autolinker_keyword'],$_REQUEST['autolinker_url']);
		$fieldDescription = array('Keyword','URL');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT autolinker_id FROM seo_autolinker WHERE autolinker_keyword = '".trim(add_slash($_REQUEST['autolinker_keyword']))."' AND autolinker_url = '".trim(add_slash($_REQUEST['autolinker_url']))."' AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		if($db->num_rows($res_check))
			$alert = 'Autolinker Details already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['sites_site_id']				= $ecom_siteid;
			$insert_array['autolinker_keyword']			= add_slash($_REQUEST['autolinker_keyword']);
			$insert_array['autolinker_url']				= add_slash($_REQUEST['autolinker_url']);
			$insert_array['autolinker_no_of_times']		= add_slash($_REQUEST['autolinker_no_of_times']);
			$insert_array['autolinker_allow_no_follow']	= ($_REQUEST['autolinker_allow_no_follow'])?1:0;
			$insert_array['autolinker_css_class']		= add_slash($_REQUEST['autolinker_css_class']);
			$db->insert_from_array($insert_array, 'seo_autolinker');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Autolinker Details added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=autolinker&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=autolinker&fpurpose=edit&autolinker_id=<?=$insert_id?>&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=autolinker&fpurpose=add&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/autolinker/add_autolinker.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['autolinker_keyword'],$_REQUEST['autolinker_url']);
		$fieldDescription = array('Keyword','URL');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				
		$sql_check = "SELECT autolinker_id FROM seo_autolinker WHERE autolinker_keyword = '".add_slash($_REQUEST['autolinker_keyword'])."' 
												AND autolinker_url = '".add_slash($_REQUEST['autolinker_url'])."' 
						AND sites_site_id=$ecom_siteid AND autolinker_id != ".$_REQUEST['autolinker_id']." LIMIT 1 ";
		$res_check = $db->query($sql_check);
	
		if($db->num_rows($res_check))
			$alert = ' Autolinker Details Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['autolinker_keyword']			= add_slash($_REQUEST['autolinker_keyword']);
			$update_array['autolinker_url']				= add_slash($_REQUEST['autolinker_url']);
			$update_array['autolinker_no_of_times']		= add_slash($_REQUEST['autolinker_no_of_times']);
			$update_array['autolinker_allow_no_follow']	= ($_REQUEST['autolinker_allow_no_follow'])?1:0;
			$update_array['autolinker_css_class']		= add_slash($_REQUEST['autolinker_css_class']);
			$db->update_from_array($update_array, 'seo_autolinker', 'autolinker_id', $_REQUEST['autolinker_id']);
			$alert .= '<br><span class="redtext"><b>Autolinker Details Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=autolinker&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=autolinker&fpurpose=edit&autolinker_id=<?=$_REQUEST['autolinker_id']?>&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=autolinker&fpurpose=add&search_autolinker_keyword=<?=$_REQUEST['search_autolinker_keyword']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			//include_once("classes/fckeditor.php");
			include("includes/autolinker/edit_autolinker.php");
		}
	}
}
?>