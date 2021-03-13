<?php
if($_REQUEST['fpurpose']=='')
{
	if($_REQUEST['Save'])
	{
		$update_array = array();
		$update_array['featured_desc']=add_slash($_REQUEST['featured_desc'],false);
		$update_array['featured_showimage']=$_REQUEST['featured_showimage'];
		$update_array['featured_showtitle']=$_REQUEST['featured_showtitle'];
		$update_array['featured_showshortdescription']=$_REQUEST['featured_showshortdescription'];
		$update_array['featured_showprice']=$_REQUEST['featured_showprice'];
		$update_array['featured_showimagetype']=$_REQUEST['featured_showimagetype'];

		$db->update_from_array($update_array, 'product_featured', 'sites_site_id', $ecom_siteid);
		if(count($_REQUEST['display_id']))
		{
			
			// Find the feature details for module mod_survey from features table
			$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_featured'";
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
								sites_site_id=$ecom_siteid AND features_feature_id = $cur_featid ";
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
					$insert_array['display_title']						= 'Featured Product';
					$insert_array['display_order']						= 0;
					$insert_array['display_component_id']				= 0;
					$db->insert_from_array($insert_array,'display_settings');
					$insertid 		= $db->insert_id();
					//echo $insertid;
					$sel_dispid[] 	= $insertid;
				}	
			}
			// Delete all those entries from display setting corresponding to current category group which where there
			// previously but not existing now
			$sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
						features_feature_id=$cur_featid AND 
						display_id NOT IN (".implode(",",$sel_dispid).")";
			$ret_del = $db->query($sql_del);
		}	
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		$alert='';
		$alert .= "Details Saved";
	}
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	//include_once("classes/fckeditor.php");
	include("includes/featured/list_featured.php");
}
else if($_REQUEST['fpurpose']=='assign')
{
	include("includes/featured/list_products.php");
}
else if($_REQUEST['fpurpose']=='save_selected')
{
	include_once("functions/functions.php");
	include_once('session.php');
	include_once("config.php");	
	//include_once("classes/fckeditor.php");
	$sql_del = "DELETE FROM product_featured WHERE sites_site_id=".$ecom_siteid;
	$db->query($sql_del);
	//$insert_array = array();
	foreach($_REQUEST['checkbox'] as $v)
		{
			$insert_array=array();
			$insert_array['products_product_id']=$v;
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'product_featured');
		}
			$sql_feat = "SELECT feature_id,feature_name FROM features WHERE feature_modulename ='mod_featured'";
			$ret_feat = $db->query($sql_feat);
			
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$cur_featid	= $row_feat['feature_id'];
			}
			
					$sql_chk_menu = "SELECT menu_id FROM site_menu WHERE features_feature_id = ".$cur_featid;
					$res_chk_menu = $db->query($sql_chk_menu);
					$if_menu_exists = $db->num_rows($res_chk_menu);
					if(!$if_menu_exists){
					$sql_insert_menu = "INSERT INTO site_menu (sites_site_id,features_feature_id,menu_title) VALUES ($ecom_siteid,$cur_featid,'".$row_feat['feature_name']."')";
					$db->query($sql_insert_menu);
					}
					
					$sql_themes = "SELECT featuredproduct_positions FROM themes WHERE theme_id=$ecom_themeid";
					$ret_themes = $db->query($sql_themes);
					if ($db->num_rows($ret_themes))
					{
						$row_themes = $db->fetch_array($ret_themes);
						$pos_arr	= explode(",",$row_themes['featuredproduct_positions']);
					}
			
					$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid";
					$ret_layouts = $db->query($sql_layouts);
			while($row_layouts = $db->fetch_array($ret_layouts))
			{
			  for($i=0;$i<count($pos_arr); $i++){
			  		$insert_array										= array();
					$insert_array['sites_site_id']						= $ecom_siteid;
					$insert_array['features_feature_id']				= $cur_featid;
					$insert_array['display_position']					= $pos_arr[$i];
					$insert_array['themes_layouts_layout_id']			= $row_layouts['layout_id'];
					$insert_array['layout_code']						= $row_layouts['layout_code'];
					$insert_array['display_title']						= 'Featured Product';
					$insert_array['display_order']						= 0;
					$insert_array['display_component_id']				= 0;
					$db->insert_from_array($insert_array,'display_settings');
					$insertid 		= $db->insert_id();
					$sel_dispid[] 	= $insertid;
					}
			 }
			 $sql_del = "DELETE FROM display_settings WHERE sites_site_id=$ecom_siteid AND 
						features_feature_id=$cur_featid AND 
						display_id NOT IN (".implode(",",$sel_dispid).")";
			 $ret_del = $db->query($sql_del);
	delete_body_cache();
	recreate_entire_websitelayout_cache();
        // calling function to send notification mails to seo engineers
        send_support_notification_emails('featured',array());
	$alert='';
	$alert .= "Featured Product Assigned";
        $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	//include_once("classes/fckeditor.php");
	include("includes/featured/list_featured.php");

	
}
else if($_REQUEST['fpurpose']=='delete')
{
    include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename ='mod_featured'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$cur_featid	= $row_feat['feature_id'];
			}
	$sql_del = "DELETE FROM product_featured WHERE sites_site_id=".$ecom_siteid;
	$db->query($sql_del);
	$sql_del_display = "DELETE FROM display_settings WHERE sites_site_id=".$ecom_siteid." AND features_feature_id=".$cur_featid;
	$db->query($sql_del_display);
	delete_body_cache();
	recreate_entire_websitelayout_cache();
	$alert='';
	$alert .= "<span class=\"redtext\">Featured Product Removed</span>";
	include("../includes/featured/list_featured.php");
	
}
?>