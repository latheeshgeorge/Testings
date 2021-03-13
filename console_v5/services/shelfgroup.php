<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/shelfgroup/list_shelfgroup.php");
}

elseif($_REQUEST['fpurpose']=='save_shelf_order') // Shelf Menu order 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['shelf_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_shelf',array('shelfgroup_id'=>$IdArr[$i]));
		// Delete Cache
		delete_shelf_cache($id);	
	}
	delete_body_cache();
	$alert = 'Order saved successfully.';
	include ('../includes/shelfgroup/list_shelfgroup.php');
}
elseif($_REQUEST['fpurpose']=='save_order') // Shelfs order within a Menu
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['ch_ids']);
	$OrderArr=explode('~',$_REQUEST['ch_order']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['shelf_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'shelf_group_shelf',array('id'=>$IdArr[$i]));
	}
	// Delete Cache
	delete_shelf_cache($_REQUEST['shelfgroup_id']);	
	$alert = 'Order saved successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	delete_body_cache();
	show_shelf_group_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_display_categoryshelf')// List display categories of shelf Menu
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
		show_display_category_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayCategoryShelfGroupAssign') // Assign display categories to shelf Menu
{
	include ('includes/shelfgroup/list_shelfgroup_display_selcategory.php');
}
elseif($_REQUEST['fpurpose']=='save_displayCategoryShelfGroupAssign') // Save display categories to shelf Menu
{
	foreach($_REQUEST['checkbox'] as $v)
		{
			$insert_array=array();
			$insert_array['shelf_group_id']=$_REQUEST['pass_shelfgroup_id'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$insert_array['product_categories_category_id']=$v;
			$db->insert_from_array($insert_array, 'shelf_group_display_category');
			
		}
		delete_shelf_cache($_REQUEST['pass_shelfgroup_id']);	
		delete_body_cache();
		$alert='Category Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelfgroup_id=<?=$_REQUEST['pass_shelfgroup_id']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelfgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfcategories_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf Menu</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose'] =='list_shelf_maininfo')// Case of listing main info for shgelf Menus
{
	include_once("functions/console_urls.php");
	$curtab	= 'main_tab_td';
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	include("includes/shelfgroup/edit_shelfgroup.php");
}
elseif($_REQUEST['fpurpose']=='list_display_staticshelf')// List display static pages of shelf Menu
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	show_display_static_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
} 
elseif($_REQUEST['fpurpose']=='list_display_shopshelf')// List display shops of shelf Menu
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	show_display_shop_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
} 

elseif($_REQUEST['fpurpose']=='displayStaticShelfGroupAssign') // Assign display static pages to shelf Menu
{
	include ('includes/shelfgroup/list_shelfgroup_display_selstaticpages.php');
}

elseif($_REQUEST['fpurpose']=='save_displayStaticShelfGroupAssign') // Save display sataic pages to shelf Menu
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['shelf_group_id']=$_REQUEST['pass_shelfgroup_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['static_pages_page_id']=$v;
		$db->insert_from_array($insert_array, 'shelf_group_display_static');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelfgroup_id']);
	delete_body_cache();
	$alert='Static Pages Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelfgroup_id=<?=$_REQUEST['pass_shelfgroup_id']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelfgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=static_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf Menu</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a><br /><br />
 <?		
}
elseif($_REQUEST['fpurpose']=='displayShopShelfGroupAssign') // Assign display static pages to shelf Menu
{
	include ('includes/shelfgroup/list_shelfgroup_display_selshop.php');
}

