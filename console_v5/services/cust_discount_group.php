<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/cust_discount_group/list_cust_disc_group.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$group_ids_arr 	= explode('~',$_REQUEST['group_ids']);
		$new_status		= $_REQUEST['ch_status'];
	
		for($i=0;$i<count($group_ids_arr);$i++)
		{
			$update_array							= array();
			$update_array['cust_disc_grp_active']	= $new_status;
			$group_id 								= $group_ids_arr[$i];	
			$db->update_from_array($update_array,'customer_discount_group',array('cust_disc_grp_id'=>$group_id));
			
		}
		
		$alert = 'Status changed successfully.';
		include ('../includes/cust_discount_group/list_cust_disc_group.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
          include_once("../functions/functions.php");
          include_once('../session.php');
          include_once("../config.php");
          if ($_REQUEST['del_ids'] == '')
          {
                  $alert = 'Sorry Customer  Group not selected';
          }
          else
          {
                  $del_count = 0; 
                  $del_arr = explode("~",$_REQUEST['del_ids']);
                  for($i=0;$i<count($del_arr);$i++)
                  {
                          if(trim($del_arr[$i]))
                          {
                                    $sql_del = "DELETE FROM customer_discount_group WHERE cust_disc_grp_id=".$del_arr[$i];
                                    $db->query($sql_del);
                                    $sql_del = "DELETE FROM customer_discount_customers_map WHERE customer_discount_group_cust_disc_grp_id=".$del_arr[$i];
                                    $db->query($sql_del);	
                                    $sql_del = "DELETE FROM customer_discount_group_products_map WHERE customer_discount_group_cust_disc_grp_id=".$del_arr[$i];
                                    $db->query($sql_del);
                                    $sql_del = "DELETE FROM customer_discount_group_categories_map WHERE customer_discount_group_cust_disc_grp_id=".$del_arr[$i];
                                    $db->query($sql_del);	
                                    $del_count++;				
                          }	
                  }
                  if($del_count > 0)
                  {
                      if($alert) $alert .="<br />";
                                    $alert .= $del_count." Group(s) Deleted Successfully";
                  }		  
          }
          include ('../includes/cust_discount_group/list_cust_disc_group.php');
}
else if($_REQUEST['fpurpose']=='save_category_discount')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		
	$group_id 		= $_REQUEST['cust_disc_grp_id'];
	$map_ids_arr 	= explode('~',$_REQUEST['map_ids']); 
	$cat_discs_arr 	= explode('~',$_REQUEST['cat_discs']); 
	for($i=0;$i<count($map_ids_arr);$i++)
	{
		$update_sql			= '';
		$disc_val	= (is_numeric(trim($cat_discs_arr[$i])))?trim($cat_discs_arr[$i]):0;
		if($disc_val>100)
			$disc_val = 100;
		
		$update_sql = "UPDATE customer_discount_group_categories_map 
						SET 
							customer_discount_group_category_discount = ".$disc_val." 
						WHERE 
							map_id = ".$map_ids_arr[$i]." 
							AND customer_discount_group_cust_disc_grp_id = $group_id 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$db->query($update_sql);
		
		/*// Get the category id for current mapping 
		$sql_catid = "SELECT product_categories_category_id FROM customer_discount_group_categories_map WHERE map_id = ".$map_ids_arr[$i]." LIMIT 1";
		$ret_catid = $db->query($sql_catid);
		if($db->num_rows($ret_catid))
		{
			$row_catid = $db->fetch_array($ret_catid);
			// Get the ids of products which are directly under this category 
			$sql_prods = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id =".$row_catid['product_categories_category_id'];
			$ret_prods = $db->query($sql_prods);
			if($db->num_rows($ret_prods))
			{
				while ($row_prods = $db->fetch_array($ret_prods))
				{
					// update the category discount value set for this category to this product map, if this product exists in the mapping table 
					 $update_sql = "UPDATE customer_discount_group_products_map 
									SET 
										category_discount = $disc_val 
									WHERE 
										customer_discount_group_cust_disc_grp_id = $group_id 
										AND products_product_id = ".$row_prods['products_product_id']." 
									LIMIT 
										1";
					$db->query($update_sql);					
				}
			}
		}*/
		
	}
	$alert = 'Details updated successfully';
	show_display_categories_discountgroup_list($group_id,$alert);
}
else if($_REQUEST['fpurpose']=='add')
{
	include("includes/cust_discount_group/add_cust_disc_group.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
    $group_id = $_REQUEST['checkbox'][0];

	include("includes/cust_discount_group/edit_cust_disc_group.php");
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['cust_disc_grp_name']);
		$fieldDescription = array('Discount Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM customer_discount_group WHERE cust_disc_grp_name = '".trim(add_slash($_REQUEST['cust_disc_grp_name']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Discount Group Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['cust_disc_grp_name']							= trim(add_slash($_REQUEST['cust_disc_grp_name']));
			$insert_array['customer_discount_method']					= add_slash($_REQUEST['customer_discount_method']);

			//$insert_array['cust_disc_grp_discount']						= add_slash($_REQUEST['cust_disc_grp_discount']);
			$insert_array['cust_disc_grp_active']						= 0;//add_slash($_REQUEST['cust_disc_grp_active']);
			$insert_array['sites_site_id']								= $ecom_siteid;
			$insert_array['cust_disc_display_category_in_myhome']		= ($_REQUEST['cust_disc_display_category_in_myhome'])?1:0;
			$insert_array['cust_apply_direct_discount_also']			= ($_REQUEST['cust_apply_direct_discount_also'])?'Y':'N';
			$insert_array['cust_apply_direct_product_discount_also']	= ($_REQUEST['cust_apply_direct_product_discount_also'])?'Y':'N';
			
			if($_REQUEST['customer_discount_method']==1)
			{
						$insert_array['cust_disc_grp_discount']						= add_slash($_REQUEST['cust_disc_grp_discount']);
					    $insert_array['cust_disc_grp_cat_discount_enable']	= 'N';

			}
			else if($_REQUEST['customer_discount_method']==2)
			{
				        
				$sql_set = "SELECT enable_custgroup_category_disc FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
				$ret_set = $db->query($sql_set);
				if($db->num_rows($ret_set))
				{
					$row_set = $db->fetch_array($ret_set);
					if($row_set['enable_custgroup_category_disc']==1)
					{
						$insert_array['cust_disc_grp_cat_discount_enable']	= 'Y';
					}
				}         
			}
			
			$db->insert_from_array($insert_array, 'customer_discount_group');
			$insert_id = $db->insert_id();
			
			if($_REQUEST['Submit'] == 'Save & Return to Edit')
			{
			?>
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=cust_discount_group&fpurpose=edit&cust_disc_grp_id=<?=$insert_id?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&curtab=prodmenu_tab_td';
			</script>
			<?
			}
			else
			{
				$alert .= '<br><span class="redtext"><b>Customer Discount Group added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Customer Discount Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&cust_disc_grp_id=<?=$insert_id?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Customer Discount Group Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Customer Discount Group  Add New Page</a>
		<?	}
		}	
		else
		{
			$alert = 'Error!! '.$alert;
			$alert .= '<br>';
			include("includes/cust_discount_group/add_cust_disc_group.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'] or $_REQUEST['Submit_Activate'] or $_REQUEST['Submit_Deactivate'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['cust_disc_grp_name']);
		$fieldDescription = array('Discount Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM customer_discount_group WHERE cust_disc_grp_name = '".trim(add_slash($_REQUEST['cust_disc_grp_name']))."' AND sites_site_id=$ecom_siteid AND cust_disc_grp_id<>".$_REQUEST['cust_disc_grp_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Discount Group Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['cust_disc_grp_name']							= trim(add_slash($_REQUEST['cust_disc_grp_name']));
			//$update_array['cust_disc_grp_discount']						= add_slash($_REQUEST['cust_disc_grp_discount']);
			/*$update_array['cust_disc_grp_active']						= add_slash($_REQUEST['cust_disc_grp_active']);*/
			$update_array['customer_discount_method']					= add_slash($_REQUEST['customer_discount_method']);
			if($_REQUEST['customer_discount_method']==1)
			{
						$update_array['cust_disc_grp_discount']						= add_slash($_REQUEST['cust_disc_grp_discount']);
					    $update_array['cust_disc_grp_cat_discount_enable']	= 'N';

			}
			else if($_REQUEST['customer_discount_method']==2)
			{
				        
				$sql_set = "SELECT enable_custgroup_category_disc FROM general_settings_sites_common_onoff WHERE sites_site_id = $ecom_siteid LIMIT 1";
				$ret_set = $db->query($sql_set);
				if($db->num_rows($ret_set))
				{
					$row_set = $db->fetch_array($ret_set);
					if($row_set['enable_custgroup_category_disc']==1)
					{
						$update_array['cust_disc_grp_cat_discount_enable']	= 'Y';
					}
				}         
			}
			$update_array['cust_disc_display_category_in_myhome']		= ($_REQUEST['cust_disc_display_category_in_myhome'])?1:0;
            $update_array['cust_apply_direct_discount_also']			= ($_REQUEST['cust_apply_direct_discount_also'])?'Y':'N';
			$update_array['cust_apply_direct_product_discount_also']	= ($_REQUEST['cust_apply_direct_product_discount_also'])?'Y':'N';
			$update_array['sites_site_id']							= $ecom_siteid;
			$db->update_from_array($update_array, 'customer_discount_group', 'cust_disc_grp_id', $_REQUEST['cust_disc_grp_id']);
			$alert .= '<br><span class="redtext"><b>Discount Group Updated successfully</b></span><br>';
			if($_REQUEST['activate_clicked']==1) // case if activate button is clicked
			{
				$more_alert = Activate_Customer_Discount_Group($_REQUEST['cust_disc_grp_id']);
				if($more_alert!='')
					$alert .= '<span class="redtext"><br><br><strong>Sorry!! Discount Group cannot be Activated<br>'.$more_alert.'</span></strong><br><br>';
				else
				{
					ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],1);
					$alert .= '<br><span class="redtext"><strong>Discount Group Activated Successfully</strong></span><br><br>';
				}	
			}
			elseif($_REQUEST['activate_clicked']==2) // case of deactivating the customer group discount
			{
				ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
				$alert .= '<br><span class="redtext"><strong>Discount Group Deactivated Successfully</strong></span><br><br>';
			}
			if($_REQUEST['Submit'] == 'Update & Return')
			{
			?>
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=cust_discount_group&fpurpose=edit&cust_disc_grp_id=<?=$_REQUEST['cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>';
			</script>
			<?
			}
			else
			{
				echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&cust_disc_grp_id=<?=$_REQUEST['cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	}
		}
		else {
			$alert = 'Error! '.$alert;
			$alert .= '';
		?>
			<br />
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
			include("includes/cust_discount_group/edit_cust_disc_group.php");
		}
	}
}
elseif($_REQUEST['fpurpose'] =='list_customergroup_maininfo')// Case of listing main info for shop groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_customer_grp_maininfo($_REQUEST['cust_disc_grp_id']);
	}
elseif($_REQUEST['fpurpose']=='list_customer') // show customer list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_display_customer_discountgroup_list($_REQUEST['cust_disc_grp_id']);
}
elseif($_REQUEST['fpurpose']=='add_customer') // Assign customers to discount group
{
		include ('includes/cust_discount_group/list_sel_discgrp_customer.php');
}	
elseif($_REQUEST['fpurpose']=='save_add_customer') // Assign customers to group
{
		$msg = '';
		$tot_cnt = $valid_cnt = 0;
		foreach($_REQUEST['checkbox'] as $v)
		{ 
			$tot_cnt++;
			$valid_for_insertion = true;
			$cur_prods_arr = array();
			$cust_name = '';
			// Get the name of current customer 
			$sql_cust = "SELECT customer_title,customer_fname,customer_surname 
									FROM 
										customers 
									WHERE 
										customer_id =$v 
									LIMIT 
										1";
			$ret_cust = $db->query($sql_cust);
			if ($db->num_rows($ret_cust))
			{
				$row_cust = $db->fetch_array($ret_cust);
				$cust_name = stripslashes($row_cust['customer_title']).stripslashes($row_cust['customer_fname']).' '.stripslashes($row_cust['customer_surname']);
			}
			// Check how many products assigned to current customer group
			$sql_grp = "SELECT products_product_id 
								FROM 
									customer_discount_group_products_map 
								WHERE 
									customer_discount_group_cust_disc_grp_id = ".$_REQUEST['pass_cust_disc_grp_id'];
			$ret_grp = $db->query($sql_grp);
			if ($db->num_rows($ret_grp))// case if current group have products assigned
			{
				while ($row_grp = $db->fetch_array($ret_grp))
				{
					$cur_prods_arr[] = $row_grp['products_product_id'];
				}
				// get the list of all groups to which the current customer is already linked
				$sql_mapgroup = "SELECT products_product_id,b.cust_disc_grp_id,b.cust_disc_grp_name  
												FROM 
													customer_discount_group_products_map a,
													customer_discount_group b,
													customer_discount_customers_map c
												WHERE 
													b.sites_site_id=$ecom_siteid 
													AND c.customers_customer_id = $v 
													AND b.cust_disc_grp_id <> ".$_REQUEST['pass_cust_disc_grp_id']." 
													AND a.customer_discount_group_cust_disc_grp_id=b.cust_disc_grp_id 
													AND b.cust_disc_grp_id=c.customer_discount_group_cust_disc_grp_id";
				$ret_mapgroup = $db->query($sql_mapgroup);
				if ($db->num_rows($ret_mapgroup))
				{	
					$grpext_arr 			= array(-1);								
					$grpnameext_arr		= array();	
					while ($row_mapgroup = $db->fetch_array($ret_mapgroup))
					{
						if(in_array($row_mapgroup['products_product_id'],$cur_prods_arr))
						{
							$valid_for_insertion = false;
							if (!in_array($row_mapgroup['cust_disc_grp_id'],$grpext_arr)) // don e to avoid picking the same group name more than once
							{
								$grpnameext_arr[] = stripslashes($row_mapgroup['cust_disc_grp_name']);
								$grpext_arr[]			= $row_mapgroup['cust_disc_grp_id'];
							}
						}	
					}									
				}	
				if($valid_for_insertion==false)
				{
					if ($msg!='')
						$msg .='<br/>';
					$msg .= $cust_name.'  not assigned as this customer is already mapped with following groups which have all/some of the product(s) in current group ';	
					$msg .= "<br>".implode('<br/>',$grpnameext_arr);			
				}
			}			
			else // case if no products set to current group yet
			{
				// check whether current customer is linked with any other group in the site
				$sql_check = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id =$v 
											AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['pass_cust_disc_grp_id'];	
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check))
				{
					
					//if ($msg!='')
					//	$msg .='<br/>';
					//$msg .= $cust_name.'  not assigned as this customer is already mapped with following group(s) ';
					$group_name_arr = array();
					while ($row_check = $db->fetch_array($ret_check))
					{
						// Check whether the current group have any products
						$sql_products = "SELECT map_id 
											FROM 
												customer_discount_group_products_map 
											WHERE 
												customer_discount_group_cust_disc_grp_id = ".$row_check['customer_discount_group_cust_disc_grp_id']." 
											LIMIT 
												1";
						$ret_products = $db->query($sql_products);
						if($db->num_rows($ret_products)==0)
						{
							$valid_for_insertion = false;
							$sql_grp_check = "SELECT cust_disc_grp_name 
												FROM 
													customer_discount_group 
												WHERE 
													cust_disc_grp_id =".$row_check['customer_discount_group_cust_disc_grp_id']." 
												LIMIT 
													1";
							$ret_grp_check = $db->query($sql_grp_check);						
							if ($db->num_rows($ret_grp_check))
							{
								$row_grp_check = $db->fetch_array($ret_grp_check);						
								$group_name_arr[] = stripslashes($row_grp_check['cust_disc_grp_name']);
								//$msg .= '<br/>'.stripslashes($row_grp_check['cust_disc_grp_name']);
								
							}
						}	
					}
					if(count($group_name_arr))
					{
						if ($msg!='')
							$msg .='<br/>';
						$msg .= $cust_name.'  not assigned as this customer is already mapped with following group(s) ';
						$msg .= implode('<br/>',$group_name_arr);
					}	
				}
			}
			if ($valid_for_insertion==true) // do the insertion only if valid to insert
			{
				$valid_cnt++;
			  $sql_check = "SELECT customers_customer_id FROM customer_discount_customers_map WHERE 
								customers_customer_id= $v AND customer_discount_group_cust_disc_grp_id=".$_REQUEST['pass_cust_disc_grp_id']." LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
					$insert_array=array();
					$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
					$insert_array['customers_customer_id']								= $v;
					$insert_array['sites_site_id']												=$ecom_siteid;
					$db->insert_from_array($insert_array, 'customer_discount_customers_map');
				}
			}	
		}
		if($msg!='')
		{
			$alert = $msg;
			if($valid_cnt>0)
			{
				$alert .= '<br>'.$valid_cnt.' Customer(s) mapped Successfully';
				$alert .="<br><br> Discount group Deactivated. Please activate it.";
				ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			}	
			elseif($valid_cnt==0)
			{
				$alert .= '<br>Sorry!! unable to map any customers';
			}	
		}	
		else
		{
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert='Customer(s) Assigned Successfullly';
			$alert .="<br><br> Discount group Deactivated. Please activate it.";
		}	
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&cust_disc_grp_id=<?=$_REQUEST['pass_cust_disc_grp_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=customermenu_tab_td" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Add New Customer Group</a>

<?			
}	
elseif($_REQUEST['fpurpose']=='unassign_customerdetails') // Unassign customers from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Customer(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
			$sql_del = "DELETE FROM customer_discount_customers_map WHERE map_id=".$ch_arr[$i]." AND sites_site_id = ".$ecom_siteid ;
				$db->query($sql_del);
			}
			$alert = 'Customer(s) Unassigned Successfully';
			ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
		}	
		show_display_customer_discountgroup_list($_REQUEST['cust_disc_grp_id'],$alert);
}	
elseif($_REQUEST['fpurpose']=='add_products') // Assign customers to discount group
{
		include ('includes/cust_discount_group/list_sel_discgrp_product.php');
}	
elseif($_REQUEST['fpurpose']=='list_products') // show customer list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_display_products_discountgroup_list($_REQUEST['cust_disc_grp_id']);
}	
elseif($_REQUEST['fpurpose']=='save_add_products') // Assign Products  to custoemr discount group
{
		$msg = '';
		$tot_cnt = $valid_cnt = 0;
		foreach($_REQUEST['checkbox'] as $v)
		{ 
			// Get the name of the product
			$sql_prod = "SELECT product_name 
								FROM 
									products 
								WHERE 
									product_id = $v 
								LIMIT 
									1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$prod_name = stripslashes($row_prod['product_name']);
			}
			$tot_cnt++;
			$valid_to_insert = true;
			// Get the list of customer who are already mapped with this customer group
			$sql_cust = "SELECT customers_customer_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										customer_discount_group_cust_disc_grp_id = ".$_REQUEST['pass_cust_disc_grp_id'];
			$ret_cust = $db->query($sql_cust);
			if($db->num_rows($ret_cust))
			{
				while ($row_cust = $db->fetch_array($ret_cust))
				{
					$grps_arr	= array();
					// Get the groups to which current customer is mapped to other than current group
					$sql_grp = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id = ".$row_cust['customers_customer_id']." 
											AND customer_discount_group_cust_disc_grp_id <>  ".$_REQUEST['pass_cust_disc_grp_id'] ;
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						while ($row_grp = $db->fetch_array($ret_grp))
						{
							$grps_arr[] = $row_grp['customer_discount_group_cust_disc_grp_id'];
						}					
						
					}	
				}
				// Check whether current product is mapped to any of the above groups
				//Modified for the customer group checking messages -LG
						if(count($grps_arr)>0)
						{
							$sql_prods = "SELECT map_id,customer_discount_group_cust_disc_grp_id 
													FROM 
														customer_discount_group_products_map 
													WHERE 
														products_product_id = $v 
														AND  customer_discount_group_cust_disc_grp_id IN (".implode(',',$grps_arr).")";
							$ret_prods = $db->query($sql_prods);
							if ($db->num_rows($ret_prods))
							{
								$valid_to_insert = false;
								if ($msg!='')
									$msg .='<br/>';
								$msg .= $prod_name.'  not assigned as some/all customer(s) in this group is/are mapped with this product in following group(s)';
								while($row_prod = $db->fetch_array($ret_prods))
								{
									$sql_grp = "SELECT cust_disc_grp_name 
												FROM 
													customer_discount_group 
												WHERE 
													cust_disc_grp_id =".$row_prod['customer_discount_group_cust_disc_grp_id']." 
												LIMIT 
													1";
									$ret_grp = $db->query($sql_grp);						
									if ($db->num_rows($ret_grp))
									{
										$row_grp = $db->fetch_array($ret_grp);						
										$msg .= '<br/>'.stripslashes($row_grp['cust_disc_grp_name']);
									}	
								}
							 }
						 }
			}
			if($valid_to_insert)
			{
				$sql_check = "SELECT products_product_id 
										FROM 
											customer_discount_group_products_map 
										WHERE 
											products_product_id= $v 
											AND customer_discount_group_cust_disc_grp_id=".$_REQUEST['pass_cust_disc_grp_id']." 
										LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
						$insert_array																	= array();
						$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
						$insert_array['products_product_id']									= $v;
						$insert_array['sites_site_id']												= $ecom_siteid;
						$db->insert_from_array($insert_array, 'customer_discount_group_products_map');
				}
				$valid_cnt++;
			}	
		}
		if($msg!='')
		{
			$alert = $msg;
			if($valid_cnt>0)
			{
				$alert .= '<br>'.$valid_cnt.' Product(s) Assigned Successfullly';
				ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}	
			elseif($valid_cnt==0)
			{
				$alert .= '<br>Sorry!! unable to map any products';
			}	
		}	
		else
		{
			$alert		='Product(s) Assigned Successfullly';
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
		}	
		$alert 	= '<center><font color="red"><b>'.$alert;
		$alert	 	.= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&cust_disc_grp_id=<?=$_REQUEST['pass_cust_disc_grp_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Add New Customer Group</a>
<?			
}

