<?php

	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_category/list_product_category.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		//include_once("classes/fckeditor.php");
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_category/add_product_category.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		if ($_REQUEST['cat_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['cat_name']);
			$fieldDescription = array('Category Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the categroy name already exists
				$sql_exists 	= "SELECT count(category_id) FROM product_categories WHERE 
									category_name='".add_slash($_REQUEST['cat_name'])."' AND sites_site_id=$ecom_siteid AND parent_id =".$_REQUEST['parent_id'];
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Category already exists";
				if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0){	
					// Check whether default_catgroup_id is there in the selected group list
					/*if (!in_array($_REQUEST['default_catgroup_id'],$_REQUEST['group_id']))
					{
						$alert = 'Default Product Category Group not included in the Selected Product Categories';
					}*/
				}	
			}
			if ($alert=='')
			{
				if($_REQUEST['product_showimage']=='' &&  $_REQUEST['product_showtitle']=='' &&   
				   $_REQUEST['product_showshortdescription']=='' &&  $_REQUEST['product_showprice']=='' &&  $_REQUEST['product_showrating']=='' &&  $_REQUEST['product_showbonuspoints']=='')  {
				   		$alert = "Sorry! Please Check any of Product Items to Display ";	
				   } 
				 if($_REQUEST['category_showname']=='' &&  $_REQUEST['category_showimage']=='' &&   
				   $_REQUEST['category_showshortdesc']==''  )  {
						$alert = "Sorry! Please Check any of Subcategory Items to Display ";	
				   }
				
			}
			if ($alert=='')
			{
				$insert_array										= array();
				$insert_array['sites_site_id']						= $ecom_siteid;
				$insert_array['parent_id']							= $_REQUEST['parent_id'];
				$insert_array['category_name']						= add_slash(trim($_REQUEST['cat_name']));
				$insert_array['category_shortdescription']			= add_slash(trim($_REQUEST['short_desc']));
				$insert_array['category_paid_description']			= add_slash(trim($_REQUEST['long_desc']),false);
				$insert_array['category_bottom_description']		= add_slash(trim($_REQUEST['bottom_desc']),false);
				$insert_array['category_hide']						= ($_REQUEST['cat_hide'])?1:0;
				$insert_array['category_showimageofproduct']		= ($_REQUEST['category_showimageofproduct'])?1:0;
				$insert_array['category_paid_for_longdescription']	= 'Y';
				$insert_array['category_subcatlisttype']			= add_slash($_REQUEST['category_subcatlisttype']);
				$insert_array['category_turnoff_treemenu']			= ($_REQUEST['chk_category_turnoff_treemenu']==1)?1:0;
				$insert_array['category_turnoff_pdf']				= ($_REQUEST['chk_category_turnoff_pdf']==1)?1:0;
				$insert_array['product_displaytype']				= add_slash($_REQUEST['product_displaytype']);
				$insert_array['product_orderfield']					= add_slash($_REQUEST['product_orderfield']);
				$insert_array['product_orderby']					= add_slash($_REQUEST['product_orderby']);
				
				$insert_array['product_displaywhere']				= add_slash($_REQUEST['product_displaywhere']);
				$insert_array['product_showimage']					= ($_REQUEST['product_showimage']==1)?1:0;
				$insert_array['product_showtitle']					= ($_REQUEST['product_showtitle']==1)?1:0;
				$insert_array['product_showshortdescription']		= ($_REQUEST['product_showshortdescription']==1)?1:0;
				$insert_array['product_showprice']					= ($_REQUEST['product_showprice']==1)?1:0;
				$insert_array['product_showrating']					= ($_REQUEST['product_showrating']==1)?1:0;
				$insert_array['product_showbonuspoints']			= ($_REQUEST['product_showbonuspoints']==1)?1:0;
				$insert_array['display_to_guest']			        = ($_REQUEST['display_to_guest']==1)?1:0;

				$insert_array['enable_grid_display']				= ($_REQUEST['enable_grid_display']==1)?1:0;
				$insert_array['product_variables_group_id']			= ($_REQUEST['product_variables_group_id'] > 0)?$_REQUEST['product_variables_group_id']:0;
				$insert_array['grid_column_cnt']					= ($_REQUEST['grid_column_cnt'] > 0)?$_REQUEST['grid_column_cnt']:12;
				$insert_array['subcategory_showimagetype']			= $_REQUEST['subcategory_showimagetype'];
				
				$insert_array['category_subcatlistmethod']			= $_REQUEST['category_subcatlistmethod'];
				$insert_array['category_showname']					= ($_REQUEST['category_showname']==1)?1:0;
				$insert_array['category_showimage']					= ($_REQUEST['category_showimage']==1)?1:0;
				$insert_array['category_showshortdesc']				= ($_REQUEST['category_showshortdesc']==1)?1:0;
				$insert_array['category_turnoff_mainimage']			= ($_REQUEST['category_turnoff_mainimage']==1)?1:0;
				$insert_array['category_turnoff_moreimages']		= ($_REQUEST['category_turnoff_moreimages']==1)?1:0;
				$insert_array['category_turnoff_noproducts']		= ($_REQUEST['category_turnoff_noproducts']==1)?1:0;
				$insert_array['special_detailspage_required']		= ($_REQUEST['special_detailspage_required']==1)?1:0;
				$insert_array['google_taxonomy_id']					=  $_REQUEST['google_product_category'];
				/*if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0)
				{
					$insert_array['default_catgroup_id']			= $_REQUEST['default_catgroup_id'];
				}
				else
				{
				 $insert_array['default_catgroup_id']			= $_REQUEST['group_id'][0];
				}*/
				$insert_array['default_catgroup_id']			= 0;
				$insert_array['mobile_api_parent_id']				= $_REQUEST['mobile_api_parent_id'];
				$insert_array['in_mobile_api_sites']				= ($_REQUEST['in_mobile_api_sites'])?1:0;

				$db->insert_from_array($insert_array,'product_categories');
				$insert_id = $db->insert_id();
				
				// Section to make entry to product_categorygroup_category
				if(count($_REQUEST['group_id']))
				{
					for($i=0;$i<count($_REQUEST['group_id']);$i++)
					{
						$insert_array								= array();
						$insert_array['catgroup_id']				= $_REQUEST['group_id'][$i];
						$insert_array['category_id']				= $insert_id;
						$insert_array['category_order']				= 0;
						$db->insert_from_array($insert_array,'product_categorygroup_category');
					}
				}
				// Completed the section to entry details to product_categorygroup_category
				// Calling function to clear category groups of current category
				if ($_REQUEST['parent_id'])	
					delete_category_cache($_REQUEST['parent_id']);
				delete_category_cache($insert_id);
				$alert .= '<br><span class="redtext"><b>Product Category added successfully</b></span><br>';
				if($_REQUEST['cat_Submit'] == 'Save & Return to Edit')
				{
			?>
				<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>&curtab=product_tab_td';
				</script>
			<?
				}
				else
				{
					echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Category Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Edit Page of Category</a> <br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Add New  Category Page </a>
			<?	}
				
			}
			else
			{
				//include_once("classes/fckeditor.php");
				include ('includes/product_category/add_product_category.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose'] == 'edit')
	{
		//include_once("classes/fckeditor.php");
		include_once("../config.php");
		include_once("functions/console_urls.php");
		include ('includes/product_category/ajax/product_category_ajax_functions.php');
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";	
		$edit_id 	= $_REQUEST['checkbox'][0];
		include ('includes/product_category/edit_product_category.php');
	}
	
	elseif($_REQUEST['fpurpose']=='save_edit')
	{
		if ($_REQUEST['cat_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['cat_name']);
			$fieldDescription = array('Category Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the categroy name already exists
				$sql_exists 	= "SELECT count(category_id) FROM product_categories WHERE 
									category_name='".add_slash($_REQUEST['cat_name'])."' AND sites_site_id=$ecom_siteid 
									AND category_id NOT IN(".$_REQUEST['checkbox'][0].") AND parent_id = ".$_REQUEST['parent_id'];
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Category already exists";
					/*if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0){
					// Check whether default_catgroup_id is there in the selected group list
						if (!in_array($_REQUEST['default_catgroup_id'],$_REQUEST['group_id']))
						{
							$alert = 'Default Product Category Group not included in the Selected Product Categories';
						}
					}*/
			}
			/*if ($alert=='')
			{
				if($_REQUEST['product_showimage']=='' &&  $_REQUEST['product_showtitle']=='' &&   
				   $_REQUEST['product_showshortdescription']=='' &&  $_REQUEST['product_showprice']=='' )  {
				   		$alert = "Sorry! Please Check any of Product Items to Display ";	
				   } 
				
			}*/
			if($alert=='')
			{
				if ($_REQUEST['parent_id'])
				{
					if ($_REQUEST['parent_id']==$_REQUEST['checkbox'][0])
					{
						$alert = 'Parent category cannot be the current category itself';
					}
					else
					{
						$subcats = generate_subcategory_tree($_REQUEST['checkbox'][0]);
						if(!is_array($subcats))
							$subcats[0] = $_REQUEST['checkbox'][0];
						if (array_key_exists($_REQUEST['parent_id'],$subcats))
							$alert = 'Parent should not be a category below the current category.';
					}	
					if ($alert=='')	
						delete_category_cache($_REQUEST['parent_id']);
				}
				if ($_REQUEST['mobile_api_parent_id'])
				{
					 if($alert!='')
					 {
					  $alert .= "<br />";
					 }
					if ($_REQUEST['mobile_api_parent_id']==$_REQUEST['checkbox'][0])
					{
						$alert .= 'Parent category for Mobile Application cannot be the current category itself ';
					}
					else
					{
						$subcats = generate_mobile_api_subcategory_tree($_REQUEST['checkbox'][0]);
						if(!is_array($subcats))
							$subcats[0] = $_REQUEST['checkbox'][0];
						if (array_key_exists($_REQUEST['mobile_api_parent_id'],$subcats))
							$alert .= 'Parent category for mobile Application should not be a category below the current category.';
					}	
				}
			}
			if ($alert=='')
			{
				// Find the previous parent for deleting the cache
				$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$_REQUEST['checkbox'][0];
				$ret_par = $db->query($sql_par);
				if ($db->num_rows($ret_par))
				{
					$row_par = $db->fetch_array($ret_par);
					if ($row_par['parent_id']!=0)
						delete_category_cache($row_par['parent_id']);
				}
				$update_array													= array();
				$update_array													= array();
				$update_array['sites_site_id']							= $ecom_siteid;
				$update_array['parent_id']								= $_REQUEST['parent_id'];
				$update_array['category_name']						= add_slash(trim($_REQUEST['cat_name']));
				$update_array['category_shortdescription']			= add_slash(trim($_REQUEST['short_desc']));
				$update_array['category_paid_description']			= add_slash(trim($_REQUEST['long_desc']),false);
				$update_array['category_bottom_description']	= add_slash(trim($_REQUEST['bottom_desc']),false);
				
				$update_array['category_hide']							= ($_REQUEST['cat_hide'])?1:0;
		//		$update_array['subcategory_showimagetype']		= $_REQUEST['subcategory_showimagetype'];
			
				//$update_array['category_showimageofproduct']	= ($_REQUEST['category_showimageofproduct'])?1:0;
				$update_array['category_paid_for_longdescription']	= 'Y';
			/*	$update_array['category_subcatlisttype']		= add_slash($_REQUEST['category_subcatlisttype']);
				$update_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
				$update_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
				$update_array['product_showimage']				= ($_REQUEST['product_showimage']==1)?1:0;
				$update_array['product_showtitle']				= ($_REQUEST['product_showtitle']==1)?1:0;
				$update_array['product_showshortdescription']	= ($_REQUEST['product_showshortdescription']==1)?1:0;
				$update_array['product_showprice']				= ($_REQUEST['product_showprice']==1)?1:0;*/
				/*if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0){
				$update_array['default_catgroup_id']			= $_REQUEST['default_catgroup_id'];
				}
				else
				{
				$update_array['default_catgroup_id']			= $_REQUEST['group_id'][0];
				}
				*/
				$update_array['category_turnoff_treemenu']			= ($_REQUEST['chk_category_turnoff_treemenu']==1)?1:0;
				$update_array['category_turnoff_pdf']				= ($_REQUEST['chk_category_turnoff_pdf']==1)?1:0;
				
				
				
				$update_array['category_turnoff_noproducts']		= ($_REQUEST['category_turnoff_noproducts']==1)?1:0;
				$update_array['special_detailspage_required']		= ($_REQUEST['special_detailspage_required']==1)?1:0;
				
				/* Google base category list change starts here */
				$update_array['google_taxonomy_id']					=  $_REQUEST['google_product_category'];
				if($_REQUEST['google_product_category_new'] != "")
				{
					$update_array['google_taxonomy_id']	=	$_REQUEST['google_product_category_new'];
				}
				/* Google base category list change ends here */
				
				$update_array['default_catgroup_id']					= 0;
				$update_array['mobile_api_parent_id']				= $_REQUEST['mobile_api_parent_id'];
				$update_array['in_mobile_api_sites']				= ($_REQUEST['in_mobile_api_sites'])?1:0;

				$edit_id 														= $_REQUEST['checkbox'][0];
				
				/* automatic 301 redirect function*/
				handle_auto_301($_REQUEST['cat_name'],0,0,$edit_id);
				
				$db->update_from_array($update_array,'product_categories',array('category_id'=>$_REQUEST['checkbox'][0]));
			
				// Section to make entry to product_categorygroup_category
				if(count($_REQUEST['group_id']))
				{
					$ext_arr = array();
					for($i=0;$i<count($_REQUEST['group_id']);$i++)
					{
						// Check whether this category is already mapped with this category group
						$sql_check = " SELECT count(catgroup_id) FROM product_categorygroup_category WHERE 
										category_id =$edit_id AND catgroup_id=".$_REQUEST['group_id'][$i];
						$ret_check = $db->query($sql_check);
						list($cnt) = $db->fetch_array($ret_check);
						
						if($cnt==0)
						{
							$insert_array								= array();
							$insert_array['catgroup_id']				= $_REQUEST['group_id'][$i];
							$insert_array['category_id']				= $_REQUEST['checkbox'][0];
							$insert_array['category_order']				= 0;
							$db->insert_from_array($insert_array,'product_categorygroup_category');
							$ext_arr[] = $_REQUEST['group_id'][$i];
						}	
						else
							$ext_arr[] = $_REQUEST['group_id'][$i];
					}
					$grp_str = implode(",",$ext_arr);
					
					$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id = ".$_REQUEST['checkbox'][0]." AND 
								catgroup_id NOT IN ($grp_str)";
					$db->query($sql_del);
					
				}
				else
				{
					$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id = ".$_REQUEST['checkbox'][0];
					$db->query($sql_del);
				}
				
				// Calling function to clear category groups of current category
				delete_category_cache($_REQUEST['checkbox'][0]);
				
				// Completed the section to entry details to product_categorygroup_category
				$alert .= '<br><span class="redtext"><b>Product Category Updated successfully</b></span><br>';
				if($_REQUEST['cat_Submit'] == 'Save & Return')
				{
				?>
				<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>';
				</script>
				<?
				}
				else
				{
					echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Category Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Edit Page of Category </a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Add New Category Page</a>
			<?	}
				
			}
			else
			{
				//include_once("classes/fckeditor.php");
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";	
				include_once("functions/console_urls.php");
				include ('includes/product_category/ajax/product_category_ajax_functions.php');
				$edit_id 	= $_REQUEST['checkbox'][0];
				include ('includes/product_category/edit_product_category.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='save_edit_settings') // case of coming to save the display settings for category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		$alert='';
		if ($alert=='')
		{
			if($_REQUEST['product_showimage']=='' &&  $_REQUEST['product_showtitle']=='' &&   
			   $_REQUEST['product_showshortdescription']=='' &&  $_REQUEST['product_showprice']=='' 
			   &&  $_REQUEST['product_showrating']=='' &&  $_REQUEST['product_showbonuspoints']=='')  {
					$alert = "Sorry! Please Check any of Product Items to Display ";	
			   } 
			 if($_REQUEST['category_showname']=='' &&  $_REQUEST['category_showimage']=='' &&   
			   $_REQUEST['category_showshortdesc']==''  )  {
					$alert = "Sorry! Please Check any of Subcategory Items to Display ";	
			   }   
			
		}
		if ($alert=='')
		{
			$update_array									= array();
			$update_array['sites_site_id']					= $ecom_siteid;
			$update_array['category_subcatlisttype']		= add_slash($_REQUEST['category_subcatlisttype']);
			$update_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
			$update_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
			$update_array['product_showimage']				= ($_REQUEST['product_showimage']==1)?1:0;
			$update_array['product_showtitle']				= ($_REQUEST['product_showtitle']==1)?1:0;
			$update_array['product_showshortdescription']	= ($_REQUEST['product_showshortdescription']==1)?1:0;
			$update_array['product_showprice']				= ($_REQUEST['product_showprice']==1)?1:0;
			$update_array['product_showrating']				= ($_REQUEST['product_showrating']==1)?1:0;
			$update_array['product_showbonuspoints']		= ($_REQUEST['product_showbonuspoints']==1)?1:0;
			
			$update_array['enable_grid_display']			= ($_REQUEST['enable_grid_display']==1)?1:0;
			$update_array['display_to_guest']			    = ($_REQUEST['display_to_guest']==1)?1:0;

			$update_array['product_variables_group_id']		= ($_REQUEST['product_variables_group_id'] > 0)?$_REQUEST['product_variables_group_id']:0;
			$update_array['grid_column_cnt']				= ($_REQUEST['grid_column_cnt'] > 0)?$_REQUEST['grid_column_cnt']:12;
			
			$update_array['subcategory_showimagetype']		= $_REQUEST['subcategory_showimagetype'];
			
			$update_array['category_subcatlistmethod']		= add_slash($_REQUEST['category_subcatlistmethod']);
			$update_array['category_showname']				= ($_REQUEST['category_showname']==1)?1:0;
			$update_array['category_showimage']				= ($_REQUEST['category_showimage']==1)?1:0;
			$update_array['category_showshortdesc']			= ($_REQUEST['category_showshortdesc']==1)?1:0;
			$update_array['product_orderfield']				= add_slash($_REQUEST['product_orderfield']);
			$update_array['product_orderby']				= add_slash($_REQUEST['product_orderby']);
			//$edit_id 														= $_REQUEST['checkbox'][0];
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$_REQUEST['edit_id']));
		
			// Calling function to clear category groups of current category
			delete_category_cache($_REQUEST['edit_id']);
			
			// Completed the section to entry details to product_categorygroup_category
			$alert = 'Display Settings Updated Successfully';
		}
		show_category_settings($_REQUEST['edit_id'],$alert);	
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Categories not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$deleted_cnt = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether categories exists under this category 
					$sql_check = "SELECT count(*) FROM product_categories WHERE parent_id=".$del_arr[$i];
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{
						//Check whether products exists under this category
						$sql_check = "SELECT count(*) FROM product_category_map WHERE product_categories_category_id=".$del_arr[$i];
						$ret_check = $db->query($sql_check);
						list($cnts) = $db->fetch_array($ret_check);
						if ($cnts==0)
						{	
							++$deleted_cnt;
							$sql_catgroup = "SELECT catgroup_id FROM product_categorygroup_category WHERE category_id=".$del_arr[$i];
							$ret_catgroup = $db->query($sql_catgroup);
							if($db->num_rows($ret_catgroup)>0)
							{
                           			while($row_catgroup = $db->fetch_array($ret_catgroup))
									{ 
										delete_catgroup_cache($row_catgroup['catgroup_id']);
									}			
							}
							$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id=".$del_arr[$i];
							$db->query($sql_del);
							
							$sql_del = "DELETE FROM product_categories WHERE category_id=".$del_arr[$i];
							$db->query($sql_del);
							$sql_delcat = "DELETE FROM customer_fav_categories WHERE categories_categories_id=".$del_arr[$i];
							$db->query($sql_delcat);
							
							
							$sql_delcat = "DELETE FROM images_product_category WHERE product_categories_category_id=".$del_arr[$i];
							$db->query($sql_delcat);
							
						$del_sql = "DELETE FROM combo_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM header_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_category_hit_count WHERE category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_category_hit_count_totals WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_categorygroup_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_shelf_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_shopbybrand_group_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM se_category_keywords WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM se_category_title WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM static_pagegroup_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM survey_display_category WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
						
						$del_sql = "DELETE FROM product_category_product_labels_group_map WHERE product_categories_category_id=".$del_arr[$i];
						$del_res = $db->query($del_sql);
							
							
							//if($alert) $alert .="<br />";
							//$alert .= "Product Category with ID -".$del_arr[$i]." Deleted";
							// Calling function to clear category groups of current category
							delete_category_cache($del_arr[$i]);
						}
						else
						{
							if($alert) $alert .="<br />";
							$alert .= "Products Exists under Category  ";
						}
					}
					else
					{
						if($alert) $alert .="<br />";
						$alert .= "Sorry!! Subcategories exists for the category  ";
					}	
				}	
			}
			if($deleted_cnt) {
			if($alert) $alert .="<br />";
			if($deleted_cnt>1)
				$alert .= "$deleted_cnt Product Categories Deleted";
			elseif($deleted_cnt	==1)
				$alert .= "$deleted_cnt Product Category Deleted";
			}
			
		}	
		include ('../includes/product_category/list_product_category.php');
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
			$update_array['category_hide']	= $new_status;
			$cur_id 						= $catid_arr[$i];	
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
			// Calling function to clear category groups of current category
			delete_category_cache($cur_id);
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/product_category/list_product_category.php');
	}
	else if($_REQUEST['fpurpose']=='subcategoryAssign') // Case of selecting categgories to groups
	{
		
		include ('includes/product_category/list_category_ass_selcat.php');
	}
	else if($_REQUEST['fpurpose']=='subcategoryAssignMobile') // Case of selecting categgories to groups
	{
		$for_mobile = 1;
		include ('includes/product_category/list_category_ass_selcat_mobile.php');
	}
	else if($_REQUEST['fpurpose']=='productAssign') // Case of selecting products to assign to current category
	{
		
		include ('includes/product_category/list_assign_products.php');
	}	
	else if($_REQUEST['fpurpose']=='productLableGroupAssign') 
	{
		
		include ('includes/product_category/list_assign_productlabelgroups.php');
	}
	else if($_REQUEST['fpurpose']=='save_subcategoryAssign') // Case of selecting products to groups
	{
    //echo "parent_id".$_REQUEST['pass_cat_id'];
		
		foreach($_REQUEST['checkbox'] as $v)
		{
			$update_array=array();
			
			$update_array['parent_id']	= $_REQUEST['pass_cat_id'];
			$cur_id 						= $v;	
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
			// Calling function to clear category groups of current category
			delete_category_cache($cur_id);
		}
		$alert='Category Assigned Successfullly';
		delete_category_cache($_REQUEST['pass_cat_id']);
		
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=category_tab_td" onclick="show_processing()">Go Back to the Edit  this Category</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='save_subcategoryAssignMobile') // Case of selecting categories to category
	{
    //echo "parent_id".$_REQUEST['pass_cat_id'];
		
		foreach($_REQUEST['checkbox'] as $v)
		{
			$update_array=array();
			
			$update_array['mobile_api_parent_id']	= $_REQUEST['pass_cat_id'];
			$cur_id 						= $v;	
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
			// Calling function to clear category groups of current category
			delete_category_cache($cur_id);
		}
		$alert='Category Assigned Successfullly';
		delete_category_cache($_REQUEST['pass_cat_id']);
		
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=categorymobile_tab_td" onclick="show_processing()">Go Back to the Edit  this Category</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='assign_selprodlabelgroup_save') // Case of selecting products to groups
	{
		$cat_id = $_REQUEST['pass_cat_id'];
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether current product label group is mapped with current category
			$sql_check = "SELECT map_id 
							FROM 
								product_category_product_labels_group_map 
							WHERE 
								product_categories_category_id =$cat_id 
								AND product_labels_group_group_id = $v 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$insert_array	= array();
				$insert_array['product_labels_group_group_id']	= $v;
				$insert_array['product_categories_category_id']	= $cat_id;
				$db->insert_from_array($insert_array,'product_category_product_labels_group_map');
			}	
		}
		$alert='Product Label Group(s) Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=label_tab_td" onclick="show_processing()">Go Back to the Edit  this Category</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='unassign_labelgroup')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include_once('../includes/product_category/ajax/product_category_ajax_functions.php');	
		$del_arr 		= explode('~',$_REQUEST['del_ids']);
		$cat_id			= $_REQUEST['edit_id'];
		for($i=0;$i<count($del_arr);$i++)
		{
			$cur_id 	= $del_arr[$i];	
			$del_qry = "DELETE FROM 
							product_category_product_labels_group_map 
						WHERE 
							product_categories_category_id =$cat_id 
							AND map_id =$cur_id 
						LIMIT 
							1";
			$db->query($del_qry);
		}
		$alert = 'Product Label Group(s) Unassigned Successfully';
		list_labelgroups($_REQUEST['edit_id'],$alert);		
	}
	else if($_REQUEST['fpurpose']=='assign_products') // Case of selecting products to categories
	{
		$category_id = $_REQUEST['pass_cat_id'];
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not selected';
		}
		else
		{ 
			$sql_assigned_products = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id =".$category_id;
			$res_assigned_products = $db->query($sql_assigned_products);
			$assigned_products_arr = array();
			while($assigned_products = $db->fetch_array($res_assigned_products))
			{
				$assigned_products_arr[]= $assigned_products['products_product_id'];
			}
					$products_arr = explode("~",$_REQUEST['product_ids']);
					for($i=0;$i<count($products_arr);$i++)
					{
						if(trim($products_arr[$i]) && !in_array($products_arr[$i],$assigned_products_arr))
						{
							$insert_array 													= array();
							$insert_array['product_categories_category_id']	= $category_id;
							$insert_array['products_product_id']					= $products_arr[$i];
							$db->insert_from_array($insert_array, 'product_category_map');
						}	
					}
					$alert = 'Product(s) Successfully assigned  to current Category'; 
		}						
		$alert = '<center><font color="red"><b>'.$alert;
				$alert .= '</b></font></center>';
				echo $alert;
				?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&catgroupid=<?=$_REQUEST['pass_catgroupid']?>&parentid=<?=$_REQUEST['pass_parentid']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=product_tab_td" onclick="show_processing()">Go Back to Edit  this Category</a><br /><br />
			
	<?php			
	}
	elseif($_REQUEST['fpurpose'] =='change_parent')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['catids']);
		$new_parent		= $_REQUEST['ch_parent'];
		for($i=0;$i<count($catid_arr);$i++)
		{   $flag =1;
			// Find the previous parent 
			$sql_par = "SELECT parent_id,category_name FROM product_categories WHERE category_id=".$catid_arr[$i];
			$ret_par = $db->query($sql_par);
			if ($db->num_rows($ret_par))
			{
				$row_par = $db->fetch_array($ret_par);
				$cat_name = $row_par['category_name'];
				if ($row_par['parent_id']!=0)
				{
					// Calling function to clear category groups of previous parent 
					delete_category_cache($row_par['parent_id']);
				}
			}
			//
			
			if ($_REQUEST['ch_parent']==$catid_arr[$i])
					{
						$flag =0;
						$alert .= "Parent category cannot be the current category itself for '".$cat_name."' <br>";
					}
					else
					{ 
						$subcats = generate_subcategory_tree($catid_arr[$i]);
						if(!is_array($subcats))
							$subcats[0] = $catid_arr[0];
						 if($_REQUEST['ch_parent']!=0){
						if (array_key_exists($_REQUEST['ch_parent'],$subcats)){
							$flag =0;
							$alert .= "Parent should not be a category below the current category for '".$cat_name."' <br>";
							}
						}
					}	
			//
			if($flag){
					$update_array					= array();
					$update_array['parent_id']		= $new_parent;
					$cur_id 						= $catid_arr[$i];	
					$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
					// Calling function to clear category groups of new parent category
					delete_category_cache($new_parent);
					$alert .= "Parent of Selected Product Category 
				   changed successfully for '".$cat_name."' <br>";
		}
		}
				
		
		include ('../includes/product_category/list_product_category.php');
	}
	elseif($_REQUEST['fpurpose'] =='catgroup_assign')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['catids']);
		$assign_group	= $_REQUEST['ch_catgroup'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id 						= $catid_arr[$i];	
			// Check whether the current category is already assigned to the selected category group
			$sql_check = "SELECT count(catgroup_id) FROM product_categorygroup_category WHERE category_id=$cur_id 
								AND catgroup_id=$assign_group";
			$ret_check = $db->query($sql_check);
			list($cnts) = $db->fetch_array($ret_check);
			if ($cnts>0)
			{
				if($alert) $alert .="<br />";
				$alert .= "Sorry!! Category with ID -".$cur_id.' already assigned to the selected product category group';
			}
			else
			{
				$insert_array					= array();
				$insert_array['catgroup_id']	= $assign_group;
				$insert_array['category_id']	= $cur_id;
				$insert_array['category_order']	= 0;
				$db->insert_from_array($insert_array,'product_categorygroup_category');
				if($alert) $alert .="<br />";
				$alert .= "Category with ID -".$cur_id.' has been assigned to the selected product category group';
				
				// Calling function to clear category groups of current category
				delete_category_cache($cur_id);
			}	
		}
		include ('../includes/product_category/list_product_category.php');
	}
	elseif ($_REQUEST['fpurpose'] =='catgroup_unassign')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['catids']);
		$assign_group	= $_REQUEST['ch_catgroup'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id 	= $catid_arr[$i];	
			// Check whether the current category is already assigned to the selected category group
			$sql_check 	= "SELECT count(catgroup_id) FROM product_categorygroup_category WHERE category_id=$cur_id 
								AND catgroup_id=$assign_group";
			$ret_check 	= $db->query($sql_check);
			list($cnt_d)= $db->fetch_array($ret_check);
			if($cnt_d>0)
			{
				// Check whether the current category is assigned to more than one category group
				$sql_check = "SELECT count(catgroup_id) FROM product_categorygroup_category WHERE category_id=$cur_id";
				$ret_check = $db->query($sql_check);
				list($cnts) = $db->fetch_array($ret_check);
				if($cnts==1)
				{
					if($alert) $alert .="<br />";
					$alert .= "Sorry!! Unassign not possible for Category with ID -".$cur_id.' since it is the only category group assigned to that category.';
				}
				elseif ($cnts>1)
				{
					$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id=$cur_id 
									AND catgroup_id=$assign_group";
					$db->query($sql_del);
					
					// find the default category group for the current category
					$sql_default = "SELECT default_catgroup_id FROM product_categories WHERE category_id=$cur_id";
					$ret_default = $db->query($sql_default);
					if ($db->num_rows($ret_default))
					{
						$row_default = $db->fetch_array($ret_default);
						// check whether this group is there in the assigned category list for this category
						$sql_checkgr = "SELECT count(catgroup_id) FROM product_categorygroup_category WHERE category_id=$cur_id AND catgroup_id=".$row_default['default_catgroup_id'];
						$ret_checkgr = $db->query($sql_checkgr);
						list($cntgr) = $db->fetch_array($ret_checkgr);
						if ($cntgr==0)
						{
							// Get any one of the category group for current category
							$sql_extgr = "SELECT catgroup_id FROM product_categorygroup_category WHERE category_id=$cur_id LIMIT 1";
							$ret_extgr = $db->query($sql_extgr);
							if ($db->num_rows($ret_extgr))
							{
								$row_extgr = $db->fetch_array($ret_extgr);
								$update_array							= array();
								$update_array['default_catgroup_id']	= $row_extgr['catgroup_id'];
								$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
							}
						}
					}
					
					if($alert) $alert .="<br />";
					$alert .= "Category with ID -".$cur_id.' has been unassigned from the selected product category group';
				}	
			}	
			else
			{
				if($alert) $alert .="<br />";
					$alert .= "Sorry!! Category with ID -".$cur_id.' is not assigned to the selected product category group';
			}
			// Calling function to clear category groups of current category
			delete_category_cache($cur_id);
		}
		
		include ('../includes/product_category/list_product_category.php');
	}
	elseif($_REQUEST['fpurpose']=='list_catimg') // show category image list using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_catimage_list($_REQUEST['cur_catid']);
	}
	elseif($_REQUEST['fpurpose']=='add_catimg') // show image gallery to select the required images
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif($_REQUEST['fpurpose']=='save_catimagedetails') // ajax to save image details 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
	/*	if ($_REQUEST['ch_ids'] == '')   // Commented For Save Details - -- For any product image
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{ */ 
				$shimg      			= ($_REQUEST['shimg']=='1')?'1':'0';
				$turnoff_moreimages		= ($_REQUEST['category_turnoff_moreimages']==1)?1:0;
				$turnoff_mainimage		= ($_REQUEST['category_turnoff_mainimage']==1)?1:0;
				$sql 					= "UPDATE product_categories SET category_showimageofproduct='".$shimg."', 
												category_turnoff_moreimages=$turnoff_moreimages,
												category_turnoff_mainimage=$turnoff_mainimage 
											WHERE 
												category_id='".$_REQUEST['edit_id']."' 
											LIMIT 
												1";
				$res = $db->query($sql);
				$alert = ' Saved Successfully ';
			if($_REQUEST['ch_ids'] != '') 
			{	
				$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
				$ch_order	= explode("~",$_REQUEST['ch_order']);
				$ch_title	= explode("~",$_REQUEST['ch_title']);
				for($i=0;$i<count($ch_arr);$i++)
				{
					$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
					$sql_change = "UPDATE images_product_category SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
					$db->query($sql_change);
				}
				
				$alert = 'Image details Saved Successfully';
			 }
	//	}	 For Else ...
		// Calling function to clear category groups of current category
	// Find the parent
		$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$_REQUEST['edit_id'];
		$ret_par = $db->query($sql_par);
		if ($db->num_rows($ret_par))
		{
			$row_par = $db->fetch_array($ret_par);
			if ($row_par['parent_id']!=0)
				delete_category_cache($row_par['parent_id']);
		}
		delete_category_cache($_REQUEST['edit_id']);
		show_catimage_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_subcatdetails') // ajax to save image details 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Categories not selected';
		}
		else
		{  
		    $ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_categories SET category_order = ".$chroder." WHERE category_id=".$ch_arr[$i];
				$db->query($sql_change);
				// Calling function to clear category groups of current category
				delete_category_cache($ch_arr[$i]);
			}
			delete_category_cache($_REQUEST['edit_id']);
			$alert = 'Category details Saved Successfully';
		}	
		show_subcat_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_subcatdetailsmobile') // ajax to save image details 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Categories not selected';
		}
		else
		{  
		    $ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_categories SET category_order_mobile = ".$chroder." WHERE category_id=".$ch_arr[$i];
				$db->query($sql_change);
				// Calling function to clear category groups of current category
				//delete_category_cache($ch_arr[$i]);
			}
			//delete_category_cache($_REQUEST['edit_id']);
			$alert = 'Category details Saved Successfully';
		}	
		show_subcat_list($_REQUEST['edit_id'],$alert,1);
	}
	elseif($_REQUEST['fpurpose']=='unassign_catimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_product_category WHERE id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$_REQUEST['edit_id'];
		$ret_par = $db->query($sql_par);
		if ($db->num_rows($ret_par))
		{
			$row_par = $db->fetch_array($ret_par);
			if ($row_par['parent_id']!=0)
				delete_category_cache($row_par['parent_id']);
		}
		delete_category_cache($_REQUEST['edit_id']);
		show_catimage_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='unassign_subcatdetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry category not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				// Find the previous parent 
				$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$ch_arr[$i];
				$ret_par = $db->query($sql_par);
				if ($db->num_rows($ret_par))
				{
					$row_par = $db->fetch_array($ret_par);
					if ($row_par['parent_id']!=0)
					{
						// Calling function to clear category groups of previous parent 
						delete_category_cache($row_par['parent_id']);
					}
				}
				$sql_del = "UPDATE product_categories SET parent_id=0 WHERE category_id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'categories Unassigned Successfully';
		}	
		delete_category_cache($_REQUEST['edit_id']);
		show_subcat_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='unassign_subcatdetailsmobile')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry category not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				/*// Find the previous parent 
				$sql_par = "SELECT parent_id FROM product_categories WHERE category_id=".$ch_arr[$i];
				$ret_par = $db->query($sql_par);
				if ($db->num_rows($ret_par))
				{
					$row_par = $db->fetch_array($ret_par);
					if ($row_par['parent_id']!=0)
					{
						// Calling function to clear category groups of previous parent 
						delete_category_cache($row_par['parent_id']);
					}
				}*/
				$sql_del = "UPDATE product_categories SET mobile_api_parent_id=0 WHERE category_id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			$alert = 'categories Unassigned Successfully';
		}	
		//delete_category_cache($_REQUEST['edit_id']);
		show_subcat_list($_REQUEST['edit_id'],$alert,1);
	}
	elseif($_REQUEST['fpurpose']=='unassign_prods')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry product(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
                        $cnts = 0;
			for($i=0;$i<count($ch_arr);$i++)
			{
                            $sql_prod_check = "SELECT DISTINCT product_categories_category_id FROM product_category_map WHERE products_product_id=".$ch_arr[$i]."";
                            $ret_prod_check = $db->query($sql_prod_check);
                            $count =$db->num_rows($ret_prod_check);
                            if($count>1)
                            {
                                $default_cat = 'No';
                                // Check whether the current category is set as the default category of current products
                                $sql_def = "SELECT product_default_category_id 
                                                FROM 
                                                    products 
                                                WHERE 
                                                    product_id=".$ch_arr[$i]." 
                                                LIMIT 
                                                    1";
                                $ret_def = $db->query($sql_def);
                                if($db->num_rows($ret_def))
                                {
                                    $row_def = $db->fetch_array($ret_def);
                                    if($row_def['product_default_category_id']==$_REQUEST['edit_id'])
                                        $default_cat = 'Yes';
                                }
                                $sql_del = "DELETE FROM 
                                                product_category_map 
                                                        WHERE 
                                                                product_categories_category_id=".$_REQUEST['edit_id']." 
                                                                AND products_product_id =".$ch_arr[$i]." 
                                                        LIMIT 
                                                                1";
				$db->query($sql_del);
                                $cnts++;
                                if($default_cat=='Yes')
                                {
                                    // get the first category still assigned with current product and set it as default category of the product
                                    $sql_cats = "SELECT product_categories_category_id 
                                                    FROM 
                                                        product_category_map 
                                                    WHERE 
                                                        products_product_id=".$ch_arr[$i]." 
                                                    LIMIT 
                                                        1";
                                    $ret_cats = $db->query($sql_cats);
                                    if($db->num_rows($ret_cats))
                                    {
                                        $row_cats = $db->fetch_array($ret_cats);
                                        $update_prod = "UPDATE 
                                                            products 
                                                        SET 
                                                            product_default_category_id=".$row_cats['product_categories_category_id']." 
                                                        WHERE 
                                                            product_id = ".$ch_arr[$i]." 
                                                            AND sites_site_id = $ecom_siteid 
                                                        LIMIT 
                                                            1";
                                        $db->query($update_prod);
                                    }
                                }       
                            }
                            elseif($count==1)
                            {
                                 $sql_pp = "SELECT product_name 
                                                FROM 
                                                    products 
                                                WHERE 
                                                    product_id=".$ch_arr[$i]." 
                                                LIMIT 
                                                    1";
                                $ret_pp = $db->query($sql_pp);
                                if($db->num_rows($ret_pp))
                                    $row_pp = $db->fetch_array($ret_pp);
                                $alert .= 'Sorry!! cannot unassign \''.$row_pp['product_name'].'\' from current category as the product is mapped only with current category';
                            }    
			}
                        if(count($ch_arr)==$cnts)
                            $alert = 'Product(s) Unassigned Successfully';
                        else
                        {
                            if($cnts==0)
                                $alert = 'Sorry!! no product(s) unassigned<br><br>'.$alert;
                            else
                                $alert = $cnts.' product(s) unassigned<br><br>'.$alert;        
                        }    
		}	
		show_product_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='subcat_changehide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['ch_ids']);
		$new_status		= $_REQUEST['change_hide'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$update_array					= array();
			$update_array['category_hide']	= $new_status;
			$cur_id 						= $catid_arr[$i];	
			$db->update_from_array($update_array,'product_categories',array('category_id'=>$cur_id));
			// Calling function to clear category groups of current category
			delete_category_cache($_REQUEST['edit_id']);
		}
		delete_category_cache($cur_id);
		$alert = 'Hidden Status of subcategories changed successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		
	
		show_subcat_list($_REQUEST['edit_id'],$alert);
		
	}
	elseif($_REQUEST['fpurpose']=='save_proddetails') // ajax to save product details 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Product(s) not selected';
		}
		else
		{  
		    $ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$cat_id = $_REQUEST['edit_id'];
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE product_category_map 
										SET 
											product_order = ".$chroder." 
										WHERE 
											product_categories_category_id=".$cat_id." 
												and products_product_id=".$ch_arr[$i]." 
										LIMIT 1";
				$db->query($sql_change);
			}
			$alert = 'Product Sort Order Saved Successfully';
		}	
		show_product_list($_REQUEST['edit_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='list_category_maininfo')
	{
		//print_r($_REQUEST);
		/*include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include_once("../classes/fckeditor.php");
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_catmaininfo($_REQUEST['cur_catid']);*/
		include_once("../config.php");
		include_once("functions/console_urls.php");
		include ('includes/product_category/ajax/product_category_ajax_functions.php');
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";	
		$edit_id 	= $_REQUEST['checkbox'][0];
		include ('includes/product_category/edit_product_category.php');
		
	}
	elseif($_REQUEST['fpurpose']=='list_subcat')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_subcat_list($_REQUEST['cur_catid']);
	}
	elseif($_REQUEST['fpurpose']=='list_subcatmobile')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_subcat_list($_REQUEST['cur_catid'],'',1);
	}
	elseif($_REQUEST['fpurpose']=='list_prods')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_product_list($_REQUEST['cur_catid']);
	}
	elseif($_REQUEST['fpurpose']=='list_cat_settings')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_category_settings($_REQUEST['cur_catid']);
	}
	elseif($_REQUEST['fpurpose']=='list_labelgroups')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		list_labelgroups($_REQUEST['cur_catid']);
	}
	elseif($_REQUEST['fpurpose']=='settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_category/ajax/product_category_ajax_functions.php');
		include_once("classes/fckeditor.php");	
		include ('includes/product_category/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='list_categories_settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_categories/ajax/product_category_ajax_functions.php');
		show_category_list_settingstomany();
		//include ('includes/products/apply_settingstomany.php');
	}elseif($_REQUEST['fpurpose']=='save_settingstomany'){
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include_once("functions/functions.php");
		include_once('session.php');
		include_once("config.php");	
		include ('includes/products/ajax/product_ajax_functions.php');
		$update_array	=	array();
		if($_REQUEST['subcatlist_check']){
			$update_array['category_subcatlisttype']			= add_slash($_REQUEST['category_subcatlisttype']);
		}
		if($_REQUEST['subcatmethod_check']){
			$update_array['category_subcatlistmethod']		= add_slash($_REQUEST['category_subcatlistmethod']);
		}
		if($_REQUEST['subcat_fields_check']){
				$update_array['category_showname']			= ($_REQUEST['category_showname']==1)?1:0;
				$update_array['category_showshortdesc']		= ($_REQUEST['category_showshortdesc']==1)?1:0;
				$update_array['category_showimage']			= ($_REQUEST['category_showimage']==1)?1:0;
		}
		if($_REQUEST['subcatlist_imagecheck'])
		{
			$update_array['subcategory_showimagetype']		= add_slash($_REQUEST['subcategory_showimagetype']);
		}
		if($_REQUEST['prod_disp_method'])
		{
			$update_array['product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
		}
		if($_REQUEST['prod_list'])
		{
			$update_array['product_displaywhere']			= add_slash($_REQUEST['product_displaywhere']);
		}
		if($_REQUEST['field_disp_prod'])
		{
		       	$update_array['product_showimage']				= ($_REQUEST['product_showimage']==1)?1:0;
				$update_array['product_showtitle']				= ($_REQUEST['product_showtitle']==1)?1:0;
				$update_array['product_showshortdescription']	= ($_REQUEST['product_showshortdescription']==1)?1:0;
				$update_array['product_showprice']				= ($_REQUEST['product_showprice']==1)?1:0;
				$update_array['product_showrating']				= ($_REQUEST['product_showrating']==1)?1:0;
				$update_array['product_showbonuspoints']		= ($_REQUEST['product_showbonuspoints']==1)?1:0;
		}		
		if($_REQUEST['cat_treemenu_check'])
		{
			$update_array['category_turnoff_treemenu']			=($_REQUEST['chk_category_turnoff_treemenu']==1)?1:0;
		}
		if($_REQUEST['cat_pdf_check'])
		{
			$update_array['category_turnoff_pdf']			=($_REQUEST['chk_category_turnoff_pdf']==1)?1:0;
		}
		if($_REQUEST['cat_moreimage_check'])
		{
			$update_array['category_turnoff_moreimages']			=($_REQUEST['category_turnoff_moreimages']==1)?1:0;
		}
		if($_REQUEST['cat_mainimage_check'])
		{
			$update_array['category_turnoff_mainimage']			= ($_REQUEST['category_turnoff_mainimage']==1)?1:0;
		}
		if($_REQUEST['cat_noprod_check'])
		{
			$update_array['category_turnoff_noproducts']			=($_REQUEST['category_turnoff_noproducts']==1)?1:0;
		}
		if($_REQUEST['cat_anyprodimg_check'])
		{
			if ($_REQUEST['category_showimageofproduct']==1)
			{
				if(!$_REQUEST['chk_ignore_assigned_img_cat'])
					$update_array['category_showimageofproduct']			=($_REQUEST['category_showimageofproduct']==1)?1:0;
			}
			else
			{
				$update_array['category_showimageofproduct']			=($_REQUEST['category_showimageofproduct']==1)?1:0;
			}		
		}	
		
		
		if($_REQUEST['in_mobile_api_sites'])
		{
			$update_array['in_mobile_api_sites']			=($_REQUEST['enable_in_mobile_api_sites']==1)?1:0;
		}
		
		
		if($_REQUEST['prod_order'])
		{
			$update_array['product_orderfield']							= add_slash($_REQUEST['product_orderfield']);
			$update_array['product_orderby']								= add_slash($_REQUEST['product_orderby']);
		}
		if($_REQUEST['select_categories']=='All') { // set the values to all the products
			if(count($update_array))
				$db->update_from_array($update_array,'product_categories',array('sites_site_id'=>$ecom_siteid));
			if($_REQUEST['cat_anyprodimg_check'])
			{
				if ($_REQUEST['category_showimageofproduct']==1 and $_REQUEST['chk_ignore_assigned_img_cat']==1)
				{
					// Get all the categories in current site
					$sql_cats = "SELECT category_id 
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid ";
					$ret_cats = $db->query($sql_cats);
					if($db->num_rows($ret_cats))
					{
						while ($row_cats = $db->fetch_array($ret_cats))
						{
							$cur_id = $row_cats['category_id'];
							// Check whether any image assigned for current category
							$sql_check = "SELECT id 
											FROM 
												images_product_category 
											WHERE 
												product_categories_category_id  = $cur_id 
											LIMIT 
												1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$update_array = array();					
								$update_array['category_showimageofproduct']			=($_REQUEST['category_showimageofproduct']==1)?1:0;
								$db->update_from_array($update_array,'product_categories',array('sites_site_id'=>$ecom_siteid,'category_id'=>$cur_id));
							}	
						}
					}	
				}
			}
			// case of product label group option is ticked
			if($_REQUEST['prodlabelgroup_check'] and count($_REQUEST['settings_productlabel_group']))
			{
				$sql_cats = "SELECT category_id 
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid ";
				$ret_cats = $db->query($sql_cats);
				if($db->num_rows($ret_cats))
				{
					while ($row_cats = $db->fetch_array($ret_cats))
					{
						$cur_id = $row_cats['category_id'];
						// Check whether any of the selected product label groups need to be assigned to current category
						for($i=0;$i<count($_REQUEST['settings_productlabel_group']);$i++)
						{
							$sql_check = "SELECT map_id 
											FROM 
												product_category_product_labels_group_map 
											WHERE 
												product_categories_category_id = $cur_id 
												AND product_labels_group_group_id  = ".$_REQUEST['settings_productlabel_group'][$i]." 
											LIMIT 
												1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$insert_array									= array();
								$insert_array['product_categories_category_id']	= $cur_id;
								$insert_array['product_labels_group_group_id']	= $_REQUEST['settings_productlabel_group'][$i];
								$db->insert_from_array($insert_array,'product_category_product_labels_group_map');
							}
						}
					}
				}		
			}
			$alert= "Categories Updated Successfully !!";
			clear_all_cache();// Clearing all cache
		}elseif ($_REQUEST['select_categories']=='Bycat'){
			if($_REQUEST['settings_categoryid'] == 0){
			$alert = "Error: Select a category";
			}elseif($_REQUEST['settings_categoryid']){
				if($_REQUEST['settings_categoryid'][0]!=0){
				// update for selected products
					if(count($update_array))
					{
						foreach($_REQUEST['settings_categoryid'] as $key=>$val)
						{
							$db->update_from_array($update_array,'product_categories',array('sites_site_id'=>$ecom_siteid,'category_id'=>$val));
							delete_category_cache($val);
							$alert= "Categories Updated Successfully !!";
						}
					}	
					if($_REQUEST['cat_anyprodimg_check'])
					{
						if ($_REQUEST['category_showimageofproduct']==1 and $_REQUEST['chk_ignore_assigned_img_cat']==1)
						{
							foreach($_REQUEST['settings_categoryid'] as $key=>$val)
							{
								// Check whether any image assigned for current category
								$sql_check = "SELECT id 
												FROM 
													images_product_category 
												WHERE 
													product_categories_category_id  = $val 
												LIMIT 
													1";
								$ret_check = $db->query($sql_check);
								$update_array = array();
								if($db->num_rows($ret_check)==0)
								{					
									$update_array['category_showimageofproduct']			=($_REQUEST['category_showimageofproduct']==1)?1:0;
									$db->update_from_array($update_array,'product_categories',array('sites_site_id'=>$ecom_siteid,'category_id'=>$val));
									delete_category_cache($val);
								}	
							}
						 	$alert= "Categories Updated Successfully !!";
						}
					}
				
					// case of product label group option is ticked
					if($_REQUEST['prodlabelgroup_check'] and count($_REQUEST['settings_productlabel_group']))
					{
						foreach($_REQUEST['settings_categoryid'] as $key=>$val)
						{
							$cur_id = $val;
							// Check whether any of the selected product label groups need to be assigned to current category
							for($i=0;$i<count($_REQUEST['settings_productlabel_group']);$i++)
							{
								$sql_check = "SELECT map_id 
												FROM 
													product_category_product_labels_group_map 
												WHERE 
													product_categories_category_id = $cur_id 
													AND product_labels_group_group_id  = ".$_REQUEST['settings_productlabel_group'][$i]." 
												LIMIT 
													1";
								$ret_check = $db->query($sql_check);
								if($db->num_rows($ret_check)==0)
								{
									$insert_array									= array();
									$insert_array['product_categories_category_id']	= $cur_id;
									$insert_array['product_labels_group_group_id']	= $_REQUEST['settings_productlabel_group'][$i];
									$db->insert_from_array($insert_array,'product_category_product_labels_group_map');
								}
							}
						}
						$alert= "Categories Updated Successfully !!";
					}
				}
			}
		}
		include ('includes/product_category/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='list_googlebase_cate')
	{
		$faction	=	'add';
		if($_REQUEST['faction'] != "")
		{
			$faction	=	$_REQUEST['faction'];
		}
		include_once("../functions/functions.php");
		include_once("../session.php");
		include_once("../config.php");
		include("../includes/product_category/ajax/product_category_ajax_functions.php");
		list_googlebase_cate($faction);	
	}
	elseif($_REQUEST['fpurpose'] =='list_shops')// Case of listing shops to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_shop_category_list($_REQUEST['cur_catid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_featured')// Case of listing shops to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_featuredprod_list($_REQUEST['cur_catid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_seo')// Case of listing shops to groups
	{	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_page_seoinfo($_REQUEST['cur_catid'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='save_seo')// Case of listing shops to groups
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		$category_id = $_REQUEST['cat_id'];	
		$unq_id = uniqid("");
	
		 $sql_check = "SELECT id FROM se_category_title WHERE sites_site_id=$ecom_siteid AND product_categories_category_id = ".$category_id;
		 $sql_keys  = "SELECT se_keywords_keyword_id FROM se_category_keywords WHERE product_categories_category_id = ".$category_id;
		$tb_name = 'se_category_title';
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
			
				$sql_delkey_rel = "DELETE FROM se_category_keywords WHERE se_keywords_keyword_id = ".$values." AND product_categories_category_id = ".$category_id;
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
				
					$insert_array['product_categories_category_id']	= $category_id;
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_category_keywords');
							
			}
	}
	//echo "<pre>";print_r($keys_list);die();
	
	//echo $tb_name;echo "<br>";die();
	if($row_check['id'] != "" && $row_check['id'] > 0)
	{
		if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
		{
			
				$sql_del = "DELETE FROM se_category_title WHERE id=".$row_check['id'];				
			
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
			
				$insert_array['product_categories_category_id']	= $category_id;
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
	
		delete_category_cache($category_id);
		recreate_entire_websitelayout_cache();
		delete_body_cache();
		//$ajax_return_function = 'ajax_return_contents';
			//include "../ajax/ajax.php";
			//include ("../includes/product_category/ajax/product_category_ajax_functions.php");
			show_page_seoinfo($category_id,$alert);
		/* Button code to save and return starts here */
	
	
}
	else if($_REQUEST['fpurpose']=='shop_sel') // Case of showing shops to be assigned
	{
		include ('includes/product_category/list_shopbybrandgroup_selshop.php');
	}
	else if($_REQUEST['fpurpose']=='save_shop_sel') // Case of mapping selected shops to curret group
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the shop is already assigned
			$sql_check = "SELECT map_id FROM category_shop_map 
							WHERE shopbybrand_category_id=".$_REQUEST['pass_cat_id']." AND 
							shopbybrand_shopbybrand_id=$v AND sites_site_id=".$ecom_siteid;
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array											= array();
				$insert_array['shopbybrand_category_id']	= $_REQUEST['pass_cat_id'];
				$insert_array['shopbybrand_shopbybrand_id']		= $v;
				$insert_array['sites_site_id']					= $ecom_siteid;

				$db->insert_from_array($insert_array, 'category_shop_map');
			}			
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['pass_shopgroup_id']);
		$alert='Shops Assigned Successfullly to Category';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shops_tab_td" onclick="show_processing()">Go Back to the Edit this Product Category</a><br /><br />
	<?	
	}
	else if($_REQUEST['fpurpose']=='unassign_shop') // Case of unassigning shops from group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 		= explode('~',$_REQUEST['del_ids']);
		$shopgroupid	= $_REQUEST['edit_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM category_shop_map WHERE map_id=$id AND sites_site_id=".$ecom_siteid;
			$db->query($sql_del);
			
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['edit_id']);
		$alert = 'Product Shops unassigned successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_shop_category_list($shopgroupid,$alert);

	}
	/*elseif($_REQUEST['fpurpose']=='sel_googlebase_cate')
	{
		$cate_id	=	$_REQUEST['cate_id'];
		include_once("../functions/functions.php");
		include_once("../session.php");
		include_once("../config.php");
		$ajax_return_function = 'ajax_return_cate';
		include("../includes/product_category/ajax/product_category_ajax_functions.php");
		sel_googlebase_cate($cate_id);
		
		//$ajax_return_function = 'ajax_return_contents';
	}*/
	else if($_REQUEST['fpurpose']=='save_shoporder') // Case of saving order for shops in group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$order_arr		= explode('~',$_REQUEST['ch_order']);		
		$id_arr 		= explode('~',$_REQUEST['ch_ids']);
		$shopgroupid	= $_REQUEST['cat_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id 						= $id_arr[$i];	
			$update_array				= array();
			$update_array['shop_order']	= $order_arr[$i];
			$db->update_from_array($update_array,'category_shop_map',array('map_id'=>$id));
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['shopgroup_id']);
		$alert = 'Order Saved successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
	    show_shop_category_list($shopgroupid,$alert);
	}
	else if($_REQUEST['fpurpose']=='featprod_sel') // Case of showing shops to be assigned
	{
		include ('includes/product_category/list_productcategory_selfeatprod.php');
	}
	else if($_REQUEST['fpurpose']=='assign_featproducts') // Case of mapping selected shops to curret group
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the shop is already assigned
			$sql_check = "SELECT map_id FROM category_featuredprod_map 
							WHERE product_categories_category_id=".$_REQUEST['pass_cat_id']." AND 
							products_product_id=$v AND sites_site_id=".$ecom_siteid;
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array											= array();
				$insert_array['product_categories_category_id']	= $_REQUEST['pass_cat_id'];
				$insert_array['products_product_id']		= $v;
				$insert_array['sites_site_id']					= $ecom_siteid;

				$db->insert_from_array($insert_array, 'category_featuredprod_map');
			}			
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['pass_shopgroup_id']);
		$alert='Featured Products Assigned Successfullly to Category';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product category Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cat_id']?>&catname=<?=$_REQUEST['pass_catname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=featured_tab_td" onclick="show_processing()">Go Back to the Edit this Product Category</a><br /><br />
	<?	
	}	
	else if($_REQUEST['fpurpose']=='unassign_featprod') // Case of unassigning shops from group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 		= explode('~',$_REQUEST['del_ids']);
		$curcatid	= $_REQUEST['edit_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM category_featuredprod_map WHERE map_id=$id AND sites_site_id=".$ecom_siteid;
			$db->query($sql_del);
			
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['edit_id']);
		$alert = 'Featured Product(s) unassigned successfully from current category.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_featuredprod_list($curcatid,$alert);

	}
	else if($_REQUEST['fpurpose']=='save_featprodorder') // Case of saving order for shops in group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$order_arr		= explode('~',$_REQUEST['ch_order']);		
		$id_arr 		= explode('~',$_REQUEST['ch_ids']);
		$curcatid	= $_REQUEST['cat_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id 						= $id_arr[$i];	
			$update_array				= array();
			$update_array['prod_order']	= $order_arr[$i];
			$db->update_from_array($update_array,'category_featuredprod_map',array('map_id'=>$id));
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['shopgroup_id']);
		$alert = 'Order Saved successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
	    show_featuredprod_list($curcatid,$alert);
	}	
	elseif($_REQUEST['fpurpose'] =='list_variables')// Case of listing variables created for category
	{	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		
		show_category_variables($_REQUEST['cur_catid'],$alert,$_REQUEST);
	}	
	elseif($_REQUEST['fpurpose'] =='save_varsetting')// Case of listing variables created for category after saving settings
	{	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		
		$cat_id		=	$_REQUEST['cat_id'];
		$set_val	=	$_REQUEST['set_val'];
		
		$sql_enablevar = "UPDATE product_categories SET enable_searchrefine = $set_val WHERE sites_site_id = $ecom_siteid AND category_id = $cat_id ";
		$ret_enablevar = $db->query($sql_enablevar);
		
		$alert = 'Search Refine Settings Saved Successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_category_variables($cat_id,$alert);
	}
	else if($_REQUEST['fpurpose']=='variable_add') // Case of showing shops to be assigned
	{
		include ('includes/product_category/add_product_category_variable.php');
	}
	else if($_REQUEST['fpurpose']=='add_variables')
	{
		if ($_REQUEST['var_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['var_name']);
			$fieldDescription = array('Category Variable Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the variable name already exists
				$sql_exists 	= "SELECT count(refine_caption) FROM product_category_searchrefine_keyword WHERE 
									refine_caption='".add_slash($_REQUEST['var_name'])."' AND sites_site_id=$ecom_siteid AND product_categories_category_id =".$_REQUEST['cur_catid'];
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Category Variable already exists";
				if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0){	
					// Check whether default_catgroup_id is there in the selected group list
					/*if (!in_array($_REQUEST['default_catgroup_id'],$_REQUEST['group_id']))
					{
						$alert = 'Default Product Category Group not included in the Selected Product Categories';
					}*/
				}	
			}
			
			if ($alert=='')
			{
				$insert_array										= array();
				$insert_array['sites_site_id']						= $ecom_siteid;
				$insert_array['product_categories_category_id']		= $_REQUEST['cur_catid'];
				$insert_array['refine_caption']						= add_slash(trim($_REQUEST['var_name']));
				$insert_array['refine_order']						= add_slash(trim($_REQUEST['var_order']));	
				$insert_array['refine_hidden']						= ($_REQUEST['var_hide'])?1:0;			
				$insert_array['refine_display_style']				= add_slash(trim($_REQUEST['var_type']));
				
				if($_REQUEST['var_type'] == "RANGE")
				{
					$insert_array['refine_lowval']					= add_slash(trim($_REQUEST['var_lowval']));
					$insert_array['refine_highval']					= add_slash(trim($_REQUEST['var_highval']));
					$insert_array['refine_interval']				= add_slash(trim($_REQUEST['var_interval']));
					$insert_array['refine_suffix']					= add_slash(trim($_REQUEST['var_suffix']));
					$insert_array['refine_prefix']					= add_slash(trim($_REQUEST['var_prefix']));
				}

				$db->insert_from_array($insert_array,'product_category_searchrefine_keyword');
				$insert_id = $db->insert_id();
				
				if($_REQUEST['var_type'] == "CHECKBOX")
				{
					for($i=0;$i<count($_REQUEST['check_item']);$i++)
					{
						$insert_array									= array();
						$insert_array['refine_id']						= $insert_id;
						if(trim($_REQUEST['check_item'][$i]) != "")
						{
							$insert_array['refineval_value']				= $_REQUEST['check_item'][$i];
							$insert_array['refineval_order']				= add_slash(trim($_REQUEST['check_item_order'][$i]));
							$insert_array['sites_site_id']					= $ecom_siteid;
							$insert_array['product_categories_category_id']	= $_REQUEST['cur_catid'];
							$db->insert_from_array($insert_array,'product_category_searchrefine_keyword_values');
						}
					}
				}
				elseif($_REQUEST['var_type'] == "BOX")
				{
					for($i=0;$i<count($_REQUEST['box_item']);$i++)
					{
						$insert_array									= array();
						$insert_array['refine_id']						= $insert_id;
						if(trim($_REQUEST['box_item'][$i]) != "" || trim($_REQUEST['box_item_code'][$i]) != "")
						{
							$insert_array['refineval_value']				= $_REQUEST['box_item'][$i];
							$insert_array['refineval_order']				= add_slash(trim($_REQUEST['box_item_order'][$i]));
							$insert_array['refineval_color_code']			= $_REQUEST['box_item_code'][$i];
							$insert_array['sites_site_id']					= $ecom_siteid;
							$insert_array['product_categories_category_id']	= $_REQUEST['cur_catid'];
							$db->insert_from_array($insert_array,'product_category_searchrefine_keyword_values');
						}
					}
				}
				elseif($_REQUEST['var_type'] == "RANGE")
				{
					$update_array									= array();
					$update_array['refine_lowval']					= $_REQUEST['var_lowval'];
					$update_array['refine_highval']					= $_REQUEST['var_highval'];
					$update_array['refine_interval']				= $_REQUEST['var_interval'];
					$update_array['refine_prefix']					= $_REQUEST['var_prefix'];
					$update_array['refine_suffix']					= $_REQUEST['var_suffix'];
					$db->update_from_array($update_array,'product_category_searchrefine_keyword',array('refine_id'=>$insert_id));
				}
				// Completed the section to entry details to product_categorygroup_category
				// Calling function to clear category groups of current category
				if ($_REQUEST['parent_id'])	
					delete_category_cache($_REQUEST['parent_id']);
				$alert .= '<br><span class="redtext"><b>Search Refine Variable Updated Successfully</b></span><br>';
				
				/*if($_REQUEST['cat_Submit'] == 'Save & Return to Edit')
				{*/
			?>
				<!--<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>&curtab=product_tab_td';
				</script>-->
			<?
				/*}
				else
				{*/
					echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Category Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$_REQUEST['cur_catid']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=variables_tab_td">Go Back to the Edit Page of Category</a> <br />
				<br />
				
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit_variables&checkbox[0]=<?=$_REQUEST['cur_catid']?>&cur_catid=<?=$_REQUEST['cur_catid']?>&pass_cat_id=<?=$_REQUEST['cur_catid']?>&varID=<?=$insert_id?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Edit Variable</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=variable_add&checkbox[0]=<?=$_REQUEST['cur_catid']?>&cur_catid=<?=$_REQUEST['cur_catid']?>&pass_cat_id=<?=$_REQUEST['cur_catid']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=variables_tab_td">Go Back to the Add Variable</a> <br />
				<br />
				<!--<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Add New  Category Page </a>-->
			<?	//}
				
			}
			else
			{
				include ('includes/product_category/add_product_category_variable.php');
			}
		}
	}
	else if($_REQUEST['fpurpose']=='edit_variables')
	{
		include ('includes/product_category/edit_product_category_variable.php');
		/*include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_edit_category_variable($_REQUEST,$alert);*/
	}
	else if($_REQUEST['fpurpose']=='edit_variables_save')
	{
		if ($_REQUEST['var_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['var_name']);
			$fieldDescription = array('Category Variable Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			
			if ($alert=='')
			{				
				$update_array										= array();
				$update_array['refine_caption']						= add_slash(trim($_REQUEST['var_name']));
				$update_array['refine_order']						= add_slash(trim($_REQUEST['var_order']));	
				$update_array['refine_hidden']						= ($_REQUEST['var_hide'])?1:0;			
				$update_array['refine_display_style']				= add_slash(trim($_REQUEST['var_type']));
				$db->update_from_array($update_array,'product_category_searchrefine_keyword',array('refine_id'=>$_REQUEST['var_id'],'product_categories_category_id'=>$_REQUEST['cur_catid']));
				
				if($_REQUEST['var_type'] == "RANGE")
				{
					$sql_remrngvar = "DELETE FROM product_category_searchrefine_keyword_values WHERE refine_id = ".$_REQUEST['var_id']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
					$ret_remrngvar = $db->query($sql_remrngvar);
					
					$update_array									= array();
					$update_array['refine_lowval']					= add_slash(trim($_REQUEST['var_lowval']));
					$update_array['refine_highval']					= add_slash(trim($_REQUEST['var_highval']));
					$update_array['refine_interval']				= add_slash(trim($_REQUEST['var_interval']));
					$update_array['refine_suffix']					= add_slash(trim($_REQUEST['var_suffix']));
					$update_array['refine_prefix']					= add_slash(trim($_REQUEST['var_prefix']));
				}
				$db->update_from_array($update_array,'product_category_searchrefine_keyword',array('refine_id'=>$_REQUEST['var_id'],'product_categories_category_id'=>$_REQUEST['cur_catid']));
				
				$delVarValID		=	array();
				if($_REQUEST['delete_var_val_id'] != "")
				{
					$delVarValID	=	explode(",",$_REQUEST['delete_var_val_id']);
				}
				//echo "<pre> Del ID - ";print_r($delVarValID);
				
				if($_REQUEST['var_type'] == "CHECKBOX")
				{
					$chkExists		=	array();
					$chkExists		=	$_REQUEST['chk_exts'];
					//echo "<pre> Chk Exts - ";print_r($chkExists);
					
					if(count($delVarValID) > 0)
					{
						for($j=0;$j<count($delVarValID);$j++)
						{
							$sql_remchkvar = "DELETE FROM product_category_searchrefine_keyword_values WHERE refineval_id = ".$delVarValID[$j]." AND refine_id = ".$_REQUEST['var_id']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
							//echo $sql_remchkvar;echo "<br>";
							$ret_remchkvar = $db->query($sql_remchkvar);
							
							if(count($chkExists) > 0)
							{
								if(($key = array_search($delVarValID[$j], $chkExists)) !== false) 
								{
									unset($chkExists[$key]);
									$chkExists = array_values($chkExists);
								}
							}
						}
					}
					
					//echo "<pre> New Chk Exts - ";print_r($chkExists);
					
					if(count($chkExists) > 0)
					{
						$var_val	=	"";
						$var_ord	=	0;
						for($n=0;$n<count($chkExists);$n++)
						{
							$var_val	=	$_REQUEST['check_item_'.$chkExists[$n]];
							$var_ord	=	$_REQUEST['check_item_order_'.$chkExists[$n]];
							
							if($chkExists[$n] != "")
							{
								$sql_upd_val = "UPDATE product_category_searchrefine_keyword_values 
												SET refineval_value = '".$var_val."',refineval_order = ".$var_ord."
												WHERE refineval_id = ".$chkExists[$n]." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
								//echo $sql_upd_val;echo "<br>";
								
								$ret_upd_val = $db->query($sql_upd_val);
							}
						}
					}
					//die();
					
					if(count($_REQUEST['check_item']) > 0)
					{
						for($i=0;$i<count($_REQUEST['check_item']);$i++)
						{
							$insert_array									= array();
							$insert_array['refine_id']						= $_REQUEST['var_id'];
							if(trim($_REQUEST['check_item'][$i]) != "")
							{
								$insert_array['refineval_value']				= $_REQUEST['check_item'][$i];
								$insert_array['refineval_order']				= add_slash(trim($_REQUEST['check_item_order'][$i]));
								$insert_array['sites_site_id']					= $ecom_siteid;
								$insert_array['product_categories_category_id']	= $_REQUEST['cur_catid'];
								$db->insert_from_array($insert_array,'product_category_searchrefine_keyword_values');
							}
						}
					}
				}
				elseif($_REQUEST['var_type'] == "BOX")
				{
					$boxExists		=	array();
					$boxExists		=	$_REQUEST['box_exts'];
					//echo "<pre> box Exts - ";print_r($boxExists);
					
					if(count($delVarValID) > 0)
					{
						for($j=0;$j<count($delVarValID);$j++)
						{
							$sql_remboxvar = "DELETE FROM product_category_searchrefine_keyword_values WHERE refineval_id = ".$delVarValID[$j]." AND refine_id = ".$_REQUEST['var_id']." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
							//echo $sql_remboxvar;echo "<br>";
							$ret_remboxvar = $db->query($sql_remboxvar);
							
							if(count($boxExists) > 0)
							{
								if(($key = array_search($delVarValID[$j], $boxExists)) !== false) 
								{
									unset($boxExists[$key]);
									$chkExists = array_values($boxExists);
								}
							}
						}
					}
					
					//echo "<pre> New Box Exts - ";print_r($boxExists);
					
					if(count($boxExists) > 0)
					{
						$var_val	=	"";
						$var_ord	=	0;
						for($n=0;$n<count($boxExists);$n++)
						{
							$var_val	=	$_REQUEST['box_item_'.$boxExists[$n]];
							$var_ord	=	$_REQUEST['box_item_order_'.$boxExists[$n]];
							$var_code	=	$_REQUEST['box_item_code_'.$boxExists[$n]];
							
							if($boxExists[$n] != "")
							{
								$sql_upd_val = "UPDATE product_category_searchrefine_keyword_values 
												SET refineval_value = '".$var_val."', refineval_order = ".$var_ord.", refineval_color_code = '".$var_code."'
												WHERE refineval_id = ".$boxExists[$n]." AND product_categories_category_id = ".$_REQUEST['cur_catid'];
								//echo $sql_upd_val;echo "<br>";
								
								$ret_upd_val = $db->query($sql_upd_val);
							}
						}
					}
					//die();
					
					if(count($_REQUEST['box_item']) > 0)
					{
						for($i=0;$i<count($_REQUEST['box_item']);$i++)
						{
							$insert_array									= array();
							$insert_array['refine_id']						= $_REQUEST['var_id'];
							if(trim($_REQUEST['box_item'][$i]) != "" || trim($_REQUEST['box_item_code'][$i]) != "")
							{
								$insert_array['refineval_value']				= $_REQUEST['box_item'][$i];
								$insert_array['refineval_order']				= add_slash(trim($_REQUEST['box_item_order'][$i]));
								$insert_array['refineval_color_code']			= $_REQUEST['box_item_code'][$i];
								$insert_array['sites_site_id']					= $ecom_siteid;
								$insert_array['product_categories_category_id']	= $_REQUEST['cur_catid'];
								$db->insert_from_array($insert_array,'product_category_searchrefine_keyword_values');
							}
						}
					}
				}
				/*elseif($_REQUEST['var_type'] == "RANGE")
				{

					$update_array									= array();
					$update_array['refine_lowval']					= $_REQUEST['var_lowval'];
					$update_array['refine_highval']					= $_REQUEST['var_highval'];
					$update_array['refine_interval']				= $_REQUEST['var_interval'];
					$update_array['refine_prefix']					= $_REQUEST['var_prefix'];
					$update_array['refine_suffix']					= $_REQUEST['var_suffix'];
					$db->update_from_array($update_array,'product_category_searchrefine_keyword',array('refine_id'=>$insert_id));
				}*/
				// Completed the section to entry details to product_categorygroup_category
				// Calling function to clear category groups of current category
				if ($_REQUEST['parent_id'])	
					delete_category_cache($_REQUEST['parent_id']);
				$alert .= '<br><span class="redtext"><b>Search Refine Variable Updated Successfully</b></span><br>';
				
				/*if($_REQUEST['cat_Submit'] == 'Save & Return to Edit')
				{*/
			?>
				<!--<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$insert_id?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>&curtab=product_tab_td';
				</script>-->
			<?
				/*}
				else
				{*/
					echo $alert;
			?>
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Category Listing page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?=$_REQUEST['cur_catid']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=variables_tab_td">Go Back to the Edit Page of Category</a> <br />
				<br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=edit_variables&checkbox[0]=<?=$_REQUEST['cur_catid']?>&cur_catid=<?=$_REQUEST['cur_catid']?>&pass_cat_id=<?=$_REQUEST['cur_catid']?>&varID=<?=$_REQUEST['var_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Edit Variable</a><br />
				<br />
				<a class="smalllink" href="home.php?request=prod_cat&fpurpose=variable_add&checkbox[0]=<?=$_REQUEST['cur_catid']?>&cur_catid=<?=$_REQUEST['cur_catid']?>&pass_cat_id=<?=$_REQUEST['cur_catid']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=variables_tab_td">Go Back to the Add Variable</a> <br />
				<br />
				<!--<a class="smalllink" href="home.php?request=prod_cat&fpurpose=add&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&search_in_mobile_application=<?php echo $_REQUEST['search_in_mobile_application']?>">Go Back to the Add New  Category Page </a>-->
			<?	//}
				
			}
			else
			{
				include ('includes/product_category/edit_product_category_variable.php');
			}
		}
	}
	
	else if($_REQUEST['fpurpose']=='delete_variable') // Case of unassigning shops from group
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr = explode('~',$_REQUEST['del_ids']);
		$catid = $_REQUEST['edit_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del_var = "DELETE FROM product_category_searchrefine_keyword WHERE refine_id=$id AND product_categories_category_id=".$catid;
			$db->query($sql_del_var);			
				
			$sql_del_var_val = "DELETE FROM product_category_searchrefine_keyword_values WHERE refine_id=$id AND product_categories_category_id=".$catid;
			$db->query($sql_del_var_val);
			
			$sql_del_var_map = "DELETE FROM product_searchrefine_map WHERE refine_id=$id AND product_categories_category_id=".$catid;
			$db->query($sql_del_var_map);
			
		}
		// Delete cache
		//delete_compshopgroup_cache($_REQUEST['edit_id']);
		$alert = 'Product Category Variable Deleted Successfully.';
		include ('../includes/product_category/ajax/product_category_ajax_functions.php');
		show_category_variables($catid,$alert,$_REQUEST);

	}
	elseif($_REQUEST['fpurpose']=='show_googlecategory_popup'){ // apply discount and tax setings to more than one or AlL products by category
		include_once('../session.php');
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include_once("../functions/functions.php");		
		include_once("../config.php");	
		include ('../includes/product_category/ajax/product_ajax_show_googlecategory.php');
		$pg = ($_REQUEST['page'])?$_REQUEST['page']:0;
		$id_arr =array();
		if($_REQUEST['ch_ids']!='')
		{
			$id_arr 	= explode('~',$_REQUEST['ch_ids']);
	    }
	    if($_REQUEST['cur_prodid']!='')
	    {
		 $prod_id = $_REQUEST['cur_prodid'];
		}	    
		else
		{
		 $prod_id = 0;
		}
		$mod = $_REQUEST['mod'];
		show_googlecategories($mod,$prod_id,$id_arr,$pg,$alert);
		//include ('includes/products/apply_settingstomany.php');
	}
?>
