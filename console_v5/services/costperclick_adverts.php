<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/cpc_adverts/list_cpcadverts.php");
}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/cpc_adverts/add_cpcadverts.php");
}
else if($_REQUEST['fpurpose']=='edit')
{

	include("includes/cpc_adverts/edit_cpcadverts.php");
	
}

else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['advertplace_name']);
		$fieldDescription = array('Adverts Placed');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM costperclick_advertplacedon WHERE advertplace_name = '".trim(add_slash($_REQUEST['advertplace_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Adverts Placed Already exists  '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['advertplace_name']=trim(add_slash($_REQUEST['advertplace_name']));
			$insert_array['sites_site_id']=$ecom_siteid;
			
			$db->insert_from_array($insert_array, 'costperclick_advertplacedon');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Adverts Placed added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=costperclick_adverts&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_adverts&fpurpose=edit&advertplace_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_adverts&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = 'Error!!'.$alert;
			include("includes/cpc_adverts/add_cpcadverts.php");
		}
	}
}

else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['advertplace_name']);
		$fieldDescription = array('Adverts Placed');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM costperclick_advertplacedon WHERE advertplace_name = '".trim(add_slash($_REQUEST['advertplace_name']))."' AND sites_site_id=$ecom_siteid AND advertplace_id<>".$_REQUEST['advertplace_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Adverts Placed Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['advertplace_name']=trim(add_slash($_REQUEST['advertplace_name']));
			$update_array['sites_site_id']=$ecom_siteid;
			
			$db->update_from_array($update_array, 'costperclick_advertplacedon', 'advertplace_id', $_REQUEST['advertplace_id']);
			$alert .= '<br><span class="redtext"><b>Keyword Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=costperclick_adverts&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_adverts&fpurpose=edit&advertplace_id=<?=$_REQUEST['advertplace_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_adverts&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
		
			$alert = 'Error! '.$alert;	
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/giftwrap_bow/edit_bow.php");
		}
	}
} 
	
else if($_REQUEST['fpurpose']=='delete')
{		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		
		$alertcnt = 0;
		$notalertcnt = 0;
		
		if ($_REQUEST['del_ids']== '')
		{
			$alert = 'Sorry Adverts Placed location  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Removing the image mappings
					
					$del_chk_sql = "SELECT url_id FROM  costperclick_adverturl
											WHERE costperclick_adverplaced_on_advertplace_id='".$del_arr[$i]."'
												AND sites_site_id=".$ecom_siteid;
					$del_chk_res = $db->query($del_chk_sql);
					$del_chk_num = $db->num_rows($del_chk_res);
					if($del_chk_num == 0) {							
					
						$sql_del = "DELETE FROM costperclick_advertplacedon WHERE advertplace_id=".$del_arr[$i];
						$db->query($sql_del);
						$alertcnt += 1;
					} else {
						
						$notalertcnt += 1;
					}
				}	
			}
			if($alertcnt!=0)
			{
				if($alert) $alert .="<br />";
					$alert .= $alertcnt ." Adverts Placed location Deleted";
			}
			if($notalertcnt !=0) 
			{
				if($alert) $alert .="<br />";
					$alert .= $notalertcnt ." Adverts Placed location not Deleted, Because these are Linked with Cost Per Click Details";
			} 		
		}
		include("../includes/cpc_adverts/list_cpcadverts.php");
}	
?>