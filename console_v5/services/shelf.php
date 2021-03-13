<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/shelf/list_shelf.php");
}

elseif($_REQUEST['fpurpose']=='save_shelf_order') // Shelf order 
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
		$db->update_from_array($update_array,'product_shelf',array('shelf_id'=>$IdArr[$i]));
		// Delete Cache
		delete_shelf_cache($id);	
	}
	delete_body_cache();
	$alert = 'Order saved successfully.';
	include ('../includes/shelf/list_shelf.php');
}
elseif($_REQUEST['fpurpose']=='save_order') // Products order within a shelf
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['ch_ids']);
	$OrderArr=explode('~',$_REQUEST['ch_order']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['product_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_shelf_product',array('id'=>$IdArr[$i]));
	}
	// Delete Cache
	delete_shelf_cache($_REQUEST['shelf_id']);	
	$alert = 'Order saved successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	delete_body_cache();
	show_product_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_display_categoryshelf')// List display categories of shelf
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelf/ajax/shelf_ajax_functions.php');
		show_display_category_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayCategoryShelfAssign') // Assign display categories to shelf
{
	include ('includes/shelf/list_shelf_display_selcategory.php');
}
elseif($_REQUEST['fpurpose']=='save_displayCategoryShelfAssign') // Save display categories to shelf
{
	foreach($_REQUEST['checkbox'] as $v)
		{
			$insert_array=array();
			$insert_array['product_shelf_shelf_id']=$_REQUEST['pass_shelf_id'];
			$insert_array['sites_site_id']=$ecom_siteid;
			$insert_array['product_categories_category_id']=$v;
			$db->insert_from_array($insert_array, 'product_shelf_display_category');
			
		}
		delete_shelf_cache($_REQUEST['pass_shelf_id']);	
		delete_body_cache();
		$alert='Category Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		//echo $alert;		
		?>
		
		<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelf_id=<?=$_REQUEST['pass_shelf_id']?>" onclick="show_processing()">Go Back to the Shelf Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelf_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfcategories_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose'] =='list_shelf_maininfo')// Case of listing main info for category groups
{
	include_once("functions/console_urls.php");
	$curtab	= 'main_tab_td';
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/shelf/ajax/shelf_ajax_functions.php');
	include("includes/shelf/edit_shelf.php");
}
elseif($_REQUEST['fpurpose']=='list_display_staticshelf')// List display static pages of shelf
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	show_display_static_shelf_list($_REQUEST['shelf_id'],$alert);
} 
elseif($_REQUEST['fpurpose']=='list_display_shopshelf')// List display static pages of shelf
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	show_display_shop_shelf_list($_REQUEST['shelf_id'],$alert);
} 
elseif($_REQUEST['fpurpose']=='list_display_settings')// List display static pages of shelf
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	show_display_settings($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='save_edit_settings') // case of coming to save the display settings for category using ajax
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	$alert='';
	if ($alert=='')
	{
		if($_REQUEST['shelf_showimage']=='' &&  $_REQUEST['shelf_showtitle']=='' &&   
		   $_REQUEST['shelf_showshortdescription']=='' &&  $_REQUEST['shelf_showprice']=='' &&  $_REQUEST['shelf_showrating']==''  &&  $_REQUEST['shelf_showbonuspoints']=='')  {
				$alert = "Sorry! Please Check any of Product Items to Display ";	
		   } 
	}
	if ($alert=='')
	{
		$update_array								= array();
		$update_array['sites_site_id']				= $ecom_siteid;
		$update_array['shelf_displaytype']			= $_REQUEST['shelf_displaytype'];
		$update_array['shelf_showimage']			= ($_REQUEST['shelf_showimage'])?1:0;;
		$update_array['shelf_showdescription']		= ($_REQUEST['shelf_showdescription'])?1:0;;
		$update_array['shelf_showprice']			= ($_REQUEST['shelf_showprice'])?1:0;;
		$update_array['shelf_showtitle']			= ($_REQUEST['shelf_showtitle'])?1:0;;
		$update_array['shelf_showrating']			= ($_REQUEST['shelf_showrating'])?1:0;
		$update_array['shelf_showbonuspoints']		= ($_REQUEST['shelf_showbonuspoints'])?1:0;
		$update_array['shelf_currentstyle']			= $_REQUEST['shelf_currentstyle'];
		$db->update_from_array($update_array, 'product_shelf', 'shelf_id', $_REQUEST['shelf_id']);		
		// Calling function to clear category groups of current category
		delete_shelf_cache($_REQUEST['shelf_id']);
		
		// Completed the section to entry details to product_categorygroup_category
		$alert = 'Display Settings Updated Successfully';
	}
	delete_body_cache();
	show_display_settings($_REQUEST['shelf_id'],$alert);	
}
elseif($_REQUEST['fpurpose']=='displayStaticShelfAssign') // Assign display static pages to shelf
{
	include ('includes/shelf/list_shelf_display_selstaticpages.php');
}

