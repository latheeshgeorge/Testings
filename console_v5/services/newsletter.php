<?php
if($_REQUEST['fpurpose']=='') 
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/list_newsletter.php");
}
elseif($_REQUEST['fpurpose']=='listcustomers')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/ajax/newsletter_ajax_functions.php");
	include("includes/newsletter/list_customers.php");
}
elseif($_REQUEST['fpurpose']=='listnewsgroups')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/list_newsgroups.php"); //list_newsgroups
}
elseif($_REQUEST['fpurpose']=='sendmail')
{
	// Getting the address to be kept as from address for the email from the order confirmation email template
	$sql_email = "SELECT lettertemplate_from  
					FROM 
						general_settings_site_letter_templates  
					WHERE 
						sites_site_id = $ecom_siteid 
						AND lettertemplate_letter_type='ORDER_CONFIRM_CUST' 
					LIMIT 
						1";
	$ret_email = $db->query($sql_email);
	if($db->num_rows($ret_email))
	{
		$row_email 	= $db->fetch_array($ret_email);
		$email_from	= stripslashes($row_email['lettertemplate_from']);
	}	
	
	$newsletter_id 			= $_REQUEST['newsletter_id'];
	// Getting the content of newsletter
	$newsletter_contents 	= mailNewsletter($newsletter_id);
	$email_subject			= stripslashes($newsletter_contents['newsletter_title']);
	$email_content 			= stripslashes($newsletter_contents['newsletter_contents']);
	
	
	$send_cust_array = array();
	if(trim($_REQUEST['custgroupid']))
	{
		$custgroup_id = explode('~',$_REQUEST['custgroupid']);
	} 
	if($_REQUEST['allcustomers']!=1)
	{
		//If all customers not selected
		if($_REQUEST['selected_custgroups'])
		{ 
			// get the customers under this group.
			$customer_group_ids = explode('~',$_REQUEST['selected_custgroups']);
			$customers_id = array();
			$customers_fname = array();
			$customers_email = array();
			if(is_array($customer_group_ids))
			{
				foreach ($customer_group_ids as $key=>$value)
				{
					if($value)
					{
						$sql_customers_in_groups = "select c.news_customer_id as custid,c.news_custname as custname,c.news_custemail as custemail 
														FROM 
															newsletter_customers c,customer_newsletter_group_customers_map cgcm 
														WHERE 
															c.news_customer_id = cgcm.customer_id 
															AND c.news_custhide = 0 
															AND cgcm.custgroup_id=".$value. "  ";
						$ret_customers_in_groups = $db->query($sql_customers_in_groups);
						if($db->num_rows($ret_customers_in_groups))
						{
							while($customers_in_groups=$db->fetch_array($ret_customers_in_groups))
							{
								// Setting the id and email id of customer to an associate array
								$cur_custid						= $customers_in_groups['custid'];
								$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($customers_in_groups['custemail']),'name'=>stripslashes($customers_in_groups['custname']));
							}
						}
					}	
				}
			}
		}
		else
		{
			$no_group_selected = 1;
		}
		$customer_ids = array();
		if($_REQUEST['selected_customers'])
		{
			$customer_ids = explode('~',$_REQUEST['selected_customers']);
		}
		else
		{
			$no_customers_selected = 1;
		}
		$cust_allid = array();
		if(count($customer_ids))
		{
			foreach ($customer_ids as $k=>$v)
			{
				if($v)
				{
					// Setting the id and email id of customer to an associate array
					 $cur_custid					= $v;
					 if(trim($_REQUEST[custgroupid]) or $_REQUEST['newsletter_cust_all']==1) // check whether customers to be picked from newsletter table or from original customer table
					 { 
					 	$sql_cust = "SELECT news_custname AS custname,
											news_custemail AS custemail  
										FROM 
											newsletter_customers 
										WHERE 
											news_customer_id = ".$cur_custid." 
											AND news_custhide=0";
					 }
					 else
					 {
						 // Get the email id of customer
						 $sql_cust = "SELECT customer_fname as custname,customer_email_7503 as custemail 
										FROM 
											customers 
										WHERE 
											customer_id = $cur_custid 
											AND customer_hide = 0 
											AND customer_activated='1' 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					}						
					$ret_cust = $db->query($sql_cust);
					if($db->num_rows($ret_cust))
					{
						$row_cust = $db->fetch_array($ret_cust);
						$cur_name						= stripslashes($row_cust['custname']);
						$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($row_cust['custemail']),'name'=>$cur_name);
					}	
				}	
			}
		}
	}
	elseif($_REQUEST['allcustomers']==1)//to send mail to all customers of selected type
	{
		if(trim($_REQUEST['custgroupid']))
		{
			$custgroup_id = explode('~',$_REQUEST['custgroupid']);
			foreach($custgroup_id AS $val)
			{  
				//$_REQUEST['custgroup_id']
				$newsql = "SELECT a.news_customer_id as custid,a.news_custname as custname,a.news_custemail as custemail 
								FROM 
									newsletter_customers a, customer_newsletter_group_customers_map b
								WHERE 
									b.custgroup_id=".$val." 
									AND a.news_custhide = 0 
									AND a.news_customer_id=b.customer_id";
				$newres = $db->query($newsql);
				if($db->num_rows($newres))
				{
					while($newrow = $db->fetch_array($newres))
					{
						$cur_custid						= $newrow['custid'];
						$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($newrow['custemail']),'name'=>stripslashes($newrow['custname']));
					}
				}	
			}
		}
		elseif($_REQUEST['newsletter_cust_all']==1) // case if show all of newsletter customers is selected
		{
			$sql_all_newsletercustomers = "SELECT news_customer_id AS custid,news_custname AS custname,
											 		news_custemail AS custemail  
												FROM 
													newsletter_customers 
												WHERE 
													sites_site_id = ".$ecom_siteid." 
													AND news_custhide=0";
												
			$ret_all_newsletercustomers =$db->query($sql_all_newsletercustomers);
			if($db->num_rows($ret_all_newsletercustomers))
			{
				while($all_newsletercustomers = $db->fetch_array($ret_all_newsletercustomers))
				{
					$cur_custid						= $all_newsletercustomers['custid'];
					$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($all_newsletercustomers['custemail']),'name'=>stripslashes($all_newsletercustomers['custname']));
				}
			}	
		
		}
		elseif(trim($_REQUEST['corp_name'])) // case of selecting corporate customer show all
		{
			$dept_id = explode(",",$_REQUEST['dept_id']);
			if(count($dept_id))
			{
				foreach($dept_id AS $value)
				{
					$sql_customers = "SELECT customer_id AS custid,customer_fname AS custname,
											 customer_email_7503 AS custemail 
										FROM 
											customers 
										WHERE 
											customers_corporation_department_department_id=".$value." 
											AND sites_site_id = ".$ecom_siteid." 
											AND customer_hide='0' 
											AND customer_activated='1'";
					$ret_customers = $db->query($sql_customers);
					if($db->num_rows($ret_customers))
					{
						$row_customers 					= $db->fetch_array($ret_customers);
						$cur_custid						= $row_customers['custid'];
						$cust_name						= stripslashes($row_customers['custname']);
						$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($row_customers['custemail']),'name'=>stripslashes($cust_name));
					}	
				}
			}
		}
		elseif(trim($_REQUEST['ftype']))
		{
			$sql_customers = "SELECT customer_id AS custid,customer_fname AS custname,
									 customer_email_7503 AS custemail 
								FROM 
									customers 
								WHERE  
									sites_site_id = ".$ecom_siteid." 
									AND customer_hide='0' 
									AND customer_activated='1'";
		
			$ret_all_newsletercustomers =$db->query($sql_customers);
			if($db->num_rows($ret_all_newsletercustomers))
			{
				while($all_newsletercustomers = $db->fetch_array($ret_all_newsletercustomers))
				{
					$cur_custid						= $all_newsletercustomers['custid'];
					$cust_name						= stripslashes($all_newsletercustomers['custname']);
					$send_cust_array[$cur_custid] 	= array('email'=>stripslashes($all_newsletercustomers['custemail']),'name'=>stripslashes($cust_name));
				}
			}
		}
	}
	
	if($_REQUEST['filterorders']==1)
	{
		$datefrom 	= $_REQUEST['date_from'];
		$dateto 	= $_REQUEST['date_to'];
		$selcats 	= trim($_REQUEST['selected_cats']);
		if($selcats!='' and $selcats!=0)
		{
			$mapprod_arr = array();
			$mapprod_str = '';
			// Get the id of products mapped with these categories
			
			$sql_mapprod = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id IN(".$selcats.")";
			$ret_mapprod = $db->query($sql_mapprod);
			if($db->num_rows($ret_mapprod))
			{
				while ($row_mapprod = $db->fetch_array($ret_mapprod))
				{
					$mapprod_arr[] = $row_mapprod['products_product_id'];
				}
				$mapprod_str = " AND products_product_id IN (".implode(',',$mapprod_arr).") ";
			}
			else
			{
				$mapprod_str = " AND products_product_id IN (-1) ";
			}
		}
		
		$sql_ord = "SELECT distinct a.order_id,a.order_custtitle,a.order_custfname,a.order_custsurname,
								a.order_custemail 
							FROM 
								orders a, order_details b 
							WHERE
								a.sites_site_id = $ecom_siteid 
								AND a.order_id = b.orders_order_id 
								AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
								AND (a.order_date>='".$datefrom." 00:00:00' AND a.order_date<='".$dateto." 23:59:59')
								$mapprod_str "; 
			
		$ret_ord = $db->query($sql_ord);
		if($db->num_rows($ret_ord))
		{
			while ($row_ord = $db->fetch_array($ret_ord))
			{
				$name = $row_ord['order_custtitle'].trim($row_ord['order_custfname']).' '.trim($row_ord['order_custsurname']);
				$send_cust_array[$row_ord['order_custemail']] 	= array('email'=>stripslashes($row_ord['order_custemail']),'name'=>stripslashes($name));
			}
		}
		if(count($send_cust_array)==0)
		{
			$alert = 'Sorry!! No customers were found that match your criteria.';
			include("includes/newsletter/ajax/newsletter_ajax_functions.php");
			include("includes/newsletter/list_customers.php");
			exit;
		}
		/*echo "<pre>";
		print_r($send_cust_array);
		echo "</pre>";
		exit;*/
	}
	
	if($_REQUEST['filteimported']==1)
	{
		$sql_imp = "SELECT customer_title,customer_name,customer_email_7503 
						FROM 
							imported_customers
						WHERE 
							sites_site_id = $ecom_siteid 
						ORDER BY 
							imported_id";
		$ret_imp = $db->query($sql_imp);
		if($db->num_rows($ret_imp))
		{
			while ($row_imp = $db->fetch_array($ret_imp))
			{
				$name = $row_imp['customer_title'].trim($row_imp['customer_name']);
				$send_cust_array[$row_imp['customer_email_7503']] 	= array('email'=>stripslashes($row_imp['customer_email_7503']),'name'=>stripslashes($name));
			}
		}
	}
		
	if(count($send_cust_array)==0)
	{
		$alert = "No customer are selected.Please Select customer(s) or Customer Group(s)";
		include("includes/newsletter/ajax/newsletter_ajax_functions.php");
		include("includes/newsletter/list_customers.php");
	}
	else
	{
		// Calling function to send the newsletter email via SMTP
		//echo "No of Customer: ".count($send_cust_array)."<br><br>";
		//send_Newsletter_emails_to_customers($email_from,$email_subject,$email_content,$send_cust_array);
		// Get the total number of records existing in the newsletter_cron_mails as of now
		$wait_hours = 1;
		$sql_cnt = "SELECT main_id,count(mail_id) as totcnt 
						FROM 
							newsletter_cron_mails 
						GROUP BY 
							main_id";
		$ret_cnt = $db->query($sql_cnt);
		if($db->num_rows($ret_cnt))
		{
			while ($row_cnt = $db->fetch_array($ret_cnt))
			{
				$temp_hours = ceil($row_cnt['totcnt']/500);
				$wait_hours += $temp_hours;
			}
		}
				
		// Check whether there exists a newsletter with the same subject for current hostname
		$sql_test = "SELECT main_id 
						FROM 
							newsletter_cron_main 
						WHERE 
							hostname='".$ecom_hostname."'  
							AND email_subject='".$email_subject."' 
							AND site_id = '".$ecom_siteid."' 
							AND site_type = 'v4'  
						LIMIT 
							1";	
		$ret_test = $db->query($sql_test);
		if($db->num_rows($ret_test)==0)
		{
			$db->query("INSERT INTO newsletter_cron_main SET 
															email_from='$email_from', 
															email_subject='".addslashes($email_subject)."', 
															email_content='".addslashes($email_content)."', 
															send_date=now(),
															scheduled_date=DATE_ADD(now(),INTERVAL $wait_hours HOUR), 
															hostname='$ecom_hostname', 
															site_id = '$ecom_siteid', 
															site_type = 'v4' ");
			$insert_id = $db->insert_id();
			foreach($send_cust_array as $k=>$v) {
				$db->query("INSERT INTO newsletter_cron_mails SET main_id='$insert_id', send_name='".addslashes($v['name'])."', send_email='".addslashes($v['email'])."'");
			}
			$sql_check = "SELECT DATE_FORMAT(scheduled_date,'%d-%b-%Y %h:%i %p') scheduled_at FROM newsletter_cron_main WHERE main_id=$insert_id LIMIT 1";
			$ret_check = $db->query($sql_check);
			if($db->num_rows($ret_check))
				$row_check = $db->fetch_array($ret_check);
			$scheduled_at = $row_check['scheduled_at'];
			
			// Check whether newsletter sending is temporarly disabled
			$sql_disabled = "SELECT disable_now, DATE_FORMAT(disable_end_on,'%d-%m-%Y') dis_date
								FROM 
									newsletter_disable ";
			$ret_disabled = $db->query($sql_disabled);
			if($db->num_rows($ret_disabled))
			{
				$row_disabled = $db->fetch_array($ret_disabled);
			}
			
			if($row_disabled['disable_now']==1)
			{
				$sending_msg = "<br><br><span style='color:#1F7F28'>---- Server Upgradation is going on ----</span><br><br>Sending process is expected to start only on <span style='color:#1F7F28'>".$row_disabled['dis_date']."</span> .<br><br>
				Only 500 emails will be send out every hour.";
			}
			elseif($row_disabled['disable_now']==0)
			{
				$sending_msg = "<br><br>Sending process expected to start on <span style='color:#1F7F28'>".$scheduled_at.".</span> <br><br>
				Only 500 emails will be send out every hour.";
			}
		?>
		
			<br><font color="red"><b>Newsletter email have been scheduled for sending to <?=count($send_cust_array)?> customers <?php echo $sending_msg;?></b></font><br> 
			<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Listing page</a><br /><br />
	<?php 
		}
		else
		{
		?>
			<br><font color="red"><b>Sorry!! the same newsletter has been already been scheduled for sending.<br><br>
			<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Listing page</a><br /><br />
		<?php 			
		}
	}
}
else if($_REQUEST['fpurpose']=='del_email_schedule')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ('../includes/newsletter/ajax/newsletter_ajax_functions.php');
	if($_REQUEST['rmid'])
	{
		$sql_del = "DELETE FROM newsletter_cron_mails 
						WHERE 
							main_id=".$_REQUEST['rmid'];
		$db->query($sql_del);
		$sql_del = "DELETE FROM newsletter_cron_main  
						WHERE 
							main_id=".$_REQUEST['rmid'];
		$db->query($sql_del);
		$alert = 'Newsletter schedule deleted successfully';	
	}
	show_newsletter_schedule($alert);
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry News letter not selected';
		}
		else
		{
					
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM newsletters WHERE newsletter_id=".$del_arr[$i];
					  $db->query($sql_del);
				
				}	
			}
			$alert = "News letter deleted Sucessfully";
		}
		include ('../includes/newsletter/list_newsletter.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/newsletter/add_newsletter.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/newsletter/ajax/newsletter_ajax_functions.php');
	include_once("classes/fckeditor.php");
	$newsletter_id = $_REQUEST['checkbox'][0];
	include("includes/newsletter/edit_newsletter.php");
	
}
else if($_REQUEST['fpurpose']=='prodnewsletter') 
{
	$newsletter_id = $_REQUEST['newsletter_id'];
	include("includes/newsletter/newsletter_product.php");
}
else if($_REQUEST['fpurpose']=='insert')
{
	

	//Function to validate forms
	validate_forms();
	

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			$insert_array								= array(); 
			$insert_array['sites_site_id'] 				= $ecom_siteid;
			$insert_array['newsletter_template_id'] 	= $_REQUEST['template_name'];
			$insert_array['preview_title'] 				= addslashes($_REQUEST['newsletter_title']);
			$insert_array['preview_contents']			= addslashes($_REQUEST['newsletter_contents']);
			$insert_array['newsletter_createdate']		= date("Y-m-d H:m:s");
			$insert_array['newsletter_lastupdate']		= date("Y-m-d H:m:s");
				
		$db->insert_from_array($insert_array, 'newsletters');
		$insert_id = $db->insert_id();
		?>
<script language="javascript">
	window.location = "home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$insert_id?>";
</script>
		   <? 
		exit;
		/*
		include("includes/newsletter/newsletter_product.php");
				
	?>
		<br><font color="red"><b>Newsletter Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?php echo $insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Newsletter Edit Page</a><br /><br />
		<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Newsletter Add page</a><br />
	<?php
	*/
	}
  
}
else if($_REQUEST['fpurpose']=='save_preview')
{
	

	//Function to validate forms
	validate_forms();
	

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			$update_array								= array();
			$update_array['newsletter_title'] 			= addslashes($_REQUEST['newsletter_title']);
			$update_array['newsletter_contents']		= addslashes($_REQUEST['newsletter_contents']);
			$update_array['newsletter_lastupdate']		=  date("Y-m-d H:m:s");
		
		
		$db->update_from_array($update_array, 'newsletters', array('newsletter_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));		
	//	$db->insert_from_array($insert_array, 'newsletters');
	//	$insert_id = $db->insert_id();

		
		if($_REQUEST['fpurptype']=='save') {
		$alert = "Saved Sucessfully";
			?>
<script language="javascript">
	window.location = "home.php?request=newsletter&alert=<?=$alert?>";
</script>
		   <? 
		} else {
	
		   $newsletter_id = $_REQUEST['newsletter_id'];
?>
<script language="javascript">
	window.location = "home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=<?PHP echo $newsletter_id; ?>";
</script>
		   <?  //"<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=$newsletter_id> ";
//		 $msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=$newsletter_id> ";
			
		} 
		echo $msg;
		exit;
		
	}
  
}
else if($_REQUEST['fpurpose'] == 'update_newsletter') {  // for updating the adverts

	if($_REQUEST['newsletter_id'])
	{
		//Function to validate forms
		validate_forms();
		if (!$alert)
		{

		$update_array						= array();
			$update_array['sites_site_id'] 		= $ecom_siteid;
		    if($_REQUEST['template_name']) {
				  if($_REQUEST['template_name']=='none') {
				      $update_array['newsletter_template_id']		= 0;
				   } else {
				      $update_array['newsletter_template_id']		= $_REQUEST['template_name'];
				   }	  
			}
			$update_array['preview_title']	= addslashes($_REQUEST['newsletter_title']);
			$update_array['preview_contents']		= addslashes($_REQUEST['newsletter_contents']); 
			
		
			$update_array['newsletter_lastupdate'] 		= date("Y-m-d H:m:s ");
			
		$db->update_from_array($update_array, 'newsletters', array('newsletter_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));
		?>
		<script language="javascript">
			window.location = "home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=<?=$_REQUEST['newsletter_id']?>";
		</script>
		<?
		exit;
		/*$msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=$_REQUEST[newsletter_id]'> ";
		echo $msg;
		
		
			?>
			<br><font color="red"><b>Newsletter Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to Newsletter Add page</a><br /><br />
			<?php
			*/
		}
		else
		{
		?>
			<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Advert Id</font><br />
		<br /><a class="smalllink" href="home.php?request=property&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_approvestatus=<?php echo $_REQUEST['cbo_approvestatus']?>&cbo_cat=<?php echo $_REQUEST['cbo_cat']?>&search_cname=<?=$_REQUEST['search_cname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		
	<?php	
	} //// updating adverts ends



}
elseif($_REQUEST['fpurpose'] == 'list_products_ajax'){ // for listing the products assiged to the adverts to be displyed 
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/newsletter/ajax/newsletter_ajax_functions.php');
		show_product_list($_REQUEST['cur_newsletterid'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Adverts
$advert_id = $_REQUEST['checkbox'][0];
	include ('includes/newsletter/list_assign_products.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_products'){// to asign the categories to the Advert

	
	$newsletter_id = $_REQUEST['newsletter_id'];
	{
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{ 
		
			$sql_assigned_products = "SELECT products_product_id FROM newsletter_products WHERE newsletters_newsletter_id =".$_REQUEST['newsletter_id']." AND sites_site_id=".$ecom_siteid;
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
					$insert_array['newsletters_newsletter_id']=$_REQUEST['newsletter_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'newsletter_products');
				}	
			}
			$alert = 'Products Successfully assigned  to Newsletter(s)'; 
		}						
	
	}	
	include ('includes/newsletter/newsletter_product.php');	
//	$msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=$_REQUEST[newsletter_id]'> ";
//	echo $msg;
//	exit;

/*	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;


			?>
	<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&newsletter_id=<?=$_REQUEST['newsletter_id']?>" onclick="show_processing()">Go Back to the Newsletter Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  this Newsletter</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Advert Add Page</a><br /><br />		
			<?
	*/
}
elseif($_REQUEST['fpurpose']=='delete_assign_products') {
	$newsletter_id = $_REQUEST['newsletter_id'];
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
					// Deleting product from Advert
					$sql_del = "DELETE FROM newsletter_products WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Unassigned from the Newsletter'; 
		}
		include ('includes/newsletter/newsletter_product.php');	
		//$msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=".$_REQUEST['newsletter_id']."'> ";
	//	echo $msg;
	//	exit;
}
 elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to Adverts using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/newsletter/ajax/newsletter_ajax_functions.php');
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
					// Deleting product from Advert
					$sql_del = "DELETE FROM newsletter_products WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Unassigned from the Newsletter'; 
		}	
	show_product_list($_REQUEST['cur_newsletter_id'],$alert);
  }
elseif($_REQUEST['fpurpose']=='list_dept') // show state list
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/newsletter/ajax/newsletter_ajax_functions.php');
		
		show_display_dept_list($_REQUEST['corp_id'],$_REQUEST['dept_id']);
}
elseif($_REQUEST['fpurpose']=='preview') 
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/newsletter_preview.php");	
}
function validate_forms()
{
	global $alert,$db;
	if($_REQUEST['dont_save']!=1)
	{
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['newsletter_title'],$_REQUEST['newsletter_contents']);
		$fieldDescription 	= array('News Letter Title','News Letter Contents');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}



	
	function mailNewsletter($newsletter_id){
	global $ecom_siteid,$db;
	$sql_newsletter = "SELECT newsletter_title,newsletter_contents FROM newsletters WHERE newsletter_id = ".$newsletter_id." AND sites_site_id=".$ecom_siteid;
	$ret_newsletter = $db->query($sql_newsletter);
	return $newsletter_contents = $db->fetch_array($ret_newsletter);
	
	}
	
	function send_Newsletter_emails_to_customers($from,$subject,$content,$cust_arr)
	{
		global $db,$ecom_siteid,$ecom_hostname;
		if (count($cust_arr))
		{
			include("classes/class.phpmailer.php");
			//SMTP Mail function starts
			$send_var 				= 0;
			$mail 					= new PHPMailer();
			$mail->From     		= $from; //Fake from address
			$mail->FromName 		= $ecom_hostname; //Fake from name
			$mail->AddReplyTo($from,$ecom_hostname);
			$mail->ClearAddress();
			$mail->ClearBCCs();
			$mail->Subject 			=  $subject;
			$mail->Body     		=  $content;
			foreach ($cust_arr as $k=>$v)
			{
				$snd_name 	= $v['name'];
				$snd_email 	= $v['email'];
				if($send_var < 3)
				{
					$send_var++;
					$mail->AddBCC($snd_email,"$snd_name");
				}
				else
				{
					$send_var=0;
					$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
					$mail->AddBCC($snd_email,"$snd_name");  
					$mail->Send();
					$mail->ClearBCCs();
					$mail->ClearAddress();
				}
			}
			if ($send_var)
			{
				$mail->AddAddress('sales-1@webclinicmailer.co.uk',"Sales Marketing");
				$mail->Send();
			}	
		}
	}

?>
