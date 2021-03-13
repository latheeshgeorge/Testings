<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/survey/list_survey.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$survey_ids_arr 		= explode('~',$_REQUEST['survey_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($survey_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['survey_hide']	= $new_status;
			$survey_id 						= $survey_ids_arr[$i];	
			$db->update_from_array($update_array,'survey',array('survey_id'=>$survey_id));
			
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/survey/list_survey.php');
		
}
elseif($_REQUEST['fpurpose']=='change_survey_status')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$survey_ids_arr 		= explode('~',$_REQUEST['survey_ids']);
		$new_status		= $_REQUEST['ch_survey_status'];
		for($i=0;$i<count($survey_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['survey_status']	= $new_status;
			$survey_id 						= $survey_ids_arr[$i];	
			$db->update_from_array($update_array,'survey',array('survey_id'=>$survey_id));
			
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/survey/list_survey.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Survey not selected';
		}
		else
		{
		// Find the feature details for module mod_survey from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_survey'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM survey WHERE survey_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $sql_del_survey_options = "DELETE FROM survey_option WHERE survey_id=".$del_arr[$i];
					  $db->query($sql_del_survey_options);
					  $sql_del_survey_results = "DELETE FROM survey_results WHERE survey_id = ".$del_arr[$i];
					  $db->query($sql_del_survey_results);
					
				// to delete from the display settings table
				  $sql_display_del = "DELETE FROM display_settings WHERE display_component_id=".$del_arr[$i]." AND features_feature_id =".$cur_featid." AND sites_site_id = $ecom_siteid ";
					 $db->query($sql_display_del);
				}	
			}
			$alert = "Surveys deleted Sucessfully";
			recreate_entire_websitelayout_cache();
		}
		include ('../includes/survey/list_survey.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/survey/add_survey.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include_once("classes/fckeditor.php");
		include ('includes/survey/ajax/survey_ajax_functions.php');
		$survey_id = $_REQUEST['checkbox'][0];
		include("includes/survey/edit_survey.php");
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
	//Function to validate forms
	validate_forms();
	if(!$alert){
		$sql_check = "SELECT count(*) as cnt FROM survey WHERE survey_title = '".trim(add_slash($_REQUEST['survey_title']))."' AND sites_site_id=$ecom_siteid ";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Error : Survey Name Already exists ';
		}
	
	if(!$alert){// checking whether atleast one option is added
		$option_count = count($_REQUEST['option_text']);
		for($i=1;$i<= $option_count;$i++){
			if($_REQUEST['option_text'][$i]!=''){
				$atleastone=1;
			}
		}
		if(!$atleastone){
		$alert = 'Error: Please Add atleast one value for the option ';
		}
		if(!$alert)
			{
				if ($_REQUEST['survay_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['survay_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['survay_displayenddate'],'normal','-'))
							$alert = 'Start or End Date is Invalid';
				}		
			}	
		if(!$alert) {
			$insert_array								= array();
			$insert_array['sites_site_id'] 				= $ecom_siteid;
			$insert_array['survey_title']				= trim(add_slash($_REQUEST['survey_title']));
			$insert_array['survey_question']			= add_slash($_REQUEST['survey_question']);
			$insert_array['survey_hide']				= $_REQUEST['survey_hide'];
			$insert_array['survey_status']				= $_REQUEST['survey_status'];
			$insert_array['survey_displayresults'] 		=($_REQUEST['survey_displayresults'])?1:0;
			$insert_array['survey_showinall'] 			=($_REQUEST['survey_showinall'])?1:0;
			$insert_array['survay_activateperiodchange']=$_REQUEST['survay_activateperiodchange'];
			$exp_survay_displaystartdate=explode("-",$_REQUEST['survay_displaystartdate']);
			$val_survay_displaystartdate=$exp_survay_displaystartdate[2]."-".$exp_survay_displaystartdate[1]."-".$exp_survay_displaystartdate[0];
			$exp_survay_displayenddate=explode("-",$_REQUEST['survay_displayenddate']);
			$val_survay_displayenddate=$exp_survay_displayenddate[2]."-".$exp_survay_displayenddate[1]."-".$exp_survay_displayenddate[0];
			$val_survey_displaystartdatetime     		=  $val_survay_displaystartdate." ".$_REQUEST['survey_starttime_hr'].":".$_REQUEST['survey_starttime_mn'].":".$_REQUEST['survey_starttime_ss'];
			$val_survey_displayenddatetime  			=  $val_survay_displayenddate." ".$_REQUEST['survey_endtime_hr'].":".$_REQUEST['survey_endtime_mn'].":".$_REQUEST['survey_endtime_ss'];
			$insert_array['survay_displaystartdate']	=  $val_survey_displaystartdatetime;
			$insert_array['survay_displayenddate']		=  $val_survey_displayenddatetime;
			$db->insert_from_array($insert_array,'survey');
			 $insert_id =  $db->insert_id();
		//Label value in case of dropdown
		for($i=1;$i<=$option_count;$i++)
		{
			//$update_value_array['value_id']=$_REQUEST['value_id'.$i];
			if($_REQUEST['option_id'][$i])
			{
				$update_option_array=array();
				if($_REQUEST['option_text'][$i] &&  $_REQUEST['option_id'][$i]){// check whether a value is removed or made null from a text box while editing
					$update_option_array['option_text']=add_slash($_REQUEST['option_text'][$i]);
					$update_option_array['option_order']=(is_numeric($_REQUEST['option_order'][$i]))? add_slash($_REQUEST['option_order'][$i]):0;
					$db->update_from_array($update_option_array, 'survey_option','option_id',$_REQUEST['option_id'][$i]);
				}
				elseif(($_REQUEST['option_id'][$i]) && ($_REQUEST['option_text'][$i]=='')){
					$filter_arr=array('option_id' => $_REQUEST['option_id'][$i]);// to delete the removed/null value from the text box
					$db->delete_from_array($filter_arr,'survey_option');	
				}
		   }
			else if($_REQUEST['option_text'][$i])
			{
				$insert_option_array=array();
				$insert_option_array['survey_id']=$insert_id;
				//$insert_value_array['site_id']=$ecom_siteid;
				$insert_option_array['option_text']=add_slash($_REQUEST['option_text'][$i]);
				$insert_option_array['option_order']=(is_numeric($_REQUEST['option_order'][$i]))? add_slash($_REQUEST['option_order'][$i]):0;
				$db->insert_from_array($insert_option_array,'survey_option');
			}	
				
		}
		
		//updating the options values ends
		
		// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
				// Find the feature details for module mod_survey from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_survey'";
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
						 $cur_arr 		= explode("_",$_REQUEST['display_id'][$i]);
						 	$layoutid	= $cur_arr[0];
							$layoutcode	= $cur_arr[1];
							$position	= $cur_arr[2];
							$insert_array										= array();
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['features_feature_id']				= $cur_featid;
							$insert_array['display_position']					= $position;
							$insert_array['themes_layouts_layout_id']			= $layoutid;
							$insert_array['layout_code']						= add_slash($layoutcode);
							$insert_array['display_title']						= add_slash(trim($_REQUEST['survey_title']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $insert_id;
							$db->insert_from_array($insert_array,'display_settings');
							
						
					}
			}
			recreate_entire_websitelayout_cache();
	?>
		<br><font color="red"><b>Survey Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=survey&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to the Survey Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=survey&fpurpose=edit&survey_id=<?php echo $insert_id?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to the Survey Edit Page</a><br />
		<br /><a class="smalllink" href="home.php?request=survey&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to Add Survay Page</a><br /><br />
	<?php
		}else {// if error
		 include ('includes/survey/add_survey.php');
		}
	}
	else {// if error
	 include ('includes/survey/add_survey.php');
	}

}
}
else if($_REQUEST['fpurpose'] == 'update_survey') {  // for updating the survey

	if($_REQUEST['survey_id'])
	{
		//Function to validate forms
		validate_forms();
		if(!$alert){
		$sql_check = "SELECT count(*) as cnt 
						FROM survey 
							WHERE survey_title = '".trim(add_slash($_REQUEST['survey_title']))."' 
								  AND sites_site_id=$ecom_siteid AND survey_id<>".$_REQUEST['survey_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Survey Name Already exists ';
		}
		if(!$alert)
			{
				if ($_REQUEST['survay_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['survay_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['survay_displayenddate'],'normal','-'))
							$alert = 'Start or End Date is Invalid';
				}		
			}
		if ($alert=='')
		{ 
			$update_array								= array();
			$update_array['sites_site_id'] 				= $ecom_siteid;
			$update_array['survey_title']				= trim(add_slash($_REQUEST['survey_title']));
			$update_array['survey_question']			= add_slash($_REQUEST['survey_question']);
			$update_array['survey_hide']				= $_REQUEST['survey_hide'];
			$update_array['survey_status']				= $_REQUEST['survey_status'];
			$update_array['survey_displayresults'] 		=($_REQUEST['survey_displayresults'])?1:0;
			$update_array['survey_showinall'] 			=($_REQUEST['survey_showinall'])?1:0;
			$update_array['survay_activateperiodchange']=$_REQUEST['survay_activateperiodchange'];
			$exp_survay_displaystartdate=explode("-",$_REQUEST['survay_displaystartdate']);
			$val_survay_displaystartdate=$exp_survay_displaystartdate[2]."-".$exp_survay_displaystartdate[1]."-".$exp_survay_displaystartdate[0];
			$exp_survay_displayenddate=explode("-",$_REQUEST['survay_displayenddate']);
			$val_survay_displayenddate=$exp_survay_displayenddate[2]."-".$exp_survay_displayenddate[1]."-".$exp_survay_displayenddate[0];
			 if($_REQUEST['survay_activateperiodchange']==0){
			$val_survay_displaystartdate ="0000-00-00";
			$val_survay_displayenddate ="0000-00-00";
			}
			
			$val_survey_displaystartdatetime     		=  $val_survay_displaystartdate." ".$_REQUEST['survey_starttime_hr'].":".$_REQUEST['survey_starttime_mn'].":".$_REQUEST['survey_starttime_ss'];
			$val_survey_displayenddatetime  			=  $val_survay_displayenddate." ".$_REQUEST['survey_endtime_hr'].":".$_REQUEST['survey_endtime_mn'].":".$_REQUEST['survey_endtime_ss'];
			$update_array['survay_displaystartdate']	=  $val_survey_displaystartdatetime;
			$update_array['survay_displayenddate']		=  $val_survey_displayenddatetime;
		$db->update_from_array($update_array, 'survey', array('survey_id' => $_REQUEST['survey_id'] , 'sites_site_id' => $ecom_siteid));
	
		//// section for adding/editing/delting values for the options
		//if(!isset($_REQUEST['option_text'])){ // validattion for checking whether atleast one value exists for the drop down
			//echo "zsdcsd";
			//$atleastone=0;
			$option_count = count($_REQUEST['option_text']);
			for($i=1;$i<= $option_count;$i++){
				if($_REQUEST['option_text'][$i]!=''){
					$atleastone=1;
					}
			}
		if(!$atleastone){
		$alert = 'Error: Please Add atleast one value for the option ';
		}
		if(!$alert) {
		
		//OPtion values
		for($i=1;$i<=$option_count;$i++)
		{		
			//$update_value_array['value_id']=$_REQUEST['value_id'.$i];
			if($_REQUEST['option_id'][$i])
			{
				$update_option_array=array();
				if($_REQUEST['option_text'][$i] &&  $_REQUEST['option_id'][$i]){// check whether a value is removed or made null from a text box while editing
					$update_option_array['option_text']=add_slash($_REQUEST['option_text'][$i]);
					$update_option_array['option_order']=(is_numeric($_REQUEST['option_order'][$i]))? add_slash($_REQUEST['option_order'][$i]):0;
					$db->update_from_array($update_option_array, 'survey_option','option_id',$_REQUEST['option_id'][$i]);
				}
				elseif(($_REQUEST['option_id'][$i]) && ($_REQUEST['option_text'][$i]=='')){
					$filter_arr=array('option_id' => $_REQUEST['option_id'][$i]);// to delete the removed/null value from the text box
					$db->delete_from_array($filter_arr,'survey_option');	
				}
		   }
					else if($_REQUEST['option_text'][$i])
					{
						$insert_option_array=array();
						$insert_option_array['survey_id']=$_REQUEST['survey_id'];
						//$insert_value_array['site_id']=$ecom_siteid;
						$insert_option_array['option_text']=add_slash($_REQUEST['option_text'][$i]);
						$insert_option_array['option_order']=(is_numeric($_REQUEST['option_order'][$i]))? add_slash($_REQUEST['option_order'][$i]):0;
						$db->insert_from_array($insert_option_array, 'survey_option');
					}	
						
				}
		
		//updating the options values ends
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_survey from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_survey'";
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
										display_component_id=".$_REQUEST['survey_id']."";
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
							$insert_array['display_title']						= add_slash(trim($_REQUEST['survey_title']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $_REQUEST['survey_id'];
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
						
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=".$_REQUEST['survey_id']." AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				}
                                // case if update the title in display settings is to be done for current combo deal
                                if($_REQUEST['survey_updatewebsitelayout']) 
                                {
                                    // Get the feature id of mod_combo from features table
                                    $sql_feat = "SELECT feature_id 
                                                    FROM 
                                                        features 
                                                    WHERE 
                                                        feature_modulename ='mod_survey' 
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
                                                        display_title='".trim(add_slash($_REQUEST['survey_title']))."' 
                                                    WHERE 
                                                        sites_site_id = $ecom_siteid 
                                                        AND features_feature_id = $cur_featid 
                                                        AND display_component_id = ".$_REQUEST['survey_id'];
                                    $db->query($sql_update);
                                }
								recreate_entire_websitelayout_cache();
			?>
			<br><font color="red"><b>Survey Updated Successfully</b></font><br>
			<br />
			<a class="smalllink" href="home.php?request=survey&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to the Survey Listing page</a><br />
			<br /><a class="smalllink" href="home.php?request=survey&fpurpose=edit&survey_id=<?=$_REQUEST['survey_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to the Survey Edit Page</a><br />
			<br /><a class="smalllink" href="home.php?request=survey&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pg']?>&status=<?=$_REQUEST['status']?>">Go Back to Add Survey Page</a><br /><br />
			<?php
		}
		else
		{
		?>
		<?php 
		$alert = '<center><font >Error! '.$alert;
			$alert .= '</font></center>';
			
		   $ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/survey/ajax/survey_ajax_functions.php');
			 include ('includes/survey/edit_survey.php');
			 ?>
	<?php
		}
}else{
$alert = 'Error! '.$alert;
		    $ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/survey/ajax/survey_ajax_functions.php');
			include ('includes/survey/edit_survey.php');
	}
}
}
//Show Survey Results
elseif($_REQUEST['fpurpose'] == 'list_survey_maininfo') 
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		show_survey_maininfo($_REQUEST['survey_id']);
}