elseif($_REQUEST['fpurpose']=='save_displayStaticShelfAssign') // Save display sataic pages to shelf
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['product_shelf_shelf_id']=$_REQUEST['pass_shelf_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['static_pages_page_id']=$v;
		$db->insert_from_array($insert_array, 'product_shelf_display_static');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelf_id']);
	delete_body_cache();
	$alert='Static Pages Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelf_id=<?=$_REQUEST['pass_shelf_id']?>" onclick="show_processing()">Go Back to the Shelf Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelf_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=static_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf</a><br /><br />
 <?		
}
elseif($_REQUEST['fpurpose']=='displayShopShelfAssign') // Assign display static pages to shelf
{
	include ('includes/shelf/list_shelf_display_selshop.php');
}

elseif($_REQUEST['fpurpose']=='save_displayShopShelfAssign') // Save display sataic pages to shelf
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['product_shelf_shelf_id']=$_REQUEST['pass_shelf_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['product_shop_shop_id']=$v;
		$db->insert_from_array($insert_array, 'product_shelf_display_shop');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelf_id']);
	delete_body_cache();
	$alert='Shop Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelf_id=<?=$_REQUEST['pass_shelf_id']?>" onclick="show_processing()">Go Back to the Shelf Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelf_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf</a><br /><br />
 <?		
}
elseif($_REQUEST['fpurpose']=='displayShopShelfUnAssign') //Un assign display static pages from shelf
{
	
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$staticid_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($staticid_arr);$i++)
	{
			$id = $staticid_arr[$i];	
			$sql_del = "DELETE FROM product_shelf_display_shop WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfid);
	$alert = 'Shop unassigned successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	delete_body_cache();
	show_display_shop_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayStaticShelfUnAssign') //Un assign display static pages from shelf
{
	
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$staticid_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($staticid_arr);$i++)
	{
			$id = $staticid_arr[$i];	
			$sql_del = "DELETE FROM product_shelf_display_static WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfid);
	$alert = 'Static Page unassigned successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	delete_body_cache();
	show_display_static_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayCategoryShelfUnAssign') //Un assign display categories from shelf
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shelf_display_category WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfid);
	$alert = 'Category unassigned successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	delete_body_cache();
	show_display_category_shelf_list($_REQUEST['shelf_id'],$alert);
}

