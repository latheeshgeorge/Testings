<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";

	include("includes/cp_urls/list_cp_urls.php");
}
elseif($_REQUEST['fpurpose']=='list_monthly')
{
	include("includes/cp_urls/list_monthly.php");
}
elseif($_REQUEST['fpurpose']=='list_clicks')
{
	include("includes/cp_urls/list_clicks.php");
}
elseif($_REQUEST['fpurpose']=='list_export')
{
	include("includes/cp_urls/list_export.php");
}

else if($_REQUEST['fpurpose']=='add')
{
	include("includes/cp_urls/add_cp_urls.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ftype = "Edit";
	include("includes/cp_urls/add_cp_urls.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$group_ids_arr 		= explode('~',$_REQUEST['group_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($group_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['url_hidden']	= $new_status;
			$url_id 						= $group_ids_arr[$i];	
			
			$db->update_from_array($update_array,'costperclick_adverturl',array('url_id'=>$url_id));
			// Delete cache
			
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/cp_urls/list_cp_urls.php');
		
}

else if($_REQUEST['fpurpose']=='insert')
{
		if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['cbo_keyword'], $_REQUEST['cbo_advertlocation'], $_REQUEST['url_mypage']);
		$fieldDescription = array('Keyword', 'Adverts Location', 'Url My Page');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if($_REQUEST['cbo_keyword']=='-other-' && $_REQUEST['keyword_other']=="") {
			$alert = " Please Enter Other Keyword ";
		}
		else if($_REQUEST['cbo_advertlocation']=='-other-' && $_REQUEST['advert_other']=="") {
			$alert = " Please Enter Other Adverts Location Url ";
		}
		
		if(!$alert) 
		{
		
		if($_REQUEST['cbo_keyword']=='-other-') {
				$sql_check = "SELECT count(*) as cnt FROM costperclick_keywords 
								WHERE keyword_word = '".trim(add_slash($_REQUEST['keyword_other']))."' AND sites_site_id=$ecom_siteid";
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
				
				if($row_check['cnt'] > 0)
					$alert = 'Keyword Already exists '; 
				if(!$alert) 
				{
					$insert_array = array();
					$insert_array['keyword_word']=trim(add_slash($_REQUEST['keyword_other']));
					$insert_array['sites_site_id']=$ecom_siteid;
					
					$db->insert_from_array($insert_array, 'costperclick_keywords');
					$keyword_other_id = $db->insert_id();
				}
		}
		if($_REQUEST['cbo_advertlocation']=='-other-') 
		{
				$sql_check = "SELECT count(*) as cnt FROM costperclick_advertplacedon 
										WHERE advertplace_name = '".trim(add_slash($_REQUEST['advert_other']))."' 
											  AND sites_site_id=$ecom_siteid";
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
				
				if($row_check['cnt'] > 0)
					$alert = 'Adverts Placed Already exists  '; 
				if(!$alert) 
				{
					$insert_array = array();
					$insert_array['advertplace_name']=trim(add_slash($_REQUEST['advert_other']));
					$insert_array['sites_site_id']=$ecom_siteid;
					
					$db->insert_from_array($insert_array, 'costperclick_advertplacedon');
					$advert_other_id = $db->insert_id();
				}	
		}
		
			$insert_array = array();
			$insert_array['sites_site_id']=$ecom_siteid;
			
			if($_REQUEST['cbo_keyword']=='-other-') { 
				$insert_array['costperclick_keywords_keyword_id']=$keyword_other_id;
			} else {
				$insert_array['costperclick_keywords_keyword_id']=trim(add_slash($_REQUEST['cbo_keyword']));
			}
			if($_REQUEST['cbo_advertlocation']=='-other-') {
				$insert_array['costperclick_adverplaced_on_advertplace_id']=$advert_other_id;
			} else {
				$insert_array['costperclick_adverplaced_on_advertplace_id']=trim(add_slash($_REQUEST['cbo_advertlocation']));
			}
			$insert_array['url_mypage']=trim(add_slash($_REQUEST['url_mypage']));
			$insert_array['url_setting_rateperclick']=trim(add_slash($_REQUEST['url_setting_rateperclick']));
			$insert_array['url_setting_days']=trim(add_slash($_REQUEST['url_setting_days']));
			$insert_array['url_setting_noofclicks']=trim(add_slash($_REQUEST['url_setting_noofclicks']));
			$insert_array['url_hidden']=trim(add_slash($_REQUEST['url_hidden']));
			
			
			$db->insert_from_array($insert_array, 'costperclick_adverturl');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Cost Per Click URL added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=costperclick_urls&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_urls&fpurpose=edit&url_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_urls&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = 'Error!!'.$alert;
			include("includes/cp_urls/add_cp_urls.php");
		}
	}
}

