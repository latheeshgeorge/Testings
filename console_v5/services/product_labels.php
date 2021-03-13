<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/product_labels/list_prod_labels.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$label_ids_arr 		= explode('~',$_REQUEST['label_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($label_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['label_hide']	= $new_status;
			$label_id						= $label_ids_arr[$i];	
			$db->update_from_array($update_array,'product_site_labels','label_id',$label_id);
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/product_labels/list_prod_labels.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Label not selected';
		}
		else
		{
			$del_count= 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// check for a drop down to remove the values in the dropdwon for tht label
					$sql_istextBox 	= "SELECT is_textbox from product_site_labels WHERE label_id=".$del_arr[$i];
					// check for a drop down to remove the values in the dropdwon for tht label
					$sql_istextBox 	= "SELECT is_textbox from product_site_labels WHERE label_id=".$del_arr[$i];
					$res_istextBox 	= $db->query($sql_istextBox);
					$istextBox 		= $db->fetch_array($res_istextBox);
					if(!$istextBox['is_textbox'])
					{// deleteing the values in the dropdown
						$sql_del 	= "DELETE FROM product_site_labels_values WHERE product_site_labels_label_id=".$del_arr[$i];
						$db->query($sql_del);
					}
					$sql_del = "DELETE FROM product_site_labels WHERE label_id=".$del_arr[$i];
					$db->query($sql_del);
					$sql_del = "DELETE FROM product_labels WHERE product_site_labels_label_id =".$del_arr[$i];
					$db->query($sql_del);
						
					$sql_del = "DELETE FROM product_labels_group_label_map WHERE product_site_labels_label_id = ".$del_arr[$i];
					$db->query($sql_del);
					$del_count ++;			
				}	
			}
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
					   $alert .= $del_count." Label(s) Deleted Succesfully";
			}		   
		}
		include ('../includes/product_labels/list_prod_labels.php');
}
else if($_REQUEST['fpurpose']=='add')
{
	include("includes/product_labels/add_prod_labels.php");
}
else if($_REQUEST['fpurpose']=='add_contact')
{
	include("includes/vendor/add_contact.php");
}
else if($_REQUEST['fpurpose']=='edit')
{  
	include("includes/product_labels/edit_prod_labels.php");
	
}
else if($_REQUEST['fpurpose']=='list_contact')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/vendor/list_vendor_contact.php");
}
else if($_REQUEST['fpurpose']=='edit_contact')
{
	include("includes/vendor/edit_contact.php");
	
}
/*else if($_REQUEST['fpurpose']=='insert_contact')
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
		
		$sql_check = "SELECT count(*) as cnt FROM product_vendor_contacts WHERE contact_name = '".add_slash($_REQUEST['contact_name'])."' AND product_vendors_label_id=".$_REQUEST['label_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Contact Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['contact_name']=add_slash($_REQUEST['contact_name']);
			$insert_array['contact_address']=add_slash($_REQUEST['contact_address']);
			$insert_array['contact_phone']=add_slash($_REQUEST['contact_phone']);
			$insert_array['contact_fax']=add_slash($_REQUEST['contact_fax']);
			$insert_array['contact_email']=add_slash($_REQUEST['contact_email']);
			$insert_array['contact_mobile']=add_slash($_REQUEST['contact_mobile']);
			$insert_array['contact_position']=add_slash($_REQUEST['contact_position']);
			$insert_array['product_vendors_label_id']=$_REQUEST['label_id'];
			$db->insert_from_array($insert_array, 'product_vendor_contacts');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Successfully Inserted</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=prod_labels&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Vednor Listing page</a><br /><br />
	<a class="smalllink" href="home.php?request=prod_labels&fpurpose=list_contact&label_id=<?=$_REQUEST['label_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pas_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Contact Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=edit_contact&id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pas_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&label_id=<?=$_REQUEST['label_id']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=add_contact&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pas_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&label_id=<?=$_REQUEST['label_id']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<span class="redtext"><strong>Error!!</strong> '.$alert;
			$alert .= '</span><br><br>';
			include("includes/vendor/add_contact.php");
		}
	}

}*/
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['label_name']);
		$fieldDescription = array('Label Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM product_site_labels WHERE label_name = '".trim(add_slash($_REQUEST['label_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Label Name Already exists '; 
		
		if($_REQUEST['is_textbox']==0){ // validattion for checking whether atleast one value exists for the drop down
		$atleastone=0;
		for($i=1;$i<=$_REQUEST['labelcnt'];$i++){
			if($_REQUEST['label_value'.$i]!=''){
				$atleastone=1;
				}
		}
		if(!$atleastone)
		$alert = 'Please Add atleast one value for the dropdown ';
		}
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['label_name']=trim(add_slash($_REQUEST['label_name']));
			$insert_array['in_search']=(add_slash($_REQUEST['in_search']))?1:0;
			$insert_array['is_textbox']=(add_slash($_REQUEST['is_textbox']))?1:0;
			$insert_array['label_hide']=(add_slash($_REQUEST['label_hide']))?1:0;
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'product_site_labels');
			$insert_id = $db->insert_id();
			
			if(!$_REQUEST['is_textbox'])
			{
			
				//Label value in case of dropdown
				for($i=1;$i<=10;$i++)
				{
					$label_value=$_REQUEST['label_value'.$i];
					if($label_value)
					{
						$insert_value_array=array();
						$insert_value_array['product_site_labels_label_id']=$insert_id;
						$insert_value_array['label_value']=add_slash($label_value);
						$db->insert_from_array($insert_value_array, 'product_site_labels_values');
					}	
				}
				
			}
			// Check whether label groups are selected
			if(count($_REQUEST['group_id']))
			{
				$ord = 1;
				for($i=0;$i<count($_REQUEST['group_id']);$i++)
				{
					if($_REQUEST['group_id'][$i])
					{
						$insert_array									= array();
						$insert_array['product_labels_group_group_id']	= $_REQUEST['group_id'][$i];
						$insert_array['product_site_labels_label_id']	= $insert_id;
						$insert_array['map_order']						= $ord;
						$db->insert_from_array($insert_array,'product_labels_group_label_map');
						$ord++; 
					}
				}
			}
			
			
			$alert .= '<font color="red"><b>Product Label Successfully Inserted</b></font>';
			echo $alert;
			?>
			<br /><br /><a class="smalllink" href="home.php?request=prod_labels&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=edit&label_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = ' <strong>Error!!</strong> '.$alert;
			include("includes/product_labels/add_prod_labels.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['label_name']);
		$fieldDescription = array('Label Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM product_site_labels WHERE label_name = '".trim(add_slash($_REQUEST['label_name']))."' AND sites_site_id=$ecom_siteid AND label_id<>".$_REQUEST['label_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Label Name Already exists ';
		if($_REQUEST['is_textbox']==0){ // validattion for checking whether atleast one value exists for the drop down
			$atleastone=0;
			for($i=1;$i<=$_REQUEST['labelcnt'];$i++){
				if($_REQUEST['label_value'.$i]!=''){
					$atleastone=1;
					}
			}
		if(!$atleastone)
		$alert = 'Please Add atleast one value for the dropdown ';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['label_name']=trim(add_slash($_REQUEST['label_name']));
			$update_array['in_search']=(add_slash($_REQUEST['in_search']))?1:0;
			$update_array['is_textbox']=(add_slash($_REQUEST['is_textbox']))?1:0;
			$update_array['label_hide']=(add_slash($_REQUEST['label_hide']))?1:0;
			$update_array['sites_site_id']=$ecom_siteid;
			$sql_type="SELECT is_textbox FROM product_site_labels WHERE sites_site_id=$ecom_siteid AND label_id=".$_REQUEST['label_id'];
			$res_type = $db->query($sql_type);
			$row_type=$db->fetch_array($res_type);
			$type=$row_type['is_textbox'];
			if($type<>$_REQUEST['is_textbox'])
			{
				$filter_arr=array('product_site_labels_label_id' => $_REQUEST['label_id']);
				$db->delete_from_array($filter_arr,'product_site_labels_values');
				$filter_arr_products=array('product_site_labels_label_id' => $_REQUEST['label_id']);
				$db->delete_from_array($filter_arr_products,'product_labels');	
			}
			$db->update_from_array($update_array, 'product_site_labels', 'label_id', $_REQUEST['label_id']);
			if(!$_REQUEST['is_textbox'])
			{
			
				//Label value in case of dropdown
				for($i=1;$i<=$_REQUEST['labelcnt'];$i++)
				{
					
			//$update_value_array['value_id']=$_REQUEST['value_id'.$i];
			if($_REQUEST['value_id'.$i])
			{
			$update_value_array=array();
			if($_REQUEST['label_value'.$i]){// check whether a value is removed or made null from a text box while editing
			$update_value_array['label_value']=$_REQUEST['label_value'.$i];
			$db->update_from_array($update_value_array, 'product_site_labels_values','label_value_id',$_REQUEST['value_id'.$i]);
			}else{
			$filter_arr=array('label_value_id' => $_REQUEST['value_id'.$i]);// to delete the removed/null value from the text box
			$db->delete_from_array($filter_arr,'product_site_labels_values');	
			}
					}
					else if($_REQUEST['label_value'.$i])
					{
						$insert_value_array=array();
						$insert_value_array['product_site_labels_label_id']=$_REQUEST['label_id'];
						//$insert_value_array['site_id']=$ecom_siteid;
						$insert_value_array['label_value']=add_slash($_REQUEST['label_value'.$i]);
						$db->insert_from_array($insert_value_array, 'product_site_labels_values');
					}	
				}
			
			}
			// Check whether label groups are selected
			$ord = 0;
			if(count($_REQUEST['group_id']))
			{
				$grp_arr = array();
				for($i=0;$i<count($_REQUEST['group_id']);$i++)
				{
					if($_REQUEST['group_id'][$i])
					{
						// Check whether already mapping occurs
						$sql_exists = "SELECT map_id 
										FROM 
											product_labels_group_label_map 
										WHERE 
											product_labels_group_group_id = ".$_REQUEST['group_id'][$i]."
											AND product_site_labels_label_id=".$_REQUEST['label_id']." 
										LIMIT 
											1";
						$ret_exists = $db->query($sql_exists);
						if($db->num_rows($ret_exists)==0)
						{
							$insert_array									= array();
							$insert_array['product_labels_group_group_id']	= $_REQUEST['group_id'][$i];
							$insert_array['product_site_labels_label_id']	= $_REQUEST['label_id'];
							$insert_array['map_order']						= 0;
							$db->insert_from_array($insert_array,'product_labels_group_label_map');
						}	
						$grp_arr[] = $_REQUEST['group_id'][$i];
						$ord++; 
					}
				}
				if(count($grp_arr))
				{
					$sql_del = "DELETE FROM 
									product_labels_group_label_map 
								WHERE 
									product_site_labels_label_id = ".$_REQUEST['label_id']." 
									AND product_labels_group_group_id NOT IN(".implode(',',$grp_arr).")";
					
					$db->query($sql_del); 
				}
			}
			if($ord==0)
			{
				$sql_del = "DELETE FROM product_labels_group_label_map WHERE product_site_labels_label_id = ".$_REQUEST['label_id'];
				$db->query($sql_del);
			}
			$alert = '<center><font color="red"><b>Product Label Successfully Updated</b></font><br>';
			echo $alert;	
			?>
			<br /><a class="smalllink" href="home.php?request=prod_labels&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=edit&label_id=<?=$_REQUEST['label_id']?>&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_labels&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&search_labelgroup=<?=$_REQUEST['search_labelgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			//$alert = '<center> Error! '.$alert;
			//$alert .= '</center>';
			//echo $alert;
			?>
			
			<?php
			include("includes/product_labels/edit_prod_labels.php");
		}
	}
}
?>