elseif($_REQUEST['fpurpose']=='list_display_productshelf')// List display products of shelf
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelf/ajax/shelf_ajax_functions.php');
		show_display_product_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='displayProdShelfAssign') // Assign display products to shelf
{
	
	include ('includes/shelf/list_shelf_display_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_displayProdShelfAssign') // Save display products to shelf
{
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['product_shelf_shelf_id']=$_REQUEST['pass_shelf_id'];
		$insert_array['sites_site_id']=$ecom_siteid;
		$insert_array['products_product_id']=$v;
		$db->insert_from_array($insert_array, 'product_shelf_display_product');
		
	}
	delete_shelf_cache($_REQUEST['pass_shelf_id']);
	delete_body_cache();
	$alert='Product Assigned Successfully';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelf_id=<?=$_REQUEST['pass_shelf_id']?>" onclick="show_processing()">Go Back to the Shelf Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelf_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfprod_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose']=='displayProdShelfUnAssign') //Un assign display products from shelf
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shelf_display_product WHERE id=$id";
			$db->query($sql_del);
			
	}
	delete_shelf_cache($shelfid);
	delete_body_cache();
	$alert = 'Product unassigned successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	show_display_product_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_productshelf')//Listing products assigned to shelf
{	   
        
	    include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shelf/ajax/shelf_ajax_functions.php');
		show_product_shelf_list($_REQUEST['shelf_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='prodShelfAssign') // Assign products to shelf
{
	include ('includes/shelf/list_shelf_selproduct.php');
}
elseif($_REQUEST['fpurpose']=='save_prodShelfAssign') // Save products to shelf
{
       $prod_id_arr = array();
	foreach($_REQUEST['checkbox'] as $v)
	{
		$insert_array=array();
		$insert_array['product_shelf_shelf_id']=$_REQUEST['pass_shelf_id'];
		$insert_array['products_product_id']=$v;
		$db->insert_from_array($insert_array, 'product_shelf_product');
                $prod_id_arr[] =$v;
	}
        // calling function to send notification mails to seo engineers
        send_support_notification_emails('shelf',array('cur_id'=>$_REQUEST['pass_shelf_id'],'prod_id_arr'=>$prod_id_arr));
	// Delete Cache
	delete_shelf_cache($_REQUEST['pass_shelf_id']);
	delete_body_cache();
	$alert='Product Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;
			
	?>
	<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&shelf_id=<?=$_REQUEST['pass_shelf_id']?>" onclick="show_processing()">Go Back to the Shelf Listing Shelves</a><br /><br />
		<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shelf_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=products_tab_td" onclick="show_processing()">Go Back to the Edit  this Shelf</a><br /><br />
            <a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to Add New Shelf</a><br /><br />
<?		
}
elseif($_REQUEST['fpurpose']=='prodShelfUnAssign') //Un assign products from shelf
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
	$shelfid		= $_REQUEST['shelf_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shelf_product WHERE id=$id";
			$db->query($sql_del);
	}
	// Delete Cache
	delete_shelf_cache($_REQUEST['shelf_id']);
	$alert = 'Product unassigned successfully.';
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	delete_body_cache();
	show_product_shelf_list($_REQUEST['shelf_id'],$alert);
	
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update shelf status
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$shelf_ids_arr 		= explode('~',$_REQUEST['shelf_ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($shelf_ids_arr);$i++)
	{
		$update_array					= array();
		$update_array['shelf_hide']	= $new_status;
		$shelf_id 						= $shelf_ids_arr[$i];	
		$db->update_from_array($update_array,'product_shelf',array('shelf_id'=>$shelf_id));
		// Delete Cache
		delete_shelf_cache($_REQUEST['shelf_id']);
	}
	delete_body_cache();
	$alert = 'Hidden Status changed successfully.';
	include ('../includes/shelf/list_shelf.php');
}
else if($_REQUEST['fpurpose']=='delete') // Delete shelf
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Shelf not selected';
		}
		else
		{   $del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			// get the feature id of shelf
			$sql_feat = "SELECT feature_id 
							FROM 
								features 
							WHERE 
								feature_modulename='mod_shelf' 
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
					   
					  $sql_del = "DELETE FROM product_shelf WHERE shelf_id=".$del_arr[$i];
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
					  $alert .= $del_count." Shelf(s) Deleted Successfully";
			 }		  
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		include ('../includes/shelf/list_shelf.php');
	

}
else if($_REQUEST['fpurpose']=='add') // New shelf
{
	include("includes/shelf/add_shelf.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit shelf
{
	include_once("functions/console_urls.php");
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/shelf/ajax/shelf_ajax_functions.php');
	include("includes/shelf/edit_shelf.php");
	
}
else if($_REQUEST['fpurpose']=='insert') // Save new shelf
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['shelf_name']);
		$fieldDescription = array('Shelf Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM product_shelf WHERE shelf_name = '".trim(add_slash($_REQUEST['shelf_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Shelf Name Already exists '; 
			if(!$alert)
			{
				if ($_REQUEST['shelf_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['shelf_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['shelf_displayenddate'],'normal','-'))
							$alert = 'Sorry!! Start or End Date is Invalid';
				}		
			}
		if ($alert=='')
			{
				if($_REQUEST['shelf_showimage']=='' &&  $_REQUEST['shelf_showtitle']=='' &&   
				   $_REQUEST['shelf_showdescription']=='' &&  $_REQUEST['shelf_showprice']=='' && $_REQUEST['shelf_showrating']=='' && $_REQUEST['shelf_showbonuspoints']=='' )  {
				   		$alert = "Sorry! Please Check any of Fields Items to Display in Shelf ";	
				   } 
				
			}	
		if(!$alert) 
		{    
		   if(!is_numeric(add_slash($_REQUEST['shelf_order'])) || $_REQUEST['shelf_order']=='')
			{
			 $order = 0;
			}
			else
			{
			$order = add_slash($_REQUEST['shelf_order']);
			}
			$insert_array = array();
			$insert_array['shelf_name']					= trim(add_slash($_REQUEST['shelf_name']));
			$insert_array['sites_site_id']				= $ecom_siteid;
			$insert_array['shelf_order']				= $order;
			$insert_array['shelf_hide']					= $_REQUEST['shelf_hide'];
			$insert_array['shelf_displaytype']			= $_REQUEST['shelf_displaytype'];
			$insert_array['shelf_showinall']			= $_REQUEST['shelf_showinall'];
			$insert_array['shelf_showimage']			= ($_REQUEST['shelf_showimage'])?1:0;
			$insert_array['shelf_description']			= add_slash($_REQUEST['shelf_description'],false); 
			$insert_array['shelf_showtitle']			= ($_REQUEST['shelf_showtitle'])?1:0;
			$insert_array['shelf_showdescription']		= ($_REQUEST['shelf_showdescription'])?1:0;
			$insert_array['shelf_showprice']			= ($_REQUEST['shelf_showprice'])?1:0;
			$insert_array['shelf_showrating']			= ($_REQUEST['shelf_showrating'])?1:0;
			$insert_array['shelf_showbonuspoints']		= ($_REQUEST['shelf_showbonuspoints'])?1:0;
			$insert_array['shelf_currentstyle']			= $_REQUEST['shelf_currentstyle'];
			$insert_array['shelf_activateperiodchange']	= $_REQUEST['shelf_activateperiodchange'];
			$exp_shelf_displaystartdate					= explode("-",$_REQUEST['shelf_displaystartdate']);
			$val_shelf_displaystartdate					=  $exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
			$exp_shelf_displayenddate					=  explode("-",$_REQUEST['shelf_displayenddate']);
			$val_shelf_displayenddate					=  $exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
			$val_shelf_displaystartdatetime     		=  $val_shelf_displaystartdate." ".$_REQUEST['shelf_starttime_hr'].":".$_REQUEST['shelf_starttime_mn'].":".$_REQUEST['shelf_starttime_ss'];
			$val_shelf_displayenddatetime  				=  $val_shelf_displayenddate." ".$_REQUEST['shelf_endtime_hr'].":".$_REQUEST['shelf_endtime_mn'].":".$_REQUEST['shelf_endtime_ss'];
			$insert_array['shelf_displaystartdate']		=  $val_shelf_displaystartdatetime;
			$insert_array['shelf_displayenddate']		=  $val_shelf_displayenddatetime;
			$db->insert_from_array($insert_array, 'product_shelf');
			$insert_id = $db->insert_id();
			// Section to make entry to display_settings table
				if(count($_REQUEST['display_id']))
				{
					// Find the feature details for module mod_productcatgroup from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shelf'";
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
						$insert_array['display_title']						= add_slash(trim($_REQUEST['shelf_name']));
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
                        send_support_notification_emails('shelf',array('cur_id'=>$insert_id,'prod_id_arr'=>array()));
			$alert .= '<br><span class="redtext"><b>Shelf added successfully</b></span><br>';
			//echo $alert;
			?>
			<!-- Redirecting to product assign tab after adding shelf starts here -->
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=shelfs&fpurpose=edit&shelf_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&curtab=products_tab_td';
			</script>
			<!--<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing Shelf</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&shelf_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Shelf</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to Add New Shelf</a>-->
			<!-- Redirecting to product assign tab after adding shelf starts here -->
			<?
		}	
		else
		{
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			$edit_id = $_REQUEST['checkbox'][0];
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/shelf/add_shelf.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['shelf_name']);
		$fieldDescription = array('Shelf Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM product_shelf WHERE shelf_name = '".trim(add_slash($_REQUEST['shelf_name']))."' AND sites_site_id=$ecom_siteid AND shelf_id<>".$_REQUEST['shelf_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Shelf Name Already exists '; 
			if(!$alert)
			{
				if ($_REQUEST['shelf_activateperiodchange'])
				{
						if(!is_valid_date($_REQUEST['shelf_displaystartdate'],'normal','-') or !is_valid_date($_REQUEST['shelf_displayenddate'],'normal','-'))
							$alert = 'Sorry!! Start or End Date is Invalid';
				}		
			}
		
	
		if(!$alert) {
		 if(!is_numeric(add_slash($_REQUEST['shelf_order'])) || $_REQUEST['shelf_order']=='')
			{
			 	$order = 0;
			}
			else
			{
				$order = add_slash($_REQUEST['shelf_order']);
			}
			$update_array = array();
			$update_array['shelf_name']					= trim(add_slash($_REQUEST['shelf_name']));
			$update_array['sites_site_id']				= $ecom_siteid;
			$update_array['shelf_order']				= $order;
			$update_array['shelf_hide']					= $_REQUEST['shelf_hide'];
		//	$update_array['shelf_displaytype']			= $_REQUEST['shelf_displaytype'];
			$update_array['shelf_showinall']			= $_REQUEST['shelf_showinall'];
		//	$update_array['shelf_showimage']			= $_REQUEST['shelf_showimage'];
			$update_array['shelf_description']			= add_slash($_REQUEST['shelf_description'],false);
		//	$update_array['shelf_showtitle']			= $_REQUEST['shelf_showtitle'];
		//	$update_array['shelf_showdescription']		= $_REQUEST['shelf_showdescription'];
		//	$update_array['shelf_showprice']			= $_REQUEST['shelf_showprice'];
		//	$update_array['shelf_currentstyle']			= $_REQUEST['shelf_currentstyle'];
			$update_array['shelf_activateperiodchange']	= $_REQUEST['shelf_activateperiodchange'];
			$exp_shelf_displaystartdate					= explode("-",$_REQUEST['shelf_displaystartdate']);
			$val_shelf_displaystartdate					= $exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
			$exp_shelf_displayenddate					= explode("-",$_REQUEST['shelf_displayenddate']);
			$val_shelf_displayenddate					= $exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
			if($_REQUEST['shelf_activateperiodchange']==0){
			$val_shelf_displaystartdate ="0000-00-00";
			$val_shelf_displayenddate ="0000-00-00";
			}
			$val_shelf_displaystartdatetime     =  $val_shelf_displaystartdate." ".$_REQUEST['shelf_starttime_hr'].":".$_REQUEST['shelf_starttime_mn'].":".$_REQUEST['shelf_starttime_ss'];
			$val_shelf_displayenddatetime  		=  $val_shelf_displayenddate." ".$_REQUEST['shelf_endtime_hr'].":".$_REQUEST['shelf_endtime_mn'].":".$_REQUEST['shelf_endtime_ss'];
			$update_array['shelf_displaystartdate']	=$val_shelf_displaystartdatetime;
			$update_array['shelf_displayenddate']		=$val_shelf_displayenddatetime;
			$db->update_from_array($update_array, 'product_shelf', 'shelf_id', $_REQUEST['shelf_id']);

			// Section to make entry to display_settings table
			if(count($_REQUEST['display_id']))
			{
				
					// Find the feature details for module mod_shelf from features table
					$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_shelf'";
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
										display_component_id=".$_REQUEST['shelf_id'];
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
							$insert_array['display_title']						= add_slash(trim($_REQUEST['shelf_name']));
							$insert_array['display_order']						= 0;
							$insert_array['display_component_id']				= $_REQUEST['shelf_id'];
							$db->insert_from_array($insert_array,'display_settings');
							$insertid 		= $db->insert_id();
							$sel_dispid[] 	= $insertid;
						}	
						/*
						// commented for not to update the display title in the display settings table
						else
						{
							$update_array						= array();
							$update_array['display_title']		= add_slash(trim($_REQUEST['shelf_name']));
							$db->update_from_array($update_array,'display_settings',array('display_id'=>$dispid));
						}*/
					}
					// Delete all those entries from display setting corresponding to current category group which where there
					// previously but not existing now
					$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
								features_feature_id=$cur_featid AND display_component_id=".$_REQUEST['shelf_id']." AND 
								display_id NOT IN (".implode(",",$sel_dispid).")";
					$ret_del = $db->query($sql_del);
				
			}
                        // case if update the title in display settings is to be done for current combo deal
                        if($_REQUEST['shelf_updatewebsitelayout']) 
                        {
                            // Get the feature id of mod_combo from features table
                            $sql_feat = "SELECT feature_id 
                                            FROM 
                                                features 
                                            WHERE 
                                                feature_modulename ='mod_shelf' 
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
                                                display_title='".trim(add_slash($_REQUEST['shelf_name']))."' 
                                            WHERE 
                                                sites_site_id = $ecom_siteid 
                                                AND features_feature_id = $cur_featid 
                                                AND display_component_id = ".$_REQUEST['shelf_id'];
                            $db->query($sql_update);
                        }
			// Delete Cache
			delete_shelf_cache($_REQUEST['shelf_id']);	
			delete_body_cache();
			recreate_entire_websitelayout_cache();
			$alert .= '<br><span class="redtext"><b>Shelf Updated successfully</b></span><br>';
			//echo $alert;
			/* Redirecting to product assign tab after adding shelf starts here */
			if($_REQUEST['Submit'] == 'Save & Return')
			{
			?>
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=shelfs&fpurpose=edit&shelf_id=<?=$_REQUEST['shelf_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>';
			</script>
			<?
			}
			else
			{
				echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=shelfs&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Shelf Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfs&fpurpose=edit&shelf_id=<?=$_REQUEST['shelf_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Shelf Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=shelfs&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to Add New Shelf</a>
		<?	}
			/* Redirecting to product assign tab after adding shelf ends here */
		}
		else {
			$alert = 'Error! '.$alert;
			//$alert .= '';
		?>
		
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ("includes/shelf/ajax/shelf_ajax_functions.php");
				$edit_id = $_REQUEST['checkbox'][0];
			include("includes/shelf/edit_shelf.php");
		}
	}
}
elseif($_REQUEST['fpurpose'] =='list_seo')// Case of listing shops to groups
{	
	$shelf_id = $_REQUEST['shelf_id'];	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/shelf/ajax/shelf_ajax_functions.php');
	show_page_seoinfo($shelf_id,$alert);
}
elseif($_REQUEST['fpurpose'] =='save_seo')// Case of listing shops to groups
{ 
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ("../includes/shelf/ajax/shelf_ajax_functions.php");
	$shelf_id = $_REQUEST['shelf_id'];	
	$unq_id = uniqid("");

	 $sql_check = "SELECT id FROM se_shelf_title WHERE sites_site_id=$ecom_siteid AND product_shelf_shelf_id = ".$shelf_id;
	 $sql_keys  = "SELECT se_keywords_keyword_id FROM se_shelf_keywords WHERE product_shelf_shelf_id = ".$shelf_id;
	$tb_name = 'se_shelf_title';
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
		
			$sql_delkey_rel = "DELETE FROM se_shelf_keywords WHERE se_keywords_keyword_id = ".$values." AND product_shelf_shelf_id = ".$shelf_id;
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
			
				$insert_array['product_shelf_shelf_id']	= $shelf_id;
				$insert_array['se_keywords_keyword_id']	= $insert_id;
				$insert_array['uniq_id']				= $unq_id;
				$db->insert_from_array($insert_array, 'se_shelf_keywords');
						
		}
}
//echo "<pre>";print_r($keys_list);die();

//echo $tb_name;echo "<br>";die();
if($row_check['id'] != "" && $row_check['id'] > 0)
{
	if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
	{
		
			$sql_del = "DELETE FROM se_shop_title WHERE id=".$row_check['id'];				
		
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
		
			$insert_array['product_shelf_shelf_id']	= $shelf_id;
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
	//delete_category_cache($category_id);
	//recreate_entire_websitelayout_cache();
	//delete_body_cache();
		
		show_page_seoinfo($shelf_id,$alert);
	/* Button code to save and return starts here */	
}
?>
