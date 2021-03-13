<?php
	if($_REQUEST['fpurpose']=='')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/newsletter_templates/list_newsletter_templates.php');
	}
	elseif($_REQUEST['fpurpose']=='add')
	{
		include_once("classes/fckeditor.php");
		include ('includes/newsletter_templates/add_newsletter_template.php');
	}
	elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$shopid_arr 	= explode('~',$_REQUEST['catids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($shopid_arr);$i++)
		{
			$update_array					    = array();
			$update_array['newstemplate_hide']	= $new_status;
			$cur_id 						    = $shopid_arr[$i];	
			$db->update_from_array($update_array,'newsletter_template',array('newstemplate_id'=>$cur_id));
			// Deleting Cache
			//delete_shop_cache($cur_id);
		}
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/newsletter_templates/list_newsletter_templates.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
	if ($_REQUEST['newslettertemplate_Submit'])
		{
			$alert = '';
				//#Server side validation
		
				$fieldRequired = array($_REQUEST['newstemplate_name'],$_REQUEST['newstemplate_template']);
				$fieldDescription = array('Newsletter Template Name','Template Content');
				$fieldEmail = array();
				$fieldConfirm = array();
				$fieldConfirmDesc = array();
				$fieldNumeric = array();
				$fieldNumericDesc = array();
				serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				$sql_check = "SELECT count(*) as cnt FROM newsletter_template WHERE newstemplate_name='".addslashes($_REQUEST['newstemplate_name'])."' AND sites_site_id=".$ecom_siteid;
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
			
				if($row_check['cnt'] > 0) {
					$alert = 'Newsletter Template Name already exists for the Same site';
				}
				
				if(!$alert) {
					$insert_array = array();
					//##############################
					//Inserting into sites_shops table
					//##############################
					$insert_array['sites_site_id']					= $ecom_siteid;
					$insert_array['newstemplate_name']				= add_slash($_REQUEST['newstemplate_name']);
					$insert_array['newstemplate_template']			= add_slash($_REQUEST['newstemplate_template'],false);
					$insert_array['newstemplate_hide']				= ($_REQUEST['newstemplate_hide'])?1:0;
					$insert_array['product_layout']					= add_slash($_REQUEST['product_layout_temp'],false);
					$db->insert_from_array($insert_array, 'newsletter_template');
					$insert_templateid = $db->insert_id();//#Getting generated site id
				$alert = '<center><font color="red"><b>Successfully Added</b></font><br>';
					echo $alert;
					?>
					<br /><a class="smalllink" href="home.php?request=newsletter_templates&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
				  <a class="smalllink" href="home.php?request=newsletter_templates&fpurpose=edit&checkbox[0]=<?=$insert_templateid?>&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
				  <a class="smalllink" href="home.php?request=newsletter_templates&fpurpose=add&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>

					<?php
				} else {
					include_once("classes/fckeditor.php");
					include ('includes/newsletter_templates/add_newsletter_template.php');

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
			$alert = 'Sorry Newsletter Template not selected';
		}
		else
		{
			$del_count = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether subshops exists for current shop
						$sql_del = "DELETE FROM newsletter_template WHERE sites_site_id=$ecom_siteid AND newstemplate_id=".$del_arr[$i];
						$db->query($sql_del);
						$del_count++;
				}	
			}
			if($del_count>0)
			{
			if($alert) $alert .="<br />";
						$alert .= $del_count." Newsletter Template(s) Deleted"; //.$del_arr[$i]
			}			
		}
		include ('../includes/newsletter_templates/list_newsletter_templates.php');
	}
	elseif($_REQUEST['fpurpose']=='edit')
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$edit_id = $_REQUEST['checkbox'][0];
		include_once("classes/fckeditor.php");
		include ('includes/newsletter_templates/edit_newsletter_template.php');
	}
	elseif($_REQUEST['fpurpose']=='edit_store')
	{
	if($_REQUEST['updatenewslettertemplate_Submit']){
				$sql_check = "SELECT count(*) as cnt FROM newsletter_template WHERE newstemplate_name='".addslashes($_REQUEST['newstemplate_name'])."' AND newstemplate_id<>".$_REQUEST['edit_id']." AND sites_site_id=".$ecom_siteid;
				$res_check = $db->query($sql_check);
				$row_check = $db->fetch_array($res_check);
			
				if($row_check['cnt'] > 0) {
					$alert = 'Template name already exists for the Same site';
				}if(!$alert) {
					$update_array = array();
					//##############################
					//Updating into sites_shops table
					//##############################
					$update_array['newstemplate_name']						= addslashes($_REQUEST['newstemplate_name']);
					$update_array['newstemplate_template']					= add_slash($_REQUEST['newstemplate_template'],false);
					$update_array['newstemplate_hide']						= ($_REQUEST['newstemplate_hide'])?1:0;
					$update_array['product_layout']							= add_slash($_REQUEST['product_layout_temp'],false);
					
					$db->update_from_array($update_array, 'newsletter_template',array('newstemplate_id'=> $_REQUEST['edit_id'],'sites_site_id' => $ecom_siteid));
					$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
					echo $alert;
					?>
					<br /><a class="smalllink" href="home.php?request=newsletter_templates&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
						  <a class="smalllink" href="home.php?request=newsletter_templates&fpurpose=edit&checkbox[0]=<?=$_REQUEST['edit_id']?>&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
						  <a class="smalllink" href="home.php?request=newsletter_templates&fpurpose=add&search_newstemplate_name=<?=$_REQUEST['search_newstemplate_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add New Page</a>
		
							<?php
						} else {
							include_once("classes/fckeditor.php");
							include ('includes/newsletter_templates/edit_newsletter_template.php');
		
				     }
			}		 
		
	}
	
?>
