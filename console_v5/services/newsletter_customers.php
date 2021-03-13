<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter_customers/list_newsletter_customers.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$customer_ids_arr 		= explode('~',$_REQUEST['news_customer_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($customer_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['news_custhide']	= $new_status;
			$news_customer_id 				= $customer_ids_arr[$i];	
			$db->update_from_array($update_array,'newsletter_customers',array('news_customer_id'=>$news_customer_id));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/newsletter_customers/list_newsletter_customers.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Customer  not selected';
		}
		else
		{
		
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Get the customer_id associated with current newsletter customer
					$sql_cust = "SELECT customer_id 
									FROM 
										newsletter_customers 
									WHERE 
										news_customer_id=".$del_arr[$i]." 
									LIMIT 
										1";
					$ret_cust = $db->query($sql_cust);
					if($db->num_rows($ret_cust))
					{
						$row_cust = $db->fetch_array($ret_cust);
						if($row_cust['customer_id']!=0)
						{
							// updating the customer table to set customer_in_mailing_list field to 0 for respective customer
							$update_sql = "UPDATE customers 
											SET 
												customer_in_mailing_list = 0 
											WHERE 
												customer_id = ".$row_cust['customer_id']." 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$db->query($update_sql);
						}
					}					
					 $sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$del_arr[$i];
					 $db->query($sql_del);
					 $sql_delcat = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$del_arr[$i];
					 $db->query($sql_delcat);				
					 $alert  = "Newsletter Customer Deleted ";
				}	
			}
		}
		include ('../includes/newsletter_customers/list_newsletter_customers.php');
}
else if($_REQUEST['fpurpose']=='add')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter_customers/add_newsletter_customers.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	
 $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter_customers/edit_newsletter_customers.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired 	= array($_REQUEST['news_custname'],$_REQUEST['news_custemail']);
		$fieldDescription = array('Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM newsletter_customers WHERE news_custemail = '".add_slash($_REQUEST['news_custemail'])."' AND sites_site_id=$ecom_siteid ";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Same Email Already exists for newsletter';
			
		if(!$alert) {
		$sql_check = "SELECT customer_id FROM customers WHERE customer_email_7503 = '".add_slash($_REQUEST['news_custemail'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
			$insert_array = array();
			if($db->num_rows($res_check)) {
				$row_check = $db->fetch_array($res_check);
				$insert_array['customer_id']	=	$row_check['customer_id'];
				// Updating the customers table to set the field customer_in_mailing_list to 1 for obtained customer
				$sql_update = "UPDATE customers 
								SET 
									customer_in_mailing_list = 1
								WHERE 
									customer_id =".$row_check['customer_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$db->query($sql_update);
			}else{
				$insert_array['customer_id']	=	0;
			}
			
			$insert_array['news_title'] 		=$_REQUEST['news_title'];
			$insert_array['news_custname']		=add_slash($_REQUEST['news_custname']);
			$insert_array['news_custemail']		=add_slash($_REQUEST['news_custemail']);
			$insert_array['news_custphone']		=add_slash($_REQUEST['news_custphone']);
			$insert_array['news_join_date']		=add_slash($_REQUEST['news_join_date']);
			$insert_array['news_custhide']		=($_REQUEST['news_custhide'])?1:0;
			$insert_array['sites_site_id']					=$ecom_siteid;
			$db->insert_from_array($insert_array, 'newsletter_customers');
			$insert_id = $db->insert_id();
		    
			// Calling the function to save the details of dynamic fields
			if(count($_REQUEST['chk_group'])){
		 	
			$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map 
									WHERE customer_id=".$insert_id."";
		    $db->query($sql_del_map);
			
			foreach($_REQUEST['chk_group'] as $key =>$val){
									$insert_array = array();
									$insert_array['customer_id'] = $insert_id;
									$insert_array['custgroup_id'] = $val;
									$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
								}
		 } 
			$alert .= '<br><span class="redtext"><b>Newsletter Customer added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=newsletter_customers&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_name=<?=$_REQUEST['search_name']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Newsletter Customers Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter_customers&fpurpose=edit&news_customer_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Edit Newsletter Customers  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter_customers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Add New Newsletter Customers Page</a>
			<?
		}	
		else
		{
			$alert = '<span class=""><strong>Error!!</strong> '.$alert;
			$alert .= '</span>';
			include("includes/newsletter_customers/add_newsletter_customers.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{  
	    $news_customer_id = $_REQUEST['news_customer_id'];
		$alert='';
		$fieldRequired = array($_REQUEST['news_custname'],$_REQUEST['news_custemail']);
		$fieldDescription = array('Customer Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$update_array = array();
		// Get the details of current newsletter customers
		$sql_cust = "SELECT news_customer_id,news_custemail,customer_id 
						FROM 
							newsletter_customers 
						WHERE 
							news_customer_id=".$_REQUEST['news_customer_id']." 
							AND sites_site_id= $ecom_siteid
						LIMIT 
							1";
		$ret_cust = $db->query($sql_cust);
		if($db->num_rows($ret_cust))
		{
			$row_cust = $db->fetch_array($ret_cust);
			$old_email = stripslashes($row_cust['news_custemail']);
			$cur_email	= stripslashes($_REQUEST['news_custemail']);
			if($old_email!=$cut_email and $row_cust['customer_id']>0) /// case if existing email id is different from the current email id
			{
				$upd='UPDATE customers 
						SET 
							customer_in_mailing_list=0  
						WHERE 
							customer_id='.$row_cust['customer_id'].'  
							AND sites_site_id='.$ecom_siteid.'  
						LIMIT  
						1';			
				$db->query($upd);
			}	
		}
		$sql_check = "SELECT count(*) as cnt FROM newsletter_customers WHERE news_custemail = '".add_slash($_REQUEST['news_custemail'])."' AND sites_site_id=$ecom_siteid AND news_customer_id<>".$_REQUEST['news_customer_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Same Email Already exists for newsletter';
		if(!$alert) {
			$sql_check = "SELECT customer_id FROM customers WHERE customer_email_7503 = '".add_slash($_REQUEST['news_custemail'])."' AND sites_site_id=$ecom_siteid ";
			$res_check = $db->query($sql_check);
			if($db->num_rows($res_check)) {
				$row_check = $db->fetch_array($res_check);
				$update_array['customer_id']			=$row_check['customer_id'];
				// Updating the customers table to set the field customer_in_mailing_list to 1 for obtained customer
				$sql_update = "UPDATE customers 
								SET 
									customer_in_mailing_list = 1
								WHERE 
									customer_id =".$row_check['customer_id']." 
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$db->query($sql_update);
				
			}else {
				$update_array['customer_id']			=0;
			}
			$update_array['news_title'] 			=$_REQUEST['news_title'];
			$update_array['news_custname']			=add_slash($_REQUEST['news_custname']);
			$update_array['news_custemail']			=add_slash($_REQUEST['news_custemail']);
			$update_array['news_custphone']			=add_slash($_REQUEST['news_custphone']);
			$update_array['news_custhide']			=($_REQUEST['news_custhide'])?1:0;
				
		$db->update_from_array($update_array,'newsletter_customers',array('news_customer_id'=>$news_customer_id));
		
		 if(count($_REQUEST['chk_group'])){
		 	
			$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map 
									WHERE customer_id=".$_REQUEST['news_customer_id']."";
		    $db->query($sql_del_map);
			
			foreach($_REQUEST['chk_group'] as $key =>$val){
									$insert_array = array();
									$insert_array['customer_id'] = $_REQUEST['news_customer_id'];
									$insert_array['custgroup_id'] = $val;
									$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
								}
		 } else {
		 	$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map 
									WHERE customer_id=".$_REQUEST['news_customer_id']."";
		    $db->query($sql_del_map);
		 }
			// Calling the function to update the additional field values from the customer edit page
			$alert .= '<br><span class="redtext"><b>Newsletter Customer Details Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=newsletter_customers&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Newsletter Customers Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter_customers&fpurpose=edit&news_customer_id=<?=$_REQUEST['news_customer_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Edit Newsletter Customers Page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter_customers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&search_email=<?php echo $_REQUEST['search_email']?>" onclick="show_processing()">Go Back to the Add New NewsletterCustomer Page</a>
		<?	
		}
		
		else {
			$alert = '<center><font >Error! '.$alert;
			$alert .= '</font></center>';
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/newsletter_customers/edit_newsletter_customers.php");
		}
	}		
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
		$fieldDescription 	= array('Corporation Name');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
				
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}

?>
