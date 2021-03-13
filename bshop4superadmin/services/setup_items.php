<?php
if($_REQUEST['fpurpose'] == '')
{
	$group_id = $_REQUEST['group_id'];
	$theme_id = $_REQUEST['theme_id'];
	include("includes/setup_items/list_setupitems.php");
}
else if($_REQUEST['fpurpose'] == 'add')
{
	include("includes/setup_items/add_setupitems.php");
}
else if($_REQUEST['fpurpose'] == 'edit')
{
	include("includes/setup_items/edit_setupitems.php");
}
else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['item_title'],$_REQUEST['layout']);
		$fieldDescription = array('Group Title','Layout Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT item_id 
								FROM 
									setup_items 
								WHERE 
									themes_theme_id=".$_REQUEST['theme_id']." 
									AND setup_groups_group_id=".$_REQUEST['group_id']." 
									AND item_title='".add_slash($_REQUEST['item_title'])."' 
								LIMIT 
									1";
		$ret_check = $db->query($sql_check);
		if ($db->num_rows($ret_check))
		{
			$alert = 'Item already exists for current Group';
		}
		if(!$alert) {
			// Checking for duplicate card names
			$order = (!is_numeric($_REQUEST['item_order']))?0:$_REQUEST['item_order'];
			$insert_array 							= array();
			$insert_array['item_title']				= add_slash($_REQUEST['item_title']); 
			$insert_array['item_order']				= $order;
			$insert_array['setup_groups_group_id']	= add_slash($_REQUEST['group_id']);
			$insert_array['themes_theme_id']		= add_slash($_REQUEST['theme_id']);
			
			$insert_array['layout_code']			= add_slash($_REQUEST['layout']); 
			$insert_array['item_template']			= add_slash($_REQUEST['template']); 
			
			$db->insert_from_array($insert_array, 'setup_items');
			$insert_id = $db->insert_id();
			
			$tagsql = "SELECT tag_id, tag_text FROM setup_tags ORDER BY tag_order";
			$tagres = $db->query($tagsql);
			while($tagrow = $db->fetch_array($tagres)) 
			{  
				$tag_id = $tagrow['tag_id'];
				$tagval = "color_".$tag_id;
				$insertarray['setup_items_item_id']		    = $insert_id; 
				$insertarray['setup_tags_tag_id']			= $tag_id; 
				$insertarray['item_value']					= $_REQUEST[$tagval];
				$db->insert_from_array($insertarray, 'setup_items_tags_values');
			}	
			
			$alert = '<center><font color="red"><b>Item Added Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_items&src_title=<?=$_REQUEST['src_title']?>&group_id=<?php echo $_REQUEST['group_id']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_items&fpurpose=edit&item_id=<?=$insert_id?>&src_title=<?=$_REQUEST['src_title']?>&group_id=<?php echo $_REQUEST['group_id']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Wizard Item</a><br /><br />
			<a href="home.php?request=setup_items&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&src_title=<?=$_REQUEST['src_title']?>&group_id=<?php echo $_REQUEST['group_id']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Item</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_items/add_setupitems.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert = '';
		$fieldRequired = array($_REQUEST['item_title'],$_REQUEST['layout']);
		$fieldDescription = array('Group Title','Layout Code');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		// Checking for duplicate card names
		$sql_check = "SELECT count(*) as cnt 
									FROM 
										setup_items 
									WHERE 
										item_title='".add_slash($_REQUEST['item_title'])."'
										AND item_id<>".$_REQUEST['item_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		if($row_check['cnt'] > 0) {
			$alert = 'Sorry!! Item already exists';
		}
		if(!$alert) {
			$order = (!is_numeric($_REQUEST['item_order']))?0:$_REQUEST['item_order'];
			$update_array = array();
			
			$update_array['item_title']			= add_slash($_REQUEST['item_title']); 
			$update_array['item_order']			= $order;
			$update_array['themes_theme_id']	= add_slash($_REQUEST['theme_id']);
			
		//	$update_array['item_forecolor']			= add_slash($_REQUEST['forecolor']); 
		//	$update_array['item_bgcolor']			= add_slash($_REQUEST['bgcolor']); 
			$update_array['layout_code']			= add_slash($_REQUEST['layout']); 
			$update_array['item_template']			= add_slash($_REQUEST['template']); 
			
			
			$db->update_from_array($update_array, 'setup_items', array('item_id'=>$_REQUEST['item_id']));
			
			$delSQl = "DELETE FROM setup_items_tags_values WHERE setup_items_item_id='".$_REQUEST['item_id']."'";
			$delRes = $db->query($delSQl);
			
			
			$tagsql = "SELECT tag_id, tag_text FROM setup_tags ORDER BY tag_order";
			$tagres = $db->query($tagsql);
			while($tagrow = $db->fetch_array($tagres)) 
			{  
				$tag_id = $tagrow['tag_id'];
				$tagval = "color_".$tag_id;
				$insertarray['setup_items_item_id']		    = $_REQUEST['item_id']; 
				$insertarray['setup_tags_tag_id']			= $tag_id; 
				$insertarray['item_value']					= $_REQUEST[$tagval];
				$db->insert_from_array($insertarray, 'setup_items_tags_values');
			}	
			
		/*	$tagsql = "SELECT tag_id, tag_text FROM setup_tags ORDER BY tag_order";
			$tagres = $db->query($tagsql);
			while($tagrow = $db->fetch_array($tagres)) 
			{  
				$tag_id = $tagrow['tag_id'];
				$tagval = "color_".$tag_id;
				$updatearray['setup_items_item_id']		    = $_REQUEST['item_id']; 
				$updatearray['setup_tags_tag_id']			= $tag_id; 
				$updatearray['item_value']					= $_REQUEST[$tagval];
				
				$db->update_from_array($updatearray, 'setup_items_tags_values', array('setup_items_item_id'=>$_REQUEST['item_id'],'setup_tags_tag_id'=>$tag_id));
				
			}	
			*/
			$alert = '<center><font color="red"><b>Setup Items updated Successfully</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=setup_items&src_title=<?=$_REQUEST['src_title']?>&group_id=<?=$_REQUEST['group_id']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=setup_items&fpurpose=edit&item_id=<?=$_REQUEST['item_id']?>&group_id=<?=$_REQUEST['group_id']?>&src_title=<?=$_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Setup Item</a><br /><br />
			<a href="home.php?request=setup_items&fpurpose=add&src_title=<?=$_REQUEST['src_title']?>&group_id=<?=$_REQUEST['group_id']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Setup Item</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/setup_items/edit_setupitems.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['item_id']) {
		$alert_del = '';
		// Check whether any items exists for current group
		$sql_items = "DELETE
								FROM 
									setup_items 
								WHERE 
									setup_groups_group_id=".$_REQUEST['group_id']."
									AND  item_id = ".$_REQUEST['item_id']."
								";
		$ret_check = $db->query($sql_items);
		
	 $sql_items = "DELETE
								FROM 
									setup_items_tags_values 
								WHERE 
									setup_items_item_id = ".$_REQUEST['item_id']."
								";
		$ret_check = $db->query($sql_items);

		
	
	}
	include("includes/setup_items/list_setupitems.php");
}

?>