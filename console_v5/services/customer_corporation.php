<?php

if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/customer_corporation/list_customer_corporation.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$corporation_ids_arr 		= explode('~',$_REQUEST['corporation_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($corporation_ids_arr);$i++)
		{
			$update_array							= array();
			$update_array['corporation_hide']		= $new_status;
			$corporation_id 						= $corporation_ids_arr[$i];	
			$db->update_from_array($update_array,'customers_corporation',array('corporation_id'=>$corporation_id));
			
		}
		
		
		$alert = 'Status changed successfully.';
	    include("../includes/customer_corporation/list_customer_corporation.php");
		
}

else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Business Customer not selected';
		}
		else
		{
			$few_not_deleted = false;	
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Check whether the corporation is linked with any of the departments
					$sql_check = "SELECT department_id 
									FROM 
										customers_corporation_department 
									WHERE 
										customers_corporation_corporation_id = ".$del_arr[$i]." 
									LIMIT 
										1";
					$ret_check = $db->query($sql_check);
					if ($db->num_rows($ret_check)==0)
					{
						$sql_del = "DELETE FROM customers_corporation WHERE corporation_id=".$del_arr[$i];
					 	$db->query($sql_del);
					}
					else 
						$few_not_deleted = true;	
				}	
			}
			if($few_not_deleted == false)
				$alert = "Business Customer deleted Sucessfully";
			else 	
				$alert = "Operation Successfull. Business Customer linked with department(s) not deleted. Remove the department(s) before deleting the Business Customer.";
		}
		include ('../includes/customer_corporation/list_customer_corporation.php');
	

}

else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/customer_corporation/add_customer_corporation.php");
}

else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include_once("classes/fckeditor.php");
	  include ('includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		$corporation_id = $_REQUEST['checkbox'][0];
	include("includes/customer_corporation/edit_customer_corporation.php");
	
}

else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
	//Function to validate forms
	validate_forms();
	
		$sql_check = "SELECT count(*) as cnt FROM customers_corporation WHERE corporation_name = '".add_slash($_REQUEST['corporation_name'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Error: Business Customer Name Already exists '; 
		if($_REQUEST['corporation_discount']>99) 
		    $alert = 'Error: Discount value Should be less than 100% ';
		if($_REQUEST['corporation_costplus']>99) 
		    $alert = 'Error: Business Customer costplus value Should be less than 100% ';	
	
	if($alert)
	{?>
		<!--<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php //echo //$alert?></font><br>-->
			
	<?php	
	include("includes/customer_corporation/add_customer_corporation.php");
	}
	else
	{
			$insert_array										= array();
			$insert_array['sites_site_id'] 						= $ecom_siteid;
			$insert_array['corporation_name'] 					= add_slash($_REQUEST['corporation_name']);
			$insert_array['corporation_type'] 					= add_slash($_REQUEST['corporation_type']);
			$insert_array['corporation_regno']	 				= add_slash($_REQUEST['corporation_regno']);
			$insert_array['corporation_vatno'] 					= add_slash($_REQUEST['corporation_vatno']);
			$insert_array['corporation_otherdetails'] 					= add_slash($_REQUEST['corporation_otherdetails']);
			$insert_array['corporation_admin_id'] 				= add_slash($_REQUEST['corporation_admin_id']);
			$insert_array['corporation_billing_id'] 			= add_slash($_REQUEST['corporation_billing_id']);
			$insert_array['corporation_discount_method'] 		= add_slash($_REQUEST['corporation_discount_method']);
			$insert_array['corporation_discount'] 				= add_slash($_REQUEST['corporation_discount']);
			$insert_array['corporation_allow_product_discount'] = ($_REQUEST['corporation_allow_product_discount'])?1:0;
			$insert_array['corporation_costplus'] 				= add_slash($_REQUEST['corporation_costplus']);
			//print_r($update_array);
			
					$db->insert_from_array($insert_array, 'customers_corporation');
			$insert_id = $db->insert_id();	
	?>
		<br><font color="red"><b>Business Customer Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?php echo $insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a><br /><br />
		<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Add Page</a>
		
	<?php
	}

	
	}
}