elseif($_REQUEST['fpurpose']=='unassign_productsdetails') // Unassign products from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Products(s) not selected';
		}
		else
		{
			$valid_to_delete = true;
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			// find out how many products are mapped to current group
			$sql_grp ="SELECT count(products_product_id) 
								FROM 
									customer_discount_group_products_map 
								WHERE 
								  	customer_discount_group_cust_disc_grp_id = ".$_REQUEST['cust_disc_grp_id'];
			$ret_grp = $db->query($sql_grp);
			list($tot_prod) = $db->fetch_array($ret_grp);
			if($tot_prod==count($ch_arr)) // case if all products are being removed
			{
				// Get the list of customers who are mapped with current group
				$sql_cust = "SELECT customers_customer_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customer_discount_group_cust_disc_grp_id =  ".$_REQUEST['cust_disc_grp_id'];
				$ret_cust = $db->query($sql_cust);
				if($db->num_rows($ret_cust))
				{
					while ($row_cust = $db->fetch_array($ret_cust))
					{
						// Get all groups to which current customer is mapped with groups other than current group
						$sql_grps = "SELECT customer_discount_group_cust_disc_grp_id 
												FROM 
													customer_discount_customers_map 
												WHERE 
													customers_customer_id = ".$row_cust['customers_customer_id']." 
													AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['cust_disc_grp_id'];
						$ret_grps = $db->query($sql_grps);
						if ($db->num_rows($ret_grps))
						{
							while ($row_grps = $db->fetch_array($ret_grps))
							{
								// Check whether there exists products under this group
								$sql_prd = "SELECT map_id 
													FROM 
														customer_discount_group_products_map 
													WHERE 
														customer_discount_group_cust_disc_grp_id = ".$row_grps['customer_discount_group_cust_disc_grp_id']." 
													LIMIT 
														1";
								$ret_prd = $db->query($sql_prd);
								if ($db->num_rows($ret_prd))
									$valid_to_delete = false;
							}
						}					
					}
				}
			}			
			if ($valid_to_delete==true)
			{
				for($i=0;$i<count($ch_arr);$i++)
				{
					$sql_del = "DELETE FROM customer_discount_group_products_map WHERE map_id=".$ch_arr[$i]." AND sites_site_id = ".$ecom_siteid ;
					$db->query($sql_del);
				}
				$alert = 'Product(s) Unassigned Successfully';
				ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}
			else
				$alert = 'Sorry!! all product cannot be unassigned as some of the customers in current group already exists in another group which do not have products mapped to it';	
		}	
		show_display_products_discountgroup_list($_REQUEST['cust_disc_grp_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_categories') // show category list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_display_categories_discountgroup_list($_REQUEST['cust_disc_grp_id']);
}	
elseif($_REQUEST['fpurpose']=='add_categories')
{
	include ('includes/cust_discount_group/list_sel_discgrp_category.php');
}
elseif($_REQUEST['fpurpose']=='save_add_categories') // Assign Products in selected category to customer discount group
{
	$msg 		= '';
	$tot_cnt 	= $valid_cnt = 0;
	$cat_arr	= array();
	
	// Get the list of categories that are selected for assignment
	foreach($_REQUEST['checkbox'] as $v)
	{ 
		$prods_arr 	= array();
	 	// Get the list of products mapped with current category
		$sql_prods = "SELECT products_product_id 
						FROM 
							product_category_map 
						WHERE 
							product_categories_category_id = $v";
		$ret_prods = $db->query($sql_prods);
		if($db->num_rows($ret_prods))
		{
			while ($row_prods = $db->fetch_array($ret_prods))
			{	
				if(!in_array($row_prods['products_product_id'],$prods_arr)) // done to avoid repetation
					$prods_arr[] = $row_prods['products_product_id'];
			}
		}
		$cat_arr[$v] = $prods_arr;
	}

	$prev_cat_id = 0;
	foreach($cat_arr as $kk=>$vv)
	{ 
		foreach ($vv as $k=>$v)
		{
			// Get the name of the product
			$sql_prod = "SELECT product_name 
								FROM 
									products 
								WHERE 
									product_id = $v 
								LIMIT 
									1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$prod_name = stripslashes($row_prod['product_name']);
			}
			$tot_cnt++;
			$valid_to_insert = true;
			// Get the list of customer who are already mapped with this customer group
			$sql_cust = "SELECT customers_customer_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										customer_discount_group_cust_disc_grp_id = ".$_REQUEST['pass_cust_disc_grp_id'];
			$ret_cust = $db->query($sql_cust);
			if($db->num_rows($ret_cust))
			{
				while ($row_cust = $db->fetch_array($ret_cust))
				{
					$grps_arr	= array();
					// Get the groups to which current customer is mapped to other than current group
					$sql_grp = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id = ".$row_cust['customers_customer_id']." 
											AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['pass_cust_disc_grp_id'];
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						while ($row_grp = $db->fetch_array($ret_grp))
						{
							$grps_arr[] = $row_grp['customer_discount_group_cust_disc_grp_id'];
						}					
					}	
				}
				// Check whether current product is mapped to any of the above groups
				//Modified for the customer group checking messages -LG
				if(count($grps_arr)>0)
				{
					$sql_prods = "SELECT map_id,customer_discount_group_cust_disc_grp_id 
									FROM 
										customer_discount_group_products_map 
									WHERE 
										products_product_id = $v 
										AND  customer_discount_group_cust_disc_grp_id IN (".implode(',',$grps_arr).")";
					$ret_prods = $db->query($sql_prods);
					if ($db->num_rows($ret_prods))
					{
						$valid_to_insert = false;
						if ($msg!='')
							$msg .='<br/>';
						$msg .= $prod_name.'  not assigned as some/all customer(s) in this group is/are mapped with this product in following group(s)';
						while($row_prod = $db->fetch_array($ret_prods))
						{
							$sql_grp = "SELECT cust_disc_grp_name 
											FROM 
												customer_discount_group 
											WHERE 
												cust_disc_grp_id =".$row_prod['customer_discount_group_cust_disc_grp_id']." 
											LIMIT 
												1";
							$ret_grp = $db->query($sql_grp);						
							if ($db->num_rows($ret_grp))
							{
								$row_grp = $db->fetch_array($ret_grp);						
								$msg .= '<br/>'.stripslashes($row_grp['cust_disc_grp_name']);
							}	
						}
					 }
				 }
			}
			if($valid_to_insert)
			{
				/*
				if($prev_cat_id != $kk)
				{
					// Make an entry to customer_discount_group_categories_map
					// check whether category already added 
					$sql_check_cat = "SELECT map_id 
										FROM 
											customer_discount_group_categories_map 
										WHERE 
											customer_discount_group_cust_disc_grp_id =".$_REQUEST['pass_cust_disc_grp_id']." 
											AND product_categories_category_id = $kk 
										LIMIT 
											1";
					$ret_check_cat = $db->query($sql_check_cat);
					if($db->num_rows($ret_check_cat)==0)
					{
						$insert_array												= array();
						$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
						$insert_array['product_categories_category_id']				= $kk;
						$insert_array['sites_site_id']								= $ecom_siteid;
						$db->insert_from_array($insert_array,'customer_discount_group_categories_map');
						$prev_cat_id = $kk;
						$valid_cnt++;
					}	
				}
				*/ 
				$sql_check = "SELECT products_product_id 
								FROM 
									customer_discount_group_products_map 
								WHERE 
									products_product_id= $v 
									AND customer_discount_group_cust_disc_grp_id=".$_REQUEST['pass_cust_disc_grp_id']." 
								LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
					$insert_array												= array();
					$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
					$insert_array['products_product_id']						= $v;
					$insert_array['sites_site_id']								= $ecom_siteid;
					$db->insert_from_array($insert_array, 'customer_discount_group_products_map');
				}
				
			}	
		}
		     if($prev_cat_id != $kk)
				{
					// Make an entry to customer_discount_group_categories_map
					// check whether category already added 
					$sql_check_cat = "SELECT map_id 
										FROM 
											customer_discount_group_categories_map 
										WHERE 
											customer_discount_group_cust_disc_grp_id =".$_REQUEST['pass_cust_disc_grp_id']." 
											AND product_categories_category_id = $kk 
										LIMIT 
											1";
					$ret_check_cat = $db->query($sql_check_cat);
					if($db->num_rows($ret_check_cat)==0)
					{
						$insert_array												= array();
						$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
						$insert_array['product_categories_category_id']				= $kk;
						$insert_array['sites_site_id']								= $ecom_siteid;
						$db->insert_from_array($insert_array,'customer_discount_group_categories_map');
						$prev_cat_id = $kk;
						$valid_cnt++;
					}	
				}		
	}
	if($msg!='')
	{
		$alert = $msg;
		if($valid_cnt>0)
		{
			if($valid_cnt>1)
				$alert .= '<br>'.$valid_cnt.' Categories Assigned Successfullly';
			else	
				$alert .= '<br>'.$valid_cnt.' Category Assigned Successfullly';
			
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';	
		}	
		elseif($valid_cnt==0)
		{
			$alert .= '<br>Sorry!! unable to map any categories';
		}	
	}	
	else
	{
		if($valid_cnt!=count($cat_arr))
		{
			if($valid_cnt>0)
			{
				if($valid_cnt>1)
					$alert = $valid_cnt.' Categories Assigned Successfullly';
				else	
					$alert = $valid_cnt.' Category Assigned Successfullly';
				ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';	
			}	
			elseif($valid_cnt==0)
				$alert = 'Sorry!! unable to map any categories';
		}
		else	
		{
			if($valid_cnt>1)
				$alert		='Categories Assigned Successfullly';
			else
				$alert		='Category Assigned Successfullly';
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';	
		}	
	}	
	$alert 	= '<center><b>'.$alert;
	$alert	 	.= '</b></center>';
	include ('includes/cust_discount_group/list_sel_discgrp_category.php');		
	//echo $alert;
	/*		
	?>
	<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&cust_disc_grp_id=<?=$_REQUEST['pass_cust_disc_grp_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=catmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
		<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Add New Customer Group</a>
<?		
*/ 	
}
elseif($_REQUEST['fpurpose']=='unassign_categorydetails')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Categories not selected';
	}
	else
	{
		$valid_to_delete 	= true;
		$ch_arr 			= explode("~",$_REQUEST['del_ids']);
		$restrictprod_arr 	= array();
		// Get the category ids which are not marked for deletion in current group
		$sql_cats = "SELECT product_categories_category_id 
						FROM 
							customer_discount_group_categories_map 
						WHERE 
							customer_discount_group_cust_disc_grp_id=".$_REQUEST['cust_disc_grp_id']." 
							AND map_id NOT IN (".implode(",",$ch_arr).")";
		$ret_cats = $db->query($sql_cats);
		if($db->num_rows($ret_cats))
		{
			while ($row_cats = $db->fetch_array($ret_cats))
			{
				// Get the ids of products which are linked with each of the unmarked categories
				$sql_prods = "SELECT products_product_id 
								FROM 
									product_category_map 
								WHERE 
									product_categories_category_id =".$row_cats['product_categories_category_id'];
				$ret_prods = $db->query($sql_prods);
				if($db->num_rowS($ret_prods))
				{
					while ($row_prods=$db->fetch_array($ret_prods))
					{
						if(!in_array($row_prods['products_product_id'],$restrictprod_arr))
							$restrictprod_arr[] = $row_prods['products_product_id'];
					}
				}
			}
		}
		$remove_prod_arr = array();
		// Get the list of products to be removed from current customer group based on the category id selected to unassign
		for($i=0;$i<count($ch_arr);$i++)
		{
			$sql_catmap = "SELECT product_categories_category_id 
								FROM 
									customer_discount_group_categories_map 
								WHERE 
								  	map_id=".$ch_arr[$i]." 
									AND customer_discount_group_cust_disc_grp_id =".$_REQUEST['cust_disc_grp_id'];
			$ret_catmap = $db->query($sql_catmap);
			if($db->num_rows($ret_catmap))
			{
				$row_catmap = $db->fetch_array($ret_catmap);
				// Get the ids of all products which are mapped with current category
				$sql_prods = "SELECT products_product_id 
								FROM 
									product_category_map 
								WHERE 
									product_categories_category_id = ".$row_catmap['product_categories_category_id'];
				$ret_prods = $db->query($sql_prods);
				if($db->num_rows($ret_prods))
				{
					while ($row_prods = $db->fetch_array($ret_prods))
					{
						// if product mapped with current category is not to be retained with current group since there exists no mapped categories to which it is linked
						//, then assign the product id to a new array
						if(!in_array($row_prods['products_product_id'],$restrictprod_arr)) 
							$remove_prod_arr[] = $row_prods['products_product_id'];
					}
				}			
			}
		}
		if(count($remove_prod_arr))
		{
			// find out how many products are mapped to current group
			$sql_grp ="SELECT count(products_product_id) 
								FROM 
									customer_discount_group_products_map 
								WHERE 
									customer_discount_group_cust_disc_grp_id = ".$_REQUEST['cust_disc_grp_id'];
			$ret_grp = $db->query($sql_grp);
			list($tot_prod) = $db->fetch_array($ret_grp);	
			if($tot_prod==count($remove_prod_arr)) // case if all products are being removed
			{
				// Get the list of customers who are mapped with current group
				$sql_cust = "SELECT customers_customer_id 
								FROM 
									customer_discount_customers_map 
								WHERE 
									customer_discount_group_cust_disc_grp_id =  ".$_REQUEST['cust_disc_grp_id'];
				$ret_cust = $db->query($sql_cust);
				if($db->num_rows($ret_cust))
				{
					while ($row_cust = $db->fetch_array($ret_cust))
					{
						// Get all groups to which current customer is mapped with groups other than current group
						$sql_grps = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id = ".$row_cust['customers_customer_id']." 
											AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['cust_disc_grp_id'];
						$ret_grps = $db->query($sql_grps);
						if ($db->num_rows($ret_grps))
						{
							while ($row_grps = $db->fetch_array($ret_grps))
							{
								// Check whether there exists products under this group
								$sql_prd = "SELECT map_id 
												FROM 
													customer_discount_group_products_map 
												WHERE 
													customer_discount_group_cust_disc_grp_id = ".$row_grps['customer_discount_group_cust_disc_grp_id']." 
												LIMIT 
													1";
								$ret_prd = $db->query($sql_prd);
								if ($db->num_rows($ret_prd)==0)
									$valid_to_delete = false;
							}
						}					
					}
				}
			}
		}
		if ($valid_to_delete==true)
		{
			for($i=0;$i<count($remove_prod_arr);$i++)
			{
				$sql_del = "DELETE 
								FROM 
									customer_discount_group_products_map 
								WHERE 
									customer_discount_group_cust_disc_grp_id=".$_REQUEST['cust_disc_grp_id']." 
									AND products_product_id=".$remove_prod_arr[$i]." 
									AND sites_site_id = ".$ecom_siteid ;
				$db->query($sql_del);
			}
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE 
								FROM 
									customer_discount_group_categories_map 
								WHERE 
									map_id = ".$ch_arr[$i]." 
									AND customer_discount_group_cust_disc_grp_id = ".$_REQUEST['cust_disc_grp_id']." 
									AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
				$db->query($sql_del);
			}
			if(count($ch_arr)>1)
				$alert = 'Categories Unassigned Successfully';
			else
				$alert = 'Category Unassigned Successfully';
			
			ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';	
		}
		else
			$alert = 'Sorry!! all products in some of the selected categories cannot be unassigned as some of the customers in current group already exists in another group which do not have products mapped to it';			
	}			
		show_display_categories_discountgroup_list($_REQUEST['cust_disc_grp_id'],$alert);
		
}
elseif($_REQUEST['fpurpose']=='add_shelves') /* Code for shelves starts here */
{
		include ('includes/cust_discount_group/list_sel_discgrp_shelf.php');
}	
elseif($_REQUEST['fpurpose']=='list_shelves') // show customer list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_display_shelves_discountgroup_list($_REQUEST['cust_disc_grp_id']);
}	
elseif($_REQUEST['fpurpose']=='save_add_shelves') // Assign Products  to custoemr discount group
{
		$msg = '';
		$tot_cnt = $valid_cnt = 0;
		foreach($_REQUEST['checkbox'] as $v)
		{ 
			// Get the name of the product
			$sql_prod = "SELECT shelf_name FROM product_shelf WHERE shelf_id = $v LIMIT 1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$prod_name = stripslashes($row_prod['shelf_name']);
			}
			$tot_cnt++;
			$valid_to_insert = true;
			// Get the list of customer who are already mapped with this customer group
			$sql_cust = "SELECT customers_customer_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										customer_discount_group_cust_disc_grp_id = ".$_REQUEST['pass_cust_disc_grp_id'];
			$ret_cust = $db->query($sql_cust);
			if($db->num_rows($ret_cust))
			{
				while ($row_cust = $db->fetch_array($ret_cust))
				{
					$grps_arr	= array();
					// Get the groups to which current customer is mapped to other than current group
					$sql_grp = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id = ".$row_cust['customers_customer_id']." 
											AND customer_discount_group_cust_disc_grp_id <>  ".$_REQUEST['pass_cust_disc_grp_id'] ;
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						while ($row_grp = $db->fetch_array($ret_grp))
						{
							$grps_arr[] = $row_grp['customer_discount_group_cust_disc_grp_id'];
						}					
						
					}	
				}
				// Check whether current product is mapped to any of the above groups
				//Modified for the customer group checking messages -LG
						if(count($grps_arr)>0)
						{
							$sql_prods = "SELECT map_id,customer_discount_group_cust_disc_grp_id 
													FROM 
														customer_discount_group_shelfs_map 
													WHERE 
														shelves_shelf_id = $v 
														AND  customer_discount_group_cust_disc_grp_id IN (".implode(',',$grps_arr).")";
							$ret_prods = $db->query($sql_prods);
							if ($db->num_rows($ret_prods))
							{
								$valid_to_insert = false;
								if ($msg!='')
									$msg .='<br/>';
								$msg .= $prod_name.'  not assigned as some/all customer(s) in this group is/are mapped with this shelf in following group(s)';
								while($row_prod = $db->fetch_array($ret_prods))
								{
									$sql_grp = "SELECT cust_disc_grp_name 
												FROM 
													customer_discount_group 
												WHERE 
													cust_disc_grp_id =".$row_prod['customer_discount_group_cust_disc_grp_id']." 
												LIMIT 
													1";
									$ret_grp = $db->query($sql_grp);						
									if ($db->num_rows($ret_grp))
									{
										$row_grp = $db->fetch_array($ret_grp);						
										$msg .= '<br/>'.stripslashes($row_grp['cust_disc_grp_name']);
									}	
								}
							 }
						 }
			}
			if($valid_to_insert)
			{
				$sql_check = "SELECT shelves_shelf_id 
										FROM 
											customer_discount_group_shelfs_map 
										WHERE 
											shelves_shelf_id= $v 
											AND customer_discount_group_cust_disc_grp_id=".$_REQUEST['pass_cust_disc_grp_id']." 
										LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
						$insert_array																	= array();
						$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
						$insert_array['shelves_shelf_id']									= $v;
						$insert_array['sites_site_id']												= $ecom_siteid;
						$db->insert_from_array($insert_array, 'customer_discount_group_shelfs_map');
				}
				$valid_cnt++;
			}	
		}
		if($msg!='')
		{
			$alert = $msg;
			if($valid_cnt>0)
			{
				$alert .= '<br>'.$valid_cnt.' Shelf(s) Assigned Successfullly';
				ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}	
			elseif($valid_cnt==0)
			{
				$alert .= '<br>Sorry!! unable to map any products';
			}	
		}	
		else
		{
			$alert		='Shelf(s) Assigned Successfullly';
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
		}	
		$alert 	= '<center><font color="red"><b>'.$alert;
		$alert	 	.= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&cust_disc_grp_id=<?=$_REQUEST['pass_cust_disc_grp_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shelfmenu_tab_td" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Add New Customer Group</a>
<?			
}

