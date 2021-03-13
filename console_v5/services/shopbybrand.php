<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/shopbybrand/list_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		//include_once("classes/fckeditor.php");	
		include ('includes/shopbybrand/add_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		if ($_REQUEST['shopbrand_Submit'])
		{
			$alert='';
			$fieldRequired 			= array($_REQUEST['shopbrand_name']);
			$fieldDescription 		= array('Product Shop Name');
			$fieldEmail 			= array();
			$fieldConfirm 			= array();
			$fieldConfirmDesc 		= array();
			$fieldNumeric 			= array();
			$fieldNumericDesc 		= array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the product shop name already exists
				$sql_exists 	= "SELECT count(shopbrand_id) FROM product_shopbybrand WHERE 
									shopbrand_name='".trim(add_slash($_REQUEST['shopbrand_name']))."' AND sites_site_id=$ecom_siteid
									AND shopbrand_parent_id=".$_REQUEST['shopbrand_parent_id']." LIMIT 1";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Product Shop name already exists";
			}        
			if ($alert=='')
			{
				if($_REQUEST['shopbrand_product_showimage']=='' &&  $_REQUEST['shopbrand_product_showtitle']=='' &&   
				   $_REQUEST['shopbrand_product_showshortdescription']=='' &&  $_REQUEST['shopbrand_product_showprice']=='' && $_REQUEST['shopbrand_product_showrating']=='' && $_REQUEST['shopbrand_product_showbonuspoints']=='')  {
				   		$alert = "Sorry! Please Check any of Product Items to Display ";	
				   } 
				
			}	

			if ($alert=='')
			{
				
				$insert_array											= array();
				$insert_array['sites_site_id']							= $ecom_siteid;
				$insert_array['shopbrand_parent_id']					= add_slash(trim($_REQUEST['shopbrand_parent_id']));
				$insert_array['shopbrand_name']							= add_slash(trim($_REQUEST['shopbrand_name']));
				$insert_array['shopbrand_description']					= add_slash(trim($_REQUEST['shopbrand_description']),false);
				$insert_array['shopbrand_bottomdescription']			= add_slash(trim($_REQUEST['shopbrand_bottomdescription']),false);
				$insert_array['shopbrand_hide']							= ($_REQUEST['shopbrand_hide'])?1:0;
				$insert_array['shopbrand_product_displaytype']			= add_slash(trim($_REQUEST['shopbrand_product_displaytype']));
				$insert_array['shopbrand_subshoplisttype']				= add_slash(trim($_REQUEST['shopbrand_subshoplisttype']));
				$insert_array['shopbrand_product_showimage']			= ($_REQUEST['shopbrand_product_showimage'])?1:0;
				$insert_array['shopbrand_showimageofproduct']		    	= ($_REQUEST['shop_showimageofproduct'])?1:0;
				
				$insert_array['shopbrand_turnoff_mainimage']		    = ($_REQUEST['shopbrand_turnoff_mainimage'])?1:0;
				$insert_array['shopbrand_turnoff_moreimages']		    = ($_REQUEST['shopbrand_turnoff_moreimages'])?1:0;

				$insert_array['shopbrand_product_showtitle']			= ($_REQUEST['shopbrand_product_showtitle'])?1:0;
				$insert_array['shopbrand_product_showshortdescription']	= ($_REQUEST['shopbrand_product_showshortdescription'])?1:0;
				$insert_array['shopbrand_product_showprice']			= ($_REQUEST['shopbrand_product_showprice'])?1:0;
				$insert_array['shopbrand_product_showrating']			= ($_REQUEST['shopbrand_product_showrating'])?1:0;
				$insert_array['shopbrand_product_showbonuspoints']		= ($_REQUEST['shopbrand_product_showbonuspoints'])?1:0;
				if (count($_REQUEST['group_id'])!=1 && count($_REQUEST['group_id'])!=0){
				$insert_array['shopbrand_default_shopbrandgroup_id']	= 0;//$_REQUEST['default_shopgroup_id'];
				}
				else
				{
				 $insert_array['shopbrand_default_shopbrandgroup_id']	= 0;//$_REQUEST['group_id'][0];
				}
				$db->insert_from_array($insert_array,'product_shopbybrand');
				$insert_id = $db->insert_id();
				
				// Section to make entry to product_shopbybrand_group_shop_map
				if(count($_REQUEST['group_id']))
				{
					for($i=0;$i<count($_REQUEST['group_id']);$i++)
					{
						$insert_array											= array();
						$insert_array['product_shopbybrand_shopbrandgroup_id']	= $_REQUEST['group_id'][$i];
						$insert_array['product_shopbybrand_shopbrand_id']		= $insert_id;
						$insert_array['shop_order']								= 0;
						$db->insert_from_array($insert_array,'product_shopbybrand_group_shop_map');
					}
				}
				// Deleting Cache
				delete_shop_cache($insert_id);
				$alert .= '<br><span class="redtext"><b>Product Shop added successfully</b></span><br>';
				//echo $alert;
			?>
			<!-- Redirecting to product assign tab after adding shop starts here -->
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?=$insert_id?>&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&curtab=products_tab_td';
			</script>
				<!--<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?=$insert_id?>&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=add&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>-->
				<!-- Redirecting to product assign tab after adding shop ends here -->
			<?
				
			}
			else
			{
				//include_once("classes/fckeditor.php");
				include ('includes/shopbybrand/add_shopbybrand.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
	    include_once("functions/console_urls.php");
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
	 	include ('includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		$edit_id = $_REQUEST['checkbox'][0];
		//include_once("classes/fckeditor.php");
		include ('includes/shopbybrand/edit_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose']=='save_edit')
	{
		if ($_REQUEST['shopbrand_Submit'])
		{
			$alert='';
			$fieldRequired = array($_REQUEST['shopbrand_name']);
			$fieldDescription = array('Product Shop Name');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
			if ($alert=='')
			{
				// Check whether the product shop name already exists
				$sql_exists 	= "SELECT count(shopbrand_id) FROM product_shopbybrand WHERE 
									shopbrand_name='".trim(add_slash($_REQUEST['shopbrand_name']))."' AND sites_site_id=$ecom_siteid 
									AND shopbrand_id NOT IN (".$_REQUEST['checkbox'][0].") AND shopbrand_parent_id=".$_REQUEST['shopbrand_parent_id']." LIMIT 1";
				$ret_exists 	= $db->query($sql_exists);
				list($ext_cnt)	= $db->fetch_array($ret_exists);
				if ($ext_cnt>0)
					$alert = "Sorry! Product Shop name already exists";
			}
			/*if ($alert=='')
			{
				if($_REQUEST['shopbrand_product_showimage']=='' &&  $_REQUEST['shopbrand_product_showtitle']=='' &&   
				   $_REQUEST['shopbrand_product_showshortdescription']=='' &&  $_REQUEST['shopbrand_product_showprice']=='' )  {
				   		$alert = "Sorry! Please Check any of Product Items to Display ";	
				   } 
				
			}	*/
			if (!$alert)
			{
				if($_REQUEST['shopbrand_parent_id'])
				{
					if($_REQUEST['shopbrand_parent_id']==$_REQUEST['checkbox'][0])
						$alert = 'Sorry!! Parent Shop should not be the current shop itself';
					else
					{
						$subshops = generate_subshop_tree($_REQUEST['checkbox'][0]);
						if(is_array($subshops))
						{
							if (array_key_exists($_REQUEST['shopbrand_parent_id'],$subshops))
								$alert = 'Sorry!! Parent Shop should not be any of the subshops of current shop';
						}
					}		
				
				}
			}
			if ($alert=='')
			{
				
				$update_array											= array();
				$update_array['shopbrand_parent_id']					= add_slash(trim($_REQUEST['shopbrand_parent_id']));
				$update_array['shopbrand_name']							= add_slash(trim($_REQUEST['shopbrand_name']));
				$update_array['shopbrand_description']					= add_slash(trim($_REQUEST['shopbrand_description']),false);
				$update_array['shopbrand_bottomdescription']			= add_slash(trim($_REQUEST['shopbrand_bottomdescription']),false);
				$update_array['shopbrand_hide']							= ($_REQUEST['shopbrand_hide'])?1:0;
				$update_array['shopbrand_default_shopbrandgroup_id']	= 0;
				$edit_id 												= $_REQUEST['checkbox'][0];
				$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$edit_id));
				
				// Section to make entry to product_shopbybrand_group_shop_map
				if(count($_REQUEST['group_id']))
				{
					$ext_arr = array();
					for($i=0;$i<count($_REQUEST['group_id']);$i++)
					{
						// Check whether this category is already mapped with this category group
						$sql_check = " SELECT id FROM product_shopbybrand_group_shop_map WHERE 
										product_shopbybrand_shopbrand_id =$edit_id AND product_shopbybrand_shopbrandgroup_id=".$_REQUEST['group_id'][$i]." LIMIT 1";
						$ret_check = $db->query($sql_check);
						list($cnt) = $db->fetch_array($ret_check);
						
						if($cnt==0)
						{
							$insert_array											= array();
							$insert_array['product_shopbybrand_shopbrandgroup_id']	= $_REQUEST['group_id'][$i];
							$insert_array['product_shopbybrand_shopbrand_id']		= $_REQUEST['checkbox'][0];
							$insert_array['shop_order']								= 0;
							$db->insert_from_array($insert_array,'product_shopbybrand_group_shop_map');
							$ext_arr[] = $_REQUEST['group_id'][$i];
						}	
						else
							$ext_arr[] = $_REQUEST['group_id'][$i];
					}
					$grp_str = implode(",",$ext_arr);
					
					$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id = ".$_REQUEST['checkbox'][0]." AND 
								product_shopbybrand_shopbrandgroup_id NOT IN ($grp_str)";
					$db->query($sql_del);
					
				}
				else
				{
					$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id = ".$_REQUEST['checkbox'][0];
					$db->query($sql_del);
				}
				// Deleting Cache
			
				delete_shop_cache($_REQUEST['checkbox'][0]);
				$alert .= '<br><span class="redtext"><b>Product Shop Updated successfully</b></span><br>';
				/* Redirecting to product assign tab after adding shop starts here */
				if($_REQUEST['shopbrand_Submit'] == 'Save & Return')
				{
				?>
				<script language="javascript">
				show_processing();
				window.parent.location = 'home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>';
				</script>
				<?
				}
				else
				{
					echo $alert;
			?>
				<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?=$_REQUEST['checkbox'][0]?>&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=add&shopname=<?=$_REQUEST['shopname']?>&show_shopgroup=<? echo $_REQUEST['show_shopgroup']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>
			<?	}
				/* Redirecting to product assign tab after adding shop ends here */
			}
			else
			{
				$ajax_return_function = 'ajax_return_contents';
				include "ajax/ajax.php";
				include ('includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');

				$edit_id = $_REQUEST['checkbox'][0];
				//include_once("classes/fckeditor.php");
				include ('includes/shopbybrand/edit_shopbybrand.php');
			}
		}
	}
	elseif($_REQUEST['fpurpose']=='list_shop_maininfo')//displaying the main information.
	{
		//print_r($_REQUEST);
		/*include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		//include_once("../classes/fckeditor.php");
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_shopmaininfo($_REQUEST['cur_shopid']);
		*/
		include_once("functions/console_urls.php");
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		$edit_id = $_REQUEST['checkbox'][0];
		include ('includes/shopbybrand/edit_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose']=='list_shop_settings')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_shop_settings($_REQUEST['cur_shopid']);
	}
	elseif($_REQUEST['fpurpose']=='list_shop_products')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_shop_products($_REQUEST['cur_shopid']);
	}
	elseif($_REQUEST['fpurpose']=='list_shopimg')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_shopimage_list($_REQUEST['cur_shopid']);
	}
	elseif($_REQUEST['fpurpose'] =='list_subshops')// Case of listing subshop
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_subshop_list($_REQUEST['cur_shopid']);
	}
	elseif($_REQUEST['fpurpose']=='add_shopimg') // show image gallery to select the required images
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/image_gallery/ajax/image_gallery_ajax_functions.php";
		include("includes/image_gallery/list_images.php");
	}
	elseif($_REQUEST['fpurpose']=='save_edit_settings') // case of coming to save the display settings for category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		$alert='';
		if ($alert=='')
		{
			if($_REQUEST['shopbrand_product_showimage']=='' &&  $_REQUEST['shopbrand_product_showtitle']=='' &&   
			   $_REQUEST['shopbrand_product_showshortdescription']=='' &&  $_REQUEST['shopbrand_product_showprice']=='' )  {
					$alert = "Sorry! Please Check any of Product Items to Display ";	
			   } 
			
		}
		if ($alert=='')
		{ 
			$update_array											= array();
			$update_array['sites_site_id']							= $ecom_siteid;
			$update_array['shopbrand_product_displaytype']			= add_slash(trim($_REQUEST['shopbrand_product_displaytype']));
			$update_array['shopbrand_subshoplisttype']				= add_slash(trim($_REQUEST['shopbrand_subshoplisttype']));
			$update_array['shopbrand_product_showimage']			= ($_REQUEST['shopbrand_product_showimage'])?1:0;
			$update_array['shopbrand_product_showtitle']			= ($_REQUEST['shopbrand_product_showtitle'])?1:0;
			$update_array['shopbrand_product_showshortdescription']	= ($_REQUEST['shopbrand_product_showshortdescription'])?1:0;
			$update_array['shopbrand_product_showprice']			= ($_REQUEST['shopbrand_product_showprice'])?1:0;
			$update_array['shopbrand_product_showrating']			= ($_REQUEST['shopbrand_product_showrating'])?1:0;
			$update_array['shopbrand_product_showbonuspoints']		= ($_REQUEST['shopbrand_product_showbonuspoints'])?1:0;				
			$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$_REQUEST['edit_id']));
			// Deleting Cache
			delete_shop_cache($_REQUEST['checkbox'][0]);
			// Completed the section to entry details to product_categorygroup_category
			$alert = 'Display Settings Updated Successfully';
		}
		show_shop_settings($_REQUEST['edit_id'],$alert);	
	}
	else if($_REQUEST['fpurpose']=='subshopAssign') // Case of selecting categgories to groups
	{
		include ('includes/shopbybrand/list_shopbybrand_ass_selshop.php');
	}
	else if($_REQUEST['fpurpose']=='save_subshopAssign') // Case of selecting products to groups
	{
    //echo "parent_id".$_REQUEST['pass_sub_id'];
		
		foreach($_REQUEST['checkbox'] as $v)
		{
			$update_array=array();
			
			$update_array['shopbrand_parent_id']	= $_REQUEST['pass_sub_id'];
			$sub_id 						= $v;	
			$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$sub_id));
			
		}
		$alert='Shops Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['pass_shopname']?>&show_shopgroup=<? echo $_REQUEST['pass_show_shopgroup']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Shop Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_sub_id']?>&shopname=<?=$_REQUEST['pass_shopname']?>&show_shopgroup=<? echo $_REQUEST['pass_show_shopgroup']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td" onclick="show_processing()">Go Back to the Edit  this Shop</a><br /><br />
	<?	
	}
	elseif($_REQUEST['fpurpose'] == 'delete')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Product Shop not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_cnt = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether subshops exists for current shop
					$sql_check = "SELECT count(*) FROM product_shopbybrand WHERE shopbrand_parent_id=".$del_arr[$i];
					$ret_check = $db->query($sql_check);
					list($cnt) = $db->fetch_array($ret_check);
					if($cnt==0)
					{
						
						$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_shopbybrand_product_map WHERE product_shopbybrand_shopbrand_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM images_shopbybrand WHERE product_shopbybrand_shopbrand_id=".$del_arr[$i];
						$db->query($sql_del);
						
						$sql_del = "DELETE FROM product_shopbybrand WHERE shopbrand_id=".$del_arr[$i];
						$db->query($sql_del);
						$del_cnt++;
						// Deleting Cache
						delete_shop_cache($del_arr[$i]);
					}
					else
					{
						if($alert) $alert .="<br />";
						$alert .= "Sorry!! Product Shop exists under current product shop id-".$del_arr[$i];
					}	
				}	
			}
			if($del_cnt>0)
			{
			if($alert) $alert .="<br />";
						$alert .= $del_cnt." Product Shop(s) Deleted Successfully";
			}			
		}	
		include ('../includes/shopbybrand/list_shopbybrand.php');
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
			$update_array['shopbrand_hide']	= $new_status;
			$cur_id 						= $shopid_arr[$i];	
			$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$cur_id));
			// Deleting Cache
			delete_shop_cache($cur_id);
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/shopbybrand/list_shopbybrand.php');
		
	}
	elseif($_REQUEST['fpurpose']=='save_shopimagedetails') // ajax to save image details 
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
	/*	if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{ */
		$shimg      = ($_REQUEST['shimg']=='1')?'1':'0';
		$turnoff_mainimage		= ($_REQUEST['shopbrand_turnoff_mainimage']==1)?1:0;
		$turnoff_moreimages		= ($_REQUEST['shopbrand_turnoff_moreimages']==1)?1:0;
		$sql = "UPDATE product_shopbybrand 
								SET 
									shopbrand_showimageofproduct='".$shimg."',
									shopbrand_turnoff_mainimage='".$turnoff_mainimage."',
		 							shopbrand_turnoff_moreimages='".$turnoff_moreimages."'
		 							WHERE shopbrand_id='".$_REQUEST['edit_id']."'";
		$res = $db->query($sql);
		$alert = ' Saved Successfully ';
		if ($_REQUEST['ch_ids'] != '')
		{
			$ch_arr 	= explode("~",$_REQUEST['ch_ids']);
			$ch_order	= explode("~",$_REQUEST['ch_order']);
			$ch_title	= explode("~",$_REQUEST['ch_title']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$chroder = (!is_numeric($ch_order[$i]))?0:$ch_order[$i];
				$sql_change = "UPDATE images_shopbybrand SET image_order = ".$chroder.",image_title='".add_slash($ch_title[$i])."' WHERE id=".$ch_arr[$i];
				$db->query($sql_change);
			}
			$alert = 'Image details Saved Successfully';
		}	
		// }	
		// Calling function to clear category groups of current category
		delete_category_cache($_REQUEST['edit_id']);
		show_shopimage_list($_REQUEST['edit_id'],$alert);
	}
	
	elseif($_REQUEST['fpurpose']=='unassign_shopimagedetails')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Image(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sql_del = "DELETE FROM images_shopbybrand WHERE id=".$ch_arr[$i];
				
				$db->query($sql_del);
			}
			$alert = 'Image(s) Unassigned Successfully';
		}	
		
		delete_category_cache($_REQUEST['shop_id']);
		show_shopimage_list($_REQUEST['shop_id'],$alert);
	}
	
	else if($_REQUEST['fpurpose']=='unassignsubshop') // Case of unassigning sub shops
	{
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "UPDATE product_shopbybrand SET shopbrand_parent_id =0 WHERE shopbrand_id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Sub shops unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_subshop_list($shopid,$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_shoporder')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 	= explode('~',$_REQUEST['ch_ids']);
		$order_arr		= explode('~',$_REQUEST['ch_order']);
		$shop_id		= $_REQUEST['shop_id'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$update_array							= array();
			$update_array['shopbrand_order']		= $order_arr[$i];
			$cur_id 								= $shopid_arr[$i];	
			$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$cur_id));
		}
		$alert = 'Sort Order Saved Successfully.';
		delete_shop_cache($shop_id);
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_subshop_list($shop_id,$alert);
	}
	elseif($_REQUEST['fpurpose']=='save_shopproductorder')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 	= explode('~',$_REQUEST['ch_ids']);
		$order_arr		= explode('~',$_REQUEST['ch_order']);
		$shop_id		= $_REQUEST['shop_id'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$update_array							= array();
			$update_array['map_sortorder']	= $order_arr[$i];
			$cur_id 								= $shopid_arr[$i];	
			$db->update_from_array($update_array,'product_shopbybrand_product_map',array('map_id'=>$cur_id));
		}
		// Deleting Cache
		delete_shop_cache($shop_id);
		$alert = 'Sort Order Saved Successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_shop_products($_REQUEST['shop_id'],$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_displaycategorygroup')// Case of listing diaplay categories to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_diplaycategory_group_list($_REQUEST['shop_id']);
	}
	elseif($_REQUEST['fpurpose'] =='list_displayproductgroup')// Case of listing diaplay products to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_diplayproduct_group_list($_REQUEST['shop_id']);
	}
	elseif($_REQUEST['fpurpose'] =='list_categorygroup')// Case of listing categgories to groups
	{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
		show_category_group_list($group_id,$alert);
	}
	else if($_REQUEST['fpurpose']=='categoryGroupAssign') // Case of selecting categgories to groups
	{
		
		include ('includes/shopbybrand/list_category_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose'] =='list_displaystaticgroup')// Case of listing diaplay pages to groups
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_diplaystatic_group_list($_REQUEST['shop_id']);
	}
    else if($_REQUEST['fpurpose']=='staticGroupAssign') // Case of selecting pages to groups
	{
		
		include ('includes/shopbybrand/list_static_shopbybrand.php');
	}