/*To list categories assigned in the Survey using AJAX*/
elseif($_REQUEST['fpurpose'] == 'list_survey_categgroup'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		show_category_list($_REQUEST['survey_id']);
}elseif($_REQUEST['fpurpose'] == 'changestat_category_ajax'){ // To Change the status of the selected category in the Surveys
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Categories not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the Survey
				 $sql_chstat = "UPDATE survey_display_category SET survey_display_category_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Categories'; 
		}	
		show_category_list($_REQUEST['cur_survey_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_categories'){// to list the categories to be assigned to the Survey
$survey_id = $_REQUEST['checkbox'][0];
	include ('includes/survey/list_assign_categories.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_categories'){// to asign the categories to the Survey

	
	 $survey_id = $_REQUEST['survey_id'];
	{
		
		if ($_REQUEST['category_ids'] == '')
		{
			$alert = 'Sorry Category not not selected';
		}
		else
		{ 
		
	 $sql_assigned_categories = "SELECT product_categories_category_id FROM survey_display_category WHERE survey_survey_id =".$_REQUEST['survey_id'];
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
					$insert_array['survey_survey_id']=$_REQUEST['survey_id'];
					$insert_array['product_categories_category_id']=$categories_arr[$i];
					$db->insert_from_array($insert_array, 'survey_display_category');
				}	
			}
			$alert = 'Survey(s) Successfully assigned categories'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=survey&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&survey_id=<?=$_REQUEST['survey_id']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Survey Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=edit&survey_id=<?=$_REQUEST['survey_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>&curtab=survycatg_tab_td&survey_title=<?=$_REQUEST['survey_title']?>" onclick="show_processing()">Go Back to the Edit  this Survey</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&status=<?=$_REQUEST['status']?>">Go Back to Add Survey Page</a><br /><br />		
			<?
	
}
elseif($_REQUEST['fpurpose']=='delete_category_ajax') // section used for delete of Category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry categories not not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting pages from page survey
					$sql_del = "DELETE FROM survey_display_category WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Categories Successfully Removed from the Survey'; 
		}	
show_category_list($_REQUEST['cur_survey_id'],$alert);
}elseif($_REQUEST['fpurpose'] == 'list_survey_prodgroup'){ // for listing the products assiged to the survey to be displyed 
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		show_product_list($_REQUEST['survey_id'],$alert);
}else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Survey
$survey_id = $_REQUEST['checkbox'][0];
	include ('includes/survey/list_assign_products.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_products'){// to asign the categories to the Survey

	
	$survey_id = $_REQUEST['survey_id'];
	{
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not  selected';
		}
		else
		{ 
		
		$sql_assigned_products = "SELECT products_product_id FROM survey_display_product WHERE survey_survey_id =".$_REQUEST['survey_id']." AND sites_site_id=".$ecom_siteid;
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
					$insert_array['survey_survey_id']=$_REQUEST['survey_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'survey_display_product');
				}	
			}
			$alert = 'Products Successfully assigned  to Survey(s)'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=survey&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&survey_id=<?=$_REQUEST['survey_id']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Survey Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=edit&survey_id=<?=$_REQUEST['survey_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>&curtab=survyprod_tab_td&survey_title=<?=$_REQUEST['survey_title']?>" onclick="show_processing()">Go Back to the Edit  this Survey</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Add New Surevy</a>		
			<?
	
}elseif($_REQUEST['fpurpose'] == 'changestat_product_ajax'){ // To Change the status of the selected Product assigned to the Adevrt
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/survey/ajax/survey_ajax_functions.php');
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
					// Changing status of the catgories in the Survey
				$sql_chstat = "UPDATE survey_display_product SET survey_display_product_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Product(s) assigned to the Survey'; 
		}	
		show_product_list($_REQUEST['cur_survey_id'],$alert);
}elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to Survey using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/survey/ajax/survey_ajax_functions.php');
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
					// Deleting product from Survey
					$sql_del = "DELETE FROM survey_display_product WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Removed from the Survey'; 
		}	