elseif($_REQUEST['fpurpose']=='unassign_shelvesdetails') // Unassign products from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Shelf(s) not selected';
		}
		else
		{
			$valid_to_delete = true;
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			// find out how many products are mapped to current group
			$sql_grp ="SELECT count(shelves_shelf_id) 
								FROM 
									customer_discount_group_shelfs_map 
								WHERE 
								  	customer_discount_group_cust_disc_grp_id = ".$_REQUEST['cust_disc_grp_id'];
			$ret_grp = $db->query($sql_grp);
			list($tot_prod) = $db->fetch_array($ret_grp);
			if($tot_prod==count($ch_arr)) // case if all products are being removed
			{
				// Get the list of customers who are mapped with current group
				$sql_cust = "SELECT customers_customer_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customer_discount_group_cust_disc_grp_id =  ".$_REQUEST['cust_disc_grp_id'];
				$ret_cust = $db->query($sql_cust);
				if($db->num_rows($ret_cust))
				{
					while ($row_cust = $db->fetch_array($ret_cust))
					{
						// Get all groups to which current customer is mapped with groups other than current group
						$sql_grps = "SELECT customer_discount_group_cust_disc_grp_id 
												FROM 
													customer_discount_customers_map 
												WHERE 
													customers_customer_id = ".$row_cust['customers_customer_id']." 
													AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['cust_disc_grp_id'];
						$ret_grps = $db->query($sql_grps);
						if ($db->num_rows($ret_grps))
						{
							while ($row_grps = $db->fetch_array($ret_grps))
							{
								// Check whether there exists products under this group
								$sql_prd = "SELECT map_id 
													FROM 
														customer_discount_group_shelfs_map 
													WHERE 
														customer_discount_group_cust_disc_grp_id = ".$row_grps['customer_discount_group_cust_disc_grp_id']." 
													LIMIT 
														1";
								$ret_prd = $db->query($sql_prd);
								if ($db->num_rows($ret_prd))
									$valid_to_delete = false;
							}
						}					
					}
				}
			}			
			if ($valid_to_delete==true)
			{
				for($i=0;$i<count($ch_arr);$i++)
				{
					$sql_del = "DELETE FROM customer_discount_group_shelfs_map WHERE map_id=".$ch_arr[$i]." AND sites_site_id = ".$ecom_siteid ;
					$db->query($sql_del);
				}
				$alert = 'Shelf(s) Unassigned Successfully';
				ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}
			else
				$alert = 'Sorry!! all shelf cannot be unassigned as some of the customers in current group already exists in another group which do not have shelves mapped to it';	
		}	
		show_display_products_discountgroup_list($_REQUEST['cust_disc_grp_id'],$alert);
}/* Code for shelves ends here */
elseif($_REQUEST['fpurpose']=='add_pages') /* Code for pages starts here */
{
		include ('includes/cust_discount_group/list_sel_discgrp_staticpage.php');
}	
elseif($_REQUEST['fpurpose']=='list_pages') // show customer list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		show_display_pages_discountgroup_list($_REQUEST['cust_disc_grp_id']);
}	
elseif($_REQUEST['fpurpose']=='save_add_pages') // Assign Products  to custoemr discount group
{
		$msg = '';
		$tot_cnt = $valid_cnt = 0;
		foreach($_REQUEST['checkbox'] as $v)
		{ 
			// Get the name of the product
			$sql_prod = "SELECT pname FROM static_pages WHERE page_id = $v LIMIT 1";
			$ret_prod = $db->query($sql_prod);
			if ($db->num_rows($ret_prod))
			{
				$row_prod = $db->fetch_array($ret_prod);
				$prod_name = stripslashes($row_prod['pname']);
			}
			$tot_cnt++;
			$valid_to_insert = true;
			// Get the list of customer who are already mapped with this customer group
			$sql_cust = "SELECT customers_customer_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										customer_discount_group_cust_disc_grp_id = ".$_REQUEST['pass_cust_disc_grp_id'];
			$ret_cust = $db->query($sql_cust);
			if($db->num_rows($ret_cust))
			{
				while ($row_cust = $db->fetch_array($ret_cust))
				{
					$grps_arr	= array();
					// Get the groups to which current customer is mapped to other than current group
					$sql_grp = "SELECT customer_discount_group_cust_disc_grp_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customers_customer_id = ".$row_cust['customers_customer_id']." 
											AND customer_discount_group_cust_disc_grp_id <>  ".$_REQUEST['pass_cust_disc_grp_id'] ;
					$ret_grp = $db->query($sql_grp);
					if ($db->num_rows($ret_grp))
					{
						while ($row_grp = $db->fetch_array($ret_grp))
						{
							$grps_arr[] = $row_grp['customer_discount_group_cust_disc_grp_id'];
						}					
						
					}	
				}
				// Check whether current product is mapped to any of the above groups
				//Modified for the customer group checking messages -LG
						if(count($grps_arr)>0)
						{
							$sql_prods = "SELECT map_id,customer_discount_group_cust_disc_grp_id 
													FROM 
														customer_discount_group_staticpage_map 
													WHERE 
														static_page_id = $v 
														AND  customer_discount_group_cust_disc_grp_id IN (".implode(',',$grps_arr).")";
							$ret_prods = $db->query($sql_prods);
							if ($db->num_rows($ret_prods))
							{
								$valid_to_insert = false;
								if ($msg!='')
									$msg .='<br/>';
								$msg .= $prod_name.'  not assigned as some/all customer(s) in this group is/are mapped with this static page in following group(s)';
								while($row_prod = $db->fetch_array($ret_prods))
								{
									$sql_grp = "SELECT cust_disc_grp_name 
												FROM 
													customer_discount_group 
												WHERE 
													cust_disc_grp_id =".$row_prod['customer_discount_group_cust_disc_grp_id']." 
												LIMIT 
													1";
									$ret_grp = $db->query($sql_grp);						
									if ($db->num_rows($ret_grp))
									{
										$row_grp = $db->fetch_array($ret_grp);						
										$msg .= '<br/>'.stripslashes($row_grp['cust_disc_grp_name']);
									}	
								}
							 }
						 }
			}
			if($valid_to_insert)
			{
				$sql_check = "SELECT static_page_id 
										FROM 
											customer_discount_group_staticpage_map 
										WHERE 
											static_page_id= $v 
											AND customer_discount_group_cust_disc_grp_id=".$_REQUEST['pass_cust_disc_grp_id']." 
										LIMIT 1";
				$ret_check = $db->query($sql_check);
				if ($db->num_rows($ret_check)==0)
				{
						$insert_array																	= array();
						$insert_array['customer_discount_group_cust_disc_grp_id']	= $_REQUEST['pass_cust_disc_grp_id'];
						$insert_array['static_page_id']									= $v;
						$insert_array['sites_site_id']												= $ecom_siteid;
						$db->insert_from_array($insert_array, 'customer_discount_group_staticpage_map');
				}
				$valid_cnt++;
			}	
		}
		if($msg!='')
		{
			$alert = $msg;
			if($valid_cnt>0)
			{
				$alert .= '<br>'.$valid_cnt.' Static Page(s) Assigned Successfullly';
				ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}	
			elseif($valid_cnt==0)
			{
				$alert .= '<br>Sorry!! unable to map any Static Page';
			}	
		}	
		else
		{
			$alert		='Static Page(s) Assigned Successfullly';
			ChangeStatus_customer_discount_group($_REQUEST['pass_cust_disc_grp_id'],0);
			$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
		}	
		$alert 	= '<center><font color="red"><b>'.$alert;
		$alert	 	.= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=cust_discount_group&search_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&cust_disc_grp_id=<?=$_REQUEST['pass_cust_disc_grp_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_cust_disc_grp_id']?>&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=pagemenu_tab_td" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_discount_group&fpurpose=add&pass_group_name=<?=$_REQUEST['pass_group_name']?>&sort_by=<?=$_REQUEST['pass_sortby']?>&sort_order=<?=$_REQUEST['pass_sortorder']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Add New Customer Group</a>
<?			
}

