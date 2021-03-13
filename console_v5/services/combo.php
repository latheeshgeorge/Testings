<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/combo/list_combo.php");
}
else if($_REQUEST['fpurpose']=='delete') // Delete combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Combo not selected';
	}
	else
	{
		$del_arr = explode("~",$_REQUEST['del_ids']);
		for($i=0;$i<count($del_arr);$i++)
		{
			if(trim($del_arr[$i]))
			{
				  $sql_del = "DELETE FROM combo_display_category WHERE combo_id=".$del_arr[$i];
				  $db->query($sql_del);
				  $sql_del = "DELETE FROM combo_display_product WHERE combo_id=".$del_arr[$i];
				  $db->query($sql_del);
				  $sql_del = "DELETE FROM combo_display_static WHERE combo_id=".$del_arr[$i];
				  $db->query($sql_del);
				  $sql_del = "DELETE FROM combo_products WHERE combo_combo_id=".$del_arr[$i];
				  $db->query($sql_del);
				  
				  $sql_sel = "SELECT comboprod_id 
									FROM 
										combo_products 
									WHERE 
										combo_combo_id = ".$del_arr[$i];
				  $ret_sel = $db->query($sql_sel);
				  if ($db->num_rows($ret_sel))
				  {
					while ($row_sel = $db->fetch_array($ret_sel))
					{
						$sql_del = "DELETE FROM combo_products_variable_combination WHERE combo_products_comboprod_id=".$row_sel['comboprod_id'];
						$db->query($sql_del);
						$sql_del = "DELETE FROM combo_products_variable_combination_map WHERE combo_products_variable_combination_comb_id=".$row_sel['comboprod_id'];
						$db->query($sql_del);
					}
				  }
				  $sql_del = "DELETE FROM combo WHERE combo_id=".$del_arr[$i];
				  $db->query($sql_del);
									
				  // Find the feature_id for mod_combo module from features table
				  $sql_del_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_combo'";
				  $del_ret_feat = $db->query($sql_del_feat);
				  if ($db->num_rows($del_ret_feat))
				  {
					$del_row_feat 	= $db->fetch_array($del_ret_feat);
					$del_feat_id	= $del_row_feat['feature_id'];
				   }
				  $sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND features_feature_id=$del_feat_id AND display_component_id=".$del_arr[$i];
				  $db->query($sql_del);
				  
				  // Delete Cache
				  delete_combo_cache($del_arr[$i]);					
				  
				  if($alert) $alert .="<br />";
			}	
		}
		$alert .=  "Combo Deal(s) Deleted";
		recreate_entire_websitelayout_cache();
	}
	include ('../includes/combo/list_combo.php');
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update combo status
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$combo_ids_arr 	= explode('~',$_REQUEST['combo_ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($combo_ids_arr);$i++)
	{
	  if($new_status==1)
	  {
		  $sql_prod = "SELECT 
								DISTINCT products_product_id 
						FROM
								combo_products
						WHERE
							   combo_combo_id=".$combo_ids_arr[$i]."
						AND
								sites_site_id=$ecom_siteid";
		 $ret_prod = $db->query($sql_prod);	
		 if($db->num_rows($ret_prod)<2)
		 {
		  $flag = 'No';
		  $flag_alert = 'No';
		 }
		 else
		  $flag = 'Yes';
	 }
	 else
	 {
	  	$flag = 'Yes';
	 }					
		 if( $flag=='Yes')
		 {
			$update_array					= array();
			$update_array['combo_active']	= $new_status;
			$combo_id 						= $combo_ids_arr[$i];	
			$db->update_from_array($update_array,'combo',array('combo_id'=>$combo_id));
			// Delete Cache
			delete_combo_cache($combo_id );
		 }		
	}
	$alert = 'Hidden Status changed successfully.';
	if($flag_alert=='No')
	{
		$alert .="<br>Hidden Status of some of the Combo Deal(s) have not changed to 'No' since the number of products assigned to them is less than two.";
	}
	include ('../includes/combo/list_combo.php');
}
else if($_REQUEST['fpurpose']=='add') // New Combo
{
	include("includes/combo/add_combo.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit shelf
{
	include_once("functions/console_urls.php");
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/combo/ajax/combo_ajax_functions.php');
	include("includes/combo/edit_combo.php");
	
}
else if($_REQUEST['fpurpose']=='insert') // Save new shelf
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['combo_name']);
		$fieldDescription = array('Combo Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM combo WHERE combo_name = '".trim(add_slash($_REQUEST['combo_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Combo Name Already exists '; 
		if(!$alert) 
		{
			$insert_array 						= array();
			$insert_array['combo_name']			=trim(add_slash($_REQUEST['combo_name']));
			$insert_array['combo_description']	=trim(add_slash($_REQUEST['combo_description'],false));
			$insert_array['sites_site_id']		=$ecom_siteid;
			$insert_array['combo_active']		= 0;//$_REQUEST['combo_active'];
			$insert_array['combo_showinall']	=$_REQUEST['combo_showinall'];
			$insert_array['combo_hidename']		= ($_REQUEST['comb_hide'])?1:0;
			$insert_array['combo_apply_direct_discount_also']			= ($_REQUEST['combo_apply_direct_discount_also'])?'Y':'N';
			$insert_array['combo_apply_custgroup_discount_also']		= ($_REQUEST['combo_apply_custgroup_discount_also'])?'Y':'N';
			$insert_array['combo_activateperiodchange']=$_REQUEST['combo_activateperiodchange'];
			$exp_combo_displaystartdate			=explode("-",$_REQUEST['combo_displaystartdate']);
			$val_combo_displaystartdate			=$exp_combo_displaystartdate[2]."-".$exp_combo_displaystartdate[1]."-".$exp_combo_displaystartdate[0];
			$exp_combo_displayenddate			=explode("-",$_REQUEST['combo_displayenddate']);
			$val_combo_displayenddate			=$exp_combo_displayenddate[2]."-".$exp_combo_displayenddate[1]."-".$exp_combo_displayenddate[0];
			
			$val_combo_displaystartdatetime     		=  $val_combo_displaystartdate." ".$_REQUEST['combo_starttime_hr'].":".$_REQUEST['combo_starttime_mn'].":".$_REQUEST['combo_starttime_ss'];
			$val_combo_displayenddatetime  				=  $val_combo_displayenddate." ".$_REQUEST['combo_endtime_hr'].":".$_REQUEST['combo_endtime_mn'].":".$_REQUEST['combo_endtime_ss'];
			$insert_array['combo_displaystartdate']		=  $val_combo_displaystartdatetime;
			$insert_array['combo_displayenddate']		=  $val_combo_displayenddatetime;
			$db->insert_from_array($insert_array, 'combo');
			$insert_id = $db->insert_id();
			// Section to make entry to display_settings table
			if(count($_REQUEST['display_id']))
			{
				// Find the feature details for module mod_combo from features table
				$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_combo'";
				$ret_feat = $db->query($sql_feat);
				if ($db->num_rows($ret_feat))
				{
					$row_feat 	= $db->fetch_array($ret_feat);
					$cur_featid	= $row_feat['feature_id'];
				}
				// checking wheter the feature is added in the site menu table 
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
					if(count($cur_arr)==3)
					{
						$layoutid	= $cur_arr[0];
						$layoutcode	= $cur_arr[1];
						$position	= $cur_arr[2];
					}
					else
					{	
						$layoutid	= $cur_arr[0];
						//$layoutcode	= $cur_arr[1].'_'.$cur_arr[2];
						$layoutcode = '';
						$position	= $cur_arr[count($cur_arr)-1];
						for($vv = 1;$vv<(count($cur_arr)-1);$vv++)
						{
								if($layoutcode!='')
									$layoutcode .='_';
								$layoutcode .= $cur_arr[$vv];
						}
					}	
					$insert_array										= array();
					$insert_array['sites_site_id']						= $ecom_siteid;
					$insert_array['features_feature_id']				= $cur_featid;
					$insert_array['display_position']					= $position;
					$insert_array['themes_layouts_layout_id']			= $layoutid;
					$insert_array['layout_code']						= add_slash($layoutcode);
					$insert_array['display_title']						= add_slash(trim($_REQUEST['combo_name']));
					$insert_array['display_order']						= 0;
					$insert_array['display_component_id']				= $insert_id;
					
					$db->insert_from_array($insert_array,'display_settings');
				}
			}
				// Completed the section to entry details to display_settings table
			recreate_entire_websitelayout_cache();	
			$alert .= '<br><span class="redtext"><b>Combo added successfully</b><br>This Combo deal is <strong>INACTIVE</strong> now. It should be activated once products are set from the edit page.<br></span>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=combo&fpurpose=edit&combo_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/combo/add_combo.php");
		}
	}
}
else if($_REQUEST['fpurpose']=='update') // Update combo
{
	
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired 		= array($_REQUEST['combo_name']);
		$fieldDescription 	= array('Combo Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM combo WHERE combo_name = '".trim(add_slash($_REQUEST['combo_name']))."' AND sites_site_id=$ecom_siteid AND combo_id<>".$_REQUEST['combo_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Combo Name Already exists '; 
		if(!$alert) {
		
			$update_array = array();
			$update_array['combo_name']			= trim(add_slash($_REQUEST['combo_name']));
			$update_array['combo_description']	= trim(add_slash($_REQUEST['combo_description'],$strip_tags=false));
			$update_array['sites_site_id']		= $ecom_siteid;
			$update_array['combo_showinall']	= $_REQUEST['combo_showinall'];
			$update_array['combo_hidename']		= ($_REQUEST['comb_hide'])?1:0;
			$update_array['combo_apply_direct_discount_also']			= ($_REQUEST['combo_apply_direct_discount_also'])?'Y':'N';
			$update_array['combo_apply_custgroup_discount_also']		= ($_REQUEST['combo_apply_custgroup_discount_also'])?'Y':'N';
			$update_array['combo_activateperiodchange']=$_REQUEST['combo_activateperiodchange'];
			$exp_combo_displaystartdate			=explode("-",$_REQUEST['combo_displaystartdate']);
			$val_combo_displaystartdate			=$exp_combo_displaystartdate[2]."-".$exp_combo_displaystartdate[1]."-".$exp_combo_displaystartdate[0];
			$exp_combo_displayenddate			=explode("-",$_REQUEST['combo_displayenddate']);
			$val_combo_displayenddate			=$exp_combo_displayenddate[2]."-".$exp_combo_displayenddate[1]."-".$exp_combo_displayenddate[0];
			 if($_REQUEST['combo_activateperiodchange']==0){
			$val_combo_displaystartdate			="0000-00-00";
			$val_combo_displayenddate 			="0000-00-00";
			}
			$val_combo_displaystartdatetime     		=  $val_combo_displaystartdate." ".$_REQUEST['combo_starttime_hr'].":".$_REQUEST['combo_starttime_mn'].":".$_REQUEST['combo_starttime_ss'];
			$val_combo_displayenddatetime  				=  $val_combo_displayenddate." ".$_REQUEST['combo_endtime_hr'].":".$_REQUEST['combo_endtime_mn'].":".$_REQUEST['combo_endtime_ss'];
			$update_array['combo_displaystartdate']		=  $val_combo_displaystartdatetime;
			$update_array['combo_displayenddate']		=  $val_combo_displayenddatetime;
			$db->update_from_array($update_array, 'combo', 'combo_id', $_REQUEST['combo_id']);
			
			// Section to make entry to display_settings table
			if(count($_REQUEST['display_id']))
			{
				
					// Find the feature details for module mod_combo from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_combo'";
					$ret_feat = $db->query($sql_feat);
					if ($db->num_rows($ret_feat))
					{
						$row_feat 	= $db->fetch_array($ret_feat);
						$cur_featid	= $row_feat['feature_id'];
					}
					// checking wheter the feature is added in the site menu table 
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
										display_component_id=".$_REQUEST['combo_id'];
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0 or $dispid==0)
						{
							if(count($cur_arr)==4)
							{
								$layoutid		= $cur_arr[1];
								$layoutcode		= $cur_arr[2];
								$position		= $cur_arr[3];
							}	
							else
							{	
								$layoutid	= $cur_arr[1];
								$layoutcode = '';
								$position	= $cur_arr[count($cur_arr)-1];
								for($vv = 2;$vv<(count($cur_arr)-1);$vv++)
								{
										if($layoutcode!='')
											$layoutcode .='_';
										$layoutcode .= $cur_arr[$vv];
								}
							}
							
							$insert_array										= array();
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['features_feature_id']				= $cur_featid;
							$insert_array['display_position']					= $position;
							$insert_array['themes_layouts_layout_id']			= $layoutid;
							$insert_array['layout_code']						= add_slash($layoutcode);
							$insert_array['display_title']						= add_slash(trim($_REQUEST['combo_name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $_REQUEST['combo_id'];
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=".$_REQUEST['combo_id']." AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				
			}
                        // case if update the title in display settings is to be done for current combo deal
                        if($_REQUEST['combo_updatewebsitelayout']) 
                        {
                            // Get the feature id of mod_combo from features table
                            $sql_feat = "SELECT feature_id 
                                            FROM 
                                                features 
                                            WHERE 
                                                feature_modulename ='mod_combo' 
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
                                                display_title='".trim(add_slash($_REQUEST['combo_name']))."' 
                                            WHERE 
                                                sites_site_id = $ecom_siteid 
                                                AND features_feature_id = $cur_featid 
                                                AND display_component_id = ".$_REQUEST['combo_id'];
                            $db->query($sql_update);
                        }
                        
                        /*if($_REQUEST['advert_updatewebsitelayout']) 
                        {
                            clear_all_cache();// Clearing all cache
                        }
                        else
                        { */
                            delete_combo_cache($_REQUEST['combo_id']);
                        /*}*/
						recreate_entire_websitelayout_cache();
			$alert .= '<br><b><span class="redtext">Combo Updated successfully</span></b><br>';
			if($flag_alert =='No')
			$alert .= '<br><b><span class="redtext">This combo deal is made hidden since it have less than 2 products assigned to it.</span></b><br>';
			echo $alert;
			?>
		
			<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=combo&fpurpose=edit&combo_id=<?=$_REQUEST['combo_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center>Error! '.$alert;
			$alert .= '</center>';
		?>
			<br />
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/combo/ajax/combo_ajax_functions.php');
			include("includes/combo/edit_combo.php");
		}
	}
}
elseif($_REQUEST['fpurpose'] =='list_combo_maininfo')// Case of listing main info for category groups
{
	include_once("functions/console_urls.php");
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/combo/ajax/combo_ajax_functions.php');
	include("includes/combo/edit_combo.php");
}
else if($_REQUEST['fpurpose']=='list_productcombo') // Listing products assigned to shelf
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='prodComboAssign') // Assign products to Combo
{
	include ('includes/combo/list_combo_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_prodComboAssign') // Save products to combo
{			
	// Calling function to get the list of products already assigned to current combo
	$existing_pdts_ids = get_existing_combo_prods($_REQUEST['pass_combo_id']);
	foreach($_REQUEST['checkbox'] as $v)
	{	
		if(!in_array($v,$existing_pdts_ids))
		{
			$insert_array=array();
			$insert_array['combo_combo_id']			= $_REQUEST['pass_combo_id'];
			$insert_array['products_product_id']	= $v;
			$insert_array['sites_site_id']			= $ecom_siteid;
			$db->insert_from_array($insert_array, 'combo_products');
			$cur_map_prod_id = $db->insert_id();
		}
	}
	$sql_product_num					= "SELECT count(*) as cnt FROM combo_products WHERE combo_combo_id=".$_REQUEST['pass_combo_id'];
	$ret_product_num					= $db->query($sql_product_num);
	$res_product_num					= $db->fetch_array($ret_product_num);
	$num_products						= $res_product_num['cnt'];
	$update_array						= array();
	$update_array['combo_totproducts']	= $num_products;
	$update_array['combo_active']		= 0; // When new products are assigned to combo, it will be deactivated automatically.

	$db->update_from_array($update_array,'combo','combo_id',$_REQUEST['pass_combo_id']); // updating the combo table for total products in a combo
	// Delete cache 
	delete_combo_cache($_REQUEST['pass_combo_id']);
	if(!$alert)
	{
		$alert='Product Assigned Successfullly. Combo has been deactivated. <br>Please set the price for the newly added product(s) and reactivate it.';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;
?>
		<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Combo Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_combo_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=products_tab_td" onclick="show_processing()">Go Back to the Edit  this Combo</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
<?		
	}
	else
	{
		include ('includes/combo/list_combo_selproduct.php');
	}
}
elseif($_REQUEST['fpurpose']=='prodComboUnAssign') //Un assign products from combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	
	$id_arr 			= explode('~',$_REQUEST['del_ids']);
	$products_to_remove	=	array();	
	$comboid			= $_REQUEST['combo_id'];
	
	for($i=0;$i<count($id_arr);$i++)
	{
		$id = $id_arr[$i];	
		// get the combination ids to delete items from combo_products_variable_combination table
		$sql_sel = "SELECT comb_id 
						FROM 
							combo_products_variable_combination 
						WHERE 
							combo_products_comboprod_id =$id";
		$ret_sel = $db->query($sql_sel);
		if($db->num_rows($ret_sel))
		{
			while ($row_sel = $db->fetch_array($ret_sel))
			{
				// deleting from combo_products_variable_combination_map 
				$sql_del = "DELETE FROM 
								combo_products_variable_combination_map 
							WHERE 
								combo_products_variable_combination_comb_id = ".$row_sel['comb_id'];
				$db->query($sql_del);
			}
		}
							
		
		$sql_del = "DELETE FROM combo_products_variable_combination WHERE combo_products_comboprod_id=$id";
		$db->query($sql_del);
		$sql_del = "DELETE FROM combo_products WHERE comboprod_id=$id";
		$db->query($sql_del);
	}
	if(!$alert)
	{
		$sql_product_num					= "SELECT count(*) as cnt FROM combo_products WHERE combo_combo_id=".$_REQUEST['combo_id'];
		$ret_product_num					= $db->query($sql_product_num);
		$res_product_num					= $db->fetch_array($ret_product_num);
		$num_products						= $res_product_num['cnt'];
		$update_array						= array();
		$update_array['combo_totproducts']	= $num_products;
		$db->update_from_array($update_array, 'combo', 'combo_id', $_REQUEST['combo_id']); // updating the combo table for total products in a combo
	// Delete cache 
		if($num_products<2)
		{
			$update_array					= array();
			$update_array['combo_active']	= 0;
			$db->update_from_array($update_array,'combo',array('combo_id'=>$comboid));
			$flag_alert = 'No';
			// Delete Cache
		}
		delete_combo_cache($_REQUEST['combo_id']);
		$alert = 'Product unassigned successfully.';
		if($flag_alert=='No')
		{
			$alert .= "<br>Status for This Combo Changed to 'Inactive' since number of assigned products is less than two.";
		}
		else
		{
			$alert_duplicate = check_same_combo_exist($_REQUEST['combo_id']);
			if ($alert_duplicate)
			{
				$update_array					= array();
				$update_array['combo_active']	= 0;
				$db->update_from_array($update_array,'combo',array('combo_id'=>$comboid));
				$alert .= '<br>Combo Deal Deactivated since there exists other combo deal(s) with the same set of products';
			}	
		}
	}
	set_combo_bundle_price($_REQUEST['combo_id']);
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='save_order') // Products order within a combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr			= explode('~',$_REQUEST['ch_ids']);
	$OrderArr		= explode('~',$_REQUEST['ch_order']);
	$varprod_arr 	= explode('~',$_REQUEST['varprod_ids']);
	$var_arr 		= explode('~',$_REQUEST['var_ids']);
	$varvalue_arr 	= explode('~',$_REQUEST['varval_ids']);
	$varprod_update_arr = array();
	$err_msgs = '';
	for($i=0;$i<count($varprod_arr);$i++)
	{
		$temp_arr								= explode('_',$varprod_arr[$i]);
		$cur_prod 								= $temp_arr[0];
		if($temp_arr[2])
			$varprod_update_arr[$cur_prod][]		= array($temp_arr[1]=>$temp_arr[2]);
	}	
	if(count($varprod_update_arr)>0)
	{
		foreach ($varprod_update_arr as $k=>$v)
		{
			$cur_combo_id 	= 0;
			$curprodmapid	= trim($k);
			if($curprodmapid)
			{
				$exists = false;
				// Get all the combinations existing for current product mapping
				$sql_comb = "SELECT comb_id 
								FROM 
									combo_products_variable_combination 
								WHERE 
									combo_products_comboprod_id = $curprodmapid";
				$ret_comb = $db->query($sql_comb);
				if($db->num_rows($ret_comb)) // if combination exists for currently mapped products
				{
					while ($row_comb = $db->fetch_array($ret_comb))
					{	
						$comb_already_exists = combination_already_exists($row_comb['comb_id'],$v);
						if($comb_already_exists)
						{
							$exists=true;
						}						
					}
				}
				if($exists==false)
				{
					// get the original product id 
					$sql_prod = "SELECT products_product_id 
									FROM 
										combo_products 
									WHERE 
										comboprod_id = $curprodmapid 
									LIMIT 
										1";
					$ret_prod = $db->query($sql_prod);
					if($db->num_rows($ret_prod))
					{
						$row_prod 			= $db->fetch_array($ret_prod);
						$org_prod_id 		= $row_prod['products_product_id'];
					}	
					
					// Making an entry to combo_products_variable_combination table to get a new combination id
					$insert_array									= array();
					$insert_array['combo_products_comboprod_id']	= $curprodmapid;
					$insert_array['products_product_id']			= $org_prod_id;
					$db->insert_from_array($insert_array,'combo_products_variable_combination');
					$cur_comb_id = $db->insert_id();
					foreach ($v as $var=>$varval)
					{
						foreach ($varval as $vars=>$varvals)
						{
							// Inserting the var and values
							if($varvals)
							{
								$insert_array													= array();
								$insert_array['combo_products_variable_combination_comb_id']	= $cur_comb_id;
								$insert_array['var_id']											= $vars;
								$insert_array['var_value_id']									= $varvals;
								$insert_array['products_product_id']							= $org_prod_id;
								$db->insert_from_array($insert_array,'combo_products_variable_combination_map');
							}	
						}	
					}
				}
				else
				{
					$sql_pd = "SELECT a.product_name 
									FROM 
										products a, combo_products b
									WHERE 
										b.comboprod_id = $curprodmapid 
										AND a.product_id = b.products_product_id
									LIMIT 
										1";
					$ret_pd = $db->query($sql_pd);
					if($db->num_rows($ret_pd))
					{
						$row_pd = $db->fetch_array($ret_pd);
					}
					$err_msgs .= " <br>".stripslashes($row_pd['product_name']);
				}
			}
		}
	}	
	for($i=0;$i<count($IdArr);$i++)
	{
		if(is_numeric($OrderArr[$i]))
		{
			$update_array					= array();
			$update_array['comboprod_order']	= $OrderArr[$i];
			$db->update_from_array($update_array,'combo_products',array('comboprod_id'=>$IdArr[$i]));
		}
		else
		{
		   $order_alert = 1;
		}
	}
	$DisArr=explode('~',$_REQUEST['ch_dis']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array1							= array();
		if(trim($DisArr[$i])>=0) 
		{
			$update_array1['combo_discount']	= trim($DisArr[$i]);
			$db->update_from_array($update_array1,'combo_products',array('comboprod_id'=>$IdArr[$i]));
		}
	}
	// Delete cache 
	delete_combo_cache($_REQUEST['combo_id']);
	if($order_alert==1)
	{
	  $alert = "Enter numeric value for order.";
	}
	$alert_succ = '';
	if(!$order_alert && !$disc_alert)
		$alert_succ = 'Details saved successfully.';
	// Check whether combo similar to current one already exists
	$alert_duplicate = check_same_combo_exist($_REQUEST['combo_id']);	
	if($alert_duplicate)
	{
		//deactivating the combo
		$sql_update = "UPDATE combo 
							SET 
								combo_active = 0 
							WHERE 
								combo_id = ".$_REQUEST['combo_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$db->query($sql_update);
		if($alert_succ!='')
			$alert_succ .='<br>';
		$alert_succ .= 'Combo deal Deactivated as there exists other combo deal(s) with the same set of products';
	}
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	if($alert_succ !='')
	{
		$alert_succ .= '<br>';
		$alert = $alert .$alert_succ;
	}	
	if($err_msgs!='')
	{
		$err_msgs = '<br>Variable Combination selected for following product(s) not saved as they already exists'.$err_msgs;
		$alert .= $err_msgs;
	}	
	set_combo_bundle_price($_REQUEST['combo_id']);
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='activate_combo')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	// Check whether atleast one combination selected for each of the products which have variables
	$alert 				= check_atleast_one_combination($_REQUEST['combo_id']);
	$alert_duplicate 	= check_same_combo_exist($_REQUEST['combo_id']);
	$alert_mismatch		= check_count_of_var_with_value_in_combo($_REQUEST['combo_id']);
	if($alert=='' and  !$alert_duplicate and $alert_mismatch=='')
	{
		//activating the combo
		// Check whether there exists atlease 2 products in combo
		$sql_prodcheck = "SELECT count(a.comboprod_id) as totprod
							FROM 
								combo_products a,products b 
							WHERE 
								a.products_product_id = b.product_id 
								AND b.sites_site_id = $ecom_siteid 
								AND a.combo_combo_id=".$_REQUEST['combo_id'];
		$ret_prodcheck = $db->query($sql_prodcheck);
		list($totprods) = $db->fetch_array($ret_prodcheck);
		if($totprods>=2)
		{
			Change_Combo_Active_status($_REQUEST['combo_id'],1);
			if($alert_succ!='')
				$alert_succ .='<br>';
			$alert_succ .= 'Combo deal Activated Successfully';
		}
		else
			$alert_succ .='<br> Sorry!! Combo deal should have atleast 2 products inorder to Activate it. ';
	}
	if($alert_duplicate)
	{
		$alert .= '<br>Combo Deal cannot be activated since there exists other combo deal(s) with the same set of products';
	}
	if($alert_mismatch)
	{
		$alert .= '<br>'.$alert_mismatch;
	}
	if($alert_succ !='')
	{
		$alert_succ .= '<br>';
		$alert = $alert .$alert_succ;
	}	
	set_combo_bundle_price($_REQUEST['combo_id']);
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='deactivate_combo')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	if($_REQUEST['combo_id'])
	{
		$update_sql = "UPDATE combo 
						SET 
							combo_active = 0 
						WHERE 
							combo_id = ".$_REQUEST['combo_id']." 
						LIMIT 
							1";
		$db->query($update_sql);
		$alert = 'Combo deal Deactivated';
	}
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='delete_productvarcombination')// Delete combo product variable combinations
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	if($_REQUEST['delid'])
	{
		if($_REQUEST['combo_id'])
		{
			$sql_del = "DELETE FROM 
							combo_products_variable_combination_map 
						WHERE 
							combo_products_variable_combination_comb_id = ".$_REQUEST['delid'];
			$db->query($sql_del);
			
			$sql_del = "DELETE FROM 
							combo_products_variable_combination 
						WHERE 
							comb_id = ".$_REQUEST['delid'];
			$db->query($sql_del);
			$alert = 'Combination Deleted Successfully';
		}
		// Check whether current combo is active now
		$sql_check = "SELECT combo_active 
						FROM 
							combo 
						WHERE 
							combo_id = ".$_REQUEST['combo_id']." 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if($db->num_rows($ret_check))
		{
			$row_check = $db->fetch_array($ret_check);
			if($row_check['combo_active']==1)
			{
				// Check whether this combo is to be deactivated
				$check = check_atleast_one_combination($_REQUEST['combo_id']);
				if($check!='') // if required combinations does not exists then deactivate the combo
				{
					Change_Combo_Active_status($_REQUEST['combo_id'],0);					
					$alert .= "<br> Combo deactivated as variable combination not selected for certain product(s) ";
				}
			}
		}
	}
	show_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_display_productcombo')// List display products of combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayProdComboAssign') // Assign display products to combo
{
	include ('includes/combo/list_combo_display_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_displayProdComboAssign') // Save display products to combo
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['combo_id']=$_REQUEST['pass_combo_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['products_product_id']=$v;
		$db->insert_from_array($insert_array, 'combo_display_product');
		
	}
	$alert='Product Assigned Successfully';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Combo Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_combo_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Combo</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
<?		
}
elseif($_REQUEST['fpurpose']=='displayProdComboUnAssign') //Un assign display products from combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
	$comboid		= $_REQUEST['combo_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
		$id = $id_arr[$i];	
		$sql_del = "DELETE FROM combo_display_product WHERE id=$id";
		$db->query($sql_del);
	}
	$alert = 'Product unassigned successfully.';
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_product_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_display_categorycombo')// List display categories of combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_category_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayCategoryComboAssign') // Assign display categories to combo
{
	include ('includes/combo/list_combo_display_selcategory.php');
}
elseif($_REQUEST['fpurpose']=='save_displayCategoryComboAssign') // Save display categories to combo
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['combo_id']=$_REQUEST['pass_combo_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['product_categories_category_id']=$v;
		$db->insert_from_array($insert_array, 'combo_display_category');
	}
	$alert='Category Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Combo Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_combo_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=categmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Combo</a><br /><br />
	<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
