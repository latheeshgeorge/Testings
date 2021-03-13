<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/customer_search/list_customer_search.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$customer_ids_arr = explode('~',$_REQUEST['customer_ids']);
		$new_status		  = $_REQUEST['ch_status'];
		for($i=0;$i<count($customer_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['customer_hide']	= $new_status;
			$customer_id 					= $customer_ids_arr[$i];	
			$db->update_from_array($update_array,'customers',array('customer_id'=>$customer_id));
		}
		
		$alert = 'Status changed successfully.';
		include ('../includes/customer_search/list_customer_search.php');
		
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
		    $count_cust=0;
			$cnt_all = 0;
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{ 
				      $cnt_all++;
					  //Added latheesh
					  $check_payon_acc = "SELECT pay_id FROM order_payonaccount_details WHERE customers_customer_id=".$del_arr[$i];
					  $ret_payon_acc   = $db->query($check_payon_acc);
					  if($db->num_rows($ret_payon_acc)==0)
					  {
					  $count_cust++;
					  $chkSql = "SELECT news_customer_id FROM newsletter_customers WHERE customer_id=".$del_arr[$i];
					  $chkRes = $db->query($chkSql);
					  $chknum = $db->num_rows($chkRes);
					  if($chknum>0) {
							while($chkrow = $db->fetch_array($chkRes)) {
								$news_customer_id = $chkrow['news_customer_id'];
								$delSql = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id='".$news_customer_id."'";
								$delRes = $db->query($delSql);
							}
					  $delsqlnews = "DELETE FROM newsletter_customers WHERE customer_id=".$del_arr[$i];
					  $delresnews = $db->query($delsqlnews);
					  }
					    // Delete all entries related to current customer from customer_discount_customers_map table 
                                          $sql_del = "DELETE 
                                                        FROM 
                                                          customer_discount_customers_map 
                                                        WHERE 
                                                          customers_customer_id = ".$del_arr[$i]."    
                                                          AND sites_site_id = $ecom_siteid  
                                                        LIMIT 
                                                          1";
                                          $db->query($sql_del);
					  $sql_del = "DELETE FROM customers WHERE customer_id=".$del_arr[$i];
					  $db->query($sql_del);
					  
					  $sql_delcat = "DELETE FROM customer_fav_categories WHERE customer_customer_id=".$del_arr[$i];
					  $db->query($sql_delcat);	
					  
					  $sql_exists_news = "SELECT news_customer_id 
										FROM 
											newsletter_customers 
										WHERE 
											customer_id ='".$del_arr[$i]."' 
											AND sites_site_id = $ecom_siteid
										LIMIT 
											1";
						$ret_exists_news = $db->query($sql_exists_news);
						
                                                if ($db->num_rows($ret_exists_news))
                                                {
                                                $row_exists_news = $db->fetch_array($ret_exists_news);
                                                
                                                    $update_array =array();
                                                        $update_array['customer_id']	= 0;
                                                        $db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
                                                }
					/*  if($alert) $alert .="<br />";
					  $alert .= "Customer with ID -".$del_arr[$i]." Deleted";*/
				  }//end of payon checking
				}	
			}
			  if($count_cust>0)
			  {
			  if($alert) $alert .="<br />";
					  $alert .= "$count_cust Customer(s) Deleted";
                            // Checking integrity of customer discount groups
                            check_customer_discountgroup_integrity();
			  }
			 $cnt_rem = $cnt_all - $count_cust;
			 if($cnt_rem>0)
			 {
			 if($alert) $alert .="<br />";
					  $alert .= "$cnt_rem Customer(s) Not Deleted Since they are linked with pay on Account ";
			 }		  
		}
		include ('../includes/customer_search/list_customer_search.php');
}
else if($_REQUEST['fpurpose']=='add')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/customer_search/add_customer.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	
 $ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";		
	include ('includes/customer_search/ajax/customer_ajax_functions.php');
	include("includes/customer_search/edit_customer.php");
}
else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Page group
	$customer_id = $_REQUEST['customer_id'];
	
	include ('includes/customer_search/list_assign_products.php');						
}
else if($_REQUEST['fpurpose'] == 'list_assign_categories'){// to list the products to be assigned to the Page group
	$customer_id = $_REQUEST['customer_id'];
	include ('includes/customer_search/list_fav_categories_selcategory.php');						
}
elseif($_REQUEST['fpurpose'] == 'list_customer_maininfo')
{ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		show_customermaininfo($_REQUEST['customer_id'],$alert,'');
}
elseif($_REQUEST['fpurpose'] == 'list_newsgroup')
{ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		show_newsletter_group_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'list_products_ajax')
{ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		show_product_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'list_categories_ajax')
{ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		show_favcategory_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'list_orders_ajax')
{ // for assigining products to the static page groups
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		show_order_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'save_news_group')
{ 
	// for assigining products to the static page groups
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/customer_search/ajax/customer_ajax_functions.php');
	$customer_ids_arr 		= explode('~',$_REQUEST['customer_ids']);
	$customer_in_mailing_list = ($_REQUEST['customer_in_mailing_list']==1)?1:0;
	// Updating the customers table
	$sql_update = "UPDATE customers 
						SET 
							customer_in_mailing_list=".$customer_in_mailing_list." 
						WHERE 
							customer_id = ".$_REQUEST['customer_id']." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
	$db->query($sql_update);
	//To get the customer details of the editing customer.
	$sql_cust = "SELECT 
							*
						FROM
							customers 
						WHERE
							customer_id	=".$_REQUEST['customer_id']." 
						AND  
							sites_site_id =	$ecom_siteid";
	$ret_cust = $db->query($sql_cust);
	$row_cust = $db->fetch_array($ret_cust);	
	// Check whether the email id already exists in the newsletter table
	if($row_cust['customer_email_7503']!='')
	{
		$sql_exists_news = "SELECT news_customer_id 
						FROM 
							newsletter_customers 
						WHERE 
							news_custemail ='".add_slash($row_cust['customer_email_7503'])."' 
							AND sites_site_id = $ecom_siteid
						LIMIT 
							1"; 
		$ret_exists_news = $db->query($sql_exists_news);
	 }	
	if($customer_in_mailing_list==1)
	{
		if ($db->num_rows($ret_exists_news))
		{ 
			$row_exists_news = $db->fetch_array($ret_exists_news);
			//if(count($customer_ids_arr) or $customer_in_mailing_list==1)
			if($customer_in_mailing_list==1)
			{
				$update_array =array();
				$update_array['news_title']	    = add_slash($row_cust['customer_title']);
				$update_array['news_custname']	= add_slash($row_cust['customer_fname']).' '.add_slash($row_cust['customer_mname']).' '.add_slash($row_cust['customer_surname']);
				$update_array['news_custemail']	= add_slash($row_cust['customer_email_7503']);
				$update_array['news_custphone']	= add_slash($row_cust['customer_phone']);
				$update_array['sites_site_id']	= $ecom_siteid;
				$update_array['customer_id']	= $row_cust['customer_id'];
				$update_array['news_join_date']	= 'curdate()';
				$db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
	
				$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']."";
				$db->query($sql_del_map);
				/*Start  the customer group from news letter*/
				if(count($customer_ids_arr))
				{
					for($i=0;$i<count($customer_ids_arr);$i++)
					{
						$insert_array = array();
						$insert_array['customer_id'] = $row_exists_news['news_customer_id'];
						$insert_array['custgroup_id'] = $customer_ids_arr[$i];
						$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
					}
				}	
			}
			else
			{
				$sql_del = "DELETE FROM newsletter_customers 
									WHERE news_customer_id=".$row_exists_news['news_customer_id']." 
										  AND sites_site_id = $ecom_siteid";
				$db->query($sql_del);
				$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map 
										WHERE customer_id=".$row_exists_news['news_customer_id']."";
				$db->query($sql_del_map);
			}
		}
		else
		{ 
			/*if ($db->num_rows($ret_exists_news))
			{
				$row_exists_news = $db->fetch_array($ret_exists_news);
				if(count($customer_ids_arr))
				{
					 $update_array =array();
					$update_array['news_title']		= add_slash($row_cust['customer_title']);
					$update_array['news_custname']	= add_slash($row_cust['customer_fname']).' '.add_slash($row_cust['customer_mname']).' '.add_slash($row_cust['customer_surname']);
					$update_array['news_custemail']	= add_slash($row_cust['customer_email_7503']);
					$update_array['news_custphone']	=  add_slash($row_cust['customer_phone']);
					$update_array['sites_site_id']	= $ecom_siteid;
					$update_array['customer_id']	= $row_cust['customer_id'];
					$update_array['news_join_date']	= 'curdate()';
					$db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
	
					$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map 
													WHERE customer_id=".$row_exists_news['news_customer_id']."";
					$db->query($sql_del_map);
					
					//Start  the customer group from nes letter
					for($i=0;$i<count($customer_ids_arr);$i++)
					{
						$insert_array = array();
						$insert_array['customer_id']  = $row_exists_news['news_customer_id'];
						$insert_array['custgroup_id'] = $customer_ids_arr[$i];
						$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
					}
				}
				else
				{
					$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$row_exists_news['news_customer_id']." AND sites_site_id = $ecom_siteid";
					$db->query($sql_del);
					$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']."";
					$db->query($sql_del_map);
				}
			}
			else
			{ */ 
				$insert_array = array();
				$insert_array['news_title']	= add_slash($row_cust['customer_title']);
				$insert_array['news_custname']	= add_slash($row_cust['customer_fname']).' '.add_slash($row_cust['customer_mname']).' '.add_slash($row_cust['customer_surname']);
				$insert_array['news_custemail']	= add_slash($row_cust['customer_email_7503']);
				$insert_array['news_custphone']	= add_slash($row_cust['customer_phone']);
				$insert_array['sites_site_id']	= $ecom_siteid;
				$insert_array['news_join_date']	= 'curdate()';
				$insert_array['customer_id']	= $row_cust['customer_id'];
				$db->insert_from_array($insert_array, 'newsletter_customers');
				$news_id						= $db->insert_id();
				if(count($customer_ids_arr))
				{
					for($i=0;$i<count($customer_ids_arr);$i++)
					{
						$insert_array = array();
						$insert_array['customer_id'] = $news_id;
						$insert_array['custgroup_id'] = $customer_ids_arr[$i];
						$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
					}
			   }
			//}
		}
	
	
	
	}
	else // case if receive newsletter is not ticked
	{
		if ($db->num_rows($ret_exists_news))
		{ 
			$row_exists_news = $db->fetch_array($ret_exists_news);
			// Deleting from customer_newsletter_group_customers_map table
			$sql_del_map = "DELETE FROM 
										customer_newsletter_group_customers_map 
									WHERE 
										customer_id=".$row_exists_news['news_customer_id']."";
			$db->query($sql_del_map);
			// Delete from newsletter customers
			$sql_del = "DELETE FROM newsletter_customers 
							WHERE 
								news_customer_id = ".$row_exists_news['news_customer_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$db->query($sql_del);					
			
		}	
	}
	$alert = 'Details Saved successfully';

	show_newsletter_group_list($_REQUEST['customer_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'assign_products')
{// to asign the categories to the group
	$group_id = $_REQUEST['customer_id'];
	{
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{ 
		
			$sql_assigned_products = "SELECT products_product_id FROM customer_fav_products WHERE customer_customer_id =".$_REQUEST['customer_id']." AND sites_site_id=".$ecom_siteid;
			$res_assigned_products = $db->query($sql_assigned_products);
			$assigned_products_arr = array();
			while($assigned_products = $db->fetch_array($res_assigned_products)){
			$assigned_products_arr[]= $assigned_products['products_product_id'];
		}
			$products_arr = explode("~",$_REQUEST['product_ids']);
			for($i=0;$i<count($products_arr);$i++)
			{
				if(trim($products_arr[$i]) && !in_array($products_arr[$i],$assigned_products_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['customer_customer_id']=$_REQUEST['customer_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'customer_fav_products');
				}	
			}
			$alert = 'Favourite products sucessfully assigned Products'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=customer_search&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=edit&customer_id=<?=$_REQUEST['customer_id']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_search_compname=<?php echo $_REQUEST['pass_search_compname']?>&pass_search_email=<?php echo $_REQUEST['pass_search_email']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>&curtab=products_tab_td" onclick="show_processing()">Go Back to the Edit  this Customer</a><br /><br />
			<?
}
else if($_REQUEST['fpurpose'] == 'assign_categories'){// to asign the categories to the group

	
	$group_id = $_REQUEST['customer_id'];
	{
		
		if ($_REQUEST['category_ids'] == '')
		{
			$alert = 'Sorry Categories not selected';
		}
		else
		{ 
		
			$sql_assigned_categories = "SELECT categories_categories_id FROM customer_fav_categories WHERE customer_customer_id =".$_REQUEST['customer_id']." AND sites_site_id=".$ecom_siteid;
			$res_assigned_categories = $db->query($sql_assigned_categories);
			$assigned_categories_arr = array();
			while($assigned_categories = $db->fetch_array($res_assigned_categories)){
			$assigned_categories_arr[]= $assigned_categories['categories_categories_id'];
		}
			$categories_arr = explode("~",$_REQUEST['category_ids']);
			for($i=0;$i<count($categories_arr);$i++)
			{
				if(trim($categories_arr[$i]) && !in_array($categories_arr[$i],$assigned_categories_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['customer_customer_id']=$_REQUEST['customer_id'];
					$insert_array['categories_categories_id']=$categories_arr[$i];
					$db->insert_from_array($insert_array, 'customer_fav_categories');
				}	
			}
			$alert = 'Favourite categories sucessfully assigned to Customer'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=customer_search&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=edit&customer_id=<?=$_REQUEST['customer_id']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_search_compname=<?php echo $_REQUEST['pass_search_compname']?>&pass_search_email=<?php echo $_REQUEST['pass_search_email']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>&curtab=category_tab_td" onclick="show_processing()">Go Back to the Edit  this Customer</a><br /><br />
			<?
}
elseif($_REQUEST['fpurpose'] == 'changestat_product_ajax'){ // To Change the status of the selected Product assigned to the Page group
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the page group
				$sql_chstat = "UPDATE customer_fav_products SET product_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Favourite Product(s) assigned to the Customer'; 
		}	
		show_product_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to page Groups using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Products  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from page groups
					$sql_del = "DELETE FROM customer_fav_products WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Selected Favourite Products Successfully Removed from the Customer'; 
		}	
show_product_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='delete_category_ajax') // section used for delete of Product assigned to page Groups using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Categories  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from page groups
					$sql_del = "DELETE FROM customer_fav_categories WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Selected Favourite Categories Successfully Removed from the Customer'; 
		}	
show_favcategory_list($_REQUEST['customer_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='list_state') // show state list
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_search/ajax/customer_ajax_functions.php');
		
		show_display_state_list($_REQUEST['country_id'],$_REQUEST['state_id']);
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['customer_title'],$_REQUEST['customer_fname'],$_REQUEST['customer_email'],$_REQUEST['customer_pwd']);
		$fieldDescription = array('Title','First Name','Email','Password');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM customers WHERE customer_email_7503 = '".add_slash($_REQUEST['customer_email'])."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Email Already exists '; 
		if($_POST['customer_discount']>99)  
			 $alert = 'Customer Discount Should below 100% ';    
		if($_POST['customer_affiliate_commission']>99)  
			 $alert = 'Customer Affiliate Commission Should below 100% ';	 
	
			
		if(!$alert){ // to check the validations for the mandatory feilds in the dynamic form
			$sql_dyn = "SELECT section_id FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
			activate = 1  AND section_type = 'register' ORDER BY sort_no ";
			$ret_dyn = $db->query($sql_dyn);
			if ($db->num_rows($ret_dyn))
				{
				while ($row_dyn = $db->fetch_array($ret_dyn)){
				
				$sql_elem = "SELECT element_name,error_msg FROM elements WHERE sites_site_id=$ecom_siteid AND 
				element_sections_section_id =".$row_dyn['section_id']." AND mandatory='Y' ORDER BY sort_no ";
					$ret_elem = $db->query($sql_elem);
					if ($db->num_rows($ret_elem)){
						while($row_elem = $db->fetch_array($ret_elem)){
						$mandatory_feildRquired = $row_elem['element_name'];
						$FieldRequired_dyn[] = $_REQUEST[$mandatory_feildRquired];
						$FieldDescription_dyn[] =  $row_elem['error_msg'];
						}
					}
				}
			}
		}
				
		if(is_array($FieldRequired_dyn) && is_array($FieldDescription_dyn) && (count($FieldRequired_dyn) == count($FieldDescription_dyn))){
	
		$fieldRequired = $FieldRequired_dyn;
		$fieldDescription = $FieldDescription_dyn;
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($FieldRequired_dyn,$FieldDescription_dyn, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		}
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['customer_accounttype'] 			=add_slash($_REQUEST['customer_accounttype']);
			$insert_array['customer_activated'] 			=$_REQUEST['customer_activated'];
			$insert_array['customer_title']     			=$_REQUEST['customer_title'];
			$insert_array['customer_fname']					=add_slash($_REQUEST['customer_fname']);
			$insert_array['customer_mname']					=add_slash($_REQUEST['customer_mname']);
			$insert_array['customer_surname']				=add_slash($_REQUEST['customer_surname']);
			$insert_array['customer_buildingname']			=add_slash($_REQUEST['customer_buildingname']);
			$insert_array['customer_streetname']			=add_slash($_REQUEST['customer_streetname']);
			$insert_array['customer_towncity']				=add_slash($_REQUEST['customer_towncity']);
			/*if($_REQUEST['customer_statecounty']!=-1)
					{
						$insert_array['customer_statecounty']						        =$_REQUEST['customer_statecounty'];
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
								$insert_array['customer_statecounty']						        =$row_checkstate['state_id'];
							}
							else
							{   $insert_st_array												=array();
								$insert_st_array['sites_site_id']									=$ecom_siteid;
								$insert_st_array['general_settings_site_country_country_id']	 	=$_REQUEST['country_id'];
								$insert_st_array['state_name']										=add_slash($_REQUEST['other_state']);
								$insert_st_array['state_hide']										=1;
								$db->insert_from_array($insert_st_array, 'general_settings_site_state');
								$insert_id = $db->insert_id();
								$insert_array['customer_statecounty']										=$insert_id;
							}
						}
					}*/
			$insert_array['customer_statecounty']			= add_slash($_REQUEST['customer_statecounty']);
			$insert_array['customer_phone']					= add_slash($_REQUEST['customer_phone']);
			$insert_array['customer_fax']					= add_slash($_REQUEST['customer_fax']);
			$insert_array['customer_mobile']				= add_slash($_REQUEST['customer_mobile']);
			$insert_array['customer_postcode']				= add_slash($_REQUEST['customer_postcode']);
			$insert_array['country_id']						= $_REQUEST['country_id'];
			$insert_array['customer_email_7503']			= add_slash($_REQUEST['customer_email']);
			//$insert_array['customer_pwd_9501']			=base64_encode(add_slash($_REQUEST['customer_pwd']));
			if(trim($_REQUEST['customer_pwd'])!='')
			{
			$insert_array['customer_pwd_9501']				=md5(add_slash($_REQUEST['customer_pwd']));
			}
			$insert_array['customer_bonus']					= add_slash($_REQUEST['customer_bonus']);
			$insert_array['customer_discount']				= add_slash($_REQUEST['customer_discount']);
			$insert_array['customer_allow_product_discount']= $_REQUEST['customer_allow_product_discount'];
			$insert_array['customer_use_bonus_points']		= $_REQUEST['customer_use_bonus_points'];
			$insert_array['customer_referred_by']			= add_slash($_REQUEST['customer_referred_by']);
			$insert_array['customer_addedon']				= 'curdate()';
			$insert_array['customer_anaffiliate']			= $_REQUEST['customer_anaffiliate'];
			$insert_array['customer_approved_affiliate']	= $_REQUEST['customer_approved_affiliate'];
			if($_REQUEST['customer_approved_affiliate']==1)
			{
				$insert_array['customer_approved_affiliate_on']	='curdate()';
			}	
			$insert_array['customer_affiliate_commission']	=add_slash($_REQUEST['customer_affiliate_commission']);
			$insert_array['customer_affiliate_taxid']		=$_REQUEST['customer_affiliate_taxid'];
			$insert_array['shop_id']						=$_REQUEST['shop_id'];
			$insert_array['customer_hide']					=$_REQUEST['customer_hide'];
			
			$insert_array['customer_compname']						= add_slash($_REQUEST['customer_compname']);
			$insert_array['customer_comptype']						= $_REQUEST['comptype_id'];
			$insert_array['customer_compregno']						= $_REQUEST['customer_compregno'];
			$insert_array['customer_compvatregno']					= $_REQUEST['customer_compvatregno'];
			$insert_array['customer_prod_disc_newsletter_receive']	= ($_REQUEST['customer_prod_disc_newsletter_receive']==1)?'Y':'N';
			$insert_array['customer_in_mailing_list']				= ($_REQUEST['customer_in_mailing_list']==1)?1:0;
			
			$insert_array['sites_site_id']					=$ecom_siteid;
			$db->insert_from_array($insert_array, 'customers');
			$insert_id = $db->insert_id();
			
			// Check whether the email id already exists in the newsletter table
						$sql_exists_news = "SELECT news_customer_id 
										FROM 
											newsletter_customers 
										WHERE 
											news_custemail ='".add_slash($_REQUEST['customer_email'])."' 
											AND sites_site_id = $ecom_siteid
										LIMIT 
											1";
						$ret_exists_news = $db->query($sql_exists_news);
						
							if ($db->num_rows($ret_exists_news))
							{
								$row_exists_news = $db->fetch_array($ret_exists_news);
								if($_REQUEST['customer_in_mailing_list']==1)
								{
									 if(count($_REQUEST['chk_group']))
									 {
										$update_array =array();
										$update_array['news_title']	= add_slash($_REQUEST['customer_title']);
										$update_array['news_custname']	= add_slash($_REQUEST['customer_fname']).' '.add_slash($_REQUEST['customer_mname']).' '.add_slash($_REQUEST['customer_surname']);
										$update_array['news_custemail']	= add_slash($_REQUEST['customer_email']);
										$update_array['news_custphone']	=  add_slash($_REQUEST['customer_phone']);
										$update_array['sites_site_id']	= $ecom_siteid;
										$update_array['customer_id']	= $insert_id;
										$update_array['news_join_date']	= 'curdate()';
										$db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
		
										$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']."";
										$db->query($sql_del_map);
									
										/*Start  the customer group from nes letter*/
										foreach($_REQUEST['chk_group'] as $key =>$val)
										{
											$insert_array = array();
											$insert_array['customer_id'] = $row_exists_news['news_customer_id'];
											$insert_array['custgroup_id'] = $val;
											$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
										}
									}
									else
									{
										$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']."";
										$db->query($sql_del_map);
										/*$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$row_exists_news['news_customer_id']." AND sites_site_id = $ecom_siteid";
										$db->query($sql_del);*/
									}	
							}
							else
							{
								$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']."";
								$db->query($sql_del_map);
								$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$row_exists_news['news_customer_id']." AND sites_site_id = $ecom_siteid";
								$db->query($sql_del);
							}
						}
						else
						{
							if(count($_REQUEST['chk_group']) or $_REQUEST['customer_in_mailing_list']==1)
							{
								if($_REQUEST['customer_in_mailing_list']==1)
								{
									$insert_array = array();
									$insert_array['news_title']	= add_slash($_REQUEST['customer_title']);
									$insert_array['news_custname']	= add_slash($_REQUEST['customer_fname']).' '.add_slash($_REQUEST['customer_mname']).' '.add_slash($_REQUEST['customer_surname']);
									$insert_array['news_custemail']	= add_slash($_REQUEST['customer_email']);
									$insert_array['news_custphone']	= add_slash($_REQUEST['customer_phone']);
									$insert_array['sites_site_id']	= $ecom_siteid;
									$insert_array['news_join_date']	= 'curdate()';
									$insert_array['customer_id']	= $insert_id;
									$db->insert_from_array($insert_array, 'newsletter_customers');
									$news_id						= $db->insert_id();
								}
								if(count($_REQUEST['chk_group']) and $news_id)
								{
									foreach($_REQUEST['chk_group'] as $key =>$val)
									{
										$insert_array = array();
										$insert_array['customer_id'] =  $news_id;
										$insert_array['custgroup_id'] = $val;
										$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
									}
								}
							}
						}
			
			//#Customer groups mapping section for news letters
		    /*if(count($_REQUEST['chk_group'])>0)
			{	
				foreach($_REQUEST['chk_group'] as $v)
				{
					$insert_array=array();
					$insert_array['custgroup_id']=$v;
					$insert_array['customer_id']=$insert_id;
					$db->insert_from_array($insert_array, 'customer_newsletter_group_customers_map');				
				}
			}	*/
			// Calling the function to save the details of dynamic fields
			save_registration_additional_fields($insert_id);
			
			$alert .= '<br><span class="redtext"><b>Customer added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=customer_search&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=edit&customer_id=<?=$insert_id?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_search_compname=<?php echo $_REQUEST['pass_search_compname']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['start']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>&pass_search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = '<span class=""><strong>Error!!</strong> '.$alert;
			$alert .= '</span>';
			include("includes/customer_search/add_customer.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert='';
		$fieldRequired = array($_REQUEST['customer_title'],$_REQUEST['customer_fname'],$_REQUEST['customer_email']);
		$fieldDescription = array('Title','First Name','Email');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM customers 
							WHERE customer_email_7503 = '".add_slash($_REQUEST['customer_email'])."' 
								  AND sites_site_id=$ecom_siteid AND customer_id<>".$_REQUEST['customer_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Email Already exists ';    
		if($_POST['customer_discount']>99)  
			 $alert = 'Customer Discount Should below 100% ';    
		if($_POST['customer_affiliate_commission']>99)  
			 $alert = 'Customer Affiliate Commission Should below 100% ';	 
		if(!$alert) 
		{
			// Check whether customer_in_mailing_list is ticked for current customer
			$sql_check = "SELECT customer_in_mailing_list 
							FROM 
								customers 
							WHERE 
								customer_id = ".$_REQUEST['customer_id']." 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
			{
				$row_check = $db->fetch_array($ret_check);
				$customer_in_mailing_list = $row_check['customer_in_mailing_list'];
			}
			/*if($customer_in_mailing_list==1)
			{
			}
			else // case if newsletter need not receive .. so delete the newsletter customer entry
			{
				$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$news_customer_id."";
				$db->query($sql_del_map);
				$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$news_customer_id." AND sites_site_id = $ecom_siteid";
				$db->query($sql_del);
			}*/
			if($customer_in_mailing_list==1)
			{	
				// Check Whether Email ID exists in the  NEwsletter Table			
				$sql_newscheck = "SELECT news_customer_id, customer_id
									  FROM newsletter_customers 
										 WHERE news_custemail = '".add_slash($_REQUEST['customer_email'])."' 
											 AND sites_site_id=$ecom_siteid";
				$res_newscheck = $db->query($sql_newscheck);
				$row_newscheck = $db->fetch_array($res_newscheck);		
				if($db->num_rows($res_newscheck) > 0)	
				{
					$news_customer_id = $row_newscheck['news_customer_id']; //new News Customer ID
					// Check Whether is ther any Entry in the NEwsletter Table
					$sqlcheck = "SELECT news_customer_id 
									  FROM newsletter_customers 
											WHERE customer_id='".$_REQUEST['customer_id']."'
											AND sites_site_id=$ecom_siteid";
					$rescheck = $db->query($sqlcheck);
					$rowcheck = $db->fetch_array($rescheck);
					$numcheck = $db->num_rows($rescheck);
					if($numcheck > 0) 
					{
						$newletter_id = $rowcheck['news_customer_id'];  // Old news Cust Id
						$update_array = array();
						$update_array['news_title']     	= $_REQUEST['customer_title'];
						$update_array['news_custname']		= add_slash($_REQUEST['customer_fname']);
						$update_array['news_custemail']		= add_slash($_REQUEST['customer_email']);
						$update_array['news_custphone']		= add_slash($_REQUEST['customer_phone']);	
						$update_array['customer_id']		= add_slash($_REQUEST['customer_id']);	
						$db->update_from_array($update_array, 'newsletter_customers', 'news_customer_id', $newletter_id);
						if($news_customer_id!=$newletter_id) 
						{
							$delsql = "DELETE FROM newsletter_customers 
												WHERE news_customer_id='".$news_customer_id."'";
							$delres = $db->query($delsql);
							
							$delmapsql = "DELETE FROM customer_newsletter_group_customers_map 
												 WHERE customer_id='".$news_customer_id."'";
							$delmapres = $db->query($delmapsql);
						}
					}
					else // if an entry exists in newsletter_customer table with the email id same as that of current customer but customer id not same as that of current customer
					{
						// So update the entry so that the details match that of the current customer
						$update_array = array();
						$update_array['news_title']     				= $_REQUEST['customer_title'];
						$update_array['news_custname']					= add_slash($_REQUEST['customer_fname']);
						$update_array['news_custemail']					= add_slash($_REQUEST['customer_email']);
						$update_array['news_custphone']					= add_slash($_REQUEST['customer_phone']);	
						$update_array['customer_id']					= add_slash($_REQUEST['customer_id']);	
						$db->update_from_array($update_array, 'newsletter_customers', 'news_customer_id', $news_customer_id);
					}
						
				}	//IF ENDS	
				else 
				{  // Means If there is no email ID exista in teh NEwsletter Table
					
					// Check Whether is ther any Entry in the NEwsletter Table
					$sqlcheck = "SELECT news_customer_id 
											  FROM newsletter_customers 
												WHERE customer_id='".$_REQUEST['customer_id']."'
													AND sites_site_id=$ecom_siteid";
					$rescheck = $db->query($sqlcheck);
					$rowcheck = $db->fetch_array($rescheck);
					if($db->num_rows($rescheck) > 0) 
					{
						$newletter_id = $rowcheck['news_customer_id'];  // Old news Cust Id
						
						$update_array = array();
						$update_array['news_title']     				=$_REQUEST['customer_title'];
						$update_array['news_custname']					=add_slash($_REQUEST['customer_fname']);
						$update_array['news_custemail']					=add_slash($_REQUEST['customer_email']);
						$update_array['news_custphone']					=add_slash($_REQUEST['customer_phone']);	
						$update_array['customer_id']					=add_slash($_REQUEST['customer_id']);	
						$db->update_from_array($update_array, 'newsletter_customers', 'news_customer_id', $newletter_id);
					}	
				}
			}
			else // case if mailing list is unticked
			{
				// Check Whether Email ID exists in the  NEwsletter Table with current email id	
				$sql_newscheck = "SELECT news_customer_id, customer_id
									  FROM newsletter_customers 
										 WHERE news_custemail = '".add_slash($_REQUEST['customer_email'])."' 
											 AND sites_site_id=$ecom_siteid";
				$res_newscheck = $db->query($sql_newscheck);
				$row_newscheck = $db->fetch_array($res_newscheck);		
				if($db->num_rows($res_newscheck) > 0)	
				{
					$news_customer_id = $row_newscheck['news_customer_id']; //new News Customer ID
					$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$news_customer_id."";
					$db->query($sql_del_map);
					$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$news_customer_id." AND sites_site_id = $ecom_siteid";
					$db->query($sql_del);
				}		
				
				// Check Whether is ther any Entry in the NEwsletter Table with current customer id
				$sqlcheck = "SELECT news_customer_id 
								  FROM newsletter_customers 
										WHERE customer_id='".$_REQUEST['customer_id']."'
										AND sites_site_id=$ecom_siteid";
				$rescheck = $db->query($sqlcheck);
				$rowcheck = $db->fetch_array($rescheck);
				$numcheck = $db->num_rows($rescheck);
				if($numcheck > 0) 
				{
					$newletter_id = $rowcheck['news_customer_id'];  // Old news Cust Id
					$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$newletter_id."";
					$db->query($sql_del_map);
					$sql_del = "DELETE FROM newsletter_customers WHERE news_customer_id=".$newletter_id." AND sites_site_id = $ecom_siteid";
					$db->query($sql_del);
				}
			}
		}
		
		if(!$alert){ // to check the validations for the mandatory feilds in the dynamic form
		
			$sql_dyn = "SELECT section_id FROM element_sections WHERE sites_site_id=$ecom_siteid AND 
			activate = 1  AND section_type = 'register' ORDER BY sort_no";
			$ret_dyn = $db->query($sql_dyn);
			if ($db->num_rows($ret_dyn))
				{
				while ($row_dyn = $db->fetch_array($ret_dyn)){
				$sql_elem = "SELECT  element_name,error_msg ,crv.id,crv.reg_label,crv.reg_val  
				FROM elements e LEFT JOIN customer_registration_values crv ON (crv.elements_element_id=e.element_id)
			AND customers_customer_id=".$_REQUEST['customer_id']."
			 WHERE e.sites_site_id=$ecom_siteid AND 
			e.element_sections_section_id =".$row_dyn['section_id']." AND mandatory ='Y'   ORDER BY sort_no";
				//	$sql_elem = "SELECT element_name,error_msg FROM elements WHERE sites_site_id=$ecom_siteid AND 
				//	element_sections_section_id =".$row_dyn['section_id']." AND mandatory='Y' ORDER BY sort_no";
					$ret_elem = $db->query($sql_elem);
					if ($db->num_rows($ret_elem)){
						while($row_elem = $db->fetch_array($ret_elem)){
							if($row_elem['reg_val']=='')
								$mandatory_feildRquired = 'New_'.$row_elem['element_name']; // to check whether it is a newly added feild
							else
								$mandatory_feildRquired = $row_elem['element_name'];
						$FieldRequired_dyn[] = $_REQUEST[$mandatory_feildRquired];
						$FieldDescription_dyn[] =  $row_elem['error_msg'];
						}
					}
				}
			}
		}

		
		/*
		if(is_array($FieldRequired_dyn) && is_array($FieldDescription_dyn) && (count($FieldRequired_dyn) == count($FieldDescription_dyn))){
	
		$fieldRequired = $FieldRequired_dyn;
		$fieldDescription = $FieldDescription_dyn;
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($FieldRequired_dyn,$FieldDescription_dyn, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		} */
		if(!$alert) {
                        // Get the last statement date
                        $sql_last = "SELECT customer_payonaccount_laststatementdate 
                                        FROM 
                                            customers 
                                        WHERE 
                                            customer_id=".$_REQUEST['customer_id']." 
                                            AND sites_site_id = $ecom_siteid 
                                        LIMIT 
                                            1";
                        $ret_last = $db->query($sql_last);
                        if($db->num_rows($ret_last))
                        {
                            $row_last = $db->fetch_array($ret_last);
                        }
			$update_array = array();
			$update_array['customer_accounttype'] 			=$_REQUEST['customer_accounttype'];
			$update_array['customer_activated'] 			=$_REQUEST['customer_activated'];
			$update_array['customer_title']     			=$_REQUEST['customer_title'];
			$update_array['customer_fname']					=add_slash($_REQUEST['customer_fname']);
			$update_array['customer_mname']					=add_slash($_REQUEST['customer_mname']);
			$update_array['customer_surname']				=add_slash($_REQUEST['customer_surname']);
			$update_array['customer_buildingname']			=add_slash($_REQUEST['customer_buildingname']);
			$update_array['customer_streetname']			=add_slash($_REQUEST['customer_streetname']);
			$update_array['customer_towncity']				=add_slash($_REQUEST['customer_towncity']);
			//$update_array['customer_statecounty']			=$_REQUEST['customer_statecounty'];
			$update_array['customer_phone']					=add_slash($_REQUEST['customer_phone']);
			$update_array['customer_fax']					=add_slash($_REQUEST['customer_fax']);
			$update_array['customer_mobile']				=add_slash($_REQUEST['customer_mobile']);
			$update_array['customer_postcode']				=add_slash($_REQUEST['customer_postcode']);
			$update_array['country_id']						=$_REQUEST['country_id'];
			
			$update_array['customer_statecounty']			= add_slash($_REQUEST['customer_statecounty']);
			$update_array['customer_email_7503']			= add_slash($_REQUEST['customer_email']);
			if(trim($_REQUEST['customer_pwd'])!='')
			{ 
				//$update_array['customer_pwd_9501']			=base64_encode(add_slash($_REQUEST['customer_pwd']));
				$update_array['customer_pwd_9501']				= md5(add_slash($_REQUEST['customer_pwd']));
			}	 
			$update_array['customer_bonus']							=add_slash($_REQUEST['customer_bonus']);
			$update_array['customer_discount']						=add_slash($_REQUEST['customer_discount']);
			$update_array['customer_allow_product_discount']	=$_REQUEST['customer_allow_product_discount'];
			$update_array['customer_use_bonus_points']			=$_REQUEST['customer_use_bonus_points'];
			$update_array['customer_referred_by']					=add_slash($_REQUEST['customer_referred_by']);
			$update_array['customer_anaffiliate']						=$_REQUEST['customer_anaffiliate'];
			$update_array['customer_approved_affiliate']			=$_REQUEST['customer_approved_affiliate'];
			if($_REQUEST['customer_approved_affiliate']==1)
			{
				$sql_cus = "SELECT customer_approved_affiliate FROM customers WHERE customer_id=".$_REQUEST['customer_id'];
				$res_cus = $db->query($sql_cus);
				$row_cus = $db->fetch_array($res_cus);
				if($row_cus['customer_approved_affiliate']==0)
				{
					$update_array['customer_approved_affiliate_on']	='curdate()';
				}	
			}	
			$update_array['customer_affiliate_commission']			= add_slash($_REQUEST['customer_affiliate_commission']);
			$update_array['customer_affiliate_taxid']				= $_REQUEST['customer_affiliate_taxid'];
			$update_array['shop_id']								= $_REQUEST['shop_id'];
			$update_array['customer_hide']							= $_REQUEST['customer_hide'];
			$update_array['customer_compname']						= add_slash($_REQUEST['customer_compname']);
			$update_array['customer_comptype']						= $_REQUEST['comptype_id'];
			$update_array['customer_compregno']						= $_REQUEST['customer_compregno'];
			$update_array['customer_compvatregno']					= $_REQUEST['customer_compvatregno'];
			
			$update_array['customer_payonaccount_status']		 	= add_slash($_REQUEST['cbo_customer_payonaccount_status']);
			$update_array['customer_payonaccount_maxlimit']		 	= add_slash($_REQUEST['customer_payonaccount_maxlimit']);
			$update_array['customer_payonaccount_rejectreason']	 	= add_slash($_REQUEST['customer_payonaccount_rejectreason']);
			$update_array['customer_payonaccount_billcycle_day'] 	        = add_slash($_REQUEST['customer_payonaccount_billcycle_day']);
                        $update_array['customer_payonaccount_billcycle_month_duration'] = add_slash($_REQUEST['customer_payonaccount_billcycle_month_duration']);
                        if($_REQUEST['cbo_customer_payonaccount_status']=='ACTIVE')// if the payonaccount status is Active and also if laststatement date is 0000-00-00 then set the last statement date to todays date
                        {
                            if($row_last['customer_payonaccount_laststatementdate']=='0000-00-00')
                            {
                                $update_array['customer_payonaccount_laststatementdate'] = 'curdate()';
                            }
                        }    
			$update_array['customer_prod_disc_newsletter_receive']	        = ($_REQUEST['customer_prod_disc_newsletter_receive']==1)?'Y':'N';
			$db->update_from_array($update_array, 'customers', 'customer_id', $_REQUEST['customer_id']);
			

			
			
			/*//#Customer groups mapping section
			$sql_del = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$_REQUEST['customer_id'];
			$db->query($sql_del);
		    if(count($_REQUEST['chk_group'])>0)
			{	
				foreach($_REQUEST['chk_group'] as $v)
				{
					$insert_array=array();
					$insert_array['custgroup_id']=$v;
					$insert_array['customer_id']=$_REQUEST['customer_id'];
					$db->insert_from_array($insert_array, 'customer_newsletter_group_customers_map');				
				}
			}*/
			//#Customer categories mapping section
			//commneted for the fav category issue on the update button
			/*
			$sql_delcat = "DELETE FROM customer_fav_categories WHERE customer_customer_id=".$_REQUEST['customer_id'];
			$db->query($sql_delcat);
		    if(count($_REQUEST['chk_cat'])>0)
			{	
				foreach($_REQUEST['chk_cat'] as $v)
				{
					$insert_array=array();
					
					$insert_array['categories_categories_id']=$v;
					$insert_array['customer_customer_id']=$_REQUEST['customer_id'];
					$insert_array['sites_site_id']=$ecom_siteid;
					$db->insert_from_array($insert_array, 'customer_fav_categories');				
				}
			}
			*/ 		
			// Calling the function to update the additional field values from the customer edit page
			update_customer_additional_fields($_REQUEST['customer_id']);
			
			$alert .= '<br><span class="redtext"><b>Customer Details Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=customer_search&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Customer Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=edit&customer_id=<?=$_REQUEST['customer_id']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_search_compname=<?php echo $_REQUEST['pass_search_compname']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>&pass_search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=customer_search&fpurpose=add&search_name=<?=$_REQUEST['pass_search_name']?>&search_compname=<?php echo $_REQUEST['pass_search_compname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['start']?>&cbo_dept=<?=$_REQUEST['pass_cbo_dept']?>&search_email=<?php echo $_REQUEST['pass_search_email']?>&corporation_id=<?=$_REQUEST['corporation_id']?>&customer_payonaccount_status=<?=$_REQUEST['customer_payonaccount_status']?>&cbo_dept=<?=$_REQUEST['cbo_dept']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font >Error! '.$alert;
			$alert .= '</font></center>';
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include ('includes/customer_search/ajax/customer_ajax_functions.php');
			include("includes/customer_search/edit_customer.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='settingstomany'){ // apply discount and tax setings to more than one or AlL products by category
		include ('includes/customer_search/apply_settingstomany.php');
}
elseif($_REQUEST['fpurpose']=='save_settingstomany'){
		$update_array =array();
		if($_REQUEST['recievenewsletter_check']){
			$update_array['customer_prod_disc_newsletter_receive']			= ($_REQUEST['customer_prod_disc_newsletter_receive']==1)?'Y':'N';
		}
		if($_REQUEST['bonuspoint_check']){
					$update_array['customer_bonus']							=add_slash($_REQUEST['customer_bonus']);
        }
		if($_REQUEST['custdiscount_check']){
					$update_array['customer_discount']						=add_slash($_REQUEST['customer_discount']);
        }
		if($_REQUEST['allowproddiscount_check']){
					$update_array['customer_allow_product_discount']		=$_REQUEST['customer_allow_product_discount'];
		}
		if($_REQUEST['usebonuspoint_check'])
		{
					$update_array['customer_use_bonus_points']				=$_REQUEST['customer_use_bonus_points'];
		}
		if($_REQUEST['select_customers']=='All') { // set the values to all the products
			if(count($update_array))
				$db->update_from_array($update_array,'customers',array('sites_site_id'=>$ecom_siteid));
			$alert= "Customers Updated Successfully !!";
		}
		include ('includes/customer_search/apply_settingstomany.php');
}
elseif($_REQUEST['fpurpose']=='list_departments')
{
    include_once("../functions/functions.php");
    include_once('../session.php');
    include_once("../config.php");	
    if($_REQUEST['corporation_id'])
    {
            $sql_list_department ="SELECT department_id,department_name FROM customers_corporation_department WHERE sites_site_id=".$ecom_siteid." AND customers_corporation_corporation_id=".$_REQUEST['corporation_id'];
            $ret_list_department = $db->query($sql_list_department);
            $DepartmentArray[0] = "--All--";
            while(list($id,$DepartmentList) = $db->fetch_array($ret_list_department)) {
            $DepartmentArray[$id]=$DepartmentList;
            }
        echo "In Department&nbsp;&nbsp;&nbsp;&nbsp;";
        if(count($DepartmentArray)){
            echo generateselectbox('department_id',$DepartmentArray,$_REQUEST['department_id'],'','');
        }else{
            echo "No Department assigned";
            }
    }
    else
    {
    $DepartmentArray[0] = "--All--";
    echo "In Department&nbsp;&nbsp;&nbsp;&nbsp;";
    echo generateselectbox('department_id',$DepartmentArray,$_REQUEST['department_id'],'','');
    }
    //$corporation_ids_arr 		= explode('~',$_REQUEST['corporation_id']);
    //$corporation_id = $_REQUEST['corporation_id'];
    //$new_status		= $_REQUEST['ch_status'];
    //for($i=0;$i<count($corporation_ids_arr);$i++)
    //{
    //	$update_array							= array();
    //	$update_array['corporation_hide']		= $new_status;
    //	$corporation_hide 						= $corporation_ids_arr[$i];	
    //	$db->update_from_array($update_array,'customers_corporation',array('corporation_id'=>$corporation_id));
            
    //}
    //include("../includes/customer_corporation/list_customer_corporation.php");   
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
