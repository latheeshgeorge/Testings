<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/site_headers/list_site_headers.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$header_ids_arr 		= explode('~',$_REQUEST['header_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($header_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['header_hide']	= $new_status;
			$header_id 						= $header_ids_arr[$i];	
			$db->update_from_array($update_array,'site_headers',array('header_id'=>$header_id));
			
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/site_headers/list_site_headers.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Site Header not selected';
		}
		else
		{
		
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM site_headers WHERE header_id=".$del_arr[$i];
					  $db->query($sql_del);
						
					
			
				}	
			}
			$alert = "Site Header deleted Sucessfully";
		}
		include ('../includes/site_headers/list_site_headers.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/site_headers/add_site_headers.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/site_headers/ajax/headers_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$header_id = $_REQUEST['checkbox'][0];
	include("includes/site_headers/edit_site_headers.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{

	//Function to validate forms
	validate_forms();
	
		//calling function to save the file
		
		 $sql_check = "SELECT count(*) as cnt FROM site_headers WHERE header_title='".add_slash($_REQUEST['header_title'])."' AND sites_site_id=$ecom_siteid";
			$ret_check_headers = $db->query($sql_check);
		    list($numcount_headers) = $db->fetch_array($ret_check_headers);
			if($numcount_headers>0)
			{ 
			$alert = "Error!! Header Title allready exists!!";
			}
			else
			{
				if($_FILES['header_filename']['type']){
					if($_FILES['header_filename']['type']=='image/jpeg' || $_FILES['header_filename']['type']=='image/gif' || $_FILES['header_filename']['type']=='image/pjpeg') 
					{
					$valid_image_type = true;
					}
					else
					{
					 $valid_image_type = false;
					 $alert = "Error: Invalid Image type. Enter jpeg or gif image.";
					}
				}
			}	
		if(!$alert)
			{
				if ($_REQUEST['header_period_change_required'])
				{
						if(!is_valid_date($_REQUEST['header_startdate'],'normal','-') or !is_valid_date($_REQUEST['header_enddate'],'normal','-'))
							$alert = 'Start or End Date is Invalid';
				}	
				if($_FILES['header_filename']['name'])
				{ 
				$ret = save_image();
				$alert = $ret['alert'];
				$add_filename = $ret['filename'];	
				}
			}	
	
	if($alert)
	{
	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/site_headers/ajax/headers_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$header_id = $_REQUEST['checkbox'][0];
	include("includes/site_headers/add_site_headers.php");
	
	?>
		<!--<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br><br />
		<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=site_headers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Site header Page</a>
-->
	<?php	
	}
	else
	{
		//Calling the function to save the Site header
		$header_id = Save_SiteHeader($add_filename);
		$insert_id = $db->insert_id();
		// Section to make entry to display_settings table
				
	?>
		<br><font color="red"><b>Site headers Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?php echo $header_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a>
	    	<br />	<br />	<a class="smalllink" href="home.php?request=site_headers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Page</a>

	<?php
	}

	
	}
}
else if($_REQUEST['fpurpose'] == 'update_site_header') {  // for updating the Site headers

	if($_REQUEST['header_id'])
	{
		//Function to validate forms
		validate_forms();
		if(!$alert)
			{
				if ($_REQUEST['header_period_change_required'])
				{
						if(!is_valid_date($_REQUEST['header_startdate'],'normal','-') or !is_valid_date($_REQUEST['header_enddate'],'normal','-'))
							$alert = 'Start or End Date is Invalid';
				}		
			}
			$sql_check = "SELECT count(*) as cnt FROM site_headers WHERE header_title='".add_slash($_REQUEST['header_title'])."' AND sites_site_id=$ecom_siteid AND header_id<>".$_REQUEST['header_id']."";
			$ret_check_headers = $db->query($sql_check);
		    list($numcount_headers) = $db->fetch_array($ret_check_headers);
			if($numcount_headers>0)
			{ 
			$alert = "Error!! Header Title allready exists!!";
			}
			else
			{
				if($_FILES['header_filename']['type']){
					if($_FILES['header_filename']['type']=='image/jpeg' || $_FILES['header_filename']['type']=='image/gif' || $_FILES['header_filename']['type']=='image/pjpeg') 
					{
					$valid_image_type = true;
					}
					else
					{
					 $valid_image_type = false;
					 $alert = "Error: Invalid Image type. Enter jpeg or gif image.";
					}
				}
			}	
	
		if (!$alert)
		{
		
		
			if($_FILES['header_filename']['name'])
			{ 
			
				$ret 			= save_image();
				$alert 			= $ret['alert'];
				$add_filename 	= $ret['filename'];
			}
			//Calling the function to save the property
			$header_id = Save_SiteHeader($add_filename);
				
			?>
			<br><font color="red"><b>Site Headers Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Site headers Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Site  Headers Edit Page</a><br /><br />
		<a class="smalllink" href="home.php?request=site_headers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Page</a>

			<?php
		}
		else
		{
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/site_headers/ajax/headers_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$header_id = $_REQUEST['checkbox'][0];
		include("includes/site_headers/edit_site_headers.php");
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Header Id</font><br />
		<br /><a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Site  Headers Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Site headers Listing page</a><br /><br />
			
		
	<?php	
	} //// updating Site header ends



}
//To display the main info of the site headers.
elseif($_REQUEST['fpurpose'] == 'list_sitegheaders_maininfo'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		show_siteheaders_maininfo($_REQUEST['cur_headerid']);
/*To list categories assigned in the Site Headers using AJAX*/
}elseif($_REQUEST['fpurpose'] == 'list_categoriesInHeaders_ajax'){
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		show_category_list($_REQUEST['cur_headerid']);
}elseif($_REQUEST['fpurpose'] == 'changestat_category_ajax'){ // To Change the status of the selected category in the Site Headers
		
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Categories not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the catgories in the Site headers
				 $sql_chstat = "UPDATE header_display_category SET header_display_category_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Categories'; 
		}	
		show_category_list($_REQUEST['cur_header_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_categories'){// to list the categories to be assigned to the Site headers
	$header_id = $_REQUEST['checkbox'][0];
	include ('includes/site_headers/list_assign_categories.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_categories'){// to asign the categories to the Site headers
	
	 $header_id = $_REQUEST['header_id'];
	{
		
		if ($_REQUEST['category_ids'] == '')
		{
			$alert = 'Sorry Category not not selected';
		}
		else
		{ 
		
	$sql_assigned_categories = "SELECT product_categories_category_id FROM header_display_category WHERE site_headers_header_id =".$_REQUEST['header_id'];
	$res_assigned_categories = $db->query($sql_assigned_categories);
	$assigned_categories_arr = array();
	while($assigned_categories = $db->fetch_array($res_assigned_categories)){
		$assigned_categories_arr[]= $assigned_categories['product_categories_category_id'];
	}
			$categories_arr = explode("~",$_REQUEST['category_ids']);
			for($i=0;$i<count($categories_arr);$i++)
			{
				if(trim($categories_arr[$i]) && !in_array($categories_arr[$i],$assigned_categories_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['site_headers_header_id']=$_REQUEST['header_id'];
					$insert_array['product_categories_category_id']=$categories_arr[$i];
					$db->insert_from_array($insert_array, 'header_display_category');
				}	
			}
			$alert = 'Site Header(s) Successfully assigned categories'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&header_id=<?=$_REQUEST['header_id']?>" onclick="show_processing()">Go Back to the Site Headers Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&curtab=catmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Site Header</a><br /><br />
					
			<?
	
}
elseif($_REQUEST['fpurpose']=='delete_category_ajax') // section used for delete of Category using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry categories not not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting pages from site headers 
					$sql_del = "DELETE FROM header_display_category WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Categories Successfully Removed from the Site header'; 
		}	
show_category_list($_REQUEST['cur_header_id'],$alert);

/**PRODUCT ASSIGINING SECTION STARTS >>>>>>>>>>>**/
}elseif($_REQUEST['fpurpose'] == 'list_products_ajax'){ // for listing the products assiged to the Site headers to be displyed 
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		show_product_list($_REQUEST['cur_headerid'],$alert);
}else if($_REQUEST['fpurpose'] == 'list_assign_products'){// to list the products to be assigned to the Site headers
$header_id = $_REQUEST['checkbox'][0];
	include ('includes/site_headers/list_assign_products.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_products'){// to asign the categories to the Site headers

	
	$header_id = $_REQUEST['header_id'];
	{
		
		if ($_REQUEST['product_ids'] == '')
		{
			$alert = 'Sorry Products not not selected';
		}
		else
		{ 
		
		$sql_assigned_products = "SELECT products_product_id FROM header_display_product WHERE site_headers_header_id =".$_REQUEST['header_id']." AND sites_site_id=".$ecom_siteid;
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
					$insert_array['site_headers_header_id']=$_REQUEST['header_id'];
					$insert_array['products_product_id']=$products_arr[$i];
					$db->insert_from_array($insert_array, 'header_display_product');
				}	
			}
			$alert = 'Products Sucessfully assigned to Site Header(s)'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&header_id=<?=$_REQUEST['header_id']?>" onclick="show_processing()">Go Back to the Site Header Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&curtab=prodmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Site Header</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Header</a>		
			<?
	
}elseif($_REQUEST['fpurpose'] == 'changestat_product_ajax'){ // To Change the status of the selected Product assigned to the Site Headers
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
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
					// Changing status of the catgories in the Site headers
				$sql_chstat = "UPDATE header_display_product SET header_display_product_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Product(s) assigned to the Site Header'; 
		}	
		show_product_list($_REQUEST['cur_header_id'],$alert);
}elseif($_REQUEST['fpurpose']=='delete_product_ajax') // section used for delete of Product assigned to Site headers using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
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
					// Deleting product from Site Headers
					$sql_del = "DELETE FROM header_display_product WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Products Successfully Removed from the Site Header'; 
		}	
show_product_list($_REQUEST['cur_header_id'],$alert);
}
/* FOR the assigned STATIC PAGES PART*/
elseif($_REQUEST['fpurpose'] == 'list_assign_pages_ajax'){
		 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		show_assign_pages_list($_REQUEST['cur_headerid'],$alert);
}
elseif($_REQUEST['fpurpose'] == 'changestat_assign_pages_ajax'){ // To Change the status of the selected Page assigned to the Site headers
		 
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");			
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		if ($_REQUEST['ch_ids'] == '')
		{
			$alert = 'Sorry Pages not not selected';
		}
		else
		{
			$chstat_arr = explode("~",$_REQUEST['ch_ids']);
			for($i=0;$i<count($chstat_arr);$i++)
			{
				if(trim($chstat_arr[$i]))
				{
					// Changing status of the static pages assigned to the Site headers
		$sql_chstat = "UPDATE header_display_static SET header_display_static_hide = ".$_REQUEST['chstat']." WHERE id=".$chstat_arr[$i];
					$db->query($sql_chstat);
				}	
			}
			$alert = 'Successfully Changed the status for  the selected Page(s) assigned to the Site Header'; 
		}	
		show_assign_pages_list($_REQUEST['cur_header_id'],$alert);
}
else if($_REQUEST['fpurpose'] == 'list_assign_pages'){// to list the products to be assigned to the Site Headers
$header_id = $_REQUEST['checkbox'][0];
	include ('includes/site_headers/list_assign_pages.php');						
	
}
else if($_REQUEST['fpurpose'] == 'assign_pages_to_Site_Headers'){// to asign the Static Pages to the Site Headers
	
	$header_id = $_REQUEST['header_id'];
	{
		
		if ($_REQUEST['page_ids'] == '')
		{
			$alert = 'Sorry Pages not not selected';
		}
		else
		{ 
		
		$sql_assigned_pages = "SELECT static_pages_page_id FROM header_display_static WHERE site_headers_header_id =".$_REQUEST['header_id']." AND sites_site_id=".$ecom_siteid;
$res_assigned_pages = $db->query($sql_assigned_pages);
$assigned_pages_arr = array();
while($assigned_pages = $db->fetch_array($res_assigned_pages)){
$assigned_pages_arr[]= $assigned_pages['static_pages_page_id'];
}
			$pages_arr = explode("~",$_REQUEST['page_ids']);
			for($i=0;$i<count($pages_arr);$i++)
			{
				if(trim($pages_arr[$i]) && !in_array($pages_arr[$i],$assigned_pages_arr))
				{
					$insert_array = array();
					$insert_array['sites_site_id']=$ecom_siteid;
					$insert_array['site_headers_header_id']=$_REQUEST['header_id'];
					$insert_array['static_pages_page_id']=$pages_arr[$i];
					$db->insert_from_array($insert_array, 'header_display_static');
				}	
			}
			$alert = 'Static Page(s) Successfully assigned to Site headers'; 
		}						
	
	}
	$alert = '<center><font color="red"><b>'.$alert;
			$alert .= '</b></font></center>';
			echo $alert;
			?>
	<br /><a class="smalllink" href="home.php?request=site_headers&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>&start=<?=$_REQUEST['pass_start']?>&header_id=<?=$_REQUEST['header_id']?>" onclick="show_processing()">Go Back to the Site headers Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_headers&fpurpose=edit&header_id=<?=$_REQUEST['header_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>&pass_start=<?=$_REQUEST['pass_start']?>&curtab=statmenu_tab_td" onclick="show_processing()">Go Back to the Edit  this Site Header</a><br /><br />
			<a class="smalllink" href="home.php?request=site_header&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Site Header</a>		
			<?
	
}elseif($_REQUEST['fpurpose']=='delete_assign_pages') // section used for delete of Static Pages assigned to Site Headers using ajax
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		include ('../includes/site_headers/ajax/headers_ajax_functions.php');
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Pages  not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					// Deleting product from Site headers
					$sql_del = "DELETE FROM header_display_static WHERE id=".$del_arr[$i];
					$db->query($sql_del);
				}	
			}
			$alert = 'Pages Successfully Removed from the Site Header'; 
		}	
