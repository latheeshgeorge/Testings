<?php
echo "<br><br><center style='font-size:16px;color:#FF0000'>Sorry! This feature is disabled at the moment.</center>";
exit;
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/general_downloads/list_general_downloads.php");
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
			$update_array['download_hide']	= $new_status;
			$header_id 						= $header_ids_arr[$i];	
			$db->update_from_array($update_array,'general_site_downloads',array('download_id'=>$header_id));
			
		}
		
		
		$alert = 'Status changed successfully.';
		include ('../includes/general_downloads/list_general_downloads.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Download File not selected';
		}
		else
		{
		
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					delete_image($del_arr[$i]);
				  	$sql_del = "DELETE FROM general_site_downloads WHERE download_id=".$del_arr[$i];
				  	$db->query($sql_del);
				}	
			}
			$alert = "Downloads deleted Sucessfully";
		}
		include ('../includes/general_downloads/list_general_downloads.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/general_downloads/add_download.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/site_headers/ajax/headers_ajax_functions.php');
		$header_id = $_REQUEST['checkbox'][0];
	include("includes/general_downloads/edit_download.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	if($_REQUEST['Submit'])
	{

	//Function to validate forms
	validate_forms();
	
	if($alert)
	{
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$header_id = $_REQUEST['checkbox'][0];
		include("includes/general_downloads/add_download.php");	
	}
	else
	{
		//Calling the function to save the Site header
		$download_id = Save_SiteHeader($add_filename);
		// Section to make entry to display_settings table
				
	?>
		<br>
		<font color="red"><b>General Downloads Added Successfully</b></font><br>
		<br /><a class="smalllink" href="home.php?request=general_downloads&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=general_downloads&fpurpose=edit&download_id=<?php echo $download_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Edit Page</a>
	    	<br />	<br />	<a class="smalllink" href="home.php?request=general_downloads&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Page</a>

	<?php
	}

	
	}
}
else if($_REQUEST['fpurpose'] == 'update') {  // for updating the Site headers

	if($_REQUEST['download_id'])
	{
		//Function to validate forms
		validate_forms();
		
				
	
		if (!$alert)
		{
		
		
			//Calling the function to save the property
			$header_id = Save_SiteHeader($add_filename);
				
			?>
			<br><font color="red"><b>General Downloads Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=general_downloads&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Listing page</a><br />
			<br />
			<a class="smalllink" href="home.php?request=general_downloads&fpurpose=edit&download_id=<?=$_REQUEST['download_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Edit Page</a><br />
			<br />
		<a class="smalllink" href="home.php?request=general_downloads&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Add Page</a>

			<?php
		}
		else
		{
		
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		include ('includes/site_headers/ajax/headers_ajax_functions.php');
		include_once("classes/fckeditor.php");
		$header_id = $_REQUEST['download_id'];
		include("includes/general_downloads/edit_download.php");
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Download Id</font><br />
		<br /><a class="smalllink" href="home.php?request=general_downloads&fpurpose=edit&download_id=<?=$_REQUEST['download_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the  Edit Page</a><br />
		<br />
			<a class="smalllink" href="home.php?request=general_downloads&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the  Listing page</a><br />
			<br />
			
		
	<?php	
	} //// updating Site header ends



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
		$fieldRequired 		= array($_REQUEST['download_title']);
		$fieldDescription 	= array('Download  Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		if ( !$_REQUEST['download_id'])//do only in case of insert
		{
				$fieldRequired[] 	= $_FILES['header_filename']['name'];
				$fieldDescription[] = array('Select File');
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
		if($_REQUEST['download_id']) //If site_header id is there then update else update
		{
			// ===============================================================================
			// 						Edit Section Starts over here
			// ===============================================================================
			
			if(!$alert)
			{//Updating the Site header table
				$update_array						= array();
				$update_array['download_title'] 		= addslashes(htmlspecialchars($_REQUEST['download_title']));
				$update_array['download_desc']			= addslashes($_REQUEST['download_desc']);
				$update_array['download_order'] 		= $_REQUEST['download_order'];
				if($_FILES['header_filename']['name']){
					$update_array['download_orgfilename']	=	$_FILES['header_filename']['name'];
					save_image($_REQUEST['download_id']);
				}
			
			$db->update_from_array($update_array, 'general_site_downloads', array('download_id' => $_REQUEST['download_id'] , 'sites_site_id' => $ecom_siteid));
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
			$insert_array['download_title']			= addslashes(htmlspecialchars($_REQUEST['download_title']));
			$insert_array['download_desc']			= addslashes($_REQUEST['download_desc']);
			$insert_array['download_order'] 		= $_REQUEST['download_order'];
			$insert_array['download_orgfilename']	= $_FILES['header_filename']['name'];
					
						
			$db->insert_from_array($insert_array, 'general_site_downloads');
			$insert_id = $db->insert_id();		
			save_image($insert_id);
			return $insert_id;	
			
			// ===============================================================================
			// 						Insert Section Ends over here
			// ===============================================================================	
		
		  }
		}
	}
}


function save_image($download_id)
{
	global $image_path,$db,$ecom_siteid,$ecom_themeid;
	$img_id = uniqid('');
	//Get the id part table
	$sql_site_header = "SELECT download_internalfilename  FROM general_site_downloads WHERE download_id=".$download_id;
	$ret_site_header = $db->query($sql_site_header);
	if ($db->num_rows($ret_site_header))
	{
		$row_site_header = $db->fetch_array($ret_site_header);
		@unlink ("$image_path/general_downloads/".$row_site_header['download_internalfilename']);
	}
	
	$site_header_path = "$image_path/general_downloads";
	
	if(!file_exists($site_header_path)) mkdir($site_header_path, 0777);
	$ext_arr = explode(".",$_FILES['header_filename']['name']);
	$len = count($ext_arr)-1;
	$filename = $img_id."-gen-download.".$ext_arr[$len];
	$db->query("UPDATE general_site_downloads SET download_internalfilename='".$filename."' WHERE download_id=".$download_id);
	move_uploaded_file($_FILES['header_filename']["tmp_name"], $site_header_path."/".$filename);
	return true;
}

function delete_image($download_id)
{
	global $image_path,$db,$ecom_siteid,$ecom_themeid;
	
	//Get the id part table
	$sql_site_header = "SELECT download_internalfilename  FROM general_site_downloads WHERE download_id=".$download_id;
	$ret_site_header = $db->query($sql_site_header);
	if ($db->num_rows($ret_site_header))
	{
		$row_site_header = $db->fetch_array($ret_site_header);
		@unlink ("$image_path/general_downloads/".$row_site_header['download_internalfilename']);
	}
	
	return true;
}
?>
