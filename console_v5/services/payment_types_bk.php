<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/payment_types/list_payment_types.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		include ('includes/product_category_groups/add_product_category_groups.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
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
									catgroup_name='".add_slash($_REQUEST['catgroup_name'])."' AND sites_site_id=$ecom_siteid";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Group name already exists";
			}
			if ($alert=='')
			{
				
				$insert_array									= array();
				$insert_array['sites_site_id']					= $ecom_siteid;
				$insert_array['catgroup_name']					= add_slash(trim($_REQUEST['catgroup_name']));
				$insert_array['catgroup_hide']					= ($_REQUEST['catgroup_hide'])?1:0;
				$insert_array['catgroup_hidename']				= ($_REQUEST['catgroup_hidename'])?1:0;
				$insert_array['catgroup_listtype']				= add_slash($_REQUEST['catgroup_listtype']);
				$insert_array['catgroup_subcatlisttype']		= add_slash($_REQUEST['catgroup_subcatlisttype']);
				$insert_array['catgroup_showinall']				= ($_REQUEST['catgroup_showinall'])?1:0;
				$insert_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
				$insert_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
				$insert_array['catgroup_showimage']				= ($_REQUEST['catgroup_showimage']==1)?1:0;
				$insert_array['catgroup_showtitle']				= ($_REQUEST['catgroup_showtitle']==1)?1:0;
				$insert_array['catgroup_showshortdescription']	= ($_REQUEST['catgroup_showshortdescription']==1)?1:0;
				$insert_array['catgroup_showprice']				= ($_REQUEST['catgroup_showprice']==1)?1:0;
				$insert_array['catgroup_showonlyforcustgroup']	= ($_REQUEST['catgroup_showonlyforcustgroup']==1)?1:0;
				$db->insert_from_array($insert_array,'product_categorygroup');
				$insert_id = $db->insert_id();
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename ='mod_productcatgroup'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
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
				// Completed the section to entry details to display_settings table
				
				$alert .= '<br><span class="redtext"><b>Product Category Group added successfully</b></span><br>';
				echo $alert;
			?>
				<br /><a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit the Page</a><br /><br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New the Page</a>
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
									AND catgroup_id NOT IN (".$checkbox[0].")";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Group name already exists";
			}
			if ($alert=='')
			{
				
				$update_array									= array();
				$update_array['sites_site_id']					= $ecom_siteid;
				$update_array['catgroup_name']					= add_slash(trim($_REQUEST['catgroup_name']));
				$update_array['catgroup_hide']					= ($_REQUEST['catgroup_hide'])?1:0;
				$update_array['catgroup_hidename']				= ($_REQUEST['catgroup_hidename'])?1:0;
				$update_array['catgroup_listtype']				= add_slash($_REQUEST['catgroup_listtype']);
				$update_array['catgroup_subcatlisttype']		= add_slash($_REQUEST['catgroup_subcatlisttype']);
				$update_array['catgroup_showinall']				= ($_REQUEST['catgroup_showinall'])?1:0;
				$update_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
				$update_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
				$update_array['catgroup_showimage']				= ($_REQUEST['catgroup_showimage']==1)?1:0;
				$update_array['catgroup_showtitle']				= ($_REQUEST['catgroup_showtitle']==1)?1:0;
				$update_array['catgroup_showshortdescription']	= ($_REQUEST['catgroup_showshortdescription']==1)?1:0;
				$update_array['catgroup_showprice']				= ($_REQUEST['catgroup_showprice']==1)?1:0;
				$update_array['catgroup_showonlyforcustgroup']	= ($_REQUEST['catgroup_showonlyforcustgroup']==1)?1:0;
				$edit_id 										= $_REQUEST['checkbox'][0];
				$db->update_from_array($update_array,'product_categorygroup',array('catgroup_id'=>$edit_id));
				
				// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename ='mod_productcatgroup'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
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
							$layoutid		= $cur_arr[1];
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
						else
						{
							$update_array						= array();
							$update_array['display_title']		= add_slash(trim($_REQUEST['catgroup_name']));
							$db->update_from_array($update_array,'display_settings',array('display_id'=>$dispid));
						}
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=$edit_id AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				}
				// Completed the section to entry details to display_settings table
				$alert .= '<br><span class="redtext"><b>Product Category Group Updated successfully</b></span><br>';
				echo $alert;
			?>
				<br /><a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?=$edit_id?>&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit the Page</a><br /><br />
				<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=add&catgroupname=<?=$_REQUEST['catgroupname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New the Page</a>
				<?
				
			}
			else
			{
				include ('includes/product_category_groups/edit_product_category_groups.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($del_ids == '')
		{
			$alert = 'Sorry Product Category Group not selected';
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
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether categories exists under this category group
					$sql_check = "SELECT count(*) FROM product_categorygroup_category WHERE catgroup_id=".$del_arr[$i];
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{
						$sql_del = "DELETE FROM product_categorygroup WHERE catgroup_id=".$del_arr[$i];
						$db->query($sql_del);
						// Delete all those entries from display setting corresponding to current category group 
						$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
									features_feature_id=$cur_featid AND display_component_id=".$del_arr[$i];
						$ret_del = $db->query($sql_del);
						if($alert) $alert .="<br />";
						$alert .= "Product Category Group with ID -".$del_arr[$i]." Deleted";
					}
					else
					{
						if($alert) $alert .="<br />";
						$alert .= "Sorry!! Category Assigned to group -".$del_arr[$i];
					}	
				}	
			}
		}	
		include ('../includes/product_category_groups/list_product_category_groups.php');
	}
	elseif($fpurpose=='change_hide')
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
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/product_category_groups/list_product_category_groups.php');
		
	}
	elseif($fpurpose =='assign_sel')
	{
		include ('includes/product_category_groups/list_product_catgroup_selcategory.php');
	}
	elseif($fpurpose =='assign_sel_save')
	{
		$selcat_arr = explode("~",$_REQUEST['sel_cats']);
		if (count($selcat_arr))
		{
			for($i=0;$i<count($selcat_arr);$i++)
			{
				$insert_array					= array();
				$insert_array['catgroup_id']	= $_REQUEST['pass_groupid'];
				$insert_array['category_id']	= $selcat_arr[$i];
				$insert_array['category_order']	= 0;
				
				$db->insert_from_array($insert_array,'product_categorygroup_category');
			}				
		}
		$alert = 'Product Categories Assigned successfully';
		?>
		<script>
		document.location = "home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_groupid']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&alert=<?php echo $alert?>";
		</script>
	<?php		
	}
	elseif($fpurpose =='unassigncat')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['catids']);
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id 						= $catid_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id=$cur_id AND catgroup_id=$groupid";
			$db->query($sql_del);
			
		}
		$alert = 'Category unassigned successfully.';
		$edit_id = $groupid;
		include ('../includes/product_category_groups/edit_product_category_groups.php');
	}
?>