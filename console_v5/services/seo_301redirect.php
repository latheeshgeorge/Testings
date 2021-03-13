<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/seo_301_redirect/list_301redirect.php");
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry URL not selected';
		}
		else
		{   
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				      $sql_del = "DELETE FROM seo_redirect WHERE redirect_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count++;				
				}	
			}
			if($del_count > 0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." URL(s) Deleted Successfully";
			}		  
		}
		include ('../includes/seo_301_redirect/list_301redirect.php');
	}
else if($_REQUEST['fpurpose'] == 'update')
{   
        $alert='';
		$fieldRequired = array($_REQUEST['old_url'],$_REQUEST['new_url']);
		$fieldDescription = array('Old URL','New URL');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);

 // for updating the Review
           $redirect_id = $_REQUEST['redirect_id'];
				//echo $_REQUEST['enquiry_statuss'].$_REQUEST['enquiry_idd'];
			
		$sql_check = "SELECT redirect_id FROM seo_redirect WHERE (redirect_old_url = '".trim(add_slash($_REQUEST['old_url']))."' OR redirect_new_url = '".trim(add_slash($_REQUEST['new_url']))."') AND redirect_id<>".$redirect_id." AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		if($db->num_rows($res_check))
			$alert = 'URLs already exists '; 
		if(!$alert) 
		{
			$update_array											= array();
			$update_array['sites_site_id'] 							= $ecom_siteid;
			$update_array['redirect_old_url'] 						= addslashes($_REQUEST['old_url']);
			$update_array['redirect_new_url'] 						= addslashes($_REQUEST['new_url']);
			$db->update_from_array($update_array, 'seo_redirect', array('redirect_id' =>$_REQUEST['redirect_id'], 'sites_site_id' => $ecom_siteid));
			$alert = '<br><span class="redtext"><b>Details saved successfully.</b></span></br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=seo_301redirect&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=seo_301redirect&fpurpose=edit&checkbox[0]=<?=$redirect_id?>&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=seo_301redirect&fpurpose=add&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}
		else
		{
		    $alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/seo_301_redirect/edit_301redirect.php");
		}		
}
else if($_REQUEST['fpurpose']=='add') // New autolinker
{
	include("includes/seo_301_redirect/add_301redirect.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit autolinker
{
	include("includes/seo_301_redirect/edit_301redirect.php");
}
else if($_REQUEST['fpurpose']=='insert') // Save new autolinker
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['old_url'],$_REQUEST['new_url']);
		$fieldDescription = array('Old URL','New URL');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT redirect_id FROM seo_redirect WHERE (redirect_old_url = '".trim(add_slash($_REQUEST['old_url']))."' OR redirect_new_url = '".trim(add_slash($_REQUEST['new_url']))."') AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		if($db->num_rows($res_check))
			$alert = 'URLs already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['sites_site_id'] 							= $ecom_siteid;
			$insert_array['redirect_old_url'] 						= addslashes($_REQUEST['old_url']);
			$insert_array['redirect_new_url'] 						= addslashes($_REQUEST['new_url']);
			$review_date_arr										= explode('-',$_REQUEST['last_access_date']);							
			$insert_array['redirect_last_access_date']				= 'now()';

			$db->insert_from_array($insert_array, 'seo_redirect');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>URL Details added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=seo_301redirect&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=seo_301redirect&fpurpose=edit&checkbox[0]=<?=$insert_id?>&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=seo_301redirect&fpurpose=add&redirect_old_url=<?=$_REQUEST['redirect_old_url']?>&redirect_new_url=<?=$_REQUEST['redirect_new_url']?>&srch_access_startdate=<?=$_REQUEST['srch_access_startdate']?>&srch_access_enddate=<?=$_REQUEST['srch_access_enddate']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/seo_301_redirect/add_301redirect.php");
		}
	}
}

?>
