<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/sizechart/list_sizechart.php");
}

elseif($_REQUEST['fpurpose']=='save_order')
{
	//print_r($_REQUEST);
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['heading_sortorder']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_sizechart_heading',array('heading_id'=>$IdArr[$i]));
			// Delete cache
		delete_statgroup_cache($IdArr[$i]);
	}
	
	$alert = 'Order saved successfully.';
	include ('../includes/sizechart/list_sizechart.php');
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$group_ids_arr 		= explode('~',$_REQUEST['heading_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($group_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['heading_hide']	= $new_status;
			$group_id 						= $group_ids_arr[$i];	
			$db->update_from_array($update_array,'product_sizechart_heading',array('heading_id'=>$group_id));
			// Delete cache
			delete_statgroup_cache($group_id);
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/sizechart/list_sizechart.php');
		
}

else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Heading not selected';
		}
		else
		{
					
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM product_sizechart_heading WHERE heading_id=".$del_arr[$i]." AND sites_site_id = ".$ecom_siteid;
					  $db->query($sql_del);
					  $sql_del_map = "DELETE FROM product_sizechart_heading_product_map WHERE heading_id=".$del_arr[$i]." AND sites_site_id = ".$ecom_siteid;
					  $db->query($sql_del_map);
					  $sql_del_values = "DELETE FROM product_sizechart_values WHERE heading_id=".$del_arr[$i]." AND sites_site_id = ".$ecom_siteid;
					  $db->query($sql_del_map);
				}	
			}
			$alert = "Product Specification Heading deleted Sucessfully";
		}
		include ('../includes/sizechart/list_sizechart.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	include("includes/sizechart/add_sizechart.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$newsletter_id = $_REQUEST['checkbox'][0];
	include("includes/sizechart/edit_sizechart.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{

	//Function to validate forms
	validate_forms();
	

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			$insert_array						= array();
			$insert_array['sites_site_id'] 		= $ecom_siteid;
			$insert_array['heading_title'] 		= addslashes($_REQUEST['heading_title']);
			$insert_array['heading_hide']		= $_REQUEST['heading_hide'];
			$insert_array['heading_sortorder']	= addslashes($_REQUEST['heading_sortorder']);
			
		$db->insert_from_array($insert_array, 'product_sizechart_heading');
		$insert_id = $db->insert_id();
				
	?>
		<br><font color="red"><b>Product Specification Heading Added Successfully</b></font><br>
		<br />
		<a class="smalllink" href="home.php?request=sizechart&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Product Specification Headings Listing page</a><br />
		<br />
		<a class="smalllink" href="home.php?request=sizechart&fpurpose=edit&heading_id=<?php echo $insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Product Specification Heading  Edit Page</a><br />
		<br />
		<a class="smalllink" href="home.php?request=sizechart&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to Product Specification  Add page</a><br />
	<?php
	}
  }
}
else if($_REQUEST['fpurpose'] == 'update_sizechart') {  // for updating the size chart

	if($_REQUEST['heading_id'])
	{
		//Function to validate forms
		validate_forms();
		if (!$alert)
		{
			$update_array						= array();
			$update_array['sites_site_id'] 		= $ecom_siteid;
			$update_array['heading_title']		= addslashes($_REQUEST['heading_title']);
			$update_array['heading_hide']		= addslashes($_REQUEST['heading_hide']);
			$update_array['heading_sortorder'] 	= addslashes($_REQUEST['heading_sortorder']); 
		$db->update_from_array($update_array, 'product_sizechart_heading', array('heading_id' => $_REQUEST['heading_id'] , 'sites_site_id' => $ecom_siteid));
			?>
			<br><font color="red"><b>Size Chart Updated Successfully</b></font><br>
			<br />
			<a class="smalllink" href="home.php?request=sizechart&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Product Specification Listing page</a><br />
			<br />
			<a class="smalllink" href="home.php?request=sizechart&fpurpose=edit&heading_id=<?=$_REQUEST['heading_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Product Specification Heading Edit Page</a><br />
			<br />
			<a class="smalllink" href="home.php?request=sizechart&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to Product Specification Add page</a><br />
			<br />
			<?php
		}
		else
		{
		?>
			<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Product Specification Heading Id</font><br />
		<br /><a class="smalllink" href="home.php?request=property&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_approvestatus=<?php echo $_REQUEST['cbo_approvestatus']?>&cbo_cat=<?php echo $_REQUEST['cbo_cat']?>&search_cname=<?=$_REQUEST['search_cname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Listing page</a><br /><br />
		
	<?php	
	} //// updating adverts ends



}



function validate_forms()
{
	global $alert,$db;
	
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['heading_title']);
		$fieldDescription 	= array('Product Specification Heading');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
}

	
?>
