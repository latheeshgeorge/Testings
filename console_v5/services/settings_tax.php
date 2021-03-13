<?php
if($_REQUEST['fpurpose']=='')
{
	#Status updation
	if($_REQUEST['Update_Status'])
	{
		if(count($_REQUEST['checkbox'])>0)
		{
			foreach($_REQUEST['checkbox'] as $v)
			{
				$sql="UPDATE general_settings_site_tax set tax_active=".$_REQUEST['cmbstatus']. " WHERE tax_id=".$v." AND sites_site_id=".$ecom_siteid;
				$db->query($sql);
			}
			clear_all_cache();// Clearing all cache
			$alert = 'Status Updated Successfully'; 
			create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting general settings cache
		}
	} 
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/tax_settings/list_tax.php");
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Tax not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   
					   $sql_del = "DELETE FROM general_settings_site_tax WHERE tax_id=".$del_arr[$i];
					    $db->query($sql_del);
						$del_count++;
				}	
			}
			create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting general settings cache
			clear_all_cache();// Clearing all cache
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
							$alert .= $del_count." Tax(s) Deleted";
							}
		}
		include ('../includes/tax_settings/list_tax.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/tax_settings/add_tax.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	include("includes/tax_settings/edit_tax.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['tax_name'],$_REQUEST['tax_val']);
		$fieldDescription = array('Tax Name','Value');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_tax WHERE tax_name = '".add_slash($_REQUEST['tax_name'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Tax Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['tax_name']=add_slash($_REQUEST['tax_name']);
			$insert_array['tax_description']=add_slash($_REQUEST['tax_description']);
			$insert_array['tax_val']=add_slash($_REQUEST['tax_val']);
			$insert_array['tax_active']=add_slash($_REQUEST['tax_active']);
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'general_settings_site_tax');
			$insert_id = $db->insert_id();
			
			clear_all_cache();// Clearing all cache
			create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting general settings cache
			
			$alert .= '<br><span class="redtext"><b>Tax added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_tax&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_tax&fpurpose=edit&tax_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_tax&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			include("includes/tax_settings/add_tax.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['tax_name'],$_REQUEST['tax_val']);
		$fieldDescription = array('Tax Name','Value');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM general_settings_site_tax WHERE tax_name = '".add_slash($_REQUEST['tax_name'])."' AND sites_site_id=$ecom_siteid AND tax_id<>".$_REQUEST['tax_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Tax Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['tax_name']=add_slash($_REQUEST['tax_name']);
			$update_array['tax_description']=add_slash($_REQUEST['tax_description']);
			$update_array['tax_val']=add_slash($_REQUEST['tax_val']);
			$update_array['tax_active']=$_REQUEST['tax_active'];
			
			
			$db->update_from_array($update_array, 'general_settings_site_tax', 'tax_id', $_REQUEST['tax_id']);
			
			clear_all_cache();// Clearing all cache
			
			create_Tax_Delivery_Paytype_Paymethod_CacheFile(); // creating / rewriting general settings cache
			
			$alert .= '<br><span class="redtext"><b>Tax Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=general_settings_tax&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_tax&fpurpose=edit&tax_id=<?=$_REQUEST['tax_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=general_settings_tax&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			include("includes/tax_settings/edit_tax.php");
		}
	}
}
?>