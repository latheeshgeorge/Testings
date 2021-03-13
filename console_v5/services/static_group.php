<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/static_group/list_group.php");
}

elseif($_REQUEST['fpurpose']=='save_order')
{
	//print_r($_REQUEST);
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['group_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'static_pagegroup',array('group_id'=>$IdArr[$i]));
		// Delete cache
		delete_statgroup_cache($IdArr[$i]);
	}
	delete_body_cache();
	recreate_entire_websitelayout_cache();
	$alert = 'Order saved successfully.';
	include ('../includes/static_group/list_group.php');
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$group_ids_arr 	= explode('~',$_REQUEST['group_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($group_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['group_hide']		= $new_status;
			$group_id 						= $group_ids_arr[$i];	
			$db->update_from_array($update_array,'static_pagegroup',array('group_id'=>$group_id));
			// Delete cache
			delete_statgroup_cache($group_id);
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		$alert = 'Status changed successfully.';
		include ('../includes/static_group/list_group.php');
}

else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Menu not selected';
		}
		else
		{
		// Find the feature details for module mod_productcatgroup from features table
			$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_staticgroup'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$cur_featid	= $row_feat['feature_id'];
			}
		   $deleted_cnt = 0 ;
		   $del_arr = explode("~",$_REQUEST['del_ids']);
		   for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					$sql_check = "SELECT count(*) FROM static_pagegroup_static_page_map spm,static_pages sp WHERE spm.static_pagegroup_group_id=".$del_arr[$i]." AND spm.static_pages_page_id=sp.page_id";
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{
					++$deleted_cnt;
					$sql_del = "DELETE FROM static_pagegroup WHERE group_id=".$del_arr[$i];
					$db->query($sql_del);
					// to delete from the display settings table
					$sql_display_del = "DELETE FROM display_settings WHERE display_component_id=".$del_arr[$i]." AND features_feature_id =".$cur_featid." AND sites_site_id = $ecom_siteid ";
					$db->query($sql_display_del);
					// Delete cache
					delete_statgroup_cache($del_arr[$i]);
					}
					else
					{
					 $cant_delete[] =  $del_arr[$i];
					}
				}	
			}
			if(count($cant_delete))  {
				$cant_deleteId = implode(",",$cant_delete);
					if($alert) 
					$alert .="<br />";
					$alert .= "Sorry !!-Cannot Delete Static Menu Id(s)-$cant_deleteId - Some static pages are already assigned to the Menu(s)";
				}
				if($deleted_cnt)  {
					if($alert) 
					$alert .="<br />";
					$alert .= " $deleted_cnt Static Page Menu(s) Deleted Sucessfully";
				}
			//$alert = "Static Page groups  deleted Sucessfully";
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		include ('../includes/static_group/list_group.php');

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/static_group/add_group.php");
}
else if($_REQUEST['fpurpose']=='edit')
{		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/static_group/ajax/static_group_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$group_id = $_REQUEST['checkbox'][0];
	include("includes/static_group/edit_group.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['group_name']);
		$fieldDescription = array('Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM static_pagegroup WHERE group_name = '".trim(add_slash($_REQUEST['group_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Menu Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['group_name']=add_slash($_REQUEST['group_name']);
			$insert_array['group_hidename']=$_REQUEST['group_hidename'];
			$insert_array['group_showinall']=$_REQUEST['group_showinall'];
			$insert_array['group_showhomelink']=$_REQUEST['group_showhomelink'];
			$insert_array['group_showsitemaplink']=$_REQUEST['group_showsitemaplink'];
			$insert_array['group_showhelplink']=$_REQUEST['group_showhelplink'];
			$insert_array['group_showsavedsearchlink']= ($_REQUEST['group_showsavedsearchlink'])?1:0;
			$insert_array['group_showxmlsitemaplink']= ($_REQUEST['group_showxmlsitemaplink'])?1:0;
			$insert_array['group_showfaqlink']= ($_REQUEST['group_showfaqlink'])?1:0;		
			
			$insert_array['group_order']=$_REQUEST['group_order'];
			$insert_array['group_hide']=$_REQUEST['group_hide'];
			$insert_array['group_listtype'] = $_REQUEST['group_listtype'];
			$insert_array['sites_site_id']=$ecom_siteid;
			
			$db->insert_from_array($insert_array, 'static_pagegroup');
			$insert_id = $db->insert_id();
			//#Group Position
//			if(count($group_position)>0)
//			{
//				foreach($group_position as $v)
//				{
//					$insert_array = array();
//					$insert_array['static_pagegroup_group_id']=$insert_id;
//					$insert_array['group_position']=$v;
//					$db->insert_from_array($insert_array, 'static_pagegroup_position');
//				}
//			
//			}
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_staticgroup'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
					// checking wheter the feature is added in the site menu table // BY ANU 
					$sql_chk_menu = "SELECT menu_id FROM site_menu WHERE features_feature_id = ".$cur_featid;
					$res_chk_menu = $db->query($sql_chk_menu);
					$if_menu_exists = $db->num_rows($res_chk_menu);
					if(!$if_menu_exists){
					$sql_insert_menu = "INSERT INTO site_menu (sites_site_id,features_feature_id,menu_title) VALUES ($ecom_siteid,$cur_featid,'".$row_feat['feature_name']."')";
					$db->query($sql_insert_menu);
					}
					//end checking site menu
					for($i=0;$i<count($_REQUEST['display_id']);$i++)
					{
						$cur_arr 	= explode("_",$_REQUEST['display_id'][$i]);
						$layoutid	= $cur_arr[0];
						$layoutcode	= $cur_arr[1];
						$position	= $cur_arr[2];
						$insert_array										= array();
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['features_feature_id']				= $cur_featid;
						$insert_array['display_position']					= $position;
						$insert_array['themes_layouts_layout_id']			= $layoutid;
						$insert_array['layout_code']						= add_slash($layoutcode);
						$insert_array['display_title']						= add_slash(trim($_REQUEST['group_name']));
						$insert_array['display_order']						= 0;
						$insert_array['display_component_id']				= $insert_id;
						$db->insert_from_array($insert_array,'display_settings');
					}
				}
				// Completed the section to entry details to display_settings table
				delete_body_cache();
				recreate_entire_websitelayout_cache();
				if($_REQUEST['Submit'] == 'Save & Return to Edit')
				{
				?>
				<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=stat_group&fpurpose=edit&group_id=<?=$insert_id?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=static_tab_td';
				</script>
				<?
				}
				else
				{
					$alert .= '<br><span class="redtext"><b>Static Page Menu added successfully</b></span><br>';
					echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing Static page Menus </a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$insert_id?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit static page Menu </a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Static page Menu</a>
		<?		}
		}	
		else
		{
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/static_group/add_group.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['group_name']);
		$fieldDescription = array('Menu Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM static_pagegroup WHERE group_name = '".trim(add_slash($_REQUEST['group_name']))."' AND sites_site_id=$ecom_siteid AND group_id<>".$_REQUEST['group_id'];
		
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Menu Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['group_name']=add_slash($_REQUEST['group_name']);
			$update_array['group_hidename']=$_REQUEST['group_hidename'];
			$update_array['group_showinall']=$_REQUEST['group_showinall'];
			$update_array['group_showhomelink']= ($_REQUEST['group_showhomelink'])?1:0;
			$update_array['group_showsitemaplink']= ($_REQUEST['group_showsitemaplink'])?1:0;
			$update_array['group_showhelplink']= ($_REQUEST['group_showhelplink'])?1:0;
			$update_array['group_showsavedsearchlink']= ($_REQUEST['group_showsavedsearchlink'])?1:0;
			$update_array['group_showxmlsitemaplink']= ($_REQUEST['group_showxmlsitemaplink'])?1:0;
			$update_array['group_showfaqlink']= ($_REQUEST['group_showfaqlink'])?1:0;
			$update_array['group_order']=$_REQUEST['group_order'];
			$update_array['group_hide']=$_REQUEST['group_hide'];
			$update_array['group_listtype'] = $_REQUEST['group_listtype'];
			$update_array['sites_site_id']=$ecom_siteid;
			$db->update_from_array($update_array, 'static_pagegroup', 'group_id', $_REQUEST['group_id']);
			#Group Position
			
			/*#Deleting existing records
			$sql_del = "DELETE FROM static_pagegroup_position WHERE static_pagegroup_group_id=".$_REQUEST['group_id'];
			$db->query($sql_del);
			if(count($group_position)>0)
			{
				foreach($group_position as $v)
				{
					$insert_array = array();
					$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
					$insert_array['group_position']=$v;
					$db->insert_from_array($insert_array, 'static_pagegroup_position');
				}
			
			}*/
			// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_staticgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_staticgroup'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
					
					// checking wheter the feature is added in the site menu table // BY ANU 
					$sql_chk_menu = "SELECT menu_id FROM site_menu WHERE features_feature_id = ".$cur_featid;
					$res_chk_menu = $db->query($sql_chk_menu);
					$if_menu_exists = $db->num_rows($res_chk_menu);
					if(!$if_menu_exists){
					$sql_insert_menu = "INSERT INTO site_menu (sites_site_id,features_feature_id,menu_title) VALUES ($ecom_siteid,$cur_featid,'".$row_feat['feature_name']."')";
					$db->query($sql_insert_menu);
					}
					//end checking site menu
					$sel_dispid	= array();
					for($i=0;$i<count($_REQUEST['display_id']);$i++)
					{
						$cur_arr 		= explode("_",$_REQUEST['display_id'][$i]);
						$dispid			= $cur_arr[0];
						$sel_dispid[] 	= $dispid;
						// Check whether this disp id is already selected for this category group
						$sql_check = "SELECT display_id FROM display_settings WHERE display_id=$dispid AND 
										sites_site_id=$ecom_siteid AND features_feature_id = $cur_featid AND 
										display_component_id=".$_REQUEST['group_id']."";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0 or $dispid==0)
						{
							$layoutid		= $cur_arr[1];
							$layoutcode		= $cur_arr[2];
							$position		= $cur_arr[3];
							$insert_array										= array();
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['features_feature_id']				= $cur_featid;
							$insert_array['display_position']					= $position;
							$insert_array['themes_layouts_layout_id']			= $layoutid;
							$insert_array['layout_code']						= add_slash($layoutcode);
							$insert_array['display_title']						= add_slash(trim($_REQUEST['group_name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $_REQUEST['group_id'];
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
						// commented not to upadte the title in the display settings table
						//else
						//{
						//	$update_array						= array();
						//	$update_array['display_title']		= add_slash(trim($_REQUEST['display_title']));
						//	$db->update_from_array($update_array,'display_settings',array('display_id'=>$dispid));
						//}
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=".$_REQUEST['group_id']." AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				}
                                // case if update the title in display settings is to be done for current combo deal
                                if($_REQUEST['statgroup_updatewebsitelayout']) 
                                {
                                    // Get the feature id of mod_combo from features table
                                    $sql_feat = "SELECT feature_id 
                                                    FROM 
                                                        features 
                                                    WHERE 
                                                        feature_modulename ='mod_staticgroup' 
                                                    LIMIT 
                                                        1";
                                    $ret_feat = $db->query($sql_feat);
                                    if ($db->num_rows($ret_feat))
                                    {
                                            $row_feat       = $db->fetch_array($ret_feat);
                                            $cur_featid     = $row_feat['feature_id'];
                                    }
                                    $sql_update = "UPDATE 
                                                        display_settings 
                                                    SET 
                                                        display_title='".trim(add_slash($_REQUEST['group_name']))."' 
                                                    WHERE 
                                                        sites_site_id = $ecom_siteid 
                                                        AND features_feature_id = $cur_featid 
                                                        AND display_component_id = ".$_REQUEST['group_id'];
                                    $db->query($sql_update);
                                }
                                // Delete cache
                                delete_statgroup_cache($_REQUEST['group_id']);
				delete_body_cache();
				recreate_entire_websitelayout_cache();
				if($_REQUEST['Submit'] == 'Save & Return')
				{
				?>
				<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>';
				</script>
				<?
				}
				else
				{
					$alert .= '<br><span class="redtext"><b>Static Page Menu Updated successfully</b></span><br>';
					echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing Static page Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  Static Page Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Static Page Menu</a>
		<?		}
		}
		else {
			$alert = 'Error! '.$alert;
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/static_group/ajax/static_group_ajax_functions.php');
			include_once("classes/fckeditor.php");
			$group_id = $_REQUEST['checkbox'][0];
			include("includes/static_group/edit_group.php");
		}
	}
}
/* To assign and list pages in the static page group usiong ajax*/
/*elseif($_REQUEST['fpurpose'] == 'pagegroup_assign')
{
//print_r($_REQUEST);
$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	
	if ($catids == '')
		{
			$alert = 'Sorry Category not selected';
		}
		else
		{
			$cat_ids = explode("~",$_REQUEST['catids']);
			$sql_check_assigned = "SELECT product_categories_category_id FROM static_pagegroup_display_category WHERE sites_site_id =".$ecom_siteid." AND static_pagegroup_group_id=".$_REQUEST['group_id'];
				$res_check_assigned = $db->query($sql_check_assigned);
				$already_assigned = array();
				while($check_assigned = $db->fetch_array($res_check_assigned)){
				$already_assigned[] = $check_assigned['product_categories_category_id'];
				}
				//print_r($already_assigned);
				//echo "<br>";
			for($i=0;$i<count($cat_ids);$i++)
			{
				if(trim($cat_ids[$i]))
				{
				if(!in_array($cat_ids[$i],$already_assigned)){
					//if($cat_ids[$i] != $check_assigned['product_categories_category_id']){
					$insert_array = array();
					$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
					$insert_array['product_categories_category_id']=$cat_ids[$i];
					$insert_array['sites_site_id']=$ecom_siteid;
					$db->insert_from_array($insert_array, 'static_pagegroup_display_category');
					}
					  if($alert) $alert .="<br />";
				}	
			}
		}
		// Delete cache
		delete_statgroup_cache($_REQUEST['group_id']);
		$alert .= "<span >Sucessfully assigned</span>";
		include ('../includes/static_group/edit_group.php');
}*/
elseif($_REQUEST['fpurpose'] =='list_staticgroup_maininfo')// Case of listing main info for shop groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		show_static_maininfo($_REQUEST['group_id']);
	}
elseif($_REQUEST['fpurpose'] == 'list_staticpages'){
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
	
		show_static_pages_list($_REQUEST['group_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'changestat_static_pages'){
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/static_group/ajax/static_group_ajax_functions.php');
	if ($_REQUEST['ch_ids'] == ''){
		$alert = 'Sorry Static Pages not selected';
	}
	else {
		$ch_stat = $_REQUEST['chstat'];
		$ch_arr = explode("~",$_REQUEST['ch_ids']);
		for($i=0;$i<count($ch_arr);$i++){
			if(trim($ch_arr[$i])){
				$sql_change = "UPDATE static_pagegroup_static_page_map SET static_pages_hide = $ch_stat WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}	
		}
		$alert = 'Hidden Status Changed Successfully';
	}	
	// Delete cache
	delete_statgroup_cache($_REQUEST['cur_group_id']);
	delete_body_cache();
	show_static_pages_list($_REQUEST['cur_group_id'],$alert);
}

elseif ($_REQUEST['fpurpose'] =='changeorder_static_pages') // change order of static pages
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry static Page(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE static_pagegroup_static_page_map SET static_pages_order = ".$chroder." WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			// Delete cache
			delete_statgroup_cache($_REQUEST['cur_group_id']);
			delete_body_cache();
			$alert = 'Order Saved Successfully';
		}	
		show_static_pages_list($_REQUEST['cur_group_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='delete_static_pages') // section used for delete delete of static pages using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry static pages not not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting pages from page groups
					$sql_del = "DELETE FROM static_pagegroup_static_page_map WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			// Delete cache
			delete_statgroup_cache($_REQUEST['cur_group_id']);
			delete_body_cache();
			$alert = 'Static Page(s) Successfully Removed from the Menu'; 
		}	
show_static_pages_list($_REQUEST['cur_group_id'],$alert);
}

elseif($_REQUEST['fpurpose'] == 'assign_static_pages'){
	$group_id = $_REQUEST['checkbox'][0];
	include ('includes/static_group/list_static_pages.php');						
	
}elseif($_REQUEST['fpurpose'] == 'assign_pages'){
	
	$group_id = $_REQUEST['group_id'];
	{
		
		if ($_REQUEST['page_ids'] == '')
		{
			$alert = 'Sorry static pages not not selected';
		}
		else
		{ 
		
		$sql_assigned_pages = "SELECT static_pages_page_id FROM static_pagegroup_static_page_map where static_pagegroup_group_id =".$_REQUEST['group_id'];
		$res_assigned_pages = $db->query($sql_assigned_pages);
		$assigned_pages_arr = array();
		while($assigned_pages = $db->fetch_array($res_assigned_pages)){
		$assigned_pages_arr[]= $assigned_pages['static_pages_page_id'];
		}
			$page_arr = explode("~",$_REQUEST['page_ids']);
			for($i=0;$i<count($page_arr);$i++)
			{
				if(trim($page_arr[$i]) && !in_array($page_arr[$i],$assigned_pages_arr))
				{
					$insert_array = array();
					$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
					$insert_array['static_pages_page_id']=$page_arr[$i];
					$db->insert_from_array($insert_array, 'static_pagegroup_static_page_map');
				}	
			}
			$alert = 'Static Page(s) assigned Successfully  '; 
			// Delete cache
			delete_statgroup_cache($_REQUEST['group_id']);
			delete_body_cache();
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&group_id=<?=$_REQUEST['group_id']?>" onclick="show_processing()">Go Back to the Static Page Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=static_tab_td" onclick="show_processing()">Go Back to the Edit  this Static Page Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Static page Menu</a>		
			<?
}
/*To list categories assigned in the page group using AJAX*/
elseif($_REQUEST['fpurpose'] == 'list_displaycategory_group'){
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		show_category_list($_REQUEST['group_id']);
}elseif($_REQUEST['fpurpose'] == 'changestat_category_ajax'){ // To Change the status of the selected category in the group
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Categories not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the page group
				 $sql_chstat = "UPDATE static_pagegroup_display_category SET static_pagegroup_category_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Categories'; 
		}	
		show_category_list($_REQUEST['cur_group_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_categories'){// to list the categories to be assigned to the Pge group
	$group_id = $_REQUEST['checkbox'][0];
	include ('includes/static_group/list_assign_categories.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_categories'){// to asign the categories to the group

	
	$group_id = $_REQUEST['group_id'];
	{
		
		if ($_REQUEST['category_ids'] == '')
		{
			$alert = 'Sorry Category not not selected';
		}
		else
		{ 
		
		$sql_assigned_categories = "SELECT product_categories_category_id FROM static_pagegroup_display_category WHERE static_pagegroup_group_id =".$_REQUEST['group_id'];
		$res_assigned_categories = $db->query($sql_assigned_categories);
		$assigned_categories_arr = array();
		while($assigned_categories = $db->fetch_array($res_assigned_categories)){
				$assigned_categories_arr[]= $assigned_categories['product_categories_category_id'];
		}
				$categories_arr = explode("~",$_REQUEST['category_ids']);
				for($i=0;$i<count($categories_arr);$i++)
				{
					if(trim($categories_arr[$i]) && !in_array($categories_arr[$i],$assigned_categories_arr))
					{
						$insert_array = array();
						$insert_array['sites_site_id']=$ecom_siteid;
						$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
						$insert_array['product_categories_category_id']=$categories_arr[$i];
						$db->insert_from_array($insert_array, 'static_pagegroup_display_category');
					}	
				}
				$alert = 'Categories Assigned Successfully  '; 
			}						
		
		}
		$alert = '<center><font color="red"><b>'.$alert;
				$alert .= '</b></font></center>';
				echo $alert;
				?>
		<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&group_id=<?=$_REQUEST['group_id']?>" onclick="show_processing()">Go Back to the Static Page Menu Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=catmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Page Menu</a><br /><br />
				 <a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Static page Menu</a>	
				<?
		
}
elseif($_REQUEST['fpurpose']=='delete_category_ajax') // section used for delete of Category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry static pages not not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting pages from page groups
					$sql_del = "DELETE FROM static_pagegroup_display_category WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Categories Successfully Removed from the Page Menu'; 
		}	
show_category_list($_REQUEST['cur_group_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'list_displayproduct_group'){ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		show_product_list($_REQUEST['group_id'],$alert);
}

else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Page group
	$group_id = $_REQUEST['checkbox'][0];
	include ('includes/static_group/list_assign_products.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_products'){// to asign the categories to the group

	
	$group_id = $_REQUEST['group_id'];
	{
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{ 
		
		$sql_assigned_products = "SELECT products_product_id FROM static_pagegroup_display_product WHERE static_pagegroup_group_id =".$_REQUEST['group_id']." AND sites_site_id=".$ecom_siteid;
		$res_assigned_products = $db->query($sql_assigned_products);
		$assigned_products_arr = array();
		while($assigned_products = $db->fetch_array($res_assigned_products)){
			$assigned_products_arr[]= $assigned_products['products_product_id'];
		}
			$products_arr = explode("~",$_REQUEST['product_ids']);
			for($i=0;$i<count($products_arr);$i++)
			{
				if(trim($products_arr[$i]) && !in_array($products_arr[$i],$assigned_products_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'static_pagegroup_display_product');
				}	
			}
			$alert = 'Products Assigned Successfully '; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&group_id=<?=$_REQUEST['group_id']?>" onclick="show_processing()">Go Back to the Static Page Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Static Page Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Static page Menu</a> 		
			<?
	
}elseif($_REQUEST['fpurpose'] == 'changestat_product_ajax'){ // To Change the status of the selected Product assigned to the Page group
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the page group
				$sql_chstat = "UPDATE static_pagegroup_display_product SET static_pagegroup_product_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Product(s) assigned to the Menu'; 
		}	
		show_product_list($_REQUEST['cur_group_id'],$alert);
}elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to page Groups using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Products  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from page groups
					$sql_del = "DELETE FROM static_pagegroup_display_product WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Removed from the Page Menu'; 
		}	
show_product_list($_REQUEST['cur_group_id'],$alert);
}
/* FOR the assigned STATIC PAGES PART*/
elseif($_REQUEST['fpurpose'] == 'list_displaystatic_group'){
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		show_assign_pages_list($_REQUEST['group_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'changestat_assign_pages_ajax'){ // To Change the status of the selected Page assigned to the Page group
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Pages not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the page group
		$sql_chstat = "UPDATE static_pagegroup_display_static SET static_pagegroup_pages_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Page(s) assigned to the Menu'; 
		}	
		show_assign_pages_list($_REQUEST['cur_group_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_pages'){// to list the products to be assigned to the Page group
	$group_id = $_REQUEST['checkbox'][0];

	include ('includes/static_group/list_assign_pages.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_pages_to_Group'){// to asign the Static Pages to the group

	
	$group_id = $_REQUEST['group_id'];
	{
		
		if ($_REQUEST['page_ids'] == '')
		{
			$alert = 'Sorry Pages not not selected';
		}
		else
		{ 
		
		$sql_assigned_pages = "SELECT static_pages_page_id 
										FROM static_pagegroup_display_static 
												WHERE static_pagegroup_group_id =".$_REQUEST['group_id']." 
													  AND sites_site_id=".$ecom_siteid;
		$res_assigned_pages = $db->query($sql_assigned_pages);
		$assigned_pages_arr = array();
		while($assigned_pages = $db->fetch_array($res_assigned_pages)){
			$assigned_pages_arr[]= $assigned_pages['static_pages_page_id'];
		}
			$pages_arr = explode("~",$_REQUEST['page_ids']);
			for($i=0;$i<count($pages_arr);$i++)
			{
				if(trim($pages_arr[$i]) && !in_array($pages_arr[$i],$assigned_pages_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['static_pagegroup_group_id']=$_REQUEST['group_id'];
					$insert_array['static_pages_page_id']=$pages_arr[$i];
					$db->insert_from_array($insert_array, 'static_pagegroup_display_static');
				}	
			}
			$alert = 'Static Page(s) Assigned Successfully '; 
		}						
	
	}
	delete_body_cache();
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=stat_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&group_id=<?=$_REQUEST['group_id']?>" onclick="show_processing()">Go Back to the Static Page Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=statmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Page Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_Menu_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>		
			<?
	
}elseif($_REQUEST['fpurpose']=='delete_assign_pages') // section used for delete of Static Pages assigned to page Menus using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/static_group/ajax/static_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Pages  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from page groups
					$sql_del = "DELETE FROM static_pagegroup_display_static WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Pages Successfully Removed from the Page Menu'; 
		}	
		delete_body_cache();
		show_assign_pages_list($_REQUEST['cur_group_id'],$alert);
}
?>