show_assign_pages_list($_REQUEST['cur_header_id'],$alert);
}
elseif($_REQUEST['fpurpose']=='remove_image_ajax')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	include ('../includes/site_headers/ajax/headers_ajax_functions.php');
	// Check whether caption exists for current site header
	// get the details of current header
	$sql_head = "SELECT header_filename,header_caption  
					FROM 
						site_headers 
					WHERE 
						header_id=".$_REQUEST['header_id']." 
						AND sites_site_id=".$ecom_siteid." 
					LIMIT 
						1";
	$ret_head = $db->query($sql_head);
	if($db->num_rows($ret_head))
	{
		$row_head = $db->fetch_array($ret_head);
		if($row_head['header_caption']!='')
		{
			$sql_del = "UPDATE  
							site_headers 
						SET 
							header_filename='' 
						WHERE 
							header_id=".$_REQUEST['header_id']." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
			$db->query($sql_del);
			$alert = 'Image Removed Successfully';
		}
		else
		{
			$alert = 'Sorry image cannot be removed as caption does not exits for current header. Please use delete option to remove the header';
		
		}
	}
	show_siteheaders_maininfo($_REQUEST['header_id'],$alert);
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
		$fieldRequired 		= array($_REQUEST['header_title']);
		$fieldDescription 	= array('Header  Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		if (!$_REQUEST['header_id'])//do only in case of insert
		{
			 if($_FILES['header_filename']['name']=='' && $_REQUEST['header_caption']=='')
			 {
					$fieldRequired[] 	= $_FILES['header_filename']['name'];
					$fieldDescription[] = array('Select File');
			 }		
		}	
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}
// =================================================================================
//					The common function to save the Site headers
// =================================================================================
function Save_SiteHeader($fname)
{
	global $db,$ecom_siteid;
	if($_REQUEST['dont_save']!=1) // case of handle the unwanted submission while coming to pages by clicking the links in action pages
	{
		if($_REQUEST['header_id']) //If site_header id is there then update else update
		{
			// ===============================================================================
			// 						Edit Section Starts over here
			// ===============================================================================
			
			if(!$alert)
			{//Updating the Site header table
			$update_array						= array();
			$update_array['sites_site_id'] 		= $ecom_siteid;
			$update_array['header_title'] 		= addslashes($_REQUEST['header_title']);
			$update_array['header_hide'] 		= ($_REQUEST['header_hide'])?1:0;
			$update_array['header_showinall'] 	= ($_REQUEST['header_showinall'])?1:0;
			if($_FILES['header_filename']['name']){
			
			$update_array['header_filename']	=	$fname;
			}
			$update_array['header_caption'] 	= add_slash($_REQUEST['header_caption']);
			if($_REQUEST['header_period_change_required']) //If periodic change is required
			{	
				$update_array['header_period_change_required']		= $_REQUEST['header_period_change_required'];
				$exp_header_displaystartdate=explode("-",$_REQUEST['header_startdate']);
				$val_header_displaystartdate=$exp_header_displaystartdate[2]."-".$exp_header_displaystartdate[1]."-".$exp_header_displaystartdate[0];
				$exp_header_displayenddate=explode("-",$_REQUEST['header_enddate']);
				$val_header_displayenddate=$exp_header_displayenddate[2]."-".$exp_header_displayenddate[1]."-".$exp_header_displayenddate[0];
				
				$val_header_displaystartdatetime     	=  $val_header_displaystartdate." ".$_REQUEST['header_starttime_hr'].":".$_REQUEST['header_starttime_mn'].":".$_REQUEST['header_starttime_ss'];
				$val_header_displayenddatetime  		=  $val_header_displayenddate." ".$_REQUEST['header_endtime_hr'].":".$_REQUEST['header_endtime_mn'].":".$_REQUEST['header_endtime_ss'];
				$update_array['header_startdate']		=  $val_header_displaystartdatetime;
				$update_array['header_enddate']			=  $val_header_displayenddatetime;
				
			}
			else 
			{
			$update_array['header_period_change_required']		= 0;
			}
			$db->update_from_array($update_array, 'site_headers', array('header_id' => $_REQUEST['header_id'] , 'sites_site_id' => $ecom_siteid));
		   }
		}else {
				
			// ===============================================================================
			// 						Insert Section Starts over here
			// ===============================================================================
			// Inserting to site headers Table
		 
			if(!$alert)
			{
			$insert_array					    	= array();
			$insert_array['sites_site_id'] 			= $ecom_siteid;
			$insert_array['header_title']			= addslashes($_REQUEST['header_title']);
			$insert_array['header_showinall'] 		= ($_REQUEST['header_showinall'])?1:0;
			if($_FILES['header_filename']['name']){
			$insert_array['header_filename']	=	$fname;
			}
			$insert_array['header_caption'] 	= add_slash($_REQUEST['header_caption']);		
			if($_REQUEST['header_period_change_required']) //If periodic change is required
			{	
				$insert_array['header_period_change_required']		= $_REQUEST['header_period_change_required'];
				$exp_header_displaystartdate=explode("-",$_REQUEST['header_startdate']);
				$val_header_displaystartdate=$exp_header_displaystartdate[2]."-".$exp_header_displaystartdate[1]."-".$exp_header_displaystartdate[0];
				$exp_header_displayenddate=explode("-",$_REQUEST['header_enddate']);
				$val_header_displayenddate=$exp_header_displayenddate[2]."-".$exp_header_displayenddate[1]."-".$exp_header_displayenddate[0];
				
				$val_header_displaystartdatetime     			=  $val_header_displaystartdate." ".$_REQUEST['header_starttime_hr'].":".$_REQUEST['header_starttime_mn'].":".$_REQUEST['header_starttime_ss'];
				$val_header_displayenddatetime  				=  $val_header_displayenddate." ".$_REQUEST['header_endtime_hr'].":".$_REQUEST['header_endtime_mn'].":".$_REQUEST['header_endtime_ss'];
				$insert_array['header_startdate']		=  $val_header_displaystartdatetime;
				$insert_array['header_enddate']			=  $val_header_displayenddatetime;

				
			}
			else 
			{
			$insert_array['header_period_change_required']		= 0;
			} 
			
			$db->insert_from_array($insert_array, 'site_headers');
			$insert_id = $db->insert_id();		
	
			return $insert_id;	
			
			// ===============================================================================
			// 						Insert Section Ends over here
			// ===============================================================================	
		
		  }
		}
	}
}