elseif($_REQUEST['fpurpose']=='unassign_pagesdetails') // Unassign products from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/cust_discount_group/ajax/cust_discount_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Static Page(s) not selected';
		}
		else
		{
			$valid_to_delete = true;
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			// find out how many products are mapped to current group
			$sql_grp ="SELECT count(static_page_id) 
								FROM 
									customer_discount_group_staticpage_map 
								WHERE 
								  	customer_discount_group_cust_disc_grp_id = ".$_REQUEST['cust_disc_grp_id'];
			$ret_grp = $db->query($sql_grp);
			list($tot_prod) = $db->fetch_array($ret_grp);
			if($tot_prod==count($ch_arr)) // case if all products are being removed
			{
				// Get the list of customers who are mapped with current group
				$sql_cust = "SELECT customers_customer_id 
										FROM 
											customer_discount_customers_map 
										WHERE 
											customer_discount_group_cust_disc_grp_id =  ".$_REQUEST['cust_disc_grp_id'];
				$ret_cust = $db->query($sql_cust);
				if($db->num_rows($ret_cust))
				{
					while ($row_cust = $db->fetch_array($ret_cust))
					{
						// Get all groups to which current customer is mapped with groups other than current group
						$sql_grps = "SELECT customer_discount_group_cust_disc_grp_id 
												FROM 
													customer_discount_customers_map 
												WHERE 
													customers_customer_id = ".$row_cust['customers_customer_id']." 
													AND customer_discount_group_cust_disc_grp_id <> ".$_REQUEST['cust_disc_grp_id'];
						$ret_grps = $db->query($sql_grps);
						if ($db->num_rows($ret_grps))
						{
							while ($row_grps = $db->fetch_array($ret_grps))
							{
								// Check whether there exists products under this group
								$sql_prd = "SELECT map_id 
													FROM 
														customer_discount_group_staticpage_map 
													WHERE 
														customer_discount_group_cust_disc_grp_id = ".$row_grps['customer_discount_group_cust_disc_grp_id']." 
													LIMIT 
														1";
								$ret_prd = $db->query($sql_prd);
								if ($db->num_rows($ret_prd))
									$valid_to_delete = false;
							}
						}					
					}
				}
			}			
			if ($valid_to_delete==true)
			{
				for($i=0;$i<count($ch_arr);$i++)
				{
					$sql_del = "DELETE FROM customer_discount_group_staticpage_map WHERE map_id=".$ch_arr[$i]." AND sites_site_id = ".$ecom_siteid ;
					$db->query($sql_del);
				}
				$alert = 'Static Page(s) Unassigned Successfully';
				ChangeStatus_customer_discount_group($_REQUEST['cust_disc_grp_id'],0);
				$alert .= '<br><br>Customer Discount Group Deactivated. Please reactivate it.';
			}
			else
				$alert = 'Sorry!! all Static Page cannot be unassigned as some of the customers in current group already exists in another group which do not have shelves mapped to it';	
		}	
		show_display_pages_discountgroup_list($_REQUEST['cust_disc_grp_id'],$alert);
}
/* Code for pages ends here */
function Activate_Customer_Discount_Group($group_id)
{
	global $db,$ecom_siteid;
	$err = '';
	// Get the list of customers mapped with current discount group
	$sql_cust = "SELECT map_id, customer_discount_group_cust_disc_grp_id, customers_customer_id 
					FROM 
						customer_discount_customers_map a , customers b  
					WHERE 
                                                a.customers_customer_id=b.customer_id 
                                                AND customer_discount_group_cust_disc_grp_id = $group_id";
	$ret_cust = $db->query($sql_cust);
	$cust_arr = array();
	if ($db->num_rows($ret_cust))
	{
		while ($row_cust = $db->fetch_array($ret_cust))
		{
			$cust_arr[] = $row_cust['customers_customer_id'];
		}
		$prod_arr = array();
		// Get the list of products in current group
		
		$sql_prods = "SELECT b.map_id,a.product_id,customer_discount_group_cust_disc_grp_id
                                    FROM 
                                            products a, customer_discount_group_products_map b 
                                    WHERE 
                                            a.sites_site_id=$ecom_siteid 
                                            AND b.customer_discount_group_cust_disc_grp_id=$group_id 
                                            AND a.product_id=b.products_product_id";
		$ret_prods = $db->query($sql_prods);
		if($db->num_rows($ret_prods))
		{
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				$prod_arr[] = $row_prods['product_id'];
			}
		}
                if(count($prod_arr)==0)
                {
                   $err = ' as No Products mapped with the discount group';
                }
                if($err=='')
                {
                  if(count($cust_arr))
                  {  
			$cust_str = implode(',',$cust_arr);
			// Get the discount group ids of other groups which are related to customers in groups other than current group
			$sql_cust = "SELECT distinct customer_discount_group_cust_disc_grp_id 
                                        FROM 
                                                customer_discount_customers_map 
                                        WHERE 
                                                sites_site_id = $ecom_siteid 
                                                AND customer_discount_group_cust_disc_grp_id <> $group_id 
                                                AND customers_customer_id IN ($cust_str)";
			$ret_cust = $db->query($sql_cust);
			if($db->num_rows($ret_cust))
			{
				while ($row_cust = $db->fetch_array($ret_cust))
				{
					// Get the details of products in obtained discount group 
					$tempprod_arr = array();
					// Get the list of products in current group
					$sql_prods = "SELECT map_id, customer_discount_group_cust_disc_grp_id, products_product_id 
									FROM 
										customer_discount_group_products_map 
									WHERE 
										customer_discount_group_cust_disc_grp_id = ".$row_cust['customer_discount_group_cust_disc_grp_id']."
										AND sites_site_id = $ecom_siteid ";
					$ret_prods = $db->query($sql_prods);
					if($db->num_rows($ret_prods))
					{
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							$tempprod_arr[] = $row_prods['products_product_id'];
						}
					}
					if(count($prod_arr)==0) // case if no products exists in current group
					{
						if(count($tempprod_arr)==0)
						{
							$groupname = '';
							$sql_grp = "SELECT cust_disc_grp_name 
											FROM 
												customer_discount_group 
											WHERE 
												cust_disc_grp_id = ".$row_cust['customer_discount_group_cust_disc_grp_id']." 
											LIMIT 
												1";
							$ret_grp = $db->query($sql_grp);
							if($db->num_rows($ret_grp))
							{
								$row_grp = $db->fetch_array($ret_grp);
								$groupname = stripslashes($row_grp['cust_disc_grp_name']);
							}
							$err = "<br> Customer already exists in group \"".$groupname."\""; 
							return $err;
						}
							
					}
					else // case if atleast 1 product exists in current group
					{
						$prod_str = implode(',',$prod_arr);
						// Check whether atleast one product in current group exists in obtained group
						$sql_check = "SELECT map_id 
                                                                  FROM 
                                                                          customer_discount_group_products_map 
                                                                  WHERE 
                                                                          customer_discount_group_cust_disc_grp_id = ".$row_cust['customer_discount_group_cust_disc_grp_id']." 
                                                                          AND products_product_id IN (".$prod_str.") 
                                                                  LIMIT 	
                                                                          1";
						$ret_check = $db->query($sql_check);
						if($db->num_rows($ret_check)>0)
						{
							$groupname = '';
							$sql_grp = "SELECT cust_disc_grp_name 
                                                                      FROM 
                                                                              customer_discount_group 
                                                                      WHERE 
                                                                              cust_disc_grp_id = ".$row_cust['customer_discount_group_cust_disc_grp_id']." 
                                                                      LIMIT 
                                                                              1";
							$ret_grp = $db->query($sql_grp);
							if($db->num_rows($ret_grp))
							{
								$row_grp = $db->fetch_array($ret_grp);
								$groupname = stripslashes($row_grp['cust_disc_grp_name']);
							}
							$err = "<br>Some of the products / customers  in current group are already mapped with the group \"".$groupname ."\""; 
							return $err;
						}				
					}
				}
			}
			
		}
		}
	}	
	else
	{
		$err = ' as No Customers mapped with the discount group';
	}	
	return $err;	
}
function ChangeStatus_customer_discount_group($group_id,$stat=0)
{
	global $db,$ecom_siteid;
	$sql_update = "UPDATE customer_discount_group 
					SET 
						cust_disc_grp_active = $stat 
					WHERE
						cust_disc_grp_id = $group_id 
						AND sites_site_id = $ecom_siteid 
					LIMIT 
						1";
	$db->query($sql_update);
}
?>
