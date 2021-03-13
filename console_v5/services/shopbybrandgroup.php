<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/shopbybrandgroup/list_shopbybrandgroup.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		include ('includes/shopbybrandgroup/add_shopbybrandgroup.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		if ($_REQUEST['shopbrandgroup_Submit'])
		{
			$alert='';
			$fieldRequired 			= array($_REQUEST['shopbrandgroup_name']);
			$fieldDescription 		= array('Product Shop Group Name');
			$fieldEmail 			= array();
			$fieldConfirm 			= array();
			$fieldConfirmDesc 		= array();
			$fieldNumeric 			= array();
			$fieldNumericDesc 		= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the product shop name already exists
				$sql_exists 	= "SELECT count(shopbrandgroup_id) FROM product_shopbybrand_group WHERE 
									shopbrandgroup_name='".trim(add_slash($_REQUEST['shopbrandgroup_name']))."' AND sites_site_id=$ecom_siteid 
									LIMIT 1";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Product Shop Menu already exists";
			}
			if(!$alert)
			{
				if ($_REQUEST['shopbrandgroup_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['shopbrandgroup_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['shopbrandgroup_displayenddate'],'normal','-'))
							$alert = 'Start or End Date is Invalid';
				}		
			}	
			if ($alert=='')
			{
				
				$insert_array												= array();
				$insert_array['sites_site_id']								= $ecom_siteid;
				$insert_array['shopbrandgroup_name']						= add_slash(trim($_REQUEST['shopbrandgroup_name']));
				$insert_array['shopbrandgroup_hide']						= ($_REQUEST['shopbrandgroup_hide'])?1:0;
				$insert_array['shopbrandgroup_hidename']					= ($_REQUEST['shopbrandgroup_hidename'])?1:0;
				$insert_array['shopbrandgroup_listtype']					= add_slash($_REQUEST['shopbrandgroup_listtype']);
				$insert_array['shopbrandgroup_subshoplisttype']				= add_slash($_REQUEST['shopbrandgroup_subshoplisttype']);
				$insert_array['shopbrandgroup_showinall']					= ($_REQUEST['shopbrandgroup_showinall'])?1:0;
				$insert_array['product_displaytype']						= add_slash($_REQUEST['product_displaytype']);
				$insert_array['shopbrandgroup_product_showimage']			= ($_REQUEST['shopbrandgroup_product_showimage']==1)?1:0;
				$insert_array['shopbrandgroup_product_showtitle']			= ($_REQUEST['shopbrandgroup_product_showtitle']==1)?1:0;
				$insert_array['shopbrandgroup_product_showshortdescription']= ($_REQUEST['shopbrandgroup_product_showshortdescription']==1)?1:0;
				$insert_array['shopbrandgroup_product_showprice']			= ($_REQUEST['shopbrandgroup_product_showprice']==1)?1:0;
				$insert_array['shopbrandgroup_display_rotator']				= ($_REQUEST['shopbrandgroup_display_rotator'])?1:0;
				$insert_array['shopbrandgroup_activateperiodchange']		= ($_REQUEST['shopbrandgroup_activateperiodchange'])?1:0;
				if ($_REQUEST['shopbrandgroup_activateperiodchange'])
				{
					$exp_shopbrandgroup_displaystartdate					= explode("-",$_REQUEST['shopbrandgroup_displaystartdate']);
					$val_shopbrandgroup_displaystartdate					= $exp_shopbrandgroup_displaystartdate[2]."-".$exp_shopbrandgroup_displaystartdate[1]."-".$exp_shopbrandgroup_displaystartdate[0];
					$exp_shopbrandgroup_displayenddate						= explode("-",$_REQUEST['shopbrandgroup_displayenddate']);
					$val_shopbrandgroup_displayenddate						= $exp_shopbrandgroup_displayenddate[2]."-".$exp_shopbrandgroup_displayenddate[1]."-".$exp_shopbrandgroup_displayenddate[0];
				}
				else
				{
						$val_shopbrandgroup_displaystartdate 				= '0000-00-00';
						$val_shopbrandgroup_displayenddate 					= '0000-00-00';
				}			
				$val_shopbrandgroup_displaystartdatetime     			=  $val_shopbrandgroup_displaystartdate." ".$_REQUEST['shopbrandgroup_starttime_hr'].":".$_REQUEST['shopbrandgroup_starttime_mn'].":".$_REQUEST['shopbrandgroup_starttime_ss'];
				$val_shopbrandgroup_displayenddatetime  				=  $val_shopbrandgroup_displayenddate." ".$_REQUEST['shopbrandgroup_endtime_hr'].":".$_REQUEST['shopbrandgroup_endtime_mn'].":".$_REQUEST['shopbrandgroup_endtime_ss'];
				$insert_array['shopbrandgroup_displaystartdate']		=  $val_shopbrandgroup_displaystartdatetime;
				$insert_array['shopbrandgroup_displayenddate']			=  $val_shopbrandgroup_displayenddatetime;
				$db->insert_from_array($insert_array,'product_shopbybrand_group');
				$groupinsert_id = $db->insert_id();
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shopbybrandgroup'";
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
					if(!$if_menu_exists)
					{
						$sql_insert_menu = "INSERT INTO site_menu (sites_site_id,features_feature_id,menu_title) VALUES ($ecom_siteid,$cur_featid,'".$row_feat['feature_name']."')";
						$db->query($sql_insert_menu);
					}
					//end checking site menu
					
					for($i=0;$i<count($_REQUEST['display_id']);$i++)
					{
						$cur_arr 											= explode("_",$_REQUEST['display_id'][$i]);
						$layoutid											= $cur_arr[0];
						$layoutcode											= $cur_arr[1];
						$position											= $cur_arr[2];
						$insert_array										= array();
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['features_feature_id']				= $cur_featid;
						$insert_array['display_position']					= $position;
						$insert_array['themes_layouts_layout_id']			= $layoutid;
						$insert_array['layout_code']						= add_slash($layoutcode);
						$insert_array['display_title']						= add_slash(trim($_REQUEST['shopbrandgroup_name']));
						$insert_array['display_order']						= 0;
						$insert_array['display_component_id']				= $groupinsert_id;
						$db->insert_from_array($insert_array,'display_settings');
					}
				}
				// Completed the section to entry details to display_settings table
				recreate_entire_websitelayout_cache();
				$alert .= '<br><span class="redtext"><b>Product Shop Menu added successfully</b></span><br>';
				echo $alert;
			?>
				<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?=$groupinsert_id?>&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=add&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Add Product Shop Group Page </a>
				<?
				
			}
			else
			{
				include ('includes/shopbybrandgroup/add_shopbybrandgroup.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		$edit_id = $_REQUEST['checkbox'][0];
		include ('includes/shopbybrandgroup/edit_shopbybrandgroup.php');
	}
	elseif($_REQUEST['fpurpose']=='save_edit')
	{
		if ($_REQUEST['shopbrandgroup_Submit'])
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['shopbrandgroup_name']);
			$fieldDescription 	= array('Product Shop Group Name');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the product shop name already exists
				$sql_exists 	= "SELECT count(shopbrandgroup_id) FROM product_shopbybrand_group WHERE 
									shopbrandgroup_name='".trim(add_slash($_REQUEST['shopbrandgroup_name']))."' AND sites_site_id=$ecom_siteid 
									AND shopbrandgroup_id NOT IN (".$_REQUEST['checkbox'][0].") 
									LIMIT 1";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Product Shop Menu already exists";
			}
			if(!$alert)
			{
				if ($_REQUEST['shopbrandgroup_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['shopbrandgroup_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['shopbrandgroup_displayenddate'],'normal','-'))
							$alert = 'Sorry!! Start or End Date is Invalid';
				}		
			}
			
			if ($alert=='')
			{
				$update_array												= array();
				$update_array['shopbrandgroup_name']						= add_slash(trim($_REQUEST['shopbrandgroup_name']));
				$update_array['shopbrandgroup_hide']						= ($_REQUEST['shopbrandgroup_hide'])?1:0;
				$update_array['shopbrandgroup_hidename']					= ($_REQUEST['shopbrandgroup_hidename'])?1:0;
				$update_array['shopbrandgroup_listtype']					= add_slash($_REQUEST['shopbrandgroup_listtype']);
				$update_array['shopbrandgroup_subshoplisttype']				= add_slash($_REQUEST['shopbrandgroup_subshoplisttype']);
				$update_array['shopbrandgroup_showinall']					= ($_REQUEST['shopbrandgroup_showinall'])?1:0;
				$update_array['product_displaytype']						= add_slash($_REQUEST['product_displaytype']);
				$update_array['shopbrandgroup_product_showimage']			= ($_REQUEST['shopbrandgroup_product_showimage']==1)?1:0;
				$update_array['shopbrandgroup_product_showtitle']			= ($_REQUEST['shopbrandgroup_product_showtitle']==1)?1:0;
				$update_array['shopbrandgroup_product_showshortdescription']= ($_REQUEST['shopbrandgroup_product_showshortdescription']==1)?1:0;
				$update_array['shopbrandgroup_product_showprice']			= ($_REQUEST['shopbrandgroup_product_showprice']==1)?1:0;
				$update_array['shopbrandgroup_display_rotator']				= ($_REQUEST['shopbrandgroup_display_rotator'])?1:0;
				$update_array['shopbrandgroup_activateperiodchange']		= ($_REQUEST['shopbrandgroup_activateperiodchange'])?1:0;
				if ($_REQUEST['shopbrandgroup_activateperiodchange'])
				{
					$exp_shopbrandgroup_displaystartdate					= explode("-",$_REQUEST['shopbrandgroup_displaystartdate']);
					$val_shopbrandgroup_displaystartdate					= $exp_shopbrandgroup_displaystartdate[2]."-".$exp_shopbrandgroup_displaystartdate[1]."-".$exp_shopbrandgroup_displaystartdate[0];
					$exp_shopbrandgroup_displayenddate						= explode("-",$_REQUEST['shopbrandgroup_displayenddate']);
					$val_shopbrandgroup_displayenddate						= $exp_shopbrandgroup_displayenddate[2]."-".$exp_shopbrandgroup_displayenddate[1]."-".$exp_shopbrandgroup_displayenddate[0];
				}
				else
				{
						$val_shopbrandgroup_displaystartdate 				= '0000-00-00';
						$val_shopbrandgroup_displayenddate 					= '0000-00-00';
				}
					
				$val_shopbrandgroup_displaystartdatetime     			=  $val_shopbrandgroup_displaystartdate." ".$_REQUEST['shopbrandgroup_starttime_hr'].":".$_REQUEST['shopbrandgroup_starttime_mn'].":".$_REQUEST['shopbrandgroup_starttime_ss'];
				$val_shopbrandgroup_displayenddatetime  				=  $val_shopbrandgroup_displayenddate." ".$_REQUEST['shopbrandgroup_endtime_hr'].":".$_REQUEST['shopbrandgroup_endtime_mn'].":".$_REQUEST['shopbrandgroup_endtime_ss'];
				$update_array['shopbrandgroup_displaystartdate']		=  $val_shopbrandgroup_displaystartdatetime;
				$update_array['shopbrandgroup_displayenddate']			=  $val_shopbrandgroup_displayenddatetime;
				
				$edit_id 													= $_REQUEST['checkbox'][0];
				$db->update_from_array($update_array,'product_shopbybrand_group',array('shopbrandgroup_id'=>$edit_id));
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_shopbybrand from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shopbybrandgroup'";
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
							$insert_array['display_title']						= add_slash(trim($_REQUEST['shopbrandgroup_name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $edit_id;
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=$edit_id AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				}
                                // case if update the title in display settings is to be done for current combo deal
                                if($_REQUEST['shopbrandgroup_updatewebsitelayout']) 
                                {
                                    // Get the feature id of mod_combo from features table
                                    $sql_feat = "SELECT feature_id 
                                                    FROM 
                                                        features 
                                                    WHERE 
                                                        feature_modulename ='mod_shopbybrandgroup' 
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
                                                        display_title='".trim(add_slash($_REQUEST['shopbrandgroup_name']))."' 
                                                    WHERE 
                                                        sites_site_id = $ecom_siteid 
                                                        AND features_feature_id = $cur_featid 
                                                        AND display_component_id = ".$edit_id;
                                    $db->query($sql_update);
                                }
				// Delete cache
				delete_compshopgroup_cache($edit_id);
				recreate_entire_websitelayout_cache();
				// Completed the section to entry details to display_settings table
				$alert .= '<br><span class="redtext"><b>Product Shop Menu Updated successfully</b></span><br>';
				echo $alert;
			?>
				<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=add&shopgroupname=<?=$_REQUEST['shopgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>
				<?
				
			}
			else
			{
				$ajax_return_function 	= 'ajax_return_contents';
				include "ajax/ajax.php";
				$edit_id 				= $_REQUEST['checkbox'][0];
				include ('includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
				include ('includes/shopbybrandgroup/edit_shopbybrandgroup.php');
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
			$alert = 'Sorry Product Shop Menu not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			// Find the feature details for module mod_productcatgroup from features table
			$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename ='mod_shopbybrandgroup'";
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
					$sql_check = "SELECT count(*) FROM product_shopbybrand_group_shop_map psm,product_shopbybrand ps WHERE psm.product_shopbybrand_shopbrandgroup_id=".$del_arr[$i]." AND psm.product_shopbybrand_shopbrand_id=ps.shopbrand_id";
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{
					    ++$deleted_cnt;
						$sql_del_cat = "DELETE FROM product_shopbybrand_group_display_category 
											WHERE product_shopbybrand_group_shopbrandgroup_id=".$del_arr[$i];
						$db->query($sql_del_cat);
						
						$sql_del_pdt = "DELETE FROM product_shopbybrand_group_display_products 
											WHERE product_shopbybrand_group_shopbrandgroup_id=".$del_arr[$i];
						$db->query($sql_del_pdt);
						
						$sql_del_stat = "DELETE FROM product_shopbybrand_group_display_staticpages 
											WHERE product_shopbybrand_group_shopbrandgroup_id=".$del_arr[$i];
						$db->query($sql_del_stat);
						
						$sql_del_shop = "DELETE FROM product_shopbybrand_group_shop_map 
											WHERE product_shopbybrand_shopbrandgroup_id=".$del_arr[$i];
						$db->query($sql_del_shop);
											
						
						$sql_del = "DELETE FROM product_shopbybrand_group WHERE shopbrandgroup_id=".$del_arr[$i];
						$db->query($sql_del);
						// Delete all those entries from display setting corresponding to current category group 
						$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
									features_feature_id=$cur_featid AND display_component_id=".$del_arr[$i];
						$ret_del = $db->query($sql_del);
						/*if($alert) $alert .="<br />";
						$alert .= "Product Shopbybrand Group with ID -".$del_arr[$i]." Deleted";*/
						// Delete cache
						delete_compshopgroup_cache($del_arr[$i]);
					}
					else
					{
						$cant_delete[] =  $del_arr[$i];
						/*if($alert) $alert .="<br />";
						$alert .= "Sorry!! Shop Assigned to group -".$del_arr[$i];*/
					}	
				}	
			}
			if(count($cant_delete))  {
				$cant_deleteId = implode(",",$cant_delete);
					if($alert) 
					$alert .="<br />";
					$alert .= "Sorry !!-Cannot Delete Product Shop Menu Id(s)-$cant_deleteId - Some Shops are already assigned to the Menu";
				}
				if($deleted_cnt)  {
					if($alert) 
					$alert .="<br />";
					$alert .= " $deleted_cnt Product Shop Menu Deleted Sucessfully";
				}
			recreate_entire_websitelayout_cache();
		}	
		include ('../includes/shopbybrandgroup/list_shopbybrandgroup.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 	= explode('~',$_REQUEST['catids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$update_array							= array();
			$update_array['shopbrandgroup_hide']	= $new_status;
			$cur_id 								= $shopid_arr[$i];	
			$db->update_from_array($update_array,'product_shopbybrand_group',array('shopbrandgroup_id'=>$cur_id));
			// Delete cache
			delete_compshopgroup_cache($cur_id);
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/shopbybrandgroup/list_shopbybrandgroup.php');
		
	}
	elseif($_REQUEST['fpurpose'] =='list_shopgroup_maininfo')// Case of listing main info for shop groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_shopbybrandgroup_maininfo($_REQUEST['group_id']);
	}
	elseif($_REQUEST['fpurpose'] =='list_displaycategory_group')// Case of listing diaplay categories to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplaycategory_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_displayproduct_group')// Case of listing diaplay products to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplayproduct_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_displaystatic_group')// Case of listing diaplay pages to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplaystatic_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_shopgroup')// Case of listing shops to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_shop_group_list($_REQUEST['group_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_subshops')// Case of listing subshop
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_subshop_list($_REQUEST['shop_id']);
	}
	else if($_REQUEST['fpurpose']=='unassignsubshop') // Case of unassigning sub shops
	{
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "UPDATE product_shopbybrand SET shopbrandgroup_parent_id =0 WHERE shopbrandgroup_id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Sub shop (s) unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_subshop_list($shopid);
	}
	/*elseif($_REQUEST['fpurpose'] =='list_displaycategorygroup')// Case of listing diaplay categories to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplaycategory_group_list($_REQUEST['shopgroup_id']);
	}
	elseif($_REQUEST['fpurpose'] =='list_displayproductgroup')// Case of listing diaplay products to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplayproduct_group_list($_REQUEST['shopgroup_id']);
	}*/
	else if($_REQUEST['fpurpose']=='categoryGroupAssign') // Case of selecting categgories to groups
	{
		include ('includes/shopbybrandgroup/list_category_shopbybrandgroup.php');
	}
	/*elseif($_REQUEST['fpurpose'] =='list_displaystaticgroup')// Case of listing diaplay pages to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_diplaystatic_group_list($_REQUEST['shopgroup_id']);
	}*/
    else if($_REQUEST['fpurpose']=='staticGroupAssign') // Case of selecting pages to groups
	{
		
		include ('includes/shopbybrandgroup/list_static_shopbybrandgroup.php');
	}
	else if($_REQUEST['fpurpose']=='save_staticGroupAssign') // Case of saving pages to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check the page is already assigned
			$sql_check = "SELECT id FROM product_shopbybrand_group_display_staticpages WHERE product_shopbybrand_group_shopbrandgroup_id=".
							$_REQUEST['pass_shopgroup_id']." AND static_pages_page_id=$v LIMIT 1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$insert_array=array();
				$insert_array['sites_site_id']									= $ecom_siteid;
				$insert_array['product_shopbybrand_group_shopbrandgroup_id']	= $_REQUEST['pass_shopgroup_id'];
				$insert_array['static_pages_page_id']							= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_group_display_staticpages');
			}			
		}
		$alert='Static Page Assigned Successfullly to Shop Menu';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shopgroup_id']?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=statmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop Menu</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='prodGroupAssign') // Case of selecting products to groups
	{
		include ('includes/shopbybrandgroup/list_product_shopbybrandgroup_selproduct.php');
	}
	else if($_REQUEST['fpurpose']=='save_categoryGroupAssign') // Case of selecting categories to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the category is already assigned 
			$sql_check = "SELECT id FROM product_shopbybrand_group_display_category WHERE product_shopbybrand_group_shopbrandgroup_id=".
			$_REQUEST['pass_shopgroup_id']." AND product_categories_category_id=$v LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array													= array();
				$insert_array['sites_site_id']									= $ecom_siteid;
				$insert_array['product_shopbybrand_group_shopbrandgroup_id']	= $_REQUEST['pass_shopgroup_id'];
				$insert_array['product_categories_category_id']					= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_group_display_category');
			}	
		}
		$alert='Category Assigned Successfullly to Shop Menu';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shopgroup_id']?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Product Shop Menu</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='save_prodGroupAssign') // Case of saving products to Shop by brand group
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the product is already assigned
			$sql_check = "SELECT id FROM product_shopbybrand_group_display_products WHERE sites_site_id=$ecom_siteid AND 
							product_shopbybrand_group_shopbrandgroup_id=".$_REQUEST['pass_shopgroup_id']." AND 
							products_product_id=$v";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array													= array();
				$insert_array['sites_site_id']									= $ecom_siteid;
				$insert_array['product_shopbybrand_group_shopbrandgroup_id']	= $_REQUEST['pass_shopgroup_id'];
				$insert_array['products_product_id']							= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_group_display_products');
			}			
		}
		$alert='Product Assigned Successfullly to Shop Menu';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shopgroup_id']?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop Menu</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='staticGroupUnAssign') // Case of unassigning categories to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 			= explode('~',$_REQUEST['del_ids']);
		$shopgroup_id		= $_REQUEST['shopgroup_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_group_display_staticpages WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Static Page unassigned successfully.';
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
	    show_diplaystatic_group_list($shopgroup_id,$alert);
	}
	else if($_REQUEST['fpurpose']=='categoryGroupUnAssign') // Case of unassigning categories from product shop
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 		= explode('~',$_REQUEST['del_ids']);
		
		$shopgroupid	= $_REQUEST['shopgroup_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_group_display_category WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Category unassigned successfully.';
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
	    show_diplaycategory_group_list($shopgroupid,$alert);
	}
	else if($_REQUEST['fpurpose']=='prodGroupUnAssign') // Case of unassigning products to categories
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 		= explode('~',$_REQUEST['del_ids']);
		$shopgroupid	= $_REQUEST['shopgroup_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_group_display_products WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Product unassigned successfully.';
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
	    show_diplayproduct_group_list($shopgroupid,$alert);
	}
	elseif ($_REQUEST['fpurpose']=='list_shops')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
		show_shop_list($_REQUEST['shopgroup_id']);	
	}
	else if($_REQUEST['fpurpose']=='shop_sel') // Case of showing shops to be assigned
	{
		include ('includes/shopbybrandgroup/list_shopbybrandgroup_selshop.php');
	}
	else if($_REQUEST['fpurpose']=='save_shop_sel') // Case of mapping selected shops to curret group
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the shop is already assigned
			$sql_check = "SELECT id FROM product_shopbybrand_group_shop_map 
							WHERE product_shopbybrand_shopbrandgroup_id=".$_REQUEST['pass_shopgroup_id']." AND 
							product_shopbybrand_shopbrand_id=$v";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array											= array();
				$insert_array['product_shopbybrand_shopbrandgroup_id']	= $_REQUEST['pass_shopgroup_id'];
				$insert_array['product_shopbybrand_shopbrand_id']		= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_group_shop_map');
			}			
		}
		// Delete cache
		delete_compshopgroup_cache($_REQUEST['pass_shopgroup_id']);
		$alert='Shops Assigned Successfullly to Shop Menu';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrandgroup&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrandgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shopgroup_id']?>&shopgroupname=<?=$_REQUEST['pass_shopgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop Menu</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='unassignshop') // Case of unassigning shops from group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 		= explode('~',$_REQUEST['del_ids']);
		$shopgroupid	= $_REQUEST['shopgroup_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE id=$id";
			$db->query($sql_del);
			
		}
		// Delete cache
		delete_compshopgroup_cache($_REQUEST['shopgroup_id']);
		$alert = 'Product Shops unassigned successfully.';

		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
	    show_shop_group_list($shopgroupid,$alert);
	}
	else if($_REQUEST['fpurpose']=='save_shoporder') // Case of saving order for shops in group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$order_arr		= explode('~',$_REQUEST['ch_order']);		
		$id_arr 		= explode('~',$_REQUEST['ch_ids']);
		$shopgroupid	= $_REQUEST['shopgroup_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id 						= $id_arr[$i];	
			$update_array				= array();
			$update_array['shop_order']	= $order_arr[$i];
			$db->update_from_array($update_array,'product_shopbybrand_group_shop_map',array('id'=>$id));
		}
		// Delete cache
		delete_compshopgroup_cache($_REQUEST['shopgroup_id']);
		$alert = 'Order Saved successfully.';
		include ('../includes/shopbybrandgroup/ajax/shopbybrandgroup_ajax_functions.php');
	    show_shop_group_list($shopgroupid,$alert);
	}
?>