else if($_REQUEST['fpurpose']=='update')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['cbo_keyword'], $_REQUEST['cbo_advertlocation'], $_REQUEST['url_mypage']);
		$fieldDescription = array('Keyword', 'Adverts Location', 'Url My Page');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		if($_REQUEST['cbo_keyword']=='-other-' && $_REQUEST['keyword_other']=="") {
			$alert = " Please Enter Other Keyword ";
		}
		else if($_REQUEST['cbo_advertlocation']=='-other-' && $_REQUEST['advert_other']=="") {
			$alert = " Please Enter Other Adverts Location Url ";
		}
		
		if(!$alert) 
		{
		
		if($_REQUEST['cbo_keyword']=='-other-') {
				$sql_check = "SELECT count(*) as cnt FROM costperclick_keywords 
								WHERE keyword_word = '".trim(add_slash($_REQUEST['keyword_other']))."' AND sites_site_id=$ecom_siteid";
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
				
				if($row_check['cnt'] > 0)
					$alert = 'Keyword Already exists '; 
				if(!$alert) 
				{
					$insert_array = array();
					$insert_array['keyword_word']=trim(add_slash($_REQUEST['keyword_other']));
					$insert_array['sites_site_id']=$ecom_siteid;
					
					$db->insert_from_array($insert_array, 'costperclick_keywords');
					$keyword_other_id = $db->insert_id();
				}
		}
		if($_REQUEST['cbo_advertlocation']=='-other-') 
		{
				$sql_check = "SELECT count(*) as cnt FROM costperclick_advertplacedon 
										WHERE advertplace_name = '".trim(add_slash($_REQUEST['advert_other']))."' 
											  AND sites_site_id=$ecom_siteid";
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
				
				if($row_check['cnt'] > 0)
					$alert = 'Adverts Placed Already exists  '; 
				if(!$alert) 
				{
					$insert_array = array();
					$insert_array['advertplace_name']=trim(add_slash($_REQUEST['advert_other']));
					$insert_array['sites_site_id']=$ecom_siteid;
					
					$db->insert_from_array($insert_array, 'costperclick_advertplacedon');
					$advert_other_id = $db->insert_id();
				}	
		}
		
		
		
			$update_array = array();
			$update_array['sites_site_id']=$ecom_siteid;
			if($_REQUEST['cbo_keyword']=='-other-') { 
				$update_array['costperclick_keywords_keyword_id']=$keyword_other_id;
			} else {
				$update_array['costperclick_keywords_keyword_id']=trim(add_slash($_REQUEST['cbo_keyword']));
			}
			if($_REQUEST['cbo_advertlocation']=='-other-') {
				$update_array['costperclick_adverplaced_on_advertplace_id']=$advert_other_id;
			} else {
				$update_array['costperclick_adverplaced_on_advertplace_id']=trim(add_slash($_REQUEST['cbo_advertlocation']));
			}
			$update_array['url_mypage']=trim(add_slash($_REQUEST['url_mypage']));
			$update_array['url_setting_rateperclick']=trim(add_slash($_REQUEST['url_setting_rateperclick']));
			$update_array['url_setting_days']=trim(add_slash($_REQUEST['url_setting_days']));
			$update_array['url_setting_noofclicks']=trim(add_slash($_REQUEST['url_setting_noofclicks']));
			$update_array['url_hidden']=trim(add_slash($_REQUEST['url_hidden']));
			
			$db->update_from_array($update_array, 'costperclick_adverturl', 'url_id', $_REQUEST['url_id']);
			$alert .= '<br><span class="redtext"><b>Cost per Click Url Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=costperclick_urls&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_urls&fpurpose=edit&url_id=<?=$_REQUEST['url_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=costperclick_urls&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
		
			$alert = 'Error!!'.$alert;
			include("includes/cp_urls/add_cp_urls.php");
		}
	}
}

else if($_REQUEST['fpurpose']=='delete')
{		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		$alertcnt = 0;
		
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry URL not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
						// Removing the Details
						$sql_del = "DELETE FROM costperclick_month WHERE costperclick_adverturl_url_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM costperclick_time WHERE costperclick_adverturl_url_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM costperclick_adverturl WHERE url_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$alertcnt +=1;
										
				}	
			}
				if($alert) $alert .="<br />";
					$alert .= $alertcnt ." Cost Per Click URLS Deleted. Also All linked Details deleted";
			 
		}
			include("../includes/cp_urls/list_cp_urls.php");
}	
?>