show_product_list($_REQUEST['cur_survey_id'],$alert);
}
/* FOR the assigned STATIC PAGES PART*/
elseif($_REQUEST['fpurpose'] == 'list_survey_staticgroup'){
		 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		show_assign_pages_list($_REQUEST['survey_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'changestat_assign_pages_ajax'){ // To Change the status of the selected Page assigned to the Survey
		 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/survey/ajax/survey_ajax_functions.php');
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
					// Changing status of the static pages assigned to the Survey
		$sql_chstat = "UPDATE survey_display_static SET survey_display_static_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Page(s) assigned to the Survey'; 
		}	
		show_assign_pages_list($_REQUEST['cur_survey_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_pages'){// to list the products to be assigned to the Survey
$survey_id = $_REQUEST['checkbox'][0];
	include ('includes/survey/list_assign_pages.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_pages_to_Survey'){// to asign the Static Pages to the Survey
	
	$survey_id = $_REQUEST['survey_id'];
	{
		
		if ($_REQUEST['page_ids'] == '')
		{
			$alert = 'Sorry Pages not not selected';
		}
		else
		{ 
		
		$sql_assigned_pages = "SELECT static_pages_page_id FROM survey_display_static WHERE survey_survey_id =".$_REQUEST['survey_id']." AND sites_site_id=".$ecom_siteid;
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
					$insert_array['survey_survey_id']=$_REQUEST['survey_id'];
					$insert_array['static_pages_page_id']=$pages_arr[$i];
					$db->insert_from_array($insert_array, 'survey_display_static');
				}	
			}
			$alert = 'Static Page(s) Successfully assigned to Survey'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=survey&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Survey Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=edit&survey_id=<?=$_REQUEST['survey_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>&curtab=survystatic_tab_td&survey_title=<?=$_REQUEST['survey_title']?>" onclick="show_processing()">Go Back to the Edit  this Survey</a><br /><br />
			<a class="smalllink" href="home.php?request=survey&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&status=<?=$_REQUEST['status']?>" onclick="show_processing()">Go Back to the Add New Survey</a>		
			<?
	
}elseif($_REQUEST['fpurpose']=='delete_assign_pages') // section used for delete of Static Pages assigned to Survey using ajax
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/survey/ajax/survey_ajax_functions.php');
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
					// Deleting product from Survey
					$sql_del = "DELETE FROM survey_display_static WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Pages Successfully Removed from the Survey'; 
		}	
show_assign_pages_list($_REQUEST['cur_survey_id'],$alert);
}

//==============================================
// to display the survey results
elseif($_REQUEST['fpurpose']=='list_survey_result') // section used for delete of Static Pages assigned to Survey using ajax
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/survey/ajax/survey_ajax_functions.php');
		
		view_survey_results($_REQUEST['survey_id']);
}


//==============================================END disply survey results
// ===============================================================================
// 						FUNCTIONS USED IN THIS PAGE
// ===============================================================================	
function validate_forms()
{
	global $alert,$db;
	//if($_REQUEST['dont_save']!=1)
	//{
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['survey_title'],$_REQUEST['survey_question']);
		$fieldDescription 	= array('Survey Title','Survey Question');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
	
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	//}
}



?>