else if($_REQUEST['fpurpose'] == 'update_corporation') {  // for updating the corporation

	if($_REQUEST['corporation_id'])
	{
		//Function to validate forms
		validate_forms();
		$sql_check = "SELECT count(*) as cnt FROM customers_corporation WHERE corporation_name = '".add_slash($_REQUEST['corporation_name'])."' AND sites_site_id=$ecom_siteid AND corporation_id <>".$_REQUEST['corporation_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Error: Business Customer Name Already exists '; 
		if($_REQUEST['corporation_discount']>99) 
		    $alert = 'Error: Discount value Should be less than 100% ';
		if($_REQUEST['corporation_costplus']>99) 
		    $alert = 'Error: Business Customer costplus value Should be less than 100% ';	
			 	
		if (!$alert)
		{
		
			$update_array										= array();
			$update_array['sites_site_id'] 						= $ecom_siteid;
			$update_array['corporation_name'] 					= add_slash($_REQUEST['corporation_name']);
			$update_array['corporation_type'] 					= add_slash($_REQUEST['corporation_type']);
			$update_array['corporation_regno']	 				= add_slash($_REQUEST['corporation_regno']);
			$update_array['corporation_vatno'] 					= add_slash($_REQUEST['corporation_vatno']);
			$update_array['corporation_otherdetails'] 			= add_slash($_REQUEST['corporation_otherdetails']);
			$update_array['corporation_admin_id'] 				= add_slash($_REQUEST['corporation_admin_id']);
			$update_array['corporation_billing_id'] 			= add_slash($_REQUEST['corporation_billing_id']);
			$update_array['corporation_discount_method'] 		= add_slash($_REQUEST['corporation_discount_method']);
			$update_array['corporation_discount'] 				= add_slash($_REQUEST['corporation_discount']);
			$update_array['corporation_allow_product_discount'] = ($_REQUEST['corporation_allow_product_discount'])?1:0;
			$update_array['corporation_costplus'] 				= add_slash($_REQUEST['corporation_costplus']);
			//print_r($update_array);
			$db->update_from_array($update_array, 'customers_corporation', array('corporation_id' => $_REQUEST['corporation_id'] , 'sites_site_id' => $ecom_siteid));
			
	
				
			?>
			<br><font color="red"><b>Business Customer Updated Successfully</b></font><br>
			<br />
			<a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>">Go Back to the Business Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Add Page</a><br /><br />
			
			<?php
		}
		else
		{
		?>
		<!--	<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php // echo $alert?></font><br>-->
	<?php
	$ajax_return_function = 'ajax_return_contents';
		include_once("classes/fckeditor.php");
		include "ajax/ajax.php";
	    include ('includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		include("includes/customer_corporation/edit_customer_corporation.php");

		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Business Customer Id</font><br />
		<br /><a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Add Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Business Customer Listing page</a><br /><br />
		
	<?php	
	} //// updating corporation ends



}

/*To list departments assigned in the Corporation  using AJAX*/
elseif($_REQUEST['fpurpose'] == 'list_departmentsInCorporation_ajax'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$ajax_return_function = 'ajax_return_contents';
		include "../ajax/ajax.php";
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		show_department_list($_REQUEST['cur_corporationid']);
}

/*To list business customers main informations*/
elseif($_REQUEST['fpurpose'] == 'list_business_customer_maininfo'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		show_customer_maniinfo($_REQUEST['cur_corporationid']);
}

/*To list department main informations*/
elseif($_REQUEST['fpurpose'] == 'show_department_maniinfo'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		show_department_maniinfo($_REQUEST['cur_departmentid'],$alert);
}

else if($_REQUEST['fpurpose']=='edit_department')
{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include_once("classes/fckeditor.php");
		include ('includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		$corporation_id = $_REQUEST['checkbox'][0];
	include("includes/customer_corporation/edit_corporation_departments.php");
}

elseif($_REQUEST['fpurpose'] == 'changestat_department_ajax'){ // To Change the status of the selected department in the Corporation
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Departments not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the department
					
				 $sql_chstat = "UPDATE customers_corporation_department SET department_hide = ".$_REQUEST['chstat']." WHERE department_id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Departments'; 
		}	
		show_department_list($_REQUEST['cur_corporation_id'],$alert);
}

else if($_REQUEST['fpurpose'] == 'add_departments'){// to add deaprtment to a corporation
$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	$corporation_id = $_REQUEST['checkbox'][0];
	include ('includes/customer_corporation/add_corporation_departments.php');						
	
}

else if($_REQUEST['fpurpose'] == 'add_customers'){// to list the customers not assigned to any of the departments
	$department_id = $_REQUEST['checkbox'][0];
	include ('includes/customer_corporation/list_department_customers.php');						
	
}

elseif($_REQUEST['fpurpose'] == 'list_state') // show state list
{ 
        include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$ajax_return_function = 'ajax_return_contents';
	     include "../ajax/ajax.php";
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		show_display_state_list($_REQUEST['country_id'],$_REQUEST['state_id']);
}

else if($_REQUEST['fpurpose'] == 'insert_department'){// to insert the department added in the corporation

	 $corporation_id = $_REQUEST['corporation_id'];
	{
	$alert = '';
			//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['department_name']);
		$fieldDescription 	= array('Department Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
				
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	if(!$alert){	
		$sql_chk_departments = "SELECT count(department_id) as cnt FROM customers_corporation_department WHERE customers_corporation_corporation_id =".$corporation_id." AND department_name='".add_slash($_REQUEST['department_name'])."'";
		$res_chk_departments = $db->query($sql_chk_departments);
		$chk_departments = $db->fetch_array($res_chk_departments);
		if($chk_departments['cnt'] > 0){
		$alert = "Same department name exists in the Business Customer";
		}
}
	if(!$alert)	{
					$insert_array = array();
					$insert_array['sites_site_id']							=$ecom_siteid;
					$insert_array['customers_corporation_corporation_id']	=$_REQUEST['corporation_id'];
					$insert_array['department_name']						=$_REQUEST['department_name'];
					$insert_array['department_building']					=$_REQUEST['department_building'];
					$insert_array['department_street']						=$_REQUEST['department_street'];
					$insert_array['department_town']						=$_REQUEST['department_town'];
					if($_REQUEST['customer_statecounty']!=-1)
					{
						$insert_array['state_id']						        =$_REQUEST['customer_statecounty'];
					}
					elseif($_REQUEST['customer_statecounty']==-1)
					{
					   if($_REQUEST['other_state']!='')
					   {
							$sql_checkstate = "SELECT state_id FROM general_settings_site_state WHERE sites_site_id=".$ecom_siteid." AND general_settings_site_country_country_id=".$_REQUEST['country_id']." AND state_name='".add_slash($_REQUEST['other_state'])."'"; 	
							$ret_chsckstate = $db->query($sql_checkstate);
							if($db->num_rows($ret_chsckstate)>0)
							{
								$row_checkstate		 = $db->fetch_array($ret_chsckstate);
								$insert_array['state_id']						        =$row_checkstate['state_id'];
							}
							else
							{   $insert_st_array												=array();
								$insert_st_array['sites_site_id']									=$ecom_siteid;
								$insert_st_array['general_settings_site_country_country_id']	 	=$_REQUEST['country_id'];
								$insert_st_array['state_name']										=add_slash($_REQUEST['other_state']);
								$insert_st_array['state_hide']										=1;
								$db->insert_from_array($insert_st_array, 'general_settings_site_state');
								$insert_id = $db->insert_id();
								$insert_array['state_id']										=$insert_id;
							}
						}
					}
					$insert_array['country_id']						        =$_REQUEST['country_id'];
					$insert_array['department_postcode']					=$_REQUEST['department_postcode'];
					$insert_array['department_phone']						=$_REQUEST['department_phone'];
					$insert_array['department_fax']							=$_REQUEST['department_fax'];
					$insert_array['department_hide']						=($_REQUEST['department_hide'])?1:0;			
					$db->insert_from_array($insert_array, 'customers_corporation_department');
			$insert_id = $db->insert_id();
			$alert = 'Department(s) Successfully added to Business Customer'; 
			$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Business Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&curtab=department_tab_td" onclick="show_processing()">Go Back to the Edit  this Business Customer</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Add Page</a><br /><br />
			<br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add_departments&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Department Add Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit_department&corporation_id=<?=$_REQUEST['corporation_id']?>&department_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  this Department</a><br /><br />
		
			<?
		}else{		
		include ('includes/customer_corporation/add_corporation_departments.php');
		}			
	
	}
	
	
}

else if($_REQUEST['fpurpose'] == 'update_department') {  // for updating the departments

	if($_REQUEST['corporation_id'] && $_REQUEST['department_id'] )
	{
		//Function to validate forms
		$alert = '';
		$fieldRequired 		= array($_REQUEST['department_name']);
		$fieldDescription 	= array('Department Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
				
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert){
		$sql_check = "SELECT count(*) as cnt FROM customers_corporation_department WHERE department_name = '".add_slash($_REQUEST['department_name'])."' AND sites_site_id=$ecom_siteid AND department_id <>".$_REQUEST['department_id']." AND customers_corporation_corporation_id = ".$_REQUEST['corporation_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Error: Same Department Name Already exists in the corporation '; 
		}	
		if (!$alert)
		{
		     
			$update_array										= array();
			$update_array['sites_site_id'] 						= $ecom_siteid;
			$update_array['department_name'] 					= add_slash($_REQUEST['department_name']);
			$update_array['department_building'] 					= add_slash($_REQUEST['department_building']);
			$update_array['department_street']	 				= add_slash($_REQUEST['department_street']);
			$update_array['department_town'] 					= add_slash($_REQUEST['department_town']);
			//update the other state section
			if($_REQUEST['customer_statecounty']!=-1)
					{
						$update_array['state_id']						        =$_REQUEST['customer_statecounty'];
					}
			elseif($_REQUEST['customer_statecounty']==-1)
				{
					   if($_REQUEST['other_state']!='')
					   {
							$sql_checkstate = "SELECT state_id FROM general_settings_site_state WHERE sites_site_id=".$ecom_siteid." AND general_settings_site_country_country_id=".$_REQUEST['country_id']." AND state_name='".add_slash($_REQUEST['other_state'])."'"; 	
							$ret_chsckstate = $db->query($sql_checkstate);
							if($db->num_rows($ret_chsckstate)>0)
							{
								$row_checkstate		 = $db->fetch_array($ret_chsckstate);
								$update_array['state_id']						        =$row_checkstate['state_id'];
							}
							else
							{   $insert_st_array												=array();
								$insert_st_array['sites_site_id']									=$ecom_siteid;
								$insert_st_array['general_settings_site_country_country_id']	 	=$_REQUEST['country_id'];
								$insert_st_array['state_name']										=add_slash($_REQUEST['other_state']);
								$insert_st_array['state_hide']										=1;
								$db->insert_from_array($insert_st_array, 'general_settings_site_state');
								$insert_id = $db->insert_id();
								$update_array['state_id']										=$insert_id;
							}
					    }
					}
			$update_array['country_id'] 				= add_slash($_REQUEST['country_id']);
			$update_array['department_postcode'] 			= add_slash($_REQUEST['department_postcode']);
			$update_array['department_phone'] 		= add_slash($_REQUEST['department_phone']);
			$update_array['department_fax'] 				= add_slash($_REQUEST['department_fax']);
			$update_array['department_hide'] = ($_REQUEST['department_hide'])?1:0;
			//print_r($update_array);
			$db->update_from_array($update_array, 'customers_corporation_department', array('department_id' => $_REQUEST['department_id'] , 'sites_site_id' => $ecom_siteid));
			
			?>
			<br><font color="red"><b>Department Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&curtab=department_tab_td">Go Back to the Business Customer Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Business Customer Add Page</a><br /><br />
			<br /><br /><a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add_departments&checkbox[]=<?=$_REQUEST['corporation_id']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Department Add Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit_department&corporation_id=<?=$_REQUEST['corporation_id']?>&department_id=<?=$_REQUEST['department_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Department Edit Page</a><br /><br />
			<?php
		}
		else
		{
		?>
		<!--	<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php // echo $alert?></font><br>-->
	<?php
	       $ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
		include ('includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		include("includes/customer_corporation/edit_corporation_departments.php");

		}
	}
	
}

/*To list Customers assigned in the Departments using AJAX*/
elseif($_REQUEST['fpurpose'] == 'list_customersInDepartment_ajax'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		
		//echo $_REQUEST['total'];
		if($_REQUEST['recs']==''){
		$_REQUEST['recs']=10;
		}
		
		show_customers_list($_REQUEST['cur_departmentid'],'',$_REQUEST['curntpage'],$_REQUEST['recs']);
		//echo $retval;
}

else if($_REQUEST['fpurpose'] == 'assign_customers'){// to asign the customers to departments

	
	$department_id = $_REQUEST['department_id'];
	{
		
		if ($_REQUEST['customer_ids'] == '')
		{
			$alert = 'Sorry No Customers Selected';
		}
		else
		{ 
		
				$customer_arr = explode("~",$_REQUEST['customer_ids']);
			for($i=0;$i<count($customer_arr);$i++)
			{
				if(trim($customer_arr[$i]) )
				{
					$update_array = array();
					$update_array['customers_corporation_department_department_id']=$department_id;
					$db->update_from_array($update_array, 'customers',array('customer_id' => $customer_arr[$i] , 'sites_site_id' => $ecom_siteid));
				}	
			}
			$alert = 'Customers Successfully assigned  to Department(s)'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=customer_corporation&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Business Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=edit_department&department_id=<?=$_REQUEST['department_id']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&curtab=customer_tab_td" onclick="show_processing()">Go Back to the Edit  this department</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_corporation&fpurpose=add_departments&search_name=<?=$_REQUEST['search_name']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Department</a>		
			<?
	
}

elseif($_REQUEST['fpurpose']=='delete_department_ajax') // section used for delete of Category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry No Departments Selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$cant_deldept=0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				$sql_customer_chk = "SELECT customer_id FROM customers WHERE customers_corporation_department_department_id = ".$del_arr[$i]." LIMIT 1";
				$ret_customer_chk = $db->query($sql_customer_chk);
				$cnt = $db->num_rows($ret_customer_chk);
				if($cnt==0)
				{
					$sql_del = "DELETE FROM customers_corporation_department WHERE department_id=".$del_arr[$i];
					$db->query($sql_del);					
				}
				else 
					$cant_deldept=1;
				}	
			}
			if($cant_deldept)
			{
				$alert= "Operation Successfully. Department with Customers not deleted.";
			}
			else
			{
				$alert = "Departments deleted Successfully ";
			}
			
		}	
show_department_list($_REQUEST['cur_corporation_id'],$alert);
}

elseif($_REQUEST['fpurpose']=='delete_customers_ajax') // section used for delete of customers using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/customer_corporation/ajax/customers_corporation_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry No Customers Selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$cant_deldept=0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
				$sql_unassign_customers = "UPDATE customers SET customers_corporation_department_department_id = 0 where customer_id = ".$del_arr[$i];
				$unassign_customers = $db->query($sql_unassign_customers);
				}	
				$alert = "selected customers are sucessfully unassigned from the department";
			}
			
		}	
show_customers_list($_REQUEST['cur_department_id'],$alert,'',$_REQUEST['recs']);
 
}


// ===============================================================================
// 						FUNCTIONS USED IN THIS PAGE
// ===============================================================================	
function validate_forms()
{
	global $alert,$db;
	if($_REQUEST['dont_save']!=1)
	{
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['corporation_name']);
		$fieldDescription 	= array('Business Customer Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
				
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}

?>