elseif($_REQUEST['fpurpose']=='save_displayShopShelfGroupAssign') // Save display sataic pages to shelf Menu
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['shelf_group_id']=$_REQUEST['pass_shelfgroup_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['product_shop_shop_id']=$v;
		$db->insert_from_array($insert_array, 'shelf_group_display_shop');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelfgroup_id']);
	delete_body_cache();
	$alert='Shop Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelfgroup_id=<?=$_REQUEST['pass_shelfgroup_id']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelfgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf Menu</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a><br /><br />
 <?		
}
elseif($_REQUEST['fpurpose']=='displayShopShelfGroupUnAssign') //Un assign display static pages from shelf Menu
{
	
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$staticid_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfgroup_id		= $_REQUEST['shelfgroup_id'];
	for($i=0;$i<count($staticid_arr);$i++)
	{
			$id = $staticid_arr[$i];	
			$sql_del = "DELETE FROM shelf_group_display_shop WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfgroup_id);
	$alert = 'Shop unassigned successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	delete_body_cache();
	show_display_shop_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayStaticShelfGroupUnAssign') //Un assign display static pages from shelf Menu
{
	
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$staticid_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfgroup_id		= $_REQUEST['shelfgroup_id'];
	for($i=0;$i<count($staticid_arr);$i++)
	{
			$id = $staticid_arr[$i];	
			$sql_del = "DELETE FROM shelf_group_display_static WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfgroup_id);
	$alert = 'Static Page unassigned successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	delete_body_cache();
	show_display_static_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayCategoryShelfGroupUnAssign') //Un assign display categories from shelf Menu
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfgroup_id		= $_REQUEST['shelfgroup_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM shelf_group_display_category WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfgroup_id);
	$alert = 'Category unassigned successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	delete_body_cache();
	show_display_category_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}

elseif($_REQUEST['fpurpose']=='list_display_productshelf')// List display products of shelf Menu
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
		show_display_product_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayProdShelfGroupAssign') // Assign display products to shelf Menu
{
	include ('includes/shelfgroup/list_shelfgroup_display_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_displayProdShelfGroupAssign') // Save display products to shelf Menu
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['shelf_group_id']=$_REQUEST['pass_shelfgroup_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['products_product_id']=$v;
		$db->insert_from_array($insert_array, 'shelf_group_display_product');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelfgroup_id']);
	delete_body_cache();
	$alert='Product Assigned Successfully';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelfgroup_id=<?=$_REQUEST['pass_shelfgroup_id']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelfgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfprod_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf Menu</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose']=='displayProdShelfGroupUnAssign') //Un assign display products from shelf Menu
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfgroup_id		= $_REQUEST['shelfgroup_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM shelf_group_display_product WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfgroup_id);
	delete_body_cache();
	$alert = 'Product unassigned successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	show_display_product_shelfgroup_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_shelfgroup')//Listing shelfs assigned to group
{	   
        
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
		show_shelf_group_list($_REQUEST['shelfgroup_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='ShelfGroupAssign') // Assign products to shelf Menu
{
	include ('includes/shelfgroup/list_group_selshelf.php');
}
elseif($_REQUEST['fpurpose']=='save_ShelfGroupAssign') // Save products to shelf Menu
{
       $shelf_id_arr = array();
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['shelf_group_id']=$_REQUEST['pass_shelfgroup_id'];
		$insert_array['shelf_shelf_id']=$v;
		$db->insert_from_array($insert_array, 'shelf_group_shelf');
                $shelf_id_arr[] =$v;
	}
        // calling function to send notification mails to seo engineers
        send_support_notification_emails('shelf',array('cur_id'=>$_REQUEST['pass_shelfgroup_id'],'shelf_id_arr'=>$shelf_id_arr));
	// Delete Cache
	delete_shelf_cache($_REQUEST['pass_shelfgroup_id']);
	delete_body_cache();
	$alert='Shelf Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;
			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelfgroup_id=<?=$_REQUEST['pass_shelfgroup_id']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing Page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelfgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfs_tab_td" onclick="show_processing()">Go Back to the Edit this Shelf Menu</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose']=='ShelfGroupUnAssign') //Un assign products from shelf Menu
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfgroup_id		= $_REQUEST['shelfgroup_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM shelf_group_shelf WHERE id=$id";
			$db->query($sql_del);
	}
	// Delete Cache
	delete_shelf_cache($_REQUEST['shelfgroup_id']);
	$alert = 'Shelf unassigned successfully.';
	include ('../includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	delete_body_cache();
	show_shelf_group_list($_REQUEST['shelfgroup_id'],$alert);
	
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update shelf Menu status
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$ids_arr 		= explode('~',$_REQUEST['ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($ids_arr);$i++)
	{
		$update_array				= array();
		$update_array['hide']	    = $new_status;
		$id 						= $ids_arr[$i];
		$db->update_from_array($update_array,'shelf_group',array('id'=>$id));
		// Delete Cache
		//delete_shelfgroup_cache($_REQUEST['id']);
	}
	delete_body_cache();
	$alert = 'Hidden Status changed successfully.';
	include ('../includes/shelfgroup/list_shelfgroup.php');
}
else if($_REQUEST['fpurpose']=='delete') // Delete shelf Menu
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Shelf Menu not selected';
		}
		else
		{   $del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			// get the feature id of shelf
			$sql_feat = "SELECT feature_id 
							FROM 
								features 
							WHERE 
								feature_modulename='mod_shelfgroup'
							LIMIT 
								1";
			$ret_feat = $db->query($sql_feat);
			if($db->num_rows($ret_feat))
			{
				$row_feat = $db->fetch_array($ret_feat);
				$cur_feat_id = $row_feat['feature_id'];
			}
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   
					  $sql_del = "DELETE FROM shelf_group WHERE id=".$del_arr[$i];
					  $db->query($sql_del);
										
					  $sql_del = "DELETE FROM display_settings WHERE features_feature_id = $cur_feat_id AND display_component_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_count ++;					
					  	// Delete Cache
						delete_shelf_cache($del_arr[$i]);
				}	
			}
			 if($del_count > 0)
			 {
			  if($alert) $alert .="<br />";
					  $alert .= $del_count." Shelf Menu(s) Deleted Successfully";
			 }		  
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		include ('../includes/shelfgroup/list_shelfgroup.php');
	

}
else if($_REQUEST['fpurpose']=='add') // New shelf Menu
{
	include("includes/shelfgroup/add_shelfgroup.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit shelf Menu
{
	include_once("functions/console_urls.php");
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
	include("includes/shelfgroup/edit_shelfgroup.php");
	
}
else if($_REQUEST['fpurpose']=='insert') // Save new shelf Menu
{
	if($_REQUEST['Submit'])
	{
    //print_r($_REQUEST);
    	$alert='';
		$fieldRequired = array($_REQUEST['name']);
		$fieldDescription = array('Shelf Menu Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM shelf_group WHERE name = '".trim(add_slash($_REQUEST['name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Shelf Menu Name Already exists ';
			if(!$alert)
			{

			}

		if(!$alert) 
		{    
  		  	$insert_array = array();
			$insert_array['name']				= trim(add_slash($_REQUEST['name']));
			$insert_array['sites_site_id']		= $ecom_siteid;
			$insert_array['hide']				= $_REQUEST['hide'];
			$insert_array['showinall']				= $_REQUEST['showinall'];
			$db->insert_from_array($insert_array, 'shelf_group');
			$insert_id = $db->insert_id();
			// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shelfgroup'";
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
						$layoutid	= $cur_arr[0];
						$layoutcode	= $cur_arr[1];
						$position	= $cur_arr[2];
						$insert_array										= array();
						$insert_array['sites_site_id']						= $ecom_siteid;
						$insert_array['features_feature_id']				= $cur_featid;
						$insert_array['display_position']					= $position;
						$insert_array['themes_layouts_layout_id']			= $layoutid;
						$insert_array['layout_code']						= add_slash($layoutcode);
						$insert_array['display_title']						= add_slash(trim($_REQUEST['name']));
						$insert_array['display_order']						= 0;
						$insert_array['display_component_id']				= $insert_id;
						$db->insert_from_array($insert_array,'display_settings');
					}
				}
				// Completed the section to entry details to display_settings table
			// Delete Cache
			delete_body_cache();
			recreate_entire_websitelayout_cache();
                        // calling function to send notification mails to seo engineers
                        send_support_notification_emails('shelfgroup',array('cur_id'=>$insert_id,'prod_id_arr'=>array()));
			$alert .= '<br><span class="redtext"><b>Shelf menu added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing Shelf Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&shelfgroup_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Shelf Menu</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a>
			<?
		}	
		else
		{
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			$edit_id = $_REQUEST['checkbox'][0];
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/shelfgroup/add_shelfgroup.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf Menu
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['name']);
		$fieldDescription = array('Shelf Menu Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM shelf_group WHERE name = '".trim(add_slash($_REQUEST['name']))."' AND sites_site_id=$ecom_siteid AND id<>".$_REQUEST['shelfgroup_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Shelf Menu Name Already exists '; 
					
	
		if(!$alert) {
		 	$update_array = array();
			$update_array['name']						= trim(add_slash($_REQUEST['name']));
			$update_array['sites_site_id']				= $ecom_siteid;
			$update_array['hide']						= $_REQUEST['hide'];
			$update_array['showinall']					= $_REQUEST['showinall'];
		
			
			$db->update_from_array($update_array, 'shelf_group', 'id', $_REQUEST['shelfgroup_id']);

			// Section to make entry to display_settings table
			if(count($_REQUEST['display_id']))
			{
				
					// Find the feature details for module mod_shelf from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shelfgroup'";
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
						$curr_arr 		= explode("_",$_REQUEST['display_id'][$i]);
						$dispid			= $curr_arr[0];
						$sel_dispid[] 	= $dispid;
						// Check whether this disp id is already selected for this category group
						$sql_check = "SELECT display_id FROM display_settings WHERE display_id=$dispid AND 
										sites_site_id=$ecom_siteid AND features_feature_id = $cur_featid AND 
										display_component_id=".$_REQUEST['shelfgroup_id'];
						$ret_check = $db->query($sql_check);
						
						if ($db->num_rows($ret_check)==0 or $dispid==0)
						{
							$layoutid		= $curr_arr[1];
							$layoutcode		= $curr_arr[2];
							$positions		= $curr_arr[3];
							$insert_array										= array();
							$insert_array['sites_site_id']						= $ecom_siteid;
							$insert_array['features_feature_id']				= $cur_featid;
							$insert_array['display_position']					= $positions;
							$insert_array['themes_layouts_layout_id']			= $layoutid;
							$insert_array['layout_code']						= add_slash($layoutcode);
							$insert_array['display_title']						= add_slash(trim($_REQUEST['name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $_REQUEST['shelfgroup_id'];
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
						
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=".$_REQUEST['shelfgroup_id']." AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				
			}
			
			 		// case if update the title in display settings  for shelfgroup 
                        if($_REQUEST['updatewebsitelayout']) 
                        {
                            // Get the feature id of mod_shelfgroup from features table
                            $sql_feat = "SELECT feature_id 
                                            FROM 
                                                features 
                                            WHERE 
                                                feature_modulename ='mod_shelfgroup' 
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
                                                display_title='".trim(add_slash($_REQUEST['name']))."' 
                                            WHERE 
                                                sites_site_id = $ecom_siteid 
                                                AND features_feature_id = $cur_featid 
                                                AND display_component_id = ".$_REQUEST['shelfgroup_id'];
                            $db->query($sql_update);
                        }
                       
			// Delete Cache
			delete_shelf_cache($_REQUEST['shelfgroup_id']);	
			delete_body_cache();
			recreate_entire_websitelayout_cache();
			$alert .= '<br><span class="redtext"><b>Shelf Menu Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=shelfgroup&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Shelf Menu Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=edit&shelfgroup_id=<?=$_REQUEST['shelfgroup_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Shelf Menu Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfgroup&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to Add New Shelf Menu</a>
		<?	
		}
		else {
			$alert = 'Error! '.$alert;
			//$alert .= '';
		?>
		
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/shelfgroup/ajax/shelfgroup_ajax_functions.php');
				$edit_id = $_REQUEST['checkbox'][0];
			include("includes/shelfgroup/edit_shelf.php");
		}
	}
}
?>
