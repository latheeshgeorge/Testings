<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/themes/list_themes.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/themes/add_theme.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['theme_id']))
	{
		include("includes/themes/edit_theme.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid theme Id</font></center><br>';
		echo $alert;
		include("includes/themes/list_themes.php");
	}	
} else if($_REQUEST['fpurpose'] == 'edit_layout') {
	if(is_numeric($_REQUEST['layout_id']))
	{	
		include("includes/themes/edit_theme_layouts.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid theme layout Id</font></center><br>';
		echo $alert;
		include("includes/themes/list_theme_layouts.php");
	}	
} 

else if($_REQUEST['fpurpose'] == 'insert_layouts') {
	
	if($_REQUEST['Submit'])
	{
	
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'],$_REQUEST['layout_code']);
		$fieldDescription = array('Layout Name','Layout Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
       $sql_check = "SELECT count(*) as cnt FROM themes_layouts WHERE layout_name='".add_slash($_REQUEST['layout_name'])."' AND themes_theme_id=".$_REQUEST['pass_theme_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Layout  Name already exists for current theme';
		}
		
		if(!$alert) {
			$insert_array = array();
			$insert_array['layout_name'] = add_slash($_REQUEST['layout_name']);
			$insert_array['layout_code'] = add_slash($_REQUEST['layout_code']);
			
			/*
			$str_layout_positions='';
			if(count($_REQUEST['layout_positions'])>0)
			{
					foreach($_REQUEST['layout_positions'] as $v)
					{
						$str_layout_positions.=$v.',';
						
					}
					$str_layout_positions=substr($str_layout_positions,0,(strlen($str_layout_positions)-1));
					
			}*/
			
			$insert_array['layout_positions']       = $_REQUEST['layout_positions'] ;
			$insert_array['themes_theme_id']        = $_REQUEST['pass_theme_id'];
                        $insert_array['layout_support_cart']    = ($_REQUEST['layout_support_cart'])?1:0;
                     
			$db->insert_from_array($insert_array, 'themes_layouts');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&theme_name=<?=$_REQUEST['pass_theme_name']?>&themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to the Theme Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to the Theme Layout Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout_add&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to Add a New Layout</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit_layout&layout_id=<?=$insert_id?>&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to Edit Current Layout</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/add_theme_layouts.php");
			
		}
		
	}
	
	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['themename'],$_REQUEST['path']);
		$fieldDescription = array('Theme Name','Theme Path');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('themename' => $_REQUEST['themename']), 'themes');
		if($sql_check > 0) {
			$alert = 'Theme Name already exists';
		}
		if(!$alert) {
			$insert_array = array();
			$insert_array['themename']				= add_slash($_REQUEST['themename']);
			$insert_array['path']					= add_slash($_REQUEST['path']);
			$insert_array['themetype']					= add_slash($_REQUEST['themetype']);
			$insert_array['in_setup']				= ($_REQUEST['in_setup'])?1:0;
			$insert_array['page_positions']				= add_slash($_REQUEST['page_positions']);
			$insert_array['advert_positions']			= add_slash($_REQUEST['advert_positions']);
			$insert_array['categorygroup_positions']	        = add_slash($_REQUEST['categorygroup_positions']);
			$insert_array['shelf_positions']			= add_slash($_REQUEST['shelf_positions']);
			$insert_array['shelfgroup_positions']		= add_slash($_REQUEST['shelfgroup_positions']);
			$insert_array['survey_positions']			= add_slash($_REQUEST['survey_positions']);
			$insert_array['combo_positions']			= add_slash($_REQUEST['combo_positions']);
			$insert_array['thumbimage_geometry']		        = add_slash($_REQUEST['thumbimage_geometry']);
			$insert_array['bigimage_geometry']			= add_slash($_REQUEST['bigimage_geometry']);
			$insert_array['advertimage_geometry']		        = add_slash($_REQUEST['advertimage_geometry']);
			$insert_array['featuredproduct_positions']          = add_slash($_REQUEST['featuredproduct_positions']);
			$insert_array['categoryimage_geometry']		        = add_slash($_REQUEST['categoryimage_geometry']);
			$insert_array['categorythumbimage_geometry']        = add_slash($_REQUEST['categorythumbimage_geometry']);
			$insert_array['headerimage_geometry']		        = add_slash($_REQUEST['headerimage_geometry']);
			$insert_array['shelf_displaytypes']					= add_slash($_REQUEST['shelf_displaytypes']);
			$insert_array['shelf_listingstyles']		        = add_slash($_REQUEST['shelf_listingstyles']);
			$insert_array['product_listingstyles']		        = add_slash($_REQUEST['product_listingstyles']);
			$insert_array['subcategory_listingstyles']	        = add_slash($_REQUEST['subcategory_listingstyles']);
			$insert_array['image_listingstyles']				= add_slash($_REQUEST['image_listingstyles']);
			$insert_array['paymenttype_displaytypes']			= add_slash($_REQUEST['paymenttype_displaytypes']);
			$insert_array['shopbybrand_positions']				= add_slash($_REQUEST['shopbybrand_positions']);
			$insert_array['iconimage_geometry']					= add_slash($_REQUEST['iconimage_geometry']);
			$insert_array['theme_background_colour']			= add_slash($_REQUEST['theme_background_colour']);
			$insert_array['theme_font_style']					= add_slash($_REQUEST['theme_font_style']);
			$insert_array['allow_special_category_details']		= ($_REQUEST['allow_special_category_details'])?1:0;
			$insert_array['allow_attachment_icon']				= ($_REQUEST['allow_attachment_icon'])?1:0;
			$insert_array['themes_support_allowed_positions']   = ($_REQUEST['themes_support_allowed_positions'])?1:0;
			$insert_array['advert_support_types']               = add_slash($_REQUEST['advert_support_types']);
			$insert_array['product_image_display_format']		= add_slash($_REQUEST['product_image_display_format']);
			$insert_array['theme_var_onlyasdropdown']			= ($_REQUEST['theme_var_onlyasdropdown'])?1:0;
			$insert_array['theme_top_cat_dropdownmenu_support']	= ($_REQUEST['theme_top_cat_dropdownmenu_support'])?1:0;
			$db->insert_from_array($insert_array, 'themes');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$insert_id?>&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Theme</a>
			<br /><br />
			<a href="home.php?request=themes&fpurpose=add&theme_name=<?=$theme_name?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Theme</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/add_theme.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update_layouts') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['layout_name'],$_REQUEST['layout_code']);
		$fieldDescription = array('Layout Name','Layout Code');
		
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM themes_layouts WHERE layout_name='".add_slash($_REQUEST['layout_name'])."' AND themes_theme_id=".$_REQUEST['pass_theme_id'] . " AND layout_id<>".$_REQUEST['layout_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Layout Name already exists for current theme';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['layout_name'] = add_slash($_REQUEST['layout_name']);
			$update_array['layout_code'] = add_slash($_REQUEST['layout_code']);
			/*
			$str_layout_positions='';
			if(count($_REQUEST['layout_positions'])>0)
			{
					foreach($_REQUEST['layout_positions'] as $v)
					{
						$str_layout_positions.=$v.',';
						
					}
					$str_layout_positions=substr($str_layout_positions,0,(strlen($str_layout_positions)-1));
					
			}*/
			
			$update_array['layout_positions']       = $_REQUEST['layout_positions'];
                        $update_array['layout_support_cart']    = ($_REQUEST['layout_support_cart'])?1:0;
			$update_array['themes_theme_id']        = $_REQUEST['pass_theme_id'];
			$db->update_from_array($update_array, 'themes_layouts', 'layout_id', $_REQUEST['layout_id']);
			
			/*//Features default positions
			$sql_del = "DELETE FROM themes_layouts_features_default_positions WHERE themes_layouts_layout_id=".$_REQUEST['layout_id'];
			$db->query($sql_del);
			
			$i=1;
			while($i<=$_REQUEST['features_cnt'])
			{
				
				if($_REQUEST['feature_position'.$i])
				{
					$insert_array_pos = array();
					$insert_array_pos['themes_layouts_layout_id']=$_REQUEST['layout_id'];
					$insert_array_pos['features_feature_id']=$_REQUEST['feature_id'.$i];
					$insert_array_pos['def_position']=$_REQUEST['feature_position'.$i];
					$insert_array_pos['def_order']=add_slash($_REQUEST['feature_order'.$i]);
					$db->insert_from_array($insert_array_pos, 'themes_layouts_features_default_positions');
				}
				$i++;
			}*/
			
                        // Delete from allowed positions
                        $sql_del = "DELETE FROM themes_layouts_feature_allowed_positions WHERE themes_layouts_layout_id=".$_REQUEST['layout_id'];
                        $db->query($sql_del);
                        // Get the rows from themes_layouts_feature_special_position_components. These values decide how to split the positions
                        $special_comp = array();
                        $sql_comp_pos = "SELECT features_feature_modulename, themes_mapping_fields,direct_entry 
                                            FROM 
                                                themes_layouts_feature_special_position_components";
                        $ret_comp_pos = $db->query($sql_comp_pos);
                        if($db->num_rows($ret_comp_pos))
                        {
                            while($row_comp_pos = $db->fetch_array($ret_comp_pos))
                            {
                                $special_comp[$row_comp_pos['features_feature_modulename']] = array('map_field'=>$row_comp_pos['themes_mapping_fields'],'direct_entry'=>$row_comp_pos['direct_entry']);
                            }
                        }
                        $i=1;
                        while($i<=$_REQUEST['features_cnt'])
                        {
                                $cur_pos = '';
                                $mypos = array();
                                
                                //if($_REQUEST['feature_position'.$i])
                                {
                                    // Get the module name of current feature from features table
                                    $sql_feat = "SELECT feature_modulename  
                                                    FROM 
                                                        features  
                                                    WHERE  
                                                        feature_id=".$_REQUEST['feature_id'.$i]."  
                                                    LIMIT  
                                                        1";
                                    $ret_feat = $db->query($sql_feat);
                                    if($db->num_rows($ret_feat))
                                    {
                                        $row_feat = $db->fetch_array($ret_feat);
                                    }
                                    if(array_key_exists($row_feat['feature_modulename'],$special_comp))
                                    {
                                        $special_arr = array();
                                        if($special_comp[$row_feat['feature_modulename']]['map_field']!='') // if the 
                                        {
                                                // get the listing type from themes table
                                                $sql_theme = "SELECT ".$special_comp[$row_feat['feature_modulename']]['map_field']." 
                                                                FROM 
                                                                    themes 
                                                                WHERE 
                                                                    theme_id=".$_REQUEST['pass_theme_id']." 
                                                                LIMIT 
                                                                    1";
                                                $ret_theme = $db->query($sql_theme);
                                                if($db->num_rows($ret_theme))
                                                {
                                                    list($sp_val) = $db->fetch_array($ret_theme);
                                                    $specialmain_arr = explode(',',$sp_val);
                                                    foreach ($specialmain_arr as $k=>$v)
                                                    {
                                                        $specialtemp_arr = explode('=>',$v);
                                                        $special_arr[$specialtemp_arr[0]] = $specialtemp_arr[1];
                                                    }
                                                }  
                                        }
                                        else // case if values to be displayed is given directly in table
                                        {
                                            $sp_val = $special_comp[$row_feat['feature_modulename']]['direct_entry'];
                                            $specialmain_arr = explode(',',$sp_val);
                                            foreach ($specialmain_arr as $k=>$v)
                                            {
                                                $specialtemp_arr = explode('=>',$v);
                                                $special_arr[$specialtemp_arr[0]] = $specialtemp_arr[1];
                                            }
                                        }
                                        $cur_pos = '';
                                        $mypos = array(); 
                                        foreach ($special_arr as $kk=>$vv)    
                                        {
                                            if($cur_pos!='')
                                                $cur_pos    .= '~';
                                           $cur_pos     .= $kk.'=>';
                                           $mypos       = array();                          
                                           if(count($_REQUEST['feature_position'.$i.'_'.$kk]))
                                           {
                                                foreach ($_REQUEST['feature_position'.$i.'_'.$kk] as $indx=>$vals)
                                                {
                                                   $mypos[] = $vals;
                                                }
                                                $cur_pos .= implode(',',$mypos);
                                           }
                                        }
                                        $insert_array_pos = array();
                                        $insert_array_pos['themes_theme_id']            = $_REQUEST['pass_theme_id'];
                                        $insert_array_pos['themes_layouts_layout_id']   = $_REQUEST['layout_id'];
                                        $insert_array_pos['features_feature_id']        = $_REQUEST['feature_id'.$i];
                                        $insert_array_pos['features_feature_modulename']= $row_feat['feature_modulename'];
                                        $insert_array_pos['allow_positions']            = $cur_pos;   
                                        $db->insert_from_array($insert_array_pos, 'themes_layouts_feature_allowed_positions'); 
                                    }
                                    else
                                    {
                                        $cur_pos = '';
                                        $mypos = array();     
                                        if(count($_REQUEST['feature_position'.$i]))
                                        {
                                            foreach ($_REQUEST['feature_position'.$i] as $indx=>$vals)
                                            {
                                                $mypos[] = $vals;
                                            }
                                            $cur_pos .= implode(',',$mypos);
                                        }
                                        else
                                            $cur_pos = '';
                                        $insert_array_pos = array();
                                        $insert_array_pos['themes_theme_id']            = $_REQUEST['pass_theme_id'];
                                        $insert_array_pos['themes_layouts_layout_id']   = $_REQUEST['layout_id'];
                                        $insert_array_pos['features_feature_id']        = $_REQUEST['feature_id'.$i];
                                        $insert_array_pos['features_feature_modulename']= $row_feat['feature_modulename'];
                                        $insert_array_pos['allow_positions']            = $cur_pos;
                                        $db->insert_from_array($insert_array_pos,'themes_layouts_feature_allowed_positions');
                                    }   
                                }
                                $i++;
                        }
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&theme_name=<?=$_REQUEST['pass_theme_name']?>&themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to the Theme Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to the Theme Layout Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=theme_layout_add&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to Add a New Layout</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit_layout&layout_id=<?=$_REQUEST['layout_id']?>&pass_theme_id=<?=$_REQUEST['pass_theme_id']?>&theme_name=<?=$theme_name?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>&pass_themetype=<?=$_REQUEST['pass_themetype']?>">Go Back to Edit Current Layout</a>
			
			
		<?	
		}
		else
		{
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/edit_theme_layouts.php");	
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['themename'],$_REQUEST['path']);
		$fieldDescription = array('Theme Name','Theme Path');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM themes WHERE themename='".$_REQUEST['themename']."' AND theme_id<>".$_REQUEST['theme_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Theme Name already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['themename']					= add_slash($_REQUEST['themename']);
			$update_array['path']						= add_slash($_REQUEST['path']);
			$update_array['themetype']					= add_slash($_REQUEST['themetype']);
			$update_array['in_setup']					= ($_REQUEST['in_setup'])?1:0;
			$update_array['page_positions']				= add_slash($_REQUEST['page_positions']);
			$update_array['advert_positions']			= add_slash($_REQUEST['advert_positions']);
			$update_array['categorygroup_positions']	= add_slash($_REQUEST['categorygroup_positions']);
			$update_array['shelf_positions']			= add_slash($_REQUEST['shelf_positions']);
			$update_array['shelfgroup_positions']		= add_slash($_REQUEST['shelfgroup_positions']);
			$update_array['survey_positions']			= add_slash($_REQUEST['survey_positions']);
			$update_array['combo_positions']			= add_slash($_REQUEST['combo_positions']);
			$update_array['thumbimage_geometry']		= add_slash($_REQUEST['thumbimage_geometry']);
			$update_array['bigimage_geometry']			= add_slash($_REQUEST['bigimage_geometry']);
			$update_array['advertimage_geometry']		= add_slash($_REQUEST['advertimage_geometry']);
			$update_array['featuredproduct_positions']	= add_slash($_REQUEST['featuredproduct_positions']);
			$update_array['categoryimage_geometry']		= add_slash($_REQUEST['categoryimage_geometry']);
			$update_array['categorythumbimage_geometry']= add_slash($_REQUEST['categorythumbimage_geometry']);
			$update_array['headerimage_geometry']			= add_slash($_REQUEST['headerimage_geometry']);
			$update_array['shelf_displaytypes']					= add_slash($_REQUEST['shelf_displaytypes']);
			$update_array['shelf_listingstyles']						= add_slash($_REQUEST['shelf_listingstyles']);
			$update_array['product_listingstyles']					= add_slash($_REQUEST['product_listingstyles']);
			$update_array['subcategory_listingstyles']			= add_slash($_REQUEST['subcategory_listingstyles']);
			$update_array['image_listingstyles']					= add_slash($_REQUEST['image_listingstyles']);
			$update_array['paymenttype_displaytypes']		= add_slash($_REQUEST['paymenttype_displaytypes']);
			$update_array['shopbybrand_positions']				= add_slash($_REQUEST['shopbybrand_positions']);
			$update_array['iconimage_geometry']				= add_slash($_REQUEST['iconimage_geometry']);
			
			$update_array['theme_background_colour']	= add_slash($_REQUEST['backgrd_colour']);
			$update_array['theme_font_style']				= add_slash($_REQUEST['font_family']);
			
			$update_array['allow_special_category_details']     = ($_REQUEST['allow_special_category_details'])?1:0;
			$update_array['allow_attachment_icon']		  		= ($_REQUEST['allow_attachment_icon'])?1:0;
            $update_array['themes_support_allowed_positions'] 	= ($_REQUEST['themes_support_allowed_positions'])?1:0;
            $update_array['advert_support_types']             	= add_slash($_REQUEST['advert_support_types']);
			$update_array['product_image_display_format']	  	= add_slash($_REQUEST['product_image_display_format']);
			$update_array['theme_var_onlyasdropdown']			= ($_REQUEST['theme_var_onlyasdropdown'])?1:0;
			$update_array['theme_top_cat_dropdownmenu_support']	= ($_REQUEST['theme_top_cat_dropdownmenu_support'])?1:0;

			$db->update_from_array($update_array, 'themes', 'theme_id', $_REQUEST['theme_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=themes&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=themes&fpurpose=edit&theme_id=<?=$_REQUEST['theme_id']?>&theme_name=<?=$_REQUEST['theme_name']?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Theme</a>
			<br />
			<br />
			<a href="home.php?request=themes&fpurpose=add&theme_name=<?=$theme_name?>&themetype=<?=$_REQUEST['themetype']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New Theme</a>
			
			</center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/themes/edit_theme.php");
		}
		
	}
}
else if($_REQUEST['fpurpose'] == 'delete_layout'){
    
	$sql_del = "DELETE FROM themes_layouts WHERE layout_id=".$_REQUEST['layout_id'];
	$db->query($sql_del);
	$error_msg = 'Layout Deleted Successfully';
	include("includes/themes/list_theme_layouts.php");
	
}else if($_REQUEST['fpurpose'] == 'delete')
{
	// Check whether this theme is used in any of the sites
	$sql_sites 			= "SELECT count(site_id) as cnt FROM sites WHERE themes_theme_id=".$_REQUEST['theme_id'];
	$ret_sites 			= $db->query($sql_sites);
	list($use_cnt) 		= $db->fetch_array($ret_sites);
	if($use_cnt>0)
	{
		$error_msg = 'Sorry Delete not possible... Theme is in use by site(s)';
	}
	else
	{
		// Get all the entries in layout table related to this theme
		$sql_layout = "SELECT layout_id FROM themes_layouts WHERE themes_theme_id=".$_REQUEST['theme_id'];
		$ret_layout = $db->query($sql_layout);
		if($db->num_rows($ret_layout))
		{
			while ($row_layout = $db->fetch_array($ret_layout))
			{
				// Deleting the entries related to current layout from themes_layouts_features_default_positions
				$sql_del = "DELETE FROM themes_layouts_features_default_positions WHERE themes_layouts_layout_id =".$row_layout['layout_id'];
				$db->query($sql_del);
			}
		}
		$sql_del = "DELETE FROM themes_layouts WHERE themes_theme_id=".$_REQUEST['theme_id'];
		$db->query($sql_del);
		$sql_del = "DELETE FROM themes WHERE theme_id=".$_REQUEST['theme_id'];
		$db->query($sql_del);
		$error_msg = 'Theme Deleted Successfully';
	}
	include("includes/themes/list_themes.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout')
{
	include("includes/themes/list_theme_layouts.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout_add')
{
	include("includes/themes/add_theme_layouts.php");
}
elseif($_REQUEST['fpurpose']=='theme_layout_edit')
{
	include("includes/themes/edit_theme_layouts.php");
}
?>