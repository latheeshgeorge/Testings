<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/preset_variable/list_presetvariable.php");
}
else if($_REQUEST['fpurpose']=='add') // add preset variable
{
	$product_id =$edit_id = $_REQUEST['checkbox'][0];
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
	include("includes/preset_variable/add_presetvariable.php");
}
elseif ($_REQUEST['fpurpose'] == 'prodvar_onchange')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/preset_variable/ajax/presetvariable_ajax_functions.php');
	showvariablevalue_list($_REQUEST['edit_id'],$_REQUEST['var_value_pass'],$alert);
}
elseif($_REQUEST['fpurpose']=='save_addprodvar') // insert product variables
{
		if($_REQUEST['prodvar_Submit'] or $_REQUEST['saveandaddmore']==1)
		{
			$alert='';
			$fieldRequired 		= array($_REQUEST['var_name']);
			$fieldDescription 	= array('Specify Variable Name');
			$fieldEmail 		= array();
			$fieldConfirm 		= array();
			$fieldConfirmDesc 	= array();
			$fieldNumeric 		= array();
			$fieldNumericDesc 	= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if($alert=='')
			{
				// Check whether the variable name already exists for current product
				$sql_check = "SELECT var_id 
										FROM 
											product_preset_variables 
										WHERE 
											var_name='".$_REQUEST['var_name']."'
								 			AND sites_site_id = ".$ecom_siteid." 
										LIMIT 
											1";
				$ret_check = $db->query($sql_check);
				if($db->num_rows($ret_check))
				{
					$alert = 'Variable already exists';
				}
			}
			if($alert=='')
			{
				$var_order 	= (!is_numeric($_REQUEST['var_order']))?0:trim($_REQUEST['var_order']);
				$var_hide 	= ($_REQUEST['var_hide'])?1:0;
				$var_vals	= $_REQUEST['var_value_exists'];
				
				$insert_array								= array();
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['var_name']				= add_slash($_REQUEST['var_name']);
				$insert_array['var_order']				= $var_order;
				$insert_array['var_hide']				= $var_hide;
				$insert_array['var_value_exists']		= $var_vals;
				$db->insert_from_array($insert_array,'product_preset_variables');
				$insert_id	= $db->insert_id();
				if ($var_vals==1) // case if values exists
				{
					for($i=0;$i<count($_REQUEST['var_val']);$i++)
					{
						$insert_array			= array();
						$var_val					= trim($_REQUEST['var_val'][$i]);
						$var_valprice			= (is_numeric($_REQUEST['var_valprice'][$i]))?$_REQUEST['var_valprice'][$i]:0;
						$var_valorder			= (is_numeric($_REQUEST['var_valorder'][$i]))?$_REQUEST['var_valorder'][$i]:0;
						if($var_val)
						{
							// Check whether value already exists, if exists ignore the new one with same value
							$sql_check = "SELECT var_value_id 
													FROM 
														product_preset_variable_data  
													WHERE 
														product_variables_var_id=$insert_id  
														AND var_value='$var_val' 
														AND sites_site_id = $ecom_siteid 
													LIMIT 1";
							$ret_check = $db->query($sql_check);
							if ($db->num_rows($ret_check)==0)
							{
									$insert_array['product_variables_var_id'] 	= $insert_id;
									$insert_array['var_value'] 							= add_slash($var_val);
									$insert_array['var_addprice'] 						= $var_valprice;
									$insert_array['var_order'] 							= $var_valorder;
									$insert_array['sites_site_id'] 						= $ecom_siteid;
									$db->insert_from_array($insert_array,'product_preset_variable_data');
							}	
						}	
					}
				}
				else // case if values does not exists
				{
					$price								= (is_numeric($_REQUEST['var_price']))?$_REQUEST['var_price']:0;
					$update_array						= array();
					$update_array['var_price']	= $price;
					$db->update_from_array($update_array,'product_preset_variables',array('var_id'=>$insert_id));
				}
				if($_REQUEST['saveandaddmore']!=1) // case if save only is clicked
				{
					$alert .= '<br><span class="redtext"><b>Preset Variable Added Successfully</b></span><br>';
					echo $alert;				
					?>
					<br />
					<a class="smalllink" href="home.php?request=preset_var&fpurpose=add&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go Back to the Preset Product Variable Add page</a><br />
					<br />
					<a class="smalllink" href="home.php?request=preset_var&fpurpose=edit&checkbox[0]=<?php echo $insert_id?>&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?php echo $_REQUEST['curtab']?>">Go to the Preset Product Variable Edit Page</a><br />
					<br />
					<a class="smalllink" href="home.php?request=preset_var&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to the Preset Product Variable Listing Page</a><br />
					<br />
					<?
				}
				else // case if save and add more is clicked
				{
					$alert 						= 'Preset Variable Details Saved Successfully';
					$edit_id						= $insert_id;
					$_REQUEST['edit_id']	= $insert_id;
					$product_id				= $_REQUEST['checkbox'][0];
					$ajax_return_function = 'ajax_return_contents';
					include "ajax/ajax.php";
					include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
					include("includes/preset_variable/edit_presetvariable.php");
				}
			}
			else
			{
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
				include("includes/preset_variable/add_presetvariable.php");
			}			
		}
	}
