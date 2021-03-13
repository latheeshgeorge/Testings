<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/product_stores/list_product_store.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		include ('includes/product_stores/add_product_store.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 		= explode('~',$_REQUEST['catids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$update_array					= array();
			$update_array['shop_active']	= $new_status;
			$cur_id 						= $shopid_arr[$i];	
			$db->update_from_array($update_array,'sites_shops',array('shop_id'=>$cur_id));
			// Deleting Cache
			//delete_shop_cache($cur_id);
		}
		$alert = 'Active Status changed successfully.';
		include ('../includes/product_stores/list_product_store.php');
		
	}
	elseif($_REQUEST['fpurpose']=='save_store_order') // Shelf order 
	{
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$IdArr=explode('~',$_REQUEST['Idstr']);
		$OrderArr=explode('~',$_REQUEST['OrderStr']);
		for($i=0;$i<count($IdArr);$i++)
		{
			$update_array					= array();
			$update_array['shop_order']	= $OrderArr[$i];
			$db->update_from_array($update_array,'sites_shops',array('shop_id'=>$IdArr[$i]));
			// Delete Cache
			//delete_shelf_cache($id);	
		}
		
		$alert = 'Order saved successfully.';
		include ('../includes/product_stores/list_product_store.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
	if ($_REQUEST['productstore_Submit'])
		{
			$alert = '';
				//#Server side validation
		
				$fieldRequired = array($_REQUEST['shop_title'],$_REQUEST['shop_address'],$_REQUEST['shop_phone'],$_REQUEST['shop_mobile'],$_REQUEST['shop_email']);
				$fieldDescription = array('Warehouse Name','Address','Phone','Mobile','Email');
				$fieldEmail = array($_REQUEST['shop_email']);
				$fieldConfirm = array();
				$fieldConfirmDesc = array();
				$fieldNumeric = array();
				$fieldNumericDesc = array();
				serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				$sql_check = "SELECT count(*) as cnt FROM sites_shops WHERE shop_title='".$_REQUEST['shop_title']."' AND sites_site_id=".$ecom_siteid;
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
			
				if($row_check['cnt'] > 0) {
					$alert = 'Branch name already exists for the Same site';
				}
				
				if(!$alert) {
					$insert_array = array();
					//##############################
					//Inserting into sites_shops table
					//##############################
					$insert_array['sites_site_id']					= $ecom_siteid;
					$insert_array['shop_title']						= addslashes($_REQUEST['shop_title']);
					$insert_array['shop_address']					= addslashes($_REQUEST['shop_address']);
					$insert_array['shop_phone']						= add_slash($_REQUEST['shop_phone']);
					$insert_array['shop_mobile']					= addslashes($_REQUEST['shop_mobile']);
					$insert_array['shop_email']						= add_slash($_REQUEST['shop_email']);
					$insert_array['shop_contactperson']				= add_slash($_REQUEST['shop_contactperson']);
					$insert_array['shop_conatactperson_designation']= add_slash($_REQUEST['shop_conatactperson_designation']);
					$insert_array['shop_order']						= add_slash($_REQUEST['shop_order']);
					$insert_array['shop_active']					= ($_REQUEST['shop_active'])?1:0;
					$db->insert_from_array($insert_array, 'sites_shops');
					$insert_shopid = $db->insert_id();//#Getting generated site id
				$alert = '<center><font color="red"><b>Branch Successfully Added</b></font><br>';
					echo $alert;
					?>
					<br /><a class="smalllink" href="home.php?request=product_stores&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				  <a class="smalllink" href="home.php?request=product_stores&fpurpose=edit&checkbox[0]=<?=$insert_shopid?>&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				  <a class="smalllink" href="home.php?request=product_stores&fpurpose=add&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>

					<?php
				} else {
					include ('includes/product_stores/add_product_store.php');

				}
			}	
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Branch not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
						$delprod_arr = array();
						// Get the list of products linked with current store
						$sql_delprod = "SELECT products_product_id 
											FROM 
												product_shop_stock 
											WHERE 
												sites_shops_shop_id = ".$del_arr[$i];
						$ret_delprod = $db->query($sql_delprod);
						if($db->num_rows($ret_delprod))
						{
							while ($row_delprod = $db->fetch_array($ret_delprod))
							{
								$delprod_arr[] = $row_delprod['products_product_id'];
							}
						}						
												
					// Check whether subshops exists for current shop
						$sql_del = "DELETE FROM product_shop_stock WHERE sites_shops_shop_id=".$del_arr[$i];
						$db->query($sql_del);
						$sql_del = "DELETE FROM product_shop_variable_combination_stock WHERE sites_shops_shop_id=".$del_arr[$i];
						$db->query($sql_del);
						$sql_del = "DELETE FROM product_shop_variable_data WHERE sites_shops_shop_id=".$del_arr[$i];
						$db->query($sql_del);
						$sql_del = "DELETE FROM product_shop_variables WHERE sites_shops_shop_id=".$del_arr[$i];
						$db->query($sql_del);
						$sql_del = "DELETE FROM sites_shops WHERE sites_site_id=$ecom_siteid AND shop_id=".$del_arr[$i];
						$db->query($sql_del);
						$del_count++;
						for ($ii=0;$ii<count($delprod_arr);$ii++)
						{
							recalculate_actual_stock($delprod_arr[$ii]); // Recalculating the stock
						}
				}	
			}
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
						$alert .= $del_count." Product Branch(s) Deleted Successfully";
			}			
		}	
		include ('../includes/product_stores/list_product_store.php');
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$edit_id = $_REQUEST['checkbox'][0];
		include ('includes/product_stores/edit_product_store.php');
	}
	elseif($_REQUEST['fpurpose']=='edit_store')
	{
	if($_REQUEST['updateproductstore_Submit']){
				$sql_check = "SELECT count(*) as cnt FROM sites_shops WHERE shop_title='".$_REQUEST['shop_title']."' AND shop_id<>".$_REQUEST['edit_id']." AND sites_site_id=".$ecom_siteid;
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
			
				if($row_check['cnt'] > 0) {
					$alert = 'Branch name already exists for the Same site';
				}if(!$alert) {
					$update_array = array();
					//##############################
					//Updating into sites_shops table
					//##############################
					$update_array['sites_site_id']					= $ecom_siteid;
					$update_array['shop_title']						= addslashes($_REQUEST['shop_title']);
					$update_array['shop_address']					= addslashes($_REQUEST['shop_address']);
					$update_array['shop_phone']						= add_slash($_REQUEST['shop_phone']);
					$update_array['shop_mobile']					= addslashes($_REQUEST['shop_mobile']);
					$update_array['shop_email']						= add_slash($_REQUEST['shop_email']);
					$update_array['shop_contactperson']				= add_slash($_REQUEST['shop_contactperson']);
					$update_array['shop_conatactperson_designation']= add_slash($_REQUEST['shop_conatactperson_designation']);
					$update_array['shop_order']						= add_slash($_REQUEST['shop_order']);
					$update_array['shop_active']					= ($_REQUEST['shop_active'])?1:0;
					$db->update_from_array($update_array, 'sites_shops','shop_id', $_REQUEST['edit_id']);
					//$insert_shopid = $db->insert_id();//#Getting generated site id
				   $alert = '<center><font color="red"><b>Branch Successfully Updated</b></font><br>';
					echo $alert;
					?>
					<br /><a class="smalllink" href="home.php?request=product_stores&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
						  <a class="smalllink" href="home.php?request=product_stores&fpurpose=edit&checkbox[0]=<?=$_REQUEST['edit_id']?>&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
						  <a class="smalllink" href="home.php?request=product_stores&fpurpose=add&storename=<?=$_REQUEST['storename']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>
		
							<?php
						} else {
							include ('includes/product_stores/edit_product_store.php');
		
				     }
			}		 
		
	}
	
?>