<?		
}
elseif($_REQUEST['fpurpose']=='displayCategoryComboUnAssign') //Un assign display categories from combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
	$comboid		= $_REQUEST['combo_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM combo_display_category WHERE id=$id";
			$db->query($sql_del);
	}
	$alert = 'Category unassigned successfully.';
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_category_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_display_staticcombo')// List display static pages of combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_static_combo_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayStaticComboAssign') // Assign display static pages to combo
{
	include ('includes/combo/list_combo_display_selstaticpages.php');
}
elseif($_REQUEST['fpurpose']=='save_displayStaticComboAssign') // Save display sataic pages to combo
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['combo_id']=$_REQUEST['pass_combo_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['static_pages_page_id']=$v;
		$db->insert_from_array($insert_array, 'combo_display_static');
	}
	$alert='Static Pages Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=combo&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Combo Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_combo_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=statmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Combo</a><br /><br />
		<a class="smalllink" href="home.php?request=combo&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
<?		
}
elseif($_REQUEST['fpurpose']=='displayStaticComboUnAssign') //Un assign display static pages from combo
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$staticid_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($staticid_arr);$i++)
	{
		$id = $staticid_arr[$i];	
		$sql_del = "DELETE FROM combo_display_static WHERE id=$id";
		$db->query($sql_del);
	}
	$alert = 'Static Page unassigned successfully.';
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_display_static_combo_list($_REQUEST['combo_id'],$alert);
}
elseif ($_REQUEST['fpurpose']=='list_combimages')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	show_comboimage_list($_REQUEST['combo_id']);
}
elseif($_REQUEST['fpurpose']=='add_comboimg') // show image gallery to select the required images
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
	include("includes/image_gallery/list_images.php");
}
elseif($_REQUEST['fpurpose']=='save_combimagedetails') // ajax to save image details 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	if ($_REQUEST['ch_ids'] == '')
	{
		$alert = 'Sorry Image(s) not selected';
	}
	else
	{
		$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
		$ch_order	= explode("~",$_REQUEST['ch_order']);
		$ch_title	= explode("~",$_REQUEST['ch_title']);
		for($i=0;$i<count($ch_arr);$i++)
		{
			$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
			$sql_change = "UPDATE images_combo SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
			$db->query($sql_change);
		}
		$alert = 'Image details Saved Successfully';
	}	
	show_comboimage_list($_REQUEST['edit_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='unassign_combimagedetails')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/combo/ajax/combo_ajax_functions.php');
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Image(s) not selected';
	}
	else
	{
		$ch_arr 	= explode("~",$_REQUEST['del_ids']);
		for($i=0;$i<count($ch_arr);$i++)
		{
			$sql_del = "DELETE FROM images_combo WHERE id=".$ch_arr[$i];
			$db->query($sql_del);
		}
		$alert = 'Image(s) Unassigned Successfully';
	}	
	show_comboimage_list($_REQUEST['combo_id'],$alert);
}
elseif($_REQUEST['fpurpose'] =='list_seo')// Case of listing shops to groups
	{	
		$combo_id = $_REQUEST['combo_id'];	

		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/combo/ajax/combo_ajax_functions.php');
		show_page_seoinfo($combo_id,$alert);
	}
	elseif($_REQUEST['fpurpose'] =='save_seo')// Case of listing shops to groups
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ("../includes/combo/ajax/combo_ajax_functions.php");
		$combo_id = $_REQUEST['combo_id'];	
		$unq_id = uniqid("");
		 $sql_check = "SELECT id FROM se_combo_title WHERE sites_site_id=$ecom_siteid AND combo_combo_id = ".$combo_id;
		 $sql_keys  = "SELECT se_keywords_keyword_id FROM se_combo_keywords WHERE combo_combo_id = ".$combo_id;
		$tb_name = 'se_combo_title';
	//echo $sql_check;die();
	
	$res_check = $db->query($sql_check);
	$row_check = $db->fetch_array($res_check);
		

	$keys_list = array();
	$res_keys = $db->query($sql_keys);
	if($db->num_rows($res_keys)>0) 
	{ 
		while($row_keys = $db->fetch_array($res_keys))
		{
			$keys_list[] = $row_keys['se_keywords_keyword_id'];
		}
		foreach($keys_list as $keys => $values)
		{
			
				$sql_delkey_rel = "DELETE FROM se_combo_keywords WHERE se_keywords_keyword_id = ".$values." AND combo_combo_id = ".$combo_id;
				//echo $sql_delkey_rel;echo "<br>";
				$db->query($sql_delkey_rel);					
			$sql_delkey = "DELETE FROM se_keywords WHERE keyword_id = ".$values." AND sites_site_id = ".$ecom_siteid;
			//echo $sql_delkey;echo "<br>";
			$db->query($sql_delkey);
		}
	}
	$ch_arr     = explode('~',$_REQUEST['ch_ids']);
	
	for($i=0;$i<count($ch_arr);$i++)
	{
		
			$insert_array = array();
			$insert_array['sites_site_id']		= $ecom_siteid;
			$insert_array['keyword_keyword']	= trim(add_slash($ch_arr[$i]));
			$db->insert_from_array($insert_array, 'se_keywords');
			$insert_id = $db->insert_id();
			
			if($insert_id > 0)
			{
				    $insert_array = array();
				
					$insert_array['combo_combo_id']	= $combo_id;
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_combo_keywords');
							
			}
	}
	//echo "<pre>";print_r($keys_list);die();
	
	//echo $tb_name;echo "<br>";die();
	if($row_check['id'] != "" && $row_check['id'] > 0)
	{
		if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
		{
			
				$sql_del = "DELETE FROM se_combo_title WHERE id=".$row_check['id'];				
			
			$db->query($sql_del);
		}
		else
		{
			$update_array['title']					= trim(add_slash($_REQUEST['page_title']));
			$update_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			$db->update_from_array($update_array, $tb_name, 'id', $row_check['id']);
		}
					 $alert	=	"Updated Successfully.";

	}
	else
	{
		$alert				= '';		
		if($alert == "")
		{
			$insert_array = array();
			
				$insert_array['combo_combo_id']	= $combo_id;
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['title']					= trim(add_slash($_REQUEST['page_title']));
				$insert_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			
			
			$db->insert_from_array($insert_array, $tb_name);
			$insert_id = $db->insert_id();
			
			if($insert_id == "" || $insert_id == 0)
			{
				$alert	=	"Inserting seo info failed.";
			}
			else
			{
			   $alert	=	"Updated Successfully.";
			}
		}
		
	}	
	show_page_seoinfo($combo_id,$alert);
}
function array_compare($needle, $haystack, $match_all = true)
{	
	if (!is_array($needle) || (count($needle) != count($haystack)) ){
	return $array_value = 0; //array not the same
	}
	foreach ($needle as $k => $v)
	{
		if (in_array($v,$haystack))
		{
			$array_value = 1; //value exists in the Array
		}
		else
		{ 
			$array_value =  0; // the value not exist in the array,the arrays are not the same // allow insertion
			break;
		}
	}
	return $array_value; 	
} 
function get_existing_combo_prods($combo_id)
{
	global $db,$ecom_hostname,$ecom_siteid;
	$existing_pdts_ids			= array();
	$sql_existing_pdts			= "SELECT products_product_id FROM combo_products WHERE combo_combo_id=".$combo_id;
	$ret_existing_pdts			= $db->query($sql_existing_pdts);
	while($existing_pdts		= $db->fetch_array($ret_existing_pdts))
	{
		$exist_prod_id = 0;
		$exist_prod_id = $existing_pdts['products_product_id'];
		if($exist_prod_id)
			$existing_pdts_ids[]	= $exist_prod_id;
	}
	return $existing_pdts_ids;
}
?>
