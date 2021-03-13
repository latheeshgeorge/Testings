<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/customer_newsletter_group/list_cust_group.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$group_ids_arr 		= explode('~',$_REQUEST['group_ids']);
		$new_status		= $_REQUEST['ch_status'];
	
		for($i=0;$i<count($group_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['custgroup_active']	= $new_status;
			$group_id 					= $group_ids_arr[$i];	
			$db->update_from_array($update_array,'customer_newsletter_group',array('custgroup_id'=>$group_id));
			
		}
		$alert = 'Status changed successfully.';
		include ('../includes/customer_newsletter_group/list_cust_group.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Group not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM customer_newsletter_group WHERE custgroup_id=".$del_arr[$i];
					  $db->query($sql_del);
					   $sql_del = "DELETE FROM customer_newsletter_group_customers_map WHERE custgroup_id=".$del_arr[$i];
					   $db->query($sql_del);
					   $del_count++;					
					 
				}	
			}
			if($del_count>0)
			{
			 if($alert) $alert .="<br />";
					  $alert .= $del_count." Newsletter Group(s) Deleted"; //".$del_arr[$i]."
			}		  
		}
		include ('../includes/customer_newsletter_group/list_cust_group.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/customer_newsletter_group/add_cust_group.php");
}
else if($_REQUEST['fpurpose']=='edit')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/customer_newsletter_group/edit_cust_group.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['custgroup_name']);
		$fieldDescription = array('Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM customer_newsletter_group WHERE custgroup_name = '".trim(add_slash($_REQUEST['custgroup_name']))."' AND sites_site_id=$ecom_siteid LIMIT 1";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Group Name Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['custgroup_name']=trim(add_slash($_REQUEST['custgroup_name']));
			
			$insert_array['custgroup_active']=add_slash($_REQUEST['custgroup_active']);
			$insert_array['sites_site_id']=$ecom_siteid;
			$db->insert_from_array($insert_array, 'customer_newsletter_group');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>Newsletter Group added successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=cust_group&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_group&fpurpose=edit&custgroup_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_group&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
		}	
		else
		{
			$alert = 'Error!! '.$alert;
			$alert .= '<br>';
			include("includes/customer_newsletter_group/add_cust_group.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['custgroup_name']);
		$fieldDescription = array('Group Name');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM customer_newsletter_group WHERE custgroup_name = '".trim(add_slash($_REQUEST['custgroup_name']))."' AND sites_site_id=$ecom_siteid AND custgroup_id<>".$_REQUEST['custgroup_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Group Name Already exists '; 
		if($_REQUEST['custgroup_discount']>99) 
		    $alert = 'Discount Should be less than 100% '; 	
		if(!$alert) {
			$update_array = array();
			$update_array['custgroup_name']=trim(add_slash($_REQUEST['custgroup_name']));
			$update_array['custgroup_active']=add_slash($_REQUEST['custgroup_active']);
			$update_array['sites_site_id']=$ecom_siteid;
			$db->update_from_array($update_array, 'customer_newsletter_group', 'custgroup_id', $_REQUEST['custgroup_id']);
			$alert .= '<br><span class="redtext"><b>Group Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=cust_group&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_group&fpurpose=edit&custgroup_id=<?=$_REQUEST['custgroup_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=cust_group&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<Error! '.$alert;
			$alert .= '';
		?>
			<br />
			<?php
			include("includes/customer_newsletter_group/edit_cust_group.php");
		}
	}
}
elseif($_REQUEST['fpurpose']=='list_customer') // show customer list using ajax
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_newsletter_group/ajax/cust_group_ajax_functions.php');
		show_display_customer_group_list($_REQUEST['custgroup_id']);
}
elseif($_REQUEST['fpurpose']=='add_customer') // Assign customers to group
{
		
		include ('includes/customer_newsletter_group/list_selcustomer.php');
}	
elseif($_REQUEST['fpurpose']=='save_add_customer') // Assign customers to group
{
		foreach($_REQUEST['checkbox'] as $v)
		{
			  $sql_customers = "SELECT 
			  						customer_id,customer_email_7503,customer_fname,customer_mname,customer_surname,customer_phone,customer_title 
								FROM 
									customers 
								WHERE 
									sites_site_id 
								AND  
									customer_id=".$v;
				$ret_customers = $db->query($sql_customers);
				if($db->num_rows($ret_customers)>0)
				{
					$row_customers = $db->fetch_array($ret_customers);			
			//inserting to the news letter customers 
			      $sql_exists_news = "SELECT
				  							 news_customer_id 
										FROM 
											newsletter_customers 
										WHERE 
											news_custemail ='".add_slash($row_customers['customer_email_7503'])."' 
										AND 
											sites_site_id = $ecom_siteid
										LIMIT 
											1"; 
										
						 $ret_exists_news = $db->query($sql_exists_news);
						
							if ($db->num_rows($ret_exists_news))
							{
							$row_exists_news = $db->fetch_array($ret_exists_news);
							 	$update_array =array();
							 	$update_array['news_title']	= add_slash($row_customers['customer_title']);
								$update_array['news_custname']	= add_slash($row_customers['customer_fname']).' '.add_slash($row_customers['customer_mname']).' '.add_slash($row_customers['customer_surname']);
								$update_array['news_custemail']	= add_slash($row_customers['customer_email_7503']);
								$update_array['news_custphone']	=  add_slash($row_customers['customer_phone']);
								$update_array['sites_site_id']	= $ecom_siteid;
								$update_array['customer_id']	= $row_customers['customer_id'];
								$update_array['news_join_date']	= 'curdate()';
								$db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
								$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']." AND custgroup_id=".$_REQUEST['pass_custgroup_id'];
						        $db->query($sql_del_map);
								
							/*Start  the customer group from nes letter*/
									$insert_array = array();
									$insert_array['customer_id'] = $row_exists_news['news_customer_id'];
									$insert_array['custgroup_id'] = $_REQUEST['pass_custgroup_id'];
									
								$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
						}
						else
						{
			                       $sql_exists_news = "SELECT 
								   							news_customer_id 
														FROM 
															newsletter_customers 
														WHERE 
															customer_id ='".$row_customers['customer_id']."' 
														AND 
															sites_site_id = $ecom_siteid
														LIMIT 1"; 
												
								$ret_exists_news = $db->query($sql_exists_news);
								
									if ($db->num_rows($ret_exists_news))
									{
									$row_exists_news = $db->fetch_array($ret_exists_news);
									 $update_array =array();
										$update_array['news_title']	= add_slash($row_customers['customer_title']);
										$update_array['news_custname']	= add_slash($row_customers['customer_fname']).' '.add_slash($row_customers['customer_mname']).' '.add_slash($row_customers['customer_surname']);
										$update_array['news_custemail']	= add_slash($row_customers['customer_email_7503']);
										$update_array['news_custphone']	=  add_slash($row_customers['customer_phone']);
										$update_array['sites_site_id']	= $ecom_siteid;
										$update_array['customer_id']	= $row_customers['customer_id'];
										$update_array['news_join_date']	= 'curdate()';
										$db->update_from_array($update_array, 'newsletter_customers', array('news_customer_id' => $row_exists_news['news_customer_id'] , 'sites_site_id' => $ecom_siteid));
										$sql_del_map = "DELETE FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_exists_news['news_customer_id']." AND custgroup_id=".$_REQUEST['pass_custgroup_id'];
										$db->query($sql_del_map);
										
									/*Start  the customer group from nes letter*/
											$insert_array = array();
											$insert_array['customer_id'] = $row_exists_news['news_customer_id'];
											$insert_array['custgroup_id'] = $_REQUEST['pass_custgroup_id'];
										$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
						        }
						        else
								{  
									$insert_array = array();
									$insert_array['news_title']	= add_slash($row_customers['customer_title']);
									$insert_array['news_custname']	= add_slash($row_customers['customer_fname']).' '.add_slash($row_customers['customer_mname']).' '.add_slash($row_customers['customer_surname']);
									$insert_array['news_custemail']	= add_slash($row_customers['customer_email_7503']);
									$insert_array['news_custphone']	= add_slash($row_customers['customer_phone']);
									$insert_array['sites_site_id']	= $ecom_siteid;
									$insert_array['news_join_date']	= 'curdate()';
									$insert_array['customer_id']	= $row_customers['customer_id'];
									$db->insert_from_array($insert_array, 'newsletter_customers');
									$news_id						= $db->insert_id();
										$insert_array = array();
										$insert_array['customer_id'] =  $news_id;
										$insert_array['custgroup_id'] = $_REQUEST['pass_custgroup_id'];
									$db->insert_from_array($insert_array,'customer_newsletter_group_customers_map');
								}
							}
			//end
			}
			/*$insert_array=array();
			$insert_array['custgroup_id']=$_REQUEST['pass_custgroup_id'];
			$insert_array['from_newslettergroup']=1;
			$insert_array['customer_id']=$v;
			$db->insert_from_array($insert_array, 'customer_newsletter_group_customers_map');*/
			
		}
		$alert='Customer Assigned Successfullly';
		$alert = '<center><font color="red"><b>'.$alert;
		$alert .= '</b></font></center>';
		echo $alert;		
		?>
		<br /><a class="smalllink" href="home.php?request=cust_group&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&custgroup_id=<?=$_REQUEST['pass_custgroup_id']?>" onclick="show_processing()">Go Back to the Customer Group Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=cust_group&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['pass_custgroup_id']?>&search_name=<?=$_REQUEST['pass_searchname']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&start=<?=$_REQUEST['pass_start']?>&pg=<?=$_REQUEST['pass_pg']?>" onclick="show_processing()">Go Back to the Edit this Customer Group</a><br /><br />
<?			
}	
elseif($_REQUEST['fpurpose']=='unassign_customerdetails') // Unassign customers from group
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/customer_newsletter_group/ajax/cust_group_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Customer(s) not selected';
		}
		else
		{
			$ch_arr 	= explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($ch_arr);$i++)
			{
				$sel_cust_id = "SELECT nc.customer_id,news_customer_id FROM customer_newsletter_group_customers_map cmp,newsletter_customers nc WHERE map_id=".$ch_arr[$i]." AND cmp.customer_id=nc.news_customer_id AND nc.sites_site_id=$ecom_siteid";
				$ret_cust_id = $db->query($sel_cust_id);
				$row_cust_id = $db->fetch_array($ret_cust_id);
				if($row_cust_id['customer_id']!=0)
				{
				 	$sql_check_group = "SELECT count(custgroup_id) as cnt FROM customer_newsletter_group_customers_map WHERE customer_id=".$row_cust_id['news_customer_id']." AND map_id<>".$ch_arr[$i];
					$ret_check_group = $db->query($sql_check_group);
					$row_check_group = $db->fetch_array($ret_check_group);
					if($row_check_group['cnt']==0)
					{
						$sql_del = "DELETE FROM newsletter_customers WHERE customer_id=".$row_cust_id['customer_id']." AND sites_site_id=".$ecom_siteid;
						$db->query($sql_del);
					}
				}
				$sql_del = "DELETE FROM customer_newsletter_group_customers_map WHERE map_id=".$ch_arr[$i];
				$db->query($sql_del);
			}
			
			$alert = 'Customer(s) Unassigned Successfully';
		}	
		show_display_customer_group_list($_REQUEST['custgroup_id'],$alert);
}		
?>