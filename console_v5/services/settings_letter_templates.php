<?php 
/*
	#################################################################
	# Script Name 	: settings_letter_templates.php
	# Description 	: Action Page for changing the details of the email templates
	# Coded by 		: ANU
	# Created on	: 21-Jun-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	include("includes/letter_templates/list_settings_letter_templates.php");
}elseif($_REQUEST['fpurpose'] == 'edit_letter_templates') {
	include("includes/letter_templates/edit_settings_letter_templates.php");
}
elseif($_REQUEST['fpurpose'] == 'update_letter_template') {
if($_REQUEST['Submit']){
			$alert='';
			$fieldRequired = array($_REQUEST['lettertemplate_from']);
			$fieldDescription = array('Letter Template from ID');
			$fieldEmail = array();
			$fieldConfirm = array();
			$fieldConfirmDesc = array();
			$fieldNumeric = array();
			$fieldNumericDesc = array();
			serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
				if(!$alert) {
					$update_array = array();
					$update_array['lettertemplate_from'] = add_slash($_REQUEST['lettertemplate_from']);
					$update_array['lettertemplate_subject'] = add_slash($_REQUEST['lettertemplate_subject']);
					$update_array['lettertemplate_contents'] = add_slash($_REQUEST['letter_content'],false);
					
					$db->update_from_array($update_array, 'general_settings_site_letter_templates','lettertemplate_id', $_REQUEST['lettertemplate_id']);
					
					$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
				     echo $alert;
				?>
					<br /><a class="smalllink"  href="home.php?request=settings_letter_templates&lettertemplate_letter_type=<?=$_REQUEST['lettertemplate_letter_type']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&lettertemplate_title=<?=$_REQUEST['lettertemplate_title']?>">Go Back to Email Template Listing page</a><br /><br />
					<a class="smalllink" href="home.php?request=settings_letter_templates&fpurpose=edit_letter_templates&lettertemplate_letter_type=<?=$_REQUEST['lettertemplate_letter_type']?>&settings_section=<?=$_REQUEST['settings_section']?>&lettertemplate_id=<?=$_REQUEST['lettertemplate_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&lettertemplate_title=<?=$_REQUEST['lettertemplate_title']?>">Go Back to Edit this Email Template</a>
					<br /><br />
					
					
					</center>
				
				<?
				}else{
				
				include("includes/letter_templates/edit_settings_letter_templates.php");
				}
			}
}
elseif($_REQUEST['fpurpose']=='save_email_disabled')
{
	if(!is_array($_REQUEST['letter_disabled']))
		$_REQUEST['letter_disabled'][0] = -1;
	for ($i=0;$i<count($_REQUEST['lettertemplate_id']);$i++)
	{
		if($_REQUEST['lettertemplate_id'][$i])
		{
			
			if(in_array($_REQUEST['lettertemplate_id'][$i],$_REQUEST['letter_disabled']))
			{
				// Setting the disabled items
				$sql_disable = "UPDATE general_settings_site_letter_templates SET lettertemplate_disabled = 1 WHERE 
								lettertemplate_id =".$_REQUEST['lettertemplate_id'][$i]." LIMIT 1";
				$db->query($sql_disable);
			}
			else
			{
				// Setting the non disabled items
				$sql_disable = "UPDATE general_settings_site_letter_templates SET lettertemplate_disabled = 0 WHERE 
								lettertemplate_id =".$_REQUEST['lettertemplate_id'][$i]." LIMIT 1";
				$db->query($sql_disable);
			}
		}	
	}
	
	
	/*if (count($_REQUEST['letter_disabled']))
	{
		$let_arr	= array(0);
		for ($i=0;$i<count($_REQUEST['letter_disabled']);$i++)
		{
			$let_arr[] = $_REQUEST['letter_disabled'][$i];
		}
		
		
		
		
		
		$let_str = implode(",",$let_arr);
		// Setting the disabled items
		$sql_disable = "UPDATE general_settings_site_letter_templates SET lettertemplate_disabled = 1 WHERE 
						lettertemplate_id IN ($let_str)";
		$db->query($sql_disable);
		
		// Setting the non disabled items
		$sql_enable = "UPDATE general_settings_site_letter_templates SET lettertemplate_disabled = 0 WHERE 
						sites_site_id=$ecom_siteid AND lettertemplate_id NOT IN ($let_str)";
		$db->query($sql_enable);
	}
	else
	{
		
		$update_array								= array();
		$update_array['lettertemplate_disabled']	= 0;
		$db->update_from_array($update_array,'general_settings_site_letter_templates',array('sites_site_id'=>$ecom_siteid));
	}*/
	
	$alert = 'Details Saved Successfully';
	include("includes/letter_templates/list_settings_letter_templates.php");
}

?>