function validate_image($mod='')
{
	global $alert;
	if($_FILES['header_filename']['name'])
	{
		$ext_arr = explode(".",$_FILES['header_filename']['name']);
		$len = count($ext_arr)-1;
		$valid_arr = array('jpg','gif');
		if(!in_array(strtolower($ext_arr[$len]),$valid_arr))
		{
			$alert = 'Invalid Image Format';
		}
	}
	else
	{
		if($mod=='')//done to handle the situation of image not selected in case of edit
			$alert = 'Select file';
		
	}	
		
	if(!$alert)
		return true;
	else
		return false;
}

function save_image()
{
	global $image_path,$db,$ecom_siteid,$ecom_themeid;
	$img_id = uniqid('');
	if($_REQUEST['header_id'])
	{
		//Get the id part table
		
		$sql_site_header = "SELECT header_filename  FROM site_headers WHERE header_id=".$_REQUEST['header_id'];
		$ret_site_header = $db->query($sql_site_header);
		if ($db->num_rows($ret_site_header))
		{
			$row_site_header = $db->fetch_array($ret_site_header);
			
				@unlink ("$image_path/site_headers/".$row_site_header['header_filename']);
		}
	}
	//Find the geometry for headers
	$sql_geo = "SELECT headerimage_geometry FROM themes WHERE  theme_id=$ecom_themeid";
	$ret_geo = $db->query($sql_geo);
	if ($db->num_rows($ret_geo))
	{
		$res_geo = $db->fetch_array($ret_geo);
		$geometry['site_header'] = $res_geo['headerimage_geometry'];
	}
	$site_header_path = "$image_path/site_headers";
	
	if(!file_exists($site_header_path)) mkdir($site_header_path, 0777);
	$ext_arr = explode(".",$_FILES['header_filename']['name']);
	$len = count($ext_arr)-1;
	$filename = $img_id.".".$ext_arr[$len];
	if($_REQUEST['chk_resizeheader']==1)
		$Resizeme = 1;
	else
		$Resizeme = 2;
	$headermage_name = resize_image($_FILES['header_filename']["tmp_name"], "site_headers/".$filename, $geometry["site_header"], $_FILES['header_filename']["type"],$Resizeme); // last parameter changed from 1 to 2 for working in local
	if(!$headermage_name)
	{
		$ret_arr['alert'] 		= 'Upload Failed';
	}
	else
	{
			$ret_arr['alert'] 		= '';
			$ret_arr['extension'] 	= $ext_arr[$len];
			$ret_arr['filename'] 	= $headermage_name;
	}
	return $ret_arr;
}

//Function to resize and copy the uploaded files to required location
/*function resize_headerimage($old, $new, $geometry, $exten,$resize_me = 1)
	{
		global $image_path;
		
		// Probably not necessary, but might as well
		if(!is_uploaded_file($old)) return FALSE;

		if ($resize_me==1)
		{
		$command = "/usr/local/bin/convert \"$old\" -geometry \"$geometry\" -interlace Line \"$image_path/site_headers/$new\" 2>&1";
			//echo htmlentities($command) . "<br><br>";
	
			$p = popen($command, "r");
			$error = "";
			while(!feof($p)) {
				$s = fgets($p, 1024);
				$error .= $s;
			}
			$res = pclose($p);
	
			if($res == 0) return $new;
			else {
				echo ("Failed to resize image: $error<br>");
				return FALSE;
			}
		}
		else
		{
			$new_path = "$image_path/site_headers/$new";
			$res = move_uploaded_file($old,$new_path);
			if ($res)
				return $new;
			else
			{
				echo "Upload Failed";
				return FALSE;
			}
				
		}	
	} */
?>
