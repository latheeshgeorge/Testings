<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_category_groups/list_product_category_groups.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		include ('includes/product_category_groups/add_product_category_groups.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		if ($_REQUEST['catgroup_Submit'])
		{
			$alert				='';
			$fieldRequired 		= array($_REQUEST['catgroup_name']);
			$fieldDescription 	= array('Group Name');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the categroy group name already exists
				$sql_exists 	= "SELECT count(catgroup_id) FROM product_categorygroup WHERE 
									catgroup_name='".add_slash($_REQUEST['catgroup_name'])."' AND sites_site_id=$ecom_siteid";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Menu name already exists";
			}
			if ($alert=='')
			{
				
				$insert_array									= array();
				$insert_array['sites_site_id']					= $ecom_siteid;
				$insert_array['catgroup_name']					= add_slash(trim($_REQUEST['catgroup_name']));
				$insert_array['catgroup_hide']					= ($_REQUEST['catgroup_hide'])?1:0;
				$insert_array['catgroup_hidename']				= ($_REQUEST['catgroup_hidename'])?1:0;
				$insert_array['catgroup_listtype']				= add_slash($_REQUEST['catgroup_listtype']);
				$insert_array['category_showimagetype']        		= ($_REQUEST['category_showimagetype']);				
				//$insert_array['catgroup_subcatlisttype']		= add_slash($_REQUEST['catgroup_subcatlisttype']);
				$insert_array['catgroup_showinall']				= ($_REQUEST['catgroup_showinall'])?1:0;
				//$insert_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
				//$insert_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
				//$insert_array['catgroup_showimage']				= ($_REQUEST['catgroup_showimage']==1)?1:0;
				//$insert_array['catgroup_showtitle']				= ($_REQUEST['catgroup_showtitle']==1)?1:0;
				//$insert_array['catgroup_showshortdescription']	= ($_REQUEST['catgroup_showshortdescription']==1)?1:0;
				//$insert_array['catgroup_showprice']				= ($_REQUEST['catgroup_showprice']==1)?1:0;
				$insert_array['catgroup_show_subcat_indropdown']= ($_REQUEST['catgroup_show_subcat_indropdown'])?1:0;
				$insert_array['catgroup_show_subcat_indropdown_subcount']= ($_REQUEST['catgroup_show_subcat_indropdown_subcount'])?$_REQUEST['catgroup_show_subcat_indropdown_subcount']:2;
				$db->insert_from_array($insert_array,'product_categorygroup');
				$insert_id = $db->insert_id();
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_productcatgroup'";
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
						$insert_array['display_title']						= add_slash(trim($_REQUEST['catgroup_name']));
						$insert_array['display_order']						= 0;
						$insert_array['display_component_id']				= $insert_id;
									
						$db->insert_from_array($insert_array,'display_settings');
					}
				}
				recreate_entire_websitelayout_cache();
				// Completed the section to entry details to display_settings table
				$alert .= '<br><span class="redtext"><b>Category Menu added successfully</b></span><br>';
				echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Category Menu Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Category Menu Edit the Page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Category Menu Add Page</a>
				<?
				
			}
			else
			{
				include ('includes/product_category_groups/add_product_category_groups.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		$edit_id = $_REQUEST['checkbox'][0];
		include ('includes/product_category_groups/edit_product_category_groups.php');
	}
	elseif($_REQUEST['fpurpose']=='save_edit')
	{
		if ($_REQUEST['catgroup_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['catgroup_name']);
			$fieldDescription = array('Group Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the categroy group name already exists
				$sql_exists 	= "SELECT count(catgroup_id) FROM product_categorygroup WHERE 
									catgroup_name='".add_slash($_REQUEST['catgroup_name'])."' AND sites_site_id=$ecom_siteid 
									AND catgroup_id NOT IN (".$_REQUEST['checkbox'][0].")";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Menu name already exists";
			}
			if ($alert=='')
			{
				
				$update_array									= array();
				$update_array['sites_site_id']					= $ecom_siteid;
				$update_array['catgroup_name']					= add_slash(trim($_REQUEST['catgroup_name']));
				$update_array['catgroup_hide']					= ($_REQUEST['catgroup_hide'])?1:0;
				$update_array['catgroup_hidename']				= ($_REQUEST['catgroup_hidename'])?1:0;
				$update_array['catgroup_listtype']				= add_slash($_REQUEST['catgroup_listtype']);
				//$update_array['catgroup_subcatlisttype']		= add_slash($_REQUEST['catgroup_subcatlisttype']);
				$update_array['catgroup_showinall']				= ($_REQUEST['catgroup_showinall'])?1:0;
				$update_array['category_showimagetype']        	= ($_REQUEST['category_showimagetype']);			
				//$update_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
				//$update_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
				//$update_array['catgroup_showimage']				= ($_REQUEST['catgroup_showimage']==1)?1:0;
				//$update_array['catgroup_showtitle']				= ($_REQUEST['catgroup_showtitle']==1)?1:0;
				//$update_array['catgroup_showshortdescription']	= ($_REQUEST['catgroup_showshortdescription']==1)?1:0;
				//$update_array['catgroup_showprice']				= ($_REQUEST['catgroup_showprice']==1)?1:0;
				$edit_id 										= $_REQUEST['checkbox'][0];
				$update_array['catgroup_show_subcat_indropdown']= ($_REQUEST['catgroup_show_subcat_indropdown'])?1:0;
				$update_array['catgroup_show_subcat_indropdown_subcount']= ($_REQUEST['catgroup_show_subcat_indropdown_subcount'])?$_REQUEST['catgroup_show_subcat_indropdown_subcount']:2;
				$db->update_from_array($update_array,'product_categorygroup',array('catgroup_id'=>$edit_id));
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_productcatgroup'";
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
										display_component_id=$edit_id";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0 or $dispid==0)
						{
							$layoutid											= $cur_arr[1];
							$layoutcode		= $cur_arr[2];
							$position		= $cur_arr[3];
							$insert_array										= array();
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['features_feature_id']				= $cur_featid;
							$insert_array['display_position']					= $position;
							$insert_array['themes_layouts_layout_id']			= $layoutid;
							$insert_array['layout_code']						= add_slash($layoutcode);
							$insert_array['display_title']						= add_slash(trim($_REQUEST['catgroup_name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $edit_id;
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
						//commented to for not to update the display settings table
						//else
						//{
						//	$update_array						= array();
						//	$update_array['display_title']		= add_slash(trim($_REQUEST['catgroup_name']));
						//	$db->update_from_array($update_array,'display_settings',array('display_id'=>$dispid));
						//}
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=$edit_id AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				}
                                // case if update the title in display settings is to be done for current catgroup
                                if($_REQUEST['catgroup_updatewebsitelayout']) 
                                {
                                    // Get the feature id of mod_productcatgroup from features table
                                    $sql_feat = "SELECT feature_id 
                                                    FROM 
                                                        features 
                                                    WHERE 
                                                        feature_modulename ='mod_productcatgroup' 
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
                                                        display_title='".trim(add_slash($_REQUEST['catgroup_name']))."' 
                                                    WHERE 
                                                        sites_site_id = $ecom_siteid 
                                                        AND features_feature_id = $cur_featid 
                                                        AND display_component_id = ".$edit_id;
                                    $db->query($sql_update);
                                }
                                
				// Deleting cache
				delete_catgroup_cache($edit_id);
				recreate_entire_websitelayout_cache();
				// Completed the section to entry details to display_settings table
				$alert .= '<br><span class="redtext"><b>Category Menu Updated successfully</b></span><br>';
				echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Category Menu Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?=$edit_id?>&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Category Menu Edit  Page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Category Menu Add Page</a>
				<?
				
			}
			else
			{	
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$edit_id = $_REQUEST['checkbox'][0];
				include ('includes/product_category_groups/edit_product_category_groups.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Category Menu not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			// Find the feature details for module mod_productcatgroup from features table
			$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename ='mod_productcatgroup'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$cur_featid	= $row_feat['feature_id'];
			}
			$deleted_cnt = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether categories exists under this category group
					$sql_check = "SELECT count(*) FROM product_categorygroup_category pcc,product_categories pc WHERE pcc.catgroup_id=".$del_arr[$i]." AND pcc.category_id=pc.category_id";
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{	
						++$deleted_cnt;
						$sql_del = "DELETE FROM product_categorygroup WHERE catgroup_id=".$del_arr[$i];
						$db->query($sql_del);
						// Delete all those entries from display setting corresponding to current category group 
						$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
									features_feature_id=$cur_featid AND display_component_id=".$del_arr[$i];
						$ret_del = $db->query($sql_del);
					
						
						// Deleting cache
						delete_catgroup_cache($del_arr[$i]);
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
					$alert .= "Sorry !!-Cannot Delete Category Menus  Id(s)-$cant_deleteId - Some Categories are already assigned to the menu(s)";
				}
				if($deleted_cnt)  {
					if($alert) 
					$alert .="<br />";
					$alert .= " $deleted_cnt Category Menu(s) Deleted Sucessfully";
				}
				
				recreate_entire_websitelayout_cache();
		}	
		include ('../includes/product_category_groups/list_product_category_groups.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['catids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$update_array					= array();
			$update_array['catgroup_hide']	= $new_status;
			$cur_id 						= $catid_arr[$i];	
			$db->update_from_array($update_array,'product_categorygroup',array('catgroup_id'=>$cur_id));
			
			// Deleting cache
			delete_catgroup_cache($cur_id);
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/product_category_groups/list_product_category_groups.php');
		
	}
	elseif($_REQUEST['fpurpose'] =='assign_sel')
	{
		include ('includes/product_category_groups/list_product_catgroup_selcategory.php');
	}
	elseif($_REQUEST['fpurpose'] =='list_categorygroup_maininfo')// Case of listing main info for category groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_categorygroup_maininfo($_REQUEST['group_id']);
	}
	elseif($_REQUEST['fpurpose'] =='list_displaycategorygroup')// Case of listing diaplay categories to groups
	{
	
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_diplaycategory_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_displayproductgroup')// Case of listing diaplay products to groups
	{
	
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_diplayproduct_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_categorygroup')// Case of listing categgories to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_category_group_list($_REQUEST['group_id'],$alert);
	}
	else if($_REQUEST['fpurpose']=='categoryGroupAssign') // Case of selecting categgories to groups
	{
		
		include ('includes/product_category_groups/list_category_catgroup_selproduct.php');
	}
	elseif($_REQUEST['fpurpose'] =='list_displaystaticgroup')// Case of listing diaplay pages to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_diplaystatic_group_list($_REQUEST['group_id'],$alert);
	}
    else if($_REQUEST['fpurpose']=='staticGroupAssign') // Case of selecting pages to groups
	{
		
		include ('includes/product_category_groups/list_static_catgroup_selproduct.php');
	}
	else if($_REQUEST['fpurpose']=='save_staticGroupAssign') // Case of saving pages to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			$sql_check = "SELECT static_pages_page_id FROM product_categorygroup_display_staticpages WHERE 
							static_pages_page_id= $v AND product_categorygroup_catgroup_id=".$_REQUEST['pass_group_id']." LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array=array();
				$insert_array['sites_site_id']=$ecom_siteid;
				$insert_array['product_categorygroup_catgroup_id']=$_REQUEST['pass_group_id'];
				$insert_array['static_pages_page_id']=$v;
				$db->insert_from_array($insert_array, 'product_categorygroup_display_staticpages');
			}
		}
		$alert='Page(s) Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br />
		<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Menu Listing page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_group_id']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=statmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Category Menu</a><br />
			<br />
		
	<?	
		
	
	}
	else if($_REQUEST['fpurpose']=='prodGroupAssign') // Case of selecting products to groups
	{

		include ('includes/product_category_groups/list_product_catgroup_selproduct.php');
	}
	else if($_REQUEST['fpurpose']=='save_categoryGroupAssign') // Case of selecting products to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			$sql_check = "SELECT product_categories_category_id FROM product_categorygroup_display_category WHERE 
						 product_categories_category_id = $v AND 
						 product_categorygroup_catgroup_id = ".$_REQUEST['pass_group_id'];
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array=array();
				$insert_array['sites_site_id']=$ecom_siteid;
				$insert_array['product_categorygroup_catgroup_id']=$_REQUEST['pass_group_id'];
				$insert_array['product_categories_category_id']=$v;
				$db->insert_from_array($insert_array, 'product_categorygroup_display_category');
			}
		}
		$alert='Category Assigned Successfullly';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br />
		<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Menu Listing page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_group_id']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Category Menu </a><br />
			<br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='save_prodGroupAssign') // Case of saving products to categories
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether already exists
			$sql_check = "SELECT products_product_id FROM product_categorygroup_display_products 
							WHERE product_categorygroup_catgroup_id=".$_REQUEST['pass_group_id']." AND 
							products_product_id = $v LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array=array();
				$insert_array['sites_site_id']=$ecom_siteid;
				$insert_array['product_categorygroup_catgroup_id']=$_REQUEST['pass_group_id'];
				$insert_array['products_product_id']=$v;
				$db->insert_from_array($insert_array, 'product_categorygroup_display_products');
			}
		}
		$alert='Product Assigned Successfullly';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br />
		<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Menu Listing page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_group_id']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Category Menu </a><br />
			<br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='staticGroupUnAssign') // Case of unassigning categories to groups
	{
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_display_staticpages WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Static Page unassigned successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_diplaystatic_group_list($groupid,$alert);
	}
	else if($_REQUEST['fpurpose']=='categoryGroupUnAssign') // Case of unassigning categories to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_display_category WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Category unassigned successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_diplaycategory_group_list($groupid,$alert);
	}
	else if($_REQUEST['fpurpose']=='prodGroupUnAssign') // Case of unassigning products to categories
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_display_products WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Product unassigned successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_diplayproduct_group_list($groupid,$alert);
	}
	
	elseif($_REQUEST['fpurpose'] =='assign_sel_save')
	{
		if (count($_REQUEST['checkbox_cat']))
		{
			foreach($_REQUEST['checkbox_cat'] as $v)
			{
				// check whether this is already assigned 
				$sql_check = "SELECT category_id FROM product_categorygroup_category WHERE catgroup_id=".$_REQUEST['pass_groupid']." 
								AND category_id = $v LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
					$insert_array					= array();
					$insert_array['catgroup_id']	= $_REQUEST['pass_groupid'];
					$insert_array['category_id']	= $v;
					$insert_array['category_order']	= 0;
					$db->insert_from_array($insert_array,'product_categorygroup_category');
				}	
			}				
		}
		// Clear Cache
		delete_catgroup_cache($_REQUEST['pass_groupid']);
		
		$alert = 'Product Categories Assigned successfully';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;	
		
		?>
		<br />
		<a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Menu Listing page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_groupid']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=category_tab_td" onclick="show_processing()">Go Back to the Edit this Category Menu </a><br />
			<br />
	<?php		
	}
	elseif($_REQUEST['fpurpose'] =='unassigncat')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['del_ids']);
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id = $catid_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id=$cur_id AND catgroup_id=$groupid";
			$db->query($sql_del);
			
		}
		// Clear Cache
		delete_catgroup_cache($groupid);
		
		$alert = 'Category unassigned successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_category_group_list($groupid,$alert);
	}
	elseif ($_REQUEST['fpurpose'] == 'save_catorder')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['ch_ids']);
		$order_arr 	= explode('~',$_REQUEST['ch_order']);
		$disptyp_arr 	= explode('~',$_REQUEST['disp_type']);
		$islink_arr 	= explode('~',$_REQUEST['is_link']);
		$dropwidth_arr 	= explode('~',$_REQUEST['drop_width']);
		$groupid	= $_REQUEST['group_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id 													= $id_arr[$i];
			$update_array										= array();
			$update_array['category_order']			= $order_arr[$i];
			$update_array['category_displaytype']	= $disptyp_arr[$i];
			$update_array['category_islink']		= $islink_arr[$i];	
			$dropwidth								= trim($dropwidth_arr[$i]);
			if($dropwidth)
			{
				if(is_numeric($dropwidth)) 
				{
					$update_array['category_subcat_width']	= $dropwidth;	
				}	
			}	
			$db->update_from_array($update_array,'product_categorygroup_category',array('catgroup_id'=>$groupid,'category_id'=>$id_arr[$i]));
		}
		// Clear Cache
		delete_catgroup_cache($groupid);
		
		$alert = 'Details Saved successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_category_group_list($groupid,$alert);
	}
?>