elseif($_REQUEST['fpurpose']=='save_preset_order') // preset order 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	//print_r($OrderArr);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array						= array();
		$update_array['var_order']	= $OrderArr[$i];
		$db->update_from_array($update_array,'product_preset_variables',array('var_id'=>$IdArr[$i]));
	}
	$alert = 'Order saved successfully.';
	include ('../includes/preset_variable/list_presetvariable.php');
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update preset hidden status
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$type_ids_arr 		= explode('~',$_REQUEST['type_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($type_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['var_hide']	= $new_status;
			$help_id 						= $type_ids_arr[$i];	
			$db->update_from_array($update_array,'product_preset_variables',array('var_id'=>$help_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/preset_variable/list_presetvariable.php');
}
else if($_REQUEST['fpurpose']=='delete') // Delete help
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	if ($_REQUEST['del_ids'] == '')
	{
		$alert = 'Sorry Preset Variable not selected';
	}
	else
	{
		$del_arr = explode("~",$_REQUEST['del_ids']);
		for($i=0;$i<count($del_arr);$i++)
		{
			if(trim($del_arr[$i]))
			{
				$sql_del = "DELETE FROM product_preset_variable_data WHERE sites_site_id=$ecom_siteid AND product_variables_var_id=".$del_arr[$i];
				$db->query($sql_del);
				$sql_del = "DELETE FROM product_preset_variables WHERE sites_site_id = $ecom_siteid AND var_id=".$del_arr[$i]." LIMIT 1";
				$db->query($sql_del);
			 }	  
		}
	}
	$alert = 'Delete operation successfull';
	include ('../includes/preset_variable/list_presetvariable.php');
}
else if($_REQUEST['fpurpose']=='edit') // Edit help
{
	$edit_id = $_REQUEST['checkbox'][0];
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
	include("includes/preset_variable/edit_presetvariable.php");
}
elseif ($_REQUEST['fpurpose']=='add_prodvarimg')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif ($_REQUEST['fpurpose']=='rem_presetvarimg')
	{
		if($_REQUEST['remvarvalueimg']==1) // case if coming to remove the image assigned to a variable value
		{
			$update_sql = "UPDATE 
								product_preset_variable_data 
							SET 
								images_image_id = 0 
							WHERE 
								var_value_id = ".$_REQUEST['src_id']." 
								AND product_variables_var_id=".$_REQUEST['srcvar_id']." 
							LIMIT 
								1";
			$db->query($update_sql);
			$alert = 'Image unassigned successfully';
			$product_id = $_REQUEST['checkbox'][0];
			$edit_id	= $_REQUEST['edit_id'];	
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
	include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
	include("includes/preset_variable/edit_presetvariable.php");
		}
	}
elseif($_REQUEST['fpurpose'] == 'save_editprodvar') // update product variables
{
	if($_REQUEST['prodvar_Submit'] or $_REQUEST['saveandaddmore']==1)
	{
		$alert='';
		$fieldRequired 		= array($_REQUEST['var_name']);
		$fieldDescription 		= array('Specify Variable Name');
		$fieldEmail 				= array();
		$fieldConfirm 			= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 			= array();
		$fieldNumericDesc 	= array();
		$edit_id					= $_REQUEST['edit_id'];
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if($alert=='')
		{
			// Check whether the variable name already exists for current product
			$sql_check = "SELECT var_id 
									FROM 
										product_preset_variables 
									WHERE 
										var_name='".addslashes($_REQUEST['var_name'])."'  
										AND sites_site_id = ".$ecom_siteid." 
										AND var_id <> ".$_REQUEST['checkbox'][0]." 
									LIMIT 
										1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				$alert = 'Preset Variable already exists';
			}
		}
		if($alert=='')
		{
			
			$var_order 	= (!is_numeric($_REQUEST['var_order']))?0:trim($_REQUEST['var_order']);
			$var_hide 	= ($_REQUEST['var_hide'])?1:0;
			$var_vals	= $_REQUEST['var_value_exists'];
			
			$update_array										= array();
			$update_array['sites_site_id']				= $ecom_siteid;
			$update_array['var_name']					= add_slash($_REQUEST['var_name']);
			$update_array['var_order']					= $var_order;
			$update_array['var_hide']						= $var_hide;
			$update_array['var_value_exists']			= $var_vals;
			$db->update_from_array($update_array,'product_preset_variables',array('var_id'=>$_REQUEST['checkbox'][0]));
			
			$edit_id	= $_REQUEST['checkbox'][0];
			if ($var_vals==1)// case values exists for variable
			{
				$i=0;
				foreach ($_REQUEST as $k=>$v)
				{
					if(substr($k,0,7)=='extvar_')
					{
						$cur_arr 		= explode("_",$k);
						$curid			= $cur_arr[2];
						$curval			= trim($_REQUEST['extvar_val_'.$curid]);
						$curprice		= trim($_REQUEST['extvar_valprice_'.$curid]);
						$curorder		= trim($_REQUEST['extvar_valorder_'.$curid]);
						$var_val		= $curval;
						$var_valprice	= (is_numeric($curprice))?$curprice:0;
						$var_valorder	= (is_numeric($curorder))?$curorder:0;
						
						// Check whether value already exists, if exists ignore the new one with same value
						$sql_check = "SELECT var_value_id 
												FROM 
														product_preset_variable_data 
												WHERE 
													product_variables_var_id=$edit_id  
													AND var_value='$var_val' 
													AND var_value_id <> $curid 
												LIMIT 
													1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0)
						{
							if($var_val)
							{
								$update_array												= array();
								$update_array['product_variables_var_id'] 		= $edit_id;
								$update_array['sites_site_id'] 						= $ecom_siteid;
								$update_array['var_value'] 							= add_slash($var_val);
								$update_array['var_addprice'] 						= $var_valprice;
								$update_array['var_order'] 							= $var_valorder;
								$db->update_from_array($update_array,'product_preset_variable_data',array('var_value_id'=>$curid));
							}	
							else
							{
								$sql_del = "DELETE FROM product_preset_variable_data WHERE var_value_id=".$curid;
								$db->query($sql_del);
							}
						}	
													
					}
				
				}
				// Section to handle the case of newly added variable values
				for($i=0;$i<count($_REQUEST['var_val']);$i++)
				{
					$insert_array			= array();
					$var_val				= trim($_REQUEST['var_val'][$i]);
					$var_valprice			= (is_numeric($_REQUEST['var_valprice'][$i]))?$_REQUEST['var_valprice'][$i]:0;
					$var_valorder			= (is_numeric($_REQUEST['var_valorder'][$i]))?$_REQUEST['var_valorder'][$i]:0;
					
					if($var_val)
					{
						// Check whether value already exists, if exists ignore the new one with same value
						$sql_check = "SELECT var_value_id 
												FROM 
													product_preset_variable_data 
												WHERE 
													product_variables_var_id=$edit_id  
													AND var_value='$var_val' 
												LIMIT 
													1";
						$ret_check = $db->query($sql_check);
						if ($db->num_rows($ret_check)==0)
						{
								$insert_array['product_variables_var_id'] 	= $edit_id;
								$insert_array['sites_site_id'] 						= $ecom_siteid;
								$insert_array['var_value'] 							= add_slash($var_val);
								$insert_array['var_addprice'] 						= $var_valprice;
								$insert_array['var_order'] 							= $var_valorder;
								$db->insert_from_array($insert_array,'product_preset_variable_data');
						}	
					}	
				}
			}
			else // case of values does not exists
			{
				$sql_del = "DELETE FROM product_preset_variable_data WHERE product_variables_var_id=".$_REQUEST['edit_id'];
				$db->query($sql_del);
				$price								= (is_numeric($_REQUEST['var_price']))?$_REQUEST['var_price']:0;
				$update_array							= array();
				$update_array['var_price']		= $price;
				$db->update_from_array($update_array,'product_preset_variables',array('var_id'=>$_REQUEST['edit_id']));
			}
			if($_REQUEST['saveandaddmore']!=1) // case if save only is requested
			{
				$alert .= '<br><span class="redtext"><b>Preset Variable Updated Successfully</b></span><br>';
				echo $alert;				
				?>
				<br />
				<a class="smalllink" href="home.php?request=preset_var&fpurpose=add&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go Back to the Preset Product Variable Add page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=preset_var&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=<?=$_REQUEST['curtab']?>">Go to the Preset Product Variable Edit Page</a><br />
				<br />
				<a class="smalllink" href="home.php?request=preset_var&search_variable_name=<?php echo $_REQUEST['search_variable_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go to Preset Product Variable Listing page</a><br />
				<br />
			<?
			}
			else // case if save and add more is requested
			{
				$alert = 'Preset Variable Updated Successfully';
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				$edit_id = $_REQUEST['checkbox'][0];
				include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
				include("includes/preset_variable/edit_presetvariable.php");
			}				
		}
		else
		{
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			$edit_id = $_REQUEST['checkbox'][0];
			include ('includes/preset_variable/ajax/presetvariable_ajax_functions.php');
			include("includes/preset_variable/edit_presetvariable.php");
		}			
	
	}	
}
?>
