<?php
	#################################################################
	# Script Name 	: component_positions.php
	# Description 	: Action page for component positions
	# Coded by 		: Sny
	# Created on	: 25-Jul-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
if($_REQUEST['fpurpose'] == '') // Showing the manage page
{
	include("includes/mobile_component_position/manage_componentpositions.php");
} 
elseif($_REQUEST['fpurpose']=='save_componentsection')
{
	if($_REQUEST['comp_str'])
	{
		$sellayout = $_REQUEST['cbo_sellayout'];
		$sql_lay = "SELECT layout_positions,layout_id FROM themes_layouts WHERE themes_theme_id=$ecom_mobilethemeid AND layout_code='".$sellayout."'";
		$ret_lay = $db->query($sql_lay);
		if($db->num_rows($ret_lay))
		{
			$row_lay 		= $db->fetch_array($ret_lay);
			$pos_arr		= explode(",",$row_lay['layout_positions']);
			$curlayoutid	= $row_lay['layout_id'];
		}
		$sel_arr = explode('~',$_REQUEST['comp_str']);
		
		
		// Section to find out the unallowed components posted in the middle area. The array generated in this section will be 
		// merged with the array to be used to eleminate the moved components from a section
		$k=0;
		$undel_ids	= array(0);
		for($i=1;$i<count($sel_arr);$i++)
		{	
			$sel_ids 	= explode(",",$sel_arr[$i]);
			
			for ($j=0;$j<count($sel_ids);$j++)
			{
				if($pos_arr[$k]=='middle')
				{
					if($sel_ids[$j])
					{
						$curid_arr 	= explode("_",$sel_ids[$j]);
						$curid		= $curid_arr[0];
						$curcomp	= $curid_arr[1];
						$curdisps= $curid_arr[3];	
						// Chech whether the current feature is allowed in middle area
						$sql_check = "SELECT feature_id FROM features WHERE feature_id=$curid AND feature_allowedinmiddlesection=1 LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)==0 && $curdisps)
						{
							$undel_ids[] = $curdisps;
						}	
					}
				}
			}
			$k++;
		}
					
		$k=0;
		for($i=1;$i<count($sel_arr);$i++)
		{	
			$sel_ids 	= explode(",",$sel_arr[$i]);
			$disp_ids	= array(0);
			for ($j=0;$j<count($sel_ids);$j++)
			{
				if($pos_arr[$k]=='middle')
				{
					if($sel_ids[$j])
					{
						$curid_arr 	= explode("_",$sel_ids[$j]);
						$curid		= $curid_arr[0];
						$curcomp	= $curid_arr[1];
						// Chech whether the current feature is allowed in middle area
						$sql_check = "SELECT feature_id FROM features WHERE feature_id=$curid AND feature_allowedinmiddlesection=1 LIMIT 1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check))
						{
							$display_allowed = true;
						}
						else
						{
							$display_allowed = false;
							$alert .= "<br><strong>'".$row_check['feature_name']."'</strong> Not allowed in position ".$pos_arr[$k];
						}	
					}
				}
				else
					$display_allowed = true;
				if($sel_ids[$j] && $display_allowed)
				{
					
					$curid_arr 	= explode("_",$sel_ids[$j]);
					$curid		= $curid_arr[0];
					$curcomp	= (!$curid_arr[1])?0:$curid_arr[1];
					//Check whether current item already exists in current position for current site_id in current layout
					$sql_check = "SELECT display_id FROM display_settings WHERE features_feature_id=".$curid." 
					AND  display_component_id=$curcomp AND display_position='".$pos_arr[$k]."' AND sites_site_id=$ecom_siteid AND layout_code='$sellayout'";
					//print "<br>".$sql_check;
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))//if already exists, then update only its order
					{
						$row_check						= $db->fetch_array($ret_check);
						$update_array					= array();
						$update_array['display_order']	= $j;
						$db->update_from_array($update_array,'display_settings',array('display_id'=>$row_check['display_id']));
						$disp_ids[] = $row_check['display_id'];
					}
					else// if not exists then place a new entry
					{
						$curtitle 									= getComponenttitle($curid,$curcomp);
						$insert_array								= array();
						$insert_array['sites_site_id']				= $ecom_siteid;
						$insert_array['display_position']			= $pos_arr[$k];
						$insert_array['themes_layouts_layout_id']	= $curlayoutid;
						$insert_array['layout_code']				= $sellayout;
						$insert_array['features_feature_id']		= $curid;
						$insert_array['display_title']				= $curtitle;
						$insert_array['display_order']				= $j;
						$insert_array['display_component_id']		= $curcomp;
						$db->insert_from_array($insert_array,'display_settings');
						$insert_id = $db->insert_id();
						$disp_ids[] = $insert_id;
					}		
				}
			}
			// Merging the arrays to consider the case of unallowed components in the middle area
			$eleminate_arr 	= array_merge($disp_ids,$undel_ids);
			$eleminate_str 	= implode(",",$eleminate_arr);
			$sql_del 		= "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND layout_code ='$sellayout' AND 
			display_position='".$pos_arr[$k]."' AND display_id NOT IN ($eleminate_str)";
			$db->query($sql_del);
			$k++;
		}
		clear_all_cache();// Clearing all cache
		// Calling function to cache the website layout section
		save_cache_website_layout($curlayoutid,$sellayout);	
	}
	if($_REQUEST['movetoedit'])
	{	
		include("includes/mobile_component_position/edit_componentpositiontitles.php");
	}
	else
	{
		$alert = 'Component Positions Saved Successsfully';
		include("includes/mobile_component_position/manage_componentpositions.php");
	}	
	
}
elseif ($_REQUEST['fpurpose'] == 'Save_titles') // Case of coming to update site_menu
{
	$cnt = count($_REQUEST['txt_displayid']);
	for($i=0;$i<$cnt;$i++)
	{
		// Calling the funtion to update the title for current component in display settings
		updateComponenttitle(trim($_REQUEST['txt_displayid'][$i]),$_REQUEST['txt_displaytitle'][$i]);
	}
	clear_all_cache();// Clearing all cache
	save_cache_website_layout($_REQUEST['cur_layoutid'],$_REQUEST['layoutcode']);	
	$alert = 'Component Details Updated Successfully';
	include("includes/mobile_component_position/manage_componentpositions.php");
}
elseif ($_REQUEST['fpurpose'] == 'Save_Position')
{
	/*
	if($_REQUEST['comp_str'])
	{
		$sellayout = $_REQUEST['cbo_sellayout'];
		$sql_lay = "SELECT layout_positions FROM themes_layouttypes WHERE theme_id=$ecom_themeid AND layout_code='".$sellayout."'";
		$ret_lay = $db->query($sql_lay);
		if($db->num_rows($ret_lay))
		{
			$row_lay 	= $db->fetch_array($ret_lay);
			$pos_arr	= explode(",",$row_lay['layout_positions']);
		}
		$sel_arr = explode('~',$_REQUEST['comp_str']);
		$k=0;
		$middle_allowed	= array('mod_shelf','mod_resell','mod_rent','mod_homepagecontent','mod_featured');
		for($i=1;$i<count($sel_arr);$i++)
		{
			$sel_ids 	= explode(",",$sel_arr[$i]);
			$disp_ids	= array(0);
			for ($j=0;$j<count($sel_ids);$j++)
			{
				if($pos_arr[$k]=='inline')
				{
					if($sel_ids[$j])
					{
						$sql_check = "SELECT b.module_name,a.menu_title FROM site_menu a, features b WHERE a.menu_id=".$sel_ids[$j]." AND 
										a.feature_id=b.feature_id AND a.site_id=$ecom_siteid";
						$ret_check = $db->query($sql_check);
						$row_check = $db->fetch_array($ret_check);
						if(in_array($row_check['module_name'],$middle_allowed))
						{
							$display_allowed = true;
						}
						else
						{
							$display_allowed = false;
							$alert .= "<br><strong>'".$row_check['menu_title']."'</strong> Not allowed in position ".$pos_arr[$k];
						}	
					}
				}
				else
					$display_allowed = true;
				if($sel_ids[$j] && $display_allowed)
				{
					//Check whether current item already exists in current position for current site_id in current layout
					$sql_check = "SELECT display_id FROM display_settings WHERE menu_id=".$sel_ids[$j]." 
					AND display_position='".$pos_arr[$k]."' AND site_id=$ecom_siteid AND layout_code='$sellayout'";
					$ret_check = $db->query($sql_check);
					if($db->num_rows($ret_check))//if already exists, then update only its order
					{
						$row_check						= $db->fetch_array($ret_check);
						$update_array					= array();
						$update_array['display_order']	= $j;
						$db->update_from_array($update_array,'display_settings',array('display_id'=>$row_check['display_id']));
						$disp_ids[] = $row_check['display_id'];
					}
					else// if not exists then place a new entry
					{
						$sql_site = "SELECT menu_title,feature_id FROM site_menu WHERE site_id=$ecom_siteid AND menu_id=".$sel_ids[$j];
						$ret_site = $db->query($sql_site);
						if ($db->num_rows($ret_site))
						{
							$row_site 	= $db->fetch_array($ret_site);
							$curtitle	= $row_site['menu_title']; 
						}
						$insert_array							= array();
						$insert_array['site_id']				= $ecom_siteid;
						$insert_array['display_position']		= $pos_arr[$k];
						$insert_array['layout_code']			= $sellayout;
						$insert_array['menu_id']				= $sel_ids[$j];
						$insert_array['display_title']			= $curtitle;
						$insert_array['display_order']			= $j;
						$insert_array['feature_id']				= $row_site['feature_id'];
						$db->insert_from_array($insert_array,'display_settings');
						$insert_id = $db->insert_id();
						$disp_ids[] = $insert_id;
					}		
				}
			}
			$eleminate_str = implode(",",$disp_ids);
			$sql_del = "DELETE FROM display_settings WHERE site_id=$ecom_siteid AND layout_code ='$sellayout' AND 
			display_position='".$pos_arr[$k]."' AND display_id NOT IN ($eleminate_str)";
			$db->query($sql_del);
			$k++;	
		}
	}
	if($_REQUEST['movetoedit'])
	{	
		include("includes/component_titles/edit_componenttitles.php");
	}
	else
	{
		include("includes/component_titles/manage_componenttitles.php");
	}	*/
}
?>