/*	else if($_REQUEST['fpurpose']=='save_staticGroupAssign') // Case of saving pages to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check the page is already assigned
			$sql_check = "SELECT id FROM product_shopbybrand_display_staticpages WHERE product_shopbybrand_shopbrand_id=".
							$_REQUEST['pass_shop_id']." AND static_pages_page_id=$v LIMIT 1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)
			{
				$insert_array=array();
				$insert_array['sites_site_id']						= $ecom_siteid;
				$insert_array['product_shopbybrand_shopbrand_id']	= $_REQUEST['pass_shop_id'];
				$insert_array['static_pages_page_id']				= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_display_staticpages');
			}			
		}
		$alert='Page Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Produch Shops Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shop_id']?>&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=shop_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop</a><br /><br />
	<?	
	}*/
	else if($_REQUEST['fpurpose']=='prodGroupAssign') // Case of selecting products to groups
	{
		include ('includes/shopbybrand/list_product_shopbybrand_selproduct.php');
	}
	/*else if($_REQUEST['fpurpose']=='save_categoryGroupAssign') // Case of selecting products to groups
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the category is already assigned 
			$sql_check = "SELECT id FROM product_shopbybrand_display_category WHERE product_shopbybrand_shopbrand_id=".
			$_REQUEST['pass_shop_id']." AND product_categories_category_id=$v LIMIT 1";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array										= array();
				$insert_array['sites_site_id']						= $ecom_siteid;
				$insert_array['product_shopbybrand_shopbrand_id']	= $_REQUEST['pass_shop_id'];
				$insert_array['product_categories_category_id']		= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_display_category');
			}	
		}
		$alert='Category Assigned Successfullly';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shop_id']?>&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Edit  this Product Shop</a><br /><br />
	<?	
	}*/
	/*else if($_REQUEST['fpurpose']=='save_prodGroupAssign') // Case of saving products to categories
	{

		
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the product is already assigned
			$sql_check = "SELECT id FROM product_shopbybrand_display_products WHERE sites_site_id=$ecom_siteid AND 
							product_shopbybrand_shopbrand_id=".$_REQUEST['pass_shop_id']." AND 
							products_product_id=$v";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array										= array();
				$insert_array['sites_site_id']						= $ecom_siteid;
				$insert_array['product_shopbybrand_shopbrand_id']	= $_REQUEST['pass_shop_id'];
				$insert_array['products_product_id']				= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_display_products');
			}			
		}
		$alert='Product Assigned Successfullly';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shop_id']?>&shopname=<?=$_REQUEST['pass_shopname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=products_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop</a><br /><br />
	<?	
	}*/
	else if($_REQUEST['fpurpose']=='staticGroupUnAssign') // Case of unassigning categories to groups
	{
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_display_staticpages WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Static Page unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
	    show_diplaystatic_group_list($shopid,$alert);
	}
	else if($_REQUEST['fpurpose']=='categoryGroupUnAssign') // Case of unassigning categories from product shop
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_display_category WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Category unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
	    show_diplaycategory_group_list($shopid,$alert);
	}
	else if($_REQUEST['fpurpose']=='prodGroupUnAssign') // Case of unassigning products to categories
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_display_products WHERE id=$id";
			$db->query($sql_del);
			
		}
		$alert = 'Product unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
	    show_diplayproduct_group_list($shopid,$alert);
	}
	/*elseif($fpurpose =='assign_sel_save')
	{
		$selcat_arr = explode("~",$_REQUEST['checkbox']);
		if (count($_REQUEST['checkbox']))
		{
			foreach($_REQUEST['checkbox'] as $v)
			{
				$insert_array					= array();
				$insert_array['catgroup_id']	= $_REQUEST['pass_groupid'];
				$insert_array['category_id']	= $v;
				$insert_array['category_order']	= 0;
				
				$db->insert_from_array($insert_array,'product_categorygroup_category');
			}				
		}
		$alert = 'Product Categories Assigned successfully';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;	
		?>
		<br /><a class="smalllink" href="home.php?request=prod_cat_group&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Group Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=prod_cat_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_groupid']?>&catgroupname=<?=$_REQUEST['pass_catgroupname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Edit  this Group</a><br /><br />
	<?php		
	}*/
	elseif($_REQUEST['fpurpose'] =='unassigncat')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['del_ids']);
		$groupid		= $_REQUEST['group_id'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id = $catid_arr[$i];	
			$sql_del = "DELETE FROM product_categorygroup_category WHERE category_id=$cur_id AND catgroup_id=$groupid";
			$db->query($sql_del);
			
		}
		$alert = 'Category unassigned successfully.';
		include ('../includes/product_category_groups/ajax/product_category_group_ajax_functions.php');
	    show_category_group_list($groupid,$alert);
	}
	elseif($_REQUEST['fpurpose'] =='list_subshopproduct')// Case of listing products in current shop
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_diplayshopproduct_group_list($_REQUEST['shop_id']);
	}
	elseif ($_REQUEST['fpurpose']=='assign_selshopprod')
	{
		include ('includes/shopbybrand/list_product_shopbybrand_shopselproduct.php');
	}
	elseif ($_REQUEST['fpurpose'] == 'save_prodShopAssign')
	{
		foreach($_REQUEST['checkbox'] as $v)
		{
			// Check whether the product is already assigned
			$sql_check = "SELECT map_id FROM product_shopbybrand_product_map WHERE 
							product_shopbybrand_shopbrand_id=".$_REQUEST['pass_shop_id']." AND 
							products_product_id=$v";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check)==0)	
			{		
				$insert_array										= array();
				$insert_array['product_shopbybrand_shopbrand_id']	= $_REQUEST['pass_shop_id'];
				$insert_array['products_product_id']				= $v;
				$db->insert_from_array($insert_array, 'product_shopbybrand_product_map');
			}			
		}
		$alert='Product Assigned to Shop Successfullly';
				
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;				
		?>
		<br /><a class="smalllink" href="home.php?request=shopbybrand&shopname=<?=$_REQUEST['pass_shopname']?>&show_shopgroup=<? echo $_REQUEST['pass_show_shopgroup']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Product Shop Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_shop_id']?>&shopname=<?=$_REQUEST['pass_shopname']?>&show_shopgroup=<? echo $_REQUEST['pass_show_shopgroup']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>&curtab=products_tab_td" onclick="show_processing()">Go Back to the Edit this Product Shop</a><br /><br />
<?php	
	}
	else if($_REQUEST['fpurpose']=='unassignshopproduct') // Case of unassigning products
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$id_arr 	= explode('~',$_REQUEST['del_ids']);
		$shopid		= $_REQUEST['shop_id'];
		for($i=0;$i<count($id_arr);$i++)
		{
			$id = $id_arr[$i];	
			$sql_del = "DELETE FROM product_shopbybrand_product_map WHERE map_id=$id";
			$db->query($sql_del);
			
		}
		// Deleting Cache
		delete_shop_cache($shopid);
		$alert = 'Product unassigned successfully.';
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
	  	show_shop_products($_REQUEST['shop_id'],$alert);
	}
	elseif($_REQUEST['fpurpose']=='settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		include ('includes/shopbybrand/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose']=='save_settingstomany'){
		$update_array	=	array();
		if($_REQUEST['subshoplist_check'])
		{
			$update_array['shopbrand_subshoplisttype']		= add_slash($_REQUEST['shopbrand_subshoplisttype']);
		}
		if($_REQUEST['prod_disp_method'])
		{
			$update_array['shopbrand_product_displaytype']			= add_slash($_REQUEST['product_displaytype']);
		
		}
		if($_REQUEST['field_disp_prod'])
		{
		       	$update_array['shopbrand_product_showimage']			= ($_REQUEST['product_showimage']==1)?1:0;
				$update_array['shopbrand_product_showtitle']			= ($_REQUEST['product_showtitle']==1)?1:0;
				$update_array['shopbrand_product_showshortdescription']	= ($_REQUEST['product_showshortdescription']==1)?1:0;
				$update_array['shopbrand_product_showprice']			= ($_REQUEST['product_showprice']==1)?1:0;
				$update_array['shopbrand_product_showrating']			= ($_REQUEST['product_showrating']==1)?1:0;
				$update_array['shopbrand_product_showbonuspoints']		= ($_REQUEST['product_showbonuspoints']==1)?1:0;
		}	
		if($_REQUEST['field_disp_moreimg'])
		{
			$update_array['shopbrand_turnoff_moreimages']			=($_REQUEST['shopbrand_turnoff_moreimages']==1)?1:0;
		}
		if($_REQUEST['field_disp_mainimg'])
		{
			$update_array['shopbrand_turnoff_mainimage']			= ($_REQUEST['shopbrand_turnoff_mainimage']==1)?1:0;
		}
		if($_REQUEST['field_disp_anyprodimg'])
		{
			if ($_REQUEST['shopbrand_showimageofproduct']==1)
			{
				if(!$_REQUEST['chk_ignore_assigned_img_shop'])
					$update_array['shopbrand_showimageofproduct']			=($_REQUEST['shopbrand_showimageofproduct']==1)?1:0;
			}
			else
			{
				$update_array['shopbrand_showimageofproduct']			=($_REQUEST['shopbrand_showimageofproduct']==1)?1:0;
			}		
		}		
		if($_REQUEST['select_shops']=='All') // case if apply to all shops selected
		{ // set the values to all the shops
			if(count($update_array))
			$db->update_from_array($update_array,'product_shopbybrand',array('sites_site_id'=>$ecom_siteid));
			if($_REQUEST['field_disp_anyprodimg'])
			{
			 if ($_REQUEST['shopbrand_showimageofproduct']==1 and $_REQUEST['chk_ignore_assigned_img_shop']==1)
				{
				   // Get all the categories in current site
					$sql_shops = "SELECT shopbrand_id 
									FROM 
										product_shopbybrand 
									WHERE 
										sites_site_id = $ecom_siteid ";
					$ret_shops = $db->query($sql_shops);
					if($db->num_rows($ret_shops))
					{
						while ($row_shops = $db->fetch_array($ret_shops))
						{
							$cur_id = $row_shops['shopbrand_id'];
							// Check whether any image assigned for current category
							$sql_check = "SELECT id 
											FROM 
												images_shopbybrand 
											WHERE 
												product_shopbybrand_shopbrand_id  = $cur_id 
											LIMIT 
												1";
							$ret_check = $db->query($sql_check);
							if($db->num_rows($ret_check)==0)
							{
								$update_array = array();					
								$update_array['shopbrand_showimageofproduct']			=($_REQUEST['shopbrand_showimageofproduct']==1)?1:0;
								$db->update_from_array($update_array,'product_shopbybrand',array('sites_site_id'=>$ecom_siteid,'shopbrand_id'=>$cur_id));
							}	
						}
					}
				}
			}
			$alert= "Shops Updated Successfully !!";
		}
		elseif ($_REQUEST['select_shops']=='Byshop') // case if apply to selected shops selected
		{
			if($_REQUEST['settings_shopid'] == 0)
			{
				$alert = "Error: Select a shop";
			}
			elseif($_REQUEST['settings_shopid'])
			{
				if($_REQUEST['settings_shopid'][0]!=0)
				{
					// update for selected products
					foreach($_REQUEST['settings_shopid'] as $key=>$val)
					{
						if(count($update_array))
						$db->update_from_array($update_array,'product_shopbybrand',array('sites_site_id'=>$ecom_siteid,'shopbrand_id'=>$val));
					}
					$alert= "Shop(s) Updated Successfully !!";
				}
			}
		}
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/products/ajax/product_ajax_functions.php');
		include ('includes/shopbybrand/apply_settingstomany.php');
	}
	elseif($_REQUEST['fpurpose'] =='shopgroup_assign')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 		= explode('~',$_REQUEST['shopids']);
		$assign_group	= $_REQUEST['ch_shopgroup'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$cur_id 						= $shopid_arr[$i];	
			// Check whether the current category is already assigned to the selected category group
			$sql_check = "SELECT count(product_shopbybrand_shopbrandgroup_id) FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id=$cur_id 
								AND product_shopbybrand_shopbrandgroup_id=$assign_group";
			$ret_check = $db->query($sql_check);
			list($cnts) = $db->fetch_array($ret_check);
			if ($cnts>0)
			{
				if($alert) $alert .="<br />";
				$alert .= "Sorry!! Shop with ID -".$cur_id.' already assigned to the selected Shop Menu';
			}
			else
			{
				$insert_array					= array();
				$insert_array['product_shopbybrand_shopbrandgroup_id']	= $assign_group;
				$insert_array['product_shopbybrand_shopbrand_id']	= $cur_id;
				$insert_array['shop_order']	= 0;
				$db->insert_from_array($insert_array,'product_shopbybrand_group_shop_map');
				if($alert) $alert .="<br />";
				$alert .= "Shop with ID -".$cur_id.' has been assigned to the selected Shop Menu';
				
				// Calling function to clear category groups of current category
			delete_compshopgroup_cache($cur_id);
			}	
		}
		include ('../includes/shopbybrand/list_shopbybrand.php');
	}
	elseif ($_REQUEST['fpurpose'] =='shopgroup_unassign')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$catid_arr 		= explode('~',$_REQUEST['shopids']);
		$assign_group	= $_REQUEST['ch_shopgroup'];
		for($i=0;$i<count($catid_arr);$i++)
		{
			$cur_id 	= $catid_arr[$i];	
			// Check whether the current category is already assigned to the selected category group
			$sql_check = "SELECT count(product_shopbybrand_shopbrandgroup_id) FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id=$cur_id 
								AND product_shopbybrand_shopbrandgroup_id=$assign_group";
			$ret_check 	= $db->query($sql_check);
			list($cnt_d)= $db->fetch_array($ret_check);
			if($cnt_d>0)
			{
				// Check whether the current category is assigned to more than one category group
					$sql_del = "DELETE FROM product_shopbybrand_group_shop_map WHERE product_shopbybrand_shopbrand_id=$cur_id 
									AND product_shopbybrand_shopbrandgroup_id=$assign_group";
					$db->query($sql_del);
					if($alert) $alert .="<br />";
					$alert .= "Shop with ID -".$cur_id.' has been unassigned from the selected Shop Menu';
			}	
			else
			{
				if($alert) $alert .="<br />";
					$alert .= "Sorry!! Shop with ID -".$cur_id.' is not assigned to the selected Shop Menu';
			}
			// Calling function to clear category groups of current category
			delete_compshopgroup_cache($cur_id);
		}
		
		include ('../includes/shopbybrand/list_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose'] =='change_parent')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 		= explode('~',$_REQUEST['shopids']);
		$new_parent		= $_REQUEST['ch_parent'];
		for($i=0;$i<count($shopid_arr);$i++)
		{   $flag =1;
			// Find the previous parent 
			$sql_par = "SELECT shopbrand_parent_id,shopbrand_name FROM product_shopbybrand WHERE shopbrand_id=".$shopid_arr[$i];
			$ret_par = $db->query($sql_par);
			if ($db->num_rows($ret_par))
			{
				$row_par = $db->fetch_array($ret_par);
				$shop_name = $row_par['shopbrand_name'];
				if ($row_par['shopbrand_parent_id']!=0)
				{
					// Calling function to clear category groups of previous parent 
					delete_compshopgroup_cache($row_par['shopbrand_parent_id']);
				}
			}
			//
			
			if ($_REQUEST['ch_parent']==$shopid_arr[$i])
					{
						$flag =0;
						$alert .= "Parent shop cannot be the current shop itself for '".$shop_name."' <br>";
					}
					else
					{ 
						$subshops = generate_subshop_tree($shopid_arr[$i]);
						if(!is_array($subshops))
							$subshops[0] = $shopid_arr[0];
						 if($_REQUEST['ch_parent']!=0){
						if (array_key_exists($_REQUEST['ch_parent'],$subshops)){
							$flag =0;
							$alert .= "Parent should not be a shop below the current shop for '".$shop_name."' <br>";
							}
						}
					}	
			//
			if($flag){
					$update_array								= array();
					$update_array['shopbrand_parent_id']		= $new_parent;
					$cur_id 									= $shopid_arr[$i];	
					$db->update_from_array($update_array,'product_shopbybrand',array('shopbrand_id'=>$cur_id));
					// Calling function to clear category groups of new parent category
					delete_compshopgroup_cache($new_parent);
					$alert .= "Parent of Selected Shop 
				   changed successfully for '".$shop_name."' <br>";
		}
		}
				
		
		include ('../includes/shopbybrand/list_shopbybrand.php');
	}
	elseif($_REQUEST['fpurpose'] =='list_seo')// Case of listing shops to groups
	{	
		$shop_id = $_REQUEST['cur_shopid'];	

		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php');
		show_page_seoinfo($shop_id,$alert);
	}
	elseif($_REQUEST['fpurpose'] =='save_seo')// Case of listing shops to groups
	{ 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ("../includes/shopbybrand/ajax/shopbybrand_ajax_functions.php");
		$shop_id = $_REQUEST['cur_shopid'];	
		$unq_id = uniqid("");
	
		 $sql_check = "SELECT id FROM se_shop_title WHERE sites_site_id=$ecom_siteid AND product_shopbybrand_shopbrand_id = ".$shop_id;
		 $sql_keys  = "SELECT se_keywords_keyword_id FROM se_shop_keywords WHERE product_shopbybrand_shopbrand_id = ".$shop_id;
		$tb_name = 'se_shop_title';
	//echo $sql_check;die();
	
	$res_check = $db->query($sql_check);
	$row_check = $db->fetch_array($res_check);
		

	$keys_list = array();
	$res_keys = $db->query($sql_keys);
	if($db->num_rows($res_keys)>0) 
	{ 
		while($row_keys = $db->fetch_array($res_keys))
		{
			$keys_list[] = $row_keys['se_keywords_keyword_id'];
		}
		foreach($keys_list as $keys => $values)
		{
			
				$sql_delkey_rel = "DELETE FROM se_shop_keywords WHERE se_keywords_keyword_id = ".$values." AND product_shopbybrand_shopbrand_id = ".$shop_id;
				//echo $sql_delkey_rel;echo "<br>";
				$db->query($sql_delkey_rel);					
			$sql_delkey = "DELETE FROM se_keywords WHERE keyword_id = ".$values." AND sites_site_id = ".$ecom_siteid;
			//echo $sql_delkey;echo "<br>";
			$db->query($sql_delkey);
		}
	}
	$ch_arr     = explode('~',$_REQUEST['ch_ids']);
	
	for($i=0;$i<count($ch_arr);$i++)
	{
		
			$insert_array = array();
			$insert_array['sites_site_id']		= $ecom_siteid;
			$insert_array['keyword_keyword']	= trim(add_slash($ch_arr[$i]));
			$db->insert_from_array($insert_array, 'se_keywords');
			$insert_id = $db->insert_id();
			
			if($insert_id > 0)
			{
				    $insert_array = array();
				
					$insert_array['product_shopbybrand_shopbrand_id']	= $shop_id;
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_shop_keywords');
							
			}
	}
	//echo "<pre>";print_r($keys_list);die();
	
	//echo $tb_name;echo "<br>";die();
	if($row_check['id'] != "" && $row_check['id'] > 0)
	{
		if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
		{
			
				$sql_del = "DELETE FROM se_shop_title WHERE id=".$row_check['id'];				
			
			$db->query($sql_del);
		}
		else
		{
			$update_array['title']					= trim(add_slash($_REQUEST['page_title']));
			$update_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			$db->update_from_array($update_array, $tb_name, 'id', $row_check['id']);
		}
					 $alert	=	"Updated Successfully.";

	}
	else
	{
		$alert				= '';		
		if($alert == "")
		{
			$insert_array = array();
			
				$insert_array['product_shopbybrand_shopbrand_id']	= $shop_id;
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['title']					= trim(add_slash($_REQUEST['page_title']));
				$insert_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			
			
			$db->insert_from_array($insert_array, $tb_name);
			$insert_id = $db->insert_id();
			
			if($insert_id == "" || $insert_id == 0)
			{
				$alert	=	"Inserting seo info failed.";
			}
			else
			{
			   $alert	=	"Updated Successfully.";
			}
		}
		
	}	
			show_page_seoinfo($shop_id,$alert);
}
?>
