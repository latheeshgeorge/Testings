<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/vendor/list_vendor.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$vendor_ids_arr 		= explode('~',$_REQUEST['vendor_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($vendor_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['vendor_hide']	= $new_status;
			$vendor_id						= $vendor_ids_arr[$i];	
			$db->update_from_array($update_array,'product_vendors',array('vendor_id'=>$vendor_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/vendor/list_vendor.php');
		
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
		$update_array['contact_sortorder']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_vendor_contacts',array('id'=>$IdArr[$i]));
	}
	$alert = 'Order saved successfully.';
	include ('../includes/vendor/list_vendor_contact.php');
}
else if($_REQUEST['fpurpose']=='delete_contact')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Vendor not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   # Check for states of this country
					  $sql_del = "DELETE FROM product_vendor_contacts WHERE id=".$del_arr[$i];
					  $db->query($sql_del);
										
					  if($alert) $alert .="<br />";
					   $alert .= "Contact with ID -".$del_arr[$i]." Deleted";
						
					    
					
				}	
			}
		}
			
		include ('../includes/vendor/list_vendor_contact.php');
	

}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Vendor not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   # Check for states of this country
					  $sql_del = "DELETE FROM product_vendors WHERE vendor_id=".$del_arr[$i];
					  $db->query($sql_del);
										
					  if($alert) $alert .="<br />";
					   $alert .= "Vendor with ID -".$del_arr[$i]." Deleted";
						
					    
					
				}	
			}
		}
			
		include ('../includes/vendor/list_vendor.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/vendor/add_vendor.php");
}
else if($_REQUEST['fpurpose']=='add_contact')
{
	
	include("includes/vendor/add_contact.php");
}

else if($_REQUEST['fpurpose']=='edit')
{  
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/vendor/ajax/vendor_ajax_functions.php');
	include("includes/vendor/edit_vendor.php");
}

elseif($_REQUEST['fpurpose'] == 'list_vendor_maininfo'){ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/vendor/ajax/vendor_ajax_functions.php');
		show_vendor_maininfo($_REQUEST['vendor_id'],$alert);
}


elseif($_REQUEST['fpurpose'] == 'list_products_ajax'){ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/vendor/ajax/vendor_ajax_functions.php');
		show_product_list($_REQUEST['vendor_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Page group
    $vendor_id = $_REQUEST['vendor_id'];
	if(trim($_REQUEST['hid_vend_id'])) { $hid_vend_id = $_REQUEST['hid_vend_id']; }

	include ('includes/vendor/list_assign_products.php');						
	
}

else if($_REQUEST['fpurpose']=='statistics')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$vendor_id = $_REQUEST['vendor_id'];
	include("includes/vendor/list_vendor_statistics.php");
}

else if($_REQUEST['fpurpose']=='list_contact')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/vendor/list_vendor_contact.php");
}

else if($_REQUEST['fpurpose'] == 'assign_products'){// to asign the categories to the group
  if(trim($_REQUEST['vendor_id'])) {
		$hid_vend_id = $_REQUEST['vendor_id'];
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not selected';
		}
		else
		{ 
			$hid_vend_arr = explode("~",$_REQUEST['vendor_id']);
			$cnt_vend = count($hid_vend_arr);
			$products_arr = explode("~",$_REQUEST['product_ids']);
			for($x=0;$x<count($hid_vend_arr);$x++)
			{
			$sql_assigned_products = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id =".$hid_vend_arr[$x]." AND sites_site_id=".$ecom_siteid;
			$res_assigned_products = $db->query($sql_assigned_products);
			$assigned_products_arr = array();
			while($assigned_products = $db->fetch_array($res_assigned_products))
			{
				$assigned_products_arr[]= $assigned_products['products_product_id'];
			}
			for($i=0;$i<count($products_arr);$i++)
			{
				if(trim($products_arr[$i]) && !in_array($products_arr[$i],$assigned_products_arr))
				{ 
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['product_vendors_vendor_id']=$hid_vend_arr[$x];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'product_vendor_map');
				}	
			}
			unset($assigned_products_arr);
		}
		 			$alert = 'Products Successfully assigned to Vendor'; 
		} 

		
} /*else {
	
	    $vendor_id = $_REQUEST['vendor_id'];
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not selected';
		}
		else
		{ 
			$sql_assigned_products = "SELECT products_product_id FROM product_vendor_map WHERE product_vendors_vendor_id =".$_REQUEST['vendor_id']." AND sites_site_id=".$ecom_siteid;
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
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['product_vendors_vendor_id']=$_REQUEST['vendor_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'product_vendor_map');
				}	
			}
			$alert = 'Vendors Successfully assigned Products'; 
	}
}*/	
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=prod_vendor&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&group_id=<?=$_REQUEST['group_id']?>" onclick="show_processing()">Go Back to the Vendor Listing page</a><br /><br />
		 <? if($cnt_vend ==1){?>
		 <a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&curtab=prods_tab_td" onclick="show_processing()">Go Back to the Edit Vendor</a><br /><br /> 
		<? }?>
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Vendor</a> 		
		<?
	
}

elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to page Groups using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/vendor/ajax/vendor_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Products  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from page groups
					$sql_del = "DELETE FROM product_vendor_map WHERE map_id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Removed from this Vendor'; 
		}	
show_product_list($_REQUEST['vendor_id'],$alert);
}

