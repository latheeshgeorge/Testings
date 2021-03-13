<?php
if($_REQUEST['fpurpose']=='') //List sections
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/custom_form/list_sections.php");
}
else if($_REQUEST['fpurpose']=='save_details') // Save details
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$ajax_return_function = 'ajax_return_contents';
	include "../ajax/ajax.php";	
	$ids_arr 		= explode('~',$_REQUEST['ch_ids']);
	$labels_arr		= explode('~',$_REQUEST['ch_labels']); 	
	$orders_arr		= explode('~',$_REQUEST['ch_order']); 	
	$mandatory_arr	= explode('~',$_REQUEST['ch_mandatory']);
	$mandatoryfeilds = implode(",", $mandatory_arr);
	$alert='';
	/*if($mandatoryfeilds){
	$sql_chk_errmsg_mandatory = "SELECT element_id,element_type,error_msg FROM elements WHERE sites_site_id=".$ecom_siteid." AND element_id IN ($mandatoryfeilds)  ";
	$ret_chk_errmsg_mandatory = $db->query($sql_chk_errmsg_mandatory);
	}
	if($db->num_rows($ret_chk_errmsg_mandatory)){
		while($errmsg_mandatory   = $db->fetch_array($ret_chk_errmsg_mandatory)){
			if($errmsg_mandatory['element_type']=='select' || $errmsg_mandatory['element_type']=='checkbox' || $errmsg_mandatory['element_type']=='radio'){ // to check whether if there is valuue aded for the select,radio,and check box elements
				$sql_chk_values_cnt = "SELECT value_id  FROM element_value WHERE elements_element_id=".$errmsg_mandatory['element_id'];
				$ret_chk_values_cnt = $db->query($sql_chk_values_cnt);
				if(!$db->num_rows($ret_chk_values_cnt)){
					$update_array= array();
					$update_array['mandatory']    = 'N';
					$db->update_from_array($update_array,'elements',array('element_id'=>$errmsg_mandatory['element_id']));
					$alert = 'Error:- Feilds without values for select,radio,check box elements will not be considered as mandatory!!.';
				}
			}
			if($errmsg_mandatory['error_msg']==''){
				$update_array= array();
				$update_array['mandatory']    = 'N';
				$db->update_from_array($update_array,'elements',array('element_id'=>$errmsg_mandatory['element_id']));
				$alert = 'Error:- Feilds without Error messages elements will not be considered as mandatory!!.';
			}
		}

	}*/
	
	if($alert==''){
			
		//foreach($mandatory_arr => $key as $val){
		//	$sql_chk_errmsg_madatory = "SELECT count(element_id) FROM elements WHERE sites_site_id=".sites_site_id." AND element_id IN ($mandatoryfeilds)  AND error_msg=''";
		//}
		for($i=0;$i<count($ids_arr);$i++)
		{
			$update_array= array();
			$update_array['element_label']  = add_slash($labels_arr[$i]);
			$update_array['sort_no']        =  add_slash($orders_arr[$i]);
			$sql_chk_errmsg_mandatory = "SELECT element_id,element_type,error_msg FROM elements WHERE sites_site_id=".$ecom_siteid." AND element_id=".$ids_arr[$i];
			$ret_chk_errmsg_mandatory = $db->query($sql_chk_errmsg_mandatory);
			$errmsg_mandatory   = $db->fetch_array($ret_chk_errmsg_mandatory);
			if(in_array($ids_arr[$i],$mandatory_arr) && $labels_arr[$i]!='')
			{
			if($errmsg_mandatory['element_type']=='select' || $errmsg_mandatory['element_type']=='checkbox' || $errmsg_mandatory['element_type']=='radio'){ // to check whether if there is valuue aded for the select,radio,and check box elements
              $sql_chk_values_cnt = "SELECT value_id  FROM element_value WHERE elements_element_id=".$errmsg_mandatory['element_id'];
				$ret_chk_values_cnt = $db->query($sql_chk_values_cnt);
				if(!$db->num_rows($ret_chk_values_cnt)){
						$update_array= array();
						$update_array['mandatory']    = 'N';
						$ch_false = 1;
					}
				else
				{  
						if($errmsg_mandatory['error_msg']!='')
						{
							$update_array['mandatory']    = 'Y';
						}
						else
						{
							$update_array['mandatory']    = 'N';
							$error_msg =1;
						}
				}
			}	
			else if($errmsg_mandatory['error_msg']==''){
						$update_array['mandatory']    = 'N';
						$error_msg =1;
			}
			else
			{
					$update_array['mandatory']    = 'Y';
			}
			}
			else
			{
			    $update_array['mandatory']    = 'N';
			}
			$db->update_from_array($update_array,'elements',array('element_id'=>$ids_arr[$i]));
		}
		$alert = 'Details changed successfully.';
		if($ch_false==1)
		{
		 $alert .="<br>Error:- Fields without values for select,radio,check box elements will not be considered as mandatory!!.";
		}
		if($error_msg ==1)
		{
		 $alert .= "<br>Error:- Fields without Error messages elements will not be considered as mandatory!!.";
		
		}
	}
	echo $script;
	include ('../includes/custom_form/list_forms.php');
}
else if($_REQUEST['fpurpose']=='delete_element') //Delete Element
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Element not selected';
		}
		else
		{
			$del_cnt = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM elements WHERE element_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_cnt++;				
				}	
			}
			if($del_cnt>0)
			{
			 if($alert) $alert .="<br />";
						$alert .= $del_cnt." Element(s) Deleted Successfully";
			}			
		}
		include ('../includes/custom_form/list_forms.php');
	

}
elseif($_REQUEST['fpurpose']=='manage_form')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/custom_form/list_forms.php");
}
elseif($_REQUEST['fpurpose']=='change_hide') //Status updation
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$section_ids_arr 		= explode('~',$_REQUEST['section_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($section_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['activate']	= $new_status;
			$section_id 					= $section_ids_arr[$i];	
			$db->update_from_array($update_array,'element_sections',array('section_id'=>$section_id));
			
		}
		
		
		$alert = 'Hidden Status changed successfully.';
		include ('../includes/custom_form/list_sections.php');
		
}
else if($_REQUEST['fpurpose']=='delete_section') //Delete section
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Section not selected';
		}
		else
		{
			$del_cnt = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM element_sections WHERE section_id=".$del_arr[$i];
					  $db->query($sql_del);
					  $del_cnt++;				
				}	
			}
			if($del_cnt>0)
			{
			 if($alert) $alert .="<br />";
						$alert .= $del_cnt." Section(s) Deleted Successfully";
			}			
		}
		
		include ('../includes/custom_form/list_sections.php');
	

}
elseif($_REQUEST['fpurpose']=='edit_section') //Edit section
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include "includes/custom_form/ajax/custom_form_ajax_functions.php";
	include("includes/custom_form/edit_section.php");
}
elseif ($_REQUEST['fpurpose']=='list_section_products')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/custom_form/ajax/custom_form_ajax_functions.php');
	show_section_product_list($_REQUEST['cur_secid']);	
}
elseif ($_REQUEST['fpurpose']=='assign_secprod') // showing the page to select the products to be linked with current dynamic section
{
	include ('includes/custom_form/sel_custom_section_products.php');	
}
elseif ($_REQUEST['fpurpose']=='assig_prodlink')
{
	if(count($_REQUEST['checkbox_link']))
	{
		for ($i=0;$i<count($_REQUEST['checkbox_link']);$i++)
		{
			// Check whether this product is already mapped
			$sql_check = "SELECT id FROM element_section_products WHERE 
							element_sections_section_id=".$_REQUEST['pass_editid']." AND products_product_id = ".$_REQUEST['checkbox_link'][$i]." AND 
							sites_site_id=$ecom_siteid";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)==0)
			{
				$insert_array									= array();
				$insert_array['element_sections_section_id']	= $_REQUEST['pass_editid'];
				$insert_array['products_product_id']			= $_REQUEST['checkbox_link'][$i];
				$insert_array['sites_site_id']					= $ecom_siteid;
				$insert_array['product_active']					= 1;
				$db->insert_from_array($insert_array,'element_section_products');
			}	
		}
		$_REQUEST['checkbox'][0] 	= $_REQUEST['pass_editid'];
		$alert 						= "Products assigned Successfully";
		$ajax_return_function 		= 'ajax_return_contents';
		include "ajax/ajax.php";
		include "includes/custom_form/ajax/custom_form_ajax_functions.php";
		include ('includes/custom_form/edit_section.php');
	}
	else
	{
		$alert = "Please select the products to be linked with the current promotional code";
		include ('includes/custom_form/sel_custom_section_products.php');
	}
}
elseif ($_REQUEST['fpurpose'] == 'unassignsecproduct') // Section to unassing products from dynamic section
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include_once ('../includes/custom_form/ajax/custom_form_ajax_functions.php');
	$prodid_arr 	= explode('~',$_REQUEST['del_ids']);
	$curid			= $_REQUEST['edit_id'];
	for($i=0;$i<count($prodid_arr);$i++)
	{
		$sql_del = "DELETE FROM element_section_products WHERE sites_site_id=$ecom_siteid AND 
					element_sections_section_id = $curid AND products_product_id=".$prodid_arr[$i];
		$db->query($sql_del);
	}
	$alert = 'Product(s) Unassigned Successfully.';
	show_section_product_list($curid,$alert);
}
elseif ($_REQUEST['fpurpose']=='chstatussecproduct') // Section to change the status of products in current dynamic section
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include_once ('../includes/custom_form/ajax/custom_form_ajax_functions.php');
	$chid_arr 	= explode("~",$_REQUEST['ch_ids']);
	$codeid		= $_REQUEST['edit_id'];
	$cur_stat	= ($_REQUEST['prod_active'])?1:0;
	if (count($chid_arr))
	{
		for ($i=0;$i<count($chid_arr);$i++)
		{
			$curid 							= $chid_arr[$i];
			$update_array					= array();
			$update_array['product_active']	= $cur_stat;
			$db->update_from_array($update_array,'element_section_products',array('sites_site_id'=>$ecom_siteid,'element_sections_section_id'=>$codeid,'products_product_id'=>$curid));
		}
		$alert = 'Status Changed Successfully';
		show_section_product_list($codeid,$alert);
	}
	else
	{
		$alert = 'Please select the product to change the status';
		show_section_product_list($curid,$alert);
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update section
 {
	if($_REQUEST['Submit'])
	{
		
		
		$alert='';
		$fieldRequired = array($_REQUEST['section_name']);
		$fieldDescription = array('Section Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM element_sections WHERE section_name = '".trim(add_slash($_REQUEST['section_name']))."' AND section_type='".$_REQUEST['form_type']."'  AND sites_site_id=$ecom_siteid AND section_id<>".$_REQUEST['section_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Section Name Already exists '; 
		if(!$alert) {
			$update_array = array();
			$update_array['section_name']					= trim(add_slash($_REQUEST['section_name']));
			$update_array['activate']						= $_REQUEST['activate'];
			$update_array['sort_no']						= add_slash($_REQUEST['sort_no']);
			$update_array['message']						= add_slash($_REQUEST['message'],false);
			$update_array['position']						= $_REQUEST['position'];
			$update_array['section_to_specific_products']	= ($_REQUEST['section_to_specific_products'])?1:0;
			$update_array['hide_heading']				= ($_REQUEST['hide_heading'])?1:0;
			$db->update_from_array($update_array, 'element_sections', 'section_id', $_REQUEST['section_id']);
			$alert .= '<br><span class="redtext"><b>Section Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=edit_section&section_id=<?=$_REQUEST['section_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=add_section&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = 'Error! '.$alert;
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/custom_form/edit_section.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='add_section') //Add new Section
{
	include("includes/custom_form/add_section.php");
}
elseif($_REQUEST['fpurpose']=='insert') //Save new section
{
	
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['section_name']);
		$fieldDescription = array('Section Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM element_sections WHERE section_name = '".trim(add_slash($_REQUEST['section_name']))."' AND section_type='".$_REQUEST['form_type']."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Section Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['section_name']						= trim(add_slash($_REQUEST['section_name']));
			$insert_array['activate']								= $_REQUEST['activate'];
			$insert_array['sites_site_id']							= $ecom_siteid;
			$insert_array['sort_no']								= add_slash($_REQUEST['sort_no']);
			$insert_array['message']								= add_slash($_REQUEST['message'],false);
			$insert_array['position']								= $_REQUEST['position'];
			$insert_array['section_type']							= $_REQUEST['form_type'];
			$insert_array['section_to_specific_products']	= ($_REQUEST['section_to_specific_products'])?1:0;
			$insert_array['hide_heading']							= ($_REQUEST['hide_heading'])?1:0;
			$db->insert_from_array($insert_array, 'element_sections');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Section added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=edit_section&section_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&fpurpose=add_section&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}
		else
		{
			$alert = 'Error!!'.$alert;
			include("includes/custom_form/add_section.php");
		}
	}	
}
?>