<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/product_label_groups/list_product_label_groups.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$group_ids_arr 		= explode('~',$_REQUEST['group_ids']);
	$new_status		= $_REQUEST['ch_status'];
	for($i=0;$i<count($group_ids_arr);$i++)
	{
		$update_array					= array();
		$update_array['group_hide']		= $new_status;
		$group_id						= $group_ids_arr[$i];	
		$db->update_from_array($update_array,'product_labels_group','group_id',$group_id);
	}
	$alert = 'Hidden Status changed successfully.';
	include ('../includes/product_label_groups/list_product_label_groups.php');
}
elseif($_REQUEST['fpurpose']=='save_order')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$group_ids_arr 		= explode('~',$_REQUEST['group_ids']);
	$group_order_arr	= explode('~',$_REQUEST['group_orders']);
	for($i=0;$i<count($group_ids_arr);$i++)
	{
		$update_array					= array();
		$update_array['group_order']	= (is_numeric(trim($group_order_arr[$i])))?trim($group_order_arr[$i]):0;
		$group_id						= $group_ids_arr[$i];	
		$db->update_from_array($update_array,'product_labels_group','group_id',$group_id);
	}
	$alert 	= 'Sort Order Saved successfully.';
	include ('../includes/product_label_groups/list_product_label_groups.php');
}
else if($_REQUEST['fpurpose']=='delete')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Product Label Group(s) not selected';
	}
	else
	{
		$del_count= 0;
		$del_arr = explode("~",$_REQUEST['del_ids']);
		for($i=0;$i<count($del_arr);$i++)
		{
			if(trim($del_arr[$i]))
			{
				$sql_del = "DELETE 
							FROM 
								product_category_product_labels_group_map  
							WHERE 
								product_labels_group_group_id   = ".$del_arr[$i];
				$db->query($sql_del);
				$sql_del = "DELETE 
							FROM 
								product_labels_group_label_map   
							WHERE 
								product_labels_group_group_id   = ".$del_arr[$i];
				$db->query($sql_del);
				$sql_del = "DELETE 
							FROM 
								product_labels_group    
							WHERE 
								group_id   = ".$del_arr[$i]." 
							LIMIT 
								1";
				$db->query($sql_del);
			}	
		}
			$alert =  "Label Group(s) Deleted Succesfully";
	}
	include ('../includes/product_label_groups/list_product_label_groups.php');
}
else if($_REQUEST['fpurpose']=='add')
{
	include("includes/product_label_groups/add_product_label_groups.php");
}
else if($_REQUEST['fpurpose']=='edit')
{  
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	$edit_id = $_REQUEST['checkbox'][0];
	include("includes/product_label_groups/edit_product_label_groups.php");
}
else if($_REQUEST['fpurpose']=='list_group_maininfo')
{  
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_labelgroup_maininfo($_REQUEST['group_id'],$alert);
}
else if($_REQUEST['fpurpose']=='list_categories')
{  
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_category_list($_REQUEST['group_id'],$alert);
}
else if($_REQUEST['fpurpose']=='list_labels')
{  
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_label_list($_REQUEST['group_id'],$alert);
}
else if($_REQUEST['fpurpose']=='displayCategoryAssign')
{ 
	include("includes/product_label_groups/list_labelgroup_selcategory.php");
}
else if($_REQUEST['fpurpose']=='save_displayCategoryAssign')
{ 
	$group_id = $_REQUEST['pass_group_id'];
	foreach($_REQUEST['checkbox'] as $v)
	{
		// Check whether current catgory is already assigned for current group
		$sql_check = "SELECT map_id  
						FROM 
							product_category_product_labels_group_map 
						WHERE 
							product_labels_group_group_id = $group_id 
							AND product_categories_category_id = $v 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0)
		{ 
			$insert_array									= array();
			$insert_array['product_labels_group_group_id']	= $group_id;
			$insert_array['product_categories_category_id']	= $v;
			$db->insert_from_array($insert_array, 'product_category_product_labels_group_map');
		}	
	}
	$alert='Category Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=prod_label_groups&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Product Label Group Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_group_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=cat_tab_td" onclick="show_processing()">Go Back to the Edit this Product Label Group</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
	<?php	
}
elseif($_REQUEST['fpurpose']=='displayCategoryUnAssign') 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
	$group_id	= $_REQUEST['group_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM 
							product_category_product_labels_group_map 
						WHERE 
							map_id = $id 
							AND product_labels_group_group_id  = $group_id 
						LIMIT 
							1";
			$db->query($sql_del);
	}
	$alert = 'Category unassigned successfully.';
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_category_list($group_id,$alert);
}
else if($_REQUEST['fpurpose']=='displayLabelAssign')
{ 
	include("includes/product_label_groups/list_labelgroup_sellabel.php");
}
else if($_REQUEST['fpurpose']=='save_displayLabelAssign')
{ 
	$group_id = $_REQUEST['pass_group_id'];
	foreach($_REQUEST['checkbox'] as $v)
	{
		// Check whether current label is already assigned for current group
		$sql_check = "SELECT map_id  
						FROM 
							product_labels_group_label_map  
						WHERE 
							product_labels_group_group_id = $group_id 
							AND product_site_labels_label_id = $v 
						LIMIT 
							1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check)==0)
		{ 
			$insert_array									= array();
			$insert_array['product_labels_group_group_id']	= $group_id;
			$insert_array['product_site_labels_label_id']	= $v;
			$db->insert_from_array($insert_array, 'product_labels_group_label_map');
		}	
	}
	$alert='Labels Assigned Successfullly';
	$alert = '<center><font color="red"><b>'.$alert;
	$alert .= '</b></font></center>';
	echo $alert;		
	?>
	<br /><a class="smalllink" href="home.php?request=prod_label_groups&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&combo_id=<?=$_REQUEST['pass_combo_id']?>" onclick="show_processing()">Go Back to the Product Label Group Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_group_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=label_tab_td" onclick="show_processing()">Go Back to the Edit this Product Label Group</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=add&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
	<?php	
}
elseif($_REQUEST['fpurpose']=='displayLabelUnAssign') 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$id_arr 	= explode('~',$_REQUEST['del_ids']);
	$group_id	= $_REQUEST['group_id'];
	for($i=0;$i<count($id_arr);$i++)
	{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM 
							product_labels_group_label_map  
						WHERE 
							map_id = $id 
							AND product_labels_group_group_id  = $group_id 
						LIMIT 
							1";
			$db->query($sql_del);
	}
	$alert = 'Labels unassigned successfully.';
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_label_list($group_id,$alert);
}
elseif($_REQUEST['fpurpose']=='save_label_order') 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	if ($_REQUEST['ch_ids'] == '') 
	{
		$alert = 'Sorry Image(s) not selected';
	}
	else
	{
		$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
		$ch_order	= explode("~",$_REQUEST['ch_order']);
		$group_id	= $_REQUEST['group_id'];
		for($i=0;$i<count($ch_arr);$i++)
		{
			$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
			$sql_change = "UPDATE product_labels_group_label_map 
							SET 
								map_order  = ".$chroder." 
								WHERE map_id=".$ch_arr[$i]." 
							LIMIT 
								1";
			$db->query($sql_change);
		}
		$alert = 'Label Order Saved Successfully';
	}	
	include ('../includes/product_label_groups/ajax/productlabelgroup_ajax_functions.php');
	show_label_list($group_id,$alert);
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['group_name']);
		$fieldDescription = array('Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt 
						FROM 
							product_labels_group  
						WHERE 
							group_name = '".trim(add_slash($_REQUEST['group_name']))."' 
							AND sites_site_id=$ecom_siteid 
						LIMIT 
							1";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0)
			$alert = 'Group Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['group_name']		= trim(add_slash($_REQUEST['group_name']));
			$insert_array['group_order']	= (add_slash(is_numeric($_REQUEST['group_order'])))?trim($_REQUEST['group_order']):0;
			$insert_array['group_hide']		= (add_slash($_REQUEST['group_hide']))?1:0;
			$insert_array['group_name_hide']= (add_slash($_REQUEST['group_name_hide']))?1:0;
			$insert_array['sites_site_id']	= $ecom_siteid;
			$db->insert_from_array($insert_array, 'product_labels_group');
			$insert_id = $db->insert_id();
			// ########################################################################################################
			// Making insertion to category-product map
			// ########################################################################################################
			if (count($_REQUEST['category_id']))
			{
				for($i=0;$i<count($_REQUEST['category_id']);$i++)
				{
					$insert_array									= array();
					$insert_array['product_labels_group_group_id ']	= $insert_id;
					$insert_array['product_categories_category_id']	= $_REQUEST['category_id'][$i];
					$db->insert_from_array($insert_array,'product_category_product_labels_group_map');
				}
			}
						
			$alert .= '<font color="red"><b>Product Label Group Added Successfully</b></font>';
			echo $alert;
			?>
			<br /><br /><a class="smalllink" href="home.php?request=prod_label_groups&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=edit&group_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = ' <strong>Error!!</strong> '.$alert;
			include("includes/product_label_groups/add_product_label_groups.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['group_name']);
		$fieldDescription = array('Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt 
						FROM 
							product_labels_group  
						WHERE 
							group_name = '".trim(add_slash($_REQUEST['group_name']))."' 
							AND sites_site_id=$ecom_siteid 
							AND group_id <> ".$_REQUEST['group_id']." 
						LIMIT 
							1";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0)
			$alert = 'Group Name Already exists '; 
		if(!$alert) 
		{
			$update_array = array();
			$update_array['group_name']		= trim(add_slash($_REQUEST['group_name']));
			$update_array['group_order']	= (add_slash(is_numeric($_REQUEST['group_order'])))?trim($_REQUEST['group_order']):0;
			$update_array['group_hide']		= (add_slash($_REQUEST['group_hide']))?1:0;
			$update_array['group_name_hide']= (add_slash($_REQUEST['group_name_hide']))?1:0;
			$update_array['sites_site_id']	= $ecom_siteid;
			$db->update_from_array($update_array, 'product_labels_group','group_id',$_REQUEST['group_id']);
			$alert .= '<font color="red"><b>Product Label Group Updated Successfully</b></font>';
			echo $alert;
			?>
			<br /><br /><a class="smalllink" href="home.php?request=prod_label_groups&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=edit&group_id=<?=$_REQUEST['group_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_label_groups&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = ' <strong>Error!!</strong> '.$alert;
			include("includes/product_label_groups/edit_product_label_groups.php");
		}
	}
}
?>