else if($_REQUEST['fpurpose']=='edit_contact')
{
	include("includes/vendor/edit_contact.php");
	
}
else if($_REQUEST['fpurpose']=='insert_contact')
{
	
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['contact_name'],$_REQUEST['contact_email']);
		$fieldDescription = array('Contact Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM product_vendor_contacts WHERE contact_name = '".trim(add_slash($_REQUEST['contact_name']))."' AND product_vendors_vendor_id=".$_REQUEST['vendor_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Contact Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['contact_name']=trim(add_slash($_REQUEST['contact_name']));
			$insert_array['contact_address']=add_slash($_REQUEST['contact_address']);
			$insert_array['contact_phone']=add_slash($_REQUEST['contact_phone']);
			$insert_array['contact_fax']=add_slash($_REQUEST['contact_fax']);
			$insert_array['contact_email']=add_slash($_REQUEST['contact_email']);
			$insert_array['contact_mobile']=add_slash($_REQUEST['contact_mobile']);
			$insert_array['contact_sortorder']=add_slash($_REQUEST['contact_sortorder']);
			$insert_array['contact_position']=add_slash($_REQUEST['contact_position']);
			$insert_array['product_vendors_vendor_id']=$_REQUEST['vendor_id'];
			$db->insert_from_array($insert_array, 'product_vendor_contacts');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b> Contact Successfully Inserted</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=prod_vendor&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Vendor Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a><br /><br /><br /><br />
	        <a class="smalllink" href="home.php?request=prod_vendor&fpurpose=list_contact&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Contact Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit_contact&id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&vendor_id=<?=$_REQUEST['vendor_id']?>" onclick="show_processing()">Go Back to the Contact Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add_contact&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&vendor_id=<?=$_REQUEST['vendor_id']?>" onclick="show_processing()">Go Back to the Add New Contact </a>
			<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/vendor/add_contact.php");
		}
	}

}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['vendor_name'],$_REQUEST['vendor_email']);
		$fieldDescription = array('Vendor Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM product_vendors WHERE vendor_name = '".trim(add_slash($_REQUEST['vendor_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Vendor Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['vendor_name']=trim(add_slash($_REQUEST['vendor_name']));
			$insert_array['vendor_address']=add_slash($_REQUEST['vendor_address']);
			$insert_array['vendor_telephone']=add_slash($_REQUEST['vendor_telephone']);
			$insert_array['vendor_fax']=add_slash($_REQUEST['vendor_fax']);
			$insert_array['vendor_email']=add_slash($_REQUEST['vendor_email']);
			$insert_array['vendor_website']=add_slash($_REQUEST['vendor_website']);
						$insert_array['vendor_hide']=add_slash($_REQUEST['vendor_hide']);

			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'product_vendors');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Vendor Successfully Inserted</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=prod_vendor&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$insert_id?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = 'Error!! '.$alert;
			
			include("includes/vendor/add_vendor.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update_contact')
{
	
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['contact_name'],$_REQUEST['contact_email']);
		$fieldDescription = array('Contact Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM product_vendor_contacts WHERE contact_name = '".trim(add_slash($_REQUEST['contact_name']))."' AND product_vendors_vendor_id=".$_REQUEST['vendor_id']." AND id<>".$_REQUEST['id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Contact Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['contact_name']=trim(add_slash($_REQUEST['contact_name']));
			$update_array['contact_address']=add_slash($_REQUEST['contact_address']);
			$update_array['contact_phone']=add_slash($_REQUEST['contact_phone']);
			$update_array['contact_fax']=add_slash($_REQUEST['contact_fax']);
			$update_array['contact_email']=add_slash($_REQUEST['contact_email']);
			$update_array['contact_mobile']=add_slash($_REQUEST['contact_mobile']);
			$update_array['contact_sortorder']=add_slash($_REQUEST['contact_sortorder']);
			$update_array['contact_position']=add_slash($_REQUEST['contact_position']);
			$update_array['product_vendors_vendor_id']=$_REQUEST['vendor_id'];
			
			$db->update_from_array($update_array, 'product_vendor_contacts', 'id', $_REQUEST['id']);
			$alert = '<center><font color="red"><b>Contact Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=prod_vendor&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Vednor Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit Vendor Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Vendor</a><br /><br /><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=list_contact&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Contact Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit_contact&id=<?=$_REQUEST['id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&vendor_id=<?=$_REQUEST['vendor_id']?>" onclick="show_processing()">Go Back to the Edit Contact Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add_contact&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&vendor_id=<?=$_REQUEST['vendor_id']?>" onclick="show_processing()">Go Back to the Add New Contact Page</a>
		<?	
		}
		else {
			$alert = 'Error! '.$alert;
			
			?>
			
			<?php
			include("includes/vendor/edit_vendor.php");
		}
	}

}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['vendor_name'],$_REQUEST['vendor_email']);
		$fieldDescription = array('Vendor Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM product_vendors WHERE vendor_name = '".trim(add_slash($_REQUEST['vendor_name']))."' AND sites_site_id=$ecom_siteid AND vendor_id<>".$_REQUEST['vendor_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Vendor Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['vendor_name']=trim(add_slash($_REQUEST['vendor_name']));
			$update_array['vendor_address']=add_slash($_REQUEST['vendor_address']);
			$update_array['vendor_telephone']=add_slash($_REQUEST['vendor_telephone']);
			$update_array['vendor_fax']=add_slash($_REQUEST['vendor_fax']);
			$update_array['vendor_email']=add_slash($_REQUEST['vendor_email']);
			$update_array['vendor_website']=add_slash($_REQUEST['vendor_website']);
			$update_array['vendor_hide'] = $_REQUEST['vendor_hide'];

			$update_array['sites_site_id']=$ecom_siteid;
			
			$db->update_from_array($update_array, 'product_vendors', 'vendor_id', $_REQUEST['vendor_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=prod_vendor&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=edit&vendor_id=<?=$_REQUEST['vendor_id']?>&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_vendor&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = 'Error! '.$alert;
			
			
			?>
			
			<?php
			include("includes/vendor/edit_vendor.php");
		}
	}
}
?>