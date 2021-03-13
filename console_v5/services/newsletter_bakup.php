<?php

$templatecode = array("Name"=>'[Name]', "E-mail"=>'[Email]', "Products"=>'[Products]', "Date"=>'[date]');

if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/list_newsletter.php");
}
if($_REQUEST['fpurpose']=='listcustomers')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	
	
	
	include("includes/newsletter/list_customers.php");
}

if($_REQUEST['fpurpose']=='listnewsgroups')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/newsletter/list_newsgroups.php"); //list_newsgroups
}

elseif($fpurpose=='sendmail')
{
	$newsletter_id = $_REQUEST['newsletter_id'];
	$newsletter_contents = mailNewsletter($newsletter_id);
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: NewsLetter<test@test.com>\r\n";
	$subject = $newsletter_contents['newsletter_title'];
	$contents= $newsletter_contents['newsletter_contents'];
	
	$prodsql = "SELECT count(id) AS cnt, products_product_id
						 FROM newsletter_products 
						 		WHERE newsletters_newsletter_id='".$newsletter_id."'";
	$prodres = $db->query($prodsql);
	$prodnum = $db->num_rows($prodres);
	if($prodnum > 0) {
			$count = 0;
			$prodcontent = "<table>";
			while($prodrow = $db->fetch_array($prodres)) {
				$count+=1;
				
				$imagsql     = "SELECT  image_thumbpath 
				                      FROM images a, images_product b 
									                 WHERE a.image_id=b.images_image_id 
													   AND b.products_product_id = '".$prodrow['products_product_id']."' 
													   AND a.sites_site_id = '".$ecom_siteid."'
													   		ORDER BY b.image_order ASC
													     ";
				$imagres      = $db->query($imagsql);
				$imagrow      = $db->fetch_array($imagres);
				$images       = $imagrow['image_thumbpath'];
				if(trim($images)) {
					$imgname = "<img src='http://".$ecom_hostname."/images/".$ecom_hostname."/thumb/".$images." border='0'/>";					 
				} else {
					$imgname = '';
				}
				
				$prodnamesql = "SELECT product_name, product_webprice, product_discount, product_discount_enteredasval, 
									   product_bulkdiscount_allowed, 
									 			FROM products 
													 WHERE product_id='".$prodrow['products_product_id']."'";
				$prodnameres = $db->query($prodnamesql);
				$prodnamerow = $db->fetch_array($prodnameres);
				
				if($prodnamerow['product_bulkdiscount_allowed']==1) {
					switch($prodnamerow['product_discount_enteredasval'])  {
						case '0' :
							$rate =  $prodnamerow['product_webprice'] - ($prodnamerow['product_webprice']*$prodnamerow['product_discount']/100);
						case '1' :
						    $rate =  $prodnamerow['product_webprice'] - $prodnamerow['product_discount'];
						case '2' :
							$rate =  $prodnamerow['product_discount'];		
					}
				}	
					 
					$prodcontent .= "<tr><td>".$prodnamerow['product_name']."</td>
						<td>".$imgname."</td>
						<td>".$rate."</td></tr>";
			}
		$contents =	str_replace('[Products]',"$prodcontent",$contents);
	}
	
	
	if($_REQUEST['allcustomers']!=1)
	{//If all customers not selected
	$reg_customers_id = array();
		if($_REQUEST['selected_custgroups']){ // get the customers under this group.
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
									 $sql_customers_in_groups = "select c.news_customer_id,c.news_custname,c.news_custemail FROM newsletter_customers c,customer_newsletter_group_customers_map cgcm
									 where c.news_customer_id = cgcm.customer_id and cgcm.custgroup_id=".$value. "  ";
									 $ret_customers_in_groups = $db->query($sql_customers_in_groups);
									 $customers_in_groups = array();
									 while($customers_in_groups=$db->fetch_array($ret_customers_in_groups))
										 {
											 $reg_customers_id[] = $customers_in_groups['news_customer_id'];
										 }
						}	
						$reg_customers_id = array_unique($reg_customers_id);
				}
			}
			$customer_id_ingroup_str = implode(',',$reg_customers_id);
		}
		else
		{
				$no_group_selected = 1;
			 
		}
			$customer_ids =array();
			if($_REQUEST['selected_customers'])
			{
				$customer_ids = explode('~',$_REQUEST['selected_customers']);
			}
			else
			{
				$no_customers_selected = 1;
			}
				$cust_allid =array();
			if(is_array($reg_customers_id) && is_array($customer_ids))
			{
				$cust_allid = array_merge($reg_customers_id , $customer_ids);//combine two arrays
				$cust_res_id = array_unique($cust_allid);// Avoid repetetion in ids
			}
			if(is_array($cust_res_id))
			{
				foreach($cust_res_id as $key =>$value)
				{	
					if($value)
					{
						$sql_customers = "SELECT news_customer_id,news_title,news_custname,news_custemail FROM newsletter_customers WHERE news_customer_id=".$value." AND sites_site_id = ".$ecom_siteid." AND news_custhide=0";
						/*if($customer_id_ingroup_str)
						$sql_customers.=" AND customer_id NOT IN ($customer_id_ingroup_str)";*/
						$ret_customers = $db->query($sql_customers);
							while($news_customers = $db->fetch_array($ret_customers))
							{
								$contents =	str_replace('[Name]',$news_customers['news_custname'],$contents);
								$contents =	str_replace('[Email]',$news_customers['news_custemail'],$contents);
								$contents =	str_replace('[date]',date("Y-m-d"),$contents);
								
								$news_customer_id[] = $news_customers['news_customer_id'];
								$to = $news_customers['news_custemail'];
								mail($to,$subject,$contents,$headers);
							}
					}
			   }
			}
		}
		elseif($_REQUEST['allcustomers']==1)
		{//to send mail to all customers
			$sql_all_newsletercustomers = "SELECT news_customer_id,news_custname,news_custemail,customer_id FROM newsletter_customers WHERE sites_site_id = ".$ecom_siteid." AND news_custhide=0";
			$ret_all_newsletercustomers =$db->query($sql_all_newsletercustomers);
			while($all_newsletercustomers = $db->fetch_array($ret_all_newsletercustomers))
			{
								$contents =	str_replace('[Name]',$news_customers['news_custname'],$contents);
								$contents =	str_replace('[Email]',$news_customers['news_custemail'],$contents);
								$contents =	str_replace('[date]',date("Y-m-d"),$contents);
								
				$news_customer_id[] = $all_newsletercustomers['news_customer_id'];
				$to = $all_newsletercustomers['news_custemail'];
				mail($to,$subject,$contents,$headers);
			}
		}
		if($no_customers_selected && $no_group_selected && $_REQUEST['allcustomers']!=1)
		{
			$alert = "No customer are selected.Please Select customer(s) or Customer Group(s)";
			include("includes/newsletter/list_customers.php");
		}
		else
		{
		?>
		
		<br><font color="red"><b>Send Newsletters Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=newsletter&fpurpose=listcustomers&newsletter_id=<?php echo $newsletter_id?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pas_pg=<?=$_REQUEST['pass_pg']?>">Go Back to List customers Page</a>
		<?
		}
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($del_ids == '')
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
			$insert_array						= array();
			$insert_array['sites_site_id'] 				= $ecom_siteid;
			$insert_array['newsletter_title'] 			= addslashes($_REQUEST['newsletter_title']);
			$insert_array['newsletter_contents']		= $_REQUEST['newsletter_contents'];
			$insert_array['newsletter_createdate']		=  date("Y-m-d H:m:s");
			$insert_array['newsletter_lastupdate']		=  date("Y-m-d H:m:s");
				
		$db->insert_from_array($insert_array, 'newsletters');
		$insert_id = $db->insert_id();
				
	?>
		<br><font color="red"><b>Newsletter Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?php echo $insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Newsletter Edit Page</a><br /><br />
		<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Newsletter Add page</a><br />
	<?php
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
			$update_array['newsletter_title']	= $_REQUEST['newsletter_title'];
			$update_array['newsletter_contents']		= $_REQUEST['newsletter_contents'];
		
			$update_array['newsletter_lastupdate'] 		= date("Y-m-d H:m:s ");
			
		$db->update_from_array($update_array, 'newsletters', array('newsletter_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));
			?>
			<br><font color="red"><b>Newsletter Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to Newsletter Add page</a><br /><br />
			<?php
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
else if($fpurpose == 'list_assign_products'){// to list the products to be assigned to the Adverts
$advert_id = $_REQUEST['checkbox'][0];
	include ('includes/newsletter/list_assign_products.php');						
	
}
else if($fpurpose == 'assign_products'){// to asign the categories to the Advert

	
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
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&newsletter_id=<?=$_REQUEST['newsletter_id']?>" onclick="show_processing()">Go Back to the Newsletter Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>" onclick="show_processing()">Go Back to the Edit  this Newsletter</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Advert Add Page</a><br /><br />		
			<?
	
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
			$alert = 'Products Successfully Removed from the Newsletter'; 
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
?>
