<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/company_type/list_comptype.php");
}

elseif($_REQUEST['fpurpose']=='save_comptype_order') // Shelf order 
{
	
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	//print_r($OrderArr);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array					= array();
		$update_array['comptype_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'general_settings_sites_customer_company_types',array('comptype_id'=>$IdArr[$i]));
		
	}
	
	$alert = 'Order saved successfully.';
	include ('../includes/company_type/list_comptype.php');
}





elseif($_REQUEST['fpurpose']=='change_hide') // Update shelf status
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$type_ids_arr 		= explode('~',$_REQUEST['type_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($type_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['comptype_hide']	= $new_status;
			$comptype_id 						= $type_ids_arr[$i];	
			$db->update_from_array($update_array,'general_settings_sites_customer_company_types',array('comptype_id'=>$comptype_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/company_type/list_comptype.php');
		
}
else if($_REQUEST['fpurpose']=='delete') // Delete shelf
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry company type not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_check = "SELECT count(*) as cnt FROM customers WHERE customer_comptype = '".$del_arr[$i]."' AND sites_site_id=$ecom_siteid";
					  $res_check = $db->query($sql_check);
					  $row_check = $db->fetch_array($res_check);
		              if($row_check['cnt'] == 0){
					  $sql_del = "DELETE FROM general_settings_sites_customer_company_types WHERE comptype_id=".$del_arr[$i];
					  $db->query($sql_del);
					  if($alert) $alert .="<br />";
					  $alert .= "Company type with ID -".$del_arr[$i]." Deleted";
				       }
					   else
					  {
					    $alert .= "Sorry Cannot Delete! Company type with ID -".$del_arr[$i]." is linked with customer";
					   }
				 }	  
			}
		}
		include ('../includes/company_type/list_comptype.php');
	

}
else if($_REQUEST['fpurpose']=='add') // New shelf
{
	
	include("includes/company_type/add_comptype.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit shelf
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/company_type/edit_comptype.php");
	
}
else if($_REQUEST['fpurpose']=='insert') // Save new shelf
{
	if($_REQUEST['Submit'])
	{
		
		
		$alert='';
		$fieldRequired = array($_REQUEST['comptype_name'],$_REQUEST['comptype_order']);
		$fieldDescription = array('Company Type Name','Company Type Order');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM general_settings_sites_customer_company_types WHERE comptype_name = '".trim(add_slash($_REQUEST['comptype_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Company Type Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['comptype_name']=add_slash($_REQUEST['comptype_name']);
			$insert_array['sites_site_id']=$ecom_siteid;
			$insert_array['comptype_order']=add_slash($_REQUEST['comptype_order']);
			$insert_array['comptype_hide']=$_REQUEST['comptype_hide'];
			$db->insert_from_array($insert_array, 'general_settings_sites_customer_company_types');
			$insert_id = $db->insert_id();
			
				
				
			$alert .= '<br><span class="redtext"><b>Company Type added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_comptype&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_comptype&fpurpose=edit&comptype_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_comptype&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/company_type/add_comptype.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['comptype_name'],$_REQUEST['comptype_order']);
		$fieldDescription = array('Company Type Name','Company Type Order');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				
		$sql_check = "SELECT count(*) as cnt FROM general_settings_sites_customer_company_types WHERE comptype_name = '".add_slash($_REQUEST['comptype_name'])."' AND sites_site_id=$ecom_siteid AND comptype_id<>".$_REQUEST['comptype_id']."";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Company Type Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['comptype_name']=add_slash($_REQUEST['comptype_name']);
			$update_array['sites_site_id']=$ecom_siteid;
			$update_array['comptype_order']=add_slash($_REQUEST['comptype_order']);
			$update_array['comptype_hide']=$_REQUEST['comptype_hide'];
			$db->update_from_array($update_array, 'general_settings_sites_customer_company_types', 'comptype_id', $_REQUEST['comptype_id']);
			$alert .= '<br><span class="redtext"><b>Company Type Name Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_comptype&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_comptype&fpurpose=edit&comptype_id=<?=$_REQUEST['comptype_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_comptype&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center>Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			include("includes/company_type/edit_comptype.php");
		}
	}
}
?>